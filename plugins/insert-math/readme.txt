=== Insert math ===
Contributors: CMTV
Tags: math, tex, latex, insert math, formula, insert formula
Requires at least: 4.0
Tested up to: 4.8
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Fast and handy insert any math formulas in your posts.

== Description ==

Add math support for your site. Insert block/inline formulas in your text with useful and fancy modal. Watch and monitor rendered math in process of typing formula. Change formula color.

= Features =

* Math support on both frontend and admin panel
* Useful and fancy modal for inserting and editing math in posts
* Insert both block and inline math
* Changing formula color
* Set ID and classes for formula
* Automatic highlighting math in visual editor
* Adding x-scrollbar to block math if browser viewport is smaller then formula

== Installation ==

Best is to install directly from WordPress.

Manual installation is simple if required. Only 2 steps:

1. Upload the entire 'insert-math' folder from downloaded archive to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

That's it! You now can use math on your site!

== Changelog ==

= 2.0 =
* Selected in visual editor formula automatically change its highlighting color
* Added an ability to set formula color
* Added an ability to set an ID for formula
* Added an ability to set classes for formula

= 1.0 =
First version

== Upgrade Notice ==

= 2.0 =
* Selected in visual editor formula automatically change its highlighting color
* Added an ability to set formula color
* Added an ability to set an ID for formula
* Added an ability to set classes for formula

= 1.0 =
First version

== Frequently Asked Questions ==

= Why do I see LaTeX math in first second when page is loading? =

You see LaTeX math in first second because math renderer is loading. When it fully loads all LaTeX math on the page will be automatically converted to normal math.

= Which library do you use to add math support? =

Plugin is using [MathJax](https://www.mathjax.org/) library. It was created long ago (in 2009) and it has support for most of the browsers. All redundant extensions are removed in order to speed up loading.

= How to set a color for a part of formula? =

You can set a color for a part of formula by wrapping this part with `\color{color}{text}` LaTeX command. Everything inside `{text}` will have a color specified in `{color}`. Example: `\color{red}{a^2}=\color{blue}{b^2}+\color{green}{c^2}`.

= I have a problem/idea. Who should I talk with? =

Open an issue at plugin GitHub [repository](https://github.com/CMTV/wordpress-plugin-insert-math).

== Screenshots ==

1. Inserting and editing formulas with handy dialog. Monitor the results in process of typing formula.
2. All formulas will have default site text color.
3. Insert formulas in block/inline format. Choose the color for any formula.