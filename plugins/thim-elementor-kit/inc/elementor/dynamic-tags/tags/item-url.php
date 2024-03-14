<?php
namespace Thim_EL_Kit\Elementor\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Data_Tag as Data_Tag;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

class Item_URL extends Data_Tag {

	public function get_name() {
		return 'thim-item-url';
	}

	public function get_title() {
		return esc_html__( 'Item URL', 'thim-elementor-kit' );
	}

	public function get_group() {
		return 'thim-ekit';
	}

	public function get_categories() {
		return array( TagsModule::URL_CATEGORY );
	}

	public function get_value( array $options = array() ) {
		return esc_url( get_permalink( get_the_ID() ) );
	}
}
