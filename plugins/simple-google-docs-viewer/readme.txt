=== Simple Google Docs Viewer ===
Contributors: maor, illuminea
Tags: google-docs, embed-pdf, documents, pdf-viewer
Requires at least: 3.0
Tested up to: 4.7.1
Stable tag: 1.2
License: GPLv2 or later

Easily embed documents like PDFs, Word documents, and Powerpoint in your site using Google Docs Viewer.
== Description ==

Easily embed documents supported by Google Docs (PDF/DOC/DOCX/PPTX/etc)with a simple shortcode `[gviewer]`
 
Ex. [gviewer file=”https://example.com/thisismyfile.pdf”]

A full list of attributes:

* `file` -- __Required__. The URL of the file you wish to show
* `width` -- Optional. The desired width of the viewer in pixels. If no width is set, the value of the theme's `$content_width` will be used. If no value is set, the width will default to 600 px.
* `height` -- Optional. The desired height of the viewer in pixels. If height is set, the height will 1.2 times the width. For example, if the width is 100 px, the height will be 120 px.
* `language` -- The language of the document. If the document is written in a right-to-left (RTL) language (like Hebrew and Arabic), specifying the language will apply RTL settings.

Another way to embed a Google Document is by using the template tag provided by the plugin in the source code. Here's an example:

`
<?php

echo simple_gviewer_embed( 'https://.../file.pdf', $args );
`

The second argument, `$args`, is an associative array. Keys can be found in the list above.

= Short Demonstration =

[youtube http://www.youtube.com/watch?v=aU1Ekd2D-kI]

== Installation ==

Add via the plugin installer, or download the ZIP file and upload from the "Upload" tab.

== Screenshots ==

1. How an embedded document looks in the front-end.
2. A sample use of the "gviewer" shortcode.

== Changelog ==

= 1.2 =
 
* Fix width shortcode attribute 

= 1.1 =
 
* Added support for https
* iframes are now responsive out of the box
 
 
= 1.0 =

* Initial release

