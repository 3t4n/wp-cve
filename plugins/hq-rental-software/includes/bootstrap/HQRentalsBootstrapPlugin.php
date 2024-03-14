<?php

namespace HQRentalsPlugin\HQRentalsBootstrap;

use HQRentalsPlugin\HQRentalsActions\elementor\HQElementorActions;
use HQRentalsPlugin\HQRentalsActions\HQRentalsActionsAdmin;
use HQRentalsPlugin\HQRentalsActions\HQRentalsActionsRedirects;
use HQRentalsPlugin\HQRentalsActions\HQRentalsAjaxHandler;
use HQRentalsPlugin\HQRentalsAdmin\HQRentalsAdminBrandsPosts;
use HQRentalsPlugin\HQRentalsAssets\HQRentalsAssetsHandler;
use HQRentalsPlugin\HQRentalsCustomPosts\HQRentalsCustomPostsHandler;
use HQRentalsPlugin\HQRentalsQueries\HQRentalsQueriesAries;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsAdminSettings;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsBootstrap;
use HQRentalsPlugin\HQRentalsShortcodes\HQRentalsShortcoder;
use HQRentalsPlugin\HQRentalsTasks\HQRentalsCronJob;
use HQRentalsPlugin\HQRentalsTemplates\HQRentalsTemplateHandler;
use HQRentalsPlugin\HQRentalsThemes\HQRentalsBethemeShortcoder;
use HQRentalsPlugin\HQRentalsWebhooks\HQRentalsWebhooksManager;
use HQRentalsPlugin\HQRentalsWebhooks\HQRentalsWebsiteEndpoints;

class HQRentalsBootstrapPlugin
{
    public static $langPath = WP_PLUGIN_DIR . '/hq-rental-software/langs/';
    /**
     * HQRentalsBootstrapPlugin constructor.
     * Review menus Later on
     */
    public function __construct()
    {
        $this->worker = new HQRentalsCronJob();
        $this->assets = new HQRentalsAssetsHandler();
        $this->brandPostAdmin = new HQRentalsAdminBrandsPosts();
        $this->shortcoder = new HQRentalsShortcoder();
        $this->shortcoderBetheme = new HQRentalsBethemeShortcoder();
        $this->webhooks = new HQRentalsWebhooksManager();
        $this->api = new HQRentalsWebsiteEndpoints();
        $this->posts = new HQRentalsCustomPostsHandler();
        $this->settingsAdmin = new HQRentalsAdminSettings();
        $this->ariesQueries = new HQRentalsQueriesAries();
        $this->actions = new HQRentalsActionsRedirects();
        $this->elementor = new HQElementorActions();
        $this->templates = new HQRentalsTemplateHandler();
        $this->ajaxHandler = new HQRentalsAjaxHandler();
        $this->adminActins = new HQRentalsActionsAdmin();
        $this->loadLocalizationFiles();
    }
    private function getCurrentLocaleFile(): string
    {
        return HQRentalsBootstrapPlugin::$langPath . HQ_RENTALS_TEXT_DOMAIN . '-' . get_locale() . '.mo';
    }
    private function getTranslationFile($locale): string
    {
        if (strpos($locale, 'es') !== false) {
            return HQRentalsBootstrapPlugin::$langPath . HQ_RENTALS_TEXT_DOMAIN . '-es_CL.mo';
        }
        if (strpos($locale, 'pt') !== false) {
            return HQRentalsBootstrapPlugin::$langPath . HQ_RENTALS_TEXT_DOMAIN . '-pt_BR.mo';
        }
        if (strpos($locale, 'nl') !== false) {
            return HQRentalsBootstrapPlugin::$langPath . HQ_RENTALS_TEXT_DOMAIN . '-nl_NL.mo';
        }
        return HQRentalsBootstrapPlugin::$langPath . HQ_RENTALS_TEXT_DOMAIN . '-' . $locale . '.mo';
    }
    private function loadLocalizationFiles()
    {
        //load file in the current locale
        // never upload all langs
        load_textdomain(
            HQ_RENTALS_TEXT_DOMAIN,
            $this->getCurrentLocaleFile()
        );
    }
    public function forceLocale($locale): void
    {
        if ($locale) {
            load_textdomain(
                HQ_RENTALS_TEXT_DOMAIN,
                $this->getTranslationFile($locale)
            );
        }
    }
}
