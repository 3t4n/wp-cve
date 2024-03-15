=== .htaccess Site Access Control ===
Contributors: citywanderer, stubgo
Donate link: http://www.wpsos.io/wordpress-plugin-htaccess-site-access-control/
Tags: WPSOS,security,securing,htaccess,htpasswd,htaccess control,access control,access,permissions,hack prevention,brute force,hack,options,configuration,config
Requires at least: 3.0.1
Tested up to: 4.4.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

.htaccess Site Access Control plugin allows you to password protect your site: WP login page, admin pages, and/or the whole site.
== Description ==

Using the password protection will give you extra security layer of protection from brute force hacking attacks. Additionally, it's also an easy way to password protect your entire site, without needing to create separate WordPress users for each visitor.

When you enable the password protection, the user won't be able to see anything - not even see the protected page - until he/she inserts the username/password. You can password protect the whole website, including the administrator pages; you can password protect the administrator pages; or you can password protect the WordPress login page.

Free plugin options include:

*   Enabling/disabling the password protection to wp-login.php, WordPress admin pages. Note that you’ll be asked to re-type the .htaccess username/password you created before enabling any of the settings – to ensure that you wouldn’t enable the password protection without even knowing the password yourself!
*   Modifying the existing users: you can change any .htaccess user’s password and remove the users.
*   Adding one .htaccess user.   

Premium plugin options:

*   Create/modify an unlimited number of .htaccess users;
*   Protect your whole site, making it accessible to only those who have the .htaccess user.

If you have any other suggestions, please let us know! You can contact us via http://www.wpsos.io/wordpress-plugin-htaccess-site-access-control/

For more information and support, check out: http://www.wpsos.io/wordpress-plugin-htaccess-site-access-control/

== Installation ==

The installation and use is very straightforward. You should:

1. Upload the folder `htaccess-site-access-control` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. From the 'Settings' menu, there will be a new option, called '.htaccess Site Access Control'

== Frequently Asked Questions ==

= Which options do you modify? =

As of version 1.0, you can choose between the following options:
1. Enabling/disabling the password protection to wp-login.php, WordPress admin pages, and/or the whole site. Note that you'll be asked to re-type the .htaccess username/password you created before enabling any of the settings - to ensure that you wouldn't enable the password protection without even knowing the password yourself!
2. Modifying the existing users: you can change any .htaccess user's password and remove the users.
3. Adding a new .htaccess user.

Note that you have to have at least one user to be able to enable any of the options: otherwise you would be locked out!

For more information and support, check out: http://www.wpsos.io/wordpress-plugin-htaccess-site-access-control/

= The plugin is giving a warning that some of the files need to be writable for it to work, what does this mean? =

Since the plugin is protecting your site via modifying .htaccess and .htpasswd files, it works only if these files are writable by WordPress. If the files don't exist, you can just create empty writable files to the location brought out in the plugin's warning. You can also see from there which files are already writable and which not.

= I forgot my password, and got locked out from the site! What can I do? =
For accessing your site again, you have to modify two files:
1. .htaccess file in your WordPress root directory (the directory where the file wp-config.php is located);
2. .htaccess file in your WordPress wp-admin folder

From both files, delete everything BETWEEN these two lines:

*   # BEGIN WPSOS htaccess plugin
*   # END WPSOS htaccess plugin

IMPORTANT: Before modifying either of the files, make a copy of them!

For accessing the files, either use FTP or log in to your web hosting service provider, usually they also enable direct file modification.

= Where can I get some support? =

Check out our site, at: http://www.wpsos.io/wordpress-plugin-htaccess-site-access-control/

= I have some suggestions for other options I want edited =

Let us know, via: http://www.wpsos.io/wordpress-plugin-htaccess-site-access-control/

== Screenshots ==

1. Screenshot of the main configuration screen
2. Screenshot of the enabling/disabling password protection configuration
3. Modifying existing users
4. Adding a new user
5. A password protected wp-login.php page

== Changelog ==

= 1.0 =
* Initial version.
