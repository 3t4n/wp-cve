=== Request Call Back ===
Contributors: scottsalisbury
Tags: request call back, request callback, callback button, call back button, callback form, call back form
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 1.4.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Adds a simple, configurable request call back button to your site. Visitors can provide their name and number via lightbox or embedded request form.

== Description ==

As the developer of several WordPress microsites, a commonly requested feature is to add a request call back button to the website header that links to a call back request form.

I originally made this plugin for myself to simplify the process of adding this basic feature, but recently decided to make it public just in case others find it useful.

There are other plugins available that do a similar task to this, but I wanted this to do more than just add a form to a page. 

By default, this plugin displays a call back button that can be added anywhere on the site via a single line of code (e.g. header.php), which when clicked will open the request form in a lightbox. The button label, colour, position is configurable from the plugin settings page. 

The plugin also provides the option to add the request form to an existing WordPress page instead of opening a lightbox, if preferred. This feature is useful if you don't want to use the built-in request button and want to use your own link instead.

= Configurable options =
* Button label
* Choice of preset colours, or remove styling to add your own
* Custom CSS and classes
* Button position
* Enable or disable lightbox/embedded mode
* Email address to send details
* Optional email address, time to call and message form fields
* Thank you page
* Options to customise form field labels and placeholders
* Options to customise the form width for both lightbox and embedded forms

== Installation ==

1. Unzip package contents
2. Upload "`request-callback`" directory to the "`/wp-content/plugins/`" directory
3. Activate the plugin through the "`Plugins`" menu in WordPress
4. Configure the plugin by going to "`Settings > Request Call Back`". Note: the plugin will work with default settings, however it is recommended to configure your email address and "`thank you`" page.
5. To add the button, place "`<?php do_action('wpcallback_button'); ?>`" in your template file where you want the button to appear (e.g. header.php). Note: you need this code if using lightbox mode (default). If "`Display mode`" is set to use an existing page, adding the button code is optional.
6. Make any required CSS and style modifications to meet your needs.
7. Test and enjoy :)

== Frequently asked questions ==

= The plugin doesn't appear to be sending emails, what's wrong? =
Sending emails is done using WordPress's built in mail function. If emails aren't sending and your email address has been configured in the plugin options, it is likely that your entire WordPress site is unable to send emails. This could be due to an incorrectly set up mail server, or emails getting lost in your spam folders.

If you're still having trouble then you will have to get in contact with your server host.

= I don't want to use the lightbox, how do I add the request form to an existing WordPress page? =
In plugin settings "`Settings > Request Call Back`", scroll down and set "`Display mode`" to "`Display form on an existing page`", then select the page you want to attach it to.

== Screenshots ==

1. Example of what the call back button looks like on a site header
2. Screen shot of the lightbox form
3. Screen shot of the embedded form
4. Screen shot of the options page

== Changelog ==

= v1.1 =
* First public release of this plugin

= v1.2 =
* Minor fixes

= v1.3 =
* New feature. Three extra form fields can be added to the call back form (email address, time to call and message) via the plugin options.

= v1.4.1 =
* Added ability to customise form field labels/placeholders and customise the form width

== Upgrade notice ==

= v1.1 =
* First public release of this plugin

= v1.2 =
* Minor fixes

= v1.3 =
* New feature, three extra form fields can be added to the call back form (email address, time to call and message).

= v1.4.1 =
* Added ability to customise form field labels/placeholders and customise the form width