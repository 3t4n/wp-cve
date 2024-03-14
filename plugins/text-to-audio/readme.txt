=== Text To Speech TTS Accessibility ===
Contributors: atlasaidev, hasanazizul
Donate link: http://atlasaidev.com/
Tags: accessibility, speech, tts, text to speech, text to audio
Requires at least: 4.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.5.19
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add accessibility to WordPress site to read contents out loud in more than 51 languages.

== Description ==

Add accessibility to WordPress site to read contents out loud in more than 51 languages.

There is no need to create an account text to speech plugin is completely free. Just install the plugin and enjoy the whole features of the plugin.

Adding text-to-speech (TTS) accessibility to your WordPress website can make your site more accessible to people with disabilities and enhance the experience of users that prefer listening to content over reading.

== Free text to speech (TTS) plugin for WordPress - Video Tutorial ==
[youtube https://www.youtube.com/watch?v=P_dw_YjnVxc&t=21s&ab_channel=AtlasAiDev]

### SUPPORT AND QUESTIONS VISIT HERE:
> * [Support](http://atlasaidev.com/contact-us/)

### USEFULL LINKS:
> * [Live Demo](http://atlasaidev.com/text-to-speech/)
> * [Text To Speech Pro](https://atlasaidev.com/text-to-speech-pro/)
> * [Video Tutorial](https://www.youtube.com/@atlasaidev)


### USAGE:
 * It’s Easy – To have the text to audio button in the content put the following shortcode anywhere in the text of your page or post.
	
	`[tta_listen_btn]`

* TTS button text can be changed by providing attributes to shortcode like this.

	`
	[tta_listen_btn listen_text="Listen" pause_text="Pause"  resume_text="Resume" replay_text="Replay"]
	`

* It can be possible to create a shortcode with custom text to read like this.

	`
	[tta_listen_btn text_to_read="Hello WordPress" ]
	`

* Add class on shortcode as an attribute. Example : 

	`
	[tta_listen_btn class="custom_class"]
	`	
* Change language and voice : 

	`
	[tta_listen_btn lang="en-GB" voice="Google US English"]
	`	

* Missing content can be added by filter. Example:

	`
    add_filter( 'tta__content_description', 'tta__content_description_callback', 10, 3 );
    function tta__content_description_callback ( $output, $description, $post_id ) {

		$output .=" extra content here";
		
		return $output;
    }
	` 

### Tex To Speech Pro Features:

* 51 languages support.
* Get Live Support for first time integration.
* Get Priority Support.
* Engage with your customers more interactively.
* Listen content while doing other task.
* Improved UI of the button.
* Multilingual support.
* Multi-lang Websites
* Responsive speaking button.
* Specify speaking content with CSS selectors.
* Translate your content to any language by this plugin.
* Support all custom post types.
* Download **mp3** file.
* Unlimited Downloads
* Multiple audio player support.

Try [Text To Speech Pro](https://atlasaidev.com/text-to-speech-pro/) version.

### Text To Speech Pro Supported Languages:

[Text To Speech Pro](https://atlasaidev.com/text-to-speech-pro/) TTS Accessibility plugin supports these languages.

Afrikaans, Albanian, Arabic, Armenian, Catalan, Chinese, 
Chinese (Mandarin/China), Chinese (Mandarin/Taiwan), 
Chinese (Cantonese), Croatian, Czech, Danish, Dutch, 
English, English (Australia), English (United Kingdom), 
English (United States), Esperanto, Finnish, French, German, 
Greek, Haitian Creole, Hindi, Hungarian, Icelandic, 
Indonesian, Italian, Japanese, Korean, Latin, Latvian, 
Macedonian, Norwegian, Polish, Portuguese, Portuguese (Brazil), 
Romanian, Russian, Serbian, Slovak, Spanish, Spanish (Spain), 
Spanish (United States), Swahili, Swedish, Tamil, Thai, 
Turkish, Vietnamese, Welsh


**IMPORTANT NOTE:**

Text To Speech TTS plugin is built on browser speechSynthesis API. No external API is used. Here is the API used [speechSynthesis](https://developer.mozilla.org/en-US/docs/Web/API/SpeechSynthesis).
That is why Text To Speech TTS doesn’t support all android phones, aslo all languages. Here you can check which android phone and which device support [speechSynthesis](https://developer.mozilla.org/en-US/docs/Web/API/SpeechSynthesis#browser_compatibility) API.

Another issue speechSynthesis API is differ browser to browser also device to device . So it changes the voices and languages based on browser. one language may available on desktop
It can be not available on mobile phone. One voice may available on desktop, it may be not available on android.

Here you can see some languages which are supported by the browsers based on device.

### Tex To Speech Free Supported Languages:

* Chrome Desktop: UK English, US English, Spanish ( Spain ), Spanish ( United States ), French, Deutsch, Italian, Russian, Dutch, Japanese, Korean, Chinese (China), Chinese (Hong Kong), Chinese (Taiwan) Hindi, Indonesian, Polish, Brazilian Portuguese.
* Chrome Mobile: English USA, English UK, German, Italian, Russian, French, Spanish, 

* Microsoft Edge Desktop : All Languages.
* Microsoft Edge Mobile : All Languages.

* FireFox Desktop: English.
* FireFox Mobile: English USA, English UK, German, Italian, Russian, French, Spanish.


### Tex To Speech Free Features:

* Add a play button to any post or page.
* Unlimited text to speech.
* Add more functionality to the website for a range of users including the visually impaired and the old people.
* Customization of button color, width and button text based on site language through [filter](https://wordpress.org/plugins/text-to-audio/#:~:text=How%20to%20change%20button%20text%3F) and [shortcode](https://wordpress.org/plugins/text-to-audio/#description:~:text=TTS%20button%20text%20can%20be%20changed%20by%20providing%20attributes%20to%20shortcode%20like%20this.).
* Live preview of play button during customization.
* Add custom CSS and custom class to the button.
* Change listening language to any language.
* Choose a voice from more than 20 voices.
* Customization of button in block editor.
* Play button can be added by shortcode `[tta_listen_btn]`.
* Add custom content to speak with [shortcode](https://wordpress.org/plugins/text-to-audio/#description:~:text=It%20can%20be%20possible%20to%20create%20a%20shortcode%20with%20custom%20text%20to%20read%20like%20this.).
* Remove special characters from content.
* Remove URL from content.

Try [Text To Speech Pro](https://atlasaidev.com/text-to-speech-pro/) version to enjoy whole features of the plugin.

### Listening Is A Better Way To Read:
Boost your understanding and focus with listening by Text To Audio TTS. Remember more of what you read. Maximize your time,
Breeze through your content 2-3x faster than it takes to read it. Do more at once, Take your reading wherever you go – to the gym, the park, or the couch, or the journy.

Text to speech plugin allow you to add accessibility feature in wordpress site easily.  Speech plugin implements Web Content Accessibility Guidelines (WCAG) in the sit easily.


### Multilingual Supported Plugins:
* <a href='https://wpml.org/' target='_blank' rel='ugc' >WPML WordPress Multilingual Plugin</a>.
* <a href='https://wordpress.org/plugins/gtranslate/' target='_blank'>Translate WordPress with GTranslate Plugin</a>.

# How WPML WordPress Multilingual Plugin  Works?
After translating content by [WPML](https://wpml.org/) Text To Audio Accessibility plugin will autometically translate content to the correct language.
Text To Audio Accessibility will detect content language from the URL of that particular page or post.

# How GTranslate Pluign Works?
Text To Audio Accessibility plugin detect page or post language when page or post are translated by [GTranslate](https://wordpress.org/plugins/gtranslate/).
Also, Text To Audio Accessibility translate content when a page language chages by GTranslate plugin's language switching wizard.


### Custom Post Type Supported Plugins:

* <a href='https://wordpress.org/plugins/advanced-custom-fields/' target='_blank'>Advanced Custom Fields (ACF)</a>.
* <a href='https://wordpress.org/plugins/custom-post-type-ui/' target='_blank'>Custom Post Type UI</a>.
* <a href='https://toolset.com/home/types-manage-post-types-taxonomy-and-custom-fields/' target='_blank'>Toolset Types</a>.

### Other Supported Plugins:
* <a href='https://wordpress.org/plugins/wp-optimize/' target='_blank'>WP-Optimize - Clean, Compress, Cache</a>.
* <a href='https://wordpress.org/plugins/elementor/' target='_blank'>Elementor Website Builder – More than Just a Page Builder</a>.


== Installation ==
1. Download and unzip the plugin
2. Upload the entire "text-to-audio" directory to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to "Text To Audio" menu and configure your settings


== Frequently Asked Questions ==

= Does Text To Speech Support All Andriod Phones ? =
Yes, Text To Speech support all android phones. But this fully support is available only for [pro version](https://atlasaidev.com/text-to-speech-pro/). 
Free vesion has some limitatios. Here it is.
Text To Speech plugin is built on browser API. No external API is used. Here is the API used [speechSynthesis](https://developer.mozilla.org/en-US/docs/Web/API/SpeechSynthesis)
That is why Text To Speech plugin doesn’t support all android phones aslo all languages. Here you can check which android phone and which device support this [speechSynthesis](https://developer.mozilla.org/en-US/docs/Web/API/SpeechSynthesis#browser_compatibility) API

Another issue speechSynthesis API is differ browser to browser also device to device . So it changes the voices and languages based on browser. one language may available on desktop
It can be not available on mobile phone. One voice may available on desktop, it may be not available on android.

If you still facing problems regarding browser issues please on a [ticket](https://atlasaidev.com/contact-us/).


= Does Text To Speech Support My Language? =
Tex To Speech Pro TTS Accessibility supports these [languages](https://wordpress.org/plugins/text-to-audio/#:~:text=PRO%20SUPPORTED%20LANGUAGES%3A).

Tex To Speech Free TTS Accessibility supports these [languages](https://wordpress.org/plugins/text-to-audio/#:~:text=based%20on%20device.-,SUPPORTED%20LANGUAGES,-%3A).


= Does Text To Speech Support Multilingual Plugins ? =
Yes, [pro version](https://atlasaidev.com/text-to-speech-pro/) of Text To Speech plugin supports
WPML and GTranslate for now. We'll give support soon for [TranslatePress](https://wordpress.org/plugins/translatepress-multilingual/),
[Loco Translate](https://wordpress.org/plugins/loco-translate/), [Polylang](https://wordpress.org/plugins/polylang/).


= Does Text To Speech Support Custom Post Types ? =
Yes, [pro version](https://atlasaidev.com/text-to-speech-pro/) of Text To Speech plugin supports custom post types.


= Content Missing From Post =
You can add missing content by **CSS Selectors**. This custom **CSS Selector** is available for [pro version](https://atlasaidev.com/text-to-speech-pro/).
Go to the **Settings** menu of the plugin. Add all of the **CSS Selectors**. It can be from multiple pages. The benifit of **CSS Selector** over filter 
that it is dynamic. But filter is static.

Also miissing content can be added by filters. 
Filter Example :
Install the plugin [Code Snippets](https://wordpress.org/plugins/code-snippets/) Then Select Snippet > Add New Create a new snippet with this block of code

`
add_filter( 'tta__content_description', 'tta__content_description_callback', 10, 3 );
function tta__content_description_callback ( $output, $description, $post_id ) {

	$output .=" extra content here";
	
	return $output;
}

` 

= How to skip contents to read? =
Yes, you can skip contents from reading. This is a [pro](https://atlasaidev.com/text-to-speech-pro/) feature. 
There is a textarea in the settings tab of [text to speech pro](https://atlasaidev.com/text-to-speech-pro/).
You can skip multiple texts. Multiple tags will be pipe(|) separated.

= How to  skip tags to read? =
Yes, you can skip tags from reading. This is a [pro](https://atlasaidev.com/text-to-speech-pro/) feature. 
There is a textarea in the settings tab of [text to speech pro](https://atlasaidev.com/text-to-speech-pro/).
You can skip multiple tags. Multiple tags will be pipe(|) separated.

= How to add button in Gutenburg block? =
Yes, you can add listening button from block editor. Open you block editor and search **Customize Button** then add it.
Now you can change **color**,  **backgroundColor** , **width**. And also add **custom_css**.

= How to change button text? =
You can change button text 2 ways one is by shortcode attribute. Another way is adding filter. But filter always overrides the shortcode attributes. Here is short code Example :
	`
	[tta_listen_btn listen_text="Listen" pause_text="Pause"  resume_text="Resume" replay_text="Replay"]
	
	`

Here Is Filter Example :

Install the plugin [Code Snippets](https://wordpress.org/plugins/code-snippets/) Then Select Snippet > Add New Create a new snippet with this block of code

`
add_filter( 'tta__button_text_arr', 'tta__button_text_arr_callback' );
function tta__button_text_arr_callback ( $button_text_arr ) {

	// Listen button
	$text_arr['listen_text'] = 'Listen'; // paste custem text
	$text_arr['pause_text'] = 'Pause'; // paste custem text
	$text_arr['resume_text'] = 'Resume'; // paste custem text
	$text_arr['replay_text'] = 'Replay'; // paste custem text

	return $text_arr;
}

`
              
= How to add custom CSS class to button? =
Add class on shortcode as an attribute. Example : 
`
[tta_listen_btn class="custom_class"]

`



= How to  change button background and text color? =
Yes, you can change button background and text color from plugins dashboard's customization menu. also from block editor by applying the **customization button** block.

= How to change voice and language by shortcode? =
You can change the voice and language of the tex to speech player by shortcode. Here is how...
	`
	[tta_listen_btn lang="en-GB" voice="Google US English"]
	`	

== Screenshots ==
1. Add play button to any post.
2. Write post by voice.
3. Customization of button.
4. Choose listening voice.
5. Choose recording language.
6. Documentation.
7. Gutenburg Support.
8. Customize the button in block editor, Block Name ( Customize Button ).

== Changelog ==

💎 TRANSLATION REQUEST 💎
We are looking for people to help translate this plugin. If you can help we would love here from you.
Help us & the WordPress community to translate the plugin. You can [contact](http://atlasaidev.com/contact-us/) with us. We'll guide you how to translate.

= 1.5.19 ( 12 Mar 2024 ) =
* Minor error fix.

= 1.5.18 ( 12 Mar 2024 ) =
* Default listening language issue fixed at listening menu.
* Faq improved.

= 1.5.17 ( 08 Mar 2024 ) =
* Shortcode attribute added for changing voice and language.
* Button Default text issue solved.


= 1.5.16 ( 04 Mar 2024 ) =
* Faq menu is now Docs.
* Multilingual notice added.

= 1.5.15 ( 28 Feb 2024 ) =
* Live preview for free version added.

= 1.5.14 ( 22 Feb 2024 ) =
* Change Button text from customization menu.
* Freemius integration for free version.

= 1.5.13 ( 19 Feb 2024 ) =
* [WPML](https://wpml.org/) and [Gtranslate](https://wordpress.org/plugins/gtranslate/) plugin compatibility improved.
* Save multiple url to database for generated mp3 file.
* Documentation updated.

= 1.5.12 ( 15 Feb 2024 ) =
* ReadMe file improved.
* Documentation updated.

= 1.5.11 ( 10 Feb 2024 ) =
* ReadMe file improved.
* Documentation updated.


= 1.5.10 ( 05 Feb 2024 ) =
* Minor bug fix.

= 1.5.9 ( 05 Feb 2024 ) =
* Faq: Faq update.


= 1.5.8 ( 02 Feb 2024 ) =
* Fixed: On customization menu buttons live design issue fixed.
* Fixed: Button responsiveness issue fixed.
* Fixed: Custom CSS not working for buttons issue fixed.

= 1.5.7 ( 29 Jan 2024 ) =
* [WPML](https://wpml.org/) and [GTranslate](https://wordpress.org/plugins/gtranslate/) plugin notice added.
* String remove system from reading content added.
* Tags remove system from reading content added.


= 1.5.6 ( 24 Jan 2024 ) =
* Documentation Updated.

= 1.5.5 ( 22 Jan 2024 ) =
* Asset dependency issue fixed.
* Default Pro button issue fixed.
* MP3 file generation issue fixed in pro version

= 1.5.4 ( 19 Jan 2024 ) =
* Bug fix.


= 1.5.3 ( 14 Jan 2024 ) =
* Remove shortcode from content.
* Option added to select any post type.
* Add player to any post type.

= 1.5.2 ( 7 Jan 2024 ) =
* Strip HTML Tags from content.
* Strip URL from content.
* Updated Documentation.

= 1.5.1 ( 6 Jan 2024 ) =
* Bug fix


= 1.5.0 ( 28 Dec 2023 ) =
* Improved: UI design improved.
* Google text to speech integration.


= 1.4.23 ( 25 Dec 2023 ) =
* added: [WPML](https://wpml.org/) support.


= 1.4.22 ( 13 Dec 2023 ) =
* added: `tts_ignore_match_80_percent` filter added.
* Improved: button display logic improved.
* Improved: `tta__button_text_arr` filter functionality improved.


= 1.4.21 ( 13 Dec 2023 ) =
* Minor  bug fix.


= 1.4.20 ( 13 Dec 2023 ) =
* [TTS Plugin conflicts with Themes and other plugins](https://wordpress.org/support/topic/tts-plugin-conflicts-with-themes-and-other-plugins/).

= 1.4.19 ( 05 Dec 2023 ) =
* Select selector functionality added.
* [Issue with button placement in latest version](https://wordpress.org/support/topic/issue-with-button-placement-in-latest-version/).

= 1.4.18 ( 02 Dec 2023 ) =
* Unnecessary code removed

= 1.4.17 ( 01 Dec 2023 ) =
* Button puase issue fixed.


= 1.4.16 ( 13 Nov 2023 ) =
* Removed: setTimeout function removed.
* Hooks file issue is fixed.

= 1.4.15 ( 13 Nov 2023 ) =
* CSS issue solved.

= 1.4.14 ( 10 Nov 2023 ) =
* Documentation updated
* Get Pro button added


= 1.4.13 ( 05 Nov 2023 ) =
* Compatible: WordPress 6.4 tested
* Add: Button width issue fixed.
* Removed: Banner Removed.


= 1.4.12 ( 30 Oct 2023 ) =
* Compatible: [WP-Optimize - Clean, Compress, Cache](https://wordpress.org/plugins/wp-optimize/) plugin compatibility added.
* Compatible: [Elementor Website Builder – More than Just a Page Builder](https://wordpress.org/plugins/elementor/) plugin compatibility added.
* Fixed: Initialized the button after ducument load.
* Change button text by shortcode [attribute](https://wordpress.org/plugins/text-to-audio/#:~:text=TTS%20button%20text%20can%20be%20changed%20by%20providing%20attributes%20to%20shortcode%20like%20this)
* Code refactor

= 1.4.11 ( 26 Oct 2023 ) =
* Added: Shortcode attribute ```text_to_read``` added. 
* Added: Custom post type support for premium verison.
* Added: [Advanced Custom Fields (ACF)](https://wordpress.org/plugins/advanced-custom-fields/) support for pro version.
* Added: [Custom Post Type UI](https://wordpress.org/plugins/custom-post-type-ui/) support for pro version.
* Added: [Toolset Types](https://toolset.com/home/types-manage-post-types-taxonomy-and-custom-fields/) support for pro version.

= 1.4.10 ( 19 Oct 2023 ) =
* Added: GTranslate plugin compatibility added for premium version.

= 1.4.9 ( 17 Oct 2023 ) =
* Added: Documentation link added.
* Added: YouTube Link added.

= 1.4.8 ( 09 Oct 2023 ) =
* Added: Halloween banner added.

= 1.4.7 ( 5 Oct 2023 ) =
* Fixed: [Shortcode stop working](https://wordpress.org/support/topic/shortcode-stop-working-2/)
* Fixed: Button hide issue fixed.
* Fixed: Listeing language change issue solved.
* Fixed: [change the button text](https://wordpress.org/support/topic/change-the-button-text-7/)
* compatibility added for twentytwentythree theme

= 1.4.6 ( Sep 30 , 2023) =
* Fixed: [Error using quotes in customized CSS](https://wordpress.org/support/topic/error-using-quotes-in-customized-css/)
* Fixed: [The custom labels for translating strings in button text issue](https://wordpress.org/support/topic/error-using-quotes-in-customized-css/)
* Fixed: [Javascript errors](https://wordpress.org/support/topic/javascript-errors-61/)


= 1.4.5 ( Sep 21 , 2023) =
* Theme support: Divi, Enfold, Astra, Kadance, OceanWP, Hello Elementor, GeneratePress, Dynamic News, Kadance, Darknews theme supported.
* Plugin support: Elementor builder supported.

= 1.4.4 ( Sep 14 , 2023) =
* Fixed: JS error fixed.
* Tested: WordPress 6.3.1.
* Improved: JS loading improved on front.

= 1.4.3 ( Sep 10 , 2023) =
* Fixed: CSS loaded properly.
* Fixed: Short code text not displaying issue solved.
* Added: Integrate with [Text To Speech Pro](https://atlasaidev.com/text-to-speech-pro/) version.
* Added: Plugin URI added.


= 1.4.2 ( Sep 07 , 2023) =
* Fixed: Fixed: button showing issue is solved.

= 1.4.1 ( Sep 6 , 2023) =
* Fixed: Custom css adding issue solved.

== Upgrade Notice ==

= 0.1 =
This version fixes a security related bug. Upgrade immediately.