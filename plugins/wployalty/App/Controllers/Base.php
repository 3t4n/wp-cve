<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Controllers;
defined('ABSPATH') or die;

use Wlr\App\Helpers\Input;
use Wlr\App\Helpers\Template;
use Wlr\App\Helpers\Woocommerce;

class Base
{
    public static $input, $woocommerce, $template, $rule;

    function __construct()
    {
        self::$input = empty(self::$input) ? new Input() : self::$input;
        self::$woocommerce = empty(self::$woocommerce) ? Woocommerce::getInstance() : self::$woocommerce;
        self::$template = empty(self::$template) ? new Template() : self::$template;
    }

    function isBasicSecurityValid($nonce_name = '')
    {
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        if (!Woocommerce::hasAdminPrivilege() || !Woocommerce::verify_nonce($wlr_nonce, $nonce_name)) {
            return false;
        }
        return true;
    }
}
