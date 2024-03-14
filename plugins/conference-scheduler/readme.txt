=== Conference Scheduler ===
Contributors: swift
Tags: conference, workshop, schedule
Donate link: https://conferencescheduler.com/
Requires at least: 4.9
Tested up to: 6.4
Requires PHP: 7.2
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily manage and display complex workshop schedules for conferences, and share workshop details in a clean, searchable, responsive interface.


== Description ==

Conference Scheduler allows you to easily manage and display complex workshop schedules for conferences and similar events, and also provide information about the workshops on your website in a clean, searchable, responsive interface. Create and manage details about your workshops using the standard WordPress admin interface and display it all on any page of your site with a simple shortcode.

= Features =

* Add and edit workshops in the standard WordPress admin
* Collects and displays important information about each workshop: workshop code, time, location, description, presenter, presenter bio, participant limit
* Add files to workshops - lets you easily distribute presentation files or other reference material
* Handles parallel sessions
* Optionally group workshops by location within a session
* Categorize workshops into streams and areas with tags
* Search and filter workshop listings on the front-end for easy access
* Pick workshops on the front-end so delegates can build their ideal conference schedule
* Customize the style of workshops easily using the WordPress customizer
* Responsive design means your schedule looks and works great on all devices - delegates can quickly pull out their phone at any time to check the schedule or workshop info
* Upgrade to [Conference Scheduler Pro](https://conferencescheduler.com/pro/) and you also get:
  * Import/Export to/from Excel
  * Complete user registration system with optional waitlists for space limited workshops
  * Multi-lingual support with WPML compatibility
  * Extensible architecture for customization - adding fields, display templates, search customization


== Installation ==

* Install and activate the Conference Scheduler plugin from the WordPress Plugins Directory.
* Setup Sessions - Conference Scheduler manages workshop timings using Sessions. Enter all possible workshop sessions on the 'Conference -> Sessions' screen of the WordPress admin. Name each session and specify start date/time and session length. Sessions can be nested to easily organize complex schedules (eg. a one hour session with two 30min sessions in parallel).
* Setup Streams and Areas - if your conference has defined streams it's best to define these tags first so you can apply them to workshops. You can also create new tags when entering workshops if you think of new ones later.
* Enter Workshop data - Add your workshops
* Display the schedule on any page of your site with the Conference Scheduler block or \[conf_scheduler\] shortcode.


== Screenshots ==

1. Browse/search workshops and show details.
2. "Pick" workshops to save for later and build your shortlist.
3. Search workshops by title and presenter.
4. Manage all your workshops in the familiar WordPress admin interface.
5. Quickly add or edit workshop details.
6. Add files to workshops to help distribute presentations or reference material before and/or after the workshop.


== Frequently Asked Questions ==

= How do I change the way the workshops look =

Use the WordPress Customizer to change basic display settings. For advanced customization, use Custom CSS rules - Conference Scheduler has been designed with a minimal set of CSS rules that are easy to extend. If you need help styling Conference Scheduler to match your theme, get in touch, and I'd be happy to help for a small fee.

= How are picks stored =

Picked workshops are stored as a cookie on the device and are thus saved between visits. If a user logs in and registers for a workshop, that is saved to their profile and displays on any device they log in with.

= What fields does the front-end search box search in =

The front-end search box looks for the query text in the workshop title and presenter fields.


== Changelog ==

= 2.4.7 - 2024-03-04 =
Tweak: Add filters to enable customization of default admin permission requirements

= 2.4.6 - 2024-02-22 =
Fix: Save order issue with workshop timing metadata

= 2.4.5 - 2023-03-03 =
Tweak: Move documentation to standalone site
Tweak: Declare WP 6.1 support

= 2.4.4 - 2022-03-03 =
Fix: Admin search issue
Fix: Closed potential XSS hole in Admin (thanks WPScan!)

= 2.4.1 - 2021-06-30 =
Fix: Potential template issue on Windows servers
Fix: PHP 7.4 compatibility

= 2.4 - 2021-05-17 =
Fix: Bug affecting search results with grouped workshops
Tweak: Improved search results to accommodate accented characters
Tweak: Prefixed theme and keyword CSS classes to avoid conflicts
Dev: options migrated to separate entries in wp_options

= 2.3.1 - 2020-12-07 =
Tweak: Open Location URLs in a new tab by default
Tweak: Clicking on a theme or keyword on a workshop sets the filter to that tag

= 2.3 - 2020-11-29 =
Feature: Added a Location URL field to allow locations to be clickable links (perfect for online conferences)
Update: Updated Select2 to v4.0.13
Update: WP 5.6 compatibility

= 2.2.2 - 2020-10-20 =
Fix: Show My Picks button now respects Show Available toggle (Pro Version)

= 2.2.1 - 2020-10-05 =
Tweak: Added hooks to allow customization of the search filters

= 2.2 - 2020-09-24 =
Feature: Single Workshop view - each workshop now has its own page with all the info on it!

= 2.1.1 - 2020-09-05 =
Tweak: Added new shortcode option to show only workshops of a single session (eg. singlesession="ID")
Tweak: Added new shortcode option to show only workshops from a list of themes or keywords (eg. theme="my-theme-slug,another-theme-slug")

= 2.1 - 2020-08-10 =
Feature: Unified Search/Filter box saves space and makes the interface more intuitive.
Added: Support display of multiple instances of the schedule on a single page.
Fixed: Properly close session and day containers.

= 2.0.7 - 2020-07-16 =
Fixed: Prevent master JS variable from overwriting Pro version.

= 2.0.5 - 2020-02-10 =
Fixed: Prevent links from closing an expanded workshop.
Fixed: Always show placeholder text for filter dropdowns.

= 2.0.4 - 2019-12-02 =
Fixed: Layout issue with day tabs and defaultState set to open.

= 2.0.3 - 2019-12-02 =
Fixed: Bug that could cause picks to not save.

= 2.0.2 - 2019-11-19 =
Fixed: Bug that could cause wrong date display on workshop.
Fixed: Bug that could reset session length to 0.

= 2.0.1 - 2019-11-16 =
Fixed: Bug that would reset session length to 0.
Tweak: Only apply day tab CSS when needed.

= 2.0 - 2019-11-14 =
Added: Timeline mode - view your workshops along a timeline rather than in sessions
Added: Day Tabs mode - display days as tabs across the top of the schedule

= 1.7.4 - 2019-10-02 =
Fixed: removed extra CSS class from favourite star

= 1.7.3 - 2019-08-27 =
Added: Add session and day slugs as a class to allow CSS targeting
Fixed: Favourite star no longer can split in 2
Fixed: Blank session start spawning many days error

= 1.7.2 - 2019-08-09 =
Added: Filter to set the end time for a day (to control placement of late night workshops)
Fixed: Shortcode attribute names always lowercase

= 1.7.1 - 2019-08-07 =
Tweak: Add 'no_workshops' class for empty sessions

= 1.7 - 2019-07-23 =
Added: Support for new Pro features
Fixed: Sort sessions starting at same time by name

= 1.6.6 - 2019-06-19 =
Hotfix: Provide defaults for all options

= 1.6.5 - 2019-06-18 =
Added: Ability to customize the Day format for day titles using the Customizer
Fixed: Sessions now sort alphabetically if start time is the same

= 1.6.4 - 2019-05-27 =
Fixed: Some workshops wouldn't show when using collapsed sessions
Added: Print CSS styles for showing My Picks
Added: Filter to customize session secondary sort order (always by time first)

= 1.6.3 - 2019-04-11 =
Fixed: Bug when showing two copies of conf_scheduler on one post

= 1.6.2 - 2019-04-11 =
Fixed: Bug in bulk delete posts

= 1.6.1 - 2019-02-24 =
Fixed: Fatal error on WP 4.X.

= 1.6 - 2019-02-19 =
Added: Session descriptions - now you can write a brief description of each session to show above the workshops
Fixed: Minor style issues

= 1.5.4 - 2019-02-14 =
Upgraded: The Conference Scheduler block has been rebuilt. More features, better compatibility.

= 1.5.1 - 2019-01-12 =
Added: Workshops can now be optionally filtered by multiple themes/keywords
Fixed: Resolved date picker conflict with ACF
Fixed: Favorite workshop star icons not working when FontAwesome JS is also enqueued
Fixed: Added print CSS styles to prevent workshops from overlapping on print

= 1.5 - 2018-12-12 =
Added: Search on workshops list in admin now also searches Location and Presenter fields
Added: Display presenter on workshops list in admin
Upgraded: Bundled Font Awesome upgraded to v5.5 Free. Custom CSS may need updating

= 1.4 - 2018-11-09 =
Added: Support for WP 5.0 and the new Block Editor
Fixed: Accidentally triggering Delete Workshops when pressing enter on options text field
Tweak: Options processing tweaks for Pro

= 1.3.2 - 2018-10-10 =
Fixed: bug preventing display of settings page for some users
Changed: Block editor support - hide default sessions UI

= 1.3.1 - 2018-10-03 =
Added: Support for WordPress Admin Dark Mode
Changed: Improved nonce validation
Fixed: Minor bug fixes

= 1.3 - 2018-08-03 =
Added: Customize workshop sort order in the WP Customizer.
Added: New options for defaultstate attribute.
Changed: Refactor Pro as an add-on to the base plugin.
Fixed: All strings now translatable.
Fixed: Minor bug fixes.

= 1.2.3 =
Added: 'defaultstate' shortcode option to control initial display of workshops and sessions.

= 1.2.2 - 2018/05/12 =
Improvement: Shortcode now displays children of the specified session and not the session itself.

= 1.2.1 - 2018/05/12 =
Minor bug fix

= 1.2 - 2018/05/12 =
Launched: Conference Scheduler Pro, with full Excel import/export, user registration, WPML multilingual capability and more.
Added: Basic workshop display styles can now be easily set with the WordPress Customizer
Removed: User registration is now a Pro feature.

= 1.1 - 2018/03/08 =
Added: Logged in users can now register for workshops by clicking a new register button!
Added: Shortcode can now be customized to display only a requested session (and children)

= 1.0.1 =
Fixed: merged sessions that have no children

= 1.0 =
Initial release

== Feature Requests ==

If there is a particular feature that you'd like to see in Conference Scheduler, let me know and I'll consider adding it.
