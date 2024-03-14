<?php

namespace WPPayForm\App\Models;

use WPPayForm\Database\DBMigrator;
use WPPayForm\Framework\Support\Arr;

class GlobalSettings extends Model
{
    public static function updateSettings($request)
    {
        $settings = Arr::get($request, 'settings');
        // dd($request);
        // Validate the data
        if (empty($settings['currency'])) {
            wp_send_json_error(
                array(
                    'message' => __('Please select a currency', 'wp-payment-form')
                ),
                423
            );
        }

        $data = array(
            'currency' => sanitize_text_field(Arr::get($settings, 'currency')),
            'locale' => sanitize_text_field(Arr::get($settings, 'locale')),
            'currency_sign_position' => sanitize_text_field(Arr::get($settings, 'currency_sign_position')),
            'currency_separator' => sanitize_text_field(Arr::get($settings, 'currency_separator')),
            'decimal_points' => intval(Arr::get($settings, 'decimal_points')),
            'currency_conversion_api_key' => sanitize_text_field(Arr::get($settings, 'currency_conversion_api_key')),
            'currency_rate_caching_interval' => sanitize_text_field(Arr::get($settings, 'currency_rate_caching_interval'))
        );
        update_option('wppayform_global_currency_settings', $data);
        update_option('wppayform_ip_logging_status', sanitize_text_field(Arr::get($request, 'ip_logging_status')), false);
        update_option('wppayform_honeypot_status', sanitize_text_field(Arr::get($request, 'honeypot_status')), false);
        update_option('wppayform_abandoned_time', intval(Arr::get($request, 'abandoned_time')), false);
        update_option('wppayform_business_name', sanitize_text_field(Arr::get($request, 'business_name')));
        update_option('wppayform_business_address', sanitize_text_field(Arr::get($request, 'business_address')));
        update_option('wppayform_business_logo', sanitize_url(Arr::get($request, 'business_logo')));

        // We will forcefully try to upgrade the DB and later we will remove this after 1-2 version
        $firstTransaction = Transaction::first();

        if (!$firstTransaction || !property_exists($firstTransaction, 'subscription_id')) {
            DBMigrator::forceUpgradeDB();
        }
        // end upgrade DB
        return;
    }
}
