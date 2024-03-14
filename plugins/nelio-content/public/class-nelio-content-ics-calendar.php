<?php
/**
 * This file contains a class with some calendar-related helper functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/helpers
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements calendar-related helper functions.
 */
class Nelio_Content_Ics_Calendar {

	/**
	 * Start date.
	 *
	 * @var string
	 */
	public $start_date = '';

	/**
	 * Current week.
	 *
	 * @var int
	 */
	public $current_week = 1;

	/**
	 * Default number of weeks to show per screen.
	 *
	 * @var int
	 */
	public $total_weeks = 6;

	protected static $instance;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	public function init() {

		add_action( 'plugins_loaded', array( $this, 'do_init' ) );

	}//end init()

	public function do_init() {

		$settings = Nelio_Content_Settings::instance();
		if ( ! $settings->get( 'use_ics_subscription' ) ) {
			return;
		}//end if

		add_action( 'wp_ajax_nelio_content_calendar_ics_subscription', array( $this, 'handle_ics_subscription' ) );
		add_action( 'wp_ajax_nopriv_nelio_content_calendar_ics_subscription', array( $this, 'handle_ics_subscription' ) );

	}//end do_init()

	/**
	 * After checking that the request is valid, do an .ics file.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function handle_ics_subscription() {

		// Only do .ics subscriptions when the setting is active.
		$settings = Nelio_Content_Settings::instance();
		if ( ! $settings->get( 'use_ics_subscription' ) ) {
			wp_die( esc_html__( 'Invalid request', 'nelio-content' ) );
		}//end if

		// Confirm all of the arguments are present.
		if ( ! isset( $_GET['key'] ) ) { // phpcs:ignore
			wp_die( esc_html__( 'Invalid request', 'nelio-content' ) );
		}//end if

		$post_query_args = array();

		if ( isset( $_GET['user'] ) ) { // phpcs:ignore

			$username = sanitize_user( wp_unslash( $_GET['user'] ) ); // phpcs:ignore
			$user     = get_user_by( 'login', $username );
			if ( $user ) {
				$post_query_args['author'] = $user->ID;
			} else {
				wp_die( esc_html__( 'Invalid request', 'nelio-content' ) );
			}//end if
		} else {
			$username = 'all';
		}//end if

		// Confirm this is a valid request.
		$key            = sanitize_user( wp_unslash( $_GET['key'] ) ); // phpcs:ignore
		$ics_secret_key = get_option( 'nc_ics_key', false );
		if ( ! $ics_secret_key || md5( $username . $ics_secret_key ) !== $key ) {
			wp_die( esc_html__( 'Invalid request', 'nelio-content' ) );
		}//end if

		/**
		 * Filters the date of the first day of the current week.
		 *
		 * @param string $day the date of the first day of the current week (formated as YYYY-MM-DD).
		 *
		 * @since 2.0.0
		 */
		$this->start_date = apply_filters( 'nelio_content_ics_calendar_subscription_start_date', $this->get_beginning_of_week( gmdate( 'Y-m-d' ) ) );

		/**
		 * Filters the number of weeks that should be shared in an ICS export.
		 *
		 * @param int $weeks Number of weeks. Default: 6.
		 *
		 * @since 2.0.0
		 */
		$this->total_weeks = apply_filters( 'nelio_content_ics_calendar_total_weeks', 6 );

		// Fix filters.
		add_filter( 'the_title', array( $this, 'do_ics_escaping' ), 99 );
		remove_filter( 'the_title', 'convert_chars' );
		remove_filter( 'the_title', 'wptexturize' );

		$formatted_posts = array();
		for ( $current_week = 1; $current_week <= $this->total_weeks; $current_week++ ) {

			// We need to set the object variable for our posts_where filter.
			$this->current_week = $current_week;
			$week_posts         = $this->get_calendar_posts_for_week( $post_query_args );

			foreach ( $week_posts as $date => $day_posts ) {
				foreach ( $day_posts as $num => $post ) {

					$start_date    = gmdate( 'Ymd', strtotime( $post->post_date_gmt ) ) . 'T' . gmdate( 'His', strtotime( $post->post_date_gmt ) ) . 'Z';
					$end_date      = gmdate( 'Ymd', strtotime( $post->post_date_gmt ) + ( 5 * 60 ) ) . 'T' . gmdate( 'His', strtotime( $post->post_date_gmt ) + ( 5 * 60 ) ) . 'Z';
					$last_modified = gmdate( 'Ymd', strtotime( $post->post_modified_gmt ) ) . 'T' . gmdate( 'His', strtotime( $post->post_modified_gmt ) ) . 'Z';

					$formatted_post = array(
						'BEGIN'         => 'VEVENT',
						'UID'           => $post->guid,
						'SUMMARY'       => apply_filters( 'the_title', $post->post_title, $post->ID ) . ' - ' . $this->get_friendly_post_status( $post->ID ),
						'DTSTAMP'       => $start_date,
						'DTSTART'       => $start_date,
						'DTEND'         => $end_date,
						'LAST-MODIFIED' => $last_modified,
						'URL'           => get_post_permalink( $post->ID ),
					);

					// Description should include everything visible in the calendar popup.
					$information_fields            = $this->get_post_information_fields( $post );
					$formatted_post['DESCRIPTION'] = '';
					if ( ! empty( $information_fields ) ) {
						foreach ( $information_fields as $key => $values ) {
							$formatted_post['DESCRIPTION'] .= $values['label'] . ': ' . $values['value'] . '\n';
						}//end foreach
						$formatted_post['DESCRIPTION'] = rtrim( $formatted_post['DESCRIPTION'] );
					}//end if

					$formatted_post['END'] = 'VEVENT';

					$formatted_posts[] = $formatted_post;

				}//end foreach
			}//end foreach
		}//end for

		$aux = Nelio_Content::instance();

		// Other template data.
		$header = array(
			'BEGIN'   => 'VCALENDAR',
			'VERSION' => '2.0',
			'PRODID'  => '-//Nelio Content//Nelio Content ' . nelio_content()->plugin_version . '//EN',
		);

		$footer = array(
			'END' => 'VCALENDAR',
		);

		// Render the .ics template and set the content type.
		header( 'Content-type: text/calendar' );
		foreach ( array( $header, $formatted_posts, $footer ) as $section ) {

			foreach ( $section as $key => $value ) {

				if ( is_string( $value ) ) {
					$this->print_ics_line_folding( $key . ':' . $value );
				} else {

					foreach ( $value as $k => $v ) {
						$this->print_ics_line_folding( $k . ':' . $v );
					}//end foreach
				}//end if
			}//end foreach
		}//end foreach

		die();

	}//end handle_ics_subscription()

	/**
	 * Given a day in string format, returns the day at the beginning of that
	 * week, which can be the given date. The end of the week is determined by
	 * the blog option, 'start_of_week'.
	 *
	 * @see http://www.php.net/manual/en/datetime.formats.date.php for valid date
	 * formats
	 *
	 * @param string $date String representing a date.
	 * @param string $format Date format in which the end of the week should be returned.
	 * @param int    $week Number of weeks we're offsetting the range.
	 *
	 * @return string $formatted_start_of_week End of the week
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function get_beginning_of_week( $date, $format = 'Y-m-d', $week = 1 ) {

		$date                    = strtotime( $date );
		$start_of_week           = get_option( 'start_of_week' );
		$day_of_week             = gmdate( 'w', $date );
		$date                   += ( ( $start_of_week - $day_of_week - 7 ) % 7 ) * 60 * 60 * 24 * $week;
		$additional              = 3600 * 24 * 7 * ( $week - 1 );
		$formatted_start_of_week = gmdate( $format, $date + $additional );
		return $formatted_start_of_week;

	}//end get_beginning_of_week()

	/**
	 * Given a day in string format, returns the day at the end of that week,
	 * which can be the given date. The end of the week is determined by the blog
	 * option, 'start_of_week'.
	 *
	 * @see http://www.php.net/manual/en/datetime.formats.date.php for valid date formats
	 *
	 * @param string $date String representing a date.
	 * @param string $format Date format in which the end of the week should be returned.
	 * @param int    $week Number of weeks we're offsetting the range.
	 *
	 * @return string End of the week
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function get_ending_of_week( $date, $format = 'Y-m-d', $week = 1 ) {

		$date                  = strtotime( $date );
		$end_of_week           = get_option( 'start_of_week' ) - 1;
		$day_of_week           = gmdate( 'w', $date );
		$date                 += ( ( $end_of_week - $day_of_week + 7 ) % 7 ) * 60 * 60 * 24;
		$additional            = 3600 * 24 * 7 * ( $week - 1 );
		$formatted_end_of_week = gmdate( $format, $date + $additional );
		return $formatted_end_of_week;

	}//end get_ending_of_week()

	/**
	 * Perform line folding according to RFC 5545.
	 *
	 * @param string $line The line without trailing CRLF.
	 *
	 * @since  2.0.0
	 * @access private
	 */
	private function print_ics_line_folding( $line ) {

		$len = mb_strlen( $line );
		if ( $len <= 73 ) {
			echo $line . "\r\n"; // phpcs:ignore WordPress.XSS.EscapeOutput
			return;
		}//end if

		$chunks = array();
		$start  = 0;
		while ( true ) {

			$chunk     = mb_substr( $line, $start, 73 );
			$chunk_len = mb_strlen( $chunk );
			$start    += $chunk_len;

			if ( $start < $len ) {
				$chunks[] = $chunk . "\r\n ";
			} else {
				$chunks[] = $chunk . "\r\n";
				echo implode( '', $chunks ); // phpcs:ignore WordPress.XSS.EscapeOutput
				return;
			}//end if
		}//end while

	}//end print_ics_line_folding()

	/**
	 * Perform the encoding necessary for ICS feed text.
	 *
	 * @param string $text The string that needs to be escaped.
	 *
	 * @return string The string after escaping for ICS.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function do_ics_escaping( $text ) {

		$text = htmlspecialchars_decode( $text );
		$text = str_replace( ',', '\,', $text );
		$text = str_replace( ';', '\:', $text );
		$text = str_replace( '\\', '\\\\', $text );
		return $text;

	}//end do_ics_escaping()

	/**
	 * Query to get all of the calendar posts for a given day.
	 *
	 * @param array $args Any filter arguments we want to pass.
	 *
	 * @return array $posts All of the posts as an array sorted by date.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	private function get_calendar_posts_for_week( $args = array() ) {

		$settings             = Nelio_Content_Settings::instance();
		$supported_post_types = $settings->get( 'calendar_post_types', array() );
		$defaults             = array(
			'post_type'      => $supported_post_types,
			'posts_per_page' => -1,
		);

		$args = array_merge( $defaults, $args );

		// The WP functions for printing the category and author assign a value of 0 to the default
		// options, but passing this to the query is bad (trashed and auto-draft posts appear!), so
		// unset those arguments.
		if ( ! isset( $args['cat'] ) || '0' === $args['cat'] ) {
			unset( $args['cat'] );
		}//end if
		if ( '0' === $args['author'] ) {
			unset( $args['author'] );
		}//end if

		if ( empty( $args['post_type'] ) || ! in_array( $args['post_type'], $supported_post_types, true ) ) {
			$args['post_type'] = $supported_post_types;
		}//end if

		/**
		 * Filters the arguments to retrieve the posts that should be shared in an ICS export.
		 *
		 * @param array $args post where arguments.
		 *
		 * @since 2.0.0
		 */
		$args = apply_filters( 'nelio_content_ics_calendar_posts_query_args', $args );
		add_filter( 'posts_where', array( $this, 'posts_where_week_range' ) );
		$post_results = new WP_Query( $args );
		remove_filter( 'posts_where', array( $this, 'posts_where_week_range' ) );

		$posts = array();
		while ( $post_results->have_posts() ) {
			$post_results->the_post();
			global $post;
			$key_date             = gmdate( 'Y-m-d', strtotime( $post->post_date ) );
			$posts[ $key_date ][] = $post;
		}//end while

		return $posts;

	}//end get_calendar_posts_for_week()

	/**
	 * Filter the WP_Query so we can get a week range of posts.
	 *
	 * @param string $where The original WHERE SQL query string.
	 *
	 * @return string Our modified WHERE query string.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function posts_where_week_range( $where = '' ) {

		global $wpdb;

		$calendar_helper = self::instance();

		$beginning_date = $calendar_helper->get_beginning_of_week( $calendar_helper->start_date, 'Y-m-d', $calendar_helper->current_week );
		$ending_date    = $calendar_helper->get_ending_of_week( $calendar_helper->start_date, 'Y-m-d', $calendar_helper->current_week );
		// Adjust the ending date to account for the entire day of the last day of the week.
		$ending_date = gmdate( 'Y-m-d', strtotime( '+1 day', strtotime( $ending_date ) ) );
		$where       = $where . $wpdb->prepare( " AND ($wpdb->posts.post_date_gmt >= %s AND $wpdb->posts.post_date_gmt < %s)", $beginning_date, $ending_date );

		return $where;

	}//end posts_where_week_range()

	/**
	 * Get relevant fields of the post.
	 *
	 * @param obj $post Post to gather relevant information fields for.
	 *
	 * @return array All of the information fields of the post.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	private function get_post_information_fields( $post ) {

		$information_fields = array();

		// Post author.
		$information_fields['author'] = array(
			'label' => _x( 'Author', 'text', 'nelio-content' ),
			'value' => get_the_author_meta( 'display_name', $post->post_author ),
			'type'  => 'author',
		);

		// If the calendar supports more than one post type, show the post type label.
		$settings             = Nelio_Content_Settings::instance();
		$supported_post_types = $settings->get( 'calendar_post_types', array() );
		if ( count( $supported_post_types ) > 1 ) {
			$information_fields['post_type'] = array(
				'label' => _x( 'Post Type', 'text', 'nelio-content' ),
				'value' => get_post_type_object( $post->post_type )->labels->singular_name,
			);
		}//end if

		// Publication time for published statuses.
		$published_statuses = array(
			'publish',
			'future',
			'private',
		);
		if ( in_array( $post->post_status, $published_statuses, true ) ) {
			if ( 'future' === $post->post_status ) {
				$information_fields['post_date'] = array(
					'label' => _x( 'Scheduled', 'text (post status)', 'nelio-content' ),
					'value' => get_the_time( null, $post->ID ),
				);
			} else {
				$information_fields['post_date'] = array(
					'label' => _x( 'Published', 'text (post status)', 'nelio-content' ),
					'value' => get_the_time( null, $post->ID ),
				);
			}//end if
		}//end if

		// Taxonomies and their values.
		$args       = array(
			'post_type' => $post->post_type,
		);
		$taxonomies = get_object_taxonomies( $args, 'object' );
		foreach ( (array) $taxonomies as $taxonomy ) {
			// Sometimes taxonomies skip by, so let's make sure it has a label too.
			if ( ! $taxonomy->public || ! $taxonomy->label ) {
				continue;
			}//end if

			$terms = get_the_terms( $post->ID, $taxonomy->name );
			if ( ! $terms || is_wp_error( $terms ) ) {
				continue;
			}//end if

			$key = 'tax_' . $taxonomy->name;
			if ( count( $terms ) ) {
				$value = '';
				foreach ( (array) $terms as $term ) {
					$value .= $term->name . ', ';
				}//end foreach
				$value = rtrim( $value, ', ' );
			} else {
				$value = '';
			}//end if

			// Used when editing editorial metadata and post meta.
			if ( is_taxonomy_hierarchical( $taxonomy->name ) ) {
				$type = 'taxonomy hierarchical';
			} else {
				$type = 'taxonomy';
			}//end if

			$information_fields[ $key ] = array(
				'label' => $taxonomy->label,
				'value' => $value,
				'type'  => $type,
			);

			if ( 'page' === $post->post_type ) {
				$ed_cap = 'edit_page';
			} else {
				$ed_cap = 'edit_post';
			}//end if

			if ( current_user_can( $ed_cap, $post->ID ) ) {
				$information_fields[ $key ]['editable'] = true;
			}//end if
		}//end foreach

		// View/preview links.
		if ( 'publish' !== $new_status ) {
			$view_link                       = add_query_arg( array( 'preview' => 'true' ), wp_get_shortlink( $post->ID ) );
			$information_fields['view-link'] = array(
				'label' => _x( 'Preview', 'command', 'nelio-content' ),
				'value' => $view_link,
			);
		} else {
			$view_link                       = htmlspecialchars_decode( get_permalink( $post ) );
			$information_fields['view-link'] = array(
				'label' => _x( 'View', 'command', 'nelio-content' ),
				'value' => $view_link,
			);
		}//end if

		/**
		 * Filters the fields of an item.
		 *
		 * @param array $fields  the fields of an item.
		 * @param int   $post_id the ID of the post.
		 *
		 * @since 2.0.0
		 */
		$information_fields = apply_filters( 'nelio_content_ics_calendar_item_information_fields', $information_fields, $post->ID );

		/**
		 * Filters whether we should hide or not empty fields from items in an ICS export.
		 *
		 * @param boolean $hide    whether we should hide or not empty fields. Default: `true`.
		 * @param int     $post_id the post ID to which the fields belong to.
		 *
		 * @since 2.0.0
		 */
		$hide_empty_fields = apply_filters( 'nelio_content_ics_calendar_hide_empty_item_information_fields', true, $post->ID );

		foreach ( $information_fields as $field => $values ) {

			/**
			 * Filters whether a certain field should be removed from an ICS export or not.
			 *
			 * @param boolean $hide    whether we should hide or not the given field. Default: `false`.
			 * @param int     $post_id the post ID to which the fields belong to.
			 *
			 * @since 2.0.0
			 */
			$hide_field = apply_filters( "nelio_content_ics_calendar_hide_{$field}_item_information_field", false, $post->ID );

			if ( $hide_field || ( $hide_empty_fields && empty( $values['value'] ) ) ) {
				unset( $information_fields[ $field ] );
			}//end if
		}//end foreach

		return $information_fields;

	}//end get_post_information_fields()

	private function get_friendly_post_status( $post_id ) {
		$statuses    = get_post_statuses();
		$post_status = get_post_status( $post_id );
		return isset( $statuses[ $post_status ] ) ? $statuses[ $post_status ] : $post_status;
	}//end get_friendly_post_status()

}//end class
