<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Softtemplate_Archive_Document extends Softtemplate_Document_Base {

	public function get_name() {
		return 'softtemplate_archive';
	}

	public static function get_title() {
		return __( 'Archive', 'soft-template-core' );
	}

	public function get_preview_as_query_args() {

		$post_type = $this->get_settings( 'preview_post_type_archive' );

		if ( ! $post_type ) {
			$post_type = 'post';
		}

		return array(
			'post_type'   => $post_type,
			'numberposts' => get_option( 'posts_per_page', 10 ),
		);
	}

	/**
	 * @since 2.0.0
	 * @access protected
	 */
	protected function register_controls() {

		parent::register_controls();

		$this->start_controls_section(
			'softtemplate_template_preview_archive',
			array(
				'label' => __( 'Preview', 'soft-template-core' ),
				'tab' => Elementor\Controls_Manager::TAB_SETTINGS,
			)
		);

		$this->add_control(
			'preview_post_type_archive',
			array(
				'label'    => esc_html__( 'Post Type', 'soft-template-core' ),
				'type'     => Elementor\Controls_Manager::SELECT2,
				'default'  => 'post',
				'options'  => Soft_template_Core_Utils::get_post_types(),
			)
		);

		$this->add_control(
			'preview_notice_archive',
			array(
				'type'      => Elementor\Controls_Manager::RAW_HTML,
				'separator' => 'before',
				'raw'       => __( 'Please reload page after applying preview settings', 'soft-template-core' ),
			)
		);

		$this->end_controls_section();

	}

}
