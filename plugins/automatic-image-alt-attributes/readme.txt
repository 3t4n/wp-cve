=== Automatic image ALT attributes ===



Contributors: Birmingham

Tags: image, img, title, alt, attribute

Requires at least: 4.7


Tested up to: 4.7


Stable tag: trunk


License: GPLv2


License URI: http://www.gnu.org/licenses/gpl-2.0.html


Automatically generates ALT attributes in image HTML. Restoring WordPress < 4.7 functionality.


== Description ==


Restores WordPress default functionality prior to version 4.7 where image ALT attributes were automatically generated and put into the HTML code. The ALT attributes were generated from the Caption (if it exists) else from the Title (if it exists) of the image in the Media Library. This plugin utilises Titles in generating ALT text, but does not utilise Captions. The Title used by this plugin, is the main Title of the image as found in the Media Library, not the HTML TITLE attribute of the image (that is something else, which this plugin ignores).



== Installation ==



1. Upload 'automatic-image-alt-attributes' directory to the '/wp-content/plugins/' directory.


2. Activate the plugin through the 'Plugins' menu in WordPress.




== Changelog ==

 

= 2.0 = 

* Extension of functionality, covering HTML of images when generated within theme files using wp_get_attachment_image and similar.

= 1.0 = 

* Initial release, adding alt attributes into the HTML of images as they get inserted into posts