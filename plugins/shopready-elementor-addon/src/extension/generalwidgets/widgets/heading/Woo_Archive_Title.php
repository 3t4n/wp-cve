<?php

namespace Shop_Ready\extension\generalwidgets\widgets\heading;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class Woo_Archive_Title extends \Shop_Ready\extension\generalwidgets\Widget_Base
{

	public function get_keywords()
	{
		return ['title', 'Archive area title', 'section title', 'page title'];
	}



	protected function register_controls()
	{

		/******************************
		 * 	CONTENT SECTION
		 ******************************/
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__('Content', 'shopready-elementor-addon'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__('Title HTML Tag', 'shopready-elementor-addon'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
				'condition' => [
					'title!' => '',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'archive_product_title_alignment',
			[
				'label' => esc_html__('Alignment', 'shopready-elementor-addon'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [

					'left' => [

						'title' => esc_html__('Left', 'shopready-elementor-addon'),
						'icon' => 'eicon-text-align-left',

					],
					'center' => [

						'title' => esc_html__('Center', 'shopready-elementor-addon'),
						'icon' => 'eicon-text-align-center',

					],
					'right' => [

						'title' => esc_html__('Right', 'shopready-elementor-addon'),
						'icon' => 'eicon-text-align-right',

					]
				],

				'selectors' => [
					'{{WRAPPER}} .area__title' => 'text-align: {{VALUE}};',
				],

			]
		);

		$this->end_controls_section();


		/*********************************
		 * 		STYLE Title
		 *********************************/

		$this->text_minimum_css(
			array(
				'title' => esc_html__('Title Style', 'shopready-elementor-addon'),
				'slug' => 'shop_ready_archive_title',
				'element_name' => 'shop_ready_archive_title_',
				'selector' => '.elementor-widget-heading_woo_archive_title .area__title',
				'hover_selector' => false,
				'disable_controls' => ['bg', 'border', 'dimensions', 'display']
			)
		);
	}

	protected function html()
	{

		$settings = $this->get_settings_for_display();

		/*Title Tag*/
		if (!empty($settings['title_tag'])) {
			$title_tag = $settings['title_tag'];
		} else {
			$title_tag = 'h3';
		}

		/*Title*/
		if (get_the_archive_title()) {
			$title = '<' . $title_tag . ' class="area__title">' . get_the_archive_title() . '</' . $title_tag . '>';
		} else {
			$title = '';
		}



		echo wp_kses_post('<div class="area__content">');
		echo wp_kses_post($title);
		echo wp_kses_post('</div>');
	}
	protected function content_template()
	{
	}
}
