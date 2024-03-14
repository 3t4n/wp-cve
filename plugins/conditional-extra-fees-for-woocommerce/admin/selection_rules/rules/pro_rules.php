<?php

class pisol_cefw_pro_rules{
    function __construct($slug){
        $this->slug = $slug;
         /* this adds the condition in set of rules dropdown */
        add_filter("pi_".$this->slug."_condition", array($this, 'addRule'));
    }

    function addRule($rules){
        $rules['state'] = array(
            'name'=>__('State (Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'location_related',
            'condition'=>'state',
            'pro'=>true
        );
        $rules['postcode'] = array(
            'name'=>__('Postcode (Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'location_related',
            'condition'=>'postcode',
            'pro'=>true
        );
        
        $rules['product_tag'] = array(
            'name'=>__('Cart has Product tag (Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'product_related',
            'condition'=>'product_tag',
            'pro'=>true
        );
        $rules['user_role'] = array(
            'name'=>__('User role (Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'user_related',
            'condition'=>'user_role',
            'pro'=>true
        );


        $rules['payment_method'] = array(
            'name'=>__('Payment Method (Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'cart_related',
            'condition'=>'payment_method',
            'pro'=>true
        );

        $rules['day_of_week'] = array(
            'name'=>__('Days of the week (Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'cart_related',
            'condition'=>'day_of_week',
            'pro'=>true
        );
        
        $rules['local_pickup'] = array(
            'name'=>__('Local pickup fees (Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'delivery_method',
            'condition'=>'local_pickup',
            'pro'=>true
        );

        $rules['shipping_method'] = array(
            'name'=>__('Shipping method (Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'delivery_method',
            'condition'=>'shipping_method',
            'pro'=>true
        );

        $rules['cat_qty'] = array(
            'name'=>__('Quantity of product from category (Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'product_related',
            'condition'=>'cat_qty',
            'pro'=>true
        );

        $rules['tag_qty'] = array(
            'name'=>__('Quantity of product from Tag (Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'product_related',
            'condition'=>'tag_qty',
            'pro'=>true
        );

        $rules['first_order'] = array(
            'name'=>__('First order (Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'purchase_history',
            'condition'=>'first_order',
            'pro'=>true
        );

        $rules['last_order'] = array(
            'name'=>__('Last Order Total (Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'purchase_history',
            'condition'=>'last_order',
            'pro'=>true
        );

        $rules['number_of_order'] = array(
            'name'=>__('Number of Orders during a period (Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'purchase_history',
            'condition'=>'number_of_order',
            'pro'=>true
        );

        $rules['total_of_orders'] = array(
            'name'=>__('Total amount spend during a period (Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'purchase_history',
            'condition'=>'total_of_orders',
            'pro'=>true
        );

        $rules['custom_attr_number'] = array(
            'name'=>__('Custom product attribute (Number)(Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'product_attributes',
            'condition'=>'custom_attr_number',
            'pro'=>true
        );

        $rules['custom_attr_text'] = array(
            'name'=>__('Custom product attribute (Text)(Available in PRO Version)','conditional-extra-fees-woocommerce'),
            'group'=>'product_attributes',
            'condition'=>'custom_attr_text',
            'pro'=>true
        );

        if(function_exists('wc_get_attribute_taxonomies')){
            $attributes = wc_get_attribute_taxonomies();
            if(is_array($attributes)){
                foreach($attributes as $att){
                    if(is_object($att)){
                        $rules['product_attribute_'.$att->attribute_id] = array(
                            'name'=>$att->attribute_label.' (Available in PRO Version)',
                            'group'=>'product_attributes',
                            'condition'=>'product_attribute_'.$att->attribute_id,
                            'pro'=>true
                        );
                    }
                }
            }
        }
        
        
        return $rules;
    }
}

new pisol_cefw_pro_rules(PI_CEFW_SELECTION_RULE_SLUG);