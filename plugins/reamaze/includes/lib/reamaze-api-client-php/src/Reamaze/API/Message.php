<?php

namespace Reamaze\API;

class Message extends APIResource {
    public static function create($params = null) {
        if (empty($params['conversation_slug'])) {
          throw new Exceptions\Api("Missing parameter 'conversation_slug'");
        }

        $client = self::getClient();

        $url = self::conversationMessagesUrl($params['conversation_slug']);

        unset($params['conversation_slug']);
        return $client->makeRequest('POST', $url, $params);
    }

    public static function all($params = null) {
        $url = self::url();
        if (!empty($params['conversation_slug'])) {
            $url = self::conversationMessagesUrl($params['conversation_slug']);
            unset($params['conversation_slug']);
        }

        $client = self::getClient();

        return $client->makeRequest('GET', $url, $params);
    }

    public static function conversationMessagesUrl($conversation_slug) {
        return Conversation::url() . "/" . $conversation_slug . '/messages';
    }
}
