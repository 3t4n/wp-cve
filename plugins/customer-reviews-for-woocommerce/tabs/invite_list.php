<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$ti_success = "";
if(isset($_COOKIE['ti-success']))
{
$ti_success = sanitize_text_field($_COOKIE['ti-success']);
setcookie('ti-success', '', time() - 60, "/");
}
$list_page_index = isset($_REQUEST['pi']) ? intval($_REQUEST['pi']) : 1;
$list_page_term = isset($_REQUEST['q']) ? sanitize_text_field($_REQUEST['q']) : "";
$page_url = "?page=$_page&tab=invite_list&pi=$list_page_index&q=$list_page_term";
if(isset($_GET['cancel']))
{
$id = intval($_GET['cancel']);
$wpdb->delete($trustindex_woocommerce->get_schedule_tablename(), [ 'id' => $id ]);
setcookie('ti-success', 'cancelled', time() + 60, "/");
header('Location: '. $page_url);
exit;
}
$results = $trustindex_woocommerce->get_schedules($list_page_index, $list_page_term);
?>
<div class="ti-box">
<h1 class="ti-free-title">
<?php echo TrustindexWoocommercePlugin::___('Invitations'); ?>
<div class="ti-pull-right">
<a href="?page=<?php echo $_page; ?>&tab=setup&step=4" class="btn-text btn-submit btn-primary btn-sm" style="line-height: 2" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading"); ?>"><?php echo TrustindexWoocommercePlugin::___("Invite Your Past Customers to Write Reviews") ;?></a>
<a href="?page=<?php echo $_page; ?>&tab=unsubscribes" class="btn-text btn-submit btn-sm" style="line-height: 2" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading"); ?>"><?php echo TrustindexWoocommercePlugin::___("Unsubscribed customers") ;?></a>
</div>
<div class="clear"></div>
</h1>
<?php if ($ti_success == "cancelled"): ?>
<?php echo TrustindexWoocommercePlugin::get_noticebox("success", TrustindexWoocommercePlugin::___('Invite cancelled!')); ?>
<?php endif; ?>
<?php include('setup' . DIRECTORY_SEPARATOR . '_filter_pagination.php'); ?>
<?php if($results->total): ?>
<table class="wp-list-table widefat fixed striped table-view-list" id="ti-woocommerce-orders">
<thead>
<tr>
<th><?php echo TrustindexWoocommercePlugin::___("Order"); ?></th>
<th>E-mail</th>
<th><?php echo TrustindexWoocommercePlugin::___("Registered date"); ?></th>
<th><?php echo TrustindexWoocommercePlugin::___("Send date"); ?></th>
<th><?php echo TrustindexWoocommercePlugin::___("Invitations status"); ?></th>
</tr>
</thead>
<tbody>
<?php foreach($results->schedules as $schedule): ?>
<tr>
<td><a href="post.php?post=<?php echo esc_attr($schedule->order_id); ?>&action=edit" target="_blank">#<?php echo esc_html($schedule->order_id); ?></a></td>
<td><?php echo esc_html($schedule->email); ?></td>
<td><?php echo date('Y-m-d H:i:s', strtotime($schedule->created_at)); ?></td>
<td><?php echo date('Y-m-d H:i:s', $schedule->timestamp); ?></td>
<td class="text-center">
<?php if($schedule->sent): ?>
<img src="<?php echo $trustindex_woocommerce->get_plugin_file_url('static/img/check-icon.svg'); ?>" class="ti-check-icon" alt="" />
<?php else: ?>
<a href="<?php echo $page_url .'&cancel='. $schedule->id; ?>" class="btn-text btn-submit btn-sm" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading"); ?>"><?php echo TrustindexWoocommercePlugin::___("Cancel invite"); ?></a>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php else: ?>
<div class="ti-notice notice-warning">
<p><?php echo TrustindexWoocommercePlugin::___("There is no e-mail found."); ?></p>
</div>
<?php endif; ?>
</div>