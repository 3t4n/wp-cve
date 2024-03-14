<?php
namespace LaStudioKitThemeBuilder\Modules\DynamicTags\Tags;

use LaStudioKitThemeBuilder\Modules\DynamicTags\Tags\Base\Data_Tag;
use LaStudioKitThemeBuilder\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Archive_URL extends Data_Tag {

	public function get_name() {
		return 'archive-url';
	}

	public function get_group() {
		return Module::ARCHIVE_GROUP;
	}

	public function get_categories() {
		return [ Module::URL_CATEGORY ];
	}

	public function get_title() {
		return esc_html__( 'Archive URL', 'lastudio-kit' );
	}

	public function get_panel_template() {
		return ' ({{ url }})';
	}

	public function get_value( array $options = [] ) {
		return lastudio_kit_helper()->get_the_archive_url();
	}
}

