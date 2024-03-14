<?php

// view control backend

add_action('wp_ajax_post_cg_gallery_view_control_backend', 'post_cg_gallery_view_control_backend');

if (!function_exists('post_cg_gallery_view_control_backend')) {
    function post_cg_gallery_view_control_backend()
    {

        contest_gal1ery_db_check();

        $cgVersion = cg_get_version_for_scripts();

        if (!empty($_POST['cgVersionScripts'])) {

            if ($cgVersion != $_POST['cgVersionScripts']) {
                echo 'newversion';// has to be done this way, with echo and exit, not return!
                exit();
            }

        } else if (empty($_POST['cgVersionScripts']) && !empty($_POST['cgGalleryFormSubmit'])) { // IMPORTANT that data is not saved when wrong data is send when updateting 109900

            echo "<div id='cgStepsNavigationTop' ></div>";
            echo "<div id='cgSortable' style='width:100%;text-align:center;'><h4>New gallery version detected please reload this page manually one more time</h4></div>";
            exit();

        }

        $isBackendCall = true;
        $isAjaxCall = true;
        $isAjaxGalleryCall = true;

        global $wp_version;
        $sanitize_textarea_field = ($wp_version < 4.7) ? 'sanitize_text_field' : 'sanitize_textarea_field';

        if (defined('DOING_AJAX') && DOING_AJAX) {

            $user = wp_get_current_user();

            if (
                is_super_admin($user->ID) ||
                in_array('administrator', (array)$user->roles) ||
                in_array('editor', (array)$user->roles) ||
                in_array('author', (array)$user->roles)
            ) {

                if (!empty($isBackendCall)) {

                    if (empty($_POST['cgGalleryHash'])) {
                        echo 0;
                        die;
                    } else {

                        $galleryHash = $_POST['cgGalleryHash'];
                        $galleryHashDecoded = wp_salt('auth') . '---cngl1---' . $_POST['cg_id'];
                        $galleryHashToCompare = md5($galleryHashDecoded);

                        if ($galleryHash != $galleryHashToCompare) {
                            echo 0;
                            die;
                        }

                    }

                }

                include(__DIR__.'/../v10/v10-admin/gallery/gallery.php');

            } else {
                echo "<h2>MISSINGRIGHTS<br>This area can be edited only as administrator, editor or author.</h2>";
                exit();
            }

            exit();
        } else {
            exit();
        }
    }
}

// view control backend ---- END

// view control backend

add_action('wp_ajax_post_cg_gallery_save_categories_changes', 'post_cg_gallery_save_categories_changes');

if (!function_exists('post_cg_gallery_save_categories_changes')) {
    function post_cg_gallery_save_categories_changes()
    {

        contest_gal1ery_db_check();

        $isBackendCall = true;
        $isAjaxCall = true;

        $isAjaxCategoriesCall = true;

        global $wp_version;
        $sanitize_textarea_field = ($wp_version < 4.7) ? 'sanitize_text_field' : 'sanitize_textarea_field';


        if (defined('DOING_AJAX') && DOING_AJAX) {

            $user = wp_get_current_user();

            if (
                is_super_admin($user->ID) ||
                in_array('administrator', (array)$user->roles) ||
                in_array('editor', (array)$user->roles) ||
                in_array('author', (array)$user->roles)
            ) {
                include(__DIR__.'/../v10/v10-admin/gallery/save-categories-changes.php');
            } else {
                echo "<div id='cgSaveCategoriesCouldNotBeChanged'><h2>MISSINGRIGHTS<br>This area can be edited only as administrator, editor or author.</h2></div>";
                exit();
            }

            exit();
        } else {
            exit();
        }
    }
}

// view control backend ---- END

// sort files

add_action('wp_ajax_post_cg_gallery_sort_files', 'post_cg_gallery_sort_files');

if (!function_exists('post_cg_gallery_sort_files')) {
    function post_cg_gallery_sort_files()
    {

        contest_gal1ery_db_check();

        $isBackendCall = true;
        $isAjaxCall = true;

        $isAjaxCategoriesCall = true;

        global $wp_version;
        $sanitize_textarea_field = ($wp_version < 4.7) ? 'sanitize_text_field' : 'sanitize_textarea_field';


        if (defined('DOING_AJAX') && DOING_AJAX) {

            $user = wp_get_current_user();

            if (
                is_super_admin($user->ID) ||
                in_array('administrator', (array)$user->roles) ||
                in_array('editor', (array)$user->roles) ||
                in_array('author', (array)$user->roles)
            ) {
                include(__DIR__.'/../v10/v10-admin/gallery/sort-gallery-files.php');
            } else {
                echo "<div id='cgSaveCategoriesCouldNotBeChanged'><h2>MISSINGRIGHTS<br>This area can be edited only as administrator, editor or author.</h2></div>";
                exit();
            }

            exit();
        } else {
            exit();
        }
    }
}

// sort files ---- END

// save json

add_action('wp_ajax_post_cg_shortcode_interval_conf', 'post_cg_shortcode_interval_conf');

if (!function_exists('post_cg_shortcode_interval_conf')) {
    function post_cg_shortcode_interval_conf()
    {
        contest_gal1ery_db_check();

        $isBackendCall = true;
        $isAjaxCall = true;

        $isAjaxCategoriesCall = true;

        global $wp_version;
        $sanitize_textarea_field = ($wp_version < 4.7) ? 'sanitize_text_field' : 'sanitize_textarea_field';

        if (defined('DOING_AJAX') && DOING_AJAX) {

            $user = wp_get_current_user();

            if (
                is_super_admin($user->ID) ||
                in_array('administrator', (array)$user->roles) ||
                in_array('editor', (array)$user->roles) ||
                in_array('author', (array)$user->roles)
            ) {

                include(__DIR__.'/../v10/v10-admin/gallery/save-shortcode-interval-conf.php');

            } else {
                echo "<div id='cgSaveCategoriesCouldNotBeChanged'><h2>MISSINGRIGHTS<br>This area can be edited only as administrator, editor or author.</h2></div>";
                exit();
            }

            exit();
        } else {
            exit();
        }
    }
}

// save json ---- END

// AJAX Script für set comment ---- ENDE
// check nickname
add_action( 'wp_ajax_post_cg_check_nickname_edit_profile', 'post_cg_check_nickname_edit_profile' );
if(!function_exists('post_cg_check_nickname_edit_profile')){
    function post_cg_check_nickname_edit_profile() {

        $_POST = cg1l_sanitize_post($_POST);
        contest_gal1ery_db_check();

        $isBackendCall = true;
        $isAjaxCall = true;

        global $wp_version;
        $sanitize_textarea_field = ($wp_version<4.7) ? 'sanitize_text_field' : 'sanitize_textarea_field';

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            $user = wp_get_current_user();

            $hasUserGroupAllowedToEdit = cgHasUserGroupAllowedToEdit($user);

            if($hasUserGroupAllowedToEdit){

                $nickname = sanitize_text_field($_POST['nickname']);
                $cg_user_id = absint($_POST['cg_user_id']);

                global $wpdb;

                $table_usermeta = $wpdb->prefix . "usermeta";
                $user_id_check = $wpdb->get_var($wpdb->prepare("SELECT user_id FROM $table_usermeta WHERE meta_key = 'nickname' AND meta_value = %s",[$nickname]));

                if(!empty($user_id_check) AND $cg_user_id != $user_id_check){
                    echo 'nickname-exists';
                    die;
                }else{
                    echo 'nickname-not-exists';
                    die;
                }

            }else{
                echo 'do-nothing';die;
            }

      }else{
            echo 'do-nothing';die;
      }

    }
}
// check nickname --- END

// add contest gallery user profile image
add_action( 'wp_ajax_post_cg_backend_image_upload', 'post_cg_backend_image_upload' );
if(!function_exists('post_cg_backend_image_upload')){
    function post_cg_backend_image_upload() {

        global $wpdb;

        $tablename = $wpdb->base_prefix . "contest_gal1ery";

        $_POST = cg1l_sanitize_post($_POST);
        if(!empty($_FILES) AND !empty($_FILES['cg_input_image_upload_file']) AND !empty($_FILES['cg_input_image_upload_file']['tmp_name']) AND !empty($_FILES['cg_input_image_upload_file']['tmp_name'][0])){
            $_FILES = cg1l_sanitize_files($_FILES,'cg_input_image_upload_file',2100000);
        }

        $user = wp_get_current_user();
        $WpUserId  = absint($_POST['user_id']);

        $isAdministrator = false;

        if(is_super_admin($user->ID) || in_array( 'administrator', (array) $user->roles )){
            $isAdministrator = true;
        }

        if($user->ID != $WpUserId && $isAdministrator != true){// another user or not administrator user can't edit profile image
            return;
        }

        if(!empty($_POST['cg_input_image_upload_file_to_delete_wp_id'])){// then image must be removed!
            $WpProfileImage = $wpdb->get_row($wpdb->prepare("SELECT WpUpload, WpUserId FROM $tablename WHERE WpUserId = %d && IsProfileImage = 1",[$WpUserId]));

            if($WpProfileImage->WpUserId == $user->ID){
                $wpdb->query($wpdb->prepare(
                    "
            DELETE FROM $tablename WHERE WpUserId = %d && IsProfileImage = %d 
        ",
                    $WpUserId, 1
                ));
                // source and database _posts table entry  will be deleted
                wp_delete_attachment($WpProfileImage->WpUpload);
            }
        }

        if(!empty($_FILES) AND !empty($_FILES['cg_input_image_upload_file']) AND !empty($_FILES['cg_input_image_upload_file']['tmp_name']) AND !empty($_FILES['cg_input_image_upload_file']['tmp_name'][0])){
                    cg_registry_add_profile_image('cg_input_image_upload_file',$WpUserId);
                }

    }
}
// add contest gallery user profile image --- END