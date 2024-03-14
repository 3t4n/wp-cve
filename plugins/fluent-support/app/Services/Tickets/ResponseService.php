<?php

namespace FluentSupport\App\Services\Tickets;

use FluentSupport\App\Models\Attachment;
use FluentSupport\App\Models\Conversation;
use FluentSupport\App\Services\Helper;
use FluentSupport\App\Services\Includes\UploadService;
use FluentSupport\Framework\Support\Arr;

class ResponseService
{
    /**
     * createResponse method is responsible for create responses to ticket by agent or customer
     * @param $data
     * @param $person
     * @param $ticket
     * @return array|false
     */
    public function createResponse($data, $person, $ticket, $silently = false)
    {
        if (empty($data['content'])) {
            return false;
        }

        $cc_emails = Arr::get($data, 'cc_emails', []);

        // Adding support for shortcode in agent response
        if ($person->person_type == 'agent') {
            $data['content'] = apply_filters('fluent_support/parse_smartcode_data', $data['content'], [
                'customer' => $ticket->customer,
                'agent'    => $person
            ]);
        }

        $convoType = Arr::get($data, 'conversation_type', 'response');

        $content = wp_unslash(wp_kses_post($data['content']));
        $resetWaitingSince = apply_filters('fluent_support/reset_waiting_since', true, $content);
        $content = apply_filters('fluent_support/response_content_before_use_anywhere', $content);
        $response = [
            'person_id'         => $person->id,
            'ticket_id'         => $ticket->id,
            'conversation_type' => $convoType,
            'content'           => $content,
            'source'            => Arr::get($data, 'source', 'web'),
            'content_hash'      => md5($content)
        ];

        if (!empty($data['message_id'])) {
            $response['message_id'] = sanitize_text_field($data['message_id']);
        }

        $response = apply_filters('fluent_support/new_' . $person->person_type . '_' . $convoType, $response, $ticket, $person);

        $createdResponse = \FluentSupport\App\Models\Conversation::create($response);

        if (!empty($cc_emails)) {
            //Store cc emails in meta settings for the response
            $createdResponse->updateSettingsValue('cc_email', $cc_emails);

            //Store all the carbon copy customers under the ticket
            $existingCcEmails = $ticket->getSettingsValue('all_cc_email', []);
            $newData = array_merge($existingCcEmails, $cc_emails);
            $ticket->updateSettingsValue('all_cc_email', array_unique($newData));
        }

        $createdResponse->load('person');

        if ($person->person_type == 'agent' && $ticket->status == 'new' && $convoType == 'response') {
            $ticket->status = 'active';
            if ($ticket->created_at) {
                $ticket->first_response_time = strtotime(current_time('mysql')) - strtotime($ticket->created_at);
            } else {
                $ticket->first_response_time = 300;
            }
        }

        $agentAdded = false;
        $updateData = [];

        if ($person->person_type == 'agent') {
            if (!$ticket->agent_id && $convoType == 'response') {
                $ticket->agent_id = $person->id;
                $agentAdded = true;
                $updateData = [
                    'agent_id' => $person->id
                ];
            }

            if ($convoType == 'response') {
                if ($resetWaitingSince) {
                    $ticket->last_agent_response = current_time('mysql');
                    $ticket->waiting_since = current_time('mysql');
                }
            }
        } else {

            if ($ticket->last_agent_response && strtotime($ticket->last_agent_response) > strtotime($ticket->last_customer_response)) {
                $ticket->waiting_since = current_time('mysql');
            }

            $ticket->last_customer_response = current_time('mysql');
        }

        if ($convoType == 'response') {
            $ticket->response_count += 1;
        }

        $closed = false;
        if (Arr::get($data, 'close_ticket') == 'yes' && $ticket->status != 'closed') {
            $ticket->status = 'closed';
            $ticket->resolved_at = current_time('mysql');
            $ticket->closed_by = $person->id;
            $ticket->total_close_time = current_time('timestamp') - strtotime($ticket->created_at);
            $closed = true;

            $internalNote = __('Ticket has been closed', 'fluent-support');

            $internalNote = apply_filters('fluent_support/ticket_close_internal_note', $internalNote, $ticket);

            if ($internalNote) {
                Conversation::create([
                    'ticket_id'         => $ticket->id,
                    'person_id'         => $person->id,
                    'conversation_type' => 'internal_info',
                    'content'           => $internalNote
                ]);
            }
        }

        $ticket->save();

        //If file upload failed to local during create response
        if ($attachmentHashes = Arr::get($data, 'attachments', [])) {
            $attachments = Attachment::where('ticket_id', $ticket->id)
                ->whereIn('file_hash', $attachmentHashes)
                ->where('status', 'in-active')
                ->get();

            if (!$attachments->isEmpty()) {
                $storageDriver = Helper::getUploadDriverKey();
                foreach ($attachments as $attachment) {
                    if ($storageDriver != 'local') {
                        do_action_ref_array('fluent_support/finalize_file_upload_' . $storageDriver, [&$attachment, $ticket->id]);
                    }

                    if ($attachment->driver == 'local') {
                        $newFileInfo = UploadService::copyFileTicketFolder($attachment->file_path, $ticket->id);
                        if ($newFileInfo && !empty($newFileInfo['file_path'])) {
                            $attachment->file_path = $newFileInfo['file_path'];
                            $attachment->full_url = $newFileInfo['url'];
                        }
                    }

                    $attachment->ticket_id = $ticket->id;
                    $attachment->person_id = $createdResponse->person_id;
                    $attachment->conversation_id = $createdResponse->id;
                    $attachment->status = 'active';
                    $attachment->save();
                }
                $createdResponse->load('attachments');
            }
        }

        if ($silently) {
            return [
                'response'    => $createdResponse,
                'ticket'      => $ticket,
                'update_data' => $updateData
            ];
        }

        if ($agentAdded) {
            $assigner = Helper::getCurrentAgent();
            $ticket->load('agent');

            /*
             * Action on ticket assign to an agent
             *
             * @since v1.0.0
             * @param object $person
             * @param object $ticket
             * @param object $assigner
             */
            do_action('fluent_support/agent_assigned_to_ticket', $person, $ticket, $assigner);
            $updateData['agent'] = $ticket->agent;
        }

        /*
         * Action on conversation
         *
         * @since v1.0.0
         * @param string $convoType
         * @param string $personType
         * @param object $createdResponse
         * @param object $ticket
         * @param object $person
         */
        do_action('fluent_support/' . $convoType . '_added_by_' . $person->person_type, $createdResponse, $ticket, $person);


        if ($closed) {
            /*
             * Action on ticket close
             *
             * @since v1.0.0
             * @param object $ticket
             * @param object $person
             */
            do_action('fluent_support/ticket_closed', $ticket, $person);

            /*
             * Action on ticket close
             *
             * @since v1.0.0
             * @param string $personType
             * @param object $ticket
             * @param object $person
             */
            do_action('fluent_support/ticket_closed_by_' . $person->person_type, $ticket, $person);
        }

        return [
            'response'    => $createdResponse,
            'ticket'      => $ticket,
            'update_data' => $updateData
        ];
    }
}
