=== Connections Business Directory Login  ===
Contributors: shazahm1@hotmail.com
Donate link: https://connections-pro.com/
Tags: address book, business directory, chamber of commerce, church directory, company directory, contact directory, directory, listings directory, local business directory, link directory, member directory, staff directory
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 3.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Extension for the Connections Business Directory that adds a shortcode and widget to display a login form.

== Description ==

This is an extension plugin for the [Connections Business Directory Plugin](http://wordpress.org/plugins/connections/) please be sure to install and active it before adding this plugin.

What does this plugin do?
It adds an [entry content block](http://connections-pro.com/documentation/login/#Content_Block), a [shortcode](http://connections-pro.com/documentation/login/#Shortcode) and a [highly configurable widget](http://connections-pro.com/documentation/login/#Widget) which displays a login form when a user is not logged into your site.

Version 3.1 introduced a [`[user_]` shortcode](https://connections-pro.com/documentation/login/#user) that allows you to display the currently logged-in user or specified user profile information, including user meta and the user avatar.

Version 3.2 introduced a [`[link_]` shortcode](https://connections-pro.com/documentation/login/#link) that allows you to output a login, logout, edit profile, registration, forgot password, or a dynamic login/logout link on a page.

Ok, great, but how does this benefit me?
Well, if you have the directory setup to require login, you can add the [[connections_login] shortcode](http://connections-pro.com/documentation/login/#Shortcode) to the [login required message setting](http://connections-pro.com/documentation/settings/#Require_Login) (or any page you want, the shortcode is not limited to this setting). When it is added and the user is not logged in and visit your directory, they'll be shown your message plus the login form.

The content block login form and the login widget are best used with [Link](http://connections-pro.com/add-on/link/) installed and activated. You can set up the login form content block to be shown on a single entry, that way when a user visits the page, and they are not logged in, they'll be shown a login form right on their page. Alternatively, you could use the widget which will only be displayed on the single entry page when a user is not logged in. It's your choice, you could use either one or both.

[Checkout the screenshots.](http://connections-pro.com/add-on/login/)

Here are some great **free extensions** (with more on the way) that enhance your experience with Connections Business Directory:

**Utility**

* [Toolbar](https://wordpress.org/plugins/connections-toolbar/) :: Provides quick links to the admin pages from the admin bar.

**Custom Fields**

* [Business Open Hours](https://wordpress.org/plugins/connections-business-directory-hours/) :: Add the business open hours.
* [Local Time](https://wordpress.org/plugins/connections-business-directory-local-time/) :: Add the business local time.
* [Facilities](https://wordpress.org/plugins/connections-business-directory-facilities/) :: Add the business facilities.
* [Income Level](https://wordpress.org/plugins/connections-business-directory-income-levels/) :: Add an income level.
* [Education Level](https://wordpress.org/plugins/connections-business-directory-education-levels/) :: Add an education level.
* [Languages](https://wordpress.org/plugins/connections-business-directory-languages/) :: Add languages spoken.
* [Hobbies](https://wordpress.org/plugins/connections-business-directory-hobbies/) :: Add hobbies.

**Misc**

* [Face Detect](https://wordpress.org/plugins/connections-business-directory-face-detect/) :: Applies face detection before cropping an image.

**[Premium Extensions](https://connections-pro.com/extensions/)**

* [Authored](https://connections-pro.com/add-on/authored/) :: Displays a list of blog posts written by the entry on their profile page.
* [Contact](https://connections-pro.com/add-on/contact/) :: Displays a contact form on the entry's profile page to allow your visitors to contact the entry without revealing their email address.
* [CSV Import](https://connections-pro.com/add-on/csv-import/) :: Bulk import your data in to your directory.
* [Custom Category Order](https://connections-pro.com/add-on/custom-category-order/) :: Order your categories exactly as you need them.
* [Custom Entry Order](https://connections-pro.com/add-on/custom-entry-order/) :: Allows you to easily define the order that your business directory entries should be displayed.
* [Enhanced Categories](https://connections-pro.com/add-on/enhanced-categories/) :: Adds many features to the categories.
* [Form](https://connections-pro.com/add-on/form/) :: Allow site visitor to submit entries to your directory. Also provides frontend editing support.
* [Link](https://connections-pro.com/add-on/link/) :: Links a WordPress user to an entry so that user can maintain their entry with or without moderation.
* [ROT13 Encryption](https://connections-pro.com/add-on/rot13-email-encryption/) :: Protect email addresses from being harvested from your business directory by spam bots.
* [SiteShot](https://connections-pro.com/add-on/siteshot/) :: Show a screen capture of the entry's website.
* [Widget Pack](https://connections-pro.com/add-on/widget-pack/) :: A set of feature rich, versatile and highly configurable widgets that can be used to enhance your directory.

== Installation ==

= Using the WordPress Plugin Search =

1. Navigate to the `Add New` sub-page under the Plugins admin page.
2. Search for `connections business directory login`.
3. The plugin should be listed first in the search results.
4. Click the `Install Now` link.
5. Lastly click the `Activate Plugin` link to activate the plugin.

= Uploading in WordPress Admin =

1. [Download the plugin zip file](http://wordpress.org/plugins/connections-business-directory-login/) and save it to your computer.
2. Navigate to the `Add New` sub-page under the Plugins admin page.
3. Click the `Upload` link.
4. Select Connections Business Directory Login zip file from where you saved the zip file on your computer.
5. Click the `Install Now` button.
6. Lastly click the `Activate Plugin` link to activate the plugin.

= Using FTP =

1. [Download the plugin zip file](http://wordpress.org/plugins/connections-business-directory-login/) and save it to your computer.
2. Extract the Connections Business Directory Login zip file.
3. Create a new directory named `connections-business-directory-login` directory in the `../wp-content/plugins/` directory.
4. Upload the files from the folder extracted in Step 2.
4. Activate the plugin on the Plugins admin page.

== Frequently Asked Questions ==

None yet...

== Screenshots ==
1. The login form rendered by the shortcode.
2. The `[user_]` shortcode for the current logged-in in the Block Editor.
3. The `[user_]` shortcode for a specific user in the Block Editor.
4. The `[user_]` shortcode for the current logged-in in the Classic Editor.
5. The `[user_]` shortcode for a specific user in the Classic Editor.
6. The `[user_]` shortcode for the current logged-in rendered on a page.
7. The `[user_]` shortcode for a specific user rendered on a page.

[Screenshots can be found here.](http://connections-pro.com/add-on/login/)

== Changelog ==

= 3.4 12/01/2023 =
* OTHER: Correct misspelling.
* OTHER: Correct README.txt error.
* DEV: phpDoc updates.
* DEV: Add WPCS.
* DEV: Update `.gitignore` and `.gitattributes` for Composer.
* WPCS: There must be exactly one blank line after the file comment.
* WPCS: Not using strict comparison for `in_array()`; supply true for `$strict` argument.
* WPCS: Various minor code sniff corrections.

= 3.3 11/22/2023 =
* NEW: Add support for the enclosing shortcode form to the `[user_]` shortcode.
* BUG: In addition to checking for a logged-in user the `id` attribute should check for a positive integer in case a user ID was specified in the `[user_]` shortcode.

= 3.2.1 11/14/2023 =
* BUG: Correct the namespace for the Login form Content Block, so it does not throw a fatal error.

= 3.2 11/10/2023 =
* FEATURE: Introduce the `[link_]` shortcode.
* TWEAK: Do not capitalize keywords.
* BUG: Correct the namespace for the form classes.
* BUG: Correct filter name. Steven A. Zahm A minute ago
* OTHER: Add screenshots to the README.txt.
* OTHER: Update README.txt to include a link to the documentation to the `[user_]` shortcode.
* OTHER: README.txt tweaks.
* DEV: phpDoc updates.

= 3.1 11/03/2023 =
* FEATURE: Introduce the `user_` shortcode.
* TWEAK: Refactor the shortcode to utilize the Shortcode API.
* TWEAK: Refactor the plugin initialization to occur on the `Connections_Directory/Loaded` action hook.
* TWEAK: Include the Request Reset Password, Reset Password, and User Register forms.

= 3.0 10/23/2023 =
* TWEAK: Remove unnecessary check for the `WPINC` constant.
* TWEAK: Make `Connections_Login` class `final`.
* TWEAK: Add `Connections_Login` class properties.
* TWEAK: Refactor loading the translations to use the `cnText_Domain` class.
* TWEAK: Refactor `getLoginFormDefaults()` to use `Request\Redirect` to set the default redirect value.
* TWEAK: Refactor `loginForm()` to utilize `Form\User_Login` instead of `wp_login_form()`.
* BUG: Ensure the "Login Form" Content Block setting name is translation ready.
* OTHER: Update plugin header requirements.
* OTHER: Correct misspelling.
* DEV: phpDoc corrections.

= 2.2.2 12/23/2021 =
* TWEAK: Update widget save settings so checkboxes are properly saved in WordPress >= 5.8.
* DEV: Do not evaluate widget instance setting when setting form field value.
* OTHER: Correct misspelling.
* DEV: phpDoc correction.

= 2.2.1 05/03/2021 =
* TWEAK: Remove use of `create_function()`.
* OTHER: Update copyright year.
* OTHER: Update `http` to `https`.
* DEV: Correct code formatting.
* DEV: phpDoc corrections.

= 2.2 06/23/2020 =
* TWEAK: If `get_permalink()` returns an empty URL, then default to `get_home_url()` for the login redirect parameter.
* OTHER: Update copyright year.
* OTHER: Update URLs from `http` to `https`.
* OTHER: Update "Test up to" version 5.4.

= 2.1 05/15/2019 =
* BUG: Check if variable is an array before counting to prevent PHP notice.
* I18N: Update POT file.
* I18N: Update Spanish (Spain) translation.
* I18N: Add French (France) translation.
* OTHER: Update copyright year.
* OTHER: Update plugin header name to match naming conventions used in other addons.
* OTHER: Update readme tags.
* OTHER: Update readme meta tags; requires, tested and minimum PHP version.

= 2.0.3 06/05/2018 =
* BUG: Shortcode returns content, not echo it.
* DEV: phpDoc update.

= 2.0.2 12/14/2017 =
* BUG: Prevent PHP warning; "Illegal string offset 'echo'".

= 2.0.1 03/17/2016 =
* NEW: Introduce the `cn_login_widget_link_anchor` filter.
* BUG: Use correct bbPress function to return the user topics created URL.
* TWEAK: Default logout link redirect URL to the current page.
* OTHER: Correct version number in changelog section of readme.txt.

= 2.0 03/02/2016 =
* FEATURE: Option to configure widget to be visible site wide in the sidebar or limited to only the entry detail/profile page in Connections.
* FEATURE: Configurable widget title based on if user is logged in or not.
* FEATURE: Option to disable the "Remember me" checkbox in the login form.
* FEATURE: Option to disable the "Lost Password" link in the login form.
* FEATURE: Add support for adding custom links which can be displayed to a logged out user.
* FEATURE: Option to display the users Gravatar when they are logged in.
* FEATURE: Option to set the Gravatar's image size.
* FEATURE: Option to display the user's admin profile link.
* FEATURE: Option to display the logout link.
* FEATURE: Add support for adding custom links which can be displayed to a logged in user.
* FEATURE: Support for bbPress.
* FEATURE: Support for BuddyPress.
* FEATURE: Extension support and integration with the [Link extension](http://connections-pro.com/add-on/link/).
* FEATURE: Add shortcode options to the [[connections_login] shortcode](http://connections-pro.com/documentation/login/#Shortcode) so the labels can be configured.
* NEW: Introduce the `cn_login_supported_tokens` filter.
* NEW: Introduce the `cn_login_avatar_size` filter.
* NEW: Introduce the `cn_login_logout_url` filter.
* NEW: Introduce the `cn_login_login_url` filter.
* NEW: Introduce the `cn_login_replace_tokens` filter.
* NEW: Introduce the `cn_login_image_types` filter.
* NEW: Introduce the `cn_login_widget_update_settings` filter.
* NEW: Introduce the `cn_login_before_widget_common_settings` action.
* NEW: Introduce the `cn_login_after_widget_common_settings` action.
* NEW: Introduce the `cn_login_before_widget_logged_out_settings` action.
* NEW: Introduce the `cn_login_after_widget_logged_out_settings` action.
* NEW: Introduce the `cn_login_after_widget_logged_in_settings` action.
* NEW: Introduce the `cn_login_widget_before` action.
* NEW: Introduce the `cn_login_widget_logged_in_before` action.
* NEW: Introduce the `cn_login_widget_logged_in_after` action.
* NEW: Introduce the `cn_login_widget_logged_out_before` action.
* NEW: Introduce the `cn_login_widget_logged_out_after` action.
* NEW: Introduce the `cn_login_widget_after` action.
* NEW: Introduce the `cn_login_display_image_{$type}` action.
* NEW: Introduce the `cn_login_widget_lost_password_url` filter.
* NEW: Introduce the `cn_login_widget_register_url` filter.
* NEW: Introduce the `cn_login_widget_register_url` action.
* NEW: Introduce the `cn_login_widget_{$context}_links` filter.
* NEW: Introduce the `cn_login_widget_before_{$context}_links` action.
* NEW: Introduce the `cn_login_widget_after_{$context}_links` action.
* TWEAK: Escape translated strings.
* I18N: Update POT file.
* I18N: Update es_ES PO/MO files.

= 1.1 07/06/2015 =
* BUG: Load the text domain immediately on plugins_loaded action so the translation files will be loaded.
* BUG: Remove stray period from version number.
* TWEAK: Refactor loadTextDomain() so it is consistent with the other extensions for Connections.
* I18N: Include the POT file.
* I18N: Add a Spanish (Spain) translation (machine translated).
* DEV: Update .gitignore.

= 1.0 08/08/2014 =
* Initial release.

== Upgrade Notice ==

= 2.1 =
It is recommended to back up before updating. Requires WordPress >= 4.7.12 and PHP >= 5.6.20 PHP version >= 7.1 recommended.

= 2.2 =
It is recommended to back up before updating. Requires WordPress >= 4.7.12 and PHP >= 5.6.20 PHP version >= 7.2 recommended.

= 2.2.1 =
It is recommended to back up before updating. Requires WordPress >= 5.1 and PHP >= 5.6.20 PHP version >= 7.2 recommended.

= 2.2.2 =
It is recommended to back up before updating. Requires WordPress >= 5.2 and PHP >= 5.6.20 PHP version >= 7.2 recommended.

= 3.0 =
It is recommended to back up before updating. Requires WordPress >= 5.8 and PHP >= 7.4 PHP version >= 7.4 is recommended.

= 3.1 =
It is recommended to back up before updating. Requires WordPress >= 5.8 and PHP >= 7.4 PHP version >= 7.4 is recommended.

= 3.2 =
It is recommended to back up before updating. Requires WordPress >= 5.8 and PHP >= 7.4 PHP version >= 7.4 is recommended.

= 3.2.1 =
It is recommended to back up before updating. Requires WordPress >= 5.8 and PHP >= 7.4 PHP version >= 7.4 is recommended.

= 3.3 =
It is recommended to back up before updating. Requires WordPress >= 6.0 and PHP >= 7.4 PHP version >= 7.4 is recommended.

= 3.4 =
It is recommended to back up before updating. Requires WordPress >= 6.0 and PHP >= 7.4 PHP version >= 7.4 is recommended.
