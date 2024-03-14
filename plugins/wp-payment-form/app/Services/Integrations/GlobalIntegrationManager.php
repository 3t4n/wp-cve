<?php

namespace WPPayForm\App\Services\Integrations;

use WPPayForm\App\Models\Form;
use WPPayForm\App\Models\Meta;
use WPPayForm\App\Models\ScheduledActions;
use WPPayForm\App\Services\FormPlaceholders;
use WPPayForm\Framework\Support\Arr;
use WPPayForm\Framework\Foundation\App;

class GlobalIntegrationManager
{
    public function getGlobalSettingsData($request)
    {
        $settingsKey = sanitize_text_field(Arr::get($request, 'settings_key'));
        $settings = apply_filters('wppayform_global_integration_settings_' . $settingsKey, []);
        $fieldSettings = apply_filters('wppayform_global_integration_fields_' . $settingsKey, []);

        if (!$fieldSettings) {
            wp_send_json_error([
                'settings' => $settings,
                'settings_key' => $settingsKey,
                'message' => __('Sorry! No integration failed found with: ', 'wp-payment-form') . $settingsKey
            ], 423);
        }

        if (empty($fieldSettings['save_button_text'])) {
            $fieldSettings['save_button_text'] = __('Save Settings', 'wp-payment-form');
        }

        if (empty($fieldSettings['valid_message'])) {
            $fieldSettings['valid_message'] = __('Your API Key is valid', 'wp-payment-form');
        }

        if (empty($fieldSettings['invalid_message'])) {
            $fieldSettings['invalid_message'] = __('Your API Key is not valid', 'wp-payment-form');
        }

        wp_send_json_success([
            'integration' => $settings,
            'settings' => $fieldSettings
        ], 200);
    }

    public function authenticateCredentials($request)
    {
        $settingsKey = sanitize_text_field(Arr::get($request, 'settings_key'));
        $integration = wp_unslash(Arr::get($request, 'integration'));
        do_action('wppayform_authenticate_global_credentials_'. $settingsKey, $integration);
    }

    public function saveGlobalSettingsData($request)
    {
        $settingsKey = sanitize_text_field(Arr::get($request, 'settings_key'));
        $integration = wp_unslash(Arr::get($request, 'integration'));
        do_action('wppayform_save_global_integration_settings_' . $settingsKey, $integration);

        // Someone should catch that above action and send response
        wp_send_json_error([
            'message' => __('Sorry, no Integration found. Please make sure that latest version of Paymattic pro installed', 'wp-payment-form')
        ], 423);
    }

    public function getAllFormIntegrations($formId)
    {
        $formattedFeeds = $this->getNotificationFeeds($formId);

        $availableIntegrations = apply_filters('wppayform_get_available_form_integrations', [], $formId);

        return [
            'feeds' => $formattedFeeds,
            'available_integrations' => $availableIntegrations,
            'all_module_config_url' => admin_url('admin.php?page=wppayform.php#/integrations')
        ];
    }

    public function getNotificationFeeds($formId)
    {
        $notificationKeys = apply_filters('wppayform_global_notification_types', [], $formId);
        if ($notificationKeys) {
            $feeds = Meta::where('form_id', $formId)
                ->whereIn('meta_key', $notificationKeys)
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            $feeds = [];
        }

        $formattedFeeds = [];

        foreach ($feeds as $feed) {
            $data = json_decode($feed->meta_value, true);
            $enabled = $data['enabled'];
            if ($enabled && $enabled == 'true') {
                $enabled = true;
            } elseif ($enabled == 'false') {
                $enabled = false;
            }
            $feedData = [
                'id' => $feed->id,
                'name' => Arr::get($data, 'name'),
                'enabled' => $enabled,
                'provider' => $feed->meta_key,
                'feed' => $data
            ];
            $feedData = apply_filters('wppayform_global_notification_feed_' . $feed->meta_key, $feedData, $formId);
            $formattedFeeds[] = $feedData;
        }
        return $formattedFeeds;
    }

    public function updateNotificationStatus($formId, $request)
    {
        $notificationId = Arr::get($request, 'notification_id');
        $status = Arr::get($request, 'status');

        $feed = Meta::where('form_id', intval($formId))
            ->where('id', intval($notificationId))
            ->first();

        $notification = json_decode($feed->meta_value, true);

        if (!$status) {
            $notification['enabled'] = false;
        } else {
            $notification['enabled'] = true;
        }

        Meta::where('form_id', intval($formId))
            ->where('id', intval($notificationId))
            ->update([
                'meta_value' => json_encode($notification, JSON_NUMERIC_CHECK)
            ]);

        $feed = Meta::where('form_id', intval($formId))
            ->where('id', intval($notificationId))
            ->first();


        return [
            'message' => __('Integration successfully updated', 'wp-payment-form')
        ];
    }

    public function getIntegrationSettings($formId, $request)
    {
        $integrationName = Arr::get($request, 'integration_name');
        $integrationId = intval(Arr::get($request, 'integration_id'));

        $settings = [
            'conditionals' => [
                'conditions' => [],
                'status' => false,
                'type' => 'all'
            ],
            'enabled' => true,
            'list_id' => '',
            'list_name' => '',
            'name' => '',
            'merge_fields' => []
        ];

        $mergeFields = false;
        if ($integrationId) {
            $feed = Meta::where('form_id', $formId)
                ->where('id', $integrationId)
                ->first();

            if ($feed->meta_value) {
                $settings = json_decode($feed->meta_value, true);

                $settings = apply_filters('wppayform_get_integration_values_' . $integrationName, $settings, $feed, $formId);
                if (!empty($settings['list_id'])) {
                    $mergeFields = apply_filters('wppayform_get_integration_merge_fields_' . $integrationName, false, $settings['list_id'], $formId);
                }
            }
        } else {
            $settings = apply_filters('wppayform_get_integration_defaults_' . $integrationName, $settings, $formId);
        }

        if ($settings['enabled'] == 'true') {
            $settings['enabled'] = true;
        } elseif ($settings['enabled'] == 'false' || $settings['enabled']) {
            $settings['enabled'] = false;
        }

        $settingsFields = apply_filters('wppayform_get_integration_settings_fields_' . $integrationName, [], $formId, $settings);

        $shortCodes = FormPlaceholders::getAllShortCodes($formId);

        $inputs = Form::getInputShortcode($formId);

        return [
            'settings' => $settings,
            'settings_fields' => $settingsFields,
            'shortcodes' => $shortCodes,
            'inputs' => $inputs,
            'merge_fields' => $mergeFields
        ];
    }

    public function saveIntegrationSettings($formId, $request)
    {
        $integrationName = Arr::get($request, 'integration_name');
        $integrationId = intval(Arr::get($request, 'integration_id'));

        if (Arr::get($request, 'data_type') == 'stringify') {
            $integration = \json_decode(Arr::get($request, 'integration'), true);
        } else {
            $integration = wp_unslash(Arr::get($request, 'integration'));
        }

        if ($integration['enabled'] && $integration['enabled'] == 'true') {
            $integration['status'] = true;
        }

        if (!$integration['name']) {
            wp_send_json_error([
                'message' => 'Validation Failed',
                'errors' => [
                    'name' => ['Feed name is required']
                ]
            ], 423);
        }

        $integration = apply_filters('wppayform_save_integration_value_' . $integrationName, $integration, $integrationId, $formId);

        $data = [
            'form_id' => $formId,
            'meta_key' => sanitize_text_field($integrationName . '_feeds'),
            'meta_value' => \json_encode($integration)
        ];

        // action trigger group
        if (Arr::get($integration, 'trigger_on_payment', false)) {
            $data['meta_group'] = 'on_payment';
        } else {
            $data['meta_group'] = 'on_submission';
        }

        $data = apply_filters('wppayform_save_integration_settings_' . $integrationName, $data, $integrationId);

        $created = false;
        if ($integrationId) {
            Meta::where('form_id', $formId)
                ->where('id', $integrationId)
                ->update($data);
        } else {
            $created = true;
            $integrationId = Meta::insert($data);
        }

        return [
            'message' => __('Integration successfully saved', 'wp-payment-form'),
            'integration_id' => $integrationId,
            'integration_name' => $integrationName,
            'created' => $created
        ];
    }

    public function deleteIntegrationFeed($formId, $request)
    {
        $integrationId = intval(Arr::get($request, 'integration_id'));

        Meta::where('form_id', $formId)
            ->where('id', $integrationId)
            ->delete();

        return [
            'message' => __('Selected integration feed successfully deleted', 'wp-payment-form')
        ];
    }

    public function getIntegrationList($formId, $request)
    {
        $integrationName = Arr::get($request, 'integration_name');
        $listId = Arr::get($request, 'list_id');

        $merge_fields = apply_filters('wppayform_get_integration_merge_fields_' . $integrationName, false, $listId, $formId);

        return [
            'merge_fields' => $merge_fields
        ];
    }

    public function chainedData($request)
    {
        do_action('wppayform_chained_' . Arr::get($request, 'route'), $request);
    }

    public static function migrate()
    {
        $metaMigrated = Meta::migrate();
        $createScheduleTable = ScheduledActions::migrate();

        $status = Arr::get($metaMigrated, 'status');

        if ($status && $createScheduleTable) {
            update_option('wppayform_integration_status', 'yes', 'no');
            return array(
                "status" => $status,
                "message" => Arr::get($metaMigrated, 'message')
            );
        } else {
            $DB = App::getInstance('db');
            $test = $DB->select($DB->raw("show columns from wp_wpf_meta like 'form_id'"));
            if (count($test)) {
                update_option('wppayform_integration_status', 'yes', 'no');
            };

            return $metaMigrated;
        }
    }
}
