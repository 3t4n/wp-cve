<?php
/**
 * @package     Splitit_WooCommerce_Plugin
 *
 * File - class-splitit-flexfields-payment-plugin-settings.php
 * Class for plugin settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // @Exit if accessed directly
}

/**
 * Class SplitIt_FlexFields_Payment_Plugin_Settings
 */
class SplitIt_FlexFields_Payment_Plugin_Settings {
	/**
	 * Return fields for plugin settings page
	 *
	 * @param array $settings Settings.
	 *
	 * @return array
	 */
	public static function get_fields( $settings ) {
		return array(
			'splitit_merchant_login'                  => array(
				'title' => '',
			),

			'general_setting_section'                 => array(
				'logout_button'       => array(
					'title' => get_option( 'api_key' ) ? '<button type="button" class="login-button" id="merchant_logout">' . __( 'Logout', 'splitit_ff_payment' ) . '</button>' : '<button type="button" class="login-button" id="merchant_login">' . __( 'Login', 'splitit_ff_payment' ) . '</button>',
				),
				'merchant'            => array(
					'title'       => __( 'Merchant', 'splitit_ff_payment' ),
					'description' => self::get_logged_merchant_name(),
					'desc_tip' => false,
				),
				'terminal'            => array(
					'title'       => __( 'Terminal', 'splitit_ff_payment' ),
					'description' => self::get_logged_merchant_terminal(),
					'desc_tip' => false,
				),
				'enabled'             => array(
					'title'   => __( 'Enable/Disable Splitit Payments', 'splitit_ff_payment' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable Payment', 'splitit_ff_payment' ),
					'default' => 'no',
				),
				'splitit_environment' => array(
					'title'             => __( 'Environment', 'splitit_ff_payment' ),
					'description'       => self::get_splitit_environment(),
					'setting_block_css' => 'margin-top: 0; /*display: none;*/',
					'type'              => 'select',
					'desc_tip' => false,
					'css'               => 'background: #fff url(' . plugins_url( 'assets/img/icon-20-px-triangle-down.svg', dirname( __FILE__ ) ) . ') no-repeat right 5px top 55% !important',
					'options'           => array(
						'sandbox'    => __( 'Sandbox', 'splitit_ff_payment' ),
						'production' => __( 'Production', 'splitit_ff_payment' ),
					),
				),
			),

			'Payment_Method_Settings_section'         => array(
				'title'                                 => __( 'Payment Method Settings', 'splitit_ff_payment' ),
				'description'                           => __( 'Specify the payment options you’d like to offer to your customers.', 'splitit_ff_payment' ),
				'splitit_settings_3d'                   => array(
					'title'           => __( 'Require checkout with 3DS', 'splitit_ff_payment' ),
					'type'            => 'select',
					'options'         => array(
						'1' => 'On',
						'0' => 'Off',
					),
					'default'         => '0',
					'desc_tip'        => true,
					'tip_title'       => __( 'Protect your customers', 'splitit_ff_payment' ),
					'tip_description' => __( 'For extra fraud protection, 3D Secure (3DS) requires customers to complete an additional verification step with the card issuer when paying.', 'splitit_ff_payment' ),
				),
				'splitit_auto_capture'                  => array(
					'title'           => __( 'Auto-Capture', 'splitit_ff_payment' ),
					'type'            => 'select',
					'options'         => array(
						'1' => 'On',
						'0' => 'Off',
					),
					'default'         => '0',
					'desc_tip'        => true,
					'tip_title'       => __( 'Protect your customers', 'splitit_ff_payment' ),
					'tip_description' => __( 'With AutoCapture on, your customer’s first payment will be captured instantly, when their card is authorized, NOT later when their product is shipped.', 'splitit_ff_payment' ),
				),
				'splitit_inst_conf'                     => self::get_new_installment_fields( $settings ),
				'splitit_upstream_default_installments' => array(
					'title'       => __( 'Choose the number by which you\'d like your customer journey amount to be divided', 'splitit_ff_payment' ),
					'type'        => 'number',
					'description' => __( 'OPTIONAL, We recommend to leave this field empty and we will use the highest value from the ranges you\'ll set above', 'splitit_ff_payment' ),
				),
			),

			'splitit_product_option'                  => array(
				'title'   => __( 'Enable Splitit per product', 'splitit_ff_payment' ),
				'type'    => 'select',
				'class'   => 'wc-enhanced-select',
				'css'     => 'width: 450px;',
				'default' => '',
				'options' => array(
					'0' => __( 'Disabled', 'splitit_ff_payment' ),
					'1' => __( 'Enable Splitit if the cart consists only of products from the list below', 'splitit_ff_payment' ),
					'2' => __( 'Enable Splitit if the cart consists of at least one of the products from the list below', 'splitit_ff_payment' ),
				),
			),

			'New_Upstream_Messaging_Settings_section' => array(
				'title'                                => __( 'On-Site Messaging Settings', 'splitit_ff_payment' ),
				'description'                          => __( 'Adding on-site messaging is a great way to let shoppers know that you offer installment payments. It’s important to inform the customer throughout their shopping journey to help them make decisions before they get to the checkout.', 'splitit_ff_payment' ),
				'option_name'                          => 'splitit_upstream_messaging_selection',
				'splitit_upstream_messaging_selection' => array(
					'title'             => __( 'Upstream Messaging Selection', 'splitit_ff_payment' ),
					'type'              => 'multiselect',
					'desc_tip'          => true,
					'class'             => 'wc-enhanced-select',
					'css'               => 'width: 450px;',
					'custom_attributes' => array(
						'data-placeholder' => __( 'Upstream Messaging Selection', 'splitit_ff_payment' ),
					),
					'options'           => self::upstream_messaging_selection(),
				),
				'pages'                                => array(
					'splitit_upstream_messaging_position_home_page' => array(
						'title'    => __( 'Home Page', 'splitit_ff_payment' ),
						'checkbox' => 'home_page_banner',
						'name'     => 'home_page_banner',
						'type'     => 'select',
						'options'  => array(
							'left'   => __( 'Left', 'splitit_ff_payment' ),
							'center' => __( 'Center', 'splitit_ff_payment' ),
							'right'  => __( 'Right', 'splitit_ff_payment' ),
						),
					),

					'splitit_upstream_messaging_position_shop_page' => array(
						'title'    => __( 'Shop Page', 'splitit_ff_payment' ),
						'checkbox' => 'shop',
						'name'     => 'shop',
						'type'     => 'select',
						'options'  => array(
							'left'   => __( 'Left', 'splitit_ff_payment' ),
							'center' => __( 'Center', 'splitit_ff_payment' ),
							'right'  => __( 'Right', 'splitit_ff_payment' ),
						),
					),

					'splitit_upstream_messaging_position_product_page' => array(
						'title'    => __( 'Product Page', 'splitit_ff_payment' ),
						'checkbox' => 'product',
						'name'     => 'product',
						'type'     => 'select',
						'options'  => array(
							'left'   => __( 'Left', 'splitit_ff_payment' ),
							'center' => __( 'Center', 'splitit_ff_payment' ),
							'right'  => __( 'Right', 'splitit_ff_payment' ),
						),
					),

					'splitit_upstream_messaging_position_cart_page' => array(
						'title'    => __( 'Cart Page', 'splitit_ff_payment' ),
						'checkbox' => 'cart',
						'name'     => 'cart',
						'type'     => 'select',
						'options'  => array(
							'left'   => __( 'Left', 'splitit_ff_payment' ),
							'center' => __( 'Center', 'splitit_ff_payment' ),
							'right'  => __( ' Right', 'splitit_ff_payment' ),
						),
					),

					'splitit_upstream_messaging_position_checkout_page' => array(
						'title'    => __( 'Checkout Page', 'splitit_ff_payment' ),
						'checkbox' => 'checkout',
						'name'     => 'checkout',
						'type'     => 'select',
						'options'  => array(
							'left'   => __( 'Left', 'splitit_ff_payment' ),
							'center' => __( 'Center', 'splitit_ff_payment' ),
							'right'  => __( ' Right', 'splitit_ff_payment' ),
						),
					),
				),
			),

			'css_provider'                            => array(
				'title' => __( 'Custom CSS', 'splitit_ff_payment' ),
				'type'  => 'title',
				'class' => 'css-provider-title',
			),

			'splitit_upstream_messaging_css'          => array(
				'title'       => __( 'On-Site Messaging CSS', 'splitit_ff_payment' ),
				'description' => __( 'Add custom CSS for On-Site Messaging, for example, to conform to your brand or in-house style', 'splitit_ff_payment' ),
				'type'        => 'textarea',
				'default'     => '<style></style>',
				'font_types'  => self::font_types(),
				'font_sizes'  => self::font_sizes(),
                'css'         => '',
                'placeholder' => '',
			),

			'splitit_flex_fields_css'                 => array(
				'title'       => __( 'Flex Fields CSS', 'splitit_ff_payment' ),
				'description' => __( 'Add custom CSS to change the UI, for example, to conform to your brand or in-house style', 'splitit_ff_payment' ),
				'type'        => 'textarea',
				'default'     => '<style></style>',
				'css'         => '',
				'placeholder' => '',
			),

		);
	}

	/**
	 * Method that getting logged merchant`s info from DB
	 *
	 * @return string
	 */
	public static function get_logged_merchant() {
		$merchant_name = get_option( 'merchant_name' );
		$terminal_name = get_option( 'terminal_name' );

		return $merchant_name && $terminal_name ? '<b>Merchant:</b> ' . $merchant_name . '; ' . '<b>Terminal:</b> ' . $terminal_name : 'Not authorized';
	}

	/**
	 * Method that getting logged merchant`s name from DB
	 *
	 * @return string
	 */
	public static function get_logged_merchant_name() {
		$merchant_name = get_option( 'merchant_name' );

		return $merchant_name ? ucwords( $merchant_name ) : 'Not chosen';
	}

	/**
	 * Method that getting logged merchant`s terminal from DB
	 *
	 * @return string
	 */
	public static function get_logged_merchant_terminal() {
		$terminal_name = get_option( 'terminal_name' );

		return $terminal_name ? ucwords( $terminal_name ) : 'Not chosen';
	}

	/**
	 * Method that getting splitit environment from DB
	 *
	 * @return string
	 */
	public static function get_splitit_environment() {
		return ucwords( get_option( 'splitit_environment' ) );
	}

	/**
	 * Initiate admin scripts and styles
	 *
	 * @param string $plugin_id Plugin ID
	 */
	public static function get_admin_scripts_and_styles( $plugin_id = 'splitit' ) {
		$_GET    = stripslashes_deep( $_GET );
		$section = isset( $_GET['section'] ) ? wc_clean( $_GET['section'] ) : null;
		$action  = isset( $_GET['action'] ) ? wc_clean( $_GET['action'] ) : null;
		$post    = isset( $_GET['post'] ) ? wc_clean( $_GET['post'] ) : null;

		if ( isset( $section ) && $section == $plugin_id ) {
			add_action(
				'admin_enqueue_scripts',
				array(
					'SplitIt_FlexFields_Payment_Plugin_Settings',
					'add_admin_files',
				)
			);
			add_action( 'admin_footer', array( 'SplitIt_FlexFields_Payment_Plugin_Settings', 'wpb_hook_javascript' ) );

			if ( ( ! get_option( 'woocommerce_splitit_settings' ) || empty( get_option( 'woocommerce_splitit_settings' ) ) &&
					( ! get_option( 'splitit_logged_user_data' ) ) || ! get_option( 'api_key' ) ) ) {
				add_action( 'admin_footer', array( 'SplitIt_FlexFields_Payment_Plugin_Settings', 'welcome_pop_up' ) );
			}
		}

		if ( isset( $action ) && 'edit' === $action && isset( $post ) ) {
			add_action(
				'admin_enqueue_scripts',
				array(
					'SplitIt_FlexFields_Payment_Plugin_Settings',
					'add_admin_order_files',
				)
			);
		}
	}

	/**
	 * Welcome pop-up
	 */
	public static function welcome_pop_up() {
		?>

		<button style="display: none" id="welcome_pop_up_btn">Open Modal</button>

		<div id="splitit_welcome_pop_up" class="modal">
			<div class="modal-content">
				<div class="modal-header">
					<span class="Welcome-to-Splitit-p">
					  Welcome to Splitit!
					</span>
				</div>
				<div class="modal-body">
					<div class="Splitit-is-an-Instal">
						Splitit is an installment solution for WooCommerce that lets your customers pay monthly with their existing credit card, so they don't need to take out a new loan. There's no applications, added interest or fees for the shopper to pay, so the checkout experience is fast and simple.
						<a href="https://www.splitit.com/" target="_blank">Learn more about Splitit</a>.
					</div>

					<div class="How-it-works">
						How do I add Splitit to WooCommerce?
					</div>
					<div class="welcome-img-block">
						<div>
							<img src="<?php echo plugins_url( 'assets/img/welcome-connect.svg', dirname( __FILE__ ) ); ?>" class="Group">
						</div>
						<div class="Path-2"></div>
						<div>
							<img src="<?php echo plugins_url( 'assets/img/welcome-config.svg', dirname( __FILE__ ) ); ?>" class="Group">
						</div>
						<div class="Path-2"></div>
						<div>
							<img src="<?php echo plugins_url( 'assets/img/welcome-setup.svg', dirname( __FILE__ ) ); ?>" class="Group">
						</div>
					</div>

					<div class="welcome-steps-block">
						<span class="-Connect-merchant">
						  1. Connect your merchant account
						</span>
						<span class="-Configure-to-your">
						  2. Configure according to your needs
						</span>
						<span class="-Setup-complete">
						  3. Complete<br>setup!
						</span>
					</div>

				</div>
				<div class="modal-footer">
					<button id="connectMerchant" data-env="sandbox" class="connect_merchant_btn sandbox">
						<span class="Create-Account">
						  My demo account
						</span>
					</button>
					<button id="connectMerchantProd" data-env="production" class="connect_merchant_btn">
						<span class="Create-Account">
						  My live account
						</span>
					</button>
					<div class="need-help-block">
						<div class="need-help">
							Need help?
							<a href="https://support.splitit.com" target="_blank">
								click here
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script>
			(function ($) {
				$(window).load(function(){
					$( "#welcome_pop_up_btn" ).trigger( "click" );
				})

				$( "#welcome_pop_up_btn" ).click( function () {
					$( "#splitit_welcome_pop_up" ).show()
				})

				$( "#welcomeModalClose" ).click( function() {
					$( "#splitit_welcome_pop_up" ).hide()
				})
			})(jQuery);
		</script>

		<?php
	}

	/**
	 * Added script and style to the order page
	 */
	public static function add_admin_order_files() {
		wp_enqueue_style( 'splitit_order_css', plugins_url( 'assets/css/adminOrder.css', dirname( __FILE__ ) ) );
		wp_enqueue_script( 'splitit_order_js', plugins_url( '/assets/js/adminOrder.js', dirname( __FILE__ ) ), array( 'jquery' ) );
		wp_add_inline_script( 'splitit_order_js', 'const WC_SPLITIT = ' . json_encode( array( 'ajaxurl_admin' => admin_url( 'admin-ajax.php' ) ) ), 'before' );
	}

	/**
	 * Register admin styles and scripts
	 */
	public static function add_admin_files() {
		wp_enqueue_style( 'spliti_admin_css', plugins_url( 'assets/css/admin.css', dirname( __FILE__ ) ) );
		wp_enqueue_script(
			'spliti_admin_js',
			plugins_url( '/assets/js/admin.js', dirname( __FILE__ ) ),
			array(
				'jquery',
				'jquery-validate',
				'jquery-validate-additional',
				'multipleSelect',
			)
		);

		// @Codemiror
		$cm_settings['flexFieldsCss']        = wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
		$cm_settings['upstreamMessagingCss'] = wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
		wp_localize_script( 'jquery', 'cm_settings', $cm_settings );

		wp_enqueue_script( 'wp-theme-plugin-editor' );
		wp_enqueue_style( 'wp-codemirror' );

		// @JQuery Validation
		wp_enqueue_script( 'jquery-validate', plugins_url( '/assets/validation/jquery.validate.js', dirname( __FILE__ ) ), array( 'jquery' ) );
		wp_enqueue_script(
			'jquery-validate-additional',
			plugins_url( '/assets/validation/additional-methods.js', dirname( __FILE__ ) ),
			array(
				'jquery',
				'jquery-validate',
			)
		);
	}

	/**
	 * Added script for CodeMirror
	 */
	public static function wpb_hook_javascript() {
		?>
		<script>
			ajaxurl_admin = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
			(function ($) {
				$(function () {
					$('#ff_css_collapse').click(function () {
						let ffCodeMirror =  $('#ff_css_advanced_section .CodeMirror')
						if (!ffCodeMirror.length) {
							setTimeout(function() {
								wp.codeEditor.initialize($('#woocommerce_splitit_splitit_flex_fields_css'), cm_settings.flexFieldsCss);
							}, 500);
						}
					})
					$('#um_css_collapse').click(function () {
						let umCodeMirror =  $('#um_css_advanced_section .CodeMirror')
						if (!umCodeMirror.length) {
							setTimeout(function() {
								wp.codeEditor.initialize($('#woocommerce_splitit_splitit_upstream_messaging_css'), cm_settings.upstreamMessagingCss);
							}, 500);
						}
					})
				});
			})(jQuery);

			if ( ! window.hasOwnProperty( 'splitit' ) ) {
				(function (i, s, o, g, r, a, m) {
					i['SplititObject'] = r;
					i[r] = i[r] || function () {
						(i[r].q = i[r].q || []).push(arguments);
					}, i[r].l = 1 * new Date();
					a = s.createElement(o),
						m = s.getElementsByTagName(o)[0];
					a.async = 1;
					a.src = g;
					m.parentNode.insertBefore(a, m);
				})(window, document, 'script', '//web-components.splitit.com/upstream.js?v=' + (Math.ceil(new Date().getTime() / 100000)), 'splitit');
				splitit('init', {
					apiKey: '<?php echo esc_attr( get_option( 'api_key' ) ? get_option( 'api_key' ) : "" ); ?>',
					lang: '<?php echo esc_attr( str_replace( '_', '-', get_locale() ) ); ?>',
					currency: '<?php echo esc_attr( get_woocommerce_currency() ); ?>',
					currencySymbol: '<?php echo esc_attr( get_woocommerce_currency_symbol( get_woocommerce_currency() ) ); ?>',
					env: '<?php echo strtolower( self::get_splitit_environment() ); ?>',
					src: "//web-components.splitit.com/upstream.js",
					defaultInstallments: "4",
					debug: false,
				});
			}
		</script>
		<?php

		wp_register_style( 'multipleSelect', 'https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css', null, null, true );
		wp_enqueue_style( 'multipleSelect' );
		wp_register_script( 'multipleSelect', 'https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js', null, null, true );
		wp_enqueue_script( 'multipleSelect' );
	}

	/**
	 * Return allowed card brands in the footer
	 *
	 * @return string[]
	 */
	private static function footer_card_brands() {
		return array(
			'amex'         => 'Amex',
			'jcb'          => 'jcb',
			'dinersclub'   => 'dinersclub',
			'maestro'      => 'maestro',
			'discover'     => 'discover',
			'visaelectron' => 'visaelectron',
			'mastercard'   => 'mastercard',
			'visa'         => 'visa',
		);
	}

	/**
	 * Return allowed font sizes
	 *
	 * @return string[]
	 */
	private static function font_sizes() {
		$arr = array();

		for ( $i = 1; $i <= 72; $i++ ) {
			$arr[ $i ] = $i;
		}

		return $arr;
	}

	/**
	 * Return allowed font types
	 *
	 * @return string[]
	 */
	private static function font_types() {
		return array(
			'American Typewriter'   => 'American Typewriter',
			'Andale Mono'           => 'Andale Mono',
			'Arial'                 => 'Arial',
			'Arial Black'           => 'Arial Black',
			'Arial Narrow'          => 'Arial Narrow',
			'Arial Rounded MT Bold' => 'Arial Rounded MT Bold',
			'Arial Unicode MS'      => 'Arial Unicode MS',
			'Avenir'                => 'Avenir',
			'Avenir Next'           => 'Avenir Next',
			'Avenir Next Condensed' => 'Avenir Next Condensed',
			'Baskerville'           => 'Baskerville',
			'Big Caslon'            => 'Big Caslon',
			'Bodoni 72'             => 'Bodoni 72',
			'Bodoni 72 Oldstyle'    => 'Bodoni 72 Oldstyle',
			'Bodoni 72 Smallcaps'   => 'Bodoni 72 Smallcaps',
			'Bradley Hand'          => 'Bradley Hand',
			'Brush Script MT'       => 'Brush Script MT',
			'Chalkboard'            => 'Chalkboard',
			'Chalkboard SE'         => 'Chalkboard SE',
			'Chalkduster'           => 'Chalkduster',
			'Charter'               => 'Charter',
			'Cochin'                => 'Cochin',
			'Comic Sans MS'         => 'Comic Sans MS',
			'Copperplate'           => 'Copperplate',
			'Courier'               => 'Courier',
			'Courier New'           => 'Courier New',
			'Didot'                 => 'Didot',
			'DIN Alternate'         => 'DIN Alternate',
			'DIN Condensed'         => 'DIN Condensed',
			'Futura'                => 'Futura',
			'Geneva'                => 'Geneva',
			'Georgia'               => 'Georgia',
			'Gill Sans'             => 'Gill Sans',
			'Helvetica'             => 'Helvetica',
			'Helvetica Neue'        => 'Helvetica Neue',
			'Herculanum'            => 'Herculanum',
			'Hoefler Text'          => 'Hoefler Text',
			'Impact'                => 'Impact',
			'Lucida Grande'         => 'Lucida Grande',
			'Luminari'              => 'Luminari',
			'Marker Felt'           => 'Marker Felt',
			'Menlo'                 => 'Menlo',
			'Microsoft Sans Serif'  => 'Microsoft Sans Serif',
			'Monaco'                => 'Monaco',
			'Noteworthy'            => 'Noteworthy',
			'Optima'                => 'Optima',
			'Palatino'              => 'Palatino',
			'Papyrus'               => 'Papyrus',
			'Phosphate'             => 'Phosphate',
			'Rockwell'              => 'Rockwell',
			'Savoye LET'            => 'Savoye LET',
			'SignPainter'           => 'SignPainter',
			'Skia'                  => 'Skia',
			'Snell Roundhand'       => 'Snell Roundhand',
			'Tahoma'                => 'Tahoma',
			'Times'                 => 'Times',
			'Times New Roman'       => 'Times New Roman',
			'Trattatello'           => 'Trattatello',
			'Trebuchet MS'          => 'Trebuchet MS',
			'Verdana'               => 'Verdana',
			'Zapfino'               => 'Zapfino',
		);
	}

	/**
	 * Return allowd sections for Upstream Messaging
	 *
	 * @return array
	 */
	private static function upstream_messaging_selection() {
		return array(
			'home_page_banner' => __( 'Home Page', 'splitit_ff_payment' ),
			'shop'             => __( 'Shop Page', 'splitit_ff_payment' ),
			'product'          => __( 'Product Page', 'splitit_ff_payment' ),
			'cart'             => __( 'Cart Page', 'splitit_ff_payment' ),
			'checkout'         => __( 'Checkout Page', 'splitit_ff_payment' ),
		);
	}

	/**
	 * Return count of Installments ranges
	 *
	 * @param array $settings Settings.
	 *
	 * @return array
	 */
	private static function get_new_installment_fields( array $settings ) {
		$installments = $settings['splitit_inst_conf']['ic_from'] ?? null;

		$return_data = array();

		if ( isset( $installments ) && ! empty( $installments ) ) {
			foreach ( $installments as $value ) {
				$return_data[] = array(
					'ic_from'        => array(
						'title'   => __( 'Starting price*', 'splitit_ff_payment' ),
						'type'    => 'number',
						'class'   => 'from',
						'default' => '0',
					),
					'ic_to'          => array(
						'title'   => __( 'Ending price*', 'splitit_ff_payment' ),
						'type'    => 'number',
						'class'   => 'to',
						'default' => '1000',
					),
					'ic_installment' => array(
						'title'   => __( 'Installments*', 'splitit_ff_payment' ),
						'type'    => 'select',
						'class'   => 'installments',
						'options' => self::merchant_installments_range(),
					),
					'ic_action'      => array(
						'title' => '<span class="delete_instalment"><span class="trash-icon-new"></span></span>',
						'css'   => 'display:none;',
					),
				);
			}
		}

		return $return_data;
	}

	/**
	 * Return merchant installments range for Upstream Messaging
	 *
	 * @return array
	 */
	public static function merchant_installments_range() {
		$installments = array();

		$inst_from = 1;
		$inst_to   = 15;

		if ( get_option( 'merchant_settings' ) ) {
			$inst_from = get_option( 'merchant_settings' )->MinInstallments;
			$inst_to   = get_option( 'merchant_settings' )->MaxInstallments;
		}

		for ( $i = $inst_from; $i <= $inst_to; $i++ ) {
			$installments[ $i ] = $i;
		}

		return $installments;
	}

	/**
	 * Return count of Installments ranges
	 *
	 * @param array $settings Settings.
	 *
	 * @return array
	 */
	private static function get_installment_fields( array $settings ) {
		$installments = $settings['splitit_inst_conf']['ic_from'] ?? null;

		$return_data = array();

		if ( isset( $installments ) && ! empty( $installments ) ) {
			foreach ( $installments as $value ) {
				$return_data[] = array(
					'ic_from'        => array(
						'title'   => __( 'from', 'splitit_ff_payment' ),
						'type'    => 'number',
						'class'   => 'from',
						'default' => '0',
					),
					'ic_to'          => array(
						'title'   => __( 'to', 'splitit_ff_payment' ),
						'type'    => 'number',
						'class'   => 'to',
						'default' => '1000',
					),
					'ic_installment' => array(
						'title'   => __( 'installment', 'splitit_ff_payment' ),
						'type'    => 'text',
						'class'   => 'installments',
						'default' => '3, 4, 5',
					),
					'ic_action'      => array(
						'title' => '<a href="#" class="delete_instalment"><span class="dashicons dashicons-trash"></span></a>',
						'css'   => 'display:none;',
					),
				);
			}
		}

		return $return_data;
	}

	/**
	 * @param object $order Order.
	 */
	public static function update_order_status_to_old( $order ) {
		$old_status = $order->get_meta( '_old_status' );

		$order->update_status( $old_status );
	}

	/**
	 * @param array $settings Settings array.
	 * @return array
	 */
	private static function get_selected_products( $settings ) {
		if ( ! isset( $settings['splitit_products_list'] ) || empty( $settings['splitit_products_list'] ) ) {
			return array();
		}

		global $wpdb;

		$product_ids = implode( ',', $settings['splitit_products_list'] );

		$query = "SELECT posts.ID as id, posts.post_title as title, lookup.sku as sku
				FROM {$wpdb->prefix}posts as posts
				INNER JOIN {$wpdb->prefix}wc_product_meta_lookup AS lookup ON posts.ID = lookup.product_id
				WHERE posts.ID IN ( $product_ids )";

		$_products = $wpdb->get_results( $query );

		$response = array();
		foreach ( $_products as $product ) {
			$sku                      = $product->sku ? $product->sku : ( '#' . $product->id );
			$response[ $product->id ] = $product->title . ' (' . $sku . ')';
		}

		return $response;
	}
}
