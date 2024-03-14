=== WP Slick Slider and Image Carousel ===
Contributors: wponlinesupport, anoopranawat, pratik-jain, patelketan
Tags: slick, image slider, slick slider, slick image slider, slider, image slider, header image slider, responsive image slider, responsive content slider, carousel, image carousel, carousel slider, content slider, coin slider, touch slider, text slider, responsive slider, responsive slideshow, Responsive Touch Slider, wp slider, wp image slider, wp header image slider, photo slider, responsive photo slider  
Requires at least: 4.0
Tested up to: 6.4.1
Stable tag: 3.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A quick, easy way to add and display multiple WP Slick Slider and carousel using a shortcode. Also added Gutenberg block support.

== Description ==

✅ Now that you have your website ready then why don’t you **download** and try out this slick slider/ carousel to give it better functionality.

**Download now** and display multiple slick image slider and carousel using shortcode with category. Fully responsive, Swipe enabled, Desktop mouse dragging and  Infinite looping. Fully accessible with arrow key navigation  Autoplay, dots, arrows etc.

[FREE DEMO](https://demo.essentialplugin.com/slick-slider-demo/?utm_source=WP&utm_medium=SlickSlider&utm_campaign=Read-Me) | [PRO DEMO](https://demo.essentialplugin.com/prodemo/pro-wp-slick-slider-and-carousel-demo/?utm_source=WP&utm_medium=SlickSlider&utm_campaign=Read-Me)

**Download Now** this slick slider because It is proven that slick sliders have been a powerful tool to present your content in a very neat manner with the help of fancy sliders and customized designs. 

Your customer might like the professional and fancy vibe of your site with slick sliders

**✅ This plugin displays your images using :**

* Slick Slider (5 designs)
* Slick Carousel (1 designs)

**Download Now** it today and explore all the features.

= ✅ Features : =
[youtube https://www.youtube.com/watch?v=yTfbOaYJYR0] 

When you want to makeover your WordPress website theme with something extraordinary and creative, you must consider the slick slider/ carousel.

Help your website get a slide-wise display to show the custom posts. Not just eye appealing, it is also loved by visitors as they find it quite easy to locate custom posts. 

Display an unlimited number of custom posts slider and carousel in a single page or post with different sets of options like category, limit, autoplay,  arrow, and navigation type. You can also display image slider on your website header.

**Also added Gutenberg block support.**

= ✅ Here is the plugin shortcode example =

**Slick Slider** 

<code>[slick-slider]</code>

**Slick Carousel** 

<code>[slick-carousel-slider]</code>

**To display only slick 4 post:**

<code>[slick-slider limit="4"]</code>
Where limit define the number of posts to display. You can use same parameter with Carousel shortcode.

**If you want to display Slider Slider by category then use this short code:** 

<code>[slick-slider category="category_ID"]</code>
You can use same parameter with Carousel shortcode.

**✅ We have given 5 designs. For designs use the following shortcode:**

<code>[slick-slider design="design-1"]</code> 
Where designs are : design-1, design-2, design-3, design-4, design-5. You can use same parameter with Carousel shortcode but in Carousel we have given only 1 design i.e. design-1.

= ✅ Here is Template code =
<code><?php echo do_shortcode('[slick-slider]'); ?> </code>
<code><?php echo do_shortcode('[slick-carousel-slider]'); ?> </code>

= ✅ Use Following Slick Slider parameters with shortcode =
<code>[slick-slider]</code>

* **limit** : [slick-slider limit="-1"] (Limit define the number of images to be display at a time. By default set to "-1" ie all images. eg. if you want to display only 5 images then set limit to limit="5")
* **category**: [slick-slider category="category_ID"] ( ie Display slider by their category ID ).
* **design** : [slick-slider design="design-1"] (You can select 5 design( design-1, design-2, design-3, design-4, design-5 ) for your  slider ).
* **show_content** : [slick-slider show_content="true" ] (Display content OR not. By default value is "true". Options are "true OR false").
* **Pagination and arrows** : [slick-slider dots="false" arrows="false"]
* **Autoplay and Autoplay Interval**: [slick-slider autoplay="true" autoplay_interval="100"]
* **Slide Speed**: [slick-slider speed="3000"]
* **fade** : [slick-slider fade="true" ] (Slider Fade effect. By default effect is slide. If you set fade="true" then effect change from slide to fade ).
* **lazyload** : [slick-slider lazyload="ondemand" ] (Use lazyload with slick slider. By default there is no lazyload enabled. If you want to set lazyload then use lazyload="ondemand" OR lazyload="progressive" ).
* **loop** : [slick-slider loop="true"] (Create a Infinite loop sliding. By default value is "true". Options are "true" OR "false".)
* **hover_pause** : [slick-slider hover_pause="true"] (Pause slider autoplay on hover. By default value is "true". Options are "true" OR "false".)
* **image_size** : [slick-slider image_size="full"] (Default is "full", values are thumbnail, medium, medium_large, large, full)
* **image_fit** : [slick-slider image_fit="false"] (image_fit parameter is used to specify how an image should be resized to fit its container. By default value is "false". Options are "true OR false"). NOTE :  image_fit="true" work better if sliderheight is given. if image_fit="false", no need to use sliderheight parameter.
* **sliderheight** : [slick-slider sliderheight="400" ] (Set image wrap height. NOTE : This parameter work better if image_fit="true" ).
* **rtl** : [slick-slider rtl="true"] (for rtl mode. By default value is "false". Options are "true OR false").
* **extra_class** : [slick-slider extra_class=""] (Enter extra CSS class for design customization ).

= ✅ Use Following Slick Carousel parameters with shortcode =
<code>[slick-carousel-slider]</code>

* **limit** : [slick-carousel-slider limit="-1"] (Limit define the number of images to be display at a time. By default set to "-1" ie all images. eg. if you want to display only 5 images then set limit to limit="5")
* **design** : [slick-carousel-slider design="design-1"]
* **category**: [slick-carousel-slider category="category_ID"] ( ie Display slider by their category ID ).
* **image_size** : [slick-carousel-slider image_size="full"] (Default is "full", values are thumbnail, medium, medium_large, large, full)
* **slidestoshow** : [slick-carousel-slider slidestoshow="3" ] (Display number of images at a time. By default value is "3").
* **slidestoscroll** : [slick-carousel-slider slidestoscroll="1" ] (Scroll number of images at a time. By default value is "1").
* **Pagination and arrows** : [slick-carousel-slider dots="false" arrows="false"]
* **Autoplay and Autoplay Interval**: [slick-carousel-slider autoplay="true" autoplay_interval="100"]
* **loop** : [slick-carousel-slider loop="true"] (Create a Infinite loop sliding. By default value is "true". Options are "true" OR "false".)
* **hover_pause** : [slick-carousel-slider hover_pause="true"] (Pause slider autoplay on hover. By default value is "true". Options are "true" OR "false".)
* **Slide Speed**: [slick-carousel-slider speed="3000"]
* **lazyload** : [slick-carousel-slider lazyload="ondemand" ] (Use lazyload with slick slider. By default there is no lazyload enabled. If you want to set lazyload then use lazyload="ondemand" OR lazyload="progressive" ).
* **centermode** : [slick-carousel-slider centermode="true" ] ( Display main image on center. By default value is "false" ).
* **variablewidth** : [slick-carousel-slider variablewidth="true" ] (Variable width of images in slider. By default value us "false")
* **image_fit** : [slick-carousel-slider image_fit="false" ] (image_fit parameter is used to specify how an image should be resized to fit its container. By default value is "false". Options are "true OR false"). NOTE :  image_fit="true" work better if sliderheight is given. if image_fit="false", no need to use sliderheight parameter.
* **sliderheight** : [slick-carousel-slider sliderheight="400" ] (Set image wrap height. NOTE : This parameter work better if image_fit="true" ).
* **rtl** : [slick-carousel-slider rtl="true"] (for rtl mode. By default value is "false". Options are "true OR false").
* **extra_class** : [slick-slider extra_class=""] (Enter extra CSS class for design customization ).

**Note: Due to lots of feedback from your users side, we have made image_fit="false" by default. Previously it was image_fit="true". We made image resize option now optional. If you want to resize the image, please use image_fit="true" and sliderheight="400" (400 is just an example. Please use this value as per your need) shortcode parameters.**

✅ **Checkout demo for better understanding**

[FREE DEMO](https://demo.essentialplugin.com/slick-slider-demo/?utm_source=WP&utm_medium=SlickSlider&utm_campaign=Read-Me) | [PRO DEMO](https://demo.essentialplugin.com/prodemo/pro-wp-slick-slider-and-carousel-demo/?utm_source=WP&utm_medium=SlickSlider&utm_campaign=Read-Me)

✅ **Essential Plugin Bundle Deal**

[Annual or Lifetime Bundle Deal](https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=SlickSlider&utm_campaign=Read-Me)

= ✅ Features include: =
* Added Gutenberg block support.
* Slick slider
* Slick carousel
* Easy to add.
* Also work with Gutenberg shortcode block. 
* Elementor, Beaver and SiteOrigin Page Builder Native Support (New).
* Divi Page Builder Native Support (New).
* Fusion Page Builder (Avada) Native Support (New).
* Touch-enabled Navigation.
* Given 5 designs.
* Media size i.e.  thumbnail, medium, medium_large, large and full
* Responsive.
* Responsive touch slider.
* Mouse Draggable.
* Use for header image slider.
* You can create multiple post slider with different options at single page or post.
* Fully responsive. Scales with its container.
* 100% Multi Language.

= How to install : =
[youtube https://www.youtube.com/watch?v=rkbohcgmzVg]

= Privacy & Policy =
* We have also opt-in e-mail selection, once you download the plugin , so that we can inform you and nurture you about products and its features.

== Installation ==

1. Upload the 'wp-slick-slider-and-carousel' folder to the '/wp-content/plugins/' directory.
2. Activate the "wp-slick-slider-and-carousel" list plugin through the 'Plugins' menu in WordPress.
3. Add this short code where you want to display slider
<code>[slick-slider] and [slick-carousel-slider]</code>

= How to install : =
[youtube https://www.youtube.com/watch?v=rkbohcgmzVg]

== Screenshots ==

1. Design-1
2. Design-2
3. Design-3
4. Design-4
5. Design-5
6. Design-6
7. Also work with Gutenberg shortcode block.
8. Also added Gutenberg block support.
9. Also added Gutenberg block support.

== Changelog ==

= 3.6 (24, Nov 2023) =
* [*] Updated analytics SDK.
* [*] Check compatibility with WordPress version 6.4.1

= 3.5 (18 Aug 23) =
* [*] Tested up to: 6.3

= 3.4.1 (02, Aug 2023) =
* [*] Tested up to: 6.2.2
* [*] Fixed all security related issues.

= 3.4 (17, May 2023) =
* [*] Tested up to: 6.2.1

= 3.3 (30, March 2023) =
* [*] Fixed - Fixed some issues like design, UI of admin side.
* [*] Update - Improve escaping functions for better security.
* [*] Update - Update optin screen.

= 3.2 (21, March 2023) =
* [*] Fixed - Fixed one undefined PHP variable warning.
* [*] Update - Improve escaping functions for better security.

= 3.1.4 (28, Dec 2022) =
* [*] Fix - Fixed Gutenberg range control issue.
* [*] Fix - Fixed some issues.

= 3.1.3 (09, Dec 2022) =
* [*] Tested up to: 6.1.1

= 3.1.2 (03, Nov 2022) =
* [*] Tested up to: 6.1

= 3.1.1 (16, Sep 2022) =
* [*] Update - Use escaping and sanitize functions for better security.
* [*] Update - Update demo and documentation link.
* [*] Update - Update Slick slider JS to stable version 1.8.0
* [*] Update - Check compatibility to WordPress version 6.0.2
* [*] Fix - Fixed slider initialize issue in Elementor tab.
* [*] Fix - SEO & HTML validation error of empty image source when plugin lazy load is enabled.
* [*] Fix - Fixed some typo mistake.
* [*] Remove - Removed unnecessary files, code and images.

= 3.1 (18, May 2022) =
* [*] Tested up to: 6.0

= 3.0.9 (29, March 2022) =
[+] Added free vs pro functionality.
[+] Tested up to: 5.9.2

= 3.0.8 (10, March 2022) =
* [+] Added demo link
* [-] Removed some unwanted code and files.

= 3.0.7 (11, Feb 2022) =
* [-] Removed some unwanted code and files.

= 3.0.6 (07, Feb 2022) =
* [*] Tested up to: 5.9 
* [*] Fixed some small issues.

= 3.0.5 (03, Feb 2022) =
* [*] Tested up to: 5.9 
* [*] Solved Gutenberg wp-editor widget issue.

= 3.0.4.1 (15, Dec 2021) =
* [*] Minor fix.

= 3.0.4 (12, Nov 2021) =
* [*] Fix - Resolve Gutenberg WP-Editor script related issue. 
* [*] Update - Add some text and links in Readme file.

= 3.0.3 (26, Oct 2021) =
* [*] Fixed a variable prefix name issue.

= 3.0.2.1 (16, Sep 2021) =
* [*] Fixed some small issue with DIVI theme
* [*] Fixed a variable name issue.

= 3.0.2 (15, Sep 2021) =
* [*] Tested up to: 5.8.1
* [*] Updated Demo Link.

= 3.0.1 (18, Aug 2021) =
* [*] Updated language file and json file.
* [*] Updated plugin analytics code.

= 3.0 (17, Aug 2021) =
* [*] Updated all external links
* [*] Tweak - Code optimization and performance improvements.
* [*] Fixed Blocks Initializer Issue.

= 2.4.3 (31, May 2021) =
* [*] Tested up to: 5.7.2
* [*] Added - https link in our analytics code to avoid browser security warning.

= 2.4.2 (24, May 2021) =
* [*] Tested up to: 5.7.2
* [*] Tweak - Code optimization and performance improvements.

= 2.4.1 (3, May 2021) =
* [*] Tested up to: 5.7.1
* [*] solve extra_class parameter issue. 

= 2.4 (15, March 2021) =
* [*] Tested up to: 5.7

= 2.3 (25, jan 2021) =
* [+] New - Added native shortcode support for Elementor, SiteOrigin and Beaver builder.
* [+] New - Added Divi page builder native support.
* [+] New - Added Fusion Page Builder (Avada) native support.
* [*] Tweak - Code optimization and performance improvements.

= 2.2.1 (27, Oct 2020) =
* [*] Minor  Update - Fixed conflict from tgmpa (theme pluign recommends - if your theme using tgmpa library) where it was showing message inside "How It Works - Display and shortcode"

= 2.2 (22, Oct 2020) =
* [+] New - Click to copy the shortcode from the getting started page.
* [*] Update - Regular plugin maintenance. Updated readme file.
* [*] Added - Added our other Popular Plugins under Slick Slider --> Install Popular Plugins From WPOS. This will help you to save your time during creating a website.

= 2.1 (14, Aug 2020) =
* [*] jQuery( document ).ready(function($) is replaced with function( $ ) to solve the issue with 3rd party plugin and theme js error.

= 2.0.2 (14-07-2020) =
* [*] Follow WordPress Detailed Plugin Guidelines for Offload Media and Analytics Code.

= 2.0.1 (07, July 2020) =
* [*] Due to lots of feedback from your users side, we have made image_fit="false" by default. Previously it was image_fit="true". We made image resize option now optional. If you want to resize the image, please use image_fit="true" and sliderheight="400" (400 is just an example. Please use this value as per your need) shortcode parameters.
* [*] Fixed some design related issues.
* [*] Tested up to: 5.4.2

= 2.0 (17, April 2020) =
* [+] New - Added Gutenberg block support. Now use plugin easily with Gutenberg!
* [+] New - Added 'align' and 'extra_class' parameter for slider shortcode. Now both slider shortcode are support twenty-ninteent and twenty-twenty theme gutenberg block align and additional class feature.
* [+] New - Add new classes sanatize function in function file.
* [*] Tweak - Code optimization and performance improvements.

= 1.9.3 (06, April 2020) =
* [+] Added new shortcode parameter loop and hover_pause for both shortcode
* [+] loop  : Create a Infinite loop sliding. By default value is "true". Options are "true" OR "false".
* [+] hover_pause  : Pause slider autoplay on hover. By default value is "true". Options are "true" OR "false".

= 1.9.2 (05, March 2020) =
* [+] Added new shortcode parameter lazyload="ondemand" OR lazyload="progressive" for both shortcodes.

= 1.9.1 (26, Dec 2019) =
* [*] Updated features list.
* [*] Replaced wp_reset_query() with wp_reset_postdata()
* [*] Added prefix wpsisac- to all classes in css to avoid conflict with any theme and third-party plugins.

= 1.8 (08, August 2019) =
* [*] Update demo links
* [*] Fixed some small-small issues.
* [*] Updated text under featred image ie Add slider image.

= 1.7.1 (31, May 2019) =
* [+] Added new shortcode parameter ie image_fit="true". image_fit parameter is used to specify how an image should be resized to fit its container. By default value is "true". Options are "true OR false". NOTE : NOTE :  image_fit="true" work better if sliderheight is given. if image_fit="false", no need to use sliderheight parameter.  
* [*] image_fit parameter work with both the shortcode.
* [+] Added new shortcode parameter ie image_size="full" for shortcode [slick-slider] (Default is "full", values are thumbnail, medium, medium_large, large, full)
* [-] Removed default height 400 from sliderheight parameter.
* [-] Remove object-fit CSS property from img under CSS if image_fit="false".

= 1.6.2 (12, Feb 2019) =
* [*] Minor change in Opt-in flow.

= 1.6.1 (26, Dec 2018) =
* [*] Update Opt-in flow.

= 1.6 (06, Dec 2018) =
* [*] Tested with WordPress 5.0 and Gutenberg.
* [*] Fixed slider height issues with some designs.
* [*] Taken better security with `esc_url` and `esc_html`. 
* [*] Fixed some CSS issues.

= 1.5.1 (05, June 2018) =
* [*] Follow some WordPress Detailed Plugin Guidelines.

= 1.5 (10/3/2018) =
* [*] Fixed  some css issues related to slider arrow.

= 1.4 (10/3/2018) =
* [*] Fixed  some css issues

= 1.3.4 (04/10/2017) =
* [*] Fixed all responsive issues and checked many mobile devices.
* [*] If you are using any cache plugin, please clear your cacheing after plugin updates

= 1.3.3 (04/10/2017) =
* [*] Updated slick.min.js to the latest version
* [*] Fixed all responsive issues and checked many mobile devices.
* [*] If you are using any cache plugin, please clear your cacheing after plugin updates

= 1.3.2.1 (27/09/2017) =
* [*] Fixed design-6 issue with shortcode parameter variablewidth="true" in responsive layout
* [*] If you are using any cache plugin, please clear your cacheing after plugin updates

= 1.3.2 (23/09/2017) =
* [*] Fixed design-6 issue with shortcode parameter variablewidth="true"
* [*] If you are using any cache plugin, please clear your cacheing after plugin updates 

= 1.3.1.1 (23/09/2017) =
* [*] Fix responsive issue reported by users in v-1.3.1 
* [*] If you are using any cache plugin, please clear your cacheing after plugin updates 

= 1.3.1 (22/09/2017) =
* [*]  Fix main JS wp_register_script issue

= 1.3 (22/09/2017) =
* [+] Added **sliderheight** parameter in shortcode <code>[slick-carousel-slider]</code>
* [*] RTL made better to work with RTL websites
* [*] Center mode and variablewidth improved better as per usres feedback 
* [*]  **sliderheight** parameter improved

= 1.2.8 (22/05/2017) =
* [+] RTL Supported

= 1.2.7 (25/04/2017) =
* [+] Added overlay for design-2

= 1.2.6 (07/11/2016) =
* [+] Added "How it work tab"
* [-] Removed Pro design tab

= 1.2.5 (20/10/2016) =
* Updated all the designs and fix the bug
* Fixed image display issue on mobile
* Replaced arrow images

= 1.2.4 =
* Updated slider js to latest version.
* Updated plugin design page.

= 1.2.3 =
* Fixed some css issues.

= 1.2.2 =
* Fixed some css issues.
* Resolved multiple slider jquery conflict issue.

= 1.2.1 =
* Fixed some bug
* Added Pro version with 16 designs

= 1.2 =
* Fixed some bug
* Added link to carousel mode

= 1.1 =
* Fixed some bug
* Added Limit

= 1.0 =
* Initial release.