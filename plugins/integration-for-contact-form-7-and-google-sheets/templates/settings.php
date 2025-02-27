<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } ?>  <style type="text/css">
  .crm_fields_table{
  width: 100%; margin-top: 30px;
  }.crm_fields_table .crm_field_cell1 label{
  font-weight: bold; font-size: 14px;
  }
  .crm_fields_table .clear{
  clear: both;
  }
  .crm_fields_table .crm_field{
  margin: 20px 0px;   
  }
  .crm_fields_table .crm_text{
  width: 100%;
  }
  .crm_fields_table .crm_field_cell1{
  width: 20%; min-width: 100px; float: left; display: inline-block;
  line-height: 26px;
  }
  .crm_fields_table .crm_field_cell2{
  width: 80%; float: left; display: inline-block;
  }
  .vxc_alert{
  padding: 10px 20px;
  }
  .vx_icons{
      color: #888;
  }
  .vx_green{
    color:rgb(0, 132, 0);  
  }
  #tiptip_content{
      max-width: 200px;
  }
  .vx_tr{
      display: table; width: 100%;
  }
  .vx_td{
      display: table-cell; width: 90%;
  }
  .vx_td2{
      display: table-cell; 
  }
 .crm_field .vx_td2 .vx_toggle_btn{
      margin: 0 0 0 10px; vertical-align: baseline; width: 80px;
  }
 
  </style> 
   
<div class="vx_wrap">

<form id="mainform" method="post">
  <?php wp_nonce_field("vx_nonce") ?>
  <h2>
  <?php esc_html_e("Settings", 'integration-for-contact-form-7-and-spreadsheets') ?>
  </h2>
  <table class="form-table">
  <tr>
  <th scope="row"><label for="vx_plugin_data"><?php esc_html_e("Plugin Data", 'integration-for-contact-form-7-and-spreadsheets'); ?></label>
  </th>
  <td>
<label for="vx_plugin_data"><input type="checkbox" name="meta[plugin_data]" value="yes" <?php if($this->post('plugin_data',$meta) == "yes"){echo 'checked="checked"';} ?> id="vx_plugin_data"><?php esc_html_e('On deleting this plugin remove all of its data','integration-for-contact-form-7-and-spreadsheets'); ?></label>
  </td>
  </tr>
  
      <tr>
  <th scope="row"><label for="vx_plugin_logs"><?php esc_html_e('Google Sheets Logs', 'integration-for-contact-form-7-and-spreadsheets'); ?></label>
  </th>
  <td>
<label for="vx_plugin_logs"><input type="checkbox" name="meta[disable_log]" value="yes" <?php if($this->post('disable_log',$meta) == "yes"){echo 'checked="checked"';} ?> id="vx_plugin_logs"><?php esc_html_e('Disable Storing Google Sheets Logs','integration-for-contact-form-7-and-spreadsheets'); ?></label>
  </td>
  </tr>
  
  <?php  $enable='1';
  if(class_exists('vxcf_form') && self::$is_pr && $enable == '1' ){
  ?>
  <tr>
<th><label for="update_entry"><?php esc_html_e("Update Entry",'integration-for-contact-form-7-and-spreadsheets');  ?></label></th>
<td><label for="update_entry"><input type="checkbox" id="update_entry" name="meta[update]" value="yes" <?php if($this->post('update',$meta) == "yes"){echo 'checked="checked"';} ?> ><?php esc_html_e("Update entry data in Google Sheets when updated in Contact Form Entries Plugin.",'integration-for-contact-form-7-and-spreadsheets');  ?><code><?php esc_html_e('Be careful, If you delete any row in Google sheet then all row numbers in sheet will change and this feature will overwrite wrong row in Google sheet.','integration-for-contact-form-7-and-spreadsheets');  ?></code></label></td>
</tr>
<tr>
<th><label for="delet_entry"><?php esc_html_e("Delete Entry",'integration-for-contact-form-7-and-spreadsheets');  ?></label></th>
<td><label for="delet_entry"><input type="checkbox" id="delet_entry" name="meta[delete]" value="yes" <?php if($this->post('delete',$meta) == "yes"){echo 'checked="checked"';} ?> ><?php esc_html_e("Delete entry data from Google Sheets when deleted from Contact Form Entries Plugin",'integration-for-contact-form-7-and-spreadsheets'); ?></label></td>
</tr>
<tr>
<th><label for="restore_entry"><?php esc_html_e("Restore Entry",'integration-for-contact-form-7-and-spreadsheets');  ?></label></th>
<td><label for="restore_entry"><input type="checkbox" id="restore_entry" name="meta[restore]" value="yes" <?php if($this->post('restore',$meta) == "yes"){echo 'checked="checked"';} ?> ><?php esc_html_e("Restore entry in Google Sheets when restored in Contact Form Entries Plugin",'integration-for-contact-form-7-and-spreadsheets'); ?></label></td>
</tr>
<?php
  }
?>
  </table>

  <p class="submit"><input type="submit" name="save" class="button-primary" title="<?php esc_html_e('Save Settings','integration-for-contact-form-7-and-spreadsheets'); ?>" value="<?php esc_html_e("Save Settings", 'integration-for-contact-form-7-and-spreadsheets') ?>" /></p>
  </form>

  <?php
  //var_dump(self::$tooltips);
  do_action('add_section_'.$this->id);
  ?>

  </div>
