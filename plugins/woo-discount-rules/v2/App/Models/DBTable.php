<?php

namespace Wdr\App\Models;

use Wdr\App\Helpers\Language;
use Wdr\App\Helpers\Woocommerce;

if (!defined('ABSPATH')) exit;

class DBTable
{
    const RULES_TABLE_NAME = WDR_PLUGIN_PREFIX . 'rules', ORDER_DISCOUNT_TABLE_NAME = WDR_PLUGIN_PREFIX . 'order_discounts', ORDER_ITEM_DISCOUNT_TABLE_NAME = WDR_PLUGIN_PREFIX . 'order_item_discounts';

    protected static $rules = array();

    function createDBTables()
    {
        /*global $wpdb;
        if (is_multisite()) {
            // get ids of all sites
            $blog_table = $wpdb->blogs;
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM {$blog_table}");
            foreach ($blog_ids as $blog_id) {
                switch_to_blog($blog_id);
                // create tables for each site
                $this->createTable();
                restore_current_blog();
            }
        } else {*/
        // activated on a single site
        $this->createTable();
        /*}*/
    }

    /**
     * Create table while initializing plugin
     */
    public function createTable()
    {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $wpdb->hide_errors();
        $charset_collate = $wpdb->get_charset_collate();
        $rules_table_name = $wpdb->prefix . self::RULES_TABLE_NAME;
        $rules_table_query = "CREATE TABLE $rules_table_name (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
                 `enabled` tinyint(1) DEFAULT '1',
                 `deleted` tinyint(1) DEFAULT '0',
                 `exclusive` tinyint(1) DEFAULT '0',
                 `title` varchar(255) DEFAULT NULL,
                 `priority` int(11) DEFAULT NULL,
                 `apply_to` text,
                 `filters` longtext NOT NULL,
                 `conditions` longtext,
                 `product_adjustments` text,
                 `cart_adjustments` text,
                 `buy_x_get_x_adjustments` text,
                 `buy_x_get_y_adjustments` text,
                 `bulk_adjustments` text NOT NULL,
                 `set_adjustments` text NOT NULL,
                 `other_discounts` text,
                 `date_from` int(11) DEFAULT NULL,
                 `date_to` int(11) DEFAULT NULL,
                 `usage_limits` int(11) DEFAULT NULL,
                 `rule_language` text DEFAULT NULL,
                 `used_limits` int(11) DEFAULT NULL,
                 `additional` text DEFAULT NULL,
                 `max_discount_sum` varchar(255) DEFAULT NULL,
                 `advanced_discount_message` text DEFAULT NULL,
                 `discount_type` varchar(255) DEFAULT NULL,
                 `used_coupons` text DEFAULT NULL,
                 `created_by` int(11) DEFAULT NULL,
                 `created_on` datetime	 DEFAULT NULL,
                 `modified_by` int(11) DEFAULT NULL,
                 `modified_on` datetime	 DEFAULT NULL, 
                 PRIMARY KEY (`id`)
			) $charset_collate;";
        $order_discount_table_name = $wpdb->prefix . self::ORDER_DISCOUNT_TABLE_NAME;
        $order_discount_table_query = "CREATE TABLE $order_discount_table_name (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
                 `order_id` int(11) DEFAULT NULL,
                 `has_free_shipping` enum('yes','no') NOT NULL DEFAULT 'no',
                 `discounts` text NOT NULL,
                 `created_at` datetime DEFAULT NULL,
                 `updated_at` datetime DEFAULT NULL,
                 `extra` longtext DEFAULT NULL,
                 PRIMARY KEY (`id`)
			) $charset_collate;";
        $order_item_discount_table_name = $wpdb->prefix . self::ORDER_ITEM_DISCOUNT_TABLE_NAME;
        $order_item_discount_table_query = "CREATE TABLE $order_item_discount_table_name (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
                 `order_id` int(11) DEFAULT NULL,
                 `order_item_id` int(11) DEFAULT NULL,
                 `rule_id` int(11) DEFAULT NULL,
                 `item_id` int(11) DEFAULT NULL,
                 `item_price` float NOT NULL,
                 `discounted_price` float NOT NULL,
                 `discount` float NOT NULL,
                 `quantity` int(11) NOT NULL,
                 `simple_discount` float NOT NULL,
                 `bulk_discount` float NOT NULL,
                 `set_discount` float NOT NULL,
                 `cart_discount` float NOT NULL,
                 `other_discount` float NOT NULL DEFAULT '0',
                 `has_free_shipping` enum('yes','no') NOT NULL DEFAULT 'no',
                 `cart_discount_label` varchar(255) DEFAULT NULL,
                 `other_price` float NOT NULL DEFAULT '0',
                 `created_at` datetime	 DEFAULT NULL,
                 `updated_at` datetime	 DEFAULT NULL,
                 `extra` longtext DEFAULT NULL,
                 PRIMARY KEY (`id`),
                 INDEX `index_rule_id` (`rule_id`),
                 INDEX `index_created_at` (`created_at`),
                 INDEX `index_rule_order_id` (`rule_id`, `order_id`)
			) $charset_collate;";
        if(strtolower($wpdb->get_var("show tables like '$rules_table_name'")) != strtolower($rules_table_name)){
            dbDelta($rules_table_query);
        }
        if(strtolower($wpdb->get_var("show tables like '$order_discount_table_name'")) != strtolower($order_discount_table_name)){
            dbDelta($order_discount_table_query);
        }
        if(strtolower($wpdb->get_var("show tables like '$order_item_discount_table_name'")) != strtolower($order_item_discount_table_name)){
            dbDelta($order_item_discount_table_query);
        }
    }

    protected static function isFrontEndRequest(){
        $is_front_end_request = false;
        if(Woocommerce::is_ajax()){
            $is_front_end_request = true;
            if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'wdr_ajax'){
                $wdr_ajax_methods = array('get_price_html', 'get_variable_product_bulk_table');
                $wdr_ajax_methods = apply_filters('advanced_woo_discount_rules_wdr_ajax_methods_for_frontend', $wdr_ajax_methods);
                if(isset($_REQUEST['method']) && is_array($wdr_ajax_methods) && !empty($wdr_ajax_methods)){
                    if(!in_array($_REQUEST['method'], $wdr_ajax_methods)){
                        $is_front_end_request = false;
                    }
                }
            }
            $back_end_ajax_actions = array('wdr_admin_statistics');
            $back_end_ajax_actions = apply_filters('advanced_woo_discount_rules_backend_end_ajax_actions', $back_end_ajax_actions);
            if(!empty($back_end_ajax_actions) && is_array($back_end_ajax_actions)){
                if(isset($_REQUEST['action'])){
                    if(in_array($_REQUEST['action'], $back_end_ajax_actions)){
                        $is_front_end_request = false;
                    }
                }
            }
        }

        return apply_filters('advanced_woo_discount_rules_is_front_end_request_for_fetching_rules', $is_front_end_request);
    }

    public function getRulesCount(){
        global $wpdb;
        $wpdb->hide_errors();

        $rules_table_name = $wpdb->prefix . self::RULES_TABLE_NAME;
        return $wpdb->get_var("SELECT COUNT(*) as total  FROM {$rules_table_name};");
    }

    /**
     * get all available rules
     * @param null $rule_id
     * @param null $rule_name
     * @param null $export
     * @return mixed
     */
    static function getRules($rule_id = NULL, $rule_name = NULL, $export = NULL, $cache = true)
    {
        global $wpdb;
        $wpdb->hide_errors();

        $rules_table_name = $wpdb->prefix . self::RULES_TABLE_NAME;

        $is_front_end_request = self::isFrontEndRequest();

        /**
         * Need for Frontend
         */
        if (!is_admin() || $is_front_end_request) {
            if(isset(self::$rules['front_end']) && $cache === true){
                return self::$rules['front_end'];
            }
            if(strtolower($wpdb->get_var("show tables like '$rules_table_name'")) != strtolower($rules_table_name)){
                return false;
            }
            $current_time = current_time('timestamp');
            $current_language = Language::getCurrentLanguage();
            $language_query = '';
            if (!empty($current_language)) {
                $current_language = esc_sql($current_language);
                $language_query = ' AND (rule_language IS NULL OR rule_language = "[]" OR rule_language LIKE \'%"' . $current_language . '"%\')';
            }
            $query = "SELECT * FROM {$rules_table_name} WHERE  enabled = %d AND deleted = %d AND (date_from <= %d OR date_from IS NULL) AND (date_to >= %d OR date_to IS NULL) AND (usage_limits > used_limits OR used_limits IS NULL OR usage_limits = 0) {$language_query} ORDER BY priority ASC";
            return self::$rules['front_end'] = $wpdb->get_results($wpdb->prepare($query, array(1, 0, $current_time, $current_time)), OBJECT);
        }
        /**
         * Need for Admin
         */
        if (is_admin()) {
            if (!is_null($rule_id) && is_null($rule_name) && is_null($export)) {
                if(isset(self::$rules['admin_based_on_rule_id']) && $cache === true){
                    return self::$rules['admin_based_on_rule_id'];
                }
                if(strtolower($wpdb->get_var("show tables like '$rules_table_name'")) != strtolower($rules_table_name)){
                    return false;
                }
                if(is_array($rule_id)){
                    $rule_id = array_map('absint', $rule_id);
                    $rule_id = implode(",", $rule_id);
                    return self::$rules['admin_based_on_rule_id'] = $wpdb->get_results("SELECT * FROM {$rules_table_name} WHERE id IN ({$rule_id})");
                } else {
                    $rule_id = intval($rule_id);
                    return self::$rules['admin_based_on_rule_id'] = $wpdb->get_row("SELECT * FROM {$rules_table_name} WHERE id={$rule_id}");
                }
            } elseif (is_null($rule_id) && !is_null($rule_name) && is_null($export)) {
                if(isset(self::$rules['admin_based_on_rule_name']) && $cache === true){
                    return self::$rules['admin_based_on_rule_name'];
                }
                if(strtolower($wpdb->get_var("show tables like '$rules_table_name'")) != strtolower($rules_table_name)){
                    return false;
                }
                $rule_name = esc_sql($rule_name);
                return self::$rules['admin_based_on_rule_name'] = $wpdb->get_results("SELECT * FROM {$rules_table_name} WHERE deleted = 0 AND title LIKE '%{$rule_name}%'");
            } else {
                if(isset(self::$rules['admin_all']) && $cache === true){
                    return self::$rules['admin_all'];
                }
                if(strtolower($wpdb->get_var("show tables like '$rules_table_name'")) != strtolower($rules_table_name)){
                    return false;
                }
                return self::$rules['admin_all'] = $wpdb->get_results("SELECT * FROM {$rules_table_name} WHERE deleted = 0 ORDER BY priority ASC");
            }
        }
        return false;
    }


    /**
     * get rules with pagination for Admin page
     * @return array|false
     */
    static function getRulesWithPagination($limit,$offset,$sort,$name = NULL)
    {
        global $wpdb;
        $wpdb->hide_errors();
        $current_user = get_current_user_id();
        $rules_table_name = $wpdb->prefix . self::RULES_TABLE_NAME;
        /**
         * Need for Admin
         */
        if (!is_admin()) {
            return array();
        }
        $where = "deleted = 0";
        if (!empty($name)){
            $where .= " AND title LIKE '%{$name}%'";
        }
        $awdr_filters = array(
            'limit' => $limit,
            'reorder' => ($sort === 1) ? 1 : 0,
        );
        $pagination = '';
        if ($limit != 'all'){
            $pagination.= "LIMIT {$limit} OFFSET {$offset}";
        }
        $query['count'] = $wpdb->get_var("SELECT COUNT(*) as total FROM {$rules_table_name} WHERE {$where} ORDER BY created_on DESC");
        update_user_meta($current_user, 'awdr_filters', $awdr_filters);
        $default_filter = get_user_meta($current_user, 'awdr_filters', true);
        $default_sort = !empty($default_filter['reorder']) ? $default_filter['reorder'] : 0 ;
        if ($sort == 1 || $default_sort == 1){
            if((int)get_option('awdr_priority_reset',0) === 0){
                self::resetRulePriorities();
                update_option('awdr_priority_reset', 1);
            }
            $query['result'] = $wpdb->get_results("SELECT * FROM {$rules_table_name} WHERE {$where} ORDER BY priority ASC {$pagination}");
        } else {
            $current_user = get_current_user_id();
            update_user_meta($current_user, 'awdr_filters', $awdr_filters);
            $query['result'] = $wpdb->get_results("SELECT * FROM {$rules_table_name} WHERE {$where} ORDER BY created_on DESC {$pagination}");
        }
        return $query;
    }

    /**
     * Get rules for on sale list (indexing)
     *
     * @param array $rule_ids
     * @return array
     */
    public static function getRulesForOnSaleList($rule_ids, $all = false)
    {
        global $wpdb;
        $wpdb->hide_errors();

        $rules_table_name = $wpdb->prefix . self::RULES_TABLE_NAME;

        $rule_query = '';
        if (!$all && is_array($rule_ids)) {
            $rule_ids = array_map('absint', $rule_ids);
            $rule_ids = implode(",", $rule_ids);
            $rule_query = "AND id IN ({$rule_ids})";
        }
        $current_time = current_time('timestamp');

        $query = "SELECT * FROM {$rules_table_name} WHERE enabled = %d AND deleted = %d {$rule_query} AND (date_from <= %d OR date_from IS NULL) AND (date_to >= %d OR date_to IS NULL) AND (usage_limits > used_limits OR used_limits IS NULL OR usage_limits = 0)";
        return self::$rules['on_sale_list'] = $wpdb->get_results($wpdb->prepare($query, array(1, 0, $current_time, $current_time)), OBJECT);
    }

    /**
     * save new rule
     * @param $format
     * @param $values
     * @param null $rule_id
     * @param $import_id
     * @return int|null
     */
    static function saveRule($format, $values, $rule_id = NULL)
    {
        global $wpdb;
        $rules_table_name = $wpdb->prefix.self::RULES_TABLE_NAME;
        if (!is_null($rule_id) && !empty($rule_id)) {
            $rule_id = intval($rule_id);
            $wpdb->update($rules_table_name, $values, array('id' => $rule_id), $format, array('%d'));
        } else {
            $wpdb->insert($rules_table_name, $values, $format);
            $rule_id = $wpdb->insert_id;
            $update_query = "UPDATE {$rules_table_name} as rule JOIN (SELECT (CASE WHEN (MAX(priority) IS NOT NULL) THEN MAX(priority) +1 ELSE 1 END) as max_priority FROM {$rules_table_name} WHERE deleted = 0) as rule_priority  SET rule.priority = rule_priority.max_priority WHERE id = {$rule_id}";
            $wpdb->query($update_query);
        }
        return $rule_id;
    }

    /**
     * update priority on after delete rule
     * @param $rule_id
     * @return mixed
     */
    static function updatePriorityOnDeleteRule($rule_id)
    {
        if((int)get_option('awdr_priority_reset',0) === 0){
            self::resetRulePriorities();
            update_option('awdr_priority_reset', 1);
        } else {
            global $wpdb;
            $rules_table_name = $wpdb->prefix.self::RULES_TABLE_NAME;
            $priority = $wpdb->get_var("SELECT priority FROM {$rules_table_name} WHERE id = {$rule_id}");
            $update_query = "UPDATE {$rules_table_name} SET priority = priority - 1 WHERE priority > {$priority} AND deleted = 0 AND id != {$rule_id}";
            $wpdb->query($update_query);
        }
    }

    /**
     * update all priority based on row number
     * @return void
     */
    static function resetRulePriorities()
    {
        global $wpdb;
        $rules_table_name = $wpdb->prefix.self::RULES_TABLE_NAME;
        $update_query = "UPDATE {$rules_table_name} AS t JOIN (SELECT @rownum:=@rownum+1 rownum, id, priority deleted FROM {$rules_table_name}
        CROSS JOIN (select @rownum := 0) rn WHERE deleted = 0 ORDER BY priority) AS r ON t.id = r.id SET t.priority = r.rownum";
        $wpdb->query($update_query);
    }

    /**
     * @param $rule_id
     * @param $new_priority
     * @return false|void
     */
    static function dragDropPriorities($position)
    {
        global $wpdb;
        $rules_table_name = $wpdb->prefix.self::RULES_TABLE_NAME;
        if (!is_array($position) || empty($position['drag_position']) || empty($position['drop_position']) || ($position['drag_position'] == $position['drop_position'])){
            return false;
        }

        $old_priority = $position['drag_position'];
        $new_priority = $position['drop_position'];
        $rule_id = $wpdb->get_var("SELECT id FROM {$rules_table_name} WHERE priority = {$position['drag_position']} AND deleted = 0");
        $old_rule_id = (int)$rule_id;

        // Moving small to high priority
        if ($old_rule_id){
            if ($old_priority < $new_priority){
                $update = "UPDATE {$rules_table_name} SET priority = {$new_priority} WHERE id={$old_rule_id}";
                $update_query = "UPDATE {$rules_table_name} SET priority = priority - 1 WHERE priority > {$old_priority} AND priority <= {$new_priority} AND id != {$old_rule_id}";
            }

//        // Moving high to small priority
            elseif ($old_priority > $new_priority) {
                $update = "UPDATE {$rules_table_name} SET priority = '{$new_priority}' WHERE id={$old_rule_id}";
                $update_query = "UPDATE {$rules_table_name} SET priority = priority + 1 WHERE priority >= {$new_priority} AND priority < {$old_priority} AND id != {$old_rule_id}";
            }
            $result1 = $wpdb->query($update);
            $result2 = $wpdb->query($update_query);
            if($result1 != false && $result2 != false){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $order_id
     * @param $free_shipping
     * @param $discounts
     * @return int
     */
    static function saveOrderDiscounts($order_id, $free_shipping, $discounts)
    {
        global $wpdb;
        $order_id = intval($order_id);
        $free_shipping = esc_sql($free_shipping);
        $order_discount_table_name = $wpdb->prefix . self::ORDER_DISCOUNT_TABLE_NAME;
        $select_query = "SELECT id FROM {$order_discount_table_name} WHERE order_id=" . $order_id;
        $order_discounts = $wpdb->get_row($select_query, OBJECT);
        $current_time = current_time('mysql', true);
        if (empty($order_discounts)) {
            $insert_query = "INSERT INTO {$order_discount_table_name} (order_id, has_free_shipping, discounts, created_at) VALUES ({$order_id}, '{$free_shipping}', '{$discounts}', '{$current_time}')";
            $wpdb->query($insert_query);
            $row_id = $wpdb->insert_id;
        } else {
            $row_id = $order_discounts->id;
            $update_query = "UPDATE {$order_discount_table_name} SET has_free_shipping = '{$free_shipping}', discounts = '{$discounts}', updated_at = '{$current_time}' WHERE id={$row_id}";
            $wpdb->query($update_query);
        }
        return $row_id;
    }

    /**
     * Updating the used limits of rules
     * @param $rule_id
     * @param $used_count
     */
    static function updateRuleUsedCount($rule_id, $used_count)
    {
        global $wpdb;
        $wpdb->update($wpdb->prefix . self::RULES_TABLE_NAME, array('used_limits' => intval($used_count)), array('id' => intval($rule_id)), array('%d'), array('%d'));
    }

    /**
     * Update rule additional data
     *
     * @param int $rule_id
     * @param string $new_additional
     * @return bool
     */
    static function updateRuleAdditionalData($rule_id, $new_additional)
    {
        global $wpdb;
        $rules_table = $wpdb->prefix.self::RULES_TABLE_NAME;
        return $wpdb->update($rules_table, array('additional' => (string) $new_additional), array('id' => (int) $rule_id), array('%s'), array('%d'));
    }

    /**
     * save the order item discount
     * @param $order_id
     * @param $order_item_id
     * @param $item_id
     * @param $item_price
     * @param $discounted_price
     * @param $discount
     * @param $quantity
     * @param $rule_id
     * @param $simple_discount
     * @param $bulk_discount
     * @param $set_discount
     * @param $cart_discount
     * @param $other_discount
     * @param $cart_discount_label
     * @param bool $is_free_shipping
     * @return int
     */
    static function saveOrderItemDiscounts($order_id, $order_item_id, $item_id, $item_price, $discounted_price, $discount, $quantity, $rule_id, $simple_discount, $bulk_discount, $set_discount, $cart_discount, $other_discount, $cart_discount_label, $is_free_shipping = false)
    {
        global $wpdb;
        $order_item_discount_table_name = $wpdb->prefix . self::ORDER_ITEM_DISCOUNT_TABLE_NAME;
        $order_id = intval($order_id);
        $order_item_id = intval($order_item_id);
        $rule_id = intval($rule_id);
        $item_id = intval($item_id);
        $item_price = floatval($item_price);
        $discounted_price = floatval($discounted_price);
        $discount = floatval($discount);
        $quantity = intval($quantity);
        $simple_discount = floatval($simple_discount);
        $bulk_discount = floatval($bulk_discount);
        $set_discount = floatval($set_discount);
        $cart_discount = floatval($cart_discount);
        $other_discount = floatval($other_discount);
        $cart_discount_label = esc_sql($cart_discount_label);
        $has_free_shipping = $is_free_shipping ? "yes" : "no";
        $select_query = "SELECT id FROM {$order_item_discount_table_name} WHERE order_id={$order_id} AND item_id={$item_id} AND rule_id={$rule_id}";
        $order_discounts = $wpdb->get_row($select_query, OBJECT);
        $current_time = current_time('mysql', true);

        if (empty($order_discounts)) {
            $insert_query = "INSERT INTO {$order_item_discount_table_name} (order_id, order_item_id, rule_id, item_id, item_price, discounted_price, discount, quantity, simple_discount, bulk_discount, set_discount, cart_discount, other_discount, has_free_shipping, cart_discount_label, created_at, updated_at) VALUES ({$order_id}, {$order_item_id}, {$rule_id}, {$item_id}, {$item_price}, {$discounted_price}, {$discount}, {$quantity}, {$simple_discount}, {$bulk_discount}, {$set_discount}, {$cart_discount}, {$other_discount}, '{$has_free_shipping}', '{$cart_discount_label}', '{$current_time}', '{$current_time}')";
            $wpdb->query($insert_query);
            $row_id = $wpdb->insert_id;
        } else {
            $row_id = $order_discounts->id;
            $update_query = "UPDATE {$order_item_discount_table_name} SET order_id={$order_id}, order_item_id={$order_item_id}, rule_id={$rule_id}, item_id={$item_id}, item_price={$item_price}, discounted_price={$discounted_price}, discount={$discount}, quantity={$quantity}, simple_discount={$simple_discount}, bulk_discount={$bulk_discount}, set_discount={$set_discount}, cart_discount={$cart_discount}, other_discount={$other_discount}, has_free_shipping='{$has_free_shipping}', cart_discount_label='{$cart_discount_label}', updated_at='{$current_time}' WHERE id={$row_id}";
            $wpdb->query($update_query);
        }
        return $row_id;
    }


    /**
     * get combine rule data
     *
     * @param $params
     * @return array|bool|object|null
     */
    public static function get_rules_rows_summary( $params ) {
        global $wpdb;
        $params = array_merge( array(
            'from'                  => '',
            'to'                    => '',
            'limit'                 => 5,
            'include_amount'        => true,
            'include_cart_discount' => false,
            //'include_shipping'      => false,
            //'include_gifted_amount' => false,
            //'include_gifted_qty'    => false,
        ), $params );
        if ( empty( $params['from'] ) || empty( $params['to'] ) ) {
            return false;
        }
        $summary_components = array();
        if ( $params['include_amount'] ) {
            $summary_components[] = 'rules_stats.discount';
        }
        if ( $params['include_cart_discount'] ) {
            $summary_components[] = 'rules_stats.cart_discount';
        }

        if ( empty( $summary_components ) ) {
            return false;
        }
        $summary_field = implode( '+', $summary_components );
        $table_items = $wpdb->prefix.self::RULES_TABLE_NAME;
        $table_stats = $wpdb->prefix.self::ORDER_ITEM_DISCOUNT_TABLE_NAME;

        $query_total = $wpdb->prepare(
            "SELECT rules.id AS rule_id, SUM({$summary_field}) AS value
			FROM {$table_items} AS rules LEFT JOIN {$table_stats} AS rules_stats
			ON rules.id = rules_stats.rule_id
			WHERE DATE(rules_stats.created_at) BETWEEN %s AND %s
			GROUP BY rules.id
			HAVING value>0
			ORDER BY value DESC
			LIMIT %d",
            array( $params['from'], $params['to'], (int) $params['limit'] )
        );
        $top = $wpdb->get_col( $query_total );
        if ( empty( $top ) ) {
            return false;
        }

        $placeholders = array_fill( 0, count( $top ), '%d' );
        $placeholders = implode( ', ', $placeholders );
        $query = $wpdb->prepare(
            "SELECT DATE(rules_stats.created_at) as date_rep, rules.id AS rule_id, CONCAT('#', rules.id, ' ', rules.title) AS title, SUM({$summary_field}) AS value
			FROM {$table_items} AS rules LEFT JOIN {$table_stats} AS rules_stats
			ON rules.id = rules_stats.rule_id
			WHERE DATE(rules_stats.created_at) BETWEEN %s AND %s AND rules.id IN ({$placeholders})
			GROUP BY date_rep, rule_id
			HAVING value>0
			ORDER BY value DESC",
            array_merge( array( $params['from'], $params['to'] ), $top )
        );

        $rows = $wpdb->get_results( $query );

        $query_info = $wpdb->prepare(
            "SELECT COUNT(results.order_id) AS total_orders, 
                SUM(results.discounted_amount) AS discounted_amount,
                SUM(results.revenue) AS revenue, SUM(results.free_shipping) as total_free_shipping
            FROM (
                SELECT rules_stats.order_id, 
                       SUM({$summary_field}) AS discounted_amount, post_meta.meta_value as revenue, 
                       SUM(CASE WHEN rules_stats.has_free_shipping = 'yes' THEN 1 ELSE 0 END) as free_shipping
                FROM {$table_stats} AS rules_stats LEFT JOIN {$wpdb->postmeta} as post_meta
			    ON (rules_stats.order_id = post_meta.post_id AND post_meta.meta_key = '_order_total')
			    WHERE DATE(rules_stats.created_at) BETWEEN %s AND %s
			    GROUP BY rules_stats.order_id
            ) AS results",
            array( $params['from'], $params['to'])
        );
        $info = $wpdb->get_row( $query_info );

        return ['stats' => $rows, 'other' => $info];
    }

    /**
     * get particular rule data
     * @param $params
     * @param $rule_id
     * @return array|bool|object|null
     */
    public static function get_rule_rows_summary( $params, $rule_id ) {
        $rule_id = intval($rule_id);
        global $wpdb;
        $params = array_merge( array(
            'from'                  => '',
            'to'                    => '',
            'limit'                 => 5,
            'include_amount'        => true,
            'include_cart_discount' => false,
        ), $params );
        if ( empty( $params['from'] ) || empty( $params['to'] ) ) {
            return false;
        }
        $summary_components = array();
        if ( $params['include_amount'] ) {
            $summary_components[] = 'rules_stats.discount';
        }
        if ( $params['include_cart_discount'] ) {
            $summary_components[] = 'rules_stats.cart_discount';
        }

        if ( empty( $summary_components ) ) {
            return false;
        }
        $summary_field = implode( '+', $summary_components );
        $table_items = $wpdb->prefix.self::RULES_TABLE_NAME;
        $table_stats = $wpdb->prefix.self::ORDER_ITEM_DISCOUNT_TABLE_NAME;

        $query = $wpdb->prepare(
            "SELECT DATE(rules_stats.created_at) as date_rep, rules.id AS rule_id, CONCAT('#', rules.id, ' ', rules.title) AS title, SUM({$summary_field}) AS value
			FROM {$table_items} AS rules LEFT JOIN {$table_stats} AS rules_stats
			ON rules.id = rules_stats.rule_id
			WHERE rules.id={$rule_id} AND DATE(rules_stats.created_at) BETWEEN %s AND %s
			GROUP BY date_rep, rule_id, title
			ORDER BY value DESC",
            array( $params['from'], $params['to'] )
        );

        $rows = $wpdb->get_results( $query );

        $query_info = $wpdb->prepare(
            "SELECT COUNT(results.order_id) AS total_orders, 
                SUM(results.discounted_amount) AS discounted_amount,
                SUM(results.revenue) AS revenue, SUM(results.free_shipping) as total_free_shipping
            FROM (
                SELECT rules_stats.order_id, 
                       SUM({$summary_field}) AS discounted_amount, post_meta.meta_value as revenue, 
                       SUM(CASE WHEN rules_stats.has_free_shipping = 'yes' THEN 1 ELSE 0 END) as free_shipping
                FROM {$table_stats} AS rules_stats LEFT JOIN {$wpdb->postmeta} as post_meta
			    ON (rules_stats.order_id = post_meta.post_id AND post_meta.meta_key = '_order_total')
			    WHERE rules_stats.rule_id={$rule_id} AND DATE(rules_stats.created_at) BETWEEN %s AND %s
			    GROUP BY rules_stats.order_id
            ) AS results",
            array($params['from'], $params['to'])
        );
        $info = $wpdb->get_row( $query_info );

        return ['stats' => $rows, 'other' => $info];
    }

    /**
     * get all coupons
     */
    public static function get_coupons_for_report()
    {
        global $wpdb;
        $table_stats = $wpdb->prefix.self::ORDER_ITEM_DISCOUNT_TABLE_NAME;
        $coupons = $wpdb->get_results("SELECT cart_discount_label FROM {$table_stats} WHERE cart_discount_label != '' GROUP BY cart_discount_label LIMIT 1000");
        if ($coupons) {
            return $coupons;
        }
        return [];
    }

    /**
     * get combine rule data
     *
     * @param $params
     * @return array|bool|object|null
     */
    public static function get_coupon_data( $params ) {
        global $wpdb;
        if ( empty( $params['from'] ) || empty( $params['to'] || empty( $params['type'] ) ) ) {
            return false;
        }
        $type = sanitize_text_field($params['type']);

        $table_stats = $wpdb->prefix.self::ORDER_ITEM_DISCOUNT_TABLE_NAME;

        $order_by = 'SUM(results.discounted_amount) DESC';
        switch ($type) {
            case 'awdr_all_coupons':
                $where_query = "cart_discount_label != ''";
                break;
            case 'awdr_custom_coupons':
                $where_query = "cart_discount_label != '' AND cart_discount = 0";
                $order_by = 'COUNT(results.order_id) DESC';
                break;
            case 'awdr_discount_coupons':
                $where_query = "cart_discount_label != '' AND cart_discount != 0";
                break;
            default:
                $where_query = $wpdb->prepare("cart_discount_label = '%s'", array($type));
        }

        $query = $wpdb->prepare(
            "SELECT results.cart_discount_label as coupon_name,
                COUNT(results.order_id) AS total_orders,
                SUM(results.discounted_amount) AS discounted_amount
            FROM (
                SELECT order_id, SUM(`cart_discount`) AS discounted_amount, cart_discount_label
                FROM {$table_stats}
			    WHERE {$where_query} AND DATE(created_at) BETWEEN %s AND %s
			    GROUP BY order_id, cart_discount_label
            ) AS results 
            GROUP BY results.cart_discount_label 
            ORDER BY {$order_by}
            LIMIT 10",
            array( $params['from'], $params['to'])
        );

        $data = $wpdb->get_results( $query );
        if ($data) {
            return $data;
        }
        return [];
    }

    /**
     * update new table structure
     */
    function updateDBTables(){
        /*global $wpdb;
        if (is_multisite()) {
            // get ids of all sites
            $blog_table = $wpdb->blogs;
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM {$blog_table}");
            foreach ($blog_ids as $blog_id) {
                switch_to_blog($blog_id);
                // create tables for each site
                $this->updateTable();
                restore_current_blog();
            }
        } else {*/
        // activated on a single site
        $this->updateTable();
        /*}*/
    }

    /**
     * Create new coloumns
     * https://code.tutsplus.com/tutorials/custom-database-tables-maintaining-the-database--wp-28455
     */
    public function updateTable(){
        //Version of currently activated plugin
        $current_version = WDR_VERSION;
        //Database version - this may need upgrading.
        $installed_version = get_option('awdr_activity_log_version');
        if( $installed_version != $current_version ){
            global $wpdb;
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $charset_collate = $wpdb->get_charset_collate();
            $rules_table_name = $wpdb->prefix . self::RULES_TABLE_NAME;
            $rules_table_query = "CREATE TABLE $rules_table_name (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
                 `enabled` tinyint(1) DEFAULT '1',
                 `deleted` tinyint(1) DEFAULT '0',
                 `exclusive` tinyint(1) DEFAULT '0',
                 `title` varchar(255) DEFAULT NULL,
                 `priority` int(11) DEFAULT NULL,
                 `apply_to` text,
                 `filters` longtext NOT NULL,
                 `conditions` longtext,
                 `product_adjustments` text,
                 `cart_adjustments` text,
                 `buy_x_get_x_adjustments` text,
                 `buy_x_get_y_adjustments` text,
                 `bulk_adjustments` text NOT NULL,
                 `set_adjustments` text NOT NULL,
                 `other_discounts` text,
                 `date_from` int(11) DEFAULT NULL,
                 `date_to` int(11) DEFAULT NULL,
                 `usage_limits` int(11) DEFAULT NULL,
                 `rule_language` text DEFAULT NULL,
                 `used_limits` int(11) DEFAULT NULL,
                 `additional` text DEFAULT NULL,
                 `max_discount_sum` varchar(255) DEFAULT NULL,
                 `advanced_discount_message` text DEFAULT NULL,
                 `discount_type` varchar(255) DEFAULT NULL,
                 `used_coupons` text DEFAULT NULL,
                 `created_by` int(11) DEFAULT NULL,
                 `created_on` datetime	 DEFAULT NULL,
                 `modified_by` int(11) DEFAULT NULL,
                 `modified_on` datetime	 DEFAULT NULL, 
                 PRIMARY KEY (`id`)
			) $charset_collate;";
            dbDelta($rules_table_query);

            $order_item_discount_table_name = $wpdb->prefix . self::ORDER_ITEM_DISCOUNT_TABLE_NAME;
            /**
             * Added `order_item_id` column (since v2.5.0)
             * Added `other_discount` column (since v2.5.0)
             * Added `index_rule_id` index (since v2.5.0)
             * Added `index_created_at` index (since v2.5.0)
             * Added `index_rule_order_id` index (since v2.5.0)
             */
            $order_item_discount_table_query = "CREATE TABLE $order_item_discount_table_name (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
                 `order_id` int(11) DEFAULT NULL,
                 `order_item_id` int(11) DEFAULT NULL,
                 `rule_id` int(11) DEFAULT NULL,
                 `item_id` int(11) DEFAULT NULL,
                 `item_price` float NOT NULL,
                 `discounted_price` float NOT NULL,
                 `discount` float NOT NULL,
                 `quantity` int(11) NOT NULL,
                 `simple_discount` float NOT NULL,
                 `bulk_discount` float NOT NULL,
                 `set_discount` float NOT NULL,
                 `cart_discount` float NOT NULL,
                 `other_discount` float NOT NULL DEFAULT '0',
                 `has_free_shipping` enum('yes','no') NOT NULL DEFAULT 'no',
                 `cart_discount_label` varchar(255) DEFAULT NULL,
                 `other_price` float NOT NULL DEFAULT '0',
                 `created_at` datetime	 DEFAULT NULL,
                 `updated_at` datetime	 DEFAULT NULL,
                 `extra` longtext DEFAULT NULL,
                 PRIMARY KEY (`id`),
                 INDEX `index_rule_id` (`rule_id`),
                 INDEX `index_created_at` (`created_at`),
                 INDEX `index_rule_order_id` (`rule_id`, `order_id`)
			) $charset_collate;";
            dbDelta($order_item_discount_table_query);

            update_option('awdr_activity_log_version', $current_version);
        }
    }

    /**
     * Get order count for 100+ sales review notification
     * @return float|int|string
     */
    public static function getOrderCount()
    {
        $order_count_from_transient = get_transient('awdr_sale_count');
        if (is_numeric($order_count_from_transient)) {
            return $order_count_from_transient;
        } else {
            global $wpdb;
            $order_item_discount_table_name = $wpdb->prefix . self::ORDER_ITEM_DISCOUNT_TABLE_NAME;
            $sale_result = $wpdb->get_results("SELECT count(order_id) FROM $order_item_discount_table_name GROUP BY order_id ");
            $sale_count_result = isset($sale_result) ? count($sale_result) : 0 ;
            set_transient('awdr_sale_count', $sale_count_result, 24 * 60 * 60);
            return $sale_count_result;
        }
    }
}