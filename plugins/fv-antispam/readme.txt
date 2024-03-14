=== FV Antispam ===
Contributors: FolioVision
Tags: antispam, spam, akismet
Requires at least: 3.5
Tested up to: 6.3
Stable tag: trunk

FV Antispam is a powerful and simple antispam plugin. It moves any spambot comments directly to trash and allows Akismet to just deal with human spam.

== Description ==
FV Antispam is a powerful and simple antispam plugin. FV Antispam moves any spambot (machine written) comments directly to the trash and allows Akismet to just deal with human spam.
What makes FV Antispam better than any other solution is the very low CPU load. FV Antispam will not burden your hosting or slow down your server. Other effective antispam plugins may get your hosting cancelled. Really. It happened to us, that's why we wrote FV Antispam.

= The Problem =

Our studies on our own sites have shown that for every 50 human spam comments a popular weblog will have up to 10,000 machine generated spam. With FV Antispam, Akismet finally becomes a usefull tool again allowing you to browse your spam folder and pull out any Akismet false positives. If you use just Akismet, bots fill your whole spam folder with thousands of comments. It's nearly an impossible task to browse through hundreds or thousands of comments to find one or two false positives.

= Why choose FV AntiSpam? =

* Dead simple. Just install, turn on and go. FV Antispam doesn't need configuration. No redundant and dangerous options or cryptic choices.
* Effective.
* Reliable.
* Zero false positives.
* Virtually no CPU server load.
* Works with the most powerful Wordpress form processor - [Filled In](http://wordpress.org/plugins/filled-in/). Utilizes Akismet for it as well if installed. Allows you to enter custom questions for extra protection.

= How does FV AntiSpam work so well? =

* No detectable signature for spam bots
* Blacklisted comments are put into trash
* The spam is moved directly to trash
* Spam pings and trackbacks are moved directly into trash if detected by Akismet
* Works hand in hand with Akismet, not trying to replace the collective system but supplement it.
* Stops only the machine spam
* Doesn't stop human spam which Akismet will move to spam folder.
* Keeps the spam folder almost empty so you can quickly go through the spam folder to rescue any Akismet false positives. This is impossible without FV Antispam as the spam folder of any popular site would have thousands of bot posted spam every week or even every day.

[Support and more information](http://foliovision.com/seo-tools/wordpress/plugins/fv-antispam)

== Changelog ==

= 2.7 =

* PHP 8.2 fixes
* WordPress 6.1 fixes

= 2.4.3 =

* Bugfix - comments going into trash after 2.4.2 upgrade

= 2.4.2 =

* Bugfix - fix for PHP 7.2 deprecation warnings

= 2.4.1 =

* Bugfix - fix for PHP7 - constructor name can't be the same as the class name

= 2.4 =

* Bugfix - important fix for Wordpress 4.2 - the hidden textarea needs not to be set as required

= 2.3 =

* Feature - save your Akismet credits by not checking the machine bot spam comments!
* Bugfix - settings screen JS fixed

= 2.2.4 =

* Fix - WPEngine changed the comment post URL, updating the plugin to handle this properly. Check your trash for non-spam comments!

= 2.2.3 =

* Bugfix - custom question not affecting AJAX Filled in forms
* Bugfix - fix for Immensely theme which uses JavaScript to validate the forms

= 2.2.2 =

* Bugfix - bad comment flood filter in use removed

= 2.2.1 =

* Fix for s2Member - no registration form protection for now

= 2.2 =

* Filled in Antispam changed - uses hidden field + Akismet (install this plugin separately). If you still get spam, use customizable set of questions, see settings!
* Fix for Jetpack Comments (Invalid token)
* Fix for bad jQuery script on registration screen
* Fix for Filled in forms in widget and excerpt texts.

= 2.1 =

* Fix for JS error in IE7-8. This error forced IE7-8 users to answer the security question manually.

= 2.0 =

* Added JavaScript protection (used for both comments and Filled in)
* Added protection against spam user registrations
* Added protection for FAQ-Tastic
* Filled in protection - spam submissions go into failed submissions
* Filled in now uses Akismet to check the submission (just install the plugin)
* Fix for templates which pre-fill the comment field with some text
* Fix for templates which pre-fill comment fields with their labels using JavaScript

= 1.9 =

* Spam pings and trackbacks go to trash if they are detected by Akismet
* Bugfix in "Filled In" plugin checking SQL

= 1.8.4 =

* Added protection for WP registration form
* Added removing of trash comments older than 1 month
* Added option to hide trackbacks in Comments in admin section

= 1.8.3.1 = 

* Admin interface display issues fix
* The Events Calendar bugfix (comment forms were not working on events)
* Bugfix in Filled in collision detection

= 1.8.3 = 

* New function - protect [Filled In](http://urbangiraffe.com/plugins/filled-in/) forms against spam
* New function - display comments by default when you enter Comments section in wp-admin Dashboard
* New function - show counts of comments and pingbacks separately in Comments section in wp-admin Dashboard

= 1.8.2 = 

* New function - redirect pingbacks and trackbacks notifications to different email address
* Or disable pingbacks and trackbacks notifications at all.

= 1.8.1 = 

* Bugfix for some templates.

= 1.8 = 

* First public release

== Screenshots ==

1. FV Antispam settings
2. Enhanced comments and pingback counters (showing comments/pingbacks)

== Installation ==

You can use the built in installer and upgrader, or you can install the plugin
manually.

**Test** comment submission as non-logged in user after the plugin is installed! Read the [FV Antispam Testing guide](http://foliovision.com/seo-tools/wordpress/plugins/fv-antispam/installation)!

== Frequently Asked Questions ==

= Why do you put the detected spam messages into trash? =

This way your spam folder is not overwhelmed with all the obvious spam messages. So you can look at this folder to check the spam messages detected by Akismet for example. And with new Wordpress versions, you can also check the trash folder, to make sure that our plugin works correctly and only deletes what's really a spam.

= Why are you putting blacklisted comments into trash? =

Same reason as above question, if a comments is already blacklisted, you don't want to think about it in spam folder.

= I have a problem with my Filled in forms and I suspect it's caused by your plugin =

When you are logged in you should see a notice above each protected Filled in form. You will also see a warning in wp-admin Dashboard if there is any conflict with your Filled in forms.

= Which Comment Preview plugins do you recommend to use with FV Antispam? =

[Live Comment Preview](http://wordpress.org/extend/plugins/live-comment-preview/) works, but you have to change the textarea ID in the live-comment-preview.php file on line 124. You can find out that the ID is by looking into the source code of the page with comment form on it. It will be an unique string of seemingly random numbers and letters like "a7391152e", so use that.

[jQuery Comment Preview](http://dimox.net/jquery-comment-preview-wordpress-plugin/) is not so lightweight as the Live Comment Preview, but you can set the ID in options, so you don't have to edit files.

= I'm seeing two comment textareas with this plugin! =

Please check your template HTML and CSS for that element. Ideally it should use standard WP [comment_form](http://codex.wordpress.org/Function_Reference/comment_form) function and the same kind of CSS as the default template (TwentyTen). It needs to have id="comment".

= All the comments go into trash! =

Same as above - make sure you use standard WP functions for display of comment forms. When you check out HTML source of your article page with comments bellow it, you should see two textareas - one with ID comment and the other with ID consisting of random numbers and letters. The first one should be set to invisible by this plugin.
