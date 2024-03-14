<?php
if ( ! defined( 'WPINC' ) ) die;

$settings = get_option('sform_settings');
$admin_notices = ! empty( $settings['admin_notices'] ) ? esc_attr($settings['admin_notices']) : 'false';
$color = ! empty( $settings['admin_color'] ) ? esc_attr($settings['admin_color']) : 'default';
$notice = '';
$id = isset( $_REQUEST['form'] ) ? absint($_REQUEST['form']) : '';
global $wpdb;
$count_stored = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_shortcodes WHERE storing = '1' AND status != 'trash'");
$storing_notice = $count_stored == 1 && $id == '' ? '<span class="dashicons dashicons-warning" style="margin-left: 5px; opacity: 0.25; cursor: pointer; width: 30px; padding-right: 5px;"></span>' : '&nbsp;';
$update_notice = apply_filters( 'sform_update', $notice );
$page_forms = $wpdb->get_results( "SELECT id, name FROM {$wpdb->prefix}sform_shortcodes WHERE widget = '0' AND status != 'trash' ORDER BY name ASC", 'ARRAY_A' );
$widget_forms = $wpdb->get_results( "SELECT id, name FROM {$wpdb->prefix}sform_shortcodes WHERE widget != '0' AND status != 'trash' ORDER BY name ASC", 'ARRAY_A' );
$page_ids = array_column($page_forms, 'id');
$widget_ids = array_column($widget_forms, 'id');
$shortcode_ids = array_merge($page_ids, $widget_ids);
$all_forms = count($page_forms) + count($widget_forms);
?>

<div id="sform-wrap" class="sform">

<div id="new-release"><?php
  if ( !empty($update_notice) && $admin_notices == 'false' ) { echo $update_notice; }
  else { echo '&nbsp;'; }?>
</div>

<div class="full-width-bar <?php echo $color ?>"><h1 class="title <?php echo $color ?>"><span class="dashicons dashicons-email-alt responsive"></span><?php _e( 'Entries', 'simpleform' ); if ( $all_forms > 1 ) { echo apply_filters( 'hidden_submissions', $notice, $id ); ?> <div class="selector"><div id="wrap-selector" class="responsive"><?php echo _e( 'Select Form', 'simpleform' ) ?>:</div><div class="form-selector"><select name="form" id="form" class="<?php echo $color ?>"><option value="" <?php selected( $id, '' ); ?>><?php echo _e( 'All Forms', 'simpleform' ); ?></option><?php if ( $page_forms && $widget_forms ) {  echo '<optgroup label="'.esc_attr__( 'Embedded in page', 'simpleform' ).'">'; } foreach($page_forms as $form) { $form_id = $form['id']; $form_name = $form['name']; echo '<option value="'.$form_id.'" '.selected( $id, $form_id ) .'>'.$form_name.'</option>'; } if ( $page_forms && $widget_forms ) {  echo '</optgroup>'; } if ( $page_forms && $widget_forms ) {  echo '<optgroup label="'.esc_attr__( 'Embedded in widget area', 'simpleform' ).'">'; } foreach($widget_forms as $form) { $form_id = $form['id']; $form_name = $form['name']; echo '<option value="'.$form_id.'" '.selected( $id, $form_id ) .'>'.$form_name.'</option>'; } if ( $page_forms && $widget_forms ) {  echo '</optgroup>'; }?></select></div></div> <?php } ?></h1></div>

<?php
if ( $id == '' ) {
$where_form = " WHERE form != '0'";
$before_last_message = get_option("sform_before_last_message") != false ? get_option("sform_before_last_message") : '';
$message_type = isset( $_REQUEST['message'] ) && $_REQUEST['message'] == 'before' ? 'before' : '';
$last_message = $before_last_message && $message_type ? $before_last_message : get_option('sform_last_message');
$before_button = $before_last_message ? true : false;
}
else {
$where_form = " WHERE form = '". $id ."'";
$last_date = $wpdb->get_var("SELECT date FROM {$wpdb->prefix}sform_submissions WHERE form = '$id' ORDER BY date DESC LIMIT 1");
$before_last_date = $last_date ? $wpdb->get_var("SELECT date FROM {$wpdb->prefix}sform_submissions WHERE form = '$id' ORDER BY date DESC LIMIT 1 OFFSET 1") : '';
$form_last_message = get_option("sform_last_{$id}_message") != false ? explode('#', get_option("sform_last_{$id}_message") ) : '';
$before_last_message = get_option("sform_before_last_{$id}_message") != false ? explode('#', get_option("sform_before_last_{$id}_message") ) : '';
$forwarded_last_message = get_option("sform_forwarded_last_{$id}_message") != false ? explode('#', get_option("sform_forwarded_last_{$id}_message") ) : '';
$forwarded_before_last_message = get_option("sform_forwarded_before_last_{$id}_message") != false ? explode('#', get_option("sform_forwarded_before_last_{$id}_message") ) : '';
$direct_last_message = get_option("sform_direct_last_{$id}_message") != false ? explode('#', get_option("sform_direct_last_{$id}_message") ) : '';
$direct_before_last_message = get_option("sform_direct_before_last_{$id}_message") != false ? explode('#', get_option("sform_direct_before_last_{$id}_message") ) : '';
$moved_last_message = get_option("sform_moved_last_{$id}_message") != false ? explode('#', get_option("sform_moved_last_{$id}_message") ) : '';
$moved_before_last_message = get_option("sform_moved_before_last_{$id}_message") != false ? explode('#', get_option("sform_moved_before_last_{$id}_message") ) : '';
$last_message_timestamp = $form_last_message && is_numeric($form_last_message[0]) ? $form_last_message[0] : '';
$before_last_message_timestamp = $before_last_message && is_numeric($before_last_message[0]) ? $before_last_message[0] : '';
$forwarded_last_message_timestamp = $forwarded_last_message && is_numeric($forwarded_last_message[0]) ? $forwarded_last_message[0] : '';
$forwarded_before_last_message_timestamp = $forwarded_before_last_message && is_numeric($forwarded_before_last_message[0]) ? $forwarded_before_last_message[0] : '';
$direct_last_message_timestamp = $direct_last_message && is_numeric($direct_last_message[0]) ? $direct_last_message[0] : '';
$direct_before_last_message_timestamp = $direct_before_last_message && is_numeric($direct_before_last_message[0]) ? $direct_before_last_message[0] : '';
$moved_last_message_timestamp = $moved_last_message && is_numeric($moved_last_message[0]) ? $moved_last_message[0] : '';
$moved_before_last_message_timestamp = $moved_before_last_message && is_numeric($moved_before_last_message[0]) ? $moved_before_last_message[0] : '';
$dates = array();
$dates[$last_message_timestamp] = $last_message_timestamp && isset($form_last_message[1]) ? $form_last_message[1] : '';
$dates[$before_last_message_timestamp] = $before_last_message_timestamp && isset($before_last_message[1]) ? $before_last_message[1] : '';
$dates[$forwarded_last_message_timestamp] = $forwarded_last_message_timestamp && isset($forwarded_last_message[1]) ? $forwarded_last_message[1] : '';
$dates[$forwarded_before_last_message_timestamp] = $forwarded_before_last_message_timestamp && isset($forwarded_before_last_message[1]) ? $forwarded_before_last_message[1] : '';
$dates[$direct_last_message_timestamp] = $direct_last_message_timestamp && isset($direct_last_message[1]) ? $direct_last_message[1] : '';
$dates[$direct_before_last_message_timestamp] = $direct_before_last_message_timestamp && isset($direct_before_last_message[1]) ? $direct_before_last_message[1] : '';
$dates[$moved_last_message_timestamp] = $moved_last_message_timestamp && isset($moved_last_message[1]) ? $moved_last_message[1] : '';
$dates[$moved_before_last_message_timestamp] = $moved_before_last_message_timestamp && isset($moved_before_last_message[1]) ? $moved_before_last_message[1] : '';
// Remove empty array elements
$dates = array_filter($dates);
$message_type = isset( $_REQUEST['message'] ) && $_REQUEST['message'] == 'before' ? 'before' : '';
$before_button = $before_last_date && strtotime($before_last_date) && array_key_exists(strtotime($before_last_date), $dates)  ? true : false;
if ( $last_date && array_key_exists(strtotime($last_date), $dates) ) { $last_message = $message_type ? $dates[strtotime($before_last_date)] : $dates[strtotime($last_date)]; } 
else { $last_message = $last_date ? '<div style="line-height:18px;">' . __('Data not available due to entries moved to other form', 'simpleform') . '</div>' : ''; }
}

echo '<div id="page-description" class="submissions-list overview">';

if ( has_action( 'submissions_list' ) ):
  do_action( 'submissions_list', $id, $shortcode_ids, $last_message );
else:
 if ( $id == '' || in_array($id, $shortcode_ids) ) {
      global $wpdb;
      $where_day = 'AND date >= UTC_TIMESTAMP() - INTERVAL 24 HOUR';
      $where_week = 'AND date >= UTC_TIMESTAMP() - INTERVAL 7 DAY';
      $where_month = 'AND date >= UTC_TIMESTAMP() - INTERVAL 30 DAY';
      $where_year = 'AND date >= UTC_TIMESTAMP() - INTERVAL 1 YEAR';
      $where_submissions = defined('SIMPLEFORM_SUBMISSIONS_NAME') ? "AND object != '' AND object != 'not stored'" : '';
      $count_all = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions $where_form $where_submissions AND hidden = '0'");
      $count_last_year = $count_all ? $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions $where_form $where_year $where_submissions AND hidden = '0'") : '0';
      $count_last_month = $count_last_year ? $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions $where_form $where_month $where_submissions AND hidden = '0'") : '0';
      $count_last_week = $count_last_month ? $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions $where_form $where_week $where_submissions AND hidden = '0'") : '0';
      $count_last_day = $count_last_week ? $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions $where_form $where_day $where_submissions AND hidden = '0'") : '0';
      $total_received = $count_all;
      $string1 = __('Submissions data is not stored in the WordPress database by default.', 'simpleform' );
      $string2 = __('Submissions data is not stored in the WordPress database', 'simpleform' );
      $string3 = __('We have designed SimpleForm to be a minimal, lightweight, fast and privacy-respectful plugin, so that it does not interfere with your site performance and can be easily managed.', 'simpleform' );
      $string4 = __('You can enable this feature with the <b>SimpleForm Contact Form Submissions</b> addon activation.', 'simpleform' );
      $string5 = __('If you want to keep a copy of your messages, you can add this feature with the <b>SimpleForm Contact Form Submissions</b> addon.', 'simpleform' );
      $string6 = __('You can find it in the WordPress.org plugin repository.', 'simpleform' );
      $string7 = __('By default, only the last message is temporarily stored.', 'simpleform' );
      $string8 = __('Therefore, it is recommended to verify the correct SMTP server configuration in case of use, and always keep the notification email enabled, if you want to be sure to receive messages.', 'simpleform' );
      $string9 = __('You can enable this feature by activating the <b>SimpleForm Contact Form Submissions</b> addon.', 'simpleform' );
      $string10 = __(' Go to the Plugins page.', 'simpleform' );
      $moved_from = " WHERE moved_from = '". $id ."'";
      $count_moved_all = $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions $moved_from $where_submissions AND hidden = '0'");
      $total_received .= $id != '' && $count_moved_all ? '&nbsp;+&nbsp;' . $count_moved_all . '<span class="dashicons dashicons-migrate" style="line-height: 40px; padding-left: 3px;"></span>' : '';
      $count_moved_last_year = $count_moved_all ? $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions $moved_from $where_year $where_submissions AND hidden = '0'") : '';
      $count_last_year .= $id != '' && $count_moved_last_year ? '&nbsp;+&nbsp;' . $count_moved_last_year . '<span class="dashicons dashicons-migrate" style="line-height: 40px; padding-left: 3px;"></span>' : '';
      $count_moved_last_month = $count_moved_last_year ? $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions $moved_from $where_month $where_submissions AND hidden = '0'") : '';
      $count_last_month .= $id != '' && $count_moved_last_month ? '&nbsp;+&nbsp;' . $count_moved_last_month . '<span class="dashicons dashicons-migrate" style="line-height: 40px; padding-left: 3px;"></span>' : '';
      $count_moved_last_week = $count_moved_last_month ? $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions $moved_from $where_week $where_submissions AND hidden = '0'") : '';
      $count_last_week .= $id != '' && $count_moved_last_week ? '&nbsp;+&nbsp;' . $count_moved_last_week . '<span class="dashicons dashicons-migrate" style="line-height: 40px; padding-left: 3px;"></span>' : '';
      $count_moved_last_day = $count_moved_last_week ? $wpdb->get_var("SELECT COUNT(id) FROM {$wpdb->prefix}sform_submissions $moved_from $where_day $where_submissions AND hidden = '0'") : '';
      $count_last_day .= $id != '' && $count_moved_last_day ? '&nbsp;+&nbsp;' . $count_moved_last_day . '<span class="dashicons dashicons-migrate" style="line-height: 40px; padding-left: 3px;"></span>' : '';
?>
	 
<div><ul id="submissions-data"><li class="type"><span class="label"><?php _e( 'All', 'simpleform' ); ?></span><span class="value"><?php echo $total_received ?></span></li><li class="type"><span class="label"><?php _e( 'This Year', 'simpleform' ); ?></span><span class="value"><?php echo $count_last_year ?></span></li><li class="type"><span class="label"><?php _e( 'Last Month', 'simpleform' ); ?></span><span class="value"><?php echo $count_last_month ?></span></li><li class="type"><span class="label"><?php _e( 'Last Week', 'simpleform' ); ?></span><span class="value"><?php echo $count_last_week ?></span></li><li><span class="label"><?php _e( 'Last Day', 'simpleform' ); ?></span><span class="value"><?php echo $count_last_day ?></span></li></ul></div>

<?php
    $plugin_file = 'simpleform-contact-form-submissions/simpleform-submissions.php';
    $admin_url = is_network_admin() ? network_admin_url( 'plugins.php' ) : admin_url( 'plugins.php' );
	if ( $last_message ) {
      $split_mail = explode('&nbsp;&nbsp;&lt;&nbsp;', $last_message);
	  $email = isset($split_mail[1]) ? explode('&nbsp;&gt;', $split_mail[1])[0] : '';
	  $subject_separator = __('Subject', 'simpleform') . ':</td><td>';
	  $split_subject = explode($subject_separator, $last_message);
	  $subject = isset($split_subject[1]) ? explode('</td>', $split_subject[1])[0] : '';
      $separator = ! empty($subject) ? '?' : '';
      /* translators: indicates a "reply" to a message */
      $mailsubject = ! empty($subject) ? 'subject=' . __('Re: ', 'simpleform' ) . str_replace(' ', '%20', $subject) : '';
      $reply = ! empty($email) ? '<span id="reply-message" class="'. $color .'"><a href="mailto:'. strip_tags($email) . $separator . $mailsubject .'"><span class="icon dashicons dashicons-share-alt2"></span><span class="text unseen"> ' . __('Reply', 'simpleform' ) . '</span></a></span>' : '';
      $message_heading = $message_type ? __('Message Before Last', 'simpleform' ) : __('Last Message', 'simpleform' ); 	
      $form_arg = isset( $_REQUEST['form'] ) && !empty( $_REQUEST['form'] ) ? '&form=' . absint($_REQUEST['form']) : '';
	  $before_arg = $message_type ? '' : '&message=before';	
      $prev_button = ! $message_type ? '<div id="before-link"><a class="'. $color .'" href="'. admin_url('admin.php?page=sform-entries') . $form_arg . $before_arg . '"><span class="icon dashicons dashicons-arrow-left-alt2"></span><span class="text unseen"> ' . __('Before Last', 'simpleform' ) . '</span></a></div>' : '';
      $next_button = $message_type ? '<div id="last-link"><a class="'. $color .'" href="'. admin_url('admin.php?page=sform-entries') . $form_arg . $before_arg . '"><span class="icon dashicons dashicons-arrow-right-alt2"></span><span class="text unseen"> ' . __('Last', 'simpleform' ) . '</span></a></div>' : '';
      $messages_nav = $before_button ? '<div id="navigation-buttons">' . $prev_button . $next_button . '</div>' : '';
	  echo '<div id="last-submission"><h3><span class="dashicons dashicons-buddicons-pm"></span>'.$message_heading.$reply .'</h3>'.	stripslashes(wpautop($last_message)) . $messages_nav . '</div>';
      if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {		
	    echo '<div id="submissions-notice" class="unseen"><h3><span class="dashicons dashicons-editor-help"></span>'. __('Before you go crazy looking for the received messages', 'simpleform' ).'</h3>'. __( 'Submissions data is not stored in the WordPress database. We have designed SimpleForm to be a minimal, lightweight, fast and privacy-respectful plugin, so that it does not interfere with your site performance and can be easily managed. If you want to keep a copy of your messages, you can add this feature with the <b>SimpleForm Contact Form Submissions</b> addon. You can find it in the WordPress.org plugin repository. By default, only the last message is temporarily stored. Therefore, it is recommended to verify the correct SMTP server configuration in case of use, and always keep the notification email enabled, if you want to be sure to receive messages.', 'simpleform' ) .'</div>'; 	
	  }
	  else {
        if ( ! class_exists( 'SimpleForm_Submissions' ) ) {	
	      echo '<div id="submissions-notice" class="unseen"><h3><span class="dashicons dashicons-editor-help"></span>'. __('Before you go crazy looking for the received messages', 'simpleform' ).'</h3>'. __('Submissions data is not stored in the WordPress database by default. We have designed SimpleForm to be a minimal, lightweight, fast and privacy-respectful plugin, so that it does not interfere with your site performance and can be easily managed. You can enable this feature by activating the <b>SimpleForm Contact Form Submissions</b> addon. Go to the Plugins page.', 'simpleform' ) .'</div>';	
	    }
	  }
	}
	else  {
	$empty_message = $count_moved_all ? sprintf( _n( '%s message has been moved to other form', '%s messages have been moved to other form', $count_moved_all, 'simpleform' ), $count_moved_all ) : __('So far, no message has been received yet!', 'simpleform' );	
      if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {		
	    echo '<div id="empty-submission"><h3><span class="dashicons dashicons-info"></span>'. __('Empty Inbox', 'simpleform' ).'</h3><b>'. $empty_message .'</b><p>'. sprintf( __('Please note that submissions data is not stored in the WordPress database by default. We have designed SimpleForm to be a minimal, lightweight, fast and privacy-respectful plugin, so that it does not interfere with your site performance and can be easily managed. If you want to keep a copy of your messages, you can add this feature with the <a href="%s" target="_blank">SimpleForm Contact Form Submissions</a> addon. You can find it in the WordPress.org plugin repository.', 'simpleform' ), esc_url( 'https://wordpress.org/plugins/simpleform-contact-form-submissions/' ) ).'</div>';
	  }
	  else {
        if ( ! class_exists( 'SimpleForm_Submissions' ) ) {	
          echo '<div id="empty-submission"><h3><span class="dashicons dashicons-info"></span>'. __('Empty Inbox', 'simpleform' ).'</h3>'. $empty_message .'<p>'.sprintf( __('Submissions data is not stored in the WordPress database by default. We have designed SimpleForm to be a minimal, lightweight, fast and privacy-respectful plugin, so that it does not interfere with your site performance and can be easily managed. You can enable this feature with the <b>SimpleForm Contact Form Submissions</b> addon activation. Go to the <a href="%s">Plugins</a> page.', 'simpleform' ), esc_url( $admin_url ) ) . '</div>';
	    }
	  }
	}
  }
  else { ?>
    <span><?php _e('It seems the form is no longer available!', 'simpleform' ) ?></span><p><span class="wp-core-ui button unavailable <?php echo $color ?>"><a href="<?php echo menu_page_url( 'sform-entries', false ); ?>"><?php _e('Reload the Submissions page','simpleform') ?></a></span><span class="wp-core-ui button unavailable <?php echo $color ?>"><a href="<?php echo menu_page_url( 'sform-creation', false ); ?>"><?php _e('Add New Form','simpleform') ?></a></span><span class="wp-core-ui button unavailable <?php echo $color ?>"><a href="<?php echo self_admin_url('widgets.php'); ?>"><?php _e('Activate SimpleForm Contact Form Widget','simpleform') ?></a></span></p>
  <?php }
  endif;
  echo '</div>';
  ?>

</div>