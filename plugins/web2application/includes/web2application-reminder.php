<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<style type="text/css">
.form-control {
    width: 400px;
}
</style>

<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $w2a_options;

//if nonces ok	
if (isset($_POST['save_reminder'])) {
    if(wp_verify_nonce($_REQUEST['w2a_push_submit_post'], 'w2a_push')){
		
		$enable_reminder = 0;
		if( isset( $_REQUEST['enable_reminder'] ) ) {
			$enable_reminder = 1;
		}
		
		$reminder_days = $_REQUEST['reminder_days'];
		$reminder_push_link = $_REQUEST['reminder_push_link'];
		$reminder_push_title = $_REQUEST['reminder_push_title'];
		$reminder_body_push = stripslashes( $_REQUEST['reminder_body_push'] );
		// $reminder_push_schedule = $_REQUEST['reminder_push_schedule'];
		// $reminder_push_date = $_REQUEST['reminder_push_date'];
		$reminder_push_time = $_REQUEST['reminder_push_time'];
		
		$w2a_settings = array(
			'w2a_reminder_body_push' => $reminder_body_push,
			'w2a_reminder_push_link' => $reminder_push_link,
			'w2a_reminder_push_title' => $reminder_push_title,
			'w2a_reminder_days' => $reminder_days,
			// 'w2a_reminder_push_schedule' => $reminder_push_schedule,
			// 'w2a_reminder_push_date' => $reminder_push_date,
			'w2a_reminder_push_time' => $reminder_push_time,
			'w2a_enable_reminder' => $enable_reminder,
		);
		
		if( !$w2a_options ) {
		    update_option('w2a_settings', $w2a_settings);
		}
		else {
		    
            $w2a_options = array_merge($w2a_options, $w2a_settings);
            update_option('w2a_settings', $w2a_options);
        }
		
		/* Set CRON */
		if( isset( $_REQUEST['enable_reminder'] ) && !empty( $reminder_push_time ) ) {
			if ( ! wp_next_scheduled( 'w2a_reminder_cron' ) ) {
				$cron_time = $reminder_push_time.':00';
				wp_schedule_event( strtotime($cron_time), 'daily', 'w2a_reminder_cron' );
			}
		}
		/* Set CRON */
	}
}

$url = 'https://www.web2application.com/w2a/api-process/get_app_id.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key']).'&version=new';
$appId = file_get_contents($url);

// check
if ($appId == "") {
	// init curl
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$appId = curl_exec($ch);
	curl_close($ch);
}

// check
$disabled = ($appId == 'Wrong API. Please Check Your API Key' || trim($w2a_options['w2a_api_key']) == "") ? true : false;

// check
if ($appId != 'Wrong API. Please Check Your API Key' && is_numeric($appId)) {
	// get app marketing tools
	$url 		= 'https://www.web2application.com/w2a/api-process/get_app_data.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key'].'&version=new');
	$app 		= file_get_contents($url);
	//$row 		= json_decode($app);
	
	// check
	if ($app == "") {
		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$app = curl_exec($ch);
		curl_close($ch);
	}
	
	// decode
	$row = json_decode($app);
}

?>
<div class="wrap">

    <h2><?php _e('Cart Reminder Settings', 'web2application'); ?></h2>
	
	<div id="content">
		<form method="post" id="reminder_setting_form">
			<table class="form-table">
				<tbody>
					<p><?php _e('On this screen you can set reminders for customers who added products to the shopping cart and did not make the actual purchase.','web2application'); ?></p>
					<p><?php _e('The reminder message will be sent by email and push message to the customer if the API for personal push notifications is set in the main system.','web2application'); ?></p>
					<tr>
						<th scope="row"><label for="enable_reminder"><?php _e('Enable Reminder','web2application'); ?></label></th>
						<td><?php if ( isset( $w2a_options['w2a_enable_reminder'] ) && $w2a_options['w2a_enable_reminder'] != "") { ?>
							<input type="checkbox" name="enable_reminder" id="enable_reminder" value="1" checked />
							<?php } else { ?>
							<input type="checkbox" name="enable_reminder" id="enable_reminder" value="1" />
							<?php } ?>
							<p class="description"><?php _e('This will turn on reminder.','web2application'); ?></p>
						</td>
					</tr>
					<tr>
						<th>
							<label for="wcf_email_body"><b><?php _e('Send miss you a reminder if the client didn\'t buy for:','web2application'); ?></b></label>
						</th>
						<td>							
							<input name="reminder_days" type="number" id="reminder_days" value="<?php echo ( isset( $w2a_options['w2a_reminder_days'] ) ) ? $w2a_options['w2a_reminder_days'] : ''; ?>" class="form-control col-md-2" style="width: 100px;" min="1" max="10"> <?php _e('Days','web2application'); ?>
						</td>
					</tr>
					<tr>
						<th>
							<label for="wcf_email_body"><b><?php _e('Push Title/Email Subject:','web2application'); ?></b></label>
						</th>
						<td>							
							<input name="reminder_push_title" type="text" id="reminder_push_title" value="<?php echo ( isset( $w2a_options['w2a_reminder_push_title'] ) ) ? $w2a_options['w2a_reminder_push_title'] : ''; ?>" class="form-control col-md-4">
						</td>
					</tr>
					<tr>
						<th>
							<label for="wcf_email_body"><b><?php _e('Push Link:','web2application'); ?></b></label>
						</th>
						<td>							
							<input name="reminder_push_link" type="text" id="reminder_push_link" value="<?php echo ( isset( $w2a_options['w2a_reminder_push_link'] ) ) ? $w2a_options['w2a_reminder_push_link'] : ''; ?>" class="form-control col-md-4">
						</td>
					</tr>
					<tr>
						<th>
							<label for="wcf_email_body"><b><?php _e('Email Body:','web2application'); ?></b></label>
						</th>
						<td>							
							<?php
							$body_push = '';
							if( isset( $w2a_options['w2a_reminder_body_push'] ) ) {
								$body_push = stripslashes($w2a_options['w2a_reminder_body_push']);
							}
							the_editor($body_push, 'reminder_body_push')
							?>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e('Push Schedule(Daily):','web2application'); ?></label></th>
						<td>
							<?php if($row->app_paied != 'no') { ?>
							<?php /* ?>
							<label for="schedule-push"><input type="radio" id="schedule-push" name="reminder_push_schedule" value="schedule_push" /><?php _e('Schedule this push', 'web2application'); ?></label>
							<label for="datepicker">
								<?php _e('Schedule Date', 'web2application'); ?>
								<input name="reminder_push_date" type="text" id="datepicker" value="<?php echo ( isset( $w2a_options['w2a_reminder_push_date'] ) ) ? $w2a_options['w2a_reminder_push_date'] : ''; ?>" class="form-control col-md-2" <?php if ($disabled) { echo "disabled"; } ?> style="width: 100px;" />
							</label>
							<?php */ ?>
							<label for="timepicker">
								<input name="reminder_push_time" type="text" id="timepicker" value="<?php echo ( isset( $w2a_options['w2a_reminder_push_time'] ) ) ? $w2a_options['w2a_reminder_push_time'] : ''; ?>" class="form-control col-md-2" <?php if ($disabled) { echo "disabled"; } ?> style="width: 100px;" />
							</label>
							<?php } else { ?>
							<?php _e('Reminder Settings is available only to premium users', 'web2application'); ?>
							<?php } ?>
						</td>
					</tr>
				</tbody>
				<?php wp_nonce_field('w2a_push', 'w2a_push_submit_post'); ?>
			</table>
			<input type="submit" value="<?php _e('Save Settings', 'web2application'); ?>" name="save_reminder" class="button button-primary" <?php if ($disabled) { echo "disabled"; } ?> />
		</form>
	</div>
	
</div>

<script>
$(document).ready(function() {
    $('#schedule-push').click(function () {
        $('#datepicker').show();
        $('#timepicker').show();
    });

    $('#send-now').click(function () {
        $('#datepicker').hide();
        $('#timepicker').hide();
    });
	
	// date picker
	$('#datepicker').datepicker({
	    dateFormat: 'yy-mm-dd'
	});
	
	// time picker
	$('#timepicker').timepicker({
    	timeFormat: 'HH:mm',
    	interval: 5,
    	startTime: '00:00',
    	dynamic: false,
    	dropdown: true,
    	scrollbar: true
	});
});
</script>