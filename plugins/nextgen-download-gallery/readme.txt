# NextGEN Download Gallery
Contributors: webaware
Plugin Name: NextGEN Download Gallery
Plugin URI: https://wordpress.org/plugins/nextgen-download-gallery/
Author URI: https://shop.webaware.com.au/
Donate link: https://shop.webaware.com.au/donations/?donation_for=NextGEN+Download+Gallery
Tags: nextgen, gallery, download
Requires at least: 4.0
Tested up to: 6.4
Requires PHP: 5.6
Stable tag: 1.6.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add a template to NextGEN Gallery that provides multiple-file downloads for trade/media galleries

## Description

Add a template to NextGEN Gallery that provides multiple-file downloads for trade/media galleries. [NextGEN Gallery](https://wordpress.org/plugins/nextgen-gallery/) is one of the best gallery plugins for WordPress because it is very flexible and has a nice, simple admin. This plugin adds a new gallery template that lets you select multiple images from the gallery to be downloaded as a ZIP archive.

NB: the Photocrati version of NextGEN Gallery can impact the performance of your server, and not all of the old plugin's functionality works. You might want to consider using [NextCellent Gallery](https://wordpress.org/plugins/nextcellent-gallery-nextgen-legacy/) instead -- it's a fork of the original NextGEN Gallery with continued support and compatibility, without the performance impacts.

NextGEN Download Gallery is targetted at creating "Trade/Media" areas on websites, allowing journalists to easily download multiple product images. It's apparently very popular with photographers too.

### Translations

Many thanks to the generous efforts of our translators:

* Czech (cs_CZ) -- [Rudolf Klusal](http://www.klusik.cz/)
* Danish (da_DK) -- [Ligefrem](http://www.ligefrem.dk/)
* Dutch (nl_NL) -- [the Dutch translation team](https://translate.wordpress.org/locale/nl/default/wp-plugins/nextgen-download-gallery/)
* English (en_CA) -- [the English (Canadian) translation team](https://translate.wordpress.org/locale/en-ca/default/wp-plugins/nextgen-download-gallery/)
* English (en_GB) -- [the English (British) translation team](https://translate.wordpress.org/locale/en-gb/default/wp-plugins/nextgen-download-gallery/)
* English (en_ZA) -- [the English (South African) translation team](https://translate.wordpress.org/locale/en-za/default/wp-plugins/nextgen-download-gallery/)
* French (fr_FR) -- Nicolas Sizun
* Korean (ko_KR) -- [the Korean translation team](https://translate.wordpress.org/locale/ko/default/wp-plugins/nextgen-download-gallery/)
* Portuguese (pt_BR) -- [Juliano Arantes](http://www.42fotografia.com.br/)
* Polish (pl_PL) -- Jakub Molek and [the Polish translation team](https://translate.wordpress.org/locale/pl/default/wp-plugins/nextgen-download-gallery/)
* Russian (sv_SE) -- [the Russian translation team](https://translate.wordpress.org/locale/ru/default/wp-plugins/nextgen-download-gallery/)
* Swedish (sv_SE) -- [the Swedish translation team](https://translate.wordpress.org/locale/sv/default/wp-plugins/nextgen-download-gallery/)
* Turkish (tr_TR) -- [the Turkish translation team](https://translate.wordpress.org/locale/tr/default/wp-plugins/nextgen-download-gallery/)

If you'd like to help out by translating this plugin, please [sign up for an account and dig in](https://translate.wordpress.org/projects/wp-plugins/nextgen-download-gallery/).

### Credits

This program incorporates a little code that is copyright by Photocrati Media 2012 under the GPLv2. Some PHP code was copied from NextGEN Gallery and altered, so that the `nggtags` shortcode could be extended as `nggtags_ext` and specify a gallery template.

## Installation

1. Install [NextGEN Gallery](https://wordpress.org/plugins/nextgen-gallery/) or [NextCellent Gallery](https://wordpress.org/plugins/nextcellent-gallery-nextgen-legacy/), and create galleries/albums
2. Upload this plugin to your /wp-content/plugins/ directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Specify the gallery template as "download"

### From a gallery shortcode

When using a shortcode to show a NextGEN gallery, you can make it a download gallery by specifying the gallery template:

`[nggallery id=1 template=download]`

### From an album shortcode

When using a shortcode to show a NextCellent Gallery album, you can make it show download galleries by specifying the gallery template:

`[nggalbum id=1 gallery=download]`

NB: NextGEN Gallery 2.0 still doesn't support this functionality, as at v2.0.66.17; see FAQ for work-around.

### From a tags shortcode

The standard `nggtags` shortcode doesn't allow you to specify the gallery template, so this plugin adds an extended version of that shortcode.

`[nggtags_ext gallery="frogs,lizards" template=download]`

Or in NextGEN Gallery v2.0:

`[ngg_images tag_ids="frogs,lizards" template=download display_type="photocrati-nextgen_basic_thumbnails"]`

## Frequently Asked Questions

### Will this plugin work without NextGEN Gallery or NextCellent Gallery?

No. [NextGEN Gallery](https://wordpress.org/plugins/nextgen-gallery/) / [NextCellent Gallery](https://wordpress.org/plugins/nextcellent-gallery-nextgen-legacy/) are doing all the work. This plugin is only adding a new gallery template and the ZIP download functionality.

### Can I make an album use the download template?

Yes, in NextCellent Gallery the album shortcode has separate parameters for album and gallery templates. The "template" parameter tells it which template to use for the album, and the "gallery" parameter tells it which template to use for the gallery. e.g.

`[nggalbum id=1 template=compact gallery=download]`

NB: NextGEN Gallery v2.0 still doesn't support this functionality. Instead, you need to link a page to each gallery in Gallery > Manage Galleries, and use the `nggallery` shortcode on those pages to set the template as "download".

### Can I make the tags shortcode use the download template?

In NextCellent Gallery, just add the template to the `nggtags` shortcode:

`[nggtags gallery="frogs,lizards" template=download]`

NextGEN Gallery v2.0 introduces a new shortcode, `ngg_images`; see the [Photocrati documentation for ngg_images](https://www.imagely.com/docs/nextgen-gallery-shortcodes/). This new shortcode can support a template parameter, like this:

`[ngg_images tag_ids="frogs,lizards" template=download display_type="photocrati-nextgen_basic_thumbnails"]`

### I don't like the download template; can I customise it?

Yes. Copy the template from the templates folder in the plugin, into a folder called nggallery in your theme's folder. You can then edit your copy of the template to get the pretty.

### Why does it break when I select too many images?

There can be several reasons, but the most common one is that your server is limiting the size of temporary files. You might be able to work around that by telling WordPress to use your uploads folder for temporary files. To do that, add this line to your wp-config.php file, just below the lines defining ABSPATH near the bottom of the file:

`define('WP_TEMP_DIR', ABSPATH . '/wp-content/uploads/');`

### You've translated my language badly / it's missing

The initial translations were made using Google Translate, so it's likely that some will be truly awful! Please help by [registering to translate into your preferred language](https://translate.wordpress.org/projects/wp-plugins/nextgen-download-gallery).

### Can I change the image paths, to download a different image?

If you have higher resolution images you'd like to download instead of the ones displayed, you can use a WordPress filter hook. See [this support post](https://wordpress.org/support/topic/linking-to-hr-images-again#post-4385317) for details. **NB:** this is advanced and requires some programming ability!

## Screenshots

1. example download gallery

## Upgrade Notice

### 1.6.2

fixed fatal error "call to a member function get_entity() on null"

## Changelog

[The full changelog can be found on GitHub](https://github.com/webaware/nextgen-download-gallery/blob/master/changelog.md). Recent entries:

### 1.6.2

Released 2023-12-05

* fixed: fatal error "call to a member function get_entity() on null"
