<?php

namespace platy\etsy\orders;

class EtsyOrderShippingItem extends \WC_Order_Item_Shipping{
    private $receipt_shipping_id;
    public function __construct($item_id = 0, $receipt_shipping_id = 0 ) {
        parent::__construct( $item_id );
        $this->receipt_shipping_id = $receipt_shipping_id;
    }

    public function save(){
        $id = parent::save();
        wc_update_order_item_meta($id, "etsy_receipt_shipping_id", $this->receipt_shipping_id);

    }
}