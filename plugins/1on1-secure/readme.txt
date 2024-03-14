=== 1on1 Secure - AI Security, Anti-Spam, and Firewall ===
Contributors: 1on1secure, chopper001
Tags: spam, antispam, anti-spam, comment, firewall, Security
Requires at least: 4.7
Tested up to: 6.4.1
Requires PHP: 5.6
Stable tag: 1.1.10
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Visitor verification and screening for known bad actors. No CAPTCHA required to prevent comment spam, prevent contact page spam, and reduce wordpress vulnerabilty scans from bad actors.


== Description ==


A powerful visitor verification and screening plugin designed to enhance your website's security.

* Reduce Comment Spam
* Reduce Contact Page Spam
* Unique Firewall Reduces the Risk of WordPress Vulnerability Scans
* Identify and blocking known bad actors

AI tools such as WormGPT and FraudGPT are now used to defeat CAPTCHAs, find vulnerabilties, and produce exploits.  New security tools are required to better detect these new AI threats.

With 1on1 Secure, you can strengthen your website's defenses without the need for CAPTCHAs. Safeguard your online presence and ensure a seamless user experience with our reliable security solution.

Take a test spin of this plugin - We would love to hear your feedback!


= Free 6-Month Renewable License =

No Credit Card required

1on1 Secure is an anti-spam plugin which works with the premium Cloud Anti-Spam service 1on1Secure.com. This is a Serviceware plugin <a href="https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/#6-software-as-a-service-is-permitted">https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/#6-software-as-a-service-is-permitted</a>


= Features =

* Firewall identifies and blocks malicious traffic.
* Customizable Personal Blacklisting and Whitelisting
* IP Blocklist blocks all requests from malicious IPs and protects your site while reducing server load.
* Complete reporting shows all blocked visitors and visitors given access
* Customizable geographic blocking and Tor Exit node blocking
* Customizable error page shown to blocked IPs


= Serviceware =
This is a Serviceware plugin for WordPress which interfaces with the 1on1 Secure cloud servers to analyze and score website visitors for potential spam or security threats.  An active 1on1 Secure account is required to use this plugin.  A link will be provided to create a new account from within the plugin settings menu.  A free 6-Month Renewable License will be provided upon registration.


== Installation ==


**Automatic Installation**

1. Login to your wordpress with wp-admin
1. Choose **Plugins** from the menu and click **Add New**
1. Search for **1on1 Secure** and then click **Install Now**
1. Once the plugin is installed, click **Activate Now**
1. Next see **Configuration** below.


**Manual Installation**

1. Unzip `1on1-Secure.zip` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

When you are ready to get started with your own account, simply follow the configuration instructions below:


**Configuration**


1. Choose **Plugins** from the menu and activate the **1on1 Secure** plugin.
1. Click on the link that says **settings**
1. You'll see a blue button that says **Get API key**. Click on it to go to the **1on1 Secure** site.
1. Create a **1on1 Secure** account and verify your account via email. Or if you already have an 1on1 Secure account; login to your **1on1 Secure** account.
1. When your new account has been verified, you'll be redirected to a new page showing you your API key. You can also access the API key by simply logging in.
1. Now, go back to your **1on1 Secure** plugin settings page
1. Enter the API key into your WordPress plugin settings.
1. Click **Update Settings**

== Upgrade Notice ==
Nothing to notice


== Frequently Asked Questions ==


= Will 1on1 Secure replace all my other security plugins? =


No, 1on1 Secure is offered as an additional layer of security to work along with your existing security plugins.


= What is "Tor" and why would I block it? =


Tor is commonly used to hide the origin of a website visitor and makes it difficult to trace back to the original user.


While Tor has legitimate uses for protecting privacy and circumventing censorship, it can also be used by individuals with malicious intentions. Blocking Tor might be considered in certain situations to mitigate potential risks.


= Why would I block visitors from outside the USA? =


This option is provided for websites whose intended visitors are in the USA.  By restricting all other countries, you limit your websites potential exposure to spam and security threats.

== Screenshots ==

1. Configure security settings for your website.

== Change log ==

= 1.1.0 - 08/21/23 =
1. Improved settngs dashboard
1. Updated Screenshots

= 1.1.1 - 08/23/23 =
1. Addressed issue with curl timeout
1. Improved UI for setup screen
1. Repaired issue with Setup Screen attaching to wrong WP menu tab

= 1.1.3 - 08/25/23 =
1. Improved UI for new registrations

= 1.1.4 - 09/05/23 =
1. Added functionality to submit form data for spam scoring

= 1.1.5 - 10/17/23 =
1. Fixed formatting of input labels in the dashboard
1. Improved dashboard loading time

= 1.1.6 - 10/27/23 =
1. Fixes for the dropdown selector for hits graph

= 1.1.7 - 11/29/23 =
1. Fixed a whitespace error that resulted in an error on activation (Thanks to @big-dave for notifying us!)

= 1.1.8 - 12/01/23 =
1. Fixed improper use of admin_enqueue_scripts and replaced with standard function call restricted only to the 1on1 Secure admin page.

= 1.1.9 - 12/01/23 =
1. Improved error message display formatting

= 1.1.10 - 02/14/24 =
1. Fixed issue to enforce one API key for each unique website URL

