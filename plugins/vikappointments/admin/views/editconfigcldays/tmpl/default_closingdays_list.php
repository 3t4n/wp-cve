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

// get closing days
$closing_days = VikAppointments::getClosingDays();

$cdLayout = new JLayoutFile('blocks.card');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">

		<div class="vap-cards-container cards-cl-days" id="cards-cl-days">

			<?php
			foreach ($closing_days as $i => $day)
			{
				if ($day['freq'] == 1)
				{
					// weekly
					$day['label'] = JDate::getInstance($day['ts'])->format('l');
				}
				else
				{
					$day['label'] = JText::translate('VAPFREQUENCYTYPE' . $day['freq']);
				}

				?>
				<div class="vap-card-fieldset" id="cl-day-fieldset-<?php echo $i; ?>">

					<?php
					$displayData = array();

					// reduce card size
					$displayData['class'] = 'compress';

					// fetch primary text
					$displayData['primary'] = $day['date'];
				
					// fetch secondary text
					$displayData['secondary'] = '<span class="badge badge-info">' . $day['label'] . '</span>';

					if ($day['services'])
					{
						foreach ($day['services'] as $id_service)
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
					$displayData['edit'] = 'vapOpenClosingDayCard(\'' . $i . '\');';

					// render layout
					echo $cdLayout->render($displayData);
					?>
					
					<input type="hidden" name="cl_day_json[]" value="<?php echo $this->escape(json_encode($day)); ?>" />

				</div>
				<?php
			}
			?>

			<!-- ADD PLACEHOLDER -->

			<div class="vap-card-fieldset add add-cl-day">
				<div class="vap-card compress">
					<i class="fas fa-plus"></i>
				</div>
			</div>

		</div>

		<div style="display:none;" id="cl-day-struct">
					
			<?php
			// create structure for records
			$displayData = array();
			$displayData['class']     = 'compress';
			$displayData['primary']   = '';
			$displayData['secondary'] = '';
			$displayData['edit']      = true;

			echo $cdLayout->render($displayData);
			?>

		</div>

	</div>

</div>

<?php
$footer  = '<button type="button" class="btn btn-success" data-role="save">' . JText::translate('JAPPLY') . '</button>';
$footer .= '<button type="button" class="btn btn-danger" data-role="delete" style="float:right;">' . JText::translate('VAPDELETE') . '</button>';

// render inspector to manage closing days
echo JHtml::fetch(
	'vaphtml.inspector.render',
	'cldays-inspector',
	array(
		'title'       => JText::translate('VAPMANAGECONFIG11'),
		'closeButton' => true,
		'keyboard'    => true,
		'footer'      => $footer,
		'width'       => 400,
	),
	$this->loadTemplate('closingdays_modal')
);

JText::script('VAPFREQUENCYTYPE0');
JText::script('VAPFREQUENCYTYPE1');
JText::script('VAPFREQUENCYTYPE2');
JText::script('VAPFREQUENCYTYPE3');
JText::script('VAPALLSERVICES');
?>

<script>
	var CL_DAYS_COUNT   = <?php echo count($closing_days); ?>;
	var SELECTED_CL_DAY = null;

	jQuery(function($) {
		// open inspector for new closing days
		$('.vap-card-fieldset.add-cl-day').on('click', () => {
			vapOpenClosingDayCard();
		});

		// fill the form before showing the inspector
		$('#cldays-inspector').on('inspector.show', () => {
			var json = [];

			// fetch JSON data
			if (SELECTED_CL_DAY) {
				var fieldset = $('#' + SELECTED_CL_DAY);

				json = fieldset.find('input[name="cl_day_json[]"]').val();

				try {
					json = JSON.parse(json);
				} catch (err) {
					json = {};
				}
			}

			if (json.date === undefined) {
				// creating new record, hide delete button
				$('#cldays-inspector [data-role="delete"]').hide();
			} else {
				// editing existing record, show delete button
				$('#cldays-inspector [data-role="delete"]').show();
			}

			fillClosingDayForm(json);
		});

		// apply the changes
		$('#cldays-inspector').on('inspector.save', function() {
			// validate form
			if (!cdValidator.validate()) {
				return false;
			}

			// get saved record
			var data = getClosingDayData();

			var fieldset;

			if (SELECTED_CL_DAY) {
				fieldset = $('#' + SELECTED_CL_DAY);
			} else {
				fieldset = vapAddClosingDayCard(data);
			}

			if (fieldset.length == 0) {
				// an error occurred, abort
				return false;
			}

			// save JSON data
			fieldset.find('input[name="cl_day_json[]"]').val(JSON.stringify(data));

			// refresh card details
			vapRefreshClosingDayCard(fieldset, data);

			// auto-close on save
			$(this).inspector('dismiss');
		});

		// delete the record
		$('#cldays-inspector').on('inspector.delete', function() {
			var fieldset = $('#' + SELECTED_CL_DAY);

			// auto delete fieldset
			fieldset.remove();

			// auto-close on delete
			$(this).inspector('dismiss');
		});
	});

	function vapOpenClosingDayCard(index) {
		if (typeof index !== 'undefined') {
			SELECTED_CL_DAY = 'cl-day-fieldset-' + index;
		} else {
			SELECTED_CL_DAY = null;
		}

		// open inspector
		vapOpenInspector('cldays-inspector');
	}

	function vapAddClosingDayCard(data) {
		let index = CL_DAYS_COUNT++;

		SELECTED_CL_DAY = 'cl-day-fieldset-' + index;

		var html = jQuery('#cl-day-struct').clone().html();

		html = html.replace(/{id}/, index);

		jQuery(
			'<div class="vap-card-fieldset" id="cl-day-fieldset-' + index + '">' + html + '</div>'
		).insertBefore(jQuery('.vap-card-fieldset.add-cl-day').last());

		// get created fieldset
		let fieldset = jQuery('#' + SELECTED_CL_DAY);

		fieldset.vapcard('edit', 'vapOpenClosingDayCard(' + index + ')');

		// create input to hold JSON data
		let input = jQuery('<input type="hidden" name="cl_day_json[]" />').val(JSON.stringify(data));

		// append input to fieldset
		fieldset.append(input);

		return fieldset;
	}

	function vapRefreshClosingDayCard(elem, data) {
		// update primary text
		elem.vapcard('primary', data.date);

		let lookup, label;

		if (data.freq == 1) {
			// create week days lookup
			lookup = <?php echo json_encode(JHtml::fetch('vikappointments.days')); ?>;
			// get selected day of the week
			label = lookup[new Date(data.ts).getDay()].text;
		} else {
			// create frequency lookup
			lookup = [
				Joomla.JText._('VAPFREQUENCYTYPE0'),
				Joomla.JText._('VAPFREQUENCYTYPE1'),
				Joomla.JText._('VAPFREQUENCYTYPE2'),
				Joomla.JText._('VAPFREQUENCYTYPE3'),
			];
			// get frequency label
			label = lookup[data.freq];
		}

		// append frequency label
		let secondary = jQuery('<span class="badge badge-info"></span>').text(label);

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
