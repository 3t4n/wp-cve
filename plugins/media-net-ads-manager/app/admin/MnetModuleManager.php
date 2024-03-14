<?php

namespace Mnet\Admin;

use Mnet\Admin\MnetAuthManager;
use Mnet\Utils\DefaultOptions;

class MnetModuleManager
{
    public static function getModules()
    {
        $pubConsoleUnblockedModules = [];
        if (MnetAuthManager::isLoggedIn()) {
            $pubConsoleUnblockedModules = MnetModuleManager::getPubConsoleUnblockedModules();
        }

        $siteMapped = \mnet_site()->mapped;
        $isAdstxtBlocked = !array_search('adstxt', $pubConsoleUnblockedModules);
        $isListAdUnitsBlocked = !array_search('ads', $pubConsoleUnblockedModules);
        $isCreateAdUnitsBlocked = !array_search('create ad unit', $pubConsoleUnblockedModules);
        // $isReportsBlocked = !array_search('reports', $pubConsoleUnblockedModules);

        $customerInactive = !!\mnet_user()->inactive;

        return ['routes' => array_replace_recursive(
            MnetModuleManager::getBaseConfig(),
            array(
                "basicConfigure" => array("isDisabled" => $customerInactive, "isBlocked" => $customerInactive),
                "dashboard" => array("isDisabled" => $customerInactive, "isBlocked" => $customerInactive),
                "adUnits" => array("isDisabled" => !$siteMapped, "isBlocked" => $isListAdUnitsBlocked || $customerInactive),
                "listAdUnits" => array("isDisabled" => !$siteMapped, "isBlocked" => $isListAdUnitsBlocked || $customerInactive),
                "createAdUnits" => array("isDisabled" => !$siteMapped, "isBlocked" => $isCreateAdUnitsBlocked || $customerInactive),
                "reports" => array("isDisabled" => $customerInactive, "isBlocked" => $customerInactive),
                "adstxt" => array("isDisabled" => !$siteMapped, "isBlocked" => $isAdstxtBlocked || $customerInactive),
                "blocking" => array("isDisabled" => $customerInactive, "isBlocked" => $customerInactive),
                "faq" => array("isDisabled" => $customerInactive, "isBlocked" => $customerInactive),
            )
        ), 'actions' => array(
            'configure-mnet-ad' => array("isBlocked" => $customerInactive),
            'refresh-adtags' => array("isBlocked" => $customerInactive),
            'create-ad-tag' => array("isBlocked" => $customerInactive),
        )];
    }

    public static function createAdUnitsAllowed()
    {
        $siteMapped = \mnet_site()->mapped;
        $pubConsoleUnblockedModules = MnetModuleManager::getPubConsoleUnblockedModules();
        $isCreateAdUnitsBlocked = !array_search('create ad unit', $pubConsoleUnblockedModules);
        $customerInactive = (bool) \mnet_user()->inactive;
        return $siteMapped && !$isCreateAdUnitsBlocked && !$customerInactive;
    }

    private static function getBaseConfig()
    {
        return array(
            "basicConfigure" => array("isDisabled" => true, "isBlocked" => true),
            "dashboard" => array("isDisabled" => true, "isBlocked" => true),
            "adUnits" => array("isDisabled" => true, "isBlocked" => true),
            "listAdUnits" => array("isDisabled" => true, "isBlocked" => true),
            "createAdUnits" => array("isDisabled" => true, "isBlocked" => true),
            "reports" => array("isDisabled" => true, "isBlocked" => true),
            "adstxt" => array("isDisabled" => true, "isBlocked" => true),
            "blocking" => array("isDisabled" => true, "isBlocked" => true),
            "faq" => array("isDisabled" => true, "isBlocked" => true),
        );
    }

    private static function getPubConsoleUnblockedModules()
    {
        $url = MNET_API_ENDPOINT . 'modules?access_token=' . \mnet_user()->token;
        $response = \wp_remote_get($url, DefaultOptions::$MNET_API_DEFAULT_ARGS);
        MnetAuthManager::clearExpiredToken($response);
        if (\is_wp_error($response) || !isset($response['body']) || $response['body'] == "") {
            return [];
        }

        return json_decode(\Arr::get($response, 'body', "{}"), true) ?: array();
    }
}
