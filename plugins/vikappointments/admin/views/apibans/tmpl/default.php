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

JHtml::fetch('formbehavior.chosen');
JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('vaphtml.assets.fontawesome');

$rows = $this->rows;

$filters = $this->filters;

$ordering = $this->ordering;

$vik = VAPApplication::getInstance();

$config = VAPFactory::getConfig();

$max_fail = $config->getUint('apimaxfail');

$is_searching = $this->hasFilters();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewApibansList". The event method receives the
 * view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayListView($is_searching);

?>

<form action="index.php?option=com_vikappointments" method="post" name="adminForm" id="adminForm">

	<div class="btn-toolbar" style="height: 32px;">
		<div class="btn-group pull-left input-append">
			<input type="text" name="keysearch" id="vapkeysearch" size="32" 
				value="<?php echo $this->escape($filters['keysearch']); ?>" placeholder="<?php echo $this->escape(JText::translate('JSEARCH_FILTER_SUBMIT')); ?>" />

			<button type="submit" class="btn">
				<i class="fas fa-search"></i>
			</button>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewApibansList","type":"search","key":"search"} -->

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

		<?php
		$options = array(
			JHtml::fetch('select.option', 1, JText::translate('VAPAPIBANOPT1')),
			JHtml::fetch('select.option', 2, JText::translate('VAPAPIBANOPT2')),
		);
		?>
		<div class="btn-group pull-right">
			<select name="type" id="vap-type-sel" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['type']); ?>
			</select>
		</div>
	</div>

	<div class="btn-toolbar" id="vap-search-tools" style="height: 32px;<?php echo ($is_searching ? '' : 'display: none;'); ?>">

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewApibansList","type":"search","key":"filters"} -->

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
	<!-- {"rule":"customizer","event":"onDisplayApibansTableTH","type":"th"} -->

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayApibansTableTD","type":"td"} -->

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		
		<?php echo $vik->openTableHead(); ?>
			<tr>
				
				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<!-- ID -->
				
				<th class="<?php echo $vik->getAdminThClass('left hidden-phone nowrap'); ?>" width="1%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEGROUP1', 'b.id', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- IP ADDRESS -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="15%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGEAPIUSER17'); ?>
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

				<!-- LAST UPDATE -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="20%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEAPIUSER18', 'b.last_update', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- FAILS COUNT -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="10%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEAPIUSER19', 'b.fail_count', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- STATUS -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="15%" style="text-align: center;">&nbsp;</th>

			</tr>
		<?php echo $vik->closeTableHead(); ?>

		<?php
		$kk = 0;
		for ($i = 0; $i < count($rows); $i++)
		{
			$row = $rows[$i];
			?>
			<tr class="row<?php echo $kk; ?>">

				<td>
					<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>">
				</td>

				<!-- ID -->
				
				<td class="hidden-phone">
					<?php echo $row['id']; ?>
				</td>

				<!-- IP ADDRESS -->
				
				<td>
					<div class="td-primary">
						<?php echo $row['ip']; ?>
					</div>
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

				<!-- LAST UPDATE -->
				
				<td>
					<span class="hasTooltip" title="<?php echo $this->escape(JHtml::fetch('date', $row['last_update'], JText::translate('DATE_FORMAT_LC2'))); ?>">
						<?php echo JHtml::fetch('date.relative', $row['last_update'], null, null, JText::translate('DATE_FORMAT_LC2')); ?>
					</span>
				</td>

				<!-- FAILS COUNT -->

				<td style="text-align: center;<?php echo ($row['fail_count'] >= $max_fail ? 'color:#900;' : ''); ?>">
					<?php
					if ($row['fail_count'] == 0)
					{
						$badge = 'success';
					}
					else if ($row['fail_count'] >= $max_fail)
					{
						$badge = 'important';
					}
					else
					{
						$badge = 'warning';
					}
					?>
					<span class="badge badge-<?php echo $badge; ?>">
						<?php echo $row['fail_count'] . ' / ' . $max_fail; ?>
					</span>
				</td>

				<!-- STATUS -->

				<td style="text-align: center;" class="hidden-phone">
					<?php
					if ($row['fail_count'] < $max_fail)
					{
						?>
						<span style="color: #090;">
							<i class="fas fa-check-circle"></i>
							<b style="text-transform: uppercase;margin-left: 2px;">
								<?php echo JText::translate('VAPACTIVE'); ?>
							</b>
						</span>
						<?php
					}
					else
					{
						?>
						<span style="color: #900;">
							<i class="fas fa-ban"></i>
							<b style="text-transform: uppercase;margin-left: 2px;">
								<?php echo JText::translate('VAPMANAGEAPIUSER20'); ?>
							</b>
						</span>
						<?php
					}
					?>
				</td>

			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php
}
?>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="apibans" />

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
		jQuery('#vap-type-sel').updateChosen(1);
		
		document.adminForm.submit();
	}
	
</script>
