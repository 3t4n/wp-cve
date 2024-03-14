<?php
if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }                                            
$name=$this->post('name',$info);  
$domain=get_site_url();
$domain=parse_url($domain);
$domain=$domain['host'];
$self_dir=admin_url().'?'.$this->id.'_tab_action=get_code'; 
 ?>
  <div class="crm_fields_table">
    <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_name"><?php esc_html_e("Account Name",'gravity-forms-googlesheets-crm'); ?></label>
  </div>
  <div class="crm_field_cell2">
  <input type="text" name="crm[name]" value="<?php echo !empty($name) ? esc_attr($name) : 'Account #'.esc_attr($id); ?>" id="vx_name" class="crm_text">

  </div>
  <div class="clear"></div>
  </div>

    <div class="crm_field">
  <div class="crm_field_cell1"><label for="app_id"><?php esc_html_e("Client ID",'gravity-forms-googlesheets-crm'); ?></label></div>
  <div class="crm_field_cell2">
     <div class="vx_tr">
  <div class="vx_td">
  <input type="password" id="app_id" name="crm[app_id]" required="required" class="crm_text" placeholder="<?php esc_html_e("Google Client ID",'gravity-forms-googlesheets-crm'); ?>" value="<?php echo esc_html($this->post('app_id',$info)); ?>">
  </div><div class="vx_td2">
  <a href="#" class="button vx_toggle_btn vx_toggle_key" title="<?php esc_html_e('Toggle Consumer Key','gravity-forms-googlesheets-crm'); ?>"><?php esc_html_e('Show Key','gravity-forms-googlesheets-crm') ?></a>
  
  </div></div>
  
    <ol>
  <li><?php echo sprintf(__('Go to %sGoogle Developer Console %s , Create new Project or Select old project','gravity-forms-googlesheets-crm'),'<a href="https://console.developers.google.com/" target="_blank">','</a>'); ?></li>
  <li><?php esc_html_e('Go to "Library" and Select "Google Sheets API" then click "Enable" button, Similarly enable "Google drive" API','gravity-forms-googlesheets-crm'); ?></li>
  <li><?php echo esc_html__('Go to "Oauth Consent Screen" and enter name of Application (e.g CRM Perks)','gravity-forms-googlesheets-crm'); ?>
  <li><?php echo sprintf(__('Enter %s in "Authorized domains" field then click "Save" button','gravity-forms-googlesheets-crm'),'<code>'.$domain.'</code>'); ?>
  <li><?php echo esc_html__('Go to "Credentials" and click "New Credentials" then Select "Oauth Client ID"','gravity-forms-googlesheets-crm'); ?>
  <li><?php echo sprintf(__('Select Application Type as "Web application", Enter name(e.g CRM Perks APP) , Enter %s or %s in "Authorized redirect URIs" field then click "Create" button','gravity-forms-googlesheets-crm'),'<code>https://www.crmperks.com/google_auth/</code>','<code>'.$self_dir.'</code>'); ?>
  </li>
<li><?php echo esc_html__('Copy "Client ID" and "Client Secret" then save changes','gravity-forms-googlesheets-crm'); ?></li>
<li><?php echo esc_html__('Click "Login with Google Sheets" button, if you see "App is not verified" error then click "show advanced" link and "Go to App" link','gravity-forms-googlesheets-crm'); ?></li>
   </ol>
  
</div>
  <div class="clear"></div>
  </div>
     <div class="crm_field">
  <div class="crm_field_cell1"><label for="app_secret"><?php esc_html_e("Client Secret",'gravity-forms-googlesheets-crm'); ?></label></div>
  <div class="crm_field_cell2">
       <div class="vx_tr" >
  <div class="vx_td">
 <input type="password" id="app_secret" name="crm[app_secret]" class="crm_text" required="required"  placeholder="<?php esc_html_e("Google Client Secret",'gravity-forms-googlesheets-crm'); ?>" value="<?php echo esc_html($this->post('app_secret',$info)); ?>">
  </div><div class="vx_td2">
  <a href="#" class="button vx_toggle_btn vx_toggle_key" title="<?php esc_html_e('Toggle Client Secret','gravity-forms-googlesheets-crm'); ?>"><?php esc_html_e('Show Key','gravity-forms-googlesheets-crm') ?></a>
  
  </div></div>
  </div>
  <div class="clear"></div>
  </div>
       <div class="crm_field">
  <div class="crm_field_cell1"><label for="app_url"><?php esc_html_e("Authorized redirect URI",'gravity-forms-googlesheets-crm'); ?></label></div>
  <div class="crm_field_cell2"><input type="url" id="app_url" name="crm[app_url]" required="required" class="crm_text" placeholder="<?php esc_html_e("Google Redirect URL",'gravity-forms-googlesheets-crm'); ?>" value="<?php echo esc_html($this->post('app_url',$info)); ?>"> 
<div class="howto">  <?php esc_html_e("Redirect URL should be same as entered in Google APP's Authorized redirect URIs field", 'gravity-forms-googlesheets-crm'); ?></div>
  </div>
  <div class="clear"></div>
  </div>
   
  <div class="crm_field">
  <div class="crm_field_cell1"><label><?php esc_html_e('Google Sheets Access','gravity-forms-googlesheets-crm'); ?></label></div>
  <div class="crm_field_cell2">
  <?php if(!empty($info['access_token']) && !empty($info['refresh_token']) ) {
 ?>
  <div style="padding-bottom: 8px;" class="vx_green"><i class="fa fa-check"></i> <?php
                            $instance_url=!empty($info['email']) ? $info['email'] : 'Google Sheets';
  echo sprintf(__("Authorized Connection to %s on %s",'gravity-forms-googlesheets-crm'),'<code>'.$instance_url.'</code>',date('F d, Y h:i:s A',$info['time']));
        ?></div>
  <?php
  }else{
  
if(empty($client['client_id']) ){
echo '<b>'.__("Please Save Client ID, Secret and Redirect URL First",'gravity-forms-googlesheets-crm').'</b>';    
}else{      
 $link_href='https://accounts.google.com/o/oauth2/auth?response_type=code&access_type=offline&state='.urlencode($link."&".$this->id."_tab_action=get_token&id=".$id."&vx_nonce=".$nonce).'&client_id='.esc_html($client['client_id']).'&redirect_uri='.urlencode(esc_url($client['call_back'])).'&scope='.urlencode('https://www.googleapis.com/auth/spreadsheets https://www.googleapis.com/auth/drive.readonly email'); 
  
  ?>
  <a class="button button-default button-hero sf_login" id="vx_login_btn" data-id="<?php echo esc_html($client['client_id']) ?>" href="<?php echo $link_href ?>"> <i class="fa fa-lock"></i> <?php esc_html_e("Login with Google Sheets",'gravity-forms-googlesheets-crm'); ?></a>
  <div class="howto"><?php esc_html_e("after clicking button if you 'This app isn't verified' error then click on 'Advanced' and finally click 'Go to unsafe' button",'gravity-forms-googlesheets-crm'); ?></div>
  <?php
  } } 
  ?></div>
  <div class="clear"></div>
  </div>                  
    <?php if(isset($info['access_token'])  && $info['access_token']!="") {
  ?>
    <div class="crm_field">
  <div class="crm_field_cell1"><label><?php esc_html_e("Revoke Access",'gravity-forms-googlesheets-crm'); ?></label></div>
  <div class="crm_field_cell2">  <a class="button button-secondary" id="vx_revoke" href="<?php echo esc_url($link."&".$this->id."_tab_action=get_token&vx_nonce=".$nonce.'&id='.$id)?>"><i class="fa fa-unlock"></i> <?php esc_html_e("Revoke Access",'gravity-forms-googlesheets-crm'); ?></a>
  </div>
  <div class="clear"></div>
  </div> 
      <div class="crm_field">
  <div class="crm_field_cell1"><label><?php esc_html_e("Test Connection",'gravity-forms-googlesheets-crm'); ?></label></div>
  <div class="crm_field_cell2">      <button type="submit" class="button button-secondary" name="vx_test_connection"><i class="fa fa-refresh"></i> <?php esc_html_e("Test Connection",'gravity-forms-googlesheets-crm'); ?></button>
  </div>
  <div class="clear"></div>
  </div> 
  <?php
    }
  ?>
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_error_email"><?php esc_html_e("Notify by Email on Errors",'gravity-forms-googlesheets-crm'); ?></label></div>
  <div class="crm_field_cell2"><textarea name="crm[error_email]" id="vx_error_email" placeholder="<?php esc_html_e("Enter comma separated email addresses",'gravity-forms-googlesheets-crm'); ?>" class="crm_text" style="height: 70px"><?php echo isset($info['error_email']) ? esc_html($info['error_email']) : ""; ?></textarea>
  <span class="howto"><?php esc_html_e("Enter comma separated email addresses. An email will be sent to these email addresses if an order is not properly added to Google Sheets. Leave blank to disable.",'gravity-forms-googlesheets-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div>  

   

<p class="submit">
  <button type="submit" value="save" class="button-primary" title="<?php esc_html_e('Save Changes','gravity-forms-googlesheets-crm'); ?>" name="save"><?php esc_html_e('Save Changes','gravity-forms-googlesheets-crm'); ?></button></p>  
  </div>  

  <script type="text/javascript">
 

  jQuery(document).ready(function($){


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
     alert("<?php esc_html_e('Google Sheets Client ID Changed.Please save new changes first','gravity-forms-googlesheets-crm') ?>");   
    }    
    }
})

    $(document).on('click','#vx_revoke',function(e){
  
  if(!confirm('<?php esc_html_e('Notification - Remove Connection?','gravity-forms-googlesheets-crm'); ?>')){
  e.preventDefault();   
  }
  })   
  })
  </script>  