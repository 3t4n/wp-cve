<?php

namespace WPT\UltimateDiviCarousel\Divi;

/**
 * Helper swiper class to store common functionality.
 */
class Swiper
{
    protected  $container ;
    /**
     * Constructor.
     */
    public function __construct( $container )
    {
        $this->container = $container;
    }
    
    /**
     * Get selector
     */
    public function get_selector( $key, $module )
    {
        $selectors = $this->get_selectors( $module );
        return $selectors[$key]['selector'];
    }
    
    /**
     * Get selector for
     */
    public function get_selectors( $module )
    {
        return [
            'swiper_container'             => [
            'selector' => "{$module->main_css_element} .swiper-container",
            'label'    => __( 'Swiper Container', 'ultimate-carousel-for-divi' ),
        ],
            'slide'                        => [
            'selector' => "{$module->main_css_element} .swiper-slide",
            'label'    => __( 'Slide', 'ultimate-carousel-for-divi' ),
        ],
            'slide_hover'                  => [
            'selector' => "{$module->main_css_element} .swiper-slide:hover",
            'label'    => __( 'Slide - Hover', 'ultimate-carousel-for-divi' ),
        ],
            'card_wrapper'                 => [
            'selector' => "{$module->main_css_element} .wpt-image-card-wrapper",
            'label'    => __( 'Card Wrapper', 'ultimate-carousel-for-divi' ),
        ],
            'text_content_wrapper'         => [
            'selector' => "{$module->main_css_element} .wpt-image-card-content-wrapper",
            'label'    => __( 'Card Content Wrapper', 'ultimate-carousel-for-divi' ),
        ],
            'text_content_wrapper_hover'   => [
            'selector' => "{$module->main_css_element} .swiper-slide:hover .wpt-image-card-content-wrapper",
            'label'    => __( 'Card Content Wrapper - Hover', 'ultimate-carousel-for-divi' ),
        ],
            'inner_card_content'           => [
            'selector' => "{$module->main_css_element}  .wpt-image-card-inner-content-wrapper",
            'label'    => __( 'Inner Card Content', 'ultimate-carousel-for-divi' ),
        ],
            'card_title'                   => [
            'selector' => "{$module->main_css_element} .wpt-image-card-title",
            'label'    => __( 'Card Title', 'ultimate-carousel-for-divi' ),
        ],
            'card_content'                 => [
            'selector' => "{$module->main_css_element} .wpt-image-card-content",
            'label'    => __( 'Card Content', 'ultimate-carousel-for-divi' ),
        ],
            'image_wrapper'                => [
            'selector' => "{$module->main_css_element} .wpt-image-card-image-wrapper",
            'label'    => __( 'Image Wrapper', 'ultimate-carousel-for-divi' ),
        ],
            'image'                        => [
            'selector' => "{$module->main_css_element} .wpt-image-card-image-wrapper img",
            'label'    => __( 'Image', 'ultimate-carousel-for-divi' ),
        ],
            'button'                       => [
            'selector' => "{$module->main_css_element} .et_pb_button",
            'label'    => __( 'Button', 'ultimate-carousel-for-divi' ),
        ],
            'button_wrapper'               => [
            'selector' => "{$module->main_css_element} .et_pb_button_wrapper",
            'label'    => __( 'Button Wrapper', 'ultimate-carousel-for-divi' ),
        ],
            'arrow_container'              => [
            'selector' => "{$module->main_css_element} .swiper-buttton-container",
            'label'    => __( 'Arrow Wrapper', 'ultimate-carousel-for-divi' ),
        ],
            'coverflow_slide_shadow_left'  => [
            'selector' => "{$module->main_css_element} .swiper-container-3d[data-effect='coverflow'] .swiper-slide-shadow-left",
            'label'    => __( 'Coverflow Effect - Slide Shadow Left', 'ultimate-carousel-for-divi' ),
        ],
            'coverflow_slide_shadow_right' => [
            'selector' => "{$module->main_css_element} .swiper-container-3d[data-effect='coverflow'] .swiper-slide-shadow-right",
            'label'    => __( 'Coverflow Effect - Slide Shadow Right', 'ultimate-carousel-for-divi' ),
        ],
            'arrow_prev'                   => [
            'selector' => "{$module->main_css_element} .swiper-button-prev",
            'label'    => __( 'Prev Arrow', 'ultimate-carousel-for-divi' ),
        ],
            'arrow_next'                   => [
            'selector' => "{$module->main_css_element} .swiper-button-next",
            'label'    => __( 'Next Arrow', 'ultimate-carousel-for-divi' ),
        ],
            'arrow_nav'                    => [
            'selector' => "{$module->main_css_element} .swiper-nav",
            'label'    => __( 'Arrow Nav - Prev & Next', 'ultimate-carousel-for-divi' ),
        ],
            'pagination_container'         => [
            'selector' => "{$module->main_css_element} .swiper-pagination",
            'label'    => __( 'Pagination Wrapper', 'ultimate-carousel-for-divi' ),
        ],
            'pagination_bullet'            => [
            'selector' => "{$module->main_css_element} .swiper-pagination-bullet",
            'label'    => __( 'Pagination Bullet', 'ultimate-carousel-for-divi' ),
        ],
            'pagination_bullet_active'     => [
            'selector' => "{$module->main_css_element} .swiper-pagination-bullet-active",
            'label'    => __( 'Pagination Bullet - Active', 'ultimate-carousel-for-divi' ),
        ],
            'pagination_bullet_inactive'   => [
            'selector' => "{$module->main_css_element} .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)",
            'label'    => __( 'Pagination Bullet - Inactive', 'ultimate-carousel-for-divi' ),
        ],
        ];
    }
    
    /**
     * Enqueue js/css assets
     */
    public function enqueue_assets()
    {
        wp_enqueue_script(
            'wpt-swiper-script',
            $this->container['plugin_url'] . "/js/swiper/swiper-bundle.min.js",
            [ 'jquery' ],
            $this->container['plugin_version'],
            true
        );
        wp_enqueue_script(
            'wpt-swiper',
            $this->container['plugin_url'] . "/js/swiper/script.js",
            [ 'wpt-swiper-script' ],
            $this->container['plugin_version'],
            true
        );
        wp_enqueue_style(
            'wpt-swiper-style',
            $this->container['plugin_url'] . "/css/swiper/swiper-bundle.min.css",
            [],
            $this->container['plugin_version'],
            false
        );
        wp_enqueue_style(
            'wpt-swiper-custom',
            $this->container['plugin_url'] . "/css/swiper/style.css",
            [ 'wpt-swiper-style' ],
            $this->container['plugin_version'],
            false
        );
    }
    
    /**
     * Set common advanced divi module settings.
     */
    public function set_advanced_fields_config( &$config, $module )
    {
    }
    
    /**
     * Common settings modal toggles.
     */
    public function set_settings_modal_toggles( &$toggles )
    {
        // general
        $toggles['general']['toggles']['carousel'] = [
            'title'    => esc_html__( 'Carousel', 'ultimate-carousel-for-divi' ),
            'priority' => 3,
        ];
        // advanced
        if ( !isset( $toggles['advanced'] ) ) {
            $toggles['advanced'] = [];
        }
        if ( !isset( $toggles['advanced']['toggles'] ) ) {
            $toggles['advanced']['toggles'] = [];
        }
    }
    
    /**
     * Get common divi module fields
     */
    public function get_fields( $module )
    {
        $fields = [];
        $fields = $this->get_carousel_setting_fields();
        return $fields;
    }
    
    public function get_carousel_setting_fields()
    {
        $fields = [];
        $fields['effect'] = [
            'label'           => esc_html__( 'Effect', 'ultimate-carousel-for-divi' ),
            'type'            => 'select',
            'option_category' => 'layout',
            'options'         => [
            'slide'     => esc_html__( 'Slide', 'ultimate-carousel-for-divi' ),
            'coverflow' => esc_html__( 'Coverflow', 'ultimate-carousel-for-divi' ),
        ],
            'default'         => $this->get_default( 'effect' ),
            'tab_slug'        => 'general',
            'toggle_slug'     => 'carousel',
            'description'     => esc_html__( 'Transition effect. Can be "Slide" or "Coverflow""', 'ultimate-carousel-for-divi' ),
        ];
        $fields['slides_per_view'] = [
            'label'           => esc_html__( 'Slides Per View', 'ultimate-carousel-for-divi' ),
            'type'            => 'range',
            'range_settings'  => [
            'min'  => 1,
            'max'  => 100,
            'step' => 1,
        ],
            'option_category' => 'layout',
            'mobile_options'  => false,
            'unitless'        => true,
            'default'         => $this->get_default( 'slides_per_view' ),
            'tab_slug'        => 'general',
            'toggle_slug'     => 'carousel',
            'description'     => esc_html__( 'Number of slides per view (slides visible at the same time on slider\'s container).', 'ultimate-carousel-for-divi' ),
        ];
        $fields['space_between'] = [
            'label'          => esc_html__( 'Space Between Slides', 'ultimate-carousel-for-divi' ),
            'type'           => 'range',
            'range_settings' => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ],
            'tab_slug'       => 'general',
            'toggle_slug'    => 'carousel',
            'description'    => esc_html__( 'Distance between slides (in pixels)', 'ultimate-carousel-for-divi' ),
            'mobile_options' => false,
            'unitless'       => true,
            'default'        => $this->get_default( 'space_between' ),
        ];
        $fields['initial_slide'] = [
            'label'          => esc_html__( 'Initial Slide', 'ultimate-carousel-for-divi' ),
            'type'           => 'range',
            'range_settings' => [
            'min'  => 0,
            'max'  => 500,
            'step' => 1,
        ],
            'tab_slug'       => 'general',
            'toggle_slug'    => 'carousel',
            'description'    => esc_html__( 'Index number of initial slide.', 'ultimate-carousel-for-divi' ),
            'show_if'        => [],
            'allowed_units'  => [ '' ],
            'default_unit'   => '',
            'validate_unit'  => false,
            'default'        => $this->get_default( 'initial_slide' ),
        ];
        $fields['centered_slides'] = [
            'label'       => esc_html__( 'Centered Slides', 'ultimate-carousel-for-divi' ),
            'type'        => 'yes_no_button',
            'options'     => [
            'off' => esc_html__( 'No', 'ultimate-carousel-for-divi' ),
            'on'  => esc_html__( 'Yes', 'ultimate-carousel-for-divi' ),
        ],
            'tab_slug'    => 'general',
            'toggle_slug' => 'carousel',
            'description' => esc_html__( 'If "Yes", then active slide will be centered, not always on the left side.', 'ultimate-carousel-for-divi' ),
            'show_if'     => [],
            'default'     => $this->get_default( 'centered_slides' ),
        ];
        $fields['enable_coverflow_slide_shadow'] = [
            'label'           => esc_html__( 'Enable Coverflow Slide Shadow', 'ultimate-carousel-for-divi' ),
            'type'            => 'yes_no_button',
            'option_category' => 'configuration',
            'options'         => [
            'on'  => esc_html__( 'Yes', 'ultimate-carousel-for-divi' ),
            'off' => esc_html__( 'No', 'ultimate-carousel-for-divi' ),
        ],
            'default'         => $this->get_default( 'enable_coverflow_slide_shadow' ),
            'show_if'         => [
            'effect' => 'coverflow',
        ],
            'tab_slug'        => 'general',
            'toggle_slug'     => 'carousel',
            'description'     => esc_html__( 'Enable Slide Shadow For Coverflow Effect.', 'ultimate-carousel-for-divi' ),
        ];
        $fields['coverflow_shadow_color'] = [
            'label'        => esc_html__( 'Shadow Color', 'ultimate-carousel-for-divi' ),
            'type'         => 'color-alpha',
            'custom_color' => true,
            'show_if'      => [
            'effect'                        => 'coverflow',
            'enable_coverflow_slide_shadow' => 'on',
        ],
            'default'      => $this->get_default( 'coverflow_shadow_color' ),
            'tab_slug'     => 'general',
            'toggle_slug'  => 'carousel',
            'description'  => esc_html__( 'Here you can select color for the Shadow.', 'ultimate-carousel-for-divi' ),
        ];
        $fields['coverflow_rotate'] = [
            'label'           => esc_html__( 'Coverflow Rotate', 'ultimate-carousel-for-divi' ),
            'type'            => 'range',
            'option_category' => 'font_option',
            'range_settings'  => [
            'min'  => '1',
            'max'  => '360',
            'step' => '1',
        ],
            'unitless'        => true,
            'show_if'         => [
            'effect' => 'coverflow',
        ],
            'default'         => $this->get_default( 'coverflow_rotate' ),
            'mobile_options'  => false,
            'tab_slug'        => 'general',
            'toggle_slug'     => 'carousel',
            'description'     => esc_html__( 'Coverflow Rotate Slide.', 'ultimate-carousel-for-divi' ),
        ];
        $fields['coverflow_depth'] = [
            'label'           => esc_html__( 'Coverflow Depth', 'ultimate-carousel-for-divi' ),
            'type'            => 'range',
            'option_category' => 'font_option',
            'range_settings'  => [
            'min'  => '1',
            'max'  => '1000',
            'step' => '1',
        ],
            'unitless'        => true,
            'show_if'         => [
            'effect' => 'coverflow',
        ],
            'default'         => $this->get_default( 'coverflow_depth' ),
            'mobile_options'  => false,
            'tab_slug'        => 'general',
            'toggle_slug'     => 'carousel',
            'description'     => esc_html__( 'Coverflow Depth Slide.', 'ultimate-carousel-for-divi' ),
        ];
        $fields['autoplay'] = [
            'label'           => esc_html__( 'Autoplay', 'ultimate-carousel-for-divi' ),
            'type'            => 'yes_no_button',
            'option_category' => 'configuration',
            'options'         => [
            'on'  => esc_html__( 'Yes', 'ultimate-carousel-for-divi' ),
            'off' => esc_html__( 'No', 'ultimate-carousel-for-divi' ),
        ],
            'default'         => $this->get_default( 'autoplay' ),
            'tab_slug'        => 'general',
            'toggle_slug'     => 'carousel',
            'description'     => esc_html__( 'Toggle "Yes" to set autoplay for the carousel', 'ultimate-carousel-for-divi' ),
        ];
        $fields['autoplay_speed'] = [
            'label'           => esc_html__( 'Autoplay Delay', 'ultimate-carousel-for-divi' ),
            'type'            => 'range',
            'range_settings'  => [
            'min'  => '0',
            'max'  => '50000',
            'step' => '100',
        ],
            'option_category' => 'configuration',
            'default'         => $this->get_default( 'autoplay_speed' ),
            'show_if'         => [
            'autoplay' => 'on',
        ],
            'tab_slug'        => 'general',
            'toggle_slug'     => 'carousel',
            'unitless'        => true,
            'description'     => esc_html__( 'Delay between transitions (in ms)', 'ultimate-carousel-for-divi' ),
        ];
        $fields['pause_on_hover'] = [
            'label'           => esc_html__( 'Pause On Hover', 'ultimate-carousel-for-divi' ),
            'type'            => 'yes_no_button',
            'option_category' => 'configuration',
            'options'         => [
            'on'  => esc_html__( 'Yes', 'ultimate-carousel-for-divi' ),
            'off' => esc_html__( 'No', 'ultimate-carousel-for-divi' ),
        ],
            'default'         => $this->get_default( 'pause_on_hover' ),
            'show_if'         => [
            'autoplay' => 'on',
        ],
            'tab_slug'        => 'general',
            'toggle_slug'     => 'carousel',
            'description'     => esc_html__( 'Toggle "Yes" to pause the carousel slide on mouse hover.', 'ultimate-carousel-for-divi' ),
        ];
        $fields['slide_transition_duration'] = [
            'label'           => esc_html__( 'Transition Duration', 'ultimate-carousel-for-divi' ),
            'type'            => 'range',
            'range_settings'  => [
            'min'  => '0',
            'max'  => '5000',
            'step' => '100',
        ],
            'option_category' => 'configuration',
            'default'         => $this->get_default( 'slide_transition_duration' ),
            'tab_slug'        => 'general',
            'toggle_slug'     => 'carousel',
            'unitless'        => true,
            'description'     => esc_html__( 'Duration of transition between slides (in ms)', 'ultimate-carousel-for-divi' ),
        ];
        return $fields;
    }
    
    /**
     * Set the styles of the slider.
     */
    public function set_styles( $module, $render_slug, $props )
    {
        $effect = $this->container['divi']->get_prop_value( $module, 'effect' );
        $slides_per_view = $this->container['divi']->get_prop_value( $module, 'slides_per_view' );
        $enable_coverflow_slide_shadow = $this->container['divi']->get_prop_value( $module, 'enable_coverflow_slide_shadow' );
        $coverflow_shadow_color = $this->container['divi']->get_prop_value( $module, 'coverflow_shadow_color' );
        $coverflow_rotate = $this->container['divi']->get_prop_value( $module, 'coverflow_rotate' );
        $coverflow_depth = $this->container['divi']->get_prop_value( $module, 'coverflow_depth' );
        $slider_loop = $this->container['divi']->get_prop_value( $module, 'slider_loop' );
        $autoplay = $this->container['divi']->get_prop_value( $module, 'autoplay' );
        $autoplay_speed = $this->container['divi']->get_prop_value( $module, 'autoplay_speed' );
        $pause_on_hover = $this->container['divi']->get_prop_value( $module, 'pause_on_hover' );
        $show_arrow = $this->container['divi']->get_prop_value( $module, 'show_arrow' );
        $show_arrow_on_hover = $this->container['divi']->get_prop_value( $module, 'show_arrow_on_hover' );
        $show_control_dot = $this->container['divi']->get_prop_value( $module, 'show_control_dot' );
        $coverflow_shadow_color = $this->container['divi']->get_prop_value( $module, 'coverflow_shadow_color' );
        $control_dot_active_color = $this->container['divi']->get_prop_value( $module, 'control_dot_active_color' );
        $control_dot_inactive_color = $this->container['divi']->get_prop_value( $module, 'control_dot_inactive_color' );
        $slide_transition_duration = $this->container['divi']->get_prop_value( $module, 'slide_transition_duration' );
        $arrow_font_size = $this->container['divi']->get_prop_value( $module, 'arrow_font_size' );
        $arrow_color = $this->container['divi']->get_prop_value( $module, 'arrow_color' );
        $arrow_background = $this->container['divi']->get_prop_value( $module, 'arrow_background' );
        $dot_pagination_width = $this->container['divi']->get_prop_value( $module, 'dot_pagination_width' );
        $dot_pagination_height = $this->container['divi']->get_prop_value( $module, 'dot_pagination_height' );
        $inactive_dot_opacity = $this->container['divi']->get_prop_value( $module, 'inactive_dot_opacity' );
        $equalize_height = $this->container['divi']->get_prop_value( $module, 'equalize_height' );
        $content_vertical_alignment = $this->container['divi']->get_prop_value( $module, 'content_vertical_alignment' );
        $overlay_content_over_image = $this->container['divi']->get_prop_value( $module, 'overlay_content_over_image' );
        $content_visiblity = $this->container['divi']->get_prop_value( $module, 'content_visiblity' );
        $dot_nav_border_radius = $this->container['divi']->get_prop_value( $module, 'dot_nav_border_radius' );
        $classes = [ 'wpt-ultimate-carousel', 'content-vertical-align-' . $content_vertical_alignment ];
        $module->add_classname( $classes );
        $module::set_style( $render_slug, [
            'selector'    => $this->get_selector( 'coverflow_slide_shadow_left', $module ),
            'declaration' => sprintf( 'background-image: linear-gradient(to left,%1$s,rgba(0,0,0,0));', esc_attr( $coverflow_shadow_color ) ),
        ] );
        $module::set_style( $render_slug, [
            'selector'    => $this->get_selector( 'coverflow_slide_shadow_right', $module ),
            'declaration' => sprintf( 'background-image: linear-gradient(to right,%1$s,rgba(0,0,0,0));', esc_attr( $coverflow_shadow_color ) ),
        ] );
        $module_class = $module->get_module_order_class( $render_slug );
    }
    
    /**
     * Get default for given keys
     */
    public function get_default( $key )
    {
        $defaults = $this->get_defaults();
        return ( isset( $defaults[$key] ) ? $defaults[$key] : '' );
    }
    
    /**
     * Get defaults
     */
    public function get_defaults()
    {
        $defaults = [
            'effect'                              => 'slide',
            'space_between'                       => '20',
            'slides_per_view'                     => 3,
            'enable_coverflow_slide_shadow'       => 'off',
            'coverflow_shadow_color'              => '#cccccc',
            'coverflow_rotate'                    => 40,
            'initial_slide'                       => '0',
            'centered_slides'                     => 'off',
            'coverflow_depth'                     => 100,
            'slider_loop'                         => 'off',
            'autoplay'                            => 'on',
            'autoplay_speed'                      => 3000,
            'pause_on_hover'                      => 'on',
            'slide_transition_duration'           => 1000,
            'show_arrow'                          => 'off',
            'show_arrow_on_hover'                 => 'off',
            'show_control_dot'                    => 'off',
            'arrow_font_size'                     => '27px',
            'arrow_color'                         => '#ffffff',
            'arrow_background'                    => '#000000',
            'arrow_position'                      => 'middle-overlap',
            'pagination_position'                 => 'center',
            'pagination_bullets_custom_margin'    => '0|8px|0|0|false|false',
            'dot_pagination_width'                => '7px',
            'dot_pagination_height'               => '7px',
            'dot_nav_border_radius'               => '50%',
            'inactive_dot_opacity'                => '0.2',
            'swiper_container_custom_margin'      => '0|0|0|0|true|true',
            'swiper_container_custom_padding'     => '0|0|0|0|true|true',
            'pagination_container_custom_padding' => '20px|0|0|0|false|false',
            'pagination_container_custom_margin'  => '0|0|0|0|false|false',
            'equalize_height'                     => 'on',
            'content_vertical_alignment'          => 'end',
            'overlay_content_over_image'          => 'off',
            'content_visiblity'                   => 'always',
            'content_custom_padding'              => '20px|20px|20px|20px|true|true',
        ];
        return $defaults;
    }

}