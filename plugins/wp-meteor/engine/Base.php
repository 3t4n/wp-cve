<?php

/**
 * Plugin_name
 *
 * @package   Plugin_name
 * @author    Aleksandr Guidrevitch <alex@excitingstartup.com>
 * @copyright 2020 wp-meteor.com
 * @license   GPL 2.0+
 * @link      https://wp-meteor.com
 */

namespace WP_Meteor\Engine;

/**
 * Base skeleton of the plugin
 */
class Base
{

    /**
     * @var array The settings of the plugin.
     */
    public $settings = array();

    /** Initialize the class and get the plugin settings */
    public function initialize()
    {
        /*
        if (
            isset($_SERVER['HTTP_VIA'])
            || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
            || isset($_SERVER['HTTP_FORWARDED_FOR'])
            || isset($_SERVER['HTTP_X_FORWARDED'])
            || isset($_SERVER['HTTP_FORWARDED'])
            || isset($_SERVER['HTTP_CLIENT_IP'])
            || isset($_SERVER['HTTP_FORWARDED_FOR_IP'])
            || isset($_SERVER['VIA'])
            || isset($_SERVER['X_FORWARDED_FOR'])
            || isset($_SERVER['FORWARDED_FOR'])
            || isset($_SERVER['X_FORWARDED'])
            || isset($_SERVER['FORWARDED'])
            || isset($_SERVER['CLIENT_IP'])
            || isset($_SERVER['FORWARDED_FOR_IP'])
            || isset($_SERVER['HTTP_PROXY_CONNECTION'])
        ) {
            // running behind a proxy
            // always pre-process scripts and gracefully fallback on the client side to document.write
            return true;
        }

        $badBrowser =
            preg_match('/msie [1-9]\./i', $_SERVER['HTTP_USER_AGENT']) ||
            preg_match('/msie 10\./i', $_SERVER['HTTP_USER_AGENT']);

        return !$badBrowser;
        */
        return true;
    }
}
