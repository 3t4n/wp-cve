<div class="pi-corw-container">
<form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
    <h4 class="mt-5"><?php printf(__('Request to cancel order #%d', 'cancel-order-request-woocommerce'), $order_id); ?></h4>
    <?php if(!empty($admin_message)): ?>
    <div class="pi-alert-box"><?php echo strip_tags($admin_message, '<br><strong><b>'); ?></div>
    <?php endif; ?>
    
    <?php echo $predefined_reasons; ?>
 
    <textarea name="order_cancel_reason" placeholder="<?php echo esc_attr(__('Reason for order canceling','cancel-order-request-woocommerce')); ?>" class="mb-10"></textarea>
    <input type="hidden" name="action" value="pi_cancellation_request">
    <input type="hidden" name="order_id" value="<?php echo esc_attr($order_id); ?>">
    <input type="hidden" name="redirect_url" value="<?php echo esc_attr($redirect_url); ?>">
    <input type="hidden" name="order_key" value="<?php echo esc_attr($order_key); ?>">
    <input type="submit" class="woocommerce-button button pi-cancel-request-submit-button" value="<?php echo esc_attr(__('Send Cancellation Request', 'cancel-order-request-woocommerce')); ?>">
</form>
</div>