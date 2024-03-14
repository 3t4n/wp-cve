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
JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('formbehavior.chosen');

$rows = $this->rows;

$filters = $this->filters;

$vik = VAPApplication::getInstance();

$multi_lang = VikAppointments::isMultilanguage();

$user = JFactory::getUser();

$currency = VAPFactory::getCurrency();

$canEdit      = $user->authorise('core.edit', 'com_vikappointments');
$canEditState = $user->authorise('core.edit.state', 'com_vikappointments');
$canOrder     = $this->ordering == 's.ordering';

if ($canOrder && $canEditState)
{
	$saveOrderingUrl = 'index.php?option=com_vikappointments&task=service.saveOrderAjax&tmpl=component';
	JHtml::fetch('vaphtml.scripts.sortablelist', 'servicesList', 'adminForm', $this->orderDir, $saveOrderingUrl);
}

$is_searching = $this->hasFilters();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewServicesList". The event method receives the
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
		<!-- {"rule":"customizer","event":"onDisplayViewServicesList","type":"search","key":"search"} -->

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
			<a href="index.php?option=com_vikappointments&amp;view=restrictions" class="btn">
				<i class="fas fa-calendar-times"></i>&nbsp;
				<?php echo JText::translate('VAPMANAGESPECIALRESTR'); ?>
			</a>

			<a href="index.php?option=com_vikappointments&amp;view=rates" class="btn">
				<i class="fas fa-dollar-sign"></i>&nbsp;
				<?php echo JText::translate('VAPMANAGESPECIALRATES'); ?>
			</a>
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

		<?php
		$options = array(
			JHtml::fetch('select.option', -1, JText::translate('VAPFILTERSELECTGROUP')),
			JHtml::fetch('select.option', 0, JText::translate('VAPSERVICENOGROUP')),
		);
		
		$options = array_merge($options, JHtml::fetch('vaphtml.admin.groups', 1));
		?>
		<div class="btn-group pull-left">
			<select name="id_group" id="vap-group-sel" class="<?php echo ($filters['id_group'] != -1 ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['id_group']); ?>
			</select>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewServicesList","type":"search","key":"filters"} -->

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
	<!-- {"rule":"customizer","event":"onDisplayServicesTableTH","type":"th"} -->

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayServicesTableTD","type":"td"} -->

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>" id="servicesList">

		<?php echo $vik->openTableHead(); ?>
			<tr>

				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<!-- ID -->

				<th class="<?php echo $vik->getAdminThClass('left hidden-phone nowrap'); ?>" width="1%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGESERVICE1', 's.id', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- NAME -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="15%" style="text-align: left;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGESERVICE2', 's.name', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- DURATION -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="8%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGESERVICE4', 's.duration', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- PRICE -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="8%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGESERVICE5', 's.price', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- CAPACITY -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="8%" style="text-align: center;">
					<?php echo JText::translate('VAPCAPACITY'); ?>
				</th>

				<!-- STATUS -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="8%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGESERVICE6'); ?>
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
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="10%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', 'VAPMANAGESERVICE10', 'g.name', $this->orderDir, $this->ordering); ?>
				</th>

				<!-- INFO -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="5%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGESERVICE15'); ?>
				</th>

				<!-- WORKING TIMES -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="5%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGESERVICE25'); ?>
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
					<?php echo JText::translate('VAPMANAGESERVICE9'); ?>
				</th>

				<!-- ORDERING -->
				
				<th class="<?php echo $vik->getAdminThClass('hidden-phone nowrap'); ?>" width="1%" style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.sort', '<i class="fas fa-sort"></i>', 's.ordering', $this->orderDir, $this->ordering); ?>
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
				
				<!-- ID -->
				
				<td class="hidden-phone">
					<?php echo $row['id']; ?>
				</td>

				<!-- NAME -->
				
				<td>
					<span class="td-primary">
						<?php
						if ($canEdit)
						{
							?>
							<a href="index.php?option=com_vikappointments&amp;task=service.edit&amp;cid[]=<?php echo $row['id']; ?>">
								<?php echo $row['name']; ?>
							</a>
							<?php
						}
						else
						{
							echo $row['name'];
						}
						?>
					</span>
				</td>

				<!-- DURATION -->
				
				<td style="text-align: center;">
					<?php
					echo VikAppointments::formatMinutesToTime($row['duration']);

					if ($row['sleep'] != 0)
					{
						$sleep = VikAppointments::formatMinutesToTime($row['sleep']);
						?>
						<div class="td-secondary">
							<?php echo ($row['sleep'] > 0 ? '+' : '') . $sleep; ?>
						</div>
						<?php
					}
					?>
				</td>

				<!-- PRICE -->
				
				<td style="text-align: center;">
					<?php echo $currency->format($row['price']); ?>
				</td>

				<!-- CAPACITY -->
				
				<td style="text-align: center;" class="hidden-phone">
					<?php
					if ($row['max_capacity'] > 1)
					{
						?>
						<strong> <?php echo $row['max_capacity']; ?></strong>
						<?php
						echo '(' . $row['min_per_res'] . '-' . $row['max_per_res'] . ')';
					}
					else
					{
						echo '/';
					}
					?>
				</td>

				<!-- STATUS -->
				
				<td style="text-align: center;" class="hidden-phone">
					<?php
					$state = array(
						'state' => $row['published'],
						'start' => $row['start_publishing'],
						'end'   => $row['end_publishing'],
					);

					echo JHtml::fetch('vaphtml.admin.stateaction', $state, $row['id'], 'service.publish', $canEditState);
					?>
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
							<a href="index.php?option=com_vikappointments&amp;task=group.edit&amp;type=1&amp;cid[]=<?php echo $row['id_group']; ?>">
								<?php echo $row['group_name']; ?>
							</a>
							<?php
						}
						else
						{
							echo $row['group_name'];
						}
					}
					else
					{
						echo '/';
					}
					?>
				</td>

				<!-- INFO -->
				
				<td style="text-align: center;" class="hidden-phone">
					<a href="javascript:void(0)" onclick="vapOpenServiceInfoModal(<?php echo $row['id']; ?>,'<?php echo addslashes($row['name']); ?>'); return false;">
						<i class="fas fa-search big"></i>
					</a>
				</td>

				<!-- WORKING TIMES -->
				
				<td style="text-align: center;">
					<a href="index.php?option=com_vikappointments&view=serworkdays&id_service=<?php echo $row['id']; ?>">
						<i class="fas fa-calendar-day big"></i>
					</a>
				</td>
				
				<!-- LANGUAGES -->

				<?php
				if ($multi_lang && $canEdit)
				{
					?>
					<td style="text-align: center;">
						<a href="index.php?option=com_vikappointments&amp;view=langservices&amp;id_service=<?php echo $row['id']; ?>">
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
	<!-- hidden input for import tool -->
	<input type="hidden" name="import_type" value="services" />

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="services" />

	<input type="hidden" name="filter_order" value="<?php echo $this->ordering; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->orderDir; ?>" />

	<?php echo JHtml::fetch('form.token'); ?>
	<?php echo $this->navbut; ?>
</form>

<?php
echo JHtml::fetch(
	'bootstrap.renderModal',
	'jmodal-serviceinfo',
	array(
		'title'       => '',
		'closeButton' => true,
		'keyboard'    => false, 
		'bodyHeight'  => 80,
		'url'		  => '',
	)
);
?>

<script>

	(function($) {
		'use strict';

		window['clearFilters'] = () => {
			$('#vapkeysearch').val('');
			$('#vap-status-sel').updateChosen('');
			$('#vap-group-sel').updateChosen(-1);
			
			document.adminForm.submit();
		}

		// modal

		window['vapOpenServiceInfoModal'] = (id, name) => {
			let url = 'index.php?option=com_vikappointments&view=serviceinfo&tmpl=component&cid[]=' + id;

			$('#jmodal-serviceinfo .modal-header h3').html(name);

			vapOpenJModal('serviceinfo', url, true);
		}

		window['vapOpenJModal'] = (id, url, jqmodal) => {
			<?php echo $vik->bootOpenModalJS(); ?>
		}

		$(function() {
			VikRenderer.chosen('.btn-toolbar');

			Joomla.submitbutton = function(task) {
				if (task == 'reportsser' || task == 'import' || task == 'export') {
					// populate view instead of task
					document.adminForm.view.value = task;
					task = '';
				}

				Joomla.submitform(task, document.adminForm);
			}
		});
	})(jQuery);
	
</script>
