=== Calculate Values with Shortcodes ===
Contributors: bhagwad
Tags: evaluation, shortcodes, calculated fields
Donate link: https://www.wp-tweaks.com
Requires at least: 4.0
Tested up to: 5.4
Requires PHP: 5.2.4
Stable tag: tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Allows you to display calculated values in your posts and pages. You can even use dynamic shortcodes as variables!

== Description ==
Have you ever wanted to calculate some values dynamically to display in your posts? Let's say you have some shortcodes that retrieve values in real time from the database. Now you want to perform some calculations on those shortcodes and display the results in your posts or pages. This plugin allows you to use a simple [calculate] shortcode with the expression inside to display these values in real time!

So an expression like [calculate]2+3*4[/calculate] will return the value "14". Now just replace any numeric value with a shortcode that returns a number and you're set.

== Attributes ==

You can use the "dec" attribute to specify the number of decimal digits you want to round the result up to. The default is "0". Meaning that by default, all results will be rounded to an integer.

For international number formatting, you can use the "int" attribute to specify a locale. For example, int="fr_FR" will format the numbers according to the french locale!

See screenshots for illustrations.

For example, you can use this to display values in a table that are constantly changing. Or refer to dynamic prices multiplied by some value. The possibilities are endless.

You can see a real world example of this plugin at work [here:](https://www.wp-tweaks.com/how-to-calculate-values-in-wordpress-posts-and-pages/)

The best part is, it doesn't use dangerous functions like "eval" to work - which means all authors can use it safely. There's no danger of it breaking your site.

== Changelog ==

= 2.3 =

Changed <? to <?php in evalmath.class.php
Removed the deprecated "money_format" function and replaced it with NumberFormatter instead

= 2.2 =
Strip out HTML from any inside shortcodes

= 2.1.1 =
Fixed a potential deprecation error: "Methods with the same name as their class will not be constructors in a future version of PHP"

Thanks to Kristina for pointing it out!

= 2.1 =

Strip HTML from inside shortcodes

Added a new optional "int" attribute which will format the output in a local currency. So for example, [calculate int="fr_FR"]15.00[/calculate] will return 15,00

Fixed a potential deprecation error: "Methods with the same name as their class will not be constructors in a future version of PHP"


== Upgrade Notice ==

= 2.3 =
Removed a deprecated function and changed <? to <?php in evalmath

== Installation ==
1. You can install the plugin from WordPress's plugin repository.
2. Just click "Activate" to enable the shortcode.
3. Or download the zipped file
4. Then go to Plugins -> Add New
5. Click "Upload Plugin" at the top of the screen
6. Use "Choose File" , select the zip file you just downloaded, and click "Install Now"
7. On the next screen, click "Activate"

== Frequently Asked Questions ==
1. What math expressions can I use with this plugin?
This plugin supports all expressions allowed by EvalMath. You can see the documentation on this git page: https://github.com/dbojdo/eval-math

== Screenshots ==
1. Basic Shortcode Usage in Editor
2. Calculated value in the post
3. Shortcode usage with shortcodes inside