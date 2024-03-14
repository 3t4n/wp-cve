=== Smart Countdown FX ===
Contributors: alex3493
Tags: countdown, counter, count down, timer, event, widget, years, months, FX, animated, responsive, recurring, rtl
Requires at least: 4.0
Tested up to: 5.3.2
Stable tag: 1.5.5
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6C2YULBJW68M6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Smart Countdown FX displays a responsive animated countdown. Supports years and months display and recurring events

== Description ==
Smart Countdown FX key features:

* years and months (along with “traditional” weeks, days, hours, minutes and seconds) can be displayed in the countdown interval.

* counter digits changes are animated and site administrator can easily switch between available [animation profiles][2], included with the plugin or added later.

* event [import plugins][3] support, no need to manually edit widget settings or shortcode for recurring or calendar events.

**Other features**

Smart Countdown FX can show both countdown and count up counters, and it will switch to the “count up” mode automatically when the event time arrives. Event description can be configured individually for countdown and count up modes and can containt HTML markup allowed for a post.

Smart Countdown FX supports different layouts. Most popular layouts (sidebar, shortcode, shortcode compact, etc.) are included in the package and can be selected in the widget options or using a shortcode attribute. Custom layout presets can be easily created using existing ones as a starting point. You will find detailed instructions in the documentation.

RTL languages are natively supported in all counter layouts.

Smart Countdown FX widget is responsive. Open "Responsive" page on different handheld devices or just change your browser window width if you are on a desktop to see the feature in action.

More than one countdown can be displayed on the same page, each instance with its individual settings and configuration.

Events import plugins are supported.

[Project home page][1]

**Coming soon**

* More event import plugins for popular event management plugins and services.

* More animation profiles.

 [1]: http://wp.smartcalc.org/
 [2]: http://wp.smartcalc.org/category/animation-profiles/
 [3]: http://wp.smartcalc.org/category/event-import-plugins/

== Installation ==
Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

== Frequently Asked Questions ==
= How does one use the shortcode, exactly? =
<http://wp.smartcalc.org/reference/> - complete list of shortcode attributes has been provided to answer this exact question.

= How can I add new animation effects? =
<http://wp.smartcalc.org/installing-more-animations/> - detailed instructions on installing additional animation profiles.

= I have installed the plugin, but Smart Countdown FX doesn't appear in available widgets list. =
Do not forget to activate the plugin after installation.

= I have configured the widget but it is not displayed. =
Please, check "Counter display mode" setting in the widget options. If "Auto - both countdown and countup" is not selected, the widget might have been automatically hidden because the event is still in the future or already in the past.

= I have inserted the countdown in a post, but it is not displayed. What's wrong? =
Check the spelling of "fx_preset" attribute (if you includeded it in attributes list). Try the standard fx_preset="Sliding_text_fade.xml". Also check "mode" attribute. Set in to "auto".

== Screenshots ==
1. Widget settings

2. Widget in sidebar (custom animation)

3. HTML Countdown in front end

4. "Time has arrived!" message

== Changelog ==

= 1.5.5 =

* Fix bug in Arabic translation.
* Added languages: Polish (pl_PL) and Ukrainian (uk).

= 1.5.4 =

* Added language: Bulgarian (bg_BG).

= 1.5.3 =

* Added language: Hebrew (he_IL).

= 1.5.2 =

* Added RTL support.
* Added more languages: Portuguese, Arabic.

= 1.5.1 =

* Bug fix - sliding animations were blocking other page elements (e.g. links) at same horizontal position.

= 1.4.9 =

* Bug fix - better compatibility with event import plugins
* Responsive behavior update (compatibility)

= 1.4.8 =

* Translations added (for front end only)
* Language files cleanup

= 1.4.7 =

* Alternative directory for custom layout presets added - now custom layouts do not get overwritten on plugin updates

= 1.4.6 =

* Code optimization (responsive performance)

= 1.4.5 =

* Improved animation performance - now using velocity.js
* Better overlapping events support with event import plugins
* Responsive behavior bug fixes
* Click on widget (if set in options) now opens the linked page in a new browser window
* Customize preview mode bug fixes

= 1.4.4 =

* Bug fixes

= 1.4.3 =

* Bug fix - counter appeared momentary before being hidden if counter display mode selected had limitations on
display time before / after event

= 1.4.2 =

* Bug fix - in countdown-only display mode counter remained visible for 1 second after event

= 1.4.1 =

* Relaxed window resize throttle limit, better compatibility with slow browsers
* Code optimization

= 1.4 =

* Maintainance release
* Added compatibility with older browsers (IE8)
* Added "text opacity" and "sliding text" animations with italic font style

= 1.3.9 =

* Bug fix - internet explorer gave error on "const" keyword in javascript. Corrected in this version.
* Bug fix - responsive adjust caused momentary page scroll in some configurations

= 1.3.8 =

* Automatic redirect on countdown zero now supports overlapping events
* Code optimization.

= 1.3.7 =

* Bug fixes.

= 1.3.6 =

* Customize preview is now fully functional. In previous versions event import plugins and counter display mode settings were ignored.
* Bug fixes.

= 1.3.5 =

* Bug fix: invalid style was breaking "Use titles for count up mode as 'Time has arrived' message" option, the counter block remained visible even when event was in progress. Corrected in this version.

= 1.3.4 =

* Minor bug fixes

= 1.3.3 =

* Bug fix: wrong interval calculation if years are visible but months are hidden
* Bug fix: all counter units become hidden in some configurations when displaying values close to event time

= 1.3.2 =

* Improvement: now "units" shortcode attribute can also define a list of units to hide (if started with "-"), e.g. units="-weeks,seconds" will show all counter units except weeks and seconds.
* Bug fix: more reliable months and days interval calculation in rare cases (e.g. when counting from leap FEB 29)

= 1.3.1 =

* Update to 1.3 has caused problems for some customers, trunk version was not in sync with the tag

= 1.3 =

* Bug fix: dates interval was not calculated correctly in some cases (-1 day error)
* Maintainance release, code clean up

= 1.2.9 =

* Bug fix: incorrect digits display on countdown zero when "hide_highest_zeros" option in layout preset is disabled (=0)

= 1.2.8 =

* Fixed "deadline crossed while running" bug

= 1.2.7 =

* Improved stability on cached sites
* Fixed "browser back button" bug

= 1.2.6 =

* Bug fix - mode="countdown" was interpreted incorrectly in shortcode

= 1.2.5 =

* CSS optimization
* added support for "countdown to event end" mode with custom countdown limit
* bug fixes

= 1.2.4 =

* Bug fix: column counter layout was not displayed correctly in v1.2.3
* Improved timer accuracy and stability

= 1.2.3 =

* added support for event import plugins which implement "countdown to event end" feature
* Fixed CSS bug that erroneousely changed layout on new counter unit display (e.g. when count up crosses 1 minute value)
* better clock sync on next event query (for event import plugins support)

= 1.2.2 =

* responsive behavior refactored
* flexible suspend detection threshold - improved stability on slow devices

= 1.1.2 =

* frontend translations added for French, German and Italian
* line-height in event titles fixed in responsive behavior

= 1.1 =

* suspend/resume detection threshold set to a greater value - improves counter stability on mobile devices

= 1.0.1 =

* fixed bug - switching tabs in Firefox and Chrome caused issues in some complex animations. Currently published animation profiles were not affected but it is recommended to update anyway.

= 1.0.0 =

* fixed automatic update issue. Now additional animation profiles can be installed in a dedicated folder outside the plugin's directory and are not deleted on automatic plugin update.

= 0.9.9 =

* bug fix - imported overlapping events were not placed in queue correctly in some cases
* compatibility - some themes set font-size for all div elements in style sheet. It caused incorrect digits display. Now this issue is fixed.

= 0.9.8 =

* event import plugins support - bug fixes
* added "%imported%" placeholder support in event titles

= 0.9.7 =

* added custom styles shortcode attributes

= 0.9.6 =

* bug fixes

= 0.9.5 =

* support for Event import plugins
* bug fixes

= 0.9 =

* First release

== Upgrade Notice ==

Please upgrade to at least 0.9.5 in order to be able to use event import plugins (upgrade to the latest version is always recommended)
