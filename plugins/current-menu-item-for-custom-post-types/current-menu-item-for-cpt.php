<?php

/**
 * Plugin Name: Current Menu Item for Custom Post Types
 * Description: Allows you to highlight the current menu item by assigning a page to a custom post type.
 * Author: Roland Murg
 * Version: 1.6
 */


function cmicpt_menu()
{
    add_submenu_page('options-general.php', __('Current Menu Item for Post Types', 'cmicpt'), __('Current Menu Item for Post Types', 'cmicpt'), 'activate_plugins', 'current-menu-item-cpt', 'cmicpt_view');
}
add_action('admin_menu', 'cmicpt_menu');

function cmicpt_admin_enqueue_files()
{
    wp_enqueue_style('cmicpt-style', plugins_url('', __FILE__) . '/css/cmicpt-admin.css');
}
add_action('admin_print_styles-settings_page_current-menu-item-cpt', 'cmicpt_admin_enqueue_files');

function cmicpt_settings_link($links)
{
    $link = '<a href="options-general.php?page=current-menu-item-cpt">Settings</a>';
    array_unshift($links, $link);
    return $links;
}
add_filter("plugin_action_links_" . plugin_basename(__FILE__), 'cmicpt_settings_link');

function cmicpt_get_settings_name()
{

    if (function_exists('pll_current_language')) {
        return 'cmicpt-data-' . pll_current_language();
    }
    if (function_exists('icl_object_id')) {
        return 'cmicpt-data-' . ICL_LANGUAGE_CODE;
    }

    return 'cmicpt-data';
}

function cmicpt_view()
{

    $cmicptClass = json_decode(get_site_option('cmicpt-class'));
    $postTypes = get_post_types(array('public' => true, '_builtin' => false), 'objects', 'and');
    if (isset($cmicptClass->showBuiltin) && $cmicptClass->showBuiltin == 1) {
        $postTypesBuiltIn = get_post_types(array('public' => true, '_builtin' => true), 'objects', 'and');
        $postTypes = (object) array_merge((array) $postTypesBuiltIn, (array) $postTypes);
    }

    require_once 'include/view.php';
}


function cmicpt_save_settings()
{
    if (!isset($_POST['cmicpt_token'])) {
        return false;
    }

    if (!wp_verify_nonce($_POST['cmicpt_token'], 'cmicpt_save_settings')) {
        wp_redirect(add_query_arg(['page' => 'current-menu-item-cpt', 'cmicpt-message' => 'invalid_nonce'], admin_url('options-general.php')));
        exit;
    }

    $dataSettingName = cmicpt_get_settings_name();

    $cmicptClasses['item'] = sanitize_text_field(str_replace('.', '', $_POST['custom_class_name']));
    $cmicptClasses['parent'] = sanitize_text_field(str_replace('.', '', $_POST['custom_parent_class_name']));
    $cmicptClasses['showBuiltin'] = (isset($_POST['show_builtin_post_types'])) ? sanitize_text_field($_POST['show_builtin_post_types']) : '';
    update_site_option('cmicpt-class', json_encode($cmicptClasses));
    $cmicptClass = json_decode(get_site_option('cmicpt-class'));
    $postTypes = get_post_types(array('public' => true, '_builtin' => false), 'objects', 'and');
    if ($cmicptClass->showBuiltin == 1) {
        $postTypesBuiltIn = get_post_types(array('public' => true, '_builtin' => true), 'objects', 'and');
        $postTypes = (object) array_merge((array) $postTypesBuiltIn, (array) $postTypes);
    }
    $cmicptData = array();
    foreach ($postTypes as $postType) {
        if (!empty($_POST[$postType->name]) && $_POST[$postType->name] != '') {
            $cmicptData[$postType->name] = sanitize_text_field($_POST[$postType->name]);
        }
    }
    update_site_option($dataSettingName, json_encode($cmicptData));

    wp_redirect(add_query_arg(['page' => 'current-menu-item-cpt', 'cmicpt-message' => 'settings_saved'], admin_url('options-general.php')));
    exit;
}
add_action('admin_init', 'cmicpt_save_settings');

function cmicpt_removable_query_args($args)
{
    $args[] = 'cmicpt-message';

    return $args;
}
add_filter('removable_query_args', 'cmicpt_removable_query_args');

require_once 'include/filter.php';
