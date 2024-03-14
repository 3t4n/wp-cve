<?php

namespace Thim_EL_Kit\Elementor\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag as Tag_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

class Item_date extends Tag_Base {

	public function get_name() {
		return 'thim-item-date';
	}

	public function get_categories() {
		return array( TagsModule::TEXT_CATEGORY );
	}

	public function get_group() {
		return array( 'thim-ekit' );
	}

	public function get_title() {
		return 'Item Date Time';
	}

	protected function register_controls() {
		$this->add_control(
			'custom_date_format',
			[
				'label'       => esc_html__( 'Custom Date Format', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'F j, Y',
				'description' => sprintf(
					__( '<a href="%s">Documentation on date and time formatting.</a>', 'thim-elementor-kit' ), 'https://wordpress.org/documentation/article/customize-date-and-time-format/'
				),
			]
		);
 	}

	public function render() {
		$format_date = get_option( 'date_format' );
		if ( $this->get_settings( 'custom_date_format' ) ) {
			$format_date = get_settings( 'custom_date_format' );
		}

		echo wp_kses_post( get_the_time( $format_date ) );
	}
}
