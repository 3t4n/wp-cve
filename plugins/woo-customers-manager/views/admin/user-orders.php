<?php
if ( ! defined('WPINC')) {
    die;
}
?>
<h2><?php _e('Orders','woo-customers-manager'); ?></h2>

<table class="form-table">
    <tbody>
        <tr>
            <th><label><?php _e('Money spent','woo-customers-manager'); ?></label></th>
            <td>
                <?php echo wc_price( wc_get_customer_total_spent( $userID ) ); ?>
            </td>
        </tr>
    </tbody>
</table>

<table class="wp-list-table widefat fixed striped posts">
    <thead>
        <tr>
            <th scope="col" id="order_status" class="manage-column column-order_status">
                    <?php _e('Status','woo-customers-manager'); ?>
            </th>
            <th scope="col" id="order_title" class="manage-column"><?php _e('Order','woo-customers-manager'); ?></th>
            <th scope="col" id="order_items" class="manage-column"><?php _e('Items','woo-customers-manager'); ?></th>
            <th scope="col" id="order_date" class="manage-column"><?php _e('Date','woo-customers-manager'); ?></th>
            <th scope="col" id="order_total" class="manage-column"><?php _e('Total','woo-customers-manager'); ?></th>
        </tr>
    </thead>

    <tbody id="the-list">
        <?php if (count($orders)): ?>
            <?php foreach ($orders as $id => $order): ?>
                <?php $statusName = esc_html(wc_get_order_status_name($order->get_status())); ?>
                <tr id="post-<?php echo $id; ?>" >
                    <td class="order_status column-order_status" data-colname="Status">
                        <mark class="order-status status-<?php echo esc_attr(sanitize_html_class($order->get_status())); ?> tips" data-tip="<?php echo $statusName; ?>">
                            <span>
                            <?php echo $statusName; ?>
                            </span>
                        </mark>
                    </td>
                    <td data-colname="Id"><a href="<?php echo get_edit_post_link($id); ?> ">#<?php echo $id; ?></a></td>
                    <td data-colname="Items"> <?php echo count($order->get_items()); ?></td>
                    <td data-colname="Date"><time datetime="<?php echo $order->get_date_created()->date('c'); ?>"><?php echo $order->get_date_created()->date_i18n(get_option('date_format')); ?></time></td>
                    <td data-colname="Total"><?php echo wc_price( $order->get_total() ); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" align="center"><?php _e('No orders','woo-customers-manager'); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
