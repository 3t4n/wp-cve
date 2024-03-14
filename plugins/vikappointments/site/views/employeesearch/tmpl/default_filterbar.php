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

?>

<div class="vapemployeeselect">
	<select name="id_service" id="vapsersel">
		<?php
		foreach ($this->groupServices() as $group)
		{
			if ($group->name)
			{
				?>
				<optgroup label="<?php echo $group->name; ?>">
				<?php
			}

			foreach ($group->services as $s)
			{
				?>
				<option value="<?php echo $s->id; ?>" <?php echo ($s->id == $this->idService ? 'selected="selected"' : ''); ?>>
					<?php
					// display service name
					echo $s->name;

					if ($s->price > 0)
					{
						// add price next to the name
						echo ' ' . VAPFactory::getCurrency()->format($s->price);
					}
					
					// then add formatted duration
					echo ' (' . VikAppointments::formatMinutesToTime($s->duration) . ')';
					?>
				</option>
				<?php
			}

			if ($group->name)
			{
				?>
				</optgroup>
				<?php
			}
		}
		?>
	</select>
</div>

<?php
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

// people dropdown
if ($this->service && $this->service->max_capacity > 1 && $this->service->max_per_res > 1)
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
if ($locationsCount = count($this->employee->locations))
{
	?>
	<div class="vap-empsearch-locations">
		<?php
		foreach ($this->employee->locations as $loc)
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
			$('#vapsersel').select2({
				allowClear: false,
				width: 300,
			});

			$('#vapmonthsel, #vapserpeopleselect').select2({
				minimumResultsForSearch: -1,
				allowClear: false,
				width: 150,
			});

			$('#vapsersel, #vapmonthsel, .vap-empsearch-locval').on('change', () => {
				document.empsearchform.submit();
			});
			
			<?php
			if ($this->service && $this->service->max_capacity > 1)
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

	function vapUpdateServiceRate(rate) {
		/**
		 * @todo 	Should the rate be updated
		 * 			also in case the new cost has been 
		 * 			nullified (free)?
		 */

		if (rate > 0) {
			// update only if the rate is higher than 0
			jQuery('#vapratebox').html(Currency.getInstance().format(rate));
		}
	}

</script>
