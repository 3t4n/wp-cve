=== Controlled Admin Access ===
Contributors: waseem_senjer, wprubyplugins
Donate link: https://wpruby.com
Tags: access, Access Manager, admin, capability, page, Post, role, user, widget
Requires at least: 4.0
Tested up to: 6.4
Stable tag: 2.0.15
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Give a temporarily limited admin access to themes designers, plugins developers and support agents.

== Description ==

Give a temporary limited admin. access to themes designers, plugins developers and support agents.

The plugin is simple and clean, it helps the administrator to create a user with a temporary access and choose which pages in your admin area which you don't want the user to access. send the details to the user and when he finished his task, you can easily deactivate the account and activate it later.

* [Upgrade to Pro Now](https://wpruby.com/plugin/controlled-admin-access-pro?utm_source=lite&utm_medium=readme&utm_campaign=freetopro "Upgrade to Controlled Admin Access Pro")
* [Documentation](https://wpruby.com/knowledgebase_category/controlled-admin-access-pro/ "Documentation ")


## Features

### Menu Filter
The plugin will allow you to select admin menu items that you want to restrict for the created admin. Not only the plugin will hide the menu item from the admin but it also will block the page if they access it in some other way.

### Expiration Time
You may don’t want to give access indefinitely, the plugin allows you to set an expiration time for the restricted admin account. After the account expires, the account will no longer be able to login into the admin dashboard. Moreover, you can always extend the expiry time or change it.

### Hide Admin Bar
WordPress offers an admin bar to provide quick access to some pages or to perform some actions. Using the plugin, you can hide the admin bar links at the top of the page will be hidden in both the frontend and admin areas.

### Disable Access
You can always disable the restricted admin account at any time. For example, if you gave a developer access to fix a bug or install a theme, when they finish the task you can disable their account. This will block login in using the account but it will retain the account’s information in case you wanted to give them access in the future.

## Pro Features
### Plugins Internal Pages
Take more control and restrict access to plugins’ internal pages. For example, you would like to give access to the WooCommerce Settings page, but you do not want the account to see the Payments Gateways tab. Currently, the plugin supports WooCommerce, Easy Digital Downloads and BuddyPress. In the future, we will add support for more plugins.

### No Password Login
Add some convenience when sending access to the user, you can generate a secure login URL for the user, and the user will use the link to login into the dashboard without the need for a password. You can also disable login by a password for restricted admins, this will restrict the admin from login in using a password or sending a reset password email.

### Activity Log
Keep track of what restricted admins have done while logged in, the plugin will log more than 20 actions such as activating/deactivating/deleting a plugin, switching a theme, deleting a theme, exporting data, publishing/deleting a post and uploading a file.

### Remote Logout
At any given time, you can force logging out any restricted admin if you no longer need them logged in the admin dashboard. This action will log them out on all logged-in devices and locations.


== Installation ==

1. Upload `controlled-admin-access` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In the dashboard, click on `Users` then `Controlled Admin Access`

== Screenshots ==

1. Add User Page
2. Manage Users Page
3. Settings Page

== Changelog ==
= 2.0.15 =
Fixed: PHP deprecation warning.

= 2.0.14 =
Adding WordPress 6.4 compatibility

= 2.0.13 =
Adding WordPress 6.3 compatibility

= 2.0.12 =
Fixed: bulk actions for standard admins.

= 2.0.11 =
Adding WordPress 6.2 compatibility

= 2.0.10 =
Removed the legacy admin menu in Users => Controlled Admin Access.

= 2.0.9 =
Fixed: blocking options.php POST requests were preventing other plugins from saving options.

= 2.0.8 =
* Fixed: A migration issue that was causing the updating user process not working.

= 2.0.7 =
* Fixed: parent_file filter was missing a parameter that prevents correct menu filtering.
* Fixed: prefix ajax actions to prevent any collisions.

= 2.0.6 =
* Fixed: Docs link in the plugin's page was incorrect.

= 2.0.5 =
* Added: Prevent restricted admins from editing the plugin's code in the Plugins Editor.

= 2.0.4 =
* Fixed: PHP warning in migrations.

= 2.0.3 =
* Fixed: Restricting main admin when creating a user.

= 2.0.2 =
* Added: A button to reset the user restrictions.

= 2.0.1 =
* Fixed: Unused class declaration has been removed.

= 2.0.0 =
* Complete redesign of the plugin's UI.
* Added: Expiration times: One hour, One Month and One Week.

= 1.5.10 =
* FIXED: Admin bar was visible for non logged users.

= 1.5.9 =
* ADDED: The admin bar in frontend and admin area is hidden from created users.
* FIXED: some plugins settings pages were not accessibly.

= 1.5.8 =
* FIXED: The plugin should not redirect super admin on login.

= 1.5.7 =
* FIXED: Access to the settings page for an uncontrolled admin user.

= 1.5.6 =
* FIXED: security issues that can grant the created admin uncontrolled access.

= 1.5.5 =
* FIXED: Allow access to customization.

= 1.5.4 =
* FIXED: Editing and Deleting users links were not working.

= 1.5.3 =
* FIXED: Complete sanitizing user inputs in the admin page.

= 1.5.2 =
* FIXED: Sanitizing user inputs in the admin page.

= 1.5.1 =
* ADDED: Add 15 days option to the User Expiry period.

= 1.5.0 =
* FIXED: Add Theme Customize, and Options pages to the not allowed pages.

= 1.4.0 =
* ADDED: Allow access to the Plugins editor.

= 1.3.9 =
* FIXED: If the user does not have access to the Dashboard page, he will be redirected to his profile.
* FIXED: deleting empty CSS and JS files.

= 1.3.8 =
* FIXED: Users with unexpired login were able to login again even after deactivation.

= 1.3.7 =
* FIXED: The Your Profile access  was not saved correctly.

= 1.3.6 =
* FIXED: Redirect to the first accessible page. 404 not found pages were fixed.

= 1.3.5 =
* FIXED: ignore menu items of the customizer to avoid menu duplication.

= 1.3.4 =
* FIXED: empty user password should not be processed when user is being updated

= 1.3.3 =
* FIXED: make activate link green only for the plugin users table

= 1.3.2 =
* FIXED: remove warning message for defining scalar constants for php less than 5.6

= 1.3.1 =
* FIXED: Users were prevented from accessing post type pages.

= 1.3.0 =
* FIXED: Restrict users from editing any other user.

= 1.2.0 =
* ADDED: Redirect the user to the first accessible page after login

= 1.1.1 =
* FIXED: Add backward compatibility for PHP 5.x

= 1.1.0 =
* ADDED: Allow the access to the Plugins page.

= 1.0.6 =
* ADDED: Spanish language translations.

= 1.0.5 =
* FIXED: Users could access the Plugins page by typing the URL in the browser.

= 1.0.4 =
* FIXED: Deactivating users bug.
* FIXED: PHP warning when slecting user roles.

= 1.0.3 =
* FIXED: Users and Plugins prevent access when the user has been edited.
ADDED: Our Blog posts widget.

= 1.0.2 =
* FIXED: Users and Plugins prevent access.
* FIXED: Javascript conflict.
* FIXED: Display the menu items title.


= 1.0.0 =
* Initial Release
