<?php

defined( 'ABSPATH' ) or exit;

if ( ! class_exists( 'PW_Gift_Card_Item_Data' ) ) :

class PW_Gift_Card_Item_Data {

    public $wc_price_args = array();
    public $recipient = '';
    public $recipient_name = '';
    public $from = '';
    public $message = '';
    public $amount = '';
    public $gift_card_number = '';
    public $preview = false;
    public $product = false;
    public $product_id = 0;
    public $variation_id = 0;
    public $parent_product = false;
    public $order = false;
    public $redeem_url = '';
    public $design = false;

    // For compatibility with a bug in "WooCommerce PDF Invoice Builder" by RedNao
    function get_id() {
        return 0;
    }

    // For compatibility with a bug in "MPesa For WooCommerce" by Osen Concepts Kenya
    function has_downloadable_item() {
        return false;
    }
}

endif;
