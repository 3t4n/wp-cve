<?php

namespace FluentSupport\App\Models;

use Exception;
use FluentSupport\App\Http\Controllers\AuthController;
use FluentSupport\App\Modules\PermissionManager;
use FluentSupport\App\Services\Helper;
use FluentSupport\App\Services\ProfileInfoService;
use FluentSupport\App\Services\TicketHelper;
use FluentSupport\App\Services\TicketQueryService;
use FluentSupport\App\Services\Tickets\ResponseService;
use FluentSupport\App\Services\Tickets\TicketService;
use FluentSupport\Framework\Support\Arr;

class Ticket extends Model
{
    protected $table = 'fs_tickets';

    protected $dates = ['waiting_since'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'agent_id',
        'product_id',
        'mailbox_id',
        'product_source',
        'privacy',
        'priority',
        'client_priority',
        'status',
        'title',
        'slug',
        'hash',
        'source',
        'message_id',
        'content',
        'last_agent_response',
        'last_customer_response',
        'waiting_since',
        'response_count',
        'first_response_time',
        'total_close_time',
        'resolved_at',
        'closed_by'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = static::slugify($model->title);
            }

            $model->hash = substr(md5(time() . wp_generate_uuid4()), 0, 8) . mt_rand(1, 99);
            $model->content_hash = md5($model->content);

            $model->last_customer_response = current_time('mysql');
            $model->created_at = current_time('mysql');
            $model->updated_at = current_time('mysql');
            $model->waiting_since = current_time('mysql');

        });

        static::deleting(function ($model) {
            //Delete the ticket meta
            Meta::where('object_type', 'ticket_meta')->where('object_id', $model->id)->delete();
            //Delete all cc info for the ticket
            Meta::where('object_type', 'ticket')->where('object_id', $model->id)->delete();
            //Delete draft info
            Meta::where('object_type', '_fs_auto_draft')->where('object_id', $model->id)->delete();
            //delete the responses first
            Conversation::deleteAll($model->id);
        });
    }

    /**
     * $searchable Columns in table to search
     * @var array
     */
    protected $searchable = [
        'content',
        'title',
        'slug',
        'id'
    ];

    /**
     * Local scope to filter tickets by search/query string
     * @param ModelQueryBuilder $query
     * @param string $search
     * @return ModelQueryBuilder
     */
    public function scopeSearchBy($query, $search)
    {
        if (strpos($search, ':')) {
            $array = explode(':', $search);
            $column = $array[0];
            $value = $array[1];
            $columns = $this->fillable;
            $columns[] = 'id';

            if (in_array($column, $columns) && $value) {
                if (is_numeric($value)) {
                    $query->where($column, $value);
                } else {
                    $query->where($column, 'LIKE', "%$value%");
                }
                return $query;
            }
        }

        $fields = $this->searchable;
        $query->where(function ($query) use ($fields, $search) {
            $query->where(array_shift($fields), 'LIKE', "%$search%");
            foreach ($fields as $field) {
                $query->orWhere($field, 'LIKE', "%$search%");
            }
        });

        return $query;
    }

    /**
     * Local scope to filter tickets by different filtering condition
     * @param ModelQueryBuilder $query
     * @param mixed $search
     * @return ModelQueryBuilder
     */

    public function doSearchForAdvancedFilter($query, $search)
    {
        foreach ($search as $s) {
            $operator = $s['operator'];
            //If selected item for ticket either title or content
            if (in_array($s['property'], ['title', 'content'])) {
                //If the selected condition is contains, query operator id LIKE
                if ($operator == 'contains') {
                    $query = $query->where(function ($query) use ($s) {
                        $query->where($s['property'], 'LIKE', "%" . $s['value'] . "%");
                    });
                } elseif ($operator == 'not_contains') {
                    //If the selected condition is not_contains, query operator id NOT LIKE
                    $query = $query->where(function ($query) use ($s) {
                        $query->where($s['property'], 'NOT LIKE', '%' . $s['value'] . '%');
                    });
                }
            }

            //If selected item is Ticket Conversation Content
            if ($s['property'] == 'conversation_content') {
                $operator = $s['operator'];
                if ($operator == 'contains') {
                    $query = $query->whereHas('responses', function ($q) use ($s) {
                        $q->where('content', 'LIKE', "%" . $s['value'] . "%");
                    });

                } else if ($operator == 'not_contains') {
                    $query = $query->whereHas('responses', function ($q) use ($s) {
                        $q->where('content', 'NOT LIKE', "%" . $s['value'] . "%");
                    });
                }
            }

            //If selected item is Ticket created or Last Response or Customer Waiting For, or Last Agent Response or Last Customer Response
            if (in_array($s['property'], ['created_at', 'updated_at', 'waiting_since', 'last_agent_response', 'last_customer_response'])) {
                $query = (new \FluentSupport\App\Models\Ticket())->buildDateBaseFilterQuery($query, $s);
            }

            //If selected item is Ticket Status or Client Priority or Agent Priority or Tags or Product or Waiting For Reply
            if (in_array($s['property'], ['status', 'client_priority', 'priority', 'tags', 'product', 'waiting_for_reply', 'agent_id', 'mailbox_id'])) {
                $query = (new \FluentSupport\App\Models\Ticket())->buildPropertiesFilterQuery($query, $s);
            }
        }
        return $query;
    }

    /**
     * Local scope to filter subscribers by search/query string
     * @param ModelQueryBuilder $query
     * @param array $statuses
     * @return ModelQueryBuilder
     */
    public function scopeFilterByStatues($query, $statuses)
    {
        if ($statuses) {
            $query->whereIn('status', $statuses);
        }

        return $query;
    }

    /**
     * Local scope to filter tickets by not response by agent
     * @param $query
     * @return mixed
     */
    public function scopeWaitingOnly($query)
    {
        $query->where(function ($q) {
            $q->whereColumn('last_agent_response', '<', 'last_customer_response')
                ->orWhereNull('last_agent_response')
                ->orWhere('status', 'new');
        });
        return $query;
    }

    /**
     * scopeApplyFilters method will filet ticket based on the selected filters
     * This method will get filter option as parameter, loop through and apply conditions in query
     * @param $query
     * @param $filters
     * @return ModelQueryBuilder
     */
    public function scopeApplyFilters($query, $filters)
    {
        $supportedColumns = ['product_id', 'client_priority', 'priority', 'mailbox_id'];
        foreach ($filters as $filterKey => $filterValue) {
            if (!$filterValue && ($filterValue !== '0' || $filterValue !== 0)) {
                continue;
            }
            //If filer using status
            if ($filterKey == 'status_type') {
                //Get list of ticket status
                $statusArray = Helper::getTkStatusesByGroupName($filterValue);
                if ($statusArray) {
                    //Apply filet where status in
                    $query->whereIn('status', $statusArray);
                }
            } else if (in_array($filterKey, $supportedColumns)) {
                $query->where($filterKey, $filterValue);
            } else if ($filterKey == 'waiting_for_reply') {
                if ($filterValue != 'yes') {
                    continue;
                }
                //Apply filter where no response by agent
                $query = $this->scopeWaitingOnly($query);
            } else if ($filterKey == 'agent_id') {
                //Apply filter where ticket is not assigned
                if ($filterValue == 'unassigned') {
                    $query->whereNull($filterKey);
                } else {
                    if (defined('FLUENTSUPPORTPRO')) {
                        if (isset($filters['watcher']) && $filters['watcher'] == 'watcher') {
                            $watcherTickets = TicketHelper::getWatcherTicketIds($filterValue);
                            $query->whereIn('id', $watcherTickets);
                        } else {
                            //Apply filter, get only assigned ticket
                            $query->where($filterKey, $filterValue);
                        }
                    }
                }
            } else if ($filterKey == 'ticket_tags') {
                if (!$filterValue) {
                    continue;
                }
                //Apply filter where ticket only has this tag id
                $query->whereHas('tags', function ($q) use ($filterValue) {
                    $q->whereIn('tag_id', $filterValue);
                });
            }
        }

        return $query;
    }

    /**
     * Local scope to filter tickets by agent id
     * @param ModelQueryBuilder $query
     * @param int $agentId
     * @return ModelQueryBuilder
     */
    public function scopeFilterByAgentId($query, $agentId)
    {
        if ($agentId) {
            $query->where('agent_id', $agentId);
        }

        return $query;
    }

    /**
     * Local scope to filter subscribers by search/query string
     * @param ModelQueryBuilder $query
     * @param int $customerId
     * @return ModelQueryBuilder
     */
    public function scopeFilterByCustomerId($query, $customerId)
    {
        $query->where('customer_id', $customerId);

        return $query;
    }

    /**
     * Local scope to filter subscribers by search/query string
     * @param ModelQueryBuilder $query
     * @param int $productId
     * @return ModelQueryBuilder
     */
    public function scopeFilterByProductId($query, $productId)
    {
        if ($productId) {
            $query->where('product_id', $productId);
        }

        return $query;
    }

    /**
     * Local scope to filter subscribers by search/query string
     * @param ModelQueryBuilder $query
     * @param array $priorities
     * @return ModelQueryBuilder
     */
    public function scopeFilterByPriorities($query, $priorities)
    {
        if ($priorities) {
            $query->whereIn('priority', $priorities);
        }

        return $query;
    }

    /**
     * @param $filter
     * @return string[]
     */

    public static function parseRelationalFilterQueryMethods($filter)
    {
        // default operator = in
        $method = 'whereHas';
        $subMethod = 'whereIn';

        switch ($filter['operator']) {
            case 'not_in':
                $method = 'whereDoesntHave';
                $subMethod = 'whereIn';

                break;
            case 'in_all':
                $method = 'whereHas';
                $subMethod = 'where';

                break;
            case 'not_in_all':
                $method = 'whereDoesntHave';
                $subMethod = 'where';

                break;
        }

        return [$method, $subMethod];
    }

    /**
     * Parse filter to set proper operator and value for the filter query.
     *
     * @param array $filter
     * @return array
     */
    public static function filterParser($filter)
    {
        switch ($filter['operator']) {
            case 'before':
                $filter['operator'] = '<';
                $filter['value'] = $filter['value'] . ' 23:59:59';
                break;

            case 'after':
                $filter['operator'] = '>';
                $filter['value'] = $filter['value'] . ' 23:59:59';
                break;

            case 'date_equal':
                $filter['operator'] = 'LIKE';
                $filter['value'] = '%' . $filter['value'] . '%';
                break;

            case 'days_before':
                $filter['operator'] = '<';
                $filter['value'] = date('Y-m-d', current_time('timestamp') - $filter['value'] * 24 * 60 * 60);
                break;

            case 'days_within':
                $filter['operator'] = 'BETWEEN';
                $filter['value'] = [
                    date('Y-m-d', current_time('timestamp') - $filter['value'] * 24 * 60 * 60),
                    date('Y-m-d') . ' 23:59:59'
                ];
                break;
            case 'date_range':
                $filter['operator'] = 'BETWEEN';
                if (isset($filter['value'][0]))
                    $filter['value'][0] .= ' 00:00:00';
                if (isset($filter['value'][1]))
                    $filter['value'][1] .= ' 23:59:59';
                break;
        }

        return $filter;
    }

    /**
     * @param \FluentSupport\Framework\Database\Orm\Builder|\FluentSupport\Framework\Database\Query\Builder $query
     * @param array $filters
     * @return ModelQueryBuilder
     */
    public function buildDateBaseFilterQuery($query, $filters)
    {
        $filter = static::filterParser($filters);
        $query->where(function ($dateQuery) use ($filter) {

            if ($filter['operator'] == 'BETWEEN') {
                $dateQuery->whereBetween($filter['property'], $filter['value']);
            } else {
                $dateQuery->where($filter['property'], $filter['operator'], $filter['value']);
            }
        });

        return $query;
    }

    /**
     * Relation builder
     * @param $relation
     * @param $query
     * @param $method
     * @param $subMethod
     * @param $subField
     * @param $filter
     * @param false $provider
     * @return ModelQueryBuilder
     */

    public static function buildRelationFilterQuery($relation, $query, $method, $subMethod, $subField, $filter, $provider = false)
    {
        if (in_array($filter['operator'], ['in_all', 'not_in_all']) && $filter['value']) {
            foreach ($filter['value'] as $item) {
                $query = static::buildRelationFilterQuery($relation, $query, $method, $subMethod, $subField, ['value' => $item, 'operator' => ''], $provider);
            }
        } else {
            $query = $query->{$method}($relation, function ($relationQuery) use ($subMethod, $subField, $filter, $provider) {
                $relationQuery = $relationQuery->{$subMethod}($subField, $filter['value']);

                if ($provider) {
                    $relationQuery = $relationQuery->where('provider', $provider);
                }

                return $relationQuery;
            });
        }

        return $query;
    }

    /**
     * get tickets by advanced filter segment data
     * @param $query
     * @param $filter
     * @return ModelQueryBuilder
     */

    public function buildPropertiesFilterQuery($query, $filter)
    {
        if (in_array($filter['property'], ['tags', 'product'])) {
            $subField = $filter['property'] == 'tags' ? 'tag_id' : 'product_id';
            list($method, $subMethod) = static::parseRelationalFilterQueryMethods($filter);
            $query = static::buildRelationFilterQuery($filter['property'], $query, $method, $subMethod, $subField, $filter);
        } elseif ($filter['property'] == 'waiting_for_reply') {
            if (($filter['value'] == 'yes' && $filter['operator'] == '=') || ($filter['value'] == 'no' && $filter['operator'] == '!=')) {
                $query = $query->where(function ($q) {
                    $q->whereColumn('last_agent_response', '<', 'last_customer_response')
                        ->orWhereNull('last_agent_response')
                        ->orWhere('status', 'new');
                });
            } else {
                $query = $query->where(function ($q) {
                    $q->whereColumn('last_customer_response', '<', 'last_agent_response');
                });
            }
        } else {
            $method = $filter['operator'] == 'in' ? 'whereIn' : 'whereNotIn';
            $query = $query->{$method}($filter['property'], (array)$filter['value']);
        }
        return $query;
    }

    /**
     * method to search by properties
     * @param $provider
     * @param $query
     * @param $search
     * @param string $operator
     * @return ModelQueryBuilder
     */
    public function buildSearchableQuery($provider, $query, $search, $operator = 'LIKE')
    {
        switch ($provider) {
            case 'customer':
                $fields = (new Customer())->getSearchableFields();
                break;
            case 'agent':
                $fields = (new Agent())->getSearchableFields();
                break;
            default:
                $fields = $this->searchable;
                break;
        }

        $query->whereHas($provider, function ($query) use ($fields, $search, $operator) {
            $query->where(array_shift($fields), $operator, $search);

            $nameArray = explode(' ', $search);

            if (count($nameArray) >= 2) {
                $query->orWhere(function ($q) use ($nameArray, $operator) {
                    $firstName = array_shift($nameArray);
                    $lastName = implode(' ', $nameArray);

                    $q->where('first_name', $operator, $firstName);
                    $q->where('last_name', $operator, $lastName);
                });
            }

            foreach ($fields as $field) {
                $query->orWhere($field, $operator, $search);
            }
        });

        return $query;
    }

    /**
     * Filter by ticket general properties like customer name, agent name etc
     * @param $provider
     * @param $query
     * @param $filters
     * @return ModelQueryBuilder
     */
    public function filterTicketByUser($provider, $query, $filters)
    {
        foreach ($filters as $filter) {
            if ($filter['operator'] == 'in' || $filter['operator'] == 'not_in') {
                $method = $filter['operator'] == 'in' ? 'whereIn' : 'whereNotIn';
                $query = $query->whereHas($provider, function ($q) use ($method, $filter) {
                    $q->{$method}($filter['property'], $filter['value']);
                });
            }

            if ($filter['operator'] == 'contains' || $filter['operator'] == 'not_contains') {
                $operator = $filter['operator'] == 'contains' ? 'LIKE' : 'NOT LIKE';
                $query->whereHas($provider, function ($q) use ($operator, $filter) {
                    $q->where($filter['property'], $operator, '%' . $filter['value'] . '%');
                });
            }

            if ($filter['operator'] == '=' || $filter['operator'] == '!=') {
                $operator = $filter['operator'];
                $query->whereHas($provider, function ($q) use ($operator, $filter) {
                    $q->where($filter['property'], $operator, $filter['value']);
                });
            }
        }
        return $query;
    }

    /**
     * One2Many: Customer has to many Click Tickets
     * @return Model Collection
     */
    public function responses()
    {
        $class = __NAMESPACE__ . '\Conversation';

        return $this->hasMany(
            $class, 'ticket_id', 'id'
        )->with('person', 'attachments', 'ccinfo')
            ->latest('id');
    }

    public function preview_response()
    {
        $class = __NAMESPACE__ . '\Conversation';

        return $this->hasOne(
            $class, 'ticket_id', 'id'
        );
    }

    public function tags()
    {
        $class = __NAMESPACE__ . '\TicketTag';

        return $this->belongsToMany(
            $class, 'fs_tag_pivot', 'source_id', 'tag_id'
        )->wherePivot('source_type', 'ticket_tag');
    }

    public function watchers()
    {
        $class = __NAMESPACE__ . '\TagPivot';

        return $this->hasMany($class, 'source_id', 'id')
            ->where('source_type', 'ticket_watcher')
            ->select(['tag_id']);
    }

    /**
     * One2one: Customer has to many Click Tickets
     * @return Model Collection
     */
    public function customer()
    {
        $class = __NAMESPACE__ . '\Customer';

        return $this->belongsTo(
            $class, 'customer_id', 'id'
        );
    }

    /**
     * One2one: Customer has to many Click Tickets
     * @return Model Collection
     */
    public function agent()
    {
        $class = __NAMESPACE__ . '\Agent';

        return $this->belongsTo(
            $class, 'agent_id', 'id'
        );
    }

    public function closed_by_person()
    {
        $class = __NAMESPACE__ . '\Person';

        return $this->belongsTo(
            $class, 'closed_by', 'id'
        );
    }

    public function product()
    {
        $class = __NAMESPACE__ . '\Product';

        return $this->belongsTo(
            $class, 'product_id', 'id'
        );
    }

    public function mailbox()
    {
        $class = __NAMESPACE__ . '\MailBox';

        return $this->belongsTo(
            $class, 'mailbox_id', 'id'
        );
    }


    public function deleteTicket()
    {
        /*
         * Action on ticket deleting
         *
         * @since v1.0.0
         * @param object $ticket
         */
        do_action('fluent_support/deleting_ticket', $this);
        // Delete the ticket
        $this->delete();
    }

    public static function slugify($title)
    {
        $slug = sanitize_title($title, 'support-ticket-' . time(), 'display');
        if (Ticket::where('slug', $slug)->first()) {
            $slug .= '-' . time();
        }
        return $slug;
    }

    public function hasTag($tagId)
    {
        $tags = $this->tags;
        foreach ($tags as $tag) {
            if ($tag->id == $tagId) {
                return true;
            }
        }

        return false;
    }

    public function attachments()
    {
        $class = __NAMESPACE__ . '\Attachment';
        return $this->hasMany($class, 'ticket_id', 'id')->where('conversation_id', NULL);
    }

    public function customData($scope = 'admin', $rendered = false)
    {
        if (!defined('FLUENTSUPPORTPRO')) {
            return [];
        }

        $fields = \FluentSupportPro\App\Services\CustomFieldsService::getFieldLabels($scope);

        if (!$fields) {
            return [];
        }

        $keys = array_keys($fields);

        $customRows = Meta::where('object_type', 'ticket_meta')->where('object_id', $this->id)
            ->whereIn('key', $keys)
            ->get();

        if (!$customRows) {
            return [];
        }

        $formattedData = [];

        $customRenderers = \FluentSupportPro\App\Services\CustomFieldsService::getCustomerRenderers();

        foreach ($customRows as $row) {
            $dataKey = $row->key;

            $value = $row->value;

            $fieldType = $fields[$dataKey]['type'];

            if ($value) {
                if (in_array($fieldType, $customRenderers) && $rendered) {
                    $value = apply_filters('fluent_support/custom_field_render_' . $fieldType, $value, $scope);
                } else if ($fieldType == 'checkbox') {
                    $value = array_values(array_filter(explode('|', $value)));
                }

                if (!is_array($value) && !is_object($value)) {
                    $formattedData[$dataKey] = links_add_target(make_clickable($value));
                } else {
                    $formattedData[$dataKey] = $value;
                }
            }
        }

        return $formattedData;
    }

    /**
     * @param $data This is the data that will be saved to the ticket_meta for custom fields
     * @return bool
     */
    public function syncCustomFields($data)
    {
        if (!is_array($data)) {
            return false;
        }

        $fields = apply_filters('fluent_support/ticket_custom_fields', []);

        if (!$fields) {
            return false;
        }

        $keys = array_keys($fields);

        $validData = Arr::only($data, $keys);

        foreach ($validData as $dataKey => $validDatum) {
            if ($fields[$dataKey]['type'] == 'checkbox' || is_array($validDatum)) {
                $validDatum = implode('|', $validDatum);
                $validDatum = '|' . $validDatum . '|';
            }

            $exist = Meta::where('object_type', 'ticket_meta')->where('object_id', $this->id)
                ->where('key', $dataKey)
                ->first();

            if ($exist) {
                $exist->value = $validDatum;
                $exist->save();
            } else {
                Meta::insert([
                    'object_type' => 'ticket_meta',
                    'object_id'   => $this->id,
                    'key'         => $dataKey,
                    'value'       => $validDatum
                ]);
            }
        }

        // maybe delete data
        $deletedSlugs = array_diff($keys, array_keys($validData));

        if ($deletedSlugs) {
            Meta::where('object_type', 'ticket_meta')->where('object_id', $this->id)
                ->whereIn('key', $deletedSlugs)
                ->delete();
        }

        return true;
    }

    public function getLastAgentResponse()
    {
        $query = \FluentSupport\App\App::db()->table('fs_conversations')
            ->select(['fs_conversations.*'])
            ->where('fs_conversations.conversation_type', 'response')
            ->where('fs_conversations.ticket_id', $this->id)
            ->where('fs_persons.person_type', '=', 'agent')
            ->join('fs_persons', 'fs_persons.id', '=', 'fs_conversations.person_id')
            ->orderBy('fs_conversations.id', 'DESC');

        return $query->first();
    }

    public function getLastResponse()
    {
        return \FluentSupport\App\App::db()->table('fs_conversations')
            ->where('ticket_id', $this->id)
            ->where('conversation_type', 'response')
            ->latest('id')
            ->first();
    }

    /**
     * This method will assign tags to the ticket
     * @param $tagIds This is the array of tag ids that will be assigned to the ticket
     * @return \FluentSupport\App\Models\Ticket
     */
    public function applyTags($tagIds)
    {
        $result = false;

        if (!is_array($tagIds)) {
            $tagIds = array($tagIds);
        }

        foreach ($tagIds as $tagId) {
            if (!$this->hasTag($tagId)) {
                $this->tags()->attach($tagId, ['source_type' => 'ticket_tag']);
                $result = true;

                /*
                 * Action while tag added to ticket
                 *
                 * @since v1.0.0
                 * @param integer $tagId
                 * @param object  $ticket
                 */
                do_action('fluent_support/ticket_tag_added', $tagId, $this);
            }
        }
        return $result;
    }

    /**
     * This method will remove tags from ticket
     * @param $tagIds This is the array of tag ids that will be removed from the ticket
     * @return \FluentSupport\App\Models\Ticket
     */
    public function detachTags($tagIds)
    {
        $result = false;

        if (!is_array($tagIds)) {
            $tagIds = array($tagIds);
        }

        foreach ($tagIds as $tagId) {
            if ($this->hasTag($tagId)) {
                $this->tags()->detach($tagId);

                /*
                 * Action while tag removed from ticket
                 *
                 * @since v1.0.0
                 * @param integer $tagId
                 * @param object  $ticket
                 */
                do_action('fluent_support/ticket_tag_removed', $tagId, $this);
                $result = true;
            }
        }
        return $result;
    }

    /**
     * This `createTicket` method will create a new ticket and it will also create a new customer or
     * a customer with WP profile if given.
     * @param array $ticketData
     * @param array $maybeNewCustomer
     * @return $this | \WP_Error
     */

    public function createTicket($ticketData, $maybeNewCustomer = false)
    {
        $ticketData = $this->maybeCreateNewCustomer($ticketData, $maybeNewCustomer);

        if (!$ticketData) {
            return new \WP_Error('error', 'Ticket could not be created');
        }

        $customer = Customer::findOrFail($ticketData['customer_id']);
        $ticketData = $this->buildTicketData($ticketData, $customer);
        $disabledFields = apply_filters('fluent_support/disabled_ticket_fields', []);

        return $this->storeTicket($ticketData, $customer, $disabledFields);
    }

    // This is a supporting method for createTicket method
    // it will create ticket data array for ticket creation
    private function buildTicketData($ticketData, $customer)
    {
        if (empty($ticketData['mailbox_id'])) {
            $mailbox = Helper::getDefaultMailBox();
            $ticketData['mailbox_id'] = $mailbox->id;
        } else {
            $mailbox = MailBox::findOrFail($ticketData['mailbox_id']); // just for validation
        }

        if (!empty($ticketData['product_id'])) {
            $ticketData['product_source'] = 'local';
        }

        $ticketData['title'] = sanitize_text_field(wp_unslash($ticketData['title']));

        $ticketData['content'] = wp_specialchars_decode(wp_unslash(wp_kses_post($ticketData['content'])));

        if (!empty($ticketData['priority'])) {
            $ticketData['priority'] = sanitize_text_field($ticketData['priority']);
        }

        if (!empty($ticketData['client_priority'])) {
            $ticketData['client_priority'] = sanitize_text_field($ticketData['client_priority']);
        }

        /*
         * Filter ticket data
         *
         * @since v1.0.0
         * @param array  $ticketData
         * @param object $customer
         */
        $ticketData = apply_filters('fluent_support/create_ticket_data', $ticketData, $customer);

        return $ticketData;
    }

    // This is a supporting method for createTicket method
    // it will store the ticket and return the ticket object
    private function storeTicket($ticketData, $customer, $disabledFields)
    {
        /*
         * Action before ticket create
         *
         * @since v1.0.0
         * @param array  $ticketData
         * @param object $customer
         */
        do_action('fluent_support/before_ticket_create', $ticketData, $customer);

        $createdTicket = Ticket::create($ticketData);

        TicketService::addTicketAttachments($ticketData, $disabledFields, $createdTicket, $customer);

        if (defined('FLUENTSUPPORTPRO') && !empty($ticketData['custom_fields'])) {
            $createdTicket->syncCustomFields($ticketData['custom_fields']);
        }

        /*
         * Action on ticket create
         *
         * @since v1.0.0
         * @param object $createdTicket
         * @param object $customer
         */
        do_action('fluent_support/ticket_created', $createdTicket, $customer);
        do_action('fluent_support/ticket_created_behalf_of_customer', $createdTicket, $customer, Helper::getAgentByUserId());
        return $createdTicket;
    }

    // This is a supporting method for createTicket method
    // it will create a new customer or a customer with WP profile if given
    // and after creating a new user it will store customer_id
    // inside $ticketData array as we only need customer_id to create ticket
    private function maybeCreateNewCustomer($ticketData, $maybeNewCustomer)
    {
        if (!empty($ticketData['customer_id'])) {
            return $ticketData;
        }

        $createdUserId = false;

        if (Arr::get($ticketData, 'create_wp_user') == 'yes' && !empty($maybeNewCustomer['username'])) {
            // Check if username already in use, if not create a wp new user
            if (!username_exists($maybeNewCustomer['username'])) {
                $authController = new AuthController();
                $createdUserId = $authController->createUser($maybeNewCustomer);
                $authController->maybeUpdateUser($createdUserId, $maybeNewCustomer);
            }
        }

        $email = Arr::get($maybeNewCustomer, 'email');
        if (!$email || !is_email($email)) {
            return false;
        }

        $existingCustomer = Customer::where('email', $email)->first();

        if ($existingCustomer) {
            $ticketData['customer_id'] = $existingCustomer->id;
            return $ticketData;
        }

        // create the customer now
        $customerData = Arr::only($maybeNewCustomer, (new Customer())->getFillable());
        $customerData['user_id'] = $createdUserId;

        $customerData = array_filter($customerData);

        $createCustomer = Customer::create($customerData);

        if (!$createCustomer) {
            return false;
        }

        $ticketData['customer_id'] = $createCustomer->id;
        return $ticketData;
    }


    /**
     * This `getTicket` will load a ticket with all its data
     * @param array $ticketWith
     * @param bool $withCrmData
     * @param int $ticketId
     * @return array
     * @throws Exception
     */
    public function getTicket($ticketWith, $withCrmData, $ticketId)
    {
        $agent = Helper::getAgentByUserId();

        $ticket = self::with($ticketWith)->findOrFail($ticketId);

        $customFieldsKey = apply_filters('fluent_support/custom_registration_form_fields_key', Helper::getBusinessSettings('custom_registration_form_field'));
        $ticket->customer->custom_field_keys = $customFieldsKey;

        if ($ticket->customer->user_id) {
            $customFieldKeysUsingHook = apply_filters('fluent_support/custom_registration_form_fields_key', []);

            foreach ($customFieldKeysUsingHook as $key) {
                $userMeta = get_user_meta($ticket->customer->user_id,$key, true);
                if($userMeta) {
                    $ticket->customer->$key = $userMeta;
                }
            }
        }

        if (defined('FLUENTSUPPORTPRO_PLUGIN_VERSION') && Helper::isAgentFeedbackEnabled()) {
            foreach ($ticket->responses as $response) {
                $agentFeedback = Meta::where('object_id', $response->id)
                    ->where('object_type', 'conversation_meta')
                    ->where('key', 'agent_feedback_ratings')
                    ->first();

                if ($agentFeedback) {
                    $response->agent_feedback = $agentFeedback->value;
                }
            }
        }

        $ticket->customer->profile_edit_url = $this->getCustomerProfileUrl($ticket->customer); //Get and set customer profile url
        $this->checkAgentPermission($ticket); // Check Agent Permission
        $this->checkIfClosedTicket($ticket);  // Check if ticket is closed, if closed then load ticket with closed data

        return $this->getTicketAdditionalData($agent, $ticket->responses, $ticket, $withCrmData);
    }

    // This checkIfClosedTicket method will validate if ticket is closed, if closed then load ticket with closed data
    private function checkIfClosedTicket($ticket)
    {
        if ($ticket->status == 'closed') {
            $ticket->load('closed_by_person');
        }
    }

    // This getCustomerProfileUrl method will return customer profile url
    private function getCustomerProfileUrl($customer)
    {
        return $customer->getUserProfileEditUrl();
    }

    // This getTicketAdditionalData method will load ticket with additional data
    private function getTicketAdditionalData($agent, $responses, $ticket, $isCrmProfileRequested = false)
    {
        foreach ($responses as $response) {
            $response->content = links_add_target(make_clickable(wpautop($response->content, false)));
            if (!empty($response->ccinfo)) {
                $val = maybe_unserialize($response->ccinfo->value);
                if(isset($val['cc_email']) && !empty($val['cc_email'])){
                    $response->cc_info = $val['cc_email'];
                } else {
                    $response->cc_info = '';
                }
            } else {
                $response->cc_info = '';
            }
        }

        $ticket->content = links_add_target(make_clickable(wpautop($ticket->content, false)));

        //Get last activity by agent
        $ticket->live_activity = TicketHelper::getActivity($ticket->id, $agent->id);
        //Get all carbon copy customer
        $ccInfo = $ticket->getSettingsValue('cc_email', []);

        $ticket->carbon_copy = !empty($ccInfo) ? implode(', ', $ccInfo) : '';

        if (defined('FLUENTSUPPORTPRO')) {
            $ticket->custom_fields = $ticket->customData('admin', true);
        }

        $data = [
            'ticket'    => $ticket,
            'responses' => $responses,
            'agent_id'  => $agent->id
        ];

        if (defined('FLUENTSUPPORTPRO') && $ticket->watchers) {
            $data['watchers'] = TicketHelper::getWatchers($ticket->watchers);
        }

        if (defined('FLUENTCRM') && $isCrmProfileRequested) {
            $data['fluentcrm_profile'] = Helper::getFluentCrmContactData($ticket->customer);
        }

        return $data;

    }


    /**
     * This `createResponse` will create a response for a ticket
     * @param array $data
     * @param int $ticketId
     * @return array
     * @throws Exception
     */
    public function createResponse($data, $ticketId)
    {
        $agent = Helper::getAgentByUserId(get_current_user_id());
        $this->checkIfValidAgent($agent);

        $ticket = static::findOrFail($ticketId);
        $this->checkAgentPermission($ticket);

        $responseData = (new ResponseService())->createResponse($data, $agent, $ticket);

        $responseData['response']->content = wp_specialchars_decode(wpautop($responseData['response']->content, false));

        return [
            'message'     => __('Response has been added', 'fluent-support'),
            'response'    => $responseData['response'],
            'ticket'      => $responseData['ticket'],
            'update_data' => $responseData['update_data']
        ];
    }

    public function addOrUpdatDraft($data, $ticketId)
    {
        $agent = Helper::getAgentByUserId(get_current_user_id());
        $this->checkIfValidAgent($agent);

        $ticket = static::findOrFail($ticketId);
        $this->checkAgentPermission($ticket);
        $key = 'ticket_no_' . $ticketId . '_agent_id_' . $agent->id . '_response_draft';

        $previousDraft = Meta::where('key', $key)->first();

        if ($data['draftID'] || $previousDraft) {
            return $this->updateDraft($key, $data['draftID'], $data);
        }

        $draftID = Meta::insertGetId([
            'object_type' => '_fs_auto_draft',
            'object_id'   => $ticketId,
            'key'         => $key,
            'value'       => maybe_serialize($data)
        ]);

        return [
            'message' => __('Draft has been added', 'fluent-support'),
            'draftID' => $draftID
        ];

    }

    public function updateDraft($key, $draftID, $data)
    {
        Meta::where('key', $key)->update([
            'value' => maybe_serialize($data)
        ]);
        return [
            'message' => __('Draft has been updated', 'fluent-support'),
            'draftID' => $draftID
        ];
    }

    public function fetchDraft($ticketId)
    {
        $agent = Helper::getAgentByUserId(get_current_user_id());
        $this->checkIfValidAgent($agent);

        $ticket = static::findOrFail($ticketId);
        $this->checkAgentPermission($ticket);
        $key = 'ticket_no_' . $ticketId . '_agent_id_' . $agent->id . '_response_draft';

        $draft = Meta::where([
            'object_type' => '_fs_auto_draft',
            'key'         => $key,
        ])->first();

        if ($draft) {
            $draft->value = maybe_unserialize($draft->value);
        }

        return [
            'draft' => $draft
        ];
    }

    public function removeDraft($draftID)
    {
        Meta::where('id', $draftID)->delete();

        return [
            'message' => __('Discard draft successfully', 'fluent-support'),
        ];
    }

    // This checkIfValidAgent method will check if agent is valid or not
    private function checkIfValidAgent($agent)
    {
        if (!$agent) {
            throw new \Exception('Sorry, You do not have permission. Please add yourself as support agent first');
        } else {
            return true;
        }
    }

    /**
     * This `closeTicket` will close a ticket by ticket id
     * @param int $ticketId
     * @return array
     * @throws Exception
     */
    public function closeTicket($ticketId, $closeSilently = false)
    {
        $agent = Helper::getAgentByUserId(get_current_user_id());

        $ticket = static::findOrFail($ticketId);
        $this->checkAgentPermission($ticket);

        return [
            'message' => __('Ticket has been closed', 'fluent_support'),
            'ticket'  => (new TicketService())->close($ticket, $agent, '', $closeSilently)
        ];
    }

    /**
     * This `reopenTicket` will reopen a ticket by ticket id
     * @param int $ticketId
     * @return array
     * @throws Exception
     */
    public function reOpenTicket($ticketId)
    {
        $agent = Helper::getAgentByUserId(get_current_user_id());

        $ticket = static::findOrFail($ticketId);
        $this->checkAgentPermission($ticket);


        return [
            'message' => __('Ticket has been opened again', 'fluent_support'),
            'ticket'  => (new TicketService())->reopen($ticket, $agent)
        ];
    }


    /**
     * This `getTicketWidgets` will load all ticket widgets
     * @param int $ticketId
     * @return array
     * @throws Exception
     */
    public function getTicketWidgets($ticketId)
    {
        $ticket = static::with('customer')->findOrFail($ticketId);
        $this->checkAgentPermission($ticket);

        //Get last 10 tickets of this customer except this
        /*
         * Filter ticket limit to show ticket in view ticket page sidebar
         * @since 1.5.6
         * @param int $limit
         */
        $limit = apply_filters('fluent_support/previous_ticket_widgets_limit', 10);

        $otherTickets = static::where('id', '!=', $ticketId)
            ->select(['id', 'title', 'status', 'created_at'])
            ->where('customer_id', $ticket->customer_id)
            ->latest('id')
            ->limit($limit)
            ->get();

        return [
            'other_tickets' => $otherTickets,
            'extra_widgets' => ProfileInfoService::getProfileExtraWidgets($ticket->customer)
        ];
    }

    /**
     * This `updateTicketProperty` will update tickets properties
     * @param string $propName
     * @param mixed $propValue
     * @param int $ticketId
     * @return array
     * @throws Exception
     */
    public function updateTicketProperty($propName, $propValue, $ticketId)
    {
        $assigner = Helper::getAgentByUserId(get_current_user_id());
        $ticket = static::findOrFail($ticketId);

        $this->checkAgentPermission($ticket);

        return [
            'message'     => __(str_replace('_', ' ', ucwords($propName)) . ' has been updated', 'fluent-support'),
            'update_data' => $this->handlePropertyUpdate($propName, $propValue, $ticket, $assigner)
        ];
    }

    // This will handle ticket property update
    private function handlePropertyUpdate($propName, $propValue, $ticket, $assigner)
    {
        $prevValue = $ticket->{$propName};

        if ($propName == 'agent_id' && !PermissionManager::currentUserCan('fst_assign_agents') ) {
            throw new \Exception('You do not have permission to assign agent', 400);
        }

        if ($propName && $propValue && $prevValue != $propValue) {
            $ticket->{$propName} = $propValue;
            $ticket->save();
        }

        $updateData = [];

        if ($propName == 'product_id') {
            $ticket->load('product');
            $updateData['product'] = $ticket->product;
        } else if ($propName == 'agent_id') {
            $ticket->load('agent');
            $updateData['agent'] = $ticket->agent;
            $updateData['assigner'] = (new TicketService())->onAgentChange($ticket, $assigner);
            if ($prevValue != $ticket->{$propName}) {
                do_action('fluent_support/agent_assigned_to_ticket', $ticket->agent, $ticket, $assigner);
            }
        }

        return $updateData;
    }

    /**
     * This `handleBulkActions` will handle bulk actions
     * @param string $action
     * @param array $ticketIds
     * @return array
     * @throws Exception
     */
    public function handleBulkActions($action, $ticketIds)
    {
        $hasAllPermission = PermissionManager::currentUserCan('fst_manage_other_tickets');
        $agent = Helper::getAgentByUserId();
        $query = Ticket::whereIn('id', $ticketIds);

        if (!$hasAllPermission) {
            //Filter ticket by agent_id
            $query->where('agent_id', $agent->id);
        }

        return $this->handleAction($action, $query, $agent);
    }

    // This will handle ticket bulk action
    private function handleAction($action, $query, $agent)
    {
        if ($action == 'close_tickets') {
            return $this->bulkCloseTickets($query->get(), $agent);
        } else if ($action == 'delete_tickets') {
            return (new TicketService)->deleteTickets($query->get());
        } else if ($action == 'assign_agent') {
            return $this->bulkAssignAgent($query);
        } else if ($action == 'assign_tags') {
            return $this->bulkAssignTag($query->get());
        } else {
            throw new \Exception('Sorry no action found as available');
        }
    }

    /**
     * This `bulkCloseTickets` will close all given or selected tickets
     * @param object $tickets
     * @param object $agent
     * @return array
     */
    public function bulkCloseTickets($tickets, $agent)
    {
        $tickets->each(function ($ticket) use ($agent) {
            (new TicketService())->close($ticket, $agent);
        });

        return [
            'message' => sprintf(__('%d tickets have been closed', 'fluent-support'), count($tickets))
        ];
    }

    /**
     * This `bulkAssignAgent` will assign all given or selected tickets to given agent
     * @param object $tickets
     * @return array
     */
    public function bulkAssignAgent($query)
    {
        $request = \FluentSupport\App\App::getInstance('request');

        if (!$request->has('agent_id')) {
            throw new \Exception('agent_id param is required');
        }

        $agent = Agent::findOrFail($request->get('agent_id'));

        $query->where(function ($q) use ($agent) {
            $q->where('agent_id', '!=', $agent->id)
                ->orWhereNull('agent_id');
        });

        $tickets = $query->get();

        $tickets->each(function ($ticket) use ($agent) {
            $assigner = Helper::getCurrentAgent();
            $ticket->agent_id = $agent->id;
            $ticket->save();
            do_action('fluent_support/agent_assigned_to_ticket', $agent, $ticket, $assigner);
        });

        return [
            'message' => __(count($tickets) . ' tickets has been assigned to', 'fluent-support') . ' ' . $agent->full_name
        ];
    }

    /**
     * This `bulkAssignTag` will assign all given or selected tickets to given tag
     * @param object $tickets
     * @return array
     */
    public function bulkAssignTag($query)
    {
        $request = \FluentSupport\App\App::getInstance('request');

        if (!$request->has('tag_ids')) {
            throw new \Exception('tag_ids param is required');
        }

        $tags = array_filter(array_map('absint', $request->get('tag_ids', [])));

        $query->each(function ($ticket) use ($tags) {
            $ticket->applyTags($tags);
        });

        return [
            'message' => __('Selected tags has been added to tickets', 'fluent-support')
        ];
    }

    // This checkAgentPermission method will validate if agent has permission to view ticket
    private function checkAgentPermission($ticket)
    {
        if (!PermissionManager::hasTicketPermission($ticket)) {
            throw new \Exception('Sorry, You do not have permission to this ticket');
        }
    }

    public static function countTicketByMailBoxId($mailbox_id)
    {
        return self::where('mailbox_id', $mailbox_id)->count();
    }

    public static function syncMailBoxId($mailbox_id, $fallback_id)
    {
        return self::where('mailbox_id', $mailbox_id)
            ->update([
                'mailbox_id' => $fallback_id
            ]);
    }

    public static function getTicketsQuery()
    {
        return self::with([
            'customer'         => function ($query) {
                $query->select(['first_name', 'last_name', 'email', 'id', 'avatar']);
            }, 'agent'         => function ($query) {
                $query->select(['first_name', 'last_name', 'id']);
            },
            'product',
            'tags',
            'preview_response' => function ($query) {
                $query->latest('id');
            }
        ]);
    }

    public function getSettingsValue($valueKey = false, $default = false)
    {
        $exist = Meta::where('object_type', 'ticket')
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
        $exist = Meta::where('object_type', 'ticket')
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
            'object_type' => 'ticket',
            'key'         => 'settings',
            'object_id'   => $this->id,
            'value'       => maybe_serialize([
                $valueKey => $value
            ])
        ];

        Meta::create($settings);

        return $this;

    }

    // accessor
//    public function getCreatedAtAttribute($date)
//    {
//        return date('Y-m-d H:i:s', strtotime($date));
//    }
}

