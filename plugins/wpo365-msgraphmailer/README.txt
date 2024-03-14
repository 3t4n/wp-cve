=== WPO365 | MICROSOFT 365 GRAPH MAILER ===
Contributors: wpo365
Tags: mail, email, smtp, phpmailer, wp_mail, Office 365, O365, Microsoft 365, M365, Exchange Online, Microsoft Graph, sendMail, azure active directory, Azure AD, AAD
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 2.27
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

**WPO365 | MS GRAPH MAILER** provides you with a modern, reliable and efficient way to send WordPress transactional emails from one of your Microsoft 365 / Exchange Online / Mail enabled accounts. 

The plugin re-configures your WordPress website to send emails using the **Microsoft Graph API** instead of - for example - SMTP. Sending WordPress emails using the **Microsoft Graph API** has become the only available alternative after Microsoft has disabled basic authentication (username and password) over the SMTP protocol.

= DELIVERY =

- Send WordPress transactional emails from one of your **Microsoft 365 Exchange Online / Mail enabled accounts** using Microsoft Graph instead of - for example - SMTP.
- Choose between delegated (send mail as a user) and application-level (send mail as any user) type permissions.

= SEND AS HTML =

- Send emails formatted as **HTML**.

= SAVE TO SENT ITEMS =

- Emails sent will be saved in the account's mailbox in the **Sent Items** folder, further helping to track (successful) mail delivery.

= ATTACHMENTS =

- Send files from your WordPress website as *attachments*. 

= CONFIGURATION / TEST EMAIL DELIVERY =

- Easy configuration with detailed step-by-step [Getting started](https://docs.wpo365.com/article/141-send-email-using-microsoft-graph-mailer) guide and video.
- Send *test email* to recipients incl. CC, BCC and attachment.

https://youtu.be/1CK7Fl8f8iA

== ADD FUNCTIONALITY WITH EXTENSIONS ==

The following features can be unlocked with the [WPO365 | MAIL](https://www.wpo365.com/downloads/wpo365-mail/) extension.

= LARGE ATTACHMENTS =

- Add support to send WordPress emails with **attachments larger than 3 MB** using Microsoft Graph.

= SEND AS / SEND ON BEHALF OF =

- Send email as / on behalf of another user or distribution list.

= SHARED MAILBOX =

- Send email from **Microsoft 365 Shared Mailbox**.

= STAGING MODE =

- **Mail Staging Mode** is useful for debugging and staging environments. WordPress emails will be logged and saved in the database instead of being sent.

= WP-CONFIG FOR AAD SECRETS =

- Further improve overall security by choosing to store Azure Active Directory secrets in your WordPress WP-Config.php (on disk) and have those secrets removed from the database.

= MAIL AUDIT / RESEND =

- **Log every email** sent from your WordPress website, review errors and (automatically) try to send unsuccessfully **sent mails again**.

= DYNAMIC SEND-FROM =

- Allow forms to **override "From"** address e.g allow Contact Form 7 to dynamically configure the account used to send the email from (requires application-level Mail.Send permissions).

= MAIL THROTTLE =

- **Throttle** the number of emails sent from your website per minute.

= SEND AS BCC =

- Send emails **as BCC** instead and prevent reply-to-all mail pollution.

= REPLY-TO =

- Configure a **default reply-to** mail address if this should differ from the account's mail address that is used to send WordPress transactional emails from.

== Prerequisites ==

- We have tested our plugin with Wordpress >= 5.0 and PHP >= 5.6.40.
- You need to be (Office 365) Tenant Administrator to configure both Azure Active Directory and the plugin.

== Support ==

We will go to great length trying to support you if the plugin doesn't work as expected. Go to our [Support Page](https://www.wpo365.com/how-to-get-support/) to get in touch with us. We haven't been able to test our plugin in all endless possible Wordpress configurations and versions so we are keen to hear from you and happy to learn!

== Feedback ==

We are keen to hear from you so share your feedback with us on [LinkedIn](https://www.linkedin.com/company/downloads-by-van-wieren) and help us get better!

== Open Source ==

When you’re a developer and interested in the code you should have a look at our repo over at [WordPress](http://plugins.svn.wordpress.org/wpo365-msgraphmailer/).

== Installation ==

Please refer to [these **Getting started** articles](https://docs.wpo365.com/article/141-send-email-using-microsoft-graph-mailer) for detailed installation and configuration instructions.

== Frequently Asked Questions ==

== Screenshots ==
1. Configuration page
2. Mail audit log

== Changelog ==

= v2.27 =
* Fix: The plugin attempted to process any POST request with parameter "error", mistakenly assuming that it would be an authentication-error sent by Microsoft. [LOGIN, MICROSOFT GRAPH MAILER]

= v2.26 =
* Fix: Updated parts of the PHP Security Library v3 to improve compatibility with older PHP versions.

= v2.25 =
* Fix: Fixed "Fatal error: Cannot use ::class with dynamic class name" for 2 files in PHP Security Library v3.

= v2.24 =
* Improvement: The default response mode - for new installations - when requesting an (OIDC) authorization code has been updated to "query". This will help preserve the code, especially if the administrator has configured a 3rd party multi-factor authentication provider such as Duo. Existing installations are not affected, however, and the response mode remains "form_post". See the [updated documentation](https://docs.wpo365.com/article/208-select-oidc-response-mode) for details.
* Improvement: Admins configuring the Microsoft Graph Mailer portion of WPO365 can now select an option to skip all checks. Checking this option instructs the Microsoft Graph Mailer to skip the check whether the default "from" email address is registered for the corresponding account and whether the "from" email address specified by a plugin has a different email-domain compared to the default "from" email address used to submit email message to Microsoft Graph.
* Fix: The PHP Secure Communications library has been updated and the plugin now uses version 3.0 (to verify an ID token's signature). [LOGIN, MICROSOFT GRAPH MAILER]

= v2.23 =
* Breaking Change: Sending WordPress email using Microsoft Graph now always will use the Azure AD configuration from the plugin's Mail configuration page. [LOGIN]
* Tested up to 6.4. [ALL]

= v2.21 =
* Feature: WPO365 can now send a daily notification to the administation email address if one of the application / client secrets is about to expire in the next 30 days. Consult [this article](https://www.wpo365.com/article/client-secret-expiration-notification/) for details. [LOGIN, MICROSOFT GRAPH MAILER]
* Fix: The plugin's updater will now display a notification when a newer version of a premium addon is available.

= v2.20 =
* Feature: **(Auto-) Retry sending failed emails** using Microsoft Graph. See the [online documentation](https://docs.wpo365.com/article/183-resending-failed-emails-automatically) for details. [MAIL]
* Feature: **Throttle nr. of emails send per minute** using Microsoft Graph. See the [online documentation](https://docs.wpo365.com/article/182-throttle-the-number-of-emails-sent-per-minute) for details. [MAIL]
* Improvement: The WPO365 | MAIL premium addon now also unlocks the option to use WP-Config.php to override (some) config options. Now administrators can - for example on their staging environment - enable mail-staging mode, simply by adding a global constant to the WP-Config.php file. See the [updated documentation](https://docs.wpo365.com/article/171-mail-staging-mode). [MAIL]
* Fix: Tested with PHP 8.2. [ALL]

= v2.19 =
* Fix: The plugin update checker did not always return the expected result. [LOGIN, MS GRAPH MAILER]

= v2.18 =
* Fix: Various modifications to **Microsoft Graph Mailer** configurator should make it easier and more intuitive to configure it.
* Fix: In an attempt to prevent the error "cURL error 28: Operation timed out after 15001 milliseconds with 0 bytes received" when integrating with Microsoft Graph, the use of the Expect: header has been disabled by default.
* Fix: [PREMIUM] The Log Viewer - to view and optionally resend emails sent using the Microsoft Graph Mailer - now calculates the last inserted logged item ID using MAX() instead of looking up the AUTO INCREMENT value, which may not be up-to-date.
* Fix: [PREMIUM] If the license key can not be verified, it will not be deleted. The corresponding error is logged as an error.

= v2.17 =
* Fix: The built-in Microsoft Graph Mailer for WordPress will now exclude any custom headers that do not start with x- or X-, to prevent Microsoft Graph from not sending the message and reporting the following error instead: "The internet message header name [...] should start with 'x-' or 'X-'.". [LOGIN, MICROSOFT GRAPH MAILER]

= v2.16 =
* Improvement: The WPO365 | MICROSOFT GRAPH MAILER plugin can now also log remotely to ApplicationInsights, allowing administrators to configure **Azure's Monitoring / Alerts** feature e.g. to send an SMS whenever an exception is logged. 
* Fix: The Microsoft Graph Mailer for WordPress no longer "unauthorizes" itself, after it fails to retrieve an access token. Instead, WPO365 Health Messages are created and administrators should regularly check for errors.
* Fix: Refactored the flow when sending emails from a different account than the one submitting the request to send an email to Microsoft Graph (= the default "From" account) to improve consistency, even when the alternative sending-from account is a Shared Mailbox, a Distribution List or Group or normal User Mailbox. [PREMIUM]

= v2.15 =
* Feature: Administrators can now enable **Mail Staging Mode**. If enabled, the WPO365 plugin will not send emails using Microsoft Graph anymore but instead will write them to the central *Mail Log*. This makes especially sense for a staging environment. [PREMIUM]
* Improvement: The WPO365 plugin will now handle forms (e.g. Contact Form 7) that propose to send emails from a different account than the "default from" mail account, after it handles any other option (e.g Shared Mailbox or Send as / Send on behalf of). The proposed "alternative from" therefore always prevail. It can also be any type of mailbox e.g. User Mailbox, Shared Mailbox or Distributionlist. But it's up to the adminstrator to ensure that the "default from" mail account is a either a member (e.g. of the Shared Mailbox) or has sufficient permissions to send emails as / on behalf of an alternative account (e.g. the Distributionlist). [PREMIUM]
* Fix: The initial OpenID Connect authorization request will now always include https://graph.microsoft.com/User.Read.
* Fix: A public property $ErrorInfo has been added to the PHPMailer object to support integration with Gravity Forms.
* Fix: The plugin now better understands - in the context of WordPress Multisite installations - whether the configuration must be retrieved / stored at site or at network level.

= v2.14 =
* Fix: ID Token validation now also validates audiences that are defined using an Application ID URI instead of the Application ID (e.g. this is the case for Microsoft Teams). [LOGIN, MICROSOFT GRAPH MAILER]
* Fix: The plugin does no longer rely on the HTTP_HOST key of the global $_SERVER variable, which - if not initialized - may cause a critical error on the website. [LOGIN, MICROSOFT GRAPH MAILER]
* Fix: The link to launch the Mail Log Viewer would return "false" for FireFox users. [MAIL]


= v2.13 =
* Improvement: The Microsoft Graph Mailer for WordPress will notify the administrator in the form of a WPO365 Health Message when another plugin with mail-sending capabilities is detected.
* Fix: An alternative system for nonces has been introduced to work around the fact that some browsers would not send the WordPress auth cookie along with HTTP 302 redirect requests, causing WordPress nonce verification to fail unexpectedly, in which case the plugin would then log the warning "Could not successfully validate oidc nonce with value xyz".

= v2.12 =
* Fix: The recently added *ID token verification* did not take the mail-authorization flow into account.
* Improvement: Administrators can now re-configure the WPO365 | LOGIN plugin to skip the *ID token verification* altogether, on the plugin's *Miscellaneous* configuration page (but this is not recommended for production environments).


= v2.11 =
* Fix: Various issues with the builtin license and update checker for premium extensions and bundles.

= v2.10 =
* Fix: License check for WPO365 | MAIL extension would show "unknown error occurred" for valid licenses.
* Fix: Update check for WPO365 | MAIL extension now better aligned with the recently updated license management service.


= v2.9 =
* Fix: The *Allow forms to override "From" address* was only enabled for application-level *Mail.Send* permissions.
* Fix: Overriding the "From" address was sometimes ignored.
* Fix: Sending from a Shared Mailbox was sometimes ignored.

= v2.8 =

= v2.7 =
* Fix: The mail authorization may falsely indicate that the plugin is not authorized to send emails using Microsoft Graph due to how the plugin compared permissions.

= v2.6 =
* Feature: Websites that are using the [Mail Integration for Office 365/Outlook](https://wordpress.org/plugins/mail-integration-365/] are now urged to switch to [WPO365 | MICROSOFT GRAPH MAILER](https://wordpress.org/plugins/wpo365-msgraphmailer/) or configure the builtin Microsoft Graph mail function of the WPO365 | LOGIN plugin. Consult the [online migration guide](https://docs.wpo365.com/article/165-migrate-from-mail-integration-for-office-365-outlook-to-wpo365-microsoft-graph-mailer) for further details. [ALL]

= v2.5 =
* Feature: The (premium version of the) Microsoft Graph Mailer can now send attachments larger than 3 MB.
* Feature: The (premium version of the) Microsoft Graph Mailer can now send emails from a Shared Mailbox.
* Improvement: Some parts of the source code have been updated to improve compatibility with PHP 8.1.

= v2.4 =
* Fix: Mail authorization would fail with the error "Could not retrieve a tenant and application specific JSON Web Key Set and thus the JWT token cannot be verified successfully".

= v2.3 =
* Fix: The delegated mail authorization feature would - under circumstances - fail to get the mail specific tenant ID and as a result an attempt to refresh the access token may fail.

= v2.2 =
* Fix: The Redirect URL field for the mail authorization is no longer greyed out and can be changed by administrators. [LOGIN]

= 2.1 =
* Fix: Added missing files.

= 2.0 =
* Change: Sending WordPress emails using Microsoft Graph can now also be configured with **delegated** permissions. Administrators are urged to review the [documentation](https://docs.wpo365.com/article/141-send-email-using-microsoft-graph-mailer) and to update their configuration. [LOGIN, MICROSOFT GRAPH MAILER]
* Feature: Azure Active Directory secrets can now be stored in the website's **WP-Config.php** and removed from the database. [MAIL]

= 1.8 =
* Fix: If the plugin is configured to send WordPress emails using Microsoft Graph then it will now always replace the "from" email address if WordPress tries to sent emails from "wordpress@[sitename]". WordPress will propose this email address is no email is set by the plugin sending the email (e.g. Contact Form 7). This email may pass checks as a valid email address but in reality this email address most likely does not exist. The option to fix the "localhost" issue has been removed since this fix improves the behavior for all hosts (incl. localhost). [ALL]

= 1.7 =
* Improvement: When specified in - for example - an email form the "From" address will be used to send the email from (instead of the configured "From" address and if the address specified in the form appears to be valid). This behavior is a premium feature and not enabled by default.

= 1.6 =
* Change: Sending mail as HTML is no longer a premium feature.
* Change: Saving a sent mail in the Sent Items folder is no longer a premium feature.
* Improvement: The Graph Mailer components have been refactored for improved logging / auditing.
* Fix: Sending a test email with attachment is now supported by all versions.
* Fix: The plugin will not try and send attachments larger than 3 Mb (the prevent the mail being refused by the Microsoft Graph API).

= 1.5 =
* Fix: Several issues related to PHP 8.x have been fixed.

= 1.4 =
* Updated README.txt

= 1.3 =
* Improvement: The plugin will now honor a reply-to email address defined "externally" e.g. when using Contact Form 7.
* Fix: Activating the plugin would case a critical error due to a class-loading error.

= 1.2 =
* Fix: Compatibility update.

= 1.1 =
* Fix: Some minor code issues were fixed after review.

= 1.0 =
* Initial version.
