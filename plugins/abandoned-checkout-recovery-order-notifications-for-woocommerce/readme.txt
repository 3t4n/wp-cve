===  Abandoned Checkout Recovery & Order Notifications for WooCommerce ===
Tags: whatsapp, abandoned cart, whatsapp api, whatsapp business, whatsapp chat
Stable tag: 1.0.1
Requires at least: 6.0.1
Tested up to: 6.1
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send WhatsApp notifications for recovering abandoned carts, double confirming CoD orders and for other order & shipment updates! Also, send out your Woocommerce catalog via bulk WhatsApp campaigns and create chat automations to engage & convert more customers.
== Description ==

Interakt is a platform that helps businesses leverage WhatsApp APIs to increase their WhatsApp sales & improve Customer Support. Interakt is a part of Jio Haptik, which is a leader in Conversational Commerce AI solutions globally.

Interakt's plugin for Woocommerce helps merchants in the following ways:

= Automate WhatsApp notifications for: =

* Recovering checkouts that were abandoned, after the customer entered their phone number in the Order Checkout Form
* Double confirming CoD orders (the WhatsApp notification will contain a CTA button which will take the customer to the order details page to confirm the order. Once confirmed, the order's CoD Confirmation Status will be marked as 'Confirmed' in the Woocommerce Orders Section)
* Confirming prepaid orders
* Confirming order shipments
* Confirming order delivery
* Confirming order cancellations

= Use order-related events sent by the plugin, to send Bulk WhatsApp Campaigns to 1000s of customers (for announcing offers, new product lines etc) =

= Additionally, via the Interakt platform, merchants can also: =

* Publish a WhatsApp Widget in their Wordpress website
* Create a WhatsApp Catalog for their Woocommerce products & send the catalog to 1000s of customers
* Configure WhatsApp Auto reply flows to answer customer queries & help customers browse through their WhatsApp Catalog
* Manager customer chats via a Shared Team Inbox

Check our [documentation](https://interakt.shop/resource-center/how-to-integrate-whatsapp-business-api-with-woocommerce-via-interakts-plugin/) to see detailed steps for how to use this plugin.

== Installation ==

== Frequently Asked Questions ==

= What are the steps to set up a new WhatsApp API account? =
Steps:
* Install the plugin.
* Go to WooCommerce-> Interakt-> Click on ‘Connect to Interakt’
* In the page that opens, click on ‘Sign Up’
* Fill the form & click on ‘Register with Interakt’ to create an account on Interakt. In the form, please enter your personal WhatsApp number where we can reach out to you for any updates - do not enter the number that you wish to use for your WhatsApp API account.
* You will be logged into your Interakt account in ‘Sandbox mode’ i.e. a temporary WhatsApp number will be assigned from our end to your Interakt account, so that you can test different features of Interakt.
* Click on ‘Connect your own number’ or go to [https://app.interakt.ai/signup/whatsapp](https://app.interakt.ai/signup/whatsapp).
* Read the ‘Important Information’ points and then click on ‘Continue with Facebook’.
* In the popup, you will first be asked to log into / create your Facebook Business account.
* Next, you will be asked to create / select a WhatsApp Business Account within your FB Business account.
* You will have to provide a display name for your WhatsApp API phone number.
* Next, you will need to enter the phone number that you wish to associate with your WhatsApp API account.
* An OTP will be sent to the number and you’ll have to enter the same OTP.
* After OTP verification is done, it will take 7-10 minutes to set up your Interakt account with your new WhatsApp API number.

= Can I use my existing WhatsApp number as my WhatsApp API number? =
We generally recommend using a new number for your WhatsApp Business API account. However if you really want to use your current WhatsApp number, please read below before going ahead:
* You will have to delete the WhatsApp account on that number.
* You can't use the number on WhatsApp Personal / Business apps while you use the number as a WhatsApp API number.
* Due to WhatsApp’s rules, going back to WhatsApp apps from Interakt is a time taking process & not guaranteed.
* Old chats on your number’s WhatsApp account will get deleted and won’t show up on Interakt.

= What are the advantages of having a WhatsApp API account? =

With a WhatsApp API account, you can fulfill many use cases which are not possible via the normal WhatsApp app today:

* Send bulk WhatsApp campaigns for discounts / offers to your customers. (customers will get the messages even if they haven’t saved your number in their contacts list)
* Send automated notifications for abandoned checkouts, order updates etc
* Assign chats between team members
* Tag customers, add fields for customers
* Send auto-replies and build automated chat flows on WhatsApp
* Send WhatsApp Catalogs to 1000s of customers via campaigns
* Build credibility via WhatsApp Green Tick

= What are some things which you can do on normal WhatsApp but not on WhatsApp API? =
The following things are not possible on WhatsApp API as of today:
* Messaging a customer 24 hours after the customer’s last reply without using an Approved Template
* Being part of WhatsApp groups
* Putting up WhatsApp statuses

= What are the messaging restrictions applicable on WhatsApp API numbers? =
After connecting a number to your WhatsApp API account:
* Unlimited responses to customer initiated conversations
* Business Initiated Conversations with 250 unique customers per rolling 24 hrs period. That is, you can send WhatsApp Campaigns to roughly 250 customers per day.

After connecting a number to your WhatsApp API account & also verifying your Facebook Business account:
* Unlimited responses to customer initiated conversations
* Business Initiated Conversations with 1000 unique customers per rolling 24 hrs period. That is, you can send WhatsApp Campaigns to roughly 1000 customers per day.
* Limit of 1000 can be increased to 10000 / 100000 per rolling 24 hrs period on [meeting specific criteria](https://developers.facebook.com/docs/whatsapp/messaging-limits)

For example:
Suppose, after connecting your number (and before FB Business Verification) you send a campaign to 200 customers at 2 pm on a particular day. Also suppose that those 200 customers hadn't messaged you on WhatsApp in the previous 24 hours. --> this will be considered as Business Initiated Conversations with 200 unique customers.
Till 2 pm on the next day, you can send a campaign to max 50 more customers. (You can still reply to the 200 customers to whom the campaign was initially sent).
At 2 pm on the next day, your limit will again be re-instated and you can again send a campaign to 250 customers.

= After clicking on ‘Connect to Interakt’, the plugin is still not getting connected. What should I do? =
Steps:
* After clicking on ‘Connect to Interakt’, make sure you sign up / log in into your Interakt account.
* After doing so, you should see 2 alerts on the top alert:
    1st alert – Your WooCommerce store is being connected to your Interakt account.
    2nd alert – Your WooCommerce store is now connected to your Interakt account…
* If you see both alerts, then come back to the plugin page in your Woocommerce admin and refresh the page, to see the plugin as Connected.
* If you don’t see both alerts, It could be because of your firewall. Disable your firewall & try connecting again (you can enable it again after that), or else, try whitelisting Interakt’s IP with your hosting provider (to get Interakt’s IP, please email us at support@interakt.ai):

= How to disable firewall for connecting the plugin ? =
* Login to your WHM (hosting server) as a root
* Search for Firewall Configuration setting. It can be under the Security settings
* Click on Firewall Disable
* Once it got disabled you can get a warning as “Firewall Status: Disabled and Stopped”. To enable the same you can click Enable under firewall settings.

= How to do IP whitelisting for some common hosting providers? =

Hostgator - [https://www.hostgator.com/help/article/hg-firewall-plugin](https://www.hostgator.com/help/article/hg-firewall-plugin)
Hostinger - [https://support.hostinger.com/en/articles/1583474-how-to-allow-or-block-a-specific-ip-address-for-your-website#how-to-manage-accesses-with-an-ip-manager](https://support.hostinger.com/en/articles/1583474-how-to-allow-or-block-a-specific-ip-address-for-your-website#how-to-manage-accesses-with-an-ip-manager)
GoDaddy - [https://in.godaddy.com/help/allow-or-block-website-access-27422](https://in.godaddy.com/help/allow-or-block-website-access-27422)
Bluehost - [https://www.bluehost.in/hosting/help/308](https://www.bluehost.in/hosting/help/308)

= Why are WhatsApp notifications for Abandoned Checkouts / Order Confirmations not going from my Interakt account? =
Please check:

* If you have followed all steps given here to set the integration live from the plugin page in your Woocommerce Admin.
* If yes, check whether you have followed all steps given here to set live campaigns for Abandoned Checkout, Order Confirmations etc from Interakt.
* If your notifications are still not getting sent, please go to [https://app.interakt.ai/notification](https://app.interakt.ai/notification), click on the particular campaign and in the page that opens, check whether the ‘Attempted’ count has increased. If it has, it means that Interakt did try to send the notification, but for some reason, it failed to get sent. Click on ‘View Details’ in the ‘Failed’ box to check the reason for failure. One common reason for failure could that - If the image of the product (for which the notification is being attempted) is stored in your WordPress admin in WebP format, the notification will fail since WhatsApp only allows sending JPeG or PnG format images in templates.Hence, you can try changing the images in Wordpress/Woocommerce to JPeg / Png format. Or, you could create a new template in Interakt, which doesn’t contain the image. Then pause the existing campaign, duplicate it and change the template in the campaign to the new one.
* If you find that notifications are not even getting attempted, then you need to check if the plugin is sending ‘events’ into your Interakt account. To test this,
* Place a CoD order in your website.
* Then go to [https://app.interakt.ai/contacts/list](https://app.interakt.ai/contacts/list)
* Click on ‘Add Filter’ & search for ‘order cod’
* Click on ‘Created On’ → ‘has any value’ → Done.
* Check if the name which you used in the CoD order appears in the list or not.
* If it appears, it implies that events are being sent by the plugin. If not, then get in touch with our support team at support@interakt.ai.

= What is the exact logic for sending out Abandoned Checkout notifications? =
The plugin sends out ‘Abandoned checkout’ events whenever a customer abandons a checkout after having given their phone number in the Order Checkout Form.

Now, after providing the phone number, there could be the following cases:
* The customer abandoned the checkout before starting payment - in this case, the order typically doesn’t appear in the orders panel in your Woocommerce admin. However, the Interakt plugin will detect this order & send an ‘Abandoned checkout’ event into your Interakt account.
* The customer abandoned the checkout after starting payment  - in this case, the order appears in the orders panel in your Woocommerce admin. The default order status is ‘Pending Payment’. In Step 2 of the ‘integration steps’ given here, if you have mapped ‘Pending Payment’ against Abandoned Checkout, in that case, the plugin will send the ‘Abandoned checkout’ event into your Interakt account.

Please note that the plugin waits for 15 minutes before sending the event into your Interakt account in both the above cases. After 15 minutes it checks whether the order was placed or not - if not, then it sends the event.

Hence, to sum up, the logic for sending Abandoned Checkout notifications is as follows:

Within 15 minutes of the customer filling the phone number, if the order does not appear in your Woocommerce orders panel, OR, it does appear in the order panel, but with statuses like Pending Payment / Failed, then the checkout will be considered as abandoned and an ‘Abandoned checkout’ event will be sent into your Interakt account.

= How can I publish a WhatsApp Widget on my Wordpress website via Interakt? =
Steps:
* Go to [https://appdev.interakt.ai/widget/manage](https://appdev.interakt.ai/widget/manage)
* Design your WhatsApp Widget
* Click on Save Changes
* Go to [https://app.interakt.ai/widget/install](https://app.interakt.ai/widget/install)
* Copy the HTML / Javascript code
* Paste it in your website’s code

= How can I create a WhatsApp Catalog with my Woocommerce Products & send it to 1000s of customers? =
Steps:
* Go to Products in your Wordpress Admin and export all products into a CSV file.
* Map the different product fields to the fields given in this [sheet](https://docs.google.com/spreadsheets/d/1pLPQwE755LxumObAW0W9NBCS1hDZiUVfQ2yr_k40c_o/edit#gid=1925743516). This is the template for creating Facebook Catalogs via Google Sheets. The mandatory fields are id, title, description, availability, condition, price, link, image_link, brand
* If you are using a WhatsApp API number starting with +91, you also need to mandatorily include the field “origin_country” for each product. If origin_country is not IN, you need to mandatorily include fields for Importer_name, Importer_address, Manufacturer_info, (Check [this video](https://youtu.be/4jrLHsnyT3Y) from 1 min 27 seconds mark to 2 min 15 seconds mark to understand exactly how to fill these 3 fields in the Google Sheet)
* Log into Facebook Commerce Settings [here](https://business.facebook.com/commerce/) and start creating a catalog using the ‘Data Feed’ method.
* Follow steps given in this video (from the 2 min 16 seconds mark) to understand how to create a FB catalog using your Google Sheet.
* After the catalog is created, go to [Interakt’s Commerce Settings](https://app.interakt.ai/commerce-settings) and complete steps 1C, 1D and 1E to finally connect your Catalog to Interakt.
* Next, [create product collections in Facebook](https://youtu.be/z083PzyZ3C4).
* Sync those collections to Interakt (2nd Step in Commerce Settings) and choose the top 10 collections. Then click on the button in the 2nd Step to create your ‘Collections List’ message on WhatsApp. Whenever this ‘Collections List’ message is sent to a customer and the customer clicks on a Collection, the corresponding WhatsApp Catalog containing 30 items will get sent automatically.
* You can now start attaching this Product Collections List to your Bulk Campaigns - [see how](https://youtu.be/dzJ2THryMpY?t=81)
* You can also attach the Product Collections to your Welcome / OOO / Delayed Messages: [see how](https://youtu.be/WiIBusUm-kM)

= How can I create WhatsApp Auto-replies to customer FAQs ? =
[Read this article](https://www.interakt.shop/resource-center/whatsapp-automation-faq-replies) to understand how to setup custom auto-replies to FAQs & include those FAQs in a List Message (which we call the Interaktive List!).

After you have created your Interaktive List, don’t forget to attach it to your [Welcome / OOO / Delayed messages](https://app.interakt.ai/automation/inbox-setting).

= How can I send a bulk WhatsApp campaign to all customers who have Abandoned Checkout in the last 30 days, or, to all customers who have placed an order in the last 60 days? =

Steps to send bulk campaign to customers who have abandoned checkout in the last 30 days:

* Go to [https://app.interakt.ai/notification](https://app.interakt.ai/notification) and create a New Campaign from scratch.
* In ‘Choose your audience’, click on +Add Filter and search for ‘Abandoned checkout’
* Click on it and you will see a list.
* Choose ‘Created On’
* Then, select ‘after’ and select the date 30 days back
* Click on Done
* This will filter out all those customers who have abandoned checkout over the last 30 days.
* After that, you’ll have to simply select a template and set the campaign live!

Steps to send bulk campaign to customers who have placed order in the last 60 days:

* Go to [https://app.interakt.ai/notification](https://app.interakt.ai/notification) and create a New Campaign from scratch.
* In ‘Choose your audience’, click on +Add Filter and search for ‘order placed’
* Click on it and you will see a list.
* Choose ‘Created On’
* Then, select ‘after’ and select the date 60 days back
* Click on Done
* Again click on +Add Filter and search for ‘order cod’
* Click on it and you will see a list.
* Choose ‘Created On’
* Then, select ‘after’ and select the date 60 days back
* Click on Done
* Make sure that the joiner between the ‘order placed’ and ‘order cod’ filters is set to OR instead of AND.
* This will filter out all those customers who have placed a prepaid or cod order over the last 60 days.
* After that, you’ll have to simply select a template and set the campaign live!

== Screenshots ==

1. Plugin setting page.
2. Step 1 in setting oAuth.
3. Step 2 in setting Order status.
4. Step 3 & 4 Phone number settings & Set live.
5. Interakt campaign

== Changelog ==
= 1.0 =
* Plugin released.

= 1.0.1 =
* FIX: Abandon cart Location warning.

