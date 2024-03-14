<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use XproElementorAddons\Control\Xpro_Elementor_Group_Control_Foreground;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class Author_Box extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'xpro-author-box';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Author Box', 'xpro-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'xi-account xpro-widget-label';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'xpro-widgets' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'author', 'user', 'profile', 'biography', 'avatar' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'source',
			array(
				'label'   => __( 'Source', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'current',
				'options' => array(
					'current' => __( 'Current Author', 'xpro-elementor-addons' ),
					'custom'  => __( 'Custom', 'xpro-elementor-addons' ),
				),
			)
		);

		$this->add_control(
			'show_avatar',
			array(
				'label'       => __( 'Avatar', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'   => __( 'Hide', 'xpro-elementor-addons' ),
				'default'     => 'yes',
				'condition'   => array(
					'source!' => 'custom',
				),
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'author_avatar',
			array(
				'label'      => __( 'Avatar Image', 'xpro-elementor-addons' ),
				'show_label' => false,
				'type'       => Controls_Manager::MEDIA,
				'default'    => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic'    => array(
					'active' => true,
				),
				'condition'  => array(
					'source' => 'custom',
				),
			)
		);

		$this->add_control(
			'show_name',
			array(
				'label'       => __( 'Name', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'   => __( 'Hide', 'xpro-elementor-addons' ),
				'default'     => 'yes',
				'condition'   => array(
					'source!' => 'custom',
				),
				'render_type' => 'template',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'author_name',
			array(
				'label'     => __( 'Name', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Jhon Walker', 'xpro-elementor-addons' ),
				'condition' => array(
					'source' => 'custom',
				),
				'dynamic'   => array(
					'active' => true,
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'author_name_tag',
			array(
				'label'   => __( 'HTML Tag', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				),
				'default' => 'h4',
			)
		);

		$this->add_control(
			'link_to',
			array(
				'label'     => __( 'Link', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''              => __( 'None', 'xpro-elementor-addons' ),
					'website'       => __( 'Website', 'xpro-elementor-addons' ),
					'posts_archive' => __( 'Posts', 'xpro-elementor-addons' ),
				),
				'condition' => array(
					'source!' => 'custom',
				),
			)
		);

		$this->add_control(
			'show_biography',
			array(
				'label'       => __( 'Biography', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'   => __( 'Hide', 'xpro-elementor-addons' ),
				'default'     => 'yes',
				'condition'   => array(
					'source!' => 'custom',
				),
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'author_website',
			array(
				'label'       => __( 'Link', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'source' => 'custom',
				),
			)
		);

		$this->add_control(
			'author_bio',
			array(
				'label'     => __( 'Biography', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::WYSIWYG,
				'default'   => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'xpro-elementor-addons' ),
				'rows'      => 3,
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'source' => 'custom',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_link',
			array(
				'label'       => __( 'Archive Button', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'   => __( 'Hide', 'xpro-elementor-addons' ),
				'default'     => 'no',
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'posts_url',
			array(
				'label'       => __( 'Button Link', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => array(
					'url' => '#',
				),
				'condition'   => array(
					'source'    => 'custom',
					'show_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'link_text',
			array(
				'label'     => __( 'Button Text', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'View All Posts', 'xpro-elementor-addons' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'show_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'        => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Above', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'separator'    => 'before',
				'prefix_class' => 'xpro-author-box-alignment-',
			)
		);

		$this->end_controls_section();

		//Styling
		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => __( 'Image', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_responsive_control(
			'width',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array( 'px', '%', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-author-box-avatar > img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'height',
			array(
				'label'          => __( 'Height', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px', 'vh' ),
				'range'          => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .xpro-author-box-avatar > img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'object-fit',
			array(
				'label'     => __( 'Object Fit', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => array(
					'height[size]!' => '',
				),
				'options'   => array(
					''        => __( 'Default', 'xpro-elementor-addons' ),
					'fill'    => __( 'Fill', 'xpro-elementor-addons' ),
					'cover'   => __( 'Cover', 'xpro-elementor-addons' ),
					'contain' => __( 'Contain', 'xpro-elementor-addons' ),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-author-box-avatar > img' => 'object-fit: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .xpro-author-box-avatar > img',
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .xpro-author-box-avatar > img',
			)
		);
		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-author-box-avatar > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'image_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-author-box-avatar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
		//Name
		$this->start_controls_section(
			'section_name_style',
			array(
				'label' => __( 'Name', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Xpro_Elementor_Group_Control_Foreground::get_type(),
			array(
				'name'     => 'name_color',
				'label'    => __( 'Title Color', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .xpro-author-box-name',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-author-box-name',
			)
		);
		$this->add_responsive_control(
			'name_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-author-box-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
		//Bio
		$this->start_controls_section(
			'section_bio_style',
			array(
				'label' => __( 'Biography', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'bio_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-author-box-bio' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'bio_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-author-box-bio',
			)
		);
		$this->add_responsive_control(
			'bio_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-author-box-bio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
		// Button
		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => __( 'Button', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-author-box-button',
			)
		);
		$this->start_controls_tabs( 'button_style_tabs' );
		$this->start_controls_tab(
			'button_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);
		$this->add_control(
			'button_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-author-box-button' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_bg',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-author-box-button',
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-author-box-button',
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'button_hover_tab_style',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);
		$this->add_control(
			'button_hcolor',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-author-box-button:hover,{{WRAPPER}} .xpro-author-box-button:focus' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hbg',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-author-box-button:hover,{{WRAPPER}} .xpro-author-box-button:focus',
			)
		);
		$this->add_control(
			'button_hborder',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-author-box-button:hover,{{WRAPPER}} .xpro-author-box-button:focus' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-author-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'button_item_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-author-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-author-box-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'author-box/layout/frontend.php';
	}
}
