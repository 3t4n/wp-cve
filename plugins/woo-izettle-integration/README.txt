=== WooCommerce Zettle Integration ===
Contributors: BjornTech
Tags: woocommerce, izettle, i-zettle, zettle, integration, pos, cash-register, payment, card, cash
Requires at least: 4.9
Tested up to: 6.4
Requires PHP: 7.3
Stable tag: 7.9.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Keep WooCommerce and Zettle in sync.

== Description ==

The WooCommerce Zettle Integration plugin is highly configurable and easy to use. Download and test within seconds, just authorize the plugin to your Zettle account and start to synchronize.

This plugin lets you:

- Create Zettle products from WooCommerce products.
- Create WooCommerce products from Zettle products.
- Realtime update selected data on products in Zettle from changes in WooCommerce.
- Realtime update selected data on products in WooCommerce from changes in Zettle.
- Syncronize stock levels between WooCommerce and Zettle.
- Optionally create WooCommerce orders based on purchases made in Zettle.
- Optionally generate EAN-13 barcodes for use in Zettle.

The plugin is compatible with the "WooCommerce advanced quantity" plugin.

When first connected you will get a one-week free trial where you can test all functionality including the automatic sync. Additional trial time can be arranged if needed. More information [here.](https://bjorntech.com/product/woocommerce-zettle-integration-automatic-sync/?utm_source=wp-izettle&utm_medium=plugin&utm_campaign=product)

== Installation ==
Install the plugin from the WordPress store as you would do with a plugin.

You will find all settings under the tab Zettle that can be found at WooCommerce->Settings->Zettle.

**Connection**

Enter your e-mail adress in the the *User e-mail* field. The e-mail is used for sending the Access Token required when purchasing a subscription.

1. Press *Authorize* to connect the plugin with your Zettle account, popup windows must be allowed in the browser for this to work.
1. You will be asked to agree with our [privacy policy](https://www.bjorntech.com/privacy-policy?utm_source=wp-izettle&utm_medium=plugin&utm_campaign=product).
1. You are then redirected to Zettle, enter your account details and authorize the plugin (your account user-id and password remains safe with Zettle and cannot be reached by the plugin).
1. When the authorization is complete, close the window

**Products to Zettle**

Select how often you want to *Sync products to Zettle*.

The plugin can create a set of products in Zettle out of the products you have in WooCommerce. Products that you already have in Zettle will remain unchanged.

*When syncing products* the plugin can be configured to update stock levels on the products in Zettle created by the plugin.

The plugin can be configured to *Use the price* from the Zettle standard price field or the sales price field. If the sales price field is chosen and the field is blank on a product, the plugin will use the standard price on that product.

*Include products with status* selects if only products being published or if all products regardless status should be synced. This is useful for products sold in Zettle but not in the web shop.

In *Product categories to sync* you can select the product categories that will be synced to Zettle, leave blank if you want all products to be synced.

Some things to remember about product synchronization:

* When you use the selection *When changed in WooCommerce* it can take a minute or two before you see the change in Zettle.
* If you have large number of products, the sync will take a long time. Do not start it several times.
* You can always start a manual sync by pressing the *Start* button
* If you want to prevent a single product or variation from being synced to Zettle, use the "Do not sync to Zettle" checkbox. You find it at the Inventory tab of simple products or at each variation of variable products.

**Products from Zettle**

The recommended way of working is to administer your products in WooCommerce and let them sync automatically to Zettle.

It is possible to configure what fields you do want to be able to change in Zettle and get automatically updated in WooCommerce.

The plugin can also be configured to create new WooCommerce product based on new products created in Zettle

**Purchases from Zettle**

You can select how often you want to *Download purchases*.

*When downloading* purchases the plugin is creating a local copy of your purchases. You can view and perform actions on downloaded purchases in the Zettle tab, found in the main Wordpress menu.

The plugin can create an order or just change the stock level for products being sold when a purchase is arriving to WooCommerce. These functions can also be manually performed at the Zettle tab in the main menu.

**Barcodes**

The plugin is adding a *Barcode* field at the Inventory tab on each simple product. On variable products a *Barcode* field is added on each variation of the product.

You can enter information manually on each product or let the plugin populate the Zettle barcode field in a number of different ways:

* Generate an EAN-13: If the barcode-field in WooCommerce is empty an EAN-13 barcode will be generated and saved on both the Zettle and WooCommerce products.
* Use the barcode field in WooCommerce described above and copy the data in the field to Zettle.
* Use the SKU field on the product or variation and copy the data in the field to Zettle.
* Use the barcode field in Zettle and copy the data to the WooCommerce barcode field (useful if you want to handle the admin of barcodes in the Zettle app)
* Clean the barcode field (useful if you want the plugin to generate new barcodes automatically later.)

**Advanced**

CAUTION: Use the advanced settings carefully and only if you fully understand the implications.

*Enable logging* if you experience problems with the plugin and need to troubleshoot.

Contact hello@bjorntech.com for more information about the advanced settings.


== Frequently Asked Questions ==
Q: What can I sync if I do not purchase a subscription?
A: All syncing is open during the trial period.

Q: I believe that I have configured everything correct and done manual sync, still nothing happens. No products being synced to Zettle and no purchases downloaded.
A: Your system probably has CRON disabled. Go to the settings page, check the box \"CRON disabled on server\" and save. While you are there, also check the \"Enable logging\". Check the logs at \"WooCommerce->Status->Logs\" after a couple of minutes and you will see how the updates started. If not, send us the logfiles to hello@bjorntech.com and we will look into the problem.

Q: Some variable products are created without variations in Zettle, is this a bug?
A: Zettle has a limit of max 99 variations on a product. To handle this, we are removing all variations on a product with more than 99 variations.

Q: When I sync products from Woo to Zettle, it seems that all products are uploaded to the “root” on Zettle. Will the sync still work both ways if I move the products to different folders in the Zettle admin (with browser) after the Woo -> Zettle sync?
A: The sync will always work both ways regardless of how you organize the products into folders from the browser or app.

Q: If I rename products (shorten names) on the Zettle website, will this have any affect on syncing? It will not change the product names in Woo store, right? What name will be shown in the customers receipt (I would prefer the longer name from Woo)?
A: Do not rename products in Zettle. The name change will be overwritten next time the product is synced from WooCommerce. Instead use the field “Product name” at the Zettle tab on your products. Just enter the name you want on the product in Zettle in the field.

Q: Do you support syncing of WEBP product images?
A: Yes

Q: Is the plugin compatible with the Zettle Inventory API change set to occur on the 31st of May 2023 (see https://developer.zettle.com/docs/api/inventory-v3/inventory-api-migration-guide)?
A: Yes

Q: Is the plugin compatible with HPOS (High performance order storage)?
A: Yes

== Changelog ==
= 7.9.0 =
* Verified to work with WooCommerce 8.6
* New: Added category exclusion filter in Products to Zettle
* New: Added option to put SKU in front of product name
* New: Added option to filter out virtual products when syncing to Zettle
* New: Added option to set low stock status in Zettle
* Fix: Manual syncs ignored if you'd put Update products in WooCommerce as your import behaviour
* Dev: Updated logging logic
= 7.8.9 =
* Verified to work with WooCommerce 8.5
* New: Added option for adding Zettle purchase row comments as meta data to order items in WooCommerce when syncing purchases from Zettle
* Fix: Non-latin character handled incorrectly for Zettle purchases
* Dev: Added filter for changing unit names when syncing products to Zettle
* Dev: Better logs for image syncing
* Dev: Added metadata to Zettle gift card purchases for integrations with other plugins
= 7.8.8 =
* Fix: Error when accessing Products from Zettle settings if not authenticated
= 7.8.7 =
* Verified to work with Wordpress 6.4
* New: Added import filters when importing products from Zettle
* Fix: Category changes in WooCommerce sometimes not picked up when syncing product to Zettle
* Fix: WebP image exports to Zettle not working on some server setups
= 7.8.6 =
* Verified to work with WooCommerce 8.2
* New: Added support to export Yoast Pro barcodes
* New: Added a new category export filter that takes category hierarchies into account
* New: Added support for the Future status in the Product export filter
* New: Added option to use comments on simple amount purchases in Zettle to bind a Zettle purchase with an existing WooCommerce order
* New: Added option to set a special status on orders created from Zettle if one item or more in the purchase is out of stock
* New: Added option to set the webhook priority on the shutdown hook that handles Zettle updates
* Fix: Base currency not correctly selected sometimes when using the WPML/WCML currency solution
* Fix: Set it up so that multiple Zettle stock import options can't be enabled at the same time - for new users
* Fix: Categories sometimes not being found when syncing categories to Zettle from Woo
* Fix: Stock changes sometimes trigged twice when using Zettle purchases combined with Products from Zettle stock sync
* Fix: Error notices showing wrong timestamp
* Fix: WebP images sometimes not being synced when dynamic image resizer plugins or solutions are active on the site
* Dev: Updated default settings for new users
* Dev: Added more logging
= 7.8.5 =
* Verified to work with Wordpress 6.3 and WooCommerce 8.0
* New: Added option to delete variants in WooCommerce if deleted in Zettle - automatically enabled for new users
* New: Added a better option for handling unusual characters in Zettle JSON objects - automatically enabled for new users
* Dev: Added better descriptions in plugin
= 7.8.4 =
* Verified to work with WooCommerce 7.9
* WC High-Performance Order Storage compatibility declaration
* New: Added option to allow the conversion from simple products to variable products when syncing existing products from Zettle to WooCommerce
* New: Added option to sync products between WooCommerce and Zettle using a better type of UUID matching
* Fix: Syncing interrupted sometimes when using the Price Based on Country for WooCommerce plugin
* Fix: Incorrect currency during syncs used when using the CURCY plugin
* Dev: Added more filters
= 7.8.3 =
* Verified to work with WooCommerce 7.5 and Wordpress 6.2
* New: Added option to force the new order email to be sent to site admins upon the creation of a WooCommerce order from a Zettle Purchase
* Fix: Daily sync actions in the Action scheduler constantly being cancelled
* Fix: Tax sometimes incorrectly calculated for US clients
* Fix: Incorrect currency being used during syncs to Zettle when Aelia Currency Switcher is installed on the site
* Fix: Empty error messages being shown at times
* Dev: Added support for the new Zettle Inventory API
= 7.8.2 =
* New: Added option to not sync attributes at all from Zettle to WooCommerce
* Fix: Tooltips not showing on Zettle purchases page
* Fix: Some options under Zettle purchases still displayed when disabling Zettle purchases
= 7.8.1 =
* Verified to work with WooCommerce 7.4
* Added support for WEBP images
* Added option to only perform a daily sync at certain intervals
* Added option to not use external references to match products between WooCommerce and Zettle
* Added option to bulk remove Zettle metadata from products
* Added option to not show any notices from plugin
* Fix: Zettle integration injecting javascript that already is provided by WooCommerce
= 7.8.0 =
* Verified to work with WooCommerce 7.2
* Added option to not generate an order created email that is sent to the admin when Zettle orders are created in WooCommerce
* Added option to not reduce stock when Zettle orders are created in WooCommerce
* Added option to do a daily export to Zettle in addition to exports done in realtime
* Added option to sync Zettle stock values to a metadata value on the WooCommerce product instead syncing it to the product stock value
* Added option to sync custom units on Zettle product over to WooCommerce as a metadata field on the product
* Added support for the new FinanceV2 Zettle API
* Bug: Variants not deleted from Zettle when removed in WooCommerce
= 7.7.1 =
* Verified to work with Wordpress 6.1
* Fix: Duplicate categories in WooCommerce sometimes not handles properly when syncing to Zettle
* Fix: Logs behaving strangely if null values are entered
= 7.7.0 =
* Verified to work with WooCommerce 7.0
* Added advanced option to skip looking for webhook signatures from Zettle
* Fix: Plugin sometimes loosing connection to Zettle when site is inactive for a long time
* Fix: Drafts not syncing correctly to Zettle after last update
* Fix: Better error messages for some scenarios
= 7.6.9 =
* Fix: Fixed more typos
= 7.6.8 =
* Moved around options to make the plugin easier to understand and use
* Added better support for UUIDs created outside of the plugin
* Fix: Multiple typos in the plugin corrected 
* Fix: Error when accessing tax options for certain configurations
= 7.6.7 =
* Verified to work with WooCommerce 6.8
* Fix: Not able to handle attributes with only numerical values when exporting to Zettle
= 7.6.6 =
* Verified to work with WooCommerce 6.7
* Added Getting started guide to plugin
= 7.6.5 =
* Fix: Export button not working properly sometimes when using WPML/Polylang
* Fix: Plugin will always use sale price when available regardless of pricing option chosen
= 7.6.4 =
* Verified to work with Wordpress 6.0 and WooCommerce 6.5
* Added option to sort variants and attributes alphabetically when imported from Zettle
* Added option to map barcode imported from Zettle to a specific meta data field in WooCommerce
* Fix: getPid() causing warnings when disabled
* Fix: Attribute name empty when importing attribute from Zettle that has a space before or after the name
= 7.6.3 =
* Verified to work with WooCommerce 6.3
* Added option to trigger save_post when stock is updated from Zettle
* Added option to trigger low and no stock notification emails when stock is changed via Zettle
* Fix: Cron replacement option triggered queued actions ran before actual requests to service, causing severe lag
* Fix: Scheduled sales caused Mismatch errors
* Fix: Issue where new products created could sometimes have an existing reference to Zettle attached - causing duplicate behavior
= 7.6.2 =
* Verified to work with WooCommerce 6.2
* Added option to trigger save_post when products are created/updated in WooCommerce via Zettle
* Fix: Issue with stocklevel changes if the Zettle Purchase stock change option and Products from Zettle stocklevel option was turned on at the same time
= 7.6.1 =
* Verified to work with Wordpress 5.9 and WooCommerce 6.1
* Fix: Categories was duplicated in every sync if synced from Zettle.
* Fix: TAX was incorrectly stored on order when creating WooCommerce order from Zettle purchase, causing problems if syncing to accounting system.
= 7.6.0 =
* New: Added admin option to clear all Zettle data on products in WooCommerce
* New: Added function to set specific category to be used in Zettle on WooCommerce product.
= 7.5.0 =
* The plugin is verified to work with WooCommerce 5.9
* New: Added option for prioritizing Zettle price above Sale price
* Fix: Changed import type to Merge products as default
* Fix: Error thrown if SKU is too long
* Fix: Notices failing to show up
* Fix: Variations out of stock were synced even when configured not to
* Fix: Added timestamp to notices
* Fix: Failing to unschedule actions causing error
* Fix: thepostid variable not declared causing warnings
* Fix: Wrong number of parameters in the update of products from zettle caused products to be created although setting was set to only update.
= 7.4.2 =
* The plugin is verified to work with WooCommerce 5.7
* Fix: Tax was not handled correct for US Zettle installations if vat was not enabled in WooCommerce
* Fix: Variation images was not imported when importing products from Zettle
= 7.4.1 =
* Fix: Purchases did not update when using debug mode.
= 7.4.0 =
* The plugin now requires PHP 7.3
* The plugin is verified to work with WooCommerce 5.6
* New: Now using the inventory tracking started and stopped messages from Zettle
* Fix: The webhook error message was not removed after a temporary webhook error
* Fix: Metadata was cleaned from products when using delayed publication, resulting in duplicates in Zettle.
* Fix: Product variations with 0 in stock in Zettle was not set to manage stock when imported to WooCommerce
* Fix: Missing customer name on orders.
* Fix: VAT was not stored correctly on orders causing VAT to not show in statistics. Use the repair function to fix already created orders.
= 7.3.0 =
* Verified to work with Wordpress 5.8 and WooCommerce 5.5
* Fix: Stocklevel was not set to 0 if a product was created in an import from Zettle.
* Fix: If multiple Taxes was used on a purchase, taxes was not set correctly on created orders.
* Fix: If stocklevel was set both from "Products from Zettle" and from "Purchases" the stocklevel could be corrupted in some cases.
* New: Implemented the new Zettle tax-setting API. Currently only used for USA-customers.
* New: Added the possibility to not sync SKU to Zettle.
= 7.2.0 =
* Fix: In some cases stocklevel is not updating correctly when importing manually.
* Fix: Allow for non-numeric article id:s when syncing stocklevel change directly to the ERP-system Fortnox.
= 7.1.1 =
* Fix: Purchases from Zettle where not automatically downloaded if the configuration page was saved after v7.0.4, please check the configuration and save the page again.
= 7.1.0 =
* New: Added an error message when the backend service is unable to connect to the site.
* Fix: Products included in a grouped product did not sync as expected.
* Fix: In some cases metadata was not cleaned correctly when trashing a product.
* Fix: Adjusted error message lenght on CURL errors.
= 7.0.5 =
* Fix: Variable products did not update correct to Zettle
= 7.0.4 =
* Fix: In some cases stocklevel changes on product variants did causes faulty "Mismatch" error mesasge.
* Fix: In some cases updating a product caused a fatal error in the barcode update.
= 7.0.3 =
* Fix: Settings page for products to Zettle did not load due to an error.
= 7.0.2 =
* Fix: Stocklevel on variaitons was not handled correctly
= 7.0.1 =
* Fix: When importing new variable products from Zettle, the product variations where not saved in all cases.
* Fix: Importing product variation images from Zettle caused an error and the image was not imported.