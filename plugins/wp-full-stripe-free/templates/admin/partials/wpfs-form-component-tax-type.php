<?php
    /** @var $view MM_WPFS_Admin_PaymentFormView|MM_WPFS_Admin_SubscriptionFormView */
    /** @var $form */
?>
<div class="wpfs-form-group">
    <label class="wpfs-form-label"><?php $view->taxType()->label(); ?></label>
    <div class="wpfs-form-check-list">
        <div class="wpfs-form-check">
            <?php
                $options = $view->taxType()->options();
                $initValue = $view->doesFormUseTaxRates( $form ) ? MM_WPFS::FIELD_VALUE_TAX_RATE_TAX_RATES : $form->vatRateType;
            ?>
            <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $initValue == $options[0]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $initValue == $options[1]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $options[2]->id(); ?>" name="<?php $options[2]->name(); ?>" <?php $options[2]->attributes(); ?> value="<?php $options[2]->value(); ?>" <?php echo $initValue == $options[2]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[2]->id(); ?>"><?php $options[2]->label(); ?></label>
        </div>
    </div>
</div>
