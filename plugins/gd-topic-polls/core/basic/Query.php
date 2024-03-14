<?php

namespace Dev4Press\Plugin\GDPOL\Basic;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Query {
	public function __construct() {
	}

	public function run( $args = array() ) : array {
		$defaults = array(
			'forums'  => array(),
			'authors' => array(),
			'orderby' => 'p.post_date',
			'order'   => 'desc',
			'limit'   => 0,
		);

		$args = wp_parse_args( $args, $defaults );

		$order = $args['orderby'] == 'random' ? 'RAND()' : $args['orderby'] . ' ' . $args['order'];

		$sql = array(
			'select' => array(
				'p.ID AS poll_id',
			),
			'from'   => array(
				gdpol_db()->wpdb()->posts . ' p',
				'INNER JOIN ' . gdpol_db()->wpdb()->posts . ' t ON t.ID = p.post_parent',
			),
			'where'  => array(
				"p.post_type = '" . gdpol()->post_type_poll() . "'",
				"p.post_status = 'publish'",
			),
			'order'  => $order,
		);

		if ( ! empty( $args['authors'] ) ) {
			$sql['where'][] = 'p.post_author IN (' . join( ', ', (array) $args['authors'] ) . ')';
		}

		if ( ! empty( $args['forums'] ) ) {
			$sql['from'][]  = "INNER JOIN " . gdpol_db()->wpdb()->postmeta . " mt ON mt.post_id = t.ID";
			$sql['where'][] = "(mt.meta_key = '_bbp_forum_id' AND CAST(mt.meta_value AS UNSIGNED) IN (" . join( ', ', (array) $args['forums'] ) . "))";
		}

		if ( $args['limit'] > 0 ) {
			$sql['limit'] = $args['limit'];
		}

		$query = gdpol_db()->build_query( $sql );
		$raw   = gdpol_db()->get_results( $query );
		$items = wp_list_pluck( $raw, 'poll_id' );

		$polls = array();

		foreach ( $items as $poll_id ) {
			$polls[] = Poll::load( $poll_id );
		}

		return $polls;
	}
}
