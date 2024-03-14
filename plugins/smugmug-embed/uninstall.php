<?php
    /**
     * User: twicklund
     * Date: 09/25/2020
     */

	if (!defined('WP_UNINSTALL_PLUGIN')) {
		die;
	}

    else {
        delete_option( 'SME_api_progress' );
        delete_option( 'SME_api_token' );
        delete_option( 'SME_License' );
        delete_option( 'SME_SelectedAlbums' );
        delete_option( 'SME_Settings' );

    }