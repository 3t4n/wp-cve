<?php
if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }                                            
 ?>
 <style type="text/css">
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
      margin: 0 0 0 10px; vertical-align: baseline; 
  }

    .submit_vx{
 padding-top: 10px;
  margin-top: 20px; 
  }
    .crm_fields_table input , .crm_fields_table select{
      margin: 0px;
  }
      .vx_accounts_table .vx_pointer{
      cursor: pointer;
  }
  .vx_accounts_table .fa-caret-up , .vx_accounts_table .fa-caret-down{
      display: none;
  }
  .vx_accounts_table th.headerSortUp .fa-caret-down{ 
display: inline; 
} 
  .vx_accounts_table th.headerSortDown .fa-caret-up{ 
display: inline; 
}
  </style> 
    <script type="text/javascript">
  jQuery(document).ready(function($){

    $(document).on('click','.vx_toggle_key',function(e){
  e.preventDefault();  
  var key=$(this).parents(".vx_tr").find(".crm_text"); 
  if($(this).hasClass('vx_hidden')){
  $(this).text('<?php esc_html_e('Show Key','gravity-forms-hubspot-crm') ?>');  
  $(this).removeClass('vx_hidden');
  key.attr('type','password');  
  }else{
  $(this).text('<?php esc_html_e('Hide Key','gravity-forms-hubspot-crm') ?>');  
  $(this).addClass('vx_hidden');
  key.attr('type','text');  
  }
  });
  });
  </script> 
 <div class="vx_wrap">
  <h2  style="margin-bottom: 12px; line-height: 36px">  <img alt="<?php esc_html_e("Gravity Forms HubSpot Plugin Settings", 'gravity-forms-hubspot-crm') ?>" title="<?php esc_html_e("Gravity Forms HubSpot Plugin Settings", 'gravity-forms-hubspot-crm') ?>" src="<?php echo $this->get_base_url()?>images/hubspot-crm-logo.png?ver=1" style="float:left; margin:0 7px 10px 0;" height="46" /> <?php esc_html_e("Gravity Forms HubSpot Plugin Settings", 'gravity-forms-hubspot-crm'); ?>  </h2>
  <div class="clear"></div>
  <?php 

   if(is_array($msgs) && count($msgs)>0){      
    foreach($msgs as $msg){
     if(isset($msg['class']) && $msg['class'] !=""){
          ?>
  <div class="fade below-h2 <?php echo $msg['class'] ?> notice is-dismissible">
  <p><?php echo wp_kses_post($msg['msg'])?></p>
  </div>
  <?php
      }     }
   }                
    ?>
  <form method="post" id="mainform">
  <?php wp_nonce_field("vx_nonce") ?>
  <h2><?php 
  if(empty($id)){
  esc_html_e("HubSpot Account Information", 'gravity-forms-hubspot-crm');
  }else{
  esc_html_e("HubSpot Account #", 'gravity-forms-hubspot-crm'); echo esc_html($id);    
  }
  if(empty($id) || $new_account_id != $id){
 ?> <a href="<?php echo esc_url($new_account) ?>" class="add-new-h2" title="<?php esc_html_e('Add New Account','gravity-forms-hubspot-crm'); ?>"><?php esc_html_e('Add New Account','gravity-forms-hubspot-crm'); ?></a> 
 <?php
}
if(!empty($id)){
 ?>
 <a href="<?php echo esc_url($page_link) ?>" class="add-new-h2" title="<?php esc_html_e('Back to Accounts','gravity-forms-hubspot-crm'); ?>"> <?php esc_html_e('Back to Accounts','gravity-forms-hubspot-crm'); ?></a>
 <?php
}
 ?>
  </h2>
  <p style="text-align: left;"> <?php echo sprintf(__("If you don't have a HubSpot account, you can %ssign up for one here%s.", 'gravity-forms-hubspot-crm'), "<a href='http://www.hubspot.com/' target='_blank' title='".__('Sign Up for HubSpot CRM','gravity-forms-hubspot-crm')."'>" , "</a>") ?> </p>
<?php 

    if(!empty($id)){
          $name=$this->post('name',$info); 
     include_once(self::$path . "templates/setting.php");   
    }else{
    include_once(self::$path . "templates/settings-table.php");        
    }
     do_action('vx_plugin_upgrade_notice_'.$this->type);
    ?>
 <div>
  <div class="hr-divider" style="margin: -10px 0 24px 0"></div>
<h3><?php esc_html_e('Analytics Tracking','gravity-forms-hubspot-crm');  ?></h3>
<p><?php echo sprintf(__('HubSpot Javascript analytics tracking is a required feature in order for HubSpot API to work correctly. However, if you have already included this via the %s HubSpot All-In-One Marketing %s, or within your theme code itself, you do not need to check this box.','gravity-forms-hubspot-crm'),'<a href="https://wordpress.org/plugins/leadin/">','</a>');  ?></p>
<table class="form-table">
  <tr>
  <th scope="row"><label for="vx_hub"><?php esc_html_e("Hub ID", 'gravity-forms-hubspot-crm'); ?></label>
  </th>
  <td>
<input type="text" name="meta[hub_id]" value="<?php echo $this->post('hub_id',$meta); ?>" style="width: 100%;" id="vx_hub_id">
<p class="howto"><?php esc_html_e('in HubSpot go to Settings -> Tracking Code -> Wordpress installation -> Hub ID', 'gravity-forms-hubspot-crm'); ?></p>
  </td>
  </tr> 
    <tr>
  <th scope="row"><label for="vx_hub_js"><?php esc_html_e("HubSpot Tracking Code", 'gravity-forms-hubspot-crm'); ?></label>
  </th>
  <td>
<label for="vx_hub_js"><input type="checkbox" name="meta[hub_js]" value="yes" <?php if($this->post('hub_js',$meta) == "yes"){echo 'checked="checked"';} ?> id="vx_hub_js"><?php esc_html_e('Yes, Include HubSpot Analytics JS','gravity-forms-hubspot-crm'); ?></label>
  </td>
  </tr> 
  </table>
<div class="hr-divider" style="margin: 10px 0 24px 0"></div>
<h3><?php esc_html_e('Optional Settings','gravity-forms-hubspot-crm');  ?></h3>

<table class="form-table">
  <tr>
  <th scope="row"><label for="vx_plugin_data"><?php esc_html_e("Plugin Data", 'gravity-forms-hubspot-crm'); ?></label>
  </th>
  <td>
<label for="vx_plugin_data"><input type="checkbox" name="meta[plugin_data]" value="yes" <?php if($this->post('plugin_data',$meta) == "yes"){echo 'checked="checked"';} ?> id="vx_plugin_data"><?php esc_html_e('On deleting this plugin remove all of its data','gravity-forms-hubspot-crm'); ?></label>
  </td>
  </tr>   

<tr>
<th><label for="update_meta"><?php esc_html_e("Update Entry",'gravity-forms-hubspot-crm');  ?></label></th>
<td><label for="update_meta"><input type="checkbox" id="update_meta" name="meta[update]" value="yes" <?php if($this->post('update',$meta) == "yes"){echo 'checked="checked"';} ?> ><?php esc_html_e("Update entry data in HubSpot when updated in Gravity Forms",'gravity-forms-hubspot-crm');  ?></label></td>
</tr>
<tr>
<th><label for="delete_meta"><?php esc_html_e("Delete Entry",'gravity-forms-hubspot-crm');  ?></label></th>
<td><label for="delete_meta"><input type="checkbox" id="delete_meta" name="meta[delete]" value="yes" <?php if($this->post('delete',$meta) == "yes"){echo 'checked="checked"';} ?> ><?php esc_html_e("Delete entry data from HubSpot when deleted from Gravity Forms",'gravity-forms-hubspot-crm'); ?></label></td>
</tr>

<tr>
<th><label for="restore_meta"><?php esc_html_e("Restore Entry",'gravity-forms-hubspot-crm');  ?></label></th>
<td><label for="restore_meta"><input type="checkbox" id="restore_meta" name="meta[restore]" value="yes" <?php if($this->post('restore',$meta) == "yes"){echo 'checked="checked"';} ?> ><?php esc_html_e("Send entry data to HubSpot when restored in Gravity Forms",'gravity-forms-hubspot-crm'); ?></label></td>
</tr>

<tr>
<th><label for="notes_meta"><?php esc_html_e("Entry Notes",'gravity-forms-hubspot-crm');  ?></label></th>
<td><label for="notes_meta"><input type="checkbox" id="notes_meta" name="meta[notes]" value="yes" <?php if($this->post('notes',$meta) == "yes"){echo 'checked="checked"';} ?> ><?php esc_html_e("Add / delete notes to HubSpot when added / deleted in Gravity Forms",'gravity-forms-hubspot-crm'); ?></label></td>
</tr>  


</table>
 <p class="submit">
   <button type="submit" value="save" class="button-primary" title="<?php esc_html_e('Save Changes','gravity-forms-hubspot-crm'); ?>" name="save"><?php esc_html_e('Save Changes','gravity-forms-hubspot-crm'); ?></button>
  <input type="hidden" name="vx_meta" value="1"> 
 </p>
</div>
  </form>

  <?php
  do_action('add_section_'.$this->id);
    if(current_user_can($this->id."_uninstall")){ 
  ?>
  <form action="" method="post">
  <?php wp_nonce_field("vx_nonce") ?>
  <?php if(current_user_can($this->id."_uninstall")){ ?>
  <div class="hr-divider"  style="margin: -10px 0 24px 0"></div>
  <h3><?php esc_html_e("Uninstall HubSpot Plugin", 'gravity-forms-hubspot-crm') ?></h3>
  <div class="delete-alert alert_red">
  <h3>
  <?php esc_html_e('Warning', 'gravity-forms-hubspot-crm'); ?>
  </h3>
  <p><?php esc_html_e("This operation deletes ALL HubSpot Feeds. ", 'gravity-forms-hubspot-crm') ?></p>
  <?php
  echo '<input type="submit" name="'.esc_attr($this->id).'_uninstall" title="'.esc_html__('Uninstall','gravity-forms-hubspot-crm').'" value="' . esc_html__("Uninstall HubSpot Plugin", 'gravity-forms-hubspot-crm') . '" class="button" onclick="return confirm(\'' .esc_html__('Warning! ALL HubSpot Feeds and Logs will be deleted. This cannot be undone. (OK) to delete, (Cancel) to stop', 'gravity-forms-hubspot-crm') . '\');"/>';
 
  ?>
  </div>
  <?php } ?>
  </form>
  <?php
  }
  ?>
  </div>
  <script type="text/javascript">
  jQuery(document).ready(function($){

             var unsaved=false;
      $('#mainform :input').change(function(){ 
        unsaved=true;
      });
       $('#mainform').submit(function(){ 
        unsaved=false;
      });
      
      $(window).bind("beforeunload",function(event) { 
    if(unsaved) return 'Changes you made may not be saved';
});
      
      $("#vx_refresh_lists").click(function(){
       $(this).val("true");   
      });

  
  })
  </script>