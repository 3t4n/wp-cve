<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * Better Elements Post Author
 *
 * Single post/page author element for elementor. 
 *
 * @since 1.0.0
 */
class Better_Post_Author extends Widget_Base {

	public function get_name() {
		return 'better-post-author';
	}

	public function get_title() {
		return __( 'Better Post Author', 'better-el-addons' );
	}

	public function get_icon() {
		return 'fa fa-user-circle-o';
	}

	public function get_categories() {
		return [ 'better-category' ]; 
	}

	protected function _register_controls() {

		$post_type_object = get_post_type_object( get_post_type() );

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'author settings', 'better-el-addons' ),
			]
		);

		$this->add_control(
			'author',
			[
				'label' => __( 'Author', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->user_fields_labels(),
				'default' => 'display_name',
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label' => __( 'HTML Tag', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'p' => 'p',
					'div' => 'div',
					'span' => 'span',
				],
				'default' => 'p',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'better-el-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'better-el-addons' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'better-el-addons' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'better-el-addons' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'better-el-addons' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'better-el-addons' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'better-el-addons' ),
				'condition' => [
					'link_to' => 'custom',
				],
				'default' => [
					'url' => '',
				],
				'show_label' => false,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Autho style', 'better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'color',
			[
				'label' => __( 'Text Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Color::get_type(),
					'value' => Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .better-widgets-author' => 'color: {{VALUE}};',
					'{{WRAPPER}} .better-widgets-author a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'author!' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-widgets-author',
				'condition' => [
					'author!' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .better-widgets-author',
				'condition' => [
					'author!' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label' => __( 'Size (%)', 'better-el-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-widgets-author img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'author' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'opacity',
			[
				'label' => __( 'Opacity (%)', 'better-el-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-widgets-author img' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'author' => 'image',
				],
			]
		);

		$this->add_control(
			'angle',
			[
				'label' => __( 'Angle (deg)', 'better-el-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'default' => [
					'unit' => 'deg',
					'size' => 0,
				],
				'range' => [
					'deg' => [
						'max' => 360,
						'min' => -360,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-widgets-author img' => '-webkit-transform: rotate({{SIZE}}deg); -moz-transform: rotate({{SIZE}}deg); -ms-transform: rotate({{SIZE}}deg); -o-transform: rotate({{SIZE}}deg); transform: rotate({{SIZE}}deg);',
				],
				'condition' => [
					'author' => 'image',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'better-el-addons' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => __( 'Image Border', 'better-el-addons' ),
				'selector' => '{{WRAPPER}} .better-widgets-author img',
				'condition' => [
					'author' => 'image',
				],
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-widgets-author img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'author' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .better-widgets-author img',
				'condition' => [
					'author' => 'image',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['author'] ) )
			return;

		$author = $this->user_data( $settings['author'] );
		$link = false;

		$target = $settings['link']['is_external'] ? 'target="_blank"' : '';

		$animation_class = ! empty( $settings['hover_animation'] ) ? 'elementor-animation-' . $settings['hover_animation'] : '';

		$html = sprintf( '<%1$s class="better-widgets-author %2$s">', $settings['html_tag'], $animation_class );
		if ( $link ) {
			$html .= sprintf( '<a href="%1$s" %2$s>%3$s</a>', $link, $target, $author );
		} else {
			$html .= $author;
		}
		$html .= sprintf( '</%s>', $settings['html_tag'] );

		echo $html;
	}

	protected function content_template() {
	}

	protected function user_fields_labels() {

		$fields = [
			'first_name'   => __( 'First Name', 'better-el-addons' ),
			'last_name'    => __( 'Last Name', 'better-el-addons' ),
			'first_last'   => __( 'First Name + Last Name', 'better-el-addons' ),
			'last_first'   => __( 'Last Name + First Name', 'better-el-addons' ),
			'nickname'     => __( 'Nick Name', 'better-el-addons' ),
			'display_name' => __( 'Display Name', 'better-el-addons' ),
			'user_login'   => __( 'User Name', 'better-el-addons' ),
			'description'  => __( 'User Bio', 'better-el-addons' ),
			'image'        => __( 'User Image', 'better-el-addons' ),
		];

		return $fields;

	}

	protected function user_data( $selected = '' ) {

	    global $post;

	    $author_id = $post->post_author;

	    $fields = [
	        'first_name'   => sanitize_text_field( get_the_author_meta( 'first_name', $author_id ) ),
	        'last_name'    => sanitize_text_field( get_the_author_meta( 'last_name', $author_id ) ),
	        'first_last'   => sprintf( '%s %s', sanitize_text_field( get_the_author_meta( 'first_name', $author_id ) ), sanitize_text_field( get_the_author_meta( 'last_name', $author_id ) ) ),
	        'last_first'   => sprintf( '%s %s', sanitize_text_field( get_the_author_meta( 'last_name', $author_id ) ), sanitize_text_field( get_the_author_meta( 'first_name', $author_id ) ) ),
	        'nickname'     => sanitize_text_field( get_the_author_meta( 'nickname', $author_id ) ),
	        'display_name' => sanitize_text_field( get_the_author_meta( 'display_name', $author_id ) ),
	        'user_login'   => sanitize_text_field( get_the_author_meta( 'user_login', $author_id ) ),
	        'description'  => wp_kses_post( get_the_author_meta( 'description', $author_id ) ), // Allowing HTML tags for description
	        'image'        => get_avatar( get_the_author_meta( 'email', $author_id ), 256 ),
	    ];

	    if ( empty( $selected ) ) {
	        // Return the entire array
	        return $fields;
	    } else {
	        // Return only the selected field
	        return $fields[ $selected ];
	    }
	}


}
