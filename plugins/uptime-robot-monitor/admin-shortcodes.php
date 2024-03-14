<?php defined('ABSPATH') or die("No script kiddies please!");

if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'urpro') );
}

function urpro_admin_shortcodes() { ?>
<div class="wp-ur-set">

                <h3><?php _e('Shortcodes', 'urpro'); ?></h3>
 	<?php echo '<input type="text" size="30" value="[uptime-robot]" onclick="this.select()"> '.__('Use this shortcode on any page or post to display your uptime robot stats.', 'urpro'); ?><br>
  	<?php echo '<br><input type="text" size="30" value="[uptime-robot-logs]" onclick="this.select()"> '.__('Use this shortcode on any page or post to display your uptime robot logs.', 'urpro'); ?><br>
 	<?php echo '<br><input type="text" size="30" value="[uptime-robot-response]" onclick="this.select()"> '.__('Use this shortcode on any page or post to display the response time charts.', 'urpro'); ?><br>

<?php 

	echo '<h3>'.__('Pro shortcodes', 'urpro').'</h3>';
	echo '<input type="text" size="50" value="[uptime-robot monitors=&quot;00000-00001&quot;]" onclick="this.select()"> '.__('Use this attribute to display specific monitors.', 'urpro').'<br><br>';
	echo '<input type="text" size="50" value="[uptime-robot days=&quot;1-7-14-180&quot;]" onclick="this.select()"> '.__('Use this attribute to display uptime ratios for a custom periode of days.', 'urpro').'<br><br>';
	echo '<input type="text" size="50" value="[uptime-robot hide=&quot;head-name&quot;]" onclick="this.select()"> '.__('Use this attribute to hide output. Options:', 'urpro').' head-name-status-type-uptime<br><br>';
	echo '<input type="text" size="50" value="[uptime-robot show=&quot;id-url&quot;]" onclick="this.select()"> '.__('Use this attribute to show extra output. Options:', 'urpro').' id-duration-url<br><br>';
	echo '<input type="text" size="50" value="[uptime-robot-logs monitors=&quot;00000-00001&quot;]" onclick="this.select()"> '.__('Use this attribute to display specific monitors.', 'urpro').'<br><br>';
	echo '<input type="text" size="50" value="[uptime-robot-logs days=&quot;7&quot;]" onclick="this.select()"> '.__('Use this attribute to display only the log history for the past x days.', 'urpro').'<br><br>';
	echo '<input type="text" size="50" value="[uptime-robot-response monitors=&quot;00000-00001&quot;]" onclick="this.select()"> '.__('Use this attribute to display specific monitors.', 'urpro').'<br><br>';
	echo '<input type="text" size="50" value="[uptime-robot-response width=&quot;100%&quot;]" onclick="this.select()"> '.__('Use this attribute to change the height of the chart. You can use % or px.', 'urpro').'<br><br>';
	echo '<input type="text" size="50" value="[uptime-robot-response height=&quot;600px&quot;]" onclick="this.select()"> '.__('Use this attribute to change the width of the chart. You can use % or px.', 'urpro').'<br><br>';

echo '</div>';

}