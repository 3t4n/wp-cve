=== Login Widget With Shortcode ===
Contributors: avimegladon
Donate link: https://www.aviplugins.com/donate/
Tags: login, widget, login widget, widget login, sidebar login, login form, user login, authentication, facebook login, twitter login, google login, google plus, facebook, twitter, social login, social media, facebook comments, fb comment, forgot password, reset password, link
Requires at least: 2.0.2
Tested up to: 6.2.2
Stable tag: 6.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a simple login form in the widget. This will allow users to login to the site from frontend. 

== Description ==

* This is a simple login form in the widget.
* Compatible with WordPress Multisite Installation.
* Use this shortcode [login_widget] to use login form in your pages/ posts. 
* Just install the plugin and add the login widget in the sidebar. 
* Change some 'optional' settings in `Login Widget Settings` (admin panel left side menu) and you are good to go. 
* Add CSS as you prefer because the form structure is really very simple.
* Use this shortcode [forgot_password] in your page to display the forgot password form. Forgot password link can be added to login widget from plugin settings page.
* Login form is responsive.
* Plugin is compatible with <strong>WPML</strong> plugin. You can check the compatibility at <a href="https://wpml.org/plugin/login-widget-with-shortcode/" target="_blank">wpml.org</a>.

[youtube https://www.youtube.com/watch?v=GIdsTLfH6Is]

= Other Optional Options =
* Add CAPTCHA security in admin and frontend login forms.
* Login Logs are stored in database ( IP, login status, login time ). PRO version has options to block IPs after certain numbers of wrong login attempts.
* You can choose the redirect page after login. It can be a page or a custom URL.
* Choose redirect page after logout.
* Choose user profile page.
* Easy CSS implementation from admin panel.

= Facebook Login Widget (PRO) =
There is a PRO version of this plugin that supports login with <strong>Facebook, Google, Twitter, LinkedIn, Amazon and Instagram accounts. Get it for <strong>USD 6.00</strong> 

<a href="https://www.aviplugins.com/fb-login-widget-pro/" target="_blank">Click here for more details</a> | <a href="https://demo.aviplugins.com/login/" target="_blank">Click here for a Live Demo</a>

* The PRO version comes with a <strong>FREE Content Restriction Addon</strong>. Partial contents of Pages/ Posts or the complete Page/Post can be hidden from visitors of your site.
* Compatible with <strong>WooCommerce</strong> plugin.
* Compatible with <strong>WordPress Multisite</strong> Installation.
* Login Logs are stored in database. IPs gets <strong>Blocked</strong> after a certain numbers of wrong login attempts. This ensures site's security.
* IPs can be <strong>Blocked</strong> permanently from admin panel.
* <strong>Captcha</strong> login securiy in Frontend and Admin login Forms.
* <strong>Restrict Admin panel Access</strong> for selected user Roles. For example you can restrict Admin Panel access for "Subscriber" and "Contributor" from your site.
* Use Shortcode to display login form in Post or Page.
* Use only Social Icons for logging in. No need to put the entire login form.
* Change welcome text "Howdy" from plugin settings section.
* Manage Forgot Password Email Body.
* Easy CSS implementation from admin panel.
* And with other useful settings. <a href="https://www.aviplugins.com/fb-login-widget-pro/" target="_blank">Click here for details</a>

= Social Login No Setup =
The plugin supports login with 30+ sites. The most important part is that it requires no Setups, no Maintanance, no need to create any APPs, APIs, Client Ids, Client Secrets or anything. Get it for <strong>USD 3.00</strong>. Supported sites are listed below.

* Facebook
* Google
* YouTube
* Google Drive
* Gmail
* Twitter
* LinkedIn
* PayPal
* Yahoo
* Microsoft
* WordPress
* Amazon
* Github
* Tumblr
* Vimeo
* Reddit
* Dribbble
* Twitch
* Medium
* Discord
* Line
* Stack Exchange
* Stack Overflow
* Disqus
* Blogger
* Meetup
* Foursquare
* Yandex
* VKontakte
* Telegram
* Dropbox
* Fitbit
* Slack
* Deviantart
* Mailchimp
* Skype

<a href="https://www.aviplugins.com/social-login-no-setup/" target="_blank">Click here for more details</a> | <a href="https://demo.aviplugins.com/social-login/" target="_blank">Click here for Live Demo</a>

> Post your plugin related queries at <a href="https://www.aviplugins.com/support.php">https://www.aviplugins.com/support.php</a>

== Installation ==

1. Upload `login-sidebar-widget.zip` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to `Login Widget Settings` and set some optional options. It's really easy.
4. Go to `Login Widget Settings -> Login Logs` to check the user login log.
4. Go to `Appearance->Widgets` in available widgets you will find `Login Widget` widget, drag it to chosen widget area where you want it to appear.
5. You can also use shortcodes to insert login form in post or pages. [login_widget title="Login Here"]
5. Now visit your site and you will see the user login form.

= Redirection After Login =
To restrict a page from non logged in users and redirect users to requested URL after successful login, add this code in the top of the page template.
 
> if ( !is_user_logged_in() ) { <br>
wp_redirect('http://www.example.com/login/?redirect='.login_wid::curPageURL());<br>
exit;<br>
}<br> Change "http://www.example.com/login/" to your login page url.
 
= Translations =

* The Serbo-Croatian Language translation file is provided by <a href="http://www.webhostinghub.com" target="_blank">Web Hosting Hub</a>  
* Chinese translation is provided by Tianming Wu 
* Portuguese (European) translation is provided by David Costa 
* Spanish translation is provided by Javier   
* Finnish translation is provided by Tomi Yl&auml;-Soininm&auml;ki, Katja Lampela
* Persian translation is provided by Salman Amini
* Dutch translation is provided by Baree van Vugt
* Italian translation is provided by Filippo Antonacci
* Brazilian Portuguese translation is provided by Edu Musa
* Polish translation is provided by Mateusz W&oacute;jcik
* German translation is provided by Benjamin Hartwich 
* Hungarian translation is provided by Attila Kiss
* Russian translation is provided by &#1042;&#1083;&#1072;&#1076;&#1080;&#1084;&#1080;&#1088; &#1050;&#1086;&#1084;&#1087;&#1100;&#1102;&#1090;&#1077;&#1088;&#1086;&#1074;
* Another Russian translation is provided by &#1042;&#1083;&#1072;&#1076;&#1080;&#1084;&#1080;&#1088; &#1050;&#1086;&#1084;&#1087;&#1100;&#1102;&#1090;&#1077;&#1088;&#1086;&#1074; (File - login-sidebar-widget-ru_RU-2.po)

== Frequently Asked Questions ==

= For any kind of queries =

1. Please email me demoforafo@gmail.com. Contact me at https://www.aviplugins.com/support.php
2. Or you can write comments directly to my plugins page. Please visit here http://avifoujdar.wordpress.com/2014/02/13/login-widget/

* If you want to translate the plugin in your language please translate the sample .PO file and email me the the file at demoforafo@gmail.com and I will include that in the language file. Sample .PO file can be downloaded from <a href="https://www.aviplugins.com/language-sample/login-sidebar-widget-es_ES.po">here</a>

== Screenshots ==

1. Login widget
2. Login widget
3. Admin login with captcha security
4. General Settings
5. Security Settings
6. Error Message Settings
7. Style Settings
8. Email Settings
9. Users Login Log
10. Forgot Password form
11. Facebook Comments Addon
12. WPML Plugin compatibility Certificate.
13. Google reCaptcha settings

== Changelog ==

= 6.1.1 = 
* Bug fixes.

= 6.1.0 = 
* Improvements.

= 6.0.9 = 
* Google reCaptcha added for login security. This can be used instead for default captcha.

= 6.0.8 = 
* Improvements and bug fixes.

= 6.0.7 = 
* Improvements.

= 6.0.6 = 
* .POT file added in language folder for easy translations.

= 6.0.5 = 
* Admin panel tabs related javascript update. Please remove browser cookies after update.

= 6.0.4 = 
* Removed namespaces.

= 6.0.3 = 
* Bug fixed after version 6.0.2 update.

= 6.0.2 = 
* Plugin settings panel is updated and other fixes.

= 6.0.1 = 
* Captcha image width increased.

= 6.0.0 = 
* This is a major update with a lot of structural changes. If you are updating from an older version then some of your plugin settings may be erased, so after update you may have have to resave your plugin settings.

= 5.8.5 = 
* Option added to disable Nonce check valivation in login form.

= 5.8.4 = 
* Some design changes are made in settings page.

= 5.8.3 = 
* Bug fixed.

= 5.8.2 = 
* Email template bug fixed.

= 5.8.1 = 
* Plugin code structure updated. This will make the plugin faster.

= 5.8.0 = 
* Option to enter URLs in Forgot Password & Registration links.

= 5.7.9 = 
* Compatibility added with All In One WP Security and bug fixed.

= 5.7.8 = 
* Bug Fixed.

= 5.7.7 = 
* Bug Fixed.

= 5.7.6 = 
* Bug Fixed after 5.7.5 update. 

= 5.7.5 = 
* Bug Fixed.

= 5.7.4 = 
* File structure updated.

= 5.7.3 = 
* Bug Fixed.

= 5.7.2 = 
* Bug Fixed.

= 5.7.1 = 
* Default plugin style updated.

= 5.6.8 = 
* Bug Fixed. 

= 5.6.7 = 
* More filters and hooks are added in the plugin. 

= 5.6.6 = 
* pagination class updated. 

= 5.6.5 = 
* loopback related bug fixed. 

= 5.6.4 = 
* support for redirect_to parameter in login form added.

= 5.6.3 = 
* Error message display updated.

= 5.6.2 = 
* Confliction with WP Register Profile PRO plugin bug fixed.

= 5.6.1 = 
* Validation functionality updated. Captcha image updated. Login form design updated.

= 5.6.0 = 
* Password code updated.

= 5.5.8 = 
* Password field bug fixed.

= 5.5.7 = 
* Plugin is now compatible with <strong>WPML</strong> Plugin.

= 5.5.6 =
* Plugin is now compatible with <a href="https://wordpress.org/plugins/google-authenticator/">Google Authenticator</a> plugin.

= 5.5.5 =
* Plugin settings panel design updated.

= 5.5.4 =
* Option to redirect users to requested URL after successful login.

= 5.5.3 =
* Bug fixed.

= 5.5.2 =
* Notice message bug fixed.

= 5.5.1 =
* Bug fixed.

= 5.5.0 =
* Forgot Password functionality updated.

= 5.4.1 =
* Option to change Error Login Message from plugin settings section.

= 5.4.0 =
* Now Compatible with WordPress Multisite.

= 5.3.0 =
* Code updated for compatibility.

= 5.2.6 =
* Styling/ CSS updated in login form.

= 5.2.5 =
* Login Log section updated in admin panel. Option added to Clear / Empty Login Log data.

= 5.2.4 =
* Plugin message display updated.

= 5.2.3 =
* User Login Log feature implemented.

= 5.2.2 =
* Hooks are added for compatibility.

= 5.2.1 =
* plugin code modifications.

= 5.2.0 =
* Code updated with some security modifications.

= 5.1.5 =
* Captcha security added in admin and frontend login forms.

= 5.1.4 =
* plugin code optimized.

= 5.1.3 =
* news dashboard widget optimized.

= 5.1.2 =
* Settings saved message in admin panel.

= 5.1.1 =
* Option to add after login redirect URL with redirect to page option.

= 5.1.0 =
* Forgot password form email address added. aviplugins.com dashboard news widget added.

= 5.0.0 =
* forms structure is updated, Now with fully responsive login form. Make sure to reload the default styling of the plugin from plugin settings page.

= 4.2.4 =
* forms structure is updated.

= 4.2.3 =
* Language selection bug fixed for <a href="https://www.aviplugins.com/fb-comments-afo-addon/">Facebook Comments Add On</a>

= 4.2.2 =
* Login and Logout page redirection modified.

= 4.2.1 =
* Remember me issue fixed.

= 4.2.0 =
* Help and Support link added.

= 4.1.0 =
* Plugin notice message bug fixed.

= 4.0.0 =
* forgot password functionality added.

= 3.2.1 =
* Security related bug fixed. Advisory https://security.dxw.com/advisories/csrfxss-vulnerablity-in-login-widget-with-shortcode-allows-unauthenticated-attackers-to-do-anything-an-admin-can-do/

= 3.1.1 =
* Localization is added.

= 2.2.4 =
* admin menu related bug fixed.

= 2.2.3 =
* Added support for css.

= 2.1.3 =
* Modified error message display.

= 2.0.2 =
* CSS file bug issue is solved.

= 2.0.1 =
* Shortcode functionality is added.

= 1.0.1 =
* this is the first release.


== Upgrade Notice ==

= 1.0 =
I will update this plugin when ever it is required.
