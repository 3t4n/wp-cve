<?php
/**
 * Upgrade Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Admin;

use RT\FoodMenu\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Upgrade Class.
 */
class Upgrade {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action(
			'in_plugin_update_message-' . TLP_FOOD_MENU_PLUGIN_ACTIVE_FILE_NAME,
			function( $plugin_data ) {
				$this->version_update_warning( TLP_FOOD_MENU_VERSION, $plugin_data['new_version'] );
			}
		);

		if ( isset( $_GET['migrate'] ) && wp_verify_nonce( $_GET['_wpnonce'], Fns::nonceText() ) ) {
			$this->migrateData();
		}

		$this->updateVersion();
	}

	/**
	 * Update message
	 *
	 * @param int $current_version Current Version.
	 * @param int $new_version New Version.
	 * @return void
	 */
	public function version_update_warning( $current_version, $new_version ) {
		$current_version_major = explode( '.', $current_version )[0];
		$new_version_major     = explode( '.', $new_version )[0];

		if ( $current_version_major === $new_version_major ) {
			return;
		}
		?>
		<style>
			.rtfm-major-update-warning {
				border-top: 2px solid #d63638;
				padding-top: 15px;
				margin-top: 15px;
				margin-bottom: 15px;
				display: flex;
			}
			.rtfm-major-update-icon i {
				color: #d63638;
				margin-right: 8px;
			}
			.rtfm-major-update-warning + p {
				display: none;
			}
			.rtfm-major-update-title {
				font-weight: 600;
				margin-bottom: 10px;
			}
			.notice-success .rtfm-major-update-warning {
				border-color: #46b450;
			}
			.notice-success .rtfm-major-update-icon i {
				color: #79ba49;
			}
		</style>
		<div class="rtfm-major-update-warning">
			<div class="rtfm-major-update-icon">
				<i class="dashicons dashicons-info"></i>
			</div>
			<div>
				<div class="rtfm-major-update-title">
					<?php
					printf(
						'%s%s.',
						esc_html__( 'Heads up, Please backup before upgrade to version ', 'tlp-food-menu' ),
						esc_html( $new_version )
					);
					?>
				</div>
				<div class="rtfm-major-update-message">
					The latest update includes some substantial changes across different areas of the plugin. <br />We highly recommend you to <b>backup your site before upgrading</b>, and make sure you first update in a staging environment.
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Migrates Data.
	 *
	 * @return void|string
	 */
	public function migrateData() {
		$activeVersion = get_option( 'tlp-food-menu-installed-version' );
		$migrateFlag   = get_option( 'tlp_fm_m_3_0' );

		if ( version_compare( $activeVersion, '3.0.0', '<' ) ) {
			try {
				$exData            = get_option( TLPFoodMenu()->options['settings'] );
				$slug              = ! empty( $exData['general']['slug'] ) ? $exData['general']['slug'] : TLPFoodMenu()->options['slug'];
				$currency          = ! empty( $exData['general']['currency'] ) ? $exData['general']['currency'] : 'USD';
				$currency_position = ! empty( $exData['general']['currency_position'] ) ? $exData['general']['currency_position'] : 'left';

				$data = [
					'currency'           => esc_html( $currency ),
					'currency_position'  => esc_html( $currency_position ),
					'price_thousand_sep' => ',',
					'price_decimal_sep'  => '.',
					'price_num_decimals' => 2,
					'slug'               => sanitize_title_with_dashes( $slug ),
				];

				update_option( TLPFoodMenu()->options['settings'], $data );

				// Get all posts.
				$allFreeMenu = get_posts(
					[
						'post_type'      => TLPFoodMenu()->post_type,
						'posts_per_page' => - 1,
						'post_status'    => 'publish',
					]
				);

				if ( ! empty( $allFreeMenu ) ) {
					foreach ( $allFreeMenu as $post ) {
						$price = get_post_meta( $post->ID, 'price', true );
						if ( $price ) {
							update_post_meta( $post->ID, '_regular_price', Fns::format_decimal( esc_html( $price ) ) );
						}
					}
				}

				add_action( 'admin_init', [ $this, 'migrateTaxonomy' ] );

				update_option( 'tlp_fm_m_3_0', 1 );
				flush_rewrite_rules();
			} catch ( \Exception $e ) {
				$GLOBALS['errors'][] = $e; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}
		}
	}

	/**
	 * Update the version.
	 *
	 * @return void
	 */
	private function updateVersion() {
		\update_option( TLPFoodMenu()->options['installed_version'], TLPFoodMenu()->options['version'] );
	}

	/**
	 * Migrates Taxonomy.
	 *
	 * @return void|string
	 */
	public function migrateTaxonomy() {
		$termPost = [];
		$terms    = get_terms(
			'food-menu-category',
			[
				'parent'     => 0,
				'hide_empty' => false,
			]
		);

		if ( empty( $terms ) ) {
			return;
		}

		foreach ( $terms as $term ) {

			$post_id      = $this->get_post_id( $term );
			$sc_id        = $this->get_sc_post_id( $term );
			$parentTermId = $this->insert_category_taxonomy( $term, 0 );

			$this->assign_term_to_post( $post_id, $parentTermId );
			$this->assign_sc_cat_meta( $sc_id, $parentTermId );
			$this->remove_old_cat_meta( $sc_id, $term );

			$subterms = get_terms(
				$term->taxonomy,
				[
					'parent'     => $term->term_id,
					'hide_empty' => false,
				]
			);

			if ( empty( $subterms ) ) {
				return;
			}

			foreach ( $subterms as $subterm ) {
				$post_id     = $this->get_post_id( $subterm );
				$sc_id       = $this->get_sc_post_id( $subterm );
				$childTermId = [];

				if ( ! is_wp_error( $parentTermId ) ) {
					$parent      = $parentTermId['term_id'];
					$childTermId = $this->insert_category_taxonomy( $subterm, $parent );

					$this->assign_term_to_post( $post_id, $childTermId );
					$this->assign_sc_cat_meta( $sc_id, $childTermId );
					$this->remove_old_cat_meta( $sc_id, $subterm );
				}

				$subsubterms = get_terms(
					$subterm->taxonomy,
					[
						'parent'     => $subterm->term_id,
						'hide_empty' => false,
					]
				);

				if ( ! empty( $subsubterms ) ) {
					foreach ( $subsubterms as $subsubterm ) {
						$post_id = $this->get_post_id( $subsubterm );
						$sc_id   = $this->get_sc_post_id( $subsubterm );

						if ( ! is_wp_error( $childTermId ) && ! empty( $childTermId ) ) {
							$parent       = $childTermId['term_id'];
							$subSubTermId = $this->insert_category_taxonomy( $subsubterm, $parent );

							$this->assign_term_to_post( $post_id, $subSubTermId );
							$this->assign_sc_cat_meta( $sc_id, $subSubTermId );
							$this->remove_old_cat_meta( $sc_id, $subsubterm );
						}
					}
				}
			}
		}
	}

	/**
	 * Assign term to post
	 *
	 * @param array  $posts Post ID.
	 * @param object $term Terms.
	 * @return void|bool
	 */
	public function assign_term_to_post( $posts, $term ) {
		if ( ! empty( $posts ) && ! is_wp_error( $term ) ) {
			foreach ( $posts as $post_id ) {
				$term_taxonomy_ids = wp_set_object_terms( $post_id, $term['term_id'], TLPFoodMenu()->taxonomies['category'], true );

				if ( is_wp_error( $term_taxonomy_ids ) ) {
					return false;
				}
			}
		}
	}

	/**
	 * Insert Category Taxonomy
	 *
	 * @param object $term Terms.
	 * @param string $parent Term Parent.
	 * @return int
	 */
	public function insert_category_taxonomy( $term, $parent ) {
		$termArgs = [
			'description' => $term->description,
			'slug'        => $term->slug,
			'parent'      => $parent,
		];
		$term_id  = wp_insert_term( $term->name, TLPFoodMenu()->taxonomies['category'], $termArgs );

		return $term_id;
	}

	/**
	 * Get Post ID.
	 *
	 * @param object $term Term.
	 * @return array
	 */
	public function get_post_id( $term ) {
		$args = [
			'post_type' => TLPFoodMenu()->post_type,
			'fields'    => 'ids',
			'tax_query' => [
				[
					'taxonomy' => $term->taxonomy,
					'field'    => 'slug',
					'terms'    => $term->slug,
				],
			],
		];

		return get_posts( $args );
	}

	/**
	 * Get ShortCode Post ID.
	 *
	 * @param object $term Term.
	 * @return array
	 */
	public function get_sc_post_id( $term ) {
		$args = [
			'post_type'  => 'fmsc',
			'fields'     => 'ids',
			'meta_query' => [
				[
					'key'   => 'fmp_categories',
					'value' => $term->term_id,
				],
			],
		];

		return get_posts( $args );
	}

	/**
	 * Assign ShortCode Category Meta.
	 *
	 * @param array  $posts Post ID.
	 * @param object $term Term.
	 * @return void
	 */
	public function assign_sc_cat_meta( $posts, $term ) {
		if ( ! empty( $posts ) && ! is_wp_error( $term ) ) {
			foreach ( $posts as $post_id ) {
				add_post_meta( $post_id, 'fmp_categories', $term['term_id'] );
			}
		}
	}

	/**
	 * Remove old meta
	 *
	 * @param array  $posts Post ID.
	 * @param object $term Term.
	 * @return void
	 */
	public function remove_old_cat_meta( $posts, $term ) {
		if ( ! empty( $posts ) && ! is_wp_error( $term ) ) {
			foreach ( $posts as $post_id ) {
				delete_post_meta( $post_id, 'fmp_categories', $term->term_id );
			}
		}
	}
}
