=== HQ Rental Software ===
Contributors: faggioni
Donate link: https://hqrentalsoftware.com
Tags: hqrentalsoftware
Requires at least: 5.3.0
Tested up to: 6.0
Requires PHP: 7.3.0
Stable tag: 1.5.29
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

The HQ Rental Software plugin allows you to easily set up the reservation process on your website. Once you install the plugin, connect it to your HQ account, and set up your reservations page, your customers will be able to make reservations which will show on your HQ account. The plugin also allows you to quickly set up a standard booking form, and the vehicle availability calendar on your website in just a few simple steps

== Installation ==

After you add the plugin to your website, please follow the steps below to complete connection with your HQ account.

Step 1 - On your website’s WordPress admin dashboard navigate settings for HQ Rentals.

Step 2 - Under the General Settings section enter your HQ Rental Software user account email and password.  Only account credentials from users with an “Administrator” role should be used.

Step 3 - Press the Authenticate button, if the credentials are approved, the system will show you a success screen informing you that the plugin has been set up and it is now connected.

You can now use the features of the plugin to set up the reservation process and more. For further information and options, please go to our Website Integrations section on our Knowledge Base https://hqrentalsoftware.com/knowledgebase_category/website-integration/

If you experience any issues, please review your user and password and try again. If you are unable to authenticate your user information and fail to connect the plugin, please contact our support team via your HQ Rental Software account and we’ll happily assist you.

== Screenshots ==

1. Here you will need to login using the same credentials you use for logging into the system. Only account credentials from users with an “Administrator” role should be used.
2. Once you have entered your credentials, press the Authenticate button.
3. You will now see the following success screen informing you that the plugin has been set up and it is now connected.
4. You can now go to the tab called “Brands” and you should see this table; now just copy the “Reservations Snippet” and paste that on the page where you would like to display the booking process. The system will automatically resize the iFrame on this page.

== Frequently Asked Questions ==

= How can I set up the plugin? =

You can find instructions under the Installation tab in this page or you can go to https://hqrentalsoftware.com/knowledgebase/wordpress-plugin/ and follow the steps.

= I’m having problems with the reservation workflow on Safari =

Due to incompatibility with Safari and Opera browsers, the domain name of the iframe has to be updated. You will need to add a CNAME record in your DNS records where the value is the name of your tenant. For example, if your link is my-company.caagcrm.com or my-company.hqrentals.app the value for the CNAME record has to be “my-company”, and the value needs to be your link for example my-company.caagcrm.com.

Once you have created the CNAME record on your domain, you will receive an SSL error. Please create a support ticket by clicking on the “?” icon placed on the top right corner in your HQ application, this way our team can proceed with the installation.

= I need to make a custom integration using the system data =

We have a REST API available that can help you interact with the system. For further assistance regarding technical information please visit https://api-docs.caagcrm.com/.

= Need more help? =

You can create a support ticket clicking on the “?” icon placed on the top right corner in your HQ application.
