<?php
	add_action( 'init', 'pt_plus_cmb_initialize_cmb_meta_boxes', 9999 );
	/**
		* Initialize the metabox class.
	*/
	function pt_plus_cmb_initialize_cmb_meta_boxes() {
		if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once THEPLUS_PLUGIN_PATH.'post-type/metabox/init.php';
	}
	
	if ( ! defined( 'ABSPATH' ) ) { exit; }
?>