<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Jetpack_Contact_Form_Auto_Reply {

	/**
	 * The single instance of Jetpack_Contact_Form_Auto_Reply.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct ( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token = 'jetpack_contact_form_auto_reply';

		// Load plugin environment variables
		$this->file = $file;
		$this->dir = dirname( $this->file );

		add_action( 'grunion_pre_message_sent', array( $this, 'send_auto_reply' ), 10, 3 );

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
	} // End __construct ()

	/**
	 * Send auto reply email
	 * @param  integer $post_id      ID of feedback post
	 * @param  array   $all_values   All fields submitted
	 * @param  array   $extra_values 'Extra' fields that are not part of the default set
	 * @return void
	 */
	public function send_auto_reply ( $post_id = 0, $all_values = array(), $extra_values = array() ) {

		if( ! $post_id ) {
			return;
		}

		// Don't send if email is marked as spam if option is set
		$not_spam = get_option( $this->settings->base . 'not_spam', '' );
		$post_status = get_post_status( $post_id );
		if( $not_spam && 'spam' == $post_status ) {
			return;
		}

		// Get auto reply options
		$enable = get_option( $this->settings->base . 'enable', '' );
		$email_subject = get_option( $this->settings->base . 'email_subject', '' );
		$email_content = get_option( $this->settings->base . 'email_content', '' );
		$email_from_name = get_option( $this->settings->base . 'email_from_name', get_bloginfo( 'name' ) );
		$email_from_address = get_option( $this->settings->base . 'email_from_address', get_bloginfo( 'admin_email' ) );
		$email_field = get_option( $this->settings->base . 'email_field', '' );

		if( ! $enable || ! $email_content || ! $email_field ) {
			return;
		}

		// Get all submitted fields
		foreach( $all_values as $k => $v ) {

			// Remove field count ID from start of field key name
			$underscore = strpos( $k, '_' );
			$start = $underscore + 1;
			$field = substr( $k, $start );

			$fields[ $field ] = $v;
		}

		// Get recipient adress
		$to = '';
		foreach( $fields as $k => $v ) {
			if( $k == $email_field ) {
				$to = $v;
				break;
			}
		}

		if( ! $to ) {
			return;
		}

		// Replace dynamic content with field data
		foreach( $fields as $field => $value ) {
			$email_subject = str_replace( '{' . $field . '}', $value, $email_subject );
			$email_content = str_replace( '{' . $field . '}', $value, $email_content );
		}

		$email_content = wpautop( $email_content );

		$headers = 'From: "' . $email_from_name  .'" <' . $email_from_address  . ">\r\n" .
				   "Content-Type: text/html;";

		// Filter email parameters
		$to = apply_filters( $this->_token . '_email_recipient', $to, $post_id );
		$email_subject = apply_filters( $this->_token . '_email_subject', $email_subject, $post_id );
		$email_content = apply_filters( $this->_token . '_email_content', $email_content, $post_id );
		$headers = apply_filters( $this->_token . '_email_headers', $headers, $post_id );

		// Send auto reply
		wp_mail( $to, $email_subject, $email_content, $headers );
	} // End send_auto_reply ()

	/**
	 * Load plugin localisation
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'jetpack-contact-form-auto-reply', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation ()

	/**
	 * Load plugin textdomain
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain () {
	    $domain = 'jetpack-contact-form-auto-reply';

	    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main Jetpack_Contact_Form_Auto_Reply Instance
	 *
	 * Ensures only one instance of Jetpack_Contact_Form_Auto_Reply is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Jetpack_Contact_Form_Auto_Reply()
	 * @return Main Jetpack_Contact_Form_Auto_Reply instance
	 */
	public static function instance ( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install () {
		$this->_log_version_number();
	} // End install ()

	/**
	 * Log the plugin version number.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number () {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

}
