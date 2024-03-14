=== Speedcheck Internet Speed Test ===

Contributors: etrality
Tags: speed test, internet speed test, speedtest, internet speed, test internet speed
Requires PHP: 4.6
Requires at least: 4.6
Tested up to: 5.0
Stable tag: /trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Speedcheck plugin lets you embed the internet speed test on your website via a shortcode. Let your visitors easily test their internet speed.

== Description ==

Speedcheck is an internet speed test that started out as an iOS and Android app with over 20 million downloads. Our users run multiple hundreas of thousands of speed tests each day making Speedcheck one of the most popular speed test solutions in the world.

=== Features ===
* Test your download and upload speed as well as latency (ping)
* Proven technology - The Speedcheck Plugin runs on the same proven speed test technology as our apps and website
* Optimized for modern browsers - Developed in HTML5 and for mobile devices the entire plugin has less than 20 kilobytes, making it super fast to load.
* High Speed 10Gbps Server infrastructure ensure reliablity and repeatability for each speed test.
* Available in 14 languages: Arabic (ar), German (de), English (en), Spanish (es), French (fr), Indonesian (id), Italian (it), Japanese (ja), Korean (ko), Dutch (nl), Polish (pl), Portuguese (pt), Russian (ru), Swedish (sv)

=== How to ===
Use the `[speedcheck]` shortcode whereever you want to display the speed test plugin. The following shortcode attributes can be used to customize language, style and positioning.

* **language** - Choose from 14 languages (ar, de, en, es, fr, id, it, ja, ko, nl, pl, pt, ru, sv), defaults to en
* **center** - Set to `true` to center the plugin, defaults to `false`
* **border** - Set to `true` to show border around the plugin, defaults to `false`
* **link** - Set to `true` to enable attribution, defaults to `false`

==== Examples of use ====
* `[speedcheck]` - Simplest implementation 
* `[speedcheck language=es]` - Implementation with language set to spanish
* `[speedcheck center=true border=true]` - Implementation centering the plugin and encasing it with a border
* `[speedcheck link=true]` - Implementation enabling attribution; your support is much appreciated

== Installation ==

1. Install the plugin via the Wordpress Plugin Manager or upload the plugin to your site.
2. Activate the plugin in the `Plugins` section.
3. Add the shortcode `[speedcheck]` whereever you want to display the speed test.
4. Happy testing!

== Screenshots ==

1. Speedcheck Plugin as displayed in default state
2. Running Speed Test
3. Ping, Download Speed and Upload Speed results presented