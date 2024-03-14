<?php
/** @var $view MM_WPFS_Admin_InlineDonationFormView */
/** @var $form */
?>
<div class="wpfs-form-group">
    <label for="" class="wpfs-form-label"><?php $view->defaultProductName()->label(); ?></label>
    <input id="<?php $view->defaultProductName()->id(); ?>" name="<?php $view->defaultProductName()->name(); ?>" <?php $view->defaultProductName()->attributes(); ?>" value="<?php echo $form->productDesc; ?>">
</div>
