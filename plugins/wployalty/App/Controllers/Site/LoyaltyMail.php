<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Controllers\Site;

use Wlr\App\Controllers\Base;

defined('ABSPATH') or die;


class LoyaltyMail extends Base
{
    function initNotification()
    {
        if (class_exists('\WPLoyalty\Wordpress')) {
            $wordpress = new \WPLoyalty\Wordpress();
            if (self::$woocommerce->isMethodExists($wordpress, 'initHook')) $wordpress->initHook();
        }

    }
}