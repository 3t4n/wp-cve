<?php
namespace LaStudioKitThemeBuilder\Modules\DynamicTags\ACF\Tags;

use LaStudioKitThemeBuilder\Modules\DynamicTags\Tags\Base\Data_Tag;
use LaStudioKitThemeBuilder\Modules\DynamicTags\ACF\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ACF_COLOR extends Data_Tag {

	public function get_name() {
		return 'acf-color';
	}

	public function get_title() {
		return esc_html__( 'ACF', 'lastudio-kit' ) . ' ' . esc_html__( 'Color Picker Field', 'lastudio-kit' );
	}

	public function get_group() {
		return Module::ACF_GROUP;
	}

	public function get_categories() {
		return [ Module::COLOR_CATEGORY ];
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	public function get_value( array $options = [] ) {
		list( $field, $meta_key ) = Module::get_tag_value_field( $this );

		if ( $field ) {
			$value = $field['value'];
		} else {
			// Field settings has been deleted or not available.
			$value = get_field( $meta_key );
		}

		if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
			$value = $this->get_settings( 'fallback' );
		}

		return $value;
	}

	protected function register_controls() {
		Module::add_key_control( $this );
	}

	public function get_supported_fields() {
		return [
			'color_picker',
		];
	}
}
