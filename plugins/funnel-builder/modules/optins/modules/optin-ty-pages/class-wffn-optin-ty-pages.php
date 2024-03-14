<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff

defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Funnel thank you optin page module
 * Class WFFN_Optin_TY_Pages
 */
if ( ! class_exists( 'WFFN_Optin_TY_Pages' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Optin_TY_Pages extends WFFN_Module_Common {

		private static $ins = null;

		/**
		 * @var WFOTY_Admin|null
		 */
		public $admin;
		protected $options;
		protected $custom_options;
		protected $template_type = [];
		protected $design_template_data = [];
		protected $templates = [];
		protected $wffn_is_oty = false;
		public $edit_id = 0;
		private $url = '';

		public $op_thankyoupage_id = 0;

		/**
		 * WFFN_Optin_TY_Pages constructor.
		 */
		public function __construct() {
			parent::__construct();
			$this->url = plugin_dir_url( __FILE__ );


			include_once __DIR__ . '/class-wfoty-admin.php';
			$this->admin = WFOTY_Admin::get_instance();
			add_action( 'init', array( $this, 'register_post_type' ), 5 );
			add_action( 'init', array( $this, 'load_compatibility' ), 2 );
			add_filter( 'template_include', [ $this, 'may_be_change_template' ], 99 );
			add_action( 'wp', array( $this, 'maybe_check_for_custom_page' ), 1 );
			add_action( 'wp', array( $this, 'set_id' ), 2 );
			$post_type = $this->get_post_type_slug();
			add_filter( "theme_{$post_type}_templates", [ $this, 'registered_page_templates' ], 99, 4 );
			add_action( 'wp', array( $this, 'parse_request_for_oty' ), - 1 );



			add_action( 'wp_enqueue_scripts', array( $this, 'oty_page_frontend_scripts' ), 21 );
			add_action( 'wffn_import_completed', array( $this, 'set_page_template' ), 10, 2 );


			add_filter( 'post_type_link', array( $this, 'post_type_permalinks' ), 10, 2 );
			add_action( 'pre_get_posts', array( $this, 'add_cpt_post_names_to_main_query' ), 20 );


		}


		public function get_post_type_slug() {
			return 'wffn_oty';
		}

		/**
		 * @return WFFN_Optin_TY_Pages|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
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

		public function register_post_type() {
			/**
			 * Thank You optin Post Type
			 */
			$bwb_admin_setting = BWF_Admin_General_Settings::get_instance();

			register_post_type( $this->get_post_type_slug(), apply_filters( 'wffn_oty_post_type_args', array(
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
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => true,
				'rewrite'             => array(
					'slug'       => ( empty( $bwb_admin_setting->get_option( 'optin_ty_page_base' ) ) ? $this->get_post_type_slug() : $bwb_admin_setting->get_option( 'optin_ty_page_base' ) ),
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

		public function oty_page_frontend_scripts() {
			$funnel    = WFFN_Core()->data->get_session_funnel();
			$funnel_id = '';
			if ( wffn_is_valid_funnel( $funnel ) ) {
				$funnel_id = 'funnel id: #' . $funnel->id . ', ';
			}

			if ( $this->is_wfoty_page() ) {
				global $post;
				$page_template = ( $post instanceof WP_Post ) ? get_post_meta( $post->ID, '_wp_page_template', true ) : '';
				if ( 'default' === $page_template || empty( $page_template ) ) {
					return;
				}
				WFFN_Core()->logger->log( $funnel_id . 'Thank You optin id: #' . $post->ID . ' Thank You optin page load scripts' );
				wp_enqueue_style( 'wffn-frontend-style' );
			}
		}

		/**
		 * Checks whether its our page or not
		 * @return bool
		 */
		public function is_wfoty_page() {
			return $this->wffn_is_oty;
		}

		/**
		 * Set wfty_is_thankyou flag if it's our page
		 * @return void
		 */
		public function parse_request_for_oty() {
			global $post;
			if ( is_null( $post ) || ! $post instanceof WP_Post ) {
				return;
			}

			$funnel = WFFN_Core()->data->get_session_funnel();
			if ( ! is_singular( $post->post_type ) || ( $post->post_type !== $this->get_post_type_slug() ) ) {
				return;
			}

			/** Log if valid funnel */
			if ( wffn_is_valid_funnel( $funnel ) ) {
				WFFN_Core()->logger->log( "Funnel id: #" . $funnel->get_id() . " parse request for optin thankyou page" );
			}

			$this->wffn_is_oty = true;

			$data = get_post_meta( $post->ID, 'wffn_step_custom_settings', true );

			if ( WFFN_Common::is_page_builder_preview() || ! isset( $data['custom_redirect'] ) || ( 'true' !== $data['custom_redirect'] ) ) {
				return;
			}
			if ( ! isset( $data['custom_redirect_page'] ) || ! is_array( $data['custom_redirect_page'] ) || ! isset( $data['custom_redirect_page']['id'] ) ) {
				return;
			}

			$custom_page = get_post( $data['custom_redirect_page']['id'] );
			if ( is_null( $custom_page ) || ! $custom_page instanceof WP_Post || 'publish' !== $custom_page->post_status ) {
				return;
			}

			/** Valid page found, redirect */
			/* Redirect Only if current page and custom page id are different */
			if ( get_the_ID() !== absint( $custom_page->ID ) && wffn_is_valid_funnel( $funnel ) ) {
				wp_safe_redirect( get_permalink( $custom_page->ID ) );
				exit;
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
			$ty_options = array();
			$db_options = get_option( 'wffn_op_settings', [] );
			$db_options = ( ! empty( $db_options ) && is_array( $db_options ) ) ? array_map( 'html_entity_decode', $db_options ) : array();

			$ty_options['css']    = isset( $db_options['ty_css'] ) ? $db_options['ty_css'] : '';
			$ty_options['script'] = isset( $db_options['ty_script'] ) ? $db_options['ty_script'] : '';

			$this->options = $ty_options;

			return $this->options;
		}

		public function default_custom_settings() {
			return array(
				'custom_css'      => '',
				'custom_js'       => '',
				'custom_redirect' => 'false',
			);
		}

		/**
		 * Copy data from old oty page to new oty page
		 *
		 * @param $oty_page_id
		 *
		 * @return int|WP_Error
		 */
		public function duplicate_oty_page( $oty_page_id ) {

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

			if ( $oty_page_id > 0 ) {
				$oty_page = get_post( $oty_page_id );
				if ( ! is_null( $oty_page ) && ( $oty_page->post_type === $this->get_post_type_slug() || in_array( $oty_page->post_type, $this->get_inherit_supported_post_type(), true ) ) ) {

					$suffix_text = ' - ' . __( 'Copy', 'funnel-builder' );
					if ( did_action( 'wffn_duplicate_funnel' ) > 0 ) {
						$suffix_text = '';
					}

					$args         = [
						'post_title'   => $oty_page->post_title . $suffix_text,
						'post_content' => $oty_page->post_content,
						'post_name'    => sanitize_title( $oty_page->post_title . $suffix_text ),
						'post_type'    => $this->get_post_type_slug(),
					];
					$duplicate_id = wp_insert_post( $args );
					if ( ! is_wp_error( $duplicate_id ) ) {

						global $wpdb;

						$post_meta_all = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$oty_page_id" ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

						if ( ! empty( $post_meta_all ) ) {
							$sql_query_selects = [];

							if ( in_array( $oty_page->post_type, $this->get_inherit_supported_post_type(), true ) ) {

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
										$meta_value = ( strpos( $meta_value, 'cartflows' ) !== false ) ? str_replace( 'cartflows', "wfoty", $meta_value ) : $meta_value;
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

									$sql_query_selects[] = "($duplicate_id, '$meta_key', '$meta_value')"; //db call ok; no-cache ok; WPCS: unprepared SQL ok.

								}
							}

							$sql_query_meta_val = implode( ',', $sql_query_selects );
							$wpdb->query( $wpdb->prepare( 'INSERT INTO %1$s (post_id, meta_key, meta_value) VALUES ' . $sql_query_meta_val, $wpdb->postmeta ) );//phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder,WordPress.DB.PreparedSQL.NotPrepared


							if ( in_array( $oty_page->post_type, $this->get_inherit_supported_post_type(), true ) ) {
								$template = WFFN_Core()->admin->get_selected_template( $oty_page_id, $post_meta_all );
								update_post_meta( $duplicate_id, '_wfoty_selected_design', $template );
							}
							do_action( 'wffn_step_duplicated', $duplicate_id );

							return $duplicate_id;
						}

						if ( in_array( $oty_page->post_type, $this->get_inherit_supported_post_type(), true ) ) {
							$template = WFFN_Core()->admin->get_selected_template( $oty_page_id, $post_meta_all );
							update_post_meta( $duplicate_id, '_wfoty_selected_design', $template );
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
		public function get_oty_pages( $term ) {
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
			$design_data = get_post_meta( $page_id, '_wfoty_selected_design', true );
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

				'wfoty-boxed.php'  => __( 'FunnelKit Boxed', 'funnel-builder' ),
				'wfoty-canvas.php' => __( 'FunnelKit Canvas for Page Builder', 'funnel-builder' )
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
			$oty_id        = $post->ID;
			$page_template = apply_filters( 'bwf_page_template', get_post_meta( $oty_id, '_wp_page_template', true ), $oty_id );

			$file         = '';
			$body_classes = [];

			switch ( $page_template ) {
				case 'wfoty-boxed.php':
					$file           = $this->get_module_path() . 'templates/wfoty-boxed.php';
					$body_classes[] = $page_template;
					break;

				case 'wfoty-canvas.php':
					$file           = $this->get_module_path() . 'templates/wfoty-canvas.php';
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
			return plugin_dir_path( WFOPP_PLUGIN_FILE ) . 'modules/optin-ty-pages/';
		}


		public function load_compatibility() {

			include_once $this->get_module_path() . 'compatibilities/page-builders/elementor/class-wffn-oty-pages-elementor.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			include_once $this->get_module_path() . 'compatibilities/page-builders/divi/class-wffn-oty-pages-divi.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			include_once $this->get_module_path() . 'compatibilities/page-builders/oxygen/class-wffn-oty-pages-oxygen.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			include_once $this->get_module_path() . 'compatibilities/page-builders/gutenberg/class-wffn-oty-pages-gutenberg.php'; //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable

		}

		public function get_edit_id() {
			return $this->edit_id;
		}

		public function update_page_design( $page_id, $data ) {
			if ( $page_id < 1 ) {
				return $data;
			}
			if ( ! is_array( $data ) ) {
				$data = $this->default_design_data();
			}
			update_post_meta( $page_id, '_wfoty_selected_design', $data );

			if ( isset( $data['selected_type'] ) && 'wp_editor' === $data['selected_type'] ) {
				update_post_meta( $page_id, '_wp_page_template', 'wfoty-boxed.php' );
			} else {
				update_post_meta( $page_id, '_wp_page_template', 'wfoty-canvas.php' );
			}

			return $data;
		}

		public static function send_resp( $data = array() ) {
			if ( ! is_array( $data ) ) {
				$data = [];
			}
			$data['nonce'] = wp_create_nonce( 'wfoty_secure_key' );
			wp_send_json( $data );
		}



		public function get_cloud_template_step_slug() {
			return 'optin_ty';
		}

		public function get_status() {
			$post_oty = get_post( $this->get_edit_id() );

			return $post_oty->post_status;
		}

		public function get_module_title( $plural = false ) {
			return ( $plural ) ? __( 'Optin Confirmation Pages', 'funnel-builder' ) : __( 'Optin Confirmation Page', 'funnel-builder' );
		}

		public function set_page_template( $oty_id, $module ) {
			if ( $this->get_cloud_template_step_slug() !== $module ) {
				return;
			}
			update_post_meta( $oty_id, '_wp_page_template', 'wfoty-boxed.php' );
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
		public function post_type_permalinks( $post_link, $post ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter

			$bwb_admin_setting = BWF_Admin_General_Settings::get_instance();

			if ( isset( $post->post_type ) && $this->get_post_type_slug() === $post->post_type && empty( trim( $bwb_admin_setting->get_option( 'optin_ty_page_base' ) ) ) ) {

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

			// Add thank you optin page step post type to existing post type array.
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
			return apply_filters( 'wffn_oty_inherit_supported_post_type', array( 'cartflows_step', 'page' ) );
		}

		public function set_id() {
			if ( $this->is_wfoty_page() && empty( $this->op_thankyoupage_id ) ) {
				global $post;
				$this->op_thankyoupage_id = $post->ID;
			}
		}

		public function maybe_check_for_custom_page() {

			global $post;
			$maybe_wfoty_id = filter_input( INPUT_GET, 'wfoty_source', FILTER_SANITIZE_NUMBER_INT );
			if ( empty( $maybe_wfoty_id ) ) {
				return;
			}
			if ( empty( $post ) ) {
				return;
			}

			$this->op_thankyoupage_id = $maybe_wfoty_id;
			$this->wffn_is_oty        = true;

		}



		public function get_settings_tab_data( $values ) {

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
							'hint'        => '',
							'hintLabel'   => __( 'Enter minimum 3 letters.', 'funnel-builder' ),
							'apiEndPoint' => '/funnels/pages/search?page=optin_ty',
							'label'       => __( 'Select Page', 'funnel-builder' ),
							'toggler'     => [
								'key'   => 'custom_redirect',
								'value' => 'true',
							],
							'values'      => ! empty( $values['custom_redirect_page'] ) ? wffn_clean( $values['custom_redirect_page'] ) : '',
							'required'    => true,
						],
					],
					'priority' => 10,
					'values'   => [],
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
							'placeholder' => __( 'Paste your CSS Code here', 'funnel-builder' ),
							'className'   => 'bwf-textarea-lg-resizable',
						],
					],
					'priority' => 15,
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
					'priority' => 20,
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

				if ( isset( $values['custom_redirect'] ) ) {

					$custom_redirect = [
						'custom_redirect'      => ! empty( $values['custom_redirect'] ) ? wffn_clean( $values['custom_redirect'] ) : '',
						'pages'                => ! empty( $values['pages'] ) ? wffn_clean( $values['pages'] ) : '',
						'select_redirect_page' => ! empty( $values['custom_redirect_page'] ) ? wffn_clean( $values['custom_redirect_page'] ) : '',
						'not_found'            => ! empty( $values['not_found'] ) ? wffn_clean( $values['not_found'] ) : __( 'Oops! No elements found. Consider changing the search query.', 'funnel-builder' ),
					];

					$tabs['custom_redirect']['values'] = wffn_clean( $custom_redirect );

				}

				if ( ! empty( $values['op_valid_enable'] ) ) {
					$op_fields = [
						'op_valid_enable' => $values['op_valid_enable'],
						'op_valid_text'   => $values['op_valid_text'],
						'op_valid_email'  => $values['op_valid_email'],
					];

					$tabs['validation']['values'] = wffn_clean( $op_fields );
				}

			}

			return $tabs;
		}

	}

	if ( class_exists( 'WFOPP_Core' ) ) {
		WFOPP_Core::register( 'optin_ty_pages', 'WFFN_Optin_TY_Pages' );
	}
}
