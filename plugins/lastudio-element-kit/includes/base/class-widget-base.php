<?php

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use LaStudioKitExtensions\Elementor\Controls\Group_Control_Box_Style;

abstract class LaStudioKit_Base extends Widget_Base
{

    public $_context = 'render';
    public $_processed_item = false;
    public $_processed_index = 0;
    public $_load_level = 100;
    public $_include_controls = [];
    public $_exclude_controls = [];
    public $_new_icon_prefix = 'selected_';

    public $_paged_key = '';

    /**
     * [__construct description]
     * @param array $data [description]
     * @param [type] $args [description]
     */
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->_load_level = (int)lastudio_kit_settings()->get('widgets_load_level', 100);

        $widget_name = $this->get_name();

        $this->_include_controls = apply_filters("lastudio-kit/editor/{$widget_name}/include-controls", [], $widget_name, $this);

        $this->_exclude_controls = apply_filters("lastudio-kit/editor/{$widget_name}/exclude-controls", [], $widget_name, $this);

        $this->enqueue_addon_resources();
    }

    protected function enqueue_addon_resources() { }

    protected function get_html_wrapper_class()
    {
        return 'lastudio-kit elementor-' . $this->get_name();
    }

    protected function get_widget_title()
    {
        return '';
    }

    protected function get_lakit_name(){
        return str_replace('lakit-', '', $this->get_name());
    }

    public function get_title()
    {
        return 'LaStudioKit ' . $this->get_widget_title();
    }

    public function get_categories()
    {
        return ['lastudiokit'];
    }

    /**
     * [get_kit_help_url description]
     * @return [type] [description]
     */
    public function get_kit_help_url()
    {
        return false;
    }

    /**
     * [get_help_url description]
     * @return [type] [description]
     */
    public function get_help_url()
    {

        $url = $this->get_kit_help_url();

        $style_parent_theme = wp_get_theme(get_template());

        $author_slug = strtolower(preg_replace('/\s+/', '', $style_parent_theme->get('Author')));

        if (!empty($url)) {
            return add_query_arg(
                array(
                    'utm_source' => $author_slug,
                    'utm_medium' => 'lastudiokit' . '_' . $this->get_name(),
                    'utm_campaign' => 'need-help',
                ),
                esc_url($url)
            );
        }

        return false;
    }

	/**
     * [allow change the widget template output]
	 * @return string
	 */
    protected function set_template_output(){
        return lastudio_kit()->plugin_path('templates');
    }

	/**
     * @description get the widget output file
	 * @param $name
	 *
	 * @return false|string
	 */
    protected function get_template_output( $name = null ){

	    $template = locate_template( lastudio_kit()->template_path() . $name );

	    if ( ! $template ) {
            $template = $this->set_template_output() . '/' . $name;
	    }

	    if ( file_exists( $template ) ) {
		    return $template;
	    } else {
		    return false;
	    }
    }

    /**
     * Get globaly affected template
     *
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function _get_global_template($name = null)
    {

        $widget_name = str_replace(['lakit-', 'lastudio-kit-'], '', $this->get_name());

        $template = call_user_func(array($this, sprintf('_get_%s_template', $this->_context)), $name);

        if (!$template) {
            $template = $this->get_template_output($widget_name . '/global/' . $name . '.php');
        }

        return $template;
    }

    /**
     * Get front-end template
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function _get_render_template($name = null)
    {
	    $widget_name = str_replace(['lakit-', 'lastudio-kit-'], '', $this->get_name());
        return $this->get_template_output($widget_name . '/render/' . $name . '.php');
    }

    /**
     * Get editor template
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function _get_edit_template($name = null)
    {
	    $widget_name = str_replace(['lakit-', 'lastudio-kit-'], '', $this->get_name());
        return $this->get_template_output($widget_name . '/edit/' . $name . '.php');
    }

    /**
     * Load template directly
     * @param $template
     * @access protected
     */
    protected function _load_template($template)
    {
        include $template;
    }

    /**
     * Get global looped template for settings
     * Required only to process repeater settings.
     *
     * @param string $name Base template name.
     * @param string $setting Repeater setting that provide data for template.
     * @param string $callback Callback for preparing a loop array
     * @return void
     */
    public function _get_global_looped_template($name = null, $setting = null, $callback = null)
    {

        $templates = array(
            'start' => $this->_get_global_template($name . '-loop-start'),
            'loop' => $this->_get_global_template($name . '-loop-item'),
            'end' => $this->_get_global_template($name . '-loop-end'),
        );

        call_user_func(
            array($this, sprintf('_get_%s_looped_template', $this->_context)), $templates, $setting, $callback
        );

    }

    /**
     * Get render mode looped template
     *
     * @param array $templates [description]
     * @param string $setting [description]
     * @param string $callback Callback for preparing a loop array
     * @return void
     */
    public function _get_render_looped_template($templates = array(), $setting = null, $callback = null)
    {

        $loop = $this->get_settings_for_display($setting);
        $loop = apply_filters('lastudio-kit/widget/loop-items', $loop, $setting, $this);

        if (empty($loop)) {
            return;
        }

        if ($callback && is_callable($callback)) {
            $loop = call_user_func($callback, $loop);
        }

        if (!empty($templates['start'])) {
            include $templates['start'];
        }

        foreach ($loop as $item) {

            $this->_processed_item = $item;

            if (!empty($templates['loop'])) {
                include $templates['loop'];
            }
            $this->_processed_index++;
        }

        $this->_processed_item = false;
        $this->_processed_index = 0;

        if (!empty($templates['end'])) {
            include $templates['end'];
        }

    }

    /**
     * Get edit mode looped template
     *
     * @param array $templates [description]
     * @param  [type] $setting   [description]
     * @return [type]            [description]
     */
    public function _get_edit_looped_template($templates = array(), $setting = null)
    {
        ?>
      <# if ( settings.<?php echo $setting; ?> ) { #>
        <?php
        if (!empty($templates['start'])) {
            include $templates['start'];
        }
        ?>
      <# _.each( settings.<?php echo $setting; ?>, function( item ) { #>
        <?php
        if (!empty($templates['loop'])) {
            include $templates['loop'];
        }
        ?>
      <# } ); #>
        <?php
        if (!empty($templates['end'])) {
            include $templates['end'];
        }
        ?>
      <# } #>
        <?php
    }

    /**
     * Get current looped item dependends from context.
     *
     * @param string $key Key to get from processed item
     * @return mixed
     */
    public function _loop_item($keys = array(), $format = '%s')
    {

        return call_user_func(array($this, sprintf('_%s_loop_item', $this->_context)), $keys, $format);

    }

    /**
     * Loop edit item
     *
     * @param  [type]  $keys       [description]
     * @param string $format [description]
     * @param boolean $nested_key [description]
     * @return [type]              [description]
     */
    public function _edit_loop_item($keys = array(), $format = '%s')
    {

        $settings = $keys[0];

        if (isset($keys[1])) {
            $settings .= '.' . $keys[1];
        }

        ob_start();

        echo '<# if ( item.' . $settings . ' ) { #>';
        printf($format, '{{{ item.' . $settings . ' }}}');
        echo '<# } #>';

        return ob_get_clean();
    }

    /**
     * Loop render item
     *
     * @param string $format [description]
     * @param  [type]  $key        [description]
     * @param boolean $nested_key [description]
     * @return [type]              [description]
     */
    public function _render_loop_item($keys = array(), $format = '%s')
    {

        $item = $this->_processed_item;

        $key = $keys[0];
        $nested_key = isset($keys[1]) ? $keys[1] : false;

        if (empty($item) || !isset($item[$key])) {
            return false;
        }

        if (false === $nested_key || !is_array($item[$key])) {
            $value = $item[$key];
        } else {
            $value = isset($item[$key][$nested_key]) ? $item[$key][$nested_key] : false;
        }

        if (!empty($value)) {
            return sprintf($format, $value);
        }

    }

    /**
     * Include global template if any of passed settings is defined
     *
     * @param  [type] $name     [description]
     * @param  [type] $settings [description]
     * @return [type]           [description]
     */
    public function _glob_inc_if($name = null, $settings = array())
    {

        $template = $this->_get_global_template($name);

        call_user_func(array($this, sprintf('_%s_inc_if', $this->_context)), $template, $settings);

    }

    /**
     * Include render template if any of passed setting is not empty
     *
     * @param  [type] $file     [description]
     * @param  [type] $settings [description]
     * @return [type]           [description]
     */
    public function _render_inc_if($file = null, $settings = array())
    {

        foreach ($settings as $setting) {
            $val = $this->get_settings_for_display($setting);

            if (!empty($val)) {
                include $file;
                return;
            }

        }

    }

    /**
     * Include render template if any of passed setting is not empty
     *
     * @param  [type] $file     [description]
     * @param  [type] $settings [description]
     * @return [type]           [description]
     */
    public function _edit_inc_if($file = null, $settings = array())
    {

        $condition = null;
        $sep = null;

        foreach ($settings as $setting) {
            $condition .= $sep . 'settings.' . $setting;
            $sep = ' || ';
        }

        ?>

      <# if ( <?php echo $condition; ?> ) { #>

        <?php include $file; ?>

      <# } #>

        <?php
    }

    /**
     * Open standard wrapper
     *
     * @return void
     */
    public function _open_wrap()
    {

    }

    /**
     * Close standard wrapper
     *
     * @return void
     */
    public function _close_wrap()
    {

    }

    /**
     * Print HTML markup if passed setting not empty.
     *
     * @param string $setting Passed setting.
     * @param string $format Required markup.
     * @param array $args Additional variables to pass into format string.
     * @param bool $echo Echo or return.
     * @return string|void
     */
    public function _html($setting = null, $format = '%s')
    {

        call_user_func(array($this, sprintf('_%s_html', $this->_context)), $setting, $format);

    }

    /**
     * Returns HTML markup if passed setting not empty.
     *
     * @param string $setting Passed setting.
     * @param string $format Required markup.
     * @param array $args Additional variables to pass into format string.
     * @param bool $echo Echo or return.
     * @return string|void
     */
    public function _get_html($setting = null, $format = '%s')
    {

        ob_start();
        $this->_html($setting, $format);
        return ob_get_clean();

    }

    /**
     * Print HTML template
     *
     * @param  [type] $setting [description]
     * @param  [type] $format  [description]
     * @return [type]          [description]
     */
    public function _render_html($setting = null, $format = '%s')
    {

        if (is_array($setting)) {
            $key = $setting[1];
            $setting = $setting[0];
        }

        $val = $this->get_settings_for_display($setting);

        if (!is_array($val) && '0' === $val) {
            printf($format, $val);
        }

        if (is_array($val) && empty($val[$key])) {
            return '';
        }

        if (!is_array($val) && empty($val)) {
            return '';
        }

        if (is_array($val)) {
            printf($format, $val[$key]);
        } else {
            printf($format, $val);
        }

    }

    /**
     * Print underscore template
     *
     * @param  [type] $setting [description]
     * @param  [type] $format  [description]
     * @return [type]          [description]
     */
    public function _edit_html($setting = null, $format = '%s')
    {

        if (is_array($setting)) {
            $setting = $setting[0] . '.' . $setting[1];
        }

        echo '<# if ( settings.' . $setting . ' ) { #>';
        printf($format, '{{{ settings.' . $setting . ' }}}');
        echo '<# } #>';
    }

    /**
     * Add icon control
     *
     * @param string $id
     * @param array $args
     * @param object $instance
     */
    public function _add_advanced_icon_control($id, array $args = array(), $instance = null)
    {

        if (defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, '2.6.0', '>=')) {

            $_id = $id; // old control id
            $id = $this->_new_icon_prefix . $id;

            $args['type'] = Controls_Manager::ICONS;
            $args['fa4compatibility'] = $_id;

            unset($args['file']);
            unset($args['default']);

            if (isset($args['fa5_default'])) {
                $args['default'] = $args['fa5_default'];

                unset($args['fa5_default']);
            }
        } else {
            $args['type'] = Controls_Manager::ICON;
            unset($args['fa5_default']);
        }

        if (null !== $instance) {
            $instance->add_control($id, $args);
        } else {
            $this->add_control($id, $args);
        }
    }

    /**
     * Prepare icon control ID for condition.
     *
     * @param string $id Old icon control ID.
     * @return string
     */
    public function _prepare_icon_id_for_condition($id)
    {

        if (defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, '2.6.0', '>=')) {
            return $this->_new_icon_prefix . $id . '[value]';
        }

        return $id;
    }

    /**
     * Print HTML icon markup
     *
     * @param array $setting
     * @param string $format
     * @param string $icon_class
     * @return void
     */
    public function _icon($setting = null, $format = '%s', $icon_class = '')
    {
        call_user_func(array($this, sprintf('_%s_icon', $this->_context)), $setting, $format, $icon_class);
    }

    /**
     * Returns HTML icon markup
     *
     * @param array $setting
     * @param string $format
     * @param string $icon_class
     * @return string
     */
    public function _get_icon($setting = null, $format = '%s', $icon_class = '')
    {
        return $this->_render_icon($setting, $format, $icon_class, false);
    }

    /**
     * Print HTML icon template
     *
     * @param array $setting
     * @param string $format
     * @param string $icon_class
     * @param bool $echo
     *
     * @return void|string
     */
    public function _render_icon($setting = null, $format = '%s', $icon_class = '', $echo = true)
    {

        if (false === $this->_processed_item) {
            $settings = $this->get_settings_for_display();
        } else {
            $settings = $this->_processed_item;
        }

        $new_setting = $this->_new_icon_prefix . $setting;

        $migrated = isset($settings['__fa4_migrated'][$new_setting]);
        $is_new = empty($settings[$setting]) && Icons_Manager::is_migration_allowed();

        $icon_html = '';

        if ($is_new || $migrated) {

            $attr = array('aria-hidden' => 'true');

            if (!empty($icon_class)) {
                $attr['class'] = $icon_class;
            }

            if (isset($settings[$new_setting])) {
                $icon_html = self::try_get_icon_html($settings[$new_setting], $attr);
            }

        } else if (!empty($settings[$setting])) {

            if (empty($icon_class)) {
                $icon_class = $settings[$setting];
            } else {
                $icon_class .= ' ' . $settings[$setting];
            }

            $icon_html = sprintf('<i class="%s" aria-hidden="true"></i>', $icon_class);
        }

        if (empty($icon_html)) {
            return;
        }

        if (!$echo) {
            return sprintf($format, $icon_html);
        }

        printf($format, $icon_html);
    }

    /**
     * [__add_control description]
     * @param boolean $control_id [description]
     * @param array $control_args [description]
     * @param integer $load_level [description]
     * @return [type]                [description]
     */
    public function _add_control($control_id = false, $control_args = [], $load_level = 100)
    {

        if (
            ($this->_load_level < $load_level
                || 0 === $this->_load_level
                || in_array($control_id, $this->_exclude_controls)
            ) && !in_array($control_id, $this->_include_controls)
        ) {
            return false;
        }

        $this->add_control($control_id, $control_args);
    }

    /**
     * [__add_responsive_control description]
     * @param boolean $control_id [description]
     * @param array $control_args [description]
     * @param integer $load_level [description]
     * @return [type]                [description]
     */
    public function _add_responsive_control($control_id = false, $control_args = [], $load_level = 100)
    {

        if (
            ($this->_load_level < $load_level
                || 0 === $this->_load_level
                || in_array($control_id, $this->_exclude_controls)
            ) && !in_array($control_id, $this->_include_controls)
        ) {
            return false;
        }

        $this->add_responsive_control($control_id, $control_args);
    }

    /**
     * [__add_group_control description]
     * @param boolean $group_control_type [description]
     * @param array $group_control_args [description]
     * @param integer $load_level [description]
     * @return [type]                      [description]
     */
    public function _add_group_control($group_control_type = false, $group_control_args = [], $load_level = 100)
    {

        if (
            ($this->_load_level < $load_level
                || 0 === $this->_load_level
                || in_array($group_control_args['name'], $this->_exclude_controls)
            ) && !in_array($group_control_args['name'], $this->_include_controls)
        ) {
            return false;
        }

        $this->add_group_control($group_control_type, $group_control_args);
    }

    /**
     * [__add_icon_control description]
     * @param  [type] $id   [description]
     * @param array $args [description]
     * @return [type]       [description]
     */
    public function _add_icon_control($id, array $args = array(), $load_level = 100)
    {

        if (
            ($this->_load_level < $load_level
                || 0 === $this->_load_level
                || in_array($id, $this->_exclude_controls)
            ) && !in_array($id, $this->_include_controls)
        ) {
            return false;
        }

        $this->_add_advanced_icon_control($id, $args);
    }

    /**
     * [__start_controls_section description]
     * @param boolean $controls_section_id [description]
     * @param array $controls_section_args [description]
     * @param integer $load_level [description]
     * @return [type]                         [description]
     */
    public function _start_controls_section($controls_section_id = false, $controls_section_args = [], $load_level = 25)
    {
        if (!$controls_section_id || $this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->start_controls_section($controls_section_id, $controls_section_args);
    }

    /**
     * [__end_controls_section description]
     * @param integer $load_level [description]
     * @return [type]              [description]
     */
    public function _end_controls_section($load_level = 25)
    {

        if ($this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->end_controls_section();
    }

    /**
     * [__start_controls_tabs description]
     * @param boolean $tabs_id [description]
     * @param integer $load_level [description]
     * @return [type]              [description]
     */
    public function _start_controls_tabs($tabs_id = false, $tab_args = [], $load_level = 25)
    {

        if (!$tabs_id || $this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->start_controls_tabs($tabs_id, $tab_args);
    }

    /**
     * [__end_controls_tabs description]
     * @param integer $load_level [description]
     * @return [type]              [description]
     */
    public function _end_controls_tabs($load_level = 25)
    {

        if ($this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->end_controls_tabs();
    }

    /**
     * [__start_controls_tab description]
     * @param boolean $tab_id [description]
     * @param array $tab_args [description]
     * @param integer $load_level [description]
     * @return [type]              [description]
     */
    public function _start_controls_tab($tab_id = false, $tab_args = [], $load_level = 25)
    {

        if (!$tab_id || $this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->start_controls_tab($tab_id, $tab_args);
    }

    /**
     * [__end_controls_tab description]
     * @param integer $load_level [description]
     * @return [type]              [description]
     */
    public function _end_controls_tab($load_level = 25)
    {

        if ($this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->end_controls_tab();
    }

    /**
     * Start popover
     *
     * @param int $load_level
     * @return void|bool
     */
    public function _start_popover($load_level = 25)
    {

        if ($this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->start_popover();
    }

    /**
     * End popover
     *
     * @param int $load_level
     * @return void|bool
     */
    public function _end_popover($load_level = 25)
    {

        if ($this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->end_popover();
    }

    public function _get_icon_setting($setting = null, $format = '%s', $icon_class = '', $echo = false)
    {
        $icon_html = '';

        $attr = array('aria-hidden' => 'true');

        if (!empty($icon_class)) {
            $attr['class'] = $icon_class;
        }

        if (!empty($setting)) {
            $icon_html = self::try_get_icon_html($setting, $attr);
        }

        if (empty($icon_html)) {
            return '';
        }

        if (!$echo) {
            return sprintf($format, $icon_html);
        }

        printf($format, $icon_html);

    }

    public function _add_link_attributes($element, array $url_control, $overwrite = false)
    {
        if (method_exists($this, 'add_link_attributes')) {
            return $this->add_link_attributes($element, $url_control, $overwrite);
        }

        $attributes = array();

        if (!empty($url_control['url'])) {
            $attributes['href'] = esc_url($url_control['url']);
        }

        if (!empty($url_control['is_external'])) {
            $attributes['target'] = '_blank';
        }

        if (!empty($url_control['nofollow'])) {
            $attributes['rel'] = 'nofollow';
        }
        if ( ! empty( $url_control['custom_attributes'] ) ) {
            // Custom URL attributes should come as a string of comma-delimited key|value pairs
            $attributes = array_merge( $attributes, Utils::parse_custom_attributes( $url_control['custom_attributes'] ) );
        }

        if ($attributes) {
            $this->add_render_attribute($element, $attributes, $overwrite);
        }

        return $this;
    }

    protected function register_carousel_section($carousel_condition = [], $carousel_columns = false, $enable_carousel = true)
    {
        $this->_start_controls_section(
            'carousel_section',
            array(
                'label' => esc_html__('Carousel Settings', 'lastudio-kit'),
                'condition' => $carousel_condition
            )
        );

        if ($enable_carousel) {
            $this->_add_control(
                'enable_carousel',
                array(
                    'label' => esc_html__('Enable Carousel', 'lastudio-kit'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'lastudio-kit'),
                    'label_off' => esc_html__('No', 'lastudio-kit'),
                    'return_value' => 'yes',
                    'default' => '',
                )
            );
        }

        $this->_add_control(
            'carousel_direction',
            array(
                'label' => esc_html__('Carousel Type', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'lastudio-kit'),
                    'vertical' => esc_html__('Vertical', 'lastudio-kit'),
                ],
            )
        );

        $this->_add_responsive_control(
            'carousel_height',
            array(
                'label' => esc_html__('Carousel Height', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('%', 'px', 'em', 'vw', 'vh'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .swiper-container-vertical' => 'height: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'carousel_direction' => 'vertical',
                ),
            )
        );

        if ($carousel_columns === false) {
            $column_dependency = 'carousel_columns!';
            $this->_add_responsive_control(
                'carousel_columns',
                array(
                    'label' => esc_html__('Slides to Show', 'lastudio-kit'),
                    'type' => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => lastudio_kit_helper()->get_select_range(10),
                )
            );
        } else {
            $column_dependency = "{$carousel_columns}!";
        }

        $this->_add_control(
            'enable_swiper_item_auto_width',
            array(
                'label' => esc_html__('Auto item width?', 'lastudio-kit'),
                'description' => esc_html__('This option may not work in some cases', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'yes',
                'default' => '',
            )
        );
        $this->_add_responsive_control('swiper_item_align_self', [
            'label' => esc_html__( 'Align Items', 'elementor' ),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => esc_html__( 'Start', 'elementor' ),
                    'icon' => 'eicon-flex eicon-align-start-v',
                ],
                'center' => [
                    'title' => esc_html__( 'Center', 'elementor' ),
                    'icon' => 'eicon-flex eicon-align-center-v',
                ],
                'flex-end' => [
                    'title' => esc_html__( 'End', 'elementor' ),
                    'icon' => 'eicon-flex eicon-align-end-v',
                ],
                'stretch' => [
                    'title' => esc_html__( 'Stretch', 'elementor' ),
                    'icon' => 'eicon-flex eicon-align-stretch-v',
                ],
            ],
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .lakit-carousel .swiper-slide' => 'align-self: {{VALUE}};',
            ],
        ]);
          $this->_add_responsive_control(
            'swiper_item__normal_width',
            array(
              'label' => esc_html__('Item Normal Width', 'lastudio-kit'),
              'type' => Controls_Manager::SLIDER,
              'size_units' => array('px', '%', 'custom'),
              'condition' => array(
                'enable_swiper_item_auto_width' => 'yes',
              ),
              'selectors' => array(
                '{{WRAPPER}}' => '--lakit-carousel-item-normal-width: {{SIZE}}{{UNIT}};',
              ),
            )
          );

      $this->_add_responsive_control(
        'swiper_item__active_width',
        array(
          'label' => esc_html__('Item Active Width', 'lastudio-kit'),
          'type' => Controls_Manager::SLIDER,
            'size_units' => array('px', '%', 'custom'),
          'condition' => array(
            'enable_swiper_item_auto_width' => 'yes',
          ),
          'selectors' => array(
            '{{WRAPPER}}' => '--lakit-carousel-item-active-width: {{SIZE}}{{UNIT}};',
          ),
        )
      );

        $this->_add_responsive_control(
            'carousel_to_scroll',
            array(
                'label' => esc_html__('Slides to Scroll', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'options' => lastudio_kit_helper()->get_select_range(20),
                'condition' => array(
                    $column_dependency => '1',
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_rows',
            array(
                'label' => esc_html__('Rows', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'options' => lastudio_kit_helper()->get_select_range(6),
                'condition' => array(
                    'carousel_direction' => 'horizontal',
                ),
            )
        );

	    $this->_add_control(
		    'carousel_overflow',
		    array(
			    'label' => esc_html__('Show Hidden Items', 'lastudio-kit'),
			    'type' => Controls_Manager::SWITCHER,
			    'label_on' => esc_html__('Yes', 'lastudio-kit'),
			    'label_off' => esc_html__('No', 'lastudio-kit'),
			    'return_value' => 'yes',
			    'default' => '',
                'prefix_class' => 'carousel-overflow-',
			    'selectors' => array(
				    '{{WRAPPER}} .swiper-container' => 'overflow: inherit',
			    ),
		    )
	    );

        $this->_add_control(
            'carousel_arrows',
            array(
                'label' => esc_html__('Show Arrows Navigation', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => 'true',
            )
        );

        $this->_add_icon_control(
            'carousel_prev_arrow',
            array(
                'label' => esc_html__('Prev Arrow Icon', 'lastudio-kit'),
                'label_block' => true,
                'default' => 'lastudioicon-arrow-left',
                'fa5_default' => array(
                    'value' => 'lastudioicon-arrow-left',
                    'library' => 'lastudioicon',
                ),
                'condition' => array(
                    'carousel_arrows' => 'true',
                ),
            )
        );

        $this->_add_icon_control(
            'carousel_next_arrow',
            array(
                'label' => esc_html__('Next Arrow Icon', 'lastudio-kit'),
                'label_block' => true,
                'default' => 'lastudioicon-arrow-right',
                'fa5_default' => array(
                    'value' => 'lastudioicon-arrow-right',
                    'library' => 'lastudioicon'
                ),
                'condition' => array(
                    'carousel_arrows' => 'true',
                ),
            )
        );

        $this->_add_control(
            'carousel_dots',
            array(
                'label' => esc_html__('Show Dots Pagination', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => '',
            )
        );

        $this->_add_control(
            'carousel_dot_type',
            array(
                'label' => esc_html__('Dots Pagination Type', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => 'bullets',
                'options' => [
                    'bullets' => esc_html__('Bullets', 'lastudio-kit'),
                    'fraction' => esc_html__('Fraction', 'lastudio-kit'),
                    'progressbar' => esc_html__('Progressbar', 'lastudio-kit'),
                    'custom' => esc_html__('Custom', 'lastudio-kit'),
                ],
                'condition' => array(
                    'carousel_dots' => 'true',
                ),
            )
        );

        $this->_add_control(
            'carousel_autoplay',
            array(
                'label' => esc_html__('Autoplay', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => 'true',
            )
        );

        $this->_add_control(
            'carousel_scrollbar',
            array(
                'label' => esc_html__('Scrollbar', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
            )
        );

        $this->_add_control(
            'carousel_pause_on_hover',
            array(
                'label' => esc_html__('Pause on Hover', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => '',
                'condition' => array(
                    'carousel_autoplay' => 'true',
                ),
            )
        );

        $this->_add_control(
            'carousel_pause_on_interaction',
            array(
                'label' => esc_html__('Disable on Interaction', 'lastudio-kit'),
                'description' => esc_html__('Set to no and autoplay will not be disabled after user interactions (swipes), it will be restarted every time after interaction', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => '',
                'condition' => array(
                    'carousel_autoplay' => 'true',
                ),
            )
        );

        $this->_add_control(
            'carousel_reverse_direction',
            array(
                'label' => esc_html__('Reverse Direction', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => '',
                'condition' => array(
                    'carousel_autoplay' => 'true',
                ),
            )
        );

        $this->_add_control(
            'carousel_autoplay_speed',
            array(
                'label' => esc_html__('Autoplay Speed', 'lastudio-kit'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5000,
                'condition' => array(
                    'carousel_autoplay' => 'true',
                ),
            )
        );

        $this->_add_control(
            'carousel_loop',
            array(
                'label' => esc_html__('Infinite Loop', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => 'true',
            )
        );


        $this->_add_control(
            'carousel_autoheight',
            array(
                'label' => esc_html__('Item AutoHeight', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => 'false',
            )
        );

        $this->_add_control(
            'carousel_equality',
            array(
                'label' => esc_html__('Item Equality', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'lakit-carousel-equalheight',
                'default' => '',
                'prefix_class' => ''
            )
        );

        $this->_add_control(
            'carousel_effect',
            array(
                'label' => esc_html__('Effect', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'description' => esc_html__('The `Fade` option does not work if `slides to show` option is `1`', 'lastudio-kit'),
                'default' => 'slide',
                'options' => array(
                    'slide' => esc_html__('Slide', 'lastudio-kit'),
                    'fade' => esc_html__('Fade', 'lastudio-kit'),
                    'cube' => esc_html__('Cube', 'lastudio-kit'),
                    'coverflow' => esc_html__('Coverflow', 'lastudio-kit'),
                    'flip' => esc_html__('Flip', 'lastudio-kit'),
                    'cards' => esc_html__('Cards', 'lastudio-kit'),
                    'creative' => esc_html__('Creative', 'lastudio-kit'),
                ),
//                'prefix_class' => 'lakit-carousel-item-effect-',
            )
        );

        $coverflow_conditions = [
            'carousel_effect' => 'coverflow'
        ];
        if ($enable_carousel) {
            $coverflow_conditions['enable_carousel'] = 'yes';
        }

        $this->_add_control(
            'carousel_coverflow__depth',
            array(
                'label' => esc_html__('Coverflow Deep', 'lastudio-kit'),
                'description' => esc_html__('Depth offset in px (slides translate in Z axis)', 'lastudio-kit'),
                'type' => Controls_Manager::NUMBER,
                'separator' => 'before',
                'min' => -1000,
                'max' => 500,
                'step' => 1,
                'default' => 100,
                'condition' => $coverflow_conditions,
            )
        );

        $this->_add_control(
            'carousel_coverflow__modifier',
            array(
                'label' => esc_html__('Coverflow Modifier', 'lastudio-kit'),
                'description' => esc_html__('Effect multiplier', 'lastudio-kit'),
                'type' => Controls_Manager::NUMBER,
                'min' => -1000,
                'max' => 10,
                'step' => 0.1,
                'default' => 1,
                'condition' => $coverflow_conditions,
            )
        );

        $this->_add_control(
            'carousel_coverflow__stretch',
            array(
                'label' => esc_html__('Coverflow Stretch', 'lastudio-kit'),
                'description' => esc_html__('Stretch space between slides (in px)', 'lastudio-kit'),
                'type' => Controls_Manager::NUMBER,
                'min' => -1000,
                'max' => 500,
                'step' => 1,
                'default' => 100,
                'condition' => $coverflow_conditions,
            )
        );

        $this->_add_control(
            'carousel_coverflow__rotate',
            array(
                'label' => esc_html__('Coverflow Rotate', 'lastudio-kit'),
                'description' => esc_html__('Slide rotate in degrees', 'lastudio-kit'),
                'type' => Controls_Manager::NUMBER,
                'min' => -360,
                'max' => 360,
                'step' => 1,
                'default' => 0,
                'condition' => $coverflow_conditions,
            )
        );

        $this->_add_control(
            'carousel_coverflow__scale',
            array(
                'label' => esc_html__('Coverflow Scale', 'lastudio-kit'),
                'description' => esc_html__('Slide scale effect', 'lastudio-kit'),
                'type' => Controls_Manager::NUMBER,
                'separator' => 'after',
                'min' => 0.1,
                'max' => 10,
                'step' => 0.1,
                'default' => 1,
                'condition' => $coverflow_conditions,
            )
        );

        $disable_content_effect_c = [];
        if($enable_carousel){
	        $disable_content_effect_c = [
		        'enable_carousel' => 'yes'
            ];
        }

        $this->_add_control(
            'carousel_disable_content_effect',
            array(
                'label' => esc_html__('Disable Content Effect', 'lastudio-kit'),
                'description' => esc_html__('If available', 'lastudio-kit'),
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'slide-no-animation',
                'prefix_class' => '',
                'condition' => $disable_content_effect_c
            )
        );

	    $this->_add_control(
		    'carousel_enable_linear_effect',
		    array(
			    'label' => esc_html__('Enable Linear Effect', 'lastudio-kit'),
			    'type' => Controls_Manager::SWITCHER,
			    'label_on' => esc_html__('Yes', 'lastudio-kit'),
			    'label_off' => esc_html__('No', 'lastudio-kit'),
			    'return_value' => 'true',
			    'default' => '',
			    'condition' => array(
				    'carousel_autoplay' => 'true',
				    'carousel_effect' => 'slide',
			    ),
		    )
	    );

        $this->_add_responsive_control(
            'carousel_item_width',
            array(
                'label' => esc_html__('Custom Item Width', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'default' => array(
                    'size' => 100,
                    'unit' => '%',
                ),
                'size_units' => ['px', 'em', 'vw', 'vh', '%'],
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .swiper-slide' => 'width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'carousel_effect' => ['flip', 'cube', 'coverflow'],
                ),
            )
        );

        $this->_add_control(
            'carousel_speed',
            array(
                'label' => esc_html__('Animation Speed', 'lastudio-kit'),
                'type' => Controls_Manager::NUMBER,
                'default' => 500,
            )
        );

        $this->_add_control(
            'carousel_center_mode',
            array(
                'label' => esc_html__('Center Mode', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'yes',
                'default' => ''
            )
        );

        $this->_add_responsive_control(
            'carousel_padding_left',
            array(
                'label' => esc_html__('Padding Left', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('%', 'px', 'em', 'vw', 'vh', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 500,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                    'em' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--lakit-carousel-padding-left: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_padding_right',
            array(
                'label' => esc_html__('Padding Right', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('%', 'px', 'em', 'vw', 'vh', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 500,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                    'em' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--lakit-carousel-padding-right: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->_add_control(
            'carousel_id',
            array(
                'label' => esc_html__('Carousel UniqueID', 'lastudio-kit'),
                'type' => Controls_Manager::TEXT,
                'separator' => 'before',
                'label_block' => true,
            )
        );
        $this->_add_control(
            'carousel_as_for',
            array(
                'label' => esc_html__('Custom Thumbs ID', 'lastudio-kit'),
                'label_block' => true,
                'description' => esc_html__('Enter uniqueID of carousel', 'lastudio-kit'),
                'type' => Controls_Manager::TEXT,
            )
        );

        $this->_end_controls_section();
    }

    protected function register_carousel_arrows_dots_style_section($carousel_condition = [])
    {

        /**
         * Arrow Sections
         */
        $this->_start_controls_section(
            'carousel_arrow_style_section',
            array(
                'label' => esc_html__('Carousel Arrows', 'lastudio-kit'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => $carousel_condition
            )
        );

        $this->_add_control(
            'carousel_arrow_show_on_hover',
            array(
                'label' => esc_html__('Display on hover', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'yes',
                'default' => 'no',
                'prefix_class' => 'lakit-arrow--showonhover-',
            )
        );

        $this->_start_controls_tabs('carousel_arrow_tabs');

        $this->_start_controls_tab(
            'carousel_tab_arrow_normal',
            array(
                'label' => esc_html__('Normal', 'lastudio-kit'),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name' => 'carousel_arrow_normal_style',
                'label' => esc_html__('Arrows Style', 'lastudio-kit'),
                'selector' => '{{WRAPPER}} .lakit-carousel .lakit-arrow'
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'carousel_tab_arrow_hover',
            array(
                'label' => esc_html__('Hover', 'lastudio-kit'),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name' => 'carousel_arrow_hover_style',
                'label' => esc_html__('Arrows Style', 'lastudio-kit'),
                'selector' => '{{WRAPPER}} .lakit-carousel .lakit-arrow:hover'
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_control(
            'carousel_prev_arrow_position',
            array(
                'label' => esc_html__('Prev Arrow Position', 'lastudio-kit'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->_add_control(
            'carousel_prev_arrow_v_position_by',
            array(
                'label' => esc_html__('Vertical Position by', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => array(
                    'top' => esc_html__('Top', 'lastudio-kit'),
                    'bottom' => esc_html__('Bottom', 'lastudio-kit'),
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_prev_arrow_top_position',
            array(
                'label' => esc_html__('Top Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_prev_arrow_v_position_by' => 'top',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .lakit-arrow.prev-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_prev_arrow_bottom_position',
            array(
                'label' => esc_html__('Bottom Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_prev_arrow_v_position_by' => 'bottom',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .lakit-arrow.prev-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
                ),
            )
        );

        $this->_add_control(
            'carousel_prev_arrow_h_position_by',
            array(
                'label' => esc_html__('Horizontal Position by', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left' => esc_html__('Left', 'lastudio-kit'),
                    'right' => esc_html__('Right', 'lastudio-kit'),
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_prev_arrow_left_position',
            array(
                'label' => esc_html__('Left Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_prev_arrow_h_position_by' => 'left',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .lakit-arrow.prev-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_prev_arrow_right_position',
            array(
                'label' => esc_html__('Right Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_prev_arrow_h_position_by' => 'right',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .lakit-arrow.prev-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ),
            )
        );

        $this->_add_control(
            'carousel_next_arrow_position',
            array(
                'label' => esc_html__('Next Arrow Position', 'lastudio-kit'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->_add_control(
            'carousel_next_arrow_v_position_by',
            array(
                'label' => esc_html__('Vertical Position by', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => array(
                    'top' => esc_html__('Top', 'lastudio-kit'),
                    'bottom' => esc_html__('Bottom', 'lastudio-kit'),
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_next_arrow_top_position',
            array(
                'label' => esc_html__('Top Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_next_arrow_v_position_by' => 'top',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .lakit-arrow.next-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_next_arrow_bottom_position',
            array(
                'label' => esc_html__('Bottom Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_next_arrow_v_position_by' => 'bottom',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .lakit-arrow.next-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
                ),
            )
        );

        $this->_add_control(
            'carousel_next_arrow_h_position_by',
            array(
                'label' => esc_html__('Horizontal Position by', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => array(
                    'left' => esc_html__('Left', 'lastudio-kit'),
                    'right' => esc_html__('Right', 'lastudio-kit'),
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_next_arrow_left_position',
            array(
                'label' => esc_html__('Left Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_next_arrow_h_position_by' => 'left',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .lakit-arrow.next-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_next_arrow_right_position',
            array(
                'label' => esc_html__('Right Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_next_arrow_h_position_by' => 'right',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .lakit-arrow.next-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'carousel_dot_style_section',
            array(
                'label' => esc_html__('Carousel Dots', 'lastudio-kit'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => $carousel_condition
            )
        );

        $this->_start_controls_tabs('carousel_dot_tabs');

        $this->_start_controls_tab(
            'carousel_tab_dot_normal',
            array(
                'label' => esc_html__('Normal', 'lastudio-kit'),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name' => 'carousel_dot_normal_style',
                'label' => esc_html__('Dots Style', 'lastudio-kit'),
                'selector' => '{{WRAPPER}} .lakit-carousel .swiper-pagination-bullet',
                'condition' => [
                  'carousel_dot_type!' => ['progressbar', 'custom', 'fraction']
                ]
            )
        );
        $this->_add_control(
            '_carousel_dot__color1',
            array(
                'label' => esc_html__('Color Normal', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__dots' => '--lakit-carousel__dots-color-1: {{VALUE}}',
                ),
                'condition' => [
                    'carousel_dot_type' => ['progressbar', 'custom', 'fraction']
                ]
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'carousel_tab_dot_hover',
            array(
                'label' => esc_html__('Active', 'lastudio-kit'),
            )
        );

        $this->_add_group_control(
            Group_Control_Box_Style::get_type(),
            array(
                'name' => 'carousel_dot_hover_style',
                'label' => esc_html__('Dots Style', 'lastudio-kit'),
                'selector' => '{{WRAPPER}} .lakit-carousel .swiper-pagination-bullet-active,{{WRAPPER}} .lakit-carousel .swiper-pagination-bullet:hover',
                'condition' => [
                    'carousel_dot_type!' => ['progressbar', 'custom', 'fraction']
                ]
            )
        );

        $this->_add_control(
            '_carousel_dot__color2',
            array(
                'label' => esc_html__('Color Active', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__dots' => '--lakit-carousel__dots-color-2: {{VALUE}}',
                ),
                'condition' => [
                    'carousel_dot_type' => ['progressbar', 'custom', 'fraction']
                ]
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_control(
            'carousel_dot_vertical',
            array(
                'label' => esc_html__('Enable Vertical Dots', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'yes',
                'default' => 'no',
                'prefix_class' => 'lakit-dots--vertical-',
                'condition' => [
                    'carousel_dot_type!' => 'progressbar'
                ],
                'separator' => 'before',
            )
        );

        $this->_add_responsive_control(
            'carousel_dots_gap',
            array(
                'label' => esc_html__('Gap', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'default' => array(
                    'size' => 5,
                    'unit' => 'px',
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--lakit-carousel-dot-item-space: {{SIZE}}{{UNIT}};',
                ),
                'separator' => 'before',
                'condition' => [
                    'carousel_dot_type!' => ['progressbar', 'custom', 'fraction']
                ]
            )
        );

        $this->_add_responsive_control(
            'carousel_dots_margin',
            array(
                'label' => esc_html__('Dots Box Margin', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', '%', 'em'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .lakit-carousel__dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_control(
            'carousel_dots_h_alignment',
            array(
                'label' => esc_html__('Horizontal Position', 'lastudio-kit'),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => esc_html__('Left', 'lastudio-kit'),
                        'icon' => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__('Center', 'lastudio-kit'),
                        'icon' => 'eicon-h-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__('Right', 'lastudio-kit'),
                        'icon' => 'eicon-h-align-right',
                    )
                ),
                'prefix_class' => 'lakit-dots-h-align-',
            )
        );

        $this->_add_control(
            'carousel_dots_v_alignment',
            array(
                'label' => esc_html__('Vertical Position', 'lastudio-kit'),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'top' => array(
                        'title' => esc_html__('Top', 'lastudio-kit'),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'middle' => array(
                        'title' => esc_html__('Middle', 'lastudio-kit'),
                        'icon' => 'eicon-v-align-middle',
                    ),
                    'bottom' => array(
                        'title' => esc_html__('Bottom', 'lastudio-kit'),
                        'icon' => 'eicon-v-align-bottom',
                    )
                ),
                'prefix_class' => 'lakit-dots-v-align-',
            )
        );

        $this->_add_control(
            'carousel_dots_alignment',
            array(
                'label' => esc_html__('Text Alignment', 'lastudio-kit'),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => esc_html__('Left', 'lastudio-kit'),
                        'icon' => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__('Center', 'lastudio-kit'),
                        'icon' => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__('Right', 'lastudio-kit'),
                        'icon' => 'eicon-text-align-right',
                    )
                ),
                'prefix_class' => 'lakit-dots-text-',
            )
        );

        $this->_end_controls_section();

        $bar_conditions = $carousel_condition;
        $bar_conditions['carousel_scrollbar'] = 'true';

        $this->_start_controls_section(
            'carousel_scrollbar_style_section',
            array(
                'label' => esc_html__('Carousel Scrollbar', 'lastudio-kit'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => $bar_conditions
            )
        );
	    $this->_add_responsive_control(
		    '_carousel_scrollbar__width',
		    array(
			    'label' => esc_html__('Width', 'lastudio-kit'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', 'em', '%', 'custom'],
			    'selectors' => array(
				    '{{WRAPPER}}' => '--swiper-scrollbar-width: {{SIZE}}{{UNIT}};',
			    ),
		    )
	    );
        $this->_add_responsive_control(
            '_carousel_scrollbar__height',
            array(
                'label' => esc_html__('Height', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => array(
                    '{{WRAPPER}}' => '--swiper-scrollbar-height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

	    $this->_add_responsive_control(
		    '_carousel_scrollbar_drag__width',
		    array(
			    'label' => esc_html__('Draggable Width', 'lastudio-kit'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', 'em', '%', 'custom'],
			    'selectors' => array(
				    '{{WRAPPER}}' => '--swiper-scrollbar-drag-width: {{SIZE}}{{UNIT}};',
			    ),
		    )
	    );
        $this->_add_responsive_control(
            '_carousel_scrollbar_drag__height',
            array(
                'label' => esc_html__('Draggable Height', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => array(
                    '{{WRAPPER}}' => '--swiper-scrollbar-drag-height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_control(
            '_carousel_scrollbar__color1',
            array(
                'label' => esc_html__('Color Normal', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__scrollbar' => 'background: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            '_carousel_scrollbar__color2',
            array(
                'label' => esc_html__('Color Active', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .swiper-scrollbar-drag' => 'background: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            '_carousel_scrollbar__margin',
            array(
                'label' => __('Margin', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', '%', 'em', 'vw', 'vh', 'custom'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__scrollbar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            '_carousel_scrollbar__radius',
            array(
                'label' => __('Border Radius', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__scrollbar, {{WRAPPER}} .swiper-scrollbar-drag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_end_controls_section();
    }

    public function get_lakit_id()
    {
        return $this->get_id();
    }

    public function get_advanced_carousel_options($carousel_columns = false, $widget_id = '', $settings = null)
    {
        if(empty($settings)){
            $settings = $this->get_settings();
        }
        if(empty($widget_id)){
            $widget_id = $this->get_lakit_id();
        }

        $rows = lastudio_kit_helper()->get_attribute_with_all_breakpoints('carousel_rows', $settings);
        $carousel_direction = (!empty($settings['carousel_direction']) && $settings['carousel_direction'] == 'vertical') ? 'vertical' : 'horizontal';
        if ($carousel_direction == 'vertical') {
            $rows = lastudio_kit_helper()->get_attribute_with_all_breakpoints('carousel_rows', []);
        }

        $carousel_id = $this->get_settings_for_display('carousel_id');
        if (empty($carousel_id)) {
            $carousel_id = 'lakit_carousel_' . $widget_id;
        }

        $carousel_autoheight = isset($settings['carousel_autoheight']) ? $settings['carousel_autoheight'] : false;

        $options = array(
            'slidesToScroll' => lastudio_kit_helper()->get_attribute_with_all_breakpoints('carousel_to_scroll', $settings),
            'rows' => $rows,
            'autoplaySpeed' => absint($settings['carousel_autoplay_speed']),
            'autoplay' => filter_var($settings['carousel_autoplay'], FILTER_VALIDATE_BOOLEAN),
            'infinite' => filter_var($settings['carousel_loop'], FILTER_VALIDATE_BOOLEAN),
            'centerMode' => filter_var($settings['carousel_center_mode'], FILTER_VALIDATE_BOOLEAN),
            'pauseOnHover' => filter_var($settings['carousel_pause_on_hover'], FILTER_VALIDATE_BOOLEAN),
            'pauseOnInteraction' => filter_var($settings['carousel_pause_on_interaction'], FILTER_VALIDATE_BOOLEAN),
            'reverseDirection' => filter_var($settings['carousel_reverse_direction'], FILTER_VALIDATE_BOOLEAN),
            'infiniteEffect' => filter_var($settings['carousel_enable_linear_effect'], FILTER_VALIDATE_BOOLEAN),
            'speed' => absint($settings['carousel_speed']),
            'arrows' => filter_var($settings['carousel_arrows'], FILTER_VALIDATE_BOOLEAN),
            'dots' => filter_var($settings['carousel_dots'], FILTER_VALIDATE_BOOLEAN),
            'variableWidth' => filter_var($settings['enable_swiper_item_auto_width'], FILTER_VALIDATE_BOOLEAN),
            'prevArrow' => '.lakit-carousel__prev-arrow-' . $widget_id,
            'nextArrow' => '.lakit-carousel__next-arrow-' . $widget_id,
            'dotsElm' => '.lakit-carousel__dots_' . $widget_id,
            'rtl' => is_rtl(),
            'effect' => $settings['carousel_effect'],
            'coverflowEffect' => [
                'rotate' => $this->get_settings_for_display('carousel_coverflow__rotate'),
                'stretch' => $this->get_settings_for_display('carousel_coverflow__stretch'),
                'depth' => $this->get_settings_for_display('carousel_coverflow__depth'),
                'modifier' => $this->get_settings_for_display('carousel_coverflow__modifier'),
                'scale' => $this->get_settings_for_display('carousel_coverflow__scale'),
            ],
            'dotType' => $settings['carousel_dot_type'],
            'direction' => $carousel_direction,
            'uniqueID' => $carousel_id,
            'asFor' => $settings['carousel_as_for'],
            'autoHeight' => filter_var($carousel_autoheight, FILTER_VALIDATE_BOOLEAN),
            'scrollbar' => filter_var($settings['carousel_scrollbar'], FILTER_VALIDATE_BOOLEAN),
        );
        if ($carousel_columns === false) {
            $options['slidesToShow'] = lastudio_kit_helper()->get_attribute_with_all_breakpoints('carousel_columns', $settings);
        } else {
            $options['slidesToShow'] = lastudio_kit_helper()->get_attribute_with_all_breakpoints($carousel_columns, $settings);
        }
        if(filter_var($settings['enable_swiper_item_auto_width'], FILTER_VALIDATE_BOOLEAN)){
          $this->add_render_attribute('main-container', 'class', 'e-swiper--variablewidth');
        }
        return $options;
    }

    public function register_masonry_setting_section($condition = [], $show_filter = true)
    {
        $css_scheme = [
            'filters_wrap'     => '.lakit-masonry_filter',
            'filters'          => '.lakit-masonry_filter-list',
            'filter_item'      => '.lakit-masonry_filter-item',
        ];

        $this->_start_controls_section(
            'section_masonry_layout',
            array(
                'label' => esc_html__('Masonry Settings', 'lastudio-kit'),
                'condition' => $condition
            )
        );
        $this->_add_control(
            'masonry_enable_custom_layout',
            array(
                'label' => esc_html__('Enable Custom Layout', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => '',
            )
        );

	    $this->_add_control(
		    'masonry_custom_layout_breakpoint',
		    array(
			    'label' => esc_html__( 'Disable Layout On', 'lastudio-kit' ),
			    'type'  => Controls_Manager::SELECT,
			    'default' => 'tablet',
			    'options' => [ 'none' => esc_html__('None', 'lastudio-kit') ] + lastudio_kit_helper()->get_active_breakpoints(false, true),
			    'condition' => array(
				    'masonry_enable_custom_layout' => 'true'
			    ),
		    )
	    );

        $this->_add_control(
            'masonry_container_width',
            array(
                'label' => esc_html__('Container Width', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 500,
                        'max' => 2000,
                    ),
                ),
                'default' => [
                    'size' => 1170,
                ],
                'condition' => array(
                    'masonry_enable_custom_layout' => 'true'
                )
            )
        );
        $this->_add_control(
            'masonry_item_width',
            array(
                'label' => esc_html__('Item Width', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 2000,
                    ),
                ),
                'default' => [
                    'size' => 300,
                ],
                'condition' => array(
                    'masonry_enable_custom_layout' => 'true'
                )
            )
        );

        $this->_add_control(
            'masonry_item_height',
            array(
                'label' => esc_html__('Masonry Item Height', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 2000,
                    ),
                ),
                'default' => [
                    'size' => 300,
                ],
                'condition' => array(
                    'masonry_enable_custom_layout' => 'true'
                )
            )
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'item_width',
            array(
                'label' => esc_html__('Item Width', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'options' => array(
                    '1' => '1 width',
                    '1-1' => '1.1 width',
                    '1-2' => '1.2 width',
                    '1-3' => '1.3 width',
                    '1-4' => '1.4 width',
                    '1-5' => '1.5 width',
                    '1-6' => '1.6 width',
                    '1-7' => '1.7 width',
                    '1-8' => '1.8 width',
                    '1-9' => '1.9 width',
                    '2' => '2 width',
                    '2-1' => '2.1 width',
                    '2-2' => '2.2 width',
                    '2-3' => '2.3 width',
                    '2-4' => '2.4 width',
                    '2-5' => '2.5 width',
                    '2-6' => '2.6 width',
                    '2-7' => '2.7 width',
                    '2-8' => '2.8 width',
                    '2-9' => '2.9 width',
                    '3' => '3 width',
                    '3-1' => '3.1 width',
                    '3-2' => '3.2 width',
                    '3-3' => '3.3 width',
                    '3-4' => '3.4 width',
                    '3-5' => '3.5 width',
                    '3-6' => '3.6 width',
                    '3-7' => '3.7 width',
                    '3-8' => '3.8 width',
                    '3-9' => '3.9 width',
                    '4' => '4 width',
                    '4-5' => '4.5 width',
                    '5' => '5 width',
                    '5-5' => '5.5 width',
                    '6' => '6 width',
                    '0-5' => '0.5 width',
                    '0-6' => '0.6 width',
                    '0-7' => '0.7 width',
                    '0-8' => '0.8 width',
                    '0-9' => '0.9 width',
                ),
            )
        );
        $repeater->add_control(
            'item_height',
            array(
                'label' => esc_html__('Item Height', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'options' => array(
                    '1' => '1 height',
                    '2' => '2 height',
                    '3' => '3 height',
                    '0-5' => '1/2 height',
                    '0-75' => '3/4 height',
                    '0-40' => '2/5 height',
                    '0-60' => '3/5 height',
                    '0-80' => '4/5 height',
                    '0-1' => '0.1 height',
                    '0-2' => '0.2 height',
                    '0-3' => '0.3 height',
                    '0-4' => '0.4 height',
                    '0-6' => '0.6 height',
                    '0-7' => '0.7 height',
                    '0-8' => '0.8 height',
                    '0-9' => '0.9 height',
                    '1-1' => '1.1 height',
                    '1-2' => '1.2 height',
                    '1-3' => '1.3 height',
                    '1-4' => '1.4 height',
                    '1-5' => '1.5 height',
                    '1-6' => '1.6 height',
                    '1-7' => '1.7 height',
                    '1-8' => '1.8 height',
                    '1-9' => '1.9 height',
                    '2-1' => '2.1 height',
                    '2-2' => '2.2 height',
                    '2-3' => '2.3 height',
                    '2-4' => '2.4 height',
                    '2-5' => '2.5 height',
                    '2-6' => '2.6 height',
                    '2-7' => '2.7 height',
                    '2-8' => '2.8 height',
                    '2-9' => '2.9 height',
                )
            )
        );
        $this->_add_control(
            'masonry_custom_layouts',
            array(
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'prevent_empty' => false,
                'title_field' => '{{{ item_width }}}x{{{ item_height }}}',
                'condition' => array(
                    'masonry_enable_custom_layout' => 'true'
                )
            )
        );
        $this->_end_controls_section();

        if($show_filter) {

            $this->_start_controls_section(
                'section_masonry_filters',
                array(
                    'label' => esc_html__('Masonry Filters', 'lastudio-kit'),
                    'condition' => $condition
                )
            );
            $this->_add_control(
                'masonry_enable_filter',
                array(
                    'label' => esc_html__('Enable Filters ?', 'lastudio-kit'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'lastudio-kit'),
                    'label_off' => esc_html__('No', 'lastudio-kit'),
                    'return_value' => 'true',
                    'default' => '',
                )
            );
            $this->_add_control(
                'masonry_filer_label_all',
                array(
                    'label' => esc_html__('`All` Filter Label', 'lastudio-kit'),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__('All', 'lastudio-kit')
                )
            );
            $this->_add_control(
                'masonry_filters',
                [
                    'label' => __('Filters', 'lastudio-kit'),
                    'type' => 'lastudiokit-query',
                    'post_type' => '',
                    'options' => [],
                    'label_block' => true,
                    'multiple' => true,
                    'filter_type' => 'taxonomy',
                    'include_type' => true,
                    'condition' => [
                        'masonry_enable_filter' => 'true'
                    ],
                ]
            );
            $this->_end_controls_section();


            /**
             * Filter Style Section
             */
            $this->_start_controls_section(
                'section_filters_style',
                array(
                    'label' => esc_html__('Filter Bar', 'lastudio-kit'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'show_label' => false,
                    'condition' => [
                        'masonry_enable_filter' => 'true'
                    ],
                )
            );

            $this->_add_control(
                'filters_container_styles_heading',
                array(
                    'label' => esc_html__('Filters Container Styles', 'lastudio-kit'),
                    'type' => Controls_Manager::HEADING,
                )
            );

            $this->_add_control(
                'filters_background_color',
                array(
                    'label' => esc_html__('Background Color', 'lastudio-kit'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['filters'] => 'background-color: {{VALUE}}',
                    ),
                )
            );

            $this->_add_responsive_control(
                'filters_padding',
                array(
                    'label' => __('Padding', 'lastudio-kit'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%'),
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['filters'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->_add_responsive_control(
                'filters_margin',
                array(
                    'label' => __('Margin', 'lastudio-kit'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%'),
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['filters'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->_add_group_control(
                Group_Control_Border::get_type(),
                array(
                    'name' => 'filters_border',
                    'label' => esc_html__('Border', 'lastudio-kit'),
                    'placeholder' => '1px',
                    'default' => '1px',
                    'selector' => '{{WRAPPER}} ' . $css_scheme['filters'],
                )
            );

            $this->_add_responsive_control(
                'filters_border_radius',
                array(
                    'label' => __('Border Radius', 'lastudio-kit'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%'),
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['filters'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->_add_group_control(
                Group_Control_Box_Shadow::get_type(),
                array(
                    'name' => 'filters_box_shadow',
                    'selector' => '{{WRAPPER}} ' . $css_scheme['filters'],
                )
            );

            $this->_add_control(
                'filters_items_styles_heading',
                array(
                    'label' => esc_html__('Filters Items Styles', 'lastudio-kit'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                )
            );


            $this->_add_responsive_control(
                'filters_items_aligment',
                array(
                    'label' => esc_html__('Alignment', 'lastudio-kit'),
                    'type' => Controls_Manager::CHOOSE,
                    'default' => 'center',
                    'label_block' => false,
                    'options' => array(
                        'flex-start' => array(
                            'title' => esc_html__('Left', 'lastudio-kit'),
                            'icon' => 'eicon-h-align-left',
                        ),
                        'center' => array(
                            'title' => esc_html__('Center', 'lastudio-kit'),
                            'icon' => 'eicon-h-align-center',
                        ),
                        'flex-end' => array(
                            'title' => esc_html__('Right', 'lastudio-kit'),
                            'icon' => 'eicon-h-align-right',
                        ),
                    ),
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['filters_wrap'] => 'justify-content: {{VALUE}};',
                    ),
                )
            );

            $this->_start_controls_tabs('tabs_filter_item');

            $this->_start_controls_tab(
                'tab_filter_item_normal',
                array(
                    'label' => esc_html__('Normal', 'lastudio-kit'),
                )
            );

            $this->_add_control(
                'filter_background_color',
                array(
                    'label' => esc_html__('Background Color', 'lastudio-kit'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['filter_item'] => 'background-color: {{VALUE}}',
                    ),
                )
            );

            $this->_add_control(
                'filter_color',
                array(
                    'label' => esc_html__('Color', 'lastudio-kit'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['filter_item'] => 'color: {{VALUE}}',
                    ),
                )
            );

            $this->_add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name' => 'filter_typography',
                    'selector' => '{{WRAPPER}}  ' . $css_scheme['filter_item'],
                )
            );


            $this->_add_responsive_control(
                'filter_padding',
                array(
                    'label' => __('Padding', 'lastudio-kit'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%'),
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['filter_item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->_add_responsive_control(
                'filter_margin',
                array(
                    'label' => __('Margin', 'lastudio-kit'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%'),
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['filter_item'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->_add_group_control(
                Group_Control_Border::get_type(),
                array(
                    'name' => 'filter_border',
                    'label' => esc_html__('Border', 'lastudio-kit'),
                    'placeholder' => '1px',
                    'default' => '1px',
                    'selector' => '{{WRAPPER}} ' . $css_scheme['filter_item'],
                )
            );

            $this->_add_responsive_control(
                'filter_border_radius',
                array(
                    'label' => __('Border Radius', 'lastudio-kit'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%'),
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['filter_item'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->_add_group_control(
                Group_Control_Box_Shadow::get_type(),
                array(
                    'name' => 'filter_box_shadow',
                    'selector' => '{{WRAPPER}} ' . $css_scheme['filter_item'],
                )
            );

            $this->_end_controls_tab();

            $this->_start_controls_tab(
                'tab_filter_item_active',
                array(
                    'label' => esc_html__('Hover/Active', 'lastudio-kit'),
                )
            );

            $this->_add_control(
                'filter_color_active',
                array(
                    'label' => esc_html__('Color', 'lastudio-kit'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['filter_item'] . ':hover' => 'color: {{VALUE}}',
                        '{{WRAPPER}} ' . $css_scheme['filter_item'] . '.active' => 'color: {{VALUE}}',
                    ),
                )
            );

            $this->_add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name' => 'filter_typography_active',
                    'selector' => '{{WRAPPER}} ' . $css_scheme['filter_item'] . '.active, {{WRAPPER}} ' . $css_scheme['filter_item'] . ':hover',
                )
            );

            $this->_add_group_control(
                Group_Control_Background::get_type(),
                array(
                    'name' => 'filter_background_active',
                    'selector' => '{{WRAPPER}} ' . $css_scheme['filter_item'] . '.active, {{WRAPPER}} ' . $css_scheme['filter_item'] . ':hover',
                )
            );

            $this->_add_responsive_control(
                'filter_padding_active',
                array(
                    'label' => __('Padding', 'lastudio-kit'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%'),
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['filter_item'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} ' . $css_scheme['filter_item'] . '.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->_add_responsive_control(
                'filter_margin_active',
                array(
                    'label' => __('Margin', 'lastudio-kit'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%'),
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['filter_item'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} ' . $css_scheme['filter_item'] . '.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->_add_group_control(
                Group_Control_Border::get_type(),
                array(
                    'name' => 'filter_border_active',
                    'label' => esc_html__('Border', 'lastudio-kit'),
                    'placeholder' => '1px',
                    'default' => '1px',
                    'selector' => '{{WRAPPER}} ' . $css_scheme['filter_item'] . '.active, {{WRAPPER}} ' . $css_scheme['filter_item'] . ':hover',
                )
            );

            $this->_add_responsive_control(
                'filter_border_radius_active',
                array(
                    'label' => __('Border Radius', 'lastudio-kit'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => array('px', '%'),
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['filter_item'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} ' . $css_scheme['filter_item'] . '.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->_add_group_control(
                Group_Control_Box_Shadow::get_type(),
                array(
                    'name' => 'filter_box_shadow_active',
                    'selector' => '{{WRAPPER}} ' . $css_scheme['filter_item'] . '.active, {{WRAPPER}} ' . $css_scheme['filter_item'] . ':hover',
                )
            );

            $this->_end_controls_tab();

            $this->_end_controls_tabs();

            $this->_end_controls_section();
        }
    }

    /**
     * @param string $item_selector
     * @param string $masonry_wrap
     * @param array $extra
     * @param string $return_type accept value `string`, `raw`, `attr`
     * @return string
     */
    public function get_masonry_options($item_selector = '', $masonry_wrap = '', $extra = [], $return_type = 'string')
    {
        $options = [
            'lakitmasonry_itemselector' => $item_selector,
            'lakitmasonry_wrap' => $masonry_wrap,
        ];
        $masonryadvance = [];
        if (filter_var($this->get_settings_for_display('masonry_enable_custom_layout'), FILTER_VALIDATE_BOOLEAN)) {
            $masonry_container_width = $this->get_settings_for_display('masonry_container_width');
            $masonry_item_width = $this->get_settings_for_display('masonry_item_width');
            $masonry_item_height = $this->get_settings_for_display('masonry_item_height');
            $masonry_custom_layouts = $this->get_settings_for_display('masonry_custom_layouts');
            $masonryadvance['container_width'] = $masonry_container_width['size'];
            $masonryadvance['item_width'] = $masonry_item_width['size'];
            $masonryadvance['item_height'] = $masonry_item_height['size'];
            $custom_layout = [];
            foreach ($masonry_custom_layouts as $idx => $layout) {
                $custom_layout[$idx] = [
                    'w' => floatval(str_replace('-', '.', $layout['item_width'])),
                    'h' => floatval(str_replace('-', '.', $layout['item_height']))
                ];
            }
            if (!empty($custom_layout)) {
                $masonryadvance['layout'] = $custom_layout;
            }

	        $masonry_custom_layout_disable_on = $this->get_settings_for_display('masonry_custom_layout_breakpoint');
	        $active_breakpoints = lastudio_kit_helper()->get_active_breakpoints();
	        $breakpoint_value = 1024;
            if(!empty($masonry_custom_layout_disable_on)){
                if($masonry_custom_layout_disable_on == 'none'){
	                $breakpoint_value = 0;
                }
                elseif (isset($active_breakpoints[$masonry_custom_layout_disable_on])){
	                $breakpoint_value = $active_breakpoints[$masonry_custom_layout_disable_on];
                }
            }
	        $masonryadvance['disable_on'] = $breakpoint_value;
        }
        if (!empty($masonryadvance)) {
            $options['lakitmasonry_layouts'] = $masonryadvance;
        }

        $options = array_merge($options, $extra);

        if ($return_type == 'attr') {
            $new_opts = [];
            foreach ($options as $option => $value) {
                $new_opts['data-' . $option] = $value;
            }
            return $new_opts;
        }
        elseif ($return_type == 'raw') {
            return $options;
        }
        else {
            $atts = '';
            foreach ($options as $option => $value) {
                $atts .= ' data-' . esc_attr($option) . '="';
                if (is_scalar($value)) {
                    $atts .= esc_attr($value);
                } else {
                    $atts .= esc_attr(wp_json_encode($value));
                }
                $atts .= '"';
            }
            return $atts;
        }
    }

    public function render_variable($var)
    {
        echo $var;
    }

    public function render_masonry_filters($container = 'parent', $echo = true)
    {
        $enable_filter = filter_var($this->get_settings_for_display('masonry_enable_filter'), FILTER_VALIDATE_BOOLEAN);
        $filters = $this->get_settings_for_display('masonry_filters');
        $all = $this->get_settings_for_display('masonry_filer_label_all');
        if (empty($all)) {
            $all = __('All', 'lastudio-kit');
        }
        $output = '';
        if ($enable_filter && !empty($filters)) {
            $output .= '<div class="lakit-masonry_filter" data-lakitmasonry_container="'.esc_attr($container).'" data-paged-key="'.esc_attr($this->_paged_key).'" data-widget-id="'.esc_attr( $this->get_id() ).'">';
            $output .= '<div class="lakit-masonry_filter-list">';
            $output .= sprintf('<div class="lakit-masonry_filter-item active" data-filter="*">%1$s</div>', esc_html($all));
            foreach ($filters as $filter_id) {
                $termObj = get_term($filter_id);
                if(!is_wp_error($termObj)){
                    $output .= sprintf('<div class="lakit-masonry_filter-item" data-count="%3$s" data-filter="term-%1$s">%2$s</div>', esc_attr($filter_id), $termObj->name, $termObj->count);
                }
            }
            $output .= '</div>';
            $output .= '</div>';
        }
        if($echo){
            echo $output;
            return true;
        }
        return $output;
    }

    public static function get_labrandicon( $key_only = false ){
        $icons = array (
            'lastudioicon-b-dribbble'       => 'Dribbble',
            'lastudioicon-b-facebook'       => 'Facebook',
            'lastudioicon-b-flickr'         => 'Flickr',
            'lastudioicon-b-foursquare'     => 'Foursquare',
            'lastudioicon-b-github-circled' => 'Github',
            'lastudioicon-b-instagram'      => 'Instagram',
            'lastudioicon-b-lastfm'         => 'Lastfm',
            'lastudioicon-b-linkedin'       => 'LinkedIn',
            'lastudioicon-b-pinterest'      => 'Pinterest',
            'lastudioicon-b-reddit'         => 'Reddit',
            'lastudioicon-b-soundcloud'     => 'Soundcloud',
            'lastudioicon-b-spotify'        => 'Spotify',
            'lastudioicon-b-tumblr'         => 'Tumblr',
            'lastudioicon-b-twitter-x'      => 'Twitter',
            'lastudioicon-b-twitter'        => 'Twitter',
            'lastudioicon-b-vimeo'          => 'Vimeo',
            'lastudioicon-b-vine'           => 'Vine',
            'lastudioicon-b-yelp'           => 'Yelp',
            'lastudioicon-b-yahoo-1'        => 'Yahoo',
            'lastudioicon-b-youtube-play'   => 'Youtube',
            'lastudioicon-b-wordpress'      => 'WordPress',
            'lastudioicon-b-dropbox'        => 'Dropbox',
            'lastudioicon-b-evernote'       => 'Evernote',
            'lastudioicon-b-skype'          => 'Skype',
            'lastudioicon-b-telegram'       => 'Telegram',
            'lastudioicon-mail'             => 'Email',
            'lastudioicon-phone-1'          => 'Phone',
            'lastudioicon-b-tiktok'         => 'Tiktok',
        );
        if($key_only){
            return array_keys($icons);
        }
        return $icons;
    }

    public static function get_laicon_default( $key_only = false ){
        $icon_list = array(
            "b-dribbble",
            "b-vkontakte",
            "b-line",
            "b-twitter-squared",
            "b-yahoo-1",
            "b-skype-outline",
            "globe",
            "shield",
            "phone-call",
            "menu-6",
            "support248",
            "f-comment-1",
            "ic_mail_outline_24px",
            "ic_compare_arrows_24px",
            "ic_compare_24px",
            "ic_share_24px",
            "bath-tub-1",
            "shopping-cart-1",
            "contrast",
            "heart-1",
            "sort-tool",
            "list-bullet-1",
            "menu-8-1",
            "menu-4-1",
            "menu-3-1",
            "menu-1",
            "down-arrow",
            "left-arrow",
            "right-arrow",
            "up-arrow",
            "phone-1",
            "pin-3-1",
            "search-content",
            "single-01-1",
            "i-delete",
            "zoom-1",
            "b-meeting",
            "bag-20",
            "bath-tub-2",
            "web-link",
            "shopping-cart-2",
            "cart-return",
            "check",
            "g-check",
            "d-check",
            "circle-10",
            "circle-simple-left",
            "circle-simple-right",
            "compare",
            "letter",
            "mail",
            "email",
            "eye",
            "heart-2",
            "shopping-cart-3",
            "list-bullet-2",
            "marker-3",
            "measure-17",
            "menu-8-2",
            "menu-7",
            "menu-4-2",
            "menu-3-2",
            "menu-2",
            "microsoft",
            "phone-2",
            "phone-call-1",
            "pin-3-2",
            "pin-check",
            "e-remove",
            "single-01-2",
            "i-add",
            "small-triangle-down",
            "small-triangle-left",
            "small-triangle-right",
            "tag-check",
            "tag",
            "clock",
            "time-clock",
            "triangle-left",
            "triangle-right",
            "business-agent",
            "zoom-2",
            "zoom-88",
            "search-zoom-in",
            "search-zoom-out",
            "small-triangle-up",
            "phone-call-2",
            "full-screen",
            "car-parking",
            "transparent",
            "bedroom-1",
            "bedroom-2",
            "search-property",
            "menu-5",
            "circle-simple-right-2",
            "detached-property",
            "armchair",
            "measure-big",
            "b-meeting-2",
            "bulb-63",
            "new-construction",
            "quite-happy",
            "shape-star-1",
            "shape-star-2",
            "star-rate-1",
            "star-rate-2",
            "home-2",
            "home-3",
            "home",
            "home-2-2",
            "home-3-2",
            "home-4",
            "home-search",
            "e-add",
            "e-delete",
            "i-delete-2",
            "i-add-2",
            "arrow-right",
            "arrow-left",
            "arrow-up",
            "arrow-down",
            "a-check",
            "a-add",
            "chart-bar-32",
            "chart-bar-32-2",
            "cart-simple-add",
            "cart-add",
            "cart-add-2",
            "cart-speed-1",
            "cart-speed-2",
            "cart-refresh",
            "ic_format_quote_24px",
            "quote-1",
            "quote-2",
            "a-chat",
            "b-comment",
            "chat",
            "b-chat",
            "f-comment",
            "f-chat",
            "subtitles",
            "voice-recognition",
            "n-edit",
            "d-edit",
            "globe-1",
            "b-twitter",
            "b-twitter-x",
            "b-facebook",
            "b-github-circled",
            "b-pinterest-circled",
            "b-pinterest-squared",
            "b-linkedin",
            "b-github",
            "b-youtube-squared",
            "b-youtube",
            "b-youtube-play",
            "b-dropbox",
            "b-instagram",
            "b-tumblr",
            "b-tumblr-squared",
            "b-skype",
            "b-foursquare",
            "b-vimeo-squared",
            "b-wordpress",
            "b-yahoo",
            "b-reddit",
            "b-reddit-squared",
            "language",
            "b-spotify-1",
            "b-soundcloud",
            "b-vine",
            "b-yelp",
            "b-lastfm",
            "b-lastfm-squared",
            "b-pinterest",
            "b-whatsapp",
            "b-vimeo",
            "b-reddit-alien",
            "b-telegram",
            "b-github-squared",
            "b-flickr",
            "b-flickr-circled",
            "b-vimeo-circled",
            "b-twitter-circled",
            "b-twitter-x-squared",
            "b-linkedin-squared",
            "b-spotify",
            "b-instagram-1",
            "b-evernote",
            "b-soundcloud-1",
            "dot-3",
            "envato",
            "letter-1",
            "mail-2",
            "mail-1",
            "circle-1",
            "bag-2",
            "bag-3"
        );
        $icons = array();
        foreach ($icon_list as $value){
            $icons['lastudioicon-'.$value] = str_replace(array('-', '_'), ' ', $value);
        }
        if($key_only){
            return array_keys($icons);
        }
        return $icons;
    }

    /**
     * @param $icon
     * @param $attributes
     * @param $tag
     * @return bool|mixed|string
     */
    public static function try_get_icon_html( $icon, $attributes = [], $tag = 'i' ) {
        $output = '';
        if ( empty( $icon['library'] ) ) {
            return $output;
        }
        if ( 'svg' === $icon['library'] ) {
            $output = Icons_Manager::render_uploaded_svg_icon( $icon['value'] );
        } else {
            $output = Icons_Manager::render_font_icon( $icon, $attributes, $tag );
        }
        return $output;
    }

	/**
     * Remove all demo data when importing data
	 * @param $element_config
	 *
	 * @return array
	 */
	public function on_import( $element_config ) {

		if(isset($element_config['settings']['query_include'])){
			unset($element_config['settings']['query_include']);
		}
		if(isset($element_config['settings']['query_include_term_ids'])){
			unset($element_config['settings']['query_include_term_ids']);
		}

		return $element_config;
	}
}
