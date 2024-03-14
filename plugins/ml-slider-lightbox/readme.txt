=== MetaSlider Lightbox ===
Contributors: andergmartins, htmgarcia, publishpress, stevejburge, rochdesigns
Tags: wordpress slideshow lightbox,meta slider,metaslider,metaslider lightbox,slideshow lightbox,lightbox,slideshow,slider,wordpress lightbox
Requires at least: 3.5
Tested up to: 6.4
Stable tag: 1.13.1
Requires PHP: 5.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Extends the MetaSlider slideshow plugin and allows slides to be opened in a lightbox.

== Description ==

For use with the popular WordPress plugin <a href="https://wordpress.org/plugins/ml-slider/">MetaSlider</a> allowing slides to be opened in a lightbox, using one of the following supported lightbox plugins:<br>

<ul>
<li><a href="https://wordpress.org/plugins/easy-fancybox/">Easy FancyBox</a> <small>(300,000+ active installations)</small></li>
<li><a href="https://wordpress.org/plugins/simple-lightbox/">Simple Lightbox</a> <small>(200,000+ active installations)</small></li>
<li><a href="https://wordpress.org/plugins/foobox-image-lightbox/">FooBox Image Lightbox</a> <small>(100,000+ active installations)</small></li>
<li><a href="https://wordpress.org/plugins/wp-colorbox/">WP Colorbox Lightbox</a> <small>(10,000+ active installations)</small></li>
<li><a href="https://wordpress.org/plugins/ari-fancy-lightbox/">ARI Fancy Lightbox</a> <small>(10,000+ active installations)</small></li>
<li><a href="https://wordpress.org/plugins/fancy-gallery/">Gallery Manager</a> <small>(6,000+ active installations)</small></li>
</ul>

We also support the following WordPress plugins, although they haven't had recent updates, or sometimes go long periods without the authors fixing issues:<br>

<ul>
<li><a href="https://wordpress.org/plugins/responsive-lightbox/">Responsive Lightbox by dFactory</a> <small>(200,000+ active installations)<br>Note: Some users are reporting errors with Responsive Lightbox by dFactory (<a href="https://wordpress.org/support/topic/conflict-with-metaslider-2/">see here</a>).</small></li>
<li><a href="https://wordpress.org/plugins/wp-lightbox-2/">WP Lightbox 2</a> <small>(60,000+ active installations)</small></li>
<li><a href="https://wordpress.org/plugins/wp-jquery-lightbox/">WP jQuery Lightbox</a> <small>(50,000+ active installations)</small></li>
<li><a href="https://wordpress.org/plugins/jquery-colorbox/">jQuery Colorbox</a> <small>(30,000+ active installations)</small></li>
<li><a href="https://wordpress.org/plugins/fancy-lightbox/">Fancy Lightbox</a> <small>(1,000+ active installations)</small></li>
<li><a href="https://wordpress.org/plugins/imagelightbox/">imageLightbox</a> <small>(800+ active installations)</small></li>
<li><a href="https://www.tipsandtricks-hq.com/wordpress-lightbox-ultimate-plugin-display-media-in-a-fancy-lightbox-overlay-3163">WP Lightbox Ultimate</a></li>
<li><a href="https://23systems.net/wordpress-plugins/lightbox-plus-for-wordpress/">Lightbox Plus</a></li>
</ul>

If you would like to use a lightbox plugin that isn't supported you can add support by hooking into the `metaslider_lightbox_supported_plugins` filter. If you need assistance, please open an issue.


== Screenshots ==

1. Toggle the lightbox in the advanced settings panel

== Installation ==

Requires: <br>

<ul>
<li><a href="https://wordpress.org/plugins/ml-slider/">MetaSlider</a> 3.0+ </li>
</ul>

and one of the following lightbox plugins:

<ul>
<li><a href="https://wordpress.org/plugins/easy-fancybox/">Easy FancyBox</a></li>
<li><a href="https://wordpress.org/plugins/simple-lightbox/">Simple Lightbox</a></li>
<li><a href="https://wordpress.org/plugins/responsive-lightbox/">Responsive Lightbox by dFactory</a></li>
<li><a href="https://wordpress.org/plugins/foobox-image-lightbox/">FooBox Image Lightbox</a></li>
<li><a href="https://wordpress.org/plugins/wp-featherlight/">WP Featherlight</a></li>
<li><a href="https://wordpress.org/plugins/wp-colorbox/">WP ColorBox Lightbox</a></li>
<li><a href="https://wordpress.org/plugins/fancy-gallery/">Gallery Manager</a></li>
<li><a href="https://wordpress.org/plugins/ari-fancy-lightbox/">ARI Fancy Lightbox – WordPress Popup</a></li>
<li><a href="https://wordpress.org/plugins/wp-lightbox-2/">WP Lightbox 2</a></li>
<li><a href="https://wordpress.org/plugins/wp-jquery-lightbox/">WP jQuery Lightbox</a></li>
<li><a href="https://wordpress.org/plugins/jquery-colorbox/">jQuery Colorbox</a></li>
<li><a href="https://wordpress.org/plugins/fancy-lightbox/">Fancy Lightbox</a></li>
<li><a href="https://wordpress.org/plugins/imagelightbox/">imageLightbox</a></li>
<li><a href="https://www.tipsandtricks-hq.com/wordpress-lightbox-ultimate-plugin-display-media-in-a-fancy-lightbox-overlay-3163">WP Lightbox Ultimate</a></li>
<li><a href="https://23systems.net/wordpress-plugins/lightbox-plus-for-wordpress/">Lightbox Plus</a></li>
</ul>


If you would like to use a lightbox plugin, you can filter the supported plugin list with the necessary attributes. For example, using <a target="_blank" href="https://wordpress.org/plugins/responsive-lightbox-lite/">Responsive Lightbox Lite</a>
<pre>
add_filter('metaslider_lightbox_supported_plugins', 'supported_plugins_list');
function supported_plugins_list($supported_plugins_list) {
    return array(
        'Responsive Lightbox' => array(
            'location' => 'responsive-lightbox-lite/responsive-lightbox-lite.php',
            'settings_url' => 'options-general.php?page=responsive-lightbox-lite',
            'rel' => 'lightbox',
            'attributes' => array(
                'data-lightbox-type' => 'iframe'
            )
        )
    );
}
</pre>

<p>Note that you can use <code>:url</code> or <code>:caption</code> to retrieve these items from the slides, such as <code>'data-lightbox-url' => ':url'</code></p>

The easy way:

1. Go to the Plugins Menu in WordPress
2. Search for "MetaSlider Lightbox"
3. Click "Install"

The not so easy way:

1. Upload the `ml-slider-lightbox` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Manage your slideshows using the 'MetaSlider' menu option

== Changelog ==

The format is based on [Keep a Changelog recommendations](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

= [1.13.1] - 12 Oct, 2021 =

* FIXED: Lightbox setting is broken if using MetaSlider v3.27.13, #18;
* CHANGED: Remove Extendify library;

= [1.13.0] - 13 Jul, 2021 =

* CHANGED: Update the library.

= [1.12.2] - 7 Jul, 2021 =

* FIXED: Bug fixes.
* ADDED: Adds option to disable the library.

= [1.12.1] - 17 May, 2021 =

* FIXED: Bug fixes.

= [1.12.0] - 28 Apr, 2021 =

* ADDED: Adds access to the Extendify template and pattern library.

= [1.11.3] - 22 Aug, 2020 =

* CHANGED: Updates readme and team account info.

= [1.11.2] - 09 Apr, 2020 =

* CHANGED: De-prioritizes recommendation for Responsive Lightbox by dFactory due to inactivity.

= [1.11.1] - 14 Aug, 2019 =

* FIXED: Fixes issue where the setting wouldn't save properly.

= [1.11.0] - 8 Jul, 2019 =

* ADDED: Adds support for Gallery Manager Pro.

= [1.10.4] - 30 Apr, 2019 =

* CHANGED: Adds unique class name to admin notices.

= [1.10.3] - 04 Jan, 2019 =

* FIXED: Fixes a bug where WP-Featherlight would not load as a gallery.

= [1.10.2] - 14 Jul, 2018 =

* CHANGED: Updates settings page for WP Lightbox 2 to match their update.

= [1.10.1] - 16 Mar, 2018 =

* FIXED: Updates how lightbox plugins are checked for activation.
* FIXED: Addresses a bug that checks for previous slider settings.
* FIXED: Removes an incompatible lightbox plugin (duplicate name).

= [1.10.0] - 16 Mar, 2018 =

* ADDED: Adds support for additional lightbox plugins.
* CHANGED: Refactors lightbox to clean up attribute function.
* CHANGED: Refactors various parts of the code to extract supported plugin data.
* CHANGED: Extracts the class MetaSliderLightboxPlugin to its own file.
* CHANGED: Changes the logic for check if the plugin is install and active.
* CHANGED: Adds a CSS class to the container that identifies the active lightbox plugin.
* CHANGED: Adds filters to let users manipulate the plugin use.
* CHANGED: Refactors lightbox to clean up attribute function.

= [1.9.3] - 14 Nov, 2018 =

* CHANGED: Fix checks to slide URL.
* FIXED: FooBox Pro compatibility update.
* FIXED: Updates the FooBox Profile name.
* FIXED: Update Gallery Manager plugin settings.

= [1.9.2] - 26 Jan, 2018 =

* CHANGED: Update translation strings.
* CHANGED: Adds warning message when no lightbox is active.

= [1.9.0] - 28 Mar, 2017 =

* FIXED: Simple lightbox use slide caption instead of attachment caption.

= [1.8.0] - 16 Mar, 2017 =

* FIXED: Update slide image URL to comply with new slide post type.

= [1.7.0] - 09 May, 2016 =

* FIXED: Removes defunct Lightbox Plus plugin link (thanks to @Hendrik57).

= [1.6.0] - 01 Apr, 2015 =

* ADDED: Adds support for FooBox Image Lightbox and WP Lightbox 2 *Pro* versions.

= [1.5.0] - 30 Jan, 2015 =

* ADDED: Adds support for FooBox Image Lightbox and Responsive Lightbox by dFactory.

= [1.4.0] - 15 Dec, 2014 =

* FIXED: Hides dependency warning in admin if WP Video Lightbox is activated (reported by and thanks to: vfontj).

= [1.3.0] - 28 Oct, 2014 =

* ADDED: Adds support for Fancy Gallery lightbox plugin (suggested by and thanks to: Zim1).

= [1.2.0] - 17 Sep, 2014 =

* ADDED: Support for additional lightbox plugins.

= [1.1.0] - 22 Aug, 2014 =

* FIXED: Array assignment compatibility PHP < v5.4 (reported by and thanks to: andrea_montuori).

= [1.0.0] - 15 Aug, 2014 =

* ADDED: Initial version.
