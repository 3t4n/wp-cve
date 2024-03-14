<div class="wrap">
    <h2><?php echo $this->plugin->displayName; ?> &raquo; <?php _e( 'Settings', $this->plugin->name ); ?></h2>

    <?php
    if ( isset( $this->message ) ) {
        ?>
        <div class="updated fade"><p><?php echo $this->message; ?></p></div>
        <?php
    }
    if ( isset( $this->errorMessage ) ) {
        ?>
        <div class="error fade"><p><?php echo $this->errorMessage; ?></p></div>
        <?php
    }
    ?>

    <div id="poststuff">
    	<div id="post-body" class="metabox-holder">
    		<!-- Content -->
    		<div id="post-body-content">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox">
		                    <?php //insert_ads_vi_plugin_card(); 
							//insert_ads_inpostads_form_get_content('above');
							?>
		            </div>
	                <form action="edit.php?post_type=<?php echo $this->plugin->posttype; ?>&page=<?php echo $this->plugin->name; ?>" method="post" class="ins-settings">
		                <div class="postbox">
		                    <h3 class="hndle"><?php _e( 'Display Settings', $this->plugin->name ); ?></h3>

		                    <div class="inside">
		                    	<p>
									<?php
									$postTypes = get_post_types( array(
										'public' => true,
									), 'objects');
									if ( $postTypes ) {
										foreach ( $postTypes as $postType ) {
											// Skip attachments
											if ( $postType->name == 'attachment' ) {
												continue;
											}
											?>
											<label for="<?php echo $postType->name; ?>"><?php echo $postType->labels->name; ?></label>
											<input type="checkbox" name="<?php echo $this->plugin->name; ?>[<?php echo $postType->name; ?>]" value="1" id="<?php echo $postType->name; ?>" <?php echo ( isset( $this->settings[$postType->name] ) ? ' checked' : '' ); ?>/>
											<?php
										}
									}
									?>
								</p>
								<p style="margin-top:30px;">
									<label for="css"><?php _e( 'Exclude CSS', $this->plugin->name ) ;?></label>
									<input type="checkbox" name="<?php echo $this->plugin->name; ?>[css]" value="1" id="css" <?php echo ( isset( $this->settings['css'] ) ? ' checked' : '' ); ?>/>
								</p>
								<p class="description">
									<?php _e( 'By default, Post Ads are wrapped in a container that has some CSS to aid layout. Developers may wish to use their own CSS, and should check this Exclude CSS option.', $this->plugin->name ); ?>
								</p>
								<p>
									<input name="submit" type="submit" name="Submit" class="button button-primary" value="<?php _e( 'Save Settings', $this->plugin->name ); ?>" />
								</p>
		                    </div>
		                </div>
		                <!-- /postbox -->

		                
		                <!-- /postbox -->
		                <input type="hidden" name="_nonce" value= "<?php echo wp_create_nonce( $this->plugin->name . '-nonce' ); ?>" />
	                </form>
					
					<div class="postbox vi-choose">
		                    <?php //insert_ads_inpostads_plugin_card();
							//insert_ads_inpostads_form_get_content('above');
							?>
		            </div>
					
	                <!-- /postbox -->
				</div>
				<!-- /normal-sortables -->
    		</div>
    		<!-- /post-body-content -->

    		<!-- Sidebar -->

    		<!-- /postbox-container -->
    	</div>
	</div>
</div>