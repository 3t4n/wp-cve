<?php
if(!class_exists("gclpr_prelaod_requests_functions")) {
    $gclpr_options = get_option('gclpr_options');
    class gclpr_prelaod_requests_functions
    {
        public function __construct() {
            add_action( 'admin_print_styles', array($this,'enqueue_styles_preload_requests'));
            add_action( 'wp_head', array($this,'preload_links_callback'),-1000);
        }

        public function enqueue_styles_preload_requests() {
            if( is_admin() ) {
                $css= GCLPR_PLUGIN_URL . "/assets/css/style.css";
                wp_enqueue_style( 'gclpr-admin', $css, array(), GCLPR_BUILD );
            }
        }

        /* enqueue link to head */
        public function preload_links_callback() {
            $fonts_urls = $images_urls = $styles_urls = $scripts_urls = $videos_urls = [];
            global $gclpr_options;
            $gclpr_font_urls = (isset($gclpr_options) && !empty($gclpr_options['gclpr_font_options']['font_urls_list'])) ? str_replace('{{site_url}}',home_url(),$gclpr_options['gclpr_font_options']['font_urls_list']) : '';                
            if(!empty($gclpr_font_urls)) {
                $gclpr_font_urls   =   explode("\n", $gclpr_font_urls);           // Break a string into an array: explode(separator,string,limit)
            }
            $fonts_urls = (isset($gclpr_font_urls) && !empty($gclpr_font_urls)) ? array_unique($gclpr_font_urls) : '';
            if(isset($fonts_urls) && !empty($fonts_urls)) {
                foreach($fonts_urls as $font_urls) {
                    $file_info = pathinfo($font_urls);
                    $file_ext = (isset($file_info['extension']) && !empty($file_info['extension'])) ? $file_info['extension'] : '';
                    if(!empty($font_urls) && !empty($file_ext)) {
                    ?><link rel="preload" href="<?php echo esc_url($font_urls); ?>" as="font" type="font/<?php esc_attr_e($file_ext); ?>" crossorigin /><?php printf("\n"); ?><?php
                    }
                }
            }

            $gclpr_style_urls   =   (isset($gclpr_options) && !empty($gclpr_options['gclpr_style_options']['style_urls_list'])) ? str_replace('{{site_url}}',home_url(),$gclpr_options['gclpr_style_options']['style_urls_list']) : '';

            if(!empty($gclpr_style_urls)) {
                $gclpr_style_urls   =   explode("\n", $gclpr_style_urls);          // Break a string into an array: explode(separator,string,limit)
            }
            $styles_urls = (isset($gclpr_style_urls) && !empty($gclpr_style_urls)) ? array_unique($gclpr_style_urls) : '';
            if(isset($styles_urls) && !empty($styles_urls)) {
                foreach($styles_urls as $style_urls) {
                    if(!empty($style_urls)) {
                    ?><link rel="preload" href="<?php echo esc_url($style_urls); ?>" as="style" crossorigin /><?php printf("\n"); ?><?php
                    }
                }
            }

            $gclpr_script_urls   =   (isset($gclpr_options) && !empty($gclpr_options['gclpr_script_options']['script_urls_list'])) ? str_replace('{{site_url}}',home_url(),$gclpr_options['gclpr_script_options']['script_urls_list']) : '';

            if(!empty($gclpr_script_urls)) {
                $gclpr_script_urls   =   explode("\n", $gclpr_script_urls);          // Break a string into an array: explode(separator,string,limit)
            }
            $scripts_urls = (isset($gclpr_script_urls) && !empty($gclpr_script_urls)) ? array_unique($gclpr_script_urls) : '';
            if(isset($scripts_urls) && !empty($scripts_urls)) {
                foreach($scripts_urls as $script_urls) {
                    if(!empty($script_urls)) {
                    ?><link rel="preload" href="<?php echo esc_url($script_urls); ?>" as="script" crossorigin /><?php printf("\n"); ?><?php
                    }
                }
            }

            $gclpr_image_urls   =   (isset($gclpr_options) && !empty($gclpr_options['gclpr_image_options']['image_urls_list'])) ? str_replace('{{site_url}}',home_url(),$gclpr_options['gclpr_image_options']['image_urls_list']) : '';
            if(!empty($gclpr_image_urls)) {
                $gclpr_image_urls   =   explode("\n", $gclpr_image_urls);          // Break a string into an array: explode(separator,string,limit)
            }
            $images_urls = (isset($gclpr_image_urls) && !empty($gclpr_image_urls)) ? array_unique($gclpr_image_urls) : '';
            if(isset($images_urls) && !empty($images_urls)) {
                foreach($images_urls as $image_urls) {
                    $file_info = pathinfo($image_urls);
                    $file_ext = (isset($file_info['extension']) && !empty($file_info['extension'])) ? $file_info['extension'] : '';
                    if(!empty($image_urls) && !empty($file_ext)) {
                    ?><link rel="preload" href="<?php echo esc_url($image_urls); ?>" as="image" type="image/<?php esc_attr_e($file_ext); ?>" crossorigin /><?php printf("\n"); ?><?php
                    }
                }
            }

            $gclpr_video_urls   =   (isset($gclpr_options) && !empty($gclpr_options['gclpr_video_options']['video_urls_list'])) ? str_replace('{{site_url}}',home_url(),$gclpr_options['gclpr_video_options']['video_urls_list']) : '';
            if(!empty($gclpr_video_urls)) {
                $gclpr_video_urls   =   explode("\n", $gclpr_video_urls);          // Break a string into an array: explode(separator,string,limit)
            }
            $image_post_urls = (isset($preload_post_meta['gclpr_image_urls'])) ? $preload_post_meta['gclpr_image_urls'] : '';
            $videos_urls = (isset($gclpr_video_urls) && !empty($gclpr_video_urls)) ? array_unique($gclpr_video_urls) : '';
            if(isset($videos_urls) && !empty($videos_urls)) {
                foreach($videos_urls as $video_urls) {
                    $file_info = pathinfo($video_urls);
                    $file_ext = (isset($file_info['extension']) && !empty($file_info['extension'])) ? $file_info['extension'] : '';
                    if(!empty($video_urls) && !empty($file_ext)) {
                    ?><link rel="preload" href="<?php echo esc_url($video_urls); ?>" as="video" type="video/<?php esc_attr_e($file_ext); ?>" crossorigin /><?php printf("\n"); ?><?php
                    }
                }
            }
        }
    }
    new gclpr_prelaod_requests_functions();
}