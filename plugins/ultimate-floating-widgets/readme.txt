# Ultimate Floating Widgets - Make popup sidebars
Contributors: vaakash
Author URI: https://www.aakashweb.com/
Plugin URI: https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/
Tags: popup, widget, sidebar, sticky, fixed sidebar, modal, floating, fixed, sticky sidebar, floating sidebar, fixed widget, popup widget, sticky widget, one column
Donate link: https://www.paypal.me/vaakash/
License: GPLv2 or later
Requires PHP: 5.3
Requires at least: 4.6
Tested up to: 6.4.3
Stable tag: 2.8

Create sticky / fixed / popup bubble and flyout sidebars and add your widgets to it.



## Description

Ultimate floating widgets plugin allows you to add floating widgets to your site. If your theme does not have a sidebar or wish not to have a sidebar but still have widgets then with this plugin you can add a floating sidebar with widgets in it.

This sidebar/widgets (widget box) will be in a collapsed state and users can expand it by clicking on a floating button. There are different types and positions for the widget box like "popup bubble" and "Flyout"

[**View live demo**](https://wpdemos.aakashweb.com/ultimate-floating-widgets/?utm_source=readme&utm_medium=description&utm_campaign=ufw-pro)

### 🚀 Make Widgets Popup

1. Create a floating sidebar (widget box)
1. Configure it as a "Popup bubble" or a "Flyout" sidebar from plugin settings page.
1. Add your WordPress widgets to it.
1. Voila !
1. You have your favorite widgets floating/sticking to the page corner/sides which users can click and open.

### ✨ Features

Ultimate Floating Widgets is a unique plugin helping users to place widgets in floating sidebars/widget boxes. It has below features using which you can utilize the power of widgets on any theme, with or without a sidebar !

* Display widgets in **Popup/Flyout**
* Add any number of widgets to the popup.
* **Minimize** and open widget boxes with a button.
* **Unlimited** number of popups.
* Multiple **triggers** to open the floating sidebar.
* Show the floating widget box in 4 **corner positions**.
* **Saves** the popup open and closed state using cookies.
* **Customize** with colors, size, icons, styles and more.
* Options to hide the widget box in posts, pages, mobile devices.
* Mobile ready and **responsive**
* **Automatic** open/close on scrolling the page.
* Supports **all themes**.

### 🎲 Use cases

You can use Ultimate floating widgets plugin for multiple purposes using the power of widgets. Some ideas and use cases are mentioned below,

* A sticky contact form widget added to the corner of the page.
* A floating feedback form widget.
* Floating widgets like search box, gallery, recent posts.
* Instagram/Facebook/Twitter/any social based widget can be added as a floating popup to the corner of the page.
* Literally any widget you would like to stay everywhere !

### 💎 PRO version

There is a PRO version where below advanced features are available to further enhance the popup widget experience.

* **Multiple columns** - With multiple columns feature you can add widgets to multiple columns inside one widget box

* **On show and idle animation** - Add an animation to the button when it is loaded on the page or when it is in idle state to grab user attention.

* **Advanced location rules** - Create custom complex rules to insert the widget box only in specific pages as required

* **Visitor conditions** - Target users based on conditions like referrer, browser, OS, device type, user login status, user role, number of times user has visited the site, number of times user has logged in and more !

[**More information**](https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/?utm_source=readme&utm_medium=description&utm_campaign=ufw-pro) - [Live demo](https://wpdemos.aakashweb.com/ultimate-floating-widgets/?utm_source=readme&utm_medium=description&utm_campaign=ufw-pro)

### Links

* [Home page](https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/)
* [Documentation](https://www.aakashweb.com/docs/ultimate-floating-widgets/)
* [Support Forum](https://www.aakashweb.com/forum/discuss/wordpress-plugins/ultimate-floating-widgets/)

Note: The plugin uses font-awesome icon font library to use as icons inside the buttons. You can also use custom image as icon.



## Installation

1. Extract the zipped file and upload the folder `Ultimate floating widgets` to to `/wp-content/plugins/` directory.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. Open the admin page from the "Ultimate floating widgets" link in the navigation menu.



## Frequently Asked Questions

### Can I add multiple floating widgets in a single page ?

Yes ! You can create multiple widget boxes and add your widgets to it and they will all be available in a single page with separate buttons to open each.

### Does it work in all themes ?

Yes. The floating widgets use the same structure/style as used by your theme. So if your theme sidebar is made as a floating popup.

### Can I show the widget box in the center of the page ?

No, that is not possible. Right now the widget boxes can be placed near the corners or to the sides of the window. You could use custom CSS (with flyout mode) to achieve the same but support for that would be beyond the scope of the plugin.

### Are widget boxes mobile responsive ?

Yes, widget boxes are responsive to small screens. Even if you provide a larger width/height for the widget box when the screen size is less than 600px then widget box will automatically switch to full screen mode.

### How to hide the widget on mobile devices ?

In Ultimate floating widgets settings page, go to the edit page of the widget box and switch to "Location rules" tab. Under this section select "On mobile devices alone".

### How to add custom CSS to alter the design of the popup/widget box ?

In widget box edit page, scroll down to "Widget template settings" and click to expand the section. In that section under "Additional CSS" option you can provide your custom CSS.

Please visit the [plugin documentation page](https://www.aakashweb.com/docs/ultimate-floating-widgets/) for complete list of FAQs.



## Screenshots

1. Sidebar widgets in a Popup bubble.
2. Widgets in a flyout type sidebar.
3. Multiple popup bubble sidebars.
4. Multiple popup bubble sidebars placed in the corners of the page.
5. List of widget boxes created.
6. Widget box edit page.
7. All supported widget box settings.
8. Widgets placed in a floating sidebar.



## Changelog

### 2.8
* Fix: Support for gradient background colors in buttons.
* Fix: Dedicated close button in popup is visible on small screens.
* Fix: Invalid CSS value for close button.
* Fix: Button has fade in effect when auto shown.
* Fix: External close button is hidden when flyout popup is in open state.

### 2.7
* New: Widget box animates when it is opened automatically.
* Fix: Widget box saved state not working when save duration is set to 0.
* Fix: Button icon is now aligned correctly in the center.
* Fix: Button with icon and text was not aligned properly.

### 2.6
* Fix: "Hide in pages" option hides the widget in the front page.
* Fix: Button flashes on page load when it is displayed on scroll.

### 2.5
* Fix: Initial state on mobile option not getting saved.
* Fix: Additional CSS was not inserted correctly.

### 2.4
* Fix: Enhancements to input and output data sanitization.

### 2.3
* New: Feature to save open or closed state of the widget box using cookies.
* Fix: Minor enhancements to admin form fields.

### 2.2
* New: Set different initial states for desktop and mobile.
* New: Verifying device type is not affected by caching plugins.
* New: Shortcodes are now supported in button texts.
* New: Widget settings can be tweaked using WordPress filters hook.
* Fix: Widget box was not fully scrollable when title is set.
* Fix: Minor rearrangements to admin form fields.

### 2.1
* New: Dedicated close button for the widget box.
* New: Option to automatically close widget box after some time.
* New: Enhancements to fields in admin settings.
* New: Widget box does not open automatically when it is closed manually.
* Fix: Support for WordPress 5.8

### 2.0
* New: Initial version



## Upgrade notice
No upgrade notice