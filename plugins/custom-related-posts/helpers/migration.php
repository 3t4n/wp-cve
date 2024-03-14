<?php

class CRP_Migration {

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'migrate_if_needed' ) );
    }

    public function migrate_if_needed()
    {
        // Get current migrated to version
        $migrated_to_version = get_option( 'crp_migrated_to_version', false );

        if ( ! $migrated_to_version ) {
            $notices = false;
            $migrated_to_version = '0.0.1';
        } else {
            $notices = true;
        }

        $migrate_special = '';
        if( isset( $_GET['crp_migrate'] ) ) {
            $migrate_special = $_GET['crp_migrate'];
        }

        if ( version_compare( $migrated_to_version, '1.6.0' ) < 0 ) {
            require_once( CustomRelatedPosts::get()->coreDir . '/helpers/migration/1_6_settings.php');
        }

        if ( CRP_VERSION !== $migrated_to_version ) {
            update_option( 'crp_migrated_to_version', CRP_VERSION );
        }
    }
}