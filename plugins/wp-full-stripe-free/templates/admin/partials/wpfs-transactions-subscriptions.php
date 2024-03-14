<?php
/** @var $pageUrl */
/** @var $textFilter */
/** @var $statusFilter */
/** @var $modeFilter */
?>
<form name="wpfs-search-subscriptions" action="<?php echo $pageUrl; ?>" method="post">
    <div class="wpfs-page-controls">
        <div class="wpfs-form-search wpfs-page-controls__control wpfs-page-controls__control--w320 js-form-search">
            <input class="wpfs-form-control wpfs-form-search__input" type="text" name="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_SUBSCRIPTIONS_TEXT_FILTER ?>" value="<?php echo !empty( $textFilter ) ? $textFilter : "";  ?>" placeholder="<?php esc_html_e( 'Search...', 'wp-full-stripe-admin'); ?>">
            <button class="wpfs-form-search__btn">
                <span class="wpfs-icon-search"></span>
            </button>
        </div>
        <div class="wpfs-ui wpfs-form-select wpfs-page-controls__control wpfs-page-controls__control--w200">
            <select class="js-selectmenu js-subscriptions-status-filter" name="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_SUBSCRIPTIONS_STATUS_FILTER ?>" id="status" data-selectmenu-prefix="<?php _e( "Status: ", 'wp-full-stripe-admin' ); ?>">
                <option value="all" <?php echo $statusFilter === MM_WPFS_Admin_Menu::PARAM_VALUE_SUBSCRIPTION_STATUS_ALL ? 'selected': ''; ?>><?php _e( 'All', 'wp-full-stripe-admin'); ?></option>
                <?php
                foreach (MM_WPFS_Utils::getSubscriptionStatuses() as $subscriptionStatus ) {
                    ?>
                    <option value="<?php echo $subscriptionStatus; ?>" <?php echo $statusFilter === $subscriptionStatus ? 'selected': ''; ?>><?php echo esc_html( MM_WPFS_Admin::getSubscriberStatusLabel( $subscriptionStatus) ); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="wpfs-ui wpfs-form-select wpfs-page-controls__control wpfs-page-controls__control--w200">
            <select class="js-selectmenu js-subscriptions-mode-filter" name="<?php echo MM_WPFS_Admin_Menu::PARAM_NAME_SUBSCRIPTIONS_MODE_FILTER ?>" id="mode" data-selectmenu-prefix="<?php _e( "Mode: ", 'wp-full-stripe-admin' ); ?>">
                <option value="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_ALL; ?>" <?php echo $modeFilter === MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_ALL ? "selected" : "";  ?>><?php _e( 'All', 'wp-full-stripe-admin' ); ?></option>
                <option value="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_LIVE; ?>" <?php echo $modeFilter === MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_LIVE ? "selected" : "";  ?>><?php _e( 'Live', 'wp-full-stripe-admin' ); ?></option>
                <option value="<?php echo MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_TEST; ?>" <?php echo $modeFilter === MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_TEST ? "selected" : "";  ?>><?php _e( 'Test', 'wp-full-stripe-admin' ); ?></option>
            </select>
        </div>
    </div>
</form>
<?php
/** @var WPFS_Subscriptions_Table $subscriptionsTable */
$subscriptionsTable->display();
?>
<div id="subscription-details-container"></div>
<script type="text/template" id="wpfs-side-pane-subscription-details">
    <div class="wpfs-side-pane" data-db-id="<%- id %>" data-stripe-id="<%- subscriptionId %>">
        <div class="wpfs-side-pane__head">
            <div class="wpfs-side-pane__title"><?php _e( 'Subscription details', 'wp-full-stripe-admin' ); ?></div>
            <button class="wpfs-btn wpfs-btn-icon wpfs-side-pane__close js-close-side-pane">
                <span class="wpfs-icon-close"></span>
            </button>
        </div>
        <div class="wpfs-side-pane-item">
            <span class="<%- paymentMethodCssClass %> wpfs-side-pane-item__icon"></span>
            <div class="wpfs-side-pane-item__data">
                <div class="wpfs-side-pane-item__name"><%- paymentMethodTooltip %></div>
                <div class="wpfs-side-pane-item__price"><%- localizedAmount %></div>
            </div>
            <div class="wpfs-side-pane-item__actions">
                <% if ( subscriptionStatus == 'running' ) { %>
                <a class="wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-tooltip js-cancel-subscription-details" data-tooltip-content="cancel-subscription-tooltip">
                    <span class="wpfs-icon-cancel"></span>
                </a>
                <div class="wpfs-tooltip-content" data-tooltip-id="cancel-subscription-tooltip">
                    <div class="wpfs-info-tooltip"><?php _e( 'Cancel', 'wp-full-stripe-admin' ); ?></div>
                </div>
                <% } %>

                <a class="wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-tooltip js-delete-subscription-details" data-tooltip-content="delete-subscription-tooltip">
                    <span class="wpfs-icon-trash"></span>
                </a>
                <div class="wpfs-tooltip-content" data-tooltip-id="delete-subscription-tooltip">
                    <div class="wpfs-info-tooltip"><?php _e( 'Delete subscription', 'wp-full-stripe-admin' ); ?></div>
                </div>
            </div>
        </div>
        <div class="wpfs-side-pane-list">
            <div class="wpfs-side-pane-list__title"><?php _e( 'Subscription', 'wp-full-stripe-admin' ); ?></div>
            <ul class="wpfs-side-pane-list__list">
                <li class="wpfs-side-pane-list__item">
                    <?php _e( 'Id:', 'wp-full-stripe-admin' ); ?> <a class="wpfs-btn wpfs-btn-link" href="<%- subscriptionUrl %>" target="_blank"><%- subscriptionId %></a>
                </li>
                <li class="wpfs-side-pane-list__item">
                    <?php _e( 'Product:', 'wp-full-stripe-admin' ); ?> <span class="wpfs-side-pane-list__highlight"><%- productName %></span>
                </li>
                <li class="wpfs-side-pane-list__item">
                    <?php _e( 'Date:', 'wp-full-stripe-admin' ); ?> <span class="wpfs-side-pane-list__highlight"><%- date %></span>
                </li>
                <li class="wpfs-side-pane-list__item">
                    <?php _e( 'Form:', 'wp-full-stripe-admin' ); ?> <span class="wpfs-side-pane-list__highlight"><%- formDisplayName %></span>
                </li>
                <li class="wpfs-side-pane-list__item">
                    <?php _e( 'Status:', 'wp-full-stripe-admin' ); ?> <span class="wpfs-side-pane-list__highlight"><%- localizedSubscriptionStatus %></span>
                </li>
                <li class="wpfs-side-pane-list__item">
                    <?php _e( 'Mode:', 'wp-full-stripe-admin' ); ?> <span class="wpfs-side-pane-list__highlight"><%- localizedApiMode %></span>
                </li>
                <% if ( coupon != null ) { %>
                <li class="wpfs-side-pane-list__item">
                    <?php _e( 'Coupon:', 'wp-full-stripe-admin' ); ?> <span class="wpfs-side-pane-list__highlight"><%- coupon %></span>
                </li>
                <% } %>
            </ul>

            <div class="wpfs-side-pane-list__title"><?php _e( 'Customer', 'wp-full-stripe-admin' ); ?></div>
            <ul class="wpfs-side-pane-list__list">
                <li class="wpfs-side-pane-list__item">
                    <?php _e( 'Id:', 'wp-full-stripe-admin' ); ?> <a class="wpfs-btn wpfs-btn-link" href="<%- customerUrl %>" target="_blank"><%- customerId %></a>
                </li>
                <li class="wpfs-side-pane-list__item">
                    <?php _e( 'Name:', 'wp-full-stripe-admin' ); ?> <span class="wpfs-side-pane-list__highlight"><%- customerName %></span>
                </li>
                <li class="wpfs-side-pane-list__item">
                    <?php _e( 'E-mail address:', 'wp-full-stripe-admin' ); ?> <a class="wpfs-btn wpfs-btn-link" href="mailto:<%- customerEmail %>"><%- customerEmail %></a>
                </li>
                <% if ( customerPhone != null ) { %>
                <li class="wpfs-side-pane-list__item">
                    <?php _e( 'Phone number:', 'wp-full-stripe-admin' ); ?> <span class="wpfs-side-pane-list__highlight"><%- customerPhone %></span>
                </li>
                <% } %>
                <% if ( ipAddress != null ) { %>
                <li class="wpfs-side-pane-list__item">
                    <?php _e( 'IP address:', 'wp-full-stripe-admin' ); ?> <span class="wpfs-side-pane-list__highlight"><%- ipAddress %></span>
                </li>
                <% } %>
            </ul>

            <% if ( customFields != null && customFields.length > 0 ) { %>
            <div class="wpfs-side-pane-list__title"><?php _e( 'Custom fields', 'wp-full-stripe-admin' ); ?></div>
            <ul class="wpfs-side-pane-list__list">
                <% _.each(customFields, function(customField) { %>
                <li class="wpfs-side-pane-list__item">
                    <%- customField.label %>: <span class="wpfs-side-pane-list__highlight"><%- customField.value %></span>
                </li>
                <% }); %>
            </ul>
            <% } %>

            <% if ( billingAddressCountry != null && billingAddressLine1 != null ) { %>
            <div class="wpfs-side-pane-list__title"><?php _e( 'Billing address', 'wp-full-stripe-admin' ); ?></div>
            <ul class="wpfs-side-pane-list__list">
                <% if ( billingName != null ) { %>
                <li class="wpfs-side-pane-list__item">
                    <span class="wpfs-side-pane-list__highlight"><%- billingName %></span>
                </li>
                <% } %>
                <% if ( billingAddressLine1 != null ) { %>
                <li class="wpfs-side-pane-list__item">
                    <span class="wpfs-side-pane-list__highlight"><%- billingAddressLine1 %><% if ( billingAddressLine1 != null ) { %>, <%- billingAddressLine2 %><% } %></span>
                </li>
                <% } %>
                <% if ( billingAddressCity != null ) { %>
                <li class="wpfs-side-pane-list__item">
                    <span class="wpfs-side-pane-list__highlight"><%- billingAddressZip %> <%- billingAddressCity %><% if ( billingAddressState != null ) { %>, <%- billingAddressState %><% } %></span>
                </li>
                <% } %>
                <li class="wpfs-side-pane-list__item">
                    <span class="wpfs-side-pane-list__highlight"><%- billingAddressCountry %></span>
                </li>
            </ul>
            <% } %>

            <% if ( shippingAddressCountry != null && shippingAddressLine1 != null ) { %>
            <div class="wpfs-side-pane-list__title"><?php _e( 'Shipping address', 'wp-full-stripe-admin' ); ?></div>
            <ul class="wpfs-side-pane-list__list">
                <% if ( shippingName != null ) { %>
                <li class="wpfs-side-pane-list__item">
                    <span class="wpfs-side-pane-list__highlight"><%- shippingName %></span>
                </li>
                <% } %>
                <% if ( shippingAddressLine1 != null ) { %>
                <li class="wpfs-side-pane-list__item">
                    <span class="wpfs-side-pane-list__highlight"><%- shippingAddressLine1 %><% if ( shippingAddressLine1 != null ) { %>, <%- shippingAddressLine2 %><% } %></span>
                </li>
                <% } %>
                <% if ( shippingAddressCity != null ) { %>
                <li class="wpfs-side-pane-list__item">
                    <span class="wpfs-side-pane-list__highlight"><%- shippingAddressZip %> <%- shippingAddressCity %><% if ( shippingAddressState != null ) { %>, <%- shippingAddressState %><% } %></span>
                </li>
                <% } %>
                <li class="wpfs-side-pane-list__item">
                    <span class="wpfs-side-pane-list__highlight"><%- shippingAddressCountry %></span>
                </li>
            </ul>
            <% } %>
        </div>
    </div>
</script>

