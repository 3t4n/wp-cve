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

JHtml::fetch('vaphtml.assets.fontawesome');
JHtml::fetch('vaphtml.assets.select2');

$vik = VAPApplication::getInstance();

$recurrence_params = VikAppointments::getRecurrenceParams();

$title = JText::sprintf(
	'VAPSAYRESERVATIONDETAILS',
	$this->order->service->name,
	$this->order->employee->name,
	$this->order->checkin->lc
);

?>

<form action="index.php" name="adminForm" id="adminForm">

	<?php echo $vik->openCard(); ?>

		<div class="span12">
			<?php echo $vik->openFieldset($title); ?>

				<div class="vap-makerec-box">
					<div class="vap-recurrence-form">
						<span class="lbl"><?php echo JText::translate('VAPMANAGECONFIGREC2'); ?></span>
						
						<span>
							<?php
							$options = array(
								JHtml::fetch('select.option', 1, JText::translate('VAPMANAGECONFIGRECSINGOPT1')),
								JHtml::fetch('select.option', 2, JText::translate('VAPMANAGECONFIGRECSINGOPT2')),
								JHtml::fetch('select.option', 4, JText::translate('VAPMANAGECONFIGRECSINGOPT4')),
								JHtml::fetch('select.option', 3, JText::translate('VAPMANAGECONFIGRECSINGOPT3')),
								JHtml::fetch('select.option', 5, JText::translate('VAPMANAGECONFIGRECSINGOPT5')),
							);
							?>
							<select id="vaprepeatbyrecsel">
								<?php echo JHtml::fetch('select.options', $options); ?>
							</select>
						</span>

						&nbsp;&nbsp;
						
						<span class="lbl"><?php echo JText::translate('VAPMANAGECONFIGREC5'); ?></span>
						
						<span>
							<?php
							$options = array();
							for ($i = $recurrence_params['min']; $i <= $recurrence_params['max']; $i++)
							{
								$options[] = JHtml::fetch('select.option', $i, $i);
							}
							?>
							<select id="vapamountrecsel">
								<?php echo JHtml::fetch('select.options', $options); ?>
							</select>
						</span>
						
						<span>
							<?php
							$options = array();
							for ($i = 0; $i < count($recurrence_params['for']); $i++)
							{
								$options[] = JHtml::fetch('select.option', $i + 1, JText::translate('VAPMANAGECONFIGRECMULTOPT' . ($i + 1)));
							}
							?>
							<select id="vapfornextrecsel">
								<?php echo JHtml::fetch('select.options', $options); ?>
							</select>
						</span>
					</div>

					<div class="vap-recurrence-button">
						<button type="button" class="btn" id="launch-recurrence-btn">
							<?php echo JText::translate('VAPMAKERECGETPREVIEW'); ?>
						</button>
					</div>

				</div>

				<div class="vap-recpreview-box" id="vap-recpreview-box" style="display: none;">
					<div class="vap-recpreview-container" id="vap-recpreview-container">

					</div>
					<div class="vap-recpreview-button">
						<button type="button" class="btn" id="vap-mkrec-btn">
							<?php echo JText::translate('VAPMAKERECLAUNCHPROC'); ?>
						</button>
					</div>
				</div>

				<div class="vap-confirmrec-box" id="vap-confirmrec-box" style="display: none;">

				</div>

				<div class="vap-recerror-box" id="vap-recerror-box" style="display: none;">

				</div>

			<?php echo $vik->closeFieldset(); ?>
		</div>

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>

	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->order->id; ?>" />
</form>

<?php
JText::script('VAPCONNECTIONLOSTERROR');
JText::script('VAPMAKERECEMPHINT');
JText::script('VAPMAKERECTIMEHINT');
JText::script('VAPFILTERSELECTEMPLOYEE');
JText::script('VAPFILTERSELECTTIME');
?>

<script>

	jQuery(function($) {
		$('#vaprepeatbyrecsel, #vapfornextrecsel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 120,
		});

		$('#vapamountrecsel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 75,
		});

		$('#vaprepeatbyrecsel').on('change', function() {
			var repeatby = parseInt($(this).val());

			if (repeatby == 4) {
				// use week for "fortnight"
				repeatby = 2;
			} else if (repeatby == 5) {
				// use month for "bi-month"
				repeatby = 3;
			}

			// make sure the "for next" dropdown supports the value set
			// within the "repeat by" select
			if ($('#vapfornextrecsel option[value="' + repeatby + '"]').length) {
				$('#vapfornextrecsel').select2('val', repeatby);
			}
		});

		$('#launch-recurrence-btn').on('click', function() {
			// disable buttons to avoid double requests
			$(this).prop('disabled', true);
			$('#vap-mkrec-btn').prop('disabled', true);

			$('#vap-recpreview-box').hide();
			$('#vap-recpreview-container').html('');
			$('#vap-recerror-box').hide();
			$('#vap-confirmrec-box').hide();

			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=makerecurrence.preview'); ?>',
				{
					id:     <?php echo (int) $this->order->id; ?>,
					by:     $('#vaprepeatbyrecsel').val(),
					amount: $('#vapamountrecsel').val(),
					for:    $('#vapfornextrecsel').val(),
				},
				(resp) => {
					let at_least_one = false;
					
					resp.forEach((v) => {
						const box = $('<div class="recurrence-date"></div>');

						// append date format
						box.append($('<span class="format"></span>').html(v.format));
						
						const msg = $('<span class="msg"></span>')
							.html(v.message)
							.addClass((v.available ? 'available' : 'occupied'));

						if (!v.available && v.reason) {
							msg.append(
								$('<i class="fas fa-exclamation-circle"></i>')
									.attr('title', v.reason)
									.tooltip()
							);
						}

						// append availability message
						box.append(msg);

						if (!v.available && v.employees) {
							// the appointment is available with a different employee
							const empHint = $('<div class="hint-box"></div>').append(
								$('<span class="hint-text"></span>').html(Joomla.JText._('VAPMAKERECEMPHINT'))
							);

							// create employee selection dropdown
							const empSelect = $('<select name="hint[' + v.date + ']"></select>')
								.append('<option></option>');

							for (let k in v.employees) {
								if (v.employees.hasOwnProperty(k)) {
									empSelect.append(
										$('<option></option>').val(k).html(v.employees[k])
									);
								}
							}

							empHint.prepend(empSelect);

							box.append(empHint);

							empSelect.select2({
								placeholder: Joomla.JText._('VAPFILTERSELECTEMPLOYEE'),
								allowClear: true,
								width: 190,
							});
						}

						if (!v.available && v.times) {
							// the appointment is available at a different time
							const timeHint = $('<div class="hint-box"></div>').append(
								$('<span class="hint-text"></span>').html(Joomla.JText._('VAPMAKERECTIMEHINT'))
							);

							// create time selection dropdown
							const timeSelect = $('<select name="hint[' + v.date + ']"></select>')
								.append('<option></option>');

							for (let k in v.times) {
								if (v.times.hasOwnProperty(k)) {
									timeSelect.append(
										$('<option></option>').val(k).html(v.times[k])
									);
								}
							}

							timeHint.prepend(timeSelect);

							box.append(timeHint);

							timeSelect.select2({
								placeholder: Joomla.JText._('VAPFILTERSELECTTIME'),
								allowClear: true,
								width: 190,
							});
						}

						$('#vap-recpreview-container').append(box);

						at_least_one = at_least_one || v.available;
					});

					$(this).prop('disabled', false);
					$('#vap-mkrec-btn').prop('disabled', !at_least_one);

					$('#vap-recpreview-box').slideDown();
				},
				(error) => {
					$('#vap-recerror-box').html(error.responseText || Joomla.JText._('VAPCONNECTIONLOSTERROR'));
					$('#vap-recerror-box').slideDown();

					$(this).prop('disabled', false);
				}
			);
		});

		$('#vap-mkrec-btn').on('click', function() {
			// disable button to avoid double requests
			$(this).prop('disabled', true);

			$('#vap-confirmrec-box').hide();
			$('#vap-recerror-box').hide();

			let hints = {};

			// iterate all non-disabled hint dropdown
			$('select[name^="hint["]').not('[disabled]').each(function() {
				// get selected hint
				let val = $(this).val();
				// get related date
				let key = $(this).attr('name').match(/^hint\[([0-9:\-\s]+)\]$/);
				
				if (val && key) {
					// register hint
					hints[key.pop()] = val;
				}
			});

			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=makerecurrence.create'); ?>',
				{
					id:     <?php echo (int) $this->order->id; ?>,
					by:     $('#vaprepeatbyrecsel').val(),
					amount: $('#vapamountrecsel').val(),
					for:    $('#vapfornextrecsel').val(),
					hints:  hints,
				},
				(resp) => {
					$('#vap-confirmrec-box').html(resp.message);
					$('#vap-confirmrec-box').slideDown();

					$(this).prop('disabled', false);
				},
				(error) => {
					$('#vap-recerror-box').html(error.responseText || Joomla.JText._('VAPCONNECTIONLOSTERROR'));
					$('#vap-recerror-box').slideDown();

					$(this).prop('disabled', false);
				}
			);
		});

		$(document).on('change', '[name^="hint["]', function() {
			// get any other select matching the same name of the changed one
			let other = $('select[name="' + $(this).attr('name') + '"]').not(this);
			// unset value from other select and disabled them
			other.select2('val', null);
			other.prop('disabled', $(this).val());

			// enable button used to creare the recurrence
			$('#vap-mkrec-btn').prop('disabled', false);
		});
	});

</script>
