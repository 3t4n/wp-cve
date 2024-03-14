<div class="modal-overlay"></div>
<div class="modal-window-container caption-editor">
	<div class="modal-window">
		<span class="close-x"></span>

		<textarea><?php echo isset( $caption_content ) ? esc_textarea( stripslashes( $caption_content ) ) : ''; ?></textarea>

		<?php
            $hide_info = get_option( 'sliderpro_hide_inline_info' );

            if ( $hide_info != true ) {
        ?>
            <div class="inline-info caption-editor-info">
            	<input type="checkbox" id="show-hide-info" class="show-hide-info">
				<label for="show-hide-info" class="show-info"><?php _e( 'Show info', 'sliderpro' ); ?></label>
				<label for="show-hide-info" class="hide-info"><?php _e( 'Hide info', 'sliderpro' ); ?></label>
				
				<div class="info-content">
	                <p><?php _e( 'In the field above you can add a caption for the slide.', 'sliderpro' ); ?></p>

					<?php
						if ( $content_type === 'posts' || $content_type === 'gallery' || $content_type === 'flickr' ) {
					?>
						<input type="checkbox" id="show-hide-dynamic-tags" class="show-hide-dynamic-tags">
						<label for="show-hide-dynamic-tags" class="show-dynamic-tags"><?php _e( 'Show dynamic tags', 'sliderpro' ); ?></label>
						<label for="show-hide-dynamic-tags" class="hide-dynamic-tags"><?php _e( 'Hide dynamic tags', 'sliderpro' ); ?></label>
					<?php
						}

						if ( $content_type === 'posts' ) {
					?>
							<table class="dynamic-tags">
								<tbody>
									<tr>
										<td><b>[sp_image]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The post\'s featured image, as an <i>img</i> HTML element. It accepts an optional parameter to specify the size of the image: [sp_image.thumbnail]. Accepted sizes are: <i>full</i>, <i>large</i>, <i>medium</i>, <i>thumbnail</i>. The default value is <i>full</i>.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_image_src]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The URL of the post\'s featured image. It accepts an optional parameter to specify the size of the image: [sp_image_src.thumbnail]. Accepted sizes are: <i>full</i>, <i>large</i>, <i>medium</i>, <i>thumbnail</i>. The default value is <i>full</i>.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_image_alt]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The <i>alt</i> text of the post\'s featured image.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_image_title]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The title of the post\'s featured image.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_image_description]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The description of the post\'s featured image.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_image_caption]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The caption of the post\'s featured image.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_title]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The post\'s title.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_link]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The post\'s link, as an <i>anchor</i> HTML element, with the post\'s title as the text of the link.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_link_url]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The post\'s link.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_date]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The post\'s date.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_excerpt]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The post\'s excerpt.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_content]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The post\'s content.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_category]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The first category that the post is assigned to.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_custom.<i>name</i>]</b></td>
										<td> - </td>
										<td><p><?php _e( 'Returns the value from a custom field. The <i>name</i> parameter indicates the name of the custom field.', 'sliderpro' ); ?></p></td>
									</tr>
								</tbody>
							</table>
	            	<?php
	            		} else if ( $content_type === 'gallery' ) {
	            	?>
	            			<table class="dynamic-tags">
								<tbody>
									<tr>
										<td><b>[sp_image]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The gallery image, as an <i>img</i> HTML element. It accepts an optional parameter to specify the size of the image: [sp_image.thumbnail]. Accepted sizes are: <i>full</i>, <i>large</i>, <i>medium</i>, <i>thumbnail</i>. The default value is <i>full</i>.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_image_src]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The URL of the gallery image. It accepts an optional parameter to specify the size of the image: [sp_image_src.thumbnail]. Accepted sizes are: <i>full</i>, <i>large</i>, <i>medium</i>, <i>thumbnail</i>. The default value is <i>full</i>.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_image_alt]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The <i>alt</i> text of the gallery image.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_image_title]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The title of the gallery image.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_image_description]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The description of the gallery image.', 'sliderpro' ); ?></p></td>
									</tr>
								</tbody>
							</table>
	            	<?php
	            		} else if ( $content_type === 'flickr' ) {
	            	?>
	            			<table class="dynamic-tags">
								<tbody>
									<tr>
										<td><b>[sp_image]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The Flickr image, as an <i>img</i> HTML element. It accepts an optional parameter to specify the size of the image: [sp_image.thumbnail]. Accepted sizes are: <i>full</i>, <i>large</i>, <i>medium</i>, <i>thumbnail</i>. The default value is <i>full</i>.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_image_src]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The URL of the Flickr image. It accepts an optional parameter to specify the size of the image: [sp_image_src.thumbnail]. Accepted sizes are: <i>square</i>, <i>thumbnail</i>, <i>small</i>, <i>medium</i>, <i>medium_640</i>, <i>large</i>. The default value is <i>medium</i>.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_image_description]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The description of the Flickr image.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_image_link]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The link of the Flickr image.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_date]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The date of the Flickr image.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_username]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The username of the image\'s owner.', 'sliderpro' ); ?></p></td>
									</tr>
									<tr>
										<td><b>[sp_user_link]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The link to the profile of the image\'s owner.', 'sliderpro' ); ?></p></td>
									</tr>
								</tbody>
							</table>
	            	<?php
	            		}
	            	?>
	            </div>
            </div>
        <?php
            }
        ?>
	</div>
</div>