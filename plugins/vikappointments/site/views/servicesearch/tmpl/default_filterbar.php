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

// employee dropdown
if ($this->service->choose_emp)
{
	$options = array();

	if ($this->service->random_emp)
	{
		// accept "anyone" selection
		$options[] = JHtml::fetch('select.option', '', '');
	}

	foreach ($this->service->employees as $e)
	{
		$options[] = JHtml::fetch('select.option', $e->id, $e->nickname);
	}

	?>
	<div class="vapemployeeselect">
		<select name="id_employee" id="vapempsel">
			<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $this->idEmployee); ?>
		</select>
	</div>
	<?php
}
else
{
	?><input type="hidden" name="id_employee" value="<?php echo $this->idEmployee; ?>" /><?php
}

// months dropdown
if (count($this->calendar->select) > 1)
{
	?>
	<div class="vapmonthselect">
		<select name="month" id="vapmonthsel">
			<?php echo JHtml::fetch('select.options', $this->calendar->select, 'value', 'text', $this->month); ?>
		</select>
	</div>
	<?php
}

// people  dropdown
if ($this->service->max_capacity > 1 && $this->service->max_per_res > 1)
{
	$options = array();

	for ($i = $this->service->min_per_res; $i <= $this->service->max_per_res; $i++)
	{
		$options[] = JHtml::fetch('select.option', $i, JText::plural('VAP_N_PEOPLE', $i));
	}

	?>
	<div class="vapserpeoplediv">
		<select name="people" id="vapserpeopleselect">
			<?php echo JHtml::fetch('select.options', $options); ?>
		</select>
	</div>
	<?php
}

// locations checkboxes
if ($locationsCount = count($this->service->locations))
{
	?>
	<div class="vap-empsearch-locations">
		<?php
		foreach ($this->service->locations as $loc)
		{
			$checked = ''; 
			
			if (!$this->options['locations'] || in_array($loc->id, $this->options['locations']))
			{
				$checked = 'checked="checked"';
			}
			?>
			<div class="vap-empsearch-locbox">
				<?php if ($locationsCount > 1): ?>
					<input type="checkbox" value="<?php echo $loc->id; ?>" <?php echo $checked; ?> name="locations[]" id="vaplocation<?php echo $loc->id; ?>" class="vap-empsearch-locval" />
				<?php endif; ?>
				<label for="vaplocation<?php echo $loc->id; ?>">
					<?php if ($locationsCount == 1): ?>
						<i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i>
					<?php endif; ?>
					<?php echo $loc->name . ($loc->address ? ' (' . $loc->address . ')' : ''); ?>
				</label>
			</div>
			<?php
		}
		?>
	</div>
	<?php
}

?>

<script>

	(function($) {
		'use strict';

		$(function() {
			$('#vapempsel').select2({
				<?php
				if ($this->service->random_emp)
				{
					// in case of "anyone" selection, display placeholder
					JText::script('VAP_EMP_ANYONE_OPT');
					?>
					placeholder: Joomla.JText._('VAP_EMP_ANYONE_OPT'),
					allowClear: true,
					<?php
				}
				else
				{
					// do not allow empty selection in case the "anyone" option is turned off
					?>
					allowClear: false,
					<?php
				}
				?>
				width: 300,
			});

			$('#vapmonthsel, #vapserpeopleselect').select2({
				minimumResultsForSearch: -1,
				allowClear: false,
				width: 150,
			});

			$('#vapempsel, #vapmonthsel, .vap-empsearch-locval').on('change', () => {
				document.sersearchform.submit();
			});

			<?php
			if ($this->service->max_capacity > 1)
			{
				?>
				$('#vapserpeopleselect').on('change', function() {	
					/**
					 * Refresh timeline to re-calculate availability.
					 * See main layout file for further details about
					 * the vapGetTimeline() function.
					 */
					let refreshed = vapGetTimeline();

					if (refreshed === false) {
						// Missing date, price not updated.
						// Make a request to refresh the price according to the new parameters.
						UIAjax.do(
							'<?php echo VAPApplication::getInstance()->ajaxUrl('index.php?option=com_vikappointments&task=employeesearch.refreshprice'); ?>',
							{
								id_emp: <?php echo (int) $this->idEmployee; ?>,
								id_ser: <?php echo (int) $this->idService; ?>,
								day: '<?php echo JFactory::getDate()->format('Y-m-d'); ?>',
								people: $(this).val(),
							},
							(result) => {
								// refresh the service price
								vapUpdateServiceRate(result.rate);
							}
						);
					}
				});
				<?php
			}
			?>
		});
	})(jQuery);

</script>
