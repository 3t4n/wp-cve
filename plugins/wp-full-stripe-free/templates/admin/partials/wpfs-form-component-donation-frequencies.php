<?php
    /** @var $view MM_WPFS_Admin_DonationFormView */
    /** @var $form */
    /** @var $data */
?>
<div class="wpfs-form-group">
    <label for="" class="wpfs-form-label wpfs-form-label--mb">
        <?php $view->donationFrequencies()->label(); ?>
    </label>
    <div class="wpfs-form-check-list wpfs-form-check-list--flex" id="<?php $view->donationFrequencies()->id(); ?>" data-field-name="<?php $view->donationFrequencies()->name(); ?>">
        <div class="wpfs-form-check">
            <input id="<?php $view->donationFrequencyOnetime()->id(); ?>" name="<?php $view->donationFrequencyOnetime()->name(); ?>" <?php $view->donationFrequencyOnetime()->attributes(); ?> <?php echo $form->allowOneTimeDonation == '1' ? 'checked' : '';  ?>>
            <label class="wpfs-form-check-label" for="<?php $view->donationFrequencyOnetime()->id(); ?>"><?php $view->donationFrequencyOnetime()->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $view->donationFrequencyDaily()->id(); ?>" name="<?php $view->donationFrequencyDaily()->name(); ?>" <?php $view->donationFrequencyDaily()->attributes(); ?> <?php echo $form->allowDailyRecurring == '1' ? 'checked' : '';  ?>>
            <label class="wpfs-form-check-label" for="<?php $view->donationFrequencyDaily()->id(); ?>"><?php $view->donationFrequencyDaily()->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $view->donationFrequencyWeekly()->id(); ?>" name="<?php $view->donationFrequencyWeekly()->name(); ?>" <?php $view->donationFrequencyWeekly()->attributes(); ?> <?php echo $form->allowWeeklyRecurring == '1' ? 'checked' : '';  ?>>
            <label class="wpfs-form-check-label" for="<?php $view->donationFrequencyWeekly()->id(); ?>"><?php $view->donationFrequencyWeekly()->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $view->donationFrequencyMonthly()->id(); ?>" name="<?php $view->donationFrequencyMonthly()->name(); ?>" <?php $view->donationFrequencyMonthly()->attributes(); ?> <?php echo $form->allowMonthlyRecurring == '1' ? 'checked' : '';  ?>>
            <label class="wpfs-form-check-label" for="<?php $view->donationFrequencyMonthly()->id(); ?>"><?php $view->donationFrequencyMonthly()->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $view->donationFrequencyAnnual()->id(); ?>" name="<?php $view->donationFrequencyAnnual()->name(); ?>" <?php $view->donationFrequencyAnnual()->attributes(); ?> <?php echo $form->allowAnnualRecurring == '1' ? 'checked' : '';  ?>>
            <label class="wpfs-form-check-label" for="<?php $view->donationFrequencyAnnual()->id(); ?>"><?php $view->donationFrequencyAnnual()->label(); ?></label>
        </div>
    </div>
</div>
