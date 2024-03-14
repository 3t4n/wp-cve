<?php
/**
 * VC Addon Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Widgets\Vc;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * VC Addon Widget.
 */
class VcAddon {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'init', [ $this, 'addVCAddon' ] );
	}

	public function addVCAddon() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		if ( function_exists( 'vc_map' ) ) {
			\vc_map(
				[
					'name'              => esc_html__( 'Food Menu', 'tlp-food-menu' ),
					'base'              => 'foodmenu',
					'class'             => '',
					'icon'              => TLPFoodMenu()->assets_url() . 'images/icon-32x32.png',
					'controls'          => 'full',
					'category'          => 'Content',
					'admin_enqueue_js'  => '',
					'admin_enqueue_css' => '',
					'params'            => [
						[
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'ShortCode', 'tlp-food-menu' ),
							'param_name'  => 'id',
							'value'       => $this->scListA(),
							'admin_label' => true,
							'description' => esc_html__( 'ShortCode list', 'tlp-food-menu' ),
						],
					],
				]
			);
		} else {
			\wpb_map(
				[
					'name'              => esc_html__( 'Food Menu', 'tlp-food-menu' ),
					'base'              => 'foodmenu',
					'class'             => '',
					'icon'              => TLPFoodMenu()->assets_url() . 'images/icon-32x32.png',
					'controls'          => 'full',
					'category'          => 'Content',
					'admin_enqueue_js'  => '',
					'admin_enqueue_css' => '',
					'params'            => [
						[
							'type'        => 'dropdown',
							'heading'     => esc_html__( 'ShortCode', 'tlp-food-menu' ),
							'param_name'  => 'id',
							'value'       => $this->scListA(),
							'admin_label' => true,
							'description' => esc_html__( 'ShortCode list', 'tlp-food-menu' ),
						],
					],
				]
			);
		}
	}

	public function scListA() {
		$sc            = [];
		$scQ           = get_posts(
			[
				'post_type'      => TLPFoodMenu()->shortCodePT,
				'order_by'       => 'title',
				'order'          => 'DESC',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			]
		);
		$sc['Default'] = '';

		if ( count( $scQ ) ) {
			foreach ( $scQ as $post ) {
				$sc[ $post->post_title ] = $post->ID;
			}
		}

		return $sc;
	}
}
