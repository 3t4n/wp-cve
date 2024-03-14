<?php

use WPSocialReviews\App\Services\Platforms\Reviews\Helper;
use WPSocialReviews\App\Models\Review;
use WPSocialReviews\Framework\Support\Arr;

/**
 ***** DO NOT CALL ANY FUNCTIONS DIRECTLY FROM THIS FILE ******
 *
 * This file will be loaded even before the framework is loaded
 * so the $app is not available here, only declare functions here.
 */


is_readable(__DIR__ . '/globals_dev.php') && include 'globals_dev.php';

if (!function_exists('dd')) {
    function dd()
    {
        foreach (func_get_args() as $arg) {
            echo "<pre>";
            print_r($arg);
            echo "</pre>";
        }
        die();
    }
}

/**
 * Get reviews of the specific platforms, $filters expects valid filters to filter reviews
 *
 * @param array $platforms
 * @param array $filters
 *
 * @return array
 */
if(!function_exists('wpsrGetReviews')){
    function wpsrGetReviews($platforms = [], $filters = [])
    {
        $validPlatforms = Helper::validPlatforms($platforms);
        return Review::filteredReviewsQuery($validPlatforms, $filters)->get()->toArray();
    }
}

/**
 * Get business info of the platforms provided, if source ids is provided it will return specific source ids data
 *
 * @param array $platforms
 * @param array $sourceIds
 *
 * @return array
 */
if(!function_exists('wpsrGetReviewsBusinessInfo')){
    function wpsrGetReviewsBusinessInfo($platforms = [], $sourceIds = [])
    {
        $validPlatforms = Helper::validPlatforms($platforms);
        return Helper::getSelectedBusinessInfoByPlatforms($validPlatforms, $sourceIds);
    }
}


/**
 * Get business info, reviews, template meta of a template
 *
 * @param integer $templateId
 *
 * @return array
 */
if(!function_exists('getReviewsDataFromTemplate')){
    function getReviewsDataFromTemplate($templateId = null)
    {
        _deprecated_function( __FUNCTION__, '3.12.1', 'wpsrGetReviewsDataFromTemplate' );
        $data['template_meta'] = [];
        $data['reviews'] = [];
        $data['business_info'] = [];

        $encodedMeta = get_post_meta($templateId, '_wpsr_template_config', true);
        $template_meta = json_decode($encodedMeta, true);

        if(!empty($template_meta)) {
            $data['template_meta'] = Helper::formattedTemplateMeta($template_meta);

            $platforms = Arr::get($template_meta, 'platform', []);
            $selectedBusinesses = Arr::get($template_meta, 'selectedBusinesses', []);

            $data['business_info'] = wpsrGetReviewsBusinessInfo($platforms, $selectedBusinesses);
            $data['reviews'] = wpsrGetReviews($platforms, $template_meta);
        }

        return $data;
    }
}

if(!function_exists('wpsrGetReviewsDataFromTemplate')){
    function wpsrGetReviewsDataFromTemplate($templateId = null)
    {
        $data['template_meta'] = [];
        $data['reviews'] = [];
        $data['business_info'] = [];

        $encodedMeta = get_post_meta($templateId, '_wpsr_template_config', true);
        $template_meta = json_decode($encodedMeta, true);

        if(!empty($template_meta)) {
            $data['template_meta'] = Helper::formattedTemplateMeta($template_meta);

            $platforms = Arr::get($template_meta, 'platform', []);
            $selectedBusinesses = Arr::get($template_meta, 'selectedBusinesses', []);

            $data['business_info'] = wpsrGetReviewsBusinessInfo($platforms, $selectedBusinesses);
            $data['reviews'] = wpsrGetReviews($platforms, $template_meta);
        }

        return $data;
    }
}

/**
 * Get wpsocialninja instance or other core modules
 *
 * @param string $key
 *
 * @return mixed
 */
if(!function_exists('wpsrSocialReviews')) {
    function wpsrSocialReviews($key = null)
    {
        return \WPSocialReviews\App\App::make($key);
    }
}