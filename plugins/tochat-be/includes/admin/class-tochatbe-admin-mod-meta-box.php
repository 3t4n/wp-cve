<?php
defined( 'ABSPATH' ) || exit;

class TOCHATBE_Admin_Mod_Meta_Box {

    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_agent_meta_data' ) );
    }

    public function register_meta_boxes() {
        add_meta_box(
            'tochatbe-mod-metabox',
            'TOCHAT.BE',
            array( $this, 'meta_box' ),
            array( 'page', 'post' ),
            'side',
            'high'
        );

    }

    public function meta_box() {
        global $post;

        $about_message = get_post_meta( $post->ID, '_tochatbe_about_message', true );

        require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/meta-boxes/html-widget-mod.php';
    }

    public function save_agent_meta_data( $post_id ) {
        global $post, $wpdb;

        if ( $post_id == null || empty( $_POST ) ) {
            return;
        }
        if ( wp_is_post_revision( $post_id ) ) {
            $post_id = wp_is_post_revision( $post_id );
        }

        if ( isset( $_POST['_tochatbe_about_message'] ) ) {
            update_post_meta( $post->ID, '_tochatbe_about_message', sanitize_textarea_field( $_POST['_tochatbe_about_message'] ) );
        }

    }

}

new TOCHATBE_Admin_Mod_Meta_Box;