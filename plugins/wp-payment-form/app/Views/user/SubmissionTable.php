<?php
use WPPayForm\Framework\Support\Arr;

?>
<div class="wpf-user-dashboard-table">
    <div class="wpf-user-dashboard-table__header">
        <div class="wpf-user-dashboard-table__column">ID</div>
        <div class="wpf-user-dashboard-table__column">Amount</div>
        <div class="wpf-user-dashboard-table__column">Date</div>
        <div class="wpf-user-dashboard-table__column">Status</div>
        <div class="wpf-user-dashboard-table__column">Payment Method</div>
    </div>
    <div class="wpf-user-dashboard-table__rows">
        <?php
        $i = 0;
        foreach (Arr::get($donationItems, 'orders', []) as $donationKey => $donationItem):
            ?>
            <div class="wpf-user-dashboard-table__row">
                <div class="wpf-user-dashboard-table__column">
                    <span class="wpf-sub-id wpf_toal_amount_btn" data-modal_id="<?php echo 'wpf_toal_amount_modal' . $i ?>">
                        <?php echo Arr::get($donationItem, 'id', '') ?> <span class="dashicons dashicons-visibility"></span>
                    </span>
                </div>
                <div class="wpf-user-dashboard-table__column">
                    <?php echo Arr::get($donationItem, 'payment_total', '') ?>
                    <?php echo Arr::get($donationItem, 'currency', '') ?>
                </div>
                <div class="wpf-user-dashboard-table__column">
                    <?php echo Arr::get($donationItem, 'created_at', '') ?>
                </div>
                <div class="wpf-user-dashboard-table__column">
                    <span class="wpf-payment-status <?php echo Arr::get($donationItem, 'payment_status', '') ?>">
                        <?php echo Arr::get($donationItem, 'payment_status', '') ?>
                    </span>
                </div>
                <div class="wpf-user-dashboard-table__column">
                    <?php echo Arr::get($donationItem, 'payment_method', '') ?>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>