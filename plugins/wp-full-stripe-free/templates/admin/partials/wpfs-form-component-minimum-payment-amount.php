<?php
/** @var $view MM_WPFS_Admin_PaymentFormView */
/** @var $form */
?>
<div class="wpfs-form-group" id="wpfs-minimum-payment-amount">
    <label for="<?php $view->minimumPaymentAmount()->id(); ?>" class="wpfs-form-label"><?php $view->minimumPaymentAmount()->label(); ?></label>
    <div id="wpfs-minimum-payment-amount-container"></div>
</div>
<script type="text/javascript">
    var wpfsMinimumPaymentAmount = <?php echo $form->minimumPaymentAmount ?>;
</script>
<script type="text/template" id="wpfs-fragment-minimum-payment-amount-template">
    <% if ( wpfsAdminSettings.preferences.currencyShowIdentifierOnLeft == '1' ) { %>
    <div class="wpfs-input-group-prepend">
        <span class="wpfs-input-group-text"><%= currencySymbol %></span>
    </div>
    <% } %>
    <input id="<?php $view->minimumPaymentAmount()->id(); ?>" class="wpfs-input-group-form-control" type="text" name="<?php $view->minimumPaymentAmount()->name(); ?>" value="<%= minimumPaymentAmount %>">
    <% if ( wpfsAdminSettings.preferences.currencyShowIdentifierOnLeft == '0' ) { %>
    <div class="wpfs-input-group-append">
        <span class="wpfs-input-group-text"><%= currencySymbol %></span>
    </div>
    <% } %>
</script>
