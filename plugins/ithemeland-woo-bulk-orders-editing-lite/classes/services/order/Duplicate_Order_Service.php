<?php

namespace wobef\classes\services\order;

use wobef\classes\repositories\Order;

class Duplicate_Order_Service
{
    private $new_order;
    private $original_order;

    public function duplicate($order_ids, $number = 1)
    {
        if (!is_array($order_ids) || empty($order_ids) || !is_numeric($number)) {
            return false;
        }

        $order_repository = new Order();

        foreach ($order_ids as $order_id) {
            $original_order = $order_repository->get_order(intval($order_id));
            if (!($original_order instanceof \WC_Order)) {
                return false;
            }
            $this->original_order = $original_order;

            for ($i = 1; $i <= intval($number); $i++) {
                $new_order_id = $this->create_order();
                if (is_wp_error($new_order_id)) {
                    return false;
                }
                $this->new_order = new \WC_Order($new_order_id);
                $this->duplicate_order_meta_fields();
                $this->duplicate_line_items();
                $this->duplicate_shipping_items();
                $this->duplicate_coupons();
                $this->add_order_note();
                $this->new_order->calculate_taxes();
                $this->new_order->save();
            }
        }

        return true;
    }

    private function create_order()
    {
        $post_date = $this->original_order->get_date_created()->date('Y-m-d H:i:s');
        $post_modified = $this->original_order->get_date_modified()->date('Y-m-d H:i:s');
        $order_data =  array(
            'post_author' => $this->original_order->get_customer_id(),
            'post_date' => $post_date,
            'post_date_gmt' => $post_date,
            'post_type' => 'shop_order',
            'post_title' => __('Duplicated Order', 'woocommerce'),
            'post_status' => 'wc-' . $this->original_order->get_status(),
            'ping_status' => 'closed',
            'post_modified' => $post_modified,
            'post_modified_gmt' => $post_modified
        );

        $new_order_id = wp_insert_post($order_data, true);
        return $new_order_id;
    }

    private function duplicate_order_meta_fields()
    {
        $new_order_id = $this->new_order->get_id();
        $original_order_id = $this->original_order->get_id();

        $original_meta_fields = get_post_meta($original_order_id, '', true);
        if (!empty($original_meta_fields) && is_array($original_meta_fields)) {
            foreach ($original_meta_fields as $meta_key => $meta_value) {
                if (isset($meta_value[0])) {
                    update_post_meta($new_order_id, $meta_key, $meta_value[0]);
                }
            }
        }
    }

    private function duplicate_line_items()
    {
        foreach ($this->original_order->get_items() as $originalOrderItem) {
            $itemName = $originalOrderItem['name'];
            $qty = $originalOrderItem['qty'];
            $lineTotal = $originalOrderItem['line_total'];
            $lineTax = $originalOrderItem['line_tax'];
            $productID = $originalOrderItem['product_id'];

            $item_id = wc_add_order_item($this->new_order->get_id(), array(
                'order_item_name'       => $itemName,
                'order_item_type'       => 'line_item'
            ));

            wc_add_order_item_meta($item_id, '_qty', $qty);
            wc_add_order_item_meta($item_id, '_tax_class', $originalOrderItem['tax_class']);
            wc_add_order_item_meta($item_id, '_product_id', $productID);
            wc_add_order_item_meta($item_id, '_variation_id', $originalOrderItem['variation_id']);
            wc_add_order_item_meta($item_id, '_line_subtotal', wc_format_decimal($lineTotal));
            wc_add_order_item_meta($item_id, '_line_total', wc_format_decimal($lineTotal));
            wc_add_order_item_meta($item_id, '_line_tax', wc_format_decimal($lineTax));
            wc_add_order_item_meta($item_id, '_line_subtotal_tax', wc_format_decimal($originalOrderItem['line_subtotal_tax']));
        }
    }

    private function duplicate_shipping_items()
    {
        $original_order_shipping_items = $this->original_order->get_items('shipping');

        foreach ($original_order_shipping_items as $original_order_shipping_item) {
            $item_id = wc_add_order_item($this->new_order->get_id(), array(
                'order_item_name' => $original_order_shipping_item['name'],
                'order_item_type' => 'shipping'
            ));
            if ($item_id) {
                wc_add_order_item_meta($item_id, 'method_id', $original_order_shipping_item['method_id']);
                wc_add_order_item_meta($item_id, 'cost', wc_format_decimal($original_order_shipping_item['cost']));
            }
        }
    }

    private function duplicate_coupons()
    {
        $original_order_coupons = $this->original_order->get_items('coupon');
        foreach ($original_order_coupons as $original_order_coupon) {
            $item_id = wc_add_order_item($this->new_order->get_id(), array(
                'order_item_name' => $original_order_coupon['name'],
                'order_item_type' => 'coupon'
            ));
            if ($item_id) {
                wc_add_order_item_meta($item_id, 'discount_amount', $original_order_coupon['discount_amount']);
            }
        }
    }

    private function add_order_note()
    {
        $args = array(
            'post_id' => $this->original_order->get_id(),
            'orderby' => 'comment_ID',
            'order' => 'ASC',
            'approve' => 'approve',
            'type' => 'order_note'
        );

        remove_filter('comments_clauses', array('WC_Comments', 'exclude_order_comments'), 10, 1);

        $order_notes = get_comments($args);

        if (!empty($order_notes) && is_array($order_notes)) {
            foreach ($order_notes as $order_note) {
                if ($order_note instanceof \WP_Comment) {
                    $comment_array = $order_note->to_array();
                    $original_comment_id = $comment_array['comment_ID'];
                    unset($comment_array['comment_ID']);
                    unset($comment_array['children']);
                    unset($comment_array['populated_children']);
                    unset($comment_array['post_fields']);
                    $comment_array['comment_post_ID'] = $this->new_order->get_id();
                    $new_comment_id = wp_insert_comment($comment_array);

                    if ($new_comment_id) {
                        $original_comment_meta = get_comment_meta($original_comment_id);
                        if (!empty($original_comment_meta) && is_array($original_comment_meta)) {
                            foreach ($original_comment_meta as $meta_key => $meta_value) {
                                if (isset($meta_value[0])) {
                                    add_comment_meta($new_comment_id, $meta_key, $meta_value[0]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
