(function ($) {
	"use strict";
	$(window).load(function () {
		var $mup_form = $("#mupwp-form");

		$mup_form.validate({
			errorElement: 'span',
			rules: {
				email: true,
			},
			highlight: function (element) {
				$(element).addClass("error");
				if ($(element).is(':checkbox')) {
					$(element).closest('label').addClass('error');
				}
			},
			unhighlight: function (element) {
				$(element).removeClass("error");
				if ($(element).is(':checkbox')) {
					$(element).closest('label').removeClass("error");
				}
			},
			errorPlacement: function (error, element) {
				if (!element.is(':checkbox')) {
					error.insertAfter(element);
				}
			},
			submitHandler: function (form) {
				save_form(getFormData($(form)));
				return false;
			},
		});

		function save_form($form) {
			var ajax_url = mailup_params.ajax_url;
			$.ajax({
				type: "POST",
				url: ajax_url,
				data: {
					action: "mupwp_save_contact",
					ajaxNonce: mailup_params.ajaxNonce,
					parameters: $form,
				},
				beforeSend: function () {
					$mup_form.find(".ajax-loader").addClass("active");
					$mup_form.find(".feedback").removeClass('error');
					$(":submit", $('#mupwp-form')).prop('disabled', true);
					$mup_form.find(".feedback").text('');
				},
				success: function (res) {
					$mup_form.find(".feedback").text(res.data);
					$mup_form.trigger("reset");
				},
				error: function (xhr) {
					var res = xhr.responseJSON;
					$mup_form.find(".feedback").addClass('error').text(res.data);
				},
				complete: function (data) {
					$mup_form.find(".ajax-loader").removeClass("active");
					$(":submit", $mup_form).prop('disabled', false);
					$mup_form.find(".feedback").fadeIn().delay(5000).queue(function (n) {
						$(this).fadeOut("slow");
						n();
					});
				}
			});
		}

		function getFormData($form) {
			var unindexed_array = $form.serializeArray();
			var indexed_array = {};
			var indexed_fields = {};
			var indexed_terms = {};

			$.map(unindexed_array, function (n) {
				if ($.isNumeric(n['name'])) {
					indexed_fields[n['name']] = n['value']
				}
				else if (n['name'].startsWith('term')) {
					var id_term = n['name'][n['name'].length - 1];
					indexed_terms[id_term] = n['value'];
				}
				else {
					indexed_array[n['name']] = n['value'];
				}
			});

			indexed_array['fields'] = indexed_fields;
			indexed_array['terms'] = indexed_terms;
			return indexed_array;
		}
	});

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
})(jQuery);