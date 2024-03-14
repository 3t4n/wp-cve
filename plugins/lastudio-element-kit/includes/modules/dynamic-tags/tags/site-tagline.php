<?php
namespace LaStudioKitThemeBuilder\Modules\DynamicTags\Tags;

use LaStudioKitThemeBuilder\Modules\DynamicTags\Tags\Base\Tag;
use LaStudioKitThemeBuilder\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Site_Tagline extends Tag {
	public function get_name() {
		return 'site-tagline';
	}

	public function get_title() {
		return esc_html__( 'Site Tagline', 'lastudio-kit' );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		echo wp_kses_post( get_bloginfo( 'description' ) );
	}
}
