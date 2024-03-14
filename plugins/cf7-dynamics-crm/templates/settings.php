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
  <?php esc_html_e("Settings", 'contact-form-dynamics-crm') ?>
  </h2>
  <table class="form-table">
  <tr>
  <th scope="row"><label for="vx_plugin_data"><?php _e("Plugin Data", 'contact-form-dynamics-crm'); ?></label>
  </th>
  <td>
<label for="vx_plugin_data"><input type="checkbox" name="meta[plugin_data]" value="yes" <?php if(vxcf_dynamics::post('plugin_data',$meta) == "yes"){echo 'checked="checked"';} ?> id="vx_plugin_data"><?php _e('On deleting this plugin remove all of its data','contact-form-dynamics-crm'); ?></label>
  </td>
  </tr>
  

  <?php
  if(class_exists('vxcf_form')){
  ?>
  <tr>
<th><label for="update_entry"><?php _e("Update Entry",'contact-form-dynamics-crm');  ?></label></th>
<td><label for="update_entry"><input type="checkbox" id="update_entry" name="meta[update]" value="yes" <?php if(vxcf_dynamics::post('update',$meta) == "yes"){echo 'checked="checked"';} ?> ><?php _e("Update entry data in Dynamics when updated in Contact Form Entries Plugin",'contact-form-dynamics-crm');  ?></label></td>
</tr>
<tr>
<th><label for="delet_entry"><?php _e("Delete Entry",'contact-form-dynamics-crm');  ?></label></th>
<td><label for="delet_entry"><input type="checkbox" id="delet_entry" name="meta[delete]" value="yes" <?php if(vxcf_dynamics::post('delete',$meta) == "yes"){echo 'checked="checked"';} ?> ><?php _e("Delete entry data from Dynamics when deleted from Contact Form Entries Plugin",'contact-form-dynamics-crm'); ?></label></td>
</tr>
<tr>
<th><label for="restore_entry"><?php _e("Restore Entry",'contact-form-dynamics-crm');  ?></label></th>
<td><label for="restore_entry"><input type="checkbox" id="restore_entry" name="meta[restore]" value="yes" <?php if(vxcf_dynamics::post('restore',$meta) == "yes"){echo 'checked="checked"';} ?> ><?php _e("Restore entry in Dynamics when restored in Contact Form Entries Plugin",'contact-form-dynamics-crm'); ?></label></td>
</tr>
<tr>
<th><label for="notes_meta"><?php _e("Entry Notes",'contact-form-dynamics-crm');  ?></label></th>
<td><label for="notes_meta"><input type="checkbox" id="notes_meta" name="meta[notes]" value="yes" <?php if(vxcf_dynamics::post('notes',$meta) == "yes"){echo 'checked="checked"';} ?> ><?php _e("Add / delete notes to Dynamics when added / deleted in Contact Form Entries Plugin",'contact-form-dynamics-crm'); ?></label></td>
</tr>

<?php
  }
?>
  </table>

  <p class="submit"><input type="submit" name="save" class="button-primary" title="<?php _e('Save Settings','contact-form-dynamics-crm'); ?>" value="<?php _e("Save Settings", 'contact-form-dynamics-crm') ?>" /></p>
  </form>

  <?php
  //var_dump(self::$tooltips);
  do_action('add_section_'.vxcf_dynamics::$id);
  ?>

  </div>
