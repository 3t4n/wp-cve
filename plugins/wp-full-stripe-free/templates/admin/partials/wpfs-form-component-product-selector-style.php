<?php
    /** @var $view MM_WPFS_Admin_PaymentFormView|MM_WPFS_Admin_SubscriptionFormView */
    /** @var $form */

    $currentValue = $view instanceof MM_WPFS_Admin_PaymentFormView ? $form->amountSelectorStyle : $form->planSelectorStyle;
?>
<label for="" class="wpfs-form-label wpfs-form-label--mb"><?php $view->productSelectorStyle()->label(); ?></label>
<?php foreach ( $view->productSelectorStyle()->options() as $option ) {
    /* @var $option MM_WPFS_Control */
    ?>
    <div class="wpfs-form-check wpfs-form-check--block">
        <input id="<?php $option->id(); ?>" name="<?php $option->name(); ?>" value="<?php $option->value(); ?>" <?php $option->attributes(); ?> <?php echo $option->value(false) == $currentValue ? 'checked' : ''; ?>/>
        <label class="wpfs-form-check-label" for="<?php $option->id(); ?>">
            <span class="wpfs-form-check-label__title"><?php $option->label(); ?></span>
            <span class="wpfs-form-check-label__desc"><?php echo $option->metadata()['description']; ?></span>
            <span class="<?php echo $option->metadata()['iconClass']; ?> wpfs-form-check-label__illu"></span>
        </label>
    </div>
<?php } ?>
