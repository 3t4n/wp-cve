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

<div class="order-appointments-table" id="order-appointments-table">

	<?php echo $this->loadTemplate('details_appointments_table'); ?>

</div>

<script>
	var SELECTED_OPTION = null;
	var OPTIONS_COUNT   = <?php echo count($this->reservation->options); ?>;
	var TMP_COSTS       = {};

	jQuery(function($) {
		// fill the form before showing the inspector
		$('#order-app-inspector').on('inspector.show', () => {
			initAppointmentData();

			// save current costs of the service
			TMP_COSTS = {
				net:   parseFloat($('input[name="service_net"]').val()),
				tax:   parseFloat($('input[name="service_tax"]').val()),
				gross: parseFloat($('input[name="service_gross"]').val()),
			};
		});

		// apply the changes
		$('#order-app-inspector').on('inspector.save', function() {
			// disable button
			$('#order-app-inspector button[data-role="save"]').prop('disabled', true);

			// get saved record
			commitAppointmentChanges().then((data) => {
				// refresh appointment row
				vapRefreshAppointmentRow($('#' + SELECTED_OPTION), data);

				// refresh total prices
				vapRefreshTotals(data);

				// auto-close on save
				$(this).inspector('dismiss');
			}).catch((err) => {
				// the callback performs an AJAX request to retrieve
				// all the costs of the saved service and, since it
				// may fail, we need to catch the exception thrown
				// and display the error message
				alert(err);
			}).finally(() => {
				// re-enable button
				$('#order-app-inspector button[data-role="save"]').prop('disabled', false);
			});
		});

		// open inspector for new options
		$('#add-app-option').on('click', () => {
			vapOpenAppOptionCard();
		});

		// fill the form before showing the inspector
		$('#order-app-opt-inspector').on('inspector.show', () => {
			var json = [];

			// fetch JSON data
			if (SELECTED_OPTION) {
				var fieldset = $('#' + SELECTED_OPTION);

				json = fieldset.find('input[name="option_json[]"]').val();

				try {
					json = JSON.parse(json);
				} catch (err) {
					json = {};
				}
			}

			// save current costs of the service
			TMP_COSTS = {
				net:   json.net   ? parseFloat(json.net)   : 0,
				tax:   json.tax   ? parseFloat(json.tax)   : 0,
				gross: json.gross ? parseFloat(json.gross) : 0,
			};

			if (json.id === undefined) {
				// creating new record, hide delete button
				$(this).find('[data-role="delete"]').hide();
			} else {
				// editing existing record, show delete button
				$(this).find('[data-role="delete"]').show();
			}

			fillAppOptionForm(json);
		});

		// apply the changes
		$('#order-app-opt-inspector').on('inspector.save', function() {
			// validate form
			if (!appOptValidator.validate()) {
				return false;
			}

			// disable button
			$('#order-app-opt-inspector button[data-role="save"]').prop('disabled', true);

			// get saved record
			getAppOptionData().then((data) => {
				var fieldset;

				if (SELECTED_OPTION) {
					fieldset = $('#' + SELECTED_OPTION);
				} else {
					fieldset = vapAddAppOptionCard(data);
				}

				if (fieldset.length == 0) {
					// an error occurred, abort
					return false;
				}

				// save JSON data
				fieldset.find('input[name="option_json[]"]').val(JSON.stringify(data));

				// refresh card details
				vapRefreshAppOptionCard(fieldset, data);

				// refresh total prices
				vapRefreshTotals(data);

				// auto-close on save
				$(this).inspector('dismiss');
			}).catch((err) => {
				// the callback performs an AJAX request to retrieve
				// all the costs of the option to save/add and, since
				// it may fail, we need to catch the exception thrown
				// and display the error message
				alert(err);
			}).finally(() => {
				// re-enable button
				$('#order-app-opt-inspector button[data-role="save"]').prop('disabled', false);
			});
		});

		// delete the record
		$('#order-app-opt-inspector').on('inspector.delete', function() {
			var fieldset = $('#' + SELECTED_OPTION);

			if (fieldset.length == 0) {
				// record not found
				return false;
			}

			// get existing record
			var json = fieldset.find('input[name="option_json[]"]').val();

			try {
				json = JSON.parse(json);
			} catch (err) {
				json = {};
			}

			if (json.id) {
				// commit record delete
				$('#adminForm').append('<input type="hidden" name="option_deleted[]" value="' + json.id + '" />');
			}

			// save current costs of the option
			TMP_COSTS = {
				net:   json.net   ? parseFloat(json.net)   : 0,
				tax:   json.tax   ? parseFloat(json.tax)   : 0,
				gross: json.gross ? parseFloat(json.gross) : 0,
			};

			// refresh total prices
			vapRefreshTotals({net: 0, tax: 0, gross: 0});

			// auto delete fieldset
			fieldset.remove();

			// auto-close on delete
			$(this).inspector('dismiss');
		});
	});

	function vapOpenAppointmentCard(index) {
		SELECTED_OPTION = 'order-app-fieldset-' + index;

		// open inspector
		vapOpenInspector('order-app-inspector');
	}

	function vapOpenAppOptionCard(index) {
		if (typeof index !== 'undefined') {
			SELECTED_OPTION = 'order-app-opt-fieldset-' + index;
		} else {
			SELECTED_OPTION = null;
		}	

		// open inspector
		vapOpenInspector('order-app-opt-inspector');
	}

	function vapRefreshTotals(data) {
		// get totals
		let totals = getInputTotals();

		// get currency helper
		currency = Currency.getInstance();

		// add new total net
		totals.total_net = currency.sum(totals.total_net, data.net);
		// subtract previous one
		totals.total_net = currency.diff(totals.total_net, TMP_COSTS.net);

		// add new total tax
		totals.total_tax = currency.sum(totals.total_tax, data.tax);
		// subtract previous one
		totals.total_tax = currency.diff(totals.total_tax, TMP_COSTS.tax);

		// add new total gross
		totals.total_cost = currency.sum(totals.total_cost, data.gross);
		// subtract previous one
		totals.total_cost = currency.diff(totals.total_cost, TMP_COSTS.gross);

		// commit changes
		updateInputTotals(totals);
	}

</script>
