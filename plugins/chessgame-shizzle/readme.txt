=== Chessgame Shizzle ===
Contributors: mpol
Tags: chess, chessgame, chessgames, pgn, pgn4web, pgnviewer
Requires at least: 4.1
Tested up to: 6.4
Stable tag: 1.2.8
Requires PHP: 7.0
License: GPLv2 or later

Chessgame Shizzle is a nice way to integrate chessgames into your WordPress website. Ideal for chess clubs, your chess blog, or any chess related website.


== Description ==

Chessgame Shizzle is a nice way to integrate chessgames into your WordPress website. Ideal for chess clubs, your chess blog, or any chess related website.
Editors and guests alike can add chessgames in PGN format through a frontend form. All chessgames are available in a custom post type as a single post with an archive list and taxonomies, it couldn't be more simple. Included widgets give you many ways to display your chessgames.

Current features include:

* List view and single view for chessgames as a WordPress post.
* Easy to use form on the frontend to add a chessgame.
* Simple and clean admin interface that integrates seamlessly into WordPress admin.
* JavaScript enabled game viewer (pgn4web).
* Several widgets to display latest game, featured game or a list of recent games.
* Tactics Lessons, useful for trainers and students.
* Moderation, so that you can check a chessgame before it is visible.
* Subscribing to notifications.
* Board themes and Piece themes.
* Anti-spam features with Nonce, Honeypot and Form Timeout for upload form.
* Import and Export from and to a PGN file.
* Generate (featured) images from a chess position.
* Localization. Own languages can be added very easily through [GlotPress](https://translate.wordpress.org/projects/wp-plugins/chessgame-shizzle).

... and all that integrated in the stylish WordPress look.


= Translations =

Translations can be added very easily through [GlotPress](https://translate.wordpress.org/projects/wp-plugins/chessgame-shizzle).
You can start translating strings there for your locale. They need to be validated though, so if there's no validator yet, and you want to apply for being validator, please post it on the support forum. I will make a request on make/polyglots to have you added as validator for this plugin/locale.

= Demo =

Check out the demo at [my local chess club Pegasus](https://svpegasus.nl/algemeen/partijen/)

= Compatibility =

This plugin is compatible with [ClassicPress](https://www.classicpress.net).

= Contributions =

This plugin is also available in [Codeberg](https://codeberg.org/cyclotouriste/chessgame-shizzle).


== Installation ==

= Installation =

* Install the plugin through the admin page "Plugins".
* Alternatively, unpack and upload the contents of the zipfile to your '/wp-content/plugins/' directory.
* Activate the plugin through the 'Plugins' menu in WordPress.
* For the form, add '[chessgame_shizzle_form]' in a page. That's it.
* For managing chessgames, check 'Chessgames' in the admin menu.

= License =

The plugin itself is released under the GNU General Public License. A copy of this license can be found at the license homepage or in the chessgame-shizzle.php file at the top.


== Frequently Asked Questions ==

= What is PGN notation and how do I write it? =

PGN notation is a way to write the moves of a chessgame, plus some metadata, like names of the players, etc.
On [Wikipedia](https://en.wikipedia.org/wiki/Portable_Game_Notation) there is more general information.

= I added a chessgame, but when viewing I get a 404 error. =

Please go to Dashboard > Settings > Permalinks and hit 'Save' twice. That should flush your permalinks and make it work.

= Where is my index page with the archive of chessgames? =

You can browse to /chessgame and you will see the archive page. The template used for this is archive.php.

= Which shortcodes are available? =

* '[chessgame_shizzle_form]' for the upload form.
* '[chessgame_shizzle_simple_list]' for a simple list with pagination.
* '[chessgame_shizzle_game postid="536"]' to show a single game that was published in the chessgames post type.
* '[chessgame_shizzle_game_extended postid="536"]' to show a single game that was published in the chessgames post type.
* '[chessgame_shizzle_lessons]' for lessons on the frontend.

In case you read this on wordpress.org, the quotes are supposed to be standard double quotes, no backticks.

= I only want to show games from a category or tag. =

You can use a shortcode parameter for showing games only from certain categories or tags:

	[chessgame_shizzle_simple_list category="213,212" tag="345,355"]

In case you read this on wordpress.org, the quotes are supposed to be standard double quotes, no backticks.

= What about Spam? =

By default this plugin uses:

* Nonce: Will verify if you really loaded the page with the form first, before posting a chessgame. Spambots will just submit the form without having a Nonce.
* Honeypot feature: Hidden input field that only spambots would fill in.
* Form Timeout: Check if the form has been submitted too fast.

New submitted chessgames will be set as pending, waiting to be moderated. When someone adds a chessgame that is considered spam, it will not be accepted and the user will get a message on the frontend form.


== Screenshots ==

1. Test...


== Changelog ==

= 1.2.8 =
* 2023-12-14
* Use date from first publishing, not from submit.
* On admin editor, have default content, so we always get saved on new chessgame.

= 1.2.7 =
* 2023-10-28
* Enable shortcut keys in chessboard.
* Support revisions in cs_chessgame post type.
* Register meta fields.
* Support revisions for meta fields in WordPress 6.4.

= 1.2.6 =
* 2023-05-22
* Use extended iframe for upload form and preview.
* Add option to search in simple list shortcode.
* Also support author in cs_chessgame post type.
* Fix calls for 'is_singular' by using correct post type as parameter.
* Small additions to spamfilters.
* Add small advertisement for a recommended plugin (free).
* Fix PHP 8.1 compatibility in chessParser.
* Take more hints from phpcs.

= 1.2.5 =
* 2023-03-07
* Add overflow to PGN text.
* Enable AutoScroll To CurrentMove.
* Add Mobile CSS for regular board in post (thanks teodorcat).
* Add Custom CSS to the iframes (from the Customizer).
* Support some Print CSS.
* Keep exact background-color for board table when printing.
* Do not set height to board table, only to divs around it.

= 1.2.4 =
* 2022-05-19
* Fix silly bug in notification emails.

= 1.2.3 =
* 2022-04-20
* Add filter options for export, category, tag, ECO code.
* Fix filename for download.
* Always add nonce to upload form.
* Use standard nonce for preview.
* Add post content to export and import.
* Use 'asc_attr()' for export in pgn data.
* Fix generating moves and fen code in certain conditions.
* Fix 'NaN' error on first load of a puzzle.

= 1.2.2 =
* 2021-11-30
* Add category and tag parameters for simple list shortcode.
* Fix template function for simple list.
* Fix spacing in result.
* Add option for '*' as result.
* Do not make post content required.
* Use human readable date in simple list shortcode.
* Add and improve color icon (and classnames).
* Fix display of elo rating when it is set as 0.
* Fix pagination, always use 'int', not the 'float' from 'ceil()'.
* No need to check if function 'current_user_can', 'user_can', 'is_multisite' and 'has_shortcode' exist.
* Use functions like 'esc_attr', 'esc_html' and 'esc_url' when appropriate.
* Some updates from phpcs and wpcs.

= 1.2.1 =
* 2021-10-20
* Add Preview button to upload form.
* Add FEN code button to viewer.
* Be able to hide metadata again.
* Make sure exported PGN files are using columns, for compatibility.
* Change extension for downloaded PGN file to .pgn.
* Change Download link and Image generator to buttons for accessibility.
* Use `wp_kses_post()` for sanitizing PGN data.
* Use `chessgame_shizzle_sanitize_pgn()` where appropriate.
* Add 365chess boardtheme.
* Set extended iframe to 'height:800px;'.
* Run update hook in 'init' instead of 'admin_init' to support background updates.

= 1.2.0 =
* 2021-05-04
* Add more filter options to lessons.
* Add search options to lessons.
* Add field for Level for difficulty of puzzle.
* Add shortcode for lessons to readme.txt.
* Use 'wp_reset_postdata()' in query for initial lesson.
* Add tornelo boardtheme.
* Fix generation of image on frontend within an iframe.
* Check for gd_info in AJAX call as well.

= 1.1.9 =
* 2021-04-07
* First stab at Tactics Lessons.
* Use datepicker for date fields.
* Use correct format for storing date as 'yyyy.mm.dd'.
* Validate date on export when added from meta to pgn.
* Fix warning when saving post on dashboard.
* Fix color of "opening code" in upload form.
* Fix display of NAG symbols.
* Add function 'chessgame_shizzle_pgn4web_dead_enqueue()'.
* Don't update meta cache on WP_Query's.
* Forget about using minified pgn4web.min.js.
* Use 'add_query_arg()' for the iframe src attribute.

= 1.1.8 =
* 2021-03-15
* Add field for Round in chessgame.
* On upload and import, when PGN data contains a FEN code, set it to puzzle.
* Add bulk action to list-table to generate featured image from FEN code.
* Improve title/description for featured image.
* Improve filename for featured image.
* Fix deprecated jQuery calls with WP 5.6 and jQuery 3.5.
* Fix error on upload when there are no subscribers for email notifications.
* Fix form submission when using http on a https website or viceversa.
* Make Import page have a real metabox.
* Add Export page and improve import from those exports.
* Set class for boardtheme on 'div.chessboard-wrapper' instead of 'body' element.
* Remove function 'chessgame_shizzle_body_classes'.
* Add function 'chessgame_shizzle_get_boardtheme_class'.
* Update chessParser to 2020-03-26.
* Fix import of annotations in game import.
* Fix import of castling move with NAG.
* Change 'intval()' to '(int)'.
* Change 'strval()' to '(string)'.

= 1.1.7 =
* 2021-01-25
* Update pgn4web to 3.05.
* Add better support for chess puzzles.
* Add "required" parameter to search widget.
* Fix featured image generation.
* Use more correct cache dir 'wp-content/cache/cs-mfen'.
* Remove deprecated call for 'get_magic_quotes_gpc()'.
* Requires PHP 5.4.

= 1.1.6 =
* 2020-10-21
* Set publish date to submit date, instead of moderation date.
* Remove ':' from form labels.
* Remove placeholders in form, labels should be enough.
* Update About page.
* Always escape formdata in 'chessgame_shizzle_add_formdata()'.

= 1.1.5 =
* 2020-05-25
* Show subscription status for email subscriptions.
* Run timeout function only once.
* Add uninstall.php file to uninstall options from db.

= 1.1.4 =
* 2020-02-25
* Don't show PHP comment on settings page.
* Add title and name attribute to iframes.
* Avoid browser cache for the iframe url.

= 1.1.3 =
* 2019-05-29
* Small CSS fix for pagination.
* Change arrows in next/prev pagination.
* Drop check for mime-type on import, too many problems.
* Set timeout from 4s to 1s.
* Support new wp_initialize_site action for multisite.
* Update chessParser from 2017-04-08 to current Git.
* Fix quoting problems in pgnParser cleanPgn function.

= 1.1.2 =
* 2019-03-05
* Add dropdown with prefab results to upload form.
* Add dropdown with prefab results to admin metabox.
* Add pagination for simple list shortcode.
* Flush cache on save_post action for recent chessgame.
* Add function chessgame_shizzle_clear_cache.
* Use esc_html functions everywhere.
* Use static vars instead of global vars in messages.
* Small fixes found by the phan tool.
* Add new file functions/cs-post-meta.php for meta fields for posts with shortcodes.

= 1.1.1 =
* 2019-01-23
* Only load admin files on wp-admin.
* On settings page, have separate functions for $_POST update.
* Add some accessibility fixes.
* Don't use transients for hashed field names, is faster this way.
* Add Variation text to help text.

= 1.1.0 =
* 2018-09-23
* Add link to frontend to generate image from position. (Requires GD).
* Add button to admin preview to generate featured image from position. (Requires GD).
* Add boardtheme 'newinchess'.
* Add class 'MFEN' for generating images.
* Add 'dir' to piecethemes.
* Add function 'chessgame_shizzle_get_piecetheme_dir'.
* Add function 'chessgame_shizzle_get_boardthemes_full'.
* Add function 'chessgame_shizzle_get_boardtheme_full'.
* Add cs-frame-post_id ID to iframes.
* Small CSS updates for iframe widgets.
* Small CSS updates for admin preview.

= 1.0.9 =
* 2018-08-31
* Add shortcode 'chessgame_shizzle_game' for a single game.
* Add shortcode 'chessgame_shizzle_game_extended' for a single game.
* Set timeout from 7s to 4s.

= 1.0.8 =
* 2018-08-03
* Add option to add chessgames to main RSS Feed.
* Add download link for PGN file.
* Add widget for searching chessgames.
* Add boardthemes beyer, blue, chesscom_blue,, chessonline, falken, informator, pgnviewer_yui, zeit.
* Add boardthemes bamboo, burl, coffee_bean, ebony_pine, executive, marble, marble_blue, marble_green, wenge.
* Update boardtheme wood.
* Set default options to use the cache.
* Cleanup piecethemes.

= 1.0.7 =
* 2018-07-16
* Add screen-reader-text to metadata link in single view.
* Rename boardtheme chess24 to wood and fix it.
* Add boardtheme magazine.
* Add 'noscript' to upload form.

= 1.0.6 =
* 2018-06-29
* Add tags and categories to meta information at frontend on single view.
* Add metabox on admin editor for preview of pgn game.
* Add preview board for theme settings.
* Add antispam function for form timeout.
* Improve antispam function for honeypot.
* Add settingstab for antispam options.
* Add example text to the privacy policy.
* Update pgn4web to 3.03.
* Add function 'chessgame_shizzle_get_field_name'.
* Fix warning for PHP 7.2.

= 1.0.5 =
* 2017-12-15
* Add more boardthemes.
* Add more piecethemes.
* Change highlightcolor.
* Load JS/CSS in iframe directly, not through wp_footer.
* Simple list really only fetches max 500 posts.

= 1.0.4 =
* 2017-09-28
* Always enqueue our own media (for iframe widgets).
* Slightly smaller board in iframe.
* Add widget for featured chessgame in iframe.
* Improve some ECO codes.

= 1.0.3 =
* 2017-09-26
* Add settings page.
* Add boardthemes.
* Add piecethemes.
* Add email notifications.
* Add widget for recent chessgames.
* Add widget for most recent chessgame in iframe.
* Revert to original color of highlighted move.
* Move thirdparty software to own dir /thirdparty.
* Improve some ECO codes.

= 1.0.2 =
* 2017-04-08
* Add admin page for importing PGN games.
* Add PgnParser and accompanying libraries.
* Add link to simple list.
* Support all ECO codes.
* Rename themefiles from chessimager.

= 1.0.1 =
* 2017-03-26
* Add shortcode 'chessgame_shizzle_simple_list'.
* Save meta key for this shortcode.
* Better placeholder for date in upload form.
* Prefix all function names.
* Use esc_html functions and similar for escaping.
* Sanitize and validate data.
* Return the form with an error for Honeypot and Nonce.
* List third parties on about page.
* Do not concatenate strings, but use sprintf on about page.

= 1.0.0 =
* 2017-03-21
* Initial release.
