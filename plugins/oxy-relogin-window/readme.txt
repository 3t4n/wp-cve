=== OXY Re-Login Window ===
Contributors: laborin
Requires at least: 4.7
Tested up to: 5.7
Requires PHP: 5.0
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Re-Login window for Oxygen Builder.

== Description ==

This free plugin presents a login window right inside Oxygen Builder when the WordPress session expires, to avoid losing your work because of the infamous Oxygen "Error 200" while saving.

How do I know if it's working? Easy:

1 - Open Oxygen Builder
2 - Using a different browser tab, log-out from WordPress

Normally, doing this would make you lose any change made in the Oxygen Builder tab. Logging back in using a different browser tab doesn't help, all unsaved changes in Oxygen Builder will be lost.

But if you have OXY Re-Login Window active, your Oxygen Builder tab will show a login window and your Oxygen Builder session key will be updated and you will be able to save your data and continue working.

Why would someone log-out while having unsaved changes in Oxygen Builder? Sadly, it's common that the WordPress session expires automatically while you are working inside Oxygen Builder.

== Installation ==

1. Upload the plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.1 =
Change the heartbeat endpoint to be the standard admin-ajax.php file, so request aren't blocked by mod_security.

= 1.0 =
Initial version
