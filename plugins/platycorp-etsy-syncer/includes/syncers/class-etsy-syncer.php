<?php
namespace platy\etsy;
use platy\etsy\rest\templates\DuplicateTemplateNameException;
use Risan\OAuth1\OAuth1Factory;
use Risan\OAuth1\Credentials\TemporaryCredentials;
use platy\etsy\api\EtsyApi;
use platy\etsy\api\EtsyClient;
use Risan\OAuth1\Credentials\TokenCredentials;
use includes\utils\Wordpress_Syncer_Utils;
use Risan\OAuth1\Credentials\ClientCredentials;
use platy\etsy\logs\PlatySyncerLogger;
use platy\etsy\api\Client as Oauth2Client;
use platy\etsy\logs\PlatyLogger;

class EtsySyncer{
    const MAX_P = 50;

    protected $api;
    protected $language;

    private $oauth1;
    const auth_token = "platy_etsy_auth_token";
    const auth_secret =  "platy_etsy_auth_secret";
    const API_KEY = 'mav5rnunf0x5nr91nu4ezam9';
    private $scopes = array('listings_r','listings_w', 'listings_d','shops_w', 'shops_r', 'transactions_r','transactions_w', 'email_r');
    private $shipping_templates;
    private $etsy_sections;
    protected $api_key;

    /**
     * 
     *
     * @var PlatysService
     */
    protected $platys_service;

    /**
     *
     * @var EtsyDataService
     */
    protected $data_service;

    /**
     * @var PlatySyncerLogger
     */
    protected $item_logger;

    
    /**
     *
     * @var PlatyLogger
     */
    protected $debug_logger;

    protected $shop_id;
    public function __construct($shop_id = 0)
    {
        $this->api_key = self::API_KEY;
        $this->item_logger = PlatySyncerLogger::get_instance();
        $this->debug_logger = PlatyLogger::get_instance();
        
        $scope_string = implode("%20", $this->scopes);
        $this->data_service = EtsyDataService::get_instance();

        $this->shop_id = $shop_id;

        $language = $this->get_option("shop_language", "locale");
        $this->language = $language == "locale" ? \substr(get_locale(  ),0,2) : $language;

        $token = null;
        $legacy_token = null;
        if($this->data_service->has_current_shop()){
            $token = $this->data_service->get_token_credentials();
            $legacy_token = $this->data_service->get_shop_legacy_token();
        }
        
        $this->api = EtsyApi::get_instance($this->api_key, $token, $legacy_token, $this->language);
        $this->etsy_sections = null;
        $this->shipping_templates = null;

        $this->platys_service = PlatysService::get_instance();
    }

    public static function clean_name($name){
        return \html_entity_decode($name, ENT_QUOTES);
    }

    public static function clean_result($results, $name = 'name', $id = 'id'){
        $clean = [];
        foreach($results as $r){
            $clean[] = ['name' => EtsySyncer::clean_name($r[$name]), 'id' => $r[$id]];
        }
        return $clean;
    }

    
    public function get_log_product_id($etsy_listing_id, $shop_id, $type = 'product') {
        return $this->data_service->get_log_product_id($etsy_listing_id, $shop_id, $type);
    }

    public static function with_id_keys($data, $id = 'id'){
        return DataService::with_id_keys($data, $id);
    }
    
    public function get_shipping_templates(){
        if($this->shipping_templates !== null) return $this->shipping_templates;

        $shop_id = $this->get_shop_id();
        $result = $this->api->getShopShippingProfiles([ 
            'params' => array('shop_id' => (int)($shop_id))
        ]);
        $clean = EtsySyncer::clean_result($result['results'], "title", 'shipping_profile_id');
        
        $ret = DataService::with_id_keys($clean);
        $this->shipping_templates = $ret;
        return $ret;
    }

    public function get_shop_sections(){
        if($this->etsy_sections !== null) return $this->etsy_sections;

        $shop_id = $this->get_shop_id();
        $result = $this->api->getShopSections(array('params' => array('shop_id' => $shop_id)));
        $clean = EtsySyncer::clean_result($result['results'], "title", 'shop_section_id');
        
        $ret = DataService::with_id_keys($clean);
        $this->etsy_sections = $ret;
        return $ret;
    }
    
    // private function get_user_id(){
    //     return $this->get_option("user_id");
    // }

    public function get_shop_id(){
        if(!empty($this->shop_id)) {
            return $this->shop_id;
        }
        return $this->data_service->get_current_shop_id();
    }


    public function get_etsy_shop_by_id($shop_id){
        $syncer = new EtsyShopsSyncer();
        return $syncer->get_etsy_shop_by_id($shop_id);
    }

    public function get_etsy_shop_by_name($shop_name){
        $syncer = new EtsyShopsSyncer();
        return $syncer->get_etsy_shop_by_name($shop_name);
    }
    
    public function get_etsy_product_data($post_id){
        return $this->data_service->get_etsy_item_data($post_id);
    }


    public function get_option($option_name, $def){
        return $this->data_service->get_option($option_name, $def, $this->shop_id);
    }

    public function save_ouath_credentials($shop_id, $tokenCredentials) {
        global $wpdb;
        $shop_tbl = \Platy_Syncer_Etsy::SHOP_TABLE_NAME;

        if(empty($shop_id)) {
            $shop_id = $this->get_shop_id();
        }

        $last_use = time();
        $tokenCredentials['last_ouath2_use'] = $last_use;
        $wpdb->update($wpdb->prefix . $shop_tbl,$tokenCredentials, ['id' => $shop_id]);
    }

    private function invalidate_default($def_name, $invalidate_against){
        $def = $this->data_service->get_options_as_array($def_name);
        if(empty($def)) return;
        $def_id = $def[$def_name]['id'];

        if(!\in_array($def_id, $invalidate_against)){
            $this->data_service->delete_option($def_name,$this->get_shop_id());
        } 
    }

    private function invalidate_connections($invalidate_against, $source_type, $target_type){
        $connections = $this->data_service->get_existing_connections($target_type);

        foreach($connections as $source_id => $connection){
            if(!\in_array($connection['id'], $invalidate_against)){
                $conn = [];
                $conn['id'] = $source_id;
                $conn['type'] = $target_type;
                $this->data_service->delete_connection($conn,$source_type);
            }
        }
    }

    public function invalidate(){
        $this->data_service->save_option('draft_mode', true, -1, 'general_settings');
        $shipping_templates = array_keys($this->get_shipping_templates());
        $etsy_sections = array_keys($this->get_shop_sections());

        $this->invalidate_default("shipping_template", $shipping_templates);
        $this->invalidate_default("etsy_section", $etsy_sections);

        $this->invalidate_connections($shipping_templates, "product_shipping_class", "shipping_template");
        $this->invalidate_connections($etsy_sections, "product_cat", "etsy_section");

        $this->data_service->invalidate_pro_options();
    }

    public function get_platys(){
        $platys_service = $this->platys_service;
		return $platys_service->get_platys();
	}

    
    public function clear_auth(){
        
    }
    
    public function add_shop_section($section_name){
        $etsyShop = $this->get_shop_id();
        $ret = $this->api->createShopSection(array('params' => array('shop_id' => $etsyShop),'data' => array('title' => $section_name)));
        return Wordpress_Syncer_Utils::clean_remote($ret['results'],'shop_section_id','title')[0];   
    }

    protected function get_etsy_product_id($id){
        return $this->data_service->get_etsy_product_id($id);
    }

    public function sync_product($item_id, $template_id, $mask = []){
        $etsy_listing_id = 0;
        try{
            $etsy_listing_id = $this->get_etsy_product_id($item_id);
        }catch(NoSuchListingException $e){

        }

        if(empty($etsy_listing_id)) {
            $this->verify_product_count();
        }

        $product = new EtsyProduct($item_id,$etsy_listing_id, $this->get_shop_id());
        $syncer = new EtsyProductSyncer($product);
        $connections = [
            'etsy_sections' => $this->data_service->get_existing_connections("etsy_section"),
            'etsy_shipping_templates' => $this->data_service->get_existing_connections("shipping_template"),
            'etsy_taxonomy_node' => $this->data_service->get_existing_connections("etsy_taxonomy_node")
        ];
        try{
            $etsy_listing_id =  $syncer->sync($this->data_service->get_template_metas($template_id),$connections,$this->data_service->get_options_as_array(), $mask);
            $this->item_logger->log_success($item_id,$etsy_listing_id, $this->get_shop_id(), 'product');
            $this->item_logger->log_meta($item_id, $this->get_shop_id(),
                "template_id", $template_id);
            
        }catch(EtsySyncerException $e){
            $e->update_listing_id($product->get_listing_id());
            throw $e;
        }

        return $etsy_listing_id;
    }

    public function get_item_logger(){
        return $this->item_logger;
    }

    public function get_etsy_listings($offset, $limit, $state) {
       return $this->api->getListingsByShop([
           'params' => [
                'shop_id' => $this->get_shop_id()
           ],
           'data' => [
               'limit' => $limit,
               'offset' => $offset,
               'state' => $state
           ]
        ])['results'];
    }

    
    public function verify_product_count() {
        $level = $this->platys_service->get_level(true);
        if($this->platys_service->is_platus($level)){
            return;
        }

        $count = $this->data_service->get_product_count($this->get_shop_id());
        if($count >= self::MAX_P) {
            $pro_link = "platycorp.com";
            throw new EtsySyncerException("Product sync limit reached. Visit $pro_link to increase your limit");
        }
    }

    public function link_etsy_item($skus, $etsy_id){
        $this->verify_product_count();
        $this->debug_logger->log_general("trying to link etsy id $etsy_id", "etsy_linking");

        if(empty($skus)) {
            throw new LinkingException("Listing $etsy_id has no skus");
        }

        foreach($skus as $sku) {
            $this->debug_logger->log_general("trying to sync sku $sku", "etsy_linking");
            $tmp_id = wc_get_product_id_by_sku($sku);
            $parent_id = wp_get_post_parent_id( $tmp_id );
            $tmp_id = empty($parent_id) ? $tmp_id : $parent_id;

            
            if(!isset($product_id)) {
                $product_id = $tmp_id;
            }

            $this->debug_logger->log_general("product id for sku $sku is $tmp_id. 
                parent id is $parent_id and global id is $product_id", "etsy_linking");
            
            if($tmp_id != $product_id) {
                throw new LinkingException("Listing $etsy_id linked to multiple products");
            }
        }

        if(empty($product_id)) {
            throw new LinkingException("No match found for $etsy_id");
        }

        $logger = $this->item_logger;
        $logger->link_product($product_id, $etsy_id, $this->get_shop_id(), "product");
        return $product_id;
    }

    public function delete_item($item_id){
        $etsy_listing_id = $this->get_etsy_product_id($item_id);
        $product = new EtsyProduct($item_id,$etsy_listing_id, $this->get_shop_id());
        $syncer = new EtsyProductSyncer($product);
        $syncer->delete_listing();
    }

    public function get_oauth_url($shop_id, $shop_name, $user_id){
        $redirect_uri = 'https://platycorp.com/wp-json/platy-corp/v1/etsy-oauth2';    
        $client = $this->api->get_client()->get_oauth();
        $nonce = $client->createNonce();
        $code_challenge = $client->generateChallengeCode();
        $ret = wp_remote_post( $redirect_uri, array(
            'body' => array(
                    'shop' => $shop_name,
                    'state' => $nonce,
                    'verifier' => $code_challenge[0],
                    'callback_url' => \urlencode(admin_url( 'admin.php?page=platy-syncer-etsy-oauth2' ))
                ) 
            )
        );

        if(is_wp_error( $ret )) {
            throw new EtsySyncerException($ret->get_error_message());
        }
        
        $url = $client->getAuthorizationUrl($redirect_uri, $this->scopes, $code_challenge[1], $nonce);
        return $url;
    }
    
    public function authenticate_user($verifier,$token,$secret){
        $temporaryCredentials = new TemporaryCredentials($token, $secret);
        try{
            $tokenCredentials = $this->oauth1->requestTokenCredentials($temporaryCredentials, $token, $verifier);
        }catch (\Exception $e){
            throw new AuthenticationException();
        }
        return $tokenCredentials;
        
    }


}