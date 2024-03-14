=== Say It! ===
Contributors: dadipaq
Tags: text-to-speech, audio, voice, tts, text-to-audio, amazon-polly, google-voice
Requires at least: 5.0.0
Tested up to: 5.8.2
Stable tag: 4.0.1
Requires PHP: 7.4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Text to speech plugin helping your website easily say something !


== Description ==

Say It! allows you to easily turn parts of your WordPress articles into audio speech.
The plugin is using modern HTML5 Speech Synthesis and doesn't require any subscriptions or service, install it, enjoy it!

Once installed, you just need to wrap any content between [sayit] shortcode. Once done, your users can simply click on the text to make it speak.

Want better quality ? Say It! now offers Google Cloud TTS and Amazon Polly compatibility !

= Parameters (for HTML5 Speech) =
* lang - Use a language different from the default one
* speed - speed of speech (recommanded between 0.5 and 1.5)
* block - set to "1" to make it work on multiple paragraphs at once

= Parameters (for Google TTS) =
* lang - Use a language different from the default one
* block - set to "1" to make it work on multiple paragraphs at once

= Parameters (for Amazon Polly) =
* block - set to "1" to make it work on multiple paragraphs at once

= Exemple =
[sayit block="1" lang="en-GB" speed="1"]
Hello I am the queen
And I talk for two paragraphs long
[/sayit]
    
== Installation ==

1. Download It
2. Install It
3. Activate It
4. use [sayit] shortcode
5. Say It!

== Frequently Asked Questions ==

= How much does it cost ? =

It cost a five star Review, just kiding, it's free.
Note : If you use the Google TTS, you'll need to give your banking informations to Google and you can be charged if you use it a lot, however SayIt! is using a smart caching system, so it request Google only for new voice Generation.

= Can I mix language ? =

Yes you can ! In the settings, you choose the default language to use but you can pass parameter in the shortcodes like so [sayit lang="fr-FR"]Bonjour[/sayit]
Note that you can't mix language yet if you are using Google TTS.

= Do you collect any data? =

No, coding something to collect data is kind of complicated, no time for that.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0.0 =
* First Release

= 1.1.0 =
* Make it work on multiple paragraphs
* Improved documentation
* Various Bug fixes

= 2.0.0 =
* Added Google Cloud TTS compatibility
* Improved stability
* New admin interface

= 2.0.1 =
* Fix missing google library

= 2.0.2 =
* Fix header ouput on google TTS Error
* Fallback to HTML5 if Error

= 2.1.0 =
* Improved Google TTS admin area
* Fixed js console output admin area
* Handle config error for Google TTS 

= 3.0.0 =
* Added Amazon Polly
* Theme functions
* Speech pause on window blur 

= 3.0.1 =
* Bug Fix

= 3.0.2 =
* Added Possibility to change tooltip text

= 3.0.3 =
* Prevent audio play when click on a link inside sayit shortcode

= 3.0.4 =
* Fix error displayed on footer

= 3.0.5 =
* Added chinese language to html5 & google TTS

= 3.0.6 =
* Fixed a bug when using special character with html5

= 3.0.7 =
* Remove missadded "hello" word

= 3.0.8 =
* Added option to change google language with shortcode

= 3.0.9 =
* Bug fix

= 4.0.0 =
* Refactored code
* New admin interface
* Added Gutenberg support

= 4.0.1 =
* Fixed an error due to php DOMDocument