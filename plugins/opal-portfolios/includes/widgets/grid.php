<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use ElementorPro\Plugin;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * OSF_Portfolio_Walker
 *
 * extends Walker_Portfolio
 */
class PE_Portfolio_Widget_Grid extends Widget_Base {

    

    public function get_name() {
        return 'opal-portfolio-grid';
    }

    public function get_title() {
        return __('Opal Portfolio Grid', 'opalportfolios');
    }

    public function get_icon() {
        return 'eicon-image-box';
    }

    public function get_categories() {
        return ['opal-addons','general'];
    }

/**
     * Register category widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'section_query',
            [
                'label' => __('Query', 'opalportfolios'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'limit',
            [
                'label'   => __('Posts Per Page', 'opalportfolios'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'     => __('Columns', 'opalportfolios'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 3,
                'options'   => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 6 => 6],
            ]
        );

        $this->add_control(
            'advanced',
            [
                'label' => __('Advanced', 'opalportfolios'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => __('Order By', 'opalportfolios'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date'  => __('Date', 'opalportfolios'),
                    'title' => __('Title', 'opalportfolios'),
                    'rand'       => __('Random', 'opalportfolios'),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => __('Order', 'opalportfolios'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc'  => __('ASC', 'opalportfolios'),
                    'desc' => __('DESC', 'opalportfolios'),
                ],
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'opalportfolios'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'categories',
            [
                'label'    => __('Categories', 'opalportfolios'),
                'type'     => Controls_Manager::SELECT2,
                'options'  => $this->get_post_categories(),
                'multiple' => true,
            ]
        );

        $this->add_control(
            'style',
            [
                'label'     => __('Style Item Layout', 'opalportfolios'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default' => 'classic',
                'options'   =>  [
                    'classic'       => 'Classic',
                    'boxed'         => 'Boxed',
                    'list'          => 'List',
                ]
            ]
        );
        $this->add_control(
            'display_category',
            [
                'label'       => __('Show Category', 'opalportfolios'),
                'type'        => Controls_Manager::SWITCHER,
                'default'     => 'yes',
            ]
        );
        $this->add_control(
            'display_description',
            [
                'label'       => __('Show Description', 'opalportfolios'),
                'type'        => Controls_Manager::SWITCHER,
                'default'     => 'yes',
            ]
        );

        $this->add_control(
            'display_readmore',
            [
                'label'       => __('Show Readmore', 'opalportfolios'),
                'type'        => Controls_Manager::SWITCHER,
                'default'     => 'no',
                'condition' => [
                    'style' => 'list',
                ],
            ]
        );

        $this->add_control(
            'masonry',
            [
                'label'       => __('Masonry', 'opalportfolios'),
                'type'        => Controls_Manager::SWITCHER,
                'default'     => 'no',
            ]
        );
        $this->add_control(
            'display_pagination',
            [
                'label'       => __('Show Pagination', 'opalportfolios'),
                'type'        => Controls_Manager::SWITCHER,
                'default'     => 'no',
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => __( 'Alignment', 'opalportfolios' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'opalportfolios' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'opalportfolios' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'opalportfolios' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .grid-item, {{WRAPPER}} .entry-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section(); 

        $this->start_controls_section(
            'section_general_style',
            [
                'label'     => __('General', 'opalportfolios'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_item',
            [
                'label' => __( 'Item', 'opalportfolios' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'item_spacing',
            [
                'label'     => __('Space Between', 'opalportfolios'),
                'type'      => Controls_Manager::SLIDER,
                'separator' => 'before',
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-main-wrapper .grid-item' => 'padding: 0 calc( {{SIZE}}{{UNIT}}/2 );',
                    
                ],
            ]
        );

        $this->add_control(
            'heading_general',
            [
                'label' => __( 'Content', 'opalportfolios' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_background',
            [
                'label'     => __('Background', 'opalportfolios'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .work-details ' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'content_padding',
            [
                'label' => __( 'Padding', 'opalportfolios' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .work-details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section(); 

        $this->start_controls_section(
            'section_title_style',
            [
                'label'     => __('Title', 'opalportfolios'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .work-details h4 a',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'opalportfolios'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .work-details h4 a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'title_color_hover',
            [
                'label'     => __('Hover color', 'opalportfolios'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .work-details h4 a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_category_style',
            [
                'label'     => __('Category', 'opalportfolios'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_category' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'category_typography',
                'selector' => '{{WRAPPER}} .portfolio-categories a',
            ]
        );
        $this->add_control(
            'category_color',
            [
                'label'     => __('Color', 'opalportfolios'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio-categories a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'category_color_hover',
            [
                'label'     => __('Hover color', 'opalportfolios'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio-categories a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_description_style',
            [
                'label'     => __('Description', 'opalportfolios'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'description_typography',
                'selector' => '{{WRAPPER}} .work-details .work-description',
            ]
        );
        $this->add_control(
            'description_color',
            [
                'label'     => __('Color', 'opalportfolios'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .work-details .work-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_readmore_style',
            [
                'label'     => __('Read More', 'opalportfolios'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'readmore_typography',
                'selector' => '{{WRAPPER}} .work-details .work-readmore',
            ]
        );
        $this->add_control(
            'readmore_color',
            [
                'label'     => __('Color', 'opalportfolios'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .work-details .work-readmore' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'readmore_color_hover',
            [
                'label'     => __('Hover color', 'opalportfolios'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .work-details .work-readmore:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_pagination_style',
            [
                'label'     => __('Pagination', 'opalportfolios'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_pagination' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pagination_typography',
                'selector' => '{{WRAPPER}} .portfolio_navigation .page-numbers',
            ]
        );

        $this->add_control(
            'pagination_color_heading',
            [
                'label'     => __('Colors', 'opalportfolios'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('pagination_colors');

        $this->start_controls_tab(
            'pagination_color_normal',
            [
                'label' => __('Normal', 'opalportfolios'),
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label'     => __('Color', 'opalportfolios'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio_navigation .page-numbers' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'pagination_color_hover',
            [
                'label' => __('Hover', 'opalportfolios'),
            ]
        );

        $this->add_control(
            'pagination_hover_color',
            [
                'label'     => __('Color', 'opalportfolios'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio_navigation .page-numbers:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'pagination_color_active',
            [
                'label' => __('Active', 'opalportfolios'),
            ]
        );

        $this->add_control(
            'pagination_active_color',
            [
                'label'     => __('Color', 'opalportfolios'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio_navigation .page-numbers.current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'pagination_spacing',
            [
                'label'     => __('Space Between', 'opalportfolios'),
                'type'      => Controls_Manager::SLIDER,
                'separator' => 'before',
                'default'   => [
                    'size' => 10,
                ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .portfolio_navigation .page-numbers' => 'margin: 0 calc( {{SIZE}}{{UNIT}}/2 );',
                    
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function get_post_categories() {
        $categories = get_terms(array(
                'taxonomy'   => 'portfolio_cat',
                'hide_empty' => false,
            )
        );
        $results    = array();
        if (!is_wp_error($categories)) {
            foreach ($categories as $category) {
                $results[$category->slug] = $category->name;
            }
        }
        return $results;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('wrapper', 'class', 'elementor-post-wrapper');
        //$this->add_render_attribute('wrapper', 'class', $settings['style']);
        $this->add_render_attribute('row', 'class', 'row');
        if (!empty($settings['column'])) {
            $this->add_render_attribute('row', 'data-elementor-columns', $settings['column']);
        }

        if (!empty($settings['column_tablet'])) {
            $this->add_render_attribute('row', 'data-elementor-columns-tablet', $settings['column_tablet']);
        }
        if (!empty($settings['column_mobile'])) {
            $this->add_render_attribute('row', 'data-elementor-columns-mobile', $settings['column_mobile']);
        }

        if(!empty( $settings['categories'])){
            $categories = array();
            foreach($settings['categories'] as $category){
                $cat = get_term_by('slug', $category, 'portfolio_cat');
                if(!is_wp_error($cat) && is_object($cat)){
                    $categories[] = $cat->slug;
                }
            }
            
            $category = esc_attr( implode( ',', $categories ) ) ;
        } else {
            $category = '';
        }

        $limit              = $settings[ 'limit' ];
        $column             = $settings[ 'column'];
        $style              = $settings[ 'style'];
        $orderby            = $settings[ 'orderby'];
        $order              = $settings[ 'order'];
        $show_category      = $settings[ 'display_category'];
        $show_description   = $settings[ 'display_description'];
        $show_readmore      = $settings[ 'display_readmore'] ? $settings[ 'display_readmore'] : 'no' ;
        $masonry            = $settings[ 'masonry'];
        $pagination         = $settings[ 'display_pagination'];

        echo do_shortcode( '[portfolio_grid category="'.$category.'" limit="'.$limit.'" column="'.$column.'" style="'.$style.'" order="'.$order.'" orderby="'.$orderby.'" show_category="'.$show_category.'" show_description="'.$show_description.'" show_readmore="'.$show_readmore.'" masonry="'.$masonry.'" pagination="'.$pagination.'"]' ); 

        wp_reset_postdata();

    }
}