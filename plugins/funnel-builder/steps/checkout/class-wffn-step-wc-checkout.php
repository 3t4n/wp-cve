<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class contains all the Aero related funnel functionality
 * Class WFFN_Step_Aero_Checkout
 */
if ( ! class_exists( 'WFFN_Step_WC_Checkout' ) ) {
	#[AllowDynamicProperties]
	class WFFN_Step_WC_Checkout extends WFFN_Step {

		private static $ins = null;
		public $slug = 'wc_checkout';
		public $substeps = [ 'wc_order_bump' ];
		public $list_priority = 20;

		/**
		 * WFFN_Step_WC_Checkout constructor.
		 */
		public function __construct() {
			parent::__construct();
			add_action( 'wfacp_listing_handle_query_args', [ $this, 'exclude_from_query' ] );
			add_action( 'woocommerce_checkout_order_processed', [ $this, 'maybe_record_orders_in_session' ], 11 );
			add_filter( 'maybe_setup_funnel_for_breadcrumb', [ $this, 'maybe_funnel_breadcrumb' ] );
			add_filter( 'wfacp_fb_pixel_ids', array( $this, 'override_pixel_key' ) );
			add_filter( 'wfacp_get_ga_key', array( $this, 'override_ga_key' ) );
			add_filter( 'wfacp_get_gad_key', array( $this, 'override_gad_key' ) );
			add_filter( 'wfacp_pinterest_key', array( $this, 'override_pint_key' ) );
			add_filter( 'wfacp_tiktok_key', array( $this, 'override_tiktok_key' ) );
			add_filter( 'wfacp_snapchat_pixel_key', array( $this, 'override_snapchat_key' ) );
			add_filter( 'wfacp_conversion_api_access_token', array( $this, 'override_conversion_api_access_token' ) );
			add_filter( 'wfacp_conversion_api_test_event_code', array( $this, 'override_conversion_api_test_event_code' ) );
			add_filter( 'wffn_funnel_environment', array( $this, 'maybe_override_environment_for_global' ) );

			add_action( 'wffn_load_api_export_class', [ $this, 'load_api_export_class' ], 999 );
			add_action( 'wffn_load_api_import_class', [ $this, 'load_api_import_class' ], 999 );
			add_action( 'wfacp_update_order_report_review', [ $this, 'update_pending_conversions' ] );
			add_filter( 'wffn_rest_get_templates', array( $this, 'alter_templates' ) );
			add_filter( 'wfacp_update_report_views', array( $this, 'maybe_already_recoded_views' ), 10, 2 );
			add_filter( 'wfacp_global_checkout_page_id', array( $this, 'maybe_override_global_checkout_id' ), 8, 1 );
			add_action( 'woocommerce_checkout_update_order_review', array( $this, 'setup_funnel_on_update_order' ), 99, 1 );

		}

		/**
		 * @return WFFN_Step_WC_Checkout|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		/**
		 * @return array|void
		 */
		public function get_supports() {
			return array_unique( array_merge( parent::get_supports(), [ 'open_link', 'next_link' ] ) );
		}

		/**
		 * @param $steps
		 *
		 * @return array
		 */
		public function get_step_data() {
			$substpes = WFFN_Core()->substeps->get_supported_substeps();
			$substpes = array_intersect( array_keys( $substpes ), $this->substeps );

			return array(
				'type'        => $this->slug,
				'title'       => $this->get_title(),
				'popup_title' => sprintf( __( 'Add %s', 'funnel-builder' ), $this->get_title() ),
				'dashicons'   => 'dashicons-cart',
				'icon'        => 'cart',
				'label'       => __( 'No Products', 'funnel-builder' ),
				'label_class' => 'bwf-st-c-badge-red',
				'substeps'    => $substpes,
			);
		}

		/**
		 * Return title of Checkout step
		 */
		public function get_title() {
			return __( 'Checkout Page', 'funnel-builder' );
		}


		public function get_step_designs( $term, $funnel_id = 0 ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
			$active_pages    = $this->get_checkout_pages( $term );
			$inside_funnels  = [];
			$outside_funnels = [];
			foreach ( $active_pages as $active_page ) {
				$post_type     = get_post_type( $active_page->ID );
				$bwf_funnel_id = get_post_meta( $active_page->ID, '_bwf_in_funnel', true );
				$data          = [];
				if ( 'cartflows_step' === $post_type ) {
					$meta = get_post_meta( $active_page->ID, 'wcf-step-type', true );
					if ( 'checkout' === $meta ) {
						$data = array(
							'id'   => $active_page->ID,
							'name' => $active_page->post_title,
						);
					}
				} else {
					$data = array(
						'id'   => $active_page->ID,
						'name' => $active_page->post_title,
					);
				}

				if ( empty( $data ) ) {
					continue;
				}

				$funnel = new WFFN_Funnel( $bwf_funnel_id );
				if ( absint( $bwf_funnel_id ) > 0 && ! empty( $funnel->get_title() ) ) {
					if ( ! isset( $inside_funnels[ $bwf_funnel_id ] ) ) {
						$inside_funnels[ $bwf_funnel_id ] = [ 'name' => $funnel->get_title(), 'id' => $bwf_funnel_id, "steps" => [] ];
					}
					$inside_funnels[ $bwf_funnel_id ]['steps'][] = $data;
				} else {
					$outside_funnels[] = $data;
				}


			}
			if ( ! empty( $outside_funnels ) ) {
				$outside_funnels = [ [ 'name' => __( 'Other Pages', 'funnel-builder' ), 'id' => 0, 'steps' => $outside_funnels ] ];
			}

			return array_merge( $inside_funnels, $outside_funnels );
		}

		public function get_checkout_pages( $term ) {
			$args = array(
				'post_type'   => array( WFACP_Common::get_post_type_slug(), 'cartflows_step', 'page' ),
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

		/**
		 * @param $funnel_id
		 * @param $type
		 * @param $posted_data
		 *
		 * @return stdClass
		 */
		public function add_step( $funnel_id, $posted_data ) {
			$title = isset( $posted_data['title'] ) ? $posted_data['title'] : '';

			$step_id = wp_insert_post( array(
				'post_type'   => WFACP_Common::get_post_type_slug(),
				'post_title'  => $title,
				'post_name'   => sanitize_title( $title ),
				'post_status' => 'publish'

			) );
			if ( ! $step_id instanceof WP_Error ) {
				update_post_meta( $step_id, '_wfacp_version', WFACP_VERSION );
				update_post_meta( $step_id, '_wfacp_created_by', 'funnel-builder' );
			}
			$posted_data['id'] = ( $step_id > 0 ) ? $step_id : 0;

			return parent::add_step( $funnel_id, $posted_data );
		}

		/**
		 * @param $funnel_id
		 * @param $step_id
		 * @param $type
		 * @param $posted_data
		 *
		 * @return stdClass
		 */
		public function duplicate_step( $funnel_id, $step_id, $posted_data ) {

			$duplicate_id      = $this->duplicate_checkout_page( $step_id );
			$posted_data['id'] = ( $duplicate_id > 0 ) ? $duplicate_id : 0;

			$post_status = ( isset( $posted_data['original_id'] ) && $posted_data['original_id'] > 0 ) ? get_post_status( $posted_data['original_id'] ) : 'publish';

			if ( $duplicate_id > 0 ) {
				$posted_data['id'] = $duplicate_id;
				$new_title         = isset( $posted_data['title'] ) ? $posted_data['title'] : '';
				$arr               = [ 'ID' => $duplicate_id, 'post_status' => $post_status ];

				if ( ! empty( $new_title ) ) {
					$arr['post_title'] = $new_title;
				}
				wp_update_post( $arr );
			}

			$status = parent::duplicate_step( $funnel_id, $step_id, $posted_data );


			if ( isset( $posted_data['id'] ) && isset( $posted_data['_data']['desc'] ) ) {
				$post               = get_post( $posted_data['id'] );
				$post->post_content = $posted_data['_data']['desc'];
				wp_update_post( $post );
			}
			do_action( 'wffn_checkout_duplicate_step' );

			return $status;
		}

		/**
		 * Copy data from old checkout page to new checkout page
		 *
		 * @param $checkout_page_id
		 *
		 * @return int|WP_Error
		 */
		public function duplicate_checkout_page( $checkout_page_id ) {

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

			if ( $checkout_page_id > 0 ) {
				$checkout_page = get_post( $checkout_page_id );

				if ( ! is_null( $checkout_page ) && ( $checkout_page->post_type === WFACP_Common::get_post_type_slug() || in_array( $checkout_page->post_type, $this->get_inherit_supported_post_type(), true ) ) ) {

					$suffix_text = ' - ' . __( 'Copy', 'funnel-builder' );
					if ( did_action( 'wffn_duplicate_funnel' ) > 0 ) {
						$suffix_text = '';
					}

					$args         = [
						'post_title'   => $checkout_page->post_title . $suffix_text,
						'post_content' => $checkout_page->post_content,
						'post_name'    => sanitize_title( $checkout_page->post_title . $suffix_text ),
						'post_type'    => WFACP_Common::get_post_type_slug(),
					];
					$duplicate_id = wp_insert_post( $args );
					if ( ! is_wp_error( $duplicate_id ) ) {

						global $wpdb;

						$post_meta_all = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$checkout_page_id" ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

						if ( ! empty( $post_meta_all ) ) {
							$sql_query_selects = [];

							if ( in_array( $checkout_page->post_type, $this->get_inherit_supported_post_type(), true ) ) {

								foreach ( $post_meta_all as $meta_info ) {


									$meta_key   = $meta_info->meta_key;
									$meta_value = $meta_info->meta_value;
									if ( '_wfacp_selected_products' === $meta_key ) {
										$meta_value = $this->map_checkout_product( $meta_value );
									}

									if ( ! in_array( $meta_key, $exclude_metas, true ) ) {
										if ( strpos( $meta_key, 'wcf-' ) === false ) {

											if ( $meta_key === '_wp_page_template' ) {
												$meta_value = ( strpos( $meta_value, 'cartflows' ) !== false ) ? str_replace( 'cartflows', "wfacp", $meta_value ) : $meta_value;
											}
											$meta_key   = esc_sql( $meta_key );
											$meta_value = esc_sql( $meta_value );

											$sql_query_selects[] = "($duplicate_id, '$meta_key', '$meta_value')";//db call ok; no-cache ok; WPCS: unprepared SQL ok.
										}
									}
								}
							} else {
								update_option( WFACP_SLUG . '_c_' . $duplicate_id, get_option( WFACP_SLUG . '_c_' . $checkout_page_id, [] ), 'no' );
								foreach ( $post_meta_all as $meta_info ) {

									$meta_key = $meta_info->meta_key;

									if ( $meta_key === '_bwf_ab_variation_of' ) {
										continue;
									}
									if ( '_wfacp_selected_products' === $meta_key ) {
										$meta_info->meta_value = $this->map_checkout_product( $meta_info->meta_value );
									}
									$meta_key   = esc_sql( $meta_key );
									$meta_value = esc_sql( $meta_info->meta_value );


									$sql_query_selects[] = "($duplicate_id, '$meta_key', '$meta_value')"; //db call ok; no-cache ok; WPCS: unprepared SQL ok.
								}
							}

							$sql_query_meta_val = implode( ',', $sql_query_selects );
							$wpdb->query( $wpdb->prepare( 'INSERT INTO %1$s (post_id, meta_key, meta_value) VALUES ' . $sql_query_meta_val, $wpdb->postmeta ) );//phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder,WordPress.DB.PreparedSQL.NotPrepared

							if ( in_array( $checkout_page->post_type, $this->get_inherit_supported_post_type(), true ) ) {
								$template = WFFN_Core()->admin->get_selected_template( $checkout_page_id, $post_meta_all );
								if ( isset( $template['selected_type'] ) && $template['selected_type'] === 'wp_editor' ) {
									$template = [
										'selected'      => 'embed_forms_4',
										'selected_type' => 'embed_forms',
									];
								}
								update_post_meta( $duplicate_id, '_wfacp_selected_design', $template );
							}
							do_action( 'wffn_step_duplicated', $duplicate_id );

							return $duplicate_id;

						}

						if ( in_array( $checkout_page->post_type, $this->get_inherit_supported_post_type(), true ) ) {
							$template = WFFN_Core()->admin->get_selected_template( $checkout_page_id, $post_meta_all );
							if ( isset( $template['selected_type'] ) && $template['selected_type'] === 'wp_editor' ) {
								$template = [
									'selected'      => 'embed_forms_4',
									'selected_type' => 'embed_forms',
								];
							}
							update_post_meta( $duplicate_id, '_wfacp_selected_design', $template );
						}
						do_action( 'wffn_step_duplicated', $duplicate_id );

						return $duplicate_id;
					}
				}
			}

			return 0;
		}

		/**
		 * @param $step_id
		 *
		 * @return mixed
		 */
		public function get_entity_edit_link( $step_id ) {
			$link = parent::get_entity_edit_link( $step_id );
			if ( $step_id > 0 && get_post( $step_id ) instanceof WP_Post ) {
				$link = esc_url( BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
					'page' => 'bwf',
					'path' => '/funnel-checkout/' . $step_id . '/design',
				], admin_url( 'admin.php' ) ) ) );
			}

			return $link;
		}

		/**
		 * @param $step_id
		 *
		 * @return array
		 */
		public function get_entity_tags( $step_id, $funnel_id ) {
			$wfacp_products = WFACP_Common::get_page_product( $step_id );

			$flags = [];

			if ( absint( $funnel_id ) !== WFFN_Common::get_store_checkout_id() ) {
				$product_count = count( $wfacp_products );
				if ( $product_count < 1 ) {
					$flags['no_product'] = array(
						'label'       => __( 'No Products', 'funnel-builder' ),
						'label_class' => 'bwf-st-c-badge-red',
						'edit'        => wffn_rest_api_helpers()->get_entity_url( 'checkout', 'products', $step_id )
					);
				}
			}

			$substeps = $this->get_substeps( $funnel_id, $step_id );
			if ( ! defined( 'WFOB_PLUGIN_BASENAME' ) || ( count( $substeps ) < 1 ) || ( count( $substeps ) > 0 && ! isset( $substeps['wc_order_bump'] ) ) || ( isset( $substeps['wc_order_bump'] ) && ! is_array( $substeps['wc_order_bump'] ) ) || ( isset( $substeps['wc_order_bump'] ) && is_array( $substeps['wc_order_bump'] ) && count( $substeps['wc_order_bump'] ) < 1 ) ) {
				$flags['no_bump'] = array(
					'label'       => __( 'No Order Bump', 'funnel-builder' ),
					'label_class' => 'bwf-st-c-badge-red',
				);

			}
			$options = get_option( '_wfacp_global_settings', [] );

			if ( isset( $options['override_checkout_page_id'] ) && ! empty( $options['override_checkout_page_id'] ) && absint( $step_id ) === absint( $options['override_checkout_page_id'] ) ) {
				$flags['global_checkout'] = array(
					'label'       => __( 'Global Checkout', 'funnel-builder' ),
					'label_class' => 'bwf-st-c-badge-yellow',
				);
			}


			return $flags;
		}


		/**
		 * @param $environment
		 *
		 * @return bool
		 */
		public function claim_environment( $environment ) {
			/**
			 * @todo we need to also take care of the embed forms here
			 */
			if ( 'wfacp_checkout' !== $environment['post_type'] ) {
				return false;
			}
			if ( $this->is_disabled( $this->get_entity_status( $environment['id'] ) ) ) {
				return false;
			}
			add_filter( 'wfacp_template_localize_data', array( $this, 'maybe_add_funnel_data' ) );

			return true;
		}

		/**
		 * @param $environment
		 *
		 * @return bool|WFFN_Funnel
		 */
		public function get_funnel_to_run( $environment ) {
			$get_checkout_page = $environment['id'];
			$get_funnel_id     = get_post_meta( $get_checkout_page, '_bwf_in_funnel', true );
			$get_funnel        = new WFFN_Funnel( $get_funnel_id );

			return $get_funnel;
		}

		/**
		 * Save Order ID in the session to use later while treating with thankyou step
		 *
		 * @param $order_id
		 */
		public function maybe_record_orders_in_session( $order_id ) {
			$order = wc_get_order( $order_id );

			if ( ! $order instanceof WC_Order ) {
				return;
			}
			$get_checkout_id = $order->get_meta( '_wfacp_post_id', true );
			if ( $get_checkout_id < 1 ) {
				$get_checkout_id = isset( $_POST['_wfacp_post_id'] ) ? $_POST['_wfacp_post_id'] : 0; //phpcs:ignore
			}
			$funnel       = WFFN_Core()->data->get_session_funnel();
			$current_step = WFFN_Core()->data->get_current_step();
			if ( WFFN_Core()->data->has_valid_session() && ! empty( $current_step ) && wffn_is_valid_funnel( $funnel ) ) {
				if ( absint( $current_step['id'] ) !== absint( $get_checkout_id ) ) {
					return;
				}
				WFFN_Core()->data->set( 'wc_order', $order_id )->save();
			}

		}


		public function _get_export_metadata( $step ) {
			$new_all_meta = WFACP_Core()->export->get_acp_array_for_json( $step['id'] );

			$new_all_meta = $this->maybe_have_substeps_export( $new_all_meta, $step );

			return $new_all_meta;
		}

		public function maybe_have_substeps_export( $new_all_meta, $step ) {
			$sub_steps = [];
			if ( isset( $step['substeps'] ) && ! empty( $step['substeps'] ) ) {
				foreach ( $step['substeps'] as $key => $substeps ) {
					$sub_steps[ $key ]  = [];
					$get_substep_object = WFFN_Core()->substeps->get_integration_object( $key );
					if ( ! empty( $get_substep_object ) ) {
						foreach ( $substeps as $substep ) {

							$sub_steps[ $key ][] = $get_substep_object->_get_export_metadata( $substep );

						}
					}
				}
			}
			$new_all_meta['substeps'] = $sub_steps;

			return $new_all_meta;
		}

		public function _process_import( $funnel_id, $step_data ) {
			$substeps = [];
			if ( isset( $step_data['meta']['substeps'] ) ) {
				$substeps = $step_data['meta']['substeps'];
				unset( $step_data['meta']['substeps'] );
			}
			$status = 'publish';

			$meta = [];
			if ( isset( $step_data['meta']['meta'] ) ) {
				$meta = $step_data['meta']['meta'];
			}

			$post_content = ( isset( $step_data['post_content'] ) && ! empty( $step_data['post_content'] ) ) ? $step_data['post_content'] : '';
			$args         = array( 'title' => $step_data['title'], 'post_status' => $status, 'post_content' => $post_content, 'meta' => $meta );
			if ( isset( $step_data['meta']['customizer_meta'] ) ) {
				$args['customizer_meta'] = $step_data['meta']['customizer_meta'];
			}
			$ids = WFACP_Core()->import->import_from_json_data( array( $args ) );


			$posted_data = [ 'title' => $step_data['title'], 'id' => $ids[0] ];
			parent::add_step( $funnel_id, $posted_data );
			if ( ! empty( $substeps ) ) {
				foreach ( $substeps as $key => $substep ) {
					$get_substep_object = WFFN_Core()->substeps->get_integration_object( $key );
					if ( ! empty( $get_substep_object ) ) {
						foreach ( $substep as $substep_single ) {
							$imported_substep_id = $get_substep_object->_process_import( $substep_single );
							$this->add_substep( $funnel_id, $ids[0], $key, array( 'id' => $imported_substep_id ) );

						}
					}
				}
			}
			if ( isset( $step_data['template'] ) && ! empty( $step_data['template'] ) ) {
				update_post_meta( $ids[0], '_tobe_import_template', $step_data['template'] );
				update_post_meta( $ids[0], '_tobe_import_template_type', $step_data['template_type'] );
			}
		}

		public function has_import_scheduled( $id ) {
			$template = get_post_meta( $id, '_tobe_import_template', true );
			if ( ! empty( $template ) ) {
				return array(
					'template'      => $template,
					'template_type' => get_post_meta( $id, '_tobe_import_template_type', true )

				);
			}

			return false;
		}

		public function do_import( $id ) {
			$template = get_post_meta( $id, '_tobe_import_template', true );
			WFACP_Core()->template_loader->add_default_template( true );

			return WFACP_Core()->importer->import( $id, get_post_meta( $id, '_tobe_import_template_type', true ), $template );
		}

		public function update_template_data( $id, $data ) {
			$data['template_active'] = 'yes';
			WFACP_Common::update_page_design( $id, $data );
			delete_post_meta( $id, '_tobe_import_template' );
			delete_post_meta( $id, '_tobe_import_template_type' );
		}

		/**
		 * @param $type
		 * @param $step_id
		 * @param $new_status
		 *
		 * @return bool
		 */
		public function switch_status( $step_id, $new_status ) {
			$switched = false;
			if ( $step_id > 0 ) {
				$newstatus = empty( $new_status ) ? 'draft' : 'publish';
				$args      = [
					'ID'          => $step_id,
					'post_status' => $newstatus,
				];

				$meta       = get_post_meta( $step_id, '_wp_page_template', true );
				$updated_id = wp_update_post( $args );

				if ( intval( $step_id ) === intval( $updated_id ) ) {
					$switched = true;
				}

				update_post_meta( $step_id, '_wp_page_template', $meta );
				WFACP_Common::save_publish_checkout_pages_in_transient();
			}

			return $switched;
		}

		/**
		 * @param $get_ref
		 *
		 * @return mixed
		 */
		public function maybe_funnel_breadcrumb( $get_ref ) {
			$step_id = filter_input( INPUT_GET, 'wfacp_id' );
			if ( empty( $get_ref ) && ! empty( $step_id ) ) {
				$funnel_id = get_post_meta( $step_id, '_bwf_in_funnel', true );
				if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
					return $funnel_id;
				}
			}

			return $get_ref;
		}

		/**
		 * @param $key
		 *
		 * @return mixed
		 */
		public function override_pixel_key( $key ) {
			$step_id = WFACP_Common::get_id();

			if ( $step_id > 0 ) {
				$setting = WFFN_Common::maybe_override_tracking( $step_id );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['fb_pixel_key'] ) && ! empty( $setting['fb_pixel_key'] ) ) ? $setting['fb_pixel_key'] : $key;
				}
			}

			return $key;
		}

		/**
		 * @param $key
		 *
		 * @return mixed
		 */
		public function override_ga_key( $key ) {
			$step_id = WFACP_Common::get_id();

			if ( $step_id > 0 ) {
				$setting = WFFN_Common::maybe_override_tracking( $step_id );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['ga_key'] ) && ! empty( $setting['ga_key'] ) ) ? $setting['ga_key'] : $key;
				}
			}

			return $key;
		}

		/**
		 * @param $key
		 *
		 * @return mixed
		 */
		public function override_gad_key( $key ) {
			$step_id = WFACP_Common::get_id();

			if ( $step_id > 0 ) {
				$setting = WFFN_Common::maybe_override_tracking( $step_id );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['gad_key'] ) && ! empty( $setting['gad_key'] ) ) ? $setting['gad_key'] : $key;
				}
			}

			return $key;
		}

		/**
		 * @param $key
		 *
		 * @return mixed
		 */
		public function override_pint_key( $key ) {
			$step_id = WFACP_Common::get_id();

			if ( $step_id > 0 ) {
				$setting = WFFN_Common::maybe_override_tracking( $step_id );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['pint_key'] ) && ! empty( $setting['pint_key'] ) ) ? $setting['pint_key'] : $key;
				}
			}

			return $key;
		}

		/**
		 * @param $key
		 *
		 * @return mixed
		 */
		public function override_tiktok_key( $key ) {
			$step_id = WFACP_Common::get_id();

			if ( $step_id > 0 ) {
				$setting = WFFN_Common::maybe_override_tracking( $step_id );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['tiktok_pixel'] ) && ! empty( $setting['tiktok_pixel'] ) ) ? $setting['tiktok_pixel'] : $key;
				}
			}

			return $key;
		}

		/**
		 * @param $key
		 *
		 * @return mixed
		 */
		public function override_snapchat_key( $key ) {
			$step_id = WFACP_Common::get_id();

			if ( $step_id > 0 ) {
				$setting = WFFN_Common::maybe_override_tracking( $step_id );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['snapchat_pixel'] ) && ! empty( $setting['snapchat_pixel'] ) ) ? $setting['snapchat_pixel'] : $key;
				}
			}

			return $key;
		}

		/**
		 * @param $key
		 *
		 * @return mixed
		 */
		public function override_conversion_api_test_event_code( $key ) {
			$step_id = WFACP_Common::get_id();

			if ( $step_id > 0 ) {
				$setting = WFFN_Common::maybe_override_tracking( $step_id );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['conversion_api_test_event_code'] ) && ! empty( $setting['conversion_api_test_event_code'] ) ) ? $setting['conversion_api_test_event_code'] : $key;
				}
			}

			return $key;
		}

		/**
		 * @param $key
		 *
		 * @return mixed
		 */
		public function override_conversion_api_access_token( $key ) {
			$step_id = WFACP_Common::get_id();

			if ( $step_id > 0 ) {
				$setting = WFFN_Common::maybe_override_tracking( $step_id );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['conversion_api_access_token'] ) && ! empty( $setting['conversion_api_access_token'] ) ) ? $setting['conversion_api_access_token'] : $key;
				}
			}

			return $key;
		}

		public function maybe_override_environment_for_global( $env ) {

			if ( did_action( 'wfacp_after_template_found' ) > 0 && class_exists( 'WFACP_Common' ) ) {
				$env['id']        = WFACP_Common::get_id();
				$env['post_type'] = WFACP_Common::get_post_type_slug();
			}

			return $env;
		}

		public function load_api_export_class() {
			if ( ! class_exists( 'WFACP_Exporter' ) ) {
				require WFACP_PLUGIN_DIR . '/admin/class-wfacp-exporter.php';//phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
				WFACP_Core()->export = WFACP_Exporter::get_instance();
			}
		}

		public function load_api_import_class() {
			if ( ! class_exists( 'WFACP_Importer' ) ) {
				require WFACP_PLUGIN_DIR . '/admin/class-wfacp-importer.php';//phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
				WFACP_Core()->import = WFACP_Importer::get_instance();
			}
		}

		public function update_pending_conversions() {
			$current_step = WFFN_Core()->data->get_current_step();

			/**
			 * Check if we have valid session to proceed
			 */
			if ( WFFN_Core()->data->has_valid_session() && ! empty( $current_step ) ) {

				/**
				 * Start Marking Impressions
				 */
				$get_step_object = WFFN_Core()->steps->get_integration_object( $current_step['type'] );
				$funnel          = WFFN_Core()->data->get_session_funnel();
				do_action( 'wffn_mark_pending_conversions', $current_step, $get_step_object, $funnel );

			}
		}

		public function alter_templates( $templates ) {


			if ( ! isset( $templates['wc_checkout']['pre_built'] ) || ! isset( $templates['wc_checkout']['embed_forms'] ) ) {
				return $templates;
			}
			$templates['wc_checkout']['customizer'] = [];
			$templates['wc_checkout']['wp_editor']  = [];
			if ( isset( $templates['wc_checkout']['customizer'] ) ) {
				$templates['wc_checkout']['customizer']['customizer-empty'] = [
					'name'               => '',
					'slug'               => 'customizer-empty',
					'build_from_scratch' => true,
					'allow_new'          => false,
					'group_type'         => array( 1 => 'customizer-empty' ),
					'no_steps'           => '1'
				];
			}
			$templates['wc_checkout']['customizer'] = array_merge( $templates['wc_checkout']['customizer'],$templates['wc_checkout']['pre_built'] );


			if ( isset( $templates['wc_checkout']['wp_editor'] ) ) {
				$templates['wc_checkout']['wp_editor']['wp_editor-empty'] = [
					'name'               => '',
					'slug'               => 'customizer-empty',
					'build_from_scratch' => true,
					'allow_new'          => false,
					'group_type'         => array( 1 => 'customizer-empty' ),
					'no_steps'           => '1'
				];
			}
			$templates['wc_checkout']['wp_editor'] = array_merge( $templates['wc_checkout']['wp_editor'],$templates['wc_checkout']['embed_forms'] );

			unset( $templates['wc_checkout']['pre_built'] );
			unset( $templates['wc_checkout']['embed_forms'] );

			return $templates;

		}

		public function get_inherit_supported_post_type() {
			return apply_filters( 'wffn_checkout_inherit_supported_post_type', array( 'cartflows_step', 'page' ) );
		}

		public function maybe_add_funnel_data( $data ) {
			$funnel = WFFN_Core()->data->get_session_funnel();
			if ( wffn_is_valid_funnel( $funnel ) ) {
				$data['funnel_id']    = $funnel->get_id();
				$data['funnel_title'] = $funnel->get_title();
			}

			return $data;
		}

		/**
		 * check if funnel checkout already recoded views
		 *
		 * @param $is
		 * @param $aero_id
		 *
		 * @return bool|mixed
		 */
		public function maybe_already_recoded_views( $is, $aero_id ) {
			if ( 0 === absint( $aero_id ) ) {
				return $is;
			}
			WFFN_Core()->data->get_transient_key();
			if ( empty( WFFN_Core()->data->get_transient_key() ) ) {
				return $is;
			}
			$key          = 'wffn_ay_' . WFFN_Core()->data->get_transient_key();
			$cookie_value = isset( $_COOKIE[ $key ] ) ? wffn_clean( $_COOKIE[ $key ] ) : ''; //phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE
			$cookie_value = explode( ',', str_replace( array( '[', ']' ), '', $cookie_value ) );
			if ( is_array( $cookie_value ) && in_array( $aero_id, $cookie_value ) ) { //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				$is = true;
			}

			return $is;
		}

		/**
		 * override global checkout id with store checkout id
		 *
		 * @param $checkout_id
		 *
		 * @return mixed
		 */
		public static function maybe_override_global_checkout_id( $checkout_id ) {
			/**
			 * Check if store checkout is configures
			 */

			if ( ! class_exists( 'WFFN_Common' ) || ! method_exists( 'WFFN_Common', 'get_store_checkout_id' ) || 0 === WFFN_Common::get_store_checkout_id() ) {
				return $checkout_id;
			}


			$funnel = new WFFN_Funnel( WFFN_Common::get_store_checkout_id() );

			/**
			 * Check if this is a valid funnel and has native checkout
			 */
			if ( ! wffn_is_valid_funnel( $funnel ) ) {
				return $checkout_id;
			}

			/**
			 * if native checkout set in store chekout funnel
			 */
			if ( true === $funnel->is_funnel_has_native_checkout() ) {
				return $checkout_id;
			}

			/**
			 * Check if the funnel found disabled, then do not override native checkout
			 * return blank instead of checkout_id
			 */
			if ( false === wffn_string_to_bool( WFFN_Core()->get_dB()->get_meta( WFFN_Common::get_store_checkout_id(), 'status' ) ) ) {
				return 0;
			}

			if ( is_array( $funnel->get_steps() ) && count( $funnel->get_steps() ) > 0 ) {
				foreach ( $funnel->get_steps() as $step ) {
					if ( isset( $step['type'] ) && $step['type'] === 'wc_checkout' && 'publish' === get_post_status( $step['id'] ) ) {
						return $step['id'];
					}

				}


			}

			return $checkout_id;
		}

		/* Setup funnel and set correct step on update order review call
		 * @param $postdata
		 *
		 * @return void
		 */
		public function setup_funnel_on_update_order( $postdata ) {
			$post_data = [];
			parse_str( $postdata, $post_data );
			$wfacp_id = isset( $post_data['_wfacp_post_id'] ) ? $post_data['_wfacp_post_id'] : 0;

			if ( $wfacp_id < 1 ) {
				return;
			}

			$environment = apply_filters( 'wffn_funnel_environment', array(
				'id'         => $wfacp_id,
				'post_type'  => WFACP_Common::get_post_type_slug(),
				'setup_time' => strtotime( gmdate( 'c' ) ),
			) );

			$funnel = $this->get_funnel_to_run( $environment );

			if ( ! wffn_is_valid_funnel( $funnel ) ) {
				return;
			}

			if ( isset( $environment['id'] ) && $environment['id'] !== '' ) {
				$environment['id'] = absint( $environment['id'] );
			}

			WFFN_Core()->data->set( 'funnel', $funnel );
			WFFN_Core()->data->set( 'current_step', [
				'id'   => $environment['id'],
				'type' => $this->slug,
			] );
			WFFN_Core()->data->save();

		}

		/**
		 * Map Checkout product with new key unique id for product
		 *
		 * @param $meta_value
		 *
		 * @return mixed|object|string
		 */
		public function map_checkout_product( $meta_value ) {

			$old_products = maybe_unserialize( $meta_value );
			if ( ! empty( $old_products ) ) {
				$new_products = [];
				foreach ( $old_products as $o_product ) {
					$new_products[ uniqid( 'wfacp_' ) ] = $o_product;
				}

				return maybe_serialize( $new_products );
			}

			return $meta_value;

		}

	}

	if ( class_exists( 'WFFN_Core' ) && class_exists( 'WFACP_Core' ) && wffn_is_wc_active() ) {

		if ( ! version_compare( WFACP_VERSION, '1.9.3', '>' ) ) {
			wffn_show_notice( array( 'pname' => WFACP_FULL_NAME ), 'version_mismatch' );

			return;
		}
		WFFN_Core()->steps->register( WFFN_Step_WC_Checkout::get_instance() );
	}
}
