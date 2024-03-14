<?php

namespace FlamixLocal\CF7\Settings;

use Flamix\Plugin\General\Helpers;

class Setting
{
    use Menu;

    const PLUGIN_NAME = 'Bitrix24 ← Contact Form 7';
    const PLUGIN_TITLE = 'Bitrix24 and Contact Form 7 integration';
    const PLUGIN_URL = 'https://flamix.solutions/bitrix24/integrations/site/cf7.php';

    public static function init()
    {
        self::registerMenu();
        self::registerFields();
    }

    public static function registerFields()
    {
        add_action('admin_init', function () {
            register_setting(self::getOptionName('group'), self::getOptionName('lead_domain'), [Helpers::class, 'parseDomain']);
            register_setting(self::getOptionName('group'), self::getOptionName('lead_api'));
            register_setting(self::getOptionName('group'), self::getOptionName('lead_backup_email'));
        });
    }

    /**
     * Get Full Options Name
     *
     * @param string $name
     * @return string
     */
    public static function getOptionName(string $name): string
    {
        return 'flamix_bitrix24_cf7_' . $name;
    }

    /**
     * Get Options VALUE by Name
     *
     * @param string $name Option name
     * @param bool|int|string|null $default Default options value
     * @return mixed|void
     */
    public static function getOption(string $name, $default = false)
    {
        return get_option(self::getOptionName($name), $default);
    }
}