<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<script type="text/html" id="wis-custom-gallery-markup">
	<span class="setting has-description">
		<span class="name">Woo Image SEO</span>

		<span>
			<a href="#wis-media-library-help-link"><?php _e( 'Why are the image attributes not updated?', 'woo-image-seo' ); ?></a>
		</span>
	</span>

	<p class="description hidden" id="wis-media-library-help-content">
		<?php
			_e( 'Woo Image SEO will not change the image attributes permanently. Instead, they are generated dynamically each time an image is displayed. Your image will have different alt and title attributes depending on the product that is using it.', 'woo-image-seo' )
		?>
		<br>
		<?php _e( 'For example, let\'s imagine that we have one image used for two different products - "Product 1" and "Product 2". If we visit the page of "Product 1", the image will have an alt attribute of "Product 1". If we open the page of "Product 2", then the image will have an alt attribute of "Product 2".', 'woo-image-seo' ) ?>
		<br>
		<?php _e( 'If you disable the plugin, the automatic attributes will disappear.', 'woo-image-seo' ) ?>
	</p>
</script>

<script>
    jQuery(window).on('load', function() {
        if (wp && wp.media && wp.media.frame) {
            wp.media.frame.on('edit:attachment', function() {
                if (jQuery('.media-modal-content .attachment-info .settings').length) {
                    jQuery('.media-modal-content .attachment-info .settings').prepend(jQuery('#wis-custom-gallery-markup').html());
                }
            });
        }

        jQuery('body').on('click', 'a[href="#wis-media-library-help-link"]', function(event) {
        	event.preventDefault();
        	jQuery('#wis-media-library-help-content').slideToggle();
        });
    });
</script>
