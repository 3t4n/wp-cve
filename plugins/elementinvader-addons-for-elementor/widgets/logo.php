<?php

namespace ElementinvaderAddonsForElementor\Widgets;

use ElementinvaderAddonsForElementor\Core\Elementinvader_Base;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Typography;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use ElementinvaderAddonsForElementor\Modules\Forms\Ajax_Handler;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class EliLogo extends Elementinvader_Base {

    public $view_folder = 'logo';

    // Default widget settings
    public function __construct($data = array(), $args = null) {

        \Elementor\Controls_Manager::add_tab(
                'tab_content',
                esc_html__('Main', 'elementinvader-addons-for-elementor')
        );

        parent::__construct($data, $args);
    }

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'eli-logo';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Eli Logo', 'elementinvader-addons-for-elementor');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-logo';
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function register_controls() {

        $this->start_controls_section(
            'logo_main',
            [
                    'label' => __('Main', 'elementinvader-addons-for-elementor'),
                    'tab' => 1,
            ]
    );
    $post_id = get_the_ID();
    $post_object_id = get_queried_object_id();
    if($post_object_id)
        $post_id = $post_object_id;
        
    $this->add_control(
        'logo_main_change_logo_text_hint',
        [
            'label' => '',
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => sprintf(__( 'Change WP default Logos <a href="%1$s" target="_blank"> open </a>', 'elementinvader-addons-for-elementor' ), add_query_arg(['autofocus[section]'=>'title_tagline', 'page_id'=>intval($post_id)], admin_url( 'customize.php' )))
                    .'<br/><br/>'.__( '1. Go to Appearance >> Customizer.', 'elementinvader-addons-for-elementor' )
                    .'<br/>'.__( '2. Expand the Site Identity section by clicking on it.', 'elementinvader-addons-for-elementor' )
                    .'<br/>'.__( '3. Upload your logo image file (gif, jpeg or png).', 'elementinvader-addons-for-elementor' )
                    .'<br/>'.__( '4. Hit the Publish button.', 'elementinvader-addons-for-elementor' ),
            'content_classes' => 'elo_elementor_hint',
            'separator' => 'before',
        ]
    );

    $this->add_control(
        'logo_image_footer_enable',
        [
            'label' => __( 'Logo version Footer / Header', 'elementinvader-addons-for-elementor' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __( 'On', 'elementinvader-addons-for-elementor' ),
            'label_off' => __( 'Off', 'elementinvader-addons-for-elementor' ),
            'return_value' => 'yes',
            'default' => '',
        ]
    );
        
    $this->add_control(
        'logo_main_change_logo_hint',
        [
            'label' => '',
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw' => sprintf(__( 'Change WP default Site Title <a href="%1$s" target="_blank"> open </a>', 'elementinvader-addons-for-elementor' ), add_query_arg(['autofocus[section]'=>'title_tagline', 'page_id'=>intval($post_id)], admin_url( 'customize.php' ))),
            'content_classes' => 'elo_elementor_hint',
        ]
    );

    $this->add_control(
        'custom_logo_image',
        [
            'label' => esc_html__( 'Custom Logo Image', 'elementinvader-addons-for-elementor' ),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'separator' => 'before',
        ]
    );

    $this->add_control(
        'custom_logo_url',
        [
            'label' => esc_html__( 'Custom Link', 'elementinvader-addons-for-elementor' ),
            'type' => \Elementor\Controls_Manager::URL,
            'placeholder' => esc_html__( 'https://your-link.com', 'elementinvader-addons-for-elementor' ),
            'options' => [ 'url', 'is_external' ],
            'default' => [
                'url' => '',
                'is_external' => true,
            ],
            'label_block' => true,
        ]
    );

    $this->end_controls_section();

        $this->start_controls_section (
            'logo_section_img',
            [
                'label' => __( 'Logo Image', 'elementinvader-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'logo_section_img_link_header',
            [
                'label' => esc_html__('Link', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $selectors = array(
            'normal' => '{{WRAPPER}} .widget-eli.eli-logo .custom-logo-link',
            'hover' => '{{WRAPPER}} .widget-eli.eli-logo .custom-logo-link%1$s'
        );

        $this->generate_renders_tabs($selectors, 'logo_section_img_link_dynamic', 'full');

        $this->add_control(
            'logo_section_img_header',
            [
                'label' => esc_html__('Image', 'elementinvader-addons-for-elementor'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $selectors = array(
            'normal' => '{{WRAPPER}} .widget-eli.eli-logo .custom-logo-link img',
            'hover' => '{{WRAPPER}} .widget-eli.eli-logo .custom-logo-link im%1$s'
        );

        $this->generate_renders_tabs($selectors, 'logo_section_img_dynamic',['margin','align','background','border','border_radius','padding','shadow','transition','image_size_control','css_filters']);

        $this->end_controls_section();

        $this->start_controls_section (
            'logo_text_section',
            [
                'label' => __( 'Logo Text', 'elementinvader-addons-for-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $selectors = array(
            'normal' => '{{WRAPPER}} .widget-eli.eli-logo .eli-logo-link-text',
            'hover' => '{{WRAPPER}} .widget-eli.eli-logo .eli-logo-link-text%1$s'
        );

        $this->generate_renders_tabs($selectors, 'logo_text_section_dynamic', 'full');

        $this->end_controls_section();


        parent::register_controls();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function render() {
        parent::render();

        $id_int = substr($this->get_id_int(), 0, 3);
        $settings = $this->get_settings();

        $object = ['settings'=>$settings, 'id_int'=>$id_int];

        $object['link'] = home_url( '/' );
        $object['link_new_window'] = false;
        if(!empty($settings['custom_logo_url']['url'])) {
            $object['link'] = $settings['custom_logo_url']['url'];
            if(empty($settings['custom_logo_url']['is_external'])) {
                $object['link_new_window'] = true;
            }
        }

        $object['is_edit_mode'] = false;          
        if(Plugin::$instance->editor->is_edit_mode())
            $object['is_edit_mode'] = true;

        $object['baseTimestamp'] = time();
        if(!empty($settings['baseTimestamp'])) {
            $object['baseTimestamp'] = strtotime($settings['baseTimestamp']);
        }

        echo $this->view('widget_layout', $object); 
    }

}
