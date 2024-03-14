<?php  // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * CRUD for analytics data
 *
 * @property string primary_key
 * @property string table_name
 * @property array events_meta
 */
class EPHD_Analytics_DB extends EPHD_DB {

	protected $events_meta;

	const PRIMARY_KEY = 'event_id';

	const EVENTS_META = array(
		'impressions'  => array(
			'type_id'      => 1,
			'description'  => 'Impressions',
			'aggregation'  => 'first day of this month', // Month
			'expiration'   => 'first day of this month', // Month
			/*	'count_config' => 'analytic_count_launcher_impression', // NOTE: comment out impressions on/off code for now	*/
			'object'       => 'page',
		),
		'launcher-open'  => array(
			'type_id'     => 2,
			'description' => 'Opened Launch Count',
			'aggregation' => 'first day of this month', // Month
			'expiration'  => 'first day of this month', // Month
			'object'      => 'page',
		),
		'faq-item-click' => array(
			'type_id'     => 3,
			'description' => 'Click to open FAQ',
			'aggregation' => 'monday this week', // 1 Week
			'expiration'  => 'first day of this month - 2 months', // 3 Months
			'object'      => 'faq',
		),
		'article-item-click' => array(
			'type_id'     => 4,
			'description' => 'Click on Article in search results',
			'aggregation' => 'monday this week', // 1 Week
			'expiration'  => 'first day of this month - 2 months', // 3 Months
			'object'      => 'article',
		),
		'contact-tab-opened' => array(
			'type_id'     => 5,
			'description' => 'Contact Form tab is opened count',
			'aggregation' => 'monday this week', // 1 Week
			'expiration'  => 'first day of this month - 2 months', // 3 Months
			'object'      => 'page',        // contact form ID
		),
		'faq-group-click' => array(
			'type_id'     => 6,
			'description' => 'Click on FAQ group',
			'aggregation' => 'monday this week', // 1 Week
			'expiration'  => 'first day of this month - 2 months', // 3 Months
			'object'      => 'faq',
		),
	);

	/**
	 * Get things started
	 *
	 * @access  public
	 */
	public function __construct() {

		parent::__construct();

		global $wpdb;

		$this->table_name  = $wpdb->prefix . 'ephd_analytics';
		$this->primary_key = self::PRIMARY_KEY;
		$this->events_meta = self::EVENTS_META;
	}

	/**
	 * Get columns and formats
	 *
	 * @access  public
	 */
	public function get_column_format() {
		return array(
			'event_id'      => '%d',
			'submit_date'   => '%s',
			'widget_id'     => '%d',
			'page_id'       => '%d',
			'object_id'     => '%d',  // <empty>, question id, article id, etc.
			'event_type_id' => '%d',  // Launcher, FAQs, Search, Contact Us etc
			'view'          => '%d',
			'click_1'       => '%d',
			'click_2'       => '%d',
			'other_1'       => '%d',
			'other_2'       => '%d'
		);
	}

	/**
	 * Get default column values
	 *
	 * @return array
	 */
	public function get_column_defaults() {
		return array(
			'submit_date'   => date( 'Y-m-d' ),
			'widget_id'     => '',
			'page_id'       => '',
			'object_id'     => '',
			'event_type_id' => '',
			'view'          => '',
			'click_1'       => '',
			'click_2'       => '',
			'other_1'       => '',
			'other_2'       => ''
		);
	}

	/**
	 * Count Analytics Event
	 *
	 * @param $widget_id
	 * @param $page_id
	 * @param $object_id
	 * @param $column_name
	 * @param $event_name
	 * @param int $value
	 *
	 * @return bool|int
	 */
	public function count_event( $widget_id, $page_id, $object_id, $column_name, $event_name, $value = 1 ) {

		// retrieve validated event meta by event name
		$event_meta = $this->get_validated_event_meta_by_name( $event_name );
		if ( empty( $event_meta ) ) {
			return false;
		}

		$aggregation_start_date = date( 'Y-m-d', strtotime( $event_meta['aggregation'] ) );

		$where_data = array(
			'widget_id'     => $widget_id,
			'page_id'       => $page_id,
			'object_id'     => $object_id,
			'event_type_id' => $event_meta['type_id'],
			'submit_date'   => array(
				'value'    => $aggregation_start_date,
				'operator' => '>='
			)
		);

		$existing_event = parent::get_a_row_by_where_clause( $where_data );
		if ( is_wp_error( $existing_event ) ) {
			return false;
		}

		// Insert new event if it doesn't exist
		if ( empty( $existing_event ) ) {
			$event_id = $this->insert_analytics_record( $widget_id, $page_id, $object_id, $event_meta['type_id'], $column_name, $value );
			if ( is_wp_error( $event_id ) ) {
				return false;
			}
			return $event_id;
		}

		if ( ! isset( $existing_event->event_id ) || ! isset( $existing_event->{$column_name} ) ) {
			return false;
		}

		// Update aggregated event
		$updated_value = $existing_event->{$column_name} + $value;
		$result = $this->update_analytics_record( $existing_event->event_id, $column_name, $updated_value );
		if ( is_wp_error( $result ) ) {
			return false;
		}

		return $existing_event->event_id;
	}

	/**
	 * Get validated event meta by name
	 *
	 * @param $event_name
	 *
	 * @return bool|array
	 */
	protected function get_validated_event_meta_by_name( $event_name ) {

		// сheck if event exist
		$event_meta = isset( $this->events_meta[$event_name] ) ? $this->events_meta[$event_name] : [];
		if ( empty( $event_meta ) ) {
			EPHD_Logging::add_log( 'Analytics update: Event "' . $event_name . '" does not exist' );
			return false;
		}

		// сheck if required event param exist
		if ( ! isset( $event_meta['type_id'] ) || ! isset( $event_meta['aggregation'] ) ) {
			EPHD_Logging::add_log( 'Analytics update: Could not retrieve Event Meta for: ' . $event_name );
			return false;
		}

		/* NOTE: comment out impressions on/off code for now
		// is counting of this event disabled in config?
		if ( ! empty( $event_meta['count_config'] ) ) {
			if ( isset( $global_config[ $event_meta['count_config'] ] ) ) {
				if ( 'off' == $global_config[ $event_meta['count_config'] ] ) {
					return false;
				}
			}
		}	*/

		return $event_meta;
	}

	/**
	 * Insert a new analytics record
	 *
	 * @param $widget_id
	 * @param $page_id
	 * @param $object_id
	 * @param $event_type_id
	 * @param $column
	 * @param $value
	 *
	 * @return int|WP_Error - WP_Error or record ID
	 */
	public function insert_analytics_record( $widget_id, $page_id, $object_id, $event_type_id, $column, $value ) {

		// insert the record
		$record = array(
			'widget_id'     => $widget_id,
			'page_id'       => $page_id,
			'object_id'     => $object_id,
			'event_type_id' => $event_type_id,
			'submit_date'   => date( 'Y-m-d' ),
			$column         => $value
		);

		$event_id = parent::insert_record( $record );
		if ( empty( $event_id ) ) {
			return new WP_Error( 'db-insert-error', 'Could not insert Help Dialog Analytics record: ' . $event_id . ' for widget ID: ' . $widget_id . ' and page ID: ' . $page_id );
		}

		return $event_id;
	}

	/**
	 * Update analytics record
	 *
	 * @param $event_id
	 * @param $column
	 * @param $value
	 *
	 * @return bool|WP_Error - WP_Error or true
	 */
	public function update_analytics_record( $event_id, $column, $value ) {

		// update the record
		$record = array(
			$column  => $value
		);

		$result = parent::update_record( $event_id, $record );
		if ( empty( $result ) ) {
			return new WP_Error( 'db-update-error', 'Could not update Help Dialog Analytics record: ' . $event_id );
		}

		return true;
	}

	/**
	 * Delete expired analytics records
	 *
	 *
	 * @return bool|WP_Error - WP_Error or true
	 */
	public function delete_expired_analytics_records() {

		$purge_interval = strtotime('1 day', 0);
		$purge_date_option = 'ephd_analytics_purge_date';

		// get how often to purge
		$last_purge = get_option( $purge_date_option, 0 );
		if ( empty( $last_purge ) ) { // do not log
			update_option( $purge_date_option, time() );
			return false;
		}

		$last_purge = EPHD_Utilities::sanitize_int( $last_purge, 0 );
		if ( empty( $last_purge ) || $last_purge > time() ) { // do not log
			update_option( $purge_date_option, time() );
			return false;
		}

		// it is too early to purge record
		if ( (time() - $last_purge ) < $purge_interval ) {
			return false;
		}

		foreach ( $this->events_meta as $event_name => $event_meta ) {
			if ( ! isset( $event_meta['type_id'] ) ) {
				EPHD_Logging::add_log( "Event meta type_id does not exist for " . $event_name );
				continue;
			}

			if ( ! isset( $event_meta['expiration'] ) ) {
				$event_meta['expiration'] = 'first day of this month';
			}

			$expiration_date = date( 'Y-m-d', strtotime( $event_meta['expiration'] ) );

			$where_data = array(
				'event_type_id' => $event_meta['type_id'],
				'submit_date'   => array(
					'value'    => $expiration_date,
					'operator' => '<'
				)
			);

			parent::delete_rows_by_where_clause( $where_data );
		}

		update_option( $purge_date_option, time() );

		// FUTURE TODO check table size and alert on admin

		return true;
	}



	/**************************************************************************************************************************
	 *
	 *                     GET ANALYTICS
	 *
	 *************************************************************************************************************************/

	/**
	 * Get total launcher Open / Invocations count
	 *
	 * @return int|null
	 */
	public function get_total_invocations_count() {
		return $this->get_invocations_count( date('Y-m-d', 0), date('Y-m-d') );
	}

	/**
	 * Get launcher Open / Invocations count By date
	 *
	 * @param $date_from
	 * @param $date_to
	 * @param $location
	 * @return int|null
	 */
	public function get_invocations_count( $date_from, $date_to, $location='' ) {

		$where_data = is_numeric( $location ) ? ['page_id' => $location] : [];

		$result = $this->get_event_sum_by_where_clause( 'launcher-open', 'click_1', $date_from, $date_to, $where_data );
		if ( $result === null || is_wp_error( $result ) ) {
			return null;
		}
		return (int)$result;
	}

	/**
	 * Get total Impressions count
	 *
	 * @return int|null
	 */
	public function get_total_impressions_count() {
		return $this->get_impressions_count( date('Y-m-d', 0), date('Y-m-d') );
	}

	/**
	 * Get Impressions count By date
	 *
	 * @param $date_from
	 * @param $date_to
	 * @param $location
	 * @return int|null
	 */
	public function get_impressions_count( $date_from, $date_to, $location='' ) {

		$where_data = is_numeric( $location ) ? ['page_id' => $location] : [];

		$result = $this->get_event_sum_by_where_clause( 'impressions', 'view', $date_from, $date_to, $where_data );
		if ( $result === null || is_wp_error( $result ) ) {
			return null;
		}
		return (int)$result;
	}

	/**
	 *  Get total contact form opened count
	 *
	 * @return int|null
	 */
	public function get_total_contact_opened_count() {

		$date_from = date('Y-m-d', 0);
		$date_to = date('Y-m-d');

		return $this->get_contact_opened_count( $date_from, $date_to );
	}

	/**
	 *  Get contact form opened count By Date
	 *
	 * @param $date_from
	 * @param $date_to
	 * @param $where_data
	 *
	 * @return int|null
	 */
	public function get_contact_opened_count( $date_from, $date_to, $where_data=[] ) {
		$result = $this->get_event_sum_by_where_clause( 'contact-tab-opened', 'click_1', $date_from, $date_to, $where_data );
		if ( $result === null || is_wp_error( $result ) ) {
			return null;
		}
		return (int)$result;
	}

	/**
	 * Get click-through rate (launcher-open / impressions) * 100
	 *
	 * @param $date_from
	 * @param $date_to
	 * @param $location
	 *
	 * @return array|null
	 */
	public function get_launcher_click_through_rate( $date_from, $date_to, $location='' ) {

		$views = 0;
		$clicks = 0;

		$views_by_page = $this->get_impressions_by_pages( $date_from, $date_to );
		if ( empty( $views_by_page ) ) {
			return null;
		}

		$clicks_by_page = $this->get_launcher_open_by_pages( $date_from, $date_to );
		if ( empty( $clicks_by_page ) ) {
			return null;
		}

		foreach ( $views_by_page as $page_id => $view_by_page ) {

			if ( $location !== '' && $page_id != $location ) {
				continue;
			}
			// Count ctr if views and clicks > 0
			if ( ! empty( $views_by_page[$page_id]->times ) && ! empty( $clicks_by_page[$page_id]->times ) ) {
				$views += $views_by_page[$page_id]->times;
				$clicks += $clicks_by_page[$page_id]->times;
			}
		}

		if ( empty( $views ) || empty( $clicks ) ) {
			return null;
		}

		return $this->get_calculated_click_through_rate( $clicks, $views );
	}

	/**
	 * Get launcher, search and contact click totals
	 *
	 * @param $date_from
	 * @param $date_to
	 *
	 * @return array|null
	 */
	public function get_all_click_totals( $date_from, $date_to ) {

		$launcher_clicks = $this->get_invocations_count( $date_from, $date_to );
		$search_clicks   = $this->get_total_searches_count( $date_from, $date_to );
		$contact_clicks  = $this->get_contact_opened_count( $date_from, $date_to );

		if ( empty( $launcher_clicks ) && empty( $search_clicks ) && empty( $contact_clicks ) ) {
			return null;
		}

		return [
			'launcher' => (int)$launcher_clicks,
			'search'   => (int)$search_clicks,
			'contact'  => (int)$contact_clicks
		];
	}

	/**
	 * Get Contact tab open by pages
	 *
	 * @param $date_from
	 * @param $date_to
	 *
	 * @return array|null
	 */
	public function get_contact_open_by_pages( $date_from, $date_to ) {

		$result = $this->get_event_grouped_sum_by_where_clause( 'contact-tab-opened', 'click_1', 'page_id', $date_from, $date_to );
		if ( empty( $result ) || is_wp_error( $result ) ) {
			return null;
		}

		return $result;
	}

	/**
	 * Get contact form C.T.R.
	 *
	 * @param $date_from
	 * @param $date_to
	 * @param $location
	 *
	 * @return array|null
	 */
	public function get_contact_form_click_through_rate( $date_from, $date_to, $location='' ) {

		$where_data = is_numeric( $location ) ? ['page_id' => $location] : [];

		$views = $this->get_contact_opened_count( $date_from, $date_to, $where_data );
		$clicks = $this->get_custom_table_count( 'submissions', $date_from, $date_to, $where_data );

		if ( empty( $views ) && empty( $clicks ) ) {
			return null;
		}

		return $this->get_calculated_click_through_rate( $clicks, $views );
	}

	/**
	 * Get Searches count by pages
	 *
	 * @param $date_from
	 * @param $date_to
	 * @param $location
	 *
	 * @return array|null
	 */
	public function get_searches_count_by_page( $date_from, $date_to, $location='' ) {

		$where_data = is_numeric( $location ) ? ['page_id' => $location] : [];

		$result = $this->get_custom_table_grouped_rows_by_where_clause( 'search', 'page_id', 'obj_id', $date_from, $date_to, '', $where_data );
		if ( empty( $result ) || is_wp_error( $result ) ) {
			return null;
		}

		// Add posts titles
		$result = $this->add_post_titles( $result );

		// Sort result by times and title
		$result = $this->sort_results_by_two_columns( $result );

		return $result;
	}

	/**
	 * Get launcher open clicks by pages
	 *
	 * @param $date_from
	 * @param $date_to
	 *
	 * @return array|null
	 */
	public function get_launcher_open_by_pages( $date_from, $date_to ) {
		$result = $this->get_event_grouped_sum_by_where_clause( 'launcher-open', 'click_1', 'page_id', $date_from, $date_to );
		if ( empty( $result ) || is_wp_error( $result ) ) {
			return null;
		}
		return $result;
	}

	/**
	 * Get Impression by pages
	 *
	 * @param $date_from
	 * @param $date_to
	 *
	 * @return array|null
	 */
	public function get_impressions_by_pages( $date_from, $date_to ) {
		$result = $this->get_event_grouped_sum_by_where_clause( 'impressions', 'view', 'page_id', $date_from, $date_to );
		if ( empty( $result ) || is_wp_error( $result ) ) {
			return null;
		}
		return $result;
	}

	/**
	 * Get FAQ clicks by pages
	 *
	 * @param $date_from
	 * @param $date_to
	 *
	 * @return array|null
	 */
	public function get_faq_click_by_pages( $date_from, $date_to ) {

		// click_1 - main FAQs list, click_2 - search results FAQs list
		$result = $this->get_event_grouped_sum_by_where_clause( 'faq-item-click', ['click_1', 'click_2'], 'page_id', $date_from, $date_to );
		if ( empty( $result ) || is_wp_error( $result ) ) {
			return null;
		}
		return $result;
	}

	/**
	 * Get FAQ Group clicks by pages
	 *
	 * @param $date_from
	 * @param $date_to
	 *
	 * @return array|null
	 */
	public function get_faq_group_click_by_pages( $date_from, $date_to ) {
		$result = $this->get_event_grouped_sum_by_where_clause( 'faq-group-click', 'click_1', 'page_id', $date_from, $date_to );
		if ( empty( $result ) || is_wp_error( $result ) ) {
			return null;
		}
		return $result;
	}

	/**
	 * Get article clicks by pages
	 *
	 * @param $date_from
	 * @param $date_to
	 *
	 * @return array|null
	 */
	public function get_article_click_by_pages( $date_from, $date_to ) {
		$result = $this->get_event_grouped_sum_by_where_clause( 'article-item-click', 'click_1', 'page_id', $date_from, $date_to );
		if ( empty( $result ) || is_wp_error( $result ) ) {
			return null;
		}
		return $result;
	}

	/**
	 * Get clicks by FAQs
	 *
	 * @param $date_from
	 * @param $date_to
	 * @param $order
	 * @param $location
	 *
	 * @return array|null
	 */
	public function get_click_by_faqs( $date_from, $date_to, $order, $location='' ) {

		$where_data = is_numeric( $location ) ? ['page_id' => $location] : [];

		// click_1 - main FAQs list, click_2 - search results FAQs list
		$result = $this->get_event_grouped_sum_by_where_clause( 'faq-item-click', ['click_1', 'click_2'], 'object_id', $date_from, $date_to, $where_data, $order );
		if ( empty( $result ) || is_wp_error( $result ) ) {
			return null;
		}

		return $result;
	}

	/**
	 * Get clicks by articles
	 *
	 * @param $date_from
	 * @param $date_to
	 * @param $order
	 * @param $location
	 *
	 * @return array|null
	 */
	public function get_click_by_articles( $date_from, $date_to, $order, $location='' ) {

		$where_data = is_numeric( $location ) ? ['page_id' => $location] : [];

		$result = $this->get_event_grouped_sum_by_where_clause( 'article-item-click', 'click_1', 'object_id', $date_from, $date_to, $where_data, $order );
		if ( empty( $result ) || is_wp_error( $result ) ) {
			return null;
		}

		return $result;
	}

	/**
	 * Get frequent searched keywords
	 *
	 * @param $date_from
	 * @param $date_to
	 * @param $location
	 *
	 * @return array|null
	 */
	public function get_all_frequent_searches( $date_from, $date_to, $location='' ) {

		$where_data = is_numeric( $location ) ? ['page_id' => $location] : [];

		$result = $this->get_custom_table_grouped_rows_by_where_clause( 'search', 'keywords', 'title', $date_from, $date_to, '', $where_data );
		if ( empty( $result ) || is_wp_error( $result ) ) {
			return null;
		}
		return $result;
	}

	/**
	 * Get frequent searched keywords with No match found
	 *
	 * @param $date_from
	 * @param $date_to
	 * @param $search_type (faqs, posts, articles)
	 * @param $location
	 *
	 * @return array|null
	 */
	public function get_not_found_frequent_searches( $date_from, $date_to, $search_type, $location='' ) {

		if ( ! in_array( $search_type, [ 'faqs', 'posts', 'articles', 'all'] ) ) {
			return [];
		}

		// Searched Keywords Without Search Results
		if ( $search_type === 'all' ) {
			$where_data = array(
				'found_faqs' => array(
					'value'    => 0,
					'operator' => '<=',
					'format'   => '%d'
				),
				'found_posts' => array(
					'value'    => 0,
					'operator' => '<=',
					'format'   => '%d'
				),
				'found_articles' => array(
					'value'    => 0,
					'operator' => '<=',
					'format'   => '%d'
				),
			);
		// Searched Keywords Without Search Results for specific post type
		} else {
			$where_data = array(
				'found_' . $search_type => array(
					'value'    => 0,
					'operator' => '=',
					'format'   => '%d'
				)
			);
		}

		if ( is_numeric( $location ) ) {
			$where_data['page_id'] = $location;
		};

		$result = $this->get_custom_table_grouped_rows_by_where_clause( 'search', 'keywords', 'title', $date_from, $date_to, '', $where_data );
		if ( empty( $result ) || is_wp_error( $result ) ) {
			return null;
		}

		return $result;
	}

	/**
	 * Get frequent searched keywords with found counts
	 *
	 * @param $date_from
	 * @param $date_to
	 * @param $search_type (faqs, posts, articles)
	 * @param $location
	 *
	 * @return array|null
	 */
	public function get_searched_posts_count( $date_from, $date_to, $search_type, $location='' ) {

		if ( ! in_array( $search_type, [ 'faqs', 'posts', 'articles'] ) ) {
			return [];
		}

		$found_count_column = 'found_' . $search_type;

		$where_data = array(
			$found_count_column => array(
				'value'    => 0,
				'operator' => '>',
				'format'   => '%d'
			)
		);

		if ( is_numeric( $location ) ) {
			$where_data['page_id'] = $location;
		}

		$result = $this->get_custom_table_grouped_rows_by_where_clause( 'search', 'keywords', 'title', $date_from, $date_to, $found_count_column, $where_data, 20 );
		if ( empty( $result ) || is_wp_error( $result ) ) {
			return null;
		}

		// Sort result by times and title
		$result = $this->sort_results_by_two_columns( $result );

		return $result;
	}

	/**
	 * Get Engagement by pages
	 *
	 * @param $date_from
	 * @param $date_to
	 * @param $order
	 *
	 * @return array|null
	 */
	public function get_engagement_by_pages( $date_from, $date_to, $order='ASC' ) {

		$launcher_view_by_pages = $this->get_impressions_by_pages( $date_from, $date_to );
		if ( empty( $launcher_view_by_pages ) ) {
			return null;
		}

		$launcher_open_by_pages = $this->get_launcher_open_by_pages( $date_from, $date_to );
		if ( empty( $launcher_open_by_pages ) ) {
			$launcher_open_by_pages = array();
		}

		return $this->get_calculated_click_through_rate_by_pages( $launcher_view_by_pages, $launcher_open_by_pages, $order );
	}

	/**
	 * Get FAQ Engagement by pages
	 *
	 * @param $date_from
	 * @param $date_to
	 * @param $order
	 *
	 * @return array|null
	 */
	public function get_helpful_faqs_by_pages( $date_from, $date_to, $order='ASC' ) {

		$faq_view_by_pages = $this->get_launcher_open_by_pages( $date_from, $date_to );
		if ( empty( $faq_view_by_pages ) ) {
			return null;
		}

		$faq_open_by_pages = $this->get_faq_group_click_by_pages( $date_from, $date_to );
		if ( empty( $faq_open_by_pages ) ) {
			$faq_open_by_pages = array();
		}

		return $this->get_calculated_click_through_rate_by_pages( $faq_view_by_pages, $faq_open_by_pages, $order );
	}

	/**
	 * Get calculated click-through rate by pages
	 *
	 * @param $views_by_pages
	 * @param $clicks_by_pages
	 * @param $order
	 * @param $precision
	 *
	 * @return array
	 */
	private function get_calculated_click_through_rate_by_pages( $views_by_pages, $clicks_by_pages, $order='ASC', $precision=1 ) {

		$result = array();

		foreach ( $views_by_pages as $page_id => $views_by_page ) {
			if ( array_key_exists( $page_id, $clicks_by_pages ) && ! empty( $views_by_page->times ) && ! empty( $clicks_by_pages[$page_id]->times ) ) {
				$ctr_rate = $clicks_by_pages[$page_id]->times / $views_by_page->times;
				$ctr_rate = round( ( $ctr_rate * 100 ), $precision );
				$open_clicks = $clicks_by_pages[$page_id]->times;
			} else {
				$ctr_rate = 0;
				$open_clicks = 0;
			}

			$result[$page_id] = new stdClass();

			$result[$page_id]->obj_id  = $page_id;
			$result[$page_id]->ctr     = $ctr_rate;
			$result[$page_id]->title   = $views_by_page->title;
			$result[$page_id]->clicks  = $open_clicks;
			$result[$page_id]->views   = $views_by_page->times;
		}

		// Sort result by CTR and title
		$result = $this->sort_results_by_two_columns( $result, $order, $order, 'ctr', 'views' );

		return $result;
	}

	/**
	 * Get calculated click-through rate
	 *
	 * @param $clicks
	 * @param $views
	 * @param int $precision
	 *
	 * @return array
	 */
	private function get_calculated_click_through_rate( $clicks, $views, $precision=1 ) {

		$ctr = 0;

		// avoid division by zero
		if ( ! empty( $views ) ) {
			$ctr = $clicks / $views;
			$ctr = round( ( $ctr * 100 ), $precision );
		}

		return array(
			'ctr'    => $ctr,
			'views'  => (int) $views,
			'clicks' => (int) $clicks
		);
	}

	/**
	 * Get total search count
	 *
	 * @param $date_from
	 * @param $date_to
	 * @param $location
	 *
	 * @return int|null
	 */
	public function get_total_searches_count( $date_from='', $date_to='', $location='' ) {

		$date_from = empty( $date_from ) ? date('Y-m-d', 0) : $date_from;
		$date_to = empty( $date_to ) ? date('Y-m-d') : $date_to;

		$where_data = is_numeric( $location ) ? ['page_id' => $location] : [];

		return $this->get_custom_table_count( 'search', $date_from, $date_to, $where_data );
	}

	/**
	 * Get custom_table count By date
	 *
	 * @param $table
	 * @param $date_from
	 * @param $date_to
	 * @param $where_data
	 *
	 * @return int|null
	 */
	public function get_custom_table_count( $table, $date_from, $date_to, $where_data=[] ) {
		global $wpdb;

		// Allow only HD tables ephd_search, ephd_submissions, ephd_analytics
		if ( ! in_array( $table, ['search', 'submissions', 'analytics'] ) ) {
			EPHD_Logging::add_log( 'DB failure: Invalid table name: ' . $table );
			return null;
		}

		$where_clause = '';
		if ( ! empty( $where_data ) ) {
			$where_clause = $this->get_where_clause( $where_data );
			if ( empty( $where_clause ) ) {
				return null;
			}
			$where_clause .= ' AND ';
		}
		$where_clause .= " DATE(submit_date) BETWEEN %s AND %s ";

		$result = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->prefix}ephd_{$table} WHERE {$where_clause}", $date_from, $date_to ) );
		if ( $result === null && ! empty( $wpdb->last_error ) ) {
			EPHD_Logging::add_log( "DB failure: " . $wpdb->last_error );
			return null;
		}
		if ( empty( $result ) ) {
			return null;
		}

		return (int)$result;
	}


	/**************************************************************************************************************************
	 *
	 *                     Stats support functions
	 *
	 *************************************************************************************************************************/

	/**
	 * Get sum rows by date, event name and event value
	 *
	 * @param $event_name
	 * @param $column_names
	 * @param $date_from
	 * @param $date_to
	 * @param $where_data
	 *
	 * @return string|WP_Error|null
	 */
	public function get_event_sum_by_where_clause( $event_name, $column_names, $date_from, $date_to, $where_data=[] ) {
		global $wpdb;

		$event_meta = $this->get_event_meta( $event_name );
		if ( empty( $event_meta ) || empty( $event_meta['type_id'] )) {
			return null;
		}

		if ( empty( $column_names ) ) {
			return null;
		}

		$select_statement = is_array( $column_names ) ? " SUM(" . implode( ") + SUM(", $column_names ) . ") AS times " : " SUM(" . $column_names . ") AS times ";

		$column_format = $this->get_column_format();

		$where_clause = '';
		if ( ! empty( $where_data ) ) {
			$where_clause = $this->get_where_clause( $where_data );
			if ( empty( $where_clause ) ) {
				return null;
			}
			$where_clause .= ' AND ';
		}
		$where_clause .= " event_type_id = {$column_format['event_type_id']} AND DATE(submit_date) BETWEEN %s AND %s ";

		$result = $wpdb->get_var( $wpdb->prepare( "SELECT {$select_statement} FROM {$this->table_name} WHERE {$where_clause}", $event_meta['type_id'], $date_from, $date_to ) );
		if ( $result === null && ! empty($wpdb->last_error) ) {
			$wpdb_last_error = $wpdb->last_error;   // add_log changes last_error so store it first
			EPHD_Logging::add_log( "DB failure: ", $wpdb_last_error );
			return new WP_Error('DB failure', $wpdb_last_error);
		}

		return $result;
	}

	/**
	 * Get event sum rows grouped by custom column
	 *
	 * @param $event_name
	 * @param $column_names
	 * @param $group_by
	 * @param $date_from
	 * @param $date_to
	 * @param $where_data
	 * @param $order
	 * @param $limit
	 *
	 * @return array|WP_Error|null
	 */
	public function get_event_grouped_sum_by_where_clause( $event_name, $column_names, $group_by, $date_from, $date_to, $where_data=[], $order='DESC', $limit=100 ) {
		global $wpdb;

		$event_meta = $this->get_event_meta( $event_name );
		if ( empty( $event_meta ) || empty( $event_meta['type_id'] ) || empty( $event_meta['object'] ) ) {
			return null;
		}

		if ( empty( $column_names ) ) {
			return null;
		}

		$select_statement = is_array( $column_names ) ? " {$group_by} as obj_id, SUM(" . implode( ") + SUM(", $column_names ) . ") AS times " : " {$group_by} as obj_id, SUM(" . $column_names . ") AS times ";

		$column_format = $this->get_column_format();

		$where_clause = '';
		if ( ! empty( $where_data ) ) {
			$where_clause = $this->get_where_clause( $where_data );
			if ( empty( $where_clause ) ) {
				return null;
			}
			$where_clause .= ' AND ';
		}
		$where_clause .= " event_type_id = {$column_format['event_type_id']} AND DATE(submit_date) BETWEEN %s AND %s ";

		$result = $wpdb->get_results( $wpdb->prepare( "SELECT {$select_statement} FROM {$this->table_name} WHERE {$where_clause} GROUP BY {$group_by} ORDER BY times {$order} LIMIT {$limit}", $event_meta['type_id'], $date_from, $date_to ), OBJECT_K );
		if ( ! empty( $wpdb->last_error ) ) {
			$wpdb_last_error = $wpdb->last_error;
			EPHD_Logging::add_log("DB failure: ", $wpdb_last_error);
			return new WP_Error('DB failure', $wpdb_last_error);
		}

		// Add posts titles
		$result = $this->add_post_titles( $result, $event_meta['object'] );

		// Sort result by times and title
		$result = $this->sort_results_by_two_columns( $result, $order );

		return $result;
	}

	/**
	 * Get grouped results from custom tables by where clause
	 *
	 * @param $table
	 * @param $group_by
	 * @param $group_by_name - returned column name
	 * @param $date_from
	 * @param $date_to
	 * @param $sum_column
	 * @param $where_data
	 * @param $limit
	 *
	 * @return array|WP_Error|null
	 */
	public function get_custom_table_grouped_rows_by_where_clause( $table, $group_by, $group_by_name, $date_from, $date_to, $sum_column='', $where_data=[], $limit=100 ) {
		global $wpdb;

		// Allow only HD tables ephd_search, ephd_submissions, ephd_analytics
		if ( ! in_array( $table, ['search', 'submissions', 'analytics'] ) ) {
			EPHD_Logging::add_log( 'DB failure: Invalid table name: ' . $table );
			return null;
		}

		$where_clause = '';
		if ( ! empty( $where_data ) ) {
			$where_clause = $this->get_where_clause( $where_data );
			if ( empty( $where_clause ) ) {
				return null;
			}
			$where_clause .= ' AND ';
		}
		$where_clause .= " DATE(submit_date) BETWEEN %s AND %s ";

		$select_sum_statement = '';
		if ( ! empty( $sum_column ) ) {
			$select_sum_statement = ", SUM({$sum_column}) AS matching";
		}

		$result = $wpdb->get_results( $wpdb->prepare( "SELECT {$group_by} AS {$group_by_name}, count(*) AS times {$select_sum_statement} FROM {$wpdb->prefix}ephd_{$table} " .
		                                              "WHERE {$where_clause} GROUP BY {$group_by_name} ORDER BY times DESC, {$group_by_name} ASC LIMIT {$limit}", $date_from, $date_to ), OBJECT_K );
		if ( ! empty( $wpdb->last_error ) ) {
			$wpdb_last_error = $wpdb->last_error;
			EPHD_Logging::add_log("DB failure: ", $wpdb_last_error);
			return new WP_Error('DB failure', $wpdb_last_error);
		}

		return $result;
	}

	/**
	 * Generate where clause from data
	 *
	 * @param array $where_data
	 *
	 * @return false|string
	 */
	public function get_where_clause( array $where_data ) {
		global $wpdb;

		if ( empty( $where_data ) ) {
			return false;
		}

		$values = array();
		$where = ' ';
		$first = true;
		foreach( $where_data as $column => $value ) {
			if ( is_array( $value ) ) {
				if ( ! isset( $value['value'] ) ) {
					EPHD_Logging::add_log( 'Wrong WHERE clause for ' . $this->table_name . ' table. Empty value for the column: ' . $column );
					return false;
				}
				$values[] = $value['value'];
				$operator = isset( $value['operator'] ) ? $value['operator'] : '=';
			} else {
				$values[] = $value;
				$operator = '=';
			}

			$format = $this->get_column_format();
			if ( isset( $value['format'] ) ) {
				$format[$column] = $value['format'];
			}

			$where .= ($first ? '' : ' AND ') . esc_sql( $column ) . ' ' . $operator . ' ' . $format[$column];
			$first = false;
		}

		return $wpdb->prepare( $where, $values );
	}



	/**************************************************************************************************************************
	 *
	 *                     Utility functions
	 *
	 *************************************************************************************************************************/

	/**
	 * Get Event Meta By name
	 *
	 * @param $event_name
	 *
	 * @return false|mixed
	 */
	public function get_event_meta( $event_name ) {
		if ( ! isset( $this->events_meta[$event_name] ) ) {
			EPHD_Logging::add_log( 'Analytics: Event: "' . $event_name . '" does not exist' );
			return false;
		}

		$event_meta = $this->events_meta[$event_name];
		if ( ! isset( $event_meta['type_id'] ) || empty( $event_meta['aggregation'] ) || empty( $event_meta['expiration'] ) ) {
			EPHD_Logging::add_log( 'Analytics: Could not retrieve Event Meta for: ' . $event_name );
			return false;
		}

		return $event_meta;
	}

	/**
	 * Add posts titles to analytics results
	 *
	 * @param $result
	 * @param $object_type
	 *
	 * @return array
	 */
	public function add_post_titles( $result, $object_type='post' ) {

		$faqs_db_handler = new EPHD_FAQs_DB();

		// Add column title
		foreach ( $result as $row ) {
			// continue if title already exists
			if ( ! empty( $row->title ) ) {
				continue;
			}

			// get faq article title from custom table
			if ( $object_type == 'faq' && ! empty( $row->obj_id ) ) {
				$title = $faqs_db_handler->get_faq_question_by_id( $row->obj_id );

			// get post title from wp_posts
			} else {
				$title = get_the_title( $row->obj_id );
			}

			if ( empty( $title ) ||  'Untitled' == $title ) {
				$title = ucfirst( $object_type . ' ' . $row->obj_id );
			}
			// Check is Home page and add title
			if ( $row->obj_id == EPHD_Config_Specs::HOME_PAGE ) {
				$title = __( 'Home Page', 'help-dialog' );
			}
			$row->title = $title;
		}
		return $result;
	}

	/**
	 * Sort analytics results array by two columns (string or numeric types)
	 *
	 * @param $results
	 * @param $order_1
	 * @param $order_2
	 * @param $column_1
	 * @param $column_2
	 *
	 * @return array
	 */
	private function sort_results_by_two_columns( $results, $order_1='DESC', $order_2='ASC', $column_1='times', $column_2='title' ) {

		if ( empty( $results ) ) {
			return [];
		}

		uasort( $results, function ( $a, $b ) use ( $column_2, $column_1, $order_1, $order_2 ) {
			if ( $a->{$column_1} == $b->{$column_1} ) {
				if ( $order_2 == 'ASC' ) {
					if ( is_numeric( $a->{$column_2} ) && is_numeric( $b->{$column_2} ) ) {
						return $a->{$column_2} > $b->{$column_2} ? 1 : -1;
					}
					return strcasecmp( $a->{$column_2}, $b->{$column_2} );
				}
				if ( $order_2 == 'DESC' ) {
					if ( is_numeric( $a->{$column_2} ) && is_numeric( $b->{$column_2} ) ) {
						return $a->{$column_2} < $b->{$column_2} ? 1 : -1;
					}
					return strcasecmp( $b->{$column_2}, $a->{$column_2} );
				}
			}
			if ( $order_1 == 'ASC' ) {
				if ( is_numeric( $a->{$column_1} ) && is_numeric( $b->{$column_1} ) ) {
					return $a->{$column_1} > $b->{$column_1} ? 1 : -1;
				}
				return strcasecmp( $a->{$column_1}, $b->{$column_1} );
			}
			if ( $order_1 == 'DESC' ) {
				if ( is_numeric( $a->{$column_1} ) && is_numeric( $b->{$column_1} ) ) {
					return $a->{$column_1} < $b->{$column_1} ? 1 : -1;
				}
				return strcasecmp( $b->{$column_1}, $a->{$column_1} );
			}
		});

		return $results;
	}

	/**************************************************************************************************************************
	 *
	 *                     OTHER
	 *
	 *************************************************************************************************************************/

	/**
	 * Get list of analytic's fields with their translated titles which need to display as columns
	 *
	 * @return array
	 */
	public static function get_analytics_column_fields() {
		return [
			'event_id'      => __( 'Event ID', 'help-dialog' ),
			'submit_date'   => __( 'Event Date', 'help-dialog' ),
			'widget_id'     => __( 'Widget ID', 'help-dialog' ),
			'page_id'       => __( 'Page ID', 'help-dialog' ),
			'object_id'     => __( 'Object ID', 'help-dialog' ),
			'event_type_id' => __( 'Event Type ID', 'help-dialog' ),
			'view'          => __( 'Event Value 1', 'help-dialog' ),
			'click_1'       => __( 'Event Value 2', 'help-dialog' ),
			'click_2'       => __( 'Event Value 3', 'help-dialog' ),
			'other_1'       => __( 'Event Value 4', 'help-dialog' ),
			'other_2'       => __( 'Event Value 5', 'help-dialog' )
		];
	}

	/**
	 * Filter output of analytics events
	 *
	 * @param $events
	 *
	 * @return array
	 */
	private function filter_output( $events ) {

		foreach ( $events as $index => $data ) {

			// change format of showing event datetime
			$events[$index]->submit_date = date( 'M j, Y @ g:ia', strtotime( $data->submit_date ) );
		}

		return $events;
	}

	/**
	 * Create the table
	 *
	 * @access  public
	 */
	public function create_table() {
		global $wpdb;

		$collate = $wpdb->has_cap( 'collation' ) ? $wpdb->get_charset_collate() : '';

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
                    event_id      bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    submit_date   date NOT NULL,
                    widget_id     smallint(5) UNSIGNED NOT NULL DEFAULT 0,
                    page_id       bigint(20) UNSIGNED NOT NULL DEFAULT 0,
                    object_id     bigint(20) UNSIGNED NOT NULL DEFAULT 0,
                    event_type_id smallint(5) UNSIGNED NOT NULL,
                    view          int(11) UNSIGNED NOT NULL DEFAULT 0,
                    click_1       int(11) UNSIGNED NOT NULL DEFAULT 0,
                    click_2       int(11) UNSIGNED NOT NULL DEFAULT 0,
                    other_1       int(11) UNSIGNED NOT NULL DEFAULT 0,
                    other_2       int(11) UNSIGNED NOT NULL DEFAULT 0,
	                PRIMARY KEY (event_id),
	                KEY ix_ephd_date (submit_date),
	                KEY ix_ephd_widget_id (widget_id),
	                KEY ix_ephd_page_id (page_id),
	                KEY ix_ephd_object_id (object_id),
	                KEY ix_ephd_event_type_id (event_type_id)
		) " . $collate . ";";

		dbDelta( $sql );
	}
}