=== GDPR Tools: comment ip removement ===
Contributors: fhwebdesign
Tags: dsgvo,tools,kommentar,deaktivieren,gdpr,comment,deactivate,remove,löschen
Requires at least: 4.9.3
Tested up to: 6.0
Requires PHP: 6.5
Stable tag: trunk
License: GPL3
License URI: license.txt

Removes the ip adresses saved in comments after saving into database and prevents saving as required in GDPR.

== Description ==
As required in the laws of GDPR, websites aren't allowed to store unencrypted ip adresses.
But this is the case by default in WordPress. This Plugin prevents the saving of ip adresses and creates a menu item in backend with the opportunity to delete all previous stored ip adresses.
This plugin provides the ability to automatically remove ip addresses after a user-defined time.
The GDPR conformity hasn't been proved by an lawyer and only reflects the estimation of the author. If you have further questions is legal advice required.

== Installation ==
1. Click at 'install'
2. Activate the plugin through the plugin menu in WordPress
3. Execute the button 'Delete all comment ip adresses!' at backend menu 'Comments' and submenu 'Comment ip'.
4. Done!

== Screenshots ==
1. Backend without saved ip adresses
2. Backend with saved ip adresses

== Changelog ==
= 1.4 =
* Update to WP 6.0

= 1.3.1 =
* FIX: some descriptions
* FIX: Saving for ip adresses now properly working 

= 1.3 =
* UPDATE: Switched menu item to submenu of 'comments'
* UPDATE: Redesign
* ADD: function to delete all comment ips after user-definable time