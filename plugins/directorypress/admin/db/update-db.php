<?php
if(!get_option('directorypress_db_update_3_3_4') || get_option('directorypress_db_update_3_3_4') != 'updated'){
	directorypress_db_update_3_3_4();
}
function directorypress_db_update_3_3_4(){
	global $wpdb;
	$prefix = $wpdb->prefix;
	if($wpdb->get_var("SELECT id FROM `".$prefix."directorypress_fields` WHERE slug = 'categories_list' AND is_configuration_page = '0'")){
		$wpdb->query("UPDATE `".$prefix."directorypress_fields` SET is_configuration_page ='1' WHERE slug ='categories_list'");
	}
	update_option('directorypress_db_update_3_3_4', 'updated');
}
// version 3.4.0
if(!get_option('directorypress_db_update_3_4_0') || get_option('directorypress_db_update_3_4_0') != 'updated'){
	directorypress_db_update_3_4_0();
}
function directorypress_db_update_3_4_0(){
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $wpdb;
	$prefix = $wpdb->prefix;

	if(class_exists('Redux') && isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_style']) && ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_style'] == 1 || empty($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_style']))){
		Redux::set_option( 'directorypress_admin_settings', 'directorypress_single_listing_style', 'default' );
	}
	update_option('directorypress_db_update_3_4_0', 'updated');
}

// version 3.4.5
if(!get_option('directorypress_db_update_3_4_1') || get_option('directorypress_db_update_3_4_1') != 'updated'){
	directorypress_db_update_3_4_1();
}
function directorypress_db_update_3_4_1(){
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $wpdb;
	$prefix = $wpdb->prefix;
	
	$wpdb->query("ALTER TABLE `".$prefix."directorypress_packages` ADD `number_of_package_renew_allowed` varchar(255) NOT NULL AFTER `number_of_listings_in_package`");
	
	update_option('directorypress_db_update_3_4_1', 'updated');
}

// version 3.4.5
if(!get_option('directorypress_db_update_3_5_11') || get_option('directorypress_db_update_3_5_11') != 'updated'){
	directorypress_db_update_3_5_11();
}
function directorypress_db_update_3_5_11(){
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $wpdb;
	$prefix = $wpdb->prefix;
	
	$wpdb->query("ALTER TABLE `".$prefix."directorypress_packages` MODIFY `number_of_package_renew_allowed` varchar(255)");
	
	update_option('directorypress_db_update_3_5_11', 'updated');
}
// version 3.6.0
if(!get_option('directorypress_db_update_3_6_0') || get_option('directorypress_db_update_3_6_0') != 'updated'){
	directorypress_db_update_3_6_0();
}
function directorypress_db_update_3_6_0(){
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $wpdb;
	$prefix = $wpdb->prefix;
	
	$wpdb->query("ALTER TABLE `".$prefix."directorypress_packages` ADD `options` mediumtext NOT NULL AFTER `fields`");
	
	update_option('directorypress_db_update_3_6_0', 'updated');
}
if(!get_option('directorypress_db_update_3_6_1') || get_option('directorypress_db_update_3_6_1') != 'updated'){
	directorypress_db_update_3_6_1();
}
function directorypress_db_update_3_6_1(){
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $wpdb;
	$prefix = $wpdb->prefix;
	
	$wpdb->query("ALTER TABLE `".$prefix."directorypress_packages` ADD `who_can_submit` text NOT NULL AFTER `description`");
	update_option('directorypress_db_update_3_6_1', 'updated');
}
if(!get_option('directorypress_db_update_3_6_2') || get_option('directorypress_db_update_3_6_2') != 'updated'){
	directorypress_db_update_3_6_2();
}
function directorypress_db_update_3_6_2(){
	global $DIRECTORYPRESS_ADIMN_SETTINGS, $wpdb;
	$prefix = $wpdb->prefix;
	
	$wpdb->query("ALTER TABLE `".$prefix."directorypress_fields` ADD `field_search_label` varchar(255) NOT NULL AFTER `name`");
	update_option('directorypress_db_update_3_6_2', 'updated');
}
