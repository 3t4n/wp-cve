<?php

namespace Wpo\Core;

use Wpo\Services\Options_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Core\WordPress_Helpers')) {

    class WordPress_Helpers
    {
        /**
         * PHP 8.1 safe version of PHP's trim.
         * 
         * @param mixed $str 
         * @param string $charlist 
         * @return string 
         */
        public static function trim($str, $charlist = " \n\r\t\v\x00")
        {
            $str = null === $str ? '' : $str;
            return trim($str, $charlist);
        }

        /**
         * PHP 8.1 safe version of PHP's ltrim.
         * 
         * @param mixed $str 
         * @param string $charlist 
         * @return string 
         */
        public static function ltrim($str, $charlist = " \n\r\t\v\x00")
        {
            $str = null === $str ? '' : $str;
            return ltrim($str, $charlist);
        }

        /**
         * PHP 8.1 safe version of PHP's rtrim.
         * 
         * @param mixed $str 
         * @param string $charlist 
         * @return string 
         */
        public static function rtrim($str, $charlist = " \n\r\t\v\x00")
        {
            $str = null === $str ? '' : $str;
            return rtrim($str, $charlist);
        }

        /**
         * PHP 8.1 safe version of PHP's stripos.
         * 
         * @param mixed $haystack 
         * @param mixed $needle 
         * @param mixed $offset 
         * @return int|false 
         */
        public static function stripos($haystack, $needle, $offset = 0)
        {
            $haystack = null === $haystack ? '' : $haystack;
            $needle = null === $needle ? '' : $needle;

            return stripos($haystack, $needle, $offset);
        }

        /**
         * PHP 8.1 safe version of PHP's strpos.
         * 
         * @param mixed $haystack 
         * @param mixed $needle 
         * @param mixed $offset 
         * @return int|false 
         */
        public static function strpos($haystack, $needle, $offset = 0)
        {
            $haystack = null === $haystack ? '' : $haystack;
            $needle = null === $needle ? '' : $needle;

            return strpos($haystack, $needle, $offset);
        }

        /**
         * Helper to base64 URL decode.
         * 
         * @param   string  Input to be decoded.
         * 
         * @return  string  Input decoded
         */
        public static function base64_url_decode($arg)
        {
            $res = $arg;
            $res = str_replace('-', '+', $res);
            $res = str_replace('_', '/', $res);

            switch (strlen($res) % 4) {
                case 0:
                    break;
                case 2:
                    $res .= "==";
                    break;
                case 3:
                    $res .= "=";
                    break;
                default:
                    break;
            }

            $res = base64_decode($res);
            return $res;
        }


        /**
         * Helper to base64 URL encode.
         * 
         * @param mixed $arg 
         * @return string 
         */
        public static function base64_url_encode($arg)
        {
            return strtr(
                base64_encode($arg),
                [
                    '=' => '',
                    '+' => '-',
                    '/' => '_',
                ]
            );
        }

        /**
         * Helper for wp_kses to define the allowed HTML element names, attribute names, attribute 
         * values, and HTML entities
         * 
         * @return mixed 
         */
        public static function get_allowed_html()
        {
            global $allowedposttags;

            $allowed_atts = array(
                'action' => array(),
                'align' => array(),
                'alt' => array(),
                'class' => array(),
                'data-nonce'  => array(),
                'data-props' => array(),
                'data-wpajaxadminurl' => array(),
                'data' => array(),
                'dir' => array(),
                'fill' => array(),
                'for' => array(),
                'height' => array(),
                'href' => array(),
                'html' => array(),
                'id' => array(),
                'lang' => array(),
                'method' => array(),
                'name' => array(),
                'novalidate' => array(),
                'onClick' => array(),
                'onclick' => array(),
                'rel' => array(),
                'rev' => array(),
                'src' => array(),
                'style' => array(),
                'tabindex' => array(),
                'target' => array(),
                'title' => array(),
                'type' => array(),
                'type' => array(),
                'value' => array(),
                'viewBox' => array(),
                'width' => array(),
                'x' => array(),
                'xml:lang' => array(),
                'xmlns' => array(),
                'y' => array(),
            );

            // Add custom tags
            $allowed_tags = array('script' => $allowed_atts);
            $allowed_tags['!DOCTYPE'] = $allowed_atts;
            $allowed_tags['body'] = $allowed_atts;
            $allowed_tags['head'] = $allowed_atts;
            $allowed_tags['html'] = $allowed_atts;
            $allowed_tags['rect'] = $allowed_atts;
            $allowed_tags['style'] = $allowed_atts;
            $allowed_tags['svg'] = $allowed_atts;
            $allowed_tags['title'] = $allowed_atts;
            $allowed_tags['button'] = $allowed_atts;
            $allowed_tags['a'] = $allowed_atts;

            // Merge global and custom tags
            $all_allowed_tags = array_merge($allowedposttags, $allowed_tags);

            // Overwrite global ones with custom atts
            $all_allowed_tags['div'] = array_merge($allowedposttags['div'], $allowed_atts);

            return $all_allowed_tags;
        }

        /**
         * Hides the WordPress Admin Bar for specific roles.
         * 
         * @since   18.0
         * 
         * @return void 
         */
        public static function hide_admin_bar()
        {

            // Don't hide for admin
            if (is_admin()) {
                return;
            }

            $roles = Options_Service::get_global_list_var('hide_admin_bar_roles');

            if (!empty($roles) && get_current_user_id() > 0) {
                $wp_usr = wp_get_current_user();

                foreach ($roles as $role) {

                    if (in_array($role, $wp_usr->roles)) {
                        show_admin_bar(false);
                        break;
                    }
                }
            }
        }

        /**
         * Simple helper to add save style (css) attributes.
         * 
         * @since   18.1
         * 
         * @param   mixed   $styles 
         * @return  string 
         */
        public static function safe_css($styles)
        {
            $styles[] = 'list-style';
            $styles[] = 'min-width';
            $styles[] = 'max-width';
            $styles[] = 'display';
            return $styles;
        }
    }
}
