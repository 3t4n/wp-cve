<?php
if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }                                        
 ?><h3><?php echo sprintf(__("Account ID# %d",'woocommerce-salesforce-crm'),esc_attr($id));
if($new_account_id != $id){
 ?> <a href="<?php echo esc_url($new_account); ?>" title="<?php esc_html_e('Add New Account','woocommerce-salesforce-crm'); ?>" class="add-new-h2"><?php esc_html_e("Add New Account",'woocommerce-salesforce-crm'); ?></a> 
 <?php
}
$name=$this->post('name',$info);    
 ?>
 <a href="<?php echo esc_url($link) ?>" class="add-new-h2" title="<?php esc_html_e('Back to Accounts','woocommerce-salesforce-crm'); ?>"><?php esc_html_e('Back to Accounts','woocommerce-salesforce-crm'); ?></a></h3>
  <div class="crm_fields_table">
    <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_name"><?php esc_html_e("Account Name",'woocommerce-salesforce-crm'); ?></label>
  </div>
  <div class="crm_field_cell2">
  <input type="text" name="crm[name]" value="<?php echo !empty($name) ? esc_attr($name) : 'Account #'.$id; ?>" id="vx_name" class="crm_text">

  </div>
  <div class="clear"></div>
  </div>
       <div class="crm_field">
  <div class="crm_field_cell1">
  <label for="vx_env"><?php esc_html_e('Environment','woocommerce-salesforce-crm'); ?></label>
  </div>
  <div class="crm_field_cell2">
<select name="crm[env]" class="crm_text" id="vx_env" data-save="no" <?php if( $api!='web' && !empty($info['access_token'])){ echo 'disabled="disabled"'; } ?> >
  <?php $envs=array(''=>__('Production','woocommerce-salesforce-crm'),'test'=>__('Sandbox','woocommerce-salesforce-crm'));
foreach($envs as $k=>$v){
    $sel='';
if(!empty($info['env']) && $info['env'] == $k){ $sel='selected="selected"'; }
echo '<option value="'.$k.'" '.$sel.'>'.$v.'</option>';
}
 ?>
 </select>
  </div>
  <div class="clear"></div>
  </div>
  
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_api"><?php esc_html_e("Integration Method",'woocommerce-salesforce-crm'); ?></label>
  </div>
  <div class="crm_field_cell2">
  <label for="vx_api"><input type="radio" name="crm[api]" value="api" id="vx_api" class="vx_tabs_radio" <?php if($this->post('api',$info) != "web"){echo 'checked="checked"';} ?>> <?php esc_html_e('API','woocommerce-salesforce-crm'); $this->tooltip($tooltips['vx_api']); ?></label>
  <label for="vx_web" style="margin-left: 15px;"><input type="radio" name="crm[api]" value="web" id="vx_web" class="vx_tabs_radio" <?php if($this->post('api',$info) == "web"){echo 'checked="checked"';} ?>> <?php esc_html_e('Web-to-Lead or Web-to-Case (use this if API is not enabled for your Org) ','woocommerce-salesforce-crm'); $this->tooltip($tooltips['vx_web']); ?></label> 
  </div>
  <div class="clear"></div>
  </div>
  <div class="vx_tabs" id="tab_vx_web" style="<?php if($this->post('api',$info) != "web"){echo 'display:none';} ?>">
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="org_id"><?php esc_html_e('Salesforce Org. ID','woocommerce-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2">
  <div class="vx_tr" >
  <div class="vx_td">
  <input type="password" id="org_id" name="crm[org_id]" class="crm_text" placeholder="<?php esc_html_e('Salesforce Organization ID','woocommerce-salesforce-crm'); ?>" value="<?php echo esc_attr($this->post('org_id',$info)); ?>">
  </div><div class="vx_td2">
  <a href="#" class="button vx_toggle_btn vx_toggle_key" title="<?php esc_html_e('Toggle Key','woocommerce-salesforce-crm'); ?>"><?php esc_html_e('Show Key','woocommerce-salesforce-crm') ?></a>
  </div></div>
  <span class="howto"><?php esc_html_e("in salesforce Go to Setup -> Company information -> Organization ID",'woocommerce-salesforce-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div> 
  
     <div class="crm_field">
  <div class="crm_field_cell1"><label for="org_url"><?php esc_html_e('Salesforce URL (optional)','woocommerce-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2">
  <input type="url" id="org_url" name="crm[org_url]" class="crm_text" placeholder="<?php esc_html_e('Keep it empty','woocommerce-salesforce-crm'); ?>" value="<?php echo esc_html($this->post('org_url',$info)); ?>">
  <span class="howto"><?php esc_html_e('Only set this url , if you do not receive data in salesforce, Copy your salesforce domain name with https from browser(e.g: https://my-instance.salesforce.com)','woocommerce-salesforce-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div> 
   
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="debug_email"><?php esc_html_e('Salesforce Debugging Emails','woocommerce-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2">
  <input type="text" name="crm[debug_email]" id="debug_email" placeholder="<?php esc_html_e('Debugging Email','woocommerce-salesforce-crm'); ?>" class="crm_text" value="<?php echo esc_attr($this->post('debug_email',$info)) ?>" />
  <span class="howto"><?php esc_html_e('Recommended - Salesforce will send notification about success or failure of lead/case to debug email.','woocommerce-salesforce-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div>   
  </div>
  <div class="vx_tabs" id="tab_vx_api" style="<?php if($this->post('api',$info) == "web"){echo 'display:none';} ?>">
  
  <div class="crm_field">
  <div class="crm_field_cell1"><label><?php esc_html_e('Salesforce Access','woocommerce-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2">
  <?php  if(isset($info['access_token'])  && $info['access_token']!="") {
  ?>
  <div style="padding-bottom: 8px;" class="vx_green"><i class="fa fa-check"></i> <?php
                            $instance_url=str_replace("https://","",$info["instance_url"]);
  echo sprintf(__("Authorized Connection to %s on %s",'woocommerce-salesforce-crm'),'<code>'.$instance_url.'</code>',date('F d, Y h:i:s A',$info['sales_token_time']));
        ?></div>
  <?php
  }else{
  $test_link='https://test.salesforce.com/services/oauth2/authorize?response_type=code&state='.urlencode($link."&".$this->id."_tab_action=get_token&id=".$id."&vx_nonce=".$nonce.'&vx_env=test').'&client_id='.$client['client_id'].'&redirect_uri='.urlencode($client['call_back']).'&scope='.urlencode('api refresh_token'); 
      
 $link_href='https://login.salesforce.com/services/oauth2/authorize?response_type=code&state='.urlencode($link."&".$this->id."_tab_action=get_token&id=".$id."&vx_nonce=".$nonce.'&vx_env=').'&client_id='.$client['client_id'].'&redirect_uri='.urlencode($client['call_back']).'&scope='.urlencode('api refresh_token'); 
 if(!empty($info['env'])){ $link_href=$test_link; }    
  ?>
  <a class="button button-default button-hero sf_login" target="_self" id="vx_login_btn" data-id="<?php echo esc_html($client['client_id']) ?>" href="<?php echo $link_href ?>" data-login="<?php echo $link_href ?>" data-test="<?php echo $test_link ?>"> <i class="fa fa-lock"></i> <?php esc_html_e("Login with Salesforce",'woocommerce-salesforce-crm'); ?></a>
  <?php
  }
  ?></div>
  <div class="clear"></div>
  </div>                  
    <?php if(isset($info['access_token'])  && $info['access_token']!="") {
  ?>
    <div class="crm_field">
  <div class="crm_field_cell1"><label><?php esc_html_e("Revoke Access",'woocommerce-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2">  <a class="button button-secondary" target="_self" id="vx_revoke" href="<?php echo esc_url($link."&".$this->id."_tab_action=get_token&vx_nonce=".$nonce.'&id='.$id);?>"><i class="fa fa-unlock"></i> <?php esc_html_e("Revoke Access",'woocommerce-salesforce-crm'); ?></a>
  </div>
  <div class="clear"></div>
  </div> 
      <div class="crm_field">
  <div class="crm_field_cell1"><label><?php esc_html_e("Test Connection",'woocommerce-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2">      <button type="submit" class="button button-secondary" name="vx_test_connection"><i class="fa fa-refresh"></i> <?php esc_html_e("Test Connection",'woocommerce-salesforce-crm'); ?></button>
  </div>
  <div class="clear"></div>
  </div> 
  <?php
    }
  ?>
  <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_error_email"><?php esc_html_e("Notify by Email on Errors",'woocommerce-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2"><textarea name="crm[error_email]" id="vx_error_email" placeholder="<?php esc_html_e("Enter comma separated email addresses",'woocommerce-salesforce-crm'); ?>" class="crm_text" style="height: 70px"><?php echo isset($info['error_email']) ? esc_attr($info['error_email']) : ""; ?></textarea>
  <span class="howto"><?php esc_html_e("Enter comma separated email addresses. An email will be sent to these email addresses if an order is not properly added to Salesforce. Leave blank to disable.",'woocommerce-salesforce-crm'); ?></span>
  </div>
  <div class="clear"></div>
  </div>  
   <div class="crm_field">
  <div class="crm_field_cell1"><label for="vx_custom_app_check"><?php esc_html_e("Salesforce App",'woocommerce-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2" style="padding-top: 5px"><label for="vx_custom_app_check"><input type="checkbox" name="crm[custom_app]" id="vx_custom_app_check" value="yes" <?php if($this->post('custom_app',$info) == "yes"){echo 'checked="checked"';} ?> ><?php echo __("Use Own Salesforce App",'woocommerce-salesforce-crm'); $this->tooltip($tooltips['vx_custom_app']); ?></label>
  </div>
  <div class="clear"></div>
  </div>
  <div id="vx_custom_app_div" style="<?php if($this->post('custom_app',$info) != "yes"){echo 'display:none';} ?>">
     <div class="crm_field">
  <div class="crm_field_cell1"><label for="app_id"><?php esc_html_e("Consumer Key",'woocommerce-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2">
     <div class="vx_tr">
  <div class="vx_td">
  <input type="password" id="app_id" name="crm[app_id]" class="crm_text" placeholder="<?php esc_html_e("Salesforce Consumer Key",'woocommerce-salesforce-crm'); ?>" value="<?php echo esc_attr($this->post('app_id',$info)); ?>">
  </div><div class="vx_td2">
  <a href="#" class="button vx_toggle_btn vx_toggle_key" title="<?php esc_html_e('Toggle Consumer Key','woocommerce-salesforce-crm'); ?>"><?php esc_html_e('Show Key','woocommerce-salesforce-crm') ?></a>
  
  </div></div>
</div>
  <div class="clear"></div>
  </div>
     <div class="crm_field">
  <div class="crm_field_cell1"><label for="app_secret"><?php esc_html_e("Consumer Secret",'woocommerce-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2">
       <div class="vx_tr" >
  <div class="vx_td">
 <input type="password" id="app_secret" name="crm[app_secret]" class="crm_text"  placeholder="<?php esc_html_e("Salesforce Consumer Secret",'woocommerce-salesforce-crm'); ?>" value="<?php echo esc_attr($this->post('app_secret',$info)); ?>">
  </div><div class="vx_td2">
  <a href="#" class="button vx_toggle_btn vx_toggle_key" title="<?php esc_html_e('Toggle Consumer Secret','woocommerce-salesforce-crm'); ?>"><?php esc_html_e('Show Key','woocommerce-salesforce-crm') ?></a>
  
  </div></div>
  </div>
  <div class="clear"></div>
  </div>
       <div class="crm_field">
  <div class="crm_field_cell1"><label for="app_url"><?php esc_html_e("Salesforce App URL",'woocommerce-salesforce-crm'); ?></label></div>
  <div class="crm_field_cell2"><input type="text" id="app_url" name="crm[app_url]" class="crm_text" placeholder="<?php esc_html_e("Salesforce App URL",'woocommerce-salesforce-crm'); ?>" value="<?php echo esc_attr($this->post('app_url',$info)); ?>"> 
    <div class="howto">
   <div>1: <?php echo $link."&".$this->id."_action=get_code";?></div>
   <div>2: https://www.crmperks.com/sf_auth/</div>
   <div>3: <?php echo sprintf(__("You can use any URL with a simple redirect code. You can find sample redirect code in %s",'woocommerce-salesforce-crm'),'plugin_folder/api/get_code.php'); ?></div>
  </div>
  </div>
  <div class="clear"></div>
  </div>
  </div>
  </div> 

  <button type="submit" value="save" class="button-primary" title="<?php esc_html_e('Save Changes','woocommerce-salesforce-crm'); ?>" name="save"><?php esc_html_e('Save Changes','woocommerce-salesforce-crm'); ?></button>  
  </div>  

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
     alert("<?php esc_html_e('Salesforce Client ID Changed.Please save new changes first','woocommerce-salesforce-crm') ?>");   
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
  
  if(!confirm('<?php esc_html_e('Notification - Remove Connection?','woocommerce-salesforce-crm'); ?>')){
  e.preventDefault();   
  }
  })   
  })
  </script>  