<?php
namespace BetterLinks\Traits;

trait Clicks {

	private static $transient_timeout = MINUTE_IN_SECONDS * 30; // 30 MINUTES

	/**
	 * Get Clicks data within a certain time limit
	 *
	 * @param string $from The start time.
	 * @param string $to The end time.
	 *
	 * @return array
	 */
	public function get_clicks_data( $from, $to ) {
		$results = \BetterLinks\Helper::get_clicks_by_date( $from, $to );
		return $results;
	}

	/**
	 * Get transient key for cached analytics data
	 *
	 * @param string     $key The unique identifier for the transient key
	 * @param string     $from The start time.
	 * @param string     $to The end time.
	 * @param string|int $id Clicks id.
	 *
	 * @return string
	 */
	private static function get_transient_key( $key, $from, $to, $id = null ) {
		$transient_key = str_replace( '-', '_', $from ) . '_' . str_replace( '-', '_', $to );
		if ( $id ) {
			$transient_key .= '_' . $id;
		}
		return $key . $transient_key;
	}

	/**
	 * Get Analytics Graph Data
	 *
	 * @param $from The start time.
	 * @param $to The end time.
	 *
	 * @return array Array of total unique clicks and total clicks.
	 */
	public function get_analytics_graph_data( $from, $to ) {
		$transient_key = self::get_transient_key( 'btl_analytics_graph_', $from, $to );
		if ( $results = get_transient( $transient_key ) ) {
			return $results;
		}

		global $wpdb;
		$query        = "SELECT count(id) as click_count, DATE(created_at) as c_date FROM {$wpdb->prefix}betterlinks_clicks 
            WHERE created_at  BETWEEN '{$from} 00:00:00' AND '{$to} 23:59:59' GROUP BY c_date ORDER BY c_date DESC";
		$total_counts = $wpdb->get_results( $query, ARRAY_A );

		$query         = "SELECT count(ip) as uniq_count, T1.c_date from ( SELECT ip, DATE( created_at ) as c_date FROM {$wpdb->prefix}betterlinks_clicks 
            WHERE created_at  BETWEEN '{$from} 00:00:00' AND '{$to} 23:59:59' GROUP BY `ip`, `c_date` ) as T1 GROUP BY T1.c_date ORDER BY T1.c_date DESC";
		$unique_counts = $wpdb->get_results( $wpdb->prepare( $query ), ARRAY_A );

		$results = array(
			'total_count'  => $total_counts,
			'unique_count' => $unique_counts,
		);
		set_transient( $transient_key, $results, self::$transient_timeout );
		return $results;
	}

	/**
	 * Get Analytics Graph Data by Tag ID
	 *
	 * @param $from The start time.
	 * @param $to The end time.
	 * @param $tag_id The Tag ID.
	 *
	 * @return array Array of total unique clicks and total clicks.
	 */
	public function get_analytics_graph_data_by_tag( $from, $to, $tag_id ) {
		$transient_key = self::get_transient_key( 'btl_analytics_graph_by_tag_', $from, $to, $tag_id );
		if ( $results = get_transient( $transient_key ) ) {
			return $results;
		}

		global $wpdb;
		$query        = "SELECT COUNT(c.id) as click_count, DATE(c.created_at) AS c_date FROM {$wpdb->prefix}betterlinks_clicks c 
							LEFT JOIN {$wpdb->prefix}betterlinks_terms_relationships tr ON tr.link_id=c.link_id 
							LEFT JOIN {$wpdb->prefix}betterlinks_terms t ON tr.term_id=t.id 
						WHERE t.term_type='tags' AND t.id='%d' AND c.created_at BETWEEN %s AND %s
						GROUP BY c_date ORDER BY c_date DESC";
		$total_counts = $wpdb->get_results( $wpdb->prepare( $query, $tag_id, "{$from} 00:00:00", "{$to} 23:59:59" ), ARRAY_A );

		$query         = "SELECT COUNT(ip) as uniq_count, T1.c_date FROM 
							( SELECT ip, DATE( created_at ) AS c_date FROM {$wpdb->prefix}betterlinks_clicks c
								LEFT JOIN {$wpdb->prefix}betterlinks_terms_relationships tr ON c.link_id=tr.link_id 
								LEFT JOIN {$wpdb->prefix}betterlinks_terms t ON tr.term_id=t.id 
									WHERE t.term_type='tags' AND t.id='%d' AND created_at BETWEEN %s AND %s    
								GROUP BY `ip`, `c_date` ) AS T1  
							GROUP BY T1.c_date ORDER BY T1.c_date DESC";
		$unique_counts = $wpdb->get_results( $wpdb->prepare( $query, $tag_id, "{$from} 00:00:00", "{$to} 23:59:59" ), ARRAY_A );

		$results = array(
			'total_count'  => $total_counts,
			'unique_count' => $unique_counts,
		);
		set_transient( $transient_key, $results, self::$transient_timeout );
		return $results;
	}

	/**
	 * Returns the unique analytics data by tag
	 *
	 * @return array Array of unique analytics by tag
	 */
	public function get_analytics_unique_list_by_tag( $from, $to, $id ) {
		$transient_key = self::get_transient_key( 'btl_analytics_unique_list_by_tag_', $from, $to, $id );
		if ( $results = get_transient( $transient_key ) ) {
			return $results;
		}

		global $wpdb;
		$prefix = $wpdb->prefix;

		$query = "SELECT id as link_id, link_title, short_url, target_url from {$prefix}betterlinks as links right join (select distinct link_id from {$prefix}betterlinks_clicks where created_at between '{$from} 00:00:00' and '{$to} 23:59:59') as clicks on clicks.link_id=links.id right join (select tr.link_id from {$prefix}betterlinks_terms t left join {$prefix}betterlinks_terms_relationships tr on t.id=tr.term_id where t.term_type='tags' and t.id='{$id}') tl on links.id=tl.link_id where id!=''";

		$results = $wpdb->get_results( $query, ARRAY_A );

		set_transient( $transient_key, $results, self::$transient_timeout );
		return $results;
	}

	/**
	 * Returns the unique analytics clicks
	 *
	 * @return array Array of unique analytics
	 */
	public function get_analytics_unique_list( $from, $to ) {
		$transient_key = self::get_transient_key( 'btl_analytics_unique_list_', $from, $to );
		if ( $results = get_transient( $transient_key ) ) {
			return $results;
		}
		global $wpdb;
		$prefix = $wpdb->prefix;

		$query = "SELECT id as link_id, link_title, short_url, target_url from {$prefix}betterlinks as links right join (select distinct link_id from {$prefix}betterlinks_clicks where created_at between '{$from} 00:00:00' and '{$to} 23:59:59') as clicks on clicks.link_id=links.id order by links.id desc";

		$results = $wpdb->get_results( $query, ARRAY_A );

		set_transient( $transient_key, $results, self::$transient_timeout );
		return $results;
	}

	/**
	 * Returns individual analytics clicks within a time limit
	 *
	 * @param int|string $id Clicks id.
	 * @param string     $from The start time.
	 * @param string     $to The end time.
	 *
	 * @return array Array of individual analytics clicks.
	 */
	public function get_individual_analytics_clicks( $id, $from, $to ) {
		$transient_key = self::get_transient_key( 'btl_individual_analytics_clicks_', $from, $to, $id );
		if ( $results = get_transient( $transient_key ) ) {
			return $results;
		}
		global $wpdb;
		$fields = 'ID, link_id, ip, browser, referer, os, device, created_at';

		$query   = "SELECT {$fields} FROM {$wpdb->prefix}betterlinks_clicks WHERE link_id={$id} AND created_at BETWEEN '{$from} 00:00:00' AND '{$to} 23:59:59' ORDER BY created_at DESC";
		$results = $wpdb->get_results( $query, ARRAY_A );

		set_transient( $transient_key, $results, self::$transient_timeout );
		return $results;
	}

	/**
	 * Returns individual link details
	 *
	 * @param int|string $id link id.
	 *
	 * @return Object Object of individual link details.
	 */
	public function get_individual_link_details( $id ) {
		global $wpdb;
		$query = "SELECT link_title, short_url, target_url FROM {$wpdb->prefix}betterlinks where id={$id}";
		return $wpdb->get_row( $query );
	}
}
