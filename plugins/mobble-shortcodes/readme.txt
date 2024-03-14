=== Mobble Shortcodes ===
Contributors: philipjohn
Tags: mobile, shortcode, mobble, responsive
Requires at least: 3.6
Tested up to: 4.0
Stable tag: 0.2.4
License: WTFPL
License URI: http://www.wtfpl.net/

Deliver mobile-specific content using the functionality in the Mobble plugin.

== Description ==

Mobble Shortcodes takes the functions available in the Mobble function and makes them available for use in pages, posts and other content.

It gives you the ability to deliver different content to different devices at will.

== Installation ==

See http://codex.wordpress.org/Managing_Plugins#Installing_Plugins

== Frequently Asked Questions ==

= What shortcodes are available? =

The most useful ones are:

`[is_handheld] (any handheld device e.g., phone, tablet, Nintendo)
[is_mobile] (any type of mobile phone e.g., iPhone, Android, etc)
[is_tablet] (any tablet device)
[is_ios] (any Apple device e.g., iPhone, iPad, iPod)`

You can also use:

`[is_iphone]
[is_ipad]
[is_ipod]
[is_android]
[is_blackberry]
[is_opera_mobile]
[is_symbian]
[is_kindle]
[is_windows_mobile]
[is_motorola]
[is_samsung]
[is_samsung_tablet]
[is_sony_ericsson]
[is_nintendo]`

You can also add 'not' to any of the above to negate the check, for example;
`[is_not_handheld] (NOT any handheld device e.g., phone, tablet, Nintendo)
[is_not_mobile] (NOT any type of mobile phone e.g., iPhone, Android, etc)
[is_not_tablet] (NOT any tablet device)
[is_not_ios] (NOT any Apple device e.g., iPhone, iPad, iPod)`

= Do you have any examples? =

Yup. Use it to annoy your visitors, if you want...

`[is_mobile]
I see you're on a mobile! This is an annoying message to bug you about our crappy mobile app.
[/is_mobile]`

This second example can direct people to the right mobile app store

`Get our super awesome app: 
Download from [is_android]<a href="https://play.google.com/store/apps/details?id=com.studio215.bigbangwhip">Google Play</a>[/is_android]
Download from [is_ios]<a href="https://itunes.apple.com/gb/app/pocket-whip/id319927587?mt=8">Apple App Store</a>[/is_ios]
`

= Caching =
Please note that in certain setups caching will cause undesired behaviour. If your cache is set too aggressively PHP will be skipped and the device detection will not work. 

= WTF is WTFPL? =

Why bother with the GPL when it limits use? WTFPL says you can do "What The Fuck you like" with this plugin. Print it on bedsheets and sleep with it for all I care.

== Changelog ==

= 0.2.4 =
* Fix for over-complicated failing shortcode hook

= 0.2.1 =
* i18n fixes

= 0.2 = 
* Added support for translations

= 0.1 =
* First version. Not a lot here.