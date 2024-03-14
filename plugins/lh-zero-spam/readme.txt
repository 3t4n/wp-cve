=== LH Zero Spam ===
Contributors: shawfactor
Donate link: https://lhero.org/portfolio/lh-zero-spam/
Tags: comments, spam, antispam, anti-spam, comment spam, spambot, spammer, spam free, spam blocker, registration spam
Requires at least: 4.0
Tested up to: 6.0
Stable tag: 1.13
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Zero Spam makes blocking spam comments and registrations easy.

== Description ==

**Why should your users prove that they're humans by filling out captchas? Let bots prove they're not bots with the <a href="http://lhero.org/plugins/lh-zero-spam/">LH Zero Spam plugin</a>.**

LH Zero Spam blocks registration spam and spam in comments automatically without any config or setup. Zero Spam was initially built based on the work by <a href="http://davidwalsh.name/wordpress-comment-spam">David Walsh</a>, but enhanced with simpler code base and unobtrusive JavaScript.

Major features in LH Zero Spam include:

* **No captcha**, because spam is not users' problem
* **No moderation queues**, because spam is not administrators' problem
* **Blocks spam registrations & comments** with the use of JavaScript
* **Blocks buddypress spam registrations** with the use of JavaScript
* **Blocks woocommerce spam orders** with the use of JavaScript

**Like this plugin? Please consider [leaving a 5-star review](https://wordpress.org/support/view/plugin-reviews/lh-zero-spam/).**

**Love this plugin or want to help the LocalHero Project? Please consider [making a donation](https://lhero.org/portfolio/lh-zero-spam/).**

== Installation ==

1. Upload the `lh-zero-spam` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Is JavaScript required for this plugin to work? =

Yes, that's what does the magic and keeps spam bots out.

= I keep getting 'There was a problem processing your comment.' =

Be sure JavaScript is enabled and there are no JS errors.

= Does this work with multisite?' =

Yes it protects from spam wp-register registrations.

= Does this work with Buddypress?' =

Yes as of version 1.08 this will protect from Buddypress registrations. Note is requires the bp_before_account_details_fields hook, must reputable themes include this hook.

= Does this work with Woocommerce?' =

Yes as of version 1.10 this will protect from Woocommerce spam order registrations.

= What is something does not work?  =

LH Zero Spam, and all [https://lhero.org](LocalHero) plugins are made to WordPress standards. Therefore they should work with all well coded plugins and themes. However not all plugins and themes are well coded (and this includes many popular ones). 

If something does not work properly, firstly deactivate ALL other plugins and switch to one of the themes that come with core, e.g. twentyfifteen, twentysixteen etc.

If the problem persists please leave a post in the support forum: [https://wordpress.org/support/plugin/lh-zero-spam/](https://wordpress.org/support/plugin/lh-zero-spam/). I look there regularly and resolve most queries.

= What if I need a feature that is not in the plugin?  =

Please contact me for custom work and enhancements here: [https://shawfactor.com/contact/](https://shawfactor.com/contact/)

== Screenshots ==

== Changelog ==

= 1.0 =
* Initial release

= 1.01 =
* Code Improvement

= 1.02 =
* Better documentation

**1.03 January 28, 2017*  
Rest api support.

**1.04 January 30, 2017*  
xmlrpc support.

**1.05 July 25, 2017*  
class check.

**1.06 September 20, 2017*  
Comment_form backup

**1.07 September 20, 2017*  
Added minimum version of php

**1.08 September 24, 2017*  
Added buddypress support

**1.09 December 17, 2017*  
filemtime for load_file

**1.10 May 17, 2019*  
anonymous function for javascript nonce handling

**1.11 July 28, 2022*  
user wp die where possible

**1.12 October 13, 2022*  
minor buf fix

**1.13 October 13, 2022*  
minor enhancement