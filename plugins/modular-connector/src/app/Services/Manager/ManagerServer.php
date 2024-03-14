<?php

namespace Modular\Connector\Services\Manager;

use Modular\Connector\Facades\Core;
use Modular\Connector\Facades\Database;
use Modular\Connector\Jobs\Health\ManagerHealthDataJob;
use Modular\ConnectorDependencies\Illuminate\Support\Str;
use function Modular\ConnectorDependencies\app;
use function Modular\ConnectorDependencies\request;

/**
 * Handles all functionality related to WordPress Core.
 */
class ManagerServer
{
    public function connectorVersion()
    {
        if (!function_exists('get_plugin_data')) {
            require_once ABSPATH . '/wp-admin/includes/plugin.php';
        }

        $connector = get_plugin_data(realpath(app()->basePath('../init.php')));

        return $connector['Version'] ?? null;
    }

    /**
     * @return string
     */
    public function phpVersion()
    {
        return PHP_VERSION;
    }

    /**
     * Detect if php is in safe mode
     *
     * @return bool
     */
    public function isSafeMode()
    {
        $value = ini_get('safe_mode');

        return !($value == 0 || strtolower($value) === 'off');
    }

    /**
     * Detect memory limit
     *
     * @return bool
     */
    public function memoryLimit()
    {
        return ini_get('memory_limit');
    }

    /**
     * Detect memory limit
     *
     * @return bool
     */
    public function disabledFunctions()
    {
        $functions = ini_get('disable_functions');
        $blacklist = ini_get('suhosin.executor.func.blacklist');

        $functions = array_merge(explode(',', $functions), explode(',', $blacklist));
        $functions = array_map(fn($function) => Str::lower(trim($function)), $functions);

        return array_filter($functions);
    }

    /**
     * Detect if shell is available
     *
     * @return bool
     */
    public function shellIsAvailable()
    {
        $requiredFunctions = ['escapeshellarg', 'proc_open', 'proc_get_status', 'proc_terminate', 'proc_close'];
        $disabledFunction = $this->disabledFunctions();

        return !$this->isSafeMode() && count(array_diff($requiredFunctions, $disabledFunction)) === count($requiredFunctions);
    }

    /**
     * @return false|float|null
     */
    public function getDiskSpace()
    {
        $diskFreeSpace = null;

        if (function_exists('disk_free_space')) {
            $diskFreeSpace = @disk_free_space(ABSPATH) ?? null;
        }

        return $diskFreeSpace;
    }

    /**
     * Check if the server is running on Windows or Unix
     *
     * @return bool
     */
    public function isUnix(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN';
    }

    /**
     * @return string
     */
    public function getContentDir()
    {
        return str_ireplace(ABSPATH, '', untrailingslashit(WP_CONTENT_DIR));
    }

    public function isPublic()
    {
        if (!function_exists('get_option')) {
            require_once ABSPATH . '/wp-includes/option.php';
        }

        return get_option('blog_public') == 1;
    }

    /**
     * Get server information
     *
     * @return array
     */
    public function information()
    {
        return [
            'connector_version' => $this->connectorVersion(),
            'php' => [
                'current' => $this->phpVersion(),
                'memory_limit' => $this->memoryLimit(),
                'safe_mode' => $this->isSafeMode(),
                'extensions' => [
                    'zlib' => extension_loaded('zlib'),
                    'mysql' => extension_loaded('mysql'),
                    'mysqli' => extension_loaded('mysqli'),
                    'pdo_mysql' => extension_loaded('pdo_mysql'),
                    'open_ssl' => extension_loaded('openssl'),
                    'curl' => extension_loaded('curl'),
                    'zip' => extension_loaded('zip'),
                ],
                'shell' => $this->shellIsAvailable(),
                'disabled_functions' => $this->disabledFunctions(),
            ],
            'database' => Database::get(),
            'core' => Core::get(),
            'site' => [
                'is_ssl' => is_ssl(),
                'is_multisite' => is_multisite(),
                'base_url' => site_url(),
                'rest_url' => rest_url(),
                'home_url' => home_url(),
                'plugins_url' => plugins_url(),
                'timezone' => wp_timezone_string(),
                'is_public' => $this->isPublic(),
                'abspath' => ABSPATH,
                'debug' => defined('WP_DEBUG') ? WP_DEBUG : false,
                'debug_log' => defined('WP_DEBUG_LOG') ? WP_DEBUG_LOG : false,
                'debug_display' => defined('WP_DEBUG_DISPLAY') ? WP_DEBUG_DISPLAY : false,
            ],
            'mu_plugins' => [
                'exist' => file_exists(WPMU_PLUGIN_DIR),
                'writable' => is_writable(dirname(WPMU_PLUGIN_DIR)),
                'exist_handler' => file_exists(WPMU_PLUGIN_DIR . '/_WPHealthHandlerMU.php')
            ],
            'server' => [
                'uname' => php_uname(), // mode: a
                'hostname' => php_uname('n'),
                'disk_free_space' => $this->getDiskSpace(),
                'is_unix' => $this->isUnix(),
                'pseudo_cron_disable' => defined('DISABLE_WP_CRON') && DISABLE_WP_CRON,
                'content_dir_writable' => is_writable($this->getContentDir())
            ]
        ];
    }

    /**
     * Get server health
     *
     * @return array
     */
    public function healthCheck()
    {
        $mrid = request()->get('mrid');

        $health = \WP_Site_Health::get_tests();

        $syncTests = $health['direct'];
        $syncTests = array_keys($syncTests);

        ManagerHealthDataJob::dispatch($mrid, 'direct', $syncTests);

        $asyncTests = $health['async'];
        $asyncTests = array_keys($asyncTests);
        
        foreach ($asyncTests as $test) {
            $dispatch = $test === 'background_updates' ? 'dispatchSync' : 'dispatch';

            ManagerHealthDataJob::$dispatch($mrid, 'async', [$test]);
        }
    }
}
