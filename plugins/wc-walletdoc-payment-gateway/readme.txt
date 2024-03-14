=== Walletdoc Payment Gateway for WooCommerce ===
Contributors: pankajwalletdoc,dwagnerwd
Tags: credit card, debit card, walletdoc, woocommerce, eft, bank2bank
Requires at least: 4.4

Tested up to: 6.3

Requires PHP: 5.6

Stable tag: 1.5.1

License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Attributions:  Walletdoc


Take card and EFT payments on your store using Walletdoc.



== Description ==



Accept card and Bank2Bank EFT payments on your store with the Walletdoc payment gateway for WooCommerce



= Features =

* Supports debit card, credit card and Bank2Bank EFT

* 3D Secure Version 2 support

* Visa and Mastercard tokenisation support

* Secure card storage

* Supports reservation of funds

* Payment Card Industry (PCI) compliant 



= Sign up with Walletdoc = 

Sign up in minutes for [Walletdoc business](https://www.walletdoc.com/business-signup) online or contact our sales team by logging a ticket at support@walletdoc.com



== Installation ==

1. Log in to your Wordpress dashboard

2. Select Plugins from the menu

3. Click Add New 

4. Type "Walletdoc" in the search field

5. Click "Install Now"



= Setup = 

1. Go to WooCommerce -> Settings -> Payments -> Walletdoc

2. Enable Walletdoc

3. Click Manage

4. For the API Credentials section paste your Production Secret Key from Walletdoc, then enable Test Mode and paste your Sandbox Secret Key. You can copy the secret keys from the [App & API Keys page](https://www.walletdoc.com/business-apps) on the Walletdoc portal.

5. Click "Save Changes"

6. Copy the webhook endpoint provided and set it in the [Walletdoc portal's Webhooks page] (https://www.walletdoc.com/business-webhooks)

7. Make sure to paste the url for both Sandbox and production webhooks.

8. Enable all webhook events for both sandbox and production webhooks and click save. 





== Frequently Asked Questions ==



= Do I require an SSL certificate on my store? =

Yes. An SSL certificate must be installed on your store in order to use the Walletdoc payment gateway.


= Can I reserve payments and charge the card later? =

Yes. If you disable "Capture" Walletdoc will reserve the funds and change the order status to "On Hold". You can then adjust the order value by removing or adding items to the order (as long as the final amount is less than or equal to the reserved amount). Once you are ready to charge the order simply change the order status to "Processing" or "Completed". Walletdoc will then capture the amount. Do note that reserved funds that are not captured within 7 days are automatically reversed by Walletdoc. 

= What is Bank2Bank?

Bank2Bank is an EFT payment method that allows customers to pay using all major South African Banks.

= How do saved cards work =

When the "Saved Card" setting is enabled, Walletdoc will provide your registered customers with an option to store their cards for future purchases. The card is tokenised with Walletdoc and stored securely in our card vault. If the card is a Visa or Mastercard, Walletdoc will tokenise the card directly with Visa and Mastercard respectively. The next time your customer checks out of your store he will be able to chose a card he previously stored for a quick checkout. 



= What are the benefits of Visa and Mastercard tokenisation = 

Walletdoc will attempt to tokenise all Visa and Mastercard cards stored on Walletdoc directly with Visa or Mastercard. Once a card is tokenised, Walletdoc no longer holds the original card number of the customer, but instead retains a secure token representing the card. The token can only be used for processing payments for your store. Besides the security benefit, if a card expires or is lost or stolen, the bank will update Walletdoc with the details of the replaced card. The token will continue to work which means your customer will not have to add his new card. This provides a higher rate of conversion for your store. 



= What are the benefits of 3D Secure Version 2 = 

With 3D Secure version 2 banks are able to analyse information on the users browser or mobile device. The banks can then do a risk assessment and if the risk is deemed low they can skip the requirement of customer authentication (such as OTP). This is called a frictionless transaction and provides a richer experience for your customer, provides you a higher rate of conversion and still gives your the same chargeback protection as a normal 3D Secure transaction.

 

== Screenshots ==



1. Walletdoc checkout page

2. Woocommerce Walletdoc plugin settings page
