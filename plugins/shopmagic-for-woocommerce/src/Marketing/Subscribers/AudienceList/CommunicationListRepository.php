<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Marketing\Subscribers\AudienceList;

use function get_posts;

/**
 * Simple repository pattern for Communication types.
 *
 * @deprecated 3.0.9 Use AudienceListRepository instead.
 */
final class CommunicationListRepository {

	/** @return string[] Indexed by id. */
	public static function get_lists_as_select_options(): array {
		$posts   = get_posts(
			[
				'post_type'   => CommunicationListPostType::TYPE,
				'numberposts' => - 1,
			]
		);
		$options = [];
		foreach ( $posts as $post ) {
			/** @var \WP_Post $post */
			$options[ $post->ID ] = $post->post_title;
		}

		return $options;
	}

	}
