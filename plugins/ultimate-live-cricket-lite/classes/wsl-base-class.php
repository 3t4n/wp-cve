<?php
/*
    Exit if accessed directly
*/
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'LCW_Base_Class' ) ) {

    Class LCW_Base_Class {

        const AJAX_PREFIX = 'wp_ajax_';
        const AJAX_NOPRIV_PREFIX = 'wp_ajax_nopriv_';
        const BASE_API_URL = 'https://apinew.cricket.com.au';
        /**
             * Add an action hook
             * @param $hook
             * @param $callback
             * @param $priority
             * @param $accepted_args
             */
            public function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
                add_action($hook, array(
                    $this,
                    $callback
                ) , $priority, $accepted_args);
            }
            /**
             * Add ajax action for short
             * @param $hook
             * @param $callback
             * @param $priv
             * @param $no_priv
             */
            public function add_ajax($hook, $callback, $priv = true, $no_priv = true, $priority = 10, $accepted_args = 1) {
                if ($priv) $this->add_action(self::AJAX_PREFIX . $hook, $callback, $priority, $accepted_args);
                if ($no_priv) $this->add_action(self::AJAX_NOPRIV_PREFIX . $hook, $callback, $priority, $accepted_args);
            }
            /**
             * Add a filter hook
             * @param $hook
             * @param $callback
             * @param $priority
             * @param $accepted_args
             */
            public function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) {
                add_filter($hook, array(
                    $this,
                    $callback
                ) , $priority, $accepted_args);
            }
            /**
             * Register script and add it into queue
             * @param $handle
             * @param $src
             * @param array $deps
             * @param $ver
             * @param $in_footer
             */
            public function enqueue_script($handle, $src, $deps = array() , $ver = false, $in_footer = true) {
                $scripts = array(
                    'handle' => $handle,
                    'src' => $src,
                    'deps' => $deps,
                    'ver' => $ver,
                    'in_footer' => $in_footer
                );
                wp_register_script($scripts['handle'], $scripts['src'], $scripts['deps'], $scripts['ver'], $scripts['in_footer']);
                wp_enqueue_script($scripts['handle']);
            }
            /**
             * enqueue an existed script
             * @param $handle
             * @param $src
             * @param array $deps
             * @param $ver
             * @param $in_footer
             */
            public function add_existed_script($handle, $src = '', $deps = array() , $ver = false, $in_footer = true) {
                wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
            }
            /**
             * Register script
             * @param $handle
             * @param $src
             * @param array $deps
             * @param $ver
             * @param $in_footer
             */
            
            public function register_script($handle, $src, $deps = array() , $ver = false, $in_footer = true) {
                $scripts =  array(
                    'handle' => $handle,
                    'src' => $src,
                    'deps' => $deps,
                    'ver' => $ver,
                    'in_footer' => $in_footer
                );
                wp_register_script($scripts['handle'], $scripts['src'], $scripts['deps'], $scripts['ver'], $scripts['in_footer']);
            }
            /**
             * Register style and add it into queue
             * @param $handle
             * @param $src
             * @param array $deps
             * @param $ver
             * @param $media
             */
            public function enqueue_style($handle, $src = false, $deps = array() , $ver = false, $media = 'all') {
                $style = array(
                    'handle' => $handle,
                    'src' => $src,
                    'deps' => $deps,
                    'ver' => $ver,
                    'media' => $media
                );
                wp_register_style($style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media']);
                wp_enqueue_style($style['handle']);
            }
            
            /**
             * Register script
             * @param $handle
             * @param $src
             * @param array $deps
             * @param $ver
             * @param $media
             */
            public function register_style($handle, $src = false, $deps = array() , $ver = false, $media = 'all') {
                $style = array(
                    'handle' => $handle,
                    'src' => $src,
                    'deps' => $deps,
                    'ver' => $ver,
                    'media' => $media
                );
                wp_register_style($style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media']);
                wp_enqueue_style($style['handle']);
            }
            /**
             * enqueue existed style
             * @param $handle
             */
            public function add_existed_style($handle) {
                wp_enqueue_style($handle);
            }
            /**
             * enqueue existed style
             * @param $handle
            */
            public function register_shortcode($shortcode_name,$callback_function) {
                add_shortcode($shortcode_name,array( 
                          $this,
                          $callback_function
                        ));
            }
    }
}