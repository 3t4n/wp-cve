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

$rows = $this->rows;

$filters = $this->filters;

$vik = VAPApplication::getInstance();

$user = JFactory::getUser();

$canEdit      = $user->authorise('core.edit', 'com_vikappointments');
$canEditState = $user->authorise('core.edit.state', 'com_vikappointments');
$canOrder     = $this->ordering == 't.ordering';

if ($canOrder && $canEditState)
{
	$saveOrderingUrl = 'index.php?option=com_vikappointments&task=tag.saveOrderAjax&tmpl=component';
	JHtml::fetch('vaphtml.scripts.sortablelist', 'tagsList', 'adminForm', $this->orderDir, $saveOrderingUrl);
}

$is_searching = $this->hasFilters();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewTagsList". The event method receives the
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
		<!-- {"rule":"customizer","event":"onDisplayViewTagsList","type":"search","key":"search"} -->

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
		<!-- {"rule":"customizer","event":"onDisplayViewTagsList","type":"search","key":"filters"} -->

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
	<!-- {"rule":"customizer","event":"onDisplayTagsTableTH","type":"th"} -->

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayTagsTableTD","type":"td"} -->

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>" id="tagsList">
		<?php echo $vik->openTableHead(); ?>
			<tr>

				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<!-- NAME -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="20%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEGROUP2', 't.name', $this->orderDir, $this->ordering); ?>
				</th>
				
				<!-- DESCRIPTION -->

				<th class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>" width="40%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGEGROUP3'); ?>
				</th>

				<!-- AUTHOR -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone left'); ?>" width="10%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGERESERVATION41'); ?>
				</th>

				<!-- CREATION DATE -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone left'); ?>" width="10%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', JText::translate('VAPMANAGEMEDIA14'), 't.createdon', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- COUNT -->

				<th class="<?php echo $vik->getAdminThClass('nowrap'); ?>" width="10%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPFIELDSETASSOC', 'count', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- ICON -->

				<th class="<?php echo $vik->getAdminThClass('nowrap'); ?>" width="5%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGEPAYMENT15'); ?>
				</th>
				
				<!-- ORDERING -->

				<th class="<?php echo $vik->getAdminThClass('hidden-phone nowrap'); ?>" width="1%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', '<i class="fas fa-sort"></i>', 't.ordering', $this->orderDir, $this->ordering); ?>
				</th>

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
				
				<!-- NAME -->

				<td>
					<?php
					if ($canEdit)
					{
						?>
						<a href="index.php?option=com_vikappointments&amp;task=tag.edit&amp;cid[]=<?php echo $row['id']; ?>&amp;group=<?php echo $this->escape($this->filters['group']); ?>">
							<?php echo JHtml::fetch('vikappointments.tag', $row); ?>
						</a>
						<?php
					}
					else
					{
						echo JHtml::fetch('vikappointments.tag', $row);
					}
					?>
				</td>

				<!-- DESCRIPTION -->

				<td class="hidden-phone">
					<?php echo $row['description']; ?>
				</td>

				<!-- AUTHOR -->

				<td class="hidden-phone">
					<?php echo $row['author_name']; ?>
				</td>

				<!-- CREATION DATE -->

				<td class="hidden-phone">
					<?php echo JHtml::fetch('date', $row['createdon'], JText::translate('DATE_FORMAT_LC5')); ?>
				</td>

				<!-- COUNT -->
				
				<td style="text-align: center;">
					<?php echo $row['count']; ?>
				</td>

				<!-- ICON -->
				
				<td style="text-align: center;">
					<?php echo JHtml::fetch('vikappointments.tag', $row, 'icon', array('class' => 'big')); ?>
				</td>

				<!-- ORDERING -->

				<td class="order nowrap center hidden-phone">
					<?php echo JHtml::fetch('vaphtml.admin.sorthandle', $row['ordering'], $canEditState, $canOrder); ?>
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
	<input type="hidden" name="view" value="tags" />
	<input type="hidden" name="group" value="<?php echo $this->escape($this->filters['group']); ?>" />

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
