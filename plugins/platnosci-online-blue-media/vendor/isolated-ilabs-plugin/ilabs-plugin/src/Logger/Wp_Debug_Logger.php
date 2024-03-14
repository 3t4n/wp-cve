<?php

namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Logger;

class Wp_Debug_Logger implements Logger_Interface
{
    public function log($log)
    {
        if (\true === WP_DEBUG_LOG || \true === WP_DEBUG) {
            if (\is_array($log) || \is_object($log)) {
                \error_log(\print_r($log, \true));
            } else {
                \error_log($log);
            }
        }
    }
    public function error(string $message, array $args = null, string $context = null)
    {
    }
}
