<h1 class="wp-heading-inline ap-header"><?php _e('Login Logs','login-sidebar-widget');?> </h3></h1>
<table width="100%" class="ap-table">
 <tr>
    <td colspan="2" align="right"><a href="<?php echo $empty_log_url;?>" class="button button-primary"><?php _e('Clear Login Log','login-sidebar-widget');?></a></td>
 </tr>
 <tr>
    <td colspan="2">&nbsp;</td>
 </tr>
 <tr>
    <td colspan="2">

    <table border="0" class="wp-list-table widefat fixed striped table-view-list">
        <thead>
         <tr>
            <td><strong><?php _e('IP','login-sidebar-widget');?></strong></td>
            <td><strong><?php _e('Message','login-sidebar-widget');?></strong></td>
            <td><strong><?php _e('Time','login-sidebar-widget');?></strong></td>
            <td><strong><?php _e('Status','login-sidebar-widget');?></strong></td>
          </tr>
        </thead>
        <tbody>
          <?php 
          if($data){
              foreach ( $data as $d ) { ?>
              <tr>
                <td><?php echo $d['ip'];?></td>
                <td><?php echo $d['msg'];?></td>
                <td><?php echo $d['l_added'];?></td>
                <td><?php echo $d['l_status'];?></td>
              </tr>
              <?php $cnt++; }
          } else { ?>
          <tr>
            <td colspan="4" align="center"><strong><?php _e('No records found','login-sidebar-widget');?></strong></td>
          </tr>
          <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td><strong><?php _e('IP','login-sidebar-widget');?></strong></td>
              <td><strong><?php _e('Message','login-sidebar-widget');?></strong></td>
              <td><strong><?php _e('Time','login-sidebar-widget');?></strong></td>
              <td><strong><?php _e('Status','login-sidebar-widget');?></strong></td>
            </tr>
          </tfoot>
        </table>

      <table width="100%">
         <tr>
            <td><p><?php $ap->paginate();?></p></td>
          </tr>
      </table>
    </td>
 </tr>
</table>
<table width="100%" class="ap-table">
    <tr>
    <td>
    Use <strong><a href="https://www.aviplugins.com/fb-login-widget-pro/" target="_blank">PRO</a></strong> version that has added security with <strong>Blocking IP</strong> after 5 wrong login attempts. <strong>Blocked IPs</strong> can be <strong>Whitelisted</strong> from admin panel or the <strong>Block</strong> gets automatically removed after <strong>1 Day</strong>.
    </td>
  </tr>
</table>