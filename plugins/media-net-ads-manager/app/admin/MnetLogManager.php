<?php

namespace Mnet\Admin;

use Mnet\Admin\MnetAuthManager;
use DateTime;
use Mnet\Admin\MnetPluginUtils;
use Mnet\MnetDbManager;
use Mnet\Utils\DefaultOptions;

class MnetLogManager
{
    public static function logSetting($adtag_id, $page, $position, $custom_css, $basic = 1, $debug = 0)
    {
        $log = self::prepareLogData($adtag_id, $page, $position, $custom_css, $basic, $debug);
        self::sendOrEnqueueLog($log);
    }

    public static function prepareLogData($adtag_id, $page, $position, $custom_css, $basic, $debug)
    {
        return array_merge(self::prepareBasicSlotData($adtag_id, $page, $position, $custom_css, $basic, $debug), self::prepareWpSiteData());
    }

    public static function prepareBasicSlotData($adtag_id, $page, $position, $custom_css, $basic, $debug)
    {
        $time = new DateTime();
        return array(
            'adtag_id' => $adtag_id,
            'customer_id' => \mnet_user()->crid,
            'page' => $page,
            'position' => $position,
            'custom_css' => $custom_css,
            'timestamp' => $time->format(DateTime::ATOM),
            'debug' => $debug,
            'is_basic' => $basic,
            'domain' => MnetPluginUtils::getDomain()
        );
    }

    public static function prepareWpSiteData()
    {
        return array(
            'theme' => MnetPluginUtils::getCurrentThemeName(),
            'plugins' => self::getPluginDetails(),
            'active_plugins' => self::getActivePlugins(),
            'server_info' => MnetPluginUtils::getServerInfo(),
        );
    }

    public static function getPluginDetails()
    {
        $plugins = \get_plugins();
        $list = array();
        foreach ($plugins as $name => $plugin) {
            $pluginName = self::getFormattedPluginName($name);
            $list[$pluginName] = isset($plugin['Version']) ? $plugin['Version'] : (isset($plugin['Description']) ? $plugin['Description'] : $pluginName);
        }
        return $list;
    }

    public static function getActivePlugins()
    {
        $activePlugins = \get_option('active_plugins');
        $list = array();
        foreach ($activePlugins as $plugin) {
            $name = self::getFormattedPluginName($plugin);
            $list[] = $name;
        }
        return $list;
    }

    public static function getFormattedPluginName($name)
    {
        return explode('/', $name)[0];
    }

    public static function sendOrEnqueueLog($log)
    {
        $failedLogs = MnetDbManager::getLogs();
        try {
            if (!empty($failedLogs)) {
                self::sendBulkLogs($failedLogs);
            }
            self::sendLog($log);
        } catch (\Exception $e) {
            self::enqueueLog($log);
        }
    }

    public static function logBulkSettings($logs)
    {
        $failedLogs = MnetDbManager::getLogs();
        try {
            if (!empty($failedLogs)) {
                self::sendBulkLogs($failedLogs);
            }
            self::sendBulkLogs($logs);
        } catch (\Exception $e) {
            foreach ($logs as $log) {
                self::enqueueLog($log);
            }
        }
    }

    protected static function sendBulkLogs($logs)
    {
        $payload['logs'] = $logs;
        $wpSiteData = self::prepareWpSiteData();
        self::sendLog(array_merge($payload, $wpSiteData), '/bulk');
    }

    protected static function sendLog($payload, $api = '')
    {
        $response = \wp_remote_post(
            MNET_API_ENDPOINT . 'log' . $api,
            array_merge(
                DefaultOptions::$MNET_API_DEFAULT_ARGS,
                array(
                    'method' => 'POST',
                    'headers' => array('content-type' => 'application/json'),
                    'body' => json_encode($payload)
                )
            )
        );

        if (!$response || \is_wp_error($response)) {
            throw new \Exception('Failed sending log');
        }

        return $response;
    }

    protected static function retryLogs($logs)
    {
        foreach ($logs as $log) {
            try {
                $response = self::sendLog($log);
                self::dequeueLog($log);
            } catch (\Exception $e) { }
        }
    }

    protected static function enqueueLog($log)
    {
        MnetDbManager::insertLog($log);
    }

    protected static function dequeueLog($log)
    {
        MnetDbManager::deleteLog($log);
    }

    public static function logWPDetails()
    {
        $details = self::prepareWpSiteData();
        $details['domain'] = MnetPluginUtils::getDomain();
        $customerId = \mnet_user()->crid;
        if (empty($customerId)) {
            $userEmail = MnetPluginUtils::getUserEmailId();
            $customerId = !empty($userEmail) ? $userEmail : 'Unknown';
        }
        $details['customer_id'] = $customerId;
        try {
            self::sendLog($details, '/wp-details');
        } catch (\Exception $e) { }
    }

    public static function logEvent($event, $message = null)
    {
        if (is_null($message)) {
            $userDetails = array_merge(
                \mnet_user()->info(),
                array(
                    'reject' => \mnet_site()->rejected,
                    'domain' => MnetPluginUtils::getDomain(),
                ),
                MnetPluginUtils::getServerInfo(),
                MnetPluginUtils::getClientInfo()
            );
            $message = json_encode(array_filter($userDetails));
        }
        $log = compact('event', 'message');
        try {
            self::sendLog($log, '/event');
        } catch (\Exception $e) { }
    }
}
