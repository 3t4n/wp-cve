
=== Post Status Notifier Lite ===
Tags: post, status, notification, notify, change, custom post type, email, log, logging, notify, placeholders,  transition
Contributors: worschtebrot
Author: Timo Reith
Author URI: http://www.ifeelweb.de
Requires at least: 3.3
Tested up to: 6.4
Stable tag: 1.11.1
Requires PHP: 7.4

Lets you create individual notification rules to be informed about all post status transitions of your blog. Features custom email texts with many placeholders and custom post types.

== Description ==

= Notify every WordPress post change! =

You want to **be notified** when one of your contributors have submitted a new post for revision or an editor published one? Vice versa you want to **notify your contributors** when their posts got published?
This is just the beginning of what you can achieve with Post Status Notifier (PSN)!

It works with all kind of **custom post types**, supports all **custom taxonomies** like categories and tags other plugins are using. You can grab all these taxonomy values and custom fields attached to a post and use them as **placeholders** in your custom notification texts. PSN has a powerful **conditional template syntax** featuring many filters and functions to get the most out of the placeholders!

Define as many notification rules as you need with all kind of settings, like custom **CC**, **BCC** and **FROM** emails addresses. PSN is **extensible**! Build your custom module to implement a new notification service.

PSN works great with plugins like **WP Job Manager** ([read more](http://www.ifeelweb.de/2014/666/notify-wp-job-manager-listings-wordpress-plugin-post-status-notifier/)), Calendarize.it ([read more](http://www.ifeelweb.de/2014/748/notify-calendarize-events-wordpress-plugin-post-status-notifier/)), **Crowdfunding by Astoundify** ([read more](http://www.ifeelweb.de/2014/706/notify-payments-crowdfunding-astoundify-post-status-notifier/)) or **Advanced Custom Fields**, just to name a few. The possibilities are endless. Want to **automate your publishing workflow** with [Buffer](http://bufferapp.com/)? No problem!

Plugin homepage:
http://www.ifeelweb.de/wp-plugins/post-status-notifier/

Always up-to-date online documentation:
http://docs.ifeelweb.de/post-status-notifier/

FAQ:
http://docs.ifeelweb.de/post-status-notifier/faq.html

= Features =

Get the [Premium version](http://codecanyon.net/item/post-status-notifier/4809420?ref=ifeelweb) for all features

* Define **custom notification rules**
* Support for posts, pages and all **custom post types**
* Support for **all post status** values
* Create **custom email texts** with support for many placeholders
* Manipulate placeholders content with **filters** to completely adjust the output to your needs (uses the filters of the famous PHP template engine Twig / limited to one filter in the Lite version)
* WordPress **multisite compatible**
* Premium version: [HTML emails / mail templates](http://docs.ifeelweb.de/post-status-notifier/mail_templates.html)
* Premium version: [Mail queue / deferred sending](http://docs.ifeelweb.de/post-status-notifier/mailqueue.html)
* Premium version: Categories filter: Include or exclude categories (even from custom post types) from notifications
* Premium version: Supports **SMTP**. You find all necessary SMTP settings to connect your SMTP server in the options section.
* Premium version: Supports **user roles** (custom roles too) as email recipients
* Premium version: Optional **logging**: Logs status changes based on your rules
* Premium version: **Dashboard widget** showing the latest log entries (can be disabled)
* Premium version: **Import / Export** of your notification rules
* Premium version: **Copy** rules
* Premium version: **Conditional template syntax** ([Manual](http://docs.ifeelweb.de/post-status-notifier/conditional_templates.html))
* Premium version: Extensible ([Manual](http://docs.ifeelweb.de/post-status-notifier/extending_index.html))
* Premium version: Custom sender e-mail. Define the notification sender (**FROM**) per rule or as a default in the options.
* Premium version: Mandrill support
* Premium version: [Dynamic recipients](http://docs.ifeelweb.de/post-status-notifier/dynamic_recipients.html)
* Premium version: [Late execution](http://docs.ifeelweb.de/post-status-notifier/options.html#late-execution) to support frontend submission plugins as well as possible
* Premium version: [Rule trigger limitations](http://docs.ifeelweb.de/post-status-notifier/limitations.html)
* Comprehensive **documentation**
* Included **translations**: english, german
* **Support** in english and german via Zendesk: ifeelwebde.zendesk.com
* Tested on Windows, Mac OS and Linux
* Built on our ifeelweb.de WordPress Plugin Framework
* The Lite version features two notification rules and one CC email


= What customers say =

**"Great plugin, look through maybe 7 plugins until found this one and it is the best."**
- misolek

**"just got the pro version and it’s working great, awesome plugin man and thanks for your excellent support"**
- nomadone

"This plugin is very intuitive and works great. Very helpful support. Top notch!"
- Rick

**"Thank you for your great support – the plugin works great now and has accomplished what 5 other commercial and free plugins couldn’t – to provide simple and configurable email notifications for WP status changes."**
- Jon

**"just got the pro version and it’s working great, awesome plugin man and thanks for your excellent support"**
- nomadone

[Comment-Source](http://codecanyon.net/item/post-status-notifier/discussion/4809420)


== Installation ==

Just unpack the `post-status-notifier-lite` folder into your plugins directory and activate it on your wordpress plugins page.
Then you will have the option `Post Status Notifier Lite` on your wordpress options page.


== Configuration ==

Go to the new option page `Post Status Notifier Lite`. Here you can define custom notification rules.

Here you can find a detailed documentation:

http://docs.ifeelweb.de/post-status-notifier/

== Change Log ==

= 1.11.1
- Fixed: The exclude filter did not work as expected (#191)
- Fixed: An unhandled exception which could occur during certain WordPress events and not in general (#194)

= 1.11.0
- Added: Support for e-mail attachments (https://docs.ifeelweb.de/post-status-notifier/rules.html#attachment) (Premium)
- Changed: Completely redesigned options section
- Fixed: Items per page option did not work in list table screen options
- Fixed: Translation issues

= 1.10.2 =
- Fixed: Minor Cross-Site Scripting issue

= 1.10.1 =
- Added: Option page layout issue
- Fixed: Minor Cross-Site Scripting issue

= 1.10.0 =
- Added: Option to define how to handle shortcodes in mail templates (see "Options > Mail Templates") (Premium)
- Fixed: Translation

= 1.9.10 =
- Fixed: PHP 8.0, 8.1 compatibility

= 1.9.9 =
- Added: Advanced option "Postponed execution" which might help if rules don't work as expected when creating posts via frontend
- Added: Option "Identical emails threshold" which defines a time period in seconds within which PSN will attempt to block multiple identical emails.
- Fixed: Translation issues

= 1.9.8 =
- Fixed: Another PHPMailer issue

= 1.9.7 =
- Fixed: PHPMailer issue

= 1.9.6 =
- Added: Reply-To support for email notifications (Premium)
- Fixed: PHP 7.4 issues
- Fixed: Compatibility with WordPress 5.5
- Fixed: Translation issues
- Fixed: Minor layout issues

= 1.9.5 =
- Changed: Options page layout improved (#PSN-29)
- Fixed: Support for ACF field type with non scalar values, like "Select", "Post Object" and others
- Fixed: Removed PHP Notices
- Fixed: Issue with submit form of plugin "WP User Frontend" (#PSN-27)

= 1.9.4 =
- Added: Button to save and stay on page for mail template form
- Fixed: Bulk actions did not work anymore
- Fixed: PHP 7.x improvements
- Fixed: Some database tables were not removed when the plugin was deleted

= 1.9.3 =
- Fixed: Error on plugin activation

= 1.9.2 =
- Fixed: Some PSN database tables have been created with type MyISAM. This has been removed to create default type which will be InnoDB in most cases
- Fixed: The initialization procedure of the plugin has been improved

= 1.9.1 =
- Fixed: Improved error reporting (PHP notices were displayed on PSN admin pages)

= 1.9.0 =
- Added: Support for ACF custom fields improved
- Added: New status "Not scheduled"
- Added: New rule condition: Post ID whitelist / blacklist
- Added: New rule condition: Exclude recipients by user ID or email address
- Added: Custom rule match condition based on template syntax
- Fixed: Rule match handling improved

= 1.8.9 =
- Fixed: Compatibility fix for PHP 7
- Fixed: Minor fixes

= 1.8.8 =

- Added: New placeholders for post title and content diffs: [post_diff_title], [post_diff_content] (http://docs.ifeelweb.de/post-status-notifier/content_diffs.html) (Premium)
- Fixed: Compatibility fix for PHP 7

= 1.8.7 =

- Added: New placeholder when running inside TO loop: [recipient_ID] (Premium)
- Added: New placeholder when running inside TO loop: [recipient_user_email] (Premium)
- Changed: Improved SMTP debug mode to not produce output in AJAX requests (Premium)
- Changed: HTML emails editor update (Premium)
- Fixed: On some hosters it could lead to issues with PHP open_basedir setting

= 1.8.6 =

- Bugfix: Whitespace in a script file caused PSN admin pages to result in an error after form submits
- Bugfix: Passing emails to Mandrill could result in errors if custom FROM was used

= 1.8.5 =

- Added: New rule option "Exclude current user" with which you can exclude the current user who saves / updates the post from all recipients
- Changed: License handling on multisite installations. Go to network settings / Post Status Notifier to activate the license network wide (Premium)
- Changed: License form moved to a dedicated tab section on default (non multisite) installations (Premium)
- Bugfix: Placeholders [post_status_before] and [post_status_after] could be empty
- Bugfix: Custom FROM set in the option could lead to problems if format "Sender Name <sender@domain.com>" was used

= 1.8.4 =

- New placeholders: [post_categories_slugs], [post_categories_slugs_array], [post_tags_slugs], [post_tags_slugs_array] (thanks to syntax53 for the proposal)
- Tweak: On multisite update, the PHP maximum execution timeout will be resetted to the default 30 seconds for every site to not run into a timeout with very large networks
- Security fixes
- Tweak: Category handling improved: special characters in category names could not be matched
- Tweak: Frontend post submission improved: Category filter did not work for posts submitted via frontend
- Minor fixes

= 1.8 =

- New feature: Rule trigger limitations (Premium)
- New feature: Dynamic recipients (Premium)
- New feature: Late execution (Premium)
- New feature: Mandrill support (Premium)
- Redesigned options page for better overview

= 1.7 =

- New feature: Mail Queue (Deferred sending)
- Improved logging: Shows detailed email contents now, including HTML mails
- Bugfix: Placeholder [post_editlink] could not be replaced in case of users without edit rights changed the post status (e.g. if the permission exceeded in the meantime but he still gets emails)
- Bugfix: Mail template HTML editor produced an JS error when opened in edit mode in Firefox

= 1.6.3 =

- Bugfix: Service section environment info metabox could break in certain cases
- Bugfix: FROM was empty if no custom FROM was set
- Improvement: Rule and mail template export could break when other plugins interfered via filters
- Fix: Rule placeholder help screen showed "post_featured_image_src" which should be "post_featured_image_url"

= 1.6.2 =

- Bugfix in custom tag handling

= 1.6.1 =

- Major improvements for the support of Categories and Tags.
- Major improvements for the support of Custom Fields
- Major improvements for the support of Scheduled Posts.

= 1.6 =

- New placeholder: [post_categories_array] Contains an array for easy use with filters
- New placeholder: [post_tags_array] Contains an array for easy use with filters
- New placeholder: [post_custom_fields_array] Contains an array for easy use with filters
- New placeholder: [post_preview_25] Contains the first 25 words of the post content
- New placeholder: [post_preview_50] Contains the first 50 words of the post content
- New placeholder: [post_preview_75] Contains the first 75 words of the post content
- New placeholder: [post_preview_100] Contains the first 100 words of the post content
- New placeholder: [post_content_strip_tags] The post content without HTML tags
- New placeholder: [post_featured_image_url] If a post has a featured image, this placeholders contains its URL
- New placeholder: [post_featured_image_width] The featured image width
- New placeholder: [post_featured_image_height] The featured image height
- New placeholder: [recipient_first_name] Only works in "One email per TO recipient" mode. The firstname of the recipient if it is available in the user profile.
- New placeholder: [recipient_last_name] Only works in "One email per TO recipient" mode. The lastname of the recipient if it is available in the user profile.
- New custom post status: "Not trash" will match every status but "Trash"
- New Premium feature: Support for conditions, loops, functions and filters in subject and body texts. Enables to access any kind of data attached to a post. Allows to create dynamic texts.
- New Premium feature: Block notifications options in Post submit box. Lets you decide to completely block notifications before you update / create a post
- New Premium feature: One email per TO recipient. Notifications can get send in a loop with one email per TO recipient disregarding CC and BCC recipients. This feature is has Beta status.

= 1.5.1 =

- Improvement: Duplicate recipients get removed
- Bugfix: Fixed a bug in the Logger module (Sent emails haven't been logged correctly)

= 1.5 =

- New feature: HTML mail support and email templates. Prepare your email templates once and select them for different notification rules.
- New feature: Auto-update via WordPress backend. Never have to upload the files via FTP again. You have to enter your license code in the plugin's settings.
- New feature: More flexible To, Cc, Bcc selection. Multiple selections are possible now.
- New feature: Editor restriction. Select one or more roles the editor of a post must be member of so that the notification will be generated.
- New feature: Recipients lists. Manage email addresses without the need to create user accounts.
- New custom post status "Not pending": Matches all post status values except "pending"
- New custom post status "Not private": Matches all post status values except "private"
- New placeholder [post_editlink]: Contains the backend edit URL
- Removed post types "attachement", "nav_menu_item" from rule settings as they are not treated like post types (have no status before/after)
- Support for placeholders in FROM
- Refactoring for percormance improvements

= 1.4 =

- New custom placeholders which will specifically match custom categories and tags registered with your blog.
- New dynamic placeholders: You will be able to fetch every custom field attached with your posts.
- New feature: Placeholder filters. This is a very powerful feature. You can use all filters of the famous PHP template engine Twig to manipulate the output of all placeholders PSN offers you, including the new dynamic placeholders. (Limited to 1 filter in Lite version)
- New feature: Import / Export notification rules (Premium)
- New feature: Copy notification rules (Premium)
- New feature: New recipient type "Individual e-mail". Enter a custom e-mail address as main recipient (TO).
- New feature: Custom sender e-mail. Define the notification sender per rule or as a default in the options. (Premium)
- New notification rule status "Not published". This will match every post status but "publish".
- New placeholder: [post_format]

= 1.3 =

- New feature: Notification rules have a categories filter now
- New placeholder: [post_permalink] can be used for notification texts. Contains the post's permalink (uses WP internal get_permalink function)
- Bugfix: Fixed a bug which occured when not logged in users changed post status in the frontend
- Bugfix: German language fix
- Improvement: Backend adjusted to new WordPress 3.8 layout

= 1.2.1 =

- Bugfix: Fixed a bug where scheduled items did not get notified when published by cron

= 1.2 =

* New feature: Notification rule recipient supports user roles (default and custom roles) and special all users
* Improvement: PSN now is completely multisite compatible
* Bugfix: Single quotes in blog name will be shown correctly now

= 1.1 =

* New feature: Bcc field. Set Bcc recipients for your notification rules.
* New feature: SMTP mode. If you want to send many notifications and have a SMTP mail server, PSN now supports it. You find all necessary SMTP settings in the options section.
* New feature: Plugin selftester. The plugin ships with some selftesting routines you can trigger manually in the plugin dashboard.
* Minor bugfixing: Now fully compatible with Windows Server 2008 / PHP 5.2

= 1.0.5 =

* Bugfix in date/time calculation (PHP5.2)

= 1.0.4 =

* Minor bugfixes

= 1.0.3 =

* Further improvements: Removed dependency to PDO at all

= 1.0.2 =

* Bugfix: Recipient “Post author” did not work for notification rules
* Bugfix: Plugin activation could produce error with PHP 5.2 (Parse error: syntax error, unexpected T_PAAMAYIM_NEKUDOTAYIM …)

= 1.0.1 =

* Removed dependency to PHP pdo_mysql (framework database models now work with native wpdb object)
* Improved backwards compatibility up to WP 3.3 (tested on 3.3.x / 3.4.x / 3.5.x)
* Adjusted log timestamp format to blog date/time settings


== Info ==

If you find any bugs please use the comments on the [plugin's homepage](http://www.ifeelweb.de/contact/). Please also contact me for feature requests and ideas how to improve this plugin. Any other reactions are welcome too of course.

== Frequently Asked Questions ==



== Screenshots ==

1. Completely customizable notification rules
2. Just a few use cases
3. Overview some options panels (available in the premium version)
4. HTML email (available in the premium version)
5. Placeholders
6. Template syntax (available in the premium version)
7. Logger (available in the premium version)
8. PSN's selftester
9. Comprehensive documentation

