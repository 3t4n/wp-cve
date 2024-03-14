<?php
namespace platy\etsy;


class EtsyItemSyncer extends EtsySyncer {

    private $etsy_item;

    public function __construct($etsy_item) {
        parent::__construct($etsy_item->get_shop_id());
        $this->etsy_item = $etsy_item;
    }

    public function get_etsy_id () {
        return $this->etsy_item->get_etsy_id();
    }

    public function get_item_id () {
        return $this->etsy_item->get_item_id();
    }

}