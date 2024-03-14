<?php
use \Elementor\Repeater;
use \Elementor\Utils;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Text_Shadow;
use \Elementor\Group_Control_Css_Filter;

class bdfe_Posts_Layouts extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'post_layouts';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'POST LAYOUTS', BDFE_TEXT_DOMAIN );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'dashicons dashicons-admin-post';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'blogmaker' ];
	}

	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', BDFE_TEXT_DOMAIN ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$select_post_categories = array();
		$select_post_cats = get_terms( 'category' );
		foreach ($select_post_cats as $select_post_cat) :
			$select_post_categories[$select_post_cat->term_id] = $select_post_cat->name;
		endforeach;
		$this->add_control(
			'select_categories',
			[
				'label' => __( 'Select Categories', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $select_post_categories,
			]
		);
		
		$this->add_control(
			'posts_count',
			[
				'label' => __( 'Post Count', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'default' => 6,
			]
		);

		$this->add_control(
			'post_meta_show',
			[
				'label'        => __( 'Show Post Meta?', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'post_meta_data',
			[
				'label' => __( 'Post Meta', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => [
					'author'	=>	__( 'Author', BDFE_TEXT_DOMAIN ),
					'date'	=>	__( 'Date', BDFE_TEXT_DOMAIN ),
					'comments'	=>	__( 'Comments', BDFE_TEXT_DOMAIN ),
				],
				'condition'	=>	[
					'post_meta_show'	=>	'true',
				]
			]
		);

		$this->add_control(
			'post_excerpt_show',
			[
				'label'        => __( 'Show Excerpt?', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
				
			]
		);
		$this->add_control(
			'post_excerpt_length',
			[
				'label' => __( 'Excerpt Length', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'min' => 70,
				'max' => 600,
				'step' => 10,
				'default' => 150,
				'condition'	=>	[
					'post_excerpt_show'	=>	'true',
				]
			]
		);

		$this->add_control(
			'posts_per_row',
			[
				'label' => __( 'Columns', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SELECT,
				'multiple' => false,
				'default' => 'two',
				'options' => [
					'one' => __( 'One Column', BDFE_TEXT_DOMAIN ),
					'two' => __( 'Two Column', BDFE_TEXT_DOMAIN ),
					'three' => __( 'Three Column', BDFE_TEXT_DOMAIN ),
					'four' => __( 'Four Column', BDFE_TEXT_DOMAIN ),
					'six' => __( 'Six Column', BDFE_TEXT_DOMAIN ),
				],
			]
		);
		$this->add_control(
			'post_thumbnail_show',
			[
				'label'        => __( 'Show Thumbnail?', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'thumbnail_position',
			[
				'label' => __( 'Thumbnail Position', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SELECT,
				'multiple' => false,
				'default' => 'top',
				'options' => [
					'left' => __( 'Thumbnail Left', BDFE_TEXT_DOMAIN ),
					'top' => __( 'Thumbnail Top', BDFE_TEXT_DOMAIN ),
					'right' => __( 'Thumbnail Right', BDFE_TEXT_DOMAIN ),
				],
				'condition'	=>	[
					'post_thumbnail_show'	=>	'true',
				]
			]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Actually its `image_size`.
				'default' => 'large',
				'exclude' => [ 'custom' ],
				'condition'	=>	[
					'post_thumbnail_show'	=>	'true',
				]
			]
		);
		$this->add_control(
			'masonry_active',
			[
				'label'        => __( 'Masonry Layout?', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'post_title_show',
			[
				'label'        => __( 'Show Title?', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'post_category_show',
			[
				'label'        => __( 'Show Category?', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'show_category_over_image',
			[
				'label'        => __( 'Show Category Over Image?', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
				'condition'	=>	[
					'post_category_show' => 'true',
				]
			]
		);
		$this->add_control(
			'read_button_show',
			[
				'label'        => __( 'Show Read More Button?', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', BDFE_TEXT_DOMAIN ),
				'label_off'    => __( 'Hide', BDFE_TEXT_DOMAIN ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);

		$this->add_control(
			'read_more_button_text',
			[
				'label'        => __( 'Button Text', BDFE_TEXT_DOMAIN ),
				'type'         => Controls_Manager::TEXT,
				'default'      => __( 'Read More', BDFE_TEXT_DOMAIN ),
				'condition'		=>	[
					'read_button_show'	=>	'true',
				]
			]
		);
		$this->add_control(
			'post_pagination_show',
			[
				'label' => __( 'Pagination', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SELECT,
				'options'	=>	[
					'none' => __( 'None', BDFE_TEXT_DOMAIN ),
					'number' => __( 'Number', BDFE_TEXT_DOMAIN ),
					'navs' => __( 'Next / Prev', BDFE_TEXT_DOMAIN ),
				],
				'default'      => 'number',
			]
		);
		$this->add_control(
			'pagination_alignment',
			[
				'label' => __( 'Pagination Alignment', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SELECT,
				'default'      => 'center',
				'options'	=>	[
					'start'	=>	__( 'Left', BDFE_TEXT_DOMAIN ),
					'center'	=>	__( 'Center', BDFE_TEXT_DOMAIN ),
					'end'	=>	__( 'Right', BDFE_TEXT_DOMAIN ),
				],
				'condition'	=>	[
					'post_pagination_show'	=>	'number'
				]
			]
		);

		$this->end_controls_section();
		/*style tab*/
		/*Style Tab*/
		$this->start_controls_section(
			'post_style_section',
			[
				'label' => __( 'Box', BDFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs(
			'post_box_style_tabs'
		);
		$this->start_controls_tab(
			'post_style_normal_tab',
			[
				'label' => __( 'Normal', BDFE_TEXT_DOMAIN ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'post_box_background',
				'label' => __( 'Box Background', BDFE_TEXT_DOMAIN ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .theimran-post-layout-one',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => __( 'Box Shadow', BDFE_TEXT_DOMAIN ),
				'selector' => '{{WRAPPER}} .theimran-post-layout-one',
			]
		);

		$this->add_responsive_control(
			'post_box_padding',
			[
				'label' => __( 'Content Padding', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'post_item_box_padding',
			[
				'label' => __( 'Item Padding', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'post_box_margin',
			[
				'label' => __( 'Item Margin', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'post_box_border',
				'selector' => '{{WRAPPER}} .theimran-post-layout-one',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'post_content_alignment',
			[
				'label'     => __( 'Text AlignMent', BDFE_TEXT_DOMAIN ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options' => [
					'left'  => __( 'Left', BDFE_TEXT_DOMAIN ),
					'center' => __( 'Center', BDFE_TEXT_DOMAIN ),
					'right' => __( 'Right', BDFE_TEXT_DOMAIN ),
				],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__content-wrapper' => 'text-align: {{VALUE}}'
				]
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'post_style_hover_tab',
			[
				'label' => __( 'Hover', BDFE_TEXT_DOMAIN ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'post_box_hover_background',
				'label' => __( 'Box Background', BDFE_TEXT_DOMAIN ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .theimran-post-layout-one:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'post_box_hover_shadow',
				'label' => __( 'Box Shadow', BDFE_TEXT_DOMAIN ),
				'selector' => '{{WRAPPER}} .theimran-post-layout-one:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
		$this->start_controls_section(
			'post_thumnail_style',
			[
				'label' => __( 'Post Thumbnail', BDFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs(
			'post_thumbnail_style_tab'
		);
		$this->start_controls_tab(
			'post_thumbnail_style_normal_tab',
			[
				'label' => __( 'Normal', 'plugin-name' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'post_thumbnail_filter',
				'selector' => '{{WRAPPER}} .theimran-post-layout-one img',
			]
		);
		$this->add_control(
			'post_thumbnail_border_radius',
			[
				'label' => __( 'Border Radius', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'post_thumbnail_style_hover_tab',
			[
				'label' => __( 'Hover', 'plugin-name' ),
			]
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'post_thumbnail_hover_filter',
				'selector' => '{{WRAPPER}} .theimran-post-layout-one img:hover',
			]
		);
		$this->add_control(
			'post_thumbnail_border_hover_radius',
			[
				'label' => __( 'Border Radius', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one img:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		$this->start_controls_section(
			'post_title_style',
			[
				'label' => __( 'Post Title', BDFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'post_title_show' => 'true',
				]
			]
		);
		$this->start_controls_tabs(
			'style_title_tabs'
		);
		$this->start_controls_tab(
			'title_tab_normal',
			[
				'label' => __( 'Normal', BDFE_TEXT_DOMAIN ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_title_typography',
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__title h3',
			]
		);

		$this->add_control(
			'post_title_color',
			[
				'label' => __( 'Title Color', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__title h3 a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_title_padding',
			[
				'label' => __( 'Padding', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__title h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'post_title_margin',
			[
				'label' => __( 'Margin', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__title h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'title_tab_hover',
			[
				'label' => __( 'hover', BDFE_TEXT_DOMAIN ),
			]
		);
		$this->add_control(
			'post_title_hover_color',
			[
				'label' => __( 'Title Hover Color', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__title h3 a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'post_title_text_hover_shadow',
				'label' => __( 'Text Shadow', BDFE_TEXT_DOMAIN ),
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__title h3',
			]
		);
		$this->end_controls_tabs();
		$this->end_controls_section();
		$this->start_controls_section(
			'post_excerpt_style',
			[
				'label' => __( 'Post Excerpt', BDFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition'	=>	[
					'post_excerpt_show' => 'true',
				]
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_excerpt_typography',
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__excerpt',
			]
		);
		$this->add_control(
			'post_excerpt_color',
			[
				'label' => __( 'Description Color', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__excerpt' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'post_excerpt_text_shadow',
				'label' => __( 'Text Shadow', BDFE_TEXT_DOMAIN ),
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__excerpt p',
			]
		);
		$this->add_responsive_control(
			'post_excerpt_padding',
			[
				'label' => __( 'Padding', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__excerpt p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'post_excerpt_margin',
			[
				'label' => __( 'Margin', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__excerpt p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'post_category_style',
			[
				'label' => __( 'Post category', BDFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition'	=>	[
					'post_category_show' => 'true',
				]
			]
		);
		$this->start_controls_tabs(
			'post_category_style_tab'
		);
		$this->start_controls_tab(
			'post_category_normal_tab',
			[
				'label'	=>	__( 'Normal', BDFE_TEXT_DOMAIN ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_category_typography',
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__categories a',
			]
		);
		$this->add_control(
			'post_category_color',
			[
				'label' => __( 'Category Color', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__categories a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'post_category_bg_color',
			[
				'label' => __( 'Category Background', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__categories a' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'post_category_border',
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__categories a',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'post_category_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__categories a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'post_category_text_shadow',
				'label' => __( 'Text Shadow', BDFE_TEXT_DOMAIN ),
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__categories a',
			]
		);
		$this->add_responsive_control(
			'post_category_padding',
			[
				'label' => __( 'Padding', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__categories a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'post_category_margin',
			[
				'label' => __( 'Margin', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__categories' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'post_category_hover_tab',
			[
				'label'	=>	__( 'Hover', BDFE_TEXT_DOMAIN ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_category_hover_typography',
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__categories a:hover',
			]
		);
		$this->add_control(
			'post_category_hover_color',
			[
				'label' => __( 'Category Color', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__categories a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'post_category_bg_hover_color',
			[
				'label' => __( 'Category Background', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__categories a:hover' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'post_category_hover_border',
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__categories a:hover',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'post_category_border_hover_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__categories a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'post_category_text_hover_shadow',
				'label' => __( 'Text Shadow', BDFE_TEXT_DOMAIN ),
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__categories a:hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		$this->start_controls_section(
			'post_meta_style',
			[
				'label' => __( 'Post Meta', BDFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition'	=>	[
					'post_meta_show' => 'true',
				]
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_meta_typography',
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__blog-meta ul li',
			]
		);
		$this->add_control(
			'post_meta_color',
			[
				'label' => __( 'Meta Color', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__blog-meta ul li a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'post_meta_icon_color',
			[
				'label' => __( 'Icon Color', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__blog-meta ul li a span.fa, {{WRAPPER}} .theimran-post-layout-one__blog-meta ul li span.fa' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'post_meta_text_shadow',
				'label' => __( 'Text Shadow', BDFE_TEXT_DOMAIN ),
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__blog-meta ul li a',
			]
		);
		$this->add_responsive_control(
			'post_meta_padding',
			[
				'label' => __( 'Padding', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__blog-meta ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'post_meta_margin',
			[
				'label' => __( 'Margin', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__blog-meta ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'post_read_button_style',
			[
				'label' => __( 'Read More Button', BDFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition'	=> [
					'read_button_show'	=> 'true',
				]
			]
		);
		$this->start_controls_tabs(
			'readmore_button_tabs'
		);
		$this->start_controls_tab(
				'style_normal_button',
				[
					'label' => __( 'Normal', BDFE_TEXT_DOMAIN ),
				]
			);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_read_button_typography',
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__read-more a',
			]
		);
		$this->add_control(
			'post_read_button_color',
			[
				'label' => __( 'Button Color', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__read-more a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'post_read_button_bg_color',
			[
				'label' => __( 'Button Background', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__read-more a' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'post_read_button_border',
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__read-more a',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'post_read_button_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__read-more a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'post_read_button_text_shadow',
				'label' => __( 'Text Shadow', BDFE_TEXT_DOMAIN ),
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__read-more a',
			]
		);
		$this->add_responsive_control(
			'post_read_button_padding',
			[
				'label' => __( 'Padding', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__read-more a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'post_read_button_margin',
			[
				'label' => __( 'Margin', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__read-more a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'style_hover_button',
			[
				'label' => __( 'Hover', BDFE_TEXT_DOMAIN ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'post_read_button_hover_typography',
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__read-more a:hover',
			]
		);
		$this->add_control(
			'post_read_button_hover_color',
			[
				'label' => __( 'Button Color', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__read-more a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'post_read_button_bg_hover_color',
			[
				'label' => __( 'Button Background', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__read-more a:hover' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'post_read_button_hover_border',
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__read-more a:hover',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'post_read_button_border_hover_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .theimran-post-layout-one__read-more a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'post_read_button_hover_text_shadow',
				'label' => __( 'Text Shadow', BDFE_TEXT_DOMAIN ),
				'selector' => '{{WRAPPER}} .theimran-post-layout-one__read-more a:hover',
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		$this->start_controls_section(
			'pagination_style',
			[
				'label' => __( 'Pagination', BDFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
				
			]
		);
		$this->start_controls_tabs(
			'pagination_tabs'
		);

		$this->start_controls_tab(
			'pagination_tab_normal',
			[
				'label' => __( 'Normal', BDFE_TEXT_DOMAIN ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pagination_typography',
				'selector' => '{{WRAPPER}} .pagination-wrapper ul li a, {{WRAPPER}} .post-navigation-older-newer a',
			]
		);
		$this->add_control(
			'pagination_color',
			[
				'label' => __( 'Text Color', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .pagination-wrapper ul li a, {{WRAPPER}} .post-navigation-older-newer a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'pagination_bg_color',
			[
				'label' => __( 'Background', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .pagination-wrapper ul li a, {{WRAPPER}} .post-navigation-older-newer a' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'pagination_border',
				'selector' => '{{WRAPPER}} .pagination-wrapper ul li a, {{WRAPPER}} .post-navigation-older-newer a',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pagination_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pagination-wrapper ul li a, {{WRAPPER}} .post-navigation-older-newer a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'pagination_text_shadow',
				'label' => __( 'Text Shadow', BDFE_TEXT_DOMAIN ),
				'selector' => '{{WRAPPER}} .pagination-wrapper ul li a, {{WRAPPER}} .post-navigation-older-newer a',
			]
		);
		$this->add_responsive_control(
			'pagination_padding',
			[
				'label' => __( 'Padding', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pagination-wrapper ul li a, {{WRAPPER}} .pagination-wrapper ul li span.current, {{WRAPPER}} .post-navigation-older-newer a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'pagination_margin',
			[
				'label' => __( 'Margin', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pagination-wrapper ul, {{WRAPPER}} .post-navigation-older-newer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);	

		$this->end_controls_tab();
		$this->start_controls_tab(
			'pagination_tab_hover',
			[
				'label' => __( 'Hover', BDFE_TEXT_DOMAIN ),
			]
		);
		$this->add_control(
			'pagination_hover_color',
			[
				'label' => __( 'Text Color', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .pagination-wrapper ul li a:hover, {{WRAPPER}} .post-navigation-older-newer a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'pagination_hover_bg_color',
			[
				'label' => __( 'Background', BDFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .pagination-wrapper ul li a:hover, {{WRAPPER}} .post-navigation-older-newer a:hover' => 'background: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tabs();
		$this->end_controls_section();

	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		$bdfe_get_categories = $settings['select_categories'];
		$bdfe_posts_per_row = $settings['posts_per_row'];
		$bdfe_posts_count = $settings['posts_count'];
		$bdfe_masonry_active = $settings['masonry_active'];
		$bdfe_thumbnail_position = $settings['thumbnail_position'];
		if ( get_query_var('paged') ) {
	    $paged = get_query_var('paged');
		} elseif ( get_query_var('page') ) { // if is static front page
		    $paged = get_query_var('page');
		} else {
		    $paged = 1;
		}
		$args = array(
			'post_type' => array('post'),
			'posts_per_page' => $bdfe_posts_count,
			'paged'			=> $paged,
			'category__in' => $bdfe_get_categories,
		);
		$bdfe_post_query = new \WP_Query( $args );

		$masonry_row_class = $masonry_grid_class = $post_columns_class = "";
		if ('true' === $bdfe_masonry_active) {
			$masonry_row_class = ' masonaryactive ';
			$masonry_grid_class = 'blog-grid-layout ';
		}
		if ('one' === $bdfe_posts_per_row) {
		$post_columns_class = 'col-md-12';
		}elseif('two' === $bdfe_posts_per_row) {
			$post_columns_class = 'col-md-6 col-lg-6';
		}elseif('three' === $bdfe_posts_per_row){
			$post_columns_class = 'col-md-6 col-lg-4';
		}elseif('four' === $bdfe_posts_per_row){
			$post_columns_class = 'col-md-4 col-lg-3';
		}elseif('six' === $bdfe_posts_per_row){
			$post_columns_class = 'col-md-3 col-lg-2';
		}
		$thumbnail_container = '';
		$thumbnail_wrapper	= '';
		$content_wrapper	= '';
		if ('left' === $bdfe_thumbnail_position) {
			$thumbnail_container = ' row';
			$thumbnail_wrapper	= ' col-md-6 pl-0 pr-3';
			$content_wrapper	= ' col-md-6';
		}elseif ('right' === $bdfe_thumbnail_position) {
			$thumbnail_container = ' row flex-row-reverse';
			$thumbnail_wrapper	= ' col-md-6 pl-3';
			$content_wrapper	= ' col-md-6';
		}
		if ($bdfe_post_query->have_posts()) :
			echo '<div class="row'.$masonry_row_class.'">'; //start row
			while ($bdfe_post_query->have_posts()) :
				$bdfe_post_query->the_post();
					echo '<div class="'. $masonry_grid_class . $post_columns_class .'">'; //Start Column
						echo '<div class="theimran-post-layout-one'.$thumbnail_container.'">';
							//start single post wrapper
							echo '<div class="theimran-post-layout-one__thumbnail'.$thumbnail_wrapper.'">';
								$this->bdfe_render_post_thumbnail();
							echo '</div>';
							echo '<div class="theimran-post-layout-one__content-wrapper'.$content_wrapper.'">'; //start content wrapper
								$this->bdfe_render_category();
								$this->bdfe_render_title();
								$this->bdfe_render_post_meta();
								$this->bdfe_render_excerpt();
								$this->bdfe_render_readmore_button();
							echo '</div>'; //End Content Wrapper
						echo '</div>'; // end single post wrapper
					echo '</div>'; // End column
			endwhile; 
			echo '</div>'; //end row
			$this->bdfe_render_pagination($paged, $bdfe_post_query);
		endif; wp_reset_postdata();
	}

	public function bdfe_render_post_thumbnail(){
		$settings = $this->get_settings_for_display();
		$bdfe_post_thumbnail_show = $settings['post_thumbnail_show'];
		$bdfe_image_size = $settings['image_size'];
		$bdfe_post_category_show = $settings['post_category_show'];
		$bdfe_show_category_over_image = $settings['show_category_over_image'];
		$bdfe_category_wrapper = has_post_thumbnail() ? '' : ' position-static';
		if('true' === $bdfe_post_thumbnail_show) : ?>
			<a href="<?php the_permalink();?>"><?php the_post_thumbnail( $bdfe_image_size );?></a>
			<?php if('true' === $bdfe_post_category_show && 'true' === $bdfe_show_category_over_image) : ?>
			<div class="theimran-post-layout-one__categories<?php echo esc_attr($bdfe_category_wrapper);?>">
				<?php
					$categories_list = get_the_category_list( esc_html__( ', ', 'bdfe' ) );
					if ( $categories_list ) {
						/* translators: 1: list of categories. */
						printf( '<span class="cat-links">' . esc_html__( '%1$s', 'bdfe' ) . '</span>', $categories_list ); // WPCS: XSS OK.
					}
					?>
			</div>
			<?php endif;
		endif;
	}
	public function bdfe_render_title(){
		$settings = $this->get_settings_for_display();
		$bdfe_post_title_show = $settings['post_title_show'];
		if('true' === $bdfe_post_title_show):
			?>
			<div class="theimran-post-layout-one__title">
				<h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
			</div>
		<?php endif; 
	}
	public function bdfe_render_excerpt(){
		$settings = $this->get_settings_for_display();
		$bdfe_post_excerpt_show = $settings['post_excerpt_show'];
		$bdfe_post_excerpt_length = $settings['post_excerpt_length'];
		if('true' === $bdfe_post_excerpt_show) : ?>
			<div class="theimran-post-layout-one__excerpt">
				<p>
					<?php echo esc_html( bdfe_get_excerpt( $bdfe_post_excerpt_length ) );?>
				</p>
			</div>
		<?php endif;
	}
	public function bdfe_render_readmore_button(){
		$settings = $this->get_settings_for_display();
		$bdfe_read_button_show = $settings['read_button_show'];
		$bdfe_read_more_button_text = $settings['read_more_button_text'];
		if('true' === $bdfe_read_button_show):
		?>
		<div class="theimran-post-layout-one__read-more">
			<a href="<?php the_permalink();?>"><?php echo esc_html($bdfe_read_more_button_text); ?></a>
		</div>
		<?php endif;
	}
	public function bdfe_render_post_meta(){
		$settings = $this->get_settings_for_display();
		$bdfe_post_meta_show = $settings['post_meta_show'];
		$bdfe_post_meta_data = is_array($settings['post_meta_data']) ? $settings['post_meta_data'] : array();
		if('true' === $bdfe_post_meta_show) :
		?>
		<div class="theimran-post-layout-one__blog-meta">
			<ul>
				<?php if(in_array('author', $bdfe_post_meta_data)) : ?>
				<li class="author-meta"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) ); ?>"><span class="post-author-image"><?php echo get_avatar( get_the_author_meta('ID'), 30); ?></span> <?php echo esc_html( get_the_author() ); ?></a></li>
				<?php endif;
				if(in_array('date', $bdfe_post_meta_data)) :
				?>
				<li><a href="#"> <span class="far fa-calendar-alt"></span><?php bdfe_posted_on(); ?></a></li>
				<?php endif;
				if(in_array('comments', $bdfe_post_meta_data)) :
				?>
				<li><span class="fas fa-comment"></span> <?php bdfe_comment_popuplink(); ?></li>
				<?php endif; ?>
			</ul>
		</div>
		<?php endif;
	}
	public function bdfe_render_category(){
		$settings = $this->get_settings_for_display();
		$bdfe_post_category_show = $settings['post_category_show'];
		$bdfe_show_category_over_image = $settings['show_category_over_image'];
		if('true' === $bdfe_post_category_show && empty($bdfe_show_category_over_image)) : ?>
		<div class="theimran-post-layout-one__categories position-static">
				<?php
				$categories_list = get_the_category_list( esc_html__( ', ', BDFE_TEXT_DOMAIN ) );
				if ( $categories_list ):
					/* translators: 1: list of categories. */
					printf( '<span class="cat-links">' . __( '%1$s', BDFE_TEXT_DOMAIN ) . '</span>', $categories_list ); // WPCS: XSS OK.
				endif;
				?>
		</div>
		<?php endif;
	}
	public function bdfe_render_pagination($paged, $query){
		$settings = $this->get_settings_for_display();
		$bdfe_posts_pagination = $settings['post_pagination_show'];
		$bdfe_pagination_alignment = $settings['pagination_alignment'];
		$totalpages = $query->max_num_pages;
	    $current = max(1,$paged );
	    $prev_icon = '<i class="fas fa-angle-left"></i>';
	    $next_icon = '<i class="fas fa-angle-right"></i>';
		if ('number' === $bdfe_posts_pagination && $totalpages > 1) :?>
		<div class="Page d-flex justify-content-<?php echo esc_attr( $bdfe_pagination_alignment );?> frontpage navigation example">

			<div class="pagination-wrapper">
				<?php
				global $wp_query;
	            $big = 999999999; // need an unlikely integer
				$paginate_args = array(
		            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))) ,
		            'format' => '?paged=%#%',
		            'current' => $current,
		            'total' => $totalpages,
		            'show_all' => False,
		            'end_size' => 1,
		            'mid_size' => 3,
		            'prev_next' => true,
		            'prev_text' => $prev_icon ,
		            'next_text' => $next_icon ,
		            'type' => 'list',
		          );
        		echo paginate_links($paginate_args);?>
			</div>
		</div>
		<?php elseif( 'navs' === $bdfe_posts_pagination ) :
			$olderpost = esc_html__( 'Older Posts', BDFE_TEXT_DOMAIN ) . '<i class="fa fa-long-arrow-right"></i>';
			$newerpost = '<i class="fa fa-long-arrow-left"></i>' . esc_html__( 'Newer Posts', BDFE_TEXT_DOMAIN );
		?>
		<div class="post-navigation-older-newer">
			<div class="older-posts-link">
				<?php next_posts_link(  $olderpost, $query->max_num_pages ); ?>
			</div>
			<div class="newer-posts-link">
             	<?php previous_posts_link( $newerpost ) ?>
			</div>
		</div>
			<?php
		endif;
	}
	
}