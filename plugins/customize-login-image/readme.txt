=== Customize Login Image ===
Contributors: apasionados
Donate link: http://apasionados.es/
Author URI: https://apasionados.es/
Tags: custom, admin, customize, logo, login
Requires at least: 3.0.1
Tested up to: 6.3
Stable tag: 3.5.3
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to customize the image and the appearance of the WordPress Login Screen.

== Description ==

This plugin allows you to customize the image and the appearance of the WordPress Login Screen. You can change: The logo, the link of the logo and the background color.

There are three features we love:

1) If no logo is uploaded, the default WordPress logo is shown.

2) You can upload your own logo in PNG format to the WordPress UPLOADS folder (you just have to name it customize-login-image.png) and if no URL for the logo was set or no logo was uploaded, the plugin looks if this file exists and uses it.

3) You can translate the plugin into your own language. So far English and Spanish translations are included. Just translate the .po file in the /lang/ folder.

4) You can choose if you want to show the server IP and/or server hostname on the login screen.

= What can I do with this plugin? =

You can upload a custom image for the login screen and specify the link attached to the logo. By default you are redirected to the homepage of your site.

You can also set a custom background color for the login screen. Until version 3.5 you could also add your own CSS now this is not possible following the updated WordPress Plugin Security guidelines.

You can choose if you want to show the server IP and/or server hostname on the login screen. We find this extremly useful when changing the server of a WordPress installation. If you activate both checkboxes you will know inmediately if you are login in on the correct server.

= What ideas is this plugin based on? =

We had been using the WordPress plugin [Customize Admin](http://wordpress.org/plugins/customize-admin/ "Customize Admin") but we didn't like that the default logo was the VanderWikj Consultancy logo (vanderwijk.png). This has been causing us problems with customers when updating their sites and this logo displaying on their login screen; because they though that something was wrong with their installation. Another thing we didn't like about this plugin was the other settings it included; we want a plugin to change the login appearance and only that.

This is why we decided to create a new plugin that doesn't have the two issues we found while using Customize Admin. Nervertheless it is a freat work from Johan van der Wijk.

= Customize Login Image Plugin in your Language! =
This first release is avaliable in English and Spanish. In the "lang" folder we have included the necessarry files to translate this plugin.

If you would like the plugin in your language and you're good at translating, please drop us a line at [Contact us](http://apasionados.es/contacto/index.php?desde=wordpress-org-customize-login-image-home).

= Further Reading =
You can access the description of the plugin in Spanish at: [Customize Login Image en espa&ntilde;ol](http://apasionados.es/blog/customize-login-image-wordpress-plugin-1726/).

== Screenshots ==

1. New interface where you can also show IP and/or hostname on login screen.
2. Example of login screen.
3. Interface of version 2 and earlier.

== Installation ==

1. First you will have to upload the plugin to the `/wp-content/plugins/` folder.
2. Then activate the plugin in the plugin panel.
If you have manage options rights you will see the new Cutomize Login Image Settings menu.
3. Specify a clickthrough url for the logo if required.
4. Specify the url for the custom logo. The path can be relative from the root or include the domain name.
5. If you have not yet uploaded a logo, you can do so via the Upload Image button. Make sure you click 'Insert into Post'. For the best result, use an image of maximum 70px height by 310px width.
6. Click Save Changes.

Please note that the plugin should not be used together with other plugins with similar funcionalities like: [Customize Admin](http://wordpress.org/plugins/customize-admin/ "Customize Admin").

== Frequently Asked Questions ==

= Why did you make another admin logo plugin?  =

We couldn't find a plugin that would give us the functionality we were looking for:
1) If no logo is uploaded, the default WordPress logo is shown.
2) Upload of own logo in PNG format to the WordPress UPLOADS folder (you just have to name it customize-login-image.png) and if no URL for the logo was set or no logo was uploaded, the plugin looks if this file exists and uses it.
3) Easy translation of the plugin into other languages. So far English and Spanish translations are included.

= How can I remove Customize Login Image? =
You can simply activate, deactivate or delete it in your plugin management section.

= Which size is the logo? =
For the best result, use an image of maximum 70px height by 310px width.

= Where can should I upload the logo if I don't want to set it up in the administration of the plugin =
If you upload a logo in PNG format named customize-login-image.png to your WordPress Uploads folder, this logo will be used if you have not introduced a URL or uploaded an image in the plugin administration.
You can check if you uploaded the logo correctly in the administration of the plugin, clicking on the image name customize-login-image.png. If the logo opens in a new browser windows, everything is OK; if it doen't open in the new browser windows and you get a 404 error (not found), please check that you placed the logo in the correct folder and that it's name is correct (all lowercase and png extension). 
Please keep in mind that only PNG files are allowed.

= What happens if I don't introduce a logo URL or don't upload a logo? =
Then the default WordPress logo will be displayed. This logo can be found in the wp-admin folder of the WordPress installation in the images folder.

= How can I show the server ip and/or hostname on the login screen? =
Activate the checkboxes for the Server IP and HOSTNAME and SAVE.
You can choose to show both (both options checked), only one (one option checked) or none (nothing checked).

= Are there any known incompatibilities? =
The plugin should not be used together with other plugins with similar funcionalities like: [Customize Admin](http://wordpress.org/plugins/customize-admin/ "Customize Admin").

= Do you make use of Customize Login Image yourself? = 
Of course we do. That's why we created it. ;-)

== Changelog ==

= 3.5.3 (09/DEC/2021) =
* Security update. Please update your plugin. We removed custom CSS, applied data sanitization and escaped data shown to increase your security. In this release we fixed an error with the removal of custom CSS.

= 3.5.1 & 3.5.2 (08/DEC/2021) =
* Security update. Please update your plugin. We removed custom CSS, applied data sanitization and escaped data shown to increase your security.

= 3.5 (07/DEC/2021) =
* Security update. Please update your plugin. We added data sanitization when saving in database and when showing elements on the login screen.

= 3.4 (07/JAN/2021) =
* Replaced deprecated login_headertitle function with login_headertext function.

= 3.3.1 =
* Code improvement.

= 3.3 =
* Solved Warning: call_user_func_array() expects parameter 1 to be a valid callback, function "cli_show_server_hostname"

= 3.2 =
* Bugfix for error: Fatal Error customize-login-image.php on line 66

= 3.1 =
* Added translations to the IP and/or HOSTNAME information shown on login screen.

= 3.0 =
* Added checkboxes to show the IP and/or HOSTNAME on login screen.

= 2.0 =
* Added compatibility to WordPress 3.8 "Parker".

= 1.3 =
* Fixed another bug with the UPLOADS folder handling. Now it should work correctly in all possible configurations of the UPLOADS folder.

= 1.2 =
* Fixed another bug with the UPLOADS folder handling. Now it should work correctly in all possible configurations of the UPLOADS folder.

= 1.1 =
* Added the possibility to access the plugins settings from the WordPress plugin screen.
* Fixed a bug with the UPLOADS folder when the option "Organize my uploads into month- and year-based folders" in the MEDIA settings was checked. Now it works correctly, looking for the image in the base-UPLOADS-folder without year and month.

= 1.0 =
* First stable release.

= 0.5 =
* Beta release.

== Upgrade Notice ==

= 3.5.3 =
UPDATED: Security update. Please update your plugin.

== Contact ==

For further information please send us an [email](http://apasionados.es/contacto/index.php?desde=wordpress-org-customizeloginimage-contact).