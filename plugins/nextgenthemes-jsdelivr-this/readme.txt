=== NGT jsDelivr CDN ===
Contributors: nico23
Tags: CDN, JS, JavaScript, jQuery, Performance, minimalistic, minimal
Donate link: https://nextgenthemes.com/donate
Requires at least: 4.3.1
Requires PHP: 5.6
Tested up to: 5.2.2
Stable tag: 1.0.0
License: GPL 3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Free CDN for WordPress Core and Plugin assets.

== Changelog ==

= 2019-09-07 1.0.0 =
* Run only once per page-load, better function names and some useful comments.

= 2019-08-31 0.9.4 =
* Coding standards, some minor things.

= 2018-09-23 0.9.2 =
* Fix: Force the recheck of file hashes when WP gets updated to not end up with core assets.

= 2018-09-01 0.9.1 =
* Release

== Description ==
It replaces all WP Core assets with versions hosted on jsDelivr.

The following conditions need to be met that plugins assets will be served from jsDeliver:

1. The plugin needs to be hosted on wp.org. Commercial plugins and plugins from elsewhere will not work because jsDelivr mirrors the wp.org plugin dir and nothing else.
1. The asset src URL most have `/plugins/plugin-slug/` in them and end with `.js` or `.css` (excluding cash busting `?ver=1.2.3`).
1. It needs to have its current version published as a tag on the wp.org plugins SVN. Some plugins may only push to /trunk/ (like my own at the time of writing) or do not have their latest version published as tags.

= Donations are really appreciated =

It took me a lot of time to come up with this plugin and I had many iterations over various different approaches how to do this until I came up with this working solution that also does not need much code. I know the official plugin was abandoned years ago and I looked at complicated bloated code and did not even feel like learning what its doing and never looked at it again and started from scratch. [Please donate here](https://nextgenthemes.com/donate/).
