=== Plugin Name ===
Plugin Name: Traffic Stats Widget Plugin
Version: 1.0.2
URI: 
Tags: traffic counter, user traffic, traffic widget, visitors counter, hit counter
Requires at least: 2.8.0
Tested up to: 4.0.1
Stable tag: 1.0.2
PHP Version: 5.2.9
MySql Version: 5.0.91-community
Author: Helen
Contributors: jhon


TSW lets your users know how much traffic you have on your blog. It counts pages visited, hits and unique IPs on your blog and shows it in a widget. It also shows the number of users currently online.

== Description ==

TSW shows the number of visitors / hits / unique IPs in the past 24 hours, 7 days and 30 days. It also shows the number of users currently online.

It provides a robots filter, but the automatic traffic could also be considered. 

Traffic Stats Widget offers language support and automatic log deletion.  

== Installation ==

1. Upload the zip to 'plugins' directory
1. Unzip (steps 1 and 2 cand also be performed automatically)
1. Activate the plugin
1. Configure and place the widget on your sidebar


If you need your traffic stats to be more accurate, you should use the Automatic Traffic Filter on the Widget. However, the internet is full of spiders, crawlers and all kind of robots not authenticating themselves as machines. Furthermore, it is very difficult to verify the signature of each and every robot visiting your blog... But there is a pretty good solution to this. I cannot access the root directory of your blog through Wordpress install API, so you will have to do the following things by yourself:


1. Create a robots.php file on the root directory of your blog: ie public_html/your-blog/
Paste the following code without // in it:

#######################################
#<?php                                #
#session_start();                     #
#$_SESSION['wtcrobot'] = 1;           #
#echo file_get_contents('robots.txt');#
#exit;                                #
#?>                                   #
#######################################

2. Open .htaccess file in the same directory and paste this in it:

RewriteRule robots\.txt robots.php

3. Make sure you have the 'RewriteEngine On' clause in place...

4. Make sure you have a robots.txt file, even an empty one, on the root directory

Done! Most of the robots will be filtered out by TSW.



Traffic Stats Widget does not have a settings section on Admin page. However, you can set the fields descriptions on the widget


== Screenshots ==

1. On the blog the widget looks like this
2. Admin Page

== Changelog ==
* 1.0
Initial Release
* 1.0.1
minor bug fixed


