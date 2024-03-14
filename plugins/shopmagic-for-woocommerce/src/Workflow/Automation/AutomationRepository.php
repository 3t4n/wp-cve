<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Automation;

use WPDesk\ShopMagic\Components\Collections\ArrayCollection;
use WPDesk\ShopMagic\Components\Collections\Collection;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\CountableRepository;
use WPDesk\ShopMagic\Components\Database\Abstraction\EntityNotFound;
use WPDesk\ShopMagic\Exception\AutomationNotFound;

/**
 * @implements CountableRepository<Automation>
 */
final class AutomationRepository implements CountableRepository {

	/** @var AutomationReconstitutionFactory */
	private $factory;

	public function __construct( AutomationReconstitutionFactory $factory ) {
		$this->factory = $factory;
	}

	public function find( $id ): object {
		$post = \get_post( (int) $id );
		if ( $post === null ) {
			throw AutomationNotFound::with_id( (int) $id );
		}

		if ( $post->post_type !== AutomationPostType::TYPE ) {
			throw AutomationNotFound::invalid_type( (int) $id );
		}

		return $this->factory->with_post( $post );
	}

	public function find_all(): Collection {
		return $this->find_by( [] );
	}

	public function find_by( array $criteria, array $order = [], int $offset = 0, ?int $limit = null ): Collection {
		$args  = array_merge(
			[
				'post_parent' => 0,
				'post_status' => 'any',
			],
			$criteria,
			[
				'post_type'              => AutomationPostType::TYPE,
				'posts_per_page'         => $limit ?? - 1,
				'offset'                 => $offset,
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
				'update_post_meta_cache' => true,
				'orderby'                => array_keys( $order )[0] ?? 'date',
				'order'                  => array_values( $order )[0] ?? 'DESC',
			]
		);
		$query = new \WP_Query( $args );

		$posts = [];
		/** @var \WP_Post $post */
		foreach ( $query->get_posts() as $post ) {
			$posts[] = $this->factory->with_post( $post );
		}

		return new ArrayCollection( $posts );
	}

	/**
	 * @inheritDoc
	 */
	public function find_one_by( array $criteria, ?array $order = null ): object {
		$args  = array_merge(
			$criteria,
			[
				'post_type'      => AutomationPostType::TYPE,
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

	/**
	 * By default, we count only top-level automations.
	 */
	public function count( array $criteria ): int {
		$args  = array_merge(
			[
				'post_status' => 'any',
				'post_parent' => 0,
			],
			$criteria,
			[
				'post_type'              => AutomationPostType::TYPE,
				'posts_per_page'         => 1,
				'fields'                 => 'ids',
				'cache_results'          => false,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'update_menu_item_cache' => false,
				'lazy_load_term_meta'    => true,
				'orderby'                => 'none',
			]
		);
		$query = new \WP_Query( $args );

		return $query->found_posts;
	}
}
