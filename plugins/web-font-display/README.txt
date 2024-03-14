=== Web Font Display ===

Contributors: aistechnolabspvtltd
Donate link: 
Tags: google fonts, font-awesome fonts, webfont load
Requires at least: 6.0
Tested up to: 6.0
Stable tag: 1.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html



Webfont display plugin help you to resolve pagespeed insights error : "Ensure text remains visible during webfont load".



== Description ==


Webfont display plugin help you to resolve pagespeed insights error : "Ensure text remains visible during webfont load".



== Frequently Asked Questions == 

= How it Works?

 

WebFont Display plugin will find all Google Fonts and font-awsome fonts in a webpage and set its font-display to swap property in your theme.

If anyone add custom fonts using @font-face property into css file then they need to add this property into @font-face{font-display:swap;} css manually.

By default browser will wait until the Google Fonts are downloaded to display the font. This is the reason for the error **'ensure text remains visible during webfont load'** in [Google PageSpeed Insights](https://developers.google.com/speed/pagespeed/insights/)

Luckily Google Fonts now supports setting `font-display` via a new query parameter. By setting `font-display` to swap, the browser uses the fallback font and when downloading actual font is complete, it just swaps the font!

**Note**: Plugin can't add `font-display: swap` to dynamically (via JS) injected Google Fonts




= Where can I find the settings configuration? 

Just install the plugin and active it. No further configuration is needed.



= How to use this plugin for custom css? 

You can use below mentioned css into your @font-face{font-display:swap}.




This plugin is not working for my font-awesome 
For that please check your font-awesome file is properly registered or not 
For example: wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . '/font-awesome/css/font-awesome.min.css', array() ); 



== Changelog ==



= 1.0.0 =


- Initial Release

== Upgrade Notice ==

Make sure you get the latest version.