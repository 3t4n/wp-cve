<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class contains all the Optin page related funnel functionality
 * Class WFFN_Step_Optin
 */
if ( ! class_exists( 'WFFN_Step_Optin' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Step_Optin extends WFFN_Step {

		private static $ins = null;
		public $slug = 'optin';
		public $list_priority = 15;

		/**
		 * WFFN_Step_Optin constructor
		 */
		public function __construct() {
			parent::__construct();
			add_action( 'bwf_funnels_funnels_display_admin_footer_text', [ $this, 'maybe_show_footer_text' ], 10, 2 );
			add_action( 'wffn_optin_action_woo_order_created', array( $this, 'maybe_set_order_in_data' ) );
			add_filter( 'wffn_optin_redirect', array( $this, 'maybe_redirect_to_thankyou_page' ), 10, 2 );
			add_filter( 'maybe_setup_funnel_for_breadcrumb', [ $this, 'maybe_funnel_breadcrumb' ] );
		}

		/**
		 * @return WFFN_Step_Optin|null
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
			return array_unique( array_merge( parent::get_supports(), [ 'open_link', 'next_link', 'track_views', 'track_conversions' ] ) );
		}

		/**
		 * @return array
		 */
		public function get_step_data() {
			return array(
				'type'        => $this->slug,
				'title'       => $this->get_title(),
				'popup_title' => __( 'Add Optin Page', 'funnel-builder' ),
				'dashicons'   => 'dashicons-email',
				'icon'        => 'mail-2',
				'label'       => __( 'No Products', 'funnel-builder' ),
				'label_class' => 'wf_funnel_btn_red',
				'desc'        => __( 'Set up optin pages with your favorite page builder', 'funnel-builder' ),
				'substeps'    => array(),
				'color'       => '#656565',

			);
		}

		/**
		 * @return string|void
		 */
		public function get_title() {
			return __( 'Optin', 'funnel-builder' );
		}


		public function get_step_designs( $term, $funnel_id = 0 ) {
			$active_pages    = WFOPP_Core()->optin_pages->get_optin_pages( $term );
			$inside_funnels  = [];
			$outside_funnels = [];
			foreach ( $active_pages as $active_page ) {
				$post_type     = get_post_type( $active_page->ID );
				$bwf_funnel_id = get_post_meta( $active_page->ID, '_bwf_in_funnel', true );
				$data          = [];
				if ( 'cartflows_step' === $post_type ) {
					$meta = get_post_meta( $active_page->ID, 'wcf-step-type', true );
					if ( 'optin' === $meta ) {
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

		/**
		 * @param $funnel_id
		 * @param $posted_data
		 *
		 * @return stdClass
		 */
		public function add_step( $funnel_id, $posted_data ) {
			$title = isset( $posted_data['title'] ) ? $posted_data['title'] : '';

			$step_id           = wp_insert_post( array(
				'post_type'    => WFOPP_Core()->optin_pages->get_post_type_slug(),
				'post_title'   => $title,
				'post_name'    => sanitize_title( $title ),
				'post_status'  => 'publish',
				'post_content' => isset( $posted_data['post_content'] ) ? $posted_data['post_content'] : '',
			) );
			$posted_data['id'] = ( $step_id > 0 ) ? $step_id : 0;
			if ( $step_id > 0 ) {
				update_post_meta( $step_id, '_wp_page_template', 'wfop-boxed.php' );
			}

			return parent::add_step( $funnel_id, $posted_data );
		}

		/**
		 * @param $funnel_id
		 * @param $step_id
		 * @param $posted_data
		 *
		 * @return stdClass
		 */
		public function duplicate_step( $funnel_id, $step_id, $posted_data ) {

			$duplicate_id      = WFOPP_Core()->optin_pages->duplicate_optin_page( $step_id );
			$posted_data['id'] = ( $duplicate_id > 0 ) ? $duplicate_id : 0;
			$post_status       = ( isset( $posted_data['original_id'] ) && $posted_data['original_id'] > 0 ) ? get_post_status( $posted_data['original_id'] ) : 'publish';

			if ( $duplicate_id > 0 ) {
				$posted_data['id'] = $duplicate_id;
				$new_title         = isset( $posted_data['title'] ) ? $posted_data['title'] : '';
				$arr               = [ 'ID' => $duplicate_id, 'post_status' => $post_status ];

				if ( ! empty( $new_title ) ) {
					$arr['post_title'] = $new_title;
				}
				wp_update_post( $arr );
			}

			$final_data = parent::duplicate_step( $funnel_id, $duplicate_id, $posted_data );

			if ( isset( $posted_data['id'] ) && isset( $posted_data['_data']['desc'] ) ) {
				$post               = get_post( $posted_data['id'] );
				$post->post_content = $posted_data['_data']['desc'];
				wp_update_post( $post );
			}

			return $final_data;

		}

		/**
		 * @param $environment
		 *
		 * @return bool
		 */
		public function claim_environment( $environment ) {
			if ( 'wffn_optin' !== $environment['post_type'] ) {
				return false;
			}
			if ( $this->is_disabled( $this->get_entity_status( $environment['id'] ) ) ) {
				return false;
			}
			WFFN_Core()->logger->log( 'Showing step type: ' . $this->get_title() . ", ID : " . $environment['id'] );

			return true;
		}

		/**
		 * @param $environment
		 *
		 * @return bool|WFFN_Funnel
		 */
		public function get_funnel_to_run( $environment ) {
			$get_optin_page = $environment['id'];
			$get_funnel_id  = get_post_meta( $get_optin_page, '_bwf_in_funnel', true );
			$get_funnel     = new WFFN_Funnel( $get_funnel_id );

			return $get_funnel;
		}

		/**
		 * @param $step_id
		 *
		 * @return mixed
		 */
		public function get_entity_edit_link( $step_id ) {
			return esc_url( BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
				'page' => 'bwf',
				'path' => '/funnel-optin/' . $step_id . '/design',
			], admin_url( 'admin.php' ) ) ) );
		}

		public function get_color() {
			return '#86ec16';
		}


		/**
		 * @param $step
		 *
		 * @return array|void
		 */
		public function _get_export_metadata( $step ) {
			$new_all_meta         = array();
			$valid_step_meta_keys = array(
				'_wp_page_template',
				'_thumbnail_id',
				'classic-editor-remember',
				'_wp_page_template',
				'_elementor_data',
				'_elementor_page_settings',
				'_elementor_controls_usage',
				'_elementor_page_assets',
			);
			$all_meta             = get_post_meta( $step['id'] );

			if ( is_array( $all_meta ) ) {
				foreach ( $all_meta as $meta_key => $value ) {
					if ( substr( $meta_key, 0, strlen( '_wfop' ) ) === '_wfop' ) {
						$new_all_meta[ $meta_key ] = maybe_unserialize( $value[0] );
					} elseif ( substr( $meta_key, 0, strlen( 'wffn_' ) ) === 'wffn_' ) {
						$new_all_meta[ $meta_key ] = maybe_unserialize( $value[0] );
					} elseif ( in_array( $meta_key, $valid_step_meta_keys, true ) ) {
						$new_all_meta[ $meta_key ] = maybe_unserialize( $value[0] );
					} else {
						$new_all_meta[ $meta_key ] = $value[0];
					}
				}

			}

			return $new_all_meta;
		}

		public function update_template_data( $id, $data ) {
			WFOPP_Core()->optin_pages->update_page_design( $id, $data );
		}

		/**
		 * @param $funnel_id
		 * @param $step_data
		 */
		public function _process_import( $funnel_id, $step_data ) {

			$post_content = ( isset( $step_data['post_content'] ) && ! empty( $step_data['post_content'] ) ) ? $step_data['post_content'] : '';
			$posted_data  = [ 'title' => $step_data['title'], 'post_content' => $post_content ];
			$data         = $this->add_step( $funnel_id, $posted_data );
			if ( isset( $step_data['meta'] ) ) {
				$this->copy_metadata( $data->id, $step_data['meta'] );
			}

			if ( isset( $step_data['meta']['_elementor_data'] ) ) {
				if ( class_exists( 'WFFN_Elementor_Importer' ) ) {
					$content        = $step_data['meta']['_elementor_data'];
					$obj            = new WFFN_Elementor_Importer();
					$elementor_data = is_string( $content ) ? $content : wp_json_encode( $content );
					$obj->import( $data->id, $elementor_data );
				}
			}

			if ( isset( $step_data['meta']['_wp_page_template'] ) ) {
				update_post_meta( $data->id, '_wp_page_template', $step_data['meta']['_wp_page_template'] );
			}

			if ( isset( $step_data['template'] ) && ! empty( $step_data['template'] ) ) {
				update_post_meta( $data->id, '_tobe_import_template', $step_data['template'] );
				update_post_meta( $data->id, '_tobe_import_template_type', $step_data['template_type'] );
			}
			if ( ! empty( $post_content ) ) {
				$post = get_post( $data->id );
				if ( $post instanceof WP_Post ) {
					$post->post_content = $post_content;
					wp_update_post( $post );
				}
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

			return WFFN_Core()->importer->import_remote( $id, get_post_meta( $id, '_tobe_import_template_type', true ), $template, 'optin' );
		}

		public function maybe_show_footer_text( $existing, $current_screen ) {
			return ( $current_screen === 'woofunnels_page_wf-op' ) ? true : $existing;
		}

		public function maybe_set_order_in_data( $order ) {

			WFFN_Core()->data->set( 'optin_woo_order', $order->get_id() )->save();
		}

		public function maybe_redirect_to_thankyou_page( $url, $current ) {
			if ( is_array( $current ) && 'optin' === $current['type'] ) {
				$get_saved_optin_order = WFFN_Core()->data->get( 'optin_woo_order', false );
				if ( ! empty( $get_saved_optin_order ) ) {
					$get_order = wc_get_order( $get_saved_optin_order );

					if ( $get_order instanceof WC_Order ) {
						return $get_order->get_checkout_order_received_url();
					}
				}
			}

			return $url;
		}

		public function mark_step_viewed() {
			$current_page = WFFN_Core()->data->get_current_step();
			$optin_id     = isset( $current_page['id'] ) ? $current_page['id'] : 0;
			if ( $optin_id > 0 ) {
				WFFN_Core()->logger->log( __FUNCTION__ . ':: ' . $optin_id );
				$this->increase_optin_visit_session_view( $optin_id );
			}
			do_action( 'wffn_event_step_viewed', $optin_id, $current_page );
			do_action( 'wffn_event_step_viewed_' . $this->slug, $optin_id, $current_page );
		}

		public function increase_optin_visit_session_view( $optin_id ) {
			if ( $optin_id < 1 ) {
				return;
			}
			WFCO_Model_Report_views::update_data( gmdate( 'Y-m-d', current_time( 'timestamp' ) ), $optin_id, 8 );
		}

		/**
		 * @param $get_ref
		 *
		 * @return mixed
		 */
		public function maybe_funnel_breadcrumb( $get_ref ) {
			$step_id = filter_input( INPUT_GET, 'edit' );
			if ( empty( $get_ref ) && ! empty( $step_id ) ) {
				$funnel_id = get_post_meta( $step_id, '_bwf_in_funnel', true );
				if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
					return $funnel_id;
				}
			}

			return $get_ref;
		}

		/**
		 * @param int $id
		 *
		 * @return false|string
		 */
		public function get_url( $id = 0 ) {
			WFOPP_Core()->optin_pages->setup_custom_options( $id );
			$data = WFOPP_Core()->optin_pages->get_custom_option();

			if ( isset( $data['custom_redirect_page'] ) && $data['custom_redirect'] === 'true' ) {
				if ( is_array( $data['custom_redirect_page'] ) && count( $data['custom_redirect_page'] ) > 0 ) {
					$url_from_filter = add_query_arg( array( 'wfop_source' => $id ), get_permalink( $data['custom_redirect_page']['id'] ) );
				}
			}
			if ( empty( $url_from_filter ) ) {
				return get_permalink( $id );
			}


			return $url_from_filter;
		}

		public function maybe_ecomm_events( $events ) {
			WFFN_Ecomm_Tracking_Optin::get_instance()->maybe_ecomm_events( $events );
		}

	}

	if ( class_exists( 'WFFN_Core' ) ) {
		WFFN_Core()->steps->register( WFFN_Step_Optin::get_instance() );
	}
}
