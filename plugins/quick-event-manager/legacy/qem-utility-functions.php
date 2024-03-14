<?php
// Manages the categories

function qem_category_key( $cal, $style, $calendar ) {
	$cat      = array( 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j' );
	$arr      = get_categories();
	$display  = event_get_stored_display();
	$pageurl  = qem_current_page_url();
	$parts    = explode( "&", $pageurl );
	$pageurl  = $parts['0'];
	$link     = ( strpos( $pageurl, '?' ) ? '&' : '?' );
	$uncatkey = $caturl = $allcats = '';
	$catkey   = '';
	if ( $style['linktocategories'] ) {
		$catkey = '<style>.qem-category:hover {background: #CCC !important;border-color: #343848 !important;}.qem-category a:hover {color:#383848 !important;}</style>' . "\r\n";
	}

	if ( qem_get_element( $cal, 'keycaption', false ) ) {
		$catkey .= ( $calendar ? '<p><span class="qem-caption">' . $cal['keycaption'] .
		                         '</span>' : '<p><span class="qem-caption">' . $display['keycaption'] . '</span>' );
	}

	if ( $calendar && $cal['calallevents'] ) {
		$allcats = $cal['calalleventscaption'];
		$caturl  = $cal['calendar_url'];
	}
	if ( ! $calendar && $display['catallevents'] ) {
		$allcats = $display['catalleventscaption'];
		$caturl  = $display['back_to_url'];
	}
	$eventbackground = '';
	if ( $style['event_background'] == 'bgwhite' ) {
		$eventbackground = 'background:white;';
	}

	if ( $style['event_background'] == 'bgcolor' ) {
		$eventbackground = 'background:' . $style['event_backgroundhex'] . ';';
	}

	if ( $caturl && $allcats ) {
		$bg     = ( $style['date_background'] == 'color' ? $style['date_backgroundhex'] : $style['date_background'] );
		$catkey .= '<span class="qem-category" style="border:' . $style['date_border_width'] . 'px solid ' . $style['date_border_colour'] . ';background:#CCC"><a style="color:' . $style['date_colour'] . '" href="' . $caturl . '">' . $allcats . '</a></span>';
	}


	$class = 'class="qem-category qem-key"';
	foreach ( $cat as $i ) {
		foreach ( $arr as $option ) {
			if ( $style[ 'cat' . $i ] == $option->slug ) {
				$thecat = empty( $option->name ) ? $option->slug : $option->name;
				break;
			} else {
				$thecat = '';
			}
		}

		if ( ! empty( $style[ 'cat' . $i ] ) ) {
			if ( $calendar ) {
				if ( qem_get_element( $cal, 'linktocategories', false ) ) {
					if ( qem_get_element( $cal, 'catstyle' ) == "colorAsBorder" ) {
						$catkey .= '<span ' . $class . ' style="' . $eventbackground . ';border:' . $style['date_border_width'] . 'px solid ' . $style[ 'cat' . $i . 'back' ] . ';"><a href="' . $pageurl . $link . 'category=' . $thecat . '">' . $thecat . '</a></span>';
					} else {
						$catkey .= '<span ' . $class . ' style="border:' . $style['date_border_width'] . 'px solid ' . $style[ 'cat' . $i . 'text' ] . ';background:' . $style[ 'cat' . $i . 'back' ] . '"><a style="color:' . $style[ 'cat' . $i . 'text' ] . '" href="' . $pageurl . $link . 'category=' . $thecat . '">' . $thecat . '</a></span>';
					}

				} else {
					if ( qem_get_element( $cal, 'catstyle' ) == "colorAsBorder" ) {
						$catkey .= '<span ' . $class . ' style="' . $eventbackground . ';border:' . $style['date_border_width'] . 'px solid ' . $style[ 'cat' . $i . 'back' ] . ';"><a href="' . $pageurl . $link . 'category=' . $thecat . '">' . $thecat . '</a></span>';
					} else {
						$catkey .= '<span ' . $class . ' style="border:' . $style['date_border_width'] . 'px solid ' . qem_get_element( $cal, 'cat' . $i . 'text', '#343838' ) . ';background:' . $style[ 'cat' . $i . 'back' ] . ';color:' . $style[ 'cat' . $i . 'text' ] . ';">' . $thecat . '</span>';
					}
				}

				if ( $style['showuncategorised'] ) {
					$uncatkey = '<span ' . $class . ' style="border:' . $style['date_border_width'] . 'px solid ' . $style['date_border_colour'] . ';">Uncategorised</span>';
				}
			} else {
				if ( $display['linktocategories'] ) {
					$catkey .= '<span ' . $class . ' style="' . $eventbackground . ';border:' . $style['date_border_width'] . 'px solid ' . $style[ 'cat' . $i . 'back' ] . ';"><a href="' . $pageurl . $link . 'category=' . $thecat . '">' . $thecat . '</a></span>';
				} else {
					$catkey .= '<span ' . $class . ' style="' . $eventbackground . ';border:' . $style['date_border_width'] . 'px solid ' . $style[ 'cat' . $i . 'back' ] . ';">' . $thecat . '</span>';
				}
				if ( $display['showuncategorised'] ) {
					$uncatkey = '<span ' . $class . ' style="border:' . $style['date_border_width'] . 'px solid ' . $style['date_border_colour'] . ';">Uncategorise
d</span>';
				}
			}
		}
	}
	$catkey .= $uncatkey . '</p><div style="clear:left;"></div>' . "\r\n";

	return $catkey;
}

/**
 * Calculates how many are coming
 *
 * @param $pid
 * @param $payment
 *
 * @return int
 */
function qem_get_the_numbers( $pid, $payment ) {
	$str        = 0;
	$register   = qem_get_stored_register();
	$whoscoming = (array) get_option( 'qem_messages_' . $pid, array() );
	if ( ! empty( $whoscoming ) ) {
		foreach ( $whoscoming as $item ) {
			if ( ! qem_check_ipnblock( $payment, $item ) &&
			     ! qem_get_element( $item, 'notattend', false ) &&
			     ( ( $register['moderate'] && isset( $item['approved'] ) ) ||
			       ! $register['moderate'] || ( $register['moderate'] && $register['moderateplaces'] ) ) ) {
				$str = $str + (int) qem_get_element( $item, 'yourplaces', 0 );
			}
		}
	}

	return (int) $str;
}

/**
 * Calculate the number of places available
 * if there is no limit then available places is retunred as zero so the limit should always be checked
 * the limit is post meta event_number and no limit is returned as a blank string
 *
 * @param $pid
 *
 * @return int
 */
function qem_number_places_available( $pid ) {
	$payment = qem_get_stored_payment();
	$number  = get_post_meta( $pid, 'event_number', true );
	if ( '' === $number ) {
		return 0;
	}
	$attending = qem_get_the_numbers( $pid, $payment );
	$places    = $number - $attending;
	if ( $places >= 0 ) {
		return (int) $places;
	} else {
		return 0;
	}
}


// Displays how many places available

function qem_places( $register, $pid, $event_number_max, $event = array() ) {
	$places     = qem_number_places_available( $pid );
	$cutoff     = '';
	$content    = '';
	$cutoffdate = get_post_meta( $pid, 'event_cutoff_date', true );
	if ( $cutoffdate && $cutoffdate < time() ) {
		$cutoff = 'checked';
	}
	if ( isset( $event['iflessthan'] ) && $event['iflessthan'] && $places > $event['iflessthan'] ) {
		return $content;
	}
	if ( '' !== $event_number_max ) {
		if ( $places == 0 || $cutoff ) {
			$content = '<div class="qem-places qem-registration-closed">' . $register['eventfullmessage'] . '</div>';
		} elseif ( $places == 1 ) {
			$content = '<div class="qem-places qem-registration-oneplacebefore">' . $event['oneplacebefore'] . '</div>';
		} else {
			$content = '<div class="qem-places qem-registration-places">' . $event['placesbefore'] . ' ' . $places . ' ' . $event['placesafter'] . '</div>';
		}
	}

	return $content;
}

/*
	Added function to normalize this action across most functions
*/
function qem_actual_link() {
	if ( isset( $_REQUEST['action'] ) ) {
		$actual_link = explode( '?', sanitize_text_field( $_SERVER['HTTP_REFERER'] ) );
		$actual_link = $actual_link[0];
	} else {
		$prefix = 'http://';
		if ( is_ssl() ) {
			$prefix = 'https://';
		}
		$actual_link = $prefix . sanitize_text_field($_SERVER['HTTP_HOST']) . sanitize_text_field($_SERVER['REQUEST_URI']);
	}

	return $actual_link;
}

// Creates the content for the popup

function get_event_popup( $atts ) {
	$atts['links']        = 'checked';
	$atts['popup']        = $atts['grid'] = '';
	$atts['linkpopup']    = '';
	$atts['thisday']      = '';
	$atts['fullevent']    = 'full';
	$atts['thisisapopup'] = true;

	if ( qem_get_element( $atts, 'listplaces', false ) && ! qem_get_element( $atts, 'fullpopup', false ) ) {
		$atts['fullevent'] = 'summary';
	}
	if ( $atts['calendar'] && ! qem_get_element( $atts, 'fullpopup', false ) ) {
		$atts['fullevent'] = 'summary';
	}
	$output = qem_event_construct_esc( $atts );
	$output = str_replace( '"', '&quot;', $output );
	$output = str_replace( '<', '&lt;', $output );
	$output = str_replace( '>', '&gt;', $output );
	$output = str_replace( "'", "&#8217;", $output );
	$output = str_replace( "&#39;", "&#8217;", $output );

	return $output;
}

function dateToCal( $timestamp ) {
	if ( $timestamp ) {
		return date( 'Ymd\THis', $timestamp );
	}
}


function qem_time( $starttime ) {
	$time = (int) strtotime( '1 jan 1970 ' . $starttime );
	if ( $time >= 86400 ) {
		return 0;
	}

	return $time;
}


/**
 * @param $array  array   search array
 * @param $keys   string|array  key or an array of keys  e.g.  array ( 'row', 'column')
 * @param $default string|mixed
 *
 * @return mixed|string
 */
function qem_get_element( $array, $keys, $default = '' ) {
	if ( ! is_array( $array ) ) {
		return $array;
	}
	if ( ! is_array( $keys ) ) {
		if ( array_key_exists( $keys, $array ) ) {
			return $array[ $keys ];
		}
	} else {
		$result = $array;
		foreach ( $keys as $key ) {
			if ( array_key_exists( $key, $result ) ) {
				$result = $result[ $key ];
			} else {
				return $default;
			}
		}
		if ( ! is_array( $result ) ) {
			return $result;
		}

	}

	return $default;
}


function qem_wp_mail( $type, $qem_email, $title, $content, $headers ) {
	add_action( 'wp_mail_failed', function ( $wp_error ) {
		/**  @var $wp_error \WP_Error */
		if ( defined( 'WP_DEBUG' ) && true == WP_DEBUG && is_wp_error( $wp_error ) ) {
			trigger_error( 'QEM Email - wp_mail error msg : ' . esc_html( $wp_error->get_error_message() ), E_USER_WARNING );
		}
	}, 10, 1 );
	if ( defined( 'WP_DEBUG' ) && true == WP_DEBUG ) {
		trigger_error( 'QEM Email message about to send: ' . esc_html( $type ) . ' To: ' . esc_html( $qem_email ), E_USER_NOTICE );
	}
	$decode_title = html_entity_decode( $title, ENT_QUOTES );
	$headers .=  "X-Entity-Ref-ID: " . uniqid() . "\r\n";
	$headers = apply_filters( 'qem_email_headers', $headers, $type, $qem_email, $title, $content, $headers );
	$decode_title = apply_filters( 'qem_email_title', $decode_title, $type, $qem_email, $title, $content, $headers );
	$qem_email = apply_filters( 'qem_email_to', $qem_email, $type, $qem_email, $title, $content, $headers );
	$res          = wp_mail( $qem_email, $decode_title, $content, $headers );
	if ( defined( 'WP_DEBUG' ) && true == WP_DEBUG ) {
		if ( true === $res ) {
			trigger_error( 'QEM Email - wp_mail responded OK : ' . esc_html( $type ) . ' To: ' . esc_html( $qem_email ), E_USER_NOTICE );
		} else {
			trigger_error( 'QEM Email - wp_mail responded FAILED to send : ' . esc_html( $type ) . ' To: ' . esc_html( $qem_email ), E_USER_WARNING );
		}
	}
}

function qem_sanitize_email_list( $email ) {
	$qem_email_in = explode( ',', sanitize_text_field( $email ) );
	$qem_email    = array();
	foreach ( $qem_email_in as $email ) {
		$out = sanitize_email( $email );
		if ( ! empty( $out ) ) {
			$qem_email[] = $out;
		}
	}

	return implode( ',', $qem_email );
}

/**
 * Recursive sanitation for text or array
 *
 * @param $array_or_string (array|string)
 *
 * @return mixed
 * @since  0.1
 */
function qem_sanitize_text_or_array_field( $array_or_string ) {
	if ( is_string( $array_or_string ) ) {
		$array_or_string = sanitize_text_field( $array_or_string );
	} elseif ( is_array( $array_or_string ) ) {
		foreach ( $array_or_string as $key => &$value ) {
			if ( is_array( $value ) ) {
				$value = qem_sanitize_text_or_array_field( $value );
			} else {
				$value = sanitize_text_field( $value );
			}
		}
	}

	return $array_or_string;
}

function qem_kses_post_svg_form( $html ) {
	return $html;

	$kses_defaults = wp_kses_allowed_html( 'post' );

	$svg_args = array(
		'svg'    => array(
			'class'           => true,
			'aria-hidden'     => true,
			'aria-labelledby' => true,
			'role'            => true,
			'xmlns'           => true,
			'fill'            => true,
			'width'           => true,
			'height'          => true,
			'viewbox'         => true // <= Must be lower case!
		),
		'g'      => array( 'fill' => true ),
		'title'  => array( 'title' => true ),
		'path'   => array(
			'd'    => true,
			'fill' => true,
		),
		'form'   => array(
			'class' => true,
			'action' => true,
			'method' => true,
			'enctype' => true,
			'id' => true,
		),
		'input' =>
			array(
				'class' => true,
				'name'  => true,
				'type'  => true,
				'value' => true,
			),
		'select' =>
			array(
				'class' => true,
				'name'  => true,
			),
		'option' =>
			array(
				'class' => true,
				'value' => true,
			),
	);

	$allowed_tags = array_merge( $kses_defaults, $svg_args );

	return wp_kses( $html, $allowed_tags );
}


function qem_remove_registration( $return ) {
	$id      = get_the_ID();
	$message = get_option( 'qem_messages_' . $id );
	$custom  = $return['CUSTOM'];

	if ( $message ) {
		$messages = array();
		$count    = count( $message );
		for ( $i = 0; $i < $count; $i ++ ) {
			if ( $message[ $i ]['ipn'] != $custom ) {
				$messages[] = $message[ $i ];
			}
		}

		update_option( 'qem_messages_' . $id, $messages );
	}
}

function qem_get_incontext() {
	$setup = qem_get_stored_payment();

	$mode = ( ( isset( $setup['sandbox'] ) && $setup['sandbox'] == 'checked' ) ? 'SANDBOX' : 'PRODUCTION' );

	if ( $mode == 'SANDBOX' ) {
		$incontext = qem_get_stored_sandbox();
	} else {
		$incontext = qem_get_stored_incontext();
	}

	$incontext['api_mode'] = $mode;

	return $incontext;
}

function qem_get_next( $rules, $current_date ) {

	$frequency = $rules['frequency'];
	$target    = $rules['target'];
	$for       = $rules['for'];


	// Format the string based on the given values
	$string = "";
	switch ( $target ) {
		case 'Day':
			$string = 'Tomorrow';
			break;
		case 'Week':
			$string = '+1 Week';
			break;
		case 'Month':
			$string = '+1 Month';
			break;
		default: // week day
			$string = $target;
			if ( $frequency != 'Every' ) {
				$month  = 'OF THIS MONTH';
				$string = $frequency . ' ' . $string;

				/*
					Run a quick test

					Testing if the returned value is < start date, if so, change the string
				*/
				if ( strtotime( $string . ' ' . $month, $current_date ) <= $current_date ) {
					$month = 'OF NEXT MONTH';
				}
				$string = $string . ' ' . $month;
			} else {
				$string = 'Next ' . $string;
			}
			break;
	}

	return strtotime( $string, $current_date );

}

function qem_get_end( $rules ) {
	$end = strtotime( "+" . $rules['number'] . " " . $rules['for'], $rules['start'] );
	if ( false === $end ) {
		$end = time();
	}

	return $end;
}

function qem_wp_kses_post( $post ) {
	// required to avoid Deprecated: preg_replace(): Passing null to parameter #3 ($subject) of type array|string is deprecated in /var/www/html/wp-includes/kses.php on line 1744
	if ( ! empty($post) ) {
		$post = wp_kses_post( $post );
	}

	return $post;
}