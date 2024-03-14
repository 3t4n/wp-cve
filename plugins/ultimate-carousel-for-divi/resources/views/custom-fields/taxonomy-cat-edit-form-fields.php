<?php if ($taxonomy != 'product_cat'): ?>
		<tr class="form-field term-thumbnail-wrap">
			<th scope="row" valign="top"><label><?php esc_html_e('Thumbnail', 'ultimate-carousel-for-divi');?></label></th>
			<td>
				<div id="wpt_tax_term_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url($image); ?>" width="60px" height="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="wpt_tax_term_thumbnail_id" name="wpt_tax_term_thumbnail_id" value="<?php echo esc_attr($thumbnail_id); ?>" />
					<button type="button" class="upload_image_button button"><?php esc_html_e('Upload/Add image', 'ultimate-carousel-for-divi');?></button>
					<button type="button" class="remove_image_button button"><?php esc_html_e('Remove image', 'ultimate-carousel-for-divi');?></button>
				</div>
				<script type="text/javascript">

					// Only show the "remove image" button when needed
					if ( '0' === jQuery( '#wpt_tax_term_thumbnail_id' ).val() ) {
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

				</script>
				<div class="clear"></div>
			</td>
		</tr>

		<?php endif;?>

		<tr class="form-field term-thumbnail-wrap">
			<th scope="row" valign="top"><label><?php esc_html_e('Order', 'ultimate-carousel-for-divi');?></label></th>
			<td>
				<input type="number" id="wpt_taxonomy_term_order" name="wpt_taxonomy_term_order" class='medium' value="<?php echo esc_attr($term_order); ?>">
			</td>
		</tr>