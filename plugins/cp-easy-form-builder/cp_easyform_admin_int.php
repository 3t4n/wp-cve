<?php

if ( !is_admin() ) 
{
    echo 'Direct access not allowed.';
    exit;
}

$nonce = wp_create_nonce( 'uname_cpefb' );

if (!defined('CP_EASYFORM_ID'))
    define ('CP_EASYFORM_ID',intval($_GET["cal"]));
    

define('CP_EASYFORM_DEFAULT_fp_from_email', get_the_author_meta('user_email', get_current_user_id()) );
define('CP_EASYFORM_DEFAULT_fp_destination_emails', CP_EASYFORM_DEFAULT_fp_from_email);

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['cp_easyform_post_options'] ) )
    echo "<div id='setting-error-settings_updated' class='updated settings-error'> <p><strong>Settings saved.</strong></p></div>";

?>
<div class="wrap">
<h1>CP Easy Form Builder</h1>

<input type="button" name="backbtn" value="Back to items list..." onclick="document.location='admin.php?page=cp_easy_form_builder';">
<br /><br />
        
<script type="text/javascript">        
  $easyFormQuery = jQuery.noConflict();  
</script> 
<form method="post" action="" name="cpformconf"> 
<input name="_wpnonce" type="hidden" value="<?php echo esc_attr($nonce); ?>" />
<input name="cp_easyform_post_options" type="hidden" value="1" />
<input name="cp_easyform_id" type="hidden" value="<?php echo intval(CP_EASYFORM_ID); ?>" />

   
<div id="normal-sortables" class="meta-box-sortables">


 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Form Builder</span></h3>
  <div class="inside">
   
     <input type="hidden" name="form_structure_control" id="form_structure_control" value="&quot;&quot;&quot;&quot;&quot;&quot;" />
     <input type="hidden" name="form_structure" id="form_structure" size="180" value="<?php echo str_replace('"','&quot;',str_replace("\r","",str_replace("\n","",esc_attr(cp_easyform_cleanJSON(cp_easyform_get_option('form_structure', CP_EASYFORM_DEFAULT_form_structure)))))); ?>" />
             
     <script>     
         
         $easyFormQuery(document).ready(function() {
            var f = $easyFormQuery("#fbuilder").fbuilder();
            f.fBuild.loadData("form_structure");
            
            $easyFormQuery("#saveForm").click(function() {       
                f.fBuild.saveData("form_structure");
            });  
                 
            $easyFormQuery(".itemForm").click(function() {
     	       f.fBuild.addItem($easyFormQuery(this).attr("id"));
     	   });  
          
           $easyFormQuery( ".itemForm" ).draggable({revert1: "invalid",helper: "clone",cursor: "move"});
     	   $easyFormQuery( "#fbuilder" ).droppable({
     	       accept: ".button",
     	       drop: function( event, ui ) {
     	           f.fBuild.addItem(ui.draggable.attr("id"));				
     	       }
     	   });
     		    
         });
        
        
        
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
           qs += "&rand="+d;
           
           document.getElementById("captchaimg").src= "<?php echo esc_attr(cp_easyform_get_site_url()).'/?cp_easyformcaptcha=captcha' ?>"+qs;
        }

     </script>
     
     <div style="background:#fafafa;width:780px;" class="form-builder">
     
         <div class="column width50">
             <div id="tabs">
     			<ul>
     				<li><a href="#tabs-1">Add a Field</a></li>
     				<li><a href="#tabs-2">Field Settings</a></li>
     				<li><a href="#tabs-3">Form Settings</a></li>
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
             <div class="button" id="saveForm">Save Form</div>
         </div>
         <div class="clearer"></div>
         
     </div>          
  <div style="border:1px dotted black;background-color:#ffffbb;padding-left:15px;padding-right:15px;padding-top:5px;width:650px;font-size:12px;color:#000000;"> 
   <p>The form builder supports 3 fields in this free version: "Single Line Text", "Email" and "Text-area".</p>
   <p>The full set of fields is available in the <a href="https://wordpress.dwbooster.com/forms/cp-easy-form-builder#download">CP Easy Form Builder - pro version</a>. It also supports:
   <ul>
    <li>&nbsp; - Dependand fields: Hide/show fields based in previous selections.</li>
    <li>&nbsp; - File uploads</li>
    <li>&nbsp; - Multi-page forms</li>
    <li>&nbsp; - Multiple forms per website</li>
    <li>&nbsp; - Supports tags for specific form fields into the email and email copy to the user</li>    
    <li>&nbsp; - ...and more fields and validations</li>
   </ul>
   <p>Note: If you already acquired the PRO version you don't need to acquire it again, in that case just use your personal download link to get the latest update.</p>
      
   <p>There are also other plugins with similar features plus adding:<br />
    &nbsp; - <strong>CP Contact Form with PayPal</strong>: Contact forms <a href="https://wordpress.dwbooster.com/forms/cp-contact-form-with-paypal">connected to PayPal</a> <br />
    &nbsp; - <strong>Calculated Fields Form</strong>: Contact forms with <a href="https://wordpress.dwbooster.com/forms/calculated-fields-form">calculated fields</a> <br />
    &nbsp; - <strong>Contact Form to Email</strong>: Contact forms with <a href="https://wordpress.dwbooster.com/forms/contact-form-to-email">reports of usage and export of data to Excel</a>.</p>

   
  </div>
      
   
  </div>    
 </div> 


 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Submit Button</span></h3>
  <div class="inside">   
     <table class="form-table">    
        <tr valign="top">
        <th scope="row">Submit button label (text):</th>
        <td><input type="text" name="vs_text_submitbtn" size="40" value="<?php $label = esc_attr(cp_easyform_get_option('vs_text_submitbtn', 'Submit')); echo esc_attr($label==''?'Submit':$label); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Previous button label (text):</th>
        <td><input type="text" name="vs_text_previousbtn" size="40" value="<?php $label = esc_attr(cp_easyform_get_option('vs_text_previousbtn', 'Previous')); echo esc_attr($label==''?'Previous':$label); ?>" /></td>
        </tr>    
        <tr valign="top">
        <th scope="row">Next button label (text):</th>
        <td><input type="text" name="vs_text_nextbtn" size="40" value="<?php $label = esc_attr(cp_easyform_get_option('vs_text_nextbtn', 'Next')); echo esc_attr($label==''?'Next':$label); ?>" /></td>
        </tr>  
        <tr valign="top">
        <td colspan="2"> - The  <em>class="pbSubmit"</em> can be used to modify the button styles. <br />
        - The styles can be applied into any of the CSS files of your theme or into the CSS file <em>"cp-easy-form-builder\css\stylepublic.css"</em>. <br />
        - For further modifications the submit button is located at the end of the file <em>"cp_easyform_public_int.inc.php"</em>.<br />
        - For general CSS styles modifications to the form and samples <a href="https://wordpress.dwbooster.com/faq/cp-easy-form-builder#q99" target="_blank">check this FAQ</a>.
        </tr>
     </table>
  </div>    
 </div> 
 
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Form Processing / Email Settings</span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top">
        <th scope="row">"From" email</th>
        <td><input required type="email" name="fp_from_email" size="40" value="<?php echo esc_attr(cp_easyform_get_option('fp_from_email', CP_EASYFORM_DEFAULT_fp_from_email)); ?>" /></td>
        </tr>             
        <tr valign="top">
        <th scope="row">Destination emails (comma separated)</th>
        <td><input type="text" name="fp_destination_emails" size="40" value="<?php echo esc_attr(cp_easyform_get_option('fp_destination_emails', CP_EASYFORM_DEFAULT_fp_destination_emails)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Email subject</th>
        <td><input type="text" name="fp_subject" size="70" value="<?php echo esc_attr(cp_easyform_get_option('fp_subject', CP_EASYFORM_DEFAULT_fp_subject)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Include additional information?</th>
        <td>
          <?php $option = cp_easyform_get_option('fp_inc_additional_info', CP_EASYFORM_DEFAULT_fp_inc_additional_info); ?>
          <select name="fp_inc_additional_info">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>>Yes</option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>>No</option>
          </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Thank you page (after sending the message)</th>
        <td><input type="text" name="fp_return_page" size="70" value="<?php echo esc_attr(cp_easyform_get_option('fp_return_page', CP_EASYFORM_DEFAULT_fp_return_page)); ?>" /></td>
        </tr>  
        <tr valign="top">
        <th scope="row">Message</th>
        <td><textarea type="text" name="fp_message" rows="6" cols="80"><?php echo esc_attr(cp_easyform_get_option('fp_message', CP_EASYFORM_DEFAULT_fp_message)); ?></textarea></td>
        </tr>                                                               
     </table>  
  </div>    
 </div>  
 
 
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Email Copy to User</span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top">
        <th scope="row">Send confirmation/thank you message to user?</th>
        <td>
          <?php $option = cp_easyform_get_option('cu_enable_copy_to_user', CP_EASYFORM_DEFAULT_cu_enable_copy_to_user); ?>
          <select name="cu_enable_copy_to_user">
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>>No</option>
          </select>
          *<em>This version doesn't support the copy to user. <a href="https://wordpress.dwbooster.com/forms/cp-easy-form-builder">Click for other versions</a>.</em>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Email field on the form</th>
        <td><select id="cu_user_email_field" name="cu_user_email_field" def="<?php echo esc_attr(cp_easyform_get_option('cu_user_email_field', CP_EASYFORM_DEFAULT_cu_user_email_field)); ?>"></select></td>
        </tr>             
        <tr valign="top">
        <th scope="row">Email subject</th>
        <td><input type="text" name="cu_subject" size="70" value="<?php echo esc_attr(cp_easyform_get_option('cu_subject', CP_EASYFORM_DEFAULT_cu_subject)); ?>" /></td>
        </tr>                 
        <tr valign="top">
        <th scope="row">Message</th>
        <td><textarea type="text" name="cu_message" rows="6" cols="80"><?php echo esc_attr(cp_easyform_get_option('cu_message', CP_EASYFORM_DEFAULT_cu_message)); ?></textarea></td>
        </tr>        
     </table>  
  </div>    
 </div>   
 

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Validation Settings</span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top">
        <th scope="row">Use Validation?</th>
        <td>
          <?php $option = cp_easyform_get_option('vs_use_validation', CP_EASYFORM_DEFAULT_vs_use_validation); ?>
          <select name="vs_use_validation">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>>Yes</option>
           <!--<option value="false"<?php if ($option == 'false') echo ' selected'; ?>>No</option>-->
          </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">"is required" text:</th>
        <td><input type="text" name="vs_text_is_required" size="40" value="<?php echo esc_attr(cp_easyform_get_option('vs_text_is_required', CP_EASYFORM_DEFAULT_vs_text_is_required)); ?>" /></td>
        </tr>             
         <tr valign="top">
        <th scope="row">"is email" text:</th>
        <td><input type="text" name="vs_text_is_email" size="70" value="<?php echo esc_attr(cp_easyform_get_option('vs_text_is_email', CP_EASYFORM_DEFAULT_vs_text_is_email)); ?>" /></td>
        </tr>       
        <tr valign="top">
        <th scope="row">"is valid captcha" text:</th>
        <td><input type="text" name="cv_text_enter_valid_captcha" size="70" value="<?php echo esc_attr(cp_easyform_get_option('cv_text_enter_valid_captcha', CP_EASYFORM_DEFAULT_cv_text_enter_valid_captcha)); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">"is valid date (mm/dd/yyyy)" text:</th>
        <td><input type="text" name="vs_text_datemmddyyyy" size="70" value="<?php echo esc_attr(cp_easyform_get_option('vs_text_datemmddyyyy', CP_EASYFORM_DEFAULT_vs_text_datemmddyyyy)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">"is valid date (dd/mm/yyyy)" text:</th>
        <td><input type="text" name="vs_text_dateddmmyyyy" size="70" value="<?php echo esc_attr(cp_easyform_get_option('vs_text_dateddmmyyyy', CP_EASYFORM_DEFAULT_vs_text_dateddmmyyyy)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">"is number" text:</th>
        <td><input type="text" name="vs_text_number" size="70" value="<?php echo esc_attr(cp_easyform_get_option('vs_text_number', CP_EASYFORM_DEFAULT_vs_text_number)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">"only digits" text:</th>
        <td><input type="text" name="vs_text_digits" size="70" value="<?php echo esc_attr(cp_easyform_get_option('vs_text_digits', CP_EASYFORM_DEFAULT_vs_text_digits)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">"under maximum" text:</th>
        <td><input type="text" name="vs_text_max" size="70" value="<?php echo esc_attr(cp_easyform_get_option('vs_text_max', CP_EASYFORM_DEFAULT_vs_text_max)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">"over minimum" text:</th>
        <td><input type="text" name="vs_text_min" size="70" value="<?php echo esc_attr(cp_easyform_get_option('vs_text_min', CP_EASYFORM_DEFAULT_vs_text_min)); ?>" /></td>
        </tr>             
        
     </table>  
  </div>    
 </div>   
 

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Captcha Verification</span></h3>
  <div class="inside">
     <table class="form-table">    
        <tr valign="top">
        <th scope="row">Use Captcha Verification?</th>
        <td colspan="5">
          <?php $option = cp_easyform_get_option('cv_enable_captcha', CP_EASYFORM_DEFAULT_cv_enable_captcha); ?>
          <select name="cv_enable_captcha">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>>Yes</option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>>No</option>
          </select>
        </td>
        </tr>
        
        <tr valign="top">
         <th scope="row">Width:</th>
         <td><input type="text" name="cv_width" size="10" value="<?php echo esc_attr(cp_easyform_get_option('cv_width', CP_EASYFORM_DEFAULT_cv_width)); ?>"  onblur="generateCaptcha();"  /></td>
         <th scope="row">Height:</th>
         <td><input type="text" name="cv_height" size="10" value="<?php echo esc_attr(cp_easyform_get_option('cv_height', CP_EASYFORM_DEFAULT_cv_height)); ?>" onblur="generateCaptcha();"  /></td>
         <th scope="row">Chars:</th>
         <td><input type="text" name="cv_chars" size="10" value="<?php echo esc_attr(cp_easyform_get_option('cv_chars', CP_EASYFORM_DEFAULT_cv_chars)); ?>" onblur="generateCaptcha();"  /></td>
        </tr>             

        <tr valign="top">
         <th scope="row">Min font size:</th>
         <td><input type="text" name="cv_min_font_size" size="10" value="<?php echo esc_attr(cp_easyform_get_option('cv_min_font_size', CP_EASYFORM_DEFAULT_cv_min_font_size)); ?>" onblur="generateCaptcha();"  /></td>
         <th scope="row">Max font size:</th>
         <td><input type="text" name="cv_max_font_size" size="10" value="<?php echo esc_attr(cp_easyform_get_option('cv_max_font_size', CP_EASYFORM_DEFAULT_cv_max_font_size)); ?>" onblur="generateCaptcha();"  /></td>        
         <td colspan="2" rowspan="">
           Preview:<br />
             <br />
            <img src="<?php echo esc_attr(cp_easyform_get_site_url()).'/?cp_easyformcaptcha=captcha'; ?>"  id="captchaimg" alt="security code" border="0"  />            
         </td> 
        </tr>             
                

        <tr valign="top">
         <th scope="row">Noise:</th>
         <td><input type="text" name="cv_noise" size="10" value="<?php echo esc_attr(cp_easyform_get_option('cv_noise', CP_EASYFORM_DEFAULT_cv_noise)); ?>" onblur="generateCaptcha();" /></td>
         <th scope="row">Noise Length:</th>
         <td><input type="text" name="cv_noise_length" size="10" value="<?php echo esc_attr(cp_easyform_get_option('cv_noise_length', CP_EASYFORM_DEFAULT_cv_noise_length)); ?>" onblur="generateCaptcha();" /></td>        
        </tr>          
        

        <tr valign="top">
         <th scope="row">Background:</th>
         <td><input type="color" name="cv_background" size="10" value="#<?php echo esc_attr(cp_easyform_get_option('cv_background', CP_EASYFORM_DEFAULT_cv_background)); ?>" onchange="generateCaptcha();" /></td>
         <th scope="row">Border:</th>
         <td><input type="color" name="cv_border" size="10" value="#<?php echo esc_attr(cp_easyform_get_option('cv_border', CP_EASYFORM_DEFAULT_cv_border)); ?>" onchange="generateCaptcha();" /></td>        
        </tr>    
        
        <tr valign="top">
         <th scope="row">Font:</th>
         <td>
            <select name="cv_font" onchange="generateCaptcha();" >
              <option value="font-1.ttf"<?php if ("font-1.ttf" == cp_easyform_get_option('cv_font', CP_EASYFORM_DEFAULT_cv_font)) echo " selected"; ?>>Font 1</option>
              <option value="font-2.ttf"<?php if ("font-2.ttf" == cp_easyform_get_option('cv_font', CP_EASYFORM_DEFAULT_cv_font)) echo " selected"; ?>>Font 2</option>
              <option value="font-3.ttf"<?php if ("font-3.ttf" == cp_easyform_get_option('cv_font', CP_EASYFORM_DEFAULT_cv_font)) echo " selected"; ?>>Font 3</option>
              <option value="font-4.ttf"<?php if ("font-4.ttf" == cp_easyform_get_option('cv_font', CP_EASYFORM_DEFAULT_cv_font)) echo " selected"; ?>>Font 4</option>
            </select>            
         </td>              
        </tr>                          
           
        
     </table>  
  </div>    
 </div>    
 
 
<div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Note</span></h3>
  <div class="inside">
   To insert this form in a post/page, use the dedicated icon 
   <?php print '<a href="javascript:cp_easyform_insertForm();" title="'.__('Insert CP Easy Form Builder').'"><img hspace="5" src="'.plugins_url('/images/cp_form.gif', __FILE__).'" alt="'.__('Insert CP Easy Form Builder').'" /></a>';     ?>
   which has been added to your Upload/Insert Menu, just below the title of your Post/Page.
   <br /><br />
  </div>
</div>   
  
</div> 


<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes"  /></p>


[<a href="https://wordpress.dwbooster.com/support?product=cp-easy-form-builder&version=1.1.3" target="_blank">Request Custom Modifications</a>] | [<a href="https://wordpress.dwbooster.com/calendars/cp-easy-form-builder" target="_blank">Help</a>]
</form>
</div>
<script type="text/javascript">generateCaptcha();</script>













