<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $current_user, $wppmfunction, $wpdb;
if (!($current_user->ID && $current_user->has_cap('manage_options'))) {
	exit;
}
$wppm_email_notificatins = get_option('wppm_email_notification');
$notification_types = $wppmfunction->get_email_notification_types();
?>
<span class="wppm-title">
	<?php echo esc_html_e('Email Notifications','taskbuilder');?>
</span>
<div class="wppm_padding_space"></div>
<table class="table table-striped table-hover wppm_email_notification_table">
  <tr>
    <th><?php echo esc_html_e('Notification Type','taskbuilder')?></th>
    <th><?php echo esc_html_e('Actions','taskbuilder')?></th>
  </tr>
  <?php foreach ( $wppm_email_notificatins as $key=>$email_template ) :
    $type  = $email_template['type'];
    $type  = isset($notification_types[$type]) ? $notification_types[$type] : '';
    ?>
    <tr>
      <td><?php echo htmlentities(esc_attr($type))?></td>
      <td>
        <div class="wppm_flex">
					<div onclick="wppm_get_edit_email_notification(<?php echo esc_attr($key)?>);" style="cursor:pointer;"><span><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/edit_01.svg'); ?>" alt="edit"></span></div>
        </div>
      </td>
    </tr>
	<?php endforeach;?>
</table>