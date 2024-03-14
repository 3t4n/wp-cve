<?php
/** @var MM_WPFS_FormView $view */
/** @var \StdClass $form */
// handle checkout subscription form
if ($view instanceof MM_WPFS_CheckoutSubscriptionFormView && 1 == $form->simpleButtonLayout) {
	if (is_null($view->firstPlan())) {
		?>
		<div class="wpfs-form-message wpfs-form-message--incorrect">
			<div class="wpfs-form-message-title">
				<?php /* translators: Banner title of not finding the plan assigned to the form */
				esc_html_e('Invalid plan', 'wp-full-stripe'); ?>
			</div>
			<?php printf(
				/* translators: Banner error message of not finding the plan assigned to the form
				 * p1: Form name
				 * p2: Plan name
				 */
				esc_html__('Checkout subscription form "%1$s": cannot find subscription plan "%2$s".', 'wp-full-stripe'),
				$view->getFormName(),
				$view->getFirstPlanName()
			); ?>
		</div>
		<?php
	}
	if (!is_null($view->firstPlan())) { ?>
		<input id="<?php $view->plans()->id(); ?>" name="<?php $view->plans()->name(); ?>"
			value="<?php $view->firstPlan()->value(); ?>" <?php $view->firstPlan()->attributes(); ?>>
	<?php }
	// handle inline subscription form
} elseif ($view instanceof MM_WPFS_SubscriptionFormView) {
	if (count($view->plans()->options()) > 1) {
		if (MM_WPFS::PLAN_SELECTOR_STYLE_DROPDOWN === $form->planSelectorStyle) { ?>
			<div class="wpfs-form-group">
				<label class="wpfs-form-label" for="<?php $view->plans()->id(); ?>">
					<?php $view->plans()->label(); ?>
				</label>
				<div class="wpfs-ui wpfs-form-select">
					<select name="<?php $view->plans()->name(); ?>" id="<?php $view->plans()->id(); ?>" data-toggle="selectmenu"
						data-wpfs-select="wpfs-subscription-plan-select" class="wpfs-subscription-plan-select">
						<?php foreach ($view->plans()->options() as $plan) { ?>
							<?php /** @var MM_WPFS_Control $plan */?>
							<option value="<?php $plan->value(); ?>" <?php $plan->attributes(); ?> 				<?php echo ($plan->value(false) === $selectedPlanId ? "selected='selected'" : ""); ?>>
								<?php $plan->label(); ?>
							</option>
						<?php } ?>
					</select>
				</div>
			</div>
			<?php
		} elseif (MM_WPFS::PLAN_SELECTOR_STYLE_RADIO_BUTTONS === $form->planSelectorStyle) {
			?>
			<fieldset class="wpfs-form-check-group">
				<legend class="wpfs-form-check-group-title">
					<?php $view->plans()->label(); ?>
				</legend>
				<?php foreach ($view->plans()->options() as $plan) { ?>
					<?php /** @var MM_WPFS_Control $plan */?>
					<div class="wpfs-form-check">
						<input type="radio" id="<?php $plan->id(); ?>" name="<?php $plan->name(); ?>" value="<?php $plan->value(); ?>"
							class="wpfs-form-check-input wpfs-subscription-plan-radio" <?php $plan->attributes(); ?> 				<?php echo ($plan->value(false) === $selectedPlanId ? "checked='checked'" : ""); ?>>
						<label class="wpfs-form-check-label" for="<?php $plan->id(); ?>">
							<?php $plan->label(); ?>
						</label>
					</div>
				<?php } ?>
			</fieldset>
			<?php
		}
	} elseif (count($view->plans()->options()) == 1) {
		?>
		<input id="<?php $view->plans()->id(); ?>" name="<?php $view->plans()->name(); ?>"
			value="<?php $view->firstPlan()->value(); ?>" <?php $view->firstPlan()->attributes(); ?>
			class="wpfs-subscription-plan-hidden">
		<?php
	} else {
		?>
		<div class="wpfs-form-message wpfs-form-message--incorrect">
			<div class="wpfs-form-message-title">
				<?php /* translators: Banner title of internal error */
				esc_html_e('Form configuration error', 'wp-full-stripe'); ?>
			</div>
			<?php /* Banner error message of not finding plans assigned to this form */
			esc_html_e('Add at least one plan to this form!', 'wp-full-stripe'); ?>
		</div>
		<?php
	}

}
// (inline_subscription|popup_subscription)(field): plans 
if ($view instanceof MM_WPFS_SubscriptionFormView && $form->allowMultipleSubscriptions == '1') { ?>
	<div class="wpfs-form-group">
		<label class="wpfs-form-label" for="<?php $view->planQuantity()->id(); ?>">
			<?php $view->planQuantity()->label(); ?>
		</label>
		<div class="wpfs-stepper wpfs-w-15">
			<input id="<?php $view->planQuantity()->id(); ?>" name="<?php $view->planQuantity()->name(); ?>"
				class="wpfs-form-control" type="text" data-toggle="stepper" <?php $view->planQuantity()->attributes(); ?>>
		</div>
	</div>
<?php } ?>