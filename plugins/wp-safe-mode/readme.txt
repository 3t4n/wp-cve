=== WP Safe Mode ===
Contributors: pxlite, msykes
Donate link: https://pixelite.com
Tags: safe mode, recovery, troubleshooting, debugging, debug, error
Text Domain: wp-safe-mode
Requires PHP : 5.2.6
Requires at least: 4.6
Tested up to: 6.1
Stable tag: 1.3

Disable plugins or switch themes for just you or the whole site for debugging, troubleshooting or accessing and restoring a broken website.

== Description ==

WP Safe Mode allows you to view your site temporarily with certain plugins disabled/enabled as well as switching to another theme.

This is particularly useful if you are experiencing problems with a specific plugin or theme and need troubleshoot without it affecting the rest of your site visitors.

Additionally, a loader file can be directly installed via FTP to help you access an inaccessible site due to PHP errors (e.g. blank screens or white screen of death) to help restore it from the admin panel.

= Main Features =

* Enter Safe Mode for just yourself whilst logged in.
* Enable Safe Mode for the whole site.
* Admin Bar shortcuts for enabling/disabling Safe Mode.
* Restrict Safe Mode to certain IP addresses.
* Automatic installation (if file permissions allow).
* Fallback to a default WordPress theme or one of your choice in Safe Mode.
* Prevent or allow Must-Use plugins from loading in Safe-Mode.
* Handy loader file via FTP when your site is completely inaccessible.
* MultiSite Support
 * Network-wide Safe Mode (for just you, or everyone)
 * Restrict Network-wide Safe Mode to certain IP address
* MultiSite Site-Specific Safe Modes
 * Override Network-wide Safe Mode settings for an individual site
 * Network Admins can deactivate Network-Active and Must-Use plugins.
 * Allow individual site admins from enabling safe mode for their own site.

= Data Privacy and GDPR Compliance =
No personal data is used or stored by this plugin. For those entering user-only mode, a cookie is loaded to identify that user.

== Installation ==

= Installing =

1. If installing, go to Plugins > Add New in the admin area, and search for events manager.
2. Click install, once installed, click 'activate'.
3. The WP Safe Mode loader will attempt to install itself automatically.
 * If something goes wrong, you'll be asked to visit the settings page for manual installation instructions.
4. Visit the WP Safe Mode menu item on your dashboard admin area.
5. Modify your Safe Mode settings as needed.
6. Enable Safe Mode by clicking one of the buttons, or via the Admin Bar within the Safe Mode menu item.

= Manual Instllation (Recommended) =

1. Download the WP Safe Mode plugin and unzip it, you'll now have a `wp-safe-mode` folder.
2. Connect to your server (for example via FTP) and go to your website folder.
3. Add this line to your `wp-config.php` file:
    *`if( !defined('WPMU_PLUGIN_DIR') ) define( 'WPMU_PLUGIN_DIR', dirname(__FILE__).'/wp-content/wp-safe-mode' ); //WP Safe Mode`*
4. Create the folder named `wp-safe-mode` inside your `wp-contents` folder.
5. Uploade the file `wp-safe-mode/bootstrap/wp-safe-mode-loader.php` into the newly created `wp-safe-mode` folder.
6. Upload the entire `wp-safe-mode` folder to your plugins folder in `wp-content/plugins`.

If your site is broken and you cannot install plugins or access the dashboard, you can modify the loader file temporarily to gain access by following these additional steps:

7. Open the plugin file `wp-safe-mode-loader.php` and change this line:
    *`public $safe_mode_on = false;`*
    to
    *`public $safe_mode_on = true;`*
8. Upload the modified `wp-safe-mode-loader.php` file to the `wp-contents/plugins/wp-safe-mode` folder.
9. Visit your site, deactivate plugins etc.
10. Undo the changes you just made to `wp-safe-mode-loader.php` when you want to disable safe mode.

= Further Installation Details =

You can also enable and disable plugins and themes directy from the loader file code by modifying the properties of the WP_Safe_Mode class, as well as enabling safe mode in different ways such as restricting it to specific IPs.

Please see the PHP code comments within the class for more information on each property.

The loader can also be installed directly into your mu-plugins folder, but we recommend our own installation steps if you already have files in that folder (i.e. it already exists). Otherwise you can also upload it there directly.

== Changelog ==
= 1.3 =
* added toggle for adding all plugins in one go to activate or keep active in safe mode

= 1.2 =
* fixed fatal errors on environments where wp-config.php is not in root folder,
* added double-check that wp-config.php has content to overwrite in event there's a symlink or something odd like that
* fixed minor php warning upon deactivation of plugin

= 1.1 =
* added failsafe check for some edge case WSOD (e.g. mu-plugins using hardcoded directory path includes) before installing loader

= 1.0 =
* First Release