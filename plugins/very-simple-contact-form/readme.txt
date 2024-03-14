=== VS Contact Form ===
Contributors: Guido07111975
Version: 15.1
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Requires PHP: 7.0
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 15.1
Tags: simple, contact, form, contact form, email


With this lightweight plugin you can create a contact form.


== Description ==
= About =
With this lightweight plugin you can create a contact form.

Add shortcode [contact] to a page or use the widget to display your form.

Form has fields for Name, Email, Subject and Message. It also has a sum to solve (to avoid abuse) and a privacy consent checkbox.

You can customize your form via the settings page or by adding attributes to the shortcode or the widget.

It's also possible to display form submissions in your dashboard.

= How to use =
After installation add shortcode [contact] to a page or use the widget to display your form.

= Settings page =
You can customize your form via the settings page. This page is located at Settings > VS Contact Form.

Settings and labels can be overridden when using the relevant attributes below.

This can be useful when having multiple contact forms on your website.

= Attributes =
You can also customize your form by adding attributes to the shortcode or the widget. Attributes will override the settings page.

Misc:

* Add custom CSS class to form: `class="your-class-here"`
* Change email address: `email_to="your-email-here"`
* Send to multiple email addresses (max 5): `email_to="first-email-here, second-email-here"`
* Change "From" email header: `from_header="your-email-here"`
* Change subject in email: `subject="your subject here"`
* Change subject in auto-reply email to sender: `subject_auto_reply="your subject here"`

Field labels:

* Name: `label_name="your label here"`
* Email: `label_email="your label here"`
* Subject: `label_subject="your label here"`
* Message: `label_message="your label here"`
* Privacy consent: `label_privacy="your label here"`
* Submit: `label_submit="your label here"`

Field placeholders:

* Name: `placeholder_name="your placeholder here"`
* Email: `placeholder_email="your placeholder here"`
* Subject: `placeholder_subject="your placeholder here"`
* Message: `placeholder_message="your placeholder here"`

Field error labels:

* Name: `error_name="your label here"`
* Email: `error_email="your label here"`
* Subject: `error_subject="your label here"`
* Sum: `error_sum="your label here"`
* Message: `error_message="your label here"`
* Message - when links are not allowed: `error_message_has_links="your label here"`
* Message - when email addresses are not allowed: `error_message_has_email="your label here"`
* Banned words: `error_banned_words="your label here"`
* Privacy consent: `error_privacy="your label here"`

Messages:

* Displayed when sending succeeds: `thank_you_message="your message here"`
* Displayed in the auto-reply email to sender: `auto_reply_message="your message here"`

Example: `[contact email_to="your-email-here" subject="your subject here" label_submit="your label here"]`

When using the widget, don't add the main shortcode tag or the brackets.

Example: `email_to="your-email-here" subject="your subject here" label_submit="your label here"`

= Display form submissions in dashboard =
Via the settings page you can activate form submissions being displayed in your dashboard.

After activation you will notice a new menu item called "Submissions".

= SMTP =
SMTP (Simple Mail Transfer Protocol) is an internet standard for sending emails.

WordPress supports the PHP `mail()` function by default, but when using SMTP there's less chance your form submissions are being marked as spam.

You must install an additional plugin for this, such as [WP mail SMTP](https://wordpress.org/plugins/wp-mail-smtp/).

= Cache =
If you're using a caching plugin and want to avoid conflicts with the contact form, I recommend excluding your contact page(s) from caching. This can be done via the settings page of most caching plugins.

= Have a question? =
Please take a look at the FAQ section.

= Translation =
Translations are not included, but the plugin supports WordPress language packs.

More [translations](https://translate.wordpress.org/projects/wp-plugins/very-simple-contact-form) are very welcome!

The translation folder inside this plugin is redundant, but kept for reference.

= Credits =
Without the WordPress codex and help from the WordPress community I was not able to develop this plugin, so: thank you!

Enjoy!


== Frequently Asked Questions ==
= About the FAQ =
The FAQ are updated regularly to include support for newly added or changed plugin features.

= How do I set plugin language? =
The plugin will use the website language, set in Settings > General.

If translations are not available in the selected language, English will be used.

= What is the default email address? =
By default form submissions will be send to the email address set in Settings > General.

You can change this via the settings page or by using an attribute.

= Why is the "from" email address not from sender? =
I have used a default "From" email header to avoid form submissions being marked as spam.

Best practice is using a "From" email header (an email address) that ends with your website domain.

The default "From" email header starts with "wordpress" and ends with your website domain.

You can change this by using an attribute.

Your reply to sender will use another email header, called "Reply-To", which is the email address that sender has filled in.

= Can I display multiple forms on the same page? =
Do not add multiple shortcodes on the same page. This might cause a conflict.

But you can display a form by using the shortcode and a form by using the widget, on the same page.

= Can I add extra fields to form? =
If you want extra fields you should use another contact form plugin, such as [WPForms](https://wordpress.org/plugins/wpforms-lite/).

= Why does form submission fail? =
An error message is displayed if plugin was unable to send form.

Your hosting provider might have disabled the mail function of your server. Please contact them for info.

If they advice you to install a SMTP plugin, please check the "SMTP" section above.

In case you're using a SMTP plugin, check the settings page of that plugin for mistakes. With most SMTP plugins it's possible to test the mail function by sending a test mail.

Form submission can also fail due to validation of an anti-spam feature. You can activate debugging via the settings page.

= Why am I not receiving form submissions? =
* Please also check the junk/spam folder of your mailbox
* Check installation info above and check attributes for mistakes
* Check the settings page, maybe you have disabled the sending of email
* In case you're using a SMTP plugin, check the settings page of that plugin for mistakes
* With most SMTP plugins it's possible to test the mail function by sending a test mail
* Install another contact form plugin to determine whether it's caused by VS Contact Form or not

= Does this plugin have anti-spam features? =
Of course, the default WordPress validating, sanitizing and escaping functions are included.

Form has a sum to solve and form contains hidden honeypot fields and a hidden time trap.

And you can limit the number of links in Message field to only 1, or disallow links and email addresses in Message field altogether.

= Does this plugin meet the conditions of the GDPR? =
The General Data Protection Regulation (GDPR) is a regulation in EU law on data protection and privacy for all individuals within the European Union.

I did my best to meet the conditions of the GDPR:

* Form has a privacy consent checkbox
* You can disable collection of IP address
* Form submissions are safely stored in database, similar to how the default posts and pages are stored
* You can easily delete form submissions

= Does this plugin have its own block? =
Not yet, but might be added in the near future.

= Why is there no semantic versioning? =
The version number won't give you info about the type of update (major, minor, patch). You should check the changelog to see whether or not the update is a major or minor one.

= How can I make a donation? =
You like my plugin and want to make a donation? There's a PayPal donate link at my website. Thank you!

= Other questions or comments? =
Please open a topic in the WordPress.org support forum for this plugin.


== Changelog ==
= Version 15.1 =
* Fix: validation
* Minor changes in code

= Version 15.0 =
* Improved validation
* Minor changes in code

= Version 14.9 =
* New: setting to allow or disallow email address in Message field
* New: setting to ignore form submissions with banned words, or when Message field does not accept links or email addresses
* Fix: sum placeholder

= Version 14.8 =
* Fix: sum vulnerability (thanks Patchstack)

= Version 14.7 =
* Replaced base64 with the wp_hash() function
* Minor changes in code

= Version 14.6 =
* Minor changes in code

= Version 14.5 =
* Fix: updated transient
* This fixes the flooding of database with temporary transients when site has much traffic
* IP address is not being used anymore for creating a transient

= Version 14.4 =
* Fix: autofill of honeypot fields

= Version 14.3 =
* Fix: removed previously added cookie
* This was causing the "headers already sent" error in some cases

= Version 14.2 =
* Fix: updated transient
* Improved validation
* Updated file uninstall
* Bumped the "requires at least" version to 5.0
* Minor changes in code

For all versions please check file changelog.


== Screenshots ==
1. Shortcode form (GeneratePress theme)
2. Shortcode form (GeneratePress theme)
3. Widget form (GeneratePress theme)
4. Widget (dashboard)
5. Settings page (dashboard)
6. Settings page (dashboard)
7. Settings page (dashboard)
8. Settings page (dashboard)
9. Settings page (dashboard)
10. Form submissions page (dashboard)