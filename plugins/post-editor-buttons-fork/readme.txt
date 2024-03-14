=== Post Editor Buttons Fork ===
Contributors: trepmal
Donate link: https://paypal.me/trepmal/5usd
Tags: toolbar,buttons,post editor,toolbar buttons,add buttons,button,post toolbar,post textarea
Requires at least: 3.3
Tested up to: 4.6
Stable tag: 2.4

This plugin allows you add your own buttons to the post editor's TEXT mode toolbar.

== Description ==

Add custom buttons to the TEXT mode editor toolbar.

This is a fork of [Oren Yomtov's](http://wordpress.org/extend/plugins/post-editor-buttons/) plugin.

Unsure of the reason behind the poor rating and "doesn't work" vote. Possibly the user didn't realized this only adds tags to the **HTML editor**? If you use this plugin and it works for you, I'd appreciate it if you'd give my a good star rating and an "it works" vote.

Conversely, if you have trouble, please post to the forums, and/or ask me on [twitter (@trepmal)](http://twitter.com/trepmal).

**If you need this to work on WordPress < 3.3** download [2.2.1](http://wordpress.org/extend/plugins/post-editor-buttons-fork/download/).

* [I'm on twitter](http://twitter.com/trepmal)

== Frequently Asked Questions ==

= I don't see the buttons I've created. Where are they? =
This plugin creates buttons for the **TEXT** editor only.

= Can I put classes/styles or other attributes inside the tag? =
Yes, but you must use single quotes. For example, this will work:
`<h2 style='color:#ff0;'>`
But this will not:
`<h3 class="clear">`
As of version 2.1, **"** will be replaced with **'** automatically
As of version 2.3, quote marks should be preserved

= Why are my inline styles are being removed? =
Some styles are removed by WordPress while others aren't. For example, this will work:
`<span style='color:red;'>`
But this will not:
`<span style='display:none;'>`
These styles are being removed when the provided tags are passed through one of WordPress's sanitation filters.

= Why isn't this tag/attribute being saved? =
When you save a custom button, the before/after pieces are filtered. No point in creating a button that adds something that'll only be removed when you save a post, right?

To allow additional tags, you'll need to add some code. (I recommend add it to your functions.php file so it will be preserved if you update the plugin).

Here's how to allow the `video` tag

`add_filter( 'admin_init', 'allowed_tags' );
function allowed_tags() {
	global $allowedposttags;
	$allowedposttags['video'] = array();
}`

To add more attributes (in this case, `src`, `type`, `poster`):

`add_filter( 'admin_init', 'allowed_tags' );
function allowed_tags() {
global $allowedposttags;
	$allowedposttags['video']['src'] = array();
	$allowedposttags['video']['type'] = array();
	$allowedposttags['video']['poster'] = array();
}`

== Installation ==

1. Upload the `post-editor-buttons` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the plugins page by clicking the `Use` link in the plugins description or from the `Settings` admin panel.

That's it, now you can start adding your own buttons.

== Screenshots ==

1. This is how the plugin's interface looks (in 3.2)
2. This is the output of the setting above (in 3.2)

== Upgrade Notice ==

= 2.4 =
Can now select core buttons to remove

= 2.3 =
Requires WordPress 3.3

= 2.2 =
Address compatibility issues if user isn't an administrator

== Changelog ==

= 2.4 =
* Core buttons can now be removed
* General maintenance

= 2.3 =
* Uses QTags API introduced in WordPress 3.3
* Uses new Help Tabs method introduced in WordPress 3.3
* Info on allowing additional tags/attributes added to help tab.
* Better handling of quote marks in tags

= 2.2 =
* Fixed issues for non-administrators

= 2.1 =
* Rework of how custom javascript is saved
* General cleanup and clarification

= 2.0 =
* Initial fork release
