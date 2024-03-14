# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## 0.9.7
### Changed
- Code cleanup

## 0.9.4
### Changed
- Optimized CSS for WordPress version 5.3

## 0.9.3
### Changed
- Fixed issues with older IE versions
- Fixed issues with contextual help

## 0.9.2
### Changed
- Fixed image source detection

## 0.9.1
### Changed
- Fixed bugs related to 'image move direction'
- Extended the contextual help
- Updated readme file

## 0.9.0
### Changed
- Face lifted the user interface
- Re-written most of the code
- Uses smoothscroll.js in favor of nicescroll.js as scrolling engine
- Some Bugfixes

## 0.8.8
### Changed
- Extended the color picker to accept rgba colors
- The color picker accepts input via keyboard again
- Re-introduced a background color for the parallax image
- Minor code changes
- Minor code clean up
- Some bug fixes
- Slowed down scrolling speed
### Removed
- GitHub link

## 0.8.7
### Changed
- fixed compatibility with Chrome Browser
- checked compatibility with the latest version of WordPress

## 0.8.6
### Changed
- updated a few included libraries
- improved some scripts

## 0.8.5
### Changed
- Rearranged the hooks for the frontend
### Added
- Theme  support for custom-background

## 0.8.4
### Changed
- Resolved a bug preventing the image data from being loaded
- Minor code cleanup
### Removed
- Unnecessary code comments


## 0.8.3
### Changed
- The Frontend script will only load if a background image is defined
- The overlay container will only be created if an overlay image is defined

## 0.8.3
### Changed
- Fixed missing remove image button on edit screens

## 0.8.1
### Changed
- Made compatible with some premium themes
- Changed the display of the thumb on the settings page

## 0.8.0
### Changed
- I'm responsive now, baby! Please note that ( for now ), when an image aspect ratio matches the viewport aspect ratio, there is no room for parallax. Choose your image higher / wider than the expected viewport size according to the parallax direction ( vertical / horizontal)
- Minor UI changes

## 0.7.5
### Changed
- Updated Nicescroll to version 3.6.8. Scrolling behavior might be different now. Options to control scrolling behavior will be available soon
- Added easing
- Resolved an issue with preserved scrolling
### Removed
- Removed custom Nicescroll version

## 0.7.4
### Changed
- Optimized performance
- Modified the "add media" button
### Removed
- Removed obsolete "add media" button on both the meta box and the settings page

## 0.7.3
### Changed
- Included missing file...

## 0.7.2
### Changed
- Optimized code, removed the loader-class
- Optimized js

## 0.6.0
### Changed
- Fixed some bugs that occurred on Installations using the non-default locale
- Fixed issue with overlay color
- Fixed issue with static background image
- Improved scroll behavior
- Added a feature to set one image for all supported posts and pages, including the possibility to override these global settings on a per-post basis (see "Settings" > "cbParallax")
- Moved the options from the general settings page to "Settings" > "cbParallax"
- Improved the performance of the parallaxing-script
- The interface is more user-friendly now
- You may want to review your image overlay settings on the post edit screens since they work again
### Removed
- Removed the option to set a background color

## 0.5.0
### Changed
- Reduced required PHP-Version to 5.3 or above due to user requests
- Minor bug fixes regarding errors on activation

## 0.4.2
### Changed
- Increased required PHP-Version to 5.4 or above

## 0.4.0
### Changed
- Completely rewritten the script for the public part
- Static image is now also being handled by the public script, it's mobile ready now
- Added an option to disable parallax on mobile ( View the "Settings / General" page). Will show the image as a static background

## 0.3.0
### Changed
- Major bug fixes, the effect now works as expected

## 0.2.6
### Changed
- The scripts for the frontend load only if needed

## 0.2.5
### Added
- Added support for the blog page template
### Changed
- Fixed support for single product page views
- The "preserve scrolling" option supersedes the "Nicescrollr" plugin settings on the frontend, if both plugins are enabled
- Code cleanup and some minor refactorings

## 0.2.4
### Added
- Added a section to the readme file regarding known issues
### Changed
- Optimized the script for the public part
- Updated the readme file

## 0.2.3
### Added
- Added a background color to the image container to kind of simulate a "color" for the overlay
- Added support for "portfolio" post type / entries for web- and media workers :)
### Changed
- Fixed some bugs
- Slightly enhanced meta box display behavior

## 0.2.2
### Removed
- Removed display errors

## 0.2.1
### Added
- Added the option to preserve the nice scrolling behavior without the need to use the parallax feature ( see "Settings / General / cbParallax" )
### Changed
- Resolved the translation bugs
- Optimized the scrolling behavior
- Corrected the scroll ratio calculation
- Corrected the "static" background image display
- Corrected the meta box display behavior

## 0.2.0
### Changed
- Optimized the script responsible for the parallax effect
### Added
- Added Nicescroll for smooth cross-browser scrolling

## 0.1.1
### Changed
- Refactored the script responsible for the parallax effect
- Added the possibility to scroll the background image horizontally
- Added a function to reposition the image on window resize automatically
- Improved performance
- Improved compatibility with webkit, opera and ie browsers
- Implemented a function that eases mouse scroll

## 0.1.0
First release :-)
