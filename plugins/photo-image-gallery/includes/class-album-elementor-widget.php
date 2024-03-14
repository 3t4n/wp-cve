<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class UXGallery_Album_Elementor_Widget extends \Elementor\Widget_Base
{
    public function get_name() {
        return 'uxgallery_album';
    }

    public function get_title() {
        return __( 'UXGallery Album', 'uxgallery' );
    }

    public function get_icon() {
        return 'fa fa-images';
    }

    public function get_categories() {
        return array('basic');
    }

    protected function _register_controls() {
        global $wpdb;
        $albumsTable = $wpdb->prefix.'ux_gallery_albums';
        $albums = $wpdb->get_results("SELECT id, name FROM `".$albumsTable."` order by id desc ");

        $albumOptions = array(
            0  => __( 'Select', 'uxgallery' )
        );

        if(!empty($albums)) {
            foreach ($albums as $album) {
                $albumOptions[$album->id] = $album->name;
            }
        }

        $this->start_controls_section(
            'content_section',
            array(
                'label' => __( 'Content', 'uxgallery' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'album_id',
            array(
                'label' => __( 'Select Gallery', 'uxgallery' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 0,
                'options' => $albumOptions,
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        echo do_shortcode('[uxgallery_album id="'.$settings['album_id'].'"]');
    }
}