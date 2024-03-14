=== Football Pool ===
Contributors: AntoineH
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=S83YHERL39GHA
Tags: pool, football, prediction, world cup, UEFA, FIFA, champions league, fantasy football, sports
Requires at least: 4.7
Tested up to: 6.3.1
Stable tag: 2.11.4
Requires PHP: 7.4
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

This plugin adds a fantasy sports pool to your blog. Play against other users, predict outcomes of matches and earn points.

== Description ==
This plugin adds a fantasy sports pool to your blog. Visitors of your website can predict outcomes of matches and earn extra points with bonus questions. Every player can view scores and charts of the other pool contenders.

This plugin installs some custom tables in the database and ships with match information for the FIFA 2022 World Cup, but it can be easily populated with the match info for other championships or sports. Please note that deactivating this plugin may also delete all the plugin's data from the database, so please make sure the 'keep data on uninstall' option on the settings page is enabled if you don't want to loose your data (it is enabled by default since version 2.3.1).

I originally coded this pool in PHP as a standalone website for the UEFA 2000 championship and rewrote the damn thing several times for every European Championship and World Cup since. Every year I added new features. In 2012 I decided to rewrite it as a WordPress plugin and uploaded it to the plugin directory. I hope you like it.

A special thank you to all the users of the plugin that donated some money! And also to the translators that found time to translate the many labels in this plugin. And thanks to all the users that reported bugs and helped improving the plugin.

**Features**

* Users can predict match outcomes.
* Automatic calculation of the pool ranking. Or define your own custom ranking for a group of matches.
* You can add bonus questions for extra fun (single answer and multiple choice).
* Add your own teams and match info to use the plugin for another (national) competition.
* Import or export the game schedule.
* Automatic calculation of championship standing.
* Configurable scoring options.
* Use the built in pages and/or shortcodes to add the pool to your blog.
* Use different leagues for your users (optional).
* Users have charts where their scores are plotted over time. And they can compare themselves to other players. (Only available if Highcharts chart API is downloaded separately, see Help for details).
* Several widgets and shortcodes to display info from the championship or the pool.
* Extra info pages for venues and teams.
* Add your own functionality via filters and actions (see help page in the admin or <a href="https://wordpress.org/support/topic/extension-plugins-for-the-plugin-using-hooks">this post</a> in the forum for some examples).
* WP-CLI support for calculating the user ranking (much faster than a calculation via the admin).
* WP-CLI support for importing match results via a csv file.

**Documentation**
The plugin has a help file in the admin that contains a lot of information. But if you like a step by step tutorial, I can recommend the following: Janek from WP Simple Hacks website made a very nice <a href="https://wpsimplehacks.com/how-to-create-a-football-pool-site-with-wordpress/">guide about my plugin</a>. It even has a video where he explains how to set up the plugin.

**Other things**

* This plugin requires WordPress 4.8 or higher, PHP 7.4 or higher and jQuery 1.4.3 or higher.
* If you want to use the charts feature, please download the Highcharts API from http://www.highcharts.com/download (see "Installation" or the plugin's Help page in the WordPress admin for details).

If you find bugs, please contact me via the <a href="http://wordpress.org/support/plugin/football-pool">support forum</a>. If you like the plugin, please rate it on the <a href="http://wordpress.org/extend/plugins/football-pool/">plugin page</a> on WordPress.org.

== Installation ==
To use your own custom translation see the <a href="http://wordpress.org/extend/plugins/football-pool/faq/">FAQ</a> for more information on translating the plugin.

1. Upload `football-pool.zip` in the plugin panel (Plugins &raquo; Add New &raquo; Upload Plugin) or unzip the file and upload the folder `football-pool` to the `/wp-content/plugins/` directory on your server.
2. Activate the plugin through the `Plugins` panel in WordPress.
3. Edit the plugin configuration via the admin menu.
4. Optional: add the pages for the pool to your menu, or use some other method to link to the pages.
5. Optional: add the "Football pool" widgets to your sidebar.
6. Optional: add bonus questions.
7. Optional: 'upgrade' existing users in your blog to pool players.
8. If you want to use the charts feature please download the Highcharts API (http://www.highcharts.com/) and put the `highcharts.js` file in the following path: `/wp-content/plugins/highcharts-js/highcharts.js`. Make sure you use the classic js file including the styling. If you use the theme-less version, then you'll also need to include the highcharts.css code in your theme.

After the pool has been set up, all you have to do is monitor the users that subscribe and fill in the right scores for the matches and the right answers for the bonus questions.

== Frequently Asked Questions ==

= Wow, there are a lot of options. Do I need to change them? =
You can, but it's not necessary. With default settings the plugin should be fine. You can play around with the options before you start the pool.

= The ranking calculation shows an estimated time left of several hours. Why? =
The calculation of the total amount of time left is based on the time a single step took to complete and this is multiplied by the total number of steps remaining for the calculation. If the step sizes (which can be set in the wp-config.php) of the first calculations are much larger than the calculation steps that follow, then at first the total time calculation may be too high.

Or maybe you just have a huge database of users, rankings and matches. If that is the case, then the calculation could be right and the total time for the calculation just takes ages to complete. As a reference, I tested with 2000 users, 50 matches, a couple of bonus questions and 3 custom rankings on my laptop and that calculation took approx. 45 minutes to complete with default step sizes. And when doing that same calculation on the command line using WP CLI, it took less than 10 minutes to complete.

= Do you have a theme that I can use with the plugin? =
No. I'm not a designer, so I don't have the skills to make one.

= I installed the plugin, but there are no matches. What happened? =
Since version 2.0.0 the plugin does not add matches on first install. But it does contain an example match schedule as an exported csv file. Go to the Matches admin page and do an import of a schedule file ("Import matches") if you want to use this example file.

= Do I need the "Predictions" page? =
Yes and no. The plugin needs this page to display predictions of users. So don't delete it. But you can remove it from your menu (WordPress Admin &raquo; Appearance &raquo; Menus).
Some themes or WordPress configurations automatically put all top level pages in the navigation. See information from the theme maker on how to make a custom menu or how to exclude pages from the menu.

= I want to use the plugin for a national competition. Is that possible? =
Yes. There are two ways to do this:
1. Upload a game schedule in the admin. Make sure you understand the required format; you can find an example in the plugin's /data/schedules folder.
2. Use the admin screens to add all the teams, groups, match types, matches, etc.

And, of course, choose a theme or make one yourself that fits your competition or blog.

= The charts are gone! What happened? =
I had to remove the required library because of WordPress plugin license policies. If you want to enable the charts then see the Help page in the WordPress admin for details on how to install the required library.

= I don't see my blog users as players of the pool. =
Go to the WordPress Admin &raquo; Football Pool &raquo; Users screen and check if these users are added in a league (if you are using leagues). Newly registered users are automatically added, but users that already existed in your blog have to be updated in the admin screen. In order to make them a player in the pool add them to a league and save. If you delete a league, then the users in that league must be placed in another league.
If you're not using leagues, then make sure the users are not removed from the pool via the Users screen.

= Is there a translation available? =
See <a href="https://translate.wordpress.org/projects/wp-plugins/football-pool/language-packs/">this page</a> for the available language packs.

If you want to make your own translation, please visit the <a href="https://translate.wordpress.org/projects/wp-plugins/football-pool/">translate.wordpress.org</a> site and view the possibilities for your language. You can also use an editor like Poedit (http://www.poedit.net/) to create the translations and upload the results to the aforementioned website. Make sure you reach out to a <a href="https://translate.wordpress.org/projects/wp-plugins/football-pool/contributors/">PTE</a> for your language to get your translations approved. Or if there is none, then you can apply for the job yourself. Also see the <a href="https://make.wordpress.org/polyglots/handbook/translating/first-steps/">first steps</a> page for more information on the general translation process within the WordPress universe.

If you have a custom translation, you can put the translation files in the wp-content/uploads/football-pool/languages dir (create it, if it doesn't exist yet) and use <a href="https://www.dropbox.com/s/o6q48rg09aunyj0/football-pool-use-custom-translation.php?dl=0">this extension plugin</a> to load it.

The default content for the rules page is in the `rules-page-content-*locale*.txt` file (e.g. `rules-page-content-nl_NL.txt`) and is not handled by the polyglot. If you've made your own translation and mail it to me, I'll add it to the plugin and give you the credits.

= I installed the plugin, but it does not look like your screenshots. =
That's correct. The plugin has some basic styling to position or size elements, but it will not change your entire blog or automagically fit perfect in your website. You will have to adjust the styling yourself to make it look good in your site. Change your theme to overwrite/change the style of the plugin, or use a plugin to add extra custom stylesheets. Please do not change the CSS in the plugin folder; if you ever update the plugin, all your hard work will be gone.

== Localizations ==

If someone wants to help translate the plugin in another language, or help keeping the existing translations up-to-date, please visit the plugin's page on <a href="https://translate.wordpress.org/projects/wp-plugins/football-pool">translate.wordpress.org</a>. Please read the information on the website carefully if you want your translation work to be reviewed and approved: <a href="https://make.wordpress.org/polyglots/handbook/translating/after-your-contribution/">After your contribution</a>.

A big shout-out to all the <a href="https://translate.wordpress.org/projects/wp-plugins/football-pool/contributors/">translation contributors and editors</a> that helped translating the plugin so far. Thank you!

The FAQ contains information on how to use a custom translation. I will keep including the pot language file in the plugin as a starting point for custom translations.

== Shortcodes ==
The plugin has the following shortcodes. See help page in the admin for extra info.

* fp-predictions
* fp-predictionform
* fp-matches
* fp-match-scores
* fp-question-scores
* fp-next-matches
* fp-last-matches
* fp-user-score
* fp-user-ranking
* fp-ranking
* fp-countdown
* fp-group
* fp-link
* fp-register
* fp-totopoints
* fp-fullpoints
* fp-goalpoints
* fp-diffpoints
* fp-jokermultiplier
* fp-plugin-option
* fp-league-info
* fp-chart-settings/fp-stats-settings
* fp-user-list
* fp-money-in-the-pot
* fp-last-calc-date
* fp-next-match-form

== Incompatible plugins & themes ==

The following plugins have been reported as not compatible with the Football Pool plugin. If you have a solution and/or are the author of the plugin you can contact me on wordpressfootballpool [at] gmail [dot] com. If you're having problems with another plugin that is not in the list, please let me know.

Basically, every caching solution should be tested with care.

* DB Cache Reloaded Fix (v2.3)
* Cimy User Extra Fields (v2.6.1) when using the email confirmation option
* Easy Timer (for football pool version 2.3.8 and below)
* Theme Gadgetry (ThemeFuse framework)
* memcached

Some themes prevent the plugin from displaying its content. See <a href="http://wordpress.org/support/topic/no-content-team-pages?replies=8#post-4981300">this post on the forum</a> for a tip on how to resolve this.

== Screenshots ==
1. Predict matches via a form in your WordPress site
2. Score charts of multiple players
3. Match predictions and scores per user
4. Group rankings
5. User ranking
6. Football Pool is packed with several widgets
7. Admin Screen: plugin options
8. Admin Screen: change match outcomes
9. Admin Screen: add a shortcode via the classic editor

== Upgrade Notice ==

= 2.11 =
Minimum PHP requirement is changed to 7.4.0.

= 2.10.0 =
After upgrading to version 2.10.0 a ranking calculation is needed (e.g. from the options page). Please backup your database before updating!!

= 2.9.3 =
Please backup your database before updating!!

= 2.9.1 =
Minimum PHP requirement is changed to 5.6. If you are running a lower PHP version, do not upgrade!!

= 2.8.0 =
Minimum MySQL version changed to 5.5.3. If you are running an older version of MySQL, do not upgrade!!
Please backup your database before updating!!

= 2.7.2 =
Minimum PHP requirement is changed to 5.3. If you are running PHP version 5.2, do not upgrade!!
Please backup your database before updating!!
Pagination class has changed. If you use this class in a (custom) pagination extension, please update your code to set the $page_param variable using the set_page_param() method.

= 2.6.1 =
Please backup your database before updating! If you're upgrading from version 2.5.1 or lower make sure you backup your files in the asset folder and place them after the update in the new football-pool folder in the WP uploads dir.

== Changelog ==
= 2.11.4 =
* New: [fp-next-match-form] shortcode to show a form of only the next match(es).
* Tweak: Small change in the test data CLI method.
* Tweak: Bumped the jQuery version for the TinyMCE dialog to 3.7.1.
* Bug fix: Removed a XSS vulnerability from some shortcodes (low priority).

= 2.11.3 =
* Updated: Removed admin icon from plugin assets and added SVG base64 URI in menu definition.
* Tweak: Removed deprecated warning for dynamic property in Football_Pool_Pool class.
* Tweak: Some refactoring of code.
* Tweak: Stop loading of plugin when doing cron actions (only needed if I ever decide to add cron actions).
* Bug fix: In some edge cases the setting "Fix incomplete predictions" could cause points to be awarded when both scores for a match are missing. Thanks fimo66 and Markus Höcker for reporting the bug.

= 2.11.2 =
* Tweak: Some refactoring of code.
* Updated: Changed the required PHP version to 7.4.
* Bug fix: League dropdown in the user admin caused a fatal error in certain cases. Thanks Ron Robinson for reporting the issue.

= 2.11.1 =
* Bug fix: fp-matches caused a fatal error for empty parameters. Thanks @wongjowo for reporting the issue.

= 2.11.0 =
* New: Audit log in the admin for the administrator to view all saves that users did in their predictions.
* New: Added cache group to WP object cache calls to be able to exclude it from persistent caching plugins.
* New: Option to consider a null value for an incomplete prediction (e.g. only home score entered) as valid and default the missing value to 0.
* New: Shortcode [fp-last-matches] that displays the last started matches before a certain date. Similar parameters as [fp-next-matches].
* New: Added parameters to the [fp-user-list] to limit the output with the 'num' parameter and to display only the latest registrations (based on and ordered by the WP_User's user_registered field) with the 'latest' parameter.
* New: Added 'is_favorite' parameter to teams. This parameter is used to add an extra CSS class to matches.
* Updated: AJAX saves on the frontend are now disabled by default and I made it a setting in the options screen (but it can also still be changed via the FOOTBALLPOOL_FRONTEND_AJAX constant in the wp-config).
* Updated: Changed the required PHP version to 7.3.
* Updated: Removed deprecated code for loading custom MO files. This should be done via the 'override_load_textdomain' filter. See FAQ for more info.
* Updated: Removed league ID from the sorting of the ranking.
* Tweak: Added different admin screen option 'items per page' settings for bonus questions and user answers.
* Tweak: Removed the old wp_enqueue_media check for WP versions lower than 3.5 (this is no longer needed).
* Tweak: Fixed some deprecated warnings (tested in PHP 8.1).
* Tweak: Toast on the prediction form now has a default z-index of 10.
* Tweak: Minor changes to the bonus question CSS.
* Bug fix: The countdown shortcode for the first match removed one element of the matches array and this caused trouble for other elements in the plugin that use the same array reference. Thanks @angelo079 for reporting and @shuhads for helping to sort this out.
* Bug fix: Removed duplicate calculation buttons when removing multiple match types at once.
* Bug fix: Fixed the Bonus question pie chart. Thanks @fimo66 for reporting the issue.

= 2.10.3 =
* Updated: Changed the match schedule because some matches had the wrong UTC time (last games of the group phase and in the final rounds).

= 2.10.2 =
* Updated: Changed the match schedule because the first couple of lines had the wrong year.

= 2.10.1 =
* Updated: Changed the match schedule because FIFA changed the Qatar vs. Ecuador match date.
* New: Added a setting to also show the actual result on the prediction form.
* New: Added a setting to be able to disable the 'unsaved changes check' on the prediction form.
* New: Shortcode [fp-user-score] now also supports the "use_querystring" parameter.
* New: Added a setting to disable the automatic selection of the logged on user for the compare function in the charts.

= 2.10.0 =
* New: Multiple joker support (for the entire pool or per match type). I also renamed the Joker to 'multiplier'.
* New: Multiplier, bonus question answers and match predictions are now automatically saved via AJAX calls on the front-end (on change). AJAX saves can be disabled via the `FOOTBALLPOOL_FRONTEND_AJAX` constant in the wp-config if you do not like the new asynchronous method.
* New: The default delimiter for CSV files (match import) is changed to a comma. If you want to keep using the old delimiter (semicolon), you can override this setting in the wp-config (see help page for details).
* New: If you want to use an alternative date format in a matches CSV file, you can now define constant `FOOTBALLPOOL_CSV_DATE_FORMAT` in your wp-config file. The constant uses the date format convention of PHP's DateTime object and applies to both import and export files.
* New: CLI command 'football-pool test-data' that creates test users in your database with random predictions (for testing purposes).
* New: Added support for the WP Personal Data Exporter tool. Users can request to export their personal data which now will also include their Football Pool data (league name, match predictions and bonus question answers).
* New: Added support for the WP Personal Data Eraser tool. When the option is set to true (defaults to false) the plugin will also erase predictions and question answers for a user when using WP's Personal Data Eraser tool.
* Tweak: Renamed the [fp-scores] shortcode to [fp-match-scores]. Old name is deprecated and will remain available for a couple of versions before I will remove it.
* New: [fp-match-scores] shortcode can now also show the row total via the 'show_total' parameter.
* New: [fp-match-scores] shortcode can now also output the user's prediction per match (instead of or next to the points).
* New: [fp-question-scores] to show a matrix of users and the scores they got on questions (uses same principles as the [fp-match-scores] shortcode for matches).
* New: [fp-last-calc-date] to show the date and time of the last ranking calculation.
* New: Two new admin roles (match editor and question editor).
* New: Bonus question output now also has a filter that can be used in an extension. Also, the code for user view and prediction page is combined into one output function (to get the same HTML structure for the question blocks).
* New: Bonus question statistics view now also has a template that can be overwritten with a filter.
* New: Added 'joker_used' indicator to the score history tables.
* New: Added constant `FOOTBALLPOOL_TOP_PLAYERS` that you can set in the wp-config if you want to show a different number of players in the default statistics page (default is 5).
* Tweak: Bumped the jQuery version for the TinyMCE dialog to 3.6.0.
* Tweak: Some small additions to the help page.
* Tweak: Updated the icon font that comes with the plugin (also some class names have changed).
* Tweak: Optimized the ranking query for big data sets when selecting ranking for a small league (sub set of users).
* Tweak: Changed all colors in the admin to match the new admin WP 5.7 color palette.
* Tweak: Added floating 'back to top' button to all admin pages and a scroll progressbar.
* Tweak: Minor changes to the match table CSS.
* Tweak: Clean up of bonus question CSS.
* Tweak: Added maxWidth and maxHeight to the ColorBox modal that is used for displaying team photos on the front-end.
* Tweak: Question's answer-before-date is now also localized on the front-end.
* Tweak: Score calculation now throws a fatal error when the default ranking is missing in the database.
* Tweak: Increased the max int sizes for the counter columns in the scorehistory tables.
* Bug fix: Fixed a compatibility problem with the Max Mega Menu plugin (thanks Holger for reporting this).
* Bug fix: User profile page showed the wrong active league for the user.
* Bug fix: Match types could be deleted when there were still matches linked to it. This resulted in orphaned matches in the database. Thanks Andreas Neubrech for reporting this.
* Bug fix: When a logged in user, but not a player in the pool, visited the stats page, then an empty page was shown, instead of the top X players.
* Bug fix: When a logged in user, but not a player in the pool, visited the user page, an incorrect page was shown.
* Bug fix: When score date is automatically filled by the plugin, then the question was not included in the calcution when immediately starting the calculation. Thanks fimo66 for reporting this.
* Bug fix: Fixed some translations. Thanks digiblogger for reporting this.
* Clean-up: I removed all old translation files from the package. Contents of the translation files were imported to the WordPress translation website and can be maintained from there. The POT-file is still available as start point for custom translations, or you can download a PO language file from the translation website.
* Clean-up: I removed the logout widget from the plugin since WordPress comes with its own widget.

= 2.9.7 =
* Updated: Prepared the widgets for the new Widget blocks admin that will be introduced in WP 5.8.
* Tweak: Changed the moment when the admin menu gets initiated.
* Bug fix: When a joker is used and activated, the joker icon should be disabled on the form. This did not work when using the date descending sort for matches or when the 'only open matches' plugin was activated (thanks Roy te Lindert for reporting).
* Bug fix: In some cases the v2.9.0 db update script was not executed (thanks @potjekak for reporting).

= 2.9.6 =
* Bug fix: Flex layout for bonus questions was broken on the user page with a combination of certain settings and linked questions (thanks Frans Jansen for reporting).
* Bug fix: Score date input for bonus questions showed the current date when you saved the form with an empty score date (thanks fimo66 for reporting).
* Tweak: Changed the group standing rules to make it easier to override the sorting manually, because the rules of the UEFA for the UEFA 2020 championship did not match the general rules in the code (thanks af3 for reporting).

= 2.9.5 =
* Updated: EURO 2020 schedule.

= 2.9.4 =
* Bug fix: Football Pool widgets couldn't be saved anymore (thanks to dar26ber and Ernst for reporting).

= 2.9.3 =
* Tweak: Database optimization for scorehistory table. Retrieving data from the table is now much faster, which should improve the performance of the ranking page, widget and shortcode.
* Tweak: Renamed some indices in the database for more consistency.
* Bug fix: Shoutbox widget not showing an input for new messages for logged in users.
* Bug fix: Plugin labelled some dates in format "Y-m-d H:i:s" as invalid for the import. Check was updated to also support this format (thanks to Kristin for supplying the data that helped me detect this problem).
* Bug fix: Setting the matches sort method to an option with match type first in the plugin options caused a database query to fail. Result was an empty prediction form (thanks to Kristin and sopanstha for identifying and helping to solve this problem).
* Bug fix: Teams class did not declare the comments property.

= 2.9.2 =
* Bug fix: Fixed error in the calculation step 'compute_ranking'. Larger data sets gave a problem in the AJAX JSON handling.
* Bug fix: TinyMCE dialog for adding shortcodes showed only one user-defined ranking in the ranking selector.
* Bug fix: Fixed display of form with shortcode [fp-predictionform] with the use of the match type parameter.
* Tweak: Refactored some code.

= 2.9.1 =
* Changed PHP version requirement to PHP 5.6 or higher.
* Tweak: Added "open" or "closed" CSS class to bonus questions to indicate their status.
* Bug fix: The check for joker saves was not working correctly when using invisible match types or the [fp-predictionform] shortcode with only a subset of matches.
* Bug fix: League detection bug in Football_Pool_Pool class constructor.

= 2.9.0 =
* New: Added sorting method options for bonus questions.
* Tweak: Updated styling for bonus questions and we now show the admin answer next to the user answer.
* Tweak: Updated the standard styling for the prediction table (classic layout only) a bit to make sure all elements are visible. Still needs to be changed to match your theme's layout.
* Bug fix: Option "user_page_show_predictions_only" did not work for bonus questions ('Undefined index: answer').
* Bug fix: Option "user_page_show_correct_question_answer" did not work for linked bonus questions.
* Bug fix: 'Undefined index: league_id' warning on the ranking page when switching between leagues enabled and leagues disabled without doing a recalculation.
* Bug fix: Fixed the "An active PHP session was detected" warning in the Site Health scan (thanks fimo66 for reporting).
* Bug fix: User predictions table showed a zero in the score column for users that did not have a prediction when option "Always show predictions" is enabled (should be left blank).
* Bug fix: CLI command 'import' failed with an error in test mode when a match id was not found.

= 2.8.1 =
* New: Changed the icons for the link to statistics and joker in the new layout.
* Tweak: Changed the display of the text 'points' to a translated singular or plural form.
* Tweak: New installs will have html5 number inputs enabled by default (new layout has enough room for the spinners).
* Tweak: Changed the images for the flags in the example schedule.
* Bug fix: WP updated its jQuery version which caused an error in the image selection button in the admin (thanks Nico for reporting this).
* Bug fix: New layout for matches missed a link to match statistics when you click on the result (thanks Nico for reporting this).
* Bug fix: Removed duplicate id warning when using date inputs in the admin.

= 2.8.0 =
* New: Database upgrade to support emoji characters (utf8mb4).
* New: Match table layout (flexbox) for better mobile device support. Existing installs can switch to the new layout in the plugin options. New installs will default to this new layout.
* New: CLI command to import match results from the command line: `$ wp football-pool import`.
* Tweak: Refactored some styling and class names. Please check and update your custom styling for matches and questions.
* Tweak: Plugin now saves new matches and questions with the current time if the entered datetime is not valid (instead of a non-valid datetime).
* Tweak: Various small optimizations in the admin.
* Tweak: Changed the base CSS styling of the charts (flexbox layout).

= 2.7.2 =
* Bug fix: Changed visibility of admin functions in v2.7.1 caused problems in some extension plugins.

= 2.7.1 =
* Only matches and bonus questions with play/score dates in the past will be added in the ranking calculation.
* Minimal WordPress version is now 4.7.0 (released December 2016).
* Updated all bundled javascript libs to a newer version.
* Added a filter to the matches admin page to show only matches in a chosen match type.
* Added a search to the bonus questions admin page to search for questions, user answers and user names.
* Added a basic search to other admin pages.
* New shortcode [fp-money-in-the-pot] for calculating and displaying the total stakes.
* Bug fix: Names for teams, stadiums and groups are back in the window title for the respective pages (in WP 4.4.0 the filter changed).
* Bug fix: Doing a calculation in the admin with invalid charts setting (enabled, but without the API) caused an error in the JSON response for the popup.
* Bug fix: Undefined variable in the teams admin page and match type admin page.

= 2.7.0 =
* Added an alternative (simplified) calculation method. This calculation drastically reduces the calculation time needed on installs with large user bases, but with the downside that no historic data is saved. So, charts are not supported with this setting enabled and also the date parameter for ranking and score functions will be ignored.
* EURO2020 Update: included match schedule for the UEFA EURO2020 championship and a new favicon.
* Some small optimizations in the standard calculation method.
* Dropped PHP version 5.2 support. Changed PHP version requirement to PHP 5.3 or higher.
* Removed calls to deprecated (as of PHP 7.2) create_function and now use lambda style anonymous functions instead.
* Added shortcode [fp-user-list] for listing the users in the pool.
* Added do_action for item deletes in admin to be able to hook into this in extension plugins.
* Joker can now be toggled on an off by users in the prediction sheet by clicking on the joker (still needs a save from the user to save final state).
* Added option to also show correct answer from admin with the questions on the User page.
* Added two constants for the match date and time formats: FOOTBALLPOOL_MATCH_DATE_FORMAT and FOOTBALLPOOL_MATCH_DAY_FORMAT. Both can be overridden in the wp-config if you want to change the formats.
* Changed visibility of $page_param parameter in Pagination class. If you use or have a custom pagination extension, please update your code to set the parameter using the set_page_param() method.
* Bug fix: In some MySQL installs the ranking page always returned zeroes for all users in combination with a strict datestring setting in MySQL (thanks @topplayer for pointing out the problem to me).
* Bug fix: Hide admin bar option was not always behaving as intended (thanks Danstano for reporting the problem).
* Bug fix: In some scenarios not all linked questions were shown with the correct match (thanks Daniel for reporting this).
* Bug fix: Fixed link to linked match in main bonus question admin screen.
* Bug fix: When selecting users with the same name on the statistics page, the charts showed weird results. Not sure if this can happen in a real-life scenario, but it happened with my test data ;)
* Bug fix: Ranking did not display users that registered after the last calculation (thanks Patrick for reporting this).
* Bug fix: Missed some translations in the Pagination class (thanks fegs1204 for reporting this).
* Bug fix: Fixed the styling for the pagination buttons on the admin pages.
* Bug fix: Set screen options in the admin was not working (thanks tmg513 for reporting).
* Bug fix: When using invisible match types, the prediction form enabled user's to set a joker. This joker was not saved, but being able to set it, is not supposed to happen (thanks reinispro for reporting).
* Bug fix: 'Undefined index' notice in matches array when using linked questions on a match in an invisible match type.
* Bug fix: The update script for v2.6.1 was not initiated in Football_Pool::activate.

= 2.6.7 =
* Russia 2018 Update: included match schedule for the FIFA 2018 World Cup and a new favicon.
* [fp-ranking] shortcode: added parameter 'user' for league parameter to show the ranking for the league of the logged in user.
* [fp-user-ranking] shortcode: added parameter 'league_rank' to show the rank in the league of the selected user.
* Created SCSS files for the stylesheets.
* Removed Spanish (es_ES) language files as there is a language pack available on http://translate.wordpress.org
* Question date now follows WP display settings for date and time. With an option to overwrite this format with the constant `FOOTBALLPOOL_QUESTIONDATETIME_FORMAT` in the wp-config file.
* Bug fix: Added extra check for default league to the plugin's User admin page (thanks kerovsky for reporting this).
* Bug fix: Added extra check in match import for invalid stadium and match type (thanks Laurent for reporting this).
* Bug fix: Default league image was not displayed correctly.
* Bug fix: Prediction form shortcode only checked for logged in users, not if they are a player in the pool (thanks ARTEFACTOCO for reporting this).
* Bug fix: Team class did not declare variable $page.

= 2.6.6 =
* Added some more formats for the countdown widget and shortcode. Also a free format is supported. See the help page about the shortcode for more info.
* Added filter to match-sorting method so it can be overwritten in an extension plugin.
* Added xssafe to league names in User admin.
* Added extra checks and trim on all values to csv import function.
* Bug fix: Fix for "[] operator not supported for strings" error in certain PHP versions when importing csv files in matches admin (thanks Patrick for reporting).
* Bug fix: Prediction stop date option values (for matches and questions) didn't save after 2.6.5 update (thanks jerofdz for reporting).
* Bug fix: Suppress notice from Statistics page class in some themes.

= 2.6.5 =
* Removed some possible XSS vulnerabilities from the plugin (thank you for your help, Ricardo Sanchez).
* Rewrote the option array definition in the admin because that was a complete mess.
* Upgraded jQuery version in tinymce-dialog.php.
* Some small changes in the tinymce javascript files.
* Changed EOL of tinymce-dialog.functions.php so it is the same as all other files.
* Show item ID for items in default view list in admin screens.
* Added "Chosen" jQuery lib to admin to enhance some select boxes.
* Removed the "ZeroClipboard" lib from timezone-test.php because that uses Flash (blegh) and replaced it with clipboard.js.
* Moved some libs in the assets folder to a new location.
* Added some extra styling on the "prediction saved" notification because this is hidden in some themes.
* Added 'use_querystring' parameter to the fp-predictions and fp-scores shortcode so they can be used on a page where parameters are passed via the querystring (and thus making these shortcodes more usable in a dynamic context).
* Time and date formats in plugin used WordPress settings, but because I didn't use the WP function date_i18n() on the matches page, any other extension that hooked into this function (e.g. wpjalali) couldn't change the formats (thank you R.Ahmadi for notifying me and for testing).

= 2.6.4 =
* Added a file extension check to the csv upload in the admin (only csv and txt files are allowed).

= 2.6.3 =
* Fixed some bugs in the TinyMCE plugin and split the dialog file into JS, CSS and functions.php.

= 2.6.2 =
* Bug fix: Updated the TinyMCE plugin (for adding shortcodes to the editor). Old one gave problems in recent WP versions.
* Bug fix: In installs where the translation for "Save Changes" in the plugin's translation files is different from the global WordPress translation file, the saving in the User admin did not work (thanks Soroush for reporting).
* Bug fix: Added cache clear to Leagues and User Defined Ranking admin after save or delete (thanks Stefan for reporting).
* Bug fix: Dashboard widget image was stored with a full URL on activation. If the scheme is switched after activation this image will cause a mixed content (insecure image) warning (thanks Stefan for reporting).
* Bug fix: Removed the incomplete 'show number of predictions' feature from the ranking shortcode (thanks Chris for reporting).

= 2.6.1 =
* Removed Dutch language files as there is a language pack available on http://translate.wordpress.org
* Added extra filters to the score calculation class for extra flexibility.
* Added filters in the User admin to be able to extend the user view.
* Changed database field length for match type name, league name, ranking name, stadium name and group name to 100 chars.
* Changed the match predictions view to work with templates so it can be changed via hooks.
* Bug fix: Undefined index warnings on the user page when match types are hidden (thanks rick for reporting).
* Bug fix: Set screen options in the admin contained a bug in the hook callback (thanks tmg513 for reporting).
* Bug fix: "undefined variable: default_league" notice when saving a user in the admin with leagues off.
* Bug fix: Fixed a bug in the max points calculation in the charts.
* Bug fix: Undefined index 'last score' in ranking when there are no results in the ranking.
* Bug fix: The html for the calculation box caused some display trouble when bootstrap CSS is also loaded. Changed the class names and ID for the progress bar elements (thanks Dennis for reporting).

= 2.6.0 =
* New: WP-CLI support for score calculation. See help page in the admin for more information.
* New: HTML templates for the group table. The templates can be changed via hooks. See help for details.
* New: shortcode [fp-scores] for displaying the scores for every user for one or more matches.
* New: new options for the user page (only show matches with predictions, only show finished matches and show actual results below prediction).
* Updated fp-league-info shortcode so it now also displays data when the pool hasn't started yet (there is no data in the score table).
* Added user ID to calc_score so it can be used in the filters.
* List email addresses in admin now takes search filter into account when displaying the addresses.
* If FOOTBALLPOOL_ALL_WP_USERS constant is defined in wp-config the plugin will show names of users that are no (longer a) valid user in the plugin (e.g. when displaying a name in the shoutbox) instead of 'unknown'.
* Updated some translations.
* Changed visibility of methods in the charts class to be able to extend this class in extension plugins.
* Updated the bulk matches upload code with some extra checks on the csv file.
* Bug fix: bulk matches upload page exited with an error notice if one of the folders didn't exist (thanks Kadri-Ann for reporting the bug).
* Bug fix: countdown widget and countdown shortcode gave an error when texts were used with single quotes in them (thanks florent71 for reporting the bug).
* Bug fix: ranking didn't show 'currentuser' CSS class for logged on user (thanks saschapi for reporting the bug).
* Bug fix: changing the constant FOOTBALLPOOL_CSV_DELIMITER caused a problem in the column count of the import function.

= 2.5.4 =
* Updated German translation (thanks aileesh).
* Updated English schedule because it contained a small error in the final (thanks benster1970 for reporting this).
* Prepare plugin for new plugin translation system (http://translate.wordpress.org).
* Added match ID to calc_score function so it can be used in the filters.
* Bug fix: On the stadium page the "Matches" title was printed below the matches.

= 2.5.3 =
* __Important:__ Location of team images, stadiums and flags has changed in since version 2.5.2. Backup your files before updating.
* Updated schedules as these were not in UTC time.
* Added filters for the chart data. When you change the score calculation you now also have the ability to update the charts to match your changes.
* Bug fix: ranking widget and shortcode didn't use the max users setting (thanks Gerrit for reporting the bug).

= 2.5.2 =
* __Important:__ Moved location of team images, stadiums and flags outside of plugin dir so your own images won't be overwritten on future updates. Plugin creates a `football-pool` dir in the WP upload dir for all your files.
* Added select from media lib for the league image.
* Newly added users to your pool are now immediately included in the ranking even though you didn't do a calculation yet.
* I disabled the loading of avatars because these can slow down the ranking display tremendously (WP runs a couple of database queries for every user to get the avatar). If you extended the plugin to show the avatars in your ranking, you can enable the loading again by setting the FOOTBALLPOOL_NO_AVATAR constant to `false` in your `wp-config.php`. Note: my setting only affects the plugin, not the rest of your blog.
* Added an upgrade warning in the WP plugin upgrade screen (for future upgrades).
* Added ability to cancel the calculation process. Old ranking will stay active if a calculation is cancelled.
* Some code cleanup in the admin.js file.
* Removed some default styling from the pool.css file.
* Removed some old match schedules.
* Removed some unused images from the default install.
* Updated the favicons (logo for the UEFA Championship 2016) and placed them in a different location.

= 2.5.1 =
* Fixed unknown constant notice.
* Bug fix: When no calculation is needed, no message was shown (thanks Reinis for reporting the bug).
* Bug fix: When a calculation is completed with no matches or questions, the session was not cleared correctly. And any calculation that was performed after this, stayed in the same 'no calc' state (thanks Reinis for reporting the bug).

= 2.5.0 =
* Complete rewrite of the score calculation. The calculation is split into smaller steps and stores intermediate results and data in a session. There is less chance of time outs during the calculation and it's easier to tweak the individual calculation step sizes. New calculation version allows you to easily overwrite the default scoring for matches with the hooks 'footballpool_score_calc_function_pre' and 'footballpool_score_calc_function_post'. In previous versions the ranking table displayed incorrect data when a calculation was in progress; this is fixed now. Only full calculations are possible in the new version (single ranking calculations and smart calculations no longer exist).
* Added pagination to bonusquestion user answers admin page because this was causing trouble on installs with a lot of users.
* Added user search in the user admin page.
* Added option to change the redirect_to parameter for the registration page.
* New shortcode [fp-next-matches].
* Changed some add_query_arg/remove_query_arg calls (<a href="https://make.wordpress.org/plugins/2015/04/20/fixing-add_query_arg-and-remove_query_arg-usage/">more info</a>).
* Changed the name of the content filter constant to `FOOTBALLPOOL_CONTENT_FILTER_PRIORITY`.
* Removed the deprecated shortcodes and corresponding option values.
* Bug fix: On some WP installations the activation quit with a fatal error on the add_cap() function. This happens if one of the default roles cannot be found (thanks David Anderson for reporting this bug).
* Bug fix: Removed obsolete closing div from statistics page (thanks WilRC for reporting this bug).
* Bug fix: Cog icon for selecting users on statistics page didn't work in themes that add an anchor on the page title (thanks pekos for reporting this bug).

= 2.4.4 =
* Bug fix: Undefined variable match in last games widget.

= 2.4.3 =
* Added 'auto set' function to bonus questions. This makes it easier for admins when rewarding points for multiple choice questions with a fixed set of answers a user can choose from.
* Added a WordPress filter that adds the team name, stadium name or group name to the corresponding page's title tag.
* Added some more filters in the code.
* Added weighted average points as an option for the [fp-league-info] shortcode.
* Updated Dutch translation.
* Added Swedish translation.
* Added Hungarian translation.
* Added Brazilian Portuguese translation.
* Bug fix: Shoutbox date and time were not localized (thanks to wendihihihi for reporting this).
* Bug fix: When all bonus questions are linked to a match, the pool page still showed the title for a question form beneath the matches form.
* Bug fix: Answers to linked bonus questions were shown in the prediction overview for users even when the question is still editable. This is unwanted behaviour. (thanks to maybor for reporting the bug).
* Bug fix: Countdown to next match widget displayed wrong match when a team is specified (thanks to Pierre Suter for reporting the bug).
* Bug fix: Stats page showed pie chart for bonus questions where score_date was not set.
* Bug fix: Countdown widget didn't show the next match when "Date descending" was chosen as the match sorting option (thanks to korry for reporting the bug).

= 2.4.2 =
* Some themes don't show the cog icon for the chart settings in the title of the page. Added shortcode [fp-chart-settings] that can be used to display the cog icon somewhere in the text. The shortcode only works for the statistics page.
* Added `FOOTBALLPOOL_CHANGE_STATS_TITLE` constant that can be set to `false` in the wp-config file to disable the cog icon in the page title (in case something goes wrong in your theme).
* Removed 'show avatar' option. I'm in the midst of changing some parts of the plugin to use HTML templates for the display of data. The avatar can already be added to the ranking table (see help page for details); other parts of the plugin will follow later.
* Moved the plugin screenshots from the plugin's zip to the svn assets folder (they're only needed for the wordpress.org site).
* Bug fix: matches disappeared when using a match sorting method that included the match type (thanks Kevin for reporting the problem and allowing me to do some bug tracking on your site).
* Bug fix: shortcode pop-up in the WP admin always included a group ID for the [fp-matches] shortcode.
* Bug fix: calculation of number of predictions went wrong for custom rankings with only bonus questions (thanks Daniel for reporting the bug).
* Bug fix: undefined index 'league_id' on the ranking page (thanks sillery4ever for reporting the bug).
* Bug fix: match schedule was wrong for the quarter finals. The matches were imported sorted on date causing the match numbers to not match correctly for the semi-finals (e.g. winner match 57). (thanks Bobby Groenen for reporting the bug).
* Bug fix: get_page_link() caused a notice when plugin pages are deleted from the database.
* Bug fix: typo in match template; match ID and form ID weren't replaced with the params.

= 2.4.1 =
* Changed option: redirect after login option can now be left empty to use the default WP behavior (go to profile page).
* Statistics page now shows the top 5 players to visitors that are not logged in (and if no user is selected in the chart settings).
* Bug fix: new feature pointer for recalc was also shown to non-admins.
* Bug fix: user predictions were shown before the match stop time had passed (thanks latinosamorir for reporting the bug).

= 2.4.0 =
* **Important!** Changes were made in the scorehistory table. If you're upgrading from a previous version please do a full calculation after the upgrade.
* New: HTML templates for the matches table, prediction form or ranking table. The templates can be changed via hooks. See help for details.
* Changed default width of matches table to 100% so it works better on responsive themes.
* Changed charts to a 100% width and made them responsive. If you want to change the width of the charts to a fixed width, you can do so in your theme CSS.
* New bonus question types: multiline text and dropdown.
* New option: users (not admins) will be redirected to a configurable page after registration (defaults to homepage).
* New option: joker multiplier can now be changed in the options.
* New option: (re)set the pages installed by the plugin (e.g. the matches page).
* New shortcode: display info from a league with [fp-league-info].
* Changed shortcode: [fp-predictionform] will no longer display a form when the visitor is not logged in.
* Removed the userselector widget and placed the functionality on the charts page. Old selector wasn't working well for mobile devices, where in a lot of themes the widget zones are placed at the bottom of the page.
* Removed `user_label` functionality (the meta key is still in the database if you want to use it). User name display can now be altered via the `footballpool_user_info_display_name` filter.
* Removed 'number of predictions' as an option for the ranking table. This functionality is now available as a template parameter in the new template structure. See the help page under the Actions and Filters section if you want the number of predictions back.
* Added option to custom rankings to exclude them from a recalculation.
* Reduced the number of queries on the frontend when linked questions are used.
* New favicon and touch icons in the 2014 World Cup style.
* Restructured javascript code.
* Added prediction log that logs all prediction changes by users to a table (accessible via a database tool).
* Fixed strict warnings caused by calling non-static functions statically.
* Bug fix: score calculations went wrong for bonus questions when not using the leagues options (thanks sillery4ever for reporting the bug).
* Bug fix: ranking selector did not work in WordPress installs with default permalink setting (thanks sillery4ever for reporting the bug).
* Bug fix: save of user answers in the bonus question admin gave an error on PHP 5.2 installs (thanks sillery4ever for reporting the bug).
* Bug fix: multiple ranking widgets always showed the same ranking (thanks oswaldine for reporting the bug).
* Bug fix: [fp-user-score] sometimes returned an incorrect score (thanks sillery4ever for reporting the bug).
* Bug fix: shortcode insert in editor failed in WP 3.9 (WP 3.9 uses a new tinyMCE version).
* Bug fix: shoutbox admin threw a warning on the start screen.
* Bug fix: if jokers are disabled then jokers that were already set, are still counted in the scoring.

= 2.3.8 =
* Bug fix: the score calculation contained a bug for installs with a big gap in the user ID's. Thanks Sergio for reporting the bug and helping me with the debug info.

= 2.3.7 =
* Small styling updates for WordPress 3.8.
* Added the match schedule for the 2014 World Cup in Brazil.
* New shortcode: display the ranking of a single user with [fp-user-ranking].
* New parameter 'group' for the [fp-matches] shortcode.
* Several hooks (filters and actions) that make the plugin extensible. See help page for details.
* Import CSV & Overwrite will now exit with an error when the file is invalid. The data will not be erased.
* Import CSV & Overwrite will ask for an extra confirmation.
* Bug fix: undefined index 'ranking' on the ranking page.

= 2.3.5 =
* Bug fix: matches admin added timezone offset on every save (thanks BruceFeuillette for reporting the bug).

= 2.3.4 =
* Bug fix: in some setups the division of the score calculation in multiple sub-steps caused the scores to be multiplied by the number of sub-steps (e.g. 2 or 3). Thanks Fares and Bart for reporting the bug and helping me with the debug info.
* Bug fix: tinymce button added the wrong code for the [fp-predictions] shortcode to the text editor (thanks pjbursnall for reporting the bug).

= 2.3.3 =
* Bug fix: internal server error (bad header) in score calculation AJAX call (thanks Josh and sindris for reporting the bug and giving me the information from the error log).
* Bug fix: removing users as a player resulted in an undefined function error (thanks Josh for reporting the bug).

= 2.3.1 =
* Bug fix: plugin broke the "featured image" function of WordPress (thanks CornelB for reporting the bug).
* Option to keep data on uninstall is now enabled by default.

= 2.3.0 =
* Updated score calculation: better support for a large user base and moved the calculation to a modal pop-up with AJAX handling (with fallback to 'normal' calculation).
* Added pagination to the user admin page (default is 20 per page; because of a bug in WP 3.6 and below it is not possible to change it in the screen options tab, so change it in the define.php file).
* Added pagination to the matches admin page (default is 50 per page; because of a bug in WP 3.6 and below it is not possible to change it in the screen options tab, so change it in the define.php file).
* New feature: link a question to a match. Linked questions are displayed beneath the match on the prediction form.
* New scoring option: goal difference bonus.
* New shortcode: display the score of a single user with [fp-user-score].
* New shortcode: display the predictions for a match or question with [fp-predictions].
* New shortcode: display a table of matches with [fp-matches].
* Some additions to the custom rankings admin screen.
* New option: disable jokers.
* New options for points a team gets for a win or draw (for the Groups page). This makes the plugin more suitable for sports that don't use the 3-point rule for wins.
* New option: show team photos on team listing page.
* New option: show venue photos on venue listing page.
* New option: choose if plugin must keep all data on uninstall.
* New option: show number of predictions per user in the ranking (match predictions and bonus question anwers are counted); this can be set for the ranking page and for the ranking widget and shortcode.
* New sorting option for matches to be able to include match types in the sorting.
* Widget "Next prediction countdown" has a new option to countdown to the next match of a particular team.
* Changed image selection to WordPress 3.5 Media Uploader for WordPress version 3.5+.
* Added contextual help to admin screens. Moved informative text on admin pages to these help tabs (needs WordPress 3.3 or higher).
* Added image for wrong answers to prediction table for questions.
* Changed database table structure so naming convention is the same for all tables.
* Minified the javascript files.
* Bug fix: old values were shown after a save of a match or a question in the admin (cache is now flushed after a save).
* Bug fix: the 'dynamic stop time' check did not work as it should, causing a prediction for a match not being saved to the database even though the match was still editable in the prediction form (thanks full1restart719 and BruceFeuillette for reporting the bug helping me with fixing the bug).
* Bug fix: prediction form shortcode did not update values when used in a post (thanks BruceFeuillette for reporting the bug).
* Bug fix: user selector widget did not work in WordPress installs with default permalink setting (thanks Tomas Jonsson to for reporting this).
* Bug fix: ranking selector did not work in WordPress installs with default permalink setting (related to bug in user selector widget).
* Bug fix: pie charts where not updated correctly for user defined rankings once such a ranking was selected on the charts page.
* Bug fix: custom date field in the shortcode tinymce popup was behaving strange.
* Bug fix: not all labels in group widget and group standing page were translated.
* New translation: Albanian (sq) by Migen Nepravishta.
* New translation: Danish (da_DK) by Morten Bilberg Rasmussen.
* New translation: German (de_DE) by Frank Winter.

= 2.2.5 =
* Bug fix: CSV upload in matches admin was not working (thanks BruceFeuillette for reporting the bug).

= 2.2.4 =
* Bug fix: removed a non-working option (prediction type) from the plugin option screen (thanks Matías for sending the screenshot). The prediction type will probably be in version 2.3.0.

= 2.2.3 =
* Bug fix: on pools with no bonus questions the User defined ranking admin gave an error (thanks Guzz Windsor for reporting this).

= 2.2.2 =
* Bug fix: WP nonce not set on delete and edit link in matches admin screen (thanks ipixelestudio for reporting this).

= 2.2.1 =
* DateTime::getTimestamp requires PHP 5.3 or higher. I replaced those calls with code that doesn't break on PHP version 5.2 (thanks to chiribombi for reporting this).

= 2.2.0 =
* Important: styling of the widgets has changed. They contained styles that could conflict with the styling of other widgets.
* Some minor changes in the security model: WordPress editors can now also manage the plugin and there is a new role "Football Pool Admin" with only rights to the plugin's admin screens.
* New feature: user defined rankings (ranking for a selected group of matches and/or questions).
* New feature: plugin option to always show predictions of other players. Regardless of the fact if matches are editable for your contenders.
* New shortcode to support the user defined rankings.
* New option to let users choose between different rankings on the charts page or ranking page.
* New shortcode to show prediction form for a group of matches and/or bonus questions.
* New feature: ability to use result of matches in multiple match types as data for the Groups page. This option can be set in the plugin options screen (thanks to Eli for reporting this).
* New feature: option to show user's avatar in the ranking tables.
* New option: choose sorting method of matches (date ascending or date descending).
* Added French translation (thanks Julien Blancher).
* All dates in the front-end are localized using date_i18n() and WordPress' Time Format setting.
* Clean up: all plugin options are stored in a single array in the wp_options table.
* Bug fix: if charts were disabled the plugin could break other plugins that use javascript (thanks AndresCZ).
* Bug fix: description text of widgets was not correct in the WordPress admin.
* Bug fix: when using the plugin in a different language the 'Save & Close' buttons did not work correctly in the admin.
* Bug fix: stadium names with special chars (like Ã) did not work correctly in PHP version below 5.4 (thanks angelpubli).
* Bug fix: when adding teams the new team was not displayed in the list. You had to reload the page.
* Bug fix: it was possible to add a match without a match type, venue or team. These 'orphaned' matches were saved in the database, but not shown.
* Bug fix: adding a new bonus question caused a warning for the first question.
* Bug fix: a user could use a trick to set multiple jokers. This was fixed.

= 2.1.3 =
* Added an extra warning for the administrator of the blog in the Plugins and Updates pages in the WordPress admin when the plugin has the charts enabled but the Highcharts API is missing.

= 2.1.2 =
* Bug fix: upgrading the plugin also deletes the Highcharts API. To make sure the front-end does not quit working, a small change was made to the init code. Also, the plugin now expects the API to be located outside the plugin's directory: `wp-content/plugins/highcharts-js/`.

= 2.1.1 =
* Small updates for WordPress 3.5.
* Added a button to the WordPress visual editor for an easy way of adding the plugin's shortcodes to your pages and posts.
* Line charts now show team names for a match in the tooltip of a data point.
* Prefixed all shortcodes with "fp-" (the old ones that didn't have this prefix).
* Bug fix: ordinal numbers in the 'position in the pool' chart were gone.
* Bug fix: the CSV importer caused an 'unknown index' notice for the new comments field for venues.

= 2.1.0 =
* Added an extra scoring option: bonus points for guessing one of the goals for home team or away team correct.
* Added culture selection to csv file list (on import screen) and support for meta information in the csv file.
* Added extra label that can be added to a username via the User Admin screen. Usable for an extra status (e.g. "winner 2012") or to show cumulative scores from other years (e.g. "1057 points"). The label (if not empty) is added behind a user's name on the ranking page and in the list generated by the ranking shortcode. A CSS class that can be styled to your liking is added to the label.
* Added possibility to hide match types from the website. Useful for competitions with large amounts of matches where you don't want to show every match all the time. Invisible match types are not shown on the website (matches page and prediction page) and in the admin for matches, but are still calculated for the scores.
* Added extra info fields for venues and teams. The info is displayed on the team or venue page.
* Added Spanish translation (thanks Cristian Carlucci).
* Bug fixed: teams that are 'real' were accidentally shown in a dropdown on the Matches admin screen.
* Bug fixed: matches that have an empty venue caused a notice in the import.
* bug fixed: countdown shortcode was not fixed for the new UTC match times.
* Bug fixed: UTC times that passed the end of the day (0:00h) caused matches to be displayed on the wrong day in the matches overview (thanks Cristian).
* Bug fixed: match times in the schedules that ship with the plugin were not UTC.

= 2.0.1 =
* Bug fixed: teams that are not in a group caused a notice in the import.
* Hint added on the matches admin screen.

= 2.0.0 =
* Added or changed admin screens for teams, groups, match types and matches so they can be easily manipulated. This way the plugin is not limited to the European championship, but can also be used for other competitions, e.g. the English Premier League.
* Changed default language to English. This makes the plugin easier to translate.
* Changed layout of the Plugin Option admin screen. More options and better grouped.
* Fixed a problem for DB users that don't have `TRUNCATE` rights. (thanks Millvi)
* Plugin now supports WordPress installs with default permalinks settings.
* Multiple choice questions with more than one answer (checkboxes) can now have a max number of answers a user may give.
* Bug fixed: prediction page for a user did not show the right points for a bonus question. (thanks Maly77)
* Bug fixed: User Selector Widget caused a notice on 404 pages.
* Added colorbox instead of fancybox for lightbox. Fancybox does not have a GPL-compatible license.

= 1.4.5 =
* Removed Highcharts library from the package on request from WordPress. The Highcharts library has a non-GPL-compatible license which violates WordPress plugin rules. If you want to keep using the charts you have to download the library yourself and enable the charts feature in the plugin option screen.

= 1.4.4 =
* New: list of email addresses of players in your pool available in the plugin User Admin screen. You can copy and paste it in an email to quickly mail your users (without the need of installing a mail plugin).
* New: if using leagues the ranking page now defaults to the league the user is in.
* Bug fixed: check if user is a player in the pool did not work correct for users that are added to the blog, but are not in a league.
* Refactored code for the widgets.

= 1.4.3 =
* Fixed a potential problem with magic quotes (wp_magic_quotes adds slashes regardless of your PHP setup).
* Performance update. Reduced number of database queries for a page request.
* New widget (beta): countdown to next match.
* Extra options for countdown shortcode (see help page for details).

= 1.4.2 =
* DateTime::createFromFormat requires PHP 5.3 or higher. I replaced those calls in the core classes with code that doesn't break on PHP version 5.2.
* New version of the Fancybox javascript library (2.0.6).
* New version of the Highcharts javascript library (2.2.4).
* Added two more layout options to the plugin options (favicon and apple-touch-icon).
* Moved body font-styling from the global stylesheet to the theme skin.

= 1.4.1 =
* Some texts were lost in translation. I added them.
* New configuration options for a single 'lock time' for matches and bonus questions. If set, users have to finish all their predictions before this date and time.
* Plugin supports a maximum number of answers a user may select in a multiple choice question (checkbox). The check is only done client-side and requires a bit of javascript knowledge to use it. See top of pool.js file for usage. I recommend adding the javascript calls in a separate file, add them to your theme or use a plugin that helps adding custom javascript to your blog. (thanks srozemuller)
* Added Polish translation. (thanks Łukasz Ciastoń)

= 1.4 =
* Translations (i18n) are working. Plugin contains en_GB translation for my Dutch version of the pool and a pot-file for users that want to make their own translation. See the <a href="http://wordpress.org/extend/plugins/football-pool/faq/">FAQ</a> for more information. (thanks dcollis)
* Bonus questions and user answers can now contain more than 200 characters.
* Style updates.

= 1.3.3 =
* Removed custom fields for admin in the standard WordPress user profile. Editing of users can be done in the plugin user screen.
* Minor style updates.
* Bug fixed: updating users via the WordPress User screen put them in the wrong league (when using leagues).
* Bug fixed: new custom table for the question types was not prefixed properly; updated the install-script. (thanks sjonas)

= 1.3.2 =
* New widget on the WordPress Admin Dashboard: a quick link to the pool. Change the picture for the widget in the Plugin Options.
* Some reordering of files and minor style updates.
* Bug fixed: deactivation of the plugin did not remove all custom tables. (thanks sjonas)

= 1.3.1 =
* Bug fixed: new users were not added to the default league set in the plugin options.
* Bug fixed: adding multiple users to the pool in the new admin screen did not work when users were removed from the pool with league support off.

= 1.3 =
* New admin screen for Users. Add or remove them from the pool or change leagues of the players in one screen.
* Added support for multiple choice questions (very basic).
* Added support for photo questions (ask a question about an image).
* New shortcode [fp-register] for including a link to the WordPress register screen in a post or page. See help page for more information.
* New version of the Highcharts javascript library (2.2.3).
* Bug fixed: shortcode [countdown] used UTC+0 time not the CET time of the match which I use everywhere else in the pool. (thanks drsp58)

= 1.2 =
* New shortcode [fp-ranking] in case you don't want to use the ranking page or widget, but only want to display the ranking in a post. For complete help on this and other shortcodes, see the new help page in the admin section.
* New shortcode [fp-group] if you want to include the standing of a group in a page or post.
* New Group Widget that displays the standing for teams in the Group Stage in a sidebar.
* New version of the Highcharts javascript library (2.2.2).
* Added a helpscreen to the admin.
* Bug fixed: when not using leagues the plugin did not properly show WordPress users as players in the pool.

= 1.1.5 =
* Bug fixed: playDate index not found on the teams page.

= 1.1.4 =
* New version of Highcharts javascript library (2.2.1). Did a small (cosmetic) change in the line charts.
* Ranking page and ranking widget now show all users. Even the ones that registered for the pool after the first match was played. In previous versions the new user had to wait for an admin to save a match or bonusquestion to recalculate the points table.
* Bug fixed: a timezone problem in the display of match times. (Thanks Okoth1)
* Bug fixed: the admin screen for bonusquestions not displaying user answers.
* Bug fixed: user selector widget showed all blog users.

= 1.1.3 =
* Fix for the problem that - on a clean install - the default data for the pool was not loaded (matches, teams, etc.) in the database. (Thanks Okoth1)

= 1.1.2 =
* Fixed a bug in the shoutbox admin (`unexpected T_PAAMAYIM_NEKUDOTAYIM`).

= 1.1.1 =
* Added pot/po/mo files with the new texts from the shoutbox widget.

= 1.1.0 =
* Added a shoutbox widget for players in the pool. So they can leave short messages in a sidebar.
* Prefixed all class names.
* Fixed a bug with the bulk actions in the admin.

= 1.0.1 =
* Removed English texts because I couldn't get gettext to work for my Dutch version. All texts are in Dutch now. If someone wants to translate the plugin, please contact me. I can give you a po-file with Dutch->English translations,

= 1.0.0 =
* First release of the plugin version of the pool
