<?php

namespace Thim_EL_Kit\Elementor\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag as Tag_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

class Item_Terms extends Tag_Base {

	public function get_name() {
		return 'thim-item-terms';
	}

	public function get_categories() {
		return array( TagsModule::TEXT_CATEGORY );
	}

	public function get_group() {
		return array( 'thim-ekit' );
	}

	public function get_title() {
		return 'Item Terms';
	}

	public function get_panel_template_setting_key() {
		return 'taxonomy';
	}

	protected function register_controls() {
		$this->add_control(
			'taxonomy',
			[
				'label'       => esc_html__( 'Taxonomy', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'options'     => \Thim_EL_Kit\Elementor::register_option_dynamic_tags_item_terms()
			]
		);

		$this->add_control(
			'show_one',
			[
				'label'   => esc_html__( 'Show One Term', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no'
			]
		);

		$this->add_control(
			'show_link',
			[
				'label'   => esc_html__( 'Show Link', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'separator',
			[
				'label'   => esc_html__( 'Separator', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::TEXT,
				'default' => ', ',
				'condition' => array(
					'show_one!' => 'yes',
				),
			]
		);
	}

	public function render() {

		$settings = $this->get_settings();

		$taxonomy = $settings ['taxonomy'];

		if ( empty( $taxonomy ) ) {
			return false;
		}

		$terms = wp_get_post_terms( get_the_ID(), $taxonomy );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return false;
		}

		$terms_list = [];

		foreach ( $terms as $term ) {
			if ( 'yes' == $settings['show_link'] ) {
				$terms_list[] = '<a href="' . esc_url( get_term_link( $term ) ) . '" class="loop-item-term">' . esc_html( $term->name ) . '</a>';
			} else {
				$terms_list[] = '<span class="loop-item-term">' . esc_html( $term->name ) . '</span>';
			}
		}

		if ( 'yes' == $settings['show_one'] ) {
			$value = $terms_list[0];
		} else {
			$value = implode( $settings['separator'], $terms_list );
		}
		echo wp_kses_post( $value );
	}

}
