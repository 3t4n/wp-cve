=== Emma for WordPress ===
Contributors: ahsodesigns, brettshumaker, thackston
Tags: Plugin, Emma, MyEmma, emarketing, form, custom, api, widget, shortcode, subscription
Author URI: http://ahsodesigns.com
Plugin URI: http://ahsodesigns.com/what-we-do/plugin-development/
Requires at least: 3.0
Tested up to: 4.8.1
Stable tag: 1.3.3
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Emma WordPress plugin allows you to quickly and easily add a signup form for your Emma list as a widget or a shortcode.

== Description ==

The Emma for Wordpress plugin allows you to quickly and easily add a signup form for your Emma list as a widget on your WordPress 3.0 or higher site.

After the plugin is installed, the setup page will guide you through entering your Emma API login information, selecting your group, setting up the fields for your form, customizing the form, and then adding the Widget to your site. The great news is that this whole process will take less than five minutes and everything can be done via the WordPress Dashboard Setting GUI – no file editing at all!

[Sign up today!](http://myemma.com/partners/get-started?utm_source=Wordpress&utm_medium=integrationpartner&utm_campaign=Wordpress-integrationpartner-partner-trial)

== Installation ==

**About the plugin:**

The Emma for Wordpress plugin allows you to quickly and easily add a signup form for your Emma list as a widget on your WordPress 3.0 or higher site.

After the plugin is installed, the setup page will guide you through entering your Emma API login information, selecting your group, setting up the fields for your form, customizing the form, and then adding the Widget to your site. The great news is that this whole process will take less than five minutes and everything can be done via the WordPress Dashboard Setting GUI – no file editing at all!

&nbsp;

= Usage Documentation =

The Emma for WordPress plugin allows you to quickly and easily add a signup form for your Emma audience as a widget or a shortcode on your WordPress 3.0 or higher site.

After the plugin is installed, the setup page will guide you through entering your Emma API login information, selecting your group, setting up the fields for your form, customizing the form and adding the widget to your site.

1. You can generate your API keys in your account by clicking the tools icon at the top-right and selecting Settings & billing. You'll see a tab for API key. Click "Generate key" to receive a public API key, private API key and account ID. If you have an agency account, go to Menu, choose Accounts, click the downward-pointing arrow to the right of the sub-account whose keys you want and select to jump into that account's settings. Scroll down to the API key section.

2. Go to your WordPress account, select Plugins from the lefthand column and click Settings below Emma for WordPress. Copy your account ID, public API key and private API key into the corresponding fields on the plugin's Account Information tab. This allows it to connect your WordPress site to your Emma account. You can select here whether to send a confirmation email. An optional field lets you assign all subscribers to a specific group in your Emma account.

3. Go to the Form Setup tab. Here, you'll choose which fields to display, set your form's width, customize the placeholder messages within each field, customize the messages that displays under the form after it has been submitted and select whether or not to send a confirmation email, then specify the subject and message of the confirmation email. Click Save.

4. Go to the Form Customization tab. This is where you will select how you would like the form to be displayed on your site (vertical or horizontal), customize the styles of your form fields (including border width, border color, border type, text color and background color), customize the styles of your form’s submit button (including width, text color, background color, border width, border color and border type) and customize the styles of your form's submit button when users hover on it. Click Save.

5. Go to the Advanced Settings tab. This is where you'll add tracking code to each signup form submission. Paste your code into the box, and click Save.

= reCAPTCHA Settings =

1. Go to the reCAPTCHA settings Note: reCAPTCHA is disabled by default
2. Once reCAPTCHA is checked, boxes appear to insert the Google reCAPTCHA keys.
3. Once code is inserted and saved, a preview box will appear
4. ReCaptcha is now implemented on all of forms.

= Front End of Site Implementation =

1. User fills out Emma sign up form, on submit a lightbox appears 
2. Once  “I’m not a robot” is checked, the form information is sent to Emma and confirmation message appears 

== Screenshots ==

1. This is the Account Information tab of the plugin settings, here you enter your account keys and account number, then select the group you wish to add members to.
2. This is the Form Setup tab of the plugin settings, here you configure the form's output on your site, you can also choose to add a stylish emma logo to your form, and share some love.
3. This is the Form Customization tab of the plugin settings, here you can style your form, choose colors, border types, and so on.
4. This is the Advanced Settings tab of the plugin settings, here you can add a tracking pixel to be placed after a successful submission.
5. This is the Help tab of the plugin settings, it contains instructions on how to get up and running with your new Emma for WordPress Plugin.

== Frequently Asked Questions ==

= How do I put the form on my website? =
   
   Once you have activated the plugin, setup your Emma account to work with their new API, and configured your Emma for Wordpress Settings, you have two options for adding the form to your site. 

   **Widget**
   If you would like to add the form to a widget area, navigate to Appearance->Widgets. This plugin comes with a widget called 'Emma for Wordpress Subscription Form'. The Widgets are listed in alphabetical order. 

   **Shortcode**
   If you would like to add the form to another area on your site, you can use the Emma shortcode. Simply type [emma_form] in the HTML view in the post editor. For more information on shortcodes, check the almighty codex: [Shortcodes](http://codex.wordpress.org/Shortcode" WordPress Codex - Shortcodes").

= How do I add members to a specific group? =
   
   Once you've configured the plugin, and entered your account ID, Private and Public API keys, navigate from the Dashboard to Settings -> Emma for Wordpress. Under the “Account information” tab there is a dropdown menu under “Add New Members to Group.” From here, you can select the group to which new members should be added.

= Can I set up an automated workflow using my WordPress form? =
   
   You sure can. You can have an email send when:
       
       1. someone subscribes to a specific group from a specific signup form (both configured on the WordPress side)
	   
	   2. someone subscribes to a specific group from any signup form (also both configured on the WordPress side)

	   3. someone subscribes to any group from any signup form

	Scenarios that won't work are when:

	   1. no group is specified in your WordPress plugin (that field isn't required, though we recommend specifying a group so your contacts are added where you can easily send to them)
	   
	   2. you don't have any groups in your account (which won't allow you to activate the plugin)

== Upgrade Notice ==

1. This Plugin requires Wordpress version 3.0 and above
2. This Plugin requires PHP version 5.2.6, as it uses json_encode with integers in the Emma_API class

== Changelog ==

= 1.3.3 =
* Fixed error on form submission on sites using https protocol.

= 1.3.2 =
* Reworked our reCaptcha integration to help prevent even more spam

= 1.3.1 =
* Added a honeypot to your forms to help prevent spam

= 1.3 =
* Added ability to use reCaptcha on your forms

= 1.2.4.2 =
* A couple URL changes

= 1.2.4.1 =
* Fixed a typo!

= 1.2.4 =
* Fixed bug where admin pointer would not dismiss

= 1.2.3 =
* Added information on how to serve you’re Emma subscribers the latest posts or customized content from WordPress.
* Fixed PHP "Undefined Index" notice

= 1.2.2 =
* Updated for WordPress 4.3 compatibility (switched to PHP5 style constructor for the widget)

= 1.2.1 =
* Added missing file to repository.
* Added screenshots.
* Updated contributors.

= 1.2.0 =
* Added 'Advanced Settings' tab that allows a tracking pixel to be loaded upon successful submission.
* Made the Signup ID an optional parameter.
* Cleaned up some PHP Notices.
* Updates to documentation.

= 1.1.2 =
* Fixed bug that was causing automation to fail in some instances.
* Updates to documentation.

= 1.1.1 =
* Updated screenshots and readme.txt

= 1.1 =
* Updated plugin to work with Emma's newest API. Lots of minor tweaks/updates.
* Now works with email automation!
* Better integration with the API.  Cleaner, more robust code with responsive default options.  
* Bug fix: All members data is now added to Emma list.  Member signup and add member parameters were revised.

= 1.0.5 =
* added confirmation email message, nomenclature updates, Emma_API class fixes, relegated error handling to object making the call, Emma_API fits the adapter pattern better now. more bugfixes.

= 1.0.4 =
* bugfixes, updated readme.txt

= 1.0.3 =
* fixed accidental php short tag, ( tyty @avioli ), updated readme.txt, spelling errors, and nomenclature updates.

= 1.0.2 =
* cleaned up OOP structure. switched to WP naming conventions, fixed bug where users weren't being assigned to groups,

= 1.0.1 =
* typed active group_id as integer for uptake to Emma. Emma required group_ids submitted as an array of integers. in older versions of PHP json_encode types integers as strings.

= 1.0 =
* it's stable. It needs some cleaning, but it flies, and flies well.
