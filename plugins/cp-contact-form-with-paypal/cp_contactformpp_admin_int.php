<?php

if ( !defined('CP_CONTACTFORMPP_AUTH_INCLUDE') ) { echo 'Direct access not allowed.';  exit; }

if ( !is_admin() ) 
{
    echo 'Direct access not allowed.';
    exit;
}

$nonce = wp_create_nonce( 'cfwpp_update_actions_post' );

if (!defined('CP_CONTACTFORMPP_ID'))
    define ('CP_CONTACTFORMPP_ID',intval($_GET["cal"]));

define('CP_CONTACTFORMPP_DEFAULT_fp_from_email', cp_contactformpp_get_default_from_email() );


define('CP_CONTACTFORMPP_DEFAULT_fp_destination_emails', get_the_author_meta('user_email', get_current_user_id()) );

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['cp_contactformpp_post_options'] ) )
    echo "<div id='setting-error-settings_updated' class='updated settings-error'> <p><strong>Settings saved.</strong></p></div>";

?>
<script type="text/javascript">
 function displaymorein(id)
 {
    document.getElementById("cpcfppmorein"+id).style.display="";
    document.getElementById("cpcfppmoreinlink"+id).style.display="none";
 }
 function displaylessin(id)
 {
    document.getElementById("cpcfppmorein"+id).style.display="none";
    document.getElementById("cpcfppmoreinlink"+id).style.display="";
 }
</script>
<div class="wrap">
<h1>PayPal Form - <?php _e('Settings for','cp-contact-form-with-paypal'); ?>: <span style="color:#006600;font-weight:bold;"><?php echo esc_html(cp_contactformpp_get_option('form_name', '')); ?></span></h1>


<input type="button" name="backbtn" value="Back to items list..." onclick="document.location='admin.php?page=cp_contact_form_paypal.php';">
<br /><br />

<form method="post" action="" name="cpformconf"> 
<input name="cp_contactformpp_post_options" type="hidden" value="1" />
<input name="rsave" type="hidden" value="<?php echo esc_attr($nonce); ?>" />
<input name="cp_contactformpp_id" type="hidden" value="<?php echo intval(CP_CONTACTFORMPP_ID); ?>" />

   
<div id="normal-sortables" class="meta-box-sortables">

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e('Form Processing','cp-contact-form-with-paypal'); ?> / <?php _e('Email Settings','cp-contact-form-with-paypal'); ?></span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top">
        <th scope="row"><?php _e('"From" email','cp-contact-form-with-paypal'); ?></th>
        <td><input required type="email" name="fp_from_email" size="40" value="<?php echo esc_attr(cp_contactformpp_get_option('fp_from_email', CP_CONTACTFORMPP_DEFAULT_fp_from_email)); ?>" /></td>
        </tr>             
        <tr valign="top">
        <th scope="row"><?php _e('Destination emails (comma separated)','cp-contact-form-with-paypal'); ?></th>
        <td><input required type="text" name="fp_destination_emails" size="40" value="<?php echo esc_attr(cp_contactformpp_get_option('fp_destination_emails', CP_CONTACTFORMPP_DEFAULT_fp_destination_emails)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e('Email subject','cp-contact-form-with-paypal'); ?></th>
        <td><input type="text" name="fp_subject" size="70" value="<?php echo esc_attr(cp_contactformpp_get_option('fp_subject', CP_CONTACTFORMPP_DEFAULT_fp_subject)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e('Include additional information?','cp-contact-form-with-paypal'); ?></th>
        <td>
          <?php $option = cp_contactformpp_get_option('fp_inc_additional_info', CP_CONTACTFORMPP_DEFAULT_fp_inc_additional_info); ?>
          <select name="fp_inc_additional_info">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e('Yes','cp-contact-form-with-paypal'); ?></option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e('No','cp-contact-form-with-paypal'); ?></option>
          </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e('Thank you page (after sending the message)','cp-contact-form-with-paypal'); ?></th>
        <td><input required type="text" name="fp_return_page" size="70" value="<?php echo esc_attr(cp_contactformpp_get_option('fp_return_page', CP_CONTACTFORMPP_DEFAULT_fp_return_page)); ?>" /></td>
        </tr>          
        <tr valign="top">
        <th scope="row"><?php _e('Error page (if the payment process isn\'t completed)','cp-contact-form-with-paypal'); ?></th>
        <td><input type="text" name="fp_error_page" size="70" value="<?php echo esc_attr(cp_contactformpp_get_option('fp_error_page', '')); ?>" />
            <br /><em><?php _e('Leave empty to send the user back to the page that contains the form.','cp-contact-form-with-paypal'); ?></em>
        </td>
        </tr>          
        <tr valign="top">
        <th scope="row"><?php _e('Email format?','cp-contact-form-with-paypal'); ?></th>
        <td>
          <?php $option = cp_contactformpp_get_option('fp_emailformat', CP_CONTACTFORMPP_DEFAULT_email_format); ?>
          <select name="fp_emailformat">
           <option value="text"<?php if ($option != 'html') echo ' selected'; ?>><?php _e('Plain Text (default)','cp-contact-form-with-paypal'); ?></option>
          </select>
        </td>
        </tr>        
        <tr valign="top">
        <th scope="row"><?php _e('Message','cp-contact-form-with-paypal'); ?></th>
        <td><textarea type="text" name="fp_message" rows="6" cols="80"><?php echo esc_textarea(cp_contactformpp_get_option('fp_message', CP_CONTACTFORMPP_DEFAULT_fp_message)); ?></textarea></td>
        </tr>                                                               
     </table>  
  </div>    
 </div>   
 
 
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e('Paypal Payment Configuration','cp-contact-form-with-paypal'); ?></span></h3>
  <div class="inside">

    <table class="form-table">
    
        <tr valign="top">
        <th scope="row" colspan="2" style="padding:3px;background-color:#cccccc;text-align:center;"><?php _e('Main PayPal Integration Settings','cp-contact-form-with-paypal'); ?>:</th>
        </tr>
      
        <tr valign="top">        
        <th scope="row"><strong><?php _e('PayPal email','cp-contact-form-with-paypal'); ?></strong></th>
        <td><input required type="email" name="paypal_email" size="40" value="<?php echo esc_attr(cp_contactformpp_get_option('paypal_email',CP_CONTACTFORMPP_DEFAULT_PAYPAL_EMAIL)); ?>" />
          <br />
          <em><?php _e('Important! Enter here the email address linked to your PayPal account.','cp-contact-form-with-paypal'); ?></em>
        </td>
        </tr>                 
         
        <tr valign="top">
        <th scope="row"><?php _e('Request cost','cp-contact-form-with-paypal'); ?></th>
        <td><input required type="text" step="any" name="request_cost" size="5" value="<?php echo esc_attr(cp_contactformpp_get_option('request_cost',CP_CONTACTFORMPP_DEFAULT_COST)); ?>" /><?php $currency = strtoupper(esc_attr(cp_contactformpp_get_option('currency',CP_CONTACTFORMPP_DEFAULT_CURRENCY))); ?>
<select name="currency" onchange="javascript:cpExplainCurrency(this);">
<option value="USD"<?php if ($currency == 'USD' || $currency == '') echo ' selected'; ?>>USD - U.S. Dollar</option>
<option value="EUR"<?php if ($currency == 'EUR') echo ' selected'; ?>>EUR - Euro</option>
<option value="GBP"<?php if ($currency == 'GBP') echo ' selected'; ?>>GBP - Pound Sterling</option>
<option value="USD"> - </option>
<option value="ARS"<?php if ($currency == 'ARS') echo ' selected'; ?>>ARS - Argentine peso</option>
<option value="AUD"<?php if ($currency == 'AUD') echo ' selected'; ?>>AUD - Australian Dollar</option>
<option value="BRL"<?php if ($currency == 'BRL') echo ' selected'; ?>>BRL - Brazilian Real</option>
<option value="CAD"<?php if ($currency == 'CAD') echo ' selected'; ?>>CAD - Canadian Dollar</option>
<option value="CZK"<?php if ($currency == 'CZK') echo ' selected'; ?>>CZK - Czech Koruna</option>
<option value="DKK"<?php if ($currency == 'DKK') echo ' selected'; ?>>DKK - Danish Krone</option>
<option value="HKD"<?php if ($currency == 'HKD') echo ' selected'; ?>>HKD - Hong Kong Dollar</option>
<option value="HUF"<?php if ($currency == 'HUF') echo ' selected'; ?>>HUF - Hungarian Forint</option>
<option value="INR"<?php if ($currency == 'INR') echo ' selected'; ?>>INR - Indian Rupee</option>
<option value="ILS"<?php if ($currency == 'ILS') echo ' selected'; ?>>ILS - Israeli New Sheqel</option>
<option value="INR"<?php if ($currency == 'INR') echo ' selected'; ?>>INR - Indian Rupee</option>
<option value="JPY"<?php if ($currency == 'JPY') echo ' selected'; ?>>JPY - Japanese Yen</option>
<option value="MYR"<?php if ($currency == 'MYR') echo ' selected'; ?>>MYR - Malaysian Ringgit</option>
<option value="MXN"<?php if ($currency == 'MXN') echo ' selected'; ?>>MXN - Mexican Peso</option>	
<option value="NOK"<?php if ($currency == 'NOK') echo ' selected'; ?>>NOK - Norwegian Krone</option>	
<option value="NZD"<?php if ($currency == 'NZD') echo ' selected'; ?>>NZD - New Zealand Dollar</option>	
<option value="PHP"<?php if ($currency == 'PHP') echo ' selected'; ?>>PHP - Philippine Peso</option>	
<option value="PLN"<?php if ($currency == 'PLN') echo ' selected'; ?>>PLN - Polish Zloty</option>		
<option value="RUB"<?php if ($currency == 'RUB') echo ' selected'; ?>>RUB - Russian Ruble</option>
<option value="SGD"<?php if ($currency == 'SGD') echo ' selected'; ?>>SGD - Singapore Dollar</option>	
<option value="SEK"<?php if ($currency == 'SEK') echo ' selected'; ?>>SEK - Swedish Krona</option>
<option value="CHF"<?php if ($currency == 'CHF') echo ' selected'; ?>>CHF - Swiss Franc</option>
<option value="TWD"<?php if ($currency == 'TWD') echo ' selected'; ?>>TWD - Taiwan New Dollar</option>
<option value="THB"<?php if ($currency == 'THB') echo ' selected'; ?>>THB - Thai Baht</option>
<option value="USD"<?php if ($currency == 'nocurrency') echo ' selected'; ?>> - Other Currency? -</option>
</select>
<script type="text/javascript">
function cpExplainCurrency(fld)
{
    var sel = fld.options[fld.options.selectedIndex].text;
    if (sel == '- Other Currency? -')
        document.getElementById("cpexplaincurr").style.display = '';
}
</script>
<div id="cpexplaincurr" style="display:none;padding:15px;background-color:#EDF5FF;border:1px solid #808080;margin-top:5px;">
<p>The currencies listed in this drop-down are the <a href="https://developer.paypal.com/docs/classic/api/currency_codes/#paypal" target="_blank">currencies supported by PayPal</a> to accept payments. Since this version
of the plugin requires the PayPal integration only the PayPal supported currencies are listed here.</p><br />

<p>The commercial versions of the plugin support all currencies since PayPal is optional in those versions and some distributions
also support integration with other payment gateways.</p><br />

<p>If you need further information or solution about this currency setting you can <a href="https://cfpaypal.dwbooster.com/contact-us">contact our support service</a>.</p>
</div>
</td>
        </tr>             
         
        <tr valign="top">
        <th scope="row"><?php _e('Paypal product name','cp-contact-form-with-paypal'); ?></th>
        <td><input required type="text" name="paypal_product_name" size="50" value="<?php echo esc_attr(cp_contactformpp_get_option('paypal_product_name',CP_CONTACTFORMPP_DEFAULT_PRODUCT_NAME)); ?>" /></td>
        </tr>         
        

       <tr valign="top">
        <th scope="row"><?php _e('PayPal Integration','cp-contact-form-with-paypal'); ?></th>
        <td><select name="enable_paypal" id="enable_paypal" onchange="cfpp_update_pp_payment_selection();">             
             <option value="1" <?php if (cp_contactformpp_get_option('enable_paypal','1') == '1') echo 'selected'; ?>>PayPal Standard</option>              
             <option value="101" <?php if (cp_contactformpp_get_option('enable_paypal','1') == '101') echo 'selected'; ?>>PayPal Express + PayPal Credit</option> 
            </select>
            <em><?php _e('Note: Leave the default option PayPal Standard if you aren\'t sure.','cp-contact-form-with-paypal'); ?></em>
            
            <div id="cfpp_paypal_options_express"  style="display:none;margin-top:10px;background:#EEF5FB;border: 1px dotted #888888;padding:10px;width:520px;">
              <table>
              <tr valign="top">        
               <th scope="row" colspan="2" style="background-color:#cccccc;padding:3px;text-align:center;"><?php _e('Required for','cp-contact-form-with-paypal'); ?> <span style="color:#008800">PayPal Express</span> <?php _e('and for the','cp-contact-form-with-paypal'); ?> <span style="color:#008800"><?php _e('Refund Feature','cp-contact-form-with-paypal'); ?></span>:</th>
               </tr>   
               <tr valign="top">        
               <th scope="row" style="padding:3px;">PayPal (NVP) - API UserName</th>
               <td style="padding:3px;"><input type="text" name="paypalexpress_api_username" size="40" value="<?php echo esc_attr(cp_contactformpp_get_option('paypalexpress_api_username','')); ?>" /></td>
               </tr>   
               <tr valign="top">        
               <th scope="row" style="padding:3px;">PayPal (NVP) - API Password</th>
               <td style="padding:3px;"><input type="password" name="paypalexpress_api_password" size="40" value="<?php echo esc_attr(cp_contactformpp_get_option('paypalexpress_api_password','')); ?>" /></td>
               </tr>   
               <tr valign="top">        
               <th scope="row" style="padding:3px;">PayPal (NVP) - API Signature</th>
               <td style="padding:3px;"><input type="password" name="paypalexpress_api_signature" size="40" value="<?php echo esc_attr(cp_contactformpp_get_option('paypalexpress_api_signature','')); ?>" /></td>
               </tr>      
              </table>    
              <div id="cfpp_paypal_options_label_express" style="display:none;margin-top:10px;background:#EEF5FB;border: 1px dotted #888888;padding:10px;width:260px;">
                <?php _e('Label for the','cp-contact-form-with-paypal'); ?> "<strong><?php _e('Pay with PayPal Credit (if enabled)','cp-contact-form-with-paypal'); ?></strong>" <?php _e('option','cp-contact-form-with-paypal'); ?>:<br />
                <input type="text" name="enable_paypal_expresscredit_yes" size="40" style="width:250px;" value="<?php echo esc_attr(cp_contactformpp_get_option('enable_paypal_expresscredit_yes',CP_CONTACTFORMPP_DEFAULT_PAYPAL_EXPRESSCREDIT_YES)); ?>" />
                <br />
                <?php _e('Label for the','cp-contact-form-with-paypal'); ?> "<strong><?php _e('Pay with PayPal Express (classic)','cp-contact-form-with-paypal'); ?></strong>" <?php _e('option','cp-contact-form-with-paypal'); ?>:<br />
                <input type="text" name="enable_paypal_expresscredit_no" size="40" style="width:250px;"  value="<?php echo esc_attr(cp_contactformpp_get_option('enable_paypal_expresscredit_no',CP_CONTACTFORMPP_DEFAULT_PAYPAL_EXPRESSCREDIT_NO)); ?>" />                
              </div>        
            </div>          
        </td>
        </tr>    

        <tr valign="top">
        <th scope="row"><?php _e('Process refunds through this plugin?','cp-contact-form-with-paypal'); ?></th>
        <td>
          <?php $option = cp_contactformpp_get_option('pprefunds', 'false'); ?>
          <select name="pprefunds" id="pprefunds" onchange="cfpp_update_pp_payment_selection();">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e('Yes','cp-contact-form-with-paypal'); ?></option>
           <option value="false"<?php if ($option != 'true') echo ' selected'; ?>><?php _e('No','cp-contact-form-with-paypal'); ?></option>
          </select>
        </td>
        </tr> 
        
  
        <tr valign="top">
        <th scope="row" colspan="2" style="padding:3px;background-color:#cccccc;text-align:center;"><?php _e('Other PayPal Integration Settings','cp-contact-form-with-paypal'); ?>:</th>
        </tr>
        
        
        <tr valign="top">        
        <th scope="row"><?php _e('Paypal Mode','cp-contact-form-with-paypal'); ?></th>
        <td><select name="paypal_mode">
             <option value="production" <?php if (cp_contactformpp_get_option('paypal_mode',CP_CONTACTFORMPP_DEFAULT_PAYPAL_MODE) != 'sandbox') echo 'selected'; ?>><?php _e('Production - real payments processed','cp-contact-form-with-paypal'); ?></option> 
             <option value="sandbox" <?php if (cp_contactformpp_get_option('paypal_mode',CP_CONTACTFORMPP_DEFAULT_PAYPAL_MODE) == 'sandbox') echo 'selected'; ?>>SandBox - <?php _e('PayPal testing sandbox are','cp-contact-form-with-paypal'); ?>a</option> 
            </select>
            <br />
           <em> * <?php _e('Note that if you are testing it in a localhost site the PayPal IPN notification won\'t reach to your website. Related FAQ entry','cp-contact-form-with-paypal'); ?>:
            <a href="https://cfpaypal.dwbooster.com/faq#q734">https://cfpaypal.dwbooster.com/faq#q734</a></em>
        </td>        
        </tr>
        
        <tr valign="top">
        <th scope="row"><?php _e('Taxes (percent)','cp-contact-form-with-paypal'); ?></th>
        <td><input type="text" name="request_taxes" size="5" value="<?php echo esc_attr(cp_contactformpp_get_option('request_taxes','0')); ?>" /></td>
        </tr>                 
        
        <tr valign="top">
        <th scope="row"><?php _e('Paypal language','cp-contact-form-with-paypal'); ?></th>
        <td><input required type="text" name="paypal_language" value="<?php echo esc_attr(cp_contactformpp_get_option('paypal_language',CP_CONTACTFORMPP_DEFAULT_PAYPAL_LANGUAGE)); ?>" /></td>
        </tr>         
        
        <tr valign="top">        
        <th scope="row"><?php _e('Payment frequency','cp-contact-form-with-paypal'); ?></th>
        <td><select name="paypal_recurrent" id="paypal_recurrent" onchange="cfwpp_update_recurrent();">
             <option value="0" <?php if (cp_contactformpp_get_option('paypal_recurrent',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '0' || 
                                         cp_contactformpp_get_option('paypal_recurrent',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == ''
                                        ) echo 'selected'; ?>><?php _e('One time payment (default option, user is billed only once)','cp-contact-form-with-paypal'); ?></option>
             <option value="0.4" <?php if (cp_contactformpp_get_option('paypal_recurrent',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '0.4') echo 'selected'; ?>><?php _e('Bill the user every 1 week','cp-contact-form-with-paypal'); ?> </option>
             <option value="1" <?php if (cp_contactformpp_get_option('paypal_recurrent',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '1') echo 'selected'; ?>><?php _e('Bill the user every 1 month','cp-contact-form-with-paypal'); ?></option> 
             <option value="2" <?php if (cp_contactformpp_get_option('paypal_recurrent',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '2') echo 'selected'; ?>><?php _e('Bill the user every 2 months','cp-contact-form-with-paypal'); ?></option> 
             <option value="3" <?php if (cp_contactformpp_get_option('paypal_recurrent',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '3') echo 'selected'; ?>><?php _e('Bill the user every 3 months','cp-contact-form-with-paypal'); ?></option> 
             <option value="6" <?php if (cp_contactformpp_get_option('paypal_recurrent',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '6') echo 'selected'; ?>><?php _e('Bill the user every 6 months','cp-contact-form-with-paypal'); ?></option> 
             <option value="12" <?php if (cp_contactformpp_get_option('paypal_recurrent',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '12') echo 'selected'; ?>><?php _e('Bill the user every 12 months','cp-contact-form-with-paypal'); ?></option>              
            </select>
            <div id="cfwpp_setupfee" style="width:350px;margin-top:5px;padding:5px;background-color:#ddddff;display:none;border:1px dotted black;">
             <?php _e('First period price (ex: include setup fee here if any or 0 for a free initial period)','cp-contact-form-with-paypal'); ?>:<br />
             <input type="text" name="paypal_recurrent_setup" size="10" value="<?php echo esc_attr(cp_contactformpp_get_option('paypal_recurrent_setup','')); ?>" />
             <br />
             <?php _e('First period lenght (if any)','cp-contact-form-with-paypal'); ?>:<br />
             <select name="paypal_recurrent_fp" id="paypal_recurrent_fp">             
             <option value="0" <?php if (cp_contactformpp_get_option('paypal_recurrent_fp',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '' || cp_contactformpp_get_option('paypal_recurrent_fp',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '0') echo 'selected'; ?>>no first period</option>
             <option value="0.4" <?php if (cp_contactformpp_get_option('paypal_recurrent_fp',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '0.4') echo 'selected'; ?>>1 <?php _e('week','cp-contact-form-with-paypal'); ?></option>
             <option value="1" <?php if (cp_contactformpp_get_option('paypal_recurrent_fp',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '1') echo 'selected'; ?>>1 <?php _e('month','cp-contact-form-with-paypal'); ?></option> 
             <option value="2" <?php if (cp_contactformpp_get_option('paypal_recurrent_fp',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '2') echo 'selected'; ?>>2 <?php _e('months','cp-contact-form-with-paypal'); ?></option> 
             <option value="3" <?php if (cp_contactformpp_get_option('paypal_recurrent_fp',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '3') echo 'selected'; ?>>3 <?php _e('months','cp-contact-form-with-paypal'); ?></option> 
             <option value="6" <?php if (cp_contactformpp_get_option('paypal_recurrent_fp',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '6') echo 'selected'; ?>>6 <?php _e('months','cp-contact-form-with-paypal'); ?></option> 
             <option value="12" <?php if (cp_contactformpp_get_option('paypal_recurrent_fp',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '12') echo 'selected'; ?>>12 <?php _e('months','cp-contact-form-with-paypal'); ?></option> 
            </select>             
            <br />             
            <hr />
             <?php $selnum = esc_attr(cp_contactformpp_get_option('paypal_recurrent_times','0')); ?>
             <?php _e('Number of times that subscription payments recur (keep "Unlimited" in most cases)','cp-contact-form-with-paypal'); ?>:<br />
             <select name="paypal_recurrent_times">
                <option value="0" <?php if ($selnum == '0') echo 'selected'; ?>><?php _e('Unlimited','cp-contact-form-with-paypal'); ?></option>
                <?php for ($kt=2; $kt<=52; $kt++) { ?>
                  <option value="<?php echo $kt; ?>" <?php if ($selnum == $kt."") echo 'selected'; ?>><?php echo $kt; ?> <?php _e('times','cp-contact-form-with-paypal'); ?></option>
                <?php } ?>
             </select>
            </div> 
            <script type="text/javascript">
              function cfwpp_update_recurrent() {
                var f = document.getElementById("paypal_recurrent");
                  if (f.options[f.options.selectedIndex].value != '0')
                      document.getElementById("cfwpp_setupfee").style .display = "";
                  else
                      document.getElementById("cfwpp_setupfee").style .display = "none";    
              } 
              cfwpp_update_recurrent();
            </script>
        </td>        
        </tr>            
        
        <tr valign="top">
        <th scope="row"><?php _e('Request address at PayPal','cp-contact-form-with-paypal'); ?></th>
        <td><select name="request_address">
             <option value="0" <?php if (cp_contactformpp_get_option('request_address','0') != '1') echo 'selected'; ?>><?php _e('No','cp-contact-form-with-paypal'); ?></option> 
             <option value="1" <?php if (cp_contactformpp_get_option('request_address','0') == '1') echo 'selected'; ?>><?php _e('Yes','cp-contact-form-with-paypal'); ?></option> 
            </select>
        </td>
        </tr>    
        
        <tr valign="top">        
        <th scope="row"><?php _e('Enable donation layout?','cp-contact-form-with-paypal'); ?></th>
        <td><select name="donationlayout">
             <option value="0" <?php if (cp_contactformpp_get_option('donationlayout','') != '1') echo 'selected'; ?>><?php _e('No','cp-contact-form-with-paypal'); ?></option> 
             <option value="1" <?php if (cp_contactformpp_get_option('donationlayout','') == '1') echo 'selected'; ?>><?php _e('Yes','cp-contact-form-with-paypal'); ?></option> 
            </select>            
        </tr>                       
        
        <tr valign="top">
        <th scope="row" colspan="2" style="padding:3px;background-color:#cccccc;text-align:center;"><?php _e('The following set of fields are only partially available in this version','cp-contact-form-with-paypal'); ?>:</th>
        </tr>
        
        <tr valign="top">        
        <th scope="row"><?php _e('Enable Paypal Payments?','cp-contact-form-with-paypal'); ?></th>
        <td>
          <div id="cpcfppmoreinlink1"><input type="checkbox" readonly disabled="disabled" name="enable_paypal2" size="40" value="1" checked /> &nbsp; [<a href="javascript:displaymorein(1);">+ <?php _e('more information','cp-contact-form-with-paypal'); ?></a>]</div>
          <div id="cpcfppmorein1" style="display:none;border:1px solid black;background-color:#ffffcc;padding:10px;">
           <p>Note: The <a href="https://cfpaypal.dwbooster.com/download">commercial versions</a> also work without PayPal to convert the form into a general purpose form with or without payment involved. The <a href="https://cfpaypal.dwbooster.com/download">Platinum version</a> supports integration with other payments gateways like Stripe, PayPal Pro, iDeal and RedSys.</p>
           [<a href="javascript:displaylessin(1);">- less information</a>]
          </div>
        </td>
        </tr>          
        
        <tr valign="top">        
        <th scope="row"><?php _e('Automatically identify prices on dropdown and checkboxes?','cp-contact-form-with-paypal'); ?></th>
        <td>            
             
            <div id="cpcfppmoreinlink2">N/A &nbsp; [<a href="javascript:displaymorein(2);">+ more information</a>]</div>
            <div id="cpcfppmorein2" style="display:none;border:1px solid black;background-color:#ffffcc;padding:10px;">       
             <p>Note: This setting applies only for the <a href="https://cfpaypal.dwbooster.com/download">commercial versions</a> who support multiple field types.</p>
             <p>If marked, any price in the selected checkboxes, radiobuttons and dropdown fields will be added to the above request cost. 
                Prices will be identified if are entered in the format $NNNN.NN, example: $30 , $24.99 and also $1,499.99. Also works with the GBP "&pound;" and EUR "&euro;" signs.</p>
             <p>For example, you can create a drop-down/select field with these options:
             <br /><br />
             &nbsp; - 1 hour tutoring for $30<br />
             &nbsp; - 2 hours tutoring for $60<br />
             &nbsp; - 3 hours tutoring for $90<br />
             &nbsp; - 4 hours tutoring for $120
             </p>
             <p>... and put the basic request cost to 0. After submission the price sent to PayPal will be the total sum of the selected options.</p>
             [<a href="javascript:displaylessin(2);">- <?php _e('less information','cp-contact-form-with-paypal'); ?></a>]
            </div>
        </td>
        </tr>        
        
        <tr valign="top">
        <th scope="row"><?php _e('Use a specific field from the form for the payment amount','cp-contact-form-with-paypal'); ?></th>
        <td>
            <div id="cpcfppmoreinlink3"><select id="paypal_price_field" name="paypal_price_field" def="<?php echo esc_attr(cp_contactformpp_get_option('paypal_price_field', '')); ?>"></select> &nbsp; [<a href="javascript:displaymorein(3);">+ <?php _e('more information','cp-contact-form-with-paypal'); ?></a>]</div>
            <div id="cpcfppmorein3" style="display:none;border:1px solid black;background-color:#ffffcc;padding:10px;">
             <p>If selected, any price in the selected field will be added to the above request cost. Use this field for example for having an open donation amount.</p>
             <p>This feature is more useful in the <a href="https://cfpaypal.dwbooster.com/download">commercial versions</a> since support adding new custom fields.</p>
             [<a href="javascript:displaylessin(3);">- <?php _e('less information','cp-contact-form-with-paypal'); ?></a>]
            </div>
        </td>
        </tr>            
        
        <tr valign="top">        
        <th scope="row"><?php _e('When should the notification-confirmation emails be sent?','cp-contact-form-with-paypal'); ?></th>
        <td>
            <div id="cpcfppmoreinlink4"><select name="paypal_notiemails">
             <option value="0" <?php if (cp_contactformpp_get_option('paypal_notiemails','0') != '0') echo 'selected'; ?>><?php _e('When paid: AFTER receiving the PayPal payment','cp-contact-form-with-paypal'); ?></option>             
            </select> &nbsp; [<a href="javascript:displaymorein(4);">+ <?php _e('more information','cp-contact-form-with-paypal'); ?></a>]</div>
            <div id="cpcfppmorein4" style="display:none;border:1px solid black;background-color:#ffffcc;padding:10px;">             
             <p>The <a href="https://cfpaypal.dwbooster.com/download">commercial versions</a> include these options:</p>
             <p>
               &nbsp; &nbsp; - <?php _e('When paid: AFTER receiving the PayPal payment.','cp-contact-form-with-paypal'); ?><br />
               &nbsp; &nbsp; - <?php _e('Always: BEFORE receiving the PayPal payment.','cp-contact-form-with-paypal'); ?>
             </p>
             [<a href="javascript:displaylessin(4);">- <?php _e('less information','cp-contact-form-with-paypal'); ?></a>]
            </div>
        </td>
        </tr>          
        
                                    
        
        <tr valign="top">        
        <th scope="row"><?php _e('A $0 amount to pay means','cp-contact-form-with-paypal'); ?>:</th>
        <td>
            <div id="cpcfppmoreinlink6"><select name="paypal_zero_payment">
             <option value="0" <?php if (cp_contactformpp_get_option('paypal_zero_payment',CP_CONTACTFORMPP_DEFAULT_PAYPAL_ZERO_PAYMENT) != '1') echo 'selected'; ?>><?php _e('Let the user enter any amount at PayPal (ex: for a donation)','cp-contact-form-with-paypal'); ?></option>              
            </select> &nbsp; [<a href="javascript:displaymorein(6);">+ more information</a>]</div>
            <div id="cpcfppmorein6" style="display:none;border:1px solid black;background-color:#ffffcc;padding:10px;">             
             <p>The <a href="https://cfpaypal.dwbooster.com/download">commercial versions</a> include these options:</p>
             <p>
               &nbsp; &nbsp; - <?php _e('Let the user enter any amount at PayPal (ex: for a donation).','cp-contact-form-with-paypal'); ?><br />
               &nbsp; &nbsp; - <?php _e('Don\'t require any payment. Form is submitted skiping the PayPal page.','cp-contact-form-with-paypal'); ?>
             </p>
             [<a href="javascript:displaylessin(6);">- <?php _e('less information','cp-contact-form-with-paypal'); ?></a>]
            </div>
        </td>
        </tr>                         
               
        
        <tr valign="top">
        <th scope="row"><?php _e('Discount Codes','cp-contact-form-with-paypal'); ?></th>
        <td> 
           <em><?php _e('N/A - This feature is available in the','cp-contact-form-with-paypal'); ?> <a href="https://cfpaypal.dwbooster.com/download"><?php _e('commercial versions','cp-contact-form-with-paypal'); ?></a>.</em>
        </td>
        </tr>  
                   
     </table>  

  </div>    
 </div>    
 

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e('Form Builder','cp-contact-form-with-paypal'); ?></span></h3>
  <div class="inside">   

  
     <div id="cpwppdialog" title="Contact form" style="display:none">
       <p><?php _e('Note: This free version includes a classic predefined contact form. The form builder for a total form customization
         is available in the ','cp-contact-form-with-paypal'); ?><a href="https://cfpaypal.dwbooster.com/download"><?php _e('commercial versions','cp-contact-form-with-paypal'); ?></a>.</p>
     </div>

     <em><?php _e('Note: This free version includes a basic form builder with the most used field types (text fields, textareas, emails and acceptance checkbox). A more advanced form builder is available in the','cp-contact-form-with-paypal'); ?> <a href="https://cfpaypal.dwbooster.com/download"><?php _e('commercial versions','cp-contact-form-with-paypal'); ?></a>.</em>
        <br /><br />
     <input type="hidden" name="form_structure_control" id="form_structure_control" value="&quot;&quot;&quot;&quot;&quot;&quot;" />   
     <input type="hidden" name="form_structure" id="form_structure" size="180" value="<?php echo str_replace('"','&quot;',str_replace("\r","",str_replace("\n","",esc_attr(cp_contactformpp_cleanJSON(cp_contactformpp_get_option('form_structure', CP_CONTACTFORMPP_DEFAULT_form_structure)))))); ?>" />
     
     <script>
         $contactFormPPQuery = jQuery;
         jQuery(window).on('load', function(){
             $contactFormPPQuery(document).ready(function() {
             
                  
                var f = $contactFormPPQuery("#fbuilder").fbuilderCFWPP();
                f.fBuild.loadData("form_structure");
                
                $contactFormPPQuery("#saveForm").click(function() {       
                    f.fBuild.saveData("form_structure");
                });  
                     
                $contactFormPPQuery(".itemForm").click(function() {
     	           f.fBuild.addItem($contactFormPPQuery(this).attr("id"));
     	       });  
              
               $contactFormPPQuery( ".itemForm" ).draggable({revert1: "invalid",helper: "clone",cursor: "move"});
     	       $contactFormPPQuery( "#fbuilder" ).droppable({
     	           accept: ".button",
     	           drop: function( event, ui ) {
     	               f.fBuild.addItem(ui.draggable.attr("id"));				
     	           }
     	       });     		    
             });
         });
                    
        
        var randcaptcha = 1;
        function generateCaptcha()
        {            
           var d=new Date();
           var f = document.cpformconf;    
           var qs = "&width="+f.cv_width.value;
		   var cv_background = f.cv_background.value;
		   cv_background = cv_background.replace('#','');
		   var cv_border = f.cv_border.value;
		   cv_border = cv_border.replace('#','');
           qs += "&height="+f.cv_height.value;
           qs += "&letter_count="+f.cv_chars.value;
           qs += "&min_size="+f.cv_min_font_size.value;
           qs += "&max_size="+f.cv_max_font_size.value;
           qs += "&noise="+f.cv_noise.value;
           qs += "&noiselength="+f.cv_noise_length.value;
           qs += "&bcolor="+cv_background;
           qs += "&border="+cv_border;
           qs += "&font="+f.cv_font.options[f.cv_font.selectedIndex].value;
           qs += "&rand="+(randcaptcha++);
           
           document.getElementById("captchaimg").src= "<?php echo esc_js(cp_contactformpp_get_site_url(true)); ?>/?cp_contactformpp=captcha"+qs;
        }

        function cfpp_update_pp_payment_selection() 
        {
           var f = document.cpformconf;
           var ppoption = f.enable_paypal.options[f.enable_paypal.selectedIndex].value;   
           var pprefunds = f.pprefunds.options[f.pprefunds.selectedIndex].value;             
           
           document.getElementById("cfpp_paypal_options_express").style.display = "none";
           
           if (ppoption == '100' || ppoption == '101' || pprefunds == 'true')
               document.getElementById("cfpp_paypal_options_express").style.display = "";
        }   
        
        cfpp_update_pp_payment_selection(); 

     </script>
     
     <div style="background:#fafafa;min-width:780px;" class="form-builder">
     
         <div class="column width50">
             <div id="tabs">
     			<ul>
     				<li><a href="#tabs-1"><?php _e('Add a Field','cp-contact-form-with-paypal'); ?></a></li>
     				<li><a href="#tabs-2"><?php _e('Field Settings','cp-contact-form-with-paypal'); ?></a></li>
     				<li><a href="#tabs-3"><?php _e('Form Settings','cp-contact-form-with-paypal'); ?></a></li>
     			</ul>
     			<div id="tabs-1">
     			    
     			</div>
     			<div id="tabs-2"></div>
     			<div id="tabs-3"></div>
     		</div>	
         </div>
         <div class="columnr width50 padding10" id="fbuilder">
             <div id="formheader"></div>
             <div id="fieldlist"></div>
             <div class="button" id="saveForm"><?php _e('Save Form','cp-contact-form-with-paypal'); ?></div>
         </div>
         <div class="clearer"></div>
         
     </div>        
     
<div style="border:1px dotted black;background-color:#eeeeaa;padding-left:15px;padding-right:15px;padding-top:5px;margin-top:10px;width:95%;font-size:12px;color:#000000;"> 
   <p><?php _e('This version supports the most frequently used field types: "Single Line Text", "Email", "Text-area" and "Acceptance Checkbox".','cp-contact-form-with-paypal'); ?></p>
   <p><button type="button" onclick="window.open('https://cfpaypal.dwbooster.com/download?src=activatebtn');" style="cursor:pointer;height:35px;color:#20A020;font-weight:bold;"><?php _e('Activate the FULL form builder','cp-contact-form-with-paypal'); ?></button>
   <p><?php _e('The','cp-contact-form-with-paypal'); ?> <a href="https://cfpaypal.dwbooster.com/download"><?php _e('full set of fields','cp-contact-form-with-paypal'); ?></a> <?php _e('also supports','cp-contact-form-with-paypal'); ?>:
   <ul>
    <li> - <?php _e('Conditional Logic: Hide/show fields based in previous selections','cp-contact-form-with-paypal'); ?>.</li>
    <li> - <?php _e('File uploads','cp-contact-form-with-paypal'); ?></li>
    <li> - <?php _e('Multi-page forms','cp-contact-form-with-paypal'); ?></li>
    <li> - <?php _e('More fields and validations','cp-contact-form-with-paypal'); ?></li>
    <li> - <?php _e('Publish it as a widget in the sidebar','cp-contact-form-with-paypal'); ?></li>
    <li> - <?php _e('Additional payment options, price configuration, addons, etc','cp-contact-form-with-paypal'); ?>....</li>
   </ul>
   <p><?php _e('For a similar plugin for bookings appointments check the','cp-contact-form-with-paypal'); ?> <a href="https://wordpress.org/plugins/appointment-hour-booking/">Appointment/Service Booking Calendar</a>.</p>
   </p>
   
  </div>
  
  </div>    
 </div>    
   
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e('Submit Button','cp-contact-form-with-paypal'); ?></span></h3>
  <div class="inside">   
     <table class="form-table">    
        <tr valign="top">
        <th scope="row"><?php _e('Submit button label (text)','cp-contact-form-with-paypal'); ?>:</th>
        <td><input type="text" name="vs_text_submitbtn" size="40" value="<?php $label = cp_contactformpp_get_option('vs_text_submitbtn', 'Submit'); echo esc_attr($label==''?'Submit':$label); ?>" /></td>
        </tr>    
        <tr valign="top">
        <td colspan="2"> - <?php _e('The','cp-contact-form-with-paypal'); ?>  <em>class="pbSubmit"</em> <?php _e('can be used to modify the button styles.','cp-contact-form-with-paypal'); ?> <br />
        - <?php _e('The styles can be applied into the','cp-contact-form-with-paypal'); ?> <a href="admin.php?page=cp_contact_form_paypal.php&edit=1&cal=1&item=css" target="_blank"><?php _e('Customization Are','cp-contact-form-with-paypal'); ?>a</a>. <br />       
        - <?php _e('For general CSS styles modifications to the form and samples','cp-contact-form-with-paypal'); ?> <a href="https://cfpaypal.dwbooster.com/faq#q82" target="_blank"><?php _e('check this FAQ','cp-contact-form-with-paypal'); ?></a>.
        </tr>
     </table>
  </div>    
 </div> 
 


 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e('Validation Settings','cp-contact-form-with-paypal'); ?></span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top" style="display:none;">
        <th scope="row"><?php _e('Use Validation?','cp-contact-form-with-paypal'); ?></th>
        <td>
          <?php $option = cp_contactformpp_get_option('vs_use_validation', CP_CONTACTFORMPP_DEFAULT_vs_use_validation); ?>
          <select name="vs_use_validation">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e('Yes','cp-contact-form-with-paypal'); ?></option>
          </select>
        </td>
        </tr>
        <tr valign="top">
        <td scope="row"><?php _e('"is required" tex','cp-contact-form-with-paypal'); ?>t:<br /><input type="text" required name="vs_text_is_required" size="40" value="<?php echo esc_attr(cp_contactformpp_get_option('vs_text_is_required', CP_CONTACTFORMPP_DEFAULT_vs_text_is_required)); ?>" /></td>
        </tr>             
         <tr valign="top">
        <td scope="row"><?php _e('"is email" text','cp-contact-form-with-paypal'); ?>:<br /><input type="text" required name="vs_text_is_email" size="50" value="<?php echo esc_attr(cp_contactformpp_get_option('vs_text_is_email', CP_CONTACTFORMPP_DEFAULT_vs_text_is_email)); ?>" /></td>
        <td scope="row"><?php _e('"is valid captcha" text','cp-contact-form-with-paypal'); ?>:<br /><input type="text" name="cv_text_enter_valid_captcha" size="50" value="<?php echo esc_attr(cp_contactformpp_get_option('cv_text_enter_valid_captcha', CP_CONTACTFORMPP_DEFAULT_cv_text_enter_valid_captcha)); ?>" /></td>
        </tr>

        <tr valign="top">
        <td scope="row"><?php _e('"is valid date (mm/dd/yyyy)" text','cp-contact-form-with-paypal'); ?>:<br /><input type="text" name="vs_text_datemmddyyyy" size="50" value="<?php echo esc_attr(cp_contactformpp_get_option('vs_text_datemmddyyyy', CP_CONTACTFORMPP_DEFAULT_vs_text_datemmddyyyy)); ?>" /></td>
        <td scope="row"><?php _e('"is valid date (dd/mm/yyyy)" text','cp-contact-form-with-paypal'); ?>:<br /><input type="text" name="vs_text_dateddmmyyyy" size="50" value="<?php echo esc_attr(cp_contactformpp_get_option('vs_text_dateddmmyyyy', CP_CONTACTFORMPP_DEFAULT_vs_text_dateddmmyyyy)); ?>" /></td>
        </tr>
        <tr valign="top">
        <td scope="row"><?php _e('"is number" text','cp-contact-form-with-paypal'); ?>:<br /><input type="text" name="vs_text_number" size="50" value="<?php echo esc_attr(cp_contactformpp_get_option('vs_text_number', CP_CONTACTFORMPP_DEFAULT_vs_text_number)); ?>" /></td>
        <td scope="row"><?php _e('"only digits" text','cp-contact-form-with-paypal'); ?>:<br /><input type="text" name="vs_text_digits" size="50" value="<?php echo esc_attr(cp_contactformpp_get_option('vs_text_digits', CP_CONTACTFORMPP_DEFAULT_vs_text_digits)); ?>" /></td>
        </tr>
        <tr valign="top">
        <td scope="row"><?php _e('"under maximum" text','cp-contact-form-with-paypal'); ?>:<br /><input type="text" name="vs_text_max" size="50" value="<?php echo esc_attr(cp_contactformpp_get_option('vs_text_max', CP_CONTACTFORMPP_DEFAULT_vs_text_max)); ?>" /></td>
        <td scope="row"><?php _e('"over minimum" text','cp-contact-form-with-paypal'); ?>:<br /><input type="text" name="vs_text_min" size="50" value="<?php echo esc_attr(cp_contactformpp_get_option('vs_text_min', CP_CONTACTFORMPP_DEFAULT_vs_text_min)); ?>" /></td>
        </tr>             
        
     </table>  
  </div>    
 </div>   
 
 
 
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e('Email Copy to User','cp-contact-form-with-paypal'); ?></span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top">
        <th scope="row"><?php _e('Send confirmation/thank you message to user?','cp-contact-form-with-paypal'); ?></th>
        <td>
          <?php $option = cp_contactformpp_get_option('cu_enable_copy_to_user', CP_CONTACTFORMPP_DEFAULT_cu_enable_copy_to_user); ?>
          <select name="cu_enable_copy_to_user" id="cu_enable_copy_to_user" onchange="cfwpp_update_copyuser_related();">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e('Yes','cp-contact-form-with-paypal'); ?></option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e('No','cp-contact-form-with-paypal'); ?></option>
          </select>
        </td>
        </tr>
        <tr valign="top" class="copyuserrelated">
        <th scope="row"><?php _e('Email field on the form','cp-contact-form-with-paypal'); ?></th>
        <td><select id="cu_user_email_field" name="cu_user_email_field" def="<?php echo esc_attr(cp_contactformpp_get_option('cu_user_email_field', CP_CONTACTFORMPP_DEFAULT_cu_user_email_field)); ?>"></select></td>
        </tr>             
        <tr valign="top" class="copyuserrelated">
        <th scope="row"><?php _e('Email subject','cp-contact-form-with-paypal'); ?></th>
        <td><input type="text" name="cu_subject" size="70" value="<?php echo esc_attr(cp_contactformpp_get_option('cu_subject', CP_CONTACTFORMPP_DEFAULT_cu_subject)); ?>" /></td>
        </tr>
        <tr valign="top" class="copyuserrelated">
        <th scope="row"><?php _e('Email format?','cp-contact-form-with-paypal'); ?></th>
        <td>
          <?php $option = cp_contactformpp_get_option('cu_emailformat', CP_CONTACTFORMPP_DEFAULT_email_format); ?>
          <select name="cu_emailformat">
           <option value="text"<?php if ($option != 'html') echo ' selected'; ?>><?php _e('Plain Text (default','cp-contact-form-with-paypal'); ?>)</option>
           <option value="html"<?php if ($option == 'html') echo ' selected'; ?>>HTML (<?php _e('use html in the textarea below','cp-contact-form-with-paypal'); ?>)</option>
          </select>
        </td>
        </tr>  
        <tr valign="top" class="copyuserrelated">
        <th scope="row"><?php _e('Message','cp-contact-form-with-paypal'); ?></th>
        <td><textarea type="text" name="cu_message" rows="6" cols="80"><?php echo esc_textarea(cp_contactformpp_get_option('cu_message', CP_CONTACTFORMPP_DEFAULT_cu_message)); ?></textarea></td>
        </tr>        
     </table>  
  </div>    
 </div>  
 

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e('Captcha Verification','cp-contact-form-with-paypal'); ?></span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top">
        <th scope="row"><?php _e('Use Captcha Verification?','cp-contact-form-with-paypal'); ?></th>
        <td colspan="5">
          <?php $option = cp_contactformpp_get_option('cv_enable_captcha', CP_CONTACTFORMPP_DEFAULT_cv_enable_captcha); ?>
          <select name="cv_enable_captcha" id="cv_enable_captcha" onchange="cfwpp_update_captcha_related();">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e('Yes','cp-contact-form-with-paypal'); ?></option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e('No','cp-contact-form-with-paypal'); ?></option>
          </select>
        </td>
        </tr>
        
        <tr valign="top" class="captcharelated">
         <th scope="row"><?php _e('Width','cp-contact-form-with-paypal'); ?>:</th>
         <td><input type="number" name="cv_width" size="10" value="<?php echo esc_attr(cp_contactformpp_get_option('cv_width', CP_CONTACTFORMPP_DEFAULT_cv_width)); ?>"  onblur="generateCaptcha();"  /></td>
         <th scope="row"><?php _e('Height','cp-contact-form-with-paypal'); ?>:</th>
         <td><input type="number" name="cv_height" size="10" value="<?php echo esc_attr(cp_contactformpp_get_option('cv_height', CP_CONTACTFORMPP_DEFAULT_cv_height)); ?>" onblur="generateCaptcha();"  /></td>
         <th scope="row"><?php _e('Chars','cp-contact-form-with-paypal'); ?>:</th>
         <td><input type="number" name="cv_chars" size="10" value="<?php echo esc_attr(cp_contactformpp_get_option('cv_chars', CP_CONTACTFORMPP_DEFAULT_cv_chars)); ?>" onblur="generateCaptcha();"  /></td>
        </tr>             

        <tr valign="top" class="captcharelated">
         <th scope="row"><?php _e('Min font size','cp-contact-form-with-paypal'); ?>:</th>
         <td><input type="number" name="cv_min_font_size" size="10" value="<?php echo esc_attr(cp_contactformpp_get_option('cv_min_font_size', CP_CONTACTFORMPP_DEFAULT_cv_min_font_size)); ?>" onblur="generateCaptcha();"  /></td>
         <th scope="row"><?php _e('Max font siz','cp-contact-form-with-paypal'); ?>e:</th>
         <td><input type="number" name="cv_max_font_size" size="10" value="<?php echo esc_attr(cp_contactformpp_get_option('cv_max_font_size', CP_CONTACTFORMPP_DEFAULT_cv_max_font_size)); ?>" onblur="generateCaptcha();"  /></td>        
         <td colspan="2" rowspan="">
           <?php _e('Preview','cp-contact-form-with-paypal'); ?>:<br />
             <br />
            <img src="<?php echo esc_attr(cp_contactformpp_get_site_url(true)); ?>/?cp_contactformpp=captcha"  id="captchaimg" alt="security code" border="0"  />            
         </td> 
        </tr>             
                

        <tr valign="top" class="captcharelated">
         <th scope="row"><?php _e('Noise','cp-contact-form-with-paypal'); ?>:</th>
         <td><input type="number" name="cv_noise" size="10" value="<?php echo esc_attr(cp_contactformpp_get_option('cv_noise', CP_CONTACTFORMPP_DEFAULT_cv_noise)); ?>" onblur="generateCaptcha();" /></td>
         <th scope="row"><?php _e('Noise Length','cp-contact-form-with-paypal'); ?>:</th>
         <td><input type="number" name="cv_noise_length" size="10" value="<?php echo esc_attr(cp_contactformpp_get_option('cv_noise_length', CP_CONTACTFORMPP_DEFAULT_cv_noise_length)); ?>" onblur="generateCaptcha();" /></td>        
        </tr>          
        

        <tr valign="top" class="captcharelated">
         <th scope="row"><?php _e('Background','cp-contact-form-with-paypal'); ?>:</th>
         <td><input type="color" name="cv_background" size="10" value="#<?php echo esc_attr(cp_contactformpp_get_option('cv_background', CP_CONTACTFORMPP_DEFAULT_cv_background)); ?>" onchange="generateCaptcha();" /></td>
         <th scope="row"><?php _e('Border','cp-contact-form-with-paypal'); ?>:</th>
         <td><input type="color" name="cv_border" size="10" value="#<?php echo esc_attr(cp_contactformpp_get_option('cv_border', CP_CONTACTFORMPP_DEFAULT_cv_border)); ?>" onchange="generateCaptcha();" /></td>        
        </tr>    
        
        <tr valign="top" class="captcharelated">
         <th scope="row"><?php _e('Font','cp-contact-form-with-paypal'); ?>:</th>
         <td>
            <select name="cv_font" onchange="generateCaptcha();" >
              <option value="font1"<?php if ("font1" == cp_contactformpp_get_option('cv_font', CP_CONTACTFORMPP_DEFAULT_cv_font)) echo " selected"; ?>>Font 1</option>
              <option value="font2"<?php if ("font2" == cp_contactformpp_get_option('cv_font', CP_CONTACTFORMPP_DEFAULT_cv_font)) echo " selected"; ?>>Font 2</option>
              <option value="font3"<?php if ("font3" == cp_contactformpp_get_option('cv_font', CP_CONTACTFORMPP_DEFAULT_cv_font)) echo " selected"; ?>>Font 3</option>
              <option value="font4"<?php if ("font4" == cp_contactformpp_get_option('cv_font', CP_CONTACTFORMPP_DEFAULT_cv_font)) echo " selected"; ?>>Font 4</option>
            </select>            
         </td>              
        </tr>                          
           
        
     </table>  
  </div>    
 </div>    
 
 
<div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e('Note','cp-contact-form-with-paypal'); ?></span></h3>
  <div class="inside">
   <?php _e('To insert this form in a post/page, use the dedicated icon','cp-contact-form-with-paypal'); ?> 
   <?php print '<a href="javascript:cp_contactformpp_insertForm();" title="'.__('Insert','cp-contact-form-with-paypal').' CP Contact Form with PayPal"><img hspace="5" src="'.plugins_url('/images/cp_form.gif', __FILE__).'" alt="'.__('Insert','cp-contact-form-with-paypal').' CP Contact Form with PayPal" /></a>';     ?>
   <?php _e('which has been added to your Upload/Insert Menu, just below the title of your Post/Page','cp-contact-form-with-paypal'); ?>.
   <br /><br />
  </div>
</div>   
  
</div> 


<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes','cp-contact-form-with-paypal'); ?>"  /></p>


[<a href="https://wordpress.org/support/plugin/cp-contact-form-with-paypal#new-post" target="_blank"><?php _e('Request Custom Modifications','cp-contact-form-with-paypal'); ?></a>] | [<a href="https://cfpaypal.dwbooster.com/" target="_blank"><?php _e('Help','cp-contact-form-with-paypal'); ?></a>]
</form>
</div>
<script type="text/javascript">

        
        function cfwpp_update_captcha_related() 
        {
           var f = document.cpformconf;
           var ppoption = f.cv_enable_captcha.options[f.cv_enable_captcha.selectedIndex].value;     
         
           if (ppoption == 'true')
               $contactFormPPQuery(".captcharelated").show();
           else
               $contactFormPPQuery(".captcharelated").hide();
        }   
        
        cfwpp_update_captcha_related();         
               
        function cfwpp_update_copyuser_related() 
        {
           var f = document.cpformconf;
           var ppoption = f.cu_enable_copy_to_user.options[f.cu_enable_copy_to_user.selectedIndex].value;     
         
           if (ppoption == 'true')
               $contactFormPPQuery(".copyuserrelated").show();
           else
               $contactFormPPQuery(".copyuserrelated").hide();
        }   
        
        cfwpp_update_copyuser_related();         
        
        generateCaptcha();        
</script>












