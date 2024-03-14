<?php
/**
 * Shortcode List Ajax Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Admin\Ajax;

use RT\FoodMenu\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Shortcode List Ajax Class.
 */
class Shortcode {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'wp_ajax_fmShortCodeList', [ $this, 'response' ] );
	}

	/**
	 * Ajax Response.
	 *
	 * @return void
	 */
	public function response() {
		$html = null;
		$scQ  = new \WP_Query(
			[
				'post_type'      => TLPFoodMenu()->shortCodePT,
				'order_by'       => 'title',
				'order'          => 'DESC',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			]
		);

		if ( $scQ->have_posts() ) {
			$html .= "<div class='mce-container mce-form'>";
			$html .= "<div class='mce-container-body'>";
			$html .= '<label class="mce-widget mce-label" style="padding: 20px;font-weight: bold;" for="scid">' . esc_html__( 'Select Short code', 'tlp-food-menu' ) . '</label>';
			$html .= "<select name='id' id='scid' style='width: 150px;margin: 15px;'>";
			$html .= "<option value=''>" . esc_html__( 'Default', 'tlp-food-menu' ) . '</option>';

			while ( $scQ->have_posts() ) {
				$scQ->the_post();
				$html .= "<option value='" . get_the_ID() . "'>" . get_the_title() . '</option>';
			}

			$html .= '</select>';
			$html .= '</div>';
			$html .= '</div>';
		} else {
			$html .= '<div>' . esc_html__( 'No shortCode found.', 'tlp-food-menu' ) . '</div>';
		}

		Fns::print_html( $html, true );

		die();
	}
}
