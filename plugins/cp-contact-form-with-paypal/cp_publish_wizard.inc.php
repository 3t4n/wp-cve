<?php 
  
  if ( !defined('CP_CONTACTFORMPP_AUTH_INCLUDE') ) { echo 'Direct access not allowed.';  exit; }
  if ( !is_admin() || !current_user_can('manage_options') ) {echo 'Direct access not allowed.';exit;} 

  $nonce = wp_create_nonce( 'cfwpp_actions_pwizard' );
  
?>
<?php global $wpdb; ?>

<h1><?php _e('Publish','cp-contact-form-with-paypal'); ?> CP Contact Form with PayPal</h1>

<style type="text/css">

.ahb-buttons-container{margin:1em 1em 1em 0;}
.ahb-return-link{float:right;}
.ahb-mssg{margin-left:0 !important; }
.ahb-section-container {
	border: 1px solid #e6e6e6;
	padding:0px;
	border-radius: 3px;
	-webkit-box-flex: 1;
	flex: 1;
	margin: 1em 1em 1em 0;
	min-width: 200px;
	background: #ffffff;
	position:relative;
}
.ahb-section{padding:20px;display:none;}
.ahb-section label{font-weight:600;}
.ahb-section-active{display:block;}

.ahb-row{display:none;}
.ahb-section table td,
.ahb-section table th{padding-left:0;padding-right:0;}
.ahb-section select,
.ahb-section input[type="text"]{width:100%;}

.cpmvcontainer { font-size:16px !important; }
</style>

<div class="ahb-buttons-container">
	<a href="javascript:document.location='admin.php?page=cp_contact_form_paypal.php';" class="ahb-return-link">&larr;<?php _e('Return to the forms list','cp-contact-form-with-paypal'); ?></a>
	<div class="clear"></div>
</div>

<form method="post" action="?page=cp_contact_form_paypal.php&pwizard=1" name="regForm" id="regForm">          
 <input name="cp_contactformpp_do_action_loaded" type="hidden" value="wizard" />
 <input name="anonce" type="hidden" value="<?php echo esc_attr($nonce); ?>" />
 
<?php 

if (!empty($_POST['cp_contactformpp_do_action_loaded']) && $_POST['cp_contactformpp_do_action_loaded'] == 'wizard') {
    global $cp_contactformpp_postURL;
?>
<div class="ahb-section-container">
	<div class="ahb-section ahb-section-active" data-step="1">
        <h1><?php _e('Great! Form successfully published','cp-contact-form-with-paypal'); ?></h1>
        <p class="cpmvcontainer"><?php _e('The payment form was placed into the page','cp-contact-form-with-paypal'); ?> <a href="<?php echo esc_attr($cp_contactformpp_postURL); ?>"><?php echo esc_html($cp_contactformpp_postURL); ?></a>.</p>
        <p class="cpmvcontainer"><?php _e('Now you can','cp-contact-form-with-paypal'); ?>:</p>
        <div style="clear:both"></div>
        <button class="button button-primary cpmvcontainer" type="button" id="nextBtn" onclick="window.open('<?php echo esc_attr($cp_contactformpp_postURL); ?>');"><?php _e('View the Published Form','cp-contact-form-with-paypal'); ?></button>
        <div style="clear:both"></div>
        <p class="cpmvcontainer">* <?php _e('Note: If the form was published in a new page or post it will be a \'draft\', you have to publish the page/post in the future if needed.','cp-contact-form-with-paypal'); ?></p>
        <div style="clear:both"></div>
        <button class="button button-primary cpmvcontainer" type="button" id="nextBtn" onclick="window.open('?page=cp_contact_form_paypal.php&cal=<?php echo intval($_POST["cp_contactformpp_id"]); ?>');"><?php _e('Edit the payment form settings','cp-contact-form-with-paypal'); ?></button>
        <div style="clear:both"></div>
    </div>
</div>
<div style="clear:both"></div>
<?php
} else {     
?>

<div class="ahb-section-container">
	<div class="ahb-section ahb-section-active" data-step="1">
		<table class="form-table">
            <tbody>
				<tr valign="top">
					<th><label><?php _e('Select form','cp-contact-form-with-paypal'); ?></label></th>
					<td>
                    <select id="cp_contactformpp_id" name="cp_contactformpp_id" onchange="reloadappbk(this);">
<?php
  $myrows = $wpdb->get_results( "SELECT * FROM ". $wpdb->prefix."cp_contact_form_paypal_settings");
  foreach ($myrows as $item)            
      echo '<option value="'.$item->id.'"'.(!empty($_GET["cal"]) && $item->id==$_GET["cal"]?' selected':'').'>'.esc_html($item->form_name).'</option>';
?>                
            </select>
                    </td>    
                </tr>   
                <tr valign="top">
                    <th><label><?php _e('Where to publish it?','cp-contact-form-with-paypal'); ?></label></th>
					<td> 
                        <select name="whereto" onchange="mvpublish_displayoption(this);">
                          <option value="0"><?php _e('Into a new page','cp-contact-form-with-paypal'); ?></option>
                          <option value="1"><?php _e('Into a new post','cp-contact-form-with-paypal'); ?></option>
                          <option value="2"><?php _e('Into an existent page','cp-contact-form-with-paypal'); ?></option>
                          <option value="3"><?php _e('Into an existent post','cp-contact-form-with-paypal'); ?></option>
                          <option value="4" style="color:#bbbbbb"><?php _e('Widget in a sidebar, header or footer - upgrade required for this option -','cp-contact-form-with-paypal'); ?></option>
                        </select>                    
                    </td>    
                </tr> 
                <tr valign="top" id="posttitle">
                    <th><label><?php _e('Page/Post Title','cp-contact-form-with-paypal'); ?></label></th>
					<td> 
                        <input type="text" name="posttitle" value="Payment Form" />
                    </td>    
                </tr>                  
                <tr valign="top"  id="ppage" style="display:none">
                    <th valign="top"></th>
					<td valign="top">
                    
                       <h3 style="background:#cccccc; padding:5px;"><?php _e('Classic way? Just copy and paste the following shortcode into the page/post','cp-contact-form-with-paypal'); ?>:</h3>
                       
                       <div style="border: 1px dotted black; background-color: #FFFACD ;padding:15px; font-weight: bold; margin:10px;">
                         [CP_CONTACT_FORM_PAYPAL id="<?php echo (!empty($_GET["cal"]) && intval($_GET["cal"])?intval($_GET["cal"]):'1'); ?>"]
                       </div>
                       
                       <?php if (defined('ELEMENTOR_PATH')) { ?>
                       <br /> 
                       <h3 style="background:#cccccc; padding:5px;"><?php _e('Using','cp-contact-form-with-paypal'); ?> Elementor?</h3>
                       
                       <img src="<?php echo plugins_url('/controllers/help/elementor.png', __FILE__) ?>">
                       <?php } ?>                       
                       
                       <br />                       
                       <h3 style="background:#cccccc; padding:5px;"><?php _e('Using New WordPress Editor','cp-contact-form-with-paypal'); ?> (Gutemberg) ? </h3>
                       
                       <img src="<?php echo plugins_url('/controllers/help/gutemberg.png', __FILE__) ?>">                      
                       
                       <br /> 
                       <h3 style="background:#cccccc; padding:5px;"><?php _e('Using classic WordPress editor or other editors?','cp-contact-form-with-paypal'); ?></h3>
                       
                        <?php _e('You can also publish the form in a post/page, use the dedicated icon','cp-contact-form-with-paypal'); ?> <?php echo '<img hspace="5" src="'.plugins_url('/images/cp_form.gif', __FILE__).'" alt="'.__('Insert CP Contact Form with PayPal','cp-contact-form-with-paypal').'" /></a>';     ?>
   <?php _e('which has been added to your Upload/Insert Menu, just below the title of your Post/Page or under the "+" icon if using the Gutemberg editor.','cp-contact-form-with-paypal'); ?>
   
                         <!-- <select name="publishpage">
                         <?php 
                             $pages = get_pages();
                             foreach ( $pages as $page ) {
                               echo '<option value="' .  intval($page->ID)  . '">';
                               echo esc_html($page->post_title);
                               echo '</option>';
                             }
                         ?>
                        </select>
                        -->
                    </td>    
                </tr> 
                <tr valign="top" id="ppost" style="display:none">
                    <th><label><?php _e('Select post','cp-contact-form-with-paypal'); ?></label></th>
					<td> 
                        <select name="publishpost">
                         <?php 
                             $pages = get_posts();
                             foreach ( $pages as $page ) {
                               echo '<option value="' .  intval($page->ID)  . '">';
                               echo esc_html($page->post_title);
                               echo '</option>';
                             }
                         ?>
                        </select>                    
                    </td>    
                </tr>                    
            <tbody>                
       </table>
       <hr size="1" />
       <div class="ahb-buttons-container">
			<input type="submit" id="subbtnnow" value="<?php _e('Publish Payment Form','cp-contact-form-with-paypal'); ?>" class="button button-primary" style="float:right;margin-right:10px"  />
			<div class="clear"></div>
		</div>
</form>
</div>
</div>
<?php } ?>


<script type="text/javascript">

function reloadappbk(item) {
    document.location = '?page=cp_contact_form_paypal.php&pwizard=1&cal='+item.options[item.options.selectedIndex].value;
}


function mvpublish_displayoption(sel) {
    document.getElementById("ppost").style.display = 'none';
    document.getElementById("ppage").style.display = 'none';
    document.getElementById("posttitle").style.display = 'none';    
    document.getElementById("subbtnnow").style.display = '';
    if (sel.selectedIndex == 4)
    {
        alert('Widget option available only in commercial versions. Upgrade required for this option.');
        sel.selectedIndex = 0;        
    }
    else if (sel.selectedIndex == 2 || sel.selectedIndex == 3)
    {        
        document.getElementById("ppage").style.display = '';
        document.getElementById("subbtnnow").style.display = 'none';
    }
    else if (sel.selectedIndex == 1 || sel.selectedIndex == 0)
    {            
        document.getElementById("posttitle").style.display = '';
    }
}


</script>   

<div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e('Note','cp-contact-form-with-paypal'); ?></span></h3>
  <div class="inside">
   <?php _e('You can also publish the form in a post/page, use the dedicated icon','cp-contact-form-with-paypal'); ?> <?php echo '<img hspace="5" src="'.plugins_url('/images/cp_form.gif', __FILE__).'" alt="'.esc_attr(__('Insert','cp-contact-form-with-paypal')).' CP Contact Form with PayPal" /></a>';     ?>
   <?php _e('which has been added to your Upload/Insert Menu, just below the title of your Post/Page or under the "+" icon if using the Gutemberg editor.','cp-contact-form-with-paypal'); ?>
   <br /><br />
  </div>
</div>
