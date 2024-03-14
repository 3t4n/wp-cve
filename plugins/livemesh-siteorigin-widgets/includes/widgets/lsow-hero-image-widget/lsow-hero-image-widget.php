<?php

/*
Widget Name: Hero Header
Description: Display custom header content with option to set HTML5/YouTube video or parallax image background.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/

class LSOW_Hero_Image_Widget extends SiteOrigin_Widget {

    function __construct() {
        parent::__construct(
            'lsow-hero-image',
            __('Hero Header', 'livemesh-so-widgets'),
            array(
                'description' => __('Display a hero background with video or image background.', 'livemesh-so-widgets'),
                'panels_icon' => 'dashicons dashicons-minus',
                'help' => LSOW_PLUGIN_HELP_URL. '#hero-header'
            ),
            array(),
            array(
                'title' => array(
                    'type' => 'text',
                    'label' => __('Title', 'livemesh-so-widgets'),
                ),

                'header_type' => array(
                    'type' => 'radio',
                    'label' => __('Header Type', 'livemesh-so-widgets'),
                    'default' => 'standard',
                    'state_emitter' => array(
                        'callback' => 'select',
                        'args' => array('header_type')
                    ),
                    'options' => array(
                        'standard' => __('Standard', 'livemesh-so-widgets'),
                        'custom' => __('Custom', 'livemesh-so-widgets'),
                    )
                ),

                'custom_header' => array(
                    'type' => 'section',
                    'label' => __('Custom Header', 'livemesh-so-widgets'),
                    'state_handler' => array(
                        'header_type[custom]' => array('show'),
                        '_else[header_type]' => array('hide'),
                    ),
                    'fields' => array(
                        'custom' => array(
                            'type' => 'tinymce',
                            'label' => __('Custom text', 'livemesh-so-widgets'),
                        ),

                        'custom_css' => array(
                            'type' => 'textarea',
                            'label' => __('Custom CSS for presentation of the Custom header elements. Will be embedded inline with the page.', 'livemesh-so-widgets'),
                            'rows' => 20
                        ),
                    )
                ),


                'standard_header' => array(
                    'type' => 'section',
                    'label' => __('Standard Header', 'livemesh-so-widgets'),
                    'state_handler' => array(
                        'header_type[standard]' => array('show'),
                        '_else[header_type]' => array('hide'),
                    ),
                    'fields' => array(
                        'heading' => array(
                            'type' => 'text',
                            'label' => __('Header text', 'livemesh-so-widgets'),
                        ),

                        'heading_font' => array(
                            'type' => 'font',
                            'label' => __('Heading font', 'livemesh-so-widgets'),
                            'default' => '',
                        ),

                        'subheading' => array(
                            'type' => 'text',
                            'label' => __('Sub-heading text', 'livemesh-so-widgets'),
                            'optional' => 'true',
                        ),

                        'button_text' => array(
                            'type' => 'text',
                            'label' => __('Button text', 'livemesh-so-widgets'),
                        ),

                        'button_url' => array(
                            'type' => 'link',
                            'label' => __('Button URL', 'livemesh-so-widgets'),
                        ),

                        'new_window' => array(
                            'type' => 'checkbox',
                            'label' => __('Open URL in a new window', 'livemesh-so-widgets'),
                        ),
                    )
                ),

                'pointer_down_url' => array(
                    'type' => 'text',
                    'label' => __('URL for Pointer Down', 'livemesh-so-widgets'),
                    'description' => __('If an URL for the pointer down is specified, the hero image will sport a pointer down indicator to help user smooth scroll to the section indicated by this URL.', 'livemesh-so-widgets'),
                ),

                'background' => array(
                    'type' => 'section',
                    'label' => __('Background', 'livemesh-so-widgets'),
                    'fields' => array(

                        'bg_type' => array(
                            'type' => 'radio',
                            'label' => __('Background Type', 'livemesh-so-widgets'),
                            'default' => 'parallax',
                            'state_emitter' => array(
                                'callback' => 'select',
                                'args' => array('bg_type')
                            ),
                            'options' => array(
                                'cover' => __('Cover Image', 'livemesh-so-widgets'),
                                'parallax' => __('Parallax Image', 'livemesh-so-widgets'),
                                'youtube' => __('YouTube Video', 'livemesh-so-widgets'),
                                'html5video' => __('HTML5 Video', 'livemesh-so-widgets'),
                            )
                        ),

                        'youtube_video' => array(
                            'type' => 'section',
                            'label' => __('YouTube Background Video', 'livemesh-so-widgets'),
                            'state_handler' => array(
                                'bg_type[youtube]' => array('show'),
                                '_else[bg_type]' => array('hide'),
                            ),
                            'fields' => array(

                                'youtube_url' => array(
                                    'type' => 'text',
                                    'sanitize' => 'url',
                                    'label' => __('YouTube URL', 'livemesh-so-widgets'),
                                    'description' => __('An URL of the YouTube video that will act as background video for this section.', 'livemesh-so-widgets'),
                                ),

                                'quality' => array(
                                    'type' => 'select',
                                    'label' => __('Choose the YouTube video quality', 'livemesh-so-widgets'),
                                    'default' => 'highres',
                                    'options' => array(
                                        'highres' => __('High Resolution', 'livemesh-so-widgets'),
                                        'default' => __('Default', 'livemesh-so-widgets'),
                                        'small' => __('Small', 'livemesh-so-widgets'),
                                        'medium' => __('Medium', 'livemesh-so-widgets'),
                                        'large' => __('Large', 'livemesh-so-widgets'),
                                        'hd720' => __('HD 720p', 'livemesh-so-widgets'),
                                        'hd1080' => __('HD 1080p', 'livemesh-so-widgets'),
                                    )
                                ),

                                'ratio' => array(
                                    'type' => 'select',
                                    'label' => __('Aspect ratio of the YouTube video', 'livemesh-so-widgets'),
                                    'default' => '16/9',
                                    'options' => array(
                                        '16/9' => __('16/9', 'livemesh-so-widgets'),
                                        'auto' => __('Auto', 'livemesh-so-widgets'),
                                        '4/3' => __('4/3', 'livemesh-so-widgets'),
                                    )
                                ),

                            ),
                        ),

                        'html5_videos' => array(
                            'type' => 'section',
                            'label' => __('HTML5 Background videos', 'livemesh-so-widgets'),
                            'state_handler' => array(
                                'bg_type[html5video]' => array('show'),
                                '_else[bg_type]' => array('hide'),
                            ),
                            'fields' => array(

                                'mp4_file' => array(
                                    'type' => 'media',
                                    'library' => 'video',
                                    'label' => __('MP4 Video file', 'livemesh-so-widgets'),
                                ),
                                'webm_file' => array(
                                    'type' => 'media',
                                    'library' => 'video',
                                    'label' => __('WebM Video file', 'livemesh-so-widgets'),
                                ),
                                'ogg_file' => array(
                                    'type' => 'media',
                                    'library' => 'video',
                                    'label' => __('Ogg Video file', 'livemesh-so-widgets'),
                                ),

                            ),
                        ),

                        'bg_image' => array(
                            'type' => 'section',
                            'label' => __('Background Image', 'livemesh-so-widgets'),
                            'fields' => array(

                                'image' => array(
                                    'type' => 'media',
                                    'label' => __('Background Image', 'livemesh-so-widgets'),
                                    'description' => __('This background image will be used as a placeholder image if YouTube or HTML5 video background option is chosen.', 'livemesh-so-widgets'),
                                    'library' => 'image',
                                    'fallback' => true,
                                ),
                            ),
                        ),

                        'overlay' => array(
                            'type' => 'section',
                            'label' => __('Background Overlay', 'livemesh-so-widgets'),
                            'fields' => array(

                                'overlay_color' => array(
                                    'type' => 'color',
                                    'label' => __('Overlay color', 'livemesh-so-widgets'),
                                    'default' => '#333333',
                                ),

                                'overlay_opacity' => array(
                                    'label' => __('Overlay opacity', 'livemesh-so-widgets'),
                                    'type' => 'slider',
                                    'min' => 0,
                                    'max' => 100,
                                    'default' => 30,
                                ),

                            ),
                        ),
                    )
                ),

                'settings' => array(
                    'type' => 'section',
                    'label' => __('Settings', 'livemesh-so-widgets'),
                    'fields' => array(

                        'top_padding' => array(
                            'type' => 'number',
                            'label' => __('Top padding', 'livemesh-so-widgets'),
                            'default' => 100,
                        ),

                        'bottom_padding' => array(
                            'type' => 'number',
                            'label' => __('Bottom padding', 'livemesh-so-widgets'),
                            'default' => 100,
                        ),


                        'responsive' => array(
                            'type' => 'section',
                            'label' => __('Responsive', 'livemesh-so-widgets'),
                            'hide' => true,
                            'fields' => array(
                                'tablet' => array(
                                    'type' => 'section',
                                    'label' => __('Tablet', 'livemesh-so-widgets'),
                                    'fields' => array(
                                        'top_padding' => array(
                                            'type' => 'number',
                                            'label' => __('Top padding', 'livemesh-so-widgets'),
                                            'default' => 80,
                                        ),

                                        'bottom_padding' => array(
                                            'type' => 'number',
                                            'label' => __('Bottom padding', 'livemesh-so-widgets'),
                                            'default' => 80,
                                        ),

                                        'width' => array(
                                            'type' => 'text',
                                            'label' => __('Resolution', 'livemesh-so-widgets'),
                                            'description' => __('The resolution to treat as a tablet resolution.', 'livemesh-so-widgets'),
                                            'default' => 800,
                                            'sanitize' => 'intval',
                                        )
                                    )
                                ),
                                'mobile' => array(
                                    'type' => 'section',
                                    'label' => __('Mobile Phone', 'livemesh-so-widgets'),
                                    'fields' => array(
                                        'top_padding' => array(
                                            'type' => 'number',
                                            'label' => __('Top padding', 'livemesh-so-widgets'),
                                            'default' => 50,
                                        ),

                                        'bottom_padding' => array(
                                            'type' => 'number',
                                            'label' => __('Bottom padding', 'livemesh-so-widgets'),
                                            'default' => 50,
                                        ),

                                        'width' => array(
                                            'type' => 'text',
                                            'label' => __('Resolution', 'livemesh-so-widgets'),
                                            'description' => __('The resolution to treat as a mobile resolution.', 'livemesh-so-widgets'),
                                            'default' => 400,
                                            'sanitize' => 'intval',
                                        )
                                    )
                                )

                            )
                        ),

                        'customize' => array(
                            'type' => 'notify',
                            'custom_label' => __('Customize this widget with the intuitive yet powerful visual styles editor - ', 'livemesh-so-widgets')  . '<a target="_blank" href="https://wordpress.org/plugins/so-css/" title="SiteOrigin CSS">' . __('SiteOrigin CSS.', 'livemesh-so-widgets') . '</a>',
                        ),
                    )
                ),


            )
        );
    }

    function initialize() {

        $this->register_frontend_scripts(
            array(
                array(
                    'lsow-ytp',
                    LSOW_PLUGIN_URL . 'assets/js/jquery.youtube-background' . LSOW_JS_SUFFIX . '.js',
                    array('jquery'),
                    LSOW_VERSION
                ),
            )
        );

        $this->register_frontend_styles(array(
            array(
                'lsow-frontend',
                LSOW_PLUGIN_URL . 'assets/css/lsow-frontend.css',
                array(),
                LSOW_VERSION
            ),
            array(
                'lsow-hero-image',
                plugin_dir_url(__FILE__) . 'css/style.css'
            )
        ));


        add_action('wp_enqueue_scripts', array($this, 'init_custom_css'), 15); // load as late as possible

    }

    function init_custom_css() {

        if (!is_active_widget(false, false, $this->id_base)) {
            return;
        }

        $custom_css = '';

        $instances = $this->get_settings();

        if (array_key_exists($this->number, $instances)) {
            $instance = $instances[$this->number];
            if (!empty($instance)) {
                $header_type = $instance['header_type'];
                if ($header_type == 'custom')
                    $custom_css = $instance['custom_header']['custom_css'];
            }
        }

        if ($custom_css <> '') {
            $custom_css = $custom_css . "\n";
            wp_add_inline_style('lsow-hero-image', $custom_css); // after custom.css file
        }

    }


    function get_less_variables($instance) {

        $font = siteorigin_widget_get_font( $instance['standard_header']['heading_font'] );
        $less['heading_font'] = $font['family'];
        if ( ! empty( $font['weight'] ) ) {
            $less['heading_font_weight'] = $font['weight'];
        }

        $less['top_padding'] = intval($instance['settings']['top_padding']) . 'px';
        $less['bottom_padding'] = intval($instance['settings']['bottom_padding']) . 'px';

        $less['tablet_width'] = intval($instance['settings']['responsive']['tablet']['width']) . 'px';
        $less['mobile_width'] = intval($instance['settings']['responsive']['mobile']['width']) . 'px';

        $less['tablet_top_padding'] = intval($instance['settings']['responsive']['tablet']['top_padding']) . 'px';
        $less['tablet_bottom_padding'] = intval($instance['settings']['responsive']['tablet']['bottom_padding']) . 'px';

        $less['mobile_top_padding'] = intval($instance['settings']['responsive']['mobile']['top_padding']) . 'px';
        $less['mobile_bottom_padding'] = intval($instance['settings']['responsive']['mobile']['bottom_padding']) . 'px';

        return $less;
    }

    /**
     * Less function for importing Google web fonts.
     */
    function less_import_google_font($instance, $args) {
        if( empty( $instance ) ) return;

        $font_import = siteorigin_widget_get_font( $instance['standard_header']['heading_font'] );
        if( !empty( $font_import['css_import'] ) ) {
            return  $font_import['css_import'];
        }
    }

    function get_template_variables($instance, $args) {

        $settings = $instance['settings'];

        $settings = array_merge($settings, array(
            'header_type' => $instance['header_type'],
            'custom_header' => $instance['custom_header'],
            'standard_header' => $instance['standard_header'],
            'pointer_down_url' => $instance['pointer_down_url'],
            'background' => $instance['background'],
        ));

        return array('settings' => $settings);
    }

}

siteorigin_widget_register('lsow-hero-image', __FILE__, 'LSOW_Hero_Image_Widget');