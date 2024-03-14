<?php
namespace WEDOS\Mon\WP;

/**
 * Plugin base class
 *
 * @author    Petr Stastny <petr@stastny.eu>
 * @copyright WEDOS Internet, a.s.
 * @license   GPLv3
 */
class WedosOnline
{
    /**
     * Plugin init
     *
     * @return void
     */
    public static function init()
    {
        if (defined('WP_ADMIN')) {
            \PHPF\WP\Page\Notices::init();
            DashboardPage::addPage();
        }

        add_action('rest_api_init', function () {
            ApiServer\Ping::registerEndpoint();
            ApiServer\ValidatePairToken::registerEndpoint();
        });
    }
}
