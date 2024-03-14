<?php
namespace Shop_Ready\extension\elefinder\category;

class WR_Settings_Finder_Category extends \Elementor\Core\Common\Modules\Finder\Base_Category {
	
	public function get_id() {
		return 'shop-raedy-settings';
	}

	public function get_title() {
		return esc_html__( 'ShopReady Settings', 'shopready-elementor-addon' );
	}
	public function get_category_items( array $options = [] ) {

		$items = [
		
			'woo_ready_dashboard' => [
				'title' => esc_html__( 'DashBoard', 'shopready-elementor-addon' ),
				'url' =>  esc_url( shop_ready_get_dashboard_url() ),
				'icon' => 'edit',
				'keywords' => [
					'shopready-elementor-addon',
					esc_html__('Shop Ready links','shopready-elementor-addon'),
					esc_html__('settings','shopready-elementor-addon')
					]
			],
			
		];

		return $items;
	}
}