<?php

namespace NitroPack\Integration\Hosting;

class SpinupWp extends Hosting {
    const STAGE = "very_early";

    public static function detect() {
        return !!getenv('SPINUPWP_SITE');
    }

    public function init($stage) {
        if (self::detect()) {
            switch ($stage) {
                case "very_early":
                    \NitroPack\Integration::initSemAcquire();
                    return true;
                case "late":
                    \NitroPack\Integration::initSemRelease();
                    add_action('nitropack_execute_purge_url', [$this, 'purgeUrl']);
                    add_action('nitropack_execute_purge_all', [$this, 'purgeAll']);
                    break;
            }
        }
    }

    public function purgeUrl($url) {
        if (!function_exists("spinupwp_purge_url")) return;
        spinupwp_purge_url($url);
    }

    public function purgeAll() {
        if (!function_exists("spinupwp_purge_site")) return;
        spinupwp_purge_site();
    }
}
