<?php defined('ABSPATH') || exit; ?>

<?php
	use Searchanise\SmartWoocommerceSearch\Info;
	use Searchanise\SmartWoocommerceSearch\Api;

	$info = Info::get_info(Api::get_instance()->get_locale());
	$yes_icon = '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
	$no_icon = '<mark class="no"><span class="dashicons dashicons-no"></span></mark>';
	$error_icon = '<mark class="error"><span class="dashicons dashicons-warning"></span> %s</mark>';
?>

<h1><?php echo esc_html(__('Searchanise Info', 'woocommerce-searchanise')); ?></h1>

<table class="wc_status_table se_info_table widefat" cellspacing="0" id="se-info">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Searchanise enviroment"><h2><?php esc_html_e('Searchanise enviroment', 'woocommerce-searchanise' ); ?></h2></th>
		</tr>
		<tbody>
			<tr>
				<td><?php esc_html_e('Plugin version', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Searchanise plugin version.', 'woocommerce-searchanise')); ?></td>
				<td><?php echo esc_html($info['addon_version']); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e('API Keys', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Registered API keys.', 'woocommerce-searchanise')); ?></td>
				<td>
					<?php foreach ($info['api_key'] as $lang_code => $key) { ?>
						<?php echo esc_html('[' . $lang_code . '] ' . $key); ?><br>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e('Export status', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Searchanise engine export statuses.', 'woocommerce-searchanise')); ?></td>
				<td>
					<?php foreach ($info['export_status'] as $lang_code => $st) { ?>
						<?php echo esc_html('[' . $lang_code . '] ' . $st); ?><br>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e('Search input selector', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Search input CSS selector.', 'woocommerce-searchanise')); ?></td>
				<td><?php echo esc_html($info['search_input_selector']); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e('Sync mode', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Searchanise synchronization mode.', 'woocommerce-searchanise')); ?></td>
				<td><?php echo esc_html($info['sync_mode']); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e('Plugin enabled', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Searchanise plugin status.', 'woocommerce-searchanise')); ?></td>
				<td><?php echo 'enabled' == $info['addon_status'] ? wp_kses($yes_icon, array('mark' => array('class' => array()), 'span' => array('class' => array()))) : wp_kses($no_icon, array('mark' => array('class' => array()), 'span' => array('class' => array()))); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e('Search enabled', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Enable search with Searchanise', 'woocommerce-searchanise')); ?></td>
				<td><?php echo 'Y' == $info['search_enabled'] ? wp_kses($yes_icon, array('mark' => array('class' => array()), 'span' => array('class' => array()))) : wp_kses($no_icon, array('mark' => array('class' => array()), 'span' => array('class' => array()))); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e('Cron async enabled', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Enable synchronization via Cron', 'woocommerce-searchanise')); ?></td>
				<td><?php echo 'Y' == $info['cron_async_enabled'] ? wp_kses($yes_icon, array('mark' => array('class' => array()), 'span' => array('class' => array()))) : wp_kses($no_icon, array('mark' => array('class' => array()), 'span' => array('class' => array()))); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e('Ajax async enabled', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Enable synchronization via Ajax calls', 'woocommerce-searchanise')); ?></td>
				<td><?php echo 'Y' == $info['ajax_async_enabled'] ? wp_kses($yes_icon, array('mark' => array('class' => array()), 'span' => array('class' => array()))) : wp_kses($no_icon, array('mark' => array('class' => array()), 'span' => array('class' => array()))); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e('Log directory', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Searchanise log directory', 'woocommerce-searchanise')); ?></td>
				<td><?php echo esc_html($info['log_dir']); ?></td>
			</tr>
		</tbody>
	</thead>
</table>

<table class="wc_status_table se_info_table widefat" cellspacing="0" id="se-info">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Server environment"><h2><?php esc_html_e('Server environment', 'woocommerce-searchanise' ); ?></h2></th>
		</tr>
		<tbody>
			<tr>
				<td><?php esc_html_e('Max execution time', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Default maximum execution script time', 'woocommerce-searchanise')); ?></td>
				<td><?php echo esc_html($info['max_execution_time']); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e('Max execution time after', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Maximum execution script time for Searchanise', 'woocommerce-searchanise')); ?></td>
				<td><?php echo esc_html($info['max_execution_time_after']); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e('Ignore user abort', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Default value of ignore_user_abort PHP setting.', 'woocommerce-searchanise')); ?></td>
				<td><?php echo esc_html($info['ignore_user_abort']); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e('Ignore user abort after', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('value of ignore_user_abort PHP setting for Searchanise.', 'woocommerce-searchanise')); ?></td>
				<td><?php echo esc_html($info['ignore_user_abort_after']); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e('Memory limit', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Default memory limit for scripts.', 'woocommerce-searchanise')); ?></td>
				<td><?php echo esc_html($info['memory_limit']); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e('Memory limit after', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Memory limit for Searchanise.', 'woocommerce-searchanise')); ?></td>
				<td><?php echo esc_html($info['memory_limit_after']); ?></td>
			</tr>
		</tbody>
	</thead>
</table>

<table class="wc_status_table se_info_table widefat" cellspacing="0" id="se-info">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Searchanise queue"><h2><?php esc_html_e('Searchanise queue', 'woocommerce-searchanise' ); ?></h2></th>
		</tr>
		<tbody>
			<tr>
				<td><?php esc_html_e('Queue status', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Common queue status.', 'woocommerce-searchanise')); ?></td>
				<td><?php echo 'Y' == $info['queue_status'] ? wp_kses($yes_icon, array('mark' => array('class' => array()), 'span' => array('class' => array()))) : ( sprintf( wp_kses($error_icon, array('mark' => array('class' => array()), 'span' => array('class' => array()))), esc_html__('Something is wrong in queue processing. Please contact Searchanise <a href="mailto:feedback@searchnise.com">feedback@searchnise.com</a> technical support', 'woocommerce-searchanise')) ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e('Total items', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Total items in Searchanise queue', 'woocommerce-searchanise')); ?></td>
				<td><?php echo esc_html($info['total_items_in_queue']); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e('Next queue', 'woocommerce-searchanise'); ?>:</td>
				<td class="help"><?php echo wc_help_tip(esc_html__('Next item in queue', 'woocommerce-searchanise')); ?></td>
				<td><?php echo esc_html(print_r($info['next_queue'], true)); ?></td>
			</tr>
		</tbody>
	</thead>
</table>

<?php if (!empty($info['plugins'])) { ?>
<table class="wc_status_table se_info_table widefat" cellspacing="0" id="se-info">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Active plugins"><h2><?php esc_html_e('Active plugins', 'woocommerce-searchanise' ); ?></h2></th>
		</tr>
		<tbody>
			<?php foreach ($info['plugins'] as $pl) { ?>
				<tr>
					<td><?php echo wp_kses("<a href='{$pl['PluginURI']}' aria-label='" . __('Visit plugin page', 'woocommerce-searchanise') . "'>{$pl['Name']}</a>", array('a' => array('href' => array()))); ?></td>
					<td class="help">&nbsp;</td>
					<td><?php echo esc_html($pl['Version']); ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</thead>
</table>
<?php } ?>
