(function ($) {
	'use strict';

	/**
	 * This detect the delivery time change 
	 * and fires a ajax request to change the delivery type,
	 * when type changes and response comes from ajax, it reloads the page
	 */
	function pi_type_change() {
		this.init = function () {
			this.detectChange();
		}

		this.detectChange = function () {
			var parent = this;
			$("input[name=\'pi_delivery_type\']").on("change", function () {
				if ($("input[name=\'pi_delivery_type\']:checked").val() == "pickup") {
					parent.ajaxCall('pickup');
				} else if ($("input[name=\'pi_delivery_type\']:checked").val() == "delivery") {
					parent.ajaxCall("delivery");
				}
			});
		}

		this.ajaxCall = function (delivery_type, reload) {
			/* IE fix*/
			if (reload === undefined) {
				reload = true;
			}

			var parent = this;
			this.blockUI()
			jQuery.ajax({
				url: window.pi_date_options.ajaxUrl,
				data: {
					'action': "pi_set_delivery_type",
					'type': delivery_type
				},
				success: function () {
					if (reload) {
						location.reload(true);
					}
					//parent.unblockUI();
				}
			})
		}

		this.blockUI = function () {

			if (typeof jQuery.fn.block == 'undefined') return;

			jQuery("#pi_checkout_field, #order_review").block({
				message: null,
				overlayCSS: {
					background: "#fff",
					opacity: .6
				}
			});
		}

		this.unblockUI = function () {

			if (typeof jQuery.fn.block == 'undefined') return;

			jQuery("#pi_checkout_field, #order_review").unblock();
		}
	}

	jQuery(function ($) {
		var delivery_change_obj = new pi_type_change();
		delivery_change_obj.init();
	});

	/**
	 * This handles the date pickup option
	 */


	function pi_date_picker() {
		this.init = function () {
			this.addDatePicker();
			this.initTimeSlot();
			this.addClearButton();
		}

		this.addClearButton = function () {
			var height = jQuery("#pi_delivery_date").outerHeight();

			jQuery("#pi_delivery_date_field ").append('<span class="pisol_clear_button" id="clear_delivery_date">&times;</span>');

			jQuery("#clear_delivery_date").css("bottom", height / 2);

			this.clearButtonListener();
		}

		this.clearButtonListener = function () {
			var parent = this;
			jQuery(document).on('click', '#clear_delivery_date', function () {
				jQuery("#pi_delivery_date").val("").trigger('change');
				jQuery("#pi_delivery_time").attr('disabled', 'disabled');
				jQuery("input[name='pi_system_delivery_date']").val("");
				parent.initTimeSlot();
			});
		}

		this.initTimeSlot = function () {
			if (jQuery("#pi_delivery_time").length) {
				jQuery("#pi_delivery_time").selectWoo({
					placeholder: window.pi_date_options.selectTimeSlot,
					minimumResultsForSearch: -1
				});
			}
		}



		this.addDatePicker = function (minDate, maxDate, allowedDates) {
			var parent = this;
			this.noDateAvailableMessage(allowedDates);
			if (jQuery("#pi_delivery_date").length) {
				if (minDate === undefined) {
					var minDate = window.pi_date_options.minDate;
				}

				if (maxDate === undefined) {
					var maxDate = window.pi_date_options.maxDate;
				}

				if (allowedDates === undefined) {
					var allowedDates = window.pi_date_options.allowedDates;
				}

				jQuery("#pi_delivery_date").datepicker('destroy').datepicker({
					minDate: new Date(allowedDates[0]),
					maxDate: new Date(allowedDates[allowedDates.length - 1]),
					beforeShowDay: function (date) {
						var date = jQuery.datepicker.formatDate("yy/mm/dd", date);
						if (allowedDates.indexOf(date) == -1) {
							return [false, ""];
						}
						return [true, ""];
					},
					onSelect: function (dd) {
						var selected_date = jQuery(this).datepicker("getDate");
						var formated_date = jQuery.datepicker.formatDate("yy/mm/dd", selected_date);
						parent.onSelect(formated_date)
					},
				});
				$('.ui-datepicker').addClass('notranslate');

			}
		}

		this.noDateAvailableMessage = function (allowedDates) {
			if (allowedDates === undefined) {
				var allowedDates = window.pi_date_options.allowedDates;
			}

			if (!this.datesAvailable(allowedDates)) {
				jQuery("#pi_delivery_date").attr('placeholder', window.pi_date_options.allDatesBooked);
				jQuery("#pi_delivery_date").prop("disabled", true);
			} else {
				jQuery("#pi_delivery_date").attr('placeholder', window.pi_date_options.datePlaceholder);
				jQuery("#pi_delivery_date").prop("disabled", false);
			}
		}

		this.datesAvailable = function (allowedDates) {
			if (allowedDates === undefined) {
				var allowedDates = window.pi_date_options.allowedDates;
			}

			if (allowedDates.length != 0) {
				return true;
			}
			return false;
		}

		this.onSelect = function (date) {
			jQuery("input[name='pi_system_delivery_date']").val(date);
			jQuery("#pi_delivery_date").trigger('change');
			var obj = new pisol_ajax_time_slot_picker();
			obj.init(date);
		}

	}

	function reassignDatePicker() {
		jQuery(document).on('pi_dtt_reassign_date_picker', function (event, minDate, maxDate, allowedDates) {
			var obj = new pi_date_picker();
			obj.addDatePicker(minDate, maxDate, allowedDates);
		})
	}
	reassignDatePicker();

	function ajaxTimePickerTrigger() {
		jQuery(document).on('pi_dtt_time_picker', function (event, date, selectValue) {
			var obj = new pisol_ajax_time_slot_picker();
			obj.init(date, selectValue);
		})
	}
	ajaxTimePickerTrigger();

	/* ajax time */
	function pisol_ajax_time_slot_picker() {
		this.init = function (selected_date, selectedValue) {
			/* IE Fix */
			if (selectedValue === undefined) {
				selectedValue = "";
			}
			this.selected_date = selected_date;
			this.makeAjaxCall(this.selected_date);
			this.selectedValue = selectedValue;
		}

		this.makeAjaxCall = function (selected_date) {
			var parent = this;
			this.blockUI();
			jQuery.ajax({
				url: window.pi_date_options.ajaxUrl,
				dataType: 'json',
				type: 'POST',
				data: {
					action: 'pisol_dtt_get_time',
					selected_date: selected_date,
				},
				success: function (data) {
					parent.unblockUI();
					if (data.length == 0) {
						var placeholder = window.pi_date_options.allSlotsBooked;
					} else {
						var placeholder = window.pi_date_options.selectTimeSlot;
					}
					jQuery("#pi_delivery_time").removeAttr('disabled');
					jQuery("#pi_delivery_time").selectWoo({
						placeholder: placeholder,
						data: data,
						minimumResultsForSearch: -1,
						language: {
							noResults: function () {
								return window.pi_date_options.allSlotsBooked;
							}
						}
					});
					if (parent.selectedValue != "") {
						jQuery("#pi_delivery_time").val(parent.selectedValue).trigger('change');
					}
				}

			});
		}



		this.timeSlotPresent = function (time_slot_id, time_slots) {
			var found_index = false;
			jQuery.each(time_slots, function (index, val) {
				if (val['id'] == time_slot_id) {
					found_index = index;
				}
			});
			return found_index;
		}

		this.blockUI = function () {
			if (typeof jQuery.fn.block == 'undefined') return;

			jQuery("#pi_checkout_field").block({
				message: null,
				overlayCSS: {
					background: "#fff",
					opacity: .6
				}
			});
		}

		this.unblockUI = function () {
			if (typeof jQuery.fn.block == 'undefined') return;

			jQuery("#pi_checkout_field").unblock();
		}

	}

	function loadUserSelectedDateTimeFields() {
		this.init = function () {
			this.setDate();
			this.setLocation();
			this.ajaxLoadOfLocation();
		}

		this.ajaxLoadOfLocation = function () {
			var parent = this;
			jQuery(document).on('dtt_location_loaded', function () {
				parent.setLocation();
			});
		}

		this.setDate = function () {
			var date = localStorage.getItem("pisol_pi_system_delivery_date");
			if (date != null && date != "" && typeof date != 'undefined' && pi_date_options.allowedDates.includes(date)) {
				jQuery('#pi_checkout_field #pi_delivery_date').datepicker("setDate", new Date(date)).trigger('change');
				jQuery('input[name="pi_system_delivery_date"]').val(date);
				this.loadTime(date);
			}
		}

		this.loadTime = function (date) {
			var savedTime = localStorage.getItem("pisol_pi_delivery_time");
			if (savedTime != null && savedTime != "" && typeof savedTime != 'undefined') {
				jQuery(document).trigger('pi_dtt_time_picker', [date, savedTime]);
			} else {
				jQuery(document).trigger('pi_dtt_time_picker', [date]);
			}
		}

		this.setLocation = function () {
			var savedLocation = localStorage.getItem("pisol_pickup_location");
			if (savedLocation != null && savedLocation != "" && typeof savedLocation != 'undefined') {
				if (jQuery("input[name='pickup_location']").length) {
					jQuery("input[name='pickup_location'][value='" + savedLocation + "']").prop("checked", true);
				} else if (jQuery("select[name='pickup_location']").length) {
					jQuery("select[name='pickup_location'] > option[value='" + savedLocation + "']").prop('selected', true);
				}
			}
		}
	}

	jQuery(function ($) {
		var pi_date_picker_obj = new pi_date_picker();
		pi_date_picker_obj.init();

		var loadUserSelectedDateTimeFieldsObj = new loadUserSelectedDateTimeFields();
		loadUserSelectedDateTimeFieldsObj.init();
	});

})(jQuery);
