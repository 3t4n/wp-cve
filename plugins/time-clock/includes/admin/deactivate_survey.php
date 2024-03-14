<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function etimeclockwp_deactivate_survey() {
?>
	<div class="etimeclockwp-popup-overlay">
		<div class="etimeclockwp-serveypanel">
			<form action="#" method="post" id="etimeclockwp-deactivate-form">
				
				<div class="etimeclockwp-popup-header">
					<h2><?php echo __( 'Employee / Volunteer Time Clock Feedback', 'etimeclockwp' ); ?></h2>
				</div>
				
				<div class="etimeclockwp-popup-body">
					
					<h3><?php echo __( 'What made you deactivate?', 'etimeclockwp' ); ?></h3>
					
					<ul id="etimeclockwp-reason-list">
						
						<li class="etimeclockwp-reason has-input" data-input-type="textfield">
							<label>
							<span>
							<input type="radio" name="etimeclockwp-selected-reason" value="2">
							</span>
							<span><?php echo __( 'I found another plugin / platform.', 'etimeclockwp' ); ?></span>
							</label>
							<div class="etimeclockwp-internal-message"></div>
							<div class="etimeclockwp-reason-input"><textarea class="etimeclockwp_input_field_error" name="better_plugin" placeholder="What is the plugins / platforms name? What feature(s) made you move?"></textarea></div>
						</li>
						
						<li class="etimeclockwp-reason has-input" data-input-type="textfield">
							<label>
							<span>
							<input type="radio" name="etimeclockwp-selected-reason" value="1">
							</span>
							<span><?php echo __( 'The plugin was missing a feature.', 'etimeclockwp' ); ?></span>
							</label>
							<div class="etimeclockwp-internal-message"></div>
							<div class="etimeclockwp-reason-input"><textarea class="etimeclockwp_input_field_error" name="feature" placeholder="What feature(s) was this plugin missing?"></textarea></div>
						</li>
						
						<li class="etimeclockwp-reason has-input" data-input-type="textfield" >
							<label>
							<span>
							<input type="radio" name="etimeclockwp-selected-reason" value="7">
							</span>
							<span><?php echo __( 'Other', 'etimeclockwp' ); ?></span>
							</label>
							<div class="etimeclockwp-internal-message"></div>
							<div class="etimeclockwp-reason-input"><textarea class="etimeclockwp_input_field_error" name="other_reason" placeholder="How can we improve?"></textarea></div>
						</li>
						
					</ul>
					
				</div>
					
					<div class="etimeclockwp-popup-footer">
						<span class='etimeclockwp-popup-footer-explain'><?php echo __("Clicking 'Submit & Deactivate' will send your response, email, site URL, and plugin verson number to the plugin devleoper so that the plugin can be improved.",'etimeclockwp'); ?></span>
						<br /><br />
						<input type="button" class="button button-secondary button-skip loginpress-popup-skip-feedback" value="Skip &amp; Deactivate" >
						<div class="action-btns">
						<span class="etimeclockwp-spinner"><img src="<?php echo admin_url( '/images/spinner.gif' ); ?>" alt=""></span>
						<input type="submit" class="button button-secondary button-deactivate etimeclockwp-popup-allow-deactivate" value="Submit &amp; Deactivate" disabled="disabled">
						<a href="#" class="button button-primary etimeclockwp-popup-button-close"><?php echo __( 'Cancel', 'etimeclockwp' ); ?></a>
					</div>
					
				</div>
				
			</form>
		</div>
	</div>
	<?php
}