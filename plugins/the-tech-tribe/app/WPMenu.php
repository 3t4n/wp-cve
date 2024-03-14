<?php
namespace TheTribalPlugin;

/**
 * Register Menu
 */
class WPMenu
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

    public function init()
    {
        add_action( 'admin_menu', [$this, 'adminMenu'] );
    }

    public function adminMenu()
    {
        add_menu_page(
            'The Tech Tribe',
            'The Tech Tribe',
            'manage_options',
            'the-tribal-plugin',
            [new Dashboard, 'init'],
            tttc_get_plugin_dir_url() . '/assets/images/dashicons/logo-white.png',
            6
        );
    }
    
}