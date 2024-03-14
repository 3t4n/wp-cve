<?php

namespace WPPayForm\App\Models;

use WPPayForm\Framework\Support\Arr;

/**
 * Order Items Model
 * @since 1.0.0
 */
class OrderItem extends Model
{
    protected $table = 'wpf_order_items';

    public function createOrder($item)
    {
        $insertItem = Arr::only($item, array(
            'form_id',
            'submission_id',
            'type',
            'parent_holder',
            'billing_interval',
            'item_name',
            'quantity',
            'item_price',
            'line_total',
            'item_meta',
            'created_at',
            'updated_at'
        ));

        $orderInsert = $this->create($insertItem);
        $insertId = $orderInsert->id;
        if ($metas = Arr::get($item, 'meta')) {
            foreach ($metas as $metaKey => $value) {
                $this->updateMeta($insertId, $metaKey, $value);
            }
        }
        return $insertId;
    }

    public static function migrate()
    {
        global $wpdb;

        $stat = $wpdb->get_results("DESCRIBE {$wpdb->prefix}wpf_order_items");

        $message = "item_meta column added successfully!";
        foreach ($stat as $column) {
            if ($column->Field == 'item_meta') {
                $message = "item_meta column already altered!";
                return array(
                    "status" => false,
                    "message" => $message
                );
            }
        }

        $tableName = $wpdb->prefix . 'wpf_order_items';
        $sql = "ALTER TABLE $tableName
            ADD item_meta text";

        $upgrade = $wpdb->query($sql);

        if ($upgrade && $wpdb->last_error === '') {
            update_option('wppayform_order_items_meta_migrate', 'yes', 'no');
        } else {
            $message = $wpdb->last_error;
            update_option('wppayform_order_items_meta_migrate', 'no', 'no');
        }

        return array(
            "status" => $upgrade,
            "message" => $message
        );
    }

    public function getOrderItems($submissionId)
    {
        $orderItems = $this->where('submission_id', $submissionId)
            ->where('type', '!=', 'discount')
            ->get();
        foreach ($orderItems as $orderItem) {
            if ($orderItem->type == 'tax_line' && $orderItem->item_price > 0) {
                $orderItem->quantity = $orderItem->line_total / $orderItem->item_price;
            }
        }
        return apply_filters('wppayform/order_items', $orderItems, $submissionId);
    }

    public function getTaxOrderItems($submissionId)
    {
        $orderItems = $this->where('submission_id', $submissionId)
            ->where('type', 'tax_line')
            ->get();

        foreach ($orderItems as $orderItem) {
            if ($orderItem->item_price > 0) {
                $orderItem->quantity = $orderItem->line_total / $orderItem->item_price;
                $orderItem->taxRate = number_format(($orderItem->line_total / $orderItem->item_price) * 100, 2);
            }
        }

        return apply_filters('wppayform/tax_items', $orderItems, $submissionId);
    }

    public function getSingleOrderItems($submissionId)
    {
        $orderItems = $this->where('submission_id', $submissionId)
            ->whereIn('type', ['single', 'signup_fee'])
            ->get();
        foreach ($orderItems as $orderItem) {
            $orderItem->item_meta = maybe_unserialize(Arr::get($orderItem, 'item_meta'));
        }
        return apply_filters('wppayform/order_items', $orderItems, $submissionId);
    }

    public function updateMeta($optionId, $key, $value)
    {
        return (new Meta())->updateOrderMeta($this->table, $optionId, $key, $value);
    }

    // public function getMetas($optionId)
    // {
    //     $metas = (new Meta())->where('meta_group', $this->table)
    //         ->where('option_id', $optionId)
    //         ->get();
    //     $formatted = array();
    //     foreach ($metas as $meta) {
    //         $formatted[$meta->meta_key] = maybe_unserialize($meta->meta_value);
    //     }
    //     return (object) $formatted;
    // }

    public function getDiscountTotal($submissionId)
    {
        $discounts = $this->getDiscountItems($submissionId);
        $total = 0;
        foreach ($discounts as $discount) {
            $total += $discount->line_total;
        }

        return $total;
    }

    public function getDiscountItems($submissionId)
    {
        $discounts = $this->where('submission_id', intval($submissionId))
            ->where('type', 'discount')
            ->get();
        return $discounts;
    }
}
