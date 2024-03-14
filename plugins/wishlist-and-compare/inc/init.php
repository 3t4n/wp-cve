<?php
/**
 * The instantiate functionality of the plugin.
 *
 * @package    Wishlist-and-compare
 * @subpackage Wishlist-and-compare/inc
 *
 * @link  https://themehigh.com
 * @since 1.0.0.0
 */

namespace THWWC;

 /**
  * Instantiate the plugin services class
 *
 * @package Wishlist-and-compare
 * @link    https://themehigh.com
 */
final class Init
{
    /**
     * Store all the classes inside an array
     *
     * @return array full list of classes
     */
    public static function get_services()
    {
        return [
            admin\THWWC_Admin_Pages::class,
            admin\THWWC_Admin_Settings::class,
            admin\THWWC_Vue_Api_Wishlist::class,
            admin\THWWC_Vue_Api_Compare::class,
            base\THWWC_Enqueue::class,
            base\THWWC_Settings_Links::class,
            thpublic\THWWC_Public_Wishlist::class,
            thpublic\THWWC_Public_Settings::class,
            thpublic\THWWC_Public_Product_Page::class,
            thpublic\THWWC_Public_Wishlist_Page::class,
            thpublic\THWWC_Public_Wishlist_Counter::class,
            thpublic\THWWC_Public_Compare::class,
        ];
    }

    /**
    * Loop through the classes,initialize them,
    * and call the register() method if it exists
    *
    * @return void
    */
    public static function register_services()
    {
        foreach (self::get_services() as $class) {
            $service = self::_instantiate($class);
            if (method_exists($service, 'register')) {
                $service->register();
            }
        }
    }

    /**
    * Initialize the class
    *
    * @param class $class class from the services array
    *
    * @return class instance new instance of the class
    */
    private static function _instantiate($class)
    {
        // if ($class!='Inc\Actions\CompareWidget') {
            $service = new $class();
            return $service;
        // }
    }
}
