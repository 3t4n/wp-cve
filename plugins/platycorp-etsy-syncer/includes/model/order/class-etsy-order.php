<?php

namespace platy\etsy\orders;

class EtsyOrder {

    private $order;
    function __construct($id = 0){
        $this->order = new \WC_Order($id);
    }

    public function __call($name, $arguments) {
        if(!empty($this->order)) {
            return \call_user_func_array([$this->order, $name], $arguments);
        }
    }

    public function can_reduce_order_stock($can_reduce, $order) {
        return true;
    }

    function get_etsy_discount_item(){
        $items = $this->get_items('coupon');
        foreach($items as $item){
            return new EtsyOrderItem($item);
        }
        throw new NoSuchEtsyItemException('coupon', "");

    }

    function get_etsy_order_item($transaction_id){
        $items = $this->order->get_items();
        foreach($items as $item){
            if(wc_get_order_item_meta($item->get_id(), "_etsy_transaction_id", true) == $transaction_id){
                if($item->get_type() == 'line_item'){
                    return new EtsyOrderItem($item);
                }
            }
            
        }
        throw new NoSuchEtsyItemException('line_item', $transaction_id);
    }

    public function get_etsy_order_tax_item($tax_name){
        $items = $this->get_items('tax');
        foreach($items as $item){
            if($item->get_name() == $tax_name) {
                return new EtsyOrderItem($item);
            }
        }
        throw new NoSuchEtsyItemException('tax', "");
    }

    public function get_etsy_order_shipping_item($receipt_shipping_id){
        $items = $this->get_items('shipping');
        foreach($items as $item){
            if(wc_get_order_item_meta($item->get_id(), "etsy_receipt_shipping_id", true) == $receipt_shipping_id){
                return new EtsyOrderItem($item);
            }
        }
        throw new NoSuchEtsyItemException('shipping', "");
    }

    public function get_order_number(){
        $receit_id = $this->order->get_meta("etsy_receipt_id", true);
        return empty($receit_id) ? $this->order->get_id() : $receit_id;
    }

}