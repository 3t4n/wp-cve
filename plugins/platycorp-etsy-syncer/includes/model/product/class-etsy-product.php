<?php
namespace platy\etsy;

use platy\etsy\api\EtsyApi;
use platy\etsy\api\OAuthException;
use platy\etsy\logs\PlatyLogger;

use platy\utils\InventoryUtils;

class EtsyProduct implements EtsyItem
{
    const BAD_TITLE_FORMAT_REGEX = "/[^\p{L}\p{Nd}\p{P}\p{Sm}\p{Zs}™©®]/u";
    const BAD_MATERIALS_FORMAT_REGEX = "/[^\p{L}\p{Nd}\p{Zs}]/u";
    const ETSY_MAX_TITLE_LEN = 140;
    const MAX_IMAGES_PER_PRODUCT = 10;
    const EXCLUDE_FROM_ETSY_META_KEY = "_exclude_from_etsy";
    const TERMS_BLACKLIST_META_KEY = "_etsy_terms_black_list";
    const ETSY_LISTING_ID_META_KEY = "_etsy_listing_id";
    const ETSY_IMAGE_ID_META_KEY = '_etsy_image_id';
    const TITLE = "title";
    const DESCRIPTION = "description";
    const MATERIALS = "materials";
    const TAGS = "tags";
    const PRODUCT_QUANTITY = 'product_quantity';
    const DRAFT_MODE = "draft_mode";
    const SHIPING_TEMPLATE_ID = "shipping_profile_id";
    const SHOP_SECTION_ID = "shop_section_id";
    const SKU = "sku";
    const STATE = "state";
    const TAXONOMY_ID = "taxonomy_id";
    const OCCASION = "occasion";
    const NON_TAXABLE = "non_taxable";
    const IS_CUSTOMIZABLE = "is_personalizable";
    const CUSTOMIZE_MESSAGE = "personalization_instructions";
    const CUSTOMIZATION_REQUIRED = "personalization_is_required";
    const RECEPIENT = "recipient";
    const WHO_MADE = "who_made";
    const WHEN_MADE = "when_made";
    const IS_SUPPLY = "is_supply";
    const ETSY_PRICE = "etsy_price";
    const MAX_STOCK = 9999;

    //meta keys
    const WHO_MADE_META_KEY = "_etsy_who_made";
    const WHEN_MADE_META_KEY = "_etsy_when_made";
    const IS_SUPPLY_META_KEY = "_etsy_is_supply";
    const MATERIALS_META_KEY = "_etsy_materials";
    const RECEPIENT_META_KEY = "_etsy_recepient";
    const OCCASION_META_KEY = "_etsy_occasion";
    const NON_TAXABLE_META_KEY = "_non_taxable";
    const ETSY_PRICE_META_KEY = "_etsy_price";
    const IS_CUSTOMIZABLE_META_KEY = "_etsy_is_personalizable";
    const CUSTOMIZE_MESSAGE_META_KEY = "_etsy_personalization_instructions";
    const CUSTOMIZATION_REQUIRED_META_KEY = "_etsy_personalization_is_required";

    const WEIGHT_UNIT_OPTION_NAME = "woocommerce_weight_unit";
    const DIMENSION_UNIT_OPTION_NAME = "woocommerce_dimension_unit";
    const LENGTH_META_KEY = "_length"; 
    const WIDTH_META_KEY = "_width";
    const HEIGHT_META_KEY = "_height";
    const WEIGHT_META_KEY = "_weight";

    //helpers
    const LISTING_ID = "listing_id";
    const SHOP_ID = "shop_id";
    const LISTING_IMAGE_ID = "listing_image_id";

    const SUPPORTED_STATES = ['active', 'inactive', 'draft'];

    protected $item_id;
    protected $product;
    protected $post;
    protected $etsy_listing_id;
    protected $variations;
    protected $shop_id;
    /**
     * 
     * @param string|int $item_id
     * @param EtsyApi $api
     */
    public function __construct($item_id, $etsy_id = 0, $shop_id = 0){
        $this->item_id = $item_id;
        $this->etsy_listing_id = $etsy_id;
        $this->variations = null;
        $this->shop_id = $shop_id;
        $this->init();

        $this->verify_exists();
    }

    protected function init(){
        $this->product = wc_get_product($this->item_id);
        $this->post = get_post($this->item_id);
        
    }

    protected function verify_exists(){
        if(empty($this->product)) {
            throw new NoSuchPostException($this->item_id);       
        }
    }

    public function get_listing_id(){
        return $this->get_etsy_id();
    }

    public function get_item_id(){
        return $this->item_id;
    }
    
    public function get_shop_id(){
        return $this->shop_id;
    }

    public function get_etsy_id() {
        return $this->etsy_listing_id;
    }

    public function on_create($etsy_listing_id) {
        $this->etsy_listing_id = $etsy_listing_id;
    }

    protected function get_product_title($template, $options){
        $unique_words = !(isset($options['title_unique']) && empty($options['title_unique']));
        $cap_title = !(isset($options['title_capitalize']) && empty($options['title_capitalize']));
        $title = $this->parse_title_template($this->for_title_template(), $template['title'], $cap_title, $unique_words ,$this->get_etsy_max_title_length());
        return $title;
    }

    protected function get_product_description($template){
        return $this->parse_content_template($this->for_content_template(), $template['description']);
    }

    function parse_content_template($args, $template){
        if(empty($template)){
            $template = '%description%';
        }
        $template = $this->parse_title_template($args, $template);
        $template = str_replace("%description%",$args['description'],$template);
        $template = str_replace("%excerpt%",$args['excerpt'],$template);
        $template = str_replace("%L%",@$args['L'],$template);
        $template = str_replace("%H%",@$args['H'],$template);
        $template = str_replace("%W%",@$args['W'],$template);
        $template = str_replace("%LU%",@$args['LU'],$template);
        $template = str_replace("%WU%",@$args['WU'],$template);
        $template = str_replace("%HU%",@$args['HU'],$template);
        

        $template = preg_replace_callback('/\%(pa_[A-Za-z0-9\-_]+)\%/', function($match) use ($args){
            $matched = $match[1];
            return isset($args[$matched]) ? $args[$matched] : "";
        }, $template);


        return trim($template);
    }

     /**
     * 
     * @param array $args
     * @param string $template
     * @param int $max_chars
     */
    protected function parse_title_template($args,$template, $cap_title = false, $unique_words = false, $max_chars = -1){
        if(empty($template)){
            $template = '%title%';
        }
        $template = str_replace("%title%",$args['title'],$template);
        $tags_string = implode(" ", $args['tags']);
        $template = str_replace("%tags%",$tags_string,$template);
        $cat_string = implode(" ", $args['categories']);
        $template = str_replace("%categories%",$cat_string,$template);
        $sku = $args['sku'];
        $template = str_replace("%sku%",$sku,$template);

        if($cap_title){
            $template = ucwords($template);
        }

        if($unique_words){
            $template_as_array = \explode(" ", $template);
            $template = implode(" ", array_unique($template_as_array));
        }

        while(strlen($template) > $max_chars && $max_chars != -1){
            $last_space = strrpos($template," ");
            if(!$last_space){
                $template = substr($template, 0,$max_chars);
            }else{
                $template = substr($template, 0, $last_space);
            }
        }
        return trim($template);
        
    }

    public function get_product_shipping_template_id($connections, $default){
        $shipping_classes = $this->get_terms('product_shipping_class');
        $id = (float) $this->get_connected_target($shipping_classes,$connections,
            $default,'etsy shipping template');
        return $id;
    }

    protected function get_etsy_max_title_length(){
        return self::ETSY_MAX_TITLE_LEN;
    }

    public function get_product_section_id($connections, $default){
        $product_cats = $this->get_terms('product_cat');
        return (float) $this->get_connected_target($product_cats,$connections,
            $default,'etsy section');
    }

    public function get_product_taxonomy_id($connections, $default){
        $product_cats = $this->get_terms('product_cat');
        return (float) $this->get_connected_target($product_cats,$connections,
            $default,'etsy taxonomy');
    }


    public function to_product_array($template,$connections,$options){
        $product = array();
        $product[self::TITLE] = $this->get_product_title($template, $options);
        $product[self::DESCRIPTION] = $this->get_product_description($template);
        $product[self::MATERIALS] = $this->get_materials($template[self::MATERIALS]);
        $product[self::SHIPING_TEMPLATE_ID] = $this->get_product_shipping_template_id($connections['etsy_shipping_templates'],$options['shipping_template']);

        $section_id = $this->get_product_section_id($connections['etsy_sections'],@$options['etsy_section']);
        if(!empty($section_id)) {
            $product[self::SHOP_SECTION_ID] = $section_id;
        }
        $product[self::SKU] = $this->get_product_sku();
        $product[self::TAXONOMY_ID] = $this->get_product_taxonomy_id($connections['etsy_taxonomy_node'],$options['etsy_taxonomy_node']);
        $product[self::TAGS] =  $this->get_tags($template[self::TAGS], @$options['ignore_tag_errors']);
        
        $this->customize($product, $template);

        $product[self::WHO_MADE] = $this->get_market_atrribute(array('name' => 'who made', 
            'key' => self::WHO_MADE_META_KEY, 'def'=>$template[self::WHO_MADE]));
        $product[self::WHEN_MADE] = $this->get_market_atrribute(array('name' => 'when made', 
            'key' => self::WHEN_MADE_META_KEY,'def'=>$template[self::WHEN_MADE]));
        $product[self::IS_SUPPLY] = $this->get_market_atrribute(array('name' => 'what is it', 
            'key' => self::IS_SUPPLY_META_KEY ,'def'=>$template[self::IS_SUPPLY] ));
        
        $occasion = $this->get_occasion();
        if(!empty($occasion)) $product[self::OCCASION] = $occasion;

        $recepient = $this->get_recepient();
        if(!empty($recepient)) $product[self::RECEPIENT] = $recepient;

        $dimensions = $this->get_dimensions();
        $product = array_merge($product, $dimensions);

        $weight = $this->get_weight();
        $product = array_merge($product, $weight);

        $product[self::NON_TAXABLE] = $this->is_non_taxable();
        // $product[self::IS_CUSTOMIZABLE] = $this->is_customizable();

        return apply_filters("platy_syncer_etsy_product_array", $product, $this->item_id); 
    }


    protected function parse_etsy_ret($ret){
        return $ret['results'][0];
    }

    protected function get_weight(){
        $dimensions = [];
        $dimensions['item_weight_unit'] = get_option(self::WEIGHT_UNIT_OPTION_NAME, "g");
        if($dimensions['item_weight_unit']=='lbs'){
            $dimensions['item_weight_unit'] = 'lb';
        }

        $weight = get_post_meta($this->item_id, self::WEIGHT_META_KEY, true);
        if(!empty($weight)) $dimensions['item_weight'] = (float) $weight;

        return $dimensions;

    }

    protected function get_dimensions(){
        $dimensions = [];
        $dimensions['item_dimensions_unit'] = get_option(self::DIMENSION_UNIT_OPTION_NAME, "cm");
        $multiplier = 1.0;
        if($dimensions['item_dimensions_unit'] == 'yd'){
            $dimensions['item_dimensions_unit'] = 'ft';
            $multiplier = 3.0;
        }

        $length = get_post_meta($this->item_id, self::LENGTH_META_KEY, true);
        if(!empty($length)) $dimensions['item_length'] = (float) $length * $multiplier;

        $width = get_post_meta($this->item_id, self::WIDTH_META_KEY, true);
        if(!empty($width)) $dimensions['item_width'] = (float) $width * $multiplier;

        $height = get_post_meta($this->item_id, self::HEIGHT_META_KEY, true);
        if(!empty($height)) $dimensions['item_height'] = (float) $height * $multiplier;

        return $dimensions;
    } 

    public function get_product_type() {
        return $this->product->get_type();
    }

    private function get_terms_blacklist() {
        $blacklist_attr_terms = get_post_meta($this->get_item_id(), 
            self::TERMS_BLACKLIST_META_KEY, true);
        return empty($blacklist_attr_terms) ? [] : explode(",", $blacklist_attr_terms);
    }

    /**
     * gets the product terms in hierarchy sort
     * @param string $tax_name
     */
    protected function get_terms($tax_name){
        $terms_ids = $this->hierarchy_sort_tax($this->item_id,$tax_name,0);
        return $terms_ids;
    }

    public function get_variations(){

        if($this->product->is_type('variable')){
            $blacklist_attr_terms = $this->get_terms_blacklist();

            $ret = [];
            $children = $this->product->get_children();
            foreach ($children as $child){

                try{
                    $variation = new EtsyProduct($child);
                }catch(NoSuchPostException $e) {
                    continue;
                }

                $final = [[]];
                foreach($this->product->get_variation_attributes() as $attr => $val){

                    $attrs = [];
                    $var_attrs = $variation->get_attribute( $attr);
                    if(empty($var_attrs)) {
                        // probably has all opitons enabled
                        $var_attrs = $this->get_attribute( $attr);
                    }

                    foreach($var_attrs as $attr_label){
                        $attrs[] = [$attr => $attr_label];
                    }
                    $temp = [];
                    while(!empty($final)){
                        $prev = array_pop($final);
                        foreach($attrs as $new_attrs){
                            $temp[] = array_merge($prev,$new_attrs);
                           
                        }
                    }
                    $final = $temp;
                }

                foreach($final as $attrs){
                    $etsy_variation = new EtsyProductVariation($child, 0, $attrs, $this->shop_id);
                    
                    if($etsy_variation->exclude_from_etsy()) {
                        continue;
                    }

                    if($etsy_variation->has_blacklisted_attribute($blacklist_attr_terms)) {
                        continue;
                    }

                    $ret[] = $etsy_variation;
                }
            }
            
        }else{
            throw new ProductNotVariableException();
        }

        return $ret;
    }

    public function get_product() {
        return $this->product;
    }

            
    public function to_offering_array($options, $template){
        $offering = [
            'price' => $this->get_price($template),
            'quantity' =>  (int) $this->get_quantity($options)
            ];    
        return $offering;

    }

    private function get_attribute_by_attr_slug($attr_slug) {
        $attrs = $this->product->get_attribute($attr_slug);

        if(empty($attrs)){
            $attr_slug = sanitize_title($attr_slug);
            $attributes =  $this->product->get_attributes();
            if(isset($attributes["pa_$attr_slug"]) || isset($attributes[$attr_slug])) {
                return [];
            }
            
            throw new EmptyVariationException($attr_slug);
        }
        return explode(",",$attrs);
    }

    private function get_attribute_by_attr_name($attr) {
        $attr_slug = null;
        $attribute_pairs = wc_get_attribute_taxonomy_labels();

        foreach($attribute_pairs as $slug_key => $label_value) {
            if($label_value == $attr) {
                $attr_slug = $slug_key;
                break;
            }
        }
        
        if(empty($attr_slug)) {
            throw new EmptyVariationException($attr);
        }

        try {
            return $this->get_attribute_by_attr_slug($attr_slug);
        }catch(EmptyVariationException $e) {
            return $this->get_attribute_by_attr_slug("pa_$attr_slug");
        }
    }
    
    public function get_attribute($attr){
        try {
            return $this->get_attribute_by_attr_slug($attr);
        }catch(EmptyVariationException $e) {
            return $this->get_attribute_by_attr_name($attr);
        }
    }
    
    protected function for_title_template(){
        $ret = array();
        $ret['title'] = $this->product->get_title();
        $ret['tags'] = [];
        foreach($this->product->get_tag_ids() as $tag_id){
            $ret['tags'][] = get_term_by('id', $tag_id,'product_tag')->name;
        }

        
        $ret['categories'] = [];
        foreach($this->product->get_category_ids() as $cat_id){
            $ret['categories'][] = get_term_by('id', $cat_id,'product_cat')->name;
        }

        $ret['sku'] = $this->get_product_sku();

        return $ret;
        
    }

    protected function get_price($template){
        $etsy_price = get_post_meta( $this->item_id, self::ETSY_PRICE_META_KEY, true );
        if(!empty($etsy_price)) return (float) $etsy_price;

        $price = $this->product->get_price();
        $price = $price ? $price : 0;

        $multiplier = !empty($template['multiply_price']) ? $template['price_multiplier'] : 1;
        $multiplier = is_numeric($multiplier) ? $multiplier : 1;
        $addition = !empty($template['add_to_price']) ? $template['price_addition'] : 0;
        $addition = is_numeric($addition) ? $addition : 0;

        return (float) ($price*$multiplier + $addition);
    }

    public function get_quantity($options){
        if(empty($options['stock_management']) || $options['stock_management']=="fixed"){
            return !empty($options['quantity']) ? $options['quantity'] : 1;
        }

        $stock = $this->product->get_stock_quantity();
        $stock = max($stock, 0);
        
        $max_stock = empty($options["max_quantity"]) ? self::MAX_STOCK : $options["max_quantity"];
        if($max_stock < $stock) {
            $stock = $max_stock;
        }

        if(
            (empty($stock) && $this->product->managing_stock()) 
            || !$this->product->is_in_stock()) {
            return 0;
        }

        return !empty($stock) ? $stock : 1;

    }

    public function get_product_sku(){
        $sku = $this->product->get_sku();
        $sku = empty($sku) ? "" : $sku;
        return $sku;
    }


     function for_content_template(){
        $ret = $this->for_title_template();
        $description = get_post_field('post_content', $this->item_id);
        $description = do_shortcode($description);
        $html_options = [
            'do_links' => 'none',
            'width' => 0
        ];
        $html = new \platy\utils\Html2Text($description, $html_options);
        $description = $html->getText();
        $ret['description'] = $description;
        $html = new \platy\utils\Html2Text($this->product->get_short_description(), $html_options);
        $excerpt = $html->getText();
        $ret['excerpt'] = $excerpt;

        if(empty(PlatysService::get_instance()->get_platys(true))) {
            return $ret;
        }

        $length = get_post_meta($this->item_id, self::LENGTH_META_KEY, true);
        $ret['L'] = $length ? $length : '';

        $width = get_post_meta($this->item_id, self::WIDTH_META_KEY, true);
        $ret['W'] = $width ? $width : '';

        $height = get_post_meta($this->item_id, self::HEIGHT_META_KEY, true);
        $ret['H'] = $height ? $height : '';

        $dimension_units = get_option(self::DIMENSION_UNIT_OPTION_NAME, "cm");
        $ret['LU'] = $length ? "$length$dimension_units" : '';

        $ret['WU'] = $width ? "$width$dimension_units" : '';

        $ret['HU'] = $height ? "$height$dimension_units" : '';

        $attrs = $this->product->get_attributes();
        foreach($attrs as $attribute) {

            if ( $attribute->is_taxonomy() ) {
                $values = wc_get_product_terms( $this->product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
                $attr_name = $attribute->get_name();
                
                $attr_label = "pa_" . wc_attribute_label($attr_name, $this->product) ;
                $ret[$attr_label] = implode(", ", $values);
            } else {
                // Convert pipes to commas and display values
                $values = array_map( 'trim', $attribute->get_options());
                $attr_name = "pa_" . $attribute->get_name();
            }
            $ret[$attr_name] = implode(", ", $values);
        }

        return $ret;
        
    }
    
    protected function get_connected_target($term_ids,$connections,$default,$meta_name){
        foreach($term_ids as $term_id){
                if(isset($connections[$term_id])){
                    return $connections[$term_id]['id'];
                }
        }

        if(!empty($default['id'])){
            return $default['id'];
        }
        return null;
    }
    
    protected function get_occasion(){
        return get_post_meta($this->item_id,self::OCCASION_META_KEY,true);
    }

    protected function get_recepient(){
        return get_post_meta($this->item_id,self::RECEPIENT_META_KEY,true);
    }

    
    protected function is_non_taxable(){
        $ret = get_post_meta($this->item_id,self::NON_TAXABLE_META_KEY,true);
        return !empty($ret);
    }

    protected function is_customizable($template){
        $ret = get_post_meta($this->item_id,self::IS_CUSTOMIZABLE_META_KEY,true);
        return !empty($ret) ? !empty($ret) : !!@$template[self::IS_CUSTOMIZABLE];
    }

    protected function get_cutomization_message($template) {
        $ret = get_post_meta($this->item_id,self::CUSTOMIZE_MESSAGE_META_KEY,true);
        $default = "";
        $from_template = @$template[self::CUSTOMIZE_MESSAGE];
        $instructions = !empty($from_template) ? $from_template : $default;
        return !empty($ret) ? $ret : $instructions;
    }

    protected function is_customization_required($template) {
        $ret = get_post_meta($this->item_id,self::CUSTOMIZATION_REQUIRED_META_KEY,true);
        return !empty($ret) ? !empty($ret) : !!@$template[self::CUSTOMIZATION_REQUIRED];
    }

    protected function customize(&$product_array, $template){
        $product_array[self::IS_CUSTOMIZABLE] = $this->is_customizable($template);
        $product_array[self::CUSTOMIZATION_REQUIRED] = $this->is_customization_required($template);
        $product_array['personalization_char_count_max'] = 256;
        $product_array[self::CUSTOMIZE_MESSAGE] = $this->get_cutomization_message($template);
    }
    
    protected function get_materials($def){
        $materials = get_post_meta($this->item_id,self::MATERIALS_META_KEY,true);
        $materials = trim($materials);
        if(!empty($materials)){
            return $materials;
        }
        return empty($def) ? "" : $def;
    }
    
    protected function get_term_meta($term_id,$meta){
        $id = get_term_meta($term_id,$meta['key'],true);
        if(!empty($id)){
            return $id;
        }
        throw new NoSuchPropertyException('No ' . $meta['name'] . " set");
    }
    
    protected function get_tags($def, $ignore_errors){
        $tags_syncer = new EtsyTagsSyncer($this->item_id);
        return $tags_syncer->get_tags($def, $ignore_errors);
    }
    
    protected function get_market_atrribute($attr){
        $ret = get_post_meta($this->item_id, $attr['key'],true);
        if(!empty($ret)){
            return $ret;
        }
        if(!empty($attr['def'])){
            return $attr['def'];
        }
        return null;
    }

    private function hierarchy_sort_tax($post_id,$tax_name,$parent_id){
        $ret = array();
        $terms = wp_get_post_terms( $post_id, $tax_name );
        
        $sorted_terms = [];
        
        foreach( $terms as $term ){
            
            $depth = count( get_ancestors( $term->term_id, $tax_name ) );
            
            if( ! array_key_exists( $depth, $sorted_terms ) ){
                $sorted_terms[$depth] = [];
            }
            
            $sorted_terms[$depth][] = $term->term_id;
            
        }
        krsort($sorted_terms);
        
        
        foreach($sorted_terms as $depth => $term_ids){
            $ret = array_merge($ret,$sorted_terms[$depth]);
        }
        return $ret;
    }
}

