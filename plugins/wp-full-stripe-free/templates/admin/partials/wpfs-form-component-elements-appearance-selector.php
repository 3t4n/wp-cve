<?php
/** @var $view MM_WPFS_Admin_PaymentFormView|MM_WPFS_Admin_SubscriptionFormView|MM_WPFS_Admin_DonationFormView|MM_WPFS_Admin_SaveCardFormView */
/** @var $form */


$currentThemeValue = $form->stripeElementsTheme;
?>
    <label for="" class="wpfs-form-label wpfs-form-label--mb"><?php $view->stripeElementsThemeSelector()->label(); ?></label>
<?php foreach ( $view->stripeElementsThemeSelector()->options() as $option ) {
    /* @var $option MM_WPFS_Control */
    ?>
    <div class="wpfs-form-check wpfs-form-check--block">
        <input id="<?php $option->id(); ?>" name="<?php $option->name(); ?>" value="<?php $option->value(); ?>" <?php $option->attributes(); ?> <?php echo $option->value(false) == $currentThemeValue ? 'checked' : ''; ?>/>
        <label class="wpfs-form-check-label" for="<?php $option->id(); ?>">
            <span class="wpfs-form-check-label__title"><?php $option->label(); ?></span>
            <span class="wpfs-form-check-label__desc"><?php echo $option->metadata()['description']; ?></span>
            <span class="<?php echo $option->metadata()['iconClass']; ?> wpfs-form-check-label__illu"></span>
        </label>
    </div>
<?php } ?>
<div class="wpfs-form-group">
    <label for="" class="wpfs-form-label"><?php $view->stripeElementsFont()->label(); ?></label>
    <input id="<?php $view->stripeElementsFont()->id(); ?>" name="<?php $view->stripeElementsFont()->name(); ?>" type="text" class="wpfs-form-control js-to-pascal-case" value="<?php echo $form->stripeElementsFont; ?>" data-to-pascal-case="#<?php $view->name()->id(); ?>">
</div>
