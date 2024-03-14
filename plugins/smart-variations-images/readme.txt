=== Smart Variations Images & Swatches for WooCommerce ===
Contributors:  drosendo
Tags: woocommerce, variations, additional images, product variations, image gallery, WooCommerce swatches, gallery, swatches, ecommerce  
Requires at least: 4.9 
Tested up to: 6.4
Stable tag: 5.2.14
Requires PHP: 7.4
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Boost your WooCommerce sales by adding additional gallery images and swatches to variable products with ease.

## Description

Smart Variations Images & Swatches is a powerful WooCommerce extension that enhances your product image gallery and adds swatches for an improved shopping experience. Designed to optimize your workflow, this plugin allows you to upload images only once for each variation gallery.

[youtube https://youtu.be/QMV8XBeub_o]

By default, WooCommerce only swaps the main variation image, not the gallery images below it. This extension enables visitors to swap different gallery images when they select a product variation, providing a more comprehensive view of each product variation. Additionally, SVI replaces dropdown fields for your variable products with color, image, or label swatches for a more user-friendly display.

**Make the smart choice!** [Read the article](https://www.smart-variations.com/additional-images-woocommerce-variations/)

## Features

### Free Features

[Live Demo](http://svi.rosendo.pt/free) | [Support](https://wordpress.org/support/plugin/smart-variations-images/)

* Unlimited additional images for each variation
* Drag & Drop custom sorting option
* Trigger gallery change on single attribute change
* Variation Swatches and Photos
* Stacked Layout Display
* Display 1 Image under Variation Selection
* Showcase Variations on product loop pages
* Slider with navigation arrows
* Magnifier Lens with Lens, Window, or Inner display
* Lightbox
* Hide thumbnails until variation is chosen
* WPML Compatible
* Compatible with page builders
* Compatible with other Swatches Plugins
* Compatible with popular themes
* Responsive design

### Premium Features

[Live Demo](https://svi.rosendo.pt/pro) | [Upgrade to PRO](https://www.smart-variations.com/smart-variations-images-pro/) | [Support](https://www.smart-variations.com/)

* All Free Features plus:
* Video Support
* Advanced Slider/Lightbox/Magnifier Lens options
* Stacked Layout
* Trigger image swap on specific attribute change
* Add variation image to Cart / Email / Admin order Edit / Order details
* API actions
* Import/Export handling
* And much more...

## Installation

### Automatic Install From WordPress Dashboard

1. Log in to your admin panel
2. Navigate to Plugins -> Add New
3. Search for **Smart Variations Images & Swatches**
4. Click install and activate respectively

### Manual Install From WordPress Dashboard

1. Download the plugin
2. Log in to your site's admin panel and navigate to Plugins -> Add New -> Upload
3. Click choose file, select the plugin file, and click install

### Install Using FTP

1. Download the plugin
2. Unzip the file
3. Launch your favorite FTP client (e.g., FileZilla, FireFTP, CyberDuck). Advanced users can use SSH
4. Upload the folder to `wp-content/plugins/`
5. Log in to your WordPress dashboard
6. Navigate to Plugins -> Installed
7. Activate the plugin

## Frequently Asked Questions

**Q: Is it compatible with any Theme?**

A: Yes! Themes that follow the default WooCommerce implementation will usually work with this plugin. However, some themes use an unorthodox method to add their own lightbox/slider, which breaks the hooks this plugin needs.

**Q: Does it support page builders?**

A: Yes! Although some builders take over the design and hooks of the pages running them. If you run into issues, I can probably figure something out!

**Q: How do I configure it to work?**

1. Create a product and build its attributes & variations
2. Go to "SVI Variations Gallery" tab and set up the galleries according to the variations you want displayed
3. Save the product
4. Go to WooCommerce > SVI > Global TAB and "Enable SVI" so that it works on the Front-End
5. Good luck with sales :)
6. Watch the video if you have doubts (https://youtu.be/QMV8XBeub_o)

**Q: What Browsers does SVI support?**

A: SVI doesn’t support IE since it is no longer maintained by Microsoft since at least 2015 and doesn’t support ECMAScript. [READ MORE](https://microsoft.com/en-us/windowsforbusiness/end-of-ie-support)

SVI is tested to run on:
- Microsoft EDGE
- Safari
- Chrome
- Firefox

**Q: What happens to my theme default gallery display?**

A: SVI replaces your default theme settings/options for the image & thumbnails area, so don't expect to use any of your theme features for this area. Otherwise, SVI wouldn't be able to do the magic. Each theme has its own structure, and it wouldn't be feasible to create all the available layouts combinations on the same plugin, so SVI just had to build its own layout.

## Screenshots

1. Display Images according to variation
2. Lightbox
3. Set up the combinations
4. Display variations under Variations Select
5. Display variations on Product Loop Pages
6. Replace dropdown fields for your variable products with Swatches
7. Setup swatches on Product > Attributes


== Changelog ==

= 5.2.14 =
* Update WC,WP version compatibility
* Fix Slider Lens Issue

= 5.2.13 =
* Update WC,WP version compatibility
* Updated Vue.js to version 2.7.15 for improved compatibility.
* Updated vue-loader to version 15.11.1 to match Vue.js version.
* Updated laravel-mix to version 6.0.49 for enhanced build capabilities.
* Upgraded laravel-mix-polyfill to version 3.0.1 for better cross-browser support.
* Updated node-sass to version 9.0.0 for compatibility with the latest Node.js.
* Upgraded postcss to version 8.4.32 for improved CSS processing.
* Updated sass-loader to version 13.3.2 for enhanced SASS support.
* Fix PHP warnings
* Fix video player Aspect Ratio 

= 5.2.12 =
* Resolved an issue where only the SVI meta data was being exported. All relevant metadata will now be correctly included in exports.
* Video support 1:1
* Update WC,WP & Freemius SDK 2.6

= 5.2.11 =
* Fix possible Fatal error: Uncaught TypeError on Backwards compatibility
* Update WC,WP & Freemius SDK 2.5.12 version compatibilty 

= 5.2.10 =
* Fix readme file

= 5.2.9 =
* Update WC,WP & Freemius SDK version compatibilty 
* Added ALT attribute option

= 5.2.8 =
* Update WC,WP & Freemius SDK version compatibilty 
* Fix possible Video/slider delay

= 5.2.7 =
* Update WC,WP & Freemius SDK 2.5.8 version compatibilty 
* Fix .hidden class could conflict
* Added alert on product SVI Gallery when attribute changed or missing 
* Fix error notices


= 5.2.6 =
* Update WC version compatibilty 
* Update WP version compatibilty 
* Update Freemius SDK 2.5.6
* Added Product Image shortcode to be used in UX Builders or other ocassions


= 5.2.5 =
* Fix SVI Default image swapping
* Fix Swiper Cube effect

= 5.2.4 =
* Update Swiper v8
* Fix Swiper arrows color white/black/blues
* Fix Swiper Bullets clickable

= 5.2.3 =
* Fix Swatches Display
* Update Freemius SDK v2.5.3
* Update WC version compatibilty 
* Update WP version compatibilty 

= 5.2.2 =
* Update WC version compatibilty 
* Update WP version compatibilty 
* Updated slider version to SwiperJS v8.4.4
* Updated Video player to Plyr v3.7.2
* Fix Video AutoPlay/Loop/Ratio

= 5.2.1 =
* Fix fatal error due to most not using PHP 8 str_ends_with()

= 5.2.0 =
* Removed ReduxFramework for admin Management
* Fix slider image jump on navigation slider
* Fix outline on swiper lazyLoad
* Unlocked some PRO features now FREE

= 5.1.12 =
* Update WC compatibility 6.5
* Fix compatibility with builders

= 5.1.11 =
* Update WC compatibility 6.4
* Updated slider version to SwiperJS v8
* Updated Video player to Plyr 3.7.0
* Unlocked Stacked Option
* Unlocked Multiple variation gallery creation


= 5.1.10 =
* Update Freemius SDK 2.3.4
* Update WC compatibility 6.3

= 5.1.9 =
* Improved Cart/order/email Image matching
* Compatability up to WP 5.9.1
* Compatability up to WC 6.2
* Fix YITH WooCommerce Badge Management compatibility

* Fix possible issue with swacthes not working in bundled products

 
= 5.1.8 =
* WC compatibility 6.1

* Added Support for Razzi Theme

= 5.1.7 =
* Fix possible conflict with Slider duplicate arrows due to outdated slider version by other themes/plugins
* Updated reduxFramework core from 4.1.29 to 4.3.5

= 5.1.6 =
* Fix slider thumbnail navigation fast clicks from highlithing images
* Add Yith Badge Management compatibility
* Fix Admin order show Image if no images exist fallback to placeholder image
* Added action to assist integration to product gallery "svi_before_images"

= 5.1.5 =
* Updated slider version to SwiperJS v7
* Fix slider LazyLoad

= 5.1.4 =
* Properly check condition for running Swatches

= 5.1.3 =
* Properly add thumbnail classes to thumbnail images


= 5.1.2 =

* Fix possible issue with single product not loading images. This would occour if the product previously was a Variation.
* Udpated WooCommerce compatibility to v5.8.0

= 5.1.1 =

* Fix Undefined property: stdClass::$default_swatches warning
* Better handle late init variations
* Improved compatibilty with builders

= 5.1.0 =

* Improved Divi Compatibility to handle Divi Theme templates
* [NEW Feature] Variation Swatches and Photos


= 5.0.23 =
- porto theme fix Skeleton issue SVI gallery not showing
- update reduxFramework struture
- Added extra compatibilyt with Porto custom product template

= 5.0.22 =
- Update compatibility WooCommerce v5.5.2

= 5.0.21 =
- Increase find similar slug % if slug name changed.
- Update compatibility WooCommerce and WordPress

= 5.0.20 =
- Start SVI enabled
- Allow export data with full image paths
- Compatability with WC 5.5.0

= 5.0.19 =
- added ignored files for fs processing
- Webpack properly extract CSS

= 5.0.18 =
* Added WordPress compatibility up to 5.7.3
* Fix notice error showing in 404 pages.
* Compatibility with WooCommerce 5.2.2



= 5.0.17 =
* Adjust vertical Slider thumbnail height
* Update Swiper Version v6.5.6



= 5.0.16 =
* Updated WordPress version compatibility
* Updated Freemius Version


= 5.0.15 =
* Make sure SVI files are registred for usage when needed

= 5.0.14 =
* Make admin SVI files load only where needed

= 5.0.13 =
* Improved Divi Compatibility to handle Divi Theme templates - thanks @crystalfyre 

= 5.0.12 =
* Prevent SVI from loading JS in pages where that dont use WooCommerce templates


= 5.0.11 =
* Compatibility with WooCommerce 5.0.0



= 5.0.10 =
* Fix SVI galleries not properly being created for new products since v5.0.7

= 5.0.9 =
* Fix custom attribute single quote mismatch

= 5.0.8 =
* Improved Divi Compatability


= 5.0.7 =
* Improved Admin create SVI galleries response


= 5.0.6 =
* Fix notices
* Improved compatibility with third party plugins swatches


= 5.0.5 =
* Fix SVI Default Gallery not appearing correctly on Product Edit
* Fix possible issue with no SVI galleries created preventing add to cart
* Prevent multiple runs for same attributes


= 5.0.4 =
* Improved slider transition between svi galleries
* Fix WPML improved support
* Divi Compatability fixed


= 5.0.3 =
* Fix incorrect object comparison Equivalent
* Fix issue with creating SVI gallery slug sensitive mismatch 

= 5.0.2 =
* Fix typo runActiveTietle
* Fix ligthbox for InnerZoom
* Fix missing video thumbnail on ligthbox

= 5.0.1 =
* Compatability to custom attributes names using commas & quotes

= 5.0.0 =
* Full code rewrite
* New options
* Faster loading
* PHP 8 compatible
