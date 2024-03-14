<div id="data-cache">

	<h3><?php printf(__('%1$s Data Cache', 'r34ics'), 'ICS Calendar'); ?></h3>

	<form id="r34ics-purge-calendar-transients" method="post" action="">
		<?php
		wp_nonce_field('r34ics','r34ics-purge-calendar-transients-nonce');
		?>
		<input type="submit" class="button button-primary" value="<?php echo esc_attr(__('Clear Cached Calendar Data', 'r34ics')); ?>" />
		<p><?php _e('This will immediately clear all existing cached calendar data (purge transients), forcing WordPress to reload all calendars the next time they are viewed. Caching will then resume as before.', 'r34ics'); ?></p>
	</form>

</div>

<hr />
	
<div id="ics-feed-url-tester">

	<h3><?php _e('ICS Feed URL Tester', 'r34ics'); ?></h3>

	<p><?php _e('If you are concerned that the plugin is not properly retrieving your feed, you can test the URL here.', 'r34ics'); ?></p>

	<form id="r34ics-url-tester" method="post" action="#ics-feed-url-tester">
		<?php
		wp_nonce_field('r34ics','r34ics-url-tester-nonce');
		?>
		<div class="r34ics-input">
			<label for="r34ics-url-tester-url_to_test"><input type="text" name="url_to_test" id="r34ics-url-tester-url_to_test" value="<?php if (!empty($url_to_test)) { echo esc_attr($url_to_test); } ?>" placeholder="<?php echo esc_attr(__('Enter feed URL...', 'r34ics')); ?>" style="width: 50%;" /></label> <input type="submit" class="button button-primary" value="<?php echo esc_attr(__('Test URL', 'r34ics')); ?>" />
		</div>
	</form>
	
	<?php
	if (!empty($url_tester_result)) {
		?>
		<h4><?php _e('Results:', 'r34ics'); ?></h4>
		<div><mark class="success"><?php printf(__('%s received.', 'r34ics'), size_format(strlen($url_tester_result), 2)); ?></mark></div>
		<?php
		if (strpos($url_tester_result,'BEGIN:VCALENDAR') === 0) {
			?>
			<div><mark class="success"><?php _e('This appears to be a valid ICS feed URL.', 'r34ics'); ?></mark></div>
			<?php
		}
		else {
			?>
			<div><mark class="error"><?php _e('This does not appear to be a valid ICS feed URL.', 'r34ics'); ?></mark></div>
			<?php
		}
	}
	else {
		if (!empty($url_to_test)) {
			?>
			<h4><?php _e('Results:', 'r34ics'); ?></h4>
			<div><mark class="error"><?php _e('Could not retrieve data from the requested URL.', 'r34ics'); ?></mark></div>
			<?php
		}
		elseif (isset($_POST['r34ics-url-tester-nonce'])) {
			?>
			<h4><?php _e('Results:', 'r34ics'); ?></h4>
			<div><mark class="error"><?php _e('An unknown error occurred while attempting to retrieve the requested URL.', 'r34ics'); ?></mark></div>
			<?php
		}
	}
	?>

</div>

<?php
// Restrict System Report to admins / super admins
if	(
			(is_multisite() && current_user_can('setup_network')) ||
			(!is_multisite() && current_user_can('manage_options'))
		)
{
	?>
	<hr />
	
	<div id="system-report">

		<h3><?php _e('System Report', 'r34ics'); ?></h3>

		<p><mark class="info"><?php _e('Please copy the following text and include it in your message when emailing support.', 'r34ics'); ?><br />
		<?php printf(__('Also please include the %1$s shortcode exactly as you have it entered on the affected page.', 'r34ics'), 'ICS Calendar'); ?></mark><br /><mark class="error"><?php printf(__('For your site security please do NOT post the System Report in the support forums.', 'r34ics')); ?></mark></p>

		<textarea class="diagnostics-window" readonly="readonly" style="cursor: copy;" onclick="this.select(); document.execCommand('copy');"><?php r34ics_system_report(); ?></textarea>

	</div>
	<?php
}
?>