=== Activate Update Services ===
Contributors: feedmeastraycat
Tags: ping, update service, wpmu, multiusers, network, multisites
Requires at least: 3.0.0
Tested up to: 4.5.2
Stable tag: 1.0.7

	WordPress removes the Update Services ability when you create a network. 
Activate this plugin to get it back.

== Description ==

WordPress removes the Update Services ability (Settings - Writing) when you create a network (aka enables WordPress MU/Multisite). 
Activate this plugin to get it back.

**Note:** This is only for WordPress setups with a network, aka multiple sites, aka multisites, 
aka WPMU in WordPress 3 and later.

This plugin *might* work with older WordPress MU sites. I haven't tested it though. If you do, please 
let me know how it turned out.


== Installation ==

1. Extract the ZIP file and move the folder "activate-update-services", with it contents, 
   to `/wp-content/plugins/` in your WordPress installation
2. Activate the pluing under 'Plugins' in the WordPress admin area
3. Change your ping sites in 'Update Service' under the 'Settings/Writing' admin panel.

If you want all sites in your network to be able to do this them self you need to activate the
plugin page for them *(Super Admin > Options)*.

== Files ==

* /activate-update-services/activate-update-services.php
* /activate-update-services/README.txt

== Changelog ==

* 1.0.7 - Tested in 3.8, no changes required (from now the version number will not change when the plugin is tested it in new WP versions and no change is required)
* 1.0.6 - Tested in 3.5.2, no changes required
* 1.0.5 - Tested in 3.5, no changes required
* 1.0.3 - Tested in 3.4.2, no changes required
* 1.0.2 - Tested in 3.3, no changes required
* 1.0.1 - Tested in 3.2.1, no changes required
* 1.0.0 - First stable release