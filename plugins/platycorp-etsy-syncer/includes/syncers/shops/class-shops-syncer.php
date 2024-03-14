<?php
namespace platy\etsy;


class EtsyShopsSyncer extends EtsySyncer {

    public function __construct() {
        parent::__construct();
 
    }

    public function get_etsy_shop_by_id($shop_id){
        try{
            $shop = $this->api->getShop(array('params' => array('shop_id' => trim($shop_id))));
            return $shop;
        }catch(\RuntimeException $e){
            throw new NoSuchShopException($shop_id);
        }
    }

    public function get_etsy_shop_by_name($shop_name){
        $shop = $this->api->findShops(array('data' => array('shop_name' => trim($shop_name))));
        if(\count( $shop['results']) == 0) {
            throw new NoSuchShopException($shop_name);
        }
        return $shop['results'][0];
    }

}