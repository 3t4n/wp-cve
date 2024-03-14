<?php
namespace BetterLinks\Traits;

trait Terms {

	public function tags_analytic() {

		$analytic = get_option( 'btl_tags_analytics', array() );
		if ( count( $analytic ) > 0 ) {
			return $analytic;
		}
		global $wpdb;
		$prefix = $wpdb->prefix;

		$query        = "SELECT t.id AS tag_id, tr.link_id,COUNT(tr.link_id) AS total_click FROM {$prefix}betterlinks_clicks c 
        LEFT JOIN {$prefix}betterlinks_terms_relationships tr ON c.link_id=tr.link_id 
        LEFT JOIN {$prefix}betterlinks_terms t ON t.id=tr.term_id WHERE t.term_type='tags' GROUP BY tag_id,tr.link_id;";
		$total_clicks = $wpdb->get_results( $query, ARRAY_A );

		$prepare_total_clicks = array();
		foreach ( $total_clicks as $value ) {
			if ( isset( $prepare_total_clicks[ $value['tag_id'] ] ) ) {
				$prepare_total_clicks[ $value['tag_id'] ] += $value['total_click'];
				continue;
			}
			$prepare_total_clicks[ $value['tag_id'] ] = $value['total_click'];
		}

		$query         = "SELECT t.id AS tag_id, COUNT(c.ip) AS unique_clicks FROM {$prefix}betterlinks_terms t LEFT JOIN {$prefix}betterlinks_terms_relationships tr ON t.id=tr.term_id LEFT JOIN {$prefix}betterlinks_clicks c ON tr.link_id=c.link_id WHERE term_type='tags' GROUP BY tag_id, c.ip;";
		$unique_clicks = $wpdb->get_results( $query, ARRAY_A );

		$prepare_unique_clicks = array();

		foreach ( $unique_clicks as $value ) {
			if ( isset( $prepare_unique_clicks[ $value['tag_id'] ] ) ) {
				$prepare_unique_clicks[ $value['tag_id'] ] += 1;
				continue;
			}
			$prepare_unique_clicks[ $value['tag_id'] ] = isset( $prepare_total_clicks[ $value['tag_id'] ] ) ? 1 : 0;
		}

		$analytic = array(
			'total_clicks'  => $prepare_total_clicks,
			'unique_clicks' => $prepare_unique_clicks,
		);
		update_option( 'btl_tags_analytics', $analytic );
		return $analytic;
	}

	public function get_all_tags() {
		global $wpdb;
		$query = "SELECT id, term_name, term_slug, link_count FROM {$wpdb->prefix}betterlinks_terms AS t LEFT JOIN (SELECT term_id, COUNT(term_id) AS link_count FROM {$wpdb->prefix}betterlinks_terms_relationships GROUP BY term_id) AS tr ON t.id=tr.term_id WHERE t.term_type='tags'";
		return $wpdb->get_results( $query, ARRAY_A );
	}

	public function get_all_terms_data( $args ) {
		if ( isset( $args['ID'] ) ) {
			$results = \BetterLinks\Helper::get_terms_by_link_ID_and_term_type( $args['ID'], $args['term_type'] );
		} else {
			$results = \BetterLinks\Helper::get_terms_all_data();
		}
		return $results;
	}
	public function create_term( $args ) {
		$term_id = \BetterLinks\Helper::insert_term( $args );
		if ( $term_id ) {
			$args['ID']    = $term_id;
			$args['lists'] = array();
			return $args;
		}
		return array();
	}
	public function update_term( $args ) {
		\BetterLinks\Helper::insert_term(
			array(
				'ID'        => $args['cat_id'],
				'term_name' => $args['cat_name'],
				'term_slug' => $args['cat_slug'],
				'term_type' => 'category',
			),
			true
		);
		return $args;
	}
	public function update_tag( $args ) {
		\BetterLinks\Helper::insert_term(
			array(
				'ID'        => $args['ID'],
				'term_name' => $args['term_name'],
				'term_slug' => $args['term_slug'],
				'term_type' => 'tags',
			),
			true
		);
		return $args;
	}
	public function delete_term( $args ) {
		if ( isset( $args['cat_id'] ) && $args['cat_id'] != 1 ) {
			\BetterLinks\Helper::delete_term_and_update_term_relationships( $args['cat_id'] );
		}
		if ( isset( $args['tag_id'] ) && '' !== $args['tag_id'] ) {
			\BetterLinks\Helper::delete_term_and_update_term_relationships( $args['tag_id'] );
		}
	}
}
