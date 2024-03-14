=== WP-Slimbox2 Plugin ===
Contributors: malcalevak
Donate link: http://transientmonkey.com/wp-slimbox2
Tags: slimbox, slimbox2, lightbox, jQuery, picture, photo, image, overlay, display, lightbox2
Requires at least: 2.8
Stable Tag: 1.1.3.1

A WordPress implementation of the Slimbox2 javascript.

== Description ==

A WordPress implementation of the stellar Slimbox2 script by Christophe Beyls (an enhanced clone of the Lightbox script) which utilizes the jQuery library to create an impressive image overlay with slide-out effects.

Almost, if not all, options are configurable from the administration panel. For more on the settings and what they do check out the <a href="http://www.digitalia.be/software/slimbox2/" title="Slimbox 2, the ultimate lightweight Lightbox clone for jQuery">Slimbox2</a> page.

Support forums are generously hosted by Ryan Hellyer of PixoPoint, <a href="http://pixopoint.com/forum/index.php?board=6.0">here</a>.

Recent Changes in v1.1.3.1:<br />
1.	Changed index.php to UTF-8 without BOM encoding to resolve issues for some users.
Recent Changes in v1.1.3:<br />
1.	Updated to Slimbox 2.05 to support WordPress 3.6
2.	Removed unnecessary xfarbtastic.css

== Installation ==

After you've downloaded and extracted the files:

1. Upload the complete `WP-Slimbox2` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the "WP-Slimbox2" page in your WordPress options menu to configure any advanced settings.
4. Manually add the <code>rel="lightbox"</code> attribute to any link tag to activate the lightbox or <code>rel="lightbox-imagesetname"</code> for an image set, using the title attribute to store a caption. Alternatively you may use the autoload option to automatically prepare links to images and additionally enable picasaweb and flickr integration to easily utilize their albums.

== Frequently Asked Questions ==

= Does Slimbox2 support the lightbox effect on pages and videos? =

No. As stated in the script creators FAQ, Slimbox was designed to display images only, to be simple and to have the smallest code.

= What kind of grouping does autoload utilize? =
Autoload has been modified to group all images in a Wordpress post if the theme places posts inside a div with class="post". If the images are instead on a page they will all be grouped together. If you want individual group sets it is recommend you instead manually insert 'rel="lightbox-groupname"' inside your hyperlinks to specify your groups.

= Why do I need WordPress 2.8+? =

The Javascript requires jQuery 1.3+ which wasn't included in WordPress until 2.8. If you're using something to override the included jQuery with a newer version (a feature I may add at a later date) it should be compatible from 2.1+ since I believe that was when wp_enqueue_script() was implemented.

= Why can't the plugin do X, Y or Z? =

Either the Javascript doesn't support it, or I haven't gotten around to adding it.

= Why can't the plugin resize images? =

Please see this <a href="http://code.google.com/p/slimbox/wiki/FAQ#Can_Slimbox_automatically_resize_my_images_when_they_are_too_lar">excerpt</a> from the FAQ by the creator of Slimbox, Christophe Beyls

= Why isn't the plugin in my language? Could I contribute a translation? =

I only know English, but as of v.0.9.4 the plugin supports localization using PO and MO files, just like WordPress.
A copy of the POT file to use in your translations can be found in the languages directory as wp-slimbox2.pot.
If you're willing to provide a translation I'd be more than happy to include it. The NEXT, PREV, and Close buttons can be translated as well.
If you've translated the plugin or would like to find out more please let me know by posting on our <a href="http://pixopoint.com/forum/index.php?topic=1383.0">support forums</a>.

= Why should I use this plugin? =

You want Lightbox or Slimbox effects using the jQuery library, and don't want any sort of "ad".
You want complete control over all the javascript settings from the admin page.

= What if I have other questions that haven't been answered? =

Please try our <a href="http://pixopoint.com/forum/index.php?board=6.0">support forums</a>, and read the Slimbox creator's <a href="http://code.google.com/p/slimbox/wiki/FAQ">FAQ</a>.

== Screenshots ==

1. Administration interface in WordPress 2.7
2. Overlay effect.

== Changelog ==
= 1.1.3.1 - Aug-28-2013 =
* Changed index.php to UTF-8 without BOM encoding to resolve issues for some users.
= 1.1.3 - Aug-24-2013 =
* Updated to Slimbox 2.05 to support WordPress 3.6
* Removed unnecessary xfarbtastic.css
= 1.1.2 - Mar-24-2012 =
* Utilizes built in Farbtastic javascripts.
* Consolidated load_farbtastic and keypress.js
* Moved CSS files to css folder.
= 1.1.1 - Feb-22-2012 =
* Fix to resolve failures experienced by some users in v1.1.
= 1.1 - Jan-31-2012 =
* Extensive re-write including several minor fixes to eliminate PHP warnings and errors.
* Ability to use <code>rel="nolightbox"</code> to exclude an image.
* Ability to customize image link URLs by simply placing '/*DESIRED URL*/' in front of the caption.
* Grouping of Flickr, Picasa and other images combined. Autoload groups by selector, lightbox groups by rel.
* Removal of WPlize in favor of built in option arrays.
* Fix for settings initialization issues.
* Option sanitization.
* Addition of Traditional Chinese/&#32321;&#39636;&#20013;&#25991; translation.
* Removed localization tracking.
= 1.0.3.4 - Jan-25-2012 =
* Addition of Russian/&#1088;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081; &#1103;&#1079;&#1099;&#1082; translation.
= 1.0.3.3 - Dec-18-2011 =
* Addition of Brazilian Portuguese/Portugu&#234;s brasileiro translation.
* Addition of Italian/Italiano translation.
* Addition of Lithuanian/Lietuvi&#154;kai translation.
* Addition of Japanese/&#26085;&#26412;&#35486; translation.
* Removal of encodeURI() to eliminate issues with images having spaces.
= 1.0.3.2 - May-04-2010 =
* Fix for defaults in new installs.
= 1.0.3.1 - May-03-2010 =
* Forgot to commit one of the RTL specific changes.
= 1.0.3 - May-02-2010 =
* Updated to Version 2.0.4 of Slimbox.
* Added encodeURI to autoload script to automatically fix URLs containing invalid characters.
* All "fixed" CSS moved to static CSS files, addition of RTL specific CSS.
* Addition of Belarusian/&#1041;&#1077;&#1083;&#1072;&#1088;&#1091;&#1089;&#1082;&#1110; Translation.
* Addition of Chinese/&#20013;&#25991; Translation.
* Update of Spanish/Espa&#241;ol and Dutch/Nederlandse Translations.
* Update of German/Deutsch Translation.
* Addition of Localization Tracking (see FAQ for details).
* Minor fixes/tweaks.
= 1.0.2 - Jan-21-2010 =
* Fixed IE Javascript issue.
* Fixed potential XSS vulnerability and rare inability to update.
* Addition of Turkish/T&uuml;rk&ccedil;e Translation.
* Update of French/Fran&ccedil;ais Translation.
= 1.0.1 - Jan-20-2010 =
* To accomodate some installs the global options variable was removed.
* To repair a small issue regarding selectors, .closest was used instead of .parents, bumping the jQuery requirement to 1.3, in turn bumping the WP requirement to 2.8. (If you insist on using an older version of WP, you can either manually upgrade jQuery, or switch back to using .parents, and specifically choose the selector value you want to use).
= 1.0 Beta - Jan-19-2010 =
* Addition of options to select caption source, render the caption as a hyperlink to the image, control autoload grouping element, and disable the effect on mobile phones.
* Initialization is now encapsulated within a function (usable in Infinite Scroll plugin, etc)
* All Javascript is now static, no more dynamic files.
* All Javascript and CSS compressed using YUI Compressor.
= 0.9.7 Beta - Apr-21-2009 =
* Addition of farbtastic overlay color select.
* Automatic key code recognition.
* Addition of French/Fran&ccedil;ais and Dutch/Nederlandse languages.
* Options transferred to WPlize class, less database calls.
* Flickr and Picasaweb images now properly load Slimbox settings.
* Minor typographical corrections.
= 0.9.6 Beta - Feb-19-2009 =
* Added rudimentary German/Deutsch translation - thanks Laws
* Tiling Next/Prev Links in Safari Fix - thanks monodistortion
* Switch from wp-blog-header to wp-load, may resolve issue on certain servers that fail to properly serve dynamic JS and CSS
= 0.9.5 Beta - Feb-01-2009 =
* Added minor IE6 fix to prevent tiling of next and previous images in a unique scenario.
* Espa&#241;ol/Spanish language typo correction.
* Updated to Slimbox 2.02 (and adjusted version # accordingly, see Slimbox website for more details)
* Support for RTL languages added (proper image progression and button display)
* Caching/compression reenabled on javascript - cache for one year, or until version change which occurs on option update.
* Support options on autoloaded image files (ie .jpg?w=400 now is properly detected)
= 0.9.4.1 Beta - Jan-24-2009 =
* Removed caching of autload script, for real this time.
= 0.9.4 Beta - Jan-24-2009 =
* Localization support implemented. Currently only Espa&#241;ol/Spanish provided. See FAQ to contribute other languages. Removed caching of autload script, at least for now.
= 0.9.3 Beta - Jan-14-2009 =
* Flickr and Picasaweb Integration, Slimbox 2.01, maintenance mode, autogrouping by post/page, compression and caching
= 0.9.2.3 Beta - Jan-08-2009 =
* Bug fix. Autoload wasn't loading options. - v0.9.2.3
= 0.9.2.2 Beta - Jan-07-2009 =
* Emergency Admin for minor overlay opacity setting error
= 0.9.2.1 Beta - Jan-07-2009 =
* Emergency JS Fix
= 0.9.2 Beta - Jan-07-2009 =
* Addition of option to change the overlay color
= 0.9.1 Beta - Jan-06-2009 =
* Addition of option to enable automatically applying to all image links (png,jpg,gif)
= 0.9 Beta: Intial release - Jan-05-2008 =

== Credits ==

Thanks to the following for help with the development of this plugin:

* <a href="http://www.digitalia.be/software/slimbox2">Christophe Beyls</a> - Creator of the Slimbox2 Javascript
* <a href="http://gsgd.co.uk/sandbox/jquery/easing/">George McGinley Smith</a> - Creator of the jQuery Easing Plugin Javascript
* <a href="http://acko.net/dev/farbtastic/">Steven Wittens</a> - Creator of the jQuery Farbtastic colorpicker Javascript
* <a href="http://pixopoint.com">Ryan Hellyer</a> - For spurring my interest in WordPress plugins by welcoming my assistance on his <a href="http://pixopoint.com/multi-level-navigation/">Multi-level Navigation plugin</a> and for hosting our <a href="http://pixopoint.com/forum/index.php?board=6.0">support forums</a>.
* Spi for code suggestion to autogroup items by post.
* <a href="http://nv1962.net/">nv1962</a> - Suggestion to implement localization and Spanish/Espa&#241;ol and Dutch/Nederlandse translations.
* Laws for German/Deutsch localization.
* monodistortion  for CSS tweaks to prevent tiling of images.
* Jandry for the French/Fran&ccedil;ais translation.
* <a href="http://www.serhatyolacan.com">Serhat Yola&ccedil;an</a> for the Turkish/T&uuml;rk&ccedil;e translation.
* <a href="http://pc.de">Marcis G.</a> for the Belarusian/&#1041;&#1077;&#1083;&#1072;&#1088;&#1091;&#1089;&#1082;&#1110; translation.
* <a href="http://www.easespot.com">easespot</a> for the Chinese/&#20013;&#25991; translation and FunDo for additional assistance.
* <a href="http://www.techload.com.br/">Marcelo</a> for the Brazilian Portuguese/Portugu&#234;s brasileiro translation.
* <a href="http://www.Behumandesign.com">Giacomo</a> for the Italian/Italiano translation.
* Nata Strazda of <a href="http://www.webhostinghub.com">Web Hub</a> for the Lithuanian/Lietuvi&#154;kai translation.
* ackie00h for the Japanese/&#26085;&#26412;&#35486; translation.
* nafanyabr for the Russian/&#1088;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081; &#1103;&#1079;&#1099;&#1082; translation.
* <a href="http://shachi.tw">shachi</a> for the Traditional Chinese/&#32321;&#39636;&#20013;&#25991; translation.
* Zareiff, for various Next/Previous/Close translation images when not provided by translators.
* Anyone else I forgot to mention who's made a suggestion or provided me with ideas.