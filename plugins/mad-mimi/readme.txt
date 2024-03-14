=== Mad Mimi for WordPress ===
Tags: madmimi, mad mimi, widget, newsletter, form, signup, newsletter widget, newsletter plugin, newsletters, emails, email, email newsletter form, newsletter form, newsletter signup, email widget, email marketing, newsletter, form, signup
Requires at least: 3.2
Tested up to: 3.7
Stable tag: trunk
Contributors: katzwebdesign, katzwebservices
Donate link:https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=zackkatz%40gmail%2ecom&item_name=Mad%20Mimi%20for%20WordPress&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8

Integrate Mad Mimi with your WordPress website: includes signup form widgets and automatically adding registered users to lists.

== Description ==

<h3>Add Mad Mimi signup form to your WordPress website.</h3>

<strong>What is Mad Mimi?</strong><br />
Mad Mimi is a lovely, simple email service that lets you create, send and track emails. Over 32,000 businesses use Mad Mimi to handle email the simple way.

This plugin adds a Mad Mimi signup form to your website in the content and the sidebar of your site.

<h4>MadMimi Plugin Features:</h4>
* Select which forms users subscribe to
* Unlimited signup forms for any number of email lists
* Automatically add registered users to a mailing list of your choice
* Choose to include a number of fields in the form, including:
	* Email
	* Name
	* Phone
	* Company
	* Title
	* Address
	* City
	* State
	* ZIP
	* Country

<h4>Get support on the <a href="http://www.seodenver.com/mad-mimi/">Mad Mimi plugin page</a></h4>

### Introduction to using Mad Mimi
[youtube http://www.youtube.com/watch?v=I8hs45fI3X8]

== Installation ==

1. Upload plugin files to your plugins folder, or install using WordPress' built-in Add New Plugin installer
1. Activate the plugin
1. Go to the plugin settings page (under Settings > Mad Mimi)
1. Enter in your Mad Mimi Username (the account's email address) and the API key. (Find your API Key at <a href="https://madmimi.com/user/edit" target="_blank" rel="nofollow">https://madmimi.com/user/edit</a>)
1. Click Save Changes.
1. If the settings are correct, a link will appear to the widgets page (Appearance > Widgets). Follow it.
1. Drag the Mad Mimi Signup Form widget to a sidebar, and configure the form.
1. If you want the form to be embedded in content, instead of shown in the sidebar, check the checkbox for "Do not display widget in sidebar", then follow the instructions for inserting the shortcode into your content where you would like the form to be displayed.

== Screenshots ==

1. How the widget appears in the Widgets panel
2. How the signup form appears in the sidebar of a site using the twentyten theme
3. How submissions appear on the Mad Mimi Audience page

== Frequently Asked Questions ==

= Requirements =
* __Requires PHP5__ for list management functionality.

If your web host does not support PHP5 and/or `curl`, please contact your host and see if they can upgrade your PHP version and activate `curl`; generally this can be done, and at no cost.

= What is the plugin license? =

* This plugin is released under a GPL license.

= What is Mad Mimi? =
Mad Mimi is the easiest email marketing tool out there. It's built from the ground up to be simple and usable.

= Do I need a Mad Mimi account to use this plugin? =
Yes, this plugin requires a Mad Mimi account.

= How do I use the `apply_filters()` functionality? (Added 1.1) =
If you want to change some code in the widget, you can use the WordPress `add_filter()` function to achieve this.

You can add code to your theme's `functions.php` file that will modify the widget output. Here's an example:
<pre>
function my_example_function($widget) {
	// The $widget variable is the output of the widget
	// This will replace 'this word' with 'that word' in the widget output.
	$widget = str_replace('this word', 'that word', $widget);
	// Make sure to return the $widget variable, or it won't work!
	return $widget;
}
add_filter('mad_mimi_signup_form', 'my_example_function');
</pre>

You can also modify the error message by hooking into `mad_mimi_signup_form_error` in a similar manner.

== Changelog ==

= 1.5.1 =
* Fixed: Translation file location
* Added: Some translations. <a href="https://poeditor.com/join/project?hash=951d8f975d29170e7a7c62aaa2fa22a7">Help translate the plugin!</a>
* Fixed: PHP Notice

= 1.5 =
* Added: Users added in the WordPress admin are now added to lists if "Sync Users" is enabled.
* Fixed: Some PHP warnings

= 1.4.6 =
* Fixed some PHP warnings
* Moved including the widget file to the MadMimi class - hopefully that will solve end of file issues for some users.

= 1.4.5 =
* Added debug setting to allow users to submit more detailed error reports.
* Added notice re: <a href="http://www.gravityforms.com?r=mmapireadme">Gravity Forms</a> Mad Mimi Add-on
* Fixed issue with form function not accepting `title=>true`, but only `title='true'` (<a href="http://wordpress.org/support/topic/578739" rel="nofollow">issue #578739</a>)

= 1.4.4 =
* Updated API call to be more secure - prevents the <a href="http://en.wikipedia.org/wiki/Firesheep" rel="nofollow">Firesheep</a> security vulnerability over unsecured WiFi.

= 1.4.3 =
* Fixed issue with user registrations not being properly added to lists.

= 1.4.2 =
* Fixed potential bug on line 368 of `madmimi-widget.php`, as reported by <a href="http://hacklab.com.br">Leo Germani</a>. Thanks again, Leo!

= 1.4.1 =

* Added check for `is_wp_error` to prevent errors from throwing their own error as reported: http://wordpress.org/support/topic/plugin-mad-mimi-for-wordpress-mad-mimi-14-plugin-breaks-plugin-page-with-fatal-php-error

= 1.4 =
Lots of improvements in this update.

* Added option for adding text before form
* Changed method of accessing data from `curl()` to WordPress functions `wp_remote_get()` and wp_remote_post()` - now if users don't have `curl()` on their servers, they still may be able to use the plugin.
* Added filters for text before form (`madmimi_form_description` and `madmimi_form_description_[form ID]`
* Added URL validation and sanitation to success page redirection
* Added basic phone validation to form submissions. Not strict, so works for international & US/Canadian numbers
* Added `label` to error messages; clicking on the messages will focus users on the field throwing the error.
* Improved error generation
* Improved speed of plugin by caching email lists using `set_transient()`
* Fixed success page redirection
* Fixed `Undefined offset` PHP notices
* Fixed display of HTML code in success message


= 1.3 =
Structural improvements in Version 1.3 are thanks to <a href="http://hacklab.com.br">Leo Germani</a>.

* Created a class, so (almost) everything is inside it. No more globals named $user or $api.
* Changed the way settings are saved based on the best practices:
	- Use of register_setting() and settings_field() (which takes care of the nonce and everything)
	- All options in a single database entry
* When displaying a widget, the plugin now checks if the settings are configured properly, otherwise it won't show the widget.
* Added code structure for internationalization of plugin. Details to come.
* Wrapped "thank-you" signup message in `<div class="mad_mimi_success">` for better formatting control.
* Added `mad_mimi_signup_form_success` filter for modifying the form submission message.
* Added `rel=nofollow` to the optional link to Mad Mimi.

= 1.2.2 =
* Runs a check to see if `curl_init` is supported by the web host; it's required for this plugin
* Fixed readme.txt links to MadMimi

= 1.2.1 =
* Critical upgrade - fixes widget not displaying in Widgets page

= 1.2 =
* Added support for PHP4 servers. The plugin won't have the user list management functionality, but everything else should work.
* Fixed potential error when submitting a form without a selected user list
* Added form id to input id's to allow for better `<label>` handling
* Added notice for users without PHP5
* Added `wpautop()` formatting to signup success message, meaning that it will add paragraphs if none were provided

= 1.1 =
* For those experiencing the `implode()` fatal error (it even *sounds* bad!), this update should fix it thanks to an updated `mimi_signup_lists()` function in `madmimi_widget.php`.
* Added error message check to make sure the error message displays on the form that was submitted, not another Mad Mimi form.
* Added checks for whether or not there are any lists, and if not, add the contact to the All Audience List
* Added three hooks for `add_filter()`: `mad_mimi_signup_form` modifies the form if used by shortcode or in the widget, `mad_mimi_signup_form_widget` modifies the widget output (including before and after the form), and `mad_mimi_signup_form_error` modifies the error message. Refer to the FAQ for more information.
* Updated widget display
= 1.0 =
* Initial plugin release

== Upgrade Notice ==

= 1.5.1 =
* Fixed: Translation file location
* Added: Some translations. <a href="https://poeditor.com/join/project?hash=951d8f975d29170e7a7c62aaa2fa22a7">Help translate the plugin!</a>
* Fixed: PHP Notice

= 1.5 =
* Added: Users added in the WordPress admin are now added to lists if "Sync Users" is enabled.
* Fixed: Some PHP warnings

= 1.4.6 =
* Fixed some PHP warnings
* Moved including the widget file to the MadMimi class - hopefully that will solve end of file issues for some users.

= 1.4.5 =
* Added debug setting to allow users to submit more detailed error reports.
* Added notice re: <a href="http://wordpressformplugin.com?utm_source=mmapi">Gravity Forms</a> Mad Mimi Add-on
* Fixed issue with form function not accepting `title=>true`, but only `title='true'` (<a href="http://wordpress.org/support/topic/578739" rel="nofollow">issue #578739</a>)

= 1.4.4 =
* Updated API call to be more secure - prevents the <a href="http://en.wikipedia.org/wiki/Firesheep" rel="nofollow">Firesheep</a> security vulnerability over unsecured WiFi.

= 1.4.3 =
* Fixed issue with user registrations not being properly added to lists. Very important if you want this functionality to function!

= 1.4.2 =
* Fixed potential bug on line 368 of `madmimi-widget.php`, as reported by <a href="http://hacklab.com.br">Leo Germani</a>. Thanks again, Leo!

= 1.4.1 =
* Fixed `Cannot use object of type WP_Error as array` error as reported: http://wordpress.org/support/topic/plugin-mad-mimi-for-wordpress-mad-mimi-14-plugin-breaks-plugin-page-with-fatal-php-error

= 1.4 =
* Changed method of accessing data from `curl()` to WordPress functions `wp_remote_get()` and wp_remote_post()` - now if users don't have `curl()` on their servers, they still may be able to use the plugin.
* Fixed success page redirection
* Improved speed of plugin by caching email lists using `set_transient()`
* Fixed `Undefined offset` PHP notices
* Fixed display of HTML code in success message

= 1.3 =
* Structural improvements, thanks to <a href="http://hacklab.com.br" rel="nofollow">Leo Germani</a>

= 1.2.2 =
* If you have experienced `Call to undefined function curl_init()` or `simplexml_load_string() Entity: line 1: parser error : Start tag expected` errors, this update should provide further information and better handle the errors

= 1.2.1 =
* Critical upgrade - fixes widget not displaying in Widgets page.

= 1.2 =
* Added support for PHP4 servers. The plugin won't have the user list management functionality, but everything else should work.
* Fixed potential error when submitting a form without a selected user list (`ActiveRecord::RecordInvalid: Validation failed: Name can't be blank`)

= 1.1 =
* This update should fix the `implode()` fatal error for those experiencing that error
* Adds `add_filter()` functionality. If you don't know what that is, don't worry about it :-)

= 1.0 =
* Blastoff!