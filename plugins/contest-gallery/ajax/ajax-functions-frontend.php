<?php


add_action( 'wp_ajax_nopriv_post_cg_load_v10', 'post_cg_load_v10' );
add_action( 'wp_ajax_post_cg_load_v10', 'post_cg_load_v10' );
if(!function_exists('post_cg_load_v10')){

    function post_cg_load_v10() {

        global $wpdb;

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            require_once(__DIR__.'/../v10/v10-frontend/load-data-ajax.php');

            exit();
        } else {
            exit();
        }
    }
}

add_action('wp_ajax_nopriv_post_cg_set_frontend_cookie', 'post_cg_set_frontend_cookie');
add_action('wp_ajax_post_cg_set_frontend_cookie', 'post_cg_set_frontend_cookie');
if (!function_exists('post_cg_set_frontend_cookie')) {

    function post_cg_set_frontend_cookie()
    {

        global $wpdb;

        if (defined('DOING_AJAX') && DOING_AJAX) {

            $galeryID = intval(sanitize_text_field($_REQUEST['gid']));// is gidReal

            if(!isset($_COOKIE['contest-gal1ery-'.$galeryID.'-voting'])) {
                cg_set_cookie($galeryID,'voting');
                // thats it cookie is set... after that cookie is available in browser
            }
            exit();
        } else {
            exit();
        }
    }
}

add_action( 'wp_ajax_nopriv_post_cg_rate_v10_oneStar', 'post_cg_rate_v10_oneStar' );
add_action( 'wp_ajax_post_cg_rate_v10_oneStar', 'post_cg_rate_v10_oneStar' );
if(!function_exists('post_cg_rate_v10_oneStar')){

    function post_cg_rate_v10_oneStar() {

        global $wpdb;

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            require_once(__DIR__.'/../v10/v10-frontend/data/rating/rate-picture-one-star.php');

            exit();
        }
        else {
            exit();
        }
    }
}

add_action( 'wp_ajax_nopriv_post_cg_rate_v10_fiveStar', 'post_cg_rate_v10_fiveStar' );
add_action( 'wp_ajax_post_cg_rate_v10_fiveStar', 'post_cg_rate_v10_fiveStar' );
if(!function_exists('post_cg_rate_v10_fiveStar')){

    function post_cg_rate_v10_fiveStar() {

        global $wpdb;

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            require_once(__DIR__.'/../v10/v10-frontend/data/rating/rate-picture-five-star.php');

            exit();
        }
        else {
            exit();
        }
    }
}

// AJAX Script für rate picture ---- ENDE

// Add image gallery form upload

add_action( 'wp_ajax_nopriv_post_cg_gallery_form_upload', 'post_cg_gallery_form_upload' );
add_action( 'wp_ajax_post_cg_gallery_form_upload', 'post_cg_gallery_form_upload' );

if(!function_exists('post_cg_gallery_form_upload')){

    function post_cg_gallery_form_upload() {

        global $wpdb;

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            include(__DIR__.'/../v10/v10-frontend/user_upload/users-upload-check.php');

            exit();
        }
        else {
            exit();
        }
    }
}

// Add image gallery form upload ---- END

// Remove image user gallery

add_action( 'wp_ajax_nopriv_post_cg_gallery_user_delete_image', 'post_cg_gallery_user_delete_image' );
add_action( 'wp_ajax_post_cg_gallery_user_delete_image', 'post_cg_gallery_user_delete_image' );

if(!function_exists('post_cg_gallery_user_delete_image')){
    function post_cg_gallery_user_delete_image() {

        if(!is_user_logged_in()){
            return;
        }

        global $wpdb;

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            include(__DIR__.'/../v10/v10-frontend/gallery/gallery-user-delete-image.php');

            exit();
        }
        else {
            exit();
        }
    }
}

// Remove image user gallery ---- END

// Edit image user data gallery

add_action( 'wp_ajax_nopriv_post_cg_gallery_user_edit_image_data', 'post_cg_gallery_user_edit_image_data' );
add_action( 'wp_ajax_post_cg_gallery_user_edit_image_data', 'post_cg_gallery_user_edit_image_data' );

if(!function_exists('post_cg_gallery_user_edit_image_data')){
    function post_cg_gallery_user_edit_image_data() {

        if(!is_user_logged_in()){
            return;
        }

        global $wpdb;

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            include(__DIR__.'/../v10/v10-frontend/gallery/gallery-user-edit-image-data.php');

            exit();
        }
        else {
            exit();
        }
    }
}

// Edit image user data gallery ---- END

// Remove image user gallery

add_action( 'wp_ajax_nopriv_post_cg_changes_recognized', 'post_cg_changes_recognized' );
add_action( 'wp_ajax_post_cg_changes_recognized', 'post_cg_changes_recognized' );

if(!function_exists('post_cg_changes_recognized')){

    function post_cg_changes_recognized() {

        if(!is_user_logged_in()){
            return;
        }

        global $wpdb;

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            include(__DIR__.'/../v10/v10-frontend/gallery/changes-recognized.php');

            exit();
        }
        else {
            exit();
        }
    }
}

// Remove image user gallery ---- END



// AJAX Script für set comment Slider ---- ENDE
/*
add_action( 'wp_ajax_nopriv_post_cg_set_comment_v10', 'post_cg_set_comment_v10' );
add_action( 'wp_ajax_post_cg_set_comment_v10', 'post_cg_set_comment_v10' );
function post_cg_set_comment_v10() {

	global $wpdb;

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

		require_once('v10/v10-frontend/data/comment/set-comment-v10.php');
		die();

	}
	else {

		exit();
	}
}*/


// AJAX Script für set comment Slider ---- ENDE


// AJAX Script show comment Slider or out of Gallery





add_action( 'wp_ajax_nopriv_cg_show_set_comments_v10', 'cg_show_set_comments_v10' );
add_action( 'wp_ajax_cg_show_set_comments_v10', 'cg_show_set_comments_v10' );

if(!function_exists('cg_show_set_comments_v10')){

    function cg_show_set_comments_v10(){

        global $wpdb;

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            require_once(__DIR__.'/../v10/v10-frontend/data/comment/show-set-comments-v10.php');
            exit();

        }
        else {

            exit();
        }

    }

}

// AJAX Script show comment Slider or out of Gallery ---- ENDE


add_action( 'wp_ajax_nopriv_post_cg_login', 'post_cg_login' );
add_action( 'wp_ajax_post_cg_login', 'post_cg_login' );

if(!function_exists('post_cg_login')){

    function post_cg_login(){

        global $wpdb;

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            require_once(__DIR__.'/../v10/v10-admin/users/frontend/login/users-login-check-ajax.php');

            die();
        }
        else {
            exit();
        }
    }

}

###PRO###

// PRO version add google sign in user
add_action( 'wp_ajax_nopriv_post_cg_google_sign_in_add_user', 'post_cg_google_sign_in_add_user' );
add_action( 'wp_ajax_post_cg_google_sign_in_add_user', 'post_cg_google_sign_in_add_user' );
if(!function_exists('post_cg_google_sign_in_add_user')){

    function post_cg_google_sign_in_add_user() {

        global $wpdb;

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            include(__DIR__.'/../v10/v10-frontend/google/google-sign-in-add-user.php');
            exit();

        }
        else {
            exit();
        }
    }

}
// PRO version add google sign in user ---- END

// PRO version info recognized
add_action( 'wp_ajax_nopriv_post_cg_pro_version_info_recognized', 'post_cg_pro_version_info_recognized' );
add_action( 'wp_ajax_post_cg_pro_version_info_recognized', 'post_cg_pro_version_info_recognized' );

if(!function_exists('post_cg_pro_version_info_recognized')){

    function post_cg_pro_version_info_recognized() {

        if(!is_user_logged_in()){
            return;
        }

        global $wpdb;

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            include('v10/v10-admin/pro/pro-version-info-recognized.php');

            exit();
        }
        else {
            exit();
        }
    }

}
// PRO version info recognized ---- END


###PRO-END###
