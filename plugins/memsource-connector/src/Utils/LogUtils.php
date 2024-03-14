<?php

namespace Memsource\Utils;

use WP_Block_Type_Registry;
use ZipArchive;

class LogUtils
{
    public const LOG_FILE_NAME = 'phrase.log';
    public const ZIP_FILE_NAME = 'phrase.log.zip';
    public const LOG_EMAIL_RECIPIENT = 'integrations@phrase.com';

    private const DEBUG = 'DEBUG';
    private const INFO = 'INFO';
    private const WARN = 'WARN';
    private const ERROR = 'ERROR';

    private const SIZE_KB = 1024;
    private const SIZE_MB = 1048576;
    private const SIZE_GB = 1073741824;

    public static function getLogFilePath(): string
    {
        return MEMSOURCE_PLUGIN_PATH . '/' . self::LOG_FILE_NAME;
    }

    public static function getZipFilePath(): string
    {
        return MEMSOURCE_PLUGIN_PATH . '/' . self::ZIP_FILE_NAME;
    }

    public static function debug($message)
    {
        self::log(self::DEBUG, $message);
    }

    public static function info($message)
    {
        self::log(self::INFO, $message);
    }

    public static function warn($message)
    {
        self::log(self::WARN, $message);
    }

    public static function error($message, $exception = null)
    {
        if ($exception instanceof \Throwable) {
            $detail = [
                'message' => $exception->getMessage(),
                'exceptionClass' => '\\' . get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
            $message .= "\n>>> Exception:\n" . self::toStr($detail);
        }

        self::log(self::ERROR, $message);
    }

    public static function log($level, $message)
    {
        global $appRegistry;
        if ($appRegistry !== null && $appRegistry->getOptionsService()->isDebugMode()) {
            $file = self::getLogFilePath();
            $log = "\n----------------------------------------------\n" .
                   "--- [" . date('r') . "] $level ---\n" .
                   ">>> $message\n";
            file_put_contents($file, $log, FILE_APPEND | LOCK_EX);
        }
    }

    public static function getLogFileSize()
    {
        $logFile = self::getLogFilePath();
        return file_exists($logFile) ? filesize($logFile) : 0;
    }

    public static function getLogFileSizeFormatted()
    {
        $size = self::getLogFileSize();
        if ($size) {
            if ($size > self::SIZE_GB) {
                return number_format($size / self::SIZE_GB, 2) . ' GB';
            } elseif ($size > self::SIZE_MB) {
                return number_format($size / self::SIZE_MB, 2) . ' MB';
            } elseif ($size > self::SIZE_KB) {
                return number_format($size / self::SIZE_KB, 2) . ' kB';
            } else {
                return $size . " bytes";
            }
        }
        return 0;
    }

    public static function zipAndEmailLogFile()
    {
        $logFile = self::getLogFilePath();
        $zipFile = self::getZipFilePath();
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE)) {
            $zip->addFile($logFile, self::LOG_FILE_NAME);
            $zip->close();
            wp_mail(
                self::LOG_EMAIL_RECIPIENT,
                'Memsource plugin log file from ' . get_site_url(),
                'Memsource plugin log file from ' . get_site_url(),
                [],
                [$zipFile]
            );
            return self::ZIP_FILE_NAME;
        }
        return null;
    }

    public static function deleteLogFile()
    {
        $logFile = self::getLogFilePath();
        $zipFile = self::getZipFilePath();
        $logDeleted = file_exists($logFile) ? unlink($logFile) : false;
        $zipDeleted = file_exists($zipFile) ? unlink($zipFile) : false;
        $result = [];
        if ($logDeleted) {
            $result['logDeleted'] = self::LOG_FILE_NAME;
        }
        if ($zipDeleted) {
            $result['zipDeleted'] = self::ZIP_FILE_NAME;
        }
        return $result;
    }

    public static function logSystemInfo()
    {
        $wpmlPluginFile = WP_PLUGIN_DIR . '/sitepress-multilingual-cms/sitepress.php';
        $mlpPluginFile = WP_PLUGIN_DIR . '/multilingualpress/multilingualpress.php';
        global $shortcode_tags;
        global $appRegistry;

        $systemData = [
            'Site URL' => get_site_url(),
            'ABSPATH' => ABSPATH,
            'WP_PLUGIN_DIR' => WP_PLUGIN_DIR,
            'MEMSOURCE_PLUGIN_PATH' => MEMSOURCE_PLUGIN_PATH,
            'PHP Version' => PHP_VERSION,
            'Wordpress Version' => get_bloginfo('version'),
            'Multisite' => is_multisite() ? 'true' : 'false',
            'Active multilingual plugin' => get_option('memsource_multilingual_plugin', 'Unknown'),
            'WPML Version' => file_exists($wpmlPluginFile) ? get_plugin_data($wpmlPluginFile)['Version'] : '-',
            'MLP Version' => file_exists($mlpPluginFile) ? get_plugin_data($mlpPluginFile)['Version'] : '-',
            'Memsource Connector Plugin Version' => MEMSOURCE_PLUGIN_VERSION,
            'Active plugins' => get_option('active_plugins'),
            'MU plugins' => wp_get_mu_plugins(),
            'Configured custom fields' => $appRegistry->getCustomFieldsService()->getCustomFieldsDump(),
            'Installed shortcodes' => array_keys($shortcode_tags),
            'Configured custom shortcodes' => $appRegistry->getShortcodeService()->getCustomShortcodesDump(),
            'Installed Gutenberg blocks' => array_keys(WP_Block_Type_Registry::get_instance()->get_all_registered()),
            'Configured custom Gutenberg blocks' => $appRegistry->getBlockService()->getCustomBlocksDump(),
        ];

        self::info("Installed tools:\n" . self::toStr($systemData));
    }

    public static function toStr($var): string
    {
        if ($var === false) {
            return 'false';
        }

        if ($var === null) {
            return 'null';
        }

        return print_r($var, true);
    }
}
