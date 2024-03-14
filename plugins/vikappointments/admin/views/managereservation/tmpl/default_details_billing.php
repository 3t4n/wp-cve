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

JHtml::fetch('vaphtml.assets.intltel', '[name="purchaser_phone"]');

$reservation = $this->reservation;

$vik = VAPApplication::getInstance();

?>

<!-- USER - Dropdown -->

<?php echo $vik->openControl(JText::translate('VAPMANAGERESERVATION38')); ?>
	<div>
		<input type="hidden" name="id_user" id="vap-users-select" value="<?php echo $reservation->id_user > 0 ? $reservation->id_user : ''; ?>" />
	</div>

	<div class="manage-customer-actions" style="margin-top: 5px;">
		<button type="button" class="btn" id="add-customer-btn" style="<?php echo $reservation->id_user > 0 ? 'display:none;' : ''; ?>">
			<i class="fas fa-user-plus"></i>
			<?php echo JText::translate('VAP_ADD_CUSTOMER'); ?>
		</button>

		<button type="button" class="btn" id="edit-customer-btn" style="<?php echo $reservation->id_user > 0 ? '' : 'display:none;'; ?>">
			<i class="fas fa-user-edit"></i>
			<?php echo JText::translate('VAP_EDIT_CUSTOMER'); ?>
		</button>
	</div>
<?php echo $vik->closeControl(); ?>

<!-- NOMINATIVE - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGERESERVATION32')); ?>
	<input type="text" name="purchaser_nominative" value="<?php echo $this->escape($reservation->purchaser_nominative); ?>" size="40" />
<?php echo $vik->closeControl(); ?>

<!-- MAIL - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEPACKORDER7')); ?>
	<input type="email" name="purchaser_mail" value="<?php echo $this->escape($reservation->purchaser_mail); ?>" size="40" />
<?php echo $vik->closeControl(); ?>

<!-- PHONE - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGEPACKORDER8')); ?>
	<input type="tel" name="purchaser_phone" value="<?php echo $reservation->purchaser_phone; ?>" />

	<input type="hidden" name="purchaser_prefix" value="<?php echo $reservation->purchaser_prefix; ?>" />
	<input type="hidden" name="purchaser_country" value="<?php echo $reservation->purchaser_country; ?>" />
<?php echo $vik->closeControl(); ?>

<?php
JText::script('VAPMAINTITLENEWCUSTOMER');
JText::script('VAPMAINTITLEEDITCUSTOMER');
?>

<script>

	jQuery(function($) {
		const BILLING_USERS_POOL = {};

		$('#vap-users-select').select2({
			placeholder: '--',
			allowClear: true,
			width: '90%',
			minimumInputLength: 2,
			ajax: {
				url: '<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=customer.users'); ?>',
				dataType: 'json',
				type: 'POST',
				quietMillis: 50,
				data: function(term) {
					return {
						term: term
					};
				},
				results: function(data) {
					return {
						results: $.map(data, function (item) {
							if (!BILLING_USERS_POOL.hasOwnProperty(item.id))
							{
								BILLING_USERS_POOL[item.id] = item;
							}

							return {
								text: item.text || item.billing_name,
								id: item.id,
							};
						}),
					};
				},
			},
			initSelection: function(element, callback) {
				// the input tag has a value attribute preloaded that points to a preselected repository's id
				// this function resolves that id attribute to an object that select2 can render
				// using its formatResult renderer - that way the repository name is shown preselected
				if ($(element).val().length) {
					callback({text: '<?php echo (empty($reservation->purchaser_nominative) ? '' : addslashes($reservation->purchaser_nominative)); ?>'});
				}
			},
			formatSelection: function(data) {
				if ($.isEmptyObject(data.billing_name)) {
					// display data returned from ajax parsing
					return data.text;
				}
				// display pre-selected value
				return data.billing_name;
			},
		});

		$('#vap-users-select').on('change customer.refresh', function() {
			var id = $(this).val();

			if (!id) {
				$('#edit-customer-btn').hide();
				$('#add-customer-btn').show();
			} else {
				$('#add-customer-btn').hide();
				$('#edit-customer-btn').show();
			}
			
			if (BILLING_USERS_POOL[id].hasOwnProperty('billing_name')) {
				$('input[name="purchaser_nominative"]').val(BILLING_USERS_POOL[id].billing_name);
			}

			if (BILLING_USERS_POOL[id].hasOwnProperty('billing_mail')) {
				$('input[name="purchaser_mail"]').val(BILLING_USERS_POOL[id].billing_mail);
			}

			if (BILLING_USERS_POOL[id].hasOwnProperty('billing_phone')) {
				$('input[name="purchaser_phone"]').intlTelInput('setNumber', BILLING_USERS_POOL[id].billing_phone).trigger('change');
			}

			if (BILLING_USERS_POOL[id].hasOwnProperty('fields') && typeof CUSTOM_FIELDS_LOOKUP !== 'undefined') {
				compileCustomFields(BILLING_USERS_POOL[id].fields);
			}
		});

		// save "country code" and "dial code" every time the phone number changes
		$('input[name="purchaser_phone"]').on('change countrychange', function() {
			var country = $(this).intlTelInput('getSelectedCountryData');

			if (!country) {
				return false;
			}

			if (country.iso2) {
				$('input[name="purchaser_country"]').val(country.iso2.toUpperCase());
			}

			if (country.dialCode) {
				var dial = '+' + country.dialCode.toString().replace(/^\+/);

				if (country.areaCodes) {
					dial += ' ' + country.areaCodes[0];
				}

				$('input[name="purchaser_prefix"]').val(dial);
			}
		});

		$('#add-customer-btn').on('click', () => {
			$('#jmodal-managecustomer .customer-title').text(Joomla.JText._('VAPMAINTITLENEWCUSTOMER'));

			// add customer URL
			let url = 'index.php?option=com_vikappointments&tmpl=component&task=customer.add';

			// open modal
			vapOpenJModal('managecustomer', url, true);
		});

		$('#edit-customer-btn').on('click', () => {
			$('#jmodal-managecustomer .customer-title').text(Joomla.JText._('VAPMAINTITLEEDITCUSTOMER'));

			// add customer URL
			let url = 'index.php?option=com_vikappointments&tmpl=component&task=customer.edit&cid[]=' + $('#vap-users-select').val();

			// open modal
			vapOpenJModal('managecustomer', url, true);
		});

		$('button[data-role="customer.save"]').on('click', function() {
			// trigger click of save button contained in managecustomer view
			window.modalCustomerSaveButton.click();
		});

		$('#jmodal-managecustomer').on('hidden', function() {
			// restore default submit function, which might have been
			// replaced by the callback used in manage customer view
			Joomla.submitbutton = ManageReservationSubmitButtonCallback;
			
			// check if the customer was saved
			if (window.modalSavedCustomerData) {
				let data = window.modalSavedCustomerData;

				// register billing details (or update them if already exist)
				BILLING_USERS_POOL[data.id] = {
					billing_name:  data.billing_name,
					billing_mail:  data.billing_mail,
					billing_phone: data.billing_phone,
					country_code:  data.country_code,
					fields:        data.fields,
				};

				// insert/update customer
				$('#vap-users-select').select2('data', data).trigger('customer.refresh');
			}
		});
	});

</script>
