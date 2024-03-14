=== Post Title Color ===
Contributors: trepmal
Tags: post, title, colorpicker
Donate link: http://kaileylampert.com/donate/
Requires at least: 3.5
Tested up to: 4.6
Stable tag: 1.4

Puts a colorpicker on the edit posts pages so you can change the color of the title in your blog

== Description ==
Puts a colorpicker on the edit posts pages so you can change the color of the title in your blog

I'm on [Twitter](http://twitter.com/trepmal/)

== Installation ==

= Installation =
1. Download the zip file and upload the contents to your plugins directory (defaul `wp-content/plugins`)
1. Activate the plugin through the 'plugins' page in WP.
1. Edit a post and find the colorpicker in the side (for 2-colomn layouts)

== Screenshots ==

2.

== Other Notes ==

This can be enabled for pages by using the `post_title_colors_post_types` filter.

```
add_filter( 'post_title_colors_post_types', 'ptc_on_pages' );
function ptc_on_pages( $post_types ) {
	$post_types[] = 'page';
	return $post_types;
}
```

== Upgrade Notice ==

= 1.3 =
Fixes javascript error. Sorry guys!

= 1.2 =
Requires WordPress 3.5

== Changelog ==

= Version 1.4 =
* Translatable. Serbo-Croation language file added
* Ensure proper script dependency
* General maintenance

= Version 1.3 =
* Oops, Javascript error. Now it works. Sorry guys!

= Version 1.2 =
* Uses new WordPress 3.5 colorpicker.

= Version 1.1 =
* Keep the post title changes in the main post loop. Sidebar/secondary loops should maintain default title colors.
* Code improvements
* Fix plugin homepage link

= Version 1.0 =
* Initial release version.
