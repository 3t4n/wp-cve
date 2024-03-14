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

JHtml::fetch('bootstrap.tooltip', '.hasTooltip');

$vik = VAPApplication::getInstance();

// get user custom fields
if ($this->user)
{
	$fields = $this->user->fields;
}
else
{
	$fields = [];
}

/**
 * Checks whether there's at least an editable custom field.
 * Otherwise unset the array to avoid display the custom fields form.
 * 
 * @since 1.7.2
 */
if (VAPCustomFieldsRenderer::hasEditableCustomFields($this->customFields, $fields) === false)
{
	// display hidden fields and immediately return
	echo VAPCustomFieldsRenderer::display($this->customFields, $fields);
	return;
}

?>

<div class="vapcompleteorderdiv">

	<!-- TITLE -->

	<h3 class="vap-confirmapp-h3"><?php echo JText::translate('VAPCOMPLETEORDERHEADTITLE'); ?></h3>
			
	<!-- ERROR BOX -->

	<div id="vapordererrordiv" class="vapordererrordiv" style="display: none;">&nbsp;</div>

	<!-- FIELDS -->

	<div class="vapcustomfields">
		<?php
		/**
		 * Tries to auto-populate the fields with the details assigned
		 * to the currently logged-in user.
		 *
		 * The third boolean flag is set to instruct the method that the
		 * customers are usual to enter the first name before the last name.
		 * Use false to auto-populate the fields in the opposite way.
		 *
		 * @since 1.7
		 */
		VikAppointments::populateFields($this->customFields, $fields, $firstNameComesFirst = true);
		
		/**
		 * Render the custom fields form by using the apposite helper.
		 *
		 * Looking for a way to override the custom fields? Take a look
		 * at "/layouts/form/fields/" folder, which should contain all
		 * the supported types of custom fields.
		 *
		 * @since 1.7
		 */
		echo VAPCustomFieldsRenderer::display($this->customFields, $fields);

		/**
		 * Trigger event to retrieve an optional field that could be used
		 * to confirm the subscription to a mailing list.
		 *
		 * @param 	object 	$user  The user details.
		 *
		 * @return  string  The HTML to display.
		 *
		 * @since   1.6.3
		 */
		$html = VAPFactory::getEventDispatcher()->triggerOnce('onDisplayMailingSubscriptionInput', array($this->user));
		
		// display field if provided
		if ($html)
		{
			?>
			<div>
				<div class="cf-value"><?php echo $html; ?></div>
			</div>
			<?php
		}

		/**
		 * Only in case of guest users, try to display the 
		 * ReCAPTCHA validation form.
		 *
		 * @since 1.7
		 */
		$is_captcha = !$this->user && $vik->isGlobalCaptcha();

		if ($is_captcha)
		{
			?>
			<div>
				<div class="cf-value"><?php echo $vik->reCaptcha(); ?></div>
			</div>
			<?php
		}
		?>
	</div>

</div>

<script>

	/**
	 * Flag used to check whether the ZIP of the user has
	 * been proprly validated. The flag is automatically
	 * validated in case there is no ZIP field.
	 *
	 * @var boolean
	 */
	var ZIP_VALIDATED = <?php echo $this->zipFieldID ? 'false' : 'true'; ?>;

	jQuery(function($) {
		// render dropdown elements with Select2 jQuery plugin
		$('.vapcustomfields .cf-value select').each(function() {
			let option = $(this).find('option').first();

			let data = {
				// hide search bar in case the number of options is lower than 10
				minimumResultsForSearch: $(this).find('option').length >= 10 ? 1 : -1,
				// allow clear selection in case the value of the first option is empty
				allowClear: option.val() ? false : true,
				// take the whole space
				width: '100%',
			};

			if (data.allowClear && !$(this).prop('multiple')) {
				// set placeholder by using the option text
				data.placeholder = option.text();

				// unset the text from the option for a correct rendering
				option.text('');
			}

			$(this).select2(data);
		});

		onInstanceReady(() => {
			return vapCustomFieldsValidator;
		}).then((form) => {
			/**
			 * Overwrite getLabel method to properly access the
			 * label by using our custom layout.
			 *
			 * @param 	mixed  input  The input element.
			 *
			 * @param 	mixed  The label of the input.
			 */
			form.getLabel = (input) => {
				return $(input).closest('.cf-control').find('.cf-label *[id^="vapcf"]');
			}
		});

		if (!ZIP_VALIDATED) {
			// auto-trigger change on load to automatically validate the existing ZIP code, if any
			$('#vapcf<?php echo $this->zipFieldID; ?>').on('change', function() { 
				// get selected ZIP code
				let zip = $(this).val();

				// reset ZIP status before validation
				ZIP_VALIDATED = false;

				if (!zip) {
					// missing ZIP code, there's no need to interrogate the system
					return false;
				}

				UIAjax.do(
					'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=confirmapp.checkzip' . ($this->itemid ? '&Itemid=' . $this->itemid : '')); ?>',
					{
						zip: zip,
					},
					(resp) => {
						// ZIP validated
						ZIP_VALIDATED = true;
					},
					(err) => {
						if (err.responseText) {
							alert(err.responseText);
							// clear field value on error
							$(this).val('');
						}
					}
				);
			}).trigger('change');

			onInstanceReady(() => {
				return vapCustomFieldsValidator;
			}).then((form) => {
				/**
				 * Add callback to validate the customer ZIP against the list
				 * of accepted ZIP codes.
				 *
				 * @return 	boolean  True if valid, false otherwise.
				 */
				form.addCallback(() => {
					const field = $('#vapcf<?php echo $this->zipFieldID; ?>');

					if (!ZIP_VALIDATED) {
						form.setInvalid(field);
						return false;
					}

					form.unsetInvalid(field);
					return true;
				});
			});
		}

		<?php
		if ($is_captcha)
		{
			?>
			onInstanceReady(() => {
				return vapCustomFieldsValidator;
			}).then((form) => {
				/**
				 * Add callback to validate whether the ReCAPTCHA quiz
				 * was completed or not.
				 *
				 * @return 	boolean  True if completed, false otherwise.
				 */
				form.addCallback(() => {
					// get recaptcha elements
					var captcha = $('#vappayform .g-recaptcha').first();
					var iframe  = captcha.find('iframe').first();

					// get widget ID
					var widget_id = captcha.data('recaptcha-widget-id');

					// check if recaptcha instance exists
					// and whether the recaptcha was completed
					if (typeof grecaptcha !== 'undefined'
						&& widget_id !== undefined
						&& !grecaptcha.getResponse(widget_id)) {
						// captcha not completed
						iframe.addClass('vapinvalid');
						return false;
					}

					// captcha completed
					iframe.removeClass('vapinvalid');
					return true;
				});
			});
			<?php
		}
		?>
	});

</script>
