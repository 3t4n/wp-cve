<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * Better Elements Post Date
 *
 * Single post/page date element for elementor.
 *
 * @since 1.0.0
 */
class Better_Post_Date extends Widget_Base {

	public function get_name() {
		return 'post-date';
	}

	public function get_title() {
		return __( 'Better Post Date', 'better-el-addons' );
	}

	public function get_icon() {
		return 'fa fa-clock-o';
	}

	public function get_categories() {
		return [ 'better-widgets-post-elements' ];
	}

	protected function _register_controls() {

		$post_type_object = get_post_type_object( get_post_type() );

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Data setings', 'better-el-addons' ),
			]
		);

		$this->add_control(
			'date_type',
			[
				'label' => __( 'Date Type', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'publish' => __( 'Publish Date', 'better-el-addons' ),
					'modified' => __( 'Last Modified Date', 'better-el-addons' ),
				],
				'default' => 'publish',
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

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Setings', 'better-el-addons' ),
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
					'{{WRAPPER}} .better-widgets-date' => 'color: {{VALUE}};',
					'{{WRAPPER}} .better-widgets-date a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-widgets-date',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .better-widgets-date',
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'better-el-addons' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
	    $settings = $this->get_settings_for_display();

	    // Backwards compatibility check
	    $date_type = isset($settings['date_type']) ? $settings['date_type'] : 'publish';

	    global $post;
	    switch ($date_type) {
	        case 'modified':
	            $date = get_the_modified_date('', $post->ID);
	            break;
	        case 'publish':
	        default:
	            $date = get_the_date('', $post->ID);
	            break;
	    }

	    if (empty($date)) {
	        return;
	    }

	    $animation_class = !empty($settings['hover_animation']) ? 'elementor-animation-' . esc_attr($settings['hover_animation']) : '';

	    $html_tag = tag_escape($settings['html_tag']);

	    // Ensure the HTML tag is allowed, fallback to 'span' if not
	    $allowed_tags = ['div', 'span', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6']; // Extend this array based on your needs
	    if (!in_array($html_tag, $allowed_tags)) {
	        $html_tag = 'span';
	    }

	    $html = sprintf('<%1$s class="better-widgets-date %2$s">', $html_tag, $animation_class);
	    $html .= esc_html($date);
	    $html .= sprintf('</%s>', $html_tag);

	    echo $html;
	}


	protected function content_template() {
	}
}
