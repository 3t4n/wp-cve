<?php
use WPPayForm\Framework\Support\Arr;
use WPPayForm\App\Services\GlobalTools;

$current_user = wp_get_current_user();
$user_email = '';
$user_name = '';
$user_from = '';
// dd($current_user);
if ($current_user) {
    $user_email = $current_user->data->user_email;
    $user_name = $current_user->data->display_name;
    $user_from = $current_user->data->user_registered;
    $dateTime = new DateTime($user_from);
    // Get the user from date with Day Month Year format
    $user_from = $dateTime->format('l F Y');
}

$read_entry = Arr::get($permissions, 'read_entry');
$read_subscription_entry = Arr::get($permissions, 'read_subscription_entry');
$can_sync_subscription_billings = Arr::get($permissions, 'can_sync_subscription_billings');
$cancel_subscription = Arr::get($permissions, 'cancel_subscription');
?>

<div class="wpf-user-dashboard">
    <div class="wpf-user-profile">
        <div class="wpf-user-avatar">
            <?php echo get_avatar($user_email, 96); ?>
        </div>
        <div class="wpf-user-info">
            <div class="wpf-user-name">
                <p>
                    <?php echo $user_name ?>
                </p>
            </div>
            <div class="wpf-sub-info">
                <div class="wpf-info-item">
                    <span class="dashicons dashicons-email"></span>
                    <span>
                        <?php echo $user_email ?>
                    </span>
                </div>
                <div class="wpf-info-item">
                    <span class="dashicons dashicons-calendar"></span>
                    <span>
                        Registered since - <?php echo $user_from ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php if ($read_entry == 'yes' || $read_subscription_entry == 'yes') { ?>
        <div class="wpf-user-content">
            <div class="wpf-menu">
                <div class="wpf-menu-item" id="wpf-user-dashboard">
                    <span class="dashicons dashicons-admin-home"></span>
                    <span>Dashboard</span>
                </div>
                <?php if ($read_subscription_entry == 'yes'): ?>
                    <div class="wpf-menu-item" id="wpf-subscription">
                        <span class="dashicons dashicons-list-view"></span>
                        <span>Manage Subscription</span>
                    </div>
                <?php endif ?>
            </div>
            <div class="wpf-content wpf-dashboard" id="content-wpf-user-dashboard">
                <div class="wpf-user-stats wpf-dashboard-card">
                    <div class="wpf-stats-head">
                        <span class="dashicons dashicons-analytics"></span>
                        Your Submission Stats
                    </div>
                    <div class="wpf-stats-card">
                        <div class="overview-card">
                            <div id="wpf_toal_amount_modal" class="wpf-dashboard-modal">
                                <!-- Modal content -->
                                <div class="modal-content max-width-340">
                                    <span class="wpf-close">&times;</span>
                                    <?php foreach ($payment_total as $total_key => $payment_total_amount): ?>
                                        <p>
                                            <?php echo $payment_total_amount / 100 ?>
                                            <?php echo $total_key ?>
                                        </p>
                                    <?php endforeach ?>
                                </div>
                            </div>
                            <div class="info">
                                <h4 class="h4">
                                    <?php echo Arr::get(array_values($payment_total), '0')/ 100 ?>
                                    <?php echo key($payment_total); ?>
                                </h4>
                                <p class="wpf_toal_amount_btn" data-modal_id="wpf_toal_amount_modal">Expend All</p>
                                <span data-v-5e7a3b24="">Total Spent</span>
                            </div>
                            <div data-v-5e7a3b24="" class="icon">
                                <img class="spent" src="<?php echo WPPAYFORM_URL . "assets/images/dashboard/spent.svg" ?>"
                                    alt="total-spent" />
                            </div>
                        </div>
                        <?php if ($read_entry == 'yes'): ?>
                            <div class="overview-card">
                                <div class="info">
                                    <h4 class="h4">
                                        <?php echo count(Arr::get($donationItems, 'orders', [])) ?>
                                    </h4>
                                    <span data-v-5e7a3b24="">Total Orders</span>
                                </div>
                                <div data-v-5e7a3b24="" class="icon">
                                    <img class="order" src="<?php echo WPPAYFORM_URL . "assets/images/dashboard/order.svg" ?>"
                                        alt="order" />
                                </div>
                            </div>
                        <?php endif ?>
                        <?php if ($read_subscription_entry == 'yes'): ?>
                            <div class="overview-card">
                                <div class="info">
                                    <h4 class="h4">
                                        <?php echo count(Arr::get($donationItems, 'subscriptions', [])) ?>
                                    </h4>
                                    <span data-v-5e7a3b24="">Total Subscription</span>
                                </div>
                                <div data-v-5e7a3b24="" class="icon">
                                    <img class="subscription"
                                        src="<?php echo WPPAYFORM_URL . "assets/images/dashboard/subscription.svg" ?>"
                                        alt="subscription" />
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
                <div class="wpf-submission-table wpf-dashboard-card">
                    <div class="wpf-submission-head">
                        <span class="dashicons dashicons-calendar"></span>
                        Your Submissions
                    </div>
                    <div class="wpf-user-dashboard-table">
                        <div class="wpf-user-dashboard-table__header">
                            <div class="wpf-user-dashboard-table__column">ID</div>
                            <div class="wpf-user-dashboard-table__column">Amount</div>
                            <div class="wpf-user-dashboard-table__column">Date</div>
                            <div class="wpf-user-dashboard-table__column">Status</div>
                            <div class="wpf-user-dashboard-table__column">Payment Method</div>
                            <div class="wpf-user-dashboard-table__column">Action</div>
                        </div>
                        <div class="wpf-user-dashboard-table__rows">
                            <?php
                            $i = 0;
                            foreach (Arr::get($donationItems, 'entries', []) as $donationIndex => $donationItem):
                                $paymentTotal = Arr::get($donationItem, 'payment_total', 0);
                                $i++;
                                ?>
                                <div class=" wpf-user-dashboard-table__row">
                                    <div id="<?php echo 'wpf_toal_amount_modal' . $i ?>" class="wpf-dashboard-modal">
                                        <!-- Modal content -->
                                        <div class="modal-content">
                                            <div class="submission-modal">    
                                                <span class="wpf-close">&times;</span>
                                                <?php
                                                $receiptHandler = new \WPPayForm\App\Modules\Builder\PaymentReceipt();
                                                echo $receiptHandler->render($donationItem['id']);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" wpf-user-dashboard-table__column">
                                        #
                                        <?php echo Arr::get($donationItem, 'id', '') ?>
                                    </div>
                                    <div class=" wpf-user-dashboard-table__column">
                                        <?php echo $paymentTotal / 100 ?>
                                        <?php echo Arr::get($donationItem, 'currency', '') ?>
                                    </div>
                                    <div class="wpf-user-dashboard-table__column">
                                        <?php echo GlobalTools::convertStringToDate(Arr::get($donationItem, 'created_at', '')) ?>
                                    </div>
                                    <div class="wpf-user-dashboard-table__column">
                                        <span
                                            class="wpf-payment-status <?php echo Arr::get($donationItem, 'payment_status', '') ?>">
                                            <?php echo Arr::get($donationItem, 'payment_status', '') ?>
                                        </span>
                                    </div>
                                    <div class="wpf-user-dashboard-table__column">
                                        <?php echo Arr::get($donationItem, 'payment_method', '') ?>
                                    </div>
                                    <div class="wpf-user-dashboard-table__column">
                                        <span class="wpf-sub-id wpf_toal_amount_btn"
                                            data-modal_id="<?php echo 'wpf_toal_amount_modal' . $i ?>">
                                            View Receipt <span class="dashicons dashicons-arrow-right-alt"></span>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="wpf-content" id="wpf-donor-history">Donor history</div> -->
            <div class="wpf-content wpf-dashboard" id="content-wpf-subscription">
                <div class="wpf-submission-table wpf-dashboard-card">
                    <div class="wpf-submission-head">
                        <span class="dashicons dashicons-calendar"></span>
                        Your Subscription
                    </div>
                    <div class="wpf-user-dashboard-table">
                        <div class="wpf-user-dashboard-table__header">
                            <div style="flex: 2" class="wpf-user-dashboard-table__column">Plan</div>
                            <div class="wpf-user-dashboard-table__column">Billing Time</div>
                            <div class="wpf-user-dashboard-table__column">Status</div>
                            <div class="wpf-user-dashboard-table__column">Interval</div>
                            <div class="wpf-user-dashboard-table__column">Action</div>
                        </div>
                        <div class="wpf-user-dashboard-table__rows">
                            <?php
                            $i = 1000;
                            foreach (Arr::get($donationItems, 'subscriptions', []) as $donationKey => $donationItem):
                                $i++;
                                ?>
                                <div class=" wpf-user-dashboard-table__row">
                                    <div id="<?php echo 'wpf_toal_amount_modal' . $i ?>" class="wpf-dashboard-modal">
                                        <!-- Modal content -->
                                        <div class="modal-content">
                                            <div class="submission-modal">
                                                <span class="wpf-close">&times;</span>
                                                <div class="wpf-user-dashboard-table-container" style="padding-top: 21px">
                                                    <?php
                                                    $receiptHandler = new \WPPayForm\App\Modules\Builder\SubscriptionEntries();
                                                    $isNotOfflinePayment = Arr::get($donationItem, 'submission.submission.payment_method', '') != 'offline';
                                                    $planName = Arr::get($donationItem, 'plan_name', '');
                                                    $submission_hash = Arr::get($donationItem, 'submission.submission.submission_hash', '');
                                                    // dd($donationItem);
                                                    echo $receiptHandler->render(
                                                        Arr::get($donationItem, 'related_payments', []),
                                                        Arr::get($donationItem, 'status', 'active'),
                                                        Arr::get($donationItem, 'form_id'),
                                                        Arr::get($donationItem, 'submission.submission.submission_hash', ''),
                                                        $can_sync_subscription_billings,
                                                        $isNotOfflinePayment,
                                                        $planName
                                                    );
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style=" flex: 2" class="wpf-user-dashboard-table__column">
                                        <?php echo $planName ?>
                                    </div>
                                    <div class="wpf-user-dashboard-table__column">
                                        <?php echo Arr::get($donationItem, 'bill_times', '') == 0 ? 'Infinity' : Arr::get($donationItem, 'bill_times', '') ?>
                                    </div>
                                    <div class="wpf-user-dashboard-table__column">
                                        <span class="wpf-payment-status <?php echo Arr::get($donationItem, 'status', '') ?>">
                                            <?php echo Arr::get($donationItem, 'status', '') ?>
                                        </span>
                                    </div>
                                    <div class="wpf-user-dashboard-table__column">
                                        <?php echo Arr::get($donationItem, 'billing_interval', '') ?>
                                    </div>
                                    <div class="wpf-user-dashboard-table__column">
                                        <div id="<?php echo 'wpf_toal_amount_cancel_modal' . $i ?>"
                                            class="wpf-dashboard-modal wpf-confirmation-modal">
                                            <!-- Modal content -->
                                            <div class="modal-content">
                                                <div class="modal-title">
                                                    <p class="title">Confirm subscription cancellation</p>
                                                    <span class="wpf-close">&times;</span>
                                                </div>
                                                <div class="modal-body">
                                                    <span class="dashicons dashicons-info-outline wpf-info-icon"></span>
                                                    <h4>Are you sure about to cancel this subscription ?</h4>
                                                    <p>This will also cancel the subscription at stripe. So no further payment
                                                        will be processed</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="modal-btn wpf-cancel">Close</button>
                                                    <button
                                                        class="modal-btn wpf-success wpf-confirm-submission-cancel"
                                                        data-form_id="<?php echo $donationItem['form_id'] ?>"
                                                        data-submission_hash="<?php echo  Arr::get($donationItem, 'submission.submission.submission_hash', '') ?>"
                                                        data-subscription_id="<?php echo $donationItem['id'] ?>">Yes,
                                                        Cancel This Subscription
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpf-subscription-action-btn">
                                            <?php if ($isNotOfflinePayment && $cancel_subscription == 'yes'): ?>
                                                <div class="wpf-cancel-subscription">
                                                    <svg
                                                        class="wpf-cancel-subscription-btn <?php echo Arr::get($donationItem, 'status', '') == 'active' ? 'active' : '' ?>"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 24 24" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"
                                                        ></path>
                                                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z">
                                                        </path>
                                                    </svg>
                                                    <button data-modal_id="<?php echo 'wpf_toal_amount_cancel_modal' . $i ?>" class="wpf-cancel-confirm-button">Cancel</button>
                                                </div>
                                            <?php endif ?>
                                            <span class="wpf-sub-id wpf_toal_amount_btn"
                                                data-modal_id="<?php echo 'wpf_toal_amount_modal' . $i ?>">
                                                <span class="dashicons dashicons-visibility"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div style="padding: 20px;">
            You have not any access for read your entries from the administration
        </div>
    <?php } ?>
</div>