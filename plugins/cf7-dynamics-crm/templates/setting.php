<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } ?><div class="crm_fields_table">
    <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_name"><?php _e("Account Name",'contact-form-dynamics-crm'); ?></label>
  </div>
  <div class="crm_field_cell2">
  <input type="text" name="crm[name]" value="<?php echo !empty($name) ? $name : 'Account #'.$id; ?>" id="vx_name" class="crm_text">

  </div>
  <div class="clear"></div>
  </div>

     <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_url"><?php _e('Dynamics-Online URL','contact-form-dynamics-crm'); ?></label>
  </div>
  <div class="crm_field_cell2">
  <input type="text" name="crm[url]" placeholder="https://abc.crm.dynamics.com" value="<?php echo !empty($info['url']) ? $info['url'] : ''; ?>" id="vx_url" class="crm_text" <?php if(!empty($info['access_token'])){ echo 'disabled="disabled"'; } ?>>
  <div class="howto"><?php _e('After entering url, click on "Save Changes" button.','contact-form-dynamics-crm'); ?><code><?php _e('Dynamics CRM On-premises is currently not supported.','contact-form-dynamics-crm'); ?></code></div>
  </div>
  <div class="clear"></div>
  </div>

  <div class="crm_field">
  <div class="crm_field_cell1"><label><?php _e('Dynamics Access','contact-form-dynamics-crm'); ?></label></div>
  <div class="crm_field_cell2">
  <?php  if(isset($info['access_token'])  && $info['access_token']!="") {
  ?>
  <div style="padding: 8px;" class="vx_green updated below-h2"><i class="fa fa-check"></i> <?php
  echo sprintf(__("Authorized Connection to %s on %s",'contact-form-dynamics-crm'),'<code>'.$info['url'].'</code>',date('F d, Y h:i:s A',$info['issued_at']));
        ?></div>
  <?php
  }else{
      if(empty($info['url'])){
vxcf_dynamics::screen_msg(__('Please save dynamics URL first','contact-form-dynamics-crm'),'error');
      }else{
  ?>
  <a class="button button-default button-hero sf_login" data-id="<?php echo esc_html($client['client_id']) ?>" href="https://login.microsoftonline.com/common/oauth2/authorize?response_type=code&state=<?php echo urlencode($link."&".vxcf_dynamics::$id."_tab_action=get_token&id=".$id."&vx_nonce=".$nonce);?>&client_id=<?php echo $client['client_id'] ?>&redirect_uri=<?php echo $client['call_back'] ?>" title="<?php _e('Login with Dynamics','contact-form-dynamics-crm'); ?>" > <i class="fa fa-lock"></i> <?php _e("Login with Dynamics",'contact-form-dynamics-crm'); ?></a>
  <?php }
  }
  ?></div>
  <div class="clear"></div>
  </div>                  
    <?php if(isset($info['access_token'])  && $info['access_token']!="") {
  ?>
    <div class="crm_field">
  <div class="crm_field_cell1"><label><?php _e("Revoke Access",'contact-form-dynamics-crm'); ?></label></div>
  <div class="crm_field_cell2">  <a class="button button-secondary" id="vx_revoke" href="<?php echo $link."&".vxcf_dynamics::$id."_tab_action=get_token&vx_nonce=".$nonce.'&id='.$id?>"><i class="fa fa-unlock"></i> <?php _e("Revoke Access",'contact-form-dynamics-crm'); ?></a>
  </div>
  <div class="clear"></div>
  </div> 
      <div class="crm_field">
  <div class="crm_field_cell1"><label><?php _e("Test Connection",'contact-form-dynamics-crm'); ?></label></div>
  <div class="crm_field_cell2">      <button type="submit" class="button button-secondary" name="vx_test_connection"><i class="fa fa-refresh"></i> <?php _e("Test Connection",'contact-form-dynamics-crm'); ?></button>
  </div>
  <div class="clear"></div>
  </div> 
  <?php
    }
  ?>
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_error_email"><?php _e("Notify by Email on Errors",'contact-form-dynamics-crm'); ?></label></div>
  <div class="crm_field_cell2"><textarea name="crm[error_email]" id="vx_error_email" placeholder="<?php _e("Enter comma separated email addresses",'contact-form-dynamics-crm'); ?>" class="crm_text" style="height: 70px"><?php echo isset($info['error_email']) ? $info['error_email'] : ""; ?></textarea>
  <span class="howto"><?php _e("Enter comma separated email addresses. An email will be sent to these email addresses if an order is not properly added to Salesforce. Leave blank to disable.",'contact-form-dynamics-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div>  
   <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_custom_app_check"><?php _e('Dynamics APP','contact-form-dynamics-crm'); ?></label></div>
  <div class="crm_field_cell2"><div><input type="checkbox" name="crm[custom_app]" id="vx_custom_app_check" value="yes" <?php if(vxcf_dynamics::post('custom_app',$info) == "yes"){echo 'checked="checked"';} ?> ><?php echo __('Use custom Azure Directory Application','contact-form-dynamics-crm'); $this->tooltip('vx_custom_app'); ?></div>
  </div>
  <div class="clear"></div>
  </div>
  <div id="vx_custom_app_div" style="<?php if(vxcf_dynamics::post('custom_app',$info) != "yes"){echo 'display:none';} ?>">
     <div class="crm_field">
  <div class="crm_field_cell1"><label for="app_id"><?php _e('APP ID','contact-form-dynamics-crm'); ?></label></div>
  <div class="crm_field_cell2">
     <div class="vx_tr">
  <div class="vx_td">
  <input type="password" id="app_id" name="crm[app_id]" class="crm_text" placeholder="<?php _e('APP ID','contact-form-dynamics-crm'); ?>" value="<?php echo esc_html(vxcf_dynamics::post('app_id',$info)); ?>">
  </div><div class="vx_td2">
  <a href="#" class="button vx_toggle_btn vx_toggle_key" title="<?php _e('Toggle Consumer Key','contact-form-dynamics-crm'); ?>"><?php _e('Show Key','contact-form-dynamics-crm') ?></a>
  
  </div></div>
      <div class="howto">
  
 <div><?php echo sprintf(__('1. Register new App %sHere%s','contact-form-dynamics-crm'),'<a href="https://portal.azure.com/#blade/Microsoft_AAD_IAM/ActiveDirectoryMenuBlade/RegisteredApps">','</a>'); ?></div>
 <div><?php esc_html_e('2. Add "New App", enter name and url then click Create button','contact-form-dynamics-crm'); ?></div>
 <div><?php esc_html_e('3. Copy Application ID , it is your APP ID ','contact-form-dynamics-crm'); ?></div>
 <div><?php esc_html_e('4. go to settings and create new password, it is APP Key','contact-form-dynamics-crm'); ?></div>
 <div><?php esc_html_e('5. go to Reply URLs and enter URL which you entered in "App Redirect URL"','contact-form-dynamics-crm'); ?></div>
  </div>
</div>
  <div class="clear"></div>
  </div>
     <div class="crm_field">
  <div class="crm_field_cell1"><label for="app_secret"><?php _e('APP Key','contact-form-dynamics-crm'); ?></label></div>
  <div class="crm_field_cell2">
       <div class="vx_tr" >
  <div class="vx_td">
 <input type="password" id="app_secret" name="crm[app_secret]" class="crm_text"  placeholder="<?php _e('APP Key','contact-form-dynamics-crm'); ?>" value="<?php echo esc_html(vxcf_dynamics::post('app_secret',$info)); ?>">
  </div><div class="vx_td2">
  <a href="#" class="button vx_toggle_btn vx_toggle_key" title="<?php _e('Toggle Consumer Secret','contact-form-dynamics-crm'); ?>"><?php _e('Show Key','contact-form-dynamics-crm') ?></a>
  
  </div></div>

  
  </div>
  <div class="clear"></div>
  </div>
       <div class="crm_field">
  <div class="crm_field_cell1"><label for="app_url"><?php _e("Dynamics App URL",'contact-form-dynamics-crm'); ?></label></div>
  <div class="crm_field_cell2"><input type="text" id="app_url" name="crm[app_url]" class="crm_text" placeholder="<?php _e("Dynamics App URL",'contact-form-dynamics-crm'); ?>" value="<?php echo esc_html(vxcf_dynamics::post('app_url',$info)); ?>"> 
    <div class="howto">
   <div>1: <?php echo $link."&".vxcf_dynamics::$id."_action=get_code";?></div>
   <div>2: https://www.crmperks.com/google_auth/</div>
  </div>
  </div>
  <div class="clear"></div>
  </div>
  </div>

 
  <button type="submit" value="save" class="button-primary" title="<?php _e('Save Changes','contact-form-dynamics-crm'); ?>" name="save"><?php _e('Save Changes','contact-form-dynamics-crm'); ?></button>  
  </div>  