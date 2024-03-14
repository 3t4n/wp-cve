<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

// load cart scripts
JHtml::fetch('vaphtml.sitescripts.cart');

$config   = VAPFactory::getConfig();
$currency = VAPFactory::getCurrency();

$cart_expanded = VAPFactory::getConfig()->getBool('confcartdisplay');

$vik = VAPApplication::getInstance();

?>

<div class="vapsummarycont">

	<div class="vapsummaryoptionsheadtitle"><?php echo JText::translate('VAPORDERSUMMARYHEADTITLE'); ?></div>
	
	<div class="vapsummaryservicescont">
	
		<?php
		$uid = 0;

		foreach ($this->groupItems() as $service)
		{
			?>		
			<div class="vapcartitemdiv" id="vapcartitemdiv<?php echo $service->id; ?>">
		
				<div class="vapcartitemleft">
					<a href="javascript:void(0)"
						onClick="vapCartExtendItem(<?php echo $service->id; ?>);"
						class="vapcartexplink <?php echo ($cart_expanded ? 'vapcartexpopened' : 'vapcartexphidden'); ?>"
						id="vapcartexplink<?php echo $service->id; ?>"
					>
						<span class="vapcartitemname">
							<i class="fas fa-angle-<?php echo $cart_expanded ? 'down' : 'right'; ?>"></i>
							<?php echo $service->name; ?>
						</span>
					</a>
				</div>

				<div class="vapcartitemright">
					<div class="vapcartitemprice" id="vapcartgroupitemprice<?php echo $service->id; ?>">
						<?php 
						$group_cost = VAPCartUtils::getServiceTotalCost($this->cart->getItemsList(), $service->id);
						
						if ($group_cost > 0)
						{
							echo $currency->format($group_cost);
						}
						?>
					</div>
				</div>
			
				<div class="vapcartinneritemscont" id="vapcartinneritemscont<?php echo $service->id; ?>" style="<?php echo ($cart_expanded ? '' : 'display: none;'); ?>">
			
					<?php
					foreach ($service->list as $item)
					{
						$uid++;
						?>
						<div class="vapcartinneritemdiv" id="vapcartinneritemdiv<?php echo $uid; ?>">
			
							<div class="vapcartinitemup">

								<div class="vapcartinitemupleft">

									<div class="vapcartitemexp">

										<a href="javascript: void(0);"
											onClick="vapCartOpenDetails(<?php echo $uid; ?>);"
											class="vapcartitemdetlink"
										>
											<span>
												<i class="fas fa-bars"></i>
												<?php echo $item->getCheckinDate(JText::translate('DATE_FORMAT_LC2'), VikAppointments::getUserTimezone()); ?>
											</span>
										</a>
									
										<!-- START MODAL BOX - Summary - Options list -->

										<div class="vapcartitemboxdialog" id="vapcartitemboxdialog<?php echo $uid; ?>" style="<?php echo ($cart_expanded ? '' : 'display: none;'); ?>">
											
											<div class="vapcartitemboxdetails"><?php echo $item->getDetails(); ?></div>
											
											<div class="vapcartitemboxoptionscont">
												<?php
												foreach ($item->getOptionsList() as $option)
												{
													?>
													<div class="vapcartitemboxoptiondiv" id="vapcartoption<?php echo $uid . '-' . $option->getID(); ?>">

														<div class="vapcartitemboxoptionleft">
															<?php echo $option->getName(); ?>
														</div>

														<div class="vapcartitemboxoptioncenter">
															<span class="vapcartitemboxoptionprice">
																<?php
																if ($option->getPrice() != 0)
																{
																	 echo $currency->format($option->getPrice());
																}
																?>
															</span>

															<?php
															// display number of units only in case the option allows a multi-selection
															if ($option->getMaxQuantity() > 1)
															{
																?>
																<span class="vapcartitemboxoptionquant" id="vapcartitemboxoptionquant<?php echo $uid . '-' . $option->getID(); ?>">
																	<?php echo JText::translate('VAPCARTQUANTITYSUFFIX') . $option->getQuantity(); ?>
																</span>
																<?php
															}
															?>
														</div>

														<div class="vapcartitemboxoptionright">
															<?php
															if ($option->getMaxQuantity() > 1 && !$option->getDuration())
															{
																?>
																<a
																	href="javascript:void(0)"
																	onClick="vapAddCartOption(<?php echo $uid . ',' . $option->getID() . ',' . $service->id . ',' . $item->getEmployeeID() . ',\'' . $item->getCheckinDate() . '\''; ?>);"
																	class="vapcartaddbtn"
																>
																	<i class="fas fa-plus-circle"></i>
																</a>
																<?php
															}

															if ($option->getMaxQuantity() > 1 || !$option->isRequired())
															{
																?>
																<a
																	href="javascript:void(0)"
																	onClick="vapRemoveCartOption(<?php echo $uid . ',' . $option->getID() . ',' . $service->id . ',' . $item->getEmployeeID() . ',\'' . $item->getCheckinDate() . '\''; ?>);"
																	class="vapcartremovebtn"
																>
																	<i class="fas fa-minus-circle"></i>
																</a>
																<?php
															}
															?>
														</div>

													</div>
													<?php
												}
												?>
											</div>

											<div class="vapcartitemboxoptionsbottom">
												<span class="vapcartitemboxoptionsdur">
													<?php
													echo VikAppointments::formatMinutesToTime($item->getDuration());

													/**
													 * Display checkout time.
													 *
													 * @since 1.6
													 */
													$checkout = $item->getCheckoutDate($config->get('timeformat'), VikAppointments::getUserTimezone());
													echo ' (' . JText::sprintf('VAPCHECKOUTAT', $checkout) . ')';
													?>
												</span>

												<?php
												if ($item->getPeople() > 1)
												{
													?>
													<span class="vapcartitemboxoptionspeople">
														<?php echo $item->getPeople(); ?>
														<i class="fas fa-users"></i>
													</span>
													<?php
												}
												?>

												<span class="vapcartitemboxoptionstcost">
													<?php
													if ($item->getPrice() > 0)
													{
														echo $currency->format($item->getPrice());
													}
													?>
												</span>
											</div>

										</div>

										<!-- END MODAL BOX -->

									</div>

								</div>

							</div>

							<div class="vapcartinitemupright">
								<div class="vapcartitemprice"  id="vapcartitemtcost<?php echo $uid; ?>">
									<?php
									$total = $item->getTotalCost();

									if ($total > 0)
									{
										echo $currency->format($total);
									}
									else
									{
										echo '&nbsp;'; 
									}
									?>
								</div>

								<div class="vapcartitemright">
									<a 
										href="javascript: void(0);"
										onClick="vapRemoveService(<?php echo $uid . ',' . $service->id . ',' . $item->getEMployeeID() . ',\'' . $item->getCheckinDate() . '\''; ?>);"
										class="vapcartremovebtn"
									>
										<i class="fas fa-minus-circle"></i>
									</a>
								</div>
							</div>
						</div>
						<?php
					}
					?>

				</div>

			</div>
			<?php
		}
		?>
	
	</div>

	<div id="vap-cart-totals-wrapper">
		<?php
		/**
		 * The cart totals are now displayed by using an apposite layout file.
		 * This way we can reuse the same block every time the cart is updated
		 * via AJAX.
		 * 
		 * @since 1.7
		 */
		$data = array(
			/**
			 * The current cart instance. If not provided, it will be loaded
			 * from the user session.
			 *
			 * @var VAPCart
			 */
			'cart' => $this->cart,

			/**
			 * The customer details assigned to the currently logged-in user.
			 * If omitted, the user details will be loaded by the layout.
			 * In case the user is not logged-in, NULL will be used.
			 * 
			 * @var object|null
			 */
			'user' => $this->user,
		);

		/**
		 * The cart totals block is displayed from the layout below:
		 * /components/com_vikappointments/layouts/blocks/carttotals.php
		 * 
		 * If you need to change something from this layout, just create
		 * an override of this layout by following the instructions below:
		 * - open the back-end of your Joomla
		 * - visit the Extensions > Templates > Templates page
		 * - edit the active template
		 * - access the "Create Overrides" tab
		 * - select Layouts > com_vikappointments > blocks
		 * - start editing the carttotals.php file on your template to create your own layout
		 *
		 * @since 1.6
		 */
		echo JLayoutHelper::render('blocks.carttotals', $data);
		?>
	</div>
	
</div>

<?php
JText::script('VAPFREE');
JText::script('VAPCARTQUANTITYSUFFIX');
?>

<script>

	var vap_t_price = <?php echo $this->cart->getTotalCost(); ?>;

	/**
	 * Expands the details of the clicked service parent.
	 *
	 * @param 	integer  id  The service ID.
	 * 
	 * @return 	void
	 */
	function vapCartExtendItem(id) {
		var obj = jQuery('#vapcartinneritemscont' + id);

		if (!obj.is(':visible')) {
			jQuery('#vapcartinneritemscont' + id).slideDown('fast');
			jQuery('#vapcartexplink' + id).addClass('vapcartexpopened');
			jQuery('#vapcartexplink' + id).removeClass('vapcartexphidden');

			jQuery('#vapcartexplink' + id).find('i').removeClass('fa-angle-right').addClass('fa-angle-down');
		} else {
			jQuery('#vapcartinneritemscont' + id).slideUp('fast');
			jQuery('#vapcartexplink' + id).removeClass('vapcartexpopened');
			jQuery('#vapcartexplink' + id).addClass('vapcartexphidden');

			jQuery('#vapcartexplink' + id).find('i').removeClass('fa-angle-down').addClass('fa-angle-right');
		}
	}

	/**
	 * Expands the details of the clicked appointment.
	 *
	 * @param 	integer  id  The appointment unique ID.
	 * 
	 * @return 	void
	 */
	function vapCartOpenDetails(id) {
		if (jQuery('#vapcartitemboxdialog' + id).is(':visible')) {
			jQuery('#vapcartitemboxdialog' + id).slideUp('fast');
		} else {
			jQuery('#vapcartitemboxdialog' + id).slideDown('fast');
		}
	}

	/**
	 * Permanently removes the selected service from the cart.
	 *
	 * @param 	integer  id_html      The unique ID of the HTML block.
	 * @param 	integer  id_service   The service ID of the appointment.
	 * @param 	integer  id_employee  The employee ID of the appointment.
	 * @param   string   checkin      The check-in date time of the appointment.
	 * 
	 * @return 	void
	 */
	function vapRemoveService(id_html, id_service, id_employee, checkin) {
		// delete item from cart
		vapRemoveCartItemRequest(id_service, id_employee, checkin).then((data) => {
			// check whether the service container owns more than a service
			if (jQuery('#vapcartinneritemscont' + id_service).children().length > 1) {
				// delete only the block of the selected service
				jQuery('#vapcartinneritemdiv' + id_html).remove();

				// refresh total of the service group
				vapUpdateCartServicePrice(id_service, data.groupCost);
			} else {
				// remove the whole service block, since there are no remaining children
				jQuery('#vapcartitemdiv' + id_service).remove();
			}

			vapUpdateCartTotals(data.totalsHtml);

			// check whether the response contains the redirect URL
			if (data.redirect) {
				// cart is empty, reach the specified URL
				document.location.href = data.redirect;
			}
		}).catch((err) => {
			// display error message
			alert(err);
		});
	}

	/**
	 * Decreases the units of the selected options.
	 *
	 * @param 	integer  id_html      The unique ID of the HTML block.
	 * @param 	integer  id_option    The ID of the option to decrease.
	 * @param 	integer  id_service   The service ID of the appointment.
	 * @param 	integer  id_employee  The employee ID of the appointment.
	 * @param   string   checkin      The check-in date time of the appointment.
	 * @param 	integer  units        The units to decrease (1 by default).
	 * 
	 * @return 	void
	 */
	function vapRemoveCartOption(id_html, id_option, id_service, id_employee, checkin, units) {
		// descrease the units of the selected option by one
		vapRemoveCartOptionRequest(id_option, id_service, id_employee, checkin, units).then((data) => {
			if (data.quantity > 0) {
				// there are still other options, update the remaining units
				jQuery('#vapcartitemboxoptionquant' + id_html + '-' + id_option).html(Joomla.JText._('VAPCARTQUANTITYSUFFIX') + data.quantity);
			} else {
				// no more options, delete whole row
				jQuery('#vapcartoption' + id_html + '-' + id_option).remove();
			}

			vapUpdateCartTotals(data.totalsHtml);
			vapUpdateCartItemPrice(id_html, data.itemTotal);
			vapUpdateCartServicePrice(id_service, data.groupCost);
		}).catch((err) => {
			// display error message
			alert(err);
		});
	}

	/**
	 * Increases the units of the selected options.
	 *
	 * @param 	integer  id_html      The unique ID of the HTML block.
	 * @param 	integer  id_option    The ID of the option to increase.
	 * @param 	integer  id_service   The service ID of the appointment.
	 * @param 	integer  id_employee  The employee ID of the appointment.
	 * @param   string   checkin      The check-in date time of the appointment.
	 * @param 	integer  units        The units to increase (1 by default).
	 * 
	 * @return 	void
	 */
	function vapAddCartOption(id_html, id_option, id_service, id_employee, checkin, units) {
		// increase the units of the selected option by one
		vapAddCartOptionRequest(id_option, id_service, id_employee, checkin, units).then((data) => {
			// update remaining quantity
			jQuery('#vapcartitemboxoptionquant' + id_html + '-' + id_option).html(Joomla.JText._('VAPCARTQUANTITYSUFFIX') + data.quantity);
			 
			vapUpdateCartTotals(data.totalsHtml);
			vapUpdateCartItemPrice(id_html, data.itemTotal);
			vapUpdateCartServicePrice(id_service, data.groupCost);
		}).catch((err) => {
			// display error message
			alert(err);
		});
	}

	/**
	 * Updates the cart totals.
	 *
	 * @param 	string  html  The HTML to update.
	 * 
	 * @return 	void
	 */
	function vapUpdateCartTotals(html) {
		jQuery('#vap-cart-totals-wrapper')
			.html(html)
			.find('.hasTooltip')
				.tooltip({container: 'body'});
	}

	/**
	 * Updates the price of the selected appointment.
	 *
	 * @param 	integer  item_id  The unique ID of the appointment.
	 * @param 	float    price    The appointment price (inclusive of options).
	 * 
	 * @return 	void
	 */
	function vapUpdateCartItemPrice(item_id, price) {
		jQuery('#vapcartitemtcost' + item_id).html(Currency.getInstance().format(price));
	}

	/**
	 * Updates the price of the selected group of services.
	 *
	 * @param 	integer  service_id  The unique ID of the service group.
	 * @param 	float    price       The sum of the services prices that belong
	 *                               to this group.
	 * 
	 * @return 	void
	 */
	function vapUpdateCartServicePrice(service_id, price) {
		var _html = '';

		if (price > 0) {
			_html = Currency.getInstance().format(price);
		} else {
			_html = '';
		}

		jQuery('#vapcartgroupitemprice' + service_id).html(_html);
	}

</script>
