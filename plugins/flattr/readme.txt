=== Flattr ===
Contributors: aphex3k, VoxPelli
Donate link: https://flattr.com/donation/give/to/der_michael
Tags: flattr, donate, micropayments
Requires at least: 3.3
Tested up to: 4.1.1 
Stable tag: 1.2.2

This plugin allows you to easily add a Flattr donation buttons to your blog and blog posts.

== Description ==

Flattr was founded to help people share money, not only content. Before Flattr, the only reasonable way to donate has been to use Paypal or other systems to send money to people. The threshold for this is quite high. People would just ignore sending donations if it wasn't for a really important cause. Sending just a small sum has always been a pain in the ass. Who would ever even login to a payment system just to donate €0.01? And €10 was just too high for just one blog entry we liked...

Flattr solves this issue. When you're registered to flattr, you pay a small monthly fee. You set the amount yourself. In the end of the month, that fee is divided between all the things you flattered. You're always logged in to the account. That means that giving someone some flattr-love is just a button away. And you should! Clicking one more button doesn't add to your fee. It just divides the fee between more people! Flattr tries to encourage people to share. Not only pieces of content, but also some money to support the people who created them. With love! 

**Flattr requires an account at flattr.com!**

== Installation ==

1. Upload the folder 'flattr' to your server in the folder '/wp-content/plugins/'
2. Go to the WordPress control panel and find the 'Plugins' section
3. Activate the plugin 'Flattr'
4. Select your default category (which usually would be 'text' if you have a normal blog), select your default language and type in your Flattr username
5. When writing or editing a blog post you have the ability to select category and language for this specific blog post.
6. Live long and prosper. :)

== Frequently Asked Questions ==

= How do I use the shortcode? =

The shortcode is [flattr url="http://example.com/"]. It has many attributes but the url attribute is the only one required.

Supported attributes are:

* **url** - the URL that should be flattred. *Required*
* **type** - overrides the default setting of the button style to use. Can be either "js", "compact", "text", "image" or "url".
* **popout** - overrides the default setting on whether the popout should be shown. Should be either "yes" or "no".

Additional autosubmit related attributes:

* **user** - the username of the user owning the thing - needed for autosubmits.
* **hidden** - overrides the default setting on whether an autosubmit of the thing should be hidden. Should be either "yes" or "no".
* **language** - overrides the blog language as the language of an autosubmit of the thing. Should be a language code like en_GB.
* **category** - overrides the default category for an autosubmit of the thing. Should be one of the [supported ones](http://developers.flattr.net/api/resources/categories/).
* **title** - the title used in an autosubmit of the thing.
* **description** - the description used in an autosubmit of the thing.
* **tags** - a comma separated list of tags that's used in an autosubmit of the thing.

For options that use "yes" or "no" you can instead of "no" use "n" or "off" - any other value will be interpreted as "yes".

== Upgrade Notice ==

= 1.2.0 =
Flattr buttons now detectable by browsers, site itself can now be flattred, a new shortcode and widgets is available etc.

== Changelog ==

= 1.2.2 =
* tried to address known issue with PHP 7. Thanks to Sebastian Reimers and Tim Pritlove for feedback.

= 1.2.1 =
* New Feature: Nicer, modern-looking static buttons for both pages and feed with support for "HiDPI" displays.

= 1.2.0 =
* New Feature: The payment links in RSS/Atom can now be added without adding the graphical buttons and are now on by default
* New Feature: Payment links are now not only included in feed entries - they are now also by default included in the head-tag of entry pages which is usable for eg. browser extensions to detect the existence of a flattr button.
* New Feature: The site itself can now be become flattrable - both feeds and the frontpage will by default in new installs have a payment links for the site itself. Existing pages needs to activate the "Make frontpage flattrable" option.
* New Feature: Added a widget with a Flattr button for the entire site.
* New Feature: Added a [flattr] shortcode for easy inclusion of Flattr buttons in posts and pages
* New Feature: A post can now specify a custom URL for the Flattr button to flattr instead of the post itself. Thanks for the patch [Erik](http://tapiren.se/2012/02/18/wordpress-flattr-plugin-1-0-1-custom-urls/)!
* New Feature: Made it possible to set a date from which the user specific button setting should be activated
* Fix: User specific buttons can now be activated for all content
* Fix: No longer prevents caching due to needless session initialization
* Fix: Settings that can be overriden by a user setting is now by default not saved in a post but rather the site setting or user setting is instead picked when the button is viewed. This means that user settings for content type and language will now be respected.
* Fix: Users with double encoding troubles of payment metadata in their feeds can now disable the encoding through the settings.
* + more minor fixes and tweaks

= 1.1.1 =
* Added support for the new button popout. 
* Replaced getBasePath() with plugin_dir_path()

= 1.1.0 =
* New Feature: Added support for WordPress uninstall feature. To use it you deactivate and delete the plugin from the plugin list.
* Fix: Descriptions sent to Flattr are now assembled in a totaly new way as a custom kind of excerpts that are limited to maximum 30 words
* Fix: The javascript loaded from Flattr.com is now loaded asynchronously
* Fix: The javascript loaded from Flattr.com is now only loaded in the frontend when dynamic buttons are used

= 1.0.2 =
* Fix: "Include in RSS/Atom feeds" setting wouldn't save

= 1.0.1 =
* Fix: Button type wasn't respected

= 1.0.0 =
* New Feature: Add additional tags to Flattr buttons to make your things appear better in the Flattr catalog. Defaults to add the "blog" tag.
* Fix: The payment links in RSS/Atom feeds will now point directly to Flattr.com to make it easier for clients to detect flattrable posts
* Fix: Compatibility with WP_DEBUG - many PHP warnings and notices has been fixed
* Fix: Some adjustments to better support PHP versions prior to 5.3 has been made
* Fix: The plugin has been stable for a long time – it's time to have the version number reflect that - welcome to 1.0.0!

= 0.99.3 =
* Fix: Feeds should now validate

= 0.99.2 =
* Fix: Wizard should work with PHP 5.2 now

= 0.99.1 =
* Important: Please skip this update if the current installed version works for you
* Important: Due to API changes all registered Flattr apps must be re-created and re-authorized (which should work like a charm now!)
* New Feature: complete rewrite for new Flattr API v2
* New Feature: new oAuth wizard
* New Feature: Payment Link entities for Atom and RSS feeds
* Fix: user based flattr buttons
* Removed: Tabbing

= 0.9.25.5 =
* Fix: Erroneous escaping in advanced thing submit (by qnrq)

= 0.9.25.4 =
* New Feature: Initial test with WP 3.2 passed
* Fix: saving option for user based flattr buttons

= 0.9.25.3 =
* Fix: typo in check time of post before autopublishing

= 0.9.25.2 =
* Fix: fixed a typo in the code, thanks to F. Holzhauer

= 0.9.25.1 =
* New Feature: Changelog Preview
* New Feature: Explicit warning messages about missing functionality
* Fix: Check time of post before autopublishing

= 0.9.25 =
* Requires at least Wordpress 3.0, tested against Wordpress 3.1.4
* New Feature: personalized Flattr buttons for every blog author
* New Feature: choose whether Flattr plugin handles excerpts or Wordpress
* New Feature: advanced feedback form
* New Feature: select JavaScript, static image or static text button
* Fix: buttons disabled for the post don't show up in the feed anymore
* Fix: Wordpress admin dashboard external resources fix
* Fix: trying to suggest the callback domain more reliably

= 0.9.24 =
* Fix: replaced the connect link for basic connect with Flattr with a text box where you enter your username yourself

= 0.9.23.1 =
* New Feature: reenabling auto-submit feature as soon as advanced account setup is complete.
* Fix: raising compatibility alongside other oauth plugins (Twitter Tools, etc.)

= 0.9.23 =
* New Feature: the Feed button can now be disabled
* Fix: fixed a bug that accidentally deletes authorization keys while "Save Changes"

= 0.9.22.2 =
* Plugin basic functionality will work even though cURL is not available

= 0.9.22.1 =
* Bugfix release
* fixed empty admin dashboard

= 0.9.22 =
* Wordpress 3 MS (ex-MU) support
* Using latest Flattr REST API 0.5

= 0.9.21.1 =
* Bugfix release
* Reauthorizing and Reconnection working.

If you rely on full flattr functionality for your blog you might want to consider skipping this version.
= 0.9.21 =
* The Javascript button now validates against w3c html validator. Best regards to Tim Dellas.
* HTTP/HTTPS callback fix(?)
* Integrate FlattRSS plugin
* Integrate Flattr Widegt plugin
* New Admin Dashboard with tabbed navigation

= 0.9.20 =
* plugin programmer changed `;)`
* adressing the borked excerpt behaviour
* Flattr plugins dashboard pages are moved to a seperate submenu

= 0.9.19 =
Dont show Flattr button on password protected posts until the password has been accepted.

= 0.9.18 =
Will now use version 0.6 of the JS API.
Will load the JS API using https only if the blog itself is using https. This should make the button load a bit faster for most of you.

= 0.9.17 =
Fixed PHP5 detection, will now show a message rather than throwing error on PHP4.

= 0.9.16 =
New release due to changes lost in the last release.

= 0.9.15 =
Now applies the 'flattr_button'-filter to the Flattr button to let other plugins modify it.
Used the 'flattr_button'-filter as a quick fix to work better with the ShareThis plugin.

= 0.9.14 =
Made it easier to connect with a Flattr account.
Now possible to put the Flattr button before the content in posts.
User ID is now the username.

= 0.9.13 =
Escaping of quotation marks in tags and title. Tags are now stripped from titles.

= 0.9.12 =
The plugin now uses the ver 0.5 of the JS API.

= 0.9.11 =
Excerpt length fixed
When auto inject into posts or pages are not selected, don't add the filter

= 0.9.10 =
Yet another excerpt fix
Added ability to exclude button from a single post/page
Added flattr settings to pages

= 0.9.9 =
Fixed empty excerpts

= 0.9.8 =
Fixed tags

= 0.9.7 =
* RSS excerpt fix

= 0.9.6 =
* Button didn't show up when using manual calls from templates.

= 0.9.5 =
* Flattr button is no longer added to rss feeds.

= 0.9.4 =
* fixed option to disable button for pages.

= 0.9.3 =
* fixed language bug. Sorry about the frequent updates :)

= 0.9.2 =
* fixed the url to load.js.

= 0.9 =
* Fixed a bug where including files would sometimes break other plugins.
* Will now give error if the plugin isn't compatible with the installed version of WordPress or PHP.
* Added support for hidding things from listings on flattr.com.
* Will warn users that enter their user name instead of their user id.
* Added support for the compact button.

= 0.8 =
* Cleaned up the code
* Added option for disabling the button on a per post basis

= 0.71 =
* Modified plugin to not use short php open tags

= 0.7 =
* Changed category setting to select box instead of input field.
* Added setting for default language.
* Added ability to edit category and language settings per post.

= 0.6 =
* httpvh (Smart YouTube) urls are now stripped when creating excerpt from post content.

= 0.5 =
* Fixed a bug that caused blog posts to display incorrectly when no excerpt was entered. 

= 0.4 =
* First public version
