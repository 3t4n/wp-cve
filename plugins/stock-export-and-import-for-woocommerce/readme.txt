=== Stock Export and Import for WooCommerce ===
Contributors: aspengrovestudios, annaqq
Tags: woocommerce, stock, inventory, report, reporting, export, import, csv, excel, spreadsheet
Requires at least: 4.0
Tested up to: 6.3.1
Stable tag: 1.0.6
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

Export and import stock statuses and quantities for WooCommerce products in Comma-Separated Values (CSV) format.

== Description ==

The Stock Export and Import plugin generates reports on the stock status (in stock / out of stock) and quantity of individual WooCommerce products. Reports can be downloaded in CSV (Comma-Separated Values) format, compatible with popular spreadsheet software. It also allows you to import CSV files to batch update your product stock data. This enables you to download a spreadsheet of your current inventory data, update it as required, and upload it back into WooCommerce.


If you like this plugin, please consider leaving a comment or review.

## You may also like these plugins
[WP Zone](https://wpzone.co/) has built a bunch of plugins, add-ons, and themes. Check out other favorites here on the repository and don’t forget to leave a 5-star review to help others in the community decide.

* [Product Sales Report for WooCommerce](https://wordpress.org/plugins/product-sales-report-for-woocommerce/) - setup a custom sales report for the products in your WooCommerce store with toggle sorting options. Including or excluding items based on date range, sale status, product category and id, define display order, choose what fields to include, and generate your report with a click.
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
* [Random Quiz Generator for LifterLMS](https://wordpress.org/plugins/random-quiz-addon-for-lifterlms/) - pull a random set of questions from your quiz so users never get the same question twice when retaking or setting up a practice quiz.
* [WP and Divi Icons](https://wordpress.org/plugins/wp-and-divi-icons/) - adds over 660 custom outline SVG icons to your website. SVG icons are vector icons, so they are sharp and look good on any screen at any size.
* [WP Layouts](https://wordpress.org/plugins/wp-layouts/) - the best way to organize, import, and export your layouts, especially if you have multiple websites.
* [WP Squish](https://wordpress.org/plugins/wp-squish/) - reduce the amount of storage space consumed by your WordPress installation through the application of user-definable JPEG compression levels and image resolution limits to uploaded images.

To view WP Zone's premium WordPress plugins and themes, visit our [WordPress products catalog page](https://wpzone.co/product/)

== Installation ==

1. Click "Plugins" > "Add New" in the WordPress admin menu.
2. Search for "WooCommerce Stock Export and Import".
3. Click "Install Now".
4. Click "Activate Plugin".

Alternatively, you can manually upload the plugin to your wp-content/plugins directory.

== Frequently Asked Questions ==

== Screenshots ==

1. Admin user interface
2. Sample export output

== Changelog ==

= 1.0.6 =
* Fix: PHP warning may appear in output in some cases

= 1.0.5 =
* Rebrand

= 1.0.4 =
* Change capability required for importing or exporting stock data to manage_woocommerce, previously view_woocommerce_reports
* Implement no-store / no-cache headers on export

= 1.0.2 =
* Improved memory usage for large exports

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.0.4 =
Please note: the user capability required for importing or exporting stock data with this plugin has changed to manage_woocommerce (previously view_woocommerce_reports).