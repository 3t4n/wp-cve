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

use blobfolio\wp\meow\about;
use blobfolio\wp\meow\admin;
use blobfolio\wp\meow\ajax;
use blobfolio\wp\meow\core;
use blobfolio\wp\meow\login;
use blobfolio\wp\meow\options;
use blobfolio\wp\meow\vendor\common;

$current_user = \wp_get_current_user();

$data = array(
	'forms'=>array(
		'settings'=>array(
			'action'=>'meow_ajax_settings',
			'n'=>ajax::get_nonce(),
			'errors'=>array(),
			'saved'=>false,
			'loading'=>false,
		),
		'reset'=>array(
			'action'=>'meow_ajax_reset',
			'n'=>ajax::get_nonce(),
			'errors'=>array(),
			'saved'=>false,
			'loading'=>false,
		),
	),
	'readonly'=>options::get_readonly(),
	'section'=>'settings',
	'modal'=>false,
	// @codingStandardsIgnoreStart
	'modals'=>array(
		'brute-force'=>array(
			sprintf(
				__('%s robots visit WordPress dozens of times each day, attempting to guess their way into wp-admin. WordPress makes no attempt to mitigate this, allowing a single robot to try combination after combination until they succeed.', 'apocalypse-meow'),
				'<a href="https://en.wikipedia.org/wiki/Brute-force_attack" target="_blank" rel="noopener">' . __('Brute-force', 'apocalypse-meow') . '</a>'
			),
			__('Apocalypse Meow keeps track of login attempts and will temporarily ban any person or robot who has failed too much, too fast. This is critical set-and-forget protection.', 'apocalypse-meow')
		),
		'login-fail_limit'=>array(
			__('This is the maximum number of login failures allowed for a given IP before the login process is disabled for that individual.', 'apocalypse-meow')
		),
		'login-subnet_fail_limit'=>array(
			sprintf(
				__('Sometimes attacks come from multiple IPs on the same network. This limit applies to the number of failures attributed to a network subnet (%s for IPv4 and %s for IPv6). It is recommended you set this value 4-5x higher than the individual fail limit.', 'apocalypse-meow'),
				'<code>/24</code>',
				'<code>/64</code>'
			)
		),
		'login-fail_window'=>array(
			__('An individual IP or entire network subnet will be banned from logging in whenever their total number of failures within this window exceeds the fail limits.', 'apocalypse-meow'),
			__('The ban lasts as long as this is true, and will reset when the earliest of the counted failures grows old enough to fall outside the window. Remaining failures, if any, that are still within the window will continue to be matched against the fail limits.', 'apocalypse-meow'),
			__('For reference, the default value of 720 translates to 12 hours.')
		),
		'login-reset_on_success'=>array(
			__('When someone successfully logs in, their prior failures are no longer counted against them, even if those failures are still within the window.', 'apocalypse-meow'),
		),
		'login-key'=>array(
			sprintf(
				__("Most servers report the remote visitor's IP address using the %s key, but if yours is living behind a proxy, the IP information might live somewhere else. If you aren't sure what to do, look for your IP address in the list.", 'apocalypse-meow'),
				'<code>REMOTE_ADDR</code>'
			),
			__('Note: visitor IP information forwarded from a proxy is not trustworthy because it is populated via request headers, which can be forged. Depending on the setup of your particular environment, this may make it impossible to effectively mitigate brute-force attacks.', 'apocalypse-meow')
		),
		'login-whitelist'=>array(
			__('It is very important you avoid getting yourself or your coworkers banned (the latter happens frequently in office environments where multiple employees fail around the same time). You should whitelist any IP addresses, ranges, or subnets from which you will be connecting.', 'apocalypse-meow'),
		),
		'login-blacklist'=>array(
			__('Where there is light, there is darkness. If a particular network is consistently harassing you, you can add their IP address, range, or subnet to the blacklist. Any entry here will never be able to access the login form.', 'apocalypse-meow'),
			__("Be very careful! If you do something silly like add yourself to the blacklist, you'll be locked out of the site.", 'apocalypse-meow'),
		),
		'login-nonce'=>array(
			sprintf(
				__('This option adds a hidden field to the standard %s form to help ensure that login attempts are actually originating there (rather than coming out of the blue, as is typical of robotic assaults).', 'apocalypse-meow'),
				'<code>wp-login.php</code>'
			),
			__('*Do not* enable this option if your site uses custom login forms or if the login page is cached.', 'apocalypse-meow'),
		),
		'login-alert_on_new'=>array(
			__('This will send an email to the account user whenever access is granted to an IP address that has not successfully logged in before.', 'apocalypse-meow'),
			__('Note: this depends on the data logged by the plugin, so if you have configured a short retention time, it may not be very useful.', 'apocalypse-meow')
		),
		'login-alert_by_subnet'=>array(
			__('This will cause the email alert function to use subnets rather than individual IPs when determining "newness". This setting is recommended for IPv6 users in particular as their IPs will change frequently.', 'apocalypse-meow'),
		),
		'passwords'=>array(
			__('Strong, unique passwords are critical for security. For historical reasons, WordPress still allows users to choose unsafe passwords for themselves. These options set some basic boundaries.', 'apocalypse-meow'),
			__('Note: because WordPress passwords are encrypted, it is not possible to apply these settings retroactively. However when users log in, if their passwords are unsafe, they will be directed to change it.')
		),
		'password-alpha'=>array(
			__('Whether or not a password must have letters in it. The third option, "UPPER & lower", requires a password contain a mixture of both upper- and lowercase letters.', 'apocalypse-meow'),
		),
		'password-numeric'=>array(
			__('Whether or not a password must have numbers in it.', 'apocalypse-meow'),
		),
		'password-symbol'=>array(
			__('Whether or not a password must have non-alphanumeric characters in it, like a cartoon curse word: $!#*()%.', 'apocalypse-meow'),
		),
		'password-length'=>array(
			__("This sets a minimum length requirement for passwords. The plugin's own minimum minimum (how low you are allowed to set it) is subject to change as technology advances. If your entry falls below the times, it will be adjusted automatically.", 'apocalypse-meow'),
		),
		'password-exempt_length'=>array(
			__('Pedantic password rules (like all the above) are well and good for short passwords, but really length is key. For users who want to choose a strong passphrase for themselves, such rules just get in the way.', 'apocalypse-meow'),
			__('This option sets the minimum length at which a password can be considered exempt from needing specific contents like letters or numbers.', 'apocalypse-meow'),
		),
		'password-common'=>array(
			__('Apocalypse Meow automatically prevents users from choosing any of the top 100K most common passwords. This protection is mandatory and cannot be disabled. ;)', 'apocalypse-meow'),
		),
		'password-bcrypt'=>array(
			sprintf(
				__("This option replaces WordPress' password hashing algorithm with the more modern and secure %s. This will help protect your users in the event a hacker gains access to the site database by making the user passwords much more time-consuming to crack.", 'apocalypse-meow'),
				'<a href="https://en.wikipedia.org/wiki/Bcrypt" target="_blank" rel="noopener">bcrypt</a>'
			),
			__('There are a few things worth noting:', 'apocalypse-meow'),
			sprintf(
				__('Bcrypt is scalable. When this setting is first enabled, Apocalypse Meow will calculate the best security/performance balance for your server. If you ever change hosting or upgrade the server hardware, just toggle the option %s then back %s to recalibrate.', 'apocalypse-meow'),
				'<code>' . __('Disabled', 'apocalypse-meow') . '</code>',
				'<code>' . __('Enabled', 'apocalypse-meow') . '</code>'
			),
			__('This option does not retroactively alter existing password hashes. When enabled, hashes will be updated as each user logs into the site. When disabled, WordPress will dutifully process any logins with bcrypt-hashed passwords, but will not downgrade them.', 'apocalypse-meow'),
		),
		'password-retroactive'=>array(
			__('When enabled, if an existing user logs in with a password that does not meet the current site requirements, they will be redirected to their profile and asked to come up with something stronger.', 'apocalypse-meow'),
		),
		'core'=>array(
			__('Out-of-the-Box, certain WordPress features and frontend oversights on the part of theme and plugin developers, can inadvertently place sites at greater risk of being successfully exploited by a hacker.', 'apocalypse-meow'),
			__('Please make sure you read about each option before flipping any switches. While each of these items mitigates a threat, some threats are more threatening than others, and if your workflow depends on something you disable here, that might make life sad.', 'apocalypse-meow')
		),
		'template-adjacent_posts'=>array(
			sprintf(
				__("WordPress adds information about next and previous posts in the HTML %s. This isn't usually a big deal, but can help robots find pages you thought were private. This is just robot food, so you can safely remove it.", 'apocalypse-meow'),
				'<code>&lt;head&gt;</code>'
			)
		),
		'core-file_edit'=>array(
			__('WordPress comes with the ability to edit theme and plugin files directly through the browser. If an attacker gains access to WordPress, they too can make such changes.', 'apocalypse-meow'),
			__('Please just use FTP to push code changes to the site. Haha.', 'apocalypse-meow'),
			sprintf(
				__('Note: This will have no effect if the %s constant is defined elsewhere.', 'apocalypse-meow'),
				'<code>DISALLOW_FILE_EDIT</code>'
			)
		),
		'template-generator_tag'=>array(
			sprintf(
				__('By default, WordPress embeds a version tag in the HTML %s. While this information is largely innocuous (and discoverable elsewhere), it can help nogoodniks better target attacks against your site. Since this is really only something a robot would see, it is safe to remove.', 'apocalypse-meow'),
				'<code>&lt;head&gt;</code>'
			)
		),
		'template-readme'=>array(
			__('WordPress releases include a publicly accessible file detailing the version information. This is one of the first things a hacker will look for as it will help them better target their attacks.', 'apocalypse-meow'),
			(
				@file_exists(trailingslashit(ABSPATH) . 'readme.html') ? sprintf(
						__('Click %s to view yours.', 'apocalypse-meow'),
						'<a href="' . esc_url(site_url('readme.html')) . '" target="_blank" rel="noopener">' . __('here', 'apocalypse-meow') . '</a>'
					) : __('Your site does not have one right now. Woo!', 'apocalypse-meow')
			)
		),
		'template-noopener'=>array(
			sprintf(
				__("Any links on your site that open in a new window (e.g. %s) could potentially trigger a redirect in *your* site's window. This opens the door to some sneaky phishing attacks. See %s and %s for more information.", 'apocalypse-meow'),
				'<code>target="blank"</code>',
				'<a href="https://dev.to/ben/the-targetblank-vulnerability-by-example" target="_blank" rel="noopener">' . __('here', 'apocalypse-meow') . '</a>',
				'<a href="https://mathiasbynens.github.io/rel-noopener/" target="_blank" rel="noopener">' . __('here', 'apocalypse-meow') . '</a>'
			),
			sprintf(
				__("This option adds %s to vulnerable links on your site, which is meant to disable this capability. It is a lightweight and non-destructive approach, but doesn't protect all browsers.", 'apocalypse-meow'),
				'<code>rel="noopener"</code>'
			),
			sprintf(
				__('For a more comprehensive solution, take a look at %s.', 'apocalypse-meow'),
				'<a href="https://github.com/danielstjules/blankshield" target="_blank" rel="noopener">blankshield</a>'
			)
		),
		'enumeration'=>array(
			sprintf(
				__("Ever wonder how a robot guessed your username? There's a neat trick that exploits a weakness in WordPress' permalink rewriting: visit %s and you should be redirected to a pretty URL ending in your username (unless Apocalypse Meow stops it). Robots simply try %s, %s, etc.", 'apocalypse-meow'),
				'<a href="' . site_url('?author=' . $current_user->ID) . '" target="_blank" rel="noopener">' . site_url('?author=' . $current_user->ID) . '</a>',
				'<code>?author=1</code>',
				'<code>?author=2</code>'
			),
		),
		'core-enumeration'=>array(
			sprintf(
				__("This setting blacklists the %s query variable so it cannot be used by robots… or anyone. Do not enable this setting if any of your themes or plugins lazy-link to an author's ID instead of their actual archive URL.", 'apocalypse-meow'),
				'<code>author</code>'
			),
			sprintf(
				__('Note: this setting will also disable the WP-REST %s endpoint in WordPress versions 4.7+.', 'apocalypse-meow'),
				'<code>users</code>'
			)
		),
		'core-enumeration_die'=>array(
			sprintf(
				__('By default, this plugin simply redirects any %s requests to the home page. But if you enable this option, it will instead trigger a 400 error and exit. This approach uses fewer resources and can more easily integrate with general log-monitoring policies.', 'apocalypse-meow'),
				'<code>?author=X</code>'
			),
			__('Note: WP-REST requests will always result in an API error.', 'apocalypse-meow')
		),
		'core-enumeration_fail'=>array(
			__('When enabled, user enumeration attempts will be counted as login failures. You probably want to enable this as user enumeration usually precedes a login attack.', 'apocalypse-meow'),
			sprintf(
				__('For tracking purposes, the "username" for these log entries will always read "%s".', 'apocalypse-meow'),
				core::ENUMERATION_USERNAME
			),
		),
		'core-browse_happy'=>array(
			__('When a user logs into WordPress, information about their web browser is sent to the WP.org API to check for possible support or security issues.', 'apocalypse-meow'),
			__('For most sites, this mandatory remote request is helpful — the average web surfer does not apply regular software updates — but there are privacy and performance implications to consider.', 'apocalypse-meow'),
			__('If public registration is disabled and everyone with a user account is tech-savvy, you can disable this check to eliminate the information leak.', 'apocalypse-meow'),
		),
		'core-dashboard_news'=>array(
			__('By default, the WordPress Dashboard contains an Events and News feed. This information is remotely fetched, adding delay to the initial page load.', 'apocalypse-meow'),
			__('If you find this information useful, keep it. Otherwise you can enable this option to remove it.', 'apocalypse-meow'),
		),
		'core-xmlrpc'=>array(
			sprintf(
				__("WordPress comes with an %s API to let users manage their blog content from mobile apps and other web sites. This is good stuff, but is also a common (and powerful) entry point for hackers. If you aren't using it, disable it.", 'apocalypse-meow'),
				'<a href="https://codex.wordpress.org/XML-RPC_Support" target="_blank" rel="noopener">XML-RPC</a>'
			),
			sprintf(
				__('Some plugins, like %s, will not work correctly with XML-RPC disabled. If something breaks, just re-enable it.', 'apocalypse-meow'),
				'<a href="https://wordpress.org/plugins/jetpack/" target="_blank" rel="noopener">Jetpack</a>'
			)
		),
		'prune'=>array(
			__('Brute-force login prevention relies on record-keeping. Over time, with lots of activity, that data might start to pose storage or performance problems. Apocalypse Meow can be configured to automatically remove old data.', 'apocalypse-meow'),
		),
		'prune-active'=>array(
			__('Enable this option to ease your server of the burden of keeping indefinite login activity records.', 'apocalypse-meow')
		),
		'prune-limit'=>array(
			__("Data older than this will be automatically pruned. It's a balance. Don't be too stingy or features like New Login Alerts won't be as effective. For most sites, it is a good idea to maintain at least 3 months worth of data.", 'apocalypse-meow')
		),
		'request'=>array(
			sprintf(
				__('The server returns various %s with every HTTP request. This information helps web browsers make sense of the returned content, and can also be used to enable or disable features, some of which have security implications.', 'apocalypse-meow'),
				'<a href="https://en.wikipedia.org/wiki/List_of_HTTP_header_fields" target="_blank" rel="noopener">' . __('headers', 'apocalypse-meow') . '</a>'
			),
			__('Not all browsers honor or understand all headers, but the settings in this section can help improve the security for users with browsers that do.', 'apocalypse-meow'),
		),
		'template-referrer_policy'=>array(
			sprintf(
				__('By default, when a visitor clicks a link, the browser will send the URL of the referring page to the new server. For example, if you click %s to learn about the %s header, %s would know you came from %s.', 'apocalypse-meow'),
				'<a href="https://blog.appcanary.com/2017/http-security-headers.html#referrer-policy" target="_blank" rel="noopener">here</a>',
				'<code>Referrer-Policy</code>',
				'<code>blog.appcanary.com</code>',
				'<code>' . admin_url('admin.php?page=meow-settings') . '</code>'
			),
			sprintf(
				__('That is, unless, your server tells it not to (and the browser listens). When set to %s, a policy of %s will be set, sharing only your domain name. When set to %s, a policy of %s will be set, sharing nothing.', 'apocalypse-meow'),
				'<code>' . __('Limited', 'apocalypse-meow') . '</code>',
				'<code>origin-when-cross-origin</code>',
				'<code>' . __('None', 'apocalypse-meow') . '</code>',
				'<code>no-referrer</code>'
			),
			sprintf(
				__('While a %s referrer policy is generally considered best practice, some (old) WordPress architecture relying on %s — such as password-protected posts — won\'t work correctly without referrer headers being set.', 'apocalypse-meow'),
				'<code>' . __('None', 'apocalypse-meow') . '</code>',
				'<code>wp_get_referer()</code>'
			),
			sprintf(
				__("Referrer Policies can be set in other ways, so if you don't need or want Apocalypse Meow to set any headers at all, leave this option set to %s.", 'apocalypse-meow'),
				'<code>' . __('Default', 'apocalypse-meow') . '</code>'
			),
		),
		'template-x_content_type'=>array(
			sprintf(
				__('%s are meant to help software, like web browsers, correctly identify and display files. When the server sends %s, it should send a corresponding %s header identifying it as %s.', 'apocalypse-meow'),
				'<a href="https://en.wikipedia.org/wiki/Media_type" target="_blank" rel="noopener">' . __('MIME types', 'apocalypse-meow') . '</a>',
				'<code>kitten.jpg</code>',
				'<code>Content-Type</code>',
				'<code>image/jpeg</code>'
			),
			sprintf(
				__('Unfortunately, MIME handling is %s.', 'apocalypse-meow'),
				'<a href="https://blobfolio.com/2017/03/climbing-mime-improbable/" target="_blank" rel="noopener">' . __('a chaotic mess', 'apocalypse-meow') . '</a>'
			),
			__('To help work around this, browsers will attempt to intelligently determine what kind of a file it has been given, independently of any headers, and handle it however it chooses.', 'apocalypse-meow'),
			sprintf(
				__('This can be dangerous for WordPress sites, since upload validation is essentially limited to file names alone, which of course are completely arbitrary. If an attacker manages to upload a malicious Flash file by calling it %s, a browser will still send it to the Flash plugin.', 'apocalypse-meow'),
				'<code>kitten.jpg</code>'
			),
			sprintf(
				__('When enabled, the server will send a %s header with a value of %s. As long as your server correctly identifies its own content, this should be safe to enable.', 'apocalypse-meow'),
				'<a href="https://blog.appcanary.com/2017/http-security-headers.html#x-content-type-options" target="_blank" rel="noopener">X-Content-Type-Options</a>',
				'<code>nosniff</code>'
			),
		),
		'template-x_frame'=>array(
			sprintf(
				__("The %s header tells a browser whether or not pages from your site can be embedded *inside* someone else's. Unless you host content that is specifically intended to be embedded elsewhere, embedding should be disabled to avoid attacks like %s.", 'apocalypse-meow'),
				'<a href="https://blog.appcanary.com/2017/http-security-headers.html#x-frame-options" target="_blank" rel="noopener">X-Frame-Options</a>',
				'<a href="https://en.wikipedia.org/wiki/Clickjacking" target="_blank" rel="noopener">clickjacking</a>'
			),
			sprintf(
				__('By default, the WordPress backend sends this header with a value of %s, meaning that admin pages can only be embedded by other pages on your site. This option extends this behavior site-wide.', 'apocalypse-meow'),
				'<code>SAMEORIGIN</code>',
				'<code>&lt;iframe&gt;</code>'
			),
			__('Unless you host content that is specifically intended to be embedded elsewhere, you should enable this option.', 'apocalypse-meow'),
		),
		'register'=>array(
			__('As you have probably noticed, open WordPress registrations attract a lot of SPAM. The options in this section provide several tests designed to detect and block robot submissions, while remaining entirely invisible to actual humans.', 'apocalypse-meow'),
			__('*Do not* enable these options if your site uses custom registration forms or if the registration page is cached.', 'apocalypse-meow'),
		),
		'register-cookie'=>array(
			sprintf(
				__('Registration robots are often very bare-bones and might not include basic functionality like support for %s.', 'apocalypse-meow'),
				'<a href="https://en.wikipedia.org/wiki/HTTP_cookie" target="_blank" rel="noopener">HTTP Cookies</a>'
			),
			__('This option sets a small cookie when the registration form is first loaded, and checks that it (still) exists when the form is being processed.', 'apocalypse-meow'),
			__('This option should be safe to enable on all sites, regardless of user demographic, as cookie support is a fundamental requirement of the WordPress login process.', 'apocalypse-meow'),
		),
		'register-honeypot'=>array(
			sprintf(
				__('Most SPAM robots are programmed to enter values into every form field they come across. This option uses a hidden text field — a %s that is meant to remain empty — to check for this behavior.', 'apocalypse-meow'),
				'<a href="https://en.wikipedia.org/wiki/Honeypot_(computing)" target="_blank" rel="noopener">honeypot</a>'
			),
		),
		'register-javascript'=>array(
			sprintf(
				__('Registration robots are often very bare-bones and might not include basic functionality like support for %s.', 'apocalypse-meow'),
				'<a href="https://en.wikipedia.org/wiki/JavaScript" target="_blank" rel="noopener">Javascript</a>'
			),
			__('This option uses Javascript to inject a hidden field into the registration form. If a web browser or robot is unable to execute the script, an error will be triggered.', 'apocalypse-meow'),
			__('Note: if a human user has chosen to disable Javascript support for whatever reason, this option will prevent them from registering. If your site is specifically designed to work without Javascript support, you should leave this option disabled.', 'apocalypse-meow'),
		),
		'register-nonce'=>array(
			__('This option adds a hidden field to the registration form to help ensure that submissions are actually originating from your site (rather than coming out of the blue, as is typical of robotic assaults).', 'apocalypse-meow'),
		),
		'register-speed'=>array(
			__('When a human completes a form, they will need to spend some amount of time reading, typing, and clicking. Robots, on the other hand, can work much faster. This option adds a speed limit to the registration form, requiring that at least two seconds have elapsed from the time the page was first generated.', 'apocalypse-meow'),
		),
		'register-jail'=>array(
			__('Because WordPress uses the same script for logins and registrations, Apocalypse Meow bans always apply.', 'apocalypse-meow'),
			__('This option merely tightens the jail integration by logging registration failures in the main activity table. From there, the usual ban logic applies.', 'apocalypse-meow'),
			__('Note: some registration errors are not necessarily malicious in nature and so are ignored by Apocalypse Meow. For example, a user should not be banned simply because each username they think of is already taken. :)', 'apocalypse-meow'),
		),
	)
	// @codingStandardsIgnoreEnd
);



$options = options::get();
foreach ($options as $k=>$v) {
	// We need to convert any boolean values to integers to keep Vue.js
	// happy.
	foreach ($v as $k2=>$v2) {
		if (\is_bool($v2)) {
			$v[$k2] = $v2 ? 1 : 0;
		}
	}

	$data['forms']['settings'][$k] = $v;
}

// The fail window gets translated to minutes to make the numbers
// easier to deal with.
$data['forms']['settings']['login']['fail_window'] = \ceil($data['forms']['settings']['login']['fail_window'] / 60);

// The whitelist and blacklist need to be collapsed.
$data['forms']['settings']['login']['blacklist'] = \trim(\implode("\n", $data['forms']['settings']['login']['blacklist']));
$data['forms']['settings']['login']['whitelist'] = \trim(\implode("\n", $data['forms']['settings']['login']['whitelist']));

// JSON doesn't appreciate broken UTF.
admin::json_meowdata($data);
?>
<div class="wrap" id="vue-settings" v-cloak>
	<h1>Apocalypse Meow: <?php echo \__('Settings', 'apocalypse-meow'); ?></h1>



	<div class="updated" v-if="forms.settings.saved"><p><?php echo \__('Your settings have been saved!', 'apocalypse-meow'); ?></p></div>
	<div class="error" v-for="error in forms.settings.errors"><p>{{error}}</p></div>

	<div class="updated" v-if="forms.reset.saved"><p><?php echo \__('Your settings been reset to the default values!', 'apocalypse-meow'); ?></p></div>
	<div class="error" v-for="error in forms.reset.errors"><p>{{error}}</p></div>



	<p>&nbsp;</p>
	<h3 class="nav-tab-wrapper">
		<a style="cursor: pointer;" class="nav-tab" v-bind:class="{'nav-tab-active' : section === 'settings'}" v-on:click.prevent="toggleSection('settings')"><?php echo \__('Settings', 'apocalypse-meow'); ?></a>

		<a style="cursor: pointer;" class="nav-tab" v-bind:class="{'nav-tab-active' : section === 'community'}" v-on:click.prevent="toggleSection('community')"><?php echo \__('Community Pool', 'apocalypse-meow'); ?></a>

		<a style="cursor: pointer;" class="nav-tab" v-bind:class="{'nav-tab-active' : section === 'wp-config'}" v-on:click.prevent="toggleSection('wp-config')"><?php echo \__('WP-Config', 'apocalypse-meow'); ?></a>
	</h3>




	<!-- ==============================================
	MAIN SETTINGS
	=============================================== -->
	<form v-if="section === 'settings'" method="post" action="<?php echo \admin_url('admin-ajax.php'); ?>" name="settings" v-on:submit.prevent="settingsSubmit">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder meow-columns one-two fixed fluid">

				<!-- Settings -->
				<div class="postbox-container two">

					<!-- ==============================================
					BRUTE FORCE
					=============================================== -->
					<div class="meow-fluid-tile">
						<div class="postbox">
							<h3 class="hndle">
								<?php echo \__('Brute-Force Protection', 'apocalypse-meow'); ?>
								<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'brute-force'}" v-on:click.prevent="toggleModal('brute-force')"></span>
							</h3>
							<div class="inside">

								<div class="meow-fieldset inline">
									<label for="login-fail_limit"><?php echo \__('Fail Limit', 'apocalypse-meow'); ?></label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'login-fail_limit'}" v-on:click.prevent="toggleModal('login-fail_limit')"></span>

									<input type="number" id="login-fail_limit" v-model.number="forms.settings.login.fail_limit" min="3" max="50" step="1" v-bind:readonly="readonly.indexOf('login-fail_limit') !== -1" required />
								</div>

								<div class="meow-fieldset inline">
									<label for="login-subnet_fail_limit"><?php echo \__('Subnet Fail Limit', 'apocalypse-meow'); ?></label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'login-subnet_fail_limit'}" v-on:click.prevent="toggleModal('login-subnet_fail_limit')"></span>

									<input type="number" id="login-subnet_fail_limit" v-model.number="forms.settings.login.subnet_fail_limit" min="10" max="100" step="1" v-bind:readonly="readonly.indexOf('login-subnet_fail_limit') !== -1" required />
								</div>

								<div class="meow-fieldset inline">
									<label for="login-fail_window"><?php echo \__('Fail Window', 'apocalypse-meow'); ?></label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'login-fail_window'}" v-on:click.prevent="toggleModal('login-fail_window')"></span>

									<input type="number" id="login-fail_window" v-model.number="forms.settings.login.fail_window" min="10" max="1440" step="1" v-bind:readonly="readonly.indexOf('login-fail_window') !== -1" required />

									<span class="meow-fg-grey">&nbsp;<?php echo \__('minutes', 'apocalypse-meow'); ?></span>
								</div>

								<div class="meow-fieldset inline">
									<label for="login-reset_on_success">
										<input type="checkbox" id="login-reset_on_success" v-model.number="forms.settings.login.reset_on_success" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('login-reset_on_success') !== -1" />
										<?php echo \__('Reset on Success', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'login-reset_on_success'}" v-on:click.prevent="toggleModal('login-reset_on_success')"></span>
								</div>

								<?php
								$keys = login::get_server_keys();
								if (\count($keys) > 1) {
									?>
									<div class="meow-fieldset inline">
										<label for="login-key"><?php echo \__('Remote IP/Proxy', 'apocalypse-meow'); ?></label>

										<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'login-key'}" v-on:click.prevent="toggleModal('login-key')"></span>

										<select id="login-key" v-model.trim="forms.settings.login.key" v-bind:disabled="readonly.indexOf('login-key') !== -1">
											<?php
											foreach ($keys as $k=>$v) {
												echo '<option value="' . \esc_attr($k) . '">' . \esc_attr("$k - $v") . '</option>';
											}
											?>
										</select>
									</div>
									<?php
								}
								?>

								<div class="meow-fieldset outline">
									<p>
										<label for="login-whitelist"><?php echo \__('Whitelist', 'apocalypse-meow'); ?></label>

										<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'login-whitelist'}" v-on:click.prevent="toggleModal('login-whitelist')"></span>
									</p>

									<p><textarea id="login-whitelist" v-model.trim="forms.settings.login.whitelist"></textarea></p>

									<p class="description">
										<?php echo \__('Enter an IP or range, one per line. Accepted formats:', 'apocalypse-meow'); ?><br>
										<code>127.0.0.1</code>,<br>
										<code>127.0.0.0/24</code>,<br>
										<code>127.0.0.1-127.0.0.10</code>
									</p>

									<?php
									$ip = login::get_visitor_ip();
									$subnet = login::get_visitor_subnet();
									?>
									<p class="description">
										<?php if ($ip) { ?>
											<?php echo \__('Your IP address is', 'apocalypse-meow'); ?>
											<strong><code><?php echo $ip; ?></code></strong><br>
											<?php echo \__('Your network subnet is', 'apocalypse-meow'); ?>
											<strong><code><?php echo $subnet; ?></code></strong><br>
										<?php
										}
										else {
											echo \__('Your IP address cannot be determined right now. That either means you are on the same network as the server, or the proxy key is not correct.', 'apocalypse-meow');
										}
										?>
									</p>
								</div>

								<div class="meow-fieldset outline">
									<p>
										<label for="login-blacklist"><?php echo \__('Blacklist', 'apocalypse-meow'); ?></label>

										<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'login-blacklist'}" v-on:click.prevent="toggleModal('login-blacklist')"></span>
									</p>

									<p><textarea id="login-blacklist" v-model.trim="forms.settings.login.blacklist"></textarea></p>

									<p class="description">
										<?php echo \__('Enter an IP or range, one per line. Accepted formats:', 'apocalypse-meow'); ?><br>
										<code>127.0.0.1</code>,<br>
										<code>127.0.0.0/24</code>,<br>
										<code>127.0.0.1-127.0.0.10</code>
									</p>
								</div>

								<div class="meow-fieldset inline">
									<label for="login-nonce">
										<input type="checkbox" id="login-nonce" v-model.number="forms.settings.login.nonce" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('login-nonce') !== -1" />
										<?php echo \__('Add Login Nonce', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'login-nonce'}" v-on:click.prevent="toggleModal('login-nonce')"></span>
								</div>

								<div class="meow-fieldset inline">
									<label for="login-alert_on_new">
										<input type="checkbox" id="login-alert_on_new" v-model.number="forms.settings.login.alert_on_new" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('login-alert_on_new') !== -1" />
										<?php echo \__('Email Alert: New Login IP', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'login-alert_on_new'}" v-on:click.prevent="toggleModal('login-alert_on_new')"></span>
								</div>

								<div class="meow-fieldset inline" v-if="forms.settings.login.alert_on_new">
									<label for="login-alert_by_subnet">
										<input type="checkbox" id="login-alert_by_subnet" v-model.number="forms.settings.login.alert_by_subnet" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('login-alert_by_subnet') !== -1" />
										<?php echo \__('Email Alert: New Subnet Only', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'login-alert_by_subnet'}" v-on:click.prevent="toggleModal('login-alert_by_subnet')"></span>
								</div>

							</div>
						</div><!--brute force-->
					</div>



					<!-- ==============================================
					DATA RETENTION
					=============================================== -->
					<div class="meow-fluid-tile">
						<div class="postbox">
							<h3 class="hndle">
								<?php echo \__('Data Retention', 'apocalypse-meow'); ?>
								<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'prune'}" v-on:click.prevent="toggleModal('prune')"></span>
							</h3>
							<div class="inside">
								<div class="meow-fieldset inline">
									<label for="prune-active">
										<input type="checkbox" id="prune-active" v-model.number="forms.settings.prune.active" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('prune-active') !== -1" />
										<?php echo \__('Prune Old Data', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'prune-active'}" v-on:click.prevent="toggleModal('prune-active')"></span>
								</div>

								<div class="meow-fieldset inline" v-if="forms.settings.prune.active">
									<label for="prune-limit"><?php echo \__('Data Expiration', 'apocalypse-meow'); ?></label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'prune-limit'}" v-on:click.prevent="toggleModal('prune-limit')"></span>

									<input type="number" id="prune-limit" v-model.number="forms.settings.prune.limit" min="30" max="365" step="1" v-bind:readonly="readonly.indexOf('prune-limit') !== -1" required />

									<span class="meow-fg-grey">&nbsp;<?php echo \__('days', 'apocalypse-meow'); ?></span>
								</div>
							</div>
						</div>
					</div>



					<!-- ==============================================
					REGISTRATION
					=============================================== -->
					<?php if (\get_option('users_can_register')) { ?>
						<div class="meow-fluid-tile">
							<div class="postbox">
								<h3 class="hndle">
									<?php echo \__('User Registration', 'apocalypse-meow'); ?>
									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'register'}" v-on:click.prevent="toggleModal('register')"></span>
								</h3>
								<div class="inside">
									<div class="meow-fieldset inline">
										<label for="register-cookie">
											<input type="checkbox" id="register-cookie" v-model.number="forms.settings.register.cookie" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('register-cookie') !== -1" />
											<?php echo \__('Cookie Support', 'apocalypse-meow'); ?>
										</label>

										<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'register-cookie'}" v-on:click.prevent="toggleModal('register-cookie')"></span>
									</div>

									<div class="meow-fieldset inline">
										<label for="register-honeypot">
											<input type="checkbox" id="register-honeypot" v-model.number="forms.settings.register.honeypot" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('register-honeypot') !== -1" />
											<?php echo \__('Honeypot', 'apocalypse-meow'); ?>
										</label>

										<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'register-honeypot'}" v-on:click.prevent="toggleModal('register-honeypot')"></span>
									</div>

									<div class="meow-fieldset inline">
										<label for="register-javascript">
											<input type="checkbox" id="register-javascript" v-model.number="forms.settings.register.javascript" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('register-javascript') !== -1" />
											<?php echo \__('Javascript Support', 'apocalypse-meow'); ?>
										</label>

										<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'register-javascript'}" v-on:click.prevent="toggleModal('register-javascript')"></span>
									</div>

									<div class="meow-fieldset inline">
										<label for="register-nonce">
											<input type="checkbox" id="register-nonce" v-model.number="forms.settings.register.nonce" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('register-nonce') !== -1" />
											<?php echo \__('Add Registration Nonce', 'apocalypse-meow'); ?>
										</label>

										<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'register-nonce'}" v-on:click.prevent="toggleModal('register-nonce')"></span>
									</div>

									<div class="meow-fieldset inline">
										<label for="register-speed">
											<input type="checkbox" id="register-speed" v-model.number="forms.settings.register.speed" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('register-speed') !== -1" />
											<?php echo \__('Speed Limit', 'apocalypse-meow'); ?>
										</label>

										<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'register-speed'}" v-on:click.prevent="toggleModal('register-speed')"></span>
									</div>

									<div class="meow-fieldset inline">
										<label for="register-jail">
											<input type="checkbox" id="register-jail" v-model.number="forms.settings.register.jail" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('register-jail') !== -1" />
											<?php echo \__('Jail Integration', 'apocalypse-meow'); ?>
										</label>

										<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'register-jail'}" v-on:click.prevent="toggleModal('register-jail')"></span>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>



					<!-- ==============================================
					PASSWORD REQUIREMENTS
					=============================================== -->
					<div class="meow-fluid-tile">
						<div class="postbox">
							<h3 class="hndle">
								<?php echo \__('Password Requirements', 'apocalypse-meow'); ?>
								<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'passwords'}" v-on:click.prevent="toggleModal('passwords')"></span>
							</h3>
							<div class="inside">

								<div class="meow-fieldset inline">
									<label for="password-alpha"><?php echo \__('Letters', 'apocalypse-meow'); ?></label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'password-alpha'}" v-on:click.prevent="toggleModal('password-alpha')"></span>

									<select id="password-alpha" v-model.trim="forms.settings.password.alpha" v-bind:disabled="readonly.indexOf('password-alpha') !== -1">
										<option value="optional"><?php echo \__('Optional', 'apocalypse-meow'); ?></option>
										<option value="required"><?php echo \__('Required', 'apocalypse-meow'); ?></option>
										<option value="required-both"><?php echo \__('UPPER & lower', 'apocalypse-meow'); ?></option>
									</select>
								</div>

								<div class="meow-fieldset inline">
									<label for="password-numeric"><?php echo \__('Numbers', 'apocalypse-meow'); ?></label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'password-numeric'}" v-on:click.prevent="toggleModal('password-numeric')"></span>

									<select id="password-numeric" v-model.trim="forms.settings.password.numeric" v-bind:disabled="readonly.indexOf('password-numeric') !== -1">
										<option value="optional"><?php echo \__('Optional', 'apocalypse-meow'); ?></option>
										<option value="required"><?php echo \__('Required', 'apocalypse-meow'); ?></option>
									</select>
								</div>

								<div class="meow-fieldset inline">
									<label for="password-symbol"><?php echo \__('Symbols', 'apocalypse-meow'); ?></label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'password-symbol'}" v-on:click.prevent="toggleModal('password-symbol')"></span>

									<select id="password-symbol" v-model.trim="forms.settings.password.symbol" v-bind:disabled="readonly.indexOf('password-symbol') !== -1">
										<option value="optional"><?php echo \__('Optional', 'apocalypse-meow'); ?></option>
										<option value="required"><?php echo \__('Required', 'apocalypse-meow'); ?></option>
									</select>
								</div>

								<div class="meow-fieldset inline">
									<label for="password-length"><?php echo \__('Minimum Length', 'apocalypse-meow'); ?></label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'password-length'}" v-on:click.prevent="toggleModal('password-length')"></span>

									<input type="number" id="password-length" v-model.number="forms.settings.password.length" min="<?php echo options::MIN_PASSWORD_LENGTH; ?>" max="500" step="1" v-bind:readonly="readonly.indexOf('password-length') !== -1" />
								</div>

								<div class="meow-fieldset inline">
									<label for="password-exempt_length"><?php echo \__('Exempt Length', 'apocalypse-meow'); ?></label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'password-exempt_length'}" v-on:click.prevent="toggleModal('password-exempt_length')"></span>

									<input type="number" id="password-exempt_length"
									v-model.number="forms.settings.password.exempt_length"
									v-bind:min="forms.settings.password.length + 1 > <?php echo options::MIN_PASSWORD_EXEMPT_LENGTH; ?> ? forms.settings.password.length + 1 : <?php echo options::MIN_PASSWORD_EXEMPT_LENGTH; ?>" max="500" step="1"
									v-bind:readonly="readonly.indexOf('password-exempt_length') !== -1" />
								</div>

								<div class="meow-fieldset inline">
									<label for="password-common">
										<input type="checkbox" id="password-common" checked disabled />
										<?php echo \__('Block Common Passwords', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'password-common'}" v-on:click.prevent="toggleModal('password-common')"></span>
								</div>

								<div class="meow-fieldset inline">
									<label for="password-retroactive">
										<input type="checkbox" id="password-retroactive" v-model.number="forms.settings.password.retroactive" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('password-retroactive') !== -1" />
										<?php echo \__('Upgrade Existing at Login', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'password-retroactive'}" v-on:click.prevent="toggleModal('password-retroactive')"></span>
								</div>

							</div>
						</div>
					</div>



					<!-- ==============================================
					USER ENUMERATION
					=============================================== -->
					<div class="meow-fluid-tile">
						<div class="postbox">
							<h3 class="hndle">
								<?php echo \__('User Enumeration', 'apocalypse-meow'); ?>
								<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'enumeration'}" v-on:click.prevent="toggleModal('enumeration')"></span>
							</h3>
							<div class="inside">

								<div class="meow-fieldset inline">
									<label for="core-enumeration">
										<input type="checkbox" id="core-enumeration" v-model.number="forms.settings.core.enumeration" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('core-enumeration') !== -1" />
										<?php echo \__('Prevent User Enumeration', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'core-enumeration'}" v-on:click.prevent="toggleModal('core-enumeration')"></span>
								</div>

								<div class="meow-fieldset inline" v-if="forms.settings.core.enumeration">
									<label for="core-enumeration_die">
										<input type="checkbox" id="core-enumeration_die" v-model.number="forms.settings.core.enumeration_die" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('core-enumeration_die') !== -1" />
										<?php echo \__('Error Instead of Redirect', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'core-enumeration_die'}" v-on:click.prevent="toggleModal('core-enumeration_die')"></span>
								</div>

								<div class="meow-fieldset inline" v-if="forms.settings.core.enumeration">
									<label for="core-enumeration_fail">
										<input type="checkbox" id="core-enumeration_fail" v-model.number="forms.settings.core.enumeration_fail" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('core-enumeration_fail') !== -1" />
										<?php echo \__('Track Enumeration Failures', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'core-enumeration_fail'}" v-on:click.prevent="toggleModal('core-enumeration_fail')"></span>
								</div>

							</div>
						</div>
					</div>



					<!-- ==============================================
					CORE/TEMPLATE
					=============================================== -->
					<div class="meow-fluid-tile">
						<div class="postbox">
							<h3 class="hndle">
								<?php echo \__('Core & Template Overrides', 'apocalypse-meow'); ?>
								<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'core'}" v-on:click.prevent="toggleModal('core')"></span>
							</h3>
							<div class="inside">
								<div class="meow-fieldset inline">
									<label for="template-adjacent_posts">
										<input type="checkbox" id="template-adjacent_posts" v-model.number="forms.settings.template.adjacent_posts" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('template-adjacent_posts') !== -1" />
										<?php echo \__('Remove Adjacent Post Tags', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'template-adjacent_posts'}" v-on:click.prevent="toggleModal('template-adjacent_posts')"></span>
								</div>

								<div class="meow-fieldset inline">
									<label for="password-bcrypt">
										<input type="checkbox" id="password-bcrypt" v-model.number="forms.settings.password.bcrypt" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('password-bcrypt') !== -1" />
										<?php echo \__('Bcrypt Password Hashing', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'password-bcrypt'}" v-on:click.prevent="toggleModal('password-bcrypt')"></span>
								</div>

								<div class="meow-fieldset inline">
									<label for="core-browse_happy">
										<input type="checkbox" id="core-browse_happy" v-model.number="forms.settings.core.browse_happy" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('core-browse_happy') !== -1" />
										<?php echo \__('Disable Browse Happy', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'core-browse_happy'}" v-on:click.prevent="toggleModal('core-browse_happy')"></span>
								</div>

								<div class="meow-fieldset inline">
									<label for="core-dashboard_news">
										<input type="checkbox" id="core-dashboard_news" v-model.number="forms.settings.core.dashboard_news" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('core-dashboard_news') !== -1" />
										<?php echo \__('Disable Events & News Dashboard Widget', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'core-dashboard_news'}" v-on:click.prevent="toggleModal('core-dashboard_news')"></span>
								</div>

								<div class="meow-fieldset inline">
									<label for="core-file_edit">
										<input type="checkbox" id="core-file_edit" v-model.number="forms.settings.core.file_edit" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('core-file_edit') !== -1" />
										<?php echo \__('Disable File Editor', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'core-file_edit'}" v-on:click.prevent="toggleModal('core-file_edit')"></span>
								</div>

								<div class="meow-fieldset inline">
									<label for="template-generator_tag">
										<input type="checkbox" id="template-generator_tag" v-model.number="forms.settings.template.generator_tag" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('template-generator_tag') !== -1" />
										<?php echo \__('Remove "Generator" Tag', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'template-generator_tag'}" v-on:click.prevent="toggleModal('template-generator_tag')"></span>
								</div>

								<div class="meow-fieldset inline">
									<label for="template-readme">
										<input type="checkbox" id="template-readme" v-model.number="forms.settings.template.readme" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('template-readme') !== -1" />
										<?php echo \__('Delete "readme.html"', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'template-readme'}" v-on:click.prevent="toggleModal('template-readme')"></span>
								</div>

								<div class="meow-fieldset inline">
									<label for="template-noopener">
										<input type="checkbox" id="template-noopener" v-model.number="forms.settings.template.noopener" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('template-noopener') !== -1" />
										rel=&quot;noopener&quot;
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'template-noopener'}" v-on:click.prevent="toggleModal('template-noopener')"></span>
								</div>

								<div class="meow-fieldset inline">
									<label for="core-xmlrpc">
										<input type="checkbox" id="core-xmlrpc" v-model.number="forms.settings.core.xmlrpc" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('core-xmlrpc') !== -1" />
										<?php echo \__('Disable XML-RPC', 'apocalypse-meow'); ?>
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'core-xmlrpc'}" v-on:click.prevent="toggleModal('core-xmlrpc')"></span>
								</div>
							</div>
						</div>
					</div>


					<!-- ==============================================
					REQUEST HEADERS
					=============================================== -->
					<div class="meow-fluid-tile">
						<div class="postbox">
							<h3 class="hndle">
								<?php echo \__('Request Headers', 'apocalypse-meow'); ?>
								<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'request'}" v-on:click.prevent="toggleModal('request')"></span>
							</h3>
							<div class="inside">

								<div class="meow-fieldset inline">
									<label for="template-referrer_policy">Referrer-Policy</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'template-referrer_policy'}" v-on:click.prevent="toggleModal('template-referrer_policy')"></span>

									<select id="template-referrer_policy" v-model.trim="forms.settings.template.referrer_policy" v-bind:disabled="readonly.indexOf('template-referrer_policy') !== -1">
										<option value="all"><?php echo \__('Default', 'apocalypse-meow'); ?></option>
										<option value="limited"><?php echo \__('Limited', 'apocalypse-meow'); ?></option>
										<option value="none"><?php echo \__('None', 'apocalypse-meow'); ?></option>
									</select>
								</div>

								<div class="meow-fieldset inline">
									<label for="template-x_content_type">
										<input type="checkbox" id="template-x_content_type" v-model.number="forms.settings.template.x_content_type" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('template-x_content_type') !== -1" />
										X-Content-Type-Options
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'template-x_content_type'}" v-on:click.prevent="toggleModal('template-x_content_type')"></span>
								</div>

								<div class="meow-fieldset inline">
									<label for="template-x_frame">
										<input type="checkbox" id="template-x_frame" v-model.number="forms.settings.template.x_frame" v-bind:true-value="1" v-bind:false-value="0" v-bind:disabled="readonly.indexOf('template-x_frame') !== -1" />
										X-Frame-Options
									</label>

									<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'template-x_frame'}" v-on:click.prevent="toggleModal('template-x_frame')"></span>
								</div>

							</div>
						</div>
					</div>

				</div><!--.postbox-container-->



				<!-- Sidebar -->
				<div class="postbox-container one">

					<!-- ==============================================
					SAVE
					=============================================== -->
					<button class="button button-large button-primary" type="submit" v-bind:disabled="forms.settings.loading || forms.reset.loading" style="height: 50px; width: 100%; margin-bottom: 20px; font-size: 16px; display: block;">
						<?php echo \__('Save Settings', 'apocalypse-meow'); ?>
					</button>



					<!-- ==============================================
					READONLY NOTICE
					=============================================== -->
					<div class="postbox" v-if="readonly.length">
						<div class="inside">
							<p class="description"><?php
							\printf(
								\__("Note: some settings have been hard-coded into this site's %s and cannot be edited here. Such fields will have a somewhat ghostly appearance.", 'apocalypse-meow'),
								'<code>' . \__('wp-config.php', 'apocalypse-meow') . '</code>'
							);
							?></p>
						</div>
					</div>



					<!-- ==============================================
					RESET
					=============================================== -->
					<div class="postbox">
						<h3 class="hndle"><?php echo \__('Reset to Default', 'apocalypse-meow'); ?></h3>
						<div class="inside">
							<p><button class="button button-large" type="button" v-bind:disabled="forms.settings.loading || forms.reset.loading" v-on:click.prevent="resetSubmit">
								<?php echo \__('Reset', 'apocalypse-meow'); ?>
							</button></p>

							<p class="description"><?php
							echo \__('Click the above button to restore the plugin to the default settings.', 'apocalypse-meow');
							?></p>
						</div>
					</div>



					<!-- ==============================================
					SISTER PLUGINS
					=============================================== -->
					<div class="postbox">
						<div class="inside" style="padding-bottom: 0px">
							<a href="https://blobfolio.com/" target="_blank" class="sister-plugins--blobfolio"><?php echo \file_get_contents(\MEOW_PLUGIN_DIR . 'img/blobfolio.svg'); ?></a>
						</div>
					</div>
				</div><!--.postbox-container-->

			</div><!--#post-body-->
		</div><!--#poststuff-->
	</form>




	<!-- ==============================================
	COMMUNITY
	=============================================== -->
	<form v-if="section === 'community'" method="post" action="<?php echo \admin_url('admin-ajax.php'); ?>" name="communityForm" v-on:submit.prevent="communitySubmit">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder meow-columns one-two">

				<!-- Main -->
				<div class="postbox-container two">
					<!-- ==============================================
					ABOUT
					=============================================== -->
					<div class="postbox">
						<h3 class="hndle"><?php echo \__('About the Pool', 'apocalypse-meow'); ?></h3>
						<div class="inside">
							<?php
								// @codingStandardsIgnoreStart
								$out = array(
									__('The Community Pool is an *optional* extension to the brute-force login protection that combines attack data from your site with other sites running in pool mode to produce a global blocklist.', 'apocalypse-meow'),

									__('In other words, an attack against one becomes an attack against all!', 'apocalypse-meow'),

									__("When enabled, your site will periodically share its attack data with the centralized Meow API. The Meow API will crunch and combine this data and return a community blocklist, which your site will then integrate with its own bans.", 'apocalypse-meow'),

									__('The blocklist data is conservatively filtered using a tiered and weighted ranking system based on activity shared within the past 24 hours. For an IP address to be eligible for community banning, it must be independently reported from multiple sources and have a significant number of total failures.', 'apocalypse-meow'),

									__("Your site's whitelist is always respected. Failures from whitelisted IPs will never be sent to the pool, and if the pool declares a ban for an IP you have whitelisted, your site will not ban it. Be sure to add your own IP address to your site's whitelist. :)", 'apocalypse-meow'),

									__("Anybody can join the Community Pool. There's just one requirement:", 'apocalypse-meow') . ' <strong>' . __('To Receive, Your Must Give.', 'apocalypse-meow') . '</strong> ' . __('It is, after all, a community. Haha.', 'apocalypse-meow')
								);
								// @codingStandardsIgnoreEnd

								echo '<p>' . \implode('</p><p>', $out) . '</p>';
							?>
						</div>
					</div>

					<!-- ==============================================
					SAVE
					=============================================== -->
					<div class="postbox">
						<h3 class="hndle"><?php echo \__('Community Status', 'apocalypse-meow'); ?></h3>
						<div class="inside">
							<div class="meow-pool-form">
								<div class="meow-pool-form--form">
									<p>
										<strong><?php echo \__('Status', 'apocalypse-meow'); ?>:</strong>
										<span v-if="forms.settings.login.community"><?php echo \__('Enabled', 'apocalypse-meow'); ?></span>
										<span v-else><?php echo \__('Disabled', 'apocalypse-meow'); ?></span>
									</p>

									<p v-if="readonly.indexOf('login-community') === -1">
										<button class="button button-large button-primary" type="submit" v-bind:disabled="forms.settings.loading">
											<span v-if="forms.settings.login.community"><?php echo \__('Leave Community', 'apocalypse-meow'); ?></span>
											<span v-else><?php echo \__('Join Community', 'apocalypse-meow'); ?></span>
										</button>
									</p>
									<p v-else>
										<?php \printf(
											\__('The Community Pool setting has been hard-coded into your site configuration (probably in %s). To change the status, that code will have to be altered.', 'apocalypse-meow'),
											'<code>wp-config.php</code>'
										); ?>
									</p>
								</div>

								<img src="<?php echo \MEOW_PLUGIN_URL; ?>img/kitten.gif" alt="Kitten" class="meow-pool-form--cat left" v-if="forms.settings.login.community" />

								<img src="<?php echo \MEOW_PLUGIN_URL; ?>img/kitten.gif" alt="Kitten" class="meow-pool-form--cat" />
							</div>
						</div>
					</div>

				</div>

				<!-- Sidebar -->
				<div class="postbox-container one">

					<!-- ==============================================
					PRIVACY NOTICE
					=============================================== -->
					<div class="postbox">
						<h3 class="hndle"><?php echo \__('Privacy Notice', 'apocalypse-meow'); ?></h3>
						<div class="inside">
							<?php
								echo '<p>' . \__('Information about your site is *never* shared with other Community Pool participants. The Meow API acts as a go-between.', 'apocalypse-meow') . '</p>';

								echo '<p>' . \__('But that said, this is not usually data that would be leaving your site, so if you are not comfortable with the idea, please leave this feature disabled!', 'apocalypse-meow') . '</p>';
							?>
						</div>
					</div>


					<div class="postbox">
						<h3 class="hndle"><?php echo \__('Login Failures', 'apocalypse-meow'); ?></h3>
						<div class="inside">
							<?php
								$out = array(
									\__('A UTC timestamp', 'apocalypse-meow'),
									\__('An IP address', 'apocalypse-meow'),
									\sprintf(
										\__('Whether or not the username was %s or %s', 'apocalypse-meow'),
										'<code>admin</code>',
										'<code>administrator</code>'
									),
								);

								echo '<p>' . \__('The following details from failed login attempts are shared:', 'apocalypse-meow') . '</p><ul style="list-style-type: disc; list-style-position: outside; padding-left: 3em;"><li>' . \implode('</li><li>', $out) . '</li></ul>';
							?>
						</div>
					</div>


					<div class="postbox">
						<h3 class="hndle"><?php echo \__('Environment/Setup', 'apocalypse-meow'); ?></h3>
						<div class="inside">
							<?php
								// @codingStandardsIgnoreStart
								$out = array(
									__('Aside from attack data, the API also collects some basic information about your site setup. This is done primarily to help the API keep its data sources straight, but might also help inform what sorts of future features would be most helpful to develop.', 'apocalypse-meow'),
									__('This information is *only* used internally — and not very sensitive to begin with — but you should still be aware it is being leaked. :)', 'apocalypse-meow')
								);
								// @codingStandardsIgnoreEnd

								echo '<p>' . \implode('</p><p>', $out) . '</p>';

								// Output the table.
								$out = array(
									\__('Domain', 'apocalypse-meow')=>common\sanitize::hostname(\site_url()),
									\__('OS', 'apocalypse-meow')=>\PHP_OS,
									\__('PHP', 'apocalypse-meow')=>\PHP_VERSION,
									\__('WordPress', 'apocalypse-meow')=>common\format::decode_entities(\get_bloginfo('version')),
									\__('This Plugin', 'apocalypse-meow')=>about::get_local('Version'),
									\__('Locale', 'apocalypse-meow')=>\get_locale(),
									\__('Timezone', 'apocalypse-meow')=>about::get_timezone(),
								);
								echo '<table class="meow-meta"><tbody>';
								foreach ($out as $k=>$v) {
									echo '<tr><th scope="row">' . \esc_html($k) . '</th><td>' . \esc_html($v) . '</td></tr>';
								}
								echo '</tbody></table>';
							?>
						</div>
					</div>

				</div>

			</div><!--#post-body-->
		</div><!--#poststuff-->
	</form>



	<!-- ==============================================
	WP-CONFIG WIZARD
	=============================================== -->
	<div id="poststuff" v-if="section === 'wp-config'">
		<div id="post-body" class="metabox-holder meow-columns one-two fixed">

			<!-- Config -->
			<div class="postbox-container two">
				<div class="postbox">
					<h3 class="hndle"><?php echo \__('Configuration Constants', 'apocalypse-meow'); ?></h3>
					<div class="inside">
						<pre class="language-php line-numbers"><code><?php echo \file_get_contents(\MEOW_PLUGIN_DIR . 'skel/wp-config.html');
						?></code></pre>

						<p><code><?php echo \trailingslashit(\ABSPATH); ?>wp-config.php</code></p>
					</div>
				</div>
			</div>

			<!-- Sidebar -->
			<div class="postbox-container one">
				<div class="postbox">
					<h3 class="hndle"><?php echo \__('Explanation', 'apocalypse-meow'); ?></h3>
					<div class="inside">
						<?php
							// @codingStandardsIgnoreStart
							$out = array(
								sprintf(
									__('Almost all of the plugin settings can alternatively be defined as PHP constants in %s. This allows system administrators to configure behaviors without logging into WordPress, and prevents those configurations from being changed by other users with access to this page.', 'apocalypse-meow'),
									'<code>wp-config.php</code>'
								),
								sprintf(
									__('This code sample contains the corresponding PHP code for every setting as currently configured. It can be copied as-is to %s, or certain pieces can be removed or tweaked as needed. Any options that site administrators should be allowed to change through this page should first be removed.', 'apocalypse-meow'),
									'<code>wp-config.php</code>'
								),
								sprintf(
									__('Note: while PHP constants can be shoved pretty much anywhere, these must be loaded into memory before the %s hook is fired or Apocalypse Meow might not see them. %s is the safest bet.', 'apocalypse-meow'),
									'<code>' . (MEOW_MUST_USE ? 'muplugins_loaded' : 'plugins_loaded') . '</code>',
									'<code>wp-config.php</code>'
								)
							);
							// @codingStandardsIgnoreEnd

							echo '<p>' . \implode('</p><p>', $out) . '</p>';
						?>
					</div>
				</div>
			</div>
		</div>
	</div>



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
