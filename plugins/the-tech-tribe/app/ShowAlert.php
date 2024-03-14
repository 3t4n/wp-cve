<?php
namespace TheTribalPlugin;

class ShowAlert
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

    public function show($args = [])
    {
        $defaults = [
            'alert' => 'primary',
            'code'  => '',
            'msg'   => '',
			'msg-header' => '',
			'msg-content' => '',
            'close' => false
        ];
         
        $args = wp_parse_args( $args, $defaults );

        $template = tttc_get_plugin_dir() . 'admin/partials/alerts/alert.php';

        if ( is_file( $template ) ) {
            require_once $template;
        }
    }
    
}