<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Helpers;
defined('ABSPATH') or die;

class Message
{
    public static function notice($arguments)
    {
        self::handler('notice', $arguments);
    }

    private static function handler($name, $arguments)
    {
        if (!is_array($arguments)) {
            return;
        }
        $message = (isset($arguments['message']) && !empty($arguments['message'])) ? $arguments['message'] : '';
        $redirect_url = (isset($arguments['redirect']) && !empty($arguments['redirect'])) ? $arguments['redirect'] : '';
        $success = (isset($arguments['success']) && !empty($arguments['success'])) ? $arguments['success'] : false;
        $json = array(
            'success' => $success,
            'message' => $message,
            'redirect' => $redirect_url
        );
        wc_add_notice($message, $name);
        if ($success) {
            wp_send_json_success($json);
        }
        wp_send_json_error($json);
    }

    public static function success($arguments)
    {
        self::handler('success', $arguments);
    }

    public static function error($arguments)
    {
        self::handler('error', $arguments);
    }
}