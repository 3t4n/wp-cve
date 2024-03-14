<?php
/**
 * Class: Soft_Template_Archive_Title
 * Name: Archive & Post Title
 * Slug: soft-template-archive-title
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

class Soft_Template_Archive_Title extends SoftTemplate_Base {
    public function get_name() {
		return 'soft-template-archive-title';
	}

	public function get_title() {
		return esc_html__( 'Archive & Post Title', 'soft-template-core' );
	}

    public function get_icon() {
		return 'eicon-post-title';
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

        // Form Style
        $this->widget_main_style();
    }

    public function widget_main_options() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'General', 'soft-template-core' ),
			]
		);
		$this->add_control(
			'title_type',
			[
				'label'   => __( 'Title', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'post_title'    => __( 'Post Title', 'soft-template-core' ),
					'archive_title' => __( 'Archive Title', 'soft-template-core' ),
				],
				'default' => 'post_title',
			]
		);
		$this->add_control(
			'use_link',
			[
				'label'     => __( 'Post Link', 'soft-template-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'1' => [
						'title' => __( 'Yes', 'soft-template-core' ),
						'icon'  => 'fa fa-check',
					],
					'0' => [
						'title' => __( 'No', 'soft-template-core' ),
						'icon'  => 'fa fa-ban',
					],
				],
				'default'   => '1',
				'condition' => [
					'title_type' => 'post_title',
				],
			]
		);
		$this->add_control(
			'title_tag',
			[
				'label'   => __( 'HTML Tag', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => __( 'H1', 'soft-template-core' ),
					'h2'   => __( 'H2', 'soft-template-core' ),
					'h3'   => __( 'H3', 'soft-template-core' ),
					'h4'   => __( 'H4', 'soft-template-core' ),
					'h5'   => __( 'H5', 'soft-template-core' ),
					'h6'   => __( 'H6', 'soft-template-core' ),
					'div'  => __( 'div', 'soft-template-core' ),
					'span' => __( 'span', 'soft-template-core' ),
				],
				'default' => 'h1',
			]
		);

		$this->add_control(
			'strip_title',
			[
				'label'        => __( 'Strip Title', 'soft-template-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'strip_yes'    => __( 'Yes', 'soft-template-core' ),
				'strip_no'     => __( 'No', 'soft-template-core' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'strip_mode',
			[
				'label'     => __( 'Strip Mode', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'word'   => __( 'Word', 'soft-template-core' ),
					'letter' => __( 'Letter', 'soft-template-core' ),
				],
				'default'   => 'word',
				'condition' => [
					'strip_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'strip_size',
			[
				'label'       => __( 'Strip Size', 'soft-template-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Strip Size', 'soft-template-core' ),
				'default'     => __( '5', 'soft-template-core' ),
				'condition'   => [
					'strip_title' => 'yes',
				],
				'description' => __( 'Number of words to show.', 'soft-template-core' ),
			]
		);

		$this->add_control(
			'strip_append',
			[
				'label'       => __( 'Append Title', 'soft-template-core' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Append Text', 'soft-template-core' ),
				'default'     => __( '...', 'soft-template-core' ),
				'condition'   => [
					'strip_title' => 'yes',
				],
				'description' => __( 'What to append if Title needs to be trimmed.', 'soft-template-core' ),
			]
		);

		$this->add_control(
			'title_open_in_new_tab',
			[
				'label'     => __( 'Open in new tab', 'soft-template-core' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'soft-template-core' ),
				'label_on'  => __( 'Yes', 'soft-template-core' ),
				'default'   => __( 'label_off', 'soft-template-core' ),
			]
		);

		$this->add_responsive_control(
			'align',
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
					'{{WRAPPER}} .ae-element-post-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
    }

    public function widget_main_style() {
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __( 'General', 'soft-template-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .ae-element-post-title',
			]
		);

		$this->start_controls_tabs( 'normal' );

		$this->start_controls_tab(
			'normal_tab',
			[
				'label' => __( 'Normal', 'soft-template-core' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ae-element-post-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} .ae-element-post-title',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover_tab',
			[
				'label' => __( 'Hover', 'soft-template-core' ),
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label'     => __( 'Hover Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .ae-element-post-title:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'text_shadow_hover',
				'selector' => '{{WRAPPER}} .ae-element-post-title:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
    }

    protected function render() {
        $this->__context = 'render';

        $settings  = $this->get_settings();
		$post_data = \Soft_template_Core_Utils::get_demo_post_data();

        global $post;
		$post = $post_data;
        
        $this->__open_wrap();
        
        $post_id   = $post_data->ID;
        if ( $settings['title_type'] === 'post_title' ) {
			$post_title = get_the_title( $post_data );
			$post_link  = get_permalink( $post_id );
		} else {
			$post_title           = \Soft_template_Core_Utils::get_the_archive_title();
			$post_link            = '';
			$settings['use_link'] = 0;
		}

        if ( $settings['strip_title'] === 'yes' ) {
			if ( $settings['strip_mode'] === 'word' ) {
				$post_title = wp_trim_words( $post_title, $settings['strip_size'], $settings['strip_append'] );
			} else {
				$post_title = \Soft_template_Core_Utils::trim_letters( $post_title, 0, $settings['strip_size'], $settings['strip_append'] );
			}
		}

        $this->add_render_attribute( 'post-title-class', 'class', 'ae-element-post-title' );

		$title_html = '';
		if ( $settings['use_link'] == 1 ) {
			if ( $settings['title_open_in_new_tab'] === 'yes' ) {
				$this->add_render_attribute( 'post-link-class', 'target', '_blank' );
			}
			$title_html = '<a ' . $this->get_render_attribute_string( 'post-link-class' ) . ' href="' . $post_link . '">';
		}

		$title_html .= sprintf( '<%1$s itemprop="name" %2$s>%3$s</%1$s>', $settings['title_tag'], $this->get_render_attribute_string( 'post-title-class' ), $post_title );

		if ( $settings['use_link'] == 1 ) {
			$title_html .= '</a>';
		}

		echo sprintf("%s",$title_html);

        $this->__close_wrap();
    }
}