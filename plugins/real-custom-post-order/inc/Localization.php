<?php

namespace DevOwl\RealCustomPostOrder;

use DevOwl\RealCustomPostOrder\Vendor\MatthiasWeb\Utils\Constants;
use DevOwl\RealCustomPostOrder\Vendor\MatthiasWeb\Utils\Localization as UtilsLocalization;
use DevOwl\RealCustomPostOrder\base\UtilsProvider;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * i18n management for backend and frontend.
 * @internal
 */
class Localization
{
    use UtilsProvider;
    use UtilsLocalization;
    /**
     * Get the directory where the languages folder exists.
     *
     * @param string $type
     * @return string[]
     */
    protected function getPackageInfo($type)
    {
        if ($type === Constants::LOCALIZATION_BACKEND) {
            return [RCPO_PATH . '/languages', RCPO_TD];
        } else {
            return [RCPO_PATH . '/' . Constants::LOCALIZATION_PUBLIC_JSON_I18N, RCPO_TD];
        }
    }
}
