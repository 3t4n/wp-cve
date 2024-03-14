=== Conditional CAPTCHA ===
Contributors: solarissmoke
Donate link: http://www.rayofsolaris.net/donate/
Tags: anti-spam, captcha, comments, spam, bot, robot, human, reCAPTCHA, Akismet
Requires at least: 4.0
Tested up to: 5.0
Stable tag: trunk

Asks commenters to complete a simple CAPTCHA if they don't have a previously approved comment, or if Akismet thinks their comment is spam.

== Description ==

This plugin has two modes - basic and Akismet-enhanced.

**Basic mode**: the plugin will serve a CAPTCHA to all commenters that aren't logged in and don't have a previously approved comment. Repeat commenters will never see a CAPTCHA.

**Akismet-enhanced mode (recommended)**: the plugin will serve a CAPTCHA only if Akismet identifies a comment as spam (much less frequently than the basic mode). Just install an activate [Akismet](http://wordpress.org/extend/plugins/akismet/) to enable this mode.

Note that a CAPTCHA will only appear **after** a comment is submitted. When a commenter is served a CAPTCHA:

* If they fail, then the comment will be automatically discarded or trashed (and won't clutter up your spam queue).
* If they pass, it will be allowed into the spam queue (or approved, if you so choose). This means that any false positives from Akismet will be easily identified without you having to trawl through all the spam comments manually.

Most genuine commenters will be able to comment on your site hassle-free, without ever seeing a CAPTCHA.

The default CAPTCHA is a simple text-based test. There is also the option to use [reCAPTCHA](http://www.google.com/recaptcha) if you want something more robust (free API key required). You can also style the CAPTCHA page to fit with your own WordPress theme.

If you come across any bugs or have suggestions, please use the plugin support forum or [email me](http://rayofsolaris.net/contact/). I can't fix it if I don't know it's broken! Please check the [FAQs](http://wordpress.org/extend/plugins/wp-conditional-captcha/faq/) for common issues.

Thanks to the following people for contributing translations of this plugin: Belorussian - [Marcis G](http://pc.de), Brazilian Portuguese - Stefano, Czech - [Ted](http://trumplin.com/), Danish - Jesper, Dutch - [Rene](http://wpwebshop.com/books/), Estonian - [Itransition](http://www.itransition.com), Finnish - Jani, French - [Laurent](http://android-software.fr), German - [Jochen](http://jochenpreusche.com), Hindi - [Outshine Solutions](http://outshinesolutions.com), Hungarian - [Gyula](http://www.televizio.sk), Italian - [Gianni](http://gidibao.net), Lithuanian - Mantas, Polish - [Pawel](http://www.spin.siedlce.pl), Romanian - [Lotus](http://simplu.mixnet.ro), Russian - [Ivanka](http://www.everycloudtech.com/), Spanish - [Reinventia](http://www.reinventia.net), Swedish - [Hugo](http://www.umu.se), Turkish - Tony, Ukranian - [Ivanka](http://www.everycloudtech.com/). Some of these translations are a bit out of date - updates welcome!

== Frequently Asked Questions ==

= I've installed it, now how do I check that it works? =

You can try posting a spammy comment on your blog (make sure you're logged out) to check that it works, and to see what it looks like. Posting a comment with `viagra-test-123` in the author/name field will always get it flagged by Akismet. If you are using basic mode, make sure you use a name and email that have not got a previously approved comment.

= Does this plugin work with other comment form modification plugins, or with themes that use Javascript to handle comment submission? =

*Conditional CAPTCHA* relies on WordPress' native form handling procedures. This means it will not work with plugins or themes that generate and process their own comment forms. Such plugins include WP AJAX Edit Comments, tdo-miniforms, Backtype and Contact Form 7. **If comment submissions on your site are processed using AJAX, then the plugin will not work.**

= How long does the commenter have to complete the CAPTCHA? =

There is a time limit of 10 minutes for the CAPTCHA to be submitted, otherwise it will be ignored even if it is correct.

= What does the option to disable Akismet's comment history do? =

Akismet stores a history for all comments on your site. It records whether or not it flagged the comment as spam, and any changes that you or other administrators make to the comment's status afterwards. This history is **never** deleted, and (in my view) just bloats your WordPress database without being at all useful. Selecting this option will prevent Akismet from storing comment histories. Note that this is feature is somewhat experimental, and not endorsed by the folks who wrote Akismet.

= Didn't you say before that the plugin works with TypePad Antispam? =

Yes, but not any more. The TypePad Antispam plugin hasn't been updated in over 4 years, and is not compatible with the latest version of WordPress.

== Changelog ==

= 4.0.0 =
* Drop support for reCAPTCHA v1 API which has been shut down.

= 3.7.1 =
* Fix handling of array inputs that other plugins might inject into the comment form.

= 3.7 =
* Add support for the new No CAPTCHA ReCAPTCHA

= 3.6.3 =
* Fix PHP warnings on CAPTCHA page when other plugins have injected arrayed content into the contact form.

= 3.6.2 =
* Allow line breaks in custom prompt message.
* Add a "Try Again" button for failed CAPTCHAs, instead of telling users to go back in their browser.

= 3.6.1 =
* Add CSRF protection to settings page.

= 3.6 =
* Revert all the changes made in version 3.5. It breaks way too many things.

= 3.5 =
* Changed logic to give priority to WordPress' built-in discussion options (especially moderation keywords/blacklist).

= 3.4.2 =
* Added HTTPS compatibility for ReCAPTCHA.

= 3.4.1 =
* Bugfix: make sure that WordPress can set commenter cookies when a CAPTCHA is completed correctly.

= 3.4 =
* Added a basic mode. The plugin no longer needs Akismet in order to work!

= 3.3 =
* Added some options to tweaks Akismet's behaviour: prevent history and prevent checking comments from logged-in users
* Fix to ensure that XML-RPC requests are not intercepted
* Introduced some compatibility checking

= 3.2.6 =
* Don't intercept comments submitted via AJAX.

= 3.2.5 =
* Add workaround for a bug in the latest version of Akismet, where comments from administrators can be flagged as spam.

= 3.2.4 =
* Added the option to customise the CAPTCHA prompt text.
* Minor tweaks to the settings page.

= 3.2.3 =
* Performance improvement to reduce size of plugin options
* Tweaked settings page to be more user friendly

= 3.2.2 =
* Minor changes to the behaviour of the plugin, as a result of changes in the latest version of Akismet.

= 3.2.1 =
* Bugfix: settings page Javascript caused errors when using jQuery < 1.6

= 3.2 =
* Added the option to leave comments for unsuccessful CAPTCHAs in the spam queue (provided the pass action something different)
* Bugfix: Options from previous versions of the plugin were not being properly upgraded

= 3.1.1 =
* Bugfix: Admin page Javascript was not compatible with jQuery >1.5.2

= 3.1 =
* Bugfix: Use blog character set instead of defaulting to UTF-8
* Better preview of CAPTCHA page
* Added basic validation of reCAPTCHA API keys
* Minor usability improvements

= 3.0 =
* Bugfix: don't mangle Unicode characters when submitting a CAPTCHA. Thanks to Mantas for pointing this out.

= 2.9 =
* Updated to fix issue with Akismet version 2.5.0 and Wordpress 3.0.3 when set to trash failed comments

= 2.8 =
* Added the ability to customise the appearance and language of reCAPTCHA

= 2.7 =
* Ensure that passed CAPTCHAs are reported as false positives to Akismet/TypePad Antispam. Thanks to [Kevin](http://www.investitwisely.com) for the suggestion.
* Added the option to place passed comments in the moderation queue

= 2.6 =
* Added support for non-js reCAPTCHA
* Updated reCAPTCHA API interface
* Modified upgrade routine because of changes to plugin update handling in Wordpress 3.1

= 2.5 =
* Added the ability to preview the CAPTCHA page from within the administration interface
* Minor performance optimisations
* Raised minimum Wordpress version to 2.8

= 2.4 =
* Bugfix: don't intercept spammy pingbacks and trackbacks. Thanks to [Kevin](http://www.investitwisely.com) for reporting this.

== Installation ==

1. Upload the wp-conditional-captcha folder to the `/wp-content/plugins/` directory (or use the Wordpress auto-install feature)
2. Activate the plugin through the 'Plugins' menu in WordPress
3. The settings for the plugin can be accessed from the Plugins administration menu.
