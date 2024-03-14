<?php

namespace OXIIMAEADDONS\Modules\Caption;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager as Controls_Manager;
use Elementor\Group_Control_Background as Group_Control_Background;
use Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size as Group_Control_Image_Size;
use Elementor\Group_Control_Typography as Group_Control_Typography;
use Elementor\Icons_Manager as Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils as Utils;
use Elementor\Widget_Base as Widget_Base;

/**
 * Description of Caption Effects
 *
 * @author biplo
 */
class Caption extends Widget_Base {

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
    }

    public function get_name() {
        return 'oxi_i_addons_caption';
    }

    public function get_title() {

        return esc_html__('Caption Effects', 'oxi-hover-effects-addons');
    }

    public function get_icon() {

        return 'eicon-image';
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
        $id = 'oxi-i-addons-c-' . $style;

        wp_register_style('oxi-i-addons-c', OXIIMAEADDONS_URL . 'Modules/Caption/css/index.css',);
        wp_register_style($id, OXIIMAEADDONS_URL . 'Modules/Caption/css/' . $style . '.css');
        return [
                'oxi-i-addons-c',
                $id,
        ];
    }

    public function get_custom_help_url() {
        return 'https://wordpress.org/support/plugin/image-hover-effects-with-carousel/';
    }

    protected function register_controls() {
        $this->init_content_general_controls();
        $this->init_content_content_controls();
        $this->init_content_url_controls();
        $this->init_content_promotion_controls();

        $this->init_style_general_controls();
        $this->init_style_title_controls();
        $this->init_style_description_controls();
        $this->init_style_icon_controls();
        $this->init_style_button_controls();
    }

    protected function init_content_general_controls() {

        $this->start_controls_section(
                'oxi_addons_c_e_section',
                [
                        'label' => esc_html__('Effects & Image', 'oxi-hover-effects-addons'),
                        'tab'   => Controls_Manager::TAB_CONTENT,
                ],
        );

        $this->add_control(
                'oxi_addons_effects',
                [
                        'label'   => esc_html__('Effects Name', 'oxi-hover-effects-addons'),
                        'type'    => Controls_Manager::SELECT,
                        'options' => [
                                'oxi_image_blinds'     => esc_html__('Blinds Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_blocks'     => esc_html__('Block Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_book'       => esc_html__('Book Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_border'     => esc_html__('Border Reveal Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_bounce'     => esc_html__('Bounce Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_circle'     => esc_html__('Circle Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_cube'       => esc_html__('Cube Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_dive'       => esc_html__('Drive Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_fade'       => esc_html__('Fade Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_fall_away'  => esc_html__('Fall Away Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_flash'      => esc_html__('Flash Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_flip'       => esc_html__('Flip Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_fold'       => esc_html__('Fold Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_hinge'      => esc_html__('Hinge Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_lightspeed' => esc_html__('Lightspeed Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_modal'      => esc_html__('Modal Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_parallax'   => esc_html__('Parallax Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_pivot'      => esc_html__('Pivot Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_pixel'      => esc_html__('Pixel Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_push'       => esc_html__('Push Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_reveal'     => esc_html__('Reveal Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_rotate'     => esc_html__('Rotate Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_shift'      => esc_html__('Shift Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_shutter'    => esc_html__('Shutter Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_slide'      => esc_html__('Slide Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_splash'     => esc_html__('Splash Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_stack'      => esc_html__('Stack Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_strip'      => esc_html__('Strip Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_switch'     => esc_html__('Switch Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_throw'      => esc_html__('Throw Effect', 'oxi-hover-effects-addons'),
                                'oxi_image_zoom'       => esc_html__('Zoom Effect', 'oxi-hover-effects-addons'),
                        ],
                        'default' => 'oxi_image_blinds',
                ],
        );

        $this->add_control(
                'oxi_image_fade',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-fade-in-up'    => esc_html__('Fade In Up', 'oxi-hover-effects-addons'),
                                'oxi-image-fade-in-down'  => esc_html__('Fade In Down', 'oxi-hover-effects-addons'),
                                'oxi-image-fade-in-left'  => esc_html__('Fade In Left', 'oxi-hover-effects-addons'),
                                'oxi-image-fade-in-right' => esc_html__('Fade In Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_fade',
                        ],
                        'default'   => 'oxi-image-fade-in-up',
                ],
        );
        $this->add_control(
                'oxi_image_dive',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-dive'     => esc_html__('Drive', 'oxi-hover-effects-addons'),
                                'oxi-image-dive-cc'  => esc_html__('Drive CC', 'oxi-hover-effects-addons'),
                                'oxi-image-dive-ccc' => esc_html__('Drive CCC', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_dive',
                        ],
                        'default'   => 'oxi-image-dive',
                ],
        );
        $this->add_control(
                'oxi_image_cube',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-cube-up'    => esc_html__('Cube Up', 'oxi-hover-effects-addons'),
                                'oxi-image-cube-down'  => esc_html__('Cube Down', 'oxi-hover-effects-addons'),
                                'oxi-image-cube-left'  => esc_html__('Cube Left', 'oxi-hover-effects-addons'),
                                'oxi-image-cube-right' => esc_html__('Cube Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_cube',
                        ],
                        'default'   => 'oxi-image-cube-up',
                ],
        );
        $this->add_control(
                'oxi_image_circle',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-circle-up'           => esc_html__('Circle Up', 'oxi-hover-effects-addons'),
                                'oxi-image-circle-down'         => esc_html__('Circle Down', 'oxi-hover-effects-addons'),
                                'oxi-image-circle-left'         => esc_html__('Circle Left', 'oxi-hover-effects-addons'),
                                'oxi-image-circle-right'        => esc_html__('Circle Right', 'oxi-hover-effects-addons'),
                                'oxi-image-circle-top-left'     => esc_html__('Circle Top Left', 'oxi-hover-effects-addons'),
                                'oxi-image-circle-top-right'    => esc_html__('Circle Top Right', 'oxi-hover-effects-addons'),
                                'oxi-image-circle-bottom-left'  => esc_html__('Circle Bottom Left', 'oxi-hover-effects-addons'),
                                'oxi-image-circle-bottom-right' => esc_html__('Circle Bottom Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_circle',
                        ],
                        'default'   => 'oxi-image-circle-up',
                ],
        );
        $this->add_control(
                'oxi_image_bounce',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-bounce-in'        => esc_html__('Bounce In', 'oxi-hover-effects-addons'),
                                'oxi-image-bounce-in-up'     => esc_html__('Bounce In Up', 'oxi-hover-effects-addons'),
                                'oxi-image-bounce-in-down'   => esc_html__('Bounce In Down', 'oxi-hover-effects-addons'),
                                'oxi-image-bounce-in-left'   => esc_html__('Bounce In Left', 'oxi-hover-effects-addons'),
                                'oxi-image-bounce-in-right'  => esc_html__('Bounce In Right', 'oxi-hover-effects-addons'),
                                'oxi-image-bounce-out'       => esc_html__('Bounce Out', 'oxi-hover-effects-addons'),
                                'oxi-image-bounce-out-up'    => esc_html__('Bounce Out Up', 'oxi-hover-effects-addons'),
                                'oxi-image-bounce-out-down'  => esc_html__('Bounce Out Down', 'oxi-hover-effects-addons'),
                                'oxi-image-bounce-out-left'  => esc_html__('Bounce Out Left', 'oxi-hover-effects-addons'),
                                'oxi-image-bounce-out-right' => esc_html__('Bounce Out Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_bounce',
                        ],
                        'default'   => 'oxi-image-bounce-in',
                ],
        );
        $this->add_control(
                'oxi_image_border',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-border-reveal'              => esc_html__('Border Reveal', 'oxi-hover-effects-addons'),
                                'oxi-image-border-reveal-vertical'     => esc_html__('Border Reveal Vertical', 'oxi-hover-effects-addons'),
                                'oxi-image-border-reveal-horizontal'   => esc_html__('Border Reveal Horizontal', 'oxi-hover-effects-addons'),
                                'oxi-image-border-reveal-corners-1'    => esc_html__('Border Reveal Corners One', 'oxi-hover-effects-addons'),
                                'oxi-image-border-reveal-corners-2'    => esc_html__('Border Reveal Corners Two', 'oxi-hover-effects-addons'),
                                'oxi-image-border-reveal-top-left'     => esc_html__('Border Reveal Top Left', 'oxi-hover-effects-addons'),
                                'oxi-image-border-reveal-top-right'    => esc_html__('Border Reveal Top Right', 'oxi-hover-effects-addons'),
                                'oxi-image-border-reveal-bottom-left'  => esc_html__('Border Reveal Bottom Left', 'oxi-hover-effects-addons'),
                                'oxi-image-border-reveal-bottom-right' => esc_html__('Border Reveal Bottom Right', 'oxi-hover-effects-addons'),
                                'oxi-image-border-reveal-cc-1'         => esc_html__('Border Reveal CC One', 'oxi-hover-effects-addons'),
                                'oxi-image-border-reveal-ccc-1'        => esc_html__('Border Reveal CCC One', 'oxi-hover-effects-addons'),
                                'oxi-image-border-reveal-cc-2'         => esc_html__('Border Reveal CC Two', 'oxi-hover-effects-addons'),
                                'oxi-image-border-reveal-ccc-2'        => esc_html__('Border Reveal CCC Two', 'oxi-hover-effects-addons'),
                                'oxi-image-border-reveal-cc-3'         => esc_html__('Border Reveal CC Three', 'oxi-hover-effects-addons'),
                                'oxi-image-border-reveal-ccc-3'        => esc_html__('Border Reveal CCC Three', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_border',
                        ],
                        'default'   => 'oxi-image-border-reveal',
                ],
        );
        $this->add_control(
                'oxi_image_zoom',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-zoom-in'                  => esc_html__('Zoom In', 'oxi-hover-effects-addons'),
                                'oxi-image-zoom-out'                 => esc_html__('Zoom Out', 'oxi-hover-effects-addons'),
                                'oxi-image-zoom-out-up'              => esc_html__('Zoom Out Up', 'oxi-hover-effects-addons'),
                                'oxi-image-zoom-out-down'            => esc_html__('Zoom Out Down', 'oxi-hover-effects-addons'),
                                'oxi-image-zoom-out-left'            => esc_html__('Zoom Out Left', 'oxi-hover-effects-addons'),
                                'oxi-image-zoom-out-right'           => esc_html__('Zoom Out Right', 'oxi-hover-effects-addons'),
                                'oxi-image-zoom-out-flip-horizontal' => esc_html__('Zoom Out Flip Horizontal', 'oxi-hover-effects-addons'),
                                'oxi-image-zoom-out-flip-vertical'   => esc_html__('Zoom Out Flip Vertical', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_zoom',
                        ],
                        'default'   => 'oxi-image-zoom-in',
                ],
        );
        $this->add_control(
                'oxi_image_throw',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-throw-in-up'     => esc_html__('Throw In Up', 'oxi-hover-effects-addons'),
                                'oxi-image-throw-in-down'   => esc_html__('Throw In Down', 'oxi-hover-effects-addons'),
                                'oxi-image-throw-in-left'   => esc_html__('Throw In Left', 'oxi-hover-effects-addons'),
                                'oxi-image-throw-in-right'  => esc_html__('Throw In Right', 'oxi-hover-effects-addons'),
                                'oxi-image-throw-out-up'    => esc_html__('Throw Out Up', 'oxi-hover-effects-addons'),
                                'oxi-image-throw-out-down'  => esc_html__('Throw Out Down', 'oxi-hover-effects-addons'),
                                'oxi-image-throw-out-left'  => esc_html__('Throw Out Left', 'oxi-hover-effects-addons'),
                                'oxi-image-throw-out-right' => esc_html__('Throw Out Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_throw',
                        ],
                        'default'   => 'oxi-image-throw-in-up',
                ],
        );
        $this->add_control(
                'oxi_image_book',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-book-open-horizontal' => esc_html__('Book Horizontal', 'oxi-hover-effects-addons'),
                                'oxi-image-book-open-vertical'   => esc_html__('Book Vertical', 'oxi-hover-effects-addons'),
                                'oxi-image-book-open-up'         => esc_html__('Book Open Up', 'oxi-hover-effects-addons'),
                                'oxi-image-book-open-down'       => esc_html__('Book Open Down', 'oxi-hover-effects-addons'),
                                'oxi-image-book-open-left'       => esc_html__('Book Open Left', 'oxi-hover-effects-addons'),
                                'oxi-image-book-open-right'      => esc_html__('Book Open Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_book',
                        ],
                        'default'   => 'oxi-image-book-open-horizontal',
                ],
        );
        $this->add_control(
                'oxi_image_switch',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-switch-up'    => esc_html__('Switch Up', 'oxi-hover-effects-addons'),
                                'oxi-image-switch-down'  => esc_html__('Switch Down', 'oxi-hover-effects-addons'),
                                'oxi-image-switch-left'  => esc_html__('Switch Left', 'oxi-hover-effects-addons'),
                                'oxi-image-switch-right' => esc_html__('Switch Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_switch',
                        ],
                        'default'   => 'oxi-image-switch-up',
                ],
        );
        $this->add_control(
                'oxi_image_strip',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-strip-shutter-up'            => esc_html__('Strip Shutter Up', 'oxi-hover-effects-addons'),
                                'oxi-image-strip-shutter-down'          => esc_html__('Strip Shutter Down', 'oxi-hover-effects-addons'),
                                'oxi-image-strip-shutter-left'          => esc_html__('Strip Shutter Left', 'oxi-hover-effects-addons'),
                                'oxi-image-strip-shutter-right'         => esc_html__('Strip Shutter Right', 'oxi-hover-effects-addons'),
                                'oxi-image-strip-horizontal-up'         => esc_html__('Strip Horizontal Up', 'oxi-hover-effects-addons'),
                                'oxi-image-strip-horizontal-down'       => esc_html__('Strip Horizontal Down', 'oxi-hover-effects-addons'),
                                'oxi-image-strip-horizontal-top-left'   => esc_html__('Strip Horizontal Top Left', 'oxi-hover-effects-addons'),
                                'oxi-image-strip-horizontal-top-right'  => esc_html__('Strip Horizontal Top Right', 'oxi-hover-effects-addons'),
                                'oxi-image-strip-horizontal-left'       => esc_html__('Strip Horizontal Left', 'oxi-hover-effects-addons'),
                                'oxi-image-strip-horizontal-right'      => esc_html__('Strip Horizontal Right', 'oxi-hover-effects-addons'),
                                'oxi-image-strip-vertical-left'         => esc_html__('Strip Vertical Left', 'oxi-hover-effects-addons'),
                                'oxi-image-strip-vertical-right'        => esc_html__('Strip Vertical Right', 'oxi-hover-effects-addons'),
                                'oxi-image-strip-vertical-top-left'     => esc_html__('Strip Vertical Top Left', 'oxi-hover-effects-addons'),
                                'oxi-image-strip-vertical-top-right'    => esc_html__('Strip Vertical Top Right', 'oxi-hover-effects-addons'),
                                'oxi-image-strip-vertical-bottom-left'  => esc_html__('Strip Vertical Bottom Left', 'oxi-hover-effects-addons'),
                                'oxi-image-strip-vertical-bottom-right' => esc_html__('Strip Vertical Bottom Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_strip',
                        ],
                        'default'   => 'oxi-image-strip-shutter-up',
                ],
        );
        $this->add_control(
                'oxi_image_stack',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-stack-up'           => esc_html__('Stack Up', 'oxi-hover-effects-addons'),
                                'oxi-image-stack-down'         => esc_html__('Stack Down', 'oxi-hover-effects-addons'),
                                'oxi-image-stack-left'         => esc_html__('Stack Left', 'oxi-hover-effects-addons'),
                                'oxi-image-stack-right'        => esc_html__('Stack Right', 'oxi-hover-effects-addons'),
                                'oxi-image-stack-top-left'     => esc_html__('Stack Top Left', 'oxi-hover-effects-addons'),
                                'oxi-image-stack-top-right'    => esc_html__('Stack Top Right', 'oxi-hover-effects-addons'),
                                'oxi-image-stack-bottom-left'  => esc_html__('Stack Bottom Left', 'oxi-hover-effects-addons'),
                                'oxi-image-stack-bottom-right' => esc_html__('Stack Bottom Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_stack',
                        ],
                        'default'   => 'oxi-image-stack-up',
                ],
        );
        $this->add_control(
                'oxi_image_splash',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-splash-up'    => esc_html__('Splash Up', 'oxi-hover-effects-addons'),
                                'oxi-image-splash-down'  => esc_html__('Splash Down', 'oxi-hover-effects-addons'),
                                'oxi-image-splash-left'  => esc_html__('Splash Left', 'oxi-hover-effects-addons'),
                                'oxi-image-splash-right' => esc_html__('Splash Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_splash',
                        ],
                        'default'   => 'oxi-image-splash-up',
                ],
        );
        $this->add_control(
                'oxi_image_slide',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-slide-up'           => esc_html__('Slide Up', 'oxi-hover-effects-addons'),
                                'oxi-image-slide-down'         => esc_html__('Slide Down', 'oxi-hover-effects-addons'),
                                'oxi-image-slide-left'         => esc_html__('Slide Left', 'oxi-hover-effects-addons'),
                                'oxi-image-slide-right'        => esc_html__('Slide Right', 'oxi-hover-effects-addons'),
                                'oxi-image-slide-top-left'     => esc_html__('Slide Top Left', 'oxi-hover-effects-addons'),
                                'oxi-image-slide-top-right'    => esc_html__('Slide Top Right', 'oxi-hover-effects-addons'),
                                'oxi-image-slide-bottom-left'  => esc_html__('Slide Bottom Left', 'oxi-hover-effects-addons'),
                                'oxi-image-slide-bottom-right' => esc_html__('Slide Bottom Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_slide',
                        ],
                        'default'   => 'oxi-image-slide-up',
                ],
        );
        $this->add_control(
                'oxi_image_shutter',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-shutter-out-horizontal'    => esc_html__('Shutter Out Horizontal', 'oxi-hover-effects-addons'),
                                'oxi-image-shutter-out-vertical'      => esc_html__('Shutter Out Vertical', 'oxi-hover-effects-addons'),
                                'oxi-image-shutter-out-diagonal-1'    => esc_html__('Shutter Out Diagonal One', 'oxi-hover-effects-addons'),
                                'oxi-image-shutter-out-diagonal-2'    => esc_html__('Shutter Out Diagonal Two', 'oxi-hover-effects-addons'),
                                'oxi-image-shutter-in-horizontal'     => esc_html__('Shutter In Horizontal', 'oxi-hover-effects-addons'),
                                'oxi-image-shutter-in-vertical'       => esc_html__('Shutter In Vertical', 'oxi-hover-effects-addons'),
                                'oxi-image-shutter-in-out-horizontal' => esc_html__('Shutter In Out Horizontal', 'oxi-hover-effects-addons'),
                                'oxi-image-shutter-in-out-vertical'   => esc_html__('Shutter In Out Vertical', 'oxi-hover-effects-addons'),
                                'oxi-image-shutter-in-out-diagonal-1' => esc_html__('Shutter In Out Diagonal One', 'oxi-hover-effects-addons'),
                                'oxi-image-shutter-in-out-diagonal-2' => esc_html__('Shutter In Out Diagonal Two', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_shutter',
                        ],
                        'default'   => 'oxi-image-shutter-out-horizontal',
                ],
        );
        $this->add_control(
                'oxi_image_shift',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-shift-top-left'     => esc_html__('Shift Top Left', 'oxi-hover-effects-addons'),
                                'oxi-image-shift-top-right'    => esc_html__('Shift Top Right', 'oxi-hover-effects-addons'),
                                'oxi-image-shift-bottom-left'  => esc_html__('Shift Bottom Left', 'oxi-hover-effects-addons'),
                                'oxi-image-shift-bottom-right' => esc_html__('Shift Bottom Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_shift',
                        ],
                        'default'   => 'oxi-image-shift-top-left',
                ],
        );
        $this->add_control(
                'oxi_image_rotate',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-rotate-left'  => esc_html__('Rotate Left', 'oxi-hover-effects-addons'),
                                'oxi-image-rotate-right' => esc_html__('Rotate Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_rotate',
                        ],
                        'default'   => 'oxi-image-rotate-left',
                ],
        );
        $this->add_control(
                'oxi_image_reveal',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-reveal-up'           => esc_html__('Reveal Up', 'oxi-hover-effects-addons'),
                                'oxi-image-reveal-down'         => esc_html__('Reveal Down', 'oxi-hover-effects-addons'),
                                'oxi-image-reveal-left'         => esc_html__('Reveal Left', 'oxi-hover-effects-addons'),
                                'oxi-image-reveal-right'        => esc_html__('Reveal Right', 'oxi-hover-effects-addons'),
                                'oxi-image-reveal-top-left'     => esc_html__('Reveal Top Left', 'oxi-hover-effects-addons'),
                                'oxi-image-reveal-top-right'    => esc_html__('Reveal Top Right', 'oxi-hover-effects-addons'),
                                'oxi-image-reveal-bottom-left'  => esc_html__('Reveal Bottom Left', 'oxi-hover-effects-addons'),
                                'oxi-image-reveal-bottom-right' => esc_html__('Reveal Bottom Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_reveal',
                        ],
                        'default'   => 'oxi-image-reveal-up',
                ],
        );
        $this->add_control(
                'oxi_image_push',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-push-up'    => esc_html__('Push Up', 'oxi-hover-effects-addons'),
                                'oxi-image-push-down'  => esc_html__('Push Down', 'oxi-hover-effects-addons'),
                                'oxi-image-push-left'  => esc_html__('Push Left', 'oxi-hover-effects-addons'),
                                'oxi-image-push-right' => esc_html__('Push Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_push',
                        ],
                        'default'   => 'oxi-image-push-up',
                ],
        );
        $this->add_control(
                'oxi_image_fall_away',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-fall-away-horizontal' => esc_html__('Fall Away Horizontal', 'oxi-hover-effects-addons'),
                                'oxi-image-fall-away-vertical'   => esc_html__('Fall Away Vertical', 'oxi-hover-effects-addons'),
                                'oxi-image-fall-away-cc'         => esc_html__('Fall Away CC', 'oxi-hover-effects-addons'),
                                'oxi-image-fall-away-ccc'        => esc_html__('Fall Away CCC', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_fall_away',
                        ],
                        'default'   => 'oxi-image-fall-away-horizontal',
                ],
        );
        $this->add_control(
                'oxi_image_parallax',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-parallax-up'    => esc_html__('Parallax Up', 'oxi-hover-effects-addons'),
                                'oxi-image-parallax-down'  => esc_html__('Parallax Down', 'oxi-hover-effects-addons'),
                                'oxi-image-parallax-left'  => esc_html__('Parallax Left', 'oxi-hover-effects-addons'),
                                'oxi-image-parallax-right' => esc_html__('Parallax Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_parallax',
                        ],
                        'default'   => 'oxi-image-parallax-up',
                ],
        );
        $this->add_control(
                'oxi_image_pixel',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-pixel-up'           => esc_html__('Pixel Up', 'oxi-hover-effects-addons'),
                                'oxi-image-pixel-down'         => esc_html__('Pixel Down', 'oxi-hover-effects-addons'),
                                'oxi-image-pixel-left'         => esc_html__('Pixel Left', 'oxi-hover-effects-addons'),
                                'oxi-image-pixel-right'        => esc_html__('Pixel Right', 'oxi-hover-effects-addons'),
                                'oxi-image-pixel-top-left'     => esc_html__('Pixel Top Left', 'oxi-hover-effects-addons'),
                                'oxi-image-pixel-top-right'    => esc_html__('Pixel Top Right', 'oxi-hover-effects-addons'),
                                'oxi-image-pixel-bottom-left'  => esc_html__('Pixel Bottom Left', 'oxi-hover-effects-addons'),
                                'oxi-image-pixel-bottom-right' => esc_html__('Pixel Bottom Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_pixel',
                        ],
                        'default'   => 'oxi-image-pixel-up',
                ],
        );
        $this->add_control(
                'oxi_image_pivot',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-pivot-in-top-left'      => esc_html__('Pivot In Top Left', 'oxi-hover-effects-addons'),
                                'oxi-image-pivot-in-top-right'     => esc_html__('Pivot In Top Right', 'oxi-hover-effects-addons'),
                                'oxi-image-pivot-in-bottom-left'   => esc_html__('Pivot In Bottom Left', 'oxi-hover-effects-addons'),
                                'oxi-image-pivot-in-bottom-right'  => esc_html__('Pivot In Bottom Right', 'oxi-hover-effects-addons'),
                                'oxi-image-pivot-out-top-left'     => esc_html__('Pivot Out Top Left', 'oxi-hover-effects-addons'),
                                'oxi-image-pivot-out-top-right'    => esc_html__('Pivot Out Top Right', 'oxi-hover-effects-addons'),
                                'oxi-image-pivot-out-bottom-left'  => esc_html__('Pivot Out Bottom Left', 'oxi-hover-effects-addons'),
                                'oxi-image-pivot-out-bottom-right' => esc_html__('Pivot Out Bottom Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_pivot',
                        ],
                        'default'   => 'oxi-image-pivot-in-top-left',
                ],
        );
        $this->add_control(
                'oxi_image_modal',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-modal-slide-up'    => esc_html__('Modal Slide Up', 'oxi-hover-effects-addons'),
                                'oxi-image-modal-slide-down'  => esc_html__('Modal Slide Down', 'oxi-hover-effects-addons'),
                                'oxi-image-modal-slide-left'  => esc_html__('Modal Slide Left', 'oxi-hover-effects-addons'),
                                'oxi-image-modal-slide-right' => esc_html__('Modal Slide Right', 'oxi-hover-effects-addons'),
                                'oxi-image-modal-hinge-up'    => esc_html__('Modal Hinge Up', 'oxi-hover-effects-addons'),
                                'oxi-image-modal-hinge-down'  => esc_html__('Modal Hinge Down', 'oxi-hover-effects-addons'),
                                'oxi-image-modal-hinge-left'  => esc_html__('Modal Hinge Left', 'oxi-hover-effects-addons'),
                                'oxi-image-modal-hinge-right' => esc_html__('Modal Hinge Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_modal',
                        ],
                        'default'   => 'oxi-image-modal-slide-up',
                ],
        );
        $this->add_control(
                'oxi_image_lightspeed',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-lightspeed-in-left'   => esc_html__('Lightspeed In Left', 'oxi-hover-effects-addons'),
                                'oxi-image-lightspeed-in-right'  => esc_html__('Lightspeed In Right', 'oxi-hover-effects-addons'),
                                'oxi-image-lightspeed-out-left'  => esc_html__('Lightspeed Out Left', 'oxi-hover-effects-addons'),
                                'oxi-image-lightspeed-out-right' => esc_html__('Lightspeed Out Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_lightspeed',
                        ],
                        'default'   => 'oxi-image-lightspeed-in-left',
                ],
        );
        $this->add_control(
                'oxi_image_hinge',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-hinge-up'    => esc_html__('Hinge Up', 'oxi-hover-effects-addons'),
                                'oxi-image-hinge-down'  => esc_html__('Hinge Down', 'oxi-hover-effects-addons'),
                                'oxi-image-hinge-left'  => esc_html__('Hinge Left', 'oxi-hover-effects-addons'),
                                'oxi-image-hinge-right' => esc_html__('Hinge Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_hinge',
                        ],
                        'default'   => 'oxi-image-hinge-up',
                ],
        );
        $this->add_control(
                'oxi_image_fold',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-fold-up'    => esc_html__('Fold Up', 'oxi-hover-effects-addons'),
                                'oxi-image-fold-down'  => esc_html__('Fold Down', 'oxi-hover-effects-addons'),
                                'oxi-image-fold-left'  => esc_html__('Fold Left', 'oxi-hover-effects-addons'),
                                'oxi-image-fold-right' => esc_html__('Fold Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_fold',
                        ],
                        'default'   => 'oxi-image-fold-up',
                ],
        );
        $this->add_control(
                'oxi_image_flip',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-flip-horizontal' => esc_html__('Flip Horizontal', 'oxi-hover-effects-addons'),
                                'oxi-image-flip-vertical'   => esc_html__('Flip Vertical', 'oxi-hover-effects-addons'),
                                'oxi-image-flip-diagonal-1' => esc_html__('Flip Diagoanl One', 'oxi-hover-effects-addons'),
                                'oxi-image-flip-diagonal-2' => esc_html__('Flip Diagoanl Two', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_flip',
                        ],
                        'default'   => 'oxi-image-flip-horizontal',
                ],
        );
        $this->add_control(
                'oxi_image_flash',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-flash-top-left'     => esc_html__('Flash Top Left', 'oxi-hover-effects-addons'),
                                'oxi-image-flash-top-right'    => esc_html__('Flash Top Right', 'oxi-hover-effects-addons'),
                                'oxi-image-flash-bottom-left'  => esc_html__('Flash Bottom Left', 'oxi-hover-effects-addons'),
                                'oxi-image-flash-bottom-right' => esc_html__('Flash Bottom Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_flash',
                        ],
                        'default'   => 'oxi-image-flash-top-left',
                ],
        );

        $this->add_control(
                'oxi_image_blocks',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-blocks-rotate-left'       => esc_html__('Block Rotate Left', 'oxi-hover-effects-addons'),
                                'oxi-image-blocks-rotate-right'      => esc_html__('Block Rotate Right', 'oxi-hover-effects-addons'),
                                'oxi-image-blocks-rotate-in-left'    => esc_html__('Block Rotate In Left', 'oxi-hover-effects-addons'),
                                'oxi-image-blocks-rotate-in-right'   => esc_html__('Block Rotate In Right', 'oxi-hover-effects-addons'),
                                'oxi-image-blocks-in'                => esc_html__('Block In', 'oxi-hover-effects-addons'),
                                'oxi-image-blocks-out'               => esc_html__('Block Out', 'oxi-hover-effects-addons'),
                                'oxi-image-blocks-float-up'          => esc_html__('Block Float Up', 'oxi-hover-effects-addons'),
                                'oxi-image-blocks-float-down'        => esc_html__('Block Float Down', 'oxi-hover-effects-addons'),
                                'oxi-image-blocks-float-left'        => esc_html__('Block Float Left', 'oxi-hover-effects-addons'),
                                'oxi-image-blocks-float-right'       => esc_html__('Block Float Right', 'oxi-hover-effects-addons'),
                                'oxi-image-blocks-zoom-top-left'     => esc_html__('Block Zoom Top Left', 'oxi-hover-effects-addons'),
                                'oxi-image-blocks-zoom-top-right'    => esc_html__('Block Zoom Top Right', 'oxi-hover-effects-addons'),
                                'oxi-image-blocks-zoom-bottom-left'  => esc_html__('Block Zoom Bottom Left', 'oxi-hover-effects-addons'),
                                'oxi-image-blocks-zoom-bottom-right' => esc_html__('Block Zoom Bottom Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_blocks',
                        ],
                        'default'   => 'oxi-image-blocks-rotate-left',
                ],
        );
        $this->add_control(
                'oxi_image_blinds',
                [
                        'label'     => esc_html__('Effects Direction', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                                'oxi-image-blinds-horizontal' => esc_html__('Blinds Horizontal', 'oxi-hover-effects-addons'),
                                'oxi-image-blinds-vertical'   => esc_html__('Blinds Vertical', 'oxi-hover-effects-addons'),
                                'oxi-image-blinds-up'         => esc_html__('Blinds Up', 'oxi-hover-effects-addons'),
                                'oxi-image-blinds-down'       => esc_html__('Blinds Down', 'oxi-hover-effects-addons'),
                                'oxi-image-blinds-left'       => esc_html__('Blinds Left', 'oxi-hover-effects-addons'),
                                'oxi-image-blinds-right'      => esc_html__('Blinds Right', 'oxi-hover-effects-addons'),
                        ],
                        'condition' => [
                                'oxi_addons_effects' => 'oxi_image_blinds',
                        ],
                        'default'   => 'oxi-image-blinds-horizontal',
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
                        'label'      => __('Opacity', 'oxi-hover-effects-addons'),
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
                                '{{WRAPPER}} .oxi-image-caption-style .oxi-image-hover-image' => 'opacity: {{SIZE}};',
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
                        'label_on'     => __('Block', 'oxi-hover-effects-addons'),
                        'label_off'    => __('Inline', 'oxi-hover-effects-addons'),
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
                        'label_on'     => __('Block', 'oxi-hover-effects-addons'),
                        'label_off'    => __('Inline', 'oxi-hover-effects-addons'),
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
                        'label'         => __('Link To', 'oxi-hover-effects-addons'),
                        'type'          => Controls_Manager::URL,
                        'placeholder'   => __('https://your-link.com', 'oxi-hover-effects-addons'),
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
                        'label_on'     => __('Block', 'oxi-hover-effects-addons'),
                        'label_off'    => __('Inline', 'oxi-hover-effects-addons'),
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
                        'label'         => __('Link To', 'oxi-hover-effects-addons'),
                        'type'          => Controls_Manager::URL,
                        'placeholder'   => __('https://your-link.com', 'oxi-hover-effects-addons'),
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
                        'label'       => __('Link Or Lightbox', 'plugin-domain'),
                        'label_block' => true,
                        'type'        => Controls_Manager::SELECT,
                        'default'     => '',
                        'options'     => [
                                ''         => __('None', 'plugin-domain'),
                                'link'     => __('URL', 'plugin-domain'),
                                'lightbox' => __('Light Box', 'plugin-domain'),
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
                        'label'         => __('Link To', 'oxi-hover-effects-addons'),
                        'type'          => Controls_Manager::URL,
                        'placeholder'   => __('https://your-link.com', 'oxi-hover-effects-addons'),
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

        $this->add_group_control(Group_Control_Background::get_type(), [
                'name'      => "content_style_background",
                'label'     => __('Background', 'oxi-hover-effects-addons'),
                'types'     => ['classic', 'gradient'],
                'condition' => [
                        'oxi_addons_effects' => [
                                'oxi_image_border',
                                'oxi_image_bounce',
                                'oxi_image_cube',
                                'oxi_image_dive',
                                'oxi_image_fade',
                                'oxi_image_fall_away',
                                'oxi_image_flip',
                                'oxi_image_fold',
                                'oxi_image_hinge',
                                'oxi_image_lightspeed',
                                'oxi_image_modal',
                                'oxi_image_parallax',
                                'oxi_image_pivot',
                                'oxi_image_push',
                                'oxi_image_reveal',
                                'oxi_image_rotate',
                                'oxi_image_shift',
                                'oxi_image_slide',
                                'oxi_image_stack',
                                'oxi_image_switch',
                                'oxi_image_throw',
                                'oxi_image_zoom',
                        ],
                ],
                'selector'  => '{{WRAPPER}} .oxi-image-caption-hover .oxi-image-hover-figure-caption'
        ]);

        $this->add_control(
                'general_style_background',
                [
                        'label'     => esc_html__('Background', 'oxi-hover-effects-addons'),
                        'type'      => Controls_Manager::COLOR,
                        'default'   => '',
                        'condition' => [
                                'oxi_addons_effects' => [
                                        'oxi_image_blinds',
                                        'oxi_image_blocks',
                                        'oxi_image_book',
                                        'oxi_image_circle',
                                        'oxi_image_flash',
                                        'oxi_image_pixel',
                                        'oxi_image_shutter',
                                        'oxi_image_splash',
                                        'oxi_image_strip',
                                ],
                        ],
                        'selectors' => [
                                "   {{WRAPPER}} .oxi-image-caption-hover,
                            {{WRAPPER}} .oxi-image-caption-hover:before,
                            {{WRAPPER}} .oxi-image-caption-hover:after,
                            {{WRAPPER}} .oxi-image-caption-hover .oxi-image-hover-figure,
                            {{WRAPPER}} .oxi-image-caption-hover .oxi-image-hover-figure:before,
                            {{WRAPPER}} .oxi-image-caption-hover .oxi-image-hover-figure:after,
                            {{WRAPPER}} .oxi-image-caption-hover .oxi-image-hover-figure-caption,
                            {{WRAPPER}} .oxi-image-caption-hover .oxi-image-hover-figure-caption:before,
                            {{WRAPPER}} .oxi-image-caption-hover .oxi-image-hover-figure-caption:after" => "background: {{VALUE}};",
                        ]
                ],
        );

        $this->add_responsive_control(
                'general_align',
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
                        'default' => '',
                ],
        );

        $this->add_responsive_control(
                'general_vertical_align',
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
                        'default' => '',
                ],
        );

        $this->add_responsive_control(
                'general_style_width',
                [
                        'label'      => __('Width', 'oxi-hover-effects-addons'),
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
                                '{{WRAPPER}} .oxi-image-caption-style' => 'width: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );
        $this->add_responsive_control(
                'general_style_height',
                [
                        'label'      => __('Height', 'oxi-hover-effects-addons'),
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
                                '{{WRAPPER}} .oxi-image-caption-style .oxi-image-caption-hover .oxi-image-hover-image img' => 'height: {{SIZE}}{{UNIT}};',
                        ],
                ],
        );

        $this->add_responsive_control(
                'general_style_padding',
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
                'general_style_margin',
                [
                        'label'      => esc_html__('Margin', 'oxi-hover-effects-addons'),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => ['px', 'em', '%'],
                        'selectors'  => [
                                '{{WRAPPER}} .oxi-image-caption-style-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .oxi-image-caption-hover,'
                                . '{{WRAPPER}}  .oxi-image-hover-figure, '
                                . '{{WRAPPER}}  .oxi-image-hover-image, '
                                . '{{WRAPPER}}  .oxi-image-hover-image img, '
                                . '{{WRAPPER}}  .oxi-image-hover-figure-caption, '
                                . '{{WRAPPER}}  .oxi-image-hover-figure-tabs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden ;',
                        ],
                ],
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                        'name'     => 'general_style_box_shadow',
                        'selector' => '{{WRAPPER}} .oxi-image-caption-hover',
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
                        'selector' => '{{WRAPPER}} .oxi-image-caption-hover .oxi-image-hover-addons-heading',
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
                                '{{WRAPPER}} .oxi-image-caption-hover .oxi-image-hover-addons-heading' => 'text-align: {{VALUE}};',
                        ],
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
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                        'name'     => 'desc_style_settings_typography',
                        'selector' => '{{WRAPPER}} .oxi-image-caption-hover .oxi-image-hover-addons-cont',
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
                                '{{WRAPPER}} .oxi-image-caption-hover .oxi-image-hover-addons-cont' => 'text-align: {{VALUE}};',
                        ],
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
                        'label'      => __('Icon Size', 'oxi-hover-effects-addons'),
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
                        'label_on'     => __('Advanced', 'oxi-hover-effects-addons'),
                        'label_off'    => __('simple', 'oxi-hover-effects-addons'),
                        'default'      => '',
                        'return_value' => 'advance',
                ],
        );
        $this->add_responsive_control(
                'icon_style_width',
                [
                        'label'      => __('Width Height', 'oxi-hover-effects-addons'),
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
                        'default'   => 'rgba(126, 0, 184, 1)',
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
                        'default'   => 'rgba(126, 0, 184, 1)',
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
                        'label'      => __('Font Size', 'oxi-hover-effects-addons'),
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
                'button_style_size_icon_gap',
                [
                        'label'      => __('Icon Gap', 'oxi-hover-effects-addons'),
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
                                '{{WRAPPER}} .oxi-image-caption-style .oxi-image-hover-addons-button .oxi-image-hover-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .oxi-image-caption-style .oxi-image-hover-addons-button .oxi-image-hover-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .oxi-image-caption-style .oxi-image-hover-addons-button .oxi-image-hover-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden;',
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
        $align            = isset($settings['general_align']) ? ' oxi-image-hover-figure-horizontal-align-' . esc_attr($settings['general_align']) : '';
        $align            .= isset($settings['general_vertical_align']) ? ' oxi-image-hover-figure-vertical-align-' . esc_attr($settings['general_vertical_align']) : '';
        ?>
        <div class="oxi-image-hover-style oxi-image-caption-style <?php echo $style ?>">
            <?php
            $LinkBox          = $settings['link_or_lightbox'] != '' ? true : false;
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
                    ?>
                        <div class="oxi-image-caption-style-caption">
                                <div class="oxi-image-hover oxi-image-caption-hover <?php echo $effects ?>">
                                        <div class="oxi-image-hover-figure">
                                                <div class="oxi-image-hover-image  <?php echo $image_animation ?>">
                                                    <?php echo Group_Control_Image_Size::get_attachment_image_html($settings, 'oxi_image_thumbnail', 'oxi_image'); ?>
                                                </div>

                                                <div class="oxi-image-hover-figure-caption">
                                                    <?php
                                                    if ($content_position) {
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
                                                                $iconlink  = $value['content_icon_link_type'] != '' ? true : false;
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
