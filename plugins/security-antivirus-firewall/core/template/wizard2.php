<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>
					<?php echo __('Final Step', 'wpsaf_security').': '.$extensionTitle.__(' Wizard', 'wpsaf_security'); ?>
				</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div  style="max-width: 600px;">
					<h3 class="text-center">
						<?php _e("Congratulations!", 'wpsaf_security'); ?>
					</h3>
					<h3 class="text-center">
						<?php _e("You finished basic configuration of the plugin!", 'wpsaf_security'); ?>
					</h3>
					<p>
						<br/>
						<strong>
							<?php _e("Useful Tips:", 'wpsaf_security'); ?>
						</strong>
						<br/><br/>
						<?php _e("In Antivirus section you can find two modules for local antivirus and online files check with cloud antivirus service. Please follow guide in Antivirus settings section to configure and enable this futures.", 'wpsaf_security'); ?>
						<br/><br/>
						<?php _e("Pay attention to Google reCaptcha module. You can enable and configure it in S.A.F. modules section. When you enable Google reCaptcha make sure that you define google key in settings section.", 'wpsaf_security'); ?>
					</p>
				</div>

				<div class="clear"></div>

				<div class="ln_solid"></div>
				<div class="buttons">
					<button class="btn btn-default pull-right" data-action="action=wptsaf_security&extension=wptsaf-security&method=hideWizard">
						<?php _e('Finish', 'wpsaf_security'); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
