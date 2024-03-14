=== SEO Backlink Monitor ===
Contributors: activewebsight
Tags: internal link, link, links seo, seo backlinks, building campaign, track your link, backlinks, cron
Requires at least: 4.7.5
Tested up to: 6.4.3
Stable tag: 1.6.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

SEO Backlink Monitor plugin that lets you track your Link Building campaign. Add your link and check if it is do follow or no follow (desktop and mobile user agent), live or not. (Special thanks to [Backlink Monitoring Manager](https://wordpress.org/plugins/backlink-monitoring-manager/) v0.1.3 by Syed Fakhar Abbas). By [Active Websight](https://www.active-websight.de)

== Description ==

SEO Backlink Monitor is a WordPress plugin that lets you track your Link Building campaign. Add your link and check if it is do follow or no follow (desktop and mobile user agent), live or not. It can auto-check all your links with wp-cron (you can select the frequency) and get a report to your email address, when a link changes its state.

It is a modified/optimized version by [Active Websight](https://www.active-websight.de) from the original [Backlink Monitoring Manager](https://wordpress.org/plugins/backlink-monitoring-manager/) (v0.1.3); special thanks to Syed Fakhar Abbas <3.

== Installation ==

This section describes how to install the plugin:

1. Upload the `seo-backlink-monitor` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Plugin screen.

== Changelog ==

= 1.6.0 =
* UPDATED: tested up to WP 6.4.3 and PHP 8.2
* UPDATED: language files
* REFACTOR: SEO_Backlink_Monitor_Parent_WP_List_Table now extends WP_List_Table
* FIXED: protect against xss (thanks to Dimas Maulana)

= 1.5.0 =
* UPDATED: tested up to WP 6.1.1
* FIXED: replace session with db options (site health check, thanks to @divvy)

= 1.4.0 =
* ADDED: save/output http status code from linkchecker (maybe some sites are blocking request or page not found); hover over icon to see status code
* ADDED: check links with random user agent
* FIXED: compare links (linkTo and linkFrom) both with decoded htmlentities
* FIXED: output links in edit-form with htmlentities (so they are the same as stored in DB)
* FIXED: JS: reset search didn't reset column select

= 1.3.0 =
* ADDED: every link now gets checked additionally with mobile user agent
* ADDED: JS: pressing enter key on "search links" triggers search
* CHANGED: update language files
* FIXED: JS: each click on table head column now correctly loads new data (did trigger multiple times)
* FIXED: link IDs didn't increment when inserting multiple links (resulted in deletion of all links inserted at the same time)
* FIXED: use correct esc function to save links to database (esc_url_raw instead of esc_url)

= 1.2.0 =
* ADDED: multi-import links
* ADDED: option to define backlinks-table results per page
* CHANGED: make backlinks-table search caseinsensitive
* CHANGED: make all links lowercase for proper validation

= 1.1.0 =
* CHANGED: for privacy reasons now all displayed links in 'List of Backlinks' have the attribute 'rel="noopener noreferrer"'

= 1.0.0 =
* Initial release - modified version of [Backlink Monitoring Manager](https://wordpress.org/plugins/backlink-monitoring-manager/) by Syed Fakhar Abbas <3
* ADDED: notes on every link
* ADDED: auto-check links with wp-cron
* ADDED: get email report of auto-checked links with changed status
* ADDED: settings - auto-check links with wp-cron, send email report, display or hide notes
* ADDED: .pot file and translations - german and german formal
