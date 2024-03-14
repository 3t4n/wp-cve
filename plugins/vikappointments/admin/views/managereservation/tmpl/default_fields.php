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

$has_repeat_fields = VAPCustomFieldsRenderer::hasRepeatableFields($this->customFields);

// check whether we should use a fieldset title for the main custom fields box
$need_title = isset($this->forms['fields']) || ($this->reservation->people > 1 && $has_repeat_fields);

?>

<div class="row-fluid">

	<!-- LEFT SIDE -->

	<div class="span6 full-width">

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset($need_title ? JText::translate('VAPMANAGECUSTOMERTITLE2') : '');
				
				/**
				 * Render the custom fields form by using the apposite helper.
				 *
				 * Looking for a way to override the custom fields? Take a look
				 * at "/layouts/form/fields/" folder, which should contain all
				 * the supported types of custom fields.
				 *
				 * @since 1.7
				 */
				echo VAPCustomFieldsRenderer::display($this->customFields, $this->reservation->custom_f, $strict = false);
					
				echo $vik->closeFieldset();
				?>
			</div>
		</div>

	</div>

	<!-- RIGHT SIDE -->

	<div class="span6 full-width">

		<?php
		if ($has_repeat_fields)
		{
			/**
			 * Display custom fields also for the other participants.
			 * 
			 * @since 1.7
			 */
			for ($i = 0; $i < $this->reservation->people - 1; $i++)
			{
				if (isset($this->reservation->attendees[$i]))
				{
					$attendee = $this->reservation->attendees[$i];

					// fetch attendee data
					$attendeeData = isset($attendee['fields']) ? $attendee['fields'] : array();

					if (isset($attendee['uploads']))
					{
						// inject uploaded files within custom fields
						$attendeeData = array_merge($attendeeData, (array) $attendee['uploads']);
					}
				}
				else
				{
					// empty details
					$attendee     = null; 
					$attendeeData = array();
				}
				?>
				<div class="row-fluid">
					<div class="span12">
						<?php
						echo $vik->openFieldset(JText::sprintf('VAP_N_ATTENDEE', $i + 2));
						
						/**
						 * Tries to auto-populate the fields with the details assigned to the current attendee.
						 *
						 * The fourth boolean flag is set to instruct the method that the customers are usual to
						 * enter the first name before the last name. Use false to auto-populate the fields in
						 * the opposite way.
						 */
						VAPCustomFieldsRenderer::autoPopulate($attendeeData, $this->customFields, $attendee, $firstNameComesFirst = true);
						
						// render the custom fields form by using the apposite helper
						echo VAPCustomFieldsRenderer::displayAttendee($i + 1, $this->customFields, $attendeeData, $strict = false);

						echo $vik->closeFieldset();
						?>
					</div>
				</div>
				<?php
			}
		}

		/**
		 * Look for any additional fields to be pushed within
		 * the "Custom Fields" fieldset (right-side).
		 *
		 * NOTE: retrieved from "onDisplayViewReservation" hook.
		 *
		 * @since 1.7
		 */
		if (isset($this->forms['fields']))
		{
			?>
			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openEmptyFieldset();
					echo $this->forms['fields'];
					echo $vik->closeEmptyFieldset();
					?>
				</div>
			</div>
			<?php

			// unset details form to avoid displaying it twice
			unset($this->forms['fields']);
		}
		?>

	</div>

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayViewReservation","type":"field","key":"fields"} -->

</div>

<?php
// create name-id custom fields lookup
$lookup = array();

foreach ($this->customFields as $field)
{
	$lookup[$field['name']] = $field['id'];
}

JText::script('JGLOBAL_SELECT_AN_OPTION');
?>

<script>

	const CUSTOM_FIELDS_LOOKUP = <?php echo json_encode($lookup); ?>;

	jQuery(function($) {
		// render select
		$('select.custom-field').each(function() {
			// check whether the first option is a placeholder
			let hasPlaceholder = $(this).find('option').first().text().length == 0;

			$(this).select2({
				// check whether we should specify a placeholder
				placeholder: hasPlaceholder ? Joomla.JText._('JGLOBAL_SELECT_AN_OPTION') : '',
				// disable search for select with 3 or lower options
				minimumResultsForSearch: $(this).find('option').length > 3 ? 0 : -1,
				// check whether the field supports empty values
				allowClear: !$(this).hasClass('required') && hasPlaceholder ? true : false,
				width: '90%',
			});
		});
	});

	function compileCustomFields(fields) {
		jQuery.each(fields, function(name, value) {
			if (!CUSTOM_FIELDS_LOOKUP.hasOwnProperty(name)) {
				// field not found, next one
				return true;
			}

			const input = jQuery('*[name="vapcf' + CUSTOM_FIELDS_LOOKUP[name] + '"]');

			if (input.length) {
				if (input.is('select')) {
					if (input.find('option[value="' + value + '"]').length) {
						// refresh select value if the option exists
						input.select2('val', value);
					} else {
						// otherwise select the first option
						input.select2('val', input.find('option').first().val());
					}
				} else if (input.is(':checkbox')) {
					// check/uncheck the input
					input.prop('checked', value ? true : false);
				} else if (input.hasClass('phone-field')) {
					// update phone number
					input.intlTelInput('setNumber', value);
				} else {
					// otherwise refresh as default input
					try {
						input.val(value);

						if (input.data('alt-value') !== undefined) {
							// we are probably updating a calendar,
							// make sure to update also the alt value
							input.attr('data-alt-value', value);
						}
					} catch (error) {
						// catch error because input file might raise
						// an error while trying to set a value
					}
				}
			}
		});
	}

</script>
