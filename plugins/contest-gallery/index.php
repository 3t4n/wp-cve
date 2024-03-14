<?php
/*
Plugin Name: Contest Gallery
Description: Contact form, files, photos and videos upload contest gallery plugin for WordPress. Create contact forms for entries with or without file/image upload. Create user registration form. Create login form. Create responsive galleries and allow to vote for any kind of entries.
Version: 21.3.6
Author: Contest Gallery
Author URI: http://www.contest-gallery.com/
Text Domain: contest-gallery
Domain Path: /languages
*/
/*error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);
error_reporting(E_STRICT);
define('WP_DEBUG',true);*/
/*define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);*/
// $wpdb->show_errors(true);
//exit( var_dump( $wpdb->last_query ) ); auch eine möglichkeit
// $lastid = $wpdb->insert_id;

// Query debug example
//$wpdb->show_errors(); //setting the Show or Display errors option to true
//$wpdb->query();
//$wpdb->print_error();

/*add_filter( 'avatar_defaults', 'wpb_new_gravatar' );
function wpb_new_gravatar ($avatar_defaults) {
    $myavatar = 'http://example.com/wp-content/uploads/2017/01/wpb-default-gravatar.png';
    $avatar_defaults[$myavatar] = "Default Gravatar";
    return $avatar_defaults;
}*/

if(!defined('ABSPATH')){exit;}

/**###NORMAL###**/
if(!function_exists('cg_normal_version_register_activation_hook')){
    function cg_normal_version_register_activation_hook(){

        deactivate_plugins( '/contest-gallery-pro/index.php' );

        // better to delete manually
        // do it when contest gallery key is valid
        // not here!!!!!
        /*cg_general_pro_version_delete_normal_version()*/

    }
}
register_activation_hook( __FILE__, 'cg_normal_version_register_activation_hook' );
/**###NORMAL-END###**/

if(!function_exists('cg_add_defer_to_cg_js_files')){
    function cg_add_defer_to_cg_js_files( $url)
    {
        if (strpos( $url, 'contest-gallery/v10/v10-js/' ) !== false)
        {
            return "$url' defer='defer";
        }
        return $url;
    }
}

add_filter( 'clean_url', 'cg_add_defer_to_cg_js_files');// clean url is available since 2.3 WP version

include('functions/general/mail/cg-user-vote-mail.php');
include('functions/general/mail/cg-user-vote-mail-prepare.php');
include('functions/general/mail/cg-user-comment-mail.php');
include('functions/general/mail/cg-user-comment-mail-prepare.php');
include('functions/general/option/cg-add-blog-option.php');
include('functions/general/option/cg-update-blog-option.php');
include('functions/general/option/cg-get-blog-option.php');
include('functions/general/option/cg-delete-blog-option.php');
include('functions/general/cg-get-version.php');
include('functions/general/cg-on-wp-mail-error.php');
include('functions/general/cg-create-json-files-when-activating.php');
include('functions/general/convert-values.php');
include('functions/general/cg-hash.php');
include('functions/general/cg-general-functions.php');
include('functions/frontend/cg-create-noscript-html.php');
include('functions/frontend/cg-shortcode-interval-check.php');
include('functions/google/cg-create-get-google-options.php');
include('functions/frontend/cg-google-sign-in-verification.php');
include('functions/general/registry/cg-registry-functions.php');
include('functions/general/registry/cg-registry-add-profile-image.php');
include('functions/general/registry/create/cg-registry-create-functions.php');
include('functions/general/registry/update/cg-registry-update-functions.php');
include('ajax/ajax-functions-frontend.php');
include('ajax/ajax-functions-backend.php');
include('functions/general/cg-check-file-types.php');
/**###NORMAL###**/
include('functions/general/normal/cg-update-to-pro.php');
/**###NORMAL-END###**/


if(!empty($_POST['cg_export_votes'])){

    if(is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) && ($_GET['page']=='contest-gallery/index.php' OR $_GET['page']=='contest-gallery-pro/index.php')){

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if(is_plugin_active( cg_get_version().'/index.php' )==false){
            echo "Please contact site administrator if you see this, code 855";exit();
        }


        if(!empty($_POST['cg_export_votes_all'])){
            include('v10/v10-admin/export/export-votes-all.php');
            add_action('init','cg_votes_csv_export_all');
            do_action('cg_votes_csv_export_all');
        }else{
            include('v10/v10-admin/export/export-votes.php');
            add_action('init','cg_votes_csv_export');
            do_action('cg_votes_csv_export');
        }

    }

}

if(!empty($_POST['contest_gal1ery_post_create_data_csv']) && !empty($_GET['edit_gallery']) && !empty($_GET['option_id']) &&  ($_GET['page']=='contest-gallery/index.php' OR $_GET['page']=='contest-gallery-pro/index.php')){

    if(is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) && ($_GET['page']=='contest-gallery/index.php' OR $_GET['page']=='contest-gallery-pro/index.php')){
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if(is_plugin_active( cg_get_version().'/index.php' )==false){
            echo "Please contact site administrator if you see this, code 856";exit();
        }

        include('v10/v10-admin/export/export-images-data.php');
        add_action('init','cg_images_data_csv_export');
        do_action('cg_images_data_csv_export');

        include('v10/v10-admin/export/controller.php');
        add_action('init','cg_remove_not_required_coded_csvs');
        do_action('cg_remove_not_required_coded_csvs');

    }

}

if(!empty($_POST['cg_create_user_data_csv_new_export']) && !empty($_GET['users_management']) && !empty($_GET['option_id']) && ($_GET['page']=='contest-gallery/index.php' OR $_GET['page']=='contest-gallery-pro/index.php')){
    if(is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) && ($_GET['page']=='contest-gallery/index.php' OR $_GET['page']=='contest-gallery-pro/index.php')){

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if(is_plugin_active( cg_get_version().'/index.php' )==false){
            echo "Please contact site administrator if you see this, code 857";exit();
        }

        include('v10/v10-admin/export/export-user-data-registry-new-export.php');
        add_action('init','cg_user_data_registry_csv_new_export');
        do_action('cg_user_data_registry_csv_new_export');

        include('v10/v10-admin/export/controller.php');
        add_action('init','cg_remove_not_required_coded_csvs');
        do_action('cg_remove_not_required_coded_csvs');

    }

}

if(!empty($_POST['cg_action_check_and_download_mail_log_for_gallery'])){

    if(is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) && ($_GET['page']=='contest-gallery/index.php' OR $_GET['page']=='contest-gallery-pro/index.php')){

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        if(is_plugin_active( cg_get_version().'/index.php' )==false){
            echo "Please contact site administrator if you see this, code 8561";exit();
        }

        include('v10/v10-admin/export/export-mail-log-data.php');

    }

}

//Create MySQL WP Table

// Register a new shortcode: [book]
add_shortcode( 'cg_gallery', 'contest_gal1ery_frontend_gallery' );
include(__DIR__.'/shortcodes/cg_gallery.php');
add_shortcode( 'cg_gallery_user', 'contest_gal1ery_frontend_gallery_user_images' );
include(__DIR__.'/shortcodes/cg_gallery_user.php');
add_shortcode( 'cg_gallery_no_voting', 'contest_gal1ery_frontend_gallery_no_voting' );
include(__DIR__.'/shortcodes/cg_gallery_no_voting.php');
add_shortcode( 'cg_gallery_winner', 'contest_gal1ery_frontend_gallery_winner' );
include(__DIR__.'/shortcodes/cg_gallery_winner.php');
add_shortcode( 'cg_users_upload', 'contest_gal1ery_users_upload' );
include(__DIR__.'/shortcodes/cg_users_upload.php');
add_shortcode('cg_users_contact', 'contest_gal1ery_users_contact');
include(__DIR__ . '/shortcodes/cg_users_contact.php');
add_shortcode( 'cg_mail_confirm', 'contest_gal1ery_check_confirmation_link' );// Achtung !!! Mail Confirm wird schon verwendet in users-upload-check
include(__DIR__.'/shortcodes/cg_mail_confirm.php');

// setup_theme runs before theme is loaded!
// see https://codex.wordpress.org/Plugin_API/Action_Reference
add_shortcode( 'cg_users_reg', 'contest_gal1ery_users_registry' );
include(__DIR__.'/shortcodes/cg_users_reg.php');
add_shortcode( 'cg_users_login', 'contest_gal1ery_users_login' );
include(__DIR__.'/shortcodes/cg_users_login.php');
add_shortcode('cg_entry_on_off', 'contest_gal1ery_entry_on_off');
include(__DIR__ . '/shortcodes/cg_entry_on_off.php');

include('functions/general/sql/contest-gallery-create-tables.php');

register_activation_hook( __FILE__, 'contest_gal1ery_db_check' );

include('functions/general/contest-gallery-db-version-check.php');

// Update DB


// Update DB - END

// Add a top level menu to wordpress

// page_title â€” The title of the page as shown in the <title> tags
// menu_title â€” The name of your menu displayed on the dashboard
// capability â€” Minimum capability required to view the menu
// menu_slug â€” Slug name to refer to the menu; should be a unique name
// function : Function to be called to display the page content for the item
// icon_url â€” URL to a custom image to use as the Menu icon
// position â€” Location in the menu order where it should appear

//create submenu items

// parent_slug : Slug name for the parent menu ( menu_slug previously defi ned)
// page_title : The title of the page as shown in the <title> tags
// menu_title : The name of your submenu displayed on the dashboard
// capability : Minimum capability required to view the submenu
// menu_slug : Slug name to refer to the submenu; should be a unique name
// function : Function to be called to display the page content for the item


/*

add_action( 'wp_enqueue_scripts', 'ajax_test_enqueue_scripts1' );
if(!function_exists('ajax_test_enqueue_scripts1')){
function ajax_test_enqueue_scripts1() {
	if( is_single() ) {
		wp_enqueue_style( 'love1', plugins_url( '/love1.css', __FILE__ ) );
	}

	wp_enqueue_script( 'cg_rate', plugins_url( '/cg_rate2.js', __FILE__ ), array('jquery'), '1.0', true );

	wp_localize_script( 'cg_rate', 'postlove1', array(
		'ajax_url1' => admin_url( 'admin-ajax.php' )
	));

}
}*/

// init languages

if(!function_exists('contest_gallery_init_languages')){
    function contest_gallery_init_languages() {

        $folderName = (basename(dirname(__FILE__))=='trunk') ? 'contest-gallery' :  basename(dirname(__FILE__)); // check if offline development
        load_plugin_textdomain( 'contest-gallery', false, $folderName . '/languages/' );

    }
}
add_action('plugins_loaded', 'contest_gallery_init_languages');


// init languages --- ENDE


// localize Scripts --- ENDE


add_action('admin_menu', 'contest_gallery_add_page');
if(!function_exists('contest_gallery_add_page')){
    function contest_gallery_add_page() {
        /**###NORMAL###**/
        add_menu_page( 'Contest Gallery', 'Contest Gallery', 'edit_posts', __FILE__, 'contest_gallery_action', 'none');
        /**###NORMAL---END###**/
    }
}

if(!function_exists('cg_backend_menu_star')){
    function cg_backend_menu_star() {
        ###NORMAL###
            wp_enqueue_style( 'cg_backend_menu_star', plugins_url('/v10/v10-css/backend/cg_backend_menu_star.css', __FILE__), false, cg_get_version_for_scripts() );
        ###NORMAL---END###
    }
}
add_action( 'admin_enqueue_scripts', 'cg_backend_menu_star', 10 );

// WP Media Upload wird hier aktiviert!!!!!
if (is_admin ()){
    add_action ( 'admin_enqueue_scripts', 'wp_enqueue_media');
}
// WP Media Upload wird hier aktiviert!!!!! ---- ENDE



//------------------------------------------------------------
// ----------------------------------------------------------- Hauptseite fÃ¼r hochgeladene Bilder ----------------------------------------------------------
//------------------------------------------------------------

include('functions/general/cg-pre-delete-wp-user.php');
include('functions/general/json-data/cg-json-single-view-order.php');
include('functions/general/json-data/cg-json-upload-form.php');
include('functions/general/json-data/cg-json-upload-form-info-data-files.php');
include('functions/general/json-data/cg-json-upload-form-info-data-files-new.php');
include('functions/general/json-data/cg-check-and-repair-image-data-file.php');
include('functions/general/sql/cg-copy-rating.php');
include('functions/general/sql/cg-copy-comments.php');
include('functions/general/sanitize/cg-sanitize-post.php');
include('functions/general/sanitize/cg-sanitize-files.php');
include('functions/general/cg-copy-pre7-gallery-images.php');
/**###NORMAL###**/
include('functions/general/option/cg-reset-to-normal-version-options-if-required.php');
###NORMAL---END###
include('functions/general/cg-copy-fb-sites.php');
include('functions/general/cg-create-fb-html.php');
include('functions/general/cg-create-fb-sites.php');
include('functions/general/cg-create-exif-data.php');
include('functions/general/cg-get-user-ip.php');
include('functions/general/cg-get-user-ip-type.php');
include('functions/general/cg-edit-image.php');
include('functions/general/cg-delete-images.php');
include('functions/general/cg-deactivate-images.php');
include('functions/general/cg-plugin-mce-css-to-add.php');
include('functions/general/cg-remove-folder-recursively.php');
include('functions/general/json-data/cg-actualize-all-images-data-deleted-images.php');
include('functions/general/json-data/cg-actualize-all-images-data-info-file.php');
include('functions/general/json-data/cg-actualize-all-images-data-sort-values-file.php');
include('functions/general/json-data/cg-actualize-all-images-data-sort-values-file-set-array.php');
include('functions/general/json-data/cg-set-data-in-images-files-with-all-data.php');
include('functions/backend/render/cg-shortcode-interval-configuration-container.php');
include('functions/backend/render/cg-preview-images-to-delete-container.php');
include('functions/backend/render/cg-multiple-files-for-post-container.php');
include('functions/backend/render/cg-sort-gallery-files-container.php');
include('functions/backend/render/cg-backend-background-drop.php');
include('functions/backend/render/cg-backend-gallery-dynamic-message.php');
include('functions/backend/render/cg-backend-render-go-top-options.php');
include('functions/backend/render/cg-total-images-shown-in-frontend-zero.php');
include('functions/backend/render/cg-add-fields-pressed-after-content-modification.php');

add_action('cg_delete_files_and_folder','cg_delete_files_and_folder');
if(!function_exists('cg_delete_files_and_folder')){
    function cg_delete_files_and_folder($folderName,$isDeleteFilesOnly = false){

        if(is_dir($folderName)){

            $folderContent = scandir($folderName);

            foreach ($folderContent as $item){
                if(is_file($folderName.'/'.$item)){
                    unlink($folderName.'/'.$item);
                }
            }

            if(!$isDeleteFilesOnly){
                 rmdir($folderName);
            }

        }

    }
}

// do not remove this!
include('v10/include-functions-v10.php');

//  add contest_gallery_action as ajax

// view control backend

add_action( 'wp_ajax_post_contest_gallery_action_ajax', 'post_contest_gallery_action_ajax' );
if(!function_exists('post_contest_gallery_action_ajax')){

    function post_contest_gallery_action_ajax() {
        cg_check_nonce();

        $isBackendCall = true;
        $isAjaxCall = true;

        global $wp_version;
        $sanitize_textarea_field = ($wp_version<4.7) ? 'sanitize_text_field' : 'sanitize_textarea_field';

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

            $user = wp_get_current_user();

            if (
                is_super_admin($user->ID) ||
                in_array( 'administrator', (array) $user->roles ) ||
                in_array( 'editor', (array) $user->roles ) ||
                in_array( 'author', (array) $user->roles )
            ) {

                if(!empty($isBackendCall)){
                    if(empty($_POST['cgBackendHash'])){
                        echo 0;die;
                    }else{

                        $cgBackendHashHash = $_POST['cgBackendHash'];
                        $cgBackendHashDecoded = wp_salt( 'auth').'---cgbackend---';
                        $cgBackendHashToCompare = md5($cgBackendHashDecoded);

                        if ($cgBackendHashHash != $cgBackendHashToCompare){
                            echo 0;die;
                        }

                    }

                }

                $isGalleryAjaxBackendLoad = true;
                $cgVersion = cg_get_version_for_scripts();

                include('index-functions.php');

            }else{
                echo "<h2>MISSINGRIGHTS<br>This area can be edited only as administrator, editor or author.</h2>";
                exit();
            }

            exit();
        }
        else {
            exit();
        }

    }

}

//  add contest_gallery_action as ajax ---- END

if (!function_exists('cg_index_scripts_and_functions')) {
    function cg_index_scripts_and_functions()
    {
        $cgVersion = cg_get_version_for_scripts();

        include('index-scripts.php');
        include('index-functions.php');
    }
}

if (!function_exists('contest_gallery_action')) {
    function contest_gallery_action()
    {
        echo "<div id='cg_main_container'>";
            cg_create_nonce();
            cg_create_version_input();
            cg_backend_gallery_dynamic_message();
            cg_backend_background_drop();
            cg_backend_render_go_to_options();
            cg_index_scripts_and_functions();
        echo "</div>";
    }
}


add_filter ('template_include', 'cg_template_include_for_cg_post_type');
//add_filter ('single_template', 'wpse255804_redirect_page_template');// page_template is not working and single_template does not takes complete own template, only small part other parts are not controllable
//add_filter ('page_template', 'wpse255804_redirect_page_template');
if(!function_exists('cg_template_include_for_cg_post_type')){
    function cg_template_include_for_cg_post_type ($template) {
        global $post;
        if(!empty($post) && $post->post_type=='contest-gallery'){
            global $wpdb;
            global $wp;
            $tablename = $wpdb->prefix . "contest_gal1ery";
            $tablename_options = $wpdb->prefix . "contest_gal1ery_options";
            $tablename_wp_pages = $wpdb->prefix . "contest_gal1ery_wp_pages";

            $wp_upload_dir = wp_upload_dir();
            $slugNameFilePath = $wp_upload_dir['basedir'].'/contest-gallery/gallery-general/post-type-slug-name-do-not-edit-or-remove.txt';
            $slugName = 'contest-gallery';
            if(file_exists($slugNameFilePath)){
                $slugName = trim(file_get_contents($slugNameFilePath));
            }
            $domain = get_bloginfo('wpurl');
            if($domain.'/'.$slugName == home_url( $wp->request )){
                wp_redirect($domain, 301);
                return;
            }
            $postParent = $post->post_parent;
            $isParentPage = false;
            $cgIdCalled = 0;
            if(empty($postParent)){
                $isParentPage = true;
                $postParent = $post->ID;
                $WpPageParent = $wpdb->get_var( "SELECT WpPage FROM $tablename_wp_pages WHERE WpPage = $postParent LIMIT 1" );
            }else{
                $WpPageParent = $wpdb->get_var( "SELECT WpPage FROM $tablename_wp_pages WHERE WpPage = $postParent LIMIT 1" );
            }
            if(!empty($WpPageParent)){// check fo redirect here and redirect here, because template_redirect is best for it, headers not sent here
                $optionsRowObject = $wpdb->get_row( "SELECT * FROM $tablename_options WHERE WpPageParent = $WpPageParent OR WpPageParentUser = $WpPageParent OR WpPageParentNoVoting = $WpPageParent OR WpPageParentWinner = $WpPageParent LIMIT 1" );
                if(!empty($optionsRowObject)){
                    $GalleryID = $optionsRowObject->id;
                    $wp_upload_dir = wp_upload_dir();
                    $GalleryIDuser = 0;
                    if($optionsRowObject->WpPageParent == $WpPageParent){$GalleryIDuser=$GalleryID;}
                    else if($optionsRowObject->WpPageParentUser == $WpPageParent){$GalleryIDuser=$GalleryID.'-u';}
                    else if($optionsRowObject->WpPageParentNoVoting == $WpPageParent){$GalleryIDuser=$GalleryID.'-nv';}
                    else if($optionsRowObject->WpPageParentWinner == $WpPageParent){$GalleryIDuser=$GalleryID.'-w';}

                    $optionsFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-options.json';
                    $options = json_decode(file_get_contents($optionsFile),true);
                    $options = (!empty($options[$GalleryIDuser])) ? $options[$GalleryIDuser] : $options;
                    if($isParentPage && !empty($options['pro']['WpPageParentRedirectURL'])){
                        wp_redirect($options['pro']['WpPageParentRedirectURL'], 301);
                    }else{
                        if(!$isParentPage){
                            $postId = $post->ID;
                            $cgIdCalled = $wpdb->get_var( "SELECT id FROM $tablename WHERE WpPage = $postId OR WpPageUser = $postId OR WpPageNoVoting = $postId OR WpPageWinner = $postId LIMIT 1" );
                        }
                    }

                    global $cgWpPageParent;
                    $cgWpPageParent = $WpPageParent;
                    global $isCgParentPage;
                    $isCgParentPage = $isParentPage;
                    global $cgId;
                    $cgId = $cgIdCalled;
                    global $cgOptionsArray;
                    $cgOptionsArray = $options;
                    global $cgGalleryIDuser;
                    $cgGalleryIDuser = $GalleryIDuser;
                    //$template = WP_PLUGIN_DIR . '/contest-gallery-pro/test.php';
                    $template = __DIR__ . '/templates/landing.php';

                }
            }
            return $template;
        }else{
            return $template;
        }

    }
}
include (__DIR__.'/register_post_type.php');

if(!function_exists('cg_check_and_add_post_type_file_if_required')){
    function cg_check_and_add_post_type_file_if_required($slugNameFilePath,$CgEntriesOwnSlugNameOption) {
        if(!empty($CgEntriesOwnSlugNameOption) && !file_exists($slugNameFilePath)){
            $CgEntriesOwnSlugNameValue = trim(sanitize_text_field($CgEntriesOwnSlugNameOption));
            file_put_contents($slugNameFilePath,$CgEntriesOwnSlugNameValue);
            $wp_upload_dir = wp_upload_dir();
            $rewriteRulesChangedFilePath = $wp_upload_dir['basedir'].'/contest-gallery/gallery-general/rewrite-rules-changed-do-not-edit-or-remove.txt';
            // do it for sure, so flush_rewrite_rules(false) will be executed in register_post_type.php one time for sure
            file_put_contents($rewriteRulesChangedFilePath,'changed');// register_post_type has to be executed in register_post_type.php, which will be executed on init, after register_post_type()
        }
    }
}

add_action( 'upgrader_process_complete', 'cg_wp_upe_upgrade_completed_post_type_check', 10, 2 );
if(!function_exists('cg_wp_upe_upgrade_completed_post_type_check')){
    /**
     * This function runs when WordPress completes its upgrade process
     * It iterates through each plugin updated to see if ours is included
     * @param $upgrader_object Array
     * @param $options Array
     */
    function cg_wp_upe_upgrade_completed_post_type_check( $upgrader_object, $options ) {
        // The path to our plugin's main file
        $our_plugin = plugin_basename( __FILE__ );
        // If an update has taken place and the updated type is plugins and the plugins element exists
        if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
            // Iterate through the plugins being updated and check if ours is there
            foreach( $options['plugins'] as $plugin ) {
                if( $plugin == $our_plugin ) {
                    $wp_upload_dir = wp_upload_dir();
                    $slugNameFilePath = $wp_upload_dir['basedir'].'/contest-gallery/gallery-general/post-type-slug-name-do-not-edit-or-remove.txt';
                    if (is_multisite()) {
                        $CgEntriesOwnSlugNameOption = cg_get_blog_option(get_current_blog_id(),'CgEntriesOwnSlugName');
                    }else{
                        $CgEntriesOwnSlugNameOption = get_option('CgEntriesOwnSlugName');
                    }
                    cg_check_and_add_post_type_file_if_required($slugNameFilePath,$CgEntriesOwnSlugNameOption);
                }
            }
        }
    }
}

// will not scale down images higher then 2560px to 2560px
//https://make.wordpress.org/core/2019/10/09/introducing-handling-of-big-images-in-wordpress-5-3/
add_filter( 'big_image_size_threshold', '__return_false' );//

if(!function_exists('cg_delete_post_hook_for_wp_pages')){
    function cg_delete_post_hook_for_wp_pages($postId) {
        global $wpdb;
        $tablename_wp_pages = $wpdb->prefix . "contest_gal1ery_wp_pages";
        $wpdb->query($wpdb->prepare(
            "
				DELETE FROM $tablename_wp_pages WHERE WpPage = %d
			",
            $postId
        ));
    }
}

add_action('delete_post','cg_delete_post_hook_for_wp_pages');

// for testing
$wp_upload_dir = wp_upload_dir();
$rewriteRulesChangedFilePath = $wp_upload_dir['basedir'].'/contest-gallery/gallery-general/rewrite-rules-changed-do-not-edit-or-remove.txt';
//file_put_contents($rewriteRulesChangedFilePath,'changed');

/*
 ** just for test if required to test wpautop
 add_filter('the_content', 'cg_wpautop_customized',200);
function cg_wpautop_customized($content) {
	$content = wpautop($content, true);
	return $content;
}
*/
