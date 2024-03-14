<?php
// Handle plugin activation/deactivation
class Upvf_Actdeact{
	static function upvf_plugin_activate(){
		$htaccess = ABSPATH.".htaccess";
		$home_url = get_home_url();
		$upload_dir = wp_upload_dir();
		$htcode = array();
		$htcode[] = "RewriteRule ^".basename(content_url()) . "/" . wp_basename( $upload_dir['baseurl'] )."/upf-docs/(.*)$ ".home_url()."?file=$1 [QSA,L]";
		$written = insert_with_markers ( $htaccess, "User Private Files", $htcode );
	}
	
	static function upvf_plugin_deactivate(){
		$htaccess = ABSPATH.".htaccess";
		$written = insert_with_markers ( $htaccess, "User Private Files", '' );
	}
	
}