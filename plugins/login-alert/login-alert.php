<?php
/*
Plugin Name: Login Alert
Plugin URI: http://www.pasqualepuzio.it
Description: 
Version: 0.2.1
Author: PasqualePuzio
Author URI: http://www.pasqualepuzio.it
*/

function init_settings() {
	$subject = get_option('login-alert_subject');
	$body = get_option('login-alert_body');
	$exclude_admin = get_option('login-alert_exclude_admin');

	if (!$subject) {
		register_setting('login-alert_options', 'login-alert_subject', 'esc_attr');
		add_option('login-alert_subject', 'New login at %SITENAME%');
	}

	if (!$body) {
		register_setting('login-alert_options', 'login-alert_body', 'esc_attr');
		add_option('login-alert_body', '%USERNAME% logged in at %DATE% %TIME%');
	}

	if (!$exclude_admin) {
		register_setting('login-alert_options', 'login-alert_exclude_admin', 'esc_attr');
		add_option('login-alert_exclude_admin', 0);
	}

}

add_action( 'admin_init', 'init_settings' );

function login_alert() {

if ($_POST[action] == "yes") {
	update_option("login-alert_subject", $_POST['login-alert_subject']);
	update_option("login-alert_body", $_POST['login-alert_body']);
	if ($_POST['login-alert_exclude_admin'] == "")
		update_option("login-alert_exclude_admin", '0');
	else
		update_option("login-alert_exclude_admin", '1');
	echo '<div id="message" class="updated">Settings successfully saved</div>';
}

?>
<h2>Login Alert Settings Page</h2>
<p>
PLACEHOLDERS AVAILABLE FOR SUBJECT: %SITENAME%
<br/>
PLACEHOLDERS AVAILABLE FOR BODY: %USERNAME%, %DATE%, %TIME%
</p>
<form method="post">
<ul id="login-alert-settings">
<li>
<label for="login-alert_subject">Subject:</label><br/>
<input type="text" name="login-alert_subject" value="<?php echo get_option('login-alert_subject'); ?>" />
</li>
<li>
<label for="login-alert_body">Body:</label><br/>
<textarea name="login-alert_body"><?php echo get_option('login-alert_body'); ?></textarea>
</li>
<li>
<label for="login-alert_exclude_admin">Exclude admin access from notifications</label><br/>
<input type="checkbox" name="login-alert_exclude_admin" value="1" <?php if (get_option('login-alert_exclude_admin') == 1) echo "checked='checked'"; ?> />
</li>
<li><input type="submit" value="Save" /></li>
</ul>
<input type="hidden" name="action" value="yes" />
<?php //settings_fields( 'login-alert_options' ); ?>
</form>
<?php }

// EMAIL NOTIFICATION

function alert_login($par) {
	
	//$user = get_userdatabylogin($par);

if ( current_user_can('administrator') && get_option('login-alert_exclude_admin') == '1' ) {
	return;
}

	$subject = get_option("login-alert_subject");
	$body = get_option("login-alert_body");

	$subject = str_replace("%SITENAME%", get_option('blogname'), $subject);
	$body = str_replace(array("%USERNAME%", "%DATE%", "%TIME%"), array($par, date('Y-m-d', current_time('timestamp')), date('H:i:s', current_time('timestamp'))), $body);

	wp_mail(get_option('admin_email'), $subject, $body.$logged_user->role[0]);
}

add_action('wp_login','alert_login');

// MENU CREATION
add_action('admin_menu', 'my_plugin_menu');


function my_plugin_menu() {
	$menuslug = add_menu_page( "Login Alert", "Login Alert", "manage_options", "login_alert", "login_alert" );
}

?>
