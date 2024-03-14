<?php
/**
 * Routes all the hooks to their respective actions and filters.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since 1.0.0
 */
// Namespace.
namespace StockSyncWithGoogleSheetForWooCommerce;

// Exit if accessed directly.
defined('ABSPATH') || exit();

if ( ! class_exists('\StockSyncWithGoogleSheetForWooCommerce\Hooks') ) {

	/**
	 * Routes all the hooks to their respective actions and filters.
	 *
	 * @package StockSyncWithGoogleSheetForWooCommerce
	 * @since 1.0.0
	 */
	class Hooks extends Base {
		/**
		 * The single instance of the class.
		 *
		 * @var Hooks
		 */
		public static $instance = null;

		/**
		 * Initializes the class.
		 */
		public static function init() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			self::$instance->add_filters();
			self::$instance->add_actions();
		}
		/**
		 * Actions to be added for the plugin.
		 *
		 * @since 1.0.0
		 */
		public function add_actions() {

			$this->init_appsero_client();

			add_action('admin_menu', [ $this, 'add_admin_menu' ]);
			add_action('admin_init', [ $this, 'redirect_to_admin_page' ], 1);
			add_action('admin_init', [ $this, 'show_appscript_update_notice' ], 1);
			add_action('init', [ $this, 'check_ssgsw_synced' ], 99999);

			// Footer CSS for admin menu icon.
			add_action('admin_head', [ $this, 'admin_menu_icon_css' ]);

			// Admin enqueue scripts.
			add_action('admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ]);

			// Parse ID from Sheet URL and save for later use.
			add_action('ssgsw_updated_spreadsheet_url', [ $this, 'updated_spreadsheet_url_callback' ]);
			add_action('ssgsw_updated_save_and_sync', [ $this, 'ssgsw_sync_sheet_callback' ]);
			add_action('admin_footer', [ $this, 'add_some_css' ] );
			/**
			 * Updating sheet hooks
			 */

			/**
			 * When a post type product is created or updated
			 */

			/**
			 * When stock is updated
			 */
			add_action('woocommerce_product_set_stock', [ $this, 'ssgs_woocommerce_product_set_stock' ], 10, 1);

			/**
			 * When product is moved to trash
			 */
			add_action('trashed_post', [ $this, 'trashed_post_callback' ], 10, 1);

			/**
			 * When product is restored from trash
			 */
			add_action('untrashed_post', [ $this, 'untashed_post_callback' ], 10, 1);

			/**
			 * When product is created, or updated or deleted
			 */
			add_action('save_post', [ $this, 'after_save_product' ], 10, 3 );
			add_action('woocommerce_product_bulk_edit_save', [ $this, 'after_product_quick_edit' ], 10, 1 );
			// add_action('woocommerce_new_product_variation', [ $this,'after_save_product' ], 10, 2 );.
			add_action('woocommerce_update_product_variation', [ $this,'after_update_product_variation' ], 10, 2 );
			add_action('woocommerce_product_quick_edit_save', [ $this, 'after_product_quick_edit' ], 10, 1 );
			add_action('wp_ajax_sssgw_appscript_improved', [ $this, 'sssgw_appscript_improved' ] );
			add_action('wp_ajax_sssgw_notice_skip', [ $this, 'sssgw_notice_skip' ] );
			add_action('wp_ajax_sssgw_already_updated', [ $this, 'ssgsw_already_updated_keyd' ] );
		}
		/**
		 * AppScript setup again
		 */
		public function sssgw_appscript_improved() {
			if ( isset( $_POST ) ) {
				$security = isset($_POST['nonce']) ? sanitize_text_field( wp_unslash($_POST['nonce']) ) : '';
				if ( ! isset( $security ) || ! wp_verify_nonce( $security, 'ssgsw_nonce2' ) ) {
					wp_die( -1, 403 );
				}
				if ( ! current_user_can( 'manage_options' ) ) {
					return false;
				}
				if ( ! is_user_logged_in() ) {
					return false;
				}
				update_option('ssgsw_setup_step', 4 );
				wp_send_json([
					'url' => admin_url('admin.php?page=ssgsw-admin'),
				]);
			}
			die();
		}
		/**
		 * Notice skip
		 */
		public function sssgw_notice_skip() {
			if ( isset( $_POST ) ) {
				$security = isset($_POST['nonce']) ? sanitize_text_field( wp_unslash($_POST['nonce']) ) : '';
				if ( ! isset( $security ) || ! wp_verify_nonce( $security, 'ssgsw_nonce2' ) ) {
					wp_die( -1, 403 );
				}
				if ( ! current_user_can( 'manage_options' ) ) {
					return false;
				}
				if ( ! is_user_logged_in() ) {
					return false;
				}
				update_option('ssgsw_new_user_activated_key', '2' );
				wp_send_json([
					'success' => true,
				]);
			}
			die();
		}
		/**
		 * Hide Notice if already updated
		 */
		public function ssgsw_already_updated_keyd() {
			if ( isset( $_POST ) ) {
				$security = isset($_POST['nonce']) ? sanitize_text_field( wp_unslash($_POST['nonce']) ) : '';
				if ( ! isset( $security ) || ! wp_verify_nonce( $security, 'ssgsw_nonce2' ) ) {
					wp_die( -1, 403 );
				}
				if ( ! current_user_can( 'manage_options' ) ) {
					return false;
				}
				if ( ! is_user_logged_in() ) {
					return false;
				}
				update_option('ssgsw_new_user_activated_key', '1' );
				update_option('ssgsw_already_updated_key', '1' );
				wp_send_json([
					'success' => true,
				]);
			}

			die();
		}
		/**
		 * Saves post callback.
		 *
		 * @param int      $product_id Product ID.
		 * @param \WP_Post $products product object.
		 * @param int      $update save data.
		 * @return string
		 */
		public function after_save_product( $product_id, $products, $update ) {

			if ( ! $this->app->is_plugin_ready() ) {
				return __('Plugin is not ready to use.', 'stock-sync-with-google-sheet-for-woocommerce');
			}
			if ( 'product' === get_post_type($product_id) || 'product_variation' === get_post_type($product_id) ) {
				$product = new Product();
				$sheet = new Sheet();
				$sheets_info = $sheet->get_first_columns2();
				$product->batch_update_delete_and_append2($product_id,'update','',$sheets_info);
			}
		}
		/**
		 * Saves post callback.
		 *
		 * @param \WP_Post $products product object.
		 * @return string
		 */
		public function after_product_quick_edit( $products ) {
			if ( ! $this->app->is_plugin_ready() ) {
				return __('Plugin is not ready to use.', 'stock-sync-with-google-sheet-for-woocommerce');
			}
			$product = new Product();
			$sheet = new Sheet();
			$sheets_info = $sheet->get_first_columns();
			$product->batch_update_delete_and_append($products->get_id(), 'update', '',$sheets_info);
		}
		/**
		 * Update post callback.
		 *
		 * @param int      $product_id Product ID.
		 * @param \WP_Post $products product object.
		 * @return string
		 */
		public function after_update_product_variation( $product_id, $products ) {

			if ( ! $this->app->is_plugin_ready() ) {
				return __('Plugin is not ready to use.', 'stock-sync-with-google-sheet-for-woocommerce');
			}
			$product = new Product();
			$sheet = new Sheet();
			$sheets_info = $sheet->get_first_columns();
			$product->batch_update_delete_and_append($product_id,'update','',$sheets_info);
		}
		/**
		 * Public function add some css
		 */
		public function add_some_css() {
			?>
				<style>
					.ssgsw_appscript_notice3 {
						padding: 1px;
						background: #f6dade;
						text-align: center;
						border-radius: 1px;
						font-size: 14px !important;
						margin: none !important;
						position: relative;
						margin-top:5px;
						margin-bottom: 5px;
					};
					.ssgsw_remove_text_dec{
						text-decoration: none !important;
						font-size: 14px;
					}
					.ssgsw_appscript_notice3 a{
						text-decoration: none !important;
						font-size: 14px;
					};
					.ssgsw_appscript_notice3 p {
						font-size:14px !important;
					};
					.ssgsw_extra_strong {
						font-weight: 700 !important;
					}
					.ssgsw-wrapper .ssgs-check .check2:checked{
						background: #FC4486;
						border: none !important;
					}
					.ssgsw-wrapper .ssgs-check .check2{
						background: #E74F6A;
						border: none !important;
					}
					.ssgsw_list_option {
						position: absolute;
						right: 35px;
						top: 20px;
						z-index: 99;
						background-color: #f0f0f1;
						text-align: left !important;
						padding-left: 14px;
						padding-right: 20px;
					}
					.ssgsw_skip_next_time {
						font-weight: 600;
					}
					span.ssgsw_dismiss_notice {
						opacity: 0.7;
					}
					@media screen and (max-width: 782px) {
						.ssgsw_notice_dismiss {
							padding: 13px;
						}
					}
					.ssgsw_notice_dismiss {
						position: absolute;
						top: 0;
						right: 1px;
						border: none;
						margin: 0;
						padding: 9px;
						background: 0 0;
						color: #787c82;
						cursor: pointer;
					}
					.ssgsw_appscript_notice3 .notice-dismiss {
						display: none;
					}
					.ssgsw_remove_text_dec{
						color:#005ae0;
						cursor:pointer;
					}
					.ssgss_imporved_tooltip {
						cursor: pointer;
						position: relative;
						background: #e4e6eb;
						color: gray;
						display: flex;
						align-items: center;
						justify-content: center;
						width: 30px;
						height: 30px;
						box-sizing: border-box;
						border-radius: 42px;
						cursor: pointer;
						transition: all 0.2s ease;
					}
					.ssgss_imporved_tooltip:hover {
						background:#0C5F9A;
						color:#fff !important;
					}
					.ssgsw_appscript_notice {
						position: absolute;
						top: -14px;
						right: -10px;
						z-index: 9999;
						padding: 17px;
						color: #767676;
						opacity: 0.8;
					}
					.ssgsw_bulet_point_option {
						width: 10px;
						background-color: #ffba00;
						z-index: 9999;
						height: 10px;
						top: 20px;
						left: 38px;
						border-radius: 50%;
						position: absolute;
					}
					.ssgsw_dismiss_common {
						cursor: pointer;
					}
					.ssgsw_tooltip .ssgsw_tooltiptext {
						visibility: hidden;
						width: 220px;
						color: #fff;
						background: #141b38;
						text-align: center;
						border-radius: 6px;
						padding: 5px 0;
						position: absolute;
						z-index: 1;
						right: 40px;
						top: 20px;
					}
					.ssgsw_tooltip:hover .ssgsw_tooltiptext {
						visibility: visible;
					}
				</style>
			<?php
		}
		/**
		 * Show Appscript update Notice
		 *
		 * @return mixed
		 */
		public function show_appscript_update_notice() {
			$active_new_user  = get_option('ssgsw_new_user_activated_key', '0' );
			$already_update   = get_option('ssgsw_already_updated_key', '0' );
			if ( '1' == $active_new_user ) { //phpcs:ignore
				return false;
			}
			if ( '1' == $already_update ) {//phpcs:ignore
				return false;
			}
			$get_page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';
			if ( 'ssgsw-admin' !== $get_page && 'ssgsw-license' !== $get_page ) {
				return false;
			}
			$we_have = "We've updated our";// phpcs:ignore
			$in_active_notice = sprintf( __(' Hey! 👏 %1$s %2$s Apps Script!%3$s Please use the new Apps Script on Google Sheets to enjoy all the %4$s new changes %5$s😃. %6$s %7$sSetup Now %8$s %9$s', '' ), $we_have, '<strong class="ssgsw_extra_strong">', '</strong>', '<strong>', '</strong>', '<strong>', '<span class="ssgsw_remove_text_dec">', '&#8594;</span>','</strong>' );// phpcs:ignore
			add_action( 'admin_notices', function () use ( $in_active_notice, $active_new_user ) {// phpcs:ignore
				?>
				<div class="ssgsw_appscript_notice3 is-dismissible " style="
				<?php
				if ( '2' === $active_new_user ) {
					echo 'display: none';
				}
				?>
				">
					<p><?php printf( $in_active_notice ); // phpcs:ignore?></p>
					<div class="ssgsw_list_option" style="display: none;">
						<ul>
							<li><span class="ssgsw_skip_next_time ssgsw_dismiss_common"><?php esc_html_e('Not now, skip','stock-sync-with-google-sheet-for-woocommerce'); ?></span></li>
							<li><span class="ssgsw_dismiss_notice ssgsw_dismiss_common"><?php esc_html_e('Dismiss, already updated','stock-sync-with-google-sheet-for-woocommerce'); ?></span></li>
						</ul>
					</div>
					<button type="button" class="ssgsw_notice_dismiss"><span class="dashicons dashicons-dismiss"></span></button>
				</div>
				
				<div class="ssgsw_appscript_notice ssgsw_tooltip" style="
				<?php
				if ( '2' === $active_new_user ) {
					echo 'display: block';
				} else {
					echo 'display: none';
				}
				?>
				">
				<div class="ssgss_imporved_tooltip">
					<img class="" src="<?php echo esc_url( SSGSW_PUBLIC . '/images/question.svg' ); ?>" alt="">
				</div>	
					<div class="ssgsw_bulet_point_option"></div>
				</div>
				<?php
			});
		}
		/**
		 * Filters to be added for the plugin.
		 *
		 * @since 1.0.0
		 */
		public function add_filters() {
			// Add promotional link to plugin action links.
			add_filter('plugin_action_links_' . plugin_basename(SSGSW_FILE), [ $this, 'add_plugin_action_links' ]);

			// Add promotional link to plugin meta links.
			add_filter('plugin_row_meta', [ $this, 'add_plugin_meta_links' ], 10, 2);

			add_filter('ssgsw_get_credentials', [ $this, 'ssgsw_get_credentials_callback' ]);

			add_filter('ssgs_get_column', [ $this, 'ssgsw_get_column_callback' ], 10, 3);
		}

		/**
		 * Add admin menu callback.
		 *
		 * @since 1.0.0
		 */
		public function add_admin_menu() {
			add_menu_page(
			__('Stock Sync with Google Sheet for WooCommerce', 'stock-sync-with-google-sheet-for-woocommerce'),
			__('Stock Sync with Google Sheet', 'stock-sync-with-google-sheet-for-woocommerce'),
			'manage_options',
			'ssgsw-admin',
			[ $this, $this->app->is_setup_complete() ? 'render_admin_page' : 'render_setup_page' ],
			SSGSW_PUBLIC . 'images/logo.svg',
			56
			);

			if ( ! $this->app->is_setup_complete() ) {
				add_submenu_page(
				'ssgsw-admin',
				__('Stock Sync with Google Sheet for WooCommerce', 'stock-sync-with-google-sheet-for-woocommerce'),
				__('Setup', 'stock-sync-with-google-sheet-for-woocommerce'),
				'manage_options',
				'ssgsw-admin',
				[ $this, 'render_setup_page' ],
				0
				);
			} else {
				add_submenu_page(
				'ssgsw-admin',
				__('Stock Sync with Google Sheet for WooCommerce', 'stock-sync-with-google-sheet-for-woocommerce'),
				__('Settings', 'stock-sync-with-google-sheet-for-woocommerce'),
				'manage_options',
				'ssgsw-admin',
				[ $this, 'render_admin_page' ],
				99
				);
			}
		}

		/**
		 * Render admin page callback.
		 *
		 * @since 1.0.0
		 */
		public function render_admin_page() {
			if ( $this->app->is_ultimate_activated() && ! $this->app->is_license_valid() ) {
				$this->load_template('dashboard/activate-license');
			}

			$this->load_template('dashboard/base');
		}

		/**
		 * Render setup page
		 */
		public function render_setup_page() {
			$this->app->reset_options(false);
			$this->load_template('setup/base');
		}


		/**
		 * Redirect to admin page
		 */
		public function redirect_to_admin_page() {
			// $array = array(
			// array(
			// 'index_number' => 3,
			// 'ID' => '',
			// 'name' => '',
			// 'stock' => '',
			// 'regular_price' => 0,
			// 'sale_price' => 0,
			// 'Image' => '',
			// 'sku' => '',
			// 'attributes' => '',
			// 'post_excerpt' => '',
			// ),
			// array(
			// 'index_number' => 4,
			// 'ID' => '',
			// 'name' => '',
			// 'stock' => '',
			// 'regular_price' => '4543',
			// 'sale_price' => '234',
			// 'Image' => '',
			// 'sku' => '',
			// 'attributes' => '',
			// 'post_excerpt' => '',
			// ),
			// );
			// $nre = new Product();
			// $new = $nre->bulk_update($array);

			$redirect_to_admin_page = ssgsw_get_option('redirect_to_admin_page', 0);
			if ( wp_validate_boolean( $redirect_to_admin_page ) ) {
				ssgsw_update_option('redirect_to_admin_page', 0);
				wp_safe_redirect( admin_url( 'admin.php?page=ssgsw-admin' ) );
				exit;
			}
		}

		/**
		 * Add plugin action links callback.
		 *
		 * @param array $links Plugin action links.
		 * @return array
		 */
		public function add_plugin_action_links( $links ) {

			if ( $this->app->is_setup_complete() ) {
				$links[] = '<a href="' . admin_url('admin.php?page=ssgsw-admin') . '">' . __('Settings', 'stock-sync-with-google-sheet-for-woocommerce') . '</a>';
			} else {
				$links[] = '<a href="' . admin_url('admin.php?page=ssgsw-admin') . '">' . __('Setup', 'stock-sync-with-google-sheet-for-woocommerce') . '</a>';
			}

			if ( ! $this->app->is_license_valid() ) {
				$links[] = wp_sprintf( '<a class="ssgsw-promo ssgsw-ultimate-button small" href="javascript:;"> <span class="ssgsw-ultimate-button">%s</span></a>', __('Get Ultimate', 'stock-sync-with-google-sheet-for-woocommerce') );
			}

			return $links;
		}

		/**
		 * Add plugin meta links callback.
		 *
		 * @param array  $links Plugin meta links.
		 * @param string $file Plugin file.
		 * @return array
		 */
		public function add_plugin_meta_links( $links, $file ) { //phpcs:ignore
			if ( ! plugin_basename( SSGSW_FILE ) ) {
				$links[] = wp_sprintf('<a target="_blank" href="https://wppool.dev/docs-category/stock-sync-with-google-sheet-for-woocommerce/"> <span class="dashicons dashicons-media-document" aria-hidden="true" style="font-size:16px;line-height:1.2"></span>%s</a>', __('Docs', 'stock-sync-with-google-sheet-for-woocommerce'));
				$links[] = wp_sprintf('<a target="_blank" href="https://wppool.dev/contact/"> <span class="dashicons dashicons-editor-help" aria-hidden="true" style="font-size:16px;line-height:1.2"></span>%s</a>', __('Support', 'stock-sync-with-google-sheet-for-woocommerce'));
			}
			return $links;
		}

		/**
		 * Admin menu icon css callback.
		 *
		 * @since 1.0.0
		 */
		public function admin_menu_icon_css() {
			printf('<style>%s</style>', '#adminmenu .toplevel_page_ssgsw-admin div.wp-menu-image img { 
				width: 18px;
				height: 18px;
			};');
		}


		/**
		 * Admin enqueue scripts callback.
		 *
		 * @param string $hook Current page hook.
		 * @since 1.0.0
		 */
		public function admin_enqueue_scripts( $hook ) {
			wp_enqueue_style('ssgsw-global-css', SSGSW_PUBLIC . 'css/global.css', [], SSGSW_VERSION);
			$pages = [ 'toplevel_page_ssgsw-admin', 'stock-sync-with-google-sheet_page_ssgsw-settings', 'edit.php', 'plugins.php', 'index.php' ];
			$pages2 = [ 'toplevel_page_ssgsw-admin' ];
			if ( ! in_array($hook, $pages) ) {
				return;
			}

			if ( in_array($hook, $pages) ) {
				wp_enqueue_script('ssgsw-notice-js', SSGSW_PUBLIC . 'js/notice.js', [ 'jquery' ], time(), true);
				wp_localize_script('ssgsw-notice-js', 'ssgsw_notice_data', [
					'ajax_url' => admin_url('admin-ajax.php'),
					'nonce'    => wp_create_nonce('ssgsw_nonce2'),
					'current_page' => $hook,
				]);
			}

			if ( ! in_array($hook, $pages) ) {
				return;
			}

			// check if we are on product edit page.
			if ( 'edit.php' === $hook && 'product' !== get_current_screen()->post_type ) {
				return;
			}

			wp_enqueue_style('ssgsw-admin-css', SSGSW_PUBLIC . 'css/admin.min.css', [], SSGSW_VERSION);
			if ( in_array( $hook, $pages2 ) ) {
				wp_enqueue_style('ssgsw-custom-css', SSGSW_PUBLIC . 'css/custom.css', [], SSGSW_VERSION);
			}

			wp_enqueue_style('ssgsw-select2-css', SSGSW_PUBLIC . 'css/select2.css', [], SSGSW_VERSION);
			wp_enqueue_script('ssgsw-select2-js', SSGSW_PUBLIC . 'js/select2.js', [ 'jquery' ], SSGSW_VERSION, true);
			wp_enqueue_script('ssgsw-admin-js', SSGSW_PUBLIC . 'js/admin.min.js', [ 'jquery' ], SSGSW_VERSION, true);
			// Localize script.
			wp_localize_script('ssgsw-admin-js', 'ssgsw_script', $this->app->localized_script());
		}

		/**
		 * Updated spreadsheet url callback.
		 *
		 * @param string $spreadsheet_url Spreadsheet url.
		 * @since 1.0.0
		 */
		public function updated_spreadsheet_url_callback( $spreadsheet_url ) {
			/**
			 * Get Sheet ID from Sheet URL Regex
			 */
			$sheet_id = preg_replace(' / ^ . * \ / d\ / ( . * )\ / . * $ / ', '$1', $spreadsheet_url);

			/**
			 * Get Sheet ID from Sheet URL
			 */

			if ( empty($sheet_id) ) {
				$sheet_id = preg_replace(' / ^ . * \ / d\ / ( . * )$ / ', '$1', $spreadsheet_url);

				if ( empty($sheet_id) ) {
					$sheet_id = preg_replace(' / ^ . * \ / ( . * )$ / ', '$1', $spreadsheet_url);
				}
			}

			error_log('Spreadsheet ID: ' . $sheet_id);
			ssgsw_update_option('spreadsheet_id', $sheet_id);
		}

		/**
		 * Get credentials callback.
		 *
		 * @param string $credentials Credentials.
		 * @return array
		 */
		public function ssgsw_get_credentials_callback( $credentials ) {
			$credentials = json_decode($credentials, true);

			return array_map('wp_unslash', $credentials);
		}
		/**
		 * Get column callback.
		 *
		 * @param mixed  $value Column value.
		 * @param string $key Column key.
		 * @param object $row Row object.
		 * @return mixed
		 */
		public function ssgsw_get_column_callback( $value, $key, $row ) {

			$column = new Column();

			if ( '_stock' === $key ) {

				if ( isset($row->_stock) && is_numeric($row->_stock) && $row->_stock > 0 ) {
					return absint( $row->_stock );
				}

				if ( isset($row->_stock_status) ) {
					return $column->get_stock_status($row->_stock_status);
				}
			}

			// ID.
			if ( 'ID' === $key || 'total_sales' === $key ) {
				return absint( $value );
			}

			// Price.
			if ( in_array( $key, [ '_sale_price', '_regular_price', '_price' ] ) ) {
				if ( $value > 0 ) {
					return round($value, 2);
				}
				return $value;
			}

			// Product Category.
			if ( 'product_cat' === $key ) {
				return $column->get_items_by_comma($value);
			}

			// Product type.
			if ( 'product_type' === $key ) {
				return $column->get_product_type($value);
			}

			if ( 'post_excerpt' === $key ) {
				return $row->_variation_description ?? $value;
			}

			if ( '_product_attributes' === $key ) {

				if ( $value ) {
					$value = maybe_unserialize($value);

					$attributes = [];

					foreach ( $value as $attribute ) {
						if ( $attribute['is_taxonomy'] ) {
							$taxonomy_values = wp_get_post_terms( $row->ID, $attribute['name'], [ 'fields' => 'slugs' ] );
							$attributes[]    = $attribute['name'] . ': ' . implode(' | ', $taxonomy_values);
						} elseif ( ! empty($attribute['value']) ) {
							$attributes[] = $attribute['name'] . ': ' . $attribute['value'];
						}
					}

					$attributes = array_filter($attributes);

					return implode(', ', $attributes);
				}

				$attributes = array_filter( (array) $row, function ( $key ) {
					return strpos($key, 'attribute_') !== false;
				}, ARRAY_FILTER_USE_KEY);

				$attributes = array_map(function ( $key, $value ) {
					if ( $value ) {
						return str_replace('attribute_', '', $key) . ': ' . $value;
					} else {
						return '';
					}
				}, array_keys($attributes), $attributes);

				$attributes = array_filter($attributes);

				return implode(', ', $attributes);
			}

			return (string) $value;
		}


		/**
		 * Save option freeze headers callback.
		 *
		 * @param mixed $value Freeze headers value.
		 * @return string
		 */
		public function ssgsw_save_option_freeze_headers_callback( $value ) {
			if ( ! $this->app->is_plugin_ready() ) {
				return __('Plugin is not ready to use.', 'stock-sync-with-google-sheet-for-woocommerce');
			}
			$sheet   = new Sheet();
			$updated = $sheet->freeze_headers( true === wp_validate_boolean( $value ) );
			$this->send_json($updated);
		}

		/**
		 * Sync sheet callback.
		 *
		 * @return void
		 */
		public function ssgsw_sync_sheet_callback() {

			if ( isset( $GLOBALS['ssgs_sync_all_products'] ) && true === $GLOBALS['ssgs_sync_all_products'] ) {
				return;
			}
			$product = new Product();
			$product->sync_all();
		}

		/**
		 * Initiates Appsero Client.
		 *
		 * @return mixed
		 */
		public function init_appsero_client() {

			if ( ! class_exists( '\Appsero\Client' ) ) {
				require_once SSGSW_INCLUDES . '/appsero/src/Client.php';
			}

			$client = new \Appsero\Client( '2153b02c-08d6-45e0-8295-6afc39509fe5', 'Stock Sync with Google Sheet for WooCommerce', SSGSW_FILE );
			// Active insights.
			$client->insights()->init();

			// Init WPPOOL Plugin.
			if ( function_exists( 'wppool_plugin_init' ) ) {
				$default_image = SSGSW_URL . '/includes/wppool/background-image.png';
				$ssgs_plugin = wppool_plugin_init( 'stock_sync_with_google_sheet_for_woocommerce', $default_image );
				$image = SSGSW_URL . '/includes/wppool/SSGS-cyber-monday.png';
				$to = '2023-11-27';
				$from = '2023-11-16';
				if ( $ssgs_plugin && is_object( $ssgs_plugin ) && method_exists( $ssgs_plugin, 'set_campaign' ) ) {
					$ssgs_plugin->set_campaign($image, $to, $from );
				}
			}
		}
		/**
		 * Set update when product stock update
		 *
		 * @param object $product product info.
		 */
		public function ssgs_woocommerce_product_set_stock( $product ) {

			if ( ! $this->app->is_plugin_ready() ) {
				return __('Plugin is not ready to use.', 'stock-sync-with-google-sheet-for-woocommerce');
			}
			$product_id = $product->get_id();
			$product = new Product();
			$sheet = new Sheet();
			$sheets_info = $sheet->get_first_columns();
			$product->batch_update_delete_and_append($product_id,'update','',$sheets_info);
		}
		/**
		 * Trashed post callback.
		 *
		 * @param int $post_id Post ID.
		 * @return string
		 */
		public function trashed_post_callback( $post_id ) {
			if ( 'product' === get_post_type($post_id) ) {
				if ( ! $this->app->is_plugin_ready() ) {
					return __('Plugin is not ready to use.', 'stock-sync-with-google-sheet-for-woocommerce');
				}
				$product = new Product();
				$sheet = new Sheet();
				$sheets_info = $sheet->get_first_columns();
				$product->batch_update_delete_and_append($post_id,'delete','',$sheets_info);
			}
		}
		/**
		 * Un trashed post callback.
		 *
		 * @param int $post_id Post ID.
		 * @return string
		 */
		public function untashed_post_callback( $post_id ) {
			if ( 'product' === get_post_type($post_id) ) {
				if ( ! $this->app->is_plugin_ready() ) {
					return __('Plugin is not ready to use.', 'stock-sync-with-google-sheet-for-woocommerce');
				}
				$product = new Product();
				$sheet = new Sheet();
				$sheets_info = $sheet->get_first_columns();
				$product->batch_update_delete_and_append($post_id, 'update', 'deleted_product',$sheets_info );
			}
		}
		/**
		 * Checks if ssgsw synced.
		 *
		 * @return void
		 */
		public function check_ssgsw_synced() {
			$ssgsw_synced = wp_validate_boolean( get_option('ssgsw_synced') );

			if ( $ssgsw_synced ) {
				return;
			}
			update_option('ssgsw_synced', true);
			$product = new Product();
			$product->sync_all();
		}
		/**
		 * Saves post callback.
		 *
		 * @param int      $post_id Post ID.
		 * @param \WP_Post $post Post object.
		 * @param bool     $update Whether this is an existing post being updated or not.
		 * @return string
		 */
		public function save_post_callback( $post_id, $post, $update ) { // phpcs:ignore
			$product = new Product();

			if ( 'product' === get_post_type($post_id) || 'product_variation' === get_post_type($post_id) ) {
				if ( ! $this->app->is_plugin_ready() ) {
					return __('Plugin is not ready to use.', 'stock-sync-with-google-sheet-for-woocommerce');
				}
				$sheet = new Sheet();
				$sheets_info = $sheet->get_first_columns();
				$product->batch_update_delete_and_append($post_id,'update','',$sheets_info);
			}
		}
	}

	// Initiate the class.
	Hooks::init();
}
