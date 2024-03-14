=== Simple Google Sitemap ===
Contributors: ixiter
Tags: ask.com, google, msn, sitemap, yahoo, xml
Requires at least: 2.7
Tested up to: 2.7
Stable tag: 1.6

Creates an XML Sitemap, containing Homepage, Articles and Pages

== Description ==

Simple Google Sitemap is a plugin to create automatically an XML Sitemap for your blog and pings it to ASK.COM, Google and MSN, whenever an Article was published or deleted.
You can edit various options for the created sitemap file.

== Installation ==

1. Upload the folder `simple-google-sitemap` to the `/wp-content/plugins/` directory
2. Create an empty file sitemap.xml in your blogs root folder and make it writeable (chmod 666).
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Edit and save the `Simple Sitemap` options in the admin panel and create your first sitemap. You can browse the result at http://your.blog.tld/sitemap.xml.
5. Nothing else to do. From now on, the plugin creates a new sitemap whenever you publish or delete an article and Google will be notified about the new sitemap.

== History ==
= Preposition =
This plugin is similar to Arne Brachold's (arnee) [Google XML Sitemaps Plugin](http://www.arnebrachhold.de/redir/sitemap-home/ "Google XML Sitemaps").
When i ran into trouble from its huge hunger for memory, I decided to write another Google Sitemapper, including all important features, but less memory usage.
It is also my first WP plugin ever. Hope you like and use it. 

= V1.6 - December 20, 2008 =
* New: Yahoo added, since it doesnt require an Application ID anymore to submit a sitemap.
* Improvement: Added numbers to language files to show german decimal number format for priority values.
* Bugfix: Priority values are allways set correctly now

= V1.5 - December 17, 2008 =
* Improvement: Changed class declaration down to PHP 4 compatibility.

= V1.4 - December 17, 2008 =
* Bugfix: Link URLs corrected. Using Blog's permalink settings now
* Bugfix: Markdown in readme.txt fixed. Some underscores needed to be escaped.

= V1.3 - December 15, 20008 =
* Bugfix: 2 items in german translation corrected.
* Bugfix: ask.com ping url corrected
* Bugfix: ask.com ping url corrected
* Improvement: Found and use wp\_remote\_get() now, to ping search engines.
* Improvement: Shows apporved ping results now
* New: Shows ping URLs with the ping results

= V1.2 - December 15, 2008 =
* Bugfix: Didnt update the sitemap file, when a page was published. Now it updates when a new page was published, or a prior published page was edited.
* New: Multilanguage Feature added. Supportet languages are english (default) and german. 

= V1.1 - December 14, 2008 =
* Improvement: Added ASK.COM and MSN to pinged Search engines
* Improvement: Search Enigine Ping uses fopen, file\_get\_contents or curl now. If none of these is possible, it throws a propper notification msg to the user and provides links to ping the services manually.

= V1.0 - December 12, 2008 =
My very first WP plugin ever. It took a few hours to read about WP plugin development and get it to run. Hopefully its bug free. Still some important features missing. 

== Credits ==
* [Nenad Marjanovic and Pytal](http://www.pytal.de/ "pytal.de") - for great hosting and technical support, for sharing knowledge
* [Olaf Prause](http://solscape.astroarts.org "Solscape - German Science Blog") - for beta tests, for exciting nightly googletalks about life, universe and all the rest.
* The moderators of "Planet Freak", the worlds funiest Skype talkshow with 4 moderators and various brave single guests - for entertainment