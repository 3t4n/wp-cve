<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class contains all the thank you page related functionality
 * Class WFFN_Step_WC_Thankyou
 */
#[AllowDynamicProperties]
class WFFN_Step_WC_Thankyou extends WFFN_Step {

	private static $ins = null;
	public $slug = 'wc_thankyou';
	public $list_priority = 40;

	/**
	 * WFFN_Step_WC_Thankyou constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_filter( 'wffn_wfty_filter_page_ids', array( $this, 'maybe_filter_thankyou' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_add_script' ) );
		add_action( 'bwf_funnels_funnels_display_admin_footer_text', [ $this, 'maybe_show_footer_text' ], 10, 2 );
		add_filter( 'maybe_setup_funnel_for_breadcrumb', [ $this, 'maybe_funnel_breadcrumb' ] );
		add_filter( 'wffn_thankyou_open_without_funnel', [ $this, 'filter_thankyou_on_native' ] );
	}

	/**
	 * @return WFFN_Step_WC_Thankyou|null
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
		return array_unique( array_merge( parent::get_supports(), [ 'track_views', 'track_conversions', 'close_funnel' ] ) );
	}

	/**
	 * @param $steps
	 *
	 * @return array
	 */
	public function get_step_data() {
		return array(
			'type'        => $this->slug,
			'title'       => $this->get_title(),
			'popup_title' => sprintf( __( 'Add %s', 'funnel-builder' ), $this->get_title() ),
			'dashicons'   => 'dashicons-yes-alt',
			'icon'        => 'for-you',
			'label_class' => 'bwf-st-c-badge-red',
			'substeps'    => array(),
		);
	}

	/**
	 * Return title of thank you step
	 */
	public function get_title() {
		return __( 'Thank You Page', 'funnel-builder' );
	}


	public function get_step_designs( $term, $funnel_id = 0 ) {
		$active_pages    = WFFN_Core()->thank_you_pages->get_thank_you_pages( $term );
		$inside_funnels  = [];
		$outside_funnels = [];
		foreach ( $active_pages as $active_page ) {
			$post_type     = get_post_type( $active_page->ID );
			$bwf_funnel_id = get_post_meta( $active_page->ID, '_bwf_in_funnel', true );
			$data          = [];
			if ( 'cartflows_step' === $post_type ) {
				$meta = get_post_meta( $active_page->ID, 'wcf-step-type', true );
				if ( 'thankyou' === $meta ) {
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
	 * @param $step
	 * @param $posted_data
	 *
	 * @return stdClass
	 */
	public function add_step( $funnel_id, $posted_data ) {
		$title               = isset( $posted_data['title'] ) ? $posted_data['title'] : '';
		$post_content        = isset( $posted_data['post_content'] ) ? $posted_data['post_content'] : '';
		$thank_you_page_data = array(
			'post_type'    => WFFN_Core()->thank_you_pages->get_post_type_slug(),
			'post_title'   => $title,
			'post_name'    => sanitize_title( $title ),
			'post_status'  => 'publish',
			'post_content' => $post_content,
		);
		$step_id             = WFFN_Core()->thank_you_pages->insert_thank_you_page( $thank_you_page_data );
		$posted_data['id']   = ( $step_id > 0 ) ? $step_id : 0;

		if ( $step_id > 0 ) {
			update_post_meta( $step_id, '_wp_page_template', 'wftp-boxed.php' );
		}

		$step_data = parent::add_step( $funnel_id, $posted_data );


		return $step_data;
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

		$duplicate_id = WFFN_Core()->thank_you_pages->duplicate_thank_you_page( $step_id );

		$posted_data['id'] = ( $duplicate_id > 0 ) ? $duplicate_id : 0;

		$post_status = ( isset( $posted_data['original_id'] ) && $posted_data['original_id'] > 0 ) ? get_post_status( $posted_data['original_id'] ) : 'publish';

		if ( $duplicate_id > 0 ) {
			$posted_data['id'] = $duplicate_id;
			$duplicate_post    = get_post( $duplicate_id );
			$original_post     = get_post( $step_id );
			$new_title         = isset( $posted_data['title'] ) ? $posted_data['title'] : '';
			if ( ! empty( $new_title ) ) {
				$duplicate_post->post_title = $new_title;
			}

			$duplicate_post->post_status  = $post_status;
			$duplicate_post->post_content = $original_post->post_content;
			wp_update_post( $duplicate_post );
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
	 * @return bool|WFFN_Funnel
	 */
	public function get_funnel_to_run( $environment ) {
		$get_thankyou_page = $environment['id'];
		$get_funnel_id     = get_post_meta( $get_thankyou_page, '_bwf_in_funnel', true );
		$get_funnel        = new WFFN_Funnel( $get_funnel_id );

		return $get_funnel;
	}

	/**
	 * @param $thankyou_page_ids
	 * @param $order
	 *
	 * @return array
	 */
	public function maybe_filter_thankyou( $thankyou_page_ids, $order ) {

		if ( ! $order instanceof WC_Order ) {
			return $thankyou_page_ids;
		}

		$current_step = [];
		$aero_id      = BWF_WC_Compatibility::get_order_meta( $order, '_wfacp_post_id' );
		$custom_id    = apply_filters( 'wffn_thankyou_open_without_funnel', 0, $thankyou_page_ids, $order );

		if ( is_array( $custom_id ) && count( $custom_id ) > 0 ) {
			$thankyou_page_ids = $custom_id;

		} elseif ( $custom_id ) {
			$thankyou_page_ids = ( absint( $custom_id ) > 0 ) ? [ $custom_id ] : $thankyou_page_ids;
		}


		if ( empty( $aero_id ) ) {

			$primary_order_id = BWF_WC_Compatibility::get_order_meta( $order, '_wfocu_primary_order' );
			if ( ! empty( $primary_order_id ) && 0 < abs( $primary_order_id ) ) {
				$aero_id = BWF_WC_Compatibility::get_order_meta( wc_get_order( $primary_order_id ), '_wfacp_post_id' );
			}
		}

		if ( empty( $aero_id ) || 0 === abs( $aero_id ) ) {
			return $thankyou_page_ids;
		}

		$funnel_id = get_post_meta( $aero_id, '_bwf_in_funnel', true );
		if ( empty( $funnel_id ) || abs( $funnel_id ) === 0 ) {
			return $thankyou_page_ids;
		}

		$funnel = WFFN_Core()->admin->get_funnel( $funnel_id );
		if ( ! $funnel instanceof WFFN_Funnel ) {
			return $thankyou_page_ids;
		}

		$current_step['id']   = $aero_id;
		$current_step['type'] = 'wc_checkout';

		$current_step['id'] = apply_filters( 'wffn_maybe_get_ab_control', $current_step['id'] );

		return $this->maybe_get_thankyou( $current_step, $funnel );
	}

	/**
	 * @param $current_step
	 *
	 * @return bool
	 */
	public function validate_environment( $current_step, $order = false ) {

		if ( ! $order instanceof WC_Order ) {
			$orderID = WFFN_Core()->data->get( 'wc_order' );
			$order   = wc_get_order( $orderID );
		}

		if ( ! $order instanceof WC_Order ) {
			WFFN_Core()->logger->log( 'No Order found.' );

			return false;
		}

		$order->read_meta_data();
		$wfacp_id = $order->get_meta( '_wfacp_post_id' );

		if ( absint( $current_step['id'] ) === absint( $wfacp_id ) ) {
			return true;
		}

		if ( apply_filters( 'wfty_maybe_change_order_id', false, $order, $current_step ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param $current_step
	 * @param $funnel
	 *
	 * @return array
	 */
	public function maybe_get_thankyou( $current_step, $funnel ) {
		$found_step         = false;
		$all_funnels        = [];
		$targets_step_found = false;
		foreach ( $funnel->steps as $key => $step ) {

			/**
			 * continue till we found the current step
			 */
			if ( false !== $current_step && absint( $current_step['id'] ) === absint( $step['id'] ) ) {
				$found_step = $key;
				continue;
			}

			/**
			 * Continue if we have not found the current step yet
			 */
			if ( false !== $current_step && false === $found_step ) {
				continue;
			}
			/**
			 * if step is not the type after the current step then break the loop
			 */
			if ( $this->slug !== $step['type'] && true === $targets_step_found ) {
				break;
			}
			if ( $this->slug !== $step['type'] ) {
				continue;
			}


			/**
			 * if we have found the current step and type is upsell then connect
			 */

			$properties = $this->populate_data_properties( $step, $funnel->get_id() );

			if ( $this->is_disabled( $this->get_enitity_data( $properties['_data'], 'status' ) ) ) {
				continue;
			}

			$all_funnels[]      = $step['id'];
			$targets_step_found = true;


		}

		return $all_funnels;
	}

	/**
	 * @param $step_id
	 *
	 * @return mixed
	 */
	public function get_entity_edit_link( $step_id ) {
		return esc_url( BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
			'page' => 'bwf',
			'path' => '/funnel-thankyou/' . $step_id . '/design',
		], admin_url( 'admin.php' ) ) ) );
	}

	public function maybe_add_script() {

		if ( WFFN_Core()->thank_you_pages->is_wfty_page() === true ) {

			$funnel       = WFFN_Core()->data->get_session_funnel();
			$current_step = WFFN_Core()->data->get_current_step();
			$order        = WFFN_Core()->thank_you_pages->data->get_order();
			if ( WFFN_Core()->data->has_valid_session() && ! empty( $current_step ) && wffn_is_valid_funnel( $funnel ) && $this->validate_environment( $current_step, $order ) ) {
				WFFN_Core()->data->set( 'current_step', [
					'id'   => WFFN_Core()->thank_you_pages->thankyoupage_id,
					'type' => $this->slug,
				] );
				WFFN_Core()->data->save();

				/**
				 * Setup the funnel result array to make sure js work clean
				 */
				WFFN_Core()->public->funnel_setup_result = array(
					'success'      => true,
					'current_step' => [
						'id'   => WFFN_Core()->thank_you_pages->thankyoupage_id,
						'type' => $this->slug,
					],
					'hash'         => WFFN_Core()->data->get_transient_key(),
					'next_link'    => '',
				);

				WFFN_Core()->public->maybe_add_script();

			}
		}
	}


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
				if ( substr( $meta_key, 0, strlen( '_wftp' ) ) === '_wftp' ) {
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


	public function update_template_data( $id, $data ) {
		WFFN_Core()->thank_you_pages->update_page_design( $id, $data );
	}

	public function do_import( $id ) {
		$template = get_post_meta( $id, '_tobe_import_template', true );

		return WFFN_Core()->importer->import_remote( $id, get_post_meta( $id, '_tobe_import_template_type', true ), $template, 'wc_thankyou' );
	}

	public function maybe_show_footer_text( $existing, $current_screen ) {
		return ( $current_screen === 'woofunnels_page_wf-ty' ) ? true : $existing;
	}

	public function mark_step_viewed() {
		$current_page = WFFN_Core()->data->get_current_step();

		$thankyou_id = isset( $current_page['id'] ) ? $current_page['id'] : 0;
		if ( $thankyou_id > 0 ) {
			$this->increase_thankyou_visit_wc_session_view( $thankyou_id );
		}
		do_action( 'wffn_event_step_viewed', $thankyou_id, $current_page );
		do_action( 'wffn_event_step_viewed_' . $this->slug, $thankyou_id, $current_page );
	}

	public function increase_thankyou_visit_wc_session_view( $thankyou_id ) {
		if ( $thankyou_id < 1 ) {
			return;
		}
		WFCO_Model_Report_views::update_data( gmdate( 'Y-m-d', current_time( 'timestamp' ) ), $thankyou_id, 5 );
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

	public function get_entity_tags( $step_id, $funnel_id ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter,VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$flags       = array();

		if ( ! class_exists( 'WFTY_Rules' ) ) {
			return $flags;
		}

		$funnel_rules = get_post_meta( $step_id, '_wfty_rules', true );
		$has_rules    = $no_product = $no_offers = false;
		foreach ( is_array( $funnel_rules ) ? $funnel_rules : array() as $rule_groups ) {
			foreach ( is_array( $rule_groups ) ? $rule_groups : array() as $rules_data ) {
				foreach ( is_array( $rules_data ) ? $rules_data : array() as $rules_arr ) {
					if ( isset( $rules_arr['rule_type'] ) && ( 'general_always' !== $rules_arr['rule_type'] && 'general_always_2' !== $rules_arr['rule_type'] ) ) {
						$has_rules = true;
						break 3;
					}
				}
			}
		}

		if ( $has_rules ) {
			$flags['has_rules'] = array(
				'label'       => __( 'Has Rules', 'funnel-builder' ),
				'label_class' => 'bwf-st-c-badge-green',
				'edit'        => wffn_rest_api_helpers()->get_entity_url( 'thankyou', 'rules', $step_id )
			);
		}

		return $flags;
	}

	public function maybe_ecomm_events( $events ) {
		WFFN_Ecomm_Tracking_Landing::get_instance()->maybe_ecomm_events( $events );
	}

	public function filter_thankyou_on_native( $custom_ids = '' ) {

		/**
		 * Check if store checkout is configures
		 */
		if ( ! WFFN_Common::get_store_checkout_id() ) {
			return $custom_ids;
		}

		/**
		 * Check if store checkout funnel is enabled
		 */

		if ( false === wffn_string_to_bool( WFFN_Core()->get_dB()->get_meta( WFFN_Common::get_store_checkout_id(), 'status' ) ) ) {
			return $custom_ids;
		}

		/**
		 * Check if we do not have checkout in our funnel
		 */

		$funnel = new WFFN_Funnel( WFFN_Common::get_store_checkout_id() );

		/**
		 * Check if this is a valid funnel and has native checkout
		 * filter thankyou pages and serve the results
		 */
		if ( wffn_is_valid_funnel( $funnel ) && true === $funnel->is_funnel_has_native_checkout() ) {
			return $this->maybe_get_thankyou( false, $funnel );
		}

		return $custom_ids;


	}


}

if ( class_exists( 'WFFN_Core' ) && ! empty( WFFN_Core()->thank_you_pages ) ) {
	WFFN_Core()->steps->register( WFFN_Step_WC_Thankyou::get_instance() );
}
