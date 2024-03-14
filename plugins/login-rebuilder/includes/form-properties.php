<?php
/*
 * Login rebuilder properties page content
 *
 * @since 2.0.0
 *
 * @package WordPress
 *
 * require login_rebuilder::properties()
 */

if ( !( isset( $this ) && is_a( $this, 'login_rebuilder' ) ) ) die( 0 );
?>
<div id="<?php echo self::LOGIN_REBUILDER_PROPERTIES_NAME; ?>" class="wrap">
<div id="icon-options-general" class="icon32"><br /></div>
<h2><?php _e( 'Login rebuilder', LOGIN_REBUILDER_DOMAIN ); ?> <?php _e( 'Settings' ) ;?></h2>
<?php $this->_properties_message( $message ); ?>

<div id="login-rebuilder-widget" class="metabox-holder">
<p><?php _e( 'Notice: This page is valid for 30 minutes.', LOGIN_REBUILDER_DOMAIN ); ?></p>
<?php if ( $show_reload ) { ?>
<p><a href="<?php echo esc_url( str_replace( '%07E', '~', $this->request_uri ) ); ?>" class="button"><?php _e( 'Reload now.', LOGIN_REBUILDER_DOMAIN ); ?></a></p>
<?php } else { ?>
<form method="post" action="<?php echo esc_url( str_replace( '%07E', '~', $this->request_uri ) ); ?>">
<table summary="login rebuilder properties" class="form-table">

<tr valign="top">
<th><?php _e( 'Status' ); ?></th>
<td>
<fieldset>
<input type="radio" name="properties[status]" id="properties_status_0" value="<?php echo esc_attr( self::LOGIN_REBUILDER_STATUS_IN_PREPARATION ); ?>" <?php checked( $this->properties['status'] == self::LOGIN_REBUILDER_STATUS_IN_PREPARATION ); ?> /><label for="properties_status_0">&nbsp;<span><?php _e( 'in preparation', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="radio" name="properties[status]" id="properties_status_1" value="<?php echo esc_attr( self::LOGIN_REBUILDER_STATUS_WORKING ); ?>" <?php checked( $this->properties['status'] == self::LOGIN_REBUILDER_STATUS_WORKING ); ?> /><label for="properties_status_1">&nbsp;<span><?php _e( 'working', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
</fieldset>
</td>
</tr>

<tr valign="top">
<th><?php _e( 'Response to an invalid request', LOGIN_REBUILDER_DOMAIN ); ?></th>
<td>
<fieldset>
<input type="radio" name="properties[response]" id="properties_response_1" value="<?php echo esc_attr( self::LOGIN_REBUILDER_RESPONSE_403 ); ?>" <?php checked( $this->properties['response'] == self::LOGIN_REBUILDER_RESPONSE_403 ); ?> /><label for="properties_response_1">&nbsp;<span><?php _e( '403 status', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="radio" name="properties[response]" id="properties_response_2" value="<?php echo esc_attr( self::LOGIN_REBUILDER_RESPONSE_404 ); ?>" <?php checked( $this->properties['response'] == self::LOGIN_REBUILDER_RESPONSE_404 ); ?> /><label for="properties_response_2">&nbsp;<span><?php _e( '404 status', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="radio" name="properties[response]" id="properties_response_3" value="<?php echo esc_attr( self::LOGIN_REBUILDER_RESPONSE_GO_HOME ); ?>" <?php checked( $this->properties['response'] == self::LOGIN_REBUILDER_RESPONSE_GO_HOME ); ?> /><label for="properties_response_3">&nbsp;<span><?php _e( 'redirect to a site url', LOGIN_REBUILDER_DOMAIN ); echo ' ( '.home_url().' )'; ?></span></label><br />
</fieldset>
</td>
</tr>

<tr valign="top">
<th><label for="properties_keyword"><?php _e( 'Login file keyword', LOGIN_REBUILDER_DOMAIN ); ?></label></th>
<td><input type="text" name="properties[keyword]" id="properties_keyword" value="<?php echo esc_attr( $this->properties['keyword'] ); ?>" class="regular-text code" <?php if ( !$this->use_site_option ) echo 'readonly="readonly"'; ?> /></td>
</tr>

<tr valign="top">
<th><label for="properties_page"><?php _e( 'New login file', LOGIN_REBUILDER_DOMAIN ); ?></label></th>
<td><input type="text" name="properties[page]" id="properties_page" value="<?php echo esc_attr( $this->properties['page'] ); ?>" class="regular-text code<?php if ( ! $this->arrow_slash_in_login_path ) { echo ' slash_invalid'; }?>" />
<?php if ( ! $this->arrow_slash_in_login_path ) { echo '<p class="alert"></p>'; }?>
<p class="info" style="margin-bottom: 1em; padding: 0 0 0 1em; font-size: 92%; color: #666666;"><label>Path: </label><span id="path_login" class="path">&nbsp;</span> <span id="writable" class="writable">&nbsp;</span><br />
<label>URL: </label><span id="url_login" class="url">&nbsp;</span><br />
<textarea name="properties[content]" id="login_page_content" rows="4" style="font-family:monospace; width: 96%;" readonly="readonly" class="content"></textarea><input type="hidden" id="content_template" value="<?php echo $this->content; ?>" />
</p>

<div style="margin-bottom: 1em;">
<p><?php _e( 'Important: If you enable this setting, you can not login if the file specified below exists.', LOGIN_REBUILDER_DOMAIN ); ?><br />
<label><input type="checkbox" name="properties[use_lock_file]" id="use_lock_file" value="1" <?php checked( isset( $this->properties['use_lock_file'] )? $this->properties['use_lock_file']: false ); ?>><?php _e( 'Use lock file', LOGIN_REBUILDER_DOMAIN ); ?></label><br />
</p>
<p style="margin-left: 1.5em;">
<input type="text" name="properties[lock_file_path]" id="lock_file_path" value="<?php echo esc_attr( isset( $this->properties['lock_file_path'] )? $this->properties['lock_file_path']: '' ); ?>" class="regular-text code">&nbsp;<span id="lock_exists" class="lock-exists">&nbsp;</span><br />
<label><input type="checkbox" name="properties[locked_status_popup]" id="locked_status_popup" value="1" <?php checked( isset( $this->properties['locked_status_popup'] )? $this->properties['locked_status_popup']: false ); ?>><?php _e( 'Show locked status on pop-up re-login form.', LOGIN_REBUILDER_DOMAIN ); ?></label><br />
</p>
</div>

<?php if ( self::HTTP_AUTHENTICATE_ENABLED ) { ?>
<div>
<p><?php _e( 'Important: If this setting is enabled, HTTP Authentication will be applied to the login page.', LOGIN_REBUILDER_DOMAIN ); ?><br />
<label><input type="checkbox" name="properties[use_http_auth]" id="use_http_auth" value="1" <?php checked( isset( $this->properties['use_http_auth'] )? $this->properties['use_http_auth']: false ); ?>><?php _e( 'Use HTTP Authentication', LOGIN_REBUILDER_DOMAIN ); ?></label><br />
</p>
<p style="margin-left: 1.5em;">
<label for="http_auth_username"><?php _e( 'Username' ); ?></label>
<input type="text" name="properties[http_auth_username]" id="http_auth_username" value="<?php if ( isset( $this->properties['http_auth_username'] ) ) { echo esc_attr( $this->properties['http_auth_username'] ); } ?>" class="regular-text" spellcheck="false" autocomplete="off" />
</p>
<p style="margin-left: 1.5em;"><?php _e( 'If you change the user name, be sure to specify the password.', LOGIN_REBUILDER_DOMAIN ); ?></p>
<p style="margin-left: 1.5em;">
<label for="http_auth_password"><?php _e( 'Password' ); ?></label>
<input type="text" name="properties[http_auth_password]" id="http_auth_password" value="" class="regular-text" spellcheck="false" autocomplete="off" />
<button type="button" class="button wp-hide-pw hide-if-no-js" data-toggle="0"><span class="dashicons dashicons-hidden" aria-hidden="true"></span></button>
<?php if ( $this->_is_wp_version( '4.4', '>=' ) ) { ?>
<button type="button" class="button generate-pw hide-if-no-js"><?php _e( 'Generate password' ); ?></button>
<?php } ?>
</p>
<p style="margin-left: 1.5em;"><?php if ( isset( $this->properties['http_auth_hash'] ) && ! empty( $this->properties['http_auth_hash'] ) ) { _e( 'Password has been set.', LOGIN_REBUILDER_DOMAIN ); } else { _e( 'Password is not set.', LOGIN_REBUILDER_DOMAIN ); } ?></p>
<p style="margin-left: 1.5em;"><input type="checkbox" name="properties[http_auth_popup]" id="http_auth_popup" value="1" <?php checked( isset( $this->properties['http_auth_popup'] ) && $this->properties['http_auth_popup'] ); ?> /><label for="http_auth_popup"><?php _e( 'In the case of a pop-up display, HTTP authentication is performed.', LOGIN_REBUILDER_DOMAIN ); ?></label></p>
</div>
<?php } ?>
</td>
</tr>
<tr valign="top">
<th><label for="properties_page"><?php _e( 'Secondary login file', LOGIN_REBUILDER_DOMAIN ); ?></label></th>
<td><input type="text" name="properties[page_subscriber]" id="properties_page_subscriber" value="<?php echo esc_attr( isset( $this->properties['page_subscriber'] )? $this->properties['page_subscriber']: '' ); ?>" class="regular-text code<?php if ( ! $this->arrow_slash_in_login_path ) { echo ' slash_invalid'; }?>" />
<?php if ( ! $this->arrow_slash_in_login_path ) { echo '<p class="alert"></p>'; }?>
<p class="info" style="padding: 0 0 0 1em; font-size: 92%; color: #666666;"><label>Path: </label><span id="path_subscriber" class="path">&nbsp;</span> <span id="writable_subscriber" class="writable">&nbsp;</span><br />
<label>URL: </label><span id="url_subscriber" class="url">&nbsp;</span><br />
<textarea name="properties[content_subscriber]" id="subscriber_page_content" rows="4" style="font-family:monospace; width: 96%;" readonly="readonly" class="content"></textarea><br />
<?php _e( 'Role' ); ?>: <?php
$roles = get_editable_roles();
unset( $roles['administrator'] );
foreach ( array_reverse( $roles ) as $role => $details ) {
	$name = translate_user_role($details['name'] );
	$checked = in_array( $role, (array)$this->properties['secondary_roles'] )? ' checked="checked"': '';
	$role = esc_attr($role);
	echo '<input type="checkbox" name="properties[secondary_roles][]" id="secondary_'.$role.'" value="'.$role.'" '.$checked.'/><label for="secondary_'.$role.'">'.$name.'</label>&nbsp;&nbsp;&nbsp;';
}
?>
</p>
</td>
</tr>

<tr valign="top">
<th><?php _e( 'Logging', LOGIN_REBUILDER_DOMAIN ); ?></th>
<td>
<fieldset>
<input type="radio" name="properties[logging]" id="properties_logging_0" value="<?php echo esc_attr( self::LOGIN_REBUILDER_LOGGING_OFF ); ?>" <?php checked( $this->properties['logging'] == self::LOGIN_REBUILDER_LOGGING_OFF ); ?> /><label for="properties_logging_0">&nbsp;<span><?php _e( 'off', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="radio" name="properties[logging]" id="properties_logging_1" value="<?php echo esc_attr( self::LOGIN_REBUILDER_LOGGING_INVALID_REQUEST ); ?>" <?php checked( $this->properties['logging'] == self::LOGIN_REBUILDER_LOGGING_INVALID_REQUEST ); ?> /><label for="properties_logging_1">&nbsp;<span><?php _e( 'invalid request only', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="radio" name="properties[logging]" id="properties_logging_2" value="<?php echo esc_attr( self::LOGIN_REBUILDER_LOGGING_LOGIN ); ?>" <?php checked( $this->properties['logging'] == self::LOGIN_REBUILDER_LOGGING_LOGIN ); ?> /><label for="properties_logging_2">&nbsp;<span><?php _e( 'login only', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="radio" name="properties[logging]" id="properties_logging_3" value="<?php echo esc_attr( self::LOGIN_REBUILDER_LOGGING_ALL ); ?>" <?php checked( $this->properties['logging'] == self::LOGIN_REBUILDER_LOGGING_ALL ); ?> /><label for="properties_logging_3">&nbsp;<span><?php _e( 'all', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
</fieldset>
<fieldset>
<label style="display: inline-block; width: 20em;"><?php _e( 'Number of invalid logs', LOGIN_REBUILDER_DOMAIN ); ?>:</label><input type="number" name="properties[logging_limit][invalid]" value="<?php $this->_isset_echo_esc_attr( $this->properties['logging_limit'], 'invalid' ); ?>" min="<?php echo self::LOGIN_REBUILDER_LOGGING_LIMIT_MIN; ?>" max="<?php echo self::LOGIN_REBUILDER_LOGGING_LIMIT_MAX; ?>" step="50" /><br />
<label style="display: inline-block; width: 20em;"><?php _e( 'Number of logs viewed on the login page', LOGIN_REBUILDER_DOMAIN ); ?>:</label><input type="number" name="properties[logging_limit][primary]" value="<?php $this->_isset_echo_esc_attr( $this->properties['logging_limit'], 'primary' ); ?>" min="<?php echo self::LOGIN_REBUILDER_LOGGING_LIMIT_MIN; ?>" max="<?php echo self::LOGIN_REBUILDER_LOGGING_LIMIT_MAX; ?>" step="50" /><br />
<label style="display: inline-block; width: 20em;"><?php _e( 'Number of login logs', LOGIN_REBUILDER_DOMAIN ); ?>:</label><input type="number" name="properties[logging_limit][login]" value="<?php $this->_isset_echo_esc_attr( $this->properties['logging_limit'], 'login' ); ?>" min="<?php echo self::LOGIN_REBUILDER_LOGGING_LIMIT_MIN; ?>" max="<?php echo self::LOGIN_REBUILDER_LOGGING_LIMIT_MAX; ?>" step="50" /><br />
<label style="display: inline-block; width: 20em;"><?php _e( 'Number of pingback logs', LOGIN_REBUILDER_DOMAIN ); ?>:</label><input type="number" name="properties[logging_limit][pingback]" value="<?php $this->_isset_echo_esc_attr( $this->properties['logging_limit'], 'pingback' ); ?>" min="<?php echo self::LOGIN_REBUILDER_LOGGING_LIMIT_MIN; ?>" max="<?php echo self::LOGIN_REBUILDER_LOGGING_LIMIT_MAX; ?>" step="50" /><br />
<label style="display: inline-block; width: 20em;"><?php _e( 'Number of deny rest api logs', LOGIN_REBUILDER_DOMAIN ); ?>:</label><input type="number" name="properties[logging_limit][rest]" value="<?php $this->_isset_echo_esc_attr( $this->properties['logging_limit'], 'rest' ); ?>" min="<?php echo self::LOGIN_REBUILDER_LOGGING_LIMIT_MIN; ?>" max="<?php echo self::LOGIN_REBUILDER_LOGGING_LIMIT_MAX; ?>" step="50" /><br />
</fieldset>
</td>
</tr>

<tr>
<th valign="top" scope="row"><?php _e( 'Format for displaying date and time of log', LOGIN_REBUILDER_DOMAIN ); ?></th>
<td>
<fieldset><legend class="screen-reader-text"><span><?php _e( 'Format for displaying date and time of log', LOGIN_REBUILDER_DOMAIN ); ?></span></legend>
<label><input type='radio' name='properties[datetime_format]' value='<?php echo esc_attr( $this->_date_time_format( false ) ); ?>' <?php
$any_checked = false;
if ( empty( $this->properties['datetime_format'] ) ) {
	checked( true );
	$any_checked = true;
}
?> /><code><?php echo esc_html( $this->_date_time_format( false ) ); ?></code> (<?php _e( 'Use site settings', LOGIN_REBUILDER_DOMAIN ); ?>)</label><br />
<?php foreach ( array( 'm-d H:i', 'm/d H:i', 'd/M H:i' ) as $_format ) { ?>
<label><input type='radio' name='properties[datetime_format]' value='<?php echo esc_attr( $_format ); ?>' <?php
if ( $_format === $this->properties['datetime_format'] ) {
	checked( true );
	$any_checked = true;
}
?>/><code><?php echo esc_html( $_format ); ?></code></label><br />
<?php }
$custom_format = empty( $this->properties['datetime_format'] )? $this->_date_time_format( false ): $this->properties['datetime_format'];
?>
<label><input type="radio" name="properties[datetime_format]" id="datetime_format_custom_radio" value="<?php echo esc_attr( $custom_format ); ?>" <?php checked( ! $any_checked ); ?> /> <input type="text" name="datetime_format_custom" id="datetime_format_custom" value="<?php echo esc_attr( $custom_format ); ?>" /> (<?php _e( 'Custom', LOGIN_REBUILDER_DOMAIN ); ?>)</label><br />
<p><strong><?php _e( 'Preview' ); ?>:</strong> <span class="example"></span><span class='spinner' style="float:none;"></span></p>
</fieldset>
</td>

<tr valign="top">
<th><?php _e( 'Browsing to the Author page', LOGIN_REBUILDER_DOMAIN ); ?></th>
<td>
<fieldset>
<input type="radio" name="properties[access_author_page]" id="properties_access_author_page_0" value="<?php echo esc_attr( self::LOGIN_REBUILDER_ACCESS_AUTHOR_PAGE_ACCEPT ); ?>" <?php checked( $this->properties['access_author_page'] == self::LOGIN_REBUILDER_ACCESS_AUTHOR_PAGE_ACCEPT); ?> /><label for="properties_access_author_page_0">&nbsp;<span><?php _e( 'accept', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="radio" name="properties[access_author_page]" id="properties_access_author_page_1" value="<?php echo esc_attr( self::LOGIN_REBUILDER_ACCESS_AUTHOR_PAGE_404 ); ?>" <?php checked( $this->properties['access_author_page'] == self::LOGIN_REBUILDER_ACCESS_AUTHOR_PAGE_404 ); ?> /><label for="properties_access_author_page_1">&nbsp;<span><?php _e( '404 status', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
</fieldset>
<p class="description"><?php _e( 'If you restrict browsing to the Author page, no "users" sitemap is created. (WordPress 5.5. 0 or later)', LOGIN_REBUILDER_DOMAIN ); ?></p>
</td>
</tr>

<tr valign="top">
<th><?php _e( 'oEmbed', LOGIN_REBUILDER_DOMAIN ); ?></th>
<td>
<fieldset>
<input type="radio" name="properties[oembed]" id="properties_oembed_0" value="<?php echo esc_attr( self::LOGIN_REBUILDER_OEMBED_DEFAULT ); ?>" <?php checked( $this->properties['oembed'] == self::LOGIN_REBUILDER_OEMBED_DEFAULT ); ?> /><label for="properties_oembed_0">&nbsp;<span><?php _e( 'Default', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="radio" name="properties[oembed]" id="properties_oembed_1" value="<?php echo esc_attr( self::LOGIN_REBUILDER_OEMBED_HIDE_AUTHOR ); ?>" <?php checked( $this->properties['oembed'] == self::LOGIN_REBUILDER_OEMBED_HIDE_AUTHOR ); ?> /><label for="properties_oembed_1">&nbsp;<span><?php _e( 'Hide the author name and url of the response data.', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="radio" name="properties[oembed]" id="properties_oembed_2" value="<?php echo esc_attr( self::LOGIN_REBUILDER_OEMBED_DONT_OUTPUT ); ?>" <?php checked( $this->properties['oembed'] == self::LOGIN_REBUILDER_OEMBED_DONT_OUTPUT ); ?> /><label for="properties_oembed_2">&nbsp;<span><?php _e( "Don't output response data and links of head element.", LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
</fieldset>
</td>
</tr>

<tr valign="top">
<th><?php _e( 'Notification', LOGIN_REBUILDER_DOMAIN ); ?></th>
<td>
<fieldset>
<input type="checkbox" name="properties[notify_unknown_ip]" id="properties_notify_unknown_ip" value="1" <?php checked( $this->properties['notify_unknown_ip'] ); ?> /><label for="properties_notify_unknown_ip">&nbsp;<span><?php _e( 'Notify the site administrator when the login page is requested from an IP address that has never logged in before.', LOGIN_REBUILDER_DOMAIN ); ?></span></label>&nbsp;<input type="submit" name="test-notify-unknown-ip" id="test-notify-unknown-ip" value="<?php esc_attr_e( 'Test', LOGIN_REBUILDER_DOMAIN ); ?>" class="button" /><br />
</fieldset>
<fieldset style="margin: 0 0 1.5em 1.5em;">
<?php _e( 'Waiting for the next notification:', LOGIN_REBUILDER_DOMAIN ); ?> <input type="number" min="<?php echo esc_attr( self::NOTIFY_WAITING_MIN ); ?>" max="<?php echo esc_attr( self::NOTIFY_WAITING_MAX ); ?>" name="properties[notify_waiting]" id="properties_notify_waiting" value="<?php echo esc_attr( $this->properties['notify_waiting'] ); ?>"> <?php _e( 'minutes', LOGIN_REBUILDER_DOMAIN ); ?>
<p><?php _e( 'This notification is effective when your home or office has a static IP address.', LOGIN_REBUILDER_DOMAIN ); ?></p>
</fieldset>
<fieldset>
<input type="checkbox" name="properties[notify_admin_login]" id="properties_notify_admin_login" value="1" <?php checked( $this->properties['notify_admin_login'] ); ?> /><label for="properties_notify_admin_login">&nbsp;<span><?php _e( 'Notify by email when the administrator login.', LOGIN_REBUILDER_DOMAIN ); ?></span></label>&nbsp;<input type="submit" name="test-notify" id="test-notify" value="<?php esc_attr_e( 'Test', LOGIN_REBUILDER_DOMAIN ); ?>" class="button" /><br />
</fieldset>
<fieldset style="margin-left: 1.5em;">
<input type="radio" name="properties[notify_siteadmin_cc]" id="properties_notify_siteadmin_cc0" value="0" <?php checked( 0 === $this->properties['notify_siteadmin_cc'] ); ?> />&nbsp;<label for="properties_notify_siteadmin_cc0"><?php _e( 'Do not specify the site administrator as CC / BCC.', LOGIN_REBUILDER_DOMAIN ); ?></label><br />
<input type="radio" name="properties[notify_siteadmin_cc]" id="properties_notify_siteadmin_cc1" value="1" <?php checked( 1 === $this->properties['notify_siteadmin_cc'] ); ?> />&nbsp;<label for="properties_notify_siteadmin_cc1"><?php _e( 'Designate the site administrator as CC.', LOGIN_REBUILDER_DOMAIN ); ?></label><br />
<input type="radio" name="properties[notify_siteadmin_cc]" id="properties_notify_siteadmin_cc2" value="2" <?php checked( 2 === $this->properties['notify_siteadmin_cc'] ); ?> />&nbsp;<label for="properties_notify_siteadmin_cc2"><?php _e( 'Designate the site administrator as BCC.', LOGIN_REBUILDER_DOMAIN ); ?></label><br />
</fieldset>
</td>
</tr>

<tr valign="top">
<th><?php _e( 'Other', LOGIN_REBUILDER_DOMAIN ); ?></th>
<td>
<fieldset>
<input type="checkbox" name="properties[ambiguous_error_message]" id="properties_ambiguous_error_message" value="1" <?php checked( $this->properties['ambiguous_error_message'] ); ?> /><label for="properties_ambiguous_error_message">&nbsp;<span><?php _e( 'Change the error message at login to ambiguous content.', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="checkbox" name="properties[disable_authenticate_email_password]" id="properties_disable_authenticate_email_password" value="1" <?php checked( $this->properties['disable_authenticate_email_password'] ); ?> /><label for="properties_disable_authenticate_email_password">&nbsp;<span><?php _e( 'Authentication using a email address and a password is prohibited.', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="checkbox" name="properties[reject_user_register]" id="properties_reject_user_register" value="1" <?php checked( $this->properties['reject_user_register'] ); ?> /><label for="properties_reject_user_register">&nbsp;<span><?php _e( 'Reject the registration form.', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="checkbox" name="properties[restrict_rest_users]" id="properties_restrict_rest_users" value="1" <?php checked( $this->properties['restrict_rest_users'] ); ?> /><label for="properties_restrict_rest_users">&nbsp;<span><?php _e( 'Deny the REST API / Users if not logged in.', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="checkbox" name="properties[contains_heading_line]" id="properties_contains_heading_line" value="1" <?php checked( $this->properties['contains_heading_line'] ); ?> /><label for="properties_contains_heading_line">&nbsp;<span><?php _e( 'Log file contains a heading line.', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
<input type="checkbox" name="properties[logged_in_users_widget]" id="properties_logged_in_users_widget" value="1" <?php checked( $this->properties['logged_in_users_widget'] ); ?> /><label for="properties_logged_in_users_widget">&nbsp;<span><?php _e( 'All users will see the "Logged-in users" widget.', LOGIN_REBUILDER_DOMAIN ); ?></span></label><br />
</fieldset>
</td>
</tr>

<tr valign="top">
<td colspan="2">
<input type="submit" name="submit" value="<?php esc_attr_e( 'Save Changes' ); ?>" class="button-primary" />
<?php if ( ( isset( $logging['invalid'] ) && is_array( $logging['invalid'] ) && count( $logging['invalid'] ) > 0 ) ||
		( isset( $logging['login'] ) && is_array( $logging['login'] ) && count( $logging['login'] ) > 0 ) ) { ?>
<input type="button" name="view-log" id="view-log" value="<?php esc_attr_e( 'View log', LOGIN_REBUILDER_DOMAIN ); ?>" class="button" />
<?php } wp_nonce_field( self::LOGIN_REBUILDER_PROPERTIES_NAME.$this->_nonce_suffix() ); $this->_private_nonce_field(); ?>
</td>
</tr>
</table>
</form>

<?php
$log_invalid = ( isset( $logging['invalid'] ) && is_array( $logging['invalid'] ) && count( $logging['invalid'] ) > 0 );
$log_login = ( isset( $logging['login'] ) && is_array( $logging['login'] ) && count( $logging['login'] ) > 0 );
$log_primary = ( isset( $logging['primary'] ) && is_array( $logging['primary'] ) && count( $logging['primary'] ) > 0 );
$log_rest = ( isset( $logging['rest'] ) && is_array( $logging['rest'] ) && count( $logging['rest'] ) > 0 );
if ( $log_invalid || $log_login || $log_primary || $log_rest ) { ?>
<div id="log-content" style="display: none;">
<table summary="login rebuilder logs" class="form-table">
<tbody>
<tr>
<td colspan="3" style="vertical-align: top;">
<h4><?php _e( 'Log of login page request', LOGIN_REBUILDER_DOMAIN ); ?></h4>
<?php
if ( $log_primary ) {
	krsort( $logging['primary'] );
	$this->_view_log_of_primary_request( $logging['primary'] );
} else {
	_e( 'No logs.', LOGIN_REBUILDER_DOMAIN );
}
?>
</td>
</tr>
<tr>
<td style="vertical-align: top;">
<h4><?php _e( 'Log of login', LOGIN_REBUILDER_DOMAIN ); ?></h4>
<?php
if ( $log_login ) {
	krsort( $logging['login'] );
	$this->_view_log_of_login( $logging['login'] );
} else {
	_e( 'No logs.', LOGIN_REBUILDER_DOMAIN );
}
?>
</td>
<td style="vertical-align: top;">
<h4><?php _e( 'Log of invalid request', LOGIN_REBUILDER_DOMAIN ); ?></h4>
<?php
if ( $log_invalid ) {
	krsort( $logging['invalid'] );
	$this->_view_log_of_invalid_request( $logging['invalid'] );
} else {
	_e( 'No logs.', LOGIN_REBUILDER_DOMAIN );
}
?>
</td>
<td style="vertical-align: top;">
<h4><?php _e( 'Log of denied REST API', LOGIN_REBUILDER_DOMAIN ); ?></h4>
<?php
if ( $log_rest ) {
	krsort( $logging['rest'] );
	$this->_view_log_of_rest( $logging['rest'] );
} else {
	_e( 'No logs.', LOGIN_REBUILDER_DOMAIN );
}
?>
</td>
</tr>
</tbody>
</table>
</div>
<?php } /* logging */ } /* show reload */ ?>
</div>
</div>
<?php if ( self::HTTP_AUTHENTICATE_ENABLED ) { ?>
<style>
#http_auth_password.strong {
	border-color: #68de7c;
}
#http_auth_password.good {
	border-color: #f0c33c;
}
#http_auth_password.short {
	border-color: #e65054;
}
#http_auth_password.bad {
	border-color: #f86368;
}
</style>
<?php } ?>
<script type="text/javascript">
( function($) {
<?php if ( isset( $logout_to ) && $logout_from != $logout_to ) { ?>
	$( 'a' ).each( function () {
		$( this ).attr( 'href', $( this ).attr( 'href' ).replace( '<?php echo $logout_from; ?>', '<?php echo $logout_to; ?>' ) );
	} );
<?php } ?>
	$( '#properties_keyword' ).blur( function () {
		// [2.8.2] Bugfix: Similar to the sanitize_key function.
		let keyword = $( this ).val().toLowerCase().replace( /[^0-9a-z_\-]/gi, '' );
		if ( keyword != $( this ).val() ) {
			$( this ).val( keyword );
		}
		if ( $( '#properties_page' ).val() != '' && $.trim( $( '#login_page_content' ).val() ) != '' ) {
			$( '#login_page_content' ).val( $( '#login_page_content' ).val().replace( /'LOGIN_REBUILDER_SIGNATURE', '[0-9a-zA-Z]+'/, "'LOGIN_REBUILDER_SIGNATURE', '" + keyword + "'" ) );
		}
		if ( $( '#properties_page_subscriber' ).val() != '' && $.trim( $( '#subscriber_page_content' ).val() ) != '' ) {
			$( '#subscriber_page_content' ).val( $( '#subscriber_page_content' ).val().replace( /'LOGIN_REBUILDER_SIGNATURE', '[0-9a-zA-Z]+'/, "'LOGIN_REBUILDER_SIGNATURE', '" + $keyword + "'" ) );
		}
	} );
	$( '#properties_page, #properties_page_subscriber' ).blur( function () {
		var page_elm = $( this );
		var uri = $.trim( $( this ).val() );
		var valid_uri = ( uri != '' );
		var info_elm = $( this ).siblings( 'p.info' );
		$( this ).siblings( 'p.alert' ).removeClass( 'notice notice-error' ).text( '' );
		if ( $( this ).hasClass( 'slash_invalid' ) && uri != '' && uri.indexOf( '/' ) != -1 ) { /* [2.6.2] Changed. */
			$( this ).siblings( 'p.alert' ).addClass( 'notice notice-error' ).html( "<p><?php _e( "The case of the sub-site, you can not contain '/' in the path name. Please change a path name.", LOGIN_REBUILDER_DOMAIN ); ?></p>" );
			info_elm.find( 'span.path,span.writable,span.url' ).text( '' );
			valid_uri = false;
			$( this ).focus();
		}
		if ( valid_uri ) {
			$( 'input[name=submit]' ).attr( 'disabled', 'disabled' );
			$.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>',
				{ action: 'login_rebuilder_try_save', mode: 0, page: uri, _ajax_nonce: '<?php echo wp_create_nonce( self::LOGIN_REBUILDER_AJAX_NONCE_NAME.$this->_nonce_suffix() ); ?>' },
			function( response ) {
				page_elm.next().each( function () {
					info_elm.find( 'span.path' ).text( response.data.path );
					if ( response.data.exists )
						info_elm.find( 'span.url' ).html( '<a href="'+response.data.url+'" target="_blank">'+response.data.url+'</a>' );
					else
						info_elm.find( 'span.url' ).text( response.data.url );
					if ( response.data.exists )
						out_exists = '<?php _e( 'File exists, ', LOGIN_REBUILDER_DOMAIN ); ?>';
					else
						out_exists = '<?php _e( 'File not found, ', LOGIN_REBUILDER_DOMAIN ); ?>';
					if ( response.data.writable ) {
						out_writing = '<?php _e( 'Writing is possible', LOGIN_REBUILDER_DOMAIN ); ?>';
						out_color = 'blue';
					} else {
						out_writing = '<?php _e( 'Writing is impossible', LOGIN_REBUILDER_DOMAIN ); ?>';
						out_color = 'orange';
					}
					info_elm.find( 'span.writable' ).text( '['+out_exists+out_writing+']' ).css( 'color', out_color );
					info_elm.find( 'textarea.content' ).text( response.data.content.replace( '%sig%', $( '#properties_keyword' ).val() ) );
				} );
			}, 'json' ).always( function() {} );
		}
		$( 'input[name=submit]' ).removeAttr( 'disabled' );
	} );
	var lock_file_exists = function () {
		$( 'input[name=submit]' ).attr( 'disabled', 'disabled' );
		var lock_file = $.trim( $( '#lock_file_path' ).val() );
		if ( '' != lock_file ) {
			$.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>', {
				action: 'login_rebuilder_lock_exists',
				use: $( '#use_lock_file' ).prop( 'checked' ),
				path: lock_file,
				_ajax_nonce: '<?php echo wp_create_nonce( self::LOGIN_REBUILDER_AJAX_NONCE_NAME.$this->_nonce_suffix() ); ?>'
			},
			function( response ) {
				$( 'span#lock_exists' ).text( '['+ response.data.status +']' ).css( 'color', response.data.color );
			}, 'json' ).always( function() {} );
		} else {
			$( 'span#lock_exists' ).text( '' ).css( 'color', '' );
		}
		$( 'input[name=submit]' ).removeAttr( 'disabled' );
	}
	$( '#lock_file_path' ).blur( lock_file_exists );
	$( '#use_lock_file' ).change( lock_file_exists );

	$( '#properties_page' ).blur();
	$( '#properties_page_subscriber' ).blur();
	$( '#lock_file_path' ).blur();
	$( '#view-log' ).click( function () { $( '#log-content' ).fadeToggle(); } );

	$( '#datetime_format_custom' ).on( 'focus', function () {
		$( '#datetime_format_custom_radio' ).prop( 'checked', true );
	} );
	$( '#datetime_format_custom' ).on( 'input', function () {
		$( '#datetime_format_custom_radio' ).val( $(this).val() );
		$( '#datetime_format_custom_radio' ).trigger( 'change' );
	} );
	$( 'input[name="properties[datetime_format]"]' ).on( 'change', function() {
		var format = $( this ),
			fieldset = format.closest( 'fieldset' ),
			example = fieldset.find( '.example' ),
			spinner = fieldset.find( '.spinner' );

		clearTimeout( $.data( this, 'datetime-timer' ) );
		$( this ).data( 'datetime-timer', setTimeout( function() {
			if ( format.val() ) {
				spinner.addClass( 'is-active' );
				$.post( ajaxurl, {
					action:	'date_format',
					date:	format.val()
				},
				function ( d ) {
					spinner.removeClass( 'is-active' );
					example.text( d );
				} );
			}
		}, 500 ) );
	} );
	$( 'input[name="properties[datetime_format]"][checked=checked]' ).trigger( 'change' );

<?php if ( self::HTTP_AUTHENTICATE_ENABLED ) { ?>
	$( '#http_auth_password' ).on( 'input', function () {
		let pass1 = $( this ).val();
		$( this ).removeClass( 'short bad good strong empty' );
		let strength = wp.passwordStrength.meter( pass1, wp.passwordStrength.userInputDisallowedList(), pass1 );
		switch( strength ) {
			case 4:
				$(this).addClass( 'strong' );
				break;
			case 3:
				$(this).addClass( 'good' );
				break;
			case 1:
			case 0:
				if ( pass1 ) {
					$(this).addClass( 'short' );
				}
				break;
			default:
				$(this).addClass( 'bad' );
				break;
		}
	} );
	$( '.wp-hide-pw' ).click( function () {
		if ( '1' === $( this ).data( 'toggle' ) ) {
			$( this ).data( 'toggle', '0' );
			$( this ).find( '.dashicons' ).removeClass( 'dashicons-visibility' ).addClass( 'dashicons-hidden' );
			$( '#http_auth_password' ).attr( 'type', 'text' );
		} else {
			$( this ).data( 'toggle', '1' );
			$( this ).find( '.dashicons' ).removeClass( 'dashicons-hidden' ).addClass( 'dashicons-visibility' );
			$( '#http_auth_password' ).attr( 'type', 'password' );
		}
	} );
	$( '.generate-pw' ).click( function () {
		$.post( '<?php echo admin_url( 'admin-ajax.php' ); ?>',
			{ action: 'generate-password' },
			function( response ) {
				if ( response.data ) {
					$( '#http_auth_password' ).val( response.data ).trigger( 'input' );
				}
			}, 'json' ).always( function() {} );
	} );
<?php } ?>
} )( jQuery );
</script>
