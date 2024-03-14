<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
<div class="wt_pf_history_page">
	<h2 class="wt_pf_page_hd"><?php _e('Product Feed'); ?>
	<span class="wt-webtoffee-icon" style="float: <?php echo (!is_rtl()) ? 'right' : 'left'; ?>;">
		<span style="font-size:14px;"><?php esc_html_e('Developed by'); ?></span>
    <a target="_blank" href="https://www.webtoffee.com">
        <img src="<?php echo WT_PRODUCT_FEED_PLUGIN_URL.'/assets/images/webtoffee-logo_small.png';?>" style="max-width:100px;">
    </a>
</span>
	</h2>
		
	<hr>
	<h2 class="wp-heading-inline"><?php _e('Manage feeds');?></h2>

	<?php
	//echo self::gen_pagination_html($total_records, $this->max_records, $offset, 'admin.php', $pagination_url_params);
	?>
	<?php
	if(isset($history_list) && is_array($history_list) && count($history_list)>0)
	{
		?>
		<table class="wp-list-table widefat fixed striped history_list_tb">
		<thead>
			<tr>
				<th><?php _e("Name"); ?></th>
				<th width="90px;"><?php _e("Catalog type"); ?></th>
				<th width="60px;"><?php _e("File type"); ?></th>				
				<th><?php _e("URL"); ?></th>				
				<th width="65px;"><?php _e("Products"); ?></th>				
				<th width="110px;"><?php _e("Refresh interval"); ?></th>					
				<th><?php _e("Last updated"); ?></th>
				<th><?php _e("Actions"); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		$i=$offset;

		foreach($history_list as $key =>$history_item)
		{
			
			$i++;
			?>
			<tr>
				<?php $form_data=maybe_unserialize($history_item['data']); ?>
				<td><?php echo ucfirst(pathinfo($history_item['file_name'], PATHINFO_FILENAME)); ?></td>
				<td><?php echo ucfirst($history_item['item_type']); ?></td>
				<td><?php echo strtoupper(pathinfo($history_item['file_name'], PATHINFO_EXTENSION)); ?></td>
				<td>
					<?php echo content_url().'/uploads/webtoffee_product_feed/'.($history_item['file_name']); ?><br/>
					<button data-uri = "<?php echo content_url().'/uploads/webtoffee_product_feed/'.($history_item['file_name']); ?>" class="button button-primary wt_pf_copy"><?php esc_html_e( 'Copy URL' ) ; ?></button>
				</td>				
				<td><?php echo ucfirst($history_item['total']); ?></td>
				<td><?php echo ucfirst($form_data['post_type_form_data']['item_gen_interval']); ?></td>
				<td><?php echo date_i18n('Y-m-d h:i:s A', $history_item['updated_at']); ?></td>
				<td>					
					<a class="wt_pf_delete_history wt_manage_feed_icons" data-href="<?php echo str_replace('_history_id_', $history_item['id'], $delete_url);?>"><img src="<?php echo WT_PRODUCT_FEED_PLUGIN_URL.'/assets/images/wt_fi_trash.svg';?>" alt="<?php _e('Delete'); ?>" title="<?php _e('Delete'); ?>"/></a>
					<?php
					$action_type=$history_item['template_type'];
					if($form_data && is_array($form_data))
					{
						$to_process=(isset($form_data['post_type_form_data']) && isset($form_data['post_type_form_data']['item_type']) ? $form_data['post_type_form_data']['item_type'] : '');
						if($to_process!="")
						{
							if(Webtoffee_Product_Feed_Sync_Admin::module_exists($action_type))
							{
								$action_module_id=Webtoffee_Product_Feed_Sync::get_module_id($action_type);
								$url=admin_url('admin.php?page='.$action_module_id.'&wt_pf_rerun='.$history_item['id']);
								?>
								   <a class="wt_manage_feed_icons" href="<?php echo $url;?>" target="_blank"><img src="<?php echo WT_PRODUCT_FEED_PLUGIN_URL.'/assets/images/wt_fi_edit.svg';?>" alt="<?php _e('Edit'); ?>" title="<?php _e('Edit'); ?>"/></a>
								<?php
							}
						}
					}

                                        if($action_type=='export' && Webtoffee_Product_Feed_Sync_Admin::module_exists($action_type))
					{
                                            $export_download_url=wp_nonce_url(admin_url('admin.php?wt_pf_export_download=true&file='.$history_item['file_name']), WEBTOFFEE_PRODUCT_FEED_ID);
						?>
                                                          <a class="wt_manage_feed_icons wt_pf_export_download_btn" target="_blank" href="<?php echo $export_download_url;?>"><img src="<?php echo WT_PRODUCT_FEED_PLUGIN_URL.'/assets/images/wt_fi_download.svg';?>" alt="<?php _e('Download'); ?>" title="<?php _e('Download'); ?>"/></a>
						<?php
					}                                        
					?>
                                        <?php if( isset($form_data['post_type_form_data']['item_gen_interval']) && 'manual' !== $form_data['post_type_form_data']['item_gen_interval'] ) { ?>
                                            <a class="wt_manage_feed_icons wt_pf_export_refresh_btn" href="javascript:void(0);" data-cron_id="<?php echo $history_item['id']; ?>"><img src="<?php echo WT_PRODUCT_FEED_PLUGIN_URL.'/assets/images/wt_fi_refresh.svg';?>" alt="<?php _e('Refresh'); ?>" title="<?php _e('Refresh'); ?>"/></a>
                                        <?php } ?>
				</td>
			</tr>
			<?php	
		}
		?>
		</tbody>
		</table>
		<?php
		//echo self::gen_pagination_html($total_records, $this->max_records, $offset, 'admin.php', $pagination_url_params);
	}else
	{
		?>
		<h4 class="wt_pf_history_no_records"><?php _e("No records found."); ?></h4>
		<?php
	}
	?>
</div>