<a href="https://icscalendar.com/" target="_blank"><img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/ics-calendar-logo-2023.svg" alt="ICS Calendar" style="display: block; height: auto; margin: 0 auto 1.5em auto; width: 200px;" /></a>

<h3><?php _e('User Guide', 'r34ics'); ?></h3>

<p><?php _e('Our complete user guide is available with full translation into dozens of languages on our website:', 'r34ics'); ?> <strong><a href="https://icscalendar.com/user-guide/">icscalendar.com/user-guide</a></strong></p>

<h3><?php _e('Support', 'r34ics'); ?></h3>

<p><?php printf(__('For support please email %1$s or use the %2$sWordPress Support Forums%3$s.', 'r34ics'), '<strong><a href="mailto:support@room34.com">support@room34.com</a></strong>', '<strong><a href="https://wordpress.org/support/plugin/ics-calendar" target="_blank" style="white-space: nowrap;">', '</a></strong>'); ?></p>

<?php
// Restrict System Report to admins / super admins
if	(
			(is_multisite() && current_user_can('setup_network')) ||
			(!is_multisite() && current_user_can('manage_options'))
		)
{
	?>
	<p><?php printf(__('When emailing, please include the %1$sSystem Report%2$s from this page.', 'r34ics'), '<strong style="white-space: nowrap;">', '</strong>'); ?></p>
	<?php
}
?>

<hr />
		
<a href="https://icscalendar.com/pro" target="_blank"><img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/ics-calendar-pro-logo-2023.svg" alt="ICS Calendar Pro" style="display: block; height: auto; margin: 1.5em auto; width: 200px;" /></a>

<p><strong style="color: #0985f2;"><?php _e('Upgrade to PRO!', 'r34ics'); ?></strong><br />
<?php printf(__('Features include additional views (Full, Masonry, Month with Sidebar, Widget, Year-at-a-Glance, and others), a Calendar Builder with save feature for full management of calendar settings without manually editing the shortcode, Customizer options to easily modify the calendar&rsquo;s appearance, advanced features like regular expressions, and more! Visit %s to learn more.', 'r34ics'), '<strong><a href="https://icscalendar.com/pro/" target="_blank">icscalendar.com/pro</a></strong>'); ?></p>

<p style="text-align: center;"><a href="https://icscalendar.com/pro/" target="_blank" class="button button-primary"><?php _e('Go PRO!', 'r34ics'); ?></a></p>

<hr />
		
<a href="https://room34.com/about/payments/?type=WordPress+Plugin&plugin=ICS+Calendar&amt=9" target="_blank"><img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))); ?>assets/room34-logo-on-white.svg" alt="Room 34 Creative Services" style="display: block; height: auto; margin: 1.5em auto; width: 200px;" /></a> 
		
<p><small>ICS Calendar v.<?php echo get_option('r34ics_version'); ?></small></p>
