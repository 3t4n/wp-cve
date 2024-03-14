<?php
/**
 * Trigger this file on Plugin uninstall
 *
 * @package talentlms-wordpress
 */

use TalentlmsIntegration\Utils;

if (! defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

function tlms_uninstall()
{
    tlms_deleteOptions();
    tlms_deleteWPPages();
    Utils::tlms_deleteWoocomerceProducts();
    tlms_dropDB();
}

function tlms_deleteOptions()
{
    delete_option('tlms-domain');
    delete_option('tlms-apikey');
    delete_option('tlms-woocommerce-active');
}

function tlms_deleteWPPages()
{
    tlms_removeCoursesPage();
    tlms_removeSignupPage();
}

function tlms_removeCoursesPage()
{
    global $wpdb;
    $the_page_title = get_option('tlms_courses_page_title');
    $the_page_name  = get_option('tlms_courses_page_name');
    $the_page_id    = get_option('tlms_courses_page_id');

    if ($the_page_id) {
        wp_delete_post($the_page_id);
    }

    delete_option('tlms_courses_page_title');
    delete_option('tlms_courses_page_name');
    delete_option('tlms_courses_page_id');
}

function tlms_removeSignupPage()
{
    global $wpdb;
    $the_page_title = get_option('tlms_signup_page_title');
    $the_page_name  = get_option('tlms_signup_page_name');
    $the_page_id    = get_option('tlms_signup_page_id');

    if ($the_page_id) {
        wp_delete_post($the_page_id);
    }

    delete_option('tlms_signup_page_title');
    delete_option('tlms_signup_page_name');
    delete_option('tlms_signup_page_id');
}

function tlms_dropDB()
{
    global $wpdb;
    $wpdb->query('DROP TABLE ' . TLMS_COURSES_TABLE);
    $wpdb->query('DROP TABLE ' . TLMS_CATEGORIES_TABLE);
    $wpdb->query('DROP TABLE ' . TLMS_PRODUCTS_TABLE);
    $wpdb->query('DROP TABLE ' . TLMS_PRODUCTS_CATEGORIES_TABLE);
}
