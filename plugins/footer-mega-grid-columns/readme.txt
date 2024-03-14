=== Footer Mega Grid Columns - For Legacy / Classic / Old Widget Screen ===
Tags: footer, footer widgets, footer widgets in grid, website footer, simple footer editor, mega footer, megafooter
Contributors: wponlinesupport, anoopranawat, pratik-jain, piyushpatel123, ridhimashukla, patelketan
Requires at least: 4.0
Tested up to: 6.3.1
Stable tag: 1.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Footer Mega Grid Columns - Register a footer widget area for your theme and allow you to add and display footer widgets in grid view with multiple columns

== Description ==

> **Important Note** : This plugin work better with WordPress older version till 5.7.

It still works with latest version of WordPress 5.8 or above but you need to enable Legacy / Classic / Old Widget Screen. For this you need to add following code in your theme/child theme functions.php file:

<code>
function fmgc_theme_setup() {
    remove_theme_support( 'widgets-block-editor' );
}
add_action( 'after_setup_theme', 'fmgc_theme_setup' );
</code>

OR

<code>
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );
</code>

[Explore Footer Mega Grid Features](https://demo.essentialplugin.com/footer-mega-grid-columns-demo/?utm_source=WP&utm_medium=Footer_Grid&utm_campaign=Read-Me) | [Annual or Lifetime Bundle Deal](https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Footer_Grid&utm_campaign=Read-Me)

Is your footer stuck in the default "1 or 2 columns" that came with your theme?

[Footer Mega Grid Columns](https://www.essentialplugin.com/wordpress-plugin/footer-mega-grid-columns/?utm_source=WP&utm_medium=Footer_Grid&utm_campaign=Read-Me) is a free plugin which allows you to create footer areas in grid depending upon the requirement of your theme.

Footer Mega Grid Columns - Register a footer widget area for your theme and allow you to add and display footer widgets in grid view with multiple columns.

The site footer is a valuable piece of site real estate, often containing important lead generating items such as mailchimp and social. A well designed footer can be a tremendous benefit.

= How to display footer grid =
Add the following code in your footer.php 
<pre><code><?php if( function_exists('slbd_display_widgets') ) { echo slbd_display_widgets(); } ?></code></pre>

= Features =
* Add a Footer widget ie Footer Mega Grid Columns .
* Display all widgets in grid 1,2,3,4 etc under Footer Mega Grid Columns.
* Can be used with most of the themes.
* Third party widget can be added.

= How to install : =
[youtube https://www.youtube.com/watch?v=52Q0IHcnxVo] 


== Installation ==

1. Upload the 'footer-mega-grid-columns' folder to the '/wp-content/plugins/' directory.
2. Activate the "Footer Mega Grid Columns" list plugin through the 'Plugins' menu in WordPress.
3. Check you Widget section for widget name Footer Mega Grid Columns.
4. Add the following code in your footer.php file under <code><footer></code> tag.
<pre>
 if( function_exists('slbd_display_widgets') ) { echo slbd_display_widgets(); }
</pre>
= How to install : =
[youtube https://www.youtube.com/watch?v=52Q0IHcnxVo] 

== Frequently Asked Questions ==

= Is this plugin works with latest version of WordPress ie 5.8 or above =

Yes, It will work but for this you need to enable Legacy / Classic / Old Widget Screen. For this you need to add following code in your theme/child theme functions.php file:

<code>
remove_theme_support( 'widgets-block-editor' );
</code>

OR

<code>
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );
</code>

= Footer is displaying in the full width. How to add in wrap? =

Yes. We have added a CSS class - 'footer-mega-col-wrap' and given a width 100%. You can take the class in your theme style.css file OR in custom CSS section.
Use like this 
<code>.footer-mega-col-wrap{max-width:1100px;}</code>

== Screenshots ==

1. Widget
2. Footer with 3 col
3. Footer with 4 col

== Changelog ==

= 1.4.1 (07, June 2022) =
* [*] Updated plugin name, readme and added FAQ for better usage.

= 1.3.3 (11, Feb 2022) =
* [*] Tested up to: 5.9

= 1.3.2 (15, Nov 2021) =
* [*] Update - Add some text and links in Readme file.

= 1.3.1 (16, Sep 2021) =
* [*] Tested up to: 5.8.1
* [*] Updated demo link.

= 1.3 (18, Aug 2021) =
* [*] Updated all external links
* [*] Tweak - Code optimization and performance improvements.

= 1.2 (31, May 2021) =
* [+] Added new language code.
* [*] Tested up to: 5.7.2
* [*] Tweak - Code optimization and performance improvements.

= 1.1.3 (12, Dec 2020) =
* [*] Tested up to: 5.6

= 1.1.2 (14, July 2020) =
* [+] Added getting started page for better user experience.

= 1.1.1 (28-10-2017) =
* [+] Added support for 5 columns

= 1.0.1 (31-01-2017) =
* [+] Added ::after and ::before to .footer-mega-col class
* [+] Added .footer-mega-col-wrap new class under footer-mega-col class

= 1.0 =
* Initial release.