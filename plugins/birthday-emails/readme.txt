=== Birthday Emails ===
Contributors: carman23
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XDD97JCQEP6M6
Tags: birthday, user, member, membership, email, WordPress, BuddyPress, Profile Fields
Requires at least: 4.5
Tested up to: 5.3
Stable tag: 1.2.3
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically send an email to WordPress or BuddyPress users on their birthday.

== Description ==

You or your members enter a birthdate in WordPress or BuddyPress. This plugin automatically sends an email to such members on their birthday.

You can customize the email that is sent to all Users.

You can request a notification email be sent to you for each birthday email that gets sent, so you know it happened and when.

If you want to contact the author, write to clawrick@gmail.com 

Birthday Emails is currently available in English only, though you can customize the birthday email sent, in any language you wish.

== Installation ==

1. Upload the 'birthday-emails' folder to the '/wp-content/plugins' directory or 'Add New' plugin through the 'Plugins' menu in WordPress
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Ensure that you have set the time zone for your Wordpress installation in wp-admin Settings -> General -> Timezone
4. For setup instructions for this plugin, on your wp-admin display, go to: Users -> Birthday Emails Settings

== Frequently Asked Questions ==

= Where are the settings for this plugin? =

On the wp-admin panel, select: Users -> Birthday Emails Settings

= Where do I enter Users birth days and birth months? =

For *WordPress*, this plugin adds fields to the user profile pages. Enter birthday day and month numbers on each user's profile page, under "Contact Info". Be sure to use numbers only, and no leading zero. Be careful not to put the day and month in the wrong fields. Remember to Save the updated User's information.

For *BuddyPress*, add a date selector Profile Field for the user's birthdate. Any date format will do. Then select the field name you give to the Profile Field in the Birthday Emails Settings page. Remember to click "Save Changes".

= I just installed the plugin but no birthday email has been sent yet. =

The plugin waits for 3 hours after being activated, before it starts to check hourly for birthdays and sending emails. This is to give you time to set up the plugin first, before it starts automatically looking for current birthdays. 

Also, there's a setting for the hour of the day when the plugin should start sending emails. The plugin waits for this hour of the day to be reached before it will send birthday emails. 

If you want to immediately check for current birthdays and send emails immediately, use the button on the Settings panel called "Check and Send Immediately". This button will cancel the wait and check for current birthdays immediately, sending email(s) if birthdays for today are found.

= In Settings there is a button called "Check and Send Immediately". What is this for? =

The plugin waits for 3 hours after being activated, before it starts to check hourly for birthdays and sending emails. This is to give you time to set up the plugin first, before it starts automatically looking for current birthdays. 

Also, there's a setting for the hour of the day when the plugin should start sending emails. The plugin waits for this hour of the day to be reached before it will send birthday emails. 

Also, the Birthday Emails plugin checks once per hour for birthdays that are today, and sends emails if found.

If you want to immediately check for current birthdays and send emails immediately, use the button on the Settings panel called "Check and Send Immediately". This button will cancel the wait and check for current birthdays immediately, sending email(s) if birthdays for today are found.

= What if I want a notification email sent to more than one address? =

You can enter a list of email addresses for notifications, each separated from the other with a comma.

= How do I customize the email that gets sent to a birthday User? =

Go to the Settings panel, and click the button at the bottom called "Edit Birthday Template". The Settings panel is on the wp-admin display, under Users -> Birthday Emails Settings.

= I want the email to look proper on a phone or tablet, or I want to control the background color, font, header and footer. How? =

You can edit the email content with the "Edit Birthday Template" button at the bottom of the Settings panel. To control these other things for your emails I recommend an additional plugin called [WP HTML Mail - Email Designer](https://wordpress.org/plugins/wp-html-mail/ "WP HTML Mail - Email Designer") by Hannes Etzelstorfer. It will let you control these other things in your emails.

= I entered a test email name and address and clicked  the "Send Test Email" button. Why didn't I get a test email? =

Be sure to click "Save Changes" after entering the test email name and address, before you click "Send Test Email". Also, see "Why can't I send test emails?" next.

= Why can't I send test emails? =

Make sure your installation of WordPress is capable of sending emails. Not all WordPress installs can do this. Use the [Check Email plugin by Chris Taylor](https://wordpress.org/plugins/check-email "Check Email plugin by Chris Taylor") to test, and see if your WordPress installation can send emails. If your WordPress installation cannot send emails, then this Birthday Emails plugin cannot work for you. 

One possible solution might be to employ an alternate email solution for WordPress. [Here is a blog](http://webcraft.tools/simple-smtp-plugins-for-wordpress/ "Here is a blog") about 5 ways to replace the email function in WordPress and why you would want to do this.

I use this plugin: [Postman SMTP Mailer/Email Log By Jason Hendriks](https://en-ca.wordpress.org/plugins/postman-smtp/ "Postman SMTP Mailer/Email Log By Jason Hendriks"). There is an article on how to use that plugin here: [How to Use Postman SMTP to Send WordPress Emails](http://www.praybox.com/how-to-use-postman-smtp-to-send-wordpress-emails/ "How to Use Postman SMTP to Send WordPress Emails"). (The article is for users of the "Praybox plugin" but applies equally to users of any WordPress plugin that sends emails, like Birthday Emails does.)

= My Emails are sent sometimes but not every time, are stopped or not supported by my ISP, or are being marked as SPAM. What can I do? =

One possible solution might be to employ an alternate email solution for WordPress. [Here is a blog](http://webcraft.tools/simple-smtp-plugins-for-wordpress/ "Here is a blog") about 5 ways to replace the email function in WordPress and why you would want to do this.

I use this plugin: [Postman SMTP Mailer/Email Log By Jason Hendriks](https://en-ca.wordpress.org/plugins/postman-smtp/ "Postman SMTP Mailer/Email Log By Jason Hendriks"). There is an article on how to use that plugin here: [How to Use Postman SMTP to Send WordPress Emails](http://www.praybox.com/how-to-use-postman-smtp-to-send-wordpress-emails/ "How to Use Postman SMTP to Send WordPress Emails"). (The article is for users of the "Praybox plugin" but applies equally to users of any WordPress plugin that sends emails, like Birthday Emails does.)

= A User's birthday was reached, but no email was generated for this user. Why? = 

For *WordPress*, be sure to enter the day number in the User's birth day field, and the month number in the User's month number field. It's easy to put the numbers in the wrong places. Also be sure not to include a leading zero in the numbers. It is possible that no-one visited your site on that birthday. With some WordPress installations someone needs to visit your site every day to trigger the email send every day. You may need to add this to your wp-config.php file, if your site doesn't get visited every day: `define('ALTERNATE_WP_CRON', true);` I had to add this to my own blog site.

For *BuddyPress*, be sure to select the Profile Field name you added to BuddyPress for the user to enter their birthdate (Type: Date Selector), select it in the Birthday Emails Settings page. Remember to click "Save Changes".

= A birthday email was sent the day before, or the day after the actual birthday. Why? =

Be sure to set the timezone for your Wordpress installation in wp-admin Settings -> General -> Timezone.

= I clicked the Unsubscribe link in the birthday email. How do I re-subscribe? =

On your User Profile page, under "Contact Info" you'll find a field called Birthday Emails Unsubscribed. When this field contains "true" you won't receive birthday emails. Change it to anything other than "true" and then you'll receive birthday emails again. Remember to click "Update Profile" after you've changed the field.

= How do I add a Profile Field in BuddyPress for the Birthdate? =

In the WordPress Admin panel, go to Users -> Profile Fields

Click the "Add New Field" button.

Give the new field a name, such as "Birthday" or "Birthdate". Make note of this name. You'll have to select it in the Birthday Emails Settings.

For "Type", Select "Date Selector".

For "Date format" choose any offered. Any format will work.

For "Range" set the Start at "1900" or whatever you deem appropriate.

For "Requirement" choose your preference. If the user does not have to enter a birthdate, and they choose not to, they will not receive a Birthday Email.

For "Visibility" choose your preference. A user is more likely to enter a birthdate if it is not visible to everyone.

Remember to click the "Save" button.

== Screenshots ==

1. An example of a birthday email sent.
2. The menu item for the Settings panel.
3. The Settings panel for the Birthday Emails plugin.
4. The panel for customizing the email sent on each User's birthday.

== Changelog ==

= 1.2.3 =
* Corrected omission of graphic birthday cake

= 1.2.2 =
* Added Unsubscribed field to User Profile page.
* Tightened security on unsubscribe.

= 1.2.1 =
* Improved BuddyPress integration.

= 1.2 =
* Added compatibility with BuddyPress.
* Added @firstname and @nickname placeholders for email content
* Added support for an unsubscribe link in the emails to avoid SPAM traps

= 1.1 =
* Added option for time of day to send emails.

= 1.0 =
* Initial Features offered

== Upgrade Notice ==

= 1.2.3 =
* Corrected omission of graphic birthday cake

