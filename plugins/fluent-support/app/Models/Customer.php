<?php

namespace FluentSupport\App\Models;

use FluentSupport\Framework\Database\Orm\Builder;
use FluentSupport\Framework\Support\Arr;
use FluentSupport\App\Models\Traits\CustomerTrait;

class Customer extends Person
{
    use CustomerTrait;

    protected static $type = 'customer';

    protected $searchable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'address_line_1',
        'address_line_2',
        'country'
    ];

    /**
     * @return array
     */
    public function getSearchableFields()
    {
        return $this->searchable;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->person_type = static::$type;
            $model->hash = md5(time() . wp_generate_uuid4());
        });

        static::addGlobalScope(function (Builder $builder) {
            $builder->where('person_type', 'customer');
        });

    }

    public static function mappables()
    {
        return [
            'title'          => __('Customer Title', 'fluent-support'),
            'address_line_1' => __('Address Line 1', 'fluent-support'),
            'address_line_2' => __('Address Line 2', 'fluent-support'),
            'city'           => __('City', 'fluent-support'),
            'state'          => __('State', 'fluent-support'),
            'zip'            => __('Zip Code', 'fluent-support'),
            'country'        => __('Country', 'fluent-support'),
        ];
    }

    /**
     * maybeCreateCustomer method will update existing customer or create new
     * This method will get request to create customer, this will check existence, if exist it will update otherwise it will create new.
     * @param $customerData
     * @return false|Customer
     */
    public static function maybeCreateCustomer($customerData)
    {
        $customer = self::getCustomerFromData($customerData);
        $user = get_user_by('email', $customerData['email']);
        if ($user) {
            if ($user->first_name) {
                $customerData['first_name'] = $user->first_name;
            }
            if ($user->last_name) {
                $customerData['last_name'] = $user->last_name;
            }
            if (empty($customerData['first_name']) && empty($customerData['last_name'])) {
                $customerData['first_name'] = $user->display_name;
            }
            $customerData['user_id'] = $user->ID;
        }

        if (!$customer) {
            if (!empty($customerData['last_ip_address'])) {
                $customerData['ip_address'] = $customerData['last_ip_address'];
            }

            $customerData = self::explodeFullName($customerData);

            // we have to create customer
            $customer = self::create($customerData);

            /*
             * Action on customer create
             *
             * @since v1.0.0
             * @param object $customer
             */
            do_action('fluent_support/customer_created', $customer);

        } else {
            if (!empty($customerData['user_id']) || !empty($customerData['remote_uid'])) {
                $customerData = array_filter($customerData);
                $customer->fill($customerData);
                $customer->save();
            }
        }

        return $customer;
    }


    /**
     * One2Many: Customer has to many Click Tickets
     * @return Model Collection
     */
    public function tickets()
    {
        $foreign_key = self::$type . '_id';

        $class = __NAMESPACE__ . '\Ticket';

        return $this->hasMany(
            $class, $foreign_key, 'id'
        );
    }

    /**
     * getCustomerFromData method will return customer information by user id or email address
     * @param $customerData
     * @return false
     */
    public static function getCustomerFromData($customerData)
    {
        $remoteUid = Arr::get($customerData, 'remote_uid');
        $email = $customerData['email'];

        $customer = false;
        if ($remoteUid) {
            $customer = self::where('remote_uid', $remoteUid)->first();
        }

        if (!$customer) {
            if (!empty($customerData['user_id'])) {
                $customer = self::where('user_id', $customerData['user_id'])->first();
            }
            if (!$customer) {
                $customer = self::where('email', $email)->first();
            }
        }

        return $customer;
    }

    /**
     * getTicketCounts method will return the number of tickets by a customer
     * @return mixed
     */
    public function getTicketCounts()
    {
        return Ticket::where('customer_id', $this->id)->count();
    }

    /**
     * getResponseCounts will return the number of responses by a customer
     * @return mixed
     */
    public function getResponseCounts()
    {
        return Conversation::where('person_id', $this->id)->count();
    }
}
