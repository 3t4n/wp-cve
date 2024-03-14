=== Lessify Wordpress ===
Contributors: magnigenie,sagarseth9
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=sagar.seth9@gmail.com&item_name=Lessify%20WordPress&return=http://wordpress.org/extend/plugins/lessify-wp/
Tags: less, wp less , lessify wordpress, less wordpress, less wp, lesscss wordpress
Requires at least: 3.0
Requires PHP: 5.4
Tested up to: 6.4.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Combine the power of WordPress with the power of Less and create something awesome.

== Description ==

**LessCSS**

LESS extends CSS with dynamic behaviour such as variables, mixins, operations and functions.Implements LessCSS in order to provide lots of interesting features for the theme development. 

**How to use ?**

Just enqueue the LESS files (with .less extension) as it is usually done for the CSS ones, and the corresponding CSS files will automatically be generated and added to the site.

**Important Notes**

1.This plugin is based on  [lessphp](http://leafo.net/lessphp/) by Leaf Corcoran

2.This plugin generates the css file under "/wp-content/uploads/lessify-cache" and therefore you need to define your images and any external links on you less file using the themeurl variable available with this plugin and if you use relative url then it will not work as we are saving the css file in a different location.

3.In order  to use themeurl variable with the images you can do something like this
```
body{ background-image: url(@{themeurl}/images/bg.png); }```
the plugin also comes with 

another variable `lessurl` which provides the url where the less file is present.


== Installation ==
* Download the plugin and extract it.
* Upload the directory '/lessify-wp/' to the '/wp-content/plugins/' directory.
* Activate the plugin through the 'Plugins' menu in WordPress.
* That's it you are done, you can now start creating your less files and enqueue to wordpress.

== Frequently Asked Questions ==
= What is lessify wordpress? =
Now you can write less directly with your wordpress theme and just enqueue them like the traditional wordpress way. This plugin would take care of the rest of the work.
= How to add LESS file to your theme? =
To enqueue your less file you can simply do like this 
`wp_enqueue_style( 'less-style', get_stylesheet_directory_uri() . '/style.less' );`

== Changelog ==

= 1.1 =
* Fixed: Instalation issue fixed for PHP verison 8.0
* Code cleanup

= 1.0 =
* Initial release.


== Upgrade Notice ==
= 1.0 =
* Initial release.