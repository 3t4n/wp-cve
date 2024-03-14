<?php
defined( 'ABSPATH' ) || exit;
/**
 * Class for admin Event meta boxes
 */
class EventM_Event_Admin_Meta_Boxes {
	/**
	 * Is meta boxes saved once?
	 *
	 * @var boolean
	 */
	private static $ep_saved_event_meta_boxes = false;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_event_meta_box_scripts' ) );
		add_action( 'add_meta_boxes', array( $this, 'ep_event_remove_meta_boxes' ), 10 );
		add_action( 'add_meta_boxes', array( $this, 'ep_event_register_meta_boxes' ), 1 );
		add_action( 'save_post', array( $this, 'ep_save_event_meta_boxes' ), 1, 2 );
		add_filter( 'manage_em_event_posts_columns', array( $this, 'ep_filter_event_columns' ) );
		add_action( 'manage_em_event_posts_custom_column', array( $this, 'ep_filter_event_columns_content' ), 10, 2 );
		add_filter( 'manage_edit-em_event_sortable_columns', array( $this, 'ep_sortable_event_columns'), 10, 1 );
		add_action( 'pre_get_posts', array( $this, 'ep_sort_events_date' ), 10, 1 );
	}

	/**
     * Enqueue meta box scripts
     */

    public function enqueue_admin_event_meta_box_scripts() {
		wp_register_script(
		    'em-meta-box-admin-custom-js',
		    EP_BASE_URL . '/includes/events/assets/js/em-admin-metabox-custom.js',
		    array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-dialog', 'jquery-ui-accordion', 'jquery-ui-sortable' ), EVENTPRIME_VERSION
        );
		wp_enqueue_media();
		wp_register_style(
            'em-meta-box-admin-custom-css',
            EP_BASE_URL . '/includes/events/assets/css/em-admin-metabox-custom.css',
            false, EVENTPRIME_VERSION
        );
		$performer_data = array();
		$performers = EventM_Factory_Service::ep_get_instance( 'EventM_Performer_Controller_List' );
		if( ! empty( $performers ) ) {
			$fields = ['id', 'name'];
			$performer_data = $performers->get_performer_field_data( $fields );
		}
		// check if attendee list extension enabled
		$enabled_attendees_list = 0;
		$extensions = EP()->extensions;
		if( ! empty( $extensions ) && in_array( 'attendees_list', $extensions ) ) {
			$enabled_attendees_list = 1;
		}
		wp_localize_script(
            'em-meta-box-admin-custom-js', 
            'em_event_meta_box_object', 
            array(
                'before_event_scheduling' => esc_html__( 'Please choose start & end date before enable scheduling!', 'eventprime-event-calendar-management' ),
                'before_event_recurrence' => esc_html__( 'Please choose start & end date before enable recurrence!', 'eventprime-event-calendar-management' ),
				'add_schedule_btn'  	  => esc_html__( 'Add New Hourly Schedule', 'eventprime-event-calendar-management' ),
				'add_day_title_label'  	  => esc_html__( 'Title', 'eventprime-event-calendar-management' ),
				'start_time_label'  	  => esc_html__( 'Start Time', 'eventprime-event-calendar-management' ),
				'end_time_label'  	   	  => esc_html__( 'End Time', 'eventprime-event-calendar-management' ),
				'description_label'  	  => esc_html__( 'Description', 'eventprime-event-calendar-management' ),
				'remove_label'  	   	  => esc_html__( 'Remove', 'eventprime-event-calendar-management' ),
				'material_icons'		  => EventM_Constants::get_material_icons(),
				'icon_text'  	   	  	  => esc_html__( 'Icon', 'eventprime-event-calendar-management' ),
				'icon_color_text'  	   	  => esc_html__( 'Icon Color', 'eventprime-event-calendar-management' ),
				'performers_data'		  => $performer_data,
				'additional_date_text' 	  => esc_html__( 'Date', 'eventprime-event-calendar-management' ),
				'additional_time_text' 	  => esc_html__( 'Time', 'eventprime-event-calendar-management' ),
				'optional_text' 	  	  => esc_html__( '(Optional)', 'eventprime-event-calendar-management' ),
				'additional_label_text'   => esc_html__( 'Label', 'eventprime-event-calendar-management' ),
				'countdown_activate_text' => esc_html__( 'Activates', 'eventprime-event-calendar-management' ),
				'countdown_activated_text'=> esc_html__( 'Activated', 'eventprime-event-calendar-management' ),
				'countdown_on_text'		  => esc_html__( 'On', 'eventprime-event-calendar-management' ),
				'countdown_ends_text'     => esc_html__( 'Ends', 'eventprime-event-calendar-management' ),
				'countdown_activates_on'  => array( 'right_away' => esc_html__( 'Right Away', 'eventprime-event-calendar-management' ), 'custom_date' => esc_html__( 'Custom Date', 'eventprime-event-calendar-management' ), 'event_date' => esc_html__( 'Event Date', 'eventprime-event-calendar-management' ), 'relative_date' => esc_html__( 'Relative Date', 'eventprime-event-calendar-management' ) ),
				'countdown_days_options'  => array( 'before' => esc_html__( 'Days Before', 'eventprime-event-calendar-management' ), 'after' => esc_html__( 'Days After', 'eventprime-event-calendar-management' ) ),
				'countdown_event_options' => array( 'event_start' => esc_html__( 'Event Start', 'eventprime-event-calendar-management' ), 'event_ends' => esc_html__( 'Event Ends', 'eventprime-event-calendar-management' ) ),
				'ticket_capacity_text'    => esc_html__( 'Capacity', 'eventprime-event-calendar-management' ),
				'add_ticket_text'    	  => esc_html__( 'Add Ticket Type', 'eventprime-event-calendar-management' ),
				'add_text'    	  		  => esc_html__( 'Add', 'eventprime-event-calendar-management' ),
				'edit_text'    	  		  => esc_html__( 'Edit', 'eventprime-event-calendar-management' ),
				'update_text'    	  	  => esc_html__( 'Update', 'eventprime-event-calendar-management' ),
				'add_ticket_category_text'=> esc_html__( 'Add Tickets Category', 'eventprime-event-calendar-management' ),
				'price_text'			  => esc_html__( 'Fee Per Ticket', 'eventprime-event-calendar-management' ),
				'offer_text'			  => esc_html__( 'Offer', 'eventprime-event-calendar-management' ),
				'no_ticket_found_error'   => esc_html__( 'You have not added any tickets for this event. Therefore, bookings for this event will be turned off.', 'eventprime-event-calendar-management' ),
				'max_capacity_error'      => esc_html__( 'Max allowed capacity is', 'eventprime-event-calendar-management' ),
				'max_less_then_min_error' => esc_html__( 'Maximum tickets number can\'t be less then minimum tickets number.', 'eventprime-event-calendar-management' ),
				'required_text'			  => esc_html__( 'Required', 'eventprime-event-calendar-management' ),
				'one_checkout_field_req'  => esc_html__( 'Please select atleast one attendee field.', 'eventprime-event-calendar-management' ),
				'no_name_field_option'    => esc_html__( 'Please select name field option.', 'eventprime-event-calendar-management' ),
				'some_issue_found'    	  => esc_html__( 'Some issue found. Please refresh the page and try again later.', 'eventprime-event-calendar-management' ),
				'fixed_field_not_selected'=> esc_html__( 'Please selecte booking field.', 'eventprime-event-calendar-management' ),
				'fixed_field_term_option_required'=> esc_html__( 'Please select one terms option.', 'eventprime-event-calendar-management' ),
				'repeat_child_event_prompt'=> esc_html__( 'The event have multiple recurrences. They will be deleted after update event.', 'eventprime-event-calendar-management' ),
				'empty_event_title'		  => esc_html__( 'Event title is required.', 'eventprime-event-calendar-management' ),
				'empty_start_date'		  => esc_html__( 'Event start date is required.', 'eventprime-event-calendar-management' ),
				'end_date_less_from_start'=> esc_html__( 'Event end date can not be less then event start date.', 'eventprime-event-calendar-management' ),
				'same_event_start_and_end'=> esc_html__( 'Event end date & time should not be same with event start date & time.', 'eventprime-event-calendar-management' ),
				'end_time_but_no_start_time'=> esc_html__( 'You have entered end time but not start time.', 'eventprime-event-calendar-management' ),
				'offer_not_save_error_text'=> esc_html__( 'You have an unsaved offer.', 'eventprime-event-calendar-management' ),
				'offer_per_more_then_100' => esc_html__( 'Discount value can\'t be more then 100.', 'eventprime-event-calendar-management' ),
				'all_site_data'		      => ep_get_all_pages_list(),
				'end_time_less_start_time'=> esc_html__( 'Event end time can not be less then event start time.', 'eventprime-event-calendar-management' ),
				'show_in_attendees_list_text'=> esc_html__( 'Add to Attendees List', 'eventprime-event-calendar-management' ),
				'enabled_attendees_list'  => $enabled_attendees_list,
				'one_booking_field_req'   => esc_html__( 'Please select atleast one booking field.', 'eventprime-event-calendar-management' ),
				'min_ticket_no_zero_error' => esc_html__( 'The minimum ticket quantity per order must be greater than zero.', 'eventprime-event-calendar-management' ),
				'max_ticket_no_zero_error' => esc_html__( 'The maximum ticket quantity per order must be greater than zero.', 'eventprime-event-calendar-management' ),
            )
        );
    }

	/**
	 * Remove default meta boxes
	 */
	public function ep_event_remove_meta_boxes() {
		remove_meta_box( 'postexcerpt', 'em_event', 'normal' );
		remove_meta_box( 'commentsdiv', 'em_event', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'em_event', 'side' );
		remove_meta_box( 'commentstatusdiv', 'em_event', 'normal' );
		remove_meta_box( 'postcustom', 'em_event', 'normal' );
		remove_meta_box( 'pageparentdiv', 'em_event', 'side' );
	}

	/**
	 * Register meta box for event
	 */
	public function ep_event_register_meta_boxes() {
		add_meta_box(
			'ep_event_register_meta_boxes',
			esc_html__( 'Event Settings', 'eventprime-event-calendar-management' ),
			array( $this, 'ep_add_event_setting_box' ),
			'em_event', 'normal', 'high'
		);
		add_meta_box( 
			'ep_event-stats', 
			__( 'Summary', 'eventprime-event-calendar-management' ), 
			array( $this, 'ep_add_event_stats_box' ),
			'em_event', 'side', 'high'
		);
		$plural_performer_text = ep_global_settings_button_title( 'Performers' );
		add_meta_box( 
			'ep_event-performers', 
			$plural_performer_text,
			array( $this, 'ep_add_event_performer_box' ),
			'em_event', 'side', 'low' 
		);
		add_meta_box( 
			'ep_event-gallery-images', 
			__( 'Event gallery', 'eventprime-event-calendar-management' ), 
			array( $this, 'ep_add_event_gallery_box' ),
			'em_event', 'side', 'low' 
		);
		do_action( 'ep_event_register_custom_meta_boxes' );
	}

	/**
	 * Add event setting details
	 *
	 * @param $post
	 */
	public function ep_add_event_setting_box( $post ): void {
		if( $post->post_type == 'em_event' ) {
			wp_enqueue_style( 'em-admin-jquery-ui' );
			wp_enqueue_script( 'em-admin-jscolor' );
			wp_enqueue_style( 'em-admin-select2-css' );
			wp_enqueue_script( 'em-admin-select2-js' );
			wp_enqueue_style( 'em-admin-jquery-timepicker' );
			wp_enqueue_script( 'em-admin-timepicker-js' );
			wp_enqueue_script( 'em-meta-box-admin-custom-js' );
			wp_enqueue_style( 'em-meta-box-admin-custom-css' );
			wp_enqueue_style( 'ep-toast-css' );
			wp_enqueue_script( 'ep-toast-js' );
			wp_enqueue_script( 'ep-toast-message-js' );
			// enqueue custom scripts and styles from extension
			do_action( 'ep_event_enqueue_custom_scripts' );
			wp_nonce_field( 'ep_save_event_data', 'ep_event_meta_nonce' );
			include_once __DIR__ .'/views/meta-box-panel-html.php';
		}
	}

	/**
	 * Return tabs data
	 *
	 * @return array
	 */
	private static function get_ep_event_meta_tabs() {
		$tabs = apply_filters(
			'ep_event_meta_tabs',
			array(
				'datetime'       => array(
					'label'      => esc_html__( 'Date & Time', 'eventprime-event-calendar-management' ),
					'target'     => 'ep_event_datetime_data',
					'class'      => array( 'ep_event_date_time' ),
					'priority'   => 10,
				),
				'booking'       => array(
					'label'      => esc_html__( 'Bookings', 'eventprime-event-calendar-management' ),
					'target'     => 'ep_event_booking_data',
					'class'      => array( 'ep_event_bookings' ),
					'priority'   => 20,
				),
				'ticket'       => array(
					'label'      => esc_html__( 'Tickets', 'eventprime-event-calendar-management' ),
					'target'     => 'ep_event_ticket_data',
					'class'      => array( 'ep_event_tickets' ),
					'priority'   => 30,
				),
				'recurrence'     => array(
					'label'      => esc_html__( 'Repeat', 'eventprime-event-calendar-management' ),
					'target'     => 'ep_event_recurrence_data',
					'class'      => array( 'ep_event_recurrence' ),
					'priority'   => 50,
				),
				'checkoutfields' => array(
					'label'      => esc_html__( 'Checkout Fields', 'eventprime-event-calendar-management' ),
					'target'   	 => 'ep_event_checkout_fields_data',
					'class'    	 => array( 'ep_event_checkout_fields' ),
					'priority' 	 => 70,
				),
				'social'     => array(
					'label'    => esc_html__( 'Social Information', 'eventprime-event-calendar-management' ),
					'target'   => 'ep_event_social_data',
					'class'    => array( 'ep_event_social_info' ),
					'priority' => 80,
				),
				'results' => array(
					'label'      => esc_html__( 'Results', 'eventprime-event-calendar-management' ),
					'target'   	 => 'ep_event_results_data',
					'class'    	 => array( 'ep_event_results' ),
					'priority' 	 => 90,
				),
				'othersettings' => array(
					'label'      => esc_html__( 'Other Settings', 'eventprime-event-calendar-management' ),
					'target'   	 => 'ep_event_other_settings_data',
					'class'    	 => array( 'ep_event_other_settings' ),
					'priority' 	 => 200,
				),
			)
		);
		// Sort tabs based on priority.
		uasort( $tabs, array( __CLASS__, 'event_data_tabs_sort' ) );
		return $tabs;
	}

	/**
	 * Callback to sort event data tabs on priority.
	 *
	 * @since 3.0.0
	 * @param int $a First item.
	 * @param int $b Second item.
	 *
	 * @return bool
	 */
	private static function event_data_tabs_sort( $a, $b ) {
		if ( ! isset( $a['priority'], $b['priority'] ) ) {
			return -1;
		}
		if ( $a['priority'] === $b['priority'] ) {
			return 0;
		}
		return $a['priority'] < $b['priority'] ? -1 : 1;
	}

	/**
	 * Show the tab contents
	 */
	private static function ep_event_tab_content() {
		global $post, $thepostid;
		$event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
		$single_event_data = $event_controller->get_single_event( $post->ID, $post );
		include __DIR__ .'/views/meta-box-date-panel-html.php';
		include __DIR__ .'/views/meta-box-recurrence-panel-html.php';
		include __DIR__ .'/views/meta-box-schedule-panel-html.php';
		include __DIR__ .'/views/meta-box-checkout-fields-panel-html.php';
		include __DIR__ .'/views/meta-box-countdown-panel-html.php';
		include __DIR__ .'/views/meta-box-tickets-panel-html.php';
		include __DIR__ .'/views/meta-box-other-settings-panel-html.php';
		include __DIR__ .'/views/meta-box-social-panel-html.php';
		include __DIR__ .'/views/meta-box-results-panel-html.php';
		include __DIR__ .'/views/meta-box-bookings-panel-html.php';
		do_action( 'ep_event_tab_content' );
	}

	/**
	 * Return the time list
	 */
	private static function ep_get_time_list( $start = '12:00 AM', $end = '12:00 PM', $duration = '30' ) {
		$time_list = array();
		if( empty( $start ) ) {
			$start = '12:00 AM';
		}
		$start_time = strtotime( $start );
		$end_time = strtotime( $end );
		$add_min = $duration * 60;
		while( $start_time < $end_time ) {
			if( date( 'G', $start_time ) == 0 ) {
				$time = date( "12:i A", $start_time );
			}else{
				$time = date( "G:i A", $start_time );
			}

			$time_list[] = $time;
			$start_time += $add_min;
		}
		return $time_list;
	}

	/**
	 * Return recurrence interval
	 */
	private static function ep_get_recurrence_interval() {
		$repeats = array(
			'daily' 		=> esc_html__( 'Day(s)', 'eventprime-event-calendar-management' ),
			'weekly' 		=> esc_html__( 'Week(s)', 'eventprime-event-calendar-management' ),
			'monthly' 		=> esc_html__( 'Month(s)', 'eventprime-event-calendar-management' ),
			'yearly' 		=> esc_html__( 'Year(s)', 'eventprime-event-calendar-management' ),
			'advanced' 		=> esc_html__( 'Advanced', 'eventprime-event-calendar-management' ),
			'custom_dates' 	=> esc_html__( 'Custom Dates', 'eventprime-event-calendar-management' ),
		);
		return $repeats;
	}

	/**
	 * return essentials checkout fields
	 * 
	 * @param array $event_checkout_attendee_fields Saved Attendee Fields.
	 * 
	 * @param string $is_popup Popup Text.
	 * 
	 * @return Field Html.
	 */
	private static function ep_get_checkout_essentials_fields( $event_checkout_attendee_fields = array(), $is_popup = '' ) {
		$field = '<div class="ep-form-check ep-form-check-inline ep-event-checkout-esse-name-field ep-ml-3">';
			$em_event_checkout_name_checked = ( isset( $event_checkout_attendee_fields['em_event_checkout_name'] ) && $event_checkout_attendee_fields['em_event_checkout_name'] == 1 ) ? 'checked="checked"' : '';
			$field .= '<input type="checkbox" name="em_event_checkout_name" class="ep-form-check-input" id="em_event_checkout_name'.$is_popup.'" value="1" data-label="'.esc_html__( 'Name', 'eventprime-event-calendar-management' ).'" '.$em_event_checkout_name_checked.'>';
			$field .= '<label for="em_event_checkout_name'.$is_popup.'">'.esc_html__( 'Name', 'eventprime-event-calendar-management' ).'</label>';
			$name_display = ( empty( $em_event_checkout_name_checked ) ? 'style="display:none;"' : '' );
			$field .= '<div class="ep-event-name-sub-fields ep-mt-3" '.$name_display.'>';
				$field .= self::ep_get_name_sub_fields( $event_checkout_attendee_fields, $is_popup );
			$field .= '</div>';
		$field .= '</div>';
		return $field;
	}

	/**
	 * get name sub field
	 * 
	 * @param array $event_checkout_attendee_fields Saved Attendee Fields.
	 * 
	 * @param string $is_popup Popup Text.
	 * 
	 * @return Field Html.
	 */
	private static function ep_get_name_sub_fields( $event_checkout_attendee_fields, $is_popup = '' ) {
		$field = '';
		// first name
		$em_event_checkout_name_first_name_checked = ( isset( $event_checkout_attendee_fields['em_event_checkout_name_first_name'] ) && $event_checkout_attendee_fields['em_event_checkout_name_first_name'] == 1 ) ? 'checked="checked"' : '';
		$first_name_display = ( empty( $em_event_checkout_name_first_name_checked ) ? 'style="display:none;"' : '' );
		$em_event_checkout_name_first_name_required_checked = ( isset( $event_checkout_attendee_fields['em_event_checkout_name_first_name_required'] ) && $event_checkout_attendee_fields['em_event_checkout_name_first_name_required'] == 1 ) ? 'checked="checked"' : '';
		$field .= '<div class="ep-form-check ep-sub-field-first-name ep-mb-2">';
			$field .= '<div class="ep-form-check-wrap ep-di-flex ep-items-center"><input type="checkbox" class="ep-form-check-input ep-name-sub-fields" data-field_type="first-name" name="em_event_checkout_name_first_name" id="em_event_checkout_name_first_name'.$is_popup.'" value="1" data-label="'.esc_html__( 'First Name', 'eventprime-event-calendar-management' ).'" '.$em_event_checkout_name_first_name_checked.'>';
			$field .= '<label for="em_event_checkout_name_first_name'.$is_popup.'" class="ep-form-label">'.esc_html__( 'First Name', 'eventprime-event-calendar-management' ).'</label></div>';
			$field .= '<label for="em_event_checkout_name_first_name_required'.$is_popup.'" class="ep-form-label ep-ml-3 ep-first-name-required" '.$first_name_display.'>';
				$field .= '<input type="checkbox" name="em_event_checkout_name_first_name_required" id="em_event_checkout_name_first_name_required'.$is_popup.'" value="1" data-label="'.esc_html__( 'Required', 'eventprime-event-calendar-management' ).'" '.$em_event_checkout_name_first_name_required_checked.'>'.esc_html__( 'Required', 'eventprime-event-calendar-management' );
			$field .= '</label>';
		$field .= '</div>';
		// middle name
		$em_event_checkout_name_middle_name_checked = ( isset( $event_checkout_attendee_fields['em_event_checkout_name_middle_name'] ) && $event_checkout_attendee_fields['em_event_checkout_name_middle_name'] == 1 ) ? 'checked="checked"' : '';
		$middle_name_display = ( empty( $em_event_checkout_name_middle_name_checked ) ? 'style="display:none;"' : '' );
		$em_event_checkout_name_middle_name_required_checked = ( isset( $event_checkout_attendee_fields['em_event_checkout_name_middle_name_required'] ) && $event_checkout_attendee_fields['em_event_checkout_name_middle_name_required'] == 1 ) ? 'checked="checked"' : '';
		$field .= '<div class="ep-form-check ep-sub-field-middle-name ep-mb-2">';
			$field .= '<div class="ep-form-check-wrap ep-di-flex ep-items-center"><input type="checkbox" class="ep-form-check-input ep-name-sub-fields" data-field_type="middle-name" name="em_event_checkout_name_middle_name" id="em_event_checkout_name_middle_name'.$is_popup.'" value="1" data-label="'.esc_html__( 'Middle Name', 'eventprime-event-calendar-management' ).'" '.$em_event_checkout_name_middle_name_checked.'>';
			$field .= '<label for="em_event_checkout_name_middle_name'.$is_popup.'" class="ep-form-label">'.esc_html__( 'Middle Name', 'eventprime-event-calendar-management' ).'</label></div>';
			$field .= '<label for="em_event_checkout_name_middle_name_required'.$is_popup.'" class="ep-form-label ep-ml-3 ep-middle-name-required" '.$middle_name_display.'>';
				$field .= '<input type="checkbox" name="em_event_checkout_name_middle_name_required" id="em_event_checkout_name_middle_name_required'.$is_popup.'" value="1" data-label="'.esc_html__( 'Required', 'eventprime-event-calendar-management' ).'" '.$em_event_checkout_name_middle_name_required_checked.'>'.esc_html__( 'Required', 'eventprime-event-calendar-management' );
			$field .= '</label>';
		$field .= '</div>';
		// last name
		$em_event_checkout_name_last_name_checked = ( isset( $event_checkout_attendee_fields['em_event_checkout_name_last_name'] ) && $event_checkout_attendee_fields['em_event_checkout_name_last_name'] == 1 ) ? 'checked="checked"' : '';
		$last_name_display = ( empty( $em_event_checkout_name_last_name_checked ) ? 'style="display:none;"' : '' );
		$em_event_checkout_name_last_name_required_checked = ( isset( $event_checkout_attendee_fields['em_event_checkout_name_last_name_required'] ) && $event_checkout_attendee_fields['em_event_checkout_name_last_name_required'] == 1 ) ? 'checked="checked"' : '';
		$field .= '<div class="ep-form-check ep-sub-field-last-name ep-mb-2">';
			$field .= '<div class="ep-form-check-wrap ep-di-flex ep-items-center"><input type="checkbox" class="ep-form-check-input ep-name-sub-fields" data-field_type="last-name" name="em_event_checkout_name_last_name" id="em_event_checkout_name_last_name'.$is_popup.'" value="1" data-label="'.esc_html__( 'Last Name', 'eventprime-event-calendar-management' ).'" '.$em_event_checkout_name_last_name_checked.'>';
			$field .= '<label for="em_event_checkout_name_last_name'.$is_popup.'" class="ep-form-label">'.esc_html__( 'Last Name', 'eventprime-event-calendar-management' ).'</label></div>';
			$field .= '<label for="em_event_checkout_name_last_name_required'.$is_popup.'" class="ep-form-label ep-ml-3 ep-last-name-required" '.$last_name_display.'>';
				$field .= '<input type="checkbox" name="em_event_checkout_name_last_name_required" id="em_event_checkout_name_last_name_required'.$is_popup.'" value="1" data-label="'.esc_html__( 'Required', 'eventprime-event-calendar-management' ).'" '.$em_event_checkout_name_last_name_required_checked.'>'.esc_html__( 'Required', 'eventprime-event-calendar-management' );
			$field .= '</label>';
		$field .= '</div>';
		return $field;
	}

	/**
	 * return fixed checkout fields
	 */
	private static function ep_get_checkout_fixed_fields( $em_event_checkout_fixed_fields = array() ) {
		$terms_check = ( ! empty( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_enabled'] ) ? 'checked="checked"' : '' );
		$display_check = ( ! empty( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_enabled'] ) ? '' : 'style="display:none;"' );
		$term_label = ( ! empty( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_label'] ) ? $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_label'] : '' );
		$field = '<div class="ep-event-checkout-fixed-field ep-box-row">';
			$field .= '<div class="ep-box-col-12 ep-mt-3"><div class="ep-form-check"><label class="ep-form-label" for="em_event_checkout_fixed_terms">';
				$field .= '<input type="checkbox" name="em_event_checkout_fixed_terms" id="em_event_checkout_fixed_terms" class="ep-form-check-input" value="1" data-label="'.esc_html__( 'Terms & Conditions', 'eventprime-event-calendar-management' ).'" '.esc_attr( $terms_check ).'>'.esc_html__( 'Terms & Conditions', 'eventprime-event-calendar-management' );
			$field .= '</label></div></div>';
			$field .= '<div class="ep-box-col-12 ep-mt-3 ep-event-terms-sub-fields" '.$display_check.'>';
				$field .= '<input type="text" name="em_event_checkout_terms_label" class="ep-form-control" id="em_event_checkout_terms_label" placeholder="'.esc_html__( 'Enter Label', 'eventprime-event-calendar-management' ).'" value="'.esc_attr( $term_label ).'">';
				$field .= '<div class="ep-error-message" id="ep_fixed_field_label_error"></div>';
				$field .= self::ep_get_terms_sub_fields( $em_event_checkout_fixed_fields );
			$field .= '</div>';
		$field .= '</div>';
		return $field;
	}

	/**
	 * get terms sub field
	 */
	private static function ep_get_terms_sub_fields( $em_event_checkout_fixed_fields ) {
		$field = '';
		$term_option = ( ! empty( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_option'] ) ? $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_option'] : '' );
		$term_content = ( ! empty( $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_content'] ) ? $em_event_checkout_fixed_fields['em_event_checkout_fixed_terms_content'] : '' );
		// select page option
		$field .= '<div class="ep-sub-field-terms-page ep-box-row ep-mt-3">';
			$field .= '<div class="ep-box-col-1 "><label for="em_event_checkout_terms_page_option">';
				if( $term_option == 'page' ) {
					$field .= '<input type="radio" class="ep-terms-sub-fields" data-terms_type="page" name="em_event_checkout_terms_option" id="em_event_checkout_terms_page_option" value="page" data-label="'.esc_html__( 'Select Page', 'eventprime-event-calendar-management' ).'" checked>';
				} else{
					$field .= '<input type="radio" class="ep-terms-sub-fields" data-terms_type="page" name="em_event_checkout_terms_option" id="em_event_checkout_terms_page_option" value="page" data-label="'.esc_html__( 'Select Page', 'eventprime-event-calendar-management' ).'">';
				}
			$field .= '</label></div>';
			$field .= '<div class="ep-box-col-11 ep-sub-field-terms-page-options">';
				if( $term_option == 'page' ) {
					$field .= '<select name="em_event_checkout_terms_page" id="em_event_checkout_terms_page" class="ep-form-control ep-event-terms-options">';
				} else{
					$field .= '<select name="em_event_checkout_terms_page" id="em_event_checkout_terms_page" class="ep-form-control ep-event-terms-options" disabled>';
				}
					$field .= '<option value="">'.esc_html__( 'Select Page', 'eventprime-event-calendar-management' ).'</option>';
					foreach( ep_get_all_pages_list() as $page_id => $page_title ){
						if( $term_option == 'page' && is_int( $term_content ) && $term_content == $page_id ) {
							$field .= '<option value="'.$page_id.'" selected>'.$page_title.'</option>';
						} else{
							$field .= '<option value="'.$page_id.'">'.$page_title.'</option>';
						}
					}
				$field .= '</select>';
				$field .= '<div class="ep-error-message" id="ep_fixed_field_page_option_error"></div>';
			$field .= '</div>';
		$field .= '</div>';

		// enter external url option
		$field .= '<div class="ep-sub-field-terms-url ep-box-row ep-mt-3">';
			$field .= '<div class="ep-box-col-1 "><label for="em_event_checkout_terms_url_option">';
				if( $term_option == 'url' ) {
					$field .= '<input type="radio" class="ep-terms-sub-fields" data-terms_type="url" name="em_event_checkout_terms_option" id="em_event_checkout_terms_url_option" value="url" data-label="'.esc_html__( 'Enter URL', 'eventprime-event-calendar-management' ).'" checked>';
				} else{
					$field .= '<input type="radio" class="ep-terms-sub-fields" data-terms_type="url" name="em_event_checkout_terms_option" id="em_event_checkout_terms_url_option" value="url" data-label="'.esc_html__( 'Enter URL', 'eventprime-event-calendar-management' ).'">';
				}
			$field .= '</label></div>';
			$field .= '<div class="ep-box-col-11 ep-sub-field-terms-url-options">';
				if( $term_option == 'url' ) {
					$field .= '<input type="url" name="em_event_checkout_terms_url" id="em_event_checkout_terms_url" class="ep-form-control ep-event-terms-options" placeholder="'.esc_html__( 'Enter URL', 'eventprime-event-calendar-management' ).'" value="'.esc_attr( $term_content ).'">';
				} else{
					$field .= '<input type="url" name="em_event_checkout_terms_url" id="em_event_checkout_terms_url" class="ep-form-control ep-event-terms-options" placeholder="'.esc_html__( 'Enter URL', 'eventprime-event-calendar-management' ).'" disabled>';
				}
				$field .= '<div class="ep-error-message" id="ep_fixed_field_url_option_error"></div>';
			$field .= '</div>';
		$field .= '</div>';

		// enter custom content option
		$content = '';
		$field .= '<div class="ep-sub-field-terms-content ep-box-row ep-mt-3">';
			$field .= '<div class="ep-box-col-1 "><label for="em_event_checkout_terms_content_option">';
				if( $term_option == 'content' ) {
					$field .= '<input type="radio" class="ep-terms-sub-fields" data-terms_type="content" name="em_event_checkout_terms_option" id="em_event_checkout_terms_content_option" value="content" data-label="'.esc_html__( 'Enter Custom Content', 'eventprime-event-calendar-management' ).'" checked>';
				} else{
					$field .= '<input type="radio" class="ep-terms-sub-fields" data-terms_type="content" name="em_event_checkout_terms_option" id="em_event_checkout_terms_content_option" value="content" data-label="'.esc_html__( 'Enter Custom Content', 'eventprime-event-calendar-management' ).'">';
				}
			$field .= '</label></div>';
			$field .= '<div class="ep-box-col-11 ep-mb-3">';
				$field .= esc_html__( 'Enter Custom Content', 'eventprime-event-calendar-management' );
			$field .= '</div>';
			if( $term_option == 'content' ) {
				$field .= '<div class="ep-box-col-12 ep-sub-field-terms-content-options ep-mt-3">';
					$field .= self::ep_get_wp_editor( wp_kses_post( $term_content ), 'description' );
					$field .= '<div class="ep-error-message" id="ep_fixed_field_custom_option_error"></div>';
				$field .= '</div>';
			} else{
				$field .= '<div class="ep-box-col-12 ep-sub-field-terms-content-options ep-mt-3" style="display:none;">';
					$field .= self::ep_get_wp_editor( "", 'description' );
					$field .= '<div class="ep-error-message" id="ep_fixed_field_custom_option_error"></div>';
				$field .= '</div>';
			}
		$field .= '</div>';
		return $field;
	}

	/**
	 * Load wp editor
	 */
	private static function ep_get_wp_editor( $content = '', $editor_id = 'description' ) {
		ob_start();
		wp_editor( $content, $editor_id );
		$temp = ob_get_clean();
		$temp .= \_WP_Editors::enqueue_scripts();
		//$temp .= print_footer_scripts();
		$temp .= \_WP_Editors::editor_js();
		return $temp;
	}

	/**
     * Save events data
     * 
     * @param int 	 $post_id Post ID.
     * @param object $post Post object.
     */
    public function ep_save_event_meta_boxes( $post_id, $post ) {
		$post_id = absint( $post_id );
		// $post_id and $post are required
		if ( empty( $post_id ) || empty( $post ) || self::$ep_saved_event_meta_boxes ) {
			return;
		}
		// Dont' save meta boxes for revisions or autosaves.
		if( defined('DOING_AUTOSAVE') and DOING_AUTOSAVE ) {
			return false;
		}
		// Check the nonce.
		if ( empty( $_POST['ep_event_meta_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['ep_event_meta_nonce'] ), 'ep_save_event_data' ) ) {
			return;
		}
		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
		if ( empty( $_POST['post_ID'] ) || absint( $_POST['post_ID'] ) !== $post_id ) {
			return;
		}
		// Check user has permission to edit.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		self::$ep_saved_event_meta_boxes = true;
		global $wpdb;
		// save data
		update_post_meta( $post_id, 'em_id', $post->ID );
		update_post_meta( $post_id, 'em_name', sanitize_text_field($post->post_title) );
                $update_post_title = array(
                        'ID'           => $post->ID,
                        'post_title'   => sanitize_text_field($post->post_title)
                    );
                wp_update_post($update_post_title);
		// tax_input
		$event_type_val = $venue_val = $organizer_val = '';$performer_val = array();
		if( isset( $_POST['tax_input'] ) && ! empty( $_POST['tax_input'] ) ) {
			$tax_input = $_POST['tax_input'];
			// event type
			if( isset( $tax_input['em_event_type'] ) && count( $tax_input['em_event_type'] ) > 0 ) {
				$event_type_val = $tax_input['em_event_type'][0];
			}
			// venue
			if( isset( $tax_input['em_venue'] ) && count( $tax_input['em_venue'] ) > 0 ) {
				$event_venue_id = $tax_input['em_venue'][0];
				// check for old venue
				$old_event_venue_id = get_post_meta( $post_id, 'em_venue', true );
				if( ! empty( $old_event_venue_id ) && $old_event_venue_id != $event_venue_id ) {
					// remove event seat data
					$event_seat_data = get_post_meta( $post_id, 'em_seat_data', true );
                    if( ! empty( $event_seat_data ) ) {
                        update_post_meta( $post_id, 'em_seat_data', array() );        
                    }
				}
				$venue_val = $tax_input['em_venue'][0];
			}
			// Organizer
			if( isset( $tax_input['em_event_organizer'] ) && count( $tax_input['em_event_organizer'] ) > 0 ) {
				$organizer_val = $tax_input['em_event_organizer'];
			}
			// Performer
			if( isset( $tax_input['em_performer'] ) && count( $tax_input['em_performer'] ) > 0 ) {
				$performer_val = $tax_input['em_performer'];
			}
		}
		update_post_meta( $post_id, 'em_event_type', $event_type_val );
		update_post_meta( $post_id, 'em_venue', $venue_val );
		update_post_meta( $post_id, 'em_organizer', $organizer_val );
		update_post_meta( $post_id, 'em_performer', $performer_val );
		// event gallery
		$em_gallery_image_ids = isset( $_POST['em_gallery_image_ids'] ) ? $_POST['em_gallery_image_ids'] : '';
		update_post_meta( $post_id, 'em_gallery_image_ids', $em_gallery_image_ids );
		// start date
		$em_start_date = isset( $_POST['em_start_date'] ) ? ep_date_to_timestamp( sanitize_text_field( $_POST['em_start_date'] ) ) : '';
		update_post_meta( $post_id, 'em_start_date', $em_start_date );
		//start time
		$em_start_time = isset( $_POST['em_start_time'] ) ? sanitize_text_field( $_POST['em_start_time'] ) : '';
		update_post_meta( $post_id, 'em_start_time', $em_start_time );
		// hide start time
		$em_hide_start_time = isset( $_POST['em_hide_event_start_time'] ) ? 1 : 0;
		update_post_meta( $post_id, 'em_hide_event_start_time', $em_hide_start_time );
		// hide start date
		$em_hide_event_start_date = isset( $_POST['em_hide_event_start_date'] ) ? 1 : 0;
		update_post_meta( $post_id, 'em_hide_event_start_date', $em_hide_event_start_date );
		// end date
		$em_end_date = isset( $_POST['em_end_date'] ) ? ep_date_to_timestamp( sanitize_text_field( $_POST['em_end_date'] ) ) : $em_start_date;
		update_post_meta( $post_id, 'em_end_date', $em_end_date );
		//end time
		$em_end_time = isset( $_POST['em_end_time'] ) ? sanitize_text_field( $_POST['em_end_time'] ) : '';
		update_post_meta( $post_id, 'em_end_time', $em_end_time );
		// hide end time
		$em_hide_event_end_time = isset( $_POST['em_hide_event_end_time'] ) ? 1 : 0;
		update_post_meta( $post_id, 'em_hide_event_end_time', $em_hide_event_end_time );
		// hide end date
		$em_hide_end_date = isset( $_POST['em_hide_end_date'] ) ? 1 : 0;
		update_post_meta( $post_id, 'em_hide_end_date', $em_hide_end_date );
		// all day
		$em_all_day = isset( $_POST['em_all_day'] ) ? 1 : 0;
		update_post_meta( $post_id, 'em_all_day', $em_all_day );
		// if event is all day then end date will be same as start date
		if( $em_all_day == 1 ) {
			$em_end_date = $em_start_date;
			update_post_meta( $post_id, 'em_end_date', $em_end_date );
			$em_start_time = '12:00 AM'; $em_end_time = '11:59 PM';
			update_post_meta( $post_id, 'em_start_time', $em_start_time );
			update_post_meta( $post_id, 'em_end_time', $em_end_time );
		} else{
			if( $em_start_date > $em_end_date ) {
				update_post_meta( $post_id, 'em_end_date', $em_start_date );
			} else if( $em_start_date == $em_end_date ) {
				if( $em_start_time == $em_end_time ) {
					if( empty( $em_start_time ) ) {
						update_post_meta( $post_id, 'em_start_time', '12:00 AM' );
						update_post_meta( $post_id, 'em_end_time', '11:59 PM' );
					} else{
						if( $em_end_time !== '11:59 PM' ) {
							update_post_meta( $post_id, 'em_end_time', '11:59 PM' );
						}
					}
				}
			}else if( $em_start_date < $em_end_date ){
				if( empty( $em_start_time ) ) {
					update_post_meta( $post_id, 'em_start_time', '12:00 AM' );
					update_post_meta( $post_id, 'em_end_time', '11:59 PM' );
				}else if( ! empty( $em_end_time ) ){
					update_post_meta( $post_id, 'em_end_time', $em_end_time );
				}else{
					if( $em_end_time !== '11:59 PM' ) {
						update_post_meta( $post_id, 'em_end_time', '11:59 PM' );
					}
				}
			}
		}
		// update start and end datetime meta
		$ep_date_time_format = 'Y-m-d';
		$start_date = get_post_meta( $post_id, 'em_start_date', true );
		$start_time = get_post_meta( $post_id, 'em_start_time', true );
		$merge_start_date_time = ep_datetime_to_timestamp( ep_timestamp_to_date( $start_date, 'Y-m-d', 1 ) . ' ' . $start_time, $ep_date_time_format, '', 0, 1 );
		if( ! empty( $merge_start_date_time ) ) {
			update_post_meta( $post_id, 'em_start_date_time', $merge_start_date_time );
		}
		$end_date = get_post_meta( $post_id, 'em_end_date', true );
		$end_time = get_post_meta( $post_id, 'em_end_time', true );
		$merge_end_date_time = ep_datetime_to_timestamp( ep_timestamp_to_date( $end_date, 'Y-m-d', 1 ) . ' ' . $end_time, $ep_date_time_format, '', 0, 1 );
		if( ! empty( $merge_end_date_time ) ) {
			update_post_meta( $post_id, 'em_end_date_time', $merge_end_date_time );
		}
		//event date placeholder
		$em_event_date_placeholder = isset( $_POST['em_event_date_placeholder'] ) ? sanitize_text_field( $_POST['em_event_date_placeholder'] ) : '';
		update_post_meta( $post_id, 'em_event_date_placeholder', $em_event_date_placeholder );
		$em_event_date_placeholder_custom_note = '';
		if( ! empty( $em_event_date_placeholder ) && $em_event_date_placeholder == 'custom_note' ) {
			$em_event_date_placeholder_custom_note = sanitize_text_field( $_POST['em_event_date_placeholder_custom_note'] );
		}
		update_post_meta( $post_id, 'em_event_date_placeholder_custom_note', $em_event_date_placeholder_custom_note );
		// add event more dates
		$em_event_more_dates = isset( $_POST['em_event_more_dates'] ) ? 1 : 0;
		update_post_meta( $post_id, 'em_event_more_dates', $em_event_more_dates );
		$event_more_dates = array();
		if( isset( $_POST['em_event_more_dates'] ) && !empty( $_POST['em_event_more_dates'] ) ) {
			if( isset( $_POST['em_event_add_more_dates'] ) && count( $_POST['em_event_add_more_dates'] ) > 0 ) {
				foreach( $_POST['em_event_add_more_dates'] as $key => $more_dates ) {
					$new_date = array();
					$new_date['uid']    = absint( $more_dates['uid'] );
					$new_date['date']   = ep_date_to_timestamp( sanitize_text_field( $more_dates['date'] ) );
					$new_date['time']   = sanitize_text_field( $more_dates['time'] );
					$new_date['label']  = sanitize_text_field( $more_dates['label'] );
					$event_more_dates[] = $new_date;
				}
			}
		}
		update_post_meta( $post_id, 'em_event_add_more_dates', $event_more_dates );
		// booking & tickets
		$em_enable_booking = isset( $_POST['em_enable_booking'] ) ? sanitize_text_field( $_POST['em_enable_booking'] ) : '';
		if( $em_enable_booking == 'bookings_on' ) {
			// check for ticket. If no ticket created then bookings will be off
			$ep_event_has_ticket = absint( $_POST['ep_event_has_ticket'] );
			if( $ep_event_has_ticket == 0 ) {
				$em_enable_booking = 'bookings_off';
			}
		}
		update_post_meta( $post_id, 'em_enable_booking', $em_enable_booking );
		// check for external booking
		if( ! empty( $em_enable_booking ) && $em_enable_booking == 'external_bookings' ) {
			$em_custom_link = isset( $_POST['em_custom_link'] ) && ! empty( $_POST['em_custom_link'] ) ? sanitize_url( $_POST['em_custom_link'] ) : '';
			update_post_meta( $post_id, 'em_custom_link', $em_custom_link );
			// open in new browser
			$em_custom_link_new_browser = isset( $_POST['em_custom_link_new_browser'] ) ? 1 : 0;
			update_post_meta( $post_id, 'em_custom_link_new_browser', $em_custom_link_new_browser );
		}
		// One time event fee
		$em_fixed_event_price = isset( $_POST['em_fixed_event_price'] ) && ! empty( $_POST['em_fixed_event_price'] ) ? sanitize_text_field( $_POST['em_fixed_event_price'] ) : '';
		update_post_meta( $post_id, 'em_fixed_event_price', $em_fixed_event_price );
		// hide booking status
		$em_hide_booking_status = isset( $_POST['em_hide_booking_status'] ) ? 1 : 0;
		update_post_meta( $post_id, 'em_hide_booking_status', $em_hide_booking_status );
		// allow cancellation option
		$em_allow_cancellations = isset( $_POST['em_allow_cancellations'] ) ? 1 : 0;
		update_post_meta( $post_id, 'em_allow_cancellations', $em_allow_cancellations );
		// save category
		$cat_table_name = $wpdb->prefix.'eventprime_ticket_categories';
		$price_options_table = $wpdb->prefix.'em_price_options';
		if( isset( $_POST['em_ticket_category_data'] ) && ! empty( $_POST['em_ticket_category_data'] ) ) {
			$em_ticket_category_data = json_decode( stripslashes( $_POST['em_ticket_category_data'] ), true) ;
		}
		if( ! empty( $em_ticket_category_data ) ) {
			$cat_priority = 1;
			foreach( $em_ticket_category_data as $cat ) {
				$cat_id = $cat['id'];
				$get_field_data = '';
				if( ! empty( $cat_id ) ) {
					$get_field_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $cat_table_name WHERE `event_id` = %d AND `id` = %d", $post_id, $cat_id ) );
				}
				if( empty( $get_field_data ) ) {
					$save_data 				 = array();
					$save_data['event_id'] 	 = $post_id;
					$save_data['name'] 	     = $cat['name'];
					$save_data['capacity']   = $cat['capacity'];
					$save_data['priority']   = 1;
					$save_data['status']     = 1;
					$save_data['created_by'] = get_current_user_id();
					$save_data['created_at'] = date_i18n( "Y-m-d H:i:s", time() );
					$result = $wpdb->insert( $cat_table_name, $save_data );
					$cat_id = $wpdb->insert_id;
				} else{
					$wpdb->update( $cat_table_name, 
						array( 
							'name' 		  	  => $cat['name'],
							'capacity' 		  => $cat['capacity'],
							'priority'		  => $cat_priority,
							'last_updated_by' => get_current_user_id(),
							'updated_at' 	  => date_i18n("Y-m-d H:i:s", time())
						), 
						array( 'id' => $cat_id )
					);
				}
				$cat_priority++;
				//save tickets
				if( isset( $cat['tickets'] ) && ! empty( $cat['tickets'] ) ) {
					$cat_ticket_priority = 1;
					foreach( $cat['tickets'] as $ticket ) {
						$ticket_data = array();
						if( isset( $ticket['id'] ) && ! empty( $ticket['id'] ) ) {
							$ticket_id = (int)$ticket['id'];
							if( ! empty( $ticket_id ) ) {
								$get_ticket_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $price_options_table WHERE `id` = %d", $ticket_id ) );
								if( ! empty( $get_ticket_data ) ) {
									$ticket_data['name'] 		   		   = addslashes( $ticket['name'] );
									$ticket_data['description']    		   = isset( $ticket['description'] ) ? addslashes( str_replace( '"', "'", $ticket['description'] ) ) : '';
									$ticket_data['price'] 		   		   = isset( $ticket['price'] ) ? $ticket['price'] : 0;
									$ticket_data['capacity'] 	   		   = isset( $ticket['capacity'] ) ? absint( $ticket['capacity'] ) : 0;
									$ticket_data['icon'] 		   		   = isset( $ticket['icon'] ) ? absint( $ticket['icon'] ) : '';
									$ticket_data['priority'] 	   		   = $cat_ticket_priority;
									$ticket_data['updated_at'] 	   		   = date_i18n("Y-m-d H:i:s", time());
									$ticket_data['additional_fees']    	   = ( isset( $ticket['ep_additional_ticket_fee_data'] ) && !empty( $ticket['ep_additional_ticket_fee_data'] ) ) ? json_encode( $ticket['ep_additional_ticket_fee_data'] ) : '';
									$ticket_data['allow_cancellation'] 	   = isset( $ticket['allow_cancellation'] ) ? absint( $ticket['allow_cancellation'] ) : 0;
									$ticket_data['show_remaining_tickets'] = isset( $ticket['show_remaining_tickets'] ) ? absint( $ticket['show_remaining_tickets'] ) : 0;
									// date
									$start_date = [];
									if( isset( $ticket['em_ticket_start_booking_type'] ) && !empty( $ticket['em_ticket_start_booking_type'] ) ) {
										$start_date['booking_type'] = $ticket['em_ticket_start_booking_type'];
										if( $ticket['em_ticket_start_booking_type'] == 'custom_date' ) {
											if( isset( $ticket['em_ticket_start_booking_date'] ) && ! empty( $ticket['em_ticket_start_booking_date'] ) ) {
												$start_date['start_date'] = $ticket['em_ticket_start_booking_date'];
											}
											if( isset( $ticket['em_ticket_start_booking_time'] ) && ! empty( $ticket['em_ticket_start_booking_time'] ) ) {
												$start_date['start_time'] = $ticket['em_ticket_start_booking_time'];
											}
										} elseif( $ticket['em_ticket_start_booking_type'] == 'event_date' ) {
											$start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
										} elseif( $ticket['em_ticket_start_booking_type'] == 'relative_date' ) {
											if( isset( $ticket['em_ticket_start_booking_days'] ) && ! empty( $ticket['em_ticket_start_booking_days'] ) ) {
												$start_date['days'] = $ticket['em_ticket_start_booking_days'];
											}
											if( isset( $ticket['em_ticket_start_booking_days_option'] ) && ! empty( $ticket['em_ticket_start_booking_days_option'] ) ) {
												$start_date['days_option'] = $ticket['em_ticket_start_booking_days_option'];
											}
											$start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
										}
									}
									$ticket_data['booking_starts'] = json_encode( $start_date );
									// end date
									$end_date = [];
									if( isset( $ticket['em_ticket_ends_booking_type'] ) && !empty( $ticket['em_ticket_ends_booking_type'] ) ) {
										$end_date['booking_type'] = $ticket['em_ticket_ends_booking_type'];
										if( $ticket['em_ticket_ends_booking_type'] == 'custom_date' ) {
											if( isset( $ticket['em_ticket_ends_booking_date'] ) && ! empty( $ticket['em_ticket_ends_booking_date'] ) ) {
												$end_date['end_date'] = $ticket['em_ticket_ends_booking_date'];
											}
											if( isset( $ticket['em_ticket_ends_booking_time'] ) && ! empty( $ticket['em_ticket_ends_booking_time'] ) ) {
												$end_date['end_time'] = $ticket['em_ticket_ends_booking_time'];
											}
										} elseif( $ticket['em_ticket_ends_booking_type'] == 'event_date' ) {
											$end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
										} elseif( $ticket['em_ticket_ends_booking_type'] == 'relative_date' ) {
											if( isset( $ticket['em_ticket_ends_booking_days'] ) && ! empty( $ticket['em_ticket_ends_booking_days'] ) ) {
												$end_date['days'] = $ticket['em_ticket_ends_booking_days'];
											}
											if( isset( $ticket['em_ticket_ends_booking_days_option'] ) && ! empty( $ticket['em_ticket_ends_booking_days_option'] ) ) {
												$end_date['days_option'] = $ticket['em_ticket_ends_booking_days_option'];
											}
											$end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
										}
									}
									$ticket_data['booking_ends'] = json_encode( $end_date );
		
									// visibility
									$ticket_data['visibility'] = $ticket_visibility = array();
									if( isset( $ticket['em_tickets_user_visibility'] ) && ! empty( $ticket['em_tickets_user_visibility'] ) ) {
										$ticket_visibility['em_tickets_user_visibility'] = $ticket['em_tickets_user_visibility'];
									}
									if( isset( $ticket['em_ticket_for_invalid_user'] ) && ! empty( $ticket['em_ticket_for_invalid_user'] ) ) {
										$ticket_visibility['em_ticket_for_invalid_user'] = $ticket['em_ticket_for_invalid_user'];
									}
									if( isset( $ticket['em_tickets_visibility_time_restrictions'] ) && ! empty( $ticket['em_tickets_visibility_time_restrictions'] ) ) {
										$ticket_visibility['em_tickets_visibility_time_restrictions'] = $ticket['em_tickets_visibility_time_restrictions'];
									}
									if( isset( $ticket['em_ticket_visibility_user_roles'] ) && ! empty( $ticket['em_ticket_visibility_user_roles'] ) ) {
										$ticket_visibility['em_ticket_visibility_user_roles'] = $ticket['em_ticket_visibility_user_roles'];
									}
									if( ! empty( $ticket_visibility ) ) {
										$ticket_data['visibility'] = json_encode( $ticket_visibility );
									}
		
									$ticket_data['show_ticket_booking_dates'] = (isset( $ticket['show_ticket_booking_dates'] ) ) ? 1 : 0;
									$ticket_data['min_ticket_no'] = isset( $ticket['min_ticket_no'] ) ? $ticket['min_ticket_no'] : 0;
									$ticket_data['max_ticket_no'] = isset( $ticket['max_ticket_no'] ) ? $ticket['max_ticket_no'] : 0;
									if( isset( $ticket['offers'] ) && ! empty( $ticket['offers'] ) ) {
										$ticket_data['offers'] = $ticket['offers'];
									}
									$ticket_data['multiple_offers_option'] = ( isset( $ticket['multiple_offers_option'] ) && !empty( $ticket['multiple_offers_option'] ) ) ? $ticket['multiple_offers_option'] : '';
									$ticket_data['multiple_offers_max_discount'] = ( isset( $ticket['multiple_offers_max_discount'] ) && !empty( $ticket['multiple_offers_max_discount'] ) ) ? $ticket['multiple_offers_max_discount'] : '';
									$ticket_data['ticket_template_id'] = ( isset( $ticket['ticket_template_id'] ) && !empty( $ticket['ticket_template_id'] ) ) ? $ticket['ticket_template_id'] : '';
									
									$wpdb->update( $price_options_table, 
										$ticket_data, 
										array( 'id' => $ticket_id )
									);
								} else{
									$ticket_data = $this->ep_add_tickets_in_category( $cat_id, $post_id, $ticket, $cat_ticket_priority );
									$result = $wpdb->insert( $price_options_table, $ticket_data );
								}
							} else{
								$ticket_data = $this->ep_add_tickets_in_category( $cat_id, $post_id, $ticket, $cat_ticket_priority );
								$result = $wpdb->insert( $price_options_table, $ticket_data );
							}
						} else{
							$ticket_data = $this->ep_add_tickets_in_category( $cat_id, $post_id, $ticket, $cat_ticket_priority );
							$result = $wpdb->insert( $price_options_table, $ticket_data );
						}
						$cat_ticket_priority++;
					}
				}
			}
		}
		// delete category
		if( isset( $_POST['em_ticket_category_delete_ids'] ) && !empty( $_POST['em_ticket_category_delete_ids'] ) ) {
			$em_ticket_category_delete_ids = $_POST['em_ticket_category_delete_ids'];
			$del_ids = json_decode( stripslashes( $em_ticket_category_delete_ids ) );
			if( is_string( $em_ticket_category_delete_ids ) && is_array( json_decode( stripslashes( $em_ticket_category_delete_ids ) ) ) &&  json_last_error() == JSON_ERROR_NONE ) {
				foreach( $del_ids as $id ) {
					$get_field_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $cat_table_name WHERE `id` = %d", $id ) );
					if( ! empty( $get_field_data ) ) {
						$wpdb->delete( $cat_table_name, array( 'id' => $id ) );
					}
				}
			}
		}
		// save tickets
		if( isset( $_POST['em_ticket_individual_data'] ) && ! empty( $_POST['em_ticket_individual_data'] ) ) {
			$em_ticket_individual_data = json_decode( stripslashes( $_POST['em_ticket_individual_data'] ), true) ;
			if( isset( $em_ticket_individual_data ) && ! empty( $em_ticket_individual_data ) ) {
				$tic = 0;
				foreach( $em_ticket_individual_data as $ticket ) {
					if( isset( $ticket['id'] ) && ! empty( $ticket['id'] ) ) {
						$ticket_id = (int)$ticket['id'];
						if( ! empty( $ticket_id ) ) {
							$get_ticket_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $price_options_table WHERE `id` = %d", $ticket_id ) );
							if( ! empty( $get_ticket_data ) ) {
								$ticket_data 				   = array();
								$ticket_data['name'] 		   = addslashes( $ticket['name'] );
								$ticket_data['description']    = isset( $ticket['description'] ) ? addslashes( str_replace( '"', "'", $ticket['description'] ) ) : '';
								$ticket_data['price'] 		   = isset( $ticket['price'] ) ? $ticket['price'] : 0;
								$ticket_data['capacity'] 	   = isset( $ticket['capacity'] ) ? absint( $ticket['capacity'] ) : 0;
								$ticket_data['icon'] 		   = isset( $ticket['icon'] ) ? absint( $ticket['icon'] ) : '';
								$ticket_data['updated_at'] 	   = date_i18n("Y-m-d H:i:s", time());
								$ticket_data['additional_fees']    = ( isset( $ticket['ep_additional_ticket_fee_data'] ) && !empty( $ticket['ep_additional_ticket_fee_data'] ) ) ? json_encode( $ticket['ep_additional_ticket_fee_data'] ) : '';
								$ticket_data['allow_cancellation'] = isset( $ticket['allow_cancellation'] ) ? absint( $ticket['allow_cancellation'] ) : 0;
								$ticket_data['show_remaining_tickets'] = isset( $ticket['show_remaining_tickets'] ) ? absint( $ticket['show_remaining_tickets'] ) : 0;
								// date
								$start_date = [];
								if( isset( $ticket['em_ticket_start_booking_type'] ) && !empty( $ticket['em_ticket_start_booking_type'] ) ) {
									$start_date['booking_type'] = $ticket['em_ticket_start_booking_type'];
									if( $ticket['em_ticket_start_booking_type'] == 'custom_date' ) {
										if( isset( $ticket['em_ticket_start_booking_date'] ) && ! empty( $ticket['em_ticket_start_booking_date'] ) ) {
											$start_date['start_date'] = $ticket['em_ticket_start_booking_date'];
										}
										if( isset( $ticket['em_ticket_start_booking_time'] ) && ! empty( $ticket['em_ticket_start_booking_time'] ) ) {
											$start_date['start_time'] = $ticket['em_ticket_start_booking_time'];
										}
									} elseif( $ticket['em_ticket_start_booking_type'] == 'event_date' ) {
										$start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
									} elseif( $ticket['em_ticket_start_booking_type'] == 'relative_date' ) {
										if( isset( $ticket['em_ticket_start_booking_days'] ) && ! empty( $ticket['em_ticket_start_booking_days'] ) ) {
											$start_date['days'] = $ticket['em_ticket_start_booking_days'];
										}
										if( isset( $ticket['em_ticket_start_booking_days_option'] ) && ! empty( $ticket['em_ticket_start_booking_days_option'] ) ) {
											$start_date['days_option'] = $ticket['em_ticket_start_booking_days_option'];
										}
										$start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
									}
								}
								$ticket_data['booking_starts'] = json_encode( $start_date );
								// end date
								$end_date = [];
								if( isset( $ticket['em_ticket_ends_booking_type'] ) && !empty( $ticket['em_ticket_ends_booking_type'] ) ) {
									$end_date['booking_type'] = $ticket['em_ticket_ends_booking_type'];
									if( $ticket['em_ticket_ends_booking_type'] == 'custom_date' ) {
										if( isset( $ticket['em_ticket_ends_booking_date'] ) && ! empty( $ticket['em_ticket_ends_booking_date'] ) ) {
											$end_date['end_date'] = $ticket['em_ticket_ends_booking_date'];
										}
										if( isset( $ticket['em_ticket_ends_booking_time'] ) && ! empty( $ticket['em_ticket_ends_booking_time'] ) ) {
											$end_date['end_time'] = $ticket['em_ticket_ends_booking_time'];
										}
									} elseif( $ticket['em_ticket_ends_booking_type'] == 'event_date' ) {
										$end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
									} elseif( $ticket['em_ticket_ends_booking_type'] == 'relative_date' ) {
										if( isset( $ticket['em_ticket_ends_booking_days'] ) && ! empty( $ticket['em_ticket_ends_booking_days'] ) ) {
											$end_date['days'] = $ticket['em_ticket_ends_booking_days'];
										}
										if( isset( $ticket['em_ticket_ends_booking_days_option'] ) && ! empty( $ticket['em_ticket_ends_booking_days_option'] ) ) {
											$end_date['days_option'] = $ticket['em_ticket_ends_booking_days_option'];
										}
										$end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
									}
								}
								$ticket_data['booking_ends'] = json_encode( $end_date );

								// visibility
								$ticket_data['visibility'] = $ticket_visibility = array();
								if( isset( $ticket['em_tickets_user_visibility'] ) && ! empty( $ticket['em_tickets_user_visibility'] ) ) {
									$ticket_visibility['em_tickets_user_visibility'] = $ticket['em_tickets_user_visibility'];
								}
								if( isset( $ticket['em_ticket_for_invalid_user'] ) && ! empty( $ticket['em_ticket_for_invalid_user'] ) ) {
									$ticket_visibility['em_ticket_for_invalid_user'] = $ticket['em_ticket_for_invalid_user'];
								}
								if( isset( $ticket['em_tickets_visibility_time_restrictions'] ) && ! empty( $ticket['em_tickets_visibility_time_restrictions'] ) ) {
									$ticket_visibility['em_tickets_visibility_time_restrictions'] = $ticket['em_tickets_visibility_time_restrictions'];
								}
								if( isset( $ticket['em_ticket_visibility_user_roles'] ) && ! empty( $ticket['em_ticket_visibility_user_roles'] ) ) {
									$ticket_visibility['em_ticket_visibility_user_roles'] = $ticket['em_ticket_visibility_user_roles'];
								}
								if( ! empty( $ticket_visibility ) ) {
									$ticket_data['visibility'] = json_encode( $ticket_visibility );
								}

								$ticket_data['show_ticket_booking_dates'] = (isset( $ticket['show_ticket_booking_dates'] ) ) ? 1 : 0;
								$ticket_data['min_ticket_no'] = isset( $ticket['min_ticket_no'] ) ? $ticket['min_ticket_no'] : 0;
								$ticket_data['max_ticket_no'] = isset( $ticket['max_ticket_no'] ) ? $ticket['max_ticket_no'] : 0;
								if( isset( $ticket['offers'] ) && ! empty( $ticket['offers'] ) ) {
									$ticket_data['offers'] = $ticket['offers'];
								}
								$ticket_data['multiple_offers_option'] = ( isset( $ticket['multiple_offers_option'] ) && !empty( $ticket['multiple_offers_option'] ) ) ? $ticket['multiple_offers_option'] : '';
								$ticket_data['multiple_offers_max_discount'] = ( isset( $ticket['multiple_offers_max_discount'] ) && !empty( $ticket['multiple_offers_max_discount'] ) ) ? $ticket['multiple_offers_max_discount'] : '';
								$ticket_data['ticket_template_id'] = ( isset( $ticket['ticket_template_id'] ) && !empty( $ticket['ticket_template_id'] ) ) ? $ticket['ticket_template_id'] : '';

								$wpdb->update( $price_options_table, 
									$ticket_data, 
									array( 'id' => $ticket_id )
								);
							} else{
								$ticket_data = $this->ep_add_individual_tickets( $post_id, $ticket );
								$result = $wpdb->insert( $price_options_table, $ticket_data );
							}
						}else{
							$ticket_data = $this->ep_add_individual_tickets( $post_id, $ticket );
							$result = $wpdb->insert( $price_options_table, $ticket_data );
						}
					} else{
						$ticket_data = $this->ep_add_individual_tickets( $post_id, $ticket );
						$result = $wpdb->insert( $price_options_table, $ticket_data );
					}
					$tic++;
					error_log($tic);
				}
			}
		}
		// delete tickets
		if( isset( $_POST['em_ticket_individual_delete_ids'] ) && !empty( $_POST['em_ticket_individual_delete_ids'] ) ) {
			$em_ticket_individual_delete_ids = $_POST['em_ticket_individual_delete_ids'];
			$del_ids = json_decode( stripslashes( $em_ticket_individual_delete_ids ) );
			if( is_string( $em_ticket_individual_delete_ids ) && is_array( json_decode( stripslashes( $em_ticket_individual_delete_ids ) ) ) &&  json_last_error() == JSON_ERROR_NONE ) {
				foreach( $del_ids as $id ) {
					$get_field_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $price_options_table WHERE `id` = %d", $id ) );
					if( ! empty( $get_field_data ) ) {
						$wpdb->delete( $price_options_table, array( 'id' => $id ) );
					}
				}
			}
		}
		// event checkout fields
		$event_checkout_attendee_fields = array();
		// check for name field
		if( isset( $_POST['em_event_checkout_name'] ) && ! empty( $_POST['em_event_checkout_name'] ) ) {
			$event_checkout_attendee_fields['em_event_checkout_name'] = absint( $_POST['em_event_checkout_name'] );
			if( isset( $_POST['em_event_checkout_name_first_name'] ) && ! empty( $_POST['em_event_checkout_name_first_name'] ) ) {
				$event_checkout_attendee_fields['em_event_checkout_name_first_name'] = absint( $_POST['em_event_checkout_name_first_name'] );
			}
			if( isset( $_POST['em_event_checkout_name_first_name_required'] ) && ! empty( $_POST['em_event_checkout_name_first_name_required'] ) ) {
				$event_checkout_attendee_fields['em_event_checkout_name_first_name_required'] = absint( $_POST['em_event_checkout_name_first_name_required'] );
			}
			if( isset( $_POST['em_event_checkout_name_middle_name'] ) && ! empty( $_POST['em_event_checkout_name_middle_name'] ) ) {
				$event_checkout_attendee_fields['em_event_checkout_name_middle_name'] = absint( $_POST['em_event_checkout_name_middle_name'] );
			}
			if( isset( $_POST['em_event_checkout_name_middle_name_required'] ) && ! empty( $_POST['em_event_checkout_name_middle_name_required'] ) ) {
				$event_checkout_attendee_fields['em_event_checkout_name_middle_name_required'] = absint( $_POST['em_event_checkout_name_middle_name_required'] );
			}
			if( isset( $_POST['em_event_checkout_name_last_name'] ) && ! empty( $_POST['em_event_checkout_name_last_name'] ) ) {
				$event_checkout_attendee_fields['em_event_checkout_name_last_name'] = absint( $_POST['em_event_checkout_name_last_name'] );
			}
			if( isset( $_POST['em_event_checkout_name_last_name_required'] ) && ! empty( $_POST['em_event_checkout_name_last_name_required'] ) ) {
				$event_checkout_attendee_fields['em_event_checkout_name_last_name_required'] = absint( $_POST['em_event_checkout_name_last_name_required'] );
			}
		}
		// check for checkout fields
		if( isset( $_POST['em_event_checkout_fields_data'] ) && count( $_POST['em_event_checkout_fields_data'] ) > 0 ) {
			$event_checkout_attendee_fields['em_event_checkout_fields_data'] = array();
			foreach( $_POST['em_event_checkout_fields_data'] as $cfd ) {
				$event_checkout_attendee_fields['em_event_checkout_fields_data'][] = absint( $cfd );
			}
			// get required field data
			if( isset( $_POST['em_event_checkout_fields_data_required'] ) && count( $_POST['em_event_checkout_fields_data_required'] ) > 0 ) {
				$event_checkout_attendee_fields['em_event_checkout_fields_data_required'] = array();
				foreach( $_POST['em_event_checkout_fields_data_required'] as $cfdr ) {
					$event_checkout_attendee_fields['em_event_checkout_fields_data_required'][] = absint( $cfdr );
				}
			}
		}
		update_post_meta( $post_id, 'em_event_checkout_attendee_fields', $event_checkout_attendee_fields );
		// event checkout fixed fields
		$event_checkout_fixed_fields = array();
		if( isset( $_POST['em_event_checkout_fixed_terms_enabled'] ) && absint( $_POST['em_event_checkout_fixed_terms_enabled'] ) == 1 ) {
			$event_checkout_fixed_fields['em_event_checkout_fixed_terms_enabled'] = $_POST['em_event_checkout_fixed_terms_enabled'];
			if( isset( $_POST['em_event_checkout_fixed_terms_label'] ) ) {
				$event_checkout_fixed_fields['em_event_checkout_fixed_terms_label'] = sanitize_text_field( $_POST['em_event_checkout_fixed_terms_label'] );
			}
			if( isset( $_POST['em_event_checkout_fixed_terms_option'] ) ) {
				$event_checkout_fixed_fields['em_event_checkout_fixed_terms_option'] = sanitize_text_field( $_POST['em_event_checkout_fixed_terms_option'] );
			}
			if( isset( $_POST['em_event_checkout_fixed_terms_content'] ) ) {
				if( $event_checkout_fixed_fields['em_event_checkout_fixed_terms_option'] == 'page' ) {
					$event_checkout_fixed_fields['em_event_checkout_fixed_terms_content'] = absint( $_POST['em_event_checkout_fixed_terms_content'] );
				} else if( $event_checkout_fixed_fields['em_event_checkout_fixed_terms_option'] == 'content' ) {
					$event_checkout_fixed_fields['em_event_checkout_fixed_terms_content'] = wp_kses_post( $_POST['em_event_checkout_fixed_terms_content'] );
				} else{
					$event_checkout_fixed_fields['em_event_checkout_fixed_terms_content'] = sanitize_text_field( $_POST['em_event_checkout_fixed_terms_content'] );
				}
			}
		}
		update_post_meta( $post_id, 'em_event_checkout_fixed_fields', $event_checkout_fixed_fields );
		$em_event_checkout_booking_fields = array();
		// check for booking fields
		if( isset( $_POST['em_event_booking_fields_data'] ) && count( $_POST['em_event_booking_fields_data'] ) > 0 ) {
			$em_event_checkout_booking_fields['em_event_booking_fields_data'] = array();
			foreach( $_POST['em_event_booking_fields_data'] as $cfd ) {
				$em_event_checkout_booking_fields['em_event_booking_fields_data'][] = absint( $cfd );
			}
			// get required field data
			if( isset( $_POST['em_event_booking_fields_data_required'] ) && count( $_POST['em_event_booking_fields_data_required'] ) > 0 ) {
				$em_event_checkout_booking_fields['em_event_booking_fields_data_required'] = array();
				foreach( $_POST['em_event_booking_fields_data_required'] as $cfdr ) {
					$em_event_checkout_booking_fields['em_event_booking_fields_data_required'][] = absint( $cfdr );
				}
			}
		}
		update_post_meta( $post_id, 'em_event_checkout_booking_fields', $em_event_checkout_booking_fields );
		// handle recurring events request
		if( isset( $_POST['em_enable_recurrence'] ) && $_POST['em_enable_recurrence'] == 1 ) {
			update_post_meta( $post_id, 'em_enable_recurrence', 1 );
			$add_recurrence = 1;
			$old_em_recurrence_step = get_post_meta( $post_id, 'em_recurrence_step', true );
			$old_em_recurrence_interval = get_post_meta( $post_id, 'em_recurrence_interval', true );
			$em_recurrence_step = (isset( $_POST['em_recurrence_step'] ) && !empty( $_POST['em_recurrence_step'] ) ) ? absint( $_POST['em_recurrence_step'] ) : 1;
			update_post_meta( $post_id, 'em_recurrence_step', $em_recurrence_step );
			// update the parent event first
			do_action( 'ep_update_parent_event_status', $post_id, $post );

			if( isset( $_POST['em_recurrence_interval'] ) && ! empty( $_POST['em_recurrence_interval'] ) ) { 
				$em_recurrence_interval = sanitize_text_field( $_POST['em_recurrence_interval'] );
				update_post_meta( $post_id, 'em_recurrence_interval', $em_recurrence_interval );
				if( ( ! empty( $old_em_recurrence_step ) && $old_em_recurrence_step == $em_recurrence_step ) && ( ! empty( $old_em_recurrence_interval ) && $old_em_recurrence_interval == $em_recurrence_interval ) ) {
					$add_recurrence = 0;
				}
				if( empty( $add_recurrence ) ) {
					// check for weekly interval
					if( $em_recurrence_interval == 'weekly' ) {
						$weekly_days = $_POST['em_selected_weekly_day'];
						$old_weekly_days = get_post_meta( $post_id, 'em_selected_weekly_day', true );
						if( $old_weekly_days != $weekly_days ) {
							$add_recurrence = 1;
						}
					}
					// check for monthly interval
					if( $em_recurrence_interval == 'monthly' ) {
						$monthly_day = $_POST['em_recurrence_monthly_day'];
						$old_monthly_day = get_post_meta( $post_id, 'em_recurrence_monthly_day', true );
						if( $old_monthly_day != $monthly_day ) {
							$add_recurrence = 1;
						} else{
							if( $monthly_day == 'day' ) {
								$em_recurrence_monthly_weekno = $_POST['em_recurrence_monthly_weekno'];
								$old_em_recurrence_monthly_weekno = get_post_meta( $post_id, 'em_recurrence_monthly_weekno', true );
								if( $old_em_recurrence_monthly_weekno != $em_recurrence_monthly_weekno ) {
									$add_recurrence = 1;
								} else{
									$em_recurrence_monthly_fullweekday = $_POST['em_recurrence_monthly_fullweekday'];
									$old_em_recurrence_monthly_fullweekday = get_post_meta( $post_id, 'em_recurrence_monthly_fullweekday', true );
									if( $old_em_recurrence_monthly_fullweekday != $em_recurrence_monthly_fullweekday ) {
										$add_recurrence = 1;
									}
								}
							}
						}
					}
					// check for yearly interval
					if( $em_recurrence_interval == 'yearly' ) {
						$yearly_day = $_POST['em_recurrence_yearly_day'];
						$old_yearly_day = get_post_meta( $post_id, 'em_recurrence_yearly_day', true );
						if( $old_yearly_day != $yearly_day ) {
							$add_recurrence = 1;
						} else{
							if( $yearly_day == 'day' ) {
								$em_recurrence_yearly_weekno = $_POST['em_recurrence_yearly_weekno'];
								$old_em_recurrence_yearly_weekno = get_post_meta( $post_id, 'em_recurrence_yearly_weekno', true );
								if( $old_em_recurrence_yearly_weekno != $em_recurrence_yearly_weekno ) {
									$add_recurrence = 1;
								} else{
									$em_recurrence_yearly_fullweekday = $_POST['em_recurrence_yearly_fullweekday'];
									$old_em_recurrence_yearly_fullweekday = get_post_meta( $post_id, 'em_recurrence_yearly_fullweekday', true );
									if( $old_em_recurrence_yearly_fullweekday != $em_recurrence_yearly_fullweekday ) {
										$add_recurrence = 1;
									} else{
										$em_recurrence_yearly_monthday = $_POST['em_recurrence_yearly_monthday'];
										$old_em_recurrence_yearly_monthday = get_post_meta( $post_id, 'em_recurrence_yearly_monthday', true );
										if( $old_em_recurrence_yearly_monthday != $em_recurrence_yearly_monthday ) {
											$add_recurrence = 1;
										}
									}
								}
							}
						}
					}
					// check for advanced interval
					if( $em_recurrence_interval == 'advanced' ) {
						$advanced_dates = $_POST['em_recurrence_advanced_dates'];
						$old_advanced_dates = get_post_meta( $post_id, 'em_recurrence_advanced_dates', true );
						if( $old_advanced_dates != $advanced_dates ) {
							$add_recurrence = 1;
						}
					}
					// check for custom dates interval
					if( $em_recurrence_interval == 'custom_dates' ) {
						$custom_dates = $_POST['em_recurrence_selected_custom_dates'];
						$old_custom_dates = get_post_meta( $post_id, 'em_recurrence_selected_custom_dates', true );
						if( $old_custom_dates != $custom_dates ) {
							$add_recurrence = 1;
						}
					}
					// check for recurrence ends
					if( isset( $_POST['em_recurrence_ends'] ) && ! empty( $_POST['em_recurrence_ends'] ) ) {
						$em_recurrence_ends = $_POST['em_recurrence_ends'];
						$old_recurrence_ends = get_post_meta( $post_id, 'em_recurrence_ends', true );
						if( $old_recurrence_ends != $em_recurrence_ends ) {
							$add_recurrence = 1;
						} else{
							if( $em_recurrence_ends == 'on' ) {
								$em_recurrence_limit = $_POST['em_recurrence_limit'];
								$old_em_recurrence_limit = get_post_meta( $post_id, 'em_recurrence_limit', true );
								if( $old_em_recurrence_limit != $em_recurrence_limit ) {
									$add_recurrence = 1;
								}
							} else{
								$em_recurrence_occurrence_time = $_POST['em_recurrence_occurrence_time'];
								$old_em_recurrence_occurrence_time = get_post_meta( $post_id, 'em_recurrence_occurrence_time', true );
								if( $old_em_recurrence_occurrence_time != $em_recurrence_occurrence_time ) {
									$add_recurrence = 1;
								}
							}
						}
					}	
				}
				if( $add_recurrence ) {
					// first delete old child events
					$this->ep_delete_child_events( $post_id );
					$em_recurrence_ends = (isset( $_POST['em_recurrence_ends'] ) && !empty( $_POST['em_recurrence_ends'] ) ) ? $_POST['em_recurrence_ends'] : 'after';
					update_post_meta( $post->ID, 'em_recurrence_ends', $em_recurrence_ends );
					$last_date_on = $stop_after = $recurrence_limit_timestamp = $start_date_only = '';
					if( $em_recurrence_ends == 'on' ) {
						$last_date_on = ep_date_to_timestamp( sanitize_text_field( $_POST['em_recurrence_limit'] ) );
						if( empty( $last_date_on ) ) {
							$last_date_on = ep_timestamp_to_date( current_time( 'timestamp' ) );
						}
						if( ! empty( $last_date_on ) ) {
							update_post_meta( $post->ID, 'em_recurrence_limit', $last_date_on );
							$recurrence_limit = new DateTime( '@' . $last_date_on );
							//$recurrence_limit->setTime( 0,0,0,0 );
							$recurrence_limit_timestamp = $recurrence_limit->getTimestamp();
						}
						// update start date format
						$start_date_only = new DateTime( '@' . $em_start_date );
						$start_date_only->setTime( 0,0,0,0 );
					}
					if( $em_recurrence_ends == 'after' ) {
						$stop_after = absint( $_POST['em_recurrence_occurrence_time'] );
						update_post_meta( $post->ID, 'em_recurrence_occurrence_time', $stop_after );
					}
					$data = array( 'start_date' => $em_start_date, 'start_time' => $em_start_time, 'end_date' => $em_end_date, 'end_time' => $em_end_time, 'recurrence_step' => $em_recurrence_step, 'recurrence_interval' => $em_recurrence_interval, 'last_date_on' => $last_date_on, 'stop_after' => $stop_after, 'recurrence_limit_timestamp' => $recurrence_limit_timestamp, 'start_date_only' => $start_date_only );
					switch( $em_recurrence_interval ) {
						case 'daily':
							$this->ep_event_daily_recurrence( $post, $data, $_POST );
							break;
						case 'weekly':
							$this->ep_event_weekly_recurrence( $post, $data, $_POST);
							break;
						case 'monthly':
							$this->ep_event_monthly_recurrence( $post, $data, $_POST );
							break;
						case 'yearly':
							$this->ep_event_yearly_recurrence( $post, $data, $_POST );
							break;
						case 'advanced':
							$this->ep_event_advanced_recurrence( $post, $data, $_POST );
							break;
						case 'custom_dates':
							$this->ep_event_custom_dates_recurrence( $post, $data, $_POST );
							break;
					}
				}
			}
		} else{
			update_post_meta( $post_id, 'em_enable_recurrence', 0 );
			// check if event have the child events and delete them
			if( isset( $_POST['ep_event_count_child_events'] ) && ! empty( $_POST['ep_event_count_child_events'] ) ) {
				$ep_event_count_child_events = absint( $_POST['ep_event_count_child_events'] );
				if( ! empty( $ep_event_count_child_events ) ) {
					//delete the child events
					$this->ep_delete_child_events( $post_id );
				}
			}
			update_post_meta( $post_id, 'em_recurrence_step', 0 );
			update_post_meta( $post_id, 'em_recurrence_interval', '' );
		}
		// add other settings meta box
		$em_event_text_color = isset( $_POST['em_event_text_color'] ) ? sanitize_text_field( $_POST['em_event_text_color'] ) : '';
		update_post_meta( $post_id, 'em_event_text_color', $em_event_text_color );
		$em_audience_notice = isset( $_POST['em_audience_notice'] ) ? sanitize_textarea_field( $_POST['em_audience_notice'] ) : '';
		update_post_meta( $post_id, 'em_audience_notice', $em_audience_notice );
		// save social info
		$em_social_links = array();
		if ( isset( $_POST['em_social_links'] ) && count( $_POST['em_social_links'] ) > 0 ) {
			foreach ( $_POST['em_social_links'] as $social_key => $social_links ) {
				if ( ! empty( $social_links ) ) {
					$em_social_links[$social_key] = sanitize_url( $social_links );
				}
			}
		}
		update_post_meta( $post_id, 'em_social_links', $em_social_links );

		// check for update recurrences
		if( ! empty( $_POST['em_enable_recurrence'] ) && $_POST['em_enable_recurrence'] == 1 ) {
			if( ! empty( $_POST['ep_event_child_events_update_confirm'] ) ) {
				$ep_event_child_events_update_confirm = $_POST['ep_event_child_events_update_confirm'];
				if( $ep_event_child_events_update_confirm == 'update_children' ) {
					// update child events
					$this->ep_update_child_events( $post_id );
				}
			}
		}
		// add result settings meta box
		$ep_select_result_page = isset( $_POST['ep_select_result_page'] ) ? sanitize_text_field( $_POST['ep_select_result_page'] ) : '';
		update_post_meta( $post_id, 'ep_select_result_page', $ep_select_result_page );
		$ep_result_start_from_type = isset( $_POST['ep_result_start_from_type'] ) ? sanitize_text_field( $_POST['ep_result_start_from_type'] ) : '';
		update_post_meta( $post_id, 'ep_result_start_from_type', $ep_result_start_from_type );
		//result start date
		$ep_result_start_date = isset( $_POST['ep_result_start_date'] ) ? ep_date_to_timestamp( sanitize_text_field( $_POST['ep_result_start_date'] ) ) : '';
		update_post_meta( $post_id, 'ep_result_start_date', $ep_result_start_date );
		//result start time
		$ep_result_start_time = isset( $_POST['ep_result_start_time'] ) ? sanitize_text_field( $_POST['ep_result_start_time'] ) : '';
		update_post_meta( $post_id, 'ep_result_start_time', $ep_result_start_time );
		$ep_result_start_days = isset( $_POST['ep_result_start_days'] ) ? sanitize_text_field( $_POST['ep_result_start_days'] ) : '';
		update_post_meta( $post_id, 'ep_result_start_days', $ep_result_start_days );
		$ep_result_start_days_option = isset( $_POST['ep_result_start_days_option'] ) ? sanitize_text_field( $_POST['ep_result_start_days_option'] ) : '';
		update_post_meta( $post_id, 'ep_result_start_days_option', $ep_result_start_days_option );
		$ep_result_start_event_option = isset( $_POST['ep_result_start_event_option'] ) ? sanitize_text_field( $_POST['ep_result_start_event_option'] ) : '';
		update_post_meta( $post_id, 'ep_result_start_event_option', $ep_result_start_event_option );
		// edit booking
		$em_allow_edit_booking = $em_edit_booking_date_data = '';
		if( ! empty( $_POST['em_allow_edit_booking'] ) ) {
			$em_allow_edit_booking = 1;
			$em_edit_booking_date_data = array(
				'em_edit_booking_date_type'         => sanitize_text_field( $_POST['em_edit_booking_date_type'] ),
				'em_edit_booking_date_date'         => ( ! empty( $_POST['em_edit_booking_date_date'] ) ? ep_date_to_timestamp( sanitize_text_field( $_POST['em_edit_booking_date_date'] ) ) : '' ),
				'em_edit_booking_date_time'         => sanitize_text_field( $_POST['em_edit_booking_date_time'] ),
				'em_edit_booking_date_days'         => sanitize_text_field( $_POST['em_edit_booking_date_days'] ),
				'em_edit_booking_date_days_option'  => sanitize_text_field( $_POST['em_edit_booking_date_days_option'] ),
				'em_edit_booking_date_event_option' => sanitize_text_field( $_POST['em_edit_booking_date_event_option'] ),
			);
		}
		update_post_meta( $post_id, 'em_allow_edit_booking', $em_allow_edit_booking );
		update_post_meta( $post_id, 'em_edit_booking_date_data', $em_edit_booking_date_data );
		do_action( 'ep_after_save_event_data', $post_id, $post );
	}

	// add ticket in the category
	public function ep_add_tickets_in_category( $cat_id, $post_id, $ticket, $cat_ticket_priority ) {
		$ticket_data['category_id']    = $cat_id;
		$ticket_data['event_id'] 	   = $post_id;
		$ticket_data['name'] 		   = addslashes( $ticket['name'] );
		$ticket_data['description']    = isset( $ticket['description'] ) ? addslashes( str_replace( '"', "'", $ticket['description'] ) ) : '';
		$ticket_data['price'] 		   = isset( $ticket['price'] ) ? $ticket['price'] : 0;
		$ticket_data['special_price']  = '';
		$ticket_data['capacity'] 	   = isset( $ticket['capacity'] ) ? absint( $ticket['capacity'] ) : 0;
		$ticket_data['is_default']     = 1;
		$ticket_data['is_event_price'] = 0;
		$ticket_data['icon'] 		   = isset( $ticket['icon'] ) ? absint( $ticket['icon'] ) : '';
		$ticket_data['priority'] 	   = $cat_ticket_priority;
		$ticket_data['status'] 		   = 1;
		$ticket_data['created_at'] 	   = date_i18n("Y-m-d H:i:s", time());
		// new
		$ticket_data['additional_fees']    = ( isset( $ticket['ep_additional_ticket_fee_data'] ) && !empty( $ticket['ep_additional_ticket_fee_data'] ) ) ? json_encode( $ticket['ep_additional_ticket_fee_data'] ) : '';
		$ticket_data['allow_cancellation'] = isset( $ticket['allow_cancellation'] ) ? absint( $ticket['allow_cancellation'] ) : 0;
		$ticket_data['show_remaining_tickets'] = isset( $ticket['show_remaining_tickets'] ) ? absint( $ticket['show_remaining_tickets'] ) : 0;
		// date
		$start_date = [];
		if( isset( $ticket['em_ticket_start_booking_type'] ) && !empty( $ticket['em_ticket_start_booking_type'] ) ) {
			$start_date['booking_type'] = $ticket['em_ticket_start_booking_type'];
			if( $ticket['em_ticket_start_booking_type'] == 'custom_date' ) {
				if( isset( $ticket['em_ticket_start_booking_date'] ) && ! empty( $ticket['em_ticket_start_booking_date'] ) ) {
					$start_date['start_date'] = $ticket['em_ticket_start_booking_date'];
				}
				if( isset( $ticket['em_ticket_start_booking_time'] ) && ! empty( $ticket['em_ticket_start_booking_time'] ) ) {
					$start_date['start_time'] = $ticket['em_ticket_start_booking_time'];
				}
			} elseif( $ticket['em_ticket_start_booking_type'] == 'event_date' ) {
				$start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
			} elseif( $ticket['em_ticket_start_booking_type'] == 'relative_date' ) {
				if( isset( $ticket['em_ticket_start_booking_days'] ) && ! empty( $ticket['em_ticket_start_booking_days'] ) ) {
					$start_date['days'] = $ticket['em_ticket_start_booking_days'];
				}
				if( isset( $ticket['em_ticket_start_booking_days_option'] ) && ! empty( $ticket['em_ticket_start_booking_days_option'] ) ) {
					$start_date['days_option'] = $ticket['em_ticket_start_booking_days_option'];
				}
				$start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
			}
		}
		$ticket_data['booking_starts'] = json_encode( $start_date );
		// end date
		$end_date = [];
		if( isset( $ticket['em_ticket_ends_booking_type'] ) && !empty( $ticket['em_ticket_ends_booking_type'] ) ) {
			$end_date['booking_type'] = $ticket['em_ticket_ends_booking_type'];
			if( $ticket['em_ticket_ends_booking_type'] == 'custom_date' ) {
				if( isset( $ticket['em_ticket_ends_booking_date'] ) && ! empty( $ticket['em_ticket_ends_booking_date'] ) ) {
					$end_date['end_date'] = $ticket['em_ticket_ends_booking_date'];
				}
				if( isset( $ticket['em_ticket_ends_booking_time'] ) && ! empty( $ticket['em_ticket_ends_booking_time'] ) ) {
					$end_date['end_time'] = $ticket['em_ticket_ends_booking_time'];
				}
			} elseif( $ticket['em_ticket_ends_booking_type'] == 'event_date' ) {
				$end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
			} elseif( $ticket['em_ticket_ends_booking_type'] == 'event_ends' ) {
				if( isset( $ticket['em_ticket_ends_booking_days'] ) && ! empty( $ticket['em_ticket_ends_booking_days'] ) ) {
					$end_date['days'] = $ticket['em_ticket_ends_booking_days'];
				}
				if( isset( $ticket['em_ticket_ends_booking_days_option'] ) && ! empty( $ticket['em_ticket_ends_booking_days_option'] ) ) {
					$end_date['days_option'] = $ticket['em_ticket_ends_booking_days_option'];
				}
				$end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
			}
		}
		$ticket_data['booking_ends'] = json_encode( $end_date );
		// visibility
		$ticket_data['visibility'] = $ticket_visibility = array();
		if( isset( $ticket['em_tickets_user_visibility'] ) && ! empty( $ticket['em_tickets_user_visibility'] ) ) {
			$ticket_visibility['em_tickets_user_visibility'] = $ticket['em_tickets_user_visibility'];
		}
		if( isset( $ticket['em_ticket_for_invalid_user'] ) && ! empty( $ticket['em_ticket_for_invalid_user'] ) ) {
			$ticket_visibility['em_ticket_for_invalid_user'] = $ticket['em_ticket_for_invalid_user'];
		}
		if( isset( $ticket['em_tickets_visibility_time_restrictions'] ) && ! empty( $ticket['em_tickets_visibility_time_restrictions'] ) ) {
			$ticket_visibility['em_tickets_visibility_time_restrictions'] = $ticket['em_tickets_visibility_time_restrictions'];
		}
		if( ! empty( $ticket_visibility ) ) {
			$ticket_data['visibility'] = json_encode( $ticket_visibility );
		}
		$ticket_data['show_ticket_booking_dates'] = (isset( $ticket['show_ticket_booking_dates'] ) ) ? 1 : 0;
		$ticket_data['min_ticket_no'] = isset( $ticket['min_ticket_no'] ) ? $ticket['min_ticket_no'] : 0;
		$ticket_data['max_ticket_no'] = isset( $ticket['max_ticket_no'] ) ? $ticket['max_ticket_no'] : 0;
		$ticket_data['offers'] = ( isset( $ticket['offers'] ) && !empty( $ticket['offers'] ) ) ? $ticket['offers'] : '';
		$ticket_data['multiple_offers_option'] = ( isset( $ticket['multiple_offers_option'] ) && !empty( $ticket['multiple_offers_option'] ) ) ? $ticket['multiple_offers_option'] : '';
		$ticket_data['multiple_offers_max_discount'] = ( isset( $ticket['multiple_offers_max_discount'] ) && !empty( $ticket['multiple_offers_max_discount'] ) ) ? $ticket['multiple_offers_max_discount'] : '';
		$ticket_data['ticket_template_id'] = ( isset( $ticket['ticket_template_id'] ) && !empty( $ticket['ticket_template_id'] ) ) ? $ticket['ticket_template_id'] : '';
		return $ticket_data;
	}

	// add individual tickets
	public function ep_add_individual_tickets( $post_id, $ticket ) {
		$ticket_data 				   = array();
		$ticket_data['category_id']    = 0;
		$ticket_data['event_id'] 	   = $post_id;
		$ticket_data['name'] 		   = addslashes( $ticket['name'] );
		$ticket_data['description']    = isset( $ticket['description'] ) ? addslashes( str_replace( '"', "'", $ticket['description'] ) ) : '';
		$ticket_data['price'] 		   = isset( $ticket['price'] ) ? $ticket['price'] : 0;
		$ticket_data['special_price']  = '';
		$ticket_data['capacity'] 	   = isset( $ticket['capacity'] ) ? absint( $ticket['capacity'] ) : 0;
		$ticket_data['is_default']     = 1;
		$ticket_data['is_event_price'] = 0;
		$ticket_data['icon'] 		   = isset( $ticket['icon'] ) ? absint( $ticket['icon'] ) : '';
		$ticket_data['priority'] 	   = 1;
		$ticket_data['status'] 		   = 1;
		$ticket_data['created_at'] 	   = date_i18n("Y-m-d H:i:s", time());
		// new
		$ticket_data['additional_fees']    = ( isset( $ticket['ep_additional_ticket_fee_data'] ) && !empty( $ticket['ep_additional_ticket_fee_data'] ) ) ? json_encode( $ticket['ep_additional_ticket_fee_data'] ) : '';
		$ticket_data['allow_cancellation'] = isset( $ticket['allow_cancellation'] ) ? absint( $ticket['allow_cancellation'] ) : 0;
		$ticket_data['show_remaining_tickets'] = isset( $ticket['show_remaining_tickets'] ) ? absint( $ticket['show_remaining_tickets'] ) : 0;
		// date
		$start_date = [];
		if( isset( $ticket['em_ticket_start_booking_type'] ) && !empty( $ticket['em_ticket_start_booking_type'] ) ) {
			$start_date['booking_type'] = $ticket['em_ticket_start_booking_type'];
			if( $ticket['em_ticket_start_booking_type'] == 'custom_date' ) {
				if( isset( $ticket['em_ticket_start_booking_date'] ) && ! empty( $ticket['em_ticket_start_booking_date'] ) ) {
					$start_date['start_date'] = $ticket['em_ticket_start_booking_date'];
				}
				if( isset( $ticket['em_ticket_start_booking_time'] ) && ! empty( $ticket['em_ticket_start_booking_time'] ) ) {
					$start_date['start_time'] = $ticket['em_ticket_start_booking_time'];
				}
			} elseif( $ticket['em_ticket_start_booking_type'] == 'event_date' ) {
				$start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
			} elseif( $ticket['em_ticket_start_booking_type'] == 'relative_date' ) {
				if( isset( $ticket['em_ticket_start_booking_days'] ) && ! empty( $ticket['em_ticket_start_booking_days'] ) ) {
					$start_date['days'] = $ticket['em_ticket_start_booking_days'];
				}
				if( isset( $ticket['em_ticket_start_booking_days_option'] ) && ! empty( $ticket['em_ticket_start_booking_days_option'] ) ) {
					$start_date['days_option'] = $ticket['em_ticket_start_booking_days_option'];
				}
				$start_date['event_option'] = $ticket['em_ticket_start_booking_event_option'];
			}
		}
		$ticket_data['booking_starts'] = json_encode( $start_date );
		// end date
		$end_date = [];
		if( isset( $ticket['em_ticket_ends_booking_type'] ) && !empty( $ticket['em_ticket_ends_booking_type'] ) ) {
			$end_date['booking_type'] = $ticket['em_ticket_ends_booking_type'];
			if( $ticket['em_ticket_ends_booking_type'] == 'custom_date' ) {
				if( isset( $ticket['em_ticket_ends_booking_date'] ) && ! empty( $ticket['em_ticket_ends_booking_date'] ) ) {
					$end_date['end_date'] = $ticket['em_ticket_ends_booking_date'];
				}
				if( isset( $ticket['em_ticket_ends_booking_time'] ) && ! empty( $ticket['em_ticket_ends_booking_time'] ) ) {
					$end_date['end_time'] = $ticket['em_ticket_ends_booking_time'];
				}
			} elseif( $ticket['em_ticket_ends_booking_type'] == 'event_date' ) {
				$end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
			} elseif( $ticket['em_ticket_ends_booking_type'] == 'relative_date' ) {
				if( isset( $ticket['em_ticket_ends_booking_days'] ) && ! empty( $ticket['em_ticket_ends_booking_days'] ) ) {
					$end_date['days'] = $ticket['em_ticket_ends_booking_days'];
				}
				if( isset( $ticket['em_ticket_ends_booking_days_option'] ) && ! empty( $ticket['em_ticket_ends_booking_days_option'] ) ) {
					$end_date['days_option'] = $ticket['em_ticket_ends_booking_days_option'];
				}
				$end_date['event_option'] = $ticket['em_ticket_ends_booking_event_option'];
			}
		}
		$ticket_data['booking_ends'] = json_encode( $end_date );
		// visibility
		$ticket_data['visibility'] = $ticket_visibility = array();
		if( isset( $ticket['em_tickets_user_visibility'] ) && ! empty( $ticket['em_tickets_user_visibility'] ) ) {
			$ticket_visibility['em_tickets_user_visibility'] = $ticket['em_tickets_user_visibility'];
		}
		if( isset( $ticket['em_ticket_for_invalid_user'] ) && ! empty( $ticket['em_ticket_for_invalid_user'] ) ) {
			$ticket_visibility['em_ticket_for_invalid_user'] = $ticket['em_ticket_for_invalid_user'];
		}
		if( isset( $ticket['em_tickets_visibility_time_restrictions'] ) && ! empty( $ticket['em_tickets_visibility_time_restrictions'] ) ) {
			$ticket_visibility['em_tickets_visibility_time_restrictions'] = $ticket['em_tickets_visibility_time_restrictions'];
		}
		if( isset( $ticket['em_ticket_visibility_user_roles'] ) && ! empty( $ticket['em_ticket_visibility_user_roles'] ) ) {
			$ticket_visibility['em_ticket_visibility_user_roles'] = $ticket['em_ticket_visibility_user_roles'];
		}
		if( ! empty( $ticket_visibility ) ) {
			$ticket_data['visibility'] = json_encode( $ticket_visibility );
		}				
		$ticket_data['show_ticket_booking_dates'] = (isset( $ticket['show_ticket_booking_dates'] ) ) ? 1 : 0;
		$ticket_data['min_ticket_no'] = isset( $ticket['min_ticket_no'] ) ? $ticket['min_ticket_no'] : 0;
		$ticket_data['max_ticket_no'] = isset( $ticket['max_ticket_no'] ) ? $ticket['max_ticket_no'] : 0;
		$ticket_data['offers']    = ( isset( $ticket['offers'] ) && !empty( $ticket['offers'] ) ) ? $ticket['offers'] : '';
		$ticket_data['multiple_offers_option'] = ( isset( $ticket['multiple_offers_option'] ) && !empty( $ticket['multiple_offers_option'] ) ) ? $ticket['multiple_offers_option'] : '';
		$ticket_data['multiple_offers_max_discount'] = ( isset( $ticket['multiple_offers_max_discount'] ) && !empty( $ticket['multiple_offers_max_discount'] ) ) ? $ticket['multiple_offers_max_discount'] : '';
		$ticket_data['ticket_template_id'] = ( isset( $ticket['ticket_template_id'] ) && !empty( $ticket['ticket_template_id'] ) ) ? $ticket['ticket_template_id'] : '';
		return $ticket_data;
	}
        
	/*
	 * Add Event Stats
	 */
	public function ep_add_event_stats_box(){
		global $post;
		echo do_action( 'ep_event_stats_list', $post );
	}
	/**
	 * Add Performers meta box
	 */
	public function ep_add_event_performer_box() {
		global $post;
		$performer_ids = array();
		if( !empty( $post ) && isset( $post->ID ) && !empty( $post->ID ) ) {
			$performer_ids = get_post_meta( $post->ID, 'em_performer', true );
		}
		$performers = EventM_Factory_Service::ep_get_performers( array( 'id', 'name' ) );?>
		<div id="taxonomy-post_tag" class="categorydiv">
			<ul><?php 
				foreach( $performers as $performer ) {
					$checked = '';
					if( ! empty( $performer_ids ) ) {
						if( in_array( $performer['id'], $performer_ids ) ) {
							$checked = 'checked="checked"';
						}
					}?>
					<li id="<?php echo esc_attr( $performer['id'] );?>">
						<label>
							<input type="checkbox" name="tax_input[em_performer][]" id="<?php echo esc_attr( $performer['id'] );?>" value="<?php echo esc_attr( $performer['id'] );?>" <?php echo esc_attr( $checked );?> /> <?php echo esc_html( $performer['name'] );?>
						</label>
					</li><?php
				}?>
			</ul>
		</div><?php
	}

	/**
	 * Add event gallery meta box
	 */
	public function ep_add_event_gallery_box() {
		global $post;
		$em_gallery_image_ids = get_post_meta( $post->ID, 'em_gallery_image_ids', true );
		if( ! empty( $em_gallery_image_ids ) ) {
			$em_gallery_image_ids = explode( ',', $em_gallery_image_ids );
		} else{
			$em_gallery_image_ids = array();
		}?>
		<div id="ep_event_gallery_container">
			<ul class="ep_gallery_images ep-d-flex ep-align-items-center ep-content-left"><?php
				$attachments         = array_filter( $em_gallery_image_ids );
				$update_meta         = false;
				$updated_gallery_ids = array();
				if ( ! empty( $attachments ) ) {
					foreach ( $attachments as $attachment_id ) {
						$attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );
						// if attachment is empty skip.
						if ( empty( $attachment ) ) {
							$update_meta = true;
							continue;
						}?>
						<li class="ep-gal-img" data-attachment_id="<?php echo esc_attr( $attachment_id ); ?>">
							<?php echo $attachment; ?>
							<div class="ep-gal-img-delete"><span class="em-event-gallery-remove dashicons dashicons-trash"></span></div>
						</li>
						<?php
						// rebuild ids to be saved.
						$updated_gallery_ids[] = $attachment_id;
					}
					// need to update product meta to set new gallery ids
					if ( $update_meta ) {
						update_post_meta( $post->ID, 'em_gallery_image_ids', implode( ',', $updated_gallery_ids ) );
					}
				}?>
			</ul>
			<input type="hidden" id="em_gallery_image_ids" name="em_gallery_image_ids" value="<?php echo esc_attr( implode( ',', $updated_gallery_ids ) ); ?>" />
		</div>
		<p class="ep_add_event_gallery hide-if-no-js">
			<a href="#" 
				data-choose="<?php esc_attr_e( 'Add images to event gallery', 'eventprime-event-calendar-management' ); ?>" 
				data-update="<?php esc_attr_e( 'Add to gallery', 'eventprime-event-calendar-management' ); ?>" 
				data-delete="<?php esc_attr_e( 'Delete image', 'eventprime-event-calendar-management' ); ?>" 
				data-text="<?php esc_attr_e( 'Delete', 'eventprime-event-calendar-management' ); ?>"
			>
				<?php esc_html_e( 'Add event gallery images', 'eventprime-event-calendar-management' ); ?>
			</a>
		</p><?php
	}

	/**
	 * Get post existing categories.
	 * 
	 * @param int $post_id Post Id.
	 * 
	 * @return object
	 */
	private static function get_existing_category_lists( $post_id ) {
		global $wpdb;
		$cat_table_name = $wpdb->prefix.'eventprime_ticket_categories';
		$get_field_data = $wpdb->get_results( $wpdb->prepare( "SELECT `id`, `name`, `capacity` FROM $cat_table_name WHERE `event_id` = %d AND `status` = 1 ORDER BY `priority` ASC", $post_id ) );
		return $get_field_data;
	}

	/**
	 * Get category tickets.
	 * 
	 * @param int $post_id Post Id.
	 * 
	 * @param int $cat_id Category Id.
	 * 
	 * @param bool $reset_keys Reset booking start and end keys.
	 * 
	 * @return object
	 */
	private static function get_existing_category_ticket_lists( $post_id, $cat_id, $reset_keys = true ){
		$get_field_data = array();
		if( !empty( $post_id ) && !empty( $cat_id ) ) {
			global $wpdb;
			$price_options_table = $wpdb->prefix.'em_price_options';
			// Format tickets for start booking and end bookings keys. This need to be update for sorting.
			$get_field_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $price_options_table WHERE `event_id` = %d AND `category_id` = %d AND `status` = 1 ORDER BY `priority` ASC", $post_id, $cat_id ) );
			if( ! empty( $get_field_data ) ) {
				$get_field_data = stripslashes_deep( $get_field_data );
				if( ! empty( $reset_keys ) ) {
					foreach( $get_field_data as $cat_key => $cat_ticket ) {
						if( ! empty( $cat_ticket->booking_starts ) ) {
							$booking_start = json_decode( stripslashes( $cat_ticket->booking_starts ) );
							if( ! empty( $booking_start ) ) {
								$updated_booking_start = array();
								if( isset( $booking_start->booking_type ) ) {
									$updated_booking_start['em_ticket_start_booking_type'] = $booking_start->booking_type;
								}
								if( isset( $booking_start->start_date ) ) {
									$updated_booking_start['em_ticket_start_booking_date'] = $booking_start->start_date;
								}
								if( isset( $booking_start->start_time ) ) {
									$updated_booking_start['em_ticket_start_booking_time'] = $booking_start->start_time;
								}
								if( isset( $booking_start->event_option ) ) {
									$updated_booking_start['em_ticket_start_booking_event_option'] = $booking_start->event_option;
								}
								if( isset( $booking_start->days ) ) {
									$updated_booking_start['em_ticket_start_booking_days'] = $booking_start->days;
								}
								if( isset( $booking_start->days_option ) ) {
									$updated_booking_start['em_ticket_start_booking_days_option'] = $booking_start->days_option;
								}
								$cat_ticket->booking_starts = json_encode( $updated_booking_start );
							}
						}
						
						if( ! empty( $cat_ticket->booking_ends ) ) {
							$booking_end = json_decode( stripslashes( $cat_ticket->booking_ends ) );
							if( ! empty( $booking_end ) ) {
								$updated_booking_end = array();
								if( isset( $booking_end->booking_type ) ) {
									$updated_booking_end['em_ticket_ends_booking_type'] = $booking_end->booking_type;
								}
								if( isset( $booking_end->end_date ) ) {
									$updated_booking_end['em_ticket_ends_booking_date'] = $booking_end->end_date;
								}
								if( isset( $booking_end->end_time ) ) {
									$updated_booking_end['em_ticket_ends_booking_time'] = $booking_end->end_time;
								}
								if( isset( $booking_end->event_option ) ) {
									$updated_booking_end['em_ticket_ends_booking_event_option'] = $booking_end->event_option;
								}
								if( isset( $booking_end->days ) ) {
									$updated_booking_end['em_ticket_ends_booking_days'] = $booking_end->days;
								}
								if( isset( $booking_end->days_option ) ) {
									$updated_booking_end['em_ticket_ends_booking_days_option'] = $booking_end->days_option;
								}
								$cat_ticket->booking_ends = json_encode( $updated_booking_end );
							}
						}

						$get_field_data[$cat_key] = $cat_ticket;
					}
				}
			}
		}
		return $get_field_data;
	}

	/**
	 * Get individual tickets.
	 * 
	 * @param int $post_id Post Id.
	 * 
	 * @param bool $reset_keys Reset booking start and end keys.
	 * 
	 * @return object
	 */
	private static function get_existing_individual_ticket_lists( $post_id, $reset_keys = true ){
		$get_field_data = array();
		if( !empty( $post_id ) ) {
			global $wpdb;
			$price_options_table = $wpdb->prefix.'em_price_options';
			// Format tickets for start booking and end bookings keys. This need to be update for sorting.
			$get_field_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $price_options_table WHERE `event_id` = %d AND `category_id` = 0 AND `status` = 1 ORDER BY `priority` ASC", $post_id ) );
			if( ! empty( $get_field_data ) ) {
				$get_field_data = stripslashes_deep( $get_field_data );
				if( ! empty( $reset_keys ) ) {
					foreach( $get_field_data as $cat_key => $cat_ticket ) {
						if( ! empty( $cat_ticket->booking_starts ) ) {
							$booking_start = json_decode( stripslashes( $cat_ticket->booking_starts ) );
							if( ! empty( $booking_start ) ) {
								$updated_booking_start = array();
								if( isset( $booking_start->booking_type ) ) {
									$updated_booking_start['em_ticket_start_booking_type'] = $booking_start->booking_type;
								}
								if( isset( $booking_start->start_date ) ) {
									$updated_booking_start['em_ticket_start_booking_date'] = $booking_start->start_date;
								}
								if( isset( $booking_start->start_time ) ) {
									$updated_booking_start['em_ticket_start_booking_time'] = $booking_start->start_time;
								}
								if( isset( $booking_start->event_option ) ) {
									$updated_booking_start['em_ticket_start_booking_event_option'] = $booking_start->event_option;
								}
								if( isset( $booking_start->days ) ) {
									$updated_booking_start['em_ticket_start_booking_days'] = $booking_start->days;
								}
								if( isset( $booking_start->days_option ) ) {
									$updated_booking_start['em_ticket_start_booking_days_option'] = $booking_start->days_option;
								}
								$cat_ticket->booking_starts = json_encode( $updated_booking_start );
							}
						}
						
						if( ! empty( $cat_ticket->booking_ends ) ) {
							$booking_end = json_decode( stripslashes( $cat_ticket->booking_ends ) );
							if( ! empty( $booking_end ) ) {
								$updated_booking_end = array();
								if( isset( $booking_end->booking_type ) ) {
									$updated_booking_end['em_ticket_ends_booking_type'] = $booking_end->booking_type;
								}
								if( isset( $booking_end->end_date ) ) {
									$updated_booking_end['em_ticket_ends_booking_date'] = $booking_end->end_date;
								}
								if( isset( $booking_end->end_time ) ) {
									$updated_booking_end['em_ticket_ends_booking_time'] = $booking_end->end_time;
								}
								if( isset( $booking_end->event_option ) ) {
									$updated_booking_end['em_ticket_ends_booking_event_option'] = $booking_end->event_option;
								}
								if( isset( $booking_end->days ) ) {
									$updated_booking_end['em_ticket_ends_booking_days'] = $booking_end->days;
								}
								if( isset( $booking_end->days_option ) ) {
									$updated_booking_end['em_ticket_ends_booking_days_option'] = $booking_end->days_option;
								}
								$cat_ticket->booking_ends = json_encode( $updated_booking_end );
							}
						}

						$get_field_data[$cat_key] = $cat_ticket;
					}
				}
			}
		}
		return $get_field_data;
	}

	/**
	 * Delete old child events
	 * 
	 * @param int $post_id Parent Post Id.
	 */
	public function ep_delete_child_events( $post_id ) {
		global $wpdb;
		// get child events
		$child_events = EventM_Factory_Service::ep_get_child_events( $post_id );
		if( ! empty( $child_events ) ) {
			$booking_controllers = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
            foreach ( $child_events as $child_post ) {
				// check category and tickets and delete them
				$cat_table_name = $wpdb->prefix.'eventprime_ticket_categories';
				$price_options_table = $wpdb->prefix.'em_price_options';
				$cates = self::get_existing_category_lists( $child_post->ID );
				if( ! empty( $cates ) ) {
					foreach( $cates as $category ) {
						if( ! empty( $category->id ) ) {
							// first delete tickets of this category
							$cat_tickets = self::get_existing_category_ticket_lists( $child_post->ID, $category->id );
							if( ! empty( $cat_tickets ) ) {
								foreach( $cat_tickets as $ticket ) {
									$wpdb->delete( $price_options_table, array( 'id' => $ticket->id ) );
								}
							}
							$wpdb->delete( $cat_table_name, array( 'id' => $category->id ) );

						}
					}
				}
				// get individual tickets
				$individual_tickets = self::get_existing_individual_ticket_lists( $child_post->ID );
				if( ! empty( $individual_tickets ) ) {
					foreach( $individual_tickets as $ticket ) {
						$wpdb->delete( $price_options_table, array( 'id' => $ticket->id ) );
					}
				}

				// delete booking of this event
				$event_bookings = $booking_controllers->get_event_bookings_by_event_id( $child_post->ID );
				if( ! empty( $event_bookings ) ) {
					foreach( $event_bookings as $booking ) {
						// delete booking
						wp_delete_post( $booking->ID, true );
					}
				}

				// delete terms relationships
				wp_delete_object_term_relationships( $child_post->ID, array( EM_EVENT_VENUE_TAX, EM_EVENT_TYPE_TAX, EM_EVENT_ORGANIZER_TAX ) );
				// delete event
                wp_delete_post( $child_post->ID, true );
				// delete child event ext data
				do_action( 'ep_delete_event_data', $child_post->ID );
            }
        }
	}

	/**
	 * Method to create daily recurring events
	 * 
	 * @param object $post Post. 
	 * 
	 * @param array $data Data.
	 */
	public function ep_event_daily_recurrence( $post,  $data = array(), $post_data = array() ) {
        $start_date 	 = new DateTime( '@' . $data['start_date'] );
        $end_date 		 = new DateTime( '@' . $data['end_date'] );
		$modify_string   = $this->get_date_modification_string( $data );
		$counter = 0;
		$old_post_metas = get_post_custom( $post->ID );
		$new_posts = array();
		// Last date on condition
		if( ! empty( $data['last_date_on'] ) ) {
			while( $data['start_date_only']->modify( $modify_string )->getTimestamp() <= $data['recurrence_limit_timestamp'] ) {
				$start_date->modify( $modify_string );
				$end_date->modify( $modify_string );
				// get date timestamp
				$child_start_date = $start_date->getTimestamp();
				$child_end_date   = $end_date->getTimestamp();
				// create child event
				$new_post_id = $this->ep_create_child_event( $post, $child_start_date, $child_end_date, $counter, $post_data );
				if( !empty( $new_post_id ) && is_int( $new_post_id ) ) {
					// set parent id to recurring post
					update_post_meta( $new_post_id, 'em_is_daily_recurrence', 1 );
				}
				$counter++;
			}
		} elseif( !empty( $data['stop_after'] ) ) { // stop after condition
			while( $counter < $data['stop_after'] ) {
				$start_date->modify( $modify_string );
				$end_date->modify( $modify_string );
				// get date timestamp
				$child_start_date = $start_date->getTimestamp();
				$child_end_date   = $end_date->getTimestamp();
				// create child event
				$new_post_id = $this->ep_create_child_event( $post, $child_start_date, $child_end_date, $counter, $post_data );
				if( !empty( $new_post_id ) && is_int( $new_post_id ) ) {
					// set parent id to recurring post
					update_post_meta( $new_post_id, 'em_is_daily_recurrence', 1 );
				}
				$counter++;
			}
		}
	}

	/**
	 * Method to create weekly recurring events
	 * 
	 * @param object $post Post. 
	 * 
	 * @param array $data Data.
	 */
	public function ep_event_weekly_recurrence( $post,  $data = array(), $post_data = array()  ) {
		$week_days = ( isset( $post_data['em_selected_weekly_day'] ) && ! empty( $post_data['em_selected_weekly_day'] ) ? $post_data['em_selected_weekly_day'] : [] );
		if( count( $week_days ) > 0 ) {
			update_post_meta( $post->ID, 'em_selected_weekly_day', $week_days );
			$step = absint( $data['recurrence_step'] );
			$step_string = ($step > 1) ? ' weeks' : ' week';
			// start modify string
			$full_week_days = ep_get_week_day_full();
			$day_name = $full_week_days[$week_days[0]];
			$wk = 1;$wstart = 0;
			if( $step == 1 ){
				$modify_string = 'Next ' . $day_name;
			} elseif( $step == 2 ){
				$modify_string = 'Second ' . $day_name;
			} elseif( $step == 3 ){
				$modify_string = 'Third ' . $day_name;   
			} elseif( $step == 4 ){
				$modify_string = 'Fourth ' . $day_name;
			} elseif( $step == 5 ){
				$modify_string = 'Fifth ' . $day_name;
			} elseif( $step == 6 ){
				$modify_string = 'Sixth ' . $day_name;   
			} elseif( $step == 7 ){
				$modify_string = 'Seventh ' . $day_name;
			} else{
				$modify_string = $day_name . ' +' . $step . ' ' . $step_string;
			}

			$start_date 	 	   = new DateTime( '@' . $data['start_date'] );
			$end_date 		 	   = new DateTime( '@' . $data['end_date'] );
			$counter 			   = 0;
			$old_post_metas 	   = get_post_custom( $post->ID );
			$default_modify_string = $modify_string;
			// Last date on condition
			if( ! empty( $data['last_date_on'] ) ) {
				while( $data['start_date_only']->modify( $modify_string )->getTimestamp() <= $data['recurrence_limit_timestamp'] ) {
					if( $counter == 0 ) {
						$new_modify_string = $this->get_new_modify_string( $start_date, $modify_string );
						$start_date->modify( $new_modify_string );
						$end_date->modify( $new_modify_string );
					} else{
						$start_date->modify( $modify_string );
						$end_date->modify( $modify_string );
					}
					
					// get date timestamp
					$child_start_date = $start_date->getTimestamp();
					$child_end_date   = $end_date->getTimestamp();
					// create child event
					$new_post_id = $this->ep_create_child_event( $post, $child_start_date, $child_end_date, $counter, $post_data );
					if( !empty( $new_post_id ) && is_int( $new_post_id ) ) {
						// set parent id to recurring post
						update_post_meta( $new_post_id, 'em_is_weekly_recurrence', 1 );
						$counter++;
					}

					if( count( $week_days ) > 1 ){
						foreach ($week_days as $key => $value ) {
							if( $wstart == 0 ) {
								$wstart = 1;
								continue;
							}
							$day_name = $full_week_days[$value];
							$modify_string = 'next '.$day_name;
							$wk++;
							if( $data['start_date_only']->modify( $modify_string )->getTimestamp() <= $data['recurrence_limit_timestamp'] ) {
								// get date of next recure
								$new_modify_string = $this->get_new_modify_string( $start_date, $modify_string );
								$start_date->modify( $new_modify_string );
								$end_date->modify( $new_modify_string );
								// get date timestamp
								$child_start_date = $start_date->getTimestamp();
								$child_end_date   = $end_date->getTimestamp();
								// create child event
								$new_post_id = $this->ep_create_child_event( $post, $child_start_date, $child_end_date, $counter, $post_data );
								if( !empty( $new_post_id ) && is_int( $new_post_id ) ) {
									// set parent id to recurring post
									update_post_meta( $new_post_id, 'em_is_weekly_recurrence', 1 );
									$counter++;
								}
							}
							if( $wk == count( $week_days ) ) {
								// move to start condition
								$wk = 1;$wstart = 0;
								$day_name = $full_week_days[$week_days[0]];
								$newstep = $step - 1;
								//echo $modify_string = $day_name. ' +'. $newstep. ' '. $step_string;echo "<br>";
								$modify_string = $default_modify_string;
								break;
							}
						}
					}
				}
			} elseif( ! empty( $data['stop_after'] ) ) { // stop after condition
				while( $counter < $data['stop_after'] ) {
					if( $counter == 0 ) {
						$new_modify_string = $this->get_new_modify_string( $start_date, $modify_string );
						$start_date->modify( $new_modify_string );
						$end_date->modify( $new_modify_string );
					} else{
						$start_date->modify( $modify_string );
						$end_date->modify( $modify_string );
					}
					// get date timestamp
					$child_start_date = $start_date->getTimestamp();
					$child_end_date   = $end_date->getTimestamp();
					// create child event
					$new_post_id = $this->ep_create_child_event( $post, $child_start_date, $child_end_date, $counter, $post_data );
					if( !empty( $new_post_id ) && is_int( $new_post_id ) ) {
						// set parent id to recurring post
						update_post_meta( $new_post_id, 'em_is_weekly_recurrence', 1 );
						$counter++;
					}

					if( count( $week_days ) > 1 ){
						foreach ($week_days as $key => $value ) {
							if( $wstart == 0 ) {
								$wstart = 1;
								continue;
							}
							$day_name = $full_week_days[$value];
							$modify_string = 'next '.$day_name;
							$wk++;
							if( $counter < $data['stop_after'] ) {
								// get date of next recure
								$new_modify_string = $this->get_new_modify_string( $start_date, $modify_string );
								$start_date->modify( $new_modify_string );
								$end_date->modify( $new_modify_string );
								// get date timestamp
								$child_start_date = $start_date->getTimestamp();
								$child_end_date   = $end_date->getTimestamp();
								// create child event
								$new_post_id = $this->ep_create_child_event( $post, $child_start_date, $child_end_date, $counter, $post_data );
								if( !empty( $new_post_id ) && is_int( $new_post_id ) ) {
									// set parent id to recurring post
									update_post_meta( $new_post_id, 'em_is_weekly_recurrence', 1 );
									$counter++;
								}
							}
							if($wk == count($week_days)){
								// move to start condition
								$wk = 1;$wstart = 0;
								$day_name = $full_week_days[$week_days[0]];
								$newstep = $step - 1;
								$modify_string = $default_modify_string;
								break;
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Method to create monthly recurring events
	 * 
	 * @param object $post Post. 
	 * 
	 * @param array $data Data.
	 */
	public function ep_event_monthly_recurrence( $post,  $data = array(), $post_data = array() ) {
		$em_recurrence_monthly_day = isset( $post_data['em_recurrence_monthly_day'] ) ? $post_data['em_recurrence_monthly_day'] : '';
		update_post_meta( $post->ID, 'em_recurrence_monthly_day', $em_recurrence_monthly_day );
		$data['em_recurrence_monthly_day'] = $em_recurrence_monthly_day;
		if( $em_recurrence_monthly_day == 'day' ) {
			$em_recurrence_monthly_weekno = isset( $post_data['em_recurrence_monthly_weekno'] ) ? $post_data['em_recurrence_monthly_weekno'] : '';
			$em_recurrence_monthly_fullweekday = isset( $post_data['em_recurrence_monthly_fullweekday'] ) ? $post_data['em_recurrence_monthly_fullweekday'] : '';
			update_post_meta( $post->ID, 'em_recurrence_monthly_weekno', $em_recurrence_monthly_weekno );
			update_post_meta( $post->ID, 'em_recurrence_monthly_fullweekday', $em_recurrence_monthly_fullweekday );
			$data['em_recurrence_monthly_weekno'] = $em_recurrence_monthly_weekno;
			$data['em_recurrence_monthly_fullweekday'] = $em_recurrence_monthly_fullweekday;
		}

		$start_date     = new DateTime( '@' . $data['start_date'] );
		$end_date 	    = new DateTime( '@' . $data['end_date'] );
		$modify_string  = $this->get_date_modification_string( $data );
		$old_post_metas = get_post_custom( $post->ID );
		// get date of next recure
		$new_modify_string = $this->get_new_modify_string( $start_date, $modify_string );
		$counter = 0;
		// Last date on condition
		if( ! empty( $data['last_date_on'] ) ) {
			while( $data['start_date_only']->modify( $new_modify_string )->getTimestamp() <= $data['recurrence_limit_timestamp'] ) {
				$start_date->modify( $new_modify_string );
				$end_date->modify( $new_modify_string );
				// get date timestamp
				$child_start_date = $start_date->getTimestamp();
				$child_end_date   = $end_date->getTimestamp();
				// create child event
				$new_post_id = $this->ep_create_child_event( $post, $child_start_date, $child_end_date, $counter, $post_data );
				if( !empty( $new_post_id ) && is_int( $new_post_id ) ) {
					// set parent id to recurring post
					update_post_meta( $new_post_id, 'em_is_monthly_recurrence', 1 );
					$counter++;
				}
				// get date of next recure
				$new_modify_string = $this->get_new_modify_string( $start_date, $modify_string );
			}
		} elseif( ! empty( $data['stop_after'] ) ) { // stop after condition
			while( $counter < $data['stop_after'] ) {
				$start_date->modify( $new_modify_string );
				$end_date->modify( $new_modify_string );
				// get date timestamp
				$child_start_date = $start_date->getTimestamp();
				$child_end_date   = $end_date->getTimestamp();
				// create child event
				$new_post_id = $this->ep_create_child_event( $post, $child_start_date, $child_end_date, $counter, $post_data );
				if( !empty( $new_post_id ) && is_int( $new_post_id ) ) {
					// set parent id to recurring post
					update_post_meta( $new_post_id, 'em_is_monthly_recurrence', 1 );
					$counter++;
				}
				// get date of next recure
				$new_modify_string = $this->get_new_modify_string( $start_date, $modify_string );
			}
		}
	}

	/**
	 * Method to create yearly recurring events
	 * 
	 * @param object $post Post. 
	 * 
	 * @param array $data Data.
	 */
	public function ep_event_yearly_recurrence( $post,  $data = array(), $post_data =array() ) {
		$em_recurrence_yearly_day = isset( $post_data['em_recurrence_yearly_day'] ) ? $post_data['em_recurrence_yearly_day'] : '';
		update_post_meta( $post->ID, 'em_recurrence_yearly_day', $em_recurrence_yearly_day );
		$data['em_recurrence_yearly_day'] = $em_recurrence_yearly_day;
		$current_year = date( 'Y' );
		if( $em_recurrence_yearly_day == 'day' ) {
			$em_recurrence_yearly_weekno = isset( $post_data['em_recurrence_yearly_weekno'] ) ? $post_data['em_recurrence_yearly_weekno'] : '';
			$em_recurrence_yearly_fullweekday = isset( $post_data['em_recurrence_yearly_fullweekday'] ) ? $post_data['em_recurrence_yearly_fullweekday'] : '';
			$em_recurrence_yearly_monthday = isset( $post_data['em_recurrence_yearly_monthday'] ) ? $post_data['em_recurrence_yearly_monthday'] : '';
			update_post_meta( $post->ID, 'em_recurrence_yearly_weekno', $em_recurrence_yearly_weekno );
			update_post_meta( $post->ID, 'em_recurrence_yearly_fullweekday', $em_recurrence_yearly_fullweekday );
			update_post_meta( $post->ID, 'em_recurrence_yearly_monthday', $em_recurrence_yearly_monthday );
			$data['em_recurrence_yearly_weekno'] = $em_recurrence_yearly_weekno;
			$data['em_recurrence_yearly_fullweekday'] = $em_recurrence_yearly_fullweekday;
			$data['em_recurrence_yearly_monthday'] = $em_recurrence_yearly_monthday;
			$data['em_recurrence_yearly_year'] = $current_year;
		}
		$start_date     = new DateTime( '@' . $data['start_date'] );
		$end_date 	    = new DateTime( '@' . $data['end_date'] );
		$modify_string  = $this->get_date_modification_string( $data );
		$old_post_metas = get_post_custom( $post->ID );
		// get date of next recure
		$new_modify_string = $this->get_new_modify_string( $start_date, $modify_string );
		$counter = 0;
		// Last date on condition
		if( ! empty( $data['last_date_on'] ) ) {
			while( $data['start_date_only']->modify( $new_modify_string )->getTimestamp() <= $data['recurrence_limit_timestamp'] ) {
				$start_date->modify( $new_modify_string );
				$end_date->modify( $new_modify_string );
				// get date timestamp
				$child_start_date = $start_date->getTimestamp();
				$child_end_date   = $end_date->getTimestamp();
				// create child event
				$new_post_id = $this->ep_create_child_event( $post, $child_start_date, $child_end_date, $counter, $post_data );
				if( !empty( $new_post_id ) && is_int( $new_post_id ) ) {
					// set parent id to recurring post
					update_post_meta( $new_post_id, 'em_is_yearly_recurrence', 1 );
					$counter++;
				}
				// get date of next recure
				$data['em_recurrence_yearly_year'] = $data['em_recurrence_yearly_year'] + $data['recurrence_step'];
				$modify_string  = $this->get_date_modification_string( $data );
				$new_modify_string = $this->get_new_modify_string( $start_date, $modify_string );
			}
		} elseif( ! empty( $data['stop_after'] ) ) { // stop after condition
			while( $counter < $data['stop_after'] ) {
				$start_date->modify( $new_modify_string );
				$end_date->modify( $new_modify_string );
				// get date timestamp
				$child_start_date = $start_date->getTimestamp();
				$child_end_date   = $end_date->getTimestamp();
				// create child event
				$new_post_id = $this->ep_create_child_event( $post, $child_start_date, $child_end_date, $counter, $post_data );
				if( !empty( $new_post_id ) && is_int( $new_post_id ) ) {
					// set parent id to recurring post
					update_post_meta( $new_post_id, 'em_is_yearly_recurrence', 1 );
					$counter++;
				}
				// get date of next recure
				$data['em_recurrence_yearly_year'] = $data['em_recurrence_yearly_year'] + $data['recurrence_step'];
				$modify_string  = $this->get_date_modification_string( $data );
				$new_modify_string = $this->get_new_modify_string( $start_date, $modify_string );
			}
		}
	}

	/**
	 * Method to create advanced recurring events
	 * 
	 * @param object $post Post. 
	 * 
	 * @param array $data Data.
	 */
	public function ep_event_advanced_recurrence( $post,  $data = array(),$post_data = array() ) {
		if(isset($post_data['em_recurrence_advanced_dates']) && is_array($post_data['em_recurrence_advanced_dates'])){
			$em_recurrence_advanced_dates = $post_data['em_recurrence_advanced_dates'];
		}else{
			$em_recurrence_advanced_dates = isset( $post_data['em_recurrence_advanced_dates'] ) ? json_decode( stripslashes( $post_data['em_recurrence_advanced_dates'] ) )  : '';
		}
		update_post_meta( $post->ID, 'em_recurrence_advanced_dates', $em_recurrence_advanced_dates );
		$data['em_recurrence_advanced_dates'] = $em_recurrence_advanced_dates;
		$weeknos_data  = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
		$start_date    = new DateTime( '@' . $data['start_date'] );
		$end_date 	   = new DateTime( '@' . $data['end_date'] );
		$modify_string = '';
		if( ! empty( $em_recurrence_advanced_dates ) ) {
			$m = date('m');
			$y = date('Y');
			$i = 0;
			$step = absint( $data['recurrence_step'] );
			$stop_recurr = 0;
			$counter = 1; 
			// Last date on condition
			if( ! empty( $data['last_date_on'] ) ) {
				$recurr_limit_month = date( 'm', $data['recurrence_limit_timestamp'] );
				$recurr_limit_year  = date( 'Y', $data['recurrence_limit_timestamp'] );
                while( $stop_recurr == 0 ) {
                    if( $i > 0 ){
                        $m += $step;
                        if( $m > 12 ){
                            $y++;
                            $monDiff = $m - 12;
                            $m = $monDiff;
                        }
                        if( $y == $recurr_limit_year ){
                            if( $m > $recurr_limit_month ){
                                $stop_recurr = 1;
                                break;
                            }
                        }
                    }
					foreach( $em_recurrence_advanced_dates as $adv ) {
                        $advs = explode( "-", $adv );
                       //checking whether a day name occurs in the given week no of the month or not
                        $dates = $this->nthDayInMonth( $advs[1], array_search($advs[0], $weeknos_data ), $m, $y );
						if( ! empty( $dates ) ) {
							if( strtotime( $dates ) < $data['start_date'] ) continue;
							$newdates = date_create( $dates );
                            $child_start_date1 = date_create( date( "Y-m-d", $data['start_date'] ) );
                            $start_date_diff = date_diff( $child_start_date1, $newdates );
                            $modify_string = $start_date_diff->days > 1 ? '+'.$start_date_diff->days.' days' : '+'.$start_date_diff->days.' day';
                            $start_date->modify( $modify_string );
                            if( $start_date->getTimestamp() > $data['recurrence_limit_timestamp'] ){
                                $stop_recurr = 1;
                                break;
                            }
                            $end_date->modify( $modify_string );
							// get date timestamp
							$child_start_date = $start_date->getTimestamp();
							$child_end_date   = $end_date->getTimestamp();
							// create child event
							$new_post_id = $this->ep_create_child_event( $post, $child_start_date, $child_end_date, $counter, $post_data );
							if( !empty( $new_post_id ) && is_int( $new_post_id ) ) {
								// set parent id to recurring post
								update_post_meta( $new_post_id, 'em_is_advanced_recurrence', 1 );
								$counter++;
							}
                            // reset the variable so we can start from the actual start & end dates
                            $start_date = new DateTime( '@' . $data['start_date'] );
							$end_date 	= new DateTime( '@' . $data['end_date'] );
						}
					}
					$i++;
				}
			} elseif( ! empty( $data['stop_after'] ) ) { // stop after condition
				while( $counter < $data['stop_after'] ) {
					if( $i > 0 ){
                        $m += $step;
                        if( $m > 12 ){
                            $y++;
                            $monDiff = $m - 12;
                            $m = $monDiff;
                        }
                    }
					foreach( $em_recurrence_advanced_dates as $adv ) {
                        $advs = explode( "-", $adv );
                       //checking whether a day name occurs in the given week no of the month or not
                        $dates = $this->nthDayInMonth( $advs[1], array_search($advs[0], $weeknos_data ), $m, $y );
						if( ! empty( $dates ) ) {
							if( strtotime( $dates ) < $data['start_date'] ) continue;
							$newdates = date_create( $dates );
                            $child_start_date1 = date_create( date( "Y-m-d", $data['start_date'] ) );
                            $start_date_diff = date_diff( $child_start_date1, $newdates );
                            $modify_string = $start_date_diff->days > 1 ? '+'.$start_date_diff->days.' days' : '+'.$start_date_diff->days.' day';
                            $start_date->modify( $modify_string );
                            $end_date->modify( $modify_string );
							// get date timestamp
							$child_start_date = $start_date->getTimestamp();
							$child_end_date   = $end_date->getTimestamp();
							// create child event
							$new_post_id = $this->ep_create_child_event( $post, $child_start_date, $child_end_date, $counter, $post_data );
							if( !empty( $new_post_id ) && is_int( $new_post_id ) ) {
								// set parent id to recurring post
								update_post_meta( $new_post_id, 'em_is_advanced_recurrence', 1 );
								$counter++;
							}
							if( $counter >= $data['stop_after'] ){
                                $stop_recurr = 1;
                                break;
                            }
                            // reset the variable so we can start from the actual start & end dates
                            $start_date = new DateTime( '@' . $data['start_date'] );
							$end_date 	= new DateTime( '@' . $data['end_date'] );
						}
					}
					$i++;
				}
			}
		}
	}

	/**
	 * Method to create custom dates recurring events
	 * 
	 * @param object $post Post. 
	 * 
	 * @param array $data Data.
	 */
	public function ep_event_custom_dates_recurrence( $post,  $data = array(),$post_data = array() ) {
		if(isset($post_data['em_recurrence_selected_custom_dates']) && is_array($post_data['em_recurrence_selected_custom_dates'])){
			$em_recurrence_selected_custom_dates = $post_data['em_recurrence_selected_custom_dates'];
		}else{
			$em_recurrence_selected_custom_dates = isset( $post_data['em_recurrence_selected_custom_dates'] ) ? json_decode( stripslashes( $post_data['em_recurrence_selected_custom_dates'] ) )  : '';
		}
		update_post_meta( $post->ID, 'em_recurrence_selected_custom_dates', $em_recurrence_selected_custom_dates );
		$data['em_recurrence_selected_custom_dates'] = $em_recurrence_selected_custom_dates;
		if( ! empty( $em_recurrence_selected_custom_dates ) ) {
			$start_date    = new DateTime( '@' . $data['start_date'] );
			$end_date 	   = new DateTime( '@' . $data['end_date'] );
			$modify_string = '';
			$counter = 1;
			foreach( $em_recurrence_selected_custom_dates as $cdates ){
				if( ! empty( $cdates ) ) {
					if( strtotime( $cdates ) < $data['start_date'] ) continue;
					$newdates = date_create( $cdates );
					$child_start_date1 = date_create( date( "Y-m-d", $data['start_date'] ) );
					$start_date_diff = date_diff( $child_start_date1, $newdates );
					$modify_string = $start_date_diff->days > 1 ? '+'.$start_date_diff->days.' days' : '+'.$start_date_diff->days.' day';
					$start_date->modify( $modify_string );
					$end_date->modify( $modify_string );
					// get date timestamp
					$child_start_date = $start_date->getTimestamp();
					$child_end_date   = $end_date->getTimestamp();
					// create child event
					$new_post_id = $this->ep_create_child_event( $post, $child_start_date, $child_end_date, $counter, $post_data );
					if( !empty( $new_post_id ) && is_int( $new_post_id ) ) {
						// set parent id to recurring post
						update_post_meta( $new_post_id, 'em_is_custom_dates_recurrence', 1 );
						$counter++;
					}
					// reset the variable so we can start from the actual start & end dates
					$start_date = new DateTime( '@' . $data['start_date'] );
					$end_date 	= new DateTime( '@' . $data['end_date'] );
				}
			}
		}
	}

	public function get_date_modification_string( $data ) {
        $step 		   = absint( $data['recurrence_step'] );
        $interval 	   = $data['recurrence_interval'];
        $modify_string = '+' . $step;
        switch ( $interval ) {
            case 'daily':
                $modify_string .= ($step > 1) ? ' days' : ' day';
                break;
            case 'weekly':
                $modify_string .= ($step > 1) ? ' weeks' : ' week';
                break;
            case 'monthly':
				$step_string = ($step > 1) ? ' months' : ' month';
				if( $data['em_recurrence_monthly_day'] == 'date' ) {
					$modify_string = 'this day +' . $step . $step_string;
				} else{
					$week_num = ep_get_week_number();
					$week_full_day = ep_get_week_day_full();
					$modify_string = $week_num[$data['em_recurrence_monthly_weekno']]. ' '. $week_full_day[$data['em_recurrence_monthly_fullweekday']] . ' of +'. $step. ' '. $step_string;
				}
                break;
            case 'yearly':
                $step_string = ($step > 1) ? ' years' : ' year';
				if( $data['em_recurrence_yearly_day'] == 'date' ) {
					$modify_string = 'this day +' . $step . $step_string;
				} else{
					$week_num = ep_get_week_number();
					$week_full_day = ep_get_week_day_full();
					$month_name = ep_get_month_name();
					$modify_string = $week_num[$data['em_recurrence_yearly_weekno']]. ' '. $week_full_day[$data['em_recurrence_yearly_fullweekday']] . ' of '. $month_name[$data['em_recurrence_yearly_monthday']] . ' ' . $data['em_recurrence_yearly_year'];
				}
                break;
        }
        return $modify_string;
    }

	public function get_new_modify_string( $start_date, $modify_string ) {
        $child_start_date1 = ep_timestamp_to_date( $start_date->getTimestamp(), 'Y-m-d', 1 );
        $tmp_date = new DateTime( $child_start_date1 );
        $tmp1_date = $tmp_date;
        $tmp2_date = $tmp1_date;
        $tmp1_date->modify( $modify_string );
        $start_date_diff = date_diff( new DateTime( $child_start_date1 ), $tmp1_date );
        $new_modify_string = $start_date_diff->days > 1 ? '+'.$start_date_diff->days.' days' : '+'.$start_date_diff->days.' day';
        return $new_modify_string;
    }

	/**
	 * Create child events
	 * 
	 * @param object $post Parent Event Data.
	 * 
	 * @param int $start_date Start Date Timestamp.
	 * 
	 * @param int $end_date End Date Timestamp.
	 */
	public function ep_create_child_event( $post, $start_date, $end_date, $counter = 0, $post_data = array() ) {
		if( ! empty( $post ) && ! empty( $start_date ) && ! empty( $end_date ) ) {
			global $wpdb;
			$child_name = ( isset( $post_data['em_add_slug_in_event_title'] ) && absint( $post_data['em_add_slug_in_event_title'] ) == 1 ) ? $this->ep_format_event_title( $post->ID, $post->post_title, $counter, $start_date, $post_data ) : $post->post_title;
			// add new child post
			$new_post = array(
				'post_title'   => $child_name,
				'post_status'  => $post->post_status,
				'post_content' => $post->post_content,
				'post_type'    => $post->post_type,
				'post_author'  => get_current_user_id(),
				'post_parent'  => $post->ID
			); 
			$new_post_id = wp_insert_post( $new_post ); // new post id
			$old_post_metas = get_post_custom( $post->ID );
			// add all metas
			if( ! empty( $old_post_metas ) ) {
				foreach( $old_post_metas as $meta_key => $meta_value ) {
					if( $meta_key == 'em_start_date' ) {
						update_post_meta( $new_post_id, $meta_key, $start_date );	
					} elseif( $meta_key == 'em_end_date' ) {
						update_post_meta( $new_post_id, $meta_key, $end_date );	
					} elseif( $meta_key == 'em_id' ) {
						update_post_meta( $new_post_id, $meta_key, $new_post_id );	
					} elseif( $meta_key == 'em_name' ) {
						update_post_meta( $new_post_id, $meta_key, $child_name );	
					} elseif( $meta_key == 'em_venue' || $meta_key == 'em_event_type' ) {
						wp_set_post_terms( $new_post_id, isset( $meta_value[0] ) ? $meta_value[0] : '', $meta_key, false );
						update_post_meta( $new_post_id, $meta_key, isset( $meta_value[0] ) ? $meta_value[0] : '' );
					} elseif( $meta_key == 'em_performer' || $meta_key == 'em_sponsor' ) {
						wp_set_post_terms( $new_post_id, isset( $meta_value[0] ) ? maybe_unserialize( $meta_value[0] ) : maybe_unserialize(array()), $meta_key, false );
						update_post_meta( $new_post_id, $meta_key, isset( $meta_value[0] ) ? maybe_unserialize( $meta_value[0] ) : maybe_unserialize( array() ) );
					} elseif( $meta_key == 'em_organizer' ){
						$orgs = isset( $meta_value[0] ) ? maybe_unserialize( $meta_value[0] ) : array();
						wp_set_post_terms( $new_post_id, $orgs, 'em_event_organizer', false );
						update_post_meta( $new_post_id, $meta_key, $orgs );
					} else{
						update_post_meta( $new_post_id, $meta_key, maybe_unserialize( $meta_value[0] ) );
					}
				}
				// update start and end datetime meta
				$ep_date_time_format = 'Y-m-d';
				$start_date = get_post_meta( $new_post_id, 'em_start_date', true );
				$start_time = get_post_meta( $new_post_id, 'em_start_time', true );
				$merge_start_date_time = ep_datetime_to_timestamp( ep_timestamp_to_date( $start_date, 'Y-m-d', 1 ) . ' ' . $start_time, $ep_date_time_format, '', 0, 1 );
				if( ! empty( $merge_start_date_time ) ) {
					update_post_meta( $new_post_id, 'em_start_date_time', $merge_start_date_time );
				}
				$end_date = get_post_meta( $new_post_id, 'em_end_date', true );
				$end_time = get_post_meta( $new_post_id, 'em_end_time', true );
				$merge_end_date_time = ep_datetime_to_timestamp( ep_timestamp_to_date( $end_date, 'Y-m-d', 1 ) . ' ' . $end_time, $ep_date_time_format, '', 0, 1 );
				if( ! empty( $merge_end_date_time ) ) {
					update_post_meta( $new_post_id, 'em_end_date_time', $merge_end_date_time );
				}
			}
			// check for categories
			$cat_table_name = $wpdb->prefix.'eventprime_ticket_categories';
			$price_options_table = $wpdb->prefix.'em_price_options';
			$categories = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $cat_table_name WHERE `event_id` = %d ORDER BY `id` ASC", $post->ID ) );
			if( ! empty( $categories ) ) {
				foreach( $categories as $cat ) {
					$cat_data = array();
					$cat = (array)$cat;
					$cat_data['event_id'] = $new_post_id;
					$cat_data['parent_id'] = $cat['id'];
					$cat_data['name'] = $cat['name'];
					$cat_data['capacity'] = $cat['capacity'];
					$cat_data['priority'] = $cat['priority'];
					$cat_data['status'] = $cat['status'];
					$cat_data['created_by'] = $cat['created_by'];
					$cat_data['last_updated_by'] = $cat['last_updated_by'];
					$cat_data['created_at'] = date_i18n( "Y-m-d H:i:s", time() );
					$cat_data['updated_at'] = date_i18n( "Y-m-d H:i:s", time() );
					$result = $wpdb->insert( $cat_table_name, $cat_data );
					$cat_id = $wpdb->insert_id;
					$cat_tickets = self::get_existing_category_ticket_lists( $post->ID, $cat['id'], false );
					if( !empty( $cat_tickets ) ) {
						foreach( $cat_tickets as $ticket ) {
							$ticket = (array)$ticket;
							$parent_price_option_id = $ticket['id'];
							unset( $ticket['id'] );
							$ticket['event_id'] = $new_post_id;
							$ticket['parent_price_option_id'] = $parent_price_option_id;
							$ticket['category_id'] = $cat_id;
							$ticket['created_at'] = date_i18n( "Y-m-d H:i:s", time() );
							$ticket['updated_at'] = date_i18n( "Y-m-d H:i:s", time() );
							$result = $wpdb->insert( $price_options_table, $ticket );
						}
					}
				}
			}
			// check for individual ticket
			$individual_tickets = self::get_existing_individual_ticket_lists( $post->ID, false );
			if( !empty( $individual_tickets ) ) {
				foreach( $individual_tickets as $ticket ) {
					$ticket = (array)$ticket;
					$parent_price_option_id = $ticket['id'];
					unset( $ticket['id'] );
					$ticket['event_id'] = $new_post_id;
					$ticket['parent_price_option_id'] = $parent_price_option_id;
					$ticket['created_at'] = date_i18n( "Y-m-d H:i:s", time() );
					$ticket['updated_at'] = date_i18n( "Y-m-d H:i:s", time() );
					$result = $wpdb->insert( $price_options_table, $ticket );
				}
			}
			do_action('ep_after_save_event_child_data', $new_post_id, $post_data);
			return $new_post_id;
		}
		return;
	}

	/**
	 * Format recurring event title
	 * 
	 * @param int $id Post Id.
	 * 
	 * @param string $post_title Post Title.
	 * 
	 * @param int $counter Counter
	 * 
	 * @param string $date Event Start Date.
	 * 
	 * @return string $post_title Post Title
	 */
	public function ep_format_event_title( $id, $post_title, $counter, $date, $post_data = array() ) {
		update_post_meta( $id, 'em_add_slug_in_event_title', 1 );
		$em_event_slug_type_options = (isset( $post_data['em_event_slug_type_options'] ) && ! empty( $post_data['em_event_slug_type_options'] ) ) ? $post_data['em_event_slug_type_options'] : '';
		update_post_meta( $id, 'em_event_slug_type_options', $em_event_slug_type_options );
		if( ! empty( $em_event_slug_type_options ) ) {
			$em_recurring_events_slug_format = ( isset( $post_data['em_recurring_events_slug_format'] ) && ! empty( $post_data['em_recurring_events_slug_format'] ) ) ? $post_data['em_recurring_events_slug_format'] : '';
			update_post_meta( $id, 'em_recurring_events_slug_format', $em_recurring_events_slug_format );
			if( ! empty( $em_recurring_events_slug_format ) ) {
				if( $em_recurring_events_slug_format == 'date' ) {
					$date = ep_timestamp_to_date( $date );
					if( $em_event_slug_type_options == 'prefix' ) {
						$post_title = $date . '-' . $post_title;
					} else{
						$post_title = $post_title . '-' . $date;
					}
				} else{
					$occurance_number = $counter + 1;
					if( $em_event_slug_type_options == 'prefix' ) {
						$post_title = $occurance_number . '-' . $post_title;
					} else{
						$post_title = $post_title . '-' . $occurance_number;
					}
				}
			}
		}
		return $post_title;
	}

	public function nthDayInMonth( $n, $day, $m = '', $y = '' ) {
        // day is in range 0 Sunday to 6 Saturday
        $y = ( ! empty( $y ) ? $y : date('Y') );
        $m = ( ! empty( $m ) ? $m : date('m') );
        $d = $this->firstDayInMonth( $day, $m, $y );
        $weeks = $this->getWeeksInMonth( $y, $m, 7 ); //1 (for monday) to 7 (for sunday)
        $week_status = array();    
        foreach( $weeks as $weekNumber => $week ){
            $week_status[$weekNumber] = $week[0].'/'.$week[1];
        }
        $week_start_end = explode( "/", $week_status[$n] );
        $start_date = $week_start_end[0];
        $end_date = $week_start_end[1];
        $week_w_count = array();
        $week_date_range = array();
        $w_loop_start = 1;
        while ( strtotime( $start_date ) <= strtotime( $end_date ) ) {
            $timestamp = strtotime( $start_date );
            $day_w_count = date( 'w', $timestamp );
            $week_w_count[$w_loop_start] = $day_w_count;
            $week_date_range[$w_loop_start] = $start_date;
            $start_date = date( "Y-m-d", strtotime("+1 days", strtotime( $start_date ) ) );
            $w_loop_start++;
        }
        if( in_array( $day, $week_w_count ) ) {
            $key_value = array_search( $day,$week_w_count );
            $newDate = $week_date_range[$key_value];
            unset( $week_status );
            unset( $week_start_end );
            unset( $week_w_count );
            unset( $week_date_range );
            return $newDate; 
        }
		unset( $week_status );
		unset( $week_start_end );
		unset( $week_w_count );
		unset( $week_date_range );
		return '';
    }
    
    public function firstDayInMonth( $day, $m = '', $y = '' ) {
        // day is in range 0 Sunday to 6 Saturday
        $y = ( ! empty( $y ) ? $y : date('Y') );
        $m = ( ! empty( $m ) ? $m : date('m') );
        $fdate = date( $y.'-'.$m.'-01' );
        $fd = date( 'w', strtotime( $fdate ) );
        $od = 1 + ( $day - $fd + 7 ) % 7;
        $newDate = date( $y.'-'.$m.'-'.$od );
        return $newDate;
    }

	public function getWeeksInMonth( $year, $month, $lastDayOfWeek ) {
        $aWeeksOfMonth = [];
        $date = new DateTime( "{$year}-{$month}-01" );
        $iDaysInMonth = cal_days_in_month( CAL_GREGORIAN, $month, $year );
        $aOneWeek = [$date->format('Y-m-d')];
        $weekNumber = 1;
        for ( $i = 1; $i <= $iDaysInMonth; $i++ ) {
            if ( $lastDayOfWeek == $date->format('N') || $i == $iDaysInMonth ) {
                $aOneWeek[] = $date->format('Y-m-d');
                $aWeeksOfMonth[$weekNumber++] = $aOneWeek;
                $date->add( new DateInterval('P1D') );
                $aOneWeek = [$date->format('Y-m-d')];
                $i++;
            }
            $date->add( new DateInterval('P1D') );
        }
        return $aWeeksOfMonth;
	}

	/**
	 * Publish event
	 */
	public function ep_event_published( $new, $old, $post ) {
		if ( is_admin() ) {
			if( $post->post_type == 'em_event' ) {
				if( ( 'draft' == $old && 'draft' == $new ) || ( 'auto-draft' == $old && 'draft' == $new ) || ( 'new' == $old && 'draft' == $new ) ) {
					$postData = [ 'ID' => $post->ID, 'post_status' => 'publish' ];
					wp_update_post( $postData );
				}
			}
		}
	}

	/**
	 * Add columns to event list table
	 */
	public function ep_filter_event_columns( $columns ) {
		unset( $columns['comments'] );
        unset( $columns['date'] );
        unset( $columns['tags'] );
        $columns['title'] 	     = esc_html__( 'Title', 'eventprime-event-calendar-management' );
		$singular_type_text      = ep_global_settings_button_title( 'Event-Type' );
        $columns['event_type']   = $singular_type_text;
		$singular_venue_text     = ep_global_settings_button_title( 'Venue' );
        $columns['venue'] 	     = $singular_venue_text;
		$singular_organizer_text = ep_global_settings_button_title( 'Organizer' );
        $columns['organizer']    = $singular_organizer_text;
		$singular_performer_text = ep_global_settings_button_title( 'Performer' );
        $columns['performer']    = $singular_performer_text;
        $columns['start_date']   = esc_html__( 'Start Date', 'eventprime-event-calendar-management' );
        $columns['end_date']     = esc_html__( 'End Date', 'eventprime-event-calendar-management' );
        $columns['repeat'] 	     = esc_html__( 'Repeat', 'eventprime-event-calendar-management' );
        return $columns;
	}

	/**
	 * Add column content
	 */
	public function ep_filter_event_columns_content( $column_name, $post_id ) {
		if( $column_name == 'venue' ) {
            $venue = get_term( get_post_meta( $post_id, 'em_venue', true ) );
            echo ( isset( $venue->name ) && 'uncategorized' !== $venue->slug ? esc_html( $venue->name ) : '----' );
        } elseif( $column_name == 'event_type' ) {
            $event_type = get_term( get_post_meta( $post_id, 'em_event_type', true ) );
            echo ( isset( $event_type->name ) && 'uncategorized' !== $event_type->slug ? esc_html( $event_type->name ) : '----' );
        } elseif( $column_name == 'organizer' ) {
			$organizers = get_post_meta( $post_id, 'em_organizer', true );
			$organizer_name = [];
			if( ! empty( $organizers ) ) {
				foreach( $organizers as $organizer ) {
					$org = get_term( $organizer );
					if( ! empty( $org ) && ! empty( $org->name ) ) {
						$organizer_name[] = $org->name;
					}
				}
			}
			echo ( ! empty( $organizer_name ) ? implode( ', ', $organizer_name ) : '----' );
        } elseif( $column_name == 'performer' ) {
			$performers = get_post_meta( $post_id, 'em_performer', true );
			$performer_name = [];
			if( ! empty( $performers ) ) {
				foreach( $performers as $performer ) {
					$per = get_the_title( $performer );
					if( ! empty( $per ) ) {
						$performer_name[] = $per;
					}
				}
			}
			echo ( ! empty( $performer_name ) ? implode( ', ', $performer_name ) : '----' );
        } elseif( $column_name == 'start_date' ) {
            $start_date = get_post_meta( $post_id, 'em_start_date', true );
			if( ! empty( $start_date ) ) {
				$start_date = ep_timestamp_to_date( $start_date );
			}
			$em_start_time = get_post_meta( $post_id, 'em_start_time', true );
			if( ! empty( $em_start_time ) ) {
				$start_date .= ' ' . $em_start_time;
			}
            echo ( ! empty( $start_date ) ? $start_date : '----' );
        } elseif( $column_name == 'end_date' ) {
            $end_date = get_post_meta( $post_id, 'em_end_date', true );
			if( ! empty( $end_date ) ) {
				$end_date = ep_timestamp_to_date( $end_date );
			}
			$em_end_time = get_post_meta( $post_id, 'em_end_time', true );
			if( ! empty( $em_end_time ) ) {
				$end_date .= ' ' . $em_end_time;
			}
            echo ( ! empty( $end_date ) ? $end_date : '----' );
        } elseif( $column_name == 'repeat' ) {
			$post_parent = wp_get_post_parent_id( $post_id );
			if( empty( $post_parent ) ) {
				$em_recurrence_interval = get_post_meta( $post_id, 'em_recurrence_interval', true );
				if( ! empty( $em_recurrence_interval ) && strpos( $em_recurrence_interval, '_' ) === true ) {
					$em_recurrence_interval = implode(' ', explode('_', $em_recurrence_interval) );
				}
				echo ( ! empty( $em_recurrence_interval ) ? ucwords( $em_recurrence_interval ) : '----' );
			} else{
				$parent_post_url = admin_url( 'post.php?post='.$post_parent.'&action=edit' );
				echo '<a href="'.esc_url( $parent_post_url ).'" title="'.esc_attr__( 'Show Parent Event').'" target1="_blank"><span class="dashicons dashicons-networking"></span></a>';
			}
        }
	}

	/**
	 * Get category tickets total capacity.
	 * 
	 * @param array $cat_ticket_data Category data.
	 * 
	 * @return int Total Capacity
	 */
	private static function get_category_tickets_capacity( $cat_ticket_data ){
		$capacity = 0;
		if( !empty( $cat_ticket_data ) ) {
			foreach( $cat_ticket_data as $ticket ) {
				$capacity += $ticket->capacity;
			}
		}
		return $capacity;
	}

	/**
	 * Get ticket booking event date options.
	 * 
	 * @param int $event_id Event Id.
	 * 
	 * @return array Event Date Options.
	 */
	private static function get_ticket_booking_event_date_options( $event_id ) {
		$event_date_options = array();
		if( $event_id ) {
			$event_date_options['event_start'] = esc_html__( 'Event Start', 'eventprime-event-calendar-management');
			$event_date_options['event_ends']  = esc_html__( 'Event Ends', 'eventprime-event-calendar-management');
			$more_dates = get_post_meta( $event_id, 'em_event_add_more_dates', true );
			if( ! empty( $more_dates ) && count( $more_dates ) > 0 ) {
				foreach( $more_dates as $more ) {
					if( empty( $more['label'] ) ) continue;

					$option_val = $more['uid'];
					$event_date_options[$option_val]  = $more['label'];
				}
			}
		}
		return $event_date_options;
	}
        
	public function ep_sort_events_date( $query ) {
		if( ! is_admin() ){
			return;
		}
		$orderby = $query->get( 'orderby');
		switch ( $orderby ) {
			case 'start_date':
				$query->set( 'meta_key', 'em_start_date' );
				$query->set( 'orderby', 'meta_value_num' );
				break;
			default:
				break;
		}
	}
        
	public function ep_sortable_event_columns($columns){
		$columns['start_date'] = array('start_date','asc'); 
		return $columns;
	}
	
	public function em_event_default_order( $query ){
		if( $query->get('post_type')=='em_event' ){
			if( $query->get('orderby') == '' ){
				$query->set('orderby','start_date');
			}
			if( $query->get('order') == '' ){
				$query->set('order','desc');
			}
		}
	}

	/**
	 * Return checkout fields tabs data
	 *
	 * @return array
	 */
	private static function get_ep_event_checkout_field_tabs() {
		$tabs = apply_filters(
			'ep_event_checkout_field_tabs',
			array(
				'attendee_fields' => array(
					'label'      => esc_html__( 'Attendee Fields', 'eventprime-event-calendar-management' ),
					'target'     => 'ep_event_attendee_fields_data',
					'class'      => array( 'ep_event_attendee_fields_wrap' ),
					'priority'   => 10,
				),
				'booking_fields' => array(
					'label'      => esc_html__( 'Booking Fields', 'eventprime-event-calendar-management' ),
					'target'     => 'ep_event_booking_fields_data',
					'class'      => array( 'ep_event_booking_fields_wrap' ),
					'priority'   => 20,
				),
			)
		);
		// Sort tabs based on priority.
		uasort( $tabs, array( __CLASS__, 'event_data_tabs_sort' ) );
		return $tabs;
	}

	/**
	 * return essentials checkout fields in table structure
	 * 
	 * @param array $event_checkout_attendee_fields Saved Attendee Fields.
	 * 
	 * @return Field Html.
	 */
	private static function ep_get_checkout_essentials_fields_rows( $event_checkout_attendee_fields = array(), $is_popup = '' ) {
		$field = '<tr class="ep-event-checkout-esse-name-field" title="'.esc_html__( 'Add attendee name field', 'eventprime-event-calendar-management' ).'">';
			$field .= '<td>';
				$field .= '<label for="em_event_checkout_name">'.esc_html__( 'Name', 'eventprime-event-calendar-management' ).'</label>';
			$field .= '</td>';
			$field .= '<td>'.esc_html__( 'For adding attendee names', 'eventprime-event-calendar-management' ).'</td>';
			$field .= '<td>';
				$em_event_checkout_name_checked = ( isset( $event_checkout_attendee_fields['em_event_checkout_name'] ) && $event_checkout_attendee_fields['em_event_checkout_name'] == 1 ) ? 'checked="checked"' : '';
				$field .= '<input type="checkbox" name="em_event_checkout_name" class="ep-form-check-input" id="em_event_checkout_name'.$is_popup.'" value="1" data-label="'.esc_html__( 'Name', 'eventprime-event-calendar-management' ).'" '.$em_event_checkout_name_checked.'>';
			$field .= '</td>';
			$field .= '<td>&nbsp;</td>';
		$field .= '</tr>';
		$field .= self::ep_get_name_sub_fields_rows( $event_checkout_attendee_fields, $is_popup );
		return $field;
	}

	/**
	 * get name sub field in table structure
	 * 
	 * @param array $event_checkout_attendee_fields Saved Attendee Fields.
	 * 
	 * @return Field Html.
	 */
	private static function ep_get_name_sub_fields_rows( $event_checkout_attendee_fields, $is_popup = '' ) {
		$field = $display = '';
		if( isset( $event_checkout_attendee_fields['em_event_checkout_name'] ) && $event_checkout_attendee_fields['em_event_checkout_name'] == 1 ) {
			$display = 'style="display:table-row;"';
		}
		// first name
		$em_event_checkout_name_first_name_checked = ( isset( $event_checkout_attendee_fields['em_event_checkout_name_first_name'] ) && $event_checkout_attendee_fields['em_event_checkout_name_first_name'] == 1 ) ? 'checked="checked"' : '';
		$first_name_display = ( empty( $em_event_checkout_name_first_name_checked ) ? 'style="display:none;"' : '' );
		$em_event_checkout_name_first_name_required_checked = ( isset( $event_checkout_attendee_fields['em_event_checkout_name_first_name_required'] ) && $event_checkout_attendee_fields['em_event_checkout_name_first_name_required'] == 1 ) ? 'checked="checked"' : '';
		$field .= '<tr class="ep-sub-field-first-name ep-event-checkout-field-name-sub-row" title="'.esc_html__( 'Add attendee first name field', 'eventprime-event-calendar-management' ).'" '.$display.'>';
			$field .= '<td>';
				$field .= '<label for="em_event_checkout_name_first_name'.$is_popup.'" class="ep-form-label">'.esc_html__( 'First Name', 'eventprime-event-calendar-management' ).'</label></div>';
			$field .= '</td>';
			$field .= '<td>'.esc_html__( 'For adding attendee first name', 'eventprime-event-calendar-management' ).'</td>';
			$field .= '<td>';
				$field .= '<div class="ep-form-check-wrap ep-di-flex ep-items-center"><input type="checkbox" class="ep-form-check-input ep-name-sub-fields" data-field_type="first-name" name="em_event_checkout_name_first_name'.$is_popup.'" id="em_event_checkout_name_first_name'.$is_popup.'" value="1" data-label="'.esc_html__( 'First Name', 'eventprime-event-calendar-management' ).'" '.$em_event_checkout_name_first_name_checked.'>';
			$field .= '</td>';
			$field .= '<td>';
				$field .= '<label for="em_event_checkout_name_first_name_required'.$is_popup.'" class="ep-form-label ep-ml-3 ep-first-name-required" title="'.esc_html__( 'Require attendee first name field', 'eventprime-event-calendar-management' ).'">';
					$field .= '<input type="checkbox" name="em_event_checkout_name_first_name_required'.$is_popup.'" id="em_event_checkout_name_first_name_required'.$is_popup.'" value="1" data-label="'.esc_html__( 'Required', 'eventprime-event-calendar-management' ).'" '.$em_event_checkout_name_first_name_required_checked.'>';
				$field .= '</label>';
			$field .= '</td>';
		$field .= '</tr>';
		// middle name
		$em_event_checkout_name_middle_name_checked = ( isset( $event_checkout_attendee_fields['em_event_checkout_name_middle_name'] ) && $event_checkout_attendee_fields['em_event_checkout_name_middle_name'] == 1 ) ? 'checked="checked"' : '';
		$middle_name_display = ( empty( $em_event_checkout_name_middle_name_checked ) ? 'style="display:none;"' : '' );
		$em_event_checkout_name_middle_name_required_checked = ( isset( $event_checkout_attendee_fields['em_event_checkout_name_middle_name_required'] ) && $event_checkout_attendee_fields['em_event_checkout_name_middle_name_required'] == 1 ) ? 'checked="checked"' : '';
		$field .= '<tr class="ep-sub-field-middle-name ep-event-checkout-field-name-sub-row" title="'.esc_html__( 'Add attendee middle name field', 'eventprime-event-calendar-management' ).'" '.$display.'>';
			$field .= '<td>';
				$field .= '<label for="em_event_checkout_name_middle_name'.$is_popup.'" class="ep-form-label">'.esc_html__( 'Middle Name', 'eventprime-event-calendar-management' ).'</label></div>';
			$field .= '</td>';
			$field .= '<td>'.esc_html__( 'For adding attendee middle name', 'eventprime-event-calendar-management' ).'</td>';
			$field .= '<td>';
				$field .= '<div class="ep-form-check-wrap ep-di-flex ep-items-center"><input type="checkbox" class="ep-form-check-input ep-name-sub-fields" data-field_type="middle-name" name="em_event_checkout_name_middle_name'.$is_popup.'" id="em_event_checkout_name_middle_name'.$is_popup.'" value="1" data-label="'.esc_html__( 'Middle Name', 'eventprime-event-calendar-management' ).'" '.$em_event_checkout_name_middle_name_checked.'>';
			$field .= '</td>';
			$field .= '<td>';
				$field .= '<label for="em_event_checkout_name_middle_name_required'.$is_popup.'" class="ep-form-label ep-ml-3 ep-middle-name-required" title="'.esc_html__( 'Require attendee middle name field', 'eventprime-event-calendar-management' ).'">';
					$field .= '<input type="checkbox" name="em_event_checkout_name_middle_name_required'.$is_popup.'" id="em_event_checkout_name_middle_name_required'.$is_popup.'" value="1" data-label="'.esc_html__( 'Required', 'eventprime-event-calendar-management' ).'" '.$em_event_checkout_name_middle_name_required_checked.'>';
				$field .= '</label>';
			$field .= '</td>';
		$field .= '</tr>';
		// last name
		$em_event_checkout_name_last_name_checked = ( isset( $event_checkout_attendee_fields['em_event_checkout_name_last_name'] ) && $event_checkout_attendee_fields['em_event_checkout_name_last_name'] == 1 ) ? 'checked="checked"' : '';
		$last_name_display = ( empty( $em_event_checkout_name_last_name_checked ) ? 'style="display:none;"' : '' );
		$em_event_checkout_name_last_name_required_checked = ( isset( $event_checkout_attendee_fields['em_event_checkout_name_last_name_required'] ) && $event_checkout_attendee_fields['em_event_checkout_name_last_name_required'] == 1 ) ? 'checked="checked"' : '';
		$field .= '<tr class="ep-sub-field-last-name ep-event-checkout-field-name-sub-row" title="'.esc_html__( 'Add attendee last name field', 'eventprime-event-calendar-management' ).'" '.$display.'>';
			$field .= '<td>';
				$field .= '<label for="em_event_checkout_name_last_name'.$is_popup.'" class="ep-form-label">'.esc_html__( 'Last Name', 'eventprime-event-calendar-management' ).'</label></div>';
			$field .= '</td>';
			$field .= '<td>'.esc_html__( 'For adding attendee last name', 'eventprime-event-calendar-management' ).'</td>';
			$field .= '<td>';
				$field .= '<div class="ep-form-check-wrap ep-di-flex ep-items-center"><input type="checkbox" class="ep-form-check-input ep-name-sub-fields" data-field_type="last-name" name="em_event_checkout_name_last_name'.$is_popup.'" id="em_event_checkout_name_last_name'.$is_popup.'" value="1" data-label="'.esc_html__( 'Last Name', 'eventprime-event-calendar-management' ).'" '.$em_event_checkout_name_last_name_checked.'>';
			$field .= '</td>';
			$field .= '<td>';
				$field .= '<label for="em_event_checkout_name_last_name_required'.$is_popup.'" class="ep-form-label ep-ml-3 ep-last-name-required" title="'.esc_html__( 'Require attendee last name field', 'eventprime-event-calendar-management' ).'">';
					$field .= '<input type="checkbox" name="em_event_checkout_name_last_name_required'.$is_popup.'" id="em_event_checkout_name_last_name_required'.$is_popup.'" value="1" data-label="'.esc_html__( 'Required', 'eventprime-event-calendar-management' ).'" '.$em_event_checkout_name_last_name_required_checked.'>';
				$field .= '</label>';
			$field .= '</td>';
		$field .= '</tr>';
		return $field;
	}

	/**
	 * Update child events after update in parent
	 * 
	 * @param int $post_id parent Event ID
	 */
	public function ep_update_child_events( $post_id ) {
		global $wpdb;
		// get child events
		$child_events = EventM_Factory_Service::ep_get_child_events( $post_id );
		if( ! empty( $child_events ) ) {
			$parent_post_data = get_post( $post_id );
			if( ! empty( $parent_post_data ) ) {
				$parent_post_metas = get_post_custom( $post_id );
				$counter = 0;
				$em_add_slug_in_event_title = get_post_meta( $post_id, 'em_add_slug_in_event_title', true );
				$parent_post_title = $parent_post_data->post_title;
				$em_recurring_events_slug_format = get_post_meta( $post_id, 'em_recurring_events_slug_format', true );
				$em_event_slug_type_options = get_post_meta( $post_id, 'em_event_slug_type_options', true );
				// check for categories
				$cat_table_name = $wpdb->prefix.'eventprime_ticket_categories';
				$price_options_table = $wpdb->prefix.'em_price_options';
				//$categories = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $cat_table_name WHERE `event_id` = %d ORDER BY `id` ASC", $post_id ) );
				$events = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
				$parent_categories = $events->get_event_ticket_category( $post_id );
				$individual_tickets = self::get_existing_individual_ticket_lists( $parent_post_data->ID, false );
				foreach ( $child_events as $child_post ) {
					// update all metas
					$child_start_date = get_post_meta( $child_post->ID, 'em_start_date', true );
					$child_event_name = $parent_post_title;
					// generate the child name
					if( ! empty( $em_add_slug_in_event_title ) ) {
						if( ! empty( $em_recurring_events_slug_format ) ) {
							if( $em_recurring_events_slug_format == 'date' ) {
								$date = ep_timestamp_to_date( $child_start_date );
								if( $em_event_slug_type_options == 'prefix' ) {
									$child_event_name = $date . '-' . $parent_post_title;
								} else{
									$child_event_name = $parent_post_title . '-' . $date;
								}
							} else{
								$occurance_number = $counter + 1;
								if( $em_event_slug_type_options == 'prefix' ) {
									$child_event_name = $occurance_number . '-' . $parent_post_title;
								} else{
									$child_event_name = $parent_post_title . '-' . $occurance_number;
								}
							}
						}
					}
					// update child post title and content
					$child_post_update = array(
						'ID'           => $child_post->ID,
						'post_title'   => $child_event_name,
						'post_content' => $parent_post_data->post_content,
					);
				  	// Update the post into the database
					wp_update_post( $child_post_update );
					if( ! empty( $parent_post_metas ) ) {
						foreach( $parent_post_metas as $meta_key => $meta_value ) {
							if( $meta_key == 'em_start_date' || $meta_key == 'em_end_date' || $meta_key == 'em_id' || $meta_key == 'em_ls_seat_plan' || $meta_key == 'em_seat_data' || $meta_key == 'meeting_data' ) {
								continue;
							} elseif( $meta_key == 'em_name' ) {
								update_post_meta( $child_post->ID, $meta_key, $child_event_name );
							} elseif( $meta_key == 'em_venue' || $meta_key == 'em_event_type' ) {
								wp_set_post_terms( $child_post->ID, isset( $meta_value[0] ) ? $meta_value[0] : '', $meta_key, false );
								update_post_meta( $child_post->ID, $meta_key, isset( $meta_value[0] ) ? $meta_value[0] : '' );
							} elseif( $meta_key == 'em_performer' || $meta_key == 'em_sponsor' ) {
								wp_set_post_terms( $child_post->ID, isset( $meta_value[0] ) ? maybe_unserialize( $meta_value[0] ) : maybe_unserialize(array()), $meta_key, false );
								update_post_meta( $child_post->ID, $meta_key, isset( $meta_value[0] ) ? maybe_unserialize( $meta_value[0] ) : maybe_unserialize( array() ) );
							} elseif( $meta_key == 'em_organizer' ) {
								$orgs = isset( $meta_value[0] ) ? maybe_unserialize( $meta_value[0] ) : array();
								wp_set_post_terms( $child_post->ID, $orgs, 'em_event_organizer', false );
								update_post_meta( $child_post->ID, $meta_key, $orgs );
							} else{
								update_post_meta( $child_post->ID, $meta_key, maybe_unserialize( $meta_value[0] ) );
							}
						}
						// update start and end datetime meta
						$ep_date_time_format = 'Y-m-d';
						$start_date = get_post_meta( $child_post->ID, 'em_start_date', true );
						$start_time = get_post_meta( $child_post->ID, 'em_start_time', true );
						$merge_start_date_time = ep_datetime_to_timestamp( ep_timestamp_to_date( $start_date, 'Y-m-d', 1 ) . ' ' . $start_time, $ep_date_time_format, '', 0, 1 );
						if( ! empty( $merge_start_date_time ) ) {
							update_post_meta( $child_post->ID, 'em_start_date_time', $merge_start_date_time );
						}
						$end_date = get_post_meta( $child_post->ID, 'em_end_date', true );
						$end_time = get_post_meta( $child_post->ID, 'em_end_time', true );
						$merge_end_date_time = ep_datetime_to_timestamp( ep_timestamp_to_date( $end_date, 'Y-m-d', 1 ) . ' ' . $end_time, $ep_date_time_format, '', 0, 1 );
						if( ! empty( $merge_end_date_time ) ) {
							update_post_meta( $child_post->ID, 'em_end_date_time', $merge_end_date_time );
						}
					}
					// category and ticket update
					if( ! empty( $parent_categories ) ) {
						foreach( $parent_categories as $parent_category ) {
							$get_cat_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $cat_table_name WHERE `event_id` = %d AND `parent_id` = %d", $child_post->ID, $parent_category->id ) );
							if( ! empty( $get_cat_data ) ) {
								$name = $parent_category->name;
								$capacity = $parent_category->capacity;
								$wpdb->update( $cat_table_name, 
									array( 
										'name' 		  	  => $name,
										'capacity' 		  => $capacity,
										'last_updated_by' => get_current_user_id(),
										'updated_at' 	  => date_i18n("Y-m-d H:i:s", time())
									), 
									array( 'id' => $get_cat_data->id )
								);
								// update tickets
								if( ! empty( $parent_category->tickets ) ) {
									foreach( $parent_category->tickets as $parent_tickets ) {
										$get_ticket_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $price_options_table WHERE `event_id` = %d AND `parent_price_option_id` = %d", $child_post->ID, $parent_tickets->id ) );
										if( ! empty( $get_ticket_data ) ) {
											$wpdb->update( $price_options_table, 
												array( 
													'name' 		  	  			   => $parent_tickets->name,
													'description' 	  			   => $parent_tickets->description,
													'start_date' 	  			   => $parent_tickets->start_date,
													'end_date' 	  				   => $parent_tickets->end_date,
													'price' 	  				   => $parent_tickets->price,
													'special_price' 	  		   => $parent_tickets->special_price,
													'capacity' 	  				   => $parent_tickets->capacity,
													'icon' 	  					   => $parent_tickets->icon,
													'variation_color' 	  		   => $parent_tickets->variation_color,
													'seat_data' 	  			   => $parent_tickets->seat_data,
													'additional_fees' 	  		   => $parent_tickets->additional_fees,
													'allow_cancellation' 	  	   => $parent_tickets->allow_cancellation,
													'show_remaining_tickets' 	   => $parent_tickets->show_remaining_tickets,
													'show_ticket_booking_dates'    => $parent_tickets->show_ticket_booking_dates,
													'min_ticket_no' 	  		   => $parent_tickets->min_ticket_no,
													'max_ticket_no' 	  		   => $parent_tickets->max_ticket_no,
													'visibility' 	  			   => $parent_tickets->visibility,
													'offers' 	  				   => $parent_tickets->offers,
													'booking_starts' 	  		   => $parent_tickets->booking_starts,
													'booking_ends' 	  			   => $parent_tickets->booking_ends,
													'multiple_offers_option' 	   => $parent_tickets->multiple_offers_option,
													'multiple_offers_max_discount' => $parent_tickets->multiple_offers_max_discount,
													'ticket_template_id' 	  	   => $parent_tickets->ticket_template_id,
													'last_updated_by' 			   => get_current_user_id(),
													'updated_at' 	  			   => date_i18n("Y-m-d H:i:s", time())
												), 
												array( 'id' => $get_ticket_data->id )
											);
										} else{
											$ticket_data = array();
											$ticket_data['event_id'] 				   = $child_post->ID;
											$ticket_data['parent_price_option_id'] 	   = $parent_tickets->id;
											$ticket_data['category_id'] 			   = $get_cat_data->id;
											$ticket_data['name'] 		  	  		   = $parent_tickets->name;
											$ticket_data['description'] 	  		   = $parent_tickets->description;
											$ticket_data['start_date'] 	  			   = $parent_tickets->start_date;
											$ticket_data['end_date'] 	  			   = $parent_tickets->end_date;
											$ticket_data['price'] 	  				   = $parent_tickets->price;
											$ticket_data['special_price'] 	  		   = $parent_tickets->special_price;
											$ticket_data['capacity'] 	  			   = $parent_tickets->capacity;
											$ticket_data['icon'] 	  				   = $parent_tickets->icon;
											$ticket_data['variation_color'] 	  	   = $parent_tickets->variation_color;
											$ticket_data['seat_data'] 	  			   = $parent_tickets->seat_data;
											$ticket_data['additional_fees'] 	  	   = $parent_tickets->additional_fees;
											$ticket_data['allow_cancellation'] 	  	   = $parent_tickets->allow_cancellation;
											$ticket_data['show_remaining_tickets'] 	   = $parent_tickets->show_remaining_tickets;
											$ticket_data['show_ticket_booking_dates']  = $parent_tickets->show_ticket_booking_dates;
											$ticket_data['min_ticket_no'] 	  		   = $parent_tickets->min_ticket_no;
											$ticket_data['max_ticket_no'] 	  		   = $parent_tickets->max_ticket_no;
											$ticket_data['visibility'] 	  			   = $parent_tickets->visibility;
											$ticket_data['offers'] 	  				   = $parent_tickets->offers;
											$ticket_data['booking_starts'] 	  		   = $parent_tickets->booking_starts;
											$ticket_data['booking_ends'] 	  		   = $parent_tickets->booking_ends;
											$ticket_data['multiple_offers_option'] 	   = $parent_tickets->multiple_offers_option;
											$ticket_data['multiple_offers_max_discount'] = $parent_tickets->multiple_offers_max_discount;
											$ticket_data['ticket_template_id'] 	  	   = $parent_tickets->ticket_template_id;
											$ticket_data['created_at'] 				   = date_i18n( "Y-m-d H:i:s", time() );
											$ticket_data['updated_at'] 				   = date_i18n( "Y-m-d H:i:s", time() );
											$result = $wpdb->insert( $price_options_table, $ticket_data );
										}
									}
								}
							} else{
								$cat_data = array();
								$cat_data['event_id'] 		 = $child_post->ID;
								$cat_data['parent_id'] 		 = $parent_category->id;
								$cat_data['name'] 			 = $parent_category->name;
								$cat_data['capacity'] 		 = $parent_category->capacity;
								$cat_data['priority'] 		 = $parent_category->priority;
								$cat_data['status'] 		 = $parent_category->status;
								$cat_data['created_by'] 	 = $parent_category->created_by;
								$cat_data['last_updated_by'] = $parent_category->last_updated_by;
								$cat_data['created_at'] 	 = date_i18n( "Y-m-d H:i:s", time() );
								$cat_data['updated_at'] 	 = date_i18n( "Y-m-d H:i:s", time() );
								$result = $wpdb->insert( $cat_table_name, $cat_data );
								$cat_id = $wpdb->insert_id;

								$cat_tickets = self::get_existing_category_ticket_lists( $parent_post_data->ID, $parent_category->id, false );
								if( !empty( $cat_tickets ) ) {
									foreach( $cat_tickets as $ticket ) {
										$ticket = (array)$ticket;
										$parent_price_option_id = $ticket['id'];
										unset( $ticket['id'] );
										$ticket['event_id'] = $child_post->ID;
										$ticket['parent_price_option_id'] = $parent_price_option_id;
										$ticket['category_id'] = $cat_id;
										$ticket['created_at'] = date_i18n( "Y-m-d H:i:s", time() );
										$ticket['updated_at'] = date_i18n( "Y-m-d H:i:s", time() );
										$result = $wpdb->insert( $price_options_table, $ticket );
									}
								}
							}
						}
					}
					// check for individual ticket
					if( ! empty( $individual_tickets ) ) {
						foreach( $individual_tickets as $ticket ) {
							$parent_price_option_id = $ticket->id;
							$get_indi_ticket_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $price_options_table WHERE `event_id` = %d AND `parent_price_option_id` = %d", $child_post->ID, $parent_price_option_id ) );
							if( ! empty( $get_indi_ticket_data ) ) {
								$updated_ticket_data = array( 
									'name' 		  	  			   => $ticket->name,
									'description' 	  			   => $ticket->description,
									'start_date' 	  			   => $ticket->start_date,
									'end_date' 	  				   => $ticket->end_date,
									'price' 	  				   => $ticket->price,
									'special_price' 	  		   => $ticket->special_price,
									'capacity' 	  				   => $ticket->capacity,
									'icon' 	  					   => $ticket->icon,
									'variation_color' 	  		   => $ticket->variation_color,
									'seat_data' 	  			   => $ticket->seat_data,
									'additional_fees' 	  		   => $ticket->additional_fees,
									'allow_cancellation' 	  	   => $ticket->allow_cancellation,
									'show_remaining_tickets' 	   => $ticket->show_remaining_tickets,
									'show_ticket_booking_dates'    => $ticket->show_ticket_booking_dates,
									'min_ticket_no' 	  		   => $ticket->min_ticket_no,
									'max_ticket_no' 	  		   => $ticket->max_ticket_no,
									'visibility' 	  			   => $ticket->visibility,
									'offers' 	  				   => $ticket->offers,
									'booking_starts' 	  		   => $ticket->booking_starts,
									'booking_ends' 	  			   => $ticket->booking_ends,
									'multiple_offers_option' 	   => $ticket->multiple_offers_option,
									'multiple_offers_max_discount' => $ticket->multiple_offers_max_discount,
									'ticket_template_id' 	  	   => $ticket->ticket_template_id,
									'updated_at' 	  			   => date_i18n("Y-m-d H:i:s", time())
								);
								$wpdb->update( $price_options_table, 
									$updated_ticket_data, 
									array( 'id' => $get_indi_ticket_data->id )
								);
							} else{
								$ticket = (array)$ticket;
								unset( $ticket['id'] );
								$ticket['event_id'] = $child_post->ID;
								$ticket['parent_price_option_id'] = $parent_price_option_id;
								$ticket['created_at'] = date_i18n( "Y-m-d H:i:s", time() );
								$ticket['updated_at'] = date_i18n( "Y-m-d H:i:s", time() );
								$result = $wpdb->insert( $price_options_table, $ticket );
							}
						}
					}
					$counter++;
					do_action( 'ep_after_edit_event_child_data', $child_post->ID, $child_post );
				}
			}
		}
	}

}

new EventM_Event_Admin_Meta_Boxes();