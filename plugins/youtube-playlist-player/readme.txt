=== YouTube Playlist Player ===
Contributors: butterflymedia
Donate link: https://www.buymeacoffee.com/wolffe
Tags: youtube, player, playlist, video, carousel, thumbnail, javascript
Requires at least: 4.9
Tested up to: 6.4.3
Requires PHP: 7.0
Stable tag: 4.6.9
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Display a YouTube player (with an optional playlist) on any post or page using a simple shortcode.

== Description ==

Display a YouTube player (with an optional playlist) on any post or page using a simple shortcode. The plugin supports a static YouTube player (no video title) and a dynamic one (video title) using the YouTube Data API v3.

Embedded players must have a viewport that is at least 200px by 200px. If the player displays controls, it must be large enough to fully display the controls without shrinking the viewport below the minimum size. We recommend 16:9 players be at least 480 pixels wide and 270 pixels tall.

The YouTube player is responsive and it will work on all themes and screen sizes!

* Uses a simple shortcode which can be used in posts, pages, custom post types, widgets, reusable blocks.
* Uses the default YouTube Embed Code (iframe) with optional privacy-enhanced mode.
* Uses correct aspect ratio for videos using a Fluid Video technology.
* Uses native HTML5 Lazy loading.
* Used modern code and is optimised for speed.
* 100% free with no ads inside.

### Static YouTube Playlist Player

Example: `[yt_playlist mainid="xcJtL7QggTI" vdid="xcJtL7QggTI, AheYbU8J5Tc, X0zGS4-UKgg, 74SZXCQb44s, 2M0XCH9q3YI, CTNgVQGLy24, B8RpvoHsgI8"]`

### YouTube V3 API Playlist Player

Example: `[yt_playlist_v3 mainid="xcJtL7QggTI" vdid="xcJtL7QggTI, AheYbU8J5Tc, X0zGS4-UKgg, 74SZXCQb44s, 2M0XCH9q3YI, CTNgVQGLy24, B8RpvoHsgI8"]`

### YouTube Channel Feed with Lightbox Player

Example: `[yt_feed channels="UCpVm7bg6pXKo1Pr6k5kxG9A" results="9"]`

Check out the [official YouTube Playlist Player website](https://getbutterfly.com/wordpress-plugins/youtube-playlist-player/) and a [YouTube Playlist Player demo](https://getbutterfly.com/wordpress-plugins/youtube-playlist-player/).

Check out a [Property Videos & Virtual Tours](https://kmproperty.ie/buy/videos-virtual-tours/) demo.

Check out more [WordPress plugins here](https://getbutterfly.com/wordpress-plugins/).

== Installation ==

1. Upload to your plugins folder, usually `wp-content/plugins/`
2. Activate the plugin on the plugins screen.
3. Configure the plugin from Settings -> YouTube Playlist Player.

== Screenshots ==

1. Front-end player #1
2. Front-end player #2
3. Dashboard
4. General Settings
5. YouTube API
6. Help/Usage

== Changelog ==

= 4.6.9 =
* UPDATE: Updated WordPress compatibility

= 4.6.8 =
* FIX: Fixed Cross Site Scripting (XSS) vulnerability (props yuyudhn via Patchstack)

= 4.6.7 =
* FIX: Fixed missing scripts and styles for the new channel feed feature

= 4.6.6 =
* FEATURE: Added YouTube channel feeds
* UPDATE: Updated WordPress compatibility

= 4.6.5 =
* FIX: Fixed Cross Site Scripting (XSS) vulnerability (props Skalucy via Patchstack)
* UPDATE: Updated PHP coding standards
* UPDATE: Updated WordPress compatibility

= 4.6.4 =
* FIX: Fixed Cross Site Scripting (XSS) vulnerability (props Yudha P. via Patchstack)
* UPDATE: Updated copyright year
* UPDATE: Removed unused patterns from PHPCS ruleset

= 4.6.3 =
* UPDATE: Updated author banner
* UPDATE: Updated WordPress compatibility for pre-5.0 versions
* UPDATE: Updated WPCS ruleset
* UPDATE: Replaced back-end PNG image with inline SVG

= 4.6.2 =
* FIX: Fixed wrong class in plugin documentation
* FIX: Clarified usage in plugin documentation and `readme.txt`
* UPDATE: Added cleanup routine after plugin uninstallation (delete 6 options)
* UPDATE: Updated `readme.txt` with shortcodes and features

= 4.6.1 =
* FIX: Fixed a content filtering issue with "Rate my post" plugin (props @sabelya)
* UPDATE: Updated WordPress compatibility

= 4.6.0 =
* FIX: Removed a redundant variable
* UPDATE: Updated WordPress compatibility
* UPDATE: Updated codebase to conform to latest WordPress Coding Standards (WPCS) ruleset

= 4.5.9 =
* FIX: Fixed documentation link
* UPDATE: Updated WordPress compatibility

= 4.5.8 =
* UPDATE: Updated PHP 8 compatibility
* UPDATE: Added lazy loading for iframes
* UPDATE: Implemented strict use for JavaScript

= 4.5.7 =
* UPDATE: Updated WordPress compatibility
* UPDATE: Removed old, unused code

= 4.5.6 =
* FIX: Fixed aspect-ratio for Firefox and Safari (props @sabelya)
* UPDATE: Updated PHP coding standards (function naming)
* UPDATE: Updated plugin assets

= 4.5.5 =
* FIX: Sanitized URL parameter in back-end
* UPDATE: Combined and minified JavaScript
* UPDATE: Minified CSS
* UPDATE: Optimized DOM loaded functions
* PERFORMANCE: Removed `setInterval()` for detecting YouTube iframe
* PERFORMANCE: Added version number to CSS to break caching
* PERFORMANCE: Removed heavy JavaScript for detecting video aspect ratio
* PERFORMANCE: Implemented modern CSS aspect-ratio for Core Web Vitals compatibility

= 4.5.4 =
* FIX: Fixed YouTube API V3 demo
* FIX: Fixed YouTube API V3 click event (switched to event delegation)
* UPDATE: Updated classic playlist JavaScript to ES6
* UPDATE: Updated `readme.txt` links
* UPDATE: Added donation link

= 4.5.3 =
* UPDATE: Updated WordPress compatibility

= 4.5.2 =
* UPDATE: Updated WordPress compatibility
* UPDATE: Updated JavaScript to ES6
* FIX: Fix version number for enqueued scripts

= 4.5.1 =
* FIX: Fixed issue with playlist not appearing
* FIX: Fixed issue with playlist styling
* UPDATE: Refactored JS for less overhead
* UPDATE: Updated WordPress compatibility
* UPDATE: Updated demo link

= 4.5.0 =
* UPDATE: Updated PHP requirements
* UPDATE: Updated WordPress compatibility

= 4.4.1 =
* FIX: Fixed a strict check
* FIX: Added spaces removal for V3 shortcode (main video)
* FIX: Added spaces removal for V3 shortcode (playlist)

= 4.4.0 =
* UPDATE: Updated WordPress compatibility
* UPDATE: Removed jQuery dependency
* UPDATE: Forced cache clearing for JavaScript actions

= 4.3.5 =
* UPDATE: Code quality fixes
* UPDATE: Updated JavaScript DOM loading detection

= 4.3.4 =
* UPDATE: Updated WordPress compatibility
* UPDATE: Mobile UI tweaks

= 4.3.3 =
* FIX: Fixed localized issue not saving options

= 4.3.2 =
* UPDATE: Updated WordPress compatibility
* UPDATE: Added new screenshots
* UPDATE: UI tweaks

= 4.3.1 =
* FIX: Removed old code
* UPDATE: Refactored and moved player functions
* UPDATE: Added YouTube related options
* UPDATE: Removed unused option
* UPDATE: Added more documentation (+ YouTube API how-to)
* UPDATE: Added more/better YouTube branding

= 4.3 =
* FIX: Loaded JS/CSS assets only when shortcode is present
* FEATURE: Added YouTube API V3
* FEATURE: Added new settings screen
* FEATURE: Added new shortcode
* UPDATE: Added a bit of documentation
* UPDATE: Added more/better YouTube branding

= 4.2.4 =
* FIX: YouTube Branding fixes
* FIX: Author box layout fixes

= 4.2.3 =
* FIX: Regression fix for previous version (added interval checking)

= 4.2.2 =
* FIX: Fixed player detection before being loaded

= 4.2.1 =
* FIX: Fixed JS code being executed on all pages
* UPDATE: Updated readme.txt

= 4.2 =
* FIX: Added PHP compatibility
* FIX: Fixed/updated old screenshots
* FIX: Removed jQuery dependency
* FIX: Fixed JS codeflow
* UPDATE: Updated WordPress compatibility
* UPDATE: Updated readme.txt and general information

= 4.1.6 =
* FIX: Fixed script being included before jQuery
* FIX: Fixed duplicated variable assignment
* FIX: Fixed strict variable assignment
* FIX: Removed unused colour picker script
* UPDATE: Updated plugin usage details
* UPDATE: Small admin UI tweaks
* UPDATE: Removed `novd` argument and switched to internal count

= 4.1.5 =
* PERFORMANCE: Stopped options from autoloading
* UPDATE: Updated WordPress compatibility
* UPDATE: Better i18n options
* UPDATE: Removed unused colour option

= 4.1.4 =
* FIX: Removed version constant
* FIX: Better security tweaks
* UPDATE: Updated admin menu name to reflect the plugin

= 4.1.3 =
* FIX: License update
* FIX: Official link update

= 4.1.2 =
* FIX: Fixed color picker enqueue dependency
* UPDATE: Moved all JS code to a separate file
* UPDATE: Changed the main video playlist function (JS) to accept parameters

= 4.1.1 =
* FIX: Removed hardcoded background colour
* FIX: Removed hardcoded padding and increased margin
* FIX: Correctly enqueued style.css
* UPDATE: Updated default height and added option autoloading
* UPDATE: Completely refactored YouTube Javascript
* UPDATE: Removed all Flash (SWFObject) dependencies

= 4.1.0 =
* FIX: Added `index.php` file to plugin root
* UPDATE: Updated plugin URLs
* UPDATE: Updated CSS styles for better compatibility

= 4.0.1 =
* UPDATE: Added getButterfly ad box

= 4.0.0 =
* FIX: Changed all HTTP links to HTTPS
* FIX: Updated YouTube API and removed all deprecated functions and parameters
* FIX: Removed parameters with same values as the default ones
* FIX: Cleaned up the code (slight performance increase)
* FIX: Fixed rare cases of line ending issues
* UI: Removed background color for better theme integration

= 3.2.0 =
* FIX: Fixed IFRAME name target
* FEATURE: Added responsiveness

= 3.1.0 =
* FIX: Fixed a PHP warning
* FIX: Removed deprecated options nonce
* FEATURE: Added usage details on the plugin page
* PROMOTION: Added link to premium version on CodeCanyon

= 3.0.2 =
* Added license link
* Added donate link
* Added default options
* Fixed wrong internal version

= 3.0.1 =
* Added CSS vendor prefixes

= 3.0.0 =
* Initial release
