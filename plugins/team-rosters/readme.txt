=== Team Rosters ===
Contributors: MarkODonnell
Donate link: http://shoalsummitsolutions.com
Tags: sports,teams,rosters,players,team roster  
Requires at least: 3.4.2
Tested up to: 6.4.3
Requires PHP: 7.0
Stable tag: 4.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Manages multiple team rosters. Creates roster tables, player galleries, and player profile pages.

== Description ==

The MSTW Team Rosters plugin manages rosters for multiple sports teams. It provides roster tables with built-in formats for high school, college, and professional teams as well as custom roster formats for baseball. Admins can repurpose data fields by re-labeling them, so rosters can be used for Office Directories, for example. See the [MSTW Plugin Development Site](http://dev.shoalsummitsolutions.com) for examples.

Players are assigned to team rosters using a Teams custom taxonomy. These taxonomies may now be linked to the MSTW Schedules & Scoreboards teams database, and the Team Rosters plugin can pull information on teams, such as their logos and colors, from that plugin. 

The plugin supports as many players and teams as needed. It provides several views of rosters including: a table (via a shortcode), a player gallery (via both a shortcode and a custom taxonomy template), and single player bio (via a custom post type template). Samples of all of the above displays are available in the screenshots on WordPress.org and on the [Shoal Summit Solutions Plugin Development Site](http://shoalsummitsolutions.com/dev).

[The complete users manual is available at] (http://shoalsummitsolutions.com/category/users-manuals/tr-plugin/)

== Installation ==

Complete installation instructions are available on [shoalsummitsolutions.com](http://shoalsummitsolutions.com/tr-installation/). 


== Frequently Asked Questions ==

[Frequently Asked Questions are available here](http://shoalsummitsolutions.com/tr-faq/).

== Usage Notes ==
*I suggest that you use the test pages on [the MSTW Plugin Development Site](http://shoalsummitsolutions.com/dev) as guides to what works and what doesn't.*

The [Other Usage Notes](http://shoalsummitsolutions.com/tr-usage-notes/) are available on shoalsummitsolutions.com.

== Screenshots ==

1. Sample Roster Table
2. Sample Player Gallery
3. Sample Single Player Bio

== Changelog ==

= 4.6 =
* Added new capability to customize the order of the fields/columns in roster tables (in addition to labels and visibility).
* Fixed issue which prevented player profiles for players on multiple teams from displaying correctly. Note this is only an issue if a site has a single player on multiple teams.
* Major improvements in player gallery responsiveness to the default fields/columns & color settings, combined with shortcode arguments. 
* The gallery page (WP taxonomy) now accepts arguments.
* The single player template now responds correctly to the combined settings and shortcode args (passed to it).
* Re-tested CSV import (more CSV features coming in planned releases)
* Created a new POEdit Template for I18N (Internationalization & translation) support.

= 4.5 =
* Added color settings for the team rosters 2 shortcode [mstw-tr-roster-2].
* Added field settings for the team rosters 2 shortcode [mstw-tr-roster-2].
* Added 'roster_type' settings for the 3 data fields in the team rosters 2 shortcode. 
* Re-tested the color and field settings for all shortcodes. 
* Removed the PHP each() function which has been removed from PHP 8.0.
* Fixed a couple of bugs with the bio page colors in the Settings admin page.
* Corrected a bug with the player bio page layout(template) which caused problems on some, but not all, websites.
* Corrected bug with roster table sort by number.
* Changed handling of height & weight columns. When both are displayed, they are now combined into one column.
* Changed the default color scheme of all shortcode displays
* Roster tables now sort correctly by number and name.

= 4.4 =
* Added new mstw_tr_roster_2 shortcode.
* Added new "long format" for the position field, so a player can have a postion of QB and a long format position of Quarterback. In this release, the long format is used only in the mstw_tr_roster_2 shortcode display.
* Re-designed the player profile/bio page to make it (much more) responsive.

= 4.3 =
* Fixed link to the plugin's settings page from the Plugins admin page.
* Added a dropdown menu to the single player page that allows the user to access all players on a given team.

= 4.2 =
* Allow user to sort roster tables by number and name on the front end.
* Fixed bug that prevented showing players by last name (only).
* Minor stylesheet cleanup.

= 4.1.4 =
* Fixed bug with settings. Should be able to always save them now without PHP warnings.
* Removed call to get_screen_icon(). screen_icon() has been removed from WP core.

= 4.1.3 =
* Added mstw_tr_get_teams_list to allow MSTW League Manager to link rosters to teams.
* Removed add_meta_boxes_mstw_lm_team action to eliminate PHP warnings. 

= 4.1.2 =
* Fixed a bug that prevented the Edit Rosters screen from saving any data.
* Customizations to labels in the Data Fields & Columns settings tab are now reflected on the appropriate admin screens, in addition to the front end displays.

= 4.1.1 =
* Fixed a couple of installation bugs. No new functionality.

= 4.1 =
* Added new admin screen to add players to rosters “in bulk” (paginated, 20 at a time).
* Added a new admin screen to edit players on a team “in bulk” (20 at a time).
* Added the capability to link the teams in Team Rosters to teams in the MSTW League Manager plugin, in addition to the MSTW Schedules & Scoreboards plugin. Team logos are pulled from the selected database when the display settings call for it. (Team Colors are available only in S&S currently, but that will be addressed in the next League Manager release.)
* Added a Quick Start admin screen.
* Added context sensitive help to all admin screens.
* Finally squashed the dastardly bug that 'broke' Featured Images (thumbnails) for posts in SOME THEMES. (I believe. Please let me know if you see this behavior again.)
* Corrected a bug that prevented “sort roster by number” from working in certain circumstances.
* Corrected a bug that prevented the CSV Import screen from using the team selected in the “Select Team to Import” control.
* Moved translation (internationalization) to [WordPress’s new “PolyGlots” system] (https://translate.wordpress.org/), and removed the /lang/ directory from the plugin itself.

= 4.0.2 =
* Made the mstw_tr_player Custom Post Type searchable
* Corrected a bug that caused local files to be copied into the Media Library when importing players from a CSV file and the Move Photos checkbox was not checked on the CSV Import screen.
* Added a sample CSV file for Teams import to the /csv-examples directory.

= 4.0.1 =
* Corrected a bug in display of 'B' hitters.
* Corrected a bug in the CSV importer that prevented the bats and throws columns created by MSTW CSV Exporter to import correctly.
* Removed a PHP warning from several front end displays.

= 4.0 =
* Access controls for MSTW Admin, MSTW Team Rosters Admin, and Team Admins.
* New data fields for the team taxonomy to integrate with MSTW Schedules & Scoreboards Teams database
* Completely re-wrote the settings screen - organized with tabs and added help screens
* Re-orgainized Edit Player screen
* Added field to link Team taxonomy to MSTW Schedules & Scoreboards Teams DB
* Corrected the display of height/weight in the single-player.php template
* Cleaned up WP internationalization/translation. Domain was changed from mstw-loc-domain to mstw-team-rosters.
* Changed Custom Post Type & Taxonomy names to reduce the possibility of name collisions with themes and other plugins. THIS HAS A MAJOR IMPACT ON UPGRADES FROM PREVIOUS VERSIONS. READ HOW TO DO IT RIGHT HERE.
* Uses the single-player.php and taxonomy-team.php templates from the plugin's /theme-templates directory so the template no longer needs to be copied to the theme's (or child theme's) directory. But they can be moved to the main theme (or child theme) directory if desired. The plugin looks for them there first.
* The plugin's stylesheet (/css/mstw-tr-styles.css) no longer needs to be modified. One can create custom styles in the mstw-tr-custom-styles.css sytlesheet in the theme's (or child theme's) main directory. It will be loaded AFTER the plugin's stylesheet in the plugin's /css directory, so mstw-tr-custom-styles.css will have the highest priory in the plugin's style cascade.
* Added a setting to control the addition of links to single player profile pages from the player names in roster tables 
* Integrated mstw_utility_functions - removed old mstw-admin-utils.php 
* Added if ( !function_exists( 'function_name' ) ) wrappers to all include files
* The problem with filtering the All Players admin screen by Team MAY BE corrected. This bug only appeared on a few installations, so it's difficult to test. If it rears its ugly head on your site, the first thing to try is to deactivate all other plugins, including any other MSTW plugins, and re-activate them one by one. Please let me know, and I'll work with you to fix it.
* Cleaned up many details in admin UI

= 3.1.2 =
* Fixed a bug (a typo) that prevented the team gallery shortcode from behaving correctly.
* Fixed bug with the show/hide table title setting - titles could not be hidden with the display setting. Corrected and tested.

= 3.1.1 =
* Fixed bug that prevented links to single player profiles from working with CHILD THEMES. If you aren't using a CHILD THEME, you don't need this patch.

= 3.1 =
* Fixed bug with sort order. Roster table and player gallery views both sort properly by number, first name, and last name.
* Fixed bug with show_height settings.
* Fixed minor bug: gallery sometimes linked to players/player-slug/?format='' instead of players/player-slug/?format=custom. This bug may or may not have an affect on a site, depending on formats and usage.
* Fixed the "Filter by Team" dropdown on the Show All Players admin screen. 
* Re-enabled the bulk delete menu on the All Players screen.
* Enabled the "Other" field. It may now be used on all 'custom' displays but it is disabled by default.
* Improved responsiveness of single player profile page (single-player.php). Looks better on small screens.
* Combined `single-player.php` and `content-single-player.php` templates (into the `single-player.php` template. Why? ...
* The use of links from the players/roster gallery or players/roster table to the single player profile is now determined by the existence of the `single-player.php` template in the active theme's main directory. Removed the 'use_xxx-links' settings, which are now superfluous. If you want links, just put the `single-player.php` template in the right directory. If not, omit it.
* Re-factored the admin menu code. Added MSTW icon to admin menu and screens.
* The WordPress Color Selector has been added to all color settings in the admin settings screen.
* Added a control to show player photos in the roster tables (shortcode).
* Added a gallery shortcode. [mstw-tr-gallery team=team-slug]

= 3.0.1 =
* Tweaked two calls (one in mstw-team-rosters.php and one in includes/mstw-team-rosters-admin.php) to prevent WARNINGS. (Easily fixed by setting WP_DEBUG to false in wp-config.php.) 
* Restructured the include files (filenames and function calls) to prevent conflicts with other MSTW plugins.

= 3.0 =
* Added a filter by team to the "All Players" table on the admin screen (screenshot-1).
* Added ability to configure table columns and data fields to meet specific application requirements. Show/hide all columns (except Player Name) and change the header/label of all columns and data fields. 
* Provided additional color settings on the Display Settings admin screen, and refactored the code to improve performance.
* Added the new WordPress Color Selector to the Display Settings admin screen.
* Added more CSS tags the display code to allow any team's rosters to be uniquely styled via the plugin's stylesheet. 
* Added player name format control to the Display Settings admin screen. Several formats are available, perhaps most importantly a first name only format is now available to address privacy concerns with young players.

= 2.1 =
* Re-factored the featured image (thumbnail) activation code to avoid conflicts with another plugin. (Thanks, Razz.)
* In the process, modified the theme settings so that the player photo width and height settings would always be honored. The default remains 150x150px regardless of how the thumbnail sizes are set in the theme.
* Corrected another conflict with some themes due to my horrible choice of the function name - my_get_posts(). Shame on me ... it's now mstw_tr_get_posts(). Doh!

= 2.0.1 =
* One include file was omitted from the build. That file is only needed for the CSV import function, which won't run without it.

= 2.0 =
* Added the ability to import rosters from CSV files
* Actived the Featured Image metabox on the add/edit page for players (player custom post type). Standard WordPress "Featured Images" are used for the player photos in the single player and player gallery pages.
* Added admin setting to hide player weights
* Added the ability to set the player photo size on the plugin settings page.
* Added three new formats for baseball: baseball-high-school, baseball-college, baseball-pro
* Cleaned up misc error checking and file/function includes to prevent conflicts with other plugins.

= 1.1 =
* Added the "Player Gallery" view of a roster
* Added admin settings for the sort order to allow numerical rosters in both the table [shortcode] and the player gallery.
* Added admin settings to enable or disable links from both the table view [shortcode] and the player gallery to the single player pages.
* Added an admin setting to control the title of the "Player Bio" content box on the single player view. By default, it is "Player Bio".
* Added fields to the player post type so that no field serves different purposes in different views [high-school|college|pro]. Note that not every field is used in every views and many fields are used in multiple views. However, every field now has one and only one meaning.

= 1.0 =
* Initial release.

== Upgrade Notice ==

Version 4.1 of the plugin was developed and tested on WordPress 4.7.3. If you use older version of WordPress, good luck! If you are using a newer WP version, please let me know how the plugin works, especially if you encounter problems.

