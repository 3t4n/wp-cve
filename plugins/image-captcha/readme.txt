=== Image Captcha ===
Contributors: Captcha Soft
Tags: captcha, image captcha, captcha for webform, capcha, captha, catcha, spam, antispam, anti-spam, anti-spam security, captcha plugin, captcha words, comment, login, lost password, registration, security, spam protection, substract, web form protection, hacking, free
Requires at least: 3.5
Tested up to: 6.0.2
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An easy to use wordpress Captcha plugin to prevent spam on your site. You can use Image Captcha to protect comments and the admin panel.

== Description ==

Image Captcha plugin is a powerful captcha protection for WordPress login and comment forms. Image Captcha - it's free and easy to use tool that will protect you from spam in the comments and prevent hacking the admin panel. In order to post comments or login, users will have to enter the name of the object in the picture. This prevents spam from automated bots. You can also set a ban by ip-address after three wrong inputs.

= Features =

* Captcha on Login form
* Captcha on Register form
* Captcha on Lost Password form
* Captcha on Comment form
* Ban by ip-address after three wrong inputs
* An ability to add custom captcha images

= Translation =

* Russian (ru_RU)

= Technical support =

If you notice any bugs in the plugins, you can notify us about it and we'll investigate and fix the issue then. Your request should contain URL of the website, issues description and WordPress admin panel credentials.

== Installation ==

1. Upload the `captcha` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin via the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Missing CAPTCHA on the comment form? = 

You might have a theme where comments.php is not coded properly. 

Wopdpress version matters. 

(WP2 series) Your theme must have a tag `<?php do_action('comment_form', $post->ID); ?>` inside the file `/wp-content/themes/[your_theme]/comments.php`. 
Most WP2 themes already have it. The best place to put this tag is before the comment textarea, you can move it up if it is below the comment textarea.

(WP3 series) WP3 has a new function comment_form inside of `/wp-includes/comment-template.php`. 
Your theme is probably not up-to-date to call that function from comments.php.
WP3 theme does not need the code line `do_action('comment_form'`... inside of `/wp-content/themes/[your_theme]/comments.php`.
Instead it uses a new function call inside of comments.php: `<?php comment_form(); ?>`
If you have WP3 and captcha is still missing, make sure your theme has `<?php comment_form(); ?>`
inside of `/wp-content/themes/[your_theme]/comments.php` (please check the Twenty Ten theme's comments.php for proper example)

== Screenshots ==

1. screenshot-1.jpg is the captcha on the comment form.

2. screenshot-2.jpg is the captcha on the login form.

3. screenshot-3.jpg is the captcha settings on the admin plugins page.

== Changelog ==

= V1.2 =
An ability to display captcha in Register and Lostpassword forms has been added.

= V1.1 =
Fixed bag.

= V1.1 =
Usability at the settings page of plugin was improved.

= V1.0 =
Initial Release