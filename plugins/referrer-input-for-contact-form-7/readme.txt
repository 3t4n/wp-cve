=== Referrer Input for Contact Form 7 ===
Contributors: damiarita
Tags: css, javascript, jQuery, contact form 7, referrer, 
Stable tag: trunk
Requires at least: 1.4.0
Tested up to: 4.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Contact Form 7 Addon that creates a cache-resistant input that contains the URL of the page the user visited before the contact form page.

== Description ==

Addon for Contact Form 7 that creates a new kind of input that contains the URL of the page the user visited before the contact form page. This plugin works well with cache plugins, because the field is filled using javascript on the user's computer in stead of the server.

You can choose where in your email to introduce this info by using a mail-tag, like any other field.

= How to use it? =

Once you have installed and activated your plugin, a new type of field will be available in your Contact Form 7 forms. In order to add it to your form, you can either click on the "Referrer" button above your form editor, or add the shortcode like: [cf7rfr_referrer {your-referrer}] ({your-referrer} has to be replaced by the name you want to give the field)

To recover the field's info on your email, use this tag: [{your-referrer}]. It will print a URL.

= What referrer? =

This plugin looks at the HTTP referrer. Not at the traffic source. So, if your user comes from google, clicks some links in your site and ends up in your contact form page, the field will contain the last page in your site your client visited. It will not tell you whether the client landed from Google. This is meant to give you some context to the user's message. Sometimes, they say "I love this product!" but you have no idea which one they are talking about. With the referrer field, at least you know what they saw last.

== Installation ==

0. Install Contact Form 7 from wordpress.org/plugins/contact-form-7/
1. On the left side menu select Plugins > Add New
2. In "Search Plugins" field enter "Referrer Input for Contact Form 7" and search
3. Press "Install Now" button

== Frequently Asked Questions ==


== Screenshots ==


== Changelog ==

= 1.0.1 =
* We change to new CF7 functions naming (shortcode->form-tag) to avoid deprecated message with debug active. No new features added

= 1.0.0 =
* First release


== Upgrade Notice ==

= 1.0.1 =
* We change to new CF7 functions naming (shortcode->form-tag) to avoid deprecated message with debug active. No new features added

= 1.0.0 =
* First Release
