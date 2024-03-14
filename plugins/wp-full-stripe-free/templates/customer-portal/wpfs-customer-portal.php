<?php
/**
 * @var MM_WPFS_CustomerPortalModel $model
 */
?>
<div id="wpfs-manage-subscriptions-container" class="wpfs-form wpfs-w-60">
	<div class="wpfs-form-title"><?php esc_html_e( 'Manage your account', 'wp-full-stripe' ); ?></div>
	<div class="wpfs-form-lead">
		<div class="wpfs-form-description wpfs-form-description--sm">
            <?php
                $orNeeded = $model->getAuthenticationType() == MM_WPFS_CustomerPortalModel::AUTHENTICATION_TYPE_PLUGIN && $model->isAccountSelectorNeeded();
                $commaNeeded = $model->getAuthenticationType() == MM_WPFS_CustomerPortalModel::AUTHENTICATION_TYPE_PLUGIN && $model->isAccountSelectorNeeded();
            ?>
			<?php echo sprintf( /* translators: p1: Subscriber's email address p2: Stripe customer id */ __( 'You can manage the payment method and subscriptions associated with <strong title="%2$s">%1$s</strong>.', 'wp-full-stripe' ), $model->getCustomerEmail(), $model->getStripeCustomer()->id ); ?>
            <?php if ( $model->isAccountSelectorNeeded() ) { ?>
                    <a href="" id="wpfs-anchor-select-account"><?php /* translators: Link text for select account link */ esc_html_e( 'Select account', 'wp-full-stripe' ); ?></a>
            <?php } ?>
            <?php if ( $orNeeded ) { ?>
            <?php /* translators: We must display 'or' when there are two possible actions */ esc_html_e( 'or', 'wp-full-stripe' ); ?>
            <?php } ?>
            <?php if ( $model->getAuthenticationType() == MM_WPFS_CustomerPortalModel::AUTHENTICATION_TYPE_PLUGIN ): ?>
                <a href="" id="wpfs-anchor-logout" ><?php /* translators: Link text for log out link */ esc_html_e( 'Log out', 'wp-full-stripe' ); ?></a>
            <?php endif; ?>
            <?php if ( $commaNeeded ) { ?>
            .
            <?php } ?>
		</div>
	</div>
	<?php MM_WPFS_ViewTemplateUtils::fullstripe_customer_portal_header( $model ); ?>
	<div class="wpfs-form-subtitle"><?php /* translators: Label for the Update card section */ esc_html_e( 'Credit/debit card', 'wp-full-stripe' ); ?></div>
	<form id="wpfs-default-card-form">
		<div class="wpfs-credit-cards">
			<div class="wpfs-credit-card">
				<?php if ( empty( $model->getCardNumber() ) ): ?>
					<div><?php /* translators: Message stating that the Stripe customer doesn't have card set as payment method. */ esc_html_e( 'You don\'t have a default card.', 'wp-full-stripe' ); ?></div>
				<?php else: ?>
					<div class="wpfs-credit-card-logo">
						<img src="<?php echo esc_attr( $model->getCardImageUrl() ); ?>" alt="<?php echo esc_attr( $model->getCardName() ); ?>">
					</div>
					<div class="wpfs-credit-card-data">
						<div class="wpfs-credit-card-name"><?php echo esc_html( $model->getCardName() . ' ' . $model->getFormattedCardNumber() ); ?></div>
						<div class="wpfs-credit-card-expires">
							<?php /* translators: Column header of the card expiry date */ esc_html_e( 'Expires', 'wp-full-stripe' ); ?>
							<br><?php echo esc_html( $model->getExpiration() ); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<a id="wpfs-anchor-update-card" class="wpfs-btn wpfs-btn-link wpfs-btn-link--bold"><?php /* translators: Button label for updating the card stored in Stripe */ esc_html_e( 'Update card', 'wp-full-stripe' ); ?></a>
		</div>
	</form>
	<form id="wpfs-update-card-form" style="display: none;">
		<div class="wpfs-form-group wpfs-w-45">
			<div class="wpfs-form-control" id="wpfs-card" data-toggle="card"></div>
		</div>
		<div class="wpfs-form-actions wpfs-mt-3 wpfs-mb-4">
			<button class="wpfs-btn wpfs-btn-primary wpfs-mr-2" type="submit"><?php /* translators: Button label for updating the card stored in Stripe */ esc_html_e( 'Update card', 'wp-full-stripe' ); ?></button>
			<a id="wpfs-anchor-discard-card-changes" class="wpfs-btn wpfs-btn-link"><?php /* translators: Button label for discarding card update */ esc_html_e( 'Discard', 'wp-full-stripe' ); ?></a>
		</div>
	</form>
	<div class="wpfs-form-subtitle" id="wpfs-subscriptions-subtitle"><?php /* translators: Label for the Subscriptions section */ esc_html_e( 'Subscriptions', 'wp-full-stripe' ); ?></div>
	<form id="wpfs-cancel-subscription-form">
		<div id="wpfs-subscriptions-table" class="wpfs-subscriptions"></div>
		<div id="wpfs-subscriptions-actions" class="wpfs-form-actions">
			<button id="wpfs-button-cancel-subscription" class="wpfs-btn wpfs-btn-primary" type="submit" disabled><?php /* translators: Button label for cancelling a subscription */ esc_html_e( 'Cancel subscription', 'wp-full-stripe' ); ?></button>
		</div>
	</form>
    <div class="wpfs-form-subtitle" id="wpfs-invoices-subtitle"><?php /* translators: Label for the Invoices section */ esc_html_e( 'Invoices', 'wp-full-stripe' ); ?></div>
    <form id="wpfs-view-invoices-form">
        <div id="wpfs-invoices-table" class="wpfs-invoices"></div>
    </form>
	<?php MM_WPFS_ViewTemplateUtils::fullstripe_customer_portal_footer( $model ); ?>
</div>
<script type="text/template" id="wpfs-invoice-show-row">
    <div class="wpfs-icon-accounting-document wpfs-invoice-icon"></div>
    <div class="wpfs-invoice-data">
        <div class="wpfs-invoice-name">
            <%- invoiceNumber %>&nbsp;&nbsp;<span class="wpfs-invoice-date">(<%- priceLabel %> / <%- created %>)</span>
        </div>
        <div class="wpfs-invoice-meta">
             <%- lineItemsLabel %>
        </div>
        <div class="wpfs-invoice-actions">
            <a class="wpfs-btn wpfs-btn-link wpfs-btn-link--bold" href="<%- invoiceUrl %>" target="_blank"><?php /* translators: Button label for downloading an invoice */ esc_html_e( 'Download', 'wp-full-stripe' ); ?></a>
        </div>
    </div>
</script>
<script type="text/template" id="wpfs-invoices-actions-show-all" >
    <a id="wpfs-invoices-view-toggle" class="wpfs-btn wpfs-btn-link wpfs-btn-link--bold"><?php /* translators: Button label for showing all invoices, not just the latest ones. */ esc_html_e( 'Show all invoices', 'wp-full-stripe' ); ?></a>
</script>
<script type="text/template" id="wpfs-invoices-actions-show-all-loading" >
    <a class="wpfs-btn wpfs-btn-link wpfs-btn-link--bold wpfs-btn-link--loader" disabled>
        <?php
        /* translators: Button label for showing all invoices, not just the latest ones. */
        esc_html_e( 'Show all invoices', 'wp-full-stripe' ); ?>
    </a>
</script>
<script type="text/template" id="wpfs-invoices-actions-show-latest" >
    <a id="wpfs-invoices-view-toggle" class="wpfs-btn wpfs-btn-link wpfs-btn-link--bold"><?php /* translators: Button label for showing the latest invoices. */ esc_html_e( 'Show only latest invoices', 'wp-full-stripe' ); ?></a>
</script>
<script type="text/template" id="wpfs-invoices-actions-show-latest-loading" >
    <a class="wpfs-btn wpfs-btn-link wpfs-btn-link--bold wpfs-btn-link--loader" disabled>
        <?php
        /* translators: Button label for showing the latest invoices. */
        esc_html_e( 'Show only latest invoices', 'wp-full-stripe' ); ?>
    </a>
</script>
<script type="text/template" id="wpfs-subscription-show-row">
	<div class="wpfs-form-check">
		<input type="checkbox" class="wpfs-form-check-input" id="<%- idAttribute %>" name="<%- nameAttribute %>" value="<%- id %>">
		<label class="wpfs-form-check-label" for="<%- idAttribute %>">
			<div class="wpfs-subscription-data">
				<div class="wpfs-subscription-name">
					<%- planLabel %> - <span class="wpfs-subscription-status <%- statusClass %>"><%- status %></span>
				</div>
            <% if (priceAndIntervalLabel) { %>
				<div class="wpfs-subscription-meta"><%- priceAndIntervalLabel %>
					• <?php
                    /* translators: Label for the creation date of subscription */
                    esc_html_e( 'Created on', 'wp-full-stripe' ); ?> <%- created %>
                    <% if (cancelAtPeriodEnd) { %>
                    • <?php
                    /* translators: Label for the cancellation date of subscription */
                    esc_html_e( 'Cancels on', 'wp-full-stripe' ); ?> <%- canceled %>
                    <% } %>
				</div>
				<div class="wpfs-subscription-actions">
                    <% if (cancelAtPeriodEnd) { %>
                    <a class="wpfs-btn wpfs-btn-link wpfs-btn-link--bold wpfs-subscription-dont-cancel-action" href="" data-wpfs-subscription-id="<%- id %>">
                        <?php
                        /* translators: Button label for activating subscription again */
                        esc_html_e( 'Don\'t cancel', 'wp-full-stripe' ); ?>
                    </a>
                    <% } else {
                        let isUpgradeable = false;
                        if (wpfsCustomerPortalSettings.preferences.letSubscribersUpdowngradeSubscriptions) {
                            if (!_.isEmpty( availablePlans )) {
                                isUpgradeable = true;
                            }
                        }
                        if (allowMultipleSubscriptions || isUpgradeable) { %>
					<a class="wpfs-btn wpfs-btn-link wpfs-btn-link--bold wpfs-subscription-update-action" href="" data-wpfs-subscription-id="<%- id %>">
						<?php
                        /* translators: Button label for changing subscription */
                        esc_html_e( 'Change subscription', 'wp-full-stripe' ); ?>
					</a>
	    				<% } %>
                        <% if (wpfsCustomerPortalSettings.preferences.letSubscribersCancelSubscriptions) { %>
                    <a class="wpfs-btn wpfs-btn-link wpfs-btn-link--bold wpfs-subscription-cancel-action" href="" data-wpfs-subscription-id="<%- id %>">
						<?php
                        /* translators: Button label for cancelling subscription */
                        esc_html_e( 'Cancel', 'wp-full-stripe' ); ?>
					</a>
                        <% } %>
                    <% } %>
				</div>
            <% } else { %>
                <div class="wpfs-subscription-meta">
                    <?php esc_html_e( 'Created on', 'wp-full-stripe' ); ?> <%- created %>
                </div>
            <% } %>
			</div>
		</label>
	</div>
</script>
<script type="text/template" id="wpfs-subscription-update-row">
    <div class="wpfs-subscription-name"><?php esc_html_e( 'Change subscription', 'wp-full-stripe' ); ?></div>
        <% let isUpgradeable = false;
        if (wpfsCustomerPortalSettings.preferences.letSubscribersUpdowngradeSubscriptions) {
            if (!_.isEmpty( availablePlans )) {
                isUpgradeable = true;
            }
        }

        if ( isUpgradeable ) { %>
        <div class="wpfs-form-group">
            <label class="wpfs-form-label" for="name"><?php esc_html_e( 'Subscription plan', 'wp-full-stripe' ); ?></label>
            <div class="wpfs-ui wpfs-form-select">
                <div class="wpfs-stepper">
                    <select name="wpfs-subscription-plan-name" data-toggle="selectmenu">
                        <% _.each(availablePlans, function(plan) { %>
                            <option value="<%- plan.id %>" data-wpfs-plan-name="<%- plan.product.name %>" data-wpfs-price-and-interval-label="<%- plan.priceAndIntervalLabel %>" data-wpfs-plan-summary-label-singular="<%- plan.summaryLabelSingular %>" data-wpfs-plan-summary-label-plural="<%- plan.summaryLabelPlural %>" <% if (planId == plan.id) { %>selected<% } %>><%- plan.product.name %> - <%- plan.priceAndIntervalLabel %></option>
                        <% }); %>
                    </select>
                </div>
            </div>
        </div>
        <% } else { %>
            <input type="hidden" name="wpfs-subscription-plan-current" data-wpfs-plan-name="<%- planName %>" data-wpfs-price-and-interval-label="<%- priceAndIntervalLabel %>" data-wpfs-plan-summary-label-singular="<%- summaryLabelSingular %>" data-wpfs-plan-summary-label-plural="<%- summaryLabelPlural %>"/>
        <% } %>
        <% if (allowMultipleSubscriptions) { %>
        <div class="wpfs-form-group">
            <label class="wpfs-form-label" for="name"><?php
                /* translators: Field label for changing subscription quantity */
                esc_html_e( 'Subscription quantity', 'wp-full-stripe' ); ?></label>
            <div class="wpfs-stepper wpfs-w-15">
                <input class="wpfs-form-control" type="text" name="wpfs-subscription-plan-quantity" value="<%- planQuantity %>" data-toggle="stepper" data-default-value="<%- planQuantity %>"
                <% if (minimumPlanQuantity > 0) { %>data-min="<%- minimumPlanQuantity %>"<% } else { %>data-min="1"<% } %>
                <% if (maximumPlanQuantity > 0) { %>data-max="<%- maximumPlanQuantity %>"<% } %>>
            </div>
        </div>
        <% } %>
        <div class="wpfs-subscription-summary wpfs-subscription-summary--hide">
            <div class="wpfs-subscription-summary-title"><?php
                /* translators: Label for subscription summary with price and interval */
                esc_html_e( 'Summary:', 'wp-full-stripe' ); ?></div>
            <div class="wpfs-subscription-summary-description wpfs-subscription-summary-description--lower"></div>
        </div>
        <div class="wpfs-form-actions wpfs-mt-3 wpfs-mb-4">
            <button class="wpfs-btn wpfs-btn-primary wpfs-mr-2 wpfs-subscription-button-update" type="submit" disabled><?php
                /* translators: Button label for updating subscription */
                esc_html_e( 'Update subscription', 'wp-full-stripe' ); ?></button>
            <a href="" class="wpfs-btn wpfs-btn-link wpfs-subscription-link-cancel-update"><?php /* Translators: Button label for discarding subscription update */ esc_html_e( 'Cancel', 'wp-full-stripe' ); ?></a>
        </div>
</script>
<script type="text/template" id="wpfs-subscription-empty-subscription-list">
    <?php esc_html_e( 'You don\'t have any subscriptions.', 'wp-full-stripe' ); ?>
</script>
<script type="text/template" id="wpfs-subscription-empty-invoice-list">
    <?php esc_html_e( 'You don\'t have any finalized invoices.', 'wp-full-stripe' ); ?>
</script>
<script type="text/template" id="wpfs-subscription-update-success-message">
	<strong><?php
        /* translators: Banner message for successfully updating subscription quantity */
        esc_html_e( 'Updated subscription.', 'wp-full-stripe' ); ?></strong>
</script>
<script type="text/template" id="wpfs-subscription-update-error-message">
	<strong><?php esc_html_e( 'We couldn\'t update subscription. Please try again', 'wp-full-stripe' ); ?></strong>
</script>
<script type="text/template" id="wpfs-subscription-cancel-success-message">
	<strong><?php
        /* translators: Banner message for successfully cancelling subscription */
        esc_html_e( 'Cancelled subscription.', 'wp-full-stripe' ); ?></strong>
</script>
<script type="text/template" id="wpfs-subscription-activate-success-message">
    <strong><?php
        /* translators: Banner message for successfully activating subscription */
        esc_html_e( 'Activated subscription.', 'wp-full-stripe' ); ?></strong>
</script>
<script type="text/template" id="wpfs-subscription-cancel-error-message">
	<strong><?php esc_html_e( 'Failed to cancel subscription, try again.', 'wp-full-stripe' ); ?></strong>
</script>
<script type="text/template" id="wpfs-subscription-activate-error-message">
    <strong><?php esc_html_e( 'Failed to activate subscription, try again.', 'wp-full-stripe' ); ?></strong>
</script>
