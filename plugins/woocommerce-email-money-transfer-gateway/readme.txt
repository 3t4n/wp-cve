=== BLAZING Email Transfer Payment Gateway ===
Contributors: massoudshakeri
Tags: WooCommerce, Payment, Gateway, Email Money Transfer, Interac, e-Transfer, Payment, Extension
Requires at least: WooCommerce 2.2
Tested up to: 6.0.2
Stable tag: 2.6.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Many customers in Canada prefer to pay for the merchandise they buy, by e-Transfer (formerly Email Money Transfer).

= What does this plugin do? =
If customers choose the secret question and answer by themselves, they have to call to inform you what the Q&A are. Or they have the option to send them by Email. The other option is you give everybody a static question and answer.

In both cases, if a third party, or a hacker, gain access to the username & password of your Email, he/she can deposit the funds to any account he wants, and there is no way to cancel the transaction.

So, this plugin creates a random word which can be used as an answer to the quetion. In this case, whoever has the access to your Email box, is not going to figure out what the answer to the question is. Unless he/she hacks the backend of the website as well as your Email box.

= How To use this plugin: =
In the 'Email Transfer payment gateway' in the WooCommerce Settings, there is a field named 'Instructions'. Whatever you enter in that field will be shown to the user in the 'Thank You' page after placing an order, and also in the Email.

You must provide an Email for customers, so you can receive the instructions to retrieve the funds they send.

Also you should keep two placeholders which are {1} and {2}.

   {1} will be replaced by Order Number. Customers should be encouraged to mention their order number in the secret question they send

   {2} will be replaced by a randomly generated 6-character long word. Customers are encouraged to use that word as the answer to the secret question.

   An 'Order note' will be added to the order, containing this randow word, so upon retrieving the funds, you will know what the answer to the secret question of each order is.

An Instruction can be something like this:

After placing your order, please send an Email money transfer to the following:

Email: xxx@yyy.com

Secret Question: Your Order Number {1}

Secret Answer: {2} (MAKE SURE YOU DO NOT REMOVE THESE TWO {1} and {2})

Thanks for choosing us! We appreciate your business.

== Installation ==
1. Upload the 'woocommerce-email-money-transfer-gateway' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit 'WooCommerce -> Settings -> Payments -> Email Transfer, and modify the instructions.

== Screenshots ==
1. How the plugin is shown in the plugins' page.
2. Settings' page of this plugin in WooCommerce settings.
3. Where to find the Answer to the Secrert Question sent to the buyer, which is added as an 'Order Note' to the order.
4. Where to modify the instructions shown to the buyer.

== Frequently Asked Questions ==
= How Email Money Transfer works? =
An e-Transfer resembles an e-check in many respects. The money is not actually transferred by e-mail. Only the instructions to retrieve the funds are.

   - The sender opens an online banking session and chooses the recipient, the amount to send, as well as a security question and answer. The funds are debited instantly, usually for a surcharge.

   - An e-mail or text message is then sent to the recipient, with instructions on how to retrieve the funds and answer the question, via a secure website.

   - The recipient must answer the security question correctly. (If the recipient fails to answer the question correctly after three tries, then the funds will automatically be returned to sender.

   - If the recipient is subscribed to online banking at one of the participating institutions, the funds are deposited instantly at no extra charge.

= Why the Answer to the Secret Question is not shown to the buyer? =
You can modify the instructions shown to the buyer the way you would like. But make sure you leave two placeholders of {1} and {2}, so the plugin can replace them later with order number and the randomly generated answer.

= How do I know what question and answer is sent to the buyer? =
If the buyer mentions the order number in the question, you would know who is sending the fund. The answer sent to the buyer can be found in the order page, as the first order note.

== Changelog ==

= 2.6.0 =
Changed class name/function names, to make it unique

= 2.5.3 =
Added BLAZING to the plugin's name

= 2.5.2 =
Removed money from the plugin's name

= 2.5.1 =
Removed Inerac name from the plugin

= 2.5.0 =
Sanitized inputs & escaped outputs, and followed WordPress Coding Standards

= 2.4.0 =
Remove the use of any of WooCommerce trademarks from the beginning of the plugin's display name

= 2.3.3 =
replaced $order->user_id with $order->get_user_id().

= 2.3.2 =
Fixed the Secret Answer sent by Email, to the right one for the ordering user.

= 2.3.1 =
replaced $order->reduce_order_stock() with wc_reduce_stock_levels( $order_id );

= 2.3.0 =
Updated according to some changes in Woocommerce api; also added an option in settings (Woocoomerce settngs -> Payments-> Email Transfer) to select the status of the order, upon using this gateway.

= 2.2.0 =
Added translation files for French

= 2.1.1 =
Fiexed the “Enable for Shipping Method” select box which was showing an empty dropdown

= 2.1.0 =
Mistakenly Email instructions was sent for every status before this release. Now it is limited to Pending, Processing, and On-hold status.

= 2.0.1 =
Fixed a bug in showing user profile page

= 2.0.0 =
Before this release whenever a customer places an order, a new answer to the secret question was given. In this release, if a customer creates a user account, the secret answer is saved in his profile, and for all the future orders that answer is shown.
The answer to the secret question can be modified in the user profile page.
Also the priority of payment instructions is changed, so it is shown before order details.

= 1.0.4 =
Changed the status of the order to "On Hold"

= 1.0.3 =
Changed the status of the order from "Processing" to "Pending Payment", so the order is not included in Woocommerce report for sales, before payment.

= 1.0.2 =
Fixed the thankyou_page code to show instructions only if this payment method is selected.

= 1.0.1 =
Fixed a bug. Apparently, in presence of other plugins, this plugin was called before woocommerce was instantiated. So I just added a few lines to check if WC()->session exists.

= 1.0.0 =
* First Release.

== Upgrade Notice ==

= 1.0 =
* First Release.

= 1.0.1 =

= 1.0.2 =
In this version thankyou_page code shows instructions only if this payment method is selected.

= 1.0.3 =
The status of the order is changed from "Processing" to "Pending Payment", so the order is not included in Woocommerce report for sales, before payment.

= 1.0.4 =
Changed the status of the order to "On Hold"

= 2.0.0 =
In this release, if a customer creates a user account, the secret answer is saved in his profile, and for all the future orders that answer is shown. The answer to the secret question can be modified in the user profile page for future orders.

= 2.0.1 =
In this release, if a customer creates a user account, the secret answer is saved in his profile, and for all the future orders that answer is shown. The answer to the secret question can be modified in the user profile page for future orders.
Also a bug in showing user profile page is fixed.

= 2.1.0 =
Mistakenly Email instructions was sent for every status before this release. Now it is limited to Pending, Processing, and On-hold status.

= 2.2.0 =
Just added translation files for French

= 2.3.0 =
Updated according to some changes in Woocommerce api; also added an option in settings (Woocoomerce settngs -> Payments-> Email money Transfer) to select the status of the order, upon using this gateway.

= 2.3.1 =
replaced $order->reduce_order_stock() with wc_reduce_stock_levels( $order_id );

= 2.3.2 =
Fixed the Secret Answer sent by Email, to the right one for the ordering user.

= 2.3.3 =
Fixed the Secret Answer sent by Email, to the right one for the ordering user.

= 2.5.0 =
Sanitized inputs & escaped outputs, and followed WordPress Coding Standards

== Thanks ==

Translators who did a great job converting the text of the plugin to their native language. Thank you!

* [Mr. Gautier Dorval](https://www.pagup.com)