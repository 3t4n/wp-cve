=== Podcast Importer SecondLine ===
Contributors: secondlinethemes
Donate link: https://secondlinethemes.com/
Tags: podcast, import, podcasting, feed, audio, rss, episodes, embed, podcasts, player, sync
Requires at least: 4.8
Tested up to: 6.3
Requires PHP: 7.1
Stable tag: trunk
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

A simple podcast import tool for WordPress.

== Description ==

Sync Podcast RSS feeds with your WordPress website automatically. The Podcast Importer plugin helps to easily import podcasts into WordPress. You can import your podcast into the regular WordPress posts or into a custom post type (if you have an existing one). 
The plugin fully supports popular WordPress podcasting plugins such as PowerPress, Seriously Simple Podcasting, Simple Podcast Press, and works even better with podcast themes developed by [SecondLineThemes](https://secondlinethemes.com) 

The plugin supports importing episodes into existing custom post types, assign categories, import featured images and more. Additionally, the plugin enables continuous import or "Sync" of podcast RSS feeds, so every time you release a new podcast episode, it could be automatically created within WordPress. You can also set multiple import schedules and import different podcasts from separate sources at the same time. (For example, when importing separate podcasts from separate feeds into one website)

To use the plugin, simply run a new import under "Tools -> Podcast Importer SecondLine" via the main menu that appears in your WordPress dashboard. Set the different options and if you need a continuous import process for future episodes, make sure to hit that checkbox before running the import process. 
You can disable a schedueld import at any time by simply deleting the import entry. 

The plugin also supports automatic import of native / embed audio players from 15+ podcast hosting providers, including: Buzzsprout, Megaphone, Pinecast, Captivate, Transistor, [Anchor.fm](https://anchor.fm), Simplecast, Podbean, Whooshkaa, Omny, Ausha, Spreaker, [Podcastpage.io](https://podcastpage.io) Audioboom, Fireside, Libsyn and more.

== Pro Version ==
The Pro version can be found here - [https://secondlinethemes.com/podcast-importer-pro](https://secondlinethemes.com/podcast-importer-pro)
It includes:
* Unlimited scheduled imports for podcasts/shows.
* Import to any Custom Post Type or Custom Taxonomy.
* Set specific import intervals / times.
* Import transcripts from RSS feed.
* Import audio player to custom fields.
* Import tags and categories from the feeds.
* Force a re-sync on all existing episodes (to update metadata)
* Set a global featured image to all imported episodes.
* Manual "Sync" button to sync on demand.

== About SecondLineThemes ==

SecondLineThemes is developing unique WordPress themes and plugins for Podcasters. Our tools are very popular among podcasters. To hear more about us please check our website:
[https://secondlinethemes.com](https://secondlinethemes.com)


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/podcast-importer-secondline` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Run a new import via the "Tools -> Podcast Importer Secondline" section in your WordPress admin panel.
4. If needed, delete any of the scheduled import processes. 

== Frequently Asked Questions ==

= The import failed or takes too much time to process? =
You can run the improter multiple times, as it will never import the same post twice. Once all episodes are imported, only future ones would be imported, assuming you selected the continuous import option.
If you import a large feed while importing featured images, it may take several runs to fully complete, so make sure to run the import process several times until all episodes were imported.

= Do you support podcast feeds from any host? =
Sure. All types of podcast feeds can be imported, as long as they are in an RSS/XML format. If you feel something is missing, please reach out and we will ensure to look into it.

= The import does not work for my podcast feed =
First of all, make sure you are filling in a valid URL, of a valid podcast RSS feed. Second, make sure your server is up to modern requirements - we recommend PHP 7 or above.

== Screenshots ==

1. Import your podcast episodes based on multiple options.
2. Add multiple continuous import processes of separate podcasts.

== Changelog ==

= 1.4.8 =
* Updated: Action Scheduler version.

= 1.4.7 =
* Updated: Action Scheduler version.

= 1.4.6 =
* Improved: Make imported links clickable.

= 1.4.5 =
* Improved compatibility

= 1.4.4 =
* Improved: Updated player import from Libsyn

= 1.4.3 =
* Fix: Improved syncing integrity

= 1.4.2 =
* Fix: Fix for importing Transistor embeds

= 1.4.1 =
* Added: Support for importing transcripts directly from your feed. (pro version only)

= 1.4.0 =
* Major rewrite of the importer process - better importing for larger feeds.

= 1.3.9 =
* Added: Specify exact import interval in different scheduled - Weekly, Daily, Hourly, every 30/20/10 minutes. (Pro version only).
* Improved: Processing multiple imports.
* Updated: WP version compatibility.

= 1.3.8 =
* Fixed: Increased default limit of scheduled imports.
* Fixed: Security patch - properly escaping SQL.

= 1.3.7 =
* Updated: WP compatibility.
* Fixed: Upgrade notice still appears on Pro version.
* Fixed: Conflicts with the older version of the Action Scheduler.

= 1.3.6 =
* Added: Support for Sounder.fm embeds.
* Improved: Description texts and naming.

= 1.3.5 =
* Fixed: Embed imports for Fireside/Omny/Libsyn.
* Fixed: Minor PHP issues.

= 1.3.4 =
* Fixed: Image import issues.

= 1.3.3 =
* Fixed: Image upload button.

= 1.3.2 =
* Fixed: Simplecast embeds imports.

= 1.3.1 =
* Fixed: Some embeds weren't being imported.
* Fixed: Author weren't being imported.

= 1.3.0 =
* Major codebase refactor. Introduced the Pro version: [https://secondlinethemes.com/podcast-importer-pro](https://secondlinethemes.com/podcast-importer-pro)
* Added: Limit the imported posts by date.
* Added: Disable importing content at all (set truncate content to 0).
* Fixed: Better handling of custom taxonomies within the Podcast post type.
* Improved: Performance and import speed.


= 1.2.5 =
* Added: Importing embeds from Libsyn - add "?include-libsyn-metadata=true" to your RSS feed URL to import embeds from Libsyn, for example - https://demo-feed.site.com/rss?include-libsyn-metadata=true
* Added: Direct link to the importer on the Plugins page.
* Fixed: Better support for Captivate embeds.
* Updated: Compatibility with WP 5.7.x

= 1.2.4 =
* Updated: Compatibility with WP 5.6.x

= 1.2.3 =
* Fixed: Omny embed audio player import.

= 1.2.2 =
* Fixed: Issues with importing non-standard content tags.

= 1.2.1 =
* Added: Hooks to post import process (both for scheduled and one-time imports).
* Fixed: Default episode/Post content data now has a proper fallback.

= 1.2.0 =
* Major update! Special thanks to Ryan Tvenge from [https://hoverboardstudios.com](https://hoverboardstudios.com)
* Added: Select the import content source (content:encoded / description / summary).
* Added: Option to truncate the content to a specific number of characters during import.
* Added: Option to prepend content to the imported post title, including a tag for the show name - [podcast_title].
* Added: You can now modify existing scheduled imports.
* Added: Import Fireside embedded player.
* Improved: Added "Advanced Options" section.
* Improved: Accessibility improvements around the form and labels.
* Improved: Better support for podcasts using M4A audio formats.
* Improved: New plugin assets.
* Fixed: Featured image import issue across all hosts.
* Fixed: Omny images were treated as duplicates, now importing properly.
* Removed: Plugin banner.

= 1.1.5 =
* Added Spreaker.com as a supported embed host.
* Patched SSRF security issue.

= 1.1.4 =
* Added Ausha.co (Thanks @Jeau!), Pinecast and Audioboom as additional embed providers.
* Increased the default number of "Continuous" imports displaying on the plugin's page.
* Fixed issue with external embed player on some themes.

= 1.1.3 =
* Fixed image import issues with Buzzsprout
* Added 3 additional providers for the embed audio player: Buzzsprout, Captivate.fm, Megaphone.fm
* Added automatic import of season number and episode number (this can only be used with themes from SecondLineThemes.com or via a theme customization)

= 1.1.2 =
* Fixed conflicts between server timezone and WP timezone - episodes are instantly published instead of being scheduled.
* Fixed a bug with the embedded audio player import.

= 1.1.1 =
* Modified XML image object to parse as string (caused a bug in WP 5.4).
* Updated the compatibility tag for WordPress 5.4. 

= 1.1.0 =
* Added cURL fallback for better compatibility on certain servers. 

= 1.0.9 =
* Advanced functionality added for users using our premium themes.
* Fixed minor error displaying while importing a new podcast with no images.

= 1.0.8 =
* You can now select multiple categories for your imports.
* Fixed minor bug with image importing.
* Updated PAnD version (dismiss notices).

= 1.0.7 =
* Fixed new episodes not importing when no GUID in feed.

= 1.0.6 =
* Fixed multiple issues with image imports.
* Added filesize data to imported episodes.
* Minor fix when no duration is specified in RSS.

= 1.0.5 =
* Avoid scheduling posts to future dates when RSS and Server have timezone conflicts.
* Now adding episode duration when possible.
* Added minor new CSS styles to better match WordPress 5.3

= 1.0.4 =
* Fixed scheduled import throwing errors (post_exists function undefined) on some occasions.

= 1.0.3 =
* Fixed audio file import when no embed player is available, but embed option was selected.

= 1.0.2 =
* Fixed some issues with duplicate imports and unsaved settings.
* Added support for Omny embedded audio player.
* Improved performance.

= 1.0.1 =
* Added option to use an embed audio player rather than the default WordPress audio player for several hosts (Transistor.fm, Anchor.fm, Simplecast, Podbean, Whooshkaa)

= 1.0 =
* Initial Release.
