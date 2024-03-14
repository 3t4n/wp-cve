=== Recent Comments ===
Contributors: automattic, nickmomrik, Viper007Bond
Tags: comments, recent, list
Stable tag: trunk

Creates functions to assist in displaying a list of the most recent comments.

== Description ==

Creates functions to assist in displaying a list of the most recent comments. Provides more configurability than the widget that comes with WordPress.

== Installation ==

1. Visit Plugins -> Add New in your WordPress administration area and search for the name of this plugin.
1. Activate the plugin.
1. Add `<?php list_most_recent_comments(); ?>` to your theme.

== Configuration ==

You may pass parameters when calling the function to configure some of the options. Parameters are accepted only in the [query-string-style](http://codex.wordpress.org/How_to_Pass_Tag_Parameters#Tags_with_query-string-style_parameters).

= list_most_recent_comments() =

In addition to the parameters that [get_comments()](http://codex.wordpress.org/Function_Reference/get_comments) and `get_most_recent_comments()` (see below) accept, this function accepts the following parameters:

* `excerpt_words` -- The number of words from the comment to display
* `excerpt_chars` -- Or alternately the number of characters from the comment to display
* `comment_format` -- Allows you to pick from two predefined display formats:

1. [Comment Author](#commentlink) on [Post Title](#postlink)
1. **Comment Author:** [This is the comment excerpt](#commentlink)

Example:

`<?php list_most_recent_comments( 'excerpt_words=5' ); ?>`

= get_most_recent_comments() =

A more powerful version of [get_comments()](http://codex.wordpress.org/Function_Reference/get_comments). It accepts the same parameters as well as the following ones:

* `passworded_posts` -- Boolean to control showing comments on passworded posts or not. Defaults to `false`.
* `showpings` -- Boolean to control showing pings and trackbacks or not. Defaults to `false`.
* `post_types` -- Array of post types to include comments from. Defaults to posts and pages: `array( 'post', 'page' )`
* `post_statuses` -- Array of post statuses to include comments from.  Defaults to published posts and static pages: `array( 'publish', 'static' )`

Arguments should likely be passed as an array instead of a string.

Example:

`<?php

list_most_recent_comments( array(
	'showpings' => true,
	'post_types' => array( 'post', 'page', 'foobar' ),
) );

?>`

== Changelog ==

= Version 2.0.0 =

* Recoded from scratch to make use of `get_comments()` instead of a direct database query.
* Additional parameters added.

= Version 1.0.0 =

* Original release