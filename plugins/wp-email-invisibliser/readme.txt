=== WP Email Invisibliser ===
Contributors: adamsargant
Donate link: http://www.sargant.net/projects/wordpress-plug-ins/
Tags: email, munge, mangle, obscure, antispam, spam
Requires at least: 2.0.2
Tested up to: 3.4.2
Stable tag: 0.1.2

A simple plugin to hide emails from spambots. Simply use the shortcode [hide_email myemail@mydomain.com] to hide myemail@mydomain.com from harvesters but create a clickable email link.

== Description ==

A simple plugin to hide emails from spambots. Simply use the shortcode [hide_email myemail@mydomain.com] to hide myemail@mydomain.com from harvesters but create a clickable email link.

There is no complex encryption, that isn't the purpose of this plugin. It simply removes readable emails from the html outpout, replaces them with a span element that uses the hex converted email address in a class and then replaces that span element with javascript on the fly to create a clickable link. If the viewers browser is not javascript enabled they will see a short message explaining why they can't see the email.

== Installation ==

1. Upload the entire WP_Email_Invisibliser folder to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Changelog ==

= 0.1.2 =
* Replace <div> output with <span> output to allow user more flexibility (update may cause current uses to change from appearing on a new line to appearing inline but will result in valid html)

= 0.1.1 =
* Bugfix

= 0.1.0 =
*first commit