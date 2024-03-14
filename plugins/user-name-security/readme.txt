=== SX User Name Security ===
Plugin Name:  SX User Name Security
Version:      2.4
Plugin URI:   https://www.seomix.fr
Description:  Prevents WordPress from showing User login and User ID. User Name Security filters the body_class function, User Nicename, Nickname and Display Name in order to avoid showing real User Login to everyone.
Usage: No configuration necessary. Upload, activate and done.
Availables languages : en_EN, fr_FR
Tags: security, wordpress security, secure, security plugin, security, users, body_class, nicename, display name
Author: Confridin
Author URI: https://www.seomix.fr
Donate link: https://www.seomix.fr/dons/
Contributors: Confridin, juliobox, secupress, seomix
Requires at least: 4.6
Text Domain: user-name-security
Tested up to: 6.4.2
Requires PHP: 5.2.4
Stable tag: trunk
License: GPL v3

SX User Name Security prevents WordPress from showing your real Login everywhere. It ovverides the body_class function, User Nicename, Nickname and Display Name.

== Description ==

WordPress show your WordPress login and ID in several places. It's time to fix this !

- WordPress automaticaly uses "User login" to fill in the "User Display Name".
- WordPress also allows everyone to use the same value for Nickname, Display Name and Login.
- The body_class function also shows to everyone your User ID and Login on author pages.

A hacker can easily see then use your "NickName" or "Display Name" to find your real login. Once activated, SX User Name Security will prevent WordPress from showing those informations, and will warn you if you need to fix old users.

***Features***

**Body_class function:**

* Removes User ID from body_class function (front-end users pages)
* Removes User Nicename from body_class function (front-end users pages)

**Current User informations:**

* The plugin changes "Display Name" and "Nickname" to a random value (like 'Ticibe T. Aduvoguripe', 'Lagubo N. Agigerovibe' or 'Datela N. Orejadavino') if they are identiqual to user login
* If not, it changes "Display Name" to "Nickname" or "Nickname" to "Display Name" if one of them is identiqual to user login

**New Registration:**

* Display Name and Nickname are changed to random value during user registration.
* Nicename is also changed (it's used to generate the user permalink on the front-end). For previous user, a notice has been added to use another plugin to safely change old nicenames.

**Other information:**

All functions are translated into french and english.

You can find me here on <a href="https://www.seomix.fr">SeoMix</a>, and here is the official french post about this plugin <a href="https://www.seomix.fr/user-name-security/">https://www.seomix.fr/user-name-security/</a>

Find here our other plugins:

* <a href="https://fr.wordpress.org/plugins/seo-key/">SEOKEY WordPress SEO plugin</a>
* <a href="https://fr.wordpress.org/plugins/secupress/">SecuPress Security plugin</a>

== Installation ==

Upload and activate the plugin.

A notice and a button will be displayed to handle all users in order to hide their logins. Then, SX User Name Security will prevent WordPress from ever showing login and ID informations.

== Screenshots ==

1. "SX User Name Security" hides your author nicename and ID (body_class function).
2. When a user Nickname or Display Name are identiqual to Login, the plugin uses a random value instead.
3. During registration, WordPress won't use the same Display Name and Login for new users : "SX User Name Security" uses a random value.
4. An administrator is able to secure all users at once 

== Changelog ==

**2024/01/06 - v.2.4**

* Major performance improvements, especially on websites with many users
* Minor Coding standards fixes

**2020/09/21 - v.2.3.2**

* Required version bumped to 4.6
* Updated plugin text domain

**2017/11 - v.2.3.1**

* Tested up to WordPress 4.9
* Improving warning information on admin pages
* Code cleaning

**2014/09/30**

* Major fix for the "fix username" button : it will no longer generate 404 error pages (but people will still be able to guess your real login with your author URL).
* Add a column in the admin user list showing "Display Name" for every user.
* Add a better explanation about the "Fix Username" button.

**2014/06/15**

* Minor fix for the admin profil URL link.

**2014/04/01**

* Add a button (JS only) to handle all users created before the plugin installation.
* Code improvements

**2014/03/26**

* Code improvement
* Bug fixes (in some cases, user "Display Name" and "Login" were not modified)
* Now an administrator can also trigger every function by saving a user (you don't have to wait every user to log-in)
* New alerts (admin notices)

**2013/03/08**

* first release

== Frequently Asked Questions ==

= Do I need to do anything else for this to work? =

Yes : just visit the admin user page to see if you have to modify some of your users.

It's also better to use SF Author URL Control (http://wordpress.org/plugins/sf-author-url-control/) combined with this plugin to also change current author permalinks (in order to hide login information).