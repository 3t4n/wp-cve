<?php

namespace FluentSupport\App\Models\Traits;

use Exception;
use FluentSupport\App\Models\Ticket;
use FluentSupport\App\Services\Helper;
use FluentSupport\Framework\Support\Arr;
use FluentSupport\App\Services\ProfileInfoService;

trait CustomerTrait
{

    /**
     * This getCustomers method will return all customers
     * @param string $search
     * @param string $status
     * @return object
     */
    public function getCustomers($search, $status=false)
    {
        $customersQuery = static::latest();

        if ( $search ) {
            $customersQuery = $customersQuery->searchBy($search);
        }

        if ($status && $status != 'all') {
            $customersQuery->filterByStatues([$status]);
        }

        $customers = $customersQuery->paginate();

        if ( defined( 'FLUENTCRM' ) && $customers->isEmpty() && !$status ) {
            $customers = $this->findInCrmAndMakeCustomer ( $search );
            ! is_null( $customers ) ? $customers : $customers = $customersQuery->paginate();
        }

        if ( defined('FLUENT_SUPPORT_PRO_DIR_FILE') && $customers->isEmpty() && !$status ) {
            $customers = $this->findUserInWPAndMakeCustomer( $search );
            ! is_null( $customers ) ? $customers : $customers = $customersQuery->paginate();
        }

        return $this->attachCustomersMetaData( $customers );
    }

    public function getCustomerField($customer_id,$userID = null)
    {
        $basicFields = [
            'first_name' => [
                'label' => __('First Name', 'fluent-support'),
                'data_type' => 'text',
                'placeholder' => __('First Name', 'fluent-support'),
                'type' => 'input-text',
                'wrapper_class' => 'fs_half_field',
            ],
            'last_name' => [
                'label' => __('Last Name', 'fluent-support'),
                'data_type' => 'text',
                'placeholder' => __('Last Name', 'fluent-support'),
                'type' => 'input-text',
                'wrapper_class' => 'fs_half_field',
            ],
            'email' => [
                'label' => __('Email', 'fluent-support'),
                'data_type' => 'email',
                'placeholder' => __('Email', 'fluent-support'),
                'type' => 'input-text',
                'wrapper_class' => 'fs_half_field',
                'disabled' => !empty($customer_id),
            ],
            'title' => [
                'label' => __('Job Title', 'fluent-support'),
                'data_type' => 'text',
                'placeholder' => __('Job Title', 'fluent-support'),
                'type' => 'input-text',
                'wrapper_class' => 'fs_half_field',
            ],
            'note' => [
                'label' => __('Note', 'fluent-support'),
                'data_type' => 'textarea',
                'placeholder' => __('Note', 'fluent-support'),
                'type' => 'input-text',
                'wrapper_class' => 'fs_half_field',
            ],
            'status' => [
                'label' => __('Status', 'fluent-support'),
                'data_type' => 'text',
                'type' => 'input-radio',
                'options' => [
                    'active' => ['id' => 'active', 'value' => 'active', 'label' => __('Active', 'fluent-support')],
                    'inactive' => ['id' => 'inactive', 'value' => 'inactive', 'label' => __('Blocked', 'fluent-support')],
                ],
                'wrapper_class' => 'fs_half_field',
            ],
            'status_html' => [
                'dependency' => [
                    'depends_on' => 'status',
                    'operator' => '=',
                    'value' => 'inactive',
                ],
                'type' => 'html-viewer',
                'wrapper_class' => 'fs_warn',
                'html' => __('block_instruction', 'fluent-support'),
            ],
        ];

        $addressFields = [
            'address_line_1' => [
                'label' => __('Address Line 1', 'fluent-support'),
                'data_type' => 'text',
                'placeholder' => __('Address Line 1', 'fluent-support'),
                'type' => 'input-text',
                'wrapper_class' => 'fs_half_field',
            ],
            'address_line_2' => [
                'label' => __('Address Line 2', 'fluent-support'),
                'data_type' => 'text',
                'placeholder' => __('Address Line 2', 'fluent-support'),
                'type' => 'input-text',
                'wrapper_class' => 'fs_half_field',
            ],
            'city' => [
                'label' => __('City', 'fluent-support'),
                'data_type' => 'text',
                'placeholder' => __('City', 'fluent-support'),
                'type' => 'input-text',
                'wrapper_class' => 'fs_half_field',
            ],
            'state' => [
                'label' => __('State', 'fluent-support'),
                'data_type' => 'text',
                'placeholder' => __('State', 'fluent-support'),
                'type' => 'input-text',
                'wrapper_class' => 'fs_half_field',
            ],
            'zip' => [
                'label' => __('Zip Code', 'fluent-support'),
                'data_type' => 'text',
                'placeholder' => __('Zip Code', 'fluent-support'),
                'type' => 'input-text',
                'wrapper_class' => 'fs_half_field',
            ],
            'country' => [
                'label' => __('Country', 'fluent-support'),
                'placeholder' => __('Country', 'fluent-support'),
                'type' => 'country-selector',
                'wrapper_class' => 'fs_half_field',
            ],
        ];

        if ($userID) {
            $addressFields =  apply_filters('fluent_support/custom_registration_form_fields',$addressFields);

            foreach ($addressFields as $key => $field) {
                //To create a custom field using _formBuilder.vue, the 'type' should be 'input-text', and the 'data_type' may vary, such as (text, number, date...).
                $addressFields[$key]['data_type'] = $addressFields[$key]['type'];
                $addressFields[$key]['type'] = 'input-text';
            }
        }

        $loading = false;

        $state = [
            'basic_fields' => $basicFields,
            'address_fields' => $addressFields,
            'loading' => $loading,
        ];

        return $state;

    }

    /**
     * This `findUserInWPAndMakeCustomer` method will find customer in WP Users and make it as a customer
     * @param string $search
     * @return object
     */
    public function findUserInWPAndMakeCustomer ( $search )
    {
        if ( is_email( $search ) ){
            $user = get_user_by( 'email', $search );

            if( $user ) {
                unset( $user->user_pass );
                $customerData = [
                    'user_id' => $user->ID,
                    'email' => $user->user_email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name
                ];
                $customer = static::maybeCreateCustomer( $customerData );
                return static::where('email', $user->user_email)->paginate();
            }
        }
    }

    /**
     * This `findInCrmAndMakeCustomer` method will find customer in Fluent CRM and make it as a customer
     * @param string $search
     * @return object
     */
    public function findInCrmAndMakeCustomer ( $search )
    {
        $customer = \FluentCrm\App\Models\Subscriber::where( 'email', $search )->first();
        if( $customer ) {
            $customerData = [
                'email' => $customer->email,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name
            ];
            $customer = static::maybeCreateCustomer( $customerData );
            return static::where('email', $customer->email)->paginate();
        }
    }

    /**
     * This getCustomer method will return a single customer
     * @param int $id
     * @param array $with
     * @return object
     */

    public function getCustomer($id, $with)
    {
        $customer = static::find($id);

        $data = [
            'customer' => $customer
        ];

        if (!$with) {
            return $data;
        }

        return $this->getCustomerAdditionalData($with, $customer, $data);
    }

    /**
     * This createCustomer method will create a new customer
     * @param array $data
     * @return object
     */

    public function createCustomer($data)
    {
        $data = Arr::only($data, $this->getFillable());

        $user = get_user_by('email', $data['email']);

        if ($user) {
            $data['user_id'] = $user->ID;
            if (empty($data['first_name'])) {
                $data['first_name'] = $user->first_name;
            }
            if (empty($data['last_name'])) {
                $data['last_name'] = $user->last_name;
            }
        }

        return static::create($data);
    }

    /**
     * This updateCustomer method will update a customer
     * @since 1.5.7
     * @param int $customerId
     * @param array $data
     * @return object
     */

    public function updateCustomer($customerId, $data)
    {
        $customFieldsKeys = apply_filters('fluent_support/custom_registration_form_fields_key', Helper::getBusinessSettings('custom_registration_form_field'));
        $customFormValue = Arr::only($data, $customFieldsKeys);

        $data = $this->takeValidKeysForUpdate($data);

        $customer = static::findOrFail($customerId);

        if($this->customerExists($customerId, $data['email']))
        {
            throw new \Exception('Another Customer has same email address');
        }

        $user = get_user_by('email', $data['email']);

        if ($user) {
            $data['user_id'] = $user->ID;

            //Update Custom field data for user
            foreach ($customFieldsKeys as $key) {
                if (isset($customFormValue[$key])) {
                    $fieldValue = $customFormValue[$key];
                    update_user_meta($user->ID, $key, $fieldValue);
                }
            }
        }

        static::where('id', $customer->id)->update($data);

        return static::findOrFail($customerId);
    }

    /**
     * deleteCustomer method will delete a customer and all tickets by that customer
     * @since 1.5.7
     * @param int $customerId
     * @return array
     */

    public function deleteCustomer($customerId)
    {
        $customer = static::findOrFail($customerId);

        $tickets = Ticket::where('customer_id', $customer->id)->get();

        foreach ($tickets as $ticket) {
            $ticket->deleteTicket();
        }

        $customer->delete();

        return [
            'message' => __('Customer Deleted Successfully', 'fluent-support')
        ];
    }

    /**
     * This attachCustomersMetaData method will attach meta data to customers
     * @since 1.5.7
     * @param object $customers
     * @return object
     */
    private function attachCustomersMetaData($customers)
    {
        foreach ($customers as $customer) {
            $customer->total_tickets = $customer->getTicketCounts();
            $customer->total_responses = $customer->getResponseCounts();
            if ($customer->user_id) {
                //Get profile link, if they are WP user
                $customer->user_profile = admin_url('user-edit.php?user_id=' . $customer->user_id);
            }
        }

        return $customers;
    }

    /**
     * This getCustomerAdditionalData method will return additional data for a customer
     * @since 1.5.7
     * @param array $with
     * @param object $customer
     * @param array $data
     * @return array
     */

    private function getCustomerAdditionalData($with, $customer, $data)
    {
        $customFieldKeys = apply_filters('fluent_support/custom_registration_form_fields_key', []);

        $userMetaData = get_user_meta($customer['user_id']);

        //Custom field data from wp user_meta
        foreach ($customFieldKeys as $fieldKey) {
            if (isset($userMetaData[$fieldKey][0])) {
                $customer[$fieldKey] = $userMetaData[$fieldKey][0];
            }
        }

        if (in_array('widgets', $with)) {
            $data['widgets'] = ProfileInfoService::getProfileExtraWidgets($customer);
        }

        if (in_array('tickets', $with)) {
            /*
             * Filter ticket limit to show ticket in customer page sidebar
             * @since 1.5.6
             * @param int $limit
             */
            $limit = apply_filters('fluent_support/customer_page_ticket_widgets_limit', 20);

            $data['tickets'] = Ticket::select(['id', 'title', 'status', 'customer_id', 'created_at'])
                ->where('customer_id', $customer->id)
                ->latest('id')
                ->limit($limit)
                ->get();
        }

        if(in_array('fluentcrm_profile', $with)) {
            $data['fluentcrm_profile'] = Helper::getFluentCrmContactData($customer);
        }

        return $data;
    }

    /**
     * This customerExists method will check if customer exists
     * @since 1.5.7
     * @param int $customerId
     * @param string $email
     * @return mixed
     */

    private function customerExists($customerId, $email)
    {
        $customer = static::where('id','!=', $customerId)->where('email', $email)->first();
        if ($customer) {
            return $customer;
        }
        return false;
    }


    /**
     * This takeValidKeysForUpdate method will take valid keys data for update
     * @since 1.5.7
     * @param array $data
     * @return array
     */
    private function takeValidKeysForUpdate($data)
    {
        $validKeys = $this->getFillable();
        unset($validKeys['hash']);
        unset($validKeys['user_id']);

        return Arr::only($data, $validKeys);
    }
}
