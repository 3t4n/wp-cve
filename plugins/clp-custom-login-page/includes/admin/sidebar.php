<div class="clp-sidebar-wrapper">

	<div class="donate widget">

		<a href="https://niteothemes.com" target="_blank"><img src="<?php echo CLP_PLUGIN_PATH .'assets/img/niteothemes.svg';?>" alt="Niteo Logo" class="niteo-logo"></a>
		<p style="margin-top:0">
			<img src="<?php echo CLP_PLUGIN_PATH .'assets/img/alex.jpg';?>" alt="Alex, NiteoThemes">
			<img src="<?php echo CLP_PLUGIN_PATH .'assets/img/paul.jpg';?>" alt="Paul, NiteoThemes">
		</p>

		<p><?php echo sprintf(__('If you love CLP plugin you can donate %s by clicking the Donate button below to support further development.', 'clp-custom-login'), '<span class="dashicons dashicons-money-alt"></span>');?> <span class="dashicons dashicons-smiley"></span></p>

		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<!-- <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"> -->
		</form>

		<p><?php echo sprintf(__('Follow us on %s.', 'clp-custom-login'), '<a href="https://twitter.com/niteothemes" target="_blank">Twitter</a>');?></p>
	</div>

	<div class="clp-widget widget" style="padding: 0;">
			<br>
		<a href="https://customloginpage.com/" target="_blank" class="button button-primary">Get PRO version!</a>
		<p style="padding: 0 2em"><?php _e('Packed with great features to secure and enhance your login page, including new customization options and templates!', 'clp-custom-login');?></p>
	</div>

	<div class="clp-widget widget" style="padding: 0;">
		<h3><?php _e('Check out our other plugins!', 'clp-custom-login');?></h3>
		<a href="https://wordpress.org/plugins/cmp-coming-soon-maintenance/" target="_blank"><img src="<?php echo CLP_PLUGIN_PATH .'assets/img/cmp-banner.png';?>" alt="CMP - Coming Soon and Maintenance" style="max-width:100%;vertical-align:top"></a>
		<p style="padding: 0 2em"><a href="https://wordpress.org/plugins/cmp-coming-soon-maintenance/" target="_blank" style="text-decoration:none;font-weight:bold;">CMP - Coming Soon and Maintenance</a> - <?php _e('with 100.000+ active installation it is a best plugin to create Maintenance or Coming Soon landing page!', 'clp-custom-login');?></p>
	</div>

	<div class="clp-rate-us widget">
		<h3 class="clp-rate-us title"><?php _e('Thank you for rating us with five stars!', 'clp-custom-login');?></h3>
		<p><?php echo sprintf(__('If you find our clp plugin useful, please show us some love and give 5%s feedback by pressing button below.', 'clp-custom-login'), '<i class="fas fa-star" aria-hidden="true"></i>');?></p>
		<a href="https://wordpress.org/support/plugin/clp-custom-login-page/reviews/?rate=5#new-post" target="_blank" style="text-decoration:none;">

		<p class="button button-primary"><?php _e('Leave Feedback', 'clp-custom-login');?></p>
		<span class="dashicons dashicons-star-filled"></span>
		<span class="dashicons dashicons-star-filled"></span>
		<span class="dashicons dashicons-star-filled"></span>
		<span class="dashicons dashicons-star-filled"></span>
		<span class="dashicons dashicons-star-filled"></span>
		</a>
		<p><?php echo sprintf( __('We are always happy to help on %s in a case you run into some issues.', 'clp-custom-login'), '<a href="https://wordpress.org/support/plugin/clp-custom-login-page/" target="_blank" style="text-decoration:none;">WordPress Support forum</a>');?>
		</p>

	</div>

	<div class="request-feature widget">
		<h3 class="clp-rate-us title"><?php _e('Request new features', 'clp-custom-login');?></h3>
		<p><?php echo sprintf( __('Are you missing a cool feature or do you have idea how to improve CLP plugin? You can %s on official Wordpress Support Forum.', 'clp-custom-login'), '<a href="https://wordpress.org/support/plugin/clp-custom-login-page/" target="_blank" style="text-decoration:none;">request feature</a>' );?> <i class="far fa-smile-wink"></i></p>
		
	</div>

</div>