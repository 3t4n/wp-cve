=== Custom Upload Dir ===
Contributors: ulfben
Donate link: https://www.amazon.com/gp/registry/wishlist/2QB6SQ5XX2U0N/105-3209188-5640446?reveal=unpurchased&filter=all&sort=priority&layout=standard&x=21&y=17
Tags: upload, organize, files, media
Requires at least: 3.9
Tested up to: 6.0.1
Stable tag: 3.4.6
Requires PHP: 5.2

Keeps your uploaded files organized in smart folder structures.

== Description ==
WordPress allow you to put uploads in year- and month-based folders. Custom Upload Dir is more flexible. You can construct paths from variables like post title, category, post author, post date [and many more](http://wordpress.org/extend/plugins/custom-upload-dir/screenshots/). 

I created Custom Upload Dir ensure my file system is always mirroring the site structure. This ensures I can always find files manually when needed. It also simplifies backups and platform migrations. 

//*[Ulf Benjaminsson](http://www.ulfbenjaminsson.com)*

= Changelog v. 3.4.6 (2020-07-17) =

1. fixed a null dereference error. ([thanks, defotoverij](https://wordpress.org/support/topic/warning-array_key_exists-expects-parameter-2-to-be-array-null-given/)!)

= Changelog v. 3.4.5 (2020-05-05) =

1. added french translation (thanks, bibi699depreau!)

= Changelog v. 3.4.4 (2019-03-24) =

1. Fixed a few PHP warnings
2. Removed the %permalink% template as it broke uploads to drafts.

= Changelog v. 3.4.3.2 (2019-03-24) =

1. Added support for the Gutenberg editor.

= Changelog v. 3.4.2 (2015-03-16) =

1. Added [filter 'cud_generate_path'](https://wordpress.org/plugins/custom-upload-dir/installation/) so you can easily adjust the path with any variable you want.
2. Added %mime_type% to sort files by mime type
3. Added %author_role% to sort files by author role.
4. Coming: support for per-post-type settings. Eg: every post type can have their own rules for where to place uploads. Currently missing the admin interface. If you feel like helping out, let me know.

= Changelog v. 3.4 (2013-02-09) =

1. Added %file_ext% to sort by file type
2. Added [tentative support for uploads from WordPress apps](http://wordpress.org/support/topic/search-and-mobile-apps-compatibility?replies=6#post-3837193).
3. Removed setting for "Base upload folder" as WP core no longer exposes this
4. Cleared out some PHP error on the admin page
5. Fixed leaking CSS on admin page

= Changelog v. 3.3 (2011-12-18) =

1. Support for WordPress 3.3
2. Support for tags and categories
3. Support for custom taxonomies
4. Added %current_user%, %permalink%
5. Added all permalink tags
6. Added test-function to admin page

[Older changelogs moved here.](http://wordpress.org/extend/plugins/custom-upload-dir/changelog/)

== Changelog ==

= Changelog v. 3.4.5 (2020-05-05) =

1. added french translation (thanks, bibi699depreau!)

= Changelog v. 3.4.4 (2019-03-24) =

1. Fixed a few PHP warnings
2. Removed the %permalink% template as it broke uploads to drafts.

= Changelog v. 3.4.3.2 (2019-03-24) =

1. Fixed 'upload_dir' not providing $post_id in Gutenberg editor.

= Changelog v. 3.4.1 (2015-03-16) =

1. Added [filter 'cud_generate_path'](https://wordpress.org/plugins/custom-upload-dir/installation/) so you can easily adjust the path with any variable you want.
2. Added %mime_type% to sort files by mime type
3. Added %author_role% to sort files by author role.
4. Coming: support for per-post-type settings. Eg: every post type can have their own rules for where to place uploads. Currently missing the admin interface. If you feel like helping out, let me know.

= Changelog v. 3.4 (2013-02-09) =

1. Added %file_ext% to sort by file type
2. Added [tentative support for uploads from WordPress apps](http://wordpress.org/support/topic/search-and-mobile-apps-compatibility?replies=6#post-3837193).
3. Removed setting for "Base upload folder" as WP core no longer exposes this
4. Cleared out some PHP error on the admin page
5. Fixed leaking CSS on admin page

= Changelog v. 3.3.2 (2011-12-29) =

1. Updated init to use WordPress API properly
2. Removed dead code

= Changelog v. 3.3.1 (2011-12-27) =

1. Fixed poor sanitization of post slugs (%post_name%)
2. Added %post_parent%
3. Fixed settings not always initializing

= Changelog v. 3.3 (2011-12-18) =

1. Support for WordPress 3.3
2. Support for tags and categories
3. Support for custom taxonomies
4. Added %current_user%, %permalink%
5. Added all permalink tags
6. Added test-function to admin page

= Changelog v. 3.0.2 (2011-07-29) =

1. Fix for post_author not working
2. Fix timestamps from the media uploader ([thanks; alx359](http://wordpress.org/support/topic/plugin-custom-upload-dir-unattached-images-upload-to-19700101?replies=8))

**Changelog v. 3.0.1 (2010-11-01)**

1. [Fix for "undefined function get_userdata()"](http://wordpress.org/support/topic/plugin-custom-upload-dir-fatal-error-call-to-undefined-function-get_userdata) (thanks somatic!)

**Changelog v. 3.0 (2010-10-21)**

1. Rewrote entire plugin for WordPress 3.0
2. A slew of new placeholders for building path's with
3. A new interface to allow arbitrary strings in path
4. Error messages when uploading to un-named or un-dated posts
5. Added translation support

**Changelog v.2.5.4 (2008-12-31)**

1. Adjusted the test case. (Pedja)

**Changelog v.2.5.3 (2008-12-30)**

More problems have crept up due to the [massive amount of dry runs of 'upload_dir'](http://trac.wordpress.org/ticket/8753) in WP 2.7.

1. Added quick bail for every call that's not for a file upload. (props to Pedja Ljubomirovic (Canada))
	
**Changelog v.2.5.2 (2008-12-15)**

1. Fixed problem with plugin blocking deletion/editing of attached files.

**Changes in v.2.5.1 (2008-12-14)**

1. Fixed Custom Upload Dir breaking WP 2.7 when called from the loop

**Changes in v.2.5 (2008-11-15)**

1. Support for WP 2.7-beta1
1. Added 'post_author'	
1. Fixed 'post_title' (broken since WP 2.4)
1. Fixed issue with use_yearmonth overriding post date	
1. Tons of small(ish) updates
1. Removed Mediacaster-support

**Changes in v.2.0** (2007-10-19)
 
1. Mediacaster compability.
1. Added proper configuration screen
1. Massive code overhaul
1. Name change (previously "Daily Upload Dir")

== Upgrade Notice ==

= 3.4.4 =
Fixed a few PHP warnings, and removed %permalink% template as it broke drafts.

= 3.4.3.2 =
Added support for uploads from the Gutenberg editor.

= 3.4.2 =
Added filter, allows users to easily extend the list of tags with anything they want.

= 3.4 =
Added %file_ext%, support for uploads from WordPress apps and fixed some errors.

= 3.3.2 = 
Removed dead code and updated to use WordPress API properly

= 3.3.1 = 
Added %post_parent% and some fixes

= 3.3 =
Support for WordPress 3.3 and many improvements!

= 3.0.2 =
Fixes post_author and and post_date behaviour in the media uploader.

= 3.0.1 = 
[Fix for "undefined function get_userdata()"](http://wordpress.org/support/topic/plugin-custom-upload-dir-fatal-error-call-to-undefined-function-get_userdata) (thanks somatic!)

= 3.0 =
Lots of new features and more flexible interface.

== Installation ==

1. Extract the `custom-upload-dir`-folder and transfer it to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Customize your directory structure in `Settings -> Custom Upload Dir`

== Frequently Asked Questions ==

= How do I change base upload folder? =
Since WP 3.5, by a setting in wp-config.php:

`define('UPLOADS', WP_CONTENT_DIR.'/your-folder');`

Just be aware that this will likely break all paths to your previously uploaded stuff.

= How can I define my own tags? =
Use the `cud_generate_path`-filter, like so:

`function my_generate_path($template, $post_id){
	$my_tags = array('%tag1%','%tag2%');
	$replace = array($data, getData());
	$customdir = str_replace($tags,	$replace, $template);
	return $customdir;
}
add_filter('cud_generate_path', 'my_generate_path');`


== Screenshots ==

1. The configuration screen.
2. Shows how the upload path corresponds to the post's timestamp.


== Additional Info ==

Copyright (C) 2007-2019 [Ulf Benjaminsson](https://www.ulfbenjaminsson.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA