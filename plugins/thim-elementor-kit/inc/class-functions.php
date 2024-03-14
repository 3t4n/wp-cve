<?php
namespace Thim_EL_Kit;

use Thim_EL_Kit\SingletonTrait;
use Thim_EL_Kit\Modules\Cache;

class Functions {
	use SingletonTrait;

	/**
	 * Output: Array ( [all] => Array ( [0] => 13506 [1] => 557 ) [course_categories] => Array ( [0] => 13506 ) )
	 */
	public function get_conditions_by_type( $type ) {
		$cache           = Cache::instance();
		$conditions_data = $cache->get( $type );
		$sorted_data     = array();
		$output          = array();

		foreach ( $conditions_data as $layout_id => $conditions ) {
			$post = get_post( $layout_id );

			if ( ! $post ) {
				continue;
			}

			if ( ! empty( $conditions ) ) {
				foreach ( $conditions as $condition ) {
					if ( 'publish' === $post->post_status ) {
						$sorted_data[ $condition['type'] ][ $condition['comparison'] ][] = $layout_id;
					}
				}
			}
		}

		foreach ( $sorted_data as $condition_type => $conditions ) {
			if ( isset( $conditions['include'] ) ) {
				foreach ( $conditions['include'] as $layout_id ) {
					$output[ $condition_type ][] = $layout_id;
				}
			}

			if ( isset( $conditions['exclude'] ) ) {
				// remove layout id from output
				foreach ( $conditions['exclude'] as $layout_id ) {
					if ( ! isset( $output[ $condition_type ] ) ) {
						continue;
					}
					$key = array_search( $layout_id, $output[ $condition_type ] );

					if ( false !== $key && isset( $output[ $condition_type ][ $key ] ) ) {
						unset( $output[ $condition_type ][ $key ] );
					}
				}
			}
		}

		return $output;
	}

	public function get_pages_loop_item( $post_type = '' ) {
		// Get all page with post type thim_elementor_kit and type loop_item and post type is $post_type
		$args = array(
			'post_type'      => 'thim_elementor_kit',
			'posts_per_page' => - 1,
			'meta_query'     => array(
				array(
					'key'     => Custom_Post_Type::TYPE,
					'value'   => 'loop_item',
					'compare' => '=',
				),
			),
		);

		// Filter loop item page by post type.
		if ( ! empty( $post_type ) ) {
			$args['meta_query'][] = array(
				'key'     => 'thim_loop_item_post_type',
				'value'   => $post_type,
				'compare' => '=',
			);
		}

		$loop_items = get_posts( $args );

		$output = array();

		if ( ! empty( $loop_items ) ) {
			foreach ( $loop_items as $loop_item ) {
				$output[ $loop_item->ID ] = $loop_item->post_title;
			}
		}

		return $output;
	}
}
