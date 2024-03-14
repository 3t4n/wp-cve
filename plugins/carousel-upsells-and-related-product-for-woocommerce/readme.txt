===  Carousel Upsells and Related Product for Woocommerce ===
Contributors: alexodiy, alexandr3000
Donate link: https://www.paypal.com/donate/?cmd=_donations&business=lushin.alexandr%40gmail.com&item_name=Plugin%20author%20support+-+Carousel+Upsells+and+Related+Product+for+Woocommerce&currency_code=USD
Tags: related carousel, upsells carousel, woocommerce carousel, product carousel, carousel, carousel javascript, woocommerce slider, woocommerce product carousel, woocommerce product slider, related products, сarousel upsells, related products slider
Requires at least: 4.8
Tested up to: 5.8
Stable tag: 0.4.6
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
The plugin replaces the standard related and upsells products on carousel slider using a script glide.js that does not depend on the jquery, which much faster than its analogues.

Simply activate the plugin and a carousel of related products will already appear in your store. Among other things, you can separately configure related products and recommended(Upsells) products.

The design of the product cards will match the design of your template, but it should be noted that some templates have their own custom carousels of recommended or related products.

== Settings plugin ==

1. Woocommerce &rarr; Settings &rarr; Products &rarr; Related Product Carousel
2. Woocommerce &rarr; Settings &rarr; Products &rarr; Upsells Product Carousel

== Features: ==

1. Replaces the standard output of related products and recommended(upsells) products with a carousel
2. No dependence on the jquery, loading is very fast
3. You can change the titles of the standard sections
4. You can enable or disable autoplay
5. You can change the time interval for scrolling the carousel
6. You can specify the number of products in the carousel
7. You can specify the number of visible products (specify the grid)
8. You can specify the number of displayed products on mobile devices and tablets
9. Standard accompanying and recommended products not displayed if their number is less than the indicated visible products in the carousel. That is, a carousel is not created. Only high-quality optimization.
10. You can disable the carousel and control other functions - section header, number of displayed products, number of columns

== New advanced settings ==

1. Hint on a mobile device (see. Screenshots)
2. Styling a mobile hint (see. Screenshots)
3. Central mode with cropping (see. Screenshots)
4. Central mode on mobile device only
5. Choosing a carousel transition animation
6. Setting duration of the transition animation
7. Setting the distance between products
8. The choice of navigation icons (see. Screenshots)
9. Setting the color of navigation

All new options have tips right in the admin panel. You can always fine-tune the carousel.

== Plugin Benefits ==

The most important advantage of the "Carousel Upsells and Related Product for Woocommerce" plugin is that it uses a javascript library of glide.js and has no jQuery dependencies. The main JS file of the plugin weighs only ~ 23kb, and in compressed form only ~ 7kb. Compared to similar carousel-slides, such as Slick Slider (88kb, and in compressed 44kb), Swiper Slide (more than 100kb) or OWL carousel (89kb, and in compressed 44kb) is many times smaller and I will remind glide.js without jQuery dependencies. With all this, glide.js has Touch Swipe mode, which allows the finger (touch on the element) to move the carousel in the right direction.

As a result, this carousel works faster and directly instantly loads your goods in the carousel. By the way, remember to optimize your images, now owners of online stores fight for each kb as search engines love fast sites.

== Required Plugins ==
* [WooCommerce](https://wordpress.org/plugins/woocommerce/)

== Great thanks ==

* Thanks for the wonderful javascript carousel Glide.js [Jędrzej Chałubek](https://glidejs.com/)
* Thanks for the help [Сampusboy](https://profiles.wordpress.org/campusboy1987/)
* Thanks for the help [KAGG Design](https://profiles.wordpress.org/kaggdesign/)
* Thanks for helping the developer [Artem Abramovich](https://profiles.wordpress.org/artabr/)
* For help [Telegram chat "WordPress & WooCommerce" and all participants](https://t.me/c_wordpress)
* For the best documentation in Russian by WordPress [Site wp-kama.ru](https://wp-kama.ru/)

== Donate link: ==
<a href="https://www.paypal.com/cgi-bin/webscr?&amp;cmd=_xclick&amp;business=studia55x5@yandex.ru&amp;currency_code=USD&amp;amount=16&amp;item_name=On coffee for the developer" target="_blank">Pay with Paypal</a>

== Translations ==

If you wish to help translate this plugin, you are most welcome!
To contribute, please visit [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/carousel-upsells-and-related-product-for-woocommerce/)

== Installation ==

This section describes how to install the plugin and get it working.

Install From WordPress Admin Panel:

1. Login to your WordPress Admin Area
2. Go to Plugins -> Add New
3. Type "**Woocommerce Glide.js Carousel Upsells and Related Product**" into the Search and hit Enter.
4. Find this plugin Click "install now"
5. Activate The Plugin

Manual Installation:

1. Download the plugin from WordPress.org repository
2. On your WordPress admin dashboard, go to ‘Plugins -> Add New -> Upload Plugin’
3. Upload the downloaded plugin file (carousel-upsells-and-related-product-for-woocommerce.0.4.6.zip) and click ‘Install Now’
4. Activate ‘**Woocommerce Glide.js Carousel Upsells and Related Product**’ from your Plugins page.

== Frequently Asked Questions ==

= Carousel does not work correctly, what should I do? =

If the carousel doesn't work, check the caching plugins first. Reducing the size of the JS code is a fairly common reason why carousels stop working.

For example, if you are using the Autoptimize plugin, you can test the functionality of our carousel plugin without the Autoptimize plugin running. In this case, it is not necessary to deactivate the Autoptimize plugin, it is enough to add the prefix <strong>?ao_noptimize=1</strong> in the root <a href="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/auto_1.png" target="_blank">of the URL</a>. In case the carousel starts working, just <a href="https://wordpress.org/support/topic/visible-products-not-working/#post-13801509" target="_blank">add an exception</a> to the Autoptimize plugin. <a href="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/auto_2.png" target="_blank">This is how it looks</a>.

If you are using WP Rocket then the problem is fixed in the same way as in the case of Autoptimize. <a href="https://wordpress.org/support/topic/load-javascript-deferred/#post-13750605">Check out this post</a>.

Please create a topic in the forum support. I will try to provide assistance as quickly as possible. The plugin is sophisticated, let's make it better together. Just waiting for your recommendations and suggestions

== Screenshots ==

1. Settings Plugin 1
2. Settings Plugin 2
3. Demo central mode
4. General Setting Related Products
5. Additional carousel settings
6. Tooltip settings on a mobile device
7. This is what the prompt looks like on mobile resolution
8. Settings central mode for mobile device only
9. Example of displaying prompts on a smartphone
10. Central mode in various resolutions
11. The design depends on the style of your theme.
12. The design depends on the style of your theme.
13. The design depends on the style of your theme.
14. The design depends on the style of your theme.

== Changelog ==

= 0.4.6 =
* Fixed an error in navigation arrows

= 0.4.5 =
* Tested WP ver 5.8

= 0.4.4 =
* Tested WP ver
* Added a description for solving problems with caching plugins

= 0.4.3 =
* Tested WP ver

= 0.4.2 =
* Updating details

= 0.4.1 =
* Plugin tested with WordPress version 5.5+

= 0.4.0 =
* Fixed carousel display in some themes

= 0.3.9 =
* Added a new function - initializing JS after loading the whole page

= 0.3.8 =
* Plugin tested with WordPress version 5.4+

= 0.3.7 =
* Added translation string for mobile tooltip

= 0.3.6 =
* Update CSS

= 0.3.5 =
* Fixed a bug when choosing a navigation arrow in related products
* Added new settings for themes that apply filters and functions

= 0.3.4 =
* Fixed links for installed WordPress in a subfolder

= 0.3.3 =
* Update CSS

= 0.3.2 =
* Update style setting

= 0.3.1 =
* Fixed string translation

= 0.3.0 =
* Last testing of new hooks and plugin update
* New option - Tooltip on mobile device
* New option - Styling a mobile tooltip
* New option - Center mode with cropping
* New option - Central mode only on mobile device
* New option - Select carousel transition animation
* New option - Set transition animation duration
* New option - Setting the distance between products
* New option - Select navigation icons
* New option - Adjust the color of navigation
* Added tooltips in admin panel
* Added demo windows with carousels

= 0.2.9 =
* Last testing of new hooks and plugin update

= 0.2.8 =
* Fix error notice

= 0.2.7 =
* Fix error update hook

= 0.2.6 =
* Test update hook

= 0.2.5 =
* Test WordPress 5.3
* Add notice
* New lines in localization

= 0.2.4 =
* Test new version WooCommerce

= 0.2.3 =
* Update CSS

= 0.2.2 =
* Update CSS

= 0.2.1 =
* Fix error

= 0.2.0 =
* Fix error

= 0.1.9 =
* Fix error text-domain

= 0.1.8 =
* Fix modal error

= 0.1.7 =
* New interface
* New POT file
* Update CSS
* Update JS
* Added a new feature - turn off the carousel

= 0.1.6 =
* Update JS

= 0.1.5 =
* Update CSS

= 0.1.4 =
* Update CSS

= 0.1.3 =
* Update CSS

= 0.1.2 =
* Fix error nav slider
* Add hover effect in navigation

= 0.1.1 =
* Presentation of the settings of the first version of the plugin

= 0.1.0 =
* Release