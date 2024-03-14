=== Google News ===
Contributors: Olav Kolbu
Donate link: http://www.kolbu.com/donations/
Tags: widget, plugin, google, news, google news, rss, feed
Requires at least: 2.3.3
Tested up to: 2.8.2
Stable tag: trunk

Displays news items from selectable Google News RSS feeds, 
inline, as a widget or in a theme. Multiple feeds allowed. 
Query filters and caching.

== Description ==

Google aggregates news from over 4500 news sources, updated
continously. The results can be retrieved as a number of 
RSS feeds, where you can create your own specific feed by
specifying one of more than 40 regions/languages, and an
optional topic ranging from Domestic to Most Popular to
Entertainment. Currently there are nine topics, including,
of course, All. In addition to this, any feed can be filtered 
through a search query so that only news items matching your 
query will be shown. Note that not all combinations of 
region/language and topic has been enabled by Google but
it should degrade gracefully.

This plugin works both as a widget, as inline content
replacement and can be called from themes. Any number of 
inline replacements or theme calls allowed, but only one 
widget instance is supported in this release.

For widget use, simply use the widget as any other after
selecting which feed it should display. For inline content
replacement, insert the one or more of the following strings in 
your content and they will be replaced by the relevant news feed.
For theme use, add the do_action function call described below.

1. **`<!--google-news-->`** for the default feed
1. **`<!--google-news#feedname-->`**

Shortcodes can be used if you have WordPress 2.5 or above,
in which case these replacement methods are also available.

1. **`[google-news]`** for the default feed
1. **`[google-news name="feedname"]`**

Calling the plugin from a theme is done with the WP do_action()
system. This will degrade gracefully and not produce errors
or output if plugin is disabled or removed.

1. **`<?php do_action('google_news'); ?>`** for the default feed
1. **`<?php do_action('google_news', 'feedname'); ?>`**

Enable plugin, go to the Google News page under 
Dashboard->Settings and read the initial information. Then 
go to the Google News page under Dashboard->Manage and 
configure one or more feeds. Then use a widget or insert
relevant strings in your content or theme. 

Additional information:

The available options are as follows. 

**Name:** Optional feed name, that can be used in the 
widget or the inline replacement string to reference
a specific feed. Any feed without a name is considered
"default" and will be used if the replacement strings do
not reference a specific feed. If there are more than
one feed with the same name, a random of these is picked
every time it is used. This also applies to the default
feed(s). 

**Title:** Optional, which when set will be used in the
widget title or as a header above the news items when 
inline. If the title is empty, then a default title
of "Google News : &lt;region&gt; : &lt;feed type&gt;" is used. Note
that as per Google Terms of Service it is a requirement
to state that the news come from Google.

**News region:** A dropdown list of 40 choices, determining
the region/language of the feed. 

**News type:** Another dropdown list, determining what type of
news you are after. Sci/Tech, Business, Health etc.

**Output type:** Some Google feeds come with just text, 
some pictures or pictures on nearly every news item. Chose
which one you want here.

**News item length:** Short or long. The short version is really just 
the news item title as a one liner but probably the one most 
WP admins will use. The long version is a 3-4 line teaser that 
has been severely stripped of useless markup that Google insists 
on passing along, including tables, links, colour/font/style
settings etc. I've tried to clean it up so it won't mess up your 
theme. For the short version, the long text without html tags is 
available as a mouse rollover/tooltip.

**Max items to show:** As the title says, if the feed has
sufficient entries to fulfil the request. 

**Optional query filter:** One of the most important parts of
the Google News RSS Feed is the ability to filter the news
with your very own search query. Get relevant, up to date
news on the exact topic you want. Note that if you add a
search query, then the short item length will include an 
"all N news articles" link curtesy Google. If you choose
to add a query, then you most likely want to set a title
as well. To explain to the viewer what kind of news you have
selected for them to see. The News Query isn't like a standard
Google Query, so you can't use || between words to search
for one or the other. So IFF you start your query with
the word **OR** then the rest of the words will be or'ed
together. I.e. a query string of 'OR this that' will look
for news containing either this or that. This is really 
just a test, so the actual query language used here may change in 
future versions. 


**Cache time:** The feeds are now fetched using WordPress 
builtin MagpieRSS system, which allows for caching of feeds
a specific number of seconds. Cached feeds are stored in
the backend database.

Clicking on a news item will of course take you via Google to
the news site with the relevant article, as per Google Terms of Use.

If you want to change the look&feel, the inline table is 
wrapped in a div with the id "google-news-inline" and the
widget is wrapped in an li with id "google-news". Let me 
know if you need more to properly skin it.

**[Download now!](http://downloads.wordpress.org/plugin/google-news.zip)**

[Support](http://www.kolbu.com/2008/04/07/google-news-plugin/)

[Donate](http://www.kolbu.com/donations/)


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Unzip into the `/wp-content/plugins/` directory
1. Activate the plugin through the Dashboard->Plugins admin menu.
1. See configuation pages under Dashboard->Settings, Dashboard->Tools and on the widget page.

Note if you're upgrading from a previous release, there may be
some strangeness the first time you edit an old feed. Try again
and it will work. Or delete the feed and create again, guaranteed
fix. :-)

== Screenshots ==

1. Widget in action under the Prosumer theme. Note the mouseover showing additional text from the news item.
2. Small part of the admin Manage page for the plugin.
3. Inline example under the Prosumer theme, replacing &lt;!--google-news--&gt; in content.

== Changelog ==

= 2.5.1 =
* Plugin disappeared from WP.org, maybe this upgrade will reinstate
* Converted to new ChangeLog syntax
* No other changes from 2.5
= 2.5 =
* Added 25 new languages/locations
= 2.4.1 =
* Fixed minor markup glitch
= 2.4 =
* Fixed WP 2.7 compat problems
= 2.3 =
* Fixed problems when plugin was used in themes
= 2.2 =
* Queries starting with OR will use remaining words in query as ORed search. I.e. 'OR this that' will search for this or that.
* Ability to call plugin from a theme.
* Bugfixes for admins with db table character sets not matching that of their WordPress install.
= 2.1 =
* Major rewrite, again. 
* Multiple feeds allowed. 
* Using WP builtin RSS fetching and caching system. 
* Shortcodes are supported. 
* Rewrote more PHP5-only code, should now work fine with PHP4
= 2.0.1 =
* Minor bugfix. Options were reset in some circumstances.
= 2.0 =
* Rewritten from scratch. Now uses a class to avoid polluting the name space. Hopefully adhering to best practices plugin writing.
* Can now be used both as a widget and as inline content replacement.
= 1.1 =
* Removed dependency on PHP 5.1++ functionality.
* Fixed UTF8-related bugs. 
* - Not a public release.
= 1.0 =
* Initial release
