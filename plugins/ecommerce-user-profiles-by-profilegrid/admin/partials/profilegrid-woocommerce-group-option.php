<?php
$dbhandler = new PM_DBhandler;
$cart_tab = (!empty($group_options) && isset($group_options['woocommerce_cart_tab']))?$group_options['woocommerce_cart_tab']:$dbhandler->get_global_option_value('pm_enable_cart_tab','0');
$purchase_tab = (!empty($group_options) && isset($group_options['woocommerce_purchases_tab']))?$group_options['woocommerce_purchases_tab']:$dbhandler->get_global_option_value('pm_woocommerce_purchases_tab','0');
$review_tab = (!empty($group_options) && isset($group_options['woocommerce_reviews_tab']))?$group_options['woocommerce_reviews_tab']:$dbhandler->get_global_option_value('pm_woocommerce_reviews_tab','0');
$order_tab = (!empty($group_options) && isset($group_options['woocommerce_orders_in_account']))?$group_options['woocommerce_orders_in_account']:$dbhandler->get_global_option_value('pm_woocommerce_orders_in_account','0');
$shipping_tab = (!empty($group_options) && isset($group_options['woocommerce_shipping_address_in_account']))?$group_options['woocommerce_shipping_address_in_account']:$dbhandler->get_global_option_value('pm_woocommerce_shipping_address_in_account','0');
$billing_tab = (!empty($group_options) && isset($group_options['woocommerce_billing_address_in_account']))?$group_options['woocommerce_billing_address_in_account']:$dbhandler->get_global_option_value('pm_woocommerce_billing_address_in_account','0');
$total_spend = (!empty($group_options) && isset($group_options['woocommerce_show_total_spent']))?$group_options['woocommerce_show_total_spent']:$dbhandler->get_global_option_value('pm_woocommerce_show_total_spent','0');
$max_product = (!empty($group_options) && isset($group_options['woocommerce_max_product']))?$group_options['woocommerce_max_product']:$dbhandler->get_global_option_value('pm_woocommerce_max_product','10');
$total_spend_permission = (!empty($group_options) && isset($group_options['woocommerce_show_total_spent_permission']))?$group_options['woocommerce_show_total_spent_permission']:$dbhandler->get_global_option_value('pm_woocommerce_show_total_spent_permission','1');


?>    
<div class="uimrow">
       <div class="uimfield">
         <?php _e( 'Enable Cart Tab','profilegrid-woocommerce' ); ?>
       </div>
       <div class="uiminput">
           <input name="group_options[woocommerce_cart_tab]" id="woocommerce_cart_tab" type="checkbox"  class="pm_toggle" value="1" style="display:none;"  <?php checked($cart_tab,1);?> />
         <label for="woocommerce_cart_tab"></label>
       </div>
         <div class="uimnote"><?php _e('Enable this option to display WooCommerce Cart tab in ProfileGrid User Profiles.','profilegrid-woocommerce');?></div>
</div>

<div class="uimrow">
       <div class="uimfield">
         <?php _e( 'Display Purchases Tab','profilegrid-woocommerce' ); ?>
       </div>
       <div class="uiminput">
         <input name="group_options[woocommerce_purchases_tab]" id="woocommerce_purchases_tab" type="checkbox"  class="pm_toggle" value="1" style="display:none;" onClick="pm_show_hide(this,'woocommerce_max_product_display_html')"  <?php checked($purchase_tab,1);?> />
         <label for="woocommerce_purchases_tab"></label>
       </div>
         <div class="uimnote"><?php _e('Displays Purchases tab in user profile page with thumbnails and names of the products purchased by the user.','profilegrid-woocommerce');?></div>
</div>

<div class="childfieldsrow" id="woocommerce_max_product_display_html" style=" <?php if($purchase_tab==1){echo 'display:block;';} else { echo 'display:none;';} ?>">
  <div class="uimrow">
    <div class="uimfield"><?php _e('Max Number of Products Displayed','profilegrid-woocommerce' ); ?></div>
    <div class="uiminput">
     
      <input type="number" name="group_options[woocommerce_max_product]" id="woocommerce_max_product" value="<?php echo $max_product; ?>">
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
         <input name="group_options[woocommerce_reviews_tab]" id="woocommerce_reviews_tab" type="checkbox"  class="pm_toggle" value="1" style="display:none;" <?php checked($review_tab,1);?>/>
         <label for="woocommerce_reviews_tab"></label>
       </div>
         <div class="uimnote"><?php _e('Displays Product Reviews tab in user profile page with reviews of the products that the user has posted.','profilegrid-woocommerce');?></div>
</div>

<div class="uimrow">
       <div class="uimfield">
         <?php _e( 'Show Orders in User Account','profilegrid-woocommerce' ); ?>
       </div>
       <div class="uiminput">
         <input name="group_options[woocommerce_orders_in_account]" id="woocommerce_orders_in_account" type="checkbox"  class="pm_toggle" value="1" style="display:none;" <?php checked($order_tab,1);?> />
         <label for="woocommerce_orders_in_account"></label>
       </div>
         <div class="uimnote"><?php _e("Displays order history and status inside the 'Settings' section of user. This is only accessible to the logged in user.",'profilegrid-woocommerce');?></div>
</div>

<div class="uimrow">
       <div class="uimfield">
         <?php _e( 'Show Shipping Address in User Account','profilegrid-woocommerce' ); ?>
       </div>
       <div class="uiminput">
         <input name="group_options[woocommerce_shipping_address_in_account]" id="woocommerce_shipping_address_in_account" type="checkbox"  class="pm_toggle" value="1" style="display:none;" <?php checked($shipping_tab,1);?> />
         <label for="woocommerce_shipping_address_in_account"></label>
       </div>
         <div class="uimnote"><?php _e("Displays and allows editing of shipping address inside the 'Settings' section of user profile. This is only accessible to the logged in user.",'profilegrid-woocommerce');?></div>
</div>

<div class="uimrow">
       <div class="uimfield">
         <?php _e( 'Show Billing Address in User Account','profilegrid-woocommerce' ); ?>
       </div>
       <div class="uiminput">
         <input name="group_options[woocommerce_billing_address_in_account]" id="woocommerce_billing_address_in_account" type="checkbox"  class="pm_toggle" value="1" style="display:none;" <?php checked($billing_tab,1);?> />
         <label for="woocommerce_billing_address_in_account"></label>
       </div>
         <div class="uimnote"><?php _e("Displays and allows editing of billing address inside the 'Settings' section of user. This is only accessible to the logged in user.",'profilegrid-woocommerce');?></div>
</div>

<div class="uimrow">
       <div class="uimfield">
         <?php _e( 'Display Purchases Count and Total Spent','profilegrid-woocommerce' ); ?>
       </div>
       <div class="uiminput">
         <input name="group_options[woocommerce_show_total_spent]" id="woocommerce_show_total_spent" type="checkbox"  class="pm_toggle" value="1" style="display:none;" <?php checked($total_spend,1);?> onClick="pm_show_hide(this,'woocommerce_show_total_spend_html')"/>
         <label for="woocommerce_show_total_spent"></label>
       </div>
         <div class="uimnote"><?php _e('Displays total count of products purchased and money spent by the user on their profile headers.','profilegrid-woocommerce');?></div>
</div>

<div class="childfieldsrow" id="woocommerce_show_total_spend_html" style=" <?php if($total_spend==1){echo 'display:block;';} else { echo 'display:none;';} ?>">
  <div class="uimrow">
    <div class="uimfield"><?php _e( 'Visibility','profilegrid-woocommerce' ); ?></div>
    <div class="uiminput">
      <select name="group_options[woocommerce_show_total_spent_permission]" id="woocommerce_show_total_spent_permission">
                    <option value="1" <?php if($total_spend_permission=='1'){ echo "selected";}?>><?php _e('Everyone','profilegrid-woocommerce');?></option>
                    <option value="2" <?php if($total_spend_permission=='2'){ echo "selected";}?>><?php _e('Group Leader Only','profilegrid-woocommerce');?></option>
                    <option value="3" <?php if($total_spend_permission=='3'){ echo "selected";}?>><?php _e('Group Members Only','profilegrid-woocommerce');?></option>
                    <option value="4" <?php if($total_spend_permission=='4'){ echo "selected";}?>><?php _e('Friends Only','profilegrid-woocommerce');?></option>
                    <option value="5" <?php if($total_spend_permission=='5'){ echo "selected";}?>><?php _e('Private','profilegrid-woocommerce');?></option>
                </select>
      <div class="errortext"></div>
    </div>
    <div class="uimnote"><?php _e('Define who will be able to see purchase count and total spent on user profiles.','profilegrid-woocommerce');?></div>
  </div>
</div>