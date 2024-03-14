<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * Better Elements Post Featured Image
 *
 * Single post/page featured image element for elementor.
 *
 * @since 1.0.0
 */
class Better_Post_Featured_Image extends Widget_Base {

	public function get_name() {
		return 'post-featured-image';
	}

	public function get_title() {
		return __( 'Better Post F Image', 'better-el-addons' );
	}

	public function get_icon() {
		return 'fa fa-picture-o';
	}

	public function get_categories() {
		return [ 'better-widgets-post-elements' ];
	}

	protected function _register_controls() {

		$post_type_object = get_post_type_object( get_post_type() );

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Settings', 'better-el-addons' ),
			]
		);

		$this->add_control(
			'preview',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => get_the_post_thumbnail(),
				'separator' => 'none',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'size',
				'label' => __( 'Image Size', 'better-el-addons' ),
				'default' => 'large',
				'exclude' => [ 'custom' ],
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
			'link_to',
			[
				'label' => __( 'Link to', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => __( 'None', 'better-el-addons' ),
					'home' => __( 'Home URL', 'better-el-addons' ),
					'post' => 'Post',
					'file' => __( 'Media File URL', 'better-el-addons' ),
					'custom' => __( 'Custom URL', 'better-el-addons' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link to', 'better-el-addons' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'better-el-addons' ),
				'condition' => [
					'link_to' => 'custom',
				],
				'show_label' => false,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Settings', 'better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} .better-widgets-featured-image img' => 'max-width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .better-widgets-featured-image img' => 'opacity: {{SIZE}};',
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
					'{{WRAPPER}} .better-widgets-featured-image img' => '-webkit-transform: rotate({{SIZE}}deg); -moz-transform: rotate({{SIZE}}deg); -ms-transform: rotate({{SIZE}}deg); -o-transform: rotate({{SIZE}}deg); transform: rotate({{SIZE}}deg);',
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
				'selector' => '{{WRAPPER}} .better-widgets-featured-image img',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-widgets-featured-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .better-widgets-featured-image img',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
	    $settings = $this->get_settings_for_display();

	    // Get the image size from widget settings or default to 'full' size
	    $image_size = !empty($settings['size_size']) ? $settings['size_size'] : 'full';
	    $featured_image = get_the_post_thumbnail(null, $image_size);

	    if (empty($featured_image)) {
	        return;
	    }

	    // Check if link is set and valid
	    $link = !empty($settings['link']['url']) ? $settings['link']['url'] : false;
	    $target = !empty($settings['link']['is_external']) ? 'target="_blank"' : '';
	    $rel = !empty($settings['link']['nofollow']) ? 'rel="nofollow"' : '';

	    // Animation class
	    $animation_class = !empty($settings['hover_animation']) ? 'elementor-animation-' . esc_attr($settings['hover_animation']) : '';

	    // Construct HTML output
	    $html = '<div class="better-widgets-featured-image ' . esc_attr($animation_class) . '">';
	    if ($link) {
	        $html .= sprintf('<a href="%1$s" %2$s %3$s>%4$s</a>', esc_url($link), $target, $rel, $featured_image);
	    } else {
	        $html .= $featured_image;
	    }
	    $html .= '</div>';

	    echo $html;
	}


	protected function content_template() {

	}

}
