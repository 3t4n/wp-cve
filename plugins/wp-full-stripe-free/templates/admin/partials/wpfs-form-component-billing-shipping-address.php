<?php
    /** @var $view MM_WPFS_Admin_FormView */
    /** @var $form */
    /** @var $data */

    $showAddress = property_exists( $form, 'showAddress' ) ? $form->showAddress : $form->showBillingAddress;
?>
<div class="wpfs-form-check-list">
    <div class="wpfs-form-check">
        <input id="<?php $view->collectBillingAddress()->id(); ?>" name="<?php $view->collectBillingAddress()->name(); ?>" value="<?php $view->collectBillingAddress()->value(); ?>" <?php $view->collectBillingAddress()->attributes(); ?> <?php echo $showAddress == $view->collectBillingAddress()->value(false) ? 'checked' : ''; ?>>
        <label class="wpfs-form-check-label" for="<?php $view->collectBillingAddress()->id(); ?>"><?php $view->collectBillingAddress()->label(); ?></label>
    </div>
</div>
<div class="wpfs-form-check-list">
    <div class="wpfs-form-check">
        <input id="<?php $view->collectShippingAddress()->id(); ?>" name="<?php $view->collectShippingAddress()->name(); ?>" value="<?php $view->collectShippingAddress()->value(); ?>" <?php $view->collectShippingAddress()->attributes(); ?> <?php echo $form->showShippingAddress == $view->collectShippingAddress()->value(false) ? 'checked' : ''; ?>>
        <label class="wpfs-form-check-label" for="<?php $view->collectShippingAddress()->id(); ?>"><?php $view->collectShippingAddress()->label(); ?></label>
    </div>
</div>
