=== WP User Merger ===
Contributors: fahadmahmood
Tags: user merger, merge users, woocommerce memberships
Requires at least: 4.4
Tested up to: 6.3
Stable tag: 1.5.7
Requires PHP: 7.0
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP User Merger is a WordPress plugin that allows you to merge two different users with seletable user fields. For example display name, user ID, login and email etc. It is a user friendly plugin to merge multiple user accounts.


== Description ==

* Author: [Fahad Mahmood](https://www.androidbubbles.com/contact)
* Project URI: <http://androidbubble.com/blog/wordpress/plugins/wp-user-merger>
* Demo URI: <http://demo.androidbubble.com/user-merger>

After activation there will be a settings page under Users menu. User Merger let you merge information of two users. There are two dropdowns on settings page. Select two users you want to merge. See screenshot 1.
Then press "Merge Users" button. A warning notification will appear for confirmation. Confirm action by pressing the Yes button. See screenshot 2.
After pressing proceed there will be a successful message which means users has been merged successfully. See screenshot 3.
If you select same users, the merge action will not be performed. A warning message will appear that same users cannot be selected for merge action. See screenshot 4.
For detailed selection there is a toggle button that allow you to choose what information the user should include after the merge action. This is a premium feature. See screenshot 5.

= Tags =
wordpress, users, merge

[youtube https://youtu.be/1GyDaARTME8] 
 
== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. There will now be a Split icon in the to Woocommerce  order overview page within the order actions.


== Frequently Asked Questions ==
= How to merge users multiple users? =
On settings page select users you want to merge. Click merge and a warning will appear. Conform procedure and users will be merged.

= How to deal with long list of users while merging? =
There is a toggle button in option tab, by turning it ON you will see dropdown as a searchable text field with suggestion list. Please refresh the settings page after turning toggle button ON.

[youtube https://youtu.be/AoZL4ITzAiM]

== Screenshots ==
1. How it works?
2. Confirmation message before merge
3. Success message after merge action
4. Same users cannot be merged
5. Detailed selection before merge (PREMIUM FEATURE)
6. WP User Merger under users menu
7. Select users you want to merge
8. Select fields and user meta
9. Settings page under users menu
10. Search users while merging.
11. Restoring merged users.
12. Developers Tab - Action & Filter Hooks
13. Help Tab - Contact Developer

== Changelog ==
= 1.5.7 =
* Fix: Data-type ensured on the settings page. 26/09/2023 [Thanks to Celeste Johns]
= 1.5.6 =
* Fix: Improved the WooCommerce customers and orders metadata updates on merging the users. 25/05/2023 [Thanks to Greg Beer & David Pitzer]
= 1.5.5 =
* Fix: Improved the WooCommerce customers and orders metadata updates on merging the users. 25/05/2023 [Thanks to Greg Beer & David Pitzer]
= 1.5.4 =
* Updated for WordPress version. 24/05/2023
= 1.5.3 =
* Fix: A number of fixes reported and insights provided. 04/11/2022 [Thanks to Kunal Sharma via WPScan]
= 1.5.2 =
* Fix: Uncaught Error, Object of class stdClass could not be converted to string. 13/09/2022 [Thanks to Jonathan]
* Fix: A number of fixes reported and insights provided. 04/11/2022 [Thanks to Erwan Le Rousseau / WPScan Security]
= 1.5.1 =
* Updated queries and functions for WooCommerce Memberships. 22/08/2022 [Thanks to Celeste Johns]
= 1.5.0 =
* is_object() check implemented to handle PHP notices. 12/04/2022 [Thanks to Greg Beer & David Pitzer]
* Gravity Forms, Gravity Forms Quiz addon & Gravity Forms PDF related improvements. 04/06/2022 [Thanks to Celeste Johns]
* WooCommerce orders should be transferred to the merged user. 06/06/2022 [Thanks to mrsouza]
= 1.4.9 =
* Action hook added for user deletion. 07/04/2022 [Thanks to Greg Beer & David Pitzer]
= 1.4.8 =
* Edit order page was being blank, it has been fixed. 02/04/2022 [Thanks to g4macgregor]
= 1.4.7 =
* Learndash related updates. 31/03/2022 [Thanks to g4macgregor]
= 1.4.6 =
* Deleted users can be restored using advanced version. 29/03/2022 [Thanks to Charis]
= 1.4.5 =
* Assets updated. 05/06/2021
= 1.4.4 =
* Improved UX. 02/06/2021 [Thanks to Kuang-Li Huang]
= 1.4.3 =
* In merging process of WooCommerce customers so all billing, shipping details and other details should be ensured. 31/05/2021 [Thanks to Kuang-Li Huang]
= 1.4.2 =
* Improved WooCommerce compatibility. 22/12/2020 [Thanks to rochekaid, yonahs and Team Ibulb Work]
= 1.4.1 =
* Removed WooCommerce dependency. 14/10/2020 [Thanks to mjdigital]
= 1.4.0 =
* Removed WooCommerce dependency. 14/10/2020 [Thanks to mjdigital]
= 1.3.9 =
* User roles are rechecked for admin menu access. 13/10/2020 [Thanks to jeholden]
= 1.3.8 =
* Languages added. 08/09/2020 [Thanks to Rais Sufyan]
= 1.3.7 =
* User roles are rechecked for admin menu access. 08/09/2020
= 1.3.6 =
* Undefined variable notice fixed. 15/06/2020
= 1.3.5 =
* Settings page link added to plugins links section. 02/06/2020 [Thanks to @pamcho]
= 1.3.4 =
* Undo option added. 16/05/2020 [Thanks to Team Ibulb Work]
= 1.3.3 =
* Updating assets. 04/05/2020 [Thanks to Rais Sufyan]
= 1.3.2 =
* Email address instead of username in dropdown lists. 03/05/2020 [Thanks to Jonathan Koehn / theWordBooks]
= 1.3.1 =
* Updating assets. 22/04/2020 [Thanks to Rais Sufyan]
= 1.3 =
* Conditional appearance of selection after success msg. 09/08/2019
= 1.2 =
* 2nd release after review. 08/08/2019
= 1.1 =
* First release after review. 07/08/2019
= 1.0 =
* First release.

== Upgrade Notice ==
= 1.5.7 =
Fix: Data-type ensured on the settings page.
= 1.5.6 =
Fix: Improved the WooCommerce customers and orders metadata updates on merging the users.
= 1.5.5 =
Fix: Improved the WooCommerce customers and orders metadata updates on merging the users.
= 1.5.4 =
Updated for WordPress version.
= 1.5.3 =
A number of fixes reported and insights provided. 04/11/2022 [Thanks to Kunal Sharma via WPScan]
= 1.5.2 =
Uncaught Error, Object of class stdClass could not be converted to string.
= 1.5.1 =
Updated queries and functions for WooCommerce Memberships.
= 1.5.0 =
Gravity Forms, Gravity Forms Quiz addon & Gravity Forms PDF related improvements.
= 1.4.9 =
Action hook added for user deletion.
= 1.4.8 =
Edit order page was being blank, it has been fixed.
= 1.4.7 =
Learndash related updates.
= 1.4.6 =
users can be restored using advanced version.
= 1.4.5 =
Assets updated.
= 1.4.4 =
Improved UX.
= 1.4.3 =
In merging process of WooCommerce customers so all billing, shipping details and other details should be ensured.
= 1.4.2 =
Improved WooCommerce compatibility.
= 1.4.1 =
Removed WooCommerce dependency.
= 1.4.0 =
Removed WooCommerce dependency.
= 1.3.9 =
User roles are rechecked for admin menu access.
= 1.3.8 =
Languages added.
= 1.3.7 =
User roles are rechecked for admin menu access.
= 1.3.6 =
Undefined variable notice fixed.
= 1.3.5 =
Settings page link added to plugins links section.
= 1.3.4 =
Undo option added.
= 1.3.3 =
Updating assets.
= 1.3.2 =
Email address instead of username in dropdown lists.
= 1.3.1 =
Updating assets.
= 1.3 =
Conditional appearance of selection after success msg.
= 1.2 =
2nd release after review.
= 1.1 =
First release after review.
= 1.0 =
* First release.


== License ==
This WordPress plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version. This WordPress plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this WordPress plugin. If not, see http://www.gnu.org/licenses/gpl-2.0.html.