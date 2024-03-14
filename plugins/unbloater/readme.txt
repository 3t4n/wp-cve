=== Unbloater ===
Contributors: christophrado
Donate link: https://www.paypal.me/christophrado
Tags: unbloat, bloat, clean, remove, notice
Requires at least: 4.2
Tested up to: 6.2
Stable tag: 1.6.1
Requires PHP: 5.6
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Remove unnecessary code, nags and bloat from WordPress core and certain plugins.

== Description ==

Unbloater is a simple and minimalist interface for cleaning up your WordPress admin dashboard and site code from unnecessary and not needed items. It mostly uses filters and actions to achieve fast and clean results. The settings screen is clean by itself, no additional styling or branding - that's what the plugin stands for.

This plugin evolved out of my personal need to clean up sites for clients. The available options, especially for third party plugins, may therefore be biased. They might also be developer-focused in that the results work towards a cleaner site for non-technical users.

= Included Options =

*Unbloater currently integrates 50+ options:*

**Core (Backend):**

* Hide update notice for non-admin users
* Disable core auto-updates
* Disable theme auto-updates
* Disable plugin auto-updates
* Disable installation of bundled items (e.g. default themes) during Core upgrades
* Disable code editors
* Limit post revisions
* Limit creation of Application Passwords to admins
* Disable Application Passwords
* Disable admin email confirmation screen
* Disable XML-RPC API
* Remove WordPress 'W' admin bar item
* Remove admin footer text

**Core (Frontend):**

* Remove generator tag
* Remove script/style version parameter
* Remove WLW Manifest link
* Remove RSD link
* Remove shortlink
* Remove feed generator tag
* Remove feed links
* Remove feeds
* Remove DNS prefetch to s.w.org
* Remove jQuery Migrate script
* Remove emoji styles and scripts
* Load comment script only when needed
* Remove recent comments inline style
* Prevent auto-linking URLs in comments
* Reduce Heartbeat interval
* Normalize favicon
* Normalize login logo title
* Normalize login logo URL
* Disable login language selector

**Block Editor / Gutenberg:**

* Deactivate Block Directory
* Deactivate Core Block Patterns
* Deactivate Template Editor
* Auto-close Welcome Guide
* Auto-exit Fullscreen Mode

**Third party plugins:**

* Advanced Custom Fields: Remove admin interface
* Autoptimize: Remove admin bar item
* Autoptimize: Remove imgopt notice
* Rank Math: Remove admin bar item
* Rank Math: Whitelabel (removes head comments and admin footer credit)
* Rank Math: Remove sitemap credit
* Rank Math: Remove link class
* SearchWP: Remove stats widget
* SearchWP: Remove stats link
* SearchWP: Remove admin bar item
* SearchWP: Move top-level menu item to bottom
* SearchWP: Remove top-level menu item
* The SEO Framework: Remove plugin indicator
* The SEO Framework: Move metabox to 'side' context
* WooCommerce: Remove 'Connect your store' notice
* WooCommerce: Remove all notices
* WooCommerce: Remove cart fragment scripts
* WooCommerce: Remove SkyVerge dashboard
* Yoast SEO: Remove admin bar item
* Yoast SEO: Remove plugin indicator

Third party options will only be shown when the applicable plugin is installed and activated.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/unbloater` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Configure your settings using the Settings->Unbloater screen

== Frequently Asked Questions ==

= Will you add support for plugin x? =

Probably not. This plugin is biased towards my personal needs, thus integrating what I need in client projects. I do not plan to add options for just any plugin out there, since that would kind of work against a clean plugin and site for myself.

== Screenshots ==

1. Plugin settings (see description for all available options)

== Changelog ==

= 1.6.1 =
* Tested up to WP 6.2

= 1.6.0 =
* Added: Disable the installation of bundled items (e.g. default themes) during Core upgrades
* Added: Disable the admin email confirmation screen that gets displayed every 6 months by default

= 1.5.5 =
* Tested up to WP 6.1

= 1.5.4 =
* Added: Empty Trash option to reduce the number of days until posts are permanently deleted to 7
* Improved: Add Block Editor scripts as inline scripts to prevent `headers already sent` errors

= 1.5.3 =
* Tested up to WP 6.0.2
* Improved: Remove script version parameters on frontend only to prevent conflicts
* Improved: Hide Block Editor options when the Classic Editor plugin is active
* Improved: Enqueue Block Editor scripts only in Block Editor context, remove jQuery dependency

= 1.5.2 =
* Fix: Broken detection of overwritten option state

= 1.5.1 =
* Added: Option to actually disable the RSS feeds (redirect to frontpage)
* Added: SearchWP - Option to move the top-level menu item to the bottom (position 98)
* Added: SearchWP - Option to hide the top-level menu item

= 1.5.0 =
* Tested up to WP 5.9
* Added: Frontend - Disable login language selector
* Added: Block Editor - Auto-close Welcome Guide
* Added: Block Editor - Auto-exit Fullscreen Mode

= 1.4.1 =
* Fix: Accidental trailing comma causing error on PHP < 7.3
* Removed: Unused legacy code

= 1.4.0 =
* Added: Remove jQuery Migrate script
* Added: Yoast SEO - Remove admin bar item
* Added: Yoast SEO - Remove plugin indicator

= 1.3.1 =
* Changed: Database option will now only be removed on plugin uninstall, not plugin deactivation

= 1.3.0 =
* Tested up to WP 5.8
* Added: RankMath - Remove admin bar item
* Added: RankMath - Whitelabel (Removes head comments and admin footer credit)
* Added: RankMath - Remove sitemap credit
* Added: RankMath - Remove link class
* Added: Block Editor - Deactivate block directory
* Added: Block Editor - Deactivate core block patterns
* Added: Block Editor - Deactivate template editor

= 1.2.0 =
* Added: Multisite support (global settings if network-activated)
* Removed: Support for Cookie Notice

= 1.1.4 =
* Added: Properly clean up / delete options on plugin deactivation
* Fixed: Hopefully finally fixed translations / text domain

= 1.1.3 =
* Tested up to WP 5.7
* Improved: Optimized/reduced translation strings
* Improved: Replaced deprecated `login_headertitle` filter with `login_headertext`

= 1.1.2 =
* Fixed: Translations / text domain for WP 4.6 and earlier

= 1.1.1 =
* Fixed: Call of non-static functions (fixes PHP 8 fatal error)
* Changed: WP version comparisons (moved to helper function)

= 1.1.0 =
* Added: Option to disable Application Passwords completely
* Added: Option to remove shortlink from head
* Fixed: Conditional logic of comment reply script
* Changed: Hardened deactivation of XML-RPC

= 1.0.0 =
* Initial release.