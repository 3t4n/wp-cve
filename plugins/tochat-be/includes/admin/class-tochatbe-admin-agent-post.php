<?php
defined( 'ABSPATH' ) || exit;

class TOCHATBE_Admin_Agent_Post {

    public function __construct() {
        add_action( 'init', array( $this, 'setup_post_type' ) );
        add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_agent_meta_data' ) );
        add_filter( 'manage_edit-tochatbe_agent_columns', array( $this, 'edit_table_columns' ) );
        add_action( 'manage_tochatbe_agent_posts_custom_column', array( $this, 'manage_table_columns' ), 10, 2 );
    }

    public function setup_post_type() {
        $args = array(
            'public'            => true,
            'label'             => 'Agents',
            'has_archive'       => true,
            'show_in_menu'      => 'to-chat-be-whatsapp',
            'show_in_admin_bar' => false,
            'supports'          => array( 'thumbnail' ),
        );
        register_post_type( 'tochatbe_agent', $args );
    }

    public function register_meta_boxes() {
        add_meta_box(
            'tochatbe-agent-metabox',
            'Agent Data',
            array( $this, 'meta_box' ),
            'tochatbe_agent',
            'normal',
            'high'
        );
    }

    public function meta_box() {
        global $post;
        require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/meta-boxes/html-agent-add.php';
    }

    public function save_agent_meta_data( $post_id ) {
        global $post, $wpdb;

        if ( $post_id == null || empty( $_POST ) ) {
            return;
        }
        if ( ! isset( $_POST['post_type'] ) || 'tochatbe_agent' !== $_POST['post_type'] ) {
            return;
        }
        if ( wp_is_post_revision( $post_id ) ) {
            $post_id = wp_is_post_revision( $post_id );
        }

        if ( isset( $_POST['agent_name'] ) ) {
            update_post_meta( $post->ID, 'agent_name', sanitize_text_field( $_POST['agent_name'] ) );
        }
        if ( isset( $_POST['agent_title'] ) ) {
            update_post_meta( $post->ID, 'agent_title', sanitize_text_field( $_POST['agent_title'] ) );
        }
        if ( isset( $_POST['agent_number'] ) ) {
            update_post_meta( $post->ID, 'agent_number', sanitize_text_field( $_POST['agent_number'] ) );
        }
        if ( isset( $_POST['agent_group_id'] ) ) {
            update_post_meta( $post->ID, 'agent_group_id', sanitize_text_field( $_POST['agent_group_id'] ) );
        }
        if ( isset( $_POST['agent_type'] ) ) {
            update_post_meta( $post->ID, 'agent_type', sanitize_text_field( $_POST['agent_type'] ) );
        }
        if ( isset( $_POST['pre_defined_message'] ) ) {
            update_post_meta( $post->ID, 'pre_defined_message', sanitize_textarea_field( $_POST['pre_defined_message'] ) );
        }

        $wpdb->update(
            $wpdb->posts,
            array( 'post_title' => sanitize_text_field( $_POST['agent_name'] ) ),
            array( 'ID' => $post_id )
        );
    }

    public function edit_table_columns( $columns ) {
        $columns = array(
            'cb'           => '<input type="checkbox" />',
            'title'        => 'Agent Name',
            'agent_title'  => 'Agent Title',
            'agent_number' => 'Agent Number',
            'agent_type'   => 'Agent Type',
            'date'         => 'Date',
        );

        return $columns;
    }

    public function manage_table_columns( $column, $post_id ) {
        $agent_type     = get_post_meta( $post_id, 'agent_type', true );
        $agent_number   = get_post_meta( $post_id, 'agent_number', true );
        $agent_group_id = get_post_meta( $post_id, 'agent_group_id', true );

        switch ( $column ) {
        case 'agent_title':
            echo get_post_meta( $post_id, 'agent_title', true );
            break;

        case 'agent_number':
            echo 'number' === $agent_type ? $agent_number : $agent_group_id;
            break;

        case 'agent_type':
            echo get_post_meta( $post_id, 'agent_type', true );
            break;

        default:
            # code...
            break;
        }
    }
}

new TOCHATBE_Admin_Agent_Post;