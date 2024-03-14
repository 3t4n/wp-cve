<?php

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
$tablename_mail_admin = $wpdb->prefix . "contest_gal1ery_mail_admin";
$tablename_mail_confirmation = $wpdb->prefix . "contest_gal1ery_mail_confirmation";
$tablenameCategories = $wpdb->prefix . "contest_gal1ery_categories";
$tablenameOptionsPro = $wpdb->prefix . "contest_gal1ery_pro_options";
$tablename_create_user_form = $wpdb->prefix . "contest_gal1ery_create_user_form";
$contest_gal1ery_create_user_entries = $wpdb->prefix . "contest_gal1ery_create_user_entries";


// search for id, change rowid additional to gallery
// categories have to be reselected
// Categories set to 0
$wpdb->query($wpdb->prepare(
    "
				DELETE FROM $tablename WHERE GalleryID = %d
			",
    $optionID
));

// search for pid
$wpdb->query($wpdb->prepare(
    "
				DELETE FROM $tablenameEntries WHERE GalleryID = %d
			",
    $optionID
));

// search for pid
$wpdb->query($wpdb->prepare(
    "
				DELETE FROM $tablenameComments WHERE GalleryID = %d
			",
    $optionID
));

// search for pid
$wpdb->query($wpdb->prepare(
    "
				DELETE FROM $tablenameIp WHERE GalleryID = %d
			",
    $optionID
));

