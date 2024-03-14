=== Date counter ===
Contributors: pankratovkm
Donate link: https://buymeacoffee.com/ko.pa
Tags: Date, counter, days, months, years, hours, minutes, seconds, difference, post date
Requires at least: 3.1
Tested up to: 6.4
Stable tag: 2.0.3
Requires PHP: 5.3
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
WC requires at least: 1.0
WC tested up to: 7.7

== Description ==

Date counter - is just a 9 kilobytes WordPress plugin.

Easily display the current date or calculate the difference between two dates.

== Current date & time ==

`Current date & time: [CurrentDatetime format="d/m/Y H:i"].`
`Current date & time: 05.07.2021 12:48.`

`[CurrentDatetime format="jS F, Y"].`
`6th July, 2021.`

`It's [CurrentDatetime format="g:i A (e)"].`
`It's 10:35 AM (UTC).`

You can find all possible formats in [documentation](https://date-counter.kopa.pw/#current_datetime "Date counter documentation.").

== Date & time difference ==

`I'm [DatetimeDifference startDate="1998-08-25" endDate="now" format="Y"] years old.`
`I'm 23 years old now.`

`[DatetimeDifference startDate="31.12.2020" endDate="now" format="a"] days have passed since the new year.`
`187 days have passed since the new year.`

`Tomorrow's trip at 14:35 (2:35 PM) starts in [DatetimeDifference startDate="now" endDate="07.07.2021 14:35" format="h hours & i minutes"].`
`Tomorrow's trip at 14:35 (2:35 PM) starts in 8 hours & 34 minutes.`

You can find all possible formats in [documentation](https://date-counter.kopa.pw/#datetime_difference "Date counter documentation.").

== Total date & time difference ==

`The store will open in [TotalDatetimeDifference startDate="now" endDate="16.07.2021 8:00" format="i"] minutes today.`
`The store will open in 19 minutes today.`

`[TotalDatetimeDifference startDate="now" endDate="25.08.2025" format="d"] days left until my b-day.`
`39 days left until my b-day.`

You can find all possible formats in [documentation](https://date-counter.kopa.pw/#total_datetime_difference "Date counter documentation.").

== 🤘Extra ==

= Shift from today's date: ± N days | ± N weeks | ± N months | ± N years =
`[DatetimeDifference startDate="now" endDate="+1day" format="d"]`
`[TotalDatetimeDifference startDate="-3years" endDate="now" format="d"]`

= Post created & modified date =
`[DatetimeDifference startDate="post:created" endDate="now" format="d"]`
`[TotalDatetimeDifference startDate="now" endDate="post:modified" format="d"]`

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/date-counter` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Place shortcode.

== Upgrade Notice ==

= 2.0.3 =
Added support for creation and modification dates of the current post via new values of startDate and endDate - post:created and post:modified.

= 2.0.2 =
Added a function to calculate the total difference between date & time. Minor fixes.

= 2.0.1 =
Fixed backwards compatibility with v1

= 2.0.0 =
The new version of the plugin, even easier and even more features!

= 1.0.4 =
Added support for Time Zone management. You can now change the time zone using the WordPress settings.

= 1.0.2 =
Added support for [PHP DateInterval::format](https://www.php.net/manual/ru/dateinterval.format.php)

== Changelog ==

= 2.0.3 =
Fixed exception messages in DatetimeDifference and TotalDatetimeDiffrence classes. Added support for creation and modification dates of the current post.

= 2.0.0 =
Redesigned plugin: added new features and simplified general workflow.

= 1.0.4 =
Added support for Time Zone management. You can now change the time zone using the WordPress settings.

= 1.0.2 =
* Improved calculations
* Added support for [PHP DateInterval::format](https://www.php.net/manual/ru/dateinterval.format.php)

= 1.0.1 =
* Just a first version of a Date counter plugin.
