<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( "Payamito_Direct_Send" ) ) {
	class Payamito_Direct_Send
	{
		protected static $instance = null;

		public $separators;
		public $slug = "direct_send";

		// If the single instance hasn't been set, set it now.
		public static function get_instance()
		{
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			self::$instance->Settings();

			return self::$instance;
		}

		public function __construct()
		{
			$this->init();
		}

		private function init()
		{
			add_action( 'admin_init', [ $this, 'settings' ] );
			if ( ! self::IsDirectSendPage() ) {
				return;
			}

			$this->separators = [
				"c" => __( "Comma", "payamito" ),
				"s" => __( "Space", "payamito" ),
				"n" => __( "Break line ", "payamito" ),
			];
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
			add_action( 'wp_ajax_send', [ $this, 'send' ] );
			! isset( $_SESSION['payamitoDirectSend'] ) ? $_SESSION['payamitoDirectSend'] = null : '';
		}

		public function send()
		{
			$type          = payamito_to_english_number( sanitize_text_field( $_POST['type'] ) );
			$mobileNumbers = array_map( "sanitize_text_field", $_POST['mobileNumbers'] );
			$mobileNumbers = array_map( "payamito_to_english_number", $_POST['mobileNumbers'] );
			// $mobileNumbers = array_map("payamito_verify_moblie_number", $_POST['mobileNumbers']);
			$mobileNumbers = array_unique( $mobileNumbers );
			if ( count( $mobileNumbers ) === 0 ) {
				self::ajax_response( 'error', __( "Please enter mobile numbers", "payamito" ), __( "!Error", "payamito" ) );
			}
			switch ( $type ) {
				case '1':
					$patterns = [];
					foreach ( $_POST['value'] as $pattern ) {
						array_push( $patterns, array_map( "sanitize_text_field", $pattern ) );
					}
					if ( count( $pattern ) === 0 ) {
						self::ajax_response( 'error', __( "Please make pattern first", "payamito" ), __( "!Error", "payamito" ) );
					}
					$patternID = payamito_to_english_number( sanitize_text_field( $_POST['patternID'] ) );
					if ( strlen( $patternID ) < 4 or is_null( $patternID ) ) {
						self::ajax_response( 'error', __( "Please fill a correct pattern ID", "payamito" ), __( "!Error", "payamito" ) );
					}
					$this->patternSend( $mobileNumbers, $patterns, $patternID );
					break;
				case '2':
					$senderNumber = payamito_to_english_number( sanitize_text_field( $_POST['senderNumber'] ) );
					if ( empty( $senderNumber ) ) {
						return self::ajax_response( 'error', __( "Please fill sender number", "payamito" ), __( "!Error", "payamito" ) );
					}
					$message = sanitize_textarea_field( $_POST['value'] );
					$this->textSend( $mobileNumbers, $message, $senderNumber );
					break;
			}
		}

		private function patternSend( $mobileNumbers, $patterns, $patternID )
		{
			$preparedPattern = [];
			foreach ( $patterns as $pattern ) {
				$preparedPattern[ $pattern[0] ] = $pattern[1];
			}

			foreach ( $mobileNumbers as $mobile ) {
				$result = payamito_send_pattern( $mobile, $preparedPattern, $patternID, $this->slug );
				if ( $result > 10000 ) {
					$result = __( "Sent successfully", 'payamito' );
				} else {
					$result = payamito_code_to_message( $result );
				}
				wp_send_json( [ 'logShow' => '1', 'result' => $result ] );
				die;
			}
		}

		private function textSend( $mobileNumbers, $text, $senderNumber )
		{
			$result = payamito_group_send( $mobileNumbers, $text, $senderNumber, $this->slug );
			if ( $result != 1 ) {
				$m = __( "Sending canceled because %s", "payamito" );
				self::ajax_response( 'error', sprintf( $m, payamito_code_to_message( $result ) ), __( "!Error", "payamito" ), true );
			} else {
				wp_send_json( [ 'logShow' => '1', 'result' => payamito_code_to_message( $result ) ] );
			}
		}

		public static function ajax_response( $type = 'error', string $message = "", $title = "", $exit = false )
		{
			wp_send_json( [ 'type' => $type, 'message' => $message, "title" => $title, 'exit' => $exit ] );
			die;
		}

		public function settings()
		{
			add_filter( 'payamito_add_section', [ $this, 'registerSettings' ], 1 );
		}

		public function registerSettings( $section )
		{
			$awesome_support_sms_settings = [
				'title' => esc_html__( 'Direct Sending', 'payamito' ),

				'fields' => [
					[
						'id'    => 'payamito_direct_mobile_numbers',
						'title' => esc_html__( 'Mobile numbers', 'payamito' ),
						'help'  => esc_html__( '', 'payamito' ),
						'type'  => 'textarea',
					],
					[
						'id'      => 'payamito_direct_send_separator',
						'type'    => 'select',
						'title'   => esc_html__( "Separator", "payamito" ),
						'desc'    => esc_html__( 'With one of the options separate your mobile numbers', 'payamito' ),
						'options' => $this->separators,
					],
					[
						'id'    => 'payamito_direct_send_active_pattern',
						'type'  => 'switcher',
						'title' => payamito_dynamic_text( 'pattern_active_title' ),
						'desc'  => payamito_dynamic_text( 'pattern_active_desc' ),
						'help'  => payamito_dynamic_text( 'pattern_active_help' ),
					],
					[
						'id'         => 'payamito_direct_send_pattern_id',
						'type'       => 'text',
						'title'      => payamito_dynamic_text( 'pattern_ID_title' ),
						'desc'       => payamito_dynamic_text( 'pattern_ID_desc' ),
						'help'       => payamito_dynamic_text( 'pattern_ID_help' ),
						'dependency' => [ "payamito_direct_send_active_pattern", '==', 'true' ],
					],
					[
						'id'         => 'payamito_direct_send_repeater',
						'type'       => 'repeater',
						'title'      => payamito_dynamic_text( 'pattern_Variable_title' ),
						'desc'       => payamito_dynamic_text( 'pattern_Variable_desc' ),
						'help'       => payamito_dynamic_text( 'pattern_Variable_help' ),
						'dependency' => [ "payamito_direct_send_active_pattern", '==', 'true' ],
						'fields'     => [
							[
								'id'   => 'payamito_direct_send_0',
								'type' => 'text',

								'default' => '0',
							],
							[
								'id'          => 'payamito_direct_send_1',
								'type'        => 'text',
								'placeholder' => esc_html__( "Your tag", "payamito" ),
							],
						],
					],

					[
						'id'         => 'payamito_direct_message',
						'title'      => esc_html__( 'Message', 'payamito' ),
						'desc'       => esc_html__( '', 'payamito' ),
						'help'       => esc_html__( '', 'payamito' ),
						'dependency' => [ "payamito_direct_send_active_pattern", '!=', 'true' ],
						'type'       => 'textarea',
					],
					[
						'id'          => 'payamito_direct_sender_number',
						'type'        => 'text',
						'title'       => esc_html__( 'Sender number', 'payamito' ),
						'placeholder' => esc_html__( "10002020", "payamito" ),
						'dependency'  => [ "payamito_direct_send_active_pattern", '!=', 'true' ],
					],
					[
						'type'     => 'callback',
						'function' => [ $this, 'includeHtml' ],
					],
				],
			];
			if ( ! is_null( $section ) ) {
				array_push( $section, $awesome_support_sms_settings );
			}

			return $section;
		}

		public function includeHtml()
		{
			$separators = $this->separators;
			$this->include( __DIR__ . '/html-send.php' );
		}

		public function include( $path )
		{
			if ( is_file( $path ) ) {
				require_once $path;
			}
		}

		private static function IsDirectSendPage()
		{
			return ( ! ( isset( $_GET['page'] ) && $_GET['page'] !== self::getPrefix() ) );
		}

		public static function getPrefix()
		{
			return "payamito";
		}

		public function enqueue_scripts()
		{
			wp_enqueue_script( 'payamitio-sweetalert-js', PAYAMITO_URL . "/assets/js/sweetalert.js", [], false, true );
			wp_enqueue_style( 'payamitio-sweetalert-css', PAYAMITO_URL . "/assets/css/sweetalert.css" );
			wp_enqueue_script( 'payamitio-loading-bar-js', PAYAMITO_URL . "/assets/js/loading-bar.min.js", [], false, true );
			wp_enqueue_style( 'payamitio-loading-bar-css', PAYAMITO_URL . "/assets/css/loading-bar.min.css" );
		}
	}
}

add_action( 'payamito_loaded', [ 'Payamito_Direct_Send', 'get_instance' ] );
