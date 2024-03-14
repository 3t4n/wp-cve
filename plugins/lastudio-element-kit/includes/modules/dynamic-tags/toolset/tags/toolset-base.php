<?php
namespace LaStudioKitThemeBuilder\Modules\DynamicTags\Toolset\Tags;

use Elementor\Controls_Manager;
use LaStudioKitThemeBuilder\Modules\DynamicTags\Tags\Base\Tag;
use LaStudioKitThemeBuilder\Modules\DynamicTags\Toolset\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Toolset_Base extends Tag {

	public function get_group() {
		return Module::TOOLSET_GROUP;
	}

	public function get_categories() {
		return [
			Module::TEXT_CATEGORY,
			Module::POST_META_CATEGORY,
		];
	}

	protected function register_controls() {
		$this->add_control(
			'key',
			[
				'label' => esc_html__( 'Key', 'lastudio-kit' ),
				'type' => Controls_Manager::SELECT,
				'groups' => Module::get_control_options( $this->get_supported_fields() ),
			]
		);
	}

	protected function get_supported_fields() {
		return [];
	}
}
