<?php

namespace platy\etsy;
use platy\etsy\logs\PlatySyncerLogger;
use platy\etsy\NoSuchPostMetaException;
class EtsyDataService{
    const option_prefix = "platy_etsy_";
    const DEFAULT_SHOP_OPTION_NAME = "default_etsy_shop";

    /**
     *
     * @var EtsyDataService
     */
    private static $instance;

    /**
     *
     * @var TemplatesService
     */
    private $templates_service;

    /**
     *
     * @var ConnectionsService
     */
    private $connections_service;

    /**
     *
     * @var ShopsService
     */
    private $shops_service;

    /**
     *
     * @var OptionsService;
     */
    private $options_service;

    /**
     * @var ProductAttributesService
     */
    private $attributes_service;

    /**
     * @var PlatySyncerLogger
     */
    private $logger;
    

    private function __construct() {
        $this->options_service = OptionsService::get_instance('shop_id');
        $this->shops_service = ShopsService::get_instance();
        $this->connections_service = ConnectionsService::get_instance('shop_id');
        $this->templates_service = TemplatesService::get_instance('shop_id');
        $this->logger = PlatySyncerLogger::get_instance();
        $this->attributes_service = ProductAttributesService::get_instance();
    }

    public static function get_instance() {
        if(EtsyDataService::$instance == null) {
            EtsyDataService::$instance = new EtsyDataService();
        }
        return EtsyDataService::$instance;
    }

    public function get_current_shop_id(){
        return $this->shops_service->get_current_shop_id();
    }

    public function get_current_shop_name(){
        return $this->shops_service->get_current_shop_name();
    }

    public function get_current_shop(){
        return $this->shops_service->get_current_shop();
    }

    public function get_post_meta($post_id, $meta_key, $shop_id = 0, $def = null) {
        try {
            if(empty($shop_id)) {
                $shop_id = $this->get_current_shop_id();
            }
            return $this->logger->get_post_meta($post_id, $shop_id, $meta_key);
        } catch(NoCurrentShopException | NoSuchPostMetaException $e) {
            if($def !== null) {
                return $def;
            }
            throw $e;
        }

    }
        
    public function log_item_meta($pid, $meta_key, $meta_value, $shop_id = 0) {
        if(empty($shop_id)) {
            $shop_id = $this->get_current_shop_id();
        }
        $this->logger->log_meta($pid, $shop_id, $meta_key, $meta_value);
    }
    
    public function get_etsy_item_data($post_id, $shop_id = 0){
        if(empty($shop_id)) {
            $shop_id = $this->get_current_shop_id();
        }
        return $this->logger->get_etsy_item_data($post_id,$shop_id);

    }

    public function get_log_product_id($etsy_listing_id, $shop_id, $type = 'product') {
        return PlatySyncerLogger::get_instance()->get_product_data($etsy_listing_id, $shop_id, $type)['post_id'];
    }
    

    public function get_etsy_product_id($id, $shop_id = 0){
        return $this->get_etsy_item_data($id, $shop_id)['etsy_id'];
    }

    public function save_option($opt_name, $opt_value, $shop_id = -1, $group = null){
        
        if($shop_id==1){
            $shop_id = $this->get_current_shop_id();
        }

        $this->options_service->save_option($opt_name, $opt_value, $shop_id, $group);
    }

    public function save_option_group($options, $group, $shop_id = -1){
        
        if($shop_id==1){
            $shop_id = $this->get_current_shop_id();
        }

        $this->options_service->save_option_group($options, $group, $shop_id);
    }

    public function delete_option($opt_name, $shop_id = -1){
        $this->options_service->delete_option($opt_name, $shop_id);
    
    }

    public function get_options_grouped(){
        $shop_id = -1;
        try {
            $shop_id = $this->get_current_shop_id();
        }catch(NoCurrentShopException $e) {

        }
        return $this->options_service->get_options_grouped($shop_id);
    }

    public function get_options_as_array($option_name = ""){
        $shop_id = -1;
        try {
            $shop_id = $this->get_current_shop_id();
        }catch(NoCurrentShopException $e) {

        }
        return $this->options_service->get_options_as_array($shop_id, $option_name);
    }

    public function get_option($option_name, $def, $shop_id = 0){
        try {
            if(empty($shop_id)){
                $shop_id = $this->get_current_shop_id();
            }
        }catch(NoCurrentShopException $e) {
            $shop_id = -1;
        }
        return $this->options_service->get_option($option_name, $def, $shop_id);
    }

    public function get_product_count($shop_id) {
        return $this->logger->get_product_count($shop_id);
    }

    public function get_shops(){
        return $this->shops_service->get_shops();
    }

    public function get_shop($shop_id){
        return $this->shops_service->get_shop($shop_id);
    }
    
    public function save_shop($shop){
        $this->shops_service->save_shop($shop);
    }

    public function get_existing_connections($target_type){
        $shop_id = $this->get_current_shop_id();
        return $this->connections_service->get_existing_connections($target_type, $shop_id);
    }

    public function get_connectable_data_entities($type){
        $shop_id = $this->get_current_shop_id();
        return $this->connections_service->get_connectable_data_entities($type, $shop_id);
    }

    public function update_connection($connection, $type){
        $shop_id = $this->get_current_shop_id();
        $this->connections_service->update_connection($connection, $type, $shop_id);
    }

    public function delete_connection($connection, $type){
        $shop_id = $this->get_current_shop_id();
        $this->connections_service->delete_connection($connection, $type, $shop_id);
    }
    public function get_template_metas($tid){
        return $this->templates_service->get_template_metas($tid);
    }

    public function get_templates(){
        global $wpdb;
        $shop_id = $this->get_current_shop_id();
        return $this->templates_service->get_templates($shop_id);
    }

    public function add_template($template){
        $shop_id = $this->get_current_shop_id();
        return $this->templates_service->add_template($template, $shop_id);
    }

    protected function get_template_by_name($name){
        $shop_id = $this->get_current_shop_id();
        return $this->templates_service->get_template_by_name($name, $shop_id);
    }

    public function update_template($tid, $template){
        $shop_id = $this->get_current_shop_id();
        $this->templates_service->update_template($tid, $template, $shop_id);
    }

    public function delete_template($tid){
        $this->templates_service->delete_template($tid);
    }

    public function update_template_meta($tid, $meta_name, $meta_value){
        $this->templates_service->update_template_meta($tid, $meta_name, $meta_value);

    }

    public function save_default_shop($id){
        $this->shops_service->save_default_shop($id);
    }

    public function has_current_shop(){
        return $this->shops_service->has_current_shop();
      }
  
      public function is_shop_authenticated(){
        return $this->shops_service->is_shop_authenticated();

      }
      
  
      public function get_option_prefix()
      {
          return EtsyDataService::option_prefix;
      }
  
      public function has_templates(){
          if(!$this->has_current_shop()) return false;
          return $this->templates_service->has_templates($this->get_current_shop_id());
      }
  
      public function has_default_taxonomy(){
          if(!$this->has_current_shop()) return false;
  
          $opt = $this->get_options_as_array('etsy_taxonomy_node');
          return !empty($opt);
      }
  
      public function has_default_shipping_template(){
          if(!$this->has_current_shop()) return false;
          $opt = $this->get_options_as_array('shipping_template');
          return !empty($opt);
      }
  
      private function has_shop_defaults(){
          return $this->has_default_taxonomy() && $this->has_default_shipping_template();
      }
  
      public function is_valid(){
          return $this->has_current_shop() && $this->is_shop_authenticated() 
              && $this->has_templates() && $this->has_shop_defaults();
      }
  
      
      public function get_token_credentials(){
        return $this->shops_service->get_token_credentials();
    }
  
      public function get_shop_legacy_token() {
          return $this->shops_service->get_shop_legacy_token();
      }
  
      public function has_legacty_token_only($shop){
          return $this->shops_service->has_legacty_token_only($shop);
      
      }
  
      public function refresh_token_expired($shop){
          return $this->shops_service->refresh_token_expired($shop);
      }

      public function invalidate_pro_options(){
        $shops = $this->get_shops();
        $platys_service = PlatysService::get_instance();

        $level = $platys_service->get_level();
        foreach($shops as $shop) {
            $shop_id = $shop['id'];

            if(!$platys_service->is_platy($level)) {
                $this->options_service->delete_option("2w_stock_sync", $shop_id);
                $this->options_service->delete_option("restock_auto_activate", $shop_id);
                $this->options_service->delete_option("mask_product_stock_on_view", $shop_id);
                $this->options_service->delete_option("safeguard_checkout_stock", $shop_id);
                $this->options_service->delete_option("auto_sync_orders", $shop_id);
                wp_clear_scheduled_hook('platy_etsy_stock_cron_hook', [$shop_id]);
                wp_clear_scheduled_hook('platy_etsy_orders_cron_hook', [$shop_id]);
            }

        }
        if(!$platys_service->is_platus($level)) {
            $this->options_service->delete_option("variation_images");
            $this->options_service->delete_option("enable_public_product_link");

        }

      }

    public function load_attributes($tax_id, $post_id = 0, $merge = false) {
        $shop_id = $this->get_current_shop_id();
        try {
            return $this->attributes_service->load_attributes($shop_id, $tax_id, $post_id, $merge);
        }catch(NoAttributesException $e) {
            return [];
        }
    }

    public function update_attribute($tax_id, $attributes) {
        foreach($attributes as $attribute) {
            $this->attributes_service->update($attribute, $tax_id, $this->get_current_shop_id());
        }
    }

}