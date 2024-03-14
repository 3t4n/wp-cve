=== PixGridder ===
Contributors: manuelmasia
Donate link: http://www.pixedelic.com/plugins/pixgridder
Tags: grid, layout, drag & drop, responsive, columns, page builder, grid builder, effects, page builder, page composer
Requires at least: 3.9.0
Tested up to: 4.2.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple page grid composer that splits your pages into ordered grids, a builder for rows and columns

== Description ==

Here is the demo page: [PixGridder](http://www.pixedelic.com/plugins/pixgridder "PixGridder") where a link to Pro version is available (pixgridder-pro)

I prefer to define this plugin as a **"grid builder"** instead of a page builder, because a page builder is commonly intended as a tool that allows to create sections and, usually, comes with shortcodes such as tabs, accordions, particular sections, galleries etc... And, in many cases, all these shortcodes are not compatible with other similar plugins you could prefer to use.

**PixGridder** is instead very simple, because it only allows to split your page into rows and columns **by moving the functions** available on your tinyMCE editor from the whole page to each column you decide to split your page into. In this way you can use the plugins you prefer with the shortcodes you prefer (and also the buttons available on your tinyMCE editor) since the editor is **exactly the same one** you would have without using PixGridder.

= How it works =

**PixGridder** doesn't generate shortcodes, it only puts into your posts and pages some html comments like this: `<!--pixgridder:row[cols=3]-->`

They're **invisible except through the source code**. So if you want to disable the plugin you don't have to worry about a lot of strange and unuseful shortcodes across your content because everything will be hidden for both the users and the search engine robots.

However, if you want to remove any trace of the plugin from the source code of a page, you can do it by enabling the **"no trace"** option: you'll get rid of all the HTML comments, but you'll keep unaltered your content.

= Page builder visual description =
Take a look to the [2nd screenshot](http://s.wordpress.org/plugins/pixgridder/screenshot-2.jpg)

1. **title** and **version** of the installed plugin
2. **Preview tab**: by clicking it you will see the live site with a preview of the changes, not editable from the preview visual itself
3. **Builder tab**: where you can edit your page/post by using the grid builder
4. **row dragger**: use it to sort your rows and move them to the top or to the bottom
5. **ID and class**: use it to open a dialog box where to add an ID or a class to your row
6. **clone button**: clone your entire row and append the clone below the original one (everything will be cloned, cloumns, IDs, classes etc.)
7. **column select**: select how many columns your row is based on
8. **delete**: remove the row
9. **alert icon**: this icon will appear when you make a not-allowed operation, such as adding a column where there is no space for other columns or try to reduce the width of column if it already has got the minimum width allowed
10. **add row**: click to add an empty row
11. **add column**: click to add an empty column
12. **column dragger**: use it to sort your columns and move them to the left or to the right inside a row
13. **column content**: here is displayed a preview of the content (the font and the text color won't reflect on the frontend)
14. **expand column**: click to increase the width of the column
15. **contract column**: click to reduce the width of the column
16. **edit column**: click to open a dialog box where to edit the content of the column (a tinyMCE editor will open in the dialog box, the width of the textarea will be relative to the max width set for the theme you're using and the width of the column you're editing)
17. **clone button**: clone your entire column and append the clone to the right of the original one, if there is enough space (everything will be cloned, content, ID, class etc.)
18. **ID and class**: use it to open a dialog box where to add an ID or a class to your column
19. **delete**: remove the column
20. **"Disable the grid builder"**: tick the checkbox and update the page → now the page is editable without using the grid builder, but the frontend still displays columns and rows, so pay attention to not remove any html comment or you risk to break the layout
21. **"Remove any trace of PixGridder from this page"**: tick the checkbox and update the page → all the row and the columns will be removed but without touching the content, still available both on the frontend and on the editor

== Installation ==

1. Upload `pixgridder` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enter a page backend and enjoy it

== Frequently Asked Questions ==

= No questions available =

...

== Screenshots ==

1. Pixgridder output demo
2. Pixgridder UI - take a look to the description tab (2)

== Changelog ==

= 2.0.6 =
* 2015.06.17 - 	tinyMCE height when disabled
= 2.0.5 =
* 2014.09.10 - 	Donate page
= 2.0.4 =
* 2014.09.04 - 	Wordpress 4.0 intelligent resizing editor issue
= 2.0.3 =
* 2014.06.06 - 	Fixed <!--nextpage--> position issue
= 2.0.2 =
* 2014.06.06 - 	Fixed <!--nextpage--> position issue
				Fixed infinite loading for preview tab on not saved pages/posts
= 2.0.1 =
* 2014.05.05 - 	Added filters for rows and columns tag
= 2.0.0 =
* 2014.04.18 - 	Fixed issues due to tinyMCE 4.0
				Fixed 'preg_replace_callback' anonymous functions for PHP < 5.3.0 compatibility
				Other WP 3.9.0 compatibility issues
= 1.3.1 =
* 2013.12.20 - 	Fixed CSS issues
= 1.3.0 =
* 2013.12.13 - 	Fixed WP 3.8 compatibility issues
= 1.2.0 =
* 2013.11.09 - 	Fixed an issue with embedded videos
				Fixed an issue with cloned tinyMCE (that restores back the original way edited on 1.1.0)
= 1.1.0 =
* 2013.09.24 - 	Changed how the tinyMCE is included in dialog boxes
= 1.0.1 =
* 2013.09.21 - 	Changed priority to the filter for the_content()
= 1.0.0 =
* 2013.08.17 - 	First release

== Upgrade Notice ==

= 1.1.0 =
It should avoid issues if another shortcode editor use "#content" instead of getting the active editor id