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

$config = VAPFactory::getConfig();

$wdLayout = new JLayoutFile('blocks.card');

$date = new JDate();

?>

<div class="row-fluid">

	<!-- LEFT -->

	<div class="span6">

		<!-- WEEKLY -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				$help = $vik->createPopover(array(
					'title'   => JText::translate('VAPWDLEGENDLABEL1'),
					'content' => JText::sprintf('VAPWDLEGENDTITLE1', JHtml::fetch('date', 'now', 'l')),
				));

				echo $vik->openFieldset(JText::translate('VAPWDLEGENDLABEL1') . $help);
				?>

					<div class="vap-cards-container cards-workdays" id="cards-workdays-week">

						<?php
						for ($i = 1; $i <= 7; $i++)
						{
							$k = $i % 7;

							// get existing working times for this day of the week
							$wd_list = isset($this->worktimeWeek[$k]) ? $this->worktimeWeek[$k] : array();
							?>
							<div class="vap-card-fieldset up-to-2" id="weekday-fieldset-<?php echo $i; ?>" data-day="<?php echo $k; ?>">

								<?php
								$displayData = array();

								// reduce card size
								$displayData['class'] = 'compress';

								// fetch primary text
								$displayData['primary'] = $date->dayToString($k);

								if ($wd_list)
								{
									$badge  = '';
									$closed = false;

									foreach ($wd_list as $wd)
									{
										// create readable lables
										$from = JHtml::fetch('vikappointments.min2time', $wd->from);
										$to   = JHtml::fetch('vikappointments.min2time', $wd->to);

										// generate badge
										$badge .= "<span class=\"badge badge-info\">{$from} - {$to}</span>\n";

										// check whether the working time was a closure
										$closed = $closed || $wd->closed;
									}

									if ($closed)
									{
										// overwrite badge with closure
										$badge = "<span class=\"badge badge-important\">" . JText::translate('VAPMANAGEEMPLOYEE22') . "</span>\n";
									}
								}
								else
								{
									// no existing working days
									$badge = "<span class=\"badge badge-warning\">" . JText::translate('JGLOBAL_NO_MATCHING_RESULTS') . "</span>\n";
								}

								// fetch secondary text
								$displayData['secondary'] = $badge;

								// fetch edit button
								$displayData['edit'] = 'vapOpenWeekDayCard(' . $i . ');';

								// render layout
								echo $wdLayout->render($displayData);
								?>
								
								<input type="hidden" name="wd_json[]" value="<?php echo $this->escape(json_encode($wd_list)); ?>" />

							</div>
							<?php
						}
						?>

					</div>
				
				<?php echo $vik->closeFieldset(); ?>
			</div>
		</div>

	</div>

	<!-- RIGHT -->

	<div class="span6">

		<!-- SPECIAL DAYS -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				$help = $vik->createPopover(array(
					'title'   => JText::translate('VAPWDLEGENDLABEL2'),
					'content' => JText::sprintf('VAPWDLEGENDTITLE2', JHtml::fetch('date', 'now', JText::translate('DATE_FORMAT_LC1'))),
				));

				echo $vik->openFieldset(JText::translate('VAPWDLEGENDLABEL2') . $help);
				?>

					<div class="vap-cards-container cards-workdays" id="cards-workdays-special">

						<!-- ADD PLACEHOLDER (show in case of 3+ working days) -->

						<div class="vap-card-fieldset up-to-2 add add-spday" style="<?php echo (count($this->worktimeDate) >= 3 ? '' : 'display:none;'); ?>">
							<div class="vap-card compress">
								<i class="fas fa-plus"></i>
							</div>
						</div>

						<?php
						foreach ($this->worktimeDate as $k => $wd_list)
						{
							?>
							<div class="vap-card-fieldset up-to-2" id="spday-fieldset-<?php echo $k; ?>" data-date="<?php echo $wd_list[0]->tsdate; ?>">

								<?php
								$displayData = array();

								// reduce card size
								$displayData['class'] = 'compress';

								// fetch primary text
								$displayData['primary'] = $wd_list[0]->date;
								
								$badge  = '';
								$closed = false;

								foreach ($wd_list as $wd)
								{
									// create readable lables
									$from = JHtml::fetch('vikappointments.min2time', $wd->from);
									$to   = JHtml::fetch('vikappointments.min2time', $wd->to);

									// generate badge
									$badge .= "<span class=\"badge badge-info\">{$from} - {$to}</span>\n";

									// check whether the working time was a closure
									$closed = $closed || $wd->closed;
								}

								if ($closed)
								{
									// overwrite badge with closure
									$badge = "<span class=\"badge badge-important\">" . JText::translate('VAPMANAGEEMPLOYEE22') . "</span>\n";
								}

								// fetch secondary text
								$displayData['secondary'] = $badge;

								// fetch edit button
								$displayData['edit'] = 'vapOpenSpecialDayCard(\'' . $k . '\');';

								// render layout
								echo $wdLayout->render($displayData);
								?>
								
								<input type="hidden" name="wd_json[]" value="<?php echo $this->escape(json_encode($wd_list)); ?>" />

							</div>
							<?php
						}
						?>

						<!-- ADD PLACEHOLDER -->

						<div class="vap-card-fieldset up-to-2 add add-spday">
							<div class="vap-card compress">
								<i class="fas fa-plus"></i>
							</div>
						</div>

					</div>

				<?php echo $vik->closeFieldset(); ?>
			</div>
		</div>

		<!-- OPTIONS -->

		<div class="row-fluid" style="margin-top: 20px;">
			<div class="span12">
				<?php echo $vik->openFieldset(JText::translate('VAPPREFERENCES')); ?>

					<!-- HIDE PAST - Checkbox -->

					<?php
					$yes = $vik->initRadioElement('', '',  $this->wdOptions['hide_past_wd'], 'onclick="vapTogglePastWD(1);"');
					$no  = $vik->initRadioElement('', '', !$this->wdOptions['hide_past_wd'], 'onclick="vapTogglePastWD(0);"');

					$help = $vik->createPopover(array(
						'title'   => JText::translate('VAPMANAGEEMPLOYEE31'),
						'content' => JText::translate('VAPMANAGEEMPLOYEE31_DESC'),
					));

					echo $vik->openControl(JText::translate('VAPMANAGEEMPLOYEE31') . $help);
					echo $vik->radioYesNo('hide_past_wd', $yes, $no, false);
					echo $vik->closeControl();
					?>

					<!-- IMPORT - Button -->

					<div>
						<button type="button" class="btn" onclick="vapOpenJModal('wdimport', null, true)"><?php echo JText::translate('VAPIMPORT'); ?></button>
					</div>

				<?php echo $vik->closeFieldset(); ?>
			</div>
		</div>

	</div>

</div>

<div style="display:none;" id="spday-struct">
			
	<?php
	// create structure for new special days
	$displayData = array();
	$displayData['class']     = 'compress';
	$displayData['primary']   = '';
	$displayData['secondary'] = '';
	$displayData['edit']      = true;

	echo $wdLayout->render($displayData);
	?>

</div>

<?php
$footer  = '<button type="button" class="btn btn-success" data-role="save">' . JText::translate('JAPPLY') . '</button>';
$footer .= '<button type="button" class="btn btn-danger" data-role="delete" style="float:right;">' . JText::translate('VAPDELETE') . '</button>';

// render inspector to manage working days management
echo JHtml::fetch(
	'vaphtml.inspector.render',
	'wd-inspector',
	array(
		'title'       => JText::translate('VAPMANAGEEMPLOYEE13'),
		'closeButton' => true,
		'keyboard'    => true,
		'footer'      => $footer,
		'width'       => 400,
	),
	$this->loadTemplate('workdays_modal')
);

JText::script('VAPMANAGEEMPLOYEE22');
JText::script('JGLOBAL_NO_MATCHING_RESULTS');
?>

<script>
	var SELECTED_OPTION = null;
	
	jQuery(function($) {
		// open inspector for new special day
		$('.vap-card-fieldset.add-spday').on('click', () => {
			vapOpenSpecialDayCard();
		});

		// fill the form before showing the inspector
		$('#wd-inspector').on('inspector.show', () => {
			var fieldset = $('#' + SELECTED_OPTION);
			var json = [];

			// fetch JSON data
			if (fieldset.length) {
				json = fieldset.find('input[name="wd_json[]"]').val();

				try {
					json = JSON.parse(json);
				} catch (err) {
					json = [];
				}

				if (json.length == 0) {
					// create default object
					json.push({
						closed: false,
						day: parseInt(fieldset.data('day')),
					});
				}
			}

			var subtitle = fieldset.vapcard('primary');

			// update form heading
			$('#inspector-wd-form h3').first().html(subtitle ? subtitle : '');

			if (json.length == 0 || json[0].id === undefined) {
				// creating a working day, hide delete button
				$('#wd-inspector [data-role="delete"]').hide();
			} else {
				// editing a working day, show delete button
				$('#wd-inspector [data-role="delete"]').show();
			}

			fillWorkingDayForm(json);
		});

		// apply the changes
		$('#wd-inspector').on('inspector.save', function() {
			// validate form
			if (!wdValidator.validate()) {
				return false;
			}

			// get saved working days
			var data = getWorkingDayData();
			// get deleted working days
			var deleted = getDeletedWorkingDays();

			var fieldset = $('#' + SELECTED_OPTION);

			if (data.length && data[0].ymd && fieldset.length == 0) {
				// field not found, lets look whether already exists
				// a record with the same specified date
				fieldset = $('#spday-fieldset-' + data[0].ymd);

				if (fieldset.length == 0) {
					// create new fieldset
					fieldset = vapAddSpecialDayCard(data);
				} else {
					// fieldset found, merge existing working days with the new ones
					let json;

					try {
						json = JSON.parse(fieldset.find('input[name="wd_json[]"]').val());
					} catch (err) {
						json = [];
					}

					// merge only in case the existing working days didn't
					// register a closure, otherwise overwrite them
					if (json.length && !parseInt(json[0].closed)) {
						if (data[0].closed) {
							// we created a closure, just update the existing working days
							json.forEach((wd, index) => {
								json[index].closed = true;
							});

							// do not use new working days
							data = json;
						} else {
							// merge working days
							data = json.concat(data);
						}
					} else {
						// auto-delete the days to overwrite
						json.forEach((wd) => {
							if (wd.id) {
								deleted.push(wd.id);
							}
						});
					}
				}
			}

			if (fieldset.length == 0) {
				// The user is probably trying to create a special day
				// without defining at least a working time...
				// This will act as an additional check and the
				// inspector won't be closed.
				return false;
			}

			// save JSON data
			fieldset.find('input[name="wd_json[]"]').val(JSON.stringify(data));

			// register working days to delete
			deleted.forEach((id) => {
				$('#adminForm').append('<input type="hidden" name="wd_deleted[]" value="' + id + '" />');
			});

			if (inspectorMode == 'spday' && data.length == 0) {
				// auto delete record as there are no more working days
				fieldset.remove();

				if (jQuery('#cards-workdays-special .vap-card-fieldset').not('.add').length < 3) {
					// auto-hide the add placeholder at the beginning of the list
					jQuery('.vap-card-fieldset.add').first().hide();
				}
			} else {
				// refresh card details
				vapRefreshWorkingDayCard(fieldset, data);
			}

			// auto-close on save
			$(this).inspector('dismiss');
		});

		// delete the record
		$('#wd-inspector').on('inspector.delete', function() {
			var fieldset = $('#' + SELECTED_OPTION);

			if (fieldset.length == 0) {
				// record not found
				return false;
			}

			// get existing working days
			var json = fieldset.find('input[name="wd_json[]"]').val();

			try {
				json = JSON.parse(json);
			} catch (err) {
				json = [];
			}

			if (json.length == 0) {
				// no days to delete
				return false;
			}

			// register working days to delete
			json.forEach((wd) => {
				if (wd.id) {
					$('#adminForm').append('<input type="hidden" name="wd_deleted[]" value="' + wd.id + '" />');
				}
			});

			// reset working days
			fieldset.find('input[name="wd_json[]"]').val('[]');

			if (inspectorMode == 'spday') {
				// auto delete record
				fieldset.remove();

				if (jQuery('#cards-workdays-special .vap-card-fieldset').not('.add').length < 3) {
					// auto-hide the add placeholder at the beginning of the list
					jQuery('.vap-card-fieldset.add').first().hide();
				}
			} else {
				// refresh card details
				vapRefreshWorkingDayCard(fieldset, []);
			}

			// auto-close on delete
			$(this).inspector('dismiss');
		});
	});

	function vapOpenWeekDayCard(day) {
		SELECTED_OPTION = 'weekday-fieldset-' + day;

		// prepare inspector for week day
		setWorkingDayInspectorMode('weekday');

		// open inspector
		vapOpenInspector('wd-inspector');
	}

	function vapOpenSpecialDayCard(date) {
		if (date) {
			SELECTED_OPTION = 'spday-fieldset-' + date;
		} else {
			SELECTED_OPTION = null;
		}

		// prepare inspector for special day
		setWorkingDayInspectorMode('spday');

		// open inspector
		vapOpenInspector('wd-inspector');
	}

	function vapAddSpecialDayCard(data) {
		let index = data[0].ymd;

		SELECTED_OPTION = 'spday-fieldset-' + index;

		var html = jQuery('#spday-struct').clone().html();

		html = html.replace(/{id}/, index);

		jQuery(
			'<div class="vap-card-fieldset up-to-2" id="spday-fieldset-' + index + '">' + html + '</div>'
		).insertBefore(jQuery('.vap-card-fieldset.add-spday').last());

		// get created fieldset
		let fieldset = jQuery('#spday-fieldset-' + index);

		fieldset.vapcard('edit', 'vapOpenSpecialDayCard(' + index + ')');

		// create input to hold JSON data
		let input = jQuery('<input type="hidden" name="wd_json[]" />').val(JSON.stringify(data));

		// append input to fieldset
		fieldset.append(input);

		if (jQuery('#cards-workdays-special .vap-card-fieldset').not('.add').length >= 3) {
			// auto-show the add placeholder at the beginning of the list
			jQuery('.vap-card-fieldset.add').first().show();
		}

		return fieldset;
	}

	function vapRefreshWorkingDayCard(elem, data) {
		if (data.length && data[0].date) {
			// update primary text
			elem.vapcard('primary', data[0].date);
		}

		// fetch secondary text
		var secondary = '';

		if (data.length) {
			var closed = false;

			data.forEach((wd) => {
				// extract from time
				let fh = Math.floor(wd.from / 60);
				let fm = wd.from % 60;

				// extract to time
				let th = Math.floor(wd.to / 60);
				let tm = wd.to % 60;

				// format times
				let fhm = getFormattedTime(fh, fm, '<?php echo $config->get('timeformat'); ?>');
				let thm = getFormattedTime(th, tm, '<?php echo $config->get('timeformat'); ?>');

				// append time as badge
				secondary += '<span class="badge badge-info">' + fhm + ' - ' + thm + '</span>\n';

				// check whether the working time is a closure
				closed = closed || parseInt(wd.closed);
			});

			if (closed) {
				// overwrite badge with closure
				secondary = '<span class="badge badge-important">' + Joomla.JText._('VAPMANAGEEMPLOYEE22') + '</span>\n';
			}
		} else {
			// no working times
			secondary = '<span class="badge badge-warning">' + Joomla.JText._('JGLOBAL_NO_MATCHING_RESULTS') + '</span>\n';
		}

		// update secondary text
		elem.vapcard('secondary', secondary);
	}

	function vapTogglePastWD(is) {
		// keep setting active for 1 year
		var upto = new Date();
		upto.setYear(upto.getFullYear() + 1);

		document.cookie = 'vikappointments.hide.past.wd=' + (is ? 1 : 0) + '; expires=' + upto.toUTCString() + '; path=/';
	}

</script>
