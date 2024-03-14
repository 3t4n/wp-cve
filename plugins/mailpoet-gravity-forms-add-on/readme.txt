=== MailPoet Gravity Forms Add-on ===
Contributors: wysija, sebd86
Tags: mailpoet, wysija, gravity forms, sebs studio, extension, add-on
Requires at least: 3.7.1
Tested up to: 3.9.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a new field for you to allow your visitors to subscriber to your MailPoet newsletters.

== Description ==

> This plugin requires <a href="http://wordpress.org/plugins/wysija-newsletters/" rel="nofollow">MailPoet plugin</a> and <a href="https://www.e-junkie.com/ecom/gb.php?cl=54585&c=ib&aff=100570" rel="nofollow">Gravity Forms plugin</a>.

This simple plugin adds a new field for you to allow your visitors to subscriber to your MailPoet newsletters.

= Localization =
* English (US)[Default] - always included. mailpoet-gravity-forms-addon.pot file in language folder for translations.

If you would like to do a translation for the plugin, you can do so via Transifex.  (https://www.transifex.com/projects/p/mailpoet-gravity-forms-add-on/)

Simply select or add a language you want to translate in and I will attach the language in the next version release. You will need an account on Transifex to do this.

If you have done a translation via PoEdit, then you are welcome to send that also. To send your translation files contact me. (http://www.sebs-studio.com/contact/?contacting=Translations)

I'll acknowledge your contribution here with either your full name or username given.

= Documentation =

For all documentation on this plugin go to: https://github.com/seb86/MailPoet-Gravity-Forms-Add-on/wiki

= Contributing =

To contribute to the plugin, visit https://github.com/seb86/MailPoet-Gravity-Forms-Add-On/blob/master/CONTRIBUTING.md for details.

== Installation ==

= Minimum Requirements =

* MailPoet
* Gravity Forms

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't even need to leave your web browser. To do an automatic install of MailPoet Gravity Forms Add-On, log in to your WordPress admin panel, navigate to the Plugins menu and click Add New.

In the search field type "MailPoet Gravity Forms Add-On" and click Search Plugins. Once you've found my plugin extension you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking Install Now. After clicking that link you will be asked if you're sure you want to install the plugin. Click yes and WordPress will automatically complete the installation.

= Manual installation =

The manual installation method involves downloading my plugin and uploading it to your webserver via your favourite FTP application.

1. Download the plugin file to your computer and unzip it
2. Using an FTP program, or your hosting control panel, upload the unzipped plugin folder to your WordPress installation's wp-content/plugins/ directory.
3. Activate the plugin from the Plugins menu within the WordPress admin.

= Setting up the Plugin =

When you edit your form or your creating a new form, under "Standard Fields" select the MailPoet button. A new field will be added to your form. Now edit that field to setup.

Fill in the required field id numbers for First Name, Last Name and Email address.

If you wish to enable a multi-list selection then enable it and select the lists your users can then select.

If you want just a simple single checkbox then make sure that you have selected the lists from the settings page under "Gravity Forms -> Settings -> MailPoet"

Once you are happy with the settings, press "Save Form". If you haven't added the form to a post/page yet then insert the form to that post/page.

That's it.

= Upgrading =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Screenshots ==

1. MailPoet settings under "Gravity Forms -> Settings"
2. Form with MailPoet field applied
3. Form on post/page

== Changelog ==

= 2.0.4 - 19/08/2014 =

* Fixed white screen of death when upgrading Gravity Forms
* Fixed users are forced to subscribe when multiselect is disabled

= 2.0.3 - 17/05/2014 =

* Fixed version check generating a fatal error depending on the version of Gravity Form used on the site
* Fixed issue with lists selection when generating a form with a MailPoet field (Thx Danilo Petrozzi for reporting the issue :) )

= 2.0.2 - 24/03/2014 =

* ADDED - Plugin Update Information - Now you can read what changes have been made on the plugins page when an update is available.
* TESTED - WordPress 3.9 beta 2 and works.
* UPDATED - POT file.

= 2.0.1 - 24/03/2014 =

* CORRECTED - Issue with screen id used for settings page, prevented from loading if in any other language but English.
* CORRECTED - If function 'mailpoet_lists' is already defined, then don't load again.
* REMOVED - Translation of the brand name 'MailPoet' only.
* UPDATED - POT file.

= 2.0.0 - 20/03/2014 =

* IMPROVED - Code structure to help correct some of the core functions so it responds better.
* IMPROVED - Javascript in form editor to identify if the field is enabling multi-list selection or not.
* CORRECTED - Now the plugin checks if Gravity Forms is installed and activated first.
* CORRECTED - Display of checkboxs, lists only the selected lists from the form editor if enabled multi-selection.
* CORRECTED - When single checkbox is in use, it subscribes the users to the lists selected from the settings page.
* CORRECTED - MailPoet field no longer stops the rest of the form from loading if MailPoet is not activated.
* ADDED - Check if Gravity Forms is not the minimum version required, the user is asked to update it.
* ADDED - Settings page under Gravity Forms to configure correctly the single checkbox option used in the form.
* ADDED - Portuguese (Brazil) language.
* UPDATED - POT file.

= 1.0.0 - 02/02/2014 =

 * Initial Release.

== Upgrade Notice ==

= 2.0.2 =

Version 2.0.1 corrects a number of issues. A Screen ID that was used was not tested in other languages. It was not reliable and prevented the settings page to load if another language was used.

A single function 'mailpoet_lists' that is used in all add-ons had come up already defined if more than one add-on is used. This has been corrected to only load once and no more.

Translation of the brand name 'MailPoet' only has been removed as it is not needed to be tranlsated.

Also this lovely "Upgrade Notice" has been added to show you the changes made.