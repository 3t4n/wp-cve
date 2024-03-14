=== Easy Custom Auto Excerpt ===
Contributors: Todi.Adiatmo, haristonjoo, Alzea, ageproem, gamaup, gatotw
Tags: excerpt, home, search, archive, automatic, auto, justify, content, read more, read more button
Requires at least: 3.5
Tested up to: 5.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Auto Excerpt for your posts on home, search and archive pages. Customize Read More button and thumbnail image. Easy to configure and have a lot of options.
== Description ==

Easy Custom Auto Excerpt is a WordPress plugin to cut/excerpt your posts displayed in home, search or archive pages. This plugin also enables you to customize the read more button text and thumbnail image. Just activate the plugin, configure some options and you're good to go :)

ECAE only works on themes that call `the_content()` or `the_excerpt()` on home, search & archive pages. ECAE does not support themes that use custom functions to display excerpt, like Total, OceanWP, Writee, and Customizr.

[youtube http://www.youtube.com/watch?v=ZZaXfrB4-68]

The free version comes with everything you need to auto excerpt your content.

> [Live Demo](http://coba.tonjoostudio.com/ "Live demos of ECAE") | <a href="http://wpexcerptplugin.com/" title="Get the premium version" rel="friend">Premium Version</a> | <a href="https://tonjoostudio.com/product/easy-custom-auto-excerpt-premium/?utm_source=wp_org&utm_medium=link&utm_campaign=ecae#manual" title="ECAE Documentation" rel="friend">Documentation</a> | [Support Forum](https://forum.tonjoostudio.com/thread-category/easy-custom-auto-excerpt/ "ECAE Support forum")

= Features: =

* Excerpt your posts based on character length.
* Choose to excerpt your posts on home, search, archive, or custom archive pages.
* Align text (Justify, Right, Left, Center) your text based on your preference.
* Custom Read More text and Button.
* Preserve Image on Excerpt.
* Preserve real excerpt you wrote.
* Partial Indonesian and Spanish translation.
* Enable excerpt on RSS feed.
* Excerpt method by 1st paragraph, 2nd paragraph, and 3rd paragraph
= Premium Features: =
And if you like our plugin and want to do more customization we offer the premium version with some added features:

* Adjust Image Excerpt Position (left, right, center, float left and float right)
* Adjust Image width and margin
* Disable excerpt on specific post
* 10 Read More font type and custom font size
* 40+ Read More button themes.
* Customize button HTML

Get the premium version: <a href="http://wpexcerptplugin.com/" title="Easy Custom Auto Excerpt Premium" rel="friend">Easy Custom Auto Excerpt Premium</a>


= Plugin Demo =
You can try the plugin on this URL: http://coba.tonjoostudio.com

> username: coba
> password: 123456
= Information =
if you have any questions, comment, customization request or suggestion please contact us via our [support forum](https://forum.tonjoostudio.com/thread-category/easy-custom-auto-excerpt/)

Find more detail on our official site: <a href="https://tonjoostudio.com/product/easy-custom-auto-excerpt-premium/?utm_source=wp_org&utm_medium=link&utm_campaign=ecae" title="Easy Custom Auto Excerpt Premium" rel="friend">Easy Custom Auto Excerpt Premium</a>

Or you can find our best plugins at <a href="https://tonjoostudio.com/" title="Tonjoo Studio" rel="friend">Tonjoo Studio</a>

We can also modify your WordPress plugins according to your needs. Visit us : <a href="https://tonjoo.com/" title="Tonjoo WordPress Developer" rel="friend">Tonjoo WordPress Developer</a>

= Install Instruction =

1. Install the plugin.
2. In the Admin Panel, Go to Excerpt -> Settings.
3. Customize the settings according to your need.
4. To remove read more link, fill read more text with "-" (without quote)

Please have a time to understand how this plugin is generating excerpt :

1. If the post has read more, then the read more will be used.
2. If the post doesn't have read more, then post excerpt will be used.
3. If the post doesn't have read more and excerpt, then it will automagically generate excerpt.

== Usage Instruction ==

= General Options =
1.  Excerpt method
    * Paragraph method will cut per paragraph
    * Character method will cut per character based on characters count of Excerpt Size
    * The left options is to only show one or more paragraph from beginning of the content
2.  Excerpt Size
    * The number of characters to show based on Paragraph or Character Excerpt Method
3.  Strip shortcode
    * If you select 'yes' any shortcode will be eliminated from the excerpt
4.  Strip empty HTML tags
    * If you select 'yes' any empty HTML tags will be eliminated from the excerpt
5.  Disable on RSS Feed
    * Disable this plugin on RSS feed page
6.  Special method
    * This basically will fix some error on some themes
= Content Options =
1.  Text align
    * The plugin will try to align the excerpt
= Display Image Options =
1.  Content image
    * Option to select what image to show on the excerpt
    * **"Show all images"** will show all the images on the visible content
    * **"Show only first image"** will only show the first image on the content
    * **"Use Featured Image"** will show the post’s featured image
2.  Image position, Image width, and Image margin **[PREMIUM VERSION]**
    * This options is to configure how to show the image
    * This options is work only on Content Image: Show Only First Image and Use Featured Image
    * Below the screenshots of the some image options
3.  Image thumbnail size **[PREMIUM VERSION]**
    * This is an option so select what image size to show
    * This options is work only on Content Image: Use Featured Image
= Excerpt location =
1.  Basic settings
    * Option to determine the location to show the excerpt, i.e. blog page, front page, archive page, and search page
    * User can also select which page to enable the excerpt
2.  Advanced settings
    * This is an advanced settings of the location, which is users can select the post type or category to show on the each option, like blog page or front page
= Read more button =
1.  Display option
    * **Normal** : show readmore button, only if content length is bigger than excerpt size
    * **Always Show** : always show the readmore button
    * **Always Hide** : always hide the readmore button
2.  Read more text
    * The text to show on read more link
3.  Text after content
    * The text located right after the content, for example dots "[…]". This element can be styled by css with selector ".ecae-dots"
4.  Inline Button
    * The plugin will try to make the read more link inline with the paragraph
5.  Readmore align
    * The plugin will try to align the read more link
6.  Button font **[PREMIUM VERSION]**
    * The font of read more link and the text before link
7.  Button font size **[PREMIUM VERSION]**
    * The font size of read more link and the text before link
8.  Text before button link
    * Text before read more link
9.  Button link type **[SEPARATED PREMIUM VERSION]**
    * The style of read more link
> Notes: both Read more text and Text before link can be translated with WPML string translation. After you save the ECAE option, go to the WPML String Translation and then search for domain name "easy-custom-auto-excerpt". The text is the one named "Readmore text" and "Before readmore text" (see the attached image below).
= Button Shortcode =
You can manually add the button by put this shortcode to your post: **[ecae_button]** Required "strip shortcode options" = No
= Read More Live Preview =
The preview of read more link and the text before link
= Custom CSS =
Allow user to add the custom css for the read more link and the text before link
= License =
* Registering the license code is useful to get the regular updates of ECAE premium
* Registering the license code will also remove the tonjoostudio ads
= Translation : =
1. Bahasa Indonesia : Todi ~ @todiadiyatmo
2. Serbian : Ogi Djuraskovic ~ firstsiteguide.com
3. German
4. French
5. Spanish

== Installation ==
1. Both free and premium plugin must be installed to get the premium edition
2. Free version: from your WordPress admin, add new plugin, then search for "Easy Custom Auto Excerpt" and install the one that developed by tonjoo. Or you can just download the plugin from https://wordpress.org/plugins/easy-custom-autoexcerpt/then unzip to /wp-content/plugin on your WordPress directory
3. After the installation, there will be a notification to enter the license code of ECAE, you can ignore that or you can enter the code at that moment
4. The option page is located on the sidebar named “Excerpt”
== Frequently Asked Questions ==
**It breaks my website!**
The auto excerpt cannot properly cut on some elements. If it breaks your site, please use read more / post excerpt. It can be activated from screen option.

**What happens if I write an excerpt for the post? **
It will be used, instead of the automatically generated one.

== Screenshots ==

1. Easy Custom Auto Excerpt in action.
2. General Options.
3. Advanced Excerpt Location Options.
4. Read More Button Options.

== Changelog ==

= 2.4.12 =
* New license manager

= 2.4.11 =
* Add new option to set excert font size
* Add new option to link image inside excerpt to single post

= 2.4.10 =
* Fixed some bugs in settings page

= 2.4.9 =
* Fixed some minor bugs in character excerpt

= 2.4.8 =
* Fixed bug in excerpt method character

= 2.4.7 =
* Fixed sanitize & escape to prevent XSS

= 2.4.6 =
* Added feature: Customize button HTML (PRO version)
* Fixed bug: post is password protected
* Updated settings page
* Updated compatibility for WordPress 4.9

= 2.4.5 =
* Bugfix unicode HTML-ENTITIES and UTF-8

= 2.4.4 =
* Excerpt method by 1st paragraph now available in FREE version!
* Excerpt method by 2nd paragraph now available in FREE version!
* Excerpt method by 3rd paragraph now available in FREE version!
* Fixed various bugs

= 2.4.3 =
* Bugfix unicode utf8 chars

= 2.4.2 =
* Added feature: add dots ([...]) at the end of the excerpt
* Fixed various bugs
* Updated compatibility for WordPress 4.8

= 2.4.1 =
* Fixed manual excerpt bug

= 2.4 =
* Fixed bug: HTML validator error because of inline style
* Fixed bug: password content not displayed correctly
* Fixed bug: WordPress shows its read more link when using <code><!--more--></code> tag
* Added feature: Remove theme's featured image when ECAE uses featured image to prevent double images
* Added feature: user can now select the featured image thumbnail size to show
* Added feature: user can now use featured image for "Excerpt" field content
* Added feature: "read more text" and "text before link" now compatible with WPML
* Premium user bug fix: can't load the license page
* Updated compability for WordPress 4.7

= 2.3.4 =
* Minor fix
* Updated compability for WordPress 4.6

= 2.3.3 =
* Change admin menu position to root

= 2.3.2 =
* Fixing post type selection on advanced excerpt location
* Fixing post editor error that affects some users
* Fixing page excerpt function

= 2.3.1.5 =
* Fixing backend UI after updated to WordPress 4.4

= 2.3.1 =
* Updated permalink

= 2.3.0 =
* Fixing google font http/https protocol
* Fix known bugs

= 2.2.9 =
* Hide ads when premium key is registered
* Fix known bugs

= 2.2.8 =
* Fixing issue with WPML
* Fix known bugs

= 2.2.7 =
* Fixing compatibility to image caption
* Fix known bugs

= 2.2.6 =
* Fix plugin updater

= 2.2.5 =
* Add an ability to make the button inline with the content
* Add an ability to make the button font same as content font
* Add plugin updater
* Fix known bugs

= 2.2.0 =
* Add disable excerpt on RSS Feed option
* Add button shortcode
* Add German translation
* Add French translation
* Add Spanish translation
* Fix known bugs

= 2.1.6 =
* Add Serbian translation (thanks to Ogi Djuraskovic)

= 2.1.5 =
* Add new advanced excerpt location options
* Add new excerpt size options in each location
* Improved translation
* Fix known bugs

= 2.1.0 =
* Fix word preserved for word excerpt method
* Tested up to WordPress 4.0
* Fix known bugs

= 2.0.9 =
* Add option to do excerpt in pages
* Redesign free button style
* Fix known bugs

= 2.0.8 =
* Fix known bugs

= 2.0.7 =
* Add option to always show "read more" link
* Change behaviour for premium promo notification
* Fix known bugs

= 2.0.6 =
* Add option to strip empty HTML tags
* Add option to change special method (change between yes and no to if there is a fatal error)
* Change behavior for position method left and right (premium edition)
* Fix known bugs

= 2.0.5 =
* Add image position method: left, right, center, float left, float right (premium edition)
* Add image width and image margin options
* Fix known bugs

= 2.0.4 =
* Fix known bugs

= 2.0.3 =
* Decrease file size
* Fix known bugs

= 2.0.2 =
* Add new excerpt method: show 1st, 1st - 2nd and 1st - 3rd paragraph (premium edition)
* Add option to change font size (premium edition)
* Updated display image options
* Fix known bugs

= 2.0.1 =
* Add option to disable excerpt in every single post (premium edition)
* Fix known bugs

= 2.0.0 =
* Updated admin UI, more easy and intuitive
* Add select font option (premium edition)
* Add select button theme option (premium edition)
* Add custom css option
* Add front page excerpt option
* Fix known bugs

= 1.0.8 =
* Improve excerpt algorithm
* Add read more option

= 1.0.7 =
* Add Spanish translation, thanks to Andrew Kurtis ~ webhostinghub.com
* Better excerpt cropping algorithm

= 1.0.6 =
* Minor bug fix

= 1.0.5 =
* Add options to disable read more.

= 1.0.4 =
* Fix remove / add image in excerpt option

= 1.0.3 =
* Add better handling for image

= 1.0.2 =
* Add strip element, for better excerpt

= 1.0.1 =
* Improve regex handling, add rules for <img> and <a>
* return real excerpt from the post if it is exist
* Fix Compability with PHP 5.4

= 1.0.0 =
* Add option to keep / preserve the image
* Excerpt now preserve word
* Tested on WP 3.6 !

= 0.92 =
* Custom Read More Text

= 0.91 =
* Bugfix on incorect header

= 0.9 =
* First Release
