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
$page_url = "?page=$_page&tab=unsubscribes&pi=$list_page_index&q=$list_page_term";
if(isset($_GET['cancel']))
{
$id = intval($_GET['cancel']);
$wpdb->delete($trustindex_woocommerce->get_unsubscribe_tablename(), [ 'id' => $id ]);
setcookie('ti-success', 'cancelled', time() + 60, "/");
header('Location: '. $page_url);
exit;
}
$results = $trustindex_woocommerce->get_unsubscribes($list_page_index, $list_page_term);
?>
<div class="ti-box">
<h1 class="ti-free-title"><?php echo TrustindexWoocommercePlugin::___('Unsubscribed customers'); ?></h1>
<?php if ($ti_success == "cancelled"): ?>
<?php echo TrustindexWoocommercePlugin::get_noticebox("success", TrustindexWoocommercePlugin::___('Unsubscribe cancelled!')); ?>
<?php endif; ?>
<?php include('setup' . DIRECTORY_SEPARATOR . '_filter_pagination.php'); ?>
<?php if($results->total): ?>
<table class="wp-list-table widefat fixed striped table-view-list" id="ti-woocommerce-orders">
<thead>
<tr>
<th>E-mail</th>
<th><?php echo TrustindexWoocommercePlugin::___("Registered date"); ?></th>
<th></th>
</tr>
</thead>
<tbody>
<?php foreach($results->unsubscribes as $unsubscribe): ?>
<tr>
<td><?php echo esc_html($unsubscribe->email); ?></td>
<td><?php echo date('Y-m-d H:i:s', strtotime($unsubscribe->created_at)); ?></td>
<td class="text-center">
<a href="<?php echo $page_url .'&cancel='. $unsubscribe->id; ?>" class="btn-text btn-submit btn-sm" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading"); ?>"><?php echo TrustindexWoocommercePlugin::___("Cancel unsubscribe"); ?></a>
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