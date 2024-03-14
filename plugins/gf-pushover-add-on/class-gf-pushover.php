<?php

include_once('lib/Pushover.php');
GFForms::include_addon_framework();

class GFPushover extends GFAddOn {
	
	protected $_version = GF_PUSHOVER_VERSION;
	protected $_min_gravityforms_version = '1.9';
	protected $_slug = 'gf-pushover-add-on';
	protected $_path = 'gf-pushover-add-on/gf-pushover-add-on.php';
	protected $_full_path = __FILE__;
	protected $_url = 'https://wp2pgpmail.com';
	protected $_title = 'Gravity Pushover';
	protected $_short_title = 'Pushover';
	protected $api = null;
	private static $_instance = null;
	
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function init() {
		parent::init();
		add_filter( 'gform_notification_services', array( $this, 'add_pushover_notification_service' ) );
		add_filter( 'gform_pre_send_email', array( $this, 'maybe_send_pushover_notification' ), 10, 4 );
		add_action( 'admin_enqueue_scripts', array( $this, 'gform_pushover_ui_injector' ), 998 );
		add_filter( 'gform_notification_settings_fields', array( $this, 'gform_pushover_notification_settings_fields'), 10, 3 );
		add_filter( 'gform_pre_notification_save', array( $this, 'gform_pushover_notification_setting_save'), 10, 2 );
	}
	
	public function initialize_api() {
		$push = new Pushover();
		if ( $push )
			return true;
	}
	public function add_pushover_notification_service( $services ) {
		if ( $this->initialize_api() ) {
			$services['pushover'] = array(
				'label' => esc_html__( 'Pushover', 'gform_pushover' ),
				'image' => $this->get_base_url() . '/img/pushover-logo.svg',
			);
		}
		return $services;
	}
	
	public function maybe_send_pushover_notification( $email, $message_format, $notification ) {
		if ( rgar( $notification, 'service' ) !== 'pushover' || !$this->initialize_api() ) {
			return $email;
		}
		if ( $email['abort_email'] ) {
			$this->log_debug( __METHOD__ . '(): Not sending email because the notification has already been aborted by another Add-On.' );
			return $email;
		}
		$result = $this->send_pushover_notification( $email, $message_format, $notification );
		if ( $result ) {
			$email['abort_email'] = true;
		}
		return $email;
	}
	
	public function send_pushover_notification( $email, $message_format, $notification ) {
		$push = new Pushover();
		$default_pushover_api = 'aobb4pgsx82twxpghpxpnqxxysrbut';
		$gravityformsaddon_gravityforms_pushover_settings = get_option('gravityformsaddon_gravityforms_pushover_settings');
		if ( isset($gravityformsaddon_gravityforms_pushover_settings['PushoverMode']) ) {
			if ( $gravityformsaddon_gravityforms_pushover_settings['PushoverMode'] == 'DefaultApplicationMode' ) {
				$push->setToken( $default_pushover_api );
			} else {
				$push->setToken( $gravityformsaddon_gravityforms_pushover_settings['PushoverSelfApplicationToken'] );
			}
		} else {
			$gravityformsaddon_gravityforms_pushover_settings['PushoverMode'] = 'DefaultApplicationMode';
			$push->setToken( $default_pushover_api );
		}
		$push->setUser( $notification['gform_pushover_user_token'] );
		$push->setHtml( 1 );
		$push->setTitle( $email['subject'] );
		$message = strip_tags($email['message'], '<strong><a>');
		$message = preg_replace("/&nbsp;/", "\n", $message);
		$message = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $message);
		$message = preg_replace('/^\s+|\s+$/m', '', $message);
		$message = preg_replace("/<strong>(.+?)<\/strong>/is", "<b>$1</b>", $message);
		$push->setMessage( $message );
		
		if ( $push->send() ) {
			return true;
		} else {
			return false;
		}
	}
	
	public function gform_pushover_ui_injector($hook) {
		if($hook != 'toplevel_page_gf_edit_forms') {
			return;
		}
		wp_enqueue_script( 'gf-pushover-add-on', plugins_url( 'gf-pushover-add-on.js', __FILE__ ), array( 'jquery' ), '0.2.7', true );
	}
	
	public function gform_pushover_notification_settings_fields( $fields, $notification, $form ) {
		$fields[0]['fields'][] = array(
			'type'				=> 'text',
			'name'				=> 'gform_pushover_user_token',
			'label'				=> 'Pushover User Key',
			'required'			=> true,
			'tooltip'			=> 'Get your Pushover User Key from <a href=\'https://pushover.net/\' target=\'_blank\'>https://pushover.net/</a>',
			'feedback_callback'	=> array( $this, 'gform_pushover_notification_validation' ),
		);
		return $fields;
	}
	
	public function gform_pushover_notification_validation( $value ) {
		if ( empty( $value ) ) {
			$is_valid = true;
		} elseif ( preg_match('/^\w{30}$/', $value ) ) {
			$is_valid = true;
		} else {
			$is_valid = false;
			GFCommon::add_error_message( esc_html( 'Please enter a valid Pushover User Key.' ) );
		}
		return $is_valid;
	}
	
	public function gform_pushover_notification_setting_save( $notification, $form ) {
		$notification['gform_pushover_user_token'] = rgpost( '_gform_setting_gform_pushover_user_token' );
		return $notification;
	}
	
	public function plugin_settings_fields() {
		return array(
			array(
				'title'  => esc_html__( 'Pushover Settings', 'gform_pushover' ),
				'fields' => array(
					array(
						'label'   => esc_html__( 'Pushover Mode', 'gform_pushover' ),
						'type'    => 'radio',
						'name'    => 'PushoverMode',
						'onclick'  => 'jQuery(this).closest("form").submit();',
						'choices' => array(
							array(
								'label' => esc_html__( 'Use the default application provided by Gravity Pushover (recommended)', 'gform_pushover' ),
								'tooltip' => esc_html__( 'The simplest case for most users.', 'gform_pushover' ),
								'value' => 'DefaultApplicationMode',
							),
							array(
								'label' => esc_html__( 'Use my own Pushover application', 'gform_pushover' ),
								'tooltip' => esc_html__( "For advanced users. Only if you have created a dedicated application on Pushover website.", 'gform_pushover' ),
								'value' => 'SelfApplicationMode',
							),
						),
						'default_value' => 'DefaultApplicationMode',
					),
					array(
						'label' => esc_html__( 'API Token', 'gform_pushover' ),
						'type' => 'text',
						'name' => 'PushoverSelfApplicationToken',
						'dependency' => array(
							'field'  => 'PushoverMode',
							'values' => 'SelfApplicationMode'
						),
						'class' => 'medium',
						'tooltip' => esc_html__( "Your API Token of your own application created on Pushover website.", 'gform_pushover' ),
						'feedback_callback' => array( $this, 'is_valid_pushover_token' ),
					),
				),
			),
		);
	}

	public function is_valid_pushover_token( $PushoverSelfApplicationToken ) {
		if ( preg_match('/^\w{30}$/', $PushoverSelfApplicationToken) == 1 ) {
			return true;
		} else {
			return false;
		}
	}
}