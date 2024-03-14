<?php
/*
Plugin Name: WPC Force Sells for WooCommerce
Plugin URI: https://wpclever.net/
Description: Create a deal that combines various related products and put them for sale altogether.
Version: 6.1.6
Author: WPClever
Author URI: https://wpclever.net
Text Domain: wpc-force-sells
Domain Path: /languages/
Requires Plugins: woocommerce
Requires at least: 4.0
Tested up to: 6.4
WC requires at least: 3.0
WC tested up to: 8.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) || exit;

! defined( 'WOOFS_VERSION' ) && define( 'WOOFS_VERSION', '6.1.6' );
! defined( 'WOOFS_LITE' ) && define( 'WOOFS_LITE', __FILE__ );
! defined( 'WOOFS_FILE' ) && define( 'WOOFS_FILE', __FILE__ );
! defined( 'WOOFS_URI' ) && define( 'WOOFS_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WOOFS_DIR' ) && define( 'WOOFS_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'WOOFS_SUPPORT' ) && define( 'WOOFS_SUPPORT', 'https://wpclever.net/support?utm_source=support&utm_medium=woofs&utm_campaign=wporg' );
! defined( 'WOOFS_REVIEWS' ) && define( 'WOOFS_REVIEWS', 'https://wordpress.org/support/plugin/wpc-force-sells/reviews/?filter=5' );
! defined( 'WOOFS_CHANGELOG' ) && define( 'WOOFS_CHANGELOG', 'https://wordpress.org/plugins/wpc-force-sells/#developers' );
! defined( 'WOOFS_DISCUSSION' ) && define( 'WOOFS_DISCUSSION', 'https://wordpress.org/support/plugin/wpc-force-sells' );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WOOFS_URI );

include 'includes/dashboard/wpc-dashboard.php';
include 'includes/kit/wpc-kit.php';
include 'includes/hpos.php';

if ( ! function_exists( 'woofs_init' ) ) {
	add_action( 'plugins_loaded', 'woofs_init', 11 );

	function woofs_init() {
		load_plugin_textdomain( 'wpc-force-sells', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'woofs_notice_wc' );

			return null;
		}

		if ( ! class_exists( 'WPCleverWoofs' ) && class_exists( 'WC_Product' ) ) {
			class WPCleverWoofs {
				protected static $settings = [];
				protected static $localization = [];
				protected static $types = [ 'simple' ];
				protected static $image_size = 'woocommerce_thumbnail';
				protected static $instance = null;

				public static function instance() {
					if ( is_null( self::$instance ) ) {
						self::$instance = new self();
					}

					return self::$instance;
				}

				function __construct() {
					self::$settings     = (array) get_option( 'woofs_settings', [] );
					self::$localization = (array) get_option( 'woofs_localization', [] );

					// Init
					add_action( 'init', [ $this, 'init' ] );

					// Add image to variation
					add_filter( 'woocommerce_available_variation', [ $this, 'available_variation' ], 10, 3 );

					// Settings
					add_action( 'admin_init', [ $this, 'register_settings' ] );
					add_action( 'admin_menu', [ $this, 'admin_menu' ] );

					// Enqueue frontend scripts
					add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

					// Enqueue backend scripts
					add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

					// Backend AJAX
					add_action( 'wp_ajax_woofs_update_search_settings', [ $this, 'ajax_update_search_settings' ] );
					add_action( 'wp_ajax_woofs_get_search_results', [ $this, 'ajax_get_search_results' ] );

					// Product settings
					add_filter( 'woocommerce_product_data_tabs', [ $this, 'product_data_tabs' ] );
					add_action( 'woocommerce_product_data_panels', [ $this, 'product_data_panels' ] );
					add_action( 'woocommerce_process_product_meta', [ $this, 'process_product_meta' ] );

					// Price class
					add_filter( 'woocommerce_product_price_class', [ $this, 'product_price_class' ] );

					// Add to cart form & button
					if ( self::get_setting( 'position', 'before' ) === 'before' ) {
						add_action( 'woocommerce_before_add_to_cart_form', [ $this, 'add_to_cart_form' ] );
					} else {
						add_action( 'woocommerce_after_add_to_cart_form', [ $this, 'add_to_cart_form' ] );
					}

					add_action( 'woocommerce_before_add_to_cart_button', [ $this, 'add_to_cart_button' ] );

					// Add to cart
					add_filter( 'woocommerce_add_to_cart_validation', [ $this, 'add_to_cart_validation' ], 10, 2 );
					add_action( 'woocommerce_add_to_cart', [ $this, 'add_to_cart' ], 10, 6 );
					add_filter( 'woocommerce_add_cart_item_data', [ $this, 'add_cart_item_data' ], 10, 2 );
					add_filter( 'woocommerce_get_cart_item_from_session', [
						$this,
						'get_cart_item_from_session'
					], 10, 2 );

					// Count
					add_filter( 'woocommerce_cart_contents_count', [ $this, 'cart_contents_count' ] );
					add_filter( 'woocommerce_get_item_count', [ $this, 'get_item_count' ], 10, 3 );

					// Cart item
					add_filter( 'woocommerce_cart_item_remove_link', [ $this, 'cart_item_remove_link' ], 10, 2 );
					add_filter( 'woocommerce_cart_item_name', [ $this, 'cart_item_name' ], 10, 2 );
					add_filter( 'woocommerce_cart_item_quantity', [ $this, 'cart_item_quantity' ], 10, 3 );
					add_action( 'woocommerce_cart_item_removed', [ $this, 'cart_item_removed' ], 10, 2 );

					// Order item
					add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'order_line_item' ], 10, 3 );
					add_filter( 'woocommerce_order_item_name', [ $this, 'cart_item_name' ], 10, 2 );

					if ( self::get_setting( 'hide_linked_order', 'no' ) === 'yes_text' || self::get_setting( 'hide_linked_order', 'no' ) === 'yes_list' ) {
						// Hide linked products, just show the main product on order details (order confirmation or emails)
						add_action( 'woocommerce_order_item_meta_start', [ $this, 'order_item_meta_start' ], 10, 2 );
					}

					// Admin order
					add_filter( 'woocommerce_hidden_order_itemmeta', [ $this, 'hidden_order_itemmeta' ] );
					add_action( 'woocommerce_before_order_itemmeta', [ $this, 'before_order_itemmeta' ], 10, 2 );

					// Hide on cart & checkout page
					add_filter( 'woocommerce_cart_item_visible', [ $this, 'cart_item_visible' ], 10, 2 );
					add_filter( 'woocommerce_checkout_cart_item_visible', [ $this, 'cart_item_visible' ], 10, 2 );

					// Get item data
					if ( self::get_setting( 'hide_linked', 'no' ) === 'yes_text' || self::get_setting( 'hide_linked', 'no' ) === 'yes_list' ) {
						add_filter( 'woocommerce_get_item_data', [ $this, 'get_item_data' ], 10, 2 );
					}

					// Hide on mini-cart
					add_filter( 'woocommerce_widget_cart_item_visible', [ $this, 'mini_cart_item_visible' ], 10, 2 );

					// Hide on order details
					add_filter( 'woocommerce_order_item_visible', [ $this, 'order_item_visible' ], 10, 2 );

					// Admin
					add_filter( 'display_post_states', [ $this, 'display_post_states' ], 10, 2 );

					// Add settings link
					add_filter( 'plugin_action_links', [ $this, 'action_links' ], 10, 2 );
					add_filter( 'plugin_row_meta', [ $this, 'row_meta' ], 10, 2 );

					// Cart contents
					add_action( 'woocommerce_before_mini_cart_contents', [ $this, 'before_mini_cart_contents' ], 9999 );
					add_action( 'woocommerce_before_calculate_totals', [ $this, 'before_calculate_totals' ], 9999 );

					// Search filters
					if ( self::get_setting( 'search_sku', 'no' ) === 'yes' ) {
						add_filter( 'pre_get_posts', [ $this, 'search_sku' ], 99 );
					}
					if ( self::get_setting( 'search_exact', 'no' ) === 'yes' ) {
						add_action( 'pre_get_posts', [ $this, 'search_exact' ], 99 );
					}
					if ( self::get_setting( 'search_sentence', 'no' ) === 'yes' ) {
						add_action( 'pre_get_posts', [ $this, 'search_sentence' ], 99 );
					}

					// WPML
					if ( function_exists( 'wpml_loaded' ) ) {
						add_filter( 'woofs_item_id', [ $this, 'wpml_item_id' ], 99 );
					}

					// Admin product filter
					add_filter( 'woocommerce_products_admin_list_table_filters', [ $this, 'product_filter' ] );
					add_action( 'pre_get_posts', [ $this, 'apply_product_filter' ] );

					// WPC Smart Messages
					add_filter( 'wpcsm_locations', [ $this, 'wpcsm_locations' ] );
				}

				public static function get_settings() {
					return apply_filters( 'woofs_get_settings', self::$settings );
				}

				public static function get_setting( $name, $default = false ) {
					if ( ! empty( self::$settings ) && isset( self::$settings[ $name ] ) ) {
						$setting = self::$settings[ $name ];
					} else {
						$setting = get_option( '_woofs_' . $name, $default );
					}

					return apply_filters( 'woofs_get_setting', $setting, $name, $default );
				}

				public static function localization( $key = '', $default = '' ) {
					$str = '';

					if ( ! empty( $key ) && ! empty( self::$localization[ $key ] ) ) {
						$str = self::$localization[ $key ];
					} elseif ( ! empty( $default ) ) {
						$str = $default;
					}

					return apply_filters( 'woofs_localization_' . $key, $str );
				}

				function init() {
					self::$types      = (array) apply_filters( 'woofs_product_types', self::$types );
					self::$image_size = apply_filters( 'woofs_image_size', self::$image_size );
				}

				function available_variation( $data, $variable, $variation ) {
					if ( $image_id = $variation->get_image_id() ) {
						$data['woofs_image'] = wp_get_attachment_image( $image_id, self::$image_size );
					}

					return $data;
				}

				function register_settings() {
					// settings
					register_setting( 'woofs_settings', 'woofs_settings' );

					// localization
					register_setting( 'woofs_localization', 'woofs_localization' );
				}

				function admin_menu() {
					add_submenu_page( 'wpclever', esc_html__( 'WPC Force Sells', 'wpc-force-sells' ), esc_html__( 'Force Sells', 'wpc-force-sells' ), 'manage_options', 'wpclever-woofs', [
						$this,
						'admin_menu_content'
					] );
				}

				function admin_menu_content() {
					add_thickbox();
					$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'settings';
					?>
                    <div class="wpclever_settings_page wrap">
                        <h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Force Sells', 'wpc-force-sells' ) . ' ' . WOOFS_VERSION . ' ' . ( defined( 'WOOFS_PREMIUM' ) ? '<span class="premium" style="display: none">' . esc_html__( 'Premium', 'wpc-force-sells' ) . '</span>' : '' ); ?></h1>
                        <div class="wpclever_settings_page_desc about-text">
                            <p>
								<?php printf( /* translators: %s is the stars */ esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'wpc-force-sells' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
                                <br/>
                                <a href="<?php echo esc_url( WOOFS_REVIEWS ); ?>" target="_blank"><?php esc_html_e( 'Reviews', 'wpc-force-sells' ); ?></a> |
                                <a href="<?php echo esc_url( WOOFS_CHANGELOG ); ?>" target="_blank"><?php esc_html_e( 'Changelog', 'wpc-force-sells' ); ?></a> |
                                <a href="<?php echo esc_url( WOOFS_DISCUSSION ); ?>" target="_blank"><?php esc_html_e( 'Discussion', 'wpc-force-sells' ); ?></a>
                            </p>
                        </div>
						<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) { ?>
                            <div class="notice notice-success is-dismissible">
                                <p><?php esc_html_e( 'Settings updated.', 'wpc-force-sells' ); ?></p>
                            </div>
						<?php } ?>
                        <div class="wpclever_settings_page_nav">
                            <h2 class="nav-tab-wrapper">
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woofs&tab=how' ); ?>" class="<?php echo esc_attr( $active_tab === 'how' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'How to use?', 'wpc-force-sells' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woofs&tab=settings' ); ?>" class="<?php echo esc_attr( $active_tab === 'settings' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Settings', 'wpc-force-sells' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woofs&tab=localization' ); ?>" class="<?php echo esc_attr( $active_tab === 'localization' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Localization', 'wpc-force-sells' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woofs&tab=premium' ); ?>" class="<?php echo esc_attr( $active_tab === 'premium' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>" style="color: #c9356e;">
									<?php esc_html_e( 'Premium Version', 'wpc-force-sells' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-kit' ); ?>" class="nav-tab">
									<?php esc_html_e( 'Essential Kit', 'wpc-force-sells' ); ?>
                                </a>
                            </h2>
                        </div>
                        <div class="wpclever_settings_page_content">
							<?php if ( $active_tab === 'how' ) { ?>
                                <div class="wpclever_settings_page_content_text">
                                    <p>
										<?php esc_html_e( 'When adding/editing the product you can choose Force Sells tab then add some products with the new price and quantity.', 'wpc-force-sells' ); ?>
                                    </p>
                                </div>
							<?php } elseif ( $active_tab === 'settings' ) {
								$position              = self::get_setting( 'position', 'before' );
								$layout                = self::get_setting( 'layout', 'list' );
								$show_thumb            = self::get_setting( 'show_thumb', 'yes' );
								$show_price            = self::get_setting( 'show_price', 'yes' );
								$show_description      = self::get_setting( 'show_description', 'no' );
								$link                  = self::get_setting( 'link', 'yes' );
								$variations_selector   = self::get_setting( 'variations_selector', 'default' );
								$change_image          = self::get_setting( 'change_image', 'yes' );
								$change_price          = self::get_setting( 'change_price', 'yes' );
								$hide_linked           = self::get_setting( 'hide_linked', 'no' );
								$hide_linked_mini_cart = self::get_setting( 'hide_linked_mini_cart', 'no' );
								$hide_linked_order     = self::get_setting( 'hide_linked_order', 'no' );
								$exclude_unpurchasable = self::get_setting( 'exclude_unpurchasable', 'no' );
								$cart_contents_count   = self::get_setting( 'cart_contents_count', 'both' );
								?>
                                <form method="post" action="options.php">
                                    <table class="form-table">
                                        <tr class="heading">
                                            <th colspan="2">
												<?php esc_html_e( 'General', 'wpc-force-sells' ); ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Position', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <select name="woofs_settings[position]">
                                                    <option value="before" <?php selected( $position, 'before' ); ?>><?php esc_html_e( 'Above add to cart button', 'wpc-force-sells' ); ?></option>
                                                    <option value="after" <?php selected( $position, 'after' ); ?>><?php esc_html_e( 'Under add to cart button', 'wpc-force-sells' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Choose the position to show the products list.', 'wpc-force-sells' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Layout', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <select name="woofs_settings[layout]">
                                                    <option value="list" <?php selected( $layout, 'list' ); ?>><?php esc_html_e( 'List', 'wpc-force-sells' ); ?></option>
                                                    <option value="grid-2" <?php selected( $layout, 'grid-2' ); ?>><?php esc_html_e( 'Grid - 2 columns', 'wpc-force-sells' ); ?></option>
                                                    <option value="grid-3" <?php selected( $layout, 'grid-3' ); ?>><?php esc_html_e( 'Grid - 3 columns', 'wpc-force-sells' ); ?></option>
                                                    <option value="grid-4" <?php selected( $layout, 'grid-4' ); ?>><?php esc_html_e( 'Grid - 4 columns', 'wpc-force-sells' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Show thumbnail', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <select name="woofs_settings[show_thumb]">
                                                    <option value="yes" <?php selected( $show_thumb, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-force-sells' ); ?></option>
                                                    <option value="no" <?php selected( $show_thumb, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-force-sells' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Show price', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <select name="woofs_settings[show_price]">
                                                    <option value="yes" <?php selected( $show_price, 'yes' ); ?>><?php esc_html_e( 'Price', 'wpc-force-sells' ); ?></option>
                                                    <option value="total" <?php selected( $show_price, 'total' ); ?>><?php esc_html_e( 'Total', 'wpc-force-sells' ); ?></option>
                                                    <option value="no" <?php selected( $show_price, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-force-sells' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Show short description', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <select name="woofs_settings[show_description]">
                                                    <option value="yes" <?php selected( $show_description, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-force-sells' ); ?></option>
                                                    <option value="no" <?php selected( $show_description, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-force-sells' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Link to individual product', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <select name="woofs_settings[link]">
                                                    <option value="yes" <?php selected( $link, 'yes' ); ?>><?php esc_html_e( 'Yes, open in the same tab', 'wpc-force-sells' ); ?></option>
                                                    <option value="yes_blank" <?php selected( $link, 'yes_blank' ); ?>><?php esc_html_e( 'Yes, open in the new tab', 'wpc-force-sells' ); ?></option>
                                                    <option value="yes_popup" <?php selected( $link, 'yes_popup' ); ?>><?php esc_html_e( 'Yes, open quick view popup', 'wpc-force-sells' ); ?></option>
                                                    <option value="no" <?php selected( $link, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-force-sells' ); ?></option>
                                                </select> <span class="description">If you choose "Open quick view popup", please install <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=woo-smart-quick-view&TB_iframe=true&width=800&height=550' ) ); ?>" class="thickbox" title="WPC Smart Quick View">WPC Smart Quick View</a> to make it work.</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Variations selector', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <select name="woofs_settings[variations_selector]">
                                                    <option value="default" <?php selected( $variations_selector, 'default' ); ?>><?php esc_html_e( 'Default', 'wpc-force-sells' ); ?></option>
                                                    <option value="woovr" <?php selected( $variations_selector, 'woovr' ); ?>><?php esc_html_e( 'Use WPC Variations Radio Buttons', 'wpc-force-sells' ); ?></option>
                                                </select> <span class="description">If you choose "Use WPC Variations Radio Buttons", please install <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=wpc-variations-radio-buttons&TB_iframe=true&width=800&height=550' ) ); ?>" class="thickbox" title="WPC Variations Radio Buttons">WPC Variations Radio Buttons</a> to make it work.</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Change image', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <select name="woofs_settings[change_image]">
                                                    <option value="yes" <?php selected( $change_image, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-force-sells' ); ?></option>
                                                    <option value="no" <?php selected( $change_image, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-force-sells' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Change the main product image when choosing the variation of variable products.', 'wpc-force-sells' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Change price', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <select name="woofs_settings[change_price]" class="woofs_change_price">
                                                    <option value="yes" <?php selected( $change_price, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-force-sells' ); ?></option>
                                                    <option value="yes_custom" <?php selected( $change_price, 'yes_custom' ); ?>><?php esc_html_e( 'Yes, custom selector', 'wpc-force-sells' ); ?></option>
                                                    <option value="no" <?php selected( $change_price, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-force-sells' ); ?></option>
                                                </select>
                                                <input type="text" class="woofs_change_price_custom" name="woofs_settings[change_price_custom]" value="<?php echo self::get_setting( 'change_price_custom', '.summary > .price' ); ?>" placeholder=".summary > .price"/>
                                                <span class="description"><?php esc_html_e( 'Change the main product price when choosing the variation of variable products. It uses JavaScript to change product price so it is very dependent on theme’s HTML. If it cannot find and update the product price, please contact us and we can help you adjust the JS file.', 'wpc-force-sells' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th colspan="2">
												<?php esc_html_e( 'Cart & Checkout', 'wpc-force-sells' ); ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Exclude unpurchasable', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <select name="woofs_settings[exclude_unpurchasable]">
                                                    <option value="yes" <?php selected( $exclude_unpurchasable, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-force-sells' ); ?></option>
                                                    <option value="no" <?php selected( $exclude_unpurchasable, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-force-sells' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Make the product still purchasable when one of the linked products is un-purchasable. These linked products are excluded from the orders.', 'wpc-force-sells' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Cart contents count', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <select name="woofs_settings[cart_contents_count]">
                                                    <option value="main" <?php selected( $cart_contents_count, 'main' ); ?>><?php esc_html_e( 'Main products only', 'wpc-force-sells' ); ?></option>
                                                    <option value="linked" <?php selected( $cart_contents_count, 'linked' ); ?>><?php esc_html_e( 'Linked products only', 'wpc-force-sells' ); ?></option>
                                                    <option value="both" <?php selected( $cart_contents_count, 'both' ); ?>><?php esc_html_e( 'Both main and linked products', 'wpc-force-sells' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="show_if_section_none">
                                            <th><?php esc_html_e( 'Hide linked products on mini-cart', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <select name="woofs_settings[hide_linked_mini_cart]">
                                                    <option value="yes" <?php selected( $hide_linked_mini_cart, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-force-sells' ); ?></option>
                                                    <option value="no" <?php selected( $hide_linked_mini_cart, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-force-sells' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Hide linked products, just show the main product on mini-cart.', 'wpc-force-sells' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="show_if_section_none">
                                            <th><?php esc_html_e( 'Hide linked products on cart & checkout page', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <select name="woofs_settings[hide_linked]">
                                                    <option value="yes" <?php selected( $hide_linked, 'yes' ); ?>><?php esc_html_e( 'Yes, just show the main product', 'wpc-force-sells' ); ?></option>
                                                    <option value="yes_text" <?php selected( $hide_linked, 'yes_text' ); ?>><?php esc_html_e( 'Yes, but shortly list linked sub-product names under the main product in one line', 'wpc-force-sells' ); ?></option>
                                                    <option value="yes_list" <?php selected( $hide_linked, 'yes_list' ); ?>><?php esc_html_e( 'Yes, but list linked sub-product names under the main product in separate lines', 'wpc-force-sells' ); ?></option>
                                                    <option value="no" <?php selected( $hide_linked, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-force-sells' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="show_if_section_none">
                                            <th><?php esc_html_e( 'Hide linked products on order details', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <select name="woofs_settings[hide_linked_order]">
                                                    <option value="yes" <?php selected( $hide_linked_order, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-force-sells' ); ?></option>
                                                    <option value="yes_text" <?php selected( $hide_linked_order, 'yes_text' ); ?>><?php esc_html_e( 'Yes, but shortly list linked sub-product names under the main product in one line', 'wpc-force-sells' ); ?></option>
                                                    <option value="yes_list" <?php selected( $hide_linked_order, 'yes_list' ); ?>><?php esc_html_e( 'Yes, but list linked sub-product names under the main product in separate lines', 'wpc-force-sells' ); ?></option>
                                                    <option value="no" <?php selected( $hide_linked_order, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-force-sells' ); ?></option>
                                                </select>
                                                <p class="description"><?php esc_html_e( 'Hide linked products, just show the main product on order details (order confirmation or emails).', 'wpc-force-sells' ); ?></p>
                                            </td>
                                        </tr>
                                        <tr class="heading" id="search">
                                            <th colspan="2">
												<?php esc_html_e( 'Search', 'wpc-force-sells' ); ?>
                                            </th>
                                        </tr>
										<?php self::search_settings(); ?>
                                        <tr class="heading">
                                            <th colspan="2"><?php esc_html_e( 'Suggestion', 'wpc-force-sells' ); ?></th>
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
												<?php settings_fields( 'woofs_settings' ); ?><?php submit_button(); ?>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'localization' ) { ?>
                                <form method="post" action="options.php">
                                    <table class="form-table">
                                        <tr class="heading">
                                            <th scope="row"><?php esc_html_e( 'General', 'wpc-force-sells' ); ?></th>
                                            <td>
												<?php esc_html_e( 'Leave blank to use the default text and its equivalent translation in multiple languages.', 'wpc-force-sells' ); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Additional price', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="woofs_localization[additional]" value="<?php echo esc_attr( self::localization( 'additional' ) ); ?>" placeholder="<?php esc_attr_e( 'Additional price:', 'wpc-force-sells' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Total price', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="woofs_localization[total]" value="<?php echo esc_attr( self::localization( 'total' ) ); ?>" placeholder="<?php esc_attr_e( 'Total:', 'wpc-force-sells' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Linked', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="woofs_localization[linked]" value="<?php echo esc_attr( self::localization( 'linked' ) ); ?>" placeholder="<?php esc_attr_e( '(linked to %s)', 'wpc-force-sells' ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'The text behind force sells products. Use "%s" for the main product name.', 'wpc-force-sells' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Choose an attribute', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="woofs_localization[choose]" value="<?php echo esc_attr( self::localization( 'choose' ) ); ?>" placeholder="<?php esc_attr_e( 'Choose %s', 'wpc-force-sells' ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Use %s to show the attribute name.', 'wpc-force-sells' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Clear', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="woofs_localization[clear]" value="<?php echo esc_attr( self::localization( 'clear' ) ); ?>" placeholder="<?php esc_attr_e( 'Clear', 'wpc-force-sells' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Default above text', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <input type="text" class="large-text" name="woofs_localization[above_text]" value="<?php echo esc_attr( self::localization( 'above_text' ) ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'The default text above products list. You can overwrite it in product settings.', 'wpc-force-sells' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Default under text', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <input type="text" class="large-text" name="woofs_localization[under_text]" value="<?php echo esc_attr( self::localization( 'under_text' ) ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'The default text under products list. You can overwrite it in product settings.', 'wpc-force-sells' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th scope="row"><?php esc_html_e( 'Cart & Checkout', 'wpc-force-sells' ); ?></th>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Linked products', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="woofs_localization[linked_products]" value="<?php echo esc_attr( self::localization( 'linked_products' ) ); ?>" placeholder="<?php esc_attr_e( 'Linked products', 'wpc-force-sells' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Linked products: %s', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="woofs_localization[linked_products_s]" value="<?php echo esc_attr( self::localization( 'linked_products_s' ) ); ?>" placeholder="<?php esc_attr_e( 'Linked products: %s', 'wpc-force-sells' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th colspan="2">
												<?php esc_html_e( 'Alert', 'wpc-force-sells' ); ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Require selection', 'wpc-force-sells' ); ?></th>
                                            <td>
                                                <input type="text" class="large-text" name="woofs_localization[alert_selection]" value="<?php echo esc_attr( self::localization( 'alert_selection' ) ); ?>" placeholder="<?php esc_attr_e( 'Please select a purchasable variation for [name] before adding this product to the cart.', 'wpc-force-sells' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr class="submit">
                                            <th colspan="2">
												<?php settings_fields( 'woofs_localization' ); ?><?php submit_button(); ?>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'premium' ) { ?>
                                <div class="wpclever_settings_page_content_text">
                                    <p>
                                        Get the Premium Version just $29!
                                        <a href="https://wpclever.net/downloads/force-sells?utm_source=pro&utm_medium=woofs&utm_campaign=wporg" target="_blank">https://wpclever.net/downloads/force-sells</a>
                                    </p>
                                    <p><strong>Extra features for Premium Version:</strong></p>
                                    <ul style="margin-bottom: 0">
                                        <li>- Add a variable product or a specific variation of a product.</li>
                                        <li>- Insert heading/paragraph into products list.</li>
                                        <li>- Get the lifetime update & premium support.</li>
                                    </ul>
                                </div>
							<?php } ?>
                        </div>
                    </div>
					<?php
				}

				function search_settings() {
					$search_sku      = self::get_setting( 'search_sku', 'no' );
					$search_id       = self::get_setting( 'search_id', 'no' );
					$search_exact    = self::get_setting( 'search_exact', 'no' );
					$search_sentence = self::get_setting( 'search_sentence', 'no' );
					$search_same     = self::get_setting( 'search_same', 'no' );
					?>
                    <tr>
                        <th><?php esc_html_e( 'Search limit', 'wpc-force-sells' ); ?></th>
                        <td>
                            <input class="woofs_search_limit" type="number" min="1" max="500" name="woofs_settings[search_limit]" value="<?php echo esc_attr( self::get_setting( 'search_limit', 10 ) ); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Search by SKU', 'wpc-force-sells' ); ?></th>
                        <td>
                            <select name="woofs_settings[search_sku]" class="woofs_search_sku">
                                <option value="yes" <?php selected( $search_sku, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-force-sells' ); ?></option>
                                <option value="no" <?php selected( $search_sku, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-force-sells' ); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Search by ID', 'wpc-force-sells' ); ?></th>
                        <td>
                            <select name="woofs_settings[search_id]" class="woofs_search_id">
                                <option value="yes" <?php selected( $search_id, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-force-sells' ); ?></option>
                                <option value="no" <?php selected( $search_id, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-force-sells' ); ?></option>
                            </select>
                            <span class="description"><?php esc_html_e( 'Search by ID when entering the numeric only.', 'wpc-force-sells' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Search exact', 'wpc-force-sells' ); ?></th>
                        <td>
                            <select name="woofs_settings[search_exact]" class="woofs_search_exact">
                                <option value="yes" <?php selected( $search_exact, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-force-sells' ); ?></option>
                                <option value="no" <?php selected( $search_exact, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-force-sells' ); ?></option>
                            </select>
                            <span class="description"><?php esc_html_e( 'Match whole product title or content?', 'wpc-force-sells' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Search sentence', 'wpc-force-sells' ); ?></th>
                        <td>
                            <select name="woofs_settings[search_sentence]" class="woofs_search_sentence">
                                <option value="yes" <?php selected( $search_sentence, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-force-sells' ); ?></option>
                                <option value="no" <?php selected( $search_sentence, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-force-sells' ); ?></option>
                            </select>
                            <span class="description"><?php esc_html_e( 'Do a phrase search?', 'wpc-force-sells' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Accept same products', 'wpc-force-sells' ); ?></th>
                        <td>
                            <select name="woofs_settings[search_same]" class="woofs_search_same">
                                <option value="yes" <?php selected( $search_same, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-force-sells' ); ?></option>
                                <option value="no" <?php selected( $search_same, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-force-sells' ); ?></option>
                            </select>
                            <span class="description"><?php esc_html_e( 'If yes, a product can be added many times.', 'wpc-force-sells' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Product types', 'wpc-force-sells' ); ?></th>
                        <td>
							<?php
							$search_types  = self::get_setting( 'search_types', [ 'all' ] );
							$product_types = array_merge( [ 'all' => esc_html__( 'All', 'wpc-force-sells' ) ], wc_get_product_types() );

							$key_pos = array_search( 'variable', array_keys( $product_types ) );

							if ( $key_pos !== false ) {
								$key_pos ++;
								$second_array  = array_splice( $product_types, $key_pos );
								$product_types = array_merge( $product_types, [ 'variation' => esc_html__( ' → Variation', 'wpc-force-sells' ) ], $second_array );
							}

							echo '<select name="woofs_settings[search_types][]" class="woofs_search_types" multiple style="width: 200px; height: 150px;">';

							foreach ( $product_types as $key => $name ) {
								echo '<option value="' . esc_attr( $key ) . '" ' . ( in_array( $key, $search_types, true ) ? 'selected' : '' ) . '>' . esc_html( $name ) . '</option>';
							}

							echo '</select>';
							?>
                        </td>
                    </tr>
					<?php
				}

				function enqueue_scripts() {
					wp_enqueue_style( 'woofs-frontend', WOOFS_URI . 'assets/css/frontend.css', [], WOOFS_VERSION );
					wp_enqueue_script( 'woofs-frontend', WOOFS_URI . 'assets/js/frontend.js', [ 'jquery' ], WOOFS_VERSION, true );
					wp_localize_script( 'woofs-frontend', 'woofs_vars', apply_filters( 'woofs_vars', [
							'price_decimals'           => wc_get_price_decimals(),
							'price_format'             => get_woocommerce_price_format(),
							'price_thousand_separator' => wc_get_price_thousand_separator(),
							'price_decimal_separator'  => wc_get_price_decimal_separator(),
							'currency_symbol'          => get_woocommerce_currency_symbol(),
							'position'                 => self::get_setting( 'position', 'before' ),
							'show_price'               => self::get_setting( 'show_price', 'yes' ),
							'change_image'             => self::get_setting( 'change_image', 'yes' ),
							'change_price'             => self::get_setting( 'change_price', 'yes' ),
							'price_selector'           => self::get_setting( 'change_price_custom', '' ),
							'variations_selector'      => self::get_setting( 'variations_selector', 'default' ),
							'additional_text'          => self::localization( 'additional', esc_html__( 'Additional price:', 'wpc-force-sells' ) ),
							'total_text'               => self::localization( 'total', esc_html__( 'Total:', 'wpc-force-sells' ) ),
							'alert_selection'          => self::localization( 'alert_selection', esc_html__( 'Please select a purchasable variation for [name] before adding this product to the cart.', 'wpc-force-sells' ) ),
						] )
					);
				}

				function admin_enqueue_scripts( $hook ) {
					if ( apply_filters( 'woofs_ignore_backend_scripts', false, $hook ) ) {
						return null;
					}

					wp_enqueue_style( 'hint', WOOFS_URI . 'assets/css/hint.css' );
					wp_enqueue_style( 'woofs-backend', WOOFS_URI . 'assets/css/backend.css', [], WOOFS_VERSION );
					wp_enqueue_script( 'woofs-backend', WOOFS_URI . 'assets/js/backend.js', [
						'jquery',
						'jquery-ui-dialog',
						'jquery-ui-sortable'
					], WOOFS_VERSION, true );
					wp_localize_script( 'woofs-backend', 'woofs_vars', [
							'nonce' => wp_create_nonce( 'woofs-security' ),
						]
					);
				}

				function action_links( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$settings             = '<a href="' . admin_url( 'admin.php?page=wpclever-woofs&tab=settings' ) . '">' . esc_html__( 'Settings', 'wpc-force-sells' ) . '</a>';
						$links['wpc-premium'] = '<a href="' . admin_url( 'admin.php?page=wpclever-woofs&tab=premium' ) . '">' . esc_html__( 'Premium Version', 'wpc-force-sells' ) . '</a>';
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
							'support' => '<a href="' . esc_url( WOOFS_DISCUSSION ) . '" target="_blank">' . esc_html__( 'Community support', 'wpc-force-sells' ) . '</a>',
						];

						return array_merge( $links, $row_meta );
					}

					return (array) $links;
				}

				function cart_item_removed( $cart_item_key, $cart ) {
					$new_keys = [];

					foreach ( $cart->cart_contents as $cart_key => $cart_item ) {
						if ( ! empty( $cart_item['woofs_key'] ) ) {
							$new_keys[ $cart_key ] = $cart_item['woofs_key'];
						}
					}

					if ( isset( $cart->removed_cart_contents[ $cart_item_key ]['woofs_keys'], $cart->removed_cart_contents[ $cart_item_key ]['woofs_separately'] ) && ( $cart->removed_cart_contents[ $cart_item_key ]['woofs_separately'] !== 'on' ) ) {
						$keys = $cart->removed_cart_contents[ $cart_item_key ]['woofs_keys'];

						foreach ( $keys as $key ) {
							$cart->remove_cart_item( $key );

							if ( $new_key = array_search( $key, $new_keys ) ) {
								$cart->remove_cart_item( $new_key );
							}
						}
					}
				}

				function cart_item_remove_link( $link, $cart_item_key ) {
					if ( isset( WC()->cart->cart_contents[ $cart_item_key ]['woofs_parent_key'] ) ) {
						$parent_key = WC()->cart->cart_contents[ $cart_item_key ]['woofs_parent_key'];

						if ( isset( WC()->cart->cart_contents[ $parent_key ] ) || array_search( $parent_key, array_column( WC()->cart->cart_contents, 'woofs_key', 'key' ) ) ) {
							return '';
						}
					}

					return $link;
				}

				function cart_contents_count( $count ) {
					// count for cart contents
					$cart_contents_count = self::get_setting( 'cart_contents_count', 'both' );

					if ( $cart_contents_count !== 'both' ) {
						$cart_contents = WC()->cart->cart_contents;

						foreach ( $cart_contents as $cart_item ) {
							if ( ( $cart_contents_count === 'linked' ) && ! empty( $cart_item['woofs_ids'] ) ) {
								$count -= $cart_item['quantity'];
							}

							if ( ( $cart_contents_count === 'main' ) && ! empty( $cart_item['woofs_parent_id'] ) ) {
								$count -= $cart_item['quantity'];
							}
						}
					}

					return $count;
				}

				function get_item_count( $count, $item_type, $order ) {
					// count for order items
					$cart_contents_count = self::get_setting( 'cart_contents_count', 'both' );
					$order_main          = $order_linked = 0;

					if ( $cart_contents_count !== 'both' ) {
						$order_items = $order->get_items( 'line_item' );

						foreach ( $order_items as $order_item ) {
							if ( $order_item->get_meta( '_woofs_parent_id' ) ) {
								$order_linked += $order_item->get_quantity();
							}

							if ( $order_item->get_meta( '_woofs_ids' ) ) {
								$order_main += $order_item->get_quantity();
							}
						}

						if ( ( $cart_contents_count === 'linked' ) && ( $order_linked > 0 ) ) {
							return $count - $order_main;
						}

						if ( ( $cart_contents_count === 'main' ) && ( $order_main > 0 ) ) {
							return $count - $order_linked;
						}
					}

					return $count;
				}

				function cart_item_name( $name, $item ) {
					if ( ! empty( $item['woofs_parent_id'] ) ) {
						$linked_text = self::localization( 'linked', esc_html__( '(linked to %s)', 'wpc-force-sells' ) );

						if ( strpos( $name, '</a>' ) !== false ) {
							$parent_name = sprintf( $linked_text, '<a href="' . get_permalink( $item['woofs_parent_id'] ) . '">' . get_the_title( $item['woofs_parent_id'] ) . '</a>' );
						} else {
							$parent_name = sprintf( $linked_text, get_the_title( $item['woofs_parent_id'] ) );
						}

						$name .= ' ' . apply_filters( 'woofs_item_associated', $parent_name, $item );
						$name = apply_filters( 'woofs_item_name', $name, $item );
					}

					return $name;
				}

				function cart_item_quantity( $quantity, $cart_item_key, $cart_item ) {
					// show qty as text - not input
					if ( ! empty( $cart_item['woofs_parent_id'] ) ) {
						return $cart_item['quantity'];
					}

					return $quantity;
				}

				function add_to_cart_validation( $passed, $product_id ) {
					if ( ! apply_filters( 'woofs_exclude', false, $product_id ) && ( get_post_meta( $product_id, 'woofs_separately', true ) !== 'on' ) && ( $product_items = self::get_product_items( $product_id, 'validate' ) ) ) {
						if ( isset( $_REQUEST['woofs_ids'] ) ) {
							$items = self::get_items( $_REQUEST['woofs_ids'], $product_id );
						} elseif ( isset( $_REQUEST['data']['woofs_ids'] ) ) {
							$items = self::get_items( $_REQUEST['data']['woofs_ids'], $product_id );
						} else {
							$items = $product_items;
						}

						if ( ! empty( $items ) && is_array( $items ) ) {
							foreach ( $items as $key => $item ) {
								if ( ! isset( $product_items[ $key ] ) ) {
									wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'wpc-force-sells' ), 'error' );

									return false;
								}

								if ( $item['id'] ) {
									$item_product = wc_get_product( $item['id'] );

									if ( ! $item_product ) {
										wc_add_notice( esc_html__( 'One of the linked products is unavailable.', 'wpc-force-sells' ), 'error' );
										wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'wpc-force-sells' ), 'error' );

										return false;
									}

									if ( $item_product->is_type( 'variable' ) ) {
										wc_add_notice( sprintf( esc_html__( '"%s" is un-purchasable.', 'wpc-force-sells' ), esc_html( $item_product->get_name() ) ), 'error' );
										wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'wpc-force-sells' ), 'error' );

										return false;
									}

									if ( is_a( $item_product, 'WC_Product_Variation' ) ) {
										$parent_id = $item_product->get_parent_id();

										if ( ( $product_items[ $key ]['id'] != $parent_id ) && ( $product_items[ $key ]['id'] != $item['id'] ) ) {
											wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'wpc-force-sells' ), 'error' );

											return false;
										}
									} else {
										if ( $product_items[ $key ]['id'] != $item['id'] ) {
											wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'wpc-force-sells' ), 'error' );

											return false;
										}
									}

									if ( ( self::get_setting( 'exclude_unpurchasable', 'no' ) !== 'yes' ) && ( ! $item_product->is_purchasable() || ! $item_product->is_in_stock() || ! $item_product->has_enough_stock( $product_items[ $key ]['qty'] ) ) ) {
										wc_add_notice( sprintf( esc_html__( '"%s" is un-purchasable.', 'wpc-force-sells' ), esc_html( $item_product->get_name() ) ), 'error' );
										wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'wpc-force-sells' ), 'error' );

										return false;
									}
								}
							}

							if ( self::get_setting( 'exclude_unpurchasable', 'no' ) !== 'yes' ) {
								foreach ( $product_items as $pk => $pi ) {
									if ( ! empty( $pi['id'] ) && ! isset( $items[ $pk ] ) ) {
										wc_add_notice( esc_html__( 'Missing a linked product.', 'wpc-force-sells' ), 'error' );
										wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'wpc-force-sells' ), 'error' );

										return false;
									}
								}
							}
						}
					}

					return $passed;
				}

				function add_cart_item_data( $cart_item_data, $product_id ) {
					if ( ! apply_filters( 'woofs_exclude', false, $product_id ) && ! isset( $cart_item_data['woofs_parent_id'] ) && ( $ids_str = self::get_ids_str( $product_id ) ) ) {
						if ( isset( $_REQUEST['woofs_ids'] ) ) {
							$ids_str = $_REQUEST['woofs_ids'];
							unset( $_REQUEST['woofs_ids'] );
						} elseif ( isset( $_REQUEST['data']['woofs_ids'] ) ) {
							$ids_str = $_REQUEST['data']['woofs_ids'];
							unset( $_REQUEST['data']['woofs_ids'] );
						}

						if ( ! empty( $ids_str ) ) {
							$cart_item_data['woofs_ids'] = $ids_str;
						}
					}

					return $cart_item_data;
				}

				function add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
					if ( ! apply_filters( 'woofs_exclude', false, $product_id ) && isset( $cart_item_data['woofs_ids'] ) && ! empty( $cart_item_data['woofs_ids'] ) ) {
						if ( ( $items = self::get_items( $cart_item_data['woofs_ids'], $product_id ) ) && ! empty( $items ) ) {
							// save current key associated with woofs_parent_key
							WC()->cart->cart_contents[ $cart_item_key ]['woofs_key'] = $cart_item_key;

							$sync_quantity = get_post_meta( $product_id, 'woofs_sync_quantity', true ) ?: 'on';
							$separately    = get_post_meta( $product_id, 'woofs_separately', true ) ?: 'off';

							WC()->cart->cart_contents[ $cart_item_key ]['woofs_sync_quantity'] = $sync_quantity;
							WC()->cart->cart_contents[ $cart_item_key ]['woofs_separately']    = $separately;

							// add child products
							foreach ( $items as $item ) {
								$item_id           = $item['id'];
								$item_price        = $item['price'];
								$item_qty          = $item['qty'];
								$item_variation    = $item['attrs'];
								$item_variation_id = 0;
								$item_product      = wc_get_product( $item_id );

								if ( ! $item_product ) {
									continue;
								}

								if ( $item_product instanceof WC_Product_Variation ) {
									// ensure we don't add a variation to the cart directly by variation ID
									$item_variation_id = $item_id;
									$item_id           = $item_product->get_parent_id();

									if ( empty( $item_variation ) ) {
										$item_variation = $item_product->get_variation_attributes();
									}
								}

								if ( ! $item_product->is_type( 'variable' ) && $item_product->is_in_stock() && $item_product->is_purchasable() && $item_product->has_enough_stock( $item_qty ) ) {
									if ( $sync_quantity === 'on' ) {
										$item_qty_new = $item_qty * $quantity;
									} else {
										$item_qty_new = $item_qty;
									}

									if ( $separately !== 'on' ) {
										$item_data = [
											'woofs_parent_id'     => $product_id,
											'woofs_parent_key'    => $cart_item_key,
											'woofs_sync_quantity' => $sync_quantity,
											'woofs_separately'    => $separately,
											'woofs_price'         => self::format_price( $item_price ),
											'woofs_qty'           => $item_qty
										];

										if ( $item_key = WC()->cart->add_to_cart( $item_id, $item_qty_new, $item_variation_id, $item_variation, $item_data ) ) {
											WC()->cart->cart_contents[ $item_key ]['woofs_key']         = $item_key;
											WC()->cart->cart_contents[ $cart_item_key ]['woofs_keys'][] = $item_key;
										} else {
											// can't add the linked product
											if ( self::get_setting( 'exclude_unpurchasable', 'no' ) !== 'yes' ) {
												if ( isset( WC()->cart->cart_contents[ $cart_item_key ]['woofs_keys'] ) ) {
													$keys = WC()->cart->cart_contents[ $cart_item_key ]['woofs_keys'];

													foreach ( $keys as $key ) {
														// remove all linked products
														WC()->cart->remove_cart_item( $key );
													}

													// remove the main product
													WC()->cart->remove_cart_item( $cart_item_key );

													// break out of the loop
													break;
												}
											}
										}
									} else {
										WC()->cart->add_to_cart( $item_id, $item_qty_new, $item_variation_id, $item_variation );
									}
								}
							}
						}
					}
				}

				function get_cart_item_from_session( $cart_item, $item_session_values ) {
					if ( ! empty( $item_session_values['woofs_ids'] ) ) {
						$cart_item['woofs_ids']           = $item_session_values['woofs_ids'];
						$cart_item['woofs_sync_quantity'] = $item_session_values['woofs_sync_quantity'];
						$cart_item['woofs_separately']    = $item_session_values['woofs_separately'];
					}

					if ( ! empty( $item_session_values['woofs_parent_id'] ) ) {
						$cart_item['woofs_parent_id']     = $item_session_values['woofs_parent_id'];
						$cart_item['woofs_parent_key']    = $item_session_values['woofs_parent_key'];
						$cart_item['woofs_sync_quantity'] = $item_session_values['woofs_sync_quantity'];
						$cart_item['woofs_separately']    = $item_session_values['woofs_separately'];
						$cart_item['woofs_price']         = $item_session_values['woofs_price'];
						$cart_item['woofs_qty']           = $item_session_values['woofs_qty'];
					}

					return $cart_item;
				}

				function order_line_item( $item, $cart_item_key, $values ) {
					// add _ to hide
					if ( isset( $values['woofs_parent_id'] ) ) {
						$item->update_meta_data( '_woofs_parent_id', $values['woofs_parent_id'] );
					}

					if ( isset( $values['woofs_ids'] ) ) {
						$item->update_meta_data( '_woofs_ids', $values['woofs_ids'] );
					}
				}

				function hidden_order_itemmeta( $hidden ) {
					return array_merge( $hidden, [
						'_woofs_parent_id',
						'_woofs_ids',
						'woofs_parent_id',
						'woofs_ids'
					] );
				}

				function before_order_itemmeta( $order_item_id, $order_item ) {
					if ( $ids = $order_item->get_meta( '_woofs_ids' ) ) {
						$order_product    = $order_item->get_product();
						$order_product_id = $order_product->get_id();
						$items            = self::get_items( $ids, $order_product_id, 'before_order_itemmeta' );

						if ( is_array( $items ) && ! empty( $items ) ) {
							$items_arr = [];

							foreach ( $items as $item ) {
								if ( ! self::item_visible( $item['id'], $order_product_id ) ) {
									continue;
								}

								$items_arr[] = apply_filters( 'woofs_admin_order_linked_product_name', '<li>' . $item['qty'] . ' × ' . get_the_title( $item['id'] ) . '</li>', $item );
							}

							$items_str = apply_filters( 'woofs_admin_order_linked_product_names', '<ul>' . implode( '', $items_arr ) . '</ul>', $items );

							echo apply_filters( 'woofs_before_order_itemmeta', '<div class="woofs-itemmeta-linked">' . sprintf( self::localization( 'linked_products_s', esc_html__( 'Linked products: %s', 'wpc-force-sells' ) ), $items_str ) . '</div>', $order_item_id, $order_item );
						}
					}

					if ( $parent_id = $order_item->get_meta( '_woofs_parent_id' ) ) {
						$linked_text = self::localization( 'linked', esc_html__( '(linked to %s)', 'wpc-force-sells' ) );

						echo sprintf( $linked_text, get_the_title( $parent_id ) );
					}
				}

				function mini_cart_item_visible( $visible, $cart_item ) {
					if ( ! empty( $cart_item['woofs_parent_id'] ) ) {
						if ( ! self::item_visible( $cart_item['data'], $cart_item['woofs_parent_id'] ) ) {
							return false;
						}

						if ( self::get_setting( 'hide_linked_mini_cart', 'no' ) === 'yes' ) {
							return false;
						}
					}

					return $visible;
				}

				function cart_item_visible( $visible, $cart_item ) {
					if ( ! empty( $cart_item['woofs_parent_id'] ) ) {
						if ( ! self::item_visible( $cart_item['data'], $cart_item['woofs_parent_id'] ) ) {
							return false;
						}

						if ( self::get_setting( 'hide_linked', 'no' ) !== 'no' ) {
							return false;
						}
					}

					return $visible;
				}

				function order_item_visible( $visible, $order_item ) {
					if ( $parent_id = $order_item->get_meta( '_woofs_parent_id' ) ) {
						if ( ! self::item_visible( $order_item->get_product(), $parent_id ) ) {
							return false;
						}

						if ( self::get_setting( 'hide_linked_order', 'no' ) !== 'no' ) {
							return false;
						}
					}

					return $visible;
				}

				function item_visible( $item, $parent ) {
					return apply_filters( 'woofs_item_visible', true, $item, $parent );
				}

				function order_item_meta_start( $order_item_id, $order_item ) {
					if ( $ids = $order_item->get_meta( '_woofs_ids' ) ) {
						$order_product    = $order_item->get_product();
						$order_product_id = $order_product->get_id();
						$items            = self::get_items( $ids, $order_product_id, 'order_item_meta_start' );

						if ( is_array( $items ) && ! empty( $items ) ) {
							if ( self::get_setting( 'hide_linked_order', 'no' ) === 'yes_list' ) {
								$items_arr = [];

								foreach ( $items as $item ) {
									if ( ! self::item_visible( $item['id'], $order_product_id ) ) {
										continue;
									}

									$items_arr[] = apply_filters( 'woofs_order_linked_product_name', '<li>' . $item['qty'] . ' × ' . get_the_title( $item['id'] ) . '</li>', $item );
								}

								$items_str = apply_filters( 'woofs_order_linked_product_names', '<ul>' . implode( '', $items_arr ) . '</ul>', $items );
							} else {
								$items_arr = [];

								foreach ( $items as $item ) {
									if ( ! self::item_visible( $item['id'], $order_product_id ) ) {
										continue;
									}

									$items_arr[] = apply_filters( 'woofs_order_linked_product_name', $item['qty'] . ' × ' . get_the_title( $item['id'] ), $item );
								}

								$items_str = apply_filters( 'woofs_order_linked_product_names', implode( '; ', $items_arr ), $items );
							}

							echo apply_filters( 'woofs_order_item_meta_start', '<div class="woofs-itemmeta-linked">' . sprintf( self::localization( 'linked_products_s', esc_html__( 'Linked products: %s', 'wpc-force-sells' ) ), $items_str ) . '</div>', $order_item_id, $order_item );
						}
					}
				}

				function get_item_data( $data, $cart_item ) {
					if ( empty( $cart_item['woofs_ids'] ) ) {
						return $data;
					}

					$items = self::get_items( $cart_item['woofs_ids'], $cart_item['product_id'], 'get_item_data' );

					if ( is_array( $items ) && ! empty( $items ) ) {
						if ( self::get_setting( 'hide_linked', 'no' ) === 'yes_list' ) {
							$items_str = [];

							foreach ( $items as $item ) {
								if ( ! self::item_visible( $item['id'], $cart_item['product_id'] ) ) {
									continue;
								}

								$items_str[] = apply_filters( 'woofs_order_linked_product_name', '<li>' . $item['qty'] . ' × ' . get_the_title( $item['id'] ) . '</li>', $item );
							}

							$data['woofs_data'] = [
								'key'     => self::localization( 'linked_products', esc_html__( 'Linked products', 'wpc-force-sells' ) ),
								'value'   => esc_html( $cart_item['woofs_ids'] ),
								'display' => apply_filters( 'woofs_order_linked_product_names', '<ul>' . implode( '', $items_str ) . '</ul>', $items ),
							];
						} else {
							$items_str = [];

							foreach ( $items as $item ) {
								if ( ! self::item_visible( $item['id'], $cart_item['product_id'] ) ) {
									continue;
								}

								$items_str[] = apply_filters( 'woofs_order_linked_product_name', $item['qty'] . ' × ' . get_the_title( $item['id'] ), $item );
							}

							$data['woofs_data'] = [
								'key'     => self::localization( 'linked_products', esc_html__( 'Linked products', 'wpc-force-sells' ) ),
								'value'   => esc_html( $cart_item['woofs_ids'] ),
								'display' => apply_filters( 'woofs_order_linked_product_names', implode( '; ', $items_str ), $items ),
							];
						}
					}

					return $data;
				}

				function display_post_states( $states, $post ) {
					if ( 'product' == get_post_type( $post->ID ) ) {
						if ( ( $items = self::get_product_items( $post->ID, 'edit' ) ) && ! empty( $items ) ) {
							$count = 0;

							foreach ( $items as $item ) {
								if ( ! empty( $item['id'] ) ) {
									$count ++;
								}
							}

							if ( $count ) {
								$states[] = apply_filters( 'woofs_post_states', '<span class="woofs-state">' . sprintf( esc_html__( 'Force sells (%s)', 'wpc-force-sells' ), $count ) . '</span>', $count, $post->ID );
							}
						}
					}

					return $states;
				}

				function ajax_update_search_settings() {
					if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woofs-security' ) || ! current_user_can( 'manage_options' ) ) {
						die( 'Permissions check failed!' );
					}

					$settings = (array) get_option( 'woofs_settings', [] );

					$settings['search_limit']    = (int) sanitize_text_field( $_POST['limit'] );
					$settings['search_sku']      = sanitize_text_field( $_POST['sku'] );
					$settings['search_id']       = sanitize_text_field( $_POST['id'] );
					$settings['search_exact']    = sanitize_text_field( $_POST['exact'] );
					$settings['search_sentence'] = sanitize_text_field( $_POST['sentence'] );
					$settings['search_same']     = sanitize_text_field( $_POST['same'] );
					$settings['search_types']    = array_map( 'sanitize_text_field', (array) $_POST['types'] );

					update_option( 'woofs_settings', $settings );
					wp_die();
				}

				function ajax_get_search_results() {
					$types       = self::get_setting( 'search_types', [ 'all' ] );
					$keyword     = sanitize_text_field( $_POST['woofs_keyword'] );
					$id          = absint( sanitize_text_field( $_POST['woofs_id'] ) );
					$exclude_ids = [ $id ];
					$added_ids   = explode( ',', self::clean_ids( sanitize_text_field( $_POST['woofs_ids'] ) ) );

					if ( ( self::get_setting( 'search_id', 'no' ) === 'yes' ) && is_numeric( $keyword ) ) {
						// search by id
						$query_args = [
							'p'         => absint( $keyword ),
							'post_type' => 'product'
						];
					} else {
						$query_args = [
							'is_woofs'       => true,
							'post_type'      => 'product',
							'post_status'    => 'publish',
							's'              => $keyword,
							'posts_per_page' => self::get_setting( 'search_limit', 10 )
						];

						if ( ! empty( $types ) && ! in_array( 'all', $types, true ) ) {
							$product_types = $types;

							if ( in_array( 'variation', $types, true ) ) {
								$product_types[] = 'variable';
							}

							$query_args['tax_query'] = [
								[
									'taxonomy' => 'product_type',
									'field'    => 'slug',
									'terms'    => $product_types,
								],
							];
						}

						if ( self::get_setting( 'search_same', 'no' ) !== 'yes' ) {
							$exclude_ids = array_merge( $exclude_ids, $added_ids );
						}

						$query_args['post__not_in'] = $exclude_ids;
					}

					$query = new WP_Query( $query_args );

					if ( $query->have_posts() ) {
						echo '<ul>';

						while ( $query->have_posts() ) {
							$query->the_post();
							$product = wc_get_product( get_the_ID() );

							if ( ! $product ) {
								continue;
							}

							if ( ! $product->is_type( 'variable' ) || in_array( 'variable', $types, true ) || in_array( 'all', $types, true ) ) {
								self::product_data_li( $product, '100%', 1, true );
							}

							if ( $product->is_type( 'variable' ) && ( empty( $types ) || in_array( 'all', $types, true ) || in_array( 'variation', $types, true ) ) ) {
								// show all children
								$children = $product->get_children();

								if ( is_array( $children ) && count( $children ) > 0 ) {
									foreach ( $children as $child ) {
										if ( $product_child = wc_get_product( $child ) ) {
											self::product_data_li( $product_child, '100%', 1, true );
										}
									}
								}
							}
						}

						echo '</ul>';
						wp_reset_postdata();
					} else {
						echo '<ul><span>' . sprintf( esc_html__( 'No results found for "%s"', 'wpc-force-sells' ), esc_html( $keyword ) ) . '</span></ul>';
					}

					wp_die();
				}

				function product_data_li( $product, $price = '100%', $qty = 1, $search = false, $key = null ) {
					if ( ! $key ) {
						$key = self::generate_key();
					}

					$product_id    = $product->get_id();
					$product_sku   = $product->get_sku();
					$product_class = 'woofs-li-product';

					if ( ! $product->is_in_stock() ) {
						$product_class .= ' out-of-stock';
					}

					if ( ! in_array( $product->get_type(), self::$types, true ) ) {
						$product_class .= ' disabled';
					}

					if ( $search ) {
						$remove_btn = '<span class="woofs-remove hint--left" aria-label="' . esc_html__( 'Add', 'wpc-force-sells' ) . '">+</span>';
					} else {
						$remove_btn = '<span class="woofs-remove hint--left" aria-label="' . esc_html__( 'Remove', 'wpc-force-sells' ) . '">×</span>';
					}

					if ( class_exists( 'WPCleverWoopq' ) && ( get_option( '_woopq_decimal', 'no' ) === 'yes' ) ) {
						$step = '0.000001';
					} else {
						$step = '1';
						$qty  = (int) $qty;
					}

					$hidden_input  = '<input type="hidden" name="woofs_ids[' . $key . '][id]" value="' . $product_id . '"/><input type="hidden" name="woofs_ids[' . $key . '][sku]" value="' . $product_sku . '"/>';
					$product_image = '<span class="img">' . $product->get_image( [ 30, 30 ] ) . '</span>';

					echo '<li class="' . esc_attr( trim( $product_class ) ) . '" data-id="' . $product->get_id() . '">' . $hidden_input . '<span class="woofs-move"></span><span class="price hint--right" aria-label="' . esc_html__( 'Set a new price using a number (eg. "49") or percentage (eg. "90%" of original price)', 'wpc-force-sells' ) . '"><input name="woofs_ids[' . $key . '][price]" type="text" value="' . $price . '"/></span><span class="qty hint--right" aria-label="' . esc_html__( 'Quantity', 'wpc-force-sells' ) . '"><input name="woofs_ids[' . $key . '][qty]" type="number" value="' . $qty . '" min="' . $step . '" step="' . $step . '"/></span>' . $product_image . '<span class="data">' . strip_tags( $product->get_name() ) . ' (' . $product->get_price_html() . ')</span> <span class="type"><a href="' . get_edit_post_link( $product_id ) . '" target="_blank">' . $product->get_type() . '<br/>#' . $product->get_id() . '</a></span> ' . $remove_btn . '</li>';
				}

				function text_data_li( $data = [], $key = null ) {
					if ( ! $key ) {
						$key = self::generate_key();
					}

					$data = array_merge( [ 'type' => 'h1', 'text' => '' ], $data );
					$type = '<select name="woofs_ids[' . $key . '][type]"><option value="h1" ' . selected( $data['type'], 'h1', false ) . '>H1</option><option value="h2" ' . selected( $data['type'], 'h2', false ) . '>H2</option><option value="h3" ' . selected( $data['type'], 'h3', false ) . '>H3</option><option value="h4" ' . selected( $data['type'], 'h4', false ) . '>H4</option><option value="h5" ' . selected( $data['type'], 'h5', false ) . '>H5</option><option value="h6" ' . selected( $data['type'], 'h6', false ) . '>H6</option><option value="p" ' . selected( $data['type'], 'p', false ) . '>p</option><option value="span" ' . selected( $data['type'], 'span', false ) . '>span</option><option value="none" ' . selected( $data['type'], 'none', false ) . '>none</option></select>';

					echo '<li class="woofs-li-text"><span class="woofs-move"></span><span class="tag">' . $type . '</span><span class="data"><input type="text" name="woofs_ids[' . $key . '][text]" value="' . esc_attr( $data['text'] ) . '"/></span><span class="woofs-remove hint--left" aria-label="' . esc_html__( 'Remove', 'wpc-force-sells' ) . '">×</span></li>';
				}

				function product_data_tabs( $tabs ) {
					$tabs['woofs'] = [
						'label'  => esc_html__( 'Force Sells', 'wpc-force-sells' ),
						'target' => 'woofs_settings',
					];

					return $tabs;
				}

				function product_data_panels() {
					global $post, $thepostid, $product_object;

					if ( $product_object instanceof WC_Product ) {
						$product_id = $product_object->get_id();
					} elseif ( is_numeric( $thepostid ) ) {
						$product_id = $thepostid;
					} elseif ( $post instanceof WP_Post ) {
						$product_id = $post->ID;
					} else {
						$product_id = 0;
					}

					if ( ! $product_id ) {
						?>
                        <div id='woofs_settings' class='panel woocommerce_options_panel woofs_table'>
                            <p style="padding: 0 12px; color: #c9356e"><?php esc_html_e( 'Product wasn\'t returned.', 'wpc-force-sells' ); ?></p>
                        </div>
						<?php
						return;
					}
					?>
                    <div id='woofs_settings' class='panel woocommerce_options_panel woofs_table'>
                        <div id="woofs_search_settings" style="display: none" data-title="<?php esc_html_e( 'Search settings', 'wpc-force-sells' ); ?>">
                            <table>
								<?php self::search_settings(); ?>
                                <tr>
                                    <th></th>
                                    <td>
                                        <button id="woofs_search_settings_update" class="button button-primary">
											<?php esc_html_e( 'Update Options', 'wpc-force-sells' ); ?>
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <table>
                            <tr>
                                <th><?php esc_html_e( 'Search', 'wpc-force-sells' ); ?> (<a href="<?php echo admin_url( 'admin.php?page=wpclever-woofs&tab=settings#search' ); ?>" id="woofs_search_settings_btn"><?php esc_html_e( 'settings', 'wpc-force-sells' ); ?></a>)
                                </th>
                                <td>
                                    <div class="w100">
                                        <span class="loading" id="woofs_loading" style="display:none;"><?php esc_html_e( 'searching...', 'wpc-force-sells' ); ?></span>
                                        <input type="search" id="woofs_keyword" placeholder="<?php esc_html_e( 'Type any keyword to search', 'wpc-force-sells' ); ?>"/>
                                        <div id="woofs_results" class="woofs_results" style="display:none;"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="woofs_tr_space">
                                <th><?php esc_html_e( 'Selected', 'wpc-force-sells' ); ?></th>
                                <td>
                                    <div class="w100">
                                        <div id="woofs_selected" class="woofs_selected">
                                            <ul>
												<?php
												if ( ( $items = self::get_product_items( $product_id, 'edit' ) ) && ! empty( $items ) ) {
													foreach ( $items as $item_key => $item ) {
														if ( ! empty( $item['id'] ) ) {
															$item_id      = $item['id'];
															$item_price   = $item['price'];
															$item_qty     = $item['qty'];
															$item_product = wc_get_product( $item_id );

															if ( ! $item_product ) {
																continue;
															}

															self::product_data_li( $item_product, $item_price, $item_qty, false, $item_key );
														} else {
															self::text_data_li( $item, $item_key );
														}
													}
												}
												?>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="woofs_tr_space">
                                <th></th>
                                <td>
                                    <a href="https://wpclever.net/downloads/force-sells?utm_source=pro&utm_medium=woofs&utm_campaign=wporg" target="_blank" class="woofs_add_txt" onclick="return confirm('This feature only available in Premium Version!\nBuy it now? Just $29')">
										<?php esc_html_e( '+ Add heading/paragraph', 'wpc-force-sells' ); ?>
                                    </a>
                                </td>
                            </tr>
                            <tr class="woofs_tr_space">
                                <th><?php esc_html_e( 'Sync quantity', 'wpc-force-sells' ); ?></th>
                                <td>
                                    <input id="woofs_sync_quantity" name="woofs_sync_quantity" type="checkbox" <?php echo esc_attr( get_post_meta( $product_id, 'woofs_sync_quantity', true ) !== 'off' ? 'checked' : '' ); ?>/>
                                    <span class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'By default, the quantity of Force Sells items is synced with that of the main product. Uncheck to stick to the default quantities.', 'wpc-force-sells' ); ?>"></span>
                                </td>
                            </tr>
                            <tr class="woofs_tr_space">
                                <th><?php esc_html_e( 'Add separately', 'wpc-force-sells' ); ?></th>
                                <td>
                                    <input id="woofs_separately" name="woofs_separately" type="checkbox" <?php echo esc_attr( get_post_meta( $product_id, 'woofs_separately', true ) === 'on' ? 'checked' : '' ); ?>/>
                                    <span class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'If enabled, the force sell products will be added as separate items and stay unaffected from the main product, their prices will change back to the original.', 'wpc-force-sells' ); ?>"></span>
                                </td>
                            </tr>
                            <tr class="woofs_tr_space">
                                <th><?php esc_html_e( 'Layout', 'wpc-force-sells' ); ?></th>
                                <td>
									<?php $layout = get_post_meta( $product_id, 'woofs_layout', true ) ?: 'unset'; ?>
                                    <select name="woofs_layout">
                                        <option value="unset" <?php selected( $layout, 'unset' ); ?>><?php esc_html_e( 'Unset (default setting)', 'wpc-force-sells' ); ?></option>
                                        <option value="list" <?php selected( $layout, 'list' ); ?>><?php esc_html_e( 'List', 'wpc-force-sells' ); ?></option>
                                        <option value="grid-2" <?php selected( $layout, 'grid-2' ); ?>><?php esc_html_e( 'Grid - 2 columns', 'wpc-force-sells' ); ?></option>
                                        <option value="grid-3" <?php selected( $layout, 'grid-3' ); ?>><?php esc_html_e( 'Grid - 3 columns', 'wpc-force-sells' ); ?></option>
                                        <option value="grid-4" <?php selected( $layout, 'grid-4' ); ?>><?php esc_html_e( 'Grid - 4 columns', 'wpc-force-sells' ); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="woofs_tr_space">
                                <th><?php esc_html_e( 'Above text', 'wpc-force-sells' ); ?></th>
                                <td>
                                    <div class="w100">
                                        <input type="text" name="woofs_before_text" value='<?php echo stripslashes( get_post_meta( $product_id, 'woofs_before_text', true ) ); ?>'/>
                                    </div>
                                </td>
                            </tr>
                            <tr class="woofs_tr_space">
                                <th><?php esc_html_e( 'Under text', 'wpc-force-sells' ); ?></th>
                                <td>
                                    <div class="w100">
                                        <input type="text" name="woofs_after_text" value='<?php echo stripslashes( get_post_meta( $product_id, 'woofs_after_text', true ) ); ?>'/>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
					<?php
				}

				function process_product_meta( $post_id ) {
					if ( isset( $_POST['woofs_ids'] ) ) {
						update_post_meta( $post_id, 'woofs_ids', self::sanitize_array( $_POST['woofs_ids'] ) );
					} else {
						delete_post_meta( $post_id, 'woofs_ids' );
					}

					if ( isset( $_POST['woofs_before_text'] ) && ( $_POST['woofs_before_text'] !== '' ) ) {
						update_post_meta( $post_id, 'woofs_before_text', sanitize_textarea_field( $_POST['woofs_before_text'] ) );
					} else {
						delete_post_meta( $post_id, 'woofs_before_text' );
					}

					if ( isset( $_POST['woofs_after_text'] ) && ( $_POST['woofs_after_text'] !== '' ) ) {
						update_post_meta( $post_id, 'woofs_after_text', sanitize_textarea_field( $_POST['woofs_after_text'] ) );
					} else {
						delete_post_meta( $post_id, 'woofs_after_text' );
					}

					if ( isset( $_POST['woofs_sync_quantity'] ) ) {
						update_post_meta( $post_id, 'woofs_sync_quantity', 'on' );
					} else {
						update_post_meta( $post_id, 'woofs_sync_quantity', 'off' );
					}

					if ( isset( $_POST['woofs_separately'] ) ) {
						update_post_meta( $post_id, 'woofs_separately', 'on' );
					} else {
						update_post_meta( $post_id, 'woofs_separately', 'off' );
					}

					if ( isset( $_POST['woofs_layout'] ) && ( $_POST['woofs_layout'] !== '' ) ) {
						update_post_meta( $post_id, 'woofs_layout', sanitize_textarea_field( $_POST['woofs_layout'] ) );
					}
				}

				function product_price_class( $class ) {
					global $product;

					if ( $product && ( $product_id = $product->get_id() ) && self::get_ids( $product_id ) ) {
						$class .= ' woofs-price-' . $product_id;
					}

					return $class;
				}

				function add_to_cart_form() {
					global $product;

					if ( ! $product->is_type( 'grouped' ) && self::get_ids( $product->get_id() ) ) {
						wp_enqueue_script( 'wc-add-to-cart-variation' );
						self::show_items();
					}
				}

				function add_to_cart_button() {
					global $product;

					if ( $product && ( $product_id = $product->get_id() ) && ! $product->is_type( 'grouped' ) && ( $ids_str = self::get_ids_str( $product_id ) ) ) {
						echo '<input name="woofs_ids" class="woofs-ids woofs-ids-' . esc_attr( $product_id ) . '" data-id="' . esc_attr( $product_id ) . '" type="hidden" value="' . esc_attr( $ids_str ) . '"/>';
					}
				}

				function has_variables( $items ) {
					foreach ( $items as $item ) {
						$item_id      = $item['id'];
						$item_product = wc_get_product( $item_id );

						if ( $item_product && $item_product->is_type( 'variable' ) ) {
							return true;
						}
					}

					return false;
				}

				function show_items() {
					global $product;
					$product_id = $product->get_id();
					$items      = self::get_product_items( $product_id, 'view' );
					$order      = 1;

					if ( ! empty( $items ) && is_array( $items ) ) {
						echo '<div class="woofs-wrap woofs-wrap-' . esc_attr( $product_id ) . '" data-id="' . esc_attr( $product_id ) . '">';

						do_action( 'woofs_wrap_before', $product );

						if ( $before_text = apply_filters( 'woofs_before_text', get_post_meta( $product_id, 'woofs_before_text', true ) ?: self::localization( 'above_text' ), $product_id ) ) {
							echo '<div class="woofs-before-text woofs-text">' . esc_html( $before_text ) . '</div>';
						}

						$sku            = $product->get_sku();
						$weight         = htmlentities( wc_format_weight( $product->get_weight() ) );
						$dimensions     = htmlentities( wc_format_dimensions( $product->get_dimensions( false ) ) );
						$layout         = get_post_meta( $product_id, 'woofs_layout', true ) ?: 'unset';
						$sync_quantity  = get_post_meta( $product_id, 'woofs_sync_quantity', true ) ?: 'on';
						$separately     = get_post_meta( $product_id, 'woofs_separately', true ) ?: 'off';
						$layout         = $layout !== 'unset' ? $layout : self::get_setting( 'layout', 'list' );
						$products_class = apply_filters( 'woofs_products_class', 'woofs-products woofs-products-layout-' . $layout, $product );
						?>
                        <div class="<?php echo esc_attr( $products_class ); ?>" data-sync-quantity="<?php echo esc_attr( $sync_quantity ); ?>" data-separately="<?php echo esc_attr( $separately ); ?>" data-product-type="<?php echo esc_attr( $product->get_type() ); ?>" data-variables="<?php echo esc_attr( self::has_variables( $items ) ? 'yes' : 'no' ); ?>" data-product-price="<?php echo esc_attr( apply_filters( 'woofs_product_price', $product->get_type() === 'variable' ? '0' : wc_get_price_to_display( $product ), $product ) ); ?>" data-product-regular-price="<?php echo esc_attr( apply_filters( 'woofs_product_regular_price', $product->get_type() === 'variable' ? '0' : wc_get_price_to_display( $product, [ 'price' => $product->get_regular_price() ] ), $product ) ); ?>" data-product-sku="<?php echo esc_attr( $sku ); ?>" data-product-o_sku="<?php echo esc_attr( $sku ); ?>" data-product-weight="<?php echo esc_attr( $weight ); ?>" data-product-o_weight="<?php echo esc_attr( $weight ); ?>" data-product-dimensions="<?php echo esc_attr( $dimensions ); ?>" data-product-o_dimensions="<?php echo esc_attr( $dimensions ); ?>">
							<?php
							do_action( 'woofs_products_before', $product );

							$global_product = $product;

							foreach ( $items as $item_key => $item ) {
								if ( ! empty( $item['id'] ) ) {
									global $product;
									$product = wc_get_product( $item['id'] );

									if ( ! $product || ! in_array( $product->get_type(), self::$types, true ) ) {
										continue;
									}

									$item_id    = $item['id'];
									$item_price = $item['price'];
									$item_qty   = $item['qty'];
									$item_class = 'woofs-item-product woofs-product woofs-product-type-' . $product->get_type();

									if ( ! self::item_visible( $product, $global_product ) ) {
										$item_class .= ' woofs-product-hidden';
									}

									if ( ! $product->is_in_stock() || ! $product->has_enough_stock( $item_qty ) || ! $product->is_purchasable() ) {
										$item_class      .= ' woofs-product-unpurchasable';
										$item_data_price = 0;
									} else {
										if ( $separately === 'on' ) {
											$item_data_price = '100%';
										} else {
											$item_data_price = $item_price;
										}
									}

									if ( ( self::get_setting( 'exclude_unpurchasable', 'no' ) !== 'yes' ) && ( ! $product->is_in_stock() || ! $product->has_enough_stock( $item_qty ) || ! $product->is_purchasable() ) ) {
										$item_data_id = 0;
									} else {
										$item_data_id = $item_id;
									}

									if ( ! class_exists( 'WPCleverWoopq' ) || ( get_option( '_woopq_decimal', 'no' ) !== 'yes' ) ) {
										$item_qty = (int) $item_qty;
									}
									?>
                                    <div class="<?php echo esc_attr( apply_filters( 'woobt_item_class', $item_class, $item ) ); ?>" data-key="<?php echo esc_attr( $item_key ); ?>" data-name="<?php echo esc_attr( $product->get_name() ); ?>" data-id="<?php echo esc_attr( $item_data_id ); ?>" data-price="<?php echo esc_attr( apply_filters( 'woofs_item_new_price', $item_data_price, $item, $product ) ); ?>" data-price-ori="<?php echo esc_attr( apply_filters( 'woofs_item_price', wc_get_price_to_display( $product ), $item, $product ) ); ?>" data-regular-price="<?php echo esc_attr( apply_filters( 'woofs_item_regular_price', wc_get_price_to_display( $product, [ 'price' => $product->get_regular_price() ] ), $item, $product ) ); ?>" data-qty="<?php echo esc_attr( $item_qty ); ?>" data-qty-ori="<?php echo esc_attr( $item_qty ); ?>" data-order="<?php echo esc_attr( $order ); ?>">
										<?php do_action( 'woofs_product_before', $item, $product, $global_product ); ?>
										<?php if ( self::get_setting( 'show_thumb', 'yes' ) !== 'no' ) { ?>
                                            <div class="woofs-thumb">
												<?php do_action( 'woofs_product_thumb_before', $item, $product, $global_product ); ?>
                                                <div class="woofs-thumb-ori">
													<?php echo $product->get_image( self::$image_size ); ?>
                                                </div>
                                                <div class="woofs-thumb-new"></div>
												<?php do_action( 'woofs_product_thumb_after', $item, $product, $global_product ); ?>
                                            </div>
										<?php } ?>
                                        <div class="woofs-title">
											<?php
											do_action( 'woofs_product_name_before', $item, $product, $global_product );

											echo '<div class="woofs-title-inner">';

											// quantity
											echo apply_filters( 'woofs_item_qty', '<span class="woofs-qty"><span class="woofs-qty-num">' . $item_qty . '</span> × </span>', $item_qty, $product );

											// product name
											if ( $product->is_in_stock() ) {
												$product_name = $product->get_name();
											} else {
												$product_name = '<s>' . $product->get_name() . '</s>';
											}

											if ( self::get_setting( 'link', 'yes' ) !== 'no' ) {
												echo '<a ' . ( self::get_setting( 'link', 'yes' ) === 'yes_popup' ? 'class="woosq-link" data-id="' . $item_id . '" data-context="woofs"' : '' ) . ' href="' . get_permalink( $item_id ) . '" ' . ( self::get_setting( 'link', 'yes' ) === 'yes_blank' ? 'target="_blank"' : '' ) . '>' . $product_name . '</a>';
											} else {
												echo $product_name;
											}

											echo '</div>';

											if ( self::get_setting( 'show_description', 'no' ) === 'yes' ) {
												echo '<div class="woofs-description">' . $product->get_short_description() . '</div>';
											}

											echo '<div class="woofs-availability">' . wc_get_stock_html( $product ) . '</div>';

											do_action( 'woofs_product_name_after', $item, $product, $global_product );
											?>
                                        </div>
										<?php if ( self::get_setting( 'show_price', 'yes' ) !== 'no' ) { ?>
                                            <div class="woofs-price">
												<?php do_action( 'woofs_product_price_before', $item, $product, $global_product ); ?>
                                                <div class="woofs-price-new"></div>
                                                <div class="woofs-price-ori">
													<?php if ( ( $item_price !== '100%' ) && ( get_post_meta( $product_id, 'woofs_separately', true ) !== 'on' ) ) {
														echo '<del>' . wc_price( $product->get_price() ) . '</del> ' . wc_price( self::new_price( $product->get_price(), $item_price ) );
													} else {
														echo $product->get_price_html();
													} ?>
                                                </div>
												<?php do_action( 'woofs_product_price_after', $item, $product, $global_product ); ?>
                                            </div>
										<?php } ?>
										<?php do_action( 'woofs_product_after', $item, $product, $global_product ); ?>
                                    </div>
									<?php
									$order ++;
								} elseif ( ! empty( $item['text'] ) ) {
									$item_class = 'woofs-item-text';

									if ( ! empty( $item['type'] ) ) {
										$item_class .= ' woofs-item-text-type-' . $item['type'];
									}

									echo '<div class="' . esc_attr( apply_filters( 'woofs_item_text_class', $item_class, $item, $product_id ) ) . '">';

									if ( empty( $item['type'] ) || ( $item['type'] === 'none' ) ) {
										echo $item['text'];
									} else {
										echo '<' . $item['type'] . '>' . $item['text'] . '</' . $item['type'] . '>';
									}

									echo '</div>';
								}
							}

							$product = $global_product;

							do_action( 'woofs_products_after', $product );
							?>
                        </div>
						<?php
						do_action( 'woofs_additional_before', $product );
						echo '<div class="woofs-additional woofs-text"></div>';

						do_action( 'woofs_total_before', $product );
						echo '<div class="woofs-total woofs-text"></div>';

						do_action( 'woofs_alert_before', $product );
						echo '<div class="woofs-alert woofs-text" style="display: none"></div>';

						if ( $after_text = apply_filters( 'woofs_after_text', get_post_meta( $product_id, 'woofs_after_text', true ) ?: self::localization( 'under_text' ), $product_id ) ) {
							echo '<div class="woofs-after-text woofs-text">' . esc_html( $after_text ) . '</div>';
						}

						do_action( 'woofs_wrap_after', $product );

						echo '</div>';
					}
				}

				function before_mini_cart_contents() {
					WC()->cart->calculate_totals();
				}

				function before_calculate_totals( $cart_object ) {
					if ( ! defined( 'DOING_AJAX' ) && is_admin() ) {
						// This is necessary for WC 3.0+
						return;
					}

					$cart_contents = $cart_object->cart_contents;
					$new_keys      = [];

					foreach ( $cart_contents as $cart_item_key => $cart_item ) {
						if ( ! empty( $cart_item['woofs_key'] ) ) {
							$new_keys[ $cart_item_key ] = $cart_item['woofs_key'];
						}
					}

					foreach ( $cart_contents as $cart_item_key => $cart_item ) {
						if ( ! empty( $cart_item['woofs_parent_key'] ) ) {
							$parent_new_key = array_search( $cart_item['woofs_parent_key'], $new_keys );

							// remove orphaned products
							if ( ! $parent_new_key || ! isset( $cart_contents[ $parent_new_key ] ) || ( isset( $cart_contents[ $parent_new_key ]['woofs_keys'] ) && ! in_array( $cart_item_key, $cart_contents[ $parent_new_key ]['woofs_keys'] ) ) ) {
								unset( $cart_contents[ $cart_item_key ] );
								continue;
							}

							// set quantity
							if ( ! empty( $cart_item['woofs_qty'] ) ) {
								if ( ! empty( $cart_item['woofs_sync_quantity'] ) && ( $cart_item['woofs_sync_quantity'] === 'on' ) ) {
									// sync quantity
									WC()->cart->cart_contents[ $cart_item_key ]['quantity'] = $cart_item['woofs_qty'] * $cart_contents[ $parent_new_key ]['quantity'];
								} else {
									// fixed quantity
									WC()->cart->cart_contents[ $cart_item_key ]['quantity'] = $cart_item['woofs_qty'];
								}
							}

							// set price
							if ( isset( $cart_item['data'], $cart_item['woofs_price'] ) && ( $cart_item['woofs_price'] !== '' ) ) {
								$new_price = self::new_price( apply_filters( 'woofs_product_original_price', $cart_item['data']->get_price() ), $cart_item['woofs_price'] );
								$cart_item['data']->set_price( $new_price );
							}
						}
					}
				}

				function search_sku( $query ) {
					if ( $query->is_search && isset( $query->query['is_woofs'] ) ) {
						global $wpdb;

						$sku = sanitize_text_field( $query->query['s'] );
						$ids = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value = %s;", $sku ) );

						if ( ! $ids ) {
							return;
						}

						$posts = [];

						foreach ( $ids as $id ) {
							$post = get_post( $id );

							if ( $post->post_type === 'product_variation' ) {
								$posts[] = $post->post_parent;
							} else {
								$posts[] = $post->ID;
							}
						}

						unset( $query->query['s'], $query->query_vars['s'] );
						$query->set( 'post__in', $posts );
					}
				}

				function search_exact( $query ) {
					if ( $query->is_search && isset( $query->query['is_woofs'] ) ) {
						$query->set( 'exact', true );
					}
				}

				function search_sentence( $query ) {
					if ( $query->is_search && isset( $query->query['is_woofs'] ) ) {
						$query->set( 'sentence', true );
					}
				}

				function get_ids( $product_id, $context = 'display' ) {
					$ids = get_post_meta( $product_id, 'woofs_ids', true );

					return apply_filters( 'woofs_get_ids', $ids, $product_id, $context );
				}

				function get_ids_str( $product_id ) {
					$ids_str = '';
					$ids_arr = [];

					if ( ( $ids = self::get_ids( $product_id ) ) && is_array( $ids ) ) {
						foreach ( $ids as $item_key => $item ) {
							$item = array_merge( [ 'id' => 0 ], $item );

							if ( ! empty( $item['id'] ) ) {
								$ids_arr[] = $item_key . '/' . $item['id'];
							}
						}

						$ids_str = implode( ',', $ids_arr );
					}

					return apply_filters( 'woofs_get_ids_str', $ids_str, $product_id );
				}

				function get_product_items( $product_id = 0, $context = 'view' ) {
					$ids   = self::get_ids( $product_id, $context );
					$items = [];

					if ( ! empty( $ids ) && is_array( $ids ) ) {
						foreach ( $ids as $item_key => $item ) {
							$item = array_merge( [
								'id'    => 0,
								'sku'   => '',
								'price' => '100%',
								'qty'   => 0,
								'attrs' => []
							], $item );

							// check for variation
							if ( ( $parent_id = wp_get_post_parent_id( $item['id'] ) ) && ( $parent = wc_get_product( $parent_id ) ) ) {
								$parent_sku = $parent->get_sku();
							} else {
								$parent_sku = '';
							}

							if ( apply_filters( 'woofs_use_sku', false ) && ! empty( $item['sku'] ) && ( $item['sku'] !== $parent_sku ) && ( $new_id = wc_get_product_id_by_sku( $item['sku'] ) ) ) {
								// get product id by SKU for export/import
								$item['id'] = $new_id;
							}

							$items[ $item_key ] = $item;
						}
					}

					return apply_filters( 'woofs_get_product_items', $items, $product_id, $context );
				}

				function get_items( $ids, $product_id = 0, $context = 'view' ) {
					$product_items = self::get_product_items( $product_id, $context );
					$items         = [];

					if ( ! empty( $ids ) ) {
						$_items = explode( ',', $ids );

						if ( is_array( $_items ) && count( $_items ) > 0 ) {
							foreach ( $_items as $_item ) {
								$_item_data    = explode( '/', $_item );
								$_item_key     = isset( $_item_data[0] ) ? $_item_data[0] : self::generate_key();
								$_item_id      = apply_filters( 'woofs_item_id', absint( isset( $_item_data[1] ) ? $_item_data[1] : 0 ), $_item, $product_id );
								$_item_product = wc_get_product( $_item_id );

								if ( ! $_item_product || ( $_item_product->get_status() === 'trash' ) ) {
									continue;
								}

								if ( is_array( $product_items ) && isset( $product_items[ $_item_key ]['price'] ) ) {
									$_item_price = $product_items[ $_item_key ]['price'];
								} else {
									$_item_price = '100%';
								}

								if ( is_array( $product_items ) && isset( $product_items[ $_item_key ]['qty'] ) ) {
									$_item_qty = $product_items[ $_item_key ]['qty'];
								} else {
									$_item_qty = 1;
								}

								if ( ( $context === 'view' ) && ( ( self::get_setting( 'exclude_unpurchasable', 'no' ) === 'yes' ) && ( ! $_item_product->is_purchasable() || ! $_item_product->is_in_stock() ) ) ) {
									continue;
								}

								$items[ $_item_key ] = [
									'id'    => $_item_id,
									'price' => self::format_price( $_item_price ),
									'qty'   => (float) $_item_qty,
									'attrs' => isset( $_item_data[2] ) ? (array) json_decode( rawurldecode( $_item_data[2] ) ) : []
								];
							}
						}
					}

					return apply_filters( 'woofs_get_items', $items, $ids, $product_id, $context );
				}

				function sanitize_array( $arr ) {
					foreach ( (array) $arr as $k => $v ) {
						if ( is_array( $v ) ) {
							$arr[ $k ] = self::sanitize_array( $v );
						} else {
							$arr[ $k ] = sanitize_text_field( $v );
						}
					}

					return $arr;
				}

				function clean_ids( $ids, $product_id = null ) {
					return apply_filters( 'woofs_clean_ids', $ids, $product_id );
				}

				function format_price( $price ) {
					// format price to percent or number
					$price = preg_replace( '/[^.%0-9]/', '', $price );

					return apply_filters( 'woofs_format_price', $price );
				}

				function new_price( $old_price, $new_price ) {
					if ( strpos( $new_price, '%' ) !== false ) {
						$calc_price = ( (float) $new_price * $old_price ) / 100;
					} else {
						$calc_price = (float) $new_price;
					}

					return $calc_price;
				}

				function wpml_item_id( $id ) {
					return apply_filters( 'wpml_object_id', $id, 'product', true );
				}

				function product_filter( $filters ) {
					$filters['woofs'] = [ $this, 'product_filter_callback' ];

					return $filters;
				}

				function product_filter_callback() {
					$woofs  = isset( $_REQUEST['woofs'] ) ? wc_clean( wp_unslash( $_REQUEST['woofs'] ) ) : false;
					$output = '<select name="woofs"><option value="">' . esc_html__( 'Force sells', 'wpc-force-sells' ) . '</option>';
					$output .= '<option value="yes" ' . selected( $woofs, 'yes', false ) . '>' . esc_html__( 'With linked products', 'wpc-force-sells' ) . '</option>';
					$output .= '<option value="no" ' . selected( $woofs, 'no', false ) . '>' . esc_html__( 'Without linked products', 'wpc-force-sells' ) . '</option>';
					$output .= '</select>';
					echo $output;
				}

				function apply_product_filter( $query ) {
					global $pagenow;

					if ( $query->is_admin && $pagenow == 'edit.php' && isset( $_GET['woofs'] ) && $_GET['woofs'] != '' && $_GET['post_type'] == 'product' ) {
						$meta_query = (array) $query->get( 'meta_query' );

						if ( $_GET['woofs'] === 'yes' ) {
							$meta_query[] = [
								'relation' => 'AND',
								[
									'key'     => 'woofs_ids',
									'compare' => 'EXISTS'
								],
								[
									'key'     => 'woofs_ids',
									'value'   => '',
									'compare' => '!='
								],
							];
						} else {
							$meta_query[] = [
								'relation' => 'OR',
								[
									'key'     => 'woofs_ids',
									'compare' => 'NOT EXISTS'
								],
								[
									'key'     => 'woofs_ids',
									'value'   => '',
									'compare' => '=='
								],
							];
						}

						$query->set( 'meta_query', $meta_query );
					}
				}

				function wpcsm_locations( $locations ) {
					$locations['WPC Force Sells'] = [
						'woofs_wrap_before'          => esc_html__( 'Before wrapper', 'wpc-force-sells' ),
						'woofs_wrap_after'           => esc_html__( 'After wrapper', 'wpc-force-sells' ),
						'woofs_products_before'      => esc_html__( 'Before products', 'wpc-force-sells' ),
						'woofs_products_after'       => esc_html__( 'After products', 'wpc-force-sells' ),
						'woofs_product_before'       => esc_html__( 'Before sub-product', 'wpc-force-sells' ),
						'woofs_product_after'        => esc_html__( 'After sub-product', 'wpc-force-sells' ),
						'woofs_product_thumb_before' => esc_html__( 'Before sub-product thumbnail', 'wpc-force-sells' ),
						'woofs_product_thumb_after'  => esc_html__( 'After sub-product thumbnail', 'wpc-force-sells' ),
						'woofs_product_name_before'  => esc_html__( 'Before sub-product name', 'wpc-force-sells' ),
						'woofs_product_name_after'   => esc_html__( 'After sub-product name', 'wpc-force-sells' ),
						'woofs_product_price_before' => esc_html__( 'Before sub-product price', 'wpc-force-sells' ),
						'woofs_product_price_after'  => esc_html__( 'After sub-product price', 'wpc-force-sells' ),
					];

					return $locations;
				}

				public static function generate_key() {
					$key         = '';
					$key_str     = apply_filters( 'woofs_key_characters', 'abcdefghijklmnopqrstuvwxyz0123456789' );
					$key_str_len = strlen( $key_str );

					for ( $i = 0; $i < apply_filters( 'woofs_key_length', 4 ); $i ++ ) {
						$key .= $key_str[ random_int( 0, $key_str_len - 1 ) ];
					}

					if ( is_numeric( $key ) ) {
						$key = self::generate_key();
					}

					return apply_filters( 'woofs_generate_key', $key );
				}
			}

			return WPCleverWoofs::instance();
		}

		return null;
	}
}

if ( ! function_exists( 'woofs_notice_wc' ) ) {
	function woofs_notice_wc() {
		?>
        <div class="error">
            <p><strong>WPC Force Sells</strong> requires WooCommerce version 3.0 or greater.</p>
        </div>
		<?php
	}
}
