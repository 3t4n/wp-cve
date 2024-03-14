<?php

namespace FluentSupport\App\Api\Classes;

use FluentSupport\App\Http\Controllers\AuthController;
use FluentSupport\App\Models\Customer;
use FluentSupport\App\Models\Ticket;

/**
 *  Customers class for PHP API
 *
 * Example Usage: $customersApi = FluentSupportApi('customers');
 *
 * @package FluentSupport\App\Api\Classes
 *
 * @version 1.0.0
 */
class Customers
{
    private $instance = null;

    private $allowedInstanceMethods = [
        'all',
        'get',
        'find',
        'first',
        'paginate'
    ];

    public function __construct(Customer $instance)
    {
        $this->instance = $instance;
    }

    /**
     * getCustomers method will return a all available customers
     *
     * @return object
     */

    public function getCustomers()
    {
        return Customer::paginate();
    }

    /**
     * getCustomer method will return a specific customer by id
     *
     * @param int $customerId
     * @return object|boolean
     */

    public function getCustomer(int $customerId)
    {
        if (is_numeric($customerId)) {
            return Customer::findOrFail($customerId);
        }
        return false;
    }

    /**
     * updateCustomer method will update the specific customer by id
     *
     * @param array $data
     * @param int $customer_id
     * @return object|boolean
     */

    public function updateCustomer(array $data, int $customer_id)
    {
        if (!$customer_id) {
            return false;
        }
        if ($customer = Customer::where('id', $customer_id)->first()) {
            return $customer->update($data);
        }

        return false;
    }

    /**
     * createCustomerWithOrWithoutWpUser method will create a customer
     * also it will create a wp user if you want to create one
     * if you want to create a wp user too then the process will be 1st it will create a wp user
     * after creating the wp user successfully it will create a fluent support customer
     *
     * @param array $data
     * @param bool $createWpUser
     * @return object|boolean
     */

    public function createCustomerWithOrWithoutWpUser(array $data, $createWpUser = false)
    {
        if (!$createWpUser) {
            $isExist = Customer::where('email', $data['email'])->first();
            if (!$data['email'] || !is_email($data['email']) || $isExist) {
                return false;
            }
            return Customer::create($data);
        }

        if (!username_exists($data['username'])) {
            $authController = new AuthController();
            $createdUser = $authController->createUser($data);
            $updateCreatedUser = $authController->maybeUpdateUser($createdUser, $data);
            if ($createdUser) {
                $data['user_id'] = $createdUser;
                return Customer::create($data);
            }
        }

        return false;
    }

    /**
     * deleteCustomer method will delete customer with or without customer tickets and attachments
     * @param int $id
     * @param bool $withAssociatedData | this will delete all tickets and attachments of this customer
     * @return void
     */
    public function deleteCustomer($id, $withAssociatedData = false)
    {
        if (!$id) {
            return;
        }

        $customer = Customer::findOrFail($id);
        if ($withAssociatedData) {
            $tickets = Ticket::where('customer_id', $id);
            foreach ($tickets->get() as $ticket) {
                (new \FluentSupport\App\Hooks\Handlers\CleanupHandler())->deleteTicketAttachments($ticket);
            }
            $tickets->delete();
        }
        $customer->delete();
    }

    public function getInstance()
    {
        return $this->instance;
    }

    public function __call($method, $params)
    {
        if (in_array($method, $this->allowedInstanceMethods)) {
            return call_user_func_array([$this->instance, $method], $params);
        }

        throw new \Exception("Method {$method} does not exist.");
    }
}
