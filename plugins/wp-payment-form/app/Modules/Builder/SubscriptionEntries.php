<?php
namespace WPPayForm\App\Modules\Builder;

use WPPayForm\Framework\Support\Arr;

use WPPayForm\App\Services\GlobalTools;

class SubscriptionEntries
{
    public function render($subscriptionEntry, $subscriptionStatus, $formId, $submissionHash, $can_sync_subscription_billings, $isNotOfflinePayment, $planName)
    {
        if (getType($subscriptionEntry) == "object") {
            $subscriptionEntry = $subscriptionEntry->toArray();
        }
        ob_start();
        ?>
        <div class='wpf-user-dashboard-table'>
            <div class="wpf-user-dashboard-loader"></div>
            <div class="wpf-user-table-title">
                <div>
                    <p style="margin: 0;font-size: 22px;font-weight: 500;color: #423b3b;">
                        <?php echo $planName ?> - billings.
                    </p>
                </div>
                <?php if ($can_sync_subscription_billings == 'yes' && $isNotOfflinePayment && $subscriptionStatus != 'cancelled'): ?>
                    <div class="wpf-sync-action">
                        <span class="dashicons dashicons-update-alt"></span>
                        <button class="wpf-sync-subscription-btn" data-form_id="<?php echo $formId ?>"
                            data-submission_hash="<?php echo $submissionHash ?>">Sync</button>
                    </div>
                <?php endif ?>
            </div>
            <div class="wpf-table-container">
                <div class='wpf-user-dashboard-table__header'>
                    <div class='wpf-user-dashboard-table__column'>ID</div>
                    <div class='wpf-user-dashboard-table__column'>Amount</div>
                    <div class='wpf-user-dashboard-table__column'>Date</div>
                    <div class='wpf-user-dashboard-table__column'>Status</div>
                    <div class='wpf-user-dashboard-table__column'>Payment Method</div>
                </div>
                <div class='wpf-user-dashboard-table__rows'>
                    <?php
                    foreach ($subscriptionEntry as $donationKey => $donationItem):
                        ?>
                        <div class='wpf-user-dashboard-table__row'>
                            <div class='wpf-user-dashboard-table__column'>
                                <span class='wpf-sub-id wpf_toal_amount_btn' style="color: black">
                                    <?php echo Arr::get($donationItem, 'id', '') ?> 
                                </span>
                            </div>
                            <div class='wpf-user-dashboard-table__column'>
                                <?php echo Arr::get($donationItem, 'payment_total', '') / 100 ?>
                                <?php echo Arr::get($donationItem, 'currency', '') ?>
                            </div>
                            <div class='wpf-user-dashboard-table__column'>
                                <?php echo GlobalTools::convertStringToDate(Arr::get($donationItem, 'created_at', '')) ?>
                            </div>
                            <div class='wpf-user-dashboard-table__column'>
                                <span class='wpf-payment-status <?php echo Arr::get($donationItem, 'status', '') ?>'>
                                    <?php echo Arr::get($donationItem, 'status', '') ?>
                                </span>
                            </div>
                            <div class='wpf-user-dashboard-table__column'>
                                <?php echo Arr::get($donationItem, 'payment_method', '') ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
        <?php
        $view = ob_get_clean();
        return $view;
    }
}
?>