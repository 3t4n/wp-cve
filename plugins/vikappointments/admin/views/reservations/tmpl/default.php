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

JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('formbehavior.chosen');
JHtml::fetch('vaphtml.status.contextmenu', 'appointments', '.status-hndl');

$rows = $this->rows;

$filters = $this->filters;

$vik = VAPApplication::getInstance();

$user = JFactory::getUser();

$config   = VAPFactory::getConfig();
$currency = VAPFactory::getCurrency();

$canEdit      = $user->authorise('core.edit', 'com_vikappointments');
$canEditState = $user->authorise('core.edit.state', 'com_vikappointments');

$is_searching = $this->hasFilters();
$is_disabled  = $filters['res_id'] > 0 ? 'disabled="disabled"' : '';

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewReservationsList". The event method receives the
 * view instance as argument.
 *
 * @since 1.6.6
 */
$forms = $this->onDisplayListView($is_searching);

/**
 * Prepares CodeMirror editor scripts for being used
 * via Javascript/AJAX.
 *
 * @wponly
 */
$vik->prepareEditor('codemirror');

// get listable columns
$listable_fields = VikAppointments::getListableFields();
// get custom fields that should be displayed in the list
$listable_cf = VikAppointments::getListableFields($custom = true);

// get default user timezone
$default_tz = JFactory::getUser()->getTimezone();

$multi_orders_cols = 0;

?>

<form action="index.php?option=com_vikappointments" method="post" name="adminForm" id="adminForm">
	
	<div class="btn-toolbar" style="height: 32px;">

		<div class="btn-group pull-left input-append">
			<input type="text" name="keysearch" id="vapkeysearch" class="vapkeysearch" size="32" <?php echo $is_disabled; ?>
				value="<?php echo $this->escape($filters['keysearch']); ?>" placeholder="<?php echo $this->escape(JText::translate('JSEARCH_FILTER_SUBMIT')); ?>" />

			<button type="submit" class="btn">
				<i class="fas fa-search"></i>
			</button>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewReservationsList","type":"search","key":"search"} -->

		<?php
		if ($filters['res_id'] == 0)
		{
			// plugins can use the "search" key to introduce custom
			// filters within the search bar
			if (isset($forms['search']))
			{
				echo $forms['search'];
			}
		}
		?>

		<div class="btn-group pull-left">
			<button type="button" class="btn<?php echo ($is_searching ? ' btn-primary' : ''); ?><?php echo ($filters['res_id'] ? ' disabled' : ''); ?>"
				<?php echo $is_disabled; ?> onclick="vapToggleSearchToolsButton(this);">
				<?php echo JText::translate('JSEARCH_TOOLS'); ?>&nbsp;<i class="fas fa-caret-<?php echo ($is_searching ? 'up' : 'down'); ?>" id="vap-tools-caret"></i>
			</button>
		</div>

		<div class="btn-group pull-left">
			<button type="button" class="btn" onclick="clearFilters();">
				<?php echo JText::translate($filters['res_id'] ? 'JTOOLBAR_BACK' : 'JSEARCH_FILTER_CLEAR'); ?>
			</button>
		</div>

		<?php
		if ($filters['res_id'] && count($rows))
		{
			?>
			<div class="btn-group pull-right">
				<a href="<?php echo $vik->addUrlCsrf('index.php?option=com_vikappointments&task=reservation.notify&cid[]=' . $filters['res_id'], $xhtml = true); ?>" class="btn btn-primary">
					<i class="fas fa-paper-plane"></i>&nbsp;
					<?php echo JText::translate('VAPMANAGERESERVATION31'); ?>
				</a>
			</div>
			<?php
		}

		if (($filters['res_id'] || count($rows) == 1) && $rows && strlen((string) $rows[0]['cc_data']))
		{
			?>
			<div class="btn-group pull-right">
				<button type="button" class="btn btn-primary" onclick="vapOpenJModal('ccdetails', null, true); return false;">
					<i class="fas fa-credit-card"></i>&nbsp;
					<?php echo JText::translate('VAPSEECCDETAILS'); ?>
				</button>
			</div>
			<?php
		}
		?>

	</div>

	<?php
	/**
	 * Display search tools only in case we are not focusing a single order.
	 *
	 * @since 1.6.3
	 */
	if ($filters['res_id'] == 0)
	{
		?>
		<div class="btn-toolbar" id="vap-search-tools" style="height: 32px;<?php echo ($is_searching ? '' : 'display: none;'); ?>">

			<?php
			$options = JHtml::fetch('vaphtml.admin.statuscodes', $group = 'appointments', $blank = true);
			// add closure status to the list
			$options[] = JHtml::fetch('select.option', 'CLOSURE', 'VAPSTATUSCLOSURE');
			?>
			<div class="btn-group pull-left">
				<select name="status" id="vap-status-sel" class="<?php echo (!empty($filters['status']) ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
					<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['status'], true); ?>
				</select>
			</div>

			<?php
			$options = JHtml::fetch('vaphtml.admin.payments', $type = 'appointments', $blank = true);

			/**
			 * Display payments filter only in case of non-empty list.
			 * Must be greater than 1 because the list includes an empty option.
			 *
			 * @since 1.6.3
			 */
			if (count($options) > 1)
			{
				?>
				<div class="btn-group pull-left">
					<select name="id_payment" id="vap-payment-sel" class="<?php echo (!empty($filters['id_payment']) ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
						<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['id_payment'], true); ?>
					</select>
				</div>
				<?php
			}
			?>

			<?php
			$options = JHtml::fetch('vaphtml.admin.locations', $id = null, $blank = true);

			/**
			 * Display locations filter only in case of non-empty list.
			 * Must be greater than 1 because the list includes an empty option.
			 *
			 * @since 1.7
			 */
			if (count($options) > 1)
			{
				?>
				<div class="btn-group pull-left">
					<select name="id_location" id="vap-location-sel" class="<?php echo (!empty($filters['id_location']) ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
						<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['id_location'], true); ?>
					</select>
				</div>
				<?php
			}
			?>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewReservationsList","type":"search","key":"filters"} -->

			<?php
			// plugins can use the "filters" key to introduce custom
			// filters within the search tools
			if (isset($forms['filters']))
			{
				echo $forms['filters'];
			}
			?>

			<div class="btn-group pull-left">
				<?php echo $vik->calendar($filters['datestart'], 'datestart', 'vapdatestart', null, array('onChange' => 'document.adminForm.submit();')); ?>
			</div>

			<?php
			if (!VAPDateHelper::isNull($filters['datestart']))
			{
				?>
				<div class="btn-group pull-left">
					<?php echo $vik->calendar($filters['dateend'], 'dateend', 'vapdateend', null, array('onChange' => 'document.adminForm.submit();')); ?>
				</div>
				<?php
			}
			?>

		</div>
		<?php
	}

if (count($rows) == 0)
{
	echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
}
else
{
	/**
	 * Trigger event to display custom columns.
	 *
	 * @since 1.7
	 */
	$columns = $this->onDisplayTableColumns();
	?>

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayReservationsTableTH","type":"th"} -->

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayReservationsTableTD","type":"td"} -->

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>

				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<?php
				if (in_array('id', $listable_fields))
				{
					?>
					<th class="<?php echo $vik->getAdminThClass('left nowrap hidden-phone'); ?>" width="1%" style="text-align: left;">
						<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGERESERVATION0', 'r.id', $this->orderDir, $this->ordering); ?>
					</th>
					<?php
				}

				if (in_array('sid', $listable_fields))
				{
					?>
					<th class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>" width="10%" style="text-align: left;">
						<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGERESERVATION2', 'r.createdon', $this->orderDir, $this->ordering); ?>
					</th>
					<?php
				}

				if (in_array('checkin_ts', $listable_fields))
				{
					$multi_orders_cols++;
					?>
					<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="10%" style="text-align: left;">
						<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGERESERVATION26', 'r.checkin_ts', $this->orderDir, $this->ordering); ?>
					</th>
					<?php
				}

				if (in_array('employee', $listable_fields))
				{
					$multi_orders_cols++;
					?>
					<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="10%" style="text-align: left;">
						<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGERESERVATION3', 'e.nickname', $this->orderDir, $this->ordering); ?>
					</th>
					<?php
				}

				if (in_array('service', $listable_fields))
				{
					$multi_orders_cols++;
					?>
					<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="10%" style="text-align: left;">
						<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGERESERVATION4', 's.name', $this->orderDir, $this->ordering); ?>
					</th>
					<?php
				}

				if (in_array('info', $listable_fields))
				{
					?>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="5%" style="text-align: center;">
						<?php echo JText::translate('VAPMANAGERESERVATION20');?>
					</th>
					<?php
				}

				if (in_array('nominative', $listable_fields))
				{
					?>
					<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="10%" style="text-align: left;">
						<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGERESERVATION38', 'r.purchaser_nominative', $this->orderDir, $this->ordering); ?>
					</th>
					<?php
				}

				if (in_array('phone', $listable_fields))
				{
					?>
					<th class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>" width="8%" style="text-align: left;">
						<?php echo JText::translate('VAPMANAGECUSTOMER4'); ?>
					</th>
					<?php
				}

				/**
				 * Display here the custom fields that should be shown
				 * within the head of the table.
				 */
				foreach ($this->customFields as $field)
				{
					if (in_array($field['name'], $listable_cf))
					{
						?>
						<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="10%" style="text-align: center;">
							<?php echo $field['langname']; ?>
						</th>
						<?php
					}
				}

				/**
				 * Display here the custom columns fetched by third-party plugins.
				 *
				 * @since 1.7
				 */
				foreach ($columns as $k => $col)
				{
					?>
					<th data-id="<?php echo $this->escape($k); ?>" class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>">
						<?php echo $col->th; ?>
					</th>
					<?php
				}

				if (in_array('total', $listable_fields))
				{
					?>
					<th class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>" width="8%" style="text-align: left;">
						<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGERESERVATION9', 'r.total_cost', $this->orderDir, $this->ordering); ?>
					</th>
					<?php
				}

				if (in_array('payment', $listable_fields))
				{
					?>
					<th class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>" width="10%" style="text-align: left;">
						<?php echo JText::translate('VAPMANAGERESERVATION13');?>
					</th>
					<?php
				}

				if (in_array('coupon', $listable_fields))
				{
					?>
					<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="5%" style="text-align: center;">
						<?php echo JText::translate('VAPMANAGERESERVATION21');?>
					</th>
					<?php
				}

				if (in_array('invoice', $listable_fields))
				{
					?>
					<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="5%" style="text-align: center;">
						<?php echo JText::translate('VAPMANAGERESERVATION35');?>
					</th>
					<?php
				}
				
				if (in_array('status', $listable_fields))
				{
					?>
					<th class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>" width="10%" style="text-align: left;">
						<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGERESERVATION12', 'r.status', $this->orderDir, $this->ordering); ?>
					</th>
					<?php
				}
				?>

			</tr>
		<?php echo $vik->closeTableHead(); ?>
		
		<?php
		for ($i = 0, $n = count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];

			// create check-in dates based on the employee timezone
			$checkin  = new JDate($row['checkin_ts']);
			$checkout = new JDate(VikAppointments::getCheckout($row['checkin_ts'], $row['duration']));

			if ($row['timezone'] && $row['timezone'] != $default_tz->getName())
			{
				$tz = new DateTimeZone($row['timezone']);

				$checkin->setTimezone($tz);
				$checkout->setTimezone($tz);

				// display times adjusted to the employee timezone
				$tz_str = str_replace('_', ' ', $row['timezone']) . "<br />"
					. $checkin->format(JText::translate('DATE_FORMAT_LC2'), $local = true) . "<br />"
					. $checkout->format(JText::translate('DATE_FORMAT_LC2'), $local = true);
			}
			else
			{
				$tz_str = '';
			}

			// adjust times to local offset (of the current logged-in user)
			$checkin->setTimezone($default_tz);
			$checkout->setTimezone($default_tz);
			
			$edit_link = 'index.php?option=com_vikappointments&amp;task=reservation.edit&amp;cid[]=' . $row['id'];
			
			if ($row['id_parent'] == -1 && $filters['res_id'] <= 0)
			{
				$edit_link = 'index.php?option=com_vikappointments&amp;view=reservations&amp;res_id=' . $row['id'];
			}

			$oid_tooltip = "";
			
			if ($row['createdon'] != -1)
			{
				if ($row['createdby'] > 0)
				{
					$created_by = $row['author'];
				}
				else
				{
					$created_by = JText::translate('VAPRESLISTGUEST');
				}

				$created_on = JHtml::fetch('date', $row['createdon'], JText::translate('DATE_FORMAT_LC1') . ' ' . $config->get('timeformat'));

				$oid_tooltip = JText::sprintf('VAPRESLISTCREATEDTIP', $created_on, $created_by);
			}

			if ($row['closure'])
			{
				$row['status'] = 'CLOSURE';
			}

			// decode stored CF data
			$cf_json = (array) json_decode($row['custom_f'], true);

			/**
			 * Translate custom fields values stored in the database.
			 *
			 * @since 1.6.4
			 */
			$cf_json = VAPCustomFieldsLoader::translateObject($cf_json, $this->customFields);
			?>
			<tr class="row<?php echo ($i % 2); ?>">
				
				<td>
					<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>">
				</td>
				
				<?php
				if (in_array('id', $listable_fields))
				{
					?>
					<td class="hidden-phone nowrap">
						<span class="hasTooltip" title="<?php echo $oid_tooltip; ?>">
							<?php echo $row['id']; ?>
						</span>
					</td>
					<?php
				}

				if (in_array('sid', $listable_fields))
				{
					?>
					<td class="hidden-phone">
						<?php
						if ($row['closure'] == 0)
						{
							?>
							<div>
								<?php
								if ($canEdit)
								{
									?>
									<a href="<?php echo $edit_link; ?>">
										<?php echo $row['sid']; ?>
									</a>
									<?php
								}
								else
								{
									echo $row['sid'];
								}
								?>
							</div>
							<?php
						}

						if (!VAPDateHelper::isNull($row['createdon']))
						{
							?>
							<div class="td-secondary">
								<?php echo JHtml::fetch('date', JDate::getInstance($row['createdon']), JText::translate('DATE_FORMAT_LC3') . ' ' . $config->get('timeformat')); ?>
							</div>
							<?php
						}
						?>
					</td>
					<?php
				}
				
				// make sure we don't have a multi-order parent
				if ($row['id_parent'] != -1 || $row['closure'])
				{
					if (in_array('checkin_ts', $listable_fields))
					{
						?>
						<td class="nowrap">
							<div class="td-primary">
								<?php
								// make checkin date clickable to access the details of the reservation
								// in case the "Order Key" column is turned off
								if ($canEdit && !in_array('sid', $listable_fields))
								{
									?>
									<a href="<?php echo $edit_link; ?>">
										<?php echo $checkin->format(JText::translate('DATE_FORMAT_LC3'), $local = true); ?>
									</a>
									<?php
								}
								else
								{
									echo $checkin->format(JText::translate('DATE_FORMAT_LC3'), $local = true);
								}

								if ($tz_str)
								{
									?>
									<div class="td-pull-right">
										<i class="fas fa-globe-americas hasTooltip" title="<?php echo $this->escape($tz_str); ?>"></i>
									</div>
									<?php
								}
								?>
							</div>

							<div class="td-secondary">
								<span class="checkin-time">
									<i class="fas fa-sign-in-alt"></i>
									<?php echo $checkin->format($config->get('timeformat'), $local = true); ?>
								</span>

								<?php
								if (in_array('checkout', $listable_fields))
								{
									?>
									<span class="checkin-time" style="margin-left: 4px;">
										<i class="fas fa-sign-out-alt"></i>
										<?php echo $checkout->format($config->get('timeformat'), $local = true); ?>
									</span>
									<?php
								}
								?>
							</div>

							<?php
							if (in_array('status', $listable_fields))
							{
								?>
								<div class="mobile-only">
									<?php
									if ($row['closure'] == 0)
									{
										echo JHtml::fetch('vaphtml.status.display', $row['status'], 'badge');
									}
									else
									{
										?>
										<span class="badge badge-important"><?php echo JText::translate('VAPSTATUSCLOSURE'); ?></span>
										<?php
									}
									?>
								</div>
								<?php
							}
							?>
						</td>
						<?php
					}

					if (in_array('employee', $listable_fields))
					{
						?>
						<td>
							<div class="td-primary">
								<?php echo $row['employee_name']; ?>
							</div>
						</td>
						<?php
					}
					
					if (in_array('service', $listable_fields))
					{
						?>
						<td>
							<div class="td-primary">
								<?php echo $row['service_name']; ?>
							</div>

							<div class="td-secondary">
								<span class="td-pull-left">
									<i class="fas fa-stopwatch"></i>
									<?php echo VikAppointments::formatMinutesToTime($row['duration']); ?>
								</span>

								<?php
								// do not show in case of a single participant
								if (in_array('people', $listable_fields) && $row['people'] > 1)
								{
									?>
									<span class="td-pull-right">
										<?php echo $row['people']; ?>
										<i class="fas fa-male"></i><i class="fas fa-male" style="margin-left: 1px;"></i>
									</span>
									<?php
								}
								?>
							</div>
						</td>
						<?php
					}
				}
				else if ($multi_orders_cols > 0)
				{
					?>
					<td colspan="<?php echo $multi_orders_cols; ?>" style="text-align: center;">
						<a href="<?php echo $edit_link; ?>">
							<span class="vaporderparentbox">
								<?php
								if ($filters['res_id'] != $row['id'])
								{
									?>
									<i class="fas fa-layer-group"></i>&nbsp;
									<?php
									echo JText::translate('VAPMANAGERESERVATION29');
								}
								else
								{
									?>
									<i class="fas fa-pen-square"></i>&nbsp;
									<?php
									echo JText::translate('VAPEDIT');
								}
								?>
							</span>
						</a>
					</td>
					<?php
				}
				
				if (in_array('info', $listable_fields))
				{
					?>
					<td style="text-align: center;">
						<?php
						if ($row['id_parent'] > 0)
						{
							?>
							<a href="javascript:void(0)" onclick="openOrderModal(<?php echo $row['id']; ?>);">
								<i class="fas fa-ticket-alt big"></i>
							</a>
							<?php
						}
						else if ($row['closure'] == 0)
						{
							if ($filters['res_id'] != $row['id'])
							{
								?>
								<a href="index.php?option=com_vikappointments&amp;view=reservations&amp;res_id=<?php echo $row['id']; ?>">
									<i class="fas fa-ticket-alt big"></i>
								</a>
								<?php
							}
							else
							{
								// we are already filtering for this multi-order
								?>
								<a class="disabled">
									<i class="fas fa-ticket-alt big"></i>
								</a>
								<?php
							}
						}
						else
						{
							?>
							<i class="fas fa-ban big no"></i>
							<?php
						}
						?>
					</td>
					<?php
				}
				
				if (in_array('nominative', $listable_fields))
				{
					?>
					<td>
						<?php
						// use primary for mail in case the nominative is empty
						$mail_class = 'td-primary';

						if ($row['purchaser_nominative'])
						{
							// nominative not empty, use secondary class for mail
							$mail_class = 'td-secondary';
							?>
							<div class="td-primary">
								<?php
								if ($row['id_user'] > 0)
								{
									?>
									<a href="javascript:void(0)" onclick="openCustomerModal(<?php echo $row['id_user']; ?>);">
										<?php echo $row['purchaser_nominative']; ?>
									</a>
									<?php
								}
								else
								{
									echo $row['purchaser_nominative'];
								}
								?>
							</div>
							<?php
						}

						if (in_array('mail', $listable_fields))
						{
							?>
							<div class="<?php echo $mail_class; ?>">
								<?php echo $row['purchaser_mail']; ?>
							</div>
							<?php
						}
						?>
					</td>
					<?php
				}

				if (in_array('phone', $listable_fields))
				{
					?>
					<td class="hidden-phone">
						<?php echo $row['purchaser_phone']; ?>
					</td>
					<?php
				}

				/**
				 * Display here the custom fields that should be shown
				 * within the body of the table.
				 */
				foreach ($this->customFields as $field)
				{
					if (in_array($field['name'], $listable_cf))
					{
						?>
						<td style="text-align: center;" class="hidden-phone">
							<?php
							/**
							 * Translate field name in order to support
							 * those fields that still use the old
							 * translation method.
							 *
							 * @since 1.6.5
							 */
							// $field['name'] = JText::translate($field['name']);

							if (isset($cf_json[$field['name']]))
							{
								echo $cf_json[$field['name']];
							}
							?>
						</td>
						<?php
					}
				}

				/**
				 * Display here the custom columns fetched by third-party plugins.
				 *
				 * @since 1.7
				 */
				foreach ($columns as $k => $col)
				{
					?>
					<td data-id="<?php echo $this->escape($k); ?>" class="hidden-phone">
						<?php echo isset($col->td[$i]) ? $col->td[$i] : ''; ?>
					</td>
					<?php
				}
				
				if (in_array('total', $listable_fields))
				{
					?>
					<td class="nowrap hidden-phone">
						<?php
						if ($row['closure'] == 0)
						{
							?>
							<div class="td-primary">
								<?php echo $currency->format($row['total_cost']); ?>
							</div>

							<div class="td-secondary">
								<?php
								// Check whether the order is flagged as "paid".
								// Rely also on "paid" column for BC.
								$is_paid = $row['paid'] || JHtml::fetch('vaphtml.status.ispaid', 'appointments', $row['status']);

								if ($row['total_cost'] > $row['tot_paid'] && !$is_paid)
								{
									// display remaining balance
									echo JText::translate('VAPORDERDUE') . ': ' . $currency->format($row['total_cost'] - $row['tot_paid']);
								}
								else if ($row['tot_paid'] > 0)
								{
									// display amount paid
									echo JText::translate('VAPORDERPAID') . ': ' . $currency->format($row['tot_paid']);
								}
								?>
							</div>
							<?php
						}
						?>
					</td>
					<?php
				}

				if (in_array('payment', $listable_fields))
				{
					?>
					<td class="hidden-phone">
						<?php echo $row['payment_name']; ?>
					</td>
					<?php
				}

				if (in_array('coupon', $listable_fields))
				{
					?>
					<td style="text-align: center;" class="hidden-phone">
						<?php
						if ($row['coupon_str'])
						{
							list($coupon_code, $coupon_percentot, $coupon_amount) = explode(';;', $row['coupon_str']);
							?>
							<span class="badge badge-warning hasTooltip" title="<?php echo $coupon_code; ?>">
								<?php
								if ($coupon_percentot == 1)
								{
									echo $coupon_amount . '%';
								}
								else
								{
									echo $currency->format($coupon_amount);
								}
								?>
							</span>
							<?php
						}
						?>
					</td>
					<?php
				}
				
				if (in_array('invoice', $listable_fields))
				{
					?>
					<td style="text-align: center;" class="hidden-phone">
						<?php
						if ($row['invoice'])
						{
							?>
							<a href="<?php echo $row['invoice']->uri; ?>" target="_blank">
								<i class="fas fa-file-pdf" style="font-size: 24px;"></i>
							</a>
							<?php
						}
						?>
					</td>
					<?php
				}
				
				if (in_array('status', $listable_fields))
				{
					?>
					<td class="hidden-phone">
						<?php
						if ($row['closure'] == 0)
						{
							$id_parent = $row['id_parent'] > 0 && $row['id_parent'] != $row['id'] ? $row['id_parent'] : 0;
							?>
							<span class="status-hndl" style="cursor:pointer;" data-id="<?php echo $row['id']; ?>" data-status="<?php echo $row['status']; ?>" data-id-parent="<?php echo $id_parent; ?>">
								<?php echo JHtml::fetch('vaphtml.status.display', $row['status']); ?>
							</span>
							<?php
						}
						else
						{
							?>
							<span class="vapreservationstatusclosure">
								<?php echo JText::translate('VAPSTATUSCLOSURE'); ?>
							</span>
							<?php
						}
						?>
					</td>
					<?php
				}
				?>
			</tr>
			<?php
		}
		?>
	</table>
	<?php
}
?>

	<!-- invoice submit fields -->
	<input type="hidden" name="notifycust" value="0" />
	<input type="hidden" name="group" value="appointments" />

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="reservations" />

	<input type="hidden" name="filter_order" value="<?php echo $this->ordering; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->orderDir; ?>" />

	<?php echo JHtml::fetch('form.token'); ?>
	<?php echo $this->navbut; ?>
</form>

<?php
if (count($rows) == 1)
{
	// credit card modal
	echo JHtml::fetch(
		'bootstrap.renderModal',
		'jmodal-ccdetails',
		array(
			'title'       => JText::translate('VAPSEECCDETAILS'),
			'closeButton' => true,
			'keyboard'    => false, 
			'bodyHeight'  => 60,
			'modalWidth'  => 60,
			'url'		  => 'index.php?option=com_vikappointments&view=ccdetails&tmpl=component&cid[]=' . $rows[0]['id'],
		)
	);
}

// reservation modal
echo JHtml::fetch(
	'bootstrap.renderModal',
	'jmodal-respinfo',
	array(
		'title'       => JText::translate('VAPMANAGERESERVATIONTITLE1'),
		'closeButton' => true,
		'keyboard'    => false, 
		'bodyHeight'  => 80,
		'url'		  => '',
	)
);

// customer modal
echo JHtml::fetch(
	'bootstrap.renderModal',
	'jmodal-custinfo',
	array(
		'title'       => JText::translate('VAPMANAGECUSTOMER21'),
		'closeButton' => true,
		'keyboard'    => false, 
		'bodyHeight'  => 80,
		'url'		  => '',
		'footer'	  => '<button type="button" class="btn pull-left" id="custinfo-filter-btn">
			<i class="fas fa-filter"></i>&nbsp;' . JText::translate('VAP_FILTER_APPOINTMENTS') . '
		</button>
		<a href="index.php?option=com_vikappointments&task=customer.edit&cid[]=0" class="btn btn-success pull-right" id="custinfo-edit-btn">' . JText::translate('VAPEDIT') . '</a>',
	)
);
?>

<!-- INVOICE DIALOG -->
<div id="dialog-invoice" style="display: none;">
	<h3 style="margin-top: 0;"><?php echo JText::translate('VAPINVOICEDIALOG'); ?></h3>

	<p><?php echo JText::translate('VAPGENERATEINVOICESTXT'); ?></p>

	<div>
		<?php
		$elem_yes = $vik->initRadioElement('', '', false, 'onClick="notifyCustValueChanged(1);"');
		$elem_no  = $vik->initRadioElement('', '',  true, 'onClick="notifyCustValueChanged(0);"');
		
		echo $vik->openControl(JText::translate('VAPMANAGEINVOICE9'));
		echo $vik->radioYesNo('notifycust_radio', $elem_yes, $elem_no, false);
		echo $vik->closeControl();
		?>
	</div>
</div>

<?php
JText::script('VAPMANAGEINVOICE6');
JText::script('VAPCANCEL');
?>

<script>

	var invoiceDialog = null;

	jQuery(function($) {
		VikRenderer.chosen('.btn-toolbar');
		VikRenderer.chosen('#jmodal-invoice');

		// trigger status code changed event
		$(window).on('statuscode.changed', (event, data) => {
			// update children item too
			$('.status-hndl[data-id-parent="' + data.id_order + '"]').html(data.html);
		});

		$('#custinfo-filter-btn').on('click', function() {
			// filter the appointments by user
			$('#vapkeysearch').val('id_user:' + $(this).attr('data-id')).prop('disabled', false);
			// reset filter by reservation (if any)
			$('#adminForm').append('<input type="hidden" name="res_id" value="0" />');

			document.adminForm.submit();
		});

		// create invoice dialog
		invoiceDialog = new VikConfirmDialog('#dialog-invoice', 'vik-invoice-confirm');

		// add confirm button
		invoiceDialog.addButton(Joomla.JText._('VAPMANAGEINVOICE6'), (task, event) => {
			// submit the form
			Joomla.submitform(task, document.adminForm);
		});

		// add cancel button
		invoiceDialog.addButton(Joomla.JText._('VAPCANCEL'));
	});
		
	function clearFilters() {
		jQuery('#vapkeysearch').val('');
		jQuery('#vapdatestart').val('');
		jQuery('#vapdateend').val('');
		jQuery('#vap-status-sel').updateChosen('');
		jQuery('#vap-payment-sel').updateChosen('');
		jQuery('#vap-location-sel').updateChosen('');

		jQuery('#adminForm').append('<input type="hidden" name="res_id" value="0" />');
		
		document.adminForm.submit();
	}

	function notifyCustValueChanged(is) {
		jQuery('#adminForm input[name="notifycust"]').val(is);
	}

	// modal

	function openOrderModal(id) {
		let url = 'index.php?option=com_vikappointments&tmpl=component&view=orderinfo&cid[]=' + id;

		vapOpenJModal('respinfo', url, true);
	}

	function openCustomerModal(id) {
		// update filter button with customer ID
		jQuery('#custinfo-filter-btn').attr('data-id', id);

		// update href to access the management page of the customer
		let href = jQuery('#custinfo-edit-btn').attr('href');
		jQuery('#custinfo-edit-btn').attr('href', href.replace(/cid\[\]=[\d]*$/, 'cid[]=' + id));

		let url = 'index.php?option=com_vikappointments&tmpl=component&view=customerinfo&cid[]=' + id;

		vapOpenJModal('custinfo', url, true);
	}

	function vapOpenJModal(id, url, jqmodal) {
		<?php echo $vik->bootOpenModalJS(); ?>
	}

	Joomla.submitbutton = function(task) {
		if (task == 'invoice.generate') {
			// show invoice dialog
			invoiceDialog.show(task);
		} else if (task == 'generateInvoices') {
			vapOpenJModal('invoice', null, true);
		} else {
			if (task == 'findreservation' || task == 'printorders' || task == 'makerecurrence') {
				// populate view instead of task
				document.adminForm.view.value = task;
				task = '';
			}

			Joomla.submitform(task, document.adminForm);
		}
	}

</script>
