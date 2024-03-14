=== Cyr to Lat reloaded – transliteration of links and file names===
Contributors: webcraftic
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VDX7JNTQPNPFW
Tags: cyr-to-lat, cyr to lat, rus to lat, cyrillic, latin, l10n, russian, rustolat, slugs, translations, transliteration, media, georgian, european, diacritics, muiltilanguage
Requires at least: 4.2
Tested up to: 5.0
Requires PHP: 5.2
Stable tag: trunk
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin converts Cyrillic, Georgian and Greek links and file names to Latin characters. This is essential for the accurate WordPress plugin operations, and visibly improves the readability of links.

== Description ==

Cyr to lat reloaded is the improved version of the popular Cyr to lat plugin, created by Sergei Biriukov, and Rus-To-Lat plugin designed by Anton Skorobogatov. Even though the plugin is still easy & simple, we have updated both the symbol base and the source code, and fixed known bugs.

What is transliteration? This term usually refers to the action aiming to convert symbols from one language to another. For example, when we change Cyrillic symbols to Latin. Since the majority of the Internet uses Latin symbols, all software is designed with full Latin support – not Cyrillic, Chinese or whatever. That is why whenever you assign Cyrillic names, you can end up with access problems in their absolute URLs. Besides, Cyrillic in links really deteriorates absolute URLs readability. And it this case you might need transliteration of links and file names.

We have created a simple transliteration plugin named Cyr to lat reloaded. It replaces Cyrillic, Georgian and Turkish symbols with Latins automatically and creates readable absolute URLs for posts, sections, marks, products and custom post types. In addition, this plugin fixes incorrect file names and removes extra symbols, which may cause access problems.

**Here’s an example of Cyrillic URL:**

http://webcraftic.com/%D0%BF%D1%80%D0%B8%D0%B2%D0%B5%D1%82-%D0%BC%D0%B8%D1%80

**Now the same link, but transliterated to Latin:**

http://webcraftic.com/privet-mir

Do you see the difference? The first one is encoded and recognized by browser only, when the second one with Latin symbols is shorter and much clearer.

**Incorrect file name of the image:**

%D0%BC%D0%BE%D0%B5_image_ 290.jpg

A+nice+picture.png

**The example of image transliteration – readable name without special characters:**

moe_image_ 290.jpg

a-nice-picture.png

You can ignore all basic rules of creating file names, but one day you’ll definitely deal with bad links in images and 404 error in direct links to the files.

We recommend naming files in Latin symbols. Cyr-to-lat reloaded plugin can do this for you automatically on each file loading. In this case, there will be no bad links.

**FEATURES**

* Converts absolute URLs of existing posts, pages, sections and tags automatically (after plugin activation);
* Preserves absolute URLs integrity;
* Transliterates file names in attachments;
* Performs transliteration of attachment file names.
* Supports Russian, Belarusian, Ukrainian, Bulgarian, Georgian, Greek, Armenian, Serbian symbols
* Support plugin Advanced custom fields
* Support plugin Asgaros
* Support plugin Buddypress

#### EXTENDED VERSION OF THE PLUGIN WITH CONTROL PANEL ####
* [Cyrlitera – transliteration of links and file names](https://wordpress.org/plugins/cyrlitera/)
* [Clearfy – WordPress optimization plugin and disable ultimate tweaker](https://wordpress.org/plugins/clearfy/)

**THANKS TO THE PLUGIN DEVELOPERS**

We’ve used some features of the following plugins:

Cyrlitera, WP Translitera, Rus-To-Lat, Cyr-To-Lat, Clearfy — WordPress optimization plugin, translit-it, Cyr to Lat enhanced, Cyr-And-Lat, Rus filename translit, rus to lat advanced

#### NEED SUPPORT, WE GOT YOU COVERED ####
We provide free support for this plugin. If you are pushed with a problem, just create a new ticket. We will definitely help you!

1. **[Get starting free support](https://clearfy.pro/support/?utm_source=wordpress.org&utm_campaign=wbcr_clearfy&utm_content=repo_description)**
4. **[Hot support](https://clearfy.pro/hot-support/?utm_source=wordpress.org&utm_campaign=wbcr_clearfy&utm_content=repo_description)** - Any user can contact us. You can use it only if you find a php error in plugin, get a white screen, or want to report a vulnerability.

#### ADDITIONAL RESOURCES ####
1. **[Youtube channel](https://www.youtube.com/channel/UCxOg4XzLe5kX1bP2YP4TTfQ)**
2. **[Telegram](https://t.me/webcraftic)**

== Screenshots ==

1. Example for posts
2. Example for files

== Installation ==

1. Upload `cyrandlat` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Make sure your system has iconv set up right, or iconv is not installed at all. If you have any problems (trimmed slugs, strange characters, question marks) - please ask for support.

== Frequently Asked Questions ==

= How can I define my own substitutions? =

Add this code to your theme's `functions.php` file:
`
function my_cyr_and_lat_table($cal_table) {
   $cal_table['Ъ'] = 'U';
   $cal_table['ъ'] = 'u';
   return $cal_table;
}
add_filter('wbcr_ctl_default_symbols_pack', 'my_cyr_and_lat_table');
`
= Does this plugin support multisites? =

Unfortunately, the answer is no. It’s temporary, as we are trying to add this support in some of the future versions.

= How to restore converted slugs? =

You can roll back the changes you have made, using the extended version of  [Cyrlitera plugin](https://wordpress.org/plugins/cyrlitera/ "Cyrlitera plugin").

= How can I redirect users from old links to new ones? =

You can do this by installing the extended version of [Cyrlitera plugin](https://wordpress.org/plugins/cyrlitera/ "Cyrlitera plugin")

== Changelog ==
= 1.2.0 =
* Added: Compatible with Wordpress 5.0
* Added: Gutenberg support
* Added: Support Armenian symbols
* Added: Support Serbian symbols
* Added: Add ACF support
* Added: Add buddypress support
* Added: Add Asgaros forum support
* Fixed: Bug with Cyrillic links on frontend.

= 1.1.1 =
* Added: Greek symbols
* Added: Special symbols
* Added: Ability to rollback changes
* Fixed: Bug with transliteration of Ukrainian symbols

= 1.1.0 =
* Rename plugin, now the plugin has a name Webcraftic Cyr-And-Lat reloaded
* Updated character base
* Fixed compatibility issues
* Tested with the latest version of Wordpress

= 1.0.2 =
* Backward сompatibility with "old" russian slugs works in terms (tags and categories) too.

= 1.0.1 =
* Fixed minor bug

= 1.0 =
* Initial release
