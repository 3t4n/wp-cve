<?php
namespace TheTribalPlugin;

use TheTribalPlugin\APIPortal;
use WP_Error;
use WP_REST_Response;
use User;

/**
 * Statistics 
 */
class HealthStatus
{
    /**
	 * instance of this class
	 *
	 * @since 0.0.1
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.0.1
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function __construct(){}

    public function isActive($args = [])
	{
		$prefix = 'ttt_is_active';
		$defaults = array(
            'single' => false,
            'action' => 'r',
            'value' => '',
            'prefix' => $prefix
        );
        $args = wp_parse_args( $args, $defaults );
        switch($args['action']){
            case 'd':
                delete_option($args['prefix']);
            break;
            case 'u':
                update_option($args['prefix'], $args['value']);
            break;
            case 'r':
                return get_option($args['prefix']);
            break;
        }
	}
    
    public function lastDownload($args = [])
	{
		$prefix = 'ttt_client_last_download';
		$defaults = array(
            'single' => false,
            'action' => 'r',
            'value' => '',
            'prefix' => $prefix
        );
        $args = wp_parse_args( $args, $defaults );
        switch($args['action']){
            case 'd':
                delete_option($args['prefix']);
            break;
            case 'u':
                update_option($args['prefix'], $args['value']);
            break;
            case 'r':
                return get_option($args['prefix']);
            break;
        }
	}

    /**
	 * This is the last system checked for new blog posts.
	 */
    public function lastChecked($args = [])
	{
		$prefix = 'ttt_client_last_checked';
		$defaults = array(
            'single' => false,
            'action' => 'r',
            'value' => '',
            'prefix' => $prefix
        );
        $args = wp_parse_args( $args, $defaults );
        switch($args['action']){
            case 'd':
                delete_option($args['prefix']);
            break;
            case 'u':
                update_option($args['prefix'], $args['value']);
            break;
            case 'r':
                return get_option($args['prefix']);
            break;
        }
	}

    /**
	 * This is the last system checked for new blog posts.
	 */
    public function verifyChecked($args = [])
	{
		$prefix = 'ttt_client_verify_status';
		$defaults = array(
            'single' => false,
            'action' => 'r',
            'value' => '',
            'prefix' => $prefix
        );
        $args = wp_parse_args( $args, $defaults );
        switch($args['action']){
            case 'd':
                delete_option($args['prefix']);
            break;
            case 'u':
                update_option($args['prefix'], $args['value']);
            break;
            case 'r':
                return get_option($args['prefix']);
            break;
        }
	}

    /**
	 * This is the last system checked for new blog posts.
	 */
    public function lastCheckedStatus($args = [])
	{
		$prefix = 'ttt_client_last_check_status';
		$defaults = array(
            'single' => false,
            'action' => 'r',
            'value' => '',
            'prefix' => $prefix
        );
        $args = wp_parse_args( $args, $defaults );
        switch($args['action']){
            case 'd':
                delete_option($args['prefix']);
            break;
            case 'u':
                update_option($args['prefix'], $args['value']);
            break;
            case 'r':
                return get_option($args['prefix']);
            break;
        }
	}

    /**
	 * This is the last system checked for new blog posts.
	 */
    public function importJobStatus($args = [])
	{
		$prefix = 'ttt_client_import_job_status';
		$defaults = array(
            'single' => false,
            'action' => 'r',
            'value' => '',
            'prefix' => $prefix
        );
        $args = wp_parse_args( $args, $defaults );
        switch($args['action']){
            case 'd':
                delete_option($args['prefix']);
            break;
            case 'u':
                update_option($args['prefix'], $args['value']);
            break;
            case 'r':
                return get_option($args['prefix']);
            break;
        }
	}

    /**
	 * This is the last system checked for new blog posts.
	 */
    public function importJobVia($args = [])
	{
		$prefix = 'ttt_client_import_job_via';
		$defaults = array(
            'single' => false,
            'action' => 'r',
            'value' => '',
            'prefix' => $prefix
        );
        $args = wp_parse_args( $args, $defaults );
        switch($args['action']){
            case 'd':
                delete_option($args['prefix']);
            break;
            case 'u':
                update_option($args['prefix'], $args['value']);
            break;
            case 'r':
                return get_option($args['prefix']);
            break;
        }
	}

    /**
	 * This is the last system checked for new blog posts.
	 */
    public function importJobStart($args = [])
	{
		$prefix = 'ttt_client_import_job_start';
		$defaults = array(
            'single' => false,
            'action' => 'r',
            'value' => '',
            'prefix' => $prefix
        );
        $args = wp_parse_args( $args, $defaults );
        switch($args['action']){
            case 'd':
                delete_option($args['prefix']);
            break;
            case 'u':
                update_option($args['prefix'], $args['value']);
            break;
            case 'r':
                return get_option($args['prefix']);
            break;
        }
	}
    
    /**
	 * This is the last system checked for new blog posts.
	 */
    public function importJobEnd($args = [])
	{
		$prefix = 'ttt_client_import_job_end';
		$defaults = array(
            'single' => false,
            'action' => 'r',
            'value' => '',
            'prefix' => $prefix
        );
        $args = wp_parse_args( $args, $defaults );
        switch($args['action']){
            case 'd':
                delete_option($args['prefix']);
            break;
            case 'u':
                update_option($args['prefix'], $args['value']);
            break;
            case 'r':
                return get_option($args['prefix']);
            break;
        }
	}

    /**
	 * This is the last system checked for new blog posts.
	 */
    public function importLogReturnPost($args = [])
	{
		$prefix = 'ttt_client_import_return';
		$defaults = array(
            'single' => false,
            'action' => 'r',
            'value' => '',
            'prefix' => $prefix
        );
        $args = wp_parse_args( $args, $defaults );
        switch($args['action']){
            case 'd':
                delete_option($args['prefix']);
            break;
            case 'c':
                add_option($args['prefix'], $args['value']);
            break;
            case 'u':
                update_option($args['prefix'], $args['value']);
            break;
            case 'r':
                return get_option($args['prefix']);
            break;
        }
	}

    /**
	 * This is the last system checked for new blog posts.
	 */
    public function apiIsVerified($args = [])
	{
		$prefix = 'ttt_client_is_api_verified';
		$defaults = array(
            'single' => false,
            'action' => 'r',
            'value' => '',
            'prefix' => $prefix
        );
        $args = wp_parse_args( $args, $defaults );
        switch($args['action']){
            case 'd':
                delete_option($args['prefix']);
            break;
            case 'c':
                add_option($args['prefix'], $args['value']);
            break;
            case 'u':
                update_option($args['prefix'], $args['value']);
            break;
            case 'r':
                return get_option($args['prefix']);
            break;
        }
	}
    
}