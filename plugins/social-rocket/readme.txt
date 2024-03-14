=== Social Rocket - Social Sharing Plugin ===
Contributors: socialrocket
Donate link: https://wpsocialrocket.com/
Tags: social share, social buttons, social share buttons, social media, share counts, social sharing, click to tweet, social rocket, facebook share, social media share, pinterest description, social media sharing
Requires at least: 4.4
Tested up to: 6.0
Requires PHP: 5.5
Stable tag: 1.3.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add fully-customizable social sharing buttons to your site. Easy to use and packed with many additional social networking features.

== Description ==
= Out of this world social sharing shouldn't be rocket science. =

Social Rocket adds fully-customizable social sharing buttons to your site. It's easy to use, and packed with many additional social networking features we're sure you'll love.

= Features =

*   Easily add buttons for Facebook, Twitter, Pinterest, LinkedIn, Mix, Reddit, Buffer, Email, and Print
*   Inline sharing buttons integrate seamlessly with your content
*   Floating buttons stay fixed edge of the screen
*   Buttons can be inserted automatically at the location you choose
*   Or use a shortcode to insert buttons anywhere you need them
*   Specify placement settings for all content, specific types of content, or specific posts/pages
*   Full support for all posts, pages, CPTs, and even archives!
*   Click to Tweet shortcode/block
*   Set custom sharing description, image, and more for individual posts, pages or archives
*   Import/export settings
*   Backup/restore your data on demand
*   Compatible with both Gutenberg and Classic Editor
*   Compatible with WP Multisite
*   Compatible with WooCommerce
*   Compatible with Easy Digital Downloads
*   Translation ready
*   Lots of hooks and filters available for further customization


= Pro Features =

[Social Rocket Pro](https://wpsocialrocket.com/products/social-rocket-pro/?utm_source=WordPress&utm_medium=Readme&utm_content=Social-Rocket-Pro&utm_campaign=Free) adds many more features to the core Social Rocket plugin, including:

*   More networks: Digg, Evernote, Flipboard, Hacker News, Pocket, Telegram, Tumblr, Vkontakte, WhatsApp, Yummly, and more being added soon!
*   "More" button allows you to hide certain buttons and reveal on click
*   Separate settings for Mobile vs Desktop -- customize the appearance and placement of your buttons for different screens
*   Link shortening via Bitly
*   Share Count Rescue -- get back shares from previous URLs (for example if you changed domains, http to https, or any other changes)
*   Optionally show custom Pinterest image on your posts/pages
*   And much more!

We also offer a Migrator extension that allows you to easily transfer your settings and data from other social sharing plugins including Mashshare, Social Pug, and Social Warfare.

*	[Social Rocket Migrator extension](https://wpsocialrocket.com/products/social-rocket-migrator/?utm_source=WordPress&utm_medium=Readme&utm_content=Social-Rocket-Migrator&utm_campaign=Free)

> <strong>Visit our website to find out more</strong>
>
> [Documentation](https://docs.wpsocialrocket.com/?utm_source=WordPress&utm_medium=Readme&utm_content=Documentation&utm_campaign=Free) | [Support](https://wpsocialrocket.com/support/?utm_source=WordPress&utm_medium=Readme&utm_content=Support&utm_campaign=Free) | [Extensions](https://wpsocialrocket.com/products/?utm_source=WordPress&utm_medium=Readme&utm_content=Extensions&utm_campaign=Free)



== Frequently Asked Questions ==

= Where can I find documentation? =

There is full documentation as well as a Getting Started guide at [our website](https://docs.wpsocialrocket.com/?utm_source=WordPress&utm_medium=Readme-FAQ&utm_content=Documentation&utm_campaign=Free)

= Help! Where can I get support? =

Support is just a click away on [our website](https://wpsocialrocket.com/support/?utm_source=WordPress&utm_medium=Readme-FAQ&utm_content=Support&utm_campaign=Free)



== Screenshots ==
1. Getting started page
2. Inline Buttons settings
3. Floating Buttons settings
4. Click to Tweet settings
5. Settings: Advanced
6. Settings: Social Extras
7. Settings: Tools


== Changelog ==
= 1.3.3 =
* FIX: sanitization issues with button CTA, icon class.

= 1.3.2 =
* FIX: issues with dismissing Facebook "invalid token" notice.
* FIX: PHP notice.

= 1.3.1 =
* FIX: minor CSS fixes.
* UPDATE: update FontAwesome to latest version.

= 1.3.0 =
* NEW: add "clear background queue" button, in tools tab.
* FIX: issue with Yoast SEO overriding our open graph tags.
* UPDATE: compatibility updates for Social Rocket Pro v1.3.x
* UPDATE: updated .pot file for translations.

= 1.2.12 =
* UPDATE: WordPress 5.5 compatibility.
* UPDATE: make Pinterest popups a little bigger (following Pinterest's new layout).

= 1.2.11 =
* UPDATE: add aria labels to share buttons.

= 1.2.10 =
* FIX: possible CSRF issue.
* FIX: will not attempt to get Facebook counts if access token is empty.
* UPDATE: updated .pot file for translations.

= 1.2.9 =
* UPDATE: update facebook API to v7.0 for getting share counts.
* UPDATE: show admin notice if facebook token is invalid.
* UPDATE: add fallback "social-rocket-hidden-pinterest-image" CSS class. (May fix situations where 3rd party plugins overwrite the style attribute on images.)
* UPDATE: updated .pot file for translations.

= 1.2.8 =
* FIX: issue with incorrect share counts due to post/page previews
* FIX: email button not using custom subject or body.
* FIX: PHP notice.
* UPDATE: added "automatically fix Gutenberg blocks" feature.
* UPDATE: minor refactoring.

= 1.2.7 =
* FIX: compatibility issue with Tasty Recipes plugin.
* FIX: invalid path in automatic settings backup.

= 1.2.6 =
* FIX: add check if function getmypid() exists. (We use the PHP function getmypid as part of checking whether our background process is still running or not. Apparently certain shared hosts disable getmypid, so we can't rely on this function being available to us.)

= 1.2.5 =
* FIX: issues with archives pages when display type set to "item".
* UPDATE: add compatibility for Tasty Recipes plugin.
* UPDATE: add 8080 to list of accepted ports when determining URLs.
* UPDATE: new background processing system. (Stopped using 3rd-party wp-background-processing library, now using our own. This should make processing more efficient).

= 1.2.4 =
* FIX: compatibility issue with Gutenberg 5.3.
* UPDATE: added visible option for "Master API Throttle" (under Settings / Advanced).
* UPDATE: updated .pot file for translations.

= 1.2.3 =
* FIX: clear background queue upon deactivation.
* UPDATE: refactor master throttle.

= 1.2.2 =
* FIX: issue with share URL when using "plain" permalinks.
* FIX: don't try to get counts for autosaves, rest api calls, etc.
* UPDATE: minor CSS update.

= 1.2.1 =
* UPDATE: increased popup window height
* UPDATE: minor CSS update
* UPDATE: slow down background processing; added master throttle value (reduces DB usage for servers with limited resources)

= 1.2.0 =
* NEW: add compatibility for MyBookTable plugin
* NEW: add option for custom border hover color
* NEW: add option to show/hide Total Shares icon
* NEW: consider paged requests as requests for original url. (In other words, if viewing page 2 of an archive, the shared URL will link to page 1). Also added new hook 'social_rocket_archives_url_use_first_page'; returning false to it will undo this change.
* FIX: issue with icon hover color not working correctly when set to "none"
* FIX: issue with mobile buttons not appearing if no desktop buttons are activated
* FIX: minor CSS fixes
* UPDATE: compatibility updates for Social Rocket Pro v1.1.x
* UPDATE: minor restyling of Pinterest settings for images/attachments
* UPDATE: refactoring background share count processing, to process queues more efficiently
* UPDATE: updated .pot file for translations.

= 1.1.1 =
* FIX: issue with genesis/divi themes and our code to prevent share buttons from showing up in the_excerpt.
* FIX: issue with Click To Tweet Call to Action color not showing up.

= 1.1.0 =
* NEW: improved Click to Tweet settings UI. This should help solve confusion about which style is being edited, what is being saved and where.
* NEW: improved Gutenberg editor controls for Inline Buttons block. Specifically, all networks can be selected from and sorted in any order for a given block, regardless of the global settings.
* NEW: show Pinterest Description field in image details modal (Classic Editor) and image block settings (Gutenberg).
* FIX: change which hooks we use in different Thrive Builder usage scenarios.
* FIX: conflicting sources of OG tags / Twitter cards (e.g. Jetpack).
* FIX: Floating Buttons text color override missing in generated CSS code.
* FIX: issue using Click To Tweet "saved style" option in Classic Editor.
* FIX: issue with Click to Tweet not using Bitly URL, if available.
* FIX: issue with OG tags & Twitter cards not populating correctly on blog index & other "special" pages.
* FIX: issue with SVG colors.
* FIX: reduce Facebook API throttle value to avoid triggering warning emails from Facebook. (Even though we weren't exceeding the allowed limits, apparently Facebook sends warning emails at around 70-80% of usage. So now we aim to keep our usage at no more than 50% of the allowed limit. The result is Facebook share counts will take slightly longer to update, but no more annoying warning emails should be triggered.)
* FIX: remove share buttons from showing up in the_excerpt.
* UPDATE: always show Pinterest- and Twitter-specific post meta fields in post editor, regardless of whether those networks are activated in the settings.
* UPDATE: change the way $theme_locations is formatted to allow for the addition of "fallback" hooks.
* UPDATE: don't automatically insert buttons on 404 pages or attachment pages; don't automatically insert buttons on search results pages, except Inline Buttons when archive display setting is set to "item".
* UPDATE: strip trailing zeros after decimal (if present) in displayed share counts.
* UPDATE: updated .pot file for translations.

= 1.0.1 =
* FIX: compatibility with Thrive Architect page builder.
* UPDATE: Add some descriptive text, documentation links.

= 1.0.0 =
* Initial release at WordPress.org.
