=== iRobots.txt SEO ===
Contributors: Mark Beljaars
Tags: SEO, robots.txt, sitemap, site map, robot, robot.txt, security, robots
Requires at least: 2.7
Tested up to: 2.9.2
Stable tag: 1.1.2

iRobots.txt SEO is a SEO optimized, secure and customizable robots.txt virtual file creator. 

== Description ==

iRobots.txt SEO (IRSEO) is a fully customizable robots.txt virtual file generator. IRSEO creates a highly optimized and secure robots.txt file straight out of the box. Users may choose to enable or disable specific user agents, directories or files using intuitive options all of which include detailed instructions.

The robots.txt file is a text file located in the root directory of a website. It's purpose is to direct user-agents (AKA bots) away from or towards specific files or directories. Inhibiting a bot from indexing specific pages will ensure your website remains keyword optimized and all indexed pages are relevant to your potential customers.

IRSEO also inhibits several Wordpress system directories and files by default. Doing this ensures that the search bots do not include security sensitive pages within search results. For example, searching for "inurl:wp-content name size description" in Google will produce a list of sites with indexed and open content directories.

Note that IRSEO creates a virtual robots.txt file. This robots.txt file is displayed whenever access to the robots.txt file is requested. 

== Installation ==

1. Download the plugin from `http://markbeljaars.com/download/current/plugins/irobotstxt-seo.zip`.
1. Extract and upload the plugin to your `/wp-content/plugins/` directory and activate it.
1. Edit the plugin settings using the admin page located under `Settings`.

== Frequently Asked Questions ==

= Does iRobots.txt SEO create or modify any files? =

No. The robots.txt file serverd by IRSEO is virtual only. Your site will remain unmodified once the plugin is removed.

= Where can I learn more about robots.txt? =

The official robots.txt information site is [http://www.robotstxt.org/](http://www.robotstxt.org/). The Google robots.txt extensions are documented [here](http://www.google.com/support/webmasters/bin/answer.py?hl=en&answer=156449).

= Why does the robots.txt file not display? =

If you type http://blog-name/robots.txt into a browser and are not presented with the IRSEO virtual robots.txt file, there may be several causes:

* Another plugin is creating its own robots.txt file. In this case, it is first in best dressed. Ensure that "Add sitemap URL to the virtual robots.txt file" option is not selected in XML Sitemap Generator.
* You have a physical robots.txt file. In this case the physical file is displayed instead of the virtual file.
* Your theme is not virtual file friendly. This is unfortunately the case for the excellent Arclite theme. 

= Can I edit the robots.txt file? =

Yes. In the "View Robots.txt" admin setting panel, select the "Enable free form editing" option. You will now be able to directly modify the robots.txt file from within this pane.  

== Screenshots ==

1. An example of the configuration page.
1. A sample generated robots.txt file.

== Changelog ==

= 1.1.2 =

* Add Belorussian translation by [Marcis G.](http://pc.de/ "Marcis G."). Thank you Marcis.

= 1.1.1 =

* Fixed file close bug in irseo_file_exists function that has caused an error on some blogs.
* Added option to allow or filter duplicate content.

= 1.1 =

* It is now possible to free edit the robots.txt file from within the plugin admin panel.
* The robots.txt file is now served if the URL does or does not contain the 'www' prefix. URL comparison is now also case insensitive.
* Added 'sitemap.xml.gz' to robots allow all section.
* XML Sitemap plugin warning is now hidden if the virtual robots.txt file is served correctly.
* The php code has now been fully commented.
* Added nonce and admin check to all administration panel settings changes (for security purposes).
* Moved all options into a single associative array resulting in smaller and easier to follow code with less calls to the option table.

= 1.0.4 =

* Fixed bug that stopped the admin page loading on some systems.
* Added "Settings" link to plugin menu using code provided by Jay (PDRater.com)

= 1.0.3 =

* Removed PHP5 function stream_get_contents and replaced with backwards compatible fgets as suggested by Jay (PDRater.com)
* Detect presense of XML Sitemap Generator and if exists post a warning explaining that this plugin also generates a virtual robots.txt file. XML Sitempas has an option for disabling robots.txt file generation. Again, thanks Jay for this feedback.

= 1.0.2 =

* Fixed defines, function names and i10n strings conflicting with the TOCC plugin.

= 1.0.1 =

* Modified admin setting section headers to expand section if clicked anywhere within the header.

= 1.0 =

* Initial public release.


