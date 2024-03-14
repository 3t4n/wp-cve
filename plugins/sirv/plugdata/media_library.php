<?php

    defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

    function check_empty_options(){
        $account_name = getValue::getOption('SIRV_ACCOUNT_NAME');
        $cdn_url = getValue::getOption('SIRV_CDN_URL');

        //die($key);

        if( empty($account_name) || empty($cdn_url) ){
            return false;
        }else return true;

    }


    if(check_empty_options()){
        wp_enqueue_style('fontAwesome', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css", array());
        //wp_enqueue_style('sirv_style', plugins_url('css/wp-sirv.css', __FILE__));
        wp_register_style('sirv_toast_style', plugins_url('css/vendor/toastr.css', __FILE__));
        wp_enqueue_style('sirv_toast_style');
        wp_enqueue_script('sirv_toast_js', plugins_url('js/vendor/toastr.min.js', __FILE__), array('jquery'), false);

        wp_register_style('sirv_style', plugins_url('css/wp-sirv.css', __FILE__));
        wp_enqueue_style('sirv_style');

        wp_enqueue_script( 'sirv_logic', plugins_url('js/wp-sirv.js', __FILE__), array( 'jquery', 'jquery-ui-sortable', 'sirv_toast_js' ), false);
        wp_localize_script( 'sirv_logic', 'sirv_ajax_object', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'ajaxnonce' => wp_create_nonce('sirv_logic_ajax_validation_nonce'),
            'assets_path' => plugins_url('assets', __FILE__),
        ));

        wp_enqueue_script( 'sirv_media_library_logic', plugins_url('js/wp-sirv-media-library.js', __FILE__), array( 'jquery'), false);
        wp_enqueue_script( 'sirv_logic-md5', plugins_url('js/vendor/wp-sirv-md5.min.js', __FILE__), array(), '1.0.0');


    include('templates/media_library.html');

    }else{
        wp_enqueue_style('sirv_style', plugins_url('css/wp-sirv.css', __FILE__));
        include('templates/login_error.html');
    }
?>
