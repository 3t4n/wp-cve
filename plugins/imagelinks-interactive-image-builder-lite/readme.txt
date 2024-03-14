=== ImageLinks Interactive Image Builder for Wordpress ===
Contributors: Avirtum
Donate link: https://www.paypal.me/avirtum/5
Tags: interactive image, floor map, product map, infographics
Requires at least: 4.0
Tested up to: 6.3
Requires PHP: 7.0
Stable tag: 1.6.0

Create Interactive Images for Your Site That Empowers Publishers and Bloggers

== Description ==

ImageLinks is a WordPress plugin that lets you tag your images with any web content, so making them more interactive. Using this plugin, you can take any image and tag it with practically any web hosted content, such as hyperlinks to webpages, albums hosted on photo services, videos (YouTube, Vimeo etc) and countless other things. Basically, if an online hosted digital content has a unique URL, chances are that you can tag it on your image using this plugin. It also features rich previews of the tagged media. This means that if you want to watch a YouTube video tagged on an image, you can do it directly on the image, without having to even open up YouTube. It's similar to [thinglink service](https://www.thinglink.com/). Use this plugin to create interactive news photography, infographics, imagemap, product map and floor map and shoppable product catalogs in minutes!

Using **[imagelinks id="123"]** shortcode, you can publish an interactive image on any Page or Post in your WordPress sites.

This is the **LITE version** of the official [ImageLinks - Interactive Image Builder for WordPress](https://1.envato.market/MAWdq) plugin which comes with support and doesn't have some limitations.


= Quick Video Demo =
https://youtu.be/ioEs6jUVkpw


= Features =
* **Advanced Builder** - drag & drop, zoom & pan features
* **Multiple instances** - create as many items as you want in the same page
* **Markers** - add images or links or design your own view
* **Tooltips** - small boxes for tiny information
* **Smart** - tooltips can occupy the best position
* **Animations** - tooltips have over 100 show/hide effects
* **2 Predefined Themes** - included 2 skins: dark & light (you can add your own)
* **17 Predefined Markers** - you can design and add your own
* **Responsive** - automatically adjust elements to image size
* **Animations** - tooltips have over 100 show/hide effects
* **Powerful Interface** - many options & capabilities
* **Export/Import** - save your config to a file and use it later or on another domain
* **AJAX saving** - save your config without page reloading
* **JSON config** - the config is served from the filesystem instead of the database for better performance
* **Code editors** - add extra css styles or js code with syntax highlighting
* **Customization** - create you own theme or extend via custom css and js


You can place any markers by simply clicking on an image. Each marker can have its own tooltip which gives an excellent opportunity for creating engaging visual stories & presentations.

== Screenshots ==
1. Manage interactive images
2. Create markers
3. Marker properties


== Installation ==
* From the WP admin panel, click "Plugins" -> "Add new"
* In the browser input box, type "ImageLinks"
* Select the "ImageLinks" plugin and click "Install"
* Activate the plugin

Alternatively, you can manually upload the plugin to your wp-content/plugins directory

== Frequently Asked Questions ==

= I'd like access to more features and support. How can I get them? =
You can get access to more features and support by visiting the CodeCanyon website and
[purchasing the plugin](https://1.envato.market/MAWdq).
Purchasing the plugin gets you access to the full version of the ImageLinks plugin, automatic updates and support.

= What is the difference between Lite and PRO =
The lite version has only two limits:
1) You can create and use only one item
2) Your published item will have a little icon
All other features are the same as PRO has.


== Changelog ==

= 1.6.0 =
* Fix: constant FILTER_SANITIZE_STRING is deprecated
* Fix: SQL injection when sorting imagelinks items in "orderby" parameter

= 1.5.4 =
* Fix: prevent cross-site scripting (XSS) from shortcode
* Mod: polish the code

= 1.5.3 =
* Fix: prevent cross-site scripting (XSS) from input form

= 1.5.2 =
* Fix: Bug with bodyclick (FireFox, IE)

= 1.5.1 =
* Fix: Bug with jquery, added dependency from this library
* Fix: Bug with marker's image, the relative property

= 1.5.0 =
* New: Absolutely new version, incompatible with the old one

= 1.4.1 =
* Fix: Sometimes lost hotspot's content after edit
* Fix: Bug with JetPack lazyload
* New: Added save/load item config to file

= 1.4.0 =
* Fix: Changed a data storage format
* New: Ready for translation

= 1.3.5 =
* Fix: Bug with multiple instances on one page

= 1.3.4 =
* Fix: Works better with touch events

= 1.3.3 =
* Fix: Bug with char encoding, problems with item update
* New: Allow execute JS code after the item loaded

= 1.3.2 =
* Fix: Bug with hotspot preview images

= 1.3.1 =
* New: Image url can be local relative to the upload folder or full
* Fix: Editor can't save a popover content in some cases

= 1.3.0 =
* New: Added WordPress editor for a popover content
* New: Allow move hotspots via arrow keys
* New: Now you can add your own theme to plugin folder

= 1.2.0 =
* New: Added a new parameter 'popoverLazyload'
* New: Added a new parameter 'popoverShow'
* New: Allow shortcodes within a popover content
* Fix: When special characters are used in popovers the plugin doesn't work

= 1.1.0 =
* New: Added a new theme 'dots'
* New: Added a new parameter 'hotSpotBelowPopover'
* New: Added a new parameter 'content' for the hotspot definition
* New: Added new location types (top-left, top-right, bottom-left, bottom-right)
* Fix: Bug with z-order of popover windows

= 1.0.0 =
* Initial release
