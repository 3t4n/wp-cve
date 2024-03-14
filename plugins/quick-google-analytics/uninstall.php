<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// die when the file is called directly
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
//define a vairbale and store an option name as the value.
$option_name = 'quickgoogleanalytics_ua';
$option_name1 = 'quickgoogleanalytics_ip';
$option_name2 = 'quickgoogleanalytics_g';
$option_name3 = 'quickgoogleanalytics_select';

//call delete option and use the vairable inside the quotations
delete_option($option_name);
delete_option($option_name1);
delete_option($option_name2);
delete_option($option_name3);

// for site options in Multisite
delete_site_option($option_name);
delete_site_option($option_name1);
delete_site_option($option_name2);
delete_site_option($option_name3);
?>