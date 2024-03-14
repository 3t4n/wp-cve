=== jAlbum Bridge ===

Contributors: mlaza, jAlbum
Donate link: https://jalbum.net/en/secure/donate
Tags: jAlbum, album, projector, slideshow, 3D, ken burns, coverflow, carousel, masonry, gallery, photo
Requires at least: 5.0
Tested up to: 6.0.2
Requires PHP: 5.2.4
Stable tag: 2.0.14
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

With this plugin you can add spectacular projectors of jAlbum albums on your WordPress site.

== Description ==

**jAlbum Bridge** is a Wordpress plugin for showcasing jAlbum albums. By using this plugin you can add spectacular **slideshows** or **grid-type galleries** to any post or page in Wordpress. The gallery can be a normal block or wide and full width in themes that support them. The gallery block is reponsive, looks great on mobile devices too.   

Slideshow transition types:

* Cross-fade
* Zoom
* Ken Burns
* Slide
* Swap
* Stack
* Flip (3D)
* Carousel (3D)
* Book (3D)
* Cube (3D)
* Cover flow (3D)

Grid-type layouts:

* Grid
* Mosaic
* Strip
* Masonry

Please note, this projector pulls data from a JSON file, which is automatically generated when you make albums with database based skins (Tiger, Photoblogger, Lucid or Story), but you can also ask jAlbum to generate this file with any skin, see: "Settings / Advanced / Generate JSON data". (Don't forget to "Make album" and "Upload" after turning this option on.)

If you're using the album from another site, make sure this site supports Cross Origin Resource Sharing (CORS), otherwise the projector's access will be blocked. Note, if the WordPress site is under **https**, the album site must support https protocol too!  

jAlbum is an album creator desktop application, which creates web albums of images on your hard disk. This way you can manage your photo collection right from your PC, no need individual uploads to a remote server.

* Create albums with folders, custom pages, external links
* No limit, you can use tens of thousands images or videos
* jAlbum manages the uploads: you can upload to any site (different from your WP site) or you can host them on jalbum.net if you wish
* Widely customizable albums
* Tons of features: Google Maps, PayPal cart, Feedback, search, etc.

For help with the plugin visit [your-site-here.com →](https://your-site-here.com)

Read more about [jAlbum features →](https://jalbum.net/software/features)

Get jAlbum application [from here →](https://jalbum.net/software/download)

jAlbum Bridge [forum on jalbum.net →](http://jalbum.net/forum/forum.jspa?forumID=83)

Feedback is welcome, especially some positive ratings :), as today these reviews are based on the old version and some misunderstanings 

== Installation ==

1. Install the plugin through the Dashboard's "Plugins / Add new" panel directly, or manually upload the plugin files to the `/wp-content/plugins/jalbum-bridge` directory.
2. "Activate" the plugin in the "Plugins" menu.
3. In the editor use the "Add block" (+) button, "jAlbum box" to insert a new box.
4. Add the URL of the album's top level page in the inspector panel's appropriate input. 


== Frequently Asked Questions ==

= Is this projector working on images within WordPress? =

No, you don't have to add the images to the WordPress Media collection. Simply use the jAlbum application - which runs on any PC - to create and upload albums separately of WordPress.

= I can't see the settings (inspector) ==

Click the 3 dots in the block's header and click **Show Block Settings** or press CTRL-SHIFT-[,] to toggle! 

= What is the workflow? =

1. First you create an album with the jAlbum application. See short instructions [here →](http://www.your-site-here.com/jalbum-workflow/).

2. Upload to separate (static) folder in your server's root, e.g. `/albums`. Make sure to avoid naming conflicts with existing WordPress posts. If you already have a post (page) called "album" and you create a folder at site root called "album" they might interfere, and instead of the post the visitors will only see the contents of the static folder.

3. Open a post (or page) in WordPress and use the `jAlbum box` button to insert or edit a projector.

4. Provide the URL to the album, e.g. `/albums/Event1`, set the projector Type and other settings. 

5. Once finished click "Update" to actualize the post. 

= What format is required as Album URL? =

You can use either "absolute" (e.g. `http://your-site-here.com/album/Travel`) or "site root relative" addresses (e.g. `/album/Travel`).

= Can I use an album from another site? =

Yes, you can. However, you'll have to have access rights on the album's server. It's called "Cross Origin Resource Sharing" (CORS), and can be controlled many ways, e.g. through the `.htaccess` file in the site root. By adding `Header add Access-Control-Allow-Origin "http://www.your-site-here.com/"` for exemple allows file access from the mentioned site. Once you already allowed access to "*" (any site) you don't have to do anything more.

= Error: "Missing or no access to tree.json". What can I do? =

This error message means there's either no tree.json (album database) in the given folder, or the server does not allow to access it. Check the following:

* Check if the album's output directory (Ctrl-Shift-O) in jAlbum contains **tree.json**. If not, choose a skin that supports JSON output (Tiger, Photoblogger, Responsive) or turn on the `Settings / Advanced / Generate JSON data` option. Make + Upload again.

* Check the **Developer tools** (F12 in Chrome) for the error. If it says "**Cross Domain Access Denied**" you'll have to set proper CORS policies on the other site (see above), or if it's on the same site make sure to use the same absolute URL format (with or withour "www" for example), or use the site root relative format, e.g. `/album/Travel`.

* If the browser complains about "**Mixed content**" thart means the WordPress page runs under secure (`https:`) protocol, while the album comes from an "unsecure" server. Ensure the album's site also has "https:" certification or move the album to the same server as the WordPress page is on.

* If none of the above happens your (album) server migh not support "JSON" files by default. You might need to add `application/json` to the **known MIME types** on your server. By default Apache servers used to be configured properly, but most IIS servers need this modification.   


== Screenshots ==

1. jAlbum box button
2. Adding URL
3. Carousel slider
4. Book slider
5. Coverflow slider
6. Masonry grid
7. Mosaic grid
8. Strip

== Changelog ==

= 2.0.14 =
* New: Option to open album links in new window (or tab)

= 2.0.13 =
* Fixed: Older themes which modified child elements' "box-sizing" attribute deeply could wreck some of the layouts, e.g. Masonry

= 2.0.12 =
* Fixed: Choosing folders as "Include" option didn't work
* Updated: Translation file

= 2.0.11 =
* Fixed: Javascript error blocking the preview in the Editor

= 2.0.10 =
* Changed: Critical errors are displayed, e.g. broken link to the album or the initial folder. In the production version these errors disappear automatically.

= 2.0.9 =
* Fixed: Link to the album's index page instead of individual images works only in DEBUG mode

= 2.0.8 =
* Added option to link to the album's index page instead of individual images

= 2.0.7 =
* Fixing disregarded aspect ratio parameter in short code mode

= 2.0.6 =
* Fixing memory leak (degraded performance over time) with slideshow type projectors

= 2.0.5 =
* Fixing broken projector with absolute album URL's

= 2.0.4 =
* Fixing range slider numeric input field is too narrow to accommodate 5-digit numbers on WP 5.0 - 5.2
* Fixing link to album is broken in certain cases
* Fixing wrong transitions in "coverflow" using random order
* Fixing wrong ordering criteria and direction with certain ordering types

= 2.0.3 =
* Fixed broken links to album
* Short code (old plugin) projectors had no links, even though originally the links were enabled by default 

= 2.0.2 =
* Range slider numeric input field too narrow to accommodate 5-digit numbers
* Aspect ratio combo box shows 10:1 when you chose 8:1, 6:1, 16:9, 6:5, 4:3, 9:16

= 2.0.1 =
* Projector is broken when using "Title + link" as title caption. (In the post, but not in Editor)
* Removed spinner arrows from range control inputs on FireFox
* More than 3 keywords error on WordPress 5.0 - 5.2
* "blockEditor" missing error on WordPress 5.0 - 5.2
* Lighter link icon with "Title + link" captions
* Better animation for the first frame in "Slide" transition
* Immediate rendering of hidden faces in "Carousel" transition
* Displaying projectors added with previous versions. Still no TinyMCE preferences dialog is provided, so you can edit the short codes manually or re-add them as Blocks.

= 2.0.0 =
* Extensive rework of the plugin for Wordpress' new **Block Editor** (a.k.a Gutenberg)
* New transition type: "Zoom"
* New ordering criteria: "File size", added new ascending/descending orders
* Block can be "wide width" and "full width" too
* Better - flicker-free - transitions on the 3D projectors
* The plugin is now translatable
* Better database compatibility with the upgraded skins

= 1.1.0 =
* Added "Folder" option to show contents only of a subdirectory
* Added "Gap" option for grid-like layouts: leaves gap between the cards
* The code checks if called from a https: page and adjusts the albumURL accordingly (Plase note, using http: album URL from a secure page results in a broken projector anyway. Make sure to support the secure protocol on the album page too in this case!)
* Coverflow width is adjusted to fill in the space better
* Added ${label} - can be used in templates for the filename without extension + underscrores replaced by spaces
* Default photo caption template is using "label" instead of "name" 

= 1.0.3 =
* Fixed Carousel 3D rendering artifacts on Safari 
* Automatically removing index.html (or php) when added as Album Url
* Fixed Grid layout 1px gap between rows
* Fixed Mosaic 1+3 layout's overlapping images
* Fixed Masonry layout falls apart on window resize

= 1.0.2 =
* Fixed TinyMCE selection does not cover all the shortcode
* Title default style is "White"

= 1.0.1 =
* Fixed TinyMCE selection error.
* Added custom attribute handling in short codes

= 1.0 =
* Initial release

== Upgrade Notice ==

= 2.0.0 =
This version is made for the new Block Editor (Gutenberg). Many parts of the plugin has been rewritten from ground. As the old (TinyMCE) version was based on "short codes", and the new on "blocks", it can't automatically convert old projectors into blocks. These old projectors will still work on the production site, but no settings dialog is available. You can use them, but have to edit the short codes - the settings - manually.  

= 1.1.0 =
This version brings new features, like "Start in subfolder", "Gap between cards in grid mode" and added "${label} variable". Also added automatic http -> https rerouting album URL's if called from a secure page.