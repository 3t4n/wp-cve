<?php

namespace FluentSupport\App\Services\ThirdParty;

use Exception;
use FluentSupport\App\Models\Agent;
use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Services\Tickets\ResponseService;
use FluentSupport\App\Services\Tickets\TicketService;

class HandleTelegramEvent
{


    /**
     * handleEvent method is responsible for handling telegram events
     * @param array $payload
     * @param string $token
     * @return array
     * @throws Exception
     */
    public function handleEvent ($payload, $token)
    {

        // Check if user have the pro version installed or not
        $this->verifyProVersion();

        // Check if the token is valid or not
        $this->validateToken($token);

        try {
            // Build the response or throw error
            $responseData = $this->parseResponseData($payload);

        } catch (\Exception $exception) {
            return false;
        }

        $data = [
            'conversation_type' => 'response',
            'content'           => $responseData['response_text'],
            'source'            => 'telegram'
        ];

        if(!empty($data['content'])) {
            // Create the response
            $this->createResponse($data, $responseData['ticket_id'], $responseData['agent_id']);
        }

        if(!empty($responseData['command'])) {
            $command = $responseData['command'];

            if($command == 'close_ticket') {
                $ticket = Ticket::find($responseData['ticket_id']);
                $agent = Agent::find($responseData['agent_id']);
                if($ticket && $agent) {
                    (new TicketService())->close( $ticket, $agent );
                }
            }
        }

        return $data;
    }


    /**
     * verifyProVersion method will check if the pro version is installed or not
     * @throws Exception
     * @return boolean | Exception
     */
    private function verifyProVersion ()
    {
        if (!defined('FLUENTSUPPORTPRO')) {
            throw new \Exception('Telegram Integration requires pro version of Fluent Support', 400);
        }

        return true;
    }

    /**
     * validateToken method will check if the token is valid or not
     * @param string $token
     * @throws Exception
     * @return boolean | Exception
     */
    private function validateToken ($token)
    {
        if (\FluentSupportPro\App\Services\Integrations\Telegram\TelegramHelper::getWebhookToken() != $token) {
            throw new \Exception('Bot Token could not be verified', 404);
        }
        return true;
    }

    /**
     * parseResponseData method will parse the response data and return an array with
     * response text and ticket id and agent id
     * @param array $payload
     * @throws Exception
     * @return array
     */
    private function parseResponseData ($payload)
    {
        $responseData = \FluentSupportPro\App\Services\Integrations\Telegram\TelegramHelper::parseTelegramBotPayload($payload);

        if ( is_wp_error( $responseData ) ) {
            /*
             * Action on telegram payload error when replying ticket from telegram
             *
             * @since v1.0.0
             * @param array $responseData
             * @param array $payload
             */
            do_action('fluent_support/telegram_payload_error', $responseData, $payload);

            throw new \Exception($responseData->get_error_message(), $responseData->get_error_code());
        } else {
            return $responseData;
        }
    }

    /**
     * createResponse method will create the response in a ticket
     * @param array $data
     * @param int $ticket_id
     * @param int $agent_id
     * @return array
     */
    private function createResponse ($data, $ticketId, $agentId)
    {
        $ticket = Ticket::find($ticketId);

        if ( !$ticket ) {
            throw new \Exception('No ticket found', 400);
        }

        $agent = Agent::find($agentId);

        if ( !$agent ) {
            throw new \Exception('No Agent found', 400);
        }

        (new ResponseService)->createResponse($data, $agent, $ticket);
    }
}
