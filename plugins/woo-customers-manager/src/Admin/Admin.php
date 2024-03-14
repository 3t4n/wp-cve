<?php namespace Premmerce\ExtendedUsers\Admin;

use Premmerce\SDK\V2\FileManager\FileManager;
use WP_User;
use WP_User_Query;

/**
 * Class Admin
 *
 * @package Premmerce\ExtendedUsers\Admin
 */
class Admin
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * Admin constructor.
     *
     * Register menu items and handlers
     *
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;

        add_filter('manage_users_columns', array($this, 'newUsersTableColumns'));
        add_filter('manage_users_custom_column', array($this, 'setDataUsersTableColumns'), 10, 3);
        add_filter('pre_get_users', array($this, 'useFilters'));

        add_action('manage_users_extra_tablenav', array($this, 'renderFilters'));

        add_action('admin_init', function () {
            wp_enqueue_style(
                'extended_users_filter_style',
                $this->fileManager->locateAsset('admin/css/premmerce-extended-users.css')
            );
        });

        add_action('show_user_profile', array($this, 'renderUserData'), 20);
        add_action('edit_user_profile', array($this, 'renderUserData'), 20);
    }

    /**
     * @param WP_User_Query $query
     *
     * @return mixed
     */
    public function useFilters(WP_User_Query $query)
    {
        if (function_exists('get_current_screen') && get_current_screen() && get_current_screen()->id == 'users') {
            $metaQuery = array();
            $dateQuery = array();

            $defaults = array(
                'money_spent_from' => '',
                'money_spent_to'   => '',
                'registered_from'  => null,
                'registered_to'    => null,
            );

            $defaults = array_replace($defaults, $_GET);

            $moneySpentFrom = $defaults['money_spent_from'];
            $moneySpentTo   = $defaults['money_spent_to'];
            $registeredFrom = (bool)strtotime($defaults['registered_from']) ? $defaults['registered_from'] : null;
            $registeredTo   = (bool)strtotime($defaults['registered_to']) ? $defaults['registered_to'] : null;

            $value   = null;
            $compare = null;

            if ($moneySpentFrom !== '' && $moneySpentTo !== '') {
                $value   = array((float)$moneySpentFrom, (float)$moneySpentTo);
                $compare = 'BETWEEN';
            } elseif ($moneySpentFrom !== '') {
                $value   = (float)$moneySpentFrom;
                $compare = '>';
            } elseif ($moneySpentTo !== '') {
                $value   = (float)$moneySpentTo;
                $compare = '<';
            }

            if ($value && $compare) {
                $metaQuery[] = array(
                    'key'     => '_money_spent',
                    'value'   => $value,
                    'compare' => $compare,
                    'type'    => 'DECIMAL(12,2)',
                );
            }

            if ($registeredFrom && $registeredTo) {
                $dateQuery[] = array(
                    'after'     => $registeredFrom,
                    'before'    => $registeredTo,
                    'inclusive' => true,
                );
            } elseif ($registeredFrom) {
                $dateQuery[] = array(
                    'after'     => $registeredFrom,
                    'inclusive' => true,
                );
            } elseif ($registeredTo) {
                $dateQuery[] = array(
                    'before'    => $registeredTo,
                    'inclusive' => true,
                );
            }

            $query->set('meta_query', $metaQuery);
            $query->set('date_query', $dateQuery);
        }

        return $query;
    }

    /**
     * Render filter fields
     *
     * @param string $position
     */
    public function renderFilters($position)
    {
        if ($position == 'top') {
            $defaults = array(
                'money_spent_from' => null,
                'money_spent_to'   => null,
                'registered_from'  => null,
                'registered_to'    => null,
            );

            $defaults = array_replace($defaults, $_GET);

            $filters = array(
                'registered_from'  => $defaults['registered_from'],
                'registered_to'    => $defaults['registered_to'],
                'money_spent_from' => $defaults['money_spent_from'],
                'money_spent_to'   => $defaults['money_spent_to'],
            );

            $this->fileManager->includeTemplate('admin/filter.php', $filters);
        }
    }

    /**
     * Render additional information and orders for user
     *
     * @param WP_User $user
     */
    public function renderUserData(WP_User $user)
    {
        $this->renderUserProfile($user);
        $this->renderUserOrders($user->ID);
    }

    /**
     * Render additional information for user
     *
     * @param WP_User $user
     */
    public function renderUserProfile(WP_User $user)
    {
        $this->fileManager->includeTemplate('admin/user-profile.php', array('user' => $user));
    }

    /**
     * Render orders for user
     *
     * @param integer $userID
     */
    public function renderUserOrders($userID)
    {
        $userPostsOrders = get_posts(array(
            'numberposts' => -1,
            'meta_key'    => '_customer_user',
            'meta_value'  => $userID,
            'post_type'   => array('shop_order'),
            'post_status' => array_keys(wc_get_order_statuses()), // ['wc-completed'],
        ));

        $userOrders = array();
        foreach ($userPostsOrders as $userPostOrder) {
            $order = wc_get_order($userPostOrder->ID);

            $userOrders[$order->get_id()] = $order;
        }

        $this->fileManager->includeTemplate('admin/user-orders.php', array('userID' => $userID, 'orders' => $userOrders));
    }

    /**
     * Add additional columns to users table
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function newUsersTableColumns($columns)
    {
        $columns['registered']  = __('Registered', 'woo-customers-manager');
        $columns['money_spent'] = __('Money spent', 'woo-customers-manager');

        return $columns;
    }

    /**
     * Set data to additional columns to users table
     *
     * @param $val
     * @param $columnName
     * @param $userId
     *
     * @return false|string
     */
    public function setDataUsersTableColumns($val, $columnName, $userId)
    {
        switch ($columnName) {
            case 'registered':
                return date_i18n(get_option('date_format'), strtotime(get_userdata($userId)->user_registered));
            case 'money_spent':
                return wc_price(wc_get_customer_total_spent($userId));
        }

        return $val;
    }
}
