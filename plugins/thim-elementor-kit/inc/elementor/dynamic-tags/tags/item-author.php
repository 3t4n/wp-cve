<?php

namespace Thim_EL_Kit\Elementor\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag as Tag_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

class Item_Author extends Tag_Base {

	public function get_name() {
		return 'thim-item-author';
	}

	public function get_categories() {
		return array( TagsModule::TEXT_CATEGORY );
	}

	public function get_group() {
		return array( 'thim-ekit' );
	}

	public function get_title() {
		return 'Item Author Name';
	}

	protected function register_controls() {
		$this->add_control(
			'show_link',
			array(
				'label'   => esc_html__( 'Author Link', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			)
		);
		$this->add_control(
			'show_avatar',
			array(
				'label'   => esc_html__( 'Author Avatar', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			)
		);
		$this->add_control(
			'avatar_size',
			array(
				'label'     => esc_html__( 'Size', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'condition' => array(
					'show_avatar' => 'yes',
				),
			)
		);
		$this->add_control(
			'avatar_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'condition' => array(
					'show_avatar' => 'yes',
				),
			)
		);
	}

	public function render() {
		$author_name = '';
		$settings    = $this->get_settings_for_display();

		if ( 'yes' === $settings['show_avatar'] ) {
			$author_avatar_style  = ! empty( $settings['avatar_size']['size'] ) ? 'width: ' . $settings['avatar_size']['size'] . $settings['avatar_size']['unit'] . ';' : '';
			$avatar_border_radius = ! empty( $settings['avatar_border_radius']['size'] ) ? 'border-radius: ' . $settings['avatar_border_radius']['size'] . $settings['avatar_border_radius']['unit'] . ';' : '';

			$author_name .= '<span class="elementor-icon-list-icon author-avatar" style="' . $author_avatar_style . '"><img style="' . $avatar_border_radius . '" src="' . get_avatar_url( get_the_author_meta( 'ID' ), 96 ) . '"></span>';
		}

		$author_name .= get_the_author_meta( 'display_name' );

		if ( 'yes' === $settings['show_link'] ) {
			$author_name = sprintf( '<a href="%s">%s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), $author_name );
		}

		echo wp_kses_post( $author_name );
	}
}
