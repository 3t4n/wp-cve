=== Yelp Reviews Ticker ===
Contributors: flaviodj
Donate link: 
Tags: yelp, reviews, yelp api, yelp business listings, yelp reviews, yelp widget, yelp widget
Requires at least: 3.3
Tested up to: 3.8.1
Stable tag: 2.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Yelp Reviews Ticker is an easy to use widget that allows you to show your business yelp reviews.

== Description ==

This widget will allow you to show a list of up to 3 reviews snippets/excerpts (via Yelp API) for your business in a customizable ticker display that is yelp compliant.

The ticker will show the user profile image, user name, the rating given by the user (1-5 stars), the excerpt of the user review, a link to your business yelp page, the date the review was given and the required yelp logo.

It allows you to easily configure the widget title (if you'd like one), the speed in which the ticker will rotate the reviews, the amount of time each review will be displayed for and the number of reviews it will show at once.

== Installation ==

1. Upload yelprt to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Place Yelp Review Ticker Widget in a Widget Area.
4. Set up the display options according to your likings.
5. Add your yelp business phone number and the Yelp API v1.0 key (AKA YWSID).
6. Don't forget to rate if weather like or dislike it!.

= Notes: =
If you don't already have a Yelp API key it is super easy to get it:

1. Sign up for a Yelp user account at http://www.yelp.com/signup if you don't have one yet.
2. Login, then register for an API key and have instant access.
http://www.yelp.com/developers/getting_started/api_access .

== Frequently asked questions ==

= Is it easy to setup? =

Yes, super easy, all you need is a Yelp account, sign-up for a Yelp API key (http://www.yelp.com/developers/getting_started/api_access), and have your business listed on Yelp.

= Where do I find the API Key? =

It's easy, login to your yelp account and go to this link http://www.yelp.com/developers/getting_started/api_access there you will "apply" for the Yelp API access and  give you all the keys you need. The whole process takes about 5 minutes.

= Why do I need my own Yelp API keys? Why can't you just provide me with one? =

This widget works with the Yelp API and each site developer must have their own. If I were to provide you with mine I'd be breaking Yelp's API rules and terms.

= Why can I only display up to 3 reviews? I want to display all of my reviews... =

Sorry that is not up to me. The API and YELP's terms only allow the latest 3 exerpts.

= My reviews are not showing in full, It only displays exerpts =

Again Yelp will allow only exerpts not the full review, but don't worry a link to the full review is posted right below it :)

== Screenshots ==

1. This is how it will display reviews in your site
2. The settings page

== Changelog ==
= 2.1 =
* Major code clean up
* Major security measures takes
* Small bug fixes
* More code markups
* Code compliance (according to codex) check
* Now displays errors (due to wrong phone number or API Key being incorrect)
* Better handling of instances

= 2.0 =
* Fixed issues with API (went from v2 back to v1)

= 1.2 =
* Fixed plugin not displaying reviews at all (broken for some since ver 1.0.1)
* Fixed css tables
* Fixed jQuery heigh match
* Code improvements

= 1.1 =
* Fixed bug with css not setting tables
* Added yelp sprites
* Dark background issue with stars solved
* Self hosted images
* Small code improvements

= 1.0.3 =
* Fixed bug with value being set to "0" and not displaying correctly in the admin interface 

= 1.0.2 =
* Minor bug fixes with instances

= 1.0.1 =
* Fixed issue with instance display (only one instance would work, now all work :)

= 1.0 =
* Several changes on "display" to comply with Yelp's API rules and conditions
* Minor cosmetic fixes

= 0.5.2 =
* Changed Classes in the lib/OAuth.php and yrt.php files to prevent conflict with other plugins using same classes
* Bugs fixes

= 0.5.1 =
* Changed the "# of reviews" from field to radio, since only 1, 2 or 3 could be selected.
* Major change on css and tables for a better cross browser experience.

= 0.5 =
* Initial version

== Upgrade notice ==

* This is a major code upgrade, PLEASE CHECK YOUR SETTINGS AFTER YOU UPGRADE, due to YELP's recent changes I had to rewrite most of the code and change API from v2 to v1.