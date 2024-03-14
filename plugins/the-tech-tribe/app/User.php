<?php
namespace TheTribalPlugin;

use WP_User_Query;

class User
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

    public function isValid($args = [])
    {
        $ret = \TheTribalPlugin\Authenticate::get_instance()->auth($args);

		return $ret;
    }

	public function verify()
	{
		$verifyArgs = $this->getAccountKeys();

		return \TheTribalPlugin\Authenticate::get_instance()->auth($verifyArgs);
	}
	
	public function getAccountKeys()
	{
		$userId = $this->getUserId();
				
		//get api key
		$apiKey = WPOptions::get_instance()->apiKey();
		
		$userDomain = site_url();

		return [
			'user_domain' 	=> $userDomain,
			'user_api_key' 	=> $apiKey,
		];
	}

	public function getUserId()
	{
		return WPOptions::get_instance()->defaultAuthor();;
	}
	
	public function getUserIdByApiKey()
	{
		$args = [
			'meta_key' 	=> 'ttt_api_key_blog_post', 
		];
		
		$userRes = false;

		$user_query = new WP_User_Query( $args );

		if ( ! empty( $user_query->get_results() ) ) {
			$userResQuery = $user_query->get_results();
			$userRes =  $userResQuery[0]->data;
		}

		return $userRes;
	}
    
}