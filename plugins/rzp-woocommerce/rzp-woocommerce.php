<?php
/**
 * Plugin Name: Razorpay Payment Links for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/rzp-woocommerce/
 * Description: The easiest and most secure solution to collect payments with WooCommerce. Allow customers to securely pay via Razorpay (Credit/Debit Cards, NetBanking, UPI, Wallets, QR Code).
 * Version: 1.2.2
 * Author: KnitPay
 * Author URI: https://www.knitpay.org/
 * License: GPLv3
 * Text Domain: rzp-woocommerce
 * Domain Path: /languages
 * WC requires at least: 2.4
 * WC tested up to: 8.6
 * 
 * Razorpay Payment Links for WooCommerce is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Razorpay Payment Links for WooCommerce is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Razorpay Payment Links for WooCommerce plugin. If not, see <http://www.gnu.org/licenses/>.
 * 
 * @category WooCommerce
 * @package  Razorpay Payment Links for WooCommerce
 * @author   KnitPay
 * @license  http://www.gnu.org/licenses/ GNU General Public License
 * @link     https://wordpress.org/plugins/rzp-woocommerce/
 *
 */

 // If this file is called directly, abort!!!
defined( 'ABSPATH' ) || exit;

/**
 * RZPWC class.
 *
 * @class Main class of the plugin.
 */
final class RZPWC {

    /**
     * Plugin version.
     *
     * @var string
     */
    public $version = '1.2.1';

    /**
     * Minimum version of WordPress required to run RZPWC.
     *
     * @var string
     */
    private $wordpress_version = '4.6';

    /**
     * Minimum version of PHP required to run RZPWC.
     *
     * @var string
     */
    private $php_version = '5.6';

    /**
     * Hold install error messages.
     *
     * @var bool
     */
    private $messages = [];

    /**
     * The single instance of the class.
     *
     * @var RZPWC
     */
    protected static $instance = null;

    /**
     * Retrieve main RZPWC instance.
     *
     * Ensure only one instance is loaded or can be loaded.
     *
     * @see rzpwc()
     * @return RZPWC
     */
    public static function get() {
        if ( is_null( self::$instance ) && ! ( self::$instance instanceof RZPWC ) ) {
            self::$instance = new RZPWC();
            self::$instance->setup();
        }

        return self::$instance;
    }

    /**
     * Instantiate the plugin.
     */
    private function setup() {
        // Define plugin constants.
        $this->define_constants();

        if ( ! $this->is_requirements_meet() ) {
            return;
        }

        // Instantiate services.
        $this->instantiate();

        // Loaded action.
        do_action( 'rzpwc_loaded' );
    }

    /**
     * Check that the WordPress and PHP setup meets the plugin requirements.
     *
     * @return bool
     */
    private function is_requirements_meet() {

        // Check WordPress version.
        if ( version_compare( get_bloginfo( 'version' ), $this->wordpress_version, '<' ) ) {
            /* translators: WordPress Version */
            $this->messages[] = sprintf( esc_html__( 'You are using the outdated WordPress, please update it to version %s or higher.', 'rzp-woocommerce' ), $this->wordpress_version );
        }

        // Check PHP version.
        if ( version_compare( phpversion(), $this->php_version, '<' ) ) {
            /* translators: PHP Version */
            $this->messages[] = sprintf( esc_html__( 'Razorpay Payment Links for WooCommerce requires PHP version %s or above. Please update PHP to run this plugin.', 'rzp-woocommerce' ), $this->php_version );
        }

        if ( empty( $this->messages ) ) {
            return true;
        }

        // Auto-deactivate plugin.
        add_action( 'admin_init', [ $this, 'auto_deactivate' ] );
        add_action( 'admin_notices', [ $this, 'activation_error' ] );

        return false;
    }

    /**
     * Auto-deactivate plugin if requirements are not met, and display a notice.
     */
    public function auto_deactivate() {
        deactivate_plugins( RZPWC_BASENAME );
        if ( isset( $_GET['activate'] ) ) { // phpcs:ignore
            unset( $_GET['activate'] ); // phpcs:ignore
        }
    }

    /**
     * Error notice on plugin activation.
     */
    public function activation_error() {
        ?>
        <div class="notice rzpwc-notice notice-error">
            <p>
                <?php echo join( '<br>', $this->messages ); // phpcs:ignore ?>
            </p>
        </div>
        <?php
    }

    /**
     * Define the plugin constants.
     */
    private function define_constants() {
        define( 'RZPWC_VERSION', $this->version );
        define( 'RZPWC_FILE', __FILE__ );
        define( 'RZPWC_PATH', dirname( RZPWC_FILE ) . '/' );
        define( 'RZPWC_URL', plugins_url( '', RZPWC_FILE ) . '/' );
        define( 'RZPWC_BASENAME', plugin_basename( RZPWC_FILE ) );
    }

    /**
     * Instantiate services.
     */
    private function instantiate() {
        // Activation hook.
        register_activation_hook( RZPWC_FILE, 
            function () {
                set_transient( 'rzpwc-admin-notice-on-activation', true, 5 );
            } 
        );

        // Deactivation hook.
        register_deactivation_hook( RZPWC_FILE, 
            function () {
                delete_option( 'rzpwc_plugin_dismiss_rating_notice' );
                delete_option( 'rzpwc_plugin_no_thanks_rating_notice' );
                delete_option( 'rzpwc_plugin_installed_time' );
                delete_option( 'rzpwc_plugin_dismiss_donate_notice' );
                delete_option( 'rzpwc_plugin_no_thanks_donate_notice' );
                delete_option( 'rzpwc_plugin_dismissed_time' );
                delete_option( 'rzpwc_plugin_dismissed_time_donate' );
            } 
        );

        // Initialize the action and filter hooks.
		$this->init_actions();
    }

    /**
	 * Initialize WordPress action and filter hooks.
	 */
	private function init_actions() {
        // Make sure it is loaded before setup_modules and load_modules.
        add_action( 'plugins_loaded', [ $this, 'localization_setup' ], 9 );

        // Add plugin action links.
        add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
        add_filter( 'plugin_action_links_' . RZPWC_BASENAME, [ $this, 'action_links' ] );

        // Declaring HPOS compatibility.
        add_action( 'before_woocommerce_init', [ $this, 'declare_compatibility' ] );

        // Register payment gateway.
        add_filter( 'woocommerce_payment_gateways', [ $this, 'register_gateway' ] );

        // Load payment gateway.
        add_action( 'plugins_loaded', [ $this, 'load_gateway' ] );
        add_action( 'woocommerce_blocks_loaded', array( $this, 'block_support' ) );
        
        // Load admin notices.
        add_action( 'admin_notices', [ $this, 'admin_notice' ] );
		add_action( 'admin_init', [ $this, 'dismiss_notice' ] );
    }

    /**
	 * Initialize plugin for localization.
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'rzp-woocommerce', false, dirname( RZPWC_BASENAME ) . '/languages' ); 
	}

    /**
	 * Add extra links as row meta on the plugin screen.
	 *
	 * @param  mixed $links Plugin Row Meta.
	 * @param  mixed $file  Plugin Base file.
	 * @return array
	 */
	public function plugin_row_meta( $links, $file ) {
		if ( RZPWC_BASENAME !== $file ) {
			return $links;
		}

		$more = [
            '<a href="https://wordpress.org/support/plugin/rzp-woocommerce/" target="_blank">' . esc_html__( 'Support', 'rzp-woocommerce' ) . '</a>',
            '<a href="https://wordpress.org/plugins/rzp-woocommerce/#faq" target="_blank">' . esc_html__( 'FAQ', 'rzp-woocommerce' ) . '</a>',
            //'<a href="https://www.sayandatta.co.in/donate" target="_blank">' . esc_html__( 'Donate', 'rzp-woocommerce' ) . '</a>',
        ];

		return array_merge( $links, $more );
	}

    /**
	 * Show action links on the plugin screen.
	 *
	 * @param  mixed $links Plugin Action links.
	 * @return array
	 */
    public function action_links( $links ) {
        $links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc-razorpay' ) . '">' . esc_html__( 'Settings', 'rzp-woocommerce' ) . '</a>';
        
        return $links;
    }

    /**
	 * Declaring HPOS compatibility
	 */
    public function declare_compatibility() {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', RZPWC_FILE, true );
        }
    }

    /**
	 * Register WooCommerce gateway.
	 *
	 * @param  mixed $links Plugin Action links.
	 * @return array
	 */
    public function register_gateway( $gateways ) {
        $gateways[] = 'RZP_WC_Payment_Gateway'; // class name

        return $gateways;
    }

    /**
	 * Load Payment Gateway.
	 */
    public function load_gateway() {
        if ( class_exists( '\WC_Payment_Gateway' ) ) {
            require_once RZPWC_PATH . 'includes/class-payment.php';
        }
    }

    /**
	 * Registers WooCommerce Blocks integration.
	 */
	public function block_support() {
		if ( class_exists( '\Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
            require_once RZPWC_PATH . 'includes/blocks/class-blocks-support.php';
			add_action(
				'woocommerce_blocks_payment_method_type_registration',
				function( \Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
					$payment_method_registry->register( new RZP_WC_Payment_Gateway_Blocks_Support() );
				}
			);
		}
	}

	/**
	 * Show internal admin notices.
	 */
	public function admin_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

        // Check transient, if available display notice
        if ( get_transient( 'rzpwc-admin-notice-on-activation' ) ) { ?>
            <div class="notice notice-success">
                <p><strong><?php 
                /* translators: %s: Plugin Details. 1. Plugin Name, 2. Plugin Version, 3. Plugin Settings */
                printf( wp_kses_post( __( 'Thanks for installing %1$s v%2$s plugin. Click <a href="%3$s">here</a> to configure plugin settings.', 'rzp-woocommerce' ) ), 'Razorpay Payment Links for WooCommerce', esc_html( RZPWC_VERSION ), esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc-razorpay' ) ) ); ?></strong></p>
            </div> <?php
            delete_transient( 'rzpwc-admin-notice-on-activation' );
        }

        $show_rating = true;
        if ( $this->calculate_time() > strtotime( '-7 days' )
            || '1' === get_option( 'rzpwc_plugin_dismiss_rating_notice' )
            || apply_filters( 'rzpwc_hide_sticky_rating_notice', false ) ) {
            $show_rating = false;
        }
    
        if ( $show_rating ) {
            $dismiss = wp_nonce_url( add_query_arg( 'rzpwc_notice_action', 'dismiss_rating' ), 'rzpwc_notice_nonce' );
            $no_thanks = wp_nonce_url( add_query_arg( 'rzpwc_notice_action', 'no_thanks_rating' ), 'rzpwc_notice_nonce' ); ?>
            
            <div class="notice notice-success">
                <p><?php echo wp_kses_post( 'Hey, I noticed you\'ve been using Razorpay Payment Links for WooCommerce for more than 1 week – that’s awesome! Could you please do me a BIG favor and give it a <strong>5-star</strong> rating on WordPress? Just to help us spread the word and boost my motivation.', 'rzp-woocommerce' ); ?></p>
                <p><a href="https://wordpress.org/support/plugin/rzp-woocommerce/reviews/?filter=5#new-post" target="_blank" class="button button-secondary" rel="noopener"><?php esc_html_e( 'Ok, you deserve it', 'rzp-woocommerce' ); ?></a>&nbsp;
                <a href="<?php echo esc_url( $dismiss ); ?>" class="already-did"><strong><?php esc_html_e( 'I already did', 'rzp-woocommerce' ); ?></strong></a>&nbsp;<strong>|</strong>
                <a href="<?php echo esc_url( $no_thanks ); ?>" class="later"><strong><?php esc_html_e( 'Nope&#44; maybe later', 'rzp-woocommerce' ); ?></strong></a></p>
            </div>
            <?php
        }
    
        $show_donate = false;
        if ( $this->calculate_time() > strtotime( '-10 days' )
            || '1' === get_option( 'rzpwc_plugin_dismiss_donate_notice' )
            || apply_filters( 'rzpwc_hide_sticky_donate_notice', false ) ) {
            $show_donate = false;
        }
    
        if ( $show_donate ) {
            $dismiss = wp_nonce_url( add_query_arg( 'rzpwc_notice_action', 'dismiss_donate' ), 'rzpwc_notice_nonce' );
            $no_thanks = wp_nonce_url( add_query_arg( 'rzpwc_notice_action', 'no_thanks_donate' ), 'rzpwc_notice_nonce' ); ?>
            
            <div class="notice notice-success">
                <p><?php echo wp_kses_post( 'Hey, I noticed you\'ve been using Razorpay Payment Links for WooCommerce for more than 2 week – that’s awesome! If you like Razorpay Payment Links for WooCommerce and you are satisfied with the plugin, isn’t that worth a coffee or two? Please consider donating. Donations help me to continue support and development of this free plugin! Thank you very much!', 'rzp-woocommerce' ); ?></p>
                <p><a href="https://www.sayandatta.co.in/donate" target="_blank" class="button button-secondary" rel="noopener"><?php esc_html_e( 'Donate Now', 'rzp-woocommerce' ); ?></a>&nbsp;
                <a href="<?php echo esc_url( $dismiss ); ?>" class="already-did"><strong><?php esc_html_e( 'I already donated', 'rzp-woocommerce' ); ?></strong></a>&nbsp;<strong>|</strong>
                <a href="<?php echo esc_url( $no_thanks ); ?>" class="later"><strong><?php esc_html_e( 'Nope&#44; maybe later', 'rzp-woocommerce' ); ?></strong></a></p>
            </div>
            <?php
        }
	}

	/**
	 * Dismiss admin notices.
	 */
	public function dismiss_notice() {
		// Check for Rating Notice
        if ( get_option( 'rzpwc_plugin_no_thanks_rating_notice' ) === '1'
            && get_option( 'rzpwc_plugin_dismissed_time' ) <= strtotime( '-10 days' ) ) {
            delete_option( 'rzpwc_plugin_dismiss_rating_notice' );
            delete_option( 'rzpwc_plugin_no_thanks_rating_notice' );
        }

        // Check for Donate Notice
        if ( get_option( 'rzpwc_plugin_no_thanks_donate_notice' ) === '1'
            && get_option( 'rzpwc_plugin_dismissed_time_donate' ) <= strtotime( '-14 days' ) ) {
            delete_option( 'rzpwc_plugin_dismiss_donate_notice' );
            delete_option( 'rzpwc_plugin_no_thanks_donate_notice' );
        }

        if ( ! isset( $_REQUEST['rzpwc_notice_action'] ) || empty( $_REQUEST['rzpwc_notice_action'] ) ) {
            return;
        }

        check_admin_referer( 'rzpwc_notice_nonce' );

        $notice = sanitize_text_field( $_REQUEST['rzpwc_notice_action'] );
        $notice = explode( '_', $notice );
        $notice_type = end( $notice );
        array_pop( $notice );
        $notice_action = join( '_', $notice );

        if ( 'dismiss' === $notice_action ) {
            update_option( 'rzpwc_plugin_dismiss_' . $notice_type . '_notice', '1' );
        }

        if ( 'no_thanks' === $notice_action ) {
            update_option( 'rzpwc_plugin_no_thanks_' . $notice_type . '_notice', '1' );
            update_option( 'rzpwc_plugin_dismiss_' . $notice_type . '_notice', '1' );
            if ( 'donate' === $notice_type ) {
                update_option( 'rzpwc_plugin_dismissed_time_donate', time() );
            } else {
                update_option( 'rzpwc_plugin_dismissed_time', time() );
            }
        }

        wp_safe_redirect( remove_query_arg( [ 'rzpwc_notice_action', '_wpnonce' ] ) );
        exit;
	}

	/**
	 * Calculate install time.
	 */
	private function calculate_time() {
		$installed_time = get_option( 'rzpwc_plugin_installed_time' );
        if ( ! $installed_time ) {
            $installed_time = time();
            update_option( 'rzpwc_plugin_installed_time', $installed_time );
        }
        return $installed_time;
	}
}

/**
 * Returns the main instance of RZPWC to prevent the need to use globals.
 *
 * @return RZPWC
 */
function rzpwc() {
    return RZPWC::get();
}

// Start it.
rzpwc();