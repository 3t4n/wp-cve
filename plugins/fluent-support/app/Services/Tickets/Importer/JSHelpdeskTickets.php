<?php

namespace FluentSupport\App\Services\Tickets\Importer;
use FluentSupport\App\Services\Tickets\Importer\BaseImporter;

class JSHelpdeskTickets extends BaseImporter
{
    protected $handler = 'js-helpdesk';
    private $attachmentDir;
    public function stats()
    {
        return [
            'name'      => esc_html('JS Helpdesk'),
            'handler'   => $this->handler,
            'tickets'   => $this->countTickets(),
            'replies'   => $this->countReplies(),
            'last_migrated' => get_option('_fs_migrate_js_helpdesk')
        ];
    }

    public function doMigration($page, $handler)
    {
        $this->handler = $handler;
        $allCounts = $this->countTickets();

        if (!$allCounts) {
            return [
                'message' => __('Sorry, no tickets available for import.', 'fluent-support'),
                'had_tickets' => 'no',
            ];
        }

        $tickets = $this->getTickets($this->limit, $page);

        $results = $this->migrateTickets($tickets);
        $hasMore = $page * $this->limit <= $allCounts;

        $remainingTickets = $allCounts - ($page * $this->limit);
        $completedTickets = intval(($page / $allCounts) * 100);

        $response = [
            'handler'       => $this->handler,
            'insert_ids'    => $results['inserts'],
            'skips'         => count($results['skips']),
            'has_more'      => $hasMore,
            'completed'     => $completedTickets,
            'imported_page' => $page,
            'total_pages'   => ceil($allCounts / $this->limit),
            'next_page'     => $page + 1,
            'total_tickets' => $allCounts,
            'remaining'     => $remainingTickets
        ];

        if (!$hasMore) {
            $response['message'] = __('All tickets has been importer successfully', 'fluent-support');
            update_option('_fs_migrate_js_helpdesk', current_time('mysql'), 'no');
        }

        return $response;
    }

    private function getTickets($limit, $page)
    {
        $offset = ($page - 1) * $limit;

        $tickets = $this->db->table('js_ticket_tickets')
            ->select(['id', 'uid', 'name', 'email', 'subject', 'message', 'status', 'lastreply', 'created', 'updated', 'isanswered', 'closed', 'attachmentdir', 'priorityid'])
            ->oldest('id')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $formattedTickets = [];

        if ($tickets) {
            foreach ($tickets as $ticket) {
                $this->attachmentDir = $ticket->attachmentdir;
                $replies = $this->getReplies($ticket->id, $ticket->uid);

                $ticketData = [
                    'origin_id'              => $ticket->id,
                    'replies'                => $replies,
                    'source'                 => $this->handler,
                    'title'                  => $ticket->subject,
                    'content'                => wp_kses_post($ticket->message),
                    'mailbox_id'             => $this->mailbox->id,
                    'response_count'         => count($replies),
                    'status'                 => 'active',
                    'created_at'             => $ticket->created,
                    'updated_at'             => $ticket->updated,
                ];

                if('0000-00-00 00:00:00' == $ticket->lastreply) {
                    $ticketData['waiting_since'] = $ticket->created;
                } else {
                    $ticketData['waiting_since'] = $ticket->lastreply;
                }

                if ($ticket->isanswered){
                    $ticketData['last_customer_response'] = $ticket->lastreply;
                } else {
                    $ticketData['last_agent_response'] = $ticket->lastreply;
                }

                if (!$replies) {
                    $ticketData['status'] = 'new';
                } else if($ticket->status == 4) {
                    $ticketData['status'] = 'closed';
                    $ticketData['resolved_at'] = $ticket->closed;
                }

                $ticketData['client_priority'] = $this->getPriority($ticket->priorityid);
                $ticketData['priority'] = $ticketData['client_priority'];
                $ticketData['attachments'] = $this->getAttachments($ticket->id, 'ticket');

                $customerData = [
                    'user_id'   => $ticket->uid,
                    'full_name' => $ticket->name,
                    'email'     => $ticket->email
                ];

                $customer = $this->getPerson($customerData, 'customer');

                $ticketData['customer'] = $customer;

                $formattedTickets[] = $ticketData;
            }
        }
        return $formattedTickets;
    }

    private function getReplies($ticketId, $customerId)
    {
        $replies = $this->db->table('js_ticket_replies')
            ->where('ticketid', $ticketId)
            ->oldest('id')
            ->get();

        $formattedReplies = [];

        foreach ($replies as $reply) {
            $formattedReplies[] = [
                'content'           => wp_kses_post($reply->message),
                'conversation_type' => 'response',
                'created_at'        => $reply->created,
                'updated_at'        => $reply->created,
                'is_customer_reply' => ($customerId === $reply->uid),
                'user'              => $this->resolvePerson($reply->uid),
                'attachments'       => $this->getAttachments($reply->id)
            ];
        }

        return $formattedReplies;

    }

    protected function getAttachments($threadId, $type='reply')
    {

        $attachments = $this->db->table('js_ticket_attachments')
            ->where(function($query) use ($type, $threadId) {
                if ($type == 'ticket') {
                    $query->where('ticketid', $threadId)
                        ->where('replyattachmentid', 0);
                } else {
                    $query->where('replyattachmentid', $threadId);
                }
            })
            ->get();

        $formattedAttachments = [];
        $uploadDir = wp_get_upload_dir();
        $jsHelpdeskUploadDir = '/jssupportticketdata/attachmentdata/ticket/';
        $uploadPath = $uploadDir['basedir'] . $jsHelpdeskUploadDir . $this->attachmentDir .'/';
        $uploadPathUrl = $uploadDir['baseurl'] . $jsHelpdeskUploadDir . $this->attachmentDir .'/';

        foreach ($attachments as $attachment) {
            $filePath = $uploadPath . $attachment->filename;
            $fullurl = $uploadPathUrl . $attachment->filename;
            $fileInfo = wp_check_filetype($filePath);

            $formattedAttachments[] = [
                'full_url'  => $fullurl,
                'title'     => $attachment->filename,
                'file_path' => $filePath,
                'driver'    => 'local',
                'status'    => 'active',
                'file_type' => (!empty($fileInfo['type'])) ? $fileInfo['type'] : ''
            ];
        }

        return $formattedAttachments;
    }

    private function countTickets() : int
    {
        return $this->db->table('js_ticket_tickets')
            ->count();
    }

    private function countReplies() : int
    {
        return $this->db->table('js_ticket_replies')
            ->count();
    }

    private function getPriority($priorityId)
    {
        if (2 == $priorityId) {
            return 'medium';
        } else if (4 == $priorityId) {
            return 'critical';
        } else {
            return 'normal';
        }
    }

    public function deleteTickets($page)
    {
        $tables = [
            'js_ticket_tickets',
            'js_ticket_replies',
            'js_ticket_config',
            'js_ticket_departments',
            'js_ticket_email',
            'js_ticket_emailtemplates',
            'js_ticket_erasedatarequests',
            'js_ticket_fieldsordering',
            'js_ticket_jshdsessiondata',
            'js_ticket_priorities',
            'js_ticket_slug',
            'js_ticket_system_errors',
            'js_ticket_users',
        ];

        global $wpdb;
        foreach ($tables as $table) {
            $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}{$table}");
        }

        return [
            'has_more' => false,
            'message'  => __('All JS Helpdesk tickets and associated data has been deleted. You may now deactivate JS Helpdesk Plugin and start using Fluent Support', 'fluent-support')
        ];
    }

}
