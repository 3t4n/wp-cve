<?php
namespace TheTribalPlugin;

use WP_Error;
use WP_REST_Response;
use WP_User;

/**
 * User Meta Profile
 */
class WPOptions
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

	public function __construct()
	{
		
	}

	/**
	* registred domain.
	* @param array $args {
	*		Array of arguments.
	*		@type int $user_id the user id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_user_meta(), u = update_user_meta(), d = delete_user_meta
	**/
	public function domain($args = [])
	{
		$prefix = 'ttt_domain_registered_blog_post';
		
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
	* registred domain.
	* @param array $args {
	*		Array of arguments.
	*		@type int $user_id the user id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_user_meta(), u = update_user_meta(), d = delete_user_meta
	**/
	public function emailRegistered($args = [])
	{
		$prefix = 'ttt_email_registered_blog_post';
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
	* API KEY.
	* @param array $args {
	*		Array of arguments.
	*		@type int $user_id the user id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_user_meta(), u = update_user_meta(), d = delete_user_meta
	**/
	public function apiKey($args = [])
	{
		$prefix = 'ttt_api_key_blog_post';
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
	* Last date download.
	* @param array $args {
	*		Array of arguments.
	*		@type int $user_id the user id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_user_meta(), u = update_user_meta(), d = delete_user_meta
	**/
	public function dateImportBlog($args = [])
	{
		$prefix = 'ttt_date_import_blog';
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
	* Manual or Auto publish posts.
	* @param array $args {
	*		Array of arguments.
	*		@type int $user_id the user id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_user_meta(), u = update_user_meta(), d = delete_user_meta
	**/
	public function publishPosts($args = [])
	{
		$prefix = 'ttt_auto_manual_publish_post';
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
	* Manual or Auto publish posts.
	* @param array $args {
	*		Array of arguments.
	*		@type int $user_id the user id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_user_meta(), u = update_user_meta(), d = delete_user_meta
	**/
	public function defaultAuthor($args = [])
	{
		$prefix = 'ttt_auto_post_author';
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