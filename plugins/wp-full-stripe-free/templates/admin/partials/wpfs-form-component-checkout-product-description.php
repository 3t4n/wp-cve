<?php
/** @var $view MM_WPFS_Admin_CheckoutDonationFormView|MM_WPFS_Admin_CheckoutPaymentFormView */
/** @var $form */
?>
<div class="wpfs-form-group">
    <label for="" class="wpfs-form-label"><?php $view->checkoutProductName()->label(); ?></label>
    <input id="<?php $view->checkoutProductName()->id(); ?>" name="<?php $view->checkoutProductName()->name(); ?>" <?php $view->checkoutProductName()->attributes(); ?>" value="<?php echo $form->productDesc; ?>">
</div>
<div class="wpfs-form-group">
    <label for="" class="wpfs-form-label"><?php $view->checkoutProductDescription()->label(); ?></label>
    <input id="<?php $view->checkoutProductDescription()->id(); ?>" name="<?php $view->checkoutProductDescription()->name(); ?>" <?php $view->checkoutProductDescription()->attributes(); ?>" value="<?php echo $form->companyName; ?>">
</div>
