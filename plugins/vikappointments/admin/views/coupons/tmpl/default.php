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

$rows = $this->rows;

$filters = $this->filters;

$vik = VAPApplication::getInstance();

$config   = VAPFactory::getConfig();
$currency = VAPFactory::getCurrency();

$user = JFactory::getUser();

$canEdit = $user->authorise('core.edit', 'com_vikappointments');

$dt_format = $config->get('dateformat') . ' ' . $config->get('timeformat');

$now = JDate::getInstance()->toSql();

$is_searching = $this->hasFilters();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewCouponsList". The event method receives the
 * view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayListView($is_searching);

?>

<form action="index.php?option=com_vikappointments" method="post" name="adminForm" id="adminForm">

	<div class="btn-toolbar" style="height: 32px;">
		<div class="btn-group pull-left input-append">
			<input type="text" name="keys" id="vapkeysearch" size="32" 
				value="<?php echo $this->escape($filters['keys']); ?>" placeholder="<?php echo $this->escape(JText::translate('JSEARCH_FILTER_SUBMIT')); ?>" />

			<button type="submit" class="btn">
				<i class="fas fa-search"></i>
			</button>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewCouponsList","type":"search","key":"search"} -->

		<?php
		// plugins can use the "search" key to introduce custom
		// filters within the search bar
		if (isset($forms['search']))
		{
			echo $forms['search'];
		}
		?>

		<div class="btn-group pull-left">
			<button type="button" class="btn <?php echo ($is_searching ? 'btn-primary' : ''); ?>" onclick="vapToggleSearchToolsButton(this);">
				<?php echo JText::translate('JSEARCH_TOOLS'); ?>&nbsp;<i class="fas fa-caret-<?php echo ($is_searching ? 'up' : 'down'); ?>" id="vap-tools-caret"></i>
			</button>
		</div>
		
		<div class="btn-group pull-left">
			<button type="button" class="btn" onclick="clearFilters();">
				<?php echo JText::translate('JSEARCH_FILTER_CLEAR'); ?>
			</button>
		</div>

		<div class="btn-group pull-right">
			<a href="index.php?option=com_vikappointments&amp;view=coupongroups" class="btn">
				<?php echo JText::translate('VAPMANAGEGROUPS'); ?>
			</a>
		</div>
	</div>

	<div class="btn-toolbar" id="vap-search-tools" style="height: 32px;<?php echo ($is_searching ? '' : 'display: none;'); ?>">

		<?php
		$options = array(
			JHtml::fetch('select.option', 0, JText::translate('VAPFILTERSELECTTYPE')),
			JHtml::fetch('select.option', 1, JText::translate('VAPCOUPONTYPEOPTION1')),
			JHtml::fetch('select.option', 2, JText::translate('VAPCOUPONTYPEOPTION2')),
		);
		?>
		<div class="btn-group pull-left">
			<select name="type" id="vap-type-sel" class="<?php echo ($filters['type'] != 0 ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['type']); ?>
			</select>
		</div>

		<?php
		$options = array(
			JHtml::fetch('select.option', 0, JText::translate('VAPFILTERSELECTVAL')),
			JHtml::fetch('select.option', 1, JText::translate('VAPCOUPONVALUETYPE1')),
			JHtml::fetch('select.option', 2, JText::translate('VAPCOUPONVALUETYPE2')),
		);
		?>
		<div class="btn-group pull-left">
			<select name="value" id="vap-value-sel" class="<?php echo ($filters['value'] != 0 ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['value']); ?>
			</select>
		</div>

		<?php
		$options = array(
			JHtml::fetch('select.option', 0, JText::translate('VAPFILTERSELECTSTATUS')),
			JHtml::fetch('select.option', 1, JText::translate('VAPCOUPONVALID0')),
			JHtml::fetch('select.option', 2, JText::translate('VAPCOUPONVALID1')),
			JHtml::fetch('select.option', 3, JText::translate('VAPCOUPONVALID2')),
		);
		?>
		<div class="btn-group pull-left">
			<select name="status" id="vap-status-sel" class="<?php echo ($filters['status'] != 0 ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['status']); ?>
			</select>
		</div>

		<?php
		$options = array(
			JHtml::fetch('select.option', -1, JText::translate('VAPFILTERSELECTGROUP')),
			JHtml::fetch('select.option', 0, JText::translate('VAPSERVICENOGROUP')),
		);

		$options = array_merge($options, JHtml::fetch('vaphtml.admin.coupongroups'));
		?>
		<div class="btn-group pull-left">
			<select name="id_group" id="vap-group-sel" class="<?php echo ($filters['id_group'] != -1 ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['id_group']); ?>
			</select>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewCouponsList","type":"search","key":"filters"} -->

		<?php
		// plugins can use the "filters" key to introduce custom
		// filters within the search bar
		if (isset($forms['filters']))
		{
			echo $forms['filters'];
		}
		?>

	</div>

<?php
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
	<!-- {"rule":"customizer","event":"onDisplayCouponsTableTH","type":"th"} -->

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayCouponsTableTD","type":"td"} -->

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>

				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<!-- ID -->
				
				<th class="<?php echo $vik->getAdminThClass('left hidden-phone nowrap'); ?>" width="1%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGECOUPON1', 'c.id', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- CODE -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="15%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGECOUPON2', 'c.code', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- TYPE -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="10%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGECOUPON3'); ?>
				</th>

				<!-- AMOUNT -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="8%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGECOUPON5', 'c.value', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- MIN COST -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="8%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGECOUPON6'); ?>
				</th>

				<!-- CUSTOM -->

				<?php
				foreach ($columns as $k => $col)
				{
					?>
					<th data-id="<?php echo $this->escape($k); ?>" class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>">
						<?php echo $col->th; ?>
					</th>
					<?php
				}
				?>

				<!-- PUBLISHING -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="15%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'JGLOBAL_FIELDSET_PUBLISHING', 'c.dstart', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- LAST MINUTE -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="5%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGECOUPON10'); ?>
				</th>

				<!-- STATUS -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="10%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGECOUPON9'); ?>
				</th>
			
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		
		<?php
		for ($i = 0, $n = count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];
			
			// validate coupon according to the applicable section
			if (in_array($row['applicable'], array('', 'appointments')))
			{
				// validate as appointment
				$valid = (int) VikAppointments::validateCoupon($row);
			}
			else if ($row['applicable'] == 'packages')
			{
				// validate as package
				$valid = (int) VikAppointments::validatePackagesCoupon($row);
			}
			else
			{
				// validate as subscription
				$valid = (int) VikAppointments::validateSubscriptionsCoupon($row);
			}

			if ($valid)
			{
				// coupon valid
				$class = 'vapreservationstatusconfirmed';
			}
			else
			{
				// coupon invalid, check whether it is expired
				if (!VAPDateHelper::isNull($row['dstart']) && $row['dstart'] > $now)
				{
					// coupon not yet active
					$class = 'vapreservationstatuspending';
					$valid = 2;
				}
				else
				{
					// coupon is expired
					$class = 'vapreservationstatusremoved';
				}
			}

			$tooltip = JText::sprintf('VAPCOUPONINFOTIP', 
				$row['used_quantity'], 
				($row['type'] == 2 ? max(array(0, $row['max_quantity'] - $row['used_quantity'])) : "&infin;"), 
				(strlen($row['notes']) ? '<br /><br />' : '') . $row['notes']
			);

			if ($row['lastminute'])
			{
				$last_min_title = VikAppointments::formatMinutesToTime($row['lastminute'] * 60, $format = true);
			}
			else
			{
				$last_min_title = '';
			}
			
			?>
			<tr class="row<?php echo ($i % 2); ?>">

				<td>
					<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>">
				</td>
				
				<!-- ID -->

				<td class="hidden-phone">
					<?php echo $row['id']; ?>
				</td>

				<!-- CODE -->
				
				<td>
					<div class="td-primary td-pull-left">
						<?php
						if ($canEdit)
						{
							?>
							<a href="index.php?option=com_vikappointments&amp;task=coupon.edit&amp;cid[]=<?php echo $row['id']; ?>">
								<?php echo $row['code']; ?>
							</a>
							<?php
						}
						else
						{
							echo $row['code'];
						}
						?>
					</div>

					<div class="td-pull-right">
						<a href="javascript: void(0);" class="hasTooltip" title="<?php echo $this->escape($tooltip); ?>">
							<i class="fas fa-sticky-note big"></i>
						</a>
					</div>
				</td>
				
				<!-- TYPE -->

				<td style="text-align: center;" class="hidden-phone">
					<?php echo JText::translate('VAPCOUPONTYPEOPTION' . $row['type'] ); ?>
				</td>
				
				<!-- AMOUNT -->

				<td style="text-align: center;">
					<?php
					if ($row['percentot'] == 1)
					{
						echo $row['value'] . JText::translate('VAPCOUPONPERCENTOTOPTION1');
					}
					else
					{
						echo $currency->format($row['value']);
					}
					?>	
				</td>

				<!-- MIN COST -->
				
				<td style="text-align: center;" class="hidden-phone">
					<?php echo $currency->format($row['mincost']); ?>
				</td>

				<!-- CUSTOM -->

				<?php
				foreach ($columns as $k => $col)
				{
					?>
					<td data-id="<?php echo $this->escape($k); ?>" class="hidden-phone">
						<?php echo isset($col->td[$i]) ? $col->td[$i] : ''; ?>
					</td>
					<?php
				}
				?>

				<!-- PUBLISHING -->
				
				<td style="text-align: center;" class="hidden-phone">
					<?php
					if (!VAPDateHelper::isNull($row['dstart']) || !VAPDateHelper::isNull($row['dend']))
					{
						if (!VAPDateHelper::isNull($row['dstart']))
						{
							?>
							<span class="badge badge-success hasTooltip" title="<?php echo $this->escape(JText::translate('VAPMANAGEPACKAGE6')); ?>">
								<?php echo JHtml::fetch('date', $row['dstart'], 'DATE_FORMAT_LC3'); ?>
							</span>
							<?php
						}
						
						if (!VAPDateHelper::isNull($row['dend']))
						{
							?>
							<span class="badge badge-important hasTooltip" title="<?php echo $this->escape(JText::translate('VAPMANAGEPACKAGE7')); ?>">
								<?php echo JHtml::fetch('date', $row['dend'], 'DATE_FORMAT_LC3'); ?>
							</span>
							<?php
						}
					}
					else
					{
						echo '/';
					}
					?>
				</td>

				<!-- LAST MINUTE -->
				
				<td style="text-align: center;" class="hidden-phone">
				    <?php echo JHtml::fetch('vaphtml.admin.stateaction', $row['lastminute'], $row['id'], '', false, array(), $last_min_title); ?>
				</td>

				<!-- STATUS -->
				
				<td style="text-align: center;" class="<?php echo $class; ?>">
					<?php echo JText::translate('VAPCOUPONVALID' . $valid); ?>
				</td>

			</tr>
			<?php
		}
		?>
	</table>
	<?php
}
?>

	<!-- hidden input for import tool -->
	<input type="hidden" name="import_type" value="coupons" />

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="coupons" />

	<input type="hidden" name="filter_order" value="<?php echo $this->ordering; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->orderDir; ?>" />

	<?php echo JHtml::fetch('form.token'); ?>
	<?php echo $this->navbut; ?>
</form>

<script>

	jQuery(function($) {
		VikRenderer.chosen('.btn-toolbar');
	});
	
	function clearFilters() {
		jQuery('#vapkeysearch').val('');
		jQuery('#vap-type-sel').updateChosen(0);
		jQuery('#vap-value-sel').updateChosen(0);
		jQuery('#vap-status-sel').updateChosen(0);
		jQuery('#vap-group-sel').updateChosen(-1);
		
		document.adminForm.submit();
	}

	Joomla.submitbutton = function(task) {
		if (task == 'import' || task == 'export') {
			// populate view instead of task
			document.adminForm.view.value = task;
			task = '';
		}
		
		Joomla.submitform(task, document.adminForm);
	}

</script>
