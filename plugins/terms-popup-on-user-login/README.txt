=== Terms and Conditions Popup on User Login or at WooCommerce checkout ===
Contributors: lehelm
Donate link: 
Tags: terms and conditions, popup, login, woocommerce, legal
Requires at least: 3.8
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.54
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Popup your Terms and Conditions when users log in to your website or when they purchase an item.

== Description ==

Terms and Conditions popup can be used three ways: 

- Show popup on user login 
- or at WooCommerce checkout
- or using a custom coded condition

**User action is recorded as proof in the database for logged in users.**

This plugin will create a popup with a scrollable window that will show your own custom Terms and Conditions. Only visitors who accept the conditions will be allowed to continue their user flow. Extensive list of features listed below.

Post your Question/Support/Feature-Requests in our [Discord Channel](https://discord.gg/q3Uja2cz5T).  
Never share your license key in the Discord channel.

== Terms on User Login Popup Workflow ==

- Display popup upon user login
- Ability to redirect users
- Ability to force log out users who decline
- Data is stored as proof
- See if user accepted or not on their edit page

== Woocommerce Popup Workflow ==

- Must accept in order to check out
- Log out or redirect users who decline your terms
- Show for logged in users, anonymous visitors or both
- Data is stored in your database for logged in users as proof

== Premium features ==
- Reset users after terms update
- Generate user reports in CSV
- Advanced logging with timestamp for every action
- Do not force logout on decline
- Do not show popup even after decline
- Use custom page as popup content
- Limit popup by user roles
- Force popup on every login
- Analytics and reports
- IP and location tracking capability
- Designated test user
- Store Anonymous users acceptance reference on Order

== How To Install and Use the plugin ==
https://www.youtube.com/watch?v=hr3RxhqqD_k

== Settings Pages of the plugin == 
https://www.youtube.com/watch?v=FhgaXiQPA40


== Terms Popup Features ==

*   The popup will show when user logs in. The popup will not dismiss until user clicks Accept or Decline button.
*   The user's response to the popup, accept or decline gets saved in the database.
*   The popup will not shown again for users who have accepted the Terms. There is an option to turn on "Show popup on every single login" if needed.
*   The popup will show on user login every time for users who have Declined the terms blocking further navigation.
*   The popup has customizable fields and labels.
*   The popup has 2 buttons Accept and Decline.
*   Accept button is only enabled for the user once he has scrolled through terms. This feature can also be disabled.
*   Accept button will dismiss the popup and register that the user has accepted the terms.
*   Decline button will log out the user and register that the user has declined the terms.
*   You can set different redirect URL's if you wish to redirect the user upon clicking Accept or Decline.
*   Easily customize the size of the popup, the labels in the popup as well as the size and color of buttons.
*   Option to enable popup on every single login regardless if they accepted terms at previous logins.
*   TEST MODE can be used to test the looks and text of the popup on any page, be sure to remove from TEST MODE after you tested the popup.

== Terms Popup Premium Features ==

*   The popup allows for resetting all users, to force them to re-accept new terms. Useful if you plan to update your terms in the future and have everyone re-accept your new updated terms.
*   Reset a Single user on the user edit page, force a user to re-accept terms and conditions.
*   Status of who has accepted your Terms gets displayed on the User listing page.
*   Log the time when user has accepted the terms and conditions. Date and time of acceptance is displayed both on individual user edit page and in list of all users.
*   Ability to change the font size for the terms inside the poup.
*   The popup can bring in any custom page that you have on your website as terms content in the popup.
*   Using a custom page as the terms popup content allows for having nicely formatted terms, with links inside. It also allows for use of shortcodes inside, multimedia etc.
*   Ability to limit the popup to only be shown for certain types of user roles. Subscribers, Editors, Admins etc. or custom roles.
*   Advanced loging of user activity regarding the popup.
*   **Generate and download a CSV report of all the users showing each user if and when accepted your terms.**
*   Designated Test User, special test user to which the popup will always show, great for testing even on live environment.
*   Reccomend new features and gain access to premium features by [upgrading to premium](https://www.lehelmatyus.com/terms-popup-on-user-login)

== WooCommerce Integration ==

* Display popup when visiting a product page
* Display popup for anonymous visitors
* Possibility to redirect visitor to your home page on decline of terms

== WooCommerce Premium Features ==

* Saves Acceptance Reference ID on Order
* Display popup on any of the following: product page, category page, cart page or checkout page
* Display popup for logged in users, anonymous visitors or both
* For logged in users response is saved in the database and popup is no longer displayed until terms have been updated
* For anonymous visitors accept response can be remembered in the browser for their conveninece
* On Terms declined redirect users and visitors to any page or url of your choosing
* Possibility to force logout user on decline and redirect them


== Custom condition using your own code =

* Using a premium license key a custom filter is made available for you
* `tpul_override_show_popup`
* You can override the logic when the popup should show or not show for a user or visitor.
* You can simply implement the custom filter in your functions.php file

Code sample below uses original value of `$should_show_popup` in conjunction with extra condition. This is so we only show the popup if a user has not yet accepted the terms and an extra condition is fulfilled.

    function YOUR_CUSTOM_FUNCTION_show_popup($should_show_popup) {

        // should_show_popup - is the original value based on active options
        // EXTRA_CONDITION is an example variable that you can set

        $EXTRA_CONDITION = false; // add your custom logic

        if ($should_show_popup && $EXTRA_CONDITION) {
            return true;
        } else {
            return false;
        }
    }
    add_filter('tpul_override_show_popup', 'YOUR_CUSTOM_FUNCTION_show_popup');

//

== Would you like a custom feature? ==

*   Contact me and let's have a discussion
*   You can go directly to my [contact page](https://www.lehelmatyus.com/contact)

== Installation ==

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. Log in to your WordPress dashboard, go to the Plugins menu and click Add New.

Type "Terms Popup On User Login" and click Search Plugins. Once you’ve found this plugin you can install it by simply clicking “Install Now”.

= Manual installation =

To manually install the plugin downloading the plugin and uploading it to your web server via your favorite FTP client application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

1. Upload `terms-popup-on-user-login.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Getting Started ==

1. Once Plugin has been installed and activated
1. Navigate to Settings -> Terms Popup On User Login Options -> Terms Modal Tab
1. Feel Free to modify the default Settings
1. Place your Terms and conditions in the Textbox
1. Be sure To Navigate to General Settings Tab (Settings -> Terms Popup On User Login Options -> General Settings Tab)
1. Set "Terms Popup On User Login" to "Show Popup" and save changes
1. Popup should fire as soon as you hit any page of your website

== Frequently Asked Questions ==

= How can I redirect a user to a different domain upon decline? =

Navigate to Settings -> Terms Popup On User Login Options -> Terms Modal Tab. If you want to redirect to a different domain make sure the "Decline redirect URL" field contains the full URL including the http:// part.

= When does the text inside "Accepted text" and "Decline and Logout text" fields get displayed? =

When a user clicks either Accept or Decline button there is a brief second while your website is registering their response and preparing the redirect. Extra Text can be displayed during this moment to inform your user what is happening.

= Can I modify the style of the popup? =

Not from within the settings of the plugin. You are welcome to use CSS in your own theme to override the styling of the popup. If you need help with a custom popup theme please feel free to contact me. Some customizations are available if you have purchased a license key, such as Accept and Decline button style and colors as well as font size for the popup content. If you need more customizations, you can send me a custom design for the popup an I will send you a quote on it. Use my [contact form](https://www.lehelmatyus.com/contact) to contact me. For bug reports use the link under "Where can I report bugs?".

= Help and Support =

If you have questions please go to the official [Support Page](https://www.lehelmatyus.com/question/question-category/terms-popup-on-user-login). Search for existing questions and answers as well as place your own question there. If you have a premium license key you can go to the plugins setting page and find a direct email address to customer support. Be sure to mention the Support token in your Email subject to get a prompt response.

= Where can I report bugs? =

You can go directly to my [contact page](https://www.lehelmatyus.com/contact) and report them there. Please be as specific as possible and include as much detail as possible. And Thank you for reporting bugs, let's get them fixed!

== Screenshots ==

1. Popup display
2. Popup confirmation on Accept clicked
3. WooCommerce Order is updated with term acceptance reference ID
4. Turn the popup on in Settings
5. Customize the labels and button features
6. Customize what shows in the popup
7. Display options and customizations
8. User Profile Edit page shows status
9. Woocommerce Features, where for who and how
10. Advanced features for loging and user reset
11. Analytics
12. Email Proof Sent to Clent and Admin

== Changelog ==

= 1.0.51 =
Refine Modal show logic. allow for Woo anonymous emal logging.

= 1.0.51 =
fix critical bug when logging out.

= 1.0.50 =
Add IP tracking capability

= 1.0.49 =
Fix Scroll issue for cookie verification

= 1.0.48 =

= 1.0.47 =
Add option not to show every time for thos who already accepted.

= 1.0.46 =
Refinte visibility logic tree to handle do not show popup for users once they saw it.

= 1.0.45 =
Refine logic of when to show popup and when not to show popup. Implement logic cache for speed.

= 1.0.44 =
Fix popup content showing up unwanted at the bottom of screen.

= 1.0.43 =
Fix php warnings, rename methods.

= 1.0.42 =
Add Option not to log out users after decline.
Add Analytics tab.

= 1.0.41 =
Add Email Testing capability.


= 1.0.40 =
Allow for Offline license Key activation, without the need to connect to the lciense server.

= 1.0.38 =
Downgrade php dependencies.

= 1.0.37 =
Downgrade php dependencies.

= 1.0.36 =
Downgrade php dependencies.

= 1.0.35 =
Rewrite License activation communication with license server.

= 1.0.34 =
Fix License Key can not be activated issue. Add reset single user feature.

= 1.0.33 =
Add special option to load CSS in footer for dashboards that customize HTML Head.

= 1.0.32 =
Refine Popup placement logic. Do not render popup on unnecesarry pages.

= 1.0.31 =
Fix Pages not showing in dropdown when selecting content for popup.

= 1.0.30 =
Rebuild Reports generating mechanism.

= 1.0.29 =
Fix accept button always enabled when set to disabled by default.

= 1.0.28 =
Fix decline button not resizing based on admin settings. Fix website scrolling while popup is open, make it not scrollable.

= 1.0.27 =
Add woocommerce integration and capability to show popup withn the checkout process.

= 1.0.26 =
Disable Esc key while modal is up.

= 1.0.25 =
Admin form field content update.

= 1.0.24 =
Test with Latest WP.

= 1.0.23 =
Test with Latest WP.

= 1.0.21 =
Refine Popup Scrollbar detection.

= 1.0.20 =
Refine mobile styling for buttons.

= 1.0.19 =
Update Rest API routes for less interference with other plugins that think they own every URL.

= 1.0.18 =
Add capability for advanced logging, to log every user action on popup.
Add advanced log report via CSV download.

= 1.0.17 =
Add support for show popup on every login.

= 1.0.16 =
Fix template showing up in popup for sites using Elementor.

= 1.0.15 =
Fix scroll detection for zoomed in browsers.

= 1.0.14 =
Fix xompatability bug with older versions.

= 1.0.12 =
Introduce reporting if user has accepted latest terms or previous versions of terms

= 1.0.11 =
Fix not redirecting after lougout when user declines.

= 1.0.10 =
Fix Accept button disabled until scrolled down popup window.
Fix Reset all users feature.

= 1.0.9 =
Fix users list license needed bug.

= 1.0.8 =
Save the date and time when the user has accepted the Terms in the Database.
Save the date when the last time the Terms were reset.
Allow the popup to be restricted to certain user roles.

= 1.0.7 =
Fix Accept button not enabled even after scrolling terms if Javascipt is loading too early
Add Accept button enabled by default feature

= 1.0.6 =
* Fix Modal showing current page instead of terms for themes using Elementor
* Fix Getting No such user 0 when trying to activate license key.

= 1.0.5 =
* Add the ability to change font size for Terms Text.
* Fix broken link to support Questions

= 1.0.4 =
* Error fix user id null when accepting terms.

= 1.0.3 =
* Error fix for "No such user 0" even though user logged in

= 1.0.2 =
* Force Secure Communication via SSL
* Add System Report under Advanced Options

= 1.0.1 =
* Added Advanced options

= 1.0.0 =
* Initial public release.
