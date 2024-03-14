=== BotBlocker ===
Contributors: brandonfenning
Donate link: http://www.lform.com/
Tags: comments, spam, akismet, captcha, bot, comment spam, anti-spam, block, blocker, botblocker, bot blocker, reduce spam, plugin
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 1.0.4

Kills spam-bots, leaves humans standing. No CAPTCHAS, no math questions, no passwords, just spam blocking that stops spam-bots dead in their tracks.

== Description ==

Since the vast majority of Wordpress spam is automated in nature, the goal of BotBlocker is to block comment spam bots and not hinder users. This is accomplished through the use of honeypot technology, which tricks the spambot into identifying itself by performing actions that a human could not. 

**BotBlocker's features include:**

1. Works out of the box to block spam
2. Automatically stops spam-bots from commenting
3. Zero hindrance to users: no CAPTCHAS, math questions, passwords or extra input required
4. Zero false positives and ignores registered users
5. Can be configured to completely block spam-bots or flag comments as spam
6. Spam detection messages can be easily adjusted
7. No javascript or cookies required
8. Hooks into `wp-comments-post.php` to prevent spam-bots from directly submitting spam comments
9. Should work fine with most customized comment forms
10. No API keys required

http://www.lform.com/botblocker/ for more information.

Plugin by [LFORM, a web design company](http://www.lform.com/) in New Jersey.

== Installation ==

1. Upload the `botblocker` folder in the zip file to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. BotBlocker is installed and working! 
4. Additional configuration can be performed by clicking on 'Settings' in the 'Plugins' screen.

== Frequently Asked Questions ==

= Will BotBlocker conflict with my comment form customizations? =
BotBlocker should not interfere with your form customizations, whether you have added fields or removed them. Barring extensive customizations to the core of the comment system, there should be no issues.

= Will BotBlocker conflict with my other comment or spam plugins? =
BotBlocker works by hooking into Wordpress's comment system and should play OK with most plugins, however there is no guarantee. 


== Screenshots ==

1. These are the configuration options available for BotBlocker. While it works out of the box, BotBlocker can be configured in several ways.

== Changelog ==

= 1.0.4 =
* Fixed bug with mechanism that prevents comments from directly being submitted to wp-comments-post.php.

= 1.0.3 =
* Fixed bug that caused logged in users & admins to be flagged as spam bots. Registered users & admins will no longer be filtered by the spam bot system.

= 1.0.2 =
* Fixed debug mode

= 1.0.1 =
* Added screenshot of plugin settings screen
* Tweaked menu option

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.0.3 =
* Upgrade immediately: fixed bug that caused logged in users & admins to be flagged as spam bots.

= 1.0.2 =
* Debug mode fixed, upgrade immediately

= 1.0 =
* Initial release