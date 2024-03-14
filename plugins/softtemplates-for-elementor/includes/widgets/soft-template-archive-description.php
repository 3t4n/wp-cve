<?php
/**
 * Class: Soft_Template_Archive_Description
 * Name: Archive Description
 * Slug: soft-template-archive-description
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

class Soft_Template_Archive_Description extends SoftTemplate_Base {
    public function get_name() {
		return 'soft-template-archive-description';
	}

	public function get_title() {
		return esc_html__( 'Archive Description', 'soft-template-core' );
	}

    public function get_icon() {
		return 'eicon-product-description';
	}

    public function get_jet_help_url() {
		return '#';
	}

    public function get_categories() {
		return array( 'soft-template-core' );
	}

    protected function register_controls() {
        // Widget main
        $this->widget_main_options();
    }

    public function widget_main_options() {
		$this->start_controls_section(
			'section_product_description_style',
			[
				'label' => __( 'Style', 'soft-template-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wc_style_warning',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'The style of this widget is often affected by your theme and plugins. If you experience any such issue, try to switch to a basic theme and deactivate related plugins.', 'soft-template-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_responsive_control(
			'text_align',
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
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'soft-template-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .term-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => __( 'Typography', 'soft-template-core' ),
				'selector' => '{{WRAPPER}} .term-description',
			]
		);

		$this->end_controls_section();
    }

    protected function render() {
        $this->__context = 'render';

        $settings  = $this->get_settings();
        $post_data = \Soft_template_Core_Utils::get_demo_post_data();

        $archive_description = \Soft_template_Core_Utils::get_the_archive_description();

        global $post;
		$post = $post_data;

        $this->__open_wrap();
        if( soft_template_core()->elementor_editor_preview() ) {
            echo '<div class="term-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div>';
        } else {
            echo sprintf("<div class='term-description'>%s</div>",$archive_description);
        }
        $this->__close_wrap();
    }
}