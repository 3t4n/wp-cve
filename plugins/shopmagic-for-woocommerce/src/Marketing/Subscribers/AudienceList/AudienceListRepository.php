<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Subscribers\AudienceList;

use WPDesk\ShopMagic\Components\Collections\ArrayCollection;
use WPDesk\ShopMagic\Components\Collections\Collection;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectRepository;
use WPDesk\ShopMagic\Components\Database\Abstraction\EntityNotFound;
use WPDesk\ShopMagic\Components\Database\Abstraction\PostNotFound;

/**
 * @implements ObjectRepository<AudienceList>
 */
class AudienceListRepository implements ObjectRepository {

	/** @var AudienceListFactory */
	private $factory;

	public function __construct( AudienceListFactory $factory ) {
		$this->factory = $factory;
	}

	public function find( $id ): object {
		$post = get_post( (int) $id );
		if ( $post === null ) {
			throw PostNotFound::with_id( (int) $id );
		}

		if ( $post->post_type !== CommunicationListPostType::TYPE ) {
			throw PostNotFound::invalid_type( (int) $id );
		}

		return $this->factory->with_post( $post );
	}

	public function find_all(): Collection {
		return $this->find_by( [] );
	}

	public function find_by( array $criteria, array $order = [], int $offset = 0, int $limit = null ): Collection {
		$args  = array_merge(
			[
				'post_status' => 'any',
			],
			$criteria,
			[
				'post_type'      => CommunicationListPostType::TYPE,
				'posts_per_page' => $limit ?? - 1,
				'paged'          => $offset,
			]
		);
		$query = new \WP_Query( $args );

		$posts = [];
		foreach ( $query->get_posts() as $post ) {
			$posts[] = $this->factory->with_post( $post );
		}

		return new ArrayCollection( $posts );
	}

	public function find_checkout_viewable_items(): Collection {
		return $this->find_by( [
			'post_status' => AudienceList::STATUS_PUBLISH,
			'meta_query'  => [
				[
					'key'   => 'checkout_available',
					'value' => '1',
				],
				[
					'key'   => 'type',
					'value' => AudienceList::TYPE_OPTIN,
				],
			],
		] );
	}

	public function find_opt_out_lists(): Collection {
		return $this->find_by( [
			'post_status' => AudienceList::STATUS_PUBLISH,
			'meta_query'  => [
				[
					'key'   => 'type',
					'value' => AudienceList::TYPE_OPTOUT,
				],
			],
		] );
	}

	public function find_one_by( array $criteria, ?array $order = null ): object {
		$args  = array_merge(
			$criteria,
			[
				'post_type'      => CommunicationListPostType::TYPE,
				'posts_per_page' => 1,
			]
		);
		$query = new \WP_Query( $args );

		$posts = $query->get_posts();

		if ( ! isset( $posts[0] ) ) {
			throw EntityNotFound::failing_criteria( $criteria, $order );
		}

		return $this->factory->with_post( $posts[0] );
	}

	public function get_as_select_options(): array {
		$posts   = $this->find_all();
		$options = [];
		foreach ( $posts as $post ) {
			/** @var AudienceList $post */
			$options[ $post->get_id() ] = $post->get_name();
		}

		return $options;
	}

	public function count( array $criteria ): int {
		$args  = array_merge(
			[ 'post_status' => [ 'publish', 'draft' ] ],
			$criteria,
			[
				'post_type'              => CommunicationListPostType::TYPE,
				'posts_per_page'         => 1,
				'fields'                 => 'ids',
				'cache_results'          => false,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'update_menu_item_cache' => false,
				'lazy_load_term_meta'    => true,
			]
		);
		$query = new \WP_Query( $args );

		return $query->found_posts;
	}
}
