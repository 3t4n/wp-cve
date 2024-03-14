<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * @since 1.1.0
 */

class CF7_Views_Elementor_Widget extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'cf7_views_widget';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'CF7 Views', 'cf7-views' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'contact form' );
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	// public function get_script_depends() {
	// return array( 'elementor-bb-frontend' );
	// }

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_content_layout',
			array(
				'label' => esc_html__( 'Select View', 'cf7-views' ),
			)
		);

		$this->add_control(
			'cf7_view',
			array(
				'label'   => esc_html__( 'Select Contact Form 7 View', 'cf7-views' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'active',
				'options' => $this->get_cf7_views(),
			)
		);

		$this->end_controls_section();

	}
	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		$id       = $settings['cf7_view'];
		if ( ! empty( $id ) ) {
			echo do_shortcode( '[cf7-views id =' . $id . ']' );
		}
	}

	protected function get_cf7_views() {
		$views = array();
		$query = new WP_Query(
			array(
				'post_type'      => 'cf7-views',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			)
		);

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
					$query->the_post();
					$views[ get_the_ID() ] = get_the_title();
			}
		} else {

			$views[] = 'No View found';
		}

		return $views;
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	/*
	protected function _content_template() {

	}*/
}
