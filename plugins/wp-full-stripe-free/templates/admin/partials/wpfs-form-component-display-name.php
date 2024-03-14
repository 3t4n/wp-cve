<?php
    /** @var $view MM_WPFS_Admin_FormView */
    /** @var $form */
?>
<div class="wpfs-form-group">
    <label for="" class="wpfs-form-label"><?php $view->displayName()->label(); ?></label>
    <input id="<?php $view->displayName()->id(); ?>" name="<?php $view->displayName()->name(); ?>" type="text" class="wpfs-form-control js-to-pascal-case" value="<?php echo $form->displayName; ?>" data-to-pascal-case="#<?php $view->name()->id(); ?>">
</div>
<div class="wpfs-form-group">
    <label for="" class="wpfs-form-label"><?php $view->name()->label(); ?></label>
    <input id="<?php $view->name()->id(); ?>" name="<?php $view->name()->name(); ?>" class="wpfs-form-control" type="text" value="<?php echo $form->name; ?>">
</div>
