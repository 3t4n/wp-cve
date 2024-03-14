<?php
if($ti_command == 'previous-invite')
{
check_admin_referer('ti-woocommerce-previous-invite');
$ids = $_POST['ids'];
if(is_array($ids))
{
$sent_emails = [];
foreach($ids as $id)
{
$id = intval($id);
$order = new WC_Order($id);
$email = null;
$user = $order->get_user();
if($user)
{
$email = $user->user_email;
}
else
{
$order_data = $order->get_data();
$email = $order_data['billing']['email'];
}
if(!in_array($email, $sent_emails))
{
$sent_emails []= $email;
$trustindex_woocommerce->sendMail($email, $id);
}
$trustindex_woocommerce->register_schedule_sent($email, $id);
}
}
exit;
}
$list_page_index = isset($_REQUEST['pi']) ? intval($_REQUEST['pi']) : 1;
$list_page_term = isset($_REQUEST['q']) ? sanitize_text_field($_REQUEST['q']) : "";
$results = $trustindex_woocommerce->get_previous_orders($list_page_index, $list_page_term);
$page_url = '?page='. $_page .'&tab=setup&step=5';
?>
<div class="ti-box">
<h1 class="ti-free-title">
<?php echo TrustindexWoocommercePlugin::___('Invite Your Past Customers to Write Reviews'); ?>
<a href="?page=<?php echo $_page; ?>&tab=setup" class="ti-back-icon"><?php echo TrustindexWoocommercePlugin::___('Back'); ?></a>
</h1>
<?php include('_filter_pagination.php'); ?>
<?php if($results->total): ?>
<div class="ti-notice notice-success is-dismissible" id="ti-woocommerce-sent-notification" style="display: none">
<p><?php echo TrustindexWoocommercePlugin::___("Invites sent!"); ?></p>
<button type="button" class="notice-dismiss"></button>
</div>
<div class="ti-notice notice-error hidden" id="ti-woocommerce-select-notification">
<p><?php echo TrustindexWoocommercePlugin::___("Please select at least one row!"); ?></p>
</div>
<table class="wp-list-table widefat fixed striped table-view-list" id="ti-woocommerce-orders">
<thead>
<tr>
<th class="text-center" style="width: 25px">
<span class="ti-checkbox">
<input type="checkbox" id="ti-woocommerce-select-all">
<label> </label>
</span>
</th>
<th style="width: 125px"><?php echo TrustindexWoocommercePlugin::___("Order"); ?></th>
<th><?php echo TrustindexWoocommercePlugin::___("Date"); ?></th>
<th style="width: 25%"><?php echo TrustindexWoocommercePlugin::___("Billing info"); ?></th>
<th style="width: 45%"><?php echo TrustindexWoocommercePlugin::___("Ordered product(s)"); ?></th>
</tr>
</thead>
<tbody>
<?php foreach($results->orders as $order): ?>
<?php
$data = $order->get_data();
$name = $data['billing']['first_name'] . ' ' . $data['billing']['last_name'];
if($data['billing']['company'])
{
$name .= ' ('. $data['billing']['company'] .')';
}
$addr = $data['billing'];
foreach($addr as $key => $value)
{
if(!in_array($key, [ 'address_1', 'address_2', 'city', 'state', 'postcode', 'country' ]))
{
unset($addr[$key]);
}
}
$address = trim(implode(' ', $addr));
?>
<tr data-id="<?php echo esc_attr($data['id']); ?>">
<td class="text-center">
<span class="ti-checkbox">
<input type="checkbox" class="ti-order-ids" value="<?php echo esc_attr($data['id']); ?>">
<label> </label>
</span>
</td>
<td><a href="post.php?post=<?php echo esc_attr($data['id']); ?>&action=edit" target="_blank">#<?php echo esc_html($data['id']); ?></td>
<td><?php echo $data['date_created']->date('Y-m-d H:i:s'); ?></td>
<td><?php echo esc_html($name); ?><br /><?php echo esc_html($address); ?></td>
<td>
<?php foreach($order->get_items() as $item_key => $item): ?>
<?php echo TrustindexWoocommercePlugin::___("%s (%d pcs)", [ $item->get_name(), $item->get_quantity() ]); ?>
<br />
<?php endforeach; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<div class="ti-footer">
<?php wp_nonce_field('ti-woocommerce-previous-invite'); ?>
<a href="#" class="btn-text btn-invite-previous ti-pull-right" data-loading-text="<?php echo TrustindexWoocommercePlugin::___("Loading") ;?>"><?php echo TrustindexWoocommercePlugin::___("Invite"); ?></a>
<div class="clear"></div>
</div>
<?php else: ?>
<div class="ti-notice notice-warning">
<p><?php echo TrustindexWoocommercePlugin::___("There is no order found."); ?></p>
</div>
<?php endif; ?>
</div>