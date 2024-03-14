=== Plugin Name ===
Contributors: aarontgrogg
Tags: body, class
Requires at least: 3.1
Tested up to: 4.2.2
Stable tag: 1.3


Add URL Slugs to `body` Class


== Description ==

This plug-in takes the URL, chops it into pieces, and adds each "piece" as an additional
class to the `body` tag.

Meaning, if your page's URL is:
http://www.example.com/2012/02/24/wordpress-plugin-add-url-slugs-as-body-classes/
Your page's `body` tag would have at least these classes:
`2012 02 24 wordpress-plugin-add-url-slugs-as-body-classess`

It gets much stronger when you start thinking about Categories or nested Pages:
`http://www.example.com/category/wordpress/`
which would add:
`category wordpress`
while:
`http://www.example.com/movies/star-trek/wrath-of-khan/`
would add:
`movies star-trek wrath-of-khan`

This allows you to very easily add custom CSS and/or JS to these pages based on these new CSS classes.

Please let me know if you have any questions/suggestions/thoughts,
Atg
http://aarontgrogg.com/
aarontgrogg@gmail.com


== Installation ==

1. Download the ZIP
2. Unzip the ZIP
3. Copy/paste the unzipped files into your WP plug-in directory (`/wp-content/plugins/`)
4. From within WP's Plugin Admin panel, Activate the plug-in
5. Write some wicked CSS to amaze your friends and befuddle your enemies...


== Frequently Asked Questions ==

= What does this do, exactly? =
* Additional CSS classes are added to the HTML `body` tag so you can easily add custom CSS and/or JS to Posts or
  Pages.  Basically anything that appears in the URL after your domain name, will be split on the "/", then 
  pushed back together separated by spaces in the HTML's `body` tag.

= What classes? =
* Say you created a Post that could be found at the following URL:
  `http://www.example.com/2012/02/24/wordpress-plugin-add-url-slugs-as-body-classes/`
  That page's `body` tag would have at least these classes:
  `2012 02 24 wordpress-plugin-add-url-slugs-as-body-classess`

  It gets much stronger when you start thinking about Categories or nested Pages, because a Category page like:
  `http://www.example.com/category/wordpress/`
  get these additional CSS classes:
  `category wordpress`
  This would allow you to add custom CSS and/or JS to all Category pages, or even _just_ the WordPress Category page!

  Or a nested Page that could be found at this URL:
  `http://www.example.com/movies/star-trek/wrath-of-khan/`
  would get these additional CSS classes:
  `movies star-trek wrath-of-khan`
  So you could add custom CSS _just_ for your Movies pages, other custom CSS for _just_ the Star Trek Movies pages, 
  and still more custom CSS for _just_ the Wrath of Khan Star Trek Movie page.

= Why would I want to do this? =
* Really only if you used some custom CSS or JS to some specific webpages.  You could add custom background images, 
  use different fonts, add sound effects, add different JS libraries, the list is limited only by your needs, really.


== Screenshots ==


== Changelog ==

= 1.3 =
2015-05-15:
* Tested & verified in WP 4.2.2, and added a few FAQs

= 1.1 =
2013-01-11:
* Tested & verified in WP 3.5

= 1.0 =
2012-02-24:
Well, this is the first version, so... here it is!
