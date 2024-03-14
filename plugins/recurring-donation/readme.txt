=== Recurring PayPal Donations ===
Contributors: wpecommerce 
Donate link: https://wp-ecommerce.net/
Tags: subscription, donate, donation, paypal, recurring, payment, donations, paypal donation, button, shortcode, sidebar, widget, monthly
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Accept PayPal subscription or recurring donation payment from your WordPress site easily.

== Description ==

The Recurring Donations plugin allows you to accept recurring donations via PayPal from your website. 

It has a user-friendly and simple shortcode that lets you place a donate button anywhere on your WordPress site. You can add the subscription donation widget to your sidebar, posts, pages etc.

The recurring donations widget this plugin creates is nice looking and user-friendly.

Your users will be able to select a subscription or recurring donation amount and pay you monthly.

This plugin allows you to accept one time donations also. So if some users prefer to pay a one-off donation, they can click on the "Donate Once" tab and make a one-time donation payment.

* Quick installation and setup.
* Easily take recurring donations via PayPal. Accept ongoing subscription donation on your site.
* The ultimate plugin to create PayPal recurring donations buttons.
* Create the donation buttons on the fly and embed them anywhere on your site using a shortcode.
* Ability to add multiple recurring donation widgets on your site for different causes. Accept subscription donation for various purposes.
* Allow your users to specify a donation amount that they wish to pay. 
* Ability to accept recurring payment in any PayPal supported currency.
* Send your users to a custom thank you page after the payment.
* Option to send your users to a custom cancel return page from PayPal.

The setup is very simple and easy. Once you have installed the plugin, all you need to do is enter your PayPal Email address in the plugin settings and your site will be ready to accept recurring donations from users.

= Shortcode =

Insert the following shortcode into a page or post to create a donation button.

`[dntplgn recurring_amt1="25" recurring_amt2="50" recurring_amt3="100" item_name="For the victims of XX"]`

Here, am1, am2 and am3 are the donation options.

= Widget =

In order to place a widget on the sidebar, go to "Appearance -> Widgets" and add a new text widget. Now add the following shortcode to the text widget.

`[dntplgn]`

After adding the widget to the sidebar, you can enter a title for the widget and some descriptive text that will appear above the button. You can also customize the shortcode parameters to override the default options.

For detailed instructions please check the [Recurring Donation Plugin](https://wp-ecommerce.net/wordpress-recurring-donation-plugin) documentation page.

== Installation ==

1. Upload plugin `donate plugin` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin using the 'Plugins' menu in your WordPress admin panel.
3. You can adjust the necessary settings using your WordPress admin panel in "Donate Plugin".
4. Create a page or a post, customize button settings and insert generated shortcode into the text.

== Frequently Asked Questions ==

= How can I add Donate Plugin form to my website? =

Use the following shortcode to add a recurring donation button to your website:

[dntplgn recurring_amt1="25" recurring_amt2="50" recurring_amt3="100" item_name="For the victims of XX"]

= Can I add more then one Donate Plagin form on the same page or post? =

Yes, you can add multiple Donate Plugin forms on your page or post or text widget.

= Can I create multiple recurring donation widgets using different paypal accounts? =

Yes, you can specify the paypal email address (where the donation should go to) in the shortcode. Example below:

[dntplgn recurring_amt1="10" recurring_amt2="20" recurring_amt3="50" item_name="For a good cause" paypal_email="paypalemail@example.com"]

== Screenshots ==

1. Donate Plugin Settings.
2. Monthly Recurring Donation Option.
3. Once Off Donation Option.

== Changelog ==

= 1.7 =
* Small fix for WordPress 5.5
* Updated the jQuery UI css file.

= 1.6 =
* Added a new option to specify a "Cancel" URL in the settings menu. PayPal will send the users to this page if they click on the cancel link on PayPal's checkout page.
* Added new translation strings.

= 1.5 =
* Added a new option in the settings to allow customization of the currency symbol.
* Added a new shortcode parameter that can be used to customize the currency symbol using the shortcode.
* The item name parameter value now gets transferred to PayPal for the one time custom donation also.

= 1.4 =
* More strings are now translatable.

= 1.3 =
* Added a new settings option that can be used to customize the payment currency. Receive recurring donation in any currency supported by PayPal.
* Added a new settings option that can be used to specify a return URL.
* The (p/m) label next to the amount value has been removed. This label can now be customized from the settings menu of the plugin.
* The currency code can also be customized in the shortcode by using the "currency_code" parameter.
* The return URL can be customized in the shortcode by using the "return_url" parameter.
* The payment currency code is displayed in the widget.
* Minor CSS improvement to add some padding between the donate button and the text above.

= 1.2 =
* Fix for when someone selects the other amount and then goes back to selecting a fixed amount and it doesn't read the amount correctly. Thanks to @jvo33 for providing the fix.
* Updated the admin menu icon to use a dashicon.

= 1.1 =
* The shortcode can now accept a paypal email address as a parameter. This will allow you to create multiple recurring donation widget with different paypal email address.
* The recurring amount will work correctly with the decimal places when the amounts are specified in the shortcode.
* Improved the usage instructions wording in the settings interface.

= 1.0.3 =
* Recurring Donation plugin is now compatible with WordPress 4.3

= 1.0.1 =
* First Commit

== Upgrade Notice ==
none

