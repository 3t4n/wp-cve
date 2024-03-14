=== AnimateGL Animations for WordPress - Elementor & Gutenberg Blocks Animations ===
Contributors: creativeinteractivemedia
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Tags: animation, animate, gutenberg animations, block animations, elementor animations, scroll animations, hover animations, animations for blocks, entrance animation, motion effects, image effects, animation effects, visual effects
Requires at least: 5.0
Tested up to: 6.2
Stable tag: 1.1
Requires PHP: 5.4

 CSS & WebGL Animations for Elementor & Gutenberg Blocks Animations, animations from CSS class, scroll animations, lock to scrollbar. Add eye-catching entrance animations to your website that bring your images to life and enhance the visual appeal of your web pages. Customize animations with CSS classes or a visual editor, and create unique and creative effects that will make your website stand out.

== Description ==

[Demo](https://animategl.com/ "Live AnimateGL demo") | [Documentation](https://creativeinteractivemedia.gitbook.io/animategl-wp/ "Documentation") | [Support](https://wordpress.org/support/plugin/animategl/ "Support") 

Add CSS and WebGL animations easily to any element on the website with [AnimateGL](https://animategl.com/) plugin. 

[youtube https://youtu.be/0K6XG-ZrCjg]

Add CSS and WebGL animations in Elementor, Block editor or any other builder. Customize direction, duration, delay and easing for each animation. Choose simple CSS animations like fade, slide, zoom and wipe or creative WebGL animations like bend, flip, stretch or directional fade. Create custom entrance animation with visual editor. AnimateGL is lightweight, fast, and easy to use, making it the perfect choice for web designers looking to enhance their website's visual appeal.

== Features ==

* Add Entrance animation to any element or block
* CSS Entrance animations Fade, Zoom In, Zoom out, Wipe, Slide Reveal
* WebGL Entrance animations Bend, Flip, Peel, Wipe, Zoom, Directional Fade
* Fully customizable animation direction, duration, delay, easing
* Gutenberg Blocks animations
* Elementor animations
* CSS class animations
* Nested animations
* Lock to scrollbar
* Repeat or play once on first enter the viewport
* Live editor for creating custom animation
* Preset animations
* Scroll triggered Entrance animations
* Easy to use
* Lightweight
* Great performance 

== CSS animations ==

CSS entrance animations are GPU accelerated and animate CSS properties opacity, transform and clip path in different combinations. CSS animations can be added to any element or block. Available CSS animations are Fade, Zoom In, Zoom Out, Wipe and Slide with settings for direction, distance, duration, delay and easing. With combination of fade, zoom, direction, easing and delay you can create unlimited number of creative elegant animations. More CSS animations coming soon.

CSS Entrance animations: 

* Fade
* Zoom In
* Zoom Out
* Wipe
* Slide

== WebGL animations ==

WebGL animations convert any element to image with html2canvas.js, then apply effects to image with custom GLSL shaders. Best use is for simple elements like heading, button or image. With WebGL we can create effects that are not possible with CSS, like 3D distortions or gradient fade, and add those effects to any element on the page. 

WebGL Entrance animations: 

* Fade
* Slide
* Stretch
* Bend
* Flip
* Zoom
* Peel

== Lightweight ==

Only 35kb for CSS animations, additional 45kb if WebGL animations are used.

== Elementor animations ==

[youtube https://youtu.be/LKEA4IO1yHM]

Add Entrance animation to any element in Elementor. Select animation type, direction, distance, delay, duration and easing in the Element Advanced tab.

== Gutenberg Blocks animations ==

[youtube https://youtu.be/oPivzJHFIek]

Add Entrance animation to any Gutenberg block. Select animation type, direction, distance, delay, duration and easing in the block inspector controls.

== Entrance Animations via CSS class ==

If you don't use Elementor or Gutenberg blocks, you can still use AnimateGL via CSS classes. Add one of preset entrance animations or custom entrance animation to any element on the page by adding the animation CSS class.

More animations available with [Entrance pack](https://codecanyon.net/item/animategl-animations-for-wordpress-entrance-pack/45375689?s=org): 

* Circle (CSS)
* Square (CSS)
* Line (CSS)

Customize direction, duration, easing and delay for each animations
 
Customize animation properties via CSS class:

* Fade - with options for directional fade and threshold
* Translate - with options for x, y, and z direction
* Rotation - with options for x, y, and z axis
* Corners Distortion - for added animation variety
* Duration - customize the length of the animation
* Delay - customize the start time of the animation
* Easing - customize the speed and flow of the animation

== Repeat ==

By default, entrance animation will play when element enters the viewport for the first time. With option repeat enabled, the animation will play each time element enters the viewport.

== Lock to scrollbar ==

Instead of fixed duration entrance animation, we can make the animation progress depend on the scroll position of the element. If the element is below the viewport, the animation progress will be 0. As we scroll the page down, and element is moving towards the middle of the viewport, the animation progresses. The end of animation is when element reches the middle of the viewport. Lock to scrollbar option can be enabled for any animation.

== Mouse Effects ==

AnimateGL also offers mouse-driven distortion effects, including:

* Pull - with options for strength, size, RGB shift, and ease

== Scroll triggered animations ==

Entrance animation is played when when the element enters the viewport, when it becomes visible on the screen. 

== Viewport entrance threshold ==

By default, entrance animation start to play when 70% or 200px of the element enters the viewport.

== Live Editor ==

Use live editor to create your custom entrance animation.

Enhance the visual appeal of your website with AnimateGL, the most powerful and advanced WebGL animation plugin for WordPress. With its lightweight 33kb gzipped size and no dependencies, AnimateGL is fast, easy to use, and the perfect choice for web designers looking to add unique animations to their website.

== Use with any page builder ==

AnimateGL can be used with any page buidler: Elementor and Gutenberg blocks editor, Visual composer and others, because animations can be added simply by adding a CSS class. Add unique animations in Elementor, Visual Composer or Guteberg blocks editor with AnimateGL.

== Help us improve ==

If you have any problem or feature request for this plugin, please feel free to [open a ticket](https://wordpress.org/support/plugin/animategl/)!

== Frequently Asked Questions ==

= How to add AnimateGL animations to any block in Gutenberg? =

Open Image block options > Advanced > AnimateGL, and select animation. Alternatively you can add animations via CSS class with Advanced > Additional CSS class, add the CSS class of the AnimateGL animation.

= Where is the list of CSS classes for Entrance animations? =

Available Entrance animations are listed on the plugin admin page.

= How to add AnimateGL animations to any element in Elementor? =

Open Image settings > Advanced > Layout > CSS Classes, add the CSS class of the AnimateGL animation. 

= What are the limitations of WebGL animations? =

WebGL animatinos are best to use for simple elements like headings, buttons or images. For more complex elements like sections it is better to use CSS animatinos.

== Changelog ==

= 1.4.23 =
* Fix: CSS animation repeat

= 1.4.22 =
* Fix: WebGL animation not working before scroll

= 1.4.21 =
* Improvement: Performance improvements 

= 1.4.20 =
* Improvement: Performance improvements 

= 1.4.19 =
* Improvement: Better performance for Lock to scrollbar animations 

= 1.4.18 =
* Fix: Updated Freemius SDK to 2.5.10 to fix a volnurability in Freemius SDK <= 2.5.9

= 1.4.17 =
* Fix: Increasing viewport height in Safari 

= 1.4.16 =
* Improvement: Faster WebGL animation loading time 

= 1.4.15 =
* Improvement: Admin page Animation editor
* Improvement: Element entrance animation trigger changed to 70% or 200px of element is in viewport

= 1.4.14 =
* Fix: Cache busting for scripts

= 1.4.13 =
* Fix: Animations not working on front end

= 1.4.12 =
* Improvement: Play entrance animation when 70% of element enters the viewport
* Improvement: WebGL Zoom In animation
* New: WebGL Zoom Out animation

= 1.4.11 =
* Fix: Animations of elements with background-attachment: fixed

= 1.4.10 =
* Improvement: WebGL flip animations.

= 1.4.9 =
* New: Presets in Animation Editor.
* Fix: Animation editor bugs.
* Improvement: WebGL stretch, bend, fade animations.

= 1.4.8 =
* New: Animation Distance option.
* Improvement: WebGL Fade animations.
* Improvement: Reset animation direction when animation name changes.

= 1.4.7 =
* Fix: WebGL Fade shader.
* New: Support for Entrance Fade pack.

= 1.4.6 =
* Improvement: Admin page Entrance Presets sorted by type.
* Fix: CSS animations Fade and Slide.

= 1.4.5 =
* Fix: Progress in Animation editor not working.
* New: CSS animation type in Animation editor.

= 1.4.4 =
* Improvement: Added tabs Support and Addons to Admin page.

= 1.4.3 =
* Improvement: Playing only selected animation in plugin admin page, Entrance Presets.
* Fix: Animations on plugin admin page, Entrance Presets broken after using animation editor.

= 1.4.2 =
* Fix: Animation cannot be added to inner section in Elementor.
* Improvement: Better animation performance.
* Improvement: New pugin admin page.

= 1.4.1 =
* Fix: Wrong element position when repeating entrance animation.
* Fix: Element in Elementor editor becomes invisible after it is clicked.
* Fix: Default option missing in entrance animation name dropdown.

= 1.4 
* New: Entrance animation "lock to scrollbar" - animation progress depends on the scroll position.
* New: Entrance animation "repeat" - play entrance enimation each time element enters the viewport.
* Improvement: Better animation performance.

= 1.3.9 =
* Fix: Wipe animation.
* New: Getting Started admin page.
* New: Add-ons admin page.

= 1.3.8 =
* New: Added support for more entrance animations via addons.

= 1.3.7 =
* Improvement: Added more easing options for animations.
* Fix: Image flashing at the end of WebGL animation.

= 1.3.6 =
* Fix: Image scaling inside WebGL animated element.

= 1.3.5 =
* Fix: Animating parent and child elements at the same time.

= 1.3.4 =
* Improvement: Faster loading, better animation performance.
* Improvement: Faster admin page with animation preview.

= 1.3.3 =
* New: Wipe CSS animation.
* New: Slide CSS animation.

= 1.3.2 =
* Fix: Entrance animation now starts when element enters the viewport, insterad of waiting for center of the element to enter the viewport.
* Fix: Hardware accelerated CSS animations.
* New: Default (center) direction for animations.

= 1.3.1 =
* New: Zoom In and Zoom Out CSS animations.

= 1.3 =
* New: Fade In CSS animation.

= 1.0.0 =
* Initial release of the plugin.