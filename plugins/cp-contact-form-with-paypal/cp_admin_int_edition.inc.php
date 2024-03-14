<?php

if ( !defined('CP_CONTACTFORMPP_AUTH_INCLUDE') ) { echo 'Direct access not allowed.';  exit; }

if ( !is_admin() ) 
{
    echo 'Direct access not allowed.';
    exit;
}

global $wpdb;

$nonce = wp_create_nonce( 'cfwpp_update_actions_post' );

$cpid = 'CP_CFWPP';
$plugslug = 'cp_contact_form_paypal.php';

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST[$cpid.'_post_edition'] ) )
    echo "<div id='setting-error-settings_updated' class='updated settings-error'> <p><strong>".esc_html(__('Settings saved.','cp-contact-form-with-paypal'))."</strong></p></div>";

if ($_GET["item"] == 'js')
    $saved_contents = base64_decode(get_option($cpid.'_JS', ''));
else if ($_GET["item"] == 'css')
    $saved_contents = base64_decode(get_option($cpid.'_CSS', ''));

?>
<script>
// Move to an external file
jQuery(function(){
	var $ = jQuery;
    <?php 
            if(function_exists('wp_enqueue_code_editor'))
			{
				$settings_js = wp_enqueue_code_editor(array('type' => 'application/javascript'));
				$settings_css = wp_enqueue_code_editor(array('type' => 'text/css'));

				// Bail if user disabled CodeMirror.
				if(!(false === $settings_js && false === $settings_css))
				{
                    if ($_GET["item"] == 'js')
                        print sprintf('{wp.codeEditor.initialize( "editionarea", %s );}',wp_json_encode( $settings_js ));
                    else
					    print sprintf('{wp.codeEditor.initialize( "editionarea", %s );}',wp_json_encode( $settings_css ));
				}
			}      
              
    ?>    
});
</script>
<style>
.ahb-tab{display:none;}
.ahb-tab label{font-weight:600;}
.tab-active{display:block;}
.ahb-code-editor-container{border:1px solid #DDDDDD;margin-bottom:20px;}
  
.ahb-csssample { margin-top: 15px; margin-left:20px;  margin-right:20px;}
.ahb-csssampleheader { 
  font-weight: bold; 
  background: #dddddd;
	padding:10px 20px;-webkit-box-shadow: 0px 2px 2px 0px rgba(100, 100, 100, 0.1);-moz-box-shadow:    0px 2px 2px 0px rgba(100, 100, 100, 0.1);box-shadow:         0px 2px 2px 0px rgba(100, 100, 100, 0.1);
  text-align:left;
}
.ahb-csssamplecode {     background: #f4f4f4;
    border: 1px solid #ddd;
    border-left: 3px solid #f36d33;
    color: #666;
    page-break-inside: avoid;
    font-family: monospace;
    font-size: 15px;
    line-height: 1.6;
    margin-bottom: 1.6em;
    max-width: 100%;
    overflow: auto;
    padding: 1em 1.5em;
    display: block;
    word-wrap: break-word; 
    text-align:left;
}   
</style>
<div class="wrap">
<h1><?php _e('Customization','cp-contact-form-with-paypal'); ?> / <?php _e('Edit Page','cp-contact-form-with-paypal'); ?></h1>  



<input type="button" name="backbtn" value="<?php _e('Back to items list','cp-contact-form-with-paypal'); ?>..." onclick="document.location='admin.php?page=<?php echo esc_attr($plugslug); ?>';">
<br /><br />

<form method="post" action="" name="cpformconf"> 
<input name="rsave" type="hidden" value="<?php echo esc_attr($nonce); ?>" />
<input name="<?php echo esc_attr($cpid); ?>_post_edition" type="hidden" value="1" />
<input name="cfwpp_edit" type="hidden" value="<?php echo esc_attr($_GET["item"]); ?>" />
   
<div id="normal-sortables" class="meta-box-sortables">

Note: This section has been modified to improve security. Please edit the custom CSS in the theme. You can <a href="https://cfpaypal.dwbooster.com/contact-us">contact us for support and assistance</a>.
  
</div> 

</form>

<?php if ($_GET["item"] == 'css') { ?>
<hr />
   
   <div class="ahb-statssection-container" style="background:#f6f6f6;">
	<div class="ahb-statssection-header" style="background:white;
	padding:10px 20px;-webkit-box-shadow: 0px 2px 2px 0px rgba(100, 100, 100, 0.1);-moz-box-shadow:    0px 2px 2px 0px rgba(100, 100, 100, 0.1);box-shadow:         0px 2px 2px 0px rgba(100, 100, 100, 0.1);">
    <h3><?php _e('Sample Styles','cp-contact-form-with-paypal'); ?>:</h3>
	</div>
	<div class="ahb-statssection">
      
        <div class="ahb-csssample">
         <div class="ahb-csssampleheader">
           <?php _e('Make the send button in a hover format','cp-contact-form-with-paypal'); ?>:
         </div>
         <div class="ahb-csssamplecode">
           .pbSubmit:hover {
               background-color: #4CAF50;
               color: white;
           }         
         </div>
        </div> 
        
        <div class="ahb-csssample">
         <div class="ahb-csssampleheader">
           <?php _e('Change the color of all form field labels','cp-contact-form-with-paypal'); ?>:
         </div>
         <div class="ahb-csssamplecode">
           #fbuilder, #fbuilder label, #fbuilder span { color: #00f; }     
         </div>
        </div> 

        <div class="ahb-csssample">
         <div class="ahb-csssampleheader">
           <?php _e('Change color of fonts into all fields','cp-contact-form-with-paypal'); ?>:
         </div>
         <div class="ahb-csssamplecode">
           #fbuilder input[type=text], 
           #fbuilder textarea, 
           #fbuilder select { 
             color: #00f; 
           }     
         </div>
        </div> 
        
        <div class="ahb-csssample">
         <div class="ahb-csssampleheader">
            <?php _e('Replace submit button text to icon/image','cp-contact-form-with-paypal'); ?>:
         </div>
         <div class="ahb-csssamplecode">
           .pbSubmit{<br />
            &nbsp; &nbsp;  background-image:url(<span style="color:#0000bb">https://cfpaypal.dwbooster.com/images/logo.png</span>);<br />
            &nbsp; &nbsp;     background-size: cover;<br />
            &nbsp; &nbsp;    color:transparent;<br />
            &nbsp; &nbsp;     width: <span style="color:#0000bb">194px</span>;<br />
            &nbsp; &nbsp;    height: <span style="color:#0000bb">35px</span>;<br />
           }     
         </div>
        </div>         
        
        <div class="ahb-csssample">
         <div class="ahb-csssampleheader">
           <?php _e('Other styles','cp-contact-form-with-paypal'); ?>:
         </div>
         <div class="ahb-csssamplecode">
           <?php _e('For other styles check the design section in the FAQ','cp-contact-form-with-paypal'); ?>: <a href="https://cfpaypal.dwbooster.com/faq?page=faq#design">https://cfpaypal.dwbooster.com/faq?page=faq#design</a>     
         </div>
        </div>         
       
    </div>
   </div>
   
<?php } ?>


</div>













