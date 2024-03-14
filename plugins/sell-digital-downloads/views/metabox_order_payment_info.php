<?php
if (!current_user_can('edit_posts')) wp_die( __('You do not have sufficient permissions to access this page.') );
?>
<table class="form-table">
  <input type="hidden" name="order_token" />
  <tr valign="top">
       <th scope="row"><strong><label for="payment_status"><?php echo __('Status:','sell-digital-downloads'); ?></label><strong></th>
       <td>
          <select name="payment_status">
            <option value="pending" <?php echo (strtolower($payment_info['status'])=='pending') ? 'selected':''; ?>><?php echo __('Pending','sell-digital-downloads'); ?></option>
            <option value="completed" <?php echo (strtolower($payment_info['status'])=='completed') ? 'selected':''; ?>><?php echo __('Completed','sell-digital-downloads'); ?></option>
            <option value="refunded" <?php echo (strtolower($payment_info['status'])=='refunded') ? 'selected':''; ?>><?php echo __('Refunded','sell-digital-downloads'); ?></option>
          </select>
        </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="amount_paid"><?php echo __('Amount Paid:','sell-digital-downloads'); ?></label><strong></th>
       <td>
          <input type="text" value="<?php echo $payment_info['amount_paid']; ?>" id="amount_paid" name="amount_paid"  /><span class="currency" style="font-weight:bold"><?php echo $currency; ?></span>
        </td>
    </tr>
    <tr valign="top">
       <th scope="row"><strong><label for="txn_id"><?php echo __('Transcation ID:','sell-digital-downloads'); ?></label><strong></th>
       <td>
          <input type="text" value="<?php echo $payment_info['txn_id']; ?>" id="txn_id" name="txn_id"  required />
        </td>
    </tr>
</table>