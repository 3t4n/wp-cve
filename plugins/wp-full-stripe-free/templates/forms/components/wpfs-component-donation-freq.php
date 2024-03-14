<?php
/** @var MM_WPFS_FormView $view */
/** @var \StdClass $form */
if (!is_null($view->donationFrequencyOptions())) {
    if (count($view->donationFrequencyOptions()->options()) > 1) {
        ?>
        <fieldset class="wpfs-form-check-group wpfs-button-group wpfs-button-group--without-space">
            <legend class="wpfs-form-check-group-title">
                <?php $view->donationFrequencyOptions()->label(); ?>
            </legend>
            <div class="wpfs-button-group-row">
                <?php
                $frequencyIndex = 0;
                foreach ($view->donationFrequencyOptions()->options() as $donationFrequencyOption): ?>
                    <div class="wpfs-button-group-item">
                        <input id="<?php $donationFrequencyOption->id(); ?>" name="<?php $donationFrequencyOption->name(); ?>"
                            type="radio" class="wpfs-form-check-input wpfs-custom-amount-radio"
                            value="<?php $donationFrequencyOption->value(); ?>" <?php echo $frequencyIndex == 0 ? "checked" : "" ?>>
                        <label for="<?php $donationFrequencyOption->id(); ?>" class="wpfs-btn wpfs-btn-outline-primary">
                            <?php $donationFrequencyOption->label(); ?>
                        </label>
                    </div>
                    <?php
                    $frequencyIndex++;
                endforeach; ?>
            </div>
        </fieldset>
        <?php
    } else if (count($view->donationFrequencyOptions()->options()) == 1) {
        $donationFrequencyOption = $view->donationFrequencyOptions()->options()[0];
        ?>
            <input id="<?php $donationFrequencyOption->id(); ?>" name="<?php $donationFrequencyOption->name(); ?>" type="hidden"
                value="<?php $donationFrequencyOption->value(); ?>">
        <?php
    }
}
?>