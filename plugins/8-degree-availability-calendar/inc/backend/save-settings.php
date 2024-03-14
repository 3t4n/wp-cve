<?php
defined('ABSPATH') or die("No script kiddies please!");
/**
 * Posted Data
 *Array
(
    [action] => edac_settings_action
    [edac_optons] => 1
    [edac_layout]=>1
    [edac_from] => 
    [edac_to] => 
    [edac_unavailable_color] => 
    [day] => Array
        (
            [0] => 7
        )

    [month] => Array
        (
            [0] => 9
        )

    [year] => Array
        (
            [0] => 2015
        )

    [id] => Array
        (
            [0] => date-7-8-2015
        )

    [edac_nonce_field] => 256764d465
    [_wp_http_referer] => /8degree_plugins/8availability-calendar/wp-admin/admin.php?page=edac-plugin
    [settings_submit] => Save
) 
*/
//$this->print_array($_POST);
/**
 * general settings
 * */
foreach($_POST as $key=>$val)
{
    $$key = $val;
}
$edac_settings = $this->edac_settings;
$edac_settings['edac_layout'] = sanitize_text_field($_POST['edac_layout']);
$edac_settings['edac_from']=sanitize_text_field($_POST['edac_from']);
$edac_settings['edac_to']=sanitize_text_field($_POST['edac_to']);
$edac_settings['edac_unavailable_color'] = sanitize_text_field($_POST['edac_unavailable_color']);
$edac_settings['edac_legend'] = sanitize_text_field($_POST['edac_legend']);
$edac_settings['edac_legend_text'] = sanitize_text_field($_POST['edac_legend_text']);
$edac_settings['edac_language'] = sanitize_text_field($_POST['edac_language']);
update_option('edac_settings',$edac_settings);
$_SESSION['edac_message'] = __('Settings Saved Successfully.','edac-plugin');
wp_redirect(admin_url('admin.php?page=edac-plugin'));
exit();

