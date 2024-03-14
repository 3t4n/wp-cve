<?php

/**
 * Class: LaStudioKit_Post_Terms
 * Name: Post Terms
 * Slug: lakit-post-terms
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}


/**
 * Post Terms Widget
 */
class LaStudioKit_Post_Terms extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_style_depends( 'lastudio-kit-base' );
	    }
    }

    public function get_name() {
        return 'lakit-post-terms';
    }

    protected function get_widget_title() {
        return esc_html__( 'Post Terms', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-sitemap';
    }

    public function get_categories() {
        return [ 'lastudiokit-builder' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Post Terms', 'lastudio-kit' ),
            ]
        );

        $this->add_control(
            'taxonomy',
            [
                'label' => __( 'Taxonomy', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'options' => get_taxonomies( array( 'public' => true ) ),
                'default' => 'category',
            ]
        );

        $this->add_control(
            'separator',
            [
                'label' => __( 'Separator', 'lastudio-kit' ),
                'type' => Controls_Manager::TEXT,
                'default' => ', ',
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label' => __( 'HTML Tag', 'lastudio-kit' ),
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
                'label' => __( 'Alignment', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', 'lastudio-kit' ),
                        'icon' => 'eicon-text-align-justify',
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
                'label' => __( 'Link to', 'lastudio-kit' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __( 'None', 'lastudio-kit' ),
                    'term' => __( 'Term', 'lastudio-kit' ),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Post Terms', 'lastudio-kit' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .lakit-post-terms',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .lakit-post-terms',
            ]
        );

        $this->_add_responsive_control(
            'term_margin',
            [
                'label' => __( 'Item Margin', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-post-terms .term-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_add_responsive_control(
            'term_padding',
            [
                'label' => __( 'Item Padding', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-post-terms .term-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->_start_controls_tabs( 'term_item_style' );

        $this->_start_controls_tab(
            'term_item_normal',
            [
                'label' => __( 'Normal', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'term_color',
            [
                'label' => __( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lakit-post-terms .term-item,{{WRAPPER}} .lakit-post-terms' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_control(
            'term_bgcolor',
            [
                'label' => __( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lakit-post-terms .term-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'term_item_hover',
            [
                'label' => __( 'Hover', 'lastudio-kit' ),
            ]
        );

        $this->_add_control(
            'term_hover_color',
            [
                'label' => __( 'Text Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .lakit-post-terms .term-item:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_control(
            'term_hover_bgcolor',
            [
                'label' => __( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .lakit-post-terms .term-item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->_add_control(
            'term_hover_border',
            [
                'label' => __( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'term_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-post-terms .term-item:hover',
                ],
            ]
        );

        $this->_add_control(
            'hover_animation',
            [
                'label' => __( 'Hover Animation', 'lastudio-kit' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'term_border',
                'selector' => '{{WRAPPER}} .lakit-post-terms .term-item',
                'separator' => 'before',
            ]
        );

        $this->_add_responsive_control(
            'term_border_radius',
            [
                'label' => __( 'Border Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-post-terms .term-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        global $post;
        $settings = $this->get_settings();

        $taxonomy = $settings['taxonomy'];
        if ( empty( $taxonomy ) )
            return;

        $term_list = get_the_terms( $post->ID, $taxonomy );
        if ( empty( $term_list ) || is_wp_error( $term_list ) )
            return;

        $animation_class = ! empty( $settings['hover_animation'] ) ? 'elementor-animation-' . $settings['hover_animation'] : '';

        $html = sprintf( '<%1$s class="lakit-post-terms %2$s">', $settings['html_tag'], $animation_class );

        $arr = [];

        switch ( $settings['link_to'] ) {
            case 'term' :
                foreach ( $term_list as $term ) {
                    $arr[] = sprintf( '<a class="term-item" href="%1$s">%2$s</a>', esc_url( get_term_link( $term ) ), $term->name );
                }
                break;

            case 'none' :
            default:
                foreach ( $term_list as $term ) {
                    $arr[] = sprintf('<span class="term-item">%1$s</span>', $term->name);
                }
                break;
        }
        $html .= join($settings['separator'], $arr);
        $html .= sprintf( '</%1$s>', $settings['html_tag'] );

        echo $html;
    }
    
}