=== Woo Image SEO ===
	Contributors: emandiev
	Tags:  WooCommerce, Woo, Woo SEO, product alt, product seo
	Requires PHP: 7.0
	Stable tag: 1.4.2
	Requires at least: 4.1
	Tested up to: 6.4.1
	License: GPLv3 or later
	License URI: https://www.gnu.org/licenses/gpl-3.0.html

== Description ==

Boost your WooCommerce SEO by automatically adding alt tags and title attributes to product images.
No configuration required.
Attributes can include various data like:
- Product Name
- Product Category
- Product Tag
- Site Name
- Site Description
- Site Domain
- Current Date
- ...or any custom text
Works only with the <a href="https://wordpress.org/plugins/woocommerce/">WooCommerce</a> plugin.<br />

== Installation ==

1. Visit <strong>Plugins > Add New</strong>
2. Search for "<strong>Woo Image SEO</strong>"
3. Download and Activate the plugin.

== Frequently Asked Questions ==

= What are the requirements? =

The WooCommerce plugin by Automattic.

= What are image "alt" tags or attributes? =

Alt text, also known as "alt attributes" or "alt tags", are used in HTML to describe the contents of an image.
Adding alternative text to photos is a principle of web accessibility. Visually impaired users using screen readers will be read an alt attribute to better understand an on-page image.
Alt tags will be displayed in place of an image if an image file cannot be loaded.
Alt tags provide better image context/descriptions to search engine crawlers, helping them to index an image properly.

= Why should I care? =

Adding appropriate alt attributes will improve your SEO. Better ranking should lead to more traffic!

= What will the plugin actually do? =

The plugin will use each product's title (name) to add alt and title attributes to the product's images.
Example:
You have a product called "Amazing Shirt".
The plugin's images will get alt="Amazing Shirt" and title="Amazing Shirt".

You can also enable/disable the generation of each attribute and choose whether to allow for user-specified attributes by going to WooCommerce -> Woo Image SEO.
You can also customize the way this plugin creates attributes.
For example, you may want to include each product's category in the alt tags, or even its tag.

Your actual files or database won't be modified.
Once you disable the plugin, the automatically generated attributes will be gone.

= Will this plugin affect the performance in a bad way? =

No.
The plugin will not cause any noticeable slowdown.
It's designed to help improve your website's SEO.
The plugin won't load any additional files.

== Changelog ==

= 1.4.2 =
* 02/12/2023:
  Remove affiliate banner.
  Add support for WordPress 6.4.1 and WooCommerce 8.3.1

= 1.4.1 =
* 03/9/2023:
  Ensure compatibility with High-Performance Order Storage (HPOS)
  Add support for WordPress 6.3.1 and WooCommerce 8.0.3
  Bump required PHP version to 7.0

= 1.4.0 =
* 23/7/2022:
  Add 4 new Attribute Builder options - Site Name, Site Description, Site Domain, and Current Date.
  Remove unused code.
  Add affiliate banner.

= 1.3.0 =
* 07/6/2022:
  Allow skipping images using the "woo-image-seo-skip" class.
  Replace the Support box with Tips.
  Improve the Feedback form.
  Improve i18n support.
  Add support for WordPress 6.0 and WooCommerce 6.5.1

= 1.2.6 =
* 17/4/2022:
  Add support for WordPress 5.9.3 and WooCommerce 6.4.1

= 1.2.5 =
* 24/8/2021:
  Fix a bug introduced in version 1.2.4

= 1.2.4 =
* 8/8/2021:
  Fix possible issues.
  Add support for WordPress 5.8 and WooCommerce 5.5.
  Improve performance.

= 1.2.3 =
* 18/5/2021:
  Fix possible JS errors in admin dashboard.
  Ensure WooCommerce 5.3 compatibility.

= 1.2.2 =
* 12/4/2021:
  Remove deprecated jQuery code.
  Add WooCommerce version requirements.
  Add Russian translation.
  Add Bulgarian translation.
  Add help text in media library modal.
  Improve settings page styling.

= 1.2.1 =
* 25/1/2021:
  Fix PHP notice

= 1.2.0 =
* 24/1/2021:
  Added the ability to append image numbers (indexes) to attributes.
  Added more social share links.
  Improved settings page styling.

= 1.1.0 =
* 19/12/2020:
  First major update!
  Added the ability to use your custom texts in the attribute builder.
  Improved settings page styling, help texts.
  Fixed a few minor bugs.
  Added full support for WordPress 5.6

= 1.0.2 =
* 18/8/2020:
  Improved settings page.
  Prepare for WordPress 5.5

= 1.0.1 =
* 11/3/2019:
  Code improvements.
  The plugin will no longer generate PHP notices.

= 1.0.0 =
* 21/12/2018:
  Initial release;
