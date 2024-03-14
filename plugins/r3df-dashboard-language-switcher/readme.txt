=== R3DF Dashboard Language Switcher ===
Contributors: r3df
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MX3FLF4YGXRLE
Tags: dashboard, admin, multi-lingual, multilingual, language, languages, native language, localization, locale, switch, switcher
Stable tag: 1.0.2
Requires at least: 4.1
Tested up to: 4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables individual user selection and setting of dashboard language: at logon, in admin with a toolbar menu, or by user profile setting.


== Description ==
This plugin allows logged in users to select the language they would like to use when viewing the WordPress dashboard. It works with multisite and single site installs of WordPress.

= The plugin offers several options for language settings: =
1. WordPress admin toolbar switcher - user can easily switch language on admin pages.
1. logon screen switcher - user can specify his/her preferred language during logon.
1. user profile setting - user can choose a language in their user profile.

= Notes: =
* This plugin does NOT add languages to WordPress, you need to add them to use the switcher. (see below)
* This plugin does NOT translate other plugins or themes, it manages the language setting for the WordPress site. (see below)
* The admin toolbar switcher takes priority over the other options. If a language selection is made via the toolbar, it changes the user profile setting to the current language selection.
* If enabled, the login switcher changes the user profile setting to requested language selection at login.

= Installing WordPress languages: =
To add languages to WordPress ( since 4.1 ), simply select a new language from the ones available on the "Site Language" setting on the "General Settings" page.
When you save your settings, the new language will be added to the site.  (and selected as the current language)

= Plugin and theme translations: =
It is up to plugin and theme authors to provide translations for their works. If you change the site language, and a plugin or theme is not translated (usually still showing English),
you need to contact the authors of those works to get the needed translation files.

There is a French translation of this plugin included. It was mostly a test of the translation of the plugin.  It's not a great translation, if you can improve it please let me know.


== Installation ==
= The easy way: =
1. To install this plugin, click on "Add New" on the plugins page in your WordPress dashboard.
1. Search for "R3DF Dashboard Language", click install when it's found.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Configure the plugin in the settings page.

= The hard way: =
1. Download the latest r3df-dashboard-language.zip from wordpress.org
1. Upload r3df-dashboard-language.zip to the `/wp-content/plugins/` folder on your web server
1. Uncompress r3df-dashboard-language.zip (delete r3df-dashboard-language.zip after it's uncompressed)
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure the plugin in the settings page.


== Frequently Asked Questions ==
= I installed the plugin, but my site only has English. =
You need to install/add languages to your site.  The plugin only changes the locale settings, it does not add languages.

** Installing WordPress languages: **
To add languages to WordPress ( since 4.1 ), simply select a new language from the ones available on the "Site Language" setting on the "General Settings" page.
When you save your settings, the new language will be added to the site.  (and selected as the current language)

= Plugin (or theme) <XXXXXXX> is not changing languages =
It is up to plugin and theme authors to provide translations for their works. If you change the site language, and a plugin or theme is not translated (usually still showing English),
you need to contact the authors of those works to get the needed translation files.


== Screenshots ==
1. The admin toolbar language selector.

== Changelog ==
= Version 1.0.2 =
* Bug fix for empty array when saving options with no locales hidden

= Version 1.0.1 =
* Minor bug fixes
* Some more refactoring
   * Moved deactivate cleanup to uninstall

= Version 1.0.0 =
* Initial release


== Upgrade Notice ==
= 1.0.2 =
* Bug fix

= 1.0.1 =
* Minor bug fixes
* DB change will cause settings to be reset

= 1.0.0 =
* Initial release
