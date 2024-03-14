<?php

defined('ABSPATH') or die('No script kiddies please!');

require_once('common/settings/settings.php');
require_once('common/user/user.php');

require_once('common/chats/chats.php');
require_once('common/chats/display_chat.php');

if (!class_exists("CBroShortCode")) {
    class CBroShortCode
    {
        private static $instance = null;

        private function __construct()
        {
            add_shortcode('chatbro', array(&$this, 'render'));
        }

        public static function get_instance()
        {
            if (!self::$instance) {
                self::$instance = new CBroShortCode();
            }
            return self::$instance;
        }

        public static function render($atts, $content = null)
        {
            $a = shortcode_atts(
                array(
                    'static' => true,
                    'registered_only' => false,
                    'title' => null,
                    'child' => false,
                    'ext_id' => null,
                    'id' => null
                ),
                $atts
            );

            if (!CBroSettings::get(CBroSettings::enable_shortcodes)) {
                return "";
            }

            $chat_id = $a['id'];
            $chat = null;

            if ($chat_id) {
                try {
                    $chat = CBroChats::get($chat_id);
                } catch (CBroChatNotFound $ex) {
                    return;
                }
            } else {
                $chat = CBroChats::get_default_chat();
            }

            if (!isset($chat)) {
                return;
            }

            $registered_only = $atts && array_key_exists('registered_only', $atts)
                ? (strtolower($a['registered_only']) == 'true' || $a['registered_only'] == '1')
                : !$chat->get_display_to_guests();


            if (!CBroUser::can_view($chat->get_display_to_guests()) || ($registered_only && !CBroUser::is_logged_in())) {
                return "";
            }

            $static = strtolower($a['static']) == 'true' || $a['static'] == '1';
            $child = strtolower($a['child']) == 'true' || $a['child'] == '1';

            if ($child && $a['title']) {
                $code = (new CBroDisplayChat($chat))->get_child_chat_code($static, $a['title'], $a['ext_id']);
            } else {
                $code = $static
                    ? (new CBroDisplayChat($chat))->get_static_chat_code()
                    : (new CBroDisplayChat($chat))->get_chat_code();
            }

            return $code;
        }
    }
}