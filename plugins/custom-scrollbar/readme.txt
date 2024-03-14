=== Custom Scrollbar ===
Contributors:       Michael Uno, miunosoft
Donate link:        http://en.michaeluno.jp/donate
Tags:               scroll, scrollbar, scrollbars, scroll bar, scroll bars, appearance, custom scrollbar, custom scrollbars, custom scroll, custom scroll bar, custom scroll bars, responsive
Requires at least:  3.4
Tested up to:       5.7.2
Stable tag:         1.3.8
License:            GPLv2 or later
License URI:        http://www.gnu.org/licenses/gpl-2.0.html

Adds a custom scrollbar to specified HTML elements.

== Description ==

<h4>Change the Look of Too Long Vertical Elements width a Scrollbar</h4>
Do you have too long elements sticking out and breaking the web site layout? 

If you have such a problem, fix their height by adding a scrollbar to the element with this plugin.

<h4>See How a Scrollbar is Created</h4>

[youtube https://www.youtube.com/watch?v=_MRQOXW1UTU]

After installing it, go to `Dashboard` -> `Appearance` -> `Scrollbars`. Set a selector and height. That's it.

Notes: you need a basic understanding of CSS/jQuery selectors to use this plugin.

<h4>Multiple Selectors</h4>
By specifying selectors, you can define multiple scrollbars.

<h4>Responsive</h4>
Supports responsive design by defining the range of browser screen widths for the scrollbar to appear.

<h4>Custom Colors</h4>
Pick custom colors for the scrollbar elements.

<h4>Custom CSS</h4>
Define custom CSS rules.

<h4>Predefined Styles</h4>
Pick you favorite style from a list.

<h4>Ajax Page Load Handling</h4>
Even if the elements is inside an element which loads with Ajax, your scrollbars will be initialized accordingly.

<h4>Supports Responsive Design</h4>
If you want to disable your scrollbars in certain screen widths, you can do so as well.
  
= Supported Language =
* English
* Japanese

== Installation ==

1. Upload **`custom-scrollbar.php`** and other files compressed in the zip folder to the **`/wp-content/plugins/`** directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Go to **Dashboard** -> **Appearance** -> **Scrollbars**.
1. Configure the options by setting a selector and height.

== Screenshots ==

1. **Front-end**
2. **Setting Page**

== Changelog ==

= 1.3.8 - 07/09/2021 =
- Fixed a bug that caused a PHP syntax error in PHP below 7.3.

= 1.3.7 - 07/09/2021 =
- Fixed dynamic UI elements which did not toggle the visibility.
- Fixed JQMIGRATE warnings in the browser console.

= 1.3.6 - 03/01/2021 =
- Fixed an incompatibility issue with PHP 8.0, which the setting UI did not load.
- Fixed an incompatibility issue with WordPress 5.6 which comes with jQuery 3.5.

= 1.3.5 - 11/19/2020 =
- Added a custom JavaScript event that is triggered right after a scrollbar is initialized.
- Fixed some non-sanitized raw HTTP request arrays handled when the setting form is submitted.

= 1.3.4 - 11/06/2020 =
- Tweaked the style of some UI elements.
- Fixed a bug of double slashes on resource URLs.
- Fixed a bug that scrollbar options were not passed properly when the target element is not found.
- Fixed a bug that the default options were not applied properly.

= 1.3.3 - 08/19/2020 =
- Fixed an incompatibility issue with WordPress 5.5 regarding radio input buttons.

= 1.3.2 - 01/10/2019 =
- Added the Japanese translation.
- Added the default language template file.

= 1.3.1 - 12/27/2016 =
- Fixed a bug that debug log was shown in the browser console.

= 1.3.0 - 12/16/2016 =
- Added the `Scroll Buttons`, `Keyboard`, `Mouse Wheel` options.
- Changed the `Ajax Handling` option in the `General` setting section by moving it to each scrollbar option item.
- Deprecated the ability of creating multiple scrollbars.

= 1.2.0 - 10/10/2016 =
- Added the `Ajax Handling` option that determines whether to initialize plugin scripts on Ajax page loads.
- Added the ability to export and import settings.
- Refined the setting pages.

= 1.1.7 - 07/07/2016 = 
- Tweaked the setting UI to improve the usability.

= 1.1.6 - 01/06/2016 =
- Added the minified version for the enabler script.
- Improved performance of the setting page.

= 1.1.5 - 12/15/2015 =
- Fixed an issue with jQuery v2.1.4.

= 1.1.4 - 11/27/2015 =
- Tweaked the setting pages.

= 1.1.3 - 11/05/2015 =
- Changed the required WordPress version to 3.4.
- Fixed a bug that settings were not saved properly when removing a scrollbar definition item.

= 1.1.2 - 10/29/2015 =
- Tweaked the setting pages.
- Fixed a bug that percentages for width and height could not be set.

= 1.1.1 - 10/20/2015 = 
- Tweaked the setting pages.
- Updated the [Admin Page Framework](https://wordpress.org/plugins/admin-page-framework/) library.

= 1.1 - 08/29/2015 =
- Added the ability to set inline CSS rules wit the `Inline CSS` option.
- Tweaked the styling of setting forms.
- Fixed a bug that the `Width` option did not take effect.

= 1 - 07/05/2015 =
- Released. 