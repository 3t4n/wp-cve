<?php
/**
 * Class: Soft_Template_Post_Title
 * Name: Post Title
 * Slug: elementor-soft-template-post-title
 */
namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Soft_Template_Post_Title extends SoftTemplate_Base {
    public function get_name() {
		return 'soft-template-post-title';
	}

	public function get_title() {
		return esc_html__( 'Post Title', 'soft-template-core' );
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
        $this->widget_options();
        $this->widget_style();
    }

    public function widget_options() {
        $this->start_controls_section(
			'title_content',
			array(
				'label' => esc_html__( 'Post Title', 'soft-template-core' ),
			)
		);

        $this->add_control(
			'title_html_tag',
			array(
				'label'   => __( 'Type', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => array(
					'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'div',
                    'span' => 'span',
                    'p'    => 'p',
				),
			)
		);

		$this->add_control(
			'title_link_to',
			array(
				'label'   => __( 'Link To', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'   => esc_html__( 'None', 'soft-template-core' ),
					'home'   => esc_html__( 'Home URL', 'soft-template-core' ),
					'post'   => esc_html__( 'Post URL', 'soft-template-core' ),
					'custom' => esc_html__( 'Custom URL', 'soft-template-core' ),
				),
			)
		);

		$this->add_control(
			'title_link_to_custom',
			[
				'label' => __( 'Link', 'soft-template-core' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'soft-template-core' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition'    => array(
					'title_link_to' => 'custom',
				),
			]
		);

        $this->end_controls_section();
    }   
    
    public function widget_style() {
		$this->__start_controls_section(
			'style_title',
			array(
				'label'      => esc_html__( 'Logo', 'soft-template-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'title_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'soft-template-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'soft-template-core' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'soft-template-core' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'soft-template-core' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-soft-template-post-title' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( ' Typography', 'soft-template-core' ),
				'selector' => '{{WRAPPER}} .elementor-soft-template-post-title .post-title, {{WRAPPER}} .elementor-soft-template-post-title .post-title a',
			]
		);

		$this->__add_responsive_control(
			'title_color_style',
			array(
				'label'     => __( 'Color Style', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-soft-template-post-title .post-title, {{WRAPPER}} .elementor-soft-template-post-title .post-title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_normal_textshadow',
				'label' => __( 'Text Shadow', 'soft-template-core' ),
				'selector' => '{{WRAPPER}} .elementor-soft-template-post-title .post-title, {{WRAPPER}} .elementor-soft-template-post-title .post-title a',
			]
		);


		$this->__end_controls_section();
    }

    protected function render() {
        $this->__context = 'render';

		$this->__open_wrap();

		$content = '';
		$title   = get_the_title();
		$settings  = $this->get_settings();

		if ( ! empty( $title ) ) {
			$link_to   = $settings['title_link_to'];
			$html_tag  = esc_attr( $settings['title_html_tag'] );
			$style     =  'style-color';

			switch ( $link_to ) {
				case 'home':
					$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_home_url() ), $title );
					break;
				case 'post':
					$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_the_permalink() ), $title );
					break;
				case 'custom':
					$content = $this->render_url_element( $settings['title_link_to_custom'], null, null, $title );
					break;
				default:
					$content = $title;
					break;
			}

			echo sprintf( '<%1$s class="post-title %2$s">%3$s</%1$s>', $html_tag, $style, $content );
		}

		$this->__close_wrap();
    }

}