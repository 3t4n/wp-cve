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
class EliPageLoader extends Elementinvader_Base {

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
        return 'eli-pageloader';
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
        return esc_html__('Eli Page Loader', 'elementinvader-addons-for-elementor');
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
        return 'eicon-product-title';
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

        /* START Section settings_source */
        $this->start_controls_section(
            'tab_settings_section_basic',
            [
                'label' => esc_html__('Basic', 'elementinvader-addons-for-elementor'),
                'tab' => '1',
            ]
        );

        $this->add_control(
            'content_id',
            [
                'label' => __( 'Page/Post/Template ID', 'elementinvader-addons-for-elementor' ),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
            );


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

        $content = '';
        if(!empty($settings['content_id'])){
            $post_data = get_post($settings['content_id']);
            if($post_data){
                if($post_data->post_type == 'page' || $post_data->post_type == 'elementor_library') {
                    $elementor_instance = \Elementor\Plugin::instance();
                    $content = $elementor_instance->frontend->get_builder_content_for_display($settings['content_id']);
                    if(empty($content ))
                        $content = $post_data->post_content;
                } else {
                    $content = $post_data->post_content;
                }
            }
        }
        ?>
        <div class="widget-eli eli_pageloader" id="eli_<?php echo esc_html($this->get_id_int());?>">
        <?php if(eli_user_in_role('administrator')):?>
        <div class="eli_header"><a href="<?php echo esc_url(admin_url('wp-admin/post.php?post='.$settings['content_id'].'&action=edit'));?>"><?php echo esc_html__( 'Edit Content', 'elementinvader-addons-for-elementor' );?></a></div>
        <?php endif;?>
            <div>
                <?php echo wp_kses_post($content);?>
            </div>
        </div>
        <?php
    }

}
