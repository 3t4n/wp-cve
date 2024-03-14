=== WP-TAB Tableau Public Viz Block ===
Contributors: garyhukkeri
Donate link: paypal.me/wptab
Tags: tableau, tableau public, gutenberg block, embed block, embed tableau, embed analytics, analytics, reports
Requires at least: 5.2
Tested up to: 6.1.1
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An easy way to embed Tableau Public Vizualizations into a WordPress page with basic embed options.

== Description ==

 A Gutenberg Embed Block to embed Tableau Public Vizualizations into a WordPress page with basic options. Provides options to hide tabs, hide toolbar, set device and set dimensions of the Viz.

- This plugin will only work for Tableau Public Visualizations i.e. you need to have a viz hosted on Tableau Public ([What is Tableau Public]( https://community.tableau.com/docs/DOC-9135)).
- This plugin uses the Tableau JS API to a)fetch and b)embed your Tableau Public Viz within your Wordpress site.
- The Tableau Public Data policy can be viewed [here](https://public.tableau.com/en-us/s/data-policy). Data on Tableau Public is expected to be public.
- Tableau's privacy policy can be viewed [here](https://www.tableau.com/privacy)
- This plugin does not store the data anywhere in any format. It simply provides a user friendly way to pull a viz hosted on [http://public.tableau.com](http://public.tableau.com) and embed it into the site hosting the plugin.

All you need is your Tableau Public url e.g 'https://public.tableau.com/views/WorldIndicators/GDPpercapita'

##Usage:

1. Click Add Block
2. Under the Embed category, select the Tableau Public Viz Block.
2. Paste your url in the block e.g 'https://public.tableau.com/views/WorldIndicators/GDPpercapita'
3. Set options in the block options or leave as is for defaults
	a. Toggle Hide tabs to hide tabs on the viz
	b. Toggle Hide toolbar to hide the toolbar on the viz
	c. Select Desktop/Tablet/Phone layout (must be actually available in the viz for this to work, see: https://help.tableau.com/current/pro/desktop/en-gb/dashboards_dsd_create.htm)
	d. Manually set Height and Width of the viz
4. Optionally select the alignment from the block Toolbar
5. Save and publish


== Installation ==

1. Upload `wptab-tableau-public-viz-block` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
Coming soon.



== Screenshots ==
1. Find the block under Embeds
2. The main block input where you insert the Tableau Public URL
3. Available options
4. Selected options example

== Changelog ==

= 1.3 =
* load tableau min js instead of normal to fix unexpected-response-error-upon-render

= 1.2 =
* js bug fixes for null containers

= 1.1 =
* Ability to add multiple blocks
* Tested for WP V5.3

= 1.0 =
* First release.
