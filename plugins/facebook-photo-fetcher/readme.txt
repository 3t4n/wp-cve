=== Social Photo Fetcher ===
Contributors: Justin_K
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=L32NVEXQWYN8A
Tags: facebook, photos, images, pictures, gallery, albums, fotobook, media
Requires at least: 2.5
Tested up to: 6.3
Stable tag: 3.0.4

Allows you to automatically create Wordpress photo galleries from Facebook albums.  Simple to use and highly customizable.

== Description ==

Social Photo Fetcher (previously called "Facebook Photo Fetcher") allows you to quickly and easily generate Wordpress photo galleries from Facebook albums.

The idea was inspired by [Fotobook](http://wordpress.org/extend/plugins/fotobook/), though its approach is fundamentally different: while Fotobook's emphasis is on automation, this plugin allows a great deal of customization.  With it you can create galleries in any Post or Page you like, right alongside your regular content. You do this simply by putting a "magic HTML tag" in the post's content - much like [Wordpress Shortcode](http://codex.wordpress.org/Gallery_Shortcode). Upon saving, the tag will instantly be populated with the Facebook album content. Presentation is fully customizable via parameters to the "magic tag" - you can choose to show only a subset of an album's photos, change the number of photos per column, show photo captions, and more.  Plus, Social Photo Fetcher doesn't limit you to just your own albums: it can create galleries from fanpages as well.

Features:

* Uses Facebook's API to instantly create Wordpress photo galleries from Facebook albums.
* Galleries are fully customizable: you can import complete albums, select excerpts, random excerpts, album descriptions, photo captions, and more.
* Galleries can be organized however you like: in any post or page, alone or alongside your other content.
* Simple PHP template function allows programmers to manually embed albums in any template or widget.
* Built-in LightBox: Photos appear in attractive pop-up overlays without the need for any other plugins.
* Admin panel handles all the setup for you: Just login and you're ready to start making albums.
* No custom database tables required; galleries live in regular post content.

For a Demo Gallery, see the [plugin's homepage](https://www.justin-klein.com/projects/facebook-photo-fetcher).

== Donate ==

Many hours have gone into developing & maintaining this plugin, far beyond my own personal needs. If you find it useful, please consider [making a donation](https://www.justin-klein.com/projects/facebook-photo-fetcher/#donate) to help support its continued development.

== Installation ==

[Installation Instructions](https://www.justin-klein.com/projects/facebook-photo-fetcher#instructions)

== Privacy ==

This plugin uses the Facebook API to fetch photo albums from Facebook. Facebook's security rules require that apps must authorize from one specific, known location. In order comply with this requirement, when you first authorize the plugin from its admin panel, a Facebook dialog will be initiated via my own authentication server. The dialog itself is shown directly by Facebook, and Facebook handles the entire login process - no personal information will be transferred via my server, as Facebook only supplies a single-use token which I then hand back to your site to be stored. This is what the plugin uses in order to fetch the photos. For more information about how the Facebook authorization process works, please see [Facebook's documentation](https://developers.facebook.com/docs/facebook-login/web).

Usage of this plugin means the site administrator is consenting to [Facebook's data policy](https://www.facebook.com/policy.php). Fetched album data will be stored in your WordPress database, in posts or pages of your choosing. It can be removed by deleting those posts or pages. You are solely responsible for the security and protection of the fetched data, as it resides on and is hosted within your own Wordpress site.

I do not store or process any of your data.

== Frequently Asked Questions ==

[FAQ](https://www.justin-klein.com/projects/facebook-photo-fetcher#faq)


== Screenshots ==

[Demo Gallery](https://www.justin-klein.com/projects/facebook-photo-fetcher#demo)


== Changelog ==
= 3.0.4 (2021-03-23) =
* Update instructions for finding FB Page IDs
* Changed donate link
* Tested w/ WP 5.7

= 3.0.3 (2020-12-09) =
* CSS fixes for TwentyTwentyOne
* Tested w/ WP 5.6

= 3.0.2 (2020-08-27) =
* Fix a bug introduced in the previous update - sorry!

= 3.0.1 (2020-08-26) =
* Minor: Added a bitcoin donation address to the admin panel & readme

= 3.0 (2020-08-11) =
* Update Fancybox to v3.  This fixes several breaking changes that WordPress introduced in their 5.5 release.  Note that existing albums should be re-fetched to update them to the new format (you can do this globally from the plugin's admin panel, Utilities tab).
* Remove the NoLB gallery parameter
* Fix a minor php notice if WP_DEBUG is defined

= 2.10 (2020-04-21) =
* Update the FB Graph API version from 2.12 to 6.0
* Make it easier to copy search results from the admin panel
* Links to search result albums open in a new browser tab
* Fix a harmless PHP warning when fetching an album w/ description hidden
* Minor admin panel clarifications
* Tested with WP 5.4

= 2.9.1.1 (2019-11-20) =
* Minor admin panel clarification
* Tested with WP 5.3

= 2.9.1 (2019-10-02) =
* If the FB API fails to return an expiration for the token, omit showing an erroneous expiration date on the admin panel (FB bug https://developers.facebook.com/support/bugs/2343932799161516/)

= 2.9 (2019-09-08) =
* Renamed the plugin from Facebook Photo Fetcher to Social Photo Fetcher, to satisfy Facebook's copyright lawyers.

= 2.8 (2019-06-07) =
* Recent browser updates seem to have broken the iFrame method of logging in to Facebook.  The authorization process has therefore been rewritten to a more compatible (but slightly less convienient) method: redirecting to the authorization server & back.
* Add filter fpf_fetch_url, so you can customize the URL to request additional fields from Facebook

= 2.7.1 (2019-06-03) =
* Small revisions to the FB login process, along with a note to a new FAQ.  FB's login process seems to be bugging out in FF & IE right now - I've reported it to them, but until they fix it, please login using Chrome.

= 2.7 (2018-11-22) =
* Updates for compatibility with WP 5.0 (Gutenberg Editor)
* CSS fixes for TwentyNineteen, and for posts in TwentyFourteen

= 2.6.2 (2018-10-08) =
* Minor: Revised instructions for finding a Page's ID in the admin panel

= 2.6.1 (2018-07-10) =
* Always load the authentication iframe over ssl (in prep of Facebook requiring this on 10/06/2018)

= 2.6 (2018-06-20) =
* Added privacy policy
* Updated Facebook API calls to v2.12
* Tested with WP 4.9.6

= 2.5 (2016-07-18) =
* Facebook API calls are now versioned (v2.7)
* Optimization: Load the lightbox js in the footer, not header
* Optimization: Remove some IE6-specific CSS that results in poor YSlow & PageSpeed results (CSS expressions & AlphaImageLoader)
* Tested with WP 4.5.3

= 2.4.0.1 (2016-04-21) =
* Verified with WP 4.5

= 2.4 (2016-03-31) =
* Moved the authentication scripts to my primary domain, with a valid SSL certificate.  If your Wordpress admin runs over SSL, you'll now be able to authorize the plugin without having to allow mixed-content in your browser.

= 2.3 (2016-01-12) =
* Add support for localization - finally! :)  Please let me know if you find any strings I've missed (forgotten to make translatable).
* Fix PHP notices if searching for a nonexistant Facebook userid with WP_DEBUG defined
* Fix PHP notices if fetching an album without a valid Facebook token and WP_DEBUG defined

= 2.2.2 (2015-08-09) =
* Fix "Re-Fetch All Albums In Pages" & "Re-Fetch All Albums In Posts" erroneously reporting a photo count of 0
* Tested with WP 4.3

= 2.2.1 (2015-07-29) =
* IMPORTANT: Wordpress 4.2.3 will break the lightboxes in all your existing galleries.  To get them working again, please use the "re-fetch all" option in the admin panel after updating this plugin; your lightboxes will work again *after* they've been re-fetched.
* Add a new "hideDesc" parameter, to hide only the description portion of the header

= 2.2.0 (2015-04-29) =
* Updated the Facebook app to Graph API v2.0.  Please let me know if you encounter any issues!
* Revised wording in the readme & admin panel.  As of April 30 2015, Facebook has removed the permissions necessary to access friends' data - meaning no more "ability to fetch _any_ album you can access."  See [here](https://developers.facebook.com/docs/apps/upgrading) for more info.
* Fix NOTICEs when accessing the admin panel if WP_DEBUG is defined
* Fix NOTICEs when fetching albums if WP_DEBUG is defined
* Fix deprecated function in the admin panel
* Hide contents of the Utilities tab when no access token is present
* Tested with WP 4.2.1

= 2.1.19 (2014-12-22) =
* Fix CSS issue with TwentyFourteen theme
* Tested on WP4.1

= 2.1.18 (2014-11-27) =
* Fix potential vulneratility an unescaped option in the admin panel
* Tested with WP 4.0.1

= 2.1.17 (2014-09-28) =
* Fix HTML validation issue caused by Facebook's new photo URL scheme

= 2.1.16 (2014-09-05) =
* Tested with WP 4.0 
* Fixed incorrect line-wrapping on TwentyThirteen theme
* Added a more explicit class "fpf-gallery" around galleries, for CSS styling.
* NOTE: If your album styling gets messed up after this update, just use the admin panel to "Re-Fetch" all albums, which add the new CSS class and get them working as before.

= 2.1.15 (2014-04-17) =
* Tested with WP 3.9

= 2.1.14 (2014-03-08) =
* fpf_default_albumparams filter to set the default parameters for all albums

= 2.1.13 (2014-02-27) =
* Fix for albums with over 500 photos

= 2.1.12 (2014-02-22) =
* Facebook has yet again changed their API without telling anyone, breaking the plugin for albums with over 100 photos.  This update fixes it so albums of any size should work again.
* The admin panel's search utility now shows the size of each album next to its name.
* Tested with wp 3.8.1

= 2.1.11 (2014-01-22) =
* Strip smartphone emoji from image descriptions (was causing crashes/incomplete results) 

= 2.1.10 (2013-12-13) =
* Verified compatibility with WP 3.8
* CSS fix for TwentyFourteen theme

= 2.1.9 (2013-10-25) =
* Don't output a title attribute for photos which don't have captions (to avoid an odd-looking mouseover)
* Add an admin panel warning for users running over SSL (which blocks the login button after some recent Chrome & Firefox browser updates)
* Tested with WP 3.7

= 2.1.8 (2013-05-09) =
* Add a note about the authorization process (to satisfy wp.org's plugin repo guidelines)

= 2.1.7 (2013-05-07) =
* Remove activation/deactivation auth

= 2.1.6 (2013-02-07) =
* Fix some harmless server 404 error logs due to old IE-specific CSS
* Fix a harmless warning on activation if WP_DEBUG is enabled

= 2.1.5 (2012-12-26) =
* Oops - missed one more bug in Fancybox in the previous commit.  Should be working now.
* Tested on WP3.5

= 2.1.4 (2012-12-26) =
* Fix a bug in Fancybox that prevents the use of URLs in 'rel' attribute
* Change the gallery 'rel' attribute to satisfy the HTML5 validator
* Also bundle the uncompressed version of Fancybox (for easier debugging/testing)


= 2.1.3 (2012-11-29) =
* Add filter "fpf_parse_params" to allow developers to supplement the included magic tag params with their own.
* Add filter "fpf_album_data" to modify the album metadata (i.e. author, date, covor photo, etc).
* Add filter "fpf_photos_presort" to modify the photo objects received from Facebook.  Applied before trimming/sorting.
* Add filter "fpf_photos_postsort" to modify the photo objects received from Facebook.  Applied after trimming/sorting.
* Show "FB Photo Fetcher+" in the admin menu if a 3rd party addon is present, and add support for an "Addon" tab.
* Move the "donate" link to the bottom of the Support Info tab (rather than a tab of its own).

= 2.1.2 (2012-11-20) =
* Don't verify the ssl certificate when contacting Facebook (to fix SSL3_GET_SERVER_CERTIFICATE on servers with improper cURL configurations)

= 2.1.1 (2012-11-19) =
* Add the url to the Support Info tab

= 2.1.0 (2012-11-19) =
* Add a button to the admin panel to renew Facebook access tokens; it's available from 1 day after the token is created (since FB only allows you to renew once per day)
* Add a more descriptive error message upon failure to get an access token
* Add a "Support Info" tab to the admin panel
* Fix for galleries in custom post types
* Fix a debug notice about wp_enqueue_style/wp_enqueue_script
* Fix z-indices in Twenty Eleven (so the lightbox doesn't come up beneath the header)
* Get rid of the isGroup and isPage warnings (not necessary since I changed the magic tag identifier)
* Rephrase the 'count mismatch' error message

= 2.0.0 (2012-11-16) =
* Complete rewrite of all Facebook authentication/interaction code; the plugin now uses the new Graph API.  Existing users of v1.x will need to re-authorize in the admin panel, and potentially update existing album tags (but only if you plan to re-fetch those albums).  Please visit the plugin documentation page for more information on upgrading.
* New tabbed admin panel
* The Magic Tag identifier has been changed to "FBGallery2"
* The ID numbering scheme has been changed
* The admin panel revalidates your access token whenever it's loaded, to make sure it hasn't expired
* Added a button to remove an existing token from the database (aka deauthorize)
* Renamed the 'item count' postmeta from _fb_album_size to _fpf_album_size
* Added new postmeta _fpf_album_cover with the Facebook URL of the cover photo
* Removed the Add-From-Server feature (it wasn't working properly; may re-add it at some point in the future...)
* Name and uid are no longer stored in the db, as they're only used by the admin panel (and can be fetched as part of the revalidation test)
* Nicer formatting for album search results
* The footer now says "Generated by Facebook Photo Fetcher 2"
* More changes than I can list...

= 1.3.5 (2012-11-13) =
* Facebook broke the redirect URL on their login dialog.  This fixes it to properly display "success" after authorization again.

= 1.3.4 (2012-08-21) =
* Oops - previous version didn't fully fix the problem.  Should work properly now.

= 1.3.3 (2012-08-20) =
* Handle multibyte characters in caption excerpts

= 1.3.2 (2012-08-16) =
* Facebook changed their API and broke things yet again (removal of prompt_permission.php endpoint). This update should work around it and get things running as they were previously.
* Facebook has announced that they'll break offline_access on Oct 3, 2012.  This update should keep the plugin running after that update as well. 
* Update setup instructions
* Update Wordpress compatibility number. 

= 1.3.1 (2012-06-05) =
* Update the instructions for getting userIDs in the admin panel (Facebook changed their URL scheme again).

= 1.3.0 (2012-04-21) =
* Apparently, the previous lightbox implementation included with this plugin was not GPL-compatible (leading to its removal from the repository). This update uses a different lightbox that should satisfy WP.org.  NOTE that if you update, you will need to re-fetch all of your albums so that their code will be updated to use new lightbox.  You can do this quickly via the "Re-Fetch All Albums In Pages" and "Re-Fetch All Albums In Posts" admin panel buttons. 

= 1.2.15 (2012-02-05) =
* Update version compatability number
* Fix "refresh albums" to work for PRIVATE post/pages (as well as public)
* Admin panel code cleanups
* Slightly revise instructions
* Add better support for code addons

= 1.2.14 (2011-12-19) =
* Apply trailingslashit() to the thumbnail path to prevent double-slash
* Update compatibility number 

= 1.2.13 (2011-11-28) =
* Removed plugin sponsorship messages.  See [Automattic Bullies WordPress Plugin Developers -- Again](http://gregsplugins.com/lib/2011/11/26/automattic-bullies/).
* Update compatibility number

= 1.2.12 (2011-06-28) =
* Add a note to the admin panels that search is only for personal albums
* Reformat the search results to be copy-pasteable tags

= 1.2.11 (2011-06-14) =
* Update compatability tag
* Add (hide-able) sponsorship message

= 1.2.10 (2011-06-08) =
* Slight cleanups to admin panel
* Some code restructuring to support an eventual cronjob addon

= 1.2.9 (2011-03-18) =
* Add new "orderby=reverse" param

= 1.2.8 (2010-11-02) =
* Remove unneeded debug code

= 1.2.7 (2010-10-30) =
* Add return URL to paypal donate button

= 1.2.6 (2010-10-28) =
* Error check if the user denies necessary permissions while connecting to Facebook

= 1.2.5 (2010-10-14) =
* Marked as compatible up to 3.0.1

= 1.2.4 (2010-10-14) =
* Bug fix: Categories were getting lost when using "Re-Fetch All Albums in Posts" 

= 1.2.3 (2010-08-08) =
* Oops - forgot to add a check in one more spot

= 1.2.2 (2010-08-08) =
* Added a check for other plugins globally including the Facebook API

= 1.2.1 (2010-08-07) = 
* Something got left out of the 1.2.0 commit...

= 1.2.0 (2010-08-07) =
* Update the Facebook client library so it'll play nice with newer plugins
* The minimum requirement is now PHP5.

= 1.1.13 (2010-07-24) =
* Update connection process for Facebook's new privacy policies (to address the bug where no albums were returned by search)

= 1.1.12 (2010-07-15) =
* Fix bug where thumbnails were not downloaded for non-group/page albums where only a portion of the album is shown.

= 1.1.11 (2010-03-16) =
* Use php long tags instead of short tags; should work on XAMPP servers now.

= 1.1.10 (2010-03-14) =
* Sorry - 1.1.9 broke regexp's again for 64-bit userID's. Should be fixed.

= 1.1.9 (2010-03-14) =
* Oops - regexp mistake required a space after the albumID in the start tag; fixed.

= 1.1.8 (2010-03-14) =
* The last version broke isPage; fixed.

= 1.1.7 (2010-03-13) =
* Added support for 64-bit userIDs (aka albumID's with dashes and minuses)

= 1.1.6 (2010-03-13) =
* Added a check for has_post_thumbnail exists (so it won't die on pre-2.9 wordpress installations)

= 1.1.5 (2010-03-11) =
* Fix an issue where the last row of photos weren't clearing their floats properly; YOU'LL NEED TO REGENERATE YOUR GALLERIES for this fix to be applied.
* Always explicitly prompt for infinite session (many users seemed to be getting this error)

= 1.1.4 (2010-03-10) =
* Add isPage parameter - now you can get photos from fan pages!

= 1.1.3 (2010-03-09) =
* Include close/next/prev/loading images for lightbox

= 1.1.2 (2010-03-09) =
* Add version number to plugin code
* Small fixes & cleanups
* Update instructions to clear up a common issue

= 1.1.1 (2010-03-08) =
* Fix bug if photo captions are enabled and contain square brackets

= 1.1.0 (2010-03-08) =
* Add support for GROUP photo albums (in addition to USERs)
* Some code restructuring

= 1.0.3 (2010-03-08) =
* Add support for "rand" argument (randomized album excerpts)
* Add links to FAQ when fail to connect with facebook
* Minor cleanups

= 1.0.2 (2010-03-07) =
* Add support for PHP4

= 1.0.1 (2010-03-06) =
* Add default stylesheet

= 1.0.0 (2010-03-06) =
* First Release


== Support ==

Please direct all support requests [here](https://www.justin-klein.com/projects/facebook-photo-fetcher#feedback)
