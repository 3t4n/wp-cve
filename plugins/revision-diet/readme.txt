=== Revision Diet ===
Contributors: davidjmillerorg
Tags: posts, revisions, revision management, database
Requires at least: 2.6
Tested up to: 2.9
Stable tag: trunk

Revision Diet is a plugin to limit the number of post revisions that are saved.

== Description ==

Revision Diet is a plugin to limit the number of post revisions that are saved. It does exactly what you expect - it allows you to set a number of revisions to keep for each post and deletes any revisions beyond that number. This number will not include the autosave revisions.

The options page is extremely simple - enter the number of revisions to keep and save your options. There is also a button to “Trim Excess Revisions.” This button will trim all the extra revisions from every post in your Wordpress installation. (Using it is optional.) As for regular use, each time you save a post the oldest revisions on that post will be deleted from the database down to the limit that is currently set.

== Installation ==

To install it simply unzip the file linked above and save it in your plugins directory under wp-content. In the plugin manager activate the plugin. Settings for the plugin may be altered under the Revision Diet page of the Options menu (version 2.3) or Settings menu (version 2.5 or later).

== Frequently Asked Questions ==

= What does the "Trim Excess Revisions" button do? =

This button automatically trims extra revisions over the limit you have currently set form every post in your database. If you never push that button any posts that you are not editing will not have their extra revisions removed.

= Does this work for page revisions? =

Yes, it does manage the revisions for pages - however the "Trim Excess Revisions" button only trims posts.

== Screenshots ==

1. This is the options page for Revision Diet