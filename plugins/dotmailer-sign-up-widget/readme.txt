=== Dotdigital for WordPress ===
Contributors: dotMailer, bsstaveley, amucklow, fstrezos, pvpcookie
Tags: email marketing, newsletter signup
Requires at least: 5.3
Tested up to: 6.4.3
Requires PHP: 7.4
Stable tag: 7.1.2
License: MIT
License URI: https://opensource.org/licenses/MIT


== Description ==

Add the Dotdigital for WordPress plugin to your site and allow your visitors to sign up to your Dotdigital-powered newsletter and email marketing campaigns. The email addresses of new subscribers can be added to multiple Dotdigital lists.

If you're not a Dotdigital user already you can find out more about us at <a href="https://www.dotdigital.com">Dotdigital.com</a>.

== Installation ==

Please refer to our documentation for <a href="https://support.dotdigital.com/en/articles/8199395">detailed information on installation and usage</a>.

== Frequently Asked Questions ==

= Q. My site is hosted by WordPress.com. Will the plugin work for me? =
A. Yes, the plugin will work, but you will need the WordPress.com Business Plan to install plugins.

= Q. Can I select more than one list to sign contacts up to? =
A. You can opt to send contacts to multiple lists, some of which can be hidden from the user.

= Q. Can contacts who have previously unsubscribed from my mailing lists re-subscribe through the plugin? =
A. Yes they can.

= Q. My contacts are not appearing in my list. Why is this? =
A. Check you have followed the installation steps correctly and that your API email and API password appear exactly as they do in your Dotdigital account. Remember that the API email is automatically generated and should not be changed.

= Q. Can I send the user to a custom Thank You page after subscription? =
A. You can find the redirection options under the Redirections tab in the plugin dashboard. Here you can set up 3 options:
* No redirection (default): the user will stay on the same page where a short message will be displayed about the result of the subscription
* Local page: you can select a page from your website that will be your Thank you page
* Custom URL: with this option you can redirect your user wherever you want to. If you choose this option, please use a valid URL starting with "http://" or "https://" here.

You can also set redirection for any widget instance using the sidebar settings in the editor.

= Q. How can I insert a signup form into my posts and pages? =
A. The preferred approach is to use the WordPress editor, and simply drag either of our blocks into the content area. An alternative (useful if your posts and pages don't use the WordPress editor), you can also use the [dotdigital-signup] shortcode to show the form inside the post's content. Shortcode parameters:

showtitle=0 - Hide the title of the widget
showdesc=0 - Hide the description under the title
redirection="URL" - Redirect the user to a custom URL after successful submission

Example: [dotdigital-signup showtitle=0 showdesc=0 redirection="http://www.example.com"] (will show the form without a title and description and will redirect the user to http://www.example.com on success)

== Screenshots ==

1. Enter your Dotdigital API credentials to get started.
2. Select list(s).
3. Capture additional contact data.
4. Add a simple newsletter signup form in the WordPress editor.
5. Add an embedded survey from Dotdigital.
6. Add a popover form from Dotdigital.

== Changelog ==

= 7.1.2 =

**Bug fixes**
- We fixed a problem with form submissions not redirecting from the REST endpoint.

= 7.1.1 =

**Improvements**
- We updated the label for the AJAX submission checkbox in our signup form block.

= 7.1.0 =

**What's new**
- Dotdigital Signup Form blocks can now be submitted via AJAX.

**Improvements**
- We updated a support link in our readme.txt.

= 7.0.0 =

**What's new**
- The plugin has been rewritten to fit a more modern, object-oriented boilerplate.
- We have provided a new Gutenberg block - 'Dotdigital Signup Form' - for the main plugin widget.
- A new block is also available to embed Surveys, Pages and Forms from Dotdigital.
- We've added a daily cron to gather data from the plugin configuration and widget usage.

**Improvements**
- The plugin's vendor namespaces are now prefixed before SVN submission, to prevent namespace clashes.

= 6.0.3 =

**Bug fixes**
- We repaired HTML input types for data fields displayed in the signup widget.

= 6.0.2 =

**Bug fixes**
- Address book and data field sorting has been repaired.
- Custom labels for address books and data fields now display correctly.

= 6.0.1 =

**Bug fixes**
- Incorrect API credentials could cause a fatal error after upgrade to v6.0.0; such errors are now handled appropriately.

= 6.0.0 =

**What's new**
- We've replaced the romanpitak API client library with our own PHP SDK for the Dotdigital API.
- This plugin now requires PHP 7.4 and is compatible with WordPress 5.3+.

**Improvements**
- We've started to bring the codebase in line with WordPress coding standards.

= 5.0.3 =

**Improvements**
- We've improved handling of account info when entering API credentials.
- We repaired some errors to do with validating required data fields.

**Bug fixes**
- Redirection now works as expected when using our shortcode in posts and pages.

= 5.0.2 =

**Improvements**
* We removed some dormant code concerned with collecting system data from the WordPress instance.

**Bug fixes**
* API credentials are no longer exposed in the stack trace in the event of a fatal error.

= 5.0.1 =

**Bug fixes**
* If no address books are selected, contacts will now be added to ‘All contacts’.
* We fixed a typo in the spelling of 'Unsubscribed'.

= 5.0.0 =

**What's new**
* The plugin has been renamed and rebranded.
* The `[dotdigital-signup]` shortcode has been added to replace `[dotmailer-signup]` (although that shortcode will continue to work).

**Improvements**
* We've made a number of presentational updates and improvements.
* Redirection on submit will now be blocked if the form has errors.

**Bug fixes**
* We fixed a bug to do with error detection in the `shutdown()` function.
* We've updated our check for contact status when a contact is retrieved from Dotdigital.
* We improved error handling for invalid API credentials.

4.0.5 (2017-11-28)

* Mod: PHP7 support
* Mod: Redirection url is always followed regardless of validation result

4.0.1 (2016-06-06)

* Fix: endpoint discovery error where region ID > 1
* Fix: small calendar display issue

4.0 (2016-03-04)

* Mod: the plugin is rewritten using the dotmailer REST (v2) API
* Add: endpoint discovery added - now your API calls use your regional API URL
* Fix: a few small layout fixes


3.4.2 (2015-07-01)

* Fix: position of the shortcode output wasn't correct in the previous versions


3.4.1 (2014-07-17)

* All strings from 'dotMailer' are changed to 'dotmailer' due to a branding change


3.4 (2014-07-03)

* Add: Redirection options in plugin admin, now you can redirect your users to a Thank You page after subscription
* Add: redirection attribute for the shortcode, which lets you use redirections locally from your form shortcodes


3.3 (2014-06-14)

* Add: Now you can add the dotMailer form with the [dotmailer-signup] shortcode to your posts and pages
* Fix: Wrong input type in forms (String) changed to (text)
* Fix: Some code cleanup


3.2.1 (2014-05-16)

* Fix: Now the user stays on the same page instead of being redirected to the home page after submission


3.2 (2014-05-02)

* Fix: Remove warning in the widget if no contact data was saved into the DB
* Fix: Version number confusion
* Mod: Now initial default messages are saved automatically to the database during plugin activation, so users need one step less to set it up properly.
* Mod: Now user settings are not deleted on plugin deactivation. Settings are only deleted if you uninstall the plugin.


== Upgrade Notice ==

= 4.0 =

* Mod: the plugin is rewritten using the dotmailer REST (v2) API
* Add: endpoint discovery added - now your API calls use your regional API URL
* Fix: a few small layout fixes

= 3.4 =

* Add: Redirection options in plugin admin, now you can redirect your users to a Thank You page after subscription
* Add: redirection attribute for the shortcode, which lets you use redirections locally from your form shortcodes

= 3.3 =
* Add: Now you can add the dotMailer form with the [dotmailer-signup] shortcode to your posts and pages
* Fix: Wrong input type in forms (String) changed to (text)
* Fix: Some code cleanup

= 3.2.1 =
* Fix: Now the user stays on the same page instead of being redirected to the home page after submission

= 3.2 =
* Fix: Remove warning in the widget if no contact data was saved into the DB
* Fix: Version number confusion
* Mod: Now initial default messages are save automatically to the database during plugin activation, so users need one step less to set it up properly.

* Mod: Now user settings are not deleted on plugin deactivation. Settings are only deleted if you uninstall the plugin.

= 3.1 =
* Fix for closing php tag on dm_widget

= 3.0 =
* Fix for reported javascript conflicts and styling issues

= 2.1 =
* Fix for Jquery Mini File, upgraded version to fix conflict

= 2.0 =
* Using the new Settings API.
* Extra features added.

= 1.1 =
* Fixed an error thrown
