<?php
/**
 * Short description: Booking calendar created by Innate Images, LLC
 * PHP Version 8.0
 
 * @category  VRCalendarSettings_Class
 * @package   VRCalendarSettings_Class
 * @author    Innate Images, LLC <info@innateimagesllc.com>
 * @copyright 2015 Innate Images, LLC
 * @license   GPL-2.0+ http://www.vrcalendarsync.com
 * @link      http://www.vrcalendarsync.com
 */

 /**
  * Short description: Booking calendar created by Innate Images, LLC
  * VRCalendarSettings Class Doc Comment
  * 
  * VRCalendarSettings Class
  * 
  * @category  VRCalendarSettings_Class
  * @package   VRCalendarSettings_Class
  * @author    Innate Images, LLC <info@innateimagesllc.com>
  * @copyright 2015 Innate Images, LLC
  * @license   GPL-2.0+ http://www.vrcalendarsync.com
  * @link      http://www.vrcalendarsync.com
  */

class VRCalendarSettings extends VRCSingleton
{
    private $_optionKey;

    /**
     * Define template file
     **/
    public function __construct()
    {
        /**
         * Short description: Booking calendar created by Innate Images, LLC
         * PHP Version 8.0
         
        * @category  Booking,ical,ics
        * @package   VR_CalendarSettings
        * @author    Innate Images, LLC <info@innateimagesllc.com>
        * @copyright 2015 Innate Images, LLC
        * @license   GPL-2.0+ http://www.vrcalendarsync.com
        * @link      http://www.vrcalendarsync.com
        */
        $this->_optionKey = VRCALENDAR_PLUGIN_SLUG.'_options';
    }

    /**
     * Get settings based on web instance
     * 
     * @param array  $option  settings option
     * @param string $default default settings
     * 
     * @return String
     */
    public function getSettings($option, $default='')
    {
        $options = get_option($this->_optionKey);

        if (is_array($options)) {
            if (isset($options[$option])) {
                return stripcslashes($options[$option]);
            }
        }

        return $default;
    }

    /**
     * Set settings based on web instance
     * 
     * @param array  $option settings option
     * @param string $value  value
     * 
     * @return String
     */
    public function setSettings($option, $value='')
    {
        $options = get_option($this->_optionKey);

        if (!is_array($options)) {
            $options = array();
        }

        $options[$option] = $value;
        update_option($this->_optionKey, $options);
        return true;
    }
}
