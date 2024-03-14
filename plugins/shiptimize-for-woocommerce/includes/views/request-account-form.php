<?php 
/**  
 * print a single field in the form 
 * try to get the value from the vendor object 
 */ 
function shiptimize_account_print_field ( $label, $name , $vendor ) { 
?>
    <div class='form-item'>
        <label><?php echo $label ?></label>
        <input type='text' name='<?php echo $name?>' value="<?php echo isset($vendor->$name) ? $vendor->$name : ''?>"/>
    </div>
<?php     
}

/** 
 *  Object Vendor containing all fields we can pre-fill from market place info 
 */ 
function shiptimize_account_print_form($vendor, $error, $message) {
    $shiptimize = WooShiptimize::instance(); 
    get_header();  

    echo "<h2> " . $shiptimize->translate("requestaccount") . " </h2> "; 

    if($message){
        echo "$message<br/>"; 
    }

    if($message && !$error){
        die(); 
    }

    echo "<form class='shiptimize-request-account' action='" . get_site_url() . "/shiptimize-request-account?vendor_id=" . $vendor->userid . "' method='POST'>"; 
     
    shiptimize_account_print_field( $shiptimize->translate('companyname'), 'companyname',$vendor ); 
    shiptimize_account_print_field( $shiptimize->translate('contactperson'),'name', $vendor );  
    shiptimize_account_print_field( $shiptimize->translate('contactphone'),'phone',$vendor ); 
    shiptimize_account_print_field( $shiptimize->translate('fiscal'),'fiscal',$vendor ); 
    shiptimize_account_print_field("Email", 'email', $vendor ); 

    echo "<br/><br/><h2>Shipping information</h2>"; 
    shiptimize_account_print_field($shiptimize->translate('streetname'),'streetname', $vendor );

    shiptimize_account_print_field( $shiptimize->translate("zipcode"),'zipcode', $vendor );
    shiptimize_account_print_field( $shiptimize->translate('city'), 'city',$vendor ); 
    shiptimize_account_print_field( $shiptimize->translate('province'), 'province',$vendor ); 
    shiptimize_account_print_field( $shiptimize->translate('country'), 'country',$vendor );
    echo "<br/><br/><button type='submit'>" . $shiptimize->translate('submitrequest') . "</button>"; 
    echo "</form>";
}