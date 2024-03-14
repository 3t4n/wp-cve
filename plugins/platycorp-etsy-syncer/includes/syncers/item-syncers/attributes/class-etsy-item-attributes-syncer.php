<?php

namespace platy\etsy;

class EtsyItemAttributesSyncer extends EtsyItemSyncer {
    const LOG_TYPE = "attributes_sync";

    /**
     *
     * @var EtsyProduct
     */
    protected $etsy_product;

    public function __construct($etsy_product){
        parent::__construct($etsy_product);
        $this->etsy_product = $etsy_product;

    }

    public function get_product_tax_id() {
        $connections =  $this->data_service->get_existing_connections("etsy_taxonomy_node");
        $def_tax_id = $this->data_service->get_option("etsy_taxonomy_node", 0);
        return $this->etsy_product->get_product_taxonomy_id($connections, $def_tax_id);
    }

    public function sync_attributes() {
        $pid = $this->get_item_id();
        $this->debug_logger->log_general("syncing attributes for product id $pid", self::LOG_TYPE);

    
        $attrs = [];

        $tax_id = $this->get_product_tax_id();
        if(empty($tax_id)) {
            return $attrs;
        }

        try {
            $attrs = $this->data_service->load_attributes($tax_id, $pid, true);
            $attrs = array_values($attrs);
        }catch(NoAttributesException $e) {
            return;
        }

        foreach($attrs as $attr) {
            try {
                $this->sync_attribute($attr);
            }catch(EtsySyncerException $e) {
                $message = $e->getMessage();
                $prop_id = $attr['property_id'];
                $display_name = $attr['display_name'];
                $this->debug_logger->log_error("Error when syncing attribute $display_name, $prop_id, $message", self::LOG_TYPE);     
            }
        }
    }

    private function delete_attribute($attr) {
        $this->api->deleteListingProperty([
                'params' => [
                    EtsyProduct::LISTING_ID => $this->get_etsy_id(),
                    'shop_id' => $this->get_shop_id(),
                    'property_id' => $attr['property_id']
                ]

            ]
        ); 
    }

    private function update_attribute($attr) {

        $formatted = $this->format_attribute($attr);
        $this->api->updateListingProperty([
                'params' => [
                    EtsyProduct::LISTING_ID => $this->get_etsy_id(),
                    'shop_id' => $this->get_shop_id(),
                    'property_id' => $attr['property_id']
                ],
                'data' => $formatted

            ]
        ); 
    }

    private function sync_attribute($attr) {
        $prop_id = $attr['property_id'];
        $display_name = $attr['display_name'];

        $this->debug_logger->log_general("syncing attribute  $display_name, $prop_id", self::LOG_TYPE);
        if(empty($attr['enabled'])) {
            $this->debug_logger->log_general("attribute  $display_name, $prop_id is disabled", self::LOG_TYPE);
            try {
                    $this->delete_attribute($attr);   
                    $this->debug_logger->log_success("attribute  $display_name, $prop_id has been deleted", self::LOG_TYPE);     
            }catch(EtsySyncerException $e) {

            }
            return;
        }

        $this->update_attribute($attr);
        $this->debug_logger->log_success("attribute $display_name, $prop_id has been updated", self::LOG_TYPE);     

    }

    private function get_product_override($pid, $tax_id, $prop_id) {

    }

    private function format_attribute($attr) {
        $format = [];

        if(!empty($attr['scale_id'])) {
            $format['scale_id'] = (int) $attr['scale_id'];
        }

        $format['value_ids'] = empty($attr['value_ids']) ? [] : $attr['value_ids'];

        $values = !empty($attr['values']) ? $attr['values'] : '';
        $format['values'] = \explode(",", $values);
        return $format;
    }

}