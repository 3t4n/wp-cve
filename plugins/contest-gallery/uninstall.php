<?php
if(!defined('ABSPATH')){exit;}
// If uninstall not called from WordPress exit
if( !defined( 'WP_UNINSTALL_PLUGIN' ) )
exit ();

if(include('uninstall-check.php')){return;}

// have to be included here, because index.php will be not processed before!!!!
include('functions/general/option/cg-add-blog-option.php');
include('functions/general/option/cg-update-blog-option.php');
include('functions/general/option/cg-get-blog-option.php');
include('functions/general/option/cg-delete-blog-option.php');
include('functions/general/cg-remove-folder-recursively.php');

if(!function_exists('contest_gal1ery_rm_uploads_content')){
    function contest_gal1ery_rm_uploads_content($dir){

        // .htaccess requires extra glob!
        $dirContentLikeHtaccess = glob($dir.'/.*');

        foreach($dirContentLikeHtaccess as $item){
            if(is_file($item)){
                unlink($item);
            }
        }

        $dirContent = glob($dir.'/*');

        foreach($dirContent as $item){
            // 1. Ebene
            if(is_dir($item)){
                contest_gal1ery_rm_uploads_content($item);
            }
            else{
                if(is_file($item)){
                    unlink($item);
                }
            }

        }

        // is_dir check important here!
        if(is_dir($dir)){
            rmdir($dir);
        }

    }
}

if(!function_exists('cgRemoveFiles')){
    // Achtung! Löschen eines Plugins wird bei Multisite immer der Hauptinstanz 'network/admin' ausgeführt.
    function cgRemoveFiles($r){

        // Multisite Pfad Beispiel:
        // /var/www.../.../htdocs/wp-content/uploads/sites/5/contest-gallery/

        $upload_dir = wp_upload_dir();

        // '' entspricht 1
        if($r==1){
            $dir = $upload_dir['basedir']."/contest-gallery";
        }
        else{
            $dir = $upload_dir['basedir']."/sites/$r/contest-gallery";
        }

        contest_gal1ery_rm_uploads_content($dir);

        ###PRO###
        $dir = plugin_dir_path( __FILE__ );
        if(is_dir($dir.'../contest-gallery-google-sign-in-library')){
            cg_remove_folder_recursively($dir.'../contest-gallery-google-sign-in-library');
        }
        ###PRO---END###

    }
}


if(!function_exists('cgDropTables')){
    function cgDropTables($i){

        global $wpdb;

        $tablename = $wpdb->prefix . "$i"."contest_gal1ery";
        $tablename_ip = $wpdb->prefix . "$i"."contest_gal1ery_ip";
        $tablename_comments = $wpdb->prefix . "$i"."contest_gal1ery_comments";
        $tablename_options = $wpdb->prefix . "$i"."contest_gal1ery_options";
        $tablename_options_input = $wpdb->prefix . "$i"."contest_gal1ery_options_input";
        $tablename_email = $wpdb->prefix . "$i"."contest_gal1ery_mail";
        $tablename_entries = $wpdb->prefix . "$i"."contest_gal1ery_entries";
        $tablename_form_input = $wpdb->prefix . "$i"."contest_gal1ery_f_input";
        $tablename_form_output = $wpdb->prefix . "$i"."contest_gal1ery_f_output";
        $tablename_options_visual = $wpdb->prefix . "$i"."contest_gal1ery_options_visual";
        $tablename_email_admin = $wpdb->prefix . "$i"."contest_gal1ery_mail_admin";
        $wpOptions = $wpdb->prefix . "$i"."options";
        $tablename_contest_gal1ery_create_user_entries = $wpdb->prefix . "$i"."contest_gal1ery_create_user_entries";
        $tablename_contest_gal1ery_create_user_form = $wpdb->prefix . "$i"."contest_gal1ery_create_user_form";
        $tablename_contest_gal1ery_pro_options = $wpdb->prefix . "$i"."contest_gal1ery_pro_options";
        $tablename_mail_gallery = $wpdb->prefix . "$i"."contest_gal1ery_mail_gallery";
        $tablename_mail_confirmation = $wpdb->prefix . "$i"."contest_gal1ery_mail_confirmation";
        $tablename_mails_collected = $wpdb->prefix . "$i"."contest_gal1ery_mails_collected";
        $tablename_categories = $wpdb->prefix . "$i"."contest_gal1ery_categories";
        $tablename_comments_notification_options = $wpdb->base_prefix . "$i"."contest_gal1ery_comments_notification_options";
        $tablename_registry_and_login_options = $wpdb->base_prefix . "$i"."contest_gal1ery_registry_and_login_options";
        $tablename_google_options = $wpdb->base_prefix . "$i"."contest_gal1ery_google_options";
        $tablename_google_users = $wpdb->base_prefix . "$i"."contest_gal1ery_google_users";
        $tablename_wp_pages = $wpdb->base_prefix . "$i"."contest_gal1ery_wp_pages";
        $posts = $wpdb->base_prefix . "$i"."posts";
        $tablename_mail_user_comment = $wpdb->base_prefix . "$i"."contest_gal1ery_mail_user_comment";
        $tablename_mail_user_upload = $wpdb->base_prefix . "$i"."contest_gal1ery_mail_user_upload";
        $tablename_mail_user_vote = $wpdb->base_prefix . "$i"."contest_gal1ery_mail_user_vote";
        $tablename_user_comment_mails = $wpdb->base_prefix . "$i"."contest_gal1ery_user_comment_mails";
        $tablename_user_vote_mails = $wpdb->base_prefix . "$i"."contest_gal1ery_user_vote_mails";


        $sqlWpOptionsDelete = 'DELETE FROM ' . $wpOptions . ' WHERE';
        $sqlWpOptionsDelete .= ' option_name = %s';

        $wpdb->query( $wpdb->prepare(
            "
			$sqlWpOptionsDelete
		",
            "p_cgal1ery_db_version"
        ));

        $sql = "DROP TABLE $tablename";
        $sql1 = "DROP TABLE $tablename_ip";
        $sql2 = "DROP TABLE $tablename_comments";
        $sql4 = "DROP TABLE $tablename_options";
        $sql5 = "DROP TABLE $tablename_options_input";
        $sql6 = "DROP TABLE $tablename_email";
        $sql7 = "DROP TABLE $tablename_entries";
        $sql8 = "DROP TABLE $tablename_form_input";
        $sql9 = "DROP TABLE $tablename_form_output";
        $sql10 = "DROP TABLE $tablename_options_visual";
        $sql11 = "DROP TABLE $tablename_email_admin";
        $sql12 = "DROP TABLE $tablename_contest_gal1ery_create_user_entries";
        $sql13 = "DROP TABLE $tablename_contest_gal1ery_create_user_form";
        $sql14 = "DROP TABLE $tablename_contest_gal1ery_pro_options";
        $sql15 = "DROP TABLE $tablename_mail_gallery";
        $sql16 = "DROP TABLE $tablename_mail_confirmation";
        $sql17 = "DROP TABLE $tablename_mails_collected";
        $sql18 = "DROP TABLE $tablename_categories";
        $sql19 = "DROP TABLE $tablename_comments_notification_options";
        $sql20 = "DROP TABLE $tablename_registry_and_login_options";
        $sql21 = "DROP TABLE $tablename_google_options";
        $sql22 = "DROP TABLE $tablename_google_users";
        $sql23 = "DROP TABLE $tablename_wp_pages";
        $sql24 = "DROP TABLE $tablename_mail_user_comment";
        $sql25 = "DROP TABLE $tablename_mail_user_upload";
        $sql26 = "DROP TABLE $tablename_mail_user_vote";
        $sql27 = "DROP TABLE $tablename_user_comment_mails";
        $sql28 = "DROP TABLE $tablename_user_vote_mails";

        $wpdb->query($sql);
        $wpdb->query($sql1);
        $wpdb->query($sql2);
        $wpdb->query($sql4);
        $wpdb->query($sql5);
        $wpdb->query($sql6);
        $wpdb->query($sql7);
        $wpdb->query($sql8);
        $wpdb->query($sql9);
        $wpdb->query($sql10);
        $wpdb->query($sql11);
        $wpdb->query($sql12);
        $wpdb->query($sql13);
        $wpdb->query($sql14);
        if($wpdb->get_var("SHOW TABLES LIKE '$tablename_mail_gallery'") == $tablename_mail_gallery){// extra condition for this old table otherwise an error might be shown when deleting this
            $wpdb->query($sql15);
        }
        $wpdb->query($sql16);
        $wpdb->query($sql17);
        $wpdb->query($sql18);
        $wpdb->query($sql19);
        $wpdb->query($sql20);
        $wpdb->query($sql21);
        $wpdb->query($sql22);

        // delete all sub pages of contest-gallery parent custom types before
        $WpPages = $wpdb->get_results( "SELECT WpPage FROM $tablename_wp_pages" );
        $collect = '';
        $hasCgWpCustomPostTypesToDelete = false;
        foreach ($WpPages as $WpPageRow){
            if(!$collect){
                $hasCgWpCustomPostTypesToDelete = true;
                $collect .= '((ID='.$WpPageRow->WpPage.' AND post_mime_type="contest-gallery-plugin-page" && post_type="contest-gallery") OR (post_parent='.$WpPageRow->WpPage.' AND post_mime_type="contest-gallery-plugin-page" && post_type="contest-gallery"))';
            }else{
                $hasCgWpCustomPostTypesToDelete = true;
                $collect .= ' OR ((ID='.$WpPageRow->WpPage.' AND post_mime_type="contest-gallery-plugin-page" && post_type="contest-gallery") OR (post_parent='.$WpPageRow->WpPage.' AND post_mime_type="contest-gallery-plugin-page" && post_type="contest-gallery"))';
            }
        }

        if($collect && $hasCgWpCustomPostTypesToDelete && count($WpPages)){
            $posts = $wpdb->get_results( "SELECT DISTINCT ID FROM $posts WHERE ($collect)" );
            foreach ($posts as $post){
                wp_delete_post($post->ID,true);
            }
        }

        $wpdb->query($sql23);

        $wpdb->query($sql24);
        $wpdb->query($sql25);
        $wpdb->query($sql26);
        $wpdb->query($sql27);
        $wpdb->query($sql28);

        cg_delete_blog_option($i,"p_cgal1ery_reg_code");
        cg_delete_blog_option($i,"p_c1_k_g_r_9");
        cg_delete_blog_option( $i,"p_cgal1ery_db_version");
        cg_delete_blog_option($i,"p_cgal1ery_install_date");
        cg_delete_blog_option( $i,"p_cgal1ery_count_users");
        cg_delete_blog_option( $i,"p_cgal1ery_uploadscounter_reminder");
        cg_delete_blog_option( $i,"p_cgal1ery_count_uploads");
        cg_delete_blog_option( $i,"p_cgal1ery_uploadscounter_reminder");
        cg_delete_blog_option( $i,"p_cgal1ery_count_users");
        cg_delete_blog_option( $i,"p_cgal1ery_reminder_time");
        cg_delete_blog_option( $i,"p_cgal1ery_count_users");
        cg_delete_blog_option( $i,"p_cgal1ery_reg_code");

        // just to go sure all this old database entries are getting deleted
        cg_delete_blog_option($i,"p_cgal1ery_pro_version_fail_status");
        cg_delete_blog_option($i,"p_cgal1ery_pro_version_main_key");
        cg_delete_blog_option($i,"p_cgal1ery_pro_version_main_key_is_old");
        cg_delete_blog_option($i,"p_cgal1ery_pro_version_success_status");
        cg_delete_blog_option($i,"p_cgal1ery_pro_version_key_new_version_string");
        cg_delete_blog_option($i,"p_cgal1ery_pro_version_key_information");
        cg_delete_blog_option($i,"p_cgal1ery_pro_version_fail_content_plugins_area");
        cg_delete_blog_option($i,"p_cgal1ery_pro_version_fail_content_main_menu_area");
        cg_delete_blog_option($i,"p_cgal1ery_pro_version_fail_registered_sites_limit_key");
        cg_delete_blog_option($i,"p_cgal1ery_pro_version_fail_registered_sites_limit_reached_already_registered_websites");
        cg_delete_blog_option($i,"p_cgal1ery_pro_version_fail_registered_sites_limit_reached");
        cg_delete_blog_option($i,"p_cgal1ery_pro_version_fail_domain_switched");
        cg_delete_blog_option($i,"p_cgal1ery_pro_version_key_activation_time");
        cg_delete_blog_option($i,"p_cgal1ery_pro_version_key_expiration_time");
        cg_delete_blog_option($i,"CgEntriesOwnSlugName");

    }
}


// Löschen aller Dateien von Contest Gallery --- ENDE
// Löschen von Tabellen	und Files von Contest Gallery
if (is_multisite()) {

    if(include('uninstall-check.php')){return;}

    global $wpdb;

    $wpBlogs = $wpdb->prefix . "blogs";

    $getBlogIDs = $wpdb->get_results( "SELECT blog_id FROM $wpBlogs ORDER BY blog_id ASC" );
    foreach($getBlogIDs as $key => $value){
        foreach($value as $key1 => $value1){
            if($value1==1){
                $i='';
                $r=1;
            }
            else{
                $i=$value1."_";
                $r=$value1;
            }
            cgDropTables($i);
            cgRemoveFiles($r);
        }
    }
}
else{

    if(include('uninstall-check.php')){return;}

    $i='';
    $r=1;
    cgDropTables($i);
    cgRemoveFiles($r);
}
// Löschen von Tabellen und Files von Contest Gallery --- ENDE


	  
?>