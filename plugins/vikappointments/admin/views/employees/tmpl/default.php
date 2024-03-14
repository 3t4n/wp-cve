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

JHtml::fetch('behavior.modal');
JHtml::fetch('formbehavior.chosen');

$rows = $this->rows;

$filters = $this->filters;

$vik = VAPApplication::getInstance();

$multi_lang = VikAppointments::isMultilanguage();

$config = VAPFactory::getConfig();

$user = JFactory::getUser();

$canEdit      = $user->authorise('core.edit', 'com_vikappointments');
$canEditState = $user->authorise('core.edit.state', 'com_vikappointments');

$lifetime_label = JText::translate('VAPSUBSCRTYPE5');

$dt_format = $config->get('dateformat') . ' ' . $config->get('timeformat');

$is_searching = $this->hasFilters();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewEmployeesList". The event method receives the
 * view instance as argument.
 *
 * @since 1.6.6
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
		<!-- {"rule":"customizer","event":"onDisplayViewEmployeesList","type":"search","key":"search"} -->

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
			JHtml::fetch('select.option', '', 'JOPTION_SELECT_PUBLISHED'),
			JHtml::fetch('select.option', 1, 'JPUBLISHED'),
			JHtml::fetch('select.option', 0, 'JUNPUBLISHED'),
		);
		?>
		<div class="btn-group pull-left">
			<select name="status" id="vap-status-sel" class="<?php echo (strlen($filters['status']) ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['status'], true); ?>
			</select>
		</div>

		<?php
		$options = array(
			JHtml::fetch('select.option', -1, JText::translate('VAPFILTERSELECTGROUP')),
			JHtml::fetch('select.option', 0, JText::translate('VAPSERVICENOGROUP')),
		);

		$options = array_merge($options, JHtml::fetch('vaphtml.admin.groups', 2));
		?>
		<div class="btn-group pull-left">
			<select name="id_group" id="vap-group-sel" class="<?php echo ($filters['id_group'] != -1 ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['id_group']); ?>
			</select>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewEmployeesList","type":"search","key":"filters"} -->

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
	<!-- {"rule":"customizer","event":"onDisplayEmployeesTableTH","type":"th"} -->

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayEmployeesTableTD","type":"td"} -->

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">

		<?php echo $vik->openTableHead(); ?>
			<tr>
				
				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<!-- ID -->
				
				<th class="<?php echo $vik->getAdminThClass('left hidden-phone nowrap'); ?>" width="1%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEEMPLOYEE1', 'e.id', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- NAME -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="15%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEEMPLOYEE4', 'e.nickname', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- CONTACTS -->
				
				<th class="<?php echo $vik->getAdminThClass('left hidden-phone'); ?>" width="15%" style="text-align: left;">
					<?php echo JText::translate('VAPCONTACT'); ?>
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

				<!-- GROUP -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="15%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEEMPLOYEE26', 'g.name', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- SERVICES -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="6%" style="text-align: center;">
					<?php echo JText::translate('VAPMENUSERVICES'); ?>
				</th>

				<!-- PAYMENTS -->
				
				<?php
				if ($user->authorise('core.access.payments', 'com_vikappointments'))
				{
					?>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="6%" style="text-align: center;">
						<?php echo JText::translate('VAPMANAGEEMPLOYEE21'); ?>
					</th>
					<?php
				}
				?>

				<!-- LOCATIONS -->
				
				<?php
				if ($user->authorise('core.access.locations', 'com_vikappointments'))
				{
					?>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="6%" style="text-align: center;">
						<?php echo JText::translate('VAPMANAGEEMPLOYEE29'); ?>
					</th>
					<?php
				}
				?>

				<!-- LISTABLE -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="6%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGEEMPLOYEE18', 'e.listable', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- LANGUAGES -->

				<?php
				if ($multi_lang && $canEdit)
				{
					?>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="6%" style="text-align: center;">
						<?php echo JText::translate('VAPLANGUAGES');?>
					</th>
					<?php
				}
				?>

				<!-- IMAGE -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="6%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGEEMPLOYEE7'); ?>
				</th>

			</tr>
		<?php echo $vik->closeTableHead(); ?>

		<?php
		for ($i = 0, $n = count($rows); $i < $n; $i++)
		{
			$row = $rows[$i];
			
			$listable_title = $lifetime_label;

			if ($row['active_to'] > 0)
			{
				$listable_title = date($dt_format, $row['active_to']);
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
				
				<td>
					<div class="td-primary">
						<?php
						if ($canEdit)
						{
							?>
							<a href="index.php?option=com_vikappointments&amp;task=employee.edit&amp;cid[]=<?php echo $row['id']; ?>">
								<?php echo $row['nickname']; ?>
							</a>
							<?php
						}
						else
						{
							echo $row['nickname'];
						}
						?>
					</div>
				</td>

				<!-- CONTACT -->	
				
				<td class="hidden-phone">
					<div class="td-primary">
						<?php echo $row['email']; ?>
					</div>

					<div class="td-secondary">
						 <?php echo $row['phone']; ?>
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

				<!-- GROUP -->
				
				<td style="text-align: center;" class="hidden-phone">
					<?php
					if ($row['id_group'] > 0)
					{
						if ($canEdit && $user->authorise('core.access.groups', 'com_vikappointments'))
						{
							?>
							<a href="index.php?option=com_vikappointments&amp;task=group.edit&amp;type=2&amp;cid[]=<?php echo $row['id_group']; ?>">
								<?php echo $row['gname']; ?>
							</a>
							<?php
						}
						else
						{
							echo $row['gname'];
						}
					}
					else
					{
						echo '/';
					}
					?>
				</td>

				<!-- SERVICES -->
				
				<td style="text-align: center;">
					<a href="index.php?option=com_vikappointments&amp;view=emprates&amp;cid[]=<?php echo $row['id']; ?>">
						<i class="fas fa-project-diagram big"></i>
					</a>
				</td>

				<!-- PAYMENTS -->
				
				<?php
				if ($user->authorise('core.access.payments', 'com_vikappointments'))
				{
					?>
					<td style="text-align: center;">
						<a href="index.php?option=com_vikappointments&amp;view=payments&amp;id_employee=<?php echo $row['id']; ?>">
							<i class="fas fa-credit-card big"></i>
						</a>
					</td>
					<?php
				}
				?>

				<!-- LOCATIONS -->
				
				<?php
				if ($user->authorise('core.access.locations', 'com_vikappointments'))
				{
					?>
					<td style="text-align: center;">
						<a href="index.php?option=com_vikappointments&amp;view=locations&amp;id_employee=<?php echo $row['id']; ?>">
							<i class="fas fa-map-marker-alt big"></i>
						</a>
					</td>
					<?php
				}
				?>

				<!-- LISTABLE -->
				
				<td style="text-align: center;" class="hidden-phone">
					<?php echo JHtml::fetch('vaphtml.admin.stateaction', $row['listable'], $row['id'], 'employee.listable', $canEditState); ?>
				</td>

				<!-- LANGUAGES -->

				<?php
				if ($multi_lang && $canEdit)
				{
					?>
					<td style="text-align: center;">
						<a href="index.php?option=com_vikappointments&amp;view=langemployees&amp;id_employee=<?php echo $row['id']; ?>">
							<?php
							foreach ($row['languages'] as $lang)
							{
								echo ' ' . JHtml::fetch('vaphtml.site.flag', $lang) . ' ';
							}
							?>
						</a>
					</td>
					<?php
				}
				?>

				<!-- IMAGE -->

				<td style="text-align: center;" class="hidden-phone">
					<?php echo JHtml::fetch('vaphtml.admin.imagestatus', $row['image']); ?>
				</td>
			</tr>
			
		<?php }	?>

	</table>

<?php } ?>

	<!-- hidden input for import tool -->
	<input type="hidden" name="import_type" value="employees" />

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="employees" />
	<input type="hidden" name="from" value="employees" />

	<input type="hidden" name="filter_order" value="<?php echo $this->ordering; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->orderDir; ?>" />

	<?php echo JHtml::fetch('form.token'); ?>
	<?php echo $this->navbut; ?>
</form>

<script>

	(function($) {
		'use strict';

		window['clearFilters'] = () => {
			$('#vapkeysearch').val('');
			$('#vap-status-sel').updateChosen('');
			$('#vap-group-sel').updateChosen(-1);
			
			document.adminForm.submit();
		}

		$(function() {
			VikRenderer.chosen('.btn-toolbar');

			Joomla.submitbutton = function(task) {
				if (task == 'reportsemp' || task == 'import' || task == 'export') {
					// populate view instead of task
					document.adminForm.view.value = task;
					task = '';
				}
				
				Joomla.submitform(task, document.adminForm);
			}
		});
	})(jQuery);
	
</script>
