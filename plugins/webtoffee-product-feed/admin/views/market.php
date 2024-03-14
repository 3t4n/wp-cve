<?php
if (!defined('ABSPATH')) {
	exit;
}
?>

<div class="wt-profeed-upsell-wrapper market-box table-box-main">
    <div class="wt-profeed-premium-upgrade wt-profeed-sidebar">

		<div class="wt-profeed-header">
			<div class="wt-feed-why-premium">
				<img style="float: left;" src="<?php echo WT_PRODUCT_FEED_PLUGIN_URL; ?>assets/images/gopro/wt-feed-crown.png" alt="alt"/>
				<?php if( isset( $fb_sync_tab ) ){ ?>
				<p style="margin:10px 0px;font-size: 16px;"><?php esc_html_e('What\'s in premium?'); ?></p>
				<?php } else{ ?>
				<p style="margin:10px 0px;font-size: 16px;"><?php esc_html_e('Why use premium?'); ?></p>
				<?php } ?>
			</div>
			<div class="wt-profeed wt-profeed_review wt-profeed_tags wt-profeed_categories wt-profeed-gopro-cta wt-profeed-features">
				<ul class="ticked-list wt-profeed-allfeat">
                                    <?php if( isset( $fb_sync_tab ) ): ?>
                                        <li><?php esc_html_e( 'Enable auto-sync for the Facebook catalog', 'webtoffee-product-feed' ); ?></li>				
					<li><?php esc_html_e( 'Add fields like condition, color, gender, material, etc., to products.', 'webtoffee-product-feed' ); ?></li>
					<li><?php esc_html_e( 'Exclude out-of-stock products from syncing', 'webtoffee-product-feed' ); ?></li>
                                        <li><?php esc_html_e( 'Exclude specific products/variations from the sync.', 'webtoffee-product-feed' ); ?></li>            
					<?php else: ?> 
                                        <li><?php esc_html_e( 'Dynamic Price & Feed Update', 'webtoffee-product-feed' ); ?>&nbsp;<i style="width:42px;height:18px;color:#fff;background-image:url('<?php echo WT_PRODUCT_FEED_PLUGIN_URL; ?>assets/images/gopro/new_flag.svg')">&nbsp;&nbsp;&nbsp;New</i></li>
					<li><?php esc_html_e( 'Advanced filtering', 'webtoffee-product-feed' ); ?></li>
					<li><?php esc_html_e( 'Multilingual & Multicurrency support for WPML', 'webtoffee-product-feed' ); ?></li>
					<li><?php esc_html_e( 'Choose from all variations/default variation/lowest priced variation to include in the feed', 'webtoffee-product-feed' ); ?></li>                                    					
					<li><?php esc_html_e( 'Server cron support', 'webtoffee-product-feed' ); ?></li>					
                                        <li><?php esc_html_e( 'Set feed refresh at a specific time', 'webtoffee-product-feed' ); ?></li>
                                        <li><?php esc_html_e( 'Exclude out-of-stock products', 'webtoffee-product-feed' ); ?></li>
                                        <li><?php esc_html_e( 'Multi-vendor compatibility for Dokan plugin', 'webtoffee-product-feed' ); ?></li>
                                        <li><?php esc_html_e( 'Compatibility for third-party brand plugins', 'webtoffee-product-feed' ); ?></li>
                                        <li><?php esc_html_e( 'Leguide, Google product reviews, Custom feed support', 'webtoffee-product-feed' ); ?></li>
                                        <?php endif; ?>
				</ul>			
			</div>
				<div class="wt-profeed-btn-wrapper">
					<?php if( isset( $fb_sync_tab ) ){ ?>
					<a href="<?php echo esc_url("https://www.webtoffee.com/product/woocommerce-product-feed/?utm_source=free_plugin_sidebar_sync&utm_medium=feed_basic&utm_campaign=WooCommerce_Product_Feed&utm_content=" . WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION); ?>" class="wt-profeed-blue-btn-new" target="_blank"><?php esc_html_e( 'Check out premium', 'webtoffee-product-feed' ); ?> <span class="dashicons dashicons-arrow-right-alt"></span></a>
					<?php }else { ?>
						<a href="<?php echo esc_url("https://www.webtoffee.com/product/woocommerce-product-feed/?utm_source=product_feed_page&utm_medium=feed_basic&utm_campaign=WooCommerce_Product_Feed&utm_content=" . WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION); ?>" class="wt-profeed-blue-btn-new" target="_blank"><?php esc_html_e( 'Check out premium', 'webtoffee-product-feed' ); ?> <span class="dashicons dashicons-arrow-right-alt"></span></a>
					<?php } ?>                    
                </div>               			
		</div>	
		<div class="wt-feed-cs-rating-money-back">
			<div class="wt-feed-money-back">
				<img src="<?php echo WT_PRODUCT_FEED_PLUGIN_URL; ?>assets/images/gopro/wt-money-back.svg" alt="alt"/>
				<p><?php echo sprintf(__('You are covered by our %s 30-day money back guarantee %s'), '<b>', '</b>'); ?></p>
			</div>
			<div class="wt-feed-cs-rating">
				<img src="<?php echo WT_PRODUCT_FEED_PLUGIN_URL; ?>assets/images/gopro/wt-satisfaction-rating.svg"" alt="alt"/>
				<p><?php echo sprintf(__('Supported by a team with %s %s customer satisfaction %s score'), '<b>', '99%', '</b>'); ?></p>
			</div>
		</div>
    </div>
</div>