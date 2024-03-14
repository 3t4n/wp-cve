=== Limit Login Attempts (Spam Protection) ===
Contributors: wp-buy, mohmmedalagha, osama.esh
Tags: login, security, authentication, anti-spam, firewall, protection, login security, spam protection, Limit Login Attempts
Requires at least: 4.6
Tested up to: 6.4.3
Requires PHP: 5.4
Stable tag: 5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Limit rate of login attempts, including by way of cookies, for each IP. Fully customizable.

== Description ==


Limit the number of login attempts possible both through normal login as well as using auth cookies.

By default WordPress allows unlimited login attempts either through the login page or by sending special cookies. This allows passwords (or hashes) to be brute-force cracked with relative ease.

Limit Login Attempts blocks an Internet address from making further attempts after a specified limit on retries is reached, making a brute-force attack difficult or impossible.


== Basic Features ==
- Limit the number of retry attempts when logging in.
- Configurable lockout timings.
- Email notification of blocked attempts (Detailed email containing all necessary information).
- Notify the user of remaining attempts.
- Report containing all blocked attempts.
- Whitelist/Blocklist of IPs (Support IP ranges).
- Allow/Block Countries.
- Automatically block IP addresses that exceed limit login attempts
- Automatically add IP addresses that exceed blocks limit to the deny list
- Send notifications about blocked retry (Email sent to admins)
- Inform the user about the remaining retries or lockout time on the login page.
- Unlock The Locked users – Easily unlock the locked admin through the email or dashboard.
- Limit the number of retry attempts when logging in per IP.
- Limit the number of attempts to log in using cookies.
- Optional logging and optional email notification.
- Compatible with Google captcha, Captcha Plus & reCaptcha.
- Dashboard gives you an overview of your site's security.
- Enable or disable the plugin functionality
- Enable to disable email notifications
- Compatible with latest WordPress version
- Woocommerce login page protection.
- Wordfence & Sucuri compatibility.
- GDPR compliant.


== Advanced Features (PRO) ==
- All Basic features included.
- Save the password that was used by the hacker (Save part of the password and hide the last three digits).
- Advanced dashboard gives you an overview of your site's security (Charts for the most important reports).
- Block attackers by IP, Country, IP range.
- Mobile Application for the admins to follow up the site security (<a href="https://www.wp-buy.com/wp-content/uploads/apps/login-attempts-app.apk">Download APK</a>).

== Video Description ==

[vimeo https://vimeo.com/585819426]


== Plugin Settings and Reports ==

[vimeo https://vimeo.com/585820422]




== Installation ==
The plugin is simple to install:

1. Download the file `wp-limit-failed-login-attempts.zip`.
2. Unzip it.
3. Upload `wp-limit-failed-login-attempts` directory to your `/wp-content/plugins` directory.
4. Go to the plugin management page and enable the plugin.
5. Configure the options from the `Limit Failed Login` page

== Screenshots ==
1. screenshot 1
2. screenshot 2
3. screenshot 3
4. screenshot 4
5. screenshot 5
6. screenshot 6
6. screenshot 7

== Changelog ==


= 5.3 =

Bug fixing in log report


= 5.2 =

Checking with wordpress version 6.2

= 5.1 =

* Bug fixing in lockout (locked accounts) report (security issiu reported by WPScan) 


= 4.9.1 =

* Bug fixing in log report (security issiu reported by WPScan) - part 3


= 4.9 =

* Bug fixing in log report (security issiu reported by WPScan) - part 2

= 4.8 =

* Bug fixing in log report (security issiu reported by WPScan)


= 4.7 =

* Bug fixing in dashboard & email reports

= 4.6 =

* Bug fixing - Use local flags instead of using third party website

= 4.5 =

* Bug fixing - Remote get issue


= 4.4 =

* Bug fixing - PHP notice message


= 4.3 =

* Bug fixing in login attempts counter

= 4.2 =

* Bug fixing in email alerts

= 4.1 =

* Bug fixing in email alerts


= 4.1 =
* Adding statistics page & new statistics widgets
* Adding a new feature: Block by IP and Range IP
* Bug fixing and enhancements


= 2.8 =
* bug fixing in settings

= 2.7 =
* Compatibility with SMPT plugins

= 2.6 =
* bug fixing in attempts count
* bug fixing in email alerts


= 2.5 =
* Bug fixing in a timezone
* Bug fixing in the lockout timer


= 2.4 =
* Bug fixing in recording attempts

= 2.3 =
* Bug fixing in the email alerts


= 2.2 =
* improvements in reports
* improvements in dashboard widgets


= 2.1 =
* hot fixes in the wp-buy cp page

= 1.9 =
* hot fixes
* improvements

= 1.8 =
* Add one starting page for all of our plugins
* Add links to dismiss the new start page links

= 1.7 =
* Adding new feature (IP blocking)
* Adding new feature (search by IP, country, username)
* Adding new feature (show username and password in the log reports)

= 1.6 =
* Bug fixing - PHP Notice -> Undefined index

= 1.5 =
* Adding username and user role to the log
* Adding search by username, IP, role, country

= 1.4 =
* Email template improvements

= 1.3 =
* Display GEO location in detail for any blocked IP address

= 1.2 =
* Bug fixing in the user permissions
* adding "Vote" message

= 1.1 =
* CSS enhancements

= 1.0 =
* First beta release