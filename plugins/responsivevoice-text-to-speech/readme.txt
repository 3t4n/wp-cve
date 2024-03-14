=== ResponsiveVoice Text To Speech ===
Contributors: ResponsiveVoice
Author: ResponsiveVoice
Donate link: https://responsivevoice.com/wordpress-text-to-speech-plugin
Tags: audio, google translate, Google Voice, speech, text to audio, text to speech, text-to-speech, text2speech, speech synthesis api, webspeech api, voice, espeak, javascript, Speak, speech, spoken, text, text-to-speech, talk, listen, accessibility, a11y, webreader, ivona, tts, arabic, mp3, ogg, buddypress, button, chinese, english, french, german, google, welcome, greetings, hebrew, html, html5, iPad, iphone, italian, jquery, link, links, menus, mobile, multilingual, page, pages, plugin, plugins, portuguese, Post, posts, readability, Russian, seo, shortcode, sidebar, sound, spanish, gspeech, stats, tablet, tts, widget, wordpress, button, blind, visual impairment, elderly, ADA, BS 8878:2010, WCAG 2.0, Web Content Accessibility Guidelines 2.0
Requires at least: 3.6
Tested up to: 6.4
Stable tag: 1.7.10
License: GPLv2

ResponsiveVoice the leading HTML5 text to speech synthesis solution, is now available for WordPress. Over 51 languages through 168 voices.

== Description ==
[ResponsiveVoice](https://responsivevoice.com/wordpress-text-to-speech-plugin) is a HTML5-based Text-To-Speech library designed to add voice features to WordPress across all smartphone, tablet and desktop devices. It supports 51 languages through 168 voices and has no dependencies.

Languages include UK English, US English, Spanish, French, Deutsch, Italian, Greek, Hungarian, Turkish, Russian, Dutch, Swedish, Norwegian, Japanese, Korean, Chinese, Hindi, Serbian, Croatian, Bosnian, Romanian, Catalan, Australian, Finnish, Afrikaans, Albanian, Arabic, Armenian, Czech, Danish, Esperanto, Hatian Creole, Icelandic, Indonesian, Latin, Latvian, Macedonian, Moldavian, Montenegrin, Polish, Brazilian Portuguese, Portuguese, Serbo-Croatian, Slovak, Spanish Latin American, Swahili, Tamil, Thai, Vietnamese and Welsh.


### Support and Questions visit here first:
> * [Support](https://responsivevoice.com/support)

### Useful Links:
> * [Live Demo](https://responsivevoice.com/wordpress-text-to-speech-plugin)
> * [Homepage](https://responsivevoice.com/wordpress-text-to-speech-plugin)
> * [Documentation](https://responsivevoice.com/wordpress-text-to-speech-plugin)

### Features:
* Listen to any post or page with the tap of a button
* Shortcodes to place Listen button anywhere on the post or page
* 51 languages supported through 168 voices
* Unlimited text to speech
* Easy access to content for website users, tap to listen to your page or post read aloud
* A more functional website for a range of users including visually impaired and the elderly
* Web Accessibility Compliance Group 2.0, ADA and BS 8878:2010 features

### Usage:

* From version 1.6.6, we added API Key configuration to Wordpress settings. To experience the complete range of voices,
[register free and get your API Key](https://responsivevoice.org/register). The plugin will still work without it, but
you will be limited to available browser voices.

* It's Easy - To have the Listen button appear put the following shortcode anywhere in the text of your page or post.
`[responsivevoice_button]`

You can select a voice by using the "voice" parameter, and change the text that appears on the button with the "buttontext" parameter. The following shortcode will read in the US English Female voice, and the button will say "Play".
`[responsivevoice_button voice="US English Female" buttontext="Play"]`
A full list of ResponsiveVoice names is available at [Documentation](https://responsivevoice.org/text-to-speech-languages/). Default is UK English Female.

* Read a whole page, or just sections of text - If you don't want the whole page or post to be read, just surround the salient text with
`[responsivevoice]Text you want ResponsiveVoice to read [/responsivevoice]`
This tag also supports the voice and buttontext parameters. Additionally, you can place the button after the text (it will be before by default) with the "buttonposition" tag, like so:
`[responsivevoice buttonposition="after"]Text you want ResponsiveVoice to read [/responsivevoice]`

The shortcodes also allow other parameters. You can adjust the Voice's pitch, volume and rate with
`[responsivevoice_button rate="1" pitch="1.2" volume="0.8" voice="US English Female" buttontext="Play"]`

For more details, please see the [Documentation](https://responsivevoice.com/wordpress-text-to-speech-plugin)

= Requirements =

There are no requirements, you do not need to install cURL.

== Frequently Asked Questions ==

You can read our FAQs [here](https://responsivevoice.freshdesk.com/support/solutions "ResponsiveVoice Helpdesk")

If you have experienced any problems with this plugin please let us know by contacting our support department at [Support](https://responsivevoice.com/support "ResponsiveVoice support") website.

== Installation ==

1. Unzip files.
2. Upload the entire responsivevoice-text-to-speech folder to the /wp-content/plugins/ directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Use the shortcodes in your post or page.
5. Enjoy!

== Screenshots ==

1. ResponsiveVoice Listen button in a Wordpress post.

== Changelog ==

= Version 1.7.10 =
* Update "Tested up to" Wordpress 6.4 release

= Version 1.7.9 =
* Update "Tested up to" Wordpress 6.3 release

= Version 1.7.8 =
* Update "Tested up to" Wordpress 6.2 release

= Version 1.7.7 =
* Add sanitation to shortcode attributes 
* Update "Tested up to" Wordpress 6.1.1 release

= Version 1.7.6 =
* Update "Tested up to" for upcoming Wordpress 6.1 release

= Version 1.7.5 =
* Upgrade to ResponsiveVoice 1.8.3

= Version 1.7.4 =
* Fix API Key include logic when the plugin is first installed
* Update "Tested up to" for upcoming Wordpress 6.0 release

= Version 1.7.3 =
* Upgrade to ResponsiveVoice 1.8.2

= Version 1.7.2 =
* Update "Tested up to" for upcoming Wordpress release

= Version 1.7.1 =
* Upgrade to ResponsiveVoice 1.8.1

= Version 1.7.0 =
* Add `responsivevoice_content_before_cleaning` and `responsivevoice_content_after_cleaning` filter hooks for plugin and theme developers.

= Version 1.6.9 =
* Upgrade to ResponsiveVoice 1.8.0
* Analytics improvement

= Version 1.6.8 =
* Prepare for Wordpress 5.7 release

= Version 1.6.7 =
* Fix issue with API key not being recognized by the plugin.
* Upgrade to ResponsiveVoice 1.7.0
* Slower default rate of speech for Croatian Male

= Version 1.6.6 =
* Add API Key configuration to the Wordpress plugin settings

= Version 1.6.5 =
* Upgrade to ResponsiveVoice 1.6.5
* Show permission popup (unless disabled) if TTS is blocked by browser due to lack of user interaction
* Give more informative error if the voice name supplied to responsiveVoice.speak does not exist

= Version 1.6.4 =
* Upgrade to ResponsiveVoice 1.6.4
* Fix permission popup not always appearing if browser doesn't support SpeechSynthesis

= Version 1.6.3 =
* Upgrade to ResponsiveVoice 1.6.3
* Deprecate some voices for which we cannot guarantee a fallback gender: Brazilian Portuguese Male, Czech Male, Danish Male, Finnish Male, Greek Male, Hungarian Male, Russian Male, Slovak Male, Spanish Male.
They have *not* been removed from the platform, and will continue to work in existing installations; however unless the male voice is available on the browser/OS, they will be female instead of male.
* Don't show deprecated voices in voice selector within [ResponsiveVoiceBox] shortcode

= Version 1.6.2 =
* Fix an alternate encoding of em-dashes being read aloud

= Version 1.6.1 =
* Upgrade to ResponsiveVoice 1.6.2
* Fix Chinese speech cut off prematurely
* Fix Greek Female does not support fast speech rate
* Fix US English Female with the wrong gender voice on recent versions of Chrome desktop (MacOS/Windows)
* Fixes Array.from with a polyfill for Internet Explorer 11
* Add comprehensible error handling and messages
* Add console error when not using an API Key
* Fix last version number in this changelog

= Version 1.6.0 =
* Upgrade to ResponsiveVoice 1.5.17
* Fix Classic Editor double-encoding some quotes
* Fix eszett, emdash, and other safe-for-text symbols being encoded and read aloud

= Version 1.5.16 =
* Upgrade to ResponsiveVoice 1.5.16
* Fixed Button won't trigger audio on Android
* Removed references to English United Kingdom (android female) voice in US English Male and UK English Male ResponsiveVoices
* Fixed Japanese Female changed to Japanese Male in Chrome Desktop
* Fixed Siri voices speaking opposite gender with UK Female, US Male, Australian Male, Japanese Male
* Fixed Native tests voices not changing on android
* Added Tamil female voice
* Added iOS 13 voices
* Added Android 9 native voices on all existing voice profiles
* Added Microsoft Edge Dev 77.0.189.3 (Official build) dev (64-bit) server side native voices for US English Male and US English Female
* Added Bangla Bangladesh Male/Female TTS Voice
* Added Bangla India Male/Female TTS Voice
* Added Estonian Male TTS Voice
* Added Filipino Female TTS Voice
* Added French Canadian Female TTS Voice
* Added Khmer Cambodian Female TTS Voice
* Added Nepali Female TTS Voice
* Added Sinhala Sri Lanka Female TTS Voice
* Added Ukrainian Female. TTS Voice

= Version 1.5.15 =
* Upgrade to ResponsiveVoice 1.5.15

= Version 1.5.14 =
* Fixed text cutoff for male fallback voices on very long texts

= Version 1.5.13 =
* Fixed Male Fallback Voices

= Version 1.5.12 =
* Fixed US English Male Fallback Voice

= 1.5.11 =
* Fixed support for OGG

= 1.5.10 =
* Upgrade to ResponsiveVoice 1.5.10
* Fixed iOS 12.0.1 bug with languages in native TTS
* Fixed Hungarian Female voice

= 1.5.9 =
* Upgrade to ResponsiveVoice 1.5.9
* Fix infinite loop with words longer than the character limit
* Add Windows 7 US English Female (Anna) and Chinese Female (Lili) voices

= 1.5.8 =
* Adjust rate, volume and pitch through shortcode
* Full support for US English Male TTS Voice
* Full support for Arabic Male TTS Voice
* Full support for Chinese Male TTS Voice
* Full support for Chinese Hong Kong Male TTS Voice
* Added French Male TTS Voice
* Added Deutsch Male TTS Voice / German Male TTS Voice
* Added Dutch Male TTS Voice
* Added Hindi Male TTS Voice
* Added Indonesian Male TTS Voice
* Added Italian Male TTS Voice
* Added Japanese Male TTS Voice
* Added Korean Male TTS Voice
* Added Polish Male TTS Voice
* Added Brazilian Portuguese Male TTS Voice
* Added Portuguese Male TTS Voice
* Added Spanish Male TTS Voice
* Added Spanish Latin American Male TTS Voice
* Added Thai Male TTS Voice
* Added Turkish Male TTS Voice
* Added Vietnamese Female TTS Voice
* Added Moldavian Female TTS Voice
* Resurrected Greek Male TTS Voice, Swedish Male TTS Voice, Finnish Male TTS Voice, Vietnamese Male TTS Voice, Latin Male TTS Voice
* Added full pitch support for Norwegian Female TTS Voice, Finnish Female TTS Voice, Arabic Female TTS Voice, Armenian Male TTS Voice, Danish Female TTS Voice, Brazilian Portuguese Female TTS Voice, Slovak Female TTS Voice, Spanish (Latin American) TTS Voice
* Romanian Male TTS Voice replaced with Romanian Female TTS Voice
* Deprecated Latin Female TTS Voice, Moldavian Male TTS Voice

= 1.5.7 =
* Upgrade to ResponsiveVoice 1.5.7
* Improve HTML5 audio stability and initialization
* Improve look and behaviour of request for permission
* Improved time estimation for currencies
* Fixed onend event on iOS

= 1.5.6 =
* Upgrade to ResponsiveVoice 1.5.6
* Improve fallback handling

= 1.5.5 =
* Align version with main ResponsiveVoice library to provide more precise support.
* Fix bug causing some paragraphs to be skipped in very long texts.
* Improved HTML5 audio stability.
* Improved HTML5 audio and TTS initialization on iOS and Android.
* Fixed support for HTML5 audio on Android.
* Added 54 Microsoft Edge native voices.
* Improved native voice matching.
* Improved Split sentences.
* Improved Decimal places interpreted as pause.
* Fixed Taiwan native voice priority.
* Use new iOS10 voices when available for native TTS.
* Use Edge non-English voices for native TTS.
* Improved time estimation algorithm.
* Fixed overlap issue on Android fallback mode.
* Improve support of non-latin character voices.
* Deprecated voice: Arabic Male – Updated to Arabic Female.
* Deprecated voice: Danish Male (no longer supported, mapped to female).
* Deprecated voice: Finnish Male (no longer supported, mapped to female).
* Deprecated voice: Greek Male (no longer supported, mapped to female).
* Deprecated voice: Latin Male (no longer supported, mapped to female).
* Deprecated voice: Slovak Male (no longer supported, mapped to female).
* Deprecated voice: Swedish Male (no longer supported, mapped to female).
* Deprecated voice: Vietnamese Male (no longer supported, mapped to female).
* Minor bugfixes and stability improvements.

= 1.1.7 =
* Add buttonposition parameter to [responsivevoice] tag.
* Buttons can now be styled through the responsivevoice-button class.
* Update compatibility with latest Wordpress release.

= 1.1.6 =
* Load ResponsiveVoice through HTTPS.
* Position ResponsiveVoice button to before paragraph instead of after.

= 1.1.5 =
* Update compatibility with Wordpress release.

= 1.1.4 =
* Releases will now be properly tagged, tags can be found in the "tags" folder as usual.
* Added responsivevoice.css and responsivevoice-includes.php.
* FIX: apostrophes, quotation marks, &, <, >, non-breaking spaces and en dashes will no longer be converted to ASCII codes.

= 1.1.3 =
* Removed ResponsiveVoice icon from buttons, now the speaker emoji is displayed instead.
* FIX: Text in the button should not wrap around anymore.

= 1.1.2 =
* FIX: multiple instances of ResponsiveVoice buttons now work on the same page.
* FIX: fixed vertical alignment of the ResponsiveVoice logo in buttons.
* FEATURE: added the possibility to only speak a piece of text. Just surround it with [responsivevoice] and [/responsivevoice]. Its parameters are voice and buttontext, like with [responsivevoice_button].

= 1.1.1 =
* FIX: Text in [responsivevoice_button] won't wrap anymore.
* Added FAQ and Support links to the plugin's action row in Wordpress' "Installed plugins" page.

= 1.1 =
* Clicking on the RVListenButton on a page while a voice is playing will now stop it.
* Added support for new standardized shortcode, [RVListenButton].
* Added support for a "voice" parameter for [RVListenButton], which defaults to UK English Female.
* Added support for a "buttontext" parameter for [RVListenButton], which defaults to "Listen to this".

= 1.0.5 =
* Support for voice attribute in shortcode

= 1.0 =
* This is the initial release of the plugin

== Upgrade Notice ==

= 1.7.10 =
* Upgrade the plugin for the latest improvements.
