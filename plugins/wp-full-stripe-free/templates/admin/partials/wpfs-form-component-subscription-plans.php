<?php
/** @var $view MM_WPFS_Admin_SubscriptionFormView */
/** @var $form */
/** @var $data */
?>
<div class="wpfs-form-group">
    <label for="" class="wpfs-form-label"><?php esc_html_e( 'Available plans', 'wp-full-stripe-admin' ); ?></label>
    <div id="<?php echo MM_WPFS_Admin_SubscriptionFormViewConstants::FIELD_FORM_RECURRING_PRODUCTS_ERROR; ?>" class="wpfs-field-list" data-field-name="<?php echo MM_WPFS_Admin_SubscriptionFormViewConstants::FIELD_FORM_RECURRING_PRODUCTS_ERROR; ?>">
        <div id="wpfs-recurring-products" class="wpfs-field-list__list js-sortable ui-sortable"></div>
        <a class="wpfs-field-list__add js-add-recurring-product" href="">
            <div class="wpfs-icon-add-circle wpfs-field-list__icon"></div>
            <?php esc_html_e( 'Add plan from Stripe', 'wp-full-stripe-admin' ); ?>
        </a>
    </div>
</div>
<input id="<?php $view->recurringProducts()->id(); ?>" name="<?php $view->recurringProducts()->name(); ?>" value="" <?php $view->recurringProducts()->attributes(); ?>>
<script type="text/javascript">
    var wpfsRecurringProducts = <?php echo json_encode( $data->plans ); ?>;
    var wpfsCurrencies = <?php echo json_encode( MM_WPFS_Currencies::getAvailableCurrencies() ); ?>;
</script>
<script type="text/template" id="wpfs-modal-remove-recurring-product">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><%- confirmationMessage %></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-danger js-remove-recurring-product-dialog"><?php _e( 'Remove plan', 'wp-full-stripe-admin'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Keep plan', 'wp-full-stripe-admin' ); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-recurring-product-template">
    <div class="wpfs-icon-expand-vertical-left-right wpfs-field-list__icon"></div>
    <div class="wpfs-field-list__info">
        <div class="wpfs-field-list__title"><%= planDescriptionLine1 %></div>
        <div class="wpfs-field-list__meta"><%= planDescriptionLine2 %></div>
    </div>
    <div class="wpfs-field-list__actions">
        <button class="wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-edit-recurring-product-properties">
            <span class="wpfs-icon-edit"></span>
        </button>
        <button class="wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-remove-recurring-product">
            <span class="wpfs-icon-trash"></span>
        </button>
    </div>
</script>
<div id="wpfs-add-recurring-product-dialog" class="wpfs-dialog-content" title="<?php esc_html_e( 'Add plan from Stripe', 'wp-full-stripe-admin'); ?>">
    <div class="wpfs-dialog-loader js-add-product-step-1">
        <div class="wpfs-dialog-loader__loader"></div>
        <p class="wpfs-dialog-content-text">
            <?php esc_html_e( 'Keep tight, we are retrieving the subscription plans from Stripe. It might take a few seconds.', 'wp-full-stripe-admin'); ?>
        </p>
    </div>

    <div class="wpfs-dialog-scrollable js-add-product-step-2">
        <div class="wpfs-form-group">
            <input class="wpfs-form-control js-stripe-product-autocomplete" type="text" placeholder="<?php esc_html_e( 'Search plans...', 'wp-full-stripe-admin'); ?>">
            <script type="text/template">
                <div class="wpfs-form-check wpfs-stripe-product-autocomplete__item">
                    <input type="radio" class="wpfs-form-check-input" id="stripe-plan-autocomplete-{value}" value="{value}" name="plan">
                    <label class="wpfs-form-check-label wpfs-stripe-plan-autocomplete__label" for="stripe-plan-autocomplete-{value}">
                        {label} - <span class="wpfs-stripe-plan-autocomplete__price">{price}</span>
                        <div class="wpfs-stripe-plan-autocomplete__desc">{description}</div>
                    </label>
                </div>
            </script>
        </div>
    </div>
    <div class="wpfs-dialog-content-actions js-add-product-step-2">
        <button class="wpfs-btn wpfs-btn-primary js-dialog-select-recurring-product"><?php esc_html_e( 'Select plan', 'wp-full-stripe-admin'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php esc_html_e( 'Discard', 'wp-full-stripe-admin'); ?></button>
    </div>

    <div id="wpfs-add-recurring-product-properties-container" class="wpfs-dialog-scrollable js-add-product-step-3">
    </div>
    <div class="wpfs-dialog-content-actions js-add-product-step-3">
        <button class="wpfs-btn wpfs-btn-primary js-dialog-add-recurring-product"><?php esc_html_e( 'Add plan', 'wp-full-stripe-admin'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php esc_html_e( 'Discard', 'wp-full-stripe-admin'); ?></button>
    </div>
</div>
<script type="text/template" id="wpfs-add-recurring-product-properties-template">
    <div class="wpfs-dialog-content-text">
        <%= tsprintf( wpfsAdminL10n.setAdditionalPlanPropertiesLabel, name ) %>
    </div>
    <div class="wpfs-form-group">
        <label for="wpfs-plan-setup-fee--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>" class="wpfs-form-label"><?php esc_html_e( 'Setup fee', 'wp-full-stripe-admin' ); ?></label>
        <div class="wpfs-input-group wpfs-input-group--sm">
            <% if ( wpfsAdminSettings.preferences.currencyShowIdentifierOnLeft == '1' ) { %>
            <div class="wpfs-input-group-prepend">
                <span class="wpfs-input-group-text"><%= getCurrencySymbol( currency ) %></span>
            </div>
            <% } %>
            <input id="wpfs-plan-setup-fee--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>" class="wpfs-input-group-form-control" type="text" name="wpfs-plan-setup-fee" value="0">
            <% if ( wpfsAdminSettings.preferences.currencyShowIdentifierOnLeft == '0' ) { %>
            <div class="wpfs-input-group-append">
                <span class="wpfs-input-group-text"><%= getCurrencySymbol( currency ) %></span>
            </div>
            <% } %>
        </div>
    </div>
    <div class="wpfs-form-group">
        <label for="wpfs-plan-trial-period-days--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>" class="wpfs-form-label"><?php esc_html_e( 'Trial period', 'wp-full-stripe-admin' ); ?></label>
        <input id="wpfs-plan-trial-period-days--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>" class="wpfs-form-control wpfs-form-control--w120" type="text" placeholder="<?php esc_html_e( 'No trial', 'wp-full-stripe-admin' ); ?>" name="wpfs-plan-trial-period-days" value="0"> <span class="wpfs-typo-body wpfs-typo-body--gunmetal"><?php esc_html_e( 'days', 'wp-full-stripe-admin' ); ?></span>
    </div>
    <div class="wpfs-form-group">
        <label for="" class="wpfs-form-label"><?php esc_html_e( 'End subscription', 'wp-full-stripe-admin' ); ?></label>
        <div class="wpfs-form-check-list">
            <div class="wpfs-form-check">
                <input type="radio" class="wpfs-form-check-input" id="wpfs-end-subscription-customer-cancels--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>" name="wpfs-end-subscription" value="wpfs-end-subscription-customer-cancels" checked>
                <label class="wpfs-form-check-label" for="wpfs-end-subscription-customer-cancels--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>"><?php esc_html_e( 'When customer cancels it', 'wp-full-stripe-admin' ); ?></label>
            </div>
            <div class="wpfs-form-check">
                <input type="radio" class="wpfs-form-check-input" id="wpfs-end-subscription-after-x-occurrences--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>" name="wpfs-end-subscription" value="wpfs-end-subscription-after-x-occurrences">
                <label class="wpfs-form-check-label" for="wpfs-end-subscription-after-x-occurrences--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>">
                    <?php esc_html_e( 'After certain # of occurrences:', 'wp-full-stripe-admin' ); ?> <input id="wpfs-subscription-cancellation-count--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>" name="wpfs-subscription-cancellation-count" class="wpfs-form-control wpfs-form-control--w56" type="text" disabled value="1">
                </label>
            </div>
        </div>
    </div>
    <% if ( interval === 'month' && formLayout === 'inline' ) {  %>
    <div class="wpfs-form-group">
        <label for="" class="wpfs-form-label"><?php esc_html_e( 'Billing cycle starts', 'wp-full-stripe-admin' ); ?></label>
        <div class="wpfs-form-check-list">
            <div class="wpfs-form-check">
                <input type="radio" class="wpfs-form-check-input" id="wpfs-billing-cycle-customer-subscribed--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>" name="wpfs-billing-cycle" value="wpfs-billing-cycle-customer-subscribed" checked>
                <label class="wpfs-form-check-label" for="wpfs-billing-cycle-customer-subscribed--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>"><?php esc_html_e( 'On the day when customer subscribed', 'wp-full-stripe-admin' ); ?></label>
            </div>
            <div class="wpfs-form-check">
                <input type="radio" class="wpfs-form-check-input" id="wpfs-billing-cycle-on-this-day--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>" name="wpfs-billing-cycle" value="wpfs-billing-cycle-on-this-day">
                <label class="wpfs-form-check-label" for="wpfs-billing-cycle-on-this-day--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>">
                    <?php esc_html_e( 'On this day of the month:', 'wp-full-stripe-admin' ); ?> <input id="wpfs-billing-cycle-day--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>" name="wpfs-billing-cycle-day" class="wpfs-form-control wpfs-form-control--w56" type="text" disabled value="1">
                </label>
            </div>
        </div>
    </div>
    <div class="wpfs-form-group" id="wpfs-prorate-until-billing-anchor-day">
        <label for="" class="wpfs-form-label"><?php esc_html_e( 'Prorate until the billing anchor day?', 'wp-full-stripe-admin' ); ?></label>
        <div class="wpfs-form-check-list">
            <div class="wpfs-form-check">
                <input type="radio" class="wpfs-form-check-input" id="wpfs-prorate-until-billing-anchor-day-no--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>" name="wpfs-prorate-until-billing-anchor-day" value="0" checked>
                <label class="wpfs-form-check-label" for="wpfs-prorate-until-billing-anchor-day-no--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>"><?php esc_html_e( 'No', 'wp-full-stripe-admin' ); ?></label>
            </div>
            <div class="wpfs-form-check">
                <input type="radio" class="wpfs-form-check-input" id="wpfs-prorate-until-billing-anchor-day-yes--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>" name="wpfs-prorate-until-billing-anchor-day" value="1">
                <label class="wpfs-form-check-label" for="wpfs-prorate-until-billing-anchor-day-yes--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>"><?php esc_html_e( 'Yes', 'wp-full-stripe-admin' ); ?></label>
            </div>
        </div>
    </div>
    <% } else { %>
    <input type="hidden" id="wpfs-billing-cycle-customer-subscribed--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>" name="wpfs-billing-cycle" value="wpfs-billing-cycle-customer-subscribed">
    <input type="hidden" id="wpfs-billing-cycle-day--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>" name="wpfs-billing-cycle-day" value="0">
    <input type="hidden" id="wpfs-prorate-until-billing-anchor-day-no--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES; ?>" name="wpfs-prorate-until-billing-anchor-day" value="0">
    <% } %>
</script>

<script type="text/template" id="wpfs-edit-recurring-product-properties-template">
    <form class="wpfs-dialog-scrollable" data-wpfs-form-type="editProductProperties">
        <div class="wpfs-dialog-content-text">
            <%= tsprintf( wpfsAdminL10n.setAdditionalPlanPropertiesLabel, name ) %>
        </div>
        <div class="wpfs-form-group">
            <label for="wpfs-plan-setup-fee--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>" class="wpfs-form-label"><?php esc_html_e( 'Setup fee', 'wp-full-stripe-admin' ); ?></label>
            <div class="wpfs-input-group wpfs-input-group--sm">
                <% if ( wpfsAdminSettings.preferences.currencyShowIdentifierOnLeft == '1' ) { %>
                <div class="wpfs-input-group-prepend">
                    <span class="wpfs-input-group-text"><%= getCurrencySymbol( currency ) %></span>
                </div>
                <% } %>
                <input id="wpfs-plan-setup-fee--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>" class="wpfs-input-group-form-control" type="text" name="wpfs-plan-setup-fee" value="<%= formatAmount( setupFee, currency ) %>">
                <% if ( wpfsAdminSettings.preferences.currencyShowIdentifierOnLeft == '0' ) { %>
                <div class="wpfs-input-group-append">
                    <span class="wpfs-input-group-text"><%= getCurrencySymbol( currency ) %></span>
                </div>
                <% } %>
            </div>
        </div>
        <div class="wpfs-form-group">
            <label for="wpfs-plan-trial-period-days--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>" class="wpfs-form-label"><?php esc_html_e( 'Trial period', 'wp-full-stripe-admin' ); ?></label>
            <input id="wpfs-plan-trial-period-days--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>" class="wpfs-form-control wpfs-form-control--w120" type="text" placeholder="<?php esc_html_e( 'No trial', 'wp-full-stripe-admin' ); ?>" name="wpfs-plan-trial-period-days" value="<%= trialDays %>"> <span class="wpfs-typo-body wpfs-typo-body--gunmetal"><?php esc_html_e( 'days', 'wp-full-stripe-admin' ); ?></span>
        </div>
        <div class="wpfs-form-group">
            <label for="" class="wpfs-form-label"><?php esc_html_e( 'End subscription', 'wp-full-stripe-admin' ); ?></label>
            <div class="wpfs-form-check-list">
                <div class="wpfs-form-check">
                    <input type="radio" class="wpfs-form-check-input" id="wpfs-end-subscription-customer-cancels--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>" name="wpfs-end-subscription" value="wpfs-end-subscription-customer-cancels" <% if ( cancellationCount == 0 ) { %> checked <% } %>>
                    <label class="wpfs-form-check-label" for="wpfs-end-subscription-customer-cancels--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>"><?php esc_html_e( 'When customer cancels it', 'wp-full-stripe-admin' ); ?></label>
                </div>
                <div class="wpfs-form-check">
                    <input type="radio" class="wpfs-form-check-input" id="wpfs-end-subscription-after-x-occurrences--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>" name="wpfs-end-subscription" value="wpfs-end-subscription-after-x-occurrences" <% if ( cancellationCount != 0 ) { %> checked <% } %>>
                    <label class="wpfs-form-check-label" for="wpfs-end-subscription-after-x-occurrences--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>">
                        <?php esc_html_e( 'After certain # of occurrences:', 'wp-full-stripe-admin' ); ?> <input id="wpfs-subscription-cancellation-count--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>" name="wpfs-subscription-cancellation-count" class="wpfs-form-control wpfs-form-control--w56" type="text" <% if ( cancellationCount == 0 ) { %> disabled <% } %> value="<%= cancellationCount == 0 ? '1' : cancellationCount %>">
                    </label>
                </div>
            </div>
        </div>
        <% if ( interval === 'month' && formLayout === 'inline' ) {  %>
        <div class="wpfs-form-group">
            <label for="" class="wpfs-form-label"><?php esc_html_e( 'Billing cycle starts', 'wp-full-stripe-admin' ); ?></label>
            <div class="wpfs-form-check-list">
                <div class="wpfs-form-check">
                    <input type="radio" class="wpfs-form-check-input" id="wpfs-billing-cycle-customer-subscribed--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>" name="wpfs-billing-cycle" value="wpfs-billing-cycle-customer-subscribed" <% if ( billingAnchorDay == 0 ) { %> checked <% } %>>
                    <label class="wpfs-form-check-label" for="wpfs-billing-cycle-customer-subscribed--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>"><?php esc_html_e( 'On the day when customer subscribed', 'wp-full-stripe-admin' ); ?></label>
                </div>
                <div class="wpfs-form-check">
                    <input type="radio" class="wpfs-form-check-input" id="wpfs-billing-cycle-on-this-day--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>" name="wpfs-billing-cycle" value="wpfs-billing-cycle-on-this-day" <% if ( billingAnchorDay != 0 ) { %> checked <% } %>>
                    <label class="wpfs-form-check-label" for="wpfs-billing-cycle-on-this-day--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>">
                        <?php esc_html_e( 'On this day of the month:', 'wp-full-stripe-admin' ); ?> <input id="wpfs-billing-cycle-day--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>" name="wpfs-billing-cycle-day" class="wpfs-form-control wpfs-form-control--w56" type="text" <% if ( billingAnchorDay == 0 ) { %> disabled <% } %> value="<%= billingAnchorDay == 0 ? '1' : billingAnchorDay %>">
                    </label>
                </div>
            </div>
        </div>
        <div class="wpfs-form-group" id="wpfs-prorate-until-billing-anchor-day-edit" <% if ( billingAnchorDay == 0 ) { %> style="display: none;" <% } %> >
            <label for="" class="wpfs-form-label"><?php esc_html_e( 'Prorate until the billing anchor day?', 'wp-full-stripe-admin' ); ?></label>
            <div class="wpfs-form-check-list">
                <div class="wpfs-form-check">
                    <input type="radio" class="wpfs-form-check-input" id="wpfs-prorate-until-billing-anchor-day-no--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>" name="wpfs-prorate-until-billing-anchor-day" value="0" <% if ( prorateUntilBillingAnchorDay == 0 ) { %> checked <% } %>>
                    <label class="wpfs-form-check-label" for="wpfs-prorate-until-billing-anchor-day-no--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>"><?php esc_html_e( 'No', 'wp-full-stripe-admin' ); ?></label>
                </div>
                <div class="wpfs-form-check">
                    <input type="radio" class="wpfs-form-check-input" id="wpfs-prorate-until-billing-anchor-day-yes--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>" name="wpfs-prorate-until-billing-anchor-day" value="1" <% if ( prorateUntilBillingAnchorDay == 1 ) { %> checked <% } %>>
                    <label class="wpfs-form-check-label" for="wpfs-prorate-until-billing-anchor-day-yes--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>"><?php esc_html_e( 'Yes', 'wp-full-stripe-admin' ); ?></label>
                </div>
            </div>
        </div>
        <% } else { %>
        <input type="hidden" id="wpfs-billing-cycle-customer-subscribed--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>" name="wpfs-billing-cycle" value="wpfs-billing-cycle-customer-subscribed">
        <input type="hidden" id="wpfs-billing-cycle-day--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>" name="wpfs-billing-cycle-day" value="0">
        <input type="hidden" id="wpfs-prorate-until-billing-anchor-day-no--<?php echo MM_WPFS::FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES; ?>" name="wpfs-prorate-until-billing-anchor-day" value="0">
        <% } %>
    </form>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-primary js-dialog-save-recurring-product-properties-dialog"><?php _e( 'Save plan', 'wp-full-stripe-admin'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Discard', 'wp-full-stripe-admin' ); ?></button>
    </div>
</script>
