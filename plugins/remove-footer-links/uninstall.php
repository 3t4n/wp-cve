<?php
/**
 * @package: Remove_Footer_Links
 * @author: plugindeveloper
 * @version: 1.0.0
 * @author_uri: https://profiles.wordpress.org/plugindeveloper/
 * @since 1.0.0
 */
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
$option_name = 'remove_footer_links';

$option_values = get_option($option_name);

$remove_data = isset($option_values['remove_data_uninstall']) ? $option_values['remove_data_uninstall'] : 0;

if($remove_data){

    delete_option($option_name);

    // for site options in Multisite
    delete_site_option($option_name);

}
