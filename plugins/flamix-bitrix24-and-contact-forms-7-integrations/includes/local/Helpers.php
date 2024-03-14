<?php

namespace FlamixLocal\CF7;

use FlamixLocal\CF7\Settings\Setting;
use Flamix\Bitrix24\Lead;
use Exception;

class Helpers
{
    /**
     * Get saved email or admin email.
     *
     * @return bool|string
     */
    public static function get_backup_email()
    {
        return Setting::getOption('lead_backup_email', get_option('admin_email', false));
    }

    /**
     * Send error msg to BackUp email.
     *
     * @param string $message
     * @return bool
     */
    public static function sendError(string $message = 'Something went wrong')
    {
        return wp_mail(self::get_backup_email(), Setting::PLUGIN_NAME . ' plugin: Error', $message);
    }

    /**
     * Sending data to Bitrix24 plugin.
     *
     * @param array $data
     * @param string $actions
     * @return array
     */
    public static function send(array $data, string $actions = 'lead/add'): array
    {
        return Lead::getInstance()->changeSubDomain(Handlers::getSubDomain())->setDomain(Setting::getOption('lead_domain'))->setToken(Setting::getOption('lead_api'))->send($data, $actions);
    }
}