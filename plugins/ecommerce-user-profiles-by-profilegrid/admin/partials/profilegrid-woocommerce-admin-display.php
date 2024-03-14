<?php
$dbhandler = new PM_DBhandler;
$pmrequests = new PM_request;
$path =  plugin_dir_url(__FILE__);
$identifier = 'SETTINGS';
$pm_user_profile_page = $dbhandler->get_global_option_value('pm_user_profile_page','0');

if(filter_input(INPUT_POST,'submit_settings'))
{
	$retrieved_nonce = filter_input(INPUT_POST,'_wpnonce');
	if (!wp_verify_nonce($retrieved_nonce, 'save_woocommerce_settings' ) ) die( 'Failed security check' );
	$exclude = array("_wpnonce","_wp_http_referer","submit_settings");
	if(!isset($_POST['pm_enable_woocommerce'])) $_POST['pm_enable_woocommerce'] = 0;
        if(!isset($_POST['pm_enable_cart_tab'])) $_POST['pm_enable_cart_tab'] = 0;
        if(!isset($_POST['pm_woocommerce_purchases_tab'])) $_POST['pm_woocommerce_purchases_tab'] = 0;
        if(!isset($_POST['pm_woocommerce_reviews_tab'])) $_POST['pm_woocommerce_reviews_tab'] = 0;
        if(!isset($_POST['pm_woocommerce_orders_in_account'])) $_POST['pm_woocommerce_orders_in_account'] = 0;
        if(!isset($_POST['pm_woocommerce_shipping_address_in_account'])) $_POST['pm_woocommerce_shipping_address_in_account'] = 0;
        if(!isset($_POST['pm_woocommerce_billing_address_in_account'])) $_POST['pm_woocommerce_billing_address_in_account'] = 0;
        if(!isset($_POST['pm_woocommerce_show_total_spent'])) $_POST['pm_woocommerce_show_total_spent'] = 0;
            
	$post = $pmrequests->sanitize_request($_POST,$identifier,$exclude);
	if($post!=false)
	{
		foreach($post as $key=>$value)
		{
			$dbhandler->update_global_option_value($key,$value);
		}
	}
	
	wp_redirect('admin.php?page=pm_settings');exit;
}
?>

<div class="uimagic">
  <form name="pm_woocommerce_settings" id="pm_woocommerce_settings" method="post">
    <!-----Dialogue Box Starts----->
    <div class="content">
      <div class="uimheader">
        <?php _e( 'WooCommerce Integration','profilegrid-woocommerce' ); ?>
           <div class="pg-box-head-ext-nav pg-box-head-ext-nav-woocommerce">
            <ul>
                <li><a href="https://profilegrid.co/woocommerce-user-profiles-purchases-reviews-social-activity/" target="_blank" class="pg-box-border pg-box-white-bg">Documentation<span class="material-icons"> article </span></a></li>
                
                <li><a href="<?php echo get_permalink($pm_user_profile_page);?>" target="_blank" class="pg-box-border pg-box-white-bg"><?php _e('Profile Preview','profilegrid-user-profiles-groups-and-communities');?><span class="material-icons"> preview </span></a></li>
                
            </ul> 

        </div>
      </div>
        
      
     
      <div class="uimsubheader">
        <?php
		//Show subheadings or message or notice
		?>
      </div>
    
        <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Enable WooCommerce Integration','profilegrid-woocommerce' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_enable_woocommerce" id="pm_enable_woocommerce" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_woocommerce','1'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'pm_woocommerce_html')" />
          <label for="pm_enable_woocommerce"></label>
        </div>
        <div class="uimnote"><?php _e("Turns on WooCommerce connection with ProfileGrid",'profilegrid-woocommerce');?></div>
      </div>
        
        <div class="childfieldsrow" id="pm_woocommerce_html" style="<?php if($dbhandler->get_global_option_value('pm_enable_woocommerce','1')=='1'){echo 'display:block;';} else { echo 'display:none;';} ?>">
      
          <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Enable Cart Tab','profilegrid-woocommerce' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_enable_cart_tab" id="pm_enable_cart_tab" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_enable_cart_tab','1'),'1'); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_enable_cart_tab"></label>
        </div>
        <div class="uimnote"><?php _e("Enable this option to display WooCommerce Cart tab in ProfileGrid User Profiles",'profilegrid-woocommerce');?></div>
      </div>
            
     <div class="uimrow">
       <div class="uimfield">
         <?php _e( 'Display Purchases Tab','profilegrid-woocommerce' ); ?>
       </div>
       <div class="uiminput">
         <input name="pm_woocommerce_purchases_tab" id="pm_woocommerce_purchases_tab" type="checkbox"  class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'woocommerce_max_product_display_html')"  <?php checked($dbhandler->get_global_option_value('pm_woocommerce_purchases_tab','0'),'1'); ?> />
         <label for="pm_woocommerce_purchases_tab"></label>
       </div>
         <div class="uimnote"><?php _e('Displays Purchases tab in user profile page with thumbnails and names of the products purchased by the user.','profilegrid-woocommerce');?></div>
    </div>
    
<div class="childfieldsrow" id="woocommerce_max_product_display_html" style="<?php if($dbhandler->get_global_option_value('pm_woocommerce_purchases_tab','0')=='1'){echo 'display:block;';} else { echo 'display:none;';} ?>">
  <div class="uimrow">
    <div class="uimfield"><?php _e('Max Number of Products Displayed','profilegrid-woocommerce' ); ?></div>
    <div class="uiminput">
     
        <input type="number" name="pm_woocommerce_max_product" id="pm_woocommerce_max_product" value="<?php echo $dbhandler->get_global_option_value('pm_woocommerce_max_product','10'); ?>">
      <div class="errortext"></div>
    
    </div>
    <div class="uimnote"><?php _e('Define the maximum number of products visible in "Purchases" tab of the profile.','profilegrid-woocommerce');?></div>
  </div>
</div> 
            
            <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Show Product Reviews Tab','profilegrid-woocommerce' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_woocommerce_reviews_tab" id="pm_woocommerce_reviews_tab" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_woocommerce_reviews_tab','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_woocommerce_reviews_tab"></label>
        </div>
        <div class="uimnote"><?php _e("Displays Product Reviews tab in user profile page with reviews of the products that the user has posted.",'profilegrid-woocommerce');?></div>
      </div>
            
            <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Show Orders in User Account','profilegrid-woocommerce' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_woocommerce_orders_in_account" id="pm_woocommerce_orders_in_account" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_woocommerce_orders_in_account','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_woocommerce_orders_in_account"></label>
        </div>
        <div class="uimnote"><?php _e("Displays order history and status inside the 'Settings' section of user. This is only accessible to the logged in user.",'profilegrid-woocommerce');?></div>
      </div>
            
            <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Show Shipping Address in User Account','profilegrid-woocommerce' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_woocommerce_shipping_address_in_account" id="pm_woocommerce_shipping_address_in_account" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_woocommerce_shipping_address_in_account','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_woocommerce_shipping_address_in_account"></label>
        </div>
        <div class="uimnote"><?php _e("Displays and allows editing of shipping address inside the 'Settings' section of user profile. This is only accessible to the logged in user.",'profilegrid-woocommerce');?></div>
      </div>
            
            <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Show Billing Address in User Account','profilegrid-woocommerce' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_woocommerce_billing_address_in_account" id="pm_woocommerce_billing_address_in_account" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_woocommerce_billing_address_in_account','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" />
          <label for="pm_woocommerce_billing_address_in_account"></label>
        </div>
        <div class="uimnote"><?php _e("Displays and allows editing of billing address inside the 'Settings' section of user. This is only accessible to the logged in user.",'profilegrid-woocommerce');?></div>
      </div>
            
            <div class="uimrow">
        <div class="uimfield">
          <?php _e( 'Display Purchases Count and Total Spent','profilegrid-woocommerce' ); ?>
        </div>
        <div class="uiminput">
           <input name="pm_woocommerce_show_total_spent" id="pm_woocommerce_show_total_spent" type="checkbox" <?php checked($dbhandler->get_global_option_value('pm_woocommerce_show_total_spent','0'),'1'); ?> class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'woocommerce_show_total_spend_html')" />
          <label for="pm_woocommerce_show_total_spent"></label>
        </div>
        <div class="uimnote"><?php _e("Displays total count of products purchased and money spent by the user on their profile headers.",'profilegrid-woocommerce');?></div>
      </div>
            
            <div class="childfieldsrow" id="woocommerce_show_total_spend_html" style=" <?php if($dbhandler->get_global_option_value('pm_woocommerce_show_total_spent','0')==1){echo 'display:block;';} else { echo 'display:none;';} ?>">
  <div class="uimrow">
    <div class="uimfield"><?php _e( 'Visibility','profilegrid-woocommerce' ); ?></div>
    <div class="uiminput">
      <select name="pm_woocommerce_show_total_spent_permission" id="pm_woocommerce_show_total_spent_permission">
          <option value="1" <?php if($dbhandler->get_global_option_value('pm_woocommerce_show_total_spent_permission','1')=='1'){ echo "selected";}?>><?php _e('Everyone','profilegrid-woocommerce');?></option>
                    <option value="2" <?php if($dbhandler->get_global_option_value('pm_woocommerce_show_total_spent_permission','1')=='2'){ echo "selected";}?>><?php _e('Group Leader Only','profilegrid-woocommerce');?></option>
                    <option value="3" <?php if($dbhandler->get_global_option_value('pm_woocommerce_show_total_spent_permission','1')=='3'){ echo "selected";}?>><?php _e('Group Members Only','profilegrid-woocommerce');?></option>
                    <option value="4" <?php if($dbhandler->get_global_option_value('pm_woocommerce_show_total_spent_permission','1')=='4'){ echo "selected";}?>><?php _e('Friends Only','profilegrid-woocommerce');?></option>
                    <option value="5" <?php if($dbhandler->get_global_option_value('pm_woocommerce_show_total_spent_permission','1')=='5'){ echo "selected";}?>><?php _e('Private','profilegrid-woocommerce');?></option>
                </select>
      <div class="errortext"></div>
    </div>
    <div class="uimnote"><?php _e('Define who will be able to see purchase count and total spent on user profiles.','profilegrid-woocommerce');?></div>
  </div>
</div>
    
        </div>
        
        <div class="pg-uim-notice-wrap"><div class="pg-uim-notice pg-woo-notice"><?php _e('Override these settings later from group options > WooCommerce','profilegrid-woocommerce');?></div></div>
        <div class="pg-uim-notice-wrap"><div class="pg-uim-notice pg-woo-notice"><?php echo sprintf(__('Download advanced WooCommerce Membership extensions <a href="%s" target="_blank">here</a>.','profilegrid-woocommerce'),'https://profilegrid.co/extensions/');?></div></div>
        
      <div class="buttonarea"> 
          <a href="<?php echo esc_url("admin.php?page=pm_settings");?>">
        <div class="cancel">&#8592; &nbsp;
          <?php _e('Cancel','profilegrid-woocommerce');?>
        </div>
        </a>
        <?php wp_nonce_field('save_woocommerce_settings'); ?>
        <input type="submit" value="<?php _e('Save','profilegrid-woocommerce');?>" name="submit_settings" id="submit_settings" />
        <div class="all_error_text" style="display:none;"></div>
      </div>
    </div>
   
  </form>
</div>







 