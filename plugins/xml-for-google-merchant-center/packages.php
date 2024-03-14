<?php defined( 'ABSPATH' ) || exit;
require_once XFGMC_PLUGIN_DIR_PATH . '/common-libs/old-php-add-functions-1-0-0.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/common-libs/icopydoc-useful-functions-1-1-4.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/common-libs/wc-add-functions-1-0-1.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/common-libs/class-icpd-feedback-1-0-1.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/common-libs/class-icpd-promo-1-0-0.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/functions.php'; // Подключаем файл функций
require_once XFGMC_PLUGIN_DIR_PATH . '/common-libs/backward-compatibility.php';

require_once XFGMC_PLUGIN_DIR_PATH . '/data/countries.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/data/usa-states.php';

require_once XFGMC_PLUGIN_DIR_PATH . 'classes/generation/traits/simple/trait-xfgmc-t-simple-get-availability-date.php';
require_once XFGMC_PLUGIN_DIR_PATH . 'classes/generation/traits/variable/trait-xfgmc-t-variable-get-availability-date.php';

require_once XFGMC_PLUGIN_DIR_PATH . '/classes/generation/traits-xfgmc-global-variables.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/generation/traits-xfgmc-simple.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/generation/traits-xfgmc-variable.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/generation/class-xfgmc-get-closed-tag.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/generation/class-xfgmc-get-open-tag.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/generation/class-xfgmc-get-paired-tag.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/generation/class-xfgmc-get-unit.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/generation/class-xfgmc-get-unit-offer.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/generation/class-xfgmc-get-unit-offer-simple.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/generation/class-xfgmc-get-unit-offer-variable.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/generation/class-xfgmc-generation-xml.php';

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/system/class-xfgmc-wp-list-table.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/system/class-xfgmc-settings-feed-wp-list-table.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/system/class-xfgmc-error-log.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/system/class-xfgmc-feedback.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/system/class-xfgmc-plugin-form-activate.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/system/class-xfgmc-plugin-upd.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/system/class-xfgmc-settings-page.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/system/class-xfgmc-data-arr.php';
require_once XFGMC_PLUGIN_DIR_PATH . '/classes/system/class-xml-for-google-merchant-center.php';