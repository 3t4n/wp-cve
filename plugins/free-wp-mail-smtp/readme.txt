=== Free WP Mail SMTP (Official - 2019) ===
Contributors: Mail250 Team
Donate link: http://www.mail250.com/
Tags: smtp, wp mail smtp, wordpress smtp, gmail smtp, mail, mailer, phpmailer, wp_mail, wp-mail, email, wp smtp, wordpress smtp plugin, bulk email, bulk mail, mail250, email marketing, bulk email marketing, deliverability, email deliverability, email delivery, email server, mail server, email integration
Requires at least: 3.3
Tested up to: 5.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Delivered 398 million+ emails. This WP MAIL SMTP plugin is optimised for Gmail's GT.387 algorithm. Download and send Unlimited Emails.

== Description ==

Having problem with your WordPress site not sending emails? over 6k websites use Mail250 platform to fix their email delivery issues.

WordPress users face this chronic email delivery problem due to the fact that by default WordPress website uses wp_mail() which internally uses PHP's mail() function, which uses server's localhost configuration (on which website is hosted) to send emails. The default configuration won't work as most of the shared hosting providers blocks outgoing SMTP ports 25 to protect their infrastructure from spam. This plugin fixes this problem by using Mail250 SMTP services instead of your hosting provider's server settings.

Mail250 is an AI-Driven Bulk Email Marketing Service

The Mail250 plugin uses SMTP integration to send outgoing emails from your WordPress installation. It replaces the wp_mail function included with WordPress.

To have the mail250 plugin running after you have activated it, go to the plugin's settings page and set the mail250 credentials.

How to use `wp_mail()` function:

We amended `wp_mail()` function so all email sends from WordPress should go through Mail250.

You can send emails using the following function: `wp_mail($to, $subject, $message, $headers = '', $attachments = array())`

Where:

* `$to` - Array or comma-separated list of email addresses to send message.
* `$subject` - Your email subject
* `$message` - Your email body (HTML)
* `$headers` - Array or "\n" separated  list of additional headers. Optional.
* `$attachments` - Array or "\n"/"," separated list of files to attach. Optional.

The wp_mail function is sending text emails as default. If you want to send an email with HTML content you have to set the content type to 'text/html' running `add_filter('wp_mail_content_type', 'set_html_content_type');` function before to `wp_mail()` one.

After wp_mail function you need to run the `remove_filter('wp_mail_content_type', 'set_html_content_type');` to remove the 'text/html' filter to avoid conflicts --http://core.trac.wordpress.org/ticket/23578

Example about how to send an HTML email using different headers:

`$subject = 'Test Email via WP Mail SMTP plugin';
$message = 'Body of the test email sent using WP Mail STMP WordPress plugin';
$to = 'recipient1@example.com, Recipient Name <recipient2@example.com>';
or
$to = array('recipient1@example.com', 'Recipient Name <recipient2@example.com>');
 
$headers = array();
$headers[] = 'From: Sender <sender@example.com>';
$headers[] = 'X-Your-Custom-Header: YourCustomeHeaderValue';
 
$attachments = array('/tmp/img1.jpg', '/tmp/img2.jpg');
 
add_filter('wp_mail_content_type', 'set_html_content_type');
$mail = wp_mail($to, $subject, $message, $headers, $attachments);
 
remove_filter('wp_mail_content_type', 'set_html_content_type');`

== Installation ==

Requirements:

1. PHP version >= 5.3.0

To upload the Mail250 Plugin .ZIP file:

1. Upload the WordPress Mail250 Plugin to the /wp-contents/plugins/ folder.
2. Activate the plugin from the "Plugins" menu in WordPress.
3. Create a Mail250 account at <a href="http://www.mail250.com/" target="_blank">http://www.mail250.com/</a>  
4. Once the account is created. Login to your Mail250 account and navigate to "Home" -> "SMTP Details" to get your SMTP credentials

== Changelog ==

= 1.0 =
* Mail250 Wordpress SMTP Plugin released

== Data & Privacy ==
Your emails will be sent via Mail250 - a third party AI based email delivery platform. For details please refer to Mail250   <a href="https://mail250.com/legal/privacy/?utm_campaign=wp_mail&utm_source=wordpress&utm_medium=plugin" target="_blank"> Privacy </a>and <a href="https://mail250.com/legal/terms/?utm_campaign=wp_mail&utm_source=wordpress&utm_medium=plugin" target="_blank">Terms</a>

