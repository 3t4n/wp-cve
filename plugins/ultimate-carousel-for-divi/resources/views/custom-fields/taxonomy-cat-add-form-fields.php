<?php if ($taxonomy != 'product_cat'): ?>
<div class="form-field term-thumbnail-wrap">
	<label><?php esc_html_e('Thumbnail', 'ultimate-carousel-for-divi');?></label>
	<div id="wpt_tax_term_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url($placeholder_img); ?>" width="60px" height="60px" /></div>
	<div style="line-height: 60px;">
		<input type="hidden" id="wpt_tax_term_thumbnail_id" name="wpt_tax_term_thumbnail_id" />
		<button type="button" class="upload_image_button button"><?php esc_html_e('Upload/Add image', 'ultimate-carousel-for-divi');?></button>
		<button type="button" class="remove_image_button button"><?php esc_html_e('Remove image', 'ultimate-carousel-for-divi');?></button>
	</div>
	<script type="text/javascript">

		// Only show the "remove image" button when needed
		if ( ! jQuery( '#wpt_tax_term_thumbnail_id' ).val() ) {
			jQuery( '.remove_image_button' ).hide();
		}

		// Uploading files
		var file_frame;

		jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

			event.preventDefault();

			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}

			// Create the media frame.
			file_frame = wp.media.frames.downloadable_file = wp.media({
				title: '<?php esc_html_e('Choose an image', 'ultimate-carousel-for-divi');?>',
				button: {
					text: '<?php esc_html_e('Use image', 'ultimate-carousel-for-divi');?>'
				},
				multiple: false
			});

			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
				var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
				var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

				jQuery( '#wpt_tax_term_thumbnail_id' ).val( attachment.id );
				jQuery( '#wpt_tax_term_thumbnail' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
				jQuery( '.remove_image_button' ).show();
			});

			// Finally, open the modal.
			file_frame.open();
		});

		jQuery( document ).on( 'click', '.remove_image_button', function() {
			jQuery( '#wpt_tax_term_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js($placeholder_img); ?>' );
			jQuery( '#wpt_tax_term_thumbnail_id' ).val( '' );
			jQuery( '.remove_image_button' ).hide();
			return false;
		});

		jQuery( document ).ajaxComplete( function( event, request, options ) {
			if ( request && 4 === request.readyState && 200 === request.status
				&& options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

				var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
			if ( ! res || res.errors ) {
				return;
			}
				// Clear Thumbnail fields on submit
				jQuery( '#wpt_tax_term_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js($placeholder_img); ?>' );
				jQuery( '#wpt_tax_term_thumbnail_id' ).val( '' );
				jQuery( '.remove_image_button' ).hide();
				return;
			}
		} );

	</script>
	<div class="clear"></div>
</div>

<?php endif;?>
<div class="form-field term-thumbnail-wrap" style='display:flex'>
	<label><?php esc_html_e('Order', 'ultimate-carousel-for-divi');?></label>
	<div id="wpt_tax_term_thumbnail" style="margin-left: 10px;">
		<input type="number" id="wpt_taxonomy_term_order" name="wpt_taxonomy_term_order" class='medium'>
	</div>
</div>
<p style='display:block; margin-bottom: 1em; margin-top: -3px;'>
<?php echo esc_html__('Enter numeric number. This field and it`s value can be used within the "Taxonomy Carousel" Divi Module for sorting.', 'ultimate-carousel-for-divi'); ?>
</p>