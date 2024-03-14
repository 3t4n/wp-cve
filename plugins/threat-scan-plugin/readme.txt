=== Threat Scan Plugin ===
Tags: Threats, Virus, Hacked, Scan, Malicious code
Requires at least: 3.0
Tested up to: 6.1
Contributors: Keith Graham
Donate link: https://www.kpgraham.com
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a very simple threat scan that looks for things out of place in the content directory as well as the database.

== Description ==

This is a very simple threat scan that looks for things out of place in the content directory as well as the database.

It searches PHP files for the occurrence of the eval() function, which, although a valuable part of PHP is also the door that hackers use in order to infect systems. The eval() function is avoided by many programmers unless there is a real need. It is sometimes used by hackers to hide their malicious code or to inject future threats into infected systems. If you find a theme or a plugin that uses the eval() function it is safer to delete it and ask the author to provide a new version that does not use this function.

When you scan your system you undoubtedly see the eval used in javascript because it is used in the javascript AJAX and JSON functionality. The appearance of eval in these cases does not mean that there is a possible threat. It just means that you should inspect the code to make sure that it is in a javascript section and not native PHP.

The plugin continues its scan by checking the database tables for javascript or html where it should not be found.

Normally, javascript is common in the post body, but if the script tag is found in a title or a text field where it does not belong it is probably because the script is hiding something, such as a hidden admin user, so that the normal administration pages do not show bad records. The scan looks for this and displays the table and record number where it believes there is something hinky.

The scan continues looking in the database for certain html in places where it does not belong. Recent threats have been putting html into fields in the options table so that users will be sent to malicious sites. The presence of html in options values is suspect and should be checked.

The options table will have things placed there by plugins so it is difficult to tell if scripts, iframes, and other html tags are a threat. They will be reported, but they should be checked before deleting the entries.

This plugin is just a simple scan and does not try to fix any problems. It will show things that may not be threats, but should be checked. If anything shows up you should try to repair the damage or hire someone to do it. I am not a security expert, but a programmer who discovered these types of things in a friend's blog. After many hours of checking I was able to fix the problem, but a professional could have done it faster and easier, although they would have charged for it.

You probably do not have a backup to your blog, so if this scan shows you are clean; your next step is to install one of the plugins that does regular backups of your system. Next make sure you have the latest Wordpress version.

If you think you have problems, the first thing to do is change your user id and password. Next make a backup of the infected system. Any repairs to Wordpress might delete important data so you might lose posts, and the backup will help you recover missing posts.

The next step is to install the latest version of Wordpress. The new versions usually have fixes for older threats.

You may want to export your Wordpress posts, make a new clean installation of Wordpress, and then import the old posts.

If this doesn't work it is time to get a pro involved.

A clean scan does not mean you are safe. Please do Backups and keep your installation up to date!


== Installation ==
1. Download the plugin.
2. Upload the plugin to your wp-content/plugins directory.
3. Activate the plugin.
4. There are no options. Clicking on the Settings link will perform the scan.

== Changelog ==
= 1.3 =
* Updated for newer versions of WordPress 5.7.1 and PHP

= 1.2 =
* Updated for newer versions of WordPress 5.4.1 and PHP

= 1.1 =
* Updated for newer versions of WordPress and PHP

= 1.0 =
* Added more detailed information
* Confirmed compatibility with Wordpress 3.5

= 0.9 =
* Fix small errors and compatibility with Wordpress 3.0

= 0.8 =
* First test release



== Support ==
This plugin is in active development. All feedback is welcome on "<a href="https://www.facebook.com/BlogsEye/" title="Wordpress plugin: Threat Scan Plugin">program development pages</a>".
