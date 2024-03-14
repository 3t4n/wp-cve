<?php
/**
 * Admin: Reset Password
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
use blobfolio\wp\meow\login;
use blobfolio\wp\meow\options;

// If a password reset is no longer needed, let's send them to the
// dashboard. At this point, headers have already been sent, so we need
// to do some cheap Javascript.
$redirect = '';
if (! login::password_require_reset_needed()) {
	$redirect = \admin_url('index.php');
}

// Let's parse the password requirements so we can properly inform
// affected users.
$pieces = array();

$alpha = options::get('password-alpha');
if ('required' === $alpha) {
	$pieces[] = \__('letter', 'apocalypse-meow') . ' <code>[a-z]</code>';
}
elseif ('required-both' === $alpha) {
	$pieces[] = \__('uppercase letter', 'apocalypse-meow') . ' <code>[A-Z]</code>';
	$pieces[] = \__('lowercase letter', 'apocalypse-meow') . ' <code>[a-z]</code>';
}

if ('required' === options::get('password-numeric')) {
	$pieces[] = \__('number', 'apocalypse-meow') . ' <code>[0-9]</code>';
}

if ('required' === options::get('password-symbol')) {
	$pieces[] = \__('symbol', 'apocalypse-meow') . ' <code>[$!;.?…]</code>';
}

// And finally a little data for Vue.
admin::json_meowdata(array(
	'shortCircuit'=>$redirect,
	'forms'=>array(
		'password'=>array(
			'action'=>'meow_ajax_retroactive_reset',
			'n'=>ajax::get_nonce(),
			'password'=>\wp_generate_password(options::MIN_PASSWORD_EXEMPT_LENGTH + 5, true),
			'errors'=>array(),
			'saved'=>false,
			'loading'=>false,
			'next'=>\admin_url('index.php'),
		),
		'generate'=>array(
			'action'=>'meow_ajax_retroactive_reset_generate',
			'n'=>ajax::get_nonce(),
			'errors'=>array(),
			'loading'=>false,
		),
	),
));
?>
<style>
	#meow-list {
		list-style: disc;
		padding-left: 3em;
	}

	#meow-postbox {
		max-width: 400px;
	}

	#meow-password-wrapper {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	#meow-password {
		width: calc(100% - 30px);
		height: 40px;
		font-family: 'Fira Mono', Consolas, Monaco, monospace;
	}

	#meow-regenerate {
		width: 20px;
		transition: color .3s ease;
		color: #ccc;
		cursor: pointer;
	}
	#meow-regenerate:hover { color: #0073aa; }
</style>
<div class="wrap" id="vue-password" v-cloak>
	<h1><?php echo \__('Choose a New Password', 'apocalypse-meow'); ?></h1>

	<!-- Error message(s). -->
	<div class="error" v-for="error in forms.password.errors"><p>{{ error }}</p></div>

	<!-- Success message. -->
	<div class="updated" v-if="forms.password.saved"><p><?php
		echo \__('Thank you for updating your password! You will now be redirected to the login page to complete the process.', 'apocalypse-meow')
	?></p></div>

	<p><?php echo \__('Please take a moment to update your login password. This site requires that passwords:', 'apocalypse-meow'); ?></p>

	<!-- List password requirements. -->
	<ul id="meow-list">
		<li>
			<?php
			\printf(
				\__('Be at least %d characters in length;', 'apocalypse-meow'),
				options::get('password-length')
			);
			?>
		</li>
		<li>
			<?php
			\printf(
				\__('Contain no fewer than %d different characters;', 'apocalypse-meow'),
				options::MIN_PASSWORD_CHARS
			);
			?>
		</li>
		<?php if (\count($pieces)) { ?>
			<li>
				<?php
				\printf(
					\__('Contain at least one of each of the following: %s', 'apocalypse-meow'),
					\implode(', ', $pieces) . '; <a href="#meow-footnote" style="text-decoration: none;"><sup>[1]</sup></a>'
				);
				?>
			</li>
		<?php } ?>
	</ul><!-- #meow-list -->

	<!-- The Password Form! -->
	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<div class="postbox-container">
				<div id="meow-postbox" class="postbox">
					<h3 class="hndle">
						<label for="meow-password"><?php echo \__('New Password', 'apocalypse-meow'); ?></label>
					</h3>
					<div class="inside">
						<form v-on:submit.prevent="passwordSubmit" name="password" id="meow-form" method="post" action="<?php echo \admin_url('admin-ajax.php'); ?>">
							<div id="meow-password-wrapper">
								<input type="text" autocomplete="off" name="password" id="meow-password" required v-model.trim="forms.password.password" />

								<a href="#" v-on:click.prevent="generateSubmit" id="meow-regenerate" class="dashicons dashicons-image-rotate"></a>
							</div>

							<p class="description"><?php
								echo \__('The above password is only a suggestion. Feel free to pick something more personal.', 'apocalypse-meow');
							?></p>

							<p>
								<button type="submit" class="button button-large button-primary" v-bind:disabled="forms.password.loading"><?php echo \__('Update Password', 'apocalypse-meow'); ?></button>
							</p>
						</form>
					</div><!-- .inside -->
				</div><!-- #meow-postbox -->
			</div><!-- .postbox-container -->
		</div><!-- #post-body -->
	</div><!-- #poststuff -->

	<?php if (\count($pieces)) { ?>
		<!-- A footnote. -->
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<code id="meow-footnote">1.
			<?php
			\printf(
				\__('Passwords containing %d or more characters are exempt from any letter/number/symbol requirements.', 'apocalypse-meow'),
				options::MIN_PASSWORD_EXEMPT_LENGTH
			);
			?>
		</code>
	<?php } ?>
</div><!-- .wrap -->
