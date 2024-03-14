=== Plugin Name ===
Contributors: aradke
Donate link: http://deepport.net
Tags: shadow
Requires at least: 2.5
Tested up to: 2.9
Stable Tag: 0.3.5

This is a plugin to add a range of shadow types to a range of objects. Currently supported are images, divs and blockquotes.

== Description ==

This is a plugin to add a range of shadow types to a range of objects. Currently supported are images, divs and blockquotes.

I got the inspiration from the [cocoia blog](http://blog.cocoia.com/) and Sebastiaan de With was kind enough to let me use the shadow image that he applies to his images.

### Browser Compatibility
I have tested this plugin in the following browsers:

1. Internet Explorer 7 &amp; 8
1. Firefox 3 &amp; 3.5
1. Safari 4
1. Opera 9

**Internet Explorer 6 is not supported** due to the use of transparent png images in ways that regular work arounds don't address. As such the plugin stops the shadows from displaying in IE6.
The opacity setting is disabled for all versions of Internet Explorer due to bugs in how it renders (even IE8).

### Usage
Add one of the following classes to the object:

* `shadow_curl`
* `shadow_flat`
* `shadow_osx`
* `shadow_osx_small`

For images this is as simply as adding the text to the end of the "CSS Class" under the "Advanced Settings" for the image.
For divs and blockquotes you will need to use the HTML view in the editor and add `class="shadow_curl"` to the object.

I decided against applying the shadows to everything it could recognise as this could easily turn into a huge mess though I am open to suggestions on other ways to do it.

### Known Issues
1. If something covers the div or blockquote it can push the shadow down to below the covering object and thus separate the shadow from the object it should be attached to. Try to avoid it through the use of clear or margins.
1. The plugin does not support divs nested inside the shadowed div or blockquotes nested inside the shadowed blockquote.

### Recommendations
From Sebastiaan: "the only thing I'd suggest is adding a border to the divs, otherwise the shadow looks rather detached." I have elected not to do this automatically to allow you greater control over the appearance of your pages.

== Installation ==

1. Upload the whole plugin folder to your /wp-content/plugins/ folder.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Place `class="shadow_curl"`, `class="shadow_flat"`, etc on the objects you want to have shadows.

== Frequently Asked Questions ==

= What objects can have shadows added to them? =

So far images, divs and blockquotes.

= Is Internet Explorer supported? =

Version 7 and up is, with the exception that the opacity level of the shadows cannot be changed.

= How do I add a shadow to an object? =

For images this is as simply as adding `shadow_xxxx` to the end of the "CSS Class" under the "Advanced Settings" for the image.
For divs and blockquotes you will need to use the HTML view in the editor and add `class="shadow_xxxx"` to the object.

= What about regular drop shadows? =

The initial release was aimed at getting the "curl" shadows looking good. In subsequent releases I may add support for "normal" drop shadows if it is requested.

== Screenshots ==

1. Screenshot taken with Firefox 3.5. The opacity of the flat shadows has been set to 80% which will not be applied in IE.
Other than that the appearance will be the same across all supported browsers.
1. Screenshot taken with Firefox 3.5. This shows the two OS X window style shadows `shadow_osx` and `shadow_osx_small`

== Changelog ==

= 0.3.5 =
* Improved handling and checking of units on options page
* Changed submit behaviour to be compatible with WPMU

= 0.3.4 =
* Added "overflow:hidden;" to avoid problems with other objects with clear's on them

= 0.3.3 =
* Added support to include the border widths in the calculation for the OS X style shadows

= 0.3.2 =
* Addressed a bug causing images to sometimes not display in IE with OS X style shadows
* Removed hard coded image directories of OS X style shadows

= 0.3.1 =
* Added `border-collapse:collapse;` to the tables used by the OS X style shadows

= 0.3 =
* Added OS X window style shadows (regular and small)

= 0.2.1 =
* Optimised PNG shadow images

= 0.2 =
* Initial public release
