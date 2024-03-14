<?php

namespace platy\etsy;

class ShopsService extends DataService {
    private $cache;

    private static  $instance = null;
    private function __construct() {
        parent::__construct(\Platy_Syncer_Etsy::SHOP_TABLE_NAME);
        $this->cache = false;
    }

    public static function get_instance() {
        if(ShopsService::$instance == null) {
            ShopsService::$instance = new ShopsService();
        }
        return ShopsService::$instance;
    }

    public function get_current_shop_id(){
        return $this->get_current_shop()['id'];
    }

    public function get_current_shop_name(){
        return $this->get_current_shop()['name'];
    }

    public function get_current_shop(){
        
        if($this->cache !== false) {
            return $this->cache;
        }

        $shop = $this->get_current_shop_raw();
        $this->cache = $shop;
        return $shop;
    }

    private function get_current_shop_raw() {
        global $table_prefix, $wpdb;
        
        $full_table_name = $table_prefix . $this->tbl_name;

        if($wpdb->get_var( "show tables like '$full_table_name'" ) != $full_table_name){
            throw new NoCurrentShopException();
        }

        $shops = $this->get_shops();
        if(count($shops)==1){
            return $shops[0];
        }
        $id = get_option( EtsyDataService::option_prefix . EtsyDataService::DEFAULT_SHOP_OPTION_NAME, null);
        if(!empty($id)){
            $shop_tbl = $this->tbl_name;
            $shops = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}$shop_tbl WHERE id=$id", ARRAY_A);
            if(!empty($shops)) {
                return $shops[0];
            }
        }
        throw new NoCurrentShopException();
    }

    public function get_shops(){
        global $wpdb;
        $shop_tbl = $this->tbl_name;
        $full_table_name = "{$wpdb->prefix}$shop_tbl";
        if($wpdb->get_var( "show tables like '$full_table_name'" ) != $full_table_name){
            return [];
        }
        $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}$shop_tbl", ARRAY_A);
        return $results;
    }

    public function get_shop($shop_id){
        if(empty($shop_id)) {
            throw new NoSuchShopException($shop_id);
        }
        
        $shops = $this->get_shops();
        foreach($shops as $shop) {
            if($shop['id'] == $shop_id) {
                return $shop;
            }
        }
        throw new NoSuchShopException($shop_id);
    }
    
    public function save_shop($shop){
        global $wpdb;
        $shop_tbl = $this->tbl_name;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$shop_tbl 
            WHERE id={$shop['id']}");
        if(count($results) == 0){
            $wpdb->insert($wpdb->prefix . $shop_tbl,$shop);
        }else{
            $wpdb->update($wpdb->prefix . $shop_tbl,$shop, ['id' => $shop['id']]);

        }
        $this->cache = false;
    }

    public function save_default_shop($id){
        if(empty($id)) throw new EtsySyncerException("Trying to save an empty shop id");
        update_option( EtsyDataService::option_prefix .  EtsyDataService::DEFAULT_SHOP_OPTION_NAME, $id);
        $this->cache = false;
    }

    public function get_token_credentials(){
        $shop = $this->get_current_shop();
        $oauth2_token = "";
        $oauth2_refresh_token = "";
        if(!empty($shop['oauth2_token'])) {
            $oauth2_token = $shop['oauth2_token'];
        }
        if(!empty($shop['oauth2_refresh_token'])) {
            $oauth2_refresh_token = $shop['oauth2_refresh_token'];
        }
        return ['oauth2_token' => $oauth2_token, 'oauth2_refresh_token' =>  $oauth2_refresh_token];
    }

    public function get_shop_legacy_token() {
        $shop = $this->get_current_shop();
        if(isset($shop['identifier'])) {
            return $shop['identifier'];
        }else {
            return null;
        }
    }

    public function has_legacty_token_only($shop){
        return empty($shop['oauth2_token']);
    
    }

    public function refresh_token_expired($shop){
		$current_time = time();
        $last_use = empty($shop['last_ouath2_use']) ? $current_time : $shop['last_ouath2_use'];
        return $current_time - $last_use > 90*24*3600;
    }

    public function has_current_shop(){
        try{
            $this->get_current_shop();
            return true;
        }catch(NoCurrentShopException $e){
  
        }
          return false;
      }
  
    public function is_shop_authenticated(){
        try{
            $shop = $this->get_current_shop();
            return !$this->has_legacty_token_only($shop) &&
                !$this->refresh_token_expired($shop);
        }catch(NoCurrentShopException $e){

        }
        return false;
    }
}