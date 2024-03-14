<?php
namespace LaStudioKitThemeBuilder\Modules\Woocommerce\Conditions;

use LaStudioKitThemeBuilder\Modules\ThemeBuilder as ThemeBuilder;
use LaStudioKitThemeBuilder\Modules\Woocommerce\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woocommerce extends ThemeBuilder\Conditions\Condition_Base {

	public static function get_type() {
		return 'woocommerce';
	}

	public function get_name() {
		return 'woocommerce';
	}

	public function get_label() {
		return __( 'WooCommerce', 'lastudio-kit' );
	}

	public function get_all_label() {
		return __( 'Entire Shop', 'lastudio-kit' );
	}

	public function register_sub_conditions() {
		$product_archive = new Product_Archive();

		$product_single = new ThemeBuilder\Conditions\Post( [
			'post_type' => 'product',
		] );

		$this->register_sub_condition( $product_archive );
		$this->register_sub_condition( $product_single );
	}

	public function check( $args ) {
		return \is_woocommerce() || Module::is_product_search();
	}
}
