<?php
/**
 * Add the settings menu link to the Settings menu in admin interface
 */
function wpsos_hp_add_settings_menu(){
	global $WPSOS_HP;
	//Create a new link to the settings menu
	//Returns the suffix of the page that can later be used in the actions etc
	$page = add_options_page(
			'.htaccess Site Access Control', //name of the settings page
			'.htaccess Site Access Control',
			'manage_options',
			'wpsos-site-access-control',
			'wpsos_hp_display_settings_page' //the function that is going to be called if the created page is loaded
	);
	
	//Add JS only to the admin page
	//add_action('admin_print_scripts-' . $page, 'wpsos_hp_register_scripts');
	
	//If a form was submitted
	if( isset( $_POST['wpsos_hp_add_user']) ){
		//Add action to call wpsos_hp_add_user function
		add_action( "admin_head-$page", array( $WPSOS_HP, 'add_user' ) );
	}
	else if( isset( $_POST['wpsos_hp_user_remove'] ) ){
		//Add action to call wpsos_hp_remove_user function
		add_action( "admin_head-$page", array( $WPSOS_HP, 'remove_user' ) );
	}
	else if( isset( $_POST['wpsos_hp_user_modify'] ) ){
		//Add action to call wpsos_hp_modify_user function
		add_action( "admin_head-$page", array( $WPSOS_HP, 'modify_user' ) );
	}
	else if( isset( $_POST['wpsos-hp-enable'] ) ){
		//Add action to call wpsos_hp_modify_user function
		add_action( "admin_head-$page", array( $WPSOS_HP, 'enable_disable_lock' ) );
	}
	
}
add_action( 'admin_menu', 'wpsos_hp_add_settings_menu' );

/**
 * Display the settings page in the admin interface
 */
function wpsos_hp_display_settings_page(){
	global $WPSOS_HP;
	?>
		<div id="wpsos" class="wrap">
			<div class="wpsos-global-notification">By using this plugin, you’re eligible for a 5% discount on <a href="http://www.wpsos.io/">WPSOS' security services</a>: virus cleanup, site securing and security maintenance!</div>
			<h2>WPSOS .htaccess Site Access Control</h2>
			
			<?php if( !$WPSOS_HP->plugin_has_sufficient_permissions() ): ?>
			
			<div class="form-wrapper warning not-working">
				<h4>The following files need to be writable by WordPress for this plugin to work:</h4>
				<p><?php echo $WPSOS_HP->htaccess_root; ?> - <?php echo $_SESSION['htaccess_root'] ? '(writable)' : '(not writable)'; ?></p>
				<p><?php echo $WPSOS_HP->htpasswd_file; ?> - <?php echo $_SESSION['htpasswd'] ? '(writable)' : '(not writable)'; ?></p>
				<p><?php echo $WPSOS_HP->htaccess_admin; ?> - <?php echo $_SESSION['htaccess_admin'] ? '(writable)' : '(not writable)'; ?></p>
			</div>
			
			<?php endif; ?>
			
			<?php if( isset( $_SESSION['wpsos_msg'] ) ): ?>
				<div class="updated"><p><strong><?php echo $_SESSION['wpsos_msg']; unset( $_SESSION['wpsos_msg'] ); ?></strong></p></div>
			<?php endif; ?>
			
				<?php $users = $WPSOS_HP->get_htpasswd_users(); ?>
				
				<div class="form-wrapper">
					<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					<h3>Enable/disable password protection</h3>
					<?php if( count( $users ) ): ?>
					<?php wp_nonce_field( 'wpsos-hp-enable' ); ?>
					<?php $options = unserialize( get_option( 'wpsos_hp_options' ) ); ?>
					<table class="form-table">
						<tr>
							<th>Protection for wp-admin</th>
							<td><label>
										<input type="radio" class="enable" name="wpsos-hp-enabled" value="1" <?php echo $options['wpsos_hp_enabled'] ? 'checked="checked"' : ''; ?>/>Enabled
									</label><br/>
									<label>
										<input type="radio" class="disable" name="wpsos-hp-enabled" value="0" <?php echo !$options['wpsos_hp_enabled'] ? 'checked="checked"' : ''; ?> />Disabled
									</label>
									<p class="subnote">By enabling this setting, you will probably be asked for the password as soon as you save this option (or go to any other admin page) - so be careful.</p>
							</td>
						</tr>
						<tr>
							<th>Protection for wp-login.php</th>
							<td><label>
									<input type="radio" class="enable" name="wpsos-hp-login" value="1" <?php echo $options['wpsos_hp_login_pwd_enabled'] ? 'checked="checked"' : ''; ?>/>Enabled
								</label><br/>
								<label>
									<input type="radio" class="disable" name="wpsos-hp-login" value="0" <?php echo $options['wpsos_hp_login_pwd_enabled'] == 0 ? 'checked="checked"' : ''; ?> />Disabled
								</label>
								<p class="subnote">By enabling this setting, you will be asked for this password before you can access the login page again. This is in addition to and separate from the WordPress login.</p>	
							</td>				
						</tr>
						<tr>
							<th>Protection for the whole site</th>
								<td><br/><p class="warning">Protecting the whole site is a premium feature. <a target="_blank" href="https://www.wpsos.io/wordpress-plugin-htaccess-site-access-control/">Get the plugin now.</a></p>
							<br/><label>
										<input type="radio" name="" value="1" disabled="disabled"/>Enabled
									</label><br/>
									<label>
										<input type="radio" name="" value="0" checked="checked" disabled="disabled" />Disabled
									</label>
									<p class="subnote">By enabling this setting, you will be asked for this password before you can access ANY PAGE again. Only the people with username/password will be able to see any page of the site - be careful!</p>	
								</td>				
							</tr>
					</table>
					<div class="pass-check"><p><strong>You must know your lockout username/password for enabling these settings for not getting locked out. Enter the username and password here:</strong></p>
							<input name="test_username" type="text" placeholder="Username" /><input name="test_password" type="password" placeholder="Password">
					</div>
						
					<p>
						<input class="submit" type="submit" value="<?php _e( 'Save' ); ?>" name="wpsos-hp-enable">
					</p>
					</form>
					
					<?php else: ?>
						<p class="info"><?php _e( 'You have to have at least one user to enable the password protection' ); ?></p>
					<?php endif; ?>
					
				</div><!-- end .form-wrapper -->
				<div class="form-wrapper" id="users-modify">
					<h3>Modify users</h3>
					<table class="form-table">
					<thead>
						<th>Username</th>
						<th>Modify password</th>
						<th>Remove user</th>
					</thead>
					<?php foreach( $users as $user ): ?>
					<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
						<?php wp_nonce_field( "wpsos-hp-$user" ); ?>
						<tr>
							<td>
								<strong><?php echo $user; ?></strong>
							</td>
							<td>
								<input type="password" name="pwd_user" placeholder="New password" />
								<input class="submit" type="submit" value="Change password" name="wpsos_hp_user_modify" />
							</td>
							<td>
								<input type="hidden" name="username" value="<?php echo $user; ?>" />
								<input class="remove" type="submit" value="Remove User" name="wpsos_hp_user_remove" />
							</td>
						</tr>
					</form>
					<?php endforeach; ?>	
					</table>
				</div>
				<div class="form-wrapper">
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<h3>Add new user</h3>
				<?php if( count( $users )>0 ): ?>
					<p class="warning">Adding more than 1 user is a premium feature. <a target="_blank" href="https://www.wpsos.io/wordpress-plugin-htaccess-site-access-control/">Get the plugin now.</a></p>
				<?php endif; ?>
				<?php wp_nonce_field( 'wpsos-hp-add-user' ); ?>
				<table class="form-table">
					<tr>
						<th>
							<?php _e( 'Username' ); ?>
						</th>
						<td><input <?php echo count( $users )>0 ? "disabled " : ''; ?>name="new_username" type="text" /></td>
					</tr>
					<tr>
						<th><?php _e( 'Password' ); ?></th>
						<td>
							<input <?php echo count( $users )>0 ? "disabled " : ''; ?>name="new_password" type="password" />
						</td>
					</tr>	
				</table>
				<p>
					<input class="submit" <?php echo count( $users )>0 ? "disabled " : ''; ?>type="submit" value="<?php _e( 'Add user' ); ?>" name="wpsos_hp_add_user">
				</p>
			</form>
			</div>
		</div>
	<?php
}
/**
 * Add links to WPSOS
 */
function wpsos_hp_set_plugin_meta( $links, $file ) {

	if ( strpos( $file, 'htaccess-site-access-control.php' ) !== false ) {

		$links = array_merge( $links, array( '<a href="' . get_admin_url() . 'options-general.php?page=wpsos-site-access-control">' . __( 'Settings' ) . '</a>' ) );
		$links = array_merge( $links, array( '<a href="http://www.wpsos.io/">WPSOS - WordPress Security & Hack Repair</a>' ) );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'wpsos_hp_set_plugin_meta', 10, 2 );
?>
