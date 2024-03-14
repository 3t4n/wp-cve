<?php defined( 'ABSPATH' ) || die; ?>

<div class="field-row">
    <div class="field-column">
        <label for="charge-amount">Amount</label>
        <div class="charge-amount-field-and-btn-container">
            <input type="number" min="0" step="any" id="charge-amount" class="field money-field" placeholder="0" data-required data-field-name="charge amount" />
            <button type="button" id="woo-mp-charge-amount-autofill-btn" class="charge-amount-autofill-btn"></button>
        </div>
    </div>
</div>
