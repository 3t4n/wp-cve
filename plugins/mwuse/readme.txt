=== MWuse - Adobe Muse Converter ===

Contributors: MWuse
Contributors URL: https://mwuse.com
Tags: Adobe Muse, drag-and-drop, editor, landing page, page builder, responsive, shortcodes, widgets, visual composer
Donate link: https://www.paypal.me/musetowordpress
Requires at least: 4.6
Tested up to: 4.9.5
Stable tag: 1.2.17006
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Design your WordPress theme easily in Adobe Muse. Get the best of both worlds.

== Description ==

Design your WordPress theme easily in Adobe Muse
Get the best of both worlds.

= Features =
* Quick conversion
* Use multiple Adobe Muse themes
* Name your pages with the standard WordPress [hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/)
* Use shortcode direct in Adobe Muse
* [Grid list](https://mwuse.com/product/grid-list/) (posts, custom posts, categories, tags, taxonomies)
* [Shortcodes](https://mwuse.com/shortcodes-list/)
* [and more widgets](https://mwuse.com/product-category/widgets/)
* WordPress Multisite ready


== Installation ==
1. Install the plugin
2. Upload your Adobe Muse theme (export as HTML) in the "mtw-themes" folder. Create one folder for each Muse theme. Muse to WordPress support multiple Muse themes.
	* Via FTP: find the mtw-themes (root of the WordPress website)
	* Via wp-admin: MWuse -> Upload Website (follow instruction in the menu)

== Read more & learn ==
[mwuse.com](https://mwuse.com)


== Credits ==
* [Pagetemplater](https://github.com/wpexplorer/page-templater) by WPExplorer

== Changelog ==
1.2.18 (progress)
- Woocommerce - slide scripts not find
- Issue with Author info
- Include mtw outsourcing scripts in mwuse
- hero-files.php mime_content_type deprecated "php 7.0" replaced by finfo

1.2.17
- ZIP Upload no need sub-folder for one project
- shortcode no-conflict [mw_noconflict] [your_shortcode] [/mw_noconflict]
- logic redirections (functions/logic-template-redirect.php)
- new function added: mw_get_page_by_template('your-project/your-file.html')
- resolve issue with grid taxonomy before post
- resolve issue with iframe

1.2.16
- WooCommerce: add to cart url encode error with GET methode
- Bug with only one Breakpoint
- Using Grid list Sequence on homepage (issue)

1.2.15
- Featured image right size issue
	* if image is lower size than the zone,
	* if a size without crop info exist
- Multisite support improvement
- Item issue, code error with a BP (Wagner issue)
- Double "GET method" (?) on external link
- scripts/images/assets/ if 404 search and display or redirect
- Slideshow no conflict
	* conflict with wp attached images, grid, ... (because no option and default integration)
	* bug new option
	* works with thumbs
- image aligment in wp content
- mtw_content shortcode issue with BP
- mtw_permalink shortcode ID, name, title args added (name and title require post_type arg)
- rare issue: double break in museconfig.js