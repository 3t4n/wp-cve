<?php
/*
Plugin Name: WPC Product Size Chart for WooCommerce
Plugin URI: https://wpclever.net/
Description: Ultimate solution to manage WooCommerce product size charts.
Version: 2.1.1
Author: WPClever
Author URI: https://wpclever.net
Text Domain: wpc-product-size-chart
Domain Path: /languages/
Requires Plugins: woocommerce
Requires at least: 4.0
Tested up to: 6.4
WC requires at least: 3.0
WC tested up to: 8.6
*/

defined( 'ABSPATH' ) || exit;

! defined( 'WPCSC_VERSION' ) && define( 'WPCSC_VERSION', '2.1.1' );
! defined( 'WPCSC_LITE' ) && define( 'WPCSC_LITE', __FILE__ );
! defined( 'WPCSC_FILE' ) && define( 'WPCSC_FILE', __FILE__ );
! defined( 'WPCSC_URI' ) && define( 'WPCSC_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WPCSC_DIR' ) && define( 'WPCSC_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'WPCSC_SUPPORT' ) && define( 'WPCSC_SUPPORT', 'https://wpclever.net/support?utm_source=support&utm_medium=wpcsc&utm_campaign=wporg' );
! defined( 'WPCSC_REVIEWS' ) && define( 'WPCSC_REVIEWS', 'https://wordpress.org/support/plugin/wpc-product-size-chart/reviews/?filter=5' );
! defined( 'WPCSC_CHANGELOG' ) && define( 'WPCSC_CHANGELOG', 'https://wordpress.org/plugins/wpc-product-size-chart/#developers' );
! defined( 'WPCSC_DISCUSSION' ) && define( 'WPCSC_DISCUSSION', 'https://wordpress.org/support/plugin/wpc-product-size-chart' );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WPCSC_URI );

include 'includes/dashboard/wpc-dashboard.php';
include 'includes/kit/wpc-kit.php';
include 'includes/hpos.php';

if ( ! function_exists( 'wpcsc_init' ) ) {
	add_action( 'plugins_loaded', 'wpcsc_init', 11 );

	function wpcsc_init() {
		// load text-domain
		load_plugin_textdomain( 'wpc-product-size-chart', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'wpcsc_notice_wc' );

			return null;
		}

		if ( ! class_exists( 'WPCleverWpcsc' ) && class_exists( 'WC_Product' ) ) {
			class WPCleverWpcsc {
				protected static $instance = null;
				protected static $settings = [];

				public static function instance() {
					if ( is_null( self::$instance ) ) {
						self::$instance = new self();
					}

					return self::$instance;
				}

				function __construct() {
					self::$settings = (array) get_option( 'wpcsc_settings', [] );

					// init
					add_action( 'init', [ $this, 'init' ] );

					// meta box
					add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
					add_action( 'save_post_wpc_size_chart', [ $this, 'save_size_chart' ] );

					// column
					add_filter( 'manage_edit-wpc_size_chart_columns', [ $this, 'custom_column' ] );
					add_action( 'manage_wpc_size_chart_posts_custom_column', [ $this, 'custom_column_value' ], 10, 2 );

					// enqueue
					add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
					add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

					// settings page
					add_action( 'admin_init', [ $this, 'register_settings' ] );
					add_action( 'admin_menu', [ $this, 'admin_menu' ] );

					// settings link
					add_filter( 'plugin_action_links', [ $this, 'action_links' ], 10, 2 );
					add_filter( 'plugin_row_meta', [ $this, 'row_meta' ], 10, 2 );

					// add tab
					if ( self::get_setting( 'position', 'above_atc' ) === 'tab' ) {
						add_filter( 'woocommerce_product_tabs', [ $this, 'product_tabs' ] );
					}

					// single product page
					switch ( self::get_setting( 'position', 'above_atc' ) ) {
						case 'below_title';
							add_action( 'woocommerce_single_product_summary', [ $this, 'size_charts_list' ], 6 );
							break;
						case 'below_price':
							add_action( 'woocommerce_single_product_summary', [ $this, 'size_charts_list' ], 11 );
							break;
						case 'below_excerpt';
							add_action( 'woocommerce_single_product_summary', [ $this, 'size_charts_list' ], 21 );
							break;
						case 'above_atc';
							add_action( 'woocommerce_single_product_summary', [ $this, 'size_charts_list' ], 29 );
							break;
						case 'below_atc';
							add_action( 'woocommerce_single_product_summary', [ $this, 'size_charts_list' ], 31 );
							break;
						case 'below_meta';
							add_action( 'woocommerce_single_product_summary', [ $this, 'size_charts_list' ], 41 );
							break;
						case 'below_sharing';
							add_action( 'woocommerce_single_product_summary', [ $this, 'size_charts_list' ], 51 );
							break;
					}

					// ajax backend
					add_action( 'wp_ajax_wpcsc_search_size_chart', [ $this, 'ajax_search_size_chart' ] );
					add_action( 'wp_ajax_wpcsc_add_combined', [ $this, 'ajax_add_combined' ] );
					add_action( 'wp_ajax_wpcsc_search_term', [ $this, 'ajax_search_term' ] );

					// ajax get chart
					add_action( 'wp_ajax_wpcsc_get_chart', [ $this, 'ajax_get_chart' ] );
					add_action( 'wp_ajax_nopriv_wpcsc_get_chart', [ $this, 'ajax_get_chart' ] );

					// ajax get charts
					add_action( 'wp_ajax_wpcsc_get_charts', [ $this, 'ajax_get_charts' ] );
					add_action( 'wp_ajax_nopriv_wpcsc_get_charts', [ $this, 'ajax_get_charts' ] );

					// footer
					add_action( 'wp_footer', [ $this, 'footer' ] );

					// product data
					add_filter( 'woocommerce_product_data_tabs', [ $this, 'product_data_tabs' ] );
					add_action( 'woocommerce_product_data_panels', [ $this, 'product_data_panels' ] );
					add_action( 'woocommerce_process_product_meta', [ $this, 'process_product_meta' ] );
				}

				function init() {
					$labels = [
						'name'          => _x( 'Size Charts', 'Post Type General Name', 'wpc-product-size-chart' ),
						'singular_name' => _x( 'Size Chart', 'Post Type Singular Name', 'wpc-product-size-chart' ),
						'add_new_item'  => esc_html__( 'Add New Size Chart', 'wpc-product-size-chart' ),
						'add_new'       => esc_html__( 'Add New', 'wpc-product-size-chart' ),
						'edit_item'     => esc_html__( 'Edit Size Chart', 'wpc-product-size-chart' ),
						'update_item'   => esc_html__( 'Update Size Chart', 'wpc-product-size-chart' ),
						'search_items'  => esc_html__( 'Search Size Chart', 'wpc-product-size-chart' ),
					];

					$args = [
						'label'               => esc_html__( 'Size Chart', 'wpc-product-size-chart' ),
						'labels'              => $labels,
						'supports'            => [ 'title', 'excerpt' ],
						'hierarchical'        => false,
						'public'              => false,
						'show_ui'             => true,
						'show_in_menu'        => true,
						'show_in_nav_menus'   => true,
						'show_in_admin_bar'   => true,
						'menu_position'       => 28,
						'menu_icon'           => 'dashicons-editor-table',
						'can_export'          => true,
						'has_archive'         => false,
						'exclude_from_search' => true,
						'publicly_queryable'  => false,
						'capability_type'     => 'post',
						'show_in_rest'        => false,
					];

					register_post_type( 'wpc_size_chart', $args );

					// shortcode
					add_shortcode( 'wpcsc', [ $this, 'shortcode' ] );
					add_shortcode( 'wpcsc_product', [ $this, 'shortcode_product' ] );
					add_shortcode( 'wpcsc_link', [ $this, 'shortcode_link' ] );
				}

				function add_meta_boxes() {
					add_meta_box( 'wpcsc_configuration', esc_html__( 'Configuration', 'wpc-product-size-chart' ), [
						$this,
						'configuration_callback'
					], 'wpc_size_chart', 'advanced', 'low' );
					add_meta_box( 'wpcsc_shortcode', esc_html__( 'Shortcode', 'wpc-product-size-chart' ), [
						$this,
						'shortcode_callback'
					], 'wpc_size_chart', 'side', 'default' );
				}

				function configuration_callback( $post ) {
					$post_id        = $post->ID;
					$type           = ! empty( get_post_meta( $post_id, 'type', true ) ) ? get_post_meta( $post_id, 'type', true ) : 'none';
					$terms          = get_post_meta( $post_id, 'terms', true );
					$combined       = (array) ( get_post_meta( $post_id, 'combined', true ) ?: [] );
					$above_text     = get_post_meta( $post_id, 'above_text', true );
					$under_text     = get_post_meta( $post_id, 'under_text', true );
					$table_data     = get_post_meta( $post_id, 'table_data', true ) ?: '[["&nbsp;"]]';
					$table_data_arr = json_decode( $table_data );
					?>
                    <div class="wpcsc_configuration_table">
                        <div class="wpcsc_configuration_tr">
                            <div class="wpcsc_configuration_th">
								<?php esc_html_e( 'Apply', 'wpc-product-size-chart' ); ?>
                            </div>
                            <div class="wpcsc_configuration_td">
                                <div>
                                    <p class="description"><?php esc_html_e( 'Select which products you want to add this Size Charts automatically. If "None" is set, you can still manually choose to add this in the "Size Charts" tab of each individual product page.', 'wpc-product-size-chart' ); ?></p>
                                    <select name="wpcsc_type" class="wpcsc_type">
                                        <option value="none" <?php selected( $type, 'none' ); ?>><?php esc_html_e( 'None', 'wpc-product-size-chart' ); ?></option>
                                        <option value="all" <?php selected( $type, 'all' ); ?>><?php esc_html_e( 'All products', 'wpc-product-size-chart' ); ?></option>
                                        <option value="combined" <?php selected( $type, 'combined' ); ?>><?php esc_html_e( 'Combined (Premium)', 'wpc-product-size-chart' ); ?></option>
										<?php
										$taxonomies = get_object_taxonomies( 'product', 'objects' ); //$taxonomies = get_taxonomies( [ 'object_type' => [ 'product' ] ], 'objects' );

										foreach ( $taxonomies as $taxonomy ) {
											echo '<option value="' . esc_attr( $taxonomy->name ) . '" ' . selected( $type, $taxonomy->name, false ) . '>' . esc_html( $taxonomy->label ) . '</option>';
										}
										?>
                                    </select>
                                </div>
                                <div class="wpcsc_type_row wpcsc_type_combined">
                                    <div class="wpcsc_combination">
                                        <p class="description" style="color: #c9356e">This feature is only available on Premium Version. Click
                                            <a href="https://wpclever.net/downloads/wpc-product-size-chart?utm_source=pro&utm_medium=wpcsc&utm_campaign=wporg" target="_blank">here</a> to buy, just $29!
                                        </p>
                                        <p class="description"><?php esc_html_e( 'Configure to find products that match all listed conditions.', 'wpc-product-size-chart' ); ?></p>
										<?php
										if ( ! empty( $combined ) ) {
											foreach ( $combined as $cmb_key => $cmb ) {
												self::combined( $cmb_key, $cmb );
											}
										}
										?>
                                    </div>
                                    <div class="wpcsc_add_combined">
                                        <a class="wpcsc_new_combined" href="#"><?php esc_attr_e( '+ Add condition', 'wpc-product-size-chart' ); ?></a>
                                    </div>
                                </div>
                                <div class="wpcsc_type_row wpcsc_type_terms">
                                    <input class="wpcsc_terms_val" name="wpcsc_terms" type="hidden" value="<?php echo esc_attr( $terms ); ?>"/>
									<?php
									if ( ! is_array( $terms ) ) {
										$terms = array_map( 'trim', explode( ',', $terms ) );
									}
									?>
                                    <select class="wpcsc_terms_select" multiple="multiple" data-<?php echo esc_attr( $type ); ?>="<?php echo esc_attr( implode( ',', $terms ) ); ?>">
										<?php
										if ( ! empty( $terms ) ) {
											foreach ( $terms as $t ) {
												if ( $term = get_term_by( 'slug', $t, $type ) ) {
													echo '<option value="' . esc_attr( $t ) . '" selected>' . esc_html( $term->name ) . '</option>';
												}
											}
										}
										?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="wpcsc_configuration_tr">
                            <div class="wpcsc_configuration_th">
								<?php esc_html_e( 'Above Text', 'wpc-product-size-chart' ); ?>
                            </div>
                            <div class="wpcsc_configuration_td">
								<?php wp_editor( $above_text, 'wpcsc_above_text', [
									'textarea_name' => 'wpcsc_above_text',
									'textarea_rows' => 10
								] ); ?>
                            </div>
                        </div>
                        <div class="wpcsc_configuration_tr">
                            <div class="wpcsc_configuration_th">
								<?php esc_html_e( 'Chart Table', 'wpc-product-size-chart' ); ?>
                            </div>
                            <div class="wpcsc_configuration_td">
                                <input id="wpcsc-table-val" type="hidden" name="wpcsc_table_data" value='<?php echo esc_attr( str_replace( '\'', '&apos;', $table_data ) ); ?>'>
                                <div class="wpcsc-table-wrapper">
                                    <table id="wpcsc-table" class="wpcsc-table">
                                        <thead>
                                        <tr>
                                            <th></th>
											<?php
											if ( ! empty( $table_data ) && ! empty( $table_data_arr ) ) {
												foreach ( $table_data_arr[0] as $col ) {
													?>
                                                    <th class="wpcsc-btns">
                                                        <input type="button" class="wpcsc-add-col wpcsc-add button-primary" value="+"/><input type="button" class="wpcsc-del-col wpcsc-del button" value="-"/>
                                                    </th>
													<?php
												}
											}
											?>
                                        </tr>
                                        </thead>
                                        <tbody>
										<?php
										if ( ! empty( $table_data ) && ! empty( $table_data_arr ) ) {
											foreach ( $table_data_arr as $row ) {
												?>
                                                <tr>
                                                    <td class="wpcsc-btns">
                                                        <input type="button" class="wpcsc-add-row wpcsc-add button-primary" value="+"/><input type="button" class="wpcsc-del-row wpcsc-del button" value="-"/>
                                                    </td>
													<?php foreach ( $row as $val ) { ?>
                                                        <td>
                                                            <input class="wpcsc-input-table" type="text" name="wpcsc_chart_input" value="<?php echo esc_attr( str_replace( '"', '&quot;', $val ) ); ?>"/>
                                                        </td>
													<?php } ?>
                                                </tr>
												<?php
											}
										}
										?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="wpcsc_configuration_tr">
                            <div class="wpcsc_configuration_th">
								<?php esc_html_e( 'Under Text', 'wpc-product-size-chart' ); ?>
                            </div>
                            <div class="wpcsc_configuration_td">
								<?php wp_editor( $under_text, 'wpcsc_under_text', [
									'textarea_name' => 'wpcsc_under_text',
									'textarea_rows' => 10
								] ); ?>
                            </div>
                        </div>
                    </div>
					<?php
				}

				function ajax_add_combined() {
					if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'wpcsc-security' ) ) {
						die( 'Permissions check failed!' );
					}

					self::combined();
					wp_die();
				}

				function combined( $combined_key = '', $combined = [] ) {
					if ( empty( $combined_key ) ) {
						$combined_key = self::generate_key();
					}

					$apply      = isset( $combined['apply'] ) ? $combined['apply'] : '';
					$compare    = isset( $combined['compare'] ) ? $combined['compare'] : 'is';
					$terms      = isset( $combined['terms'] ) ? (array) $combined['terms'] : [];
					$taxonomies = get_object_taxonomies( 'product', 'objects' );
					?>
                    <div class="wpcsc_combined">
                        <span class="wpcsc_combined_remove">&times;</span> <span class="wpcsc_combined_selector_wrap">
                                    <select class="wpcsc_combined_selector" name="wpcsc_combined[<?php echo esc_attr( $combined_key ); ?>][apply]">
	                                    <?php foreach ( $taxonomies as $taxonomy ) {
		                                    echo '<option value="' . esc_attr( $taxonomy->name ) . '" ' . ( $apply === $taxonomy->name ? 'selected' : '' ) . '>' . esc_html( $taxonomy->label ) . '</option>';
	                                    } ?>
                                    </select>
                                </span> <span class="wpcsc_combined_compare_wrap">
							<select class="wpcsc_combined_compare" name="wpcsc_combined[<?php echo esc_attr( $combined_key ); ?>][compare]">
								<option value="is" <?php selected( $compare, 'is' ); ?>><?php esc_html_e( 'including', 'wpc-product-size-chart' ); ?></option>
								<option value="is_not" <?php selected( $compare, 'is_not' ); ?>><?php esc_html_e( 'excluding', 'wpc-product-size-chart' ); ?></option>
							</select></span> <span class="wpcsc_combined_val_wrap">
                                    <select class="wpcsc_combined_val wpcsc_apply_terms" multiple="multiple" name="wpcsc_combined[<?php echo esc_attr( $combined_key ); ?>][terms][]">
                                        <?php
                                        if ( ! empty( $terms ) ) {
	                                        foreach ( $terms as $ct ) {
		                                        if ( $term = get_term_by( 'slug', $ct, $apply ) ) {
			                                        echo '<option value="' . esc_attr( $ct ) . '" selected>' . esc_html( $term->name ) . '</option>';
		                                        }
	                                        }
                                        }
                                        ?>
                                    </select>
                                </span>
                    </div>
					<?php
				}

				function generate_key() {
					$key         = '';
					$key_str     = apply_filters( 'wpcsc_key_characters', 'abcdefghijklmnopqrstuvwxyz0123456789' );
					$key_str_len = strlen( $key_str );

					for ( $i = 0; $i < apply_filters( 'wpcsc_key_length', 4 ); $i ++ ) {
						$key .= $key_str[ random_int( 0, $key_str_len - 1 ) ];
					}

					if ( is_numeric( $key ) ) {
						$key = self::generate_key();
					}

					return apply_filters( 'wpcsc_generate_key', $key );
				}

				function sanitize_array( $var ) {
					if ( is_array( $var ) ) {
						return array_map( [ __CLASS__, 'sanitize_array' ], $var );
					} else {
						return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
					}
				}

				function shortcode_callback( $post ) {
					?>
                    <div class="wpcsc-shortcode-wrap">
                        <p><?php esc_html_e( 'You can use below shortcode to place this size chart where you want.', 'wpc-product-size-chart' ); ?></p>
                        <input type="text" onfocus="this.select();" readonly="readonly" class="code" value="[wpcsc id='<?php echo esc_attr( get_the_ID() ); ?>']"/>
                    </div>
					<?php
				}

				function save_size_chart( $post_id ) {
					if ( isset( $_POST['wpcsc_type'] ) ) {
						update_post_meta( $post_id, 'type', sanitize_text_field( $_POST['wpcsc_type'] ) );
					}

					if ( isset( $_POST['wpcsc_terms'] ) ) {
						update_post_meta( $post_id, 'terms', sanitize_text_field( $_POST['wpcsc_terms'] ) );
					}

					if ( isset( $_POST['wpcsc_combined'] ) ) {
						update_post_meta( $post_id, 'combined', self::sanitize_array( $_POST['wpcsc_combined'] ) );
					}

					if ( isset( $_POST['wpcsc_above_text'] ) ) {
						update_post_meta( $post_id, 'above_text', sanitize_post( $_POST['wpcsc_above_text'] ) );
					}

					if ( isset( $_POST['wpcsc_table_data'] ) ) {
						update_post_meta( $post_id, 'table_data', sanitize_text_field( $_POST['wpcsc_table_data'] ) );
					}

					if ( isset( $_POST['wpcsc_under_text'] ) ) {
						update_post_meta( $post_id, 'under_text', sanitize_post( $_POST['wpcsc_under_text'] ) );
					}
				}

				function custom_column( $columns ) {
					return [
						'cb'          => $columns['cb'],
						'title'       => esc_html__( 'Title', 'wpc-product-size-chart' ),
						'description' => esc_html__( 'Description', 'wpc-product-size-chart' ),
						'shortcode'   => esc_html__( 'Shortcode', 'wpc-product-size-chart' ),
						'date'        => esc_html__( 'Date', 'wpc-product-size-chart' )
					];
				}

				function custom_column_value( $column, $post_id ) {
					if ( $column == 'description' ) {
						echo get_the_excerpt( $post_id );
					}

					if ( $column == 'shortcode' ) {
						?>
                        <div class="wpcsc-shortcode-wrap">
                            <input type="text" onfocus="this.select();" readonly="readonly" value="[wpcsc id='<?php echo esc_attr( $post_id ); ?>']" class="code"/>
                        </div>
						<?php
					}
				}

				function enqueue_scripts() {
					// library
					if ( self::get_setting( 'library', 'magnific' ) === 'magnific' ) {
						// magnific
						wp_enqueue_style( 'magnific-popup', WPCSC_URI . 'assets/libs/magnific-popup/magnific-popup.css' );
						wp_enqueue_script( 'magnific-popup', WPCSC_URI . 'assets/libs/magnific-popup/jquery.magnific-popup.min.js', [ 'jquery' ], WPCSC_VERSION, true );
					} else {
						// featherlight
						wp_enqueue_style( 'featherlight', WPCSC_URI . 'assets/libs/featherlight/featherlight.min.css' );
						wp_enqueue_script( 'featherlight', WPCSC_URI . 'assets/libs/featherlight/featherlight.min.js', [ 'jquery' ], WPCSC_VERSION, true );
					}

					// wpcsc
					wp_enqueue_style( 'wpcsc-frontend', WPCSC_URI . 'assets/css/frontend.css', [], WPCSC_VERSION );
					wp_enqueue_script( 'wpcsc-frontend', WPCSC_URI . 'assets/js/frontend.js', [ 'jquery' ], WPCSC_VERSION, true );
					wp_localize_script( 'wpcsc-frontend', 'wpcsc_vars', [
							'ajax_url' => admin_url( 'admin-ajax.php' ),
							'nonce'    => wp_create_nonce( 'wpcsc-security' ),
							'library'  => self::get_setting( 'library', 'magnific' ),
							'effect'   => self::get_setting( 'effect', 'mfp-3d-unfold' ),
						]
					);
				}

				function admin_enqueue_scripts( $hook ) {
					if ( apply_filters( 'wpcsc_ignore_backend_scripts', false, $hook ) ) {
						return null;
					}

					wp_enqueue_style( 'wpcsc-backend', WPCSC_URI . 'assets/css/backend.css', [ 'woocommerce_admin_styles' ], WPCSC_VERSION );
					wp_enqueue_script( 'wpcsc-backend', WPCSC_URI . 'assets/js/backend.js', [
						'jquery',
						'wc-enhanced-select',
						'selectWoo'
					], WPCSC_VERSION, true );
					wp_localize_script( 'wpcsc-backend', 'wpcsc_vars', [
						'nonce' => wp_create_nonce( 'wpcsc-security' )
					] );
				}

				function action_links( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$how                  = '<a href="' . esc_url( admin_url( 'admin.php?page=wpclever-wpcsc&tab=how' ) ) . '">' . esc_html__( 'How to use?', 'wpc-product-size-chart' ) . '</a>';
						$settings             = '<a href="' . esc_url( admin_url( 'admin.php?page=wpclever-wpcsc&tab=settings' ) ) . '">' . esc_html__( 'Settings', 'wpc-product-size-chart' ) . '</a>';
						$links['wpc-premium'] = '<a href="' . esc_url( admin_url( 'admin.php?page=wpclever-wpcsc&tab=premium' ) ) . '">' . esc_html__( 'Premium Version', 'wpc-product-size-chart' ) . '</a>';
						array_unshift( $links, $how, $settings );
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
							'support' => '<a href="' . esc_url( WPCSC_DISCUSSION ) . '" target="_blank">' . esc_html__( 'Community support', 'wpc-product-size-chart' ) . '</a>',
						];

						return array_merge( $links, $row_meta );
					}

					return (array) $links;
				}

				function register_settings() {
					// settings
					register_setting( 'wpcsc_settings', 'wpcsc_settings' );
				}

				function admin_menu() {
					add_submenu_page( 'wpclever', esc_html__( 'WPC Product Size Chart', 'wpc-product-size-chart' ), esc_html__( 'Product Size Chart', 'wpc-product-size-chart' ), 'manage_options', 'wpclever-wpcsc', [
						$this,
						'admin_menu_content'
					] );
				}

				function admin_menu_content() {
					$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'settings';
					?>
                    <div class="wpclever_settings_page wrap">
                        <h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Product Size Chart', 'wpc-product-size-chart' ) . ' ' . esc_html( WPCSC_VERSION ) . ' ' . ( defined( 'WPCSC_PREMIUM' ) ? '<span class="premium" style="display: none">' . esc_html__( 'Premium', 'wpc-product-size-chart' ) . '</span>' : '' ); ?></h1>
                        <div class="wpclever_settings_page_desc about-text">
                            <p>
								<?php printf( /* translators: %s is the stars */ esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'wpc-product-size-chart' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
                                <br/>
                                <a href="<?php echo esc_url( WPCSC_REVIEWS ); ?>" target="_blank"><?php esc_html_e( 'Reviews', 'wpc-product-size-chart' ); ?></a> |
                                <a href="<?php echo esc_url( WPCSC_CHANGELOG ); ?>" target="_blank"><?php esc_html_e( 'Changelog', 'wpc-product-size-chart' ); ?></a> |
                                <a href="<?php echo esc_url( WPCSC_DISCUSSION ); ?>" target="_blank"><?php esc_html_e( 'Discussion', 'wpc-product-size-chart' ); ?></a>
                            </p>
                        </div>
						<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) { ?>
                            <div class="notice notice-success is-dismissible">
                                <p><?php esc_html_e( 'Settings updated.', 'wpc-product-size-chart' ); ?></p>
                            </div>
						<?php } ?>
                        <div class="wpclever_settings_page_nav">
                            <h2 class="nav-tab-wrapper">
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-wpcsc&tab=how' ) ); ?>" class="<?php echo esc_attr( $active_tab === 'how' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'How to use?', 'wpc-product-size-chart' ); ?>
                                </a>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-wpcsc&tab=settings' ) ); ?>" class="<?php echo esc_attr( $active_tab === 'settings' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Settings', 'wpc-product-size-chart' ); ?>
                                </a>
                                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wpc_size_chart' ) ); ?>" class="nav-tab">
									<?php esc_html_e( 'Global Size Charts', 'wpc-product-size-chart' ); ?>
                                </a>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-wpcsc&tab=premium' ) ); ?>" class="<?php echo esc_attr( $active_tab === 'premium' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>" style="color: #c9356e">
									<?php esc_html_e( 'Premium Version', 'wpc-product-size-chart' ); ?>
                                </a>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-kit' ) ); ?>" class="nav-tab">
									<?php esc_html_e( 'Essential Kit', 'wpc-product-size-chart' ); ?>
                                </a>
                            </h2>
                        </div>
                        <div class="wpclever_settings_page_content">
							<?php if ( $active_tab === 'how' ) { ?>
                                <div class="wpclever_settings_page_content_text">
                                    <p>
										<?php esc_html_e( '1. Global Size Charts: Switch to Global Size Charts tab to add some size charts then you can use these size charts in each product.', 'wpc-product-size-chart' ); ?>
                                    </p>
                                    <p>
										<?php esc_html_e( '2. Product Basis Size Charts: When adding/editing the product you can choose the Size Charts tab then add some size charts as you want.', 'wpc-product-size-chart' ); ?>
                                    </p>
                                </div>
							<?php } elseif ( $active_tab === 'settings' ) {
								$library  = self::get_setting( 'library', 'magnific' );
								$effect   = self::get_setting( 'effect', 'mfp-3d-unfold' );
								$position = self::get_setting( 'position', 'above_atc' );
								$combine  = self::get_setting( 'combine', 'no' );
								?>
                                <form method="post" action="options.php">
                                    <table class="form-table wpcsc-table">
                                        <tr class="heading">
                                            <th colspan="2">
												<?php esc_html_e( 'General', 'wpc-product-size-chart' ); ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Popup library', 'wpc-product-size-chart' ); ?></th>
                                            <td>
                                                <select name="wpcsc_settings[library]">
                                                    <option value="featherlight" <?php selected( $library, 'featherlight' ); ?>><?php esc_html_e( 'Featherlight', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="magnific" <?php selected( $library, 'magnific' ); ?>><?php esc_html_e( 'Magnific', 'wpc-product-size-chart' ); ?></option>
                                                </select>
                                                <span class="description">Read more about <a href="https://noelboss.github.io/featherlight/" target="_blank">Featherlight</a> and <a href="https://dimsemenov.com/plugins/magnific-popup/" target="_blank">Magnific</a>. Recommend using the popup library that is already used in the theme or other plugins on your site.</span>
                                            </td>
                                        </tr>
                                        <tr class="wpcsc-show-if-magnific">
                                            <th><?php esc_html_e( 'Effect', 'wpc-product-size-chart' ); ?></th>
                                            <td>
                                                <select name="wpcsc_settings[effect]">
                                                    <option value="mfp-fade" <?php selected( $effect, 'mfp-fade' ); ?>><?php esc_html_e( 'Fade', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="mfp-zoom-in" <?php selected( $effect, 'mfp-zoom-in' ); ?>><?php esc_html_e( 'Zoom in', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="mfp-zoom-out" <?php selected( $effect, 'mfp-zoom-out' ); ?>><?php esc_html_e( 'Zoom out', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="mfp-newspaper" <?php selected( $effect, 'mfp-newspaper' ); ?>><?php esc_html_e( 'Newspaper', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="mfp-move-horizontal" <?php selected( $effect, 'mfp-move-horizontal' ); ?>><?php esc_html_e( 'Move horizontal', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="mfp-move-from-top" <?php selected( $effect, 'mfp-move-from-top' ); ?>><?php esc_html_e( 'Move from top', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="mfp-3d-unfold" <?php selected( $effect, 'mfp-3d-unfold' ); ?>><?php esc_html_e( '3d unfold', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="mfp-slide-bottom" <?php selected( $effect, 'mfp-slide-bottom' ); ?>><?php esc_html_e( 'Slide bottom', 'wpc-product-size-chart' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Effect for Magnific popup only.', 'wpc-product-size-chart' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Position', 'wpc-product-size-chart' ); ?></th>
                                            <td>
                                                <select name="wpcsc_settings[position]">
                                                    <option value="above_atc" <?php selected( $position, 'above_atc' ); ?>><?php esc_html_e( 'Above the add to cart button', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="below_atc" <?php selected( $position, 'below_atc' ); ?>><?php esc_html_e( 'Under the add to cart button', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="below_title" <?php selected( $position, 'below_title' ); ?>><?php esc_html_e( 'Under the title', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="below_price" <?php selected( $position, 'below_price' ); ?>><?php esc_html_e( 'Under the price', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="below_excerpt" <?php selected( $position, 'below_excerpt' ); ?>><?php esc_html_e( 'Under the excerpt', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="below_meta" <?php selected( $position, 'below_meta' ); ?>><?php esc_html_e( 'Under the meta', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="below_sharing" <?php selected( $position, 'below_sharing' ); ?>><?php esc_html_e( 'Under the sharing', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="tab" <?php selected( $position, 'tab' ); ?>><?php esc_html_e( 'In a new tab', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="no" <?php selected( $position, 'no' ); ?>><?php esc_html_e( 'None (hide it)', 'wpc-product-size-chart' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Choose the position to show the size-chart links on single product page. You also can use the shortcode [wpcsc_link].', 'wpc-product-size-chart' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Combine', 'wpc-product-size-chart' ); ?></th>
                                            <td>
                                                <select name="wpcsc_settings[combine]">
                                                    <option value="yes" <?php selected( $combine, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-product-size-chart' ); ?></option>
                                                    <option value="no" <?php selected( $combine, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-product-size-chart' ); ?></option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Combine and show all product size charts in one popup.', 'wpc-product-size-chart' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Label', 'wpc-product-size-chart' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="wpcsc_settings[label]" value="<?php echo esc_attr( self::get_setting( 'label' ) ); ?>" placeholder="<?php esc_html_e( 'Size Charts', 'wpc-product-size-chart' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th colspan="2"><?php esc_html_e( 'Suggestion', 'wpc-product-size-chart' ); ?></th>
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
												<?php settings_fields( 'wpcsc_settings' ); ?><?php submit_button(); ?>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'premium' ) { ?>
                                <div class="wpclever_settings_page_content_text">
                                    <p>Get the Premium Version just $29!
                                        <a href="https://wpclever.net/downloads/wpc-product-size-chart?utm_source=pro&utm_medium=wpcsc&utm_campaign=wporg" target="_blank">https://wpclever.net/downloads/wpc-product-size-chart</a>
                                    </p>
                                    <p><strong>Extra features for Premium Version:</strong></p>
                                    <ul style="margin-bottom: 0">
                                        <li>- Use combined source.</li>
                                        <li>- Get lifetime update & premium support.</li>
                                    </ul>
                                </div>
							<?php } ?>
                        </div>
                    </div>
					<?php
				}

				function ajax_search_size_chart() {
					$return = [];

					$search_results = new WP_Query( [
						'post_type'           => 'wpc_size_chart',
						's'                   => sanitize_text_field( $_GET['q'] ),
						'post_status'         => 'publish',
						'ignore_sticky_posts' => 1,
						'posts_per_page'      => 50
					] );

					if ( $search_results->have_posts() ) {
						while ( $search_results->have_posts() ) {
							$search_results->the_post();
							$title    = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
							$return[] = [ $search_results->post->ID, $title ];
						}
					}

					wp_send_json( $return );
				}

				function ajax_search_term() {
					$return = [];

					$args = [
						'taxonomy'   => sanitize_text_field( $_REQUEST['taxonomy'] ),
						'orderby'    => 'id',
						'order'      => 'ASC',
						'hide_empty' => false,
						'fields'     => 'all',
						'name__like' => sanitize_text_field( $_REQUEST['q'] ),
					];

					$terms = get_terms( $args );

					if ( is_array( $terms ) && count( $terms ) ) {
						foreach ( $terms as $term ) {
							$return[] = [ $term->slug, $term->name ];
						}
					}

					wp_send_json( $return );
				}

				function ajax_get_chart() {
					if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'wpcsc-security' ) ) {
						die( 'Permissions check failed!' );
					}

					echo self::size_chart( sanitize_text_field( $_POST['id'] ) );

					wp_die();
				}

				function ajax_get_charts() {
					if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'wpcsc-security' ) ) {
						die( 'Permissions check failed!' );
					}

					echo self::size_charts( sanitize_text_field( $_POST['id'] ) );

					wp_die();
				}

				function footer() {
					echo '<a class="wpcsc-popup-btn" href="#" data-featherlight=".wpcsc-popup">#</a><div class="wpcsc-popup mfp-with-anim"></div>';
				}

				function shortcode( $attrs ) {
					$output = '';
					$attrs  = shortcode_atts( [ 'id' => null ], $attrs, 'wpcsc' );

					if ( $attrs['id'] ) {
						$output = self::size_chart( $attrs['id'] );
					}

					return apply_filters( 'wpcsc_shortcode', $output, $attrs['id'] );
				}

				function shortcode_product( $attrs ) {
					$output = '';
					$attrs  = shortcode_atts( [ 'id' => null ], $attrs, 'wpcsc_product' );

					if ( ! $attrs['id'] ) {
						global $product;
						$attrs['id'] = $product->get_id();
					}

					if ( $attrs['id'] ) {
						$output = self::size_charts( $attrs['id'] );
					}

					return apply_filters( 'wpcsc_shortcode_product', $output, $attrs['id'] );
				}

				function shortcode_link() {
					ob_start();
					self::size_charts_list();

					return apply_filters( 'wpcsc_shortcode_link', ob_get_clean() );
				}

				function product_tabs( $tabs ) {
					global $product;

					if ( $product ) {
						$product_id = $product->get_id();
						$charts     = self::get_size_charts( $product_id );

						if ( $product_id && ! empty( $charts ) ) {
							$label = self::get_setting( 'label' );

							if ( empty( $label ) ) {
								$label = esc_html__( 'Size Charts', 'wpc-product-size-chart' );
							}

							$tabs['wpcsc'] = [
								'title'    => apply_filters( 'wpcsc_tab_title', esc_html( $label ) ),
								'callback' => [ $this, 'tab_content' ]
							];
						}
					}

					return $tabs;
				}

				function tab_content( $key, $tab ) {
					global $product;

					if ( $product ) {
						$product_id = $product->get_id();

						if ( $product_id ) {
							echo apply_filters( 'wpcsc_tab_content_title', '<h2>' . esc_html( $tab['title'] ) . '</h2>' );
							echo self::size_charts( $product_id );
						}
					}
				}

				function get_size_charts( $product_id ) {
					$charts = [];
					$active = get_post_meta( $product_id, 'wpcsc_active', true ) ?: 'default';

					switch ( $active ) {
						case 'default':
							// global size charts
							$args  = [
								'post_type'      => 'wpc_size_chart',
								'posts_per_page' => 500,
								'meta_key'       => 'type',
								'meta_value'     => 'none',
								'meta_compare'   => '!=',
							];
							$query = new WP_Query( $args );

							if ( $query->have_posts() ) {
								while ( $query->have_posts() ) {
									$query->the_post();
									$post_id = get_the_ID();
									$type    = ! empty( get_post_meta( $post_id, 'type', true ) ) ? get_post_meta( $post_id, 'type', true ) : 'none';

									switch ( $type ) {
										case 'all':
											$charts[] = get_the_ID();

											break;
										case 'combined':
											break;
										default:
											if ( ! empty( get_post_meta( $post_id, 'terms', true ) ) ) {
												$terms = explode( ',', get_post_meta( $post_id, 'terms', true ) );

												if ( has_term( $terms, $type, $product_id ) ) {
													$charts[] = get_the_ID();
												}
											}
									}
								}

								wp_reset_postdata();
							}

							break;
						case 'overwrite':
							// product size charts
							$size_charts = get_post_meta( $product_id, 'wpcsc_size_charts', true );

							if ( ! empty( $size_charts ) ) {
								$charts = explode( ',', $size_charts );
							}

							break;
					}

					return apply_filters( 'wpcsc_get_size_charts', $charts, $product_id );
				}

				function size_charts_list() {
					global $product;

					if ( $product && is_a( $product, 'WC_Product' ) && ( $product_id = $product->get_id() ) ) {
						$charts = self::get_size_charts( $product_id );

						if ( ! empty( $charts ) ) {
							$list  = '';
							$label = self::get_setting( 'label' );

							if ( empty( $label ) ) {
								$label = esc_html__( 'Size Charts', 'wpc-product-size-chart' );
							}

							if ( self::get_setting( 'combine', 'no' ) === 'yes' ) {
								$list .= '<div class="wpcsc-size-charts-list">';
								$list .= apply_filters( 'wpcsc_size_charts_link', '<a class="wpcsc-btn wpcsc-size-charts-list-items" href="#sc-' . esc_attr( $product_id ) . '" data-id="' . esc_attr( $product_id ) . '" data-pid="' . esc_attr( $product_id ) . '">' . esc_html( $label ) . '</a>', $product_id, $charts );
								$list .= '</div><!-- /wpcsc-size-charts-list -->';
							} else {
								$list .= '<div class="wpcsc-size-charts-list">';
								$list .= '<span class="wpcsc-size-charts-list-label">' . esc_html( $label ) . '</span>';

								foreach ( $charts as $chart ) {
									$list .= apply_filters( 'wpcsc_size_chart_link', '<a class="wpcsc-btn wpcsc-size-charts-list-item" href="#sc-' . esc_attr( $chart ) . '" data-id="' . esc_attr( $chart ) . '" data-cid="' . esc_attr( $chart ) . '">' . esc_html( get_the_title( $chart ) ) . '</a>', $product_id, $chart );
								}

								$list .= '</div><!-- /wpcsc-size-charts-list -->';
							}

							echo apply_filters( 'wpcsc_size_charts_list', $list, $product_id, $charts );
						}
					}
				}

				function size_charts( $product_id ) {
					$content = '';
					$charts  = self::get_size_charts( $product_id );

					if ( ! empty( $charts ) ) {
						$content .= '<div class="wpcsc-size-charts">';

						foreach ( $charts as $chart ) {
							$content .= self::size_chart( $chart );
						}

						$content .= '</div><!-- /wpcsc-size-charts -->';
					}

					return apply_filters( 'wpcsc_size_charts', $content, $product_id );
				}

				function size_chart( $chart_id ) {
					$above_text = get_post_meta( $chart_id, 'above_text', true );
					$under_text = get_post_meta( $chart_id, 'under_text', true );
					$table_data = get_post_meta( $chart_id, 'table_data', true ) ?: '';

					if ( empty( $table_data ) || ( $table_data === '[["&nbsp;"]] ' ) || ( $table_data === '[[" "]] ' ) ) {
						$table_data_arr = [];
					} else {
						$table_data_arr = json_decode( $table_data );
					}

					$chart = '<div class="wpcsc-size-chart wpcsc-size-chart-' . esc_attr( $chart_id ) . '">';
					$chart .= '<div class="wpcsc-size-chart-title">' . apply_filters( 'wpcsc_size_chart_title', get_the_title( $chart_id ), $chart_id ) . '</div>';
					$chart .= '<div class="wpcsc-size-chart-content">';

					if ( ! empty( $above_text ) ) {
						$chart .= apply_filters( 'wpcsc_size_chart_above_text', '<div class="wpcsc-size-chart-above-text">' . $above_text . '</div>', $chart_id );
					}

					if ( is_array( $table_data_arr ) && ! empty( $table_data_arr ) && ( count( $table_data_arr[0] ) > 1 ) ) {
						$chart .= '<table>';
						$chart .= '<thead><tr>';

						foreach ( $table_data_arr[0] as $col ) {
							$chart .= '<th>' . esc_html( $col ) . '</th>';
						}

						$chart .= '</tr></thead>';
						$chart .= '<tbody>';

						foreach ( $table_data_arr as $k => $row ) {
							if ( $k == 0 ) {
								// exclude first row
								continue;
							}

							$chart .= '<tr>';

							foreach ( $row as $val ) {
								$chart .= '<td>' . esc_html( $val ) . '</td>';
							}

							$chart .= '</tr>';
						}

						$chart .= '</tbody>';
						$chart .= '</table>';
					}

					if ( ! empty( $under_text ) ) {
						$chart .= apply_filters( 'wpcsc_size_chart_under_text', '<div class="wpcsc-size-chart-under-text">' . $under_text . '</div>', $chart_id );
					}

					$chart .= '</div>';
					$chart .= '</div><!-- /wpcsc-size-chart -->';

					return apply_filters( 'wpcsc_size_chart', $chart, $chart_id );
				}

				function product_data_tabs( $tabs ) {
					$tabs['wpcsc'] = [
						'label'  => esc_html__( 'Size Charts', 'wpc-product-size-chart' ),
						'target' => 'wpcsc_settings'
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
                        <div id='wpcsc_settings' class='panel woocommerce_options_panel wpcsc_settings'>
                            <p style="padding: 0 12px; color: #c9356e"><?php esc_html_e( 'Product wasn\'t returned.', 'wpc-product-size-chart' ); ?></p>
                        </div>
						<?php
						return;
					}

					$active            = get_post_meta( $product_id, 'wpcsc_active', true ) ?: 'default';
					$saved_size_charts = get_post_meta( $product_id, 'wpcsc_size_charts', true );
					?>
                    <div id='wpcsc_settings' class='panel woocommerce_options_panel wpcsc_settings'>
                        <div class="wpcsc-global">
                            <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=wpc_size_chart' ) ); ?>" target="_blank"><?php esc_html_e( 'Manage Global Size Charts', 'wpc-product-size-chart' ); ?></a>
                        </div>
                        <div class="wpcsc-global">
							<?php echo sprintf( esc_html__( 'You can use the shortcode %s to show this product\'s size charts.', 'wpc-product-size-chart' ), '<strong>[wpcsc_product id="' . esc_attr( $product_id ) . '"]</strong>' ); ?>
                        </div>
                        <div class="wpcsc-active">
                            <label>
                                <input name="wpcsc_active" type="radio" value="default" <?php echo esc_attr( $active === 'default' ? 'checked' : '' ); ?>/> <?php esc_html_e( 'Global', 'wpc-product-size-chart' ); ?>
                            </label> <label>
                                <input name="wpcsc_active" type="radio" value="disable" <?php echo esc_attr( $active === 'disable' ? 'checked' : '' ); ?>/> <?php esc_html_e( 'Disable', 'wpc-product-size-chart' ); ?>
                            </label> <label>
                                <input name="wpcsc_active" type="radio" value="overwrite" <?php echo esc_attr( $active === 'overwrite' ? 'checked' : '' ); ?>/> <?php esc_html_e( 'Overwrite', 'wpc-product-size-chart' ); ?>
                            </label>
                        </div>
                        <div class="wpcsc-overwrite">
                            <select class="wpcsc-size-chart-search" multiple="multiple">
								<?php
								if ( ! empty( $saved_size_charts ) ) {
									$size_charts = explode( ',', $saved_size_charts );

									if ( ! empty( $size_charts ) ) {
										foreach ( $size_charts as $size_chart ) {
											if ( $size_chart_data = get_post( $size_chart ) ) {
												echo '<option value="' . esc_attr( $size_chart_data->ID ) . '" selected>' . esc_html( $size_chart_data->post_title ) . '</option>';
											}
										}
									}
								}
								?>
                            </select>
                            <input type="hidden" name="wpcsc_size_charts" class="wpcsc-size-charts-val" value="<?php echo esc_attr( $saved_size_charts ); ?>"/>
                        </div>
                    </div>
					<?php
				}

				function process_product_meta( $post_id ) {
					if ( isset( $_POST['wpcsc_active'] ) ) {
						update_post_meta( $post_id, 'wpcsc_active', sanitize_text_field( $_POST['wpcsc_active'] ) );
					} else {
						delete_post_meta( $post_id, 'wpcsc_active' );
					}

					if ( isset( $_POST['wpcsc_size_charts'] ) ) {
						update_post_meta( $post_id, 'wpcsc_size_charts', sanitize_text_field( $_POST['wpcsc_size_charts'] ) );
					} else {
						delete_post_meta( $post_id, 'wpcsc_size_charts' );
					}
				}

				public static function get_settings() {
					return apply_filters( 'wpcsc_get_settings', self::$settings );
				}

				public static function get_setting( $name, $default = false ) {
					if ( ! empty( self::$settings ) && isset( self::$settings[ $name ] ) ) {
						$setting = self::$settings[ $name ];
					} else {
						$setting = get_option( 'wpcsc_' . $name, $default );
					}

					return apply_filters( 'wpcsc_get_setting', $setting, $name, $default );
				}
			}

			return WPCleverWpcsc::instance();
		}

		return null;
	}
}

if ( ! function_exists( 'wpcsc_notice_wc' ) ) {
	function wpcsc_notice_wc() {
		?>
        <div class="error">
            <p><strong>WPC Product Size Chart</strong> requires WooCommerce version 3.0 or greater.</p>
        </div>
		<?php
	}
}
