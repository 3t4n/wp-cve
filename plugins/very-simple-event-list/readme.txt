=== VS Event List ===
Contributors: Guido07111975
Version: 17.3
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Requires PHP: 7.1
Requires at least: 5.3
Tested up to: 6.4
Stable tag: 17.3
Tags: simple, event, events, event list, event manager


With this lightweight plugin you can create an event list.


== Description ==
= About =
With this lightweight plugin you can create an event list.

Add the VS Event List block or the shortcode to a page or use the widget to display your events.

You can customize your event list via the settings page or by adding attributes to the VS Event List block, the shortcode or the widget.

= How to use =
After installation go to menu item "Events" and start adding your events.

Then create or edit a page and add the VS Event List block or any of the following shortcodes:

* `[vsel]` to display upcoming events (today included)
* `[vsel-future-events]` to display future events (today not included)
* `[vsel-current-events]` to display current events
* `[vsel-past-events]` to display past events (before today)
* `[vsel-all-events]` to display all events

You can also go to Appearance > Widgets and use the VS Event List widget to display your events.

= Settings page =
You can customize your event list via the settings page. This page is located at Settings > VS Event List.

Several settings can be overridden when using the relevant attributes below.

This can be useful when having multiple event lists on your website.

= Attributes =
You can also customize your event list by adding attributes to the VS Event List block, the shortcode or the widget. Attributes will override the settings page.

* Add custom CSS class to event list: `class="your-class-here"`
* Change the number of events per page: `posts_per_page="5"`
* Skip one or multiple events: `offset="1"`
* Change date format: `date_format="j F Y"`
* Display events from a certain category: `event_cat="your-category-slug"`
* Display events from multiple categories: `event_cat="your-category-slug-1, your-category-slug-2"`
* Reverse the order of events in the upcoming, future and current events list: `order="DESC"`
* Reverse the order of events in the past and all events list: `order="ASC"`
* Change the "no events are found" text: `no_events_text="your text here"`
* Disable event title link: `title_link="false"`
* Disable featured image link: `featured_image_link="false"`
* Disable featured image caption: `featured_image_caption="false"`
* Disable featured image: `featured_image="false"`
* Disable read more link: `read_more="false"`
* Disable pagination: `pagination="false"`
* Display all event info: `event_info="all"`
* Display a summary: `event_info="summary"`

Example: `[vsel posts_per_page="5" event_cat="your-category-slug" event_info="summary"]`

When using the VS Event List block or the widget, don't add the main shortcode tag or the brackets.

Example: `posts_per_page="5" event_cat="your-category-slug" event_info="summary"`

When using the "offset" attribute, pagination will be disabled.

= Featured image =
Featured images will be used as the primary image for every event.

By default the "post thumbnail" is used as the source for the featured image. The size of the post thumbnail may vary by theme.

WordPress creates duplicate images in different sizes upon upload. These sizes can be set via Settings > Media. If the post thumbnail doesn't look as expected (low resolution or poor cropping), you can choose a different size via the settings page.

You can also change the width of the featured image.

The featured image on the single event page is handled by your theme.

= Default support =
Plugin creates a custom post type "event".

This automatically supports the single event page, the event category page, the (event) post type archive page and the search results page. It hooks into the theme template file that is being used by these pages.

Support for the single event page is needed. Support for the other pages is added to make VS Event List compatible with page builder plugins. Events on default WP pages are not ordered by event date.

Plugin activates the post attributes box in the editor. In the post attributes box you can set a custom order for events that have the same date. Custom order can be handy when automatic ordering by time is disabled.

Plugin supports the menu page. Support is added to make VS Event List compatible with page builder plugins.

= Advanced Custom Fields =
You can add extra fields to the event details by using the [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields) plugin. The most commonly used fields are supported.

Create a field group for the post type "event" and add fields to this group. This new field group will then be added to the editor.

= RSS and iCal feed =
You can share your upcoming events via an RSS feed.

The default RSS widget will display events from future to upcoming. To reverse this order I recommend using an RSS feed plugin capable of changing the RSS feed order.

You can share your upcoming and past events with an external calendar via an iCal feed.

You can activate both feeds on the settings page.

= Have a question? =
Please take a look at the FAQ section.

= Translation =
Translations are not included, but the plugin supports WordPress language packs.

More [translations](https://translate.wordpress.org/projects/wp-plugins/very-simple-event-list) are very welcome!

The translation folder inside this plugin is redundant, but kept for reference.

= Credits =
Without the WordPress codex and help from the WordPress community I was not able to develop this plugin, so: thank you!

Enjoy!


== Frequently Asked Questions ==
= About the FAQ =
The FAQ are updated regularly to include support for newly added or changed plugin features.

= How do I set plugin language? =
The plugin will use the website language, set in Settings > General.

If translations are not available in the selected language, English will be used.

= How do I set the date and time format? =
By default, the plugin uses the date and time format from Settings > General.

The datepicker and date input field only support 2 numeric date formats: "day-month-year" (30-01-2016) and "year-month-day" (2016-01-30).

If your date format is not supported, it will be converted into 1 of the 2 formats above.

You can change the date and time format for the frontend of your website via the settings page. You can also change the date format by using an attribute.

The date icon only supports 2 date formats: "day-month-year" (30 Jan 2016) and "year-month-day" (2016 Jan 30).

If your date format is not supported, it will be converted into 1 of the 2 formats above.

= Which timezone does the plugin use? =
Events are saved in the database and displayed throughout your website without a timezone offset.

= Can I change the colors of the date icon? =
If you are handy with CSS, you can use the Additional CSS page of the Customizer for your custom styling.

Examples:

Change background and text color of whole icon: `.vsel-start-icon, .vsel-end-icon {background:#eee; color:#f26535;}`

Change background and text color of top part: `.vsel-day-top, .vsel-month-top {background:#f26535; color:#eee;}`

= Can I override plugin template files via my (child) theme? =
You can only override the single event page via your (child) theme.

In most cases, the PHP file "single" is being used for the single event page. This file is located in your theme folder.

Create a duplicate of the file "single" and rename it "single-event". Then add this file to your (child) theme folder and customize it to your needs.

= How does plugin hook into theme template files? =
The plugin hooks into the `the_content()` and `the_excerpt()` functions. These are used by most themes.

Some themes and page builder plugins will not support these functions. In that case support can be disabled via the settings page.

= Why is there no pagination in the widget? =
Pagination is not working properly in a widget.

But you can add a link to the page that displays more events.

= Why is there no pagination when using the offset attribute? =
Offset breaks pagination, so that's why pagination is disabled when using offset.

= Why does the offset attribute have no effect? =
Under some circumstances, such as when attribute "posts_per_page" is set to "-1", the offset attribute will be ignored.

= Why is the page with all events not displaying properly? =
This only applies to pages with a shortcode based event list.

When using the block editor, edit the page and check the shortcode in "Edit as HTML" mode.

When using the classic editor, edit the page and check the shortcode after switching to the "Text" tab instead of "Visual".

It might be accidentally wrapped in HTML tags, such as code tags. Remove these tags and resave the page.

= Can I have "Event" as page title? =
Having "Event" as page (or post) title will not cause any problems, but having "event" as slug (end of URL) will cause a conflict with the (event) post type archive page.

You should change this slug into something else. This can be done by changing the permalink of this page (or post).

= Why a 404 (nothing found) when I click the title link? =
This is mostly caused by the permalink settings. Please resave the permalink via Settings > Permalinks.

= Why a 404 (nothing found) when I click the event category link? =
This is mostly caused by the permalink settings. Please resave the permalink via Settings > Permalinks.

= Can I add multiple shortcodes on the same page? =
This is possible, but to avoid a conflict you should disable pagination. This can be done via the settings page or by using an attribute.

= Why no event details or event categories box in the editor? =
When using the block editor, click the options icon and select "Preferences".

When using the classic editor, click the "Screen Options" tab.

Probably the checkbox to display the relevant box in the editor is not checked.

= Why no featured image box in the editor? =
When using the block editor, click the options icon and select "Preferences".

When using the classic editor, click the "Screen Options" tab.

Probably the checkbox to display the relevant box in the editor is not checked.

But sometimes your theme does not support featured images. Or only for posts and pages. In that case you must manually add this support for events.

= Why no Advanced Custom Fields field group in the editor? =
When using the block editor, click the options icon and select "Preferences".

When using the classic editor, click the "Screen Options" tab.

Probably the checkbox to display the relevant box in the editor is not checked.

= Why does my RSS or iCal feed not refresh? =
When visiting your feed via the subscription URL and feed is outdated, empty your browser cache.

If you're using the default RSS widget you can force a refresh via Settings > Reading, by changing the number of items in the feed.

= Why is there no semantic versioning? =
The version number won't give you info about the type of update (major, minor, patch). You should check the changelog to see whether or not the update is a major or minor one.

= How can I make a donation? =
You like my plugin and want to make a donation? There's a PayPal donate link at my website. Thank you!

= Other questions or comments? =
Please open a topic in the WordPress.org support forum for this plugin.


== Changelog ==
= Version 17.3 =
* Updated block code

= Version 17.2 =
* Updated block code
* Minor changes in code

= Version 17.1 =
* Fix: iCal feed

= Version 17.0 =
* Replaced date() with gmdate()
* Updated block code
* Minor changes in code

= Version 16.9 =
* Minor changes in code

= Version 16.8 =
* Fix: deprecated element in block code
* Updated block code
* Minor changes in code

= Version 16.7 =
* New: VS Event List block!
* Block editor users can now replace their shortcode block with the VS Event List block
* Many thanks to Craig from Roundup WP
* Minor changes in code

= Version 16.6 =
* Minor changes in code

= Version 16.5 =
* Fix: mistake in main plugin file
* Previous version causes fatal error in some cases

= Version 16.4 =
* Changed CSS class of the event info container
* Class "vsel-image-info" becomes "vsel-info-block"
* Added 2 CSS classes for alignment: "vsel-left" and "vsel-right"
* Removed old alignment classes (these classes end with left and right)
* Because of these changes you may have to clear your browser cache
* Updated stylesheet
* Minor changes in code
* Textual changes

For all versions please check file changelog.


== Screenshots ==
1. Shortcode event list (GeneratePress theme)
2. Shortcode event list (GeneratePress theme)
3. Widget event list (GeneratePress theme)
4. Single event (GeneratePress theme)
5. Events page (dashboard)
6. Single event (dashboard)
7. Widget (dashboard)
8. Settings page (dashboard)
9. Settings page (dashboard)
10. Settings page (dashboard)
11. Settings page (dashboard)
12. Settings page (dashboard)
13. Settings page (dashboard)
14. Settings page (dashboard)
15. Settings page (dashboard)