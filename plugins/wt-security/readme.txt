=== WebTotem Security ===
Contributors: wordpress@wtsec
Donate Link: https://wtotem.com/
Tags: security, firewall, monitoring, scan, antivirus, wtotem, protection, wt-security
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.1

WebTotem is a SaaS which provides powerful tools for securing and monitoring your website in one place in easy and flexible way.

== Description ==

**WebTotem Security** – is a security plugin for WordPress that  monitors websites and prevents website attacks with the help of special internal and external utilities.

## Internal utilities:##
1) **Antivirus** looks for shells, viruses, obfuscations, or file changes.
2) **Firewall** checks client requests to the server preventing SQL injections, XSS, or DDOS attacks.

## External utilities:##
1) **Deface scanner** shows the substitution of pages by hackers on the website.
2) **SSL module** shows the expiration date of the website SSL certificate.
3) **Port scanner** detects open ports on the web server that can be exploited by intruders.
4) **Reputation module** shows blacklist entries.
5) **Accessibility module** tracks the website availability and page response time.
6) **Technology module** detects the technology stack and its versions.
7) **Server Resources module** shows RAM/CPU load data and server disc usage.

It is required to have an account on [WebTotem](https://wtotem.com/) to use the extension.

== Installation  ==
Installing the WebTotem security plugin is very simple. Detailed description of the process with screenshots is available [here](https://docs.wtotem.com/plugin-for-wordpress) , however below we give a short instruction.

To install WebTotem Security:

1. Go to the "Plugins" page and then select "Add New",
2. Search for our plugin using the name "WebTotem Security",
3. Once you have installed the plugin, you need to activate it. Go to the "Installed Plugins" page and click on the "Activate" button,
4. Go to the [WebTotem](https://wtotem.com/cabinet/profile/keys) and Generate an API-key on the "API-keys" page,
5. Use the API-key to activate plugin in the Wordpress admin panel on the "WebTotem Security" page.

Visit the [Support Forum](https://wordpress.org/support/plugin/wt-security/) to ask questions, report bugs or suggest new features.

== Frequently Asked Questions ==

More information on the WebTotem Security plugin can be found in our [Help Center](https://docs.wtotem.com/plugin-for-wordpress).

= SETUP =
**Why can’t I activate Wordpress plugin with API-Keys?**
It is required to copy API-Key immediately after it has been generated. Since we don't store API-Keys with authentic namings for the sake of security issues. If you did not copy it from generation window, we recommend you to delete it, generate a new one again and copy it with original naming.

= FIREWALL =
**Why doesn’t firewall block the attacks?**
After installation the firewall is undergoing training for two weeks, analyzing the operation of the system and all requests. Upon completion of the training, the firewall will start to block attacks. If after two weeks after installation the firewall does not block attacks, then contact support.

**Does GDN send my data to other WebTotem clients?**
Thanks for the question. You don't have to worry about your personal data. GDN option shares data collected between your websites and does not share it with other WebTotem clients.

= ANTIVIRUS =
**How does antivirus work?**
Our antivirus scans every 6 hours and scans automatically each time the filesystem changes. In other words, if you upload a new file to your website our antivirus scans it immediately. There is also an option to start manual scanning by clicking the rescan button in the right top of the module.  Manual scanning shows the same results if no changes to filesystem has occured since the last automatic scanning.

**How do I delete an infected file?**
It is impossible to completely delete a file marked as infected by an antivirus using our service. This can be a vital file for your website. You can quarantine this file. To do this, select the site you need in your personal account. Go to the antivirus module, click the "SHOW MORE" button, configure the filter for infected files and click the "trash bin" icon next to the file name.

== Screenshots ==
1. Dashboard - Shows statistics for all modules.
2. Firewall - Shows firewall activity, attacks map.
3. Antivirus - Shows antivirus and quarantine logs.
4. Settings - Offers multiple settings to configure the functionality of the plugin.
4. Reports - Offers multiple tools to create reports.

== Changelog ==
= 2.4.24 =
* Added forceCheck buttons
* Fixed AV data request
* Internal improvements

= 2.4.23 =
* Fixed some errors WP scan
* Added user feedback popup

= 2.4.22 =
* The Open Path Scanner module has been added
* The Port Scanner and SSL modules have been changed
* Availability and deface modules have been removed

= 2.4.21 =
* The logic has been changed, now when the plugin is removed, the AV and WAF agents are not deleted
* Fixed styles for mobile devices

= 2.4.20 =
* Fixed an issue with blocking custom administrator roles
* WP scan improvements

= 2.4.19 =
* Fixed some errors in multisite
* Internal improvements

= 2.4.18 =
* Added the Confidential files section on the WP scan page
* Added support for different domains in multisite mode
* Added the rescan button to the WP scan page
* Fixed errors caused by the absence of the wtotem_audit_logs table in the database
* Changed the maximum value to 10000 for the DOS limits parameter
* Internal improvements

= 2.4.17 =
* Added the setting blocking countries
* Added WP scan page: Log of user actions. Logs on found links, scripts and iframes

= 2.4.16 =
* Added pop-up notification
* Added 2FA to all users
* Fixed an error saving settings without installed agents

= 2.4.15 =
* Fixed the cause of php warnings
* Fixed conflict with Google Authenticator
* Fixed errors in styles
* Internal improvements

= 2.4.14 =
* Added firewall log report
* Added login attempts
* Added password reset attempts
* Added Determining the environment by the API-key

= 2.4.13 =
* Fixed ajax error

= 2.4.12 =
* Added Two-factor authorization
* Added reCAPTCHA for authorization
* Added the option to Hide the WP version
* Added API Data Caching
* Fixed a bug when switching to a multisite

= 2.4.11 =
* Fixed the problem of reinstalling agents when updating.

= 2.4.10 =
* Fixed a bug when upgrading from older versions.

= 2.4.9 =
* Fixed issues with switching to a multisite

= 2.4.8 =
* Session data storage has been changed

= 2.4.7 =
* Fixed an issue related to using the function str_contains

= 2.4.6 =
* Internal improvements

= 2.4.5 =
* Fixed session errors

= 2.4.4 =
* Internal improvements

= 2.4.3 =
* Fixed styles issue

= 2.4.2 =
* Fixed multisite page view

= 2.4.1 =
* Added multisite support
* All settings have been moved to the settings page
* Internal improvements

= 2.3.47 =
* Change title for request counter on WAF blocks
* Fixed adding domains

= 2.3.46 =
* Fixed adding IDN domains

= 2.3.45 =
* Fixed page reload issue

= 2.3.44 =
* Fixed a problem with viewing AV logs

= 2.3.43 =
* Added URL white list
* Fixed the issue of time zone

= 2.3.42 =
* Added port ignore list
* Added the ability to send IP addresses by list
* Added notifications settings

= 2.3.41 =
* Fixed the issue of reinstalling agents

= 2.3.40 =
* Fixed styles

= 2.3.39 =
* Added antivirus last scan time

= 2.3.38 =
* Fixed an issue with API key authorization

= 2.3.37 =
* Fixed the issue of deleting agent files

= 2.3.36 =
* Fixed redirects issue

= 2.3.35 =
* Fixed the authorization issue

= 2.3.34 =
* Changed the translation algorithm
* Added ru-Ru language

= 2.3.33 =
* Fixed logout bug

= 2.3.32 =
* Fixed waf training period

= 2.3.31 =
* Changed display of data

= 2.3.30 =
* Fixed file filter by status
* Updated agents statuses

= 2.3.29 =
* Fixed styles dark mode
* Logic changed, agents are removed when logout

= 2.3.28 =
* Updated plugin information
* Updated screenshots

= 2.3.27 =
* Fixed the issue of deactivating the plugin

= 2.3.26 =
* Fixed the issue of adding a file to the quarantine

= 2.3.25 =
* Added analytics system

= 2.3.24 =
* Added Firewall advanced options allow/deny list

= 2.3.23 =
* Fixed conflict with some plugins
* Fixed session errors in the "site health" section

= 2.3.1-22 =
* Added antivirus permission changed filter
* Added download antivirus log
* Added antivirus rescan
* Fixed plugin deactivation bug
* Fixed the issue of adding sites with www

= 2.3 =
* Added report page
* Limit login attempt option
* Added file quarantine
* Added Server resources module

= 2.2 =
* Added settings page
* Fixed data display error
* Added dark mode
* Added attacks map view

= 1.1 =
* Disable waf in admin page

== Upgrade Notice ==
= 1.0 =
Publishing the plugin
