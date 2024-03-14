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
    .submit{
  display: none;
  }
  </style> 
    <script type="text/javascript">
  jQuery(document).ready(function($){

        $('#vx_env').change(function(){
   var btn=$('#vx_login_btn');
   var link=btn.attr('data-login');   
  if($(this).val() == 'test'){
    link=btn.attr('data-test');   
  }
  btn.attr('href',link);
  });
    $(".vx_tabs_radio").click(function(){
  $(".vx_tabs").hide();   
  $("#tab_"+this.id).show();   
  }); 
$(".sf_login").click(function(e){
    if($("#vx_custom_app_check").is(":checked")){
    var client_id=$(this).data('id');
    var new_id=$("#app_id").val();
    if(client_id!=new_id){
          e.preventDefault();   
     alert("<?php esc_html_e('Google Sheets Client ID Changed.Please save new changes first','integration-for-contact-form-7-and-spreadsheets') ?>");   
    }    
    }
})
  $("#vx_custom_app_check").click(function(){ 
     if($(this).is(":checked")){
         $("#vx_custom_app_div").show();
     }else{
            $("#vx_custom_app_div").hide();
     } 
  });
  
    $(document).on('click','#vx_revoke',function(e){
  
  if(!confirm('<?php esc_html_e('Notification - Remove Connection?','integration-for-contact-form-7-and-spreadsheets'); ?>')){
  e.preventDefault();   
  }
  });

  });
  </script> 
<div class="vx_wrap">

  <form method="post" id="mainform">
  <?php wp_nonce_field("vx_nonce") ?>
  <h2 class="vx_img_head"><img alt="<?php esc_html_e("Google Sheets Feeds", 'integration-for-contact-form-7-and-spreadsheets') ?>" title="<?php esc_html_e("Google Sheets Feeds", 'integration-for-contact-form-7-and-spreadsheets') ?>" src="<?php echo $this->get_base_url()?>images/googlesheets-crm-logo.png?ver=1" /> <?php 
  if(empty($id)){
  esc_html_e("Google Sheets Accounts", 'integration-for-contact-form-7-and-spreadsheets');
  }else{
  esc_html_e("Google Sheets Account #", 'integration-for-contact-form-7-and-spreadsheets'); echo esc_html($id);    
  } 
  if(empty($id) || $new_account_id != $id){
 ?> <a href="<?php echo esc_url($new_account); ?>" class="add-new-h2" title="<?php esc_html_e('Add New Account','integration-for-contact-form-7-and-spreadsheets'); ?>"><?php esc_html_e('Add New Account','integration-for-contact-form-7-and-spreadsheets'); ?></a> 
 <?php
}if(!empty($id)){
 ?>
 <a href="<?php echo esc_url($page_link); ?>" class="add-new-h2" title="<?php esc_html_e('Back to Accounts','integration-for-contact-form-7-and-spreadsheets'); ?>"><?php esc_html_e('Back to Accounts','integration-for-contact-form-7-and-spreadsheets'); ?></a>
 <?php
}
 ?>
  </h2>
  <p style="text-align: left;"> <?php echo sprintf(esc_html__("If you don't have a Google Sheets account, you can %ssign up for one here%s.", 'integration-for-contact-form-7-and-spreadsheets'), "<a href='https://accounts.google.com/' target='_blank' title='".esc_html__('Sign Up for Google Sheets CRM','integration-for-contact-form-7-and-spreadsheets')."'>" , "</a>") ?> </p>
    <?php 
$this->show_msgs($msgs);               

    if(!empty($id)){ 
          $name=$this->post('name',$info); 
     include_once(self::$path . "templates/setting.php");   
    }else{
    include_once(self::$path . "templates/settings-table.php");        
    }
    do_action('vx_plugin_upgrade_notice_'.$this->type);
    ?>
 <div>


</div>
  </form>

  </div>
