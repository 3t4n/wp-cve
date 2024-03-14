<?php
/** 
 *	Functions
 */

class ATTMGR_Function {
	/*
	 *	Load 
	 */
	public static function load() {
		add_action( 'init', array( 'ATTMGR_Updation', 'plugin_update') );
		add_action( 'init', array( 'ATTMGR_Function', 'init' ) );
		add_action( 'init', array( 'ATTMGR_Shortcode', 'init' ) );
		add_action( 'init', array( 'ATTMGR_User', 'init' ) );
		add_action( 'init', array( 'ATTMGR_Calendar', 'init' ) );
		add_action( 'init', array( 'ATTMGR_Form', 'init' ) );
		add_action( 'init', array( 'ATTMGR_Info', 'init' ) );
		add_action( 'init', array( 'ATTMGR_Admin_Page', 'init' ) );
		add_action( 'init', array( 'ATTMGR_CRON', 'init' ) );
	}

	/**
	 *	Initialize
	 */
	public static function init(){
		add_action( 'wp_enqueue_scripts', array( 'ATTMGR_Function', 'front_script' ) );
		add_action( ATTMGR::PLUGIN_ID.'_front_script', array( 'ATTMGR_Function', 'add_front_script' ) );
	}

	/** 
	 *	Load css and js for front page
	 */
	public static function front_script() {
		do_action( ATTMGR::PLUGIN_ID.'_front_script' );
	}

	/**
	 *	Front script
	 */
	public static function add_front_script() {
		global $attmgr;
		$option = get_option( ATTMGR::PLUGIN_ID );
		// css
		wp_enqueue_style(
			ATTMGR::PLUGIN_ID.'_style',				// handle
			$attmgr->mypluginurl.'front.css',		// src
			false, 									// deps
			ATTMGR::PLUGIN_VERSION, 				// ver
			'all'									// media
		);
		// js
		wp_enqueue_script( 
			ATTMGR::PLUGIN_ID.'_script',			// handle
			$attmgr->mypluginurl.'front.js',		// src
			array( 'jquery' ),						// deps
			ATTMGR::PLUGIN_VERSION, 				// ver
			true 									// in footer
		);
	}

	/**
	 *	Get user portrait
	 */
	public static function get_portrait( $portrait, $staff ) {
		global $attmgr;
		$option = get_option( ATTMGR::PLUGIN_ID );
		$p = null;
		// Use Featured image
		if ( empty( $option['general']['use_avatar'] ) ) {
			$p = get_the_post_thumbnail( $staff->data[ATTMGR::PLUGIN_ID.'_mypage_id'], 'thumbnail' );
		}
		// Use Avatar
		else {
			$p = get_avatar( $staff->data['ID'] );
			$search = array( "class='", 'class="' );
			$replace = array( "class='wp-post-image ", 'class="wp-post-image ' );
			$p = str_replace( $search, $replace, $p );
		}

		if ( ! empty( $p ) ) {
			$portrait = $p;
		}
		else {
			$portrait = sprintf( '<img src="%simg/nopoatrait.png" />', $attmgr->mypluginurl );
		}

		// Link
		if ( ! empty( $staff->data['user_url'] ) ) {
			$portrait = sprintf( '<a href="%s">%s</a>', $staff->data['user_url'], $portrait );
		}
		return $portrait;
	}

}
