<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
$options           = get_option( 'shareasale_wc_tracker_options' );
$attribution_key   = @$options['autovoid-key'];
$attribution_value = @$options['autovoid-value'];
?>

<div id="shareasale-wc-tracker">
	<div class="wrap">    
		<h2>
			<img id="shareasale-logo" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/star_logo.png' ) ?>">
			ShareASale WooCommerce Tracker Advanced Settings
		</h2>
		<h2 class="nav-tab-wrapper">
	    	<a href="?page=shareasale_wc_tracker" class="nav-tab">
	    		Tracking Settings
	    	</a>
	    	<a href="?page=shareasale_wc_tracker_automatic_reconciliation&amp;page_num=1" class="nav-tab">
	    		Automatic Reconciliation
	    	</a>
	    	<a href="?page=shareasale_wc_tracker_datafeed_generation&amp;page_num=1" class="nav-tab">
	    		Datafeed Generation
	    	</a>
	    	<a href="?page=shareasale_wc_tracker_advanced_settings" class="nav-tab nav-tab-active">
	    		Advanced Settings
	    	</a>
	    	<!-- <a href="?page=shareasale_wc_tracker_advanced_analytics" class="nav-tab nav-tab-analytics">
	    		Advanced Analytics
	    	</a> -->
		</h2>
		<form action="options.php" method="post">
			<div id="shareasale-wc-tracker-options">
			<?php
			  settings_fields( 'shareasale_wc_tracker_options' );
			  do_settings_sections( 'shareasale_wc_tracker_advanced_settings' );
			?>     
			</div>
			<?php if ($attribution_key && $attribution_value): ?>
			<p>Currently attributing ShareASale Affiliates on traffic to <span style="background-color: #D6D6D6"><?php echo get_bloginfo('url') . '/...?<strong>' . $attribution_key . '=' . $attribution_value; ?></strong></span></p>
			<?php endif; ?>
			<button id="tracker-options-save" class="button" name="Submit">Save Settings</button>
		</form>
	</div> 
</div>
