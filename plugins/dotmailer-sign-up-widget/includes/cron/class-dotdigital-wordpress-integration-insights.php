<?php

/**
 * Builds integration insight data schema.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_Wordpress\Includes\Cron;

use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
use Dotdigital_WordPress\Includes\Client\Dotdigital_Wordpress_Insight_Data;
class Dotdigital_Wordpress_Integration_Insights
{
    private const PAGES_AND_FORMS_WIDGET = 'dotdigital-for-wordpress/pages-and-forms';
    private const SIGNUP_FORM_DEFAULT_OPTIONS = array('showtitle' => \true, 'showdesc' => \true, 'redirecturl' => '');
    private const PAGES_AND_FORMS_DEFAULT_OPTIONS = array('formStyle' => 'embedded', 'selectedOption' => '');
    /**
     * @var Dotdigital_Wordpress_Insight_Data
     */
    private $insight_data;
    /**
     * Dotdigital_Wordpress_Integration_Insights constructor.
     */
    public function __construct()
    {
        $this->insight_data = new Dotdigital_Wordpress_Insight_Data();
    }
    /**
     * Send integration insights.
     *
     * @return void
     */
    public function send_integration_insights()
    {
        global $wp_version;
        $schema = array();
        $schema['recordId'] = site_url() . '?plugin_name=dotdigital-for-wordpress';
        $schema['platform'] = 'WordPress';
        $schema['version'] = $wp_version;
        $schema['connectorVersion'] = DOTDIGITAL_WORDPRESS_VERSION;
        $schema['phpVersion'] = \phpversion();
        $schema['lastUpdated'] = current_datetime()->format('Y-m-d H:i:s');
        $schema['configuration'] = $this->get_configuration();
        $schema['widgets'] = $this->get_dotdigital_widgets();
        $this->insight_data->post($schema);
    }
    /**
     * Get all active dotdigital widgets.
     *
     * @return array
     */
    private function get_dotdigital_widgets()
    {
        $widget_options = array();
        $available_widgets = $this->get_available_widgets();
        $widget_instances = array();
        foreach ($available_widgets as $widget_data) {
            $instances = get_option('widget_' . $widget_data['id_base']);
            if (!empty($instances)) {
                foreach ($instances as $instance_id => $instance_data) {
                    if (\is_numeric($instance_id)) {
                        $unique_instance_id = $widget_data['id_base'] . '-' . $instance_id;
                        $widget_instances[$unique_instance_id] = $instance_data;
                    }
                }
            }
        }
        $sidebars_widgets = get_option('sidebars_widgets');
        $sidebars_widget_instances = array();
        foreach ($sidebars_widgets as $sidebar_id => $widget_ids) {
            if ('wp_inactive_widgets' === $sidebar_id) {
                continue;
            }
            if (!\is_array($widget_ids) || empty($widget_ids)) {
                continue;
            }
            foreach ($widget_ids as $widget_id) {
                if (isset($widget_instances[$widget_id])) {
                    // Add to array.
                    $sidebars_widget_instances[$sidebar_id][$widget_id] = $widget_instances[$widget_id];
                    if (isset($sidebars_widget_instances[$sidebar_id][$widget_id]['content']) && \strpos($sidebars_widget_instances[$sidebar_id][$widget_id]['content'], 'dotdigital')) {
                        $widget_options['widget-' . \count($widget_options)] = $this->get_dotdigital_widget_options($sidebars_widget_instances[$sidebar_id][$widget_id]['content'], $sidebar_id);
                    } elseif (\false !== \strpos($widget_id, 'dm_widget')) {
                        $widget_options['widget-' . \count($widget_options)] = array('widget_name' => 'Dotdigital Signup Form [Legacy]', 'widget_area' => $sidebar_id);
                    }
                }
            }
        }
        return $widget_options;
    }
    /**
     * Get all the active available widgets.
     *
     * @return array
     */
    private function get_available_widgets()
    {
        global $wp_registered_widget_controls;
        $widget_controls = $wp_registered_widget_controls;
        $available_widgets = array();
        foreach ($widget_controls as $widget) {
            if (!empty($widget['id_base']) && !isset($available_widgets[$widget['id_base']])) {
                $available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
                $available_widgets[$widget['id_base']]['name'] = $widget['name'];
            }
        }
        return $available_widgets;
    }
    /**
     * Get dotdigital widget options.
     *
     * @param string $widget_content Widget content in xml format.
     * @param string $widget_area Widget area.
     * @return array
     */
    private function get_dotdigital_widget_options($widget_content, $widget_area)
    {
        $widget_data = array();
        $widget_options = parse_blocks($widget_content);
        foreach ($widget_options as $widget_option) {
            $widget_data['widget_name'] = $widget_option['blockName'];
            $widget_data['widget_area'] = $widget_area;
            $widget_data['widget_options'] = $this->process_widget_options($widget_option);
        }
        return $widget_data;
    }
    /**
     * Process widget options.
     *
     * @param array $options Widget options.
     * @return array|string[]
     */
    private function process_widget_options($options)
    {
        switch ($options['blockName']) {
            case self::PAGES_AND_FORMS_WIDGET:
                return $this->get_pages_and_forms_options($options['attrs']);
            default:
                return $this->get_signup_form_options($options['attrs']);
        }
    }
    /**
     * Get saved options for pages and forms widget.
     *
     * @param array $attributes Widget attributes.
     * @return array|string[]
     */
    private function get_pages_and_forms_options($attributes)
    {
        $diff = \array_diff($attributes, self::PAGES_AND_FORMS_DEFAULT_OPTIONS);
        $options = \array_merge(self::PAGES_AND_FORMS_DEFAULT_OPTIONS, $diff);
        if ('embedded' === $options['formStyle']) {
            unset($options['showAfter']);
            unset($options['showMobile']);
            unset($options['useEsc']);
            unset($options['dialogWidth']);
            unset($options['stopDisplaying']);
        } else {
            $options['showAfter'] = $options['showAfter'] ?? 0;
            $options['showMobile'] = $options['showAfter'] ?? \false;
            $options['useEsc'] = $options['useEsc'] ?? \false;
            $options['dialogWidth'] = $options['dialogWidth'] ?? 600;
            $options['stopDisplaying'] = $options['stopDisplaying'] ?? 'fc';
        }
        return $options;
    }
    /**
     * Get saved options for signup forms widget.
     *
     * @param array $attributes Widget attributes.
     * @return array
     */
    private function get_signup_form_options($attributes)
    {
        if (!$attributes) {
            return self::SIGNUP_FORM_DEFAULT_OPTIONS;
        }
        return \array_merge(self::SIGNUP_FORM_DEFAULT_OPTIONS, $attributes);
    }
    /**
     * Get dotdigital configuration.
     *
     * @return array
     */
    private function get_configuration()
    {
        $credentials = get_option(Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH);
        $api_user = $credentials[Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH_USERNAME];
        $lists = get_option(Dotdigital_WordPress_Config::SETTING_LISTS_PATH);
        $datafields = get_option(Dotdigital_WordPress_Config::SETTING_DATAFIELDS_PATH);
        $redirection = get_option(Dotdigital_WordPress_Config::SETTING_REDIRECTS_PATH);
        $configuration['api_user'] = $api_user ?? array();
        $configuration['datafields'] = \false !== $datafields ? $this->trim_array_keys(\array_map(function ($datafield) {
            return $datafield['name'];
        }, $datafields)) : array();
        $configuration['lists'] = \false !== $lists ? \array_combine(\array_column($lists, 'id'), \array_reverse(\array_keys($lists))) : array();
        $configuration['redirection'] = \false !== $redirection ? $this->process_redirection($redirection) : '';
        return array($configuration);
    }
    /**
     * Trim array keys.
     *
     * @param array $array Array to trim the keys.
     * @return array
     */
    private function trim_array_keys($array)
    {
        $result = array();
        foreach ($array as $key => $value) {
            $trimmed_key = \str_replace(' ', '', $key);
            $result[$trimmed_key] = $value;
        }
        return $result;
    }
    /**
     * Process redirection.
     *
     * @param array $redirection redirection info.
     * @return array|string[]
     */
    private function process_redirection($redirection)
    {
        $redirection_type = \array_keys($redirection);
        switch (\reset($redirection_type)) {
            case 'url':
                return array('url' => $redirection['url']);
            case 'noRedirection':
                return array('url' => 'noRedirection');
            default:
                return array('page' => (string) \reset($redirection));
        }
    }
}
