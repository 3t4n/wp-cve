<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>
					<?php echo __('Step', 'wpsaf_security').' 2 : '.$extensionTitle.__(' Wizard', 'wpsaf_security'); ?>
				</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div>
					<h3 class="text-center">
						<i class="fa fa-check-square" style="color:green;" />
                      	<?php _e('Core modules successfully activated!', 'wpsaf_security'); ?>
                    </h3>
					
					<br/>
					<p>
						<?php _e("Follow instructions to configure and activate Cloud Antivirus Monitor Module", 'wpsaf_security'); ?>
					</p>
					<p  class="text-center">
						<button class="btn btn-info btn-lg"  data-action="action=wptsaf_security&extension=malware-scanner&method=settings">
							<?php _e('Configure and Activate', 'wpsaf_security'); ?>
						</button>					
					</p>
				</div>

				<div class="clear"></div>

				<div class="ln_solid"></div>
				<div class="buttons">
					<button class="btn btn-default pull-left"  data-action="action=wptsaf_security&extension=wptsaf-security&method=hideWizard">
						<?php _e( "Close Permanently", 'wpsaf_security'); ?>
					</button>
					
					<button class="btn btn-default pull-right btn-popup-close">
						<?php _e('Close', 'wpsaf_security'); ?>
					</button>

					<button class="btn btn-default pull-right"  data-action="action=wptsaf_security&extension=wptsaf-security&method=wizardStepTwo">
						<?php _e('Skip', 'wpsaf_security'); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
