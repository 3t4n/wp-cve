<?php
namespace LaStudioKitThemeBuilder\Modules\DynamicTags\ACF\Tags;

use LaStudioKitThemeBuilder\Modules\DynamicTags\Tags\Base\Tag;
use LaStudioKitThemeBuilder\Modules\DynamicTags\ACF\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ACF_Number extends Tag {

	public function get_name() {
		return 'acf-number';
	}

	public function get_title() {
		return esc_html__( 'ACF', 'lastudio-kit' ) . ' ' . esc_html__( 'Number', 'lastudio-kit' ) . ' ' . esc_html__( 'Field', 'lastudio-kit' );
	}

	public function get_group() {
		return Module::ACF_GROUP;
	}

	public function get_categories() {
		return [
			Module::NUMBER_CATEGORY,
			Module::POST_META_CATEGORY,
		];
	}

	public function render() {
		list( $field, $meta_key ) = Module::get_tag_value_field( $this );

		if ( $field && ! empty( $field['type'] ) ) {
			$value = $field['value'];
		} else {
			// Field settings has been deleted or not available.
			$value = get_field( $meta_key );
		} // End if().

		echo wp_kses_post( $value );
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	protected function register_controls() {
		Module::add_key_control( $this );
	}

	public function get_supported_fields() {
		return [
			'number',
		];
	}
}
