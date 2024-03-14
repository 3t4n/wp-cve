<?php
namespace Thim_EL_Kit\Elementor\DynamicTags\Tags;

use \Elementor\Core\DynamicTags\Tag as Tag_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

class Item_Title extends Tag_Base {

	public function get_name() {
		return 'thim-item-title';
	}

	public function get_categories() {
		return array( TagsModule::TEXT_CATEGORY );
	}

	public function get_group() {
		return array( 'thim-ekit' );
	}

	public function get_title() {
		return 'Item Title';
	}

	public function render() {
		echo wp_kses_post( get_the_title(get_the_ID()) );
	}
}
