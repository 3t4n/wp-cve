<?php
namespace platy\etsy;

use platy\etsy\api\EtsyApi;
use platy\etsy\api\OAuthException;

use platy\utils\InventoryUtils;

/**
 * Syncs a product to Etsy
 */
class EtsyProductSyncer extends EtsyItemSyncer {

    protected $shop_id;
    /**
     *
     * @var EtsyProduct
     */
    protected $etsy_product;

    /**
     *
     * @var EtsyInventorySyncer
     */
    protected $inventory_syncer;

    /**
     *
     * @var EtsyImagesSyncer
     */
    protected $images_syncer;

    /**
     *
     * @var EtsyItemAttributesSyncer
     */
    protected $attributes_syncer;

    protected $etsy_listing;
    public function __construct($etsy_product){
        parent::__construct($etsy_product);
        $this->etsy_product = $etsy_product;
        $this->inventory_syncer = new EtsyInventorySyncer($etsy_product);
        $this->images_syncer = new EtsyImagesSyncer($etsy_product);
        $this->attributes_syncer = new EtsyItemAttributesSyncer($etsy_product);
        $this->etsy_listing = null;
    }


    protected function get_etsy_listing_id() {
        return $this->get_etsy_id();
    }
    
    protected function get_listing(){
        if(empty($this->get_etsy_listing_id())) throw new NoSuchListingException();
        if(!empty($this->etsy_listing)) return $this->etsy_listing;
        try{
            $ret = $this->api->getListing(array('params' =>array(EtsyProduct::LISTING_ID => $this->get_etsy_listing_id())));
        }catch(OAuthException $e){
            throw new NoSuchListingException();
        }
        $this->etsy_listing = $ret;
        return $this->etsy_listing;
    }

        
    public function delete_listing(){
        try{
            if(empty($this->get_etsy_listing_id())) {
                throw new NoSuchListingException("No etsy listing id found for this product");
            }

            $this->api->deleteListing(array('params' =>array(EtsyProduct::LISTING_ID => $this->get_etsy_listing_id())));
        }catch(OAuthException $e){
            if(!$e->get_status_code()==404 && !$e->get_status_code()==400){
                throw $e;
            }
        }
    }

    protected function update_listing($product_as_array){
        if(empty($this->get_etsy_listing_id())){

            throw new NoSuchListingException();
        }
        

        try{
            $this->api->updateListing(array('data' => $product_as_array,
                'params' =>array(EtsyProduct::LISTING_ID => $this->get_etsy_listing_id(), 
                EtsyProduct::SHOP_ID => $this->shop_id)));
        }catch(OAuthException $e){
            if($e->get_status_code()==404 || $e->get_status_code()==403){
                throw new NoSuchListingException();   
            }else{
                throw $e;
            }
        }
        
    }
    
    protected function get_variation_attributes(){
        return $this->etsy_product->get_product()->get_variation_attributes();
    }   

    protected function verify_product_variation_num(){
        $type = $this->etsy_product->get_product()->get_type();
        if($type != "variable") {
            throw new ProductNotVariableException();
        }
        $variation_attributes = $this->get_variation_attributes();
        if(count($variation_attributes)>2){
            throw new EtsySyncerException('Cannot sync product with more than two variations');
            
        }
    }

    
    private function verify_product_title($title, $exists) {
        if(!$exists && empty($title)) {
            return;
        }

        $this->verify_property_exists('title', $title);
        if(\preg_match(EtsyProduct::BAD_TITLE_FORMAT_REGEX, $title)){
            throw new BadFormatException($title);
        }
        if(
            substr_count($title, '%') > 1 || 
            substr_count($title, '+') > 1 || 
            substr_count($title, ':') > 1 || 
            substr_count($title, '&') > 1
        ){
            throw new BadFormatException("Characters + : % &  can appear only once - " . $title);
        }
    }

    private function verify_product_material($material) {
        if(\preg_match(EtsyProduct::BAD_MATERIALS_FORMAT_REGEX, $material)){
            throw new BadFormatException($material, "material");
        }
    }

    private function verify_property_exists($prop_label, $prop) {
        if(empty($prop)) {
            throw new NoSuchPropertyException($prop_label . " not set");
        }
    }

    
    protected function verify_product_type(){
        if(!in_array($this->etsy_product->get_product()->get_type() ,array('simple', 'variable'))){
            throw new EtsySyncerException('cannot sync product type ' . $this->etsy_product->get_product()->get_type()); 
        }
        if($this->etsy_product->get_product()->is_downloadable()){
            throw new EtsySyncerException('Cannot sync downloadable products');
        }
    }

    public function verify_product($product_array, $exists = true){
        $this->verify_product_type();
        try{
            $this->verify_product_variation_num();
        }catch(ProductNotVariableException $e){

        }

        $this->verify_product_title(@$product_array[EtsyProduct::TITLE], $exists);
        
        $tags = explode(",", @$product_array[EtsyProduct::TAGS] ?? "");

        $tags_syncer = new EtsyTagsSyncer();
        $tags_syncer->verify_tags($tags);

        $materials = explode(",", @$product_array[EtsyProduct::MATERIALS] ?? "");
        foreach($materials as $material) {
            $this->verify_product_material($material);
        }

        if($exists) {
            $this->verify_property_exists("Etsy Shipping Template", @$product_array[EtsyProduct::SHIPING_TEMPLATE_ID]);
            $this->verify_property_exists("Etsy Cateogory", @$product_array[EtsyProduct::TAXONOMY_ID]);
            $this->verify_property_exists("Who Made", @$product_array[EtsyProduct::WHO_MADE]);
            $this->verify_property_exists("When Made", @$product_array[EtsyProduct::WHEN_MADE]);
            $this->verify_property_exists("What Is It", @$product_array[EtsyProduct::IS_SUPPLY]);
        }

    }

    public function verify_product_inventory($template,$options) {
        $this->inventory_syncer->verify_product_inventory($template,$options);
    }

    protected function create_listing($product_as_array){
        
        $ret = $this->api->createDraftListing(
            array( 'params' => array(EtsyProduct::SHOP_ID => $this->shop_id),
                'data' => $product_as_array));
        // $ret = $this->parse_etsy_ret($ret);

        $etsy_listing_id = $ret[EtsyProduct::LISTING_ID];
        $this->etsy_product->on_create($etsy_listing_id);
    }

    protected function mask_product($product_as_array, $mask) {
        $product_mask = $product_as_array;
        if(!empty($mask)) {
            $product_mask = [];
            foreach($mask as $field => $to_mask) {
                if($to_mask && \key_exists($field, $product_as_array)) {
                    $product_mask[$field] = $product_as_array[$field];
                }
            }
        }
        return $product_mask;
    }
    
    
    private function get_state_from_options($options){
        return isset($options['draft_mode']) && empty($options['draft_mode']) ? 'active' : 'draft';
    }

    protected function get_create_state($options){
        return $this->get_state_from_options($options);
    }

    protected function get_update_state($options){
        $state_from_options = $this->get_state_from_options($options);

        try{
            $ret = $this->get_listing();
            $listing_state = $ret[EtsyProduct::STATE];

            /**
             * if the state is not supproted return active or inactive for draft mode
             */
            if(!\in_array($listing_state, EtsyProduct::SUPPORTED_STATES)){
                $listing_state = $state_from_options=="active" ? $state_from_options : "inactive";
            }

            /**
             * If the listing exists we can upgrade but nothing else.
             */
            if($listing_state == 'draft' && $state_from_options=="active") return $state_from_options;
            
            return $listing_state;
        }catch(NoSuchListingException $e){

        }
        return $state_from_options;
    }

    public function get_listing_state() {
        return $this->get_listing()[EtsyProduct::STATE];
    }

    public function sync($template, $connections, $options, $mask){
        $this->verify_product_inventory($template,$options);
        $product_as_array = $this->etsy_product->to_product_array($template, $connections, $options);
        $product_mask = $this->mask_product($product_as_array, $mask);;
        try{      
            $product_update = $product_mask;
            /**
             * requires a call to etsy
             */
            $product_update[EtsyProduct::STATE] =  $this->get_update_state($options);
            $this->verify_product($product_update, false);
            $this->update_listing($product_update);
        }catch(NoSuchListingException $e){
            $this->verify_product($product_as_array);
            $product_as_array[EtsyProduct::STATE] =  $this->get_create_state($options);
            $this->create_listing(array_merge($product_as_array,$this->to_offering_array($options,$template)));
            $upload_inventory = true;
        }

        $upload_inventory = $upload_inventory ?? ( empty($mask) || @$mask['inventory'] );
        if($upload_inventory) {
            $this->upload_inventory($options, $template);
        }

        $upload_images = empty($mask) || @$mask['images'];
        if($upload_images) {
            $this->upload_images($options);
        }

        $upload_attributes = empty($mask) || @$mask['attributes'];
        if($upload_images) {
            $this->upload_attributes();
        }
        
        return $this->get_etsy_listing_id();
        
    }

    protected function upload_attributes() {
        $this->attributes_syncer->sync_attributes();
    }

    protected function upload_images($options) {
        $this->images_syncer->upload_images($options);
    }

    protected function to_offering_array($options, $template){
        return $this->inventory_syncer->to_offering_array($options,$template);
    }

    protected function upload_inventory($options, $template) {
        $this->inventory_syncer->upload_inventory($options, $template);
    }

    
    public function update_state($state) {
        $this->update_listing([EtsyProduct::STATE => $state]);
    }
    protected function activate_product(){
        $this->update_state('active');
    }
}