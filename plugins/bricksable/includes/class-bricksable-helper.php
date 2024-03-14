<?php
namespace Bricksable\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Helpers {

	/**
	 * Get Bricks Templates
	 *
	 * @param string $exclude_template_id Bricks Template ID.
	 * @return array
	 * @since 1.2.5
	 */
	public static function get_templates_list( $exclude_template_id = '' ) {
		$templates = get_posts(
			array(
				'post_type'      => BRICKS_DB_TEMPLATE_SLUG,
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'   => BRICKS_DB_TEMPLATE_TYPE,
						'value' => array( 'section', 'content' ),
					),
				),
				'post_status'    => 'publish',
			)
		);

		$list = array();

		foreach ( $templates as $template ) {
			if ( $exclude_template_id === $template->ID ) {
				continue;
			}

			$list[ $template->ID ] = $template->post_title;
		}

		return $list;
	}
}
