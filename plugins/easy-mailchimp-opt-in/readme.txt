=== Easy Mailchimp Optin Form ===
Contributors: Mahfuzar
Tags: mailchimp, email, newsletter, signup, marketing, plugin, widget, mailchimp optin, Mail chimp Opt in, mail chimp signup, mailchimp form
Requires at least: 2.8
Tested up to: 4.0
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: http://mahfuzar.info/

== Description ==

The MailChimp plugin allows you to quickly and easily add a signup form for your MailChimp list as a widget on your WordPress 2.8 or higher site.

Not sure what [MailChimp](http://www.mailchimp.com/features/full_list/) is or if it will be helpful? Signup up for a [FREE Trial Account](http://www.mailchimp.com/signup/) and see for yourself!

After Installation, the setup page will guide you through entering API information, selecting your List and then add the Widget to your site. The time from starting installation to have the form on your site should be less than 5 minutes - absolutely everything can be done via the Wordpress Setting GUI - no file editing at all!

== Installation ==

This section describes how to install the plugin and get started using it.

= Version 2.8+ =
1. Unzip our archive and upload the entire `mailchimp` directory to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings and look for "MailChimp Setup" in the menu
4. Enter your MailChimp API Key and let the plugin verify it.
5. Select One of your lists to have your visitors subscribe to.
6. Finally, go to Appearance->Widgets and drag the `MailChimp Widget` widget into one of your Widget Areas
7. And you are DONE!

= Advanced =
If you have a custom coded sidebar or something else special going on where you can't simply enable the widget through the Wordpress GUI, all you need to do is:

If you are using Wordpress v2.8 or higher, you can use the short-code:
` [mailchimp Optin="1"] `

Where ever you want it to show up.


== Developer Mode ==

You can enable "Devleoper Mode" by adding the following line to your `wp-config.php` file just above the "That's all, stope editing!" line.

    define('MAILCHIMP_DEV_MODE', true);

This will enable the MailChimp List Subscribe plugin to operate without the need to connect an external MailChimp Account, and will provide a
subscription form widget that will not actually submit anywhere.

This will allow you to style and configure the widget in non-production environments that are not publicly accessible.

For more Developer Mode customization options see the following article:

http://connect.mailchimp.com/how-to/how-to-article-configuring-developer-mode-for-the-list-subscribe-wordpress-plugin

== Frequently Asked Questions ==

= What in the world is MailChimp? =

Good question! [MailChimp](http://mailchimp.com/features/all/) is full of useful, powerful email marketing features that are easy to use and even a little fun (that's right---we said fun), whether you're an email marketing expert, or a small business just getting started.

To learn more, just check out our site: [MailChimp](http://mailchimp.com/features/all/)


= Wait a minute, you want me to pay to try this? =

*Absolutely not!* We welcome you to come signup for a [FREE Trial Account](http://mailchimp.com/signup/) and see if you find it useful.



== Screenshots ==

1. Plugin setting page Settings > mailchimp. 
2. An example mailchimp Optin Widget
3. Demo Optin form. 




== Changelog == 

version 1.0 - 20.8.2014
First release
version 1.1 - 8.9.2014
fixed name and email icon
version 1.3 - 9.9.2014
fixed input field width issue

== Upgrade Notice ==

= 1.0 =
First release 
= 1.1 =
Name and email icon fixed.
= 1.3 =
fixed input field width issue
