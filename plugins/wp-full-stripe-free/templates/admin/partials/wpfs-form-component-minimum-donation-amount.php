<?php
/** @var $view MM_WPFS_Admin_DonationFormView */
/** @var $form */
?>
<div class="wpfs-form-group" id="wpfs-minimum-donation-amount">
    <label for="<?php $view->minimumDonationAmount()->id(); ?>" class="wpfs-form-label"><?php $view->minimumDonationAmount()->label(); ?></label>
    <div id="wpfs-minimum-donation-amount-container"></div>
</div>
<script type="text/javascript">
    var wpfsMinimumDonationAmount = <?php echo $form->minimumDonationAmount ?>;
</script>
<script type="text/template" id="wpfs-fragment-minimum-donation-amount-template">
    <% if ( wpfsAdminSettings.preferences.currencyShowIdentifierOnLeft == '1' ) { %>
    <div class="wpfs-input-group-prepend">
        <span class="wpfs-input-group-text"><%= currencySymbol %></span>
    </div>
    <% } %>
    <input id="<?php $view->minimumDonationAmount()->id(); ?>" class="wpfs-input-group-form-control" type="text" name="<?php $view->minimumDonationAmount()->name(); ?>" value="<%= minimumDonationAmount %>">
    <% if ( wpfsAdminSettings.preferences.currencyShowIdentifierOnLeft == '0' ) { %>
    <div class="wpfs-input-group-append">
        <span class="wpfs-input-group-text"><%= currencySymbol %></span>
    </div>
    <% } %>
</script>
