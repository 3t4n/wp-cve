=== WP Custom HTML Page ===
Tags: html,custom,uri
Requires at least: 3.6.1
Tested up to: 5.5.1
Stable tag: 0.6.2
Requires PHP: 5.2
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display full custom HTML on custom permalink address, or put it inside content as a shortcode.

== Description ==
Multisite support added in v0.6!

Notice: this plugin will not have further major updates. If interested in new features, please upgrade into this compatible replacement: https://wit.rs/wp-plugins/wit-custom-output
After uninstalling the WPCHTMLP and installing WIT Custom Output, your old custom pages will still work, but with better interface, more features and regular updates.

WP Custom HTML Pages plugin has two functionalities:

1 - Create a custom HTML document output at a custom permalink address, completely bypassing WordPress templating. This is useful in cases when you just want to output some HTML without uploading the page to the server.

2 - Create a shortcode which outputs your custom HTML.

== Installation ==
Download from the repository (by searching for \"WP Custom HTML Pages Milos Stojanovic\") and activate.

== Frequently Asked Questions ==
Q: Why would I need this plugin just to show an HTML page?
A:  So you can easily display your custom HTML pages without bothering to upload the page to the server. It is useful in cases when you don\'t have access to your hosting FTP server, or if .htaccess rules are giving you trouble to link to the page. Also, you get a pretty URI to the page rather than having to link directly to the .html document.

Q: Does WP Custom HTML Pages work with multisite WordPress?
A:  It does, as of version 0.6.

Q: Can this plugin do this or that..
A:  WITCO, the new release, probably can, as many of its options are built based on previous user requests. On the other hand, if all you need is a tiny plugin that will output some custom HTML, you may want to stick with WPCHTMLP.

Q: Is this plugin a security concern?
A:  The WPCHTMLP plugin is by itself is as secure as the WordPress installation it is hosted on. However, if the website gets hacked, or you allow strangers to add custom pages, the plugin may become a point of phishing attacks or other hacking mischiefs.

Upgrade to WITCO questions:

Q: If I replace this plugin with WIT Custom Output, will my old pages and settings be preserved?
A:  Yes, just go to WPCHTMLP Settings and make sure that "Completely remove database tables.." is set to OFF, then uninstall WPCHTMLP and install WITCO.

Q: If I had custom changes in code implemented in WPCHTMLP, how hard would it be to implement the same changes in WITCO?
A:  Depends, WITCO is a new plugin built on different code base, yet some pieces of code are similar. But then again, some of the features you implemented may be available in WITCO out of the box. If you wish to transfer your changes, hire a coder or contact us. If you code the features yourself, and don't like OOP frameworks, it may be a better choice to stick with WPCHTMLP.

== Screenshots ==
1. Admin menu, under Settings - WP Custom HTML Pages
2. Single HTML page edit screen, HTML Pages are submenu of Pages

== Changelog ==
v 0.6.2 - Fixed non-multisite installations with subdirectory in path
v 0.6.1 - Fixed error for non-multisite installations

v 0.6.0
-Features:
--Enabled multisite support: blogs in a MUWP installation will now have ability to create custom HTML pages
--Subfolder installation support: now works with WordPress with subfolder in the root path
--Optional ignore of trailing slash in URL: prior to this version, adding trailing slash while typing in URL did not match the custom page if it did not had trailing slash in it's permalink. Checking this option ON will remove trailing slashes from URL when checking the database for permalink.
-Fixes:
--WP permalink (at top of editor page) will now match custom URI set for that page
--Various fixes in code, tested with WP 5.5.1 multisite
-Notice: there won't be any major updates to WPCHTMLP, for a new backwards compatible replacement with many new features (WIT Custom Output) please go to: https://wit.rs/wp-plugins/wit-custom-output

v 0.5.1 - Custom permalinks will now work with variable parameters within the request, if the option is enabled in settings (ie website.com/pagename?param=example will lead to /pagename). This option is OFF by default, to prevent breakings of existing setups.
v 0.5.0 - Changed html column type in db to mediumtext, to allow larger html pages (unavailable in upgrade, this will require reinstallation of plugin)
v 0.4.5 - Fixed PHP error messages, added quick link to the edited page
v 0.4.3 - Security improvement, gave up on supporting subdirectory installs (for now)
v 0.4.2 - Fixed minor bug which affected users who had error reporting on
v 0.4.1 - Fixed the bug with escaped quote characters
v 0.4 - More polishing
v 0.3 - Feature: Added Ace editor for editing page code. Fix: Removed requirement for custom URI to start with forward slash. TODO: Option to use textarea instead of Ace editor.
v 0.2 - Cleaned code a bit, changes to readme.txt, added screenshots and icon to repository.
v 0.1 - Published