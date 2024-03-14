<?php
/*
Plugin Name: WPC Brands for WooCommerce
Plugin URI: https://wpclever.net/
Description: WPC Brands allows you to manage product brands in the easiest.
Version: 1.2.6
Author: WPClever
Author URI: https://wpclever.net
Text Domain: wpc-brands
Domain Path: /languages/
Requires at least: 4.0
Tested up to: 6.4
WC requires at least: 3.0
WC tested up to: 8.4
*/

use Automattic\WooCommerce\Utilities\FeaturesUtil;

defined( 'ABSPATH' ) || exit;

! defined( 'WPCBR_VERSION' ) && define( 'WPCBR_VERSION', '1.2.6' );
! defined( 'WPCBR_FILE' ) && define( 'WPCBR_FILE', __FILE__ );
! defined( 'WPCBR_URI' ) && define( 'WPCBR_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WPCBR_REVIEWS' ) && define( 'WPCBR_REVIEWS', 'https://wordpress.org/support/plugin/wpc-brands/reviews/?filter=5' );
! defined( 'WPCBR_CHANGELOG' ) && define( 'WPCBR_CHANGELOG', 'https://wordpress.org/plugins/wpc-brands/#developers' );
! defined( 'WPCBR_DISCUSSION' ) && define( 'WPCBR_DISCUSSION', 'https://wordpress.org/support/plugin/wpc-brands' );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WPCBR_URI );

include 'includes/dashboard/wpc-dashboard.php';
include 'includes/kit/wpc-kit.php';

if ( ! function_exists( 'wpcbr_init' ) ) {
	add_action( 'plugins_loaded', 'wpcbr_init', 11 );

	function wpcbr_init() {
		// load text-domain
		load_plugin_textdomain( 'wpc-brands', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'wpcbr_notice_wc' );

			return null;
		}

		if ( ! class_exists( 'WPCleverWpcbr' ) && class_exists( 'WC_Product' ) ) {
			class WPCleverWpcbr {
				protected static $settings = [];
				protected static $instance = null;

				public static function instance() {
					if ( is_null( self::$instance ) ) {
						self::$instance = new self();
					}

					return self::$instance;
				}

				function __construct() {
					self::$settings = (array) get_option( 'wpcbr_settings', [] );

					// init
					add_action( 'init', [ $this, 'wp_init' ] );
					add_action( 'woocommerce_init', [ $this, 'woo_init' ] );

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

					// backend brands
					add_action( 'wpc-brand_add_form_fields', [ $this, 'add_form_fields' ] );
					add_action( 'wpc-brand_edit_form_fields', [ $this, 'edit_form_fields' ] );
					add_action( 'edit_wpc-brand', [ $this, 'save_form_fields' ] );
					add_action( 'create_wpc-brand', [ $this, 'save_form_fields' ] );
					add_filter( 'manage_edit-wpc-brand_columns', [ $this, 'brand_columns' ] );
					add_filter( 'manage_wpc-brand_custom_column', [ $this, 'brand_columns_content' ], 10, 3 );

					// backend products
					add_filter( 'woocommerce_product_filters', [ $this, 'product_filter' ] );

					// archive
					add_action( 'woocommerce_archive_description', [ $this, 'brand_banner' ], 15 );

					// product tab
					if ( apply_filters( 'wpcbr_single_position', self::get_setting( 'single_position', 'after_meta' ) ) === 'tab' ) {
						add_filter( 'woocommerce_product_tabs', [ $this, 'product_tabs' ] );
					}

					// WPC Smart Messages
					add_filter( 'wpcsm_locations', [ $this, 'wpcsm_locations' ] );

					// HPOS compatibility
					add_action( 'before_woocommerce_init', function () {
						if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
							FeaturesUtil::declare_compatibility( 'custom_order_tables', WPCBR_FILE );
						}
					} );
				}

				function wp_init() {
					// image sizes
					add_image_size( 'wpcbr-logo', 96, 96 );

					// shortcode
					add_shortcode( 'wpcbr', [ $this, 'brand_shortcode' ] );
					add_shortcode( 'wpcbr_banner', [ $this, 'brand_banner_shortcode' ] );

					// show image for archive
					$archive_position = apply_filters( 'wpcbr_archive_position', self::get_setting( 'archive_position', 'after_title' ) );

					switch ( $archive_position ) {
						case 'before_thumbnail':
							add_action( 'woocommerce_before_shop_loop_item', [ $this, 'brand_archive' ], 9 );
							break;
						case 'before_title':
							add_action( 'woocommerce_shop_loop_item_title', [ $this, 'brand_archive' ], 9 );
							break;
						case 'after_title':
							add_action( 'woocommerce_shop_loop_item_title', [ $this, 'brand_archive' ], 11 );
							break;
						case 'after_rating':
							add_action( 'woocommerce_after_shop_loop_item_title', [ $this, 'brand_archive' ], 6 );
							break;
						case 'after_price':
							add_action( 'woocommerce_after_shop_loop_item_title', [ $this, 'brand_archive' ], 11 );
							break;
						case 'before_add_to_cart':
							add_action( 'woocommerce_after_shop_loop_item', [ $this, 'brand_archive' ], 9 );
							break;
						case 'after_add_to_cart':
							add_action( 'woocommerce_after_shop_loop_item', [ $this, 'brand_archive' ], 11 );
							break;
					}

					// show image for single
					$single_position = apply_filters( 'wpcbr_single_position', self::get_setting( 'single_position', 'after_meta' ) );

					switch ( $single_position ) {
						case 'before_title':
							add_action( 'woocommerce_single_product_summary', [ $this, 'brand_single' ], 4 );
							break;
						case 'after_title':
							add_action( 'woocommerce_single_product_summary', [ $this, 'brand_single' ], 6 );
							break;
						case 'after_price':
							add_action( 'woocommerce_single_product_summary', [ $this, 'brand_single' ], 11 );
							break;
						case 'after_excerpt':
							add_action( 'woocommerce_single_product_summary', [ $this, 'brand_single' ], 21 );
							break;
						case 'before_add_to_cart':
							add_action( 'woocommerce_single_product_summary', [ $this, 'brand_single' ], 29 );
							break;
						case 'after_add_to_cart':
							add_action( 'woocommerce_single_product_summary', [ $this, 'brand_single' ], 31 );
							break;
						case 'after_meta':
							add_action( 'woocommerce_single_product_summary', [ $this, 'brand_single' ], 41 );
							break;
						case 'after_sharing':
							add_action( 'woocommerce_single_product_summary', [ $this, 'brand_single' ], 51 );
							break;
					}
				}

				function woo_init() {
					$labels = [
						'name'                       => esc_html__( 'Brands', 'wpc-brands' ),
						'singular_name'              => esc_html__( 'Brand', 'wpc-brands' ),
						'menu_name'                  => esc_html__( 'Brands', 'wpc-brands' ),
						'all_items'                  => esc_html__( 'All Brands', 'wpc-brands' ),
						'edit_item'                  => esc_html__( 'Edit Brand', 'wpc-brands' ),
						'view_item'                  => esc_html__( 'View Brand', 'wpc-brands' ),
						'update_item'                => esc_html__( 'Update Brand', 'wpc-brands' ),
						'add_new_item'               => esc_html__( 'Add New Brand', 'wpc-brands' ),
						'new_item_name'              => esc_html__( 'New Brand Name', 'wpc-brands' ),
						'parent_item'                => esc_html__( 'Parent Brand', 'wpc-brands' ),
						'parent_item_colon'          => esc_html__( 'Parent Brand:', 'wpc-brands' ),
						'search_items'               => esc_html__( 'Search Brands', 'wpc-brands' ),
						'popular_items'              => esc_html__( 'Popular Brands', 'wpc-brands' ),
						'back_to_items'              => esc_html__( '&larr; Go to Brands', 'wpc-brands' ),
						'separate_items_with_commas' => esc_html__( 'Separate brands with commas', 'wpc-brands' ),
						'add_or_remove_items'        => esc_html__( 'Add or remove brands', 'wpc-brands' ),
						'choose_from_most_used'      => esc_html__( 'Choose from the most used brands', 'wpc-brands' ),
						'not_found'                  => esc_html__( 'No brands found', 'wpc-brands' )
					];

					$args = [
						'hierarchical'      => true,
						'labels'            => $labels,
						'show_ui'           => true,
						'query_var'         => true,
						'public'            => true,
						'show_admin_column' => true,
						'rewrite'           => [
							'slug'         => apply_filters( 'wpcbr_taxonomy_slug', self::get_setting( 'slug', 'brand' ) ),
							'hierarchical' => true,
							'with_front'   => apply_filters( 'wpcbr_taxonomy_with_front', true )
						]
					];

					register_taxonomy( 'wpc-brand', [ 'product' ], $args );
				}

				public static function get_settings() {
					return apply_filters( 'wpcbr_get_settings', self::$settings );
				}

				public static function get_setting( $name, $default = false ) {
					if ( ! empty( self::$settings ) && isset( self::$settings[ $name ] ) ) {
						$setting = self::$settings[ $name ];
					} else {
						$setting = get_option( 'wpcbr_' . $name, $default );
					}

					return apply_filters( 'wpcbr_get_setting', $setting, $name, $default );
				}

				function register_settings() {
					register_setting( 'wpcbr_settings', 'wpcbr_settings' );
				}

				function admin_menu() {
					add_submenu_page( 'wpclever', esc_html__( 'WPC Brands', 'wpc-brands' ), esc_html__( 'Brands', 'wpc-brands' ), 'manage_options', 'wpclever-wpcbr', [
						$this,
						'admin_menu_content'
					] );
				}

				function admin_menu_content() {
					add_thickbox();
					$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'settings';
					?>
                    <div class="wpclever_settings_page wrap">
                        <h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Brands', 'wpc-brands' ) . ' ' . WPCBR_VERSION; ?></h1>
                        <div class="wpclever_settings_page_desc about-text">
                            <p>
								<?php printf( esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'wpc-brands' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
                                <br/>
                                <a href="<?php echo esc_url( WPCBR_REVIEWS ); ?>" target="_blank"><?php esc_html_e( 'Reviews', 'wpc-brands' ); ?></a> |
                                <a href="<?php echo esc_url( WPCBR_CHANGELOG ); ?>" target="_blank"><?php esc_html_e( 'Changelog', 'wpc-brands' ); ?></a> |
                                <a href="<?php echo esc_url( WPCBR_DISCUSSION ); ?>" target="_blank"><?php esc_html_e( 'Discussion', 'wpc-brands' ); ?></a>
                            </p>
                        </div>
						<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) { ?>
                            <div class="notice notice-success is-dismissible">
                                <p><?php esc_html_e( 'Settings updated.', 'wpc-brands' ); ?></p>
                            </div>
						<?php } ?>
                        <div class="wpclever_settings_page_nav">
                            <h2 class="nav-tab-wrapper">
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-wpcbr&tab=settings' ); ?>" class="<?php echo esc_attr( $active_tab === 'settings' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Settings', 'wpc-brands' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-wpcbr&tab=shortcodes' ); ?>" class="<?php echo esc_attr( $active_tab === 'shortcodes' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Shortcodes', 'wpc-brands' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-kit' ); ?>" class="nav-tab">
									<?php esc_html_e( 'Essential Kit', 'wpc-brands' ); ?>
                                </a>
                            </h2>
                        </div>
                        <div class="wpclever_settings_page_content">
							<?php if ( $active_tab === 'settings' ) {
								if ( isset( $_REQUEST['settings-updated'] ) && $_REQUEST['settings-updated'] === 'true' ) {
									flush_rewrite_rules();
								}
								?>
                                <form method="post" action="options.php">
                                    <table class="form-table">
                                        <tr class="heading">
                                            <th colspan="2">
												<?php esc_html_e( 'General', 'wpc-brands' ); ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Slug', 'wpc-brands' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="wpcbr_settings[slug]" value="<?php echo esc_attr( self::get_setting( 'slug', 'brand' ) ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Logo size', 'wpc-brands' ); ?></th>
                                            <td>
												<?php
												$logo_size          = self::get_setting( 'logo_size', 'wpcbr-logo' );
												$logo_sizes         = self::image_sizes();
												$logo_sizes['full'] = [
													'width'  => '',
													'height' => '',
													'crop'   => false
												];

												if ( ! empty( $logo_sizes ) ) {
													echo '<select name="wpcbr_settings[logo_size]">';

													foreach ( $logo_sizes as $logo_size_name => $logo_size_data ) {
														echo '<option value="' . esc_attr( $logo_size_name ) . '" ' . ( $logo_size_name === $logo_size ? 'selected' : '' ) . '>' . esc_attr( $logo_size_name ) . ( ! empty( $logo_size_data['width'] ) ? ' ' . $logo_size_data['width'] . '&times;' . $logo_size_data['height'] : '' ) . ( $logo_size_data['crop'] ? ' (cropped)' : '' ) . '</option>';
													}

													echo '</select>';
												}
												?>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th colspan="2">
												<?php esc_html_e( 'Products archive', 'wpc-brands' ); ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Position', 'wpc-brands' ); ?></th>
                                            <td>
												<?php $archive_position = apply_filters( 'wpcbr_archive_position', 'default' ); ?>
                                                <select name="wpcbr_settings[archive_position]" <?php echo esc_attr( $archive_position !== 'default' ? 'disabled' : '' ); ?>>
													<?php if ( $archive_position === 'default' ) {
														$archive_position = self::get_setting( 'archive_position', 'after_title' );
													} ?>
                                                    <option value="before_thumbnail" <?php selected( $archive_position, 'before_thumbnail' ); ?>><?php esc_html_e( 'Above thumbnail', 'wpc-brands' ); ?></option>
                                                    <option value="before_title" <?php selected( $archive_position, 'before_title' ); ?>><?php esc_html_e( 'Above title', 'wpc-brands' ); ?></option>
                                                    <option value="after_title" <?php selected( $archive_position, 'after_title' ); ?>><?php esc_html_e( 'Under title', 'wpc-brands' ); ?></option>
                                                    <option value="after_rating" <?php selected( $archive_position, 'after_rating' ); ?>><?php esc_html_e( 'Under rating', 'wpc-brands' ); ?></option>
                                                    <option value="after_price" <?php selected( $archive_position, 'after_price' ); ?>><?php esc_html_e( 'Under price', 'wpc-brands' ); ?></option>
                                                    <option value="before_add_to_cart" <?php selected( $archive_position, 'before_add_to_cart' ); ?>><?php esc_html_e( 'Above add to cart button', 'wpc-brands' ); ?></option>
                                                    <option value="after_add_to_cart" <?php selected( $archive_position, 'after_add_to_cart' ); ?>><?php esc_html_e( 'Under add to cart button', 'wpc-brands' ); ?></option>
                                                    <option value="0" <?php echo esc_attr( ! $archive_position || ( $archive_position === '0' ) ? 'selected' : '' ); ?>><?php esc_html_e( 'None (hide it)', 'wpc-brands' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Type', 'wpc-brands' ); ?></th>
                                            <td>
												<?php $archive_type = apply_filters( 'wpcbr_archive_type', 'default' ); ?>
                                                <select name="wpcbr_settings[archive_type]" <?php echo esc_attr( $archive_type !== 'default' ? 'disabled' : '' ); ?>>
													<?php if ( $archive_type === 'default' ) {
														$archive_type = self::get_setting( 'archive_type', 'image' );
													} ?>
                                                    <option value="text" <?php selected( $archive_type, 'text' ); ?>><?php esc_html_e( 'Text', 'wpc-brands' ); ?></option>
                                                    <option value="image" <?php selected( $archive_type, 'image' ); ?>><?php esc_html_e( 'Image', 'wpc-brands' ); ?></option>
                                                    <option value="both" <?php selected( $archive_type, 'both' ); ?>><?php esc_html_e( 'Text & Image', 'wpc-brands' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th colspan="2">
												<?php esc_html_e( 'Single product', 'wpc-brands' ); ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Position', 'wpc-brands' ); ?></th>
                                            <td>
												<?php $single_position = apply_filters( 'wpcbr_single_position', 'default' ); ?>
                                                <select name="wpcbr_settings[single_position]" <?php echo esc_attr( $single_position !== 'default' ? 'disabled' : '' ); ?>>
													<?php if ( $single_position === 'default' ) {
														$single_position = self::get_setting( 'single_position', 'after_meta' );
													} ?>
                                                    <option value="before_title" <?php selected( $single_position, 'before_title' ); ?>><?php esc_html_e( 'Above title', 'wpc-brands' ); ?></option>
                                                    <option value="after_title" <?php selected( $single_position, 'after_title' ); ?>><?php esc_html_e( 'Under title', 'wpc-brands' ); ?></option>
                                                    <option value="after_price" <?php selected( $single_position, 'after_price' ); ?>><?php esc_html_e( 'Under price', 'wpc-brands' ); ?></option>
                                                    <option value="after_excerpt" <?php selected( $single_position, 'after_excerpt' ); ?>><?php esc_html_e( 'Under excerpt', 'wpc-brands' ); ?></option>
                                                    <option value="before_add_to_cart" <?php selected( $single_position, 'before_add_to_cart' ); ?>><?php esc_html_e( 'Above add to cart button', 'wpc-brands' ); ?></option>
                                                    <option value="after_add_to_cart" <?php selected( $single_position, 'after_add_to_cart' ); ?>><?php esc_html_e( 'Under add to cart button', 'wpc-brands' ); ?></option>
                                                    <option value="after_meta" <?php selected( $single_position, 'after_meta' ); ?>><?php esc_html_e( 'Under meta', 'wpc-brands' ); ?></option>
                                                    <option value="after_sharing" <?php selected( $single_position, 'after_sharing' ); ?>><?php esc_html_e( 'Under sharing', 'wpc-brands' ); ?></option>
                                                    <option value="tab" <?php selected( $single_position, 'tab' ); ?>><?php esc_html_e( 'In a new tab', 'wpc-brands' ); ?></option>
                                                    <option value="0" <?php echo esc_attr( ! $single_position ? 'selected' : '' ); ?>><?php esc_html_e( 'None (hide it)', 'wpc-brands' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Type', 'wpc-brands' ); ?></th>
                                            <td>
												<?php $single_type = apply_filters( 'wpcbr_single_type', 'default' ); ?>
                                                <select name="wpcbr_settings[single_type]" <?php echo esc_attr( $single_type !== 'default' ? 'disabled' : '' ); ?>>
													<?php if ( $single_type === 'default' ) {
														$single_type = self::get_setting( 'single_type', 'image' );
													} ?>
                                                    <option value="text" <?php selected( $single_type, 'text' ); ?>><?php esc_html_e( 'Text', 'wpc-brands' ); ?></option>
                                                    <option value="image" <?php selected( $single_type, 'image' ); ?>><?php esc_html_e( 'Image', 'wpc-brands' ); ?></option>
                                                    <option value="both" <?php selected( $single_type, 'both' ); ?>><?php esc_html_e( 'Text & Image', 'wpc-brands' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th colspan="2"><?php esc_html_e( 'Suggestion', 'wpc-brands' ); ?></th>
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
												<?php settings_fields( 'wpcbr_settings' ); ?><?php submit_button(); ?>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'shortcodes' ) { ?>
                                <table class="form-table">
                                    <tr>
                                        <th scope="row">[wpcbr]</th>
                                        <td>
                                            Brands list for a single product.

                                            <ul class="wpcbr_shortcode_attrs">
                                                <li><em>product_id</em> - (optional) product ID</li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">[wpcbr_banner]</th>
                                        <td>
                                            Brand banner.

                                            <ul class="wpcbr_shortcode_attrs">
                                                <li><em>id</em> - (optional) brand ID</li>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
							<?php } ?>
                        </div>
                    </div>
					<?php
				}

				function admin_enqueue_scripts() {
					wp_enqueue_media();
					wp_enqueue_editor();
					wp_enqueue_style( 'wpcbr-backend', WPCBR_URI . 'assets/css/backend.css', [], WPCBR_VERSION );
					wp_enqueue_script( 'wpcbr-backend', WPCBR_URI . 'assets/js/backend.js', [ 'jquery' ], WPCBR_VERSION, true );
				}

				function enqueue_scripts() {
					wp_enqueue_style( 'wpcbr-frontend', WPCBR_URI . 'assets/css/frontend.css', [], WPCBR_VERSION );
				}

				function action_links( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$settings = '<a href="' . admin_url( 'admin.php?page=wpclever-wpcbr&tab=settings' ) . '">' . esc_html__( 'Settings', 'wpc-brands' ) . '</a>';
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
							'support' => '<a href="' . esc_url( WPCBR_DISCUSSION ) . '" target="_blank">' . esc_html__( 'Community support', 'wpc-brands' ) . '</a>',
						];

						return array_merge( $links, $row_meta );
					}

					return (array) $links;
				}

				function product_tabs( $tabs ) {
					global $product;

					if ( $product ) {
						$product_id = $product->get_id();
						$brands     = wc_get_product_terms( $product_id, 'wpc-brand' );

						if ( is_array( $brands ) && ! empty( $brands ) ) {
							$tabs['wpcbr'] = [
								'title'    => esc_html__( 'Brands', 'wpc-brands' ),
								'priority' => 50,
								'callback' => [ $this, 'product_tabs_callback' ]
							];
						}
					}

					return $tabs;
				}

				function product_tabs_callback() {
					echo do_shortcode( '[wpcbr context="tab"]' );
				}

				function form_fields( $term = null ) {
					if ( $term ) {
						$logo        = get_term_meta( $term->term_id, 'wpcbr_logo', true ) ?: '';
						$banner      = get_term_meta( $term->term_id, 'wpcbr_banner', true ) ?: '';
						$banner_link = get_term_meta( $term->term_id, 'wpcbr_banner_link', true ) ?: '';
						$table_start = '<table class="form-table">';
						$table_end   = '</table>';
						$tr_start    = '<tr class="form-field">';
						$tr_end      = '</tr>';
						$th_start    = '<th scope="row">';
						$th_end      = '</th>';
						$td_start    = '<td>';
						$td_end      = '</td>';
					} else {
						// add new
						$logo        = '';
						$banner      = '';
						$banner_link = '';
						$table_start = '';
						$table_end   = '';
						$tr_start    = '<div class="form-field">';
						$tr_end      = '</div>';
						$th_start    = '';
						$th_end      = '';
						$td_start    = '';
						$td_end      = '';
					}

					echo $table_start . $tr_start . $th_start;
					?>
                    <label for="wpcbr_description"><?php esc_html_e( 'Description', 'wpc-brands' ); ?></label>
					<?php
					echo $th_end . $td_start;
					?>
                    <div class="wpcbr_editor">
						<?php
						if ( $term ) {
							wp_editor( html_entity_decode( stripcslashes( $term->description ) ), 'wpcbr_description', [
								'textarea_name' => 'wpcbr_description',
								'textarea_rows' => 5
							] );
						} else {
							echo '<textarea name="wpcbr_description" class="wpcbr_description" cols="50" rows="5"></textarea>';
						}
						?>
                        <p><?php esc_html_e( 'Description for the brand archive page. You can include some HTML markup and shortcodes.', 'wpc-brands' ); ?></p>
                    </div>
					<?php
					echo $td_end . $tr_end;
					// new row
					echo $tr_start . $th_start;
					?>
                    <label for="wpcbr_logo"><?php esc_html_e( 'Logo', 'wpc-brands' ); ?></label>
					<?php
					echo $th_end . $td_start;
					?>
                    <div class="wpcbr_image_uploader">
                        <input type="hidden" name="wpcbr_logo" id="wpcbr_logo" class="wpcbr_image_val" value="<?php echo esc_attr( $logo ); ?>"/>
                        <a href="#" id="wpcbr_logo_select" class="button"><?php esc_html_e( 'Select image', 'wpc-brands' ); ?></a>
                        <div class="wpcbr_selected_image" <?php echo( empty( $logo ) ? 'style="display: none"' : '' ); ?>>
                            <span class="wpcbr_selected_image_img"><?php echo wp_get_attachment_image( $logo ); ?></span>
                            <span class="wpcbr_remove_image"><?php esc_html_e( '× remove', 'wpc-brands' ); ?></span>
                        </div>
                    </div>
					<?php
					echo $td_end . $tr_end;
					// new row
					echo $tr_start . $th_start;
					?>
                    <label for="wpcbr_banner"><?php esc_html_e( 'Banner', 'wpc-brands' ); ?></label>
					<?php
					echo $th_end . $td_start;
					?>
                    <div class="wpcbr_image_uploader">
                        <input type="hidden" name="wpcbr_banner" id="wpcbr_banner" class="wpcbr_image_val" value="<?php echo esc_html( $banner ); ?>"/>
                        <a href="#" id="wpcbr_banner_select" class="button"><?php esc_html_e( 'Select image', 'wpc-brands' ); ?></a>
                        <div class="wpcbr_selected_image" <?php echo( empty( $banner ) ? 'style="display: none"' : '' ); ?>>
                            <span class="wpcbr_selected_image_img"><?php echo wp_get_attachment_image( $banner, 'full' ); ?></span>
                            <span class="wpcbr_remove_image"><?php esc_html_e( '× remove', 'wpc-brands' ); ?></span>
                        </div>
                    </div>
					<?php
					echo $td_end . $tr_end;
					// new row
					echo $tr_start . $th_start;
					?>
                    <label for="wpcbr_banner_link"><?php esc_html_e( 'Banner link', 'wpc-brands' ); ?></label>
					<?php
					echo $th_end . $td_start;
					?>
                    <input type="url" name="wpcbr_banner_link" id="wpcbr_banner_link" value="<?php echo esc_url( $banner_link ); ?>"/>
					<?php
					echo $td_end . $tr_end . $table_end;
				}

				function add_form_fields() {
					self::form_fields();
				}

				function edit_form_fields( $term ) {
					self::form_fields( $term );
				}

				function save_form_fields( $term_id ) {
					flush_rewrite_rules();

					if ( isset( $_POST['wpcbr_logo'] ) ) {
						update_term_meta( $term_id, 'wpcbr_logo', sanitize_text_field( $_POST['wpcbr_logo'] ) );
					}

					if ( isset( $_POST['wpcbr_banner'] ) ) {
						update_term_meta( $term_id, 'wpcbr_banner', sanitize_text_field( $_POST['wpcbr_banner'] ) );
					}

					if ( isset( $_POST['wpcbr_banner_link'] ) ) {
						update_term_meta( $term_id, 'wpcbr_banner_link', sanitize_url( $_POST['wpcbr_banner_link'] ) );
					}

					if ( isset( $_POST['wpcbr_description'] ) ) {
						global $wpdb;
						$wpdb->update( $wpdb->term_taxonomy, [ 'description' => sanitize_post_field( 'post_content', $_POST['wpcbr_description'], 0, 'db' ) ], [ 'term_id' => $term_id ] );
					}
				}

				function brand_columns( $columns ) {
					return [
						'cb'          => isset( $columns['cb'] ) ? $columns['cb'] : 'cb',
						'logo'        => esc_html__( 'Logo', 'wpc-brands' ),
						'name'        => esc_html__( 'Name', 'wpc-brands' ),
						'description' => esc_html__( 'Description', 'wpc-brands' ),
						'slug'        => esc_html__( 'Slug', 'wpc-brands' ),
						'posts'       => esc_html__( 'Count', 'wpc-brands' ),
					];
				}

				function brand_columns_content( $column, $column_name, $term_id ) {
					if ( $column_name === 'logo' ) {
						$image = wp_get_attachment_image( get_term_meta( $term_id, 'wpcbr_logo', 1 ), [
							'40',
							'40'
						] );

						return $image ?: wc_placeholder_img( [ '40', '40' ] );
					}

					return $column;
				}

				function product_filter( $filters ) {
					global $wp_query;

					$current_brand = ( ! empty( $wp_query->query['wpc-brand'] ) ? $wp_query->query['wpc-brand'] : '' );
					$terms         = get_terms( 'wpc-brand' );

					if ( empty( $terms ) ) {
						return $filters;
					}

					$args = [
						'pad_counts'         => 1,
						'count'              => 1,
						'hierarchical'       => 1,
						'hide_empty'         => 1,
						'show_uncategorized' => 1,
						'orderby'            => 'name',
						'selected'           => $current_brand,
						'menu_order'         => false
					];

					$filters       = $filters . PHP_EOL;
					$taxonomy_name = 'wpc-brand';
					$filters       .= '<select name="' . $taxonomy_name . '">';
					$filters       .= '<option value="" ' . selected( $current_brand, '', false ) . '>' . esc_html__( 'Filter by brand', 'wpc-brands' ) . '</option>';
					$filters       .= wc_walk_category_dropdown_tree( $terms, 0, $args );
					$filters       .= "</select>";

					return $filters;
				}

				function brand_shortcode( $attrs ) {
					ob_start();

					$attrs = shortcode_atts( [
						'product_id' => null,
						'context'    => null,
						'type'       => null
					], $attrs, 'wpcbr' );

					if ( ! $attrs['product_id'] ) {
						global $product;

						if ( $product ) {
							$attrs['product_id'] = $product->get_id();
						}
					}

					if ( $attrs['product_id'] ) {
						$brands = wc_get_product_terms( $attrs['product_id'], 'wpc-brand' );

						if ( is_array( $brands ) && ! empty( $brands ) ) {
							if ( 'single' === $attrs['context'] ) {
								$type = self::get_setting( 'single_type', 'image' );
							} elseif ( 'archive' === $attrs['context'] ) {
								$type = self::get_setting( 'archive_type', 'image' );
							} else {
								$type = 'full';
							}

							if ( $attrs['type'] ) {
								$type = $attrs['type'];
							}

							echo '<div class="wpcbr-wrap wpcbr-wrap-' . $type . '">';

							do_action( 'wpcbr_before_wrap', $attrs );

							if ( 'text' === $type ) {
								echo get_the_term_list( $attrs['product_id'], 'wpc-brand', esc_html__( 'Brand: ', 'wpc-brands' ), ', ' );
							} else {
								echo '<div class="wpcbr-brands">';

								do_action( 'wpcbr_before_brands', $attrs );

								foreach ( $brands as $brand ) {
									echo '<div class="wpcbr-brand wpcbr-brand-' . $brand->term_id . '">';
									do_action( 'wpcbr_before_brand', $brand );

									$logo_id = get_term_meta( $brand->term_id, 'wpcbr_logo', true );

									if ( $logo_id && ( $type === 'image' || $type === 'both' || $type === 'full' ) ) {
										$logo_size = self::get_setting( 'logo_size', 'wpcbr-logo' );
										echo '<span class="wpcbr-brand-image">';
										do_action( 'wpcbr_before_brand_image', $brand );
										echo '<a href="' . get_term_link( $brand->term_id ) . '" rel="brand"><img src="' . wp_get_attachment_image_url( $logo_id, $logo_size ) . '" alt="' . esc_attr( $brand->name ) . '"/></a>';
										do_action( 'wpcbr_after_brand_image', $brand );
										echo '</span>';
									}

									if ( $type === 'both' || $type === 'full' ) {
										echo '<span class="wpcbr-brand-info">';
										do_action( 'wpcbr_before_brand_info', $brand );

										echo '<span class="wpcbr-brand-name">';
										do_action( 'wpcbr_before_brand_name', $brand );
										echo '<a href="' . get_term_link( $brand->term_id ) . '" rel="brand">' . apply_filters( 'wpcbr_brand_name', $brand->name, $brand ) . '</a>';
										do_action( 'wpcbr_after_brand_name', $brand );
										echo '</span>';

										if ( ! empty( $brand->description ) && $type === 'full' ) {
											echo '<span class="wpcbr-brand-description">';
											do_action( 'wpcbr_before_brand_description', $brand );
											echo apply_filters( 'wpcbr_brand_description', $brand->description, $brand );
											do_action( 'wpcbr_after_brand_description', $brand );
											echo '</span>';
										}

										do_action( 'wpcbr_after_brand_info', $brand );
										echo '</span>';
									}

									do_action( 'wpcbr_after_brand', $brand );
									echo '</div>';
								}

								do_action( 'wpcbr_after_brands', $attrs );

								echo '</div>';
							}

							do_action( 'wpcbr_after_wrap', $attrs );

							echo '</div>';
						}
					}

					return apply_filters( 'wpcbr_shortcode', ob_get_clean(), $attrs );
				}

				function wpcsm_locations( $locations ) {
					$locations['WPC Brands'] = [
						'wpcbr_before_wrap'              => esc_html__( 'Before wrapper', 'wpc-brands' ),
						'wpcbr_after_wrap'               => esc_html__( 'After wrapper', 'wpc-brands' ),
						'wpcbr_before_brands'            => esc_html__( 'Before brand list', 'wpc-brands' ),
						'wpcbr_after_brands'             => esc_html__( 'After brand list', 'wpc-brands' ),
						'wpcbr_before_brand'             => esc_html__( 'Before brand', 'wpc-brands' ),
						'wpcbr_after_brand'              => esc_html__( 'After brand', 'wpc-brands' ),
						'wpcbr_before_brand_image'       => esc_html__( 'Before brand image', 'wpc-brands' ),
						'wpcbr_after_brand_image'        => esc_html__( 'After brand image', 'wpc-brands' ),
						'wpcbr_before_brand_name'        => esc_html__( 'Before brand name', 'wpc-brands' ),
						'wpcbr_after_brand_name'         => esc_html__( 'After brand name', 'wpc-brands' ),
						'wpcbr_before_brand_description' => esc_html__( 'Before brand description', 'wpc-brands' ),
						'wpcbr_after_brand_description'  => esc_html__( 'After brand description', 'wpc-brands' ),
					];

					return $locations;
				}

				function brand_archive() {
					echo do_shortcode( '[wpcbr context="archive"]' );
				}

				function brand_single() {
					echo do_shortcode( '[wpcbr context="single"]' );
				}

				function brand_banner_shortcode( $attrs ) {
					ob_start();

					$attrs = shortcode_atts( [
						'id' => null,
					], $attrs, 'wpcbr_banner' );

					if ( ! $attrs['id'] ) {
						$brand = get_queried_object();

						if ( $brand && $brand->term_id ) {
							$attrs['id'] = $brand->term_id;
						}
					}

					if ( $attrs['id'] ) {
						$banner = get_term_meta( $attrs['id'], 'wpcbr_banner', true );
						$link   = get_term_meta( $attrs['id'], 'wpcbr_banner_link', true );

						if ( $banner ) {
							echo '<div class="wpcbr-banner">';

							if ( ! empty( $link ) ) {
								if ( strpos( $link, 'http' ) === false ) {
									$link = site_url( $link );
								}

								echo '<a href="' . esc_url( $link ) . '">' . wp_get_attachment_image( $banner, 'full' ) . '</a>';
							} else {
								echo wp_get_attachment_image( $banner, 'full' );
							}

							echo '</div>';
						}
					}

					return apply_filters( 'wpcbr_banner_shortcode', ob_get_clean(), $attrs );
				}

				function brand_banner() {
					if ( ! is_tax( 'wpc-brand' ) || is_paged() ) {
						return;
					}

					echo do_shortcode( '[wpcbr_banner]' );
				}

				// extra
				function image_sizes() {
					global $_wp_additional_image_sizes;
					$sizes = [];

					foreach ( get_intermediate_image_sizes() as $_size ) {
						if ( in_array( $_size, [ 'thumbnail', 'medium', 'medium_large', 'large' ] ) ) {
							$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
							$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
							$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
						} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
							$sizes[ $_size ] = [
								'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
								'height' => $_wp_additional_image_sizes[ $_size ]['height'],
								'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
							];
						}
					}

					return $sizes;
				}
			}

			return WPCleverWpcbr::instance();
		}

		return null;
	}
}

if ( ! function_exists( 'wpcbr_notice_wc' ) ) {
	function wpcbr_notice_wc() {
		?>
        <div class="error">
            <p><strong>WPC Brands</strong> requires WooCommerce version 3.0 or greater.</p>
        </div>
		<?php
	}
}
