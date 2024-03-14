=== Formstack Online Forms ===
Contributors: mmattax, noahwesley, jeremyformstack, brianFormstack
Donate link: https://www.formstack.com
Tags: form builder, forms, lead generation, online forms, web forms, surveys, quizzes
Requires at least: 2.8
Tested up to: 5.1.1
Stable tag: 2.0.2

This plugin allows you to easily embed Web forms built with Formstack's online form builder
into your sidebar, pages, and posts.

== Description ==

Formstack’s [WordPress form plugin](https://www.formstack.com/integrations/wordpress?utm_source=wordpress.com&utm_medium=referral&utm_campaign=integrations) makes it quick and easy to embed contact forms, lead generation forms, payment forms, and more on your WordPress blogs and websites. Build customized online forms in seconds with our [drag-and-drop interface](https://www.formstack.com/features/drag-and-drop?utm_source=wordpress.com&utm_medium=referral&utm_campaign=integrations), and integrate with [40+ third-party apps](https://www.formstack.com/integrations?utm_source=wordpress.com&utm_medium=referral&utm_campaign=integrations), including Salesforce, MailChimp, and PayPal.

This plugin features two components:

*    Formstack Widget
*    Formstack Plugin

The **Formstack widget** allows you to embed Formstack web forms into your sidebar. The widget automatically optimizes the web form’s CSS to make your online forms fit and look great on your WordPress pages.

The **Formstack Plugin** adds a button to the TinyMCE editor that allows you to easily select the Formstack web form you wish to embed. Once a form is selected, a shortcode will be inserted into the editor, which will be converted to the selected form once your page or blog post is rendered.

This plugin supports the following shortcodes `[Formstack]`, `[formstack]`, and `[fs]`.

A Formstack account and API key are required. [Signup for free today!](https://www.formstack.com/pricing?source=wp-plugin_utm_medium=pluginlisting "Free Online Forms")

== Installation ==

1. Download the plugin zip file by clicking “Download” in the upper right corner of the page.
2. Upload Formstack.zip via the Upload link in the WordPress plugins dashboard.
3. Activate the Formstack Plugin through the “Plugins” menu in WordPress.

== Frequently Asked Questions ==

= Where can I find my Formstack API key? =

Your Formstack API key can be found in your account settings. Go here
to find or create your API key: [https://www.formstack.com/admin/apiKey/main](https://www.formstack.com/admin/apiKey/main).

== Changelog ==

= 2.0.2 =
* Fixed: Errors with unauthenticated accounts and trying to use API in widget.

= 2.0.1 =
* Fixed: Adjusted timeout value to 120seconds for API requests to accommodate for large responses.

= 2.0.0 =
* Added: Embed options when choosing a form. Conditionally include jQuery, jQueryUI, Modernizr, and Formstack-provided CSS.
* Added: options to clear/refresh cached Formstack lists in your WordPress admin.
* Updated: Make the plugin use Formstack API V2 API over the older V1.
* Updated: Removed the embedding of Formstack account admins within WordPress admin page.

= 1.0.13 =
* Increased timeout limit for API requests.
* Conditionally loading content meant for TinyMCE.
* Added caching for API requests. Will especially help those who have large amounts of lists.
* Improved handling of cases where API requests return no lists.

= 1.0.12 =
* Fixes js bug with empty forms array when inserting form into post.

= 1.0.11 =
* Reworked and updated plugin to current WordPress standards
* Confirmed compatibility with latest WordPress versions.

= 1.0.10 =
* Update compatibility reference for 4.2

= 1.0.9 =
* Update compatibility reference for 4.1

= 1.0.8 =
* Resolve issues with the Widget in Wordpress 3.9
* Resolve issues with Widget when no API Key is saved for the Plugin

= 1.0.7 =
* Fix incompatibilities with Worpdress 3.9

= 1.0.6 =
* Formstack side-menu now properly links to appropriate Formstack
* functionality, no longer hardcoding embedded forms's version

= 1.0.5 =
* Formstack side-menu now defaults to the bottom (instead of possibly over-writing an existing menu).

= 1.0.4 =
* Increased functionality, build forms within Wordpress, better error messages

= 1.0.3 =
* Added PHP version to the options page for easier troubleshooting.

= 1.0.2 =
* Added Formstack API status area on the plugin settings page.

= 1.0.1 =
* Now using wp_remote_fopen for improved server compatibility. Should fix errors some people were having when loading the widgets page.
* Minor housekeeping

= 1.0.0 =
* Hello World

== Upgrade Notice ==

= 2.0.2 =
* Fixed: Errors with unauthenticated accounts and trying to use API in widget.

= 2.0.1 =
* Fixed: Adjusted timeout value to 120seconds for API requests to accommodate for large responses.

= 2.0.0 =
* Added: Embed options when choosing a form. Conditionally include jQuery, jQueryUI, Modernizr, and Formstack-provided CSS.
* Added: options to clear/refresh cached Formstack lists in your WordPress admin.
* Updated: Make the plugin use Formstack API V2 API over the older V1.
* Updated: Removed the embedding of Formstack account admins within WordPress admin page.

= 1.0.13 =
* Increased timeout limit for API requests.
* Conditionally loading content meant for TinyMCE.
* Added caching for API requests. Will especially help those who have large amounts of lists.
* Improved handling of cases where API requests return no lists.

= 1.0.12 =
* Fixes js bug with empty forms array when inserting form into post.

= 1.0.11 =
* Reworked and updated plugin to current WordPress standards
* Confirmed compatibility with latest WordPress versions.

= 1.0.10 =
* Update compatibility reference for 4.2

= 1.0.9 =
* Update compatibility reference for 4.1

= 1.0.8 =
* Resolve issues with the Widget in Wordpress 3.9
* Resolve issues with Widget when no API Key is saved for the Plugin

= 1.0.7 =
* Fix incompatibilities with Worpdress 3.9

= 1.0.6 =
Fix Forms link and removed broken Submissions link. Forms embeeded through
this plugig are no longer hard-coded to -v2.

= 1.0.4 =
Significant upgrade to the plugin. Should resolve many problems experienced when inserting a form in a page/post.

= 1.0.2 =
Fix for servers that don't support CURL. Upgrade if you are seeing error messages.
