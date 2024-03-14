=== Really Simple Popup ===
Contributors: huntlyc, DaganLev
Donate link: http://www.hotscot.net/
Author URI: http://www.hotscot.net/
Tags: popup, fancybox
Requires at least: 3.5.1
Tested up to: 4.0.1
Stable tag: 1.0.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple, easy to use, fancybox style popup

== Description ==

Simple, easy to use, fancybox style popup.

Works for all images in your page that are in anchor tags.  If it finds more than one, it will create a small gallery you can navigate through with the buttons or your keyboard.  Advanced users can open iframes and html content in it fairly easily.

= Avanced users =


= Basic use =
Anchor class for popup: "hs-rsp-popup"

= Advanced use =
- disable gallery feature and popup for image by adding this class: **no-hsrsp-popup**
- anchor classes for **iframe** popup *"hs-rsp-popup iframe"*
- anchor classes for **html** popup *"hs-rsp-popup hiddendiv"*

= Examples =

To popup an image give the anchor tag around the image this class: *"hs-rsp-popup"*
Example: `<a href="image.png" class="hs-rsp-popup"><img src="image.png" alt="image"/></a>`

For an iframe, set the srct of the iframe and use the class *"iframe"* combined with *"hs-rsp-popup"*:
`<a href="http://www.youtube.com/watch?hl=en-GB&v=2WNrx2jq184&gl=GB" class="hs-rsp-popup iframe" title="Bird is the word">Watch this</a>`

You can also use this to show html content. For local content just use the id of the element for the href:
`<a href="#elementid" class="hs-rsp-popup hiddendiv">click here</a>
<div id="elementid" style="display:none">Hello, World!</div>`

For remote content, link to the page, for example:
`<a href="http://www.hotscot.net" class="hs-rsp-popup hiddendiv">click here</a>`

= Setting Popup Size (image/iframe) =
You can use the following html5 data attributes in your link to set the size: `data-popupheight="100" data-popupwidth="300"`  These values are in pixels.

For Example:
`<a href="image.png" class="hs-rsp-popup" data-popupheight="100" data-popupwidth="300"><img src="image.png" alt="image"/></a>`

**Note:** for this to work, you have to use _both_ the data-popupwidth and data-popupheight attributes.


== Installation ==

1. Extract to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

n/a

== Screenshots ==

n/a

== Changelog ==

= 1.0.11 =
Removed console.log

= 1.0.10 =
Add hs-rsp-nogallery class to any image on the page to prevent the gallery from working

= 1.0.9 =
Removed console log left over (can effect IE)

= 1.0.8 =
Add 'no-hsrsp-popup' class to image to not have it part of gallery/popup. Fixed but on set width/height window resize

= 1.0.7 =
Reverted body scroll fix as it was having undesirable effects

= 1.0.5 =
* Fixed: Firefox close issue

= 1.0.4 =
* Fixed: hidden div popup

= 1.0.3 =
* UI Enhancements: new icons, hover states for 'gallery' and fade effect instead of 'slide-down'

= 1.0.2 =
* New: Set width & height of image/iframe popup

= 1.0.1 =
* Update to the readme file

= 1.0 =
* Initial version

== Upgrade Notice ==

= 1.0.11 =
Removed console.log

= 1.0.10 =
Add hs-rsp-nogallery class to any image on the page to prevent the gallery from working

= 1.0.9 =
Removed console log left over (can effect IE)

= 1.0.8 =
Add 'no-hsrsp-popup' class to image to not have it part of gallery/popup. Fixed but on set width/height window resize

= 1.0.5 =
* Fixed: Firefox close issue

= 1.0.4 =
Fixed: hidden div popup

= 1.0.3 =
UI Enhancements: new icons, hover states for 'gallery' and fade effect instead of 'slide-down'

= 1.0.2 =
* New: Set width & height of image/iframe popup

= 1.0.1 =
* Update to the readme file

= 1.0 =
New and shiny stuff is cool!
