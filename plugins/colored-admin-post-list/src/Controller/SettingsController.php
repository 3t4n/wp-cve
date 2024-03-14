<?php

namespace Rockschtar\WordPress\ColoredAdminPostList\Controller;

use Rockschtar\WordPress\ColoredAdminPostList\Enums\AdminPage;
use Rockschtar\WordPress\ColoredAdminPostList\Enums\Setting;
use Rockschtar\WordPress\ColoredAdminPostList\Models\PostStatus;
use Rockschtar\WordPress\ColoredAdminPostList\Utils\PostStati;

class SettingsController
{
    use Controller;

    private function __construct()
    {
        add_action("admin_init", [&$this, "registerSettings"]);
        add_action("admin_menu", [&$this, 'adminMenu']);
        add_action('admin_print_scripts-settings_page_' . AdminPage::ADMIN_PAGE_OPTIONS, [&$this, "adminPrintScriptsSettings"]);
        add_action('admin_print_scripts-posts_page_' . AdminPage::ADMIN_PAGE_OPTIONS, [&$this, "adminPrintScriptsSettings"]);
        add_filter("plugin_action_links_" . CAPL_PLUGIN, [$this, 'pluginActionLinks']);
    }

    public function adminMenu(): void
    {
        add_options_page(
            "Colored Post List",
            "Colored Post List",
            "manage_options",
            AdminPage::ADMIN_PAGE_OPTIONS,
            [$this, "viewSettings"]
        );
    }

    public function adminPrintScriptsSettings(): void
    {
        wp_enqueue_style("wp-color-picker");
        wp_enqueue_script("wp-color-picker");
        wp_enqueue_script("capl-settings", CAPL_PLUGIN_URL . "scripts/settings.js", ["jquery", "wp-color-picker"]);
    }

    public function pluginActionLinks(array $links): array
    {
        $settingsLink = '<a href="options-general.php?page=' . AdminPage::ADMIN_PAGE_OPTIONS . '">' . __("Settings", "colored-admin-post-list") . '</a>';
        array_unshift($links, $settingsLink);
        return $links;
    }

    public function registerSettings(): void
    {
        $dummyCallback = static function () {
        };

        add_settings_section(
            Setting::SECTION_GENERAL,
            __("General", "colored-admin-post-list"),
            $dummyCallback,
            Setting::PAGE_DEFAULT
        );

        add_settings_section(
            Setting::SECTION_COLORS_DEFAULT,
            __("Default Post Statuses", "colored-admin-post-list"),
            $dummyCallback,
            Setting::PAGE_DEFAULT
        );

        add_settings_field(
            Setting::ENABLED,
            __("Enabled", "colored-admin-post-list"),
            [$this, "settingEnabled"],
            Setting::PAGE_DEFAULT,
            Setting::SECTION_GENERAL
        );

        $defaultPostStati = PostStati::getDefault();

        $registerSettingPostStati = function (PostStatus $postStatus, string $section) {
            add_settings_field(
                $postStatus->getOptionKey(),
                $postStatus->getLabel(),
                static function () use ($postStatus) {
                    $setting = get_option($postStatus->getOptionKey());
                    echo '<input class="capl-wp-color-picker" type="text" id="' . $postStatus->getOptionKey() . '" class="regular-text" name="' . $postStatus->getOptionKey() . '" value="' . $setting . '" />';
                },
                Setting::PAGE_DEFAULT,
                $section
            );

            register_setting(
                Setting::PAGE_DEFAULT,
                $postStatus->getOptionKey(),
                ['type' => 'string', 'default' => $postStatus->getDefaultColor()]
            );
        };

        foreach ($defaultPostStati as $defaultPostStatus) {
            $registerSettingPostStati($defaultPostStatus, Setting::SECTION_COLORS_DEFAULT);
        }

        $customPostStati = PostStati::getCustom();

        foreach ($customPostStati as $customPostStatus) {
            $registerSettingPostStati($customPostStatus, Setting::SECTION_COLORS_CUSTOM);
        }

        if (sizeof($customPostStati) > 0) {
            add_settings_section(
                Setting::SECTION_COLORS_CUSTOM,
                __("Custom Post Statuses", "colored-admin-post-list"),
                $dummyCallback,
                Setting::PAGE_DEFAULT
            );
        }
    }

    public function settingEnabled(): void
    {
        $checked = checked(get_option(Setting::ENABLED, false), true, false);
        echo '<input type="checkbox" name="' . Setting::ENABLED . '" value="1"' . $checked . '  />';
    }

    public function viewSettings(): void
    {
        include(CAPL_PLUGIN_DIR . "/views/settings.php");
    }
}
