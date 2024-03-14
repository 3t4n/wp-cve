=== WP Prayer II ===
Contributors: abrg,littlebenjiboy
Donate link: https://www.goministry.com/
Tags: church, pray, prayer, Bible, ministry
Requires at least: 5.0
Tested up to: 6.3
Stable tag: 2.4.6
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An application that allows an organization share, update, and manage prayer requests.

== Description ==

Users can submit requests through prayer form using the shortcode. They can also click a pray button that lets the original poster know their request has been prayed for by someone else. Each request displays a count of how many times it has been prayed for. All requests can be categorized and tagged like a post.

Authorized users can track and manage prayer requests through admin page. They can approve requests, enter comments as well as look at several other pieces of information stored with each request.

= Live Prayer =

View our working version of Prayer, post a prayer request or pray for others here: [Request Prayer](https://www.goprayer.com/request-prayer/)

= Features =

* Requests may be entered by those needing prayer directly through your existing website. May be 
added by staff at any login level.
* Email notifications to users and administrator when request is made.
* All requests can be moderated to make sure the request is appropriate for intercessors.
* Set up categories that apply for your ministry.
* Spam prevention on prayer request form
* Captcha on prayer request form

= Help us by Donating a Bible =

Do you have an extra bible? Instead of bringing them to a used bookstore, consider donating a bible into the hands of people who are hungry for the Word of God. Your gift will mean so much for someone who's eager to have a Bible in his or her own language. What a wonderful way to show God's love. Donate a Bible here: [Donate Bible](https://www.kingsbiblesociety.com/donate-bible/)
 

= Support and Requests =

We respond to all support requests sent from our WP Prayer contact form: [Contact Form](https://www.goprayer.com/contact/)

== Installation ==

1. Upload Prayer folder to the '/wp-content/plugins/' directory or use the 'Add New' option under the 'Plugins' menu in your WordPress backend and 'Upload'

2. Activate the plugin through the 'Plugins' menu in WordPress.

3. Configure settings for WP Prayers Request in the WordPress dashboard by clicking on the 'Settings' option in your WordPress backend.

4. Enter the appropriate information into the form fields on the 'Settings' page.

5. Make sure the appropriate shortcodes are placed on the appropriate pages.

6. Paste shortcodes accordingly:

* Paste this shortcode into the page you would like to use to display your prayer listings: [upr_form]

* Paste this shortcode into the page you would like to use to display your submission form for prayer request: [upr_list_prayers]

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

== Screenshots ==


== Changelog ==

= 2.4.6 =
* Fix email field validation

= 2.4.5 =
* Fix prayer button count

= 2.4.4 =
* Fix error message on prayer request with hyperlinks

= 2.4.3 =
* Custom thank you message after prayer submission

= 2.4.2 =
* Checkbox on form - Do not share this request

= 2.4.1 =
* Block name field on form with hyperlinks

= 2.4.0 =
* Allow HTML Links/Hyperlinks in user email and admin email confirmation message box

= 2.3.9 =
* Add Shortcode installation instructions to Settings

= 2.3.8 =
* Remove validation message on form name input

= 2.3.7 =
* Fix error message with no posts

= 2.3.6 =
* Block prayer requests with hyperlinks

For older changelog entries, please see the [additional changelog.txt file](https://plugins.svn.wordpress.org/wp-prayers-request/trunk/changelog.txt) delivered with the plugin.