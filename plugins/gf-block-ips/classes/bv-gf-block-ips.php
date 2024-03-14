<?php

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class BV_GF_Block_Ips {

    private $callback = null;

    public function __construct() {

        new BV_Links();
        $this->callback = new BV_Callback();

        add_action( 'init', array( $this, 'bv_gravity_ip_post_types_init' ) );
        add_action( 'add_meta_boxes_ip', array( $this, 'bv_gravity_ip_add_custom_box' ) );
        add_action( 'save_post', array( $this, 'bv_gravity_ip_save_postdata' ) );
        add_action( 'gform_pre_submission', array( $this, 'bv_gravity_ip_pre_submission_handler' ) );
        add_action( 'gform_entries_first_column_actions', array( $this, 'bv_gravity_ip_first_column_actions' ), 10, 4 );
    }

    public function bv_gravity_ip_post_types_init() {

        $args = array(
            'label'              => 'Blocked IP',
            'has_archive'        => false,
            'supports'           => array( 'title' ),
            'public'             => false,
            'publicly_queryable' => true,
            'show_ui'            => true,
        );
        register_post_type( 'ip', $args );
        add_submenu_page( 'edit.php?post_type=ip', 'Bulk Import', 'Bulk Import', 'manage_options', 'bulk-import', array( $this->callback, 'bv_gravity_ip_bulk_import_menu_callback' ) );
    }

    public function bv_gravity_ip_add_custom_box( $post ) {

        add_meta_box(
            'gravity_ips_custom_box',
            'IP',
            array( $this->callback, 'bv_gravity_ip_custom_box_html' ),
            'ip'
        );
    }

    public function bv_gravity_ip_save_postdata( $post_id ) {

        if ( array_key_exists( 'gravity_ips_ip', $_POST ) && filter_var( $_POST['gravity_ips_ip'], FILTER_VALIDATE_IP ) ) {
            update_post_meta(
                $post_id,
                '_gravity_ips_ip',
                sanitize_text_field( $_POST['gravity_ips_ip'] )
            );
        }
    }

    public function bv_gravity_ip_pre_submission_handler( $form ) {

        $args = array(
            'post_type'  => 'ip',
            'meta_key'   => '_gravity_ips_ip',
            'meta_value' => $_SERVER['REMOTE_ADDR'],
        );
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) {
            die( "ERROR: your ip has been blacklisted to submit this form. Please contact the webmaster" );
        }
    }

    public function bv_gravity_ip_first_column_actions( $form_id, $field_id, $value, $entry ) {

        $lead_id = $entry['id'];
        $form    = $entry['form_id'];
        $ip      = $entry['ip'];
        $args    = array(
            'post_type'  => 'ip',
            'meta_key'   => '_gravity_ips_ip',
            'meta_value' => $ip,
        );
        $query = new WP_Query( $args );

        if ( isset( $_GET['blocked_ip'] ) ) {
            $new_ip = trim( $_GET['blocked_ip'] );
            $args   = array(
                'post_type'  => 'ip',
                'meta_key'   => '_gravity_ips_ip',
                'meta_value' => $new_ip,
            );
            $query = new WP_Query( $args );
            if ( filter_var( $new_ip, FILTER_VALIDATE_IP ) ) {

                if ( !$query->have_posts() ) {
                    // Create post object
                    $my_post = array(
                        'post_title'   => $new_ip,
                        'post_content' => '',
                        'post_status'  => 'publish',
                        'meta_input'   => array(
                            '_gravity_ips_ip' => $new_ip,
                        ),
                        'post_type'    => 'ip',
                    );

                    // Insert the post into the database
                    wp_insert_post( $my_post );
                }
            }
        } elseif ( isset( $_GET['unblocked_ip'] ) ) {
            $new_ip = $_GET['unblocked_ip'];
            $args   = array(
                'post_type'  => 'ip',
                'meta_key'   => '_gravity_ips_ip',
                'meta_value' => $new_ip,
            );
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    wp_delete_post( get_the_ID() );
                }
            }
        }

        $args = array(
            'post_type'  => 'ip',
            'meta_key'   => '_gravity_ips_ip',
            'meta_value' => $ip,
        );
        $query2 = new WP_Query( $args );
        if ( !$query2->have_posts() ) {
            echo "| <a href='admin.php?page=gf_entries&id={$form}&blocked_ip={$ip}'>Block IP</a> ";
        } else {
            echo "| <a href='admin.php?page=gf_entries&id={$form}&unblocked_ip={$ip}'>Unblock IP</a> ";
        }

    }
}