<?php
/**
 * Short description: Booking calendar created by Innate Images, LLC
 * PHP Version 8.0
 
 * @category  VRCShortcode_Class
 * @package   VRCShortcode_Class
 * @author    Innate Images, LLC <info@innateimagesllc.com>
 * @copyright 2015 Innate Images, LLC
 * @license   GPL-2.0+ http://www.vrcalendarsync.com
 * @link      http://www.vrcalendarsync.com
 */

 /**
  * Short description: Booking calendar created by Innate Images, LLC
  * VRCShortcode Class Doc Comment
  * 
  * VRCShortcode Class
  * 
  * @category  VRCShortcode_Class
  * @package   VRCShortcode_Class
  * @author    Innate Images, LLC <info@innateimagesllc.com>
  * @copyright 2015 Innate Images, LLC
  * @license   GPL-2.0+ http://www.vrcalendarsync.com
  * @link      http://www.vrcalendarsync.com
  */
abstract class VRCShortcode extends VRCSingleton
{

    protected $slug;
    protected $atts;

    // Call function to enqueue scripts and styles in the frontend?
    protected $enqueue_scripts_frontend = true;
    // Call function to enqueue scripts and styles in the admin pages?
    protected $enqueue_scripts_admin = false;

    /**
     * Define template file
     **/
    protected function __construct()
    {
        add_shortcode($this->slug, array($this,'shortcodeHandler'));

        if ($this->enqueue_scripts_frontend) {
            add_action('wp_enqueue_scripts', array($this,'enqueueScripts'));
        }

        if ($this->enqueue_scripts_admin) {
            add_action('admin_enqueue_scripts', array($this,'adminEnqueueScripts'));
        }
    }

    /**
     * Enqueue scripts for the frontend.
     * 
     * @return String
     */
    function enqueueScripts()
    {
        /* this method will be overridden in child classes */
    }

    /**
     * Enqueue scripts in admin pages.
     * By default, runs the same code as is run in the frontend (by calling enqueue_scripts).
     * You can override it to load specific scripts.
     * 
     * @return String
     */
    function adminEnqueueScripts()
    {
        $this->enqueueScripts();
    }

    /**
     * Render view based on web instance
     * 
     * @param string $view view
     * @param array  $data data
     * 
     * @return String
     */
    protected function renderView($view, $data)
    {
        ob_start();
        include VRCALENDAR_PLUGIN_DIR . "/Public/Views/Shortcodes/{$view}.view.php";
        return ob_get_clean();
    }

    /**
     * Render view based on web instance
     * 
     * @param array  $atts    attribute
     * @param string $content content data
     * 
     * @return String
     */
    abstract function shortcodeHandler( $atts , $content="" );
}
