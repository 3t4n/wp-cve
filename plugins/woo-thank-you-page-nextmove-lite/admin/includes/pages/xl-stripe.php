<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'XL_Addon_Install_Stripe' ) ) {
	class XL_Addon_Install_Stripe {
		public static $name = 'Payments';

		public static function render() {
			echo '<div id="xl_installation">';

			self::section_heading();
			self::section_screenshot();
			self::section_step_install();
			self::section_step_setup();

			echo '</div>';
		}

		public static function section_heading() {
			$img     = XLWCTY_PLUGIN_URL . 'admin/assets/img/funnlkitpayment.webp';
			$heading = __( 'Stripe Payment Gateway for WooCommerce by FunnelKit', 'woo-thank-you-page-nextmove-lite' );
			$desc    = __( 'SCA-Ensured Secure Gateway to Accept Payments in WooCommerce, Quick Onboarding, Multiple Payment Methods and Recurring Subscriptions', 'woo-thank-you-page-nextmove-lite' );
			?>
            <section class="xl-top">
                <img class="img-top" src="<?php echo esc_url( $img ) ?>" alt="<?php esc_attr_e( $heading ) ?>"/>
                <h1><?php esc_attr_e( $heading ) ?></h1>
                <p><?php echo wp_kses_post( $desc ); ?></p>
            </section>
			<?php
		}

		public static function section_screenshot() {
			$img = XLWCTY_PLUGIN_URL . 'admin/assets/img/stripe-payment.webp';
			?>
            <section class="xl_screenshot">
                <div class="xli_cont">
                    <img class="xli_addon_image" src="<?php echo esc_url( $img ); ?>" alt="<?php esc_attr_e( self::$name ); ?>"/>
                    <a href="<?php echo esc_url( $img ); ?>" id="xli_product_image" class="xl_hover"></a>
                    <div id="xli_image_modal" class="xli_screenshot">
                        <span class="xli_close_btn">&times;</span>
                        <img src="<?php echo esc_url( $img ); ?>" class="xli_model_content" alt="<?php esc_attr_e( self::$name ); ?>"/>
                    </div>
                </div>
                <ul>
                    <li><?php esc_attr_e( 'Quick onboarding and easy setup process', 'woo-thank-you-page-nextmove-lite' ); ?></li>
                    <li><?php esc_attr_e( 'Major debit and credit cards such as MasterCard, Visa, American Express, etc.', 'woo-thank-you-page-nextmove-lite' ); ?></li>
                    <li><?php esc_attr_e( 'Local payment methods such as iDEAL, P24, SEPA, Bancontact, and more', 'woo-thank-you-page-nextmove-lite' ); ?></li>
                    <li><?php esc_attr_e( 'Express checkout payments such as Apple Pay and Google Pay', 'woo-thank-you-page-nextmove-lite' ); ?></li>
                    <li><?php esc_attr_e( 'Automatic webhook creation and synchronization', 'woo-thank-you-page-nextmove-lite' ); ?></li>
                    <li><?php esc_attr_e( 'SCA-ensured, 3D secure payments', 'woo-thank-you-page-nextmove-lite' ); ?></li>
                    <li><?php esc_attr_e( 'Recurring payments for subscriptions', 'woo-thank-you-page-nextmove-lite' ); ?></li>
                    <li><?php esc_attr_e( 'Deep compatibility with the complete FunnelKit suite', 'woo-thank-you-page-nextmove-lite' ); ?></li>
                </ul>
            </section>
			<?php
		}

		public static function section_step_install() {
			$step_one      = XLWCTY_PLUGIN_URL . 'admin/assets/img/one.svg';
			$step_complete = XLWCTY_PLUGIN_URL . 'admin/assets/img/step-complete.svg';
			?>
            <div id="xl_installation_error"></div>
            <div id="xl_installation_alert_success"></div>
            <section class="xli_step xl_step-install">
                <aside class="xli_count">
                    <img id="xli_activated_img" src="<?php echo esc_url( $step_one ) ?>" alt="Completed"/>
                    <img id="step-completed" class="xli_activated" src="<?php echo esc_url( $step_complete ) ?>" alt="Step 1"/>
                    <span id="xli_step_one" class="xli_loader_nextmove"></span>
                </aside>
                <div>
                    <h1><?php esc_html_e( 'Install and Activate Stripe Gateway For WooCommerce', 'woo-thank-you-page-nextmove-lite' ) ?></h1>
                    <p><?php esc_html_e( 'Click on the button below to install and activate the Stripe Gateway For WooCommerce..', 'woo-thank-you-page-nextmove-lite' ) ?></p>
                    <button id="xl_install_button" class="button button-primary" data-xl-slug="funnelkit-stripe-woo-payment-gateway" data-xl-file="/funnelkit-stripe-woo-payment-gateway.php">
						<?php printf( __( 'Activate %s', 'woo-thank-you-page-nextmove-lite' ), 'Stripe Gateway For WooCommerce' ) ?>
                    </button>
                </div>
            </section>
			<?php
		}

		public static function section_step_setup() {
			$step_two      = XLWCTY_PLUGIN_URL . 'admin/assets/img/two.svg';
			$step_complete = XLWCTY_PLUGIN_URL . 'admin/assets/img/step-complete.svg';
			$action_url    = get_admin_url() . 'admin.php?page=wc-settings&tab=fkwcs_api_settings';
			?>
            <section id="xli_setup" class="xli_step xl_step-install">
                <aside class="xli_count">
                    <img id="xli_step_two" src="<?php echo esc_url( $step_two ) ?>" alt="Step 2"/>
                    <img id="step2-completed" class="xli_activated" src="<?php echo esc_url( $step_complete ) ?>" alt="Completed"/>
                </aside>
                <div>
                    <h1><?php esc_html_e( ' Set Up FunnelKit Stripe Gateway For WooCommerce', 'woo-thank-you-page-nextmove-lite' ) ?></h1>
                    <p><?php esc_html_e( 'Start setting up FunnelKit Stripe Gateway For WooCommerce from the button below.', 'woo-thank-you-page-nextmove-lite' ) ?></p>
                    <a href="<?php echo esc_url( $action_url ); ?>" class="button button-primary" id="xl_setup_link"><?php esc_html_e( 'Start setup FunnelKit Stripe', 'woo-thank-you-page-nextmove-lite' ) ?></a>
                </div>
            </section>
			<?php
		}
	}
}
