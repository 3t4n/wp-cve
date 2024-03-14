<?php

namespace FluentSupport\App\Http\Controllers;

use FluentSupport\App\Modules\IntegrationSettingsModule;
use FluentSupport\Framework\Request\Request;

class IntegrationController extends Controller
{
    /**
     * getSettings method will fetch the list of integration settings by integration key
     * @param Request $request
     * @return false
     */
    public function getSettings(Request $request)
    {
        $settingsKey = $request->getSafe('integration_key', 'sanitize_text_field');
        return IntegrationSettingsModule::getSettings($settingsKey, true);
    }

    /**
     * saveSettings method will save the integration settings by integration key
     * @param Request $request
     * @return array
     */
    public function saveSettings(Request $request)
    {
        $settingsKey = $request->getSafe('integration_key', 'sanitize_text_field');

        $settings = $request->get('settings', []);

        foreach ($settings as $key => $value) {
            $settings[$key] = is_array($value) ? map_deep($value, 'sanitize_text_field') : sanitize_text_field($value);
        }

        $settings = wp_unslash($settings);

        $settings = IntegrationSettingsModule::saveSettings($settingsKey, $settings);

        if(!$settings || is_wp_error($settings)) {
            $errorMessage = (is_wp_error($settings)) ? $settings->get_error_message() : __('Settings failed to save', 'fluent-support');
            return $this->sendError([
                'message' => $errorMessage
            ]);
        }


        return [
            'message' => __('Settings has been updated', 'fluent-support'),
            'settings' => $settings
        ];
    }
}
