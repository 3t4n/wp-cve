<div class="wrap sfif-page">
	
	<h2><?php _e('Set All First Images As Featured', 'sfif_domain'); ?></h2>
	<span class="note"><?php echo 'version ' . SFIF_PLUGIN_VERSION; ?></span>
	
	<div id="alert"></div>
	
	<form method="post" name="sfif" action="<?php bloginfo('wpurl') ?>/wp-admin/admin-ajax.php">
				
				<?php settings_fields( 'sfif_settings_group' ); ?>
				
				<p><?php _e('This plugin will search for all the first images of your <b>published</b> posts or pages and set them as the featured image.', 'sfif_domain'); ?></p>
				
				
					<!-- ** Enable buttons ***************** -->
						
					<h3><?php _e('Options', 'sfif_domain'); ?></h3>
					<h4><?php _e('Please make sure to make a backup of your metapost table before running the plugin.', 'sfif_domain'); ?></h4>		
						
						
						<p>
							
							<label class="description" for="post_type"><?php _e( 'Run For:', 'sfif_domain'); ?></label>
							<select id="post_type" name="sfif_settings[post_type]">
								
								<?php 	
									
									$post_types = get_post_types( '' ,'object');
									$exclude = array('attachment', 'revision', 'nav_menu_item');
									$type_labels = array();
									
		  							foreach ( $post_types as $post_type ) {
										
										if( in_array($post_type->name, $exclude) ) continue;
										if( in_array($post_type->labels->name, $type_labels) ) continue;
										 
										array_push($type_labels, $post_type->labels->name); 
										
										echo '<option value="' . $post_type->name . '" ' . selected($post_type->name, $options['run_for']) . '>';
										echo $post_type->labels->name;
										echo '</option>';
			
									}
								 ?>
								
							</select>
							
						</p>
						
						<p>
							<label class="description" for="post_date_from"><?php _e( 'From:', 'sfif_domain'); ?></label>
							<select id="post_date_from" name="sfif_settings[post_date_from]">
								<option value=""></option>
							<?php 
								
								foreach( $available_dates as $date ) {
										
									$date = new DateTime($date->post_date);
									
									echo '<option value="' . $date->format('Y-m-01') . '">';
									echo $date->format('Y ' . __('F', 'sfif_domain'));
									echo '</option>';

								}
							?>
							</select>
						
							<label class="description" for="post_date_to"><?php _e( 'To:', 'sfif_domain'); ?></label>
							<select id="post_date_to" name="sfif_settings[post_date_to]">
								<option value=""></option>
							<?php 
								
								foreach( $available_dates as $date ) {
										
									$date = new DateTime($date->post_date);
									
									echo '<option value="' . $date->format('Y-m-31') . '">';
									echo $date->format('Y ' . __('F', 'sfif_domain'));
									echo '</option>';

								}
							?>
							</select>
						
						
						</p>
						
						<p>
							<input type="checkbox" id="overwrite" name="sfif_settings[overwrite]" value="1" <?php checked(1, $options['overwrite'] ) ?> />
							<label class="description" for="overwrite"><?php _e( 'Overwrite thumbnails', 'sfif_domain'); ?></label><br/>
							<span class="tip"><?php _e('If enabled the first image found in the post will overwrite the already selected featured image.', 'sfif_domain')?></span>
						</p>
						
						<?php wp_nonce_field('update_featured', 'token') ?>
						<input type="hidden" name="action_update" value="update_featured" / >
					
						<span id="error_alert" class="error"></span>
					
						<div class="submit">
							<input type="submit" onclick="return false;" class="button-primary" value="<?php _e('Start', 'sfif_domain'); ?>" />
							<div class="loading"><img src="<?php echo SFIF_PLUGIN_URL . '/includes/images/ajax-loader-xs.gif' ?>" alt="loading" /></div>
							<div class="clearfix"></div>
						</div>
				
			</form>	
			
			<div id="activity"></div>
	
</div>