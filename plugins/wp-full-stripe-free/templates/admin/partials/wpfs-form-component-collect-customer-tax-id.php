<?php
/** @var $view MM_WPFS_Admin_PaymentFormView */
/** @var $form */
?>
<div class="wpfs-form-group" id="collect-tax-id" style="<?php echo $view->doesFormCalculateTax( $form ) ? '' : 'display: none;' ?>">
    <label class="wpfs-form-label"><?php $view->collectCustomerTaxId()->label(); ?></label>
    <div class="wpfs-form-check-list">
        <div class="wpfs-form-check">
            <?php $options = $view->collectCustomerTaxId()->options(); ?>
            <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $form->collectCustomerTaxId == $options[0]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $form->collectCustomerTaxId == $options[1]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
        </div>
    </div>
</div>
