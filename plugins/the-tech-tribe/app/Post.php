<?php
namespace TheTribalPlugin;

use TheTribalPlugin\APIPortal;
use WP_Error;
use WP_REST_Response;
use User;

/**
 * Get the Post
 */
class Post
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

    public function get( $args = [] )
    {
        //get this in DB
		
		$ret = '';
		$apiKey = WPOptions::get_instance()->apiKey();
		
		if($apiKey != '') {
			tttCustomLogs("api key not empty");

			//move this to function
			$userAccountKeys = \TheTribalPlugin\User::get_instance()->getAccountKeys();
			
			$userAccountKeys['date_import_blog'] = \TheTribalPlugin\WPOptions::get_instance()->dateImportBlog();
			
			$postBodyArgs = [
				'body' => $userAccountKeys
			];
			//move this to function

			$urlAPIPortal = new APIPortal();
			$url = $urlAPIPortal->url() . 'post/get';

			$response = wp_remote_post( $url, [
				'timeout'   => 45,
				'body'		=> $postBodyArgs['body']
			]);
			
			tttCustomLogs("wp_remote_post response");
			tttCustomLogs($response);
			
			$resCode = wp_remote_retrieve_response_code($response);
			tttCustomLogs("resCode");
			tttCustomLogs($resCode);

			$resBody = wp_remote_retrieve_body($response);
			tttCustomLogs("resBody");
			tttCustomLogs($resBody);

			$toArrayBody = json_decode($resBody, 1);
			
			if ( is_wp_error( $response ) ) {
				tttCustomLogs("has wp error");
				tttCustomLogs($response);

				$error_message = $response->get_error_message();
				$ret = $error_message;
			} else {
				$ret = $toArrayBody;
			}
		}

		tttCustomLogs("return");
		tttCustomLogs($ret);
		return rest_ensure_response($ret);
    }
    
}