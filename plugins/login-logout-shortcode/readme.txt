=== Login Logout Shortcode ===
Contributors: prontotools, zkancs
Tags: login, logout, shortcode
Requires at least: 4.0
Tested up to: 4.9.6
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A single shortcode you can place anywhere to allow visitors to login/logout.

== Description ==

Use a simple, single shortcode to allow your visitors to login or logout depending on their current status.

The shortcode outputs an a href link for you to add in the header, footer or anywhere else on your website.

**Example Shortcode:**

`[login-logout text_to_login="Login" text_to_logout="Logout" class="custom-class" redirect="/shop/"]`

**Available Parameters:**

- **text_to_login** - The text to prompt logged-out users to log in.
- **text_to_logout** - The text to prompt logged-in users to log out.
- **class** - Any optional custom classes to be added to the link.
- **redirect** - An optional page to redirect to after logging in/out. If not included, users will redirect to whatever page they’re on when they click the link.
- **login_url** - An optional parameter that user can change the URL login page

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Include the shortcode anywhere on your website: `[login-logout text_to_login="Login" text_to_logout="Logout" class="" redirect=""]`
4. Enjoy!

== Changelog ==

= 1.1.0 =
* Add login URL parameter so that user can change the URL login page instead of login on WP admin page 

= 1.0.0 =
* First release!

