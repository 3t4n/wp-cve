<?php
/**
 * Frontend Template Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Frontend;

use RT\FoodMenu\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Frontend Template Class.
 */
class Template {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_filter( 'template_include', [ $this, 'template_loader' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'load_templatesctipt' ] );
	}

	public static function template_loader( $template ) {
		if ( is_embed() ) {
			return $template;
		}

		$find = [ 'food-menu.php' ];
		$file = null;

		if ( is_single() && get_post_type() == TLPFoodMenu()->post_type ) {
			$file   = 'single-food-menu.php';
			$find[] = $file;
			$find[] = TLPFoodMenu()->plugin_template_path() . $file;
		} elseif ( Fns::is_food_taxonomy() ) {
			$term = get_queried_object();

			if ( is_tax( TLPFoodMenu()->taxonomies['category'] ) ) {
				$file = 'taxonomy-' . $term->taxonomy . '.php';
			} else {
				$file = 'archive-food-menu-cat.php';
			}

			$find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
			$find[] = TLPFoodMenu()->plugin_template_path() . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
			$find[] = 'taxonomy-' . $term->taxonomy . '.php';
			$find[] = TLPFoodMenu()->plugin_template_path() . 'taxonomy-' . $term->taxonomy . '.php';
			$find[] = $file;
			$find[] = TLPFoodMenu()->plugin_template_path() . $file;
		} elseif ( is_post_type_archive( TLPFoodMenu()->post_type ) ) {
			$file   = 'archive-food-menu-cat.php';
			$find[] = $file;
			$find[] = TLPFoodMenu()->plugin_template_path() . $file;

		}

		if ( $file ) {
			$template = locate_template( array_unique( $find ) );

			if ( ! $template ) {
				$template = TLPFoodMenu()->plugin_template_path() . $file;
			}
		}

		return $template;
	}

	public function load_templatesctipt() {
		if ( get_post_type() == TLPFoodMenu()->post_type || is_post_type_archive( TLPFoodMenu()->post_type ) ) {
			$nonce = wp_create_nonce( Fns::nonceText() );

			wp_localize_script(
				'fm-frontend',
				'fmp',
				[
					'nonceID' => esc_attr( Fns::nonceId() ),
					'nonce'   => esc_attr( $nonce ),
					'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
				]
			);
		}
	}
}
