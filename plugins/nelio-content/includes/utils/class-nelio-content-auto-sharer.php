<?php
/**
 * This file shares content automatically.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

use function Nelio_Content\Helpers\get;
use function Nelio_Content\Helpers\key_by;

/**
 * This class implements all the functions used for sharing content automatically on social media.
 */
class Nelio_Content_Auto_Sharer {

	const SCHEDULE_WEEK      = 'nelio_content_social_automations_schedule_week';
	const RESET_MESSAGES     = 'nelio_content_social_automations_reset_social_messages';
	const MAX_SHARES_PER_DAY = 30;

	protected static $instance;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if
		return self::$instance;
	}//end instance()

	public function init() {
		add_action( 'init', array( $this, 'enable_cron_tasks' ) );
	}//end init()

	public function enable_cron_tasks() {
		add_action( self::SCHEDULE_WEEK, array( $this, 'schedule_week' ) );
		add_action( self::RESET_MESSAGES, array( $this, 'schedule_week' ) );
		$this->add_schedule_week_cron();
	}//end enable_cron_tasks()


	public function reset() {
		delete_transient( 'nc_automation_groups' );
		delete_option( 'nc_reshare_last_day' );
		wp_schedule_single_event( time(), self::RESET_MESSAGES, array( time() ) );
	}//end reset()

	public function schedule_week() {

		$today       = gmdate( 'Y-m-d', time() );
		$weekdays    = array( 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday' );
		$end_of_week = ( get_option( 'start_of_week', 0 ) + 6 ) % 7;
		$end_of_week = $weekdays[ $end_of_week ];

		$last_day_to_schedule = gmdate( 'Y-m-d', strtotime( "next {$end_of_week}" ) );
		$last_scheduled_day   = max( $today, get_option( 'nc_reshare_last_day', $today ) );
		if ( $last_scheduled_day >= $last_day_to_schedule ) {
			return;
		}//end if

		$days_to_schedule = $this->diff_days( $today, $last_day_to_schedule );
		$posts            = $this->get_posts_for_resharing( $days_to_schedule );
		if ( empty( $posts ) ) {
			return;
		}//end if

		$posts_per_day = $this->array_split( $posts, $days_to_schedule );
		foreach ( $posts_per_day as $posts ) {
			$last_scheduled_day = gmdate( 'Y-m-d', strtotime( $last_scheduled_day . ' +1 day' ) );
			$this->schedule_day( $last_scheduled_day, is_array( $posts ) ? $posts : array( $posts ) );
		}//end foreach
		update_option( 'nc_reshare_last_day', $last_scheduled_day );

	}//end schedule_week()

	/**
	 * This function requests the cloud to generate all the messages for a given
	 * day, using the given list of posts.
	 *
	 * @param string $day   day to schedule.
	 * @param array  $posts list of posts used to "fill" the day.
	 *
	 * @since  1.3.0
	 * @access public
	 */
	public function schedule_day( $day, $posts ) {
		if ( empty( $posts ) ) {
			return;
		}//end if

		$posts = array_map(
			function ( $post ) {
				$post['content'] = '';
				return $post;
			},
			$posts
		);

		$data = array(
			'method'    => 'POST',
			'timeout'   => apply_filters( 'nelio_content_request_timeout', 30 ),
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
			'body'      => wp_json_encode(
				array(
					'day'   => $day,
					'posts' => $posts,
				)
			),
		);

		$url = sprintf(
			nc_get_api_url( '/site/%s/social/auto', 'wp' ),
			nc_get_site_id()
		);
		wp_remote_request( $url, $data );

	}//end schedule_day()

	private function add_schedule_week_cron() {
		if ( wp_next_scheduled( self::SCHEDULE_WEEK ) ) {
			return;
		}//end if

		$time     = sprintf(
			'%02d:%02d:00',
			wp_rand( 0, 4 ),
			wp_rand( 0, 59 )
		);
		$today    = gmdate( 'Y-m-d', time() ) . 'T' . $time;
		$tomorrow = strtotime( $today . ' +1 day' );
		wp_schedule_event( $tomorrow, 'daily', self::SCHEDULE_WEEK );
	}//end add_schedule_week_cron()

	private function get_posts_for_resharing( $num_of_days ) {
		global $wpdb;
		$queries = array(
			'top' => $this->get_top_posts_query(),
			'1mo' => $this->get_recent_posts_query( 1 ),
			'3mo' => $this->get_recent_posts_query( 3 ),
			'6mo' => $this->get_recent_posts_query( 6 ),
			'1yr' => $this->get_recent_posts_query( 12 ),
			'2yr' => $this->get_recent_posts_query( 24 ),
			'fbq' => $this->get_fallback_query(),
		);

		$post_ids        = array();
		$total_posts     = $num_of_days * self::MAX_SHARES_PER_DAY;
		$posts_per_block = ceil( $total_posts / count( $queries ) );
		foreach ( $queries as $key => $query ) {
			$query    = $this->exclude_post_ids( $post_ids, $query );
			$count    = 'fbq' !== $key ? $posts_per_block : $total_posts - count( $post_ids );
			$query    = $this->limit_post_count( $count, $query );
			$post_ids = array_merge(
				$post_ids,
				array_map( 'absint', $wpdb->get_col( $query ) ) // phpcs:ignore
			);
		}//end foreach

		/**
		 * Filters the post IDs that will be reshared.
		 *
		 * @param array  $post_ids List of post IDs.
		 * @param number $days     Number of days to schedule.
		 *
		 * @since 3.0.0
		 */
		$post_ids = apply_filters( 'nelio_content_posts_to_reshare', $post_ids, $num_of_days );

		if ( empty( $post_ids ) ) {
			return array();
		}//end if

		if ( count( $post_ids ) < $num_of_days ) {
			$post_ids = array_fill( 0, $num_of_days, $post_ids );
			$post_ids = array_merge( ...$post_ids );
			$post_ids = array_slice( $post_ids, 0, $num_of_days );
		}//end if
		shuffle( $post_ids );

		$post_helper = Nelio_Content_Post_Helper::instance();
		return array_map( array( $post_helper, 'post_to_aws_json' ), $post_ids );
	}//end get_posts_for_resharing()

	private function get_top_posts_query() {
		$query  = $this->get_basic_query();
		$joins  = array();
		$wheres = array();

		$key      = '_nc_engagement_total';
		$joins[]  = $this->left_meta_join( 'engtot', $key );
		$wheres[] = sprintf( '(engtot.meta_value >= %d)', $this->get_meta_value_threshold( $key ) );

		$settings = Nelio_Content_Settings::instance();
		$ga_view  = $settings->get( 'ga4_property_id' );
		if ( ! empty( $ga_view ) ) {
			$key      = "_nc_pageviews_total_{$ga_view}";
			$joins[]  = $this->left_meta_join( 'googanal', $key );
			$wheres[] = sprintf( '(googanal.meta_value >= %d)', $this->get_meta_value_threshold( $key ) );
		}//end if

		$join  = implode( ' ', $joins );
		$where = 'AND (' . implode( ' OR ', $wheres ) . ')';

		$query = str_replace( '{{joins}}', $join, $query );
		$query = str_replace( '{{wheres}}', $where, $query );
		return $query;
	}//end get_top_posts_query()

	private function get_recent_posts_query( $max_months ) {
		$today = gmdate( 'Y-m-d', time() );
		$date  = gmdate( 'Y-m-d', strtotime( "{$today} - {$max_months} months" ) );
		$where = sprintf( 'AND \'%s\' <= p.post_date_gmt', esc_sql( $date ) );

		$query = $this->get_basic_query();
		$query = str_replace( '{{joins}}', '', $query );
		$query = str_replace( '{{wheres}}', $where, $query );
		return $query;
	}//end get_recent_posts_query()

	private function get_fallback_query() {
		$query = $this->get_basic_query();
		$query = str_replace( '{{joins}}', '', $query );
		$query = str_replace( '{{wheres}}', '', $query );
		return $query;
	}//end get_fallback_query()

	private function get_basic_query() {
		static $query;
		if ( ! empty( $query ) ) {
			return $query;
		}//end if

		global $wpdb;
		$query = '' .
			"SELECT DISTINCT ID FROM {$wpdb->posts} p {{joins}}" .
			'WHERE p.post_status = \'publish\' AND p.ID NOT IN {{pids}} {{wheres}} ' .
			'ORDER BY RAND() ' .
			'LIMIT 0, {{post_count}}';
		$query = $this->add_post_type_filter( $query );
		$query = $this->add_automation_group_filter( $query );
		$query = $this->add_share_filter( $query );
		$query = $this->add_end_mode_filter( $query );

		return $query;
	}//end get_basic_query()

	private function add_post_type_filter( $query ) {
		$settings   = Nelio_Content_Settings::instance();
		$post_types = array_map(
			function ( $type ) {
				$type = esc_sql( sanitize_text_field( $type ) );
				return "'{$type}'";
			},
			$settings->get( 'calendar_post_types' )
		);

		$where = 'AND p.post_type IN (' . implode( ',', $post_types ) . ')';

		return str_replace( '{{wheres}}', "{$where} {{wheres}}", $query );
	}//end add_post_type_filter()

	private function add_automation_group_filter( $query ) {
		$groups = nc_get_automation_groups();
		$groups = array_filter(
			$groups,
			function( $g ) {
				return (
					! empty( $g['priority'] ) &&
					array_reduce(
						$g['profileSettings'],
						function( $carry, $ps ) {
							if ( $carry ) {
								return $carry;
							}//end if
							return ! empty( $ps['enabled'] ) && ! empty( $ps['reshare'] ) && ! empty( $ps['reshare']['enabled'] );
						},
						false
					)
				);
			}
		);

		if ( empty( $groups ) ) {
			return str_replace( '{{wheres}}', 'AND FALSE', $query );
		}//end if

		$groups = key_by( $groups, 'id' );
		if ( ! empty( $groups['universal'] ) ) {
			$publication = get( $groups['universal'], 'publication.type', 'always' );
			if ( 'always' === $publication ) {
				return $query;
			}//end if
		}//end if

		$term_map   = $this->get_term_taxonomy_ids_from_groups( $groups );
		$tax_tables = array();
		if ( ! empty( $term_map ) ) {
			$taxs  = array_keys( $term_map );
			$names = array_map(
				function( $i ) {
					++$i;
					return "tr{$i}";
				},
				array_keys( $taxs )
			);

			$tax_tables = array_combine( $taxs, $names );
		}//end if

		$query  = $this->join_term_tables( $term_map, $tax_tables, $query );
		$today  = gmdate( 'Y-m-d', time() );
		$wheres = array_map(
			function( $group ) use ( $today, &$term_map, &$tax_tables ) {
				$where = array();

				$post_type = get( $group, 'postType' );
				if ( ! empty( $post_type ) ) {
					$where[] = sprintf( "p.post_type = '%s'", esc_sql( $post_type ) );
				}//end if

				$taxonomies = get( $group, 'taxonomies', array() );
				if ( ! empty( $taxonomies ) ) {
					$conds   = array_map(
						function( $tax, $terms ) use ( &$term_map, &$tax_tables ) {
							$terms = array_map(
								function ( $term ) use ( $tax, &$term_map ) {
									return get( $term_map, array( $tax, $term ), 0 );
								},
								$terms
							);
							$terms = array_values( array_filter( $terms ) );
							if ( empty( $terms ) ) {
								return '';
							}//end if
							$table = $tax_tables[ $tax ];
							return sprintf( "{$table}.term_taxonomy_id IN (%s)", implode( ',', $terms ) );
						},
						array_keys( $taxonomies ),
						array_values( $taxonomies )
					);
					$where[] = '(' . implode( ' AND ', array_filter( $conds ) ) . ')';
				}//end if

				$authors = get( $group, 'authors', array() );
				$authors = array_values( array_filter( array_map( 'absint', $authors ) ) );
				if ( ! empty( $authors ) ) {
					$where[] = 'p.post_author IN (' . implode( ', ', $authors ) . ')';
				}//end if

				if ( 'max-age' === get( $group, 'publication.type', 'always' ) ) {
					$days    = get( $group, 'publication.days', 60 );
					$date    = gmdate( 'Y-m-d', strtotime( "{$today} - {$days} days" ) );
					$where[] = sprintf( '\'%s\' <= p.post_date_gmt', esc_sql( $date ) );
				}//end if

				return empty( $where ) ? '' : '(' . implode( ' AND ', $where ) . ')';
			},
			$groups
		);
		$where  = implode( ' OR ', array_filter( $wheres ) );
		return str_replace( '{{wheres}}', "AND ({$where}) {{wheres}}", $query );
	}//end add_automation_group_filter()

	private function get_term_taxonomy_ids_from_groups( $groups ) {
		$taxonomies = array_reduce(
			$groups,
			function( $result, $g ) {
				$gt = get( $g, 'taxonomies', array() );
				foreach ( $gt as $tax => $terms ) {
					$terms          = array_values( array_filter( array_map( 'absint', $terms ) ) );
					$result[ $tax ] = isset( $result[ $tax ] ) ? $result[ $tax ] : array();
					foreach ( $terms as $term ) {
						$result[ $tax ][ $term ] = 0;
					}//end foreach
				}//end foreach
				return $result;
			},
			array()
		);

		$wheres = array();
		foreach ( $taxonomies as $tax => $terms ) {
			$wheres[] = sprintf(
				"(t.taxonomy = '%s' AND t.term_id IN (%s))",
				esc_sql( $tax ),
				implode( ',', array_merge( array( 0 ), array_keys( $terms ) ) )
			);
		}//end foreach

		global $wpdb;
		$sql = "SELECT term_id AS old_id, term_taxonomy_id AS new_id, taxonomy FROM {$wpdb->term_taxonomy} t WHERE {{wheres}}";
		$sql = str_replace( '{{wheres}}', implode( ' OR ', $wheres ), $sql );

		$mappings = $wpdb->get_results( $sql, ARRAY_A ); // phpcs:disable
		foreach ( $mappings as $m ) {
			$taxonomies[ $m['taxonomy'] ][ $m['old_id'] ] = absint( $m['new_id'] );
		}//end foreach

		$taxonomies = array_map(
			function( $terms ) {
				$terms = array_filter( $terms );
				return empty( $terms ) ? false : $terms;
			},
			$taxonomies
		);

		return array_filter( $taxonomies );
	}//end get_term_taxonomy_ids_from_groups()

	private function join_term_tables( $tax_terms, $join_tables, $query ) {
		$joins = array();
		foreach ( $tax_terms as $tax => $terms ) {
			$joins[] = array(
				'terms' => array_values( $terms ),
				'table' => isset( $join_tables[ $tax ] ) ? $join_tables[ $tax ] : false,
			);
		}//end foreach

		return array_reduce(
			$joins,
			function( $q, $j ) {
				$terms = $j['terms'];
				$table = $j['table'];
				$join  = sprintf(
					'LEFT JOIN wp_term_relationships %1$s ON (p.ID = %1$s.object_id AND %1$s.term_taxonomy_id IN (%2$s))',
					$table,
					implode( ',', $terms )
				);
				return ! empty( $table ) ? str_replace( '{{joins}}', "{$join} {{joins}}", $q ) : $q;
			},
			$query
		);
	}//end join_term_tables()

	private function add_share_filter( $query ) {
		$settings = Nelio_Content_Settings::instance();

		$auto_share = 'include-in-auto-share' === $settings->get( 'auto_share_default_mode' )
			? array(
				'key'  => '_nc_exclude_from_auto_share',
				'cond' => 'IS NULL',
			)
			: array(
				'key'  => '_nc_include_in_auto_share',
				'cond' => 'IS NOT NULL',
			);

		$join  = $this->left_meta_join( 'share_filter', $auto_share['key'] );
		$where = "AND share_filter.meta_key {$auto_share['cond']}";

		$query = str_replace( '{{joins}}', "{$join} {{joins}}", $query );
		$query = str_replace( '{{wheres}}', "{$where} {{wheres}}", $query );
		return $query;
	}//end add_share_filter()

	private function add_end_mode_filter( $query ) {
		// FIXME. This needs addressing.
		$end_modes = nc_get_auto_share_end_modes();
		$end_modes = key_by( $end_modes, 'value' );

		$conditions = array_map( array( $this, 'end_mode_to_sql_condition' ), $end_modes );
		$join       = $this->left_meta_join( 'end_mode', '_nc_auto_share_end_mode' );
		$where      = 'AND (' . implode( ' OR ', $conditions ) . ')';

		$query = str_replace( '{{joins}}', "{$join} {{joins}}", $query );
		$query = str_replace( '{{wheres}}', "{$where} {{wheres}}", $query );
		return $query;
	}//end add_end_mode_filter()

	private function end_mode_to_sql_condition( $mode ) {
		$mv = 'end_mode.meta_value';
		$pd = 'p.post_date_gmt';

		if ( 'never' === $mode['value'] ) {
			return sprintf( '(%1$s IS NULL) OR (%1$s = \'never\')', $mv );
		}//end if

		$today = gmdate( 'Y-m-d', time() );
		$date  = gmdate( 'Y-m-d', strtotime( "{$today} - {$mode['months']} months" ) );
		return sprintf(
			'(%1$s = \'%2$s\' AND  \'%3$s\' <= %4$s)',
			$mv,
			esc_sql( $mode['value'] ),
			esc_sql( $date ),
			$pd
		);
	}//end end_mode_to_sql_condition()

	private function exclude_post_ids( $post_ids, $query ) {
		$pids = '(' . implode( ',', array_merge( array( 0 ), $post_ids ) ) . ')';
		return str_replace( '{{pids}}', $pids, $query );
	}//end exclude_post_ids()

	private function limit_post_count( $count, $query ) {
		return str_replace( '{{post_count}}', $count, $query );
	}//end limit_post_count()

	private function get_meta_value_threshold( $meta_name ) {

		$meta_value = 0;

		// Get number of pages.
		$args          = array(
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => $meta_name, // phpcs:ignore
			'orderby'        => 'meta_value_num',
			'order'          => 'desc',
		);
		$query         = new WP_Query( $args );
		$num_top_posts = $query->max_num_pages;
		wp_reset_postdata();

		// Get the "threshold" post.
		$last_good_post = min( $num_top_posts, 250 );
		$args['paged']  = $last_good_post;
		$query          = new WP_Query( $args );
		if ( $query->have_posts() ) {
			$query->the_post();
			$meta_value = absint( get_post_meta( get_the_ID(), $meta_name, true ) );
		}//end if
		wp_reset_postdata();

		return 1 + $meta_value;

	}//end get_meta_value_threshold()

	private function array_split( $array, $parts = 1 ) {

		if ( 1 >= $parts ) {
			return $array;
		}//end if

		$index  = 0;
		$result = array_fill( 0, $parts, array() );
		$max    = ceil( count( $array ) / $parts );
		foreach ( $array as $v ) {
			if ( count( $result[ $index ] ) >= $max ) {
				++$index;
			}//end if
			array_push( $result[ $index ], $v );
		}//end foreach

		return $result;

	}//end array_split()

	private function left_meta_join( $alias, $meta_key ) {
		global $wpdb;
		return '' .
			"LEFT JOIN {$wpdb->postmeta} {$alias} ON (" .
			"p.ID = {$alias}.post_id AND " .
			"{$alias}.meta_key = '{$meta_key}')";
	}//end left_meta_join()

	private function diff_days( $a, $b ) {
		$a = new DateTime( $a );
		$b = new DateTime( $b );
		return absint( $a->diff( $b )->format( '%a' ) );
	}//end diff_days()

	/*
	private function print_query( $q ) {
		$q = wp_remote_request(
			'https://codebeautify.org/Ql/formateQL',
			array(
				'method' => 'POST',
				'body'   => array( 'data' => $q ),
			)
		)['body'];

		$q = preg_replace( '/([A-Z])/', '<b>$1</b>', $q );
		$q = preg_replace( '/({{[^}]*}})/', '<b style="color:green">$1</b>', $q );
		$q = preg_replace( "/('[^']*')/", '<span style="color:darkred">$1</span>', $q );
		$q = str_replace( '<b>I</b><b>D</b>', 'ID', $q );
		$q = preg_replace( '/\.([a-zA-Z_]+)/', '.<span style="color:#66f">$1</span>', $q );
		echo "<pre>$q</pre>";
	}//end print_query()
	*/

}//end class
