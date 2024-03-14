<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>
					<?php echo __('Step', 'wpsaf_security').' 1 : '.$extensionTitle.__(' Wizard', 'wpsaf_security'); ?>
				</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div  style="max-width: 600px;">
					<p>
						<?php _e("Make sure that you have enabled and configured all modules of the S.A.F. to protect your website from hackers, spammer, viruses and  malicious code most effective way!", 'wpsaf_security'); ?>
					</p>
					<p>
						<?php _e("Activate core S.A.F. security tools", 'wpsaf_security'); ?>: <strong><?php _e("Antivirus, Firewall (Network Monitor), Brute Force Monitor, Security Report, 404 Detection,  Easy Password", 'wpsaf_security'); ?></strong>
					</p>
					<p class="text-center">
						<br/>
						<button class="btn btn-info btn-lg"  data-action="action=wptsaf_security&extension=wptsaf-security&method=wizardStepOne">
							<?php _e('Activate 6 Modules', 'wpsaf_security'); ?>
						</button>
					</p>
				</div>
				<div class="clear"></div>

				<div class="ln_solid"></div>
				<div class="buttons">
					<button class="btn btn-default pull-right"  data-action="action=wptsaf_security&extension=wptsaf-security&method=hideWizard&args[type]=1">
						<?php _e( "Close", 'wpsaf_security'); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
