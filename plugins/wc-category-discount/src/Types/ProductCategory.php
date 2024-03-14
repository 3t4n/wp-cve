<?php

namespace Wcd\DiscountRules\Types;
if (!defined('ABSPATH')) exit;

use Wcd\DiscountRules\WcFunctions;

class ProductCategory extends AbstractType
{
    protected function setDiscountTypeInfo()
    {
        return array('slug' => 'category_discount', 'label' => __('Category Discount', WCD_TEXT_DOMAIN));
    }

    /**
     * Render required fields
     */
    protected function renderDiscountField()
    {
        add_action('cmb2_admin_init', function () {
            //General settings tab
            $general_settings = new_cmb2_box([
                'id' => $this->prefix . 'category_discount_general_settings',
                'title' => __('Category discounts', WCD_TEXT_DOMAIN),
                'object_types' => array('options-page'),
                'option_key' => $this->type_info['slug'],
                'tab_group' => $this->type_info['slug'],
                'tab_title' => __('Category discounts', WCD_TEXT_DOMAIN),
                'save_button' => __('Save Settings', WCD_TEXT_DOMAIN),
                'icon_url' => 'dashicons-tag'
            ]);
            $general_settings->add_field(array(
                'name' => __('Would you like to display Discount message on cart page', WCD_TEXT_DOMAIN),
                'id' => $this->prefix . 'display_discount_name',
                'type' => 'radio_inline',
                'options' => array('1' => __('Yes', WCD_TEXT_DOMAIN), '0' => __('No', WCD_TEXT_DOMAIN)),
                'default' => '0'
            ));
            $general_settings->add_field(array(
                'name' => __('What message would you like to display on cart page', WCD_TEXT_DOMAIN),
                'id' => $this->prefix . 'display_discount_message',
                'desc' => '{{discount_name}} => Name of the rule.<br> {{discount_amount}} => How much discount was applied.<br>{{discount_type}}=>Percentage/Flat',
                'type' => 'textarea',
                'default' => 'Discount <strong>"{{discount_name}}"</strong> has been applied to your cart.'
            ));
            $general_settings->add_field(array(
                'name' => __('Apply discount on ', WCD_TEXT_DOMAIN),
                'id' => $this->prefix . 'applicable_discount',
                'type' => 'select',
                'options' => array(
                    '1' => __('First matched Rule', WCD_TEXT_DOMAIN),
                    '2' => __('Biggest price Rule', WCD_TEXT_DOMAIN),
                    '3' => __('Lowest price Rule', WCD_TEXT_DOMAIN),
                    '4' => __('Last matched Rule', WCD_TEXT_DOMAIN),
                    '5' => __('All matched Rule', WCD_TEXT_DOMAIN)
                ),
                'default' => '1'
            ));
            $general_settings->add_field(array(
                'name' => __('Discount Table ', WCD_TEXT_DOMAIN),
                'id' => $this->prefix . 'discount_table_format',
                'type' => 'select',
                'options' => array(
                    'disabled_layout' => __('Disabled', WCD_TEXT_DOMAIN),
                    'default_layout' => __('Default layout', WCD_TEXT_DOMAIN),
                ),
                'default' => 'disabled_layout',
                'desc' => __('Show or Hide discount table on the product page', WCD_TEXT_DOMAIN),
            ));
            $general_settings->add_field(array(
                'name' => __('Table Placement', WCD_TEXT_DOMAIN),
                'id' => $this->prefix . 'discount_table_placement',
                'type' => 'select',
                'options' => array(
                    'before_cart' => __('Before Add to cart form', WCD_TEXT_DOMAIN),
                    'after_cart' => __('After Add to cart form', WCD_TEXT_DOMAIN),
                ),
                'default' => 'before_cart',
                'desc' => __('Place the discount table on the product page', WCD_TEXT_DOMAIN),
            ));
            $general_settings->add_field(array(
                'name' => __('Discount table header on the product page', WCD_TEXT_DOMAIN),
                'id' => $this->prefix . 'discount_table_head',
                'type' => 'table_head',
            ));
            //Discount rules tab
            $discount_rules = new_cmb2_box([
                'id' => $this->prefix . 'category_discount_rules_settings',
                'title' => __('Category discounts', WCD_TEXT_DOMAIN),
                'menu_title' => __('Discount rules', WCD_TEXT_DOMAIN),
                'object_types' => array('options-page'),
                'option_key' => $this->type_info['slug'] . '_rules',
                'parent_slug' => $this->type_info['slug'],
                'tab_group' => $this->type_info['slug'],
                'tab_title' => __('Discount rules', WCD_TEXT_DOMAIN),
                'save_button' => __('Save Rules', WCD_TEXT_DOMAIN)
            ]);
            $discount_fields = $discount_rules->add_field(array(
                'id' => $this->prefix . 'category_discount_rules_group',
                'type' => 'group',
                'repeatable' => true,
                'options' => array(
                    'group_title' => 'Discount rule  {#}',
                    'add_button' => 'Add Another Discount rule',
                    'remove_button' => 'Remove Discount',
                    'closed' => true,
                    'sortable' => true
                )
            ));
            $discount_rules->add_group_field($discount_fields, array(
                'name' => __('Discount name', WCD_TEXT_DOMAIN),
                'desc' => __('Name of your discount', WCD_TEXT_DOMAIN),
                'id' => $this->prefix . 'discount_name',
                'type' => 'text',
                'attributes' => ['data-name' => 'discount_name', 'required' => 'required']
            ));
            $discount_rules->add_group_field($discount_fields, array(
                'name' => __('Discount Category', WCD_TEXT_DOMAIN),
                'desc' => __('Choose whatever categories you need to give discount', WCD_TEXT_DOMAIN),
                'id' => $this->prefix . 'discount_category',
                'type' => 'wcd_pw_multiselect',
                'options' => $this->getCategories()
            ));

            /**
             * clone fields
             */
            $discount_rules->add_group_field($discount_fields, array(
                'name' => __('Discount Rule', WCD_TEXT_DOMAIN),
                'desc' => __('If left empty, this rule will not apply.', WCD_TEXT_DOMAIN),
                'id' => $this->prefix . 'discount_range_repeater',
                'type' => 'address',
                'repeatable' => true,
            ));

            $discount_rules->add_group_field($discount_fields, array(
                'name' => __('Discount starts on', WCD_TEXT_DOMAIN),
                'desc' => __('When will this discount starts (optional)', WCD_TEXT_DOMAIN),
                'id' => $this->prefix . 'discount_start_date',
                'type' => 'text_date',
                'date_format' => 'Y-m-d'
            ));
            $discount_rules->add_group_field($discount_fields, array(
                'name' => __('Discount ends on', WCD_TEXT_DOMAIN),
                'desc' => __('When will this discount ends(optional)', WCD_TEXT_DOMAIN),
                'id' => $this->prefix . 'discount_end_date',
                'type' => 'text_date',
                'date_format' => 'Y-m-d'
            ));
            $discount_rules->add_group_field($discount_fields, array(
                'name' => __('Enable rule', WCD_TEXT_DOMAIN),
                'id' => $this->prefix . 'enable_discount_rule',
                'type' => 'radio_inline',
                'options' => array('1' => __('Yes', WCD_TEXT_DOMAIN), '0' => __('No', WCD_TEXT_DOMAIN)),
                'default' => '1'
            ));

        });
        //Dynamic Table Header
        add_filter( 'cmb2_render_table_head', [new ProductCategory(), 'cmb2_render_table_head_field_callback'], 10, 5 );


    }

    /**
     * Get all categories
     * @return array - list of all categories
     */
    function getCategories()
    {
        $categories = array();
        $category_list = get_terms('product_cat', array(
            'orderby' => 'name',
            'order' => 'asc',
            'hide_empty' => false
        ));
        if (!empty($category_list)) {
            foreach ($category_list as $category) {
                $categories[$category->term_id] = $category->name;
            }
        }
        return $categories;
    }

    /**
     * get general setting
     * @return mixed
     */
    function getGeneralSettings()
    {
        return get_option('category_discount', array());
    }

    /**
     * get table placement settings
     * @return bool|mixed|string
     */
    function tablePlacementSetting(){
        $category_general_settings = $this->getGeneralSettings();
        $discount_table_format = isset($category_general_settings[$this->prefix . 'discount_table_format']) ? $category_general_settings[$this->prefix . 'discount_table_format'] : '';
        if($discount_table_format != '' && $discount_table_format === 'disabled_layout' || $discount_table_format === ''){
            return false;
        }else{
            return isset($category_general_settings[$this->prefix . 'discount_table_placement']) ? $category_general_settings[$this->prefix . 'discount_table_placement'] : '';
        }

    }

    /**
     * get category rules
     * @return mixed
     */
    function getRules()
    {
            return get_option('category_discount_rules', array());
    }

    /**
     * Calculate discount for all products
     *
     * @param $item - product object
     * @return float|int|mixed
     */
    function calculateDiscount($item)
    {

        $wc_functions = new WcFunctions();
        $item_price = $wc_functions->getItemPrice($item);
        $category_general_settings = $this->getGeneralSettings();
        $category_discount_rules = $this->getRules();
        if (is_admin())
            return 0;

        $all_category_rules = isset($category_discount_rules[$this->prefix . 'category_discount_rules_group']) ? $category_discount_rules[$this->prefix . 'category_discount_rules_group'] : array();
        if (empty($all_category_rules))
            return 0;

        $product_categories = $wc_functions->getItemCategories($item);
        if (empty($product_categories)) {
            $product_id = $wc_functions->getParentId($item);
            if (empty($product_id))
                return 0;
            $parent_item = $wc_functions->getItem($product_id);
            $product_categories = $wc_functions->getItemCategories($parent_item);
        }

        if (empty($product_categories))
            return 0;

        $matched_rules = array();
        foreach ($all_category_rules as $rule_key => $rule) {
            $enable_discount_rule = (isset($rule[$this->prefix . 'enable_discount_rule'])) ? $rule[$this->prefix . 'enable_discount_rule'] : 1;
            if (!$enable_discount_rule)
                continue;

            if (isset($rule[$this->prefix . 'discount_range_repeater']) && !empty($rule[$this->prefix . 'discount_range_repeater'])) {
                $discount_categories = (isset($rule[$this->prefix . 'discount_category']) && !empty($rule[$this->prefix . 'discount_category'])) ? $rule[$this->prefix . 'discount_category'] : array();
                if (array_intersect($discount_categories, $product_categories)) {
                    $discount_starts_on = isset($rule[$this->prefix . 'discount_start_date']) ? $rule[$this->prefix . 'discount_start_date'] : '';
                    $discount_ends_on = isset($rule[$this->prefix . 'discount_end_date']) ? $rule[$this->prefix . 'discount_end_date'] : '';
                    if (!empty($discount_starts_on) && (strtotime(date('Y-m-d')) < strtotime($discount_starts_on)))
                        continue;

                    if (!empty($discount_ends_on) && (strtotime(date('Y-m-d')) > strtotime($discount_ends_on)))
                        continue;
                    /*$minimum_discount_price = isset($rule[$this->prefix . 'minimum_discount_price']) ? $rule[$this->prefix . 'minimum_discount_price'] : '';
                    $minimum_discount_price = str_replace(',', '', $minimum_discount_price);
                    $maximum_discount_price = isset($rule[$this->prefix . 'maximum_discount_price']) ? $rule[$this->prefix . 'maximum_discount_price'] : '';
                    $maximum_discount_price = str_replace(',', '', $maximum_discount_price);
                    if (!empty($minimum_discount_price) && (float)$discounted_price < (float)$minimum_discount_price)
                        continue;
                    if (!empty($maximum_discount_price) && (float)$discounted_price > (float)$maximum_discount_price)
                        continue;*/
                    foreach ($rule[$this->prefix . 'discount_range_repeater'] as $discount_range) {


                        $minimum_product_rule = (!empty($discount_range['minimum-product'])) ? $discount_range['minimum-product'] : 0;
                        $maximum_product_rule = (!empty($discount_range['maximum-product'])) ? $discount_range['maximum-product'] : 'unlimited';
                        $discount_price = (!empty($discount_range['amount'])) ? $discount_range['amount'] : 0;
                        $discount_type = (!empty($discount_range['discount-type'])) ? $discount_range['discount-type'] : '';
                        if ($discount_type == '' || $discount_price == 0)
                            continue;

                        switch ($discount_type) {
                            case 'percentage':
                                $discounted_price = (($discount_price / 100) * $item_price);
                                break;
                            case 'flat':
                            default:
                                $discounted_price = $discount_price;
                                break;
                        }

                        $discounted_price = ($discounted_price <= 0) ? 0 : $discounted_price;

                        if( $minimum_product_rule !== 0 || $maximum_product_rule !== 'unlimited'){
                            return $discounted_price;
                        }
                        $matched_rules[] = array('price' => $discounted_price, 'rule' => $rule, 'rule_id' => $rule_key, 'if_min' => '0', 'if_max' => '0', 'if_type' => $discount_type);

                    }
                }
            }
        }
        if (empty($matched_rules))
            return 0;

        $applicable_rule = isset($category_general_settings[$this->prefix . 'applicable_discount']) ? $category_general_settings[$this->prefix . 'applicable_discount'] : 1;
        switch ($applicable_rule) {
            case 5:
                $discounted_price = array_sum(array_column($matched_rules, 'price'));
                $this->applied_rule['rules'] = $matched_rules;
                break;
            case 4:
                $to_apply = end($matched_rules);
                $discounted_price = $to_apply['price'];
                $this->applied_rule['rules'][] = array('price' => $discounted_price, 'rule' => $to_apply['rule'], 'rule_id' => $to_apply['rule_id']);
                break;
            case 3:
                $discounted_price = min(array_column($matched_rules, 'price'));
                $array_index = array_search($discounted_price, $matched_rules, true);
                $this->applied_rule['rules'][] = array('price' => $discounted_price, 'rule' => isset($matched_rules[$array_index]['rule']) ? $matched_rules[$array_index]['rule'] : array(), 'rule_id' => $matched_rules[$array_index]['rule_id']);
                break;
            case 2:
                $discounted_price = max(array_column($matched_rules, 'price'));
                $array_index = array_search($discounted_price, $matched_rules, true);
                $this->applied_rule['rules'][] = array('price' => $discounted_price, 'rule' => isset($matched_rules[$array_index]['rule']) ? $matched_rules[$array_index]['rule'] : array(), 'rule_id' => $matched_rules[$array_index]['rule_id']);
                break;
            case 1:
            default:
                $discounted_price = $matched_rules[0]['price'];
                $this->applied_rule['rules'][] = array('price' => $discounted_price, 'rule' => $matched_rules[0]['rule'], 'rule_id' => $matched_rules[0]['rule_id']);
                break;
        }
        $product_ids = $wc_functions->getProductId($item);
        $this->applied_rule['rules']['applied_for'] = $product_ids;
        $display_discount_name = isset($category_general_settings[$this->prefix . 'display_discount_name']) ? $category_general_settings[$this->prefix . 'display_discount_name'] : 0;
        if (!$display_discount_name) {
            $this->applied_rule['show'] = 0;
        }
        $this->applied_rule['discount_message'] = (isset($category_general_settings[$this->prefix . 'display_discount_message']) && !empty($category_general_settings[$this->prefix . 'display_discount_message'])) ? $category_general_settings[$this->prefix . 'display_discount_message'] : 'Discount <strong>"{{discount_name}}"</strong> has been applied to your cart.';
        $this->applied_rule['app_prefix'] = $this->prefix;
        $this->applied_message[$product_ids][] = $this->applied_rule;
        return $discounted_price = ($discounted_price <= 0) ? $item_price : $discounted_price;
    }

    /**
     * set applied rule for min max range disconts
     * @param $matched_rules
     * @param $product_id
     * @return int
     */
    function minMaxMatchedRule($matched_rules, $product_id)
    {

        if (empty($matched_rules))
            return 0;
       // $category_general_settings = get_option('category_discount', array());
        $category_general_settings = $this->getGeneralSettings();
        $applicable_rule = isset($category_general_settings[$this->prefix . 'applicable_discount']) ? $category_general_settings[$this->prefix . 'applicable_discount'] : 1;
        switch ($applicable_rule) {
            case 5:
                $discounted_price = array_sum(array_column($matched_rules, 'price'));
                $this->applied_rule['rules'] = $matched_rules;
                break;
            case 4:
                $to_apply = end($matched_rules);
                $discounted_price = $to_apply['price'];
                $this->applied_rule['rules'][] = array('price' => $discounted_price, 'rule' => $to_apply['rule'], 'rule_id' => $to_apply['rule_id']);
                break;
            case 3:
                $discounted_price = min(array_column($matched_rules, 'price'));
                $array_index = array_search($discounted_price, $matched_rules, true);
                $this->applied_rule['rules'][] = array('price' => $discounted_price, 'rule' => isset($matched_rules[$array_index]['rule']) ? $matched_rules[$array_index]['rule'] : array(), 'rule_id' => $matched_rules[$array_index]['rule_id']);
                break;
            case 2:
                $discounted_price = max(array_column($matched_rules, 'price'));
                $array_index = array_search($discounted_price, $matched_rules, true);
                $this->applied_rule['rules'][] = array('price' => $discounted_price, 'rule' => isset($matched_rules[$array_index]['rule']) ? $matched_rules[$array_index]['rule'] : array(), 'rule_id' => $matched_rules[$array_index]['rule_id']);
                break;
            case 1:
            default:
                $discounted_price = $matched_rules[0]['price'];
                $this->applied_rule['rules'][] = array('price' => $discounted_price, 'rule' => $matched_rules[0]['rule'], 'rule_id' => $matched_rules[0]['rule_id']);
                break;
        }
        $this->applied_rule['rules']['applied_for'] = $product_id;
        $display_discount_name = isset($category_general_settings[$this->prefix . 'display_discount_name']) ? $category_general_settings[$this->prefix . 'display_discount_name'] : 0;
        if (!$display_discount_name) {
            $this->applied_rule['show'] = 0;
        }
        $this->applied_rule['discount_message'] = (isset($category_general_settings[$this->prefix . 'display_discount_message']) && !empty($category_general_settings[$this->prefix . 'display_discount_message'])) ? $category_general_settings[$this->prefix . 'display_discount_message'] : 'Discount <strong>"{{discount_name}}"</strong> has been applied to your cart.';
        //$this->applied_message['discount_messages'][] = (isset($category_general_settings[$this->prefix . 'display_discount_message']) && !empty($category_general_settings[$this->prefix . 'display_discount_message'])) ? $category_general_settings[$this->prefix . 'display_discount_message'] : 'Discount <strong>"{{discount_name}}"</strong> has been applied to your cart.';
        $this->applied_rule['app_prefix'] = $this->prefix;
        $this->applied_message[$product_id][] = $this->applied_rule;
        return $discounted_price = ($discounted_price <= 0) ? 0 : $discounted_price;
    }

    /**
     * Get applied discounts
     * @return array
     */
    function getAppliedDiscounts()
    {
        if(isset($this->applied_message) && !empty($this->applied_message)){
            return $this->applied_message;
        }
        return array();
    }

    /**
     * Create Table head field using cmb2
     * @param $field
     * @param $value
     * @param $object_id
     * @param $object_type
     * @param $field_type
     */
    public function cmb2_render_table_head_field_callback( $field, $value, $object_id, $object_type, $field_type ) {

        // make sure we specify each part of the value we need.
        $value = wp_parse_args( $value, array(
            'rule_name'         => '',
            'discount_range'     => '',
            'discount_value'    => '',
        ) );

        ?>
            <?php echo $field_type->input( array(
                'class' => 'cmb_text_small',
                'name'  => $field_type->_name( '[rule_name]' ),
                'id'    => $field_type->_id( '_rule_name' ),
                'value' => $value['rule_name'],
                'placeholder'  => 'Name column title',
                'type'  => 'text',
            ) ); ?>


            <?php echo $field_type->input( array(
                'class' => 'cmb_text_small',
                'name'  => $field_type->_name( '[discount_range]' ),
                'id'    => $field_type->_id( '_discount_range' ),
                'value' => $value['discount_range'],
                'placeholder'  => 'Range column title',
                'type'  => 'text',
            ) ); ?>

            <?php echo $field_type->input( array(
                'class' => 'cmb_text_small',
                'name'  => $field_type->_name( '[discount_value]' ),
                'id'    => $field_type->_id( '_discount_value' ),
                'value' => $value['discount_value'],
                'placeholder'  => 'Discount column title',
                'type'  => 'text',
            ) ); ?>
        <br class="clear">
        <?php
        echo $field_type->_desc( true );

    }
}
