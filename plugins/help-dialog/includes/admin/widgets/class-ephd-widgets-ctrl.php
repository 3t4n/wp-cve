<?php

defined( 'ABSPATH' ) || exit();

/**
 * Handle user submission from Help dialog Widgets
 */
class EPHD_Widgets_Ctrl {

	public function __construct() {

		add_action( 'wp_ajax_ephd_create_widget', array( $this, 'create_widget' ) );
		add_action( 'wp_ajax_nopriv_ephd_create_widget', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_update_widget', array( $this, 'update_widget' ) );
		add_action( 'wp_ajax_nopriv_ephd_update_widget', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_delete_widget', array( $this, 'delete_widget' ) );
		add_action( 'wp_ajax_nopriv_ephd_delete_widget', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_update_preview', array( $this, 'update_preview' ) );
		add_action( 'wp_ajax_nopriv_ephd_update_preview', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_load_widget_form', array( $this, 'load_widget_form' ) );
		add_action( 'wp_ajax_nopriv_ephd_load_widget_form', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_search_locations', array( $this, 'search_locations' ) );
		add_action( 'wp_ajax_nopriv_ephd_search_locations', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_copy_design_to', array( $this, 'copy_design_to' ) );
		add_action( 'wp_ajax_nopriv_ephd_copy_design_to', array( 'EPHD_Utilities', 'user_not_logged_in' ) );

		add_action( 'wp_ajax_ephd_tiny_mce_input_save', array( $this, 'tiny_mce_input_save' ) );
		add_action( 'wp_ajax_nopriv_ephd_tiny_mce_input_save', array( 'EPHD_Utilities', 'user_not_logged_in' ) );
	}

	/**
	 * Create WIDGET together with its own design
	 */
	public function create_widget() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		// retrieve configuration for all Widgets
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 28, $widgets_config ) );
		}

		// retrieve Widget data
		$widget = self::get_sanitized_widget_from_input();

		// add missing fields from specs
		$widget = array_merge( EPHD_Config_Specs::get_default_hd_config(), $widget );

		// cannot overwrite existing widget
		if ( isset( $widgets_config[$widget['widget_id']] ) ) {
			EPHD_Logging::add_log( 'Widget already exists (30)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 30, 'widget id: ' . $widget['widget_id'] ) );
		}

		// retrieve Global configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 31 ) );
		}

		// retrieve and apply changes for Global config
		$global_config = self::get_sanitized_global_form_from_input( $global_config );

		// assign new widget an ID
		$global_config['last_widget_id']++;
		$widget['widget_id'] = $global_config['last_widget_id'];

		// save Widgets configuration
		$widgets_config[$widget['widget_id']] = $widget;
		$updated_widgets_config = ephd_get_instance()->widgets_config_obj->update_config( $widgets_config );
		if ( is_wp_error( $updated_widgets_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving Widgets configuration. (33)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 33, $updated_widgets_config ) );
		}
		$updated_widget = $updated_widgets_config[$widget['widget_id']];


		// update last Widget id and last Design id in Global configuration
		$updated_global_config = ephd_get_instance()->global_config_obj->update_config( $global_config );
		if ( is_wp_error( $updated_global_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving Global configuration. (35)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 35, $updated_global_config ) );
		}

		// pass into JS the new Widget as a new settings box
		$widgets_page_handler = EPHD_Widgets_Display::get_widgets_page_handler( $widgets_config );
		if ( is_wp_error( $widgets_page_handler ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 36 ) );
		}

		$widget_form_config = array(
			'class'         => 'ephd-wp__widget-form ephd-wp__widget-form--active',
			'html'          => $widgets_page_handler->get_widget_form( $updated_widget ),
			'return_html'   => true,
		);

		// pass into JS a preview box for the new Widget
		$widget_preview_config = $widgets_page_handler->get_config_of_widget_preview_box( $updated_widget, true );

		// Set notification message
		$notification_message = __( 'Configuration Saved', 'help-dialog');
		if ( $widget['widget_status'] == 'published' ) {
			$notification_message = __( 'Configuration is saved and the Widget is published', 'help-dialog');
		}
		if ( $widget['widget_status'] == 'draft' ) {
			$notification_message = __( 'Configuration is saved and the Widget is set to Draft', 'help-dialog');
		}

		// pass into JS updated preview of front-end Widget
		$hd_handler = new EPHD_Help_Dialog_View( $updated_widget, true, true );

		wp_die( wp_json_encode( array(
			'status'            => 'success',
			'message'           => esc_html( $notification_message ),
			'widget_id'         => esc_attr( $updated_widget['widget_id'] ),
			'widget_form'       => EPHD_HTML_Forms::admin_settings_box( $widget_form_config ),
			'demo_styles'       => EPHD_Help_Dialog_View::insert_widget_inline_styles( $updated_global_config, $updated_widget, true ),
			'widget_preview'    => EPHD_HTML_Forms::admin_settings_box( $widget_preview_config ),
			'preview'           => $hd_handler->output_help_dialog( true ),
		) ) );
	}

	/**
	 * Update WIDGET
	 */
	public function update_widget() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		// retrieve Widget data
		$widget = self::get_sanitized_widget_from_input();

		// create a new widget if it doesn't exist
		if ( empty( $widget['widget_id'] ) ) {
			self::create_widget();
			return;
		}

		/** UPDATE WIDGET SETTINGS */

		// retrieve configuration for all widgets
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 40 ) );
		}

		// do not update if the widget does not exist
		if ( ! isset( $widgets_config[$widget['widget_id']] ) ) {
			EPHD_Logging::add_log( 'Widget does not exist (42)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 42, 'widget id: ' . $widget['widget_id'] ) );
		}

		// Save widget status before changes
		$initial_widget_status = $widgets_config[$widget['widget_id']]['widget_status'];

		// add missing fields for existing Widget
		$widget = array_merge( $widgets_config[$widget['widget_id']], $widget );

		// save Widgets configuration
		$widgets_config[$widget['widget_id']] = $widget;
		$updated_widgets_config = ephd_get_instance()->widgets_config_obj->update_config( $widgets_config );
		if ( is_wp_error( $updated_widgets_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving widgets configuration. (43)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 43, $updated_widgets_config ) );
		}
		$updated_widget = $updated_widgets_config[$widget['widget_id']];

		/** UPDATE GLOBAL SETTINGS */

		// retrieve Global configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 45 ) );
		}

		// retrieve and apply changes for Global config
		$global_config = self::get_sanitized_global_form_from_input( $global_config );

		// save Global configuration
		$updated_global_config = ephd_get_instance()->global_config_obj->update_config( $global_config );
		if ( is_wp_error( $updated_global_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving global configuration. (46)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 46, $updated_global_config ) );
		}

		// pass into JS the new widget as a new settings box
		$widgets_page_handler = EPHD_Widgets_Display::get_widgets_page_handler( $widgets_config );
		if ( is_wp_error( $widgets_page_handler ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 49 ) );
		}

		/** FINISH */

		$widget_form_config = array(
			'class'         => 'ephd-wp__widget-form ephd-wp__widget-form--active',
			'html'          => $widgets_page_handler->get_widget_form( $updated_widget ),
			'return_html'   => true,
		);

		// pass into JS a preview box for the new Widget
		$widget_preview_config = $widgets_page_handler->get_config_of_widget_preview_box( $updated_widget, true );

		// Set notification message
		$notification_message = __( 'Configuration Saved', 'help-dialog');
		if ( $widget['widget_status'] == 'published' && $initial_widget_status == 'draft' ) {
			$notification_message = __( 'Configuration is saved and the Widget is published', 'help-dialog');
		}
		if ( $widget['widget_status'] == 'draft' && $initial_widget_status == 'published' ) {
			$notification_message = __( 'Configuration is saved and the Widget is set to Draft', 'help-dialog');
		}

		// pass into JS updated preview of front-end Widget
		$hd_handler = new EPHD_Help_Dialog_View( $updated_widget, true, true );

		wp_die( wp_json_encode( array(
			'status'            => 'success',
			'message'           => esc_html( $notification_message ),
			'widget_id'         => esc_attr( $updated_widget['widget_id'] ),
			'widget_form'       => EPHD_HTML_Forms::admin_settings_box( $widget_form_config ),
			'demo_styles'       => EPHD_Help_Dialog_View::insert_widget_inline_styles( $updated_global_config, $updated_widget, true ),
			'widget_preview'    => EPHD_HTML_Forms::admin_settings_box( $widget_preview_config ),
			'preview'           => $hd_handler->output_help_dialog( true ),
		) ) );
	}

	/**
	 * Delete WIDGET
	 */
	public function delete_widget() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		// retrieve Widget id ( default Widget has id=1; normally user does not have option to delete the default Widget - generate error on attempt )
		$widget_id = (int)EPHD_Utilities::post( 'widget_id' );
		if ( empty( $widget_id ) || $widget_id == EPHD_Config_Specs::DEFAULT_ID ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 50 ) );
		}

		// retrieve configuration for all Widgets
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 51, $widgets_config ) );
		}

		// cannot delete Widget if it does not exist in configuration
		if ( ! isset( $widgets_config[$widget_id] ) ) {
			EPHD_Logging::add_log( 'Widget does not exist (52)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 52, $widgets_config ) );
		}

		// remove Widget
		unset( $widgets_config[$widget_id] );

		// update Widgets configuration
		$updated_widgets_config = ephd_get_instance()->widgets_config_obj->update_config( $widgets_config );
		if ( is_wp_error( $updated_widgets_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving widgets configuration. (53)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 53, $updated_widgets_config ) );
		}

		// remove widget from custom table
		ephd_get_instance()->widgets_config_obj->delete_widget_by_id( $widget_id );  // ignore return value; will be cleared when saving changes


		wp_die( wp_json_encode( array(
			'status'    => 'success',
			'message'   => esc_html__( 'Widget removed', 'help-dialog' ),
		) ) );
	}

	/**
	 * Update WIDGET preview when user changes settings - nothing is saved here
	 */
	public function update_preview() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		// retrieve Widget data
		$widget = self::get_sanitized_widget_from_input();

		// retrieve configuration for all Widgets - user can preview existing Widget or be creating a Widget (when passed Widget id is 0)
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 60 ) );
		}

		// add missing fields for existing Widget
		$base_widget = isset( $widgets_config[$widget['widget_id']] )
			? $widgets_config[$widget['widget_id']]
			: $widgets_config[EPHD_Config_Specs::DEFAULT_ID];
		$widget = array_merge( $base_widget, $widget );

		// retrieve Global configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $global_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 62 ) );
		}

		// retrieve and apply changes for Global config - do not save, preview only
		$global_config = self::get_sanitized_global_form_from_input( $global_config );

		// define whether need to return the Widget preview opened or closed
		$is_opened = (bool)EPHD_Utilities::post( 'is_opened', false );

		$hd_handler = new EPHD_Help_Dialog_View( $widget, $is_opened, true, $global_config );
		wp_die( wp_json_encode( array(
			'status'        => 'success',
			'message'       => 'success',
			'demo_styles'   => EPHD_Help_Dialog_View::insert_widget_inline_styles( $global_config, $widget, true ),
			'preview'       => $hd_handler->output_help_dialog( true ),
		) ) );
	}

	/**
	 * Return form HTML to create a new Widget
	 */
	public function load_widget_form() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		// retrieve Widget id (0 if it is needed to create a new Widget)
		$widget_id = (int)EPHD_Utilities::post( 'widget_id', -1 );
		$parent_widget_id = (int)EPHD_Utilities::post( 'parent_widget_id' );
		if ( $widget_id < 0 ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 65 ) );
		}

		// retrieve configs for all Widgets
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 66 ) );
		}

		// retrieve current Widget configuration
		if ( isset( $widgets_config[$widget_id] ) ) {
			$widget = $widgets_config[$widget_id];
		// or copy current widget
		} else if ( !empty( $parent_widget_id ) && !empty( $widgets_config[$parent_widget_id] ) ) {
			$widget = $widgets_config[$parent_widget_id];
			$widget['widget_id'] = 0;

			// retrieve new one widget name
			$widget_name = EPHD_Utilities::post( 'widget_name', $widgets_config[$parent_widget_id]['widget_name'] );
			if ( empty( $widget_name ) ) {
				EPHD_Utilities::ajax_show_error_die( __( 'Widget name cannot be empty.', 'help-dialog' ) );
			}
			$widget['widget_name'] = $widget_name;

			// make sure the new Widget does not inherit locations from default Widget
			$widget['location_page_filtering'] = 'include';
			$widget['location_pages_list'] = [];
			$widget['location_posts_list'] = [];
			$widget['location_cpts_list'] = [];
			$widget['faqs_sequence'] = [];
		} else {
			$widget = $widgets_config[EPHD_Widgets_DB::DEFAULT_ID];
			$widget['widget_id'] = 0;

			// retrieve new one widget name
			$widget_name = EPHD_Utilities::post( 'widget_name', $widgets_config[EPHD_Widgets_DB::DEFAULT_ID]['widget_name'] );
			if ( empty( $widget_name ) ) {
				EPHD_Utilities::ajax_show_error_die( __( 'Widget name cannot be empty.', 'help-dialog' ) );
			}
			$widget['widget_name'] = $widget_name;

			// make sure the new Widget does not inherit locations from default Widget
			$widget['location_page_filtering'] = 'include';
			$widget['location_pages_list'] = [];
			$widget['location_posts_list'] = [];
			$widget['location_cpts_list'] = [];
			$widget['faqs_sequence'] = [];
		}

		// pass into JS the new Widget as a new settings box
		$widgets_page_handler = EPHD_Widgets_Display::get_widgets_page_handler( $widgets_config );
		if ( is_wp_error( $widgets_page_handler ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 67 ) );
		}

		$widget_form_config = array(
			'class'         => 'ephd-wp__widget-form ephd-wp__widget-form--active' . ( isset( $widgets_config[$widget_id] ) ? '' : ' ephd-wp__new-widget-form' ),
			'html'          => $widgets_page_handler->get_widget_form( $widget ),
			'return_html'   => true,
		);

		$hd_handler = new EPHD_Help_Dialog_View( $widget, true, true );

		wp_die( wp_json_encode( array(
			'status'        => 'success',
			'message'       => 'success',
			'widget_form'   => EPHD_HTML_Forms::admin_settings_box( $widget_form_config ),
			'preview'       => $hd_handler->output_help_dialog( true ),
			'demo_styles'   => EPHD_Help_Dialog_View::insert_widget_inline_styles( [], $widget, true ),
		) ) );
	}

	/**
	 * Return sanitized Widget data from request data
	 *
	 * @return array
	 */
	private static function get_sanitized_widget_from_input() {

		$widget = [];

		// retrieve status value, can be only public or draft
		$widget['widget_status'] = EPHD_Utilities::post( 'widget_status' );
		if ( empty( $widget['widget_status'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 69 ) );
		}
		if ( $widget['widget_status'] !== EPHD_Help_Dialog_Handler::HELP_DIALOG_STATUS_PUBLIC ) {
			$widget['widget_status'] = EPHD_Help_Dialog_Handler::HELP_DIALOG_STATUS_DRAFT;
		}

		// retrieve Widget name, it cannot be empty - allow empty name only for preview update purpose
		$widget['widget_name'] = EPHD_Utilities::post( 'widget_name' );
		if ( empty( $widget['widget_name'] ) ) {
			EPHD_Utilities::ajax_show_error_die( __( 'Widget name cannot be empty.', 'help-dialog' ) );
		}

		// retrieve Widget id (0 if it is needed to create a new Widget)
		$widget['widget_id'] = (int)EPHD_Utilities::post( 'widget_id', -1 );
		if ( $widget['widget_id'] < 0 ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 70 ) );
		}

		// retrieve search option
		$widget['search_option'] = EPHD_Utilities::post( 'search_option' );
		if ( ! in_array( $widget['search_option'], ['show_search', 'hide_search'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 73 ) );
		}

		// retrieve search posts
		$widget['search_posts'] = EPHD_Utilities::post( 'search_posts' );
		if ( ! in_array( $widget['search_posts'], ['on', 'off'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 75 ) );
		}

		// retrieve search kb
		if ( EPHD_KB_Core_Utilities::is_kb_or_amag_enabled() ) {
			$widget['search_kb'] = EPHD_Utilities::post( 'search_kb' );
			if ( empty( $widget['search_kb'] ) ) {
				EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 76 ) );
			}
		}

		// retrieve type of the page filtering
		$widget['location_page_filtering'] = EPHD_Utilities::post( 'location_page_filtering' );

		// retrieve 'page' type of Locations
		$widget['location_pages_list'] = EPHD_Utilities::post( 'location_pages_list', [] );
		if ( ! is_array( $widget['location_pages_list'] ) ) {
			$widget['location_pages_list'] = array();
		}

		// retrieve 'post' type of Locations
		$widget['location_posts_list'] = EPHD_Utilities::post( 'location_posts_list', [] );
		if ( ! is_array( $widget['location_posts_list'] ) ) {
			$widget['location_posts_list'] = array();
		}

		// retrieve 'cpt' type of Locations
		$widget['location_cpts_list'] = EPHD_Utilities::post( 'location_cpts_list', [] );
		if ( ! is_array( $widget['location_cpts_list'] ) ) {
			$widget['location_cpts_list'] = array();
		}

		// retrieve language location filtering
		$widget['location_language_filtering'] = EPHD_Utilities::post( 'location_language_filtering' );

		// retrieve faqs_sequence
		$widget['faqs_sequence'] = EPHD_Utilities::post( 'faqs_sequence', [] );
		if ( ! is_array( $widget['faqs_sequence'] ) ) {
			$widget['faqs_sequence'] = array();
		}

		// retrieve Initial Message settings
		$initial_message_id = (int)EPHD_Utilities::post( 'initial_message_id' );
		$initial_message_text = EPHD_Utilities::post( 'initial_message_text', '', 'wp_editor' );
		$initial_message_image_url = EPHD_Utilities::post( 'initial_message_image_url' );
		$widget['initial_message_id'] = ! isset( $widget['initial_message_text'] ) || empty( $widget['initial_message_image_url'] ) || $initial_message_text != $widget['initial_message_text'] || $initial_message_image_url != $widget['initial_message_image_url']
			? current_time( 'timestamp', true )
			: $initial_message_id;
		$widget['initial_message_toggle'] = EPHD_Utilities::post( 'initial_message_toggle' );
		$widget['initial_message_text'] = $initial_message_text;
		$widget['initial_message_mode'] = EPHD_Utilities::post( 'initial_message_mode' );
		$widget['initial_message_image_url'] = EPHD_Utilities::post( 'initial_message_image_url' );

		// retrieve Triggers settings
		$widget['trigger_delay_toggle'] = EPHD_Utilities::post( 'trigger_delay_toggle' );
		$widget['trigger_delay_seconds'] = EPHD_Utilities::post( 'trigger_delay_seconds' );
		$widget['trigger_scroll_toggle'] = EPHD_Utilities::post( 'trigger_scroll_toggle' );
		$widget['trigger_scroll_percent'] = EPHD_Utilities::post( 'trigger_scroll_percent' );
		/*$widget['trigger_days_and_hours_toggle'] = EPHD_Utilities::post( 'trigger_days_and_hours_toggle' );
		$widget['trigger_days'] = EPHD_Utilities::post( 'trigger_days' );
		$widget['trigger_hours_from'] = EPHD_Utilities::post( 'trigger_hours_from' );
		$widget['trigger_hours_to'] = EPHD_Utilities::post( 'trigger_hours_to' );*/

		// retrieve Launcher Mode
		$widget['launcher_mode'] = EPHD_Utilities::post( 'launcher_mode' );

		// retrieve Launcher Icon
		$widget['launcher_icon'] = EPHD_Utilities::post( 'launcher_icon' );

		// retrieve Launcher Location
		$widget['launcher_location'] = EPHD_Utilities::post( 'launcher_location' );

		// retrieve Launcher Text
		$widget['launcher_text'] = EPHD_Utilities::post( 'launcher_text' );

		// retrieve Launcher Bottom Distance
		$widget['launcher_bottom_distance'] = EPHD_Utilities::post( 'launcher_bottom_distance' );

		// retrieve Show/Hide Launcher Powered By Text
		$widget['launcher_powered_by'] = EPHD_Utilities::post( 'launcher_powered_by' );

		// retrieve FAQs Tab display mode
		$widget['display_faqs_tab'] = EPHD_Utilities::post( 'display_faqs_tab' );

		// retrieve Contact Form Tab display mode
		$widget['display_contact_tab'] = EPHD_Utilities::post( 'display_contact_tab' );

		// retrieve Chat Tab display mode
		$widget['display_channels_tab'] = EPHD_Utilities::post( 'display_channels_tab' );

		// retrieve Name Input
		$widget['contact_name_toggle'] = EPHD_Utilities::post( 'contact_name_toggle' );

		// retrieve Subject Input
		$widget['contact_subject_toggle'] = EPHD_Utilities::post( 'contact_subject_toggle' );

		// retrieve Acceptance Checkbox
		$widget['contact_acceptance_checkbox'] = EPHD_Utilities::post( 'contact_acceptance_checkbox' );

		// retrieve Chat Welcome Text
		$widget['chat_welcome_text'] = EPHD_Utilities::post( 'chat_welcome_text' );

		// retrieve Acceptance Title toggle
		$widget['contact_acceptance_title_toggle'] = EPHD_Utilities::post( 'contact_acceptance_title_toggle' );

		$widget['channel_phone_label'] = EPHD_Utilities::post( 'channel_phone_label' );
		$widget['channel_whatsapp_label'] = EPHD_Utilities::post( 'channel_whatsapp_label' );
		$widget['channel_custom_link_label'] = EPHD_Utilities::post( 'channel_custom_link_label' );

		// Labels
		$widget['channel_header_top_tab'] = EPHD_Utilities::post( 'channel_header_top_tab' );
		$widget['channel_header_title'] = EPHD_Utilities::post( 'channel_header_title', '', 'wp_editor' );
		$widget['channel_header_sub_title'] = EPHD_Utilities::post( 'channel_header_sub_title', '', 'wp_editor' );
		$widget['faqs_top_tab'] = EPHD_Utilities::post( 'faqs_top_tab' );
		$widget['contact_us_top_tab'] = EPHD_Utilities::post( 'contact_us_top_tab' );
		$widget['welcome_title'] = EPHD_Utilities::post( 'welcome_title', '', 'wp_editor' );
		$widget['welcome_text'] = EPHD_Utilities::post( 'welcome_text', '', 'wp_editor' );
		$widget['search_input_placeholder'] = EPHD_Utilities::post( 'search_input_placeholder' );
		$widget['article_read_more_text'] = EPHD_Utilities::post( 'article_read_more_text' );
		$widget['search_results_title'] = EPHD_Utilities::post( 'search_results_title' );
		$widget['breadcrumb_home_text'] = EPHD_Utilities::post( 'breadcrumb_home_text' );
		$widget['breadcrumb_search_result_text'] = EPHD_Utilities::post( 'breadcrumb_search_result_text' );
		$widget['breadcrumb_article_text'] = EPHD_Utilities::post( 'breadcrumb_article_text' );
		$widget['found_faqs_tab_text'] = EPHD_Utilities::post( 'found_faqs_tab_text' );
		$widget['found_articles_tab_text'] = EPHD_Utilities::post( 'found_articles_tab_text' );
		$widget['found_posts_tab_text'] = EPHD_Utilities::post( 'found_posts_tab_text' );
		$widget['no_results_found_title_text'] = EPHD_Utilities::post( 'no_results_found_title_text' );
		$widget['protected_article_placeholder_text'] = EPHD_Utilities::post( 'protected_article_placeholder_text' );
		$widget['search_input_label'] = EPHD_Utilities::post( 'search_input_label' );
		$widget['search_instruction_text'] = EPHD_Utilities::post( 'search_instruction_text' );
		$widget['no_result_contact_us_text'] = EPHD_Utilities::post( 'no_result_contact_us_text' );

		// Other config
		$widget['launcher_start_wait'] = EPHD_Utilities::post( 'launcher_start_wait' );

		// skip updating colors if predefined colors is selected
		$colors_set = EPHD_Utilities::post( 'colors_set' );

		// retrieve selected Style Feature
		$dialog_width_id = EPHD_Utilities::post( 'dialog_width' );
		if ( empty( $colors_set ) ) {
			// Colors
			$widget['launcher_background_color'] = EPHD_Utilities::post( 'launcher_background_color' );
			$widget['launcher_background_hover_color'] = EPHD_Utilities::post( 'launcher_background_hover_color' );
			$widget['launcher_icon_color'] = EPHD_Utilities::post( 'launcher_icon_color' );
			$widget['launcher_icon_hover_color'] = EPHD_Utilities::post( 'launcher_icon_hover_color' );
			$widget['background_color'] = EPHD_Utilities::post( 'background_color' );
			$widget['not_active_tab_color'] = EPHD_Utilities::post( 'not_active_tab_color' );
			$widget['tab_text_color'] = EPHD_Utilities::post( 'tab_text_color' );
			$widget['main_title_text_color'] = EPHD_Utilities::post( 'main_title_text_color' );
			$widget['welcome_title_color'] = EPHD_Utilities::post( 'welcome_title_color' );
			$widget['welcome_title_link_color'] = EPHD_Utilities::post( 'welcome_title_link_color' );
			$widget['found_faqs_article_active_tab_color'] = EPHD_Utilities::post( 'found_faqs_article_active_tab_color' );
			$widget['found_faqs_article_tab_color'] = EPHD_Utilities::post( 'found_faqs_article_tab_color' );
			$widget['article_post_list_title_color'] = EPHD_Utilities::post( 'article_post_list_title_color' );
			$widget['article_post_list_icon_color'] = EPHD_Utilities::post( 'article_post_list_icon_color' );
			$widget['breadcrumb_color'] = EPHD_Utilities::post( 'breadcrumb_color' );
			$widget['breadcrumb_background_color'] = EPHD_Utilities::post( 'breadcrumb_background_color' );
			$widget['breadcrumb_arrow_color'] = EPHD_Utilities::post( 'breadcrumb_arrow_color' );
			$widget['faqs_qa_border_color'] = EPHD_Utilities::post( 'faqs_qa_border_color' );
			$widget['faqs_question_text_color'] = EPHD_Utilities::post( 'faqs_question_text_color' );
			$widget['faqs_question_background_color'] = EPHD_Utilities::post( 'faqs_question_background_color' );
			$widget['faqs_question_active_text_color'] = EPHD_Utilities::post( 'faqs_question_active_text_color' );
			$widget['faqs_question_active_background_color'] = EPHD_Utilities::post( 'faqs_question_active_background_color' );
			$widget['faqs_answer_text_color'] = EPHD_Utilities::post( 'faqs_answer_text_color' );
			$widget['faqs_answer_background_color'] = EPHD_Utilities::post( 'faqs_answer_background_color' );
			$widget['single_article_read_more_text_color'] = EPHD_Utilities::post( 'single_article_read_more_text_color' );
			$widget['single_article_read_more_text_hover_color'] = EPHD_Utilities::post( 'single_article_read_more_text_hover_color' );
			$widget['back_text_color'] = EPHD_Utilities::post( 'back_text_color' );
			$widget['back_text_color_hover_color'] = EPHD_Utilities::post( 'back_text_color_hover_color' );
			$widget['back_background_color'] = EPHD_Utilities::post( 'back_background_color' );
			$widget['back_background_color_hover_color'] = EPHD_Utilities::post( 'back_background_color_hover_color' );
			$widget['contact_submit_button_color'] = EPHD_Utilities::post( 'contact_submit_button_color' );
			$widget['contact_submit_button_hover_color'] = EPHD_Utilities::post( 'contact_submit_button_hover_color' );
			$widget['contact_submit_button_text_color'] = EPHD_Utilities::post( 'contact_submit_button_text_color' );
			$widget['contact_submit_button_text_hover_color'] = EPHD_Utilities::post( 'contact_submit_button_text_hover_color' );
			$widget['contact_acceptance_background_color'] = EPHD_Utilities::post( 'contact_acceptance_background_color' );
			$widget['channel_phone_color'] = EPHD_Utilities::post( 'channel_phone_color' );
			$widget['channel_phone_hover_color'] = EPHD_Utilities::post( 'channel_phone_hover_color' );
			$widget['channel_whatsapp_color'] = EPHD_Utilities::post( 'channel_whatsapp_color' );
			$widget['channel_whatsapp_hover_color'] = EPHD_Utilities::post( 'channel_whatsapp_hover_color' );
			$widget['channel_link_color'] = EPHD_Utilities::post( 'channel_link_color' );
			$widget['channel_link_hover_color'] = EPHD_Utilities::post( 'channel_link_hover_color' );

			$widget['channel_label_color'] = EPHD_Utilities::post( 'channel_label_color' );
		} else {
			$widget = EPHD_Premade_Designs::get_premade_design( $colors_set, $dialog_width_id, $widget );
		}

		$widget['contact_title_header'] = EPHD_Utilities::post( 'contact_title_header' );
		$widget['contact_welcome_title'] = EPHD_Utilities::post( 'contact_welcome_title', '', 'wp_editor' );
		$widget['contact_welcome_text'] = EPHD_Utilities::post( 'contact_welcome_text', '', 'wp_editor' );
		$widget['contact_name_text'] = EPHD_Utilities::post( 'contact_name_text' );
		$widget['contact_user_email_text'] = EPHD_Utilities::post( 'contact_user_email_text' );
		$widget['contact_subject_text'] = EPHD_Utilities::post( 'contact_subject_text' );
		$widget['contact_comment_text'] = EPHD_Utilities::post( 'contact_comment_text' );
		$widget['contact_acceptance_text'] = EPHD_Utilities::post( 'contact_acceptance_text', '', 'wp_editor' );
		$widget['contact_acceptance_title'] = EPHD_Utilities::post( 'contact_acceptance_title' );
		$widget['contact_button_title'] = EPHD_Utilities::post( 'contact_button_title' );
		$widget['contact_success_message'] = EPHD_Utilities::post( 'contact_success_message', '', 'wp_editor' );

		$widget['channel_phone_toggle'] = EPHD_Utilities::post( 'channel_phone_toggle' );
		$widget['channel_phone_country_code'] = EPHD_Utilities::post( 'channel_phone_country_code' );
		$widget['channel_phone_number'] = EPHD_Utilities::post( 'channel_phone_number' );
		$widget['channel_phone_number_image_url'] = EPHD_Utilities::post( 'channel_phone_number_image_url' );
		$widget['channel_whatsapp_toggle'] = EPHD_Utilities::post( 'channel_whatsapp_toggle' );
		$widget['channel_whatsapp_phone_country_code'] = EPHD_Utilities::post( 'channel_whatsapp_phone_country_code' );
		$widget['channel_whatsapp_phone_number'] = EPHD_Utilities::post( 'channel_whatsapp_phone_number' );
		$widget['channel_whatsapp_web_on_desktop'] = EPHD_Utilities::post( 'channel_whatsapp_web_on_desktop' );
		$widget['channel_whatsapp_welcome_message'] = EPHD_Utilities::post( 'channel_whatsapp_welcome_message' );
		$widget['channel_whatsapp_number_image_url'] = EPHD_Utilities::post( 'channel_whatsapp_number_image_url' );
		$widget['channel_custom_link_toggle'] = EPHD_Utilities::post( 'channel_custom_link_toggle' );
		$widget['channel_custom_link_url'] = EPHD_Utilities::post( 'channel_custom_link_url' );
		$widget['channel_custom_link_image_url'] = EPHD_Utilities::post( 'channel_custom_link_image_url' );
		$widget['article_back_button_text'] = EPHD_Utilities::post( 'article_back_button_text' );

		// retrieve custom options
		$widget = apply_filters( 'ephd_admin_widget_feature_option_sanitize', $widget );

		return $widget;
	}

	/**
	 * Retrieve updated Global config from request
	 *
	 * @param $global_config
	 *
	 * @return array|null
	 */
	private static function get_sanitized_global_form_from_input( $global_config ) {

		/* TODO hide for now
		// retrieve Desktop Width
		$global_config['container_desktop_width'] = EPHD_Utilities::post( 'container_desktop_width' );

		// retrieve Table Width
		$global_config['container_tablet_width'] = EPHD_Utilities::post( 'container_tablet_width' );

		// retrieve Tablet Break Point
		$global_config['tablet_break_point'] = EPHD_Utilities::post( 'tablet_break_point' );
		*/

		// retrieve Mobile Break Point
		$global_config['mobile_break_point'] = EPHD_Utilities::post( 'mobile_break_point' );

		// retrieve Tabs Sequence
		$global_config['tabs_sequence'] = EPHD_Utilities::post( 'tabs_sequence' );

		// retrieve Preview Mode
		$global_config['preview_post_mode'] = EPHD_Utilities::post( 'preview_post_mode' );

		if ( EPHD_KB_Core_Utilities::is_kb_or_amag_enabled() ) {
			$global_config['preview_kb_mode'] = EPHD_Utilities::post( 'preview_kb_mode' );
		}

		// retrieve selected Style Feature
		$dialog_width_id = EPHD_Utilities::post( 'dialog_width' );

		// retrieve logo image url
		$global_config['logo_image_url'] = EPHD_Utilities::post( 'logo_image_url' );
		$global_config['logo_image_width'] = EPHD_Utilities::post( 'logo_image_width' );

		// get selected theme settings
		return EPHD_Premade_Designs::get_premade_global_config( $dialog_width_id, $global_config );
	}

	/**
	 * Perform search for certain type of Locations
	 */
	public function search_locations() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		// retrieve post type
		$locations_type = EPHD_Utilities::post( 'locations_type' );
		if ( empty( $locations_type ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 78 ) );
		}

		// retrieve search value
		$search_value = EPHD_Utilities::post( 'search_value' );

		// retrieve excluded Location ids
		$excluded_ids = EPHD_Utilities::post( 'excluded_ids', [] );
		if ( ! is_array( $excluded_ids ) ) {
			$excluded_ids = array();
		}

		// retrieve include/exclude option
		$location_page_filtering = EPHD_Utilities::post( 'location_page_filtering', 'include' );

		$widgets_page_handler = EPHD_Widgets_Display::get_widgets_page_handler();
		if ( is_wp_error( $widgets_page_handler ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 77 ) );
		}

		// retrieve widget id
		$widget_id = EPHD_Utilities::post( 'widget_id' );
		if ( empty( $widget_id ) ) {
			$widget_id = 0;
		}

		$language = EPHD_Utilities::post( 'location_language_filtering', 'all' );

		wp_die( wp_json_encode( array(
			'status'        => 'success',
			'message'       => 'success',
			'locations'     => $widgets_page_handler->get_available_locations_list( $locations_type, true, $search_value, $excluded_ids, $location_page_filtering, $widget_id, $language ),
		) ) );
	}

	/**
	 * Copy current Widget Design to another Widget Design
	 *
	 */
	/* Future code
	public function copy_design_to() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		// retrieve target Design id - only existing Design allowed
		$target_design_id = (int)EPHD_Utilities::post( 'target_design_id' );
		if ( empty( $target_design_id ) || ! isset( $designs_config[$target_design_id] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 81 ) );
		}

		// retrieve Design id - either existing Design or default Design (when copy from a newly creating Widget that is not saved yet)
		$current_design_id = (int)EPHD_Utilities::post( 'current_design_id' );
		if ( ! isset( $designs_config[$current_design_id] ) && $current_design_id != 1 ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 82 ) );
		}

		// retrieve current Design
		$current_design = isset( $designs_config[$current_design_id] )
			? $designs_config[$current_design_id]
			: $designs_config[EPHD_Config_Specs::DEFAULT_ID];

		// copy current Widget Design to the target Widget Design
		$designs_config[$target_design_id] = $current_design;

		// copy the Design id
		$designs_config[$target_design_id]['design_id'] = $target_design_id;

		wp_die( wp_json_encode( array(
			'status'    => 'success',
			'message'   => esc_html__( 'Design Copied', 'help-dialog' ),
		) ) );
	}
*/
	/**
	 * Save setting input from Tiny MCE editor
	 */
	public function tiny_mce_input_save() {

		// die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_capability_or_error_die( EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ) );

		// retrieve option name - allow only certain option names
		$option_name = EPHD_Utilities::post( 'option_name' );
		if ( ! in_array( $option_name, ['no_results_found_content_html'] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 86 ) );
		}

		// retrieve Design id
		$widget_id = (int)EPHD_Utilities::post( 'widget_id', -1 );
		if ( $widget_id < 1 ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 87 ) );
		}

		// retrieve configuration for all Designs
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $widgets_config ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 88 ) );
		}

		if ( empty( $widgets_config[$widget_id] ) ) {
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 90 ) );
		}

		// retrieve specification for Design fields
		$widget_specs = EPHD_Config_Specs::get_fields_specification( EPHD_Widgets_DB::EPHD_WIDGETS_CONFIG_NAME );

		// retrieve option name - allow only certain option names
		$max_option_length = intval( $widget_specs[$option_name]['max'] );
		$option_value = EPHD_Utilities::post( 'option_value', '', $widget_specs[$option_name]['type'], $max_option_length );

		// update option
		$widgets_config[$widget_id][$option_name] = $option_value;

		// update Designs configuration
		$updated_widgets_config = ephd_get_instance()->widgets_config_obj->update_config( $widgets_config );
		if ( is_wp_error( $updated_widgets_config ) ) {
			EPHD_Logging::add_log( 'Error occurred on saving Designs configuration. (89)' );
			EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 89, $updated_widgets_config ) );
		}

		wp_die( wp_json_encode( array(
			'status'    => 'success',
			'message'   => esc_html__( 'Configuration Saved', 'help-dialog' ),
		) ) );
	}
}
