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
JHtml::fetch('vaphtml.assets.fontawesome');

$rows 	= $this->rows;
$navbut = $this->navbut;

$filters = $this->filters;

$employees = $this->employees;

$config = VAPFactory::getConfig();

$date_format = $config->get('dateformat');
$time_format = $config->get('timeformat');

$vik = VAPApplication::getInstance();

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_vikappointments');

$date = new JDate();

$is_searching = $this->hasFilters();

?>

<form action="index.php?option=com_vikappointments" method="post" name="adminForm" id="adminForm">
	
	<div class="btn-toolbar" style="height: 32px;">

		<div class="btn-group pull-left">
			<?php
			$options = array();

			foreach ($employees as $e)
			{
				$options[] = JHtml::fetch('select.option', $e->id, $e->nickname);
			}
			?>
			<select name="id_employee" id="vap-employee-sel" onChange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['id_employee']); ?>
			</select>
		</div>

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

		<div class="btn-group pull-left">
			<?php
			$options = array();
			$options[] = JHtml::fetch('select.option', -1, JText::translate('VAPFILTERSELECTTYPE'));
			$options[] = JHtml::fetch('select.option', 1, JText::translate('VAPWDLEGENDLABEL1'));
			$options[] = JHtml::fetch('select.option', 2, JText::translate('VAPWDLEGENDLABEL2'));
			?>
			<select name="type" id="vap-type-sel" class="<?php echo ($filters['type'] != -1 ? 'active' : ''); ?>" onChange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['type']); ?>
			</select>
		</div>

		<div class="btn-group pull-left">
			<?php
			$options = array();
			$options[] = JHtml::fetch('select.option', -1, JText::translate('VAPFILTERSELECTSTATUS'));
			$options[] = JHtml::fetch('select.option', 1, JText::translate('VAPMANAGEWD5'));
			$options[] = JHtml::fetch('select.option', 0, JText::translate('VAPMANAGEEMPLOYEE22'));
			?>
			<select name="status" id="vap-status-sel" class="<?php echo ($filters['status'] != -1 ? 'active' : ''); ?>" onChange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $filters['status']); ?>
			</select>
		</div>

		<div class="btn-group pull-left">
			<?php echo $vik->calendar($filters['date'], 'date', 'vap-date', null, array('onChange' => 'document.adminForm.submit();')); ?>
		</div>

	</div>
	
<?php
if (count($rows) == 0)
{
	$url = 'index.php?option=com_vikappointments&amp;task=employee.edit&amp;cid[]=' . $filters['id_employee'] . '#employee_workdays';
	
	if (!$is_searching)
	{
		// no active filters, display tip to create new working times
		echo $vik->alert(
			'<div>' . JText::translate('JGLOBAL_NO_MATCHING_RESULTS') . '</div>'
			. '<a href="' . $url . '" target="_blank">' . JText::translate('VAPSERWDLINKTO') . '</a>'
		);
	}
	else
	{
		// no active filters, display tip to create new working times
		echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
	}
}
else
{
	?>
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>

				<th width="1%">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>

				<!-- DAY -->

				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="30%" style="text-align: left;">
					<?php echo JText::translate('VAPMANAGEWD2'); ?>
				</th>

				<!-- PARENT -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="10%" style="text-align: center;">
					&nbsp;
				</th>

				<!-- FROM -->

				<th class="<?php echo $vik->getAdminThClass(); ?>" width="15%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGEWD3'); ?>
				</th>
				
				<!-- TO -->

				<th class="<?php echo $vik->getAdminThClass(); ?>" width="15%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGEWD4'); ?>
				</th>
				
				<!-- STATUS -->

				<th class="<?php echo $vik->getAdminThClass(); ?>" width="10%" style="text-align: center;">
					<?php echo JText::translate('VAPMANAGEWD5'); ?>
				</th>
			
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		
		<?php
		for ($i = 0, $n = count($rows); $i < $n; $i++)
		{	
			$row = $rows[$i];

			if ($row['ts'] == -1)
			{
				// weekly
				$day = $date->dayToString($row['day']);
			}
			else
			{
				// recurring
				$day = JHtml::fetch('date', $row['ts'], JText::translate('DATE_FORMAT_LC1'), date_default_timezone_get());
			}
			?>
			<tr class="row<?php echo ($i % 2); ?>">

				<td>
					<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>">
				</td>
				
				<!-- DAY -->

				<td>
					<div class="td-primary">
						<?php
						if ($canEdit)
						{
							?>
							<a href="index.php?option=com_vikappointments&amp;task=serworkday.edit&amp;cid[]=<?php echo $row['id']; ?>">
								<?php echo $day; ?>
							</a>
							<?php
						}
						else
						{
							echo $day;
						}
						?>
					</div>
				</td>

				<!-- PARENT -->

				<td style="text-align: center;">
					<?php
					if ($row['parent'] > 0)
					{
						$icon    = 'link';
						$tooltip = JText::translate('VAPMANAGEWD_LINK');
					}
					else
					{
						$icon    = 'unlink';
						$tooltip = JText::translate('VAPMANAGEWD_UNLINK');
					}
					?>
					
					<i class="fas fa-<?php echo $icon; ?> big hasTooltip" title="<?php echo $this->escape($tooltip); ?>"></i>
				</td>

				<!-- FROM -->
				
				<td style="text-align: center;">
					<?php
					if ($row['closed'])
					{
						echo '/';
					}
					else
					{
						echo JHtml::fetch('vikappointments.min2time', $row['fromts'], $string = true);
					}
					?>
				</td>

				<!-- TO -->
				
				<td style="text-align: center;">
					<?php
					if ($row['closed'])
					{
						echo '/';
					}
					else
					{
						echo JHtml::fetch('vikappointments.min2time', $row['endts'], $string = true);
					}
					?>
				</td>

				<!-- STATUS -->
				
				<td style="text-align: center;">
					<?php echo JHtml::fetch('vaphtml.admin.stateaction', !$row['closed']); ?>
				</td>

			</tr>
			<?php
		}
		?>
	</table>
	<?php
}
?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="serworkdays" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="id_service" value="<?php echo $filters['id_service']; ?>" />

	<?php echo JHtml::fetch('form.token'); ?>
	<?php echo $this->navbut; ?>
</form>

<script>

	jQuery(function($) {
		VikRenderer.chosen('.btn-toolbar');
	});

	function clearFilters() {
		jQuery('#vap-type-sel').updateChosen(-1);
		jQuery('#vap-status-sel').updateChosen(-1);
		jQuery('#vap-date').val('');
		
		document.adminForm.submit();
	}

</script>
