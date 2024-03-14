<?php

/**
 * ====================================================
 * Process/Get repositioned table header
 * ====================================================
 */

if( !function_exists( 'pvtfw_process_table_header' ) ){

    function pvtfw_process_table_header(){

        $default_columns = PVTFW_COMMON::get_default_columns();
        $columns_labels = PVTFW_COMMON::get_columns_labels();
        $columns = (array) get_option('pvtfw_variant_table_columns', $default_columns);


        // Removing off values from with column

        foreach($columns as $key=>$value)
        {
            if(is_null($value) || $value == 'off')
            unset($columns[$key]);
        }

        // New array initialization depending on column repositioning
        $latest = array();

        // Depending on column repositioning making output array
        foreach ($columns as $key => $value){
            if(!is_null($value) || $value != 'off'){
                $latest[$key] = $columns_labels[$key];
            }
        }

        return $latest;
    }

}

/**
 * ====================================================
 * Getting Product Attribute Labels
 * ====================================================
 */
if( !function_exists( 'get_attr_label' ) ){

    function get_attr_label( $atts ){

        $product = wc_get_product( absint( $atts["id"] ) );

        // Array to retrun label
        $attr_lbl = [];

        // Get the attribute label
        foreach ($product->get_variation_attributes() as $taxonomy => $term_names ) {
            $attr_lbl[] = wc_attribute_label($taxonomy);
        }
        return $attr_lbl;
    }

}

/**
 * ====================================================
 * Print table data for table head
 * 
 * @revised in 1.4.20
 * ====================================================
 */

if( !function_exists( 'pvtfw_print_table_header' ) ){

    function pvtfw_print_table_header( $atts ){

        $latest = pvtfw_process_table_header();
        $attr_lbl = get_attr_label( $atts );

        /**
         * Hook: pvtfw_pro_thead_th.
         *
         * @hooked 
         */
        do_action('pvtfw_pro_thead_th');

        // Arrow markup for filter the column icon
        $title_arrow = apply_filters( 'pvtfw_thead_arrows', '<span class="arrow"></span>' );

        foreach ($latest as $key => $label) {
            if($label == __("Action", "product-variant-table-for-woocommerce")){
                echo "<th class='{$key}'>".apply_filters('pvtfw_action_th_title', '&nbsp;')."</th>";
            }
            elseif($label == __("Attributes", "product-variant-table-for-woocommerce")){
                foreach($attr_lbl as $key => $lbl){
                    echo "<th class='{$lbl} sortable asc'>{$lbl}{$title_arrow}</th>";
                }
            }
            else{
                echo "<th class='{$key} sortable asc'>{$label}{$title_arrow}</th>";
            }
        }

        /**
         * ================================
         * Subtotal code here
         * ================================
         */
        $showSubTotal = PVTFW_COMMON::pvtfw_get_options()->showSubTotal;
        if($showSubTotal != ''):
            $key = __("subtotal", "product-variant-table-for-woocommerce");
            echo apply_filters('pvtfw_subtotal_title', "<th class='{$key} sortable asc'>".__("SubTotal", "product-variant-table-for-woocommerce")."{$title_arrow}</th>");
        endif;
    }

    add_action('pvtfw_table_header', 'pvtfw_print_table_header', 99, 1);

}