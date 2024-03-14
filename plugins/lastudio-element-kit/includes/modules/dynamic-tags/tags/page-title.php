<?php
namespace LaStudioKitThemeBuilder\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use LaStudioKitThemeBuilder\Modules\DynamicTags\Tags\Base\Tag;
use LaStudioKitThemeBuilder\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Page_Title extends Tag {
	public function get_name() {
		return 'page-title';
	}

	public function get_title() {
		return esc_html__( 'Page Title', 'lastudio-kit' );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		if ( is_home() && 'yes' !== $this->get_settings( 'show_home_title' ) ) {
			return;
		}

		if ( lastudio_kit()->elementor()->common ) {
			$current_action_data = lastudio_kit()->elementor()->common->get_component( 'ajax' )->get_current_action_data();

			if ( $current_action_data && 'render_tags' === $current_action_data['action'] ) {
				// Override the global $post for the render.
				query_posts(
					[
						'p' => get_the_ID(),
						'post_type' => 'any',
					]
				);
			}
		}

		$include_context = 'yes' === $this->get_settings( 'include_context' );

		$title = lastudio_kit_helper()->get_page_title( $include_context );

		echo wp_kses_post( $title );
	}

	protected function register_controls() {
		$this->add_control(
			'include_context',
			[
				'label' => esc_html__( 'Include Context', 'lastudio-kit' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'show_home_title',
			[
				'label' => esc_html__( 'Show Home Title', 'lastudio-kit' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);
	}
}
