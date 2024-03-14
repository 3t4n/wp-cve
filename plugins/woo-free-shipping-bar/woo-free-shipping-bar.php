<?php
/**
 * Plugin Name: Free Shipping Bar for WooCommerce
 * Plugin URI: https://villatheme.com/
 * Description: Display the total amounts of customer to reach minimum order amount Free Shipping system.
 * Version: 1.2.0
 * Author: VillaTheme
 * Author URI: https://villatheme.com
 * Text Domain: woo-free-shipping-bar
 * Domain Path: /languages
 * Copyright 2017-2023 VillaTheme.com. All rights reserved.
 * Requires at least: 5.0
 * Tested up to: 6.3
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 * Requires PHP: 7.0
 */

define( 'WFSPB_F_VERSION', '1.2.0' );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WFSPB_F_Shipping' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( 'woocommerce-free-shipping-bar/woocommerce-free-shipping-bar.php' ) ) {
		return;
	}
	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		define( 'WFSPB_F_SHIPPING_DIR', WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'woo-free-shipping-bar' . DIRECTORY_SEPARATOR );
		define( 'WFSPB_F_SHIPPING_LANGUAGES_DIR', WFSPB_F_SHIPPING_DIR . 'languages' . DIRECTORY_SEPARATOR );

		$plugin_url = plugins_url( 'woo-free-shipping-bar' );

		define( 'WFSPB_F_SHIPPING_CSS', $plugin_url . '/assets/css/' );
		define( 'WFSPB_F_SHIPPING_JS', $plugin_url . '/assets/js/' );
		define( 'WFSPB_F_SHIPPING_IMAGES', $plugin_url . '/assets/images/' );

	}

	/**
	 * Class WFSPB_F_Shipping
	 */
	class WFSPB_F_Shipping {
		private $settings;

		public function __construct() {
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

			//Compatible with High-Performance order storage (COT)
			add_action( 'before_woocommerce_init', array( $this, 'before_woocommerce_init' ) );

			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				if ( is_file( WFSPB_F_SHIPPING_DIR . "admin-system.php" ) ) {
					require_once WFSPB_F_SHIPPING_DIR . "includes/data.php";
					$this->settings = new WFSPB_F_Data();
				}

				if ( ! is_admin() ) {
					if ( is_file( WFSPB_F_SHIPPING_DIR . "wfspb-front-end.php" ) ) {
						require_once WFSPB_F_SHIPPING_DIR . "wfspb-front-end.php";
					}
				}

				if ( is_file( WFSPB_F_SHIPPING_DIR . "admin-system.php" ) ) {
					require_once WFSPB_F_SHIPPING_DIR . "admin-system.php";
				}

				if ( is_file( WFSPB_F_SHIPPING_DIR . "includes/support.php" ) ) {
					require_once WFSPB_F_SHIPPING_DIR . "includes/support.php";
				}
				if ( class_exists( 'VillaTheme_Support' ) ) {
					new VillaTheme_Support(
						array(
							'support'   => 'https://wordpress.org/support/plugin/woo-free-shipping-bar',
							'docs'      => 'http://docs.villatheme.com/?item=woocommerce-free-shipping-bar',
							'review'    => 'https://wordpress.org/support/plugin/woo-free-shipping-bar/reviews/?rate=5#rate-response',
							'pro_url'   => 'https://1.envato.market/N3mPV',
							'css'       => WFSPB_F_SHIPPING_CSS,
							'image'     => WFSPB_F_SHIPPING_IMAGES,
							'slug'      => 'woo-free-shipping-bar',
							'menu_slug' => 'woocommerce_free_ship',
							'survey_url' => 'https://script.google.com/macros/s/AKfycbyEruJLWkwB0gXJINPkF8gRKVJ4OulK-F8KfgmWxKPdIXWffVQtC4Rz37mUqKWZo1g-DQ/exec',
							'version'   => WFSPB_F_VERSION
						)
					);
				}
				add_action( 'init', array( $this, 'init' ) );
			}

			add_filter(
				'plugin_action_links_woo-free-shipping-bar/woo-free-shipping-bar.php', array(
				$this,
				'settings_link'
			), 9
			);
			add_action( 'admin_notices', array( $this, 'notification' ) );
			add_action( 'admin_menu', array( $this, 'create_options_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script' ) );
			add_action( 'admin_init', array( $this, 'save_data' ), 1 );

			//inline script ajax
			add_action( 'wp_ajax_wfspb_added_to_cart', array( $this, 'get_data_atc' ) );
			add_action( 'wp_ajax_nopriv_wfspb_added_to_cart', array( $this, 'get_data_atc' ) );

//			add_action( 'wp_ajax_wfspb_get_min_amount', array( $this, 'get_min_amount_updated_cart_totals' ) );
//			add_action( 'wp_ajax_nopriv_wfspb_get_min_amount', array( $this, 'get_min_amount_updated_cart_totals' ) );

		}

		public function before_woocommerce_init() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		}

		/**
		 * Function init when run plugin+
		 */
		function init() {
			/*Register post type*/
			load_plugin_textdomain( 'woo-free-shipping-bar' );
			$this->load_plugin_textdomain();

		}

		/**
		 * load Language translate
		 */
		public function load_plugin_textdomain() {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'woo-free-shipping-bar' );
			// Admin Locale
			if ( is_admin() ) {
				load_textdomain( 'woo-free-shipping-bar', WFSPB_F_SHIPPING_LANGUAGES_DIR . "woo-free-shipping-bar-$locale.mo" );
			}

			// Global + Frontend Locale
			load_textdomain( 'woo-free-shipping-bar', WFSPB_F_SHIPPING_LANGUAGES_DIR . "woo-free-shipping-bar-$locale.mo" );
			load_plugin_textdomain( 'woo-free-shipping-bar', false, WFSPB_F_SHIPPING_LANGUAGES_DIR );
		}

		// Notification when activate plugin
		public function notification() {
			if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				deactivate_plugins( 'woo-free-shipping-bar/woo-free-shipping-bar.php' );
				unset( $_GET['activate'] ); ?>

                <div id="message" class="error">
                    <p><?php esc_html_e( 'Please install WooCommerce and active to use Free Shipping Bar for WooCommerce plugin !', 'woo-free-shipping-bar' ); ?></p>
                </div>

				<?php
			}
		}

		//	When activate plugin
		public function activate() {
			global $wp_version;
			if ( version_compare( $wp_version, '2.9', '<' ) ) {
				deactivate_plugins( plugin_basename( __FILE__ ) );
				flush_rewrite_rules();
				wp_die( "This plugin requires WordPress version 2.9 or higher." );

			}
			if ( ! get_option( 'wfspb-param', 0 ) ) {
				update_option( 'wfspb-param', unserialize( 'a:23:{s:12:"default-zone";s:1:"6";s:15:"ipfind_auth_key";s:0:"";s:13:"detect-mobile";s:1:"1";s:8:"bg-color";s:16:"rgb(32, 98, 150)";s:10:"text-color";s:7:"#FFFFFF";s:10:"link-color";s:7:"#77B508";s:4:"font";s:7:"PT Sans";s:9:"font-size";s:2:"16";s:10:"text-align";s:6:"center";s:17:"bg-color-progress";s:7:"#C9CFD4";s:19:"bg-current-progress";s:7:"#0D47A1";s:19:"progress-text-color";s:7:"#FFFFFF";s:18:"font-size-progress";s:2:"11";s:5:"style";s:1:"1";s:8:"position";s:1:"0";s:15:"announce-system";s:43:"Free shipping for billing over {min_amount}";s:17:"message-purchased";s:50:"You have purchased {total_amounts} of {min_amount}";s:15:"message-success";s:65:"Congratulation! You have got free shipping. Go to {checkout_page}";s:13:"message-error";s:74:"You are missing {missing_amount} to get Free Shipping. Continue {shopping}";s:13:"initial-delay";s:1:"5";s:13:"close-message";s:1:"1";s:18:"set-time-disappear";s:1:"5";s:16:"conditional-tags";s:0:"";}' ) );
			}
		}

		//	When deactivate plugin
		public function deactivate() {
			flush_rewrite_rules();
		}

		// link setting page on install plugin
		public function settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=woocommerce_free_ship" title="' . esc_html__( 'Settings', 'woo-free-shipping-bar' ) . '">' . esc_html__( 'Settings', 'woo-free-shipping-bar' ) . '</a>';
			array_unshift( $links, $settings_link );

			return $links;
		}

		//	enqueue script
		public function admin_enqueue_script() {
			$page = isset( $_REQUEST['page'] ) ? wc_clean( $_REQUEST['page'] ) : '';
			if ( $page == 'woocommerce_free_ship' ) {
				global $wp_scripts;
				$scripts = $wp_scripts->registered;
				//			print_r($scripts);
				foreach ( $scripts as $k => $script ) {
					preg_match( '/^\/wp-/i', $script->src, $result );
					if ( count( array_filter( $result ) ) < 1 && $script->handle != 'query-monitor' ) {
						wp_dequeue_script( $script->handle );
					}
				}
				wp_enqueue_style( 'woo-free-shipping-bar-menu', WFSPB_F_SHIPPING_CSS . 'menu.min.css' );
				wp_enqueue_style( 'woo-free-shipping-bar-button', WFSPB_F_SHIPPING_CSS . 'button.min.css' );
				wp_enqueue_style( 'woo-free-shipping-bar-segment', WFSPB_F_SHIPPING_CSS . 'segment.min.css' );
				wp_enqueue_style( 'woo-free-shipping-bar-tab', WFSPB_F_SHIPPING_CSS . 'tab.css' );
				wp_enqueue_style( 'woo-free-shipping-bar-form', WFSPB_F_SHIPPING_CSS . 'form.min.css' );
				wp_enqueue_style( 'woo-free-shipping-bar-dropdown', WFSPB_F_SHIPPING_CSS . 'dropdown.min.css' );
				wp_enqueue_style( 'woo-free-shipping-bar-checkbox', WFSPB_F_SHIPPING_CSS . 'checkbox.min.css' );
				wp_enqueue_style( 'woo-free-shipping-bar-icon', WFSPB_F_SHIPPING_CSS . 'icon.min.css' );
				wp_enqueue_style( 'woocommerce-free-shipping-font-select', WFSPB_F_SHIPPING_CSS . 'fontselect.css' );
				wp_enqueue_style( 'woocommerce-free-shipping-font-transition', WFSPB_F_SHIPPING_CSS . 'transition.min.css' );
				wp_enqueue_style( 'woo-free-shipping-bar-style', WFSPB_F_SHIPPING_CSS . 'woo-free-shipping-bar-admin-style.css', array(), WFSPB_F_VERSION );

				//wp_enqueue_script( 'jquery' );
				wp_enqueue_media();
				wp_enqueue_script( 'woo-free-shipping-bar-dependsOn', WFSPB_F_SHIPPING_JS . 'dependsOn-1.0.2.min.js', array( 'jquery' ) );
				wp_enqueue_script( 'woo-free-shipping-bar-tab', WFSPB_F_SHIPPING_JS . 'tab.js', array( 'jquery' ) );
				wp_enqueue_script( 'woo-free-shipping-bar-form', WFSPB_F_SHIPPING_JS . 'form.js', array( 'jquery' ) );
				wp_enqueue_script( 'woo-free-shipping-bar-dropdown', WFSPB_F_SHIPPING_JS . 'dropdown.js', array( 'jquery' ) );
				wp_enqueue_script( 'woo-free-shipping-bar-checkbox', WFSPB_F_SHIPPING_JS . 'checkbox.js', array( 'jquery' ) );
				wp_enqueue_script( 'woo-free-shipping-bar-font-select', WFSPB_F_SHIPPING_JS . 'jquery.fontselect.min.js', array( 'jquery' ) );
				wp_enqueue_script( 'woo-free-shipping-bar-transition', WFSPB_F_SHIPPING_JS . 'transition.min.js', array( 'jquery' ) );
				wp_enqueue_script( 'woo-free-shipping-bar-jqColorPicker', WFSPB_F_SHIPPING_JS . 'jqColorPicker.min.js', array( 'jquery' ) );
				wp_enqueue_script( 'woo-free-shipping-bar-address', WFSPB_F_SHIPPING_JS . 'jquery.address-1.6.min.js', array( 'jquery' ) );
				wp_enqueue_script( 'woo-free-shipping-bar-admin', WFSPB_F_SHIPPING_JS . 'woo-free-shipping-bar-admin.js', array( 'jquery' ), WFSPB_F_VERSION );

				//inline style Style tab
				$bg_color            = $this->get_field( 'bg-color' );
				$text_color          = $this->get_field( 'text-color' );
				$link_color          = $this->get_field( 'link-color' );
				$text_align          = $this->get_field( 'text-align' );
				$font                = $this->get_field( 'font' );
				$font_size           = $this->get_field( 'font-size' );
				$font_family         = str_replace( '+', ' ', $font );
				$enable_progress     = $this->get_field( 'enable-progress' );
				$bg_progress         = $this->get_field( 'bg-color-progress' );
				$bg_current_progress = $this->get_field( 'bg-current-progress' );
				$progress_text_color = $this->get_field( 'progress-text-color' );
				$fontsize_progress   = $this->get_field( 'font-size-progress' );

				$custom_css = "
					#wfspb-top-bar{
						background-color: {$bg_color};
						color: {$text_color};
						font-family: {$font_family};
					} 
					#wfspb-top-bar #wfspb-main-content{
						font-size: {$font_size}px;
						text-align: {$text_align};
					}
					div#wfspb-close{
						font-size: {$font_size}px;
						line-height: {$font_size}px;
					}
					#wfspb-top-bar #wfspb-main-content > a{
						color: {$link_color};
					}";

				if ( $enable_progress ) {
					$custom_css .= "
					#wfspb-progress{
						background-color: {$bg_progress};
						display: block !important;
					}
					#wfspb-current-progress{
						background-color: {$bg_current_progress};
					}
					#wfspb-label{
						color: {$progress_text_color};
						font-size: {$fontsize_progress}px;
					}
				";
				}

				wp_add_inline_style( 'woo-free-shipping-bar-style', $custom_css );
			}
		}

		public static function set_field( $field, $multi = false ) {
			if ( $field ) {
				if ( $multi ) {
					return 'wfspb-param[' . $field . '][]';
				} else {
					return 'wfspb-param[' . $field . ']';
				}

			} else {
				return '';
			}
		}

		public static function get_field( $field, $default = '' ) {
			$params = get_option( 'wfspb-param', array() );
			if ( isset( $params[ $field ] ) && $field ) {
				return $params[ $field ];
			} else {
				return $default;
			}
		}

		public function get_message_field( $field, $lang = 'default', $default = '' ) {
			$params = $this->settings->get_option( $field );
			if ( isset( $params[ $lang ] ) ) {
				return stripslashes( $params[ $lang ] );
			} else {
				return stripslashes( $default );
			}
		}

		// save data on admin setting options page
		public function save_data() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return false;
			}

			if ( ! isset( $_POST['_woofreeshipbar_nonce'] ) || ! wp_verify_nonce( $_POST['_woofreeshipbar_nonce'], 'woofreeshipbar_action_nonce' ) ) {
				return false;
			}

			$data = wc_clean( $_POST['wfspb-param'] );

			update_option( 'wfspb-param', $data );

			global $woocommerce_free_shipping_settings;
			$woocommerce_free_shipping_settings = null;
			$this->settings                     = new WFSPB_F_Data();
		}

		// admin setting options page
		public function setting_page_woo_free_shipping_bar() { ?>
            <div class="wrap">
                <h1><?php echo esc_html__( 'Free Shipping Bar for WooCommerce', 'woo-free-shipping-bar' ); ?></h1>
                <div class="woo-free-shipping-bar">
					<?php if ( $this->settings->check_woo_shipping_zone() == false ) { ?>
                        <div class="vi-ui negative message">
                            <div class="header">
								<?php esc_html_e( 'Not exists a zone free shipping on Woocommmerce', 'woo-free-shipping-bar' ) ?>
                            </div>
                            <p><?php esc_html_e( 'Please go to WooCommerce -> Settings -> Shipping and then Add New a Shipping Zones with Free Shipping method (or Enable Free Shipping method) for your location.', 'woo-free-shipping-bar' ) ?></p>
                        </div>
					<?php } ?>
                    <form class="vi-ui form" method="post" action="">
						<?php
						wp_nonce_field( 'woofreeshipbar_action_nonce', '_woofreeshipbar_nonce' );
						settings_fields( 'woo-free-shipping-bar' );
						do_settings_sections( 'woo-free-shipping-bar' );

						?>

                        <div class="vi-ui top attached tabular menu">
                            <div class="item active" data-tab="general">
                                <i class="large setting icon"></i><?php esc_html_e( 'General', 'woo-free-shipping-bar' ) ?>
                            </div>
                            <div class="item" data-tab="design">
                                <i class="large tags icon"></i><?php esc_html_e( 'Design', 'woo-free-shipping-bar' ) ?>
                            </div>
                            <div class="item" data-tab="message">
                                <i class="large announcement icon"></i><?php esc_html_e( 'Message', 'woo-free-shipping-bar' ) ?>
                            </div>
                            <div class="item" data-tab="effect">
                                <i class="large crop icon"></i><?php esc_html_e( 'Effect', 'woo-free-shipping-bar' ) ?>
                            </div>
                            <div class="item" data-tab="assign">
                                <i class="large columns icon"></i><?php esc_html_e( 'Assign', 'woo-free-shipping-bar' ) ?>
                            </div>
                        </div>
                        <div class="vi-ui wfspb-container tab attached bottom segment active" data-tab="general">
                            <table class="optiontable form-table">
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Enable', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <div class="vi-ui toggle checkbox checked">
                                            <input type="checkbox"
                                                   name="<?php echo esc_attr( self::set_field( 'enable' ) ); ?>" <?php checked( self::get_field( 'enable' ), 1 ); ?>
                                                   value="1">
                                            <label for="<?php echo esc_attr( self::set_field( 'enable' ) ); ?>"><?php esc_html_e( 'Enable', 'woo-free-shipping-bar' ) ?></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Free Shipping Zone', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <select class="vi-ui fluid dropdown required"
                                                name="<?php echo esc_attr( self::set_field( 'default-zone' ) ); ?>"
                                                value="<?php echo htmlentities( self::get_field( 'default-zone' ) ); ?>">
											<?php $this->get_default_shipping_zone(); ?>
                                        </select>
                                        <p class="description"><?php esc_html_e( 'Please select zone default what you set Free Shiping method.', 'woo-free-shipping-bar' ) ?>
                                            (*)require</p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Detect IP', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <a class="vi-ui button" target="_blank"
                                           href="https://1.envato.market/N3mPV"><?php esc_html_e( 'Get this feature', 'woo-free-shipping-bar' ) ?></a>
                                        <p class="description"><?php esc_html_e( 'If you enable to Detect IP then the user is accessing to your site will be automatically apply to Free Shipping zone with their IP. Note: their ip are contained in Free Shipping zone (Don\'t apply with STATE)', 'woo-free-shipping-bar' ) ?></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Mobile', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <a class="vi-ui button" target="_blank"
                                           href="https://1.envato.market/N3mPV"><?php esc_html_e( 'Get this feature', 'woo-free-shipping-bar' ) ?></a>
                                        <p class="description"><?php esc_html_e( 'Enable on mobile and tablet.', 'woo-free-shipping-bar' ) ?></p>

                                    </td>
                                </tr>

                            </table>
                        </div>
                        <div class="vi-ui wfspb-container tab attached bottom segment" data-tab="design">
                            <table class="optiontable form-table">
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Small Progres Bar', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <a class="vi-ui button" target="_blank"
                                           href="https://1.envato.market/N3mPV"><?php esc_html_e( 'Get this feature', 'woo-free-shipping-bar' ) ?></a>
                                        <p class="description"><?php esc_html_e( 'Show progress bar at bottom Cart page, Checkout page', 'woo-free-shipping-bar' ) ?></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Position on Cart page', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <a class="vi-ui button" target="_blank"
                                           href="https://1.envato.market/N3mPV"><?php esc_html_e( 'Get this feature', 'woo-free-shipping-bar' ) ?></a>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Position on Checkout page', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <a class="vi-ui button" target="_blank"
                                           href="https://1.envato.market/N3mPV"><?php esc_html_e( 'Get this feature', 'woo-free-shipping-bar' ) ?></a>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Show single product page', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <a class="vi-ui button" target="_blank"
                                           href="https://1.envato.market/N3mPV"><?php esc_html_e( 'Get this feature', 'woo-free-shipping-bar' ) ?></a>
                                        <p class="description"><?php esc_html_e( 'Show progress bar below add to cart button.', 'woo-free-shipping-bar' ) ?></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Background Color', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <input type="text"
                                               name="<?php echo esc_attr( self::set_field( 'bg-color' ) ); ?>"
                                               value="<?php echo htmlentities( self::get_field( 'bg-color', 'rgb(32, 98, 150)' ) ); ?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Text Color', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <input type="text"
                                               name="<?php echo esc_attr( self::set_field( 'text-color' ) ); ?>"
                                               value="<?php echo htmlentities( self::get_field( 'text-color', '#FFFFFF' ) ); ?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Link Color', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <input type="text"
                                               name="<?php echo esc_attr( self::set_field( 'link-color' ) ); ?>"
                                               value="<?php echo htmlentities( self::get_field( 'link-color', '#77B508' ) ); ?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Font-Family', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <input id="wfspb-font" type="text"
                                               name="<?php echo esc_attr( self::set_field( 'font' ) ); ?>"
                                               value="<?php echo htmlentities( self::get_field( 'font', 'PT Sans' ) ); ?>">
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Font-Size', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <select class="vi-ui fluid dropdown select-fontsize"
                                                name="<?php echo esc_attr( self::set_field( 'font-size' ) ); ?>">

											<?php for ( $i = 10; $i <= 40; $i ++ ) { ?>
                                                <option value="<?php echo esc_attr( $i ); ?>" <?php selected( self::get_field( 'font-size', 16 ), $i ); ?> > <?php echo esc_html( $i ) . 'px'; ?></option>
											<?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Text Align', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <select class="vi-ui fluid dropdown select-textalign"
                                                name="<?php echo esc_attr( self::set_field( 'text-align' ) ); ?>">
                                            <option value="left" <?php selected( self::get_field( 'text-align' ), 'left' ) ?>><?php esc_html_e( 'Left', 'woo-free-shipping-bar' ) ?></option>
                                            <option value="center" <?php selected( self::get_field( 'text-align', 'center' ), 'center' ) ?>><?php esc_html_e( 'Center', 'woo-free-shipping-bar' ) ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Enable Progress', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <div class="vi-ui toggle checkbox wfspb-enable-progress">
                                            <input type="checkbox"
                                                   name="<?php echo esc_attr( self::set_field( 'enable-progress' ) ); ?>" <?php checked( self::get_field( 'enable-progress' ), 1 ); ?>
                                                   value="1">
                                            <label for="<?php echo esc_attr( self::set_field( 'enable-progress' ) ); ?>"><?php esc_html_e( 'Enable', 'woo-free-shipping-bar' ) ?></label>
                                        </div>
                                    </td>
                                </tr>

                                <tr valign="top" class="wfspb-progress-percent">
                                    <th scope="row"><?php esc_html_e( 'Progress Background Color', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <input type="text"
                                               name="<?php echo esc_attr( self::set_field( 'bg-color-progress' ) ); ?>"
                                               value="<?php echo htmlentities( self::get_field( 'bg-color-progress', '#C9CFD4' ) ); ?>">
                                    </td>
                                </tr>
                                <tr valign="top" class="wfspb-progress-percent">
                                    <th scope="row"><?php esc_html_e( 'Current Progress Background Color', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <input type="text"
                                               name="<?php echo esc_attr( self::set_field( 'bg-current-progress' ) ); ?>"
                                               value="<?php echo htmlentities( self::get_field( 'bg-current-progress', '#0D47A1' ) ); ?>">
                                    </td>
                                </tr>
                                <tr valign="top" class="wfspb-progress-percent">
                                    <th scope="row"><?php esc_html_e( 'Progress Text Color', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <input type="text"
                                               name="<?php echo esc_attr( self::set_field( 'progress-text-color' ) ); ?>"
                                               value="<?php echo htmlentities( self::get_field( 'progress-text-color', '#FFFFFF' ) ); ?>">
                                    </td>
                                </tr>
                                <tr valign="top" class="wfspb-progress-percent">
                                    <th scope="row"><?php esc_html_e( 'Font-Size Progress Bar', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <select class="vi-ui fluid dropdown select-fontsize-progress"
                                                name="<?php echo esc_attr( self::set_field( 'font-size-progress' ) ); ?>">
											<?php for ( $i = 10; $i <= 20; $i ++ ) { ?>
                                                <option value="<?php echo esc_attr( $i ); ?>" <?php selected( self::get_field( 'font-size-progress', 11 ), $i ); ?> > <?php echo esc_html( $i ) . 'px'; ?></option>
											<?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr valign="top" class="wfspb-progress-percent">
                                    <th scope="row"><?php esc_html_e( 'Progress Bar Effect', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <select class="vi-ui fluid dropdown select-progress-effect"
                                                name="<?php echo esc_attr( self::set_field( 'progress_effect' ) ); ?>">

                                            <option value="0" <?php selected( self::get_field( 'progress_effect', 0 ), 0 ); ?>><?php esc_html_e( 'Plain', 'woo-free-shipping-bar' ) ?></option>
                                            <option value="1" <?php selected( self::get_field( 'progress_effect' ), 1 ); ?>><?php esc_html_e( 'Loading', 'woo-free-shipping-bar' ) ?></option>
                                            <option value="2" <?php selected( self::get_field( 'progress_effect' ), 2 ); ?>><?php esc_html_e( 'Border', 'woo-free-shipping-bar' ) ?></option>

                                        </select>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Style', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <div class="vi-ui form">
                                            <div class="three fields">
                                                <div class="field">
                                                    <img src="<?php echo WFSPB_F_SHIPPING_IMAGES ?>progress-style1.png"
                                                         class="vi-ui centered medium image middle aligned"/>
                                                    <div class="vi-ui toggle checkbox checked center aligned segment">
                                                        <input type="radio"
                                                               name="<?php echo esc_attr( self::set_field( 'style' ) ); ?>" <?php checked( self::get_field( 'style', 1 ), 1 ); ?>
                                                               value="1">
                                                        <label for="<?php echo esc_attr( self::set_field( 'style' ) ); ?>"><?php esc_html_e( 'Style 1', 'woo-free-shipping-bar' ) ?></label>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <img src="<?php echo WFSPB_F_SHIPPING_IMAGES ?>progress-style2.png"
                                                         class="vi-ui centered medium image middle aligned"/>
                                                    <div class="vi-ui toggle checkbox checked center aligned segment">
                                                        <input type="radio"
                                                               name="<?php echo esc_attr( self::set_field( 'style' ) ); ?>" <?php checked( self::get_field( 'style' ), 2 ); ?>
                                                               value="2">
                                                        <label for="<?php echo esc_attr( self::set_field( 'style' ) ); ?>"><?php esc_html_e( 'Style 2', 'woo-free-shipping-bar' ) ?></label>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <img src="<?php echo WFSPB_F_SHIPPING_IMAGES ?>progress-style3.png"
                                                         class="vi-ui centered medium image middle aligned"/>
                                                    <div class="vi-ui toggle checkbox checked center aligned segment">
                                                        <input type="radio"
                                                               name="<?php echo esc_attr( self::set_field( 'style' ) ); ?>" <?php checked( self::get_field( 'style' ), 3 ); ?>
                                                               value="3">
                                                        <label for="<?php echo esc_attr( self::set_field( 'style' ) ); ?>"><?php esc_html_e( 'Style 3', 'woo-free-shipping-bar' ) ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Position', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <div class="vi-ui form">
                                            <div class="three fields">
                                                <div class="field">
                                                    <img src="<?php echo WFSPB_F_SHIPPING_IMAGES ?>position-top.png"
                                                         class="vi-ui centered large image middle aligned "/>
                                                    <div class="vi-ui toggle checkbox checked center aligned segment">
                                                        <input type="radio"
                                                               name="<?php echo esc_attr( self::set_field( 'position' ) ); ?>" <?php checked( self::get_field( 'position', 0 ), 0 ); ?>
                                                               value="0">
                                                        <label for="<?php echo esc_attr( self::set_field( 'position' ) ); ?>"><?php esc_html_e( 'Top', 'woo-free-shipping-bar' ) ?></label>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <img src="<?php echo WFSPB_F_SHIPPING_IMAGES ?>position-bottom.png"
                                                         class="vi-ui centered large image middle aligned "/>
                                                    <div class="vi-ui toggle checkbox center aligned segment">
                                                        <input type="radio"
                                                               name="<?php echo esc_attr( self::set_field( 'position' ) ); ?>" <?php checked( self::get_field( 'position' ), 1 ); ?>
                                                               value="1">
                                                        <label for="<?php echo esc_attr( self::set_field( 'position' ) ); ?>"><?php esc_html_e( 'Bottom', 'woo-free-shipping-bar' ) ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Gift Icon', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <a class="vi-ui button" target="_blank"
                                           href="https://1.envato.market/N3mPV"><?php esc_html_e( 'Get this feature', 'woo-free-shipping-bar' ) ?></a>
                                    </td>
                                </tr>
                                <tr valign="top" class="wfspb-gift-box-option">
                                    <th scope="row"><?php esc_html_e( 'Custom Icon', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <a class="vi-ui button" target="_blank"
                                           href="https://1.envato.market/N3mPV"><?php esc_html_e( 'Get this feature', 'woo-free-shipping-bar' ) ?></a>
                                        <p class="description"><?php esc_html_e( 'Image dimension should be 147 x 71(px).', 'woo-free-shipping-bar' ) ?></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Custom CSS', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <textarea
                                                name="<?php echo esc_attr( self::set_field( 'custom_css' ) ); ?>"><?php echo self::get_field( 'custom_css' ) ?></textarea>
                                    </td>
                                </tr>
                            </table>
							<?php
							if ( self::get_field( 'position' ) == 0 ) {
								$class_pos = 'top_bar';
							} else {
								$class_pos = 'bottom_bar';
							}

							if ( self::get_field( 'enable-progress' ) == 0 ) {
								$class_progress = 'disable_progress_bar';
							} else {
								$class_progress = 'enable_progress_bar';
							}
							?>
                            <div id="wfspb-top-bar" class="customized <?php echo esc_attr( $class_pos ) ?>">
                                <div id="wfspb-main-content"><?php echo esc_html__( 'You have purchased $100 of $120. Continue', 'woo-free-shipping-bar' ) ?>
                                    <a href="#"><?php echo esc_html__( 'Shopping', 'woo-free-shipping-bar' ) ?></a>
                                </div>
                                <div class="" id="wfspb-close"></div>
                                <div id="wfspb-progress" class="<?php echo esc_attr( $class_progress ) ?>"
                                     style="display: none">
                                    <div id="wfspb-current-progress">
                                        <div id="wfspb-label">25%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="vi-ui wfspb-container tab attached bottom segment" data-tab="message">
							<?php $langs = function_exists( 'icl_get_languages' ) ? icl_get_languages( 'skip_missing=0&orderby=code' ) :
								array( 'default' => array( 'native_name' => '', 'country_flag_url' => '' ) ); ?>
                            <table class="optiontable form-table">
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Announce System', 'woo-free-shipping-bar' ) ?></th>
                                    <td class="wfspb-notice-sample">
										<?php
										foreach ( $langs as $lang_code => $lang_opttion ) {
											?>
                                            <label><img src="<?php echo esc_url( $lang_opttion['country_flag_url'] ); ?>"> <?php echo esc_html( $lang_opttion['native_name'] ) ?>
                                            </label>
                                            <textarea rows="2"
                                                      name="<?php echo( self::set_field( 'announce-system' ) . '[' . esc_attr( $lang_code ) . ']' ); ?>"><?php echo trim( strip_tags( self::get_message_field( 'announce-system', $lang_code, 'Free shipping for billing over {min_amount}' ) ) ); ?></textarea>
											<?php
										} ?>
                                        <ul class="description" style="list-style: none">
                                            <li>
                                                <span>{min_amount}</span>
                                                - <?php esc_html_e( 'Minimum order amount Free Shipping', 'woo-free-shipping-bar' ) ?>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Message Purchased', 'woo-free-shipping-bar' ) ?></th>
                                    <td class="wfspb-notice-sample">
                                        <div class="field">
											<?php
											foreach ( $langs as $lang_code => $lang_opttion ) {
												?>
                                                <label><img src="<?php echo esc_url( $lang_opttion['country_flag_url'] ); ?>"> <?php echo esc_html( $lang_opttion['native_name'] ) ?>
                                                </label>
                                                <textarea rows="2"
                                                          name="<?php echo( self::set_field( 'message-purchased' ) . '[' . esc_attr( $lang_code ) . ']' ); ?>"><?php echo trim( strip_tags( self::get_message_field( 'message-purchased', $lang_code, 'You have purchased {total_amounts} of {min_amount}' ) ) ); ?></textarea>
												<?php
											} ?>
                                            <ul class="description" style="list-style: none">
                                                <li>
                                                    <span>{total_amounts}</span>
                                                    - <?php esc_html_e( 'The total amount of your purchases', 'woo-free-shipping-bar' ) ?>
                                                </li>
                                                <li>
                                                    <span>{cart_amount}</span>
                                                    - <?php esc_html_e( 'Total quantity in cart.', 'woo-free-shipping-bar' ) ?>
                                                </li>
                                                <li>
                                                    <span>{min_amount}</span>
                                                    - <?php esc_html_e( 'Minimum order amount Free Shipping', 'woo-free-shipping-bar' ) ?>
                                                </li>
                                                <li>
                                                    <span>{missing_amount}</span>
                                                    - <?php esc_html_e( 'The outstanding amount of the free shipping program', 'woo-free-shipping-bar' ) ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Message Success', 'woo-free-shipping-bar' ) ?></th>
                                    <td class="wfspb-notice-sample">
										<?php
										foreach ( $langs as $lang_code => $lang_opttion ) {
											?>
                                            <label><img src="<?php echo esc_url( $lang_opttion['country_flag_url'] ); ?>"> <?php echo esc_html( $lang_opttion['native_name'] ) ?>
                                            </label>
                                            <textarea rows="2"
                                                      name="<?php echo( self::set_field( 'message-success' ) . '[' . esc_attr( $lang_code ) . ']' ); ?>"><?php echo trim( strip_tags( self::get_message_field( 'message-success', $lang_code, 'Congratulation! You have got free shipping. Go to {checkout_page}' ) ) ); ?></textarea>
											<?php
										} ?>
                                        <ul class="description" style="list-style: none">
                                            <li>
                                                <span>{checkout_page}</span>
                                                - <?php esc_html_e( 'Link to checkout page', 'woo-free-shipping-bar' ) ?>
                                            </li>

                                        </ul>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Message Error', 'woo-free-shipping-bar' ) ?></th>
                                    <td class="wfspb-notice-sample">
										<?php
										foreach ( $langs as $lang_code => $lang_opttion ) {
											?>
                                            <label><img src="<?php echo esc_url( $lang_opttion['country_flag_url'] ); ?>"> <?php echo esc_html( $lang_opttion['native_name'] ) ?>
                                            </label>
                                            <textarea rows="2"
                                                      name="<?php echo( self::set_field( 'message-error' ) . '[' . esc_attr( $lang_code ) . ']' ); ?>"><?php echo trim( strip_tags( self::get_message_field( 'message-error', $lang_code, 'You are missing {missing_amount} to get Free Shipping. Continue {shopping}' ) ) ); ?></textarea>
											<?php
										} ?>
                                        <ul class="description" style="list-style: none">
                                            <li>
                                                <span>{missing_amount}</span>
                                                - <?php esc_html_e( 'The outstanding amount of the free shipping program', 'woo-free-shipping-bar' ) ?>
                                            </li>
                                            <li>
                                                <span>{shopping}</span>
                                                - <?php esc_html_e( 'Link to shop page', 'woo-free-shipping-bar' ) ?>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="vi-ui wfspb-container tab attached bottom segment" data-tab="effect">
                            <table class="optiontable form-table">
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Initial delay', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <a class="vi-ui button" target="_blank"
                                           href="https://1.envato.market/N3mPV"><?php esc_html_e( 'Get this feature', 'woo-free-shipping-bar' ) ?></a>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Close message', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <a class="vi-ui button" target="_blank"
                                           href="https://1.envato.market/N3mPV"><?php esc_html_e( 'Get this feature', 'woo-free-shipping-bar' ) ?></a>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Time to disappear', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <a class="vi-ui button" target="_blank"
                                           href="https://1.envato.market/N3mPV"><?php esc_html_e( 'Get this feature', 'woo-free-shipping-bar' ) ?></a>
                                    </td>
                                </tr>
                                <tr valign="top" class="wfspb-sub-settime">
                                    <th scope="row"><?php esc_html_e( 'Set time to disappear', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <a class="vi-ui button" target="_blank"
                                           href="https://1.envato.market/N3mPV"><?php esc_html_e( 'Get this feature', 'woo-free-shipping-bar' ) ?></a>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Show gift box', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <div class="vi-ui toggle checkbox checked">
                                            <input type="hidden"
                                                   name="<?php echo esc_attr( self::set_field( 'show-giftbox' ) ); ?>"
                                                   value="0"/>
                                            <input type="checkbox"
                                                   name="<?php echo esc_attr( self::set_field( 'show-giftbox' ) ); ?>" <?php checked( self::get_field( 'show-giftbox', 1 ), 1 ); ?>
                                                   value="1"/>
                                            <label><?php esc_html_e( 'Enable', 'woo-free-shipping-bar' ) ?></label>
                                            <p class="description"><?php esc_html_e( '(Display gift box when customer add product to cart)', 'woo-free-shipping-bar' ) ?></p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="vi-ui wfspb-container tab attached bottom segment" data-tab="assign">
                            <table class="optiontable form-table">
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Assign pages', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <a class="vi-ui button" target="_blank"
                                           href="https://1.envato.market/N3mPV"><?php esc_html_e( 'Get this feature', 'woo-free-shipping-bar' ) ?></a>
                                        <p class="description"><?php esc_html_e( 'Checked to hide bar on this page', 'woo-free-shipping-bar' ) ?></p>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php esc_html_e( 'Conditional tags', 'woo-free-shipping-bar' ) ?></th>
                                    <td>
                                        <a class="vi-ui button" target="_blank"
                                           href="https://1.envato.market/N3mPV"><?php esc_html_e( 'Get this feature', 'woo-free-shipping-bar' ) ?></a>
                                        <p class="description"><?php esc_html_e( 'Lets you control on which pages disappear using WP\'s conditional tags.', 'woo-free-shipping-bar' ) ?></p>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <p style="position: relative; z-index: 99999; display: inline-block;">
                            <button class="vi-ui primary button wfsb-submit">
                                <i class="send icon"></i>
								<?php echo esc_html__( 'Save', 'woo-free-shipping-bar' ) ?>
                            </button>
                        </p>
                    </form>
                </div>
				<?php
				do_action( 'villatheme_support_woo-free-shipping-bar' );
				?>
            </div>
			<?php
		}

		// Create menu for plugin
		public function create_options_page() {
			add_menu_page(
				esc_html__( 'Free Shipping Bar for WooCommerce', 'woo-free-shipping-bar' ),
				esc_html__( 'WC F-Shipping Bar', 'woo-free-shipping-bar' ),
				'manage_options',
				'woocommerce_free_ship',
				array( $this, 'setting_page_woo_free_shipping_bar' ),
				'dashicons-backup',
				2
			);
		}


		/**
		 * Get total amount woocommerce when added to cart
		 */
		public function get_data_atc() {
			check_ajax_referer( 'vifsb-nonce', 'nonce' );

			$message_success = $this->get_message( 'message-success' );
			$checkout        = '<a href="' . wc_get_checkout_url() . '" title="' . esc_html__( 'Checkout', 'woo-free-shipping-bar' ) . '">' . esc_html__( 'Checkout', 'woo-free-shipping-bar' ) . '</a>';

			$default_zone = $this->get_field( 'default-zone' );
			$customer     = WC()->session->get( 'customer' );
			$country      = isset( $customer['shipping_country'] ) ? $customer['shipping_country'] : '';
			$state        = isset( $customer['shipping_state'] ) ? $customer['shipping_state'] : '';
			$postcode     = isset( $customer['shipping_postcode'] ) ? $customer['shipping_postcode'] : '';

			if ( $country ) {
				$detect_result    = $this->settings->detect_ip( $country, $state, $postcode );
				$min_amount       = $detect_result['min_amount'];
				$ignore_discounts = $detect_result['ignore_discounts'];

				if ( ! $min_amount && $default_zone ) {
					$detect_result    = $this->settings->get_min_amount( $default_zone );
					$min_amount       = $detect_result['min_amount'];
					$ignore_discounts = $detect_result['ignore_discounts'];
					$min_amount       = $this->settings->toInt( $min_amount );
				}
			} elseif ( $default_zone ) {
				$detect_result    = $this->settings->get_min_amount( $default_zone );
				$min_amount       = $detect_result['min_amount'];
				$ignore_discounts = $detect_result['ignore_discounts'];
				$min_amount       = $this->settings->toInt( $min_amount );
			} else {
				$detect_result    = $this->settings->get_shipping_min_amount();
				$min_amount       = $detect_result['min_amount'];
				$ignore_discounts = $detect_result['ignore_discounts'];
			}

			if ( ! $min_amount ) {
				wp_send_json( array( 'no_free_shipping' => 1 ) );
			}

			$total = isset( WC()->cart ) ? WC()->cart->get_displayed_subtotal() : 0;

			if ( WC()->cart->display_prices_including_tax() ) {
				$total = $total - WC()->cart->get_discount_tax();
			}

			if ( 'no' === $ignore_discounts ) {
				$total = $total - WC()->cart->get_discount_total();
			}

			$total = round( $total, wc_get_price_decimals() );

			$cart_amount = WC()->cart->cart_contents_count;
			$key         = array(
				'{total_amounts}',
				'{cart_amount}',
				'{min_amount}',
				'{missing_amount}'
			);

			$missing_amount = $min_amount - $total;
			$amount1        = '<b id="current_amout">' . wc_price( $total ) . '</b>';
			$cart_amount1   = '<b id="current_amout">' . esc_html( $cart_amount ) . '</b>';
			$min_amount1    = '<b id="wfspb_min_order_amount">' . wc_price( $min_amount ) . '</b>';
			if ( is_cart() ) {
				if ( wc()->cart->display_prices_including_tax() ) {
					$missing_amount1 = '<b id="wfspb_missing_amount">' . wc_price( $missing_amount ) . '</b>';
				} else {
					if ( 'incl' === get_option( 'woocommerce_tax_display_shop' ) ) {
						$missing_amount1 = '<b id="wfspb_missing_amount">' . wc_price( $missing_amount ) . '</b>';

					} else {
						if ( wc_prices_include_tax() ) {
							$missing_amount1 = '<b id="wfspb_missing_amount">' . wc_price( $missing_amount ) . '</b>';
						} else {
							$missing_amount_r = $this->settings->real_amount( $missing_amount );
							$missing_amount1  = '<b id="wfspb_missing_amount">' . wc_price( $missing_amount_r ) . '</b>';
						}

					}
				}
			} else {
				if ( 'incl' === get_option( 'woocommerce_tax_display_shop' ) ) {
					if ( wc()->cart->display_prices_including_tax() ) {
						$missing_amount1 = '<b id="wfspb_missing_amount">' . wc_price( $missing_amount ) . '</b>';
					} else {
						$missing_amount_r = $this->settings->get_price_including_tax( $missing_amount );
						$missing_amount1  = '<b id="wfspb_missing_amount">' . wc_price( $missing_amount_r ) . esc_html__( '(incl. tax)', 'woo-free-shipping-bar' ) . '</b>';
					}

				} else {
					if ( wc_prices_include_tax() ) {
						$missing_amount1 = '<b id="wfspb_missing_amount">' . wc_price( $missing_amount ) . '</b>';
					} else {
						$missing_amount_r = $this->settings->real_amount( $missing_amount );
						$missing_amount1  = '<b id="wfspb_missing_amount">' . wc_price( $missing_amount_r ) . '</b>';
					}

				}
			}
			$replaced = array(
				$amount1,
				$cart_amount1,
				$min_amount1,
				$missing_amount1
			);

			$min_percent    = $this->settings->toInt( $min_amount );
			$amount_percent = $this->settings->toInt( $total );
			if ( $amount_percent >= $min_percent ) {
				$amount_total_pr = 100;
			} else {
				if ( $min_percent == 0 ) {
					$amount_total_pr = $amount_percent * 100;
				} else {
					$amount_total_pr = round( ( $amount_percent * 100 ) / $min_percent, 2 );
				}
			}

			$message_purchased = $this->get_message( 'message-purchased' );

			if ( $amount_percent < $min_percent ) {
				$message = str_replace( $key, $replaced, strip_tags( $message_purchased ) );
			} else {
				$message = str_replace( '{checkout_page}', $checkout, strip_tags( $message_success ) );
			}

			$arr_data = array(
				'total_percent' => $amount_total_pr,
				'message_bar'   => wp_unslash( $message )
			);

			echo json_encode( $arr_data );

			die();
		}

		public function get_message( $arg ) {
			$params = $this->settings;
			$lang   = function_exists( 'wpml_get_current_language' ) ? wpml_get_current_language() : 'default';
			$result = ! empty( $params->get_option( $arg )[ $lang ] ) ? $params->get_option( $arg )[ $lang ] : $params->get_option( $arg )['default'];

			return $result;
		}

		// get shipping method (function of ajax)
//		public function get_min_amount_updated_cart_totals() {
//
//			// get value current total cart
//
//			$default_zone = $this->get_field( 'default-zone' );
//			$customer     = WC()->session->get( 'customer' );
//			$country      = isset( $customer['shipping_country'] ) ? $customer['shipping_country'] : '';
//			$state        = isset( $customer['shipping_state'] ) ? $customer['shipping_state'] : '';
//			$postcode     = isset( $customer['shipping_postcode'] ) ? $customer['shipping_postcode'] : '';
//			if ( $country ) {
//				$min_amount = $this->settings->detect_ip( $country, $state, $postcode );
//			} elseif ( $default_zone ) {
//				$min_amount = $this->settings->toInt( $this->settings->get_min_amount( $default_zone ) );
//			} else {
//				$min_amount = $this->settings->get_shipping_min_amount();
//			}
//
//			$total = WC()->cart->get_displayed_subtotal();
//
//			if ( WC()->cart->display_prices_including_tax() ) {
//				$total = round( $total - ( WC()->cart->get_discount_total() + WC()->cart->get_discount_tax() ), wc_get_price_decimals() );
//			} else {
//				$total = round( $total - WC()->cart->get_discount_total(), wc_get_price_decimals() );
//			}
//			if ( $min_amount > $total ) {
//				echo wc_price( $min_amount );
//			} else {
//				echo wc_price( $min_amount );
//			}
//
//			die();
//		}

		// get default shipping method
		public function get_default_shipping_zone() {

			$zones = array();

			// Rest of the World zone
			$zone                                                = new \WC_Shipping_Zone( 0 );
			$zones[ $zone->get_id() ]                            = $zone->get_data();
			$zones[ $zone->get_id() ]['formatted_zone_location'] = $zone->get_formatted_location();
			$zones[ $zone->get_id() ]['shipping_methods']        = $zone->get_shipping_methods();

			// Add user configured zones
			$zones = array_merge( $zones, WC_Shipping_Zones::get_zones() );
			foreach ( $zones as $each_zone ) {
				$zone_name        = $each_zone['zone_name'];
				$shipping_methods = $each_zone['shipping_methods'];
				if ( is_array( $shipping_methods ) && count( $shipping_methods ) ) {
					foreach ( $shipping_methods as $free_shipping ) {
						if ( $free_shipping->id == 'free_shipping' ) {
							$zone_id = isset( $each_zone['zone_id'] ) ? $each_zone['zone_id'] : '';
							echo "<option value='" . esc_attr( $zone_id ) . "' " . selected( self::get_field( 'default-zone', $zone_id ), $zone_id ) . " >" . esc_html__( $zone_name ) . "</option>";
						} else {
							echo '';
						}
					}
				}
			}

		}


	}

	new WFSPB_F_Shipping();
}
