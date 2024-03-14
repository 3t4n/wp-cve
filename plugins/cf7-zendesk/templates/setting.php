<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } ?><div class="crm_fields_table">
    <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_name"><?php esc_html_e("Account Name",'contact-form-zendesk-crm'); ?></label>
  </div>
  <div class="crm_field_cell2">
  <input type="text" name="crm[name]" value="<?php echo !empty($name) ? esc_html($name) : 'Account #'.esc_html($id); ?>" id="vx_name" class="crm_text">

  </div>
  <div class="clear"></div>
  </div>
                
    <?php if(isset($info['api_key'])  && $info['api_key']!="") {
  ?>
      <div class="crm_field">
  <div class="crm_field_cell1"><label><?php esc_html_e("Test Connection",'contact-form-zendesk-crm'); ?></label></div>
  <div class="crm_field_cell2">      <button type="submit" class="button button-secondary" name="vx_test_connection"><i class="fa fa-refresh"></i> <?php esc_html_e("Test Connection",'contact-form-zendesk-crm'); ?></button>
  </div>
  <div class="clear"></div>
  </div> 
  <?php
    }
  ?>

    <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_url"><?php esc_html_e('URL','contact-form-zendesk-crm'); ?></label></div>
  <div class="crm_field_cell2">
<input type="url" id="vx_url" name="crm[url]" class="crm_text" placeholder="https://example.zendesk.com" value="<?php echo esc_html($this->post('url',$info)); ?>" required>
  </div>
  <div class="clear"></div>
  </div>
      <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_email"><?php esc_html_e('Email','contact-form-zendesk-crm'); ?></label></div>
  <div class="crm_field_cell2">
<input type="email" id="vx_email" name="crm[email]" class="crm_text" placeholder="<?php esc_html_e('Zendesk Login email','contact-form-zendesk-crm'); ?>" value="<?php echo esc_html($this->post('email',$info)); ?>" required>
  </div>
  <div class="clear"></div>
  </div>
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_pass"><?php esc_html_e('API Key','contact-form-zendesk-crm'); ?></label></div>
  <div class="crm_field_cell2">
  <div class="vx_tr">
  <div class="vx_td">
  <input type="password" id="vx_pass" name="crm[api_key]" class="crm_text" placeholder="<?php esc_html_e('API Key','contact-form-zendesk-crm'); ?>" value="<?php echo esc_html($this->post('api_key',$info)); ?>" required>
  <span class="howto"><?php esc_html_e("Go to Zendesk admin > Apps and Integrations > API > Zendesk API and add click Add API key button.",'contact-form-zendesk-crm'); ?></span>
  </div>
  <div class="vx_td2">
  <a href="#" class="button vx_toggle_btn vx_toggle_key" title="<?php esc_html_e('Toggle Key','contact-form-zendesk-crm'); ?>"><?php esc_html_e('Show Key','contact-form-zendesk-crm') ?></a>
  
  </div>
  </div>
  </div>
  <div class="clear"></div>
  </div>
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_error_email"><?php esc_html_e("Notify by Email on Errors",'contact-form-zendesk-crm'); ?></label></div>
  <div class="crm_field_cell2"><textarea name="crm[error_email]" id="vx_error_email" placeholder="<?php esc_html_e("Enter comma separated email addresses",'contact-form-zendesk-crm'); ?>" class="crm_text" style="height: 70px"><?php echo isset($info['error_email']) ? $info['error_email'] : ""; ?></textarea>
  <span class="howto"><?php esc_html_e("Enter comma separated email addresses. An email will be sent to these email addresses if an order is not properly added to Zendesk. Leave blank to disable.",'contact-form-zendesk-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div>  
   
 
  <button type="submit" value="save" class="button-primary" title="<?php esc_html_e('Save Changes','contact-form-zendesk-crm'); ?>" name="save"><?php esc_html_e('Save Changes','contact-form-zendesk-crm'); ?></button>  
  </div>  