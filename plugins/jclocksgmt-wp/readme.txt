=== jClocksGMT World Clocks ===
Contributors: kingkode
Donate link: http://kingkode.com/donate/
Tags: time, clock, world clock, timezone
Requires at least: 3.0.1
Tested up to: 4.5.2
Stable tag: 4.5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

jQuery based analog and digital world clocks for Wordpress.

== Description ==

jClocksGMT is a jQuery analog and digital world clocks plugin based on GMT offsets. 
Now supporting automatic daylight saving time conversions for affected timezones. 
Requires jQuery Rotate plugin.

`[jclocksgmt]`
This shortcode will display the default clock for Greenwich, England

Additional attributes: 

* title
 * Usage `[jclocksgmt title='Houston, TX, USA']` Title of location
* offset
 * Usage `[jclocksgmt offset='-6']` Set Standard GMT offset
* dst
 * Usage `[jclocksgmt dst='true]` set FALSE if location does not need to observe dst
* digital
 * Usage `[jclocksgmt digital=true]` Display digital clock
* analog
 * Usage `[jclocksgmt analog=true]` Display analog clock
* timeformat
 * Usage `[jclocksgmt timeformat='hh:mm A']` Time format
* date
 * Usage `[jclocksgmt date=false]` Display date
* dateformat
 * Usage `[jclocksgmt dateformat='MM/DD/YYYY']` Date format
* skin
 * Usage `[jclocksgmt skin=1]` Set 1 of 5 clock themes.

A basic shortcode for a custom location would look like this:
`[jclocksgmt title='Houston, TX, USA' offset='-6']`

See more documentation in FAQ section.

== Installation ==

1. Upload the `jclocksgmt-wp` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Activate the included widgets for display in your theme or use the following shortcodes:
1. Put the shortcode `[jclocksgmt]` on any page to display the clock.

See more documentation in FAQ section.

== Frequently Asked Questions ==

= What are shortcodes? =

A shortcode is a WordPress-specific code that lets you do nifty things with very little effort. Shortcodes can embed files or create objects that would normally require lots of complicated, ugly code in just one line. Shortcode = shortcut.  To use a shortcode, simple enter it on the page or post in your WordPress blog as described below and it will be replaced on the live page with the additional functionality.

= What shortcodes does jClocksGMT-WP use? =

`[jclocksgmt]`
This shortcode will display the default clock for Greenwich, England

Additional attributes: 

* title
 * Usage `[jclocksgmt title='Houston, TX, USA']` Title of location
* offset
 * Usage `[jclocksgmt offset='-6']` Set Standard GMT offset
* dst
 * Usage `[jclocksgmt dst='true]` set FALSE if location does not need to observe dst
* digital
 * Usage `[jclocksgmt digital=true]` Display digital clock
* analog
 * Usage `[jclocksgmt analog=true]` Display analog clock
* timeformat
 * Usage `[jclocksgmt timeformat='hh:mm A']` Time format
* date
 * Usage `[jclocksgmt date=false]` Display date
* dateformat
 * Usage `[jclocksgmt dateformat='MM/DD/YYYY']` Date format
* skin
 * Usage `[jclocksgmt skin=1]` Set 1 of 5 clock themes.

A basic shortcode for a custom location would look like this: 
`[jclocksgmt title='Houston, TX, USA' offset='-6']`

= What are GMT offsets? =

GMT offsets are the hour differences from one location compared to Greenwich Mean Time in Greenwich, London.

Common offsets by time zone: 
(only use the number after GMT: GMT-2 = offset: '-2' Daylight Saving Time converted automatically)


* `GMT-12` |  Eniwetok
* `GMT-11` |  Samoa
* `GMT-10` |  Hawaii
* `GMT-9` |  Alaska
* `GMT-8` |  PST, Pacific US 
* `GMT-7` |  MST, Mountain US
* `GMT-6` |  CST, Central US
* `GMT-5` |  EST, Eastern US
* `GMT-4` |  Atlantic, Canada
* `GMT-3` |  Brazilia, Buenos Aries
* `GMT-2` |  Mid-Atlantic
* `GMT-1` |  Cape Verdes
* `GMT 0` |  Greenwich Mean Time
* `GMT+1` |  Berlin, Rome
* `GMT+2` |  Israel, Cairo
* `GMT+3` |  Moscow, Kuwait
* `GMT+7` |  Abu Dhabi, Muscat
* `GMT+5` |  Islamabad, Karachi
* `GMT+6` |  Almaty, Dhaka
* `GMT+7` |  Bangkok, Jakarta
* `GMT+8` |  Hong Kong, Beijing
* `GMT+9` |  Tokyo, Osaka
* `GMT+10` |  Sydney, Melbourne, Guam
* `GMT+11` |  Magadan, Soloman Is.
* `GMT+12` |  Fiji, Wellington, Auckland

= What are the rules for formatting date and time? =

**Time Formatting:**

FORMAT| OUTPUT | MEANING

* `HH` | `19` | 24-hour format of hour with leading zero (two digits long). 
* `hh` | `07` | 12-hour format of hour with leading zero (two digits long). 
* `H` | `19` | 24-hour format of hour without leading zeros. 
* `h` | `7` | 12-hour format of hour without leading zeros. 
* `mm` | `01` | Minutes with the leading zero (two digits long). 
* `m` | `1` | Minutes without the leading zero. 
* `ss` | `08` | Seconds with the leading zero (two digits long). 
* `s` | `8` | Seconds without the leading zero. 
* `a` | `pm` | Lowercase am or pm. 
* `A` | `PM` | Uppercase am or pm. 
* `SSS` | `095` | Milliseconds with leading zeros (three digits long). 
* `S` | `95` | Milliseconds without leading zeros.

**Date Formatting:**

FORMAT| OUTPUT | MEANING

* `YYYY` | `2016` | Four-digit representation of the year. 
* `YY` | `16` | Two-digit representation of the year. 
* `MMMM` | `April` | Full textual representation of the month. 
* `MMM` | `Apr` | Three letter representation of the month. 
* `MM` | `04` | Month with the leading zero (two digits long). 
* `M` | `4` | Month without the leading zero. 
* `DDDD` | `Friday` | Full textual representation of the day of the week. 
* `DDD` | `Fri` | Three letter representation of the day of the week. 
* `DD` | `01` | Day of the month with leading zero (two digits long). 
* `D` | `1` | Day of the month without leading zero. 

== Screenshots ==

1. Example of Clocks
2. Promotional Image

== Changelog ==

= 1.0.2 =
* Compatible with Wordpress 4.5.2
= 1.0.1 =
* Fixed image path error
= 1.0 =
* Initial release