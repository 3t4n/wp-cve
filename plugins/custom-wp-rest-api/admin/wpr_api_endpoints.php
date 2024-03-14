<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
include_once('header.php');

$tab = $wpdb->prefix.WCRA_DB.'api_endpoints';

if(isset($_GET['a']) && isset($_GET['id']) ){
  if( intval(sanitize_text_field($_GET['id'])) > 0 ){
    $_get_base_by_id = wcra_get_base_by_id(intval(sanitize_text_field($_GET['id'])));
    $where = array('id' => intval(sanitize_text_field($_GET['id'] )));
    $update = $wpdb->delete( $tab , $where);
    if($update){

      $notification = "<strong>1</strong> Base has been deleted - <strong>$_get_base_by_id</strong>";
      wcra_save_recent_activity(array('txt' => $notification ));
      print('<script>window.location.href="admin.php?page=wcra_api_endpoints"</script>');
    }
    
  }
}

if(isset($_POST['wpr_save_end_settings'])){
    if(empty($_POST['wpr_set_base'])){
      echo '<script>alert("Base required!");</script>';
    }else if(empty($_POST['wpr_sec_set'])){
      echo '<script>alert("Secret required!");</script>';
    }else{
      $wpr_set_base = sanitize_text_field($_POST['wpr_set_base']);
      $q = "SELECT * FROM $tab WHERE base =  '".$wpr_set_base."' ";
      $get = $wpdb->get_row($q);
      if(!empty($get)){
        echo '<script>alert("Base already exists!");</script>';
      }else{ 
        $wpr_get_params = array();
        if(isset($_POST['wpr_params'])){
          $wpr_params = is_array($_POST['wpr_params']);
          if(empty($wpr_params)){
            foreach ($wpr_params as  $value) {
              if(!empty($value)){
                $wpr_get_params[] = sanitize_text_field($value);
              }
            }
          }
        }
        
        
        $mt_rand = mt_rand(100,10000);
        $callback = WCRA_DB.$wpr_set_base.'_callback';
        $permission_callback = WCRA_DB.$wpr_set_base.'_permission_callback';
        $basedata = array('callback' => $callback ) ;
        //print_r($wpr_get_params);die;
        $data = array('base' => $wpr_set_base ,'basedata' =>serialize($basedata),'param' => serialize($wpr_get_params) , 'secret' => sanitize_text_field($_POST['wpr_sec_set']));
        $inseret  = $wpdb->insert( $tab , $data );
        $notification = "<strong>1</strong> New base has been created - <strong>$wpr_set_base</strong>";
        wcra_save_recent_activity(array('txt' => $notification ));
                
      }
    }

    
}
$wpr_set_base = isset($_POST['wpr_set_base']) ? sanitize_text_field($_POST['wpr_set_base']) : '';


 ?>
  <div class="gsr_back_body">
 <div class="wraparea">
<h2><?php _e('New Endpoint URL'); ?></h2>

<form action="" method="post">
<table class="form-table" id="wpr_edpts_tab">
  <tr>
    <th class="row"><?php _e('Base'); ?> </th>
    <td><input type="text" name="wpr_set_base" value="<?php echo esc_attr($wpr_set_base); ?>" >
    </td>
  </tr>
  <tr>
    <th class="row"><?php _e('Select Secret'); ?> </th>
    <td>
    <div class="form-group">
      <label for="wpr_sec_set" class="control-label"></label>
      <select class="form-control" name="wpr_sec_set">
       <?php $_secret_list = wcra_secret_list();
      $_get_root_secret = wcra_get_root_secret();
        if(count($_secret_list)){
          echo _e('<option value="">Select</option>');
          foreach ($_secret_list as $key => $value) {
            echo '<option value="'.esc_attr($key).'">'.esc_attr($value).'</option>';
          }
         // echo '<option value="'.$_get_root_secret.'">Root</option>';
        }
       ?>
    </select>
   
    <div class="customtooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
    <span class="customtooltiptext">You can create your own secret <a href='?page=wcra_new_api'>here</a></span>
  </div>
    
    
    </div>
    </td>
  </tr>
</table>
<p class="submit"><input name="wpr_save_end_settings" id="submit" class="button button-primary" value="<?php _e('Add New'); ?>" type="submit"></p>
</form>

<h3><?php _e('Custom Endpoint/Routes URLs'); ?></h3>
<table class="wp-list-table widefat fixed striped posts" id="">
<thead><tr><td width="5%"><?php _e('#ID'); ?></td><td width="25"><?php _e('Secret Used'); ?></td><td width="50%"><?php _e('Endpoint'); ?></td><td width="10%"><?php _e('Filter Hook'); ?></td><td width="10%"><?php _e('Action'); ?></td></tr></thead>
<?php 
$get_endspts = wcra_endpoints_data();

if(count($get_endspts)){
  foreach ($get_endspts as $key => $value) {
    $params = unserialize($value->param);
    $params['secret_key'] = $value->secret;
    $callback = unserialize($value->basedata);
    $help_link = '<a class="page-title-action" href="#popup_endp_'.$value->id.'"><i class="fa fa-info-circle" aria-hidden="true"></i>Show Me</a>';
    $html = '<div id="popup_endp_'.$value->id.'" class="overlay popup_endp">
              <div class="popup">
                <h3>Put the below code snippet in your functions.php or any function page</h3>
                <a class="close" href="#">&times;</a>
                <p class="content codesnip" id="codesnipid_'.$value->id.'" style="overflow:hidden;">
                '.wcra_help_content($callback["callback"]).'
                </p>
                <a href="javascript:;" class="btn btn-info" onclick="copyToClipboard(\'#codesnipid_'.$value->id.'\')">Copy To Clipboard</a>
              </div>
            </div>';
  ?>
  <tr>
    <td><?php echo $value->id; ?></td>
    <td><?php echo wcra_get_username($value->secret) ? wcra_get_username($value->secret) : 'Root'; ?></td>
    <td><a href="<?php echo wcra_get_end_url($value->base , $params); ?>" target="_blank"><?php echo wcra_get_end_url($value->base , $params); ?></a></td>
    <td><?php echo $help_link; echo $html;?></td>
    <td>
      <?php if($value->base != 'wcra_test'){ ?>
    <a class="_delete_endpoints" href="<?php 
     echo add_query_arg( array(
          'a' => 1,
          'id' => esc_attr($value->id),
      ), admin_url('admin.php?page=wcra_api_endpoints') );
     ?>" class="btn btn-info">Delete</a>
   <?php } ?>
   </td>
   
  </tr>
  <?php
}

}else{
  echo '<tr><td colspan="5">no endpoints recorded!</td></tr>';

}?>
<tfoot><tr><td><?php _e('#ID'); ?></td><td></td><td><?php _e('Endpoint'); ?></td><td><?php _e('Filter Hook'); ?></td><td><?php _e('Action'); ?></td></tr></tfoot>
</table>
</div>
</div>
<script type="text/javascript">
  function copyToClipboard(element) {
        var $temp = jQuery("<input>");
        jQuery("body").append($temp);
        $temp.val(jQuery(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
        alert('Copied to clipboard!');
      }
</script>
