<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Softtemplate_Single_Document extends Softtemplate_Document_Base {

	public function get_name() {
		return 'softtemplate_single';
	}

	public static function get_title() {
		return __( 'Single', 'soft-template-core' );
	}

	public function get_preview_as_query_args() {

		$post_type = $this->get_settings( 'preview_post_type_single' );
		$post_id   = $this->get_settings( 'preview_post_id' );

		if ( ! $post_type ) {
			$post_type = 'post';
		}

		$args = array(
			'post_type'           => $post_type,
			'numberposts'         => 1,
			'ignore_sticky_posts' => true,
		);

		if ( ! empty( $post_id ) ) {

			$pid = is_array( $post_id ) ? $post_id[0] : $post_id;

			if ( get_post_type( $pid ) === $post_type ) {
				unset( $args['numberposts'] );
				$args['p'] = absint( $pid );
			}

		}

		$posts = get_posts( $args );

		if ( ! empty( $posts ) ) {
			return array(
				'p'         => $posts[0]->ID,
				'post_type' => $post_type,
			);
		} else {
			return false;
		}

	}

	/**
	 * @since 2.0.0
	 * @access protected
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->start_controls_section(
			'softtemplate_template_preview_single',
			array(
				'label' => __( 'Preview', 'soft-template-core' ),
				'tab' => Elementor\Controls_Manager::TAB_SETTINGS,
			)
		);

		$this->add_control(
			'preview_post_type_single',
			array(
				'label'    => esc_html__( 'Post Type', 'soft-template-core' ),
				'type'     => Elementor\Controls_Manager::SELECT2,
				'default'  => 'post',
				'options'  => Soft_template_Core_Utils::get_post_types(),
			)
		);

		$this->add_control(
			'preview_post_id',
			array(
				'label'        => __( 'Select Post', 'soft-template-core' ),
				'type'         => 'softtemplate_search',
				'action'       => 'soft_template_search_posts',
				'query_params' => array( 'preview_post_type_single' ),
				'label_block'  => true,
				'multiple'     => true,
				'saved'        => $this->get_preview_post_id_for_settings(),
				'description'  => __( 'Please remove selected post after changing preview post type', 'soft-template-core' ),
			)
		);

		$this->add_control(
			'preview_notice_single',
			array(
				'type'      => Elementor\Controls_Manager::RAW_HTML,
				'separator' => 'before',
				'raw'       => __( 'Please reload page after applying preview settings', 'soft-template-core' ),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * [get_preview_post_id_for_settings description]
	 * @return [type] [description]
	 */
	public function get_preview_post_id_for_settings() {

		$settings  = $this->get_main_meta( '_elementor_page_settings' );
		$post_type = ! empty( $settings['preview_post_type_single'] ) ? $settings['preview_post_type_single'] : 'post';

		if ( ! empty( $settings['preview_post_id'] ) ) {

			$pid = is_array( $settings['preview_post_id'] ) ? $settings['preview_post_id'] : array( $settings['preview_post_id'] );

			$posts = get_posts( array(
				'post_type'           => $post_type,
				'post__in'            => $pid,
				'ignore_sticky_posts' => true,
			) );

			if ( empty( $posts ) ) {
				return array();
			} else {
				return wp_list_pluck( $posts, 'post_title', 'ID' );
			}

		} else {
			return array();
		}

	}

}
