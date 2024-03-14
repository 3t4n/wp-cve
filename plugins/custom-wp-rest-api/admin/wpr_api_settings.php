<?php 
ob_start();
global $current_user;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
include_once('header.php');
if(isset($_GET['reset'])){
  if(sanitize_text_field($_GET['reset']) == 1){
    wcra_reset_deafault_settings();
    //exit( wp_redirect( admin_url( 'admin.php?page=wcra_api_settings' ) ) ); 
  }
}

$_get_settings = wcra_get_settings();

if(isset($_POST['wpr_save_settings'])){

  $nonce = sanitize_text_field($_REQUEST['udate_flds_non']);
  if ( ! wp_verify_nonce( $nonce, 'udate_flds' ) ) {
    echo '<script>alert("Invaid request");</script>';
  }else{
    if(!isset($_POST['wpr_set_req_check'])){
      $_get_settings['wpr_set_req_check'] = 0 ;
    }else{
      $_get_settings['wpr_set_req_check'] = 1 ;
    }
    if(!isset($_POST['wpr_set_auth_check'])){
      $_get_settings['wpr_set_auth_check'] = 0 ;
    }else{
      $_get_settings['wpr_set_auth_check'] = 1 ;
    }
    if(!isset($_POST['wpr_set_enb_api'])){
      $_get_settings['wpr_set_enb_api'] = 0 ;
    }else{
      $_get_settings['wpr_set_enb_api'] = 1 ;
    }
    if(!isset($_POST['wpr_set_auth_check_by_admin'])){
      $_get_settings['wpr_set_auth_check_by_admin'] = 0 ;
    }else{
      $_get_settings['wpr_set_auth_check_by_admin'] = 1 ;
    }
    
    if(empty($_POST['wpr_set_end_slug'])){
      $_get_settings['wpr_set_end_slug'] = 'wpapi';
    }else{
      $_get_settings['wpr_set_end_slug'] = sanitize_text_field($_POST['wpr_set_end_slug']);
    }
  
    if(empty($_POST['wpr_set_ver']) || is_numeric(sanitize_text_field($_POST['wpr_set_ver'])) === false ){
      $_get_settings['wpr_set_ver'] = 1;
    }else{
      $_get_settings['wpr_set_ver'] = sanitize_text_field($_POST['wpr_set_ver']);
    }
  
    
    update_option('wcra_settings_meta' , $_get_settings);
    $notification = "Settings Saved!";
  
    //wcra_save_recent_activity(array('txt' => $notification ));
    
    echo '<script>alert("Settings Saved!");</script>';
  } 
}

$wpr_set_backlog  = $wpr_set_recent_activity_dur = '';
$wpr__settings = get_option('wcra_settings_meta');
$wpr_set_req_check = esc_attr($wpr__settings['wpr_set_req_check']) == 1 ? 'checked' : '';
$wpr_set_ver = esc_attr($wpr__settings['wpr_set_ver']);
$wpr_set_end_slug = esc_attr($wpr__settings['wpr_set_end_slug']);
$wpr_set_auth_check = esc_attr($wpr__settings['wpr_set_auth_check']) == 1 ? 'checked' : '';
$wpr_set_enb_api = esc_attr($wpr__settings['wpr_set_enb_api']) == 1 ? 'checked' : '';
$wpr_set_auth_check_by_admin = esc_attr($wpr__settings['wpr_set_auth_check_by_admin']) == 1 ? 'checked' : '';


 ?>
 <div class="gsr_back_body">
 <div class="wraparea">
 <h2><?php _e( 'Settings' ); ?></h2>
<form action="" method="post">
<table class="form-table wpr_set_table_wrap">
  <input type="hidden" value="<?php echo wp_create_nonce( 'udate_flds' );?>" name="udate_flds_non">
 <tr>
    <th class="row"><?php _e( 'Enable Api' ); ?></th>
    <td>
      <label class="switch">
        <input type="checkbox" name="wpr_set_enb_api" value="1" <?php echo esc_attr($wpr_set_enb_api); ?>>
        <span class="slider round"></span>
      </label>
      <a href="#" data-toggle="tooltip" title="This option should be turn on .All custom endpoints/routes will be working if it is enabled."><i class="fa fa-question-circle" aria-hidden="true"></i></a>
    </td>
  </tr>
  <tr>
    <th class="row"><?php _e( "Enable Request's Log Capturing" ); ?></th>
    <td>
     <label class="switch">
        <input type="checkbox" name="wpr_set_req_check" value="1" <?php echo esc_attr($wpr_set_req_check); ?>>
        <span class="slider round"></span>
      </label>
      <a href="#" data-toggle="tooltip" title="All request/response will be recorded by the system, if it is turned on.You can see the logs in LOG tab"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
    </td>
  </tr>
  <tr>
    <th class="row"><?php _e( 'Enable Api Authentication with Secret Key' ); ?></th>
    <td>
      <label class="switch">
        <input type="checkbox" name="wpr_set_auth_check" value="1" <?php echo esc_attr($wpr_set_auth_check); ?>>
        <span class="slider round"></span>
      </label>
      <a href="#" data-toggle="tooltip" title="If this option is disabled, without secret key the routes will be working."><i class="fa fa-question-circle" aria-hidden="true"></i></a>
    </td>
  </tr>
  <tr>
    <th class="row"><?php _e('Api Version'); ?></th>
    <td><input type="number" name="wpr_set_ver" value="<?php echo esc_attr($wpr_set_ver); ?>" min="1"></td>
  </tr>

  <tr>
    <th class="row"><?php _e('Api Namespace'); ?></th>
    <td><input type="text" name="wpr_set_end_slug" value="<?php echo esc_attr($wpr_set_end_slug); ?>" > <a href="#" data-toggle="tooltip" title="Namespaces are the slugs in the URL before the endpoint.For eg. if your endpoint URL is 'www.domain.com/wp-json/<strong>wcra</strong>/v1/myapp/'  .
Then <strong>'wcra'</strong> is your NAMESAPCE. and it would be remain same on all the endpoint URL."><i class="fa fa-question-circle" aria-hidden="true"></i></a></td>
  </tr>
  <tr>
    <th class="row"><?php _e('Keep Recent Activity Log For'); ?></th>
    <td>
       <div class="form-group">
          <label for="wpr_set_recent_activity_dur" class="control-label"></label>
          <select class="form-control" name="wpr_set_recent_activity_dur">
           <?php echo wcra_recent_activity_options_html($wpr_set_recent_activity_dur); ?>

          </select>
    </div>
    </td>
  </tr>

</table>
<p class="submit"><input name="wpr_save_settings" id="submit" class="button button-primary" value="<?php _e('Save Settings'); ?>" type="submit">
<input name="wpr_reset_settings" id="wpr_reset_settings" class="button button-primary" value="<?php _e('Reset Default Settings'); ?>" type="button"></p>
</form>
</div>
</div>

