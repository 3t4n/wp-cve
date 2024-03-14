=== wpSpoiler ===
Contributors: flix
Tags: post, spoiler, show, hide
Requires at least: 2.0
Tested up to: 2.5.1
Stable tag: 1.2

A plugin designed to protect the reader against spoilers.

== Description ==

    wpSpoiler is a plugin for WordPress, designed to protect the reader against spoilers, for example in book or filmreviews.
The spoiler text is hidden by default and shows up if wished.

To mark a text as spoiler, enclose the passage with [spoiler] and [/spoiler].

Features

*   Very fast, less code
*   Customizeable with CSS

A german version of this page can be found [here](http://felixtriller.de/projekte/wpspoiler/ "felixtriller.de").
Eine deutsche Version dieser Seite ist [hier](http://felixtriller.de/projekte/wpspoiler/ "felixtriller.de") zu finden.

== Installation ==

To install the plugin follow the listed steps:

1. Download the package
1. Decompress the archive, customize **wpSpoiler.php** and upload the wpspoiler folder to /wp-content/plugins/ on your webspace
1. Activate the plugin through the 'Plugins' menu in WordPress

== Usage ==

To mark a text as spoiler, enclose the passage with [spoiler] and [/spoiler].
Example:
`Spoiler: [spoiler]Bruce Willis is a ghost![/spoiler]`

The result is shown in the screenshot section.

== Screenshots ==

1. Hidden
2. Visible

== Customize ==

The language of the link text has to be changed in the pluginfile itself: **wpSpoiler.php**.

CSS can be used to style the spoiler box and the links. The spoiler-div is identified by the classname `spoiler_div`.
The link's class is `spoiler_link_show` or `spoiler_link_hide`, depending on the current state.

An example stylesheet code snippet:
`
/* wpSpoiler */
a.spoiler_link_show,
a.spoiler_link_hide {
    background-repeat:      no-repeat;
    background-position:    left center;
    padding-left:           18px;
}
a.spoiler_link_show {
    background-image:       url(images/add.png);
}
a.spoiler_link_hide {
    background-image:       url(images/delete.png);
}
div.spoiler_div {
    background-color:       #ddd;
    margin-top:             -10px;
    padding:                2px;
}
`
