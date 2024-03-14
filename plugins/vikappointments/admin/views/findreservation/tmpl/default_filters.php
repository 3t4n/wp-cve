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

$filters = $this->filters;

if (count($this->services) > 0 || count($this->employees) > 0)
{
	// build services select
	$services_select = '<select name="id_ser" id="vap-service-sel">'
		. JHtml::fetch('select.options', $this->services, 'id', 'name', $filters['id_ser'])
		. '</select>';

	// check if we should use a blank option (- all employees -)
	$blank_option = $filters['search_mode'] == 2 && count($this->employees) != 1;

	// build employees select
	$employees_select = '<select name="id_emp" id="vap-employee-sel">'
		. ($blank_option ? '<option></option>' : '')
		. JHtml::fetch('select.options', $this->employees, 'id', 'nickname', $filters['id_emp'])
		. '</select>';
	?>
	<div class="btn-toolbar vapresfiltertoolbar" id="filter-bar" style="font-size: inherit;">
		
		<div class="btn-group pull-left" style="font-size: inherit;">
			<?php echo ($filters['search_mode'] == 1 ? $employees_select : $services_select); ?>
		</div>

		<div class="btn-group pull-left" style="font-size: inherit;">
			<?php echo ($filters['search_mode'] == 1 ? $services_select : $employees_select); ?>
		</div>

		<div class="btn-group pull-left">
			<input type="number" name="people" value="<?php echo $filters['people']; ?>" min="1" max="999" step="1" title="<?php echo $this->escape(JText::translate('VAPMANAGERESERVATION25')); ?>" class="hasTooltip" />
		</div>

		<div class="btn-group pull-right">
			<button type="button" class="btn" id="switch-search-mode">
				<i class="fas fa-sync-alt"></i>&nbsp;<?php echo JText::translate('VAPFINDRESREVSEARCH'); ?>
			</button>
		</div>

		<input type="hidden" name="searchmode" value="<?php echo $filters['search_mode']; ?>" />

	</div>
	<?php
}

if (!empty($this->appointment))
{
	// fetch summary text
	$text = JText::sprintf(
		'VAP_FINDRES_EDIT_SUMMARY',
		$this->appointment->service->name,
		$this->appointment->employee->name,
		$this->appointment->checkin->lc2
	);

	// display summary of the appointment
	echo VAPApplication::getInstance()->alert($text);
}

JText::script('VAPFINDRESALLEMPLOYEES');
?>

<script>

	jQuery(function($) {
		$('#vap-service-sel').select2({
			allowClear: false,
			width: 300,
		});

		$('#vap-employee-sel').select2({
			placeholder: Joomla.JText._('VAPFINDRESALLEMPLOYEES'),
			allowClear: <?php echo count($this->employees) == 1 ? 'false' : 'true'; ?>,
			width: 300,
		});

		$('#vap-service-sel, #vap-employee-sel').on('change', () => {
			// refresh form after changing service or employee
			document.adminForm.submit();
		});

		$('#switch-search-mode').on('click', () => {
			// fetch new search mode
			const newSearchMode = <?php echo ($filters['search_mode'] % 2) + 1; ?>;
			// switch search mode
			$('input[name="searchmode"]').val(newSearchMode);

			// submit form
			document.adminForm.submit();
		});

		$('input[name="people"]').on('change', () => {
			// refresh timeline on people change
			vapGetTimeline();
		});
	});

</script>
