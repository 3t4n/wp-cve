<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;

class MEAFE_Pricing_Table extends Widget_Base
{

    public function get_name() {
        return 'meafe-pricing-table';
    }

    public function get_title() {
        return esc_html__( 'Pricing Table', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-pricing-table';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-pricing-table'];
    }

    public function get_script_depends() {
        return ['tooltip', 'meafe-pricing-table'];
    }

    /**
     * Register pricing table widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @access protected
     */
    protected function register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $this->meafe_register_controls();
    }

    /**
     * Register pricing table widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 2.2.5
     * @access protected
     */
    protected function meafe_register_controls() {
        /* Content Tab */
        $this->register_content_header_controls();
        $this->register_content_pricing_controls();
        $this->register_content_features_controls();
        $this->register_content_ribbon_controls();
        $this->register_content_tooltip_controls();
        $this->register_content_button_controls();

        /* Style Tab */
        $this->register_style_table_controls();
        $this->register_style_header_controls();
        $this->register_style_pricing_controls();
        $this->register_style_features_controls();
        $this->register_style_tooltip_controls();
        $this->register_style_ribbon_controls();
        $this->register_style_button_controls();
        $this->register_style_footer_controls();
    }

    /*-----------------------------------------------------------------------------------*/
    /*  CONTENT TAB
    /*-----------------------------------------------------------------------------------*/

    protected function register_content_header_controls() {
        /**
         * Content Tab: Header
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_header',
            array(
                'label' => __( 'Header', 'mega-elements-addons-for-elementor' ),
            )
        );

        $this->add_control(
            'pricing_table_layouts',
            [
                'label'         => esc_html__( 'Select Layout', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => '1',
                'label_block'   => false,
                'options'       => [
                    '1'       => esc_html__( 'Layout One', 'mega-elements-addons-for-elementor' ),
                    '2'       => esc_html__( 'Layout Two', 'mega-elements-addons-for-elementor' ),
                    '3'       => esc_html__( 'Layout Three', 'mega-elements-addons-for-elementor' ),
                    '4'       => esc_html__( 'Layout Four', 'mega-elements-addons-for-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'icon_type',
            array(
                'label'       => esc_html__( 'Icon Type', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'     => array(
                    'none'  => array(
                        'title' => esc_html__( 'None', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-ban',
                    ),
                    'icon'  => array(
                        'title' => esc_html__( 'Icon', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-star',
                    ),
                    'image' => array(
                        'title' => esc_html__( 'Image', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-picture-o',
                    ),
                ),
                'default'     => 'icon',
            )
        );

        $this->add_control(
            'select_table_icon',
            array(
                'label'            => __( 'Icon', 'mega-elements-addons-for-elementor' ),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'table_icon',
                'default'          => array(
                    'value'   => 'fas fa-star',
                    'library' => 'fa-solid',
                ),
                'condition'        => array(
                    'icon_type' => 'icon',
                ),
            )
        );

        $this->add_control(
            'icon_image',
            array(
                'label'     => __( 'Image', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'condition' => array(
                    'icon_type' => 'image',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'      => 'image', // Usage: '{name}_size' and '{name}_custom_dimension', in this case 'image_size' and 'image_custom_dimension'.
                'default'   => 'full',
                'separator' => 'none',
                'condition' => array(
                    'icon_type' => 'image',
                ),
            )
        );

        $this->add_control(
            'table_title',
            array(
                'label'   => __( 'Title', 'mega-elements-addons-for-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array(
                    'active' => true,
                ),
                'default' => __( 'Title', 'mega-elements-addons-for-elementor' ),
                'title'   => __( 'Enter table title', 'mega-elements-addons-for-elementor' ),
            )
        );

        $this->add_control(
            'table_subtitle',
            array(
                'label'   => __( 'Subtitle', 'mega-elements-addons-for-elementor' ),
                'type'    => Controls_Manager::TEXTAREA,
                'dynamic' => array(
                    'active' => true,
                ),
                'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'mega-elements-addons-for-elementor' ),
                'title'   => __( 'Enter table subtitle', 'mega-elements-addons-for-elementor' ),
            )
        );

        $this->end_controls_section();
    }

    protected function register_content_pricing_controls() {
        /**
         * Content Tab: Pricing
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_pricing',
            array(
                'label' => __( 'Pricing', 'mega-elements-addons-for-elementor' ),
            )
        );

        $this->add_control(
            'currency_symbol',
            array(
                'label'   => __( 'Currency Symbol', 'mega-elements-addons-for-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    ''             => __( 'None', 'mega-elements-addons-for-elementor' ),
                    'dollar'       => '&#36; ' . __( 'Dollar', 'mega-elements-addons-for-elementor' ),
                    'euro'         => '&#128; ' . __( 'Euro', 'mega-elements-addons-for-elementor' ),
                    'baht'         => '&#3647; ' . __( 'Baht', 'mega-elements-addons-for-elementor' ),
                    'franc'        => '&#8355; ' . __( 'Franc', 'mega-elements-addons-for-elementor' ),
                    'guilder'      => '&fnof; ' . __( 'Guilder', 'mega-elements-addons-for-elementor' ),
                    'krona'        => 'kr ' . __( 'Krona', 'mega-elements-addons-for-elementor' ),
                    'lira'         => '&#8356; ' . __( 'Lira', 'mega-elements-addons-for-elementor' ),
                    'peseta'       => '&#8359 ' . __( 'Peseta', 'mega-elements-addons-for-elementor' ),
                    'peso'         => '&#8369; ' . __( 'Peso', 'mega-elements-addons-for-elementor' ),
                    'pound'        => '&#163; ' . __( 'Pound Sterling', 'mega-elements-addons-for-elementor' ),
                    'real'         => 'R$ ' . __( 'Real', 'mega-elements-addons-for-elementor' ),
                    'ruble'        => '&#8381; ' . __( 'Ruble', 'mega-elements-addons-for-elementor' ),
                    'rupee'        => '&#8360; ' . __( 'Rupee', 'mega-elements-addons-for-elementor' ),
                    'indian_rupee' => '&#8377; ' . __( 'Rupee (Indian)', 'mega-elements-addons-for-elementor' ),
                    'shekel'       => '&#8362; ' . __( 'Shekel', 'mega-elements-addons-for-elementor' ),
                    'yen'          => '&#165; ' . __( 'Yen/Yuan', 'mega-elements-addons-for-elementor' ),
                    'won'          => '&#8361; ' . __( 'Won', 'mega-elements-addons-for-elementor' ),
                    'custom'       => __( 'Custom', 'mega-elements-addons-for-elementor' ),
                ),
                'default' => 'dollar',
            )
        );

        $this->add_control(
            'currency_symbol_custom',
            array(
                'label'     => __( 'Custom Symbol', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'dynamic'   => array(
                    'active' => true,
                ),
                'default'   => '',
                'condition' => array(
                    'currency_symbol' => 'custom',
                ),
            )
        );

        $this->add_control(
            'table_price',
            array(
                'label'   => __( 'Price', 'mega-elements-addons-for-elementor' ),
                'type'    => Controls_Manager::NUMBER,
                'dynamic' => array(
                    'active' => true,
                ),
                'default' => '49.99',
            )
        );

        $this->add_control(
            'currency_format',
            array(
                'label'   => __( 'Currency Format', 'mega-elements-addons-for-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'raised',
                'options' => array(
                    'raised' => __( 'Raised', 'mega-elements-addons-for-elementor' ),
                    ''       => __( 'Normal', 'mega-elements-addons-for-elementor' ),
                ),
            )
        );

        $this->add_control(
            'discount',
            array(
                'label'        => __( 'Discount', 'mega-elements-addons-for-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => __( 'On', 'mega-elements-addons-for-elementor' ),
                'label_off'    => __( 'Off', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'table_original_price',
            array(
                'label'     => __( 'Original Price', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::NUMBER,
                'dynamic'   => array(
                    'active' => true,
                ),
                'default'   => '69',
                'condition' => array(
                    'discount' => 'yes',
                ),
            )
        );

        $this->add_control(
            'table_duration',
            array(
                'label'   => __( 'Duration', 'mega-elements-addons-for-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'default' => __( 'per month', 'mega-elements-addons-for-elementor' ),
            )
        );

        $this->add_control(
            'duration_position',
            array(
                'label'        => __( 'Duration Position', 'mega-elements-addons-for-elementor' ),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'nowrap',
                'options'      => array(
                    'nowrap' => __( 'Same Line', 'mega-elements-addons-for-elementor' ),
                    'wrap'   => __( 'Next Line', 'mega-elements-addons-for-elementor' ),
                ),
                'prefix_class' => 'meafe-pricing-table-price-duration-',
            )
        );

        $this->end_controls_section();
    }

    protected function register_content_features_controls() {
        /**
         * Content Tab: Features
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_features',
            array(
                'label' => __( 'Features', 'mega-elements-addons-for-elementor' ),
            )
        );

        $this->add_control(
            'table_additional_info',
            array(
                'label'   => __( 'Features Title', 'mega-elements-addons-for-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array(
                    'active' => true,
                ),
                'default' => __( 'Features', 'mega-elements-addons-for-elementor' ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'feature_text',
            array(
                'label'       => __( 'Text', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => array(
                    'active' => true,
                ),
                'placeholder' => __( 'Feature', 'mega-elements-addons-for-elementor' ),
                'default'     => __( 'Feature', 'mega-elements-addons-for-elementor' ),
            )
        );

        $repeater->add_control(
            'exclude',
            array(
                'label'        => __( 'Exclude', 'mega-elements-addons-for-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => __( 'Yes', 'mega-elements-addons-for-elementor' ),
                'label_off'    => __( 'No', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
            )
        );

        $repeater->add_control(
            'tooltip_content',
            array(
                'label'       => __( 'Tooltip Content', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::WYSIWYG,
                'default'     => __( 'This is a tooltip', 'mega-elements-addons-for-elementor' ),
                'dynamic'     => array(
                    'active' => true,
                ),
            )
        );

        $repeater->add_control(
            'select_feature_icon',
            array(
                'label'            => __( 'Icon', 'mega-elements-addons-for-elementor' ),
                'type'             => Controls_Manager::ICONS,
                'label_block'      => true,
                'default'          => array(
                    'value'   => 'far fa-arrow-alt-circle-right',
                    'library' => 'fa-regular',
                ),
                'fa4compatibility' => 'feature_icon',
            )
        );

        $repeater->add_control(
            'feature_icon_color',
            array(
                'label'     => __( 'Icon Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-icon svg path' => 'fill: {{VALUE}}',
                ),
                'condition' => array(
                    'select_feature_icon[value]!' => '',
                ),
            )
        );

        $repeater->add_control(
            'feature_text_color',
            array(
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
                ),
            )
        );

        $repeater->add_control(
            'feature_bg_color',
            array(
                'name'      => 'feature_bg_color',
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'table_features',
            array(
                'label'       => '',
                'type'        => Controls_Manager::REPEATER,
                'default'     => array(
                    array(
                        'feature_text'        => __( 'Feature #1', 'mega-elements-addons-for-elementor' ),
                        'select_feature_icon' => 'fa fa-check',
                    ),
                    array(
                        'feature_text'        => __( 'Feature #2', 'mega-elements-addons-for-elementor' ),
                        'select_feature_icon' => 'fa fa-check',
                    ),
                    array(
                        'feature_text'        => __( 'Feature #3', 'mega-elements-addons-for-elementor' ),
                        'select_feature_icon' => 'fa fa-check',
                    ),
                ),
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ feature_text }}}',
            )
        );

        $this->end_controls_section();
    }

    /**
     * Register Pricing Table Tooltip Controls
     *
     * @since 2.2.5
     * @return void
     */
    protected function register_content_tooltip_controls() {
        $this->start_controls_section(
            'section_tooltip',
            [
                'label'                 => __( 'Tooltip', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'show_tooltip',
            [
                'label'                 => __( 'Enable Tooltip', 'mega-elements-addons-for-elementor' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Yes', 'mega-elements-addons-for-elementor' ),
                'label_off'             => __( 'No', 'mega-elements-addons-for-elementor' ),
                'return_value'          => 'yes',
            ]
        );

        $this->add_control(
            'tooltip_trigger',
            [
                'label'              => __( 'Trigger', 'mega-elements-addons-for-elementor' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => 'hover',
                'options'            => array(
                    'hover' => __( 'Hover', 'mega-elements-addons-for-elementor' ),
                    'click' => __( 'Click', 'mega-elements-addons-for-elementor' ),
                ),
                'frontend_available' => true,
                'condition' => [
                    'show_tooltip' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tooltip_size',
            array(
                'label'   => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => array(
                    'default' => __( 'Default', 'mega-elements-addons-for-elementor' ),
                    'large'   => __( 'Large', 'mega-elements-addons-for-elementor' ),
                ),
                'condition' => [
                    'show_tooltip' => 'yes',
                ],
            )
        );

        $this->add_control(
            'tooltip_position',
            array(
                'label'   => __( 'Position', 'mega-elements-addons-for-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => array(
                    'top'          => __( 'Top', 'mega-elements-addons-for-elementor' ),
                    'bottom'       => __( 'Bottom', 'mega-elements-addons-for-elementor' ),
                    'left'         => __( 'Left', 'mega-elements-addons-for-elementor' ),
                    'right'        => __( 'Right', 'mega-elements-addons-for-elementor' ),
                ),
                'condition' => [
                    'show_tooltip' => 'yes',
                ],
            )
        );

        $this->add_control(
            'tooltip_arrow',
            array(
                'label'   => __( 'Show Arrow', 'mega-elements-addons-for-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'yes',
                'options' => array(
                    'yes' => __( 'Yes', 'mega-elements-addons-for-elementor' ),
                    'no'  => __( 'No', 'mega-elements-addons-for-elementor' ),
                ),
                'frontend_available' => true,
                'condition' => [
                    'show_tooltip' => 'yes',
                ],
            )
        );

        $this->add_control(
            'tooltip_display_on',
            array(
                'label'   => __( 'Display On', 'mega-elements-addons-for-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'text',
                'options' => array(
                    'text' => __( 'Text', 'mega-elements-addons-for-elementor' ),
                    'icon' => __( 'Icon', 'mega-elements-addons-for-elementor' ),
                ),
                'frontend_available' => true,
                'condition' => [
                    'show_tooltip' => 'yes',
                ],
            )
        );

        $this->add_control(
            'tooltip_icon',
            [
                'label'     => __( 'Icon', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-info-circle',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_tooltip'       => 'yes',
                    'tooltip_display_on' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'tooltip_distance',
            array(
                'label'       => __( 'Distance', 'mega-elements-addons-for-elementor' ),
                'description' => __( 'The distance between the text/icon and the tooltip.', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::SLIDER,
                'default'     => array(
                    'size' => '',
                ),
                'range'       => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'   => array(
                    '.meafe-tooltip.meafe-tooltip-{{ID}}.tt-top' => 'transform: translateY(-{{SIZE}}{{UNIT}});',
                    '.meafe-tooltip.meafe-tooltip-{{ID}}.tt-bottom' => 'transform: translateY({{SIZE}}{{UNIT}});',
                    '.meafe-tooltip.meafe-tooltip-{{ID}}.tt-left' => 'transform: translateX(-{{SIZE}}{{UNIT}});',
                    '.meafe-tooltip.meafe-tooltip-{{ID}}.tt-right' => 'transform: translateX({{SIZE}}{{UNIT}});',
                ),
                'condition' => [
                    'show_tooltip' => 'yes',
                ],
            )
        );

        $this->end_controls_section();
    }

    protected function register_content_ribbon_controls() {
        /**
         * Content Tab: Ribbon
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_ribbon',
            array(
                'label' => __( 'Ribbon', 'mega-elements-addons-for-elementor' ),
            )
        );

        $this->add_control(
            'show_ribbon',
            array(
                'label'        => __( 'Show Ribbon', 'mega-elements-addons-for-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => __( 'Yes', 'mega-elements-addons-for-elementor' ),
                'label_off'    => __( 'No', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'ribbon_style',
            array(
                'label'     => __( 'Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => array(
                    '1' => __( 'Default', 'mega-elements-addons-for-elementor' ),
                    '2' => __( 'Circle', 'mega-elements-addons-for-elementor' ),
                    '3' => __( 'Ribbon', 'mega-elements-addons-for-elementor' ),
                ),
                'condition' => array(
                    'show_ribbon' => 'yes',
                ),
            )
        );

        $this->add_control(
            'ribbon_title',
            array(
                'label'     => __( 'Title', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'dynamic'   => array(
                    'active' => true,
                ),
                'default'   => __( 'New', 'mega-elements-addons-for-elementor' ),
                'condition' => array(
                    'show_ribbon' => 'yes',
                ),
            )
        );

        $this->add_responsive_control(
            'ribbon_size',
            array(
                'label'      => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 200,
                    ),
                    'em' => array(
                        'min' => 1,
                        'max' => 15,
                    ),
                ),
                'default'    => array(
                    'size' => 4,
                    'unit' => 'em',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-ribbon-2' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ),
                'condition'  => array(
                    'show_ribbon'  => 'yes',
                    'ribbon_style' => array( '2' ),
                ),
            )
        );

        $this->add_responsive_control(
            'top_distance',
            array(
                'label'      => __( 'Distance from Top', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 200,
                    ),
                ),
                'default'    => array(
                    'size' => 20,
                    'unit' => '%',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-ribbon' => 'top: {{SIZE}}{{UNIT}};',
                ),
                'condition'  => array(
                    'show_ribbon'  => 'yes',
                    'ribbon_style' => array( '2', '3' ),
                ),
            )
        );

        $ribbon_distance_transform = is_rtl() ? 'translateY(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)' : 'translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)';

        $this->add_responsive_control(
            'ribbon_distance',
            array(
                'label'     => __( 'Distance', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-ribbon-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: ' . $ribbon_distance_transform,
                ),
                'condition' => array(
                    'show_ribbon'  => 'yes',
                    'ribbon_style' => array( '1' ),
                ),
            )
        );

        $this->add_control(
            'ribbon_position',
            array(
                'label'       => __( 'Position', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::CHOOSE,
                'toggle'      => false,
                'label_block' => false,
                'options'     => array(
                    'left'  => array(
                        'title' => __( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center'  => array(
                        'title' => __( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right' => array(
                        'title' => __( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'default'     => 'right',
                'condition'   => array(
                    'show_ribbon'  => 'yes',
                    'ribbon_style' => array( '1', '2', '3' ),
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function register_content_button_controls() {
        /**
         * Content Tab: Button
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_button',
            array(
                'label' => __( 'Button', 'mega-elements-addons-for-elementor' ),
            )
        );

        $this->add_control(
            'table_button_position',
            array(
                'label'   => __( 'Button Position', 'mega-elements-addons-for-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'below',
                'options' => array(
                    'above' => __( 'Above Features', 'mega-elements-addons-for-elementor' ),
                    'below' => __( 'Below Features', 'mega-elements-addons-for-elementor' ),
                    'none'  => __( 'None', 'mega-elements-addons-for-elementor' ),
                ),
            )
        );

        $this->add_control(
            'table_button_text',
            array(
                'label'   => __( 'Button Text', 'mega-elements-addons-for-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => array(
                    'active' => true,
                ),
                'default' => __( 'Get Started', 'mega-elements-addons-for-elementor' ),
            )
        );

        $this->add_control(
            'link',
            array(
                'label'       => __( 'Link', 'mega-elements-addons-for-elementor' ),
                'label_block' => true,
                'type'        => Controls_Manager::URL,
                'dynamic'     => array(
                    'active' => true,
                ),
                'placeholder' => 'https://www.your-link.com',
                'default'     => array(
                    'url' => '#',
                ),
            )
        );

        $this->end_controls_section();
    }

    /*-----------------------------------------------------------------------------------*/
    /*  STYLE TAB
    /*-----------------------------------------------------------------------------------*/

    protected function register_style_table_controls() {
        /**
         * Content Tab: Table
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_table_style',
            array(
                'label' => __( 'Table', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'table_align',
            array(
                'label'        => __( 'Alignment', 'mega-elements-addons-for-elementor' ),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => false,
                'options'      => array(
                    'left'   => array(
                        'title' => __( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'right'  => array(
                        'title' => __( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-right',
                    ),
                ),
                'default'      => '',
                'prefix_class' => 'meafe-pricing-table-align-',
            )
        );

        $this->end_controls_section();
    }

    protected function register_style_header_controls() {
        /**
         * Style Tab: Header
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_table_header_style',
            array(
                'label' => __( 'Header', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'table_title_bg_color',
            array(
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-head' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'table_header_border',
                'label'       => __( 'Border', 'mega-elements-addons-for-elementor' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'condition'   => array(
                    'table_button_text!' => '',
                ),
                'selector'    => '{{WRAPPER}} .meafe-pricing-table-head',
            )
        );

        $this->add_responsive_control(
            'table_title_padding',
            array(
                'label'      => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'table_title_icon',
            array(
                'label'     => __( 'Icon', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'icon_type!' => 'none',
                ),
            )
        );

        $this->add_responsive_control(
            'table_icon_size',
            array(
                'label'      => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => array(
                    'unit' => 'px',
                    'size' => 26,
                ),
                'range'      => array(
                    'px' => array(
                        'min'  => 5,
                        'max'  => 100,
                        'step' => 1,
                    ),
                ),
                'size_units' => array( 'px', 'em' ),
                'condition'  => array(
                    'icon_type'                 => 'icon',
                    'select_table_icon[value]!' => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'table_icon_image_width',
            array(
                'label'      => __( 'Width', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => array(
                    'size' => 120,
                    'unit' => 'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1200,
                        'step' => 1,
                    ),
                ),
                'size_units' => array( 'px', '%' ),
                'condition'  => array(
                    'icon_type'   => 'image',
                    'icon_image!' => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-icon' => 'width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_control(
            'table_icon_bg_color',
            array(
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => array(
                    'icon_type!' => 'none',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-icon' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'table_icon_color',
            array(
                'label'     => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => array(
                    'icon_type'                 => 'icon',
                    'select_table_icon[value]!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-icon' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-pricing-table-icon svg' => 'fill: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'table_icon_margin',
            array(
                'label'      => __( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'condition'  => array(
                    'icon_type!' => 'none',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'table_icon_padding',
            array(
                'label'      => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'condition'  => array(
                    'icon_type!' => 'none',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'table_icon_border',
                'label'       => __( 'Border', 'mega-elements-addons-for-elementor' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'condition'   => array(
                    'icon_type!' => 'none',
                ),
                'selector'    => '{{WRAPPER}} .meafe-pricing-table-icon',
            )
        );

        $this->add_control(
            'icon_border_radius',
            array(
                'label'      => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'condition'  => array(
                    'icon_type!' => 'none',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-icon, {{WRAPPER}} .meafe-pricing-table-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'table_title_heading',
            array(
                'label'     => __( 'Title', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'table_title_color',
            array(
                'label'     => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-title' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'table_title_typography',
                'label'    => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector' => '{{WRAPPER}} .meafe-pricing-table-title',
            )
        );

        $this->add_control(
            'table_subtitle_heading',
            array(
                'label'     => __( 'Sub Title', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'table_subtitle!' => '',
                ),
            )
        );

        $this->add_control(
            'table_subtitle_color',
            array(
                'label'     => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => array(
                    'table_subtitle!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-subtitle' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'table_subtitle_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'condition' => array(
                    'table_subtitle!' => '',
                ),
                'selector'  => '{{WRAPPER}} .meafe-pricing-table-subtitle',
            )
        );

        $this->add_responsive_control(
            'table_subtitle_spacing',
            array(
                'label'      => __( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ),
                ),
                'size_units' => array( 'px', '%' ),
                'condition'  => array(
                    'table_subtitle!' => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-subtitle' => 'margin-top: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function register_style_pricing_controls() {
        /**
         * Style Tab: Pricing
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_table_pricing_style',
            array(
                'label' => __( 'Pricing', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'table_pricing_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-pricing-table-price',
                'separator' => 'before',
            )
        );

        $this->add_control(
            'table_price_color_normal',
            array(
                'label'     => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-price' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'table_price_bg_color_normal',
            array(
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-price' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'price_border_normal',
                'label'       => __( 'Border', 'mega-elements-addons-for-elementor' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .meafe-pricing-table-price',
            )
        );

        $this->add_control(
            'pricing_border_radius',
            array(
                'label'      => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'table_pricing_width',
            array(
                'label'      => __( 'Width', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => array(
                    '%'  => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1,
                    ),
                    'px' => array(
                        'min'  => 25,
                        'max'  => 1200,
                        'step' => 1,
                    ),
                ),
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-price' => 'width: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'table_price_margin',
            array(
                'label'      => __( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'table_price_padding',
            array(
                'label'      => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'pa_logo_wrapper_shadow',
                'selector' => '{{WRAPPER}} .meafe-pricing-table-price',
            )
        );

        $this->add_control(
            'table_curreny_heading',
            array(
                'label'     => __( 'Currency Symbol', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'currency_symbol!' => '',
                ),
            )
        );

        $this->add_control(
            'currency_size',
            array(
                'label'     => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-price-prefix' => 'font-size: calc({{SIZE}}em/100)',
                ),
                'condition' => array(
                    'currency_symbol!' => '',
                ),
            )
        );

        $this->add_control(
            'currency_position',
            array(
                'label'       => __( 'Position', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default'     => 'before',
                'options'     => array(
                    'before' => array(
                        'title' => __( 'Before', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'after'  => array(
                        'title' => __( 'After', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
            )
        );

        $this->add_control(
            'currency_vertical_position',
            array(
                'label'                => __( 'Vertical Position', 'mega-elements-addons-for-elementor' ),
                'type'                 => Controls_Manager::CHOOSE,
                'label_block'          => false,
                'options'              => array(
                    'top'    => array(
                        'title' => __( 'Top', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'middle' => array(
                        'title' => __( 'Middle', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'eicon-v-align-middle',
                    ),
                    'bottom' => array(
                        'title' => __( 'Bottom', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                ),
                'default'              => 'top',
                'selectors_dictionary' => array(
                    'top'    => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ),
                'selectors'            => array(
                    '{{WRAPPER}} .meafe-pricing-table-price-prefix' => 'align-self: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'table_duration_heading',
            array(
                'label'     => __( 'Duration', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'duration_text_color',
            array(
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-price-duration' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'duration_typography',
                'label'    => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector' => '{{WRAPPER}} .meafe-pricing-table-price-duration',
            )
        );

        $this->add_responsive_control(
            'duration_spacing',
            array(
                'label'      => __( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ),
                ),
                'size_units' => array( 'px' ),
                'selectors'  => array(
                    '{{WRAPPER}}.meafe-pricing-table-price-duration-wrap .meafe-pricing-table-price-duration' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
                'condition'  => array(
                    'duration_position' => 'wrap',
                ),
            )
        );

        $this->add_control(
            'table_original_price_style_heading',
            array(
                'label'     => __( 'Original Price', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'discount' => 'yes',
                ),
            )
        );

        $this->add_control(
            'table_original_price_text_color',
            array(
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'condition' => array(
                    'discount' => 'yes',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-price-original' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'table_original_price_text_size',
            array(
                'label'      => __( 'Font Size', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => array(
                    'px' => array(
                        'min'  => 5,
                        'max'  => 100,
                        'step' => 1,
                    ),
                ),
                'size_units' => array( 'px', 'em' ),
                'condition'  => array(
                    'discount' => 'yes',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-price-original' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function register_style_features_controls() {
        /**
         * Style Tab: Features
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_table_features_style',
            array(
                'label' => __( 'Features', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'table_additional_info_heading',
            array(
                'label'     => __( 'Features Title', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'table_additional_info!' => '',
                ),
            )
        );

        $this->add_control(
            'additional_info_color',
            array(
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-additional-info' => 'color: {{VALUE}}',
                ),
                'condition' => array(
                    'table_additional_info!' => '',
                ),
            )
        );

        $this->add_control(
            'additional_info_bg_color',
            array(
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-additional-info' => 'background: {{VALUE}}',
                ),
                'condition' => array(
                    'table_additional_info!' => '',
                ),
            )
        );

        $this->add_responsive_control(
            'additional_info_margin',
            array(
                'label'      => __( 'Margin Top', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => array(
                    'size' => 20,
                    'unit' => 'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ),
                ),
                'size_units' => array( 'px' ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-additional-info' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
                'condition'  => array(
                    'table_additional_info!' => '',
                ),
            )
        );

        $this->add_responsive_control(
            'additional_info_padding',
            array(
                'label'      => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'condition'  => array(
                    'table_additional_info!' => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-additional-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'additional_info_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'condition' => array(
                    'table_additional_info!' => '',
                ),
                'selector'  => '{{WRAPPER}} .meafe-pricing-table-additional-info',
            )
        );

        $this->add_control(
            'table_features_content_heading',
            array(
                'label'     => __( 'Features Content', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'table_features_align',
            array(
                'label'       => __( 'Alignment', 'mega-elements-addons-for-elementor' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'     => array(
                    'left'   => array(
                        'title' => __( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'right'  => array(
                        'title' => __( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-right',
                    ),
                ),
                'default'     => '',
                'selectors'   => array(
                    '{{WRAPPER}} .meafe-pricing-table-features'   => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'table_features_bg_color',
            array(
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-features' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'table_features_text_color',
            array(
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-features' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'table_features_padding',
            array(
                'label'      => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'default'    => array(
                    'top'      => '20',
                    'right'    => '',
                    'bottom'   => '20',
                    'left'     => '',
                    'unit'     => 'px',
                    'isLinked' => false,
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-features' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'table_features_margin',
            array(
                'label'      => __( 'Margin Bottom', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 60,
                        'step' => 1,
                    ),
                ),
                'size_units' => array( 'px' ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-features' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'table_features_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-pricing-table-features',
                'separator' => 'before',
            )
        );

        $this->add_control(
            'table_features_icon_heading',
            array(
                'label'     => __( 'Icon', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'table_features_icon_color',
            array(
                'label'     => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-fature-icon' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-pricing-table-fature-icon svg' => 'fill: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'table_features_icon_size',
            array(
                'label'      => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => array(
                    'px' => array(
                        'min'  => 5,
                        'max'  => 100,
                        'step' => 1,
                    ),
                ),
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-fature-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            'table_features_icon_spacing',
            array(
                'label'      => __( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ),
                ),
                'size_units' => array( 'px' ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-fature-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'table_features_rows_heading',
            array(
                'label'     => __( 'Rows', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'table_features_spacing',
            array(
                'label'      => __( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => array(
                    'unit' => 'px',
                    'size' => 10,
                ),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ),
                ),
                'size_units' => array( 'px' ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-features li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'table_features_alternate',
            array(
                'label'        => __( 'Striped Rows', 'mega-elements-addons-for-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'label_on'     => __( 'Yes', 'mega-elements-addons-for-elementor' ),
                'label_off'    => __( 'No', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
            )
        );

        $this->add_responsive_control(
            'table_features_rows_padding',
            array(
                'label'      => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-features li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'  => array(
                    'table_features_alternate' => 'yes',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_features_style' );

        $this->start_controls_tab(
            'tab_features_even',
            array(
                'label'     => __( 'Even', 'mega-elements-addons-for-elementor' ),
                'condition' => array(
                    'table_features_alternate' => 'yes',
                ),
            )
        );

        $this->add_control(
            'table_features_bg_color_even',
            array(
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-features li:nth-child(even)' => 'background-color: {{VALUE}}',
                ),
                'condition' => array(
                    'table_features_alternate' => 'yes',
                ),
            )
        );

        $this->add_control(
            'table_features_text_color_even',
            array(
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-features li:nth-child(even)' => 'color: {{VALUE}}',
                ),
                'condition' => array(
                    'table_features_alternate' => 'yes',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_features_odd',
            array(
                'label'     => __( 'Odd', 'mega-elements-addons-for-elementor' ),
                'condition' => array(
                    'table_features_alternate' => 'yes',
                ),
            )
        );

        $this->add_control(
            'table_features_bg_color_odd',
            array(
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-features li:nth-child(odd)' => 'background-color: {{VALUE}}',
                ),
                'condition' => array(
                    'table_features_alternate' => 'yes',
                ),
            )
        );

        $this->add_control(
            'table_features_text_color_odd',
            array(
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-features li:nth-child(odd)' => 'color: {{VALUE}}',
                ),
                'condition' => array(
                    'table_features_alternate' => 'yes',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'table_divider_heading',
            array(
                'label'     => __( 'Divider', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'table_feature_divider',
                'label'       => __( 'Divider', 'mega-elements-addons-for-elementor' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .meafe-pricing-table-features li',
            )
        );

        $this->end_controls_section();
    }

    /**
     * Register Tooltip Style Controls
     *
     * @since 2.2.5
     * @return void
     */
    protected function register_style_tooltip_controls() {

        $this->start_controls_section(
            'section_tooltips_style',
            [
                'label'     => __( 'Tooltip', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_tooltip' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tooltip_bg_color',
            [
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '.meafe-tooltip.meafe-tooltip-{{ID}} .meafe-tooltip-content' => 'background-color: {{VALUE}};',
                    '.meafe-tooltip.meafe-tooltip-{{ID}}.tt-top .meafe-tooltip-callout:after'    => 'border-top-color: {{VALUE}};',
                    '.meafe-tooltip.meafe-tooltip-{{ID}}.tt-bottom .meafe-tooltip-callout:after' => 'border-bottom-color: {{VALUE}};',
                    '.meafe-tooltip.meafe-tooltip-{{ID}}.tt-left .meafe-tooltip-callout:after'   => 'border-left-color: {{VALUE}};',
                    '.meafe-tooltip.meafe-tooltip-{{ID}}.tt-right .meafe-tooltip-callout:after'  => 'border-right-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_tooltip' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tooltip_color',
            [
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '.meafe-tooltip.meafe-tooltip-{{ID}} .meafe-tooltip-content' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_tooltip' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tooltip_width',
            [
                'label'     => __( 'Width', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min'  => 50,
                        'max'  => 400,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '.meafe-tooltip.meafe-tooltip-{{ID}}' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'render_type'        => 'template',
                'condition' => [
                    'show_tooltip' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'tooltip_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '.meafe-tooltip.meafe-tooltip-{{ID}}',
                'condition' => [
                    'show_tooltip' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'tooltip_border',
                'label'       => __( 'Border', 'mega-elements-addons-for-elementor' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '.meafe-tooltip.meafe-tooltip-{{ID}} .meafe-tooltip-content',
                'condition'   => [
                    'show_tooltip' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tooltip_border_radius',
            [
                'label'      => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '.meafe-tooltip.meafe-tooltip-{{ID}} .meafe-tooltip-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'show_tooltip' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'tooltip_padding',
            [
                'label'      => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '.meafe-tooltip.meafe-tooltip-{{ID}} .meafe-tooltip-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'  => [
                    'show_tooltip' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'tooltip_box_shadow',
                'selector'  => '.meafe-tooltip.meafe-tooltip-{{ID}} .meafe-tooltip-content',
                'condition' => [
                    'show_tooltip' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tooltip_icon_style_heading',
            [
                'label'     => __( 'Tooltip Icon', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_tooltip'       => 'yes',
                    'tooltip_display_on' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'tooltip_icon_color',
            [
                'label'     => __( 'Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-pricing-table-features .meafe-pricing-table-tooltip-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_tooltip'       => 'yes',
                    'tooltip_display_on' => 'icon',
                ],
            ]
        );

        $this->add_responsive_control(
            'tooltip_icon_size',
            [
                'label'      => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'   => 5,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units' => [ 'px', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .meafe-pricing-table-features .meafe-pricing-table-tooltip-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition'  => [
                    'show_tooltip'       => 'yes',
                    'tooltip_display_on' => 'icon',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_ribbon_controls() {
        /**
         * Style Tab: Ribbon
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_table_ribbon_style',
            array(
                'label' => __( 'Ribbon', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'ribbon_bg_color',
            array(
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-ribbon .meafe-pricing-table-ribbon-inner' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-pricing-table-ribbon.meafe-pricing-table-ribbon-3 .meafe-pricing-table-ribbon-inner:after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-pricing-table-ribbon.meafe-pricing-table-ribbon-3.meafe-pricing-table-ribbon-center .meafe-pricing-table-ribbon-inner::before' => 'border-color: {{VALUE}}',
                    
                ),
            )
        );

        $this->add_control(
            'ribbon_text_color',
            array(
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-ribbon .meafe-pricing-table-ribbon-inner' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'ribbon_typography',
                'selector' => '{{WRAPPER}} .meafe-pricing-table-ribbon .meafe-pricing-table-ribbon-inner',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .meafe-pricing-table-ribbon .meafe-pricing-table-ribbon-inner',
            )
        );

        $this->end_controls_section();
    }

    protected function register_style_button_controls() {
        /**
         * Style Tab: Button
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_table_button_style',
            array(
                'label'     => __( 'Button', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'table_button_text!' => '',
                ),
            )
        );

        $this->add_control(
            'table_button_size',
            array(
                'label'     => __( 'Size', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'xl',
                'options'   => array(
                    'sm' => __( 'Small', 'mega-elements-addons-for-elementor' ),
                    'md' => __( 'Medium', 'mega-elements-addons-for-elementor' ),
                    'lg' => __( 'Large', 'mega-elements-addons-for-elementor' ),
                    'xl' => __( 'Extra Large', 'mega-elements-addons-for-elementor' ),
                ),
                'condition' => array(
                    'table_button_text!' => '',
                ),
            )
        );

        $this->add_responsive_control(
            'button_spacing',
            array(
                'label'      => __( 'Spacing', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => array(
                    'size' => 20,
                    'unit' => 'px',
                ),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ),
                ),
                'size_units' => array( 'px' ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-button-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition'  => array(
                    'table_button_text!'    => '',
                    'table_button_position' => 'above',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            array(
                'label'     => __( 'Normal', 'mega-elements-addons-for-elementor' ),
                'condition' => array(
                    'table_button_text!' => '',
                ),
            )
        );

        $this->add_control(
            'button_bg_color_normal',
            array(
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-button' => 'background-color: {{VALUE}}',
                ),
                'condition' => array(
                    'table_button_text!' => '',
                ),
            )
        );

        $this->add_control(
            'button_text_color_normal',
            array(
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'condition' => array(
                    'table_button_text!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-button' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'button_border_normal',
                'label'       => __( 'Border', 'mega-elements-addons-for-elementor' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'condition'   => array(
                    'table_button_text!' => '',
                ),
                'selector'    => '{{WRAPPER}} .meafe-pricing-table-button',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'button_typography',
                'label'     => __( 'Typography', 'mega-elements-addons-for-elementor' ),
                'condition' => array(
                    'table_button_text!' => '',
                ),
                'selector'  => '{{WRAPPER}} .meafe-pricing-table-button',
            )
        );

        $this->add_responsive_control(
            'table_button_padding',
            array(
                'label'      => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'condition'  => array(
                    'table_button_text!' => '',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'button_border_radius',
            array(
                'label'      => __( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'  => array(
                    'table_button_text!' => '',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'pa_pricing_table_button_shadow',
                'selector'  => '{{WRAPPER}} .meafe-pricing-table-button',
                'condition' => array(
                    'table_button_text!' => '',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            array(
                'label'     => __( 'Hover', 'mega-elements-addons-for-elementor' ),
                'condition' => array(
                    'table_button_text!' => '',
                ),
            )
        );

        $this->add_control(
            'button_bg_color_hover',
            array(
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'condition' => array(
                    'table_button_text!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-button:hover' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'button_text_color_hover',
            array(
                'label'     => __( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'condition' => array(
                    'table_button_text!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-button:hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'button_border_hover',
                'label'       => __( 'Border', 'mega-elements-addons-for-elementor' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'condition'   => array(
                    'table_button_text!' => '',
                ),
                'selector'    => '{{WRAPPER}} .meafe-pricing-table-button:hover',
            )
        );

        $this->add_control(
            'button_hover_animation',
            array(
                'label'     => __( 'Animation', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HOVER_ANIMATION,
                'condition' => array(
                    'table_button_text!' => '',
                ),
            )
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function register_style_footer_controls() {
        /**
         * Style Tab: Footer
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_table_footer_style',
            array(
                'label' => __( 'Footer', 'mega-elements-addons-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'table_footer_bg_color',
            array(
                'label'     => __( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .meafe-pricing-table-footer' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'table_footer_padding',
            array(
                'label'      => __( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .meafe-pricing-table-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

    }

    private function get_currency_symbol( $symbol_name ) {
        $symbols = array(
            'dollar'       => '&#36;',
            'euro'         => '&#128;',
            'franc'        => '&#8355;',
            'pound'        => '&#163;',
            'ruble'        => '&#8381;',
            'shekel'       => '&#8362;',
            'baht'         => '&#3647;',
            'yen'          => '&#165;',
            'won'          => '&#8361;',
            'guilder'      => '&fnof;',
            'peso'         => '&#8369;',
            'peseta'       => '&#8359',
            'lira'         => '&#8356;',
            'rupee'        => '&#8360;',
            'indian_rupee' => '&#8377;',
            'real'         => 'R$',
            'krona'        => 'kr',
        );
        return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
    }

    /**
     * Render pricing table widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @access protected
     */
    protected function get_tooltip_attributes( $item, $tooltip_key ) {
        $settings = $this->get_settings_for_display();
        $tooltip_position = 'tt-' . $settings['tooltip_position'];

        $this->add_render_attribute(
            $tooltip_key,
            array(
                'class'                 => 'meafe-pricing-table-tooptip',
                'data-tooltip'          => esc_attr($item['tooltip_content']),
                'data-tooltip-position' => esc_attr($tooltip_position),
                'data-tooltip-size'     => esc_attr($settings['tooltip_size']),
            )
        );

        if ( $settings['tooltip_width'] ) {
            $this->add_render_attribute( $tooltip_key, 'data-tooltip-width', esc_attr($settings['tooltip_width']['size']) );
        }
    }
    /**
     * Render pricing table widget price on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @access protected
     */
    protected function get_price_content( $settings, $symbol, $fraction, $intvalue ) { ?>
        <div class="meafe-pricing-table-price-wrap">
            <div class="meafe-pricing-table-price">
                <?php if ( 'yes' === $settings['discount'] && $settings['table_original_price'] ) { ?>
                    <span class="meafe-pricing-table-price-original">
                        <?php
                        if ( $symbol && 'after' === $settings['currency_position'] ) {
                            echo esc_html($settings['table_original_price']) . $symbol;
                        } else {
                            echo $symbol . esc_html($settings['table_original_price']);
                        }
                        ?>
                    </span>
                <?php } ?>
                <?php if ( $symbol && ( 'before' === $settings['currency_position'] || '' === $settings['currency_position'] ) ) { ?>
                    <span class="meafe-pricing-table-price-prefix">
                        <?php echo $symbol; ?>
                    </span>
                <?php } ?>
                <span <?php echo $this->get_render_attribute_string( 'table_price' ); ?>>
                    <span class="meafe-pricing-table-integer-part">
                        <?php echo esc_html($intvalue); ?>
                    </span>
                    <?php if ( $fraction ) { ?>
                        <span class="meafe-pricing-table-after-part">
                            <?php echo esc_html($fraction); ?>
                        </span>
                    <?php } ?>
                </span>
                <?php if ( $symbol && 'after' === $settings['currency_position'] ) { ?>
                    <span class="meafe-pricing-table-price-prefix">
                        <?php echo $symbol; ?>
                    </span>
                <?php } ?>
                <?php if ( $settings['table_duration'] ) { ?>
                    <span <?php echo $this->get_render_attribute_string( 'table_duration' ); ?>>
                        <?php echo esc_html($settings['table_duration']); ?>
                    </span>
                <?php } ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render pricing table widget title on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @access protected
     */
    protected function get_price_title( $settings, $symbol, $has_icon, $is_new ) { ?>
        <div class="meafe-pricing-table-head">
            <?php if ( 'none' !== $settings['icon_type'] ) { ?>
                <div class="meafe-pricing-table-icon-wrap">
                    <?php if ( 'icon' === $settings['icon_type'] && $has_icon ) { ?>
                        <span class="meafe-pricing-table-icon meafe-icon">
                            <?php
                            if ( $is_new || $migrated ) {
                                Icons_Manager::render_icon( $settings['select_table_icon'], [ 'aria-hidden' => 'true' ] );
                            } elseif ( ! empty( $settings['table_icon'] ) ) {
                                ?><i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i><?php
                            }
                            ?>
                        </span>
                    <?php } elseif ( 'image' === $settings['icon_type'] ) { ?>
                        <?php $image = $settings['icon_image'];
                        if ( $image['url'] ) { ?>
                            <span class="meafe-pricing-table-icon meafe-pricing-table-icon-image">
                                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'icon_image' ); ?>
                            </span>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="meafe-pricing-table-title-wrap">
                <?php if ( $settings['table_title'] ) { ?>
                    <h3 <?php echo $this->get_render_attribute_string( 'table_title' ); ?>>
                        <?php echo esc_html($settings['table_title']); ?>
                    </h3>
                <?php } ?>
                <?php if ( $settings['table_subtitle'] && $settings['pricing_table_layouts'] != '2' ) { ?>
                    <h4 <?php echo $this->get_render_attribute_string( 'table_subtitle' ); ?>>
                        <?php echo wp_kses_post($settings['table_subtitle']); ?>
                    </h4>
                <?php } ?>
            </div>
        </div>
        <?php
    }
    /**
     * Render pricing table widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $symbol = '';

        if ( ! empty( $settings['currency_symbol'] ) ) {
            if ( 'custom' !== $settings['currency_symbol'] ) {
                $symbol = $this->get_currency_symbol( $settings['currency_symbol'] );
            } else {
                $symbol = $settings['currency_symbol_custom'];
            }
        }

        if ( ! isset( $settings['table_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
            // add old default
            $settings['table_icon'] = 'fa fa-star';
        }

        $has_icon = ! empty( $settings['table_icon'] );

        if ( $has_icon ) {
            $this->add_render_attribute( 'i', 'class', esc_attr($settings['table_icon']) );
            $this->add_render_attribute( 'i', 'aria-hidden', 'true' );
        }

        if ( ! $has_icon && ! empty( $settings['select_table_icon']['value'] ) ) {
            $has_icon = true;
        }
        $migrated = isset( $settings['__fa4_migrated']['select_table_icon'] );
        $is_new = ! isset( $settings['table_icon'] ) && Icons_Manager::is_migration_allowed();

        $this->add_inline_editing_attributes( 'table_title', 'none' );
        $this->add_render_attribute( 'table_title', 'class', 'meafe-pricing-table-title' );

        $this->add_inline_editing_attributes( 'table_subtitle', 'none' );
        $this->add_render_attribute( 'table_subtitle', 'class', 'meafe-pricing-table-subtitle' );

        $this->add_render_attribute( 'table_price', 'class', 'meafe-pricing-table-price-value' );

        $this->add_inline_editing_attributes( 'table_duration', 'none' );
        $this->add_render_attribute( 'table_duration', 'class', 'meafe-pricing-table-price-duration' );

        $this->add_inline_editing_attributes( 'table_additional_info', 'none' );
        $this->add_render_attribute( 'table_additional_info', 'class', 'meafe-pricing-table-additional-info' );

        $this->add_render_attribute( 'pricing-table', 'class', 'meafe-pricing-table' );

        $this->add_render_attribute( 'feature-list-item', 'class', '' );

        $this->add_inline_editing_attributes( 'table_button_text', 'none' );

        $this->add_render_attribute( 'table_button_text', 'class', [
            'meafe-pricing-table-button',
            'elementor-button',
            'elementor-size-' . esc_attr($settings['table_button_size']),
        ] );

        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_link_attributes( 'table_button_text', $settings['link'] );
        }

        $this->add_render_attribute( 'pricing-table-duration', 'class', 'meafe-pricing-table-price-duration' );
        if ( 'wrap' === $settings['duration_position'] ) {
            $this->add_render_attribute( 'pricing-table-duration', 'class', 'next-line' );
        }

        if ( $settings['button_hover_animation'] ) {
            $this->add_render_attribute( 'table_button_text', 'class', 'elementor-animation-' . esc_attr($settings['button_hover_animation']) );
        }

        if ( 'raised' === $settings['currency_format'] ) {
            $price = explode( '.', $settings['table_price'] );
            $intvalue = $price[0];
            $fraction = '';
            if ( 2 === count( $price ) ) {
                $fraction = $price[1];
            }
        } else {
            $intvalue = $settings['table_price'];
            $fraction = '';
        }
        $allowedOptions = ['1', '2', '3', '4'];
        $layouts_safe = in_array($settings['pricing_table_layouts'], $allowedOptions) ? $settings['pricing_table_layouts'] : '1';
        ?>
        <div class="meafe-pricing-table-container layout-<?php echo esc_attr($layouts_safe); ?>">
            <div <?php echo $this->get_render_attribute_string( 'pricing-table' ); ?>>
                <?php if( $settings['pricing_table_layouts'] == '2' ) echo '<div class="meafe-pricing-table-price-title-wrap">' ?>
                <?php if( $settings['pricing_table_layouts'] == '3' ) $this->get_price_content( $settings, $symbol, $fraction, $intvalue ); ?>
                <?php $this->get_price_title( $settings, $symbol, $has_icon, $is_new ); ?>
                <?php if( $settings['pricing_table_layouts'] != '3' ) $this->get_price_content( $settings, $symbol, $fraction, $intvalue ); ?>
                <?php if( $settings['pricing_table_layouts'] == '2' ) echo '</div>' ?>
                <?php if ( $settings['table_subtitle'] && $settings['pricing_table_layouts'] == '2' ) { ?>
                    <h4 <?php echo $this->get_render_attribute_string( 'table_subtitle' ); ?>>
                        <?php echo wp_kses_post($settings['table_subtitle']); ?>
                    </h4>
                <?php } ?>
                <?php if ( 'above' === $settings['table_button_position'] ) { ?>
                    <div class="meafe-pricing-table-button-wrap">
                        <?php if ( $settings['table_button_text'] ) { ?>
                            <a <?php echo $this->get_render_attribute_string( 'table_button_text' ); ?>>
                                <?php echo esc_html($settings['table_button_text']); ?>
                            </a>
                        <?php } ?>
                    </div>
                <?php } ?>
                <ul class="meafe-pricing-table-features">
                    <?php if ( $settings['table_additional_info'] ) { ?>
                        <div <?php echo $this->get_render_attribute_string( 'table_additional_info' ); ?>>
                            <?php echo $this->parse_text_editor( $settings['table_additional_info'] ); ?>
                        </div>
                    <?php } ?>
                    <?php foreach ( $settings['table_features'] as $index => $item ) : ?>
                        <?php
                        $fallback_defaults = [
                            'fa fa-check',
                            'fa fa-times',
                            'fa fa-dot-circle-o',
                        ];

                        $migration_allowed = Icons_Manager::is_migration_allowed();

                        // add old default
                        if ( ! isset( $item['feature_icon'] ) && ! $migration_allowed ) {
                            $item['feature_icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
                        }

                        $migrated = isset( $item['__fa4_migrated']['select_feature_icon'] );
                        $is_new = ! isset( $item['feature_icon'] ) && $migration_allowed;

                        $feature_list_key = $this->get_repeater_setting_key( 'feature_list_key', 'table_features', $index );
                        $this->add_render_attribute( $feature_list_key, 'class', 'elementor-repeater-item-' . $item['_id'] );

                        $feature_content_key = $this->get_repeater_setting_key( 'feature_content_key', 'table_features', $index );
                        $this->add_render_attribute( $feature_content_key, 'class', 'meafe-pricing-table-feature-content' );

                        $tooltip_icon_key = $this->get_repeater_setting_key( 'tooltip_icon_key', 'table_features', $index );
                        $this->add_render_attribute( $tooltip_icon_key, 'class', 'meafe-pricing-table-tooltip-icon' );

                        if ( 'yes' === $settings['show_tooltip'] && $item['tooltip_content'] ) {
                            if ( 'text' === $settings['tooltip_display_on'] ) {
                                $this->get_tooltip_attributes( $item, $feature_content_key );
                                if ( 'click' === $settings['tooltip_trigger'] ) {
                                    $this->add_render_attribute( $feature_content_key, 'class', 'meafe-tooltip-click' );
                                }
                            } else {
                                $this->get_tooltip_attributes( $item, $tooltip_icon_key );
                                if ( 'click' === $settings['tooltip_trigger'] ) {
                                    $this->add_render_attribute( $tooltip_icon_key, 'class', 'meafe-tooltip-click' );
                                }
                            }
                        }

                        $feature_key = $this->get_repeater_setting_key( 'feature_text', 'table_features', $index );
                        $this->add_render_attribute( $feature_key, 'class', 'meafe-pricing-table-feature-text' );
                        $this->add_inline_editing_attributes( $feature_key, 'none' );

                        if ( 'yes' === $item['exclude'] ) {
                            $this->add_render_attribute( $feature_list_key, 'class', 'excluded' );
                        }
                        ?>
                        <li <?php echo $this->get_render_attribute_string( $feature_list_key ); ?>>
                            <div <?php echo $this->get_render_attribute_string( $feature_content_key ); ?>>
                                <?php
                                if ( ! empty( $item['select_feature_icon'] ) || ( ! empty( $item['feature_icon']['value'] ) && $is_new ) ) :
                                    echo '<span class="meafe-pricing-table-fature-icon meafe-icon">';
                                    if ( $is_new || $migrated ) {
                                        Icons_Manager::render_icon( $item['select_feature_icon'], [ 'aria-hidden' => 'true' ] );
                                    } else { ?>
                                        <i class="<?php echo esc_attr($item['feature_icon']); ?>" aria-hidden="true"></i>
                                        <?php
                                    }
                                    echo '</span>';
                                    endif;
                                ?>
                                <?php if ( $item['feature_text'] ) { ?>
                                    <span <?php echo $this->get_render_attribute_string( $feature_key ); ?>>
                                        <?php echo esc_html($item['feature_text']); ?>
                                    </span>
                                <?php } ?>
                                <?php if ( 'yes' === $settings['show_tooltip'] && 'icon' === $settings['tooltip_display_on'] && $item['tooltip_content'] ) { ?>
                                    <span <?php echo $this->get_render_attribute_string( $tooltip_icon_key ); ?>>
                                        <?php \Elementor\Icons_Manager::render_icon( $settings['tooltip_icon'], array( 'aria-hidden' => 'true' ) ); ?>
                                    </span>
                                <?php } ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="meafe-pricing-table-footer">
                    <?php if ( 'below' === $settings['table_button_position'] ) { ?>
                        <?php if ( $settings['table_button_text'] ) { ?>
                            <a <?php echo $this->get_render_attribute_string( 'table_button_text' ); ?>>
                                <?php echo esc_html($settings['table_button_text']); ?>
                            </a>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <?php if ( 'yes' === $settings['show_ribbon'] && $settings['ribbon_title'] ) { ?>
                <?php
                    $classes = [
                        'meafe-pricing-table-ribbon',
                        'meafe-pricing-table-ribbon-' . esc_attr($settings['ribbon_style']),
                        'meafe-pricing-table-ribbon-' . esc_attr($settings['ribbon_position']),
                    ];
                    $this->add_render_attribute( 'ribbon', 'class', $classes );
                    ?>
                <div <?php echo $this->get_render_attribute_string( 'ribbon' ); ?>>
                    <div class="meafe-pricing-table-ribbon-inner">
                        <div class="meafe-pricing-table-ribbon-title">
                            <?php echo esc_html($settings['ribbon_title']); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php
    }

    /**
     * Render pricing table widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @access protected
     */
    protected function get_content_template() {
        ?>
        <#
            var buttonClasses = 'meafe-pricing-table-button elementor-button elementor-size-' + settings.table_button_size + ' elementor-animation-' + settings.button_hover_animation;
           
            var $i = 1,
                symbols = {
                    dollar: '&#36;',
                    euro: '&#128;',
                    franc: '&#8355;',
                    pound: '&#163;',
                    ruble: '&#8381;',
                    shekel: '&#8362;',
                    baht: '&#3647;',
                    yen: '&#165;',
                    won: '&#8361;',
                    guilder: '&fnof;',
                    peso: '&#8369;',
                    peseta: '&#8359;',
                    lira: '&#8356;',
                    rupee: '&#8360;',
                    indian_rupee: '&#8377;',
                    real: 'R$',
                    krona: 'kr'
                },
                symbol = '',
                iconHTML = {},
                iconsHTML = {},
                migrated = {},
                iconsMigrated = {},
                tooltipIconHTML = {};

            if ( settings.currency_symbol ) {
                if ( 'custom' !== settings.currency_symbol ) {
                    symbol = symbols[ settings.currency_symbol ] || '';
                } else {
                    symbol = settings.currency_symbol_custom;
                }
            }
           
            if ( settings.currency_format == 'raised' ) {
                var table_price = settings.table_price.toString(),
                    price = table_price.split( '.' ),
                    intvalue = price[0],
                    fraction = price[1];
            } else {
                var intvalue = settings.table_price,
                    fraction = '';
            }

            function get_tooltip_attributes( item, toolTipKey ) {
                view.addRenderAttribute( toolTipKey, 'class', 'meafe-pricing-table-tooptip' );
                view.addRenderAttribute( toolTipKey, 'data-tooltip', item.tooltip_content );
                view.addRenderAttribute( toolTipKey, 'data-tooltip-position', 'tt-' + settings.tooltip_position );
                view.addRenderAttribute( toolTipKey, 'data-tooltip-size', settings.tooltip_size );

                if ( settings.tooltip_width.size ) {
                    view.addRenderAttribute( toolTipKey, 'data-tooltip-width', settings.tooltip_width.size );
                }
            }

            function get_price_content( settings ) { #>
                <div class="meafe-pricing-table-price-wrap">
                    <div class="meafe-pricing-table-price">
                        <# if ( settings.discount === 'yes' && settings.table_original_price > 0 ) { #>
                            <span class="meafe-pricing-table-price-original">
                                <# if ( ! _.isEmpty( symbol ) && 'after' == settings.currency_position ) { #>
                                    {{{ settings.table_original_price + symbol }}}
                                <# } else { #>
                                    {{{ symbol + settings.table_original_price }}}
                                <# } #>
                            </span>
                        <# } #>
                        <# if ( ! _.isEmpty( symbol ) && ( 'before' == settings.currency_position || _.isEmpty( settings.currency_position ) ) ) { #>
                            <span class="meafe-pricing-table-price-prefix">{{{ symbol }}}</span>
                        <# } #>
                        <span class="meafe-pricing-table-price-value">
                            <span class="meafe-pricing-table-integer-part">
                                {{{ intvalue }}}
                            </span>
                            <# if ( fraction ) { #>
                                <span class="meafe-pricing-table-after-part">
                                    {{{ fraction }}}
                                </span>
                            <# } #>
                        </span>
                        <# if ( ! _.isEmpty( symbol ) && 'after' == settings.currency_position ) { #>
                            <span class="meafe-pricing-table-price-prefix">{{{ symbol }}}</span>
                        <# } #>
                        <# if ( settings.table_duration ) { #>
                            <span class="meafe-pricing-table-price-duration elementor-inline-editing" data-elementor-setting-key="table_duration" data-elementor-inline-editing-toolbar="none">
                                {{{ settings.table_duration }}}
                            </span>
                        <# } #>
                    </div>
                </div>
            <# }

            view.addRenderAttribute( 'table_icon', 'class', settings.table_icon );
            
            var iconHTML = elementor.helpers.renderIcon( view, settings.select_table_icon, { 'aria-hidden': true }, 'i' , 'object' ),
            migrated = elementor.helpers.isIconMigrated( settings, 'select_table_icon' );
            var allowedLayouts = ['1', '2', '3', '4'];
            function validateSelectOptions(option) {
                return allowedLayouts.some(element => element === option) ? option : '1';
            }
        #>
        <div class="meafe-pricing-table-container layout-{{{validateSelectOptions(settings.pricing_table_layouts)}}}">
            <div class="meafe-pricing-table">
                <# if( settings.pricing_table_layouts == '2' ) { #>
                    <div class="meafe-pricing-table-price-title-wrap">
                <# } #>

                <# if( settings.pricing_table_layouts == '3' ) {
                    get_price_content( settings );
                } #>

                <div class="meafe-pricing-table-head">
                    <# if ( settings.icon_type != 'none' ) { #>
                        <div class="meafe-pricing-table-icon-wrap">
                            <# if ( settings.icon_type == 'icon' ) { #>
                                <# if ( settings.table_icon || settings.select_table_icon ) { #>
                                    <span class="meafe-pricing-table-icon meafe-icon">
                                        <# if ( iconHTML && iconHTML.rendered && ( ! settings.table_icon || migrated ) ) { #>
                                            {{{ iconHTML.value }}}
                                        <# } else { #>
                                            <i {{{ view.getRenderAttributeString( 'table_icon' ) }}} aria-hidden="true"></i>
                                        <# } #>
                                    </span>
                                <# } #>
                            <# } else if ( settings.icon_type == 'image' ) { #>
                                <span class="meafe-pricing-table-icon meafe-pricing-table-icon-image">
                                    <# if ( settings.icon_image.url != '' ) { #>
                                        <#
                                        var image = {
                                            id: settings.icon_image.id,
                                            url: settings.icon_image.url,
                                            size: settings.image_size,
                                            dimension: settings.image_custom_dimension,
                                            model: view.getEditModel()
                                        };
                                        var image_url = elementor.imagesManager.getImageUrl( image );
                                        #>
                                        <img src="{{{ image_url }}}" />
                                    <# } #>
                                </span>
                            <# } #>
                        </div>
                    <# } #>
                    <div class="meafe-pricing-table-title-wrap">
                        <# if ( settings.table_title ) { #>
                            <h3 class="meafe-pricing-table-title elementor-inline-editing" data-elementor-setting-key="table_title" data-elementor-inline-editing-toolbar="none">
                                {{{ settings.table_title }}}
                            </h3>
                        <# } #>
                        <# if ( settings.table_subtitle && settings.pricing_table_layouts != '2' ) { #>
                            <h4 class="meafe-pricing-table-subtitle elementor-inline-editing" data-elementor-setting-key="table_subtitle" data-elementor-inline-editing-toolbar="none">
                                {{{ settings.table_subtitle }}}
                            </h4>
                        <# } #>
                    </div>
                </div>

                <# if( settings.pricing_table_layouts != '3' ) {
                    get_price_content( settings );
                } #>
                
                <# if( settings.pricing_table_layouts == '2' ) { #>
                    </div>
                <# } #>

                <# if ( settings.table_subtitle && settings.pricing_table_layouts == '2' ) { #>
                    <h4 class="meafe-pricing-table-subtitle elementor-inline-editing" data-elementor-setting-key="table_subtitle" data-elementor-inline-editing-toolbar="none">
                        {{{ settings.table_subtitle }}}
                    </h4>
                <# } #>

                <# if ( settings.table_button_position == 'above' ) { #>
                    <div class="meafe-pricing-table-button-wrap">
                        <#
                        if ( settings.table_button_text ) {
                        var button_text = settings.table_button_text;

                        view.addRenderAttribute( 'table_button_text', 'class', buttonClasses );

                        view.addInlineEditingAttributes( 'table_button_text' );

                        var button_text_html = '<a ' + 'href="' + settings.link.url + '"' + view.getRenderAttributeString( 'table_button_text' ) + '>' + button_text + '</a>';

                        print( button_text_html );
                        }
                        #>
                    </div>
                <# } #>
                <ul class="meafe-pricing-table-features">
                    <#
                    if ( settings.table_additional_info ) {
                    var additional_info_text = settings.table_additional_info;

                    view.addRenderAttribute( 'table_additional_info', 'class', 'meafe-pricing-table-additional-info' );

                    view.addInlineEditingAttributes( 'table_additional_info' );

                    var additional_info_text_html = '<div ' + view.getRenderAttributeString( 'table_additional_info' ) + '>' + additional_info_text + '</div>';

                    print( additional_info_text_html );
                    } #>
                    <# var i = 1; #>
                    <# _.each( settings.table_features, function( item, index ) {
                        var featureContentKey = view.getRepeaterSettingKey( 'feature_content_key', 'table_features', index );
                        view.addRenderAttribute( featureContentKey, 'class', 'meafe-pricing-table-feature-content' );

                        var tooltipIconKey = view.getRepeaterSettingKey( 'tooltip_icon_key', 'table_features', index );
                        view.addRenderAttribute( tooltipIconKey, 'class', 'meafe-pricing-table-tooltip-icon' );

                        if ( 'yes' === settings.show_tooltip && item.tooltip_content ) {
                            if ( 'text' === settings.tooltip_display_on ) {
                                get_tooltip_attributes( item, featureContentKey );
                                if ( 'click' === settings.tooltip_trigger ) {
                                    view.addRenderAttribute( featureContentKey, 'class', 'meafe-tooltip-click' );
                                }
                            } else {
                                get_tooltip_attributes( item, tooltipIconKey );
                                if ( 'click' === settings.tooltip_trigger ) {
                                    view.addRenderAttribute( tooltipIconKey, 'class', 'meafe-tooltip-click' );
                                }
                            }
                        } #>
                        <li class="elementor-repeater-item-{{ item._id }} <# if ( item.exclude == 'yes' ) { #> excluded <# } #>">
                            <div {{{ view.getRenderAttributeString( featureContentKey ) }}}>
                                <# if ( item.select_feature_icon || item.feature_icon.value ) { #>
                                    <span class="meafe-pricing-table-fature-icon meafe-icon">
                                    <#
                                        iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.select_feature_icon, { 'aria-hidden': true }, 'i', 'object' );
                                        iconsMigrated[ index ] = elementor.helpers.isIconMigrated( item, 'select_feature_icon' );
                                        if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.feature_icon || iconsMigrated[ index ] ) ) { #>
                                            {{{ iconsHTML[ index ].value }}}
                                        <# } else { #>
                                            <i class="{{ item.feature_icon }}" aria-hidden="true"></i>
                                        <# }
                                    #>
                                    </span>
                                <# } #>

                                <#
                                    var feature_text = item.feature_text;

                                    view.addRenderAttribute( 'table_features.' + (i - 1) + '.feature_text', 'class', 'meafe-pricing-table-feature-text' );

                                    view.addInlineEditingAttributes( 'table_features.' + (i - 1) + '.feature_text' );

                                    var feature_text_html = '<span' + ' ' + view.getRenderAttributeString( 'table_features.' + (i - 1) + '.feature_text' ) + '>' + feature_text + '</span>';

                                    print( feature_text_html );
                                #>

                                <#
                                if ( 'yes' === settings.show_tooltip && 'icon' === settings.tooltip_display_on && item.tooltip_content ) {
                                    tooltipIconHTML = elementor.helpers.renderIcon( view, settings.tooltip_icon, { 'aria-hidden': true }, 'i', 'object' );
                                    var tooltip_icon_html = '<span' + ' ' + view.getRenderAttributeString( tooltipIconKey ) + '>' + tooltipIconHTML.value + '</span>';

                                    print( tooltip_icon_html );
                                }
                                #>
                            </div>
                        </li>
                    <# i++ } ); #>
                </ul>
                <div class="meafe-pricing-table-footer">
                    <#
                    if ( settings.table_button_position == 'below' ) {
                        if ( settings.table_button_text ) {
                        var button_text = settings.table_button_text;

                        view.addRenderAttribute( 'table_button_text', 'class', buttonClasses );

                        view.addInlineEditingAttributes( 'table_button_text' );

                        var button_text_html = '<a ' + 'href="' + settings.link.url + '"' + view.getRenderAttributeString( 'table_button_text' ) + '>' + button_text + '</a>';

                        print( button_text_html );
                        }
                    }
                    #>
                </div>
            </div>
            <# if ( settings.show_ribbon == 'yes' && settings.ribbon_title != '' ) { #>
                <div class="meafe-pricing-table-ribbon meafe-pricing-table-ribbon-{{ settings.ribbon_style }} meafe-pricing-table-ribbon-{{ settings.ribbon_position }}">
                    <div class="meafe-pricing-table-ribbon-inner">
                        <div class="meafe-pricing-table-ribbon-title">
                            <# print( settings.ribbon_title ); #>
                        </div>
                    </div>
                </div>
            <# } #>
        </div>
        <?php
    }

    /**
     * Render pricing table widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * Remove this after Elementor v3.3.0
     *
     * @since 1.0.0
     * @access protected
     */
    protected function content_template() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
        $this->get_content_template();
    }
}
