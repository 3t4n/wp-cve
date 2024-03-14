<?php

if ( !defined('CP_CONTACTFORMPP_AUTH_INCLUDE') ) { echo 'Direct access not allowed.';  exit; }

if ( !is_admin() ) 
{
    echo 'Direct access not allowed.';
    exit;
}

if (!empty($_REQUEST['r']))
    $verify_nonce = wp_verify_nonce( $_REQUEST['r'], 'cfwpp_update_actions');
else
    $verify_nonce = false;

global $wpdb;
$message = "";
if (isset($_GET['a']) && $_GET['a'] == '1' && $verify_nonce)
{
    define('CP_CONTACTFORMPP_DEFAULT_fp_from_email', cp_contactformpp_get_default_from_email()  );
    define('CP_CONTACTFORMPP_DEFAULT_fp_destination_emails', get_the_author_meta('user_email', get_current_user_id()) );
    
    // temporal lines to guarantee migration
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'paypal_zero_payment'," varchar(10) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'fp_emailformat'," varchar(10) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'cu_emailformat'," varchar(10) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'paypal_notiemails'," varchar(20) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'paypal_mode'," varchar(20) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'paypal_recurrent'," varchar(20) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'paypal_identify_prices'," varchar(20) NOT NULL default ''");       
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'cp_emailformat'," varchar(10) NOT NULL default ''");       
    
    // insert line
    $wpdb->insert( $wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE, array( 
                                      'form_name' => stripcslashes(sanitize_text_field($_GET["name"])),

                                      'form_structure' => CP_CONTACTFORMPP_DEFAULT_form_structure,

                                      'fp_from_email' => cp_contactformpp_get_option('fp_from_email', CP_CONTACTFORMPP_DEFAULT_fp_from_email),
                                      'fp_destination_emails' => cp_contactformpp_get_option('fp_destination_emails', CP_CONTACTFORMPP_DEFAULT_fp_destination_emails),
                                      'fp_subject' => cp_contactformpp_get_option('fp_subject', CP_CONTACTFORMPP_DEFAULT_fp_subject),
                                      'fp_inc_additional_info' => cp_contactformpp_get_option('fp_inc_additional_info', CP_CONTACTFORMPP_DEFAULT_fp_inc_additional_info),
                                      'fp_return_page' => cp_contactformpp_get_option('fp_return_page', CP_CONTACTFORMPP_DEFAULT_fp_return_page),
                                      'fp_message' => cp_contactformpp_get_option('fp_message', CP_CONTACTFORMPP_DEFAULT_fp_message),
                                      'fp_emailformat' => cp_contactformpp_get_option('fp_emailformat', CP_CONTACTFORMPP_DEFAULT_email_format),

                                      'cu_enable_copy_to_user' => cp_contactformpp_get_option('cu_enable_copy_to_user', CP_CONTACTFORMPP_DEFAULT_cu_enable_copy_to_user),
                                      'cu_user_email_field' => cp_contactformpp_get_option('cu_user_email_field', CP_CONTACTFORMPP_DEFAULT_cu_user_email_field),
                                      'cu_subject' => cp_contactformpp_get_option('cu_subject', CP_CONTACTFORMPP_DEFAULT_cu_subject),
                                      'cu_message' => cp_contactformpp_get_option('cu_message', CP_CONTACTFORMPP_DEFAULT_cu_message),
                                      'cp_emailformat' => cp_contactformpp_get_option('cp_emailformat', CP_CONTACTFORMPP_DEFAULT_email_format),

                                      'vs_use_validation' => cp_contactformpp_get_option('vs_use_validation', CP_CONTACTFORMPP_DEFAULT_vs_use_validation),
                                      'vs_text_is_required' => cp_contactformpp_get_option('vs_text_is_required', CP_CONTACTFORMPP_DEFAULT_vs_text_is_required),
                                      'vs_text_is_email' => cp_contactformpp_get_option('vs_text_is_email', CP_CONTACTFORMPP_DEFAULT_vs_text_is_email),
                                      'vs_text_datemmddyyyy' => cp_contactformpp_get_option('vs_text_datemmddyyyy', CP_CONTACTFORMPP_DEFAULT_vs_text_datemmddyyyy),
                                      'vs_text_dateddmmyyyy' => cp_contactformpp_get_option('vs_text_dateddmmyyyy', CP_CONTACTFORMPP_DEFAULT_vs_text_dateddmmyyyy),
                                      'vs_text_number' => cp_contactformpp_get_option('vs_text_number', CP_CONTACTFORMPP_DEFAULT_vs_text_number),
                                      'vs_text_digits' => cp_contactformpp_get_option('vs_text_digits', CP_CONTACTFORMPP_DEFAULT_vs_text_digits),
                                      'vs_text_max' => cp_contactformpp_get_option('vs_text_max', CP_CONTACTFORMPP_DEFAULT_vs_text_max),
                                      'vs_text_min' => cp_contactformpp_get_option('vs_text_min', CP_CONTACTFORMPP_DEFAULT_vs_text_min),
                                      
                                      'enable_paypal' => cp_contactformpp_get_option('enable_paypal', CP_CONTACTFORMPP_DEFAULT_ENABLE_PAYPAL),
                                      'paypal_notiemails' => cp_contactformpp_get_option('paypal_notiemails', '0'),
                                      'paypal_email' => cp_contactformpp_get_option('paypal_email', CP_CONTACTFORMPP_DEFAULT_PAYPAL_EMAIL),
                                      'request_cost' => cp_contactformpp_get_option('request_cost', CP_CONTACTFORMPP_DEFAULT_COST),
                                      'paypal_product_name' => cp_contactformpp_get_option('paypal_product_name', CP_CONTACTFORMPP_DEFAULT_PRODUCT_NAME),
                                      'currency' => cp_contactformpp_get_option('currency', CP_CONTACTFORMPP_DEFAULT_CURRENCY),
                                      'paypal_language' => cp_contactformpp_get_option('paypal_language', CP_CONTACTFORMPP_DEFAULT_PAYPAL_LANGUAGE),                                         

                                      'cv_enable_captcha' => cp_contactformpp_get_option('cv_enable_captcha', CP_CONTACTFORMPP_DEFAULT_cv_enable_captcha),
                                      'cv_width' => cp_contactformpp_get_option('cv_width', CP_CONTACTFORMPP_DEFAULT_cv_width),
                                      'cv_height' => cp_contactformpp_get_option('cv_height', CP_CONTACTFORMPP_DEFAULT_cv_height),
                                      'cv_chars' => cp_contactformpp_get_option('cv_chars', CP_CONTACTFORMPP_DEFAULT_cv_chars),
                                      'cv_font' => cp_contactformpp_get_option('cv_font', CP_CONTACTFORMPP_DEFAULT_cv_font),
                                      'cv_min_font_size' => cp_contactformpp_get_option('cv_min_font_size', CP_CONTACTFORMPP_DEFAULT_cv_min_font_size),
                                      'cv_max_font_size' => cp_contactformpp_get_option('cv_max_font_size', CP_CONTACTFORMPP_DEFAULT_cv_max_font_size),
                                      'cv_noise' => cp_contactformpp_get_option('cv_noise', CP_CONTACTFORMPP_DEFAULT_cv_noise),
                                      'cv_noise_length' => cp_contactformpp_get_option('cv_noise_length', CP_CONTACTFORMPP_DEFAULT_cv_noise_length),
                                      'cv_background' => cp_contactformpp_get_option('cv_background', CP_CONTACTFORMPP_DEFAULT_cv_background),
                                      'cv_border' => cp_contactformpp_get_option('cv_border', CP_CONTACTFORMPP_DEFAULT_cv_border),
                                      'cv_text_enter_valid_captcha' => cp_contactformpp_get_option('cv_text_enter_valid_captcha', CP_CONTACTFORMPP_DEFAULT_cv_text_enter_valid_captcha)
                                     )
                      );   
    
    $message = __('Item added','cp-contact-form-with-paypal');
} 
else if (isset($_GET['u']) && $_GET['u'] != '' && $verify_nonce)
{
    $wpdb->query('UPDATE `'.$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE.'` SET form_name="'.esc_sql(sanitize_text_field($_GET["name"])).'" WHERE id='.intval($_GET['u']));           
    $message = __('Item updated','cp-contact-form-with-paypal');        
}
else if (isset($_GET['d']) && $_GET['d'] != '' && $verify_nonce)
{
    $wpdb->query( $wpdb->prepare('DELETE FROM `'.$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE.'` WHERE id=%d', $_GET['d']) );       
    $message = __('Item deleted','cp-contact-form-with-paypal');
} 
else if (isset($_GET['c']) && $_GET['c'] != '' && $verify_nonce)
{
    $myrows = $wpdb->get_row( "SELECT * FROM ".$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE." WHERE id=".intval($_GET['c']), ARRAY_A);    
    unset($myrows["id"]);
    $myrows["form_name"] = 'Cloned: '.$myrows["form_name"];
    $wpdb->insert( $wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE, $myrows);
    $message = __('Item duplicated/cloned','cp-contact-form-with-paypal');
}
else if (isset($_GET['ac']) && $_GET['ac'] == 'st' && $verify_nonce)
{   
    update_option( 'CP_CFPP_LOAD_SCRIPTS', ($_GET["scr"]=="1"?"0":"1") );   
    if ($_GET["chs"] != '')
    {
        $target_charset = str_replace('`','``',sanitize_text_field($_GET["chs"]));
        $tables = array( $wpdb->prefix.CP_CONTACTFORMPP_POSTS_TABLE_NAME_NO_PREFIX, $wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE );                
        foreach ($tables as $tab)
        {  
            $myrows = $wpdb->get_results( "DESCRIBE {$tab}" );                                                                                 
            foreach ($myrows as $item)
	        {
	            $name = $item->Field;
		        $type = $item->Type;
		        if (preg_match("/^varchar\((\d+)\)$/i", $type, $mat) || !strcasecmp($type, "CHAR") || !strcasecmp($type, "TEXT") || !strcasecmp($type, "MEDIUMTEXT"))
		        {
	                $wpdb->query("ALTER TABLE {$tab} CHANGE {$name} {$name} {$type} COLLATE `{$target_charset}`");	            
	            }
	        }
        }
    }
    $message = __('Troubleshoot settings updated','cp-contact-form-with-paypal');
}

$nonce = wp_create_nonce( 'cfwpp_update_actions' );

if ($message) echo "<div id='setting-error-settings_updated' class='updated settings-error'><p><strong>".esc_html($message)."</strong></p></div>";

?>
<div class="wrap">
<h1>PayPal Form</h1>

<script type="text/javascript">
 function cp_addItem()
 {
    var calname = document.getElementById("cp_itemname").value;
    document.location = 'admin.php?page=cp_contact_form_paypal.php&a=1&r=<?php echo esc_js($nonce); ?>&name='+encodeURIComponent(calname);       
 }
 
 function cp_updateItem(id)
 {
    var calname = document.getElementById("calname_"+id).value;    
    document.location = 'admin.php?page=cp_contact_form_paypal.php&u='+id+'&r=<?php echo esc_js($nonce); ?>&name='+encodeURIComponent(calname);    
 }
 
 function cp_cloneItem(id)
 {
    document.location = 'admin.php?page=cp_contact_form_paypal.php&c='+id+'&r=<?php echo esc_js($nonce); ?>';
 } 
 
 function cp_manageSettings(id)
 {
    document.location = 'admin.php?page=cp_contact_form_paypal.php&cal='+id+'&r=<?php echo esc_js($nonce); ?>';
 }
 
 function cp_publish(id)
 {
     document.location = 'admin.php?page=cp_contact_form_paypal.php&pwizard=1&cal='+id+'&r='+Math.random();
 }  
 
 function cp_viewMessages(id)
 {
    document.location = 'admin.php?page=cp_contact_form_paypal.php&cal='+id+'&list=1&r=<?php echo esc_js($nonce); ?>';
 } 
 
 function cp_deleteItem(id)
 {
    if (confirm('<?php _e('Are you sure that you want to delete this item?','cp-contact-form-with-paypal'); ?>'))
    {        
        document.location = 'admin.php?page=cp_contact_form_paypal.php&d='+id+'&r=<?php echo esc_js($nonce); ?>';
    }
 }
 
 function cp_updateConfig()
 {
    if (confirm('<?php _e('Are you sure that you want to update these settings?','cp-contact-form-with-paypal'); ?>'))
    {        
        var scr = document.getElementById("ccscriptload").value;    
        var chs = document.getElementById("cccharsets").value;    
        document.location = 'admin.php?page=cp_contact_form_paypal.php&ac=st&scr='+scr+'&chs='+chs+'&r=<?php echo esc_js($nonce); ?>';
    }    
 }
 
</script>


<div id="normal-sortables" class="meta-box-sortables">


 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e('Form List','cp-contact-form-with-paypal'); ?> / <?php _e('Items List','cp-contact-form-with-paypal'); ?></span></h3>
  <div class="inside">
  
  
  <table cellspacing="10" cellpadding="6" class="ahb-calendars-list">
   <tr>
    <th align="left">ID</th><th align="left"><?php _e('Form Name','cp-contact-form-with-paypal'); ?></th><th align="left">&nbsp; &nbsp; <?php _e('Options','cp-contact-form-with-paypal'); ?></th><th align="left">Shortcode <?php _e('for Pages and Posts','cp-contact-form-with-paypal'); ?></th>
   </tr> 
<?php  

  $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE );                                                                     
  foreach ($myrows as $item)         
  {
?>
   <tr> 
    <td nowrap><?php echo intval($item->id); ?></td>
    <td nowrap><input type="text" name="calname_<?php echo intval($item->id); ?>" id="calname_<?php echo intval($item->id); ?>" value="<?php echo esc_attr($item->form_name); ?>" /></td>          
    
    <td>
                             <input style="margin-bottom:5px;" class="button"  type="button" name="calupdate_<?php echo intval($item->id); ?>" value="<?php _e('Rename','cp-contact-form-with-paypal'); ?>" onclick="cp_updateItem(<?php echo intval($item->id); ?>);" />
                             <input style="margin-bottom:5px;" class="button-primary button"  type="button" name="calmanage_<?php echo intval($item->id); ?>" value="<?php _e('Settings','cp-contact-form-with-paypal'); ?>" onclick="cp_manageSettings(<?php echo intval($item->id); ?>);" />
                             <input style="margin-bottom:5px;" class="button-primary button"  type="button" name="calmanage_<?php echo intval($item->id); ?>" value="<?php _e('Publish','cp-contact-form-with-paypal'); ?>" onclick="cp_publish(<?php echo intval($item->id); ?>);" />
                             <input style="margin-bottom:5px;" class="button"  type="button" name="calmanagem_<?php echo intval($item->id); ?>" value="<?php _e('Messages','cp-contact-form-with-paypal'); ?>" onclick="cp_viewMessages(<?php echo intval($item->id); ?>);" />                     
                             <input style="margin-bottom:5px;" class="button"  type="button" name="calclone_<?php echo intval($item->id); ?>" value="<?php _e('Clone','cp-contact-form-with-paypal'); ?>" onclick="cp_cloneItem(<?php echo intval($item->id); ?>);" />                             
                             <input style="margin-bottom:5px;" class="button"  type="button" name="caldelete_<?php echo intval($item->id); ?>" value="<?php _e('Delete','cp-contact-form-with-paypal'); ?>" onclick="cp_deleteItem(<?php echo intval($item->id); ?>);" />                             
    </td>
    <td nowrap><nobr>[CP_CONTACT_FORM_PAYPAL id="<?php echo intval($item->id); ?>"]</nobr></td>          
   </tr>
<?php  
   } 
?>   
     
  </table> 
    
  <div class="clearer"></div>  
   
  </div>    
 </div> 


<div class="ahb-section-container">
	<div class="ahb-section">
		<label><?php _e('New Form','cp-contact-form-with-paypal'); ?></label>&nbsp;&nbsp;&nbsp;
		<input type="text" name="cp_itemname" id="cp_itemname" placeholder=" - <?php _e('Form Name','cp-contact-form-with-paypal'); ?> - " class="ahb-new-calendar" />
		<input type="button" class="button-primary" value="<?php _e('Add New','cp-contact-form-with-paypal'); ?>" onclick="cp_addItem();" />
	</div>
</div>

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e('Troubleshoot Area','cp-contact-form-with-paypal'); ?></span></h3>
  <div class="inside"> 
    <p><strong><?php _e('Important','cp-contact-form-with-paypal'); ?>!</strong>: <?php _e('Use this area','cp-contact-form-with-paypal'); ?> <strong><?php _e('only','cp-contact-form-with-paypal'); ?></strong> <?php _e('if you are experiencing conflicts with third party plugins, with the theme scripts or with the character encoding.','cp-contact-form-with-paypal'); ?></p>
    <form name="updatesettings">
      <?php _e('Script load method','cp-contact-form-with-paypal'); ?>:<br />
       <select id="ccscriptload" name="ccscriptload">
        <option value="0" <?php if (get_option('CP_CFPP_LOAD_SCRIPTS',(CP_CONTACTFORMPP_DEFAULT_DEFER_SCRIPTS_LOADING?"1":"0")) == "1") echo 'selected'; ?>><?php _e('Classic (Recommended)','cp-contact-form-with-paypal'); ?></option>
        <option value="1" <?php if (get_option('CP_CFPP_LOAD_SCRIPTS',(CP_CONTACTFORMPP_DEFAULT_DEFER_SCRIPTS_LOADING?"1":"0")) != "1") echo 'selected'; ?>><?php _e('Direct','cp-contact-form-with-paypal'); ?></option>
       </select><br />
       <em>* <?php _e('Change the script load method if the form doesn\'t appear in the public website.','cp-contact-form-with-paypal'); ?></em>
      
      <br /><br />
      <?php _e('Character encoding','cp-contact-form-with-paypal'); ?>:<br />
       <select id="cccharsets" name="cccharsets">
        <option value=""><?php _e('Keep current charset (Recommended)','cp-contact-form-with-paypal'); ?></option>
        <option value="utf8_general_ci">UTF-8 (<?php _e('try this first','cp-contact-form-with-paypal'); ?>)</option>
        <option value="latin1_swedish_ci">latin1_swedish_ci</option>
        <option value="hebrew_general_ci">hebrew_general_ci</option>
       </select><br />
       <em>* <?php _e('Update the charset if you are getting problems displaying special/non-latin characters. After updated you need to edit the special characters again.','cp-contact-form-with-paypal'); ?></em>
       <br />
       <input type="button" onclick="cp_updateConfig();" name="gobtn" value="UPDATE" />
      <br /><br />      
    </form>

  </div>    
 </div> 

 
  <script type="text/javascript">
   function cp_editArea(id)
   {       
          document.location = 'admin.php?page=cp_contact_form_paypal.php&edit=1&cal=1&item='+id+'&r='+Math.random();
   }
  </script>
  <div id="metabox_basic_settings_custom" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e('Customization Area','cp-contact-form-with-paypal'); ?></span></h3>
  <div class="inside"> 
      <p><?php _e('Use this area to add custom CSS styles or custom scripts.','cp-contact-form-with-paypal'); ?></p>
      <input type="button" onclick="cp_editArea('css');" name="gobtn3" value="<?php _e('Add Custom Styles','cp-contact-form-with-paypal'); ?>" />     
  </div>    
 </div> 
 
  
</div> 


[<a href="https://wordpress.org/support/plugin/cp-contact-form-with-paypal#new-post" target="_blank"><?php _e('Request Custom Modifications','cp-contact-form-with-paypal'); ?></a>] | [<a href="https://wordpress.org/support/plugin/cp-contact-form-with-paypal#new-post" target="_blank"><?php _e('Help','cp-contact-form-with-paypal'); ?></a>]
</form>
</div>














