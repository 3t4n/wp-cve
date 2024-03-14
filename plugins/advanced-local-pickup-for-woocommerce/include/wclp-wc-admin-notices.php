<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_ALP_Admin_Notices_Under_WC_Admin {

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		$this->init();	
	}
	
	/**
	 * Get the class instance
	 *
	 * @return WC_ALP_Admin_Notices_Under_WC_Admin
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	/*
	* init from parent mail class
	*/
	public function init() {	
		add_action( 'admin_init', array( $this, 'admin_notices_for_alp_pro' ) );										
		add_action( 'alp_settings_admin_notice', array( $this, 'alp_settings_admin_notice' ) );
		add_action( 'alp_settings_admin_footer', array( $this, 'alp_settings_admin_footer' ) );

		add_action( 'admin_notices', array( $this, 'admin_notice_after_update' ) );		
		add_action('admin_init', array( $this, 'wplp_plugin_notice_ignore' ) );
	}

	public function admin_notices_for_alp_pro() {
		if ( isset( $_GET['alp-pro-settings-ignore-notice'] ) ) {
			if (isset($_GET['nonce']) && wp_verify_nonce($_GET['nonce'], 'alp_pro_dismiss_notice')) {
				set_transient( 'alp_settings_admin_notice_ignore', 'yes', 2592000 );
			}
		}
	}

	public function alp_settings_admin_notice() {
		
		$ignore = get_transient( 'alp_settings_admin_notice_ignore' );
		if ( 'yes' == $ignore ) {
			return;
		}
		include 'views/admin_message_panel.php';
	}

	public function alp_settings_admin_footer() {
		include 'views/admin_footer_panel.php';
	}

	/*
	* Display admin notice on plugin install or update
	*/
	public function admin_notice_after_update() { 		
		
		if ( get_option('wplp_review_notice_ignore') ) {
			return;
		}
		
		// Add nonce to the dismissable URL
		$nonce = wp_create_nonce('wplp_dismiss_notice');
		$dismissable_url = esc_url(add_query_arg(['wplp-review-ignore-notice' => 'true', 'nonce' => $nonce]));
		
		?>
		<style>		
		.wp-core-ui .notice.wplp-dismissable-notice {
			position: relative;
			padding-right: 38px;
		}
		.wp-core-ui .notice.wplp-dismissable-notice a.notice-dismiss {
			padding: 9px;
			text-decoration: none;
		} 
		.wp-core-ui .button-primary.btn_review_notice {
			background: transparent;
			color: #f1a451;
			border-color: #f1a451;
			text-transform: uppercase;
			padding: 0 11px;
			font-size: 12px;
			height: 30px;
			line-height: 28px;
			margin: 5px 0 15px;
		}
		</style>	
		<div class="notice updated notice-success wplp-dismissable-notice">
			<a href="<?php echo esc_url($dismissable_url); ?>" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>
			<p>Hey, I noticed you are using the Advanced Local Pickup Plugin - that’s awesome!</br>Could you please do me a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?</p>
			<p>Eran Shor</br>Founder of zorem</p>
			<a class="button-primary btn_review_notice" target="blank" href="https://wordpress.org/support/plugin/advanced-local-pickup-for-woocommerce/reviews/#new-post">Ok, you deserve it</a>
			<a class="button-primary btn_review_notice" href="<?php echo esc_url($dismissable_url); ?>">Nope, maybe later</a>
			<a class="button-primary btn_review_notice" href="<?php echo esc_url($dismissable_url); ?>">I already did</a>
		</div>
	<?php 		
	}	


	/*
	* Hide admin notice on dismiss of ignore-notice
	*/
	public function wplp_plugin_notice_ignore() {
		if (isset($_GET['wplp-review-ignore-notice'])) {
			if (isset($_GET['nonce']) && wp_verify_nonce($_GET['nonce'], 'wplp_dismiss_notice')) {
				update_option( 'wplp_review_notice_ignore', 'true' );
			}
		}
	}
			
}

/**
 * Returns an instance of WC_ALP_Admin_Notices_Under_WC_Admin.
 *
 * @since 1.6.5
 * @version 1.6.5
 *
 * @return WC_ALP_Admin_Notices_Under_WC_Admin
*/
function WC_ALP_Admin_Notices_Under_WC_Admin() {
	static $instance;

	if ( ! isset( $instance ) ) {		
		$instance = new WC_ALP_Admin_Notices_Under_WC_Admin();
	}

	return $instance;
}

/**
 * Register this class globally.
 *
 * Backward compatibility.
*/
WC_ALP_Admin_Notices_Under_WC_Admin();
