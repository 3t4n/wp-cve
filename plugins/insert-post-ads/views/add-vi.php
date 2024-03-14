<div class="wrap">
    <h2><?php echo $this->plugin->displayName; ?> &raquo; <?php _e( 'Add vi', $this->plugin->name ); ?></h2>

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
		                    <?php insert_ads_vi_plugin_card(); 
							//insert_ads_inpostads_form_get_content('above');
							//insert_ads_vi_customize_adcode_form_get_content();
							?>
		            </div>
					<div class="postbox">
		                    <?php 
							if(get_transient('insert_ads_vi_api_authetication_token')){
								insert_ads_vi_customize_adcode_form_get_content();
							}
							?>
		            </div>
	                
					
					<div class="postbox vi-choose">
		                    <?php 
							if(get_transient('insert_ads_vi_api_authetication_token')){
							insert_ads_inpostads_plugin_card();
							//insert_ads_inpostads_form_get_content('above');
							//insert_ads_inpostads_form_get_content('middle');
							}
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