=== WP Prayer ===
Contributors: abrg
Donate link: https://www.goministry.com/
Tags: church, ministry, bible, pray, prayer
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 2.0.8
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Prayer request application that allows users to submit requests, or pray for existing requests

== Description ==

Prayer request application that allows users to submit requests, or pray for existing requests.  All requests can be moderated from the admin section.
Every time a request is submitted, prayer requester will receive an email detailing the prayer they submitted.

= Live WP Prayer =

View our working version of WP Prayer, post a prayer request or pray for others here: [Request Prayer](https://www.goministry.com/request-prayer/)

= Features =

* List of prayer requests with details at administrator area.
* Email notifications to users and administrator when request is made.
* Easy management of content used in email notification.
* Dynamic Thank you page content with editable content at administrator area.
* Spam prevention on prayer request form
* Captcha on prayer request form

= Help us by Donating a Bible =

Do you have an extra bible? Instead of bringing them to a used bookstore, consider donating a bible into the hands of people who are hungry for the Word of God. Your gift will mean so much for someone who's eager to have a Bible in his or her own language. What a wonderful way to show God's love. [Donate Bible](https://www.kingsbiblesociety.com/donate-bible/)

= Support and Requests =

We respond to all support requests sent from our WP Prayer contact form: [Contact](https://www.goministry.com/contact/)

== Installation ==

1. Upload 'WP Payer' folder to the '/wp-content/plugins/' directory or use the 'Add New' option under the 'Plugins' menu in your WordPress backend and 'Upload' 'WP Prayer.zip'.

2. Activate the plugin through the 'Plugins' menu in WordPress.

3. Configure settings for WP Prayer in the WordPress dashboard by clicking on the 'WP Prayer' option in your WordPress backend.

4. Enter the appropriate information into the form fields on the 'WP Prayer Settings' page.

5. Make sure the appropriate shortcodes are placed on the appropriate pages.

6. Paste shortcodes accordingly:

* Paste this shortcode into the page you would like to use to display your prayer listings: [wp-prayer-engine]

* Paste this shortcode into the page you would like to use to display your praise listings: [wp-prayer-praise]

* Paste this shortcode into the page you would like to use to display your submission form for prayer request and praise report: [wp-prayer-engine form]

* Paste this shortcode into the page you would like to use to display your submission form for prayer request: [wp-prayer-engine form type=prayer]

* Paste this shortcode into the page you would like to use to display your submission form for praise report: [wp-prayer-engine form type=praise]

== Frequently Asked Questions ==

= How does Spam/DDoS prevention and detection system work? =

Spam/DDoS prevention and detection system prevents DDoS attacks on prayer request form by blocking the frequent requests from one or several IP addresses. If the maximum number of requests from related IP addresses exceeds a certain time interval, then excessive requests are blocked. Spam bots usually submit the info immediately after the page has been loaded, this happens because spam bots do not actually fill the web form, they just send $_POST data to the blog. The normal visitor sends the data after several seconds or minutes. When the prayer request is posted, if the timestamps are missing or if the user did not spend enough time on the page, the prayer request will be blocked.

= How does spam prevention (aka honeypot technique) method work? =

The spam prevention is based on fact that almost all the bots will fill inputs with name 'email' or 'url'. Extra hidden field is added to prayer request form. This field is hidden for the user and user will not fill it. But this field is visible for the spammer. If the spammer will fill this trap-field with anything - the prayer request will be blocked because it is spam. This blocks automatic spam messages (sent by spam-bots via post requests). This does not block manual spam (submitted by spammers manually via browser).

= If mails are going in spam. =

To avoid your email being marked as spam, it is highly recommended that your domain name in 'From Email' must match with your website, i.e. if your website is example.com then your email must hosted on @example.com.

= Why does the From address still show as the default or show up as 'sent on behalf of' the default address? =

Possibly your mail server has added a Sender: header or is configured to always set the envelope sender to the user calling it.

= Why are emails not being sent? =

Some hosts may refuse to relay mail from an unknown domain. See https://trac.wordpress.org/ticket/5007 for more details.

= Captcha does not appear on website. =

When logged in to Wordpress, captcha does not show on website. Log out from Wordpress to see the captcha.

== Screenshots ==

1. Settings page

2. Email Settings page

3. Prayer Request

4. Manage Prayers page

== Changelog ==

= 2.0.8 =
* Removed social media buttons

= 2.0.7 =
* Removed export to pdf

= 2.0.6 =
* Add spam filter to comments

= 2.0.5 =
* Fix Pray button for block themes

= 2.0.4 =
* Add option to remove email on prayer form

= 2.0.3 =
* Add custom thank you message after prayer submission

= 2.0.2 =
* Add option to notify user when someone pray for the request

= 2.0.1 =
* Add status private to managed prayers

= 2.0 =
* Allow HTML Links/Hyperlinks in user email and admin email confirmation message box

= 1.9.9 =
* Hide prayer form after submission

= 1.9.8 =
* Option to change text on Pray button


For older changelog entries, please see the [additional changelog.txt file](https://plugins.svn.wordpress.org/wp-prayer/trunk/changelog.txt) delivered with the plugin.
