<?php
/** @var MM_WPFS_FormView $view */
/** @var \StdClass $form */

if ( isset( $form->showAddress ) && 1 == $form->showAddress ): ?>
	<?php if ( ! is_null( $view->sameBillingAndShippingAddress() ) ): ?>
		<div class="wpfs-form-check">
			<input type="checkbox" class="wpfs-form-check-input wpfs-same-billing-and-shipping-address" id="<?php $view->sameBillingAndShippingAddress()->id(); ?>" name="<?php $view->sameBillingAndShippingAddress()->name(); ?>" value="1" checked data-wpfs-address-switcher-id="<?php $view->addressSwitcher()->id(); ?>" data-wpfs-billing-address-switch-id="<?php $view->billingAddressRadio()->id(); ?>">
			<label class="wpfs-form-check-label" for="<?php $view->sameBillingAndShippingAddress()->id(); ?>">
				<?php $view->sameBillingAndShippingAddress()->label(); ?>
			</label>
		</div>
	<?php endif; ?>
	<?php if ( ! is_null( $view->addressSwitcher() ) ): ?>
		<div id="<?php $view->addressSwitcher()->id(); ?>" class="wpfs-form-check-group wpfs-button-group wpfs-button-group--without-space" style="display: none;">
			<div class="wpfs-button-group-row">
				<div class="wpfs-button-group-item">
					<input id="<?php $view->billingAddressRadio()->id(); ?>" name="<?php $view->billingAddressRadio()->name(); ?>" type="radio" class="wpfs-form-check-input wpfs-billing-address-switch" checked data-wpfs-billing-address-panel-id="<?php $view->billingAddressPanel()->id(); ?>" data-wpfs-shipping-address-panel-id="<?php $view->shippingAddressPanel()->id(); ?>">
					<label class="wpfs-btn wpfs-btn-outline-primary" for="<?php $view->billingAddressRadio()->id(); ?>"><?php $view->billingAddressRadio()->label(); ?></label>
				</div>
				<div class="wpfs-button-group-item">
					<input id="<?php $view->shippingAddressRadio()->id(); ?>" name="<?php $view->shippingAddressRadio()->name(); ?>" type="radio" class="wpfs-form-check-input wpfs-shipping-address-switch" data-wpfs-billing-address-panel-id="<?php $view->billingAddressPanel()->id(); ?>" data-wpfs-shipping-address-panel-id="<?php $view->shippingAddressPanel()->id(); ?>">
					<label class="wpfs-btn wpfs-btn-outline-primary" for="<?php $view->shippingAddressRadio()->id(); ?>"><?php $view->shippingAddressRadio()->label(); ?></label>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php if ( ! is_null( $view->billingAddressPanel() ) ): ?>
		<div id="<?php $view->billingAddressPanel()->id(); ?>">
			<div class="wpfs-form-group">
				<label class="wpfs-form-label" for="<?php $view->billingName()->id(); ?>"><?php $view->billingName()->label(); ?></label>
				<input id="<?php $view->billingName()->id(); ?>" name="<?php $view->billingName()->name(); ?>" type="text" class="wpfs-form-control" value="<?php $view->billingName()->value(); ?>" <?php $view->billingName()->attributes(); ?>>
			</div>
            <div class="wpfs-form-group">
                <label class="wpfs-form-label" for="<?php $view->billingAddressCountry()->id(); ?>"><?php $view->billingAddressCountry()->label(); ?></label>
                <div class="wpfs-ui wpfs-form-select">
                    <select id="<?php $view->billingAddressCountry()->id(); ?>" name="<?php $view->billingAddressCountry()->name(); ?>" data-toggle="selectmenu" data-wpfs-select="wpfs-billing-address-country-select" class="wpfs-billing-address-country-select" <?php $view->billingAddressCountry()->attributes(); ?>>
                        <?php foreach ( $view->billingAddressCountry()->options() as $country ) : ?>
                            <?php /** @var MM_WPFS_Control $country */ ?>
                            <option value="<?php $country->value(); ?>" <?php $country->attributes(); ?>><?php $country->caption(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
			<div class="wpfs-form-row">
				<div class="wpfs-form-col">
					<div class="wpfs-form-group">
						<label class="wpfs-form-label" for="<?php $view->billingAddressLine1()->id(); ?>"><?php $view->billingAddressLine1()->label(); ?></label>
						<input id="<?php $view->billingAddressLine1()->id(); ?>" name="<?php $view->billingAddressLine1()->name(); ?>" type="text" class="wpfs-form-control" value="<?php $view->billingAddressLine1()->value(); ?>" <?php $view->billingAddressLine1()->attributes(); ?>>
					</div>
				</div>
				<div class="wpfs-form-col">
					<div class="wpfs-form-group">
						<label class="wpfs-form-label" for="<?php $view->billingAddressLine2()->id(); ?>"><?php $view->billingAddressLine2()->label(); ?></label>
						<input id="<?php $view->billingAddressLine2()->id(); ?>" name="<?php $view->billingAddressLine2()->name(); ?>" type="text" class="wpfs-form-control" value="<?php $view->billingAddressLine2()->value(); ?>" <?php $view->billingAddressLine2()->attributes(); ?>>
					</div>
				</div>
			</div>
			<div class="wpfs-form-group">
				<label class="wpfs-form-label" for="<?php $view->billingAddressCity()->id(); ?>"><?php $view->billingAddressCity()->label(); ?></label>
				<input id="<?php $view->billingAddressCity()->id(); ?>" name="<?php $view->billingAddressCity()->name(); ?>" type="text" class="wpfs-form-control" value="<?php $view->billingAddressCity()->value(); ?>" <?php $view->billingAddressCity()->attributes(); ?>>
			</div>
			<div class="wpfs-form-row">
				<div class="wpfs-form-col">
					<div class="wpfs-form-group">
						<label class="wpfs-form-label" for="<?php $view->billingAddressState()->id(); ?>"><?php $view->billingAddressState()->label(); ?></label>
						<input id="<?php $view->billingAddressState()->id(); ?>" name="<?php $view->billingAddressState()->name(); ?>" type="text" class="wpfs-form-control" style="<?php echo $view->getDefaultBillingCountry() === MM_WPFS::COUNTRY_CODE_UNITED_STATES ? 'display: none;' : ''; ?>" value="<?php $view->billingAddressState()->value(); ?>" <?php $view->billingAddressState()->attributes(); ?>>
                        <div class="wpfs-ui wpfs-form-select wpfs-billing-address-state-select" style="<?php echo $view->getDefaultBillingCountry() !== MM_WPFS::COUNTRY_CODE_UNITED_STATES ? 'display: none;' : ''; ?>">
                            <select id="<?php $view->billingAddressStateSelect()->id(); ?>" name="<?php $view->billingAddressStateSelect()->name(); ?>" data-toggle="selectmenu" data-wpfs-select="wpfs-billing-address-state-select" class="wpfs-billing-address-state-select" <?php $view->billingAddressStateSelect()->attributes(); ?>>
                                <?php foreach ( $view->billingAddressStateSelect()->options() as $state ) : ?>
                                    <?php /** @var MM_WPFS_Control $country */ ?>
                                    <option value="<?php $state->value(); ?>" <?php $state->attributes(); ?>><?php $state->caption(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
					</div>
				</div>
				<div class="wpfs-form-col">
					<div class="wpfs-form-group">
						<label class="wpfs-form-label" for="<?php $view->billingAddressZip()->id(); ?>"><?php $view->billingAddressZip()->label(); ?></label>
						<input id="<?php $view->billingAddressZip()->id(); ?>" name="<?php $view->billingAddressZip()->name(); ?>" type="text" class="wpfs-form-control" value="<?php $view->billingAddressZip()->value(); ?>" <?php $view->billingAddressZip()->attributes(); ?>>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php if ( ! is_null( $view->shippingAddressPanel() ) ): ?>
		<div id="<?php $view->shippingAddressPanel()->id(); ?>" style="display: none;">
			<div class="wpfs-form-group">
				<label class="wpfs-form-label" for="<?php $view->shippingName()->id(); ?>"><?php $view->shippingName()->label(); ?></label>
				<input id="<?php $view->shippingName()->id(); ?>" name="<?php $view->shippingName()->name(); ?>" type="text" class="wpfs-form-control" value="<?php $view->shippingName()->value(); ?>" <?php $view->shippingName()->attributes(); ?>>
			</div>
            <div class="wpfs-form-group">
                <label class="wpfs-form-label" for="<?php $view->shippingAddressCountry()->id(); ?>"><?php $view->shippingAddressCountry()->label(); ?></label>
                <div class="wpfs-ui wpfs-form-select">
                    <select id="<?php $view->shippingAddressCountry()->id(); ?>" name="<?php $view->shippingAddressCountry()->name(); ?>" data-toggle="selectmenu" data-wpfs-select="wpfs-shipping-address-country-select" class="wpfs-shipping-address-country-select" <?php $view->shippingAddressCountry()->attributes(); ?>>
                        <?php foreach ( $view->shippingAddressCountry()->options() as $country ) : ?>
                            <?php /** @var MM_WPFS_Control $country */ ?>
                            <option value="<?php $country->value(); ?>" <?php $country->attributes(); ?>><?php $country->caption(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
			<div class="wpfs-form-row">
				<div class="wpfs-form-col">
					<div class="wpfs-form-group">
						<label class="wpfs-form-label" for="<?php $view->shippingAddressLine1()->id(); ?>"><?php $view->shippingAddressLine1()->label(); ?></label>
						<input id="<?php $view->shippingAddressLine1()->id(); ?>" name="<?php $view->shippingAddressLine1()->name(); ?>" type="text" class="wpfs-form-control" value="<?php $view->shippingAddressLine1()->value(); ?>" <?php $view->shippingAddressLine1()->attributes(); ?>>
					</div>
				</div>
				<div class="wpfs-form-col">
					<div class="wpfs-form-group">
						<label class="wpfs-form-label" for="<?php $view->shippingAddressLine2()->id(); ?>"><?php $view->shippingAddressLine2()->label(); ?></label>
						<input id="<?php $view->shippingAddressLine2()->id(); ?>" name="<?php $view->shippingAddressLine2()->name(); ?>" type="text" class="wpfs-form-control" value="<?php $view->shippingAddressLine2()->value(); ?>" <?php $view->shippingAddressLine2()->attributes(); ?>>
					</div>
				</div>
			</div>
			<div class="wpfs-form-group">
				<label class="wpfs-form-label" for="<?php $view->shippingAddressCity()->id(); ?>"><?php $view->shippingAddressCity()->label(); ?></label>
				<input id="<?php $view->shippingAddressCity()->id(); ?>" name="<?php $view->shippingAddressCity()->name(); ?>" type="text" class="wpfs-form-control" value="<?php $view->shippingAddressCity()->value(); ?>" <?php $view->shippingAddressCity()->attributes(); ?>>
			</div>
			<div class="wpfs-form-row">
				<div class="wpfs-form-col">
					<div class="wpfs-form-group">
						<label class="wpfs-form-label" for="<?php $view->shippingAddressState()->id(); ?>"><?php $view->shippingAddressState()->label(); ?></label>
						<input id="<?php $view->shippingAddressState()->id(); ?>" name="<?php $view->shippingAddressState()->name(); ?>" type="text" class="wpfs-form-control" style="<?php echo $view->getDefaultShippingCountry() === MM_WPFS::COUNTRY_CODE_UNITED_STATES ? 'display: none;' : ''; ?>"  value="<?php $view->shippingAddressState()->value(); ?>" <?php $view->shippingAddressState()->attributes(); ?>>
                        <div class="wpfs-ui wpfs-form-select wpfs-shipping-address-state-select" style="<?php echo $view->getDefaultShippingCountry() !== MM_WPFS::COUNTRY_CODE_UNITED_STATES ? 'display: none;' : ''; ?>">
                            <select id="<?php $view->shippingAddressStateSelect()->id(); ?>" name="<?php $view->shippingAddressStateSelect()->name(); ?>" data-toggle="selectmenu" data-wpfs-select="wpfs-shipping-address-state-select" class="wpfs-shipping-address-state-select" <?php $view->shippingAddressStateSelect()->attributes(); ?>>
                                <?php foreach ( $view->shippingAddressStateSelect()->options() as $state ) : ?>
                                    <?php /** @var MM_WPFS_Control $country */ ?>
                                    <option value="<?php $state->value(); ?>" <?php $state->attributes(); ?>><?php $state->caption(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
					</div>
				</div>
				<div class="wpfs-form-col">
					<div class="wpfs-form-group">
						<label class="wpfs-form-label" for="<?php $view->shippingAddressZip()->id(); ?>"><?php $view->shippingAddressZip()->label(); ?></label>
						<input id="<?php $view->shippingAddressZip()->id(); ?>" name="<?php $view->shippingAddressZip()->name(); ?>" type="text" class="wpfs-form-control"  value="<?php $view->shippingAddressZip()->value(); ?>" <?php $view->shippingAddressZip()->attributes(); ?>>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>