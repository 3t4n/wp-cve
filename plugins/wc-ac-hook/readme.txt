=== WC-AC Hook ===
Contributors: mtreherne
Tags: WooCommerce, ActiveCampaign
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=matt@sendmail.me.uk&currency_code=GBP&item_name=Donation+for+WC-AC+Hook
Requires at least: 4.1.1
Tested up to: 6.2
Requires PHP: 5.3
Stable tag: 1.4.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Integrates WooCommerce with ActiveCampaign by adding or updating a contact on ActiveCampaign with specified tags, when an order is created at checkout.

== Description ==

Integrates WooCommerce with ActiveCampaign by adding or updating a contact on ActiveCampaign with **specified tags**, when an order is created on WooCommerce at checkout. You also have the option of a checkbox at checkout to mimic a subscription form and allow contacts to **signup to marketing**.

Using the plugin means that all of your shop customers will be automatically created as contacts on ActiveCampaign. They will have their first name, last name, email and phone number taken from their billing details on their order. You must specify (in the plugin settings) on which ActiveCampaign list contacts are added or updated.

You may **tag** all contacts created in this way with multiple tags e.g. you may want to track that the source is your WooCommerce shop and that an order has been created. It is also possible to add **tags based on each product item** on an order e.g. if you want to know exactly what items a customer has ordered or perhaps a type of item (by using the same tag for multiple products).

This enables you to use ActiveCampaign automations (or integration with other applications) based on shop orders and products.

You have the option in settings to **track order status**. This means that contacts are created as soon as an order is created at checkout, and the suffix (pending), (failed), (processing), (on-hold), (cancelled) or (completed) will be appended to the last tags listed. The tags will be updated as the status changes. Please read the FAQs for an example to help you understand this option.

If a customer already exists as a contact on ActiveCampaign their details will be updated (note that a new contact will have a status of active, but updates will retain the existing contact status for the ActiveCampaign list).

A WooCommerce system status log called `wc-ac-hook*.log` can be checked for errors.

== Installation ==

You must have WooCommerce installed and have an ActiveCampaign account to make use of this plugin.

1. For manual code install upload and extract the plugin to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Goto settings page for this plugin from the 'Plugins' menu or the 'WooCommerce > Settings > Integration' tab then:
4. Enter your ActiveCampaign URL (in the format accountname.api-us1.com) and your ActiveCampaign API key, which you will find under Settings > Developer > API Access when logged into your ActiveCampaign account;
5. Enter the ActiveCampaign list ID to which contacts are added or updated;
6. Enter the default tag(s) that are added to the contact.
7. If you wish to have tags associated with each product you must enter these on each products 'Advanced Data' fields.

Read the FAQs for more information regarding how to change the settings to track order status and to allow customers to signup to marketing at checkout (to mimic an ActiveCampaign subscription form).

If you deactivate the plugin all settings will be retained until you uninstall (delete). Note that the tags in advanced product data will be retained even if the plugin is deleted.

== Frequently Asked Questions ==

= How do I find the ActiveCampaign list ID? =

1. Login to your ActiveCampaign account
2. Click 'Lists' from the menu
3. Click on the number of contacts by the list title
4. Look in the URL you will see listid=#

= I'm having problems syncing contacts, can I view a debug log? =

If you are having problems syncing with ActiveCampaign then the first thing to check is for error messages in the debug log. To do this you must enable debug logging (if it has not been enabled by default).

1. Login as a WordPress admin or shop manager
2. Select the menu 'WooCommerce > Settings > Integration > WC-AC Hook'
3. Ensure that the 'Debug Log' option is checked to enable logging

You can view the debug log at 'WooCommerce > System status > Logs > wc-ac-hook-*'. Select the log and click on view. If you switch on debug logging then you will need to wait for you next shop order before you will see an entry in the log.

If you wish to clear the debug log then your site administrator can remove the file `/wp-content/uploads/wc-logs/wc-ac-hook*.log` or through the admin panel you now have the option to delete the log you are viewing at 'WooCommerce > System status > Logs'. WooCommerce will rotate logs daily and delete logs after 30 days by default.

= When will a contact be created or updated on ActiveCampaign? =

1. By default only when the order status is changed to 'Completed'.
2. Optionally you can change the settings so that it is done when an order is created at checkout with a status of 'Processing'.
3. Alternatively, if you have selected the track order status option, then it is done when an order is created at checkout whatever the status, and **whenever** the order status changes.

Note that if you create an order manually, using the administrator panel, then the contact will only be created or updated on ActiveCampaign **after** the status of the order is changed.

A contact will also be created or updated on ActiveCampaign at checkout if you have enabled the setting to allow 'Signup to Marketing'. If customer selects the checkbox then a form subscription will be triggered with associated actions.

= Can I track order status and abandoned carts? =

You now have the option in settings to track order status. This means that contacts are created as soon as an order is created at checkout, and the suffix (pending), (failed), (processing), (on-hold), (cancelled) or (completed) will be appended to the last tags listed. The tags will be updated as the status changes. To give you an example of how this works:

1. In settings you have the following two Default Tag(s) = WC Order, WC-Last-Order
2. A product has an ActiveCampaign Tag = Purchased-Widget
3. A customer enters an order and goes to PayPal, a contact would be created on ActiveCampaign with the tags 'WC Order', 'WC-Last-Order (pending)' and 'Purchased-Widget (pending)'.
4. As the order status changes the tags with status will be removed and new tags added e.g. 'WC-Last-Order (processing)' and 'Purchased-Widget (processing)'.
5. If the order is completed you will be left with the tags 'WC Order', 'WC-Last-Order (completed)' and 'Purchased-Widget'. Note that you do not get the (completed) suffix on the final product tag when the order is complete.

You could design ActiveCampaign automations to be triggered based on such tags being added. For example you may wish to be notified if any order has a status of (pending) for more than a certain period of time. Since this would indicate that a cart has been abandoned during payment; or you may want an automatic campaign to follow up with a customer if they cancelled an order.

= Can I mimic form subscriptions on ActiveCampaign e.g. for double opt in to marketing? =

The plugin now has the capability, through a checkbox at checkout (after order notes), to allow customers to opt in or out of email marketing. Allowing customers to subscribe through this method will trigger a form subscription on ActiveCampaign and trigger any associated actions e.g. subscribe to list, add tags and double opt in.

To set this up, use the 'Signup to Marketing', 'Checkbox Label' and 'Marketing Form ID' options under the menu 'WooCommerce > Settings > Integration > WC-AC Hook'.

When using this form subscription, you would typically use the form to subscribe the contact to a different list (to the ActiveCampaign List ID used for all shop contacts in the settings). This way you can have a separate list for marketing purposes with double opt in.

Note that if you want to change the position of the checkbox or are using tools such as WooCommerce Checkout Field Editor then you may still use this capability using the following steps:

1. Keep (or set) the 'Signup to Marketing' option 'No' to suppress the default checkbox
2. Enter the 'Marketing Form ID' (each form has a unique ID number on ActiveCampaign)
3. Create your own custom checkbox field on the checkout page with the name `wc_ac_marketing_checkbox`

= Can I synchronise other fields or e-commerce data with ActiveCampaign? =

ActiveCampaign have released version 3 of their API which supports 'Deep Data Integration' e.g. more order details such as order values and number of items purchased. ActiveCampaign have also launched their own integration with WooCommerce which is now available to account holders with 'Plus' plans and upwards. Given this development, there are no plans to add this capability to this plugin at this point in time.

= Can I have multiple product tags? =

Yes you can add multiple ActiveCampaign tags to a product separated by commas. Note that if you are using the option to track order status, the status will only be added to the last tag listed.

== Screenshots ==

1. Plugin settings page from 'Integration' tab on 'WooCommerce > Settings' menu
2. Advanced product data fields when editing 'Products'

== Changelog ==

= 1.4.2 =
* Fix to stop PHP notices
* Declare HPOS compatibility
* Meta data (wc_ac_marketing_checkbox) saved with HPOS

= 1.4.1 =
* Fix to error when used with other ActiveCampaign plugin(s)

= 1.4 =
* Fix name clashes with use of PHP namespace
* This version now has minimum requirement of PHP >= 5.3.0
* Fix to stop PHP notices

= 1.3.2 =
* Extended debug logging to show tags added
* Tweak to field help text
* Tweak to settings link from plugins list
* Fix to stop PHP notices

= 1.3.1 =
* Fix for elegant failure if activation attempted without WooCommerce
* ActiveCampaign API updated to v2.0.3

= 1.3 =
* Marketing checkbox at checkout to mimic subscription form
* Tweak to avoid adding tags back at completion if added at processing and then removed
* Fix to pass array to end function and stop E_STRICT warning
* ActiveCampaign API updated to v2.0.2

= 1.2.2 =
* Fix to support WordPress Multisite
* ActiveCampaign API updated to latest version

= 1.2.1 =
* Fix to replace `__DIR__` with `__FILE__` to ensure compatibility with PHP 5.2

= 1.2 =
* Added option to track order status using tags (see FAQs for example)

= 1.1 =
* Added option to add/update contact when order has status of processing
	
= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.3 =
Marketing checkbox at checkout to mimic subscription form

= 1.2 =
Option to track order status using tags

= 1.1 =
Option to add/update contact when order status is processing or completed