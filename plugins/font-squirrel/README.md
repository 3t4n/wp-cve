wp-fontsquirrel
===============

A WordPress plugin that connects to Font Squirrel API to provide fonts for your website.

[Font Squirrel](http://www.fontsquirrel.com/) is your best resource for FREE, hand-picked, high-quality, commercial-use fonts.
They provide a large part of their fonts in a web-embedding-ready way that is supported by all major browsers (@font-face CSS method).

The provided formats are :
* TTF - Works in most browsers except IE and iPhone.
* EOT - IE only.
* WOFF - Compressed, emerging standard.
* SVG - iPhone/iPad

They provide an API to list, preview and download fonts very easily : this unofficial plugin is using this official API.

When you browse fonts and dispplay one with this plugin, the font is downloaded from fontsquirrel.com. If you then choose to install the
font, its css rule is put in the head section of you website pages so that you can use it on any page. The WordPress built-in editor 
(TinyMce) has a dropdown list that lets you choose the font you want for each part of your site.
