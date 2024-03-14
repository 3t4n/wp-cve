<?php
/**
 * Created by PhpStorm.
 * User: thanhlam
 * Date: 15/01/2021
 * Time: 20:45
 */

namespace NineKolor\TelegramWC\Classes;


class Sender
{
    public $chatID;
    public $token;
    public $parseMode;
    public $accessTags ;

    public function __construct()
    {
        $this->chatID = '';
        $this->token = '';
        $this->parseMode = 'HTML';
        $this->accessTags = '<b><strong><i><u><em><ins><s><strike><del><a><code><pre>';
    }


    public function sendMessage($value)
    {
        $this->postTelegramSendMessageAPI(strip_tags($value,$this->accessTags));
    }

    /**
     * @param string $text
     * @return array|mixed|object|string|\WP_Error|null
     */
    private function postTelegramSendMessageAPI($text)
    {
        $data = array('chat_id' => $this->chatID,
            'text' => stripcslashes(html_entity_decode($text)),
            'parse_mode' => $this->parseMode,
        );
        $return = wp_remote_post('https://api.telegram.org/bot' . $this->token . '/sendMessage', array(
            'timeout' => 5,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' => $data,
            'cookies' => array()
        ));
        if (is_wp_error($return)) {
            return json_encode(['ok' => false, 'curl_error_code' => $return->get_error_message()]);
        } else {
            $return = json_decode($return['body'], true);
        }
        return $return;
    }
}