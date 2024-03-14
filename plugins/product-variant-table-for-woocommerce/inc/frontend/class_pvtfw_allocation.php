<?php

require_once(plugin_dir_path(__FILE__) . 'class_pvtfw_available_btn.php');
require_once(plugin_dir_path(__FILE__) . 'class_pvtfw_print_table.php');

class PVTFW_ALLOCATION{


    function pvtfw_variant_table_init() {

        global $pvtfw_print_table, $pvtfw_available_btn;

        $place = get_option('pvtfw_variant_table_place', 'woocommerce_after_single_product_summary_9');
        $priority = strrchr($place, "_");
        $place = str_replace($priority, "", $place);
        $priority = str_replace("_", "", $priority);
        add_action($place, array($pvtfw_print_table, 'print_table'), $priority);

        $showAvailableOptionBtn = get_option('pvtfw_variant_table_show_available_options_btn', 'on');
        if ($showAvailableOptionBtn == 'on') {
            add_action('woocommerce_single_product_summary', array($pvtfw_available_btn, 'available_options_btn'), 11);
        }
    }

}

$pvtfw_allocation = new PVTFW_ALLOCATION();