<div class="wrap">
	<h2 id="quick_adsense_title">Quick Adsense Settings <span style="font-size: 14px;">(Version 2.8)</span></h2>
	<form id="quick_adsense_settings_form" method="post" action="options.php" name="wp_auto_commenter_form" style="display: none;">
		<?php settings_fields( 'quick_adsense_settings' ); ?>
		<div id="quick_adsense_settings_tabs">
			<ul>
				<li><a href="#tabs-settings">Settings</a></li>
				<li><a href="#tabs-post-body-ads">Ads on Post Body</a></li>
				<li><a href="#tabs-sidebar-widget-ads">Sidebar Widget</a></li>
				<li><a href="#tabs-header-footer-codes">Header / Footer Codes</a></li>
			</ul>
			<div id="tabs-settings">
				<div id="quick_adsense_top_sections_wrapper">
					<?php do_settings_sections( 'quick-adsense-general' ); ?>
				</div>
				<?php submit_button( 'Save Changes' ); ?>
			</div>
			<div id="tabs-post-body-ads">
				<?php do_settings_sections( 'quick-adsense-onpost' ); ?>
				<?php submit_button( 'Save Changes' ); ?>
			</div>
			<div id="tabs-sidebar-widget-ads">
				<?php do_settings_sections( 'quick-adsense-widgets' ); ?>
				<?php submit_button( 'Save Changes' ); ?>
			</div>
			<div id="tabs-header-footer-codes">
				<?php do_settings_sections( 'quick-adsense-header-footer-codes' ); ?>
				<?php submit_button( 'Save Changes' ); ?>
			</div>
		</div>	
	</form>
</div>
