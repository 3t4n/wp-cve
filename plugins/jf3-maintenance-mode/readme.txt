=== Maintenance Redirect ===
Contributors: petervandoorn,jfinch3
Tags: maintenance, maintenance mode, maintenance mode page, 503, 200, 507, redirect, developer, coming soon, coming soon page, launch page, under construction, unavailable, offline, site offline
Requires at least: 5.1
Tested up to: 6.4.2
Requires PHP: 5.6
Stable tag: 2.0.1
Text Domain: jf3-maintenance-mode
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://paypal.me/fabulosawebdesigns

Display a maintenance mode page and allow invited visitors to bypass the functionality to preview the site.


== Description ==
This plugin is intended primarily for designers / developers that need to allow clients to preview sites before being available to the general public or to temporarily hide your WordPress site while undergoing major updates.

Any logged in user with WordPress administrator privileges will be allowed to view the site regardless of the settings in the plugin. The exact privilege can be set using a filter hook - see FAQs.

The behaviour of this plugin can be enabled or disabled at any time without losing any of the settings configured in its settings pane. However, deactivating the plugin is recommended versus having it activated while disabled.

When redirect is enabled it can send 2 different header types. “200 OK” is best used for when the site is under development and “503 Service Temporarily Unavailable” is best for when the site is temporarily taken offline for small amendments. If used for a long period of time, 503 can damage your Google ranking.

A list of IP addresses can be set up to completely bypass maintenance mode. This option is useful when needing to allow a client’s entire office to access the site while in maintenance mode without needing to maintain individual access keys.

Access keys work by creating a cookie on the user’s computer that will be checked for when maintenance mode is active. When a new key is created, a link to create the access key cookie will be emailed to the email address provided. Access can then be revoked either by disabling or deleting the key.

This plugin allows three methods of notifying users that a site is undergoing maintenance:

  1. They can be presented with a simple message.

  2. They can be presented with a custom HMTL page.

  3. They can be redirected to a static HTML page. This static page will need to be uploaded to the server via FTP or some other method. This plugin DOES NOT include any way to upload the static page file. Any URL can be used here, and it doesn't need to be on the same server (so you could redirect back to the client's current site if you're working on a dev site, for example). However, it should NOT be the URL of a WordPress page or post on the same site as this will result in an infinite redirect loop.


== Installation ==
1. Upload the `jf3-maintenance-mode` folder to your plugins directory (usually `/wp-content/plugins/`).

2. Activate the plugin through the `Plugins` menu in WordPress.

3. Configure the settings through the `Maintenance Redirect` Settings panel.


== Frequently Asked Questions ==
= What does Maintenance Redirect block? =

This plugin is designed to block only the normal display of pages in the web browser. It will not effect any other calls to WordPress, such as the Rest API.

This means that services such as the PayPal and Stripe integrations in WooCommerce, for example, are still able to function for testing WooCommerce stores. 

It also means that all of the usual WordPress REST endpoints are active. If you wish to completely lock down your site's data then you will need to find an additional solution to block those calls.

= How can I bypass the redirect programatically? =

There is a filter which allows you to programatically bypass the redirection block:

**`wpjf3_matches`**

This allows you to run pretty much any test you like, although be aware that the whole redirection thing runs *before* the `$post` global is set up, so WordPress conditionals such as `is_post()` and `is_tax()` are not available. 

This example looks in the `$_SERVER` global to see if any part of the URL contains "demo"

	function my_wpjf3_matches( $wpjf3_matches ) {
		if ( stristr( $_SERVER['REQUEST_URI'], 'demo' ) ) 
			$wpjf3_matches[] = "<!-- Demo -->";
		return $wpjf3_matches;
	}
	add_filter( "wpjf3_matches", "my_wpjf3_matches" );

*Props to @brianhenryie for this!*

= How can I let my logged-in user see the front end? =

By default, Maintenance Redirect uses the `manage_options` capability, but that is normally only applied to administrators. As it stands, a user with a lesser permissions level, such as editor, is able to view the admin side of the site, but not the front end. You can change this using this filter:

**`wpjf3_user_can`**

This filter is used to pass a different WordPress capability to check if the logged-in user has permission to view the site and thus bypass the redirection, such as `edit_posts`. Note that this is run before `$post` is set up, so WordPress conditionals such as `is_post()` and `is_tax()` are not available. However, it's not really meant for programatically determining whether a user should have access, but rather just changing the default capability to be tested, so you don't really need to do anything other than the example below.

	function my_wpjf3_user_can( $capability ) {
		return "edit_posts";
	}
	add_filter( "wpjf3_user_can", "my_wpjf3_user_can" );


== Screenshots ==
1. About & Info tab
2. Header Type tab
3. Unrestricted IP Addresses tab
4. Access Keys tab
5. Maintenance Message tab showing Message only
6. Maintenance Message tab showing Redirect
7. Maintenance Message tab showing HTML

== Changelog ==
= 2.0.1 =
* Added clarification to readme and on-screen info about the scope of what this plugin blocks (see FAQs)
= 2.0 =
* Uodated minimum requirements for WP (5.1) and PHP (5.6).
* Updated the UI with tabs for each of the sections.
* Changed the activation method from a select menu to a toggle switch.
* Split the HTML and Message storage in the database to separate fields. When the plugin is updated the message text should be copied across, but please do double-check. *I suggest that you make a copy of your message or HTML before updating just in case.*
* Tweaked the HTML used for the Message option.
* Added an option to delete all of the plugin's settings and database tables the next time that the plugin is deleted using the Plugins screen. There are caveats - see the About & Options tab in the plugin settings page for more info.
* Added responsive styling for the IP addresses and Access Keys tables to better display on mobile phone screens.
* Tidied up some of the on-screen information.
* Added an about section with a link to "buy me a coffee" :-)
* Additional minor code tweaks

= 1.8.3 =
* Fixed sprintf() PHP warning.

= 1.8.2 =
* Added plugin status to the admin bar.
* Fixed bug where body text was being output twice.

= 1.8.1 =
* Fixed Update Settings button not working.

= 1.8 =
* Added button to copy full URL of an Access Key link to the clipboard.
* You can now click on your IP or Class C address to add it to the unrestricted IP addresses field.
* General hardening of translated text escaping.

= 1.7 =
* Added links to plugin screen and the dashboard notification to go to the Settings page.
* Added information to the Site Health screen.

= 1.6 =
* Added `wpjf3_matches` filter to allow programatical bypasses. Thanks to @brianhenryie for this.
* Added `wpjf3_user_can` filter to allow the WordPress capability check to be changed so logged-in users can be allowed to bypass the redirect.

= 1.5.3 =
* Fixed ability to set IP address using Class C wildcard (eg, 192.168.0.*) - thanks to @tsouts for bringing that to my attention.

= 1.5.2 =
* Tiny translation tweak

= 1.5.1 =
* Phooey! Found a couple of translation strings that I missed on the previous commit!

= 1.5 =
* Now translatable! I’m a typical Englishman who doesn’t speak any other language, so at time of release there aren’t any translation packs available. However, if you’re interested in contributing, just pop over to https://translate.wordpress.org/ and get translating!
* Minimum WordPress requirement now 4.6 due to usage of certain translation bits & bobs.

= 1.4 =
* Plugin taken over by @petervandoorn due to being unmaintained for over 4 years. 
* Changed name to Maintenance Redirect
* Setting added to choose whether to return 200 or 503 header
* Added nonces and other, required, security checks
* General code modernisation

= 1.3 =
* Updated to return 503 header when enabled to prevent indexing of maintenance page. 
* Also, wildcards are allowed to enable entire class C ranges. Ex: 10.10.10.*
* A fix affecting some installations using a static page has been added. Thanks to Dorthe Luebbert.

= 1.2 =
* Fixed bug in Unrestricted IP table creation.

= 1.1 =
* Updated settings panel issue in WordPress 3.0 and moved folder structure to work properly with WordPress auto install.

= 1.0 =
* First release. No Changes Yet.

== Upgrade Notice ==
New UI with tabs and an activation toggle switch. Internal settings storage changed, so please make a copy of your message or HTML before updating just in case.
