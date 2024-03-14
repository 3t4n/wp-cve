<?php

defined('ABSPATH') or die();

/**
 * Weblizar companion helper class
 */
class wl_companion_helper
{
    
    /* Get theme name and version */
    public static function wl_get_theme_name()
    {
        if (get_bloginfo('version') < '3.4') {
            $theme_data = wp_get_theme(get_stylesheet_directory() . '/style.css');
            $theme_name      = $theme_data['Name'];
        } else {
            $theme_name      = wp_get_theme();
        }
        return $theme_name;
    }

    public static function wl_get_theme_version($name, $version)
    {
        $my_theme      = wp_get_theme();
        $theme_name    = $my_theme->get('Name');
        $theme_version = $my_theme->get('Version');

        if ($name == $theme_name && $theme_version > $version) {
            return true;
        } else {
            return false;
        }
    }

    public static function wl_check_for_options_data()
    {
        $theme_name = self::wl_get_theme_name();
        $class      = '';

        if ($theme_name == 'Enigma Parallax' || $theme_name == 'Enigma' || $theme_name == 'Weblizar' || $theme_name == 'Creative' || $theme_name == 'Explora' || $theme_name == 'Guardian' || $theme_name == 'HealthCare' || $theme_name == 'scoreline' || $theme_name == 'Chronicle' || $theme_name == 'Green-Lantern') {
            if (self::wl_get_theme_version('Enigma Parallax', '4.0.1') || self::wl_get_theme_version('Enigma', '6.0.1')) {
                $enigma_parallax = get_option('enigma_options');
                if (empty($enigma_parallax)) {
                    $class = 'disabled';
                }
            } elseif (self::wl_get_theme_version('Weblizar', '4.7.6')) {
                $weblizar_options = get_option('weblizar_options');
                if (empty($weblizar_options)) {
                    $class = 'disabled';
                }
            } elseif (self::wl_get_theme_version('Creative', '2.4.4')) {
                $weblizar_options = get_option('creative_general_options');
                if (empty($weblizar_options)) {
                    $class = 'disabled';
                }
            } elseif (self::wl_get_theme_version('Explora', '1.6.1')) {
                $weblizar_options = get_option('explora_options');
                if (empty($weblizar_options)) {
                    $class = 'disabled';
                }
            } elseif (self::wl_get_theme_version('Guardian', '4.3.7')) {
                $weblizar_options = get_option('guardian_options');
                if (empty($weblizar_options)) {
                    $class = 'disabled';
                }
            } elseif (self::wl_get_theme_version('HealthCare', '2.2.6')) {
                $weblizar_options = get_option('health_options');
                if (empty($weblizar_options)) {
                    $class = 'disabled';
                }
            } elseif (self::wl_get_theme_version('scoreline', '1.7.0')) {
                $weblizar_options = get_option('scoreline_options');
                if (empty($weblizar_options)) {
                    $class = 'disabled';
                }
            } elseif (self::wl_get_theme_version('Chronicle', '2.7.2')) {
                $weblizar_options = get_option('chronicle_theme_options');
                if (empty($weblizar_options)) {
                    $class = 'disabled';
                }
            } elseif (self::wl_get_theme_version('Green-Lantern', '3.3.33')) {
                $weblizar_options = get_option('green-lantern_options_gl');
                if (empty($weblizar_options)) {
                    $class = 'disabled';
                }
            } else {
                $my_theme      = wp_get_theme();
                $template_name = $my_theme->get('Template');
                $template_arr  = array( 'enigma', 'enigma-parallax', 'creative', 'green-lantern', 'guardian', 'chronicle', 'weblizar', 'scoreline', 'green-lantern' );

                if (in_array($template_name, $template_arr)) {
                    $theme_arr = array(
                        'enigma-parallax' => 'enigma-parallax',
                        'enigma'          => 'Enigma',
                        'weblizar'        => 'Weblizar',
                        'creative'        => 'Creative',
                        'guardian'        => 'Guardian',
                        'scoreline'       => 'scoreline',
                        'chronicle'       => 'Chronicle',
                        'green-Lantern'   => 'Green-lantern'
                    );

                    $version_arr = array(
                        'enigma-parallax' => '4.0.1',
                        'enigma'          => '6.0.1',
                        'weblizar'        => '4.7.6',
                        'creative'        => '2.4.4',
                        'guardian'        => '1.6.1',
                        'scoreline'       => '1.7.0',
                        'chronicle'       => '2.7.2',
                        'green-Lantern'   => '3.3.33'
                    );

                    $option_arr = array(
                        'enigma-parallax' => 'enigma_options',
                        'enigma'          => 'enigma_options',
                        'weblizar'        => 'weblizar_options',
                        'creative'        => 'creative_general_options',
                        'guardian'        => 'guardian_options',
                        'scoreline'       => 'scoreline_options',
                        'chronicle'       => 'chronicle_theme_options',
                        'green-Lantern'   => 'green-lantern_options_gl'
                    );

                    foreach ($theme_arr as $key => $value) {
                        if ($template_name == $key && self::wl_get_theme_version($theme_arr[$key], $version_arr[$key])) {
                            $weblizar_options = get_option($option_arr[$key]);
                            if (empty($weblizar_options)) {
                                $class = 'disabled';
                            }
                        }
                    }
                }
            }
        }

        return $class;
    }

    public static function wl_get_option_name()
    {
        $theme_name = self::wl_get_theme_name();

        if ($theme_name == 'Enigma Parallax' || $theme_name == 'Enigma') {
            $options_data = get_option('enigma_options');
        } elseif ($theme_name == 'Weblizar') {
            $options_data = get_option('weblizar_options');
        } elseif ($theme_name == 'Creative') {
            $options_data = get_option('creative_general_options');
        } elseif ($theme_name == 'Explora') {
            $options_data = get_option('explora_options');
        } elseif ($theme_name == 'Guardian') {
            $options_data = get_option('guardian_options');
        } elseif ($theme_name == 'HealthCare') {
            $options_data = get_option('health_options');
        } elseif ($theme_name == 'scoreline') {
            $options_data = get_option('scoreline_options');
        } elseif ($theme_name == 'Chronicle') {
            $options_data = get_option('chronicle_theme_options');
        } else {
            $my_theme      = wp_get_theme();
            $template_name = $my_theme->get('Template');
            $template_arr  = array( 'enigma', 'enigma-parallax', 'creative', 'green-lantern', 'guardian', 'chronicle', 'weblizar', 'scoreline', 'green-lantern' );

            if (in_array($template_name, $template_arr)) {
                $theme_arr = array(
                    'enigma-parallax' => 'Enigma Parallax',
                    'enigma'          => 'Enigma',
                    'weblizar'        => 'Weblizar',
                    'creative'        => 'Creative',
                    'guardian'        => 'Guardian',
                    'scoreline'       => 'scoreline',
                    'chronicle'       => 'Chronicle',
                    'green-Lantern'   => 'Green-lantern'
                );

                $option_arr = array(
                    'enigma-parallax' => 'enigma_options',
                    'enigma'          => 'enigma_options',
                    'weblizar'        => 'weblizar_options',
                    'creative'        => 'creative_general_options',
                    'guardian'        => 'guardian_options',
                    'scoreline'       => 'scoreline_options',
                    'chronicle'       => 'chronicle_theme_options',
                    'green-Lantern'   => 'green-lantern_options_gl'
                );

                foreach ($theme_arr as $key => $value) {
                    if ($template_name == $key) {
                         esc_attr_e($key,WL_COMPANION_DOMAIN);
                        $options_data = get_option($option_arr[$key]);
                    }
                }
            }
        }
        if (empty($options_data)) {
            $options_data = array();
        }
        
        return $options_data;
    }

    public static function wl_get_export_data()
    {
        $theme_name      = self::wl_get_theme_name();
        $free_theme_data = get_option('free_theme_data');

        $service_titles = array();
        $service_texts  = array();
        $service_icons  = array();
        $service_links  = array();
        $port_title     = array();
        $port_link      = array();
        $port_img       = array();
        $slider_title   = array();
        $slide_desc     = array();
        $slide_btn_text = array();
        $slide_btn_link = array();
        $slide_image    = array();

        for ($i=1; $i<5; $i++) {
            array_push($service_titles, get_theme_mod('service_'.$i.'_title'));
            array_push($service_texts, get_theme_mod('service_'.$i.'_text'));
            array_push($service_icons, get_theme_mod('service_'.$i.'_icons'));
            array_push($service_links, get_theme_mod('service_'.$i.'_link'));
            array_push($port_title, get_theme_mod('port_'.$i.'_title'));
            array_push($port_link, get_theme_mod('port_'.$i.'_link'));
            array_push($port_img, get_theme_mod('port_'.$i.'_img'));

            if (null !== (get_theme_mod('slide_title_'.$i)) && ! empty(get_theme_mod('slide_title_'.$i))) {
                array_push($slider_title, get_theme_mod('slide_title_'.$i));
            }
            if (null !== (get_theme_mod('slide_desc_'.$i)) && ! empty(get_theme_mod('slide_desc_'.$i))) {
                array_push($slide_desc, get_theme_mod('slide_desc_'.$i));
            }
            if (null !== (get_theme_mod('slide_btn_text_'.$i)) && ! empty(get_theme_mod('slide_btn_text_'.$i))) {
                array_push($slide_btn_text, get_theme_mod('slide_btn_text_'.$i));
            }
            if (null !== (get_theme_mod('slide_btn_link_'.$i)) && ! empty(get_theme_mod('slide_btn_link_'.$i))) {
                array_push($slide_btn_link, get_theme_mod('slide_btn_link_'.$i));
            }
            if (null !== (get_theme_mod('slide_image_'.$i)) && ! empty(get_theme_mod('slide_image_'.$i))) {
                array_push($slide_image, get_theme_mod('slide_image_'.$i));
            }
        }

        $data = array(
            'service_titles'         => $service_titles,
            'service_texts'          => $service_texts,
            'service_icons'          => $service_icons,
            'service_links'          => $service_links,
            'port_title'             => $port_title,
            'port_link'              => $port_link,
            'port_img'               => $port_img,
            'slider_title'           => $slider_title,
            'slide_desc'             => $slide_desc,
            'slide_btn_link'         => $slide_btn_link,
            'slide_btn_text'         => $slide_btn_text,
            'slide_image'            => $slide_image,
            'blog_title'             => get_theme_mod('blog_title'),
            'blog_speed'             => get_theme_mod('blog_speed'),
            'footer_social'          => get_theme_mod('footer_section_social_media_enbled'),
            'header_social'          => get_theme_mod('header_social_media_in_enabled'),
            'footer_customizations'  => get_theme_mod('footer_customizations'),
            'developed_by_text'      => get_theme_mod('developed_by_text'),
            'developed_by_link_txt'  => get_theme_mod('developed_by_weblizar_text'),
            'developed_by_link'      => get_theme_mod('developed_by_link'),
            'email_id'               => get_theme_mod('email_id'),
            'phone_no'               => get_theme_mod('phone_no'),
            'twitter_link'           => get_theme_mod('twitter_link'),
            'fb_link'                => get_theme_mod('fb_link'),
            'linkedin_link'          => get_theme_mod('linkedin_link'),
            'youtube_link'           => get_theme_mod('youtube_link'),
            'instagram'              => get_theme_mod('instagram'),
            'vk_link'                => get_theme_mod('vk_link'),
            'qq_link'                => get_theme_mod('qq_link'),
            'whatsapp_link'          => get_theme_mod('whatsapp_link'),
            'side_interval'          => get_theme_mod('slider_image_speed'),
            'call_out_text'          => get_theme_mod('fc_title'),
            'call_out_btext'         => get_theme_mod('fc_btn_txt'),
            'call_out_link'          => get_theme_mod('fc_btn_link'),
            'call_out_icon'          => get_theme_mod('fc_icon'),
        );

        if (update_option('free_theme_data', $data)) {
            return base64_encode(serialize($data));
        } else {
            return base64_encode(serialize(' '));
        }
    }

    public static function wl_get_import_data($data)
    {
        $free_theme_data  = unserialize(base64_decode($data));
        $wl_theme_options = get_option('enigma_options_pro');

        for ($i=0; $i < 4; $i++) {
            if (! empty($free_theme_data["service_titles"][$i])) {
                $service_title = wp_strip_all_tags($free_theme_data["service_titles"][$i]);
            } else {
                $service_title = '';
            }

            // Create Services
            $services = array(
                'post_title'   => $service_title,
                'post_content' => $free_theme_data["service_texts"][$i],
                'post_type'    => 'weblizar_service',
                'post_status'  => 'publish',
                'post_author'  => get_current_user_id(),
                'meta_input'   => array(
                    'service_font_awesome_icons' => 'fa '.$free_theme_data["service_icons"][$i],
                    'service_button_target'      => $free_theme_data["service_links"][$i],
                ),
            );
                
            // // Insert the post into the database
            wp_insert_post($services);
            
            if (! empty($free_theme_data["port_title"][$i])) {
                $port_title = wp_strip_all_tags($free_theme_data["port_title"][$i]);
            } else {
                $port_title = '';
            }

            // Create Portfolio
            $portfolios = array(
                'post_title'   => $port_title,
                'post_content' => '',
                'post_type'    => 'weblizar_portfolio',
                'post_status'  => 'publish',
                'post_author'  => get_current_user_id(),
                'meta_input'   => array(
                    'portfolio_button_link' => $free_theme_data["port_link"][$i],
                ),
            );
                
            // Insert the post into the database
            $portfolio_ID = wp_insert_post($portfolios);

            // Add Featured Image to Portfolio
            $image_url        = $free_theme_data["port_img"][$i]; // Define the image URL here
            $image_name       = 'portfolio-' . $i . '.png';
            $upload_dir       = wp_upload_dir(); // Set upload folder
            $image_data       = file_get_contents($image_url); // Get image data
            $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
            $filename         = basename($unique_file_name); // Create image file name

            // Check folder permission and define file location
            if (wp_mkdir_p($upload_dir['path'])) {
                $file = $upload_dir['path'] . '/' . $filename;
            } else {
                $file = $upload_dir['basedir'] . '/' . $filename;
            }

            // Create the image  file on the server
            file_put_contents($file, $image_data);

            // Check image file type
            $wp_filetype = wp_check_filetype($filename, null);

            // Set attachment data
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title'     => sanitize_file_name($filename),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            // Create the attachment
            $attach_id = wp_insert_attachment($attachment, $file, $portfolio_ID);

            // Include image.php
            require_once(ABSPATH . 'wp-admin/includes/image.php');

            // Define attachment metadata
            $attach_data = wp_generate_attachment_metadata($attach_id, $file);

            // Assign metadata to attachment
            wp_update_attachment_metadata($attach_id, $attach_data);

            // And finally assign featured image to post
            set_post_thumbnail($portfolio_ID, $attach_id);


            //********* Create Slider *************//
            if (! empty($free_theme_data["slider_title"][$i])) {
                $slider_title = wp_strip_all_tags($free_theme_data["slider_title"][$i]);
            } else {
                $slider_title = '';
            }

            if (! empty($free_theme_data["slide_desc"][$i])) {
                $slide_desc = wp_strip_all_tags($free_theme_data["slide_desc"][$i]);
            } else {
                $slide_desc = '';
            }
                
            $sliders = array(
                'post_title'   => $slider_title,
                'post_content' => $slide_desc,
                'post_type'    => 'weblizar_slider',
                'post_status'  => 'publish',
                'post_author'  => get_current_user_id(),
                'meta_input'   => array(
                    'slider_button_link' => $free_theme_data["slide_btn_link"][$i],
                    'slider_button_text' => $free_theme_data["slide_btn_text"][$i],
                ),
            );
                
            // Insert the post into the database
            $slider_ID = wp_insert_post($sliders);

            // Add Featured Image to Portfolio
            $image_url        = $free_theme_data["slide_image"][$i]; // Define the image URL here
            $image_name       = 'slider-' . $i . '.png';
            $upload_dir       = wp_upload_dir(); // Set upload folder
            $image_data       = file_get_contents($image_url); // Get image data
            $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
            $filename         = basename($unique_file_name); // Create image file name

            // Check folder permission and define file location
            if (wp_mkdir_p($upload_dir['path'])) {
                $file = $upload_dir['path'] . '/' . $filename;
            } else {
                $file = $upload_dir['basedir'] . '/' . $filename;
            }

            // Create the image  file on the server
            file_put_contents($file, $image_data);

            // Check image file type
            $wp_filetype = wp_check_filetype($filename, null);

            // Set attachment data
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title'     => sanitize_file_name($filename),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            // Create the attachment
            $attach_id = wp_insert_attachment($attachment, $file, $slider_ID);

            // Include image.php
            require_once(ABSPATH . 'wp-admin/includes/image.php');

            // Define attachment metadata
            $attach_data = wp_generate_attachment_metadata($attach_id, $file);

            // Assign metadata to attachment
            wp_update_attachment_metadata($attach_id, $attach_data);

            // And finally assign featured image to post
            set_post_thumbnail($slider_ID, $attach_id);
        }

        $wl_theme_options['home_blog_heading']                  = $free_theme_data['blog_title'];
        $wl_theme_options['blog_slide_duration']                = (int) $free_theme_data['blog_speed'];
        $wl_theme_options['footer_section_social_media_enbled'] = $free_theme_data['footer_social'];
        $wl_theme_options['header_section_social_media_enbled'] = $free_theme_data['header_social'];
        $wl_theme_options['footer_customizations']              = $free_theme_data['footer_customizations'];
        $wl_theme_options['developed_by_weblizar_text']         = $free_theme_data['developed_by_text'];
        $wl_theme_options['developed_by_link']                  = $free_theme_data['developed_by_link'];
        $wl_theme_options['contact_email']                      = $free_theme_data['email_id'];
        $wl_theme_options['contact_phone_no']                   = $free_theme_data['phone_no'];
        $wl_theme_options['twitter_link']                       = $free_theme_data['twitter_link'];
        $wl_theme_options['facebook_link']                      = $free_theme_data['fb_link'];
        $wl_theme_options['linkedin_link']                      = $free_theme_data['linkedin_link'];
        $wl_theme_options['youtube_link']                       = $free_theme_data['youtube_link'];
        $wl_theme_options['side_interval']                      = (int) $free_theme_data['side_interval'];
        $wl_theme_options['call_out_text']                      = $free_theme_data['call_out_text'];
        $wl_theme_options['call_out_btext']                     = $free_theme_data['call_out_btext'];
        $wl_theme_options['call_out_link']                      = $free_theme_data['call_out_link'];
        $wl_theme_options['call_out_icon']                      = $free_theme_data['call_out_icon'];

        if (update_option('enigma_options_pro', $wl_theme_options)) {
            return true;
        } else {
            return false;
        }
    }
    
    public static function wl_add_import_menu()
    {
        $theme_arr = array(
            'Enigma Parallax' => '4.0.1',
            'Enigma'          => '6.0.1',
            'Weblizar'        => '4.7.6',
            'Creative'        => '2.4.4',
            'Explora'         => '1.6.1',
            'Guardian'        => '4.3.7',
            'HealthCare'      => '2.2.6',
            'scoreline'       => '1.7.0',
            'Chronicle'       => '2.7.2',
        );

        foreach ($theme_arr as $key => $value) {
            if (self::wl_get_theme_version($key, $value)) {
                /* Action for creating menu pages */
                add_action('admin_menu', array( 'WL_WC_ImportExportMenu', 'create_menu' ));
            }
        }
        add_option('free_theme_data');
    }

    public static function wl_add_import_menu_child()
    {
        $my_theme   = wp_get_theme();
        $theme_name = $my_theme->get('Template');
        $theme_arr  = array( 'enigma', 'enigma-parallax', 'creative', 'green-lantern', 'guardian', 'chronicle', 'weblizar', 'green-lantern' );

        if (in_array($theme_name, $theme_arr)) {
            add_action('admin_menu', array( 'WL_WC_ImportExportMenu', 'create_menu' ));
        }
        add_option('free_theme_data');
    }
}
