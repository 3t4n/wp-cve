<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CWG_Instock_Google_Recaptcha' ) ) {

	class CWG_Instock_Google_Recaptcha {

		private $options;

		public function __construct() {
			$options = get_option( 'cwginstocksettings' );
			$this->options = get_option( 'cwginstocksettings' );
			$check_is_enable = isset( $options['enable_recaptcha'] ) && '1' == $options['enable_recaptcha'] ? '1' : '2';
			if ( '1' == $check_is_enable ) {
				add_action( 'cwg_instock_after_email_field', array( $this, 'add_recaptcha_to_subscribe_form' ), 10, 2 );
				add_filter( 'cwgstock_submit_attr', array( $this, 'disable_attr_on_recaptcha' ), 10, 3 );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ), 999 );
			}
			add_action( 'cwginstock_register_settings', array( $this, 'add_settings_field' ) );
			add_filter( 'cwginstock_localization_array', array( $this, 'add_localize_data' ) );
			add_action( 'cwginstock_after_submit_button', array( $this, 'google_credit' ), 10, 2 );
		}

		public function add_recaptcha_to_subscribe_form( $product_id, $variation_id ) {
			if ( self::is_recaptcha_enabled() == '1' ) {
				if ( ! self::is_recaptcha_v3() ) {
					$variation_id = intval( $variation_id );
					$options = $this->options;
					$bool = true;
					/**
					 * Filter for bypassing reCAPTCHA in the subscribe form.
					 * 
					 * @since 1.0.0
					 */
					if ( $variation_id > 0 && apply_filters( 'cwginstock_bypass_recaptcha', $bool, $product_id, $variation_id ) ) {
						?>
						<div id="cwg-google-recaptcha"></div>
						<?php
					} else {
						?>
						<div class="g-recaptcha" data-sitekey="<?php echo do_shortcode( $this->get_site_key() ); ?>"
							data-callback="cwginstock_recaptcha_callback"></div>
						<?php
					}
				}
				wp_enqueue_script( 'recaptcha' );
			}
		}

		public function disable_attr_on_recaptcha( $attr, $product_id, $variation_id ) {
			$attr = "disabled='disabled' ";
			return $attr;
		}

		public function enqueue_script() {
			if ( '1' == self::is_recaptcha_enabled() ) {
				if ( ! self::is_recaptcha_v3() ) {
					wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', array(), CWGINSTOCK_VERSION );
				} else {
					$site_key = $this->get_site_key();
					wp_enqueue_script( 'recaptcha', "https://www.google.com/recaptcha/api.js?render=$site_key", array(), CWGINSTOCK_VERSION );
				}
			}
		}

		public function add_settings_field() {
			add_settings_section( 'cwginstock_section_recaptcha', __( 'Google reCAPTCHA Settings ', 'back-in-stock-notifier-for-woocommerce' ), array( $this, 'recaptcha_settings_heading' ), 'cwginstocknotifier_settings' );
			add_settings_field( 'cwg_instock_enable_recaptcha', __( 'Enable reCAPTCHA in Subscribe Form', 'back-in-stock-notifier-for-woocommerce' ), array( $this, 'enable_recaptcha' ), 'cwginstocknotifier_settings', 'cwginstock_section_recaptcha' );
			add_settings_field( 'cwg_instock_select_recaptcha_version', __( 'Select Google reCAPTCHA version', 'back-in-stock-notifier-for-woocommerce' ), array( $this, 'select_recaptcha_version' ), 'cwginstocknotifier_settings', 'cwginstock_section_recaptcha' );
			add_settings_field( 'cwg_instock_recaptcha_sitekey', __( 'reCAPTCHA v2 Site Key', 'back-in-stock-notifier-for-woocommerce' ), array( $this, 'recaptcha_site_key' ), 'cwginstocknotifier_settings', 'cwginstock_section_recaptcha' );
			add_settings_field( 'cwg_instock_enable_gcaptcha_verify', __( 'Verify reCAPTCHA response in Server Side - this will ignore nonce validation', 'back-in-stock-notifier-for-woocommerce' ), array( $this, 'enable_recaptcha_verify' ), 'cwginstocknotifier_settings', 'cwginstock_section_recaptcha' );
			add_settings_field( 'cwg_instock_recaptcha_secret', __( 'reCAPTCHA v2 Secret Key(this is required when you want to verify reCAPTCHA response in server side)', 'back-in-stock-notifier-for-woocommerce' ), array( $this, 'recaptcha_secret_key' ), 'cwginstocknotifier_settings', 'cwginstock_section_recaptcha' );

			//v3
			add_settings_field( 'cwg_instock_recaptcha_vthree_sitekey', __( 'reCAPTCHA v3 Site Key', 'back-in-stock-notifier-for-woocommerce' ), array( $this, 'recaptcha_vthree_site_key' ), 'cwginstocknotifier_settings', 'cwginstock_section_recaptcha' );
			add_settings_field( 'cwg_instock_recaptcha_vthree_secret', __( 'reCAPTCHA v3 Secret Key', 'back-in-stock-notifier-for-woocommerce' ), array( $this, 'recaptcha_vthree_secret_key' ), 'cwginstocknotifier_settings', 'cwginstock_section_recaptcha' );
			add_settings_field( 'cwg_instock_recaptcha_vthree_hide_badge', __( 'Hide Google v3 recaptcha badge in website', 'back-in-stock-notifier-for-woocommerce' ), array( $this, 'recaptcha_vthree_badge_hide' ), 'cwginstocknotifier_settings', 'cwginstock_section_recaptcha' );
		}

		public function recaptcha_settings_heading() {
			$url = "<a href='https://www.google.com/recaptcha/'>" . __( 'Check this for more information about google reCAPTCHA' ) . '</a>';
			$captcha_heading = __( 'Add Google reCAPTCHA to the Subscribe Form', 'back-in-stock-notifier-for-woocommerce' );
			echo esc_url_raw( $captcha_heading . '  ' . $url );
		}

		public function enable_recaptcha() {
			$options = get_option( 'cwginstocksettings' );
			?>
			<input type='checkbox' name='cwginstocksettings[enable_recaptcha]' <?php isset( $options['enable_recaptcha'] ) ? checked( $options['enable_recaptcha'], 1 ) : ''; ?> value="1" />
			<p><i>
					<?php esc_html_e( 'Select this option to enable reCAPTCHA in Subscribe Form(site key required for this option)', 'back-in-stock-notifier-for-woocommerce' ); ?>
				</i></p>
			<?php
		}

		public function select_recaptcha_version() {
			$options = get_option( 'cwginstocksettings' );
			?>
			<select class="cwg_instock_recaptcha_version" name="cwginstocksettings[select_recaptcha_version]" style="width:65px;">
				<option value="v2" <?php echo isset( $options['select_recaptcha_version'] ) && 'v2' == $options['select_recaptcha_version'] ? 'selected=selected' : ''; ?>>
					<?php esc_html_e( 'v2', 'back-in-stock-notifier-for-woocommerce' ); ?>
				</option>
				<option value="v3" <?php echo isset( $options['select_recaptcha_version'] ) && 'v3' == $options['select_recaptcha_version'] ? 'selected=selected' : ''; ?>>
					<?php esc_html_e( 'v3', 'back-in-stock-notifier-for-woocommerce' ); ?>
				</option>
			</select>
			<?php
		}

		public function enable_recaptcha_verify() {
			$options = get_option( 'cwginstocksettings' );
			?>
			<input class="cwg_instock_recaptcha_v2" type='checkbox' name='cwginstocksettings[enable_recaptcha_verify]' <?php isset( $options['enable_recaptcha_verify'] ) ? checked( $options['enable_recaptcha_verify'], 1 ) : ''; ?> value="1" />
			<p><i>
					<?php esc_html_e( 'By Default this option is unchecked means reCAPTCHA verified in client side and WP Nonce Verification in server side, if you check this option then reCAPTCHA Verification can take place in both Client/Server Side(validate again client reCAPTCHA response) and ignore WP Nonce', 'back-in-stock-notifier-for-woocommerce' ); ?>
				</i></p>
			<?php
		}

		public function recaptcha_site_key() {
			$options = get_option( 'cwginstocksettings' );
			?>
			<input type='text' class="cwg_instock_recaptcha_v2" style='width: 400px;' name='cwginstocksettings[recaptcha_site_key]'
				value='<?php echo wp_kses_post( isset( $options['recaptcha_site_key'] ) ? $options['recaptcha_site_key'] : '' ); ?>' />
			<?php
		}

		public function recaptcha_secret_key() {
			$options = get_option( 'cwginstocksettings' );
			?>
			<input type='text' class="cwg_instock_recaptcha_v2" style='width: 400px;'
				name='cwginstocksettings[recaptcha_secret_key]'
				value='<?php echo wp_kses_post( isset( $options['recaptcha_secret_key'] ) ? $options['recaptcha_secret_key'] : '' ); ?>' />
			<p><i>
					<?php esc_html_e( "reCAPTCHA Secret Key required only when you enabled this option - 'Verify reCAPTCHA response in Server Side', otherwise it is optional", 'back-in-stock-notifier-for-woocommerce' ); ?>
				</i></p>
			<?php
		}

		public function recaptcha_vthree_site_key() {
			$options = get_option( 'cwginstocksettings' );
			?>
			<input type='text' class="cwg_instock_recaptcha_v3" style='width: 400px;'
				name='cwginstocksettings[recaptcha_v3_site_key]'
				value='<?php echo wp_kses_post( isset( $options['recaptcha_v3_site_key'] ) ? $options['recaptcha_v3_site_key'] : '' ); ?>' />
			<?php
		}

		public function recaptcha_vthree_secret_key() {
			$options = get_option( 'cwginstocksettings' );
			?>
			<input type='text' class="cwg_instock_recaptcha_v3" style='width: 400px;'
				name='cwginstocksettings[recaptcha_v3_secret_key]'
				value='<?php echo wp_kses_post( isset( $options['recaptcha_v3_secret_key'] ) ? $options['recaptcha_v3_secret_key'] : '' ); ?>' />
			<?php
		}

		public function recaptcha_vthree_badge_hide() {
			$options = get_option( 'cwginstocksettings' );
			?>
			<input class="cwg_instock_recaptcha_v3" type='checkbox' name='cwginstocksettings[recaptchav3_badge_hide]' <?php isset( $options['recaptchav3_badge_hide'] ) ? checked( $options['recaptchav3_badge_hide'], 1 ) : ''; ?> value="1" />
			<?php
		}

		public static function is_recaptcha_enabled() {
			$options = get_option( 'cwginstocksettings' );
			$is_enabled = isset( $options['enable_recaptcha'] ) && '1' == $options['enable_recaptcha'] ? '1' : '2';
			return $is_enabled;
		}

		public static function is_recaptcha_v3() {
			$options = get_option( 'cwginstocksettings' );
			$get_version = isset( $options['select_recaptcha_version'] ) && 'v3' == $options['select_recaptcha_version'] ? true : false;
			return $get_version;
		}

		public function get_site_key() {
			$options = get_option( 'cwginstocksettings' );
			$site_key = isset( $options['recaptcha_site_key'] ) && '' != $options['recaptcha_site_key'] ? $options['recaptcha_site_key'] : '';
			if ( self::is_recaptcha_v3() ) {
				$site_key = isset( $options['recaptcha_v3_site_key'] ) && '' != $options['recaptcha_v3_site_key'] ? $options['recaptcha_v3_site_key'] : '';
			}
			return $site_key;
		}

		public static function get_secret_key() {
			$options = get_option( 'cwginstocksettings' );
			$secret_key = isset( $options['recaptcha_secret_key'] ) && '' != $options['recaptcha_secret_key'] ? $options['recaptcha_secret_key'] : '';
			if ( self::is_recaptcha_v3() ) {
				$secret_key = isset( $options['recaptcha_v3_secret_key'] ) && '' != $options['recaptcha_v3_secret_key'] ? $options['recaptcha_v3_secret_key'] : '';
			}
			return $secret_key;
		}

		public function add_localize_data( $already_loaded ) {
			$options = get_option( 'cwginstocksettings' );
			$already_loaded['enable_recaptcha'] = self::is_recaptcha_enabled();
			$already_loaded['recaptcha_site_key'] = $this->get_site_key();
			$already_loaded['enable_recaptcha_verify'] = ! ( self::is_recaptcha_v3() ) && isset( $options['enable_recaptcha_verify'] ) && '1' == $options['enable_recaptcha_verify'] ? '1' : '2';
			$already_loaded['recaptcha_secret_present'] = self::get_secret_key() != '' ? 'yes' : 'no';
			$already_loaded['is_v3_recaptcha'] = self::is_recaptcha_v3() ? 'yes' : 'no';
			return $already_loaded;
		}

		public function google_credit( $product_id, $variation_id ) {
			$get_option = get_option( 'cwginstocksettings' );
			$is_v3 = self::is_recaptcha_v3() ? 'yes' : 'no';
			$hide_recaptchav3_badge = 'yes' == $is_v3 && isset( $get_option['recaptchav3_badge_hide'] ) && '' != $get_option['recaptchav3_badge_hide'] ? true : false;
			if ( $hide_recaptchav3_badge ) {
				?>
				<div class="cwginstock_google_credit">
					<small>
						<?php esc_html_e( 'This site is protected by reCAPTCHA and the Google', 'back-in-stock-notifier-for-woocommerce' ); ?>
						<a href="https://policies.google.com/privacy">
							<?php esc_html_e( 'Privacy Policy', 'back-in-stock-notifier-for-woocommerce' ); ?>
						</a> and
						<a href="https://policies.google.com/terms">
							<?php esc_html_e( 'Terms of Service', 'back-in-stock-notifier-for-woocommerce' ); ?>
						</a> apply.
					</small>
				</div>
				<?php
			}
		}

	}

	$instock_recaptcha = new CWG_Instock_Google_Recaptcha();
}
