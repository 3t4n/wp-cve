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

$is_searching = $this->hasFilters();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewStatesList". The event method receives the
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
		<!-- {"rule":"customizer","event":"onDisplayViewStatesList","type":"search","key":"search"} -->

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
	</div>

	<div class="btn-toolbar" id="vap-search-tools" style="height: 32px;<?php echo ($is_searching ? '' : 'display: none;'); ?>">

		<?php
		$options = array(
			JHtml::fetch('select.option', '', JText::translate('VAPFILTERSELECTSTATUS')),
			JHtml::fetch('select.option', 1, JText::translate('JPUBLISHED')),
			JHtml::fetch('select.option', 0, JText::translate('JUNPUBLISHED')),
		);
		?>
		<div class="btn-group pull-left">
			<select name="status" id="vap-status-sel" class="<?php echo (strlen($filters['status']) ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['status']); ?>
			</select>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewStatesList","type":"search","key":"filters"} -->

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
	<!-- {"rule":"customizer","event":"onDisplayStatesTableTH","type":"th"} -->

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayStatesTableTD","type":"td"} -->

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				
				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<!-- NAME -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="25%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGESTATE1', 's.state_name', $this->orderDir, $this->ordering); ?>
				</th>
				
				<!-- 2 CODE -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="10%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGESTATE2', 's.state_2_code', $this->orderDir, $this->ordering); ?>
				</th>
				
				<!-- 3 CODE -->

				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="10%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGESTATE3'); ?>
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
				
				<!-- STATES -->

				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="10%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMENUCITIES', 'cities_count', $this->orderDir, $this->ordering); ?>
				</th>
				
				<!-- STATUS -->

				<th class="<?php echo $vik->getAdminThClass(); ?>" width="10%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGESTATE4'); ?>
				</th>
			
			</tr>
		<?php echo $vik->closeTableHead(); ?>

		<?php
		for ($i = 0, $n = count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];
			?>
			<tr class="row<?php echo ($i % 2); ?>">

				<td>
					<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>">
				</td>
				
				<!-- NAME -->
				
				<td style="text-align: left;">
					<div class="td-primary">
						<?php
						if ($canEdit)
						{
							?>
							<a href="index.php?option=com_vikappointments&amp;task=state.edit&amp;cid[]=<?php echo $row['id']; ?>">
								<?php echo $row['state_name']; ?>
							</a>
							<?php
						}
						else
						{
							echo $row['state_name'];
						}
						?>
					</div>

					<div class="btn-group">
						<a href="index.php?option=com_vikappointments&amp;view=cities&amp;id_state=<?php echo $row['id']; ?>" class="btn btn-mini">
							<i class="fas fa-filter"></i>
							<span class="hidden-phone"><?php echo JText::translate('VAP_DISPLAY_CITIES'); ?></span>
						</a>

						<a href="index.php?option=com_vikappointments&amp;task=city.add&amp;id_state=<?php echo $row['id']; ?>" class="btn btn-mini">
							<i class="fas fa-plus-circle"></i>
							<span class="hidden-phone"><?php echo JText::translate('VAP_ADD_CITY'); ?></span>
						</a>
					</div>
				</td>

				<!-- 2 CODE -->
				
				<td style="text-align: center;">
					<?php echo $row['state_2_code']; ?>
				</td>

				<!-- 3 CODE -->
				
				<td style="text-align: center;" class="hidden-phone">
					<?php echo $row['state_3_code']; ?>
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

				<!-- CITIES -->
				
				<td style="text-align: center;" class="hidden-phone">
					<?php echo ($row['cities_count'] > 0 ? $row['cities_count'] : ''); ?>
				</td>

				<!-- STATUS -->
				
				<td style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.stateaction', $row['published'], $row['id'], 'state.publish', $canEditState, array('id_country' => $filters['id_country'])); ?>
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
	<input type="hidden" name="import_type" value="states" />
	<input type="hidden" name="import_args[id_country]" value="<?php echo $filters['id_country']; ?>" />

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="states" />
	<input type="hidden" name="id_country" value="<?php echo $filters['id_country']; ?>" />

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
		jQuery('#vap-status-sel').updateChosen('');
		
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
