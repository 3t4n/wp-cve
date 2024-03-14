<?php

namespace FluentSupport\App\Hooks\CLI;

use FluentSupport\App\Modules\StatModule;

class FluentCli
{
    public function stats($args, $assoc_args)
    {
        $overallStats = StatModule::getOverAllStats();
        $format = \WP_CLI\Utils\get_flag_value($assoc_args, 'format', 'table');

        \WP_CLI\Utils\format_items(
            $format,
            $overallStats,
            ['title', 'count']
        );
    }

    public function activate_license($args, $assoc_args)
    {
        if (empty($assoc_args['key'])) {
            \WP_CLI::line('use --key=LICENSE_KEY to activate the license');
            return;
        }

        $licenseKey = trim(sanitize_text_field($assoc_args['key']));

        if (!class_exists('\FluentSupportPro\App\Services\PluginManager\LicenseManager')) {
            \WP_CLI::line('FluentSupport Pro is required');
            return;
        }

        \WP_CLI::line('Validating License, Please wait');

        $licenseManager = new \FluentSupportPro\App\Services\PluginManager\LicenseManager();
        $response = $licenseManager->activateLicense($licenseKey);

        if (is_wp_error($response)) {
            \WP_CLI::error($response->get_error_message());
            return;
        }

        \WP_CLI::line('Your license key has been successfully updated');
        \WP_CLI::line('Your License Status: ' . $response['status']);
        \WP_CLI::line('Expire Date: ' . $response['expires']);
        return;
    }

    public function license_status()
    {

        if (!class_exists('\FluentSupportPro\App\Services\PluginManager\LicenseManager')) {
            \WP_CLI::line('FluentSupport Pro is required');
            return;
        }

        \WP_CLI::line('Fetching License details, Please wait');

        $licenseManager = new \FluentSupportPro\App\Services\PluginManager\LicenseManager();
        $licenseManager->verifyRemoteLicense(true);
        $response = $licenseManager->getLicenseDetails();

        \WP_CLI::line('Your License Status: ' . $response['status']);
        \WP_CLI::line('Expires: ' . $response['expires']);
        return;
    }

}
