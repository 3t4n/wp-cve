<?php
namespace TheTribalPlugin;

class APIPortal
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

    public function url( $args = [] )
    {
		$apiUrl = 'https://portal.thetechtribe.com';
		if(defined('TTT_DEV_PORTAL_LOCAL')){
			$apiUrl = TTT_DEV_PORTAL_LOCAL;
		}
        $defaults = [
            'version'   => 'v1',
            'url'       => $apiUrl,
            'url_api'   => 'wp-json/ttt-portal'
        ];

        $args = wp_parse_args( $args, $defaults );

        return $args['url'] .'/'. $args['url_api'] .'/'. $args['version'] .'/';
    }
}