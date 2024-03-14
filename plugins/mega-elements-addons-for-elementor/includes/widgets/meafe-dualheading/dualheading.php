<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
class MEAFE_Dualheading extends Widget_Base
{

    public function get_name() {
        return 'meafe-dualheading';
    }

    public function get_title() {
        return esc_html__( 'Dual Heading', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-dualheading';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-dualheading'];
    }

    protected function register_controls() 
    {
        /**
         * Dual Heading General Settings
        */
        $this->start_controls_section( 
            'meafe_dualheading_content_general_settings', 
            [
                'label' => esc_html__( 'General Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'bdcgs_heading_title_tag',
            [
                'label'     => esc_html__( 'Select Tag', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'h2',
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
            ]
        );

        $this->add_control(
            'bdcgs_heading_one_text',
            [
                'label'     => esc_html__( 'Heading ( First Part )', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'label_block' => true,
                'default'   => esc_html__( 'Heading', 'mega-elements-addons-for-elementor' ),
                'dynamic'   => [ 'action' => true ]
            ]
        );

        $this->add_control(
            'bdcgs_heading_two_text',
            [
                'label'     => esc_html__( 'Heading ( Last Part )', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'label_block' => true,
                'default'   => esc_html__( 'Example', 'mega-elements-addons-for-elementor' ),
                'dynamic'   => [ 'action' => true ]
            ]
        );

        $this->add_responsive_control(
            'bdcgs_heading_content_alignment',
            [
                'label'     => esc_html__( 'Alignment', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options'   => [
                    'left'  => [
                        'title' => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'prefix_class' => 'meafe-dualheading-align-'
            ]
        );

        $this->end_controls_section();

        /**
         * Dual Heading General Style
         */
        $this->start_controls_section(
            'meafe_dualheading_style_general_style',
            [
                'label' => esc_html__( 'General Style', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'bdsgs_dualheading_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-dual-header' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bdsgs_dualheading_container_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-dual-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bdsgs_dualheading_container_margin',
            [
                'label'     => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-dual-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'bdsgs_dualheading_border',
                'label'     => esc_html__( 'Border', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-dual-header',
            ]
        );

        $this->add_responsive_control(
            'bdsgs_dualheading_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-dual-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'bdsgs_dualheading_shadow',
                'selector'  => '{{WRAPPER}} .meafe-dual-header',
            ]
        );

        $this->end_controls_section();

        /*
         * Dual Heading Color & Typography Style
         */
        $this->start_controls_section(
            'meafe_dualheading_style_color_and_typogrpahy',
            [
                'label'     => esc_html__( 'Color & Typography', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bdscat_dualheading_title_heading',
            [
                'label'     => esc_html__( 'Title Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'bdscat_dualheading_dual_title_color',
            [
                'label'     => esc_html__( 'Main Heading Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .meafe-dual-header .title span.main-header' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
            'name' => 'bdscat_dualheading_title_typography',
                'selector' => '{{WRAPPER}} .meafe-dual-header .title span.main-header',
            ]
        );

        $this->add_control(
            'bdscat_dualheading_base_title_color',
            [
                'label'     => esc_html__( 'Second Heading Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .meafe-dual-header .title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
            'name' => 'bdscat_dualheading_title_second_typography',
                'selector' => '{{WRAPPER}} .meafe-dual-header .title span',
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        
        $settings = $this->get_settings_for_display(); ?>

        <div class="meafe-dual-header">
            <<?php Utils::print_validated_html_tag( $settings['bdcgs_heading_title_tag'] ); ?> class="title">
                <span class="main-header"><?php echo esc_html( $settings['bdcgs_heading_one_text'] ); ?></span> 
                <span><?php echo esc_html( $settings['bdcgs_heading_two_text'] ); ?></span>
            </<?php Utils::print_validated_html_tag( $settings['bdcgs_heading_title_tag'] ); ?>>
        </div>
        <?php
    }

    protected function content_template() { ?>
        <div class="meafe-dual-header">
			<# var titleSizeTag = elementor.helpers.validateHTMLTag( settings.bdcgs_heading_title_tag ); #>
            <{{{titleSizeTag}}} class="title">
                <span class="main-header">{{{settings.bdcgs_heading_one_text}}}</span> 
                <span>{{{settings.bdcgs_heading_two_text}}}</span>
            </{{{titleSizeTag}}}>
        </div>
        <?php
    }
}
