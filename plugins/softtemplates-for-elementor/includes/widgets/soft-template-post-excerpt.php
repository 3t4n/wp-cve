<?php
/**
 * Class: Soft_Template_Post_Excerpt
 * Name: Post Excerpt
 * Slug: soft-template-post-excerpt
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Soft_Template_Post_Excerpt extends SoftTemplate_Base {
    public function get_name() {
		return 'soft-template-post-excerpt';
	}

	public function get_title() {
		return esc_html__( 'Post Excerpt', 'soft-template-core' );
	}

    public function get_icon() {
		return 'eicon-post-excerpt';
	}

    public function get_jet_help_url() {
		return '#';
	}

    public function get_categories() {
		return array( 'soft-template-core' );
	}

    protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'soft-template-core' ),
			]
		);

        $this->add_control(
			'excerpt_type',
			[
				'label' => __( 'Excerpt Type', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'dynamic'  => __( 'Dynamic', 'soft-template-core' ),
					'custom' => __( 'Custom', 'soft-template-core' ),
				],
			]
		);

		$this->add_control(
			'excerpt',
			[
				'type' => Controls_Manager::WYSIWYG,
				'label_block' => true,
                'condition' => array(
					'excerpt_type' => 'custom',
				),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'soft-template-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'soft-template-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'soft-template-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'soft-template-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'soft-template-core' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'soft-template-core' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'soft-template-core' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .elementor-widget-container',
			]
		);

		$this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if( $settings['excerpt_type'] == 'custom' ) {
            echo $this->get_settings_for_display( 'excerpt' );
        } else {
            if( ! has_excerpt() ) {
                if( soft_template_core()->elementor_editor_preview() ) {
                    echo 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';
                }
            } else {
                the_excerpt();
            }
        }
    }
}