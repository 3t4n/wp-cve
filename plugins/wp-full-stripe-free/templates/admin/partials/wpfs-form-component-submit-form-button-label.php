<?php
    /** @var $view MM_WPFS_Admin_FormView */
    /** @var $form */
    /** @var $submitButtonDescription */
?>
<div class="wpfs-form-group">
    <label for="<?php $view->buttonLabel()->id(); ?>" class="wpfs-form-label"><?php $view->buttonLabel()->label(); ?></label>
    <input id="<?php $view->buttonLabel()->id(); ?>" name="<?php $view->buttonLabel()->name(); ?>" <?php $view->buttonLabel()->attributes(); ?> value="<?php echo $form->buttonTitle; ?>">
    <?php if ( isset( $submitButtonDescription ) ) { ?>
    <div class="wpfs-form-help"><?php echo esc_html( $submitButtonDescription ) ?></div>
    <?php } ?>
</div>
