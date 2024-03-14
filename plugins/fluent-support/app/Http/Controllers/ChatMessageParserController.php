<?php

namespace FluentSupport\App\Http\Controllers;

use FluentSupport\Framework\Request\Request;
use FluentSupport\App\Services\ThirdParty\HandleSlackEvent;
use FluentSupport\App\Services\ThirdParty\HandleTelegramEvent;

/**
 *  ChatMessageParserController class is responsible for getting information from integrated 3rd party request and response
 * @package FluentSupport\App\Http\Controllers
 *
 * @version 1.0.0
 */

class ChatMessageParserController extends Controller
{
    /**
     * handleTelegramWebhook will get the content from telegram, check the data validity and create response in a ticket
     * @param Request $request
     * @param HandleTelegramEvent $handler
     * @param string $token
     * @throws Exception
     * @return mixed
     */
    public function handleTelegramWebhook(Request $request, HandleTelegramEvent $handler, $token)
    {
        try {
            return $this->sendSuccess([
                'message' => __('Response has been successfully recorded', 'fluent-support'),
                'status'  => true,
                'data'    => $handler->handleEvent($request->all(), $token)
            ]);
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage(),
                'status'  => false
            ]);
        }
    }

    /**
     * handleSlackEvent responsible for getting information from integrated slack request and response
     * @param HandleSlackEvent $handler
     * @param $token
     * @return array
     */
    public function handleSlackEvent(HandleSlackEvent $handler, $token)
    {
        try{
            $this->sendSuccess([
                'message' => 'received',
                'result' => $handler->handleEvent($token)
            ]);
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage(),
                'status'  => false
            ]);
        }
    }
}
