<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
<style type="text/css">
.wt_productfeed_cron_settings_page{ padding:15px; }
.cron_list_tb td, .cron_list_tb th{ text-align:center; vertical-align:middle; }
.wt_productfeed_delete_cron{ cursor:pointer; }

.wt_productfeed_cron_current_time{float:right; width:auto; font-size:12px; font-weight:normal;}
.wt_productfeed_cron_current_time span{ display:inline-block; width:85px; }
.cron_list_tb td a{ cursor:pointer; }
</style>
<div class="wt_productfeed_cron_settings_page">
	<h2 class="wp-heading-inline"><?php _e('Scheduled Actions', 'webtoffee-product-feed');?> <div class="wt_productfeed_cron_current_time"><b><?php _e('Current server time:');?></b> <span>--:--:-- --</span><br/>
			<?php 
			$wt_time_zone = Webtoffee_Product_Feed_Sync_Common_Helper::get_advanced_settings('default_time_zone'); 
			if(!$wt_time_zone){
			?>
			<data><?php	esc_html_e( 'To switch to your website timezone'); ?> <a href="<?php echo admin_url('admin.php?page=wt_import_export_for_woo');?>" target="_blank" style="text-decoration:none;"> <?php esc_html_e( 'click here' ); ?> <em class="dashicons dashicons-external"></em></a></data>
			<?php } ?>
		</div></h2>
	<p>
		<?php _e('Lists all the scheduled processes for import and export.', 'webtoffee-product-feed'); ?><br />
		<?php _e('Disable or delete unwanted scheduled actions to reduce server load and reduce the chances for failure of actively scheduled actions.', 'webtoffee-product-feed'); ?>
	</p>
	<?php
	Webtoffee_Product_Feed_Sync_Cron::list_cron();
	?>
</div>