<div class="modal-overlay"></div>
<div class="modal-window-container export-window">
	<div class="modal-window">
		<span class="close-x"></span>
		
		<textarea><?php echo isset( $export_string ) ? esc_textarea( $export_string ) : ''; ?></textarea>
        
        <?php
            $hide_info = get_option( 'sliderpro_hide_inline_info' );

            if ( $hide_info != true ) {
        ?>
        		<div class="inline-info export-info">
                    <input type="checkbox" id="show-hide-info" class="show-hide-info">
                    <label for="show-hide-info" class="show-info"><?php _e( 'Show info', 'sliderpro' ); ?></label>
                    <label for="show-hide-info" class="hide-info"><?php _e( 'Hide info', 'sliderpro' ); ?></label>
                    
                    <div class="info-content">
                        <p><?php _e( 'The text above represents the data of the slider. Please copy the text and then paste it in the import slider window, by clicking on the <i>Import Slider</i> button in the <i>Slider Pro</i> installation where you want to import the slider.', 'sliderpro' ); ?></p>
                        <p><a href="https://bqworks.net/slider-pro/screencasts/#import-export" target="_blank"><?php _e( 'See the video tutorial', 'sliderpro' ); ?> &rarr;</a></p>
                    </div>
                </div>
        <?php
            }
        ?>
	</div>
</div>