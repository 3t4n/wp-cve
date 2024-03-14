<?php

namespace Rublon_WordPress\Libs\Classes\Features;

use RublonHelper;

/**
 * Proxy class for caching the available Rublon features.
 *
 * If local cache is unavailable, then requesting the Rublon server
 * to get list of active features for this consumer.
 *
 * This class is used to determine if some Rublon payed features
 * will work for this consumer. If some feature is not enabled
 * on the Rublon server, it won't work even if Wordpress performs
 * an API call. This is used only to avoid displaying disabled GUI elements.
 */
abstract class RublonFeature
{

    /**
     * Feature key name to override in subclass.
     *
     * @var string
     */
    const FEATURE = '';

    /**
     * Number of seconds to keep the cache.
     */
    const CACHE_EXPIRATION_SEC = 86400; // 24h
    const BUFFERED_CONFIRMATION_OPTION_KEY = 'buffered-confirmation-time';
    const FEATURE_BUFFERED_CONFIRMATION = 'bufferedAutoConfirmation';
    const FEATURE_IDENTITY_PROVIDING = 'accessControlManager';
    const FEATURE_FLAG_BUSINESS_EDITION = 'businessEdition';
    const FEATURE_FORCE_MOBILE_APP = 'forceMobileApp';
    const FEATURE_OPERATION_CONFIRMATION = 'operationConfirmation';

    /**
     * Get list of all features with information which one is available for the consumer.
     *
     * @param boolean $cached Use cached features (default: true)
     * @return mixed|Ambigous <NULL, array>
     */
    static function getFeatures($cached = true)
    {
        if (RublonHelper::isNewVersion() || !RublonHelper::isSiteRegistered()) {
            return null;
        } else if ($cached && $features = self::getFeaturesFromCache()) {
            return $features;
        }
    }

    /**
     * Get features list from cache.
     *
     * @return array|NULL
     */
    static function getFeaturesFromCache()
    {
        return get_transient(self::getTransientName());
    }

    /**
     * Save features got from server in local cache.
     *
     * @param array $features
     * @return boolean
     */
    static function saveFeaturesInCache($features)
    {
        return set_transient(self::getTransientName(), $features, self::CACHE_EXPIRATION_SEC);
    }

    /**
     * Get the transient name.
     *
     * @return string
     */
    protected static function getTransientName()
    {
        return 'rublon_features';
    }

    /**
     * Get the buffered confirmation buffer time option value (minutes).
     * No matter if this feature is enabled.
     *
     * @return number
     */
    static function getBufferedConfirmationOptionValue()
    {
        $options = get_option(RublonHelper::RUBLON_ADDITIONAL_SETTINGS_KEY);

        if (!empty($options[self::BUFFERED_CONFIRMATION_OPTION_KEY])) {
            return $options[self::BUFFERED_CONFIRMATION_OPTION_KEY];
        } else {
            return 0;
        }
    }

    /**
     * Removes features list from cache.
     *
     * @return boolean
     */
    static function deleteFeaturesFromCache()
    {
        return delete_transient(self::getTransientName());
    }

}
