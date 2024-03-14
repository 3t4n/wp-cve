<?php

namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Archive_Description extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );


	}

	public function get_name() {
		return 'thim-ekits-archive-desc';
	}

	public function get_title() {
		return esc_html__( 'Archive Description', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-post-excerpt';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_keywords() {
		return array( 'desc', 'excerpt', 'content' );
	}

	protected function register_controls() {
 		$this->start_controls_section(
			'page_title_settings',
			[
				'label' => esc_html__( 'Setting', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-archive-description' => 'text-align: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
 				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-archive-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'page_title_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-ekit-archive-description',
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		echo '<div class="thim-ekit-archive-description">' . get_the_archive_description() . '</div>';
	}
	protected function content_template() {
		echo '<div class="thim-ekit-archive-description">' . __('Description of archive','thim-elementor-kit'). '</div>';
	}
}
