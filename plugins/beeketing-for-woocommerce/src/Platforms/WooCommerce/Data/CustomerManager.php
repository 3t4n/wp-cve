<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:20 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Data;


use BeeketingConnect_beeketing_woocommerce\Common\Data\Exception\EntityNotFoundException;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Manager\CustomerManagerAbstract;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Count;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Customer;

class CustomerManager extends CustomerManagerAbstract
{
    private static $preLoaded = false;
    private static $wcUserOrderCounts = array();
    private static $wcUserTotalSpents = array();

    /**
     * Get a customer
     * @param $arg
     * @return Customer
     * @throws \Exception
     */
    public function get($arg)
    {
        $id = $arg['resource_id'];
        $user = get_user_by('id', $id);

        if ($user) {
            return $this->formatUser($user);
        }

        throw new EntityNotFoundException(sprintf('Cant get customer id %s', $id));
    }

    /**
     * Get many customers
     * @param $arg
     * @return array
     */
    public function getMany($arg)
    {
        $page = $arg['page'];
        $limit = $arg['limit'];

        $offset = ($page - 1) * $limit;

        // Find in id
        if ($ids = $arg['ids']) {
            $args = array(
                'role' => 'customer',
                'include' => $ids,
                'number' => -1,
            );
        } else {
            // Default mode
            $args = array(
                'role' => 'customer',
                'offset' => $offset,
                'number' => $limit,
            );
        }

        $result = get_users($args);

        global $wpdb;
        // Get order count
        $countResults = $wpdb->get_results(
            "SELECT user_id, meta_value FROM $wpdb->usermeta WHERE meta_key = '_order_count'"
        );
        foreach ($countResults as $count_result) {
            self::$wcUserOrderCounts[$count_result->user_id] = $count_result->meta_value;
        }

        // Get total spent
        $spentResults = $wpdb->get_results(
            "SELECT user_id, meta_value FROM $wpdb->usermeta WHERE meta_key = '_money_spent'"
        );
        foreach ($spentResults as $spentResult) {
            self::$wcUserTotalSpents[$spentResult->user_id] = $spentResult->meta_value;
        }

        // Mark pre loaded data
        self::$preLoaded = true;

        $customers = array();
        foreach ($result as $item) {
            $customers[] = $this->formatUser($item);
        }

        return $customers;
    }

    /**
     * Count customers
     * @return Count
     */
    public function count()
    {
        $result = count_users();
        $count = 0;
        foreach ($result['avail_roles'] as $role => $total) {
            if ($role == 'customer') {
                $count = $total;
            }
        }

        return new Count($count);
    }

    /**
     * Format user
     *
     * @param $user
     * @return Customer
     */
    private function formatUser($user)
    {
        if (self::$preLoaded) {
            $ordersCount = isset(self::$wcUserOrderCounts[$user->ID]) ? self::$wcUserOrderCounts[$user->ID] : 0;
            $totalSpent = isset(self::$wcUserTotalSpents[$user->ID]) ? self::$wcUserTotalSpents[$user->ID] : 0;
        } else {
            $ordersCount = wc_get_customer_order_count($user->ID);
            $totalSpent = wc_get_customer_total_spent($user->ID);
        }

        $totalSpent = floatval($totalSpent);

        $customer = new Customer();
        $customer->id = $user->ID;
        $customer->email = $user->user_email;
        $customer->first_name = $user->first_name;
        $customer->last_name = $user->last_name;
        $customer->accepts_marketing = true;
        $customer->verified_email = !$user->user_activation_key;
        $customer->signed_up_at = $user->user_registered;
        $customer->address1 = $user->billing_address_1;
        $customer->address2 = $user->billing_address_2;
        $customer->city = $user->billing_city;
        $customer->company = $user->billing_company;
        $customer->province = $user->billing_state;
        $customer->zip = $user->billing_postcode;
        $customer->country = $user->billing_country;
        $customer->country_code = $user->billing_country;
        $customer->orders_count = (int)$ordersCount;
        $customer->total_spent = $totalSpent;

        return $customer;
    }
}