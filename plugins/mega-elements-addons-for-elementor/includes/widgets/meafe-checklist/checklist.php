<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Repeater;

class MEAFE_Checklist extends Widget_Base
{

    public function get_name() {
        return 'meafe-checklist';
    }

    public function get_title() {
        return esc_html__( 'Checklist', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-checklist';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-checklist'];
    }

    protected function register_controls()
    {   
        /**
         * Checklist General Settings
         */ 
        $this->start_controls_section(
            'meafe_checklist_content_general_settings',
            [
                'label'     => esc_html__( 'General Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'btcgs_checklist_icon_type', 
            [
                'label'         => esc_html__( 'Icon Type', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'icon_only'  => esc_html__( 'Icon', 'mega-elements-addons-for-elementor' ),
                    'counter'   => esc_html__( 'Counter', 'mega-elements-addons-for-elementor' ),
                ],
                'default'       => 'icon_only',
                'label_block'   => true,
            ]
        );

        $this->add_control(
            'btcgs_checklist_selected_icon',
            [
                'label'     => __( 'Icon', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::ICONS,
                'fa4compatibility' => 'btcgs_checklist_icon',
                'default'   => [
                    'value' => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'btcgs_checklist_icon_type' => 'icon_only',
                ],
                'separator' => 'after',
            ]
        );

        $checklist_repeater = new Repeater();

        $checklist_repeater->add_control(
            'btcgs_checklist_name',
            [
                'label'     => esc_html__( 'Text', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => esc_html__( 'List Item', 'mega-elements-addons-for-elementor' ),
                'default'   => esc_html__( 'List Item', 'mega-elements-addons-for-elementor' ),
                'dynamic'   => [
                    'active' => true,
                ],
            ]
        );

        $checklist_repeater->add_control(
            'btcgs_checklist_link',
            [
                'label'     => esc_html__( 'Link', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::URL,
                'dynamic'   => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'btcgs_checklist_repeater',
            array(
                'type'      => Controls_Manager::REPEATER,
                'fields'    => $checklist_repeater->get_controls(),
                'default'   => array(
                    array(
                        'btcgs_checklist_name'   => esc_html__( 'List Item #1', 'mega-elements-addons-for-elementor' ),
                    ),
                    array(
                        'btcgs_checklist_name'   => esc_html__( 'List Item #2', 'mega-elements-addons-for-elementor' ),
                    ),
                    array(
                        'btcgs_checklist_name'   => esc_html__( 'List Item #3', 'mega-elements-addons-for-elementor' ),
                    ),
                ),
                'title_field' => '{{{ btcgs_checklist_name }}}',
            )
        );

        $this->end_controls_section();

        /**
         * Checklist General Style
         */
        $this->start_controls_section(
            'meafe_checklist_style_general_style',
            [
                'label'     => esc_html__( 'General Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'btsgs_space_between',
            [
                'label'     => esc_html__( 'Space Between', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px'    => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-icon-list-items:not(.meafe-inline-items) .meafe-icon-list-item:not(:last-child)::after' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .meafe-icon-list-items:not(.meafe-inline-items):not(.enabled-divider) .meafe-icon-list-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'btsgs_icon_align',
            [
                'label'     => esc_html__( 'Alignment', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'      => [
                        'title' => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center'    => [
                        'title' => esc_html__( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'     => [
                        'title' => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'meafe%s-align-',
            ]
        );

        $this->add_control(
            'btsgs_divider',
            [
                'label'     => esc_html__( 'Divider', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_off' => esc_html__( 'Off', 'mega-elements-addons-for-elementor' ),
                'label_on'  => esc_html__( 'On', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-icon-list-item:not(:last-child):after' => 'content: ""',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'btsgs_divider_style',
            [
                'label'     => esc_html__( 'Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'solid'     => esc_html__( 'Solid', 'mega-elements-addons-for-elementor' ),
                    'double'    => esc_html__( 'Double', 'mega-elements-addons-for-elementor' ),
                    'dotted'    => esc_html__( 'Dotted', 'mega-elements-addons-for-elementor' ),
                    'dashed'    => esc_html__( 'Dashed', 'mega-elements-addons-for-elementor' ),
                ],
                'default'   => 'solid',
                'condition' => [
                    'btsgs_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-icon-list-items:not(.meafe-inline-items) .meafe-icon-list-item:not(:last-child):after' => 'border-top-style: {{VALUE}}',
                    '{{WRAPPER}} .meafe-icon-list-items.meafe-inline-items .meafe-icon-list-item:not(:last-child):after' => 'border-left-style: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btsgs_divider_weight',
            [
                'label'     => esc_html__( 'Weight', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 1,
                ],
                'range'     => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'condition' => [
                    'btsgs_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-icon-list-items:not(.meafe-inline-items) .meafe-icon-list-item:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .meafe-inline-items .meafe-icon-list-item:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btsgs_divider_width',
            [
                'label'     => esc_html__( 'Width', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'unit' => '%',
                ],
                'condition' => [
                    'btsgs_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-icon-list-item:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btsgs_divider_color',
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'condition' => [
                    'btsgs_divider' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-icon-list-item:not(:last-child):after' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Checklist Icon Style
         */
        $this->start_controls_section(
            'meafe_checklist_style_icon_style',
            [
                'label'     => esc_html__( 'Icon Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'btcgs_checklist_icon_type' => 'icon_only',
                ],
            ]
        );

        $this->add_control(
            'btsis_icon_color',
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-icon-list-icon svg' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btsis_icon_color_hover',
            [
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-icon-list-item:hover .meafe-icon-list-icon svg' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btsis_icon_size',
            [
                'label'     => esc_html__( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 14,
                ],
                'range'     => [
                    'px' => [
                        'min' => 6,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-icon-list-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .meafe-icon-list-icon + .meafe-icon-list-text' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
                ],
            ]
        );


        $this->end_controls_section();

        /**
         * Checklist Text Style
         */
        $this->start_controls_section(
            'meafe_checklist_style_text_style',
            [
                'label'     => esc_html__( 'Text Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btsts_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-icon-list-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btsts_text_color_hover',
            [
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-icon-list-item:hover .meafe-icon-list-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btsts_text_indent',
            [
                'label'     => esc_html__( 'Text Indent', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-icon-list-icon + .meafe-icon-list-text' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'btsts_icon_typography',
                'selector'  => '{{WRAPPER}} .meafe-icon-list-item',
            ]
        );

        $this->end_controls_section();

        /**
         * Checklist Counter Style
         */
        $this->start_controls_section(
            'meafe_checklist_style_count_style',
            [
                'label'     => esc_html__( 'Count Value Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'btcgs_checklist_icon_type' => 'counter',
                ],
            ]
        );

        $this->add_control(
            'btscs_count_color',
            [
                'label'     => esc_html__( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-icon-list-items.counter-select li::before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btscs_count_color_hover',
            [
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-icon-list-items.counter-select li:hover::before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btscs_count_size',
            [
                'label'     => esc_html__( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 14,
                ],
                'range'     => [
                    'px' => [
                        'min' => 6,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-icon-list-items.counter-select li::before' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $migrated = isset( $settings['__fa4_migrated']['btcgs_checklist_selected_icon'] );

        if ( ! isset( $settings['btcgs_checklist_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
            $settings['btcgs_checklist_icon'] = 'fa fa-check';
        }

        $is_new = empty( $settings['btcgs_checklist_icon'] ) && Icons_Manager::is_migration_allowed();
        $is_icon = ( $settings['btcgs_checklist_icon_type'] == 'icon_only' ) ? true : false;

        $add_class = ( $is_icon ) ? ' icon-select' : ' counter-select';
        $add_divider = ( $settings['btsgs_divider'] ) ? ' enabled-divider' : '';
        $this->add_render_attribute( 'btcgs_checklist_repeater', 'class', 'meafe-icon-list-items' . esc_attr($add_class) . esc_attr($add_divider) );
        $this->add_render_attribute( 'meafe_list_item', 'class', 'meafe-icon-list-item' );
        if( $settings['btcgs_checklist_icon_type'] == 'counter' ) {
            $this->add_render_attribute( 'meafe_list_item', 'class', 'meafe-icon-counter' );
        }
        ?>
        <ul <?php echo $this->get_render_attribute_string( 'btcgs_checklist_repeater' ); ?>>

            <?php
            foreach ( $settings['btcgs_checklist_repeater'] as $index => $item ) :
                $repeater_setting_key = $this->get_repeater_setting_key( 'btcgs_checklist_name', 'btcgs_checklist_repeater', $index );

                $this->add_render_attribute( $repeater_setting_key, 'class', 'meafe-icon-list-text' );

                $this->add_inline_editing_attributes( $repeater_setting_key );
                ?>
                <li <?php echo $this->get_render_attribute_string( 'meafe_list_item' ); ?>>
                    <?php
                    if ( ! empty( $item['btcgs_checklist_link']['url'] ) ) {
                        $link_key = 'btcgs_checklist_link_' . $index;

                        $this->add_link_attributes( $link_key, $item['btcgs_checklist_link'] );

                        echo '<a ' . $this->get_render_attribute_string( $link_key ) . '>';
                    }
                    
                    if ( $is_icon ) {
                        echo '<span class="meafe-icon-list-icon">'; 
                        if ( $is_new || $migrated ) { ?>
                            <?php Icons_Manager::render_icon( $settings['btcgs_checklist_selected_icon'] ); ?>
                        <?php } else { ?>
                            <i class="<?php echo esc_attr( $settings['btcgs_checklist_icon'] ); ?>"></i>
                        <?php } 
                        echo '</span>';
                    } ?>
                    <span <?php echo $this->get_render_attribute_string( $repeater_setting_key ); ?>><?php echo esc_html( $item['btcgs_checklist_name'] ); ?></span>
                    <?php if ( ! empty( $item['btcgs_checklist_link']['url'] ) ) : ?>
                        </a>
                    <?php endif; ?>
                </li>
                <?php
            endforeach;
            ?>
        </ul>
        <?php
    }

    protected function content_template() {
        ?>
        <#
            var add_class = ( settings.btcgs_checklist_icon_type == 'icon_only' ) ? ' icon-select' : ' counter-select';
            var add_divider = ( settings.btsgs_divider ) ? ' enabled-divider' : '';
            view.addRenderAttribute( 'btcgs_checklist_repeater', 'class', 'meafe-icon-list-items' + add_class + add_divider );
            view.addRenderAttribute( 'meafe_list_item', 'class', 'meafe-icon-list-item' );

            if( settings.btcgs_checklist_icon_type == 'counter' ) {
                view.addRenderAttribute( 'meafe_list_item', 'class', 'meafe-icon-counter' );
            }
            var iconsHTML = {},
                migrated = {};
        #>
        <# if ( settings.btcgs_checklist_repeater ) { #>
            <ul {{{ view.getRenderAttributeString( 'btcgs_checklist_repeater' ) }}}>
            <# _.each( settings.btcgs_checklist_repeater, function( item, index ) {

                    var iconTextKey = view.getRepeaterSettingKey( 'btcgs_checklist_name', 'btcgs_checklist_repeater', index );

                    view.addRenderAttribute( iconTextKey, 'class', 'meafe-icon-list-text' );

                    view.addInlineEditingAttributes( iconTextKey ); #>

                    <li {{{ view.getRenderAttributeString( 'meafe_list_item' ) }}}>
                        <# if ( item.btcgs_checklist_link && item.btcgs_checklist_link.url ) { #>
                            <a href="{{ item.btcgs_checklist_link.url }}">
                        <# } #>
                        
                        <# if( settings.btcgs_checklist_icon_type == 'icon_only' ) {
                            if ( settings.btcgs_checklist_icon || settings.btcgs_checklist_selected_icon.value ) { #>
                                <span class="meafe-icon-list-icon">
                                    <#
                                        iconsHTML[ index ] = elementor.helpers.renderIcon( view, settings.btcgs_checklist_selected_icon, { 'aria-hidden': true }, 'i', 'object' );
                                        migrated[ index ] = elementor.helpers.isIconMigrated( settings, 'btcgs_checklist_selected_icon' );
                                        if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! settings.btcgs_checklist_icon || migrated[ index ] ) ) { #>
                                            {{{ iconsHTML[ index ].value }}}
                                        <# } else { #>
                                            <i class="{{ settings.btcgs_checklist_icon }}" aria-hidden="true"></i>
                                        <# }
                                    #>
                                </span>
                            <# }
                        } #>
                        
                        <span {{{ view.getRenderAttributeString( iconTextKey ) }}}>{{{ item.btcgs_checklist_name }}}</span>
                        <# if ( item.btcgs_checklist_link && item.btcgs_checklist_link.url ) { #>
                            </a>
                        <# } #>
                    </li>
                <#
                } ); #>
            </ul>
        <#  } #>

        <?php
    }
}
