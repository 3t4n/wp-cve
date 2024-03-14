=== a Gateway for Pasargad Bank on WooCommerce ===
Contributors: AlaFalaki
Donate link: -
Tags: woocommerce, gateway, pasasrgad bank
Requires at least: 3.5
Tested up to: 4.8
Stable tag: 2.5.2
License: MIT
License URI: https://opensource.org/licenses/MIT

This is a payment gateway for adding Pasargad bank (an Iranian bank) gateway to your woocommerce site.

== Description ==

= فارسی =
افزونه درگاه پرداخت بانک پاسارگاد با توانایی تایید سفارش‌های موفق به صورت خودکار (که در فروشگاه‌هایی که محصولات مجازی ارائه می‌دهند باعث می‌شود که لینک دانلود بعد از پرداخت موفق نمایش داده شود.) این افزونه بر اساس فروشگاه ساز ووکامرس نوشته شده است. با استفاده از این افزونه می‌توانید هر نوع فروشگاه مبتنی بر ووکامرسی را به درگاه بانک پاسارگاد متصل نمود. جهت آگاهی از نحوه کار این پلاگین بخش نکته‌ها را مطالعه نمایید.

= English =
A payment gateway plugin that works with Pasargad bank (Iran) and automatically confirm the orders (so that for virtual products, the download link will apear immediately after successful payment.) This plugin works on WooCommerce ( WooCommerce is a plugin for WordPress that makes shopping websites ).With this plugin you can make any kind of shopping websites. Please read our "Sending Data To Other Servers Policy" section to understand exactly what your dealing with.

== Installation ==

= فارسی =
1. فایل دانلود شده را از حالت فشرده خارج کرده، سپس پوشه `a-gateway-for-pasargad-bank-on-woocommerce` را در مسیر '/wp-content/plugins' کپی نمایید.
2. از طریق بخش افزونه‌ها در وردپرس، افزونه را فعال نمایید.
3. در بخش «تسویه حساب» از تنظیمات ووکامرس، درگاه را فعال نموده و تنظیمات مورد نیاز را وارد نمایید.
4. درگاه به صورت خودکار به فروشگاه شما افزوده خواهد شد.
= English =
1. Uniz/Upload `a-gateway-for-pasargad-bank-on-woocommerce` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enter plugin's required fields and also activate it under Checkout tab in WooCommerce configuration page
4. The gateway will automatically added to your website's checkout page

== Frequently asked questions ==

= Do all users can make purchase whit this plugin? =

No, only register users can.

= Can i use both of IRT/IRR currency? =

Yes, after version 2.5 this feature was added.

== Screenshots ==

1. Plugin's configuration page.

== Changelog ==

= 2.5.2: =
* Add pasargad bank logo in checkout page.
* Add "Tracking Code" to message.

= 2.5.1: =
* Fix a bug for succesfull transactions.

= 2.5: =
* Fix "doesnt show setting page in woocommerce".
* Add IRR/IRT (currency) understanding for better compatibility.
* Fix privateKey typo.

= 2.2: =
* Set custom callback pages.

= 2.0: =
* Major updates in code structure.
* Fixes WoCommerce 2.4.4 API bug.
* It will display succesfull buy message.

== Upgrade notice ==

= 2.5: =
Fixes a bug in which plugin's setting page doesn't show up in WooCommerce 2.6 and also now Plugin understand the websites currency (don't force you to use IRT as currency.)

= 2.2: =
* Now you can choose a WordPress page to display successful/failed pays.

= 2.0: =
Huge performance update and also fixes a bug that the callback doesn't work on WoCommerce 2.4.4 and minor feature updates.

== Sending Data To Other Servers Policy ==

In this plugin we only send request to the Pasargad bank web servers. There are 3 method that we used, they are listed as follows: 

= https://pep.shaparak.ir/gateway.aspx =
This method sends payment request to the Pasargad bank, It uses 6 variables that taken from your website. "Invoice Number, Invoice Date, Amount" from each order and "Merchant Code, Terminal Code, Redirect Address" from your plugin's setting page.

= https://pep.shaparak.ir/CheckTransactionResult.aspx =
This method checks if the transaction was successful or not.  We send a variable name "Transaction Reference ID" that bank send to website after each payment.

= https://pep.shaparak.ir/VerifyPayment.aspx =
If the transaction was valid and successful, we must verify the payment, otherwise the money will return to customer's bank account after 24 hours. This method will use 6 variables exactly like "Send Payment Request" method (the first method)

*** Other than these 3 methods, we will not send your data to any other server. ***
