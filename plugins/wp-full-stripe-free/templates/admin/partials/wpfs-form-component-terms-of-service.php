<?php
    /** @var $view MM_WPFS_Admin_FormView */
    /** @var $form */
    /** @var $data */
?>
<div class="wpfs-form-group">
    <label class="wpfs-form-label"><?php esc_html_e( "Consent", 'wp-full-stripe-admin' ); ?></label>
    <div class="wpfs-form-check-list">
        <div class="wpfs-form-check">
            <input id="<?php $view->showTermsOfService()->id(); ?>" name="<?php $view->showTermsOfService()->name(); ?>" value="<?php $view->showTermsOfService()->value(); ?>" <?php $view->showTermsOfService()->attributes(); ?> <?php echo $form->showTermsOfUse == $view->showTermsOfService()->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $view->showTermsOfService()->id(); ?>"><?php $view->showTermsOfService()->label(); ?></label>
        </div>
    </div>
</div>
<div class="wpfs-form-group wpfs-tos-section" style="<?php echo $form->showTermsOfUse == $options[1]->value(false) ? '' : 'display: none'; ?>">
    <label for="" class="wpfs-form-label"><?php $view->termsOfServiceLabel()->label(); ?></label>
    <input id="<?php $view->termsOfServiceLabel()->id(); ?>" name="<?php $view->termsOfServiceLabel()->name(); ?>" class="wpfs-form-control" type="text" value="<?php echo esc_html( $form->termsOfUseLabel ); ?>">
</div>
<div class="wpfs-form-group wpfs-tos-section" style="<?php echo $form->showTermsOfUse == $options[1]->value(false) ? '' : 'display: none'; ?>">
    <label for="" class="wpfs-form-label"><?php $view->termsOfServiceErrorMessage()->label(); ?></label>
    <input id="<?php $view->termsOfServiceErrorMessage()->id(); ?>" name="<?php $view->termsOfServiceErrorMessage()->name(); ?>" class="wpfs-form-control" type="text" value="<?php echo $form->termsOfUseNotCheckedErrorMessage; ?>">
</div>
