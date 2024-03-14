<?php

namespace Thim_EL_Kit\Elementor\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag as Tag_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

class Item_Comment extends Tag_Base {

	public function get_name() {
		return 'thim-item-comment';
	}

	public function get_categories() {
		return array( TagsModule::TEXT_CATEGORY );
	}

	public function get_group() {
		return array( 'thim-ekit' );
	}

	public function get_title() {
		return 'Item Comment';
	}

	protected function register_controls() {
		$this->add_control(
			'text_no_comments',
			[
				'label'   => esc_html__( 'No Comments', 'thim-elementor-kit' ),
				'default' => esc_html__( 'No Responses', 'thim-elementor-kit' ),
			]
		);

		$this->add_control(
			'text_one_comments',
			[
				'label'   => esc_html__( 'One Comment', 'thim-elementor-kit' ),
				'default' => esc_html__( 'One Response', 'thim-elementor-kit' ),
			]
		);

		$this->add_control(
			'text_many_comments',
			[
				'label'   => esc_html__( 'Many Comment', 'thim-elementor-kit' ),
				'default' => esc_html__( '{number} Responses', 'thim-elementor-kit' ),
			]
		);

		$this->add_control(
			'show_link',
			[
				'label'   => esc_html__( 'Comment Link', 'thim-elementor-kit' ),
 				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);
	}

	public function render() {
		$settings = $this->get_settings();

		$comments_number = get_comments_number();

		if ( ! $comments_number ) {
			$count = $settings['text_no_comments'];
		} elseif ( 1 === $comments_number ) {
			$count = $settings['text_one_comments'];
		} else {
			$count = strtr( $settings['text_many_comments'], [
				'{number}' => number_format_i18n( $comments_number ),
			] );
		}

		if ( 'yes' === $this->get_settings( 'show_link' ) ) {
			$count = sprintf( '<a href="%s">%s</a>', get_comments_link(), $count );
		}

		echo wp_kses_post( $count );
	}
}
