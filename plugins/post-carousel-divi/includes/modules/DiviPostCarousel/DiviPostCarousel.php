<?php

class LWP_PostCarouselModule extends ET_Builder_Module
{
    public  $slug = 'lwp_post_carousel' ;
    public  $vb_support = 'on' ;
    protected  $module_credits = array(
        'module_uri' => 'https://www.learnhowwp.com/divi-post-carousel/',
        'author'     => 'learnhowwp.com',
        'author_uri' => 'https://www.learnhowwp.com/',
    ) ;
    public function init()
    {
        $this->name = esc_html__( 'Post Carousel', 'lwp-divi-module' );
        $this->main_css_element = '%%order_class%%';
        $this->icon = 'k';
    }
    
    public function get_fields()
    {
        $post_fields = array(
            'post_count'                => array(
            'label'           => esc_html__( 'Post Count', 'lwp-divi-module' ),
            'description'     => esc_html__( 'The number of posts to show in the carousel', 'lwp-divi-module' ),
            'type'            => 'text',
            'option_category' => 'basic_option',
            'toggle_slug'     => 'content_settings',
            'default'         => '9',
        ),
            'featured_image_size'       => array(
            'label'           => esc_html__( 'Image Size', 'lwp-divi-module' ),
            'description'     => esc_html__( 'Choose the size of the featured image', 'lwp-divi-module' ),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => array(
            'thumbnail'                       => esc_html__( 'thumbnail', 'lwp-divi-module' ),
            'medium'                          => esc_html__( 'medium', 'lwp-divi-module' ),
            'large'                           => esc_html__( 'large', 'lwp-divi-module' ),
            'full'                            => esc_html__( 'full', 'lwp-divi-module' ),
            'et-pb-post-main-image'           => esc_html__( 'et-pb-post-main-image', 'lwp-divi-module' ),
            'et-pb-post-main-image-fullwidth' => esc_html__( 'et-pb-post-main-image-fullwidth', 'lwp-divi-module' ),
        ),
            'default'         => 'et-pb-post-main-image',
            'toggle_slug'     => 'content_settings',
        ),
            'post_categories'           => array(
            'label'           => esc_html__( 'Include Categories', 'lwp-divi-module' ),
            'description'     => esc_html__( 'Choose the size of the featured image', 'lwp-divi-module' ),
            'type'            => 'categories',
            'option_category' => 'basic_option',
            'toggle_slug'     => 'content_settings',
        ),
            'use_manual_excerpt'        => array(
            'label'           => esc_html__( 'Use Manual Excerpt', 'lwp-divi-module' ),
            'type'            => 'yes_no_button',
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Disable this option if you want to ignore manually defined excerpts and always generate it automatically.', 'lwp-divi-module' ),
            'toggle_slug'     => 'content_settings',
            'options'         => array(
            'on'  => esc_html__( 'Yes', 'lwp-divi-module' ),
            'off' => esc_html__( 'No', 'lwp-divi-module' ),
        ),
            'default'         => 'off',
        ),
            'slides_show'               => array(
            'label'           => esc_html__( 'Slides Count', 'lwp-divi-module' ),
            'description'     => esc_html__( 'Choose the number of images to show in the carousel', 'lwp-divi-module' ),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => array(
            '1' => esc_html__( '1', 'lwp-divi-module' ),
            '2' => esc_html__( '2', 'lwp-divi-module' ),
            '3' => esc_html__( '3', 'lwp-divi-module' ),
            '4' => esc_html__( '4', 'lwp-divi-module' ),
            '5' => esc_html__( '5', 'lwp-divi-module' ),
            '6' => esc_html__( '6', 'lwp-divi-module' ),
        ),
            'mobile_options'  => true,
            'default'         => '3',
            'default_tablet'  => '2',
            'default_phone'   => '1',
            'toggle_slug'     => 'element_settings',
        ),
            'slides_scroll'             => array(
            'label'           => esc_html__( 'Slides Scroll', 'lwp-divi-module' ),
            'description'     => esc_html__( 'Choose the number of images to scroll on the press of a button or on automatic animation', 'lwp-divi-module' ),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => array(
            '1' => esc_html__( '1', 'lwp-divi-module' ),
            '2' => esc_html__( '2', 'lwp-divi-module' ),
            '3' => esc_html__( '3', 'lwp-divi-module' ),
            '4' => esc_html__( '4', 'lwp-divi-module' ),
            '5' => esc_html__( '5', 'lwp-divi-module' ),
            '6' => esc_html__( '6', 'lwp-divi-module' ),
        ),
            'default'         => '1',
            'mobile_options'  => true,
            'toggle_slug'     => 'element_settings',
        ),
            'show_dots'                 => array(
            'label'           => esc_html__( 'Show Dots', 'lwp-divi-module' ),
            'type'            => 'yes_no_button',
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Select if you want to show dots under the carousel', 'lwp-divi-module' ),
            'toggle_slug'     => 'element_settings',
            'options'         => array(
            'off' => esc_html__( 'No', 'lwp-divi-module' ),
            'on'  => esc_html__( 'Yes', 'lwp-divi-module' ),
        ),
            'default'         => 'on',
            'mobile_options'  => true,
        ),
            'show_arrows'               => array(
            'label'           => esc_html__( 'Show Arrows', 'lwp-divi-module' ),
            'type'            => 'yes_no_button',
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Select if you want to show arrows on the carousel', 'lwp-divi-module' ),
            'toggle_slug'     => 'element_settings',
            'options'         => array(
            'on'  => esc_html__( 'Yes', 'lwp-divi-module' ),
            'off' => esc_html__( 'No', 'lwp-divi-module' ),
        ),
            'default'         => 'on',
            'mobile_options'  => true,
        ),
            'autoplay_animation'        => array(
            'label'           => esc_html__( 'Autoplay', 'lwp-divi-module' ),
            'type'            => 'yes_no_button',
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Select if you want the carousel to keep moving to next slides automatically', 'lwp-divi-module' ),
            'toggle_slug'     => 'animation_settings',
            'tab_slug'        => 'advanced',
            'options'         => array(
            'off' => esc_html__( 'No', 'lwp-divi-module' ),
            'on'  => esc_html__( 'Yes', 'lwp-divi-module' ),
        ),
            'show_if_not'     => array(
            'layout' => 'sync',
        ),
        ),
            'pause_on_hover'            => array(
            'label'           => esc_html__( 'Pause On Hover', 'lwp-divi-module' ),
            'type'            => 'yes_no_button',
            'option_category' => 'basic_option',
            'tab_slug'        => 'advanced',
            'description'     => esc_html__( 'Select if you want to stop the autoplay animation on hover', 'lwp-divi-module' ),
            'toggle_slug'     => 'animation_settings',
            'options'         => array(
            'on'  => esc_html__( 'Yes', 'lwp-divi-module' ),
            'off' => esc_html__( 'No', 'lwp-divi-module' ),
        ),
            'default'         => 'on',
            'show_if'         => array(
            'autoplay_animation' => 'on',
        ),
        ),
            'autoplay_animation_speed'  => array(
            'label'           => esc_html__( 'Automatic Animation Speed (in ms)', 'lwp-divi-module' ),
            'description'     => esc_html__( 'Set the animation speed for the autoplay animation.', 'lwp-divi-module' ),
            'type'            => 'range',
            'toggle_slug'     => 'animation_settings',
            'tab_slug'        => 'advanced',
            'option_category' => 'basic_option',
            'range_settings'  => array(
            'min'  => 0,
            'max'  => 5000,
            'step' => 50,
        ),
            'default'         => '2000ms',
            'description'     => esc_html__( 'Speed up or slow down your animation by adjusting the animation duration. Units are in milliseconds and the default animation duration is one second.', 'lwp-divi-module' ),
            'validate_unit'   => true,
            'fixed_unit'      => 'ms',
            'fixed_range'     => true,
            'show_if'         => array(
            'autoplay_animation' => 'on',
        ),
        ),
            'slide_animation_speed'     => array(
            'label'           => esc_html__( 'Slide Animation Speed (in ms)', 'lwp-divi-module' ),
            'description'     => esc_html__( 'Set the animation speed for transition between slides', 'lwp-divi-module' ),
            'type'            => 'range',
            'toggle_slug'     => 'animation_settings',
            'tab_slug'        => 'advanced',
            'option_category' => 'basic_option',
            'range_settings'  => array(
            'min'  => 0,
            'max'  => 5000,
            'step' => 50,
        ),
            'default'         => '300ms',
            'validate_unit'   => true,
            'fixed_unit'      => 'ms',
            'fixed_range'     => true,
        ),
            'infinite_animation'        => array(
            'label'           => esc_html__( 'Infinite Animation', 'lwp-divi-module' ),
            'type'            => 'yes_no_button',
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Select if you want enable infinite animation. The carousel will start from the first image once it reaches the end.', 'lwp-divi-module' ),
            'toggle_slug'     => 'animation_settings',
            'tab_slug'        => 'advanced',
            'options'         => array(
            'off' => esc_html__( 'No', 'lwp-divi-module' ),
            'on'  => esc_html__( 'Yes', 'lwp-divi-module' ),
        ),
            'show_if'         => array(
            'layout' => 'default',
        ),
        ),
            'arrow_color'               => array(
            'label'           => esc_html__( 'Arrow Color', 'lwp-divi-module' ),
            'type'            => 'color-alpha',
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Select the colors of the arrows in the carousel.', 'lwp-divi-module' ),
            'toggle_slug'     => 'element_design_settings',
            'tab_slug'        => 'advanced',
            'mobile_options'  => true,
            'hover'           => 'tabs',
        ),
            'arrow_bg'                  => array(
            'label'          => esc_html__( 'Arrow Background', 'lwp-divi-module' ),
            'type'           => 'color-alpha',
            'description'    => esc_html__( 'Select the background color for the arrows in the carousel.', 'lwp-divi-module' ),
            'tab_slug'       => 'advanced',
            'toggle_slug'    => 'element_design_settings',
            'mobile_options' => true,
            'hover'          => 'tabs',
        ),
            'dots_color'                => array(
            'label'           => esc_html__( 'Dots Color', 'lwp-divi-module' ),
            'type'            => 'color-alpha',
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Select color for dots in the carousel', 'lwp-divi-module' ),
            'toggle_slug'     => 'element_design_settings',
            'tab_slug'        => 'advanced',
            'mobile_options'  => true,
        ),
            'layout'                    => array(
            'label'           => esc_html__( 'Layout', 'lwp-divi-module' ),
            'description'     => esc_html__( 'Choose the layout for the slider.', 'lwp-divi-module' ),
            'type'            => 'hidden',
            'option_category' => 'basic_option',
            'options'         => array(
            'default' => esc_html__( 'Default', 'lwp-divi-module' ),
        ),
            'default'         => 'default',
            'toggle_slug'     => 'layout',
            'tab_slug'        => 'advanced',
        ),
            'center_padding'            => array(
            'label'           => esc_html__( 'Center Padding', 'lwp-divi-module' ),
            'description'     => esc_html__( 'Set the center padding for the center mode slider.', 'lwp-divi-module' ),
            'type'            => 'range',
            'toggle_slug'     => 'layout',
            'tab_slug'        => 'advanced',
            'option_category' => 'basic_option',
            'range_settings'  => array(
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ),
            'default'         => '60px',
            'description'     => esc_html__( 'Speed up or slow down your animation by adjusting the animation duration. Units are in milliseconds and the default animation duration is one second.', 'lwp-divi-module' ),
            'validate_unit'   => true,
            'fixed_unit'      => 'px',
            'fixed_range'     => true,
            'show_if'         => array(
            'layout' => 'center',
        ),
        ),
            'vertical_layout'           => array(
            'label'           => esc_html__( 'Vertical Layout', 'lwp-divi-module' ),
            'type'            => 'hidden',
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Select if you want enable sliding for this slider.', 'lwp-divi-module' ),
            'toggle_slug'     => 'layout',
            'tab_slug'        => 'advanced',
            'options'         => array(
            'off' => esc_html__( 'No', 'lwp-divi-module' ),
            'on'  => esc_html__( 'Yes', 'lwp-divi-module' ),
        ),
            'show_if'         => array(
            'layout' => array( 'default', 'center' ),
        ),
        ),
            'adaptive_height'           => array(
            'label'           => esc_html__( 'Adaptive Height', 'lwp-divi-module' ),
            'type'            => 'yes_no_button',
            'option_category' => 'basic_option',
            'description'     => esc_html__( 'Select if you want enable adaptive height.', 'lwp-divi-module' ),
            'toggle_slug'     => 'layout',
            'tab_slug'        => 'advanced',
            'options'         => array(
            'off' => esc_html__( 'No', 'lwp-divi-module' ),
            'on'  => esc_html__( 'Yes', 'lwp-divi-module' ),
        ),
            'default'         => 'on',
        ),
            'arrow_location'            => array(
            'label'           => esc_html__( 'Arrow Location', 'lwp-divi-module' ),
            'description'     => esc_html__( 'Choose the location of the arrows.', 'lwp-divi-module' ),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => array(
            'side'   => esc_html__( 'Side', 'lwp-divi-module' ),
            'top'    => esc_html__( 'Top', 'lwp-divi-module' ),
            'bottom' => esc_html__( 'Bottom', 'lwp-divi-module' ),
        ),
            'default'         => 'side',
            'toggle_slug'     => 'layout',
            'tab_slug'        => 'advanced',
            'show_if_not'     => array(
            'layout' => 'sync',
        ),
        ),
            'arrow_location_sync'       => array(
            'label'           => esc_html__( 'Arrow Location', 'lwp-divi-module' ),
            'description'     => esc_html__( 'Choose the location of the arrows.', 'lwp-divi-module' ),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => array(
            'side'   => esc_html__( 'Side', 'lwp-divi-module' ),
            'bottom' => esc_html__( 'Bottom', 'lwp-divi-module' ),
        ),
            'default'         => 'side',
            'toggle_slug'     => 'layout',
            'tab_slug'        => 'advanced',
            'show_if'         => array(
            'layout' => 'sync',
        ),
        ),
            'arrow_alignment'           => array(
            'label'           => esc_html__( 'Arrow Alignment', 'lwp-divi-module' ),
            'description'     => esc_html__( 'Choose the alignment of the arrows.', 'lwp-divi-module' ),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => array(
            'left'   => esc_html__( 'Left', 'lwp-divi-module' ),
            'right'  => esc_html__( 'Right', 'lwp-divi-module' ),
            'center' => esc_html__( 'Center', 'lwp-divi-module' ),
        ),
            'default'         => 'left',
            'toggle_slug'     => 'layout',
            'tab_slug'        => 'advanced',
            'show_if_not'     => array(
            'arrow_location' => 'side',
            'layout'         => 'sync',
        ),
        ),
            'arrow_alignment_sync'      => array(
            'label'           => esc_html__( 'Arrow Alignment', 'lwp-divi-module' ),
            'description'     => esc_html__( 'Choose the alignment of the arrows.', 'lwp-divi-module' ),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => array(
            'left'   => esc_html__( 'Left', 'lwp-divi-module' ),
            'right'  => esc_html__( 'Right', 'lwp-divi-module' ),
            'center' => esc_html__( 'Center', 'lwp-divi-module' ),
        ),
            'default'         => 'left',
            'toggle_slug'     => 'layout',
            'tab_slug'        => 'advanced',
            'show_if_not'     => array(
            'arrow_location_sync' => 'side',
        ),
            'show_if'         => array(
            'layout' => 'sync',
        ),
        ),
            'arrow_alignment_side'      => array(
            'label'           => esc_html__( 'Arrow Alignment', 'lwp-divi-module' ),
            'description'     => esc_html__( 'Choose the alignment of the arrows.', 'lwp-divi-module' ),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => array(
            'top'    => esc_html__( 'Top', 'lwp-divi-module' ),
            'center' => esc_html__( 'Center', 'lwp-divi-module' ),
            'bottom' => esc_html__( 'Bottom', 'lwp-divi-module' ),
        ),
            'default'         => 'center',
            'toggle_slug'     => 'layout',
            'tab_slug'        => 'advanced',
            'show_if'         => array(
            'arrow_location' => 'side',
            'layout'         => array( 'default', 'center' ),
        ),
        ),
            'arrow_alignment_side_sync' => array(
            'label'           => esc_html__( 'Arrow Alignment', 'lwp-divi-module' ),
            'description'     => esc_html__( 'Choose the alignment of the arrows.', 'lwp-divi-module' ),
            'type'            => 'select',
            'option_category' => 'basic_option',
            'options'         => array(
            'top'    => esc_html__( 'Top', 'lwp-divi-module' ),
            'center' => esc_html__( 'Center', 'lwp-divi-module' ),
            'bottom' => esc_html__( 'Bottom', 'lwp-divi-module' ),
        ),
            'default'         => 'center',
            'toggle_slug'     => 'layout',
            'tab_slug'        => 'advanced',
            'show_if'         => array(
            'arrow_location_sync' => 'side',
            'layout'              => 'sync',
        ),
        ),
            'post_background'           => array(
            'label'       => esc_html__( 'Post Background', 'lwp-divi-module' ),
            'type'        => 'color-alpha',
            'description' => esc_html__( 'Select the background color for the posts.', 'lwp-divi-module' ),
            'tab_slug'    => 'advanced',
            'toggle_slug' => 'post_styles',
        ),
        );
        $post_fields_paid = array();
        $post_fields = array_merge( $post_fields, $post_fields_paid );
        return $post_fields;
    }
    
    public function get_settings_modal_toggles()
    {
        return array(
            'advanced' => array(
            'toggles' => array(
            'content_settings'        => esc_html__( 'Content', 'lwp-divi-module' ),
            'element_settings'        => esc_html__( 'Carousel Elements', 'lwp-divi-module' ),
            'element_design_settings' => array(
            'title'    => esc_html__( 'Carousel Elements', 'lwp-divi-module' ),
            'priority' => 20,
        ),
            'animation_settings'      => array(
            'title'    => esc_html__( 'Carousel Animation', 'lwp-divi-module' ),
            'priority' => 21,
        ),
            'image_styles'            => array(
            'title'    => esc_html__( 'Image', 'lwp-divi-module' ),
            'priority' => 22,
        ),
            'main_image_styles'       => array(
            'title'    => esc_html__( 'Main Image', 'lwp-divi-module' ),
            'priority' => 23,
        ),
            'center_image_styles'     => array(
            'title'    => esc_html__( 'Center Image', 'lwp-divi-module' ),
            'priority' => 23,
        ),
            'post_styles'             => array(
            'title'    => esc_html__( 'Post Styles', 'lwp-divi-module' ),
            'priority' => 24,
        ),
        ),
        ),
        );
    }
    
    public function get_advanced_fields_config()
    {
        return array(
            'borders'        => array(
            'default'      => array(),
            'image'        => array(
            'css'          => array(
            'main' => array(
            'border_radii'  => "{$this->main_css_element} .small-slider .slick-slide img",
            'border_styles' => "{$this->main_css_element} .small-slider .slick-slide img",
        ),
        ),
            'label_prefix' => esc_html__( 'Image', 'lwp-divi-module' ),
            'tab_slug'     => 'advanced',
            'toggle_slug'  => 'image_styles',
        ),
            'main_image'   => array(
            'css'             => array(
            'main' => array(
            'border_radii'  => "{$this->main_css_element} .big-slider .slick-slide img",
            'border_styles' => "{$this->main_css_element} .big-slider .slick-slide img",
        ),
        ),
            'label_prefix'    => esc_html__( 'Main Image', 'lwp-divi-module' ),
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'main_image_styles',
            'depends_show_if' => 'sync',
            'depends_on'      => array( 'layout' ),
        ),
            'center_image' => array(
            'css'             => array(
            'main' => array(
            'border_radii'  => "{$this->main_css_element} .lwp-slick-slider.lwp-center-slider .slick-slide.slick-center img, {$this->main_css_element} .lwp-slick-slider.lwp-center-slider .slick-slide[aria-hidden=\"true\"]:not([tabindex=\"-1\"]) + .slick-cloned[aria-hidden=\"true\"] img",
            'border_styles' => "{$this->main_css_element} .lwp-slick-slider.lwp-center-slider .slick-slide.slick-center img, {$this->main_css_element} .lwp-slick-slider.lwp-center-slider .slick-slide[aria-hidden=\"true\"]:not([tabindex=\"-1\"]) + .slick-cloned[aria-hidden=\"true\"] img",
        ),
        ),
            'label_prefix'    => esc_html__( 'Center Image', 'lwp-divi-module' ),
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'center_image_styles',
            'depends_show_if' => 'center',
            'depends_on'      => array( 'layout' ),
        ),
            'arrow_border' => array(
            'css'          => array(
            'main' => array(
            'border_radii'  => "{$this->main_css_element} .slick-next:before, {$this->main_css_element} .slick-prev:before",
            'border_styles' => "{$this->main_css_element} .slick-next:before, {$this->main_css_element} .slick-prev:before",
        ),
        ),
            'label_prefix' => esc_html__( 'Arrow', 'lwp-divi-module' ),
            'tab_slug'     => 'advanced',
            'toggle_slug'  => 'element_design_settings',
            'style'        => 'solid',
        ),
            'post'         => array(
            'css'          => array(
            'main' => array(
            'border_radii'  => "{$this->main_css_element} .small-slider .lwp_post_carousel_item_inner",
            'border_styles' => "{$this->main_css_element} .small-slider .lwp_post_carousel_item_inner",
        ),
        ),
            'label_prefix' => esc_html__( 'Post', 'lwp-divi-module' ),
            'tab_slug'     => 'advanced',
            'toggle_slug'  => 'post_styles',
            'defaults'     => array(
            'border_radii'  => 'on||||',
            'border_styles' => array(
            'width' => '1px',
            'color' => '#d8d8d8',
            'style' => 'solid',
        ),
        ),
        ),
        ),
            'box_shadow'     => array(
            'default'      => array(),
            'image'        => array(
            'css'          => array(
            'main'    => "{$this->main_css_element} .small-slider .slick-slide img",
            'overlay' => 'inset',
        ),
            'label_prefix' => esc_html__( 'Image', 'lwp-divi-module' ),
            'tab_slug'     => 'advanced',
            'toggle_slug'  => 'image_styles',
        ),
            'main_image'   => array(
            'css'             => array(
            'main'    => "{$this->main_css_element} .big-slider .slick-slide img",
            'overlay' => 'inset',
        ),
            'label_prefix'    => esc_html__( 'Main Image', 'lwp-divi-module' ),
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'main_image_styles',
            'depends_show_if' => 'sync',
            'depends_on'      => array( 'layout' ),
        ),
            'center_image' => array(
            'css'             => array(
            'main'    => "{$this->main_css_element} .lwp-slick-slider.lwp-center-slider .slick-slide.slick-center img, {$this->main_css_element} .lwp-slick-slider.lwp-center-slider .slick-slide[aria-hidden=\"true\"]:not([tabindex=\"-1\"]) + .slick-cloned[aria-hidden=\"true\"] img",
            'overlay' => 'inset',
        ),
            'label_prefix'    => esc_html__( 'Main Image', 'lwp-divi-module' ),
            'tab_slug'        => 'advanced',
            'toggle_slug'     => 'center_image_styles',
            'depends_show_if' => 'center',
            'depends_on'      => array( 'layout' ),
        ),
            'post'         => array(
            'css'          => array(
            'main'    => "{$this->main_css_element} .small-slider .lwp_post_carousel_item_inner",
            'overlay' => 'inset',
        ),
            'label_prefix' => esc_html__( 'Post', 'lwp-divi-module' ),
            'tab_slug'     => 'advanced',
            'toggle_slug'  => 'post_styles',
        ),
        ),
            'margin_padding' => array(
            'css' => array(
            'important' => 'all',
        ),
        ),
            'fonts'          => array(
            'post_title'   => array(
            'css'          => array(
            'main'      => "{$this->main_css_element} h2.lwp_post_carousel_heading, {$this->main_css_element} h1.lwp_post_carousel_heading, {$this->main_css_element} h3.lwp_post_carousel_heading, {$this->main_css_element} h4.lwp_post_carousel_heading, {$this->main_css_element} h5.lwp_post_carousel_heading, {$this->main_css_element} h6.lwp_post_carousel_heading",
            'important' => 'all',
        ),
            'header_level' => array(
            'default' => 'h4',
        ),
            'label'        => esc_html__( 'Post Title', 'lwp-divi-module' ),
        ),
            'post_excerpt' => array(
            'css'   => array(
            'main' => "{$this->main_css_element} .lwp_post_carousel_excerpt",
        ),
            'label' => esc_html__( 'Excerpt', 'lwp-divi-module' ),
        ),
            'post_meta'    => array(
            'css'   => array(
            'main' => "{$this->main_css_element} .lwp_post_carousel_meta",
        ),
            'label' => esc_html__( 'Meta', 'lwp-divi-module' ),
        ),
        ),
            'button'         => array(
            'button' => array(
            'label'          => esc_html__( 'Button', 'lwp-divi-module' ),
            'css'            => array(
            'main'         => "{$this->main_css_element} .et_pb_button",
            'limited_main' => "{$this->main_css_element} .et_pb_button",
            'alignment'    => "{$this->main_css_element} .et_pb_button_wrapper",
        ),
            'box_shadow'     => array(
            'css' => array(
            'main' => '%%order_class%% .et_pb_button',
        ),
        ),
            'margin_padding' => array(
            'css' => array(
            'main'      => "%%order_class%% .et_pb_button",
            'important' => 'all',
        ),
        ),
            'use_alignment'  => true,
        ),
        ),
        );
    }
    
    public function get_custom_css_fields_config()
    {
        return array(
            'arrow'            => array(
            'label'    => esc_html__( 'Arrow', 'lwp-divi-module' ),
            'selector' => '%%order_class%% .slick-next,%%order_class%% .slick-prev',
        ),
            'dots'             => array(
            'label'    => esc_html__( 'Dots', 'lwp-divi-module' ),
            'selector' => '%%order_class%% .slick-dots li button',
        ),
            'image'            => array(
            'label'    => esc_html__( 'Images', 'lwp-divi-module' ),
            'selector' => '%%order_class%% .lwp-slick-slider .slick-slide img',
        ),
            'center_image'     => array(
            'label'    => esc_html__( 'Center Image', 'lwp-divi-module' ),
            'selector' => '%%order_class%% .lwp-slick-slider.lwp-center-slider .slick-slide.slick-center img',
        ),
            'main_image'       => array(
            'label'    => esc_html__( 'Main Image', 'lwp-divi-module' ),
            'selector' => '%%order_class%% .big-slider .slick-slide img',
        ),
            'slider_container' => array(
            'label'    => esc_html__( 'Slider Container', 'lwp-divi-module' ),
            'selector' => '%%order_class%% .slick-slider',
        ),
            'slide_container'  => array(
            'label'    => esc_html__( 'Slide Container', 'lwp-divi-module' ),
            'selector' => '%%order_class%% .lwp-slick-slider .slick-slide',
        ),
        );
    }
    
    private function on_off_map( $setting )
    {
        
        if ( $setting == 'off' || $setting == '' ) {
            return 'false';
        } else {
            return 'true';
        }
    
    }
    
    public function render( $attrs, $content, $render_slug )
    {
        /*Post settings */
        $post_count = $this->props['post_count'];
        $featured_image_size = $this->props['featured_image_size'];
        $post_background = $this->props['post_background'];
        $post_categories = $this->props['post_categories'];
        $use_manual_excerpt = $this->props['use_manual_excerpt'];
        $order = 'DESC';
        $orderby = 'date';
        $post_offset = 0;
        $show_title = 'on';
        $show_featured_image = 'on';
        $show_excerpt = 'on';
        $show_author = 'on';
        $show_date = 'on';
        $show_categories = 'on';
        $show_comments = 'on';
        $show_button = 'on';
        $post_meta_separator = '|';
        $excerpt_length = 170;
        $button_text = 'Read More';
        $date_format = 'M j, Y';
        $carousel_style = 'default';
        $carousel_image_position = 'left';
        $overlay_content_color = '';
        $hover_transition_speed = '';
        $overlay_padding = '';
        $content_padding = '';
        $content_margin = '';
        /*Carousel settings*/
        $show_arrows = $this->props['show_arrows'];
        $show_arrows_tablet = $this->props['show_arrows_tablet'];
        $show_arrows_phone = $this->props['show_arrows_phone'];
        $show_arrows_last_edited = $this->props['show_arrows_last_edited'];
        $show_dots = $this->props['show_dots'];
        $show_dots_tablet = $this->props['show_dots_tablet'];
        $show_dots_phone = $this->props['show_dots_phone'];
        $show_dots_last_edited = $this->props['show_dots_last_edited'];
        $slides_scroll = $this->props['slides_scroll'];
        $slides_scroll_tablet = $this->props['slides_scroll_tablet'];
        $slides_scroll_phone = $this->props['slides_scroll_phone'];
        $slides_scroll_last_edited = $this->props['slides_scroll_last_edited'];
        $slides_show = $this->props['slides_show'];
        $slides_show_tablet = $this->props['slides_show_tablet'];
        $slides_show_phone = $this->props['slides_show_phone'];
        $slides_show_last_edited = $this->props['slides_show_last_edited'];
        $infinite_animation = $this->props['infinite_animation'];
        $autoplay_animation = $this->props['autoplay_animation'];
        $autoplay_animation_speed = $this->props['autoplay_animation_speed'];
        $slide_animation_speed = $this->props['slide_animation_speed'];
        $pause_on_hover = $this->props['pause_on_hover'];
        $arrow_color = $this->props['arrow_color'];
        $arrow_color_tablet = $this->props['arrow_color_tablet'];
        $arrow_color_phone = $this->props['arrow_color_phone'];
        $arrow_color_last_edited = $this->props['arrow_color_last_edited'];
        $arrow_color_hover = $this->get_hover_value( 'arrow_color' );
        $arrow_bg = $this->props['arrow_bg'];
        $arrow_bg_tablet = $this->props['arrow_bg_tablet'];
        $arrow_bg_phone = $this->props['arrow_bg_phone'];
        $arrow_bg_last_edited = $this->props['arrow_bg_last_edited'];
        $arrow_bg_hover = $this->get_hover_value( 'arrow_bg' );
        $arrow_location = $this->props['arrow_location'];
        $arrow_location_sync = $this->props['arrow_location_sync'];
        $arrow_alignment = $this->props['arrow_alignment'];
        $arrow_alignment_sync = $this->props['arrow_alignment_sync'];
        $arrow_alignment_side = $this->props['arrow_alignment_side'];
        $arrow_alignment_side_sync = $this->props['arrow_alignment_side_sync'];
        $dots_color = $this->props['dots_color'];
        $dots_color_tablet = $this->props['dots_color_tablet'];
        $dots_color_phone = $this->props['dots_color_phone'];
        $dots_color_last_edited = $this->props['dots_color_last_edited'];
        $layout = $this->props['layout'];
        $vertical_layout = $this->props['vertical_layout'];
        $adaptive_height = $this->props['adaptive_height'];
        $center_padding = $this->props['center_padding'];
        $helper_class = 'lwp-' . $layout . '-slider';
        
        if ( $layout === 'sync' ) {
            
            if ( $arrow_location_sync == 'side' ) {
                $helper_class = $helper_class . ' ' . 'lwp-' . $arrow_location_sync . '-' . $arrow_alignment_side_sync;
            } else {
                $helper_class = $helper_class . ' ' . 'lwp-' . $arrow_location_sync . '-' . $arrow_alignment_sync;
            }
        
        } else {
            
            if ( $arrow_location == 'side' ) {
                $helper_class = $helper_class . ' ' . 'lwp-' . $arrow_location . '-' . $arrow_alignment_side;
            } else {
                $helper_class = $helper_class . ' ' . 'lwp-' . $arrow_location . '-' . $arrow_alignment;
            }
        
        }
        
        $slides_show_responsive_active = et_pb_get_responsive_status( $slides_show_last_edited );
        
        if ( $slides_show_responsive_active == false ) {
            $slides_show_tablet = $slides_show;
            $slides_show_phone = '1';
            //By default the carousel shows 1 slide for phones
            if ( $layout == 'sync' ) {
                //If layout is synced slider it should default to 3
                $slides_show_phone = '3';
            }
        } else {
            $slides_show_tablet = ( $slides_show_tablet == '' ? $slides_show : $slides_show_tablet );
            $slides_show_phone = ( $slides_show_phone == '' ? $slides_show_tablet : $slides_show_phone );
        }
        
        $slides_scroll_responsive_active = et_pb_get_responsive_status( $slides_scroll_last_edited );
        
        if ( $slides_scroll_responsive_active == false ) {
            $slides_scroll_tablet = $slides_scroll;
            $slides_scroll_phone = '1';
        } else {
            $slides_scroll_tablet = ( $slides_scroll_tablet == '' ? $slides_scroll : $slides_scroll_tablet );
            $slides_scroll_phone = ( $slides_scroll_phone == '' ? $slides_scroll_tablet : $slides_scroll_phone );
        }
        
        $show_arrows_resposnive_active = et_pb_get_responsive_status( $show_arrows_last_edited );
        
        if ( $show_arrows_resposnive_active == false ) {
            $show_arrows_phone = $show_arrows_tablet = $show_arrows;
        } else {
            if ( $show_arrows_tablet == '' ) {
                $show_arrows_tablet = $show_arrows;
            }
            if ( $show_arrows_phone == '' ) {
                $show_arrows_phone = $show_arrows_tablet;
            }
        }
        
        $show_dots_resposnive_active = et_pb_get_responsive_status( $show_dots_last_edited );
        
        if ( $show_dots_resposnive_active == false ) {
            $show_dots_phone = $show_dots_tablet = $show_dots;
        } else {
            if ( $show_dots_tablet == '' ) {
                $show_dots_tablet = $show_dots;
            }
            if ( $show_dots_phone == '' ) {
                $show_dots_phone = $show_dots_tablet;
            }
        }
        
        //Post Output
        $post_title_level = 'h4';
        if ( isset( $this->attrs_unprocessed['post_title_level'] ) ) {
            $post_title_level = $this->attrs_unprocessed['post_title_level'];
        }
        //Button props generated by advanced options
        $button_custom = $this->props['custom_button'];
        $button_rel = $this->props['button_rel'];
        $button_icon = $this->props['button_icon'];
        $post_query = new WP_Query( array(
            'post_type'      => 'post',
            'posts_per_page' => $post_count,
            'offset'         => $post_offset,
            'cat'            => $post_categories,
            'post_status'    => 'publish',
            'order'          => $order,
            'orderby'        => $orderby,
        ) );
        $post_output = '';
        while ( $post_query->have_posts() ) {
            $post_query->the_post();
            $button_output = '';
            if ( $show_button == 'on' ) {
                $button_output = $this->render_button( array(
                    'button_text'    => $button_text,
                    'button_url'     => get_permalink(),
                    'url_new_window' => 'off',
                    'button_custom'  => $button_custom,
                    'button_rel'     => $button_rel,
                    'custom_icon'    => $button_icon,
                ) );
            }
            $post_thumbnail_output = '';
            $has_featured_image = false;
            $post_permalink = get_permalink();
            $featured_image_src = get_the_post_thumbnail( get_the_ID(), $featured_image_size );
            $featured_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $featured_image_size );
            if ( has_post_thumbnail() && $show_featured_image == 'on' ) {
                $has_featured_image = true;
            }
            $post_title_output = '';
            if ( $show_title == 'on' ) {
                $post_title_output = '<div class="lwp_post_carousel_title">
					<' . $post_title_level . ' class="lwp_post_carousel_heading">
						<a class="lwp_post_title" href="' . get_permalink() . '">' . get_the_title() . '</a>
					</' . $post_title_level . '>
				</div>';
            }
            $post_excerpt_output = '';
            
            if ( $show_excerpt == 'on' ) {
                $excerpt_text = '';
                
                if ( has_excerpt() && $use_manual_excerpt == 'on' ) {
                    $excerpt_text = get_the_excerpt();
                } else {
                    $excerpt_text = et_core_intentionally_unescaped( wpautop( et_delete_post_first_video( strip_shortcodes( truncate_post(
                        $excerpt_length,
                        false,
                        '',
                        true
                    ) ) ) ), 'html' );
                }
                
                $post_excerpt_output = '<div class="lwp_post_carousel_excerpt">' . $excerpt_text . '</div>';
            }
            
            $post_meta_output = '';
            $post_meta_array = array();
            $post_author_output = '';
            $post_date_output = '';
            $post_category_output = '';
            $post_comment_output = '';
            
            if ( $show_author == 'on' ) {
                $post_author_output = '<span class="lwp_meta_by">' . esc_html__( "by", "lwp-divi-module" ) . '</span> ' . get_the_author_posts_link();
                array_push( $post_meta_array, $post_author_output );
            }
            
            
            if ( $show_date == 'on' ) {
                $post_date_output = '<span class="lwp_meta_date">' . get_the_time( $date_format ) . '</span>';
                array_push( $post_meta_array, $post_date_output );
            }
            
            
            if ( $show_categories == 'on' ) {
                $post_category_output = '<span class="lwp_meta_categories">' . get_the_category_list( ',' ) . '</span>';
                array_push( $post_meta_array, $post_category_output );
            }
            
            
            if ( $show_comments == 'on' ) {
                $post_comment_output = '<span class="lwp_meta_comments">' . get_comments_number_text( __( "0 Comments", "lwp-divi-module" ) ) . '</span>';
                array_push( $post_meta_array, $post_comment_output );
            }
            
            $post_meta_output = $post_meta_output . '<p class="lwp_post_carousel_meta">';
            $meta_count = count( $post_meta_array );
            for ( $i = 0 ;  $i < $meta_count ;  $i++ ) {
                $post_meta_output = $post_meta_output . $post_meta_array[$i];
                
                if ( $meta_count == 1 || $i == $meta_count - 1 ) {
                    continue;
                } else {
                    $post_meta_output = $post_meta_output . ' <span class="lwp_meta_separator">' . $post_meta_separator . '</span> ';
                }
            
            }
            $post_meta_output = $post_meta_output . '</p>';
            $featured_image = ( is_array( $featured_image_url ) ? $featured_image_url[0] : $featured_image_url );
            $post_output = $post_output . lwp_post_carousel_style(
                $carousel_style,
                $post_title_output,
                $post_meta_output,
                $post_excerpt_output,
                $button_output,
                $featured_image_src,
                $post_permalink,
                $has_featured_image,
                $carousel_image_position,
                $featured_image
            );
        }
        wp_reset_postdata();
        
        if ( isset( $content_margin ) && !empty($content_margin) ) {
            $values = explode( "|", $content_margin );
            if ( empty($values[0]) ) {
                $values[0] = '-20%';
            }
            if ( empty($values[1]) ) {
                $values[1] = 'auto';
            }
            if ( empty($values[2]) ) {
                $values[2] = '0px';
            }
            if ( empty($values[3]) ) {
                $values[3] = '5px';
            }
            $style = 'margin:' . $values[0] . ' ' . $values[1] . ' ' . $values[2] . ' ' . $values[3] . ';';
            ET_Builder_Element::set_style( $render_slug, array(
                'selector'    => '%%order_class%% .lwp_carousel_overlap.lwp_has_featured_image .lwp_overlap_content_outer',
                'declaration' => sprintf( '%1$s', esc_attr( $style ) ),
            ) );
        }
        
        
        if ( isset( $overlay_padding ) && !empty($overlay_padding) ) {
            $values = explode( "|", $overlay_padding );
            for ( $i = 0 ;  $i < 4 ;  $i++ ) {
                if ( empty($values[$i]) ) {
                    $values[$i] = '40px';
                }
            }
            $style = 'padding:' . $values[0] . ' ' . $values[1] . ' ' . $values[2] . ' ' . $values[3] . ';';
            ET_Builder_Element::set_style( $render_slug, array(
                'selector'    => '%%order_class%% .lwp_post_carousel_item_inner.lwp_carousel_overlay_box',
                'declaration' => sprintf( '%1$s', esc_attr( $style ) ),
            ) );
        }
        
        
        if ( isset( $content_padding ) && !empty($content_padding) ) {
            $values = explode( "|", $content_padding );
            for ( $i = 0 ;  $i < 4 ;  $i++ ) {
                if ( empty($values[$i]) ) {
                    $values[$i] = '15px';
                }
            }
            $style = 'padding:' . $values[0] . ' ' . $values[1] . ' ' . $values[2] . ' ' . $values[3] . ';';
            ET_Builder_Element::set_style( $render_slug, array(
                'selector'    => '%%order_class%% .lwp_carousel_overlay_box .lwp_content_overlay',
                'declaration' => sprintf( '%1$s', esc_attr( $style ) ),
            ) );
        }
        
        
        if ( isset( $post_background ) && !empty($post_background) ) {
            $style = 'background-color:' . $post_background . ';';
            ET_Builder_Element::set_style( $render_slug, array(
                'selector'    => '%%order_class%% .lwp_post_carousel_item_inner',
                'declaration' => sprintf( '%1$s', esc_attr( $style ) ),
            ) );
        }
        
        
        if ( isset( $overlay_content_color ) && !empty($overlay_content_color) ) {
            $style = 'background-color:' . $overlay_content_color . ';';
            ET_Builder_Element::set_style( $render_slug, array(
                'selector'    => '%%order_class%% .lwp_carousel_overlay .lwp_content_overlay',
                'declaration' => sprintf( '%1$s', esc_attr( $style ) ),
            ) );
        }
        
        
        if ( isset( $hover_transition_speed ) ) {
            $style = 'transition-duration:' . $hover_transition_speed . ';';
            ET_Builder_Element::set_style( $render_slug, array(
                'selector'    => '%%order_class%% .lwp_carousel_hover .lwp_content_overlay',
                'declaration' => sprintf( '%1$s', esc_attr( $style ) ),
            ) );
        }
        
        $show_arrows = self::on_off_map( $show_arrows );
        $show_arrows_tablet = self::on_off_map( $show_arrows_tablet );
        $show_arrows_phone = self::on_off_map( $show_arrows_phone );
        $show_dots = self::on_off_map( $show_dots );
        $show_dots_tablet = self::on_off_map( $show_dots_tablet );
        $show_dots_phone = self::on_off_map( $show_dots_phone );
        $vertical_layout = self::on_off_map( $vertical_layout );
        $adaptive_height = self::on_off_map( $adaptive_height );
        $pause_on_hover = self::on_off_map( $pause_on_hover );
        $infinite_animation = self::on_off_map( $infinite_animation );
        $autoplay_animation = self::on_off_map( $autoplay_animation );
        $autoplay_animation_speed = chop( $autoplay_animation_speed, "ms" );
        $slide_animation_speed = chop( $slide_animation_speed, "ms" );
        $data_slick = '';
        //variable for data-slick attributes.
        $sync_slider_html = '';
        //variable to store html for the sync slider
        
        if ( $layout == 'default' || $layout == '' ) {
            //Slick attribute if layout is default
            $data_slick = sprintf(
                'data-slick=\'{ "vertical":%17$s, "slidesToShow": %1$s, "slidesToScroll": %2$s, "dots":%3$s, "arrows":%4$s, "infinite":%5$s, "autoplay":%6$s, "autoplaySpeed":%7$s, "pauseOnHover":%16$s, "adaptiveHeight":%18$s, "speed":%19$s, "responsive": [ { "breakpoint": 980, "settings": { "slidesToShow": %8$s, "slidesToScroll": %10$s, "arrows":%12$s,"dots":%14$s } } ,{ "breakpoint": 767, "settings": { "slidesToShow": %9$s, "slidesToScroll": %11$s, "arrows":%13$s,"dots":%15$s } } ] }\'',
                $slides_show,
                $slides_scroll,
                $show_dots,
                $show_arrows,
                $infinite_animation,
                $autoplay_animation,
                $autoplay_animation_speed,
                $slides_show_tablet,
                $slides_show_phone,
                $slides_scroll_tablet,
                $slides_scroll_phone,
                $show_arrows_tablet,
                $show_arrows_phone,
                $show_dots_tablet,
                $show_dots_phone,
                $pause_on_hover,
                $vertical_layout,
                $adaptive_height,
                $slide_animation_speed
            );
        } else {
            
            if ( $layout == 'center' ) {
                //Slick attribute if layout is set to center mode
                $data_slick = sprintf(
                    'data-slick=\'{ "vertical":%17$s, "centerMode": true, "centerPadding": "%18$s",  "slidesToShow": %1$s, "dots":%3$s, "arrows":%4$s, "infinite":true, "autoplay":%6$s, "autoplaySpeed":%7$s, "pauseOnHover":%16$s, "adaptiveHeight":%19$s, "speed":%20$s, "responsive": [ { "breakpoint": 980, "settings": { "centerMode": true, "slidesToShow": %8$s, "arrows":%12$s,"dots":%14$s } } ,{ "breakpoint": 767, "settings": {"centerMode": true, "slidesToShow": %9$s, "arrows":%13$s,"dots":%15$s } } ] }\'',
                    $slides_show,
                    $slides_scroll,
                    $show_dots,
                    $show_arrows,
                    $infinite_animation,
                    $autoplay_animation,
                    $autoplay_animation_speed,
                    $slides_show_tablet,
                    $slides_show_phone,
                    $slides_scroll_tablet,
                    $slides_scroll_phone,
                    $show_arrows_tablet,
                    $show_arrows_phone,
                    $show_dots_tablet,
                    $show_dots_phone,
                    $pause_on_hover,
                    $vertical_layout,
                    $center_padding,
                    $adaptive_height,
                    $slide_animation_speed
                );
            } else {
                
                if ( $layout == 'sync' ) {
                    //Slick attribute and html if layout is set to synced slider
                    $data_slick = sprintf(
                        'data-slick=\'{"asNavFor": ".big-slider","slidesToShow": %1$s, "slidesToScroll": %2$s, "dots":%3$s, "arrows":%4$s, "focusOnSelect": true, "adaptiveHeight":%17$s, "speed":%18$s, "responsive": [ { "breakpoint": 980, "settings": { "slidesToShow": %8$s, "slidesToScroll": %10$s, "arrows":%12$s,"dots":%14$s } } ,{ "breakpoint": 767, "settings": { "slidesToShow": %9$s, "slidesToScroll": %11$s, "arrows":%13$s,"dots":%15$s } } ] }\'',
                        $slides_show,
                        $slides_scroll,
                        $show_dots,
                        $show_arrows,
                        $infinite_animation,
                        $autoplay_animation,
                        $autoplay_animation_speed,
                        $slides_show_tablet,
                        $slides_show_phone,
                        $slides_scroll_tablet,
                        $slides_scroll_phone,
                        $show_arrows_tablet,
                        $show_arrows_phone,
                        $show_dots_tablet,
                        $show_dots_phone,
                        $pause_on_hover,
                        $adaptive_height,
                        $slide_animation_speed
                    );
                    $sync_slider_html = sprintf( '<section class="lwp-slick-slider slider big-slider" data-slick=\'{"slidesToShow": 1, "slidesToScroll": 1, "arrows":false, "fade":true, "asNavFor":".small-slider", "adaptiveHeight":true}\'>
					%1$s
		 		</section>', $images_output );
                }
            
            }
        
        }
        
        
        if ( isset( $arrow_color ) ) {
            $arrow_color_responsive_active = et_pb_get_responsive_status( $arrow_color_last_edited );
            $arrow_color_values = array(
                'desktop' => $arrow_color,
                'tablet'  => ( $arrow_color_responsive_active ? $arrow_color_tablet : '' ),
                'phone'   => ( $arrow_color_responsive_active ? $arrow_color_phone : '' ),
            );
            et_pb_responsive_options()->generate_responsive_css(
                $arrow_color_values,
                '%%order_class%% .slick-next:before,%%order_class%% .slick-prev:before',
                'color',
                $render_slug,
                '',
                'color'
            );
        }
        
        if ( isset( $arrow_color_hover ) && $arrow_color_hover != '' ) {
            ET_Builder_Element::set_style( $render_slug, array(
                'selector'    => '%%order_class%% .slick-next:hover:before,%%order_class%% .slick-prev:hover:before',
                'declaration' => sprintf( 'color:%1$s;', $arrow_color_hover ),
            ) );
        }
        
        if ( isset( $arrow_bg ) ) {
            $arrow_bg_responsive_active = et_pb_get_responsive_status( $arrow_bg_last_edited );
            $arrow_bg_values = array(
                'desktop' => $arrow_bg,
                'tablet'  => ( $arrow_bg_responsive_active ? $arrow_bg_tablet : '' ),
                'phone'   => ( $arrow_bg_responsive_active ? $arrow_bg_phone : '' ),
            );
            et_pb_responsive_options()->generate_responsive_css(
                $arrow_bg_values,
                '%%order_class%% .slick-next:before,%%order_class%% .slick-prev:before',
                'background-color',
                $render_slug,
                '',
                'color'
            );
        }
        
        if ( isset( $arrow_bg_hover ) && $arrow_bg_hover != '' ) {
            ET_Builder_Element::set_style( $render_slug, array(
                'selector'    => '%%order_class%% .slick-next:hover,%%order_class%% .slick-prev:hover',
                'declaration' => sprintf( 'background-color:%1$s;', $arrow_bg_hover ),
            ) );
        }
        
        if ( isset( $dots_color ) ) {
            $dots_color_responsive_active = et_pb_get_responsive_status( $dots_color_last_edited );
            $dots_color_values = array(
                'desktop' => $dots_color,
                'tablet'  => ( $dots_color_responsive_active ? $dots_color_tablet : '' ),
                'phone'   => ( $dots_color_responsive_active ? $dots_color_phone : '' ),
            );
            et_pb_responsive_options()->generate_responsive_css(
                $dots_color_values,
                '%%order_class%% .slick-dots li button',
                'background-color',
                $render_slug,
                '',
                'color'
            );
        }
        
        return sprintf(
            '%3$s
			<section class="lwp-slick-slider slider small-slider %4$s" %2$s>
				%1$s
			</section>',
            $post_output,
            $data_slick,
            $sync_slider_html,
            $helper_class
        );
    }

}
new LWP_PostCarouselModule();