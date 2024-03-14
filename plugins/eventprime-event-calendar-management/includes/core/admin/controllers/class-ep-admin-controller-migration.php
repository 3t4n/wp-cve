<?php
/**
 * Class for migration
 */

defined( 'ABSPATH' ) || exit;

class EventM_Admin_Controller_Migration {

    public function ep_run_migration_commands() {
		if( get_option( 'ep_update_revamp_version' ) == 1 ) return;

        self::ep_update_new_global_settings(); //update global settings
        self::ep_migrate_taxonomy_data();
		// add price options table new columns
		self::add_new_price_options_column();
		self::ep_migrate_events_data();
		self::ep_migrate_performers_data();
		self::ep_migrate_bookings_data();
		self::ep_migrate_attendee_booking_fields_data();
		self::ep_revamp_update_guest_booking_options();

        // this should be the last one
        self::ep_revamp_update_global_option();
    }

	/**
	 * Update the global settings
	 */
	private static function ep_update_new_global_settings() {
		$global_options = (object)get_option( EM_GLOBAL_SETTINGS );
		if( ! empty( $global_options ) ) {
			foreach( $global_options as $key => $val ){
				$global_options->$key = $val;
			}
        	update_option( EM_GLOBAL_SETTINGS, $global_options );
		}
	}

    /**
	 * Migrate custom taxonomy data
	 */
	private static function ep_migrate_taxonomy_data() {
		self::ep_migrate_event_types_data();
		self::ep_migrate_venues_data();
		self::ep_migrate_organizers_data();
	}
    
	// event type migrate
	private static function ep_migrate_event_types_data() {
		$terms = get_terms( array( 
			'taxonomy'   => EM_EVENT_TYPE_TAX,
			'hide_empty' => 0
		) );
		if( ! empty( $terms ) ){
			foreach( $terms as $term ){
				$description = get_term_meta( $term->term_id, 'em_description', true );
				if( ! empty( $description ) ) {
					wp_update_term( $term->term_id, EM_EVENT_TYPE_TAX, array(
						'description' => $description
					) );
				}
				$em_color = get_term_meta( $term->term_id, 'em_color', true );
				if( ! empty( $em_color ) ) {
					$em_color = '#'.$em_color;
					update_term_meta( $term->term_id, 'em_color', $em_color );
				}
				$em_type_text_color = get_term_meta( $term->term_id, 'em_type_text_color', true );
				if( ! empty( $em_type_text_color ) ) {
					$em_type_text_color = '#'.$em_type_text_color;
					update_term_meta( $term->term_id, 'em_type_text_color', $em_type_text_color );
				}
			}
		}
	}

	// venue migrate
	private static function ep_migrate_venues_data() {
		global $wpdb;
		$terms = get_terms( array( 
			'taxonomy'   => EM_VENUE_TYPE_TAX,
			'hide_empty' => 0
		) );
		if( ! empty( $terms ) ){
			foreach( $terms as $term ){
				$description = get_term_meta( $term->term_id, 'em_description', true );
				if( ! empty( $description ) ) {
					wp_update_term( $term->term_id, EM_VENUE_TYPE_TAX, array(
						'description' => $description
					) );
				}

				// migrate seats
				$em_type = get_term_meta( $term->term_id, 'em_type', true );
				if( ! empty( $em_type ) && $em_type == 'seats' ) {
					self::ep_check_for_seating_tables();
					$em_seats = get_term_meta( $term->term_id, 'em_seats', true );
					if( ! empty( $em_seats ) ) {
						$term_name = $term->name;
						$plan_name = $term_name . ' - Seat Plan';
						$em_seat_color = get_term_meta( $term->term_id, 'em_seat_color', true );
						$em_booked_seat_color = get_term_meta( $term->term_id, 'em_booked_seat_color', true );
						$em_reserved_seat_color = get_term_meta( $term->term_id, 'em_reserved_seat_color', true );
						$em_selected_seat_color = get_term_meta( $term->term_id, 'em_selected_seat_color', true );
						if( ! empty( $plan_name ) ) {
							$plan_table_name = $wpdb->prefix.'eventprime_live_seat_plan';
        					$seat_table_name = $wpdb->prefix.'eventprime_live_seating';
							//$existing_plan_data = $wpdb->get_row( "SELECT * FROM $plan_table_name WHERE `venue_id` = $term->term_id AND `plan_name` = ". sanitize_text_field( $plan_name ) );
							$existing_plan_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $plan_table_name WHERE `venue_id` = %d AND `plan_name` = %s", $term->term_id, sanitize_text_field( $plan_name ) ) );
							if( empty( $existing_plan_data ) ) {
								// insert seat plan
								$plan_data               = array();
								$plan_data['venue_id']   = absint( $term->term_id );
								$plan_data['plan_name']  = sanitize_text_field( $plan_name );
								$plan_data['seat_available_color'] = ( ! empty( $em_seat_color ) ? sanitize_text_field( '#'.$em_seat_color ) : '' );
								$plan_data['seat_selected_color']  = ( ! empty( $em_selected_seat_color ) ? sanitize_text_field( '#'.$em_selected_seat_color ) : '' );
								$plan_data['seat_booked_color']    = ( ! empty( $em_booked_seat_color ) ? sanitize_text_field( '#'.$em_booked_seat_color ) : '' );
								$plan_data['seat_reserved_color']  = ( ! empty( $em_reserved_seat_color ) ? sanitize_text_field( '#'.$em_reserved_seat_color ) : '' );
								$plan_data['priority'] 	 = 1;
								$plan_data['status'] 	 = 1;
								$plan_data['created_by'] = get_current_user_id();
								$plan_data['created_at'] = date_i18n("Y-m-d H:i:s", time());
								$result = $wpdb->insert( $plan_table_name, $plan_data );
								$plan_id = $wpdb->insert_id;

								// insert seat plan area
								$seat_data = array();
								$seat_data['plan_id']         = absint( $plan_id );
								$seat_data['area_name']       = 'Seating Area';
								$seat_data['area_slug']       = 'seating_area';
								$seat_data['area_properties'] = '';
								$seat_data['seat_data']       = '';
								$seat_data['priority'] 	      = 1;
								$seat_data['status'] 	      = 1;
								$seat_data['created_by']      = get_current_user_id();
								$seat_data['created_at']      = date_i18n("Y-m-d H:i:s", time());
								$result = $wpdb->insert( $seat_table_name, $seat_data );
								$area_id = $wpdb->insert_id;

								// update area id in area seats
								$area_seats = $em_seats;
								foreach( $area_seats as $seats_arr ) {
									foreach( $seats_arr as $seats ) {
										$seats->area_id = $area_id;
									}
								}
								$wpdb->update( $seat_table_name, 
									array( 
										'seat_data' => maybe_serialize( $area_seats ),
									), 
									array( 'id' => $area_id )
								);
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Add new columns for price options table
	 */
	private static function add_new_price_options_column() {
		global $wpdb;
		$price_options_table = $wpdb->prefix.'em_price_options';
		// add variation color column in variation table
        $db_name = $wpdb->dbname;
        $column_name = 'variation_color';
        $ep_set_variation_price = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
		if ( empty( $ep_set_variation_price ) ) {
			$add_color_column = "ALTER TABLE `{$price_options_table}` ADD `variation_color` VARCHAR(20) NULL DEFAULT NULL ";
    		$wpdb->query( $add_color_column );
    	
			$column_name = 'seat_data';
			$add_seat_data_column = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_seat_data_column ) ) {
				$add_seat_data_column = "ALTER TABLE `{$price_options_table}` ADD `seat_data` Longtext NULL DEFAULT NULL ";
				$wpdb->query( $add_seat_data_column );
			}
			$column_name = 'parent_price_option_id';
			$add_parent_price_option_id = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_parent_price_option_id ) ) {
				$add_parent_price_option_id = "ALTER TABLE `{$price_options_table}` ADD `parent_price_option_id` integer(11) DEFAULT 0 NOT NULL ";
				$wpdb->query( $add_parent_price_option_id );
			}

			// add new tickets column in price options table
			$column_name = 'category_id';
			$add_category_id = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_category_id ) ) {
				$add_category_id = "ALTER TABLE `{$price_options_table}` ADD `category_id` integer(11) DEFAULT 0 NOT NULL ";
				$wpdb->query( $add_category_id );
			}
			$column_name = 'additional_fees';
			$add_additional_fees = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_additional_fees ) ) {
				$add_additional_fees = "ALTER TABLE `{$price_options_table}` ADD `additional_fees` longtext DEFAULT NULL ";
				$wpdb->query( $add_additional_fees );
			}
			$column_name = 'allow_cancellation';
			$add_allow_cancellation = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_allow_cancellation ) ) {
				$add_allow_cancellation = "ALTER TABLE `{$price_options_table}` ADD `allow_cancellation` tinyint(2) DEFAULT 0 NOT NULL ";
				$wpdb->query( $add_allow_cancellation );
			}
			$column_name = 'show_remaining_tickets';
			$add_show_remaining_tickets = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_show_remaining_tickets ) ) {
				$add_show_remaining_tickets = "ALTER TABLE `{$price_options_table}` ADD `show_remaining_tickets` tinyint(2) DEFAULT 0 NOT NULL ";
				$wpdb->query( $add_show_remaining_tickets );
			}
			$column_name = 'show_ticket_booking_dates';
			$add_show_ticket_booking_dates = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_show_ticket_booking_dates ) ) {
				$add_show_ticket_booking_dates = "ALTER TABLE `{$price_options_table}` ADD `show_ticket_booking_dates` tinyint(2) DEFAULT 0 NOT NULL ";
				$wpdb->query( $add_show_ticket_booking_dates );
			}
			$column_name = 'min_ticket_no';
			$add_min_ticket_no = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_min_ticket_no ) ) {
				$add_min_ticket_no = "ALTER TABLE `{$price_options_table}` ADD `min_ticket_no` varchar(50) DEFAULT NULL ";
				$wpdb->query( $add_min_ticket_no );
			}
			$column_name = 'max_ticket_no';
			$add_max_ticket_no = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_max_ticket_no ) ) {
				$add_max_ticket_no = "ALTER TABLE `{$price_options_table}` ADD `max_ticket_no` varchar(50) DEFAULT NULL ";
				$wpdb->query( $add_max_ticket_no );
			}
			$column_name = 'visibility';
			$add_visibility = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_visibility ) ) {
				$add_visibility = "ALTER TABLE `{$price_options_table}` ADD `visibility` longtext DEFAULT NULL ";
				$wpdb->query( $add_visibility );
			}
			$column_name = 'offers';
			$add_offers = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_offers ) ) {
				$add_offers = "ALTER TABLE `{$price_options_table}` ADD `offers` longtext DEFAULT NULL ";
				$wpdb->query( $add_offers );
			}
			$column_name = 'booking_starts';
			$add_booking_starts = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_booking_starts ) ) {
				$add_booking_starts = "ALTER TABLE `{$price_options_table}` ADD `booking_starts` longtext DEFAULT NULL ";
				$wpdb->query( $add_booking_starts );
			}
			$column_name = 'booking_ends';
			$add_booking_ends = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_booking_ends ) ) {
				$add_booking_ends = "ALTER TABLE `{$price_options_table}` ADD `booking_ends` longtext DEFAULT NULL ";
				$wpdb->query( $add_booking_ends );
			}
			$column_name = 'multiple_offers_option';
			$add_multiple_offers_option = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_multiple_offers_option ) ) {
				$add_multiple_offers_option = "ALTER TABLE `{$price_options_table}` ADD `multiple_offers_option` longtext DEFAULT NULL ";
				$wpdb->query( $add_multiple_offers_option );
			}
			$column_name = 'multiple_offers_max_discount';
			$add_multiple_offers_max_discount = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_multiple_offers_max_discount ) ) {
				$add_multiple_offers_max_discount = "ALTER TABLE `{$price_options_table}` ADD `multiple_offers_max_discount` longtext DEFAULT NULL ";
				$wpdb->query( $add_multiple_offers_max_discount );
			}
			$column_name = 'ticket_template_id';
			$add_ticket_template_id = $wpdb->get_results($wpdb->prepare("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",$db_name, $price_options_table, $column_name ));
			if ( empty( $add_ticket_template_id ) ) {
				$add_ticket_template_id = "ALTER TABLE `{$price_options_table}` ADD `ticket_template_id` integer(10) DEFAULT NULL ";
				$wpdb->query( $add_ticket_template_id );
			}
		}
	}

	// if user have old seating extension then new seating table will create
	private static function ep_check_for_seating_tables() {
		// create table
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$seat_plan_table = $wpdb->prefix.'eventprime_live_seat_plan';
		$live_seating_table = $wpdb->prefix.'eventprime_live_seating';
		if( $wpdb->get_var( "SHOW TABLES LIKE '$seat_plan_table'") == $seat_plan_table ) {
			return;
	  	}

		if( version_compare( get_bloginfo('version'), '6.1')  < 0 ){
			require_once( ABSPATH . 'wp-includes/wp-db.php' );
		} else{
			require_once( ABSPATH . 'wp-includes/class-wpdb.php' );
		}
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "CREATE TABLE `{$seat_plan_table}` (
			`id` bigint(20) NOT NULL AUTO_INCREMENT,
			`venue_id` integer(11) NOT NULL,
			`plan_name` varchar(255) DEFAULT NULL,
			`seat_available_color` varchar(50) DEFAULT NULL,
			`seat_selected_color` varchar(50) DEFAULT NULL,
			`seat_booked_color` varchar(50) DEFAULT NULL,
			`seat_reserved_color` varchar(50) DEFAULT NULL,
			`priority` integer(11) DEFAULT NULL,
			`status` tinyint(2) DEFAULT 1 NOT NULL,
			`created_by` integer(11) DEFAULT NULL,
			`last_updated_by` integer(11) DEFAULT NULL,
			`created_at` datetime NOT NULL,
			`updated_at` datetime DEFAULT NULL,
			PRIMARY KEY (`id`)
		){$charset_collate}";
	
		dbDelta( $sql );

		$sql = "CREATE TABLE `{$live_seating_table}` (
			`id` bigint(20) NOT NULL AUTO_INCREMENT,
			`plan_id` integer(11) NOT NULL,
			`area_name` varchar(255) DEFAULT NULL,
			`area_slug` varchar(255) DEFAULT NULL,
			`area_properties` longtext DEFAULT NULL,
			`seat_data` longtext DEFAULT NULL,
			`priority` integer(11) DEFAULT NULL,
			`status` tinyint(2) DEFAULT 1 NOT NULL,
			`created_by` integer(11) DEFAULT NULL,
			`last_updated_by` integer(11) DEFAULT NULL,
			`created_at` datetime NOT NULL,
			`updated_at` datetime DEFAULT NULL,
			PRIMARY KEY (`id`)
		){$charset_collate}";
	
		dbDelta( $sql );
	}

	// organizer migrate
	private static function ep_migrate_organizers_data() {
		$terms = get_terms( array( 
			'taxonomy'   => EM_EVENT_ORGANIZER_TAX,
			'hide_empty' => 0
		) );
		if( ! empty( $terms ) ){
			foreach( $terms as $term ){
				$description = get_term_meta( $term->term_id, 'em_description', true );
				if( ! empty( $description ) ) {
					wp_update_term( $term->term_id, EM_EVENT_ORGANIZER_TAX, array(
						'description' => $description
					) );
				}
				$em_social_links = get_term_meta( $term->term_id, 'em_social_links', true );
				if( ! empty( $em_social_links ) && is_object( $em_social_links ) ) {
					$em_social_links = (array)$em_social_links;
					update_term_meta( $term->term_id, 'em_social_links', $em_social_links );
				}
			}
		}
	}

	// events migrate
	private static function ep_migrate_events_data() {
		global $wpdb;
		$price_options_table = $wpdb->prefix.'em_price_options';
		$args = array(
            'numberposts' => -1,
        	'post_type'   => EM_EVENT_POST_TYPE,
        	'post_status' => 'any',
			'orderby' 	  => 'date',
        	'order' 	  => 'ASC',
        );
        $posts = get_posts( $args );
		if( ! empty( $posts ) ) {
			$event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
			foreach( $posts as $post ) {
				$em_id = get_post_meta( $post->ID, 'em_id', true );
				if( empty( $em_id ) ) {
					$single_post = $event_controller->get_single_event( $post->ID );
					update_post_meta( $post->ID, 'em_id', $post->ID );
					update_post_meta( $post->ID, 'em_name', $post->post_title );
					add_post_meta( $post->ID, 'em_old_ep_event', 1 );
					
					// set the event type
					$em_event_type = get_post_meta( $post->ID, 'em_event_type', true );
					if( ! empty( $em_event_type ) ) {
						wp_set_object_terms( $post->ID, intval( $em_event_type ), 'em_event_type' );
					}
					// set the venue
					$em_venue = get_post_meta( $post->ID, 'em_venue', true );
					if( ! empty( $em_venue ) ) {
						wp_set_object_terms( $post->ID, intval( $em_venue ), 'em_venue' );
					}
					// set the organizer
					$em_organizer = get_post_meta( $post->ID, 'em_organizer', true );
					if( ! empty( $em_organizer ) && count( $em_organizer ) > 0 ) {
						foreach( $em_organizer as $organizer ) {
							if( ! empty( $organizer ) ) {
								wp_set_object_terms( $post->ID, intval( $organizer ), 'em_event_organizer' );
							}
						}
					}
					// set gallery images
					$em_gallery_image_ids = get_post_meta( $post->ID, 'em_gallery_image_ids', true );
					if( ! empty( $em_gallery_image_ids ) && is_array( $em_gallery_image_ids ) ) {
						$em_gallery_image_ids = implode( ',', $em_gallery_image_ids );
						update_post_meta( $post->ID, 'em_gallery_image_ids', $em_gallery_image_ids );
					}
					// enable booking
					$enable_booking = get_post_meta( $post->ID, 'em_enable_booking', true );
					$em_custom_link_enabled = get_post_meta( $post->ID, 'em_custom_link_enabled', true );
					if( $enable_booking == 1 ) {
						update_post_meta( $post->ID, 'em_enable_booking', 'bookings_on' );
					} else if( ! empty( $em_custom_link_enabled ) ) {
						update_post_meta( $post->ID, 'em_enable_booking', 'external_bookings' );
					} else {
						update_post_meta( $post->ID, 'em_enable_booking', 'bookings_off' );
					} 
					// start date
					$em_start_date = ep_timestamp_to_datetime( $single_post->em_start_date, 'Y-m-d,h:i A', 1 );
					if( ! empty( $em_start_date ) ) {
						$event_start_dates = explode( ',', $em_start_date );
						if( ! empty( $event_start_dates ) ) {
							$em_start_date = strtotime( $event_start_dates[0] );
							$em_start_time = $event_start_dates[1];
							update_post_meta( $post->ID, 'em_start_date', $em_start_date );
							if( ! empty( $em_start_time ) ) {
								update_post_meta( $post->ID, 'em_start_time', $em_start_time );
							}
						}
					}
					// end date
					$em_end_date = ep_timestamp_to_datetime( $single_post->em_end_date, 'Y-m-d,h:i A', 1 );
					if( ! empty( $em_end_date ) ) {
						$event_end_dates = explode( ',', $em_end_date );
						if( ! empty( $event_end_dates ) ) {
							$em_end_date = strtotime( $event_end_dates[0] );
							$em_end_time = $event_end_dates[1];
							update_post_meta( $post->ID, 'em_end_date', $em_end_date );
							if( ! empty( $em_end_time ) ) {
								update_post_meta( $post->ID, 'em_end_time', $em_end_time );
							}
						}
					}

					// start booking date
					$em_start_booking_date = get_post_meta( $post->ID, 'em_start_booking_date', true );
					if( $em_start_booking_date ) {
						$em_start_booking_date = ep_timestamp_to_datetime( $em_start_booking_date, 'Y-m-d,h:i A', 1 );
					}

					// end booking date
					$em_last_booking_date = get_post_meta( $post->ID, 'em_last_booking_date', true );
					if( $em_last_booking_date ) {
						$em_last_booking_date = ep_timestamp_to_datetime( $em_last_booking_date, 'Y-m-d,h:i A', 1 );
					}

					// check if price tier not created for the event
					$get_price_tier_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $price_options_table WHERE `event_id` = %d", $post->ID ) );
					if( empty( $get_price_tier_data ) ) {
						$ticket_price = get_post_meta( $post->ID, 'em_ticket_price', true );
						$capacity = get_post_meta( $post->ID, 'em_standing_capacity', true );
						if( empty( $capacity ) ) {
							$capacity = get_post_meta( $post->ID, 'em_seating_capacity', true );
						}
						$tier_data = array();
						$tier_data['event_id'] = $post->ID;
						$tier_data['name'] = esc_html__('Default Price', 'eventprime-event-calendar-management');
						$tier_data['description'] = esc_html__('Default Price', 'eventprime-event-calendar-management');
						$tier_data['start_date'] = $em_start_booking_date;
						$tier_data['end_date'] = $em_last_booking_date;
						$tier_data['price'] = $ticket_price;
						$tier_data['special_price'] = '';
						$tier_data['capacity'] = $capacity;
						$tier_data['is_default'] = 1;
						$tier_data['is_event_price'] = 1;
						$tier_data['icon'] = '';
						$tier_data['priority'] = 1;
						$tier_data['status'] = 1;
						$tier_data['created_at'] = date_i18n("Y-m-d H:i:s", time());
						$result = $wpdb->insert( $price_options_table, $tier_data );
					}

					
					/* if( ! empty( $em_start_booking_date ) ) {
						$event_start_booking_dates = explode( ',', $em_start_booking_date );
						if( ! empty( $event_start_booking_dates ) ) {
							$em_start_booking_date = $event_start_booking_dates[0];
							$em_start_booking_time = $event_start_booking_dates[1];
							$updated_booking_start = array();
							$updated_booking_start['booking_type'] = 'custom_date';
							$updated_booking_start['start_date'] = ( ! empty( $em_start_booking_date ) ? $em_start_booking_date : '' );
							$updated_booking_start['start_time'] = ( ! empty( $em_start_booking_time ) ? $em_start_booking_time : '' );
							// check if start date exist in price tier
							$get_field_data = $wpdb->get_results( "SELECT `id`, `start_date` FROM $price_options_table WHERE `event_id` = $post->ID" );
							if( ! empty( $get_field_data ) ) {
								foreach( $get_field_data as $field_data ) {
									$em_start_booking_date = ep_timestamp_to_datetime( $field_data->start_date, 'Y-m-d,h:i A', 1 );
									if( ! empty( $em_start_booking_date ) ) {
										$event_start_booking_dates = explode( ',', $em_start_booking_date );
										if( ! empty( $event_start_booking_dates ) ) {
											$em_start_booking_date = $event_start_booking_dates[0];
											$em_start_booking_time = $event_start_booking_dates[1];
											$updated_booking_start = array();
											$updated_booking_start['booking_type'] = 'custom_date';
											$updated_booking_start['start_date'] = ( ! empty( $em_start_booking_date ) ? $em_start_booking_date : '' );
											$updated_booking_start['start_time'] = ( ! empty( $em_start_booking_time ) ? $em_start_booking_time : '' );
										}
									}

									$wpdb->update( $price_options_table, 
										array( 
											'booking_starts' => json_encode( $updated_booking_start ),
											'updated_at' 	 => date_i18n( "Y-m-d H:i:s", time() )
										), 
										array( 'id' => $field_data->id )
									);
								}
							}
						}
					}
					
					if( ! empty( $em_last_booking_date ) ) {
						$event_last_booking_dates = explode( ',', $em_last_booking_date );
						if( ! empty( $event_last_booking_dates ) ) {
							$em_last_booking_date = $event_last_booking_dates[0];
							$em_last_booking_time = $event_last_booking_dates[1];
							$updated_booking_end = array();
							$updated_booking_end['booking_type'] = 'custom_date';
							$updated_booking_end['end_date'] = ( ! empty( $em_last_booking_date ) ? $em_last_booking_date : '' );
							$updated_booking_end['end_time'] = ( ! empty( $em_last_booking_time ) ? $em_last_booking_time : '' );
							// check if end date exist in price tier
							$get_field_data = $wpdb->get_results( "SELECT `id`, `end_date` FROM $price_options_table WHERE `event_id` = $post->ID" );
							if( ! empty( $get_field_data ) ) {
								foreach( $get_field_data as $field_data ) {
									$em_last_booking_date = ep_timestamp_to_datetime( $field_data->end_date, 'Y-m-d,h:i A', 1 );
									if( ! empty( $em_last_booking_date ) ) {
										$event_last_booking_dates = explode( ',', $em_last_booking_date );
										if( ! empty( $event_last_booking_dates ) ) {
											$em_last_booking_date = $event_last_booking_dates[0];
											$em_last_booking_time = $event_last_booking_dates[1];
											$updated_booking_end = array();
											$updated_booking_end['booking_type'] = 'custom_date';
											$updated_booking_end['end_date'] = ( ! empty( $em_last_booking_date ) ? $em_last_booking_date : '' );
											$updated_booking_end['end_time'] = ( ! empty( $em_last_booking_time ) ? $em_last_booking_time : '' );
										}
									}

									$wpdb->update( $price_options_table, 
										array( 
											'booking_ends' => json_encode( $updated_booking_end ),
											'updated_at'   => date_i18n("Y-m-d H:i:s", time())
										), 
										array( 'id' => $field_data->id )
									);
								}
							}
						}
					} */

					// max tickets per person
					$em_max_tickets_per_person = get_post_meta( $post->ID, 'em_max_tickets_per_person', true );
					if( ! empty( $em_max_tickets_per_person ) ) {
						$get_field_data = $wpdb->get_results( "SELECT `id` FROM $price_options_table WHERE `event_id` = $post->ID" );
						if( ! empty( $get_field_data ) ) {
							foreach( $get_field_data as $field_data ) {
								$wpdb->update( $price_options_table, 
									array( 
										'max_ticket_no' => $em_max_tickets_per_person,
										'updated_at' 	=> date_i18n( "Y-m-d H:i:s", time() )
									), 
									array( 'id' => $field_data->id )
								);
							}
						}
					}

					// blank meta keys
					update_post_meta( $post->ID, 'em_hide_event_start_time', '' );
					update_post_meta( $post->ID, 'em_hide_event_start_date', '' );
					update_post_meta( $post->ID, 'em_hide_event_end_time', '' );
					update_post_meta( $post->ID, 'em_hide_end_date', '' );
					update_post_meta( $post->ID, 'em_event_date_placeholder', '' );
					update_post_meta( $post->ID, 'em_event_date_placeholder_custom_note', '' );
					update_post_meta( $post->ID, 'em_event_more_dates', '' );
					update_post_meta( $post->ID, 'em_event_add_more_dates', '' );
					update_post_meta( $post->ID, 'em_event_checkout_name', '' );
					update_post_meta( $post->ID, 'em_event_checkout_name_first_name', '' );
					update_post_meta( $post->ID, 'em_event_checkout_name_first_name_required', '' );
					update_post_meta( $post->ID, 'em_event_checkout_name_middle_name', '' );
					update_post_meta( $post->ID, 'em_event_checkout_name_middle_name_required', '' );
					update_post_meta( $post->ID, 'em_event_checkout_name_last_name', '' );
					update_post_meta( $post->ID, 'em_event_checkout_name_last_name_required', '' );
					update_post_meta( $post->ID, 'em_event_checkout_fields_data', '' );

					// for recurring
					$em_event_slug_type_options = get_post_meta( $post->ID, 'em_event_slug_type_options', true );
					if( ! empty( $em_event_slug_type_options ) ) {
						$em_event_slug_type_options = strtolower( $em_event_slug_type_options );
						update_post_meta( $post->ID, 'em_event_slug_type_options', $em_event_slug_type_options );
					}
					$em_recurring_events_slug_format = get_post_meta( $post->ID, 'em_recurring_events_slug_format', true );
					if( ! empty( $em_recurring_events_slug_format ) ) {
						if( $em_recurring_events_slug_format == 'Occurrence number' ) {
							$em_recurring_events_slug_format = 'number';
						} else{
							$em_recurring_events_slug_format = 'date';
						}
						update_post_meta( $post->ID, 'em_recurring_events_slug_format', $em_recurring_events_slug_format );
					}
					add_post_meta( $post->ID, 'em_recurrence_ends', 'on' );

					// migrate seating data
					$em_seats = get_post_meta( $post->ID, 'em_seats', true );
					if( ! empty( $em_seats ) ) {
						$em_seats = maybe_unserialize( $em_seats );
						$em_venue = get_post_meta( $post->ID, 'em_venue', true );
						if( ! empty( $em_venue ) ) {
							$plan_table_name = $wpdb->prefix.'eventprime_live_seat_plan';
							$seat_table_name = $wpdb->prefix.'eventprime_live_seating';
							// get plan id from venue id
							$venue_plan = $wpdb->get_row( "SELECT `id` FROM $plan_table_name WHERE `venue_id` = $em_venue" );
							if( ! empty( $venue_plan ) ) {
								// add seat plan meta
								add_post_meta( $post->ID, 'em_ls_seat_plan', $venue_plan->id );
								$plan_area = $wpdb->get_row( "SELECT `id`, `area_name` FROM $seat_table_name WHERE `plan_id` = $venue_plan->id" );
								if( ! empty( $plan_area ) ) {
									$area_id = $plan_area->id;
									$area_name = $plan_area->area_name;
									// update row name in seat
									foreach( $em_seats as $seats ) {
										foreach( $seats as $seat ) {
											$row = $seat->row;
											$indexNo = '';
											if( $row > 25 ) {
												$indexNo = intval( $row / 26 );
												$row = $row % 26;
											}
											$seat->row_name = chr( 65 + $row ) . $indexNo;
											$seat_variation_id = ( isset( $seat->variation_id ) && ! empty( $seat->variation_id ) ? $seat->variation_id : '' );
											$seat->ticket_id = $seat_variation_id;
											$seat->area_id = $area_id;
										}
									}
									$new_em_seats = new stdClass();
									$seat_props = array(
										'id' => $area_id,
										'name' => $area_name,
										'seats' => $em_seats,
										'area_properties' => ''
									);
									$new_em_seats->{$area_id} = (object)$seat_props;
									if( ! empty( $new_em_seats ) ) {
										update_post_meta( $post->ID, 'em_seat_data', maybe_serialize( $new_em_seats ) );
									}
								}
							}
						}
					}

					// add ticket template id to price option table
					$em_ticket_template = get_post_meta( $post->ID, 'em_ticket_template', true );
					if( ! empty( $em_ticket_template ) ) {
						$get_field_data = $wpdb->get_results( "SELECT `id` FROM $price_options_table WHERE `event_id` = $post->ID" );
						if( ! empty( $get_field_data ) ) {
							foreach( $get_field_data as $field_data ) {
								$wpdb->update( $price_options_table, 
									array( 
										'ticket_template_id' => $em_ticket_template,
										'updated_at' 	=> date_i18n( "Y-m-d H:i:s", time() )
									), 
									array( 'id' => $field_data->id )
								);
							}
						}
					}

					// add sponsors
					$sponsors = get_post_meta( $post->ID, 'em_sponsor_image_ids', true );
					if( ! empty( $sponsors ) && count( $sponsors ) > 0 ) {
						$post_sponsor = array();
						$sp_id = 1;
						foreach( $sponsors as $sponsor_id ) {
							$event_t = 'Event-' . $post->ID . '-sponsor-'.$sp_id;
							$new_post = array(
								'post_title'   => $event_t,
								'post_status'  => 'publish',
								'post_type'    => 'em_sponsor',
								'post_author'  => $post->post_author,
							); 
							$new_sponsor_post_id = wp_insert_post( $new_post );
							$post_sponsor[] = $new_sponsor_post_id;
							add_post_meta( $new_sponsor_post_id, '_thumbnail_id', $sponsor_id, true );

							$sp_id++;
						}
						if( ! empty( $post_sponsor ) ) {
							update_post_meta( $post->ID, 'em_sponsor', $post_sponsor );
						}
					}
				}
			}
		}
	}

	// performers migrate
	private static function ep_migrate_performers_data() {
		$args = array(
            'numberposts' => -1,
        	'post_type'   => EM_PERFORMER_POST_TYPE,
        	'post_status' => 'any',
        );
		$posts = get_posts( $args );
		if( ! empty( $posts ) ) {
			foreach( $posts as $post ) {
				$em_social_links = get_post_meta( $post->ID, 'em_social_links', true );
				if( ! empty( $em_social_links ) && is_object( $em_social_links ) ) {
					$em_social_links = (array)$em_social_links;
					update_post_meta( $post->ID, 'em_social_links', $em_social_links );
				}
			}
		}
	}

	// bookings migrate
	private static function ep_migrate_bookings_data() {
		$args = array(
            'numberposts' => -1,
        	'post_type'   => EM_BOOKING_POST_TYPE,
        	'post_status' => 'any',
        );
		$posts = get_posts( $args );
		if( ! empty( $posts ) ) {
			foreach( $posts as $post ) {
				add_post_meta( $post->ID, 'em_old_ep_booking', 1 );
			}
		}
	}

	// check for checkout field table
	private static function ep_check_for_checkout_fields_table() {
		// create table
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$checkout_fields_table = $wpdb->prefix.'eventprime_checkout_fields';

		if( version_compare( get_bloginfo('version'), '6.1')  < 0 ){
			require_once( ABSPATH . 'wp-includes/wp-db.php' );
		} else{
			require_once( ABSPATH . 'wp-includes/class-wpdb.php' );
		}
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		if( $wpdb->get_var( "SHOW TABLES LIKE '$checkout_fields_table'") == $checkout_fields_table ) {
			return;
	  	}

		$sql = "CREATE TABLE `{$checkout_fields_table}` (
			`id` bigint(20) NOT NULL AUTO_INCREMENT,
			`type` varchar(50) DEFAULT NULL,
			`label` varchar(255) DEFAULT NULL,
			`option_data` longtext DEFAULT NULL,
			`priority` integer(11) DEFAULT NULL,
			`status` tinyint(2) DEFAULT 1 NOT NULL,
			`created_by` integer(11) DEFAULT NULL,
			`last_updated_by` integer(11) DEFAULT NULL,
			`created_at` datetime NOT NULL,
			`updated_at` datetime DEFAULT NULL,
			PRIMARY KEY (`id`)
		){$charset_collate}";

		dbDelta( $sql );
		
		$ticket_category_table = $wpdb->prefix.'eventprime_ticket_categories';

		if( $wpdb->get_var( "SHOW TABLES LIKE '$ticket_category_table'") == $ticket_category_table ) {
			return;
	  	}

		$sql = "CREATE TABLE `{$ticket_category_table}` (
			`id` bigint(20) NOT NULL AUTO_INCREMENT,
			`event_id` integer(11) NOT NULL,
			`parent_id` integer(11) DEFAULT NULL,
			`name` varchar(100) DEFAULT NULL,
			`capacity` integer(100) DEFAULT NULL,
			`priority` integer(11) DEFAULT NULL,
			`status` tinyint(2) DEFAULT 1 NOT NULL,
			`created_by` integer(11) DEFAULT NULL,
			`last_updated_by` integer(11) DEFAULT NULL,
			`created_at` datetime NOT NULL,
			`updated_at` datetime DEFAULT NULL,
			PRIMARY KEY (`id`)
		){$charset_collate}";
        
		dbDelta( $sql );
	}

	// attendee booking fields migrate
	private static function ep_migrate_attendee_booking_fields_data() {
		global $wpdb;
		$custom_booking_field_data = ep_get_global_settings('custom_booking_field_data');
		if( ! empty( $custom_booking_field_data ) ) {
			self::ep_check_for_checkout_fields_table();
			$checkout_fields_table = $wpdb->prefix.'eventprime_checkout_fields';
			$em_event_checkout_attendee_fields = $em_event_checkout_fields_data = $required_field_id = array();
			$priority = 1;
			foreach( $custom_booking_field_data as $booking_fields ) {
				if( ! empty( $booking_fields ) ) {
					$save_data = array();
					$save_data['label'] = sanitize_text_field( $booking_fields->label );
					$save_data['type'] = sanitize_text_field( $booking_fields->type );
					$save_data['priority'] = $priority;
					$save_data['status'] = 1;
					$save_data['created_by'] = get_current_user_id();
					$save_data['created_at'] = date_i18n( "Y-m-d H:i:s", time() );
					$result = $wpdb->insert( $checkout_fields_table, $save_data );
					$field_id = $wpdb->insert_id;
					$em_event_checkout_fields_data[] = $field_id;
					if( $booking_fields->required == 1 ) {
						$required_field_id[] = $field_id;
					}
					$priority++;
				}
			}

			// now set checkout fields in the events
			$args = array(
				'numberposts' => -1,
				'post_type'   => EM_EVENT_POST_TYPE,
				'post_status' => 'publish',
			);
			$posts = get_posts( $args );
			if( ! empty( $posts ) ) {
				// first get all saved checkout fields
				if( ! empty( $em_event_checkout_fields_data ) ) {
					$em_event_checkout_attendee_fields['em_event_checkout_fields_data'] = $em_event_checkout_fields_data;
					// check for required fields
					if( ! empty( $required_field_id ) ) {
						$em_event_checkout_attendee_fields['em_event_checkout_fields_data_required'] = $required_field_id;
					}

					// now set in event
					foreach( $posts as $post ) {
						add_post_meta( $post->ID, 'em_event_checkout_attendee_fields', $em_event_checkout_attendee_fields );
					}
				}
			}
		}
	}

	// guest booking migration
	private static function ep_revamp_update_guest_booking_options() {
		global $wpdb;
		//ep_get_global_settings
		$custom_guest_booking_field_data = ep_get_global_settings( 'custom_guest_booking_field_data' );
		if( ! empty( $custom_guest_booking_field_data ) ) {
			$old_gb_data = $guest_booking_checkout_fields = array();$gb_num = 0;$priority = 1;
			self::ep_check_for_checkout_fields_table();
			$checkout_fields_table = $wpdb->prefix.'eventprime_checkout_fields';
			$get_last_priority = $wpdb->get_row( "SELECT `priority` FROM $checkout_fields_table ORDER BY `id` DESC" );
			if( ! empty( $get_last_priority ) ) {
				$priority = $get_last_priority->priority;
			}
			foreach( $custom_guest_booking_field_data as $gb_data ) {
				if( ! empty( $gb_data ) ) {
					if( $gb_num > 2 ) {
						$old_gb_data[] = $gb_data;
						$check_for_gb_label = str_ireplace( array( '\'', '"',
						',' , ';', '<', '>' ), ' ', $gb_data->label );
						$get_field_data = $wpdb->get_row( "SELECT `id` FROM $checkout_fields_table WHERE `label` = $gb_data->label AND `type` = $gb_data->type" );
						if( empty( $get_field_data ) ) {
							$save_data = array();
							$save_data['label'] = sanitize_text_field( $check_for_gb_label );
							$save_data['type'] = sanitize_text_field( $gb_data->type );
							$save_data['priority'] = $priority;
							$save_data['status'] = 1;
							$save_data['created_by'] = get_current_user_id();
							$save_data['created_at'] = date_i18n( "Y-m-d H:i:s", time() );
							$result = $wpdb->insert( $checkout_fields_table, $save_data );
							$field_id = $wpdb->insert_id;

							$new_gb_data = array(
								'type' 				=> $gb_data->type,
								'checkout_field_id' => $field_id,
								'show' 				=> 1,
								'mandatory' 		=> $gb_data->required,
								'label' 			=> $check_for_gb_label,
							);
							$new_gb_key = str_replace( ' ', '_', strtolower( $check_for_gb_label ) );
							$guest_booking_checkout_fields[$new_gb_key] = $new_gb_data;
							$priority++;
						} else{
							$new_gb_data = array(
								'type' 				=> $gb_data->type,
								'checkout_field_id' => $get_field_data->id,
								'show' 				=> 1,
								'mandatory' 		=> $gb_data->required,
								'label' 			=> $check_for_gb_label,
							);
							$new_gb_key = str_replace( ' ', '_', strtolower( $check_for_gb_label ) );
							$guest_booking_checkout_fields[$new_gb_key] = $new_gb_data;
						}
					}
					$gb_num++;
				}
			}
			// save in the global settings
			if( ! empty( $guest_booking_checkout_fields ) && count( $guest_booking_checkout_fields ) > 0 ) {
				$global_settings = EventM_Factory_Service::ep_get_instance( 'EventM_Admin_Model_Settings' );
        		$global_settings_data = $global_settings->ep_get_settings();
				$global_settings_data->guest_booking_checkout_fields = $guest_booking_checkout_fields;
				$global_settings->ep_save_settings( $global_settings_data );
			}
		}
	}

    private static function ep_revamp_update_global_option() {
		$instance = EP();
		// update revamp version
		update_option( 'ep_update_revamp_version', 1 );
		update_option( 'ep_db_need_to_run_migration', 0 );
		update_option( EM_DB_VERSION, $instance->version );
	}

}