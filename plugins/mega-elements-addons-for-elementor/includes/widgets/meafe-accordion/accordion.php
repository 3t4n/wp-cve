<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;

class MEAFE_Accordion extends Widget_Base
{

    public function get_name() {
        return 'meafe-accordion';
    }

    public function get_title() {
        return esc_html__( 'Accordion', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-accordion';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-accordion'];
    }

    public function get_script_depends() {
        return ['meafe-accordion'];
    }

    protected function register_controls()
    {
        /**
         * Accordion General Settings
         */
        $this->start_controls_section(
            'meafe_accordion_content_general_settings',
            [
                'label' => esc_html__( 'General Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );
        $this->add_control(
            'baccgs_accordion_type',
            [
                'label'         => esc_html__( 'Accordion Type', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'accordion',
                'label_block'   => false,
                'options'       => [
                    'accordion' => esc_html__( 'Accordion', 'mega-elements-addons-for-elementor' ),
                    'toggle'    => esc_html__( 'Toggle', 'mega-elements-addons-for-elementor' ),
                ],
            ]
        );
        $this->add_control(
            'baccgs_accordion_toggle_speed',
            [
                'label'         => esc_html__( 'Toggle Speed (ms)', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::NUMBER,
                'label_block'   => false,
                'default'       => 300,
            ]
        );
        $this->end_controls_section();

        /**
         * Accordion Content Settings
         */
        $this->start_controls_section(
            'meafe_accordion_content_content_settings',
            [
                'label' => esc_html__( 'Content Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $accordion_repeater = new Repeater();

        $accordion_repeater->add_control(
            'bacccs_accordion_tab_default_active',
            [
                'label'         => esc_html__( 'Active as Default', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'no',
                'return_value'  => 'yes',
            ]
        );

        $accordion_repeater->add_control(
            'bacccs_accordion_tab_title',
            [
                'label'         => esc_html__( 'Tab Title', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'default'       => esc_html__( 'Tab Title', 'mega-elements-addons-for-elementor' ),
                'dynamic'       => ['active' => true],
                'label_block' => true,
            ]
        );
        
        $accordion_repeater->add_control(
            'bacccs_accordion_tab_content',
            [
                'label'         => esc_html__( 'Tab Content', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::WYSIWYG,
                'default'       => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'mega-elements-addons-for-elementor' ),
                'dynamic'       => ['active' => true],
            ]
        );

        $this->add_control(
            'bacccs_accordion_tab',
            [
                'type'      => Controls_Manager::REPEATER,
                'seperator' => 'before',
                'default'   => [
                    ['bacccs_accordion_tab_title' => esc_html__( 'Accordion Tab Title 1', 'mega-elements-addons-for-elementor' )],
                    ['bacccs_accordion_tab_title' => esc_html__( 'Accordion Tab Title 2', 'mega-elements-addons-for-elementor' )],
                    ['bacccs_accordion_tab_title' => esc_html__( 'Accordion Tab Title 3', 'mega-elements-addons-for-elementor' )],
                ],
                'fields'    => $accordion_repeater->get_controls(),
                'title_field' => '{{bacccs_accordion_tab_title}}',
            ]
        );

        $this->add_control(
            'bacccs_accordion_selected_icon',
            [
                'label'     => __( 'Icon', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::ICONS,
                'separator' => 'before',
                'fa4compatibility' => 'bacccs_accordion_icon',
                'default'   => [
                    'value' => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'chevron-down',
                        'angle-down',
                        'angle-double-down',
                        'caret-down',
                        'caret-square-down',
                    ],
                    'fa-regular' => [
                        'caret-square-down',
                    ],
                ],
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $this->add_control(
            'bacccs_accordion_selected_active_icon',
            [
                'label'     => __( 'Active Icon', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::ICONS,
                'fa4compatibility' => 'bacccs_accordion_icon_active',
                'default'   => [
                    'value' => 'fas fa-minus',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'chevron-up',
                        'angle-up',
                        'angle-double-up',
                        'caret-up',
                        'caret-square-up',
                    ],
                    'fa-regular' => [
                        'caret-square-up',
                    ],
                ],
                'skin'      => 'inline',
                'label_block' => false,
                'condition' => [
                    'bacccs_accordion_selected_icon[value]!' => '',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Accordion General Style
         * ----------- --------------------------------
         */
        $this->start_controls_section(
            'meafe_accordion_style_general_style',
            [
                'label'         => esc_html__( 'General Style', 'mega-elements-addons-for-elementor' ),
                'tab'           => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'bacsgs_accordion_padding',
            [
                'label'         => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'bacsgs_accordion_margin',
            [
                'label'         => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'bacsgs_accordion_border',
                'label'         => esc_html__( 'Border', 'mega-elements-addons-for-elementor' ),
                'selector'      => '{{WRAPPER}} .meafe-accordion',
            ]
        );
        $this->add_responsive_control(
            'bacsgs_accordion_border_radius',
            [
                'label'         => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'bacsgs_accordion_box_shadow',
                'selector'      => '{{WRAPPER}} .meafe-accordion',
            ]
        );
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Accordion Tab Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'meafe_accordion_style_tab_style',
            [
                'label'     => esc_html__( 'Tab Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bacsts_accordion_tab_title_typography',
                'selector'  => '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header',
            ]
        );
        $this->add_responsive_control(
            'bacsts_accordion_tab_icon_size',
            [
                'label'     => esc_html__( 'Icon Size', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range'      => [
                    'px'     => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header span.blsm-default-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header span.blsm-active-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bacsts_accordion_icon_spacing',
            [
                'label'         => esc_html__( 'Icon Spacing', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-header .blsm-default-icon' => 'left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-header .blsm-active-icon' => 'left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bacsts_accordion_icon_top_spacing',
            [
                'label'         => esc_html__( 'Icon Top Spacing', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SLIDER,
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-header .blsm-default-icon' => 'top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-header .blsm-active-icon' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'bacsts_accordion_tab_padding',
            [
                'label'         => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'bacsts_accordion_tab_margin',
            [
                'label'         => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'bacsts_accordion_header_tabs' );
        # Normal State Tab
        $this->start_controls_tab( 'bacsts_accordion_header_tabs_normal', ['label' => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' )] );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'bacsts_accordion_tabs_normal_bgtype',
                'types'         => ['classic', 'gradient'],
                'selector'      => '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header',
            ]
        );
        $this->add_control(
            'bacsts_accordion_tabs_normal_text_color',
            [
                'label'         => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'default'       => '',
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header span.blsm-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'bacsts_accordion_tabs_normal_icon_color',
            [
                'label'         => esc_html__( 'Icon Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'default'       => '',
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header span.blsm-default-icon' => 'color: {{VALUE}}','{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header span.blsm-active-icon' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'bacsts_accordion_tabs_normal_border',
                'label'         => esc_html__( 'Border', 'mega-elements-addons-for-elementor' ),
                'selector'      => '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header',
            ]
        );
        $this->add_responsive_control(
            'bacsts_accordion_tabs_normal_border_radius',
            [
                'label'         => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->end_controls_tab();

        # Hover State Tab
        $this->start_controls_tab(
            'bacsts_accordion_header_tabs_hover',
            [
                'label' => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'bacsts_accordion_tabs_hover_bgtype',
                'types'         => ['classic', 'gradient'],
                'selector'      => '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header:hover',
            ]
        );
        $this->add_control(
            'bacsts_accordion_tabs_hover_text_color',
            [
                'label'         => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'default'       => '',
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header .blsm-title:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'bacsts_accordion_tabs_hover_icon_color',
            [
                'label'         => esc_html__('Icon Color', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'default'       => '',
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header span.blsm-default-icon:hover' => 'color: {{VALUE}}','{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header span.blsm-active-icon:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'bacsts_accordion_tabs_hover_border',
                'label'         => esc_html__('Border', 'mega-elements-addons-for-elementor'),
                'selector'      => '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header:hover',
            ]
        );
        $this->add_responsive_control(
            'bacsts_accordion_tabs_hover_border_radius',
            [
                'label'         => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->end_controls_tab();

        #Active State Tab
        $this->start_controls_tab(
            'bacsts_accordion_header_tabs_active',
            [
                'label' => esc_html__( 'Active', 'mega-elements-addons-for-elementor' ),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'bacsts_accordion_tabs_active_bgtype',
                'types'         => ['classic', 'gradient'],
                'selector'      => '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header.active',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'bacsts_accordion_tabs_active_border',
                'label'         => esc_html__('Border', 'mega-elements-addons-for-elementor'),
                'selector'      => '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header.active',
            ]
        );
        $this->add_responsive_control(
            'bacsts_accordion_tabs_active_border_radius',
            [
                'label'         => esc_html__('Border Radius', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-header.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Accordion Content Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'meafe_accordion_style_content_style',
            [
                'label'     => esc_html__( 'Content Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'bacscs_accordion_content_bgtype',
                'types'         => ['classic', 'gradient'],
                'selector'      => '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-content',
            ]
        );

        $this->add_control(
            'bacscs_accordion_content_text_color',
            [
                'label'         => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::COLOR,
                'default'       => '',
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-content' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'          => 'bacscs_accordion_content_typography',
                'selector'      => '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-content',
            ]
        );
        $this->add_responsive_control(
            'bacscs_accordion_content_padding',
            [
                'label'         => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'bacscs_accordion_content_margin',
            [
                'label'         => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'          => 'bacscs_accordion_content_border',
                'label'         => esc_html__( 'Border', 'mega-elements-addons-for-elementor' ),
                'selector'      => '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-content',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'bacscs_accordion_content_shadow',
                'selector'      => '{{WRAPPER}} .meafe-accordion .meafe-accordion-list .meafe-accordion-content',
                'separator'     => 'before',
            ]
        );
        $this->end_controls_section();

    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();
        $id_int = substr($this->get_id_int(), 0, 3);

        $migrated = isset( $settings['__fa4_migrated']['bacccs_accordion_selected_icon'] );

        if ( ! isset( $settings['bacccs_accordion_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
            $settings['bacccs_accordion_icon'] = 'fa fa-plus';
            $settings['bacccs_accordion_icon_active'] = 'fa fa-minus';
        }

        $is_new = empty( $settings['bacccs_accordion_icon'] ) && Icons_Manager::is_migration_allowed();

        $this->add_render_attribute( 'meafe-accordion', 'class', 'meafe-accordion' );
        $this->add_render_attribute( 'meafe-accordion', 'id', 'meafe-accordion-' . esc_attr( $this->get_id() ) );
        ?>
        <div
            <?php echo $this->get_render_attribute_string( 'meafe-accordion' ); ?>
            <?php echo 'data-accordion-id="' . esc_attr( $this->get_id() ) . '"'; ?>
            <?php echo !empty($settings['baccgs_accordion_type']) ? 'data-accordion-type="' . esc_attr( $settings['baccgs_accordion_type'] ) . '"' : 'accordion'; ?>
            <?php echo !empty($settings['baccgs_accordion_toggle_speed']) ? 'data-toogle-speed="' . esc_attr($settings['baccgs_accordion_toggle_speed']) . '"' : '300'; ?>
        >
		<?php foreach ( $settings['bacccs_accordion_tab'] as $index => $tab ) {
            $tab_count = $index + 1;
            $tab_title_setting_key = $this->get_repeater_setting_key( 'bacccs_accordion_tab_title', 'bacccs_accordion_tab', $index);
            $tab_content_setting_key = $this->get_repeater_setting_key( 'bacccs_accordion_tab_content', 'bacccs_accordion_tab', $index );

            $tab_title_class = ['elementor-tab-title', 'meafe-accordion-header'];
            $tab_content_class = ['meafe-accordion-content', 'clearfix'];

            if ( $tab['bacccs_accordion_tab_default_active'] == 'yes' ) {
                $tab_title_class[] = 'active-default';
                $tab_content_class[] = 'active-default';
            }

            $this->add_render_attribute( $tab_title_setting_key, [
                'id'        => 'elementor-tab-title-' . $id_int . $tab_count,
                'class'     => $tab_title_class,
                'tabindex'  => $id_int . $tab_count,
                'data-tab'  => $tab_count,
                'role'      => 'tab',
                'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
            ]);

            $this->add_render_attribute($tab_content_setting_key, [
                'id'        => 'elementor-tab-content-' . $id_int . $tab_count,
                'class'     => $tab_content_class,
                'data-tab'  => $tab_count,
                'role'      => 'tabpanel',
                'aria-labelledby' => 'elementor-tab-title-' . $id_int . $tab_count,
            ]);

            echo '<div class="meafe-accordion-list">
                <div ' . $this->get_render_attribute_string( $tab_title_setting_key ) . '>
                    <span>';
                        if ( $is_new || $migrated ) { ?>
                            <span class="blsm-default-icon"><?php Icons_Manager::render_icon( $settings['bacccs_accordion_selected_icon'] ); ?></span>
                            <span class="blsm-active-icon"><?php Icons_Manager::render_icon( $settings['bacccs_accordion_selected_active_icon'] ); ?></span>
                        <?php } else { ?>
                            <span class="blsm-default-icon"><i class="<?php echo esc_attr( $settings['bacccs_accordion_icon'] ); ?>"></i></span>
                            <span class="blsm-active-icon"><i class="<?php echo esc_attr( $settings['bacccs_accordion_icon_active'] ); ?>"></i></span>
                        <?php }
                        echo '<span class="blsm-title">' . esc_html( $tab['bacccs_accordion_tab_title'] ) . '</span>' .
                    '</span>';
                echo '</div>';

            echo '<div ' . $this->get_render_attribute_string($tab_content_setting_key) . '>';
            echo '<p>' . do_shortcode( $tab['bacccs_accordion_tab_content'] ) . '</p>';
            echo '</div>
                </div>';
        }
        echo '</div>';
    }

    protected function content_template() {
    }
}