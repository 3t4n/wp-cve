<?php

if(!function_exists('cg_options_tabcontent_v10')){

    function cg_options_tabcontent_v10() {
        /* Register our stylesheet. */

        if(!empty($_GET['page'])){
            $check = $_GET['page'];
        }
        else{
            $check = '';
        }

        if ($check!='contest-gallery/index.php' && $check!='contest-gallery-pro/index.php') {
            return;
        }

        #wp_enqueue_style( 'cg_options_tabcontent_v10', plugins_url('/v10-admin/options/cg_options_tabcontent.css', __FILE__), false , cg_get_version_for_scripts() );
        wp_enqueue_style( 'cg_wp_styles_v10', plugins_url('/v10-css/wp-styles.css', __FILE__), false , cg_get_version_for_scripts() );
        wp_enqueue_style( 'cg_options_style_v10', plugins_url('/v10-css/cg_options_style.css', __FILE__), false , cg_get_version_for_scripts() );

        wp_enqueue_style( 'cg_backend_gallery', plugins_url('/v10-css/backend/cg_backend_gallery.css', __FILE__), false, cg_get_version_for_scripts() );

        wp_enqueue_style( 'cg_main_menu_css', plugins_url('/v10-css/backend/cg_main_menu.css', __FILE__), false, cg_get_version_for_scripts() );

        if (!empty($_GET['users_management'])) {
            wp_enqueue_style( 'cg_contest_gallery_user_profile_image_css', plugins_url('/v10-css/backend/cg_contest_gallery_user_profile_image.css', __FILE__), false, cg_get_version_for_scripts());
        }

    }

}


add_action('admin_enqueue_scripts', 'cg_options_tabcontent_v10' );


// AJAX Script für Check Admin Image Upload im Backend
// Achtung! Für Backend AJAX Calls ist der FrontEnd Aufbau nicht erforderlich, nur die Action muss registriert werden

add_action( 'wp_ajax_nopriv_cg_check_wp_admin_upload_v10', 'cg_check_wp_admin_upload_v10' );
add_action( 'wp_ajax_cg_check_wp_admin_upload_v10', 'cg_check_wp_admin_upload_v10' );

if(!function_exists('cg_check_wp_admin_upload_v10')){

    function cg_check_wp_admin_upload_v10(){

        contest_gal1ery_db_check();

        $cgVersion = cg_get_version_for_scripts();

        if (!empty($_POST['cgVersionScripts'])) {
            if ($cgVersion != $_POST['cgVersionScripts']) {
                echo 'newversion';// has to be done this way, with echo and exit, not return!
                exit();
            }
        }

        global $wpdb;

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            require_once('v10-admin/gallery/wp-uploader.php');
            die();
        }
        else {
            exit();
        }

    }
}


// AJAX Script für Check Admin Image Upload im Backend ---- ENDE

add_action('wp_ajax_nopriv_post_cg_registry','post_cg_registry');
add_action('wp_ajax_post_cg_registry','post_cg_registry');

if(!function_exists('post_cg_registry')){

    function post_cg_registry(){
        global $wpdb;

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            require_once(__DIR__.'/../v10/v10-admin/users/frontend/registry/users-registry-check-name-mail-ajax.php');
            die();

        }
        else {

            exit();
        }

    }
}


