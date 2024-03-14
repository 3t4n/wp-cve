<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return <<<'SCRIPT'

var mobx_wp_gallery = function() {

	var wp_galleries = document.querySelectorAll( '.gallery[id*="gallery-"], .wp-block-gallery' );

	for ( var g = 0, gl = wp_galleries.length; g < gl; g++ ) {

		// Remove data carousel attr to prevent opening lightbox (jetPack)
		wp_galleries[g].removeAttribute( 'data-carousel-extra' );

		var wp_images = wp_galleries[g].querySelectorAll( '.gallery-icon > a, .blocks-gallery-item a' );

		for ( var i = 0, il = wp_images.length; i < il; i++ ) {

			var wp_link  = wp_images[i],
				wp_image = wp_link.firstElementChild,
				isAuto   = ( wp_image.title || wp_image.alt ) && mobx_options.autoCaption,
				caption  = wp_link.parentElement.nextElementSibling,
				slibing  = wp_link.nextElementSibling;

			caption = caption && caption.className.indexOf( 'wp-caption-text' ) > -1 ? caption.innerHTML : '';

			// Get Gutenberg caption.
			if ( slibing && slibing.tagName === 'FIGCAPTION' ) {
				caption = slibing.innerHTML || caption;
			}

			// Trick to prevent conflict with Gutenberg images having same class name structure than gallery.
			wp_image.setAttribute('data-rel', 'wp-gallery-' + ( g + 1) );
			wp_image.setAttribute('data-title', isAuto ? wp_image.title || wp_image.alt  : caption || '' );
			wp_image.setAttribute('data-desc', isAuto ? caption : '' );
			wp_image.setAttribute('data-thumb', wp_image.src );

			mobx.addAttr( wp_link, {
				rel    : 'wp-gallery-' + ( g + 1),
				title  : isAuto ? wp_image.title || wp_image.alt  : caption || '',
				desc   : isAuto ? caption : '',
				thumb  : wp_image.src
			});

		}

	}

};

mobx_wp_gallery();

SCRIPT;
