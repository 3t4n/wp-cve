<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff
defined( 'ABSPATH' ) || exit; //Exit if accessed directly


/**
 * Funnel thank you page module
 * Class WFFN_Thank_You_WC_Page
 */
if ( ! class_exists( 'WFFN_Thank_You_WC_Pages' ) ) {
	#[AllowDynamicProperties]
	class WFFN_Thank_You_WC_Pages extends WFFN_Module_Common {

		private static $ins = null;
		public $wfty_is_thankyou = false;
		public $thankyoupage_id = 0;
		/**
		 * @var WFTY_Data
		 */
		public $data;
		/**
		 * @var WFTP_Admin|null
		 */
		public $admin;
		public $ecom_tracking;
		protected $options;
		protected $optionsShortCode;
		protected $custom_options;
		protected $template_type = [];
		protected $design_template_data = [];
		protected $templates = [];
		public $edit_id = 0;
		private $url = '';

		/**
		 * WFFN_Thank_You_WC_Pages constructor.
		 */
		public function __construct() {
			parent::__construct();
			$this->url = plugin_dir_url( __FILE__ );
			$this->process_url();


			include_once __DIR__ . '/class-wftp-admin.php';
			include_once __DIR__ . '/includes/class-wffn-ecomm-tracking.php';
			$this->ecom_tracking = WFFN_Ecomm_Tracking::get_instance();
			$this->admin         = WFTP_Admin::get_instance();

			$this->define_plugin_properties();

			add_filter( 'woocommerce_get_checkout_order_received_url', array( $this, 'redirect_to_thankyou' ), 999, 2 );
			add_action( 'init', array( $this, 'register_post_type' ), 5 );
			add_action( 'wp', array( $this, 'maybe_check_for_custom_page' ), 1 );
			add_action( 'wp', array( $this, 'set_id' ), 2 );
			add_action( 'wp', array( $this, 'validate_order' ), 11 );
			add_action( 'wp', array( $this, 'wfty_add_shortcodes' ), 12 );


			add_action( 'wp', array( $this, 'parse_request_for_thankyou' ), - 1 );

			add_action( 'wp', array( $this, 'maybe_set_query_var' ), 1 );

			add_filter( 'template_include', [ $this, 'may_be_change_template' ], 99 );


			$post_type = $this->get_post_type_slug();
			add_filter( "theme_{$post_type}_templates", [ $this, 'registered_page_templates' ], 99, 4 );

			add_action( 'init', array( $this, 'load_files' ), 1 );
			add_action( 'init', array( $this, 'load_instances' ), 1 );
			add_action( 'plugins_loaded', [ $this, 'load_compatibility' ], 2 );

			add_action( 'wp_footer', array( $this, 'execute_wc_thankyou_hooks' ), 1 );

			add_action( 'wp_enqueue_scripts', array( $this, 'thank_you_scripts' ), 21 );
			add_action( 'wffn_import_completed', array( $this, 'set_page_template' ), 10, 2 );

			add_filter( 'post_type_link', array( $this, 'post_type_permalinks' ), 10, 2 );
			add_action( 'pre_get_posts', array( $this, 'add_cpt_post_names_to_main_query' ), 20 );
			add_action( 'bwf_global_save_settings_ty-settings', array( $this, 'update_global_settings_fields' ) );

			add_filter( 'bwf_enable_ecommerce_integration_fb_purchase', '__return_true' );
			add_filter( 'bwf_enable_ecommerce_integration_ga_purchase', '__return_true' );
			add_filter( 'bwf_enable_ecommerce_integration_gad', '__return_true' );
			add_filter( 'bwf_enable_ga4', '__return_true' );
			add_filter( 'woofunnels_global_settings_fields', array( $this, 'add_global_settings_fields' ) );
			add_action( 'template_redirect', array( $this, 'clear_cart' ) );
			$this->load_component_files();
		}

		private function process_url() {
			if ( isset( $_REQUEST['page'] ) && isset( $_REQUEST['edit'] ) && $_REQUEST['edit'] > 0 ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->edit_id = absint( $_REQUEST['edit'] );  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			if ( isset( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && isset( $_REQUEST['post'] ) && $_REQUEST['post'] > 0 ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->edit_id = absint( $_REQUEST['post'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			if ( isset( $_REQUEST['action'] ) && 'elementor_ajax' === $_REQUEST['action'] && isset( $_REQUEST['editor_post_id'] ) && $_REQUEST['editor_post_id'] > 0 ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->edit_id = absint( $_REQUEST['editor_post_id'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
		}

		public function define_plugin_properties() {
			define( 'WFTY_PLUGIN_FILE', __FILE__ );
			define( 'WFTY_PLUGIN_DIR', __DIR__ );
		}

		public static function get_post_type_slug() {
			return 'wffn_ty';
		}

		public function load_component_files() {
			require $this->get_module_path() . 'components/class-wfty-shortcode-component-abstract.php';
			require $this->get_module_path() . 'components/customer-info/class-wfty-customer-info-component.php';
			require $this->get_module_path() . 'components/order-details/class-wfty-order-details-component.php';
		}

		public function get_module_path() {
			return plugin_dir_path( WFFN_PLUGIN_FILE ) . 'modules/thankyou-pages/';
		}


		/**
		 * @return WFFN_Thank_You_WC_Pages|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}


		public static function send_resp( $data = array() ) {
			if ( ! is_array( $data ) ) {
				$data = [];
			}
			$data['nonce'] = wp_create_nonce( 'wftp_secure_key' );
			wp_send_json( $data );
		}

		public function load_files() {
			require $this->get_module_path() . 'includes/class-wfty-data.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			require $this->get_module_path() . 'includes/class-wfty-common.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			require $this->get_module_path() . 'includes/class-wfty-shortcodes.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			do_action( 'wffn_include_files_loaded' );
		}

		public function load_instances() {

			$this->data = WFTY_Data::get_instance();
			add_action( 'wp', array( $this->data, 'load_order_wp' ), 10 );

			WFTY_Shortcodes::init();
			do_action( 'wffn_shortcodes_initialized' );
		}

		public function register_post_type() {
			/**
			 * Thank You Page Post Type
			 */
			$bwb_admin_setting = BWF_Admin_General_Settings::get_instance();

			register_post_type( $this->get_post_type_slug(), apply_filters( 'wffn_thank_you_post_type_args', array(
				'labels'              => array(
					'name'          => $this->get_module_title( true ),
					'singular_name' => $this->get_module_title(),
					'add_new'       => sprintf( __( 'Add %s', 'funnel-builder' ), $this->get_module_title() ),
					'add_new_item'  => sprintf( __( 'Add New %s', 'funnel-builder' ), $this->get_module_title() ),
					'search_items'  => sprintf( esc_html__( 'Search %s', 'funnel-builder' ), $this->get_module_title( true ) ),
					'all_items'     => sprintf( esc_html__( 'All %s', 'funnel-builder' ), $this->get_module_title( true ) ),
					'edit_item'     => sprintf( esc_html__( 'Edit %s', 'funnel-builder' ), $this->get_module_title() ),
					'view_item'     => sprintf( esc_html__( 'View %s', 'funnel-builder' ), $this->get_module_title() ),
					'update_item'   => sprintf( esc_html__( 'Update %s', 'funnel-builder' ), $this->get_module_title() ),
					'new_item_name' => sprintf( esc_html__( 'New %s', 'funnel-builder' ), $this->get_module_title() ),
				),
				'public'              => true,
				'show_ui'             => true,
				'map_meta_cap'        => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => true,
				'show_in_menu'        => false,
				'show_in_admin_bar'   => true,
				'hierarchical'        => false,
				'show_in_nav_menus'   => false,
				'rewrite'             => array(
					'slug'       => ( empty( $bwb_admin_setting->get_option( 'ty_page_base' ) ) ? $this->get_post_type_slug() : $bwb_admin_setting->get_option( 'ty_page_base' ) ),
					'with_front' => false,
				),
				'capabilities'        => array(
					'create_posts' => 'do_not_allow', // Prior to Wordpress 4.5, this was false.
				),
				'show_in_rest'        => true,
				'query_var'           => true,
				'supports'            => array( 'title', 'elementor', 'editor', 'custom-fields', 'revisions', 'thumbnail', 'author' ),
				'has_archive'         => false,
			) ) );
		}

		public function get_module_title( $plural = false ) {
			return ( $plural ) ? __( 'Thank You Pages', 'funnel-builder' ) : __( 'Thank You Page', 'funnel-builder' );
		}

		public function get_option( $key = 'all' ) {

			if ( null === $this->options ) {
				$this->setup_options();
			}
			if ( 'all' === $key ) {
				return $this->options;
			}

			return isset( $this->options[ $key ] ) ? $this->options[ $key ] : false;
		}

		public function setup_options() {
			$db_options    = get_option( 'wffn_tp_settings', [] );
			$db_options    = ( ! empty( $db_options ) && is_array( $db_options ) ) ? array_map( function ( $val ) {
				return is_scalar( $val ) ? html_entity_decode( $val ) : $val;
			}, $db_options ) : array();
			$this->options = wp_parse_args( $db_options, $this->default_global_settings() );

			return $this->options;
		}

		public function default_global_settings() {
			return array(
				'css'                     => '',
				'script'                  => '',
				'is_fb_view_event'        => array(),
				'is_fb_purchase_event'    => array(),
				'is_fb_synced_event'      => array(),
				'is_fb_advanced_event'    => array(),
				'pint_key'                => '',
				'is_ga_view_event'        => array(),
				'id_prefix_gad'           => '',
				'id_suffix_gad'           => '',
				'is_ga_purchase_event'    => array(),
				'is_gad_purchase_event'   => array(),
				'content_id_value'        => 'product_id',
				'content_id_variable'     => array(),
				'content_id_prefix'       => '',
				'content_id_suffix'       => '',
				'track_traffic_source'    => array(),
				'ga_track_traffic_source' => array(),
				'exclude_from_total'      => array(),
				'enable_general_event'    => array(),
				'general_event_name'      => 'GeneralEvent',
				'custom_aud_opt_conf'     => array(),
			);
		}

		public function insert_thank_you_page( $data = null ) {
			$wfty_post_args = $this->get_default_thank_you_page_data();
			if ( ! is_null( $data ) && is_array( $data ) ) {
				$wfty_post_args = array_merge( $wfty_post_args, $data );
			}

			$id = wp_insert_post( $wfty_post_args );

			return $id;
		}

		private function get_default_thank_you_page_data() {
			return array(
				'post_type'    => self::get_post_type_slug(),
				'post_title'   => __( 'Thank You', 'funnel-builder' ),
				'post_name'    => sanitize_title( __( 'Thank You', 'funnel-builder' ) ),
				'post_status'  => 'draft',
				'menu_order'   => '1',
				'post_content' => '',
			);
		}

		public function register_classes() {
			$this->data = WFTY_Data::get_instance();
		}

		/**
		 * @param $url
		 * @param WC_Order $order
		 *
		 * @return mixed|void
		 */
		public function redirect_to_thankyou( $url, $order ) {

			if ( did_action( 'wfocu_funnel_init_event' ) ) {
				return $url;
			}
			$order_id = $order->get_id();
			if ( $order_id > 0 ) {
				$get_link = $this->data->setup_thankyou_post( $order_id )->get_page_link();

				if ( false !== $get_link ) {
					$get_link = trim( $get_link );
					$get_link = wp_specialchars_decode( $get_link );

					return ( WFTY_Common::prepare_single_post_url( $get_link, $order ) );
				}
			}

			return $url;
		}

		/**
		 * Set wfty_is_thankyou flag if it's our page
		 * @return void
		 */
		public function parse_request_for_thankyou() {
			global $post;

			if ( empty( $post ) ) {
				return;
			}

			if ( ! empty( $post->post_type ) && self::get_post_type_slug() === $post->post_type ) {
				$this->wfty_is_thankyou = true;
			}

		}

		public function wfty_add_shortcodes() {
			WFTY_Shortcodes::init();
		}

		public function maybe_set_query_var( $wp_query_obj ) {
			if ( true === $this->is_wfty_page() ) {
				$get_order_id = filter_input( INPUT_GET, 'order_id', FILTER_SANITIZE_NUMBER_INT );
				if ( $get_order_id !== null ) {
					$get_order_received_endpoint                = get_option( 'woocommerce_checkout_order_received_endpoint', 'order-received' );
					$wp_query_obj->query_vars['order-received'] = $get_order_id;
					set_query_var( 'order-received', $get_order_id );
					if ( $get_order_received_endpoint !== 'order-received' ) {
						$wp_query_obj->query_vars[ $get_order_received_endpoint ] = $get_order_id;
						set_query_var( $get_order_received_endpoint, $get_order_id );
					}
					add_filter( 'woocommerce_is_checkout', array( $this, 'declare_wc_checkout_page' ) );
					add_filter( 'woocommerce_is_order_received_page', array( $this, 'declare_wc_order_received_page' ) );
				}

			}
		}

		/**
		 * Checks whether its our page or not
		 * @return bool
		 */
		public function is_wfty_page() {
			return $this->wfty_is_thankyou;
		}


		/**
		 * Validates current order and checks if order qualifies for the current loading
		 * @uses WC_Order::post_status
		 */
		public function validate_order() {
			$order = $this->data->get_order();

			if ( $order instanceof WC_Order ) {
				/**
				 * Check order key from URL so that users cannot open other's thank you page
				 */
				$order_key = $order->get_order_key();
				if ( ! empty( $order_key ) && filter_input( INPUT_GET, 'key', FILTER_UNSAFE_RAW ) !== $order_key ) {
					if ( $this->is_wfty_page() ) {
						wp_die( __( 'Unable to process your request.', 'funnel-builder' ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					$this->data->reset_order();

					return;
				}
			}
		}

		public function declare_wc_order_received_page( $bool ) {
			if ( $this->is_wfty_page() === true ) {
				return true;
			}

			return $bool;

		}

		/**
		 * Copy data from old thankyou page to new thankyou page
		 *
		 * @param $ty_page_id
		 *
		 * @return int|WP_Error
		 */
		public function duplicate_thank_you_page( $ty_page_id ) {

			$exclude_metas = array(
				'cartflows_imported_step',
				'enable-to-import',
				'site-sidebar-layout',
				'site-content-layout',
				'theme-transparent-header-meta',
				'_uabb_lite_converted',
				'_astra_content_layout_flag',
				'site-post-title',
				'ast-title-bar-display',
				'ast-featured-img',
				'_thumbnail_id',
			);

			if ( $ty_page_id > 0 ) {
				$ty_page = get_post( $ty_page_id );
				if ( ! is_null( $ty_page ) && ( $ty_page->post_type === $this->get_post_type_slug() || in_array( $ty_page->post_type, $this->get_inherit_supported_post_type(), true ) ) ) {

					$suffix_text = ' - ' . __( 'Copy', 'funnel-builder' );
					if ( did_action( 'wffn_duplicate_funnel' ) > 0 ) {
						$suffix_text = '';
					}

					$args         = [
						'post_title'   => $ty_page->post_title . $suffix_text,
						'post_content' => $ty_page->post_content,
						'post_name'    => sanitize_title( $ty_page->post_title . $suffix_text ),
						'post_type'    => $this->get_post_type_slug(),
					];
					$duplicate_id = wp_insert_post( $args );
					if ( ! is_wp_error( $duplicate_id ) ) {

						global $wpdb;

						$post_meta_all = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$ty_page_id" ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,

						if ( ! empty( $post_meta_all ) ) {
							$sql_query_selects = [];

							if ( in_array( $ty_page->post_type, $this->get_inherit_supported_post_type(), true ) ) {


								foreach ( $post_meta_all as $meta_info ) {

									$meta_key   = $meta_info->meta_key;
									$meta_value = $meta_info->meta_value;

									if ( in_array( $meta_key, $exclude_metas, true ) ) {
										continue;
									}
									if ( strpos( $meta_key, 'wcf-' ) !== false ) {
										continue;
									}

									if ( $meta_key === '_wp_page_template' ) {
										$meta_value = ( strpos( $meta_value, 'cartflows' ) !== false ) ? str_replace( 'cartflows', "wftp", $meta_value ) : $meta_value;
									}

									$meta_key   = esc_sql( $meta_key );
									$meta_value = esc_sql( $meta_value );


									$sql_query_selects[] = "($duplicate_id, '$meta_key', '$meta_value')";

								}
							} else {

								foreach ( $post_meta_all as $meta_info ) {

									$meta_key = $meta_info->meta_key;
									if ( $meta_key === '_bwf_ab_variation_of' ) {
										continue;
									}

									$meta_key   = esc_sql( $meta_key );
									$meta_value = esc_sql( $meta_info->meta_value );

									$sql_query_selects[] = "($duplicate_id, '$meta_key', '$meta_value')";
								}
							}

							$sql_query_meta_val = implode( ',', $sql_query_selects );
							$wpdb->query( $wpdb->prepare( 'INSERT INTO %1$s (post_id, meta_key, meta_value) VALUES ' . $sql_query_meta_val, $wpdb->postmeta ) );//phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder,WordPress.DB.PreparedSQL.NotPrepared

							if ( in_array( $ty_page->post_type, $this->get_inherit_supported_post_type(), true ) ) {
								$template = WFFN_Core()->admin->get_selected_template( $ty_page_id, $post_meta_all );
								update_post_meta( $duplicate_id, '_wftp_selected_design', $template );
							}
							do_action( 'wffn_step_duplicated', $duplicate_id );

							return $duplicate_id;
						}

						if ( in_array( $ty_page->post_type, $this->get_inherit_supported_post_type(), true ) ) {
							$template = WFFN_Core()->admin->get_selected_template( $ty_page_id, $post_meta_all );
							update_post_meta( $duplicate_id, '_wftp_selected_design', $template );
						}
						do_action( 'wffn_step_duplicated', $duplicate_id );

						return $duplicate_id;
					}
				}
			}

			return 0;
		}

		/**
		 * @return array
		 */
		public function get_thank_you_pages( $term ) {
			$args = array(
				'post_type'   => array( $this->get_post_type_slug(), 'cartflows_step', 'page' ),
				'post_status' => 'any',
			);
			if ( ! empty( $term ) ) {
				if ( is_numeric( $term ) ) {
					$args['p'] = $term;
				} else {
					$args['s'] = $term;
				}
			}
			$query_result = new WP_Query( $args );
			if ( $query_result->have_posts() ) {
				return $query_result->posts;
			}

			return array();
		}


		public function get_optionsShortCode( $key = 'all', $id = 0 ) {
			$id = ( 0 === $id ) ? $this->edit_id : $id;
			$id = empty( $id ) ? filter_input( INPUT_GET, 'preview_id', FILTER_UNSAFE_RAW ) : $id;
			$id = empty( $id ) ? $this->thankyoupage_id : $id;

			if ( empty( $id ) ) {
				return false;
			}
			if ( null === $this->optionsShortCode ) {
				$this->setup_options_shortcode( $id );
			}
			if ( 'all' === $key ) {
				return $this->optionsShortCode;
			}

			return isset( $this->optionsShortCode[ $key ] ) ? $this->optionsShortCode[ $key ] : false;
		}

		public function setup_options_shortcode( $id ) {
			$db_options             = get_post_meta( $id, '_shortcode_settings', true );
			$this->optionsShortCode = wp_parse_args( $db_options, $this->default_shortcode_settings() );

			return $this->optionsShortCode;
		}

		public function default_shortcode_settings() {
			return array(
				'txt_color'                           => "#444444",
				'txt_fontfamily'                      => "default",
				'txt_font_size'                       => "15",
				'head_color'                          => "#444444",
				'head_font_size'                      => "20",
				'head_font_weight'                    => 'default',
				'layout_settings'                     => '2c',
				'order_details_heading'               => __( 'Order Details', 'funnel-builder' ),
				'customer_details_heading'            => __( 'Customer Details', 'funnel-builder' ),
				'order_details_img'                   => 'true',
				'order_downloads_btn_text'            => __( 'Download', 'funnel-builder' ),
				'order_download_heading'              => __( 'Downloads', 'funnel-builder' ),
				'order_downloads_show_file_downloads' => 'true',
				'order_downloads_show_file_expiry'    => 'true',
				'order_subscription_heading'          => __( 'Subscription', 'funnel-builder' ),
			);
		}

		public function get_custom_option( $key = 'all' ) {

			if ( null === $this->custom_options ) {
				$this->setup_custom_options();
			}
			if ( 'all' === $key ) {
				return $this->custom_options;
			}

			return isset( $this->custom_options[ $key ] ) ? $this->custom_options[ $key ] : false;
		}


		public function default_custom_settings() {
			return array(
				'custom_css'      => '',
				'custom_js'       => '',
				'custom_redirect' => 'false',
			);
		}

		public function get_page_design( $page_id ) {
			$design_data = get_post_meta( $page_id, '_wftp_selected_design', true );
			if ( empty( $design_data ) ) {
				$design_data = $this->default_design_data();
			}

			return $design_data;
		}

		public function default_design_data() {
			return [
				'selected'        => 'wp_editor_1',
				'selected_type'   => 'wp_editor',
				'template_active' => 'no',
			];
		}


		/**
		 * Save selected design template against checkout page
		 */

		public function save_design() {
			$resp = array(
				'msg'    => '',
				'status' => false,
			);

			check_ajax_referer( 'wffn_tp_save_design', '_nonce' );
			$wftp_id = isset( $_POST['wftp_id'] ) ? absint( wffn_clean( $_POST['wftp_id'] ) ) : 0;
			if ( $wftp_id > 0 ) {
				$selected_type = isset( $_POST['selected_type'] ) ? wffn_clean( $_POST['selected_type'] ) : '';
				$selected      = isset( $_POST['selected'] ) ? wffn_clean( $_POST['selected'] ) : '';
				$data          = [
					'selected'        => $selected,
					'selected_type'   => $selected_type,
					'template_active' => isset( $_POST['template_active'] ) ? wffn_clean( $_POST['template_active'] ) : '',
				];
				do_action( 'wffn_design_saved', $wftp_id, $selected_type, 'wc_thankyou' );

				$this->update_page_design( $wftp_id, $data );
				do_action( 'wfty_page_design_updated', $wftp_id, $data );

				$resp = array(
					'msg'    => __( 'Design Saved Successfully', 'funnel-builder' ),
					'status' => true
				);
			}
			self::send_resp( $resp );
		}

		public function update_page_design( $page_id, $data ) {
			if ( $page_id < 1 ) {
				return $data;
			}
			if ( ! is_array( $data ) ) {
				$data = $this->default_design_data();
			}
			update_post_meta( $page_id, '_wftp_selected_design', $data );

			if ( isset( $data['selected_type'] ) && 'wp_editor' === $data['selected_type'] ) {
				update_post_meta( $page_id, '_wp_page_template', 'wftp-boxed.php' );
			} else {
				update_post_meta( $page_id, '_wp_page_template', 'wftp-canvas.php' );
			}
		}

		public function remove_design() {
			$resp = array(
				'msg'    => '',
				'status' => false,
			);
			check_ajax_referer( 'wffn_tp_remove_design', '_nonce' );
			if ( isset( $_POST['wftp_id'] ) && $_POST['wftp_id'] > 0 ) {
				$wftp_id                     = absint( $_POST['wftp_id'] );
				$template                    = $this->default_design_data();
				$template['template_active'] = 'no';
				$this->update_page_design( $wftp_id, $template );
				do_action( 'wftp_template_removed', $wftp_id );
				do_action( 'woofunnels_module_template_removed', $wftp_id );

				$args = [
					'ID'           => $wftp_id,
					'post_content' => ''
				];
				wp_update_post( $args );

				$resp = array(
					'msg'    => __( 'Design Saved Successfully', 'funnel-builder' ),
					'status' => true,
				);
			}
			self::send_resp( $resp );
		}

		public function import_template() {
			check_ajax_referer( 'wffn_tp_import_design', '_nonce' );
			$resp     = [
				'status' => false,
				'msg'    => __( 'Importing of template failed', 'funnel-builder' ),
			];
			$builder  = isset( $_REQUEST['builder'] ) ? wffn_clean( $_REQUEST['builder'] ) : '';
			$template = isset( $_REQUEST['template'] ) ? wffn_clean( $_REQUEST['template'] ) : '';
			$wftp_id  = isset( $_REQUEST['wftp_id'] ) ? wffn_clean( $_REQUEST['wftp_id'] ) : '';

			$result = WFFN_Core()->importer->import_remote( $wftp_id, $builder, $template, $this->get_cloud_template_step_slug() );


			if ( true === $result['success'] ) {
				$resp['status'] = true;
				$resp['msg']    = __( 'Importing of template finished', 'funnel-builder' );
			} else {
				$resp['error'] = $result['error'];
			}
			self::send_resp( $resp );
		}

		public function get_cloud_template_step_slug() {
			return 'wc_thankyou';
		}


		public function registered_page_templates( $templates ) {

			$all_templates = wp_get_theme()->get_post_templates();
			$path          = [

				'wftp-boxed.php'  => __( 'FunnelKit Boxed', 'funnel-builder' ),
				'wftp-canvas.php' => __( 'FunnelKit Canvas for Page Builder', 'funnel-builder' )
			];
			if ( isset( $all_templates['page'] ) && is_array( $all_templates['page'] ) ) {
				$paths = array_merge( $all_templates['page'], $path );
			} else {
				$paths = $path;
			}
			if ( is_array( $paths ) && is_array( $templates ) ) {
				$paths = array_merge( $paths, $templates );
			}

			return $paths;

		}

		public function may_be_change_template( $template ) {
			global $post;
			if ( ! is_null( $post ) && $post->post_type === $this->get_post_type_slug() ) {
				$template = $this->get_template_url(    $template );
			}

			return $template;

		}

		public function get_template_url( $main_template ) {
			global $post;
			$wftp_id = $post->ID;

			$page_template = apply_filters( 'bwf_page_template', get_post_meta( $wftp_id, '_wp_page_template', true ), $wftp_id );

			$file         = '';
			$body_classes = [];
			switch ( $page_template ) {

				case 'wftp-boxed.php':
					$file           = $this->get_module_path() . 'templates/wftp-boxed.php';
					$body_classes[] = $page_template;
					break;

				case 'wftp-canvas.php':
					$file           = $this->get_module_path() . 'templates/wftp-canvas.php';
					$body_classes[] = $page_template;
					break;

				default:
					/**
					 ** Unhook any Next/Prev Navigation
					 **/ add_filter( 'next_post_link', '__return_empty_string' );
					add_filter( 'previous_post_link', '__return_empty_string' );

					if ( false !== strpos( $main_template, 'single.php' ) ) {
						$page = locate_template( array( 'page.php' ) );

					}

					if ( ! empty( $page ) ) {
						$file = $page;
					}

					break;

			}
			if ( ! empty( $body_classes ) ) {
				add_filter( 'body_class', [ $this, 'wffn_add_unique_class' ], 9999, 1 );
			}
			if ( file_exists( $file ) ) {

				return $file;
			}

			return $main_template;
		}



		public function load_compatibility() {
			include_once $this->get_module_path() . 'compatibilities/page-builders/gutenberg/class-wfty-gutenberg-extension.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable

			include_once $this->get_module_path() . 'compatibilities/page-builders/elementor/class-wffn-thankyou-wc-pages-elementor.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			include_once $this->get_module_path() . 'compatibilities/page-builders/divi/class-wffn-thankyou-wc-pages-divi.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			include_once $this->get_module_path() . 'compatibilities/page-builders/oxygen/class-wffn-thankyou-wc-pages-oxygen.php'; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable

		}



		public function get_edit_id() {
			return $this->edit_id;
		}

		public function register_template_type( $data ) {

			if ( isset( $data['slug'] ) && ! empty( $data['slug'] ) && isset( $data['title'] ) && ! empty( $data['title'] ) ) {
				$slug  = sanitize_title( $data['slug'] );
				$title = esc_html( trim( $data['title'] ) );
				if ( ! isset( $this->template_type[ $slug ] ) ) {
					$this->template_type[ $slug ]        = trim( $title );
					$this->design_template_data[ $slug ] = [
						'edit_url'    => $data['edit_url'],
						'button_text' => $data['button_text'],
						'title'       => $data['title'],
						'description' => isset( $data['description'] ) ? $data['description'] : '',
					];
				}
			}
		}

		public function register_template( $slug, $data, $type = 'pre_built' ) {
			if ( '' !== $slug && ! empty( $data ) ) {
				$this->templates[ $type ][ $slug ] = $data;
			}
		}

		public function declare_wc_checkout_page( $bool ) {
			if ( $this->is_wfty_page() === true ) {
				return true;
			}

			return $bool;

		}

		public function update_global_settings_fields( $options ) {
			$options = ( is_array( $options ) && count( $options ) > 0 ) ? wp_unslash( $options ) : 0;
			$resp    = [
				'status' => false,
				'msg'    => __( 'Settings Updated', 'funnel-builder' ),
				'data'   => '',
			];

			if ( ! is_array( $options ) || count( $options ) === 0 ) {
				return $resp;
			}

			$options['css']    = isset( $options['css'] ) ? htmlentities( $options['css'] ) : '';
			$options['script'] = isset( $options['script'] ) ? htmlentities( $options['script'] ) : '';
			$this->update_options( $options );
			$resp['status'] = true;

			return $resp;
		}

		public function update_options( $options ) {
			update_option( 'wffn_tp_settings', $options, true );
		}


		public function update_edit_url() {
			check_admin_referer( 'wffn_tp_update_edit_url', '_nonce' );

			$id  = isset( $_POST['id'] ) ? wffn_clean( $_POST['id'] ) : 0;
			$url = isset( $_POST['url'] ) ? $_POST['url'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			if ( absint( $id ) > 0 && ( $url !== '' ) ) {
				$url .= $this->check_oxy_inner_content( $id );
			}

			$resp = [
				'status' => true,
				'url'    => $url,
			];
			wp_send_json( $resp );
		}


		/**
		 * Hooked over `wp_footer`
		 * Trying and executing wc native thankyou hooks
		 * Payment Gateways and other plugin usually use these hooks to read order data and process
		 * Also removes native woocommerce_order_details_table() to prevent order table load
		 */
		public function execute_wc_thankyou_hooks() {

			if ( ! $this->is_wfty_page() ) {
				return;
			}
			if ( ! $this->data->get_order() instanceof WC_Order ) {
				return;
			}

			if ( 0 !== did_action( 'woocommerce_thankyou' ) ) {
				return;
			}
			$order = $this->data->get_order();
			remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 ); ?>
            <div class="wffn_wfty_wc_thankyou" style="display: none; opacity: 0">
				<?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>
				<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
            </div>
			<?php
		}

		public function toggle_state() {
			check_ajax_referer( 'wffn_tp_toggle_state', '_nonce' );
			$resp = [
				'status' => false,
				'msg'    => __( 'Unable to change state', 'funnel-builder' ),
			];

			$state   = isset( $_POST['toggle_state'] ) ? sanitize_text_field( $_POST['toggle_state'] ) : '';
			$wftp_id = isset( $_POST['wftp_id'] ) ? sanitize_text_field( $_POST['wftp_id'] ) : '';

			$status = ( 'true' === $state ) ? 'publish' : 'draft';

			wp_update_post( [ 'ID' => $wftp_id, 'post_status' => $status ] );

			$resp['status'] = true;
			$resp['msg']    = __( 'Status changed successfully.', 'funnel-builder' );


			self::send_resp( $resp );
		}

		public function get_status() {
			$post_lp = get_post( $this->get_edit_id() );

			return $post_lp->post_status;
		}

		public function thank_you_scripts() {

			if ( ! $this->is_wfty_page() ) {
				return;
			}

			wp_enqueue_style( 'wffn_frontend_tp_css', $this->url . 'assets/css/style.css', [], time() );

			$style = $this->generate_thank_you_style();
			wp_add_inline_style( 'wffn_frontend_tp_css', $style );
			$page_template = get_post_meta( $this->thankyoupage_id, '_wp_page_template', true );
			if ( 'default' === $page_template || empty( $page_template ) ) {
				return;
			}
			if ( true === apply_filters( 'wfty_load_frontend_style', true, $this->thankyoupage_id ) ) {
				wp_enqueue_style( 'wffn-frontend-style' );
			}

		}

		public function generate_thank_you_style() {
			$thank_you_id        = $this->thankyoupage_id;
			$text_font_family    = $this->get_optionsShortCode( 'txt_fontfamily', $thank_you_id );
			$text_color          = $this->get_optionsShortCode( 'txt_color', $thank_you_id );
			$text_font_size      = $this->get_optionsShortCode( 'txt_font_size', $thank_you_id );
			$heading_text_color  = $this->get_optionsShortCode( 'head_color', $thank_you_id );
			$heading_font_size   = $this->get_optionsShortCode( 'head_font_size', $thank_you_id );
			$heading_font_weight = $this->get_optionsShortCode( 'head_font_weight', $thank_you_id );
			$order_show_images   = $this->get_optionsShortCode( 'order_details_img', $thank_you_id );
			$order_show_images   = ( empty( $order_show_images ) ) ? 'true' : $order_show_images; // string

			$text_font_family    = ( 'default' !== $text_font_family ? $text_font_family : "inherit" );
			$text_color          = ( empty( $text_color ) ) ? 'inherit' : $text_color;
			$text_font_size      = ( empty( $text_font_size ) ) ? '15' : $text_font_size;
			$heading_text_color  = ( empty( $heading_text_color ) ) ? 'inherit' : $heading_text_color;
			$heading_font_size   = ( empty( $heading_font_size ) ) ? '18' : $heading_font_size;
			$heading_font_weight = ( empty( $heading_font_weight ) ) ? '400' : $heading_font_weight;
			$font_array          = [];
			$primary_body_class  = 'postid-' . $thank_you_id;
			if ( 'inherit' !== $text_font_family ) {
				$font_array[] = $text_font_family;
			}

			if ( ! empty( $font_array ) ) {
				$font_array      = array_unique( $font_array );
				$font_string     = implode( '|', $font_array );
				$google_font_url = "//fonts.googleapis.com/css?family=" . $font_string;
				wp_enqueue_style( 'wffn-google-fonts', esc_url( $google_font_url ), array(), WFFN_VERSION, 'all' );

			}

			$output = "
		body.$primary_body_class .wfty_wrap * {
			color: {$text_color};
			font-family: {$text_font_family};
			font-size: {$text_font_size}px;
		}
		
		body.$primary_body_class .wfty_wrap .wfty_box.wfty_order_details table tr th,
		body.$primary_body_class .wfty_wrap .wfty_box.wfty_order_details table tr td,
		body.$primary_body_class .wffn_customer_details_table,		
		body.$primary_body_class .wfty_Dview{
			color: {$text_color};
			font-family: {$text_font_family};
			font-size: {$text_font_size}px;
		}

		body.$primary_body_class .woocommerce-order h2.woocommerce-column__title, 
		body.$primary_body_class .wffn_customer_details_table .woocommerce-customer-details h2.woocommerce-column__title, 
		body.$primary_body_class .woocommerce-order h2.woocommerce-order-details__title, 
		body.$primary_body_class .woocommerce-order .woocommerce-thankyou-order-received,
		body.$primary_body_class .wfty_wrap .woocommerce-order-details h2,
		body.$primary_body_class .woocommerce-order h2.wc-bacs-bank-details-heading,
		body.$primary_body_class .wfty_customer_info .wfty_text_bold,
		body.$primary_body_class .wfty_wrap .wfty_title, body.$primary_body_class .wfty_wrap .wc-bacs-bank-details-heading
		 {
			color: {$heading_text_color};
			font-size: {$heading_font_size}px;
			font-weight: {$heading_font_weight};
		}

		.woocommerce-order ul.order_details,
		.woocommerce-order .woocommerce-order-details,
		.woocommerce-order .woocommerce-customer-details,
		
		img.emoji, img.wp-smiley {}";
			if ( 'false' === $order_show_images ) {
				$output .= "body.$primary_body_class .wfty_wrap .wfty_order_details .wfty_pro_list .wfty_leftDiv .wfty_p_name {padding-left:0}";
			}

			return $output;
		}

		public function set_page_template( $wftp_id, $module ) {
			if ( $this->get_cloud_template_step_slug() !== $module ) {
				return;
			}
			update_post_meta( $wftp_id, '_wp_page_template', 'wftp-boxed.php' );
		}

		public function wffn_add_unique_class( $classes ) {
			$classes[] = 'wffn-page-template';

			return $classes;
		}



		public function maybe_check_for_custom_page() {
			global $post;
			$maybe_wfty_id = filter_input( INPUT_GET, 'wfty_source', FILTER_SANITIZE_NUMBER_INT );
			if ( empty( $maybe_wfty_id ) ) {
				return;
			}
			if ( empty( $post ) ) {
				return;
			}

			global $wp_query;
			$this->thankyoupage_id  = $maybe_wfty_id;
			$this->wfty_is_thankyou = true;
			$this->maybe_set_query_var( $wp_query );

		}

		public function set_id( $id = null ) {
			if ( ! is_null( $id ) && is_integer( $id ) ) {
				$this->thankyoupage_id = $id;
			}
			if ( $this->is_wfty_page() && empty( $this->thankyoupage_id ) ) {
				global $post;
				$this->thankyoupage_id = $post->ID;
			}
		}

		public function get_id() {
			return apply_filters( 'wffn_thankyou_page_id', $this->thankyoupage_id );
		}

		/**
		 * Modify permalink
		 *
		 * @param string $post_link post link.
		 * @param array $post post data.
		 * @param string $leavename leave name.
		 *
		 * @return string
		 */
		public function post_type_permalinks( $post_link, $post ) {

			$bwb_admin_setting = BWF_Admin_General_Settings::get_instance();

			if ( isset( $post->post_type ) && $this->get_post_type_slug() === $post->post_type && empty( trim( $bwb_admin_setting->get_option( 'ty_page_base' ) ) ) ) {


				// If elementor page preview, return post link as it is.
				if ( isset( $_REQUEST['elementor-preview'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
					return $post_link;
				}

				$structure = get_option( 'permalink_structure' );

				if ( in_array( $structure, $this->get_supported_permalink_structures_to_normalize(), true ) ) {

					$post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );

				}

			}

			return $post_link;
		}

		/**
		 * Have WordPress match postname to any of our public post types.
		 * All of our public post types can have /post-name/ as the slug, so they need to be unique across all posts.
		 * By default, WordPress only accounts for posts and pages where the slug is /post-name/.
		 *
		 * @param WP_Query $query query statement.
		 */
		public function add_cpt_post_names_to_main_query( $query ) {

			// Bail if this is not the main query.
			if ( ! $query->is_main_query() ) {
				return;
			}


			// Bail if this query doesn't match our very specific rewrite rule.
			if ( ! isset( $query->query['page'] ) || 2 !== count( $query->query ) ) {
				return;
			}

			// Bail if we're not querying based on the post name.
			if ( empty( $query->query['name'] ) ) {
				return;
			}

			// Add landing page step post type to existing post type array.
			if ( isset( $query->query_vars['post_type'] ) && is_array( $query->query_vars['post_type'] ) ) {

				$post_types = $query->query_vars['post_type'];

				$post_types[] = $this->get_post_type_slug();

				$query->set( 'post_type', $post_types );

			} else {

				// Add CPT to the list of post types WP will include when it queries based on the post name.
				$query->set( 'post_type', array( 'post', 'page', $this->get_post_type_slug() ) );
			}
		}


		public function get_inherit_supported_post_type() {
			return apply_filters( 'wffn_wfty_inherit_supported_post_type', array( 'cartflows_step', 'page' ) );
		}


		public function add_global_settings_fields( $fields ) {
			$fields["ty-settings"] = $this->all_global_settings_fields();

			return $fields;
		}

		public function all_global_settings_fields() {

			$array = array(

				'custom_css'      => array(
					'title'    => __( 'Custom CSS', 'funnel-builder' ),
					'heading'  => __( 'Custom CSS', 'funnel-builder' ),
					'slug'     => 'custom_css',
					'fields'   => array(
						array(
							'key'         => 'css',
							'type'        => 'textArea',
							'label'       => __( 'Custom CSS Tweaks', 'funnel-builder' ),
							'placeholder' => __( 'Type here...', 'funnel-builder' ),
						),

					),
					'priority' => 5,
				),
				'external_script' => array(
					'title'    => __( 'External Scripts', 'funnel-builder' ),
					'heading'  => __( 'External Scripts', 'funnel-builder' ),
					'slug'     => 'external_script',
					'fields'   => array(

						array(
							'key'         => 'script',
							'type'        => 'textArea',
							'label'       => __( 'External JS Scripts', 'funnel-builder' ),
							'placeholder' => __( 'Type here...', 'funnel-builder' ),
						),

					),
					'priority' => 10,
				),
			);
			foreach ( $array as &$arr ) {
				$values = [];
				foreach ( $arr['fields'] as &$field ) {
					$values[ $field['key'] ] = $this->get_option( $field['key'] );
				}
				$arr['values'] = $values;
			}

			return $array;
		}

		public function clear_cart() {
			if ( function_exists( 'wc_clear_cart_after_payment' ) ) {
				wc_clear_cart_after_payment();
			}

		}

		public function get_settings_tab_data( $values = null ) {

			$tabs = [
				'custom_redirect' => [
					'title'    => __( 'Custom Redirection', 'funnel-builder' ),
					'heading'  => __( 'Custom Redirection', 'funnel-builder' ),
					'slug'     => 'custom_redirect',
					'fields'   => [
						0 => [
							'type'   => 'radios',
							'key'    => 'custom_redirect',
							'label'  => __( 'Custom Redirection', 'funnel-builder' ),
							'hint'   => '',
							'values' => [
								0 => [
									'value' => 'true',
									'name'  => 'Yes',
								],
								1 => [
									'value' => 'false',
									'name'  => 'No',
								],
							],
						],
						1 => [
							'type'        => 'custom-select',
							'key'         => 'select_redirect_page',
							'placeholder' => __( 'Select Option', 'funnel-builder' ),
							'apiEndPoint' => '/funnels/pages/search?pages=wc_thankyou',
							'label'       => __( 'Select Page', 'funnel-builder' ),
							'toggler'     => [
								'key'   => 'custom_redirect',
								'value' => 'true',
							],
							'hintLabel'   => __( 'Enter minimum 3 letters.', 'funnel-builder' ),
							'hint'        => '',
							'required'    => true,
							'values'      => ! empty( $values['custom_redirect_page'] ) ? wffn_clean( wffn_rest_api_helpers()->array_change_key( $values['custom_redirect_page'], 'product', 'name' ) ) : '',
						],
					],
					'priority' => 10,
					'values'   => '',
				],
				'custom_css'      => [
					'title'    => __( 'Custom CSS', 'funnel-builder' ),
					'heading'  => __( 'Custom CSS', 'funnel-builder' ),
					'slug'     => 'custom_css',
					'fields'   => [
						0 => [
							'key'         => 'custom_css',
							'type'        => 'textArea',
							'label'       => __( 'Custom CSS Tweaks', 'funnel-builder' ),
							'placeholder' => __( 'Paste your CSS code here', 'funnel-builder' ),
							'className'   => 'bwf-textarea-lg-resizable',
						],
					],
					'priority' => 20,
					'values'   => [
						'custom_css' => '',
					],
				],
				'custom_js'       => [
					'title'    => __( 'External Script', 'funnel-builder' ),
					'heading'  => __( 'External Scripts', 'funnel-builder' ),
					'slug'     => 'custom_js',
					'fields'   => [
						0 => [
							'key'         => 'custom_js',
							'type'        => 'textArea',
							'label'       => __( 'Custom JS Tweaks', 'funnel-builder' ),
							'placeholder' => __( 'Paste your code here', 'funnel-builder' ),
							'className'   => 'bwf-textarea-lg-resizable',
						],
					],
					'priority' => 30,
					'values'   => [
						'custom_js' => '',
					],
				],
			];

			if ( ! empty( $values ) ) {
				if ( ! empty( $values['custom_css'] ) ) {
					$tabs['custom_css']['values']['custom_css'] = html_entity_decode( $values['custom_css'] );
				}

				if ( ! empty( $values['custom_js'] ) ) {
					$tabs['custom_js']['values']['custom_js'] = html_entity_decode( $values['custom_js'] );
				}

				if ( isset( $values['custom_redirect'] ) ) {

					$custom_redirect = [
						'custom_redirect'      => ! empty( $values['custom_redirect'] ) ? wffn_clean( $values['custom_redirect'] ) : '',
						'pages'                => ! empty( $values['pages'] ) ? wffn_clean( $values['pages'] ) : '',
						'select_redirect_page' => ! empty( $values['custom_redirect_page'] ) ? wffn_clean( wffn_rest_api_helpers()->array_change_key( $values['custom_redirect_page'], 'product', 'name' ) ) : '',
						'not_found'            => ! empty( $values['not_found'] ) ? wffn_clean( $values['not_found'] ) : __( 'Oops! No elements found. Consider changing the search query.', 'funnel-builder' ),
					];

					$tabs['custom_redirect']['values'] = wffn_clean( $custom_redirect );

				}
			}

			return $tabs;
		}

	}


	if ( class_exists( 'WFFN_Core' ) && wffn_is_wc_active() ) {
		WFFN_Core::register( 'thank_you_pages', 'WFFN_Thank_You_WC_Pages' );
	}
}

