<?php

namespace FluentSupport\App\Models;

use Exception;
use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Models\Attachment;
use FluentSupport\App\Services\Tickets\ResponseService;
use FluentSupport\App\Modules\PermissionManager;
use FluentSupport\App\Services\Helper;
use FluentSupport\Framework\Support\Arr;

class Conversation extends Model
{
    protected $table = 'fs_conversations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ticket_id',
        'person_id',
        'message_id',
        'conversation_type',
        'content',
        'source',
        'is_important'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if(empty($model->content_hash)) {
                $model->content_hash = md5($model->content);
            }
            $model->created_at = current_time('mysql');
            $model->updated_at = current_time('mysql');
        });

        static::deleting(function ($model) {
            //Delete cc info
            Meta::where('object_type', 'response')->where('object_id', $model->id)->delete();
        });
    }

    /**
     * $searchable Columns in table to search
     * @var array
     */
    protected $searchable = [
        'content'
    ];

    /**
     * Local scope to filter subscribers by search/query string
     * @param ModelQueryBuilder $query
     * @param string $search
     * @return ModelQueryBuilder
     */
    public function scopeSearchBy($query, $search)
    {
        if ($search) {
            $fields = $this->searchable;
            $query->where(function ($query) use ($fields, $search) {
                $query->where(array_shift($fields), 'LIKE', "%$search%");

                foreach ($fields as $field) {
                    $query->orWhere($field, 'LIKE', "$search%");
                }
            });
        }

        return $query;
    }

    /**
     * Local scope to filter subscribers by search/query string
     * @param ModelQueryBuilder $query
     * @param string $type
     * @return ModelQueryBuilder
     */
    public function scopeFilterByType($query, $type)
    {
        $query->whereIn('conversation_type', $type);

        return $query;
    }

    /**
     * One2Many: Customer has to many Click Tickets
     * @return Model Collection
     */
    public function ticket()
    {
        $class = __NAMESPACE__ . '\Ticket';

        return $this->belongsTo(
            $class, 'ticket_id', 'id'
        );
    }

    /**
     * One2Many: Customer has to many Click Tickets
     * @return Model Collection
     */
    public function person()
    {
        $class = __NAMESPACE__ . '\Person';

        return $this->belongsTo(
            $class, 'person_id', 'id'
        );
    }

    /**
     * A Conversation belongs to a Customer.
     *
     * @return \FluentSupport\App\Models\Model
     */
    public function customer()
    {
        return $this->person();
    }

    public function attachments()
    {
        $class = __NAMESPACE__ . '\Attachment';
        return $this->hasMany($class, 'conversation_id', 'id');
    }

    /**
     * One2One: Conversation has cc info
     * @return Model Collection
     */
    public function ccinfo()
    {
        $class = __NAMESPACE__ . '\Meta';

        return $this->hasOne(
            $class, 'object_id', 'id'
        )->where('object_type', 'response')->where('key', 'settings');
    }


    /**
     * This `doBulkReplies` will handle bulk replies
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function doBulkReplies ( $data )
    {
        $agent = Helper::getAgentByUserId();
        $tickets = $this->getTicketsToForBulkReply( $data, $agent );

        $responseData = [
            'content'           => wp_kses_post(Arr::get($data, 'content', '')),
            'conversation_type' => 'response',
            'close_ticket'      => Arr::get($data, 'close_ticket'),
        ];

        $attachments = Arr::get($data, 'attachments', []);
        $attachments = $this->getAttachsForBulkReplies( $attachments );

        foreach ( $tickets as $ticket ) {
            if ( $attachments ) {
                $responseData['attachments'] = [];
                foreach ( $attachments as $attachment ) {
                    $responseData['attachments'][] = $this->handleAttachmentOnBulkReplies ( $attachment, $ticket );
                }
            }
            (new ResponseService())->createResponse( $responseData, $agent, $ticket );
        }

        return [
            'message' => __( 'Response has been added to the selected tickets', 'fluent-support' )
        ];
    }

    // This is a supporting method of `doBulkReplies` it will return all selected tickets
    // Also it will check all check permission
    // @param array $data
    // @param object $agent
    private function getTicketsToForBulkReply( $data, $agent )
    {
        $ticketIds = array_filter($data['ticket_ids'], 'absint');

        //Get logged in agent information
        $hasAllPermission = PermissionManager::currentUserCan('fst_manage_other_tickets');
        $query = Ticket::whereIn('id', $ticketIds)->where('status', '!=', 'closed');

        //If the agent does not have permission
        if ( !$hasAllPermission ) {
            $query->where('agent_id', $agent->id); //Filter ticket by agent_id
        }

        $tickets = $query->get();

        //if not ticket found
        if ( $tickets->isEmpty() ) {
            throw new \Exception( 'Sorry no tickets found based on your filter and bulk actions');
        }

        return $tickets;
    }

    // This is a supporting method of `doBulkReplies` it will return it will return attachments
    // if agent add attachments to bulk replies if there is no attachments then it will return false
    // @param array $attachments
    private function getAttachsForBulkReplies ( $attachments )
    {
        if ( $attachments ) {
            $attachs = Attachment::whereNull('ticket_id')
                ->orderBy('id', 'asc')
                ->whereIn('file_hash', $attachments)
                ->get();
            return $attachs;
        }
        return false;
    }

    // This is a supporting method of `doBulkReplies` this method will prepare the uploaded attachments
    // for adding in response
    // @param object $attachment
    // @param object $ticket
    private function handleAttachmentOnBulkReplies ( $attachment, $ticket )
    {
        $attachedFile = $attachment->replicate();
        $attachedFile->ticket_id = $ticket->id;
        $attachedFile->save();
        return $attachedFile->file_hash;
    }


    /**
     * This `deleteResponse` is responsible for deleting response
     * @param int $ticketId
     * @param int $responseId
     * @return array
     * @throws Exception
     */
    public function deleteResponse ( $ticketId, $responseId )
    {
        $ticket = Ticket::findOrFail($ticketId);
        $response = static::findOrFail($responseId);
        $agent = Helper::getAgentByUserId();

        $this->checkUserTaskPermission( $ticket->agent_id, $agent->id, 'delete' );

        static::where('id', $response->id)->delete();
        $response->ccinfo()->delete();

        return [
            'message' => __('Selected response has been deleted', 'fluent-support')
        ];
    }

    /**
     * Update the conversation type for a response.
     *
     * @param array $data
     * @param int $ticketId
     * @param int $responseId
     * @param string $conversationType
     *
     * @return array
     * @throws Exception
     */
    public function publishDraftResponse ( $data, $ticketId, $responseId, $conversationType )
    {
        $ticket = Ticket::findOrFail($ticketId);
        $response = static::findOrFail($responseId);

        $content = wp_unslash(wp_kses_post($data['content']));
        $resetWaitingSince = apply_filters('fluent_support/reset_waiting_since', true, $content);

        $person = Helper::getAgentByUserId(get_current_user_id());

        $approveDraftResponsePermission = PermissionManager::currentUserCan('fst_approve_draft_reply');

        if ( !$approveDraftResponsePermission ) {
            throw new \Exception("Sorry, You do not have permission to approve this draft response");
        }

        $response->conversation_type = $conversationType;
        $response->created_at = current_time('mysql');
        $response->save();

        if ($person->person_type == 'agent' && $ticket->status == 'new') {
            $ticket->status = 'active';
            if ($ticket->created_at) {
                $ticket->first_response_time = strtotime(current_time('mysql')) - strtotime($ticket->created_at);
            } else {
                $ticket->first_response_time = 300;
            }
        }

        if ($resetWaitingSince) {
            $ticket->last_agent_response = current_time('mysql');
            $ticket->waiting_since = current_time('mysql');
        }

        $ticket->response_count += 1;
        $ticket->save();

        do_action('fluent_support/' . $conversationType . '_added_by_' . $person->person_type, $response, $ticket, $person);

        return [
            'message'  => __('Draft response has been successfully approved.', 'fluent-support'),
            'response' => $response,
        ];
    }

    public function updateResponse($data, $ticketId, $responseId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $response = static::findOrFail($responseId);

        $agent = Helper::getAgentByUserId();

        $this->checkUserTaskPermission($ticket->agent_id, $agent->id, 'update');

        $response->content = wp_unslash(wp_kses_post($data['content']));

        if ( $response->conversation_type == 'draft_response' ) {
            $this->updateDraftResponseData($response);
        }

        $response->save();

        return [
            'message'  => __('Selected response has been updated', 'fluent-support'),
            'response' => $response
        ];
    }

    public function updateDraftResponseData($response)
    {
        $agent = Helper::getAgentByUserId();
        $approveDraftResponsePermission = PermissionManager::currentUserCan('fst_approve_draft_reply');

        if ($response->person_id == $agent->id) {
            return null;
        }

        if ($approveDraftResponsePermission) {
            $response->conversation_type = 'response';
            $response->save();
            return [
                'message'  => __('Selected draft response has been approved and updated', 'fluent-support'),
                'response' => $response
            ];
        } else {
            throw new \Exception("Sorry, You do not have permission to approve this draft response");
        }
    }

    public function getSettingsValue($valueKey = false, $default = false)
    {
        $exist = Meta::where('object_type', 'response')
            ->where('key', 'settings')
            ->where('object_id', $this->id)
            ->first();

        if ($exist) {
            $value = maybe_unserialize($exist->value);
            if ($valueKey) {
                if (!is_array($value)) {
                    return $default;
                }
                return Arr::get($value, $valueKey, $default);
            }
            return $value;
        }

        return $default;
    }

    public function updateSettingsValue($valueKey, $value)
    {
        $exist = Meta::where('object_type', 'response')
            ->where('key', 'settings')
            ->where('object_id', $this->id)
            ->first();

        if ($exist) {
            $existingValue = maybe_unserialize($exist->value);

            if (!is_array($existingValue)) {
                $existingValue = [];
            }

            $existingValue[$valueKey] = $value;

            $exist->value = maybe_serialize($existingValue);
            $exist->save();
            return $this;
        }

        $settings = [
            'object_type' => 'response',
            'key'         => 'settings',
            'object_id'   => $this->id,
            'value'       => maybe_serialize([
                $valueKey => $value
            ])
        ];

        Meta::create($settings);

        return $this;

    }

    public static function deleteAll($ticketId){
        $conversations = Conversation::where('ticket_id', $ticketId)->get();
        foreach ($conversations as $conversation) {
            $conversation->delete();
        }
    }

    // This function will check agent permission to specific task regarding response response
    private function checkUserTaskPermission ( $ticketAgentId, $agentId, $task )
    {
        if ( !PermissionManager::currentUserCan('fst_manage_other_tickets') ) {
            if ( $ticketAgentId != $agentId ) {
                throw new \Exception("Sorry, You do not have permission to {$task} this response");
            }
        } else {
            return true;
        }
    }

}
