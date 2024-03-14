<?php
namespace Outfunnel\Forms;

class Logger {
    private static function logMessage($message, $log_level, $userId = '', $payload = []) {
        $outfunnel_settings = get_option('outfunnel_settings');

        if (!$outfunnel_settings) {
            return;
        }

        if(!isset($outfunnel_settings['of_enable_logging'])) {
            return;
        }

        $api_key = $outfunnel_settings['logging_api_key'];

        $url = $outfunnel_settings['logging_url'];

        $current_timestamp = time();

        $authentication_header = "Sentry sentry_version=7,sentry_timestamp={$current_timestamp}sentry_client=outfunnel-wordpress/1.0,sentry_key=$api_key";

        wp_remote_post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Sentry-Auth' => $authentication_header
            ],
            'body' => wp_json_encode([
                'platform' => 'php',
                'level' => $log_level,
                'message' => $message,
                'extra' => $payload,
                'user' => ['id' => $userId]
            ])
        ]);
    }

    public static function info($message, $userId = '', $payload = []) {
        Logger::logMessage($message, "info", $userId, $payload );
    }

    public static function warning($message, $userId = '', $payload = []) {
        Logger::logMessage($message, "error", $userId, $payload);
    }
}

?>
