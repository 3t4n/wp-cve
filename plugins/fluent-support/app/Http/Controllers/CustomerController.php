<?php

namespace FluentSupport\App\Http\Controllers;

use FluentCrm\App\Models\Subscriber;
use FluentSupport\App\Models\Customer;
use FluentSupport\Framework\Request\Request;
use FluentSupport\App\Services\AvatarUploder;

/**
 * CustomerController class for REST API
 * This class is responsible for getting data for all request related to customer
 * @package FluentSupport\App\Http\Controllers
 *
 * @version 1.0.0
 */
class CustomerController extends Controller
{
    /**
     * index method will return the list of customers
     * @param Request $request
     * @param Customer $customer
     * @return array
     */
    public function index(Request $request, Customer $customer)
    {
        return [
            'customers' => $customer->getCustomers($request->getSafe('search', 'sanitize_text_field'), $request->getSafe('status', 'sanitize_text_field')),
        ];
    }

    public function customerField (Request $request,Customer $customer, $customer_id) {

        $userID = intval($request->get('user_id'));
        return[
            'customerField' => $customer->getCustomerField($customer_id,$userID)
        ];
    }


    /**
     * getCustomer method will return individual customer information by customer id
     * This function will also get information about extra widgets, tickets and Fluent CRM
     * @param Request $request
     * @param Customer $customer
     * @param $customer_id
     * @return array
     */
    public function getCustomer(Request $request, Customer $customer, $customer_id)
    {
        return $customer->getCustomer($customer_id, $request->getSafe('with',null,[]));
    }

    /**
     * Create method will create new customer
     * @param Request $request
     * @param Customer $customer
     * @return array
     * @throws \FluentSupport\Framework\Validator\ValidationException
     */
    public function create(Request $request, Customer $customer)
    {
        $this->validate($request->get(), [
            'email' => 'required|email|unique:fs_persons'
        ]);

        return [
            'message'  => __('Customer has been added', 'fluent-support'),
            'customer' => $customer->createCustomer($request->get())
        ];
    }

    /**
     * update method will update existing customer by customer id
     * @param Request $request
     * @param Customer $customer
     * @param $customerId
     * @return array
     * @throws \FluentSupport\Framework\Validator\ValidationException
     */
    public function update(Request $request, Customer $customer, $customer_id)
    {
        $data = $this->validate($request->get(), [
            'email'      => 'required|email',
            'first_name' => 'required'
        ]);

        try {
            return [
                'message'  => __('Customer has been updated', 'fluent-support'),
                'customer' => $customer->updateCustomer($customer_id, $data)
            ];
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => __($e->getMessage(), 'fluent-support'),
                'errors'  => [
                    'email' => [
                        'unique' => __('Email address has been assigned to other customer', 'fluent-support'),
                    ]
                ]
            ], 423);
        }
    }

    /**
     * delete method will delete a customer and all tickets by that customer
     * @param Request $request
     * @param Customer $customer
     * @param int $customerId
     * @return array
     */
    public function delete(Request $request, Customer $customer, $customer_id)
    {
        return $customer->deleteCustomer($customer_id);
    }

    /**
     * addOrUpdateProfileImage method will update a customer avatar
     * For a successful upload it's required to send file object, customer id and the user type(customer)
     * @param Request $request
     * @return array
     */
    public function addOrUpdateProfileImage(Request $request, AvatarUploder $avatarUploder)
    {
        try {
            return $avatarUploder->addOrUpdateProfileImage($request->files(), $request->getSafe('customer_id', 'intval'), 'customer');
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => __($e->getMessage(), 'fluent-support'),
            ],
            $e->getCode()
        );
        }
    }

    /**
     * resetAvatar method will restore a customer avatar
     * For a successful upload it's required to send file object, customer id and the user type(customer)
     * @param Request $request
     * @param $id
     * @return array
     */
    public function resetAvatar(Customer $customer, $customer_id)
    {
        try {
            $customer->restoreAvatar($customer, $customer_id);

            return [
                'message' => __('Customer avatar reset to gravatar default', 'fluent-support'),
            ];
        } catch (\Exception $e) {
            return [
                'message' => __($e->getMessage(), 'fluent-support')
            ];
        }
    }

    public function searchContact(Request $request)
    {
        $search = $request->getSafe('search', 'sanitize_text_field');
        if (!$search) {
            return $this->sendError([
                'message' => 'Please provide search string'
            ]);
        }

        $isEmail = is_email($search);

        // search the existing customers first
        if ($isEmail) {
            $customers = Customer::select(['first_name', 'last_name', 'email', 'id', 'user_id'])
                ->where('email', $search)
                ->get();
        } else {
            $customers = Customer::select(['first_name', 'last_name', 'email', 'id', 'user_id'])
                ->searchBy($search)
                ->limit(10)
                ->get();
        }

        if (!$customers->isEmpty()) {
            return [
                'type'     => 'search_result',
                'provider' => 'fluent_support',
                'data'     => $customers,
                'is_email' => $isEmail,
                'search' => $search
            ];
        }

        // If FluentCRM exist then let's search for
        if (defined('FLUENTCRM')) {

            if ($isEmail) {
                $contacts = \FluentCrm\App\Models\Subscriber::where('email', $search)
                    ->select(['first_name', 'last_name', 'email', 'id', 'user_id'])
                    ->get();
            } else {

                $contacts = \FluentCrm\App\Models\Subscriber::searchBy($search)
                     ->select(['first_name', 'last_name', 'email', 'id', 'user_id'])
                    ->limit(10)
                    ->get();
            }

            if (!$contacts->isEmpty()) {
                return [
                    'type'     => 'search_result',
                    'provider' => 'fluent_crm',
                    'data'     => $contacts,
                    'is_email' => $isEmail
                ];
            }
        }

        // let's search from user's database
        $user_query = new \WP_User_Query(array('search' => $search, 'number' => 10));

        $users = $user_query->get_results();

        if ($users) {
            $formattedUsers = [];

            foreach ($users as $user) {
                $formattedUsers[] = [
                    'id'         => $user->ID,
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'user_id'    => $user->ID,
                    'email'      => $user->user_email
                ];
            }

            return [
                'type'     => 'search_result',
                'provider' => 'wp_users',
                'data'     => $formattedUsers,
                'is_email' => $isEmail
            ];
        }

        return [
            'type'     => 'none',
            'provider' => 'none',
            'data'     => [],
            'is_email' => $isEmail
        ];

    }
}
