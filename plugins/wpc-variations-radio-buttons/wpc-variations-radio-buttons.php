<?php
/**
 * Plugin Name: WPC Variations Radio Buttons for WooCommerce
 * Plugin URI: https://wpclever.net/
 * Description: WPC Variations Radio Buttons will replace dropdown select with radio buttons for the buyer easier in selecting the variations.
 * Version: 3.5.0
 * Author: WPClever
 * Author URI: https://wpclever.net
 * Text Domain: wpc-variations-radio-buttons
 * Domain Path: /languages/
 * Requires at least: 4.0
 * Tested up to: 6.4
 * WC requires at least: 3.0
 * WC tested up to: 8.5
 */

defined( 'ABSPATH' ) || exit;

! defined( 'WOOVR_VERSION' ) && define( 'WOOVR_VERSION', '3.5.0' );
! defined( 'WOOVR_LITE' ) && define( 'WOOVR_LITE', __FILE__ );
! defined( 'WOOVR_FILE' ) && define( 'WOOVR_FILE', __FILE__ );
! defined( 'WOOVR_URI' ) && define( 'WOOVR_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WOOVR_DIR' ) && define( 'WOOVR_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'WOOVR_SUPPORT' ) && define( 'WOOVR_SUPPORT', 'https://wpclever.net/support?utm_source=support&utm_medium=woovr&utm_campaign=wporg' );
! defined( 'WOOVR_REVIEWS' ) && define( 'WOOVR_REVIEWS', 'https://wordpress.org/support/plugin/wpc-variations-radio-buttons/reviews/?filter=5' );
! defined( 'WOOVR_CHANGELOG' ) && define( 'WOOVR_CHANGELOG', 'https://wordpress.org/plugins/wpc-variations-radio-buttons/#developers' );
! defined( 'WOOVR_DISCUSSION' ) && define( 'WOOVR_DISCUSSION', 'https://wordpress.org/support/plugin/wpc-variations-radio-buttons' );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WOOVR_URI );

include 'includes/dashboard/wpc-dashboard.php';
include 'includes/kit/wpc-kit.php';
include 'includes/hpos.php';

if ( ! function_exists( 'woovr_init' ) ) {
	add_action( 'plugins_loaded', 'woovr_init', 11 );

	function woovr_init() {
		// load text-domain
		load_plugin_textdomain( 'wpc-variations-radio-buttons', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'woovr_notice_wc' );

			return null;
		}

		if ( ! class_exists( 'WPClever_Woovr' ) && class_exists( 'WC_Product' ) ) {
			class WPClever_Woovr {
				protected static $instance = null;
				protected static $settings = [];

				public static function instance() {
					if ( is_null( self::$instance ) ) {
						self::$instance = new self();
					}

					return self::$instance;
				}

				function __construct() {
					self::$settings = (array) get_option( 'woovr_settings', [] );

					// settings page
					add_action( 'admin_init', [ $this, 'register_settings' ] );
					add_action( 'admin_menu', [ $this, 'admin_menu' ] );

					// settings link
					add_filter( 'plugin_action_links', [ $this, 'action_links' ], 10, 2 );
					add_filter( 'plugin_row_meta', [ $this, 'row_meta' ], 10, 2 );

					// enqueue backend scripts
					add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 99 );

					// enqueue frontend scripts
					add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 99 );

					// product data tabs
					add_filter( 'woocommerce_product_data_tabs', [ $this, 'product_data_tabs' ] );
					add_action( 'woocommerce_product_data_panels', [ $this, 'product_data_panels' ] );
					add_action( 'woocommerce_process_product_meta', [ $this, 'process_product_meta' ] );

					// functions
					add_filter( 'woocommerce_post_class', [ $this, 'post_class' ], 99, 2 );
					add_action( 'woocommerce_before_variations_form', [ $this, 'before_variations_form' ] );

					// custom variation name & image
					add_action( 'woocommerce_product_after_variable_attributes', [
						$this,
						'variation_settings'
					], 10, 3 );
					add_action( 'woocommerce_save_product_variation', [ $this, 'save_variation_settings' ], 10, 2 );
					add_filter( 'woocommerce_product_variation_get_name', [ $this, 'variation_get_name' ], 99, 2 );

					// WPC Smart Messages
					add_filter( 'wpcsm_locations', [ $this, 'wpcsm_locations' ] );

					// WPC Variation Duplicator
					add_action( 'wpcvd_duplicated', [ $this, 'duplicate_variation' ], 99, 2 );

					// WPC Variation Bulk Editor
					add_action( 'wpcvb_bulk_update_variation', [ $this, 'bulk_update_variation' ], 99, 2 );
				}

				public static function get_settings() {
					return apply_filters( 'woovr_get_settings', self::$settings );
				}

				public static function get_setting( $name, $default = false ) {
					if ( ! empty( self::$settings ) && isset( self::$settings[ $name ] ) ) {
						$setting = self::$settings[ $name ];
					} else {
						$setting = get_option( '_woovr_' . $name, $default );
					}

					return apply_filters( 'woovr_get_setting', $setting, $name, $default );
				}

				function register_settings() {
					// settings
					register_setting( 'woovr_settings', 'woovr_settings' );
				}

				function admin_menu() {
					add_submenu_page( 'wpclever', esc_html__( 'WPC Variations Radio Buttons', 'wpc-variations-radio-buttons' ), esc_html__( 'Variations Radio Buttons', 'wpc-variations-radio-buttons' ), 'manage_options', 'wpclever-woovr', [
						$this,
						'admin_menu_content'
					] );
				}

				function admin_menu_content() {
					$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'settings';
					?>
                    <div class="wpclever_settings_page wrap">
                        <h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Variations Radio Buttons', 'wpc-variations-radio-buttons' ) . ' ' . WOOVR_VERSION . ' ' . ( defined( 'WOOVR_PREMIUM' ) ? '<span class="premium" style="display: none">' . esc_html__( 'Premium', 'wpc-variations-radio-buttons' ) . '</span>' : '' ); ?></h1>
                        <div class="wpclever_settings_page_desc about-text">
                            <p>
								<?php printf( esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'wpc-variations-radio-buttons' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
                                <br/>
                                <a href="<?php echo esc_url( WOOVR_REVIEWS ); ?>" target="_blank"><?php esc_html_e( 'Reviews', 'wpc-variations-radio-buttons' ); ?></a> |
                                <a href="<?php echo esc_url( WOOVR_CHANGELOG ); ?>" target="_blank"><?php esc_html_e( 'Changelog', 'wpc-variations-radio-buttons' ); ?></a> |
                                <a href="<?php echo esc_url( WOOVR_DISCUSSION ); ?>" target="_blank"><?php esc_html_e( 'Discussion', 'wpc-variations-radio-buttons' ); ?></a>
                            </p>
                        </div>
						<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) { ?>
                            <div class="notice notice-success is-dismissible">
                                <p><?php esc_html_e( 'Settings updated.', 'wpc-variations-radio-buttons' ); ?></p>
                            </div>
						<?php } ?>
                        <div class="wpclever_settings_page_nav">
                            <h2 class="nav-tab-wrapper">
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woovr&tab=settings' ); ?>" class="<?php echo esc_attr( $active_tab === 'settings' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Settings', 'wpc-variations-radio-buttons' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woovr&tab=premium' ); ?>" class="<?php echo esc_attr( $active_tab === 'premium' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>" style="color: #c9356e">
									<?php esc_html_e( 'Premium Version', 'wpc-variations-radio-buttons' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-kit' ); ?>" class="nav-tab">
									<?php esc_html_e( 'Essential Kit', 'wpc-variations-radio-buttons' ); ?>
                                </a>
                            </h2>
                        </div>
                        <div class="wpclever_settings_page_content">
							<?php if ( $active_tab === 'settings' ) {
								$active             = self::get_setting( 'active', 'yes' );
								$hide_unpurchasable = self::get_setting( 'hide_unpurchasable', 'no' );
								$selector           = self::get_setting( 'selector', 'default' );
								$show_name          = self::get_setting( 'variation_name', 'formatted' );
								$product_name       = self::get_setting( 'product_name', 'yes' );
								$show_clear         = self::get_setting( 'show_clear', 'yes' );
								$show_image         = self::get_setting( 'show_image', 'yes' );
								$show_price         = self::get_setting( 'show_price', 'yes' );
								$show_availability  = self::get_setting( 'show_availability', 'yes' );
								$show_description   = self::get_setting( 'show_description', 'yes' );
								$clear_label        = self::get_setting( 'clear_label' );
								$clear_image        = self::get_setting( 'clear_image', 'placeholder' );
								$clear_image_id     = self::get_setting( 'clear_image_id', '' );
								?>
                                <form method="post" action="options.php">
                                    <table class="form-table">
                                        <tr>
                                            <th>
												<?php esc_html_e( 'Active', 'wpc-variations-radio-buttons' ); ?>
                                            </th>
                                            <td>
                                                <select name="woovr_settings[active]">
                                                    <option value="no" <?php echo esc_attr( $active === 'no' || $active === 'yes_wpc' ? 'selected' : '' ); ?>><?php esc_html_e( 'No', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="yes" <?php selected( $active, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-variations-radio-buttons' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'This is the default status, you can set status for individual product in the its settings.', 'wpc-variations-radio-buttons' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
												<?php esc_html_e( 'Hide unpurchasable variation', 'wpc-variations-radio-buttons' ); ?>
                                            </th>
                                            <td>
                                                <select name="woovr_settings[hide_unpurchasable]">
                                                    <option value="no" <?php selected( $hide_unpurchasable, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="yes" <?php selected( $hide_unpurchasable, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-variations-radio-buttons' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Selector interface', 'wpc-variations-radio-buttons' ); ?></th>
                                            <td>
                                                <select name="woovr_settings[selector]">
                                                    <option value="default" <?php selected( $selector, 'default' ); ?>><?php esc_html_e( 'Radio buttons (default)', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="ddslick" <?php selected( $selector, 'ddslick' ); ?>><?php esc_html_e( 'ddSlick', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="select2" <?php selected( $selector, 'select2' ); ?>><?php esc_html_e( 'Select2', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="select" <?php selected( $selector, 'select' ); ?>><?php esc_html_e( 'HTML select tag', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="grid-2" <?php selected( $selector, 'grid-2' ); ?>><?php esc_html_e( 'Grid - 2 columns', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="grid-3" <?php selected( $selector, 'grid-3' ); ?> <?php selected( $selector, 'grid' ); ?>><?php esc_html_e( 'Grid - 3 columns', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="grid-4" <?php selected( $selector, 'grid-4' ); ?>><?php esc_html_e( 'Grid - 4 columns', 'wpc-variations-radio-buttons' ); ?></option>
                                                </select> <span class="description">
                                                    Read more about ddSlick, Select2 and HTML select tag <a href="https://wpclever.net/downloads/variations-radio-buttons" target="_blank">here</a>.
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
												<?php esc_html_e( 'Show "Option none"', 'wpc-variations-radio-buttons' ); ?>
                                            </th>
                                            <td>
                                                <select name="woovr_settings[show_clear]">
                                                    <option value="no" <?php selected( $show_clear, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="yes" <?php selected( $show_clear, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-variations-radio-buttons' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
												<?php esc_html_e( '"Option none" label', 'wpc-variations-radio-buttons' ); ?>
                                            </th>
                                            <td>
                                                <input type="text" class="regular-text" name="woovr_settings[clear_label]" placeholder="<?php esc_html_e( 'Choose an option', 'wpc-variations-radio-buttons' ); ?>" value="<?php echo esc_attr( $clear_label ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Leave blank to use the default text and its equivalent translation in multiple languages.', 'wpc-variations-radio-buttons' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( '"Option none" image', 'wpc-variations-radio-buttons' ); ?></th>
                                            <td>
                                                <select name="woovr_settings[clear_image]" class="woovr_clear_image">
                                                    <option value="placeholder" <?php selected( $clear_image, 'placeholder' ); ?>><?php esc_html_e( 'Placeholder image', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="product" <?php selected( $clear_image, 'product' ); ?>><?php esc_html_e( 'Main product\'s image', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="custom" <?php selected( $clear_image, 'custom' ); ?>><?php esc_html_e( 'Custom image', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="none" <?php selected( $clear_image, 'none' ); ?>><?php esc_html_e( 'No image', 'wpc-variations-radio-buttons' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'If you choose "Placeholder image", you can change it in WooCommerce > Settings > Products > Placeholder image.', 'wpc-variations-radio-buttons' ); ?></span>
                                                <div class="woovr_clear_image_custom" style="display: none">
													<?php wp_enqueue_media(); ?>
                                                    <span class="woovr_image_selector">
														<input type="hidden" class="woovr_image_id" name="woovr_settings[clear_image_id]" value="<?php echo esc_attr( $clear_image_id ); ?>">
														<span class="woovr_image_preview">
															<?php if ( $clear_image_id ) {
																echo '<span class="woovr_image_preview">' . wp_get_attachment_image( $clear_image_id ) . '<a class="woovr_image_remove button" href="#">' . esc_html__( 'Remove', 'wpc-variations-radio-buttons' ) . '</a></span>';
															} else {
																echo '<span class="woovr_image_preview">' . wc_placeholder_img() . '</span>';
															} ?>
														</span>
														<a href="#" class="woovr_image_add button"><?php esc_attr_e( 'Choose Image', 'wpc-variations-radio-buttons' ); ?></a>
													</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Variation name', 'wpc-variations-radio-buttons' ); ?></th>
                                            <td>
                                                <select name="woovr_settings[variation_name]">
                                                    <option value="formatted" <?php selected( $show_name, 'formatted' ); ?>><?php esc_html_e( 'Formatted without attribute label (e.g Green, M)', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="formatted_label" <?php selected( $show_name, 'formatted_label' ); ?>><?php esc_html_e( 'Formatted with attribute label (e.g Color: Green, Size: M)', 'wpc-variations-radio-buttons' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
												<?php esc_html_e( 'Include product name', 'wpc-variations-radio-buttons' ); ?>
                                            </th>
                                            <td>
                                                <select name="woovr_settings[product_name]">
                                                    <option value="no" <?php selected( $product_name, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="yes" <?php selected( $product_name, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-variations-radio-buttons' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Include the product name before variation name.', 'wpc-variations-radio-buttons' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
												<?php esc_html_e( 'Show image', 'wpc-variations-radio-buttons' ); ?>
                                            </th>
                                            <td>
                                                <select name="woovr_settings[show_image]">
                                                    <option value="no" <?php selected( $show_image, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="yes" <?php selected( $show_image, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-variations-radio-buttons' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
												<?php esc_html_e( 'Show price', 'wpc-variations-radio-buttons' ); ?>
                                            </th>
                                            <td>
                                                <select name="woovr_settings[show_price]">
                                                    <option value="no" <?php selected( $show_price, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="yes" <?php selected( $show_price, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-variations-radio-buttons' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
												<?php esc_html_e( 'Show availability', 'wpc-variations-radio-buttons' ); ?>
                                            </th>
                                            <td>
                                                <select name="woovr_settings[show_availability]">
                                                    <option value="no" <?php selected( $show_availability, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="yes" <?php selected( $show_availability, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-variations-radio-buttons' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
												<?php esc_html_e( 'Show description', 'wpc-variations-radio-buttons' ); ?>
                                            </th>
                                            <td>
                                                <select name="woovr_settings[show_description]">
                                                    <option value="no" <?php selected( $show_description, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-variations-radio-buttons' ); ?></option>
                                                    <option value="yes" <?php selected( $show_description, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-variations-radio-buttons' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th colspan="2"><?php esc_html_e( 'Suggestion', 'wpc-variations-radio-buttons' ); ?></th>
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
												<?php settings_fields( 'woovr_settings' ); ?><?php submit_button(); ?>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'premium' ) { ?>
                                <div class="wpclever_settings_page_content_text">
                                    <p>
                                        Get the Premium Version just $29!
                                        <a href="https://wpclever.net/downloads/variations-radio-buttons?utm_source=pro&utm_medium=woovr&utm_campaign=wporg" target="_blank">https://wpclever.net/downloads/variations-radio-buttons</a>
                                    </p>
                                    <p><strong>Extra features for Premium Version:</strong></p>
                                    <ul style="margin-bottom: 0">
                                        <li>- Settings for individual product.</li>
                                        <li>- Get the lifetime update & premium support.</li>
                                    </ul>
                                </div>
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
						$settings             = '<a href="' . admin_url( 'admin.php?page=wpclever-woovr&tab=settings' ) . '">' . esc_html__( 'Settings', 'wpc-variations-radio-buttons' ) . '</a>';
						$links['wpc-premium'] = '<a href="' . admin_url( 'admin.php?page=wpclever-woovr&tab=premium' ) . '">' . esc_html__( 'Premium Version', 'wpc-variations-radio-buttons' ) . '</a>';
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
							'support' => '<a href="' . esc_url( WOOVR_DISCUSSION ) . '" target="_blank">' . esc_html__( 'Community support', 'wpc-variations-radio-buttons' ) . '</a>',
						];

						return array_merge( $links, $row_meta );
					}

					return (array) $links;
				}

				function admin_enqueue_scripts( $hook ) {
					if ( apply_filters( 'woovr_ignore_backend_scripts', false, $hook ) ) {
						return null;
					}

					wp_enqueue_style( 'woovr-backend', WOOVR_URI . 'assets/css/backend.css', [], WOOVR_VERSION );
					wp_enqueue_script( 'woovr-backend', WOOVR_URI . 'assets/js/backend.js', [ 'jquery' ], WOOVR_VERSION, true );
					wp_localize_script( 'woovr-backend', 'woovr_vars', [
						'media_add_text' => esc_html__( 'Add to Variation', 'wpc-variations-radio-buttons' ),
						'media_title'    => esc_html__( 'Custom Image', 'wpc-variations-radio-buttons' ),
						'media_remove'   => esc_html__( 'Remove', 'wpc-variations-radio-buttons' )
					] );
				}

				function enqueue_scripts() {
					// ddslick
					wp_enqueue_script( 'ddslick', WOOVR_URI . 'assets/libs/ddslick/jquery.ddslick.min.js', [ 'jquery' ], WOOVR_VERSION, true );

					// select2
					wp_enqueue_style( 'select2' );
					wp_enqueue_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2.full.min.js', [ 'jquery' ], WOOVR_VERSION, true );

					// woovr
					wp_enqueue_style( 'woovr-frontend', WOOVR_URI . 'assets/css/frontend.css', [], WOOVR_VERSION );
					wp_enqueue_script( 'woovr-frontend', WOOVR_URI . 'assets/js/frontend.js', [ 'jquery' ], WOOVR_VERSION, true );
				}

				function product_data_tabs( $tabs ) {
					$tabs['woovr'] = [
						'label'  => esc_html__( 'Radio Buttons', 'wpc-variations-radio-buttons' ),
						'target' => 'woovr_settings',
						'class'  => [ 'show_if_variable' ]
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
                        <div id='woovr_settings' class='panel woocommerce_options_panel woovr_table'>
                            <p style="padding: 0 12px; color: #c9356e"><?php esc_html_e( 'Product wasn\'t returned.', 'wpc-variations-radio-buttons' ); ?></p>
                        </div>
						<?php
						return;
					}

					$active = get_post_meta( $product_id, '_woovr_active', true ) ?: 'default';
					?>
                    <div id='woovr_settings' class='panel woocommerce_options_panel woovr_table'>
                        <div class="woovr_tr">
                            <div class="woovr_td"><?php esc_html_e( 'Active', 'wpc-variations-radio-buttons' ); ?></div>
                            <div class="woovr_td">
                                <input name="_woovr_active" type="radio" value="default" <?php checked( $active, 'default' ); ?>/> <?php esc_html_e( 'Default', 'wpc-variations-radio-buttons' ); ?> (<a href="<?php echo admin_url( 'admin.php?page=wpclever-woovr&tab=settings' ); ?>" target="_blank"><?php esc_html_e( 'settings', 'wpc-variations-radio-buttons' ); ?></a>)
                                <input name="_woovr_active" type="radio" value="no" <?php checked( $active, 'no' ); ?>/> <?php esc_html_e( 'No', 'wpc-variations-radio-buttons' ); ?>
                                <input name="_woovr_active" type="radio" value="yes" <?php checked( $active, 'yes' ); ?>/> <?php esc_html_e( 'Yes (Overwrite)', 'wpc-variations-radio-buttons' ); ?>
                                <div style="color: #c9356e; margin-top: 10px">
                                    You only can use the
                                    <a href="<?php echo admin_url( 'admin.php?page=wpclever-woovr&tab=settings' ); ?>" target="_blank">default settings</a> for all products.<br/> To overwrite for individual product, please use the premium version. Click
                                    <a href="https://wpclever.net/downloads/variations-radio-buttons?utm_source=pro&utm_medium=woovr&utm_campaign=wporg" target="_blank">here</a> to buy, just $29.
                                </div>
                            </div>
                        </div>
                    </div>
					<?php
				}

				function process_product_meta( $post_id ) {
					if ( isset( $_POST['_woovr_active'] ) ) {
						update_post_meta( $post_id, '_woovr_active', sanitize_text_field( $_POST['_woovr_active'] ) );
					} else {
						delete_post_meta( $post_id, '_woovr_active' );
					}
				}

				function before_variations_form() {
					global $product;

					if ( $product && ( $product_id = $product->get_id() ) ) {
						$active  = self::get_setting( 'active', 'yes' );
						$_active = get_post_meta( $product_id, '_woovr_active', true ) ?: 'default';

						if ( $_active === 'yes' || ( $_active === 'default' && $active === 'yes' ) ) {
							self::variations_form( $product );
						}
					}
				}

				function variation_settings( $loop, $variation_data, $variation ) {
					$variation_id = $variation->ID;
					$name         = get_post_meta( $variation_id, 'woovr_name', true );
					$image        = get_post_meta( $variation_id, 'woovr_image', true );
					$image_id     = get_post_meta( $variation_id, 'woovr_image_id', true );

					echo '<div class="form-row form-row-full woovr-variation-settings">';
					echo '<label>' . esc_html__( 'WPC Variations Radio Buttons', 'wpc-variations-radio-buttons' ) . '</label>';
					echo '<div class="woovr-variation-wrap">';

					echo '<p class="form-field form-row">';
					echo '<label>' . esc_html__( 'Custom name', 'wpc-variations-radio-buttons' ) . '</label>';
					echo '<input type="text" class="woovr_name" name="woovr_name[' . $variation_id . ']" value="' . esc_attr( $name ) . '"/>';
					echo '</p>';

					echo '<p class="form-field form-row woovr_custom_image">';
					echo '<label>' . esc_html__( 'Custom image', 'wpc-variations-radio-buttons' ) . '</label>';
					echo '<span class="woovr_image_selector">';
					echo '<input type="hidden" class="woovr_image_id" name="woovr_image_id[' . $variation_id . ']" value="' . esc_attr( $image_id ) . '"/>';

					if ( $image_id ) {
						echo '<span class="woovr_image_preview">' . wp_get_attachment_image( $image_id ) . '<a class="woovr_image_remove button" href="#">' . esc_html__( 'Remove', 'wpc-variations-radio-buttons' ) . '</a></span>';
					} else {
						echo '<span class="woovr_image_preview">' . wc_placeholder_img() . '</span>';
					}

					echo '<a href="#" class="woovr_image_add button" rel="' . esc_attr( $variation_id ) . '">' . esc_html__( 'Choose Image', 'wpc-variations-radio-buttons' ) . '</a>';
					echo '</span>';
					echo '</p>';

					echo '<p class="form-field form-row">';
					echo '<label>' . esc_html__( '- OR - Custom image URL', 'wpc-variations-radio-buttons' ) . '</label>';
					echo '<input type="url" class="woovr_image_url" name="woovr_image[' . $variation_id . ']" value="' . esc_attr( $image ) . '"/>';
					echo '</p>';

					echo '</div></div>';
				}

				function save_variation_settings( $post_id ) {
					if ( isset( $_POST['woovr_name'][ $post_id ] ) ) {
						update_post_meta( $post_id, 'woovr_name', sanitize_text_field( $_POST['woovr_name'][ $post_id ] ) );
					} else {
						delete_post_meta( $post_id, 'woovr_name' );
					}

					if ( isset( $_POST['woovr_image'][ $post_id ] ) ) {
						update_post_meta( $post_id, 'woovr_image', sanitize_url( $_POST['woovr_image'][ $post_id ] ) );
					} else {
						delete_post_meta( $post_id, 'woovr_image' );
					}

					if ( isset( $_POST['woovr_image_id'][ $post_id ] ) ) {
						update_post_meta( $post_id, 'woovr_image_id', sanitize_text_field( $_POST['woovr_image_id'][ $post_id ] ) );
					} else {
						delete_post_meta( $post_id, 'woovr_image_id' );
					}
				}

				function bulk_update_variation( $variation_id, $fields ) {
					if ( ! empty( $fields['woovr_name'] ) ) {
						update_post_meta( $variation_id, 'woovr_name', sanitize_text_field( $fields['woovr_name'] ) );
					}

					if ( ! empty( $fields['woovr_image'] ) ) {
						update_post_meta( $variation_id, 'woovr_image', sanitize_text_field( $fields['woovr_image'] ) );
					}

					if ( ! empty( $fields['woovr_image_id'] ) ) {
						update_post_meta( $variation_id, 'woovr_image_id', sanitize_text_field( $fields['woovr_image_id'] ) );
					}
				}

				function variation_get_name( $name, $product ) {
					if ( ( $custom_name = get_post_meta( $product->get_id(), 'woovr_name', true ) ) && ! empty( $custom_name ) ) {
						return $custom_name;
					}

					return $name;
				}

				function post_class( $classes, $product ) {
					if ( $product->is_type( 'variable' ) ) {
						$product_id        = $product->get_id();
						$active            = self::get_setting( 'active', 'yes' );
						$show_price        = self::get_setting( 'show_price', 'yes' );
						$show_availability = self::get_setting( 'show_availability', 'yes' );
						$show_description  = self::get_setting( 'show_description', 'yes' );
						$_active           = get_post_meta( $product_id, '_woovr_active', true ) ?: 'default';

						if ( $_active === 'yes' ) {
							// overwrite settings
							$show_price        = get_post_meta( $product_id, '_woovr_show_price', true ) ?: $show_price;
							$show_availability = get_post_meta( $product_id, '_woovr_show_availability', true ) ?: $show_availability;
							$show_description  = get_post_meta( $product_id, '_woovr_show_description', true ) ?: $show_description;
						}

						if ( ( $_active === 'yes' ) || ( ( $_active === 'default' ) && ( $active === 'yes' ) ) ) {
							$classes[] = 'woovr-active';

							if ( $show_price === 'yes' ) {
								$classes[] = 'woovr-show-price';
							}

							if ( $show_availability === 'yes' ) {
								$classes[] = 'woovr-show-availability';
							}

							if ( $show_description === 'yes' ) {
								$classes[] = 'woovr-show-description';
							}
						}
					}

					return $classes;
				}

				static function data_attributes( $attrs ) {
					$attrs_arr = [];

					foreach ( $attrs as $key => $attr ) {
						$attrs_arr[] = 'data-' . sanitize_title( $key ) . '="' . esc_attr( $attr ) . '"';
					}

					return implode( ' ', $attrs_arr );
				}

				static function is_purchasable( $product ) {
					return $product->is_purchasable() && $product->is_in_stock() && $product->has_enough_stock( 1 );
				}

				public static function variations_form( $product, $variation = false, $context = '' ) {
					self::woovr_variations_form( $product, $variation, $context );
				}

				public static function woovr_variations_form( $product, $variation = false, $context = '', $allowed_terms = [] ) {
					$product_id         = $product->get_id();
					$unique_id          = uniqid( 'woovr_' . $product_id . '_' ); // compatible with WPC Product Bundles
					$active             = apply_filters( 'woovr_active', get_post_meta( $product_id, '_woovr_active', true ) ?: 'default', $product, $variation, $context );
					$show_clear         = apply_filters( 'woovr_show_clear', self::get_setting( 'show_clear', 'yes' ), $product, $variation, $context );
					$hide_unpurchasable = apply_filters( 'woovr_hide_unpurchasable', self::get_setting( 'hide_unpurchasable', 'no' ), $product, $variation, $context );

					// settings
					$selector          = apply_filters( 'woovr_default_selector', self::get_setting( 'selector', 'default' ), $product, $variation, $context );
					$show_name         = apply_filters( 'woovr_default_variation_name', self::get_setting( 'variation_name', 'formatted' ), $product, $variation, $context );
					$product_name      = apply_filters( 'woovr_default_product_name', self::get_setting( 'product_name', 'yes' ), $product, $variation, $context );
					$show_image        = apply_filters( 'woovr_default_show_image', self::get_setting( 'show_image', 'yes' ), $product, $variation, $context );
					$show_price        = apply_filters( 'woovr_default_show_price', self::get_setting( 'show_price', 'yes' ), $product, $variation, $context );
					$show_availability = apply_filters( 'woovr_default_show_availability', self::get_setting( 'show_availability', 'yes' ), $product, $variation, $context );
					$show_description  = apply_filters( 'woovr_default_show_description', self::get_setting( 'show_description', 'yes' ), $product, $variation, $context );
					$clear_label       = apply_filters( 'woovr_default_clear_label', self::get_setting( 'clear_label', esc_html__( 'Choose an option', 'wpc-variations-radio-buttons' ) ), $product, $variation, $context );
					$clear_image       = apply_filters( 'woovr_default_clear_image', self::get_setting( 'clear_image', 'placeholder' ), $product, $variation, $context );
					$clear_image_id    = apply_filters( 'woovr_default_clear_image_id', self::get_setting( 'clear_image_id', 0 ), $product, $variation, $context );

					if ( $active === 'yes' ) {
						// overwrite settings
						$selector          = get_post_meta( $product_id, '_woovr_selector', true ) ?: $selector;
						$show_name         = get_post_meta( $product_id, '_woovr_variation_name', true ) ?: $show_name;
						$show_image        = get_post_meta( $product_id, '_woovr_show_image', true ) ?: $show_image;
						$show_price        = get_post_meta( $product_id, '_woovr_show_price', true ) ?: $show_price;
						$show_availability = get_post_meta( $product_id, '_woovr_show_availability', true ) ?: $show_availability;
						$show_description  = get_post_meta( $product_id, '_woovr_show_description', true ) ?: $show_description;
						$clear_label       = ! empty( get_post_meta( $product_id, '_woovr_clear_label', true ) ) ? esc_html( get_post_meta( $product_id, '_woovr_clear_label', true ) ) : $clear_label;
						$clear_image       = get_post_meta( $product_id, '_woovr_clear_image', true ) ?: $clear_image;
						$clear_image_id    = get_post_meta( $product_id, '_woovr_clear_image_id', true ) ?: $clear_image_id;
					}

					if ( empty( $clear_label ) ) {
						$clear_label = esc_html__( 'Choose an option', 'wpc-variations-radio-buttons' );
					}

					// apply filters
					$clear_label       = apply_filters( 'woovr_clear_label', $clear_label, $product, $variation, $context );
					$clear_image       = apply_filters( 'woovr_clear_image', $clear_image, $product, $variation, $context );
					$clear_image_id    = apply_filters( 'woovr_clear_image_id', $clear_image_id, $product, $variation, $context );
					$selector          = apply_filters( 'woovr_selector', $selector, $product, $variation, $context );
					$show_name         = apply_filters( 'woovr_show_name', $show_name, $product, $variation, $context );
					$show_image        = apply_filters( 'woovr_show_image', $show_image, $product, $variation, $context );
					$show_price        = apply_filters( 'woovr_show_price', $show_price, $product, $variation, $context );
					$show_availability = apply_filters( 'woovr_show_availability', $show_availability, $product, $variation, $context );
					$show_description  = apply_filters( 'woovr_show_description', $show_description, $product, $variation, $context );

					// clear image src
					$clear_image_src = '';

					if ( $clear_image !== 'none' ) {
						$clear_image_src = wc_placeholder_img_src();

						if ( ( $clear_image === 'product' ) && ( $product_image_id = $product->get_image_id() ) ) {
							$product_image   = wp_get_attachment_image_src( $product_image_id );
							$clear_image_src = $product_image[0];
						}

						if ( ( $clear_image === 'custom' ) && $clear_image_id ) {
							$custom_image    = wp_get_attachment_image_src( $clear_image_id );
							$clear_image_src = $custom_image[0];
						}
					}

					$clear_image_src = apply_filters( 'woovr_clear_image_src', $clear_image_src, $product );

					// default attributes
					$df_attrs = [];

					if ( $variation ) {
						$df_attrs_o = $variation->get_attributes();
					} else {
						$df_attrs_o = $product->get_default_attributes();
					}

					foreach ( $df_attrs_o as $k => $v ) {
						$k_a              = 'attribute_' . str_replace( 'attribute_', '', $k );
						$df_attrs[ $k_a ] = $v;
					}

					$children = apply_filters( 'woovr_get_children', $product->get_children(), $product );

					if ( ! empty( $children ) ) {
						// build children data
						$children_data = [];

						foreach ( $children as $child ) {
							$child_product = wc_get_product( $child );

							if ( ! $child_product || ! $child_product->variation_is_visible() ) {
								continue;
							}

							if ( ( $hide_unpurchasable === 'yes' ) && ! self::is_purchasable( $child_product ) ) {
								continue;
							}

							$attrs         = [];
							$product_attrs = $product->get_attributes();
							$child_attrs   = $child_product->get_attributes();

							foreach ( $child_attrs as $k => $a ) {
								if ( $a === '' ) {
									if ( $product_attrs[ $k ]->get_id() ) {
										foreach ( $product_attrs[ $k ]->get_terms() as $term ) {
											if ( ! empty( $allowed_terms ) && ! empty( $allowed_terms[ $k ] ) ) {
												if ( ! in_array( $term->slug, $allowed_terms[ $k ] ) ) {
													continue;
												}
											}

											$attrs[ 'attribute_' . $k ][] = $term->slug;
										}
									} else {
										// custom attribute
										foreach ( $product_attrs[ $k ]->get_options() as $option ) {
											if ( ! empty( $allowed_terms ) && ! empty( $allowed_terms[ $k ] ) ) {
												if ( ! in_array( $option, $allowed_terms[ $k ] ) ) {
													continue;
												}
											}

											$attrs[ 'attribute_' . $k ][] = $option;
										}
									}
								} else {
									if ( ! empty( $allowed_terms ) && ! empty( $allowed_terms[ $k ] ) ) {
										if ( ! in_array( $a, $allowed_terms[ $k ] ) ) {
											continue 2;
										}
									}

									$attrs[ 'attribute_' . $k ][] = $a;
								}
							}

							$attrs = woovr_combinations( $attrs );

							foreach ( $attrs as $attr ) {
								$children_data[] = [
									'id'      => $child,
									'product' => $child_product,
									'attrs'   => $attr
								];
							}
						}

						$children_data = apply_filters( 'woovr_get_children_data', $children_data, $product );

						if ( ! empty( $children_data ) ) {
							do_action( 'woovr_variations_above', $product );

							echo '<div class="woovr-variations ' . esc_attr( 'woovr-variations-' . $selector ) . '" data-click="0" data-description="' . esc_attr( $show_description ) . '">';

							do_action( 'woovr_variations_before', $product );
							// should add a fieldset and legend

							if ( $selector === 'default' || $selector === 'grid' || $selector === 'grid-2' || $selector === 'grid-3' || $selector === 'grid-4' ) {
								// show choose an option
								if ( $show_clear === 'yes' ) {
									$data_attrs = apply_filters( 'woovr_data_attributes_option_none', [
										'id'            => 0,
										'pid'           => $product_id,
										'sku'           => '',
										'purchasable'   => 'no',
										'attrs'         => '',
										'price'         => 0,
										'regular-price' => 0,
										'pricehtml'     => '',
										'availability'  => '',
										'weight'        => '',
										'dimensions'    => ''
									] );

									$df_checked = empty( $df_attrs ) ? 'checked' : '';

									echo '<div class="woovr-variation woovr-variation-radio ' . ( empty( $df_attrs ) ? 'woovr-variation-active' : '' ) . '" ' . self::data_attributes( $data_attrs ) . '>';

									do_action( 'woovr_variation_before' );

									$radio_id = 'woovr_' . $product_id . '_0';
									echo apply_filters( 'woovr_variation_radio_selector', '<div class="woovr-variation-selector"><input type="radio" id="' . esc_attr( $radio_id ) . '" name="' . esc_attr( $unique_id ) . '" ' . $df_checked . '/></div>', $product_id, $df_checked, 0 );

									if ( ( $show_image === 'yes' ) && ( $clear_image !== 'none' ) ) {
										echo '<div class="woovr-variation-image">' . apply_filters( 'woovr_clear_image', '<img src="' . esc_url( $clear_image_src ) . '"/>', $product ) . '</div>';
									}

									echo '<div class="woovr-variation-info">';
									echo '<div class="woovr-variation-name"><label for="' . esc_attr( $radio_id ) . '">' . apply_filters( 'woovr_clear_name', $clear_label, $product ) . '</label></div>';
									echo '<div class="woovr-variation-description">' . apply_filters( 'woovr_clear_description', '', $product ) . '</div>';
									echo '</div><!-- /woovr-variation-info -->';

									do_action( 'woovr_variation_after' );

									echo '</div><!-- /woovr-variation -->';
								}

								// radio buttons
								foreach ( $children_data as $child_data ) {
									$child_id      = $child_data['id'];
									$child_product = $child_data['product'];
									$child_attrs   = htmlspecialchars( json_encode( $child_data['attrs'] ), ENT_QUOTES, 'UTF-8' );
									$child_checked = $child_data['attrs'] == $df_attrs ? 'checked' : '';

									// get name
									if ( ( $custom_name = get_post_meta( $child_id, 'woovr_name', true ) ) && ! empty( $custom_name ) ) {
										$child_name = $custom_name;
									} else {
										$child_name_arr = [];

										foreach ( $child_data['attrs'] as $k => $a ) {
											if ( $t = get_term_by( 'slug', $a, str_replace( 'attribute_', '', $k ) ) ) {
												$n = $t->name;
											} elseif ( $t = get_term_by( 'name', $a, str_replace( 'attribute_', '', $k ) ) ) {
												$n = $t->name;
											} else {
												$n = $a;
											}

											if ( $show_name === 'formatted_label' ) {
												$child_name_arr[] = wc_attribute_label( str_replace( 'attribute_', '', $k ), $product ) . ': ' . $n;
											} else {
												$child_name_arr[] = $n;
											}
										}

										$child_name = implode( ', ', $child_name_arr );

										if ( $product_name === 'yes' ) {
											$child_name = $product->get_name() . ' – ' . $child_name;
										}
									}

									// get image
									if ( $child_product->get_image_id() ) {
										$child_image     = wp_get_attachment_image_src( $child_product->get_image_id() );
										$child_image_src = $child_image[0];
									} else {
										$child_image_src = wc_placeholder_img_src();
									}

									// custom image
									if ( $child_image_id = get_post_meta( $child_id, 'woovr_image_id', true ) ) {
										$child_image     = wp_get_attachment_image_src( absint( $child_image_id ) );
										$child_image_src = $child_image[0];
									} elseif ( get_post_meta( $child_id, 'woovr_image', true ) ) {
										$child_image_src = esc_url( get_post_meta( $child_id, 'woovr_image', true ) );
									}

									$child_image_src = esc_url( apply_filters( 'woovr_variation_image_src', $child_image_src, $child_product ) );
									$child_images    = array_filter( explode( ',', get_post_meta( $child_id, 'wpcvi_images', true ) ) );
									$data_attrs      = apply_filters( 'woovr_data_attributes', [
										'id'            => $child_id,
										'pid'           => $product_id,
										'sku'           => $child_product->get_sku(),
										'purchasable'   => self::is_purchasable( $child_product ) ? 'yes' : 'no',
										'attrs'         => $child_attrs,
										'price'         => wc_get_price_to_display( $child_product ),
										'regular-price' => wc_get_price_to_display( $child_product, [ 'price' => $child_product->get_regular_price() ] ),
										'pricehtml'     => htmlentities( $child_product->get_price_html() ),
										'imagesrc'      => $child_image_src,
										'availability'  => htmlentities( wc_get_stock_html( $child_product ) ),
										'weight'        => htmlentities( wc_format_weight( $child_product->get_weight() ) ),
										'dimensions'    => htmlentities( wc_format_dimensions( $child_product->get_dimensions( false ) ) ),
										'images'        => ! empty( $child_images ) ? 'yes' : 'no'
									], $child_product );

									$child_class = 'woovr-variation woovr-variation-radio';

									if ( $child_checked === 'checked' ) {
										$child_class .= ' woovr-variation-active';
									}

									echo '<div class="' . esc_attr( $child_class ) . '" ' . self::data_attributes( $data_attrs ) . '>';

									do_action( 'woovr_variation_before', $child_product );

									$radio_id = 'woovr_' . $product_id . '_' . $child_id;
									echo apply_filters( 'woovr_variation_radio_selector', '<div class="woovr-variation-selector"><input type="radio" id="' . esc_attr( $radio_id ) . '" name="' . esc_attr( $unique_id ) . '" ' . $child_checked . '/></div>', $product_id, $child_checked, $child_id );

									if ( $show_image === 'yes' ) {
										echo '<div class="woovr-variation-image"><img src="' . $child_image_src . '"/></div>';
									}

									echo '<div class="woovr-variation-info">';
									$child_info = '<div class="woovr-variation-name"><label for="' . esc_attr( $radio_id ) . '">' . apply_filters( 'woovr_variation_name', $child_name, $child_product ) . '</label></div>';

									if ( $show_price === 'yes' ) {
										$child_info .= '<div class="woovr-variation-price">' . apply_filters( 'woovr_variation_price', $child_product->get_price_html(), $child_product ) . '</div>';
									}

									if ( $show_availability === 'yes' ) {
										$child_info .= '<div class="woovr-variation-availability">' . apply_filters( 'woovr_variation_availability', wc_get_stock_html( $child_product ), $child_product ) . '</div>';
									}

									if ( $show_description === 'yes' ) {
										$child_info .= '<div class="woovr-variation-description">' . apply_filters( 'woovr_variation_description', $child_product->get_description(), $child_product ) . '</div>';
									}

									echo apply_filters( 'woovr_variation_info', $child_info, $child_product );
									echo '</div><!-- /woovr-variation-info -->';

									do_action( 'woovr_variation_after', $child_product );

									echo '</div><!-- /woovr-variation -->';
								}
							} else {
								// dropdown
								echo '<div class="woovr-variation woovr-variation-dropdown">';

								if ( ( $selector === 'select' ) && ( $show_image === 'yes' ) ) {
									echo '<div class="woovr-variation-image">' . apply_filters( 'woovr_clear_image', '<img src="' . esc_url( $clear_image_src ) . '"/>', $product ) . '</div>';
								}

								echo '<div class="woovr-variation-selector"><select class="woovr-variation-select" id="' . esc_attr( $unique_id ) . '">';

								// show choose an option
								if ( $show_clear === 'yes' ) {
									$data_attrs = apply_filters( 'woovr_data_attributes_option_none', [
										'id'            => 0,
										'pid'           => $product_id,
										'sku'           => '',
										'purchasable'   => 'no',
										'attrs'         => '',
										'price'         => 0,
										'regular-price' => 0,
										'pricehtml'     => '',
										'imagesrc'      => $show_image === 'yes' ? $clear_image_src : '',
										'description'   => htmlentities( apply_filters( 'woovr_clear_description', '', $product ) ),
										'availability'  => ''
									] );
									echo '<option value="0" ' . self::data_attributes( $data_attrs ) . '>' . apply_filters( 'woovr_clear_name', $clear_label, $product ) . '</option>';
								}

								foreach ( $children_data as $child_data ) {
									$child_id       = $child_data['id'];
									$child_product  = $child_data['product'];
									$child_attrs    = htmlspecialchars( json_encode( $child_data['attrs'] ), ENT_QUOTES, 'UTF-8' );
									$child_selected = $child_data['attrs'] == $df_attrs ? 'selected' : '';

									// get name
									if ( ( $custom_name = get_post_meta( $child_id, 'woovr_name', true ) ) && ! empty( $custom_name ) ) {
										$child_name = $custom_name;
									} else {
										$child_name_arr = [];

										foreach ( $child_data['attrs'] as $k => $a ) {
											if ( $t = get_term_by( 'slug', $a, str_replace( 'attribute_', '', $k ) ) ) {
												$n = $t->name;
											} elseif ( $t = get_term_by( 'name', $a, str_replace( 'attribute_', '', $k ) ) ) {
												$n = $t->name;
											} else {
												$n = $a;
											}

											if ( $show_name === 'formatted_label' ) {
												$child_name_arr[] = wc_attribute_label( str_replace( 'attribute_', '', $k ), $product ) . ': ' . $n;
											} else {
												$child_name_arr[] = $n;
											}
										}

										$child_name = implode( ', ', $child_name_arr );

										if ( $product_name === 'yes' ) {
											$child_name = $product->get_name() . ' – ' . $child_name;
										}
									}

									// get image
									if ( $child_product->get_image_id() ) {
										$child_image     = wp_get_attachment_image_src( $child_product->get_image_id() );
										$child_image_src = $child_image[0];
									} else {
										$child_image_src = wc_placeholder_img_src();
									}

									// custom image
									if ( $child_image_id = get_post_meta( $child_id, 'woovr_image_id', true ) ) {
										$child_image     = wp_get_attachment_image_src( absint( $child_image_id ) );
										$child_image_src = $child_image[0];
									} elseif ( get_post_meta( $child_id, 'woovr_image', true ) ) {
										$child_image_src = esc_url( get_post_meta( $child_id, 'woovr_image', true ) );
									}

									$child_image_src = esc_url( apply_filters( 'woovr_variation_image_src', $child_image_src, $child_product ) );

									// get info
									$child_info = '';

									if ( $show_price === 'yes' ) {
										$child_info .= '<span class="woovr-variation-price">' . apply_filters( 'woovr_variation_price', $child_product->get_price_html(), $child_product ) . '</span>';
									}

									if ( $show_availability === 'yes' ) {
										$child_info .= '<span class="woovr-variation-availability">' . apply_filters( 'woovr_variation_availability', wc_get_stock_html( $child_product ), $child_product ) . '</span>';
									}

									if ( $show_description === 'yes' ) {
										$child_info .= '<span class="woovr-variation-description">' . apply_filters( 'woovr_variation_description', $child_product->get_description(), $child_product ) . '</span>';
									}

									$data_attrs = apply_filters( 'woovr_data_attributes', [
										'id'            => $child_id,
										'pid'           => $product_id,
										'sku'           => $child_product->get_sku(),
										'purchasable'   => self::is_purchasable( $child_product ) ? 'yes' : 'no',
										'attrs'         => $child_attrs,
										'price'         => wc_get_price_to_display( $child_product ),
										'regular-price' => wc_get_price_to_display( $child_product, [ 'price' => $child_product->get_regular_price() ] ),
										'pricehtml'     => htmlentities( $child_product->get_price_html() ),
										'imagesrc'      => $show_image === 'yes' ? $child_image_src : '',
										'description'   => htmlentities( apply_filters( 'woovr_variation_info', $child_info, $child_product ) ),
										'availability'  => htmlentities( wc_get_stock_html( $child_product ) )
									], $child_product );

									echo '<option value="' . $child_id . '" ' . self::data_attributes( $data_attrs ) . ' ' . $child_selected . '>' . apply_filters( 'woovr_variation_name', $child_name, $child_product ) . '</option>';
								}

								echo '</select></div><!-- /woovr-variation-selector -->';

								if ( ( $selector === 'select' ) && ( $show_price === 'yes' ) ) {
									echo '<div class="woovr-variation-price"></div>';
								}

								echo '</div><!-- /woovr-variation -->';
							}

							do_action( 'woovr_variations_after', $product );

							echo '</div><!-- /woovr-variations -->';

							do_action( 'woovr_variations_below', $product );
						}
					}
				}

				function wpcsm_locations( $locations ) {
					$locations['WPC Variations Radio Buttons'] = [
						'woovr_variations_above'  => esc_html__( 'Before variations wrap', 'wpc-variations-radio-buttons' ),
						'woovr_variations_below'  => esc_html__( 'After variations wrap', 'wpc-variations-radio-buttons' ),
						'woovr_variations_before' => esc_html__( 'Before variations', 'wpc-variations-radio-buttons' ),
						'woovr_variations_after'  => esc_html__( 'After variations', 'wpc-variations-radio-buttons' ),
						'woovr_variation_before'  => esc_html__( 'Before variation', 'wpc-variations-radio-buttons' ),
						'woovr_variation_after'   => esc_html__( 'After variation', 'wpc-variations-radio-buttons' ),
					];

					return $locations;
				}

				function duplicate_variation( $old_variation_id, $new_variation_id ) {
					if ( $name = get_post_meta( $old_variation_id, 'woovr_name', true ) ) {
						update_post_meta( $new_variation_id, 'woovr_name', $name );
					}

					if ( $image = get_post_meta( $old_variation_id, 'woovr_image', true ) ) {
						update_post_meta( $new_variation_id, 'woovr_image', $image );
					}

					if ( $image_id = get_post_meta( $old_variation_id, 'woovr_image_id', true ) ) {
						update_post_meta( $new_variation_id, 'woovr_image_id', $image_id );
					}
				}
			}

			return WPClever_Woovr::instance();
		}

		return null;
	}
}

if ( ! function_exists( 'woovr_combinations' ) ) {
	function woovr_combinations( $arrays ) {
		$result = [ [] ];

		foreach ( $arrays as $property => $property_values ) {
			$tmp = [];

			foreach ( $result as $result_item ) {
				foreach ( $property_values as $property_value ) {
					$tmp[] = array_merge( $result_item, [ $property => $property_value ] );
				}
			}

			$result = $tmp;
		}

		return apply_filters( 'woovr_combinations', $result );
	}
}

if ( ! function_exists( 'woovr_notice_wc' ) ) {
	function woovr_notice_wc() {
		?>
        <div class="error">
            <p><strong>WPC Variations Radio Buttons</strong> requires WooCommerce version 3.0 or greater.</p>
        </div>
		<?php
	}
}
