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

$filtersUri = 'index.php?option=com_vikappointments&view=employeeslist';

foreach ($this->filters as $k => $v)
{
	if ($v)
	{
		$filtersUri .= "&filters[$k]=$v";
	}
}

if ($this->itemid)
{
	$filtersUri .= "&Itemid={$this->itemid}";
}

$config = VAPFactory::getConfig();

?>

<div class="vap-emplist-toolbar">
	
	<?php
	/**
	 * The toolbar TOP box contains the select to filter the employees by group
	 * and a button to toggle the box containing the sortable fields.
	 */
	?>

	<div class="vap-emplist-toolbar-top">

		<?php
		// check if the group selection is allowed and the groups list
		// contains more than one element
		if (count($this->groups) > 1 && $config->getBool('empgroupfilter'))
		{ 
			$url = $filtersUri . '&ordering=' . $this->options['ordering'];
			
			?>

			<div class="vap-empgroup-filterblock">

				<form action="<?php echo JRoute::rewrite($url); ?>" id="vap-empgroup-form" method="post">
					<div class="vap-emplist-groups">
						<select name="employee_group" class="vap-empgroup-sel">
							<option></option>
							<?php echo JHtml::fetch('select.options', $this->groups, 'id', 'name', $this->empGroup); ?>
						</select>
					</div>
				</form>

			</div>

		<?php
		}
		
		// check if the customers are allowed to sort the employees list
		if ($config->getBool('empordfilter'))
		{
			// display the button to toggle the sortable fields
			?>
			<div class="vap-emplist-ordering">
				<span>
					<button type="button" class="vap-btn small blue" onClick="vapDisplayFilters(this);">
						<?php echo JText::translate('VAPEMPORDERINGTITLE'); ?>
					</button>
				</span>
				
				<div class="vap-emplist-ordering-fields">
					<ul>
						<?php
						foreach (VikAppointments::getEmployeesAvailableOrderings() as $ord)
						{
							if (empty($this->filters['service']) && in_array($ord, array(7, 8)))
							{
								// it is not possible to sort by rate if the service hasn't been selected
								continue;
							}

							$url = $filtersUri . '&ordering=' . $ord;

							if ($this->empGroup)
							{
								$url .= '&employee_group=' . $this->empGroup;
							}
							?>

							<li class="<?php echo $ord == $this->options['ordering'] ? 'selected' : ''; ?>">
								<?php
								if ($ord != $this->options['ordering'])
								{
									// create a link to sort the employees using this type
									?>
									<a href="<?php echo $url; ?>"><?php echo JText::translate('VAPEMPORDERING' . $ord); ?></a>
									<?php
								}
								else
								{
									// this sortable type is already selected
									?>
									<span><?php echo JText::translate('VAPEMPORDERING' . $ord); ?></span>
									<?php
								}
								?>
							</li>
							<?php
						}
						?>
					</ul>
				</div>

			</div>
			<?php
		}
		?>

	</div>

	<?php
	/**
	 * End of toolbar top box.
	 */

	// check if the customer is filtering the employees list
	if ($this->hasFilters)
	{
		// we need to show a response about the filtered used
		?>
		<div class="vap-empfilters_response">
			<?php
			if ($this->employeesCount > 0)
			{
				// we found something while searching with the given filters
				?>
				<span class="success-result">
					<?php
					if ($this->employeesCount > 1)
					{
						// the search found more than one employee
						echo JText::sprintf('VAPEMPLISTRESULTPLUS', $this->employeesCount);
					}
					else
					{
						// the search found only one employee
						echo JText::translate('VAPEMPLISTRESULT1');
					}
					?>
				</span>
				<?php
			}
			else
			{
				// no employee found while searching with the given filters
				?>
				<span class="bad-result">
					<?php echo JText::translate('VAPEMPLISTRESULT0'); ?>
				</span>
				<?php
			}
			?>
		</div>
		<?php
	}
	?>
</div>

<?php
JText::script('VAPEMPALLGROUPSOPTION');
?>

<script>
	
	jQuery(function($) {
		$('.vap-empgroup-sel').select2({
			placeholder: Joomla.JText._('VAPEMPALLGROUPSOPTION'),
			allowClear: true,
			width: 300
		});

		$('.vap-empgroup-sel').on('change', () => {
			$('#vap-empgroup-form').submit();
		});
	});

	function vapDisplayFilters(button) {
		var fields = jQuery('.vap-emplist-ordering-fields');
		if (!fields.is(':visible')) {
			fields.slideDown();
			jQuery(button).addClass('active');
		} else {
			fields.slideUp();
			jQuery(button).removeClass('active');
		}
	}

</script>
