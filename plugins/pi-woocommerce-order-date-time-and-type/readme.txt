=== Order date, Order pickup, Order date time, Pickup Location, delivery date  for WooCommerce ===
Contributors: rajeshsingh520
Tags: delivery date, local pickup, delivery time, pickup date, WooCommerce delivery date, order pickup, order delivery date and time, order pickup date and time,    WooCommerce delivery date time, WooCommerce pickup date time, pickup location
Requires at least: 4.8
Stable tag: 3.0.49
Tested up to: 6.4.2
Requires PHP: 7.2

Order delivery, Order pickup, Order date and time, Pickup location, Order Limit, Order delivery date & delivery time for WooCommerce

== Description ==

[Demo link](https://woo-restaurant.com/menu/) | [Buy Pro Version](https://www.piwebsolution.com/product/order-delivery-date-time-and-pickup-locations-for-woocommerce/) | [Pro Admin Demo](http://websitemaintenanceservice.in/dtt_demo/)


== Delivery Type ==
<ul>
<li>You can offer <strong>pickup or delivery or both</strong> this type</li>
<li>You can make any one of them as <strong>default delivery type</strong></li>
<li>This delivery type will change the WooCommerce shipping method, <strong>If you have WooCommerce Local pickup method enabled in the shipping zone</strong>*</li>
<li>You can <strong>hide or show the checkout form field</strong> as per the delivery types selected. E.g: you may hide buyer address field from the checkout form if he selects pickup as delivery type. *</li>
<li>You can change <strong>Date from required to not required</strong> vise versa based on the delivery type selected by the user.*
     <ul>
     <li>Pickup Date as required, </li>
     <li>Delivery Date as required, </li>
     <li>Date as required for pickup and delivery both,</li> 
     <li>Date as non required for delivery and pickup both.</li>
     </ul>
</li>
<li>You can change Time from required to non required vise versa based on the delivery type selected by the user.* 
     <ul>
     <li>Time as required for pickup, </li>
     <li>Time as required for delivery, </li>
     <li>Time as required for pickup and delivery both, </li>
     <li>Time as non required for delivery and pickup both.</li>
     <li>Time will always be non required field if Date is non required as time is dependent on the date</li>
     </ul>
</li>
<li>You can hide Delivery Date and delivery time field based on the delivery type selected.
    <ul>
    <li>Enable delivery date and delivery time for both</li>
    <li>Disable delivery date and delivery time for both</li>
    <li>Enable delivery date and delivery time for pickup</li>
    <li>Enable delivery date and delivery time for delivery</li>
    </ul>
</li>
<li>Change position of delivery Date and delivery time form on checkout page:
    <ul>
    <li>Before customer detail</li>
    <li>After customer detail</li>
    </ul>
</li>
<li>Add custom label to delivery and pickup selection button</li>
<li>Set text and background color of the delivery type buttons</li>
<li>There is no query string added in the checkout page *</li>
</ul>

== WooCommerce Delivery Date ==
<ul>
<li>Set product <strong>preparation days</strong></li>
<li>Making delivery date option available for <strong>Pickup only, Delivery only or for both pickup and Delivery</strong> *</li>
<li>Set <strong>pickup holiday dates</strong></li>
<li>Set <strong>delivery holiday dates</strong></li>
<li><strong>Overall order limit</strong> on specific date *</li>
<li><strong>Order limit for pickup</strong> on the specific date *</li>
<li><strong>Order limit for delivery</strong> on the specific date *</li>
<li>Set holidays based on the <strong>days of the week</strong></li>
<li>Day specific <strong>overall order limit</strong> *</li>
<li>Day specific <strong>pickup order limit</strong> *</li>
<li>Day specific <strong>delivery order limit</strong> *</li>
<li>Custom <strong>date format</strong> *</li>
<li>Make delivery field as <strong>required field / non-required field</strong> *</li>
<li>If certain delivery date has reached its order limit then that delivery date will not be available for selection *</li>

<li>Set Overall <strong>quantity limit</strong> on specific date *</li>
<li>Set <strong>quantity limit for pickup</strong> on the specific date *</li>
<li>Set <strong>quantity limit for delivery</strong> on the specific date *</li>
<li>Set Day specific <strong>overall quantity limit</strong> *</li>
<li>Set Day specific <strong>pickup quantity limit</strong> *</li>
<li>Set Day specific <strong>delivery quantity limit</strong> *</li>
</ul>

== Delivery Time ==
<ul>
<li>Show time as either continues or time slots</li>
<li>You can show continues or time slot based on the delivery type selected by the user
    <ul>
    <li>Show time slot for pickup and delivery type both</li>
    <li>Show continues time for pickup and delivery both</li>
    <li>Show continues time for pickup and time slot for delivery</li>
    <li>Show continues time for delivery and time slot for pickup</li>
    </ul>
</li>
<li><strong>Set time slot based order limit</strong> once limit is reached that time slot is no longer available for selection on that particular date *</li>
<li>You can have different order limit for pickup and delivery time slots *</li>
<li><strong>Set time slot based quantity limit</strong> once limit is reached that time slot is no longer available for selection on that particular date *</li>
<li>You can have different quantity limit for pickup and delivery time slots *</li>
<li>You can set different time slots as per the day of the week *</li>
<li>If you offer same day delivery then you can set preparation time in minutes</li>
<li>If the buyer select same day delivery date and the time slot is past that slot / time  will not be available for selection</li>
<li>If you are using continues time then you can set the gap between the two consecutive times</li>
<li>You can set time format from the options given in the plugin *</li>
</ul>

== Pickup Location ==
<ul>
<li>Create <strong>unlimited pickup locations</strong> *</li>
<li>Show pickup location as <strong>button or as drop-down</strong> *</li>
<li>You can show <strong>image and map link</strong> in each location when you are showing it as button to the user *</li>
<li>You can <strong>assign shipping zone</strong> to pickup location, so that user will only see the pickup locations that are near to there shipping zone *</li>
<li>Send new <strong>order email to respective store</strong> from which user will pickup his order *</li>
</ul>

== Custom message =
<ul>
<li>Add <strong>custom message</strong> on top of delivery type option or below the pickup location *</li>
<li>You can set background color and text color of this message *</li>
</ul>

== Disable plugin * ==
<ul>
<li>There is option to disable the plugin 
    <ul>
    <li>if there is any virtual product in the cart</li>
    <li>if there is more virtual product in the cart compared to other non virtual product  </li>
    <li>if all the product in the cart are virtual product </li>
    </ul>
</li>
<li>There is a direct filter to disable the plugin option as per your logic <strong>"pisol_disable_dtt_completely"</strong> (PRO)</li>
</ul>

== Date, Time, Location and Pickup Information Display ==
<ul>
<li>Delivery Date, Delivery Time, Location and Pickup type information is stored in the Order meta </li>
<li>Delivery type, Delivery date / Pickup Date, Delivery time / Pickup time and Pickup location selected by the user are shown on:
    <ul>
    <li>Order success page</li>
    <li>Order email</li>
    <li>Order details shown on the backend to the admin</li>
    <li>Invoice Pdf generated by using plugin <strong>"WooCommerce PDF Invoices & Packing Slips"</strong> *</li>
    </ul>
</li>
</ul>

== Same day / Next day order cutoff time * =
<ul>
<li><strong>Set a Same day cutoff time:</strong> Once the time goes past the cutoff time, user will not be able to place a delivery/pickup order for same day (today's delivery/today's pickup)</li> 
<li><strong>Set a Next day cutoff time:</strong> Once the time goes past the cutoff time, user will not be able to place a delivery/pickup order for Next day (tomorrow delivery/tomorrow pickup)</li> 
<li>You can specify a different cutoff time for delivery order and pickup order</li>
<li>You can specify a different cutoff time for same day order and next day order</li>
</ul>

== Control Payment gateway as per the delivery type selected by customer * ==
This option allows you remove the payment method as per the delivery option selected by the customer
<ul>
<li>Remove payment gateway for delivery order</li> 
<li>Remove payment gateway for pickup order</li> 
</ul>
E.g: if you have enabled PayPal, Cash on delivery in WooCommerce, 
so now you can remove Cash payment for all the Delivery order, so when a customer is opting for a delivery he will have to make payment through PayPal he cant opt for cash payment

where as if they are opting for Pickup order then they can wither make payment through PayPal or Cash 

== Special working dates * ==
<ul>
<li>This allow you to enable some future delivery date for order placement even when that date is far away in future</li>
<li>You can start accepting order for Christmas even long time before it comes near to your pre-order date setting</li>
<li>You can even set plugin to only allow order for this special delivery date only</li>
</ul>

<small>points marked with * are available in pro version only</small>

== Quantity limit for time slot ==

Set quantity limit on Days, Date, & Time Slot. Say you Create an quantity limit restriction for each time slot, that is total quantity of product that can be purchased in that time slot. This even count the quantity from the order already placed for that time slot.

E.g: say you set a quantity limit of 10 on time slot 9pm-10pm

and now if customer A placed an order with 5 unit of product A and 2 unit of product B so total quantity was 7 unit and he selected date 27th and time slot 9pm – 10pm

so now if another customer comes in an total quantity in his cart is 4 unit then if he select date 27th then time slot 9pm-10pm wont be available for selection as this will breach the limit of that slot

but if he reduces his quantity to say 3 unit then he will be given this time slot or if he select some other date than 27th then also he will be able to select this time slot

== WooCommerce App support by our Date and time plugin ==
<ul>
<li>You will be able to see the Delivery/Pickup date and time in the Order note section of the WooCommerce App</li>
</ul>

== Addon plugins for PRO ==
This are the addon plugins:
<ul>
<li>
    <strong>Preparation time master pro (Addon plugin for PRO only)</strong><br>
    <ul>
        <li>Different preparation time for delivery and pickup order</li>
        <li>Different preparation time for each of the product</li>
        <li>Calculate preparation time as Parallel preparation or Sum of preparation time of all products</li>
        <li>Set availability date/day for a product, so if user is buying those product he can only select those date for delivery/pickup for the order (this will over write the shop level date)</li>
        <li>Set product as only available at specific pickup location only </li>
    </ul>
    <a href="https://www.piwebsolution.com/product/product-level-date-time-preparation-time-pro-addon-plugin/">Read more</a>
</li>
<li>
    <strong>Delivery/Pickup email alert plugin (Addon plugin for pro and free)</strong><br>
    It automatically sends a reminder email to your customer regarding upcoming delivery or pickup for there order, you can configure in the plugin how much time before a reminder email should be sent.
    <a href="https://wordpress.org/plugins/delivery-pickup-reminder-email-woocommerce/">Click to read more</a>
</li>

<li>
    <strong>Advance report (Addon plugin for pro and free)</strong><br>
    This shows you advance report as per delivery type, time and date
    <a href="https://wordpress.org/plugins/order-calendar-for-woocommerce/">Click to read more</a>
</li>
<li>
    <strong>Delivery date time preference popup (Addon plugin for PRO only)</strong><br>
    This allows user to select there delivery preference before they reach checkout page, <a href="https://www.youtube.com/watch?v=bJw2k4FniOQ&t=68s">Watch video</a>
</li>
<li>
    <strong>Cutoff time for each day of the week (Addon plugin for PRO only)</strong><br>
    <ul>
        <li>Set different cutoff time for days of the week</li>
        <li>Set different cutoff time for pickup and delivery</li>
    </ul>
    <a href="https://www.piwebsolution.com/product/pro-cutoff-time-for-each-day-of-the-week-addon-plugin/">Read more</a>
</li>
<li>
    <strong>Dining at restaurant option (Addon plugin for PRO only)</strong><br>
    <ul>
        <li>Adds extra option of Dining at restaurant apart from the option of delivery and pickup</li>
        <li>Set different time slot for dining option</li>
        <li>Option to select dining locations (the same pickup locations are shown for dining as well)</li>
    </ul>
    <a href="https://www.piwebsolution.com/product/dining-option-pro/">Read more</a>
</li>
<li>
    <strong>Special Working date & Special Timing Pro (Addon for pro plugin)</strong><br>
    <ul>
        <li>You can set special working dates that are outside your preorder day range</li>
        <li>You can set special timing for this special date as well</li>
        <li>You can use this plugin to change time slot of some normal working date as well </li>
    </ul>
<a href="https://www.piwebsolution.com/product/special-working-date-pro/">Read more</a>
</li>
</ul>




== Frequently Asked Questions ==
= I don't want delivery date and delivery time option when the buyer want delivery =
You can do that in the pro version, it allows you to disable the date and time option when user opt for delivery

= I don't want to have date and time option =
You can disable delivery date and time option for both delivery and pickup, or you can disable for pickup or disable for delivery

= I only want to have a delivery option with, delivery date and time field =
You can enable the delivery date and time field only for delivery by setting *Enable delivery or pickup or both* option to "Delivery"

= I only want to have pickup option with the pickup date and time field =
You can do so using *Enable delivery or pickup or both* option

= I want to give the customer option of to select pickup or delivery =
You can do that using *Enable delivery or pickup or both* option

= I want to give the option of pickup or delivery but don't want to have the option of date and time =
You can do that using this option in the plugin *Enable delivery date and time *

= I Don't want pickup time/delivery time option =
You can disable the pickup & delivery time option in the Pro Version

= I want to only allow an order for X days in future, I don't want the buyer to place a delivery or pickup date of say 6 months away from now =
You can control how long away the delivery or pickup date can be allowed by using *Pre-order days * option, In the free version you can only select this to be 10 days max.
E.g: Say today is 10 March and you only want to allow the buyer to place order 10 days ahead in future then you add Pre-order days as 10, and because of this buyer can only select pickup or delivery date up to 20 March

= I need some days to prepare the order once they come in =
You can set a number of days you need to prepare an order in *Order preparation days*. E.g: if today is 10 March, and you need 2 days to prepare order then the buyer can only select dates from 13 March onwards for delivery or pickup

= I have order preparation time in minutes =
Then you have to set the order preparation days as 0, once you do so you will see a new option below it, *Order preparation minutes* in this you can specify the time you need to prepare in minutes. This will block the user from selecting the time that has passed away or the time that is not fit based on your order preparation time 

= I want to change the text shown in the Pick and delivery selection button on the checkout page =
You can change them from *Delivery label* and *Delivery label * option 

= I want the pickup to be selected as the default option when the buyer comes to the checkout page =
You can set a default delivery method using this option *Default Delivery Type *

= I want to change the time format of the Delivery / Pickup time selector =
You can do that using this option *Time Format*

= I want to increase change the time gap between the times in the time selector drop-down = 
You can do that in the pro version using this option *Time interval in minutes*

= I want to have the pickup/delivery time option, but don't want to make it as a required field (as some time user may want to leave it empty) =
You can do that in the Pro version using this *Make delivery/pickup time as required field in checkout*

= I want to show my address to the customer when they select pickup option =
You can do this go to "Pickup Tab"

= I have multiple shops and want to give the buyer the option to select the shop from where they want to do pickup =
You can set multiple pickup locations (only 2 in Free version and unlimited in pro version). This pickup location will be shown to the user on checkout field to select one location.

= I have different delivery/pickup timing for different days of the week =
In the pro version, you can set different delivery pickup start and end time for the different days of the weeks

= I don't do delivery/pickup on Saturday and Sundays (or weekend) =
You can disable the days of the week when you don't do delivery/pickup, you can have different days disabled for pickup and different for delivery. When a day is disabled, the buyer won't be able to select that date

= Can I insert holidays =
Yes you can insert holidays, once you insert certain date as a holiday that date will not be available to the user for selection

= I want to make time as a required field for pic-up but optional for delivery =
You can do so in the PRO version

= I want to make time as an optional field for pic-up and delivery both =
You can do so in the PRO version

= I want to make the Date field as an optional for the pickup =
Yes you can do that in pro version

= I want to make the Delivery Date field as an optional =
Yes you can do that in the pro version

= I want to make the date field optional for all the delivery type (pickup or delivery ) = 
Yes you can do that in the pro version

= I want to change the position of the Data and time option on the checkout page =
In the pro version, you can change the location of the date and time field on the checkout page. It offers 2 positions one before the customer detail and one is after the customer detail

= I want to hide the time field for pickup and show it for delivery =
You can do that in the pro version, you can hide time file for the delivery type or pickup type

= I want to add a message on top that delivery will be done approximately on the selected time =
You can do that in the PRO version, infect you can set a different message for the Delivery and pickup type and you can have that message show up at different position like, above the Delivery type selector option, or below the date and time 

= Specify shipping address =
Yes, it will remove Specify shipping option from the checkout form when user select pickup as an option

= I want to show time as Range, instead of exact time =
Yes, you can do that in the pro version, in the pro version you can set the time range. and you can set a different time ranges for each day of the weak and different time range for delivery and pickup order type

= It removes all shipping method when pickup type is selected =
It does that in the Pro version, you will have to create a "local pickup" shipping method in each shipping zones, then it will add that in the shipping method when pickup will be selected and show rest of method when delivery will be selected

= I will like to show the pickup location as a drop-down =
Yes, you can do so in the PRO version.

= I want the Date, time and location in PDF invoice =
At present our PRO version support "WooCommerce PDF Invoices & Packing Slips" invoice generator plugin, so if you are using it then Date, Time, Pickup Location detail will be added in the Invoice PDF, and Packaging slip PDF as well

= Date selector is opening buy Date is not selected =
Check your Setting > General setting and make sure a date format is set in it 

= I want to remove some of the form fields on the checkout, when the user is opting for Pickup, but those fields back when they want delivery =
Ye, you can do that in the pro version, it gives you the option to hide the form fields as per the delivery type selected by the customer on the checkout page

= You can change the first day of the week in the front end date selector calendar as per your need =
Say you want the Sunday to be the first day of the week in the calendar, then you can do that in the Pro version 

= I want to put order limit for days =
Yes you can do that in the pro version, you can set order limit for the day of the weeks, wherein you can set an Overall order limit plugin you can set a separate order limit for pickup and delivery 

= I want to put order limit only on the delivery and not pickup =
yes you can do that you can set an order limit only on the delivery and leave pickup unrestricted (or you can do that other way around as well)

= I have a day based order limit set but I want to increase the limit on some special occasion =
Yes you can do so, you can set a different order limit for some specific delivery date and that limit will overwrite the order limit set on the week day basis

= I want to send the pickup location map link to the buyer so they can find the location =
If you are showing the pickup location as a button then it will show the map link for each location on the front end, and it will also add the location map link in the email

= How shipping method are handled =
For proper working, you must enable WooCommerce "Local pickup" shipping method. then it will change the shipping method as per the user selection of the delivery type option of the plugin.

= I have too many pickup locations to show on checkout page =
In pro version you can assign shipping zone to pickup location, so the buyer will only see those pickup location that you have assigned for there shipping zones, E.g: if you have pickup location in all the states in your country so instead of showing all you will assign each pickup location a shipping zone (zones will be based on city) so the buyer will only see the pickup location near to there city

= I don't want the date, time or pickup option when the customer is buying only Virtual product =
In pro version their is option to remove the plugin options of date , time , location and delivery type when the customer is buying a virtual product. Plugin offers 3 different kind of option for this
* Remove plugin field if there is any virtual product in the cart
* Remove plugin field if there is more virtual product in the cart compared to other non virtual product
* Remove plugin field if all the product in the cart are virtual product

= I don't want user to place any order for same day delivery or pickup after 5PM =
Yes you can do that in the pro version, it allows you to set, Cutoff time for same day delivery for pickup as well as delivery. You can set different cutoff time for pickup and delivery.

= I don't want user to place order for tomorrow after 5pm today =
Yes that can be done in the pro version, you can set a cutoff time for next day delivery/pickup order, once that cutoff time is gone user will not be able to select an order for next date pickup or delivery. And you can set different cutoff time for pickup and delivery

= I don't want to offer cash payment to customer opting for Delivery option =
Yes you can do so in the Pro version it allows you to remove the payment gateways as per the user selection of Delivery or Pickup

= I want to send new order email to respective store from which user is going to pickup his order =
Yes it can be done in the pro version

= I want to add some special date for order placement that are far away from my pre-order day limit =
You can do that in the pro version it allows you to add special date, this special date can be far away in the future
E.g: you can start accepting order for Christmas from October even if your pre-order days are say 10 

= I only want to sell product on some specif date only =
you can do that using pro version, You will have to enable the option of force special date, then it allow you to set the date that user can select for delivery/pickup apart from those date user cant pick another date
E.g: Say you only want to allow pickup on 24th Oct 2020 then you can put that date and user will only have that one date to select from 

= I will like to offer a different time slot for some specific date in between the normal working days =
We have an addon that allow you to do that in the pro version 

= I want to set different preparation time for Delivery and Pickup order =
You can do that in the pro version 

= Can shop manager access the plugin settings =
Pro version gives you the option to allow shop manager to see and modify the plugin settings

= Can we see Delivery date, time and pickup location detail in WooCommerce App =
Yes you can view those detail in the WooCommerce app inside the Order note section of the order

= Is it HPOS compatible =
Yes the Free version and PRO version both are HPOS compatible

= Quantity limit for time slot =
Yes in the pro version you can set quantity limit for each time slot. 

= Quantity limit for day of the week =
Yes in the pro version you can set quantity limit for each day of the week.


== Upgrade Notice ==

= 3.0.47 =
* warning given when used with WooCommerce block so user can know that plugin don't work with WooCommerce block based checkout page

= 3.0.42 =
* Made compatible with PHP 8.2

= 3.0.34 =
* layout issue with full site editing fixed

= 3.0.20 =
* vulnerability fixed

= 3.0.19 = 
* Tested for WP 6.2 

= 3.0.17 =
* Tested for WC 7.5.0

= 2.9.46 =
* WooCommerce App support added in the Free version

= 2.9.19 =
Major update changes the core implementation  
