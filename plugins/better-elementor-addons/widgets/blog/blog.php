<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Box_Shadow;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


/**
 * Category array
 * @param string $term
 * @return array
 */
function better_cat_array($term = 'category') {
    $cats = get_terms( array(
        'taxonomy' => $term,
        'hide_empty' => true
    ));
    $cat_array = array();
    $cat_array['all'] = esc_html__( 'All', 'better-el-addons');
    foreach ($cats as $cat) {
        $cat_array[$cat->slug] = $cat->name;
    }
    return $cat_array;
}
		
/**
 * @since 1.0.1
 */
class Better_Blog extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.1
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'better-blog';
	}
	public function get_script_depends() { return ['better-el-addons']; }

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.1
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Better Blog', 'better_plg' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.1
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.1
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'better-category' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.1
	 *
	 * @access protected
	 */
	protected function _register_controls() {
	
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Blog Settings', 'better_plg' ),
			]
		);
		$this->add_control(
			'better_blog_style',
			[
				'label' => __( 'Style', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style1' => __( 'Style 1', 'better-el-addons' ),
					'style2' => __( 'Style 2', 'better-el-addons' ),
					'style3' => __( 'Style 3', 'better-el-addons' ),
					'style4' => __( 'Style 4', 'better-el-addons' ),
					'style5' => __( 'Style 5', 'better-el-addons' ),
					'style6' => __( 'Style 6', 'better-el-addons' ),
					'style7' => __( 'Style 7', 'better-el-addons' ),
					'style8' => __( 'Style 8', 'better-el-addons' ),
					'style9' => __( 'Style 9', 'better-el-addons' ),
					'style10' => __( 'Style 10', 'better-el-addons' ),
				],
				'default' => 'style1',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Blog Post Settings', 'better-el-addons' ),
				'condition' => [
					'better_blog_style' => array('style1','style2','style4','style9','style10')
				],
			]
		);
	
		$this->add_control(
            'blog_post',
            [
                'label' => __( 'Blog Post to show', 'better-el-addons' ),
                'type' => Controls_Manager::NUMBER,
				'default' => '6',

            ]
        );
		
		$this->add_control(
			'sort_cat',
			[
				'label' => __( 'Sort post by Category', 'better-el-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'better-el-addons' ),
				'label_off' => __( 'No', 'better-el-addons' ),
				'return_value' => 'yes',
			]
		);
		

		
		$this->add_control(
			'paged_on',
			[
				'label' => __( 'Always show the same list on every page(not paged).', 'better-el-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __( 'Yes', 'better-el-addons' ),
				'label_off' => __( 'No', 'better-el-addons' ),
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'show_excerpt',
			[
				'label' => __( 'Show Exerpt', 'better-el-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'better-el-addons' ),
				'label_off' => __( 'Hide', 'better-el-addons' ),
				'return_value' => 'yes',
				'condition' => [
					'better_blog_style!' => 'style9',
					'better_blog_style!' => 'style10'
				],
			]
		);
		
		$this->add_control(
            'excerpt',
            [
                'label' => __( 'Blog Excerpt Length', 'better-el-addons' ),
                'type' => Controls_Manager::NUMBER,
				'default' => '150',
				'min' => 10,
				'condition' => [
					'show_excerpt' => 'yes',
					'better_blog_style!' => 'style9',
					'better_blog_style!' => 'style10'
				],
            ]
        );

		$this->add_control(
            'excerpt_after',
            [
                'label' => __( 'After Excerpt text/symbol', 'better-el-addons' ),
                'type' => Controls_Manager::TEXT,
				'condition' => [
					'show_excerpt' => 'yes',
					'better_blog_style!' => 'style9',
					'better_blog_style!' => 'style10'
				],
				'default' => '...',
            ]
        );
		
		$this->add_control(
			'blog_column',
			[
				'label' => __( 'Blog Columns', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => __( 'One Column', 'better-el-addons' ),
					'two' => __( 'Two Columns', 'better-el-addons' ),
					'three' => __( 'Three Columns', 'better-el-addons' ),
					'four' => __( 'Four Columns', 'better-el-addons' ),
				],
				'default' => 'three',
			]
		);
		$this->add_control(
			'image',
			[
				'label' => __( 'Show Featured Image', 'better-el-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'better-el-addons' ),
				'label_off' => __( 'Hide', 'better-el-addons' ),
				'return_value' => 'yes',
				'condition' => [
					'better_blog_style!' => 'style9',
					'better_blog_style!' => 'style10'
				],
			]
		);
		
		$this->add_control(
			'button_show',
			[
				'label' => __( 'Show Button', 'better-el-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __( 'Show', 'better-el-addons' ),
				'label_off' => __( 'Hide', 'better-el-addons' ),
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
            'button',
            [
                'label' => __( 'Button Text', 'better-el-addons'),
                'type' => Controls_Manager::TEXT,
				'default' => __( 'Read More', 'better-el-addons' ),
				'label_block' => true,
				'condition' => [
					'button_show' => 'yes',
				],
            ]
        );
		
		$this->add_control(
			'icon',
			[
				'label' => __( 'Button Icon', 'better-el-addons' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
				'condition' => [
					'button_show' => 'yes',
					'better_blog_style!' => 'style9',
					'better_blog_style!' => 'style10'
				],
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label' => __( 'Button Icon Position', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'better-el-addons' ),
					'right' => __( 'After', 'better-el-addons' ),
				],
				'condition' => [
				    'button_show' => 'yes',
					'icon!' => '',
					'better_blog_style!' => 'style9',
					'better_blog_style!' => 'style10'
				],
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label' => __( 'Button Icon Spacing', 'better-el-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'button_show' => 'yes',
					'icon!' => '',
					'better_blog_style!' => 'style9',
					'better_blog_style!' => 'style10'
				],
				'selectors' => [
					'{{WRAPPER}} .content-btn .content-btn-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .content-btn .content-btn-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'meta_show',
			[
				'label' => __( 'Show Post Meta', 'better-el-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'better-el-addons' ),
				'label_off' => __( 'Hide', 'better-el-addons' ),
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'cat_show',
			[
				'label' => __( 'Show Post Category', 'better-el-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'better-el-addons' ),
				'label_off' => __( 'Hide', 'better-el-addons' ),
				'return_value' => 'yes',
				'condition' => [
					'image' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'colors_warning',
			[
				'type' =>  Controls_Manager::RAW_HTML,
				'raw' => __( '<b>Note:</b> Try to show pagination only for (single) blog page.', 'better-el-addons' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition' => [
					'paged_on' => '',
					'better_blog_style!' => 'style9',
					'better_blog_style!' => 'style10'
				],
			]
		);

		$this->add_control(
			'page_show',
			[
				'label' => __( 'Show Pagination', 'better-el-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => __( 'Show', 'better-el-addons' ),
				'label_off' => __( 'Hide', 'better-el-addons' ),
				'return_value' => 'yes',
				'condition' => [
					'paged_on' => '',
					'better_blog_style!' => 'style9',
					'better_blog_style!' => 'style10'
				],
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'main_settings',
			[
				'label' => esc_html__( 'Blog Post Settings', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				'condition' => [
					'better_blog_style' => array('style3','style5','style6','style7','style8')
				],
			]
        );
        
        $this->add_control(
			'posts_number',
			[
				'label' => esc_html__( 'Post Number', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 30,
				'step' => 1,
				'default' => 3,
			]
		);

		$this->add_control(
            'order', [
                'label' => esc_html__( 'Order', 'better-el-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'ASC' => 'ASC',
                    'DESC' => 'DESC'
                ],
                'default' => 'ASC'
            ]
		);
		
		$this->add_control(
            'cat', [
                'label' => esc_html__( 'Category', 'better-el-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => better_cat_array(),
                'default' => 'all',
            ]
        );

		$this->end_controls_section();
		
		$this->start_controls_section(
			'post_section',
			[
				'label' => __( 'Post Settings', 'better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_blog_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_responsive_control(
			'post_margin',
			[
				'label' => __( 'Margin', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .blog-col-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'title_section',
			[
				'label' => __( 'Title Settings', 'better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_blog_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __( 'Margin', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .blog-col-inner .excerpt-box h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typo',
				'label'     => __( 'Title Typography', 'better-el-addons' ),
				'selector'  => '{{WRAPPER}} .blog-col-inner .excerpt-box h3',
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .blog-col-inner .excerpt-box h3' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'title_color_hover',
			[
				'label' => __( 'Color on Hover', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .blog-col-inner .excerpt-box h3:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'text_section',
			[
				'label' => __( 'Text Settings', 'better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_blog_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_responsive_control(
			'text_margin',
			[
				'label' => __( 'Margin', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-blog p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'text_typo',
				'label'     => __( 'Text Typography', 'better-el-addons' ),
				'selector'  => '{{WRAPPER}} .better-blog p',
			]
		);
		
		$this->add_control(
			'text_color',
			[
				'label' => __( 'Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-blog p' => 'color: {{VALUE}};',
				],
			]
		);
		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'meta_section',
			[
				'label' => __( 'Post Meta Settings', 'better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'meta_show' => 'yes',
					'better_blog_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_responsive_control(
			'meta_margin',
			[
				'label' => __( 'Margin', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .blog-col-inner .excerpt-box .post-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'meta_typo',
				'label'     => __( 'Typography', 'better-el-addons' ),
				'selector'  => '{{WRAPPER}} .blog-col-inner .excerpt-box .post-meta',
			]
		);
		
		$this->add_control(
			'meta_color',
			[
				'label' => __( 'Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .blog-col-inner .excerpt-box .post-meta' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'meta_link',
			[
				'label' => __( 'Link Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .blog-col-inner .excerpt-box .post-meta a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'meta_link_hover',
			[
				'label' => __( 'Link Color on Hover', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .blog-col-inner .excerpt-box .post-meta a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'meta_icon',
			[
				'label' => __( 'Icon Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .blog-col-inner .excerpt-box .post-meta .fa' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'cat_section_setting',
			[
				'label' => __( 'Post Category Settings', 'better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'cat_show' => 'yes',
					'better_blog_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_responsive_control(
			'cat_margin',
			[
				'label' => __( 'Margin', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .cat-post' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'cat_typo',
				'label'     => __( 'Typography', 'better-el-addons' ),
				'selector'  => '{{WRAPPER}} .cat-post',
			]
		);
		
		$this->add_control(
			'cat_color',
			[
				'label' => __( 'Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .cat-post' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'btn_settings',
			[
				'label' => __( 'Button Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'button_show' => 'yes',
					'better_blog_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'btn_typography',
				'label'     => __( 'Typography', 'better-el-addons' ),
				'selector'  => '{{WRAPPER}} .content-btn',
			]
		);
		
		$this->add_responsive_control(
			'btn_margin',
			[
				'label' => __( 'Margin', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .content-btn' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => __( 'Padding', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .content-btn' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'btn_border_radius',
			[
				'label' => __( 'Border Radius', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .content-btn' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'btn_color_section',
			[
				'label' => __( 'Button Color Scheme Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'button_show' => 'yes',
					'better_blog_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_control(
			'btn_color',
			[
				'label' => __( 'Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .content-btn' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'btn_color_hover',
			[
				'label' => __( 'Color on Hover','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blog-col-inner .excerpt-box .content-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'btn_bg',
			[
				'label' => __( 'Background Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blog-col-inner .excerpt-box .content-btn' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .blog-col-inner .excerpt-box .content-btn::before' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'btn_bg_hover',
			[
				'label' => __( 'Background Color on Hover','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .content-btn:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .content-btn::after' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'btn_border',
			[
				'label' => __( 'Border', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .content-btn' => 'border-width:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'btn_border_hover',
			[
				'label' => __( 'Border on Hover', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .blog-col-inner .excerpt-box .content-btn:hover' => 'border-width:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'btn_border_color',
			[
				'label' => __( 'Border Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blog-col-inner .excerpt-box .content-btn' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'btn_border_color_hover',
			[
				'label' => __( 'Border Color on  Hover','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .content-btn:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'pagination_setting',
			[
				'label' => __( 'Pagination Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_blog_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_control(
			'page_color',
			[
				'label' => __( 'Pagination Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pagination > li > a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'page_color_hover',
			[
				'label' => __( 'Pagination Color on Hover','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pagination > li > a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'page_color_bg',
			[
				'label' => __( 'Pagination Background Color','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pagination > li > a' => 'background-color: {{VALUE}};border-color:{{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'page_color_hover_bg',
			[
				'label' => __( 'Pagination Background Color on Hover','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pagination > li > a:hover' => 'background-color: {{VALUE}};border-color:{{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'page_color_active',
			[
				'label' => __( 'Pagination Color on Active','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pagination > .active > a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'page_color_hover_bg_active',
			[
				'label' => __( 'Pagination Background Color on Active','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pagination > .active > a' => 'background-color: {{VALUE}};border-color:{{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'content_padding_setting',
			[
				'label' => __( 'Text & Button Content Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_blog_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_control(
			'content_bg',
			[
				'label' => __( 'Background','better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .excerpt-box' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'excerpt_padding_box',
			[
				'label' => __( 'Padding', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .excerpt-box' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .blog-col-inner',
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section( 
			'section_style3',
			[
				'label' => esc_html__( 'Main Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_blog_style' => array('style3','style5','style6','style7','style8','style9','style10')
				],
			]
        );

        $this->add_control(
			'better_blog_item_options',
			[
				'label' => esc_html__( 'Item setting', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
        );
		$this->add_control(
			'better_blog_item_bg',
			[
				'label' => esc_html__( 'Blog item Background', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-blog.style-3 .item.list .cont' => 'background: {{VALUE}}',
                ],
				'condition' => [
					'better_blog_style' => 'style3'
				],
			]
        );
        
        $this->add_control(
			'better_blog_title_options',
			[
				'label' => esc_html__( 'Blog Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
        );
        
        // Sub-Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_blog_title_typography',
				'label' => esc_html__( 'Blog Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .better-blog .posts .item .content h4 a, {{WRAPPER}} .better-blog .item .cont h6, {{WRAPPER}} .better-blog .item .cont h5, {{WRAPPER}} .better-blog .sm-post p',
			]
		);

		$this->add_control(
			'better_blog_title_color',
			[
				'label' => esc_html__( 'Blog Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-blog .posts .item .content h4 a, 
				{{WRAPPER}} .better-blog .item .cont h6, 
				{{WRAPPER}} .better-blog .item .cont h5, 
				{{WRAPPER}} .better-blog.style-3 .item .cont h6 a, 
				{{WRAPPER}} .better-blog .sm-post p' => 'color: {{VALUE}}',
                ],
			]
        );
        
        $this->add_control(
			'better_blog_author_options',
			[
				'label' => esc_html__( 'Blog Author', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
        );
        
        // Sub-Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_blog_author_typography',
				'label' => esc_html__( 'Blog Author Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .better-blog .item .cont .info .author a, {{WRAPPER}} .better-blog .item .cont .info h6 a',
				'condition' => [
					'better_blog_style!' => 'style9'
				],
			]
		);

		$this->add_control(
			'better_blog_author_color',
			[
				'label' => esc_html__( 'Blog Author Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-blog .item .cont .info .author' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-blog .item .cont .info h6' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-blog.style-3 .item .cont .info .author a' => 'color: {{VALUE}}',
                ],
				'condition' => [
					'better_blog_style!' => 'style9'
				],
			]
        );

        $this->add_control(
			'better_blog_category_options',
			[
				'label' => esc_html__( 'Blog Category', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
        );
        
        // Sub-Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_blog_category_typography',
				'label' => esc_html__( 'Blog Category Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .better-blog .posts .item .content .tags a, {{WRAPPER}} .better-blog .item .cont .info .tag a',
			]
		);

		$this->add_control(
			'better_blog_category_color',
			[
				'label' => esc_html__( 'Blog Category Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-blog .posts .item .content .tags a, {{WRAPPER}} .better-blog .item .cont .info .tag,.better-blog.style-3 .item .cont .info .tag a' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
			]
        );
        
        $this->add_control(
			'better_blog_date_options',
			[
				'label' => esc_html__( 'Blog Date', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
        );
        
        // Sub-Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_blog_date_typography',
				'label' => esc_html__( 'Blog Date Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .better-blog .posts .item .content .date, {{WRAPPER}} .better-blog .item .cont h6 span, {{WRAPPER}} .better-blog .item .cont .date span, {{WRAPPER}} .better-blog .item .cont .date span i, {{WRAPPER}} .better-blog .sm-post span',
			]
		);

		$this->add_control(
			'better_blog_date_color',
			[
				'label' => esc_html__( 'Blog Date Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-blog .item .cont .date span' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-blog .item .cont .date span i' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-blog .item .cont h6 span' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-blog .posts .item .content .date' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-blog .sm-post span' => '-webkit-text-fill-color: {{VALUE}}',
                ],
                'separator' => 'after',
			]
        );
        
        $this->add_control(
			'better_blog_date_background',
			[
				'label' => esc_html__( 'Blog Date Background', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-blog .item .cont .date' => 'background: {{VALUE}}',
                ],
                'separator' => 'after',
			]
        );
        
        $this->add_control(
			'better_blog_btn_options',
			[
				'label' => esc_html__( 'Blog Read More Button', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
        );

        // Sub-Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_blog_btn_typography',
				'label' => esc_html__( 'Blog Button Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .better-simple-btn',
			]
		);

		$this->add_control(
			'better_blog_btn_color',
			[
				'label' => esc_html__( 'Blog Button Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-simple-btn'=> 'color: {{VALUE}}',
				'{{WRAPPER}} .better-blog.style-3 .item .cont .btn-more a'=> 'color: {{VALUE}}',

                ],
                'separator' => 'after',
			]
        );
        
        $this->add_control(
			'better_blog_btn_background',
			[
				'label' => esc_html__( 'Blog Button Background', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-simple-btn:after' => 'background: {{VALUE}}',
                ],
                'separator' => 'after',
			]
        );

		$this->add_control(
			'better_blog_cover_color',
			[
				'label' => esc_html__( 'Cover Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-blog.style-7 .item:after' => 'background: {{VALUE}}',
                ],
				'condition' => [
					'better_blog_style' => array('style7')
				],
			]
        );

		$this->add_control(
			'better_blog_border_radius',
			[
				'label' => __( 'Border Radius', 'better-el-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-blog.style-7 .item, {{WRAPPER}} .better-blog.style-7 .item:after' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'better_blog_style' => array('style7')
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.1
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings(); 
		if ($settings['paged_on']  != 'yes') {
			$bim_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		} else {
			$bim_paged = '';
		}
		if ( $settings['sort_cat']  == 'yes' ) {
			$query = new \WP_Query(array(
				'posts_per_page'   => $settings['blog_post'],
				'paged' => $bim_paged,
				'post_type' => 'post',
				'cat'=> $settings['blog_cat']
					
			)); 
		} else { 
			$query = new \WP_Query(array(
				'posts_per_page'   => $settings['blog_post'],
				'paged' => $bim_paged,
				'post_type' => 'post'
			)); 	
			
		}

		$style = $settings['better_blog_style'];
		require_once( 'styles/'.$style.'.php' );	
 
		}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.1
	 *
	 * @access protected
	 */
	protected function content_template() {
		
		
	}
}


