=== Plugin Name ===
Contributors: padraic
Tags: pubsubhubbub, rss, atom, feed, feeds, post, posts
Requires at least: 2.5
Tested up to: 2.8.4
Stable tag: /tags/1.2.0

Implements a Pubsubhubbub Real Time Publisher informing Planet Earth of your updates now, not later!

== Description ==

Implements [the Pubsubhubbub protocol](http://pubsubhubbub.googlecode.com/svn/trunk/pubsubhubbub-core-0.1.html "the PubSubHubbub protocol") based on the current PubSubHubbub Core 0.1 Specification and any changes since its publication. [PubSubHubbub](http://code.google.com/p/pubsubhubbub/ "PubSubHubbub") is an open, simple web-scale pubsub (publish/subscribe) protocol capable of notifying one or more Hub Servers (e.g. the reference open source [implementation on Google App Engine](http://pubsubhubbub.appspot.com/ "implementation on Google App Engine")) of your blog updates so they may be forwarded to any of your feed's Pubsubhubbub enabled Subscribers as quickly as possible.

Any Pubsubhubbub enabled Subscriber can then receive an almost immediate update from any of your Hubs containing the actual update for immediate use. Subscribers know what Hubs to use when subscribing to your RSS 0.92, RDF/RSS 1.0, RSS 2.0, or Atom 1.0 feeds since this plugin will insert one or more links to your configured Hubs into these feeds.

WP Pubsubhubbub has the following features:

* Supports multiple Hubs configured from the settings page
* Pings all Hubs whenever you publish/edit a blog post
* Adds suitable Atom namespaced link elements for each configured Hub to the top of your RSS 0.92, RDF/RSS 1.0, RSS 2.0, or Atom 1.0 feeds
* Maintains compliance with the Pubsubhubbub 0.1 Core Specification and any outstanding amendments
* WP Pubsubhubbub utilises a Pubsubhubbub Publisher implementation which is fully unit tested for reliability and specification conformance, based on Zend Framework's upcoming Pubsubhubbub component for which I am the lead developer.
* Where no replacement Hubs are configured, WP Pubsubhubbub defaults to the reference Hub hosted on Google App Engine. This is a very reliable Hub. We also default to using the Superfeedr Hub as an alternative to ensure reliable service. You may replace or add to this list as needed.
* Version 1.1.0 also enables Hub notification of changes to your RSS 2.0 Comment Feeds

As the specification authors state on the main Pubsubhubbub site, the protocol is decentralized and free. No company is at the center controlling it. Anybody can run a hub, or anybody can ping (publish) or subscribe using open hubs.

== Installation ==

1. Upload the `wp-pubsubhubbub` directory to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enter two or more custom hubs under your WP Pubsubhubbub Settings (optional - default hubs preset)
4. It is recommended to use at least two Hubs to avoid a single point of failure.

== Frequently Asked Questions ==

= Where can I read more about Pubsubhubbub? =

You can visit [the Pubsubhubbub home](http://pubsubhubbub.googlecode.com "the Pubsubhubbub home") or [the Pubsubhubbub mailing list](http://groups.google.com/group/pubsubhubbub "the Pubsubhubbub mailing list") on Google Groups.

= Where can I go to read the Pubsubhubbub Specification? =

You can visit [Pubsubhubbub Core 1.0 Specification](http://pubsubhubbub.googlecode.com/svn/trunk/pubsubhubbub-core-0.1.html "Pubsubhubbub Core 1.0 Specification")

= Is this in common use? =

It's getting there! Pubsubhubbub is currently supported by Superfeedr, Rabbithub, and Google itself is currently implementing it across some of their properties like Google Reader and Blogger. When it's adopted in full by Google Reader for feed subscriptions it will be amazing!

= Where can I learn more about the author of this plugin? =

You can learn more about [Padraic Brady](http://blog.astrumfutura.com "Padraic Brady") (that's me!) at [his blog](http://blog.astrumfutura.com "his blog").

I'm a PHP developer from Ireland who works on several open source projects, including the Zend Framework where I am the lead developer for Zend_Feed_Reader, Zend_Feed_Writer, Zend_Feed_Pubsubhubbub (!), Zend_Oauth, and many more. I've been writing PHP since 1998.

== Screenshots ==

1. Use the WP Pubsubhubbub settings page to configure one or more Hubs for use. Using at least two (as configured by default) is recommended to ensure a reliable fail proof Subscriber service.

== Changelog ==

= 1.2.0 =
* Added Hub support for remaining Wordpress feeds (RSS 0.92 and RDF/RSS 1.0)
* Added Hub support for all Wordpress comment feeds (RSS 2.0 and Atom 1.0)
* Added additional failover default Hub from Superfeedr
* Refactored source code and merged some functions
* Implemented conflict safe namespace setting in feeds to prevent conflicting or duplicate namespace declarations

= 1.0.5 =
* Added back files missing from svn (WP svn is not handling Tags very well right now)

= 1.0.3 =
* Small change to disable any non-fatal Exception error reports
* Using a quick script to set absolute paths across the included library to freeze version used
* Removed reliance on appending an include_path

= 1.0.1 =
* Prevented plugin from reporting errors which are not fatal and interrupting the posting process.

= 1.0 =
* Stable plugin functionality.
