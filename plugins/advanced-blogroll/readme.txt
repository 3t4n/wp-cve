=== Advanced Blogroll ===
Contributors: yakuphan
Donate link: http://www.yakupgovler.com/?p=592
Tags: blogroll, widget, advanced blogroll, blogroll widget
Requires at least: 2.3
Tested up to: 2.8.4
Stable tag: 1.4

Advanced Blogroll Widget displays your bookmarks as you want. You can customize your blogroll.

== Description ==

Advanced Blogroll Widget displays your bookmarks as you want. You can customize your blogroll.
You can add how many widgets you want. You can select the category of your links so that you can seperate your bookmarks in different categories.

= Supported Languages =
* English
* Turkish
* Russian - Thanks <a href="http://www.fatcow.com">Fatcow </a>
* German - Thanks <a href="http://www.wilsen.de/">Sebastian Masch</a>
* Belorussian - Thanks <a href="http://antsar.info">ilyuha</a>

== Installation ==

= Installation =
1. Download the zip file and extract the contents.
2. Upload the 'random-tags-cloud-widget' folder or 'random-tags-cloud.php' to your plugins directory (wp-content/plugins/).
3. Activate the plugin through the 'plugins' page in WP.
4. See 'Design'->'Widgets' to place it on your sidebar. Set the settings.
5. If you add the following CSS codes to your style.css, your bookmarks' images will have a transparent hover effect:

/* Advanced Blogroll CSS */

.ab\_images a, .ab\_images a:hover {
  text-decoration: none;
}

.ab\_images a img {
 filter:alpha(opacity=60);
 -moz-opacity: 0.6;
 opacity: 0.6;
 border:none;
}

.ab_images a:hover img {
 filter:alpha(opacity=100);
 -moz-opacity: 1.0;
 opacity: 1.0;
}

== Frequently Asked Questions ==
= How can i format my blogroll? =
You can use 'ab\_bookmarks' CSS class to format your bookmarks list and you can use 'ab_images' CSS class to format your list with only images.

== Screenshots ==

1. Widget's settings with default values in Design'->'Widgets'
2. Your blogroll with only names
3. Your blogroll with only images
4. Your blogroll with images and names
5. Adding image to your bookmark

== Options ==

Widget's options allow you to change your blogroll's displaying.

= Title =
Your blogroll's title on your sidebar.

= Category =
Category of your bookmarks you want to list.

= Order by =
What you want to order by

= Order =
How you want to order

= Display Form =
How to display your bookmarks. With only names, only images, images and names.

= Image Width =
Your bookmarks' image width.

= Image Height =
Your bookmarks' image height.

= Number of Bookmarks to Show =
How many bookmarks you want to show.

= Add rel = "nofollow" to bookmarks =
Allows add rel = "nofollow" to your bookmarks.

== Changelog ==

= 1.4 =
* Adding Belorussian Language Support

= 1.3 =
* Adding German Language support

= 1.2 =
* Adding Russian Language support

= 1.1 =
* Adding Rating Sort
* Adding link description to link title

= 1.0 =
* Plugin released
