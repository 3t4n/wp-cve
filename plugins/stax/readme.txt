=== STAX Header Builder ===
Contributors: staxwp, SeventhQueen, geowrge
Tags: header builder, header edit, frontend editor, page builder, drag-and-drop, visual editor, wysiwyg, design, sticky header, slide-up header, transparent header
Requires at least: 5.0
Tested up to: 5.9
Requires PHP: 7.0
Stable tag: 1.3.6
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

A header builder that works with any theme. Front-end drag&drop interface to create pixel perfect headers with ease.

== Description ==

Stax is a front-end drag & drop visual header builder plugin for WordPress enabling the creation of page headers in a live, visual way. This lets you focus on the look and design of your page headers, instead of tripping over the behind-the-scenes mechanics of building headers.
Check out **[Stax Builder demos](https://staxbuilder.com/?utm_source=wp-repo&utm_medium=link&utm_campaign=readme)**.

= Works with any theme =
Yes that is right, it doesn't matter the theme you are using. Even more cool is that you can change the theme and you can keep your built header.

= Mobile/Tablet/Desktop different content & settings =
You won't see this in any other builder. Just an example is to have your logo in one place on desktop and centered on mobile. You can change the content just on a specific resolution if you like or just the settings of an element. How cool is that?

= Live design - like it should be =
See live how your header looks, right at the moment of edit. Easily switch from desktop, tablet or mobile view

= Unlimited headers =
For real, you can have as many headers on your site. Change background, border, typography, make'em sticky, boxed, full-width, custom height, custom width.

= Deleted items history =
Removed an elements you now want back? Say no more, Deleted Items to the rescue

= STAX Builder Pro. Create a really professional header =
Header resize, Header Slide up, Header transparent. Different logo for resize and transparent states.
Templates.
Import/Export Headers.
Saved Elements.
Page level header.
And many more.

= Free widgets .. for now =

- **Logo**. Add a logo with lots of options to style.
- **Menu**. Add your existing Wordpress menus to the header.
- **Search**. Add a WordPress search
- **Button**. Create an eye-catching button
- **Icon**. Add an icon and play with the cool options
- **Text**. A WYSIWYG text editor, just like the WordPress editor.
- **Image**. Control the size and other settings of images.
- **Link**. Add a quick link
- **Separator**. Use it to nicely separate header elements
- **Accordion**. Used for the Content area, easily create content that expands
- **Tabs**. Easily create tabbed content
- **Divider**. Separate content with the help of this element
- **Google Map**. App a Google map to your page
- **Heading**. Styled text with lot of options and typography.
- **Spacer**. Adds space between content elements

- **And counting...**

= Documentation and Support =
- For documentation and tutorials go to our [Documentation](https://docs.staxbuilder.com/?utm_source=wp-repo&utm_medium=link&utm_campaign=readme).
- If you have any more questions, visit our support on the [Plugin's Forum](https://wordpress.org/support/plugin/stax).
- For more information about features check out our website at [STAX Builder](https://staxbuilder.com/?utm_source=wp-repo&utm_medium=link&utm_campaign=readme).

= Do you like STAX Builder? =
- Join our [Facebook Group](https://www.facebook.com/groups/Stax/).
- Join our [Spectrum Community](https://spectrum.chat/stax).
- Learn from our tutorials on [Youtube Channel](https://www.youtube.com/channel/UCPhNdFwX254P98QzDU5B-ww).
- Or rate us on [WordPress](https://wordpress.org/support/plugin/stax/reviews/?filter=5/#new-post) :)

= Minimum Requirements =

* WordPress 4.6 or greater
* PHP version 5.4 or greater
* MySQL version 5.0 or greater

= We recommend your host supports: =

* PHP version 7.1 or greater
* MySQL version 5.6 or greater
* WordPress Memory limit of 64 MB or greater (128 MB or higher is preferred)

= Installation =

1. Install using the WordPress built-in Plugin installer, or Extract the zip file and drop the contents in the `wp-content/plugins/` directory of your WordPress installation.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to your site in front-end.
4. Press the 'Edit with Stax' button found at bottom-right of the page.

For documentation and tutorials visit our [Knowledge Base](https://docs.staxbuilder.com/?utm_source=wp-repo&utm_medium=link&utm_campaign=readme).

== Frequently Asked Questions ==

**What does this plugin actually do?**

You can use STAX to design and build your site header on any theme, all in front-end while you see it live how it looks.

**Will Stax work with my theme?**

Yes, you can use it on any theme. The first time you open Stax you will just have to select your current header area to replace.

== Changelog ==

= 1.3.6 - 2022-02-23 =
* Update SDK framework

= 1.3.5 - 2022-02-16 =
* Add filter for the generated content to be used by developers('stax_the_zone_html')

= 1.3.4 - 2021-02-05 =
- Fixed Javascript error in editor
- Udate Freemius SDK

= 1.3.3 - 2019-10-28 =
- Fixed Editor color picker changing color

= 1.3.2 - 2019-08-29 =
- Fixed some errors in editor

= 1.3.1 - 2019-08-29 =
- Code optimizations.
- Editor tooltips position fix
- Fix errors on save
- Fix zone ordering and element orders on save
- Added theme compatibility options to predefine header tags in different themes.

= 1.3.0 - 2019-07-25 =
- NEW FEATURE: Added right click context menu to easily change elements
- NEW FEATURE: Keyboard shortcuts.
- Fixed menu alignment in section
- Improved code for faster loading interface, added code optimization and refactoring

= 1.2.4 - 2019-06-14 =
- Added notice of empty zone in front-end for site admins
- Fix for Internet Explorer 11

= 1.2.3 - 2019-03-25 =
- New option for Search element. Form action can now be customized
- Fixed font selector dropdown background.
- Fixed front-end loading font when no font was selected.

= 1.2.2 - 2019-03-22 =
- Fix templates preview

= 1.2.1 - 2019-03-21 =
- Fix missing database column on fresh install
- Remove database tables and data on plugin uninstall

= 1.2 - 2019-03-15 =
- Improvements and added hooks and filters for developers
- Fix footer zone rendering
- PRO container elements. NEW SideMenu element

= 1.1.6 - 2019-02-26 =
- NEW FEATURE: Burger menu align option: Center or Left
- Responsive fixes and theme compatibility

= 1.1.5 - 2019-02-21 =
NEW FEATURE: Duplicate current zone and make it show only on current page.
NEW FEATURE: Ability to delete default defined zones and to rename them.
- Added Edit Header action in admin post & page list
- Added small intro tour on Edit interface

= 1.1.4 - 2019-02-19 =
IMPROVEMENT: Tooltips added for certain actions
FIX: Menu walker title escape

= 1.1.3 - 2019-02-14 =
- Fixed z-index in editor on color picker and dropdowns.
- Added some filters and hooks to help developers
- Enhanced zones conditional display logic
- Changed menu dropdown caret with css symbol

= 1.1.2 - 2019-01-25 =
- Fixed template imports
- Fixed zones conditions so they display correct based on their settings

= 1.1.1 - 2019-01-24 =
- Fixed a missing file from the framework

= 1.1.0 - 2019-01-23 =
- Fixed CSS generation for Desktop of some isolated cases
- Burger menu color fix
- Fixed section initialisation when no elements could be dragged.

= 1.0.9 - 2019-01-17 =
- Extra check for flexMenu functionality in Javascript.
- Elements now move independent on resolutions.
- Fixed some CSS rendering on desktop
- Updated Material Design font icons
- Fixed importer for elements on mobile/desktop

= 1.0.8 - 2019-01-08 =
- Elements now move independent on resolutions.
- Refresh and "Apply to desktop" buttons have been added for each different settings on mobile&tablet.
- Theme compatibility added for themes that want to make a better integration with Stax.
- Fixed image height on sections

= 1.0.7 - 2018-12-11 =
- Fixed zone selector saving and adding extra checks on front-end

= 1.0.6 - 2018-10-17 =
- Fixed saving data on some isolated server environments.

= 1.0.5 - 2018-10-10 =
- Removed Start editor button in front. Added it in admin bar. Also other fixes.

= 1.0.4 - 2018-09-27 =
- Applied a Safari browser fix on load.

= 1.0.3 - 2018-09-14 =
- Multi zone support. You can now add multiple zones to edit on your site. By default Stax defines Header & Footer to build but you can add unlimited zones

= 1.0.2 - 2018-05-10 =
- Header replace if front-end is now disabled by default.

= 1.0.1 - 2018-05-04 =
* Fix for full-width dropdown menu at mobile.
* Wordpress admin bar fix for sticky headers on mobile.

= 1.0 - 2018-05-04 =
* Initial Public  Release
