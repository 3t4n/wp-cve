<?php

if (!empty($_GET['option_id']) AND !empty($_POST['cg_delete_gallery'])) {

    $optionID = absint($_GET["option_id"]);

    global $wpdb;

    $tablename = $wpdb->prefix . "contest_gal1ery";
    $tablenameOptions = $wpdb->prefix . "contest_gal1ery_options";
    $tablenameEntries = $wpdb->prefix . "contest_gal1ery_entries";
    $tablenameComments = $wpdb->prefix . "contest_gal1ery_comments";
    $tablenameIp = $wpdb->prefix . "contest_gal1ery_ip";
    $tablenameMail = $wpdb->prefix . "contest_gal1ery_mail";
    $tablename_options_input = $wpdb->prefix . "contest_gal1ery_options_input";
    $tablename_form_input = $wpdb->prefix . "contest_gal1ery_f_input";
    $tablename_form_output = $wpdb->prefix . "contest_gal1ery_f_output";
    $tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
    $tablename_email_admin = $wpdb->prefix . "contest_gal1ery_mail_admin";
    $tablename_mail_confirmation = $wpdb->prefix . "contest_gal1ery_mail_confirmation";
    $tablenameCategories = $wpdb->prefix . "contest_gal1ery_categories";
    $tablenameOptionsPro = $wpdb->prefix . "contest_gal1ery_pro_options";
    $tablename_create_user_form = $wpdb->prefix . "contest_gal1ery_create_user_form";
    $contest_gal1ery_create_user_entries = $wpdb->prefix . "contest_gal1ery_create_user_entries";
    $tablename_mail_user_upload = $wpdb->base_prefix . "contest_gal1ery_mail_user_upload";
    $tablename_mail_user_vote = $wpdb->prefix . "contest_gal1ery_mail_user_vote";
    $tablename_user_vote_mails = $wpdb->prefix . "contest_gal1ery_user_vote_mails";
    $tablename_mail_user_comment = $wpdb->prefix . "contest_gal1ery_mail_user_comment";
    $tablename_user_comment_mails = $wpdb->prefix . "contest_gal1ery_user_comment_mails";
    $tablename_wp_pages = $wpdb->prefix . "contest_gal1ery_wp_pages";

    $options = $wpdb->get_row("SELECT * FROM $tablenameOptions WHERE id = '$optionID'");

    $galleryVersion = $options->Version;

    if(intval($galleryVersion)>=21 || !empty($options->WpPageParent)){
        $rowObjects = $wpdb->get_results("SELECT * FROM $tablename WHERE GalleryID = '$optionID'");
        foreach ($rowObjects as $rowObject){
            if(!empty($rowObject->WpPage)){wp_delete_post($rowObject->WpPage,true);}
            if(!empty($rowObject->WpPageUser)){wp_delete_post($rowObject->WpPageUser,true);}
            if(!empty($rowObject->WpPageNoVoting)){wp_delete_post($rowObject->WpPageNoVoting,true);}
            if(!empty($rowObject->WpPageWinner)){wp_delete_post($rowObject->WpPageWinner,true);}
        }
    }

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablename WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablenameOptions WHERE id = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablenameEntries WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablenameComments WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablenameIp WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablenameMail WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablename_options_input WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablename_form_input WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablename_form_output WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablename_options_visual WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablename_email_admin WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablename_mail_confirmation WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablenameCategories WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablenameOptionsPro WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablename_create_user_form WHERE GalleryID = %d
			",
        $optionID
    ));

    // SINCE 11100 or so Do not delete $contest_gal1ery_create_user_entries main-user
    // maybe delete some days (IREGENDWANNMAL) users who registered multiple times with same data but and then are then confirmed after multiple registrations
    // Delete only information fields first
    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $contest_gal1ery_create_user_entries WHERE GalleryID = %d AND Field_Type != %s AND Field_Type != %s AND Field_Type != %s AND Field_Type != %s
			",
        $optionID,'password','password-confirm','main-user-name','main-mail'
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablename_mail_user_upload WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablename_mail_user_vote WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablename_user_vote_mails WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablename_mail_user_comment WHERE GalleryID = %d
			",
        $optionID
    ));

    $wpdb->query($wpdb->prepare(
        "
				DELETE FROM $tablename_user_comment_mails WHERE GalleryID = %d
			",
        $optionID
    ));

    if(!empty($options->WpPageParent)){
        $wpdb->query($wpdb->prepare(
            "
				DELETE FROM $tablename_wp_pages WHERE WpPage = %d
			",
            $options->WpPageParent
        ));
        wp_delete_post($options->WpPageParent,true);
    }
    if(!empty($options->WpPageParentUser)){
        $wpdb->query($wpdb->prepare(
            "
				DELETE FROM $tablename_wp_pages WHERE WpPage = %d
			",
            $options->WpPageParentUser
        ));
        wp_delete_post($options->WpPageParentUser,true);
    }
    if(!empty($options->WpPageParentNoVoting)){
        $wpdb->query($wpdb->prepare(
            "
				DELETE FROM $tablename_wp_pages WHERE WpPage = %d
			",
            $options->WpPageParentNoVoting
        ));
        wp_delete_post($options->WpPageParentNoVoting,true);
    }
    if(!empty($options->WpPageParentWinner)){
        $wpdb->query($wpdb->prepare(
            "
				DELETE FROM $tablename_wp_pages WHERE WpPage = %d
			",
            $options->WpPageParentWinner
        ));
        wp_delete_post($options->WpPageParentWinner,true);
    }

    $upload_dir = wp_upload_dir();

    cg_remove_folder_recursively($upload_dir['basedir'] . '/contest-gallery/gallery-id-' . $optionID . '');

}

?>