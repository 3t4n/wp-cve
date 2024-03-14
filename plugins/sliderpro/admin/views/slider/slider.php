<div class="wrap sliderpro-admin">
	<h2><?php echo isset( $_GET['action'] ) && $_GET['action'] === 'edit' ? __( 'Edit Slider', 'sliderpro' ) : __( 'Add New Slider', 'sliderpro' ); ?></h2>

	<form action="" method="post">
    	<div class="metabox-holder has-right-sidebar">
            <div class="editor-wrapper">
                <div class="editor-body">
                    <div id="titlediv">
                    	<input name="name" id="title" type="text" value="<?php echo esc_attr( $slider_name ); ?>" />
                    </div>
					
                    <?php
                        if ( get_option( 'sliderpro_hide_image_size_warning' ) != true ) {
                    ?>
                            <div class="image-size-warning">
                                <p><?php _e( 'Some of the main slide images are smaller than the size of the slide (determined by the <i>Width</i> and <i>Height</i> options), so they might appear blurred when viewed in the slider.', 'sliderpro' ); ?></p>
                                <p><?php _e( 'When you select images to insert them into the slider, you can set their size from the right column of the Media Library window, as you can see in <a href="https://bqworks.net/slider-pro/screencasts/#simple-slider" target="_blank">this video</a> at 0:05.', 'sliderpro' ); ?></p>
                                <a href="#" class="image-size-warning-close"><?php _e( 'Don\'t show this again.', 'sliderpro' ); ?></a>
                            </div>
                    <?php
                        }
                    ?>

					<div class="slides-container">
                    	<?php
                    		if ( isset( $slides ) ) {
                    			if ( $slides !== false ) {
                    				foreach ( $slides as $slide ) {
                    					$this->create_slide( $slide );
                    				}
                    			}
                    		} else {
                    			$this->create_slide( false );
                    		}
	                    ?>
                    </div>

                    <div class="add-slide-group">
                        <a class="button add-slide" href="#"><?php _e( 'Add Slides', 'sliderpro' ); ?> <span class="add-slide-arrow">&#9660</span></a>
                        <ul class="slide-type">
                            <li><a href="#" data-type="image"><?php _e( 'Image Slides', 'sliderpro' ); ?></a></li>
                            <li><a href="#" data-type="posts"><?php _e( 'Posts Slides', 'sliderpro' ); ?></a></li>
                            <li><a href="#" data-type="gallery"><?php _e( 'Gallery Slides', 'sliderpro' ); ?></a></li>
                            <li><a href="#" data-type="flickr"><?php _e( 'Flickr Slides', 'sliderpro' ); ?></a></li>
                            <li><a href="#" data-type="empty"><?php _e( 'Empty Slide', 'sliderpro' ); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="inner-sidebar meta-box-sortables ui-sortable">
				<div class="postbox action">
					<div class="inside">
						<input type="submit" name="submit" class="button-primary" value="<?php echo isset( $_GET['action'] ) && $_GET['action'] === 'edit' ? __( 'Update', 'sliderpro' ) : __( 'Create', 'sliderpro' ); ?>" />
                        <span class="spinner update-spinner"></span>
						<a class="button preview-slider" href="#"><?php _e( 'Preview', 'sliderpro' ); ?></a>
                        <span class="spinner preview-spinner"></span>
					</div>
				</div>
                
                <div class="sidebar-settings">
                    <?php
                        $settings_panels = BQW_SliderPro_Settings::getSliderSettingsPanels();
                        $default_panels_state = BQW_SliderPro_Settings::getPanelsState();

                        foreach ( $settings_panels as $panel_name => $panel ) {
                            $panel_state_class = isset( $panels_state ) && isset( $panels_state[ $panel_name ] ) ? $panels_state[ $panel_name ] : ( isset( $default_panels_state[ $panel_name ] ) ? $default_panels_state[ $panel_name ] : 'closed' );
                    ?>
                            <div class="postbox <?php echo $panel_name; ?>-panel <?php echo $panel_state_class; ?>" data-name="<?php echo $panel_name; ?>">
                                <div class="handlediv"></div>
                                <h3 class="hndle"><?php echo $panel['label']; ?></h3>
                                <div class="inside">
                                    <?php  include( $panel['renderer'] ); ?>
                                </div>
                            </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
	</form>
</div>