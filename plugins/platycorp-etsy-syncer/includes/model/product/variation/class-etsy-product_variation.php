<?php
namespace platy\etsy;

class EtsyProductVariation extends EtsyProduct
{   
    private $attrs;
    private $parent_product;
    function __construct($item_id, $etsy_id, $attrs, $shop_id){
        parent::__construct($item_id, $etsy_id, $shop_id);
        $this->attrs = $attrs;
        $this->parent_product = wc_get_product($this->product->get_parent_id());
    }

    public function get_attrs(){
        return $this->attrs;
    }

    public function get_parent_product() {
        return $this->parent_product;
    }

    public function get_attr_num() {
        return \count($this->attrs);
    }

    public function has_blacklisted_attribute($blacklist) {
        foreach($this->attrs as $tax => $attr) {
            $term = get_term_by( "name", $attr, $tax );
            if(empty($term)) {
                $term = get_term_by( "slug", $attr, $tax );
            }
            if(empty($term)) {
                continue;
            }
            if(in_array($term->slug, $blacklist) || \in_array($term->name, $blacklist)){
                return true;
            }
        }
        return false;
    }

    public function exclude_from_etsy() {
        $exclude = get_post_meta( $this->get_item_id(), EtsyProduct::EXCLUDE_FROM_ETSY_META_KEY,
                 true );
        return !empty($exclude);
    }
}