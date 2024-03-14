<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Houzez Property Feed Settings Functions
 */
class Houzez_Property_Feed_Settings {

	public function __construct() {

        add_action( 'admin_init', array( $this, 'save_settings') );

	}

    public function save_settings()
    {
        if ( !isset($_POST['save_hpf_settings']) )
        {
            return;
        }

        if ( !isset($_POST['_wpnonce']) || ( isset($_POST['_wpnonce']) && !wp_verify_nonce( $_POST['_wpnonce'], 'save-hpf-settings' ) ) ) 
        {
            die( __( "Failed security check", 'houzezpropertyfeed' ) );
        }

        $options = get_option( 'houzez_property_feed' , array() );
        if ( !is_array($options) ) { $options = array(); }

        if ( isset($_GET['page']) && sanitize_text_field($_GET['page']) == 'houzez-property-feed-import' )
        {
            $new_options = array(
                'email_reports' => ( ( isset($_POST['email_reports']) && $_POST['email_reports'] == 'yes' ) ? true : false ),
                'email_reports_to' => ( ( isset($_POST['email_reports_to']) && sanitize_email($_POST['email_reports_to']) ) ? sanitize_email($_POST['email_reports_to']) : '' ),
                'remove_action' => ( ( isset($_POST['remove_action']) && in_array($_POST['remove_action'], array( '', 'remove_all_media', 'delete' )) ) ? sanitize_text_field($_POST['remove_action']) : '' ),
                'media_processing' => ( ( isset($_POST['media_processing']) && in_array($_POST['media_processing'], array( '', 'background' )) ) ? sanitize_text_field($_POST['media_processing']) : '' ),
            );
        }

        if ( isset($_GET['page']) && sanitize_text_field($_GET['page']) == 'houzez-property-feed-export' )
        {
            $new_options = array(
                'sales_statuses' => ( ( isset($_POST['sales_statuses']) && !empty($_POST['sales_statuses']) ) ? hpf_clean( $_POST['sales_statuses'] ) : array() ),
                'lettings_statuses' => ( ( isset($_POST['lettings_statuses']) && !empty($_POST['lettings_statuses']) ) ? hpf_clean( $_POST['lettings_statuses'] ) : array() ),
                'property_selection' => ( ( isset($_POST['property_selection']) && in_array($_POST['property_selection'], array( '', 'individual', 'per_export' )) ) ? sanitize_text_field($_POST['property_selection']) : '' ),
            );
        }

        $options = array_merge( $options, $new_options );

        update_option( 'houzez_property_feed', $options );

        wp_redirect( admin_url( 'admin.php?page=' . ( isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'houzez-property-feed-import' ) . '&tab=settings&hpfsuccessmessage=' . __( 'Settings saved', 'houzezpropertyfeed' ) ) );
        die();
    }
}

new Houzez_Property_Feed_Settings();