=== Title Experiments Free ===
Contributors: jasonlfunk, seokai, kbowson
Tags: conversions, ab testing, optimization, headlines, split testing, titles
Requires at least: 3.9
Tested up to: 5.9.3
Requires PHP: 7.0
Stable tag: 9.0.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Split (A/B) test multiple titles for a post and discover which gets more page views. Great way to increase click through rates.

== Description ==

## TITLE EXPERIMENTS WILL NOT SUPPORTED IN WORDPRESS 5.0+ 

#### Most people decide to read your article based solely on it's title.
Are your titles getting in your way?

Test your titles and discover what your readers find interesting. Get more clicks. Get more revenue.

Use the process of A/B Testing to try different variations of your post titles to get a higher click through rate. It's very simple to add multiple versions of an article title that will be randomly displayed in any post list. Each time the article is listed it is counted as an impression. Each time the article is visited it is counted as a view. This will easily let you see what percentage of impressions get clicked on for each title.

For more information, check out https://wpexperiments.com/title-experiments/.

== Installation ==

1. No Special Installation Instructions

== Frequently Asked Questions ==

Check out https://wpexperiments.com/title-experiments/

== Screenshots ==

1. Add multiple titles and see how many people view the article with that title.


== Changelog ==
= 9.0.4 =
* Removed file causing 404 errors

= 9.0.3 =
* Squashed bugs

= 9.0.2 =
* Squashed bugs

= 9.0.1 =
* Squashed bugs

= 9.0 =
* Move titles to a metabox so that everything will still work with Gutenberg

= 8.9.2 =
* Include the title in the ajax placeholder

= 8.9 =
* PHP 7 compatibility updates.

= 8.8 =
* Add titleex_enable() and titlex_disable() functions so themes and plugins can enable and disable the experiments on demand.

= 8.7 =
* Add code to assist in debugging

= 8.6 =
* Fix a problem with featured images

= 8.5 =
* Fix permalink appearing for new posts before saving

= 8.4 =
* Update the <title> meta tag with the correct post title

= 8.3 =
* Fix mobile post list page view
* Added an option to send search engines through the experiments too
* UI updates on the post page

= 8.2 =
* Remove PHP notice in logs

= 8.1 =
* Bump the script enqueue version to sure the newest files are included

= 8.0 =
* Add setting to hide the webpage until titles are loaded

= 7.6 =
* Include jQuery.cookie as dependency
* Bug fixes

= 7.6 =
* Bug fix

= 7.3 =
* Add icon
* Update the 'Tested up to'

= 7.2 =
= 7.1 =
= 7.0 =
* Bug fix for probabilities displaying over 100%

= 6.8 =
* Set default database table collation when installing

= 6.6 =
* Bug fix
* Add AJAX loading capabilities

= 6.5 =
* Add 'clear statistics' link on the settings page
* Require 'manage_options' capability to access the settings page

= 6.4 =
* Fix bug where titles are getting cleared when the 'Preview' button is being clicked

= 6.3 =
* Bug fix

= 6.2 =
* Add a recalculate "never" option that does straight even distribution of titles

= 6.1 =
* Support for new Title Experiments Pro features

= 6.0 =
* Update the save_post method to be more efficient

= 5.9 =
* Add Facebook/Twitter to list of robots

= 5.8 =
* Fix DB schema which was causing new installations to fail

= 5.1 - 5.7 =
* Performance enhancements

= 5.0 =
* Add an 'ignore logged in users' option

= 4.9 =
* Bug fix

= 4.8 =
* Add a 'skip pages' option

= 4.7 =
* Added setting to control which title search engines see

= 4.6 =
* Bug fixes

= 4.3 =
* Bug fixes

= 3.9, 4.0, 4.1 =
* Embed wp-session-manager

= 3.8 =
* Try to start a session sooner

= 3.6 =
* Bug fix

= 3.5 =
* Show new probabilities when titles load

= 3.4 =
* Bug fix

= 3.3 =
* Make sure the dashboard shows the cached probabilities

= 3.2 =
* Make titles re-generate probabiltiy only so often by default and not every request.

= 3.0 =
* Fix rss feed generation and ajax call

= 2.8 =
* Add javascript cache avoidance

= 2.5 =
* Code cleanup

= 1.6 =
* Fix bug causing fatal error

= 1.4 =
* More advanced propability engine
* No more "Winners" and "Losers" only a weighted distribution based on performance

= 1.3 =
* Strip slashes in titles

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.0 =
Initial realse.