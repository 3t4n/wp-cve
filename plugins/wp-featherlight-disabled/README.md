# WP Featherlight Disabled

Fork of the original lightweight jQuery lightbox for WordPress images and galleries.

__Contributors:__ [Johnny Nguyen](https://github.com/wpjohnny), zeroneit
__Requires:__ WordPress 4.0
__Tested up to:__ WordPress 5.4.1
__License:__ [GPL-2.0+](http://www.gnu.org/licenses/gpl-2.0.html)

## Description ##

WP Featherlight Disabled is a fork of the original ultra-light WP Featherlight jQuery lightbox plugin. This plugin displays all standard WordPress images and galleries in a simple, minimalistic lightbox popup. Its total weight is only 6-7kb in CSS/JS. 

- The original version loads the plugin CSS/JS globally and can be manually disabled per post via checkbox. 
- Mine is the opposite, plugin CSS/JS is only loaded if manually enabled per post via checkbox.

In order for WordPress images and galleries to be lightboxed, you need to select the "Media File" option when choosing where the thumbnails should link. You can also select the "Custom Link" option if you make sure to link directly to an image file. This should work for any image file even if it's hosted on another website.

![media-file](https://cloud.githubusercontent.com/assets/2184093/9620710/8850a71e-50e3-11e5-8c89-065fdd0d367d.jpg)

It's also possible to load Videos, iframes, and ajax content using WP Featherlight by adding data attributes to your content. For more details on custom content loading, check out the [featherlight documentation](https://github.com/noelboss/featherlight/#usage).

There are no settings for WP Featherlight Disabled, so you should be able to install it without needing to configure anything.

Here's an example of the plugin being used to load a large (5mb) external image in a standard WordPress post:

![Loader in Action](https://cloud.githubusercontent.com/assets/2184093/7943635/5ba2155e-092b-11e5-8b97-be5ca8cc77d8.gif)

If you find a display problem, it may be related to your theme but can [open an issue at the original GitHub](https://github.com/cipherdevgroup/wp-featherlight/issues) to have them look into it. For more information about the Featherlight script itself, check out their [GitHub plugin page](http://noelboss.github.io/featherlight/).

## Installation ##

The best way to install this plugin is to either [download a copy](https://github.com/WPJohnny-plugins/wp-featherlight-disabled) from the GitHub. Once available on WordPress.org repository, can search for "WP Featherlight Disabled" from your WordPress admin dashboard.

## Developer Notes ##

While there are no options in the plugin, there are some handy filters to modify the default behavior. As of `0.3.0` all images which use the default WordPress captions will also include a caption when the image is lightboxed. To disable this behavior, filter `wp_featherlight_captions` to false.

If you have questions about how any part of the plugin works, please don't hesitate to [open an issue](https://github.com/cipherdevgroup/wp-featherlight/issues).

## Contributing ##

Developers can fork off this [WPJohnny fork](https://github.com/WPJohnny-plugins/wp-featherlight-disabled_) or most current original found on the [develop branch](https://github.com/cipherdevgroup/wp-featherlight/tree/develop). Pull requests, issues, and any feedback are all more than welcome. If you would like to contribute code, please make your pull requests against the develop branch rather than the master.
