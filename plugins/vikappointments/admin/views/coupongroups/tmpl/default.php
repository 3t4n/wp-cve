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

$rows = $this->rows;

$filters = $this->filters;

$vik = VAPApplication::getInstance();

$canEdit      = JFactory::getUser()->authorise('core.edit', 'com_vikappointments');
$canEditState = JFactory::getUser()->authorise('core.edit.state', 'com_vikappointments');
$canOrder     = $this->ordering == 'g.ordering';

if ($canOrder && $canEditState)
{
	$saveOrderingUrl = 'index.php?option=com_vikappointments&task=coupongroup.saveOrderAjax&tmpl=component';
	JHtml::fetch('vaphtml.scripts.sortablelist', 'coupongroupsList', 'adminForm', $this->orderDir, $saveOrderingUrl);
}

$is_searching = $this->hasFilters();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewCoupongroupsList". The event method receives the
 * view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayListView($is_searching);

?>

<form action="index.php?option=com_vikappointments" method="post" name="adminForm" id="adminForm">
	
	<div class="btn-toolbar vapgroup-keyfilter-block" id="filter-bar">

		<div class="btn-group pull-left input-append">
			<input type="text" name="keysearch" id="vapkeysearch" class="vapkeysearch" size="32" 
				value="<?php echo $this->escape($filters['keysearch']); ?>" placeholder="<?php echo $this->escape(JText::translate('JSEARCH_FILTER_SUBMIT')); ?>" />

			<button type="submit" class="btn">
				<i class="fas fa-search"></i>
			</button>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewCoupongroupsList","type":"search","key":"search"} -->

		<?php
		// plugins can use the "search" key to introduce custom
		// filters within the search bar
		if (isset($forms['search']))
		{
			echo $forms['search'];
		}

		// in case a plugin needs to use the filter bar, display the button
		if (isset($forms['filters']))
		{
			?>
			<div class="btn-group pull-left">
				<button type="button" class="btn <?php echo ($is_searching ? 'btn-primary' : ''); ?>" onclick="vapToggleSearchToolsButton(this);">
					<?php echo JText::translate('JSEARCH_TOOLS'); ?>&nbsp;<i class="fas fa-caret-<?php echo ($is_searching ? 'up' : 'down'); ?>" id="vap-tools-caret"></i>
				</button>
			</div>
			<?php
		}
		?>

		<div class="btn-group pull-left">
			<button type="button" class="btn" onclick="clearFilters();">
				<?php echo JText::translate('JSEARCH_FILTER_CLEAR'); ?>
			</button>
		</div>

	</div>

	<div class="btn-toolbar" id="vap-search-tools" style="height: 32px;<?php echo ($is_searching ? '' : 'display: none;'); ?>">

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewCoupongroupsList","type":"search","key":"filters"} -->

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
	<!-- {"rule":"customizer","event":"onDisplayCoupongroupsTableTH","type":"th"} -->

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayCoupongroupsTableTD","type":"td"} -->

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>" id="coupongroupsList">
		
		<?php echo $vik->openTableHead(); ?>
			<tr>

				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<!-- ID -->

				<th class="<?php echo $vik->getAdminThClass('left hidden-phone nowrap'); ?>" width="1%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEGROUP1', 'g.id', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- NAME -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="20%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEGROUP2', 'g.name', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- DESCRIPTION -->
				
				<th class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>" width="45%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGEGROUP3'); ?>
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

				<!-- COUNT -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="10%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', JText::translate('VAPMENUCOUPONS'), 'count', $this->orderDir, $this->ordering); ?>
				</th>
				
				<!-- ORDERING -->

				<th class="<?php echo $vik->getAdminThClass('hidden-phone nowrap'); ?>" width="1%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', '<i class="fas fa-sort"></i>', 'g.ordering', $this->orderDir, $this->ordering); ?>
				</th>

			</tr>
		<?php echo $vik->closeTableHead(); ?>
		
		<?php
		for ($i = 0, $n = count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];
			
			$desc = strip_tags((string) $row['description']);
			
			if (strlen($desc) > 256)
			{
				$desc = mb_substr($desc, 0, 250, 'UTF-8') . "...";
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

				<!-- NAME -->
				
				<td style="text-align: left;">
					<div class="td-primary">
						<?php
						if ($canEdit)
						{
							?>
							<a href="index.php?option=com_vikappointments&amp;task=coupongroup.edit&amp;cid[]=<?php echo $row['id']; ?>">
								<?php echo $row['name']; ?>
							</a>
							<?php
						}
						else
						{
							echo $row['name'];
						}
						?>
					</div>

					<div class="btn-group">
						<?php
						if ($row['count'])
						{
							?>
							<a href="index.php?option=com_vikappointments&amp;view=coupons&amp;id_group=<?php echo $row['id']; ?>" class="btn btn-mini">
								<i class="fas fa-filter"></i>
								<span class="hidden-phone"><?php echo JText::plural('VAP_DISPLAY_N_COUPONS', $row['count']); ?></span>
							</a>
							<?php
						}
						?>

						<a href="index.php?option=com_vikappointments&amp;task=coupon.add&amp;id_group=<?php echo $row['id']; ?>" class="btn btn-mini">
							<i class="fas fa-plus-circle"></i>
							<span class="hidden-phone"><?php echo JText::translate('VAP_ADD_COUPON'); ?></span>
						</a>
					</div>
				</td>

				<!-- DESCRIPTION -->
				
				<td class="hidden-phone">
					<?php echo $desc; ?>
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

				<!-- COUNT -->
				
				<td style="text-align: center;">
					<?php echo $row['count']; ?>
				</td>

				<!-- ORDERING -->
				
				<td class="order nowrap center hidden-phone">
					<?php echo JHtml::fetch('vaphtml.admin.sorthandle', $row['ordering'], $canEditState, $canOrder); ?>
				</td>

			</tr>
			<?php
		}
		?>
	</table>
	<?php
}
?>
	
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="coupongroups" />

	<input type="hidden" name="filter_order" value="<?php echo $this->ordering; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->orderDir; ?>" />

	<?php echo JHtml::fetch('form.token'); ?>
	<?php echo $this->navbut; ?>
</form>

<script>

	function clearFilters() {
		jQuery('#vapkeysearch').val('');
		
		document.adminForm.submit();
	}
	
</script>
