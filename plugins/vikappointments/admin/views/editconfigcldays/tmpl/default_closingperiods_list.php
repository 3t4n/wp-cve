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

// get closing periods
$closing_periods = VikAppointments::getClosingPeriods();

$cpLayout = new JLayoutFile('blocks.card');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">

		<div class="vap-cards-container cards-cl-periods" id="cards-cl-periods">

			<?php
			foreach ($closing_periods as $i => $period)
			{
				?>
				<div class="vap-card-fieldset" id="cl-period-fieldset-<?php echo $i; ?>">

					<?php
					$displayData = array();

					// reduce card size
					$displayData['class'] = 'compress';

					// fetch primary text
					$displayData['primary'] = $period['datestart'] . ' - ' . $period['dateend'];
				
					// fetch secondary text
					$displayData['secondary'] = '';

					if ($period['services'])
					{
						foreach ($period['services'] as $id_service)
						{
							if (isset($this->services[$id_service]))
							{
								$displayData['secondary'] .= '<span class="badge badge-important">' . $this->services[$id_service] . '</span>';
							}
						}
					}
					else
					{
						$displayData['secondary'] .= '<span class="badge badge-success">' . JText::translate('VAPALLSERVICES') . '</span>';
					}

					// fetch edit button
					$displayData['edit'] = 'vapOpenClosingPeriodCard(\'' . $i . '\');';

					// render layout
					echo $cpLayout->render($displayData);
					?>
					
					<input type="hidden" name="cl_period_json[]" value="<?php echo $this->escape(json_encode($period)); ?>" />

				</div>
				<?php
			}
			?>

			<!-- ADD PLACEHOLDER -->

			<div class="vap-card-fieldset add add-cl-period">
				<div class="vap-card compress">
					<i class="fas fa-plus"></i>
				</div>
			</div>

		</div>

		<div style="display:none;" id="cl-period-struct">
					
			<?php
			// create structure for records
			$displayData = array();
			$displayData['class']     = 'compress';
			$displayData['primary']   = '';
			$displayData['secondary'] = '';
			$displayData['edit']      = true;

			echo $cpLayout->render($displayData);
			?>

		</div>

	</div>

</div>

<?php
$footer  = '<button type="button" class="btn btn-success" data-role="save">' . JText::translate('JAPPLY') . '</button>';
$footer .= '<button type="button" class="btn btn-danger" data-role="delete" style="float:right;">' . JText::translate('VAPDELETE') . '</button>';

// render inspector to manage closing periods
echo JHtml::fetch(
	'vaphtml.inspector.render',
	'clperiods-inspector',
	array(
		'title'       => JText::translate('VAPMANAGECONFIG11'),
		'closeButton' => true,
		'keyboard'    => true,
		'footer'      => $footer,
		'width'       => 400,
	),
	$this->loadTemplate('closingperiods_modal')
);
?>

<script>
	var CL_PERIODS_COUNT   = <?php echo count($closing_periods); ?>;
	var SELECTED_CL_PERIOD = null;

	jQuery(function($) {
		// open inspector for new closing periods
		$('.vap-card-fieldset.add-cl-period').on('click', () => {
			vapOpenClosingPeriodCard();
		});

		// fill the form before showing the inspector
		$('#clperiods-inspector').on('inspector.show', () => {
			var json = [];

			// fetch JSON data
			if (SELECTED_CL_PERIOD) {
				var fieldset = $('#' + SELECTED_CL_PERIOD);

				json = fieldset.find('input[name="cl_period_json[]"]').val();

				try {
					json = JSON.parse(json);
				} catch (err) {
					json = {};
				}
			}

			if (json.datestart === undefined) {
				// creating new record, hide delete button
				$('#clperiods-inspector [data-role="delete"]').hide();
			} else {
				// editing existing record, show delete button
				$('#clperiods-inspector [data-role="delete"]').show();
			}

			fillClosingPeriodForm(json);
		});

		// apply the changes
		$('#clperiods-inspector').on('inspector.save', function() {
			// validate form
			if (!cpValidator.validate()) {
				return false;
			}

			// get saved record
			var data = getClosingPeriodData();

			var fieldset;

			if (SELECTED_CL_PERIOD) {
				fieldset = $('#' + SELECTED_CL_PERIOD);
			} else {
				fieldset = vapAddClosingPeriodCard(data);
			}

			if (fieldset.length == 0) {
				// an error occurred, abort
				return false;
			}

			// save JSON data
			fieldset.find('input[name="cl_period_json[]"]').val(JSON.stringify(data));

			// refresh card details
			vapRefreshClosingPeriodCard(fieldset, data);

			// auto-close on save
			$(this).inspector('dismiss');
		});

		// delete the record
		$('#clperiods-inspector').on('inspector.delete', function() {
			var fieldset = $('#' + SELECTED_CL_PERIOD);

			// auto delete fieldset
			fieldset.remove();

			// auto-close on delete
			$(this).inspector('dismiss');
		});
	});

	function vapOpenClosingPeriodCard(index) {
		if (typeof index !== 'undefined') {
			SELECTED_CL_PERIOD = 'cl-period-fieldset-' + index;
		} else {
			SELECTED_CL_PERIOD = null;
		}

		// open inspector
		vapOpenInspector('clperiods-inspector');
	}

	function vapAddClosingPeriodCard(data) {
		let index = CL_PERIODS_COUNT++;

		SELECTED_CL_PERIOD = 'cl-period-fieldset-' + index;

		var html = jQuery('#cl-period-struct').clone().html();

		html = html.replace(/{id}/, index);

		jQuery(
			'<div class="vap-card-fieldset" id="cl-period-fieldset-' + index + '">' + html + '</div>'
		).insertBefore(jQuery('.vap-card-fieldset.add-cl-period').last());

		// get created fieldset
		let fieldset = jQuery('#' + SELECTED_CL_PERIOD);

		fieldset.vapcard('edit', 'vapOpenClosingPeriodCard(' + index + ')');

		// create input to hold JSON data
		let input = jQuery('<input type="hidden" name="cl_period_json[]" />').val(JSON.stringify(data));

		// append input to fieldset
		fieldset.append(input);

		return fieldset;
	}

	function vapRefreshClosingPeriodCard(elem, data) {
		// update primary text
		elem.vapcard('primary', data.datestart + ' - ' + data.dateend);

		// init secondary box
		let secondary = jQuery();

		if (data.servicesName.length) {
			data.servicesName.forEach((service) => {
				secondary = secondary.add(jQuery('<span class="badge badge-important"></span>').text(service));
			});
		} else {
			secondary = secondary.add(jQuery('<span class="badge badge-success"></span>').text(Joomla.JText._('VAPALLSERVICES')));
		}

		// update secondary text
		elem.vapcard('secondary', secondary);
	}

</script>
