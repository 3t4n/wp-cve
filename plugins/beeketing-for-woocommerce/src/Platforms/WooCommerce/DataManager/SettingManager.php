<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/12/18
 * Time: 12:49 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\DataManager;

use BeeketingConnect_beeketing_woocommerce\Common\Constants as CommonConstants;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Constants;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Helper;

class SettingManager
{

    const CACHE_KEY = 'beeketing_setting_cache';
    const CACHE_TIMEOUT = 3600;

    /**
     * Get setting value by key
     * @param $key
     * @param null $default
     * @param bool $useCache
     * @return array|mixed|null
     */
    public function get($key, $default = null, $useCache = true)
    {
        $settings = $this->getAll($useCache);

        // Get setting by key
        if (isset($settings[$key])) {
            return $settings[$key];
        }

        return $default;
    }

    /**
     * Get all settings
     * @param bool $useCache
     * @return array|mixed
     */
    public function getAll($useCache = true)
    {
        if ($useCache) {
            $settings = wp_cache_get(self::CACHE_KEY, CommonConstants::CACHE_GROUP);
        } else {
            $settings = false;
        }

        // Cant get from cache
        if (!$settings) {
            $settings = get_option(Constants::APP_SETTING_KEY);
            $settings = is_array($settings) ? $settings : array();

            // Write to cache
            wp_cache_set(self::CACHE_KEY, $settings, CommonConstants::CACHE_GROUP, self::CACHE_TIMEOUT);
        }

        return $settings;
    }

    /**
     * Update setting
     *
     * @param $key
     * @param $value
     */
    public function update($key, $value)
    {
        $settings = $this->getAll();

        $settings[$key] = $value;
        update_option(Constants::APP_SETTING_KEY, $settings);

        // Flush cache
        wp_cache_delete(self::CACHE_KEY, CommonConstants::CACHE_GROUP);
    }

    /**
     * Update many settings
     *
     * @param $key
     * @param $value
     */
    public function updateMany($data)
    {
        $settings = $this->getAll();
        foreach ($data as $key => $value) {
            $settings[$key] = $value;
        }

        update_option(Constants::APP_SETTING_KEY, $settings);

        // Flush cache
        wp_cache_delete(self::CACHE_KEY, CommonConstants::CACHE_GROUP);
    }

    /**
     * Delete setting from db
     */
    public function deleteAll()
    {
        delete_option(Constants::APP_SETTING_KEY);
    }

    /**
     * Get access token that beeketing can use to access info in this site
     * @param bool $autoGenerate
     * @return array|mixed|null|string
     */
    public function getAccessToken($autoGenerate = true)
    {
        // Generate access token
        $token = $this->get(CommonConstants::SETTING_ACCESS_TOKEN);
        if (!$token && $autoGenerate) {
            $token = Helper::generateToken();
            $this->update(CommonConstants::SETTING_ACCESS_TOKEN, $token);
        }

        return $token;
    }

}