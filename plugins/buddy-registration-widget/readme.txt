=== BuddyPress Registration Widget ===
Contributors: pawaryogesh1989, clarionwpdeveloper
Tags: Buddypress registration, widget, buddypress widget, buddypress registration form widget, buddypress registration, buddypress registration form, buddypress form, disable buddypress cover image, buddypress registration shortcode, shortcode, buddypress custom registration templates, buddypress widget
Requires at least: 5.0 or higher
Tested up to: 5.6
Stable tag: 5.0
PHP Version: 5.6 or higher
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display BuddyPress Registration form as a Widget using this Plugin.

== Description ==

This plugin provides BuddyPress registration form as a widget with many other configurable option. Using this plugin the website administrator can disable member cover image, group cover image. Administrator can also allow the users to browse the website without the need for uploading a profile picture. Activity tab can also be removed from the user profile. The most important feature added is to allow the administrator use custom templates to display the registration form and it is done in such way that future plugin updates will not affect the customizations done.

Now, the registration form can also be displayed on any page using just a shortcode. Use shortcode "[buddyRegisterFormCode]" on any page to display the registration form.

== Details ==

* Display BuddyPress registration form as a widget.
* Use shortcode "[buddyRegisterFormCode]" to display the registration form on any page.
* Option to disable member/user cover image.
* Option to disable group cover image.
* Option to disable validation of "Profile Image".
* Option to remove the "Activity Tab" from the user profile.
* Option to use custom template to modified form fields/layout to display form in Sidebar.
* Option to use custom template to modified form fields/layout to display form on any page using shortcode.

== Screenshots ==

1. screenshot-1 - Configurable options in backend.

== Changelog ==

= 2.1.2 =
* Minor fixes for compatibility with latest version of WordPress

= 2.1.0 =
* Minor fixes for compatibility with PHP 7.3 and latest version of WordPress

= 2.0.0 =
* Added new screen in backend with configurable options.
* Added shortcode "[buddyRegisterFormCode]" to display the registration form on any page.
* Added option to disable member/user cover image.
* Added option to disable group cover image.
* Added option to disable validation of "Profile Image".
* Added option to remove the "Activity Tab" from the user profile.
* Added option to use custom template to modified form fields/layout to display form in Sidebar.
* Added option to use custom template to modified form fields/layout to display form on any page using shortcode.

= 1.0.0 =
* Display BuddyPress registration form as a widget.
* Disable Member and Group Cover Images.

== Installation ==

The Plugin can be installed in two ways.

= Download and Install =

1. Download and upload the plugin files to the `/wp-content/plugins/buddyregistration` directory from the Wordpress plugin repository.
2. Activate the plugin through the 'Plugins' screen in WordPress admin.

= Using Plugin Interface =

1. Open the plugin Interface in the wordpress admin and click on "Add new".
2. In the Search box enter "buddypress registration form widget" and hit Enter.
3. Click on "Install" to install the plugin.

== Frequently Asked Questions ==

= Does BuddyPress Registration Widget modify any file? =

No! The plugin does not modify any of your Wordpress files.

= Is there any plugin dependency to install this plugin? =

Yes! BuddyPress plugin should be installed to use this plugin.

= Does this plugin require any special permissions? =

No! This plugin does not require any special permissions or settings.

= Can I display the registration form on any page using a shortcode? =

Yes, you can show the registration form on any page using a shortcode. Use shortcode "[buddyRegisterFormCode]" to display the registration form on any page.

= Can I use custom template to display the registration form in widget? =

Yes, with this new feature you can absolutely use/modified the template. For this you just need select the "YES" option in the configuration and than copy the 'form-template.php' from 'wp-content/plugins/buddy-registration-widget/templates/' to 'wp-content/plugins/buddy-registration-widget/templates/custom' using a file manager like File Zilla or any appropriate tool. After copying you can do customization in the copied file and widget will use this customized template in frontend. Doing this will ensure that your changes will not be overwritten by future updates.

= Can I use custom template to display the registration form on a page using shortcode? =

Yes, with this new feature you can absolutely use/modified the template. For this you just need select the "YES" option in the configuration and than copy the 'shortcode-form-template.php' from 'wp-content/plugins/buddy-registration-widget/templates/' to 'wp-content/plugins/buddy-registration-widget/templates/custom' using a file manager like File Zilla or any appropriate tool. After copying you can do customization in the copied file and shortcode will use this customized template in frontend. Doing this will ensure that your changes will not be overwritten by future updates.

= Will my customizations be lost after plugin update in future? =

No, your customizations will not be lost.

= Can I disable/enable the Group cover and Member cover images anytime? =

Yes, now with the new features you can any time disable/enable the Group cover and Member cover images from the backend. Please see the screenshot for the available options.

= Can I disable/remove the forced upload of "Profile Image" for users on my site? =

Yes, you can disable the upload of "Profile Image/Avatar" and let the user browse the website without the need to upload one. Please check screenshot for the option available.

= Can I remove the "Activity" tab from the user profile? =

Yes, you can remove the "Activity" tab from the user profile. Please check screenshot for the option available.