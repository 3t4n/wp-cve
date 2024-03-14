=== Get Post List With Thumbnails ===
Contributors: alvaron
Donate link: http://www.wpworking.com/
Tags: list, posts list, list post, thumbnails
Requires at least: 3.1.3
Tested up to: 3.5.2
Stable tag: 10.0.2

== Description ==

Description:Displays a list with posts and custom size thumbnails(for the first attached or featured image), linked to each post permalink. You can configure it as multiple widgets or short code. 

You can choose(parameters are not required, see screen shot 2) list orientation, display only images, display the post date, date format, display the post title, category, the number of posts, number of registers per line(for horizontal orientation), the table width, the thumbnails dimensions, the table cellpadding and cellspacing and the columns layout. See it working on http://www.wpworking.com/posts-list/

Get GPLWT PRO and choose colors and formating!
http://www.wpworking.com/shop/

More info about the plugin: http://www.wpworking.com/

Ask for support on wpworking@wpworking.com


== Installation ==


1. Upload `get_post_list_with_thumbs` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Register a widget sidebar on your functions file, for example, just paste the code below on your theme functions.php(seescreenshot 1)

/*if ( function_exists('register_sidebar') )
register_sidebar(array(
	'name' => 'get_post_list_with_thumbs',
	'before_widget' => '',
	'after_widget' => '',
	'before_title' => '',
	'after_title' => '',
));*/

You if you have already registered any sidebar, you can drag the `Get Post List With Thumbnails` widget inside it, at wp-admin

4. Configure the widget on your wp-admin pannel and save(see screenshot 2)
5. Use the code bellow where you want the widget ti show, on your theme pages(see screenshot 3)
/* if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Get Post List With Thumbnails')) : endif; */

you can use short code: [gplt nocats="" ptype="post" ttpos="b" orient="h" imgo="false" ttgo="true" dtgo="true" dtfrm="1" categ="" postnr="100" linn="3" divwid="300" tbwid="90" tbhig="90" cp="4" cs="4" lwt="2" tte="" sptb="false" tgtb="false" ordm="DESC" ordf="ID" metk="" mett="" pgin="" ptype="post" dexcp="false" gptb="false"]

Important: if you want to add featured image instead of the first image, add add_theme_support( 'post-thumbnails' ); to your functions.php file

== Frequently Asked Questions ==

If you have any questions, please let me know  wpworking@wpworking.com

If some of your thumbnails doesn't show up, it happens because they are linked to older posts
just upload the image again and link or include in the new post

== Screenshots ==

1. Posting it on a page as shotcode - live demo on: http://www.wpworking.com/posts-list/
2. Configuring widget parameters on wp-admin
3. Registering the widget on wp-admin widget pannel and Making the widget work on a PHP page

== Changelog ==
New features:
exclude comma-separated posts
exclude comma-separated categories
orderby Most Commented and Most Accessed posts / choose widget title position / custom post types support
Bug fixed on total number of posts
Adjustment for the beta pagination feature
Bug Fixed on number of posts
Adjustment to display the first attached image to the post
Bug Fixed on Widgets Registering
Supports Multiple Widgets
Set Custom Field Key and Custom Field Values Type(number or text to combine with DESC or ASC order)
New workaround for better positioning using shortcodes
Random order available
You can call GPLT wherever you want on your posts and pages, using short code

[gplt nocats="" ptype="post" ttpos="b" orient="h" imgo="false" ttgo="true" dtgo="true" dtfrm="1" categ="" postnr="100" linn="3" divwid="300" tbwid="90" tbhig="90" cp="4" cs="4" lwt="2" tte="" sptb="false" tgtb="false" ordm="DESC" ordf="ID" metk="" mett="" pgin="" ptype="post" dexcp="false"]

Parameters

NoPsts: comma-separated ids from the posts you want to exclude, default=''
NoCats: comma-separated ids from the categories you want to exclude, default=''
Post Type: all posts types, singular name, default=posts
Widget Title Position: b for before, a for after. default =b
Orientation: orient="v" // v = Vertical , h = Horizontal default 'v'
Display only images: imgo="false" // true, false default 'false'
Display post title: ttgo="false" // true, false default 'false'
Display post date: dtgo="false" // true, false default 'false'
Date Format: dtfrm="1" // 1 - d/m/y , 2 - m/d/y default '1'
Category Slug: categ='' // leave out or blank for all categories default=''
Number of Posts: postnr="20" //default=20
Number of registers per line: linn="3" //default=3 only for horizontal orientation
Table result width: divwid=300 // default=300
Thumbnails Width: tbwid="40" //default = 40
Thumbnails Height: tbhig="40" //default = 40 
Result Table Cellpadding: cp="4" // default= 4
Result Table Cellspacing: cs="4" // default= 4
Column layout: lwt = 1 // default= 1
Widget(or list) title: tte= "" // default=""
Suppress Thumbnails: sptb="false" // true, false default 'false'
Target links to a blank page/tab: tgtb="false" // true, false default 'false' 
Order: ordm = "DESC" // DESC, ASC default 'DESC'
Order By: ordf = "ID" // ID, Date, Title, Random default 'ID'
Custom Field Key: metk = "" // default ''
Custom Field Values Type: mett = "" // n(numeric),t(text) default 'n' numeric
Registers per Page(beta feature): pgin = "" // default= ''
Post Type: ptype="post" // default='post' 'post' or 'page'
Display Excerpt: dexcp="false" // true, false default 'false'
Get featured image(instead of first image): gptb="false" // true, false default 'false'

== Upgrade Notice ==
10.0.2 New feature, exclude posts
10.0.1 New feature, exclude categories
10.0.0 New features: orderby Most Commented and Most Accessed posts / choose widget title position / custom post types support
9.0.1 Bug fixed on total number of posts
9.0.0 New option, choose featured image(instead of first image)
8.0.4 Adjustment for the beta pagination feature
8.0.3 Bug Fixed on number of posts
8.0.2 Adjustment to make GPLWT display the image first attached to the post
8.0.1 Bug fixed on widget registration
8.0.0 You can choose between displaying posts and pages. Supports displaying excerpt. Beta page navigation feature added
7.0.0 Supports Multiple Widgets
6.2.0 Set Custom Field Key and Custom Field Values Type(number or text to combine with DESC or ASC order)
6.1.0 Workaround for better positioning using shortcodes / Random order available
6.0.0 Supports Order(DESC, ASC) and OrderBy(ID, Date, Title)
5.0.0 You can suppress thumbnails and target links to a blank page/tab
4.0.0 Now you can use the short code [gplt]
See it working on http://www.wpworking.com/posts-list/

== Arbitrary section ==

Get GPLWT PRO and choose colors and formating!
http://www.wpworking.com/shop/

The result div is on div id gplwt_container, table id div_postlist so you can play with css
Configure as widget or shortcode

Very important: the pagination feature is a beta feature, under development

[gplt nocats ="" ptype="post" ttpos="b" orient="h" imgo="false" ttgo="true" dtgo="true" dtfrm="1" categ="" postnr="100" linn="3" divwid="300" tbwid="90" tbhig="90" cp="4" cs="4" lwt="2" tte="" sptb="false" tgtb="false" ordm="DESC" ordf="ID" metk="" mett="" pgin="" ptype="post" dexcp="false" gptb="false"]

Parameters

NoPsts: comma-separated ids from the posts you want to exclude, default=''
NoCats: comma-separated ids from the categories you want to exclude, default=''
Post Type: all posts types, singular name, default=posts
Widget Title Position: b for before, a for after. default =b
Orientation: orient="v" // v = Vertical , h = Horizontal default 'v'
Display only images: imgo="false" // true, false default 'false'
Display post title: ttgo="false" // true, false default 'false'
Display post date: dtgo="false" // true, false default 'false'
Date Format: dtfrm="1" // 1 - d/m/y , 2 - m/d/y default '1'
Category Slug: categ='' // leave out or blank for all categories default=''
Number of Posts: postnr="20" //default=20
Number of registers per line: linn="3" //default=3 only for horizontal orientation
Table result width: divwid=300 // default=300
Thumbnails Width: tbwid="40" //default = 40
Thumbnails Height: tbhig="40" //default = 40 
Result Table Cellpadding: cp="4" // default= 4
Result Table Cellspacing: cs="4" // default= 4
Column layout: lwt = 1 // default= 1
Widget(or list) title: tte= "" // default=""
Suppress Thumbnails: sptb="false" // true, false default 'false'
Target links to a blank page/tab: tgtb="false" // true, false default 'false'
Order: ordm = "DESC" // DESC, ASC default 'DESC'
Order By: ordf = "ID" // ID, Date, Title, Random default 'ID'
Custom Field Key: metk = "" // default ''
Custom Field Values Type: mett = "" // n(numeric),t(text) default 'n' numeric
Registers per Page(beta feature): pgin = "" // default= ''
Post Type: ptype="post" // default='post' 'post' or 'page'
Display Excerpt: dexcp="false" // true, false default 'false'
Get featured image(instead of first image): gptb="false" // true, false default 'false'

If you have any questions, please let me know  wpworking@wpworking.com

This readme file were validated at http://wordpress.org/extend/plugins/about/validator/