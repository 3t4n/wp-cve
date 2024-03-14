<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
include_once('header.php');
$_get_settings = wcra_get_settings();
$days = esc_attr($_get_settings['wpr_set_recent_activity_dur']);
$_get_recent_activity = wcra_get_recent_activity();

if(isset($_GET['clearall'])){
  if(sanitize_text_field($_GET['clearall']) == 1){
    wcra_clear_recent_activity();
   // exit( wp_redirect( admin_url( 'admin.php?page=wcra_api_recent_activity' ) ) ); 
  }
}

 ?>
  <div class="gsr_back_body">
<div class="wraparea">
<h2><?php esc_html_e( 'Recent Activity ( From last '.$days.' Days)' ); ?><a href="<?php echo admin_url('admin.php?page=wcra_api_recent_activity&clearall=1'); ?>" style="float: right;text-decoration: none;">Clear All</a></h2>
<table class="wp-list-table widefat fixed striped posts">
<thead>
  <tr>
    <td width="70%"><?php esc_html_e( 'Activity' ); ?></td>
    <td width="30%"><?php esc_html_e( 'Date Time' ); ?></td>
  </tr>
</thead>

  <tbody>

   <?php if(count($_get_recent_activity)){
      foreach ($_get_recent_activity as $key => $value) {
        ?>
        <tr>
          <td><?php echo $value->notification; ?></td>
          <td><?php echo esc_attr(date('j M, Y g:i A' , strtotime($value->date_time) )); ?><strong>( <?php echo esc_attr(human_time_diff( strtotime($value->date_time), strtotime(date('Y-m-d H;i:s')) )) . ' ago'; ?>)</strong></td>
        </tr>
        <?php
      }

    }else{
      ?>
      <tr><td colspan="5" align="left"><?php esc_html_e( 'No Recent Activity' ); ?></td></tr>
      <?php
      } ?>

  </tbody>
<tfoot>
  <tr>
    <td><?php esc_html_e( 'Activity' ); ?></td>
    <td><?php esc_html_e( 'Date Time' ); ?></td>
  </tr>
</tfoot>
</table>
</div>
</div>
