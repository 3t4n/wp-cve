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

$vik = VAPApplication::getInstance();

$filters = $this->filters;

if (count($this->services) > 0 || count($this->employees) > 0)
{
	// build services select
	$services_select = '<select name="id_ser" id="vap-service-sel">'
		. JHtml::fetch('select.options', $this->services, 'id', 'name', $filters['id_ser'])
		. '</select>';

	// build employees select
	$employees_select = '<select name="id_emp" id="vap-employee-sel">'
		. JHtml::fetch('select.options', $this->employees, 'id', 'nickname', $filters['id_emp'])
		. '</select>';
	?>
	<div class="btn-toolbar vapresfiltertoolbar" id="filter-bar" style="font-size: inherit;">
		
		<div class="btn-group pull-left" style="font-size: inherit;">
			<?php echo $employees_select; ?>
		</div>

		<div class="btn-group pull-left" style="font-size: inherit;">
			<?php echo $services_select; ?>
		</div>

		<div class="btn-group pull-right">
			<button type="button" class="btn active">
				<i class="fas fa-calendar-alt"></i>&nbsp;
				<?php echo JText::translate('VAPFREQUENCYTYPE2'); ?>
			</button>

			<a href="<?php echo $vik->addUrlCsrf('index.php?option=com_vikappointments&task=calendar.switch&layout=caldays', $xhtml = true); ?>" class="btn">
				<i class="fas fa-calendar-week"></i>&nbsp;
				<?php echo JText::translate('VAPFREQUENCYTYPE1'); ?>
			</a>
		</div>

	</div>
	<?php
}
?>

<div class="vapallcalhead">
	<a href="javascript:void(0)" id="prev-year-btn">
		<i class="fas fa-angle-double-left big"></i>
	</a>
	
	<span class="vaptitleyearsp">
		<?php echo $filters['year']; ?>

		<input type="hidden" name="year" value="<?php echo $filters['year']; ?>" />
	</span>
	
	<a href="javascript:void(0)" id="next-year-btn">
		<i class="fas fa-angle-double-right big"></i>
	</a>
</div>

<script>

	jQuery(function($) {
		$('#vap-service-sel, #vap-employee-sel').select2({
			allowClear: false,
			width: 200,
		});

		$('#vap-service-sel, #vap-employee-sel').on('change', () => {
			// refresh form after changing service or employee
			document.adminForm.submit();
		});

		$('#prev-year-btn, #next-year-btn').on('click', function() {
			// get year input
			const yearInput = $('#adminForm input[name="year"]');

			// get year value
			let y = parseInt(yearInput.val());

			if ($(this).attr('id') == 'prev-year-btn') {
				y--;
			} else {
				y++;
			}

			// update input
			yearInput.val(y);

			// refresh form
			document.adminForm.submit();
		});
	});

</script>
