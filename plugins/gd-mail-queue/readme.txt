=== GD Mail Queue ===
Contributors: GDragoN
Donate link: https://plugins.dev4press.com/gd-mail-queue/
Version: 4.2.1
Tags: dev4press, mail, queue, email log, smtp, html email, bbpress, mail log, wp_mail
Requires at least: 5.5
Tested up to: 6.2
Requires PHP: 7.3
Stable tag: trunk
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Intercept emails sent with wp_mail and implements a flexible mail queue system for sending emails, converting plain text emails to HTML with templates customization, email log, and more.

== Description ==
The plugin adds a mail queue system that can intercept all the emails sent through the `wp_mail` function, and depending on the predefined rules (number of recipients), sent the email into the queue, with each recipient getting their email. On top of that, the plugin can process all plain text emails and wrap them in the HTML, and send them as HTML, with options to customize the template and various other aspects.

= How the plugin works =

The central part of the plugin, called 'Mailer,' controls the process.

* Intercept every `wp_mail` call
* If needed, wrap email in HTML
* Determine the number of recipients
* If eligible, add each recipient email into the queue

Process of turning plain text into HTML emails, support all emails sent through wp_mail, even if they don't end in the queue. This can turn all WordPress system emails (registration, password reset...) into HTML emails and you can choose between predefined HTML templates, or register own templates via filter.

Queue works through the CRON, as a background job, and you can configure how many emails to send in a batch, with the timeout setting to prevent PHP timeout breaking the sending process.

= Email Sending =
Emails are sent using PHPMailer built into WordPress. But, the plugin has additional options to control the queue sending process and the wp_mail sending process.

* Email sending engine based on PHPMailer class
* PHPMailer options to use SMTP for sending emails
* Customize email From for all emails passed through `wp_mail`
* Customize email From, Reply, and Sender for all emails sent through the queue

= Email Logging =
The plugin includes an advanced email log.

* Custom database tables to store logs, made for performance
* Store all relevant data for each email, including a list of attachments
* Email log panel with filters for logged emails
* Preview popup displaying all logged email details
* Preview popup shows the full HTML email part preview
* Retry sending any failed emails through the queue

= Security related features =
The plugin will attempt to strip malicious content from intercepted email, and sanitize the email plain text before generating HTML version, with the option to control the scope of the HTML tags to allow. HTML from subject will be striped. Emails stored in the log will be additionally sanitized, and on display, email content will be again escaped or run through KSES to avoid issues.

= Other plugin features =

* Options to pause wp_mail and queue operations
* Dashboard: an overview of the queue (including the last run) statistics
* Dashboard: an overview of the overall mailer statistics
* Dashboard: additional boxes with various other information
* Automatic cleanup of all successfully sent and/or failed emails
* Logging of the errors for each email send through the queue
* Developers friendly with various actions and filters for extra control
* Support for intercepting bbPress subscription notifications emails
* Support for the BuddyPress mail system
* Option to set BuddyPress to use WordPress wp_mail() function
* Track email types from emails sent by WordPress, bbPress and more
* Tools to test sending of emails and adding to the queue

= Upgrade to GD Mail Queue Pro =
Pro version contains many more great features:

* REST API based email sending engines
* REST API Engine: SendGrid
* REST API Engine: Amazon Web Services SES (through free addon)
* REST API Engine: Gmail (through free addon)
* REST API Engine: Mailgun (through free addon)
* REST API Engine: Mailjet (through free addon)
* REST API Engine: SendInBlue (through free addon)
* PHPMailer third-party SMTP services support
* PHPMailer SMTP Service: Amazon Web Services SES
* PHPMailer SMTP Service: Mailgun
* PHPMailer SMTP Service: Mailjet
* PHPMailer SMTP Service: Mandrill
* PHPMailer SMTP Service: PostMark
* PHPMailer SMTP Service: PepiPost
* PHPMailer SMTP Service: SendGrid
* PHPMailer SMTP Service: SendInBlue
* PHPMailer SMTP Service: SendPulse
* PHPMailer SMTP Service: SparkPost
* Panel to show everything currently in queue
* Email Notifications with daily and weekly statistics
* Email Notifications with daily and weekly errors logged
* Safe staging support with email redirection
* HTMLfy support for uploading logos
* Improved dashboard with various control buttons
* Improved log with the email role-based filtering
* Tool to preview HTML template

With more features on the roadmap exclusively for the Pro version.

* More information about [GD Mail Queue Pro](https://plugins.dev4press.com/gd-mail-queue/)
* Compare [Free vs. Pro Plugin](https://plugins.dev4press.com/gd-mail-queue/articles/lite-vs-pro/)

= More Information and Support =
* More information about [GD Mail Queue](https://plugins.dev4press.com/gd-mail-queue/)
* Support and Knowledge Base for [GD Mail Queue](https://support.dev4press.com/kb/product/gd-mail-queue/)

= Important =
* The plugin only works with the default WordPress `wp_mail` function that uses the `PHPMailer` object.
* The plugin doesn't replace `wp_mail` or PHPMailer and uses default function and class built into WordPress.
* It is not advisable to use this plugin and some other plugin that manipulates WordPress `PHPMailer` object.
* Plugin doesn't support plugins replacing the `wp_mail` function (Sendgrid, WP Offload SES, and similar).

== Installation ==
= General Requirements =
* PHP: 7.3 or newer
* Plugin doesn't work with PHP 7.2 or older versions.

= WordPress Requirements =
* WordPress: 5.5 or newer
* Plugin doesn't work with WordPress 5.4 or older versions.

= Basic Installation =
* Plugin folder in the WordPress plugins folder must be `gd-mail-queue`.
* Upload `gd-mail-queue` folder to the `/wp-content/plugins/` directory.
* Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==
= Does the plugin works with WordPress MultiSite installations? =
Yes. Each website can be set up to use the mail queue.

= Where can I configure the plugin? =
The plugin has its top-level item in the WordPress admin side menu: GD Mail Queue. This will open a panel with a dashboard, settings, and tools.

= How can I use the email log? =
When the plugin is installed, the email log is disabled. To enable it, open plugin Settings -> Log panel and enable it from there.

= Does the plugin support BuddyPress? =
Yes. If you use the BuddyPress HTML emails feature, it will work as expected, and GD Mail Queue will handle these emails without turning them into HTML. You can also use an option in GD Mail Queue to force BuddyPress to send plain text emails, and GD Mail Queue will turn them into HTML emails.

= Does the plugin support Contact Form 7? =
Yes (and no). The plugin can detect and intercept CF7 emails. If your CF7 form contains attachments that have to be sent, GD Mail Queue can intercept the email, but attachments will be gone because the CF7 plugin is deleting attachments as soon as it sends an email, so queued email can't find the attachments to send. If you don't use attachments in the CF7 plugin, GD Mail Queue will work fine. If you use CF7 with attachments, make sure to disable GD Mail Queue intercept to queue for these emails. Logging and other features in GD Mail Queue work fine with CF7 email. To stop intercept for specific mail types, check out this: [Filter: gdmaq_mailer_add_to_queue](https://support.dev4press.com/kb/reference/gdmaq_mailer_add_to_queue/).

= Does the plugin supports other SMTP sending plugins? =
No. GD Mail Queue has support for SMTP email sending, and you can add your SMTP server information for the plugin to use for all emails and/or queue emails. If you need to use REST API for email sending or want to use predefined SMTP sending services through PHPMailer, GD Mail Queue Pro supports several popular email services using official REST API libraries and SMTP sending to.

= Does the queue supports sending emails with attachments? =
Yes. But, because the queue sends emails with the delay, the attachments must be available to ship at any time. So, if the email sends temporary attachments removed by the sending function as soon as they are passed to the wp_mail() function, queue will still be able to send email, but attachments will not be sent if they are missing in the moment of the queue sending.

= Does the plugin supports REST API-based sending plugins? =
No. All plugins that send emails to various services using REST API or some other API to connect to services always replace the wp_mail() function, making it impossible for my plugin to detect emails and work with them. GD Mail Queue can't use third-party plugins to send emails because there is no universal interface for all plugins to implement and use.

= Does this plugin replace wp_mail() function? =
No. Most email sending plugins start by replacing the wp_mail() function in WordPress, making it hard for other plugins to do anything related to email sending. GD Mail Queue plugin doesn't replace this function (or any other WordPress function), and it is relying on that core function to intercept and determine if the email should be turned in HTML or added to the queue.

= Can I specify the SMTP server for sending queue emails? =
Yes. Settings for that are available on the PHPMailer Settings panel once you switch Mode to Custom SMTP Server.

= Can I add my own HTML templates? =
Yes. To get more information on registering additional templates, check out the knowledge base article: [Additional HTML templates](https://support.dev4press.com/kb/article/additional-html-templates/).

= Can I translate the plugin to my language? =
Yes. The POT file is provided as a base for translation. Translation files should go into the languages directory.

== Upgrade Notice ==
= 4.2 =
Various improvements and fixes.

= 4.1 =
Various improvements and fixes. Dropped support for older PHPMailer libraries.

= 4.0 =
Various improvements, changes and security fix.

== Changelog ==
= 4.2.1 - 2023.08.09 =
* Edit: improved the function for detecting HTML tags in content
* Fix: duplicated merge of the KSES wide tags and attributes lists
* Fix: rare issue when the plain text processing deals with NULL value
* Fix: fixed regex for the conversion of text links

= 4.2 - 2023.07.16 =
* New: plugin icon for the dashboard, about and menu
* Edit: changed order of processing for the HTMLfy of plain content
* Edit: changed HTMLfy strip method to allow basic A and BR tags
* Edit: preprocess plain text only if it contains HTML
* Fix: stripped required tags when HTMLfy process is in strip mode
* Fix: url in plain text email ends up with encoded entities

= 4.1 - 2023.06.26 =
* New: system requirements: plugin requires WordPress 5.5
* New: system requirements: PHPMailer class 6.1 or newer
* New: expanded list of email types detection for WordPress core
* Edit: updates to the code to support only one PHPMailer class
* Remove: dropping support for older versions of PHPMailer class
* Fix: queue message logging error for long results messages
* Fix: queue sometimes fails to mark message as failed

= 4.0 - 2023.06.09 =
* New: plugin tested with WordPress up to 6.2
* New: system requirements: plugin requires PHP 7.3
* New: system requirements: plugin requires WordPress 5.2
* New: option to control HTMLfy pre-processing of plain text
* New: run KSES filter when adding email into queue
* New: run KSES filter when saving email into the log
* New: logged email preview with proper data escaping
* New: filters to control pre-processing of emails going to queue
* New: process logged email preview with KSES before displaying
* New: filter for HTMLfy pre-processing control for KSES
* Edit: many more additional content escaping for display
* Edit: various small updates to improve PHP code standards
* Edit: d4pLib 2.8.15
* Fix: unauthenticated stored cross-site scripting vulnerability

= 3.9.3 - 2022.08.26 =
* Edit: no longer check for super admin but use activate_plugins cap
* Fix: issue with saving plugin settings in multisite environments

= 3.9.2 - 2022.05.17 =
* New: plugin tested with WordPress 6.0

= 3.9.1 - 2021.12.03 =
* New: plugin tested with WordPress up to 5.8
* Edit: d4pLib 2.8.14
* Fix: phpmailer error handling not catching some errors

= 3.9 - 2021.04.16 =
* New: system requirements: plugin requires WordPress 5.0
* New: queue processing settings: show current PHP timeout limit
* Edit: main queue function improved to better handle from email and name
* Edit: d4pLib 2.8.13
* Fix: from email and name get overwritten by queue processing in some cases
* Fix: admin side panels grid rows count not saving properly

= 3.8 - 2020.09.05 =
* New: added 7 more email types detection for WordPress core
* New: support for email types detection for Asgaros Forum plugin
* New: support for email types detection for Contact Form 7 plugin
* New: log entry popup shows Info tab with more important information
* Edit: log now showing the email sending engine with status

= 3.7 - 2020.07.28 =
* New: support for WordPress 5.5 and new PHPMailer class
* New: use class alias to support new and old PHPMailer classes
* Edit: various code quality improvements
* Edit: removed some obsolete functions and code blocks
* Edit: d4pLib 2.8.12
* Fix: problem with function to normalize emails
* Fix: cleanup functions not taking into account blog ID
* Fix: few PHP strict mode warnings

= 3.6 - 2020.06.20 =
* New: dashboard widget to show latest email sending errors
* New: options to set sleep periods for batch and each email
* New: queue function now has support for 'from' field
* New: support for email types detection for Rank Math plugin
* Edit: various improvements to queue test tool

= 3.5.1 - 2020.06.10 =
* Edit: updated database schema due to the problem with column lengths
* Fix: regression related to the cron job interval saving

= 3.5 - 2020.06.09 =
* New: phpmailer smtp services listed on same settings page
* New: support for email types detection for WP Members plugin
* New: bulk retry option in the email log for failed emails
* New: auto requeue locked emails not sent due to the server error
* New: using SCSS file as a base for the CSS file
* New: reorganized CSS and JS files
* Edit: improved queue box on the plugin dashboard with more information
* Edit: improved htmlfy main method with additional arguments
* Edit: improved bulk operation messages and counts displayed
* Edit: various improvements to the JavaScript
* Edit: retried emails have new retry status
* Edit: d4pLib 2.8.10

= 3.4.2 - 2020.04.07 =
* New: tested with PHP 7.4
* Edit: d4pLib 2.8.5
* Fix: minor issue with with the PHP 7.4 deprecations

= 3.4.1 - 2019.11.02 =
* Fix: email type detection related to the GD Topic Polls plugin

= 3.4 - 2019.09.28 =
* New: validate email object for missing attachments before queue processing
* New: color coded log rows for the failed and queued emails
* New: email log: action to retry sending emails that failed previously
* Edit: various updates and expansions to the universal core email class
* Edit: queue test is now sending proper from and from name values
* Edit: various updates to the plugin readme file including more FAQ entries
* Edit: improved queue error detection that happens before the sending attempt
* Edit: few small updates to the emails log processing
* Edit: d4pLib 2.7.8
* Fix: adding to log can set wrong status for emails sent through queue
* Fix: in some cases reply_to value doesn't get stored in the queue
* Fix: some minor problems with logging the direct emails
* Fix: add to log database method doesn't log message value

= 3.3 - 2019.07.22 =
* New: improved detection of the plain text email content
* New: option to control detection of the plain text email content
* New: option to fix the plugin content type when using HTML
* New: various additional new actions and filters for more control
* New: buddypress: force use of the wp_mail to send plain text emails only
* Edit: updated plugin icon for the WordPress menus
* Edit: remove some unused PHPMailer parameters from mirroring
* Edit: d4pLib 2.7.5
* Fix: saving failed message in log fails if message is too long

= 3.2 - 2019.06.26 =
* New: mail type detection: support for GD Topic Polls
* New: phpmailer updated to use core email class for email building
* Edit: various updates to readme and extra plugin information
* Edit: d4pLib 2.7.3

= 3.1 - 2019.06.18 =
* New: universal core email class for various operations
* New: set reply to email and name globaly in wp_mail
* New: htmlfy expanded with the website tagline tag
* New: htmlfy expanded with the website link tag
* Edit: queue function: sets char set and content type if missing
* Edit: queue test now sets char set to UTF-8
* Edit: various minor tweaks and improvements
* Edit: overall improved detection of the HTML emails
* Edit: d4pLib 2.7.2
* Fix: email log: HTML tag displayed for non-HTML emails
* Fix: queue function: not setting the content type for the email
* Fix: dashboard: incorrect status for the mailer intercept
* Fix: from name global: invalid check for changing From Name

= 3.0.1 - 2019.06.15 =
* Edit: fully updated about page for the version 3.0
* Edit: various updates to the settings labels and information
* Fix: missing core engines registration action point
* Fix: missing PHPMailer services registration action point

= 3.0 - 2019.06.14 =
* New: option to pause email sending through wp_mail
* New: plugin dashboard completely reorganized
* New: plugin dashboard: wp-mail status box
* New: plugin dashboard: mail log status box
* New: database tables for emails, log and email/log relationship
* New: log emails send by wp_mail, queue or both
* New: emails log panel with an overview of all logged emails
* New: emails log panel with the option to delete from log
* New: emails log panel with popup dialogue for email preview
* New: daily maintenance with support for log cleanup
* New: fake PHPMailer class now implements magic methods
* New: mirror PHPMailer class captures more information
* New: detect email type: support for WP error recovery mode email
* New: email preheader tag: choose the value to generate
* New: a filter that can be used to pause wp_mail sending
* New: a filter that can be used to control queue decision
* New: additional filters and actions for various things
* Edit: additional information on the plugin dashboard
* Edit: improved plugin settings organization
* Edit: reset tool support for clearing the email log tables
* Edit: d4pLib 2.7.1
* Fix: email preheader tag set to the wrong value

= 2.1.2 - 2019.05.30 =
* Fix: wrong links for the update and install notifications in network mode
* Fix: wrong admin menu action used when in the network mode

= 2.1.1 - 2019.05.26 =
* Fix: wrong database table name for the queue cleanup process

= 2.1 - 2019.05.22 =
* New: option to use flexible limit when sending queued emails
* New: action run after each email has been sent through the queue
* New: a filter that can be used to pause the queue processing
* New: option on advanced settings panel to pause the queue processing
* New: export tool: select what to export: settings and/or statistics
* Edit: export tool: improved import of settings from the file as a proper array
* Edit: dashboard: improved display of the queue related information
* Edit: improved the descriptions for various plugin settings
* Edit: d4pLib 2.6.4
* Fix: export tool: statistics data problem caused by the JSON import
* Fix: export tool: wrong file name for the plugin settings export JSON file

= 2.0.1 - 2019.05.08 =
* Edit:  check if the template file exists before attempting to load
* Fix: the display of the last queue timestamp conversion error
* Fix: default option for the HTML template was wrong

= 2.0 - 2019.05.06 =
* New: support for queue email send engines
* New: email send engine: phpmailer
* New: phpmailer support for using SMTP for sending
* New: set from email and name globally in wp_mail
* New: additional information on the dashboard for queue
* New: tools to test email sending and adding to the queue
* New: detect email type for emails sent by BuddyPress
* New: includes defuse encryption library
* Edit: few changes in some of the filters and actions
* Edit: better organization of the plugin settings panels
* Edit: improvements to the function for adding to the queue
* Edit: various loading and initialization improvements
* Fix: few issues when preparing an email to send in queue
* Fix: few problems with function for adding to the queue
* Fix: plugin settings export not working

= 1.0 - 2019.05.02 =
* First plugin version

== Screenshots ==
1. Plugin Dashboard
2. Mail Log: Overview
3. Mail Log: Single Email basics
4. Mail Log: Single Email HTML
5. Email sending test tool
6. Email Example: Basic Template
7. Email Example: Basic Template with Header and Logo
8. Settings: Queue Controls
9. Settings: Mailer Controls
