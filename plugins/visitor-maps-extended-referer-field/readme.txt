=== Visitor Maps Extended Referer Field ===

Contributors: Jason Lau
Donate link: http://jasonlau.biz
Tags: map, maps, visitor, who, whos, online, jason, lau, jasonlau, jasonlau.biz, referer, referrer, refering, referring, host, address, search, string, query, engine, google, extend, extended, field, ban, banned, banning, blacklist, filter, ip, address, addresses, lock, protect, secure, security, spam, spammer, spamming
Requires at least: 2.8.4 
Stable tag: 1.2.6
Tested up to: 3.8.1

== Description ==

Extend the <a href="http://www.642weather.com/weather/scripts-wordpress-visitor-maps.php" target="_blank">Visitor Maps and Who's Online</a> plugin by <a title="Visit author homepage" href="http://www.642weather.com/weather/scripts.php" target="_blank">Mike Challis</a> with this handy plugin. This plugin adds additional features, such as IP and referer banning. It also alters the <em>Referer</em> column for the Admin's <em>Who's Been Online</em> grid, displaying the referring host name followed by the search string, if there is one. Referer links are proxified so you can click them without exposing your referring address. Long URLs are prevented from expanding the <em>Who's Been Online</em> grid too wide for viewing. This plugin writes to .htaccess, which is automatically backed-up before any changes are made. There are no settings required.

== Installation ==

Use the Plugin installer which is located in the WordPress Dashboard menu under "Plugins > Add New". 

Activate.  

Once the plugin is activated, it automagically works.

== Frequently Asked Questions ==

= Do you accept donations? =

Yes I do. Thank you in advance!

= Do you know you misspelled the word "referrer"? =

In PHP, the referring URL is known as the "HTTP Referer". That is why I sometimes spell the word, referrer, minus one "r". To avoid confusion, if I am talking about a PHP variable, or the value of a PHP variable, I spell it the PHP way, and not the grammatically correct way.

== Screenshots ==

1. screenshot-1.png Is a picture of the Who's Been Online Referer column after it's been converted.

2. screenshot-2.png Is a picture of the Who's Been Online IP Address column, which displays the IP-banning buttons.

3. screenshot-3.png Is a picture of the Banned-IP Address manager, which eases IP-banning.

4. screenshot-4.png Is a picture of the Automatic Updater which refreshes the Who's Online grid after a set amount of time.

5. screenshot-5.png Is a picture of the Banned-Referers manager, which eases Referer-banning. 

== Changelog ==

= 1.2.6 =

Fixed a bug in the install process.

= 1.2.5 =

Fixed a bug in the install process.

= 1.2.4 =

Fixed a JS bug.

= 1.2.3 =

Removed the LIMIT directive from htaccess.

= 1.2.2 =

Ensured referer url is properly cleaned before added to htaccess. Now old htaccess backup is deleted and rebuilt upon reactivation.

= 1.2.1 =

Fixed a bug which caused the plugin to not display properly when referer host is not internet protocol. Verified compatibility with WordPress 3.5.1.

= 1.2.0 =

Fixed a bug which caused the plugin to not display properly in languages other than English.

= 1.1.9 =

Added a progress meter for the auto-updater so you can see the timer progress. Added a button which allows you to refresh the grid manually.

= 1.1.8 =

New feature automatically reloads the grid after a set period of time. Look for the new Automatic Update link to adjust the settings.

= 1.1.7 =

Fixed a JavaScript bug which caused an error to be thrown just after banning a visitor.

= 1.1.6 =

Fixed a bug in the upgrade process which caused users to have to deactivate and reactivate the plugin for the banning features to work.

= 1.1.5 =

Setup automated .htaccess creation in case .htaccess does not exist. Banning features are now automatically disabled if .htaccess is not found or not writable.

= 1.1.4 =

Made more changes to the plugin activation process. Previously banned IPs were not being carried over to the next version. Hopefully, this will be the final update for a while.

= 1.1.3 =

Fixed a possible bug in the install process.

= 1.1.2 =

Fixed a possible bug in the install process.

= 1.1.1 =

Prayers have been answered. You can now ban or unban referers, and combat those pesky referer spammers.

= 1.1.0 =

Improved the installation process. Fixed a bug which may have cause htaccess to be improperly written during installation. All older versions of this plugin should be overwritten at this time.

= 1.0.9 =

Critical update. Fixed a bug in the initialization.

= 1.0.8 =

Made some improvements to the IP banning feature. Added a couple of screenshots. Fixed a minor bug.

= 1.0.7 =

Fixed a bug in the unbanning process.

= 1.0.6 =

You can now ban and unban ip addresses from the list table using a new button which is located beside each visitor IP address. IP addresses are added to .htaccess when banned, and removed from .htaccess when unbanned. The .htaccess file is automatically backed-up before each change.

= 1.0.5 =

Added the original referer link as the popup title for the http host name. Hold your mouse over the referer link to view the original link.

= 1.0.4 =

Proxified the referer links, so they can be followed without revealing your Dashboard link. Now you can click the search term to search it in Google. Verified compatibility with WordPress 3.4.1 and Visitor Maps 1.5.8.1.

= 1.0.3 =

Minor change to visitor-maps-extended-referer.js.

= 1.0.2 =

Strips %2B from the query string. Verified compatibility with WordPress version 3.3.2.

= 1.0.1 =

Escaped the search string and stripped a couple of HTML characters to prevent injection.

= 1.0.0 =

Initial release.

== Upgrade Notice ==

= 1.0.0 =

Initial release.

= 1.0.1 =

Escaped the search string and stripped a couple of HTML characters to prevent injection.

= 1.0.2 =

Strips %2B from the query string. Verified compatibility with WordPress version 3.3.2.

= 1.0.3 =

Minor change to visitor-maps-extended-referer.js.

= 1.0.4 =

Proxified the referer links, so they can be followed without revealing the original referer. Now you can click the search term to search it in Google. Verified compatibility with WordPress 3.4.1 and Visitor Maps 1.5.8.1.

= 1.0.5 =

Added the original referer link as the popup title for the http host name. Hold your mouse over the referer link to view the original link.

= 1.0.6 =

You can now ban and unban ip addresses from the list table using a new button which is located beside each visitor IP address. IP addresses are added to .htaccess when banned, and removed from .htaccess when unbanned. The .htaccess file is automatically backed-up before each change.

= 1.0.7 =

Fixed a bug in the unbanning process.

= 1.0.8 =

Made some improvements to the IP banning feature. Added a couple of screenshots. Fixed a minor bug.

= 1.0.9 =

Critical update. Fixed a bug in the initialization.

= 1.1.0 =

Improved the installation process. Fixed a bug which may have cause htaccess to be improperly written during installation. All older versions of this plugin should be overwritten at this time.

= 1.1.1 =

Prayers have been answered. You can now ban or unban referers, and combat those pesky referer spammers.

= 1.1.2 =

Fixed a possible bug in the install process.

= 1.1.3 =

Fixed a possible bug in the install process.

= 1.1.4 =

Made more changes to the plugin activation process. Previously banned IPs were not being carried over to the next version. Hopefully, this will be the final update for a while.

= 1.1.5 =

Made more changes to the plugin activation process. Setup automated .htaccess creation in case .htaccess does not exist. Banning features are now automatically disabled if .htaccess is not found or writable.

= 1.1.6 =

Fixed a bug in the upgrade process which caused users to have to deactivate and reactivate the plugin for the banning features to work.

= 1.1.7 =

Fixed a JavaScript bug which caused an error to be thrown just after banning a visitor.

= 1.1.8 =

New feature automatically reloads the grid after a set period of time. Look for the new Automatic Update link to adjust the settings.

= 1.1.9 =

Added a progress meter to the auto-updater so you can see the timer progress. Added a button which allows you to refresh the grid manually.

= 1.2.0 =

Fixed a bug which caused the plugin to not display properly in languages other than English.

= 1.2.1 =

Fixed a bug which caused the plugin to not display properly when referer host is not internet protocol. Verified compatibility with WordPress 3.5.1.

= 1.2.2 =

Ensured referer url is properly cleaned before added to htaccess. Now old htaccess backup is deleted and rebuilt upon reactivation.

= 1.2.3 =

Removed the LIMIT directive from htaccess.

= 1.2.4 =

Fixed a JS bug.

= 1.2.5 =

Fixed a bug in the install process.

= 1.2.6 =

Fixed a bug in the install process.