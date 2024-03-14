<?php

namespace Thim_EL_Kit\Elementor\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag as Tag_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;

class Item_Custom_Field extends Tag_Base {

	public function get_name() {
		return 'thim-item-custom-field';
	}

	public function get_categories() {
		return array( TagsModule::TEXT_CATEGORY );
	}

	public function get_group() {
		return array( 'thim-ekit' );
	}

	public function get_title() {
		return esc_html__( 'Post Custom Field', 'thim-elementor-kit' );
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	public function is_settings_required() {
		return true;
	}

	protected function register_controls() {
		$this->add_control(
			'key',
			[
				'label'   => esc_html__( 'Key', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
 				'options' => $this->get_custom_field(),
			]
		);

		$this->add_control(
			'custom_key',
			[
				'label'       => esc_html__( 'Custom Key', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'key',
				'condition'   => [
					'key' => '',
				],
			]
		);

	}

	public function render() {
		$key = $this->get_settings( 'key' );

		if ( empty( $key ) ) {
			$key = $this->get_settings( 'custom_key' );
		}

		if ( empty( $key ) ) {
			return;
		}

		$value = get_post_meta( get_the_ID(), $key, true );

		echo  wp_kses_post( $value );
	}

	private function get_custom_field() {
		$options = [ '' => esc_html__( 'Select...', 'thim-elementor-kit' ), ];

		return apply_filters( 'thim-ekits\dynamic-tags\item-custom-field', $options );
	}
}
