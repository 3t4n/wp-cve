=== International Namedays ===
Contributors: kgyt
Donate link: http://kgyt.eu/
Tags: névnap, imieniny, namnsdag, svátek, jmeniny, meniny, navnedag, nameday
Requires at least: 1.0
Tested up to: 3.9.1
Stable tag: 2.3

This plugin displays namedays for different countries.

== Description ==

This plugin displays namedays for different countries.

**Usage**

	kgyt_nameday( [DISPLAY], [COUNTRY], [TIMESTAMP] );

display (boolean) - if true (default) the nameday is visible

country (string)  - select two letter countrycode (hu, pl, no, se, dk, cz, sk)

timestamp (int)   - set the date or timezone


**Usage in posts**

Print today's Hungarian nameday:

	<!-- kgyt_nameday -->

or

Print today's nameday from special country:

	<!-- kgyt_nameday COUNTRY -->

or

Print Hungarian nameday of special time:

	<!-- kgyt_nameday TIMESTAMP -->

or

Print customized nameday:

	<!-- kgyt_nameday COUNTRY TIMESTAMP -->

country (string)  - select two letter countrycode (hu, pl, no, se, dk, cz, sk)

timestamp (int)   - set the date or timezone

**Examples**

Print today's Hungarian nameday:

	<?php kgyt_nameday(); ?>

Get yesterday Swedish nameday:

	<?php

		$nameday = kgyt_nameday( false, 'se', time() - ( 3600 * 24 ) );

		echo $nameday;

	?>

== Installation ==

Installation to [WordPress] (http://wordpress.org):

1. Upload plugin to the /wp-content/plugins/ directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Edit your theme's php files and insert the code like in description

== Frequently Asked Questions ==

= Where are you get the nameday lists =

From Wikipedia.

= Can I offer a job for you? =

Yes. Currently I am looking for a job.
Please don't hesitate if you are my next boss... :)
Yes! Budapest.

== Screenshots ==

1. Examples
2. See code in HTML output and in template...
