=== Simple Nivo Slider ===
Contributors: tmus
Donate link: 
Tags: slider, jquery, nivo, image, gallery, banner
Requires at least: 2.9.2
Tested up to: 3.2.1
Stable tag: 0.5.6

"The world's most awesome jQuery slider" by Gilbert Pellegrom, made easily available for WordPress.

== Description ==

The Simple Nivo Slider plugin provides easy access to Gilbert Pellegrom's excellent jQuery-based Nivo Slider. The admin panel makes it easy to tweak the most commonly used Nivo Slider options from within WordPress.

Visit http://nivo.dev7studios.com for more info on the Nivo Slider, that's where the real magic happens.

Valid XHTML 1.0 code generation and CSS stylability are priorities.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `simple-nivo-slider` plugin directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu
1. Select an appropriate "category" from the plugin options page (the plugin will use the "Featured Images" from posts in the selected category)
1. Place `<?php if (function_exists('simple_nivo_slider')) simple_nivo_slider(); ?>` in your templates - Or place a [snivo] shortcode in your post.

= Options =

* Custom Field `simple_nivo_caption` can be used to specify a caption for the image
* Custom Field `simple_nivo_link` can be used to specify a link other the default which links to the post (specify `none` to disable link completely)

= Parameters =

The `simple_nivo_slider()` and `[snivo]` instantiation methods accept the same parameters

1. `ID` : This is the ID of the DIV associated with the slider
1. `Category` : This is the category slug of the categories to use for images (multiple categories can be specified, seperated by comma)

= Examples =

Template function - All defaults

`simple_nivo_slider();`

Template function - ID and multiple categories specified

`simple_nivo_slider("anotherid", "mycat1,mycat2");`

Shortcode - All defaults

`[snivo]`

Shortcode - ID and multiple categories specified

`[snivo id="anotherid" category="mycat1,mycat2"]`

== Upgrade Notice ==

* When upgrading, new transitions will be disabled by default.

== Frequently Asked Questions ==

= Can I make the link for a particular slide take me to a non-default page or another site? =
You can specify a custom link, using the `simple_nivo_link` custom field on the slide post.

= Can the link for a particular slide be disabled completely? =
Yes, use `none` as the value for the `simple_nivo_link` custom field on the slide post.

= Will Simple Nivo Slider work with post-link plugins like "Page Links To"? =
Most likely, yes.

= I'd like to put captions on some of my slides, is that possible? =
You can specify a caption for a slide, using the `simple_nivo_caption` custom field on the slide post.

= How do I reset all Simple Nivo Slider options to defaults? =
Uninstalling the plugin, removes all settings as well. Re-installing, will return all options to their default values.

= The slider is not behaving as expected (no effects, controls not working etc) =
If you have other Nivo based slider plugins installed, try to deactivate them and see if that helps. Loading multiple instances (and even versions) of the same jQuery function, can yield unpredictable results.

= I have some suggestions or found a bug in the plugin. Can I contact You with info? =
Please do. I can't make too many promises up front, but I promise I'll check my mail. You'll find my mail address in the Contact section.

== Screenshots ==

No screenshots included - Visit http://nivo.dev7studios.com to see the slider in action

== Changelog ==

= 0.5.6 =
Added "shuffle images" option to display the slides in random order

= 0.5.5 =
Fixed a problem where not all images in a category would be added to a slider correctly

= 0.5.2 =
Support for multiple categories from shortcodes as well as from template function. [snivo id="myid" category="mypostcategory1,mypostcategory2"] or simple_nivo_slider('myid', 'mypostcategory1,mypostcategory2');

= 0.5.1 =
Updated the nivo-slider component to version 2.5.2
Initial support for [snivo id="myid" category="mypostcategory"] style shortcodes. Currently only image div "id" and the post "category" can be specified in the shortcodes (more options will surely follow).
Squashed a few bugs

= 0.4.5 =
Added support for using post image sizes other than 'full'. Full remains the default value.

= 0.4.4 =
Updated to nivo slider 2.5.1.
Fixed a few minor problems.

= 0.4.3 =
Fixed php warning in database update code. This would in some situations cause problems activating or upgrading the plugin.

= 0.4.2 =
A few small fixes. New arrow- and loader images.

= 0.4.1 =
Fix CSS problems.

= 0.4.0 =
Improved handling of non-automatic plugin updates. This should make the plugin behave nicely for updates performed manually too.
Other minor fixes and improvements were thrown in as well.

= 0.3.1 =
Added plugin database update helper functionality to make sure database is "sane" after an update.

= 0.3.0 =
Nivo-Slider updated to version 2.5. Support for new transitions added to plugin code and CSS was cleaned up.

= 0.2.2 =
Insignificant documentation updates

= 0.2.0 =
Fixed small but significant bug, causing JS problems on some IE configurations

= 0.1.1 =
Removed incorrect upgrade notice statement in readme.txt

= 0.1 =
Initial release

== Credits ==

The plugin was originally based on the WP Nivo Slider plugin by Rafael Cirolini (quickly turned into a complete rewrite though).

The actual slider code was developed by Gilbert Pellegrom over at http://nivo.dev7studios.com.

== Contact ==

Thomas M Steenholdt (plugin developer): tmus at tmus dot dk

