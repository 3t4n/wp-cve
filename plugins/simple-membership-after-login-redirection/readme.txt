=== Simple Membership After Login Redirection ===
Contributors: smp7, wp.insider
Donate link: https://simple-membership-plugin.com/
Tags: login, redirection, member, members, membership, login redirection, access, level
Requires at least: 3.8
Tested up to: 6.4
Stable tag: 1.6
License: GPLv2 or later

An addon for the simple membership plugin to configure after login redirection to a specific page based on the member's level.

== Description ==

This addon allows you to configure an after login page for each of the membership access levels you create in your [simple membership plugin](http://wordpress.org/plugins/simple-membership/).

This addon plugin will automatically redirect the members to the appropriate page after they log into your site.

After you install this addon, edit your membership levels and specify the redirect pages and the addon will take care of the rest.

== Installation ==

1. Upload `swpm-after-login-redirection` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

None

== Screenshots ==

None

== Changelog ==

= 1.6 =
* Added an extra line debug logging output to show which page it is redirecting to. Useful for troubleshooting.

= 1.5 =
* Added a new feature to enable custom redirections (the custom redirection can be set by defining the parameter 'swpm_redirect_to' in the URL)

= 1.4 =
* Fixed an "Undefined index" PHP notice when the settings is saved and WP Debug is enabled on the site.

= 1.3 =
* Added a new feature to enable redirection to the last page (where they clicked the login link) after the member logs in. You need to upgrade simple membership plugin for this to work.

= 1.2 =
* Registering the actions and hooks earlier so they don't have a race condition on some sites.

= 1.1 =
* Moved the after login redirection action inside the plugins_loaded hook

= 1.0 =
* First commit to wordpress.org

== Upgrade Notice ==

None
