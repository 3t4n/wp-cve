<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_search
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

JHtml::fetch('bootstrap.tooltip', '.hasTooltip');

/**
 * Get login requirements:
 * [0] - Never
 * [1] - Optional
 * [2] - Required on confirmation page
 * [3] - Required on calendars page
 */
$login_req = $config->getUint('loginreq');

// get user custom fields
if ($user)
{
	$fields = $user->fields;
}
else
{
	$fields = [];
}

// fetch Item ID from module parameters
$itemid = $params->get('itemid');

?>

<!-- CONTAINER -->

<form class="vap-opb-form" id="vap-opb-form<?php echo $module_id; ?>" method="post">

	<div class="vap-opb-container" id="vap-opb-container<?php echo $module_id; ?>">
		
		<!-- STEP: Find an appointment -->

		<div class="opb-step-wrapper search-box clickable" data-step="search">

			<!-- STEP TITLE -->

			<div class="opb-step-title">
				<h2><?php echo JText::translate('VAP_OPB_FIND_APP_TITLE'); ?></h2>
			</div>

			<!-- STEP BODY -->

			<div class="opb-step-body">
				
				<?php
				if ($login_req == 3 && !VikAppointments::isUserLogged())
				{
					// display login form in case the users must be logged in before accessing
					// the availability calendar of the services/employees
					echo VikAppointmentsOnepageBookingHelper::getLoginForm('search', 'vap-opb-form' . $module_id);
				}
				else
				{
					?>
					<!-- SERVICE -->

					<div class="opb-search-field service-field">

						<label for="vap-opb-search-service<?php echo $module_id; ?>"><?php echo JText::translate('VAP_OPB_SERVICE_LABEL'); ?></label>

						<select id="vap-opb-search-service<?php echo $module_id; ?>" data-id="<?php echo $module_id; ?>">
							<?php
							foreach ($groups as $group)
							{
								if (!empty($group->name))
								{
									?>
									<optgroup label="<?php echo htmlspecialchars($group->name); ?>">
									<?php
								}

								foreach ($group->services as $s)
								{
									?>
									<option
										value="<?php echo (int) $s->id; ?>"
										<?php echo $s->id == $selectedService->id ? 'selected="selected"' : ''; ?>
										data-random="<?php echo (int) $s->random_emp; ?>"
										data-first-date="<?php echo htmlspecialchars($s->first_date); ?>"
										data-last-date="<?php echo htmlspecialchars($s->last_date); ?>"
										data-max-capacity="<?php echo (int) $s->max_capacity; ?>"
										data-min-cap-res="<?php echo (int) $s->min_per_res; ?>"
										data-max-cap-res="<?php echo (int) $s->max_per_res; ?>"
										data-display-seats="<?php echo (int) $s->display_seats; ?>"
									>
										<?php echo $s->name; ?>
									</option>
									<?php
								}
								
								if (!empty($group->name))
								{
									?>
									</optgroup>
									<?php
								}
							}
							?>
						</select>

					</div>

					<!-- EMPLOYEE -->

					<div class="opb-search-field employee-field" style="<?php echo $employees ? '' : 'display: none;'; ?>">

						<label for="vap-opb-search-employee<?php echo $module_id; ?>"><?php echo JText::translate('VAP_OPB_EMPLOYEE_LABEL'); ?></label>

						<select id="vap-opb-search-employee<?php echo $module_id; ?>">
							<?php
							if ($selectedService->random_emp)
							{
								?><option></option><?php
							}

							foreach ($employees as $e)
							{
								?>
								<option value="<?php echo (int) $e->id; ?>"><?php echo $e->nickname; ?></option>
								<?php
							}
							?>
						</select>

					</div>

					<!-- PEOPLE -->

					<div class="opb-search-field people-field" style="<?php echo $selectedService->max_capacity > 1 && $selectedService->max_per_res > 1 ? '' : 'display: none;'; ?>">

						<label for="vap-opb-search-people<?php echo $module_id; ?>"><?php echo JText::translate('VAP_OPB_PEOPLE_LABEL'); ?></label>

						<select id="vap-opb-search-people<?php echo $module_id; ?>">
							<?php
							for ($i = $selectedService->min_per_res; $i <= $selectedService->max_per_res; $i++)
							{
								?>
								<option value="<?php echo $i; ?>"><?php echo JText::plural('VAP_N_PEOPLE', $i); ?></option>
								<?php
							}
							?>
						</select>

					</div>

					<!-- NEXT BUTTON -->

					<div class="opb-next-button-box">
						<button type="button" class="vap-btn blue" data-role="timeline.request">
							<?php echo JText::translate('VAP_OPB_SEARCH_BUTTON'); ?>
						</button>
					</div>
					<?php
				}
				?>

			</div>

		</div>

		<!-- STEP: timeline -->

		<div class="opb-step-wrapper timeline-box collapsed" data-step="timeline">

			<!-- TIME TEMPLATE -->

			<div class="opb-time-slot-tmpl" style="display: none;">
				<div class="opb-time-slot-wrapper">
					<div class="opb-time-slot">
						<div class="time-details">
							<div class="time-details-title">{title}</div>
							<div class="time-details-clock">
								<i class="fas fa-sign-in-alt"></i>
								<span class="time-details-clock-checkin">{checkin}</span>
								&nbsp;
								<i class="fas fa-sign-out-alt"></i>
								<span class="time-details-clock-checkout">{checkout}</span>
							</div>
							<div class="time-details-seats">{seats}</div>
						</div>
						<div class="time-price">
							<div class="time-price-total">{total_price}</div>
							<div class="time-price-person"><span>{price_per_person}</span>&nbsp;/&nbsp;<i class="fas fa-male"></i></div>
						</div>
						<div class="time-actions">
							<button type="button" class="vap-btn book-now"><?php echo JText::translate('VAP_OPB_BOOK_NOW_BUTTON'); ?></button>
							<div class="loading-target" style="display: none;"></div>
						</div>
					</div>
					<div class="opb-time-slot-extra" style="display: none;">
						<div class="opb-time-slot-options">
							
						</div>

						<div class="loading-target" style="display: none;"></div>

						<button type="button" class="vap-btn blue add-cart"><?php echo JText::translate('VAP_OPB_ADD_CART_BUTTON'); ?></button>
					</div>
				</div>
			</div>

			<!-- STEP BODY -->

			<div class="opb-step-body" style="display: none;">

				<div class="opb-timeline-date-filter">
					<a href="javascript:void(0)" class="prev-date-link">
						<i class="fas fa-angle-double-left" aria-hidden="true"></i>
						<span class="visually-hidden"><?php echo JText::translate('VAP_OPB_ARIA_PREV_DAY'); ?></span>
					</a>

					<div class="selected-date-wrapper">
						<input type="text" class="date-field" id="vap-opb-search-date<?php echo $module_id; ?>" value="" style="display: none;" />

						<i class="fas fa-calendar-alt"></i>
						<span class="selected-date-badge"></span>
					</div>

					<a href="javascript:void(0)" class="next-date-link">
						<i class="fas fa-angle-double-right" aria-hidden="true"></i>
						<span class="visually-hidden"><?php echo JText::translate('VAP_OPB_ARIA_NEXT_DAY'); ?></span>
					</a>
				</div>

				<?php
				// in case of multi-timezone setting enabled, display the timezone dropdown
				if ($config->getBool('multitimezone'))
				{
					?>
					<div class="opb-timeline-timezone">
						<label for="opb-timezone-list-<?php echo $module_id; ?>" class="visually-hidden"><?php echo JText::translate('VAP_OPB_ARIA_TIMEZONE'); ?></label>
						<?php echo VikAppointmentsOnepageBookingHelper::getTimezoneDropdown($module_id); ?>
					</div>
					<?php
				}
				?>

				<div class="opb-timeline-container<?php echo $params->get('scrollable_timeline') ? ' scrollable' : ''; ?>">

				</div>

				<!-- NEXT BUTTON -->

				<div class="opb-next-button-box" style="display: none;">
					<button type="button" class="vap-btn blue" data-role="booking.next">
						<?php echo JText::translate('VAP_OPB_NEXT_BUTTON'); ?>
					</button>
				</div>

			</div>

		</div>

		<?php
		// determine whether the billing box should be displayed or not
		$has_billing_box = VikAppointmentsOnepageBookingHelper::shouldDisplayBillingBox($cart, $customFields, $user, $zipFieldID);
		
		if ($has_billing_box)
		{
			?>
			<!-- STEP: billing -->

			<div class="opb-step-wrapper billing-box collapsed" data-step="billing">

				<!-- STEP TITLE -->

				<div class="opb-step-title">
					<h2><?php echo JText::translate('VAP_OPB_BILLING_TITLE'); ?></h2>
				</div>

				<!-- STEP BODY -->

				<div class="opb-step-body" style="display: none;">
					<?php
					if (($login_req == 1 || $login_req == 2) && !VikAppointments::isUserLogged())
					{
						// display login form in case of "optional" or "required on confirmation" setting
						echo VikAppointmentsOnepageBookingHelper::getLoginForm('billing', 'vap-opb-form' . $module_id);
					}
						
					// display step body only in case of optional login or for registered users
					if ($login_req < 2 || VikAppointments::isUserLogged())
					{
						?>
						<div class="opb-custom-fields">
							<div class="main-custom-fields">
								<?php
								/**
								 * Tries to auto-populate the fields with the details assigned
								 * to the currently logged-in user.
								 *
								 * The third boolean flag is set to instruct the method that the
								 * customers are usual to enter the first name before the last name.
								 * Use false to auto-populate the fields in the opposite way.
								 */
								VikAppointments::populateFields($customFields, $fields, $firstNameComesFirst = true);
								
								/**
								 * Render the custom fields form by using the apposite helper.
								 *
								 * Looking for a way to override the custom fields? Take a look
								 * at "/layouts/form/fields/" folder, which should contain all
								 * the supported types of custom fields.
								 */
								echo VAPCustomFieldsRenderer::display($customFields, $fields, [
									'strict' => true,
									'suffix' => '-mod-' . $module_id . '-',
								]);

								// only in case of guest users, try to display the ReCAPTCHA validation form
								$is_captcha = !$user && $vik->isGlobalCaptcha();

								if ($is_captcha)
								{
									?>
									<div class="opb-captcha">
										<?php echo $vik->reCaptcha(); ?>
									</div>
									<?php
								}
								?>
							</div>

							<?php
							// count the total number of attendees and, in case it is higher than 1, 
							// display the custom fields to collect the information of the other guests
							$attendees = VAPCartUtils::getAttendees($cart->getItemsList());

							if ($attendees > 1 && VAPCustomFieldsRenderer::hasRepeatableFields($customFields))
							{
								// iterate fields for any other attendee (excluded the first one)
								for ($attendee = 1; $attendee < $attendees; $attendee++)
								{
									?>
									<div class="attendee-custom-fields">
										<div class="attendee-title"><?php echo JText::sprintf('VAP_N_ATTENDEE', $attendee + 1); ?></div>

										<div class="attendee-fieldset">
											<?php
											// render the custom fields form by using the apposite helper
											echo VAPCustomFieldsRenderer::displayAttendee($attendee, $customFields);
											?>
										</div>
									</div>
									<?php
								}
							}
							?>
						</div>

						<!-- NEXT BUTTON -->

						<div class="opb-next-button-box">
							<button type="button" class="vap-btn blue" data-role="booking.next">
								<?php echo JText::translate('VAP_OPB_NEXT_BUTTON'); ?>
							</button>
						</div>
						<?php
					}
					?>
				</div>

			</div>
			<?php
		}
		?>

		<!-- STEP: summary -->

		<div class="opb-step-wrapper summary-box collapsed" data-step="summary">

			<!-- STEP TITLE -->

			<div class="opb-step-title">
				<h2><?php echo JText::translate('VAP_OPB_SUMMARY_TITLE'); ?></h2>
			</div>

			<!-- CART ITEM TMPL -->

			<div class="opb-cart-item-tmpl" style="display: none;">
				<div class="opb-cart-item">
					<div class="cart-item-details-outer">
						<div class="item-details">
							<div class="item-details-main">{details}</div>
							<div class="item-details-sub">
								<span class="item-checkout">{checkout}</span>
								<span class="item-people">
									<span class="item-people-inner">{people}</span>
									<i class="fas fa-users"></i>
								</span>
							</div>
						</div>
						<div class="item-total">{price}</div>
					</div>
					<div class="cart-item-options">{options}</div>
					<div class="cart-item-actions">
						<button type="button" class="vap-btn red cancel-appointment">
							<?php echo JText::translate('VAP_OPB_CANCEL_BUTTON'); ?>
						</button>
						<div class="loading-target" style="display: none;"></div>
					</div>
				</div>
			</div>

			<!-- STEP BODY -->

			<div class="opb-step-body" style="display: none;">

				<!-- CART ITEMS -->
				
				<div class="opb-cart-container">

				</div>

				<!-- COUPON -->

				<?php
				// check whether the form used to redeem the coupons should be displayed or not
				if (VikAppointments::hasCoupon('appointments'))
				{
					?>
					<div class="opb-cart-coupon">
						<input type="text" placeholder="<?php echo htmlspecialchars(JText::translate('VAPENTERYOURCOUPON')); ?>" aria-label="<?php echo htmlspecialchars(JText::translate('VAPENTERYOURCOUPON')); ?>" />

						<button type="button" class="vap-btn blue redeem-coupon">
							<?php echo JText::translate('VAP_OPB_REDEEM_COUPON_BUTTON'); ?>
						</button>

						<div class="loading-target" style="display: none;"></div>
					</div>
					<?php
				}
				?>

				<!-- CART TOTALS -->

				<div class="opb-cart-totals">
					<?php echo VikAppointmentsOnepageBookingHelper::getCartTotalsHtml($cart); ?>
				</div>

				<!-- PAYMENT METHODS -->

				<?php
				// display payment methods list, if any
				echo VikAppointmentsOnepageBookingHelper::getPaymentMethodsHtml($id_employee);
				?>

				<!-- NEXT BUTTON -->

				<div class="opb-next-button-box">
					<button type="button" class="vap-btn blue" data-role="booking.confirm">
						<?php echo JText::translate('VAP_OPB_CONFIRM_BUTTON'); ?>
					</button>

					<div class="loading-target" style="display: none;"></div>
				</div>

				<!-- HIDDEN CUSTOM FIELDS -->

				<?php
				if (!$has_billing_box)
				{
					echo VAPCustomFieldsRenderer::display($customFields, $fields);
				}
				?>

			</div>

		</div>

	</div>

</form>

<!-- LOADING TEMPLATE -->

<div class="vap-opb-loading-tmpl" style="display: none;">
	<div class="spinner">
		<div class="double-bounce1"></div>
		<div class="double-bounce2"></div>
	</div>
</div>

<script>
	(function(w) {
		if (typeof ONEPAGE_BOOKING_CONFIG === 'undefined')
		{
			w['ONEPAGE_BOOKING_CONFIG'] = {
				/**
				 * The list of currently booked items.
				 * 
				 * @var object
				 */
				cart: <?php echo json_encode($cart); ?>,

				/**
				 * The URL in which the module is published.
				 * 
				 * @var string
				 */
				currentUrl: '<?php echo addslashes((string) JUri::getInstance()); ?>',

				/**
				 * The URL used to fetch the list of employees assigned to the selected service.
				 * 
				 * @var string
				 */
				serviceEmployeesUrl: '<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=modules.serviceemployees'); ?>',

				/**
				 * The URL used to fetch the list of options assigned to the selected service.
				 * 
				 * @var string
				 */
				serviceOptionsUrl: '<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=modules.serviceoptions'); ?>',

				/**
				 * The URL used to fetch the availability timeline for the selected service/employee relation.
				 * 
				 * @var string
				 */
				timelineUrl: '<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=modules.timelineajax'); ?>',

				/**
				 * The URL used to add an item into the cart.
				 * 
				 * @var string
				 */
				addItemUrl: '<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=cart.additem'); ?>',

				/**
				 * The URL used to add an item into the cart.
				 * 
				 * @var string
				 */
				removeItemUrl: '<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=cart.removeitem'); ?>',

				/**
				 * The URL used to redeem a coupon code.
				 * 
				 * @var string
				 */
				redeemCouponUrl: '<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=cart.redeemcoupon'); ?>',

				/**
				 * The URL used to validate the ZIP Codes.
				 * 
				 * @var string
				 */
				validateZipUrl: '<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=confirmapp.checkzip'); ?>',

				/**
				 * The URL used to save the order.
				 * 
				 * @var string
				 */
				saveOrderUrl: '<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=confirmapp.saveorder&ajax=1' . ($itemid ? '&Itemid=' . $itemid : '')); ?>',

				/**
				 * The time format specified from the configuration panel.
				 * 
				 * @var string
				 */
				timeFormat: '<?php echo $config->get('timeformat'); ?>',

				/**
				 * Choose whether the shopping cart is enabled or whether the system accepts
				 * only one appointment per time.
				 * 
				 * @var bool
				 */
				isCartEnabled: <?php echo $config->getBool('enablecart') ? 'true' : 'false'; ?>,

				/**
				 * The ID of the custom field that should be used to validate the ZIP Code against
				 * the list of supported values.
				 * 
				 * @var int
				 */
				zipCustomField: <?php echo (int) $zipFieldID; ?>,

				/**
				 * Choose whether the module should perform a refresh before moving to the step that
				 * follows the timeline. This is useful to properly fetch payments and custom fields
				 * that might vary according to the booked items.
				 * 
				 * @var bool
				 */
				isRefreshNeededAfterBook: <?php echo VikAppointmentsOnepageBookingHelper::isRefreshNeededAfterBook() ? 'true' : 'false'; ?>,

				/**
				 * Checks whether the user has to solve a captcha at the billing stage.
				 * 
				 * @var bool
				 */
				isCaptchaEnabled: <?php echo !empty($is_captcha) ? 'true' : 'false'; ?>,

				/**
				 * Choose whether the unavailable time slots should be displayed or not.
				 * 
				 * @var bool
				 */
				hideUnavailable: <?php echo $params->get('hide_unavailable') ? 'true' : 'false'; ?>,

				/**
				 * Choose whether the price per person should be displayed or not.
				 * Even if this setting is enable, it will appear only when the number
				 * of selected participants is higher than 1.
				 * 
				 * @var bool
				 */
				showPricePerPerson: <?php echo $params->get('price_per_person') ? 'true' : 'false'; ?>,

				/**
				 * The minimum duration of the loading animation (in milliseconds).
				 * The animation won't be dismissed until the whole specified interval
				 * is passed.
				 * 
				 * Use false to disable the animation.
				 * 
				 * @var int|bool
				 */
				animationDuration: <?php echo $params->get('loading_animation') ? (int) $params->get('animation_duration') : 'false'; ?>,
			};
		}
	})(window);
</script>
