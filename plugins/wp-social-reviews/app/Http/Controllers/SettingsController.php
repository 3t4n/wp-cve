<?php

namespace WPSocialReviews\App\Http\Controllers;

use WPSocialReviews\Framework\Request\Request;
use WPSocialReviews\App\Services\GlobalSettings;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $platform = $request->get('platform');

        if(!defined('WC_VERSION') && $platform === 'woocommerce'){
           return false;
        }

        do_action('wpsocialreviews/get_advance_settings_' . $platform);
    }

    public function update(Request $request)
    {
        $platform = $request->get('platform');
        $settingsJSON = $request->get('settings');
        $settings = json_decode($settingsJSON, true);
        $settings = wp_unslash($settings);
        do_action('wpsocialreviews/save_advance_settings_' . $platform, $settings);
    }

    public function delete(Request $request)
    {
        $platform = $request->get('platform');
        $cacheType = $request->get('cacheType');
        do_action('wpsocialreviews/clear_cache_' . $platform, $cacheType);
    }

    public function getFluentFormsSettings(Request $request)
    {
        $platform = 'fluent_forms';
        do_action('wpsocialreviews/get_advance_settings_' . $platform);
    }

    public function saveFluentFormsSettings(Request $request)
    {
        $platform = 'fluent_forms';
        $settingsJSON = $request->get('settings');
        $settings = json_decode($settingsJSON, true);
        $settings = wp_unslash($settings);
        do_action('wpsocialreviews/save_advance_settings_' . $platform, $settings);
    }

    public function deleteTwitterCard()
    {
        delete_option('wpsr_twitter_cards_data');

        return [
            'success' => 'success',
            'message' => __('Card Data Deleted Successfully!', 'wp-social-reviews')
        ];
    }

    public function getLicense(Request $request)
    {
        $response = apply_filters('wpsr_get_license', false, $request);
        if(!$response) {
            return $this->sendError([
                'message' => __('Sorry! License could not be retrieved. Please try again', 'wp-social-reviews')
            ]);
        }

        return $response;
    }

    public function removeLicense(Request $request)
    {
        $response = apply_filters('wpsr_deactivate_license', false, $request);
        if(!$response) {
            return $this->sendError([
                'message' => __('Sorry! License could not be removed. Please try again', 'wp-social-reviews')
            ]);
        }

        return $response;
    }

    public function addLicense(Request $request)
    {
        $response = apply_filters('wpsr_activate_license', false, $request);
        if(!$response) {
            return $this->sendError([
                'message' => __('Sorry! License could not be added. Please try again', 'wp-social-reviews')
            ]);
        }

        return $response;
    }

    public function getTranslations()
    {
        $translationsSettings = (new GlobalSettings())->getGlobalSettings('translations');

        return [
            'message'               => 'success',
            'translations_settings' => $translationsSettings
        ];
    }

    public function saveTranslations(Request $request)
    {
        $translationsSettings = $request->get('translations_settings');
        $settings = get_option('wpsr_global_settings', []);
        $settings['global_settings']['translations'] = $translationsSettings;

        $globalSettings = (new GlobalSettings())->formatGlobalSettings($settings);

        update_option('wpsr_global_settings', $globalSettings);

        return [
            'message'   =>  __('Settings saved successfully!', 'wp-social-reviews')
        ];
    }

    public function getAdvanceSettings()
    {
        $advanceSettings = (new GlobalSettings())->getGlobalSettings('advance_settings');

        return [
            'message'           => 'success',
            'advance_settings'  => $advanceSettings
       ];
    }

    public function saveAdvanceSettings(Request $request)
    {
        $advanceSettings = $request->get('advance_settings');
        $settings = get_option('wpsr_global_settings', []);
        $settings['global_settings']['advance_settings'] = $advanceSettings;

        $globalSettings = (new GlobalSettings())->formatGlobalSettings($settings);

        update_option('wpsr_global_settings', $globalSettings);

        return [
            'message'   =>  __('Settings saved successfully!', 'wp-social-reviews')
        ];
    }

    public function resetData(Request $request)
    {
        $platform = $request->get('platform');

        do_action('wpsocialreviews/reset_data', $platform);

        return [
            'message'   =>  __('Images reset successfully!', 'wp-social-reviews')
        ];
    }

    public function resetErrorLog(Request $request)
    {
        delete_option('wpsr_errors');
        return [
            'message'   =>  __('Reset Error Logs successfully!', 'wp-social-reviews')
        ];
    }
}