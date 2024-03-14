<?php
namespace LaStudioKitThemeBuilder\Modules\DynamicTags\Tags;

use LaStudioKitThemeBuilder\Modules\DynamicTags\Tags\Base\Tag;
use LaStudioKitThemeBuilder\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Author_Name extends Tag {

	public function get_name() {
		return 'author-name';
	}

	public function get_title() {
		return esc_html__( 'Author Name', 'lastudio-kit' );
	}

	public function get_group() {
		return Module::AUTHOR_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		echo wp_kses_post( get_the_author() );
	}
}
