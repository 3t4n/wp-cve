<?php

/**
 * Provide a admin header view for the plugin
 *
 * @link       http://www.webfactoryltd.com
 * @since      0.1
 */

if (!defined('WPINC')) {
	die;
}
?>

<div class="signals-cnt-fix">
	<div class="signals-fix-wp38">
		<div class="signals-header signals-clearfix">
			<img src="<?php echo esc_url(CSMM_URL); ?>/framework/admin/img/mm-icon-dark.png" class="signals-logo">
			<p>
				<strong><?php esc_attr_e( 'Minimal Coming Soon &amp; Maintenance Mode', 'minimal-coming-soon-maintenance-mode' ); ?></strong>
				<span><?php esc_attr_e( 'by', 'minimal-coming-soon-maintenance-mode' ); ?> <a href="http://www.webfactoryltd.com/" target="_blank"><?php esc_attr_e( 'WebFactory Ltd', 'minimal-coming-soon-maintenance-mode' ); ?></a></span>
			</p>

<?php
  if ($signals_csmm_options['status']== '1') {
    $action_url = add_query_arg(array('action' => 'csmm_change_status', 'new_status' => 'disabled', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));
    $action_url = wp_nonce_url($action_url, 'csmm_change_status');
  } else {
    $action_url = add_query_arg(array('action' => 'csmm_change_status', 'new_status' => 'enabled', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));
    $action_url = wp_nonce_url($action_url, 'csmm_change_status');
  }

  /*
  <div id="header-status" title="Click to change the Coming Soon status" data-action-url="<?php echo $action_url; ?>">
          <label for="">Coming Soon Status:</label> <div class="csmm-status-wrapper <?php echo ($signals_csmm_options['status'] != '1')? 'off': 'on'; ?>"><span class="csmm-status-btn csmm-status-off">OFF</span><span class="csmm-status-btn csmm-status-on">ON</span></div>
        </div>
        */
?>
<div id="header-right">
        <a href="#pro" class="csmm-change-tab">Get a lifetime <b>PRO</b> license<br>for only $39</a>
      </div>
		</div><!-- .signals-header -->
