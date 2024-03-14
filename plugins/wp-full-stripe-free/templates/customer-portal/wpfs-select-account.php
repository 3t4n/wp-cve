<?php
/**
 * @var MM_WPFS_CustomerPortalModel $model
 */
?>
<div id="wpfs-select-account-container" class="container">
    <div class="wpfs-form wpfs-w-60">
        <div class="wpfs-form-title"><?php /* translators: Page title on the 'Select account' page of the Customer portal */ esc_html_e( 'Select a customer to proceed', 'wp-full-stripe' ); ?></div>
        <div class="wpfs-form-lead">
            <div class="wpfs-form-description wpfs-form-description--sm">
                <?php echo sprintf( /* translators: p1: Customer's email address */ __( 'Several Stripe customers are associated with the email address <strong>%s</strong>.', 'wp-full-stripe' ), $model->getAccountEmail() ); ?>
                <?php if ( $model->getAuthenticationType() == MM_WPFS_CustomerPortalModel::AUTHENTICATION_TYPE_PLUGIN ): ?>
                    <a href="" id="wpfs-anchor-logout" ><?php /* translators: Link text for log out link */ esc_html_e( 'Log out', 'wp-full-stripe' ); ?></a>
                <?php endif; ?>
        </div>
        <div class="wpfs-form-subtitle"><?php /* translators: Page subtitle on the 'Select account' page of the Customer portal */ esc_html_e( 'Available customers', 'wp-full-stripe' ); ?></div>
        <form>
            <div class="wpfs-account-selector-list">
                <?php
                /* @var $account MM_WPFS_CustomerPortalAccount */
                foreach ( $model->getAccounts() as $account ) { ?>
                    <div class="wpfs-account-selector-item">
                        <div class="wpfs-account-selector-data">
                            <div class="wpfs-account-selector-name">
                                <?php
                                    $subscriptionsLabel = $account->getNumberOfSubscriptions() > 0 ?
                                        sprintf( /* translators: Number of subscriptions displayed in the account selector */
                                                _n( "%s subscription", "%s subscriptions", $account->getNumberOfSubscriptions(), 'wp-full-stripe' ), number_format_i18n( $account->getNumberOfSubscriptions() ))
                                                :
                                                /* translators: Label displayed in the account selector when the account has no subscriptions */
                                                __( 'No subscription','wp-full-stripe' );
                                ?>
                                <strong><?php echo $account->getName(); ?></strong> (<?php echo $subscriptionsLabel ?>)
                            </div>
                            <div class="wpfs-account-selector-meta"><?php echo sprintf( __('Created at %s', 'wp-full-stripe'), $account->getCreatedAtLabel() ); ?></div>
                        </div>
                        <div class="wpfs-account-selector-actions">
                            <a class="wpfs-btn wpfs-btn-link wpfs-btn-link--bold wpfs-account-selector" href="" data-customer-id="<?php echo $account->getStripeCustomerId() ?>"><?php esc_html_e( 'Select account', 'wp-full-stripe' ); ?></a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </form>
    </div>
</div>
