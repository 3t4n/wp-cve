<?php

namespace AOP\App\Admin\AdminPages\Settings;

use AOP\App\Plugin;
use AOP\App\Admin\AdminPages\Settings\Options\Checkbox;

class SubpageSettings
{
    const MENU_TITLE       = 'Settings';
    const PAGE_TITLE       = 'Settings';
    const SLUG             = Plugin::_NAME . '_settings';
    const HIDE_EDIT_BUTTON = Plugin::PREFIX_ . 'setting_editpage_hidden';

    public static function isSettingsPage()
    {
        return $_REQUEST['page'] === self::SLUG;
    }

    public static function view()
    {
        printf(
            '<h1 class="wp-heading-inline">%s</h1>',
            __(self::PAGE_TITLE)
        );

        print('<div class="wrap">');

        if ($_SERVER['DOCUMENT_URI'] !== '/wp-admin/options-general.php') {
            settings_errors();
        }

        print('<form method="post" action="options.php">');

        settings_fields(self::SLUG);
        do_settings_sections(self::SLUG);

        submit_button();
        print('</form>');

        print('</div>');
    }

    public static function allOptions()
    {
        new Checkbox([
            'page_name' => SubpageSettings::SLUG,
            'setting_name' => SubpageSettings::HIDE_EDIT_BUTTON,
            'field_label' => 'Hide "Edit page" button',
        ]);
    }
}
