=== Bazz CallBack widget ===
Contributors: glomberg
Donate link: https://www.paypal.me/bazzcallback
Tags: bazz, callback, call, call back, call request 
Requires at least: 3.0.1
Tested up to: 6.2
Stable tag: 3.23
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin makes a simple widget for callback on your website.

== Description ==

The plugin is intended for easy creating a widget on your website.
The widget will be performing "call-back function". That is, you will be recieving client's phone which the client will have specified in the widget by your e-mail.

Features:
* RTL ready

Supported languages:
* 🇺🇸 English
* 🇷🇺 Russian
* 🇺🇦 Ukrainian
* 🇫🇷 French
* 🇮🇹 Italian
* 🇪🇸 Spanish (Spain)
* 🇲🇽 Spanish (Mexico)
* 🇩🇪 German
* 🇸🇪 Swedish
* 🇷🇴 Romanian
* 🇵🇱 Polish
* 🇳🇱 Dutch
* 🇬🇷 Greek
* 🇮🇷 Persian
* 🇨🇳 Chinese

== Installation ==

Just install and activate the plugin, and the widget of a call-back Bazz CallBack will appear on your website.

== Frequently Asked Questions ==

= Do not receive an email from the widget =

1. Check if you entered the email address correctly in the plugin settings.
2. If, when you click on "Waiting for a call", the cursor takes the form of an expectation and nothing happens - it means an error in the plugin code. It can be found in the PHP logs and sent to the author for the study.
3. If upon clicking the letter is sent (the result text is displayed), but do not come to the mail, check the logs of the mail server. The message may be stuck due to incorrect mail server settings or due to the blocked sending option. On some hosting sites, the mail server works in a limited mode or specifically filters notifications from WordPress. In this case, setting up sending messages through an external SMTP server (for example gmail) can help you. The easiest way to configure this is with the appropriate plugin (see google).
4. Finally, check your spam folder for your mail :-).

= How to attach google/yandex targets? =

The plugin has a custom event "bazzSuccessSubmit" triggered on success submitting the form. So you can listen to this event and handle it then it triggered.
For example to follow yandex targets put on you page this code:
(replace ID and name of target to yours)
```
document.addEventListener( 'bazzSuccessSubmit', function( event ) {
	ym(111222333, 'reachGoal', 'NameOfTarget');
}, false );
```

== Screenshots ==

1. The widget looks so same

2. Configuration here

== Changelog ==

= 3.23 - 19 March 2023 =
* JS errors fixed.
* WP 6.2 support.

= 3.22 - 22 December 2020 =
* JS attaching fixed.
* WP 5.6 support.

= 3.21 - 22 October 2020 =
* JS custom event "bazzSuccessSubmit" added.

= 3.20 - 22 September 2020 =
* Fronend validation fixed.

= 3.19 - 19 March 2020 =
* Some layout updates.
* Simple spam protection.
* Translation updated.

= 3.18 - 06 March 2019 =
* Fix masked input bug
* Added Chinese language (thanks for Josh)

= 3.17 - 04 October 2018 =
* Fix some grammar
* Remake some phrases for future translations. It will be easy to translate for now.
* Added Greek language (thanks to PC Security)
* Added Romanian language (thanks to Genry Ceal)

= 3.16 - 21 August 2018 =
* Added Italian language (thanks to Bobo)

= 3.15 - 26 May 2018 =
* Added Spanish (Spain) language (thanks to Josie Monginsidi)

= 3.14 - 17 January 2018 =
* Added Spanish (Mexico) language (thanks to Ruben Lara)
* Added Swedish language (thanks to Alfons Passgård) 

= 3.13 - 17 January 2018 =
* Minor fixes.
* Added German language (thanks to Daniel)
* IF you want to translate the plugin into your language, email me!

= 3.12 - 17 November 2017 =
* Tested up to 4.9 WordPress
* Fix RTL bugs
* Fix vertical position of the text on the button.
* Change the name in the header FROM
* Added Dutch localization (thanks to Pascal)
* Added Polish localization (thanks Leszek Czerwiński)
* IF you want to translate the plugin into your language, email me!


= 3.11 - 11 October 2017 =
* Added RTL support
* Added Persian localization (thanks to Emad)
* Fix minor issues
* IF you want to translate the plugin into your language, email me!

= 3.10 - 03 October 2017 =
* Mistake fixed

= 3.9 - 28 September 2017 =
* UK localize added.
* Fix minor issues

= 3.8 - 26 August 2017 =
* Fixed "fixed" possition (position: fixed !important;) in CSS-file

= 3.7 - 28 July 2017 =
* Non-russion phones mask removed
* Russian 152 support.
* Phone number in email fixed.

= 3.6 - 04 July 2017 =
* Settings button added

= 3.5 - 25 May 2017 =
* Layout fixed
* Fix minor issues

= 3.4 - 26 April 2017 =
* Fix minor issues

= 3.3 - 26 April 2017 =
* Fix left-right opening error

= 3.2 - 09 March 2017 =
* The widget disabled on mobile screens
* Stiles versions implemented
* Z-index increased
* Fix minor issues

= 3.1 - 25 January 2017 =
* !Clear cache after updating (ctrl+F5)
* Fix minor issues

= 3.0 - 23 January 2017 =
* !Clear cache after updating (ctrl+F5)
* You can change color scheme
* You can change left/right side to the show
* Mail() function was changed to wp_mail()
* Fix translations
* Fix minor issues

= 2.3 - 20 January 2017 =
* Fix minor issues

= 2.2 - 23 December 2016 =
* Fix minor issues
* Phones masks

= 2.1 =
* Fix minor issues

= 2.0 =
* This is the major update.
* Locolization: dded default EN language
* Locolization: addition RU language
* Some options was deleted.


= 1.4 =
* Mistake fixed

= 1.3 =
* jQuery hook fixed
* Some logic fixed
* Right side position fixed (75рх)

= 1.2 =
* Selecting of working time implementing

= 1.1 =
* Allow to change working time
* Allow to change position
* Safari support fix

= 1.0 =
* Start version.