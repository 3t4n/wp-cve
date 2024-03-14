=== Drag and Drop Multiple File Upload for WooCommerce ===
Contributors: glenwpcoder
Tags: drag and drop, woocommerce, ajax uploader, multiple file, upload, woocommerce uploader
Requires at least: 3.0.1
Tested up to: 6.4
Stable tag: 1.1.2
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

**Drag and Drop Multiple File Uploader** is a simple, straightforward WordPress plugin extension for WooCommerce, which allows the user to upload multiple files using the **drag-and-drop** feature or the common browse-file of your product page.

Plugin requires at least v3.5.0 of WooCommerce.

Here's a little [DEMO](https://woo-commerce.codedropz.com/product/cap/).

### Features

* File Type Validation
* File Size Validation
* Ajax Uploader
* Limit number of files Upload.
* Limit files size for each field
* Can specify custom file types or extension
* Manage Text and Error message in admin settings
* Drag & Drop or Browse File - Multiple Upload
* Display Uploader in WooCommerce - Single Product Page
* Option to display in "Add to Cart Form", "Variations Form", "Add To Cart Button", "Single Variation".
* Able to delete uploaded file before adding to cart
* Support multiple languages
* Mobile Responsive
* Compatible with any browser

### ⭐ Premium Features

* **New** - File Remote Storage *(Google Drive, Dropbox, Amazon S3, FTP)*
* Image Preview (For Images)
* Parallel / Sequential Upload
* Change Filename Pattern *(Filename, Username, User ID, IP Address, Random etc)*
* Change Base Upload Directory
* Change Upload Folder by *(Order No, Random, Date, Time, Name, Customer ID )*
* Add Custom Fees *( Conditional )*
* Approve / Reject Files
* Chunks Upload *( Break large files into smaller Chunks )*
  - Capable of uploading large files.
* Set Max Total Size
* ZIP Files
* Ajax Uploader
* Unlimited Uploads
* Show uploader based on *(Categories, Products, Tags, Attributes)*
* Show uploader on **"Checkout"** and **"Product"** page.
* Optimized Code & Performance
* Improved Security
* Unlimited Sites
* One Time Payment
* 1 Month Premium Support

Pro version [DEMO](https://www.codedropz.com/woo-commerce-pro/shop/).

You can get [PRO Version here!](https://www.codedropz.com/woocommerce-drag-drop-multiple-file-upload/)

Compatible with **"WPML"** and **"Polylang"** multilingual plugin.

### Other Plugin You May Like

* [Drag & Drop Multiple File Upload - WPForms](https://www.codedropz.com/drag-drop-file-uploader-wpforms/)
An extension for **WPForms**
* [Drag & Drop Multiple File Upload - Contact Form 7](https://wordpress.org/plugins/drag-and-drop-multiple-file-upload-contact-form-7/)
An extension for **Contact Form 7**

== Frequently Asked Questions ==

= How can I send feedback or get help with a bug? =

For any bug reports go to <a href="https://wordpress.org/support/plugin/drag-and-drop-multiple-file-upload-for-woocommerce">Support</a> page.

= How can I change File Upload Name? =

Go to "WooCommerce > Settings > File Uploads" in "Upload Restriction - Options" section there's a field "Name" where you can add/change of the uploader name.

= How can I change "File Upload" Label =

Go to "WooCommerce > Settings > File Uploads" in "Uploader Info" there's a field "File Upload Label" where you can change/add a custom label.

= How can I limit Max File Size? =

To limit file size, go to "WooCommerce > Settings > File Uploads" scroll down and find "Upload Restriction" section.

On that section there's a Text field name "Max File Size (Bytes)" that you specify File Size limit of each file. (if this field empty, default: 10MB)

Please also take note it should be `Bytes` you may use any converter just Google (MB to Bytes converter).

= How can I set "Max" Number of Files in my Upload? =

To limit the Num of files go to "WooCommerce > Settings > File Uploads" find the "Upload Restriction" section and then add number in "Max File Upload" field. (default : 10)

= How can I set a "Minimum" File Upload? =

To set Minimum Num of files go to "WooCommerce > Settings > File Uploads" find the "Upload Restriction" section and then add number in "Min File Upload" field.

= How can I Add or Limit File Types? =

To add file types restriction, in "WooCommerce > Settings > File Uploads" scroll down and find the "Upload Restriction" section.

In 'Supported File Types' field, add File types/extensions you want to accept, this should be separated by (,) comma.

Example: jpg, png, jpeg, gif

= How can I change text in my Uploader? =

You can change text `Drag & Drop Files Here or Browse Files` text in Wordpress Admin, it's under "WooCommerce > Settings > File Uploads".

= How to Disable Uploader in Specific Product? =

Go to "Products" then "Edit" specific products.

In "Product Data" box/widget click "File Uploads" tab then there's an option that allow you disable the uploader.

= How to change label for individual product? =

Go to "Products" then "Edit" specific products.

In "Product Data" box/widget click "File Uploads" tab then there's a field name "Label" where you can add custom label for individual product.

= How can I change Error Messages? =

All error message can be managed here "WooCommerce > Settings > File Uploads" 'Error Message' section.

== Installation ==

To install this plugin see below:

1. Upload the plugin files to the `/wp-content/plugins/drag-and-drop-multiple-file-upload-for-woocommerce.zip` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Configure plugin in "WooCommerce > Settings > File Uploads".

== Screenshots ==

1. Product Single Page - Front-end
2. Upload in Progress - Front-end
3. Shopping Cart - Front-end
4. Order Details - Front-end
5. Order Details - Admin
6. File Upload (Product Settings) - Admin
7. Uploader Settings - Admin
8. Upload Display - Front-end

== Changelog ==

= 1.1.2 = 
* Check WooCommerce 8.2.1 compatibility
* Declared compatibility for HPOS

= 1.1.1 = 
* Security - Addressed and resolved security vulnerabilities that were reported (Thanks to "Marc Montpas")

= 1.1.0 = 
* Bug Fix - Overwrite the existing file if a file with the same name already exists

= 1.0.10 =
* Fixes - Bug fixes
* Fixes - Added alternative solution for cache nonce
* Checking Wordpress 6.2 compatibility & WooCommerce 7.5.1

= 1.0.9 =
* Fixes - Security Fixes
* Added - Security nonce for upload and delete (Ajax Request)

= 1.0.8 =
* Bug - Css fixes font Conflict
* Check - Test with latest version of Wordpress 6.1.1 and WooCommerce 7.3.0

= 1.0.7 =
* New - French Translation Updated (Thanks to @dleroux61 / Dominique Le Roux)
* Check - Tested with latest version of Wordpress 5.9.3 & WooCommerce 6.4.1

= 1.0.6 =
* Fixes - Disable File Upload not working.
* Tested - In Wordpress 5.8.2 & Latest WooCommerce version

= 1.0.5 =
* Fixes - Custom text/message issue.

= 1.0.4 =
* Add accept attributes to display specific file types when browsing files - https://wordpress.org/support/topic/restrict-upload-in-browse-files/
  - use 'dndmfu_wc_all_types' filter (bolean) to show all types.
* Translate “deleting”, “of” & “remove” text.
* Added compatibility plugin for polylang & wpml multilingual.

= 1.0.3 =
* Bug - Fixes
* Fixed - Conflict with "Drag & Drop Multiple Upload For CF7"
* Fixed - Option error message not showing
* Note - You need to go to "WooCommerce -> Settings -> File Uploads" and re-save options.

= 1.0.2 =
* Bug - Fixes
* Fixed - Minimum file validation error message not showing.

= 1.0.1 =
* Bug - Fixes
* New - Added new option to disable file upload (globally).
* New - Added option in "Product Data" to enable/disable file upload of individual product.

= 1.0 =
* Initial Release

== Upgrade Notice ==

== Donations ==

Would you like to support the advancement of this plugin? [Donate](http://codedropz.com/donation)