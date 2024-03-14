<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer;

/**
 * @implements Normalizer<\WP_Post>
 */
class PostNormalizer implements Normalizer {

	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object( \WP_Post::class, $object );
		}

		return [
			'id'     => $object->ID,
			'object' => 'post',
			'_links' => [
				'edit' => [ 'href' => get_edit_post_link( $object->ID, 'edit' ) ],
			],
		];
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof \WP_Post;
	}
}
