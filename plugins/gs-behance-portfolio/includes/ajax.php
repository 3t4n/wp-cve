<?php

namespace GSBEH;

// if direct access than exit the file.
defined('ABSPATH') || exit;

/**
 * Handle plugin ajax endpoints.
 * 
 * @since 2.0.12
 */
class Ajax {

    /**
     * Constructor of the class.
     * 
     * @since 2.0.12
     */
    public function __construct() {
        add_action( 'wp_ajax_gs_resync_behance_data', array( $this, 'resync_data' ) );
    }

    /**
     * Resync data on request.
     * 
     * @since  2.0.12
     * @return void
     */
    public function resync_data() {
        // plugin()->data->delete_saved_user_ids();
        plugin()->resync_data_task();
        wp_send_json_success( __( 'Successfully synced data', 'gs-behance' ) );
    }
}