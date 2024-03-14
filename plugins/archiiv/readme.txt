=== Archiiv ===
Contributors: arcbound
Tags: beehiiv, arcbound, archiiv, newsletter integration, newsletter form, marketing automation, newsletter automation
Requires at least: 5.4
Tested up to: 6.4.2
Requires PHP: 7.2
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Connect your site to a Beehiiv account via a shortcode form. Site visitors enter their emails, and they are saved as subscribed users in Behiiv.

== Description ==

*Beehiiv is an online newsletter subscriptions service riviling other subscriptions services like Mailchimp or Substack.*

= About Archiiv = 

The Archiiv plugin allows site owners to easily output a simple form on the front end of their website that collects users' emails and stores them as Beehiiv user subscribers.

The form is output via a simple shortcode placed in the text/block editor or echoed in a php template using the `apply_shortcodes()` function. This allows developers and site owners to output the form anywhere on the website with total control.

= Connect to Beehiiv =

To connect your form with with your Beehiiv account, visit the Archiiv settings page created when you activate the plugin. You will be prompted to add your Beehiiv API key, your Beehiiv publication ID, and redirect URL.

*Instructions about where to find the Beehiiv API key and publication ID are found via a link on the Arhciiv settings page.*

If the API key, publication ID, or redirect URL is missing and you use the shortcode to output your form, it will not display. Admins will see an error box that instructs them to fill out these fields in the settings page properly. Non-admins and logged-out site users / vistors will not see anything. Only when the API key, the publication ID, and redirect URL are saved will the form output correctly.

*For the best user experience, we recommend setting up a specific "Thank You" page for your Beehiiv subscribers to inform them that their submission was successful, but this functionality is optional per site administrator.*

= Form CSS Targets =

Forms are output with simple CSS so that this plugin is easily accessible out-of-the-box to developers and non-developers alike. You may also easily overwrite the CSS styles with your own, using another plugin, theme styles overrides, or any other preferred method of outputting custom CSS.

To target the different parts of the form in CSS, the following is a reference guide of classes used:
- **Parent Form**: .beehiiv-form-connection
- **Input Label**: .beehiiv-email-label
- **Email Input**: .beehiiv-field-1
- **Submit Button**: .beehiiv-submit-button

= Plugin Notifications = 

Upon successful form entry, the site admin email is sent a notification from their server (which can be routed through another SMTP plugin via third-party code / plugins) that the user is successfully subscribed. If the form entry was unsuccessful, the site admin email is also sent a notification that the request did not go through.

There are several reasons for an unsuccessful subscription, but the most likely culprit is that the API key, publication ID, the redirect URL slug, or any combination of these are missing or invalid.

No email will be sent from the server directly to the user, though this may be a feature we add to the plugin later on. Anyone who desires to send new subscribers a welcome email upon successful login should navigate to their Beehiiv account, access their "Settings" tab, select "Publication" under the *Admin* Menu, and scroll to "Welcome Email". Set up your welcome email for subscribers directly from this section.
You can also [access the Beehiiv welcome email here](https://app.beehiiv.com/settings/publication#welcome-email).


== Installation ==

= The Easy Way =

The easiest way to intall the Archiiv plugin is to:
1. Go to your plugins menu.
2. Click "Add New".
3. Search for "Archiiv" in the search bar.
4. Click "Install".
5. Activate the plugin.
6. Visit the Archiiv settings page in the main admin menu.
7. Enter your Beehiiv API key and publication ID
8. Click "Save" to save your settings.
9. Use the shortcode `[beehiiv_newsletter]` to output the form.

= The Manual Way = 
To upload the plugin via FTP:
1. Download the Archiiv plugin zip.
2. Unzip the plugin.
3. Using your favorite FTP client, navigate to the "plugins" directory of your Wordpress Site (found in wp-content directory).
4. Upload your unzipped Archiiv folder to the "plugins" directory.
5. Activate the plugin in your plugins dashbaord in your admin panel.
6. Visit the Archiiv settings page in the main admin menu.
7. Enter your Beehiiv API key and publication ID
8. Click "Save" to save your settings.
9. Use the shortcode `[beehiiv_newsletter]` to output the form.

== Frequently Asked Questions ==

= Do I need a Beehiiv account to install this plugin? =

You can install the plugin without a Beehiiv account, but it won't output a form until you add your Beehiiv API key and plublication ID, which are both provided for you once you sign up for a paid Beehiiv account.

= Can I use the free version of Beehiiv with this plugin? =

No. Beehiiv does not provide API support for non-paid accounts. You can always sign up for a free Beehiiv account, but this plugin and all other integration platforms won't work until you are able to provide an API, which is only available on paid Beehiiv accounts.

= How do I uninstall the plugin? Is my data saved when it is uninstalled? =

To uninstall the plugin, go to the plugins diretory in your Wordpress dashboard. If it is activated, deactivate it first, then you are safe to delete the plugin. The plugin does not erase your API key, publication ID, or redirect URL when the plugin is removed. We don't delete the data to ensure ease of access if you use our plugin again in future. If you want to ensure your data is removed from your database before unintalling the plugin, however, erase the fields in the Arhciiv settings page and click "save".

= I see and can use the form, and it is redirecting my visitors correctly, but I keep receiving an email saying the user wasn't successfully subscribed. What does this mean? =

When a user fills out the form, they will be redirected to the homepage (if your redirect URL is not set) or to your specified redirect URL, even if the subscribe request failed. This is to ensure good user expeirence on your website. Upon a failed request, site admins will receive an email notifiying them that the user was not added to their Beehiiv subscribers list, but they are still provided the email used so that the user can be added manually.

The main reasons for a form failure include either a wrong API key, publication ID, or both. You will want to check both and ensure they are correct on the backend of your Beehiiv account, which can be found here: [Beehiiv Integrtions Page](https://app.beehiiv.com/settings/integrations).

= I don't receive an email when a user fills out my form. What's going on? =

The most likely culprit is that the email is either going to spam or your hosting provider has throttled the number of emails your site is allowed to send. The way to get around this is to set up SMTP to route your email through a trusted email client. This can be done using other third-party SMTP plugins in the Wordpress plugin library. Be sure to whitelist your trusted email so you can receive Archiiv's notifications at the top of your inbox.

= I don't like the styles of the form / the styles don't match the rest of my site. How can I override them? =

We added form styles to be universal and adaptable for any web admin so that the form was styled and functional despite the level of development experience. You can change the styles and layout of the form by targeting these classes in a separate CSS sheet or plugin:
- **Parent Form**: .beehiiv-form-connection
- **Email Input**: .beehiiv-field-1
- **Submit Button**: .beehiiv-submit-button

= Should I contact Beehiiv if I am having trouble with this plugin? =

No. Archiiv is a joint collaborative effort between Beehiiv and [Arcbound](https://arcbound.com/), but it is still considered a third-party plugin to Beehiiv. While Beehiiv and Arcbound may promote the use of this plugin for Wordpress users on their site and other resource materials, both remain largely detached from the code and therefore won't have the answers you need to troubleshoot errors you are experiencing. For support, please post any questions you have regarding the Archiiv plguin in the plugin forum and our plugin developers will do their best to respond in a timely manner.


== Changelog ==

= 1.2 =
* Added additional parameter to the request body to allow for new subscriber email.

= 1.1.1 =
* Fixed verbiage in plugin to prevent confusion about redirect output

= 1.1 =
* Fixed bug not allowing forms to output correctly

= 1.0 =
* First iteration of the plugin
* All features have been tested for efficiency and security


== Upgrade Notice ==

= 1.1.1 =
* Fixed verbiage in plugin to prevent confusion about redirect output

= 1.1 =
* Bug fix to allow for forms to output correctly

= 1.0 =
* Plugin release
