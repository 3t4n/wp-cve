=== WP Updates Settings ===
Contributors: Yslo
Tags: automatic, background, updates, admin, plugin, core, theme
Requires at least: 3.7
Tested up to: 4.9
Stable tag: 1.1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Configure WordPress updates settings through UI (User Interface).

== Description ==

Allows you the ability to set Updates and Automatic Background Updates through Settings panel.

= Features =

* Show/hide Updates notification
* Use default Wordpress behaviors
* Enable/Disable Updates capabilities to Administrator users
* Set Major Core Automatic Background Updates
* Set Minor Core Automatic Background Updates
* Set Plugin Automatic Background Updates
* Set Theme Automatic Background Updates
* Set Translation files Automatic Background Updates
* Set Auto Core Update Notification emails.
* Add Updates panel (Settings > Updates)
* Contextual Help
* Translation MO/PO files
* Multisite
* Desactivate restore default WordPress behavior
* Uninstall restore default WordPress behavior

= Languages =

* English
* French

== Installation ==

1. Download and extract plugin files to wp-content/plugin directory.
2. Activate the plugin through the WordPress admin interface.

You will find 'Updates' menu in your WordPress Settings panel.

== Upgrade Notice ==

* Upgrade (1.0.x -> 1.0.3 and more) : Your settings are automatically upgraded with previous settings.
* Upgrade (1.0.1 -> 1.0.2) : You must reset your settings.
* Upgrade (1.0.0 -> 1.0.1) : Restore default WordPress updates behaviors. You must reset your settings.

== Screenshots ==

1. Activate the plugin
2. Like to Settings Updates panel
3. Updates link added to Settings panel
4. Updates Settings page and Help

== Frequently Asked Questions ==

= I can't activate WP Updates Settings =

* WP Updates Settings could be activated on WordPress 3.7.x and more. If you are using an older version, WP Updates Settings can't be activated.

== Changelog ==

= 1.1.4 =
* Feature : better translation integration with WordPress.org

= 1.1.3 =
* Fix : Undefined index saving options on debug mode true

= 1.1.2 =
* Code improvement

= 1.1.1 =
* Fix : Default Auto Core Update Notification emails setting.

= 1.1.0 =
* Feature : Auto Core Update Notification emails.

= 1.0.4 =
* Prevent activation on WordPress 3.6.x (and less)
* Fix : Settings Updates panel CSS is limitated to this panel

= 1.0.3 =
* Fix : previous plugin settings on update

= 1.0.2 =
* Deactivate restore default WordPress behavior
* Settings Updates panel CSS

= 1.0.1 =
* Multisite
* Optimize admin_init actions
* Fix activate/unactivate plugin loss of previously saved settings
* Translation improvement

= 1.0.0 =
* Initial version
