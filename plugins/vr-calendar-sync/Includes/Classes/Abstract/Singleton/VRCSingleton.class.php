<?php
/**
 * Short description: Booking calendar created by Innate Images, LLC
 * PHP Version 8.0
 
 * @category  VRCSingleton_Class
 * @package   VRCSingleton_Class
 * @author    Innate Images, LLC <info@innateimagesllc.com>
 * @copyright 2015 Innate Images, LLC
 * @license   GPL-2.0+ http://www.vrcalendarsync.com
 * @link      http://www.vrcalendarsync.com
 */

 /**
  * Short description: Booking calendar created by Innate Images, LLC
  * VRCSingleton Class Doc Comment
  * 
  * VRCSingleton Class
  * 
  * @category  VRCSingleton_Class
  * @package   VRCSingleton_Class
  * @author    Innate Images, LLC <info@innateimagesllc.com>
  * @copyright 2015 Innate Images, LLC
  * @license   GPL-2.0+ http://www.vrcalendarsync.com
  * @link      http://www.vrcalendarsync.com
  */
class VRCSingleton
{
    /**
     * Instance of all class.
     *
     * @since 1.0.0
     *
     * @var object
     */
    protected static $instances;

    /**
     * Define template file
     **/
    protected function __construct()
    {

    }

    /**
     * Return an instance of 'called' class.
     *
     * @since 1.0.0
     *
     * @return object    A single instance of 'called' class.
     */
    public static function getInstance()
    {
        $class = get_called_class();
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new $class;
        }
        return self::$instances[$class];
    }
}
