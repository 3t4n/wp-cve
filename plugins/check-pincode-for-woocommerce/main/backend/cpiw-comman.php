<?php
if (!defined('ABSPATH')){
    exit;
}

// Default values and save settings
add_action('init','cpiw_InitialSave');
function cpiw_InitialSave(){
    global $cpiw_comman;
    
    $optionget = array(
        'cpiw_enable' => 'enable',
        'cpiw_dateshow' => 'enable',
        'cpiw_codshow'=>'enable',
        'cpiw_poupshow'=>'enable',
        'cpiw_checkoutpincodevalidation'=>'enable',
        'cpiw_delivery_date_text'=>'Delivery Date',
        'cpiw_place_order_button_txt'=>'Choose valid zipcode in product page then place order',
        'cpiw_cashondeliimg_form'=>'',
        'date_image'=>'',
        'popuppincode_image'=>'',
        'cpiw_cashondelinotimg_form'=>'',
        'cpiw_changebutton_text'=>'Change',
        'cpiw_checkavail_text'=>'Check Availability At',
        'cpiw_pincodeplace_text'=>'pincode',
        'cpiw_checklocationtext_text'=>'Check your location availability info',
        'cpiw_cpopsubmit_text'=>'submit',
        'cpiw_cpopplaceholder_text'=>'Enter Pincode',
        'cpiw_emptyfield_text'=>'Pincode field should not be empty!',
        'checkavailbilitycolor'=>'#000000',
        'checkandchangetxtcolor'=>'#ffffff',
        'checkandchangebackcolor'=>'#cd2653',
        'mainbackcolor'=>'#f3f3f3',
        'deliverydatetextcolor'=>'#000000',
        'codtextcolor'=>'#000000',
        'submitbackcolor'=>'#cd2653',
        'popupbackcolor'=>'#ffffff',
        'popuptextcolor'=>'#000000',
        'submittextcolor'=>'#ffffff',
        'cpiw_popavailabletext'=>'We are available currently servicing your area.',
    );

    foreach ($optionget as $key_optionget => $value_optionget) {
       $cpiw_comman[$key_optionget] = get_option( $key_optionget,$value_optionget );
    }
}