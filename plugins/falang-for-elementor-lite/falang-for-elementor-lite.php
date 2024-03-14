<?php
/*
 * Plugin Name: Falang for Elementor Lite
 * Description: Manage translation for Elementor with Falang
 * Author: Faboba
 * Author URI: https://www.faboba.com
 * Version: 1.20
 * Elementor tested up to: 3.19.4
 * Elementor Pro tested up to: 3.19.3
*/

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Plugin;

class FalangElementor
{

    private static $elements = array(); //list of widgets translatable
    private static $elements_pro = array(); //list of widgets in pro version to display lite message

    var $language_list; //list of active language (Falang or QTranslate)
    var $current_language; //list of active language (Falang or QTranslate)
    var $default_language;

    private $admin_notices;

    /**
     * @var static
     */
    protected static $instance;


    function __construct()
    {

        define('FALANG_ELEMENTOR_FILE', __FILE__); // plugin name as known by WP
        define('FALANG_ELEMENTOR_LITE_DIR', dirname(__FILE__)); // plugin name as known by WP
        define('FALANG_ELEMENTOR_LITE_PLUGIN_BASE', plugin_basename(FALANG_ELEMENTOR_FILE));

        //Falang must be active.
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        if (!is_plugin_active('falang/falang.php')) {
            add_action('admin_notices', array($this, 'falang_fail_load'));
            return;
        }

        //TODO use autoload
        require_once FALANG_ELEMENTOR_LITE_DIR . '/admin/admin-notices.php';

        //load all widgets
        add_action('plugins_loaded', array($this, 'init_widgets'));

        add_action('init', array($this, 'init'));
    }

    /**
     * Gets global falandivi.
     * @param null|mixed $cache
     *
     * @return static
     * @since 1.5
     *
     */
    public static function getInstance($cache = null)
    {
        return static::$instance ?: static::$instance = new static();
    }

    /**
     * @since 1.8 add falang_is_supported_builder
     * @update 1.9 add message on lite version for supported widget on pro
     */
    function init()
    {

        //init languages
        $this->default_language = $this->get_default_language();
        $this->language_list = $this->get_languages_list(array('hide_default' => true));
        $this->current_language = $this->get_current_language();

        //load admin notice
        $this->admin_notices = new Falang\Elementor\Admin\Admin_Notices();

        //backend
        //after section use for simple item
        add_action('elementor/element/after_section_end', array($this, 'after_section_end'), 10, 2);

        //elementor pro not published display message on each pro widget to upgrade
        //test edit mode in the after_section_end_add_free_message function
        if (!$this->is_falang_for_elementor_pro_activated()) {
            add_action('elementor/element/after_section_end', array($this, 'after_section_end_add_free_message'), 10, 2);
        }

        //frontend translation
        add_action('elementor/frontend/widget/before_render', array($this, 'frontend_widget_before_render'), 9, 1);

        //happy elementor wrapper link fix
        //lastudio fix wrapper link
        //powerpro pack wrapper link and custom cursor
        if (is_plugin_active('happy-elementor-addons/plugin.php') ||
            is_plugin_active('lastudio/lastudio.php') ||
            is_plugin_active('bdthemes-element-pack-lite/bdthemes-element-pack-lite.php') ||
            is_plugin_active('powerpack-elements/powerpack-elements.php') ||
            is_plugin_active('lastudio-element-kit/lastudio-element-kit.php')) {
            add_action('elementor/frontend/before_render', array($this, 'frontend_widget_before_render'), 1, 1);
        }
        //essential-addons-for-elementor-lite/includes/Extensions/Wrapper_Link.php
        if (is_plugin_active('essential-addons-for-elementor-lite/essential_adons_elementor.php')) {
            $this->fix_wrapper_link_eael();
        }

        //remove content translation for Elementor page
        add_filter('falang_is_supported_builder', array($this, 'falang_is_supported_builder'), 10, 2);
    }

    function add_languages_module_tabs($tabs)
    {
        $tabs['falang'] = esc_html__('Falang', 'falang-elementor-lite');
        return $tabs;
    }

    /**
     * get current language from Falang
     * @since 1.3
     *
     */
    function get_current_language()
    {
        return Falang()->get_current_language();
    }

    function get_default_language()
    {
        return Falang()->get_model()->get_default_language();
    }

    function get_languages_list($args = array())
    {
        return Falang()->get_model()->get_languages_list($args);
    }


    /*
     * @since 1.0
     * @update 1.9 load pro version list to display message
     * */
    function init_widgets()
    {
        $this->loadConfigElements(FALANG_ELEMENTOR_LITE_DIR . '/widgets/*.json');
        if (!$this->is_falang_for_elementor_pro_activated()) {
            $this->loadProMessageElements(FALANG_ELEMENTOR_LITE_DIR . '/pro/*.json');
        }
    }

    /*
     * @since 1.5 need to be public for Falang for Elementor Pro
     * */
    public function loadConfigElements($path)
    {
        //load each element supported for translation
        $files = glob($path);

        foreach ($files as $file) {
            if (is_file($file)) {
                $element = $this->loadJsonFile($file);
                if (isset($element['widget_name'])) {
                    static::$elements[$element['widget_name']] = $element;
                }
            }
        }
    }

    /*
    * @since 1.9 load pro element to display message on lite version
    * */
    public function loadProMessageElements($path)
    {
        //load each element supported for translation
        $files = glob($path);
        foreach ($files as $file) {
            if (is_file($file)) {
                $this->mergeProMessageElements($file);
            }
        }
    }

    /*
    * @since 1.9 load pro element to display message on lite version
    * */
    private function mergeProMessageElements($file)
    {
        $elements = $this->loadJsonFile($file);
        if (isset(static::$elements_pro['single'])) {
            static::$elements_pro['single'] = array_merge(static::$elements_pro['single'], $elements['single']);
        } else {
            static::$elements_pro['single'] = $elements['single'];
        }
        if (isset(static::$elements_pro['repeatable'])) {
            static::$elements_pro['repeatable'] = array_merge(static::$elements_pro['repeatable'], $elements['repeatable']);
        } else {
            static::$elements_pro['repeatable'] = $elements['repeatable'];
        }
    }


    /**
     * Loads a bootstrap config.
     *
     * @param string $file
     * @param array $parameters
     *
     * @return array
     */
    protected function loadJsonFile($file)
    {
        if (!$content = @file_get_contents($file)) {
            throw new RuntimeException("Unable to load file '{$file}'");
        }

        if (!is_array($value = @json_decode($content, true))) {
            throw new RuntimeException("Invalid JSON format in '{$file}'");
        }

        return $value;
    }

    public function getElements()
    {
        return static::$elements;
    }

    /**
     * @param $name
     * @param $language
     *
     * @return string
     */
    private function getTranslatedFieldName($name, $language)
    {
        return $name . '_' . strtolower($language->locale);
    }

    /*
     * backend method use to add translated field after the original section
     * @since 1.4 - use arrays of sections in json
     *            - add display of label before flag for section
     * @update 1.11 add options for single elements user for wpforms
     * @update 1.16 fix warning when label is not set like section
     * @update 1.17 fix warning when label is not set in section_control
     * @update 1.19 fix option on link , change the way the extra param's are added in the array
     * @update 1.20 fix typo eror in the new extra param's way
     * */
    function after_section_end($section, $section_id)
    {
        $widget_name = $section->get_name();
        $elts = $this->getElements();

        if (!isset($elts[$widget_name]['sections'])) {
            return;
        }

        $sections = $elts[$widget_name]['sections'];

        foreach ($sections as $falang_section) {

            //need to have a section_name
            if (!isset($falang_section['section_name'])) {
                continue;
            }
            //can start with 'section_' due to extra widget like xtempos
            $section_name = $falang_section['section_name'];

            if ($section_name === $section_id) {

                //try override only on supported module
                //module are all in lowercase
                if (!array_key_exists($widget_name, $elts)) {
                    return;
                }

                $section_control = $section->get_controls($section_name);

                #Start Custom Settings Section
                foreach ($this->language_list as $language) {
                    $language_slug = sanitize_key('_' . $language->locale);
                    $flag = $language->get_flag();
                    $section_name_locale = $section_name . '<span>' . $flag . '</span>';

                    //single item


                    //add simple item (need to add a section)
                    if (isset($falang_section['control']) && !empty($falang_section['control'])) {

                        //add section
                        $section_label = isset($section_control['label']) ?  $section_control['label']:'';
                        $section->start_controls_section(
                            $section_name_locale,
                            [
                                //'label' => __( $section_control['label'].' '.$language->locale, 'falang' ),
                                'label' => '<span style="font-weight: 400;">' . $section_label . ' ' . $flag . '</span>',
                                'tab' => $section_control['tab'],
                            ]
                        );

                        foreach ($falang_section['control'] as $key) {
                            $control = $section->get_controls($key);
                            $control_title = $control['name'] . $language_slug;
                            $label = isset($control['label']) ?  $control['label']:'';

                            //build translated controls
                            $newControl = [
                                'label' => trim($label. ' ' . $language->locale),
                                'type' => $control['type'],
                            ];
                            if (isset($control['placeholder'])){$newControl['placeholder'] = $control['placeholder'];}
                            if (isset($control['options'])){$newControl['options'] = $control['options'];}
                            if (isset($control['label_block'])){$newControl['label_block'] = $control['label_block'];}

                            //add new control to the section
                            $section->add_control(
                                $control_title,$newControl
                            );
                        }

                        #End Custom Settings Section
                        $section->end_controls_section();
                    }

                    //add repeatable item no section added all are aded in sub item.
                    if (isset($falang_section['repeatable']) && !empty($falang_section['repeatable'])) {
                        $repeatable_name = array_keys($falang_section['repeatable'][0])[0];

                        // Get existing control
                        $control = $section->get_controls($repeatable_name);

                        if (is_wp_error($control)) {
                            return;
                        }

                        foreach ($falang_section['repeatable'][0][$repeatable_name] as $key) {
                            //construct elt to add
                            $label = isset($control['fields'][$key]['label'])?$control['fields'][$key]['label']:"";
                            $newControl =
                                [
                                    'name' => $control['fields'][$key]['name'] . $language_slug,
                                    'label' => $label . '&nbsp;<span>' . $flag . '</span>',
                                    'type' => $control['fields'][$key]['type']
                                ];
                            if (isset($control['fields'][$key]['placeholder'])){$newControl['placeholder'] = $control['fields'][$key]['placeholder'];}
                            if (isset($control['fields'][$key]['label_block'])){$newControl['label_block'] = $control['fields'][$key]['label_block'];}

                            $control['fields'][$this->getTranslatedFieldName($key, $language)] = $newControl;
                        }

                        $section->update_control($repeatable_name, $control);

                    }//end repeatable
                    //add


                }//end languages
            }
        } //end foreach

    }

    /*
     * Translate the content on front-end
     * @since 1.4 support array of sections
     * @since 1.13 fix image translation (not necessary to be translated)
     * */
    function frontend_widget_before_render(Element_Base $element)
    {
        $widget_name = $element->get_name();
        $elts = $this->getElements();

        if (!isset($elts[$widget_name]['sections'])) {
            return;
        }

        $sections = $elts[$widget_name]['sections'];

        foreach ($sections as $falang_section) {
            //try override only on supported module
            //module are all in lowercase
            if (array_key_exists($widget_name, $elts)) {
                $current_language = Falang()->get_current_language();
                //manage sinple widget
                if (isset($falang_section['control']) && !empty($falang_section['control'])) {
                    if ($current_language->locale != $this->get_default_language()->locale) {
                        foreach ($falang_section['control'] as $key) {
                            $settings_key = $this->getTranslatedFieldName($key, $current_language);
                            $org_element = $element->get_settings($key);
                            $trans_element = $element->get_settings($settings_key);
                            if (!is_array($org_element)) {//default case not an array
                                if ($trans_element) {
                                    $element->set_settings($key, $trans_element);
                                }
                            } else {
                                //image supported actually need to use url
                                if (isset($trans_element['url']) && !empty($trans_element['url'])) {
                                    $element->set_settings($key, $trans_element);
                                }
                            }
                        }
                    }
                }

                //manage repeatable item
                if (isset($falang_section['repeatable']) && !empty($falang_section['repeatable'])) {
                    $repeatable_name = array_keys($falang_section['repeatable'][0])[0];
                    $repeatable = $element->get_settings($repeatable_name);

                    foreach ($falang_section['repeatable'][0][$repeatable_name] as $key) {
                        foreach ($repeatable as $index => $repeat) {
                            $settings_key = $this->getTranslatedFieldName($key, $current_language);
                            if (isset($repeat[$settings_key]) && !empty($repeat[$settings_key])) {
                                $repeatable[$index][$key] = $repeat[$settings_key];
                            }
                        }
                    }
                    //override repeatable item with translation
                    $element->set_settings($repeatable_name, $repeatable);
                }

            }
        }
    }

    /**
     * Show in WP Dashboard notice about the plugin Falang is not activated.
     *
     * @return void
     * @since 1.3
     *
     */
    public function falang_fail_load()
    {
        $screen = get_current_screen();
        if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
            return;
        }

        $plugin = 'falang/falang.php';

        if ($this->is_falang_installed()) {
            if (!current_user_can('activate_plugins')) {
                return;
            }

            $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);

            $message = '<p>' . __('Falang for Elementor is not working because you need to activate the Falang plugin.', 'falang-for-elementor-lite') . '</p>';
            $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $activation_url, __('Activate Falang Now', 'falang-for-elementor-lite')) . '</p>';
        } else {
            if (!current_user_can('install_plugins')) {
                return;
            }

            $install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=falang'), 'falang-for-elementor-lite');

            $message = '<p>' . __('Falang for Elementor Lite is not working because you need to install the Falang plugin.', 'falang-for-elementor-lite') . '</p>';
            $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $install_url, __('Install Falang Now', 'falang-for-elementor-lite')) . '</p>';
        }

        echo '<div class="error"><p>' . $message . '</p></div>';
    }

    private function is_falang_installed()
    {
        $file_path = 'falang/falang.php';
        $installed_plugins = get_plugins();

        return isset($installed_plugins[$file_path]);
    }


    /**
     * @since 1.9 check if falang for elementor pro is active
     *
     */
    private function is_falang_for_elementor_pro_activated()
    {
        return is_plugin_active('falang-for-elementor/falang-for-elementor.php');
    }

    /**
     * @return false if not supported , Elementor if supported (use for display)
     * @since 1.8 add builder name use to
     */
    public function falang_is_supported_builder($return, $post)
    {
        if (in_array('elementor/elementor.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            if (Plugin::$instance->documents->get($post->ID)->is_built_with_elementor()) {
                $return = 'Elementor';
            }
        }
        return $return;
    }

    /**
     * @since 1.9 add message on addon's not supported in the lite
     * @since 1.18 fix warning message $section_label['label'] on front-end (should not be called)
     *             Only add the free message on the edit Mode
     *
     */
    public function after_section_end_add_free_message($section, $section_id)
    {
        if (!$this->is_edit_mode()){
            return;
        }

        if (!isset(static::$elements_pro)) {
            return;
        }
        $elements = static::$elements_pro;
        if (!isset($elements['single']) && !isset($elements['repeatable'])) {
            return;
        }

        $single_elts = $elements['single'];
        $name = $section->get_name();
        if (isset($single_elts) && isset($name) && isset($single_elts[$name])) {
            if ($single_elts[$name] === $section_id) {
                $section_name = $single_elts[$name];
                $section_label = $section->get_controls($section_name);

                #Start Custom Settings Section
                foreach ($this->language_list as $language) {
                    $language_slug = sanitize_key('_' . $language->locale);
                    $flag = $language->get_flag();
                    $section_name_locale = $section_name . '<span>' . $flag . '</span>';

                    //for simple item
                    //add section
                    $section->start_controls_section(
                        $section_name_locale,
                        [
                            //'label' => '<span style="font-weight: 400;">' . (isset($section_label['label']) ? $section_label['label'] : '')  . ' ' . $flag . '</span>',
                            'label' => '<span style="font-weight: 400;">' . $section_label['label'] . ' ' . $flag . '</span>',
                            'tab' => $section_label['tab'],
                        ]
                    );

                    $section->add_control(
                        $section_name . $language_slug,
                        [
                            'raw' => esc_html__('The Falang for Elementor Pro is needed to translate this widget.', 'falang-for-elementor-lite'),
                            'type' => Controls_Manager::RAW_HTML,
                            'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
                        ]
                    );

                    $section->end_controls_section();

                }
            }
        }

        //widget-name => [section,repeatable-item_name]
        $repeatable_elts = $elements['repeatable'];
        if (isset($repeatable_elts) && isset($name) && isset($repeatable_elts[$name])) {
            $key = $repeatable_elts[$name][0];
            $repeatable_section = $repeatable_elts[$name][1];;

            if ($key === $section_id) {

                // Get existing control
                $control = $section->get_controls($repeatable_section);

                if (is_wp_error($control)) {
                    return;
                }
                //construct elt to add (one for all languages)
                $elt =
                    [
                        'raw' => esc_html__('The Falang for Elementor Pro is needed to translate this widget.', 'falang-for-elementor-lite'),
                        'type' => Controls_Manager::RAW_HTML,
                        'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
                        'name' => $key. '_fake',//fake name to remove notice
                    ];
                //$control['fields'][$this->getTranslatedFieldName($key, $language)] = $elt;
                $control['fields'][$key . '_fake'] = $elt;

                $section->update_control($repeatable_section, $control);

            }

        }
    }

    /**
     * remove the Essentiol Addons Elementor Wrapper Link action for before render priority 1
     * because it's break the translation and put before the falang for Elementor Lite before_render action.
     *
     * @return void
     * @since 1.14
     *
     */
    public function fix_wrapper_link_eael(){
        global $wp_filter;
        global $wp_current_filter;
        //see https://brickslabs.com/removing-action-filter-inside-a-plugins-namespaced-class-constructor/
        $hook = 'elementor/frontend/before_render';
        $class = 'Essential_Addons_Elementor\Extensions\Wrapper_Link';
        if (isset($wp_filter[$hook])){
            $filter = $wp_filter[$hook];
            foreach ( $filter as $priority => $callbacks ) {
                // loop through all the callbacks for the current priority
                foreach ( $callbacks as $callback ) {
                    // check if the callback is an array and the first element is the Eventin class
                    if ( is_array( $callback['function'] ) && $callback['function'][0] instanceof $class ) {
                        // remove the callback
                        remove_filter( $hook, $callback['function'], $priority );
                    }
                }
            }
        }
        //add falang for Elementor front-end translation
        add_action('elementor/frontend/before_render', array($this, 'frontend_widget_before_render'), 1, 1);

        if (isset($wp_filter[$hook])){
            add_action('elementor/frontend/before_render', array('Essential_Addons_Elementor\Extensions\Wrapper_Link','before_render'), 1);
        }

    }

    /*
     * @from 1.18
     * Check if we are on Edit mode
    */
    public function is_edit_mode(){
        return Plugin::$instance->editor->is_edit_mode();
    }

}

$FalangElementor = FalangElementor::getInstance();