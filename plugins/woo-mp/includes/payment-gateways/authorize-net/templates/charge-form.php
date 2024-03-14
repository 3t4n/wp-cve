<?php defined( 'ABSPATH' ) || die; ?>

<?php $this->template( 'card-fields' ); ?>

<?php $this->template( 'charge-amount-field' ); ?>

<div class="field-row">
    <div class="field-column button-field-column">
        <button type="button" class="button field collapse-arrow" data-toggle="collapse" aria-controls="additional-details" aria-expanded="false">More</button>
    </div>
</div>

<div id="additional-details" class="field-row" style="display: none;">
    <div class="field-column">
        <div class="field-row">
            <div class="field-column">
                <label for="tax-amount">Tax Amount</label>
                <input type="number" min="0" step="any" id="tax-amount" class="field money-field" placeholder="0" data-field-name="tax amount" />
            </div>
            <div class="field-column">
                <label for="freight-amount">Freight Amount</label>
                <input type="number" min="0" step="any" id="freight-amount" class="field money-field" placeholder="0" data-field-name="freight amount" />
            </div>
            <div class="field-column">
                <label for="duty-amount">Duty Amount</label>
                <input type="number" min="0" step="any" id="duty-amount" class="field money-field" placeholder="0" data-field-name="duty amount" />
            </div>
        </div>
        <div class="field-row">
            <div class="field-column">
                <label for="po-number">PO Number</label>
                <input type="text" id="po-number" class="field" placeholder="Purchase Order Number" />
            </div>
        </div>
        <div class="field-row">
            <div class="field-column checkbox-field-column">
                <label class="checkbox-label">Tax Exempt<input type="checkbox" id="tax-exempt" /></label>
            </div>
        </div>
    </div>
</div>
