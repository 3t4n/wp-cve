=== Animate on Scroll ===
Contributors: aryadhiratara, thinkdigitalway
Tags: aos, animate, animation, scroll, scroll animation, css animation, fade, zoom, flip, slide, effects, effect
Requires at least: 5.8
Tested up to: 6.2
Requires PHP: 7.4
Stable tag: 1.0.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Animate any Elements on scroll using the popular AOS JS library simply by adding class names.

== Description ==

Animate any Elements on scroll using the popular AOS JS library simply by adding class names.

This plugin helps you integrate easily with AOS JS library to add any AOS animations (on scroll animation) to WordPress.

It should work well with the native gutenberg core blocks or any page builder (_tested with GenerateBlocks and Elementor_) that provides an input field for adding custom class names to elements.

## About AOS

AOS is a small JavaScript library built by **[Michał Sajnóg](https://twitter.com/michalsnik)** that allows you to add animated effects to HTML elements when they come into view as the user scrolls down a webpage. AOS provides a set of predefined animations using CSS that can be easily applied to elements using simple data attributes in the HTML code.

Check out the AOS **[demo](https://michalsnik.github.io/aos/)** and **[documentation](https://github.com/michalsnik/aos)**.

## How to add Animations?

Simply add the desired AOS animation to your element class name with "aos-" prefix and the plugin will add the corresponding aos attribute to the element tag.

**Fade animations:**

- fade: **aos-fade**
- fade-up: **aos-fade-up**
- fade-down: **aos-fade-down**
- fade-left: **aos-fade-left**
- fade-right: **aos-fade-right**
- fade-up-right: **aos-fade-up-right**
- fade-up-left: **aos-fade-up-left**
- fade-down-right: **aos-fade-down-right**
- fade-down-left: : **aos-fade-down-left**

**Flip animations:**

- flip-up: **aos-flip-up**
- flip-down: **aos-flip-down**
- flip-left: **aos-flip-left**
- flip-right: **aos-flip-right**

**Slide animations:**

- slide-up: **aos-slide-up**
- slide-down: **aos-slide-down**
- slide-left: **aos-slide-left**
- slide-right: **aos-slide-right**

**Zoom animations:**

- zoom-in: **aos-zoom-in**
- zoom-in-up: **aos-zoom-in-up**
- zoom-in-down: **aos-zoom-in-down**
- zoom-in-left: **aos-zoom-in-left**
- zoom-in-right: **aos-zoom-in-right**
- zoom-out: **aos-zoom-out**
- zoom-out-up: **aos-zoom-out-up**
- zoom-out-down: **aos-zoom-out-down**
- zoom-out-left: **aos-zoom-out-left**
- zoom-out-right: **aos-zoom-out-right**

## Animation Settings

By default, the global animation settings are

- offset: -100
- duration: 1100
- easing: ease
- delay: 0
- once: true

you can change that using filter:

    add_filter( 'aos_init', function($aos_init) {
        return '
        var aoswp_params = {
	        "offset":"200",
	        "duration":"1800",
	        "easing":"ease-in-out",
	        "delay":"0",
	        "once": false};
        ';
    } );


 or add the extra classes below to the element for individual settings. The plugin will add the corresponding aos attribute to the tag.

**Once behavior:**

- once=true: **aos-once-true**
- once=false: **aos-once-false**

**Easing functions:**

- linear: **aos-easing-linear**
- ease: **aos-easing-ease**
- ease-in: **aos-easing-ease-in**
- ease-out: **aos-easing-ease-out**
- ease-in-out: **aos-easing-ease-in-out**
- ease-in-back: **aos-easing-ease-in-back**
- ease-out-back: **aos-easing-ease-out-back**
- ease-in-out-back: **aos-easing-ease-in-out-back**
- ease-in-sine: **aos-easing-ease-in-sine**
- ease-out-sine: **aos-easing-ease-out-sine**
- ease-in-out-sine: **aos-easing-ease-in-out-sine**
- ease-in-quad: **aos-easing-ease-in-quad**
- ease-out-quad: **aos-easing-ease-out-quad**
- ease-in-out-quad: **aos-easing-ease-in-out-quad**
- ease-in-cubic: **aos-easing-ease-in-cubic**
- ease-out-cubic: **aos-easing-ease-out-cubic**
- ease-in-out-cubic: **aos-easing-ease-in-out-cubic**
- ease-in-quart: **aos-easing-ease-in-quart**
- ease-out-quart: **aos-easing-ease-out-quart**
- ease-in-out-quart: **aos-easing-ease-in-out-quart**

**Animation Duration:**

- 100ms: **aos-duration-100**
- 200ms: **aos-duration-200**
- 300ms: **aos-duration-300**
- 400ms: **aos-duration-400**
- 500ms: **aos-duration-500**
- 600ms: **aos-duration-600**
- 700ms: **aos-duration-700**
- 800ms: **aos-duration-800**
- 900ms: **aos-duration-900**
- 1000ms: **aos-duration-1000**
- 1100ms: **aos-duration-1100**
- 1200ms: **aos-duration-1200**
- 1300ms: **aos-duration-1300**
- 1400ms: **aos-duration-1400**
- 1500ms: **aos-duration-1500**
- 1600ms: **aos-duration-1600**
- 1700ms: **aos-duration-1700**
- 1800ms: **aos-duration-1800**
- 1900ms: **aos-duration-1900**
- 2000ms: **aos-duration-2000**
- 2100ms: **aos-duration-2100**
- 2200ms: **aos-duration-2200**
- 2300ms: **aos-duration-2300**
- 2400ms: **aos-duration-2400**
- 2500ms: **aos-duration-2500**
- 2600ms: **aos-duration-2600**
- 2700ms: **aos-duration-2700**
- 2800ms: **aos-duration-2800**
- 2900ms: **aos-duration-2900**
- 3000ms: **aos-duration-3000**

**Animation Delay:** (***new**, added in 1.0.2)

- 100ms: **aos-delay-100**
- 200ms: **aos-delay-200**
- 300ms: **aos-delay-300**
- 400ms: **aos-delay-400**
- 500ms: **aos-delay-500**
- 600ms: **aos-delay-600**
- 700ms: **aos-delay-700**
- 800ms: **aos-delay-800**
- 900ms: **aos-delay-900**
- 1000ms: **aos-delay-1000**
- 1100ms: **aos-delay-1100**
- 1200ms: **aos-delay-1200**
- 1300ms: **aos-delay-1300**
- 1400ms: **aos-delay-1400**
- 1500ms: **aos-delay-1500**
- 1600ms: **aos-delay-1600**
- 1700ms: **aos-delay-1700**
- 1800ms: **aos-delay-1800**
- 1900ms: **aos-delay-1900**
- 2000ms: **aos-delay-2000**
- 2100ms: **aos-delay-2100**
- 2200ms: **aos-delay-2200**
- 2300ms: **aos-delay-2300**
- 2400ms: **aos-delay-2400**
- 2500ms: **aos-delay-2500**
- 2600ms: **aos-delay-2600**
- 2700ms: **aos-delay-2700**
- 2800ms: **aos-delay-2800**
- 2900ms: **aos-delay-2900**
- 3000ms: **aos-delay-3000**

## To Disable Animations On Specific Device

- To disable animations on certain elements on devices larger than 767px, simply add `aoswp-disable-desktop` class name to the element tag
&nbsp;
- To disable animations on certain elements on devices smaller than 766px, simply add `aoswp-disable-mobile` class name to the element tag
&nbsp;
- To disable animations site-wide / per page basis on specific devices:
add this lines to your css files:

    @media ( [ `your media query` ](https://gist.github.com/gokulkrishh/242e68d1ee94ad05f488) ) {
	
			html:not(.no-js) .aoswp-enabled [data-aos] {
			    opacity: 1!important;
			    -webkit-transform: none!important;
			    transform: none!important;
			    transition: none!important;
				transition-timing-function: unset!important;
				transition-duration: unset!important;
				transition-property: none!important;
			}
		
	}

&nbsp;
## Note

- Both AOS JavaScript and CSS will only be loaded if there is **'```aos-```'** in the page's html. So this plugin will not add bloat to pages that do not use/need the AOS animations.
&nbsp;
- Although the AOS library is already lightweight, the CSS and JS in this plugin are delay-able, so it won't hurt your site's performance at all. (You can use **[Optimize More!](https://wordpress.org/plugins/optimize-more/)**  to delay the CSS and JS)

## Disclaimer

This plugin doesn't add anything to your database and won't do any permanent change to your HTML, so you can safely deactivate and delete it when you no longer need it.

## USEFUL PLUGINS TO OPTIMIZE YOUR SITE'S SPEED:

- **[Optimize More!](https://wordpress.org/plugins/optimize-more/)** -  A DIY WordPress Page Speed Optimization Pack. Features:
 - **Load CSS Asynchronously** - selectively load CSS file(s) asynchronously on selected post/page types.
 - **Delay CSS and JS until User Interaction** - selectively delay CSS/JS load until user interaction on selected post/page types.
 - **Preload Critical CSS, JS, and Font Files** - selectively preload critical CSS/JS/Font file(s) on selected post/page types.
 - **Remove Unused CSS and JS Files** - selectively remove unused CSS/JS file(s) on selected post/page types.
 - **Load Gutenberg CSS conditionally** - Load each CSS of the core blocks will only get enqueued when the block gets rendered on a page.
 - **Advance Defer JS** - hold JavaScripts load until everything else has been loaded. Adapted from the legendary **varvy's defer js** method _*recommended for defer loading 3rd party scripts like ads, pixels, and trackers_
 - **Defer JS** - selectively defer loading JavaScript file(s) on selected post/page types.
 - **Remove Passive Listener Warnings** - Remove the "Does not use passive listeners to improve scrolling performance" warning on Google PageSpeed Insights
&nbsp;
- **[Optimize More! Images](https://wordpress.org/plugins/optimize-more-images/)** - A simple yet powerfull image, iframe, and video optimization plugin (Lazy load images / iframes / videos, Preload featured images automatically). Also support lazy loading CSS background images.
&nbsp;
- **[Lazyload, Preload, and more!](https://wordpress.org/plugins/lazyload-preload-and-more/)** - A simplified version of **Optimize More! Images**. Able to do what **Optimize More! Images** can do but without UI for settings (you can customize the default settings using filters). This tiny little plugin (around 14kb zipped) will automatically: 
 - **lazyload** your below the fold images (img tag and bg images) /iframes / videos,
 - **preload** your featured images,
 - and add **loading="eager"** to your featured image and all images that have `no-lazy` or `skip-lazy` class.

## Other USEFUL PLUGIN:

- **[Shop Extra](https://wordpress.org/plugins/shop-extra/)** - A lightweight plugin to optimize your WooCommerce & Business site:
 - **Floating WhatsApp Chat Widget** (can be use without WooCommerce),
 - **WhatsApp Order Button for WooCommrece**,
 - **Hide/Disable WooCommerce Elements**,
 - **WooCommerce Strings Translations**,
 - and many more.
&nbsp;
- **[Image & Video Lightbox](https://wordpress.org/plugins/image-video-lightbox/)** - A lightweight plugin that automatically adds Lightbox functionality to images displayed by WordPress (Gutenberg) Gallery and Image Blocks, as well as GenerateBlocks Image Blocks, and also videos created by the core Video Block,  without the need to set the link to media file manually one by one.


&nbsp;
== Frequently Asked Questions ==

= Why AOS? =

AOS library has so many built in animations and the JavaScript is written in pure JS without any dependencies.

= Where is the Settings Page? =

This plugin doesn't has any settings page, since the animation executions are based from your element class.

= Does it works with any Page Builders? =

Yes, as long as your builder has input fields for adding custom class names.

= How to add the Animation? =

Simply add the desired AOS animation to your element class name with “aos-” prefix. Please read the plugin description.

= Is it customizable? =

Yes, the customizations are available using filter and class names. Please read the plugin description.

== Installation ==

#### From within WordPress

1. Visit **Plugins > Add New**
1. Search for **Animate on Scroll** or **Arya Dhiratara**
1. Activate Animate on Scroll from your Plugins page


#### Manually

1. Download the plugin using the download link in this WordPress plugins repository
1. Upload **animate-on-scroll** folder to your **/wp-content/plugins/** directory
1. Activate Animate on Scroll plugin from your Plugins page


== Screenshots ==


== Changelog ==

= 1.0.6 =

- Fix conflict with the built-in query loop block (thanks to @weiko for reporting this)

= 1.0.5 =

- Add css to disable the animations on mobile and/or desktop devices. Simply add `aoswp-disable-desktop` class name to disable the animation on device larger than 767px, or add `aoswp-disable-mobile` class name to disable the animation on device smaller than 766px. (as asked by @mikemastrox)

= 1.0.4 =

- Fix php warning on function that responsible to enqueued the assets conditionally (thanks again @clipb!) 

= 1.0.3 =

- Add missing pattern for 'fade-left' animation (thanks to @clipb for reporting this)
- Refactor the code to get better compatibility for Elementor

= 1.0.2 =

- Add animation delay function to the plugin (accommodating Kevin Mccourt's feedback on the GeneratePress Facebook Group)

= 1.0.1 =

- Fix plugin banner

= 1.0.0 =

- Initial release