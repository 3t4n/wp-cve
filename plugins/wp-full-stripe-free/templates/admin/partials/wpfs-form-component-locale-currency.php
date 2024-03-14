<?php
    /** @var $view MM_WPFS_Admin_FormView */
    /** @var $form */
?>
<div class="wpfs-form-group">
    <label class="wpfs-form-label"><?php $view->localeDecimalSeparator()->label(); ?></label>
    <div class="wpfs-form-check-list">
        <div class="wpfs-form-check">
            <?php $options = $view->localeDecimalSeparator()->options(); ?>
            <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $form->decimalSeparator == $options[0]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $form->decimalSeparator == $options[1]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
        </div>
    </div>
</div>
<div class="wpfs-form-group">
    <label class="wpfs-form-label"><?php $view->localeUseSymbolNotCode()->label(); ?></label>
    <div class="wpfs-form-check-list">
        <div class="wpfs-form-check">
            <?php $options = $view->localeUseSymbolNotCode()->options(); ?>
            <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $form->showCurrencySymbolInsteadOfCode == $options[0]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $form->showCurrencySymbolInsteadOfCode == $options[1]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
        </div>
    </div>
</div>
<div class="wpfs-form-group">
    <label class="wpfs-form-label"><?php $view->localeCurrencySymbolAtFirstPosition()->label(); ?></label>
    <div class="wpfs-form-check-list">
        <div class="wpfs-form-check">
            <?php $options = $view->localeCurrencySymbolAtFirstPosition()->options(); ?>
            <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $form->showCurrencySignAtFirstPosition == $options[0]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $form->showCurrencySignAtFirstPosition == $options[1]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
        </div>
    </div>
</div>
<div class="wpfs-form-group">
    <label class="wpfs-form-label"><?php $view->localePutSpaceBetweenSymbolAndAmount()->label(); ?></label>
    <div class="wpfs-form-check-list">
        <div class="wpfs-form-check">
            <?php $options = $view->localePutSpaceBetweenSymbolAndAmount()->options(); ?>
            <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $form->putWhitespaceBetweenCurrencyAndAmount == $options[0]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $form->putWhitespaceBetweenCurrencyAndAmount == $options[1]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
        </div>
    </div>
</div>
