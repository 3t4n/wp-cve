=== Manually Approved Reviews for WooCommerce ===
Contributors: teckel
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=99J6Y4WCHCUN4&lc=US&item_name=WooCommerce%20Reviews%20Manually%20Approved&item_number=WooCommerce%20Reviews%20Manually%20Approved%20Plugin&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: woocommerce, woo, commerce, product, review, reviews, approval, approved, pending, status, hold, force, manually, comment, comments, discussion
Stable tag: 1.3.0
Requires at least: 2.1.0
Tested up to: 6.2
WC requires at least: 2.0.0
WC tested up to: 4.5.2
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Force WooCommerce product reviews to be manually approved.


== Description ==

With this plugin, newly written WooCommerce product reviews will be in a pending (hold) status until manually approved.

Product reviews in WooCommerce are immediately available for everyone to see (this can't be changed without this plugin). WooCommerce ignores the WordPress "comment must be manually approved" discussion setting. With this plugin, new product reviews must be manually reviewed and approved before being visible on the front-end.


== Installation ==

= For an automatic installation through WordPress: =

1. Select **Add New** from the WordPress **Plugins** menu in the admin area.
2. Search for **Manually Approved Reviews for WooCommerce**.
3. Click **Install Now**, then **Activate Plugin**.

= For manual installation via FTP: =

1. Upload the **woo-reviews-manually-approved** folder to the **/wp-content/plugins/** directory.
2. Activate the plugin from the **Plugins** screen in your WordPress admin area.

= To upload the plugin through WordPress, instead of FTP: =

1. From the **Add New** plugins page in your WordPress admin area, select the **Upload Plugin** button.
2. Select the **woo-reviews-manually-approved.zip** file, click **Install Now** and **Activate Plugin**.


== Frequently Asked Questions ==

= Are there any settings for Manually Approved Reviews for WooCommerce? =

No. All you need to do is install and activate the plugin and new product reviews will require approval. To turn it off, simply deactivate the plugin.

= How does it work? =

The plugin creates a hook that is triggered whenever someone writes a product review. It then changes the review to a pending (hold) status which requires the review to be manually reviewed and approved (or deleted).

= Wait, WooCommerce doesn't do this? =

Nope. New product reviews are immediately available for anyone to read, which is why this plugin exists.

= How do I approve the review? =

In the WordPress admin area under Comments.


== Screenshots ==

1. Activate the Manually Approved Reviews for WooCommerce plugin.
2. New reviews will be in pending status until manually approved.


== Changelog ==

= v1.3.0 - 2/24/2023 =
* Verified working with WordPress v6.2

= v1.2 - 2/1/2021 =
* Updated contact email address, verified working with WordPress v5.6

= v1.1 - 3/23/2016 =
* Added check to verify WooCommerce was installed and activated

= v1.0 - 3/22/2016 =
* Initial release