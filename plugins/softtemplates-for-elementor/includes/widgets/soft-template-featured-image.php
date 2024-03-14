<?php
/**
 * Class: Soft_Template_Featured_Image
 * Name: Featured Images
 * Slug: soft-template-featured-image
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

class Soft_Template_Featured_Image extends SoftTemplate_Base {
    public function get_name() {
		return 'soft-template-featured-image';
	}

	public function get_title() {
		return esc_html__( 'Featured Image', 'soft-template-core' );
	}

    public function get_icon() {
		return 'eicon-image';
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
			'image_content',
			array(
				'label' => esc_html__( 'Post Featured Image', 'soft-template-core' ),
			)
		);

        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'featured_image_size',
				'exclude' => [ 'custom' ],
				'default' => 'large',
			]
		);

        $this->add_control(
			'image_link_to',
			array(
				'label'   => __( 'Link To', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
                    'none'   => esc_html__( 'None', 'soft-template-coret' ),
                    'home'   => esc_html__( 'Home URL', 'soft-template-coret' ),
                    'post'   => esc_html__( 'Post URL', 'soft-template-coret' ),
                    'media'  => esc_html__( 'Media URL', 'soft-template-coret' ),
                    'custom' => esc_html__( 'Custom URL', 'soft-template-coret' ),
				),
			)
		);

		$this->add_control(
			'image_link_to_custom',
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
					'image_link_to' => 'custom',
				),
			]
		);

        $this->end_controls_section();
    }   
    
    public function widget_style() {
		$this->__start_controls_section(
			'style_featured_images',
			array(
				'label'      => esc_html__( 'Featured Images', 'soft-template-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'image_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'soft-template-core' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__( 'Left', 'soft-template-core' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'soft-template-core' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'soft-template-core' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-soft-template-featured-image' => 'text-align: {{VALUE}}',
				),
			)
		);


		$this->__add_responsive_control(
			'image_max_width',
			[
				'label' => __( 'Max Width', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'size_units'    => ['px', 'em', '%'],
                'default'     	=> [ 'size' => 100, 'unit' => '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-featured-image .post-featured-image img'  => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);		

		$this->__add_responsive_control(
			'image_main_width',
			[
				'label' => __( 'Width', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'size_units'    => ['px', 'em', '%'],
                'default'     	=> [ 'unit' => '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-featured-image .post-featured-image img'  => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);			
		
		$this->__add_responsive_control(
			'image_main_height',
			[
				'label' => __( 'Height', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'size_units'    => ['px', 'vh', '%'],
                'default'     	=> [ 'unit' => 'px' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-featured-image .post-featured-image img'  => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);	

		$this->add_responsive_control(
			'object-fit',
			[
				'label' => esc_html__( 'Object Fit', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'condition' => [
					'image_main_height[size]!' => '',
				],
				'options' => [
					'' => esc_html__( 'Default', 'soft-template-core' ),
					'fill' => esc_html__( 'Fill', 'soft-template-core' ),
					'cover' => esc_html__( 'Cover', 'soft-template-core' ),
					'contain' => esc_html__( 'Contain', 'soft-template-core' ),
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} img' => 'object-fit: {{VALUE}};',
				],
			]
		);
        
        $this->__add_responsive_control(
			'image_opacity',
			[
				'label' => __( 'Opacity', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'size_units'    => ['%'],
                'default'     	=> [ 'size' => 100, 'unit' => '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-featured-image .post-featured-image img'  => 'opacity: {{SIZE}}%;',
				],
			]
		);

        $this->__add_responsive_control(
			'image_rotate',
			[
				'label' => __( 'Rotate', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => -360,
                        'max'  => 360,
                        'step' => 1,
					],
				],
				'size_units'    => ['px'],
				'selectors' => [
					'{{WRAPPER}} .elementor-soft-template-featured-image .post-featured-image img' => '-moz-transform: rotate({{SIZE}}deg); -webkit-transform: rotate({{SIZE}}deg); -o-transform: rotate({{SIZE}}deg); -ms-transform: rotate({{SIZE}}deg); transform: rotate({{SIZE}}deg);',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_boxshadow',
                'selector' => '{{WRAPPER}} .elementor-soft-template-featured-image .post-featured-image img'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => esc_html__( 'Border', 'soft-template-core' ),
                'selector' => '{{WRAPPER}} .elementor-soft-template-featured-image .post-featured-image img',
            ]
        ); 

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'soft-template-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-soft-template-featured-image .post-featured-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
			'image_hover_animation',
			[
				'label' => __( 'Hover Animation', 'soft-template-core' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
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

        $animation = ! empty( $settings['image_hover_animation'] ) ? 'elementor-animation-' . esc_attr( $settings['image_hover_animation'] ) : '';

        $images_size = ! empty($settings['featured_image_size']) ? $settings['featured_image_size'] : 'large';
        $image   = get_the_post_thumbnail( get_the_ID(), $images_size ); 
        
        if( !empty( $image ) ) {
            $link_to   = $settings ['image_link_to'];

            switch ( $link_to ) {
				case 'home':
					$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_home_url() ), $image );
					break;
				case 'post':
					$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_the_permalink() ), $image );
					break;
				case 'media':
					$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_the_post_thumbnail_url() ), $image );
					break;
				case 'custom':
					$content = sprintf( '<a href="%1$s">%2$s</a>', esc_url( $settings ['image_link_to_custom']['url'] ), $image );
					break;
				default:
					$content = $image;
					break;
			}

            echo sprintf( '<div class="post-featured-image %1$s">%2$s</div>', $animation, $content );
        } else {
            if( soft_template_core()->elementor_editor_preview() ) {
                $placeholder_image = Utils::get_placeholder_image_src();
                echo sprintf( '<div class="post-featured-image %1$s"><img src="%2$s" alt="Placeholder"/></div>', $animation, $placeholder_image );
            }
        }


		$this->__close_wrap();
    }

}