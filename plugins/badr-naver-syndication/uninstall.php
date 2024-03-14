<?php
if( !defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) exit();

require_once (trailingslashit ( dirname ( __FILE__ ) ) . 'badr-syndication-class.php');

class badr_syndication_uninstall extends badrSyndication{
	
	var $aOptions = array('_syndication','_syndication_yeti');
	
	function init() {}
	
	function deleteOptions() {
		foreach($this->aOptions as $option) {
			delete_option($option);
		}
	}
	
	function deleteTables() {
		$this->_procDB('deleteTable');
	}
	function deleteMeta() {
		global $wpdb;
		$wpdb->delete( $wpdb->postmeta, array( 'meta_key' => '_syndication' ) );
	}
}

$badr_syndication_uninstall = new badr_syndication_uninstall();
$badr_syndication_uninstall->deleteOptions();
$badr_syndication_uninstall->deleteTables();
$badr_syndication_uninstall->deleteMeta();