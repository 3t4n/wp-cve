<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'XL_Addon_Install_Checkout' ) ) {
	class XL_Addon_Install_Checkout {
		public static $name = 'Checkout';

		public static function render() {
			echo '<div id="xl_installation">';

			self::section_heading();
			self::section_screenshot();
			self::section_step_install();
			self::section_step_setup();

			echo '</div>';
		}

		public static function section_heading() {
			$img     = XLWCTY_PLUGIN_URL . "admin/assets/img/funnel-builder.webp";
			$heading = __( 'Funnel Builder for WordPress by FunnelKit', 'woo-thank-you-page-nextmove-lite' );
			$desc    = __( 'Customize WooCommerce Checkout Pages, Conversion Friendly Templates, Performance Stats & A/B Funnel Testing', 'woo-thank-you-page-nextmove-lite' );
			?>
            <section class="xl-top">
                <img class="img-top" src="<?php echo esc_url( $img ) ?>" alt="<?php esc_attr_e( $heading ) ?>"/>
                <h1><?php esc_attr_e( $heading ) ?></h1>
                <p><?php echo wp_kses_post( $desc ); ?></p>
            </section>
			<?php
		}

		public static function section_screenshot() {
			$img = XLWCTY_PLUGIN_URL . 'admin/assets/img/funnel-builder-large.webp';
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
                    <li><?php esc_attr_e( 'Optimized WooCommerce checkout templates', 'woo-thank-you-page-nextmove-lite' ); ?></li>
                    <li><?php esc_attr_e( 'Easy customization and works with all page builders', 'woo-thank-you-page-nextmove-lite' ); ?></li>
                    <li><?php esc_attr_e( 'Built-in advanced checkout form fields editor', 'woo-thank-you-page-nextmove-lite' ); ?></li>
                    <li><?php esc_attr_e( 'Conversion tracking with pixel events', 'woo-thank-you-page-nextmove-lite' ); ?></li>
                    <li><?php esc_attr_e( 'Compatible with all popular payment gateways', 'woo-thank-you-page-nextmove-lite' ); ?></li>
                    <li><?php esc_attr_e( 'Powerful analytics for performance tracking', 'woo-thank-you-page-nextmove-lite' ); ?></li>
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
                    <h1><?php esc_html_e( 'Install and Activate the Funnel Builder', 'woo-thank-you-page-nextmove-lite' ) ?></h1>
                    <p><?php esc_html_e( 'Click on the button below to install and activate the Funnel Builder.', 'woo-thank-you-page-nextmove-lite' ) ?></p>
                    <button id="xl_install_button" class="button button-primary" data-xl-slug="funnel-builder" data-xl-file="/funnel-builder.php">
						<?php printf( __( 'Activate %s', 'woo-thank-you-page-nextmove-lite' ), 'Funnel builder' ) ?>
                    </button>
                </div>
            </section>
			<?php
		}

		public static function section_step_setup() {
			$step_two      = XLWCTY_PLUGIN_URL . 'admin/assets/img/two.svg';
			$step_complete = XLWCTY_PLUGIN_URL . 'admin/assets/img/step-complete.svg';
			$action_url    = get_admin_url() . 'admin.php?page=bwf';
			?>
            <section id="xli_setup" class="xli_step xl_step-install">
                <aside class="xli_count">
                    <img id="xli_step_two" src="<?php echo esc_url( $step_two ) ?>" alt="Step 2"/>
                    <img id="step2-completed" class="xli_activated" src="<?php echo esc_url( $step_complete ) ?>" alt="Completed"/>
                </aside>
                <div>
                    <h1><?php esc_html_e( 'Set Up Funnel Builder', 'woo-thank-you-page-nextmove-lite' ) ?></h1>
                    <p><?php esc_html_e( 'Start setting up the FunnelKit Funnel Builder from the button below. This could be the start of something awesome! Start building your funnels and skyrocket your sales 🚀 ', 'woo-thank-you-page-nextmove-lite' ) ?></p>
                    <a href="<?php echo esc_url( $action_url ); ?>" class="button button-primary" id="xl_setup_link"><?php esc_html_e( 'Start setup Funnel Builder', 'woo-thank-you-page-nextmove-lite' ) ?></a>
                </div>
            </section>
			<?php
		}
	}
}
