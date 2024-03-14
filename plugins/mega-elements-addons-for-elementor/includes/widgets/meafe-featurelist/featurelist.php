<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;

class MEAFE_Featurelist extends Widget_Base
{

    public function get_name() {
        return 'meafe-featurelist';
    }

    public function get_title() {
        return esc_html__( 'Feature Lists', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-feature-list';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-featurelist'];
    }

    protected function register_controls() 
    {
        /**
         * Feature List General Settings
         */
        $this->start_controls_section(
            'meafe_feature_list_content_general_settings',
            [
                'label' => esc_html__( 'General Settings', 'mega-elements-addons-for-elementor' )
            ]
        );

        $fl_repeater = new Repeater();

        $fl_repeater->add_control(
            'bflcgs_feature_list_icon_type',
            [
                'label'       => esc_html__( 'Icon Type', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => [
                    'icon'  => [
                        'title' => esc_html__( 'Icon', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-star',
                    ],
                    'image' => [
                        'title' => esc_html__( 'Image', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-picture-o',
                    ],
                ],
                'default'     => 'icon',
                'label_block' => false,
            ]
        );

        $fl_repeater->add_control(
            'bflcgs_feature_list_icon_new',
            [
                'label'         => esc_html__( 'Icon', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::ICONS,
                'fa4compatibility' => 'bflcgs_feature_list_icon',
                'condition'     => [
                    'bflcgs_feature_list_icon_type' => 'icon'
                ]
            ]
        );
        
        $fl_repeater->add_control(
            'bflcgs_feature_list_img',
            [
                'label'     => esc_html__( 'Image', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'bflcgs_feature_list_icon_type' => 'image'
                ]
            ]
        );

        $fl_repeater->add_control(
            'bflcgs_feature_list_title',
            [
                'label'     => esc_html__( 'Title', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Title', 'mega-elements-addons-for-elementor' ),
                'dynamic'   => [ 'active' => true ]
            ]
        );

        $fl_repeater->add_control(
            'bflcgs_feature_list_content',
            [
                'label'     => esc_html__( 'Content', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXTAREA,
                'default'   => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'mega-elements-addons-for-elementor' ),
                'dynamic'   => [ 'active' => true ]
            ]
        );

        $fl_repeater->add_control(
            'bflcgs_feature_list_link',
            [
                'label'       => esc_html__( 'Link', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [ 'active' => true ],
                'placeholder' => esc_html__( 'https://', 'mega-elements-addons-for-elementor' ),
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'bflcgs_feature_list',
            array(
                'label'       => esc_html__( 'Feature Item', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::REPEATER,
                'seperator'   => 'before',
                'default'     => array(
                    array(
                        'bflcgs_feature_list_icon_new'    => array(
                            'value'     => 'fas fa-check',
                            'library'   => 'fa-solid'
                        ),
                        'bflcgs_feature_list_title'   => esc_html__( 'Feature Item 1', 'mega-elements-addons-for-elementor' ),
                        'bflcgs_feature_list_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisi cing elit, sed do eiusmod tempor incididunt ut abore et dolore magna', 'mega-elements-addons-for-elementor' )
                    ),
                    array(
                        'bflcgs_feature_list_icon_new'    => array(
                            'value'     => 'fas fa-check',
                            'library'   => 'fa-solid'
                        ),
                        'bflcgs_feature_list_title'   => esc_html__( 'Feature Item 2', 'mega-elements-addons-for-elementor' ),
                        'bflcgs_feature_list_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisi cing elit, sed do eiusmod tempor incididunt ut abore et dolore magna', 'mega-elements-addons-for-elementor' )
                    ),
                    array(
                        'bflcgs_feature_list_icon_new'    => array(
                            'value'     => 'fas fa-check',
                            'library'   => 'fa-solid'
                        ),
                        'bflcgs_feature_list_title'   => esc_html__( 'Feature Item 3', 'mega-elements-addons-for-elementor' ),
                        'bflcgs_feature_list_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisi cing elit, sed do eiusmod tempor incididunt ut abore et dolore magna', 'mega-elements-addons-for-elementor' )
                    )
                ),
                'fields'      => $fl_repeater->get_controls(),
                'title_field' => '<i class="{{ bflcgs_feature_list_icon_new.value }}" aria-hidden="true"></i> {{{ bflcgs_feature_list_title }}}',
            )
        );

        $this->add_control(
            'bflcgs_feature_list_title_size',
            [
                'label'     => esc_html__( 'Title HTML Tag', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'h1'    => esc_html__( 'H1', 'mega-elements-addons-for-elementor' ),
                    'h2'    => esc_html__( 'H2', 'mega-elements-addons-for-elementor' ),
                    'h3'    => esc_html__( 'H3', 'mega-elements-addons-for-elementor' ),
                    'h4'    => esc_html__( 'H4', 'mega-elements-addons-for-elementor' ),
                    'h5'    => esc_html__( 'H5', 'mega-elements-addons-for-elementor' ),
                    'h6'    => esc_html__( 'H6', 'mega-elements-addons-for-elementor' ),
                    'span'  => esc_html__( 'Span', 'mega-elements-addons-for-elementor' ),
                    'p'     => esc_html__( 'P', 'mega-elements-addons-for-elementor' ),
                    'div'   => esc_html__( 'Div', 'mega-elements-addons-for-elementor' ),
                ],
                'default'   => 'h3',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'bflcgs_feature_list_icon_shape',
            [
                'label'       => esc_html__( 'Icon Shape', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'circle',
                'label_block' => false,
                'options'     => [
                    'circle'  => esc_html__( 'Circle', 'mega-elements-addons-for-elementor' ),
                    'square'  => esc_html__( 'Square', 'mega-elements-addons-for-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'bflcgs_feature_list_icon_shape_view',
            [
                'label'       => esc_html__( 'Shape View', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'solid',
                'label_block' => false,
                'options'     => [
                    'solid'         => esc_html__( 'Solid', 'mega-elements-addons-for-elementor' ),
                    'background'    => esc_html__( 'Background', 'mega-elements-addons-for-elementor' ),
                    'dotted'        => esc_html__( 'Dotted', 'mega-elements-addons-for-elementor' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'bflcgs_feature_list_icon_position',
            [
                'label'           => esc_html__( 'Icon Position', 'mega-elements-addons-for-elementor' ),
                'type'            => Controls_Manager::CHOOSE,
                'options'         => [
                    'left'  => [
                        'title' => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'top'   => [
                        'title' => esc_html__( 'Top', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default'         => 'left',
                'devices'         => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => 'left',
                'tablet_default'  => 'left',
                'mobile_default'  => 'left',
                'prefix_class'    => 'meafe%s-icon-position-',
                'toggle'          => false,
            ]
        );

        $this->end_controls_section();

        // /**
        //  * -------------------------------------------
        //  * Feature List General Style
        //  * -------------------------------------------
        //  */

        $this->start_controls_section(
            'meafe_feature_list_style_general_style',
            [
                'label'     => esc_html__( 'General Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'bflsgs_feature_list_text_align',
            [
                'label'     => __( 'Alignment', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'      => [
                        'title' => __( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center'    => [
                        'title' => __( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'     => [
                        'title' => __( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                    'justify'   => [
                        'title' => __( 'Justified', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-justify',
                    ],
                ],
                'condition' => [
                    'bflcgs_feature_list_icon_position' => 'top',
                ],
                'prefix_class' => 'meafe-featurelist-text-position-',
            ]
        );

        $this->add_responsive_control(
            'bflsgs_feature_list_space_between',
            [
                'label'     => esc_html__( 'Space Between', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 15,
                ],
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-feature-list-items .meafe-feature-list-item:not(:last-child)'                              => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .meafe-feature-list-items .meafe-feature-list-item:not(:first-child)'                             => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Feature List Icon Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'meafe_feature_list_style_icon_style',
            [
                'label'     => esc_html__( 'Icon Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background:: get_type(),
            [
                'name'    => 'bflsis_feature_list_icon_background',
                'types'   => [ 'classic', 'gradient' ],
                'exclude' => [
                    'image',
                ],
                'color' => [
                    'default' => '#3858f4',
                ],
                'selector' => '{{WRAPPER}} .meafe-feature-list-items .meafe-feature-list-icon-box .meafe-feature-list-icon-inner',
            ]
        );

        $this->add_control(
            'bflsis_feature_list_secondary_color',
            [
                'label'     => esc_html__( 'Secondary Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .meafe-feature-list-items.background .meafe-feature-list-icon'  => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'bflcgs_feature_list_icon_shape_view' => 'background',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bflsis_feature_list_icon_color',
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-feature-list-items .meafe-feature-list-icon' => 'color: {{VALUE}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'bflsis_feature_list_icon_circle_size',
            [
                'label'     => esc_html__( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 70,
                ],
                'range'     => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-feature-list-icon-box .meafe-feature-list-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}'
                ],
            ]
        );

        $this->add_responsive_control(
            'bflsis_feature_list_icon_size',
            [
                'label'     => esc_html__( 'Icon Size', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 21,
                ],
                'range'     => [
                    'px' => [
                        'min' => 6,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-feature-list-icon-box .meafe-feature-list-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .meafe-feature-list-icon-box .meafe-feature-list-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .meafe-feature-list-img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bflsis_feature_list_icon_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-feature-list-icon-box .meafe-feature-list-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [

                'name'      => 'bflcis_feature_list_border_width',
                'label'     => esc_html__( 'Border Width', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 1,
                ],
                'range'     => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-feature-list-icon-box .meafe-feature-list-icon-inner' => 'border-width: {{SIZE}}{{UNIT}}',

                ],
                'condition' => [
                    'bflcgs_feature_list_icon_shape_view' => 'background',
                ],
            ]
        );

        $this->add_control(
            'bflsis_feature_list_icon_border_radius',
            [
                'label'         => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-feature-list-icon-box .meafe-feature-list-icon-inner .meafe-feature-list-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'condition' => [
                    'bflcgs_feature_list_icon_shape_view' => 'background',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'bflsis_feature_list_icon_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe-feature-list-icon-inner',
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Feature List Content Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'meafe_feature_list_style_content_style',
            [
                'label'     => esc_html__( 'Content Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bflscs_feature_list_heading_title',
            [
                'label' => esc_html__( 'Title', 'mega-elements-addons-for-elementor' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'bflscs_feature_list_title_bottom_space',
            [
                'label'     => esc_html__( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .meafe-feature-list-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'bflscs_feature_list_title_color',
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#414247',
                'selectors' => [
                    '{{WRAPPER}} .meafe-feature-list-content-box .meafe-feature-list-title, {{WRAPPER}} .meafe-feature-list-content-box .meafe-feature-list-title > a, {{WRAPPER}} .meafe-feature-list-content-box .meafe-feature-list-title:visited' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'bflscs_feature_list_title_typography',
                'selector' => '{{WRAPPER}} .meafe-feature-list-content-box .meafe-feature-list-title',
            ]
        );

        $this->add_control(
            'bflscs_feature_list_description',
            [
                'label'     => esc_html__( 'Description', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bflscs_feature_list_description_color',
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-feature-list-content-box .meafe-feature-list-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'bflscs_feature_list_description_typography',
                'selector' => '{{WRAPPER}} .meafe-feature-list-content-box .meafe-feature-list-content',
                'fields_options' => [
                    'font_size' => [ 'default' => [ 'unit' => 'px', 'size' => 14 ] ]
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'bflcgs_feature_list', [
            'id'    => 'meafe-feature-list-' . esc_attr( $this->get_id() ),
            'class' => [
                'meafe-feature-list-items',
                $settings['bflcgs_feature_list_icon_shape'],
                $settings['bflcgs_feature_list_icon_shape_view'],
            ]
        ] );

        $this->add_render_attribute( 'bflcgs_feature_list_item', 'class', 'meafe-feature-list-item' );
        
        if( isset($settings['bflsis_feature_list_icon_border_width']['right']) &&  isset($settings['bflsis_feature_list_icon_border_width']['left']) ) {
            $border  = $settings['bflsis_feature_list_icon_border_width']['right'] + $settings['bflsis_feature_list_icon_border_width']['left'];
        }

        ?>

        <ul <?php echo $this->get_render_attribute_string( 'bflcgs_feature_list' ); ?>>
            <?php $i = 0;
            foreach ( $settings['bflcgs_feature_list'] as $index => $item ) :

                $this->add_render_attribute( 'bflcgs_feature_list_icon'.$i, 'class', 'meafe-feature-list-icon' );
                $this->add_render_attribute( 'bflcgs_feature_list_title'.$i, 'class', 'meafe-feature-list-title' );
                $this->add_render_attribute( 'bflcgs_feature_list_content'.$i, 'class', 'meafe-feature-list-content' );

                if( $item['bflcgs_feature_list_link']['url'] ) {
                    $this->add_render_attribute( 'bflcgs_feature_list_title_anchor'.$i, 'href', esc_url( $item['bflcgs_feature_list_link']['url'] ) );

                    if ( $item['bflcgs_feature_list_link']['is_external'] ) {
                        $this->add_render_attribute( 'bflcgs_feature_list_title_anchor'.$i, 'target', '_blank' );
                    }

                    if ( $item['bflcgs_feature_list_link']['nofollow'] ) {
                        $this->add_render_attribute( 'bflcgs_feature_list_title_anchor'.$i, 'rel', 'nofollow' );
                    }
                }

                $feature_icon_tag = 'span';

                $feature_has_icon = ( !empty( $item['bflcgs_feature_list_icon'] ) || !empty( $item['bflcgs_feature_list_icon_new'] ) );

                if ( $item['bflcgs_feature_list_link']['url'] ) {
                    $this->add_render_attribute( 'bflcgs_feature_list_link'.$i, 'href', $item['bflcgs_feature_list_link']['url'] );

                    if ( $item['bflcgs_feature_list_link']['is_external'] ) {
                        $this->add_render_attribute( 'bflcgs_feature_list_link'.$i, 'target', '_blank' );
                    }

                    if ( $item['bflcgs_feature_list_link']['nofollow'] ) {
                        $this->add_render_attribute( 'bflcgs_feature_list_link'.$i, 'rel', 'nofollow' );
                    }
                    $feature_icon_tag = 'a';
                }

                ?>
                <li class="meafe-feature-list-item">
                    <div class="meafe-feature-list-icon-box">
                        <div class="meafe-feature-list-icon-inner">

                            <<?php echo esc_attr($feature_icon_tag) .' '. $this->get_render_attribute_string( 'bflcgs_feature_list_icon'.$i ) . $this->get_render_attribute_string( 'bflcgs_feature_list_link'.$i ); ?>>

                                <?php 
                                    if ( $item['bflcgs_feature_list_icon_type'] == 'icon' && $feature_has_icon ) {

                                        if ( empty($item['bflcgs_feature_list_icon']) || isset($item['__fa4_migrated']['bflcgs_feature_list_icon_new']) ) {

                                            if( isset( $item['bflcgs_feature_list_icon_new']['value']['url'] ) ) {
                                                echo '<img src="' . esc_url( $item['bflcgs_feature_list_icon_new']['value']['url'] ) . '" alt="' . esc_attr( get_post_meta( $item['bflcgs_feature_list_icon_new']['value']['id'], '_wp_attachment_image_alt', true ) ) . '"/>';
                                            }
                                            else {
                                                echo '<i class="' . esc_attr( $item['bflcgs_feature_list_icon_new']['value'] ) . '" aria-hidden="true"></i>';
                                            }
                                            
                                        } else {
                                            echo '<i class="'.esc_attr( $item['bflcgs_feature_list_icon'] ).'" aria-hidden="true"></i>';
                                        }
                                    }
                                ?>

                                <?php if ( $item['bflcgs_feature_list_icon_type'] == 'image' ) {
                                    $this->add_render_attribute( 'bflcgs_feature_list_image'.$i, [
                                        'src'   => esc_url( $item['bflcgs_feature_list_img']['url'] ),
                                        'class' => 'meafe-feature-list-img',
                                        'alt'   => esc_attr( get_post_meta( $item['bflcgs_feature_list_img']['id'], '_wp_attachment_image_alt', true ) )
                                    ]);

                                    echo '<img '.$this->get_render_attribute_string( 'bflcgs_feature_list_image'.$i ).'>';
                                    
                                } ?>

                            </<?php echo esc_attr($feature_icon_tag); ?>>
                        </div>
                    </div>
                    
                    <div class="meafe-feature-list-content-box">
                        <<?php echo implode( ' ', [
                            Utils::validate_html_tag($settings['bflcgs_feature_list_title_size']),
                            $this->get_render_attribute_string( 'bflcgs_feature_list_title'.$i )
                        ] ); ?>
                        >
                            <?php echo !empty($item['bflcgs_feature_list_link']['url']) ? "<a {$this->get_render_attribute_string('bflcgs_feature_list_title_anchor'.$i)}>": ''; ?>
                                <?php echo esc_html($item['bflcgs_feature_list_title']); ?>
                            <?php echo !empty($item['bflcgs_feature_list_link']['url']) ? "</a>": ''; ?>
                        </<?php Utils::print_validated_html_tag($settings['bflcgs_feature_list_title_size']); ?>
                    >
                    <p <?php echo $this->get_render_attribute_string( 'bflcgs_feature_list_content'.$i ); ?>><?php echo wp_kses_post($item['bflcgs_feature_list_content']); ?></p>
                    </div>

                </li>
            <?php $i++; endforeach; ?>
        </ul>
        <?php
    }

}
