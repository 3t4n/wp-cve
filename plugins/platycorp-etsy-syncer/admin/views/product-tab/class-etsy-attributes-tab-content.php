<?php

namespace platy\etsy\admin;

use platy\etsy\EtsyDataService;
use platy\etsy\EtsyAttributesSyncer;
use platy\etsy\AttributeInvalidException;
use platy\etsy\EtsySyncerException;
use platy\etsy\NoAttributesException;
use platy\etsy\EtsyProduct;
use platy\etsy\PlatysService;
use platy\etsy\EtsyItemAttributesSyncer;

class EtsyAttributesTabContent {
    
    private $shop_id;
    private $attributes;
    private $data_service;
    private $tax_id;
    private $item_logger;
    private $post_id;
    public function __construct() {
        $this->data_service = EtsyDataService::get_instance();
        $this->shop_id =  $this->data_service->get_current_shop_id();
        $this->attributes = [];
    }

    private function init_attributes($post_id) {        
        $syncer = new EtsyAttributesSyncer();
        $item_syncer = new EtsyItemAttributesSyncer(new EtsyProduct($post_id));
        try {
            $this->tax_id = $item_syncer->get_product_tax_id();
            $tax_id = $this->tax_id;
            $attrs = $syncer->get_taxonomy_attributes($tax_id);
        }catch (EtsySyncerException $e) {
            return;
        }
        try {
            $loaded_attrs = $this->data_service->load_attributes($this->tax_id, $post_id);
        }catch(NoAttributesException $e) {
            $loaded_attr = [];
        }

        foreach($loaded_attrs as $prop_id => $loaded_attr) {
            $attr = $attrs[$prop_id];
            $attrs[$prop_id] = array_merge($attr, $loaded_attr);
        }

        $this->attributes = array_values($attrs);
    }

    private function get_scales($attribute) {
        $ret = [];
        $scales = $attribute['scales'];
        foreach($scales as $scale) {
            $ret[$scale['scale_id']] = $scale['display_name'];
        }
        return $ret;
    }

    private function get_values($attribute) {
        $ret = [];
        foreach($attribute['possible_values'] as $val) {
            $ret[$val['value_id']] = $val['name'];
        }
        return $ret;
    }

    public function enqueue_attribute_scripts() {
        global $post;

        if(!function_exists('get_current_screen')) {
            return;
        }

        $screen = get_current_screen();
        if ( $screen && $screen->id == "product") {
            if ( 'product' === $post->post_type ) {  
                $this->init_attributes($post->ID);
   
                wp_enqueue_script(  'etsy-attributes-script', PLATY_SYNCER_ETSY_DIR_URL . 
                    'admin/js/attributes-product-tab/tab.js', [], "1.0.6" );
                wp_localize_script( 'etsy-attributes-script', 'platySyncer',  
                    [
                        'attributes' => $this->attributes
                    ] 
                );
            }
        }
    }


    public function create_tab_content($post_id){

        $attributes = $this->attributes;

        if(!empty($attributes)) {
            // echo '<h3>Attributes</h3>';
        }

        $tax_id = $this->tax_id;
        echo "<input name='etsy-tax-id' hidden value='$tax_id'>";
        foreach($attributes as $attribute) {
            echo "<hr>";
            $prop_id = $attribute['property_id'];
            echo "<input hidden name='etsy-attr-$prop_id" . "_enabled' value=''>";

            $value = @$attribute['enabled'];
            woocommerce_wp_checkbox(array(
                'id'      => "etsy-attr-$prop_id" . "_enabled",
                'name'      => "etsy-attr-$prop_id" . "_enabled",
                'label'   => __( $attribute['display_name'], 'woocommerce' ),
                'value'   => $value,
                 )
            );

            $display_name = $attribute['display_name'];
            echo "<input name='etsy-attr-$prop_id" . "_display_name' hidden value='$display_name'>";

            echo "<div id='etsy-attr-$prop_id'>";
            
            $scales = $this->get_scales($attribute);
            if(!empty($scales)) {
                $def = array_key_first($scales);
                $value =  @$attribute['scale_id'] ? @$attribute['scale_id'] : $def;

                woocommerce_wp_select(array(
                    'id'      => "etsy-attr-$prop_id" . "_scale_id",
                    'name'      => "etsy-attr-$prop_id". "_scale_id",
                    'label'   => __( 'Scale', 'woocommerce' ),
                    'options' =>  $scales, 
                    'value'   => $value,
                    'placeholder' => 'Slect Scale',
                    'custom_attributes' => [ 

                ]));
            }

            $max_choices = $attribute['is_multivalued'] ? $attribute['max_values_allowed'] : 1;
            $value = @$attribute['value_ids'];
            
            woocommerce_wp_select(array(
                'id'      => "etsy-attr-$prop_id" . "_value_ids",
                'name'      => "etsy-attr-$prop_id" . "_value_ids[]",
                'label'   => __( 'Values', 'woocommerce' ),
                'options' =>  $this->get_values($attribute), 
                'value'   => $value,
                'description' => "Choose max $max_choices values",
                'custom_attributes' => ['multiple' => true])
            );

            echo "<input name='hidden-etsy-attr-$prop_id-max-values' hidden value='$max_choices'>";

            $value = @$attribute['values'];
            $multi_description = "Seperate at most $max_choices values by commas";
            $single_description = "Input a value";
            $description = $attribute['is_multivalued'] ? $multi_description : $single_description;
            woocommerce_wp_text_input(
                array(
                    'id'        => "etsy-attr-$prop_id" . "_values",
                    'value'     => $value,
                    'label'     => __( 'Values', 'woocommerce' ),
                    'description' => $description,
                    'custom_attributes' => [ 

                    ]
                )
            );
            
            echo "</div>";
        }
    }

    public function save_settings($post_id){

        $platys = PlatysService::get_instance();
        $level = $platys->get_level();
        if(!$platys->is_platy($level)) {
            //$this->show_admin_notice("Must be a pro to set Etsy Attributes");
            return;
        }
        
        $tax_id = $_POST['etsy-tax-id'];
        $attributes = [];
        foreach($_POST as $field => $value) {

            if($this->startsWith( $field, "etsy-attr-" )) {
                $field = \str_replace("etsy-attr-", '', $field);
                $len = \strlen($field);
                $ind = \strpos($field, "_");
                $prop_id = substr($field, 0, $ind);
                $field = \substr($field, $ind + 1, $len - $ind);

                if(!isset($attributes[$prop_id])) {
                    $attributes[$prop_id] = [];
                }

                $attributes[$prop_id][$field] = $value;

            }
        }

        foreach($attributes as $prop_id => $attribute) {
                try {
                    $this->verify_attribute($prop_id, $attribute);
                }catch(AttributeInvalidException $e) {
                    $this->show_admin_notice($e->getMessage());
                    continue;
                }
                $this->save_attribute($post_id, $tax_id, $prop_id, $attribute);
        }
    }

    public function show_admin_notice($message) {
        set_transient("platy_etsy_error_transient", $message, 5);
    }

    private function verify_attribute($prop_id, $attribute) {
        $display_name = $_POST["etsy-attr-$prop_id" . "_display_name"];

        if(!$attribute['enabled']) {
            return;
        }

        if(empty($attribute['value_ids']) && empty($attribute['values'])) {
            throw new AttributeInvalidException("no value for etsy attribute $display_name");
        }

        $max_choices = $_POST["hidden-etsy-attr-$prop_id-max-values"];
        
        if(isset($attribute['value_ids']) && \count($attribute['value_ids']) > $max_choices) {
            throw new AttributeInvalidException("Cannot save more than $max_choices values for attribute $display_name");
        }

        if(isset($attribute['values']) && \count(explode(",", $attribute['values'])) > $max_choices) {
            throw new AttributeInvalidException("Cannot save more than $max_choices values for attribute $display_name");
        }
    }

    private function save_attribute($post_id, $tax_id, $prop_id, $attribute) {
        foreach($attribute as $field => $value) {
            $this->data_service->log_item_meta($post_id, "attr-$prop_id-tax-$tax_id-$field", $value);
        }
    }

    private function startsWith( $haystack, $needle ) {
        $length = strlen( $needle );
        return substr( $haystack, 0, $length ) === $needle;
   }
}