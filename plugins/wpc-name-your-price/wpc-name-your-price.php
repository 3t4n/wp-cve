<?php
/*
Plugin Name: WPC Name Your Price for WooCommerce
Plugin URI: https://wpclever.net/
Description: WPC Name Your Price lets customers pay with what price they want.
Version: 2.1.0
Author: WPClever
Author URI: https://wpclever.net
Text Domain: wpc-name-your-price
Domain Path: /languages/
Requires at least: 4.0
Tested up to: 6.4
WC requires at least: 3.0
WC tested up to: 8.6
*/

defined( 'ABSPATH' ) || exit;

! defined( 'WOONP_VERSION' ) && define( 'WOONP_VERSION', '2.1.0' );
! defined( 'WOONP_LITE' ) && define( 'WOONP_LITE', __FILE__ );
! defined( 'WOONP_FILE' ) && define( 'WOONP_FILE', __FILE__ );
! defined( 'WOONP_URI' ) && define( 'WOONP_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WOONP_REVIEWS' ) && define( 'WOONP_REVIEWS', 'https://wordpress.org/support/plugin/wpc-name-your-price/reviews/?filter=5' );
! defined( 'WOONP_CHANGELOG' ) && define( 'WOONP_CHANGELOG', 'https://wordpress.org/plugins/wpc-name-your-price/#developers' );
! defined( 'WOONP_DISCUSSION' ) && define( 'WOONP_DISCUSSION', 'https://wordpress.org/support/plugin/wpc-name-your-price' );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WOONP_URI );

include 'includes/dashboard/wpc-dashboard.php';
include 'includes/kit/wpc-kit.php';
include 'includes/hpos.php';

if ( ! function_exists( 'woonp_init' ) ) {
	add_action( 'plugins_loaded', 'woonp_init', 11 );

	function woonp_init() {
		// load text-domain
		load_plugin_textdomain( 'wpc-name-your-price', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'woonp_notice_wc' );

			return null;
		}

		require 'includes/class-helper.php';
		require 'includes/class-core.php';

		if ( ! class_exists( 'WPCleverWoonp' ) && class_exists( 'WC_Product' ) ) {
			class WPCleverWoonp {
				protected static $instance = null;

				public static function instance() {
					if ( is_null( self::$instance ) ) {
						self::$instance = new self();
					}

					return self::$instance;
				}

				function __construct() {
					// enqueue backend
					add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

					// enqueue frontend
					add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

					// settings page
					add_action( 'admin_init', [ $this, 'register_settings' ] );
					add_action( 'admin_menu', [ $this, 'admin_menu' ] );

					// settings link
					add_filter( 'plugin_action_links', [ $this, 'action_links' ], 10, 2 );
					add_filter( 'plugin_row_meta', [ $this, 'row_meta' ], 10, 2 );

					// product data tabs
					add_filter( 'woocommerce_product_data_tabs', [ $this, 'product_data_tabs' ] );
					add_action( 'woocommerce_product_data_panels', [ $this, 'product_data_panels' ] );
					add_action( 'woocommerce_process_product_meta', [ $this, 'process_product_meta' ] );
				}

				function admin_enqueue_scripts() {
					wp_enqueue_style( 'woonp-backend', WOONP_URI . 'assets/css/backend.css', [], WOONP_VERSION );
					wp_enqueue_script( 'woonp-backend', WOONP_URI . 'assets/js/backend.js', [ 'jquery' ], WOONP_VERSION, true );
				}

				function enqueue_scripts() {
					wp_enqueue_style( 'woonp-frontend', WOONP_URI . 'assets/css/frontend.css', [], WOONP_VERSION );
					wp_enqueue_script( 'woonp-frontend', WOONP_URI . 'assets/js/frontend.js', [ 'jquery' ], WOONP_VERSION, true );
					wp_localize_script( 'woonp-frontend', 'woonp_vars', [
							'rounding'       => WoonpHelper::get_setting( 'rounding', 'down' ),
							'price_decimals' => wc_get_price_decimals(),
						]
					);
				}

				function register_settings() {
					// settings
					register_setting( 'woonp_settings', 'woonp_settings' );
				}

				function admin_menu() {
					add_submenu_page( 'wpclever', esc_html__( 'WPC Name Your Price', 'wpc-name-your-price' ),
						esc_html__( 'Name Your Price', 'wpc-name-your-price' ), 'manage_options', 'wpclever-woonp', [
							$this,
							'admin_menu_content',
						] );
				}

				function admin_menu_content() {
					$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'settings';
					?>
                    <div class="wpclever_settings_page wrap">
                        <h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Name Your Price', 'wpc-name-your-price' ) . ' ' . WOONP_VERSION; ?></h1>
                        <div class="wpclever_settings_page_desc about-text">
                            <p>
								<?php printf( esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.',
									'wpc-name-your-price' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
                                <br/>
                                <a href="<?php echo esc_url( WOONP_REVIEWS ); ?>" target="_blank"><?php esc_html_e( 'Reviews', 'wpc-name-your-price' ); ?></a> |
                                <a href="<?php echo esc_url( WOONP_CHANGELOG ); ?>" target="_blank"><?php esc_html_e( 'Changelog', 'wpc-name-your-price' ); ?></a> |
                                <a href="<?php echo esc_url( WOONP_DISCUSSION ); ?>" target="_blank"><?php esc_html_e( 'Discussion', 'wpc-name-your-price' ); ?></a>
                            </p>
                        </div>
						<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) { ?>
                            <div class="notice notice-success is-dismissible">
                                <p><?php esc_html_e( 'Settings updated.', 'wpc-name-your-price' ); ?></p>
                            </div>
						<?php } ?>
                        <div class="wpclever_settings_page_nav">
                            <h2 class="nav-tab-wrapper">
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woonp&tab=settings' ); ?>" class="<?php echo esc_attr( $active_tab === 'settings' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Settings', 'wpc-name-your-price' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-kit' ); ?>" class="nav-tab">
									<?php esc_html_e( 'Essential Kit', 'wpc-name-your-price' ); ?>
                                </a>
                            </h2>
                        </div>
                        <div class="wpclever_settings_page_content">
							<?php if ( $active_tab === 'settings' ) {
								$step          = '0.0001';
								$global_status = WoonpHelper::get_setting( 'global_status', 'enable' );
								$value         = WoonpHelper::get_setting( 'value', 'price' );
								$rounding      = WoonpHelper::get_setting( 'rounding', 'down' );
								$atc_button    = WoonpHelper::get_setting( 'atc_button', 'show' );
								$type          = WoonpHelper::get_setting( 'type', 'default' );
								?>
                                <form method="post" action="options.php">
                                    <table class="form-table">
                                        <tr class="heading">
                                            <th>
												<?php esc_html_e( 'General', 'wpc-name-your-price' ); ?>
                                            </th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Status', 'wpc-name-your-price' ); ?></th>
                                            <td>
                                                <select name="woonp_settings[global_status]">
                                                    <option value="enable" <?php selected( $global_status, 'enable' ); ?>><?php esc_html_e( 'Enable', 'wpc-name-your-price' ); ?></option>
                                                    <option value="disable" <?php selected( $global_status, 'disable' ); ?>><?php esc_html_e( 'Disable', 'wpc-name-your-price' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'You still can enable/disable it on a product basis.', 'wpc-name-your-price' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Suggested price', 'wpc-name-your-price' ); ?></th>
                                            <td>
                                                <input type="text" name="woonp_settings[suggested_price]" value="<?php echo WoonpHelper::get_setting( 'suggested_price', esc_html__( 'Suggested Price: %s', 'wpc-name-your-price' ) ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Use General tab\'s price as suggested price, leave blank to hide. Use "%s" for price.', 'wpc-name-your-price' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Add to cart button', 'wpc-name-your-price' ); ?></th>
                                            <td>
                                                <select name="woonp_settings[atc_button]">
                                                    <option value="show" <?php selected( $atc_button, 'show' ); ?>><?php esc_html_e( 'Show', 'wpc-name-your-price' ); ?></option>
                                                    <option value="hide" <?php selected( $atc_button, 'hide' ); ?>><?php esc_html_e( 'Hide', 'wpc-name-your-price' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Show/hide add to cart button on the shop/archive page.', 'wpc-name-your-price' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Rounding values', 'wpc-name-your-price' ); ?></th>
                                            <td>
                                                <select name="woonp_settings[rounding]">
                                                    <option value="down" <?php selected( $rounding, 'down' ); ?>><?php esc_html_e( 'Down', 'wpc-name-your-price' ); ?></option>
                                                    <option value="up" <?php selected( $rounding, 'up' ); ?>><?php esc_html_e( 'Up', 'wpc-name-your-price' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Round the amount to the nearest bigger (up) or smaller (down) value when an invalid number is inputted.', 'wpc-name-your-price' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th>
												<?php esc_html_e( 'Price input', 'wpc-name-your-price' ); ?>
                                            </th>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Label', 'wpc-name-your-price' ); ?></th>
                                            <td>
                                                <input type="text" name="woonp_settings[label]" value="<?php echo WoonpHelper::get_setting( 'label', esc_html__( 'Name Your Price (%s) ', 'wpc-name-your-price' ) ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Use "%s" for currency.', 'wpc-name-your-price' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Default value', 'wpc-name-your-price' ); ?></th>
                                            <td>
                                                <select name="woonp_settings[value]">
                                                    <option value="price" <?php selected( $value, 'price' ); ?>><?php esc_html_e( 'Product price', 'wpc-name-your-price' ); ?></option>
                                                    <option value="min" <?php selected( $value, 'min' ); ?>><?php esc_html_e( 'Min value', 'wpc-name-your-price' ); ?></option>
                                                    <option value="max" <?php selected( $value, 'max' ); ?>><?php esc_html_e( 'Max value', 'wpc-name-your-price' ); ?></option>
                                                    <option value="empty" <?php selected( $value, 'empty' ); ?>><?php esc_html_e( 'Empty', 'wpc-name-your-price' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Type', 'wpc-name-your-price' ); ?></th>
                                            <td>
                                                <select name="woonp_settings[type]" class="woonp_type">
                                                    <option value="default" <?php selected( $type, 'default' ); ?>><?php esc_html_e( 'Input (default)', 'wpc-name-your-price' ); ?></option>
                                                    <option value="select" <?php selected( $type, 'select' ); ?>><?php esc_html_e( 'Select', 'wpc-name-your-price' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="woonp_show_if_type_input">
                                            <th><?php esc_html_e( 'Minimum', 'wpc-name-your-price' ); ?></th>
                                            <td>
                                                <input type="number" name="woonp_settings[min]" min="0" step="<?php echo esc_attr( $step ); ?>" value="<?php echo WoonpHelper::get_setting( 'min' ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Leave blank or zero to disable.', 'wpc-name-your-price' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="woonp_show_if_type_input">
                                            <th><?php esc_html_e( 'Step', 'wpc-name-your-price' ); ?></th>
                                            <td>
                                                <input type="number" name="woonp_settings[step]" min="0" step="<?php echo esc_attr( $step ); ?>" value="<?php echo WoonpHelper::get_setting( 'step' ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Leave blank or zero to disable.', 'wpc-name-your-price' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="woonp_show_if_type_input">
                                            <th><?php esc_html_e( 'Maximum', 'wpc-name-your-price' ); ?></th>
                                            <td>
                                                <input type="number" name="woonp_settings[max]" min="0" step="<?php echo esc_attr( $step ); ?>" value="<?php echo WoonpHelper::get_setting( 'max' ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Leave blank or zero to disable.', 'wpc-name-your-price' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="woonp_show_if_type_select">
                                            <th><?php esc_html_e( 'Values', 'wpc-name-your-price' ); ?></th>
                                            <td>
                                                <textarea name="woonp_settings[values]" rows="10" cols="50"><?php echo WoonpHelper::get_setting( 'values' ); ?></textarea>
                                                <p class="description">
													<?php esc_html_e( 'Enter each value in one line and can use the range e.g "10-20".', 'wpc-name-your-price' ); ?>
                                                </p>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th colspan="2"><?php esc_html_e( 'Suggestion', 'wpc-name-your-price' ); ?></th>
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
												<?php settings_fields( 'woonp_settings' ); ?><?php submit_button(); ?>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } ?>
                        </div>
                    </div>
					<?php
				}

				function action_links( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$settings = '<a href="' . admin_url( 'admin.php?page=wpclever-woonp&tab=settings' ) . '">' . esc_html__( 'Settings', 'wpc-name-your-price' ) . '</a>';
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
							'support' => '<a href="' . esc_url( WOONP_DISCUSSION ) . '" target="_blank">' . esc_html__( 'Community support', 'wpc-name-your-price' ) . '</a>',
						];

						return array_merge( $links, $row_meta );
					}

					return (array) $links;
				}

				function product_data_tabs( $tabs ) {
					$tabs['woonp'] = [
						'label'  => esc_html__( 'Name Your Price', 'wpc-name-your-price' ),
						'target' => 'woonp_settings',
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
                        <div id='woonp_settings' class='panel woocommerce_options_panel woonp_table'>
                            <p style="padding: 0 12px; color: #c9356e"><?php esc_html_e( 'Product wasn\'t returned.', 'wpc-name-your-price' ); ?></p>
                        </div>
						<?php
						return;
					}

					$step  = '0.0001';
					$price = get_post_meta( $product_id, '_woonp_status', true ) ?: 'default';
					$type  = get_post_meta( $product_id, '_woonp_type', true ) ?: 'default';
					?>
                    <div id='woonp_settings' class='panel woocommerce_options_panel woonp_table'>
                        <div class="woonp_tr">
                            <div class="woonp_td"><?php esc_html_e( 'Name Your Price', 'wpc-name-your-price' ); ?></div>
                            <div class="woonp_td">
                                <input name="_woonp_status" type="radio" value="default" <?php echo esc_attr( $price === 'default' ? 'checked' : '' ); ?>/> <?php esc_html_e( 'Default',
									'wpc-name-your-price' ); ?>
                                (<a href="<?php echo admin_url( 'admin.php?page=wpclever-woonp&tab=settings' ); ?>" target="_blank"><?php esc_html_e( 'settings', 'wpc-name-your-price' ); ?></a>) &nbsp;
                                <input name="_woonp_status" type="radio" value="disable" <?php echo esc_attr( $price === 'disable' ? 'checked' : '' ); ?>/> <?php esc_html_e( 'Disable',
									'wpc-name-your-price' ); ?>
                                &nbsp;
                                <input name="_woonp_status" type="radio" value="overwrite" <?php echo esc_attr( $price === 'overwrite' ? 'checked' : '' ); ?>/> <?php esc_html_e( 'Overwrite',
									'wpc-name-your-price' ); ?>
                            </div>
                        </div>
                        <div class="woonp_show_if_overwrite">
                            <div class="woonp_tr">
                                <div class="woonp_td"><?php esc_html_e( 'Type', 'wpc-name-your-price' ); ?></div>
                                <div class="woonp_td">
                                    <select name="_woonp_type">
                                        <option value="default" <?php echo esc_attr( $type === 'default' ? 'selected' : '' ); ?>><?php esc_html_e( 'Input (default)', 'wpc-name-your-price' ); ?></option>
                                        <option value="select" <?php echo esc_attr( $type === 'select' ? 'selected' : '' ); ?>><?php esc_html_e( 'Select', 'wpc-name-your-price' ); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="woonp_tr woonp_show_if_type_input">
                                <div class="woonp_td"><?php esc_html_e( 'Minimum', 'wpc-name-your-price' ); ?></div>
                                <div class="woonp_td">
                                    <input type="number" name="_woonp_min" min="0" style="width: 120px" step="<?php echo esc_attr( $step ); ?>" value="<?php echo esc_attr( get_post_meta( $product_id, '_woonp_min', true ) ); ?>"/>
                                    <span class="description"><?php esc_html_e( 'Leave blank or zero to disable.', 'wpc-name-your-price' ); ?></span>
                                </div>
                            </div>
                            <div class="woonp_tr woonp_show_if_type_input">
                                <div class="woonp_td"><?php esc_html_e( 'Step', 'wpc-name-your-price' ); ?></div>
                                <div class="woonp_td">
                                    <input type="number" name="_woonp_step" min="0" style="width: 120px" step="<?php echo esc_attr( $step ); ?>" value="<?php echo esc_attr( get_post_meta( $product_id, '_woonp_step', true ) ); ?>"/>
                                    <span class="description"><?php esc_html_e( 'Leave blank or zero to disable.', 'wpc-name-your-price' ); ?></span>
                                </div>
                            </div>
                            <div class="woonp_tr woonp_show_if_type_input">
                                <div class="woonp_td"><?php esc_html_e( 'Maximum', 'wpc-name-your-price' ); ?></div>
                                <div class="woonp_td">
                                    <input type="number" name="_woonp_max" min="0" style="width: 120px" step="<?php echo esc_attr( $step ); ?>" value="<?php echo esc_attr( get_post_meta( $product_id, '_woonp_max', true ) ); ?>"/>
                                    <span class="description"><?php esc_html_e( 'Leave blank or zero to disable.', 'wpc-name-your-price' ); ?></span>
                                </div>
                            </div>
                            <div class="woonp_tr woonp_show_if_type_select">
                                <div class="woonp_td"><?php esc_html_e( 'Values', 'wpc-name-your-price' ); ?></div>
                                <div class="woonp_td">
                                    <textarea name="_woonp_values" rows="10" cols="50" style="float: none; width: 100%; height: 200px"><?php echo get_post_meta( $product_id, '_woonp_values', true ); ?></textarea>
                                    <p class="description" style="margin-left: 0">
										<?php esc_html_e( 'Enter each value in one line and can use the range e.g "10-20".',
											'wpc-name-your-price' ); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
					<?php
				}

				function process_product_meta( $post_id ) {
					if ( isset( $_POST['_woonp_status'] ) ) {
						update_post_meta( $post_id, '_woonp_status', sanitize_text_field( $_POST['_woonp_status'] ) );
					}

					if ( isset( $_POST['_woonp_type'] ) ) {
						update_post_meta( $post_id, '_woonp_type', sanitize_text_field( $_POST['_woonp_type'] ) );
					}

					if ( isset( $_POST['_woonp_min'] ) ) {
						update_post_meta( $post_id, '_woonp_min', sanitize_text_field( $_POST['_woonp_min'] ) );
					}

					if ( isset( $_POST['_woonp_max'] ) ) {
						update_post_meta( $post_id, '_woonp_max', sanitize_text_field( $_POST['_woonp_max'] ) );
					}

					if ( isset( $_POST['_woonp_step'] ) ) {
						update_post_meta( $post_id, '_woonp_step', sanitize_text_field( $_POST['_woonp_step'] ) );
					}

					if ( isset( $_POST['_woonp_values'] ) ) {
						update_post_meta( $post_id, '_woonp_values', sanitize_textarea_field( $_POST['_woonp_values'] ) );
					}
				}

				public static function get_values( $values ) {
					$woonp_values = [];
					$values_arr   = explode( "\n", (string) $values );

					if ( count( $values_arr ) > 0 ) {
						foreach ( $values_arr as $item ) {
							$item_value = self::clean_values( $item );

							if ( strpos( $item_value, '-' ) ) {
								$item_value_arr = explode( '-', $item_value );

								for ( $i = (int) $item_value_arr[0]; $i <= (int) $item_value_arr[1]; $i ++ ) {
									$woonp_values[] = [ 'name' => $i, 'value' => $i ];
								}
							} elseif ( is_numeric( $item_value ) ) {
								$woonp_values[] = [
									'name'  => esc_html( trim( $item ) ),
									'value' => (int) $item_value,
								];
							}
						}
					}

					if ( empty( $woonp_values ) ) {
						// default values
						$woonp_values = apply_filters( 'woonp_default_values', [
							[ 'name' => '1', 'value' => 1 ],
							[ 'name' => '2', 'value' => 2 ],
							[ 'name' => '3', 'value' => 3 ],
							[ 'name' => '4', 'value' => 4 ],
							[ 'name' => '5', 'value' => 5 ],
							[ 'name' => '6', 'value' => 6 ],
							[ 'name' => '7', 'value' => 7 ],
							[ 'name' => '8', 'value' => 8 ],
							[ 'name' => '9', 'value' => 9 ],
							[ 'name' => '10', 'value' => 10 ],
						] );
					} else {
						$woonp_values = array_intersect_key( $woonp_values, array_unique( array_map( 'serialize', $woonp_values ) ) );
					}

					return apply_filters( 'woonp_values', $woonp_values );
				}

				public static function clean_values( $str ) {
					return preg_replace( '/[^.\-0-9]/', '', $str );
				}
			}

			return WPCleverWoonp::instance();
		}

		return null;
	}
}

if ( ! function_exists( 'woonp_notice_wc' ) ) {
	function woonp_notice_wc() {
		?>
        <div class="error">
            <p><strong>WPC Name Your Price</strong> requires WooCommerce version 3.0 or greater.</p>
        </div>
		<?php
	}
}
