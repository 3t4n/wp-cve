<?php

namespace platy\etsy;

interface EtsyItem {
    public function get_item_id();
    public function get_etsy_id();
    public function get_shop_id();
}