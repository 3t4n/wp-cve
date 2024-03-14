<?php

namespace platy\etsy;

class EtsyAttributesSyncer extends EtsySyncer {

    public function get_taxonomy_attributes($tax_id) {
        $res = $this->api->getPropertiesByTaxonomyId(['params' => [
            'taxonomy_id' => $tax_id
            ]
        ]);

        $attrs = (array_filter($res['results'], function ($attr) {
            return $attr['supports_attributes'];
        }));

        $ret = [];
        
        foreach($attrs as $attr) {
            $ret[$attr['property_id']] = $attr;
        }

        return $ret;
    }


    public function get_etsy_taxonomy_tree(){
        return $this->api->getSellerTaxonomyNodes()['results'];
    }

}
