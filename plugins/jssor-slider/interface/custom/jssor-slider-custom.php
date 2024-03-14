<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/*
 * jssor slider wordpress plugin - custom extensions
 */

function wp_jssor_template_redirect()
{
    $jssor_extension =  empty($_GET['jssor_extension']) ? '' : sanitize_text_field($_GET['jssor_extension']);

    if(!empty($jssor_extension)){
        //jssor_extension=media_browser
        if ($jssor_extension == "media_browser"){
            remove_action( 'admin_init', 'send_frame_options_header',10);

            wp_enqueue_style('theme-style', get_stylesheet_uri());
            wp_enqueue_script('wp-jssor-media-browser-init-script', WP_JSSOR_SLIDER_URL.'interface/custom/media/js/wp.jssor.media.browser.init-1.0.1.min.js');

            include(WP_JSSOR_SLIDER_PATH . 'interface/custom/media/media-browser.php');

            die();
        }
        //jssor_extension=import_slider_with_progress
        else if($jssor_extension == "import_slider_with_progress")
        {
            include(WP_JSSOR_SLIDER_PATH . 'interface/custom/import/import-slider-with-progress.php');

            die();
        }
        //jssor_extension=preview_slider
        else if($jssor_extension == "preview_slider")
        {
            Jssor_Slider_Dispatcher::load_module_output();
            include(WP_JSSOR_SLIDER_PATH . 'interface/custom/preview/preview-slider.php');

            die();
        }
        //jssor_extension=download_build_zip
        else if($jssor_extension == "download_build_zip")
        {
            include(WP_JSSOR_SLIDER_PATH . 'interface/custom/build/download-build-zip.php');

            die();
        }
    }
}

//add_action('template_redirect', 'wp_jssor_template_redirect');

?>
