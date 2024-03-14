<?php

namespace Thim_EL_Kit\Elementor\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag as Tag_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

class Item_Excerpt extends Tag_Base {

	public function get_name() {
		return 'thim-item-excerpt';
	}

	public function get_categories() {
		return array( TagsModule::TEXT_CATEGORY );
	}

	public function get_group() {
		return array( 'thim-ekit' );
	}

	public function get_title() {
		return 'Item Excerpt';
	}

	protected function register_controls() {
		$this->add_control(
			'max_length',
			array(
				'label'   => esc_html__( 'Excerpt Length', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '20',
			)
		);

		$this->add_control(
			'excerpt_more',
			array(
				'label'   => esc_html__( 'Excerpt More', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '...',
				'ai'      => array(
					'active' => false,
				),
			)
		);
	}


	public function render() {

		$settings     = $this->get_settings_for_display();
		$max_length   = (int) $settings['max_length'];
		$excerpt      = get_the_excerpt( get_the_ID() );
		$excerpt_more = ! empty( $settings['excerpt_more'] ) ? $settings['excerpt_more'] : '';

		if ( empty( $excerpt ) ) {
			return;
		}

		$excerpt = wp_trim_words( $excerpt, $max_length, $excerpt_more );

		echo wp_kses_post( $excerpt );
	}

}
