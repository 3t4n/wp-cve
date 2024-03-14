<?php

namespace OXIIMAEADDONS\Modules\Image;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager as Controls_Manager;
use Elementor\Group_Control_Background as Group_Control_Background;
use Elementor\Group_Control_Border as Group_Control_Border;
use Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size as Group_Control_Image_Size;
use Elementor\Group_Control_Typography as Group_Control_Typography;
use Elementor\Icons_Manager as Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils as Utils;
use Elementor\Widget_Base as Widget_Base;

/**
 * Description of Image
 *
 * @author biplo
 */
class Image extends Widget_Base {

    public function get_name() {

        return 'oxi_i_addons_image';
    }

    public function get_title() {
        return esc_html__('Image Hover Effects', 'oxi-hover-effects-addons');
    }

    public function get_icon() {
        return ' eicon-image';
    }

    public function get_categories() {

        return ['oxi-h-effects-addons'];
    }

    public function get_keywords() {
        return [
                'image',
                'image hover effects',
                'hover effect',
                'effects',
                'image hover',
        ];
    }

    public function get_style_depends() {


        if (Plugin::$instance->editor->is_edit_mode() || Plugin::$instance->preview->is_preview_mode()) {
            $style = 'cache';
        } else {
            $settings = $this->get_settings_for_display();
            $style    = esc_attr($settings['oxi_addons_effects']);
        }
        $id = 'oxi-i-addons-i-' . $style;
        wp_register_style('oxi-i-addons-i-e', OXIIMAEADDONS_URL . 'Modules/Image/css/index.css');
        wp_register_style($id, OXIIMAEADDONS_URL . 'Modules/Image/css/' . $style . '.css');
        return [
                'oxi-i-addons-i-e',
                $id,
        ];
    }

    public function get_custom_help_url() {
        return 'https://wordpress.org/support/plugin/image-hover-effects-with-carousel/';
    }

    protected function register_controls() {
        $this->init_content_general_controls();
        $this->init_content_overlay_controls();
        $this->init_content_content_controls();
        $this->init_content_url_controls();
        $this->init_content_promotion_controls();

        $this->init_style_general_controls();
        $this->init_style_content_controls();
        $this->init_style_title_controls();
        $this->init_style_description_controls();
        $this->init_style_icon_controls();
        $this->init_style_button_controls();
    }

    protected function init_content_general_controls() {
        $this->start_controls_section(
                'oxi_addons_c_g_section',
                [
                        'label' => esc_html__('Effects & Image', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_CONTENT,
                ],
        );
        $this->add_control(
                'oxi_addons_effects',
                [
                        'label'              => esc_html__('Effects Name', 'oxi-hover-effects-addons'),
                        'type'               => Controls_Manager::SELECT,
                        'frontend_available' => true,
                        'options'            => [
                                'oxi_image_e_e_01' => esc_html__('Effects 01', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_02' => esc_html__('Effects 02', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_03' => esc_html__('Effects 03', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_04' => esc_html__('Effects 04', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_05' => esc_html__('Effects 05', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_06' => esc_html__('Effects 06', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_07' => esc_html__('Effects 07', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_08' => esc_html__('Effects 08', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_09' => esc_html__('Effects 09', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_10' => esc_html__('Effects 10', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_11' => esc_html__('Effects 11', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_12' => esc_html__('Effects 12', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_13' => esc_html__('Effects 13', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_14' => esc_html__('Effects 14', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_15' => esc_html__('Effects 15', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_16' => esc_html__('Effects 16', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_17' => esc_html__('Effects 17', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_18' => esc_html__('Effects 18', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_19' => esc_html__('Effects 19', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_20' => esc_html__('Effects 20', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_21' => esc_html__('Effects 21', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_22' => esc_html__('Effects 22', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_23' => esc_html__('Effects 23', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_24' => esc_html__('Effects 24', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_25' => esc_html__('Effects 25', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_26' => esc_html__('Effects 26', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_27' => esc_html__('Effects 27', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_28' => esc_html__('Effects 28', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_29' => esc_html__('Effects 29', 'oxi-hover-effects-addons'),
                                'oxi_image_e_e_30' => esc_html__('Effects 30', 'oxi-hover-effects-addons'),
                        ],
                        'default'            => 'oxi_image_e_e_01',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_02',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'top_to_bottom'       => esc_html__('Top & Bottom', 'oxi-hover-effects-addons'),
                                'left_to_right'       => esc_html__('Left & Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom_c'     => esc_html__('Top & Bottom C', 'oxi-hover-effects-addons'),
                                'left_to_right_c'     => esc_html__('Left & Right C', 'oxi-hover-effects-addons'),
                                'corner-left-top'     => esc_html__('Corner Left Top', 'oxi-hover-effects-addons'),
                                'corner-top-right'    => esc_html__('Corner Top Right', 'oxi-hover-effects-addons'),
                                'corner-right-bottom' => esc_html__('Corner Right Bottom', 'oxi-hover-effects-addons'),
                                'corner-bottom-left'  => esc_html__('Corner Bottom Left', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_02',
                        ],
                        'default'   => 'top_to_bottom',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_03',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_03',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_04',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_04',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_05',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_05',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_06',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_06',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_07',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_07',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_08',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'scale_up'      => esc_html__('Scale Up', 'oxi-hover-effects-addons'),
                                'scale_down'    => esc_html__('Scale Down', 'oxi-hover-effects-addons'),
                                'scale_down_up' => esc_html__('Scale Down Up', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_08',
                        ],
                        'default'   => 'scale_up',
                ],
        );

        $this->add_control(
                'oxi_image_e_e_09',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_09',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_10',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_10',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_11',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_11',
                        ],
                        'default'   => 'top_to_bottom',
                ],
        );

        $this->add_control(
                'oxi_image_e_e_12',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_12',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_13',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_13',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_14',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_14',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_15',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_15',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_16',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_16',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_17',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Image Out', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Content In', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_17',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_18',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_18',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_19',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_19',
                        ],
                        'default'   => 'left_to_right',
                ],
        );

        $this->add_control(
                'oxi_image_e_e_20',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_20',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_21',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_21',
                        ],
                        'default'   => 'right_to_left',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_22',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_22',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_23',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_23',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_24',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_24',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_25',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_25',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_26',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_26',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_27',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_27',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_28',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'top_to_bottom' => esc_html__('Top to Bottom', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                                'bottom_to_top' => esc_html__('Bottom to Top', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_28',
                        ],
                        'default'   => 'left_to_right',
                ],
        );
        $this->add_control(
                'oxi_image_e_e_30',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'left_to_right' => esc_html__('Left to Right', 'oxi-hover-effects-addons'),
                                'right_to_left' => esc_html__('Right to Left', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_e_e_30',
                        ],
                        'default'   => 'left_to_right',
                ],
        );

        $this->add_control(
                'oxi_image',
                [
                        'label'     => esc_html__('Choose Image', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::MEDIA,
                        'default'   => [
                                'url' => Utils::get_placeholder_image_src(),
                        ],
                        'dynamic'   => ['active' => true],
                        'separator' => 'before',
                ],
        );

        $this->init_image_animation_effects();

        $this->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                        'name'    => 'oxi_image_thumbnail',
                        'exclude' => ['custom'],
                        'include' => [],
                        'default' => 'full',
                ],
        );
        $this->add_responsive_control(
                'oxi_image_opacity',
                [
                        'label'      => esc_html__('Opacity', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'default'    => [
                                'size' => 1,
                                'unit' => 'px',
                        ],
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 1,
                                        'step' => 0.01,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-effects-style .oxi-image-hover-image-effects' => 'opacity: {{SIZE}};',
                        ],
                ],
        );

        $this->end_controls_section();
    }

    protected function init_image_animation_effects() {
        $this->add_control(
                'oxi_image_hover_effects',
                [
                        'label'   => esc_html__('Image  Animation', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::SELECT,
                        'options' => [
                                ''       => esc_html__('None', 'oxi-hover-effects-addons'),
                                'custom' => esc_html__('Custom', 'oxi-hover-effects-addons'),
                        ],
                        'default' => '',
                ],
        );

        $this->add_control(
                'oxi_image_hover_effects_transform',
                [
                        'label'              => __('Animation', 'oxi-hover-effects-addons'),
                        'type'               => Controls_Manager::POPOVER_TOGGLE,
                        'return_value'       => 'yes',
                        'frontend_available' => true,
                        'condition'          => [
                                'oxi_image_hover_effects' => 'custom',
                        ]
                ],
        );
        $this->start_popover();

        $this->add_responsive_control(
                'oxi_image_hover_effects_translate_x',
                [
                        'label'      => __('Translate X', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => -500,
                                        'max'  => 500,
                                        'step' => 1,
                                ],
                        ],
                        'condition'  => [
                                'oxi_image_hover_effects_transform' => 'yes',
                                'oxi_image_hover_effects'           => 'custom',
                        ],
                        'default'    => [
                                'size' => 0
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-image-custom img' =>
                                '-ms-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-image-custom img'  =>
                                '-ms-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_tablet.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_tablet.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_tablet.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_tablet.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_tablet.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_tablet.SIZE || 1}});',
                                '(mobile){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-image-custom img'  =>
                                '-ms-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_mobile.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_mobile.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_mobile.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_mobile.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_mobile.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_mobile.SIZE || 1}})'
                        ]
                ],
        );
        $this->add_responsive_control(
                'oxi_image_hover_effects_translate_y',
                [
                        'label'      => __('Translate Y', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => -500,
                                        'max'  => 500,
                                        'step' => 1,
                                ],
                        ],
                        'default'    => [
                                'size' => 0
                        ],
                        'condition'  => [
                                'oxi_image_hover_effects_transform' => 'yes',
                                'oxi_image_hover_effects'           => 'custom',
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-image-custom img' =>
                                '-ms-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-image-custom img'  =>
                                '-ms-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_tablet.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_tablet.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_tablet.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_tablet.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_tablet.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_tablet.SIZE || 1}});',
                                '(mobile){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-image-custom img'  =>
                                '-ms-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_mobile.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_mobile.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_mobile.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_mobile.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_mobile.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_mobile.SIZE || 1}})'
                        ]
                ],
        );
        $this->add_responsive_control(
                'oxi_image_hover_effects_rotate',
                [
                        'label'      => __('Rotate', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => -180,
                                        'max'  => 180,
                                        'step' => 1,
                                ],
                        ],
                        'default'    => [
                                'size' => 0
                        ],
                        'condition'  => [
                                'oxi_image_hover_effects_transform' => 'yes',
                                'oxi_image_hover_effects'           => 'custom',
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-image-custom img' =>
                                '-ms-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-image-custom img'  =>
                                '-ms-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_tablet.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_tablet.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_tablet.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_tablet.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_tablet.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_tablet.SIZE || 1}});',
                                '(mobile){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-image-custom img'  =>
                                '-ms-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_mobile.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_mobile.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_mobile.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_mobile.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_mobile.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_mobile.SIZE || 1}})'
                        ]
                ],
        );
        $this->add_responsive_control(
                'oxi_image_hover_effects_scale',
                [
                        'label'      => __('Scale', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'default'    => [
                                'size' => 1
                        ],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 5,
                                        'step' => 0.01,
                                ],
                        ],
                        'condition'  => [
                                'oxi_image_hover_effects_transform' => 'yes',
                                'oxi_image_hover_effects'           => 'custom',
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-image-custom img' =>
                                '-ms-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-image-custom img'  =>
                                '-ms-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_tablet.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_tablet.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_tablet.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_tablet.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_tablet.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_tablet.SIZE || 1}});',
                                '(mobile){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-image-custom img'  =>
                                '-ms-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_mobile.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_mobile.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_mobile.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_mobile.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{oxi_image_hover_effects_translate_x_mobile.SIZE || 0}}px, {{oxi_image_hover_effects_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{oxi_image_hover_effects_rotate_mobile.SIZE || 0}}deg) '
                                . 'scale({{oxi_image_hover_effects_scale_mobile.SIZE || 1}})'
                        ]
                ],
        );
        $this->end_popover();
    }

    protected function init_content_overlay_controls() {


        $this->start_controls_section(
                'oxi_addons_c_o_content',
                [
                        'label' => esc_html__('Overlay Settings', 'oxi-hover-effects-addons'),
                ],
        );
        $this->add_control(
                'oxi_addons_c_o_position',
                [
                        'label'   => esc_html__('Content Position', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::SELECT,
                        'options' => [
                                'inside'  => esc_html__('Inside Effects', 'oxi-hover-effects-addons'),
                                'outside' => esc_html__('Outside Effects', 'oxi-hover-effects-addons'),
                        ],
                        'default' => 'inside',
                ],
        );
        $repeater = new Repeater();

        $repeater->add_control(
                'c_image',
                [
                        'label'     => esc_html__('Choose Image', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::MEDIA,
                        'default'   => [
                                'url' => Utils::get_placeholder_image_src(),
                        ],
                        'dynamic'   => ['active' => true],
                        'separator' => 'before',
                ],
        );

        $repeater->add_group_control(
                Group_Control_Image_Size::get_type(),
                [
                        'name'    => 'c_image_thumbnail',
                        'exclude' => ['custom'],
                        'include' => [],
                        'default' => 'full',
                ],
        );
        $repeater->add_responsive_control(
                'c_image_width',
                [
                        'label'      => esc_html__('Width', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px', 'em', '%'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 1000,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}' => 'max-width: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $repeater->add_responsive_control(
                'c_image_opacity',
                [
                        'label'      => esc_html__('Opacity', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'default'    => [
                                'size' => 1,
                                'unit' => 'px',
                        ],
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 1,
                                        'step' => 0.01,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}} ' => 'opacity: {{SIZE}};',
                        ],
                ],
        );

        $repeater->start_controls_tabs('c_image_tabs');

        $repeater->start_controls_tab(
                'c_image_normal',
                [
                        'label' => esc_html__('Normal', 'oxi-hover-effects-addons')
                ],
        );

        $repeater->add_control(
                'c_image_normal_transform_toggle',
                [
                        'label'        => esc_html__('Transform', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::POPOVER_TOGGLE,
                        'return_value' => 'yes',
                ],
        );

        $repeater->start_popover();

        $repeater->add_responsive_control(
                'c_image_normal_translate_x',
                [
                        'label'      => esc_html__('Translate X', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => -500,
                                        'max'  => 500,
                                        'step' => 1,
                                ],
                        ],
                        'condition'  => [
                                'c_image_normal_transform_toggle' => 'yes',
                        ],
                        'default'    => [
                                'size' => 0
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}' =>
                                '-ms-transform:'
                                . 'translate({{c_image_normal_translate_x.SIZE || 0}}px, {{c_image_normal_translate_y.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate.SIZE || 0}}deg) '
                                . 'scaleX({{c_image_normal_scale.SIZE || 1}}) scaleY({{c_image_normal_scale_y.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{c_image_normal_translate_x.SIZE || 0}}px, {{c_image_normal_translate_y.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate.SIZE || 0}}deg) '
                                . 'scaleX({{c_image_normal_scale.SIZE || 1}}) scaleY({{c_image_normal_scale_y.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{c_image_normal_translate_x.SIZE || 0}}px, {{c_image_normal_translate_y.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate.SIZE || 0}}deg) '
                                . 'scaleX({{c_image_normal_scale.SIZE || 1}}) scaleY({{c_image_normal_scale_y.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}'  =>
                                '-ms-transform:'
                                . 'translate({{c_image_normal_translate_x_tablet.SIZE || 0}}px, {{c_image_normal_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{c_image_normal_scale_tablet.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{c_image_normal_translate_x_tablet.SIZE || 0}}px, {{c_image_normal_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{c_image_normal_scale_tablet.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{c_image_normal_translate_x_tablet.SIZE || 0}}px, {{c_image_normal_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{c_image_normal_scale_tablet.SIZE || 1}})',
                                '(mobile){{WRAPPER}} .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}'  =>
                                '-ms-transform:'
                                . 'translate({{c_image_normal_translate_x_mobile.SIZE || 0}}px, {{c_image_normal_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_mobile.SIZE || 0}}deg)'
                                . 'scale({{c_image_normal_scale_mobile.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{c_image_normal_translate_x_mobile.SIZE || 0}}px, {{c_image_normal_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_mobile.SIZE || 0}}deg)'
                                . 'scale({{c_image_normal_scale_mobile.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{c_image_normal_translate_x_mobile.SIZE || 0}}px, {{c_image_normal_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_mobile.SIZE || 0}}deg)'
                                . 'scale({{c_image_normal_scale_mobile.SIZE || 1}});'
                        ]
                ],
        );
        $repeater->add_responsive_control(
                'c_image_normal_translate_y',
                [
                        'label'      => esc_html__('Translate Y', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => -500,
                                        'max'  => 500,
                                        'step' => 1,
                                ],
                        ],
                        'default'    => [
                                'size' => 0
                        ],
                        'condition'  => [
                                'c_image_normal_transform_toggle' => 'yes',
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}' =>
                                '-ms-transform:'
                                . 'translate({{c_image_normal_translate_x.SIZE || 0}}px, {{c_image_normal_translate_y.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate.SIZE || 0}}deg) '
                                . 'scaleX({{c_image_normal_scale.SIZE || 1}}) scaleY({{c_image_normal_scale_y.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{c_image_normal_translate_x.SIZE || 0}}px, {{c_image_normal_translate_y.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate.SIZE || 0}}deg) '
                                . 'scaleX({{c_image_normal_scale.SIZE || 1}}) scaleY({{c_image_normal_scale_y.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{c_image_normal_translate_x.SIZE || 0}}px, {{c_image_normal_translate_y.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate.SIZE || 0}}deg) '
                                . 'scaleX({{c_image_normal_scale.SIZE || 1}}) scaleY({{c_image_normal_scale_y.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}'  =>
                                '-ms-transform:'
                                . 'translate({{c_image_normal_translate_x_tablet.SIZE || 0}}px, {{c_image_normal_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{c_image_normal_scale_tablet.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{c_image_normal_translate_x_tablet.SIZE || 0}}px, {{c_image_normal_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{c_image_normal_scale_tablet.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{c_image_normal_translate_x_tablet.SIZE || 0}}px, {{c_image_normal_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{c_image_normal_scale_tablet.SIZE || 1}})',
                                '(mobile){{WRAPPER}} .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}'  =>
                                '-ms-transform:'
                                . 'translate({{c_image_normal_translate_x_mobile.SIZE || 0}}px, {{c_image_normal_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_mobile.SIZE || 0}}deg)'
                                . 'scale({{c_image_normal_scale_mobile.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{c_image_normal_translate_x_mobile.SIZE || 0}}px, {{c_image_normal_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_mobile.SIZE || 0}}deg)'
                                . 'scale({{c_image_normal_scale_mobile.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{c_image_normal_translate_x_mobile.SIZE || 0}}px, {{c_image_normal_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_mobile.SIZE || 0}}deg)'
                                . 'scale({{c_image_normal_scale_mobile.SIZE || 1}});'
                        ]
                ],
        );

        $repeater->add_responsive_control(
                'c_image_normal_rotate',
                [
                        'label'      => esc_html__('Rotate', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => -180,
                                        'max'  => 180,
                                        'step' => 1,
                                ],
                        ],
                        'default'    => [
                                'size' => 0
                        ],
                        'condition'  => [
                                'c_image_normal_rotate_toggle' => 'yes',
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}' =>
                                '-ms-transform:'
                                . 'translate({{c_image_normal_translate_x.SIZE || 0}}px, {{c_image_normal_translate_y.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate.SIZE || 0}}deg) '
                                . 'scaleX({{c_image_normal_scale.SIZE || 1}}) scaleY({{c_image_normal_scale_y.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{c_image_normal_translate_x.SIZE || 0}}px, {{c_image_normal_translate_y.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate.SIZE || 0}}deg) '
                                . 'scaleX({{c_image_normal_scale.SIZE || 1}}) scaleY({{c_image_normal_scale_y.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{c_image_normal_translate_x.SIZE || 0}}px, {{c_image_normal_translate_y.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate.SIZE || 0}}deg) '
                                . 'scaleX({{c_image_normal_scale.SIZE || 1}}) scaleY({{c_image_normal_scale_y.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}'  =>
                                '-ms-transform:'
                                . 'translate({{c_image_normal_translate_x_tablet.SIZE || 0}}px, {{c_image_normal_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{c_image_normal_scale_tablet.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{c_image_normal_translate_x_tablet.SIZE || 0}}px, {{c_image_normal_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{c_image_normal_scale_tablet.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{c_image_normal_translate_x_tablet.SIZE || 0}}px, {{c_image_normal_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{c_image_normal_scale_tablet.SIZE || 1}})',
                                '(mobile){{WRAPPER}} .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}'  =>
                                '-ms-transform:'
                                . 'translate({{c_image_normal_translate_x_mobile.SIZE || 0}}px, {{c_image_normal_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_mobile.SIZE || 0}}deg)'
                                . 'scale({{c_image_normal_scale_mobile.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{c_image_normal_translate_x_mobile.SIZE || 0}}px, {{c_image_normal_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_mobile.SIZE || 0}}deg)'
                                . 'scale({{c_image_normal_scale_mobile.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{c_image_normal_translate_x_mobile.SIZE || 0}}px, {{c_image_normal_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_mobile.SIZE || 0}}deg)'
                                . 'scale({{c_image_normal_scale_mobile.SIZE || 1}});'
                        ]
                ],
        );
        $repeater->add_responsive_control(
                'c_image_normal_scale',
                [
                        'label'      => esc_html__('Scale', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'default'    => [
                                'size' => 1
                        ],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 5,
                                        'step' => 0.01,
                                ],
                        ],
                        'condition'  => [
                                'c_image_normal_scale_toggle' => 'yes',
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}' =>
                                '-ms-transform:'
                                . 'translate({{c_image_normal_translate_x.SIZE || 0}}px, {{c_image_normal_translate_y.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate.SIZE || 0}}deg) '
                                . 'scaleX({{c_image_normal_scale.SIZE || 1}}) scaleY({{c_image_normal_scale_y.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{c_image_normal_translate_x.SIZE || 0}}px, {{c_image_normal_translate_y.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate.SIZE || 0}}deg) '
                                . 'scaleX({{c_image_normal_scale.SIZE || 1}}) scaleY({{c_image_normal_scale_y.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{c_image_normal_translate_x.SIZE || 0}}px, {{c_image_normal_translate_y.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate.SIZE || 0}}deg) '
                                . 'scaleX({{c_image_normal_scale.SIZE || 1}}) scaleY({{c_image_normal_scale_y.SIZE || 1}});',
                                '(tablet){{WRAPPER}} .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}'  =>
                                '-ms-transform:'
                                . 'translate({{c_image_normal_translate_x_tablet.SIZE || 0}}px, {{c_image_normal_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{c_image_normal_scale_tablet.SIZE || 1}})'
                                . '-webkit-transform:'
                                . 'translate({{c_image_normal_translate_x_tablet.SIZE || 0}}px, {{c_image_normal_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{c_image_normal_scale_tablet.SIZE || 1}})'
                                . 'transform:'
                                . 'translate({{c_image_normal_translate_x_tablet.SIZE || 0}}px, {{c_image_normal_translate_y_tablet.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_tablet.SIZE || 0}}deg) '
                                . 'scale({{c_image_normal_scale_tablet.SIZE || 1}})',
                                '(mobile){{WRAPPER}} .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}'  =>
                                '-ms-transform:'
                                . 'translate({{c_image_normal_translate_x_mobile.SIZE || 0}}px, {{c_image_normal_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_mobile.SIZE || 0}}deg)'
                                . 'scale({{c_image_normal_scale_mobile.SIZE || 1}});'
                                . '-webkit-transform:'
                                . 'translate({{c_image_normal_translate_x_mobile.SIZE || 0}}px, {{c_image_normal_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_mobile.SIZE || 0}}deg)'
                                . 'scale({{c_image_normal_scale_mobile.SIZE || 1}});'
                                . 'transform:'
                                . 'translate({{c_image_normal_translate_x_mobile.SIZE || 0}}px, {{c_image_normal_translate_y_mobile.SIZE || 0}}px) '
                                . 'rotate({{c_image_normal_rotate_mobile.SIZE || 0}}deg)'
                                . 'scale({{c_image_normal_scale_mobile.SIZE || 1}});'
                        ]
                ],
        );

        $repeater->end_popover();

        $repeater->end_controls_tab();

        # Hover State Tab
        $repeater->start_controls_tab(
                'c_image_hover',
                [
                        'label' => esc_html__('Hover', 'oxi-hover-effects-addons'),
                ],
        );
        $repeater->add_control(
                'c_image_hover_transform',
                [
                        'label'        => esc_html__('Transform', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::POPOVER_TOGGLE,
                        'return_value' => 'yes',
                ],
        );

        $repeater->start_popover();

        $repeater->add_responsive_control(
                'c_image_hover_translate_X',
                [
                        'label'      => esc_html__('Translate X', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px', '%'],
                        'range'      => [
                                'px' => [
                                        'min'  => -500,
                                        'max'  => 580,
                                        'step' => 1,
                                ],
                                '%'  => [
                                        'min'  => -100,
                                        'max'  => 100,
                                        'step' => 1,
                                ],
                        ],
                        'default'    => [
                                'size' => 0
                        ],
                        'condition'  => [
                                'c_image_hover_transform' => 'yes',
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}' =>
                                '-ms-transform:'
                                . 'translate({{c_image_hover_translate_X.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);'
                                . '-webkit-transform:'
                                . 'translate({{c_image_hover_translate_X.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);'
                                . 'transform:'
                                . 'translate({{c_image_hover_translate_X.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);',
                                '(tablet){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}'  =>
                                '-ms-transform:'
                                . 'translate({{c_image_hover_translate_X_tablet.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);'
                                . '-webkit-transform:'
                                . 'translate({{c_image_hover_translate_X_tablet.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);'
                                . 'transform:'
                                . 'translate({{c_image_hover_translate_X_tablet.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);',
                                '(mobile){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}'  =>
                                '-ms-transform:'
                                . 'translate({{c_image_hover_translate_X_mobile.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);'
                                . '-webkit-transform:'
                                . 'translate({{c_image_hover_translate_X_mobile.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);'
                                . 'transform:'
                                . 'translate({{c_image_hover_translate_X_mobile.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);'
                        ]
                ],
        );
        $repeater->add_responsive_control(
                'c_image_hover_translate_Y',
                [
                        'label'      => esc_html__('Translate Y', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px', '%'],
                        'range'      => [
                                'px' => [
                                        'min'  => -500,
                                        'max'  => 580,
                                        'step' => 1,
                                ],
                                '%'  => [
                                        'min'  => -100,
                                        'max'  => 100,
                                        'step' => 1,
                                ],
                        ],
                        'default'    => [
                                'size' => 0
                        ],
                        'condition'  => [
                                'c_image_hover_transform' => 'yes',
                        ],
                        'selectors'  => [
                                '(desktop){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}' =>
                                '-ms-transform:'
                                . 'translate({{c_image_hover_translate_X.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);'
                                . '-webkit-transform:'
                                . 'translate({{c_image_hover_translate_X.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);'
                                . 'transform:'
                                . 'translate({{c_image_hover_translate_X.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);',
                                '(tablet){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}'  =>
                                '-ms-transform:'
                                . 'translate({{c_image_hover_translate_X_tablet.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);'
                                . '-webkit-transform:'
                                . 'translate({{c_image_hover_translate_X_tablet.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);'
                                . 'transform:'
                                . 'translate({{c_image_hover_translate_X_tablet.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);',
                                '(mobile){{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}'  =>
                                '-ms-transform:'
                                . 'translate({{c_image_hover_translate_X_mobile.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);'
                                . '-webkit-transform:'
                                . 'translate({{c_image_hover_translate_X_mobile.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);'
                                . 'transform:'
                                . 'translate({{c_image_hover_translate_X_mobile.SIZE || 0}}px, {{c_image_hover_translate_Y.SIZE || 0}}px)rotate(0deg) scale(1);'
                        ]
                ],
        );

        $repeater->end_popover();
        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();

        $repeater->add_responsive_control(
                'c_image_hover_transition_duration',
                [
                        'label'      => esc_html__('Animation Duration', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 10,
                                        'step' => .01,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}}' => 'transition: all {{SIZE}}S;'
                        ]
                ],
        );
        $repeater->add_responsive_control(
                'c_image_hover_transition_delay',
                [
                        'label'      => esc_html__('Animation Delay', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 10,
                                        'step' => .01,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-overlay-wrapper {{CURRENT_ITEM}} ' => 'transition-delay: {{SIZE}}S;'
                        ]
                ],
        );

        $this->add_control(
                'oxi_addons_c_o_content_tab',
                [
                        'type'        => Controls_Manager::REPEATER,
                        'seperator'   => 'before',
                        'default'     => [],
                        'fields'      => $repeater->get_controls(),
                        'title_field' => 'Overlay image',
                ],
        );

        $this->end_controls_section();
    }

    protected function init_content_content_controls() {

        $this->start_controls_section(
                'oxi_addons_c_e_content',
                [
                        'label' => esc_html__('Content Settings', 'oxi-hover-effects-addons'),
                ],
        );

        $this->add_control(
                'oxi_addons_c_e_content_position',
                [
                        'label'   => esc_html__('Content Position', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::SELECT,
                        'options' => [
                                'inside'  => esc_html__('Inside Effects', 'oxi-hover-effects-addons'),
                                'outside' => esc_html__('Outside Effects', 'oxi-hover-effects-addons'),
                        ],
                        'default' => 'inside',
                ],
        );

        $repeater = new Repeater();

        $repeater->add_control(
                'content_type',
                [
                        'label'   => esc_html__('Content Type', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::SELECT,
                        'options' => [
                                'Title'       => esc_html__('Title', 'oxi-hover-effects-addons'),
                                'Description' => esc_html__('Description', 'oxi-hover-effects-addons'),
                                'Icon'        => esc_html__('Icon', 'oxi-hover-effects-addons'),
                                'Button'      => esc_html__('Button', 'oxi-hover-effects-addons'),
                        ],
                        'default' => 'Title',
                ],
        );

        $repeater->add_control(
                'content_title_width',
                [
                        'label'        => esc_html__('Width', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => esc_html__('Block', 'oxi-hover-effects-addons'),
                        'label_off'    => esc_html__('Inline', 'oxi-hover-effects-addons'),
                        'default'      => 'full-width',
                        'return_value' => 'full-width',
                        'condition'    => [
                                'content_type' => 'Title',
                        ],
                ],
        );
        $repeater->add_control(
                'content_title_position',
                [
                        'label'        => esc_html__('Position', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __('Bottom', 'oxi-hover-effects-addons'),
                        'label_off'    => __('Auto', 'oxi-hover-effects-addons'),
                        'default'      => '',
                        'return_value' => 'bottom',
                        'condition'    => [
                                'content_type'        => 'Title',
                                'content_title_width' => '',
                        ],
                ],
        );
        $repeater->add_control(
                'content_title',
                [
                        'label'       => esc_html__('Title', 'oxi-hover-effects-addons'),
                        'type'        => Controls_Manager::TEXT,
                        'default'     => esc_html__('Hover Title', 'oxi-hover-effects-addons'),
                        'placeholder' => esc_html__('Type your title here', 'oxi-hover-effects-addons'),
                        'separator'   => 'before',
                        'label_block' => true,
                        'dynamic'     => ['active' => true],
                        'condition'   => [
                                'content_type' => 'Title',
                        ],
                ],
        );
        $repeater->add_control(
                'content_title_tag',
                [
                        'label'     => esc_html__('Title Tag', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'h1'   => esc_html__('H1', 'oxi-hover-effects-addons'),
                                'h2'   => esc_html__('H2', 'oxi-hover-effects-addons'),
                                'h3'   => esc_html__('H3', 'oxi-hover-effects-addons'),
                                'h4'   => esc_html__('H4', 'oxi-hover-effects-addons'),
                                'h5'   => esc_html__('H5', 'oxi-hover-effects-addons'),
                                'h6'   => esc_html__('H6', 'oxi-hover-effects-addons'),
                                'p'    => esc_html__('Paragraph', 'oxi-hover-effects-addons'),
                                'span' => esc_html__('Span', 'oxi-hover-effects-addons'),
                                'div'  => esc_html__('Div', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => 'h3',
                        'condition' => [
                                'content_type' => 'Title',
                        ],
                ],
        );

        $repeater->add_control(
                'content_title_animation',
                [
                        'label'     => esc_html__('Animation', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => $this->init_content_animation_name(),
                        'default'   => 'oxi-a-ani-regular',
                        'condition' => [
                                'content_type' => 'Title',
                        ],
                ],
        );
        $repeater->add_control(
                'content_title_animation_delay',
                [
                        'label'     => esc_html__('Animation Delay', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                ''                    => esc_html__('None', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xs'  => esc_html__('Delay XS', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-sm'  => esc_html__('Delay SM', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-md'  => esc_html__('Delay MD', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-lg'  => esc_html__('Delay LG', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xl'  => esc_html__('Delay XL', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xxl' => esc_html__('Delay XXL', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => '',
                        'condition' => [
                                'content_type' => 'Title',
                        ],
                ],
        );

        $repeater->add_control(
                'content_description',
                [
                        'label'       => esc_html__('Description', 'oxi-hover-effects-addons'),
                        'type'        => Controls_Manager::TEXTAREA,
                        'rows'        => 5,
                        'default'     => esc_html__('Description', 'oxi-hover-effects-addons'),
                        'placeholder' => esc_html__('Type your description here', 'oxi-hover-effects-addons'),
                        'show_label'  => true,
                        'dynamic'     => ['active' => true],
                        'condition'   => [
                                'content_type' => 'Description',
                        ],
                ],
        );

        $repeater->add_control(
                'content_description_tag',
                [
                        'label'     => esc_html__('Description Tag', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'p'    => esc_html__('Paragraph', 'oxi-hover-effects-addons'),
                                'span' => esc_html__('Span', 'oxi-hover-effects-addons'),
                                'div'  => esc_html__('Div', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => 'p',
                        'condition' => [
                                'content_type' => 'Description',
                        ],
                ],
        );

        $repeater->add_control(
                'content_description_animation',
                [
                        'label'     => esc_html__('Animation', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => $this->init_content_animation_name(),
                        'default'   => 'oxi-a-ani-regular',
                        'condition' => [
                                'content_type' => 'Description',
                        ],
                ],
        );
        $repeater->add_control(
                'content_description_animation_delay',
                [
                        'label'     => esc_html__('Animation Delay', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                ''                    => esc_html__('None', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xs'  => esc_html__('Delay XS', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-sm'  => esc_html__('Delay SM', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-md'  => esc_html__('Delay MD', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-lg'  => esc_html__('Delay LG', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xl'  => esc_html__('Delay XL', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xxl' => esc_html__('Delay XXL', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => '',
                        'condition' => [
                                'content_type' => 'Description',
                        ],
                ],
        );

        $repeater->add_control(
                'content_icon_width',
                [
                        'label'        => esc_html__('Width', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => esc_html__('Block', 'oxi-hover-effects-addons'),
                        'label_off'    => esc_html__('Inline', 'oxi-hover-effects-addons'),
                        'default'      => 'full-width',
                        'return_value' => 'full-width',
                        'condition'    => [
                                'content_type' => 'Icon',
                        ],
                ],
        );
        $repeater->add_control(
                'content_icon_position',
                [
                        'label'        => esc_html__('Position', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __('Bottom', 'oxi-hover-effects-addons'),
                        'label_off'    => __('Auto', 'oxi-hover-effects-addons'),
                        'default'      => '',
                        'return_value' => 'bottom',
                        'condition'    => [
                                'content_type'       => 'Icon',
                                'content_icon_width' => '',
                        ],
                ],
        );
        $repeater->add_control(
                'content_icon',
                [
                        'label'       => esc_html__('Icon', 'oxi-hover-effects-addons'),
                        'type'        => Controls_Manager::ICONS,
                        'label_block' => true,
                        'separator'   => 'before',
                        'condition'   => [
                                'content_type' => 'Icon',
                        ],
                ],
        );

        $repeater->add_control(
                'content_icon_animation',
                [
                        'label'     => esc_html__('Animation', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => $this->init_content_animation_name(),
                        'default'   => 'oxi-a-ani-regular',
                        'condition' => [
                                'content_type' => 'Icon',
                        ],
                ],
        );

        $repeater->add_control(
                'content_icon_animation_delay',
                [
                        'label'     => esc_html__('Animation Delay', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                ''                    => esc_html__('None', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xs'  => esc_html__('Delay XS', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-sm'  => esc_html__('Delay SM', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-md'  => esc_html__('Delay MD', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-lg'  => esc_html__('Delay LG', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xl'  => esc_html__('Delay XL', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xxl' => esc_html__('Delay XXL', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => '',
                        'condition' => [
                                'content_type' => 'Icon',
                        ],
                ],
        );
        $repeater->add_control(
                'content_icon_link_type',
                [
                        'label'     => esc_html__('Link Type', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                ''         => esc_html__('None', 'oxi-hover-effects-addons'),
                                'link'     => esc_html__('Link', 'oxi-hover-effects-addons'),
                                'lightbox' => esc_html__('lightbox', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => '',
                        'separator' => 'before',
                        'condition' => [
                                'content_type' => 'Icon',
                        ],
                ],
        );
        $repeater->add_control(
                'content_icon_lightbox',
                [
                        'label'     => esc_html__('Lightbox Image', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::MEDIA,
                        'default'   => [
                                'url' => Utils::get_placeholder_image_src(),
                        ],
                        'dynamic'   => ['active' => true],
                        'condition' => [
                                'content_type'           => 'Icon',
                                'content_icon_link_type' => 'lightbox',
                        ],
                ],
        );

        $repeater->add_control(
                'content_icon_link',
                [
                        'label'         => esc_html__('Link To', 'oxi-hover-effects-addons'),
                        'type'          => Controls_Manager::URL,
                        'placeholder'   => esc_html__('https://your-link.com', 'oxi-hover-effects-addons'),
                        'show_external' => true,
                        'default'       => [
                                'url'         => '',
                                'is_external' => false,
                                'nofollow'    => false,
                        ],
                        'dynamic'       => ['active' => true],
                        'condition'     => [
                                'content_type'           => 'Icon',
                                'content_icon_link_type' => 'link',
                        ],
                ],
        );

        $repeater->add_control(
                'content_button_width',
                [
                        'label'        => esc_html__('Width', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => esc_html__('Block', 'oxi-hover-effects-addons'),
                        'label_off'    => esc_html__('Inline', 'oxi-hover-effects-addons'),
                        'default'      => 'full-width',
                        'return_value' => 'full-width',
                        'condition'    => [
                                'content_type' => 'Button',
                        ],
                ],
        );
        $repeater->add_control(
                'content_button_position',
                [
                        'label'        => esc_html__('Position', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __('Bottom', 'oxi-hover-effects-addons'),
                        'label_off'    => __('Auto', 'oxi-hover-effects-addons'),
                        'default'      => '',
                        'return_value' => 'bottom',
                        'condition'    => [
                                'content_type'         => 'Button',
                                'content_button_width' => '',
                        ],
                ],
        );
        $repeater->add_control(
                'content_button',
                [
                        'label'       => esc_html__('Button Text', 'oxi-hover-effects-addons'),
                        'type'        => Controls_Manager::TEXT,
                        'default'     => esc_html__('Click Me', 'oxi-hover-effects-addons'),
                        'placeholder' => esc_html__('Type your title here', 'oxi-hover-effects-addons'),
                        'separator'   => 'before',
                        'label_block' => true,
                        'dynamic'     => ['active' => true],
                        'condition'   => [
                                'content_type' => 'Button',
                        ],
                ],
        );
        $repeater->add_control(
                'content_button_icon',
                [
                        'label'       => esc_html__('Button Icon', 'oxi-hover-effects-addons'),
                        'type'        => Controls_Manager::ICONS,
                        'label_block' => true,
                        'condition'   => [
                                'content_type' => 'Button',
                        ],
                ],
        );
        $repeater->add_control(
                'content_button_icon_alignment',
                [
                        'label'     => esc_html__('Icom Alignment', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::CHOOSE,
                        'options'   => [
                                'flex-start'  => [
                                        'title' => esc_html__('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-left',
                                ],
                                'flex-top'    => [
                                        'title' => esc_html__('Top', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-v-align-top',
                                ],
                                'flex-bottom' => [
                                        'title' => esc_html__('Bottom', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-v-align-bottom',
                                ],
                                'flex-end'    => [
                                        'title' => esc_html__('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-right',
                                ]
                        ],
                        'condition' => [
                                'content_type' => 'Button',
                        ],
                        'default'   => 'flex-start',
                ],
        );

        $repeater->add_control(
                'content_button_animation',
                [
                        'label'     => esc_html__('Animation', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => $this->init_content_animation_name(),
                        'default'   => 'oxi-a-ani-regular',
                        'condition' => [
                                'content_type' => 'Button',
                        ],
                ],
        );
        $repeater->add_control(
                'content_button_animation_delay',
                [
                        'label'     => esc_html__('Animation Delay', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                ''                    => esc_html__('None', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xs'  => esc_html__('Delay XS', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-sm'  => esc_html__('Delay SM', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-md'  => esc_html__('Delay MD', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-lg'  => esc_html__('Delay LG', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xl'  => esc_html__('Delay XL', 'oxi-hover-effects-addons'),
                                'oxi-a-ani-delay-xxl' => esc_html__('Delay XXL', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => '',
                        'condition' => [
                                'content_type' => 'Button',
                        ],
                ],
        );

        $repeater->add_control(
                'content_button_link_type',
                [
                        'label'     => esc_html__('Link Type', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                ''         => esc_html__('None', 'oxi-hover-effects-addons'),
                                'link'     => esc_html__('Link', 'oxi-hover-effects-addons'),
                                'lightbox' => esc_html__('lightbox', 'oxi-hover-effects-addons'),
                        ],
                        'default'   => '',
                        'separator' => 'before',
                        'condition' => [
                                'content_type' => 'Button',
                        ],
                ],
        );
        $repeater->add_control(
                'content_button_lightbox',
                [
                        'label'     => esc_html__('Lightbox Image', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::MEDIA,
                        'default'   => [
                                'url' => Utils::get_placeholder_image_src(),
                        ],
                        'dynamic'   => ['active' => true],
                        'condition' => [
                                'content_type'             => 'Button',
                                'content_button_link_type' => 'lightbox',
                        ],
                ],
        );

        $repeater->add_control(
                'content_button_link',
                [
                        'label'         => esc_html__('Link To', 'oxi-hover-effects-addons'),
                        'type'          => Controls_Manager::URL,
                        'placeholder'   => esc_html__('https://your-link.com', 'oxi-hover-effects-addons'),
                        'show_external' => true,
                        'default'       => [
                                'url'         => '',
                                'is_external' => false,
                                'nofollow'    => false,
                        ],
                        'dynamic'       => ['active' => true],
                        'condition'     => [
                                'content_type'             => 'Button',
                                'content_button_link_type' => 'link',
                        ],
                ],
        );

        $this->add_control(
                'oxi_addons_c_e_content_tab',
                [
                        'type'        => Controls_Manager::REPEATER,
                        'seperator'   => 'before',
                        'default'     => [
                                ['content_type' => esc_html__('Title', 'oxi-hover-effects-addons')],
                                ['content_type' => esc_html__('Description', 'oxi-hover-effects-addons')],
                        ],
                        'fields'      => $repeater->get_controls(),
                        'title_field' => '{{content_type}}',
                ],
        );

        $this->end_controls_section();
    }

    public function init_content_animation_name() {
        return [
                ''                           => esc_html__('None', 'oxi-hover-effects-addons'),
                'oxi-a-ani-regular'          => esc_html__('Regular', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-up'          => esc_html__('Fade Up', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-down'        => esc_html__('Fade Down', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-left'        => esc_html__('Fade Left', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-right'       => esc_html__('Fade Right', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-up-big'      => esc_html__('Fade up Big', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-down-big'    => esc_html__('Fade down Big', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-left-big'    => esc_html__('Fade left Big', 'oxi-hover-effects-addons'),
                'oxi-a-ani-fade-right-big'   => esc_html__('Fade Right Big', 'oxi-hover-effects-addons'),
                'oxi-a-ani-zoom-in'          => esc_html__('Zoom In', 'oxi-hover-effects-addons'),
                'oxi-a-ani-zoom-out'         => esc_html__('Zoom Out', 'oxi-hover-effects-addons'),
                'oxi-a-ani-flip-x'           => esc_html__('Flip X', 'oxi-hover-effects-addons'),
                'oxi-a-ani-flip-y'           => esc_html__('Flip Y', 'oxi-hover-effects-addons'),
                'oxi-a-ani-scale-x'          => esc_html__('Scale X', 'oxi-hover-effects-addons'),
                'oxi-a-ani-scale-y'          => esc_html__('Scale Y', 'oxi-hover-effects-addons'),
                'oxi-a-ani-transform-left'   => esc_html__('Transform Left', 'oxi-hover-effects-addons'),
                'oxi-a-ani-transform-right'  => esc_html__('Transform Right', 'oxi-hover-effects-addons'),
                'oxi-a-ani-transform-top'    => esc_html__('Transform Top', 'oxi-hover-effects-addons'),
                'oxi-a-ani-transform-bottom' => esc_html__('Transform Bottom', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-left'      => esc_html__('Rotate Left', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-right'     => esc_html__('Rotate Right', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-left-c'    => esc_html__('Rotate Left C', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-right-c'   => esc_html__('Rotate Right C', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-top-c'     => esc_html__('Rotate Top C', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-bottom-c'  => esc_html__('Rotate Bottom C', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-left-cc'   => esc_html__('Rotate Left CC', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-right-cc'  => esc_html__('Rotate Right CC', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-top-cc'    => esc_html__('Rotate Top CC', 'oxi-hover-effects-addons'),
                'oxi-a-ani-rotate-bottom-cc' => esc_html__('Rotate Bottom CC', 'oxi-hover-effects-addons'),
        ];
    }

    protected function init_content_url_controls() {
        $this->start_controls_section(
                'oxi_addons_c_e_Link',
                [
                        'label' => esc_html__('Link or Lightbox', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_CONTENT,
                ],
        );
        $this->add_control(
                'link_or_lightbox',
                [
                        'label'       => esc_html__('Link Or Lightbox', 'plugin-domain'),
                        'label_block' => true,
                        'type'        => Controls_Manager::SELECT,
                        'default'     => '',
                        'options'     => [
                                ''         => esc_html__('None', 'plugin-domain'),
                                'link'     => esc_html__('URL', 'plugin-domain'),
                                'lightbox' => esc_html__('Light Box', 'plugin-domain'),
                        ],
                ],
        );
        $this->add_control(
                'content_lightbox',
                [
                        'label'     => esc_html__('Lightbox Image', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::MEDIA,
                        'default'   => [
                                'url' => Utils::get_placeholder_image_src(),
                        ],
                        'dynamic'   => ['active' => true],
                        'condition' => [
                                'link_or_lightbox' => 'lightbox',
                        ],
                ],
        );
        $this->add_control(
                'content_link',
                [
                        'label'         => esc_html__('Link To', 'oxi-hover-effects-addons'),
                        'type'          => Controls_Manager::URL,
                        'placeholder'   => esc_html__('https://your-link.com', 'oxi-hover-effects-addons'),
                        'show_external' => true,
                        'separator'     => 'before',
                        'default'       => [
                                'url'         => '',
                                'is_external' => false,
                                'nofollow'    => false,
                        ],
                        'dynamic'       => ['active' => true],
                        'condition'     => [
                                'link_or_lightbox' => 'link',
                        ],
                ],
        );

        $this->end_controls_section();
    }

    protected function init_content_promotion_controls() {

    }

    protected function init_style_general_controls() {

        $this->start_controls_section(
                'general_style_settings',
                [
                        'label' => esc_html__('General Style', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ],
        );

        $this->add_responsive_control(
                'general_style_width',
                [
                        'label'      => esc_html__('Width', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px', 'em', '%'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 1000,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-effects-style' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'general_style_height',
                [
                        'label'      => esc_html__('Height', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px', 'em', '%'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 1000,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-effects-style .oxi-image-effects-hover .oxi-image-hover-image img' => 'height: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'general_style_margin',
                [
                        'label'      => esc_html__('Margin', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-effects-style-caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'general_style_border_radius',
                [
                        'label'      => esc_html__('Border Radius', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}}  .oxi-image-effects-hover, '
                                . '{{WRAPPER}} .oxi-image-hover-figure, '
                                . '{{WRAPPER}} .oxi-image-hover-image,'
                                . '{{WRAPPER}} .oxi-image-hover-image img,'
                                . '{{WRAPPER}} .oxi-image-hover-overlay-wrapper, '
                                . '{{WRAPPER}} .oxi-image-hover-figure-body, '
                                . '{{WRAPPER}} .oxi-image-hover-figure-effects:before,'
                                . '{{WRAPPER}} .oxi-image-hover-figure-effects:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} .oxi-image-hover-image-effects,'
                                . '{{WRAPPER}} .oxi-image-hover-figure-effects,'
                                . '{{WRAPPER}} .oxi-image-hover-figure-tabs'                                                                                                                                                                                                                                                                                                                                                                                                             => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden  !important;',
                        ],
                ],
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                        'name'     => 'general_style_box_shadow',
                        'selector' => '{{WRAPPER}} .oxi-image-effects-hover',
                ],
        );

        $this->end_controls_section();
    }

    protected function init_style_content_controls() {

        $this->start_controls_section(
                'content_style_settings',
                [
                        'label' => esc_html__('Content Style', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ],
        );

        $this->add_group_control(Group_Control_Background::get_type(), [
                'name'      => "content_style_background",
                'label'     => __('Background', 'oxi-hover-effects-addons'),
                'types'     => ['classic', 'gradient'],
                'condition' => [
                        'oxi_addons_effects' => [
                                'oxi_image_e_e_01',
                                'oxi_image_e_e_03',
                                'oxi_image_e_e_04',
                                'oxi_image_e_e_05',
                                'oxi_image_e_e_06',
                                'oxi_image_e_e_07',
                                'oxi_image_e_e_08',
                                'oxi_image_e_e_09',
                                'oxi_image_e_e_10',
                                'oxi_image_e_e_11',
                                'oxi_image_e_e_12',
                                'oxi_image_e_e_13',
                                'oxi_image_e_e_14',
                                'oxi_image_e_e_15',
                                'oxi_image_e_e_16',
                                'oxi_image_e_e_17',
                                'oxi_image_e_e_18',
                                'oxi_image_e_e_19',
                                'oxi_image_e_e_20',
                                'oxi_image_e_e_21',
                                'oxi_image_e_e_22',
                                'oxi_image_e_e_23',
                                'oxi_image_e_e_24',
                                'oxi_image_e_e_25',
                                'oxi_image_e_e_26',
                                'oxi_image_e_e_27',
                                'oxi_image_e_e_28',
                                'oxi_image_e_e_29',
                        ],
                ],
                'selector'  => '{{WRAPPER}} .oxi-image-effects-hover .oxi-image-hover-figure .oxi-image-hover-figure-effects'
        ]);
        $this->add_control(
                'content_style_bg_color',
                [
                        'label'     => esc_html__('Background Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'condition' => [
                                'oxi_addons_effects' => [
                                        'oxi_image_e_e_02',
                                        'oxi_image_e_e_30',
                                ],
                        ],
                        'selectors' => [
                                "{{WRAPPER}} .oxi-image-effects-hover .oxi-image-hover-figure-effects,
                            {{WRAPPER}} .oxi-image-effects-hover .oxi-image-hover-figure-effects:before,
                            {{WRAPPER}} .oxi-image-effects-hover .oxi-image-hover-figure-effects:after" => "background: {{VALUE}};",
                        ]
                ],
        );
        $this->add_responsive_control(
                'content_align',
                [
                        'label'   => esc_html__('Horizontal Alignment', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::CHOOSE,
                        'options' => [
                                'flex-start' => [
                                        'title' => esc_html__('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-h-align-left',
                                ],
                                'center'     => [
                                        'title' => esc_html__('Center', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-h-align-center',
                                ],
                                'flex-end'   => [
                                        'title' => esc_html__('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-h-align-right',
                                ]
                        ],
                        'default' => 'center',
                ],
        );

        $this->add_responsive_control(
                'content_vertical_align',
                [
                        'label'   => esc_html__('Vertical Alignment', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::CHOOSE,
                        'options' => [
                                'flex-start' => [
                                        'title' => esc_html__('Top', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-v-align-top',
                                ],
                                'center'     => [
                                        'title' => esc_html__('Middle', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-v-align-middle',
                                ],
                                'flex-end'   => [
                                        'title' => esc_html__('Bottom', 'oxi-hover-effects-addons'),
                                        'icon'  => 'eicon-v-align-bottom',
                                ]
                        ],
                        'default' => 'center',
                ],
        );

        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name'     => 'content_border',
                        'label'    => __('Border', 'oxi-hover-effects-addons'),
                        'selector' => '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-figure-tabs',
                ],
        );

        $this->add_responsive_control(
                'content_style_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-figure-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'content_style_margin',
                [
                        'label'      => esc_html__('Margin', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-figure-effects' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                ],
        );
        $this->end_controls_section();
    }

    protected function init_style_title_controls() {

        $this->start_controls_section(
                'title_style_settings',
                [
                        'label' => esc_html__('Title Style', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ],
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name'     => 'title_style_settings_typography',
                        'selector' => '{{WRAPPER}} .oxi-image-effects-hover .oxi-image-hover-addons-heading',
                ],
        );
        $this->add_responsive_control(
                'title_style_alignment',
                [
                        'label'     => __('Alignment', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::CHOOSE,
                        'options'   => [
                                'left'    => [
                                        'title' => __('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-left',
                                ],
                                'center'  => [
                                        'title' => __('Center', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-center',
                                ],
                                'right'   => [
                                        'title' => __('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-right',
                                ],
                                'justify' => [
                                        'title' => __('Justified', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-justify',
                                ],
                        ],
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-addons-heading' => 'text-align: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'title_style_interface',
                [
                        'label'        => esc_html__('Style Interface', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => esc_html__('Advanced', 'oxi-hover-effects-addons'),
                        'label_off'    => esc_html__('simple', 'oxi-hover-effects-addons'),
                        'default'      => '',
                        'return_value' => 'advance',
                ],
        );

        $this->start_controls_tabs('title_style_settings_tabs');
        # Normal State Tab
        $this->start_controls_tab(
                'title_style_settings_normal',
                [
                        'label' => esc_html__('Normal', 'oxi-hover-effects-addons')
                ],
        );

        $this->add_control(
                'title_style_settings_color',
                [
                        'label'     => esc_html__('Text Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '#FFF',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-addons-heading' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'title_style_background',
                [
                        'label'     => esc_html__('Background', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => 'rgba(126, 0, 184, 1)',
                        'selectors' => [
                                "{{WRAPPER}} .oxi-image-hover .oxi-image-hover-addons-heading" => "background: {{VALUE}};",
                        ],
                        'condition' => [
                                'title_style_interface' => 'advance',
                        ],
                ],
        );
        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name'      => 'title_style_border',
                        'label'     => __('Border', 'oxi-hover-effects-addons'),
                        'condition' => [
                                'title_style_interface' => 'advance',
                        ],
                        'selector'  => '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-addons-heading',
                ],
        );
        $this->end_controls_tab();

        # Hover State Tab
        $this->start_controls_tab(
                'title_style_settings_hover',
                [
                        'label' => esc_html__('Hover', 'oxi-hover-effects-addons'),
                ],
        );
        $this->add_control(
                'title_style_settings_hover_color',
                [
                        'label'     => esc_html__('Text Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-addons-heading' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'title_style_style_hover_background',
                [
                        'label'     => esc_html__('Background', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => 'rgba(126, 0, 184, 1)',
                        'selectors' => [
                                "{{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-addons-heading" => "background: {{VALUE}};",
                        ],
                        'condition' => [
                                'title_style_interface' => 'advance',
                        ],
                ],
        );
        $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                        'name'      => 'title_style_hover_border',
                        'label'     => __('Border', 'oxi-hover-effects-addons'),
                        'condition' => [
                                'title_style_interface' => 'advance',
                        ],
                        'selector'  => '{{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-addons-heading',
                ],
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'title_style_settings_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-addons-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator'  => 'before',
                ],
        );

        $this->end_controls_section();
    }

    protected function init_style_description_controls() {
        $this->start_controls_section(
                'desc_style_settings',
                [
                        'label' => esc_html__('Description Style', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ],
        );
        $this->add_responsive_control(
                'desc_style_alignment',
                [
                        'label'     => __('Alignment', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::CHOOSE,
                        'options'   => [
                                'left'    => [
                                        'title' => __('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-left',
                                ],
                                'center'  => [
                                        'title' => __('Center', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-center',
                                ],
                                'right'   => [
                                        'title' => __('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-right',
                                ],
                                'justify' => [
                                        'title' => __('Justified', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-justify',
                                ],
                        ],
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-addons-cont' => 'text-align: {{VALUE}};',
                        ],
                ],
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name'     => 'desc_style_settings_typography',
                        'selector' => '{{WRAPPER}} .oxi-image-effects-hover .oxi-image-hover-addons-cont',
                ],
        );
        $this->start_controls_tabs('desc_style_settings_tabs');
        # Normal State Tab
        $this->start_controls_tab(
                'desc_style_settings_normal',
                [
                        'label' => esc_html__('Normal', 'oxi-hover-effects-addons')
                ],
        );

        $this->add_control(
                'desc_style_settings_color',
                [
                        'label'     => esc_html__('Text Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '#FFF',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-addons-cont' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->end_controls_tab();

        # Hover State Tab
        $this->start_controls_tab(
                'desc_style_settings_hover',
                [
                        'label' => esc_html__('Hover', 'oxi-hover-effects-addons'),
                ],
        );
        $this->add_control(
                'desc_style_settings_hover_color',
                [
                        'label'     => esc_html__('Text Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-image-hover:hover .oxi-image-hover-addons-cont' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'desc_style_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-addons-cont' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator'  => 'before',
                ],
        );

        $this->end_controls_section();
    }

    protected function init_style_icon_controls() {

        $this->start_controls_section(
                'icon_style_settings',
                [
                        'label' => esc_html__('Icon Style', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ],
        );

        $this->add_responsive_control(
                'icon_style_size',
                [
                        'label'      => esc_html__('Icon Size', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 100,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-addons-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-addons-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-addons-icon svg' => 'max-height: {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'icon_style_alignment',
                [
                        'label'     => __('Alignment', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::CHOOSE,
                        'options'   => [
                                'left'    => [
                                        'title' => __('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-left',
                                ],
                                'center'  => [
                                        'title' => __('Center', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-center',
                                ],
                                'right'   => [
                                        'title' => __('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-right',
                                ],
                                'justify' => [
                                        'title' => __('Justified', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-justify',
                                ],
                        ],
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-addons-icon-tabs' => 'text-align: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'icon_style_interface',
                [
                        'label'        => esc_html__('Style Interface', 'oxi-hover-effects-addons'),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => esc_html__('Advanced', 'oxi-hover-effects-addons'),
                        'label_off'    => esc_html__('simple', 'oxi-hover-effects-addons'),
                        'default'      => '',
                        'return_value' => 'advance',
                ],
        );
        $this->add_responsive_control(
                'icon_style_width',
                [
                        'label'      => esc_html__('Width Height', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'default'    => [
                                'size' => 75,
                                'unit' => 'px',
                        ],
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 200,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}}  .oxi-image-hover .oxi-image-addons-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; display: inline-flex; align-items: center; justify-content: center;',
                        ],
                        'condition'  => [
                                'icon_style_interface' => 'advance',
                        ],
                ],
        );

        $this->start_controls_tabs('icon_style_settings_tabs');
        # Normal State Tab
        $this->start_controls_tab(
                'icon_style_settings_normal',
                [
                        'label' => esc_html__('Normal', 'oxi-hover-effects-addons')
                ],
        );

        $this->add_control(
                'icon_style_settings_color',
                [
                        'label'     => esc_html__('Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '#FFF',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-addons-icon,'
                                . ' {{WRAPPER}} .oxi-image-hover .oxi-image-addons-icon i' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'icon_style_background',
                [
                        'label'     => esc_html__('Background', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '#B8005B',
                        'selectors' => [
                                "{{WRAPPER}} .oxi-image-hover .oxi-image-addons-icon" => "background: {{VALUE}};",
                        ],
                        'condition' => [
                                'icon_style_interface' => 'advance',
                        ],
                ],
        );

        $this->end_controls_tab();

        # Hover State Tab
        $this->start_controls_tab(
                'icon_style_settings_hover',
                [
                        'label' => esc_html__('Hover', 'oxi-hover-effects-addons'),
                ],
        );
        $this->add_control(
                'icon_style_settings_hover_color',
                [
                        'label'     => esc_html__('Text Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-addons-icon:hover, '
                                . '{{WRAPPER}} .oxi-image-hover .oxi-image-addons-icon:hover i' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'icon_style_hover_background',
                [
                        'label'     => esc_html__('Background', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'selectors' => [
                                "{{WRAPPER}} .oxi-image-hover .oxi-image-addons-icon:hover" => "background: {{VALUE}};",
                        ],
                        'condition' => [
                                'icon_style_interface' => 'advance',
                        ],
                ],
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_responsive_control(
                'icon_style_border_radius',
                [
                        'label'      => esc_html__('Border Radius', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-addons-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden ;',
                        ],
                        'condition'  => [
                                'icon_style_interface' => 'advance',
                        ],
                ],
        );
        $this->add_responsive_control(
                'icon_style_settings_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-addons-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator'  => 'before',
                ],
        );
        $this->add_responsive_control(
                'icon_style_settings_margin',
                [
                        'label'      => esc_html__('Margin', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-addons-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator'  => 'before',
                ],
        );

        $this->end_controls_section();
    }

    protected function init_style_button_controls() {
        $this->start_controls_section(
                'button_style_settings',
                [
                        'label' => esc_html__('Button Style', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_STYLE,
                ],
        );

        $this->add_responsive_control(
                'button_style_size',
                [
                        'label'      => esc_html__('Font Size', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 100,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-button'     => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-button i'   => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-button svg' => 'max-width: {{SIZE}}{{UNIT}};max-height: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'button_style_alignment',
                [
                        'label'     => __('Alignment', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::CHOOSE,
                        'options'   => [
                                'left'    => [
                                        'title' => __('Left', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-left',
                                ],
                                'center'  => [
                                        'title' => __('Center', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-center',
                                ],
                                'right'   => [
                                        'title' => __('Right', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-right',
                                ],
                                'justify' => [
                                        'title' => __('Justified', 'oxi-hover-effects-addons'),
                                        'icon'  => 'fa fa-align-justify',
                                ],
                        ],
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-addons-button' => 'text-align: {{VALUE}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'button_style_size_icon_gap',
                [
                        'label'      => esc_html__('Icon Gap', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::SLIDER,
                        'default'    => [
                                'size' => 10,
                                'unit' => 'px',
                        ],
                        'size_units' => ['px'],
                        'range'      => [
                                'px' => [
                                        'min'  => 0,
                                        'max'  => 100,
                                        'step' => 1,
                                ],
                        ],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-button i'   => 'margin: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-button svg' => 'margin: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name'     => 'button_style_settings_typography',
                        'exclude'  => [
                                'font_size',
                        ],
                        'selector' => '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-button',
                ],
        );

        $this->start_controls_tabs('button_style_settings_tabs');
        # Normal State Tab
        $this->start_controls_tab(
                'button_style_settings_normal',
                [
                        'label' => esc_html__('Normal', 'oxi-hover-effects-addons')
                ],
        );

        $this->add_control(
                'button_style_settings_color',
                [
                        'label'     => esc_html__('Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-button, {{WRAPPER}} .oxi-image-hover .oxi-image-hover-button i' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'button_style_background',
                [
                        'label'     => esc_html__('Background', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'selectors' => [
                                "{{WRAPPER}} .oxi-image-hover .oxi-image-hover-button" => "background: {{VALUE}};",
                        ],
                ],
        );

        $this->end_controls_tab();

        # Hover State Tab
        $this->start_controls_tab(
                'button_style_settings_hover',
                [
                        'label' => esc_html__('Hover', 'oxi-hover-effects-addons'),
                ],
        );
        $this->add_control(
                'button_style_settings_hover_color',
                [
                        'label'     => esc_html__('Text Color', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'selectors' => [
                                '{{WRAPPER}} .oxi-image-hover .oxi-image-hover-button:hover, {{WRAPPER}} .oxi-image-hover .oxi-image-hover-button:hover i' => 'color: {{VALUE}};',
                        ],
                ],
        );
        $this->add_control(
                'button_style_hover_background',
                [
                        'label'     => esc_html__('Background', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'selectors' => [
                                "{{WRAPPER}} .oxi-image-hover .oxi-image-hover-button:hover" => "background: {{VALUE}};",
                        ]
                ],
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
                'button_style_settings_padding',
                [
                        'label'      => esc_html__('Padding', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-effects-style .oxi-image-hover-addons-button .oxi-image-hover-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator'  => 'before',
                ],
        );
        $this->add_responsive_control(
                'button_style_settings_margin',
                [
                        'label'      => esc_html__('Margin', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-effects-style .oxi-image-hover-addons-button .oxi-image-hover-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                        'separator'  => 'before',
                ],
        );
        $this->add_responsive_control(
                'button_style_border_radius',
                [
                        'label'      => esc_html__('Border Radius', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-effects-style .oxi-image-hover-addons-button .oxi-image-hover-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden;',
                        ],
                ],
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $style    = esc_attr($settings['oxi_addons_effects']);

        $effects          = isset($settings[$style]) ? esc_attr($settings[$style]) : '';
        $image_animation  = isset($settings['oxi_image_hover_effects']) ? esc_attr('oxi-image-hover-image-' . $settings['oxi_image_hover_effects']) : '';
        $align            = isset($settings['content_align']) ? ' oxi-image-hover-figure-horizontal-align-' . esc_attr($settings['content_align']) : '';
        $align            .= isset($settings['content_vertical_align']) ? ' oxi-image-hover-figure-vertical-align-' . esc_attr($settings['content_vertical_align']) : '';
        ?>
        <div class="oxi-image-hover-style oxi-image-effects-style <?php echo $style ?>">

                <?php
                $LinkBox          = $settings['link_or_lightbox'] != '' ? true : false;
                $overlay_position = isset($settings['oxi_addons_c_o_position']) && $settings['oxi_addons_c_o_position'] == 'outside' ? true : false;
                $content_position = $settings['oxi_addons_c_e_content_position'] == 'outside' ? true : false;
                if ($content_position) :
                    $content_position = $style == 'oxi_image_modal' ? false : true;
                endif;
                if ($LinkBox) {
                    if (!empty($settings['content_link']['url'])) {
                        $this->add_link_attributes('content_link', $settings['content_link']);
                    }

                    if ($settings['link_or_lightbox'] === 'lightbox') {
                        $this->add_render_attribute('content_link', [
                                'href'                         => isset($settings['content_lightbox']['url']) && !empty($settings['content_lightbox']['url']) ? esc_url($settings['content_lightbox']['url']) : esc_url($settings['oxi_image']['url']),
                                'data-elementor-open-lightbox' => 'yes',
                        ]);
                    }
                    ?>
                    <a <?php echo $this->get_render_attribute_string('content_link'); ?>>
                        <?php
                    }

                    $rep = '';
                    if (isset($settings['oxi_addons_c_o_content_tab'])):
                        foreach ($settings['oxi_addons_c_o_content_tab'] as $index => $value) {


                            if (isset($value['c_image']['url']) && !empty($value['c_image']['url'])) :
                                $rep .= '<div class="oxi-image-hover-overlay-wrapper">';
                                $rep .= '   <div class="oxi-image-hover-overlay-image elementor-repeater-item-' . $value['_id'] . '">';
                                $rep .= Group_Control_Image_Size::get_attachment_image_html($value, 'c_image_thumbnail', 'c_image');
                                $rep .= '   </div>';
                                $rep .= '</div>';
                            endif;
                        }
                    endif;
                    ?>

                        <div class="oxi-image-effects-style-caption">
                                <div class="oxi-image-hover oxi-image-effects-hover <?php echo $effects ?>">
                                        <div class="oxi-image-hover-figure">
                                                <div class="oxi-image-hover-image">
                                                        <div class="oxi-image-hover-image-effects  <?php echo $image_animation ?>">
                                                            <?php echo Group_Control_Image_Size::get_attachment_image_html($settings, 'oxi_image_thumbnail', 'oxi_image'); ?>
                                                        </div>
                                                </div>
                                                <?php
                                                if ($overlay_position):
                                                    echo $rep;
                                                endif;
                                                ?>
                                                <div class="oxi-image-hover-figure-body">
                                                        <div class="oxi-image-hover-figure-effects">

                                                                <?php
                                                                if (!$overlay_position):
                                                                    echo $rep;
                                                                endif;
                                                                if ($content_position) {
                                                                    echo '</div>';
                                                                    echo '</div>';
                                                                }
                                                                ?>

                                                                <div class="oxi-image-hover-figure-tabs <?php echo $align ?>">
                                                                    <?php
                                                                    $content_align = 'full-width';
                                                                    foreach ($settings['oxi_addons_c_e_content_tab'] as $index => $value) {
                                                                        $type = !empty($value['content_type']) ? esc_attr($value['content_type']) : '';

                                                                        if ($type === 'Title') {
                                                                            $title     = wp_kses_post($value['content_title']);
                                                                            $tag       = esc_attr($value['content_title_tag']);
                                                                            $animation = esc_attr($value['content_title_animation']);
                                                                            $delay     = esc_attr($value['content_title_animation_delay']);
                                                                            $position  = $value['content_title_width'] == 'full-width' ? 'oxi-image-hover-figure-full-width' : '';

                                                                            if (empty($position) && $content_align == 'full-width') {

                                                                                $content_align = 'inline';
                                                                                $bottom        = $value['content_title_position'] == 'bottom' ? 'oxi-image-hover-addons-inline-bottom' : '';
                                                                                ?>
                                                                                    <div class="oxi-image-hover-addons-inline <?php echo $bottom; ?>">
                                                                                        <?php
                                                                                    } elseif (!empty($position) && $content_align == 'inline') {
                                                                                        $content_align = 'full-width';
                                                                                        ?>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                                <<?php echo $tag; ?> class="oxi-image-hover-addons-heading oxi-image-hover-rams <?php echo $animation; ?>  <?php echo $delay; ?> <?php echo $position; ?>"><?php echo $title; ?>
                                                                                </<?php echo $tag; ?>>
                                                                                <?php
                                                                            } elseif ($type === 'Description') {
                                                                                $desc      = wp_kses_post($value['content_description']);
                                                                                $tag       = esc_attr($value['content_description_tag']);
                                                                                $animation = esc_attr($value['content_description_animation']);
                                                                                $delay     = esc_attr($value['content_description_animation_delay']);
                                                                                if ($content_align == 'inline') {
                                                                                    $content_align = 'full-width';
                                                                                    ?>
                                                                            </div>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                        <<?php echo $tag; ?> class="oxi-image-hover-addons-cont oxi-image-hover-rams <?php echo $animation; ?>  <?php echo $delay; ?>"><?php echo $desc; ?>
                                                                        </<?php echo $tag; ?>>
                                                                        <?php
                                                                    } elseif ($type === 'Icon') {
                                                                        $iconlink = $value['content_icon_link_type'] != '' ? true : false;

                                                                        $icon      = $value['content_icon'];
                                                                        $animation = esc_attr($value['content_icon_animation']);
                                                                        $delay     = esc_attr($value['content_icon_animation_delay']);
                                                                        $position  = $value['content_icon_width'] == 'full-width' ? 'oxi-image-hover-figure-full-width' : '';
                                                                        if (empty($position) && $content_align == 'full-width') {
                                                                            $content_align = 'inline';
                                                                            $bottom        = $value['content_icon_position'] == 'bottom' ? 'oxi-image-hover-addons-inline-bottom' : '';
                                                                            ?>
                                                                            <div class="oxi-image-hover-addons-inline <?php echo $bottom; ?>">

                                                                                    <?php
                                                                                } elseif (!empty($position) && $content_align == 'inline') {
                                                                                    $content_align = 'full-width';
                                                                                    ?>
                                                                            </div>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                        <div class="oxi-image-hover-addons-icon-tabs oxi-image-hover-rams <?php echo $animation; ?> <?php echo $delay; ?> <?php echo $position; ?>">
                                                                            <?php
                                                                            if ($iconlink > 0 && !$LinkBox) {
                                                                                $att = $index . '_content_link';

                                                                                if (!empty($value['content_icon_link']['url'])) {
                                                                                    $iconlink = true;
                                                                                    $this->add_link_attributes($att, $value['content_icon_link']);
                                                                                }
                                                                                if ($value['content_icon_link_type'] === 'lightbox') {
                                                                                    $this->add_render_attribute($att, [
                                                                                            'href'                         => esc_url($value['content_icon_lightbox']['url']),
                                                                                            'data-elementor-open-lightbox' => 'yes',
                                                                                    ]);
                                                                                }
                                                                                ?>
                                                                                    <a <?php echo $this->get_render_attribute_string($att); ?>>
                                                                                    <?php } ?>
                                                                                        <div class="oxi-image-addons-icon"><?php Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']); ?></div>
                                                                                        <?php if ($iconlink && !$LinkBox) { ?>
                                                                                    </a>
                                                                                <?php } ?>

                                                                        </div>
                                                                        <?php
                                                                    } elseif ($type === 'Button') {


                                                                        $text      = wp_kses_post($value['content_button']);
                                                                        $icon      = $value['content_button_icon'];
                                                                        $animation = esc_attr($value['content_button_animation']);
                                                                        $delay     = esc_attr($value['content_button_animation_delay']);
                                                                        $position  = $value['content_button_width'] == 'full-width' ? 'oxi-image-hover-figure-full-width' : '';
                                                                        if (empty($position) && $content_align == 'full-width') {
                                                                            $content_align = 'inline';
                                                                            $bottom        = $value['content_button_position'] == 'bottom' ? 'oxi-image-hover-addons-inline-bottom' : '';
                                                                            ?>
                                                                            <div class="oxi-image-hover-addons-inline <?php echo $bottom; ?>">
                                                                                <?php
                                                                            } elseif (!empty($position) && $content_align == 'inline') {
                                                                                $content_align = 'full-width';
                                                                                ?>
                                                                            </div>
                                                                            <?php
                                                                        }
                                                                        $alignment  = 'oxi-image-addons-alignment-' . esc_attr($value['content_button_icon_alignment']);
                                                                        ?>

                                                                        <div class="oxi-image-hover-addons-button oxi-image-hover-rams  <?php echo $animation; ?> <?php echo $delay; ?>  <?php echo $position; ?>">

                                                                                <?php
                                                                                $buttonlink = $value['content_button_link_type'] != '' ? true : false;

                                                                                if ($buttonlink && !$LinkBox) {
                                                                                    $att = $index . '_content_link';

                                                                                    if (!empty($value['content_button_link']['url'])) {
                                                                                        $iconlink = true;
                                                                                        $this->add_link_attributes($att, $value['content_button_link']);
                                                                                    }
                                                                                    if ($value['content_button_link_type'] === 'lightbox') {
                                                                                        $this->add_render_attribute($att, [
                                                                                                'href'                         => esc_url($value['content_button_lightbox']['url']),
                                                                                                'data-elementor-open-lightbox' => 'yes',
                                                                                        ]);
                                                                                    }
                                                                                    ?>
                                                                                    <a <?php echo $this->get_render_attribute_string($att); ?>>
                                                                                    <?php } ?>

                                                                                        <span class="oxi-image-hover-button  <?php echo $alignment; ?>">
                                                                                                <?php Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']); ?>
                                                                                                <?php echo $text; ?>
                                                                                        </span>
                                                                                        <?php if ($buttonlink && !$LinkBox) { ?>
                                                                                    </a>
                                                                                <?php } ?>
                                                                        </div>

                                                                        <?php
                                                                    }
                                                                }

                                                                if ($content_align == 'inline') {
                                                                    ?>
                                                            </div>

                                                            <?php
                                                        }
                                                        ?>
                                                </div>
                                                <?php
                                                if (!$content_position) {
                                                    ?>
                                            </div>
                                    </div>
                                    <?php
                                }
                                ?>


                        </div>
        </div>
        </div>

        <?php
        if ($LinkBox) {
            ?>
            </a>
            <?php
        }
        ?>


        </div>
        <?php
    }

}
