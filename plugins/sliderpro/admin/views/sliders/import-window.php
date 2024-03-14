<div class="modal-overlay"></div>
<div class="modal-window-container import-window">
	<div class="modal-window">
		<span class="close-x"></span>
		
		<textarea></textarea>

		<div class="buttons sp-clearfix">
			<a class="button-secondary save" href="#"><?php _e( 'Import', 'sliderpro' ); ?></a>
		</div>
		
		<?php
            $hide_info = get_option( 'sliderpro_hide_inline_info' );

            if ( $hide_info != true ) {
        ?>
				<div class="inline-info import-info">
		            <input type="checkbox" id="show-hide-info" class="show-hide-info">
		            <label for="show-hide-info" class="show-info"><?php _e( 'Show info', 'sliderpro' ); ?></label>
		            <label for="show-hide-info" class="hide-info"><?php _e( 'Hide info', 'sliderpro' ); ?></label>
		            
		            <div class="info-content">
		                <p><?php _e( 'In the field above you need to insert the new slider\'s data, as it was exported. Then, click the <i>Import</i> button. If you want to import a slider from a plugin version older than 4.0, you need to insert the exported XML content.', 'sliderpro' ); ?></p>
		            	<p><a href="https://bqworks.net/slider-pro/screencasts/#import-export" target="_blank"><?php _e( 'See the video tutorial', 'sliderpro' ); ?> &rarr;</a></p>
		            </div>
		        </div>
		<?php
            }
        ?>
	</div>
</div>