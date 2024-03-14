=== Lazy Load Divi Slider Backgrounds ===
	Contributors: emandiev
	Tags:  Lazy Load, Divi, Divi Theme, Divi Optimization
	Requires PHP: 5.0
	Stable tag: 1.0
	Requires at least: 4.1
	Tested up to: 5.3
	License: GPLv3 or later
	License URI: https://www.gnu.org/licenses/gpl-3.0.html

	Speed up your website by lazy loading slider backgrounds. This plugin works only with the Divi Builder.

== Description ==

Optimize your website's image delivery by lazy loading slider backgrounds.<br />
This plugin works only with the <a href="https://www.elegantthemes.com/plugins/divi-builder/">Divi Builder</a>, usually part of the Divi Theme.<br />
No configuration required.<br />
If you are looking to optimize your Divi-based website even further, check out my other plugins:<br />
<a href="https://wordpress.org/plugins/lazy-load-divi-section-backgrounds/">Lazy Load Divi Section Backgrounds</a><br />
<a href="https://wordpress.org/plugins/responsive-divi-backgrounds/">Responsive Divi Backgrounds</a>

== Installation ==

1. Visit <strong>Plugins > Add New</strong>
2. Search for "<strong>Lazy Load Divi Slider Backgrounds</strong>"
3. Download and Activate the plugin.

== Frequently Asked Questions ==

= What are the requirements? =

The Divi Builder by Elegant Themes.

= Something else? =

jQuery, but chances are you already load it. Most popular themes use it.

= What is Lazy Loading and why should I care? =

Lazy loading is a technique that defers loading of non-critical resources at page load time. Instead, these non-critical resources are loaded at the moment of need. Where images are concerned, "non-critical" is often synonymous with "off-screen". By lazy loading images, you improve your website's load speed. If you've used Google's PageSpeed Insights tool, you may have seen an opportunity called "Defer offscreen images". Google basically tells us to lazy load our images.

= I already have a lazy loading plugin! =

Most lazy loading plugins handle only the `<img>` elements from your pages.
Lazy loading background images requires a different approach and this plugin is specially designed to handle Divi Builder's Slider Module.

= What about browser support? =

All modern browsers are supported.

= What about users without JavaScript? =

If a visitor has disabled their JavaScript, they will still see the slider images as normal.
However, they won't benefit from lazy loading.

= Will this plugin affect the performance in a bad way? =

The plugin should not cause any slowdown.
It's designed to help improve your website's performance.
The plugin won't load any additional files.
Instead, it prints CSS and JS inline in the <head> section and after the main content respectively.

Unlike most plugins, the required resources are only included on the pages which need them.
The plugin checks the current page's content and if there is a slider with a background image the CSS and JS get printed.
All other posts/pages won't be affected to maximize performance.

= It doesn't work on my website. Do you know why? =

1. Check if the Divi Builder (or Divi Theme) is updated.
2. Check if the browser's console (F12) shows any errors. jQuery should be loaded and if it's not you will see an error.

= Anything else you may recommend? =

Yes! Check out my other plugin if you have sections with background images: <a href="https://wordpress.org/plugins/lazy-load-divi-section-backgrounds/">Lazy Load Divi Section Backgrounds</a>

== Changelog ==

= 1.0.0 =
* 12/12/2018:
Initial release.