=== Site PIN ===
Contributors: marcus.downing, diddledan
Tags: auth
Requires at least: 3.0
Tested up to: 3.9.1
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Prevent careless visitors by locking your site down with a PIN

== Description ==

Whenever a site is under development, or its content should remain private, you want to prevent to general public
from reading it - whether deliberately or by accident.

A comman way to do this is with [HTTP authorisation](http://httpd.apache.org/docs/2.4/howto/auth.html),
but this has some problems:

*   Each user of your site has two different sets of username and password, one for the authorisation and one for 
WordPress login. It's not uncommon for some users to find this confusing.
*   To set up authorisation you need access to your web server's configuration (at least using the `.htaccess` file), 
which may not be available depending on your hosting supplier.
You also need to learn how to configure the authorisation correctly, and risk breaking the site if you get it wrong.
*   When the time comes to remove the authorisation, you need to edit the configuration again.
*   In certain circumstances, combining authorisation with WordPress login can get a visitor into a circular redirect
loop, where the result of logging in is to redirect to the login page.

Site PIN solves these problems by replacing authorisation with a simple PIN. This has the following advantages:

*    Everybody knows what a PIN is, and it's clearly not the same as a password.
*    Logging into WordPress bypasses the PIN, so you can't lock yourself out
*    It's just a WordPress plugin, so no server configuration is necessary.
*    You can change or remove the PIN from WordPress admin.

== Installation ==

1. Upload the `site-pin` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Visit *Tools > Site PIN* to set up your PIN

== Frequently Asked Questions ==

= What if I lock myself out? =

If you log into WordPress, you don't need a PIN.

= Will Google, Bing and Yahoo index my site? =

No, search engines won't index the site while it's locked with a PIN.

= Does the PIN have to be four digits? =

No, it can be any number of digits. In fact it can use numbers, letters and punctuation like any password, 
but people are in the habit of thinking of a PIN as a few digits.

= What do I do if the wrong person has access to my site? =

Immediately change the PIN, and disable that person's user if they have one. And be more careful in the future!

Note that even the lowest level of user (typically Subscriber) still has access to the site, so you have to disable
somebody's account entirely to stop them logging in.

= Can I give people a hint? =

Yes, you can set a custom message to display on the PIN entry screen. But giving a hint can be dangerous 
because an attacker may be able to work it out. An example of a bad PIN would be something like "our address" or 
"the year the company was started" since that's information anybody could find out.
A better hint might be "the same as the PIN on the warehouse door" because only employees should know that.

= Who can change the PIN? Who can read the PIN? =

Only administrators can change the PIN. Any contributor can see the PIN.

If you want to adjust WordPress' permissions with code of your own, the ability to edit the PIN uses the
`edit_theme_options`
permission while the ability to read the PIN uses the 
`edit_posts`
permission.

== Screenshots ==

1. The PIN entry screen. Logging in is an alternative to knowing the PIN.
2. The settings page. You can set a message to display with the PIN.

== Changelog ==

= 1.3 =
* Improved redirection after PIN entry

= 1.2 =
* Fix for admin CSS in new versions of WordPress

= 1.1 =
* Added plugins page link to Site PIN settings
* Update to Bang standard visual style

= 1.0 =
* First version

