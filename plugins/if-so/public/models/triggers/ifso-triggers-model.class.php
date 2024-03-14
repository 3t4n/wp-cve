<?php

namespace IfSo\PublicFace\Models\TriggersModel;

require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/handlers/impl/cookies-handler.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/handlers/impl/empty-data-rules-handler.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/handlers/impl/license-validation-handler.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/handlers/impl/recurrence-handler.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/handlers/impl/skip-handler.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/handlers/impl/testing-mode-handler.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/handlers/impl/triggers-handler.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/ab-testing-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/advertising-platform-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/dynamic-link-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/device-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/geolocation-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/page-url-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/page-visit-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/referrer-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/schedule-date-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/start-end-time-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/user-behavior-browser-language-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/user-behavior-logged-in-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/user-behavior-logged-out-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/user-behavior-logged-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/user-behavior-new-user-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/user-behavior-returning-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/cookie-is-set.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/user-ip-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/utm-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/groups-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/user-roles-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/user-details-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/triggers-visited-trigger.class.php';
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-service/triggers/impl/post-category-trigger.class.php';

use IfSo\PublicFace\Services\TriggersService\Triggers;

class TriggersModel{
    public static function get_triggers(){
        $triggers = [];

        $triggers[] = new Triggers\ReferrerTrigger();
        $triggers[] = new Triggers\PageUrlTrigger();
        $triggers[] = new Triggers\AdvertisingPlatformTrigger();
        $triggers[] = new Triggers\DynamicLinkTrigger();
        $triggers[] = new Triggers\ABTestingTrigger();
        $triggers[] = new Triggers\UserBehaviorNewUserTrigger();
        $triggers[] = new Triggers\UserBehaviorReturningTrigger();
        $triggers[] = new Triggers\UserBehaviorLoggedInTrigger();
        $triggers[] = new Triggers\UserBehaviorLoggedOutTrigger();
        $triggers[] = new Triggers\UserBehaviorLoggedTrigger();
        $triggers[] = new Triggers\UserBehaviorBrowserLanguageTrigger();
        $triggers[] = new Triggers\DeviceTrigger();
        $triggers[] = new Triggers\StartEndTimeTrigger();
        $triggers[] = new Triggers\ScheduleDateTrigger();
        $triggers[] = new Triggers\GeolocationTrigger();
        $triggers[] = new Triggers\PageVisitTrigger();
        $triggers[] = new Triggers\CookieIsSet();
        $triggers[] = new Triggers\UserIpAddress();
        $triggers[] = new Triggers\UtmTrigger();
        $triggers[] = new Triggers\GroupTrigger();
        $triggers[] = new Triggers\UserRolesTrigger();
        $triggers[] = new Triggers\UserDetailsTrigger();
        $triggers[] = new Triggers\TriggersVisitedTrigger();
        $triggers[] = new Triggers\PostCategoryTrigger();

        $triggers = apply_filters('ifso_triggers_list_filter',$triggers);   //For custom triggers extension


        return $triggers;
    }
}