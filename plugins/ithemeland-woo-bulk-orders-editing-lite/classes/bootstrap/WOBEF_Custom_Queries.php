<?php

namespace wobef\classes\bootstrap;

use wobef\classes\repositories\Order;

class WOBEF_Custom_Queries
{
    public function init()
    {
        add_filter('posts_where', [$this, 'general_column_filter'], 10, 2);
        add_filter('posts_where', [$this, 'meta_filter'], 10, 2);
        add_filter('posts_where', [$this, 'product_filter'], 10, 2);
        add_filter('posts_where', [$this, 'product_taxonomy_filter'], 10, 2);
        add_filter('posts_where', [$this, 'coupon_used_filter'], 10, 2);
    }

    public function general_column_filter($where, $wp_query)
    {
        global $wpdb;
        if ($search_term = $wp_query->get('wobef_general_column_filter')) {
            if (is_array($search_term) && count($search_term) > 0) {
                foreach ($search_term as $item) {
                    $field = esc_sql($item['field']);
                    $value = (is_array($item['value'])) ? esc_sql($item['value']) : trim(esc_sql($item['value']));
                    switch ($item['operator']) {
                        case 'like':
                            $custom_where = "(posts.{$field} LIKE '%{$value}%')";
                            break;
                        case 'exact':
                            $custom_where = "(posts.{$field} = '{$value}')";
                            break;
                        case 'not':
                            $custom_where = "(posts.{$field} != '{$value}')";
                            break;
                        case 'begin':
                            $custom_where = "(posts.{$field} LIKE '{$value}%')";
                            break;
                        case 'end':
                            $custom_where = "(posts.{$field} LIKE '%{$value}')";
                            break;
                        case 'in':
                            $custom_where = "(posts.{$field} IN ({$value}))";
                            break;
                        case 'or':
                            if (is_array($value)) {
                                $custom_where = "(";
                                $i = 1;
                                foreach ($value as $value_item) {
                                    $custom_where .= "(posts.{$field} = '{$value_item}')";
                                    if (count($value) > $i) {
                                        $custom_where .= " OR ";
                                    }
                                    $i++;
                                }
                                $custom_where .= ")";
                            }
                            break;
                        case 'not_in':
                            $custom_where = "(posts.{$field} NOT IN ({$value}))";
                            break;
                        case 'between':
                            $value = (is_numeric($value[1])) ? "{$value[0]} AND {$value[1]}" : "'{$value[0]}' AND '{$value[1]}'";
                            $custom_where = "(posts.{$field} BETWEEN {$value})";
                            break;
                        case '>':
                            $custom_where = "(posts.{$field} > {$value})";
                            break;
                        case '<':
                            $custom_where = "(posts.{$field} < {$value})";
                            break;
                        case '>_with_quotation':
                            $custom_where = "(posts.{$field} > '{$value}')";
                            break;
                        case '<_with_quotation':
                            $custom_where = "(posts.{$field} < '{$value}')";
                            break;
                    }

                    $order_repository = new Order();
                    $type = (isset($item['type'])) ? esc_sql($item['type']) : 'shop_order';
                    $orders_ids = $order_repository->get_ids_by_custom_query('', $custom_where, $type);
                    $ids = (!empty($orders_ids)) ? $orders_ids : '0';
                    $where .= " AND ({$wpdb->posts}.ID IN ({$ids}))";
                }
            }
        }

        return $where;
    }

    public function meta_filter($where, $wp_query)
    {
        global $wpdb;
        $order_repository = new Order();
        $join = "LEFT JOIN $wpdb->postmeta AS postmeta ON (posts.ID = postmeta.post_id)";
        if ($search_term = $wp_query->get('wobef_meta_filter')) {
            if (is_array($search_term) && count($search_term) > 0) {
                foreach ($search_term as $item) {
                    $key = esc_sql($item['key']);
                    $value = esc_sql($item['value']);
                    switch ($item['operator']) {
                        case 'like':
                            if (is_array($value)) {
                                $custom_where = "(";
                                $i = 1;
                                foreach ($value as $value_item) {
                                    $custom_where .= "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '%{$value_item}%')";
                                    if (count($value) > $i) {
                                        $custom_where .= " OR ";
                                    }
                                    $i++;
                                }
                                $custom_where .= ")";
                            } else {
                                $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '%{$value}%')";
                            }
                            break;
                        case 'exact':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value = '{$value}')";
                            break;
                        case 'not':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value != '{$value}')";
                            break;
                        case 'begin':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '{$value}%')";
                            break;
                        case 'end':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value LIKE '%{$value}')";
                            break;
                        case 'in':
                            $custom_where = "(postmeta.meta_key = '{$key}' AND postmeta.meta_value IN ({$value}))";
                            break;
                        case 'between':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value BETWEEN {$value[0]} AND {$value[1]})";
                            break;
                        case 'between_with_quotation':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value BETWEEN '{$value[0]}' AND '{$value[1]}')";
                            break;
                        case '<=':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value <= {$value})";
                            break;
                        case '>=':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value >= {$value})";
                            break;
                        case '<=_with_quotation':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value <= '{$value}')";
                            break;
                        case '>=_with_quotation':
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value >= '{$value}')";
                            break;
                        default:
                            $custom_where = "(postmeta.meta_key = '$key' AND postmeta.meta_value = '{$value}')";
                            break;
                    }
                    $type = (isset($item['type'])) ? esc_sql($item['type']) : 'shop_order';
                    $orders_ids = $order_repository->get_ids_by_custom_query($join, $custom_where, $type);
                    $ids = (!empty($orders_ids)) ? $orders_ids : '0';
                    $where .= " AND ({$wpdb->posts}.ID IN ({$ids}))";
                }
            }
        }
        return $where;
    }

    public function coupon_used_filter($where, $wp_query)
    {
        global $wpdb;
        $order_repository = new Order();
        $order_items = $wpdb->prefix . "woocommerce_order_items";
        $join = "LEFT JOIN {$order_items} AS items ON (posts.ID = items.order_id)";
        if ($search_term = $wp_query->get('wobef_coupon_used_filter')) {
            if (is_array($search_term) && count($search_term) > 0) {
                foreach ($search_term as $item) {
                    $custom_where = "(items.order_item_type = 'coupon')";
                    $value = esc_sql($item['value']);
                    switch ($value) {
                        case 'yes':
                            $query_type = 'IN';
                            break;
                        default:
                            $query_type = 'NOT IN';
                            break;
                    }
                    $orders_ids = $order_repository->get_ids_by_custom_query($join, $custom_where);
                    $ids = (!empty($orders_ids)) ? $orders_ids : '0';
                    $where .= " AND ({$wpdb->posts}.ID {$query_type} ({$ids}))";
                }
            }
        }
        return $where;
    }

    public function product_filter($where, $wp_query)
    {
        global $wpdb;
        $order_repository = new Order();
        $order_items = $wpdb->prefix . "woocommerce_order_items";
        $order_itemmeta = $wpdb->prefix . "woocommerce_order_itemmeta";
        $join = "LEFT JOIN {$order_items} AS items ON (posts.ID = items.order_id)";
        $join .= " LEFT JOIN {$order_itemmeta} AS itemmeta ON (items.order_item_id = itemmeta.order_item_id)";
        if ($search_term = $wp_query->get('wobef_product_filter')) {
            if (is_array($search_term) && count($search_term) > 0) {
                foreach ($search_term as $item) {
                    $custom_where = $this->set_custom_where_by_product_id($item['value'], $item['operator']);
                    $orders_ids = $order_repository->get_ids_by_custom_query($join, $custom_where);
                    $ids = (!empty($orders_ids)) ? $orders_ids : '0';
                    $where .= " AND ({$wpdb->posts}.ID IN ({$ids}))";
                }
            }
        }
        return $where;
    }

    public function product_taxonomy_filter($where, $wp_query)
    {
        global $wpdb;
        $order_repository = new Order();
        $order_items = $wpdb->prefix . "woocommerce_order_items";
        $order_itemmeta = $wpdb->prefix . "woocommerce_order_itemmeta";
        $join = "LEFT JOIN {$order_items} AS items ON (posts.ID = items.order_id)";
        $join .= " LEFT JOIN {$order_itemmeta} AS itemmeta ON (items.order_item_id = itemmeta.order_item_id)";
        if ($search_term = $wp_query->get('wobef_product_taxonomy_filter')) {
            if (is_array($search_term) && count($search_term) > 0) {
                foreach ($search_term as $item) {
                    if (!empty($item['value']) && is_array($item['value']) && isset($item['value'][0])) {
                        switch ($item['operator']) {
                            case 'or':
                                $tax_query['relation'] = 'OR';
                                $operator = 'IN';
                                break;
                            case 'and':
                                $tax_query['relation'] = 'AND';
                                $operator = 'IN';
                                break;
                            default:
                                $tax_query['relation'] = 'AND';
                                $operator = 'NOT IN';
                                break;
                        }

                        if (!empty($item['taxonomy'])) {
                            if (is_array($item['value']) && !empty($item['value'])) {
                                foreach ($item['value'] as $value_item) {
                                    $tax_query[] = [
                                        'taxonomy' => esc_sql($item['taxonomy']),
                                        'field' => 'term_id',
                                        'terms' => intval($value_item),
                                        'operator' => $operator
                                    ];
                                }
                            }
                        } else {
                            foreach ($item['value'] as $term) {
                                $taxonomy = explode('__', $term);
                                if (!empty($taxonomy[0])) {
                                    $tax_query[] = [
                                        'taxonomy' => esc_sql($taxonomy[0]),
                                        'field' => 'term_id',
                                        'terms' => intval($taxonomy[1]),
                                        'operator' => $operator
                                    ];
                                }
                            }
                        }

                        $products = new \WP_Query([
                            'post_type' => ['product', 'product_variation'],
                            'posts_per_page' => -1,
                            'post_status' => 'any',
                            'fields' => 'ids',
                            'tax_query' => $tax_query
                        ]);

                        $products_id = (!empty($products->posts)) ? $products->posts : [];
                        $custom_where = $this->set_custom_where_by_product_id($products_id, $item['operator']);
                        $orders_ids = $order_repository->get_ids_by_custom_query($join, $custom_where);
                        $ids = (!empty($orders_ids)) ? $orders_ids : '0';
                        $where .= " AND ({$wpdb->posts}.ID IN ({$ids}))";
                    }
                }
            }
        }
        return $where;
    }

    private function set_custom_where_by_product_id($products_id, $operator)
    {
        global $wpdb;
        $where = "";
        if (is_array($products_id) && !empty($products_id)) {
            $products_id = esc_sql($products_id);
            if ($operator == 'or') {
                $i = 1;
                foreach ($products_id as $product_id) {
                    if ($i != 1) {
                        $where .= " OR ";
                    }
                    $product_id_array = explode('__', $product_id);
                    $product_id = (is_array($product_id_array) && !empty($product_id_array[1])) ? $product_id_array[1] : $product_id;
                    $where .= "((itemmeta.meta_key = '_product_id' AND itemmeta.meta_value = {$product_id}) OR (itemmeta.meta_key = '_variation_id' AND itemmeta.meta_value = {$product_id}))";
                    $i++;
                }
            } else {
                $operator_in_query = ($operator == 'and') ? 'IN' : 'NOT IN';
                $just_variation = true;
                $just_product = true;
                $products_array = [];
                $variations_array = [];
                $i = 1;
                foreach ($products_id as $product_id) {
                    $product_id_array = explode('__', $product_id);
                    if (is_array($product_id_array) && isset($product_id_array[0]) && isset($product_id_array[1])) {
                        if ($product_id_array[0] == 0) {
                            // is product
                            $just_variation = false;
                            $products_array[] = $product_id_array[1];
                        } else {
                            $just_product = false;
                            $variations_array[] = $product_id_array[1];
                        }
                    } else {
                        // is product
                        $just_variation = false;
                        $products_array[] = $product_id;
                    }
                    $i++;
                }

                $product_string = (!empty($products_array)) ? str_replace(',0', '', implode(',', array_unique($products_array))) : '';
                $variation_string = (!empty($variations_array)) ? str_replace(',0', '', implode(',', array_unique($variations_array))) : '';

                if ($just_variation) {
                    $where .= "(itemmeta.meta_key = '_variation_id' AND itemmeta.meta_value {$operator_in_query} ({$variation_string}))";
                } else if ($just_product) {
                    $where .= "(itemmeta.meta_key = '_product_id' AND itemmeta.meta_value {$operator_in_query} ({$product_string}))";
                } else {
                    $order_itemmeta = $wpdb->prefix . "woocommerce_order_itemmeta";
                    $order_items = $wpdb->prefix . "woocommerce_order_items";
                    $order_item_ids = [];
                    $order_item_ids2 = [];
                    $item_ids = $wpdb->get_results("SELECT DISTINCT items.order_id FROM {$order_items} items JOIN {$order_itemmeta} item1 ON (items.order_item_id = item1.order_item_id) WHERE (item1.meta_key = '_variation_id' AND item1.meta_value IN ({$variation_string}))", ARRAY_A);
                    $item_ids2 = $wpdb->get_results("SELECT DISTINCT items.order_id FROM {$order_items} items JOIN {$order_itemmeta} item1 ON (items.order_item_id = item1.order_item_id) WHERE (item1.meta_key = '_product_id' AND item1.meta_value IN ({$product_string}))", ARRAY_A);
                    if (!empty($item_ids)) {
                        foreach ($item_ids as $item_id) {
                            if (isset($item_id['order_id'])) {
                                $order_item_ids[] = $item_id['order_id'];
                            }
                        }
                    }
                    if (!empty($item_ids2)) {
                        foreach ($item_ids2 as $item_id2) {
                            if (isset($item_id2['order_id'])) {
                                $order_item_ids2[] = $item_id2['order_id'];
                            }
                        }
                    }

                    $item_ids_string = (is_array($order_item_ids) && is_array($order_item_ids2)) ? implode(',', array_intersect($order_item_ids, $order_item_ids2)) : '0';
                    $item_ids_string = !empty($item_ids_string) ? $item_ids_string : '0';
                    $where .= "(items.order_id {$operator_in_query} ({$item_ids_string}))";
                }
            }
        } else {
            $where = "(posts.ID = 0)";
        }

        return $where;
    }
}
