<?php
/*
 * Display Related Product Elementor Elements
 */
namespace Elementor;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Related_Product_Manager extends Widget_Base {

	public function get_name() {
		return 'related-product-manager';
	}

	public function get_title() {
		return __( 'Related Products Manager',RPMW_TEXTDOMAIN );
	}

	public function get_icon() {
		return 'eicon-product-related';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'related', 'similar', 'product' ];
	}
     public function get_categories() {
          return ['related-product-manager'];
     }

    // Adding the controls fields for the Related product manager Element
	protected function _register_controls() {
		$this->start_controls_section(
			'section_related_products_content',
                    [
                    'label' => __( 'Related Products Manager',RPMW_TEXTDOMAIN ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
		);  
          $this->add_control(
               'related_product_heading', [
                    'label' => __('Heading',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::TEXT,
                    'default' => __('Related Product',RPMW_TEXTDOMAIN),
                    'placeholder' => __('Enter Heading',RPMW_TEXTDOMAIN),
               ]
          );
		$this->add_control(
			'posts_per_page',[
				'label' => __( 'Display Number of Product',RPMW_TEXTDOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'range' => [
					'px' => [
						'max' => 20,
					],
				],
			]

          );
          $this->add_responsive_control(
               'columns', [
                    'label' => esc_html__('Columns',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::SELECT,
                    'default' => '4',
                    'tablet_default' => '2',
                    'mobile_default' => '1',
                    'options' => [
                         '1' => esc_html__('1',RPMW_TEXTDOMAIN),
                         '2' => esc_html__('2',RPMW_TEXTDOMAIN),
                         '3' => esc_html__('3',RPMW_TEXTDOMAIN),
                         '4' => esc_html__('4',RPMW_TEXTDOMAIN),
                         '5' => esc_html__('5',RPMW_TEXTDOMAIN),
                         '6' => esc_html__('6',RPMW_TEXTDOMAIN),
                    ],
                    'frontend_available' => true,
               ]
          );
          $this->add_control(
               'orderby',[
                    'label' => __( 'Order By',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'id',
                    'options' => [
                         'id' => __( 'ID',RPMW_TEXTDOMAIN ),
                         'date' => __( 'Date',RPMW_TEXTDOMAIN ),
                         'title' => __( 'Title',RPMW_TEXTDOMAIN ),
                         'price' => __( 'Price',RPMW_TEXTDOMAIN ),
                         'rating' => __( 'Rating',RPMW_TEXTDOMAIN ),
                         'rand' => __( 'Random',RPMW_TEXTDOMAIN ),
                         'modified' => __( 'Modified',RPMW_TEXTDOMAIN ),
                    ],
               ]
          );
          $this->add_control(
               'order',[
                    'label' => __( 'Order',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'desc',
                    'options' => [
                         'asc' => __( 'ASC',RPMW_TEXTDOMAIN ),
                         'desc' => __( 'DESC',RPMW_TEXTDOMAIN ),
                    ],
               ]
          );
          $this->add_control(
               'show_heading', [
                    'label' => esc_html__('Show Heading',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'label_off' => __('Off',RPMW_TEXTDOMAIN),
                    'label_on' => __('On',RPMW_TEXTDOMAIN),
                    'separator' => 'before',
               ]
          );
          $this->add_control(
               'title_tag', [
                    'label' => esc_html__('Heading HTML Tag',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                         'h1' => esc_html__('H1',RPMW_TEXTDOMAIN),
                         'h2' => esc_html__('H2',RPMW_TEXTDOMAIN),
                         'h3' => esc_html__('H3',RPMW_TEXTDOMAIN),
                         'h4' => esc_html__('H4',RPMW_TEXTDOMAIN),
                         'h5' => esc_html__('H5',RPMW_TEXTDOMAIN),
                         'h6' => esc_html__('H6',RPMW_TEXTDOMAIN),
                         'div' => esc_html__('div',RPMW_TEXTDOMAIN),
                         'span' => esc_html__('span',RPMW_TEXTDOMAIN),
                         'p' => esc_html__('p',RPMW_TEXTDOMAIN),
                    ],
                    'condition' => [
                         'show_heading' => 'yes',
                    ],
                    'default' => 'h2',
               ]
          );
          $this->add_control(
               'show_category', [
                    'label' => esc_html__('Show Category',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'label_off' => __('Off',RPMW_TEXTDOMAIN),
                    'label_on' => __('On',RPMW_TEXTDOMAIN),
               ]
          );
          $this->add_control(
               'show_tag', [
                    'label' => esc_html__('Show Tag',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'label_off' => __('Off',RPMW_TEXTDOMAIN),
                    'label_on' => __('On',RPMW_TEXTDOMAIN),
               ]
          );
          $this->add_control(
               'show_sale_badge', [
                    'label' => esc_html__('Show Sale Badge',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'label_off' => __('Off',RPMW_TEXTDOMAIN),
                    'label_on' => __('On',RPMW_TEXTDOMAIN),
               ]
          );
          $this->add_control(
               'related_product_sale_icon_title', [
                    'label' => __('Title',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::TEXT,
                    'default' => __('Sale!',RPMW_TEXTDOMAIN),
                    'condition' => [
                              'show_sale_badge' => 'yes',
                         ],
               ]    
          ); 
          $this->add_control(
               'product_categories', [
                    'label' => esc_html__('Related By Categories',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::SELECT2,
                    'label_block' => true,
                    'multiple' => true,
                    'description' => esc_html__('Select the categories you want to show',RPMW_TEXTDOMAIN),
                    'options' => rpmw_post_categories(),
                    'separator' => 'before',
               ]   
          );
          $this->add_control(
               'exclude_product_categories', [
                    'label' => esc_html__('Exclude Above Categories',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes',RPMW_TEXTDOMAIN),
                    'label_off' => esc_html__('NO',RPMW_TEXTDOMAIN),
                    'default' => 'no',
               ]
          );
          $this->add_control(
               'button_text', [
                    'label' => __('Button Text',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::TEXT,
                    'default' => __('View Product',RPMW_TEXTDOMAIN),
                    'placeholder' => __('Button text',RPMW_TEXTDOMAIN),
                    'separator' => 'before',     
               ]
          );

          $this->end_controls_section();

          parent::_register_controls();

          $this->start_controls_section(
               'section_heading_style',
               [
                    'label' => __( 'Heading',RPMW_TEXTDOMAIN ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                         'show_heading' => 'yes',
                    ],
               ]
          );
          $this->add_control(
               'heading_color',[
                    'label' => __( 'Color',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::COLOR,
                    'global' => [
                         'default' => Global_Colors::COLOR_PRIMARY,
                    ],
                    'selectors' => [
                         '{{WRAPPER}} .elementor-product-heading-wrapper' => 'color: {{VALUE}};',
                    ],
               ]
          );
          $this->add_group_control(
               Group_Control_Typography::get_type(),
               [
                    'name' => 'heading_typography',
                    'global' => [
                         'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                    ],
                    'selector' => '{{WRAPPER}} .elementor-product-heading-wrapper',
               ]
          );
          $this->add_responsive_control(
               'heading_text_align', [
                    'label' => __( 'Text Align',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                         'left' => [
                              'title' => __( 'Left',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-left',
                         ],
                         'center' => [
                              'title' => __( 'Center',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-center',
                         ],
                         'right' => [
                              'title' => __( 'Right',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-right',
                         ],
                    ],
                    'selectors' => [
                         '{{WRAPPER}} .elementor-product-heading-wrapper' => 'text-align: {{VALUE}};',
                    ],
               
               ]
          );
          $this->add_responsive_control(
               'heading_spacing',
               [
                    'label' => __( 'Spacing',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                         '{{WRAPPER}} .elementor-product-heading-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    ],
                    
               ]
          );
          $this->end_controls_section();

          $this->start_controls_section(
               'section_design_layout', [
                    'label' => esc_html__('Layout',RPMW_TEXTDOMAIN),
                    'tab' => Controls_Manager::TAB_STYLE,
               ]
          );
          $this->add_control(
               'product_grid_gap', [
                    'label' => esc_html__('Grid Gap',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                         'size' => 30,
                    ],
                    'range' => [
                         'px' => [
                         'min' => 0,
                         'max' => 100,
                         ],
                    ],
                    'frontend_available' => true,
                    'selectors' => [
                         '{{WRAPPER}} .product-grid-gap' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
                    ],
               ]
          );
          $this->add_control(
               'product_row_gap', [
                    'label' => esc_html__('Rows Gap',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                         'size' => 30,
                    ],
                    'range' => [
                         'px' => [
                         'min' => 0,
                         'max' => 100,
                         ],
                    ],
                    'frontend_available' => true,
                    'selectors' => [
                         '{{WRAPPER}} .product-container' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
               ]
          );
          $this->end_controls_section();
     
          $this->start_controls_section(
               'section_design_image_layout', [
                    'label' => esc_html__('Image',RPMW_TEXTDOMAIN),
                    'tab' => Controls_Manager::TAB_STYLE,
               ]
          );
          $this->add_control(
               'img_border_radius', [
                    'label' => esc_html__('Border Radius',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                         '{{WRAPPER}} .related-products_img, {{WRAPPER}} .related-products_img a img, {{WRAPPER}} .elementor-product-item__overlay, {{WRAPPER}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
               ]
          );
          $this->start_controls_tabs('thumbnail_effects_tabs');

          $this->start_controls_tab('normal', [
               'label' => esc_html__('Normal',RPMW_TEXTDOMAIN),
                    ]
          );
          $this->add_group_control(
                    Group_Control_Css_Filter::get_type(), [
               'name' => 'thumbnail_filters',
               'selector' => '{{WRAPPER}} .product_thumbnail img',
                    ]
          );
          $this->end_controls_tab();

          $this->start_controls_tab('hover', [
               'label' => esc_html__('Hover',RPMW_TEXTDOMAIN),
                    ]
          );
          $this->add_group_control(
                    Group_Control_Css_Filter::get_type(), [
               'name' => 'thumbnail_hover_filters',
               'selector' => '{{WRAPPER}} .elementor-product:hover .product_thumbnail:hover img, {{WRAPPER}} .elementor-product-item__overlay a img:hover',
                    ]
          );
          $this->end_controls_tab();

          $this->end_controls_tabs();

          $this->end_controls_section();

          $this->start_controls_section(
               'section_product_cat',
               [
                    'label' => __( 'Category',RPMW_TEXTDOMAIN ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                         'show_category' => 'yes',
                    ],

               ]
          );
          $this->add_control(
               'product_category_color', [
                    'label' => esc_html__('Color',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                         '{{WRAPPER}} .related-product-category a' => 'color: {{VALUE}};',
                    ],
               ]
          );
          $this->add_control(
               'product_category_hover_color', [
                    'label' => esc_html__('Hover Color',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                         '{{WRAPPER}} .related-product-category a:hover' => 'color: {{VALUE}};',
                    ],
               ]
          );
          $this->add_group_control(
               Group_Control_Typography::get_type(),
               [
                    'name' => 'product_category_typography',
                    'global' => [
                         'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                    ],
                    'selector' => '{{WRAPPER}} .related-product-category',
                    
               ]
          );
          $this->add_responsive_control(
               'product_category_align',
               [
                    'label' => __( 'Text Align',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                         'left' => [
                              'title' => __( 'Left',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-left',
                         ],
                         'center' => [
                              'title' => __( 'Center',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-center',
                         ],
                         'right' => [
                              'title' => __( 'Right',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-right',
                         ],
                    ],
                    'selectors' => [
                         '{{WRAPPER}} .related-product-category' => 'text-align: {{VALUE}};',
                    ],
               ]
          );
          $this->add_responsive_control(
               'product_category_spacing',
               [
                    'label' => __( 'Spacing',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                         '{{WRAPPER}} .related-product-category' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    ],
                    'separator' => 'after',
               ]
          );
          $this->end_controls_section();

          $this->start_controls_section(
               'section_product_tags',
               [
                    'label' => __( 'Tag',RPMW_TEXTDOMAIN ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                         'show_tag' => 'yes',
                    ],
               ]
          );
          $this->add_control(
               'product_tag_color', [
                    'label' => esc_html__('Color',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                         '{{WRAPPER}} .related-product-tag a' => 'color: {{VALUE}};',
                    ],
               ]
          );
          $this->add_control(
               'product_tag_hover_color', [
                    'label' => esc_html__('Hover Color',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                         '{{WRAPPER}} .related-product-tag a:hover' => 'color: {{VALUE}};',
                    ],
               ]
          );
          $this->add_group_control(
               Group_Control_Typography::get_type(),
               [
                    'name' => 'product_tag_typography',
                    'global' => [
                         'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                    ],
                    'selector' => '{{WRAPPER}} .related-product-tag',
                    
               ]
          );
          $this->add_responsive_control(
               'product_tag_align',
               [
                    'label' => __( 'Text Align',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                         'left' => [
                              'title' => __( 'Left',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-left',
                         ],
                         'center' => [
                              'title' => __( 'Center',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-center',
                         ],
                         'right' => [
                              'title' => __( 'Right',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-right',
                         ],
                    ],
                    'selectors' => [
                         '{{WRAPPER}} .related-product-tag' => 'text-align: {{VALUE}};',
                    ],
               ]
          );
          $this->add_responsive_control(
               'product_tag_spacing',
               [
                    'label' => __( 'Spacing',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                         '{{WRAPPER}} .related-product-tag' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    ],
                    'separator' => 'after',
               ]
          );
          $this->end_controls_section();

          $this->start_controls_section(
               'section_design_sale', [
                    'label' => esc_html__('Sale Badge',RPMW_TEXTDOMAIN),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                         'show_sale_badge' => 'yes',
                    ],
                         ]
          );
          $this->add_control(
               'sale_title_color',
               [
                    'label' => __( 'Color',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::COLOR,
                    'global' => [
                         'default' => Global_Colors::COLOR_PRIMARY,
                    ],
                    'selectors' => [
                         '{{WRAPPER}} .onsale' => 'color: {{VALUE}};',
                    ],
               ]
          );
          $this->add_control(
               'section_sale_bg_color', [
                    'label' => esc_html__('Background Color',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                         '{{WRAPPER}} .onsale' => 'background-color: {{VALUE}};',
                    ],          
               ]
          );
          $this->add_control(
               'sale_border_radius', [
                    'label' => esc_html__('Border Radius',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                         '{{WRAPPER}} .onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ], 
               ]
          );
          $this->add_group_control(
               Group_Control_Typography::get_type(),
               [
                    'name' => 'product_sale_typography',
                    'global' => [
                         'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                    ],
                    'selector' => '{{WRAPPER}} .onsale',
                    
               ]
          );
          $this->end_controls_section();

          $this->start_controls_section(
               'section_design_content', [
                    'label' => esc_html__('Content',RPMW_TEXTDOMAIN),
                    'tab' => Controls_Manager::TAB_STYLE,
               ]
          );
          $this->add_control(
               'product_title_style', [
                    'label' => esc_html__('Product Title',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::HEADING,
               ]
          );
          $this->add_control(
               'product_title_color', [
                    'label' => esc_html__('Color',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                         '{{WRAPPER}} .related_product_title' => 'color: {{VALUE}};',
                    ],
               ]
          );
          $this->add_control(
               'product_title_hover_color', [
                    'label' => esc_html__('Hover Color',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                         '{{WRAPPER}} .related_product_title:hover' => 'color: {{VALUE}};',
                    ],
               ]
          );
          $this->add_group_control(
               Group_Control_Typography::get_type(),
               [
                    'name' => 'product_title_typography',
                    'global' => [
                         'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                    ],
                    'selector' => '{{WRAPPER}} .related_product_title',
                    
               ]
          );
          $this->add_responsive_control(
               'product_title_align',
               [
                    'label' => __( 'Text Align',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                         'left' => [
                              'title' => __( 'Left',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-left',
                         ],
                         'center' => [
                              'title' => __( 'Center',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-center',
                         ],
                         'right' => [
                              'title' => __( 'Right',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-right',
                         ],
                    ],
                    'selectors' => [
                         '{{WRAPPER}} .related_product_title' => 'text-align: {{VALUE}};',
                    ],
               ]
          );
          $this->add_responsive_control(
               'product_title_spacing',
               [
                    'label' => __( 'Spacing',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                         '{{WRAPPER}} .related_product_title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    ],
                    'separator' => 'after',
               ]
          );
          $this->add_control(
               'product_rating_style', [
                    'label' => esc_html__('Rating Star',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::HEADING,
               ]
          );
          $this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size',RPMW_TEXTDOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);
          $this->add_control(
			'stars_color',
			[
				'label' => __( 'Color',RPMW_TEXTDOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .star-rating :before' => 'color: {{VALUE}}',
				],
				
			]
		);
          $this->add_control(
			'stars_unmarked_color',
			[
				'label' => __( 'Unmarked Color',RPMW_TEXTDOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .star-rating:before' => 'color: {{VALUE}}',
				],
			]
		);
          $this->add_responsive_control(
               'Alignment',
               [
                    'label' => __( 'Text Align',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                         'end' => [
                              'title' => __( 'Left',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-left',
                         ],
                         'center' => [
                              'title' => __( 'Center',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-center',
                         ],
                         'flex-end' => [
                              'title' => __( 'Right',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-right',
                         ],
                    ],
                    'selectors' => [
                         '{{WRAPPER}} .related_product_star_rating' => 'justify-content: {{VALUE}};',
                    ],
               ]
          );
          $this->add_responsive_control(
               'icon_bottom',
               [
                    'label' => __( 'Spacing Bottom',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                         '{{WRAPPER}} .star-rating' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    ],
                    'separator' => 'after',
               ]
          );
          $this->add_control(
               'product_price_style', [
                    'label' => esc_html__('Product Price',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::HEADING,
               ]
          );
          $this->add_control(
               'product_price_color', [
                    'label' => esc_html__('Color',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                         '{{WRAPPER}} .related-price' => 'color: {{VALUE}};',
                    ],
               ]
          );
          $this->add_group_control(
               Group_Control_Typography::get_type(),
               [
                    'name' => 'product_price_typography',
                    'global' => [
                         'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                    ],
                    'selector' => '{{WRAPPER}} .related-price',
                    
               ]
          );
          $this->add_responsive_control(
               'product_price_align',
               [
                    'label' => __( 'Text Align',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                         'left' => [
                              'title' => __( 'Left',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-left',
                         ],
                         'center' => [
                              'title' => __( 'Center',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-center',
                         ],
                         'right' => [
                              'title' => __( 'Right',RPMW_TEXTDOMAIN ),
                              'icon' => 'eicon-text-align-right',
                         ],
                    ],
                    'selectors' => [
                         '{{WRAPPER}} .related-price' => 'text-align: {{VALUE}};',
                    ],
               ]
          );
          $this->add_responsive_control(
               'product_price_spacing',
               [
                    'label' => __( 'Spacing',RPMW_TEXTDOMAIN ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em' ],
                    'selectors' => [
                         '{{WRAPPER}} .related-price' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    ],
               ]
          );
          $this->end_controls_section();

          $this->start_controls_section(
               'section_style', [
                    'label' => __('Button',RPMW_TEXTDOMAIN),
                    'tab' => Controls_Manager::TAB_STYLE,
               ]
          );
          $this->add_responsive_control(
               'button_text_align', [
                    'label' => __('Alignment',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                         'left' => [
                         'title' => __('Left',RPMW_TEXTDOMAIN),
                         'icon' => 'fa fa-align-left',
                         ],
                         'center' => [
                         'title' => __('Center',RPMW_TEXTDOMAIN),
                         'icon' => 'fa fa-align-center',
                         ],
                         'right' => [
                         'title' => __('Right',RPMW_TEXTDOMAIN),
                         'icon' => 'fa fa-align-right',
                         ],
                    ],
                    'selectors' => [
                         '{{WRAPPER}} .view-btn' => 'text-align: {{VALUE}};',
                    ],
               ]
          );
          $this->add_group_control(
               Group_Control_Border::get_type(), [
                    'name' => 'btn_border',
                    'selector' => '{{WRAPPER}} .view-btn a',
               ]
          );
          $this->add_control(
               'product_btn_border_radius', [
                    'label' => __('Border Radius',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                         '{{WRAPPER}} .view-btn a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
               ]
          );
          $this->add_group_control(
               Group_Control_Typography::get_type(), [
                    'name' => 'typography',
                    'selector' => '{{WRAPPER}} .view-btn a',
               ]
          );
          $this->start_controls_tabs('tabs_button_style');

          $this->start_controls_tab(
               'tab_button_normal', [
                    'label' => __('Normal',RPMW_TEXTDOMAIN),
               ]
          );
          $this->add_control(
               'cart_button_text_color', [
                    'label' => __('Text Color',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                         '{{WRAPPER}} .view-btn a' => 'color: {{VALUE}};',
                    ],
               ]
          );
          $this->add_control(
               'background_color', [
                    'label' => __('Background Color',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                         '{{WRAPPER}} .view-btn a' => 'background-color: {{VALUE}};',
                    ],
               ]
          );
          $this->end_controls_tab();

          $this->start_controls_tab(
               'tab_button_hover', [
                    'label' => __('Hover',RPMW_TEXTDOMAIN),
               ]
          );
          $this->add_control(
               'cart_text_hover_color', [
                    'label' => __('Text Color',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                         '{{WRAPPER}} .view-btn a:hover' => 'color: {{VALUE}};',
                    ],
               ]
          );
          $this->add_control(
               'cart_button_background_hover_color', [
                    'label' => __('Background Color',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                         '{{WRAPPER}} .view-btn a:hover' => 'background-color: {{VALUE}};',
                    ],
               ]
          );
          $this->add_control(
               'cart_button_hover_border_color', [
                    'label' => __('Border Color',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                         '{{WRAPPER}} .view-btn a:hover' => 'border-color: {{VALUE}};',
                    ],
                    
               ]
          );
          $this->end_controls_tabs();

          $this->add_group_control(
               Group_Control_Box_Shadow::get_type(), [
                    'name' => 'box_shadow',
                    'selector' => '{{WRAPPER}} .view-btn a',
                    ]
          );
          $this->add_responsive_control(
               'text_padding', [
                    'label' => __('Padding',RPMW_TEXTDOMAIN),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                         '{{WRAPPER}} .view-btn a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
               ]
          );
          $this->end_controls_section();

     }
	
     /**
     * Render Related Product widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @access protected
     */
     protected function render() {
     
          $settings = $this->get_settings_for_display();
          global $product;
          $args = array(
               'post_type' => 'product',
               'posts_per_page' => 3,
               'post_status' => 'publish',
               'columns' => 3,
               'orderby' => $settings['orderby'],
			'order' => $settings['order'],
               'taxonomy'     => 'product_cat', 
          );?>
          <?php switch ($settings['orderby']){
               case 'price':
                    $args = array(
                         'post_type'      => 'product',
                         'orderby'        => 'meta_value_num',
                         'order'          => $settings['order'],
                         'meta_key'       => '_price'
                    );
               break;

               case 'price-desc':
                    $args = array(
                         'post_type'      => 'product',
                         'orderby'        => 'meta_value_num',
                         'order'          => $settings['order'],
                         'meta_key'       => '_price'
                    );
               break;
               
               case 'rating':
                    $args = array(
                         'post_type'      => 'product',
                         'orderby'        => 'meta_value_num',
                         'order'          => $settings['order'],
                         'meta_key'       => '_wc_average_rating'
                    );
               break;
               
               case 'modified':
                    $args = array(
                         'post_type'      => 'product',
                         'orderby'        => 'meta_value_num',
                         'order'          => $settings['order'],
                         'meta_key'       => '_modified'
                    );
               break;  

               case 'date':
                    $args = array(
                         'post_type'      => 'product',
                         'order'          => $settings['order'],
                    );
               break;
          }
          // Related Product post per page
          if ( ! empty( $settings['posts_per_page'] ) ) {
               $args['posts_per_page'] = $settings['posts_per_page'];
          }
          // Related Product column 
          if ( ! empty( $settings['columns'] ) ) {
               $args['columns'] = $settings['columns'];

          }
          // Related Product Exclude category
          if (isset($settings['exclude_product_categories']) && !empty($settings['exclude_product_categories'])) {
               $post_cats = 'NOT IN';
          } else {
               $post_cats = 'IN';
          }
          // Query Check all Display Category 
          if (isset($settings['product_categories']) && !empty($settings['product_categories'])) {
          // Display All Product with Category 
               $args['tax_query'][] = array(
               array(
                    'taxonomy' => 'product_cat',
                    'field' => 'ID',
                    'terms' => $settings['product_categories'],
                    'operator' => $post_cats,
               ),
               );
          } 
          $the_query = new \WP_Query($args);
          
               if ( $the_query->have_posts() ) { 
                    if (isset($settings['show_heading']) && $settings['show_heading'] == 'yes') {
                         $tag = $settings['title_tag'];?>
                         <<?php echo $tag; ?> class="elementor-product-heading-wrapper">
                         <?php echo esc_html($settings['related_product_heading']); ?>
                         </<?php echo $tag; ?>>
                    <?php } ?>
                    <div class="related-products grid-container product-grid-gap elementor-grid product_border_radius related-products_contanair-<?php echo esc_html($settings['columns']);?>" >
                         <?php while ($the_query->have_posts()) : $the_query->the_post(); global $product; global $post; ?>
                              <div class="related-products_contanair product-container"> 
                                   <div class="related-products_img product_thumbnail elementor-product-item__overlay "> <?php   
                                        if (isset($settings['show_sale_badge']) && $settings['show_sale_badge'] == 'yes') {?>
                                             <div class="related-product-sale-price">
                                             <?php
                                             if( $product->is_on_sale() ) {?>
                                                  <span class="onsale"><?php
                                                  echo esc_html($settings['related_product_sale_icon_title']);      
                                                  }?></span>
                                             </div>
                                        <?php } ?>                                      
                                             <?php if ( has_post_thumbnail() ) {?> 
                                             <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail(); ?></a>
                                             <?php } ?>
                                   </div>                             
                                   <div class="related-products_contant" ><?php
                                        if (isset($settings['show_category']) && $settings['show_category'] == 'yes') {?>
                                             <div class="related-product-category">
                                                  <?php echo wc_get_product_category_list( $post->ID );?>
                                             </div> <?php
                                        } 
                                        if (isset($settings['show_tag']) && $settings['show_tag'] == 'yes') {?>  
                                             <div class="related-product-tag">
                                                  <?php echo wc_get_product_tag_list( $post->ID );?>
                                             </div>
                                             <?php
                                        } ?>
                                        <h4 class="related_product_title"><?php the_title(); ?></h4>
                                        <div class="related_product_star_rating">
                                             <?php if ($average = $product->get_average_rating()) : ?>
                                             <?php echo '<div class="star-rating " title="'.sprintf(__( 'Rated %s out of 5', 'woocommerce' ), $average).'">
                                             <span style="width:'.( ( $average / 5 ) * 100 ) . '%">'.$average.__( 'out of 5', 'woocommerce' ).'</span></div>'; ?>
                                             <?php endif; ?>  
                                             </div>
                                        <div class="related-price">
                                             <?php echo $product->get_price_html(); ?>
                                        </div>
                                        <div class="view-btn">
                                             <a class="button" href="<?php the_permalink(); ?>"><?php
                                             echo esc_html($settings['button_text']);?></a>
                                        </div>
                                   </div>
                              </div>
                              <?php
                         endwhile;
                         wp_reset_postdata();
               }
               echo '</div>';       
     }

}

Plugin::instance()->widgets_manager->register_widget_type(new Related_Product_Manager());
