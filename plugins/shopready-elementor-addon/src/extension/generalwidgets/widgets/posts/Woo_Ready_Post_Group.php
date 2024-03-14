<?php

namespace Shop_Ready\extension\generalwidgets\widgets\posts;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_Ready_Post_Group extends \Shop_Ready\extension\generalwidgets\Widget_Base {


	public $wrapper_class = false;
	static function content_layout_style() {
		return apply_filters(
			'shop_ready_gen_post_groups',
			array(

				'1' => __( 'Layout One', 'shopready-elementor-addon' ),

			)
		);
	}

	static function woo_ready_get_post_types( $args = array() ) {
		$post_type_args = array(
			'show_in_nav_menus' => true,
		);
		if ( ! empty( $args['post_type'] ) ) {
			$post_type_args['name'] = $args['post_type'];
		}
		$_post_types = get_post_types( $post_type_args, 'objects' );

		$post_types = array();
		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}
		return $post_types;
	}

	static function woo_ready_get_taxonomies( $element_ready_texonomy = 'category' ) {
		$terms = get_terms(
			array(
				'taxonomy'   => $element_ready_texonomy,
				'hide_empty' => true,
			)
		);
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->slug ] = $term->name;
			}
			return $options;
		}
	}

	protected function register_controls() {
		$this->start_controls_section(
			'post_content_section',
			array(
				'label' => __( 'Post Content', 'shopready-elementor-addon' ),
			)
		);

		$this->add_control(
			'content_layout_style',
			array(
				'label'   => __( 'Layout', 'shopready-elementor-addon' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => self::content_layout_style(),
			)
		);

		$this->add_control(
			'post_masonry',
			array(
				'label'        => __( 'Post Masonry', 'shopready-elementor-addon' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'shopready-elementor-addon' ),
				'label_off'    => __( 'Off', 'shopready-elementor-addon' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		// Content Option Start
		$this->start_controls_section(
			'post_content_option',
			array(
				'label' => __( 'Post Option', 'shopready-elementor-addon' ),
			)
		);

		$this->add_control(
			'element_ready_post_type',
			array(
				'label'       => esc_html__( 'Content Sourse', 'shopready-elementor-addon' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'options'     => self::woo_ready_get_post_types(),
			)
		);

		$this->add_control(
			'posts_categories',
			array(
				'label'       => esc_html__( 'Categories', 'shopready-elementor-addon' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => self::woo_ready_get_taxonomies(),
				'condition'   => array(
					'element_ready_post_type' => 'post',
				),
			)
		);

		$this->add_control(
			'woo_ready_prod_categories',
			array(
				'label'       => esc_html__( 'Categories', 'shopready-elementor-addon' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => self::woo_ready_get_taxonomies( 'product_cat' ),
				'condition'   => array(
					'element_ready_post_type' => 'product',
				),
			)
		);

		$this->add_control(
			'post_limit',
			array(
				'label'     => __( 'Limit', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'custom_order',
			array(
				'label'        => esc_html__( 'Custom order', 'shopready-elementor-addon' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'postorder',
			array(
				'label'     => esc_html__( 'Order', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'DESC',
				'options'   => array(
					'DESC' => esc_html__( 'Descending', 'shopready-elementor-addon' ),
					'ASC'  => esc_html__( 'Ascending', 'shopready-elementor-addon' ),
				),
				'condition' => array(
					'custom_order!' => 'yes',
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'     => esc_html__( 'Orderby', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'          => esc_html__( 'None', 'shopready-elementor-addon' ),
					'ID'            => esc_html__( 'ID', 'shopready-elementor-addon' ),
					'date'          => esc_html__( 'Date', 'shopready-elementor-addon' ),
					'name'          => esc_html__( 'Name', 'shopready-elementor-addon' ),
					'title'         => esc_html__( 'Title', 'shopready-elementor-addon' ),
					'comment_count' => esc_html__( 'Comment count', 'shopready-elementor-addon' ),
					'rand'          => esc_html__( 'Random', 'shopready-elementor-addon' ),
				),
				'condition' => array(
					'custom_order' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_thumb',
			array(
				'label'        => esc_html__( 'Thumbnail', 'shopready-elementor-addon' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'label'     => esc_html__( 'Thumb Size', 'shopready-elementor-addon' ),
				'name'      => 'thumb_size',
				'default'   => 'large',
				'condition' => array(
					'show_thumb' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_category',
			array(
				'label'        => esc_html__( 'Category', 'shopready-elementor-addon' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_author',
			array(
				'label'        => esc_html__( 'Author', 'shopready-elementor-addon' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_date',
			array(
				'label'        => esc_html__( 'Date', 'shopready-elementor-addon' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'date_type',
			array(
				'label'     => esc_html__( 'Date Type', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'date',
				'options'   => array(
					'date'      => esc_html__( 'Date', 'shopready-elementor-addon' ),
					'time'      => esc_html__( 'Time', 'shopready-elementor-addon' ),
					'time_ago'  => esc_html__( 'Time Ago', 'shopready-elementor-addon' ),
					'date_time' => esc_html__( 'Date and Time', 'shopready-elementor-addon' ),
				),
				'condition' => array(
					'show_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => esc_html__( 'Title', 'shopready-elementor-addon' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'title_length',
			array(
				'label'     => __( 'Title Length', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::NUMBER,
				'step'      => 1,
				'default'   => 5,
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_content',
			array(
				'label'        => esc_html__( 'Content', 'shopready-elementor-addon' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'content_length',
			array(
				'label'     => __( 'Content Length', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::NUMBER,
				'step'      => 1,
				'default'   => 20,
				'condition' => array(
					'show_content' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_read_more_btn',
			array(
				'label'        => esc_html__( 'Read More', 'shopready-elementor-addon' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'read_more_txt',
			array(
				'label'       => __( 'Read More button text', 'shopready-elementor-addon' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Read More', 'shopready-elementor-addon' ),
				'placeholder' => __( 'Read More', 'shopready-elementor-addon' ),
				'label_block' => true,
				'condition'   => array(
					'show_read_more_btn' => 'yes',
				),
			)
		);

		$this->add_control(
			'readmore_icon',
			array(
				'label'       => __( 'Readmore Icon', 'shopready-elementor-addon' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'condition'   => array(
					'show_read_more_btn' => 'yes',
				),
			)
		);

		$this->add_control(
			'readmore_icon_position',
			array(
				'label'     => __( 'Icon Postion', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'right',
				'options'   => array(
					'left'  => __( 'Left', 'shopready-elementor-addon' ),
					'right' => __( 'Right', 'shopready-elementor-addon' ),
				),
				'condition' => array(
					'readmore_icon!' => '',
				),
			)
		);

		// Button Icon Margin
		$this->add_control(
			'readmore_icon_indent',
			array(
				'label'     => __( 'Icon Spacing', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'condition' => array(
					'readmore_icon!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .readmore__btn .readmore_icon_right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .readmore__btn .readmore_icon_left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section(); // Content Option End

		/*
		-----------------------
			BOX STYLE
		-------------------------*/
		$this->start_controls_section(
			'post_slider_content_box',
			array(
				'label' => __( 'Box', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'box_background',
				'label'    => __( 'Background', 'shopready-elementor-addon' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .woo__ready__single__post',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'box_border',
				'label'    => __( 'Border', 'shopready-elementor-addon' ),
				'selector' => '{{WRAPPER}} .woo__ready__single__post',
			)
		);

		$this->add_responsive_control(
			'box_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .woo__ready__single__post' => 'overflow:hidden;border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',

				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .woo__ready__single__post',
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => __( 'Padding', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo__ready__single__post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_margin',
			array(
				'label'      => __( 'Margin', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo__ready__single__post' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .slick-list' => 'margin: -{{TOP}}{{UNIT}} -{{RIGHT}}{{UNIT}} -{{BOTTOM}}{{UNIT}} -{{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'box_item_margin_vartically',
			array(
				'label'              => __( 'Item Margin Vartically', 'shopready-elementor-addon' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', '%', 'em' ),
				'allowed_dimensions' => array( 'top', 'bottom' ),
				'selectors'          => array(
					'{{WRAPPER}} .woo__ready__single__post' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom:{{BOTTOM}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_nth_child_margin',
			array(
				'label'      => __( 'Nth Child 2 Margin Vartically', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => -200,
						'max'  => 200,
						'step' => 5,
					),
					'%'  => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .woo__ready__single__post:nth-child(2n)' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_item_hover_margin',
			array(
				'label'      => __( 'Item Hover Margin Vartically', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => -200,
						'max'  => 200,
						'step' => 5,
					),
					'%'  => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .woo__ready__single__post:hover' => 'transform: translateY({{SIZE}}{{UNIT}});',
				),
			)
		);

		$this->end_controls_section();
		/*
		-----------------------
			BOX STYLE END
		-------------------------*/

		/*
		-----------------------
			CONTENT STYLE
		-------------------------*/
		$this->start_controls_section(
			'post_slider_content_style_section',
			array(
				'label'     => __( 'Content', 'shopready-elementor-addon' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_content' => 'yes',
				),
			)
		);
		$this->add_control(
			'content_color',
			array(
				'label'     => __( 'Color', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woo__ready__single__post .post__content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'label'    => __( 'Typography', 'shopready-elementor-addon' ),
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__content',
			)
		);

		$this->add_responsive_control(
			'content_margin',
			array(
				'label'      => __( 'Margin', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo__ready__single__post .post__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => __( 'Padding', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo__ready__single__post .post__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_align',
			array(
				'label'     => __( 'Alignment', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'shopready-elementor-addon' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'shopready-elementor-addon' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'shopready-elementor-addon' ),
						'icon'  => 'fa fa-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'shopready-elementor-addon' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .woo__ready__single__post .post__content' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
		/*
		-----------------------
			CONTENT STYLE END
		-------------------------*/

		/*
		-----------------------
			TITLE STYLE
		-------------------------*/
		$this->start_controls_section(
			'post_slider_title_style_section',
			array(
				'label'     => __( 'Title', 'shopready-elementor-addon' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);
		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woo__ready__single__post .post__content .post__title a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => __( 'Hover Color', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woo__ready__single__post .post__content .post__title a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'shopready-elementor-addon' ),
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__content .post__title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo__ready__single__post .post__content .post__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_padding',
			array(
				'label'      => __( 'Padding', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo__ready__single__post .post__content .post__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
		/*
		-----------------------
			TITLE STYLE END
		-------------------------*/

		/*
		-----------------------
			CATEGORY STYLE
		-------------------------*/
		$this->start_controls_section(
			'post_slider_category_style_section',
			array(
				'label'     => __( 'Category', 'shopready-elementor-addon' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'category_style_tabs' );

		$this->start_controls_tab(
			'category_style_normal_tab',
			array(
				'label' => __( 'Normal', 'shopready-elementor-addon' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'category_typography',
				'label'    => __( 'Typography', 'shopready-elementor-addon' ),
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__category li a',
			)
		);

		$this->add_control(
			'category_color',
			array(
				'label'     => __( 'Color', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woo__ready__single__post .post__category li a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'category_background',
				'label'    => __( 'Background', 'shopready-elementor-addon' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__category li a',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'category_border',
				'label'    => __( 'Border', 'shopready-elementor-addon' ),
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__category li a',
			)
		);

		$this->add_responsive_control(
			'category_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .woo__ready__single__post .post__category li a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'category_shadow',
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__category li a',
			)
		);

		$this->add_responsive_control(
			'category_padding',
			array(
				'label'      => __( 'Padding', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo__ready__single__post .post__category li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'category_margin',
			array(
				'label'      => __( 'Margin', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo__ready__single__post .post__category li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab(); // Normal Tab end

		$this->start_controls_tab(
			'category_style_hover_tab',
			array(
				'label' => __( 'Hover', 'shopready-elementor-addon' ),
			)
		);
		$this->add_control(
			'category_hover_color',
			array(
				'label'     => __( 'Color', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woo__ready__single__post .post__category li a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'category_hover_background',
				'label'    => __( 'Background', 'shopready-elementor-addon' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__category li a:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'category_hover_border',
				'label'    => __( 'Border', 'shopready-elementor-addon' ),
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__category li a:hover',
			)
		);

		$this->add_responsive_control(
			'category_hover_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .woo__ready__single__post .post__category li a:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'category_hover_shadow',
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__category li a:hover',
			)
		);

		$this->end_controls_tab(); // Hover Tab end

		$this->end_controls_tabs();

		$this->end_controls_section();
		/*
		-----------------------
			CATEGORY STYLE END
		-------------------------*/

		/*
		-----------------------
			META STYLE
		-------------------------*/
		$this->start_controls_section(
			'post_meta_style_section',
			array(
				'label' => __( 'Meta', 'shopready-elementor-addon' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_typography',
				'label'    => __( 'Typography', 'shopready-elementor-addon' ),
				'selector' => '{{WRAPPER}} .woo__ready__single__post ul.post__meta li',
			)
		);

		$this->add_control(
			'meta_color',
			array(
				'label'     => __( 'Color', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woo__ready__single__post ul.post__meta'                           => 'color: {{VALUE}}',
					'{{WRAPPER}} .woo__ready__single__post ul.post__meta a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'meta_margin',
			array(
				'label'      => __( 'Margin', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo__ready__single__post ul.post__meta li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'meta_padding',
			array(
				'label'      => __( 'Padding', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo__ready__single__post ul.post__meta li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/*
		-----------------------
			META STYLE END
		-------------------------*/

		/*
		-----------------------
			READMORE STYLE
		-------------------------*/
		$this->start_controls_section(
			'post_slider_readmore_style_section',
			array(
				'label'     => __( 'Read More', 'shopready-elementor-addon' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_read_more_btn' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'readmore_style_tabs' );

		$this->start_controls_tab(
			'readmore_style_normal_tab',
			array(
				'label' => __( 'Normal', 'shopready-elementor-addon' ),
			)
		);

		$this->add_control(
			'readmore_color',
			array(
				'label'     => __( 'Color', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woo__ready__single__post .post__btn a.readmore__btn' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'readmore_typography',
				'label'    => __( 'Typography', 'shopready-elementor-addon' ),
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__btn a.readmore__btn',
			)
		);

		$this->add_responsive_control(
			'readmore_margin',
			array(
				'label'      => __( 'Margin', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo__ready__single__post .post__btn a.readmore__btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'readmore_padding',
			array(
				'label'      => __( 'Padding', 'shopready-elementor-addon' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woo__ready__single__post .post__btn a.readmore__btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'readmore_background',
				'label'    => __( 'Background', 'shopready-elementor-addon' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__btn a.readmore__btn',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'readmore_border',
				'label'    => __( 'Border', 'shopready-elementor-addon' ),
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__btn a.readmore__btn',
			)
		);

		$this->add_responsive_control(
			'readmore_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .woo__ready__single__post .post__btn a.readmore__btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'readmore_shadow',
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__btn a.readmore__btn',
			)
		);

		$this->end_controls_tab(); // Normal Tab end

		$this->start_controls_tab(
			'readmore_style_hover_tab',
			array(
				'label' => __( 'Hover', 'shopready-elementor-addon' ),
			)
		);
		$this->add_control(
			'readmore_hover_color',
			array(
				'label'     => __( 'Color', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woo__ready__single__post .post__btn a.readmore__btn:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'readmore_hover_background',
				'label'    => __( 'Background', 'shopready-elementor-addon' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__btn a.readmore__btn:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'readmore_hover_border',
				'label'    => __( 'Border', 'shopready-elementor-addon' ),
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__btn a.readmore__btn:hover',
			)
		);

		$this->add_responsive_control(
			'readmore_hover_border_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'shopready-elementor-addon' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => array(
					'{{WRAPPER}} .woo__ready__single__post .post__btn a.readmore__btn:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'readmore_hover_shadow',
				'selector' => '{{WRAPPER}} .woo__ready__single__post .post__btn a.readmore__btn:hover',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		/*
		-----------------------
			READMORE STYLE END
		-------------------------*/
	}

	protected function html( $instance = array() ) {
		$settings = $this->get_settings_for_display();

		$custom_order_ck = $this->get_settings_for_display( 'custom_order' );
		$orderby         = $this->get_settings_for_display( 'orderby' );
		$postorder       = $this->get_settings_for_display( 'postorder' );

		$this->add_render_attribute( 'woo_ready_posts_wrap__area_attr', 'class', 'element__ready__post__content__layout-' . $settings['content_layout_style'] );
		$this->add_render_attribute( 'woo_ready_post_item_attr', 'class', 'woo__ready__single__post woo__ready__post__layout__' . $settings['content_layout_style'] );

		$this->add_render_attribute( 'woo_ready_posts_container_attr', 'class', 'display:grid grid-template-columns-2' );
		if ( 'yes' == $settings['post_masonry'] ) {
			$this->add_render_attribute( 'woo_ready_posts_container_attr', 'id', 'posts__masonry' );
		}

		// Query
		$args = array(
			'post_type'           => ! empty( $settings['element_ready_post_type'] ) ? $settings['element_ready_post_type'] : 'post',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => ! empty( $settings['post_limit'] ) ? $settings['post_limit'] : 3,
			'order'               => $postorder,
		);

		// Custom Order
		if ( $custom_order_ck == 'yes' ) {
			$args['orderby'] = $orderby;
		}

		if ( ! empty( $settings['woo_ready_prod_categories'] ) ) {
			$get_categories = $settings['woo_ready_prod_categories'];
		} else {
			$get_categories = $settings['posts_categories'];
		}

		$get_posts_cats = str_replace( ' ', '', $get_categories );

		if ( ! empty( $get_categories ) ) {
			if ( is_array( $get_posts_cats ) && count( $get_posts_cats ) > 0 ) {
				$field_name        = is_numeric( $get_posts_cats[0] ) ? 'term_id' : 'slug';
				$args['tax_query'] = array(
					array(
						'taxonomy'         => ( $settings['element_ready_post_type'] == 'product' ) ? 'product_cat' : 'category',
						'terms'            => $get_posts_cats,
						'field'            => $field_name,
						'include_children' => false,
					),
				);
			}
		}

		$query_post  = get_posts( $args );
		$counter     = 1;
		$single_data = array_slice( $query_post, 0, 1 )[0];
		unset( $query_post[0] );

		if ( is_array( $query_post ) ) {
			$chunk_data = array_chunk( $query_post, 2 );
		} else {
			$chunk_data = array();
		}

		?>
<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'woo_ready_posts_wrap__area_attr' ) ); ?>>
    <div <?php echo wp_kses_post( $this->get_render_attribute_string( 'woo_ready_posts_container_attr' ) ); ?>>

        <?php if ( $single_data ) : ?>
        <div class="single__masonry__item">
            <div <?php echo wp_kses_post( $this->get_render_attribute_string( 'woo_ready_post_item_attr' ) ); ?>>
                <?php $this->element_ready_post_thumbnail( $single_data->ID ); ?>
                <div class="post__content">
                    <div class="post__inner">
                        <?php $this->woo_ready_post_meta( $single_data->ID ); ?>
                        <?php $this->woo_ready_post_category( $single_data->ID ); ?>
                        <?php $this->woo_ready_post_title( $single_data->ID ); ?>
                        <?php $this->woo_ready_post_content( $single_data->ID ); ?>
                        <?php $this->woo_ready_post_readmore( $single_data->ID ); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ( $chunk_data ) : ?>

        <div class="woo-ready-column display:grid grid-template-columns-2">

            <?php foreach ( $chunk_data as $key => $single ) : ?>


            <?php foreach ( $single as $sl => $item ) : ?>

            <div class="woo-ready-columnzz">
                <div <?php echo wp_kses_post( $this->get_render_attribute_string( 'woo_ready_post_item_attr' ) ); ?>>
                    <?php if ( 'yes' == $settings['show_thumb'] && has_post_thumbnail( $item->ID ) ) : ?>
                    <div class="post__thumb">
                        <a
                            href="<?php echo esc_url( get_the_permalink( $item->ID ) ); ?>"><?php echo wp_kses_post( get_the_post_thumbnail( $item->ID, 'element_ready_grid_small_thumb' ) ); ?></a>
                    </div>
                    <?php endif; ?>
                    <div class="post__content">
                        <div class="post__inner">
                            <?php $this->woo_ready_post_meta( $item->ID ); ?>
                            <?php $this->woo_ready_post_category( $item->ID ); ?>
                            <?php $this->woo_ready_post_title( $item->ID ); ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php endforeach; ?>


            <?php endforeach; ?>

        </div>

        <?php endif; ?>

    </div>
</div>
<?php
	}

	// Loop Content
	public function woo_ready_render_loop_content( $contetntstyle = null ) {
		$settings = $this->get_settings_for_display();
		?>

<?php if ( $contetntstyle == 1 ) : ?>

<?php endif; ?>

<?php
	}

	// Time Ago Content
	public function woo_ready_time_ago() {
		return human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ' . __( 'ago', 'shopready-elementor-addon' );
	}

	public function element_ready_post_thumbnail( $get_post_id ) {
		$settings   = $this->get_settings_for_display();
		$thumb_link = Group_Control_Image_Size::get_attachment_image_src( get_post_thumbnail_id( $get_post_id ), 'thumb_size', $settings );
		?>
<?php if ( 'yes' == $settings['show_thumb'] && has_post_thumbnail( $get_post_id ) ) : ?>
<div class="post__thumb">
    <a href="<?php echo esc_url( get_the_permalink( $get_post_id ) ); ?>"><img
            src="<?php echo esc_url( $thumb_link ); ?>"
            alt="<?php echo esc_attr( get_the_title( $get_post_id ) ); ?>"></a>
</div>
<?php
		endif;
	}

	public function woo_ready_post_category( $get_post_id ) {
		$settings = $this->get_settings_for_display();
		?>
<?php if ( $settings['show_category'] == 'yes' ) : ?>
<ul class="post__category">
    <?php
			foreach ( get_the_category( $get_post_id ) as $category ) {
				$term_link = get_term_link( $category );
				?>
    <li><a href="<?php echo esc_url( $term_link ); ?>"
            class="category <?php echo esc_attr( $category->slug ); ?>"><?php echo esc_attr( $category->name ); ?></a>
    </li>
    <?php
			}
			?>
</ul>
<?php
		endif;
	}

	public function woo_ready_post_title( $get_post_id ) {
		$settings = $this->get_settings_for_display();
		?>
<?php if ( $settings['show_title'] == 'yes' ) : ?>
<h3 class="post__title"><a
        href="<?php echo esc_url( get_the_permalink( $get_post_id ) ); ?>"><?php echo esc_html( wp_trim_words( get_the_title( $get_post_id ), $settings['title_length'], '' ) ); ?></a>
</h3>
<?php
		endif;
	}

	public function woo_ready_post_content( $get_post_id ) {
		$settings = $this->get_settings_for_display();
		if ( $settings['show_content'] == 'yes' ) {
			echo '<p>' . wp_kses_post( wp_trim_words( get_the_content( $get_post_id ), $settings['content_length'], '' ) ) . '</p>';
		}
	}

	public function woo_ready_post_meta() {
		 $settings = $this->get_settings_for_display();
		?>
<?php if ( $settings['show_author'] == 'yes' || $settings['show_date'] == 'yes' ) : ?>
<ul class="post__meta">

    <?php if ( $settings['show_author'] == 'yes' ) : ?>
    <li><i class="fa fa-user-circle"></i><a
            href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) ); ?>"><?php the_author(); ?></a>
    </li>
    <?php endif; ?>

    <?php if ( $settings['show_date'] == 'yes' ) : ?>

    <?php if ( 'date' == $settings['date_type'] ) : ?>
    <li><i class="fa fa-clock-o"></i><?php the_time( esc_html__( 'd F Y', 'shopready-elementor-addon' ) ); ?></li>
    <?php endif; ?>

    <?php if ( 'time' == $settings['date_type'] ) : ?>
    <li><i class="fa fa-clock-o"></i><?php the_time(); ?></li>
    <?php endif; ?>

    <?php if ( 'time_ago' == $settings['date_type'] ) : ?>
    <li><i class="fa fa-clock-o"></i><?php echo wp_kses_post( $this->woo_ready_time_ago() ); ?></li>
    <?php endif; ?>

    <?php if ( 'date_time' == $settings['date_type'] ) : ?>
    <li><i class="fa fa-clock-o"></i><?php echo esc_html(get_the_time( 'd F y - D g:i:a' )); ?></li>
    <?php endif; ?>

    <?php endif; ?>

</ul>
<?php
		endif;
	}

	public function woo_ready_post_readmore( $get_post_id ) {
		$settings = $this->get_settings_for_display();
		?>
<?php if ( $settings['show_read_more_btn'] == 'yes' ) : ?>
<div class="post__btn">
    <?php if ( ! empty( $settings['readmore_icon'] ) ) : ?>
    <?php if ( 'right' == $settings['readmore_icon_position'] ) : ?>
    <a class="readmore__btn"
        href="<?php echo esc_url( get_the_permalink( $get_post_id ) ); ?>"><?php echo sprintf( esc_html__( '%s', 'shopready-elementor-addon' ), esc_html($settings['read_more_txt'] )); ?>
        <i class="readmore_icon_right <?php echo esc_attr( $settings['readmore_icon'] ); ?>"></i></a>
    <?php elseif ( 'left' == $settings['readmore_icon_position'] ) : ?>
    <a class="readmore__btn" href="<?php echo esc_url( get_the_permalink( $get_post_id ) ); ?>"><i
            class="readmore_icon_left <?php echo esc_attr( $settings['readmore_icon'] ); ?>"></i><?php echo sprintf( esc_html__( '%s', 'shopready-elementor-addon' ), esc_html($settings['read_more_txt'] )); ?></a>
    <?php endif; ?>
    <?php else : ?>
    <a class="readmore__btn"
        href="<?php echo esc_url( get_the_permalink( $get_post_id ) ); ?>"><?php echo sprintf( esc_html__( '%s', 'shopready-elementor-addon' ), esc_html($settings['read_more_txt'] )); ?>
    </a>
    <?php endif; ?>
</div>
<?php
		endif;
	}
}