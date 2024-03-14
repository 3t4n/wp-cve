<?php

namespace Mnet\Admin;

use Mnet\MnetDbManager;

class MnetOptions
{
    public static $MNET_OPTION_PREFIX = 'MNET_';

    public static $USER_DETAILS = "USER_DETAILS";
    public static $SITE_DETAILS = "SITE_DETAILS";

    public static function setMnetOptions($email, $response)
    {
        self::setSiteDetailOption($response);
        self::setUserDetailOption($email, $response);
    }

    public static function setUserDetailOption($email, $response)
    {
        $data = [];
        $data['name'] = $response['account_info']['name'];
        if (empty($data['name'])) {
            $data['name'] = 'User';
        }

        $data['crid'] = $response['account_info']['cid'];
        $data['token'] = $response['access_token'];
        $data['email'] = $email;
        $data['isEap'] = intval(\Arr::get($response, 'account_info.isEap', 0));
        $data['inactive'] = intval(\Arr::get($response, 'account_info.inactive', 0));

        self::saveOption(self::$USER_DETAILS, $data);
        \mnet_user()->refresh($data);
    }

    public static function setSiteDetailOption($response)
    {
        $data = [];
        $data['rejected'] = intval(\Arr::get($response, 'account_info.eapRejected', 0));
        $data['mapped'] = intval(\Arr::get($response, 'account_info.siteMapped', 0));
        $data['status'] = \Arr::get($response, 'account_info.siteStatus', '');

        self::saveOption(self::$SITE_DETAILS, $data);
    }

    public static function saveOption($option_name, $data)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }
        $option_name = self::$MNET_OPTION_PREFIX . $option_name;
        if (empty(\get_option($option_name))) {
            \add_option($option_name, $data);
        } else {
            \update_option($option_name, $data);
        }
    }

    public static function getOption($option_name, $default = null)
    {
        $value = \get_option(self::$MNET_OPTION_PREFIX . $option_name, null);
        if (is_null($value)) {
            return $default;
        }
        return $value;
    }

    public static function clearOptions()
    {
        $options = [
            self::$USER_DETAILS,
            self::$SITE_DETAILS,
            MnetDbManager::$DB_VERSION_KEY,
            MnetAdTag::$AD_HEAD_CODE_KEY
        ];
        foreach ($options as $option) {
            self::deleteOption($option);
        }
        \mnet_user()->invalidate();
        \mnet_site()->invalidate();
        MnetAuthManager::removeAuthCookie();
    }

    public static function deleteOption($option_name)
    {
        \delete_option(self::$MNET_OPTION_PREFIX . $option_name);
    }

    public static function clearLoggedInOptions()
    {
        MnetAuthManager::removeAuthCookie();
        $data = ['token' => null, 'isEap' => 0];
        self::updateUserOptions($data);
        \mnet_user()->refresh($data);
    }

    public static function updateUserOptions($data)
    {
        if (is_array($data)) {
            $oldData = \mnet_user()->data();
            $data = array_merge($oldData, $data);
        }
        self::saveOption(self::$USER_DETAILS, $data);
    }

    public static function updateSiteOptions($data)
    {
        if (is_array($data)) {
            $oldData = \mnet_site()->data();
            $data = array_merge($oldData, $data);
        }
        self::saveOption(self::$SITE_DETAILS, $data);
    }

    public static function getUserDetails()
    {
        $userDetails = self::getOption(self::$USER_DETAILS);
        if (!is_null($userDetails)) {
            return json_decode($userDetails, true);
        }
        return self::getUserOldDetails();
    }

    public static function getSiteDetails()
    {
        return json_decode(self::getOption(self::$SITE_DETAILS, "[]"), true);
    }

    public static function getUserOldDetails()
    {
        return array(
            'name' => self::getOption("MNET_CUSTOMER_NAME", "User"),
            'crid' => self::getOption("MNET_CRID"),
            'email' => self::getOption("MNET_CUSTOMER_EMAIL", ''),
            'isEap' => self::getOption("MNET_EAP_CUSTOMER", 0),
            'inactive' => self::getOption("MNET_CUSTOMER_INACTIVE", false)
        );
    }
}
