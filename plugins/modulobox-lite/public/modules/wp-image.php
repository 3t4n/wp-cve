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

var mobx_wp_images = function() {

	var wp_images = document.querySelectorAll( 'a > img[class*="wp-image-"]' );

	for ( var i = 0, il = wp_images.length; i < il; i++ ) {

		var wp_image = wp_images[i],
			wp_link  = wp_image.parentElement,
			isAuto   = ( wp_image.title || wp_image.alt ) && mobx_options.autoCaption,
			caption  = wp_link.nextElementSibling,
			slibing  = wp_link.nextElementSibling;

		caption = caption && caption.className.indexOf( 'wp-caption-text' ) > -1 ? caption.innerHTML : '';

		// Get Gutenberg caption.
		if ( slibing && slibing.tagName === 'FIGCAPTION' ) {
			caption = slibing.innerHTML || caption;
		}

		wp_image.setAttribute( 'data-src', wp_link.href );

		mobx.addAttr( wp_image, {
			title  : isAuto ? wp_image.title || wp_image.alt  : caption || '',
			desc   : isAuto ? caption : ''
		});

	}

};

jQuery( document ).ready(function(){

	// disable jetPack lightbox
	jQuery( '.single-image-gallery' ).removeData( 'carousel-extra' );
	mobx_wp_images();

});

SCRIPT;
