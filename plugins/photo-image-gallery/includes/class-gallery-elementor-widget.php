<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class UXGallery_Gallery_Elementor_Widget extends \Elementor\Widget_Base
{
    public function get_name() {
        return 'uxgallery_gallery';
    }

    public function get_title() {
        return __( 'UXGallery Gallery', 'uxgallery' );
    }

    public function get_icon() {
        return 'fa fa-image';
    }

    public function get_categories() {
        return array('basic');
    }

    protected function _register_controls() {
        global $wpdb;
        $galleriesTable = $wpdb->prefix.'ux_gallery_gallerys';
        $galleries = $wpdb->get_results("SELECT id, name FROM `".$galleriesTable."` order by id desc ");

        $galleryOptions = array(
            0  => __( 'Select', 'uxgallery' )
        );

        if(!empty($galleries)) {
            foreach ($galleries as $gallery) {
                $galleryOptions[$gallery->id] = $gallery->name;
            }
        }

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'uxgallery' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'gallery_id',
            [
                'label' => __( 'Select Gallery', 'uxgallery' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 0,
                'options' => $galleryOptions,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        echo do_shortcode('[uxgallery id="'.$settings['gallery_id'].'"]');
    }
}