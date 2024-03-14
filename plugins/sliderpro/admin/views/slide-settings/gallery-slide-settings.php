<tr>
	<td colspan="2">
		<?php
            $hide_info = get_option( 'sliderpro_hide_inline_info' );

            if ( $hide_info != true ) {
        ?>
            <div class="inline-info slide-settings-info">
                <input type="checkbox" id="show-hide-info" class="show-hide-info">
                <label for="show-hide-info" class="show-info"><?php _e( 'Show info', 'sliderpro' ); ?></label>
                <label for="show-hide-info" class="hide-info"><?php _e( 'Hide info', 'sliderpro' ); ?></label>
                
                <div class="info-content">
                    <p><?php _e( 'One <i>Gallery</i> slide in the admin area will dynamically generate multiple slides in the published slider (one slide for each image from the <i>[gallery]</i> shortcode).', 'sliderpro' ); ?></p>
                    <p><?php _e( 'You just need to drop the slider shortcode in a post that contains a <i>[gallery]</i> shortcode, and the images from the <i>[gallery]</i> will automatically be loaded in the slider. Then, if you want to hide the original gallery, you can add the <i>hide</i> attribute to the <i>[gallery]</i> shortcode: <i>[gallery ids="1,2,3" hide="true"]</i>.', 'sliderpro' ); ?></p>
                    <p><?php _e( 'The images and their data can be fetched through <i>dynamic tags</i>, which are enumerated in the Main Image, Layers and HTML editors.', 'sliderpro' ); ?></p>
                    <p><a href="https://bqworks.net/slider-pro/screencasts/#slider-from-gallery" target="_blank"><?php _e( 'See the video tutorial', 'sliderpro' ); ?> &rarr;</a></p>
                </div>
            </div>
        <?php
            }
        ?>
	</td>
</tr>