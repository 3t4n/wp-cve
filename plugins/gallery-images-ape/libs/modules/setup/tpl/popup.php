<?php 
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */
?>
<form class="ape_gallery_setup ape_gallery_setup-deactivation-feedback no-confirmation-message">
	<div class="ape_gallery_setup-dialog">
		
		<div class="ape_gallery_setup-header">
			<h4><?php _e('Quick feedback','gallery-images-ape'); ?></h4>
		</div>
		
		<div class="ape_gallery_setup-body">
			<div class="ape_gallery_setup-panel" data-panel-id="confirm"><p></p></div>
			
			<div class="ape_gallery_setup-panel active" data-panel-id="reasons">
				<h3><strong><?php _e('If you have a moment, please let us know why you are deactivating:','gallery-images-ape'); ?></strong></h3>
				
				<ul id="reasons-list">
				
					<li class="reason has-input" data-input-type="textarea">
						<label>
							<span>
								<input name="check" value="1" type="radio">
							</span>
							<span><?php _e('The plugin suddenly stopped working','gallery-images-ape'); ?></span>
						</label>
						<div class="internal-message"></div>
					</li>
					
					<li class="reason has-input" data-input-type="textfield">
						<label>
							<span>
								<input name="check" value="2" type="radio">
							</span>
							<span><?php _e('I found a better plugin','gallery-images-ape'); ?></span>
						</label>
						<div class="internal-message"><input type="text" name="ape_gallery_setup-msg-better-plugin" placeholder="<?php _e('What\'s the plugin\'s name?','gallery-images-ape'); ?>" /></div>
					</li>
					
					<li class="reason has-input" data-input-type="textarea">
						<label>
							<span>
								<input name="check" value="3" type="radio">
							</span>
							<span><?php _e('I only needed the plugin for a short period','gallery-images-ape'); ?></span>
						</label>
						<div class="internal-message"></div>
					</li>
					
					<li class="reason has-input" data-input-type="textarea">
						<label>
							<span>
								<input name="check" value="4" type="radio">
							</span>
							<span><?php _e('The plugin broke my site','gallery-images-ape'); ?></span>
						</label>
						<div class="internal-message"></div>
					</li>
					
					<li class="reason" data-input-type="">
						<label>
							<span>
								<input name="check" value="5" type="radio">
							</span>
							<span><?php _e('I no longer need the plugin','gallery-images-ape'); ?></span>
						</label>
						<div class="internal-message"></div>
					</li>
					
					<li class="reason has-input" data-input-type="textarea">
						<label>
							<span>
								<input name="check" value="6" type="radio">
							</span>
							<span><?php _e('It\'s a temporary deactivation. I\'am just debugging an issue.','gallery-images-ape'); ?></span>
						</label>
						<div class="internal-message"></div>
					</li>

					<li class="reason has-input" data-input-type="textfield">
						<label>
							<span>
								<input name="check" value="7" type="radio">
							</span>
							<span><?php _e('Other'); ?></span>
						</label>
						<div class="internal-message"><input type="text" name="ape_gallery_setup-msg-other" /></div>
					</li>
				
				</ul>
				
			</div>
		</div>
		
		<div class="ape_gallery_setup-footer">
			<button type="button" class="button button-secondary button-deactivate allow-deactivate"><?php _e('Skip & Deactivate'); ?></button>
			<button type="button" class="button button-primary button-close"><?php _e('Cancel'); ?></button>
		</div>
		
	</div>
</form>