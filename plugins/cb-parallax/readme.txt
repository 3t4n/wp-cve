=== cbParallax ===
Tags: parallax, responsive, fullscreen, image, background, Hintergrund, Bild, Hintergrundbild
Requires at least: 5.5
Tested up to: 6.1.1
Requires PHP: 5.6+
Stable tag: 0.9.7
Version: 0.9.7
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Author: Demis Patti

== Description ==
Responsive, fullscreen background image with a parallax effect.

== Features ==
- Custom background image
- One Image for all pages or individual images and effects on a per post basis
- Compatible with posts, pages, products, and many more
- Optional fullscreen background parallax effect
- Works vertically and, for fun, horizontally
- Various overlays to choose from

== Requirements ==
* Your theme must support the core WordPress implementation of the [Custom Backgrounds](https://codex.wordpress.org/Custom_Backgrounds) theme feature.
* In order to use the parallax feature, I decided to set the minimum required image dimensions to 1920px * 1200px, which covers a fullHD screen with a slight vertical parallax movement ( Image height - viewport height, so 1200px - 1080px gives 120px offset to move the image. I hope you get the point here.).
* You most likely need to edit some css in order to "uncover" the background image or parts of it respectively. Your theme's layout should be "boxed", or an opacity should be added to the page content container for the background image to be seen.
* PHP version 5.6 or above.

== Installation ==
1. Upload the `cb-parallax` folder to your `/wp-content/plugins/` directory.
2. Activate the "cbParallax" plugin through the "Plugins" menu in WordPress.
3. Edit a post to add a custom background.

== Limitations ==
- Not yet compatible with PHP version 8 and above

== Frequently Asked Questions ==

= Where do I interact with this plugin and how does it work? =

Please visit the plugin help tab for further information.

= Why doesn't it work with my theme? =

Most likely, this is because your theme doesn't support the WordPress `custom-background` theme feature.
This plugin requires that your theme utilize this theme feature to work properly.
Unfortunately, there's just no reliable way for the plugin to overwrite the background if the theme doesn't support this feature.
You'll need to check with your theme author to see if they'll add support or switch to a different theme.

= My theme supports 'custom-background' but it doesn't work! =

That's unlikely.
Just to make sure, check with your theme author and make sure that they support the WordPress `custom-background` theme feature.
Also, make sure that no container element is covering the element that holds the background image.

= How do I add support for this in a theme? =

Your theme must support the [Custom Backgrounds] (https://codex.wordpress.org/Custom_Backgrounds) feature for this plugin to work.
If you're a theme author, consider adding support for this feature if you can make it fit in with your design.  The following is the basic code, but check out the above link.
	add_theme_support( 'custom-background' );

= Are there any known limitations? =
* This is not really a limitation of functionality, but since the background image container wraps the body element, it usually resembles the viewport dimensions. This means, that on themes where the navigation bar is on the side, the sidebar covers a part of the viewport and thus also a part of the image (logic, but noteworthy).

= Can you help me? =

Yes. I have a look at the plugin's support page two or three times a month and I provide some basic support there.

= Are there any known issues? =
* Besides the known limitations, no.

== Screenshots ==
1. Multiple background views of a single post.
2. Settings Page.
3. Custom background meta box on the edit post screen.

== Changelog ==

= Version 0.9.4 =
1. Optimized CSS for WordPress version 5.3

= Version 0.9.3 =
1. Fixed issues with older IE versions
2. Fixed issues with contextual help

= Version 0.9.2 =
1. Fixed image source detection

= Version 0.9.1 =
1. Fixed bugs related to 'image move direction'
2. Extended the contextual help
3. Updated readme file

= Version 0.9.0 =
1. Face lifted the user interface
2. Re-written most of the code
3. Uses smoothscroll.js in favour of nicescroll.js as scrolling engine
4. Some Bugfixes

= Version 0.8.8 =
1. Extended the color picker to accept rgba colors
2. The color picker accepts input via keyboard again
3. Re-introduced a background color for the parallax image
4. Minor code changes
5. Minor code clean up
6. Some bug fixes
7. Removed Github link
8. Slowed down scrolling speed

= Version 0.8.7 =
1. Fixed compatibility with Chrome Browser
2. Checked compatibility with the latest version of WordPress

= Version 0.8.6 =
1. Updated a few included libraries
2. Improved some scripts

= Version 0.8.5 =
1. Rearranged the hooks for the frontend
2. re-added theme support for custom-background

= Version 0.8.4 =
1. Resolved a bug preventing the image data from being loaded.
2. Removed unnecessary comments.
3. Minor code cleanup.

= Version 0.8.3 =
1. The Frontend script will only load if a background image is defined.
2. The overlay container will only be created if an overlay image is defined.

= Version 0.8.2 =
1. Fixed missing remove image button on edit screens.

= Version 0.8.1 =
1. Made compatible with some premium themes.
2. Changed the display of the thumb on the settings page.

= Version 0.8.0 =
1. I'm responsive, baby! Please note that ( for now ), when an image aspect ratio matches the viewport aspect ratio, there is no room for parallax. Choose your image higher / wider than the expected viewport size according to the parallax direction ( vertical / horizontal)
2. Minor UI changes

= Version 0.7.5 =
1. Updated Nicescroll to version 3.6.8. Scrolling behaviour might be different now. Options to control scrolling behaviour will be available soon.
2. Removed custom Nicescroll version
3. Added easing
4. Resolved an issue with preserved scrolling

= Version 0.7.4 =
1. Optimized performance
2. Removed obsolete "add media" button on both the meta box and the settings page
3. Modified the "add media" button

= Version 0.7.3 =
1. Included missing file...

= Version 0.7.2 =
1. Optimized code, removed the loader-class
2. Optimized js

= Version 0.6.0 =
1. Fixed some bugs that occurred on Installations using the non-default locale.
2. Fixed issue with overlay color.
3. Fixed issue with static background image.
4. Improved scroll behaviour.
5. Added a feature to set one image for all supported posts and pages, including the possibility to override these global settings on a per-post basis (see "Settings" > "cbParallax").
6. Moved the options from the general settings page to "Settings" > "cbParallax".
7. Improved the performance of the parallaxing-script.
8. Removed the option to set a background color.
9. The interface is more user-friendly now.
10.You may want to review your image overlay settings on the post edit screens since they work again.

= Version 0.5.0 =
1. Reduced required PHP-Version to 5.3 or above due to user requests.
2. Minor bug fixes regarding errors on activation.

= Version 0.4.2 =
1. Increased required PHP-Version to 5.4 or above.

= Version 0.4.0 =
1. Completely rewritten the script for the public part.
2. Static image is now also being handled by the public script, it's mobile ready now.
3. Added an option to disable parallax on mobile ( View the "Settings / General" page). Will show the image as a static background.

= Version 0.3.0 =
1. Major bug fixes, the effect now works as expected.

= Version 0.2.6 =
1. The scripts for the frontend load only if needed.

= Version 0.2.5 =
1. Added support for the blog page.
2. Fixed support for single product page views.
3. The "preserve scrolling" option superseeds the "Nicescrollr" plugin settings on the frontend, if both plugins are enabled.
4. Code cleanup and some minor refactorings.

= Version 0.2.4 =
1. Optimized the script for the public part.
2. Added a section to the readme file regarding known issues.
3. Updated the readme file.

= Version 0.2.3 =
1. Fixed some bugs.
2. Added a background color to the image container to kind of simulate a "color" for the overlay.
3. Slightly enhanced meta box display behaviour.
4. Added support for "portfolio" post type / entries for web- and media workers :)

= Version 0.2.2 =
1. Removed display errors.

= Version 0.2.1 =
1. Resolved the translation bugs.
2. Optimized the scrolling behaviour.
3. Corrected the scroll ratio calculation.
4. Corrected the "static" background image display.
5. Corrected the meta box display behaviour.
6. Added the option to preserve the nice scrolling behaviour without the need to use the parallax feature ( see "Settings / General / cbParallax" ).

= Version 0.2.0 =
1. Optimized the script responsible for the parallax effect.
2. Added Nicescroll for smooth cross-browser scrolling.

= Version 0.1.1 =
1. Massively refactored the script responsible for the parallax effect.
2. Added the possibility to scroll the background image horizontally.
3. Added a function to reposition the image on window resize automaticly.
4. Improoved performance.
5. Improoved compatibility with webkit, opera and ie browsers.
6. Implemented a function that eases mousescroll.

= Version 0.1.0 =
First release :-)
