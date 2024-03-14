<?php
namespace platy\etsy\admin;
use platy\etsy\MarketAttributes;
// use includes\syncers\etsy\PlatyItemSyncerEtsy;
use platy\etsy\EtsyProduct;
use platy\etsy\PlatysService;
use platy\etsy\EtsyDataService;


class EtsyProductTab{
    private $attribute_content;
    private $post;
    private $shop_id;
    private $data_service;
    public function __construct(){
        $this->data_service = EtsyDataService::get_instance();
        $this->shop_id =  $this->data_service->get_current_shop_id();
        $this->attribute_content = new EtsyAttributesTabContent();
    }

    public function get_level() {
        $platy_service = PlatysService::get_instance();
        $level = $platy_service->get_level(true);
        return $level;
    }
    
    public function add_product_tab( $default_tabs ) {
        $class = 'show_if_simple show_if_variable';
        
        $default_tabs['etsy_tab'] = array(
            'title' 	=> __( 'Etsy', 'woocommerce' ),
            'label' =>  __( 'Etsy', 'etsy_tab' ),
            'target' => 'etsy_product_data',
            'class' => $class,
            'priority' 	=> 50
        );
        return $default_tabs;
    }
    
    public function create_tab_content(){
        $post = $this->get_post();
        
        echo '<div id="etsy_product_data" class="panel wc-metaboxes-wrapper woocommerce_options_panel">';

        $who_made_val = $this->get_option($post->ID,EtsyProduct::WHO_MADE,EtsyProduct::WHO_MADE_META_KEY , true);
        woocommerce_wp_select(array(
            'id'      => '_etsy_who_made',
            'label'   => __( 'Who made', 'woocommerce' ),
            'options' =>  $this->attributes_to_options(MarketAttributes::WHO_MADE_ARRAY),
            'value'   => $who_made_val));

        $when_made_val = $this->get_option($post->ID,EtsyProduct::WHEN_MADE ,EtsyProduct::WHEN_MADE_META_KEY,true);
        woocommerce_wp_select(array(
            'id'      => '_etsy_when_made',
            'label'   => __( 'When made', 'woocommerce' ),
            'options' =>  $this->attributes_to_options(MarketAttributes::WHEN_MADE_ARRAY), 
            'value'   => $when_made_val));
        
        $is_supply_val = $this->get_option($post->ID,EtsyProduct::IS_SUPPLY,EtsyProduct::IS_SUPPLY_META_KEY,true);
        woocommerce_wp_select(array(
            'id'      => '_etsy_is_supply',
            'label'   => __( 'What is it', 'woocommerce' ),
            'options' =>  $this->attributes_to_options(MarketAttributes::IS_SUPPLY_ARRAY), 
            'value'   => $is_supply_val));
        
        $etsy_materials = $this->get_option($post->ID,EtsyProduct::MATERIALS,EtsyProduct::MATERIALS_META_KEY);
        woocommerce_wp_textarea_input(array(
            'id'      => '_etsy_materials',
            'label'   => __( 'Materials', 'woocommerce' ),
            'description' =>  'Comma separated list of materials', 
            'value'   => $etsy_materials));

        $etsy_recepient = $this->get_option($post->ID,EtsyProduct::RECEPIENT,EtsyProduct::RECEPIENT_META_KEY);
        woocommerce_wp_select(array(
            'id'      => '_etsy_recepient',
            'label'   => __( 'Recepient (where applicable)', 'woocommerce' ),
            'options' =>  $this->attributes_to_options(MarketAttributes::RECEPIENT_ARRAY), 
            'value'   => $etsy_recepient));

        $etsy_occasion = $this->get_option($post->ID,EtsyProduct::OCCASION,EtsyProduct::OCCASION_META_KEY);
        woocommerce_wp_select(array(
            'id'      => '_etsy_occasion',
            'label'   => __( 'Occasion (where applicable)', 'woocommerce' ),
            'options' =>  $this->attributes_to_options(MarketAttributes::OCCASION_ARRAY), 
            'value'   => $etsy_occasion));

        $non_taxable = $this->get_option($post->ID,EtsyProduct::NON_TAXABLE,EtsyProduct::NON_TAXABLE_META_KEY);
        woocommerce_wp_checkbox(array(
            'id'      => EtsyProduct::NON_TAXABLE_META_KEY,
            'label'   => __( 'Non Taxable', 'woocommerce' ),
            'description' =>  'Check if item is not taxable', 
            'value'   => $non_taxable));
        
        $is_customizable = $this->get_option($post->ID,EtsyProduct::IS_CUSTOMIZABLE, EtsyProduct::IS_CUSTOMIZABLE_META_KEY);
        woocommerce_wp_checkbox(array(
            'id'      => EtsyProduct::IS_CUSTOMIZABLE_META_KEY,
            'label'   => __( 'Is Customizable', 'woocommerce' ),
            'description' =>  'Check if the item is customizable', 
            'value'   => $is_customizable));

        $customizable_message = $this->get_option($post->ID,EtsyProduct::CUSTOMIZE_MESSAGE, EtsyProduct::CUSTOMIZE_MESSAGE_META_KEY);
        woocommerce_wp_textarea_input(array(
            'id'      => EtsyProduct::CUSTOMIZE_MESSAGE_META_KEY,
            'label'   => __( 'Customization Instructions', 'woocommerce' ),
            'description' =>  'Instructions to the customer for customization', 
            'value'   => $customizable_message));

        $customization_required = $this->get_option($post->ID,EtsyProduct::CUSTOMIZATION_REQUIRED, EtsyProduct::CUSTOMIZATION_REQUIRED_META_KEY);
        woocommerce_wp_checkbox(array(
            'id'      => EtsyProduct::CUSTOMIZATION_REQUIRED_META_KEY,
            'label'   => __( 'Is Customization required', 'woocommerce' ),
            'description' =>  'Check if the item required customization', 
            'value'   => $customization_required));
        
        $product_type = \WC_Product_Factory::get_product_type($post->ID);
        if($product_type == "variable") {
            $blacklist_var_terms = $this->get_option($post->ID,EtsyProduct::TERMS_BLACKLIST_META_KEY,EtsyProduct::TERMS_BLACKLIST_META_KEY);
            woocommerce_wp_textarea_input(array(
                'id'      => EtsyProduct::TERMS_BLACKLIST_META_KEY,
                'label'   => __( 'Exclude variation terms', 'woocommerce' ),
                'description' =>  'Comma separated list of variation attributes to exclude', 
                'value'   => $blacklist_var_terms));
        }

        $this->add_attributes_content($post->ID);
            
        echo '</div>';
    }

    public function enqueue_attribute_scripts($hook) {
        $this->attribute_content->enqueue_attribute_scripts();
    }

    private function add_attributes_content($post_id) {
        $this->attribute_content->create_tab_content($post_id);
    }
    
    public function save_settings($post_id){
        $who_made = sanitize_text_field($_POST[EtsyProduct::WHO_MADE_META_KEY] );
        $legal_values = MarketAttributes::map_to_attribute_ids(MarketAttributes::WHO_MADE_ARRAY);
        if(\in_array($who_made, $legal_values) || empty($who_made)){
            update_post_meta($post_id, EtsyProduct::WHO_MADE_META_KEY, $who_made);
        }
        
        $when_made = sanitize_text_field($_POST[EtsyProduct::WHEN_MADE_META_KEY]);
        $legal_values = MarketAttributes::map_to_attribute_ids(MarketAttributes::WHEN_MADE_ARRAY);
        if(\in_array($when_made, $legal_values) || empty($when_made)){
            update_post_meta($post_id, EtsyProduct::WHEN_MADE_META_KEY, $when_made);
        }

        $legal_values = MarketAttributes::map_to_attribute_ids(MarketAttributes::IS_SUPPLY_ARRAY);
        $what_is_it = sanitize_text_field($_POST[EtsyProduct::IS_SUPPLY_META_KEY]);
        if(\in_array($what_is_it, $legal_values) || empty($what_is_it)){
            update_post_meta($post_id, EtsyProduct::IS_SUPPLY_META_KEY, $what_is_it);
        }

        $materials = sanitize_text_field($_POST[EtsyProduct::MATERIALS_META_KEY]);
        update_post_meta($post_id, EtsyProduct::MATERIALS_META_KEY, $materials ); // no need to validate anything here
        
        $legal_values = MarketAttributes::map_to_attribute_ids(MarketAttributes::RECEPIENT_ARRAY);
        $recpient = sanitize_text_field($_POST[EtsyProduct::RECEPIENT_META_KEY]);
        if(\in_array($recpient, $legal_values) || empty($recpient)){
            update_post_meta($post_id, EtsyProduct::RECEPIENT_META_KEY, $recpient);
        }

        $legal_values = MarketAttributes::map_to_attribute_ids(MarketAttributes::OCCASION_ARRAY);
        $occasion = sanitize_text_field($_POST[EtsyProduct::OCCASION_META_KEY]);
        if(\in_array($occasion, $legal_values) || empty($occasion)){
            update_post_meta($post_id, EtsyProduct::OCCASION_META_KEY, $occasion);
        }

        $non_taxable = sanitize_text_field(@$_POST[EtsyProduct::NON_TAXABLE_META_KEY]);
        update_post_meta( $post_id, EtsyProduct::NON_TAXABLE_META_KEY, $non_taxable ); // no need to validate anything here

        if(\is_numeric($_POST[EtsyProduct::ETSY_PRICE_META_KEY]) || empty($_POST[EtsyProduct::ETSY_PRICE_META_KEY])){
            update_post_meta( $post_id, EtsyProduct::ETSY_PRICE_META_KEY, $_POST[EtsyProduct::ETSY_PRICE_META_KEY] );
        }
        
        $is_customizable_key = EtsyProduct::IS_CUSTOMIZABLE_META_KEY;
        update_post_meta( $post_id, $is_customizable_key, @$_POST[$is_customizable_key] );

        $customizable_message = EtsyProduct::CUSTOMIZE_MESSAGE_META_KEY;
        update_post_meta( $post_id, $customizable_message, $_POST[$customizable_message] );

        $customization_required = EtsyProduct::CUSTOMIZATION_REQUIRED_META_KEY;
        update_post_meta( $post_id, $customization_required, @$_POST[$customization_required] );

        $blacklist_var_terms = sanitize_text_field(@$_POST[EtsyProduct::TERMS_BLACKLIST_META_KEY]);
        update_post_meta( $post_id, EtsyProduct::TERMS_BLACKLIST_META_KEY, $blacklist_var_terms ); // no need to validate anything here

        $this->attribute_content->save_settings($post_id);

    }

    public function save_variation_settings($post_id, $loop){
        if(\is_numeric($_POST[EtsyProduct::ETSY_PRICE_META_KEY][$loop]) || empty($_POST[EtsyProduct::ETSY_PRICE_META_KEY][$loop])){
            update_post_meta( $post_id, EtsyProduct::ETSY_PRICE_META_KEY, $_POST[EtsyProduct::ETSY_PRICE_META_KEY][$loop] );
        }

        $exclude_from_etsy = @$_POST[EtsyProduct::EXCLUDE_FROM_ETSY_META_KEY][$loop];
        update_post_meta( $post_id, EtsyProduct::EXCLUDE_FROM_ETSY_META_KEY, $exclude_from_etsy);

  
    }

    public function add_etsy_price_field(){
        $post = $this->get_post();
        $price = $this->get_option($post->ID,EtsyProduct::ETSY_PRICE,EtsyProduct::ETSY_PRICE_META_KEY);
        $this->add_price_field_html(EtsyProduct::ETSY_PRICE_META_KEY, EtsyProduct::ETSY_PRICE_META_KEY, $price);
        
    }

    protected function add_price_field_html($id, $name, $price){
        woocommerce_wp_text_input(
			array(
                'id'        => $id,
                'name'        => $name,
				'value'     => $price,
                'label'     => __( 'Etsy price', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
				'data_type' => 'price',
			)
		);
    }

    protected function add_variation_price_field_html($id, $name, $price){
        woocommerce_wp_text_input(
			array(
                'id'        => $id,
                'name'        => $name,
				'value'     => $price,
                'label'     => __( 'Etsy price', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
                'wrapper_class' => 'form-row form-row-first form-row-last',
				'data_type' => 'price',
			)
		);
    }

    public function add_variation_etsy_price_field($loop, $variation_data, $variation ){
        $price = $this->get_option($variation->ID,EtsyProduct::ETSY_PRICE,EtsyProduct::ETSY_PRICE_META_KEY);
        $this->add_variation_price_field_html(EtsyProduct::ETSY_PRICE_META_KEY . "$loop", EtsyProduct::ETSY_PRICE_META_KEY . "[$loop]", $price);
    }

    public function add_variation_options($loop, $variation_data, $variation ){
        $this->add_variation_options_html($loop, $variation_data, $variation);
    }

    public function add_variation_options_html($loop, $variation_data, $variation) {
        $exclude_from_etsy = $this->get_option($variation->ID, "", EtsyProduct::EXCLUDE_FROM_ETSY_META_KEY);
        woocommerce_wp_checkbox(array(
            'id'      => EtsyProduct::EXCLUDE_FROM_ETSY_META_KEY . "[$loop]",
            'label'   => __( 'Exclude from Etsy  ', 'woocommerce' ),
            'description' =>  'Exclude this variation from Etsy', 
            "desc_tip" => "true",
            "class" => "checkbox",
            "style" => "margin: 0.3rem !important; vertical-align: middle;",
            'value'   => $exclude_from_etsy));

    }

    /**
     * returns option from post meta or if not
     * found then options
     * @param  string $option_name option key
     * @param  string $post_meta_key meta key
     * @return string        
     */
    protected function get_option($post_id,$option_name, $post_meta_key, $remote = false){
        $value = get_post_meta($post_id, $post_meta_key,true);
        // if(empty($value)){
        //     $value = $remote ? $this->syncer->get_default_remote($option_name) : $this->syncer->get_option($option_name);

        // }
        return $value;
    }
    
    protected function get_post(){
        global $post;
        return $post;
    }
    private function attributes_to_options($attributes){
        $ret = [];
        $ret[''] = '';
        foreach($attributes as $attr){
            $ret[$attr['id']] = $attr['name'];
        }
        return $ret;
    }
}