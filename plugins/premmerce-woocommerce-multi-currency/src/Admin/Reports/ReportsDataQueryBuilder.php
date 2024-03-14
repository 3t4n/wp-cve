<?php

namespace Premmerce\WoocommerceMulticurrency\Admin\Reports;

/**
 * Class ReportsDataQueryBuilder
 * @package Premmerce\WoocommerceMulticurrency\Admin\Reports
 *
 * @todo: rewrite this class when WC product and order tables will be released
 */
class ReportsDataQueryBuilder
{
    /**
     * @var string
     */
    private $reportClassName;

    /**
     * @var string
     */
    private $reportName;

    /**
     * @var bool
     */
    private $rateMultiplierNeeded = false;

    /**
     * Main class function. Controls query building process.
     *
     * @param $args
     * @param $reportData
     * @return mixed
     */
    public function buildNewReportDataQuery($args, $reportData)
    {
        global $wpdb;

        $this->reportName = $reportData['reportName'];
        $this->reportClassName = $reportData['reportClassName'];


        $newQuery['select'] = $this->buildSelectPart($args['data']);
        $newQuery['from']   = "FROM {$wpdb->posts} AS posts";
        $newQuery['join']   = $this->buildJoinPart($args);
        $newQuery['where']  = $this->buildWherePart($args);

        if ($args['group_by']) {
            $groupBy = $this->reportName === 'sales-by-category' ? 'posts.ID, product_id, post_date' : $args['group_by'];

            $newQuery['group_by'] = "GROUP BY {$groupBy}";
        }

        if ($args['order_by']) {
            $newQuery['order_by'] = "ORDER BY {$args['order_by']}";
        }

        if ($args['limit']) {
            $newQuery['limit'] = "LIMIT {$args['limit']}";
        }

        return $newQuery;
    }

    /**
     * Build array with parts of query select part
     *
     * @param array     $data
     * @return string   $selectParts
     */
    private function buildSelectPart($data)
    {
        $selectParts = array();
        $get_key = '';


        foreach ($data as $raw_key => $value) {
            $key      = sanitize_key($raw_key);
            $distinct = '';

            if (isset($value['distinct'])) {
                $distinct = 'DISTINCT';
            }



            $multiplier = '';

            if (in_array($key, array(
                '_order_total',
                '_line_total',
                '_order_shipping',
                '_order_tax',
                '_order_shipping_tax',
                '_refund_amount',
                'tax_amount',
                'shipping_tax_amount'
            ))) {
                $this->rateMultiplierNeeded = true;
                $multiplier = ' * IFNULL(currencies.rate, 1)';
            }


            switch ($value['type']) {
                case 'meta':
                    $get_key = "meta_{$key}.meta_value" . $multiplier;
                    break;
                case 'parent_meta':
                    $get_key = "parent_meta_{$key}.meta_value" . $multiplier;
                    break;
                case 'post_data':
                    $get_key = "posts.{$key}";
                    break;
                case 'order_item_meta':
                    $get_key = "order_item_meta_{$key}.meta_value" . $multiplier;
                    break;
                case 'order_item':
                    $get_key = "order_items.{$key}";
                    break;
                default:
                    continue 2;
            }

            if ($value['function']) {
                $get = "{$value['function']}({$distinct} {$get_key})";
            } else {
                $get = "{$distinct} {$get_key}";
            }

            $selectParts[] = "{$get} as {$value['name']}";
        }


        $selectString = 'SELECT ' . implode(',', $selectParts);


        return $selectString;
    }

    /**
     * Build join parts of query
     *
     * @param $params
     *
     * @return string
     */
    private function buildJoinPart($params)
    {
        $joins = array_merge(
            $this->buildJoinMainParts($params),
            $this->buildJoinPartsFromWhereMeta($params['where_meta']),
            $this->buildJoinPartsFromParentOrderStatus($params['parent_order_status']),
            $this->rateMultiplierNeeded ? $this->buildJoinCurrencies() : array()
        );

        $joinString = implode(' ', $joins);

        return $joinString;
    }

    /**
     * Build main parts of join
     *
     * @param $params
     * @return array
     */
    private function buildJoinMainParts($params)
    {
        $joinsMainParts = array();

        global $wpdb;

        foreach (($params['data'] + $params['where']) as $raw_key => $value) {
            $join_type = isset($value['join_type']) ? $value['join_type'] : 'INNER';
            $type      = isset($value['type']) ? $value['type'] : false;
            $key       = sanitize_key($raw_key);

            switch ($type) {
                case 'meta':
                    $joinsMainParts[ "meta_{$key}" ] = "{$join_type} JOIN {$wpdb->postmeta} AS meta_{$key} ON ( posts.ID = meta_{$key}.post_id AND meta_{$key}.meta_key = '{$raw_key}' )";
                    break;
                case 'parent_meta':
                    $joinsMainParts[ "parent_meta_{$key}" ] = "{$join_type} JOIN {$wpdb->postmeta} AS parent_meta_{$key} ON (posts.post_parent = parent_meta_{$key}.post_id) AND (parent_meta_{$key}.meta_key = '{$raw_key}')";
                    break;
                case 'order_item_meta':
                    $joinsMainParts['order_items'] = "{$join_type} JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON (posts.ID = order_items.order_id)";

                    if (! empty($value['order_item_type'])) {
                        $joinsMainParts['order_items'] .= " AND (order_items.order_item_type = '{$value['order_item_type']}')";
                    }

                    $joinsMainParts[ "order_item_meta_{$key}" ] = "{$join_type} JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_item_meta_{$key} ON " .
                        "(order_items.order_item_id = order_item_meta_{$key}.order_item_id) " .
                        " AND (order_item_meta_{$key}.meta_key = '{$raw_key}')";
                    break;
                case 'order_item':
                    $joinsMainParts['order_items'] = "{$join_type} JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON posts.ID = order_items.order_id";
                    break;
            }
        }

        return $joinsMainParts;
    }

    /**
     * Build join parts from where_meta params
     *
     * @param $whereMeta
     *
     * @return array
     */
    private function buildJoinPartsFromWhereMeta($whereMeta)
    {
        $joinsFromWhereMeta = array();

        global $wpdb;

        if (! empty($whereMeta)) {
            foreach ($whereMeta as $value) {
                if (! is_array($value)) {
                    continue;
                }
                $join_type = isset($value['join_type']) ? $value['join_type'] : 'INNER';
                $type      = isset($value['type']) ? $value['type'] : false;
                $key       = sanitize_key(is_array($value['meta_key']) ? $value['meta_key'][0] . '_array' : $value['meta_key']);

                if ('order_item_meta' === $type) {
                    $joinsFromWhereMeta['order_items']              = "{$join_type} JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON posts.ID = order_items.order_id";
                    $joinsFromWhereMeta[ "order_item_meta_{$key}" ] = "{$join_type} JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS order_item_meta_{$key} ON order_items.order_item_id = order_item_meta_{$key}.order_item_id";
                } else {
                    // If we have a where clause for meta, join the postmeta table
                    $joinsFromWhereMeta[ "meta_{$key}" ] = "{$join_type} JOIN {$wpdb->postmeta} AS meta_{$key} ON posts.ID = meta_{$key}.post_id";
                }
            }
        }

        return $joinsFromWhereMeta;
    }

    /**
     * @param   array     $parentOrderStatus
     * @return  array     $joinPartsFromParentOrderStatus
     */
    private function buildJoinPartsFromParentOrderStatus($parentOrderStatus)
    {
        $joinPartsFromParentOrderStatus = array();


        if (! empty($parentOrderStatus)) {
            global $wpdb;

            $joinPartsFromParentOrderStatus['parent'] = "LEFT JOIN {$wpdb->posts} AS parent ON posts.post_parent = parent.ID";
        }

        return $joinPartsFromParentOrderStatus;
    }

    /**
     * Build query part responsible for currencies table join
     *
     * @return array
     */
    private function buildJoinCurrencies()
    {
        global $wpdb;

        $joinCurrencies['currencies'] = "
            LEFT JOIN {$wpdb->postmeta} AS meta__order_currency 
            ON posts.ID = meta__order_currency.post_id
            AND meta__order_currency.meta_key = '_premmerce_multicurrency_order_currency_id'
            
            LEFT JOIN {$wpdb->prefix}premmerce_currencies AS currencies 
            ON (currencies.id = meta__order_currency.meta_value)
            ";

        return $joinCurrencies;
    }

    /**
     * Build WHERE part of query
     *
     * @param array     $params
     *
     * @return string   $wherePart
     */
    private function buildWherePart($params)
    {
        $orderStatus = apply_filters('woocommerce_reports_order_statuses', $params['order_status']);

        $wherePart = "WHERE posts.post_type IN ( '" . implode("','", $params['order_types']) . "' )" .
            $this->buildWherePartFromOrderStatus($orderStatus) .
            $this->buildWherePartFromParentOrderStatus($params['parent_order_status'], $orderStatus) .
            $this->buildWherePartFromFilterRange($params['filter_range']) .
            $this->buildWherePartFromWhereMeta($params['where_meta']) .
            $this->buildWherePartFromWhere($params['where']);

        return $wherePart;
    }

    /**
     * @param array    $orderStatus
     * @return string   $wherePartFromOrderStatus
     */
    private function buildWherePartFromOrderStatus($orderStatus)
    {
        $wherePartFromOrderStatus = '';

        if (! empty($orderStatus)) {
            $wherePartFromOrderStatus = "AND posts.post_status IN ( 'wc-" . implode("','wc-", $orderStatus) . "')";
        }

        return $wherePartFromOrderStatus;
    }

    /**
     * @param  array   $parentOrderStatus
     * @param          $orderStatus
     * @return string  $whereFromParentOrderStatus
     */
    private function buildWherePartFromParentOrderStatus($parentOrderStatus, $orderStatus)
    {
        $whereFromParentOrderStatus = '';


        if (! empty($parentOrderStatus)) {
            if ($orderStatus) {
                $whereFromParentOrderStatus = " AND ( parent.post_status IN ( 'wc-" . implode("','wc-", $parentOrderStatus) . "') OR parent.ID IS NULL ) ";
            } else {
                $whereFromParentOrderStatus = " AND parent.post_status IN ( 'wc-" . implode("','wc-", $parentOrderStatus) . "') ";
            }
        }


        return $whereFromParentOrderStatus;
    }

    /**
     * @param           $filterRange
     *
     * @return string   $wherePartFromFilterRange
     */
    private function buildWherePartFromFilterRange($filterRange)
    {
        $wherePartFromFilterRange = '';

        if ($filterRange) {
            $dates = $this->getDatesForQuery();
            $wherePartFromFilterRange = "
				AND 	posts.post_date >= '" . date('Y-m-d H:i:s', $dates['start']) . "'
				AND 	posts.post_date < '" . date('Y-m-d H:i:s', strtotime('+1 DAY', $dates['end'])) . "'
			";
        }

        return $wherePartFromFilterRange;
    }

    /**
     * Get start and end dates to use in query
     *
     * @return array
     */
    private function getDatesForQuery()
    {
        if (class_exists($this->reportClassName)) {
            $report = new $this->reportClassName;
        } else {
            $report = new \WC_Admin_Report();
        }

        $report->calculate_current_range($this->getCurrentRange());
        $dates = array(
            'start' => $report->start_date,
            'end'   => $report->end_date
        );
        
        return $dates;
    }

    /**
     * Retrieve current range from get or return default
     *
     * @return string   $currentRange
     */
    private function getCurrentRange()
    {
        $defaultRange = in_array($this->reportName, array('taxes-by-code', 'taxes-by-date')) ? 'last_month' : '7day';

        $currentRange = ! empty($_GET['range']) ? sanitize_text_field(wp_unslash($_GET['range'])) : $defaultRange;

        if (! in_array($currentRange, array( 'custom', 'year', 'last_month', 'month', '7day' ))) {
            $currentRange = $defaultRange;
        }

        return $currentRange;
    }

    /**
     * @param  array     $whereMeta
     * @return string
     */
    private function buildWherePartFromWhereMeta($whereMeta)
    {
        $wherePartFromWhereMeta = '';

        if (! empty($whereMeta)) {
            $relation = isset($whereMeta['relation']) ? $whereMeta['relation'] : 'AND';

            $wherePartFromWhereMeta = ' AND (';

            foreach ($whereMeta as $index => $value) {
                if (! is_array($value)) {
                    continue;
                }

                $key = sanitize_key(is_array($value['meta_key']) ? $value['meta_key'][0] . '_array' : $value['meta_key']);

                if (strtolower($value['operator']) == 'in' || strtolower($value['operator']) == 'not in') {
                    if (is_array($value['meta_value'])) {
                        $value['meta_value'] = implode("','", $value['meta_value']);
                    }

                    if (! empty($value['meta_value'])) {
                        $where_value = "{$value['operator']} ('{$value['meta_value']}')";
                    }
                } else {
                    $where_value = "{$value['operator']} '{$value['meta_value']}'";
                }

                if (! empty($where_value)) {
                    if ($index > 0) {
                        $wherePartFromWhereMeta .= ' ' . $relation;
                    }

                    if (isset($value['type']) && 'order_item_meta' === $value['type']) {
                        if (is_array($value['meta_key'])) {
                            $wherePartFromWhereMeta .= " ( order_item_meta_{$key}.meta_key   IN ('" . implode("','", $value['meta_key']) . "')";
                        } else {
                            $wherePartFromWhereMeta .= " ( order_item_meta_{$key}.meta_key   = '{$value['meta_key']}'";
                        }

                        $wherePartFromWhereMeta .= " AND order_item_meta_{$key}.meta_value {$where_value} )";
                    } else {
                        if (is_array($value['meta_key'])) {
                            $wherePartFromWhereMeta .= " ( meta_{$key}.meta_key   IN ('" . implode("','", $value['meta_key']) . "')";
                        } else {
                            $wherePartFromWhereMeta .= " ( meta_{$key}.meta_key   = '{$value['meta_key']}'";
                        }

                        $wherePartFromWhereMeta .= " AND meta_{$key}.meta_value {$where_value} )";
                    }
                }
            }

            $wherePartFromWhereMeta .= ')';
        }

        return $wherePartFromWhereMeta;
    }

    /**
     * @param $where
     * @return string
     */
    private function buildWherePartFromWhere($where)
    {
        $wherePartFromWhere = '';

        if (! empty($where)) {
            foreach ($where as $value) {
                if (strtolower($value['operator']) == 'in' || strtolower($value['operator']) == 'not in') {
                    if (is_array($value['value'])) {
                        $value['value'] = implode("','", $value['value']);
                    }

                    if (! empty($value['value'])) {
                        $where_value = "{$value['operator']} ('{$value['value']}')";
                    }
                } else {
                    $where_value = "{$value['operator']} '{$value['value']}'";
                }

                if (! empty($where_value)) {
                    $wherePartFromWhere .= " AND {$value['key']} {$where_value}";
                }
            }
        }

        return $wherePartFromWhere;
    }
}
