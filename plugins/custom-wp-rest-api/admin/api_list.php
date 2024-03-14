<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
include_once('header.php');
$_get_list_api_access = wcra_get_list_api_access();

global $wpdb;
if(isset($_GET['s']) && isset($_GET['id']) ){
  if( intval(sanitize_text_field($_GET['id'])) > 0 ){
    $data = array('Status' => intval(sanitize_text_field($_GET['s']) ));
    $where = array('id' => intval(sanitize_text_field($_GET['id']) ));
    $tab = $wpdb->prefix.WCRA_DB.'api_base';
    $update = $wpdb->update( $tab , $data , $where);
    if($update){
      $st = intval(sanitize_text_field($_GET['s'])) == 0 ? 'Activated' : 'Deactivated';
      $_get_email_by_id = wcra_get_email_by_id(intval(sanitize_text_field($_GET['id'])));
      $notification = "A Secret has been $st - <strong>$_get_email_by_id</strong>";
      wcra_save_recent_activity(array('txt' => $notification ));
      print('<script>window.location.href="admin.php?page=wcra_api_list"</script>');
    }
    
  }
}
$prefix = $wpdb->prefix.WCRA_DB;

 ?>
  <div class="gsr_back_body">
<div class="wraparea">
<h2 wp-heading-inline >Access Lists</h2>

<table class="wp-list-table widefat fixed striped posts">
<thead>
  <tr>
    <td>User Name</td>
    <td>Email</td>
  <!--   <td>Api Secret</td> -->
    <td>Created At</td>
    <td>Status</td>
    <td>Action</td>
  </tr>
</thead>

  <tbody>

   <?php if(count($_get_list_api_access)){
      foreach ($_get_list_api_access as $key => $value) {
        $staus = $value->Status == 0 ? '<i class="fa fa-circle" style="color:green;"></i>' : '<i class="fa fa-circle" style="color:red;" ></i>';
        $action = $value->Status == 0 ? '<a href="?page=wcra_api_list&s=1&id='.esc_attr($value->id).'" class="btn btn-info actctiuser">Deactivate</a>' : '<a href="?page=wcra_api_list&s=0&id='.esc_attr($value->id).'" class="btn btn-info actctiuser">Activate</a>';
        ?>
        <tr>
          <td><?php echo $value->Fullname; ?></td>
          <td><?php echo $value->Email; ?></td>
         <!--  <td><?php //echo $value->ApiSecret; ?></td> -->
          <td><?php echo $value->CreatedAt; ?></td>
          <td><?php echo $staus; ?></td>
          <td><?php echo $action; ?></td>
        </tr>
        <?php
      }

    }else{
      ?>
      <tr><td colspan="5" align="left">No Records</td></tr>
      <?php
      } ?>

  </tbody>
<tfoot>
  <tr>
    <td>User Name</td>
    <td>Email</td>
   <!--  <td>Api Secret</td> -->
    <td>Created At</td>
    <td>Status</td>
    <td>Action</td>
  </tr>
</tfoot>
</table>
</div>
</div>
<script type="text/javascript">
   jQuery('.actctiuser').on('click' , function(){
    if(confirm("Confirm!")){
      return true;
    }else{
      return false;
    }
   });
</script>
