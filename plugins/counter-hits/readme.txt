=== Counter-Hits ===
Contributors: WPGear
Donate link: http://wpgear.xyz/counter-hits/
Tags: counter, hits, visitors, activity
Requires at least: 4.1
Tested up to: 5.9
Requires PHP: 5.4
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: 1.8

A simple, easy, fast, adaptive, local, objective counter to visit your site.

== Description ==

A simple, easy, fast, adaptive, local, objective counter to visit your site.
It does not use any additional requests to other servers, which means it consumes minimal resources.
Displays the count of all views of any page as a number. 
You can customize your own display style. Use class: 'wpgear_counter_hits'

Just paste Shortcode [Get_Counter_Hits] wherever you like and that's it.
Or you can use calls in PHP scripts code: echo get_Counter_Hits ();
	
= Features =
* Simple local Counter.
* It always works, even if the result is not displayed anywhere.
* Doesn't count if Admin Page.

* ShortCode for use: [Get_Counter_Hits]
* ShortCode for use with correction +100000: [Get_Counter_Hits base="100000"]
* PHP use: echo get_Counter_Hits ($base);

* CSS Class 'wpgear_counter_hits' - for disign customisation.

= Demo =
<a href://wpgear.xyz/counter-hits/>You can see the Demo here</a>

== Installation ==

1. Upload 'counter-hits' folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Make sure your system has iconv set up right, or iconv is not installed at all. If you have any problems (trimmed slugs, strange characters, question marks) - please ask for support. 

== Frequently Asked Questions ==

NA

== Screenshots ==
 
1. screenshot-1.png This is the example page whith Counter.

== Changelog ==
= 1.8 =
	2021.03.05
	* Tested to WP 5.7-RC2-50482
	
= 1.7 =
	2021.02.21
	* Optimization code. Now, more correctly.
	
= 1.6 =
	2021.02.19
	* Anty Flood functions.
	
= 1.5 =
	2021.02.18
	* Fix problem: Doesn't count if WooCommerce is installed.
	
= 1.4 =
	2021.02.17
	* Optimization code.
	
= 1.3.1 =
	2021.02.16
	* Just edit "Tested up to: 5.6.1" on ReadMe.txt
	
= 1.3 =
	2018.11.23
	* Fix problem: Null value whith search queries.
	
= 1.2 =
	2018.11.06
	* Optimization. Reduced read-write DB operations.
	
= 1.1 =
	2018.11.05
	* Add ShortCode [Get_Counter_Hits]
	
= 1.0 =
	2018.11.04
	* Initial release

== Upgrade Notice ==
= 1.7 =
	* Now, more correctly.
	
= 1.6 =
	* 2021.02.19 Now Counter_Hits works more neat.
	
= 1.5 =
	* 2021.02.18 If WooCommerce is installed, you need upgrade Counter_Hits.
	
= 1.4 =
	* 2021.02.17 Fixes old stupidity. Doesn't count if Admin Page.
	
= 1.3.1 =
	* 2021.02.16 Just edit "Tested up to: 5.6.1" on ReadMe.txt - No other changes. Everything works well.