<?php

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use AbsoluteAddons\Absp_Widget;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Service extends Absp_Widget
{

	protected $current_style;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_name()
	{
		return 'absolute-service';
	}

	/**
	 * Retrieve the widget title
	 *
	 * @return string Widget title.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_title()
	{
		return __('Service', 'absolute-addons');
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_icon()
	{
		return 'absp eicon-apps';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends()
	{
		return array(
			'absolute-addons-btn',
			'absp-service',
			'absp-pro-service',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends()
	{
		return array(
			'absolute-addons-service',
		);
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_categories()
	{
		return ['absp-widgets'];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function register_controls()
	{
		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Service $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array($this->get_prefixed_hook('controllers/starts'), [&$this]);

		$this->start_controls_section(
			'section_template',
			array(
				'label' => __('Template', 'absolute-addons'),
			)
		);

		$layouts = apply_filters($this->get_prefixed_hook('styles'), [
			'one' => __('Style One', 'absolute-addons'),
			'two' => __('Style Two', 'absolute-addons'),
			'three' => __('Style Three', 'absolute-addons'),
			'four' => __('Style Four', 'absolute-addons'),
			'five-pro' => __('Style Five (Pro)', 'absolute-addons'),
			'six-pro' => __('Style Six (Pro)', 'absolute-addons'),
			'seven-pro' => __('Style Seven (Upcoming)', 'absolute-addons'),
			'eight-pro' => __('Style Eight (Upcoming)', 'absolute-addons'),
			'nine' => __('Style Nine', 'absolute-addons'),
			'ten' => __('Style Ten', 'absolute-addons'),
			'eleven' => __('Style Eleven (Upcoming)', 'absolute-addons'),
			'twelve' => __('Style Twelve', 'absolute-addons'),
			'thirteen' => __('Style Thirteen', 'absolute-addons'),
			'fourteen' => __('Style Fourteen', 'absolute-addons'),
			'fifteen' => __('Style Fifteen (Upcoming)', 'absolute-addons'),
			'sixteen' => __('Style Sixteen (Upcoming)', 'absolute-addons'),
			'seventeen' => __('Style Seventeen (Upcoming)', 'absolute-addons'),
			'eighteen' => __('Style Eighteen (Upcoming)', 'absolute-addons'),
			'nineteen-pro' => __('Style Nineteen (Pro)', 'absolute-addons'),
			'twenty' => __('Style Twenty (Upcoming)', 'absolute-addons'),
			'twenty-one' => __('Style Twenty One (Upcoming)', 'absolute-addons'),
			'twenty-two' => __('Style Twenty Two (Upcoming)', 'absolute-addons'),
			'twenty-three' => __('Style Twenty Three (Upcoming)', 'absolute-addons'),
			'twenty-four' => __('Style Twenty Four (Upcoming)', 'absolute-addons'),
			'twenty-five-pro' => __('Style Twenty Five (Pro)', 'absolute-addons'),
			'twenty-six' => __('Style Twenty Six (Upcoming)', 'absolute-addons'),
			'twenty-seven' => __('Style Twenty Seven (Upcoming)', 'absolute-addons'),
			'twenty-eight' => __('Style Twenty Eight (Upcoming)', 'absolute-addons'),
			'twenty-nine-pro' => __('Style Twenty Nine (Pro)', 'absolute-addons'),
			'thirty' => __('Style Thirty (Upcoming)', 'absolute-addons'),
			'thirty-one' => __('Style Thirty One (Upcoming)', 'absolute-addons'),
			'thirty-two' => __('Style Thirty Two (Upcoming)', 'absolute-addons'),
			'thirty-three' => __('Style Thirty Three (Upcoming)', 'absolute-addons'),
			'thirty-four' => __('Style Thirty Four (Upcoming)', 'absolute-addons'),
			'thirty-five' => __('Style Thirty Five (Upcoming)', 'absolute-addons'),
			'thirty-six' => __('Style Thirty Six (Upcoming)', 'absolute-addons'),
			'thirty-seven' => __('Style Thirty Seven (Upcoming)', 'absolute-addons'),
			'thirty-eight' => __('Style Thirty Eight (Upcoming)', 'absolute-addons'),
		]);

		$pro_styles = [
			'five-pro',
			'six-pro',
			'seven-pro',
			'eight-pro',
			'nineteen-pro',
			'twenty-five-pro',
			'twenty-nine-pro',
		];

		$this->add_control(
			'absolute_service',
			array(
				'label' => __('Service Style', 'absolute-addons'),
				'label_block' => true,
				'type' => Absp_Control_Styles::TYPE,
				'options' => $layouts,
				'disabled' => [
					'seven-pro',
					'eight-pro',
					'eleven',
					'fifteen',
					'sixteen',
					'seventeen',
					'eighteen',
					'twenty',
					'twenty-one',
					'twenty-two',
					'twenty-three',
					'twenty-four',
					'twenty-six',
					'twenty-seven',
					'twenty-eight',
					'thirty',
					'thirty-one',
					'thirty-two',
					'thirty-three',
					'thirty-four',
					'thirty-five',
					'thirty-six',
					'thirty-seven',
					'thirty-eight',
				],
				'default' => 'one',
			)
		);

		$this->init_pro_alert($pro_styles);

		$this->end_controls_section();


		// Content Controllers
		$this->render_controller('content-controller-service-style');


		// Style Controllers
		$this->content_section('one');
		$this->content_section('two');
		$this->content_section('three');
		$this->content_section('four');
		$this->content_section('five');
		$this->content_section('six');
		$this->content_section('nine');
		$this->content_section('ten');
		$this->content_section('twelve');
		$this->content_section('thirteen');
		$this->content_section('fourteen');
		$this->content_section('nineteen');
		$this->content_section('twenty-five');
		$this->content_section('twenty-nine');

		$this->read_more_button_section($this);


		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Service $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array($this->get_prefixed_hook('controllers/ends'), [&$this]);

	}

	private function content_section($style = '')
	{
		$this->start_controls_section(
			'service_content_section_' . $style,
			[
				'label' => __('Content Section', 'absolute-addons'),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'absolute_service' => $style,
				],
			]
		);

		if (!in_array($style, ['five', 'thirteen'])) {
			$this->add_responsive_control(
				'service_column_' . $style,
				[
					'label' => __('Service Column', 'absolute-addons'),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'1' => __('1 Column', 'absolute-addons'),
						'2' => __('2 Column', 'absolute-addons'),
						'3' => __('3 Column', 'absolute-addons'),
						'4' => __('4 Column', 'absolute-addons'),
						'5' => __('5 Column', 'absolute-addons'),
						'6' => __('6 Column', 'absolute-addons'),
					],
					'default' => '3',
					'devices' => ['desktop', 'tablet', 'mobile'],
					'desktop_default' => 3,
					'tablet_default' => 3,
					'mobile_default' => 2,
					'prefix_class' => 'absp-service-grid%s-',
					'selectors' => [
						'(desktop+){{WRAPPER}} .absp-service .absp-service-grid-col' => 'grid-template-columns: repeat({{service_column_' . $style . '.VALUE}}, 1fr);',
						'(tablet){{WRAPPER}} .absp-service .absp-service-grid-col' => 'grid-template-columns: repeat({{service_column_' . $style . '_tablet.VALUE}}, 1fr);',
						'(mobile){{WRAPPER}} .absp-service .absp-service-grid-col' => 'grid-template-columns: repeat({{service_column_' . $style . '_mobile.VALUE}}, 1fr);',
					],
					'condition' => [
						'absolute_service!' => 'thirteen',
					],
				]
			);
		}

		if ('thirteen' === $style) {
			$this->add_responsive_control(
				'service_column_' . $style,
				[
					'label' => __('Service Column', 'absolute-addons'),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'1' => __('1 Column', 'absolute-addons'),
						'2' => __('2 Column', 'absolute-addons'),
					],
					'default' => '2',
					'devices' => ['desktop', 'tablet', 'mobile'],
					'desktop_default' => 2,
					'tablet_default' => 2,
					'mobile_default' => 1,
					'prefix_class' => 'absp-service-grid%s-',
					'selectors' => [
						'(desktop+){{WRAPPER}} .absp-service .absp-service-grid-col' => 'grid-template-columns: repeat({{service_column_' . $style . '.VALUE}}, 1fr);',
						'(tablet){{WRAPPER}} .absp-service .absp-service-grid-col' => 'grid-template-columns: repeat({{service_column_' . $style . '_tablet.VALUE}}, 1fr);',
						'(mobile){{WRAPPER}} .absp-service .absp-service-grid-col' => 'grid-template-columns: repeat({{service_column_' . $style . '_mobile.VALUE}}, 1fr);',
					],
					'condition' => [
						'absolute_service' => 'thirteen',
					],
				]
			);
		}

		if ('five' === $style) {
			$this->add_control(
				'absp_service_item_alignment',
				[
					'label' => __('Item Alignment', 'absolute-addons'),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'absp-item-left' => [
							'title' => __('Left', 'absolute-addons'),
							'icon' => 'eicon-h-align-left',
						],
						'absp-item-top' => [
							'title' => __('Top', 'absolute-addons'),
							'icon' => 'eicon-v-align-top',
						],
					],
					'default' => 'absp-item-left',
				]
			);

			$this->add_control(
				'service_number', [
					'label' => __('Service Number', 'absolute-addons'),
					'type' => Controls_Manager::TEXT,
					'default' => __('1', 'absolute-addons'),
					'label_block' => true,
				]
			);

			$this->add_control(
				'service_title_tag',
				[
					'label' => __('Title HTML Tag', 'absolute-addons'),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
						'div' => 'div',
					],
					'default' => 'h3',
					'separator' => 'before',
				]
			);

			$this->add_control(
				'service_title', [
					'label' => __('Service Title', 'absolute-addons'),
					'type' => Controls_Manager::TEXT,
					'default' => __('Service Title #1', 'absolute-addons'),
					'label_block' => true,
				]
			);

			$this->add_control(
				'absp_service_content_option',
				[
					'label' => __('Show Content', 'absolute-addons'),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'yes' => __('Show', 'absolute-addons'),
						'no' => __('Hide', 'absolute-addons'),
					],
					'default' => 'yes',
				]
			);

			$this->add_control(
				'service_content',
				[
					'label' => __('Choose a icon', 'absolute-addons'),
					'type' => Controls_Manager::WYSIWYG,
					'default' => __('Your content Here', 'absolute-addons'),
					'condition' => [
						'absp_service_content_option' => 'yes',
					],
				]
			);
		}

		$repeater = new Repeater();

		if (in_array($style, ['one', 'two', 'three', 'four', 'six'])) {
			$repeater->add_control(
				'service_number', [
					'label' => __('Service Number', 'absolute-addons'),
					'type' => Controls_Manager::TEXT,
					'default' => __('1', 'absolute-addons'),
					'label_block' => true,
				]
			);
		}

		if ('nineteen' === $style) {
			$repeater->add_control(
				'absp_service_alignment',
				[
					'label' => __('Item Alignment', 'absolute-addons'),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'absp-item-left' => [
							'title' => __('Left', 'absolute-addons'),
							'icon' => 'eicon-text-align-left',
						],
						'absp-item-right' => [
							'title' => __('Right', 'absolute-addons'),
							'icon' => 'eicon-text-align-right',
						],
					],
					'default' => 'absp-item-left',
				]
			);
		}

		if ('five' !== $style) {
			$repeater->add_control(
				'service_title_tag',
				[
					'label' => __('Title HTML Tag', 'absolute-addons'),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
						'div' => 'div',
					],
					'default' => 'h3',
					'separator' => 'before',
				]
			);

			$repeater->add_control(
				'service_title', [
					'label' => __('Service Title', 'absolute-addons'),
					'type' => Controls_Manager::TEXT,
					'default' => __('Service Title #1', 'absolute-addons'),
					'label_block' => true,
				]
			);
		}

		if (in_array($style, ['thirteen', 'twenty-five'])) {
			$repeater->add_control(
				'service_sub_title_tag',
				[
					'label' => __('Sub Title HTML Tag', 'absolute-addons'),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
						'span' => 'span',
						'div' => 'div',
					],
					'default' => 'h4',
					'separator' => 'before',
				]
			);

			$repeater->add_control(
				'service_sub_title', [
					'label' => __('Service Sub Title', 'absolute-addons'),
					'type' => Controls_Manager::TEXT,
					'default' => __('Sub Title #1', 'absolute-addons'),
					'label_block' => true,
				]
			);
		}

		if (in_array($style, ['one', 'nine', 'ten', 'twelve', 'thirteen', 'fourteen', 'nineteen'])) {
			$repeater->add_control(
				'service_icon',
				[
					'label' => __('Choose a icon', 'absolute-addons'),
					'type' => Controls_Manager::ICONS,
					'default' => [
						'value' => 'fas fa-palette',
						'library' => 'solid',
					],
				]
			);
		}

		if (in_array($style, ['twenty-five', 'twenty-nine'])) {
			$repeater->add_control(
				'service_image',
				[
					'label' => __('Item Image', 'absolute-addons'),
					'type' => Controls_Manager::MEDIA,
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
					],
				]
			);

			$repeater->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' => 'thumbnail',
					'fields_options' => [
						'size' => [
							'label' => __('Image Size', 'absolute-addons'),
						],
					],
					'exclude' => ['custom'], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
					'default' => 'large',
				]
			);
		}

		if ('five' !== $style) {
			$repeater->add_control(
				'service_content',
				[
					'label' => __('Service Content', 'absolute-addons'),
					'type' => Controls_Manager::WYSIWYG,
					'default' => __('Your content Here', 'absolute-addons'),
				]
			);
		}

		if ('one' === $style) {
			$repeater->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'service_item_background',
					'fields_options' => [
						'background' => [
							'label' => __('Item Background', 'absolute-addons'),
						],
					],
					'types' => ['classic', 'gradient'],
					'selector' => '{{WRAPPER}} .absp-service {{CURRENT_ITEM}}',
				]
			);

			$repeater->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'service_item_hover_background',
					'fields_options' => [
						'background' => [
							'label' => __('Item Background Hover', 'absolute-addons'),
						],
					],
					'types' => ['classic', 'gradient'],
					'selector' => '{{WRAPPER}} .absp-service {{CURRENT_ITEM}}:hover',
				]
			);
		}

		if ('one' === $style) {
			$repeater->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'service_number_bg',
					'fields_options' => [
						'background' => [
							'label' => __('Number Background Color', 'absolute-addons'),
						],
					],
					'types' => ['classic', 'gradient'],
					'selector' => '{{WRAPPER}} .absp-service  {{CURRENT_ITEM}} .absp-service-number',
				]
			);

			$repeater->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'service_number_hover_bg',
					'fields_options' => [
						'background' => [
							'label' => __('Number BG Hover Color', 'absolute-addons'),
						],
					],
					'types' => ['classic', 'gradient'],
					'selector' => '{{WRAPPER}} .absp-service  {{CURRENT_ITEM}}.absp-service-item:hover .absp-service-number',
				]
			);

			$repeater->add_control(
				'service_number_hover_color',
				array(
					'label' => __('Number Hover Color', 'absolute-addons'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-service  {{CURRENT_ITEM}}.absp-service-item:hover .absp-service-number' => 'color: {{VALUE}}',
					],
				)
			);

			$repeater->add_control(
				'service_icon_hover_color',
				array(
					'label' => __('Service Icon Hover Color', 'absolute-addons'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-service  {{CURRENT_ITEM}}.absp-service-item:hover .absp-service-icon' => 'color: {{VALUE}}',
						'{{WRAPPER}} .absp-service {{CURRENT_ITEM}}:hover .absp-service-right' => 'border-color: {{VALUE}}',
					],
				)
			);

			$repeater->add_control(
				'service_title_hover_color',
				array(
					'label' => __('Service Title Hover Color', 'absolute-addons'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-service  {{CURRENT_ITEM}}.absp-service-item:hover .absp-service-title' => 'color: {{VALUE}}',
					],
				)
			);
		}

		if ('four' === $style) {
			$repeater->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'service_number_bg',
					'fields_options' => [
						'background' => [
							'label' => __('Number Background Color', 'absolute-addons'),
						],
					],
					'types' => ['classic', 'gradient'],
					'selector' => '{{WRAPPER}} .absp-service  {{CURRENT_ITEM}} .absp-service-number',
				]
			);

			$repeater->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'service_number_hover_bg',
					'fields_options' => [
						'background' => [
							'label' => __('Number BG Hover Color', 'absolute-addons'),
						],
					],
					'types' => ['classic', 'gradient'],
					'selector' => '{{WRAPPER}} .absp-service  {{CURRENT_ITEM}}.absp-service-item:hover .absp-service-number',
				]
			);

			$repeater->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'service_number_border_hover',
					'label' => __('Border', 'absolute-addons'),
					'selector' => '{{WRAPPER}} .absp-service  {{CURRENT_ITEM}}.absp-service-item:hover .absp-service-number',
				]
			);

			$repeater->add_control(
				'service_title_hover_color',
				array(
					'label' => __('Service Title Hover Color', 'absolute-addons'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-service  {{CURRENT_ITEM}}.absp-service-item:hover .absp-service-title' => 'color: {{VALUE}}',
					],
				)
			);
		}

		if ('nineteen' === $style) {
			$repeater->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'service_icon_bg',
					'fields_options' => [
						'background' => [
							'label' => __('Icon Background Color', 'absolute-addons'),
						],
					],
					'types' => ['classic', 'gradient'],
					'selector' => '{{WRAPPER}} .absp-service  {{CURRENT_ITEM}} .absp-service-icon',
				]
			);
		}

		if (in_array($style, ['nine', 'ten', 'nineteen'])) {
			$repeater->add_control(
				'service_icon_color',
				array(
					'label' => __('Service Icon Color', 'absolute-addons'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-service  {{CURRENT_ITEM}} .absp-service-icon' => 'color: {{VALUE}}',
					],
				)
			);
		}

		if ('twenty-five' === $style) {
			$repeater->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'service_subtitle_background',
					'fields_options' => [
						'background' => [
							'label' => __('Subtitle Background Color', 'absolute-addons'),
						],
					],
					'types' => ['classic', 'gradient'],
					'selector' => '{{WRAPPER}} .absp-service {{CURRENT_ITEM}} .absp-service-subtitle',
				]
			);

			$repeater->add_control(
				'service_subtitle_color',
				array(
					'label' => __('Subtitle Color', 'absolute-addons'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-service {{CURRENT_ITEM}} .absp-service-subtitle' => 'color: {{VALUE}}',
					],
				)
			);

			$repeater->add_control(
				'service_subtitle_border_color',
				array(
					'label' => __('Subtitle Border Color', 'absolute-addons'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-service {{CURRENT_ITEM}}.absp-service-item' => 'border-color: {{VALUE}}',
					],
				)
			);
		}

		if (in_array($style, ['one', 'two', 'three', 'four', 'five', 'six', 'seven'])) {
			$repeater->add_control(
				'service_number_color',
				array(
					'label' => __('Service Number Color', 'absolute-addons'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-service {{CURRENT_ITEM}} .absp-service-number' => 'color: {{VALUE}}',
					],
				)
			);
		}

		if ('six' === $style) {
			$repeater->add_control(
				'service_number_hover_color',
				array(
					'label' => __('Service Number Hover Color', 'absolute-addons'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-service {{CURRENT_ITEM}}:hover .absp-service-number' => 'color: {{VALUE}}',
					],
				)
			);
		}

		if ('six' === $style) {
			$repeater->add_control(
				'service_title_hover_color',
				array(
					'label' => __('Service Title Hover Color', 'absolute-addons'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-service {{CURRENT_ITEM}}:hover .absp-service-title' => 'color: {{VALUE}}',
					],
				)
			);
		}

		if ('five' !== $style) {
			$repeater->add_control(
				'service_title_color',
				array(
					'label' => __('Service Title Color', 'absolute-addons'),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-service {{CURRENT_ITEM}} .absp-service-title' => 'color: {{VALUE}}',
						'{{WRAPPER}} .absp-service .absp-service-twenty-nine {{CURRENT_ITEM}} hr' => 'background-color: {{VALUE}}',
					],
				)
			);
		}

		if (in_array($style, ['six', 'nine', 'ten'])) {
			$this->read_more_button_controller($repeater);
		}

		if ('five' !== $style) {
			$this->add_control(
				'service_' . $style,
				[
					'label' => __('Service Item', 'absolute-addons'),
					'type' => Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
					'default' => [
						[
							'service_number' => __('1', 'absolute-addons'),
							'service_title' => __('Creative </br> Design', 'absolute-addons'),
							'service_icon' => [
								'value' => 'fas fa-palette',
							],
							'service_content' => __('At Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore. Lorem ipsum dolor sit amet, ', 'absolute-addons'),
						],
						[
							'service_number' => __('2', 'absolute-addons'),
							'service_title' => __('Illustration</br> & Artwork', 'absolute-addons'),
							'service_icon' => [
								'value' => 'fas fa-cloud',
							],
							'service_content' => __('At Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore. Lorem ipsum dolor sit amet, ', 'absolute-addons'),
						],
						[
							'service_number' => __('3', 'absolute-addons'),
							'service_title' => __('UI/UX</br> Design', 'absolute-addons'),
							'service_icon' => [
								'value' => 'fas fa-rocket',
							],
							'service_content' => __('At Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore. Lorem ipsum dolor sit amet, ', 'absolute-addons'),
						],
					],
					'title_field' => '{{{ service_title }}}',
				]
			);
		}

		$this->end_controls_section();
	}

	protected function read_more_button_controller($settings)
	{
		$settings->add_control(
			'absp_service_btn_switch',
			[
				'label' => __('Show Button', 'absolute-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __('Yes', 'absolute-addons'),
				'label_off' => __('No', 'absolute-addons'),
			]
		);

		$settings->add_responsive_control(
			'absp_service_btn_align',
			[
				'label' => __('Alignment', 'absolute-addons'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'absolute-addons'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'absolute-addons'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'absolute-addons'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .absp-service-btn' => 'text-align: {{VALUE}};',
				],
				'default' => 'left',
				'condition' => [
					'absp_service_btn_switch' => 'yes',
				],
			]
		);

		$settings->add_control(
			'absp_service_btn_text',
			[
				'label' => __('Button Text', 'absolute-addons'),
				'type' => Controls_Manager::TEXT,
				'default' => __('Read more ', 'absolute-addons'),
				'placeholder' => __('Read more ', 'absolute-addons'),
				'condition' => [
					'absp_service_btn_switch' => 'yes',
				],
			]
		);

		$settings->add_control(
			'absp_service_btn_link',
			[
				'label' => __('Link', 'absolute-addons'),
				'type' => Controls_Manager::URL,
				'placeholder' => __('https://your-link.com', 'absolute-addons'),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'condition' => [
					'absp_service_btn_switch' => 'yes',
				],
			]
		);

		$settings->add_control(
			'absp_service_btn_icons_switch',
			[
				'label' => __('Add icon? ', 'absolute-addons'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __('Yes', 'absolute-addons'),
				'label_off' => __('No', 'absolute-addons'),
				'condition' => [
					'absp_service_btn_switch' => 'yes',
				],
			]
		);

		$settings->add_control(
			'absp_service_btn_icons',
			[
				'label' => __('Icon', 'absolute-addons'),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => '',
				],
				'label_block' => true,
				'condition' => [
					'absp_service_btn_switch' => 'yes',
					'absp_service_btn_icons_switch' => 'yes',
				],
			]
		);

		$settings->add_control(
			'absp_service_btn_icon_align',
			[
				'label' => __('Icon Position', 'absolute-addons'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'left' => __('Before', 'absolute-addons'),
					'right' => __('After', 'absolute-addons'),
				],
				'default' => 'right',
				'condition' => [
					'absp_service_btn_switch' => 'yes',
					'absp_service_btn_icons_switch' => 'yes',
				],
			]
		);
	}

	protected function read_more_button_section($settings)
	{
		$this->start_controls_section(
			'absp_service_read_more_section',
			[
				'label' => __('Read More Button', 'absolute-addons'),
				'condition' => [
					'absolute_service' => 'five',
				],
			]
		);

		$this->read_more_button_controller($settings);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$style = $settings['absolute_service'];
		$this->current_style = $style;

		$this->add_inline_editing_attributes('service_number', 'basic');
		$this->add_render_attribute('service_number', 'class', 'service-number');

		$this->add_inline_editing_attributes('service_title', 'basic');
		$this->add_render_attribute('service_title', 'class', 'absp-service-title', true);

		$this->add_inline_editing_attributes('service_sub_title', 'basic');
		$this->add_render_attribute('service_sub_title', 'class', 'absp-service-subtitle', true);

		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-service -->
					<div class="absp-service element-<?php echo esc_attr($style) ?>">
						<div class="absp-service-<?php echo esc_attr($style) ?>">
							<div class="absp-service-grid-col">
								<?php if ('five' === $style) { ?>
									<div
										class="absp-service-item <?php echo esc_attr($settings['absp_service_item_alignment']); ?>"
										style="align-items: <?php echo esc_attr($settings['service_alignment']); ?>">
										<?php $this->render_template($style); ?>
									</div>
								<?php } else {
									foreach ($settings['service_' . $style] as $service) {
										$alignment = ('nineteen' === $style) ? $service['absp_service_alignment'] : "";
										?>
										<div
											class="absp-service-item <?php echo esc_attr($alignment); ?> elementor-repeater-item-<?php echo esc_attr($service['_id']); ?>"
											<?php if (isset($settings['service_alignment'])) {
											echo 'style="align-items: '. esc_attr($settings['service_alignment']).' " ';
											 } ?> >
											<?php if ('one' === $style) { ?>
												<?php $this->service_number($service); ?>

												<div class="absp-service-left">
													<?php
													$this->service_icon($service);

													if (!empty($service['service_title'])) {
														$this->service_title($service);
													} ?>
												</div>
												<div class="absp-service-right">
													<?php if (!empty($service['service_content'])) {
														$this->service_content($service);
													} ?>
												</div>
											<?php } else {
												$this->render_template($style, ['service' => $service]);
											} ?>
										</div>
									<?php }
								} ?>
							</div>
						</div>
					</div>
					<!-- absp-service -->
				</div>
			</div>
		</div>
		<?php
	}

	protected function service_icon($settings)
	{
		?>
		<div class="absp-service-icon">
			<?php Icons_Manager::render_icon($settings['service_icon'], ['aria-hidden' => 'true']); ?>
		</div>
		<?php
	}

	protected function service_image($settings)
	{
		$image_url = Group_Control_Image_Size::get_attachment_image_src($settings['service_image']['id'], 'thumbnail', $settings);
		?>
		<div class="absp-service-image">
			<img src="<?php echo esc_url($image_url); ?>"
				 alt="<?php echo esc_attr(Control_Media::get_image_alt($settings)); ?>">
		</div>
		<?php
	}

	protected function service_number($settings)
	{
		?>
		<div class="absp-service-number">
			<span><?php echo esc_html($settings['service_number']); ?></span>
		</div>
		<?php
	}

	protected function service_content($settings)
	{
		?>
		<div class="absp-service-content">
			<p><?php echo wp_kses_post($settings['service_content']); ?></p>
		</div>
		<?php
	}

	protected function service_sub_title($settings)
	{
		$subtitle_before = $subtitle_after = '';

		absp_tag_start($settings['service_sub_title_tag'], 'service_sub_title', $this);

		absp_render_title($settings['service_sub_title'], $subtitle_before, $subtitle_after);

		absp_tag_end($settings['service_sub_title_tag']);
	}

	protected function service_title($settings)
	{
		$title_before = $title_after = '';

		absp_tag_start($settings['service_title_tag'], 'service_title', $this);

		absp_render_title($settings['service_title'], $title_before, $title_after);

		absp_tag_end($settings['service_title_tag']);
	}

	protected function read_more_button($settings, $class_name)
	{
		if ('yes' === $settings['absp_service_btn_switch']) {
			$target = $settings['absp_service_btn_link']['is_external'] ? ' target=_blank' : '';
			$nofollow = $settings['absp_service_btn_link']['nofollow'] ? ' rel=nofollow' : '';
			?>
			<div class="absp-service-btn absp-btn-<?php echo esc_attr($settings['absp_service_btn_align']); ?>">
				<a href="<?php echo esc_url($settings['absp_service_btn_link']['url']) ?>"
				   class="absp-btn <?php echo esc_attr($class_name); ?>" <?php echo esc_attr($target); ?><?php echo esc_attr($nofollow); ?>>
					<?php
					if ('left' === $settings['absp_service_btn_icon_align']) {
						if ('yes' === $settings['absp_service_btn_icons_switch']) {
							Icons_Manager::render_icon($settings['absp_service_btn_icons'], ['aria-hidden' => 'true']);
						}
						echo esc_html($settings['absp_service_btn_text']);
					} else {
						echo esc_html($settings['absp_service_btn_text']);
						if ('yes' === $settings['absp_service_btn_icons_switch']) {
							Icons_Manager::render_icon($settings['absp_service_btn_icons'], ['aria-hidden' => 'true']);
						}
					}
					?>
				</a>
			</div>
		<?php }
	}
}
