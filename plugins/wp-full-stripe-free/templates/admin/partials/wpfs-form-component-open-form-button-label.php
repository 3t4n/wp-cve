<?php
    /** @var $view MM_WPFS_Admin_CheckoutFormView */
    /** @var $form */
    /** @var $openButtonDescription */
?>
<div class="wpfs-form-group">
    <label for="<?php $view->openButtonLabel()->id(); ?>" class="wpfs-form-label"><?php $view->openButtonLabel()->label(); ?></label>
    <input id="<?php $view->openButtonLabel()->id(); ?>" name="<?php $view->openButtonLabel()->name(); ?>" <?php $view->openButtonLabel()->attributes(); ?> value="<?php echo $form->openButtonTitle; ?>">
    <?php if ( isset( $openButtonDescription ) ) { ?>
        <div class="wpfs-form-help"><?php echo esc_html( $openButtonDescription ) ?></div>
    <?php } ?>
</div>
