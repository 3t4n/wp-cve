<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('RTCORE_Plugin_Hooks')):

/**
 * RTCORE Plugin Hooks Class.
 *
 * @class RTCORE_Plugin_Hooks
 * @version	1.0.0
 */
class RTCORE_Plugin_Hooks
{
    /**
	 * The single instance of the class.
	 *
	 * @var RTCORE_Plugin_Hooks
	 * @since 1.0.0
	 */
	protected static $instance = null;

    /**
	 * RTCORE Plugin Hooks Instance.
	 *
	 * @since 1.0.0
	 * @static
	 * @return RTCORE_Plugin_Hooks
	 */
	public static function instance()
    {
        // Get an instance of Class
		if(is_null(self::$instance)) self::$instance = new self();
        
        // Return the instance
		return self::$instance;
	}

	/**
	 * Cloning is forbidden.
	 * @since 1.0.0
	 */
	public function __clone()
    {
		_doing_it_wrong(__FUNCTION__, __('Cheating huh?', 'realtyna-core'), '1.0.0');
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 * @since 1.0.0
	 */
	public function __wakeup()
    {
		_doing_it_wrong(__FUNCTION__, __('Cheating huh?', 'realtyna-core'), '1.0.0');
	}
    
    /**
	 * Constructor method
	 */
	protected function __construct()
    {
        register_activation_hook(RTCORE_BASENAME, array($this, 'activate'));
		register_deactivation_hook(RTCORE_BASENAME, array($this, 'deactivate'));
		register_uninstall_hook(RTCORE_BASENAME, array('RTCORE_Plugin_Hooks', 'uninstall'));
	}
    
    /**
     * Runs on plugin activation
     * @param boolean $network
     */
    public function activate($network = false)
	{
	    // Reset Last Copy Time
	    update_option('sesame_copytime', time());
	}
    
    /**
     * Runs on plugin deactivation
     * @param boolean $network
     */
    public function deactivate($network = false)
	{
	}
    
    /**
     * Runs on plugin uninstallation
     */
    public static function uninstall()
	{
	}
}

endif;