<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that represents admin notices.
 *
 * @since 1.3.0
 * @version 1.3.0
 */
class WC_Metaps_Admin_Notices {
	/**
	 * Notices (array)
	 * @var array
	 */
	public $notices = array();

	/**
	 * Constructor
	 *
	 * @since 1.3.0
     * @version 1.3.0
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Allow this class and other classes to add slug keyed notices (to avoid duplication).
	 *
	 * @since 1.0.0
     * @version 1.0.0
	 */
	public function add_admin_notice( $slug, $class, $message, $dismissible = false ) {
		$this->notices[ $slug ] = array(
			'class'       => $class,
			'message'     => $message,
			'dismissible' => $dismissible,
		);
	}

	/**
	 * Display any notices we've collected thus far.
	 *
	 * @since 1.0.0
     * @version 1.0.0
	 */
	public function admin_notices() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		// Main Metaps payment method check.
		$this->metaps_check_environment();

		foreach ( (array) $this->notices as $notice_key => $notice ) {
			echo '<div class="' . esc_attr( $notice['class'] ) . '" style="position:relative;">';

			if ( $notice['dismissible'] ) {
				?>
				<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wc-metaps-hide-notice', $notice_key ), 'wc_paydesign_cs_hide_notices_nonce', '_wc_paydesign_cs_nonce' ) ); ?>" class="woocommerce-message-close notice-dismiss" style="position:relative;float:right;padding:9px 0 9px 9px;text-decoration:none;"></a>
				<?php
			}

			echo '<p>';
			echo wp_kses( $notice['message'], array( 'a' => array( 'href' => array(), 'target' => array() ) ) );
			echo '</p></div>';
		}
	}

	/**
	 * The backup sanity check, in case the plugin is activated in a weird way,
	 * or the environment changes after activation. Also handles upgrade routines.
	 *
	 * @since 1.0.0
     * @version 1.1.0
	 */
	public function metaps_check_environment() {
		$show_cs_notice = get_option( 'wc_metaps_cs_notice' );
		$options            = get_option( 'woocommerce_paydesign_cs_settings' );
		$setting_cs_lp      = isset( $options['setting_cs_lp'] ) ? $options['setting_cs_lp'] : '';
		$setting_cs_sm      = isset( $options['setting_cs_sm'] ) ? $options['setting_cs_sm'] : '';

		if ( isset( $options['enabled'] ) && 'yes' === $options['enabled'] ) {
            if ( $show_cs_notice != 'no' && $_GET['section'] == 'paydesign_cs') {
                // Show message if enabled and FORCE SSL is disabled and WordpressHTTPS plugin is not detected.
                if ( $setting_cs_lp == 'yes' && $setting_cs_sm == 'no' ) {
                    /* translators: 1) Wikipedia link */
                    $this->add_admin_notice( 'cs', 'notice notice-warning', __( 'If "Lawson/Ministop" is checked in the convenience store payment column, please also check "Seicomart". You cannot use "Seicomart" unless you check the box.', 'woo-paydesign' ) , false );
                }
            }
        }
	}
}

new WC_Metaps_Admin_Notices();
