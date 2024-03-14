=== Astounding Spam Prevention ===
Tags: spam,  antispam, anti-spam, spam blocker, block spam, signup spam, comment spam, spam filter, registration spam, spammer, spammers, spamming, comment, comments, protection, register, registration, security, signup, user registration spam, wonderful spam, lovely spam, wonderful spam
Tested up to: 6.2
Contributors:  kpgraham@gmail.com
Requires at least: 4.0
Stable tag: 1.19
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://www.kpgraham.com/BuyMyBook.html


Very effective anti-spam plugin that eliminates comment spam, and registration spam. Combines many effective methods for identifying spammers and keeping them off your site. THe plugin is designed to be very light weight so it will not interfere with the operation of your website. Easy to install and default options work perfectly on most sites. Will not lock you out. No Captchas needed. Works with JetPack Protect.

== Description == 
Using the most effective features of other spam programs. Astounding Spam Prevention effectively guards against comment spam and registration spam without the use of a Captcha.
This is a fork of the original Stop Spammers Spam Prevention and uses some of the code, but it is greatly simplified so it is not as aggressive and will not prevent a user from logging in. It does not interfere with jetpack and does not know about WooCommerce so there are no conflicts. Unlike Stop Spammers it does not check logins, but only checks registrations and comments.
It also repairs many bugs that I found in the Stop Spammers plugin and has many new methods for detecting spam.

== Donate ==
Please buy one of my books and give it a good review. I worked hard on these books, and they are worth reading.
My Author Page at Amazon: https://amzn.to/42BjwXv

<br>



 
== Installation ==
1. Install the plugin using "add new" from the plugin's menu item on the WordPress control panel. Search for Stop Spammers and install.
OR
1. Download the plugin.
2. Upload the plugin to your wp-content/plugins directory.
3. Activate the plugin.
4. Under the settings, review options that are enabled. The plugin will operate very well without changing any settings. 

== Changelog ==

= 1.19 =
* Tested under WordPress 6.3
* CheckSFS seems to be missing a valid index. If it keeps causing problems then uncheck it in the settings.

= 1.18 =
* Tested under WordPress 6.2
* Created warning for sites that cannot reach spamhaus.org. (Check your PHP error logs!)

= 1.17 =
* Tested under WordPress 5.7.1

= 1.16 =
* Tested under WordPress 5.4.1

= 1.15 =
* Fixed bug in session speed check.

= 1.14 =
* Added Phishing sites check.
* added Myip.ms Blacklist IPs list check 
* moved Cache clear to the cache tab.

= 1.13 =
* Fixed a bug in the SFS module.
* Added the ability to clear the cache.
* Fixed the Ajax calls in the settings. Brought it into this decade's standards. jQUery definitely doesn't work on many sites.

= 1.12 =
* Beta WP broke jQuery so I added some code to make the admin options interface javascript load depending on jQuery. Turned out it is a beta issue that broke many things including JetPack.
* Limited length of error message. Some spammers a filling in the author name or email with thousands of characters and it fills up the log quickly.
* renamed the .javascript files back to .js. I am worried that the file type may be blocked.
* replace jquery calls with custom code. Decided that the jQuery issues with WP are not worth it.

= 1.11 =
* Added Red Herring form option.

= 1.10 =
* Removed URI from logs. It took up too much room and was only interesting when debugging.
* Added aditional check for action=register inside post in case registrations (bbpress) uses a different form.

= 1.09 =
* Added back Stop Forum Spam check. This was failing for a while. Needs to be monitored.
* Divided settings into recomended and optional.

= 1.08 =
* Fixed some typos in descriptions.
* Added a list to block VPN servers that are known spammers.

= 1.07 =
* Fixed some typos in descriptions.
* Added a list of common spam TLDs.
* Added option to show all reasons for rejection, not just the first one.

= 1.06 =
* Changed TLD checks to report the actual TLD even if not 3 characters.
* Made the allowed tld list editable.
* Change order of checks to put sessions checks near the end of the list.

= 1.05 =
* Fixed typo checking for cached entries. It was letting them through.

= 1.04 =
* Stopped showing cache hits in log. Most spammers try 4 times to do their thing and it was filling up the logs.
* Added version number to settings page.
* Added settings to plugin summary on plugins tab.
* Added warning for larger log files.

= 1.03 =
* Added a check for generated bad neighborhood list that the author maintains. List is newly discovered IP addresses only.

= 1.02 =
* Changed the log file name to begin with a '.' to prevent web servers from delivering log directly.
* Changed display order of log file to most recent first.
* Changed deny message to type 403 "forbidden".

= 1.01 =
* Fix JavaScript error on options page.
* Added new default spamwords.
* FIxed DNSBL error.

= 1.00 =
* Initial version. Forked many anti spam methods from original plugin "Stop Spammer Registrations Plugin". 
* added 9 new spam prevention methods and fixed bugs in 4 others.
* Redesigned settings pages using easier updating and editing.
* Uses expandable log file that can grow to fit. 
* Caching identifies the original source of the spam for easier troubleshooting.
* Rewrote the load process to limit resource usage. Uses "lazy loading" to avoid memory usage when the plugin is not active.
* Fixed bugs in plugin uninstall procedure.. 


