=== Multilingual Comments Number ===
Contributors: minimus
Donate link: http://simplelib.com
Tags: comments, comments_number
Requires at least: 3.0
Tested up to: 6.1
Stable tag: 2.0.23
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin corrects output of comments_number for languages having more than one form of plurals (All Slavic languages for example).

== Description ==

The output strings of standard Wordpress function comments-number can not be correct for languages having more than one form of plurals (All Slavic languages for example). __Multilingual Comments Number__ plugin corrects this problem.

Available languages:

  * English (of course)
  * Russian
  * German
  * Polish
  * Belorussian
  * Ukrainian
  * Czech

If you have created your own language pack, or have an update of an existing one, you can send __.po__ and __.mo files__ to me so that I can bundle it into __Multilingual Comments Number__.
  
More info you can see on the [plugin page](http://www.simplelib.com/?p=128)

== Installation ==

1. Upload plugin dir to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That is all.

== Frequently Asked Questions ==

= What about "numeric only" strings? =

"Numeric only" strings are not filtering by __miltilingual-comments-number__ and will be send to result document AS IS.

= What about HTML tagged strings? =

All HTML tags are saved. Only text will be translated. 

For example: __comments\_number__ send to result page this code `<a href="ext-comments-editor.php">21 comments</a>`

resulting string after __Multilingual Comments Number__ filtering for ru_RU: `<a href="ext-comments-editor.php">21 комментарий</a>`

== Screenshots ==

1. Outputs of comments_number are correct not always
2. Tags and numeric only strings
3. Plurals
4. Admin Page  

== Changelog ==

= 2.0.23 =
* Wordpress 6.0 ready
= 2.0.22 =
* Wordpress 5.8 ready
= 2.0.21 =
* Wordpress 5.2 ready
= 2.0.20 =
* Wordpress 5.1 ready
= 2.0.19 =
* Wordpress 4.6 ready
= 2.0.18 =
* Wordpress 4.5 ready
= 2.0.17 =
* Wordpress 4.4 compatibility
= 2.0.16 =
* Wordpress 4.2 compatibility
= 2.0.15 =
* Wordpress 4.0 compatibility
= 2.0.14 =
* Some changes
= 2.0.13 =
* Plugin Settings moved from Plugin Admin Page to Discussion Settings Page
* Wordpress Settings API are used
* Now the plugin codes are using __Wordpress 3.0+__ standards (PHP5 only) 
= 1.0.10 =
* 'No comments' and 'One comment' strings settings added
* Admin page added
= 0.2.8 =
* code optimisation
* Wordpress 2.8 compatibility checked
= 0.2.7 =
* Code optimisation
* Czech language added
= 0.2.6 =
* Initial upload

== Upgrade Notice ==

= 2.0.23 =
Wordpress 6.0 ready
= 2.0.22 =
Wordpress 5.8 ready
= 2.0.21 =
Wordpress 5.2 ready
= 2.0.20 =
Wordpress 5.1 ready
= 2.0.19 =
Wordpress 4.6 ready
= 2.0.18 =
Wordpress 4.5 ready.
= 2.0.17 =
Wordpress 4.4 compatibility
= 2.0.16 =
Wordpress 4.2 compatibility.
= 2.0.15 =
Wordpress 4.0 compatibility
= 2.0.14 =
Some changes
= 2.0.13 =
Plugin Settings moved from Plugin Admin Page to Discussion Settings Page
Wordpress Settings API are used
Now the plugin codes are using __Wordpress 3.0+__ standards (PHP5 only)
= 1.0.10 =
'No comments' and 'One comment' strings settings added
Admin page added
= 0.2.8 =
code optimisation
Wordpress 2.8 compatibility checked
= 0.2.7 =
Code optimisation
Czech language added
= 0.2.6 =
Initial upload