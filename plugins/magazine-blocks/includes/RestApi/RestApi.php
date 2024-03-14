<?php
/**
 * Magazine Blocks Rest API.
 *
 * @package Magazine Blocks
 */

namespace MagazineBlocks\RestApi;

defined( 'ABSPATH' ) || exit;

use MagazineBlocks\Traits\Singleton;

/**
 * Magazine Blocks Rest API.
 */
class RestApi {

	use Singleton;

	/**
	 * Constructor.
	 */
	protected function __construct() {
		$this->init_hooks();
	}

	/**
	 * Init hooks.
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'rest_api_init', array( $this, 'on_rest_api_init' ) );
	}

	/**
	 * On rest api init.
	 *
	 * @return void
	 */
	public function on_rest_api_init() {
		$this->register_rest_routes();
		$this->register_rest_fields();
	}

	/**
	 * Register rest routes.
	 *
	 * @return void
	 */
	private function register_rest_routes() {
		$controllers = array(
			'MagazineBlocks\RestApi\Controllers\LibraryDataController',
			'MagazineBlocks\RestApi\Controllers\ImageImportController',
			'MagazineBlocks\RestApi\Controllers\RegenCSSController',
			'MagazineBlocks\RestApi\Controllers\SettingsController',
			'MagazineBlocks\RestApi\Controllers\ChangelogController',
			'MagazineBlocks\RestApi\Controllers\VersionControlController',
			'MagazineBlocks\RestApi\Controllers\GlobalStylesController',
		);

		foreach ( $controllers as $controller ) {
			$controller = new $controller();
			$controller->register_routes();
		}
	}

	/**
	 * Register rest fields.
	 *
	 * Additional fields for post types.
	 *
	 * @return void
	 */
	public function register_rest_fields() {
		$post_types = magazine_blocks_get_post_types();
		$fields     = [
			'magazineBlocksPostFeaturedMedia'  => array( $this, 'get_post_featured_media' ),
			'magazineBlocksPostAuthor'         => array( $this, 'get_post_author' ),
			'magazineBlocksPostCommentsNumber' => array( $this, 'get_post_comments_number' ),
			'magazineBlocksPostExcerpt'        => array( $this, 'get_post_excerpt' ),
			'magazineBlocksPostCategories'     => array( $this, 'get_post_categories' ),
		];

		foreach ( $post_types as $post_type ) {
			foreach ( $fields as $id => $callback ) {
				register_rest_field(
					$post_type->name,
					$id,
					array(
						'get_callback'    => $callback,
						'update_callback' => null,
						'schema'          => null,
					)
				);
			}
		}
	}

	/**
	 * Get post featured media.
	 *
	 * @param array $post Post data.
	 *
	 * @return array
	 */
	public function get_post_featured_media( array $post ): array {
		return array_reduce(
			get_intermediate_image_sizes(),
			function ( $acc, $curr ) use ( $post ) {
				if ( isset( $post['featured_media'] ) ) {
					$acc[ $curr ] = wp_get_attachment_image_src( $post['featured_media'], $curr )[0] ?? false;
				}

				return $acc;
			},
			[]
		);
	}

	/**
	 * Get post author.
	 *
	 * @param array $post Post data.
	 *
	 * @return array
	 */
	public function get_post_author( array $post ): array {
		return [
			'name'   => get_the_author_meta( 'display_name', $post['author'] ),
			'avatar' => get_avatar_url( $post['author'] ),
		];
	}

	/**
	 * Get post comment.
	 *
	 * @param array $post Post data.
	 *
	 * @return false|int
	 */
	public function get_post_comments_number( array $post ) {
		if ( post_password_required( $post['id'] ) || ! comments_open( $post['id'] ) ) {
			return false;
		}
		return get_comments_number( $post['id'] );
	}

	/**
	 * Get post excerpt.
	 *
	 * @param array $post Post data.
	 *
	 * @return string
	 */
	public function get_post_excerpt( array $post ): string {
		return get_the_excerpt( $post['id'] );
	}

	/**
	 * Get categories.
	 *
	 * @param array $post Post data.
	 * @return array
	 */
	public function get_post_categories( array $post ): array {
		$categories = get_the_terms( $post['id'], 'category' );

		if ( empty( $categories ) || is_wp_error( $categories ) ) {
			return [];
		}

		return array_reduce(
			$categories,
			function( $acc, $curr ) {
				$acc[] = $curr->name;
				return $acc;
			},
			array()
		);
	}
}
