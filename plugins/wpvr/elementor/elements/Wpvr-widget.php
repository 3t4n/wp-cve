<?php

namespace WpvrElement\Elements\Wpvr;

use Elementor\Controls_Stack;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Doserx Search
 *
 * Elementor widget for doserx search.
 *
 * @since 1.0.0
 */
class Wpvr_Widget extends Widget_Base {

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'Wpvr-widget';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'WPVR', 'wpvr' );
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'icon-wpvrtourmake_icon';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'general' ];
    }

    /**
     * Retrieve the list of scripts the widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends() {
        return [ 'Wpvr-widget' ];
    }


    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and Wpvrize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function init_controls() {
        if ( version_compare(ELEMENTOR_VERSION, '3.1.0', '>=') ) {
            $this->register_controls();
        } else {
            $this->_register_controls();
        }
    }

    protected function _register_controls() {
        $this->wpvr_shortcode_controls();
    }

    protected function register_controls() {
        $this->wpvr_shortcode_controls();
    }

    /**
     * Register shortcode Controls.
     *
     * @access protected
     */
    protected function wpvr_shortcode_controls(){

        /**
         * Nasim
         * get all tour info and store in $wpvr_post
         */
        $the_posts = get_posts(array('post_type' => 'wpvr_item',
            'posts_per_page' => -1));

        $wpvr_post = array();

        foreach($the_posts as $post){
            if($post->post_title){
                $wpvr_post[$post->ID] = $post->post_title.' : '.$post->ID;
            }else{
                $wpvr_post[$post->ID] = 'No title'  .' : '.$post->ID;
            }
        }

        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'WPVR Setup', 'wpvr' ),
            ]
        );

        /**
         * Nasim
         * add a select type field instead of text field
         */
        $this->add_control(
            'vr_id',
            [
                'label' => __( 'ID:', 'wpvr' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $wpvr_post
            ]
        );
        $this->add_control(
            'vr_width',
            [
                'label' => __( 'Width:', 'wpvr' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __( '', 'wpvr' ),
            ]
        );

        $this->add_control(
            'vr_height',
            [
                'label' => __( 'Height:', 'wpvr' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __( 'Put value in (px)', 'wpvr' ),
            ]
        );

        $this->add_control(
            'vr_radius',
            [
                'label' => __( 'Radius:', 'wpvr' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => __( '', 'wpvr' ),
            ]
        );

        $this->end_controls_section();

    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();
        $id = 0;
        $width = "600px";
        $height = "400px";
        $radius = "0px";
        $id = $settings['vr_id'];
        $width = $settings['vr_width'];
        $height = $settings['vr_height'];
        $radius = $settings['vr_radius'];
        if (empty($width)) {
            $width = "600px";
        }
        if (empty($height)) {
            $height = "400px";
        }
        if (empty($radius)) {
            $radius = "0px";
        }

        if ($id) {
            $shortcode = do_shortcode( shortcode_unautop( '[wpvr id="'.$id.'" width="'.$width.'" height="'.$height.'" radius="'.$radius.'"]'  ) );
            echo $shortcode;
        }
    }

    /**
     * Print element template.
     *
     * Used to generate the element template on the editor.
     *
     * @since 2.0.0
     * @access public
     */
    public function print_template() {
        ob_start();

        if ( version_compare(ELEMENTOR_VERSION, '3.1.0', '>=') ) {
            $this->content_template();
        } else {
            $this->_content_template();
        }

        $template_content = ob_get_clean();

        $element_type = $this->get_type();

        /**
         * Template content.
         *
         * Filters the controls stack template content before it's printed in the editor.
         *
         * The dynamic portion of the hook name, `$element_type`, refers to the element type.
         *
         * @since 1.0.0
         *
         * @param string         $content_template The controls stack template in the editor.
         * @param Controls_Stack $this             The controls stack.
         */
        $template_content = apply_filters( "elementor/{$element_type}/print_template", $template_content, $this );

        if ( empty( $template_content ) ) {
            return;
        }
        ?>
        <script type="text/html" id="tmpl-elementor-<?php echo esc_attr( $this->get_name() ); ?>-content">
            <?php $this->print_template_content( $template_content ); ?>
        </script>
        <?php
    }
    protected function _content_template() {

    }
    protected function content_template() {

    }
}
