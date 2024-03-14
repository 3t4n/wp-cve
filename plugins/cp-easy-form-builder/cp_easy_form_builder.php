<?php
/*
Plugin Name: Form Builder CP
Plugin URI: https://wordpress.dwbooster.com/forms/cp-easy-form-builder
Description: This plugin allows you to easily insert forms into your website and get them via email.
Version: 1.2.37
Author: CodePeople
Author URI: https://form2email.dwbooster.com
License: GPL
Text Domain: cp-easy-form-builder
*/


/* initialization / install / uninstall functions */


define('CP_EASYFORM_TABLE_NAME_NO_PREFIX', "cp_easy_forms");
define('CP_EASYFORM_TABLE_NAME', @$wpdb->prefix . CP_EASYFORM_TABLE_NAME_NO_PREFIX);


// CP Easy Form constants

define('CP_EASYFORM_DEFAULT_DEFER_SCRIPTS_LOADING', (get_option('CP_EFB_LOAD_SCRIPTS',"0") == "1"?true:false));


define('CP_EASYFORM_DEFAULT_form_structure', '[[{"name":"email","index":0,"title":"Email","ftype":"femail","userhelp":"","csslayout":"","required":true,"predefined":"","size":"medium"},{"name":"subject","index":1,"title":"Subject","required":true,"ftype":"ftext","userhelp":"","csslayout":"","predefined":"","size":"medium"},{"name":"message","index":2,"size":"large","required":true,"title":"Message","ftype":"ftextarea","userhelp":"","csslayout":"","predefined":""}],[{"title":"Contact Form","description":"You can use the following form to contact us.","formlayout":"top_aligned"}]]');

define('CP_EASYFORM_DEFAULT_fp_subject', 'Contact from the blog...');
define('CP_EASYFORM_DEFAULT_fp_inc_additional_info', 'true');
define('CP_EASYFORM_DEFAULT_fp_return_page', get_site_url());
define('CP_EASYFORM_DEFAULT_fp_message', "The following contact message has been sent:\n\n<"."%INFO%".">\n\n");

define('CP_EASYFORM_DEFAULT_cu_enable_copy_to_user', 'true');
define('CP_EASYFORM_DEFAULT_cu_user_email_field', '');
define('CP_EASYFORM_DEFAULT_cu_subject', 'Confirmation: Message received...');
define('CP_EASYFORM_DEFAULT_cu_message', "Thank you for your message. We will reply you as soon as possible.\n\nThis is a copy of the data sent:\n\n<"."%INFO%".">\n\nBest Regards.");

define('CP_EASYFORM_DEFAULT_vs_use_validation', 'true');

define('CP_EASYFORM_DEFAULT_vs_text_is_required', 'This field is required.');
define('CP_EASYFORM_DEFAULT_vs_text_is_email', 'Please enter a valid email address.');

define('CP_EASYFORM_DEFAULT_vs_text_datemmddyyyy', 'Please enter a valid date with this format(mm/dd/yyyy)');
define('CP_EASYFORM_DEFAULT_vs_text_dateddmmyyyy', 'Please enter a valid date with this format(dd/mm/yyyy)');
define('CP_EASYFORM_DEFAULT_vs_text_number', 'Please enter a valid number.');
define('CP_EASYFORM_DEFAULT_vs_text_digits', 'Please enter only digits.');
define('CP_EASYFORM_DEFAULT_vs_text_max', 'Please enter a value less than or equal to {0}.');
define('CP_EASYFORM_DEFAULT_vs_text_min', 'Please enter a value greater than or equal to {0}.');


define('CP_EASYFORM_DEFAULT_cv_enable_captcha', 'true');
define('CP_EASYFORM_DEFAULT_cv_width', '180');
define('CP_EASYFORM_DEFAULT_cv_height', '60');
define('CP_EASYFORM_DEFAULT_cv_chars', '5');
define('CP_EASYFORM_DEFAULT_cv_font', 'font-1.ttf');
define('CP_EASYFORM_DEFAULT_cv_min_font_size', '25');
define('CP_EASYFORM_DEFAULT_cv_max_font_size', '35');
define('CP_EASYFORM_DEFAULT_cv_noise', '200');
define('CP_EASYFORM_DEFAULT_cv_noise_length', '4');
define('CP_EASYFORM_DEFAULT_cv_background', 'ffffff');
define('CP_EASYFORM_DEFAULT_cv_border', '000000');
define('CP_EASYFORM_DEFAULT_cv_text_enter_valid_captcha', 'Please enter a valid captcha code.');

define('CP_EASYFORM_FORMS_TABLE', 'cp_easy_form_settings');

// end CP Easy Form constants


require_once 'banner.php';
$codepeople_promote_banner_plugins[ 'cp-easy-form-builder' ] = array( 'plugin_name' => 'CP Easy Form Builder (Form Builder CP)', 'plugin_url'  => 'https://wordpress.org/support/plugin/cp-easy-form-builder/reviews/?filter=5#new-post');


register_activation_hook(__FILE__,'cp_easyform_install');
register_deactivation_hook( __FILE__, 'cp_easyform_remove' );


function cp_easyform_install($networkwide)  {
	global $wpdb;
    
	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide) {
	                $old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				_cp_easyform_install();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	_cp_easyform_install();
}

function _cp_easyform_install() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    
    define('CP_EASYFORM_DEFAULT_fp_from_email', get_the_author_meta('user_email', get_current_user_id()) );
    define('CP_EASYFORM_DEFAULT_fp_destination_emails', CP_EASYFORM_DEFAULT_fp_from_email);

    $table_name = $wpdb->prefix.CP_EASYFORM_FORMS_TABLE;

    $sql = "CREATE TABLE $table_name (
         id mediumint(9) NOT NULL AUTO_INCREMENT,

         form_name VARCHAR(250) DEFAULT '' NOT NULL,

         form_structure mediumtext,

         fp_from_email VARCHAR(250) DEFAULT '' NOT NULL,
         fp_destination_emails text,
         fp_subject VARCHAR(250) DEFAULT '' NOT NULL,
         fp_inc_additional_info VARCHAR(10) DEFAULT '' NOT NULL,
         fp_return_page VARCHAR(250) DEFAULT '' NOT NULL,
         fp_message text,

         cu_enable_copy_to_user VARCHAR(10) DEFAULT '' NOT NULL,
         cu_user_email_field VARCHAR(250) DEFAULT '' NOT NULL,
         cu_subject VARCHAR(250) DEFAULT '' NOT NULL,
         cu_message text,

         vs_use_validation VARCHAR(10) DEFAULT '' NOT NULL,
         vs_text_is_required VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_is_email VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_datemmddyyyy VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_dateddmmyyyy VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_number VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_digits VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_max VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_min VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_submitbtn VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_previousbtn VARCHAR(250) DEFAULT '' NOT NULL,
         vs_text_nextbtn VARCHAR(250) DEFAULT '' NOT NULL,

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

    $count = $wpdb->get_var( "SELECT COUNT(id) FROM ".$table_name  );
    if (!$count)
    {                
        $wpdb->insert( $table_name, array( 'id' => 1,
                                      'form_name' => 'Form 1',

                                      'form_structure' => get_option('form_structure', CP_EASYFORM_DEFAULT_form_structure),

                                      'fp_from_email' => get_option('fp_from_email', CP_EASYFORM_DEFAULT_fp_from_email),
                                      'fp_destination_emails' => get_option('fp_destination_emails', CP_EASYFORM_DEFAULT_fp_destination_emails),
                                      'fp_subject' => get_option('fp_subject', CP_EASYFORM_DEFAULT_fp_subject),
                                      'fp_inc_additional_info' => get_option('fp_inc_additional_info', CP_EASYFORM_DEFAULT_fp_inc_additional_info),
                                      'fp_return_page' => get_option('fp_return_page', CP_EASYFORM_DEFAULT_fp_return_page),
                                      'fp_message' => get_option('fp_message', CP_EASYFORM_DEFAULT_fp_message),

                                      'cu_enable_copy_to_user' => get_option('cu_enable_copy_to_user', CP_EASYFORM_DEFAULT_cu_enable_copy_to_user),
                                      'cu_user_email_field' => get_option('cu_user_email_field', CP_EASYFORM_DEFAULT_cu_user_email_field),
                                      'cu_subject' => get_option('cu_subject', CP_EASYFORM_DEFAULT_cu_subject),
                                      'cu_message' => get_option('cu_message', CP_EASYFORM_DEFAULT_cu_message),

                                      'vs_use_validation' => get_option('vs_use_validation', CP_EASYFORM_DEFAULT_vs_use_validation),
                                      'vs_text_is_required' => get_option('vs_text_is_required', CP_EASYFORM_DEFAULT_vs_text_is_required),
                                      'vs_text_is_email' => get_option('vs_text_is_email', CP_EASYFORM_DEFAULT_vs_text_is_email),
                                      'vs_text_datemmddyyyy' => get_option('vs_text_datemmddyyyy', CP_EASYFORM_DEFAULT_vs_text_datemmddyyyy),
                                      'vs_text_dateddmmyyyy' => get_option('vs_text_dateddmmyyyy', CP_EASYFORM_DEFAULT_vs_text_dateddmmyyyy),
                                      'vs_text_number' => get_option('vs_text_number', CP_EASYFORM_DEFAULT_vs_text_number),
                                      'vs_text_digits' => get_option('vs_text_digits', CP_EASYFORM_DEFAULT_vs_text_digits),
                                      'vs_text_max' => get_option('vs_text_max', CP_EASYFORM_DEFAULT_vs_text_max),
                                      'vs_text_min' => get_option('vs_text_min', CP_EASYFORM_DEFAULT_vs_text_min),
                                      'vs_text_submitbtn' => get_option('vs_text_submitbtn', 'Submit'),
                                      'vs_text_previousbtn' => get_option('vs_text_previousbtn', 'Previous'),
                                      'vs_text_nextbtn' => get_option('vs_text_nextbtn', 'Next'), 

                                      'cv_enable_captcha' => get_option('cv_enable_captcha', CP_EASYFORM_DEFAULT_cv_enable_captcha),
                                      'cv_width' => get_option('cv_width', CP_EASYFORM_DEFAULT_cv_width),
                                      'cv_height' => get_option('cv_height', CP_EASYFORM_DEFAULT_cv_height),
                                      'cv_chars' => get_option('cv_chars', CP_EASYFORM_DEFAULT_cv_chars),
                                      'cv_font' => get_option('cv_font', CP_EASYFORM_DEFAULT_cv_font),
                                      'cv_min_font_size' => get_option('cv_min_font_size', CP_EASYFORM_DEFAULT_cv_min_font_size),
                                      'cv_max_font_size' => get_option('cv_max_font_size', CP_EASYFORM_DEFAULT_cv_max_font_size),
                                      'cv_noise' => get_option('cv_noise', CP_EASYFORM_DEFAULT_cv_noise),
                                      'cv_noise_length' => get_option('cv_noise_length', CP_EASYFORM_DEFAULT_cv_noise_length),
                                      'cv_background' => get_option('cv_background', CP_EASYFORM_DEFAULT_cv_background),
                                      'cv_border' => get_option('cv_border', CP_EASYFORM_DEFAULT_cv_border),
                                      'cv_text_enter_valid_captcha' => get_option('cv_text_enter_valid_captcha', CP_EASYFORM_DEFAULT_cv_text_enter_valid_captcha)
                                     )
                      );     
    }

    add_option("cp_easyform_data", 'Default', '', 'yes'); // Creates new database field    
}

function cp_easyform_remove() {
    delete_option('cp_easyform_data'); // Deletes the database field
}


function cpeasyformbuilder_gutenberg_block() {
    global $wpdb;
    
    wp_enqueue_script( 'cpefbuilder_gutenberg_editor', plugins_url('/js/block.js', __FILE__));

    wp_enqueue_style('cp-easyform-publicstyle', plugins_url('css/stylepublic.css', __FILE__));                   
    
    wp_deregister_script('query-stringify');
    wp_register_script('query-stringify', plugins_url('/js/jQuery.stringify.js', __FILE__));
    
    wp_deregister_script('cp_easyform_validate_script');
    wp_register_script('cp_easyform_validate_script', plugins_url('/js/jquery.validate.js', __FILE__));
    
    wp_enqueue_script( 'cp_easyform_builder_script', 
    plugins_url('/js/fbuilderf.jquery.js', __FILE__),array("jquery","jquery-ui-core","jquery-ui-datepicker","jquery-ui-widget","jquery-ui-position","jquery-ui-tooltip","query-stringify","cp_easyform_validate_script"), false, true );
           
    $forms = array();
    $rows = $wpdb->get_results("SELECT id,form_name FROM ".$wpdb->prefix.CP_EASYFORM_FORMS_TABLE." ORDER BY form_name");
    foreach ($rows as $item)
       $forms[] = array (
                        'value' => $item->id,
                        'label' => $item->form_name,
                        );

    wp_localize_script( 'cpefbuilder_gutenberg_editor', 'cpefbuilder_forms', array(
                        'forms' => $forms,
                        'siteUrl' => get_site_url()
                      ) );     
}


function cpeasyformbuilder_render_form_admin ($atts) {
    $is_gutemberg_editor = defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'];
    if (!$is_gutemberg_editor)
        return cp_easyform_filter_content (array('id' => $atts["formId"]));
    else if ($atts["formId"])
    {
        return '<input type="hidden" name="form_structure'.$atts["instanceId"].'" id="form_structure'.$atts["instanceId"].'" value="'.esc_attr(cp_easyform_get_option('form_structure' ,CP_EASYFORM_DEFAULT_form_structure, $atts["formId"])).'" /><fieldset class="ahbgutenberg_editor" disabled><div id="fbuilder"><div id="fbuilder_'.$atts["instanceId"].'"><div id="formheader_'.$atts["instanceId"].'"></div><div id="fieldlist_'.$atts["instanceId"].'"></div></div></div></fieldset>';
    }
    else
        return '';
}


/* Filter for placing the form into the contents */

function cp_easyform_filter_content($atts) {
    global $wpdb;    
    extract( shortcode_atts( array(
		'id' => '',
	), $atts ) );
    //if ($id != '')
    //    define ('CP_EASYFORM_ID',$id);
    $id = str_replace("&quot;","",$id);
    ob_start();
    if (!defined('CP_AUTH_INCLUDE')) define('CP_AUTH_INCLUDE', true);
    cp_easyform_get_public_form($id); 
    $buffered_contents = ob_get_contents();
    ob_end_clean();
    
    return $buffered_contents;
}



$CP_EFB_global_form_count_number = 0;
$CP_EFB_global_form_count = "_".$CP_EFB_global_form_count_number;

function cp_easyform_get_public_form($id = 1) {
    global $wpdb; 
    global $CP_EFB_global_form_count;
    global $CP_EFB_global_form_count_number;
    $CP_EFB_global_form_count_number++;
    $CP_EFB_global_form_count = "_".$CP_EFB_global_form_count_number;      
    
    if ($id)
        $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_EASYFORM_FORMS_TABLE." WHERE id=".$id );
    else
        $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_EASYFORM_FORMS_TABLE );
    //define ('CP_EASYFORM_ID',$myrows[0]->id);     
    $id = $myrows[0]->id;
    
    $previous_label = cp_easyform_get_option('vs_text_previousbtn', 'Previous',$id);
    $previous_label = ($previous_label==''?'Previous':$previous_label);
    $next_label = cp_easyform_get_option('vs_text_nextbtn', 'Next',$id);
    $next_label = ($next_label==''?'Next':$next_label);  
        
 
    wp_enqueue_style('cp-easyform-publiclstyle', plugins_url('css/stylepublic.css', __FILE__));     
    wp_enqueue_style('cp-easyform-publiclstyledesign', plugins_url('css/cupertino/jquery-ui-1.8.20.custom.css', __FILE__));     
    
    wp_deregister_script('query-stringify');
    wp_register_script('query-stringify', plugins_url('/js/jQuery.stringify.js', __FILE__));
    
    wp_deregister_script('cp_contactformpp_validate_script');
    wp_register_script('cp_easyform_validate_script', plugins_url('/js/jquery.validate.js', __FILE__));
    
    wp_enqueue_script( 'cp_easyform_builder_script', 
    plugins_url('/js/fbuilderf.jquery.js', __FILE__),array("jquery","jquery-ui-core","jquery-ui-datepicker","jquery-ui-widget","jquery-ui-position","jquery-ui-tooltip","query-stringify","cp_easyform_validate_script"), false, true );
    
       
    wp_localize_script('cp_easyform_builder_script', 'cp_easyform_fbuilder_config'.$CP_EFB_global_form_count, array('obj'  	=>
    '{"pub":true,"identifier":"'.$CP_EFB_global_form_count.'","messages": {
    	                	"required": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_is_required', CP_EASYFORM_DEFAULT_vs_text_is_required,$id)).'",
    	                	"email": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_is_email', CP_EASYFORM_DEFAULT_vs_text_is_email,$id)).'",
    	                	"datemmddyyyy": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_datemmddyyyy', CP_EASYFORM_DEFAULT_vs_text_datemmddyyyy,$id)).'",
    	                	"dateddmmyyyy": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_dateddmmyyyy', CP_EASYFORM_DEFAULT_vs_text_dateddmmyyyy,$id)).'",
    	                	"number": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_number', CP_EASYFORM_DEFAULT_vs_text_number,$id)).'",
    	                	"digits": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_digits', CP_EASYFORM_DEFAULT_vs_text_digits,$id)).'",
    	                	"max": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_max', CP_EASYFORM_DEFAULT_vs_text_max,$id)).'",
    	                	"min": "'.str_replace(array('"'),array('\\"'),cp_easyform_get_option('vs_text_min', CP_EASYFORM_DEFAULT_vs_text_min,$id)).'",
                        	"previous": "'.str_replace(array('"'),array('\\"'),$previous_label).'",
                        	"next": "'.str_replace(array('"'),array('\\"'),$next_label).'"
    	                }}'
    ));  

   
?>    
<script type="text/javascript">     
 function cp_easyform_pform_doValidate<?php echo esc_js($CP_EFB_global_form_count); ?>(form)
 {
    form.cp_ref_page.value = document.location;
    <?php if (cp_easyform_get_option('cv_enable_captcha', CP_EASYFORM_DEFAULT_cv_enable_captcha,$id) != 'false') { ?>  $dexQuery = jQuery.noConflict();    
    if (form.hdcaptcha.value == '') { setTimeout( "cp_easyform_cerror('<?php echo esc_js($CP_EFB_global_form_count); ?>')", 100); return false; }          
    var result = $dexQuery.ajax({ type: "GET", url: "<?php echo esc_js(cp_easyform_get_site_url()); ?>/?ps=<?php echo esc_js($CP_EFB_global_form_count); ?>&cp_easyform_pform_process=2&hdcaptcha="+form.hdcaptcha.value, async: false }).responseText;
    if (result == "captchafailed") {
        $dexQuery("#captchaimg<?php echo esc_js($CP_EFB_global_form_count); ?>").attr('src', $dexQuery("#captchaimg<?php echo esc_js($CP_EFB_global_form_count); ?>").attr('src')+'&'+Math.floor((Math.random() * 99999) + 1));
        setTimeout( "cp_easyform_cerror('<?php echo esc_js($CP_EFB_global_form_count); ?>')", 100);
        return false;
    } else <?php } ?>
    {
        document.getElementById("form_structure<?php echo esc_js($CP_EFB_global_form_count); ?>").value = '';  
        return true;
    }
 }
 function cp_easyform_cerror(id){$dexQuery = jQuery.noConflict();$dexQuery("#hdcaptcha_error"+id).css('top',$dexQuery("#hdcaptcha"+id).outerHeight());$dexQuery("#hdcaptcha_error"+id).css("display","inline");}
</script>
<?php    
    $button_label = cp_easyform_get_option('vs_text_submitbtn', 'Submit',$id);
    $button_label = ($button_label==''?'Submit':$button_label);
    
    @include dirname( __FILE__ ) . '/cp_easyform_public_int.inc.php';
   
}



function cp_easyform_show_booking_form($id = "")
{
    if ($id != '')
        define ('CP_EASYFORM_ID',$id);
    define('CP_AUTH_INCLUDE', true);
    @include dirname( __FILE__ ) . '/cp_easyform_public_int.inc.php';    
}

/* Code for the admin area */

if ( is_admin() ) {
    add_action('media_buttons', 'set_cp_easyform_insert_button', 100);
    add_action('admin_enqueue_scripts', 'set_cp_easyform_insert_adminScripts', 1);
    add_action('admin_menu', 'cp_easyform_admin_menu');    
    add_action('enqueue_block_editor_assets', 'cpeasyformbuilder_gutenberg_block' );

    $plugin = plugin_basename(__FILE__);
    add_filter("plugin_action_links_".$plugin, 'cp_easyform_customAdjustmentsLink');
    add_filter("plugin_action_links_".$plugin, 'cp_easyform_settingsLink');
    add_filter("plugin_action_links_".$plugin, 'cp_easyform_helpLink');

    function cp_easyform_admin_menu() {
        add_options_page('CP Easy Form Builder Options', 'CP Easy Form Builder', 'manage_options', 'cp_easy_form_builder', 'cp_easyform_html_post_page' );
        add_menu_page( 'CP Easy Form Builder Options', 'CP Easy Form Builder', 'edit_pages', 'cp_easy_form_builder', 'cp_easyform_html_post_page' );
        add_submenu_page( 'cp_easy_form_builder', 'Upgrade', 'Upgrade', 'edit_pages', "cp_easy_form_builder_upgrade", 'cp_easyform_html_post_page' );
    }
} else { // if not admin
    add_shortcode( 'CP_EASY_FORM_WILL_APPEAR_HERE', 'cp_easyform_filter_content' );    
}

function cp_easyform_settingsLink($links) {
    $settings_link = '<a href="options-general.php?page=cp_easy_form_builder">'.__('Settings').'</a>';
	array_unshift($links, $settings_link);
	return $links;
}

function cp_easyform_helpLink($links) {
    $help_link = '<a href="https://wordpress.dwbooster.com/forms/cp-easy-form-builder">'.__('Documentation').'</a>';
	array_unshift($links, $help_link);
	return $links;
}

function cp_easyform_customAdjustmentsLink($links) {
    $customAdjustments_link = '<a href="https://wordpress.org/support/plugin/cp-easy-form-builder/#new-post">'.__('Support').'</a>';
	array_unshift($links, $customAdjustments_link);
	return $links;
}

function cp_easyform_html_post_page() {
    if (isset($_GET["edit"]) && $_GET["edit"] == '1')
        @include_once dirname( __FILE__ ) . '/cp_admin_int_edition.inc.php';
    else if (isset($_GET["cal"]) && $_GET["cal"] != '')
        @include_once dirname( __FILE__ ) . '/cp_easyform_admin_int.php';
    else if ($_GET["page"] == 'cp_easy_form_builder_upgrade')
    {
            echo("Redirecting to upgrade page...<script type='text/javascript'>document.location='https://wordpress.dwbooster.com/forms/cp-easy-form-builder#download';</script>");
            exit;
    }
    else 
        @include_once dirname( __FILE__ ) . '/cp_easyform_admin_int_list.inc.php';        
}

function set_cp_easyform_insert_button() {
    print '<a href="javascript:cp_easyform_insertForm();" title="'.__('Insert CP Easy Form').'"><img hspace="5" src="'.plugins_url('/images/cp_form.gif', __FILE__).'" alt="'.__('Insert CP Easy Form').'" /></a>';
}

function set_cp_easyform_insert_adminScripts($hook) {
    if (isset($_GET["page"]) && $_GET["page"] == 'cp_easy_form_builder')
    {

        wp_enqueue_style('cp-easyform-adminlstyle', plugins_url('css/style.css', __FILE__));     
        wp_enqueue_style('cp-easyform-adminlstyledesign', plugins_url('css/cupertino/jquery-ui-1.8.20.custom.css', __FILE__)); 
    
        wp_deregister_script('query-stringify');
        wp_register_script('query-stringify', plugins_url('/js/jQuery.stringify.js', __FILE__));
        wp_enqueue_script( 'cp_easyform_buikder_script', plugins_url('/js/fbuilderf.jquery.js', __FILE__),array("jquery","jquery-ui-core","jquery-ui-sortable","jquery-ui-tabs","jquery-ui-droppable","jquery-ui-button","query-stringify") );
    }
    if( 'post.php' != $hook  && 'post-new.php' != $hook )
        return;
    wp_enqueue_script( 'cp_easyform_script', plugins_url('/cp_easyform_scripts.js', __FILE__) );
}



function cp_easyform_get_site_url($admin = false)
{
    $blog = get_current_blog_id();
    if( $admin ) 
        $url = get_admin_url( $blog );	
    else 
        $url = get_home_url( $blog );	

    $url = parse_url($url);
    $url = rtrim(@$url["path"],"/");
    if ($url == '')
        $url = 'http://'.sanitize_text_field($_SERVER["HTTP_HOST"]);
    return $url;
}

function cp_easyform_cleanJSON($str)
{
    $str = str_replace('&qquot;','"',$str);
    $str = str_replace('	',' ',$str);
    $str = str_replace("\n",'\n',$str);
    $str = str_replace("\r",'',$str);      
    return $str;
}
/* hook for checking posted data for the admin area */

add_action( 'init', 'cp_easy_form_check_posted_data', 11 );

//START: activation redirection 
function cp_easyform_activation_redirect( $plugin ) {
    if(
        $plugin == plugin_basename( __FILE__ ) &&
        (!isset($_POST["action"]) || $_POST["action"] != 'activate-selected') &&
        (!isset($_POST["action2"]) || $_POST["action2"] != 'activate-selected') 
      )
    {
        exit( wp_redirect( admin_url( 'admin.php?page=cp_easy_form_builder' ) ) );
    }
}
add_action( 'activated_plugin', 'cp_easyform_activation_redirect' );
//END: activation redirection 

function cp_easy_form_check_posted_data() {

    global $wpdb;

    $ao_options = get_option('autoptimize_js_exclude',"seal.js, js/jquery/jquery.js");
    if (!strpos($ao_options,'stringify.js'))
       update_option('autoptimize_js_exclude',"jQuery.stringify.js,jquery.validate.js,".$ao_options);
    
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['cp_easyform_post_options'] ) && current_user_can('edit_pages') && is_admin() )
    {
        if (wp_verify_nonce( sanitize_text_field($_REQUEST['_wpnonce']), 'uname_cpefb' )) 
            cp_easyform_save_options();
        return;
    }     
        
    if (isset( $_GET['cp_easyformcaptcha'] ) && $_GET['cp_easyformcaptcha'] == 'captcha' )
    {
        @include_once dirname( __FILE__ ) . '/captcha/captcha.php';            
        exit;        
    }
    
    if ( 'POST' != $_SERVER['REQUEST_METHOD'] || ! isset( $_POST['cp_easyform_pform_process'] ) )
         if ( 'GET' != $_SERVER['REQUEST_METHOD'] || !isset( $_GET['cp_easyform_pform_process'] ) )
    	    return;
    
    
    if (function_exists('session_start')) @session_start(); 
    if (isset($_GET["ps"])) $sequence = sanitize_key($_GET["ps"]); else if (isset($_POST["cp_pform_psequence"])) $sequence = sanitize_text_field($_POST["cp_pform_psequence"]);
    if ( isset( $_GET['cp_easyform_pform_process'] ) && $_GET['cp_easyform_pform_process'] == "2")
    {
        if (md5(strtolower($_GET['hdcaptcha'])) != (empty($_SESSION['rand_code'.$sequence])?$_COOKIE['rand_code'.$sequence]:$_SESSION['rand_code'.$sequence]))
        {
            echo 'captchafailed';
            exit;
        }
        else
        {
            echo 'OK';
            exit;
        }
    }    

	if ( 'POST' != $_SERVER['REQUEST_METHOD'] || ! isset( $_POST['cp_easyform_pform_process'] ) )
		return;

    define("CP_EASYFORM_ID", intval($_POST["cp_easyform_id"]) );    
    if ( 
          (cp_easyform_get_option('cv_enable_captcha', CP_EASYFORM_DEFAULT_cv_enable_captcha) != 'false') 
          && (md5(strtolower($_POST['hdcaptcha'])) != (empty($_SESSION['rand_code'.$sequence])?$_COOKIE['rand_code'.$sequence]:$_SESSION['rand_code'.$sequence]))
       )
    {
        echo 'captchafailed'; 
        exit;
    }
    $_SESSION['rand_code'.$sequence] = '';


    // get form info
    //---------------------------
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    $form_data = json_decode(cp_easyform_cleanJSON(cp_easyform_get_option('form_structure', CP_EASYFORM_DEFAULT_form_structure)));
    $fields = array();
    foreach ($form_data[0] as $item)
    {
        $fields[$item->name] = $item->title;
        if ($item->ftype == 'fPhone' || $item->ftype == 'fcheck' || $item->ftype == 'fcheck' || $item->ftype == 'fdropdown' || $item->ftype == 'fdate') // join fields for phone fields                       
        {
            echo "Phone, radio, checkboxes, date and dropdown fields aren't supported in this version. Please check at <a href=\"https://wordpress.dwbooster.com/forms/cp-easy-form-builder\">https://wordpress.dwbooster.com/forms/cp-easy-form-builder</a>";exit;
        }     
    } 

    // grab posted data
    //---------------------------

    $buffer = "";
    foreach ($_POST as $item => $value)
        if (isset($fields[str_replace($sequence,'',$item)]))
            $buffer .= $fields[str_replace($sequence,'',$item)] . ": ". (is_array($value)?sanitize_textarea_field(implode(", ",$value)):sanitize_textarea_field($value)) . "\n\n";

    $attachments = array();     
    foreach ($_FILES as $item => $value)  
        if (isset($fields[$item]))
        {           
            echo "File uploads aren't supported in this version. Please check at <a href=\"https://wordpress.dwbooster.com/forms/cp-easy-form-builder\">https://wordpress.dwbooster.com/forms/cp-easy-form-builder</a>";exit;
        }   
                    
    $buffer_A = $buffer;
    if ('true' == cp_easyform_get_option('fp_inc_additional_info', CP_EASYFORM_DEFAULT_fp_inc_additional_info))
    {
        $buffer .="ADDITIONAL INFORMATION\n"
              ."*********************************\n"
              ."IP: ".sanitize_text_field($_SERVER['REMOTE_ADDR'])."\n"
              ."Referer: ".sanitize_text_field($_SERVER["HTTP_REFERER"])."\n"
              ."Server Time:  ".date("Y-m-d H:i:s")."\n"
              ."User Agent: ".sanitize_text_field($_SERVER['HTTP_USER_AGENT'])."\n";

    }

    // 1- Send email
    //---------------------------
    $message = str_replace('<'.'%INFO%'.'>',$buffer,cp_easyform_get_option('fp_message', CP_EASYFORM_DEFAULT_fp_message));
    $subject = cp_easyform_get_option('fp_subject', @CP_EASYFORM_DEFAULT_fp_subject);

    if (!defined("CP_EASYFORM_DEFAULT_fp_from_email"))  define('CP_EASYFORM_DEFAULT_fp_from_email', get_the_author_meta('user_email', get_current_user_id()) );

    $from = cp_easyform_get_option('fp_from_email', @CP_EASYFORM_DEFAULT_fp_from_email);    
    $to = explode(",",cp_easyform_get_option('fp_destination_emails', (defined('CP_EASYFORM_DEFAULT_fp_destination_emails')?CP_EASYFORM_DEFAULT_fp_destination_emails:'')));

    if (!strpos($from,">"))
        $from = '"'.$from.'" <'.$from.'>'; 
            
    foreach ($to as $item)
        if (trim($item) != '')
        {
            wp_mail(trim($item), $subject, $message,
                "From: ".$from."\r\n".
                "Content-Type: text/plain; charset=utf-8\n".
                "X-Mailer: PHP/" . phpversion(), $attachments);
        }  

    header('Location:'.cp_easyform_get_option('fp_return_page', CP_EASYFORM_DEFAULT_fp_return_page));
    exit;
}





function cp_easyform_save_options() 
{
    global $wpdb;
    if (!defined('CP_EASYFORM_ID'))
        define ('CP_EASYFORM_ID', intval($_POST["cp_easyform_id"]) );     
        
    $my_POST = $_POST;    
    if ((substr_count($_POST['form_structure_control'],"\\") > 1) || substr_count($_POST['form_structure_control'],"\\\"title\\\":"))
        $my_POST = stripslashes_deep($my_POST);
        
    $data = array(
                  'form_structure' => cp_easyform_clean($my_POST['form_structure']),

                  'fp_from_email' => sanitize_text_field($my_POST['fp_from_email']),
                  'fp_destination_emails' => sanitize_text_field($my_POST['fp_destination_emails']),
                  'fp_subject' => cp_easyform_clean($my_POST['fp_subject']),
                  'fp_inc_additional_info' => sanitize_text_field($my_POST['fp_inc_additional_info']),
                  'fp_return_page' => sanitize_text_field($my_POST['fp_return_page']),
                  'fp_message' => cp_easyform_clean($my_POST['fp_message']),

                  'cu_enable_copy_to_user' => sanitize_text_field($my_POST['cu_enable_copy_to_user']),
                  'cu_user_email_field' => sanitize_text_field(@$my_POST['cu_user_email_field']),
                  'cu_subject' => cp_easyform_clean($my_POST['cu_subject']),
                  'cu_message' => cp_easyform_clean($my_POST['cu_message']),

                  'vs_text_is_required' => sanitize_text_field($my_POST['vs_text_is_required']),
                  'vs_text_is_email' => sanitize_text_field($my_POST['vs_text_is_email']),
                  'vs_text_datemmddyyyy' => sanitize_text_field($my_POST['vs_text_datemmddyyyy']),
                  'vs_text_dateddmmyyyy' => sanitize_text_field($my_POST['vs_text_dateddmmyyyy']),
                  'vs_text_number' => sanitize_text_field($my_POST['vs_text_number']),
                  'vs_text_digits' => sanitize_text_field($my_POST['vs_text_digits']),
                  'vs_text_max' => sanitize_text_field($my_POST['vs_text_max']),
                  'vs_text_min' => sanitize_text_field($my_POST['vs_text_min']),
                  'vs_text_submitbtn' => sanitize_text_field($my_POST['vs_text_submitbtn']),
                  'vs_text_previousbtn' => sanitize_text_field($my_POST['vs_text_previousbtn']),
                  'vs_text_nextbtn' => sanitize_text_field($my_POST['vs_text_nextbtn']),

                  'cv_enable_captcha' => sanitize_text_field($my_POST['cv_enable_captcha']),
                  'cv_width' => sanitize_text_field($my_POST['cv_width']),
                  'cv_height' => sanitize_text_field($my_POST['cv_height']),
                  'cv_chars' => sanitize_text_field($my_POST['cv_chars']),
                  'cv_font' => sanitize_text_field($my_POST['cv_font']),
                  'cv_min_font_size' => sanitize_text_field($my_POST['cv_min_font_size']),
                  'cv_max_font_size' => sanitize_text_field($my_POST['cv_max_font_size']),
                  'cv_noise' => sanitize_text_field($my_POST['cv_noise']),
                  'cv_noise_length' => sanitize_text_field($my_POST['cv_noise_length']),
                  'cv_background' => str_replace('#','',sanitize_text_field($my_POST['cv_background'])),
                  'cv_border' => str_replace('#','',sanitize_text_field($my_POST['cv_border'])),
                  'cv_text_enter_valid_captcha' => sanitize_text_field($my_POST['cv_text_enter_valid_captcha'])
	);
    $wpdb->update ( $wpdb->prefix.CP_EASYFORM_FORMS_TABLE, $data, array( 'id' => CP_EASYFORM_ID ));    
}


function cp_easyform_clean ($str)
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


function cp_easyform_add_field_verify ($table, $field, $type = "text") 
{
    global $wpdb;
    $results = $wpdb->get_results("SHOW columns FROM `".$table."` where field='".$field."'");    
    if (!count($results))
    {               
        $sql = "ALTER TABLE  `".$table."` ADD `".$field."` ".$type; 
        $wpdb->query($sql);
    }
}

// cp_easyform_get_option:
$cp_easyform_option_buffered_item = false;
$cp_easyform_option_buffered_id = -1;

function cp_easyform_get_option ($field, $default_value, $id = '1')
{
    if (!defined("CP_EASYFORM_ID"))
    {
        if (!defined("CP_EASYFORM_ID"))
            define ("CP_EASYFORM_ID", 1);
    }    
    if ($id == '') 
        $id = CP_EASYFORM_ID;
    global $wpdb, $cp_easyform_option_buffered_item, $cp_easyform_option_buffered_id;
    if ($cp_easyform_option_buffered_id == $id)
        $value = @$cp_easyform_option_buffered_item->$field;
    else
    {
       $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_EASYFORM_FORMS_TABLE." WHERE id=".$id );
       if (count($myrows)) 
       {
           $value = @$myrows[0]->$field;       
           $cp_easyform_option_buffered_item = $myrows[0];
           $cp_easyform_option_buffered_id  = $id;
       }
       else  
           $value = $default_value; 
    }
    return $value;
}


class CP_EasyForm_Widget extends WP_Widget
{
  function __construct()
  {
    $widget_ops = array('classname' => 'CP_EasyForm_Widget', 'description' => 'Displays a form' );
    parent::__construct('CP_EasyForm_Widget', 'CP Easy Form Builder', $widget_ops);
  }

  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
    ?><p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Title: <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p><?php
  }

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }

  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);

    echo wp_kses_data($before_widget);
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

    if (!empty($title))
      echo wp_kses_data($before_title . $title . $after_title);

    // WIDGET CODE GOES HERE
    define('CP_AUTH_INCLUDE', true);
    cp_easyform_get_public_form();

    echo wp_kses_data($after_widget);
  }

}

add_action( 'widgets_init', function () { return register_widget("CP_EasyForm_Widget"); } );


// register gutemberg block
if (function_exists('register_block_type'))
{ 
    register_block_type('cpefbuilder/form-rendering', array(
                        'attributes'      => array(
                                'formId'    => array(
                                    'type'      => 'string'
                                ),
                                'instanceId'    => array(
                                    'type'      => 'string'
                                ),
                            ),
                        'render_callback' => 'cpeasyformbuilder_render_form_admin'
                    )); 
}

// optional opt-in deactivation feedback
require_once 'cp-feedback.php';

// code for compatibility with third party scripts
add_filter('litespeed_cache_optimize_js_excludes', 'cpeasyformbuilder_litespeed_cache_optimize_js_excludes' );
function cpeasyformbuilder_litespeed_cache_optimize_js_excludes($options)
{
    return  "jquery.validate.min.js\njQuery.stringify.js\njquery.validate.js\njquery.js\n".$options;
}

// code for compatibility with third party scripts
add_filter('option_sbp_settings', 'cpeasyformbuilder_sbp_fix_conflict' );
function cpeasyformbuilder_sbp_fix_conflict($option)
{
    if(!is_admin())
    {
       if(is_array($option) && isset($option['jquery_to_footer'])) 
           unset($option['jquery_to_footer']);
    }
    return $option;
}

?>