<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Houzez Property Feed License Functions
 */
class Houzez_Property_Feed_License {

	private $license_status = array();

	public function __construct() {

		add_filter( 'houzez_property_feed_pro_active', array( $this, 'is_pro_active' ), 1 );

		add_filter( 'houzez_property_feed_pro_status', array( $this, 'get_license_key_status' ), 1 );

		add_action( 'admin_init', array( $this, 'save_license_key') );

	}

	public function is_pro_active( $active )
	{
		if ( $this->is_pro_installed() && $this->is_license_active() )
		{
			return true;
		}

		return $active;
	}

	private function is_pro_installed()
	{
		return class_exists('Houzez_Property_Feed_Pro');
	}

	private function is_license_active()
	{
		$license_key_status = $this->get_license_key_status();
		if ( isset($license_key_status['success']) && $license_key_status['success'] === true )
		{
			return true;
		}
		return false;
	}

	public function save_license_key()
    {
        if ( !isset($_POST['save_license_key']) )
        {
            return;
        }

        if ( !isset($_POST['_wpnonce']) || ( isset($_POST['_wpnonce']) && !wp_verify_nonce( $_POST['_wpnonce'], 'save-license-key' ) ) ) 
        {
            die( __( "Failed security check", 'houzezpropertyfeed' ) );
        }

        delete_transient( 'houzez_property_feed_license_status' );

        // ready to save
        $options = get_option( 'houzez_property_feed' , array() );
        
        $options['license_key'] = sanitize_text_field($_POST['license_key']);

        update_option( 'houzez_property_feed', $options );

        if ( isset($_POST['license_key_action']) && sanitize_text_field($_POST['license_key_action']) == 'deactivate' )
        {
        	$this->deactivate_license_key( sanitize_text_field($_POST['current_license_key']) );
        }
        elseif ( !empty($_POST['license_key']) )
        {
        	$this->activate_license_key( sanitize_text_field($_POST['license_key']) );
        }

        wp_redirect( admin_url( 'admin.php?page=' . ( isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'houzez-property-feed-import' ) . '&tab=license&hpfsuccessmessage=' . __( 'License details saved', 'houzezpropertyfeed' ) ) );
        die();
    }

    public function get_license_key_status( $force = false )
    {
    	if ( !empty($this->license_status) )
    	{
    		return $this->license_status;
    	}
    	
    	$options = get_option( 'houzez_property_feed', array() );
        
        $license_key = isset($options['license_key']) ? $options['license_key'] : '';

        if ( empty($license_key) )
        {
        	$return = array(
        		'success' => false,
        		'error' => __( 'No license key entered', 'houzezpropertyfeed' )
        	);
        	$this->license_status = $return;
        	return $return;
        }

        // only do once per day
        /*if ( $force !== true )
        {
	        $last_checked = get_option( 'houzez_property_feed_license_key_last_checked', '' );
	        if ( ( time() - $last_checked) <= 86400 )
	        {
	        	$license_key_status = get_option( 'houzez_property_feed_license_key_status', array() );
	        	if ( is_array($license_key_status) && !empty($license_key_status) )
	        	{
	        		$return = $license_key_status;
	        		$this->license_status = $return;
        			return $return;
	        	}
	        }
	    }*/

	    if ( $force !== true )
    	{
    		// Not forcing. Get from transient if possible
    		$license_status = get_transient( 'houzez_property_feed_license_status' );

    		if ( $license_status !== false ) 
    		{
    			// return transient value
				return $license_status;
			}
    	}

	    // construct list of import and export formats being used
	    $import_formats = array();
	    $export_formats = array();

	    $imports = ( isset($options['imports']) && is_array($options['imports']) && !empty($options['imports']) ) ? $options['imports'] : array();
	    foreach ( $imports as $key => $import )
        {
            if ( isset($import['deleted']) && $import['deleted'] === true )
            {
                unset( $imports[$key] );
            }
            elseif ( !isset($import['running']) || ( isset($import['running']) && $import['running'] !== true ) )
            {
            	unset( $imports[$key] );
            }
        }
        foreach ( $imports as $key => $import )
        {
        	$import_formats[] = $import['format'];
        }

        $exports = ( isset($options['exports']) && is_array($options['exports']) && !empty($options['exports']) ) ? $options['exports'] : array();
	    foreach ( $exports as $key => $export )
        {
            if ( isset($export['deleted']) && $export['deleted'] === true )
            {
                unset( $exports[$key] );
            }
            elseif ( !isset($export['running']) || ( isset($export['running']) && $export['running'] !== true ) )
            {
            	unset( $exports[$key] );
            }
        }
        foreach ( $exports as $key => $export )
        {
        	$export_formats[] = $export['format'];
        }

	    $import_formats = implode(",", $import_formats);
	    $export_formats = implode(",", $export_formats);

        $instance_id = get_option( 'houzez_property_feed_instance_id', '' );

        $url = 'https://houzezpropertyfeed.com/?';
    	$url .= 'wc-api=wc-am-api&';
    	$url .= 'wc_am_action=status&';
    	$url .= 'instance=' . $instance_id . '&';
    	$url .= 'object=' . parse_url( get_site_url(), PHP_URL_HOST ) . '&';
    	$url .= 'product_id=17&';
    	$url .= 'slug=houzez-property-feed-pro&';
    	$url .= 'plugin_name=houzez-property-feed-pro/houzez-property-feed-pro.php&';
    	$url .= 'version=' . HOUZEZ_PROPERTY_FEED_PRO_VERSION . '&';
    	$url .= 'api_key=' . $license_key . '&';
    	$url .= 'imports=' . $import_formats . '&';
    	$url .= 'exports=' . $export_formats;

    	$response = wp_remote_get( $url );
    	
    	if ( is_wp_error($response) )
    	{
        	$return = array(
        		'success' => false,
        		'error' => __( 'Failed to request license status', 'houzezpropertyfeed' ) . ': ' . $response->get_error_message()
        	);
        	$this->license_status = $return;
        	return $return;
		}

		if ( 200 !== wp_remote_retrieve_response_code( $response ) )
		{
        	$return = array(
        		'success' => false,
        		'error' => __( 'Received response code when requesting license key status', 'houzezpropertyfeed' ) . ': ' . wp_remote_retrieve_response_code( $response )
        	);
        	$this->license_status = $return;
        	return $return;
		}

		$result = $response['body'];

		$body = json_decode($result, true);

		if ( json_last_error() !== JSON_ERROR_NONE ) 
		{
        	$return = array(
        		'success' => false,
        		'error' => __( 'Failed to decode response when requesting license key status. Please try again', 'houzezpropertyfeed' ) . ': ' . print_r( $result, true )
        	);
        	$this->license_status = $return;
        	return $return;
		}

		if ( isset($body['success']) )
		{
			if ( $body['success'] === true )
			{
				if ( isset($body['status_check']) && $body['status_check'] === 'active' )
				{
					$return = array(
		        		'success' => true
		        	);
				}
				else
				{
					$return = array(
		        		'success' => false,
		        		'error' => __( 'License key inactive', 'houzezpropertyfeed' )
		        	);
				}
			}
			else
			{
				$return = array(
	        		'success' => false,
	        		'error' => __( 'Error when requesting license key status', 'houzezpropertyfeed' ) . ': ' . $body['error']
	        	);
			}

			update_option( 'houzez_property_feed_license_key_last_checked', time() );
			update_option( 'houzez_property_feed_license_key_status', $return );

			$this->license_status = $return;
			set_transient( 'houzez_property_feed_license_status', $return, HOUR_IN_SECONDS );
			return $return;
		}
		else
		{
			$return = array(
        		'success' => false,
        		'error' => __( 'Something went wrong when requesting license key status', 'houzezpropertyfeed' ) . ': ' . print_r($body, true)
        	);
        	$this->license_status = $return;
			return $return;
		}
    }

    private function activate_license_key( $license_key )
    {
    	update_option( 'houzez_property_feed_license_key_last_checked', '' );
		update_option( 'houzez_property_feed_license_key_status', array() );

    	$instance_id = get_option( 'houzez_property_feed_instance_id', '' );

    	if ( empty($instance_id) )
    	{
    		$instance_id = wp_generate_password( 12, false ); // disable specialchars
    		update_option('houzez_property_feed_instance_id', $instance_id );
    	}

    	$url = 'https://houzezpropertyfeed.com/?';
    	$url .= 'wc-api=wc-am-api&';
    	$url .= 'wc_am_action=activate&';
    	$url .= 'instance=' . $instance_id . '&';
    	$url .= 'object=' . parse_url( get_site_url(), PHP_URL_HOST ) . '&';
    	$url .= 'product_id=17&';
    	$url .= 'slug=houzez-property-feed-pro&';
    	$url .= 'plugin_name=houzez-property-feed-pro/houzez-property-feed-pro.php&';
    	$url .= 'version=' . HOUZEZ_PROPERTY_FEED_PRO_VERSION . '&';
    	$url .= 'api_key=' . $license_key;

    	$response = wp_remote_get( $url );
    	
    	if ( is_wp_error($response) )
    	{
  			wp_redirect( admin_url( 'admin.php?page=' . ( isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'houzez-property-feed-import' ) . '&tab=license&hpferrormessage=' . __( 'Failed to request license activation', 'houzezpropertyfeed' ) . ': ' . $response->get_error_message() ) );
        	die();
		}

		if ( 200 !== wp_remote_retrieve_response_code( $response ) )
		{
			wp_redirect( admin_url( 'admin.php?page=' . ( isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'houzez-property-feed-import' ) . '&tab=license&hpferrormessage=' . __( 'Received response code when trying to activate license key', 'houzezpropertyfeed' ) . ': ' . wp_remote_retrieve_response_code( $response ) ) );
        	die();
		}

		$result = $response['body'];

		$body = json_decode($result, true);

		if ( json_last_error() !== JSON_ERROR_NONE ) 
		{
			wp_redirect( admin_url( 'admin.php?page=' . ( isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'houzez-property-feed-import' ) . '&tab=license&hpferrormessage=' . __( 'Failed to decode response when trying to activate license key. Please try again', 'houzezpropertyfeed' ) . ': ' . print_r( $result, true ) ) );
        	die();
		}

		if ( isset($body['success']) )
		{
			if ( $body['success'] === true )
			{
				wp_redirect( admin_url( 'admin.php?page=' . ( isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'houzez-property-feed-import' ) . '&tab=license&hpfsuccessmessage=' . __( 'License key activated', 'houzezpropertyfeed' ) ) );
	        	die();
			}
			else
			{
				wp_redirect( admin_url( 'admin.php?page=' . ( isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'houzez-property-feed-import' ) . '&tab=license&hpferrormessage=' . __( 'Error when activating license key', 'houzezpropertyfeed' ) . ': ' . $body['error'] ) );
	        	die();
			}
		}
		else
		{
			wp_redirect( admin_url( 'admin.php?page=' . ( isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'houzez-property-feed-import' ) . '&tab=license&hpferrormessage=' . __( 'Something went wrong when trying to activate license key', 'houzezpropertyfeed' ) . ': ' . print_r($body, true) ) );
	        die();
		}
    }

    private function deactivate_license_key( $license_key )
    {
    	update_option( 'houzez_property_feed_license_key_last_checked', '' );
		update_option( 'houzez_property_feed_license_key_status', array() );

    	$instance_id = get_option( 'houzez_property_feed_instance_id', '' );

    	$url = 'https://houzezpropertyfeed.com/?';
    	$url .= 'wc-api=wc-am-api&';
    	$url .= 'wc_am_action=deactivate&';
    	$url .= 'instance=' . $instance_id . '&';
    	$url .= 'object=' . parse_url( get_site_url(), PHP_URL_HOST ) . '&';
    	$url .= 'product_id=17&';
    	$url .= 'slug=houzez-property-feed-pro&';
    	$url .= 'plugin_name=houzez-property-feed-pro/houzez-property-feed-pro.php&';
    	$url .= 'version=' . HOUZEZ_PROPERTY_FEED_PRO_VERSION . '&';
    	$url .= 'api_key=' . $license_key;

    	$response = wp_remote_get( $url );
    	
    	if ( is_wp_error($response) )
    	{
  			wp_redirect( admin_url( 'admin.php?page=' . ( isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'houzez-property-feed-import' ) . '&tab=license&hpferrormessage=' . __( 'Failed to request license deactivation', 'houzezpropertyfeed' ) . ': ' . $response->get_error_message() ) );
        	die();
		}

		if ( 200 !== wp_remote_retrieve_response_code( $response ) )
		{
			wp_redirect( admin_url( 'admin.php?page=' . ( isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'houzez-property-feed-import' ) . '&tab=license&hpferrormessage=' . __( 'Received response code when trying to deactivate license key', 'houzezpropertyfeed' ) . ': ' . wp_remote_retrieve_response_code( $response ) ) );
        	die();
		}

		$result = $response['body'];

		$body = json_decode($result, true);

		if ( json_last_error() !== JSON_ERROR_NONE ) 
		{
			wp_redirect( admin_url( 'admin.php?page=' . ( isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'houzez-property-feed-import' ) . '&tab=license&hpferrormessage=' . __( 'Failed to decode response when trying to deactivate license key. Please try again', 'houzezpropertyfeed' ) . ': ' . print_r( $result, true ) ) );
        	die();
		}

		if ( isset($body['success']) )
		{
			if ( $body['success'] === true )
			{
				wp_redirect( admin_url( 'admin.php?page=' . ( isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'houzez-property-feed-import' ) . '&tab=license&hpfsuccessmessage=' . __( 'License key deactivated', 'houzezpropertyfeed' ) ) );
	        	die();
			}
			else
			{
				wp_redirect( admin_url( 'admin.php?page=' . ( isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'houzez-property-feed-import' ) . '&tab=license&hpferrormessage=' . __( 'Error when deactivating license key', 'houzezpropertyfeed' ) . ': ' . $body['error'] ) );
	        	die();
			}
		}
		else
		{
			wp_redirect( admin_url( 'admin.php?page=' . ( isset($_GET['page']) ? sanitize_text_field($_GET['page']) : 'houzez-property-feed-import' ) . '&tab=license&hpferrormessage=' . __( 'Something went wrong when trying to deactivate license key', 'houzezpropertyfeed' ) . ': ' . print_r($body, true) ) );
	        die();
		}
    }

}

new Houzez_Property_Feed_License();