=== GamiPress - Notifications By Type ===
Contributors: gamipress, tsunoa, rubengc, eneribs
Tags: gamipress, gamification, point, achievement, rank, badge, award, reward, credit, engagement, ajax, notification, popup, dialog
Requires at least: 4.4
Tested up to: 6.4
Stable tag: 1.0.8
License: GNU AGPLv3
License URI:  http://www.gnu.org/licenses/agpl-3.0.html

Set different notifications settings by type

== Description ==

GamiPress - Notifications By Type let's you set different notifications settings by type for [GamiPress - Notifications](https://gamipress.com/add-ons/gamipress-notifications/ "GamiPress - Notifications") add-on!

= Features =

* Achievement and step notification pattern by achievement type.
* Achievement and step notification pattern by single achievement.
* Points awards and deductions notification pattern by points type.
* Rank and rank requirements notification pattern by rank type.
* Rank and rank requirements notification pattern by single rank.
* Custom notification sound effects by each type.
* Custom notification colors by each type.
* Disable any notification by achievement type, by single achievement, by points type, by rank type or by single rank.

= Settings hierarchy =

On achievements and ranks, you have the ability to set custom settings by a single item or to the whole type, the settings will be applied in following order:

* Single item (achievement or rank) settings -> Type settings (achievement or rank type) -> Notifications settings

If you leave empty the notifications settings of a single item then type settings will be applied.
If type settings are empty, then will be applied notifications setting.

Important: This plugin requires [GamiPress](https://wordpress.org/plugins/gamipress/ "GamiPress") and [GamiPress - Notifications](https://gamipress.com/add-ons/gamipress-notifications/ "GamiPress - Notifications") 1.0.3 in order to work.

== Installation ==

= From WordPress backend =

1. Navigate to Plugins -> Add new.
2. Click the button "Upload Plugin" next to "Add plugins" title.
3. Upload the downloaded zip file and activate it.

= Direct upload =

1. Upload the downloaded zip file into your `wp-content/plugins/` folder.
2. Unzip the uploaded zip file.
3. Navigate to Plugins menu on your WordPress admin area.
4. Activate this plugin.

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 1.0.8 =

* **Improvements**
* Updated deprecated jQuery functions.

= 1.0.7 =

* **Improvements**
* Make single acheivements and ranks color settings persist over type settings.

= 1.0.6 =

* **New Features**
* Added the field "Override Achievement Output" on single achievements and achievement types.
* Added the field "Override Rank Output" on single ranks and rank types.
* **Improvements**
* Prevent to override the automatic display output of achievements and ranks if the override setting is not checked.

= 1.0.5 =

* **Bug Fixes**
* Fixed wrong sound effect override per type.

= 1.0.4 =

* **New Features**
* Added support to Notifications add-on new feature (notifications sound effects).

= 1.0.3 =

* **New Features**
* Added settings by single item (achievement and rank), letting you disable or setting up custom notification to a specific item.
* Added instructions of settings hierarchy on plugin readme.
* Added support to GamiPress multisite features.

= 1.0.2 =

* **Bug Fixes**
* Fixed wrong step's achievement check when disabling step notifications.
* Fixed wrong rank requirement's rank check when disabling rank requirement notifications.

= 1.0.1 =

* **New Features**
* Added support for the new GamiPress Notifications color settings.

= 1.0.0 =

* Initial release.
