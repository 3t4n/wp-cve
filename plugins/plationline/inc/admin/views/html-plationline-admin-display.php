<?php

/**
 * @link              https://plati.online
 * @since             6.0.0
 * @package           PlatiOnlinePO6
 *
 */

use PlatiOnlinePO6\Inc\Core\WC_Plationline_PR;
use PlatiOnlinePO6\Inc\Core\WC_PlatiOnline_Recurrence;

global $theorder;
global $post;

$order = ($post instanceof WP_Post) ? wc_get_order($post->ID) : null;

if ($order instanceof WC_Order === false) {
    $order = $theorder;
}
$order_id = $order->get_id();

$plationline_transaction_type = $order->get_meta('_plationline_transaction_type');
$current_status = $order->get_status();
$payment_method = $order->get_payment_method();
$po6pr = new WC_Plationline_PR();
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="po6-ajax-loading">
    <span id="po6-wait">
        <?php echo __('Performing PlatiOnline request, please wait...', 'plationline') ?>
    </span>
</div>
<?php
if ($plationline_transaction_type == 'plationline_recurrence_master') {
    echo '<div class="inline notice notice-info">';
    echo '<p>' . __('PlatiOnline Recurrent Transaction Type', 'plationline') . ': <b>' . WC_PlatiOnline_Recurrence::$po_recurrence_types[$plationline_transaction_type] . '</b></p>';

    $po6_recurrence = new WC_PlatiOnline_Recurrence();
    $plationline_recurrence_frequency = $order->get_meta('_plationline_recurrence_frequency');
    $plationline_recurrence_duration = $order->get_meta('_plationline_recurrence_duration');

    if (!empty($plationline_recurrence_frequency)) {
        echo '<p>' . __('Recurrence frequency', 'plationline') . ': <b>' . $po6_recurrence->form_fields['recurrence_frequency']['options'][$plationline_recurrence_frequency] . '</b></p>';
    }

    if (!empty($plationline_recurrence_duration)) {
        echo '<p>' . __('Recurrence duration', 'plationline') . ': <b>' . $po6_recurrence->form_fields['recurrence_duration']['options'][$plationline_recurrence_duration] . '</b></p>';
    }

    $child_order_ids = $order->get_meta('_plationline_recurrence_child_orders');
    if (!empty($child_order_ids)) {
        \krsort($child_order_ids);
        echo '<h3>' . __('PlatiOnline child recurrent orders', 'plationline') . '</h3>';
        echo '<table width="100%">';
        echo '<tr align="center">
                <th>' . __('Order number', 'plationline') . '</th>
                <th>' . __('Order date purchased', 'plationline') . '</th>
                <th>' . __('Order status', 'plationline') . '</th>
                <th>' . __('Order transaction ID', 'plationline') . '</th>
                <th>' . __('View order', 'plationline') . '</th>
            </tr>';
        foreach ($child_order_ids as $child_order_id => $child_trans_id) {
            $child_order = \wc_get_order(absint($child_order_id));
            if (!empty($child_order)) {
                echo '<tr align="center">';
                echo '<td>#' . $child_order_id . '</td>';
                echo '<td>' . $child_order->get_date_created()->format('d-m-Y') . '</td>';
                echo '<td>' . \wc_get_order_status_name($child_order->get_status()) . '</td>';
                echo '<td>#' . $child_trans_id . '</td>';
                $url = admin_url('post.php?post=' . absint($child_order->get_id()) . '&action=edit');
                echo '<td>' . \sprintf('<a href="%s"><b>%s</b></a>', $url, __('View child order', 'plationline')) . '</td>';
                echo '</tr>';
            }
        }
        echo '</table>';
    }
    echo '</div>';
}

if ($plationline_transaction_type == 'plationline_recurrence_child' && !empty($order->get_meta('_plationline_recurrence_master_order_id'))) {
    echo '<div class="inline notice notice-info">';
    echo '<p>' . __('PlatiOnline Recurrent Transaction Type', 'plationline') . ': <b>' . WC_PlatiOnline_Recurrence::$po_recurrence_types[$plationline_transaction_type] . '</b></p>';
    $url = admin_url('post.php?post=' . absint($order->get_meta('_plationline_recurrence_master_order_id')) . '&action=edit');
    echo '<p>' . __('PlatiOnline master order', 'plationline') . ': <a href="' . $url . '"><b>#' . $order->get_meta('_plationline_recurrence_master_order_id') . '</b></a></p>';
    echo '<p>' . __('PlatiOnline master transaction ID', 'plationline') . ': <a href="' . $url . '"><b>#' . $order->get_meta('_plationline_recurrence_master_transaction_id') . '</b></a></p>';
    echo '</div>';
}
?>
<div class="po6-row">
    <p class="description po6-description">
        <?php echo __('Query PlatiOnline to obtain the current status for transaction', 'plationline') ?>
    </p>
    <button type="button" class="button button-primary" data-order="<?php echo $order_id ?>"
            id="query-po6">
        <?php echo __('Query Transaction', 'plationline') ?>
    </button>
</div>
<?php if ($payment_method != $po6pr->id): ?>
    <div class="po6-row">
        <p class="description po6-description">
            <?php echo __('Send Void request to PlatiOnline', 'plationline') ?>
        </p>
        <button type="button" class="button button-primary" data-order="<?php echo $order_id ?>"
                id="void-po6">
            <?php echo __('Void Transaction', 'plationline') ?>
        </button>
    </div>
    <div class="po6-row">
        <p class="description po6-description">
            <?php echo __('Send Settle request to PlatiOnline', 'plationline') ?>
        </p>
        <button type="button" class="button button-primary" data-order="<?php echo $order_id ?>"
                id="settle-po6">
            <?php echo __('Settle Transaction', 'plationline') ?>
        </button>
    </div>
    <!--<div class="po6-row">
        <p class="description po6-description">
			<?php /*echo __('Send Settle request for selected amount to PlatiOnline', 'plationline') */ ?>
        </p>
        <input value="<?php /*echo (float)$order->get_total() */ ?>" id="settle-po6-amount"
               type="number" step="0.01" min="0.01" max="<?php /*echo (float)$order->get_total() */ ?>">
		<?php /*echo $order->get_currency() */ ?>
        <button type="button" class="button button-primary" data-order="<?php /*echo $order_id */ ?>"
                id="settle-amount">
			<?php /*echo __('Settle Selected Amount', 'plationline') */ ?>
        </button>
    </div>-->

    <div class="po6-row">
        <p class="description po6-description">
            <?php echo __('Send Refund request for selected amount to PlatiOnline', 'plationline') ?>
        </p>
        <input value="<?php echo (float)$order->get_total() ?>" id="refund-po6-amount"
               type="number" step="0.01" min="0.01" max="<?php echo (float)$order->get_total() ?>">
        <?php echo $order->get_currency() ?>
        <button type="button" class="button button-primary" data-order="<?php echo $order_id ?>"
                id="refund-po6">
            <?php echo __('Refund Selected Amount', 'plationline') ?>
        </button>
    </div>

    <?php if (in_array($plationline_transaction_type, array('plationline_recurrence_master', 'plationline_recurrence_child'))): ?>
        <div class="po6-row">
            <p class="description po6-description">
                <?php echo __('Send Cancel Recurrence request to PlatiOnline', 'plationline') ?>
            </p>
            <?php
            if ($plationline_transaction_type == 'plationline_recurrence_master') {
                $master_order_id = $order_id;
            } else {
                $master_order_id = (int)$order->get_meta('_plationline_recurrence_master_order_id');
            }
            ?>
            <button type="button" class="button button-primary" data-order="<?php echo $master_order_id ?>"
                    id="cancel-recurrence-po6">
                <?php echo __('Cancel Recurrence', 'plationline') ?>
            </button>
        </div>
    <?php endif; ?>
<?php endif; ?>
