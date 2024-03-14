<form action="<?php echo admin_url( 'admin.php?page=fmc_admin_settings&tab=cache' ); ?>" method="post">
	<h4>Clear Cached Flexmls&reg; API Responses</h4>
	<p>If you&#8217;re having problems with your Flexmls&reg; widgets or listings, you can click the button below which will clear out the cached information and fetch the latest data from the MLS and your FlexMLS&reg; account.</p>
	<p><?php wp_nonce_field( 'clear_api_cache_action', 'clear_api_cache_nonce' ); ?><button type="submit" class="button-secondary">Clear Cache</button></p>
</form>
