<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff

defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Funnel landing page module
 * Class WFFN_Landing_Pages
 */
if ( ! class_exists( 'WFFN_Landing_Pages' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Landing_Pages extends WFFN_Module_Common {

		private static $ins = null;
		/**
		 * @var WFLP_Admin|null
		 */
		public $admin;
		protected $options;
		protected $custom_options;
		protected $template_type = [];
		protected $design_template_data = [];
		protected $templates = [];
		protected $wflp_is_landing = false;
		public $edit_id = 0;
		public $url = '';
		public $ecom_tracking = null;

		/**
		 * WFFN_Landing_Pages constructor.
		 */
		public function __construct() {
			parent::__construct();
			$this->url = plugin_dir_url( __FILE__ );
			$this->process_url();

			if ( is_admin() ) {
				include_once __DIR__ . '/class-wflp-admin.php';
				$this->admin = WFLP_Admin::get_instance();
			}

			include_once __DIR__ . '/includes/class-wffn-ecomm-tracking-landing.php';
			$this->ecom_tracking = WFFN_Ecomm_Tracking_Landing::get_instance();

			add_action( 'init', array( $this, 'register_post_type' ), 5 );
			add_action( 'init', array( $this, 'load_compatibility' ), 2 );


			add_filter( 'template_include', [ $this, 'may_be_change_template' ], 99 );

			$post_type = $this->get_post_type_slug();
			add_filter( "theme_{$post_type}_templates", [ $this, 'registered_page_templates' ], 99, 4 );

			add_action( 'wp', array( $this, 'parse_request_for_landing' ), - 1 );


			add_action( 'wp_enqueue_scripts', array( $this, 'landing_page_frontend_scripts' ), 21 );
			add_action( 'wffn_import_completed', array( $this, 'set_page_template' ), 10, 2 );
			add_action( 'bwf_global_save_settings_lp-settings', array( $this, 'update_global_settings_fields' ) );

			add_filter( 'post_type_link', array( $this, 'post_type_permalinks' ), 10, 2 );
			add_action( 'pre_get_posts', array( $this, 'add_cpt_post_names_to_main_query' ), 20 );
			add_filter( 'bwf_general_settings_default_config', function ( $fields ) {
				$fields['landing_page_base'] = 'sp';

				return $fields;
			} );

			add_filter( 'woofunnels_global_settings', function ( $menu ) {

				array_push( $menu, array(
					'title'    => __( 'Sales', 'funnel-builder' ),
					'slug'     => 'lp-settings',
					'priority' => 20,
				) );

				return $menu;
			} );
			add_filter( 'woofunnels_global_settings_fields', array( $this, 'add_global_settings_fields' ) );
			add_filter( 'bwf_general_settings_fields', array( $this, 'add_permalink_settings' ), 10 );
		}

		private function process_url() {

			if ( isset( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && isset( $_REQUEST['post'] ) && $_REQUEST['post'] > 0 ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->edit_id = absint( $_REQUEST['post'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			if ( isset( $_REQUEST['action'] ) && 'elementor_ajax' === $_REQUEST['action'] && isset( $_REQUEST['editor_post_id'] ) && $_REQUEST['editor_post_id'] > 0 ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->edit_id = absint( $_REQUEST['editor_post_id'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
		}


		public function get_post_type_slug() {
			return 'wffn_landing';
		}

		/**
		 * @return WFFN_Landing_Pages|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}


		public function register_post_type() {
			/**
			 * Landing page Post Type
			 */

			$bwb_admin_setting = BWF_Admin_General_Settings::get_instance();

			register_post_type( $this->get_post_type_slug(), apply_filters( 'wffn_landing_post_type_args', array(
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
				'hierarchical'        => false,
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'rewrite'             => array(
					'slug'       => ( empty( $bwb_admin_setting->get_option( 'landing_page_base' ) ) ? $this->get_post_type_slug() : $bwb_admin_setting->get_option( 'landing_page_base' ) ),
					'with_front' => false,
				),
				'capabilities'        => array(
					'create_posts' => 'do_not_allow', // Prior to Wordpress 4.5, this was false.
				),
				'query_var'           => true,
				'show_in_rest'        => true,
				'supports'            => array( 'title', 'elementor', 'editor', 'custom-fields', 'revisions', 'thumbnail', 'author' ),
				'has_archive'         => false,
			) ) );
		}

		public function landing_page_frontend_scripts() {
			$funnel    = WFFN_Core()->data->get_session_funnel();
			$funnel_id = '';

			if ( wffn_is_valid_funnel( $funnel ) ) {
				$funnel_id = 'funnel id: #' . $funnel->get_id() . ', ';
			}

			if ( $this->is_wflp_page() ) {
				global $post;

				$page_template = ( $post instanceof WP_Post ) ? get_post_meta( $post->ID, '_wp_page_template', true ) : '';
				if ( 'default' === $page_template || empty( $page_template ) ) {
					return;
				}
				WFFN_Core()->logger->log( $funnel_id . 'Sale Page ID: #' . $post->ID . ' sales page load scripts' );
				wp_enqueue_style( 'wffn-frontend-style' );
			}
		}

		/**
		 * Checks whether its our page or not
		 * @return bool
		 */
		public function is_wflp_page() {
			return $this->wflp_is_landing;
		}

		/**
		 * Set wfty_is_thankyou flag if it's our page
		 * @return void
		 */
		public function parse_request_for_landing() {
			global $post;

			if ( is_null( $post ) || ! $post instanceof WP_Post ) {
				return;
			}

			$funnel = WFFN_Core()->data->get_session_funnel();

			if ( is_singular( $post->post_type ) && ( $this->get_post_type_slug() === $post->post_type ) ) {

				if ( wffn_is_valid_funnel( $funnel ) ) {
					WFFN_Core()->logger->log( "Funnel id: #" . $funnel->get_id() . " parse request for sales page" );
				}

				$this->wflp_is_landing = true;
			}
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

		public function get_custom_option( $key = 'all' ) {

			if ( null === $this->custom_options ) {
				$this->setup_custom_options();
			}
			if ( 'all' === $key ) {
				return $this->custom_options;
			}

			return isset( $this->custom_options[ $key ] ) ? $this->custom_options[ $key ] : false;
		}

		public function setup_options() {
			$db_options    = get_option( 'wffn_lp_settings', [] );
			$db_options    = ( ! empty( $db_options ) && is_array( $db_options ) ) ? array_map( 'html_entity_decode', $db_options ) : array();
			$this->options = wp_parse_args( $db_options, $this->default_global_settings() );

			return $this->options;
		}

		public function default_global_settings() {
			return array(
				'css'    => '',
				'script' => '',
			);
		}

		public function default_custom_settings() {
			return array(
				'custom_css' => '',
				'custom_js'  => '',
			);
		}

		/**
		 * Copy data from old landing page to new landing page
		 *
		 * @param $landing_page_id
		 *
		 * @return int|WP_Error
		 */
		public function duplicate_landing_page( $landing_page_id ) {

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

			if ( $landing_page_id > 0 ) {
				$landing_page = get_post( $landing_page_id );
				if ( ! is_null( $landing_page ) && ( $landing_page->post_type === $this->get_post_type_slug() || in_array( $landing_page->post_type, $this->get_inherit_supported_post_type(), true ) ) ) {

					$suffix_text = ' - ' . __( 'Copy', 'funnel-builder' );
					if ( did_action( 'wffn_duplicate_funnel' ) > 0 ) {
						$suffix_text = '';
					}

					$args         = [
						'post_title'   => $landing_page->post_title . $suffix_text,
						'post_content' => $landing_page->post_content,
						'post_name'    => sanitize_title( $landing_page->post_title . $suffix_text ),
						'post_type'    => $this->get_post_type_slug(),
					];
					$duplicate_id = wp_insert_post( $args );
					if ( ! is_wp_error( $duplicate_id ) ) {

						global $wpdb;

						$post_meta_all = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$landing_page_id" ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

						if ( ! empty( $post_meta_all ) ) {
							$sql_query_selects = [];

							if ( in_array( $landing_page->post_type, $this->get_inherit_supported_post_type(), true ) ) {
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
										$meta_value = ( strpos( $meta_value, 'cartflows' ) !== false ) ? str_replace( 'cartflows', "wflp", $meta_value ) : $meta_value;
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

							if ( in_array( $landing_page->post_type, $this->get_inherit_supported_post_type(), true ) ) {
								$template = WFFN_Core()->admin->get_selected_template( $landing_page_id, $post_meta_all );
								update_post_meta( $duplicate_id, '_wflp_selected_design', $template );
							}
							do_action( 'wffn_step_duplicated', $duplicate_id );

							return $duplicate_id;
						}

						if ( in_array( $landing_page->post_type, $this->get_inherit_supported_post_type(), true ) ) {
							$template = WFFN_Core()->admin->get_selected_template( $landing_page_id, $post_meta_all );
							update_post_meta( $duplicate_id, '_wflp_selected_design', $template );
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
		public function get_landing_pages( $term = '' ) {

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

		public function get_page_design( $page_id ) {
			$design_data = get_post_meta( $page_id, '_wflp_selected_design', true );
			if ( empty( $design_data ) ) {
				return $this->default_design_data();
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


		public function registered_page_templates( $templates ) {

			$all_templates = wp_get_theme()->get_post_templates();
			$path          = [

				'wflp-boxed.php'  => __( 'FunnelKit Boxed', 'funnel-builder' ),
				'wflp-canvas.php' => __( 'FunnelKit Canvas for Page Builder', 'funnel-builder' )
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
				$template = $this->get_template_url( $template );
			}

			return $template;
		}

		public function get_template_url( $main_template ) {
			global $post;
			$wflp_id       = $post->ID;
			$page_template = apply_filters( 'bwf_page_template', get_post_meta( $wflp_id, '_wp_page_template', true ), $wflp_id );

			$file         = '';
			$body_classes = [];

			switch ( $page_template ) {
				case 'wflp-boxed.php':
					$file           = $this->get_module_path() . 'templates/wflp-boxed.php';
					$body_classes[] = $page_template;
					break;

				case 'wflp-canvas.php':
					$file           = $this->get_module_path() . 'templates/wflp-canvas.php';
					$body_classes[] = $page_template;
					break;

				default:
					/**
					 * Remove Next/Prev Navigation
					 */ add_filter( 'next_post_link', '__return_empty_string' );
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

		public function get_module_path() {
			return plugin_dir_path( WFFN_PLUGIN_FILE ) . 'modules/landing-pages/';
		}


		public function load_compatibility() {
			include_once $this->get_module_path() . 'compatibilities/page-builders/gutenberg/class-wffn-landing-pages-gutenberg.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			include_once $this->get_module_path() . 'compatibilities/page-builders/elementor/class-wffn-landing-pages-elementor.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			include_once $this->get_module_path() . 'compatibilities/page-builders/divi/class-wffn-landing-pages-divi.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			include_once $this->get_module_path() . 'compatibilities/page-builders/oxygen/class-wffn-landing-pages-oxygen.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
		}

		public function get_edit_id() {
			return $this->edit_id;
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



		public function update_edit_url() {
			check_admin_referer( 'wffn_lp_update_edit_url', '_nonce' );

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

		public function update_options( $options ) {
			update_option( 'wffn_lp_settings', $options, true );
		}

		/**
		 * Save selected design template against checkout page
		 */

		public function save_design() {
			$resp = array(
				'msg'    => '',
				'status' => false,
			);
			check_ajax_referer( 'wffn_lp_save_design', '_nonce' );
			$wflp_id = isset( $_POST['wflp_id'] ) ? absint( wffn_clean( $_POST['wflp_id'] ) ) : 0;

			if ( $wflp_id > 0 ) {
				$selected_type = isset( $_POST['selected_type'] ) ? wffn_clean( $_POST['selected_type'] ) : '';
				$this->update_page_design( $wflp_id, [
					'selected'      => isset( $_POST['selected'] ) ? sanitize_text_field( $_POST['selected'] ) : '',
					'selected_type' => $selected_type
				] );
				do_action( 'wffn_design_saved', $wflp_id, $selected_type, 'landing' );
				do_action( 'wflp_page_design_updated', $wflp_id, $selected_type );
				$resp = array(
					'msg'    => __( 'Design Saved Successfully', 'funnel-builder' ),
					'status' => true,
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
			update_post_meta( $page_id, '_wflp_selected_design', $data );

			if ( isset( $data['selected_type'] ) && 'wp_editor' === $data['selected_type'] ) {
				update_post_meta( $page_id, '_wp_page_template', 'wflp-boxed.php' );
			} else {
				update_post_meta( $page_id, '_wp_page_template', 'wflp-canvas.php' );
			}

			return $data;
		}

		public static function send_resp( $data = array() ) {
			if ( ! is_array( $data ) ) {
				$data = [];
			}
			$data['nonce'] = wp_create_nonce( 'wflp_secure_key' );
			wp_send_json( $data );
		}

		public function remove_design() {
			$resp = array(
				'msg'    => '',
				'status' => false,
			);
			check_ajax_referer( 'wffn_lp_remove_design', '_nonce' );
			if ( isset( $_POST['wflp_id'] ) && $_POST['wflp_id'] > 0 ) {
				$wflp_id                     = absint( $_POST['wflp_id'] );
				$template                    = $this->default_design_data();
				$template['template_active'] = 'no';
				$this->update_page_design( $wflp_id, $template );
				do_action( 'wflp_template_removed', $wflp_id );
				do_action( 'woofunnels_module_template_removed', $wflp_id );

				$args = [
					'ID'           => $wflp_id,
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
			$resp = [
				'status' => false,
				'msg'    => __( 'Importing of template failed', 'funnel-builder' ),
			];
			check_ajax_referer( 'wffn_lp_import_design', '_nonce' );
			$builder  = isset( $_POST['builder'] ) ? sanitize_text_field( $_POST['builder'] ) : '';
			$template = isset( $_POST['template'] ) ? sanitize_text_field( $_POST['template'] ) : '';
			$wflp_id  = isset( $_POST['wflp_id'] ) ? sanitize_text_field( $_POST['wflp_id'] ) : '';


			$result = WFFN_Core()->importer->import_remote( $wflp_id, $builder, $template, $this->get_cloud_template_step_slug() );


			if ( true === $result['success'] ) {
				$resp['status'] = true;
				$resp['msg']    = __( 'Importing of template finished', 'funnel-builder' );
			} else {
				$resp['error'] = $result['error'];
			}

			self::send_resp( $resp );
		}

		public function toggle_state() {
			check_ajax_referer( 'wffn_lp_toggle_state', '_nonce' );
			$resp = [
				'status' => false,
				'msg'    => __( 'Unable to change state', 'funnel-builder' ),
			];

			$state   = isset( $_POST['toggle_state'] ) ? sanitize_text_field( $_POST['toggle_state'] ) : '';
			$wflp_id = isset( $_POST['wflp_id'] ) ? sanitize_text_field( $_POST['wflp_id'] ) : '';

			$status = ( 'true' === $state ) ? 'publish' : 'draft';

			wp_update_post( [ 'ID' => $wflp_id, 'post_status' => $status ] );

			$resp['status'] = true;
			$resp['msg']    = __( 'Status changed successfully', 'funnel-builder' );


			self::send_resp( $resp );
		}

		public function get_cloud_template_step_slug() {
			return 'landing';
		}
		public function get_status() {
			$post_lp = get_post( $this->get_edit_id() );

			return $post_lp->post_status;
		}

		public function get_module_title( $plural = false ) {
			return ( $plural ) ? __( 'Sales', 'funnel-builder' ) : __( 'Sales Page', 'funnel-builder' );
		}

		public function set_page_template( $wflp_id, $module ) {
			if ( $this->get_cloud_template_step_slug() !== $module ) {
				return;
			}
			update_post_meta( $wflp_id, '_wp_page_template', 'wflp-boxed.php' );
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

			if ( isset( $post->post_type ) && $this->get_post_type_slug() === $post->post_type && empty( trim( $bwb_admin_setting->get_option( 'landing_page_base' ) ) ) ) {


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
		function add_cpt_post_names_to_main_query( $query ) {

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


		public function wffn_add_unique_class( $classes ) {
			array_push( $classes, 'wffn-page-template' );

			return $classes;
		}

		public function get_inherit_supported_post_type() {
			return apply_filters( 'wffn_sp_inherit_supported_post_type', array( 'cartflows_step', 'page' ) );
		}

		public function add_global_settings_fields( $fields ) {
			$fields["lp-settings"] = $this->all_global_settings_fields();

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
				)
			);
			foreach ( $array as &$arr ) {
				$values = [];
				foreach ( $arr['fields'] as &$field ) {
					$values[ $field['key'] ] = WFFN_Core()->landing_pages->get_option( $field['key'] );
				}
				$arr['values'] = $values;
			}

			return $array;
		}

		public function add_permalink_settings( $fields ) {

			$fields['landing_page_base'] = array(
				'label'     => __( 'Sales Page', 'funnel-builder' ),
				'hint'      => '',
				'type'      => 'input',
				'inputType' => 'text',
			);

			return $fields;
		}

		public function get_settings_tab_data( $values = null ) {

			$tabs = [
				'custom_css' => [
					'title'    => __( 'Custom CSS', 'funnel-builder' ),
					'heading'  => __( 'Custom CSS', 'funnel-builder' ),
					'slug'     => 'custom_css',
					'fields'   => [
						0 => [
							'key'         => 'custom_css',
							'type'        => 'textArea',
							'label'       => __( 'Custom CSS Tweaks', 'funnel-builder' ),
							'placeholder' => __( 'Paste your CSS Code here', 'funnel-builder' ),
							'className'   => 'bwf-textarea-lg-resizable',
						],
					],
					'priority' => 5,
					'values'   => [
						'custom_css' => '',
					],
				],
				'custom_js'  => [
					'title'    => __( 'External Scripts', 'funnel-builder' ),
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
					'priority' => 10,
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
					$tabs['custom_js']['values']['custom_js'] = html_entity_decode($values['custom_js']);
				}
			}

			return $tabs;
		}


	}

	if ( class_exists( 'WFFN_Core' ) ) {
		WFFN_Core::register( 'landing_pages', 'WFFN_Landing_Pages' );
	}
}
