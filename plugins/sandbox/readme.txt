=== Sandbox ===
Contributors: barteled
Donate link: http://wordpress.think-bowl.com/
Tags: sandbox, test, regression, verify, offline
Requires at least: 3.8
Tested up to: 3.8.1
Stable tag: 0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create a completely independent test site using existing hosting that is only available to administrators.

== Description ==

Have you ever stared at the upgrade button of your favorite plugin with a deep foreboding feeling of what lies ahead? Will this bring your live site to its knees? Or have you wanted to test your new exciting theme without prematurely showing it to the world?

Enter the Sandbox plugin. With two simple clicks an entire testing site is created using your existing hosting provider. No more copying down to a complex local test setup or living dangerously on a live site. A complete independent sandbox, unavailable to the general public and search engines, can be created quickly and simply. You can even create multiple sandboxes to test different iterations of your site.

There is not that much more to say, it's that easy!
<h2>What can I test with this?</h2>
Pretty much anything, but here are some examples:
<ul>
	<li>Plugin upgrades</li>
	<li>Theme changes</li>
	<li>Setting changes</li>
	<li>Even full, Wordpress upgrades</li>
	<li>Plugin conflicts? Create a sandbox and disable one at a time.</li>
</ul>
<h2>Ok, but how does it work?</h2>
When you add a new sandbox, a couple simple parameters are required to differentiate it from other sandboxes. A status is displayed as the sandbox is being created. This can take some time depending on the size of your Wordpress install.

Basic steps to creating the sandbox:
<ol>
	<li>Copy the tables
Database tables are copied to new tables with a different prefix and various data within the tables is updated to reflect the sandbox prefix.</li>
	<li>Copy the files
All files are copied to a new folder under the sandbox folder.</li>
	<li>Update the configuration
The configuration file within the sandbox is updated to support the unique sandbox database prefix.</li>
</ol>
Once activated, only your browser can get to the sandbox installation. You can return the live site at anytime by clicking the link in the notice at the top of the admin interface.
<h2>Can I use my existing developer tools to edit the sandbox?</h2>
Sure. Tools such as Dreamweaver can update the files located in the sandbox directory. A full path is available on the edit screen of the sandbox. Database files can be modified as well. All sandbox tables will have the prefix displayed on the edit screen of the sandbox.

Another beer and coffee fueled creation of [Think Bowl](http://think-bowl.com/ "It's less of a tank and more of a bowl.")

== Installation ==

Installation from zip:

1. From wp-admin interface, select Plugins -> Add New
2. Click Upload
3. Click "Choose File" and select zip
4. Click "Install Now"
5. Activate the plugin through the 'Plugins' menu in WordPress

Installation from Wordpress Plugin Directory

1. From wp-admin interface, go to Plugins -> Add New
2. Search for "Sandbox"
3. Click Install Now under the plugin name
4. Click Ok to install the plugin
5. Click Activate Plugin once installed

== Frequently Asked Questions ==

= Will sandbox files and tables be removed on deactivation/uninstall? =

No. To err on the side of caution, sandboxes are left intact when the plugin is deactivate and/or uninstalled. If you no longer need your sandboxes, delete them before removing the plugin.

= How do I disable the sandbox and return to the live site? =

Login to the admin interface, there should be a banner at the top which says "Currently in <Name> sandbox. To deactivate sandbox, click here." 

If you cannot get into the admin interface, there is a cookie in the browser called "sandbox". Delete this and you will be returned to the live site. This cookie is used to tell the plugin if you want to use a sandbox and which one. So for example if you activate the sandbox in one browser, you can start up a different browser and view the live site.

= How do I use the export capability? =

The export capability provides a way to download a sandbox and install it somewhere. To download an export, go to Sandbox option on the sidebar of the Admin interface. Place your mouse over the row of the sandbox you would like to export and the select the export option. Once prompted, click the download link. Depending on the size of the sandbox and speed of your web server, it may take some time for the link to appear.

The download file is a zip of all the files for the sandbox. Within the zip there is a SQL dump (dump.sql) of all the database tables related to the sandbox. The files need to be copied into the desired directory of the new server and the SQL file needs to be imported into the database. 

= Why are my backups are now huge? =

Backup plugins will backup not only your existing site but also the directory containing your sandboxes. Most backup plugins will have an exclusion setting which you can add the ./sandbox directory to. 

= This plug-in helped me a lot. How can I ever repay you? =

A small Paypal donation at [Think Bowl](http://think-bowl.com/ "It's less of a tank and more of a bowl.").

== Screenshots ==

== Changelog ==

= 0.1 =
* First release with simple functionality.

= 0.2 =
* Fixed two PHP warnings
* Added capability to handled wordpress sites that are not in the root directory

= 0.3 =
* Minor bug fix that could cause sandbox_edit() error on some servers

= 0.4 =
* Add capability to export sandbox for installation elsewhere.







