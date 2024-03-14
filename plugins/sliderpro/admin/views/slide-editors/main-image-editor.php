<div class="modal-overlay"></div>
<div class="modal-window-container main-image-editor <?php echo $content_class;?>">
	<div class="modal-window">
		<span class="close-x"></span>
		<div class="fieldset main-image">
			<h3 class="heading"><?php _e( 'Image', 'sliderpro' ); ?><span class="clear-fieldset"><?php _e( 'Clear', 'sliderpro' ); ?></span></h3>
			<div class="image-loader">
				<?php
					if ( isset( $data['main_image_source'] ) && $data['main_image_source'] !== '' ) {
						echo '<img src="' . esc_url( $data['main_image_source'] ) . '" />';
					} else {
						echo '<p class="no-image">' . __( 'Click to add image', 'sliderpro' ) . '</p>';
					}
				?>
			</div>
			<table>
				<tbody>
					<tr>
						<td><label for="main-image-source"><?php _e( 'Source:', 'sliderpro' ); ?></label></td>
						<td><input id="main-image-source" class="field" type="text" name="main_image_source" value="<?php echo isset( $data['main_image_source'] ) ? esc_attr( $data['main_image_source'] ) : ''; ?>" /></td>
					</tr>
					<tr>
						<td><label for="main-image-alt"><?php _e( 'Alt:', 'sliderpro' ); ?></label></td>
						<td><input id="main-image-alt" class="field" type="text" name="main_image_alt" value="<?php echo isset( $data['main_image_alt'] ) ? esc_attr( $data['main_image_alt'] ) : ''; ?>" /></td>
					</tr>
					<tr>
						<td><label for="main-image-title"><?php _e( 'Title:', 'sliderpro' ); ?></label></td>
						<td><input id="main-image-title" class="field" type="text" name="main_image_title" value="<?php echo isset( $data['main_image_title'] ) ? esc_attr( $data['main_image_title'] ) : ''; ?>" /></td>
					</tr>
					<tr>
						<td><label for="main-image-retina-source"><?php _e( 'Retina Source:', 'sliderpro' ); ?></label></td>
						<td><input id="main-image-retina-source" class="field" type="text" name="main_image_retina_source" value="<?php echo isset( $data['main_image_retina_source'] ) ? esc_attr( $data['main_image_retina_source'] ) : ''; ?>" /><span class="additional-image-loader"></span></td>
					</tr>
				</tbody>
			</table>

			<input type="checkbox" id="show-hide-additional-images" class="show-hide-additional-images">
			<label for="show-hide-additional-images" class="show-additional-images">Show additional images</label>
			<label for="show-hide-additional-images" class="hide-additional-images">Hide additional images</label>

			<table class="additional-images">
				<tbody>
					<tr>
						<td><label for="main-image-small-source"><?php _e( 'Small:', 'sliderpro' ); ?></label></td>
						<td><input id="main-image-small-source" class="field" type="text" name="main_image_small_source" value="<?php echo isset( $data['main_image_small_source'] ) ? esc_attr( $data['main_image_small_source'] ) : ''; ?>" /><span class="additional-image-loader"></span></td>
					</tr>
					<tr>
						<td><label for="main-image-medium-source"><?php _e( 'Medium:', 'sliderpro' ); ?></label></td>
						<td><input id="main-image-medium-source" class="field" type="text" name="main_image_medium_source" value="<?php echo isset( $data['main_image_medium_source'] ) ? esc_attr( $data['main_image_medium_source'] ) : ''; ?>" /><span class="additional-image-loader"></span></td>
					</tr>
					<tr>
						<td><label for="main-image-large-source"><?php _e( 'Large:', 'sliderpro' ); ?></label></td>
						<td><input id="main-image-large-source" class="field" type="text" name="main_image_large_source" value="<?php echo isset( $data['main_image_large_source'] ) ? esc_attr( $data['main_image_large_source'] ) : ''; ?>" /><span class="additional-image-loader"></span></td>
					</tr>
					<tr>
						<td><label for="main-image-retina-small-source"><?php _e( 'Retina Small:', 'sliderpro' ); ?></label></td>
						<td><input id="main-image-retina-small-source" class="field" type="text" name="main_image_retina_small_source" value="<?php echo isset( $data['main_image_retina_small_source'] ) ? esc_attr( $data['main_image_retina_small_source'] ) : ''; ?>" /><span class="additional-image-loader"></span></td>
					</tr>
					<tr>
						<td><label for="main-image-retina-medium-source"><?php _e( 'Retina Medium:', 'sliderpro' ); ?></label></td>
						<td><input id="main-image-retina-medium-source" class="field" type="text" name="main_image_retina_medium_source" value="<?php echo isset( $data['main_image_retina_medium_source'] ) ? esc_attr( $data['main_image_retina_medium_source'] ) : ''; ?>" /><span class="additional-image-loader"></span></td>
					</tr>
					<tr>
						<td><label for="main-image-retina-large-source"><?php _e( 'Retina Large:', 'sliderpro' ); ?></label></td>
						<td><input id="main-image-retina-large-source" class="field" type="text" name="main_image_retina_large_source" value="<?php echo isset( $data['main_image_retina_large_source'] ) ? esc_attr( $data['main_image_retina_large_source'] ) : ''; ?>" /><span class="additional-image-loader"></span></td>
					</tr>
				</tbody>
			</table>
			
			<input class="field" type="hidden" name="main_image_id" value="<?php echo isset( $data['main_image_id'] ) ? esc_attr( $data['main_image_id'] ) : ''; ?>" />
			<input class="field" type="hidden" name="main_image_width" value="<?php echo isset( $data['main_image_width'] ) ? esc_attr( $data['main_image_width'] ) : ''; ?>" />
			<input class="field" type="hidden" name="main_image_height" value="<?php echo isset( $data['main_image_height'] ) ? esc_attr( $data['main_image_height'] ) : ''; ?>" />
		</div>

		<div class="fieldset link">
			<h3 class="heading"><?php _e( 'Link', 'sliderpro' ); ?><span class="clear-fieldset"><?php _e( 'Clear', 'sliderpro' ); ?></span></h3>
			<table>
				<tbody>
					<tr>
						<td><label for="main-image-link"><?php _e( 'URL:', 'sliderpro' ); ?></label></td>
						<td><input id="main-image-link" class="field" type="text" name="main_image_link" value="<?php echo isset( $data['main_image_link'] ) ?  esc_attr( $data['main_image_link'] ) : ''; ?>" /></td>
					</tr>
					<tr>
						<td><label for="main-image-link-title"><?php _e( 'Title:', 'sliderpro' ); ?></label></td>
						<td><input id="main-image-link-title" class="field" type="text" name="main_image_link_title" value="<?php echo isset( $data['main_image_link_title'] ) ? esc_attr( $data['main_image_link_title'] ) : ''; ?>" /></td>
					</tr>
				</tbody>
			</table>
		</div>

		<?php
            $hide_info = get_option( 'sliderpro_hide_inline_info' );

            if ( $hide_info != true ) {
        ?>
            <div class="inline-info main-image-editor-info">
                <input type="checkbox" id="show-hide-info" class="show-hide-info">
				<label for="show-hide-info" class="show-info"><?php _e( 'Show info', 'sliderpro' ); ?></label>
				<label for="show-hide-info" class="hide-info"><?php _e( 'Hide info', 'sliderpro' ); ?></label>
				
				<div class="info-content">
	                <p><?php _e( 'The <i>Image</i> field allows you to set the slide\'s main image.', 'sliderpro' ); ?></p>
	                <p><?php _e( 'If you click on the <i>Show additional images</i> link, you will have the possibility to specify different image sources that will be loaded in the place of the main image source when the slider is at a certain size.', 'sliderpro' ); ?></p>
	                <p><?php _e( 'The exact slider size at which these additional images will load can be set in the <i>Miscellaneous</i> sidebar panel.', 'sliderpro' ); ?></p>
	                <p><?php _e( 'The <i>Link</i> is optional and allows you to set a link for the slide\'s image.', 'sliderpro' ); ?></p>
					
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
										<td><b>[sp_link_url]</b></td>
										<td> - </td>
										<td><p><?php _e( 'The post\'s link.', 'sliderpro' ); ?></p></td>
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