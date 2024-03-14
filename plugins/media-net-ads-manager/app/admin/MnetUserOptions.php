<?php

namespace Mnet\Admin;

use Mnet\MnetDbManager;

class MnetUserOptions
{
    public static $ACCESS_TOKEN_KEY = 'MNET_ACCESS_TOKEN';
    public static $CRID_KEY = 'MNET_CRID';
    public static $WP_OPTION_CUSTOMER_NAME = 'MNET_CUSTOMER_NAME';
    public static $WP_OPTION_CUSTOMER_EMAIL = 'MNET_CUSTOMER_EMAIL';
    public static $WP_OPTION_CUSTOMER_INACTIVE = 'MNET_CUSTOMER_INACTIVE';
    public static $WP_OPTION_EAP_CUSTOMER = 'MNET_EAP_CUSTOMER';
    public static $WP_OPTION_MNET_SITE_REJECT = 'MNET_SITE_REJECT';
    public static $WP_OPTION_MNET_SITE_MAPPED = 'MNET_SITE_MAPPED';
    public static $WP_OPTION_MNET_SITE_STATUS = 'MNET_SITE_STATUS';

    public static function setUserWpOptions($email, $response)
    {
        // set access token
        MnetPluginUtils::setWpOption(self::$ACCESS_TOKEN_KEY, $response['access_token']);

        $name = $response['account_info']['name'];
        $name = empty($name) ? 'User' : $name;

        $newId = $response['account_info']['cid'];
        $oldId = MnetUser::id();
        $refreshAdtags = $oldId !== $newId;

        MnetPluginUtils::setWpOption(self::$CRID_KEY, $newId);
        MnetPluginUtils::setWpOption(self::$WP_OPTION_CUSTOMER_EMAIL, $email);
        MnetPluginUtils::setWpOption(self::$WP_OPTION_CUSTOMER_NAME, $name);
        MnetPluginUtils::setWpOption(self::$WP_OPTION_EAP_CUSTOMER, intval(\Arr::get($response, 'account_info.isEap', 0)));
        MnetPluginUtils::setWpOption(self::$WP_OPTION_MNET_SITE_REJECT, intval(\Arr::get($response, 'account_info.eapRejected', 0)));
        MnetPluginUtils::setWpOption(self::$WP_OPTION_MNET_SITE_MAPPED, intval(\Arr::get($response, 'account_info.siteMapped', 0)));
        MnetPluginUtils::setWpOption(self::$WP_OPTION_CUSTOMER_INACTIVE, intval(\Arr::get($response, 'account_info.inactive', 0)));
        MnetPluginUtils::setWpOption(self::$WP_OPTION_MNET_SITE_STATUS, \Arr::get($response, 'account_info.siteStatus', ''));

        return $refreshAdtags;
    }

    public static function clearUserOptions()
    {
        \delete_option(MnetUserOptions::$WP_OPTION_CUSTOMER_EMAIL);
        \delete_option(MnetDbManager::$WP_OPTION_MNET_DB_VERSION);
        \delete_option(MnetUserOptions::$CRID_KEY);
        \delete_option(MnetAdTag::$AD_HEAD_CODE_KEY);
        self::clearLoggedInUserOptions();
    }

    public static function clearLoggedInUserOptions()
    {
        static::removeAuthCookie();
        \delete_option(MnetUserOptions::$ACCESS_TOKEN_KEY);
        \delete_option(MnetUserOptions::$WP_OPTION_CUSTOMER_NAME);
        \delete_option(MnetUserOptions::$WP_OPTION_EAP_CUSTOMER);
        \delete_option(MnetUserOptions::$WP_OPTION_MNET_SITE_REJECT);
    }
}
