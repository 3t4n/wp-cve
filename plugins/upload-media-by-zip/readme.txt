=== Upload Media by Zip ===
Contributors: trepmal
Donate link: http://kaileylampert.com/donate/
Tags: upload, media library, zip
Requires at least: 2.8
Tested up to: 4.6
Stable tag: 0.9.1

Upload a zip archive and let WP unzip it and attach everything to a page/post (or not).

== Description ==

Upload a zip archive and let WP unzip it and attach everything to a page/post (or not).

Please note that you'll still be restricted by your server's maximum upload size.

* [I'm on twitter](http://twitter.com/trepmal)

If the zip file uploads, but the contents aren't extracted, see the [FAQs](http://wordpress.org/extend/plugins/upload-media-by-zip/faq/).

== Installation ==

Standard installation

== Frequently Asked Questions ==

= The zip file uploads, but the contents aren't extracted =
Try this: Open up the upload-media-by-zip.php file and locate <code>WP_Filesystem()</code> (line 301). Surrounding it are three lines labeled 1, 2, and three. Uncomment those.

This problem happens only on some server setups, I haven't experienced it personally which makes it difficult for me to provide a better solution. If you have one, please share.

= The tabs in the media pop-up are all crazy =
Sounds like you're using 2.8. Update.

== Screenshots ==

1. Original uploader (good if you don't want to attach images to another post)
2. Zip uploader media button
3. Second uploader

== Other Notes ==

= Languages =
* Farsi, by [mohsengham](http://www.newbie.ir/1390/04/upload-media-by-zip/)
* German, by [daveshine](http://deckerweb.de/)

== Changelog ==
(Localizations added without changing version number)

= 0.9.1 =
* Maintenance. Verified compat with 4.6

= 0.9 (20110808) =
* German localization added

= 0.9 (20110807) =
* Farsi localization added

= 0.9 =
* Getting ready for translation, POT file may already be available
* Bugfix: can now delete temporary upload despite hidden files

= 0.8.1 =
* Bugfix: now shows correct message on failed extraction

= 0.8 =
* Experimental "Insert all into post" feature (feedback appreciated)
* List attachment IDs with 'success' message

= 0.7 =
* fun with recursion - funky stuff should be fixed - sorry for the double update

= 0.6 =
* zipped folders (any depth) now good-to-go
* file extensions removed from title, like core uploader

= 0.5 =
* allows contents of a zipped folder to be added successfully to the media library

= 0.4 =
* linked Upload page to better capability (upload_files)
* works with 2.8! (media upload tabs are wacky in 2.8, but I'm not going to fix it... because it's 2.8)
* minor wording changes (sticking with "upload zip archive")

= 0.3 =
* fixed compatibility with Quick Press

= 0.2 =
* added zip uploader to media pop-up
* first WP.org release

= 0.1 =
* just getting started

== Upgrade Notice ==

= 0.9 =
Speak something besides English? Translate this plugin!

= 0.8 =
New experimental "Insert all into post" feature. Feedback appreciated.

