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
                    <p><?php _e( '<i>Custom Content</i> slides allow you to manually specify the image(s), link and all the other data for the slide.', 'sliderpro' ); ?></p>
                </div>
            </div>
        <?php
            }
        ?>
	</td>
</tr>