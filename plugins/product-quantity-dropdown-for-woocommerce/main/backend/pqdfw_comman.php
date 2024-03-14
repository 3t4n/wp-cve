<?php
/* Common file */
function pqdfw_init() {
    global $pqdfw_comman;
    $optionget = array(
        'enable_plugin' => 'yes',
        'pqdfw_min_quantity' => '1',
        'pqdfw_max_quantity' => '20',
        'pqdfw_step_quantity' => '1',
        'pqdfw_dropdown_lable' => 'Qty',
    );
   
    foreach ($optionget as $key_optionget => $value_optionget) {
       $pqdfw_comman[$key_optionget] = get_option( $key_optionget,$value_optionget );
    }
}
add_action('init','pqdfw_init');
?>