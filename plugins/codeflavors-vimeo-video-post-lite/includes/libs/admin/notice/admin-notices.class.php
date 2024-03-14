<?php

namespace Vimeotheque\Admin\Notice;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @ignore
 */
class Admin_Notices{
	/**
	 * Instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @access public
	 * @static
	 *
	 * @var Admin_Notices
	 */
	public static $instance = null;

	/**
	 * @var Notice_Interface[]
	 */
	private $notices = [];

	/**
	 * Clone.
	 *
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'codeflavors-vimeo-video-post-lite' ), '2.0' );
	}

	/**
	 * Wakeup.
	 *
	 * Disable unserializing of the class.
	 *
	 * @access public
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'codeflavors-vimeo-video-post-lite' ), '2.0' );
	}

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @access public
	 * @static
	 *
	 * @return Admin_Notices
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Admin_Notices constructor.
	 */
	private function __construct() {}

	/**
	 * @param Notice_Interface $notice
	 */
	public function register( Notice_Interface $notice ){
		if( did_action( 'admin_notices' ) ){
			_doing_it_wrong( __METHOD__,  __('You must register the notice before "admin_notices" hook is triggered.', 'codeflavors-vimeo-video-post-lite'), '2.0' );
		}

		$this->notices[] = $notice;
	}

	/**
	 * Show the notice
	 */
	public function show_notices(){
		foreach( $this->notices as $notice ){
			$notice->get_notice();
		}
	}

	/**
	 * @return Notice_Interface[]
	 */
	public function get_notices(){
		return $this->notices;
	}
}