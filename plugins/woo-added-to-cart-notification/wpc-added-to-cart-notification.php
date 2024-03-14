<?php
/*
Plugin Name: WPC Added To Cart Notification for WooCommerce
Plugin URI: https://wpclever.net/
Description: WPC Added To Cart Notification will open a popup to notify the customer immediately after adding a product to cart.
Version: 3.0.1
Author: WPClever
Author URI: https://wpclever.net
Text Domain: woo-added-to-cart-notification
Domain Path: /languages/
Requires at least: 4.0
Tested up to: 6.4
WC requires at least: 3.0
WC tested up to: 8.5
*/

defined( 'ABSPATH' ) || exit;

! defined( 'WOOAC_VERSION' ) && define( 'WOOAC_VERSION', '3.0.1' );
! defined( 'WOOAC_LITE' ) && define( 'WOOAC_LITE', __FILE__ );
! defined( 'WOOAC_FILE' ) && define( 'WOOAC_FILE', __FILE__ );
! defined( 'WOOAC_URI' ) && define( 'WOOAC_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WOOAC_DIR' ) && define( 'WOOAC_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'WOOAC_SUPPORT' ) && define( 'WOOAC_SUPPORT', 'https://wpclever.net/support?utm_source=support&utm_medium=wooac&utm_campaign=wporg' );
! defined( 'WOOAC_REVIEWS' ) && define( 'WOOAC_REVIEWS', 'https://wordpress.org/support/plugin/woo-added-to-cart-notification/reviews/?filter=5' );
! defined( 'WOOAC_CHANGELOG' ) && define( 'WOOAC_CHANGELOG', 'https://wordpress.org/plugins/woo-added-to-cart-notification/#developers' );
! defined( 'WOOAC_DISCUSSION' ) && define( 'WOOAC_DISCUSSION', 'https://wordpress.org/support/plugin/woo-added-to-cart-notification' );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WOOAC_URI );

include 'includes/dashboard/wpc-dashboard.php';
include 'includes/kit/wpc-kit.php';
include 'includes/hpos.php';

if ( ! function_exists( 'wooac_init' ) ) {
	add_action( 'plugins_loaded', 'wooac_init', 11 );

	function wooac_init() {
		// load text-domain
		load_plugin_textdomain( 'woo-added-to-cart-notification', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'wooac_notice_wc' );

			return null;
		}

		if ( ! class_exists( 'WPCleverWooac' ) && class_exists( 'WC_Product' ) ) {
			class WPCleverWooac {
				protected static $settings = [];
				protected static $localization = [];
				protected static $instance = null;

				public static function instance() {
					if ( is_null( self::$instance ) ) {
						self::$instance = new self();
					}

					return self::$instance;
				}

				function __construct() {
					self::$settings     = (array) get_option( 'wooac_settings', [] );
					self::$localization = (array) get_option( 'wooac_localization', [] );

					// settings
					add_action( 'admin_init', [ $this, 'register_settings' ] );
					add_action( 'admin_menu', [ $this, 'admin_menu' ] );

					// backend scripts
					add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

					// frontend scripts
					add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

					// link
					add_filter( 'plugin_action_links', [ $this, 'action_links' ], 10, 2 );
					add_filter( 'plugin_row_meta', [ $this, 'row_meta' ], 10, 2 );

					// add the time
					add_action( 'woocommerce_add_to_cart', [ $this, 'add_to_cart' ], 10 );

					// fragments
					add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'add_to_cart_fragments' ] );

					// footer
					add_action( 'wp_footer', [ $this, 'footer' ] );

					// WPC Smart Messages
					add_filter( 'wpcsm_locations', [ $this, 'wpcsm_locations' ] );
				}

				public static function get_settings() {
					return apply_filters( 'wooac_get_settings', self::$settings );
				}

				public static function get_setting( $name, $default = false ) {
					if ( ! empty( self::$settings ) && isset( self::$settings[ $name ] ) ) {
						$setting = self::$settings[ $name ];
					} else {
						$setting = get_option( 'wooac_' . $name, $default );
					}

					return apply_filters( 'wooac_get_setting', $setting, $name, $default );
				}

				public static function localization( $key = '', $default = '' ) {
					$str = '';

					if ( ! empty( $key ) && ! empty( self::$localization[ $key ] ) ) {
						$str = self::$localization[ $key ];
					} elseif ( ! empty( $default ) ) {
						$str = $default;
					}

					return apply_filters( 'wooac_localization_' . $key, $str );
				}

				function register_settings() {
					// settings
					register_setting( 'wooac_settings', 'wooac_settings' );

					// localization
					register_setting( 'wooac_localization', 'wooac_localization' );
				}

				function admin_menu() {
					add_submenu_page( 'wpclever', esc_html__( 'WPC Added To Cart Notification', 'woo-added-to-cart-notification' ), esc_html__( 'Added To Cart Notification', 'woo-added-to-cart-notification' ), 'manage_options', 'wpclever-wooac', [
						$this,
						'admin_menu_content'
					] );
				}

				function admin_menu_content() {
					$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'settings';
					?>
                    <div class="wpclever_settings_page wrap">
                        <h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Added To Cart Notification', 'woo-added-to-cart-notification' ) . ' ' . WOOAC_VERSION . ' ' . ( defined( 'WOOAC_PREMIUM' ) ? '<span class="premium" style="display: none">' . esc_html__( 'Premium', 'woo-added-to-cart-notification' ) . '</span>' : '' ); ?></h1>
                        <div class="wpclever_settings_page_desc about-text">
                            <p>
								<?php printf( esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'woo-added-to-cart-notification' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
                                <br/>
                                <a href="<?php echo esc_url( WOOAC_REVIEWS ); ?>" target="_blank"><?php esc_html_e( 'Reviews', 'woo-added-to-cart-notification' ); ?></a> |
                                <a href="<?php echo esc_url( WOOAC_CHANGELOG ); ?>" target="_blank"><?php esc_html_e( 'Changelog', 'woo-added-to-cart-notification' ); ?></a> |
                                <a href="<?php echo esc_url( WOOAC_DISCUSSION ); ?>" target="_blank"><?php esc_html_e( 'Discussion', 'woo-added-to-cart-notification' ); ?></a>
                            </p>
                        </div>
						<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) { ?>
                            <div class="notice notice-success is-dismissible">
                                <p><?php esc_html_e( 'Settings updated.', 'woo-added-to-cart-notification' ); ?></p>
                            </div>
						<?php } ?>
                        <div class="wpclever_settings_page_nav">
                            <h2 class="nav-tab-wrapper">
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-wooac&tab=settings' ); ?>" class="<?php echo esc_attr( $active_tab === 'settings' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Settings', 'woo-added-to-cart-notification' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-wooac&tab=localization' ); ?>" class="<?php echo esc_attr( $active_tab === 'localization' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Localization', 'woo-added-to-cart-notification' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-wooac&tab=premium' ); ?>" class="<?php echo esc_attr( $active_tab === 'premium' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>" style="color: #c9356e">
									<?php esc_html_e( 'Premium Version', 'woo-added-to-cart-notification' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-kit' ); ?>" class="nav-tab">
									<?php esc_html_e( 'Essential Kit', 'woo-added-to-cart-notification' ); ?>
                                </a>
                            </h2>
                        </div>
                        <div class="wpclever_settings_page_content">
							<?php if ( $active_tab === 'settings' ) {
								// general
								$show_ajax   = self::get_setting( 'show_ajax', 'yes' );
								$show_normal = self::get_setting( 'show_normal', 'yes' );
								$style       = self::get_setting( 'style', 'default' );
								$layout      = self::get_setting( 'layout', 'vertical' );

								// popup
								$effect                 = self::get_setting( 'effect', 'mfp-3d-unfold' );
								$show_image             = self::get_setting( 'show_image', 'yes' );
								$show_content           = self::get_setting( 'show_content', 'yes' );
								$free_shipping_bar      = self::get_setting( 'free_shipping_bar', 'yes' );
								$suggested              = (array) self::get_setting( 'suggested', [] );
								$suggested_carousel     = self::get_setting( 'suggested_carousel', 'yes' );
								$show_share_cart        = self::get_setting( 'show_share_cart', 'yes' );
								$show_view_cart         = self::get_setting( 'show_view_cart', 'yes' );
								$show_checkout          = self::get_setting( 'show_checkout', 'no' );
								$show_continue_shopping = self::get_setting( 'show_continue_shopping', 'yes' );
								$show_adding            = self::get_setting( 'show_adding', 'no' );
								$continue_url           = self::get_setting( 'continue_url', '' );
								$add_link               = self::get_setting( 'add_link', 'yes' );
								$auto_close             = self::get_setting( 'auto_close', '2000' );

								// notiny
								$notiny_position = self::get_setting( 'notiny_position', 'right-bottom' );
								?>
                                <form method="post" action="options.php">
                                    <table class="form-table">
                                        <tr class="heading">
                                            <th colspan="2">
												<?php esc_html_e( 'General', 'woo-added-to-cart-notification' ); ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Open on AJAX add to cart', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[show_ajax]">
                                                    <option value="yes" <?php selected( $show_ajax, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="no" <?php selected( $show_ajax, 'no' ); ?>><?php esc_html_e( 'No', 'woo-added-to-cart-notification' ); ?></option>
                                                </select>
                                                <span class="description"><?php printf( esc_html__( 'The notification will be opened immediately after whenever click to AJAX Add to cart buttons? See %s "Add to cart behaviour" setting %s', 'woo-added-to-cart-notification' ), '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=products&section=display' ) . '" target="_blank">', '</a>.' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Open on normal add to cart', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[show_normal]">
                                                    <option value="yes" <?php selected( $show_normal, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="no" <?php selected( $show_normal, 'no' ); ?>><?php esc_html_e( 'No', 'woo-added-to-cart-notification' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'The notification will be opened immediately after whenever click to normal Add to cart buttons (AJAX is not enable) or Add to cart button in single product page?', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Style', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[style]" class="wooac_style">
                                                    <option value="default" <?php selected( $style, 'default' ); ?>><?php esc_html_e( 'Popup (default)', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="notiny" <?php selected( $style, 'notiny' ); ?>><?php esc_html_e( 'Notiny', 'woo-added-to-cart-notification' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Popup layout', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[layout]" class="wooac_layout">
                                                    <option value="vertical" <?php selected( $layout, 'vertical' ); ?>><?php esc_html_e( 'Vertical (default)', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="horizontal" <?php selected( $layout, 'horizontal' ); ?>><?php esc_html_e( 'Horizontal', 'woo-added-to-cart-notification' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Popup effect', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[effect]">
                                                    <option value="mfp-fade" <?php selected( $effect, 'mfp-fade' ); ?>><?php esc_html_e( 'Fade', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="mfp-zoom-in" <?php selected( $effect, 'mfp-zoom-in' ); ?>><?php esc_html_e( 'Zoom in', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="mfp-zoom-out" <?php selected( $effect, 'mfp-zoom-out' ); ?>><?php esc_html_e( 'Zoom out', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="mfp-newspaper" <?php selected( $effect, 'mfp-newspaper' ); ?>><?php esc_html_e( 'Newspaper', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="mfp-move-horizontal" <?php selected( $effect, 'mfp-move-horizontal' ); ?>><?php esc_html_e( 'Move horizontal', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="mfp-move-from-top" <?php selected( $effect, 'mfp-move-from-top' ); ?>><?php esc_html_e( 'Move from top', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="mfp-3d-unfold" <?php selected( $effect, 'mfp-3d-unfold' ); ?>><?php esc_html_e( '3d unfold', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="mfp-slide-bottom" <?php selected( $effect, 'mfp-slide-bottom' ); ?>><?php esc_html_e( 'Slide bottom', 'woo-added-to-cart-notification' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Product image', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[show_image]">
                                                    <option value="yes" <?php selected( $show_image, 'yes' ); ?>><?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="no" <?php selected( $show_image, 'no' ); ?>><?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Show/hide the product image.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th><?php esc_html_e( 'Link to individual product', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[add_link]">
                                                    <option value="yes" <?php selected( $add_link, 'yes' ); ?>><?php esc_html_e( 'Yes, open in the same tab', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="yes_blank" <?php selected( $add_link, 'yes_blank' ); ?>><?php esc_html_e( 'Yes, open in the new tab', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="yes_popup" <?php selected( $add_link, 'yes_popup' ); ?>><?php esc_html_e( 'Yes, open quick view popup', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="no" <?php selected( $add_link, 'no' ); ?>><?php esc_html_e( 'No', 'woo-added-to-cart-notification' ); ?></option>
                                                </select> <span class="description">If you choose "Open quick view popup", please install <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=woo-smart-quick-view&TB_iframe=true&width=800&height=550' ) ); ?>" class="thickbox" title="WPC Smart Quick View">WPC Smart Quick View</a> to make it work.</span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Suggested products', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <p style="color: #c9356e">
                                                    This feature is only available on the Premium Version. Click
                                                    <a href="https://wpclever.net/downloads/added-to-cart-notification?utm_source=pro&utm_medium=wooac&utm_campaign=wporg" target="_blank">here</a> to buy, just $29.
                                                </p>
                                                <ul>
                                                    <li>
                                                        <label><input type="checkbox" name="wooac_settings[suggested][]" value="related" <?php echo esc_attr( in_array( 'related', $suggested ) ? 'checked' : '' ); ?>/> <?php esc_html_e( 'Related products', 'woo-added-to-cart-notification' ); ?>
                                                        </label></li>
                                                    <li>
                                                        <label><input type="checkbox" name="wooac_settings[suggested][]" value="up-sells" <?php echo esc_attr( in_array( 'up-sells', $suggested ) ? 'checked' : '' ); ?>/> <?php esc_html_e( 'Upsells products', 'woo-added-to-cart-notification' ); ?>
                                                        </label></li>
                                                    <li>
                                                        <label><input type="checkbox" name="wooac_settings[suggested][]" value="cross-sells" <?php echo esc_attr( in_array( 'cross-sells', $suggested ) ? 'checked' : '' ); ?>/> <?php esc_html_e( 'Cross-sells products', 'woo-added-to-cart-notification' ); ?>
                                                        </label></li>
                                                    <li>
                                                        <label><input type="checkbox" name="wooac_settings[suggested][]" value="wishlist" <?php echo esc_attr( in_array( 'wishlist', $suggested ) ? 'checked' : '' ); ?>/> <?php esc_html_e( 'Wishlist', 'woo-added-to-cart-notification' ); ?>
                                                        </label> <span class="description">(from
                                                            <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=woo-smart-wishlist&TB_iframe=true&width=800&height=550' ) ); ?>" class="thickbox" title="WPC Smart Wishlist">WPC Smart Wishlist</a>)</span>
                                                    </li>
                                                    <li>
                                                        <label><input type="checkbox" name="wooac_settings[suggested][]" value="compare" <?php echo esc_attr( in_array( 'compare', $suggested ) ? 'checked' : '' ); ?>/> <?php esc_html_e( 'Compare', 'woo-added-to-cart-notification' ); ?>
                                                        </label> <span class="description">(from
                                                        <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=woo-smart-compare&TB_iframe=true&width=800&height=550' ) ); ?>" class="thickbox" title="WPC Smart Compare">WPC Smart Compare</a>)</span>
                                                    </li>
                                                </ul>
                                                <p class="description">You can use
                                                    <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=wpc-custom-related-products&TB_iframe=true&width=800&height=550' ) ); ?>" class="thickbox" title="WPC Custom Related Products">WPC Custom Related Products</a> or
                                                    <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=wpc-smart-linked-products&TB_iframe=true&width=800&height=550' ) ); ?>" class="thickbox" title="WPC Smart Linked Products">WPC Smart Linked Products</a> plugin to configure related/upsells/cross-sells in bulk with smart conditions.
                                                </p>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th><?php esc_html_e( 'Suggested products limit', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="number" class="text small-text" min="1" step="1" max="50" name="wooac_settings[suggested_limit]" value="<?php echo esc_attr( self::get_setting( 'suggested_limit', 5 ) ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th><?php esc_html_e( 'Suggested products carousel', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[suggested_carousel]">
                                                    <option value="yes" <?php selected( $suggested_carousel, 'yes' ); ?>><?php esc_html_e( 'Yes', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="no" <?php selected( $suggested_carousel, 'no' ); ?>><?php esc_html_e( 'No', 'woo-added-to-cart-notification' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Cart content', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[show_content]">
                                                    <option value="yes" <?php selected( $show_content, 'yes' ); ?>><?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="no" <?php selected( $show_content, 'no' ); ?>><?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Show/hide the cart total and cart content count.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th><?php esc_html_e( 'Free shipping bar', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[free_shipping_bar]">
                                                    <option value="yes" <?php selected( $free_shipping_bar, 'yes' ); ?>><?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="no" <?php selected( $free_shipping_bar, 'no' ); ?>><?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?></option>
                                                </select> <span class="description">If you enable this option, please install and activate <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=wpc-free-shipping-bar&TB_iframe=true&width=800&height=550' ) ); ?>" class="thickbox" title="WPC Free Shipping Bar">WPC Free Shipping Bar</a> to make it work.</span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th><?php esc_html_e( 'Share cart', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[show_share_cart]">
                                                    <option value="yes" <?php selected( $show_share_cart, 'yes' ); ?>><?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="no" <?php selected( $show_share_cart, 'no' ); ?>><?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?></option>
                                                </select> <span class="description">If you enable this option, please install and activate <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=wpc-share-cart&TB_iframe=true&width=800&height=550' ) ); ?>" class="thickbox" title="WPC Share Cart">WPC Share Cart</a> to make it work.</span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'View cart', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[show_view_cart]">
                                                    <option value="yes" <?php selected( $show_view_cart, 'yes' ); ?>><?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="no" <?php selected( $show_view_cart, 'no' ); ?>><?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Show/hide "View cart" button.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Checkout', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[show_checkout]">
                                                    <option value="yes" <?php selected( $show_checkout, 'yes' ); ?>><?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="no" <?php selected( $show_checkout, 'no' ); ?>><?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Show/hide "Checkout" button.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Continue shopping', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[show_continue_shopping]">
                                                    <option value="yes" <?php selected( $show_continue_shopping, 'yes' ); ?>><?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="no" <?php selected( $show_continue_shopping, 'no' ); ?>><?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Show/hide "Continue shopping" button.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Continue shopping link', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="url" name="wooac_settings[continue_url]" class="regular-text code" value="<?php echo esc_url( $continue_url ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'By default, only hide the popup when clicking on "Continue Shopping" button.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Auto close', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input name="wooac_settings[auto_close]" type="number" min="0" max="300000" step="1" value="<?php echo esc_attr( $auto_close ); ?>"/>ms.
                                                <span class="description"><?php esc_html_e( 'Set the time is zero to disable auto close.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-notiny">
                                            <th scope="row"><?php esc_html_e( 'Position', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[notiny_position]">
                                                    <option value="right-top" <?php selected( $notiny_position, 'right-top' ); ?>><?php esc_html_e( 'right-top', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="right-bottom" <?php selected( $notiny_position, 'right-bottom' ); ?>><?php esc_html_e( 'right-bottom', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="fluid-top" <?php selected( $notiny_position, 'fluid-top' ); ?>><?php esc_html_e( 'center-top', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="fluid-bottom" <?php selected( $notiny_position, 'fluid-bottom' ); ?>><?php esc_html_e( 'center-bottom', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="left-top" <?php selected( $notiny_position, 'left-top' ); ?>><?php esc_html_e( 'left-top', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="left-bottom" <?php selected( $notiny_position, 'left-bottom' ); ?>><?php esc_html_e( 'left-bottom', 'woo-added-to-cart-notification' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Adding to cart', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_settings[show_adding]">
                                                    <option value="yes" <?php selected( $show_adding, 'yes' ); ?>><?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?></option>
                                                    <option value="no" <?php selected( $show_adding, 'no' ); ?>><?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Show/hide notifications of products being added to cart.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th colspan="2"><?php esc_html_e( 'Suggestion', 'woo-added-to-cart-notification' ); ?></th>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                To display custom engaging real-time messages on any wished positions, please install
                                                <a href="https://wordpress.org/plugins/wpc-smart-messages/" target="_blank">WPC Smart Messages</a> plugin. It's free!
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                Wanna save your precious time working on variations? Try our brand-new free plugin
                                                <a href="https://wordpress.org/plugins/wpc-variation-bulk-editor/" target="_blank">WPC Variation Bulk Editor</a> and
                                                <a href="https://wordpress.org/plugins/wpc-variation-duplicator/" target="_blank">WPC Variation Duplicator</a>.
                                            </td>
                                        </tr>
                                        <tr class="submit">
                                            <th colspan="2">
												<?php settings_fields( 'wooac_settings' ); ?><?php submit_button(); ?>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'localization' ) { ?>
                                <form method="post" action="options.php">
                                    <table class="form-table">
                                        <tr class="heading">
                                            <th scope="row"><?php esc_html_e( 'General', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
												<?php esc_html_e( 'Leave blank to use the default text and its equivalent translation in multiple languages.', 'woo-added-to-cart-notification' ); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Added to the cart', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="wooac_localization[added]" value="<?php echo esc_attr( self::localization( 'added' ) ); ?>" placeholder="<?php esc_attr_e( '%s was added to the cart.', 'woo-added-to-cart-notification' ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Use %s to show the product name.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Adding to the cart', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="wooac_localization[adding]" value="<?php echo esc_attr( self::localization( 'adding' ) ); ?>" placeholder="<?php esc_attr_e( '%s is being added to the cart...', 'woo-added-to-cart-notification' ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Use %s to show the product name.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'You may also like', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="wooac_localization[suggested]" value="<?php echo esc_attr( self::localization( 'suggested' ) ); ?>" placeholder="<?php esc_attr_e( 'You may also like', 'woo-added-to-cart-notification' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Cart content', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="wooac_localization[cart_content]" value="<?php echo esc_attr( self::localization( 'cart_content' ) ); ?>" placeholder="<?php esc_attr_e( 'Your cart: %s', 'woo-added-to-cart-notification' ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Use %s to show the cart content.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Count (singular)', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="wooac_localization[count_singular]" value="<?php echo esc_attr( self::localization( 'count_singular' ) ); ?>" placeholder="<?php esc_attr_e( '%s item', 'woo-added-to-cart-notification' ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Use %s to show the count.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Count (plural)', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="wooac_localization[count_plural]" value="<?php echo esc_attr( self::localization( 'count_plural' ) ); ?>" placeholder="<?php esc_attr_e( '%s items', 'woo-added-to-cart-notification' ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Use %s to show the count.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Share cart', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="wooac_localization[share_cart]" value="<?php echo esc_attr( self::localization( 'share_cart' ) ); ?>" placeholder="<?php esc_attr_e( 'Share cart', 'woo-added-to-cart-notification' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'View cart', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="wooac_localization[view_cart]" value="<?php echo esc_attr( self::localization( 'view_cart' ) ); ?>" placeholder="<?php esc_attr_e( 'View cart', 'woo-added-to-cart-notification' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Checkout', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="wooac_localization[checkout]" value="<?php echo esc_attr( self::localization( 'checkout' ) ); ?>" placeholder="<?php esc_attr_e( 'Checkout', 'woo-added-to-cart-notification' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Continue shopping', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="wooac_localization[continue]" value="<?php echo esc_attr( self::localization( 'continue' ) ); ?>" placeholder="<?php esc_attr_e( 'Continue shopping', 'woo-added-to-cart-notification' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr class="submit">
                                            <th colspan="2">
												<?php settings_fields( 'wooac_localization' ); ?><?php submit_button(); ?>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'premium' ) { ?>
                                <div class="wpclever_settings_page_content_text">
                                    <p>
                                        Get the Premium Version just $29!
                                        <a href="https://wpclever.net/downloads/added-to-cart-notification?utm_source=pro&utm_medium=wooac&utm_campaign=wporg" target="_blank">https://wpclever.net/downloads/added-to-cart-notification</a>
                                    </p>
                                    <p><strong>Extra features for Premium Version:</strong></p>
                                    <ul style="margin-bottom: 0">
                                        <li>- Show suggested products.</li>
                                        <li>- Get the lifetime update & premium support.</li>
                                    </ul>
                                </div>
							<?php } ?>
                        </div>
                    </div>
					<?php
				}

				function admin_enqueue_scripts( $hook ) {
					if ( strpos( $hook, 'wooac' ) ) {
						add_thickbox();
						wp_enqueue_script( 'wooac-backend', WOOAC_URI . 'assets/js/backend.js', [ 'jquery' ], WOOAC_VERSION, true );
					}
				}

				function enqueue_scripts() {
					$style = self::get_setting( 'style', 'default' );

					switch ( $style ) {
						case 'notiny':
							// notiny
							wp_enqueue_style( 'notiny', WOOAC_URI . 'assets/libs/notiny/notiny.css' );
							wp_enqueue_script( 'notiny', WOOAC_URI . 'assets/libs/notiny/notiny.js', [ 'jquery' ], WOOAC_VERSION, true );
							break;
						default:
							// feather icons
							wp_enqueue_style( 'wooac-feather', WOOAC_URI . 'assets/libs/feather/feather.css' );

							// magnific
							wp_enqueue_style( 'magnific-popup', WOOAC_URI . 'assets/libs/magnific-popup/magnific-popup.css' );
							wp_enqueue_script( 'magnific-popup', WOOAC_URI . 'assets/libs/magnific-popup/jquery.magnific-popup.min.js', [ 'jquery' ], WOOAC_VERSION, true );
					}

					$added_to_cart = 'no';
					$requests      = apply_filters( 'wooac_auto_show_requests', [
						'add-to-cart',
						'product_added_to_cart',
						'added_to_cart',
						'set_cart',
						'fill_cart'
					] );

					if ( is_array( $requests ) && ! empty( $requests ) ) {
						foreach ( $requests as $request ) {
							if ( isset( $_REQUEST[ $request ] ) ) {
								$added_to_cart = 'yes';
								break;
							}
						}
					}

					// main style & js
					wp_enqueue_style( 'wooac-frontend', WOOAC_URI . 'assets/css/frontend.css', [], WOOAC_VERSION );
					wp_enqueue_script( 'wooac-frontend', WOOAC_URI . 'assets/js/frontend.js', [
						'jquery',
						'wc-cart-fragments'
					], WOOAC_VERSION, true );
					wp_localize_script( 'wooac-frontend', 'wooac_vars', apply_filters( 'wooac_vars', [
							'show_ajax'                 => self::get_setting( 'show_ajax', 'yes' ),
							'show_normal'               => self::get_setting( 'show_normal', 'yes' ),
							'show_adding'               => self::get_setting( 'show_adding', 'no' ),
							'add_to_cart_button'        => apply_filters( 'wooac_add_to_cart_button', '.add_to_cart_button:not(.disabled, .wpc-disabled, .wooaa-disabled, .wooco-disabled, .woosb-disabled, .woobt-disabled, .woosg-disabled, .woofs-disabled, .woopq-disabled, .wpcbn-btn, .wpcuv-update), .single_add_to_cart_button:not(.disabled, .wpc-disabled, .wooaa-disabled, .wooco-disabled, .woosb-disabled, .woobt-disabled, .woosg-disabled, .woofs-disabled, .woopq-disabled, .wpcbn-btn, .wpcuv-update)' ),
							'archive_product'           => apply_filters( 'wooac_archive_product', '.product' ),
							'archive_product_name'      => apply_filters( 'wooac_archive_product_name', '.woocommerce-loop-product__title' ),
							'archive_product_image'     => apply_filters( 'wooac_archive_product_image', '.attachment-woocommerce_thumbnail' ),
							'single_product'            => apply_filters( 'wooac_single_product', '.product' ),
							'single_product_name'       => apply_filters( 'wooac_single_product_name', '.product_title' ),
							'single_product_image'      => apply_filters( 'wooac_single_product_image', '.wp-post-image' ),
							'single_add_to_cart_button' => apply_filters( 'wooac_single_add_to_cart_button', '.single_add_to_cart_button' ),
							'style'                     => self::get_setting( 'style', 'default' ),
							'effect'                    => self::get_setting( 'effect', 'mfp-3d-unfold' ),
							'suggested'                 => json_encode( (array) self::get_setting( 'suggested', [] ) ),
							'carousel'                  => self::get_setting( 'suggested_carousel', 'yes' ) === 'yes',
							'close'                     => (int) self::get_setting( 'auto_close', '2000' ),
							'delay'                     => (int) apply_filters( 'wooac_delay', 300 ),
							'notiny_position'           => self::get_setting( 'notiny_position', 'right-bottom' ),
							'added_to_cart'             => esc_attr( $added_to_cart ),
							'slick_params'              => apply_filters( 'wooac_slick_params_json', json_encode( apply_filters( 'wooac_slick_params', [
								'slidesToShow'   => 1,
								'slidesToScroll' => 1,
								'dots'           => true,
								'arrows'         => false,
								'adaptiveHeight' => true,
								'autoplay'       => true,
								'autoplaySpeed'  => 3000,
								'rtl'            => is_rtl()
							] ) ) ),
						] )
					);
				}

				function action_links( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$settings             = '<a href="' . admin_url( 'admin.php?page=wpclever-wooac&tab=settings' ) . '">' . esc_html__( 'Settings', 'woo-added-to-cart-notification' ) . '</a>';
						$links['wpc-premium'] = '<a href="' . admin_url( 'admin.php?page=wpclever-wooac&tab=premium' ) . '" style="color: #c9356e">' . esc_html__( 'Premium Version', 'woo-added-to-cart-notification' ) . '</a>';
						array_unshift( $links, $settings );
					}

					return (array) $links;
				}

				function row_meta( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$row_meta = [
							'support' => '<a href="' . esc_url( WOOAC_DISCUSSION ) . '" target="_blank">' . esc_html__( 'Community support', 'woo-added-to-cart-notification' ) . '</a>',
						];

						return array_merge( $links, $row_meta );
					}

					return (array) $links;
				}

				function get_notiny() {
					ob_start();
					$items = WC()->cart->get_cart();

					echo '<div class="wooac-wrapper wooac-notiny wooac-notiny-added">';
					do_action( 'wooac_notiny_before' );

					if ( is_array( $items ) && count( $items ) > 0 ) {
						foreach ( $items as $key => $item ) {
							if ( ! isset( $item['wooac_time'] ) ) {
								$items[ $key ]['wooac_time'] = time() - 10000;
							}
						}

						array_multisort( array_column( $items, 'wooac_time' ), SORT_ASC, $items );
						$item    = end( $items );
						$product = apply_filters( 'wooac_product', $item['data'], $item );

						if ( $product && ( $product_id = $product->get_id() ) && ! apply_filters( 'wooac_exclude', false, $product, $item ) ) {
							if ( ! in_array( $product_id, apply_filters( 'wooac_exclude_ids', [ 0 ] ), true ) ) {
								echo apply_filters( 'wooac_image', '<div class="wooac-image">' . $product->get_image() . '</div>', $product );
								echo apply_filters( 'wooac_text', '<div class="wooac-text">' . sprintf( self::localization( 'added', esc_html__( '%s was added to the cart.', 'woo-added-to-cart-notification' ) ), '<span>' . $product->get_name() . '</span>' ) . '</div>', $product );
							}
						}
					}

					do_action( 'wooac_notiny_after' );
					echo '</div>';

					return apply_filters( 'wooac_notiny_html', ob_get_clean() );
				}

				function get_popup() {
					ob_start();
					$items              = WC()->cart->get_cart();
					$layout             = self::get_setting( 'layout', 'vertical' );
					$suggested_products = [];

					do_action( 'wooac_wrap_above' );
					echo '<div class="' . esc_attr( 'wooac-wrapper wooac-popup wooac-popup-added mfp-with-anim wooac-popup-' . $layout ) . '">';
					do_action( 'wooac_wrap_before' );

					if ( is_array( $items ) && count( $items ) > 0 ) {
						foreach ( $items as $cart_item_key => $cart_item ) {
							if ( ! isset( $cart_item['wooac_time'] ) ) {
								$items[ $cart_item_key ]['wooac_time'] = time() - 10000;
							}
						}

						array_multisort( array_column( $items, 'wooac_time' ), SORT_ASC, $items );
						$item = end( $items );

						global $product;
						$global_product = $product;
						$product        = apply_filters( 'wooac_product', $item['data'], $item );

						if ( $product && ( $product_id = $product->get_id() ) && ! apply_filters( 'wooac_exclude', false, $product, $item ) ) {
							if ( ! in_array( $product_id, apply_filters( 'wooac_exclude_ids', [ 0 ] ), true ) ) {
								$link = self::get_setting( 'add_link', 'yes' );

								if ( $layout === 'horizontal' ) {
									echo '<div class="wooac-popup-inner">';
								}

								if ( self::get_setting( 'show_image', 'yes' ) === 'yes' ) {
									do_action( 'wooac_image_before' );

									if ( $link !== 'no' ) {
										echo apply_filters( 'wooac_image', '<div class="wooac-image"><a ' . ( $link === 'yes_popup' ? 'class="woosq-btn" data-id="' . $product_id . '"' : '' ) . ' href="' . $product->get_permalink() . '" ' . ( $link === 'yes_blank' ? 'target="_blank"' : '' ) . '>' . $product->get_image() . '</a></div>', $product );
									} else {
										echo apply_filters( 'wooac_image', '<div class="wooac-image">' . $product->get_image() . '</div>', $product );
									}

									do_action( 'wooac_image_after' );
								}

								if ( $layout === 'horizontal' ) {
									echo '<div class="wooac-content">';
								}

								do_action( 'wooac_text_before' );

								if ( $link !== 'no' ) {
									echo apply_filters( 'wooac_text', '<div class="wooac-text">' . sprintf( self::localization( 'added', esc_html__( '%s was added to the cart.', 'woo-added-to-cart-notification' ) ), '<a ' . ( $link === 'yes_popup' ? 'class="woosq-btn" data-id="' . $product_id . '"' : '' ) . ' href="' . $product->get_permalink() . '" ' . ( $link === 'yes_blank' ? 'target="_blank"' : '' ) . '>' . $product->get_name() . '</a>' ) . '</div>', $product );
								} else {
									echo apply_filters( 'wooac_text', '<div class="wooac-text">' . sprintf( self::localization( 'added', esc_html__( '%s was added to the cart.', 'woo-added-to-cart-notification' ) ), '<span>' . $product->get_name() . '</span>' ) . '</div>', $product );
								}

								do_action( 'wooac_text_after' );

								if ( self::get_setting( 'show_content', 'yes' ) === 'yes' ) {
									do_action( 'wooac_cart_content_before' );

									$count = WC()->cart->get_cart_contents_count();

									if ( $count === 1 ) {
										$count_str = self::localization( 'count_singular', esc_html__( '%s item', 'woo-added-to-cart-notification' ) );
									} else {
										$count_str = self::localization( 'count_plural', esc_html__( '%s items', 'woo-added-to-cart-notification' ) );
									}

									$cart_content_data = '<span class="wooac-cart-content-total">' . apply_filters( 'wooac_cart_content_total', wp_kses_post( WC()->cart->get_cart_subtotal() ) ) . '</span> <span class="wooac-cart-content-count">' . apply_filters( 'wooac_cart_content_count', wp_kses_data( sprintf( $count_str, WC()->cart->get_cart_contents_count() ) ) ) . '</span>';
									$cart_content      = sprintf( self::localization( 'cart_content', esc_html__( 'Your cart: %s', 'woo-added-to-cart-notification' ) ), $cart_content_data );
									echo apply_filters( 'wooac_cart_content', '<div class="wooac-cart-content">' . $cart_content . '</div>' );

									do_action( 'wooac_cart_content_after' );
								}

								if ( ( self::get_setting( 'free_shipping_bar', 'yes' ) === 'yes' ) && class_exists( 'WPCleverWpcfb' ) ) {
									do_action( 'wooac_free_shipping_bar_before' );
									echo do_shortcode( '[wpcfb]' );
									do_action( 'wooac_free_shipping_bar_after' );
								}

								if ( ( ( self::get_setting( 'show_share_cart', 'yes' ) === 'yes' ) && class_exists( 'WPCleverWpcss' ) ) || ( self::get_setting( 'show_view_cart', 'yes' ) === 'yes' ) || ( self::get_setting( 'show_checkout', 'no' ) === 'yes' ) || ( self::get_setting( 'show_continue_shopping', 'yes' ) === 'yes' ) ) {
									echo '<div class="wooac-action">';
									do_action( 'wooac_action_before' );

									if ( ( self::get_setting( 'show_share_cart', 'yes' ) === 'yes' ) && class_exists( 'WPCleverWpcss' ) ) {
										echo apply_filters( 'wooac_share', '<a id="wooac-share" class="wpcss-btn" data-hash="' . esc_attr( WC()->cart->get_cart_hash() ) . '" href="' . wc_get_cart_url() . '">' . self::localization( 'share_cart', esc_html__( 'Share cart', 'woo-added-to-cart-notification' ) ) . '</a>' );
									}

									if ( self::get_setting( 'show_view_cart', 'yes' ) === 'yes' ) {
										echo apply_filters( 'wooac_cart', '<a id="wooac-cart" href="' . wc_get_cart_url() . '">' . self::localization( 'view_cart', esc_html__( 'View cart', 'woo-added-to-cart-notification' ) ) . '</a>' );
									}

									if ( self::get_setting( 'show_checkout', 'no' ) === 'yes' ) {
										echo apply_filters( 'wooac_checkout', '<a id="wooac-checkout" href="' . wc_get_checkout_url() . '">' . self::localization( 'checkout', esc_html__( 'Checkout', 'woo-added-to-cart-notification' ) ) . '</a>' );
									}

									if ( self::get_setting( 'show_continue_shopping', 'yes' ) === 'yes' ) {
										echo apply_filters( 'wooac_continue', '<a id="wooac-continue" href="#" data-url="' . self::get_setting( 'continue_url' ) . '">' . self::localization( 'continue', esc_html__( 'Continue shopping', 'woo-added-to-cart-notification' ) ) . '</a>' );
									}

									do_action( 'wooac_action_after' );
									echo '</div>';
								}

								if ( $layout === 'horizontal' ) {
									echo '</div><!-- /wooac-content -->';
									echo '</div><!-- /wooac-popup-inner -->';
								}
							}
						}

						$product = $global_product;
					}

					do_action( 'wooac_wrap_after' );
					echo '</div>';
					do_action( 'wooac_wrap_below' );

					return apply_filters( 'wooac_popup_html', ob_get_clean(), $items, $layout, $suggested_products );
				}

				function add_to_cart( $cart_item_key ) {
					if ( isset( WC()->cart->cart_contents[ $cart_item_key ]['woosb_parent_id'] ) || isset( WC()->cart->cart_contents[ $cart_item_key ]['wooco_parent_id'] ) || isset( WC()->cart->cart_contents[ $cart_item_key ]['woobt_parent_id'] ) || isset( WC()->cart->cart_contents[ $cart_item_key ]['woofs_parent_id'] ) ) {
						// prevent bundled products and composite products
						WC()->cart->cart_contents[ $cart_item_key ]['wooac_time'] = time() - 10000;
					} else {
						WC()->cart->cart_contents[ $cart_item_key ]['wooac_time'] = time();
					}
				}

				function add_to_cart_fragments( $fragments ) {
					$style = self::get_setting( 'style', 'default' );

					switch ( $style ) {
						case 'notiny':
							$fragments['.wooac-notiny-added'] = self::get_notiny();
							break;
						default:
							$fragments['.wooac-popup-added'] = self::get_popup();
					}

					return $fragments;
				}

				function footer() {
					if ( is_admin() ) {
						return;
					}

					$style       = self::get_setting( 'style', 'default' );
					$layout      = self::get_setting( 'layout', 'vertical' );
					$show_adding = self::get_setting( 'show_adding', 'no' ) === 'yes';
					$show_image  = self::get_setting( 'show_image', 'yes' ) === 'yes';

					switch ( $style ) {
						case 'notiny':
							if ( $show_adding ) {
								echo '<div class="wooac-wrapper-adding wooac-notiny wooac-notiny-adding" style="display: none;">';

								if ( $show_image ) {
									echo '<div class="wooac-image"><img alt="" class="wooac-product-image" src="' . wc_placeholder_img_src() . '"/></div>';
								}

								echo '<div class="wooac-text">' . sprintf( self::localization( 'adding', esc_html__( '%s is being added to the cart...', 'woo-added-to-cart-notification' ) ), '<span class="wooac-product-name"></span>' ) . '</div>';
								echo '</div>';
							}

							echo '<div class="wooac-wrapper wooac-notiny wooac-notiny-added" style="display: none;"></div>';
							break;
						default:
							if ( $show_adding ) {
								echo '<div class="' . esc_attr( 'wooac-wrapper-adding wooac-popup wooac-popup-adding mfp-with-anim wooac-popup-' . $layout ) . '">';

								if ( $layout === 'horizontal' ) {
									echo '<div class="wooac-popup-inner">';
								}

								if ( $show_image ) {
									echo '<div class="wooac-image"><img alt="" class="wooac-product-image" src="' . wc_placeholder_img_src() . '"/></div>';
								}

								if ( $layout === 'horizontal' ) {
									echo '<div class="wooac-content">';
								}

								echo '<div class="wooac-text">' . sprintf( self::localization( 'adding', esc_html__( '%s is being added to the cart...', 'woo-added-to-cart-notification' ) ), '<span class="wooac-product-name"></span>' ) . '</div>';

								if ( $layout === 'horizontal' ) {
									echo '</div><!-- /wooac-content -->';
									echo '</div><!-- /wooac-popup-inner -->';
								}

								echo '</div>';
							}

							echo '<div class="' . esc_attr( 'wooac-wrapper wooac-popup wooac-popup-added mfp-with-anim wooac-popup-' . $layout ) . '"></div>';
					}
				}

				function wpcsm_locations( $locations ) {
					$locations['WPC Added To Cart Notification'] = [
						'wooac_wrap_before'               => esc_html__( 'Before wrapper', 'woo-added-to-cart-notification' ),
						'wooac_wrap_after'                => esc_html__( 'After wrapper', 'woo-added-to-cart-notification' ),
						'wooac_image_before'              => esc_html__( 'Before image', 'woo-added-to-cart-notification' ),
						'wooac_image_after'               => esc_html__( 'After image', 'woo-added-to-cart-notification' ),
						'wooac_text_before'               => esc_html__( 'Before text', 'woo-added-to-cart-notification' ),
						'wooac_text_after'                => esc_html__( 'After text', 'woo-added-to-cart-notification' ),
						'wooac_suggested_before'          => esc_html__( 'Before suggested wrapper', 'woo-added-to-cart-notification' ),
						'wooac_suggested_after'           => esc_html__( 'After suggested wrapper', 'woo-added-to-cart-notification' ),
						'wooac_suggested_products_before' => esc_html__( 'Before suggested products', 'woo-added-to-cart-notification' ),
						'wooac_suggested_products_after'  => esc_html__( 'After suggested products', 'woo-added-to-cart-notification' ),
						'wooac_action_before'             => esc_html__( 'Before action buttons', 'woo-added-to-cart-notification' ),
						'wooac_action_after'              => esc_html__( 'After action buttons', 'woo-added-to-cart-notification' ),
					];

					return $locations;
				}
			}

			return WPCleverWooac::instance();
		}

		return null;
	}
}

if ( ! function_exists( 'wooac_notice_wc' ) ) {
	function wooac_notice_wc() {
		?>
        <div class="error">
            <p><strong>WPC Added To Cart Notification</strong> requires WooCommerce version 3.0 or greater.</p>
        </div>
		<?php
	}
}
