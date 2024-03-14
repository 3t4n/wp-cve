<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } ?><div class="crm_fields_table">
    <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_name"><?php esc_html_e("Account Name",'cf7-pipedrive'); ?></label>
  </div>
  <div class="crm_field_cell2">
  <input type="text" name="crm[name]" value="<?php echo !empty($name) ? esc_html($name) : 'Account #'.esc_html($id); ?>" id="vx_name" class="crm_text">

  </div>
  <div class="clear"></div>
  </div>
                
    <?php if(isset($info['api_token'])  && $info['api_token']!="") {
  ?>
      <div class="crm_field">
  <div class="crm_field_cell1"><label><?php esc_html_e("Test Connection",'cf7-pipedrive'); ?></label></div>
  <div class="crm_field_cell2">      <button type="submit" class="button button-secondary" name="vx_test_connection"><i class="fa fa-refresh"></i> <?php esc_html_e("Test Connection",'cf7-pipedrive'); ?></button>
  </div>
  <div class="clear"></div>
  </div> 
  <?php
    }
  ?>
<div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_pass"><?php esc_html_e('API Key','cf7-pipedrive'); ?></label></div>
  <div class="crm_field_cell2">
  <div class="vx_tr" >
  <div class="vx_td">
  <input type="password" id="vx_pass" name="crm[api_key]" class="crm_text" placeholder="<?php esc_html_e('API Key','cf7-pipedrive'); ?>" value="<?php echo $this->post('api_key',$info); ?>" required>
  </div>
  <div class="vx_td2">
  <a href="#" class="button vx_toggle_btn vx_toggle_key" title="<?php esc_html_e('Toggle Key','cf7-pipedrive'); ?>"><?php esc_html_e('Show Key','cf7-pipedrive') ?></a>
  
  </div>
  </div>
  </div>
  <div class="clear"></div>
  </div>
  
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_url"><?php esc_html_e('Pipedrive URL','cf7-pipedrive'); ?></label></div>
  <div class="crm_field_cell2">
 <input type="text" id="vx_url" name="crm[app_url]" class="crm_text" placeholder="<?php esc_html_e('https://your-company.pipedrive.com','cf7-pipedrive'); ?>" value="<?php echo $this->post('app_url',$info); ?>" required>
  </div>
  <div class="clear"></div>
  </div> 
      
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_error_email"><?php esc_html_e("Notify by Email on Errors",'cf7-pipedrive'); ?></label></div>
  <div class="crm_field_cell2"><textarea name="crm[error_email]" id="vx_error_email" placeholder="<?php esc_html_e("Enter comma separated email addresses",'cf7-pipedrive'); ?>" class="crm_text" style="height: 70px"><?php echo isset($info['error_email']) ? esc_html($info['error_email']) : ""; ?></textarea>
  <span class="howto"><?php esc_html_e("Enter comma separated email addresses. An email will be sent to these email addresses if an order is not properly added to Pipedrive. Leave blank to disable.",'cf7-pipedrive'); ?></span>
  </div>
  <div class="clear"></div>
  </div>  
   
 
  <button type="submit" value="save" class="button-primary" title="<?php esc_html_e('Save Changes','cf7-pipedrive'); ?>" name="save"><?php esc_html_e('Save Changes','cf7-pipedrive'); ?></button>  
  </div>  