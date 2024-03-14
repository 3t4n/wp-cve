<?php

class Thumbnail_Editor_Scripts {
	
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'additional_scripts_admin' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'additional_scripts' ) );
	}
	
	public function additional_scripts_admin(){
		wp_enqueue_script( 'jquery.Jcrop', plugins_url( THE_PLUGIN_DIR . '/js/jquery.Jcrop.js' ) );
		wp_enqueue_script( 'jquery.cr', plugins_url( THE_PLUGIN_DIR . '/js/jquery.cr.js' ) );
		wp_enqueue_script( 'jquery-ui-resizable' );
		wp_enqueue_script( 'jquery-ui-tooltip' );
		wp_enqueue_style( 'jquery.Jcrop', plugins_url( THE_PLUGIN_DIR . '/css/jquery.Jcrop.css' ) );
		wp_enqueue_style( 'jquery-ui', plugins_url( THE_PLUGIN_DIR . '/css/jquery-ui.css' ) );
		wp_enqueue_style( 'th-editor-admin', plugins_url( THE_PLUGIN_DIR . '/css/editor.css' ) );

		wp_enqueue_script( 'ap.cookie', plugins_url( THE_PLUGIN_DIR . '/js/ap.cookie.js' ) );
		wp_enqueue_script( 'ap-tabs', plugins_url( THE_PLUGIN_DIR . '/js/ap-tabs.js' ) );
	}
	
	public function additional_scripts(){
		wp_enqueue_style( 'th-editor-front', plugins_url( THE_PLUGIN_DIR . '/css/editor-front.css' ) );
	}
	
}