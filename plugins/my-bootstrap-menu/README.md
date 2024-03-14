# My Bootstrap Menu
Contributors: codetoolbox, Michael Carder
Author URI: http://www.michaelcarder.com
Plugin URI: http://www.codetoolbox.net/wordpress/wordpress-plugins/my-bootstrap-menu/
Tags: bootstrap, navbar, nav-menu, menu, submenu, drop-down submenu, responsive, mobile, menu walker, button, tabs, pills, alignment, inverse, logo, title, login, register, image select, my plugin settings
Requires at least: 4.0
Tested up to: 4.7.5
Stable tag: 1.2.1
Header tagline: Customizable plugin that applies the Bootstrap formatting classes to any Wordpress Navigation Menu
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

## Description 
My Boostrap Menu is a fully customizable plugin that applies the Bootstrap formatting classes to any Wordpress Navigation Menu. [Bootstrap](http://getbootstrap.com/components/#navbar) provides responsive menus for phones, tablets and desktops.

The plugin allows the user to select a menu by name, or by selecting your theme's menu location. This gives extreme flexibility for theme developers, allowing multiple menus to be formatted as bootstrap menus- e.g. one fixed at the top and another at the bottom of the screen!

Works with all levels of Sub-menus! These were removed from Bootstrap in version 3, however we have included the original styles and each level will be correctly styled.

'My Boostrap Menu' is built on the ['My Plugin Settings'](http://www.codetoolbox.net/wordpress/wordpress-plugins/my-plugin-settings/) framework which creates simple and intuitive user interfaces for input forms; including media dialogues and the popup selection of [Bootstrap Glyphicons](http://glyphicons.com/).

More information here: [My Bootstrap Menu](http://www.codetoolbox.net/wordpress/wordpress-plugins/my-bootstrap-menu/)

## Features 
Fully customizable labels, logos, icons, formatting and alignment make this the most user and developer friendly menu tool. Additional styles have been included to tweak basic formatting issues when using Bootstrap navbars with Wordpress. Each Menu can be Bootstrapped and configured with settings:
* Choose your menu type
    * Navbar
    * Tabs
    * Pills
    * Buttons
    * Button Group
* Select a menu location
    * Fixed to the top of the screen
    * Fixed to the bottom
    * Static Top
* Choose to display:
    * Title
    * Logo
    * Login, Register
    * Search
* Upload a custom Logo
    * Optional logo for the Mobile version
* Custom Search box
    * Select a Glyhpicon, button type and labels
    * Choose default text for the search input box
* Custom Login and Register buttons
    * Select Glyphicons and label text
* Formatting
    * Align any menu elements to the left or right!
    * Customize the button menu style
    * Customize Sub-menu dropdowns and links
* Advanced Options!
    * Optional container classes
    * Include Wordpress default classes or not
    * Optional fixes for fixed top menus
* All files included
    * Select whether to load the included versions of Boostrap and custom css.

## Installation 

**Requires PHP 5.3+**

My Bootstrap Menu can be installed in a few easy steps:

#### Either:

1. Search for the 'My Bootstrap Menu' plugin via Wordpress Admin > Plugins > Add New
2. Install with the 'Install Now' button

#### Or:

1. Unzip "My Bootstrap Menu" and put all files into a folder like "/wp-content/plugins/my-bootstrap-menu/"
2. Activate the plugin through the 'Plugins' menu in WordPress

#### Then...

3. Inside Wordpress admin, go to Appearance > My Bootstrap Menu
4. Select which menu or location to Bootstrap and select the checkbox: 'Bootstrap this menu'
5. Configure any other settings
6. Save and go to your site to see your new Menu!

## Frequently Asked Questions 
### Where can I find the settings for each menu? 
All settings are managed in Wordpres Admin > Appearance > My Bootstrap Menu

### Why isn't my menu being bootstrapped? 
There are a couple of things to make sure of:
1. The checkbox 'Bootstrap this menu' is checked for your menu or location
2. The settings are saved!
3. You are either loading the plugin's version of Bootstrap, or you have one loaded with your theme
4. The menu is being called by name correctly
5. The theme location is being called correctly in your theme files

### Does this plugin work with newest WP version? 
Yes, this plugin works fine with WordPress 4.3.1

## Screenshots 
1. The main settings page, showing the list of menus and the main tabs
2. A standard Bootstrap Nav Menu, with logo and multi-tiered sub-menus
3. Inverse Pills menu
4. Button Menu aligned right, and different glyphicons for search and logout
5. Mobile enabled responsive menu, features vertical button stacking.
6. Glyphicon Selection for Search, Login, Logout, and Register buttons
7. Select a logo to display on your menu

## Changelog 

### 1.2.1
Update to allow 'None' for internal container type. 
Also various formatting updates.

### 1.2.0
**POTENTIALLY BREAKING CHANGES**
Changed saved menu name to be slug format. You may need to reapply settings/logos etc.
Added Logo 'title' and 'alt' text fields.
Fix for selecting a Menu/Theme from drop-down.

### 1.1.2
Fix for uninstalling/deleting the plugin.

### 1.1.1
Minor fix for Admin site for Firefox 

### 1.1.0
**First major revision - ALWAYS CHECK BEFORE UPGRADING LIVE SITES**
Updated and checked for Wordpress 4.4 
Updated Internal Bootstrap version to 3.3.6.
Changed dropdown menu icons to use Glyphicons (i.e. use with Bootstrap)
Changed search field classes - improved appearance on mobile mode.
Removed on-hover sub-menu styles... this is to keep consistent with Bootstrap being mobile/tablet friendly.
Fixed submenu headings are links - requires clicking the caret for submenus.
Fixed Tab, Button, Button Group Menus to show active correctly.
Fixes for the Pills, Buttons, Button Group and Tabs with submenus on collapse.
Reduced margins on Tabs to show at the bottom of the navbar.
Removed duplicate class declaration on menu types.
Displays either 1) caret (or glyphicon) for top level menu and 2) right caret for submenus.
Fixes for php versions <5.3
My-Plugin-Settings-Helper - updated
 
### 1.0.6
Fix to use caret, instead of WP dashicons by default. Load Dashicons if selected.

### 1.0.5
Fixes for php versions <5.4. 
Possible fix for object instead of string for unique id. 
Added ability to select Dashicon for dropdown menu items.

### 1.0.4
Added 'active' class to all parent menu and submenu items.

### 1.0.3 
Fixes for Wordpress 4.3.0 compatibility. Menu names being returned as menu objects in the latest WP release.

###  1.0.2
Enhanced functionality for setting Main Menu Items as links when they have child/submenu items.
This will change the behaviour when selecting 'Submenu headings are links' so that submenus appear on mouse-over and menu items open on click.
(Thanks to David Woodward for highlighting this)
###  1.0.1 
Minor fixes for unique menu id, to remove non alpha-numeric characters.
### 1.0 
 First release!

## Upgrade Notice
###  1.0.2
Enhanced functionality for setting Main Menu Items as links when they have child/submenu items.
This will change the behaviour when selecting 'Submenu headings are links' so that submenus appear on mouse-over and menu items open on click.


## About us 
This plugin has been developed by [Michael Carder Ltd](http://www.michaelcarder.com/). It is is built on the ['My Plugin Settings'](http://www.codetoolbox.net/wordpress/wordpress-plugins/my-plugin-settings/) framework which creates simple and intuitive user interfaces for input forms; including media dialogues and the popup selection of [Bootstrap Glyphicons](http://glyphicons.com/).

['Contact us'](http://www.michaelcarder.co.uk/contact-us) for more information.
