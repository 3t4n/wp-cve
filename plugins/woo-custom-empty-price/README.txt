===  Woo Custom Empty Price ===
Contributors: chrisjallen
Donate link: https://paypal.me/chrisjimallen
Tags: banner, author, author url, profile, profile url, custom url, custom link
Requires at least: 3.0.1
Requires PHP: 5.6
Tested up to: 6.4
Stable tag: 1.1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Show text, a call to action or custom HTML when a product has no price set.

== Description ==

This plugin will allow you to completely customise the empty price HTML on a single product page. It can be used to add a 'CONTACT FOR PRICING' call to action button, 
some advisory text, or any custom HTML you choose.

There are three 'content types' available for flexibility & ease of use:

1. A simple text box with options for bold or italic styling
2. A button with an optional link
3. An option to add your own custom HTML

By default it will use the styles available in your custom theme, but each content type has a configurable CSS class, should you wish to style the content further. 

Note on variable products:

The custom content will only show on variable products if none of the variations have a price set. Due to the nature
of variable products, this will result in an out of stock message being displayed. If you do not wish to have this out of stock message, the 
simplest option is to only use this on 'Simple' or 'Grouped' products.


== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'
2. Search for 'Woo Custom Empty Price'
3. Activate 'Woo Custom Empty Price' from your Plugins page.
4. Visit 'Woo Custom Empty Price Options' in the 'Plugins' submenu to access the settings.

= From WordPress.org =

1. Download 'Woo Custom Empty Price'.
2. Upload the 'woo-custom-empty-price' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. Activate 'Woo Custom Empty Price' from your Plugins page.
4. Visit 'Woo Custom Empty Price Options' in the 'Plugins' submenu to access the settings.

== Frequently Asked Questions ==

= Can I set the button to open a popup, for say a contact form? =

Technically, yes, as long as you use the custom HTML option. As the custom HTML will not allow you to add
your own script tags for security reasons, you  will have to enqueue your own Javascript seperately from the plugin. Your best option
is to use a Wordpress [Shortcode](https://codex.wordpress.org/Shortcode). Support for shortcodes was added in V1.1.0.

I am considering adding the ability to enqueue your own scripts as a built-in feature, so if you require this kind of functionality, just ask.

= Do you have any other useful plugins? =

Yes, I have two other ( completely free ) plugins called WP Dev Flag & WP Custom Author URL, both on the Wordpress Repository here: 

[WP Dev Flag](https://wordpress.org/plugins/wp-dev-flag/)
[WP Custom Author URL](https://wordpress.org/plugins/wp-custom-author-url/)


== Screenshots ==

1. Plain Text settings:
2. Call To Action settings:
3. Custom HTML settings:

== Changelog ==

= 1.1.5 =
* Remove unnecessary hook that was causing a warning.

= 1.1.4 =
* Fixed bug causing errors on some themes, eg. Storefront.

= 1.1.3 =
* Full code factoring which no longer uses WPPB, resulting in a much lighter codebase. 
  Also the plugin now uses its own override template for single-product/price.php to make it more consistent across themes.

= 1.1.2 =
* Updated deprecated jQuery .load() function to use .on()

= 1.1.0 =
* Added shortcode support to HTML content type.

= 1.0.0 =
* First Version.

== Upgrade Notice ==

= 1.1.0 =
Added shortcode support to HTML content type.

= 1.0.0 =
This is the first version.
