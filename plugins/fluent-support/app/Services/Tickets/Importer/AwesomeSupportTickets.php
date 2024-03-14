<?php

namespace FluentSupport\App\Services\Tickets\Importer;

use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Services\Helper;
use FluentSupport\App\Models\Attachment;
use FluentSupport\App\Models\Conversation;

class AwesomeSupportTickets extends BaseImporter
{
    protected $handler = 'awesome-support';

	public function stats()
	{
		$ticketsCount = $this->getCount();
        $replyCount = $this->db->table('posts')
            ->where('post_type', 'ticket_reply')
            ->count();

        return [
            'name' => esc_html('Awesome Support'),
            'tickets'       => $ticketsCount,
            'replies'       => $replyCount,
            'customers'     => count( get_users( array( 'role' => 'wpas_user' ) ) ),
            'handler'       => $this->handler,
            'last_migrated' => get_option('_fs_migrate_awesome_support')
        ];
	}

	/**
	 * This `doMigration` method will migrate ticket from other support system
	 * @param int $page
	 * @param string $handler
	 * @return array
	 */
    public function doMigration( $page, $handler )
    {
    	$this->handler = $handler;
        $allCounts = $this->getCount();

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
            'total_pages'   => ceil($allCounts / $this->limit),
            'imported_page' => $page,
            'next_page'     => $page + 1,
            'total_tickets' => $allCounts,
            'remaining'     => $remainingTickets
        ];

        if (!$hasMore) {
            $response['message'] = __('All tickets has been importer successfully', 'fluent-support');
            update_option('_fs_migrate_awesome_support', current_time('mysql'), 'no');
        }

        return $response;
    }

    /**
	 * This `getTickets` method will get tickets from other support system
	 * @param int $limit
	 * @param int $page
	 * @return object $tickets
	 */
	public function getTickets( $limit, $page )
    {
    	$args = [
    		'posts_per_page' => $limit,
    		'paged' => $page
    	];

        $tickets = \wpas_get_tickets('any', $args);
        $formattedTickets = [];

        if ($tickets){
            foreach($tickets as $ticket){
                $customerData = $this->resolvePerson($ticket->post_author);

                if (!$customerData) {
                    continue;
                }

                $replies = $this->getReplies($ticket);

                $ticketData = [
                    'origin_id'              => intval($ticket->ID),
                    'replies'                => $replies,
                    'source'                 => sanitize_text_field($this->handler),
                    'title'                  => sanitize_text_field($ticket->post_title),
                    'content'                => wp_kses_post($ticket->post_content),
                    'mailbox_id'             => intval($this->mailbox->id),
                    'response_count'         => count($replies),
                    'status'                 => 'active',
                    'created_at'             => $ticket->post_date,
                    'updated_at'             => $ticket->post_modified,
                    'last_customer_response' => $ticket->post_modified,
                    'waiting_since'          => $ticket->post_modified
                ];

                $ticketStatus = $this->getMeta('_wpas_status', $ticket->ID);

                if ($ticketStatus == 'closed') {
                    $ticketData['status'] = 'closed';
                    $ticketData['resolved_at'] = $this->getMeta('_ticket_closed_on', $ticket->ID);
                } else if (!$replies) {
                    $ticketData['status'] = 'new';
                }

                $customer = $this->getPerson($customerData, 'customer');

                if(!$customer) {
                    continue;
                }

                $ticketData['customer'] = $customer;
                $ticketData['attachments'] = $this->getAttachments($ticket->ID);

                $formattedTickets[] = $ticketData;
            }
        }

        return $formattedTickets;
    }

    /**
     * This `getMeta` method will get associated meta data of a ticket
     * @param string $metaKey
     * @param int $postId
     * @return array
     */
    public function getMeta($metaKey, $postId)
    {
        return get_post_meta($postId, $metaKey, true);
    }

    /**
     * This `getReplies` method will get associated replies with a ticket by it's id
     * @param object $ticket
     * @return array $formattedReplies
     */
    public function getReplies($ticket)
    {
        $replies = $this->db->table('posts')
            ->where('post_type', 'ticket_reply')
            ->where('post_parent', $ticket->ID)
            ->oldest('ID')
            ->get();

        $formattedReplies = [];

        foreach ($replies as $reply) {
            $formattedReplies[] = [
                'content'           => $reply->post_content,
                'conversation_type' => 'response',
                'created_at'        => $reply->post_date,
                'updated_at'        => $reply->post_modified,
                'is_customer_reply' => ($ticket->post_author === $reply->post_author),
                'user'              => $this->resolvePerson($reply->post_author),
                'attachments'       => $this->getAttachments($reply->ID)
            ];
        }

        return $formattedReplies;
    }

    protected function getAttachments($threadId)
    {
        $attachments = $this->db->table('posts')
            ->where('post_type', 'attachment')
            ->where('post_parent', $threadId)
            ->oldest('ID')
            ->get();

        $formattedAttachments = [];

        foreach ($attachments as $attachment) {
            $formattedAttachments[] = [
                'full_url'  => sanitize_url($attachment->guid),
                'title'     => $attachment->post_title,
                'file_path' => get_attached_file($attachment->ID),
                'driver'    => 'local',
                'status'    => 'active',
                'file_type' => $attachment->post_mime_type
            ];
        }

        return $formattedAttachments;
    }

    protected function getCount()
	{
		return count( \wpas_get_tickets( 'any' ) );
	}

    /**
     * This `deleteTickets` method will delete tickets with all available data
     * @param int $page //In some scenario it can be id of ticket
     * @return array $response
     */
    public function deleteTickets($page)
    {
        $hasmore = true;

        $args = [
            'posts_per_page' => $this->limit,
            'paged' => $page
        ];

        $tickets = \wpas_get_tickets('any', $args);

        if (!$tickets) {
            $hasmore = false;
        }

        if ($tickets) {
            foreach($tickets as $ticket){
                wp_delete_post($ticket->ID, true);

                $this->db->table('posts')
                    ->whereIn('post_type', ['ticket_reply', 'ticket_history'])
                    ->where('post_parent', $ticket->ID)
                    ->oldest('ID')
                    ->delete();
            }
        }

        $response = [
            'has_more' => $hasmore,
            'next_page' => (int) $page + 1
        ];

        if (!$hasmore) {
            $response['message'] = __('All tickets has been deleted successfully', 'fluent-support');
        }

        return $response;
    }

}


