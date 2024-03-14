=== Custom CSS and JavaScript ===
Contributors: aspengrovestudios, annaqq
Tags: css, custom css, styles, custom styles, stylesheet, custom stylesheet, javascript, custom javascript, js, custom js
Requires at least: 3.5
Tested up to: 6.3.0
Stable tag: 2.0.16
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Easily add custom CSS and JavaScript code to your WordPress site, with draft previewing, revisions, and minification!

== Description ==

This plugin allows you to add custom site-wide CSS styles and JavaScript code to your WordPress site. Useful for overriding your theme's styles and adding client-side functionality.

Features:

* Code editor with syntax highlighting and AJAX saving to avoid reloading the editor at each save.
* Save and preview your CSS and JavaScript as a draft that is only applied to logged-in users with the necessary permissions until you are ready to publish your changes to the public.
* View and restore past revisions of your CSS and JavaScript.
* Automatically minify your custom CSS and JavaScript code to reduce file size.
* For the public, custom CSS and JavaScript code is served from the filesystem instead of the database for optimal performance.

Now available! [Custom CSS and JavaScript Developer Edition](https://wpzone.co/product/custom-css-and-javascript-developer-edition/?utm_source=custom-css-and-javascript&utm_medium=link&utm_campaign=wp-repo-upgrade-link):

* Divide your CSS and JavaScript into multiple virtual files to keep your code organized (the code is still served as one CSS and one JS file on the front-end for efficiency).
* Supports Sassy CSS (SCSS)!
* Live preview for CSS!
* Upload and download CSS and JavaScript files, individually or in ZIP files.
* The developer logo and review/donation links are removed from the editor page in the WordPress admin.

[Click here](https://wpzone.co/product/custom-css-and-javascript-developer-edition/?utm_source=custom-css-and-javascript&utm_medium=link&utm_campaign=wp-repo-upgrade-link) to purchase!


Alternatively, you can manually upload the plugin to your wp-content/plugins directory.

If you like this plugin, please consider leaving a comment or review.

## User Access Control

In the Custom CSS and JavaScript WordPress plugin, access to plugin features is determined by user capabilities. Users with the `edit_theme_options` capability will enjoy full access to all the plugin's features.

By default, the "Administrator" and "Editor" roles come equipped with the `edit_theme_options` capability. However, site administrators have the flexibility to customize these capabilities and assign them to other roles or individual users through plugins or custom code.

Furthermore, to provide users with enhanced control, we've introduced a custom capability known as `wpz_custom_css_js`. Users possessing both the `wpz_custom_css_js` and `edit_posts` capabilities can be granted access to the plugin's features.

## You may also like these plugins
[WP Zone](https://wpzone.co/) has built a bunch of plugins, add-ons, and themes. Check out other favorites here on the repository and don’t forget to leave a 5-star review to help others in the community decide.

* [Product Sales Report for WooCommerce](https://wordpress.org/plugins/product-sales-report-for-woocommerce/) - set up a custom sales report for the products in your WooCommerce store with toggle sorting options. Including or excluding items based on date range, sale status, product category and id, define display order, choose what fields to include, and generate your report with a click.
* [Export Order Items for WooCommerce](https://wordpress.org/plugins/export-order-items-for-woocommerce/) - export the order details for each sale in your WooCommerce store. Simplify order fulfillment, generate accounting reports in a few clicks, and download into CSV format for readability and universal compatibility with Export Order Items.
* [Replace Image](https://wordpress.org/plugins/replace-image/) – keep the same URL when uploading to the WordPress media library
* [Force Update Check for Plugins and Themes](https://wordpress.org/plugins/force-update-check-for-plugins-and-themes/) -force Update Check for Plugins and Themes forces WordPress to run a theme and plugin update check whenever you visit the WordPress updates page
* [Connect SendGrid for Emails](https://wordpress.org/plugins/connect-sendgrid-for-emails/) -  connect SendGrid for Emails is a third-party fork of (and a drop-in replacement for) the official SendGrid plugin
* [Custom CSS and JavaScript](https://wordpress.org/plugins/custom-css-and-javascript/) - allows you to add custom site-wide CSS styles and JavaScript code to your WordPress site. Useful for overriding your theme’s styles and adding client-side functionality.
* [Disable User Registration Notification Emails](https://wordpress.org/plugins/disable-user-registration-notification-emails/) - when this plugin is activated, it disables the notification sent to the admin email when a new user account is registered.
* [Inline Image Upload for BBPress](https://wordpress.org/plugins/image-upload-for-bbpress/) - enables the TinyMCE WYSIWYG editor for BBPress forum topics and replies and adds a button to the editor’s “Insert/edit image” dialog that allows forum users to upload images from their computer and insert them inline into their posts.
* [Password Strength for WooCommerce](https://wordpress.org/plugins/password-strength-for-woocommerce/) - disables password strength enforcement in WooCommerce.
* [Potent Donations for WooCommerce](https://wordpress.org/plugins/donations-for-woocommerce/) – acceptance donations through your WooCommerce store
* [Shortcodes for Divi](https://wordpress.org/plugins/shortcodes-for-divi/) - allows to use Divi Library layouts as shortcodes everywhere where text comes.
* [Stock Export and Import for WooCommerce](https://wordpress.org/plugins/stock-export-and-import-for-woocommerce/) - generates reports on the stock status (in stock / out of stock) and quantity of individual WooCommerce products.
* [Random Quiz Generator for LifterLMS](https://wordpress.org/plugins/random-quiz-addon-for-lifterlms/) - pull a random set of questions from your quiz so users never get the same question twice when retaking or setting up a practice quiz.
* [WP and Divi Icons](https://wordpress.org/plugins/wp-and-divi-icons/) - adds over 660 custom outline SVG icons to your website. SVG icons are vector icons, so they are sharp and look good on any screen at any size.
* [WP Layouts](https://wordpress.org/plugins/wp-layouts/) - the best way to organize, import, and export your layouts, especially if you have multiple websites.
* [WP Squish](https://wordpress.org/plugins/wp-squish/) - reduce the amount of storage space consumed by your WordPress installation through the application of user-definable JPEG compression levels and image resolution limits to uploaded images.

To view WP Zone's premium WordPress plugins and themes, visit our [WordPress products catalog page](https://wpzone.co/product/).


== Installation ==

1. Click "Plugins" > "Add New" in the WordPress admin menu.
2. Search for "Custom CSS and JavaScript".
3. Click "Install Now".
4. Click "Activate Plugin".

== Frequently Asked Questions ==

== Screenshots ==

1. Custom CSS editor


== Changelog ==

= 2.0.16 - August 29, 2023 =
* Revert changes related to the admin page slug change

= 2.0.15 - August 23, 2023 =
* Introduce `wpz_custom_css_js` capability

= 2.0.14 - August 21, 2023 =
* Fix: error on save "security token expired"
* Compatibility with PHP 8
* Load minified files on admin page
* Change admin page title from h2 to h3
* Add addons tab
* Admin page rebrand

= 2.0.13 - August 17, 2023 =
* Add notice about new plugin, AI Image Lab

= 2.0.12 =
* Updated links, author, changed branding to AGS,
* Updated tested up to,
* Removed donation links,
* Added aspengrovestudios as contributor

= 2.0.11 =
* Fix: Issue with previous update if the admin JavaScript file was already in the browser cache

= 2.0.10 =
* Miscellaneous improvements
* Updated licensing (GPLv3+)

= 2.0.9 =
* Added search functionality to code editor
* Added bracket matching to code editor

= 2.0.8 =
* Fixed issue with backslash in CSS

= 2.0.5 =
* Improved HTTPS support

= 2.0 =
* Added revisions
* Added drafts/previewing
* Added minification

= 1.0.5 =
* Changed file storage location to prevent deletion on plugin update. IMPORTANT: BACK UP YOUR CUSTOM CSS AND JAVASCRIPT CODE BEFORE INSTALLING THIS UPDATE.

= 1.0 =
* Initial release

== Upgrade Notice ==
