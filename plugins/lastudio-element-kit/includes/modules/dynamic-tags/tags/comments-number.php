<?php
namespace LaStudioKitThemeBuilder\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use LaStudioKitThemeBuilder\Modules\DynamicTags\Tags\Base\Tag;
use LaStudioKitThemeBuilder\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Comments_Number extends Tag {

	public function get_name() {
		return 'comments-number';
	}

	public function get_title() {
		return esc_html__( 'Comments Number', 'lastudio-kit' );
	}

	public function get_group() {
		return Module::COMMENTS_GROUP;
	}

	public function get_categories() {
		return [
			Module::TEXT_CATEGORY,
			Module::NUMBER_CATEGORY,
		];
	}

	protected function register_controls() {
		$this->add_control(
			'format_no_comments',
			[
				'label' => esc_html__( 'No Comments Format', 'lastudio-kit' ),
				'default' => esc_html__( 'No Responses', 'lastudio-kit' ),
			]
		);

		$this->add_control(
			'format_one_comments',
			[
				'label' => esc_html__( 'One Comment Format', 'lastudio-kit' ),
				'default' => esc_html__( 'One Response', 'lastudio-kit' ),
			]
		);

		$this->add_control(
			'format_many_comments',
			[
				'label' => esc_html__( 'Many Comment Format', 'lastudio-kit' ),
				'default' => esc_html__( '{number} Responses', 'lastudio-kit' ),
			]
		);

		$this->add_control(
			'link_to',
			[
				'label' => esc_html__( 'Link', 'lastudio-kit' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'None', 'lastudio-kit' ),
					'comments_link' => esc_html__( 'Comments Link', 'lastudio-kit' ),
				],
			]
		);
	}

	public function render() {
		$settings = $this->get_settings();

		$comments_number = get_comments_number();

		if ( ! $comments_number ) {
			$count = $settings['format_no_comments'];
		} elseif ( 1 === $comments_number ) {
			$count = $settings['format_one_comments'];
		} else {
			$count = strtr( $settings['format_many_comments'], [
				'{number}' => number_format_i18n( $comments_number ),
			] );
		}

		if ( 'comments_link' === $this->get_settings( 'link_to' ) ) {
			$count = sprintf( '<a href="%s">%s</a>', get_comments_link(), $count );
		}

		echo wp_kses_post( $count );
	}
}
