<?php

namespace SCC\Admin\Controllers;

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class InitialSetupWizardController {

    public function hooks() {
        // add_action( 'admin_init', [ $this, 'maybe_load_setup_wizard' ] );
        add_action( 'admin_init', [ $this, 'maybe_redirect_after_activation' ] );
    }

    public function maybe_redirect_after_activation() {
        if ( wp_doing_ajax() || wp_doing_cron() ) {
            return;
        }

        // Decide redirection by transient key
        if ( ! get_transient( 'df_scc_post_activation_setup_redirect' ) ) {
            return;
        }

        delete_transient( 'df_scc_post_activation_setup_redirect' );

        // quit redirecting if the user has aleady used the initial setup wizard
        if ( get_option( 'df_scc_post_activation_no_setup_redirect' ) ) {
            return;
        }

        // redirect to the setup wizard
        update_option( 'df_scc_post_activation_no_setup_redirect', true );
        wp_safe_redirect( add_query_arg(
            'page',
            'scc-tabs&open-wizard=1',
            admin_url( 'admin.php' )
        ) );
        exit;
    }
}
