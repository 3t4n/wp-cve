=== Plugin Name ===
Plugin Name: GMO Showtime
Plugin URI: https://cloud.z.com/jp/en/wp/themes-plugins#plugins
Author: Z.com by GMO
Author URI: https://cloud.z.com/jp/en/wp/
Contributors: Takeaki Nagashima
Donation Link:
Tags: Slider, Simple, Effects
Requires at least: 3.8.1
Tested up to: 4.4
Stable tag: Version 1.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

GMO Showtime slider plugin gives cool effects to the slider in a snap. The control screen is simple, for anyone to easily use. Express user's originality with fully customizable link and color as well as 16 slider effects in 6 different layouts.

== Instructions ==

Following features are included in this slider plugin.

-Unlimited sliders
-16 effects
-6 layouts
-Page addressable slider
-Slider accepts outside contents other than posts and pages.
-Image Size
730x487px for horizontal layout
1200x487px for photo with overlapping text

Basic settings
1. Install plugin and activate. Open "GMO Showtime" in settings option.
2. Place the code in your theme like below.
*If you are using "Tidy" WordPress theme, you are not required to take this action.

<?php if ( function_exists( 'showtime' ) ): ?>
<?php showtime(); ?>
<?php endif; ?>

3. Select effect from "Slider Settings". Total of 16 effects are available as listed below.

-Slice down
-Slice down left
-Slice up
-Slice up left
-Slice up down
-Slice up down left
-Fold
-Fade
-Random
-Slide in right
-Slide in left
-Box random
-Box rain
-Box rain reverse
-Box rain grow
-Box rain grow reverse

4. Configure "General Settings".
-Page types: Slider content can be chosen from [Home], [Posts and Pages] or [Archives].
-Text Position: Slider position can be chosen from [Left],[Right],[Top-Left],[Top-Right], [Bottom Left], [Bottom-Right].

*[Maintenance Mode] will temporarily disable slider for maintenance purpose.

Slider settings
1. Open [Carousel], click [Add New], enter caption in [Excerpt], set a featured image then click [Publish].
To configure posts or sticky page, open each edit page screen and click [Copy to a new carousel] on the admin bar, then click [Publish] on the edit carousel page. Please make sure that all necessary information is included since elements being copied to the slider will be excerpted with the tile.


== Installation ==

Search and download plugin from either WordPress admin page or http://wordpress.org/plugins.  From the WordPress admin page, simply activate the plugin, or upload a file from "Add New" to install and activate plug-in.

== Frequently Asked Questions ==

== Changelog ==
= 1.5 [25/2/15]
* Author Profile Change

= 1.4 [19/06/12] =
* Support : how to use

= 1.3 [19/05/14] =
* New feature : Custom Color text

= 1.2 [13/05/14] =
* Improvement: Code renovation

= 1.1 [12/05/14] =
* New feature : Custom Color Scheme

= 1.0 [12/04/14] =
* Initial version

== Upgrade Notice ==

== Screenshots ==

1. Settings top page
2. Color settings

= Arbitrary section =

*Image and text (caption)

Horizontal image and text layout
Title: 60 characters (gmoshowtime_title_lr_length)
Excerpts: 148 characters (gmoshowtime_content_lr_length)
Text overlaps on the image
Title: 60 characters (gmoshowtime_title_ov_length)
Excerpts: 280 characters (gmoshowtime_content_ov_length)

Number of characters is limited using PHP.  Add code to make changes.

gmoshotime_title_lr_length varies depending on where it is applied.  Filer name is back of the character number list.  Code below is modified to show title in 40 characters in horizontal layout.

----------------------------------------------------------------
add_filter("gmoshowtime_title_lr_length", "my_gmoshowtime_title_lr_length");

function my_gmoshowtime_title_lr_length($length){

    $length = 40 // (Number of the characters)
    return $length;

}