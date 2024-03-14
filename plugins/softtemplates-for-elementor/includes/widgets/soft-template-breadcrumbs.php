<?php
/**
 * Class: Soft_Template_Breadcrumbs
 * Name: Breadcrumbs
 * Slug: soft-template-breadcrumbs
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

class Soft_Template_Breadcrumbs extends SoftTemplate_Base {
    public function get_name() {
		return 'soft-template-breadcrumbs';
	}

	public function get_title() {
		return esc_html__( 'Breadcrumbs', 'soft-template-core' );
	}

    public function get_icon() {
		return 'eicon-yoast';
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
			'section_General_title',
			[
				'label' => __( 'General Style', 'soft-template-core' ),
			]
		);

        $info_message = 'Yoast SEO/Rank Math SEO Plugin need to be installed.';

		if ( soft_template_core()->has_yoast_seo() ) {
			$info_message = 'Additional settings are available in the Yoast SEO <a href="' . admin_url('admin.php?page=wpseo_titles#top#breadcrumbs') . '" target="_blank">Breadcrumbs Panel</a>';
		}

		if ( soft_template_core()->has_rank_math() ) {
			$info_message = 'Additional settings are available in the Rank Math SEO <a href="' . admin_url('admin.php?page=rank-math-options-general#setting-panel-breadcrumbs') . '" target="_blank">Breadcrumbs Panel</a>';
		}
        
		$this->add_control(
			'ae_breadcrumb_raw_html',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'raw'             => $info_message,
				'separator'       => 'after',
			]
		);

		$this->add_responsive_control(
			'anchor_align',
			[
				'label'     => __( 'Alignment', 'soft-template-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left' => [
						'title' => __( 'Left', 'soft-template-core' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'soft-template-core' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'soft-template-core' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-breadcrumbs' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'separator_color',
			[
				'label'     => __( 'Separator Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-breadcrumbs span span, {{WRAPPER}} .elementor-soft-template-breadcrumbs span.separator' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'anchor_style',
			[
				'label'     => __( 'Anchor Style', 'soft-template-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->start_controls_tabs( 'button_style' );
		$this->start_controls_tab( 'anchor_normal', [ 'label' => __( 'Normal', 'soft-template-core' ) ] );
		$this->add_control(
			'anchor_normal_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-breadcrumbs a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'anchor_normal_typography',
				'label'    => __( 'Anchor Typography', 'soft-template-core' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .elementor-soft-template-breadcrumbs a',
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab( 'anchor_hover', [ 'label' => __( 'Hover', 'soft-template-core' ) ] );
		$this->add_control(
			'anchor_hover_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-breadcrumbs a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'anchor_hover_typography',
				'label'    => __( 'Anchor Typography', 'soft-template-core' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .elementor-soft-template-breadcrumbs  a:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'current_page_style',
			[
				'label'     => __( 'Current Page Style', 'soft-template-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'current_page_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-breadcrumbs span .breadcrumb_last, {{WRAPPER}} span.last' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'current_page_typography',
				'label'     => __( 'Current Page Typography', 'soft-template-core' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-breadcrumbs .breadcrumb_last, {{WRAPPER}} .last',
				],
			]
		);

		$this->end_controls_section();
    }

    protected function render() {
		$breadcrumbs = '';
		if ( soft_template_core()->has_yoast_seo() ) {
			$breadcrumbs = yoast_breadcrumb( '', '', false );
		}
		if ( soft_template_core()->has_rank_math() ) {
			$breadcrumbs = rank_math_the_breadcrumbs();
		}

        $this->__context = 'render';

        $this->__open_wrap();
        if( soft_template_core()->elementor_editor_preview() ) {
            echo '<nav class="breadcrumb"><span><span><a href="#">Home</a> » <span><a href="#">Help</a> » <span><a href="#">WordPress</a> » <span class="breadcrumb_last" aria-current="page">Breadcrumbs</span></span></span></span></span></nav>';
        } else {
            echo sprintf("<div class='soft_template'>%s</div>", $breadcrumbs);
        }
        $this->__close_wrap();
	}
}