<?php
/**
 * This class handles the creation and rendering of the captcha form.
 *
 * @package uwc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'UWC_Captcha_Form_Render', false ) ) {
	/**
	 * UWC_Captcha_Form_Render Class.
	 */
	class UWC_Captcha_Form_Render {
		/**
		 * Hook.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'uwc_init' ) );
		}
		/**
		 * All hooks related to capctha goes here.
		 *
		 * @since 1.0.0
		 */
		public function uwc_init() {
			$uwc_setting_data = uwc_get_option();
			if ( uwc_is_this_location_checked( 'wp_login_form' ) ) {
				add_action( 'login_form', array( $this, 'uwc_login_form' ) );
			}
			if ( uwc_is_this_location_checked( 'ld_login_form' ) ) {
				add_filter( 'login_form_middle', array( $this, 'uwc_login_form_middle' ), 10, 2 );
			}
			if ( uwc_is_this_location_checked( 'wp_registration_form' ) ) {
				add_action( 'register_form', array( $this, 'uwc_register_form' ) );
			}
			if ( uwc_is_this_location_checked( 'wp_reset_passwordd_form' ) ) {
				add_action( 'lostpassword_form', array( $this, 'uwc_lostpassword_form' ) );
			}
			if ( uwc_is_this_location_checked( 'wp_comments_form' ) ) {
				add_action( 'comment_form_after_fields', array( $this, 'uwc_comment_form_after_fields' ) );
				add_action( 'comment_form_logged_in_after', array( $this, 'uwc_comment_form_after_fields' ) );
			}
			if ( uwc_is_this_location_checked( 'wc_login_form' ) ) {
				add_action( 'woocommerce_login_form', array( $this, 'uwc_login_form' ) );
			}
			if ( uwc_is_this_location_checked( 'wc_registration_form' ) ) {
				add_action( 'woocommerce_register_form', array( $this, 'uwc_login_form' ) );
			}
			if ( uwc_is_this_location_checked( 'wc_reset_passwordd_form' ) ) {
				add_action( 'woocommerce_lostpassword_form', array( $this, 'uwc_login_form' ) );
			}
			if ( uwc_is_this_location_checked( 'wc_checkout' ) ) {
				add_action( 'woocommerce_review_order_before_submit', array( $this, 'wc_checkout' ) );
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'uwc_wp_enqueue_scripts' ) );
			add_action( 'login_enqueue_scripts', array( $this, 'uwc_wp_enqueue_scripts' ) );
		}
		/**
		 * Returns captcha theme.
		 *
		 * @since 1.1.1
		 * @return string
		 */
		public function get_capctha_theme() {
			$uwc_setting_data = uwc_get_option();
			if ( isset( $uwc_setting_data['captcha_theme'] ) && 'dark' === $uwc_setting_data['captcha_theme'] ) {
				$captcha_theme = $uwc_setting_data['captcha_theme'];
			} else {
				$captcha_theme = 'light';
			}
			return $captcha_theme;
		}
		/**
		 * Display captcha box for checkout page.
		 *
		 * @since 1.1.1
		 */
		public function wc_checkout() {
			$intval_login_checkout = uniqid( 'interval_' );
			$uwc_setting_data      = uwc_get_option();
			?>
			<?php $this->get_captcha_html( true ); ?><br>
			<script>
				var uwcCaptcha = null;
				var $uwc = jQuery.noConflict();
				var captcha_method = '<?php echo esc_html( $uwc_setting_data['captcha_method'] ); ?>';
				$uwc(document).on('updated_checkout', function () {
					if ( typeof (grecaptcha.render) !== 'undefined' && uwcCaptcha === null ) {
						if ( captcha_method === 'google' ) {
							uwcCaptcha = grecaptcha.render('uwc-g-recaptcha-checkout', {'sitekey': '<?php echo esc_attr( $uwc_setting_data['google_site_key'] ); ?>', 'theme': '<?php echo esc_attr( $this->get_capctha_theme() ); ?>'});
						} else {
							uwcCaptcha = hcaptcha.render('uwc-h-captcha-checkout', {'sitekey': '<?php echo esc_attr( $uwc_setting_data['hcaptcha_site_key'] ); ?>', 'theme': '<?php echo esc_attr( $this->get_capctha_theme() ); ?>'});
						}
					}
				});
			</script>
			<?php
		}
		/**
		 * Display captcha box.
		 *
		 * @since 1.0.0
		 */
		public function uwc_woocommerce_register_form() {
			$this->get_captcha_html( true );
		}
		/**
		 * Display captcha box.
		 *
		 * @since 1.0.0
		 */
		public function uwc_login_form() {
			$this->get_captcha_html( true );
		}
		/**
		 * Display captcha box register form.
		 *
		 * @since 1.0.0
		 */
		public function uwc_register_form() {
			$this->get_captcha_html( true );
		}
		/**
		 * Display captcha box for lost password.
		 *
		 * @since 1.0.0
		 */
		public function uwc_lostpassword_form() {
			$this->get_captcha_html( true );
		}
		/**
		 * Display captcha box for comments form.
		 *
		 * @since 1.1.6
		 */
		public function uwc_comment_form_after_fields() {
			$this->get_captcha_html( true );
		}
		/**
		 * Display captcha box.
		 *
		 * @since 1.0.0
		 * @param string $content Content to display. Default empty.
		 * @param array  $args    Array of login form arguments.
		 * @return string
		 */
		public function uwc_login_form_middle( $content, $args ) {
			return $this->get_captcha_html();
		}
		/**
		 * Enqueue scripts here.
		 *
		 * @since 1.0.0
		 */
		public function uwc_wp_enqueue_scripts() {
			$uwc_setting_data = uwc_get_option();
			if ( isset( $uwc_setting_data['captcha_method'] ) && 'google' === $uwc_setting_data['captcha_method'] ) {
				wp_enqueue_script( 'wpuc_google_recaptcha', 'https://www.google.com/recaptcha/api.js', array(), '1.0.0', true );
			} else {
				wp_enqueue_script( 'wpuc_hcaptcha', 'https://hcaptcha.com/1/api.js', array(), '1.0.0', true );
			}
			$wpuc_css_ver = gmdate( 'ymd-Gis', filemtime( UWC_PLUGIN_PATH . '/assets/css/uwc-style.css' ) );
			wp_enqueue_style( 'wpuc_hcaptcha_css', UWC_PLUGIN_URL . '/assets/css/uwc-style.css', array(), $wpuc_css_ver );
		}
		/**
		 * Creating captcha form here.
		 *
		 * @since 1.0.0
		 * @param bool $echo Wheteher to display the content or simply retun. Default return.
		 * @return string
		 */
		public function get_captcha_html( $echo = false ) {
			$uwc_setting_data = uwc_get_option();
			$html             = '';
			if ( isset( $uwc_setting_data['captcha_method'] ) && ! empty( $uwc_setting_data['google_site_key'] ) && 'google' === $uwc_setting_data['captcha_method'] ) {
				$html = sprintf(
					'<div class="g-recaptcha" id="uwc-g-recaptcha-checkout" data-sitekey="%1$s" data-theme="%2$s"></div>',
					esc_attr( $uwc_setting_data['google_site_key'] ),
					esc_attr( $this->get_capctha_theme() )
				);
			} elseif ( isset( $uwc_setting_data['captcha_method'] ) && ! empty( $uwc_setting_data['hcaptcha_site_key'] ) && 'hcaptcha' === $uwc_setting_data['captcha_method'] ) {
				$html = sprintf(
					'<div class="h-captcha" id="uwc-h-captcha-checkout" data-sitekey="%1$s" data-theme="%2$s" style="display: block"></div>',
					esc_attr( $uwc_setting_data['hcaptcha_site_key'] ),
					esc_attr( $this->get_capctha_theme() )
				);
			}
			if ( true === $echo ) {
				echo $html; // @codingStandardsIgnoreLine.
			}
			return $html;
		}
	}
	new UWC_Captcha_Form_Render();
}
