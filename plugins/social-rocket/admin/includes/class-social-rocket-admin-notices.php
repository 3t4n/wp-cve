<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Class.
 */
class Social_Rocket_Admin_Notices {

	/**
	 * Stores notices.
	 * @var array
	 */
	private static $notices = array();

	/**
	 * Array of notices - name => callback.
	 * @var array
	 */
	private static $core_notices = array();

	/**
	 * Constructor.
	 */
	public static function init() {
		
		self::$notices = get_option( 'social_rocket_admin_notices', array() );

		add_action( 'switch_theme', array( __CLASS__, 'reset_admin_notices' ) );
		add_action( 'social_rocket_activated', array( __CLASS__, 'reset_admin_notices' ) );
		add_action( 'wp_loaded', array( __CLASS__, 'hide_notices' ) );
		add_action( 'shutdown', array( __CLASS__, 'store_notices' ), 999 );

		if ( current_user_can( 'manage_options' ) ) {
			add_action( 'admin_print_styles', array( __CLASS__, 'add_notices' ) );
		}
		
		add_action( 'wp_ajax_social_rocket_hide_notice', array( __CLASS__, 'hide_notices_ajax' ) );
		
	}
	

	/**
	 * Store notices to DB
	 */
	public static function store_notices() {
		update_option( 'social_rocket_admin_notices', self::get_notices() );
	}
	

	/**
	 * Get notices
	 * @return array
	 */
	public static function get_notices() {
		return self::$notices;
	}
	

	/**
	 * Remove all notices.
	 */
	public static function remove_all_notices() {
		self::$notices = array();
	}
	

	/**
	 * Reset notices for themes when switched or a new version of Social Rocket is installed.
	 */
	public static function reset_admin_notices() {
		// nothing yet to do here...
	}
	

	/**
	 * Show a notice.
	 * @param string $name
	 */
	public static function add_notice( $name ) {
		self::$notices = array_unique( array_merge( self::get_notices(), array( $name ) ) );
	}
	

	/**
	 * Remove a notice from being displayed.
	 * @param  string $name
	 */
	public static function remove_notice( $name ) {
		self::$notices = array_diff( self::get_notices(), array( $name ) );
		delete_option( 'sr_admin_notice_' . $name );
		delete_transient( 'sr_hide_' . $name . '_notice' );
	}
	

	/**
	 * See if a notice is being shown.
	 * @param  string  $name
	 * @return boolean
	 */
	public static function has_notice( $name ) {
		return in_array( $name, self::get_notices() );
	}
	

	/**
	 * Hide a notice if the GET variable is set.
	 */
	public static function hide_notices() {
		if ( isset( $_GET['social-rocket-hide-notice'] ) && isset( $_GET['_social_rocket_notice_nonce'] ) ) {
			if ( ! wp_verify_nonce( $_GET['_social_rocket_notice_nonce'], 'social_rocket_hide_notices_nonce' ) ) {
				wp_die( __( 'Action failed. Please refresh the page and retry.', 'social-rocket' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'Cheatin&#8217; huh?', 'social-rocket' ) );
			}
			
			$hide_notice = sanitize_text_field( $_GET['social-rocket-hide-notice'] );

			if ( isset( $_GET['social-rocket-dismiss'] ) && (int)$_GET['social-rocket-dismiss'] === 1 ) {
				
				update_option( 'sr_admin_notice_' . $hide_notice, '' );
				
			} else {
			
				self::remove_notice( $hide_notice );
				
			}
			
			if ( isset( $_GET['social-rocket-hide-transient'] ) && (int)$_GET['social-rocket-hide-transient'] > 0 ) {
				set_transient( 'sr_hide_' . $hide_notice . '_notice', 1, intval( $_GET['social-rocket-hide-transient'] ) );
			}
			
			do_action( 'social_rocket_hide_' . $hide_notice . '_notice' );
		}
	}
	
	
	/**
	 * Hide a notice for AJAX requests
	 */
	public static function hide_notices_ajax() {
		if ( isset( $_POST['social-rocket-hide-notice'] ) && isset( $_POST['_social_rocket_notice_nonce'] ) ) {
		
			$notice = sanitize_text_field( $_POST['social-rocket-hide-notice'] );
			
			if ( ! wp_verify_nonce( $_POST['_social_rocket_notice_nonce'], 'sr_admin_notice_'.$notice.'_nonce' ) ) {
				wp_die( __( 'Action failed. Please refresh the page and retry.', 'social-rocket' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'Cheatin&#8217; huh?', 'social-rocket' ) );
			}

			if ( isset( $_POST['social-rocket-dismiss'] ) && (int)$_POST['social-rocket-dismiss'] === 1 ) {
				
				update_option( 'sr_admin_notice_' . $notice, '' );
				
			} else {
			
				self::remove_notice( $notice );
				
			}
			
			if ( isset( $_POST['social-rocket-hide-transient'] ) && (int)$_POST['social-rocket-hide-transient'] > 0 ) {
				set_transient( 'sr_hide_' . $notice . '_notice', 1, intval( $_POST['social-rocket-hide-transient'] ) );
			}
			
			do_action( 'social_rocket_hide_' . $notice . '_notice' );
			
			wp_send_json( array( 'status' => 'success' ) );
		}
	}
	

	/**
	 * Add notices + styles if needed.
	 */
	public static function add_notices() {
	
		$notices = self::get_notices();
		
		if ( ! empty( $notices ) ) {
			foreach ( $notices as $notice ) {
				if ( ! empty( self::$core_notices[ $notice ] ) && apply_filters( 'social_rocket_show_admin_notice', true, $notice ) ) {
					add_action( 'admin_notices', array( __CLASS__, self::$core_notices[ $notice ] ) );
				} else {
					add_action( 'admin_notices', array( __CLASS__, 'output_custom_notices' ) );
				}
			}
		}
	}
	

	/**
	 * Add a custom notice.
	 * @param string $name
	 * @param string $notice_html
	 */
	public static function add_custom_notice( $name, $data ) {
		self::add_notice( $name );
		update_option( 'sr_admin_notice_' . $name, $data );
	}
	

	/**
	 * Output any stored custom notices.
	 */
	public static function output_custom_notices() {
		$notices = self::get_notices();
		if ( ! empty( $notices ) ) {
			foreach ( $notices as $notice ) {
				if ( empty( self::$core_notices[ $notice ] ) ) {
					if ( ! get_transient( 'sr_hide_' . $notice . '_notice' ) ) {
						$data = get_option( 'sr_admin_notice_' . $notice );
						if ( is_array( $data ) ) {
							$class             = isset( $data['class'] ) ? $data['class'] : '';
							$content           = isset( $data['content'] ) ? $data['content'] : '';
							$dismissable       = isset( $data['dismissable'] ) ? $data['dismissable'] : true;
							$dismiss_permanent = isset( $data['dismiss_permanent'] ) ? $data['dismiss_permanent'] : false;
							$dismiss_transient = isset( $data['dismiss_transient'] ) ? $data['dismiss_transient'] : false;
							echo '<div class="notice '.$class.' social-rocket-message" id="sr_admin_notice_'.$notice.'">
								'.( $dismissable ? '<a class="social-rocket-message-close notice-dismiss" href="' . esc_url( wp_nonce_url( add_query_arg( array( 'social-rocket-hide-notice' => $notice ) ), 'social_rocket_hide_notices_nonce', '_social_rocket_notice_nonce' ) ) . '">' . __( 'Dismiss', 'social-rocket' ) . '</a>' : '' ).'
								'.$content.'
							</div>';
							if ( $dismissable ) {
								echo '<script type="text/javascript">
									jQuery(document).ready(function($){
										$("#sr_admin_notice_'.$notice.' .notice-dismiss").on("click",function(e){
											e.preventDefault();
											var data = {
												action: "social_rocket_hide_notice",
												"_social_rocket_notice_nonce": "'.wp_create_nonce( 'sr_admin_notice_'.$notice.'_nonce' ).'",
												'.($dismiss_permanent ? '"social-rocket-dismiss": "'.$dismiss_permanent.'",' : '').'
												'.($dismiss_transient ? '"social-rocket-hide-transient": "'.$dismiss_transient.'",' : '').'
												"social-rocket-hide-notice": "'.$notice.'"
											};
											$.post(ajaxurl, data, function(response) {
												if ( response.status === "success" ) {
													$("#sr_admin_notice_'.$notice.'").fadeOut();
												}
											});
											return false;
										});
									});
								</script>';
							}
						} elseif ( $data ) {
							echo $data;
						}
					}
				}
			}
		}
	}
	
}

// Call the class
Social_Rocket_Admin_Notices::init();
