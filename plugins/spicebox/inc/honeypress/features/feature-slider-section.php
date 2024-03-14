<?php

if (!function_exists('spiceb_honeypress_slider_customize_register')) :

    function spiceb_honeypress_slider_customize_register($wp_customize) {
        $selective_refresh = isset($wp_customize->selective_refresh) ? 'postMessage' : 'refresh';

        /* Slider Section */
        $wp_customize->add_section('slider_section', array(
            'title' => __('Slider settings', 'spicebox'),
            'panel' => 'section_settings',
            'priority' => 1,
        ));

        // Enable slider
        $wp_customize->add_setting('home_page_slider_enabled', array('default' => 'on'));
        $wp_customize->add_control('home_page_slider_enabled', array(
            'label' => __('Enable slider', 'spicebox'),
            'section' => 'slider_section',
            'type' => 'radio',
            'choices' => array(
                'on' => __('ON', 'spicebox'),
                'off' => __('OFF', 'spicebox')
            )
        ));

        // Slider Variation
        $wp_customize->add_setting( 'slide_variation', array( 'default' => 'slide') );
        $wp_customize->add_control( 'slide_variation',
        array(
            'label'    => __( 'Slider Background Type', 'honeypress' ),
            'section'  => 'slider_section',
            'type'     => 'select',
            'choices'=>array(
                'slide'=>__('Image', 'honeypress'),
                'video'=>__('Video', 'honeypress')
                )
        ));

        // Slider Video Section
        $wp_customize->add_setting( 'slide_video_upload',
           array(
              'default' => '',
              'transport' => 'refresh',
              'sanitize_callback' => 'absint'
           )
        );
        $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'slide_video_upload',
           array(
              'label' => __( 'Slider video' ),
              'description' => esc_html__( 'Upload your video in .mp4 format and minimize its file size for best results. For this theme the recommended size is 1150 × 2000 pixels.','innofit' ),
              'section' => 'slider_section',
              'mime_type' => 'video',  // Required. Can be image, audio, video, application, text
              'button_labels' => array( // Optional
                 'select' => __( 'Select File' ),
                 'change' => __( 'Change File' ),
                 'default' => __( 'Default' ),
                 'remove' => __( 'Remove' ),
                 'placeholder' => __( 'No file selected' ),
                 'frame_title' => __( 'Select File' ),
                 'frame_button' => __( 'Choose File' ),

              )
           )
        ) );

        //Slider video url
        $wp_customize->add_setting( 'slide_video_url',array(
        'capability'     => 'edit_theme_options',
        'default' => '',
        'sanitize_callback' => '',
        'transport'         => $selective_refresh,
        ));
        $wp_customize->add_control( 'slide_video_url',array(
        'label'   => __('Or, enter a YouTube URL:','honeypress'),
        'section' => 'slider_section',
        'type' => 'text',
        ));

        //Slider Image
        $theme = wp_get_theme();
        if ($theme->name == 'HoneyPress') {
            $wp_customize->add_setting('home_slider_image', array('default' => SPICEB_PLUGIN_URL . 'inc/honeypress/images/slider/slider.jpg',
                'sanitize_callback' => 'esc_url_raw', 'transport' => $selective_refresh,));
        } elseif ($theme->name == 'Radix Multipurpose') {
            $wp_customize->add_setting('home_slider_image', array('default' => SPICEB_PLUGIN_URL . 'inc/honeypress/images/slider/radix-multipurpose-slider.jpg',
                'sanitize_callback' => 'esc_url_raw', 'transport' => $selective_refresh,));
        } elseif ($theme->name == 'Bizhunt') {
            $wp_customize->add_setting('home_slider_image', array('default' => SPICEB_PLUGIN_URL . 'inc/honeypress/images/slider/bizhunt-slider.jpg',
                'sanitize_callback' => 'esc_url_raw', 'transport' => $selective_refresh,));
        } elseif ($theme->name == 'Tromas') {
            $wp_customize->add_setting('home_slider_image', array('default' => SPICEB_PLUGIN_URL . 'inc/honeypress/images/slider/tromas-slider.jpg',
                'sanitize_callback' => 'esc_url_raw', 'transport' => $selective_refresh,));
        }elseif ($theme->name == 'HoneyWaves') {
            $wp_customize->add_setting('home_slider_image', array('default' => SPICEB_PLUGIN_URL . 'inc/honeypress/images/slider/honeywaves-slider.jpg',
                'sanitize_callback' => 'esc_url_raw', 'transport' => $selective_refresh,));
        }elseif ($theme->name == 'HoneyBee') {
            $wp_customize->add_setting('home_slider_image', array('default' => SPICEB_PLUGIN_URL . 'inc/honeypress/images/slider/honeybee-slider.jpg',
                'sanitize_callback' => 'esc_url_raw', 'transport' => $selective_refresh,));
        } elseif ($theme->name == 'Honeypress Dark') {
            $wp_customize->add_setting('home_slider_image', array('default' => SPICEB_PLUGIN_URL . 'inc/honeypress/images/slider/honeypress-dark.jpg',
                'sanitize_callback' => 'esc_url_raw', 'transport' => $selective_refresh,));
        } else {
            $wp_customize->add_setting('home_slider_image', array('default' => SPICEB_PLUGIN_URL . 'inc/honeypress/images/slider/slider.jpg',
                'sanitize_callback' => 'esc_url_raw', 'transport' => $selective_refresh,));
        }
        $wp_customize->add_control(
                new WP_Customize_Image_Control(
                        $wp_customize,
                        'home_slider_image',
                        array(
                    'type' => 'upload',
                    'label' => __('Image', 'spicebox'),
                    'settings' => 'home_slider_image',
                    'section' => 'slider_section',
                        )
                )
        );

        // Image overlay
        $wp_customize->add_setting('slider_image_overlay', array(
            'default' => true,
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('slider_image_overlay', array(
            'label' => __('Enable slider image overlay', 'spicebox'),
            'section' => 'slider_section',
            'type' => 'checkbox',
        ));


        //Slider Background Overlay Color
        $wp_customize->add_setting('slider_overlay_section_color', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'rgba(0,0,0,0.6)',
        ));

        $wp_customize->add_control(new Honeypress_Customize_Alpha_Color_Control($wp_customize, 'slider_overlay_section_color', array(
                    'label' => __('Slider image overlay color', 'spicebox'),
                    'palette' => true,
                    'section' => 'slider_section')
        ));

        //Content Alignment
        $wp_customize->add_setting( 'slider_content_alignment',
        array(
        'default' => 'center',
        'transport' => 'refresh',

        )
    );
    $wp_customize->add_control( new Honeypress_Text_Radio_Button_Custom_Control( $wp_customize, 'slider_content_alignment',
        array(
        'label' => __( 'Slider Content Alignment', 'spicebox' ),
        'section' => 'slider_section',
        'choices' => array(
            'left' => __( 'Left' ), // Required. Setting for this particular radio button choice and the text to display
            'center' => __( 'Center' ), // Required. Setting for this particular radio button choice and the text to display
            'right' => __( 'Right' ) // Required. Setting for this particular radio button choice and the text to display
        )
    )
) );


        // Slider title
        $wp_customize->add_setting('home_slider_title', array(
            'default' => __('Nulla nec dolor sit amet lacus molestie', 'spicebox'),
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'spiceb_honeypress_home_page_sanitize_text',
            'transport' => $selective_refresh,
        ));
        $wp_customize->add_control('home_slider_title', array(
            'label' => __('Title', 'spicebox'),
            'section' => 'slider_section',
            'type' => 'text',
        ));

        //Slider discription
        $wp_customize->add_setting('home_slider_discription', array(
            'default' => __('Sea summo mazim ex, ea errem eleifend definitionem vim. Ut nec hinc dolor possim <br> mei ludus efficiendi ei sea summo mazim ex.', 'spicebox'),
            'sanitize_callback' => 'spiceb_honeypress_home_page_sanitize_text',
            'transport' => $selective_refresh,
        ));
        $wp_customize->add_control('home_slider_discription', array(
            'label' => __('Description', 'spicebox'),
            'section' => 'slider_section',
            'type' => 'textarea',
        ));


        // Slider button text
        $wp_customize->add_setting('home_slider_btn_txt', array(
            'default' => __('Nec Sem', 'spicebox'),
            'sanitize_callback' => 'spiceb_honeypress_home_page_sanitize_text',
            'transport' => $selective_refresh,
        ));
        $wp_customize->add_control('home_slider_btn_txt', array(
            'label' => __('Button Text', 'spicebox'),
            'section' => 'slider_section',
            'type' => 'text',
        ));

        // Slider button link
        $wp_customize->add_setting('home_slider_btn_link', array(
            'default' => __('#', 'spicebox'),
            'sanitize_callback' => 'spiceb_honeypress_home_page_sanitize_text',
            'transport' => $selective_refresh,
        ));
        $wp_customize->add_control('home_slider_btn_link', array(
            'label' => __('Button Link', 'spicebox'),
            'section' => 'slider_section',
            'type' => 'text',
        ));

        // Slider button target
        $wp_customize->add_setting(
                'home_slider_btn_target',
                array(
                    'default' => false,
                    'sanitize_callback' => 'spiceb_honeypress_home_page_sanitize_text',
        ));
        $wp_customize->add_control('home_slider_btn_target', array(
            'label' => __('Open link in new tab', 'spicebox'),
            'section' => 'slider_section',
            'type' => 'checkbox',
        ));

        // Slider button2 text
        $wp_customize->add_setting('home_slider_btn_txt2', array(
            'default' => __('Cras Vitae', 'spicebox'),
            'sanitize_callback' => 'spiceb_honeypress_home_page_sanitize_text',
            'transport' => $selective_refresh,
        ));
        $wp_customize->add_control('home_slider_btn_txt2', array(
            'label' => __('Button 2 Text', 'spicebox'),
            'section' => 'slider_section',
            'type' => 'text',
        ));

        // Slider button link
        $wp_customize->add_setting('home_slider_btn_link2', array(
            'default' => __('#', 'spicebox'),
            'sanitize_callback' => 'spiceb_honeypress_home_page_sanitize_text',
            'transport' => $selective_refresh,
        ));
        $wp_customize->add_control('home_slider_btn_link2', array(
            'label' => __('Button 2 Link', 'spicebox'),
            'section' => 'slider_section',
            'type' => 'text',
        ));

        // Slider button target
        $wp_customize->add_setting(
                'home_slider_btn_target2',
                array(
                    'default' => false,
                    'sanitize_callback' => 'spiceb_honeypress_home_page_sanitize_text',
        ));
        $wp_customize->add_control('home_slider_btn_target2', array(
            'label' => __('Open link in new tab', 'spicebox'),
            'section' => 'slider_section',
            'type' => 'checkbox',
        ));
    }

    add_action('customize_register', 'spiceb_honeypress_slider_customize_register');
endif;

/**
 * Add selective refresh for Front page section section controls.
 */
function spiceb_honeypress_register_home_slider_section_partials($wp_customize) {
    $wp_customize->selective_refresh->add_partial('home_slider_image', array(
        'selector' => '.slider .item',
        'settings' => 'home_slider_image',
    ));

    //Slider section
    $wp_customize->selective_refresh->add_partial('home_slider_title', array(
        'selector' => '.caption-content.text-center .title',
        'settings' => 'home_slider_title',
        'render_callback' => 'spiceb_honeypress_slider_section_title_render_callback',
    ));

    $wp_customize->selective_refresh->add_partial('home_slider_discription', array(
        'selector' => '.caption-content.text-center .description',
        'settings' => 'home_slider_discription',
        'render_callback' => 'spiceb_honeypress_slider_section_discription_render_callback',
    ));

    $wp_customize->selective_refresh->add_partial('home_slider_btn_txt', array(
        'selector' => '.caption-content.text-center .btn-small.btn-default',
        'settings' => 'home_slider_btn_txt',
        'render_callback' => 'spiceb_honeypress_slider_btn_render_callback',
    ));

    $wp_customize->selective_refresh->add_partial('home_slider_btn_txt2', array(
        'selector' => '.about-tbn',
        'settings' => 'home_slider_btn_txt2',
        'render_callback' => 'spiceb_honeypress_slider_btn2_render_callback',
    ));
    //Slider video
    $wp_customize->selective_refresh->add_partial( 'slide_video_url', array(
        'selector'            => '.video-slider',
        'settings'            => 'slide_video_url',

    ) );
}

add_action('customize_register', 'spiceb_honeypress_register_home_slider_section_partials');

function spiceb_honeypress_slider_section_title_render_callback() {
    return get_theme_mod('home_slider_title');
}

function spiceb_honeypress_slider_section_discription_render_callback() {
    return get_theme_mod('home_slider_discription');
}

function spiceb_honeypress_slider_btn_render_callback() {
    return get_theme_mod('home_slider_btn_txt');
}

function spiceb_honeypress_slider_btn2_render_callback() {
    return get_theme_mod('home_slider_btn_txt2');
}
