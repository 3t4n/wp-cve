=== Wonder PDF Embed ===
Contributors: wonderplugin
Tags: pdf embed, pdf viewer, responsive pdf embed, responsive pdf viewer, pdf lightbox
Donate link: https://www.wonderplugin.com/wordpress-pdf-embed/
Requires at least: 3.6
Tested up to: 6.4.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Embed PDF to your WordPress website by using Mozilla's PDF.js

== Description ==

**Wonder PDF Embed**

WonderPlugin PDF Embed is a plugin to embed and display PDF files on your WordPres website by using Mozilla's PDF.js.

**Features**

* Easy to use
* Responsive PDF viewer
* Using [Mozilla's PDF.js](https://mozilla.github.io/pdf.js/)
* Embed and display PDF files in WordPress posts or pages - [click to see the online demo](https://www.wonderplugin.com/wordpress-pdf-embed/#tutorial).
* Works with the premium plugin Wonder Lightbox to [open a PDF file in a lightbox popup](https://www.wonderplugin.com/wordpress-pdf-embed/#lightbox)
* Works with the premium plugin Wonder Carousel to create a [PDF carousel](https://www.wonderplugin.com/wordpress-pdf-embed/#carousel)
* Options to hide the download button, the print button and the text selection tool menu item in the PDF viewer toolbar. Please note: the options only use CSS and JavaScript code to hide the relative menu items/buttons in the PDF.js viewer toolbar. It's NOT a DRM (Digital Rights Management) scheme to protect the PDF file. It does NOT stop experienced visitors from downloading, printing or copying text from the PDF file.

**How to Use**

You can use the following shortcode to embed a PDF file to WordPress posts or pages:

[wonderplugin_pdf src="http://www.yourwordpresssite.com/yourdoc.pdf" width="100%" height="600px" style="border:0;"]

**PDF URL Requirements**

* The PDF file MUST be hosted on the SAME DOMAIN as the WordPress website.
* The PDF URL MUST be an absolute URL, that's, the URL must start with http:// or https://.
* Make sure there are no special characters in the PDF URL, for example, apostrophes, double quotes, accented letters etc.
* The PDF URL is defined with the src attribute.

**Shortcode Attributes**

* You can use px or % for the width and height attributes, for example, 600px or 100%.
* By using % for the width attribute, the PDF viewer will be responsive.
* If you use 100% for the height attribute, make sure the container of the shortcode has a proper height value.
* You can use the style attribute to define the CSS style of the PDF viewer which is an iframe.

For more information, please view the online tutorial: [https://www.wonderplugin.com/wordpress-pdf-embed/#tutorial](https://www.wonderplugin.com/wordpress-pdf-embed/#tutorial)

**Open a PDF file in a lightbox popup**

Please view the online document: [https://www.wonderplugin.com/wordpress-pdf-embed/#lightbox](https://www.wonderplugin.com/wordpress-pdf-embed/#lightbox)

**Create a PDF carousel**

Please view the online document: [https://www.wonderplugin.com/wordpress-pdf-embed/#carousel](https://www.wonderplugin.com/wordpress-pdf-embed/#carousel)

== Installation ==

**Install the plugin in WordPress backend**

1. In WordPress backend, go to menu Plugins -> Add New
1. Search Wonder PDF Embed
1. Install the plugin

**Install the plugin with downloaded plugin zip file**

1. In WordPress backend, go to menu Plugins -> Add New
1. Click the link Upload Plugin
1. Select the plugin zip file, then click Install Now

**Uninstall the plugin**

1. In WordPress backend, go to menu Plugins -> Installed Plugins
1. Deactivate the plugin
1. After the plugin is deactivated, Delete the plugin

== Frequently Asked Questions ==

= Do the option "hide the download button", "hide the print button" and "hide the text selection tool menu item" prevent people from downloading,  printing or copying text from the PDF file? =

No. 

The options only use CSS and JavaScript code to hide the relative menu items/buttons in the PDF.js viewer toolbar. It's NOT a DRM (Digital Rights Management) scheme to protect the PDF file. It does NOT stop experienced visitors from downloading, printing or copying text from the PDF file.

= I received the error "An error occurred while loading the PDF" or "Missing PDF file" =

Please check your PDF URL and make sure it complies with the following requirements:

1. The PDF file MUST be hosted on the SAME DOMAIN as the WordPress website.
1. The PDF URL MUST be an absolute URL, that's, the URL must start with http:// or https://.
1. Make sure there are no special characters in the PDF URL, for example, apostrophes, double quotes, accented letters etc.

== Screenshots ==

== Changelog ==

= 2.7 =
* Support Google Analytics

= 2.6 =
* Support page, zoom, pagemode URL parameters

= 2.5 =
* Fix a bug on setting the default PDF link target

= 2.4 =
* Add an option to specify the default PDF link target

= 2.3 =
* Change the PDF.js 2.0.493 folder name to the default name pdfjs to fix the possible cache issue

= 2.2 =
* Change PDF.js folder name to fix the cache issue after upgrading

= 2.1 =
* Fix the cache issue after upgrading PDF.js

= 2.0 =
* Add Mozilla PDF.js version 2.0.493 (dark theme toolbar) as an option

= 1.9 =
* Update Mozilla PDF.js to the latest version 2.12.313
* NOTICE: The latest Mozilla PDF.js changes the viewer toolbar from dark to light color theme!

= 1.8 =
* Support loading cross-domain PDF files by configuring remote server CORS policy

= 1.7 =
* Escape shortcode attributes

= 1.6 =
* Add an option to hide the whole toolbar
* Add an option to hide the Open File button in the toolbar
* Add an option to disable right click on the PDF viewer

= 1.5 =
* Add an option to hide the Document Properties menu item in the toolbar 
* Enable the Hand Tool option when the Text Selection Tool menu is hidden

= 1.4 =
* Add an option to hide the Text Selection Tool menu item in the toolbar 

= 1.3 =
* Add information for the toolbar options

= 1.2 =
* Add two options to hide the Download and the Print button in the toolbar

= 1.1 =
* Update Mozilla PDF.js to the latest version 2.0.493

= 1.0 =
* First version released

== Upgrade Notice ==
