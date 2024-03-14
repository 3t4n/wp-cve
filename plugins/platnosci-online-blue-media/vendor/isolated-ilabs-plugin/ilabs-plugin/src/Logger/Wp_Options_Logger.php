<?php

namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Logger;

class Wp_Options_Logger implements Logger_Interface
{
    /**
     * @var string
     */
    private $wp_options_debug_key;
    public function log($log)
    {
        if (\true === WP_DEBUG_LOG || \true === WP_DEBUG) {
            if (\is_array($log) || \is_object($log)) {
                update_option($this->wp_options_debug_key, \print_r($log, \true));
            } else {
                update_option($this->wp_options_debug_key, $log);
            }
        }
    }
    public function error(string $message, array $args = null, string $context = null)
    {
    }
    /**
     * @param string $wp_options_debug_key
     */
    public function set_wp_options_debug_key(string $wp_options_debug_key) : void
    {
        $this->wp_options_debug_key = $wp_options_debug_key;
    }
}
