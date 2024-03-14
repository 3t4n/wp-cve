<?php
defined( 'ABSPATH' ) || exit;

/**
 * Factory class
 */

class EventM_Factory_Service {
	/**
	 * Check the request type
	 *
	 * @param string $type admin, frontend.
	 * @return bool
	 */
	public static function ep_is_request( $type ){
		switch ( $type ){
			case 'admin':
				return is_admin();
			case 'frontend':
				return !is_admin();
		}
	}

	/**
	 * Call the global setting option.
	 * 
	 * @param string $meta meta key.
	 */
	public static function ep_global_settings( $meta = null ) {
		// Load global setting array from options table
	    $global_options = get_option(EM_GLOBAL_SETTINGS);
	    // Check if option exists 
	    if ( ! empty( $global_options ) ) {
	        if ( ! empty( $meta ) ) {
	            if ( array_key_exists( $meta, $global_options ) ) {
	                return $global_options[$meta];
	            } else {
	                // Option does not exists
	                return false;
	            }
	        }
	        return $global_options;
	    }
	    return false;
	}

	/**
	 * Get the requested parameters
	 * 
	 * @param string $key The query parameter
	 * @param string $default Thedefault value to return if not found
	 * 
	 * @return string The requested parameter.
	 */
	public static function ep_get_request_param( $key, $default = '' ) {
    	// If not request set
	    if ( ! isset( $_REQUEST[ $key ] ) || empty( $_REQUEST[ $key ] ) ) {
	        return $default;
	    }
	 
	    // Set so process it
	    return sanitize_text_field( (string) wp_unslash( $_REQUEST[ $key ] ) );
	}

	public static function ep_define_common_field_errors() {
		$errors = array(
			'required' 		 => esc_html__( 'This is required field', 'eventprime-event-calendar-management' ),
			'invalid_url' 	 => esc_html__( 'Please enter a valid url', 'eventprime-event-calendar-management' ),
			'invalid_email'  => esc_html__( 'Please enter a valid email', 'eventprime-event-calendar-management' ),
			'invalid_phone'  => esc_html__( 'Please enter a valid phone no.', 'eventprime-event-calendar-management' ),
			'invalid_number' => esc_html__( 'Please enter a valid number', 'eventprime-event-calendar-management' ),
			'invalid_date'   => esc_html__( 'Please enter a valid date', 'eventprime-event-calendar-management' ),
		);
		return $errors;
	}

	/**
	 * Load plugin controllers
	 * 
	 * @param string $class_name Controller class name.
	 * 
	 * @return object Class instance.
	 */
	public static function ep_get_instance( $class_name ) {
		$instance = '';
		if( !empty( $class_name ) ) {
			if( class_exists( $class_name ) ) {
				$instance = new $class_name;
			}
		}
		return $instance;
	}

	/**
	 * Get RM forms list
	 */
	public static function ep_get_rm_forms() {
        $rm_forms = array();
        // Registration Magic Integration
        if ( ep_is_registration_magic_active() ) {
            $where = array( "form_type" => 1 );
            $data_specifier = array( '%d' );
            $forms = RM_DBManager::get( 'FORMS', $where, $data_specifier, 'results', 0, 99999, '*', $sort_by = 'created_on', $descending = true );
            //$form_dropdown_array[0] = __('Default EventPrime Form','eventprime-event-calendar-management');
            if ( $forms ) {
                foreach ( $forms as $form ) {
                    $rm_forms[$form->form_id] = $form->form_name;
				}
			}
        }
        return $rm_forms;
    }

	/**
	 * Get event views
	 * 
	 * @return array
	 */
	public static function get_event_views() {
		$event_views = array(
			'square_grid'     	=> 'Square Grid',
			'staggered_grid'  	=> 'Staggered Grid',
			'rows'     			=> 'Stacked Rows',
			'slider'   			=> 'Slider',
			'month'    			=> 'Calendar / Month',
			'week'    			=> 'Calendar / Week - Regular',
			'listweek' 			=> 'Calendar / Week - Agenda',
			'day'      			=> 'Calendar Day',
		);

		return apply_filters( 'ep_event_views', $event_views );
	}

	/**
     * Get event types limited fields data
	 * 
	 * @param array $fields Fields.
	 * 
	 * @param int $with_id
	 * 
	 * @return object|array Event Type Data.
     */
    public static function ep_get_event_types( $fields, $with_id = 0 ) {
		$event_type_data = array();
        $event_type = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Type_Controller_List' );
		if( !empty( $event_type ) && !empty( $fields ) ) {
			$event_type_data = $event_type->get_event_type_field_data( $fields, $with_id );
		}

		return $event_type_data;
    }
    
    /**
     * Get event organizers limited fields data
	 * 
	 * @param array $fields Fields.
	 * 
	 * @return object|array Event Type Data.
     */
    public static function ep_get_organizers( $fields ) {
        $organizers_data = array();
        $organizers = EventM_Factory_Service::ep_get_instance( 'EventM_Organizer_Controller_List' );
		if( !empty( $organizers ) && !empty( $fields ) ) {
			$organizers_data = $organizers->get_event_organizer_field_data( $fields );
		}

		return $organizers_data;
    }
    
    /**
     * Get event organizers limited fields data
	 * 
	 * @param array $fields Fields.
	 * 
	 * @param int $with_id
	 * 
	 * @return object|array Event Venue Data.
     */
    public static function ep_get_venues( $fields, $with_id = 0 ) {
        $venues_data = array();
        $venues = EventM_Factory_Service::ep_get_instance( 'EventM_Venue_Controller_List' );
		if( !empty( $venues ) && !empty( $fields ) ) {
			$venues_data = $venues->get_event_venues_field_data( $fields, $with_id );
		}
        return $venues_data;
    }
    
    /**
     * Get performers limited fields data
	 * 
	 * @param array $fields Fields.
	 * 
	 * @return object|array Performer Data.
     */
    public static function ep_get_performers( $fields ) {
		$performers_data = array();
        $performers = EventM_Factory_Service::ep_get_instance( 'EventM_Performer_Controller_List' );
		if( !empty( $performers ) && !empty( $fields ) ) {
			$performers_data = $performers->get_performer_field_data( $fields );
		}

		return $performers_data;
    }
    
    /**
     * Get events limited fields data
	 * 
	 * @param array $fields Fields.
	 * 
	 * @return object|array Events Data.
     */
    public static function ep_get_events( $fields ) {
		$events_data = array();
        $events = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
		if( !empty( $events ) && !empty( $fields ) ) {
			$events_data = $events->get_events_field_data( $fields );
		}

		return $events_data;
    }

	/**
	 * Add rewrite rules
	 */
	public static function ep_load_rewrites() {
		$slug = 'event';
		$post_type = 'em_event';

		return array(
            '(?:'.$slug.')/?$'=>'index.php?post_type='.$post_type,
            '(?:'.$slug.')/(feed|rdf|rss|rss2|atom)/?$'=>'index.php?post_type='.$post_type.'&feed=$matches[1]',
        );
	}

	/**
	 * Load single template
	 */
	public static function ep_load_single_template( $template ) {
		if( is_single() ) {
			if( get_post_type() == EM_EVENT_POST_TYPE ) {
				$template = locate_template( 'single-' . EM_EVENT_POST_TYPE . '.php' );
				if( $template == '' ) {
					$template = ep_get_template_part( 'events/single-ep-event' );
				}
			} elseif( get_post_type() == EM_PERFORMER_POST_TYPE ) {
				$template = locate_template( 'single-' . EM_PERFORMER_POST_TYPE . '.php' );
				if( $template == '' ) {
					$template = ep_get_template_part( 'performers/single-ep-performer' );
				}
			} else{
				$extensions = (array)EP()->extensions;
				if( ! empty( $extensions ) && in_array( 'sponsor', $extensions ) ) {
					if( get_post_type() == EM_SPONSOR_POST_TYPE ) {
						$template = locate_template( 'single-' . EM_SPONSOR_POST_TYPE . '.php' );
						if( $template == '' && defined( 'EMSPS_BASE_DIR' ) ) {
							$template = ep_get_template_part( 'single-ep-sponsor', null, null, EMSPS_BASE_DIR.'/includes' );
						}
					}
				}
			}
		} elseif( is_tax( EM_EVENT_TYPE_TAX ) ) {
			$template = locate_template('taxonomy-em-event-type.php');
			if( $template == '' ) {
				$template = ep_get_template_part( 'event_types/single-ep-event-type' );
			}
		} elseif( is_tax( EM_EVENT_VENUE_TAX ) ) {
			$template = locate_template('taxonomy-em-venue.php');
			if( $template == '' ) {
				$template = ep_get_template_part( 'venues/single-ep-venue' );
			}
		} elseif( is_tax( EM_EVENT_ORGANIZER_TAX ) ) {
			$template = locate_template('taxonomy-em-event-organizer.php');
			if( $template == '' ) {
				$template = ep_get_template_part( 'organizers/single-ep-event-organizer' );
			}
		}
		
		return $template;
	}

	/**
	 * Get child events
	 * 
	 * @param int $post_id Parent Event ID.
	 * 
	 * @return object $posts Post.
	 */
	public static function ep_get_child_events( $post_id, $args = array() ) {
		$default = array(
			'post_parent' => $post_id,
			'post_type'   => 'em_event',
			'post_status' => 'any',
			'numberposts' => -1,
			'orderby'     => 'em_start_date',
			'order'       => 'ASC',
		);
		$args = wp_parse_args( $args, $default );
		$posts = get_posts( $args );
		return $posts;
	}

	/**
	 * Get venue by venue id
	 * 
	 * @param int $venue_id Venue ID.
	 * 
	 * @return object Venue.
	 */
	public static function ep_get_venue_by_id( $venue_id ) {
		$venue = new stdClass();
		if( ! empty( $venue_id ) ) {
			$venue         = EventM_Factory_Service::ep_get_instance( 'EventM_Venue_Controller_List' );
			$single_venue  = $venue->get_single_venue( $venue_id );
			if( ! empty( $single_venue ) ) {
				$venue = $single_venue;
			}
		}
		return $venue;
	}

	/**
	 * Get event type by event_type id
	 * 
	 * @param int $event_type_id Event Type ID.
	 * 
	 * @return object Event Type.
	 */
	public static function ep_get_event_type_by_id( $event_type_id ) {
		$event_type = new stdClass();
		if( ! empty( $event_type_id ) ) {
			$event_type_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Type_Controller_List' );
			$event_type = $event_type_controller->get_single_event_type( $event_type_id );
		}
		return $event_type;
	}

	/**
	 * Get upcoming events by venue id
	 * 
	 * @param int $venue_id Venue ID.
	 * 
	 * @param array $exclude Exclude event id
	 * 
	 * @return array Events.
	 */
	public static function get_upcoming_event_by_venue_id( $venue_id, $exclude = array() ) {
		$venue = EventM_Factory_Service::ep_get_instance( 'EventM_Venue_Controller_List' );
		$args = $events_data = array();
		if( ! empty( $exclude ) ) {
			$args['post__not_in'] = $exclude;
			$args['numberposts']  = -1;
		}
		$event_qry = $venue->get_upcoming_events_for_venue( $venue_id, $args );
		$events = $event_qry->posts;
		if( !empty( $events ) && count( $events ) > 0 ) {
			$event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
			foreach( $events as $post ) {
				$event = $event_controller->get_single_event( $post->ID, $post );
				if( ! empty( $event ) ) {
					$events_data[] = $event;
				}
			}
		}
		return $events_data;
	}

	/**
	 * Get organizers
	 * 
	 * @param int|array $organizers Organizer ID.
	 * 
	 * @return array Organizer.
	 */
	public static function get_organizers_by_id( $organizers ) {
		if( is_int( $organizers ) ) {
			$organizers = array( $organizers );
		}
		if( ! empty( $organizers ) && ! is_array( $organizers ) ) $organizers = (array)$organizers;

		$organizers_data = array();
		if( ! empty( $organizers ) ) {
			foreach( $organizers as $id ) {
				if( ! empty( $id ) ) {
					$organizer_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Organizer_Controller_List' );
					$organizer = $organizer_controller->get_single_organizer( $id );
					$organizers_data[] = $organizer;
				}
			}
		}
		return $organizers_data;
	}

	/**
	 * Get performers
	 * 
	 * @param int|array $performers $performer ID.
	 * 
	 * @return array Performer.
	 */
	public static function get_performers_by_id( $performers ) {
		if( is_int( $performers ) ) {
			$performers = array( $performers );
		}
		$performers_data = array();
		if( ! empty( $performers ) ) {
			foreach( $performers as $id ) {
				if( ! empty( $id ) ) {
					$performer_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Performer_Controller_List' );
					$performer = $performer_controller->get_single_performer( $id );
					$performers_data[] = $performer;
				}
			}
		}
		return $performers_data;
	}

	/**
	 * Load events full data
	 * 
	 * @param array Events post.
	 * 
	 * @return array Events data with metas.
	 */
	public static function load_event_full_data( $events ){
		$events_data = array();
		if( ! empty( $events ) && count( $events ) > 0 ) {
			$event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
			foreach( $events as $post ) {
				$event = $event_controller->get_single_event( $post->ID, $post );
				if( ! empty( $event ) ) {
					$events_data[] = $event;
				}
			}
		}
		return $events_data;
	}

	/**
	 * Get all offers of an event.
	 * 
	 * @param object $event Event.
	 * 
	 * @return array All offers.
	 */
	public static function get_event_all_offers( $event ) {
		$all_offers_data = array(
			'all_offers' 		 => array(),
			'all_show_offers' 	 => array(),
			'show_ticket_offers' => array(),
			'ticket_offers' 	 => array(),
			'applicable_offers'  => array()
		);
		if( ! empty( $event ) ) {
			$event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
			$all_tickets = $event_controller->get_event_all_tickets( $event );
			if( ! empty( $all_tickets ) && count( $all_tickets ) > 0 ) {
				foreach( $all_tickets as $ticket ) {
					if( ! empty( $ticket->offers ) ) {
						$all_offers_data = EventM_Factory_Service::get_event_single_offer_data( $all_offers_data, $ticket, $event->em_id );
						/* $ticket_offers = json_decode( $ticket->offers );
						if( ! empty( $ticket_offers ) ) {
							foreach( $ticket_offers as $to ) {
								$all_offers_data['all_offers'][] = $to;
								if( isset( $to->em_ticket_show_offer_detail ) && ! empty( $to->em_ticket_show_offer_detail ) ) {
									$all_offers_data['all_show_offers'][$to->uid] = $to;
									$all_offers_data['show_ticket_offers'][$ticket->id][$to->uid] = $to;
								}
								$all_offers_data['ticket_offers'][$ticket->id][$to->uid] = $to;
							}
							$offer_applied_data = EventM_Factory_Service::get_event_offer_applied_data( $ticket_offers, $ticket );
							if( ! empty( $offer_applied_data ) && count( $offer_applied_data ) > 0 ) {
								$all_offers_data['applicable_offers'][$ticket->id] = $offer_applied_data;
							}
						} */
					}
				}
			}
		}
		return $all_offers_data;
	}

	/**
	 * Update all offer data from single offer
	 */
	public static function get_event_single_offer_data( $all_offers_data, $ticket, $event_id ) {
		$ticket_offers = json_decode( $ticket->offers );
		if( ! empty( $ticket_offers ) ) {
			foreach( $ticket_offers as $to ) {
				$all_offers_data['all_offers'][] = $to;
				if( isset( $to->em_ticket_show_offer_detail ) && ! empty( $to->em_ticket_show_offer_detail ) ) {
					$all_offers_data['all_show_offers'][$to->uid] = $to;
					$all_offers_data['show_ticket_offers'][$ticket->id][$to->uid] = $to;
				}
				$all_offers_data['ticket_offers'][$ticket->id][$to->uid] = $to;
			}
			$offer_applied_data = EventM_Factory_Service::get_event_offer_applied_data( $ticket_offers, $ticket, $event_id );
			if( ! empty( $offer_applied_data ) && count( $offer_applied_data ) > 0 ) {
				foreach( $offer_applied_data as $applied_offer_key => $ep_applied_offer ) {
					$all_offers_data['applicable_offers'][$ticket->id][$applied_offer_key] = $ep_applied_offer;
				}
			}
		}
		return $all_offers_data;
	}

	/**
	 * get offer start and end date.
	 * 
	 * @param object $offer Offer Data.
	 * 
	 * @param object $event Event Data.
	 * 
	 * @return string Offer Date.
	 */
	public static function get_offer_date( $offer, $event ) {
		$offer_date = '';
		if( ! empty( $offer ) ) {
			$offer_start_timestamp = $offer_end_timestamp = $book_start_date = $book_end_date = '';
			$booking_type = $offer->em_offer_start_booking_type;
			if( $booking_type == 'custom_date' ) {
				if( ! empty( $offer->em_offer_start_booking_date ) ) {
					// offer start
					$book_start_date = $offer->em_offer_start_booking_date;
					if( ! empty( $offer->em_offer_start_booking_time ) ) {
						$book_start_date .= ' ' . $offer->em_offer_start_booking_time;
						$offer_start_timestamp = ep_datetime_to_timestamp( $book_start_date );
					} else{
						$offer_start_timestamp = ep_date_to_timestamp( $book_start_date );
					}
					//offer end
					if( ! empty( $offer->em_offer_ends_booking_date ) ) {
						$book_end_date = $offer->em_offer_ends_booking_date;
						if( ! empty( $offer->em_offer_ends_booking_time ) ) {
							$book_end_date .= ' ' . $offer->em_offer_ends_booking_time;
							$offer_end_timestamp = ep_datetime_to_timestamp( $book_end_date );
						} else{
							$offer_end_timestamp = ep_date_to_timestamp( $book_end_date );
						}
					}
				}
				// if offer start and end date is same the show the time.
				if( ! empty( $offer->em_offer_start_booking_date ) ) {
					$offer_date = esc_html__( 'Offer Date:', 'eventprime-event-calendar-management' );
					$offer_start_timestamp = ep_date_to_timestamp( $offer->em_offer_start_booking_date );
					$offer_date .= ' ' . ep_timestamp_to_date( $offer_start_timestamp, 'd M', 1 );
					if( ! empty( $offer->em_offer_ends_booking_date ) ) {
						if( $offer->em_offer_start_booking_date == $offer->em_offer_ends_booking_date ) {
							if( ! empty( $offer->em_offer_start_booking_time ) && ! empty( $offer->em_offer_ends_booking_time ) ) {
								$offer_date .= ' ' . $offer->em_offer_start_booking_time . ' to ' . $offer->em_offer_ends_booking_time;
							}
						} else{
							if( ! empty( $offer_end_timestamp ) ) {
								$offer_date .= ' - ' . ep_timestamp_to_date( $offer_end_timestamp, 'd M', 1 );
							}
						}
					}
				}
			} elseif( $booking_type == 'event_date' ) {
				$event_option = $offer->em_offer_start_booking_event_option;
				$offer_start_timestamp = '';
				if( $event_option == 'event_start' ) {
					$offer_start_timestamp = $event->em_start_date;
					if( ! empty( $event->em_start_time ) ) {
						$offer_start_timestamp = ep_timestamp_to_date( $event->em_start_date );
						$offer_start_timestamp .= ' ' . $event->em_start_time;
						$offer_start_timestamp = ep_datetime_to_timestamp( $offer_start_timestamp );
					}
				} elseif( $event_option == 'event_ends' ) {
					$offer_start_timestamp = $event->em_end_date;
					if( ! empty( $event->em_end_time ) ) {
						$offer_start_timestamp = ep_timestamp_to_date( $event->em_end_date );
						$offer_start_timestamp .= ' ' . $event->em_end_time;
						$offer_start_timestamp = ep_datetime_to_timestamp( $offer_start_timestamp );
					}
				} else{
					if( ! empty( $event_option ) ) {
						$em_event_add_more_dates = $event->em_event_add_more_dates;
						if( ! empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
							foreach( $em_event_add_more_dates as $more_dates ) {
								if( $more_dates['uid'] == $event_option ) {
									$offer_start_timestamp = $more_dates['date'];
									if( ! empty( $more_dates['time'] ) ) {
										$date_more = ep_timestamp_to_date( $more_dates['date'] );
										$date_more .= ' ' . $more_dates['time'];
										$offer_start_timestamp = ep_datetime_to_timestamp( $date_more );
									}
									break;
								}
							}
						}
					}
				}

				// offer end
				$end_event_option = $offer->em_offer_ends_booking_event_option;
				$offer_end_timestamp = '';
				if( $end_event_option == 'event_start' ) {
					$offer_end_timestamp = $event->em_start_date;
					if( ! empty( $event->em_start_time ) ) {
						$offer_end_timestamp = ep_timestamp_to_date( $event->em_start_date );
						$offer_end_timestamp .= ' ' . $event->em_start_time;
						$offer_end_timestamp = ep_datetime_to_timestamp( $offer_end_timestamp );
					}
				} elseif( $end_event_option == 'event_ends' ) {
					$offer_end_timestamp = $event->em_end_date;
					if( ! empty( $event->em_end_time ) ) {
						$offer_end_timestamp = ep_timestamp_to_date( $event->em_end_date );
						$offer_end_timestamp .= ' ' . $event->em_end_time;
						$offer_end_timestamp = ep_datetime_to_timestamp( $offer_end_timestamp );
					}
				} else{
					if( ! empty( $end_event_option ) ) {
						$em_event_add_more_dates = $event->em_event_add_more_dates;
						if( ! empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
							foreach( $em_event_add_more_dates as $more_dates ) {
								if( $more_dates['uid'] == $end_event_option ) {
									$offer_end_timestamp = $more_dates['date'];
									if( ! empty( $more_dates['time'] ) ) {
										$date_more = ep_timestamp_to_date( $more_dates['date'] );
										$date_more .= ' ' . $more_dates['time'];
										$offer_end_timestamp = ep_datetime_to_timestamp( $date_more );
									}
									break;
								}
							}
						}
					}
				}
				if( ! empty( $offer_start_timestamp ) ) {
					$offer_date = esc_html__( 'Offer Date:', 'eventprime-event-calendar-management' );
					$offer_date .= ' ' . ep_timestamp_to_date( $offer_start_timestamp, 'd M', 1 );
				}
				if( ! empty( $offer_end_timestamp ) ) {
					$offer_date .= ' - ' . ep_timestamp_to_date( $offer_end_timestamp, 'd M', 1 );
				}
			}
			
			if( empty( $offer_date ) && ! empty( $offer_start_timestamp ) ) {
				$offer_date = esc_html__( 'Offer Date:', 'eventprime-event-calendar-management' );
				$offer_date .= ' ' . ep_timestamp_to_date( $offer_start_timestamp, 'd M', 1 );

				if( ! empty( $offer_end_timestamp ) ) {
					$offer_date .= ' - ' . ep_timestamp_to_date( $offer_end_timestamp, 'd M', 1 );
				}
			}
		}
		return $offer_date;
	}

	/**
	 * Get Event QR code image url.
	 * 
	 * @param object $event Event.
	 * 
	 * @return string QR code image url.
	 */
	public static function get_event_qr_code( $event ) {
		$image_url = '';
		if( ! empty( $event ) && isset( $event->event_url ) && ! empty( $event->event_url ) ) {
			$url = $event->event_url;
			$file_name = 'ep_qr_'.md5($url).'.png';
			$upload_dir = wp_upload_dir();
			$file_path = $upload_dir['basedir'] . '/ep/' . $file_name;
			if( ! file_exists( $file_path ) ) {
				if( ! file_exists( dirname( $file_path ) ) ){
					mkdir( dirname( $file_path ), 0755 );
				}
				require_once EP_BASE_DIR . 'includes/lib/qrcode.php';
				$qrCode = new QRcode();
				$qrCode->png( $url, $file_path, 'M', 4, 2 );
			}
			$image_url = esc_url( $upload_dir['baseurl'].'/ep/'.$file_name );
		}
		return $image_url;
	}

	/**
	 * Get ticket category name.
	 * 
	 * @param int $category_id Category Id.
	 * 
	 * @param object $event Event.
	 * 
	 * @return string Category name.
	 */
	public static function get_ticket_category_name( $category_id, $event ) {
		$cat_name = '';
		if( ! empty( $category_id ) && ! empty( $event ) ) {
			if( ! empty( $event->ticket_categories ) ) {
				foreach( $event->ticket_categories as $category ) {
					if( $category->id == $category_id ) {
						$cat_name = $category->name;
						break;
					}
				}
			}
		}
		return $cat_name;
	}

	/**
	 * Get event checkout fields.
	 * 
	 * @param object $event Event.
	 * 
	 * @return array
	 */
	public static function get_event_checkout_fields( $event ) {
		global $wpdb;
		$attendee_fields = array();
		if( ! empty( $event->em_event_checkout_attendee_fields ) ) {
			$checkout_table_name = $wpdb->prefix.'eventprime_checkout_fields';
			$attendee_fields = $event->em_event_checkout_attendee_fields;
			if( ! empty( $attendee_fields ) && ! empty( $attendee_fields['em_event_checkout_fields_data'] ) && count( $attendee_fields['em_event_checkout_fields_data'] ) > 0 ) {
				$attendee_fields_data = array();
				foreach( $attendee_fields['em_event_checkout_fields_data'] as $fields ) {
					$get_field_data = $wpdb->get_row( $wpdb->prepare( "SELECT `id`, `type`, `label` FROM $checkout_table_name WHERE `id` = %d", $fields ) );
					if( ! empty( $get_field_data ) ) {
						$attendee_fields_data[] = $get_field_data;
					}
				}
				$attendee_fields['em_event_checkout_fields_data'] = $attendee_fields_data;
			}
		}
		return $attendee_fields;
	}

	/**
	 * Download iCal file
	 */
	public static function get_ical_file() {
		if( ! is_admin() ) {
			if( isset( $_REQUEST['event'] ) ) {
				$event_id = absint( $_REQUEST['event'] );
				if( ! empty( $event_id ) ) {
					if( isset( $_REQUEST['download'] ) ) {
						$download_format = sanitize_text_field( $_REQUEST['download'] );
						if ($download_format === 'ical') {
							$event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
							$event = $event_controller->get_single_event( $event_id );
							$event_url = $event->event_url;
							$event_content = preg_replace('#<a[^>]*href="((?!/)[^"]+)">[^<]+</a>#', '$0 ( $1 )', $event->description);
							$event_content = str_replace("<p>", "\\n", $event_content);
							$event_content = strip_shortcodes(strip_tags($event_content));
							$event_content = str_replace("\r\n", "\\n", $event_content);
							$event_content = str_replace("\n", "\\n", $event_content);
							$event_content = preg_replace('/(<script[^>]*>.+?<\/script>|<style[^>]*>.+?<\/style>)/s', '', $event_content);

							$gmt_offset_seconds = ep_gmt_offset_seconds( $event->em_start_date );
							$time_format = ( $event->em_all_day == 1 ) ? 'Ymd' : 'Ymd\\THi00\\Z';
		
							$crlf = "\r\n";
		
							$ical  = "BEGIN:VCALENDAR".$crlf;
							$ical .= "VERSION:2.0".$crlf;
							$ical .= "METHOD:PUBLISH".$crlf;
							$ical .= "CALSCALE:GREGORIAN".$crlf;
							$ical .= "PRODID:-//WordPress - EPv".EVENTPRIME_VERSION."//EN".$crlf;
							$ical .= "X-ORIGINAL-URL:".home_url().'/'.$crlf;
							$ical .= "X-WR-CALNAME:".get_bloginfo('name').$crlf;
							$ical .= "X-WR-CALDESC:".get_bloginfo('description').$crlf;
							$ical .= "REFRESH-INTERVAL;VALUE=DURATION:PT1H".$crlf;
							$ical .= "X-PUBLISHED-TTL:PT1H".$crlf;
							$ical .= "X-MS-OLK-FORCEINSPECTOROPEN:TRUE".$crlf;
		
							$ical .= "BEGIN:VEVENT".$crlf;
							$ical .= "CLASS:PUBLIC".$crlf;
							$ical .= "UID:EP-".md5( strval( $event->em_id ) )."@".ep_get_site_domain().$crlf;
							$ical .= "DTSTART:".gmdate( $time_format, ( ep_convert_event_date_time_to_timestamp( $event, 'start' ) ) ).$crlf;
							$ical .= "DTEND:".gmdate( $time_format, ( ep_convert_event_date_time_to_timestamp( $event, 'end' ) ) ).$crlf;
							$ical .= "DTSTAMP:".get_the_date( $time_format, $event->em_id ).$crlf;
							$ical .= "CREATED:".get_the_date( 'Ymd', $event->em_id ).$crlf;
							$ical .= "LAST-MODIFIED:".get_the_modified_date( 'Ymd', $event->em_id ).$crlf;
							$ical .= "SUMMARY:".html_entity_decode( $event->name, ENT_NOQUOTES, 'UTF-8' ).$crlf;
							$ical .= "DESCRIPTION:".html_entity_decode( $event_content, ENT_NOQUOTES, 'UTF-8' ).$crlf;
							$ical .= "X-ALT-DESC;FMTTYPE=text/html:".html_entity_decode( $event_content, ENT_NOQUOTES, 'UTF-8' ).$crlf;
							$ical .= "URL:".$event_url.$crlf;
		
							if ( ! empty( $event->venue_details ) ) {
								$ical .= "LOCATION:".trim(strip_tags($event->venue_details->em_address)).$crlf;
							}
		
							$cover_image_id = get_post_thumbnail_id( $event->em_id );
							if ( ! empty( $cover_image_id ) && $cover_image_id > 0 ) {
								$ical .= "ATTACH;FMTTYPE=".get_post_mime_type( $cover_image_id ).":".$event->image_url.$crlf;
							}
		
							$ical .= "END:VEVENT".$crlf;
							$ical .= "END:VCALENDAR";
		
							header('Content-type: application/force-download; charset=utf-8');
							header('Content-Disposition: attachment; filename="ep-event-'.$event->id.'.ics"');
		
							echo $ical;
							exit;
						}
					}
				}
			}
		}
	}

	/**
	 * Get userwise upcoming bookings
	 * 
	 * @param int $user_id User ID.
	 * 
	 * @return array Upcoming Bookings.
	 */
	public static function get_user_wise_upcoming_bookings( $user_id ) {
		$upcoming_bookings = array();
		if( ! empty( $user_id ) ) {
			$booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
			$upcoming_bookings = $booking_controller->get_user_upcoming_bookings( $user_id );
		}
		return $upcoming_bookings;
	}

	/**
	 * Get user all bookings
	 * 
	 * @param int $user_id User ID.
	 * 
	 * @return array All Bookings.
	 */
	public static function get_user_all_bookings( $user_id ) {
		$all_bookings = array();
		if( ! empty( $user_id ) ) {
			$booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
			$all_bookings = $booking_controller->get_user_all_bookings( $user_id );
		}
		return $all_bookings;
	}

	/**
	 * Get user's wishlist events
	 * 
	 * @param int $user_id User ID.
	 * 
	 * @return array All wishlisted events.
	 */
	public static function get_user_wishlisted_events( $user_id ) {
		$all_events = array();
		if( ! empty( $user_id ) ) {
			$wishlist_meta = get_user_meta( $user_id, 'ep_wishlist_event', true );
			if( ! empty( $wishlist_meta ) ) {
				foreach( $wishlist_meta as $event_id => $wishlist ) {
					if( ! empty( $event_id ) ) {
						$event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
						$event = $event_controller->get_single_event( $event_id );
						if( ! empty( $event ) && ! empty( $event->id ) ) {
							$booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
							$check_booking_id = $booking_controller->check_event_booking_by_user( $event_id, $user_id );
							$event_data = array( 'event' => $event, 'booking' => $check_booking_id );
							$all_events[] = $event_data;
						}
					}
				}
			}
		}
		return $all_events;
	}

	/**
	 * Get user submitted events
	 * 
	 * @param int $user_id User ID.
	 * 
	 * @return array All submitted events.
	 */
	public static function get_user_submitted_events( $user_id ) {
		$all_events = array();
		if( ! empty( $user_id ) ) {
			$args = array(
                'numberposts' => -1,
                'orderby'     => 'date',
                'order'       => 'DESC',
                'author' 	  => $user_id,
				'post_status' => 'any',
                'meta_query'  => array(
                    array(
                        'key'     => 'em_user_submitted', 
                        'value'   => 1, 
                        'compare' => '=', 
                        'type'    => 'NUMERIC,'
                    )
                ),
                'post_type'   => EM_EVENT_POST_TYPE
            );
            $events = get_posts( $args );
			if( ! empty( $events ) && count( $events ) > 0 ) {
				$event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
				foreach( $events as $event ) {
					$event_data = $event_controller->get_single_event( $event->ID );
					$all_events[] = $event_data;
				}
			}
		}
		return $all_events;
	}

	/**
	 * Render event booking button html
	 * 
	 * @param object $event Event Data.
	 * 
	 * @return string
	 */
	public static function render_event_booking_btn( $event ) {
		$btn_html = '';
		if( ! empty( $event ) ) {
			// check if event expired
			if( check_event_has_expired( $event ) ) {
				// means event has ended. So user can only view the event detail.
				$btn_html .= '<a href="'.esc_url( $event->event_url ).'" target="_blank">';
					$btn_html .= '<div class="ep-btn ep-btn-dark ep-box-w-100 ep-my-0 ep-py-2">';
						$btn_html .= '<span class="ep-fw-bold ep-text-small">';
							$btn_html .= esc_html__( 'View Details ', 'eventprime-event-calendar-management' );
						$btn_html .= '</span>';
					$btn_html .= '</div>';
				$btn_html .= '</a>';
			} else{
				$event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
				if( ! empty( $event->em_enable_booking ) ) {
					if( $event->em_enable_booking == 'bookings_off' ) {
						$btn_html .= '<a href="'.esc_url( $event->event_url ).'" target="_blank">';
							$btn_html .= '<div class="ep-btn ep-btn-dark ep-box-w-100 ep-my-0 ep-py-2">';
								$btn_html .= '<span class="ep-fw-bold ep-text-small">';
									$btn_html .= esc_html__( 'View Details ', 'eventprime-event-calendar-management' );
								$btn_html .= '</span>';
							$btn_html .= '</div>';
						$btn_html .= '</a>';
					} elseif( $event->em_enable_booking == 'external_bookings' ) {
						$url = $event->em_custom_link;
						if( empty( $url ) ) {
							$url = $event->event_url;
						}
						$new_window = '';
						if( $event->em_custom_link_new_browser == 1 ) {
							$new_window = 'target="_blank"';
						}
						$btn_html .= '<a href="'.esc_url( $url ).'" '. esc_attr( $new_window ) .'>';
							$btn_html .= '<div class="ep-btn ep-btn-dark ep-box-w-100 ep-my-0 ep-py-2">';
								$btn_html .= '<span class="ep-fw-bold ep-text-small">';
									$btn_html .= esc_html__( 'View Details ', 'eventprime-event-calendar-management' );
								$btn_html .= '</span>';
							$btn_html .= '</div>';
						$btn_html .= '</a>';
					} else{
						// check for booking status 
						if( ! empty( $event->all_tickets_data ) ) {
							$check_for_booking_status = $event_controller->check_for_booking_status( $event->all_tickets_data, $event );
							if( ! empty( $check_for_booking_status ) ) {
								if( $check_for_booking_status['status'] == 'not_started' ) {
									$btn_html .= '<div class="ep-btn ep-btn-light ep-box-w-100 ep-my-1 ep-p-2">';
										$btn_html .= '<span class="material-icons-outlined ep-align-middle ep-text-muted ep-fs-6">history_toggle_off</span> ';
										$btn_html .= '<span class="ep-text-muted ep-text-small"><em>';
											$btn_html .= $check_for_booking_status['message'];
										$btn_html .= '</em></span>';
									$btn_html .= '</div>';
								} elseif( $check_for_booking_status['status'] == 'off' ) {
									$btn_html .= '<div class="ep-btn ep-btn-light ep-box-w-100 ep-my-1 ep-p-2">';
										$btn_html .= '<span class="material-icons-outlined ep-align-middle ep-text-muted ep-fs-6">block</span> ';
										$btn_html .= '<span class="ep-text-muted ep-text-small"><em>';
											$btn_html .= $check_for_booking_status['message'];
										$btn_html .= '</em></span>';
									$btn_html .= '</div>';
								} else{
									$btn_html .= '<a href="'.esc_url( $event->event_url ).'" target="_blank">';
										if( $check_for_booking_status['message'] == 'Free' ) {
											$btn_html .= '<div class="ep-btn ep-btn-dark ep-box-w-100 ep-my-0 ep-p-2">';
										} else{
											$btn_html .= '<div class="ep-btn ep-btn-warning ep-box-w-100 ep-my-10 ep-p-2">';
										}
											$btn_html .= '<span class="ep-fw-bold ep-text-small">';
												$btn_html .= esc_html__( $check_for_booking_status['message'], 'eventprime-event-calendar-management' );
											$btn_html .= '</span>';
										$btn_html .= '</div>';
									$btn_html .= '</a>';
								}
							}
						}
					}
				}
			}
		}

		return $btn_html;
	}

	/**
	 * check if ticket available for booking
	 * 
	 * @param object $ticket Ticket Data.
	 * 
	 * @param object $event Event Data.
	 * 
	 * @return string
	 */
	public static function check_for_ticket_available_for_booking( $ticket, $event ) {
		$booking_status = array();
		if( ! empty( $ticket ) && ! empty( $event ) ) {
			$event_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
			$booking_status = $event_controller->check_for_ticket_available_for_booking( $ticket, $event );
		}
		return $booking_status;
	}

	/**
	 * Check if ticket visible or not
	 * 
	 * @param object $ticket Ticket Data.
	 * 
	 * @param object $event Event Data.
	 * 
	 * @return bool
	 */
	public static function check_for_ticket_visibility( $ticket, $event ) {
		$response = array( 'status' => false, 'message' => '', 'reason' => '' );
		if( ! empty( $ticket ) && ! empty( $event ) ) {
			if( ! empty( $ticket->visibility ) ) {
				$visibility = json_decode( $ticket->visibility );
				$em_tickets_user_visibility = $em_ticket_for_invalid_user = $em_tickets_visibility_time_restrictions = $em_ticket_visibility_user_roles = '';
				$em_tickets_user_visibility = $visibility->em_tickets_user_visibility;
				$em_ticket_for_invalid_user = $visibility->em_ticket_for_invalid_user;
				//$em_tickets_visibility_time_restrictions = $visibility->em_tickets_visibility_time_restrictions;
				$em_tickets_visibility_time_restrictions = 'always_visible';
				// if time is always visible
				if( $em_tickets_visibility_time_restrictions == 'always_visible' ) {
					if( $em_tickets_user_visibility == 'public' ) {
						$response['status'] = true;
					} elseif( $em_tickets_user_visibility == 'all_login' ) {
						if( is_user_logged_in() ) {
							$response['status'] = true;
						} else{
							if( $em_ticket_for_invalid_user == 'disabled' ) {
								$response = array( 'status' => true, 'message' => 'disabled', 'reason' => 'user_login' );
							} else{
								$response = array( 'status' => false, 'message' => 'require_login', 'reason' => '' );
							}
						}
					} elseif( $em_tickets_user_visibility == 'user_roles' ) {
						if( is_user_logged_in() ) {
							if( isset( $visibility->em_ticket_visibility_user_roles ) && ! empty( $visibility->em_ticket_visibility_user_roles ) ) {
								$em_ticket_visibility_user_roles = $visibility->em_ticket_visibility_user_roles;
								$user = wp_get_current_user();
								$roles = ( array ) $user->roles;
								if( in_array( 'administrator', $roles ) ) {
									$response['status'] = true;
								} else{
									if( ! empty( $em_ticket_visibility_user_roles ) ) {
										$found_role = 0;
										foreach( $em_ticket_visibility_user_roles as $ur ) {
											if( in_array( $ur, $roles ) ) {
												$response['status'] = true;
												$found_role = 1;
												break;
											}
										}
										if( empty( $found_role ) ) {
											$response = array( 'status' => false, 'message' => 'role_not_found', 'reason' => '' );
										} else{
											$response['status'] = true;
										}
									} else{
										$response['status'] = true;
									}
								}
							} else{
								$response['status'] = true;
							}
						} else{
							if( $em_ticket_for_invalid_user == 'disabled' ) {
								$response = array( 'status' => true, 'message' => 'disabled', 'reason' => 'user_role' );
							}
							$response = array( 'status' => false, 'message' => 'require_login', 'reason' => '' );
						}
					}
				}
			} else{
				$response['status'] = true;
			}
		}
		return $response;
	}
	
	/**
	 * Get all event bookings
	 * 
	 * @param int $event_id Event ID.
	 * 
	 * @param bool $ticket_qty Load ticket quantities with bookings.
	 * 
	 * @return array All bookings.
	 */
	public static function get_event_booking_by_event_id( $event_id, $ticket_qty = false ) {
		$bookings = array();
		if( ! empty( $event_id ) ) {
			$booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
			$all_bookings = $booking_controller->get_event_bookings_by_event_id( $event_id );
			if( ! empty( $all_bookings ) ) {
				foreach( $all_bookings as $booking ) {
					$booking_data = $booking_controller->load_booking_detail( $booking->ID, false );
					if( ! empty( $booking_data ) ) {
						$bookings['bookings'][] = $booking_data;
					}
				}
			}
			// if need ticket quantities then calculate booked quantity for each ticket.
			if( ! empty( $ticket_qty ) ) {
				$tickets = array();
				if( ! empty( $bookings['bookings'] ) && count( $bookings['bookings'] ) > 0 ) {
					foreach( $bookings['bookings'] as $booking ) {
						if ( isset( $booking->em_status ) && $booking->em_status !== 'cancelled' ) {
							if( isset( $booking->em_order_info ) ) {
								if( isset( $booking->em_order_info['tickets'] ) && ! empty( $booking->em_order_info['tickets'] ) ) {
									$booked_tickets = $booking->em_order_info['tickets'];
									foreach( $booked_tickets as $ticket ) {
										if( ! empty( $ticket->id ) && ! empty( $ticket->qty ) ) {
											if( isset( $tickets[$ticket->id] ) ) {
												$old_qty = $tickets[ $ticket->id ];
												$old_qty += $ticket->qty;
												$tickets[ $ticket->id ] = $old_qty;
											} else{
												$tickets[ $ticket->id ] = $ticket->qty;
											}
										}
									}
								} else if( isset( $booking->em_order_info['order_item_data'] ) && ! empty( $booking->em_order_info['order_item_data'] ) ) {
									$booked_tickets = $booking->em_order_info['order_item_data'];
									foreach( $booked_tickets as $ticket ) {
										if( ! empty( $ticket->id ) && ! empty( $ticket->qty ) ) {
											if( isset( $tickets[$ticket->id] ) ) {
												$old_qty = $tickets[ $ticket->id ];
												$old_qty += $ticket->qty;
												$tickets[ $ticket->id ] = $old_qty;
											} else{
												$tickets[ $ticket->id ] = $ticket->qty;
											}
										} else if( ! empty( $ticket->variation_id ) ) {
											if( isset( $tickets[$ticket->variation_id] ) ) {
												$old_qty = $tickets[ $ticket->variation_id ];
												$old_qty += $ticket->quantity;
												$tickets[ $ticket->variation_id ] = $old_qty;
											} else{
												$tickets[ $ticket->variation_id ] = $ticket->quantity;
											}
										}
									}
								}
							}
						}
					}
				}
				$bookings['tickets'] = $tickets;
			}
		}
		return $bookings;
	}

	/**
	 * Check if offer is eligibe for apply
	 * 
	 * @param object $offer Offer Data.
	 * 
	 * @param object $ticket Ticket Data.
	 * 
	 * @param int $event_id Event ID.
	 * 
	 * @return bool
	 */
	public static function check_event_offer_applied( $offer, $ticket, $event_id,$qty =0 ) {
		$applied = 0;
		if( ! empty( $offer ) ) {
			$current_time = ep_get_current_timestamp();
			$min_date = $max_date = $current_time;
			$event_start_date = get_post_meta( $event_id, 'em_start_date', true );
			$event_start_time = get_post_meta( $event_id, 'em_start_time', true );
			$event_end_date = get_post_meta( $event_id, 'em_end_date', true );
			$event_end_time = get_post_meta( $event_id, 'em_end_time', true );
			$event_add_more_dates = get_post_meta( $event_id, 'em_event_add_more_dates', true );
			// offer start date
			$offer_start_booking_type = $offer->em_offer_start_booking_type;
			if( $offer_start_booking_type == 'custom_date' ) {
				if( isset( $offer->em_offer_start_booking_date ) && ! empty( $offer->em_offer_start_booking_date ) ) {
					$offer_start_date = $offer->em_offer_start_booking_date;
					if( isset( $offer->em_offer_start_booking_time ) && ! empty( $offer->em_offer_start_booking_time ) ) {
						$offer_start_date .= ' ' . $offer->em_offer_start_booking_time;
						$min_date = ep_datetime_to_timestamp( $offer_start_date );
					} else{
						$min_date = ep_date_to_timestamp( $offer_start_date );
					}
				}
			} elseif( $offer_start_booking_type == 'relative_date' ) {
				$days         = ( ! empty( $offer->em_offer_start_booking_days ) ? $offer->em_offer_start_booking_days : 1 );
				$days_option  = ( ! empty( $offer->em_offer_start_booking_days_option ) ? $offer->em_offer_start_booking_days_option : 'before' );
				$event_option = ( ! empty( $offer->em_offer_start_booking_event_option ) ? $offer->em_offer_start_booking_event_option : 'event_start' );
				$days_string  = ' days';
				if( $days == 1 ) {
					$days_string = ' day';
				}
				// + or - days
				$days_icon = '- ';
				if( $days_option == 'after' ) {
					$days_icon = '+ ';
				}
				if( $event_option == 'event_start' ) {
					$book_end_timestamp = $event_start_date;
					if( ! empty( $event_start_time ) ) {
						$book_end_timestamp = ep_timestamp_to_date( $event_start_date );
						$book_end_timestamp .= ' ' . $event_start_time;
						$book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
					}
					$min_date = strtotime( $days_icon . $days . $days_string, $book_end_timestamp );
				} elseif( $event_option == 'event_ends' ) {
					$book_end_timestamp = $event_end_date;
					if( ! empty( $event_end_time ) ) {
						$book_end_timestamp = ep_timestamp_to_date( $event_end_date );
						$book_end_timestamp .= ' ' . $event_end_time;
						$book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
					}
					$min_date = strtotime( $days_icon . $days . $days_string, $book_end_timestamp );
				} else{
					if( ! empty( $event_option ) ) {
						$em_event_add_more_dates = $event_add_more_dates;
						if( ! empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
							foreach( $em_event_add_more_dates as $more_dates ) {
								if( $more_dates['uid'] == $event_option ) {
									$min_date = $more_dates['date'];
									if( ! empty( $more_dates['time'] ) ) {
										$date_more = ep_timestamp_to_date( $more_dates['date'] );
										$date_more .= ' ' . $more_dates['time'];
										$min_date = ep_datetime_to_timestamp( $date_more );
									}
									break;
								}
							}
						}
						$min_date = strtotime( $days_icon . $days . $days_string, $min_date );
					}
				}
			} else{
				$em_offer_start_booking_event_option = $offer->em_offer_start_booking_event_option;
				if( $em_offer_start_booking_event_option == 'event_start' ) {
					$book_end_timestamp = $event_start_date;
					if( ! empty( $event_start_time ) ) {
						$book_end_timestamp = ep_timestamp_to_date( $event_start_date );
						$book_end_timestamp .= ' ' . $event_start_time;
						$book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
					}
					$min_date = $book_end_timestamp;
				} elseif( $em_offer_start_booking_event_option == 'event_ends' ) {
					$book_end_timestamp = $event_end_date;
					if( ! empty( $event_end_time ) ) {
						$book_end_timestamp = ep_timestamp_to_date( $event_end_date );
						$book_end_timestamp .= ' ' . $event_end_time;
						$book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
					}
					$min_date = $book_end_timestamp;
				} else{
					if( ! empty( $event_option ) ) {
						$em_event_add_more_dates = $event_add_more_dates;
						if( ! empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
							foreach( $em_event_add_more_dates as $more_dates ) {
								if( $more_dates['uid'] == $event_option ) {
									$min_date = $more_dates['date'];
									if( ! empty( $more_dates['time'] ) ) {
										$date_more = ep_timestamp_to_date( $more_dates['date'] );
										$date_more .= ' ' . $more_dates['time'];
										$min_date = ep_datetime_to_timestamp( $date_more );
									}
									break;
								}
							}
						}
					}
				}
			}

			// offer end date
			$offer_ends_booking_type = $offer->em_offer_ends_booking_type;
			if( $offer_ends_booking_type == 'custom_date' ) {
				if( isset( $offer->em_offer_ends_booking_date ) && ! empty( $offer->em_offer_ends_booking_date ) ) {
					$offer_end_date = $offer->em_offer_ends_booking_date;
					if( isset( $offer->em_offer_ends_booking_time ) && ! empty( $offer->em_offer_ends_booking_time ) ) {
						$offer_end_date .= ' ' . $offer->em_offer_ends_booking_time;
						$max_date = ep_datetime_to_timestamp( $offer_end_date );
					} else{
						$max_date = ep_date_to_timestamp( $offer_end_date );
					}
				}
			} elseif( $offer_ends_booking_type == 'relative_date' ) {
				$days         = ( ! empty( $offer->em_offer_ends_booking_days ) ? $offer->em_offer_ends_booking_days : 1 );
				$days_option  = ( ! empty( $offer->em_offer_ends_booking_days_option ) ? $offer->em_offer_ends_booking_days_option : 'before' );
				$event_option = ( ! empty( $offer->em_offer_ends_booking_event_option ) ? $offer->em_offer_ends_booking_event_option : 'event_ends' );
				$days_string  = ' days';
				if( $days == 1 ) {
					$days_string = ' day';
				}
				// + or - days
				$days_icon = '- ';
				if( $days_option == 'after' ) {
					$days_icon = '+ ';
				}
				if( $event_option == 'event_start' ) {
					$book_end_timestamp = $event_start_date;
					if( ! empty( $event_start_time ) ) {
						$book_end_timestamp = ep_timestamp_to_date( $event_start_date );
						$book_end_timestamp .= ' ' . $event_start_time;
						$book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
					}
					$max_date = strtotime( $days_icon . $days . $days_string, $book_end_timestamp );
				} elseif( $event_option == 'event_ends' ) {
					$book_end_timestamp = $event_end_date;
					if( ! empty( $event_end_time ) ) {
						$book_end_timestamp = ep_timestamp_to_date( $event_end_date );
						$book_end_timestamp .= ' ' . $event_end_time;
						$book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
					}
					$max_date = strtotime( $days_icon . $days . $days_string, $book_end_timestamp );
				} else{
					if( ! empty( $event_option ) ) {
						$em_event_add_more_dates = $event_add_more_dates;
						if( ! empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
							foreach( $em_event_add_more_dates as $more_dates ) {
								if( $more_dates['uid'] == $event_option ) {
									$max_date = $more_dates['date'];
									if( ! empty( $more_dates['time'] ) ) {
										$date_more = ep_timestamp_to_date( $more_dates['date'] );
										$date_more .= ' ' . $more_dates['time'];
										$max_date = ep_datetime_to_timestamp( $date_more );
									}
									break;
								}
							}
						}
						$max_date = strtotime( $days_icon . $days . $days_string, $max_date );
					}
				}
			} else{
				$em_offer_ends_booking_event_option = $offer->em_offer_ends_booking_event_option;
				if( $em_offer_ends_booking_event_option == 'event_start' ) {
					$book_end_timestamp = $event_start_date;
					if( ! empty( $event_start_time ) ) {
						$book_end_timestamp = ep_timestamp_to_date( $event_start_date );
						$book_end_timestamp .= ' ' . $event_start_time;
						$book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
					}
					$max_date = $book_end_timestamp;
				} elseif( $em_offer_ends_booking_event_option == 'event_ends' ) {
					$book_end_timestamp = $event_end_date;
					if( ! empty( $event_end_time ) ) {
						$book_end_timestamp = ep_timestamp_to_date( $event_end_date );
						$book_end_timestamp .= ' ' . $event_end_time;
						$book_end_timestamp = ep_datetime_to_timestamp( $book_end_timestamp );
					}
					$max_date = $book_end_timestamp;
				} else{
					if( ! empty( $event_option ) ) {
						$em_event_add_more_dates = $event_add_more_dates;
						if( ! empty( $em_event_add_more_dates ) && count( $em_event_add_more_dates ) > 0 ) {
							foreach( $em_event_add_more_dates as $more_dates ) {
								if( $more_dates['uid'] == $event_option ) {
									$max_date = $more_dates['date'];
									if( ! empty( $more_dates['time'] ) ) {
										$date_more = ep_timestamp_to_date( $more_dates['date'] );
										$date_more .= ' ' . $more_dates['time'];
										$max_date = ep_datetime_to_timestamp( $date_more );
									}
									break;
								}
							}
						}
					}
				}
			}

			// check for offer date time condition
			if( $current_time >= $min_date && $current_time <= $max_date ) {
				// now check for offer types
				if( ! empty( $offer->em_ticket_offer_type ) ) {
					$em_ticket_offer_type = $offer->em_ticket_offer_type;
					if( $em_ticket_offer_type == 'seat_based' ) {
						if( ! empty( $offer->em_ticket_offer_seat_option ) ) {
							$seat_option = $offer->em_ticket_offer_seat_option;
							if( ! empty( $offer->em_ticket_offer_seat_number ) ) {
								$seat_number = $offer->em_ticket_offer_seat_number;
								$event_ticket_booking_count = self::get_event_booking_by_ticket_id( $event_id, $ticket->id );
								if( $seat_option == 'first' ) {
									if( $event_ticket_booking_count < $seat_number ) {
										$offer->em_remaining_ticket_to_offer = $seat_number - $event_ticket_booking_count;
										return $offer;
									}
								} else {
									$ticket_caps = $ticket->capacity;
									$unbooked_tickets = $ticket_caps - $event_ticket_booking_count;
									if( ! empty( $unbooked_tickets ) && $unbooked_tickets <= $seat_number ) {
										$offer->em_remaining_ticket_to_offer = $unbooked_tickets;
										return $offer;
									} 
								}
							}
						}
					} else if( $em_ticket_offer_type == 'role_based' ) {
						if( isset( $offer->em_ticket_offer_user_roles ) && ! empty( $offer->em_ticket_offer_user_roles ) ) {
							$em_ticket_offer_user_roles = $offer->em_ticket_offer_user_roles;
							$user = wp_get_current_user();
							$roles = ( array ) $user->roles;
							if( ! empty( $em_ticket_offer_user_roles ) ) {
								$found_role = 0;
								foreach( $em_ticket_offer_user_roles as $ur ) {
									if( in_array( $ur, $roles ) ) {
										$found_role = 1;
										break;
									}
								}
								if( ! empty( $found_role ) ) {
									$applied = 1;
									return $applied;
								}
							}
						}
					}
                                        else if( $em_ticket_offer_type == 'volume_based' && $qty!=0 ) {
                                            if( ! empty( $offer->em_ticket_offer_volumn_count ) ) 
                                            {
                                                $volume = $offer->em_ticket_offer_volumn_count;
                                                if($qty >= $volume)
                                                {
                                                    $applied = 1;
                                                    return $applied;
                                                }
                                                
                                            }
                                            
                                        }
				}
			}
		}
		return $applied;
	}

	/**
	 * Get event offers applied data
	 * 
	 * @param array $offers Event Offers.
	 * 
	 * @param object $ticket Ticket Data.
	 * 
	 * @param int $event_id Event ID.
	 * 
	 * @return array
	 */
	public static function get_event_offer_applied_data( $offers, $ticket, $event_id, $qty=0 ) {
		$offer_data = array();
		if( ! empty( $offers ) ) {
			$i = 1;
			foreach( $offers as $offer ) {
				$applied_status = self::check_event_offer_applied( $offer, $ticket, $event_id, $qty );
				if( ! empty( $applied_status ) ) {
					if( is_object( $applied_status ) ) { //offer data updated from method
						$offer_data[$offer->uid] = $applied_status;
					} else{
						$offer_data[$offer->uid] = $offer;
					}
					// check for multiple offer handle condition
					if( ! empty( $ticket->multiple_offers_option ) ) {
						if( $ticket->multiple_offers_option == 'first_offer' ) {
							if( count( $offer_data ) > 0 ) {
								break;
							}
						}
					}
				}
				$i++;
			}
		}
		return $offer_data;
	}

	/**
	 * Get event available offers
	 * 
	 * @param object $event Event Data
	 * 
	 * @return int available offers
	 */
	public static function get_event_available_offers( $event ) {
		$available_offers = 0;
		if( ! empty( $event ) && ! empty( $event->all_tickets_data ) ) {
			foreach( $event->all_tickets_data as $ticket ) {
				if( ! empty( $ticket->offers ) ) {
					$ticket_offers_data = json_decode( $ticket->offers );
					if( ! empty( $ticket_offers_data ) ) {
						foreach( $ticket_offers_data as $to ) {
							if( isset( $to->em_ticket_show_offer_detail ) && ! empty( $to->em_ticket_show_offer_detail ) ) {
								$available_offers++;
							}
						}
					}
				}
			}
		}
		return $available_offers;
	}

	/**
	 * Get event available tickets
	 * 
	 * @param object $event Event Data
	 * 
	 * @return int available tickets
	 */
	public static function get_event_available_tickets( $event ) {
		$available_tickets = 0;
		if( ! empty( $event ) && ! check_event_has_expired( $event ) && ! empty( $event->all_tickets_data ) ) {
			$all_event_bookings = self::get_event_booking_by_event_id( $event->id, true );
			$booked_tickets_data = $all_event_bookings['tickets'];
			foreach( $event->all_tickets_data as $ticket ) {
				$check_ticket_visibility = self::check_for_ticket_visibility( $ticket, $event );
				if( ! empty( $check_ticket_visibility['status'] ) ) {
					$check_ticket_available = EventM_Factory_Service::check_for_ticket_available_for_booking( $ticket, $event );
					if( empty( $check_ticket_available['expire'] ) ) {
						$remaining_caps = $ticket->capacity;
						if( ! empty( $booked_tickets_data ) ) {
							if( isset( $booked_tickets_data[$ticket->id] ) && ! empty( $booked_tickets_data[$ticket->id] ) ) {
								$booked_ticket_qty = absint( $booked_tickets_data[$ticket->id] );
								if( $booked_ticket_qty > 0 ) {
									$remaining_caps = $ticket->capacity - $booked_ticket_qty;
								}
							}
						}
						$available_tickets += $remaining_caps;
					}
				}
			}
		}
		return $available_tickets;
	}

	/**
	 * Get ticket booking event date options for fes.
	 * 
	 * @return array Event Date Options.
	 */
	public static function get_fes_ticket_booking_event_date_options() {
		$event_date_options = array();
		$event_date_options['event_start'] = esc_html__( 'Event Start', 'eventprime-event-calendar-management');
        $event_date_options['event_ends']  = esc_html__( 'Event Ends', 'eventprime-event-calendar-management');
		return $event_date_options;
	}

	/**
	 * Get ticket by id.
	 * 
	 * @param int $ticket_id Ticket Id.
	 * 
	 * @return object Ticket Data.
	 */
	public static function get_event_ticket_by_id( $ticket_id ) {
		$ticket_data = new stdClass();
		if( ! empty( $ticket_data ) ) {
			global $wpdb;
			$ticket_table_name = $wpdb->prefix.'em_price_options';
			$ticket_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $ticket_table_name WHERE `id` = %d", $ticket_id ) );
		}
		return $ticket_data;
	}

	/**
	 * Get Booking QR code image url.
	 * 
	 * @param object $event Booking.
	 * 
	 * @return string QR code image url.
	 */
	public static function get_booking_qr_code( $booking ) {
		$image_url = '';
		if( ! empty( $booking ) ) {
			$url = get_permalink( ep_get_global_settings( 'booking_details_page' ) );
			$url = add_query_arg( 'order_id', $booking->em_id, $url );
			$file_name = 'ep_qr_'.md5($url).'.png';
			$upload_dir = wp_upload_dir();
			$file_path = $upload_dir['basedir'] . '/ep/' . $file_name;
			if( ! file_exists( $file_path ) ) {
				if( ! file_exists( dirname( $file_path ) ) ){
					mkdir( dirname( $file_path ), 0755 );
				}
				require_once EP_BASE_DIR . 'includes/lib/qrcode.php';
				$qrCode = new QRcode();
				$qrCode->png( $url, $file_path, 'M', 4, 2 );
			}
			$image_url = esc_url( $upload_dir['baseurl'].'/ep/'.$file_name );
		}
		return $image_url;
	}

	/**
	 * Get event available capacity
	 * 
	 * @param object $event Event Data
	 * 
	 * @return bool
	 */
	public static function ep_is_event_sold_out( $event ) {
		$total_caps = $total_bookings = 0;
		if( ! empty( $event ) && ! check_event_has_expired( $event ) && ! empty( $event->all_tickets_data ) ) {
			$all_event_bookings = self::get_event_booking_by_event_id( $event->id, true );
			$booked_tickets_data = $all_event_bookings['tickets'];
			foreach( $event->all_tickets_data as $ticket ) {
				// ticket total capacity
                $total_caps += $ticket->capacity;
				// ticket booked capacity
                $total_bookings += ( ! empty( $booked_tickets_data[$ticket->id] ) ? $booked_tickets_data[$ticket->id] : 0 );
			}
		}
		if( $total_caps > $total_bookings ) {
			return false;
		} else{
			return true;
		}
	}

	/**
	 * Get all checkout fields.
	 * 
	 * @return array
	 */
	public static function get_all_checkout_fields() {
		global $wpdb;
		$checkout_table_name = $wpdb->prefix.'eventprime_checkout_fields';
		$get_field_data = $wpdb->get_results( "SELECT * FROM $checkout_table_name", OBJECT_K );
		return $get_field_data;
	}

	/**
	 * Get checkout field data by id
	 * 
	 * @param int $id Checkout Field Id.
	 * 
	 * @return object
	 */
    public static function get_checkout_field_by_id( $id ) {
        global $wpdb;
        $table_name = $wpdb->prefix.'eventprime_checkout_fields';
		$get_field_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE `id` = %d", $id ) );
        return $get_field_data;
    }

	/**
	 * Get total booking number by event id
	 * 
	 * @param int $event_id Event ID
	 * 
	 * @return int Booking number
	 */
	public static function get_total_booking_number_by_event_id( $event_id ) {
		$total_booking = 0;
		if( ! empty( $event_id ) ) {
			$booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
			$all_bookings = $booking_controller->get_event_bookings_by_event_id( $event_id );
			if( ! empty( $all_bookings ) ) {
				foreach( $all_bookings as $booking ) {
					$booking_data = $booking_controller->load_booking_detail( $booking->ID, false );
					if( ! empty( $booking_data ) ) {
						if( isset( $booking_data->em_order_info['tickets'] ) && ! empty( $booking_data->em_order_info['tickets'] ) ) {
							$booked_tickets = $booking_data->em_order_info['tickets'];
							foreach( $booked_tickets as $ticket ) {
								if( ! empty( $ticket->id ) && ! empty( $ticket->qty ) ) {
									$total_booking += $ticket->qty;
								}
							}
						} else if( isset( $booking_data->em_order_info['order_item_data'] ) && ! empty( $booking_data->em_order_info['order_item_data'] ) ) {
							$booked_tickets = $booking_data->em_order_info['order_item_data'];
							foreach( $booked_tickets as $ticket ) {
								if( isset( $ticket->quantity ) ) {
									$total_booking += $ticket->quantity;
								} else if( isset( $ticket->qty ) ) {
									$total_booking += $ticket->qty;
								}
							}
						}
					}
				}
			}
		}
		return $total_booking;
	}
        
	public static function ep_form_field_generator( $fields, $required = 0 ){
		$core_field_types = array_keys( ep_get_core_checkout_fields() );
		$input_name = ep_get_slug_from_string( $fields->label );
		if ( in_array( $fields->type, $core_field_types ) ) {?>
			<div class="ep-mb-3">
				<label for="name" class="form-label ep-text-small"><?php
					echo esc_html( $fields->label );
					if ($required) {?>
						<span class="ep-form-fields-required">
							<?php echo esc_html('*'); ?>
						</span><?php 
					}?>
				</label>
				<input name="ep_form_fields[<?php echo esc_attr($fields->id); ?>][label]" type="hidden" value="<?php echo esc_attr($fields->label); ?>">
				<input name="ep_form_fields[<?php echo esc_attr($fields->id); ?>][<?php echo esc_attr($input_name); ?>]" 
					type="<?php echo esc_attr($fields->type); ?>" 
					class="ep-form-control" 
					id="ep_form_fields_<?php echo esc_attr($fields->id); ?>_<?php echo esc_attr($input_name); ?>" 
					placeholder="<?php echo esc_attr($fields->label); ?>"
					<?php
					if ($required) {
						echo 'required="required"';
					}?>
				>
				<div class="ep-error-message" id="ep_form_fields_<?php echo esc_attr($fields->id); ?>_<?php echo esc_attr($input_name); ?>_error"></div>
			</div><?php
		}else{
			if( class_exists('EP_Advanced_Checkout_Fields' ) ){
				$adv_acf_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Advanced_Checkout_Fields_Controller' );
				$adv_acf_controller->ep_advcance_form_field_generator($fields, $required); 
			}
		}
	}

	/**
	 * Get total attendee number by booking id
	 * 
	 * @param int $booking_id Booking ID
	 * 
	 * @return int Attendee number
	 */
	public static function get_total_attendee_number_by_booking_id( $booking_id ) {
		$total_attendee = 0;
		if( ! empty( $booking_id ) ) {
			$booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
			$booking_data = $booking_controller->load_booking_detail( $booking_id, false );
			if( ! empty( $booking_data ) ) {
				if( isset( $booking_data->em_order_info['tickets'] ) && ! empty( $booking_data->em_order_info['tickets'] ) ) {
					$booked_tickets = $booking_data->em_order_info['tickets'];
					foreach( $booked_tickets as $ticket ) {
						if( ! empty( $ticket->id ) && ! empty( $ticket->qty ) ) {
							$total_attendee += $ticket->qty;
						}
					}
				} else if( isset( $booking_data->em_order_info['order_item_data'] ) && ! empty( $booking_data->em_order_info['order_item_data'] ) ) {
					$booked_tickets = $booking_data->em_order_info['order_item_data'];
					foreach( $booked_tickets as $ticket ) {
						if( isset( $ticket->quantity ) ) {
							$total_attendee += $ticket->quantity;
						} else if( isset( $ticket->qty ) ) {
							$total_attendee += $ticket->qty;
						}
					}
				}
			}
		}
		return $total_attendee;
	}

	/**
	 * Get checkout field label by id
	 * 
	 * @param int $id Checkout Field Id.
	 * 
	 * @return object
	 */
    public static function get_checkout_field_label_by_id( $id ) {
        global $wpdb;
        $table_name = $wpdb->prefix.'eventprime_checkout_fields';
		$get_field_label = $wpdb->get_row( $wpdb->prepare( "SELECT `label` FROM $table_name WHERE `id` = %d", $id ) );
        return $get_field_label;
    }

	/**
	 * Get ticket name by id
	 * 
	 * @param int $id Ticket Field Id.
	 * 
	 * @return object
	 */
    public static function get_ticket_name_by_id( $id ) {
        global $wpdb;
        $table_name = $wpdb->prefix.'em_price_options';
		$name = '';
		$get_field_data = $wpdb->get_row( $wpdb->prepare( "SELECT `name` FROM $table_name WHERE `id` = %d", $id ) );
		if( ! empty( $get_field_data ) ) {
			$name = $get_field_data->name;
		}
        return $name;
    }
	
	/* Get event views
	 * 
	 * @return array
	 */
	public static function get_image_visibility_options() {
		$image_visibility_views = array(
			'none'     	=> 'None',
			'fill' 		=> 'Fill',
			'contain' 	=> 'Contain',
			'cover' 	=> 'Cover'
		);
		return $image_visibility_views;
	}

	/**
	 * Get event booking fields.
	 * 
	 * @param object $event Event.
	 * 
	 * @return array
	 */
	public static function get_event_checkout_booking_fields( $event ) {
		global $wpdb;
		$booking_fields = array();
		if( ! empty( $event->em_event_checkout_booking_fields ) ) {
			$checkout_table_name = $wpdb->prefix.'eventprime_checkout_fields';
			$booking_fields = $event->em_event_checkout_booking_fields;
			if( ! empty( $booking_fields ) && ! empty( $booking_fields['em_event_booking_fields_data'] ) && count( $booking_fields['em_event_booking_fields_data'] ) > 0 ) {
				$booking_fields_data = array();
				foreach( $booking_fields['em_event_booking_fields_data'] as $fields ) {
					$get_field_data = $wpdb->get_row( $wpdb->prepare( "SELECT `id`, `type`, `label` FROM $checkout_table_name WHERE `id` = %d", $fields ) );
					if( ! empty( $get_field_data ) ) {
						$booking_fields_data[] = $get_field_data;
					}
				}
				$booking_fields['em_event_booking_fields_data'] = $booking_fields_data;
			}
		}
		return $booking_fields;
	}

	/**
	 * Get event booking by ticket id
	 * 
	 * @param $event_id Event ID.
	 * 
	 * @param $ticket_id Ticket ID.
	 * 
	 * @return int
	 */
	public static function get_event_booking_by_ticket_id( $event_id, $ticket_id ) {
		$ticket_booking_count = 0;
		$booking_controller = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
		$all_bookings = $booking_controller->get_event_bookings_by_event_id( $event_id );
		if( ! empty( $all_bookings ) ) {
			foreach( $all_bookings as $booking ) {
				$order_info = get_post_meta( $booking->ID, 'em_order_info', true );
				if( ! empty( $order_info ) ) {
					$tickets = $order_info['tickets'];
					if( ! empty( $tickets  ) ) {
						foreach( $tickets as $ticket ) {
							if( $ticket->id == $ticket_id ) {
								$ticket_booking_count += $ticket->qty;
							}
						}
					}
				}
			}
		}
		return $ticket_booking_count;
	}

}