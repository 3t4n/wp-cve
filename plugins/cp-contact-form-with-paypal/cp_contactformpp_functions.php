<?php
 
if ( !defined('CP_CONTACTFORMPP_AUTH_INCLUDE') ) { echo 'Direct access not allowed.';  exit; } 

// functions
//------------------------------------------


function cpcfwpp_plugin_init() {
   load_plugin_textdomain( 'cp-contact-form-with-paypal', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
   $ao_options = get_option('autoptimize_js_exclude',"seal.js, js/jquery/jquery.js");
   if (!strpos($ao_options,'stringify.js'))
      update_option('autoptimize_js_exclude',"jQuery.stringify.js,jquery.validate.js,".$ao_options);
}

function cp_contactformpp_install($networkwide)  {
	global $wpdb;

	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide) {
	                $old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				_cp_contactformpp_install();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	_cp_contactformpp_install();
}


function cp_contactformpp_get_default_from_email() 
{
    $default_from = strtolower(get_the_author_meta('user_email', get_current_user_id()));
    $domain = str_replace('www.','', strtolower($_SERVER["HTTP_HOST"]));                                  
    while (substr_count($domain,".") > 1)
        $domain = substr($domain, strpos($domain, ".")+1);                 
    $pos = strpos($default_from, $domain);
    if (substr_count($domain,".") == 1 && $pos === false)
        return 'admin@'.$domain;
    else    
        return $default_from;
}


function _cp_contactformpp_install() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    
    define('CP_CONTACTFORMPP_DEFAULT_fp_from_email', cp_contactformpp_get_default_from_email() );
    define('CP_CONTACTFORMPP_DEFAULT_fp_destination_emails', get_the_author_meta('user_email', get_current_user_id()) );

    $table_name = $wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE;

    $sql = "CREATE TABLE ".$wpdb->prefix.CP_CONTACTFORMPP_POSTS_TABLE_NAME_NO_PREFIX." (
         id mediumint(9) NOT NULL AUTO_INCREMENT,
         formid INT NOT NULL,
         time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
         ipaddr VARCHAR(250) DEFAULT '' NOT NULL,
         notifyto VARCHAR(250) DEFAULT '' NOT NULL,
         data mediumtext,
         paypal_post mediumtext,
         posted_data mediumtext,
         paid INT DEFAULT 0 NOT NULL,
         UNIQUE KEY id (id)
         ) ".$charset_collate.";";
    $wpdb->query($sql);

    $sql = "CREATE TABLE ".$wpdb->prefix.CP_CONTACTFORMPP_DISCOUNT_CODES_TABLE_NAME_NO_PREFIX." (
         id mediumint(9) NOT NULL AUTO_INCREMENT,
         form_id mediumint(9) NOT NULL DEFAULT 1,
         code VARCHAR(250) DEFAULT '' NOT NULL,
         discount VARCHAR(250) DEFAULT '' NOT NULL,
         expires datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
         availability int(10) unsigned NOT NULL DEFAULT 0,
         used int(10) unsigned NOT NULL DEFAULT 0,
         UNIQUE KEY id (id)
         ) ".$charset_collate.";";
    $wpdb->query($sql);


    $sql = "CREATE TABLE $table_name (
         id mediumint(9) NOT NULL AUTO_INCREMENT,

         form_name VARCHAR(250) DEFAULT '' NOT NULL,

         form_structure mediumtext,

         fp_from_email VARCHAR(250) DEFAULT '' NOT NULL,
         fp_destination_emails text,
         fp_subject VARCHAR(250) DEFAULT '' NOT NULL,
         fp_inc_additional_info VARCHAR(20) DEFAULT '' NOT NULL,
         fp_return_page VARCHAR(250) DEFAULT '' NOT NULL,
         fp_error_page VARCHAR(250) DEFAULT '' NOT NULL,
         fp_message text,
         fp_emailformat VARCHAR(15) DEFAULT '' NOT NULL,

         cu_enable_copy_to_user VARCHAR(10) DEFAULT '' NOT NULL,
         cu_user_email_field VARCHAR(250) DEFAULT '' NOT NULL,
         cu_subject VARCHAR(250) DEFAULT '' NOT NULL,
         cu_message text,
         cp_emailformat VARCHAR(10) DEFAULT '' NOT NULL,

         vs_use_validation VARCHAR(10) DEFAULT '' NOT NULL,
         vs_text_is_required TEXT DEFAULT '' NOT NULL,
         vs_text_is_email TEXT DEFAULT '' NOT NULL,
         vs_text_datemmddyyyy TEXT DEFAULT '' NOT NULL,
         vs_text_dateddmmyyyy TEXT DEFAULT '' NOT NULL,
         vs_text_number TEXT DEFAULT '' NOT NULL,
         vs_text_digits TEXT DEFAULT '' NOT NULL,
         vs_text_max TEXT DEFAULT '' NOT NULL,
         vs_text_min TEXT DEFAULT '' NOT NULL,
         vs_text_submitbtn TEXT DEFAULT '' NOT NULL,

         enable_paypal varchar(10) DEFAULT '' NOT NULL,
         
         paypalexpress_api_username TEXT DEFAULT '' NOT NULL,
         paypalexpress_api_password TEXT DEFAULT '' NOT NULL,
         paypalexpress_api_signature TEXT DEFAULT '' NOT NULL,
         enable_paypal_expresscredit_yes TEXT DEFAULT '' NOT NULL,
         enable_paypal_expresscredit_no TEXT DEFAULT '' NOT NULL,
         
         pprefunds varchar(10) DEFAULT '' NOT NULL,
         
         paypal_notiemails varchar(10) DEFAULT '' NOT NULL,
         paypal_email varchar(255) DEFAULT '' NOT NULL ,         
         request_cost varchar(255) DEFAULT '' NOT NULL ,
         paypal_price_field varchar(255) DEFAULT '' NOT NULL ,
         request_taxes varchar(20) DEFAULT '' NOT NULL ,
         request_address varchar(20) DEFAULT '' NOT NULL ,
         donationlayout varchar(20) DEFAULT '' NOT NULL ,
         paypal_product_name varchar(255) DEFAULT '' NOT NULL,
         currency varchar(10) DEFAULT '' NOT NULL,
         paypal_language varchar(10) DEFAULT '' NOT NULL,
         paypal_mode varchar(20) DEFAULT '' NOT NULL ,
         paypal_recurrent varchar(20) DEFAULT '' NOT NULL ,
         paypal_recurrent_fp varchar(20) DEFAULT '' NOT NULL ,
         paypal_recurrent_times varchar(20) DEFAULT '' NOT NULL ,
         paypal_recurrent_setup varchar(20) DEFAULT '' NOT NULL ,
         paypal_identify_prices varchar(20) DEFAULT '' NOT NULL ,
         paypal_zero_payment varchar(10) DEFAULT '' NOT NULL ,
         
         script_load_method varchar(10) DEFAULT '' NOT NULL ,

         cv_enable_captcha VARCHAR(20) DEFAULT '' NOT NULL,
         cv_width VARCHAR(20) DEFAULT '' NOT NULL,
         cv_height VARCHAR(20) DEFAULT '' NOT NULL,
         cv_chars VARCHAR(20) DEFAULT '' NOT NULL,
         cv_font VARCHAR(20) DEFAULT '' NOT NULL,
         cv_min_font_size VARCHAR(20) DEFAULT '' NOT NULL,
         cv_max_font_size VARCHAR(20) DEFAULT '' NOT NULL,
         cv_noise VARCHAR(20) DEFAULT '' NOT NULL,
         cv_noise_length VARCHAR(20) DEFAULT '' NOT NULL,
         cv_background VARCHAR(20) DEFAULT '' NOT NULL,
         cv_border VARCHAR(20) DEFAULT '' NOT NULL,
         cv_text_enter_valid_captcha VARCHAR(200) DEFAULT '' NOT NULL,

         UNIQUE KEY id (id)
         ) ".$charset_collate.";";
    $wpdb->query($sql);

    $count = $wpdb->get_var(  "SELECT COUNT(id) FROM ".$table_name  );
    if (!$count)
    {
        $wpdb->insert( $table_name, array( 'id' => 1,
                                      'form_name' => 'Form 1',

                                      'form_structure' => cp_contactformpp_get_option('form_structure', CP_CONTACTFORMPP_DEFAULT_form_structure),

                                      'fp_from_email' => cp_contactformpp_get_option('fp_from_email', CP_CONTACTFORMPP_DEFAULT_fp_from_email),
                                      'fp_destination_emails' => cp_contactformpp_get_option('fp_destination_emails', CP_CONTACTFORMPP_DEFAULT_fp_destination_emails),
                                      'fp_subject' => cp_contactformpp_get_option('fp_subject', CP_CONTACTFORMPP_DEFAULT_fp_subject),
                                      'fp_inc_additional_info' => cp_contactformpp_get_option('fp_inc_additional_info', CP_CONTACTFORMPP_DEFAULT_fp_inc_additional_info),
                                      'fp_return_page' => cp_contactformpp_get_option('fp_return_page', CP_CONTACTFORMPP_DEFAULT_fp_return_page),
                                      'fp_error_page' => cp_contactformpp_get_option('fp_error_page', ''),
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
                                      'vs_text_submitbtn' => cp_contactformpp_get_option('vs_text_submitbtn', 'Submit'),
                                      
                                      'script_load_method' => cp_contactformpp_get_option('script_load_method', '0'),

                                      'enable_paypal' => cp_contactformpp_get_option('enable_paypal', CP_CONTACTFORMPP_DEFAULT_ENABLE_PAYPAL),
                                      
                                      'enable_paypal_expresscredit_yes' => cp_contactformpp_get_option('enable_paypal_expresscredit_yes', CP_CONTACTFORMPP_DEFAULT_PAYPAL_EXPRESSCREDIT_YES),
                                      'enable_paypal_expresscredit_no' => cp_contactformpp_get_option('enable_paypal_expresscredit_no', CP_CONTACTFORMPP_DEFAULT_PAYPAL_EXPRESSCREDIT_NO),
                                      'pprefunds' => cp_contactformpp_get_option('pprefunds', 'false'),
                                      
                                      'paypal_notiemails' => cp_contactformpp_get_option('paypal_notiemails', '0'),
                                      'paypal_email' => cp_contactformpp_get_option('paypal_email', CP_CONTACTFORMPP_DEFAULT_PAYPAL_EMAIL),
                                      'request_cost' => cp_contactformpp_get_option('request_cost', CP_CONTACTFORMPP_DEFAULT_COST),
                                      'paypal_price_field' => cp_contactformpp_get_option('paypal_price_field', ''),
                                      'request_taxes' => cp_contactformpp_get_option('request_taxes', '0'),                                      
                                      'request_address' => cp_contactformpp_get_option('request_address', '0'),                                      
                                      'donationlayout' => cp_contactformpp_get_option('donationlayout', ''),
                                      'paypal_product_name' => cp_contactformpp_get_option('paypal_product_name', CP_CONTACTFORMPP_DEFAULT_PRODUCT_NAME),
                                      'currency' => cp_contactformpp_get_option('currency', CP_CONTACTFORMPP_DEFAULT_CURRENCY),
                                      'paypal_language' => cp_contactformpp_get_option('paypal_language', CP_CONTACTFORMPP_DEFAULT_PAYPAL_LANGUAGE),
                                      'paypal_mode' => cp_contactformpp_get_option('paypal_mode', CP_CONTACTFORMPP_DEFAULT_PAYPAL_MODE),
                                      'paypal_recurrent' => cp_contactformpp_get_option('paypal_recurrent', CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT),
                                      'paypal_recurrent_fp' => cp_contactformpp_get_option('paypal_recurrent_fp', CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT),
                                      'paypal_recurrent_times' => cp_contactformpp_get_option('paypal_recurrent_times', '0'),
                                      'paypal_recurrent_setup' => cp_contactformpp_get_option('paypal_recurrent_setup', '0'),
                                      'paypal_identify_prices' => cp_contactformpp_get_option('paypal_identify_prices', CP_CONTACTFORMPP_DEFAULT_PAYPAL_IDENTIFY_PRICES),
                                      'paypal_zero_payment' => cp_contactformpp_get_option('paypal_zero_payment', CP_CONTACTFORMPP_DEFAULT_PAYPAL_ZERO_PAYMENT),

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
    }

}


function cp_contactformpp_gutenberg_block() {
    global $wpdb;
    
    wp_enqueue_script( 'cpcfwpp_gutenberg_editor', plugins_url('/js/block.js', __FILE__));

    wp_enqueue_style('cfwpp-publicstyle', plugins_url('css/stylepublic.css', __FILE__));                       

    wp_deregister_script('cp_contactformpp_validate_script');
    wp_register_script('cp_contactformpp_validate_script', plugins_url('/js/jquery.validate.js', __FILE__));        
    wp_enqueue_script( 'cp_contactformpp_builder_script',
           plugins_url('/js/fbuilder.jquery.js?nc=2', __FILE__),array("jquery","jquery-ui-core","jquery-ui-datepicker","jquery-ui-widget","jquery-ui-position","jquery-ui-tooltip","cp_contactformpp_validate_script"), false, true ); 
           
    $forms = array();
    $rows = $wpdb->get_results("SELECT id,form_name FROM ".$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE." ORDER BY form_name");
    foreach ($rows as $item)
       $forms[] = array (
                        'value' => $item->id,
                        'label' => $item->form_name,
                        );

    wp_localize_script( 'cpcfwpp_gutenberg_editor', 'cpcfwpp_forms', array(
                        'forms' => $forms,
                        'siteUrl' => get_site_url()
                      ) );     
}


function cp_contactformpp_render_form_admin ($atts) {
    $is_gutemberg_editor = defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'];
    if (!$is_gutemberg_editor)
        return cp_contactformpp_filter_content (array('id' => $atts["formId"]));
    else if ($atts["formId"])
    {
        return '<input type="hidden" name="form_structure'.$atts["instanceId"].'" id="form_structure'.$atts["instanceId"].'" value="'.esc_attr(cp_contactformpp_get_option('form_structure' ,CP_CONTACTFORMPP_DEFAULT_form_structure, $atts["formId"])).'" /><fieldset class="ahbgutenberg_editor" disabled><div id="fbuilder"><div id="fbuilder_'.$atts["instanceId"].'"><div id="formheader_'.$atts["instanceId"].'"></div><div id="fieldlist_'.$atts["instanceId"].'"></div></div></div></fieldset>';
    }
    else
        return __('Form inserted. <b>Save and reload this page</b> to render the form.','cp-contact-form-with-paypal');
}
    

function cp_contactformpp_filter_content($atts) {
    global $wpdb;
    extract( shortcode_atts( array(
		'id' => '',
	), $atts ) );
    ob_start();
    cp_contactformpp_get_public_form(intval($id));    
    $buffered_contents = ob_get_contents();
    ob_end_clean();
    return $buffered_contents;
}


function cp_contactformpp_get_public_form($id) {
    global $wpdb;
    global $CP_CPP_global_form_count;
    global $CP_CFPP_global_form_count_number;
    $CP_CFPP_global_form_count_number++;
    $CP_CPP_global_form_count = "_".$CP_CFPP_global_form_count_number;  
    if (!defined('CP_AUTH_INCLUDE')) define('CP_AUTH_INCLUDE', true);

    if ($id != '')
        $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE." WHERE id=".$id );
    else
        $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE );
    if ($id == '') $id = $myrows[0]->id;
    if (cp_contactformpp_defer_enabled())
    {
        wp_enqueue_style('cfwpp-publicstyle', plugins_url('css/stylepublic.css', __FILE__) );
        wp_enqueue_style('cfwpp-calendarstyle', plugins_url('css/cupertino/jquery-ui-1.8.20.custom.css', __FILE__) );        
        
        wp_deregister_script('cp_contactformpp_validate_script');
        wp_register_script('cp_contactformpp_validate_script', plugins_url('/js/jquery.validate.js', __FILE__));
        
        wp_enqueue_script( 'cp_contactformppv2_buikder_script',
        plugins_url('/js/fbuilder.jquery.js?nc=2', __FILE__),array("jquery","jquery-ui-core","jquery-ui-datepicker","jquery-ui-widget","jquery-ui-position","jquery-ui-tooltip","cp_contactformpp_validate_script"), false, true );
        
        
        wp_localize_script('cp_contactformppv2_buikder_script', 'cp_contactformpp_fbuilder_config'.$CP_CPP_global_form_count, array('obj'  	=>
        '{"pub":true,"identifier":"'.$CP_CPP_global_form_count.'","messages": {
        	                	"required": "'.str_replace(array('"'),array('\\"'),__(cp_contactformpp_get_option('vs_text_is_required', CP_CONTACTFORMPP_DEFAULT_vs_text_is_required,$id),'cp-contact-form-with-paypal')).'",
        	                	"email": "'.str_replace(array('"'),array('\\"'),__(cp_contactformpp_get_option('vs_text_is_email', CP_CONTACTFORMPP_DEFAULT_vs_text_is_email,$id),'cp-contact-form-with-paypal')).'",
        	                	"datemmddyyyy": "'.str_replace(array('"'),array('\\"'),__(cp_contactformpp_get_option('vs_text_datemmddyyyy', CP_CONTACTFORMPP_DEFAULT_vs_text_datemmddyyyy,$id),'cp-contact-form-with-paypal')).'",
        	                	"dateddmmyyyy": "'.str_replace(array('"'),array('\\"'),__(cp_contactformpp_get_option('vs_text_dateddmmyyyy', CP_CONTACTFORMPP_DEFAULT_vs_text_dateddmmyyyy,$id),'cp-contact-form-with-paypal')).'",
        	                	"number": "'.str_replace(array('"'),array('\\"'),__(cp_contactformpp_get_option('vs_text_number', CP_CONTACTFORMPP_DEFAULT_vs_text_number,$id),'cp-contact-form-with-paypal')).'",
        	                	"digits": "'.str_replace(array('"'),array('\\"'),__(cp_contactformpp_get_option('vs_text_digits', CP_CONTACTFORMPP_DEFAULT_vs_text_digits,$id),'cp-contact-form-with-paypal')).'",
        	                	"max": "'.str_replace(array('"'),array('\\"'),__(cp_contactformpp_get_option('vs_text_max', CP_CONTACTFORMPP_DEFAULT_vs_text_max,$id),'cp-contact-form-with-paypal')).'",
        	                	"min": "'.str_replace(array('"'),array('\\"'),__(cp_contactformpp_get_option('vs_text_min', CP_CONTACTFORMPP_DEFAULT_vs_text_min,$id),'cp-contact-form-with-paypal')).'"
        	                }}'
        ));
    }  
    else
    {
        wp_enqueue_script( "jquery" );
        wp_enqueue_script( "jquery-ui-core" );
        wp_enqueue_script( "jquery-ui-datepicker" );
    }  
    $codes = $wpdb->get_results( 'SELECT * FROM '.CP_CONTACTFORMPP_DISCOUNT_CODES_TABLE_NAME.' WHERE `form_id`='.$id);

    $button_label = cp_contactformpp_get_option('vs_text_submitbtn', 'Submit',$id);
    $button_label = ($button_label==''?'Submit':$button_label);
    ?>
<script type="text/javascript">
 var cp_contactformpp_ready_to_go = false;
 function doValidate<?php echo esc_html($CP_CPP_global_form_count); ?>(form)
 {
    if (cp_contactformpp_ready_to_go) return false; 
    $dexQuery = jQuery;
    document.cp_contactformpp_pform<?php echo esc_html($CP_CPP_global_form_count); ?>.cp_ref_page.value = document.location;
    <?php if (cp_contactformpp_get_option('cv_enable_captcha', CP_CONTACTFORMPP_DEFAULT_cv_enable_captcha,$id) != 'false') { ?> if ($dexQuery("#hdcaptcha_cp_contact_form_paypal_post<?php echo $CP_CPP_global_form_count; ?>").val() == '')
    {
        setTimeout( "cfwpp_cerror<?php echo esc_html($CP_CPP_global_form_count); ?>()", 100);
        return false;
    }
    var result = $dexQuery.ajax({
        type: "GET",
        url: "<?php echo cp_contactformpp_get_site_url(); ?>?ps=<?php echo esc_html($CP_CPP_global_form_count); ?>"+String.fromCharCode(38)+"hdcaptcha_cp_contact_form_paypal_post="+$dexQuery("#hdcaptcha_cp_contact_form_paypal_post<?php echo $CP_CPP_global_form_count; ?>").val(),
        async: false
    }).responseText;
    if (result.indexOf("captchafailed") != -1)
    {
        $dexQuery("#captchaimg<?php echo esc_html($CP_CPP_global_form_count); ?>").attr('src', $dexQuery("#captchaimg<?php echo esc_html($CP_CPP_global_form_count); ?>").attr('src')+'&'+Math.floor((Math.random() * 99999) + 1));      
        setTimeout( "cfwpp_cerror<?php echo esc_html($CP_CPP_global_form_count); ?>()", 100);
        return false;
    }
    else <?php } ?>
    {
        var cpefb_error = 0;
        $dexQuery("#cp_contactformpp_pform<?php echo esc_html($CP_CPP_global_form_count); ?>").find(".cpefb_error").each(function(index){
            if ($dexQuery(this).css("display")!="none")
                cpefb_error++;    
            });
        if (cpefb_error) return false;    
        cp_contactformpp_ready_to_go = true;
        cfwpp_blink(".pbSubmit");
        document.getElementById("form_structure<?php echo esc_html($CP_CPP_global_form_count); ?>").value = '';    
        return true;
    }
 }
function cfwpp_cerror<?php echo esc_html($CP_CPP_global_form_count); ?>(){$dexQuery = jQuery;$dexQuery("#hdcaptcha_error<?php echo esc_html($CP_CPP_global_form_count); ?>").css('top',$dexQuery("#hdcaptcha_cp_contact_form_paypal_post<?php echo esc_html($CP_CPP_global_form_count); ?>").outerHeight());$dexQuery("#hdcaptcha_error<?php echo $CP_CPP_global_form_count; ?>").css("display","inline");}
function cfwpp_blink(selector){
        try {   
            $dexQuery = jQuery;
            $dexQuery(selector).fadeOut(1000, function(){
                $dexQuery(this).fadeIn(1000, function(){
                    try {
                        if (cp_contactformpp_ready_to_go)
                            cfwpp_blink(this); 
                    } catch (e) {}  
                });
            });         
        } catch (e) {}           
}
</script>    
    <?php
    
    @include dirname( __FILE__ ) . '/cp_contactformpp_public_int.inc.php';    

}


function cp_contactformpp_settingsLink($links) {
    $settings_link = '<a href="admin.php?page=cp_contact_form_paypal.php">'.__('Settings','cp-contact-form-with-paypal').'</a>';
	array_unshift($links, $settings_link);
	return $links;
}


function cp_contactformpp_helpLink($links) {
    $help_link = '<a href="https://wordpress.org/support/plugin/cp-contact-form-with-paypal#new-post">'.__('Help','cp-contact-form-with-paypal').'</a>';
	array_unshift($links, $help_link);
	return $links;
}


function cp_contactformpp_customAdjustmentsLink($links) {
    $customAdjustments_link = '<a href="https://cfpaypal.dwbooster.com/download">'.__('Upgrade','cp-contact-form-with-paypal').'</a>';
	array_unshift($links, $customAdjustments_link);
	return $links;
}


function set_cp_contactformpp_insert_button() {
    print '<a href="javascript:cp_contactformpp_insertForm();" title="'.__('Insert','cp-contact-form-with-paypal').' CP Contact Form with PayPal"><img hspace="5" src="'.plugins_url('/images/cp_form.gif', __FILE__).'" alt="'.__('Insert','cp-contact-form-with-paypal').' CP Contact Form with PayPal" /></a>';
}


function cp_contactformpp_html_post_page() {       
    if (!empty($_GET["pwizard"]) && $_GET["pwizard"] == '1')
        include_once dirname( __FILE__ ) . '/cp_publish_wizard.inc.php';
    else if (isset($_GET["cal"]) && $_GET["cal"] != '')
    {
        $_GET["cal"] = intval($_GET["cal"]);
        if (isset($_GET["edit"]) && $_GET["edit"] == '1')
            @include_once dirname( __FILE__ ) . '/cp_admin_int_edition.inc.php';
        else if (isset($_GET["list"]) && $_GET["list"] == '1')
            @include_once dirname( __FILE__ ) . '/cp_contactformpp_admin_int_message_list.inc.php';
        else
            @include_once dirname( __FILE__ ) . '/cp_contactformpp_admin_int.php';
    }
    else
    {    
        if (isset($_GET["page"]) &&$_GET["page"] == 'cp_contact_form_addons')
        {
            @include_once dirname( __FILE__ ) . '/cp_contactformpp-addons.inc.php';
        } 
        else if (isset($_GET["page"]) &&$_GET["page"] == 'cp_contact_form_paypal_upgrade')
        {
            echo("Redirecting to upgrade page...<script type='text/javascript'>document.location='https://cfpaypal.dwbooster.com/download';</script>");
            exit;
        } 
        else if (isset($_GET["page"]) &&$_GET["page"] == 'cp_contact_form_paypal_demo')
        {
            echo("Redirecting to demo page...<script type='text/javascript'>document.location='https://cfpaypal.dwbooster.com/home#demos';</script>");
            exit;
        } 
        else if (isset($_GET["page"]) &&$_GET["page"] == 'cp_contact_form_paypal_doc')
        {
            echo("Redirecting to demo page...<script type='text/javascript'>document.location='https://cfpaypal.dwbooster.com/documentation?ref=plugin';</script>");
            exit;
        }         
        else
            @include_once dirname( __FILE__ ) . '/cp_contactformpp_admin_int_list.inc.php';
    }
}


function set_cp_contactformpp_insert_adminScripts($hook) {
    if (isset($_GET["page"]) && $_GET["page"] == "cp_contact_form_paypal.php")
    {        
        wp_enqueue_style('cp_contactformpp-adminstyles', plugins_url('css/newadminlayout.css', __FILE__) );        
        wp_enqueue_script( 'cp_contactformppv2_buikder_script', plugins_url('/js/fbuilder.jquery.js?nc=2', __FILE__),array("jquery","jquery-ui-core","jquery-ui-sortable","jquery-ui-dialog","jquery-ui-tabs","jquery-ui-droppable","jquery-ui-button","jquery-ui-datepicker") );
        wp_enqueue_style('jquery-ui-cfwppstyles', plugins_url('/css/style.css', __FILE__));
        wp_enqueue_style('jquery-ui-datepicker', plugins_url('/css/cupertino/jquery-ui-1.8.20.custom.css', __FILE__));      
    }

    if( 'post.php' != $hook  && 'post-new.php' != $hook )
        return;
    wp_enqueue_script( 'cp_contactformpp_script', plugins_url('/cp_contactformpp_scripts.js', __FILE__) );
}


function cp_contactformpp_get_site_url($admin = false)
{
    $blog = get_current_blog_id();
    if( $admin ) 
        $url = get_admin_url( $blog );	
    else 
        $url = get_home_url( $blog );	

    $url = parse_url($url);
    $url = rtrim(@$url["path"],"/");    
    if (is_ssl())
        $url = str_replace('http://', 'https://', $url);    
    return $url;
}

function cp_contactformpp_get_FULL_site_url($admin = false)
{
    $url = cp_contactformpp_get_site_url($admin);
    $pos = strpos($url, "://");    
    if ($pos === false)
        $url = 'http://'.$_SERVER["HTTP_HOST"].$url;
    if (is_ssl())
        $url = str_replace('http://', 'https://', $url);
    return $url;
}

function cp_contactformpp_cleanJSON($str)
{
    $str = str_replace('&qquot;','"',$str);
    $str = str_replace('&qquote;','"',$str);
    $str = str_replace('	',' ',$str);
    $str = str_replace("\n",'\n',$str);
    $str = str_replace("\r",'',$str);
    return $str;
}


function cp_contactformpp_load_discount_codes() {
    global $wpdb;

    if ( ! current_user_can('edit_pages') ) // prevent loading coupons from outside admin area
    {
        echo 'No enough privilegies to load this content.';
        exit;
    }

    if (!defined('CP_CONTACTFORMPP_ID'))
        define ('CP_CONTACTFORMPP_ID',intval($_GET["dex_item"]));

    if (isset($_GET["add"]) && $_GET["add"] == "1")
        $wpdb->insert( CP_CONTACTFORMPP_DISCOUNT_CODES_TABLE_NAME, array('form_id' => CP_CONTACTFORMPP_ID,
                                                                         'code' => esc_sql(sanitize_text_field($_GET["code"])),
                                                                         'discount' => esc_sql(sanitize_text_field($_GET["discount"])),
                                                                         'availability' => esc_sql(sanitize_text_field($_GET["discounttype"])),
                                                                         'expires' => esc_sql(sanitize_text_field($_GET["expires"])),
                                                                         ));

    if (isset($_GET["delete"]) && $_GET["delete"] == "1")
        $wpdb->query( $wpdb->prepare( "DELETE FROM ".CP_CONTACTFORMPP_DISCOUNT_CODES_TABLE_NAME." WHERE id = %d", $_GET["code"] ));

    $codes = $wpdb->get_results( 'SELECT * FROM '.CP_CONTACTFORMPP_DISCOUNT_CODES_TABLE_NAME.' WHERE `form_id`='.intval(CP_CONTACTFORMPP_ID));
    if (count ($codes))
    {
        echo '<table>';
        echo '<tr>';
        echo '  <th style="padding:2px;background-color: #cccccc;font-weight:bold;">Coupon</th>';
        echo '  <th style="padding:2px;background-color: #cccccc;font-weight:bold;">Discount</th>';
        echo '  <th style="padding:2px;background-color: #cccccc;font-weight:bold;">Type</th>';
        echo '  <th style="padding:2px;background-color: #cccccc;font-weight:bold;">Valid until</th>';
        echo '  <th style="padding:2px;background-color: #cccccc;font-weight:bold;">Options</th>';
        echo '</tr>';
        foreach ($codes as $value)
        {
           echo '<tr>';
           echo '<td>'.esc_html($value->code).'</td>';
           echo '<td>'.esc_html($value->discount).'</td>';
           echo '<td>'.($value->availability==1?"Fixed Value":"Percent").'</td>';
           echo '<td>'.esc_html(substr($value->expires,0,10)).'</td>';
           echo '<td>[<a href="javascript:dex_delete_coupon('.esc_js($value->id).')">Delete</a>]</td>';
           echo '</tr>';
        }
        echo '</table>';
    }
    else
        echo 'No discount codes listed for this form yet.';
    exit;
}


/**
* The following function needs to run on init, its purpose is:
* - Check if the post is a PayPal IPN notification
* - Check if the post is a form submission and perform the required captcha validation
* - Generate captcha images
* - Other actions, each one with its proper validation & access verification
* Nonce verifications, access level verification and other validations are made according to its case
*/
function cp_contact_form_paypal_check_init_actions() {

    global $wpdb;

	if ( isset( $_GET['cp_contactformpp_ipncheck'] ) && $_GET['cp_contactformpp_ipncheck'] != '' )
		cp_contactformpp_check_IPN_verification();
 
    if ( isset( $_GET['cp_contactformpp_ipncheckexpress'] ) && $_GET['cp_contactformpp_ipncheckexpress'] != '' )
		cp_contactformpp_check_IPN_verification_express();		   

    if(isset($_GET) && array_key_exists('cp_contact_form_paypal_post',$_GET)) {
        if ($_GET["cp_contact_form_paypal_post"] == 'loadcoupons')
            cp_contactformpp_load_discount_codes();            
    }
    
    if (isset( $_GET['cp_contactformpp'] ) && $_GET['cp_contactformpp'] == 'captcha' )
    {
        @include_once dirname( __FILE__ ) . '/captcha/captcha.php';            
        exit;        
    }        

    if ( isset( $_GET['cp_contactformpp_csv'] ) && current_user_can('edit_pages') && is_admin() )
    {
        cp_contactformpp_export_csv();
        return;
    }

    if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['cp_contactformpp_post_options'] ) && current_user_can('edit_pages') && is_admin() )
    {
        cp_contactformpp_save_options();
        return;
    }
    
    
	if ( 'POST' != $_SERVER['REQUEST_METHOD'] || ! isset( $_POST['cp_contactformpp_pform_process'] ) )
	    if ( 'GET' != $_SERVER['REQUEST_METHOD'] || !isset( $_GET['hdcaptcha_cp_contact_form_paypal_post'] ) )
		    return;

    if (isset($_POST["cp_contactformpp_id"])) define("CP_CONTACTFORMPP_ID",intval($_POST["cp_contactformpp_id"]));

    if (function_exists('session_start')) @session_start();
    if (isset($_GET["ps"])) $sequence = sanitize_key($_GET["ps"]); else if (isset($_POST["cp_pform_psequence"])) $sequence = sanitize_key($_POST["cp_pform_psequence"]);
    if (!isset($_GET['hdcaptcha_cp_contact_form_paypal_post']) || $_GET['hdcaptcha_cp_contact_form_paypal_post'] == '') $_GET['hdcaptcha_cp_contact_form_paypal_post'] = @$_POST['hdcaptcha_cp_contact_form_paypal_post'];
    
    $captcha_tr = '';
    if (!empty($_COOKIE['rand_code'.$sequence])) $captcha_tr = get_transient( "cpeople-captcha-".sanitize_key($_COOKIE['rand_code'.$sequence]));
        
    if (
           (cp_contactformpp_get_option('cv_enable_captcha', CP_CONTACTFORMPP_DEFAULT_cv_enable_captcha) != 'false') &&
           ( (strtolower($_GET['hdcaptcha_cp_contact_form_paypal_post']) != strtolower($_SESSION['rand_code'.$sequence])) ||
             ($_SESSION['rand_code'.$sequence] == '')
           )
           &&
           ( ((strtolower($_GET['hdcaptcha_cp_contact_form_paypal_post'])) != $captcha_tr) ||
             ($captcha_tr == '')
           )
       )
    {
        $_SESSION['rand_code'.$sequence] = '';
        setCookie('rand_code'.$sequence, '', time()+36000,"/");        
        echo 'captchafailed';
        exit;
    }


	// if this isn't the real post (it was the captcha verification) then echo ok and exit
    if ( 'POST' != $_SERVER['REQUEST_METHOD'] || ! isset( $_POST['cp_contactformpp_pform_process'] ) )
	{
	    echo 'ok';
        exit;
	}

    //if (get_magic_quotes_gpc())
        foreach ($_POST as $item => $value)
            if (!is_array($value))
                $_POST[$item] = stripcslashes($value);

	// get base price
    $price = cp_contactformpp_get_option('request_cost', CP_CONTACTFORMPP_DEFAULT_COST);
    $price = trim(str_replace(',','', str_replace(CP_CONTACTFORMPP_DEFAULT_CURRENCY_SYMBOL,'', 
                                     str_replace(CP_CONTACTFORMPP_GBP_CURRENCY_SYMBOL,'', 
                                     str_replace(CP_CONTACTFORMPP_EUR_CURRENCY_SYMBOL_A, '',
                                     str_replace(CP_CONTACTFORMPP_EUR_CURRENCY_SYMBOL_B,'', $price )))) ));     
    $price = floatval($price);
    $added_cost = @$_POST[cp_contactformpp_get_option('paypal_price_field', '').$sequence];
    if (!is_numeric($added_cost))
        $added_cost = 0;
    $price += floatval($added_cost); 
    $taxes = trim(str_replace("%","",cp_contactformpp_get_option('request_taxes', '0')));
    if ($taxes == '') $taxes = 0;

    // get form info
    //---------------------------
    $identify_prices = cp_contactformpp_get_option('paypal_identify_prices',CP_CONTACTFORMPP_DEFAULT_PAYPAL_IDENTIFY_PRICES);    
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    $form_data = json_decode(cp_contactformpp_cleanJSON(cp_contactformpp_get_option('form_structure', CP_CONTACTFORMPP_DEFAULT_form_structure)));
    $fields = array();
    foreach ($form_data[0] as $item)
        $fields[$item->name] = $item->title;                

    // calculate discounts if any
    //---------------------------
    $discount_note = "";
    $coupon = false;
    $codes = array();

    // grab posted data
    //---------------------------
    $buffer = "";
    foreach ($_POST as $item => $value)
        if (isset($fields[str_replace($sequence,'',$item)]))
        {
            $buffer .= $fields[str_replace($sequence,'',$item)] . ": ". (is_array($value)?(implode(", ",$value)):($value)) . "\n\n";
            $params[str_replace($sequence,'',$item)] = $value;
        }

    $buffer_A = $buffer;

    $paypal_product_name = cp_contactformpp_get_option('paypal_product_name', CP_CONTACTFORMPP_DEFAULT_PRODUCT_NAME).$discount_note;
    $params["PayPal Product Name"] = $paypal_product_name; 
    $params["Cost"] = number_format ($price, 2);
    $params["taxamount"] = round($price * ($taxes/100),2);
    $params["Costtax"] = $price + $params["taxamount"];
    
    $current_user = wp_get_current_user();
    $params["user_login"] = $current_user->user_login;
    $params["user_id"] = $current_user->ID;
    $params["user_email"] = $current_user->user_email;
    $params["user_firstname"] = $current_user->user_firstname; 
    $params["user_lastname"] = $current_user->user_lastname; 
    $params["display_name"] = $current_user->display_name;     
    
    cp_contactformpp_add_field_verify(CP_CONTACTFORMPP_POSTS_TABLE_NAME,'posted_data');

    $_SESSION['rand_code'.$sequence] = '';
    setCookie('rand_code'.$sequence, '', time()+36000,"/");

    $saveipaddr = ('true' == cp_contactformpp_get_option('fp_inc_additional_info', CP_CONTACTFORMPP_DEFAULT_fp_inc_additional_info));
    
	/**
	 * Action called before insert the data into database. 
	 * To the function is passed an array with submitted data.
	 */							
    do_action_ref_array( 'cpcfwpp_process_data_before_insert', array(&$params) );
    
    // insert into database
    //---------------------------
    $wpdb->query("ALTER TABLE ".CP_CONTACTFORMPP_POSTS_TABLE_NAME." CHANGE `ipaddr` `ipaddr` VARCHAR(250)");
    $to = cp_contactformpp_get_option('cu_user_email_field', CP_CONTACTFORMPP_DEFAULT_cu_user_email_field).$sequence;
    $rows_affected = $wpdb->insert( CP_CONTACTFORMPP_POSTS_TABLE_NAME, array( 'formid' => CP_CONTACTFORMPP_ID,
                                                                        'time' => current_time('mysql'),
                                                                        'ipaddr' => ($saveipaddr?$_SERVER['REMOTE_ADDR']:'-'),
                                                                        'notifyto' => sanitize_email((@$_POST[$to]?sanitize_email(@$_POST[$to]):'')),
                                                                        'paypal_post' => serialize($params),
                                                                        'posted_data' => serialize($params),
                                                                        'data' => $buffer_A
                                                                         ),
                                                                         array ('%d','%s','%s','%s','%s','%s','%s')  );
    if (!$rows_affected)
    {         
        echo 'Error saving data! Please try again.';
        echo '<br /><br />If the error persists please be sure you are using the latest version and in that case contact support service at https://cfpaypal.dwbooster.com/contact-us?debug=db';
        exit;
    }

    $myrows = $wpdb->get_results( "SELECT MAX(id) as max_id FROM ".CP_CONTACTFORMPP_POSTS_TABLE_NAME );


 	// save data here
    $item_number = $myrows[0]->max_id;
    
    $params[ 'itemnumber' ] = $item_number;
    
  	/**
	 * Action called after inserting the data into database. 
	 * To the function is passed an array with submitted data.
	 */							
    do_action_ref_array( 'cpcfwpp_process_data', array(&$params) );  
    
    $paypal_recurrent = cp_contactformpp_get_option('paypal_recurrent_setup','0');
    
    $error_page = cp_contactformpp_get_option('fp_error_page', '');
    if ($error_page == '')
        $error_page = esc_url_raw($_POST["cp_ref_page"]);

    if (cp_contactformpp_get_option('enable_paypal', '') == '100' || cp_contactformpp_get_option('enable_paypal', '') == '101' )        
    {
         @include_once dirname( __FILE__ ) . '/cp_paypal.express.php';

         try 
         {                
             $ppexp = new DEXBCCF_PayPalEXPC();
             $ppexp->mode = cp_contactformpp_get_option('paypal_mode',CP_CONTACTFORMPP_DEFAULT_PAYPAL_MODE);        
             $ppexp->API_UserName =cp_contactformpp_get_option('paypalexpress_api_username','');        
             $ppexp->API_Password = cp_contactformpp_get_option('paypalexpress_api_password','');
             $ppexp->API_Signature = cp_contactformpp_get_option('paypalexpress_api_signature','');
             $ppexp->currency = strtoupper(cp_contactformpp_get_option('currency', CP_CONTACTFORMPP_DEFAULT_CURRENCY));
             $ppexp->lang = cp_contactformpp_get_option('paypal_language', CP_CONTACTFORMPP_DEFAULT_PAYPAL_LANGUAGE);

             $products = array();
             $products[0]['ItemName'] = $paypal_product_name; //Item Name
		     $products[0]['ItemPrice'] = $price; //Item Price
		     $products[0]['ItemNumber'] = $item_number; //Item Number
		     $products[0]['ItemDesc'] = $paypal_product_name; //Item Desc
		     $products[0]['ItemQty']	= 1; // Item Quantity
		     
		     $charges = array(); //Other important variables like tax, shipping cost
		     $charges['TotalTaxAmount'] = $params["taxamount"];  //Sum of tax for all items in this order. 
		     $charges['HandalingCost'] = 0;  //Handling cost for this order.
		     $charges['InsuranceCost'] = 0;  //shipping insurance cost for this order.
		     $charges['ShippinDiscount'] = 0; //Shipping discount for this order. Specify this as negative number.
		     $charges['ShippinCost'] = 0; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
             
             $okurl = cp_contactformpp_get_FULL_site_url().'/?cp_contactformpp_ipncheckexpress='.$item_number;
             
             $ppexp->SetExpressCheckOut($products, $charges, $okurl, $error_page, (cp_contactformpp_get_option('request_address','0') != '1' ? '1' :'2' ), (cp_contactformpp_get_option('enable_paypal', '') == '101') );
             exit;
             
         } catch (Exception $e) {
             echo "Error: ".esc_html($e->getMessage());
         }         
         
         exit;
    }
            
    if (cp_contactformpp_get_option('paypal_mode',CP_CONTACTFORMPP_DEFAULT_PAYPAL_MODE) == "sandbox")                                                                                  
        $ppurl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    else
        $ppurl = 'https://www.paypal.com/cgi-bin/webscr';
    $recurrent = cp_contactformpp_get_option('paypal_recurrent',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT);    
    $recurrent_fp = cp_contactformpp_get_option('paypal_recurrent_fp',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT);
?>
<html>
<head><title>Redirecting to Paypal...</title></head>
<body>
<form action="<?php echo esc_attr($ppurl); ?>" name="ppform3" method="post">
<input type="hidden" name="business" value="<?php echo esc_attr(trim(cp_contactformpp_get_option('paypal_email', CP_CONTACTFORMPP_DEFAULT_PAYPAL_EMAIL))); ?>" />
<input type="hidden" name="item_name" value="<?php echo esc_attr($paypal_product_name); ?>" />
<input type="hidden" name="item_number" value="<?php echo esc_attr($item_number); ?>" />
<?php if (cp_contactformpp_get_option('paypal_recurrent',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '0' || cp_contactformpp_get_option('paypal_recurrent',CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT) == '') { ?>
<input type="hidden" name="cmd" value="<?php if (cp_contactformpp_get_option('donationlayout','') == '1') echo '_donations'; else echo '_xclick'; ?>" />
<input type="hidden" name="bn" value="NetFactorSL_SI_Custom" />
<input type="hidden" name="amount" value="<?php echo floatval($price); ?>" />
<?php } else { ?>
<?php if ($paypal_recurrent != '' && $recurrent_fp != '' && $recurrent_fp != '0') { ?>
<input type="hidden" name="a1" value="<?php echo esc_attr($paypal_recurrent); ?>">
<input type="hidden" name="p1" value="<?php echo esc_attr($recurrent_fp=='0.4'?'1':$recurrent_fp); ?>">
<input type="hidden" name="t1" value="<?php echo ($recurrent_fp=='0.4'?'W':'M'); ?>">
<?php } ?>
<?php $selnum = (cp_contactformpp_get_option('paypal_recurrent_times','0')); ?>
<?php if ($selnum != '0') { ?>
<input type="hidden" name="srt" value="<?php echo esc_attr($selnum); ?>">
<?php } ?>
<input type="hidden" name="no_note" value="1" />
<input type="hidden" name="cmd" value="_xclick-subscriptions">
<input type="hidden" name="bn" value="NetFactorSL_SI_Custom">
<input type="hidden" name="a3" value="<?php echo esc_attr(cp_contactformpp_apply_tax($price, $taxes)); ?>">
<input type="hidden" name="p3" value="<?php echo esc_attr($recurrent=='0.4'?'1':$recurrent); ?>">
<input type="hidden" name="t3" value="<?php echo ($recurrent=='0.4'?'W':'M'); ?>">
<input type="hidden" name="src" value="1">
<input type="hidden" name="sra" value="1">
<?php } ?>
<?php if ($taxes != '0' && $taxes != '') { ?>
<input type="hidden" name="tax_rate"  value="<?php echo esc_attr($taxes); ?>" />
<?php } ?>
<input type="hidden" name="page_style" value="Primary" />
<input type="hidden" name="charset" value="utf-8">
<input type="hidden" name="no_shipping" value="<?php if (cp_contactformpp_get_option('request_address','0') != '1') echo '1'; else echo '2'; ?>" />
<input type="hidden" name="return" value="<?php echo esc_url(trim(cp_contactformpp_replace_params(cp_contactformpp_get_option('fp_return_page', CP_CONTACTFORMPP_DEFAULT_fp_return_page),$params))); ?>">
<input type="hidden" name="cancel_return" value="<?php echo esc_url($error_page); ?>" />
<input type="hidden" name="currency_code" value="<?php echo esc_attr(cp_contactformpp_clean_currency(cp_contactformpp_get_option('currency', CP_CONTACTFORMPP_DEFAULT_CURRENCY))); ?>" />
<input type="hidden" name="lc" value="<?php echo esc_attr(trim(cp_contactformpp_get_option('paypal_language', CP_CONTACTFORMPP_DEFAULT_PAYPAL_LANGUAGE))); ?>" />
<input type="hidden" name="notify_url" value="<?php echo esc_attr(cp_contactformpp_get_FULL_site_url()); ?>/?cp_contactformpp_ipncheck=<?php echo esc_attr($item_number); ?>" />
</form>
<script type="text/javascript">
document.ppform3.submit();
</script>
</body>
</html>
<?php
        exit();   
}

function cp_contactformpp_apply_tax($price, $taxes)
{
    if (intval($taxes))
        $price = round( $price + $price * $taxes / 100, 2);
    return $price;
}

function cp_contactformpp_clean_currency($currency)
{
	$currency = trim(strtoupper($currency));
	if ($currency == 'GPB')
		return 'GBP';
    else if ($currency == 'POUNDS')
		return 'GBP';
	else if ($currency == 'CDN')
		return 'CAD';
	else if ($currency == '$')
		return 'USD';
    else if ($currency == 'DOLLAR')
		return 'USD';
    else if ($currency == 'DOLLARS')
		return 'USD'; 
    else if ($currency == 'EURO')
		return 'EUR';
    else if ($currency == '€')
		return 'EUR';
	else if ($currency == 'MXP')
		return 'MXN';
	else if ($currency == 'AUS')
		return 'AUD';    
	else
		return $currency;
}


function cp_contactformpp_add_field_verify ($table, $field, $type = "text") 
{
    global $wpdb;
    $results = $wpdb->get_results("SHOW columns FROM `".$table."` where field='".$field."'");    
    if (!count($results))
    {               
        $sql = "ALTER TABLE  `".$table."` ADD `".$field."` ".$type; 
        $wpdb->query($sql);
    }
}


function cp_contactformpp_check_upload($uploadfiles) {
    $filetmp = $uploadfiles['tmp_name'];
    //clean filename and extract extension
    $filename = $uploadfiles['name'];
    // get file info
    $filetype = wp_check_filetype( basename( $filename ), null );

    if ( in_array ($filetype["ext"],array("php","asp","aspx","cgi","pl","perl","exe","cmd","js","msi","bat","com")) )
        return false;
    else
        return true;
}


function cp_contactformpp_check_IPN_verification() {
    global $wpdb;

    $item_number = intval($_GET["cp_contactformpp_ipncheck"]);
    $item_name = esc_html($_POST['item_name']);
    $payment_status = esc_html($_POST['payment_status']);
    $receiver_email = sanitize_email($_POST['receiver_email']);
    $payer_email = sanitize_email($_POST['payer_email']);
    $payment_type = esc_html($_POST['payment_type']);

    if (CP_CONTACTFORMPP_STEP2_VRFY)
    {
	    if ($payment_status != 'Completed' && $payment_type != 'echeck')
	        return;
        
	    if ($payment_type == 'echeck' && $payment_status != 'Pending')
	        return;
    }
	$str = '';
    if ($_POST["first_name"]) $str .= 'Buyer: '.esc_html($_POST["first_name"])." ".esc_html($_POST["last_name"])."\n";
    if ($_POST["payer_email"]) $str .= 'Payer email: '.$payer_email."\n";
	if ($_POST["residence_country"]) $str .= 'Country code: '.esc_html($_POST["residence_country"])."\n";
	if ($_POST["payer_status"]) $str .= 'Payer status: '.esc_html($_POST["payer_status"])."\n";
	if ($_POST["protection_eligibility"]) $str .= 'Protection eligibility: '.esc_html($_POST["protection_eligibility"])."\n";

	if ($_POST["item_name"]) $str .= 'Item: '.$item_name."\n";
	if ($_POST["payment_gross"])
	     $str .= 'Payment: '.esc_html($_POST["payment_gross"])." ".esc_html($_POST["mc_currency"])." (Fee: ".esc_html($_POST["payment_fee"]).")"."\n";
	else if ($_POST["mc_gross"])
	     $str .= 'Payment: '.esc_html($_POST["mc_gross"])." ".esc_html($_POST["mc_currency"])." (Fee: ".esc_html($_POST["mc_fee"]).")"."\n";
	if ($_POST["payment_date"]) $str .= 'Payment date: '.esc_html($_POST["payment_date"]);
	if ($_POST["payment_type"]) $str .= 'Payment type/status: '.$payment_type."/".$payment_status."\n";
	if ($_POST["business"]) $str .= 'Business: '.esc_html($_POST["business"])."\n";
	if ($_POST["receiver_email"]) $str .= 'Receiver email: '.$receiver_email."\n";

    $myrows = $wpdb->get_results( "SELECT * FROM ".CP_CONTACTFORMPP_POSTS_TABLE_NAME." WHERE id=".intval($item_number));
    
    if (!count($myrows))
        return;
    
    $params = unserialize($myrows[0]->posted_data);

    if ($myrows[0]->paid == 0)
    {
        $params["txnid"] = sanitize_text_field($_POST['txn_id']);
        
        do_action_ref_array( 'cpcfwpp_process_paid', array(&$params) );
        
        $wpdb->query("UPDATE ".CP_CONTACTFORMPP_POSTS_TABLE_NAME." SET paid=1,paypal_post='".esc_sql($str)."',posted_data='".esc_sql(serialize($params))."' WHERE id=".intval($item_number));
        cp_contactformpp_process_ready_to_go_reservation($item_number, $payer_email, $params);
        echo 'OK - processed';
    }
    else
        echo 'OK - already processed';
    
    exit();
}


function cp_contactformpp_check_IPN_verification_express() {
    global $wpdb;

    $itemnumber = intval($_GET['cp_contactformpp_ipncheckexpress']);
    $myrows = $wpdb->get_results( "SELECT * FROM ".CP_CONTACTFORMPP_POSTS_TABLE_NAME." WHERE id=".intval($itemnumber));
    $params = unserialize($myrows[0]->posted_data);

    if (!defined('CP_CONTACTFORMPP_ID'))
        define ('CP_CONTACTFORMPP_ID',$myrows[0]->formid);    
        
	try 
	{	
        $products = array();
        $products[0]['ItemName'] = $params["PayPal Product Name"]; //Item Name
	    $products[0]['ItemPrice'] = str_replace(',','',$params["Cost"]); //Item Price
	    $products[0]['ItemNumber'] = $itemnumber; //Item Number
	    $products[0]['ItemDesc'] = $params["PayPal Product Name"]; //Item Number
	    $products[0]['ItemQty']	= 1; // Item Quantity
	    
	    $charges = array(); //Other important variables like tax, shipping cost
	    $charges['TotalTaxAmount'] = $params["taxamount"];  //Sum of tax for all items in this order. 
	    $charges['HandalingCost'] = 0;  //Handling cost for this order.
	    $charges['InsuranceCost'] = 0;  //shipping insurance cost for this order.
	    $charges['ShippinDiscount'] = 0; //Shipping discount for this order. Specify this as negative number.
	    $charges['ShippinCost'] = 0; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.		        			    
	    
	    @include_once dirname( __FILE__ ) . '/cp_paypal.express.php';
        $ppexp = new DEXBCCF_PayPalEXPC();
        $ppexp->mode = cp_contactformpp_get_option('paypal_mode',CP_CONTACTFORMPP_DEFAULT_PAYPAL_MODE);        
        $ppexp->API_UserName = cp_contactformpp_get_option('paypalexpress_api_username','');        
        $ppexp->API_Password = cp_contactformpp_get_option('paypalexpress_api_password','');
        $ppexp->API_Signature = cp_contactformpp_get_option('paypalexpress_api_signature','');
        $ppexp->currency = strtoupper(cp_contactformpp_get_option('currency', CP_CONTACTFORMPP_DEFAULT_CURRENCY));
        $ppexp->lang = cp_contactformpp_get_option('paypal_language', CP_CONTACTFORMPP_DEFAULT_PAYPAL_LANGUAGE);      			    
        $ppexp->DoExpressCheckoutPayment($products, $charges);

	} catch (Exception $e) {
        echo "Error: ".esc_html($e->getMessage());
        exit;
    }	    

    if ($myrows[0]->paid == 0)
    {
        $wpdb->query("UPDATE ".CP_CONTACTFORMPP_POSTS_TABLE_NAME." SET paid=1,paypal_post='".esc_sql($str)."' WHERE id=".intval($itemnumber));
        cp_contactformpp_process_ready_to_go_reservation($itemnumber, $payer_email, $params);        
    }
    
    header('Location: '. cp_contactformpp_replace_params( cp_contactformpp_get_option('fp_return_page', CP_CONTACTFORMPP_DEFAULT_fp_return_page), $params) );
    exit();
}


function cp_contactformpp_process_ready_to_go_reservation($itemnumber, $payer_email = "", $params = array())
{

   global $wpdb;
    $itemnumber = intval($itemnumber);
   
    if (!defined('CP_CONTACTFORMPP_DEFAULT_fp_from_email'))  define('CP_CONTACTFORMPP_DEFAULT_fp_from_email', cp_contactformpp_get_default_from_email()  );
    if (!defined('CP_CONTACTFORMPP_DEFAULT_fp_destination_emails')) define('CP_CONTACTFORMPP_DEFAULT_fp_destination_emails', get_the_author_meta('user_email', get_current_user_id()) );

   $myrows = $wpdb->get_results( "SELECT * FROM ".CP_CONTACTFORMPP_POSTS_TABLE_NAME." WHERE id=".$itemnumber );

   $mycalendarrows = $wpdb->get_results( 'SELECT * FROM '. $wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE .' WHERE `id`='.$myrows[0]->formid);

   if (!defined('CP_CONTACTFORMPP_ID'))
        define ('CP_CONTACTFORMPP_ID',$myrows[0]->formid);

    $buffer_A = $myrows[0]->data;
    $buffer = $buffer_A;

    if ('true' == cp_contactformpp_get_option('fp_inc_additional_info', CP_CONTACTFORMPP_DEFAULT_fp_inc_additional_info))
    {
        $buffer .="ADDITIONAL INFORMATION\n"
              ."*********************************\n"
              ."IP: ".$myrows[0]->ipaddr."\n"
              ."Server Time:  ".date("Y-m-d H:i:s")."\n";
    }

    // 1- Send email
    //---------------------------
    $attachments = array();
    if ('html' == cp_contactformpp_get_option('fp_emailformat', CP_CONTACTFORMPP_DEFAULT_email_format))
        $message = str_replace('<'.'%INFO%'.'>',str_replace("\n","<br />",str_replace('<','&lt;',$buffer)),cp_contactformpp_get_option('fp_message', CP_CONTACTFORMPP_DEFAULT_fp_message));
    else    
        $message = str_replace('<'.'%INFO%'.'>',$buffer,cp_contactformpp_get_option('fp_message', CP_CONTACTFORMPP_DEFAULT_fp_message));    
    foreach ($params as $item => $value)
    {
        $message = str_replace('<'.'%'.$item.'%'.'>',(is_array($value)?(implode(", ",$value)):($value)),$message);
        if (strpos($item,"_link"))
            $attachments[] = $value;
    }
       
    $message = str_replace('<'.'%itemnumber%'.'>',$itemnumber,$message);    
    $subject = cp_contactformpp_get_option('fp_subject', CP_CONTACTFORMPP_DEFAULT_fp_subject);
    $from = cp_contactformpp_get_option('fp_from_email', CP_CONTACTFORMPP_DEFAULT_fp_from_email);
    $to = explode(",",cp_contactformpp_get_option('fp_destination_emails', CP_CONTACTFORMPP_DEFAULT_fp_destination_emails));
    if ('html' == cp_contactformpp_get_option('fp_emailformat', CP_CONTACTFORMPP_DEFAULT_email_format)) $content_type = "Content-Type: text/html; charset=utf-8\n"; else $content_type = "Content-Type: text/plain; charset=utf-8\n";
    $replyto = $myrows[0]->notifyto;
    
    if (!strpos($from,">"))
       $from = '"'.$from.'" <'.$from.'>';
           
    foreach ($to as $item)
        if (trim($item) != '')
        {
            wp_mail(sanitize_email($item), $subject, $message,
                "From: ".$from."\r\n".
                ($replyto!=''?"Reply-To: ".$replyto."\r\n":'').
                $content_type.
                "X-Mailer: PHP/" . phpversion(), $attachments);
        }

    // 2- Send copy to user
    //---------------------------
    $to = cp_contactformpp_get_option('cu_user_email_field', CP_CONTACTFORMPP_DEFAULT_cu_user_email_field);
    $_POST[$to] = $myrows[0]->notifyto;
    if ((trim($_POST[$to]) != '' || $payer_email != '') && 'true' == cp_contactformpp_get_option('cu_enable_copy_to_user', CP_CONTACTFORMPP_DEFAULT_cu_enable_copy_to_user))
    {
        if ('html' == cp_contactformpp_get_option('cu_emailformat', CP_CONTACTFORMPP_DEFAULT_email_format))
            $message = str_replace('<'.'%INFO%'.'>',str_replace("\n","<br />",str_replace('<','&lt;',$buffer_A)).'</pre>',cp_contactformpp_get_option('cu_message', CP_CONTACTFORMPP_DEFAULT_cu_message));
        else    
            $message = str_replace('<'.'%INFO%'.'>',$buffer_A,cp_contactformpp_get_option('cu_message', CP_CONTACTFORMPP_DEFAULT_cu_message));
        foreach ($params as $item => $value)
            $message = str_replace('<'.'%'.$item.'%'.'>',(is_array($value)?(implode(", ",$value)):($value)),$message);
        $message = str_replace('<'.'%itemnumber%'.'>',$itemnumber,$message);        
        $subject = cp_contactformpp_get_option('cu_subject', CP_CONTACTFORMPP_DEFAULT_cu_subject);
        if ('html' == cp_contactformpp_get_option('cu_emailformat', CP_CONTACTFORMPP_DEFAULT_email_format)) $content_type = "Content-Type: text/html; charset=utf-8\n"; else $content_type = "Content-Type: text/plain; charset=utf-8\n";
        if ($_POST[$to] != '')
            wp_mail(sanitize_email($_POST[$to]), $subject, $message,
                    "From: ".$from."\r\n".
                    $content_type.
                    "X-Mailer: PHP/" . phpversion());
        if (strtolower($_POST[$to]) != strtolower($payer_email) && $payer_email != '')
            wp_mail(sanitize_email($payer_email), $subject, $message,
                    "From: ".$from."\r\n".
                    $content_type.
                    "X-Mailer: PHP/" . phpversion());
    }

}


function cp_contactformpp_get_field_name ($fieldid, $form) 
{
    if (is_array($form))
        foreach($form as $item)
            if ($item->name == $fieldid)
                return $item->title;
    return $fieldid;
}


function cp_contactformpp_clean_csv_value($value)
{
    $value = trim($value);
    while (strlen($value) > 1 && in_array($value[0],array('=','@')))
        $value = trim(substr($value, 1));
    return $value;
}

function cp_contactformpp_export_csv ()
{
    if (!is_admin())
        return;
    global $wpdb;
    
    if (!defined('CP_CONTACTFORMPP_ID'))
        define ('CP_CONTACTFORMPP_ID',intval($_GET["cal"]));
    
    $form_data = json_decode(cp_contactformpp_cleanJSON(cp_contactformpp_get_option('form_structure', CP_CONTACTFORMPP_DEFAULT_form_structure)));
    
    $cond = '';
    if ($_GET["search"] != '') $cond .= " AND (data like '%".esc_sql($_GET["search"])."%' OR paypal_post LIKE '%".esc_sql($_GET["search"])."%')";
    if ($_GET["dfrom"] != '') $cond .= " AND (`time` >= '".esc_sql($_GET["dfrom"])."')";
    if ($_GET["dto"] != '') $cond .= " AND (`time` <= '".esc_sql($_GET["dto"])." 23:59:59')";
    if (CP_CONTACTFORMPP_ID != 0) $cond .= " AND formid=".CP_CONTACTFORMPP_ID;
    
    $events = $wpdb->get_results( "SELECT * FROM ".CP_CONTACTFORMPP_POSTS_TABLE_NAME." WHERE 1=1 ".$cond." ORDER BY `time` DESC" );
    
    $fields = array("Form ID", "Time", "IP Address", "email", "Paid");
    $values = array();
    foreach ($events as $item)
    {
        $value = array($item->formid, $item->time, $item->ipaddr, $item->notifyto, ($item->paid?"Yes":"No"));
        $data = array();
        if ($item->posted_data)
            $data = unserialize($item->posted_data);
        else if (!$item->paid)
            $data = unserialize($item->paypal_post);
            
        $end = count($fields); 
        for ($i=0; $i<$end; $i++) 
            if (isset($data[$fields[$i]]) ){
                $value[$i] = $data[$fields[$i]];
                unset($data[$fields[$i]]);
            }    
        
        foreach ($data as $k => $d)    
        {
           $fields[] = $k;
           $value[] = $d;
        }        
        $values[] = $value;        
    }    
    
    
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=export".date("Y-m-d").".csv");  
    
    $end = count($fields); 
    for ($i=0; $i<$end; $i++)
    {
        $hlabel = cp_contactformpp_iconv("utf-8", "ISO-8859-1//TRANSLIT//IGNORE", cp_contactformpp_get_field_name($fields[$i],@$form_data[0]));
        echo '"'.str_replace('"','""', cp_contactformpp_clean_csv_value($hlabel)).'",';
    }
    echo "\n";
    foreach ($values as $item)    
    {        
        for ($i=0; $i<$end; $i++)
        {
            if (!isset($item[$i])) 
                $item[$i] = '';
            if (is_array($item[$i]))    
                $item[$i] = implode(',',$item[$i]);       
            $item[$i] = cp_contactformpp_iconv("utf-8", "ISO-8859-1//TRANSLIT//IGNORE", $item[$i]);            
            echo '"'.str_replace('"','""', cp_contactformpp_clean_csv_value($item[$i])).'",';
        }    
        echo "\n";
    }
    
    exit;    
}

function cp_contactformpp_defer_enabled()
{
    return CP_CONTACTFORMPP_DEFAULT_DEFER_SCRIPTS_LOADING || true; 
}


function cp_contactformpp_iconv($from, $to, $text)
{
    $text = trim($text);
    if ( strlen($text) > 1 && (in_array(substr($text,0,1), array('=','@','+','-'))) )
    {
        if (substr($text,0,1) != '-' || floatval($text)."" != $text)
            $text = chr(9).$text;
    }      
    if (function_exists('iconv'))
        return iconv($from, $to, $text);
    else
        return $text;    
}


function cp_contactformpp_translate_json($str)
{
    $form_data = json_decode(cp_contactformpp_cleanJSON($str));        
    
    $form_data[1][0]->title = __($form_data[1][0]->title,'cp-contact-form-with-paypal');   
    $form_data[1][0]->description = __($form_data[1][0]->description,'cp-contact-form-with-paypal');   
            
    for ($i=0; $i < count($form_data[0]); $i++)    
    {
        $form_data[0][$i]->title = cp_contactformpp_filter_allowed_tags(__($form_data[0][$i]->title,'cp-contact-form-with-paypal'));   
        $form_data[0][$i]->userhelpTooltip = cp_contactformpp_filter_allowed_tags(__($form_data[0][$i]->userhelpTooltip,'cp-contact-form-with-paypal')); 
        $form_data[0][$i]->userhelp = cp_contactformpp_filter_allowed_tags(__($form_data[0][$i]->userhelp,'cp-contact-form-with-paypal')); 
        $form_data[0][$i]->csslayout = sanitize_text_field($form_data[0][$i]->csslayout);
        if ($form_data[0][$i]->ftype == 'fCommentArea')
            $form_data[0][$i]->userhelp = __($form_data[0][$i]->userhelp,'cp-contact-form-with-paypal');   
        else 
            if ($form_data[0][$i]->ftype == 'fradio' || $form_data[0][$i]->ftype == 'fcheck' || $form_data[0][$i]->ftype == 'fradio')    
            {
                for ($j=0; $j < count($form_data[0][$i]->choices); $j++)  
                    $form_data[0][$i]->choices[$j] = __($form_data[0][$i]->choices[$j],'cp-contact-form-with-paypal'); 
            } 
    }           
    $str = json_encode($form_data);
    return $str;
}


function cp_contactformpp_filter_allowed_tags($content)
{
    $tags_allowed = array(
                              'a' => array(
                                  'href' => array(),
                                  'title' => array(),
                                  'style' => array(),
                                  'class' => array(),
                              ),
                              'br' => array(),
                              'em' => array(),
                              'b' => array(),
                              'strong' => array(),
                              'img' => array(
                                        'src' => array(),
                                        'width' => array(),
                                        'height' => array(),
                                        'border' => array(),
                                        'style' => array(),
                                        'class' => array(),
                                        ),
                          );       
    //$allowed_tags = wp_kses_allowed_html( 'post' );
    //return  wp_kses( $content, $allowed_tags );
    return  wp_kses( $content, $tags_allowed );
}


function cp_contactformpp_save_options()
{
    global $wpdb;
    if (!defined('CP_CONTACTFORMPP_ID'))
        define ('CP_CONTACTFORMPP_ID',intval($_POST["cp_contactformpp_id"]));


    $verify_nonce = wp_verify_nonce( $_POST['rsave'], 'cfwpp_update_actions_post');
    if (!$verify_nonce)
    {
        echo 'Error: Form cannot be authenticated. Please contact our <a href="https://cfpaypal.dwbooster.com/contact-us">support service</a> for verification and solution. Thank you.';
        return;
    }

    // temporal lines to guarantee migration from previous version    
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'paypalexpress_api_username'," varchar(250) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'paypalexpress_api_password'," varchar(250) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'paypalexpress_api_signature'," varchar(250) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'enable_paypal_expresscredit_yes'," varchar(250) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'enable_paypal_expresscredit_no'," varchar(250) NOT NULL default ''");    
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'pprefunds'," varchar(10) NOT NULL default ''");   
    
    
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'fp_emailformat'," varchar(10) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'cu_emailformat'," varchar(10) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'paypal_notiemails'," varchar(20) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'paypal_mode'," varchar(20) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'paypal_recurrent'," varchar(20) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'paypal_recurrent_fp'," varchar(20) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'paypal_identify_prices'," varchar(20) NOT NULL default ''");        
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'cp_emailformat'," varchar(10) NOT NULL default ''");       
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'request_taxes'," varchar(20) NOT NULL default ''");       
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'request_address'," varchar(20) NOT NULL default ''");       
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'donationlayout'," varchar(20) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'paypal_price_field'," varchar(250) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE,'vs_text_submitbtn'," varchar(250) NOT NULL default ''");
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE, "fp_error_page", "varchar(250) DEFAULT '' NOT NULL");  
    
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE, "paypal_recurrent_setup", "varchar(20) DEFAULT '' NOT NULL");  
    cp_contactformpp_add_field_verify($wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE, "paypal_recurrent_times"," varchar(10) NOT NULL default ''");

    
    $_POST["request_cost"] = cpcfwpp_clean_price($_POST["request_cost"]);
    
    if ((substr_count($_POST['form_structure_control'],"\\") > 1) || substr_count($_POST['form_structure_control'],"\\\"title\\\":"))
        foreach ($_POST as $item => $value)
          if (!is_array($value))
              $_POST[$item] = stripcslashes($value);

    $data = array(
                  'form_structure' => cp_contactformpp_clean ($_POST['form_structure']),
                  'fp_from_email' => sanitize_email($_POST['fp_from_email']),
                  'fp_destination_emails' => sanitize_text_field($_POST['fp_destination_emails']),
                  'fp_subject' => sanitize_text_field($_POST['fp_subject']),
                  'fp_inc_additional_info' => sanitize_text_field($_POST['fp_inc_additional_info']),
                  'fp_return_page' => esc_url_raw($_POST['fp_return_page']),
                  'fp_error_page' => esc_url_raw($_POST['fp_error_page']),
                  'fp_message' => cp_contactformpp_clean($_POST['fp_message']),
                  'fp_emailformat' => sanitize_text_field($_POST['fp_emailformat']),

                  'cu_enable_copy_to_user' => sanitize_text_field($_POST['cu_enable_copy_to_user']),
                  'cu_user_email_field' => sanitize_text_field(@$_POST['cu_user_email_field']),
                  'cu_subject' => sanitize_text_field($_POST['cu_subject']),
                  'cu_message' => cp_contactformpp_clean($_POST['cu_message']),
                  'cu_emailformat' => sanitize_text_field($_POST['cu_emailformat']),

                  'enable_paypal' => sanitize_text_field(@$_POST["enable_paypal"]),
                  
                  'paypalexpress_api_username' => sanitize_text_field(@$_POST["paypalexpress_api_username"]),
                  'paypalexpress_api_password' => sanitize_text_field(@$_POST["paypalexpress_api_password"]),
                  'paypalexpress_api_signature' => sanitize_text_field(@$_POST["paypalexpress_api_signature"]),
                  'enable_paypal_expresscredit_yes' => sanitize_text_field(@$_POST["enable_paypal_expresscredit_yes"]),
                  'enable_paypal_expresscredit_no' => sanitize_text_field(@$_POST["enable_paypal_expresscredit_no"]),                  
                  
                  'pprefunds' => sanitize_text_field(@$_POST["pprefunds"]),
                  
                  'paypal_notiemails' => sanitize_text_field(@$_POST["paypal_notiemails"]),
                  'paypal_email' => sanitize_email($_POST["paypal_email"]),
                  'request_cost' => sanitize_text_field($_POST["request_cost"]),
                  'paypal_price_field' => sanitize_text_field(@$_POST["paypal_price_field"]),
                  'request_taxes' => sanitize_text_field($_POST["request_taxes"]),
                  'request_address' => sanitize_text_field($_POST["request_address"]),
                  'donationlayout' => sanitize_text_field($_POST["donationlayout"]),
                  'paypal_product_name' => sanitize_text_field($_POST["paypal_product_name"]),
                  'currency' => sanitize_text_field($_POST["currency"]),
                  'paypal_language' => sanitize_text_field($_POST["paypal_language"]),
                  'paypal_mode' => sanitize_text_field($_POST["paypal_mode"]),
                  'paypal_recurrent' => sanitize_text_field($_POST["paypal_recurrent"]),
                  'paypal_recurrent_fp' => sanitize_text_field($_POST["paypal_recurrent_fp"]),
                  'paypal_recurrent_setup' => sanitize_text_field($_POST["paypal_recurrent_setup"]),
                  'paypal_recurrent_times' => sanitize_text_field($_POST["paypal_recurrent_times"]),
                  //'paypal_identify_prices' => sanitize_text_field(@$_POST["paypal_identify_prices"]),
                  'paypal_zero_payment' => sanitize_text_field($_POST["paypal_zero_payment"]),

                  'vs_text_is_required' => sanitize_text_field($_POST['vs_text_is_required']),
                  'vs_text_is_email' => sanitize_text_field($_POST['vs_text_is_email']),
                  'vs_text_datemmddyyyy' => sanitize_text_field($_POST['vs_text_datemmddyyyy']),
                  'vs_text_dateddmmyyyy' => sanitize_text_field($_POST['vs_text_dateddmmyyyy']),
                  'vs_text_number' => sanitize_text_field($_POST['vs_text_number']),
                  'vs_text_digits' => sanitize_text_field($_POST['vs_text_digits']),
                  'vs_text_max' => sanitize_text_field($_POST['vs_text_max']),
                  'vs_text_min' => sanitize_text_field($_POST['vs_text_min']),
                  'vs_text_submitbtn' => sanitize_text_field($_POST['vs_text_submitbtn']),

                  'cv_enable_captcha' => sanitize_text_field($_POST['cv_enable_captcha']),
                  'cv_width' => sanitize_text_field($_POST['cv_width']),
                  'cv_height' => sanitize_text_field($_POST['cv_height']),
                  'cv_chars' => sanitize_text_field($_POST['cv_chars']),
                  'cv_font' => sanitize_text_field($_POST['cv_font']),
                  'cv_min_font_size' => sanitize_text_field($_POST['cv_min_font_size']),
                  'cv_max_font_size' => sanitize_text_field($_POST['cv_max_font_size']),
                  'cv_noise' => sanitize_text_field($_POST['cv_noise']),
                  'cv_noise_length' => sanitize_text_field($_POST['cv_noise_length']),
                  'cv_background' => sanitize_text_field(str_replace('#','',$_POST['cv_background'])),
                  'cv_border' => sanitize_text_field(str_replace('#','',$_POST['cv_border'])),
                  'cv_text_enter_valid_captcha' => sanitize_text_field($_POST['cv_text_enter_valid_captcha'])
	);
    $wpdb->update ( $wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE, $data, array( 'id' => CP_CONTACTFORMPP_ID ));

}

function cp_contactformpp_clean ($str)
{
    if ( is_object( $str ) || is_array( $str ) ) {
        return '';
    }
    $str = (string) $str; 
    $filtered = wp_check_invalid_utf8( $str );    
    while ( preg_match( '/%[a-f0-9]{2}/i', $filtered, $match ) ) 
        $filtered = str_replace( $match[0], '', $filtered );
    return trim($filtered);
}

function cpcfwpp_clean_price($price)
{
    return preg_replace('/[^0-9.]+/', '', str_replace(',','.',$price));
}


function cp_contactformpp_data_management_loaded() 
{
    global $wpdb, $cp_contactformpp_postURL;

    if (empty($_POST['cp_contactformpp_do_action_loaded']))
        return;
    
    $action = sanitize_text_field(@$_POST['cp_contactformpp_do_action_loaded']);
	if (!$action) return; // go out if the call isn't for this one

    if ($_POST['cp_contactformpp_id']) $item = intval($_POST['cp_contactformpp_id']);

    if ($action == "wizard" && current_user_can('manage_options') && wp_verify_nonce( $_POST['anonce'], 'cfwpp_actions_pwizard'))
    {
        $shortcode = '[CP_CONTACT_FORM_PAYPAL id="'.$item .'"]';
        if (!empty($_POST["publishpage"])) 
            $publishpage = sanitize_text_field($_POST["publishpage"]); 
        else 
            $publishpage = '';
        if (!empty($_POST["publishpost"])) 
            $publishpost = sanitize_text_field($_POST["publishpost"]); 
        else 
            $publishpost = '';
        $cp_contactformpp_postURL = cp_contactformpp_publish_on(sanitize_text_field(@$_POST["whereto"]), $publishpage, $publishpost, $shortcode, sanitize_text_field(@$_POST["posttitle"]));            
        return;
    }

    // ...
    echo 'Some unexpected error happened. If you see this error contact the support service at https://cfpaypal.dwbooster.com/contact-us';

    exit();
}    


function cp_contactformpp_replace_params($text, $params)
{
    if (is_array($params))
        foreach ($params as $item => $value)
        {
            $text = str_replace ( '<%'.$item.'%>', $value, $text);
            $text = str_replace ( '%'.$item.'%', $value, $text);
        }
    return $text;    
}


function cp_contactformpp_publish_on($whereto, $publishpage = '', $publishpost = '', $content = '', $posttitle = 'Payment Form')
{
    global $wpdb;
    $id = '';
    if ($whereto == '0' || $whereto =='1') // new page
    {
        $my_post = array(
          'post_title'    => $posttitle,
          'post_type' => ($whereto == '0'?'page':'post'),
          'post_content'  => 'This is a <b>preview</b> page, remember to publish it if needed. You can edit the full form settings into the admin settings page.<br /><br /> '.$content,
          'post_status'   => 'draft'
        );
        
        // Insert the post into the database
        $id = wp_insert_post( $my_post );
    }
    else 
    {
        $id = ($whereto == '2'?$publishpage:$publishpost);
        $post = get_post( $id );
        $pos = strpos($post->post_content,$content);
        if ($pos === false)
        {
            $my_post = array(
                  'ID'           => $id,
                  'post_content' => $content.$post->post_content,
              );
            // Update the post into the database
            wp_update_post( $my_post );
        }
    }
    return get_permalink($id);
}


// cp_contactformpp_get_option:
$cp_contactformpp_option_buffered_item = false;
$cp_contactformpp_option_buffered_id = -1;

function cp_contactformpp_get_option ($field, $default_value = '', $id = '')
{
    if (!defined("CP_CONTACTFORMPP_ID"))
    {
        if (!(isset($_GET["itemnumber"]) && intval($_GET["itemnumber"]) != ''))
            define ("CP_CONTACTFORMPP_ID", 1);
    }    
    if ($id == '') 
        $id = CP_CONTACTFORMPP_ID;
    global $wpdb, $cp_contactformpp_option_buffered_item, $cp_contactformpp_option_buffered_id;
    if ($cp_contactformpp_option_buffered_id == $id)
        $value = (property_exists($cp_contactformpp_option_buffered_item, $field) && isset($cp_contactformpp_option_buffered_item->$field) ? @$cp_contactformpp_option_buffered_item->$field : '');
    else
    {
       $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_CONTACTFORMPP_FORMS_TABLE." WHERE id=".$id );
       if (count($myrows)) 
       {
           $value = $myrows[0]->$field;
           $cp_contactformpp_option_buffered_item = $myrows[0];
           $cp_contactformpp_option_buffered_id  = $id;
       }
       else  
           $value = $default_value; 
    }
    if ($value == '' && is_object($cp_contactformpp_option_buffered_item) && $cp_contactformpp_option_buffered_item->form_structure == '')
        $value = $default_value;
        
    if ($field == 'paypal_email' && $value == CP_CONTACTFORMPP_DEFAULT_PAYPAL_EMAIL)    
    {
        $current_user = wp_get_current_user();
        $value = $current_user->user_email;
    }        
    return $value;
}


if (!class_exists('CP_PayPalRefund'))
{
    class CP_PayPalRefund
    {
    private $API_Username, $API_Password, $Signature, $API_Endpoint, $version;
    function __construct($intializeData)
    {
    
        if($intializeData['mode'] == "sandbox")
        {
            $this->API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp";
        }else{
            $this->API_Endpoint = "https://api-3t.paypal.com/nvp";
        }
        $this->API_Username = $intializeData['username'];
        $this->API_Password = $intializeData['password'];
        $this->Signature = $intializeData['signature'];
        $this->version = "94";
    }
    
    /**
     * This function actually Sends the Request for Refund
     * @param string - $requestString
     * @return array - returns the response
     */
    function sendRefundRequest($refundparams)
    {
        //$this->API_UserName  = urlencode($this->API_Username);
        //$this->API_Password  = urlencode($this->API_Password);
        //$this->API_Signature = urlencode($this->Signature);
    
        $this->version = urlencode($this->version);
        
        $refundparams['METHOD'] = 'RefundTransaction';
        $refundparams['VERSION'] = $this->version;
        $refundparams['PWD'] = $this->API_Password;
        $refundparams['USER'] = $this->API_Username;
        $refundparams['SIGNATURE'] = $this->Signature;

        $response = wp_remote_post( 
                                     $this->API_Endpoint,
                                     array ( 'timeout' => 45, 'body' => $refundparams )
                                 );
    
        if ( !is_array( $response ) || is_wp_error( $response ) ) 
            return array("ERROR_MESSAGE"=>"RefundTransaction failed");
    
        // Extract the response details.
        $httpResponseAr = explode("&", $response['body']);
    
        $aryResponse = array();
        foreach ($httpResponseAr as $i => $value)
        {
            $tmpAr = explode("=", $value);
            if(sizeof($tmpAr) > 1)
            {
                $aryResponse[$tmpAr[0]] = urldecode($tmpAr[1]);
            }
        }
        
        if((0 == sizeof($aryResponse)) || !array_key_exists('ACK', $aryResponse))
            return array("ERROR_MESSAGE"=>"Invalid HTTP Response for POST request to {$this->API_Endpoint}");
    
        return $aryResponse;
    }
    
    /**
     * @param array $aryData
     * @return array
     */
    function refundAmount($aryData)
    {
        //if(trim(@$aryData['currencyCode'])=="")
        //    return array("ERROR_MESSAGE"=>"Currency Code is Missing");
        if(trim(@$aryData['refundType'])=="")
            $aryData['refundType'] = 'Full';
        if(trim(@$aryData['transactionID'])=="")
            return array("ERROR_MESSAGE"=>"Transaction ID is Missing");
    
        $requestString = array();
        $requestString['TRANSACTIONID'] = $aryData['transactionID'];
        $requestString['REFUNDTYPE'] = $aryData['refundType'];
        if (trim(@$aryData['currencyCode'])!="")
            $requestString['CURRENCYCODE'] = $aryData['currencyCode'];

        if(trim(@$aryData['invoiceID'])!="")
            $requestString['INVOICEID'] = $aryData['invoiceID'];
    
        if(isset($aryData['memo']))
            $requestString['NOTE'] = $aryData['memo'];
    
        if(strcasecmp($aryData['refundType'], 'Partial') == 0)
        {
            if(!isset($aryData['amount']))
            {
                return array("ERROR_MESSAGE"=>"For Partial Refund - It is essential to mention Amount");
            }
            else
            {
                $requestString['AMT'] = $aryData['amount'];
            }
    
            if(!isset($aryData['memo']))
            {
                return array("ERROR_MESSAGE"=>"For Partial Refund - It is essential to enter text for Memo");
            }
        }
    
        $res = $this->sendRefundRequest($requestString);
        return $res;
    }
    
    }
}


$codepeople_promote_banner_plugins[ 'cp-contact-form-with-paypal' ] = array( 
                      'plugin_name' => 'CP Contact Form with PayPal', 
                      'plugin_url'  => 'https://wordpress.org/support/plugin/cp-contact-form-with-paypal/reviews/?filter=5#new-post'
);
require_once 'banner.php';

?>