<?php
/**
 * Admin: Settings
 *
 * @package Apocalypse Meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

/**
 * Do not execute this file directly.
 */
if (! \defined('ABSPATH')) {
	exit;
}

use blobfolio\wp\meow\admin;
use blobfolio\wp\meow\ajax;
use blobfolio\wp\meow\vendor\common;



global $wpdb;
$current_user = \wp_get_current_user();

$data = array(
	'forms'=>array(
		'md5'=>array(
			'action'=>'meow_ajax_tools_md5',
			'n'=>ajax::get_nonce(),
			'errors'=>array(),
			'loading'=>false,
			'hasMD5'=>!! \intval($wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->users}` WHERE `user_pass` REGEXP '^[A-Fa-f0-9]{32}$'")),
		),
		'reset'=>array(
			'action'=>'meow_ajax_tools_reset',
			'n'=>ajax::get_nonce(),
			'email'=>0,
			'message'=>\sprintf(
				\__('The %s system administrator has reset all user passwords.', 'apocalypse-meow'),
				common\format::decode_entities(\get_bloginfo('name'))
				) . "\n\n" .
				\__('To regain access to your account, visit the following link and choose a new password:', 'apocalypse-meow'),
			'errors'=>array(),
			'loading'=>false,
			'last'=>0,
		),
		'admin'=>array(
			'action'=>'meow_ajax_tools_admin',
			'n'=>ajax::get_nonce(),
			'admin'=>'',
			'administrator'=>'',
			'errors'=>array(),
			'loading'=>false,
			'hasAdmin'=>\username_exists('admin'),
			'hasAdministrator'=>\username_exists('administrator'),
		),
		'sessions'=>array(
			'action'=>'meow_ajax_tools_sessions',
			'n'=>ajax::get_nonce(),
			'errors'=>array(),
			'loading'=>false,
		),
		'sessionDelete'=>array(
			'action'=>'meow_ajax_tools_session_delete',
			'n'=>ajax::get_nonce(),
			'user_id'=>0,
			'session_id'=>'',
			'errors'=>array(),
			'loading'=>false,
		),
	),
	'msg'=>'',
	'sessions'=>array(),
	'activeSession'=>\hash('sha256', \wp_get_session_token()),
	'section'=>'passwords',
	'modal'=>false,
	// @codingStandardsIgnoreStart
	'modals'=>array(
		'passwords-md5'=>array(
			sprintf(
				__('For historical reasons, WordPress has retained backward compatibility with the outdated %s hashing algorithm. Should a hacker obtain a copy of your %s table, any user with an MD5-hashed password could be in serious trouble.', 'apocalypse-meow'),
				'<a href="https://en.wikipedia.org/wiki/MD5" target="_blank" rel="noopener">MD5</a>',
				"<code>{$wpdb->users}</code>"
			),
			__('This tool will securely rehash any insecure MD5 password hashes in the database. This will lock affected users out of their account (until they reset their passwords), however these users have likely been absent from the site for many years.', 'apocalypse-meow'),
			'<a href="https://en.wikipedia.org/wiki/MD5" target="_blank" rel="noopener">MD5</a>'
		),
		'passwords-reset'=>array(
			__('This will immediately reset all user passwords site-wide. To regain account access, each user will need to complete the "Forgot Password" process.', 'apocalypse-meow'),
			__('If your site or database has been breached, or you suspect it has, run this tool immediately.', 'apocalypse-meow'),
		),
		'passwords-reset-email'=>array(
			__("Check this box if you wish to start the \"Forgot Password\" process on behalf of users. They'll each be sent an email with the message you specify and a link to choose a new password. This is a user-friendly choice, but there are a few downsides to consider:", 'apocalypse-meow'),
			'&nbsp;&nbsp;&nbsp;&ndash;&nbsp;' . __('Sending lots and lots of emails can take a long time.', 'apocalypse-meow'),
			'&nbsp;&nbsp;&nbsp;&ndash;&nbsp;' . __('Sending lots and lots of emails might violate your hosting provider\'s ToS.', 'apocalypse-meow'),
			'&nbsp;&nbsp;&nbsp;&ndash;&nbsp;' . __('The reset links will be transmitted in plain text across an insecure channel (i.e. email); they could be intercepted by third parties and used to gain unauthorized account access.', 'apocalypse-meow'),
			sprintf(
				__('Note: this plugin implements its own version of the %s function to make it more performant at scale. As a consequence, it does not trigger all of the action/filter hooks the WP version does. If for some reason your site is relying on any of these obscure hooks, this option should probably be avoided.', 'apocalypse-meow'),
				'<a href="https://developer.wordpress.org/reference/functions/get_password_reset_key/" target="_blank" rel="noopener">get_password_reset_key</a>'
			)
		),
		'users-admin'=>array(
			__('The majority of WordPress sites have a user called "admin" or "administrator", and almost all brute-force attacks specifically target one or the other. You should rename these users immediately to moot such attacks.', 'apocalypse-meow')
		),
		'sessions'=>array(
			__("WordPress generates a unique Session ID each time a user logs into the site. Aside from providing some useful diagnostic information, such as browser and network information, it also provides a server-side mechanism for continually revalidating the session (i.e. regardless of whether or not the user's computer has the right cookie).", 'apocalypse-meow'),
			__("If something doesn't look right, you can revoke the session and that user will be immediately logged out. Just be sure to regenerate that account's password afterward or else they'll be able to hop right back in.", 'apocalypse-meow')
		),
	)
	// @codingStandardsIgnoreEnd
);



// JSON doesn't appreciate broken UTF.
admin::json_meowdata($data);
?>
<div class="wrap" id="vue-tools" v-cloak>
	<h1>Apocalypse Meow: <?php echo \__('Tools', 'apocalypse-meow'); ?></h1>



	<div class="updated" v-if="msg"><p>{{ msg }}</p></div>

	<div class="error" v-for="error in forms.md5.errors"><p>{{ error }}</p></div>
	<div class="error" v-for="error in forms.reset.errors"><p>{{ error }}</p></div>
	<div class="error" v-for="error in forms.admin.errors"><p>{{ error }}</p></div>



	<p>&nbsp;</p>
	<h3 class="nav-tab-wrapper">
		<a style="cursor: pointer;" class="nav-tab" v-bind:class="{'nav-tab-active' : section === 'passwords'}" v-on:click.prevent="toggleSection('passwords')"><?php echo \__('Passwords', 'apocalypse-meow'); ?></a>


		<a style="cursor: pointer;" v-if="forms.admin.hasAdministrator || forms.admin.hasAdmin" class="nav-tab" v-bind:class="{'nav-tab-active' : section === 'users'}" v-on:click.prevent="toggleSection('users')"><?php echo \__('Users', 'apocalypse-meow'); ?></a>

		<a style="cursor: pointer;" v-if="sessions.length" class="nav-tab" v-bind:class="{'nav-tab-active' : section === 'sessions'}" v-on:click.prevent="toggleSection('sessions')"><?php echo \__('Sessions', 'apocalypse-meow'); ?></a>
	</h3>



	<div id="poststuff">
		<div id="post-body" class="metabox-holder meow-columns one-two fixed" v-bind:class="{fluid: section !== 'sessions'}">

			<!-- Tools -->
			<div class="postbox-container two">
				<!-- ==============================================
				PASSWORD RESET
				=============================================== -->
				<div class="meow-fluid-tile" v-if="section === 'passwords'">
					<div class="postbox">
						<h3 class="hndle">
							<?php echo \__('Reset All Passwords', 'apocalypse-meow'); ?>
							<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'passwords-reset'}" v-on:click.prevent="toggleModal('passwords-reset')"></span>
						</h3>
						<div class="inside">
							<form method="post" action="<?php echo \admin_url('admin-ajax.php'); ?>" name="resetForm" v-on:submit.prevent="resetSubmit">
								<fieldset class="meow-fieldset">
									<label class="checkbox">
										<input type="checkbox" v-model.number="forms.reset.email" v-bind:true-value="1" v-bind:false-value="0" />
										<?php echo \__('Send Email Notifications', 'apocalypse-meow'); ?>
										<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'passwords-reset-email'}" v-on:click.prevent="toggleModal('passwords-reset-email')"></span>
									</label>
								</fieldset>

								<fieldset class="meow-fieldset" v-if="forms.reset.email">
									<label class="meow-label" for="passwords-reset-message">
										<?php echo \__('Email Message', 'apocalypse-meow'); ?>
									</label>

									<textarea v-model.trim="forms.reset.message" required></textarea>

									<p class="description"><?php echo \__('Note: the appropriate link will be appended automatically.', 'apocalypse-meow'); ?></p>
								</fieldset>

								<p><button type="submit" class="button button-primary button-large"><?php echo \__('Reset', 'apocalypse-meow'); ?></button></p>

								<p class="description"><?php echo \__('Note: this will probably log you out.', 'apocalypse-meow'); ?></p>
							</form>
						</div>
					</div>
				</div>



				<!-- ==============================================
				MD5 PASSWORDS
				=============================================== -->
				<div class="meow-fluid-tile" v-if="section === 'passwords' && forms.md5.hasMD5">
					<div class="postbox">
						<h3 class="hndle">
							<?php echo \__('Fix MD5 Passwords', 'apocalypse-meow'); ?>
							<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'passwords-md5'}" v-on:click.prevent="toggleModal('passwords-md5')"></span>
						</h3>
						<div class="inside">
							<form method="post" action="<?php echo \admin_url('admin-ajax.php'); ?>" name="md5Form" v-on:submit.prevent="md5Submit">
								<p><?php echo \__('The site database contains one or more passwords encrypted with the woefully insecure MD5 hasing algorithm. WP will re-encrypt them automatically the next time the user logs in, but that may not be soon enough.', 'apocalypse-meow'); ?></p>

								<p><?php echo \__('Click the button below to reset and re-encrypt these passwords now.', 'apocalypse-meow'); ?></p>

								<p><button type="submit" class="button button-primary button-large"><?php echo \__('Reset', 'apocalypse-meow'); ?></button></p>

								<p class="description"><?php echo \__('Note: the affected users will need to complete the Forgot Password process to regain access to the site.', 'apocalypse-meow'); ?></p>
							</form>
						</div>
					</div>
				</div>



				<!-- ==============================================
				ADMIN USER
				=============================================== -->
				<div class="meow-fluid-tile" v-if="section === 'users' && (forms.admin.hasAdmin || forms.admin.hasAdministrator)">
					<div class="postbox">
						<h3 class="hndle">
							<?php echo \__('Default Username', 'apocalypse-meow'); ?>
							<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'users-admin'}" v-on:click.prevent="toggleModal('users-admin')"></span>
						</h3>
						<div class="inside">
							<form method="post" action="<?php echo \admin_url('admin-ajax.php'); ?>" name="adminForm" v-on:submit.prevent="adminSubmit">

								<fieldset class="meow-fieldset" v-if="forms.admin.hasAdmin">
									<label class="meow-label" for="users-admin-admin"><?php echo \__('Rename', 'apocalypse-meow'); ?> "admin"</label>
									<input id="users-admin-admin" type="text" v-model.trim="forms.admin.admin" required />
								</fieldset>

								<fieldset class="meow-fieldset" v-if="forms.admin.hasAdministrator">
									<label class="meow-label" for="users-admin-administrator"><?php echo \__('Rename', 'apocalypse-meow'); ?> "administrator"</label>
									<input id="users-admin-administrator" type="text" v-model.trim="forms.admin.administrator" required />
								</fieldset>

								<p><button type="submit" class="button button-primary button-large"><?php echo \__('Rename', 'apocalypse-meow'); ?></button></p>

								<?php if (common\data::iin_array($current_user->user_login, array('admin', 'administrator'), true)) { ?>
									<p class="description"><?php echo \__('Note: this will probably log you out.', 'apocalypse-meow'); ?></p>
								<?php } ?>
							</form>
						</div>
					</div>
				</div>



				<!-- ==============================================
				USER SESSIONS
				=============================================== -->
				<div class="meow-fluid-tile wide" v-if="section === 'sessions' && sessions.length">
					<div class="postbox">
						<h3 class="hndle">
							<?php echo \__('Active User Sessions', 'apocalypse-meow'); ?>
							<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'sessions'}" v-on:click.prevent="toggleModal('sessions')"></span>
						</h3>
						<div class="inside">
							<table class="meow-results">
								<thead>
									<tr>
										<th><?php echo \__('ID', 'apocalypse-meow'); ?></th>
										<th><?php echo \__('User', 'apocalypse-meow'); ?></th>
										<th><?php echo \__('Login', 'apocalypse-meow'); ?></th>
										<th><?php echo \__('Sessions', 'apocalypse-meow'); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="item in sessions">
										<td>{{ item.user_id }}</td>
										<td>{{ item.email }}</td>
										<td>{{ item.login }}</td>
										<td>
											<div class="meow-jail" v-for="session in item.sessions">
												<table class="meow-meta">
													<tbody>
														<tr>
															<th scope="row">Created</th>
															<td>{{ session.date_created }}</td>
														</tr>
														<tr>
															<th scope="row">Expires</th>
															<td>{{ session.date_expires }}</td>
														</tr>
														<tr>
															<th scope="row">IP</th>
															<td><a v-bind:href="'https://geoiptool.com/en/?IP=' + session.ip" target="_blank" rel="noopener">{{ session.ip }}</a></td>
														</tr>
														<tr>
															<th scope="row">Browser</th>
															<td>{{ session.ua }}</td>
														</tr>
														<tr v-if="session.session_id !== activeSession">
															<td></td>
															<td>
																<button type="button" class="button button-small" v-on:click.prevent="sessionDeleteSubmit(item.user_id, session.session_id)" v-bind:disabled="forms.sessionDelete.loading"><?php
																	echo \__('Revoke Session', 'apocalypse-meow');
																?></button>
															</td>
														</tr>
														<tr v-else>
															<th scope="row"><?php echo \__('Status', 'apocalypse-meow'); ?></th>
															<td><strong><?php echo \__('Your Current Session', 'apocalypse-meow'); ?> :)</strong></td>
														</tr>
													</tbody>
												</table>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>


			</div><!--.postbox-container-->



			<!-- Sidebar -->
			<div class="postbox-container one">

				<!-- ==============================================
				WP-CLI
				=============================================== -->
				<div class="postbox">
					<h3 class="hndle"><?php echo \__('Command Line', 'apocalypse-meow'); ?></h3>
					<div class="inside">
						<p class="description"><?php
						\printf(
							\__('These and other tools can also be accessed via %s!', 'apocalypse-meow'),
							'<a href="https://wp-cli.org/" target="_blank" rel="noopener">WP-CLI</a>'
						);
						?></p>

						<p class="description"><?php echo \__('For instructions, type the following from your site root:', 'apocalypse-meow'); ?></p>

						<pre class="language-bash"><code>wp meow --help</code></pre>
					</div>
				</div>

			</div><!--.postbox-container-->

		</div><!--#post-body-->
	</div><!--#poststuff-->



	<!-- ==============================================
	HELP MODAL
	=============================================== -->
	<transition name="fade">
		<div v-if="modal" class="meow-modal">
			<span class="dashicons dashicons-dismiss meow-modal--close" v-on:click.prevent="toggleModal('')"></span>
			<img src="<?php echo \MEOW_PLUGIN_URL; ?>img/kitten.gif" class="meow-modal--cat" alt="Kitten" />
			<div class="meow-modal--inner">
				<p v-for="p in modals[modal]" v-html="p"></p>
			</div>
		</div>
	</transition>

</div><!--.wrap-->
