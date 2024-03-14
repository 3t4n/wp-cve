<?php
/**
 * Column Option Enhance functions
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Extension;

use Elementor\Controls_Manager;
use Elementor\Element_Column;

defined('ABSPATH') || die();

class Column_Extended {

    public static function init() {
        add_action( 'elementor/element/column/layout/before_section_end', [ __CLASS__, 'add_controls' ] );
    }

    public static function add_controls( Element_Column $element ) {
        $element->add_responsive_control(
            '_skt_addons_elementor_column_width',
            [
                'label' => __( 'Custom Column Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXT,
                'separator' => 'before',
                'label_block' => true,
                'description' => __( 'Here you can set the column width the way you always wanted to! e.g 250px, 50%, calc(100% - 250px)', 'skt-addons-elementor' ),
                'selectors' => [
                    '{{WRAPPER}}.elementor-column' => 'width: {{VALUE}};',
                ],
            ]
        );

        $element->add_responsive_control(
            '_skt_addons_elementor_column_order',
            [
                'label' => __( 'Column Order', 'skt-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'style_transfer' => true,
                'selectors' => [
                    '{{WRAPPER}}.elementor-column' => '-webkit-box-ordinal-group: calc({{VALUE}} + 1 ); -ms-flex-order:{{VALUE}}; order: {{VALUE}};',
                ],
                'description' => sprintf(
                    __( 'Column ordering is a great addition for responsive design. You can learn more about CSS order property from %sMDN%s.', 'skt-addons-elementor' ),
                    '<a
href="https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Flexible_Box_Layout/Ordering_Flex_Items#The_order_property" target="_blank">',
                    '</a>'
                ),
            ]
        );
    }
}

Column_Extended::init();