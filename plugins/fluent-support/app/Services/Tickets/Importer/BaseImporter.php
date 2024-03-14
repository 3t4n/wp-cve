<?php

namespace FluentSupport\App\Services\Tickets\Importer;

use FluentSupport\App\Models\Agent;
use FluentSupport\App\Models\Attachment;
use FluentSupport\App\Models\Customer;
use FluentSupport\App\Models\Person;
use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Services\Helper;
use FluentSupport\Framework\Support\Arr;

abstract class BaseImporter
{
    protected $db;
    protected $handler = '';
    protected $mailbox;
    protected $limit = 50;

    public function __construct()
    {
        $this->db = Helper::FluentSupport('db');
        $this->mailbox = Helper::getDefaultMailBox();
        $this->limit = apply_filters('fluent_support/ticket_import_chunk_limit', 50);
    }
    /**
     * This method `stats` returns an array of ticket stats of targeted helpdesk system
     *
     * @return array
     */
    abstract public function stats();

    /**
     * This method `doMigration` is responsible for migrating tickets from targeted helpdesk system
     * and it returns an array with migration response
     *
     * @param int $page
     * @param string $handler
     * @return array
     */
    abstract public function doMigration($page, $handler);

    /**
     * This method `deleteTickets` delete tickets and related data from targeted helpdesk once the
     * migration process done succesfully
     *
     * @param int $page // this will be migrated ticket page number
     * @return array
     */
    abstract public function deleteTickets($page);

    /**
     * This method `migrateTickets` migrate tickets on Fluent Support with the help of `migrateTicket` method
     *
     * @param array $tickets // array of tickets with all required data
     * @return array
     */
    public function migrateTickets($tickets)
    {
        $insertIds = [];
        $skips = [];
        foreach ($tickets as $ticket) {
            $createdTicket = $this->migrateTicket($ticket);
            if ($createdTicket) {
                $insertIds[] = $createdTicket->id;
            } else {
                $skips[] = $ticket;
            }
        }

        return [
            'inserts' => $insertIds,
            'skips'   => $skips
        ];
    }

    /**
     * This method `migrateTicket` is responsible for migrating a single ticket in Fluent Support
     *
     * @param array $ticketData
     * @return \FluentSupport\App\Models\Ticket $createdTicket
     */
    public function migrateTicket($ticketData)
    {
        if ($this->isMigrated($ticketData['origin_id'])) {
            return false;
        }

        $fillables = (new Ticket())->getFillable();
        $fillables[] = 'created_at';
        $fillables[] = 'updated_at';

        $data = array_filter(Arr::only($ticketData, $fillables));

        $data['customer_id'] = $ticketData['customer']->id;

        if (!empty($ticketData['agent'])) {
            $data['agent_id'] = $ticketData['agent']->id;
        }

        if (empty($ticketData['mailbox_id'])) {
            $data['mailbox_id'] = $this->mailbox->id;
        }

        $data = $this->addMetaData($data);

        $createdTicketId = $this->db->table('fs_tickets')
            ->insertGetId($data);

        if (!$createdTicketId) {
            return false;
        }

        $createdTicket = Ticket::find($createdTicketId);


        if (!empty($ticketData['attachments'])) {
            foreach ($ticketData['attachments'] as $attachmentData) {
                $attachmentData['person_id'] = $createdTicket->customer_id;
                $attachmentData['ticket_id'] = $createdTicket->id;
                Attachment::create($attachmentData);
            }
        }

        if (!$createdTicket) {
            return false;
        }

        Helper::updateTicketMeta($createdTicket->id, '_' . $this->handler . '_origin_id', $ticketData['origin_id']);

        if (empty($ticketData['replies'])) {
            return $createdTicket;
        }

        // Let's migrate the replies
        $lastAgentResponseTime = $createdTicket->last_customer_response;
        $lastCustomerResponseTime = $createdTicket->waiting_since;
        $waitingSince = $createdTicket->waiting_since;
        $responseCount = 0;
        $firstResponseTimestamp = false;

        $defualtAgentId = false;

        foreach ($ticketData['replies'] as $reply) {
            $replyData = Arr::only($reply, [
                'content',
                'conversation_type',
                'created_at',
                'updated_at'
            ]);

            $replyData['ticket_id'] = $createdTicket->id;
            $replyData['source'] = $this->handler;
            $replyData['content_hash'] = md5($replyData['content']);

            $person = ($reply['is_customer_reply']) ? $this->getPerson($reply['user']) : $this->getPerson($reply['user'], 'agent');

            if (!$person) {
                continue; // person could not be found
            }

            $replyData['person_id'] = intval($person->id);

            if ($replyData['conversation_type'] == 'response') {
                $responseCount++;
            }

            if ($reply['is_customer_reply']) {
                if (strtotime($lastCustomerResponseTime) < strtotime($replyData['created_at'])) {
                    $lastCustomerResponseTime = $replyData['created_at'];
                }
            } else if ($replyData['conversation_type'] == 'response') {

                if (!$firstResponseTimestamp) {
                    $firstResponseTimestamp = $replyData['created_at'];
                }

                if (strtotime($lastAgentResponseTime) < strtotime($replyData['created_at'])) {
                    $lastAgentResponseTime = $replyData['created_at'];
                    $waitingSince = $replyData['created_at'];
                }

                $defualtAgentId  = $person->id;
            }

            $conversionId = $this->db->table('fs_conversations')
                ->insertGetId($replyData);

            if (!empty($reply['attachments'])) {
                foreach ($reply['attachments'] as $attachmentData) {
                    $attachmentData['ticket_id'] = $createdTicket->id;
                    $attachmentData['person_id'] = $person->id;
                    $attachmentData['conversation_id'] = $conversionId;
                    Attachment::create($attachmentData);
                }
            }
        }

        $ticketUpdateData = array_filter([
            'last_customer_response' => $lastAgentResponseTime,
            'waiting_since'          => $waitingSince,
            'response_count'         => $responseCount
        ]);

        if(!$createdTicket->agent_id && $defualtAgentId) {
            $ticketUpdateData['agent_id'] = $defualtAgentId;
        }

        if ($firstResponseTimestamp) {
            $ticketUpdateData['first_response_time'] = strtotime($firstResponseTimestamp) - strtotime($createdTicket->crerated_at);
        }

        if ($createdTicket->status == 'closed' && $createdTicket->resolved_at) {
            $ticketUpdateData['total_close_time'] = strtotime($createdTicket->resolved_at) - strtotime($createdTicket->crerated_at);
        }

        if ($ticketUpdateData) {
            $createdTicket->fill($ticketUpdateData);
            $createdTicket->save();
        }

        return $createdTicket;
    }

    /**
     * This `getPerson` method will get associated person with a tickets or conversation
     * @param array $personData
     * @param string $type user type default is `customer`
     * @return object|false $person
     */
    protected function getPerson($personData, $type = 'customer')
    {

        static $cachedPerson = [];

        if (!empty($personData['user_id'])) {
            if (isset($personData[$personData['user_id'] . '_' . $type])) {
                return $personData[$personData['user_id'] . '_' . $type];
            }
        }

        if ($type == 'customer') {
            if (!empty($personData['user_id']) || !empty($personData['email'])) {
                $person = Customer::maybeCreateCustomer($personData);
                if ($person) {
                    $cachedPerson[$person->user_id . '_' . $type] = $person;
                }
                return $person;
            }
            return false;
        }

        if (!empty($personData['user_id'])) {
            $person = Person::where('user_id', $personData['user_id'])
                ->where('person_type', $type)
                ->first();
            if ($person) {
                $cachedPerson[$person->user_id . '_' . $type] = $person;

                return $person;
            }
        }

        if (!empty($personData['email'])) {
            $person = Person::where('email', $personData['email'])
                ->where('person_type', $type)
                ->first();
            if ($person) {
                $cachedPerson[$person->user_id . '_' . $type] = $person;
                return $person;
            }
        }

        $user = false;

        if (!empty($personData['user_id'])) {
            $user = get_user_by('ID', $personData['user_id']);
        } else if (!empty($personData['email'])) {
            $user = get_user_by('email', $personData['email']);
        }

//        if (!$user) {
//            return false;
//        }

        if (!$user && 'agent' == $type) {
            $person = Agent::updateOrCreate(
                [
                    'email' => $personData['email']
                ],
                [
                    'first_name' => $personData['first_name'],
                    'last_name'  => $personData['last_name'],
                    'email'      => $personData['email'],
                    'type'       => $type
                ]
            );

            return $person;
        }


        if ($user){
            $personData['user_id'] = $user->ID;
            $personData['first_name'] = $user->first_name;
            $personData['last_name'] = $user->last_name;
            $personData['last_name'] = $user->last_name;
            $personData['email'] = $user->user_email;

            if (empty($personData['full_name'])) {
                $personData['full_name'] = $user->display_name;
            }

        }

        if (empty($personData['first_name']) && $personData['last_name'] && !empty($personData['name'])) {
            $fullNameArray = explode(' ', $personData['name']);
            $personData['first_name'] = array_shift($fullNameArray);
            if ($fullNameArray) {
                $personData['last_name'] = implode(' ', $fullNameArray);
            }
            unset($personData['name']);
        }


        $personData['person_type'] = $type;

        $person = Person::create($personData);
        $cachedPerson[$person->user_id . '_' . $type] = $person;
        return $person;
    }

    protected function fallbackAgentId()
    {
        $agent = Person::where('person_type', 'agent')
            ->oldest('id')
            ->select(['id'])
            ->first();

        return (int)$agent->id;
    }

    protected function isMigrated($originId)
    {
        $exist = $this->db->table('fs_meta')
            ->where('object_type', 'ticket_meta')
            ->where('key', '_' . $this->handler . '_origin_id')
            ->where('value', $originId)
            ->first();

        return !!$exist;
    }

    protected function addMetaData($ticketData)
    {
        if (empty($ticketData['slug'])) {
            $ticketData['slug'] = Ticket::slugify($ticketData['title']);
        }

        $ticketData['hash'] = substr(md5(time() . wp_generate_uuid4()), 0, 8) . mt_rand(1, 99);

        $ticketData['content_hash'] = md5($ticketData['content']);

        if (empty($ticketData['last_customer_response'])) {
            $ticketData['last_customer_response'] = current_time('mysql');
        }

        if (empty($ticketData['created_at'])) {
            $ticketData['created_at'] = current_time('mysql');
        }
        if (empty($ticketData['updated_at'])) {
            $ticketData['updated_at'] = current_time('mysql');
        }

        if (empty($ticketData['waiting_since'])) {
            $ticketData['waiting_since'] = current_time('mysql');
        }

        return $ticketData;
    }


    public function resolvePerson($personUserId)
    {
        $person = get_user_by('ID', $personUserId);

        if (!$person) {
            return false;
        }

        return [
            'user_id'   => $personUserId,
            'full_name' => $person->first_name . ' ' . $person->last_name,
            'email'     => $person->user_email
        ];
    }
}
