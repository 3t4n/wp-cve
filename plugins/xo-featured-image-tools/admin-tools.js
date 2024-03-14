var XOFieaturedImageTool;

(function ($) {
	XOFieaturedImageTool = function (parameters) {
		var self = this;
		var total_count = parameters.post_ids.length;
		var counter = 0;
		var successes_counter = 0;
		var error_counter = 0;
		var abort_flag = false;

		this.sprintf = function () {
			var _arg = $.makeArray(arguments), template = _arg.shift(), i;
			for (i in _arg) {
				template = template.replace('%s', _arg[i]);
			}
			return template;
		};

		this.updateStatus = function (response) {
			counter++;
			$("#xo-featured-image-bar").progressbar("value", (counter / total_count) * 100);
			$("#xo-featured-image-bar-percent").html(Math.round((counter / total_count) * 100) + "%");
			if (response.success) {
				successes_counter++;
				$("#xo-featured-image-success-count").html(successes_counter);
				// $("#xo-featured-image-msg").append("<li>" + response.success + "</li>");
			} else {
				error_counter++;
				$("#xo-featured-image-error-count").html(error_counter);
				$("#xo-featured-image-msg").append("<li>" + response.error + "</li>");
			}
		};

		this.finalization = function () {
			var msg = '';
			$('#xo-featured-image-stop-bottun').hide();
			if (error_counter > 0) {
				msg = this.sprintf(parameters.failure_message, error_counter);
			} else {
				msg = parameters.success_message;
			}
			$("#xo-featured-image-message").hide();
			$("#message").html("<p><strong>" + msg + "</strong></p>");
			$("#message").show();
			$("#xo-featured-image-back-link").show();
		};

		this.init = function (id) {
			$("#xo-featured-image-bar").progressbar();
			$("#xo-featured-image-bar-percent").html("0%");

			$("#xo-featured-image-stop-bottun").click(function (btn) {
				abort_flag = true;
				$('#xo-featured-image-stop-bottun').prop("disabled", true);
				$('#xo-featured-image-stop-bottun').val(parameters.stop_button_message);
			});
		};

		this.start = function (id) {
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: { action: 'xo_featured_image_tools', nonce: parameters.nonce, id: id, 'external_image': parameters.external_image, 'exclude_small_image': parameters.exclude_small_image, 'default_image': parameters.default_image },
				success: function (response) {
					if (response !== Object(response) || (typeof response.success === 'undefined' && typeof response.error === 'undefined')) {
						response = new Object;
						response.success = false;
						response.error = "Fatal error.";
					}
					self.updateStatus(response);
					if (parameters.post_ids.length && !abort_flag) {
						self.start(parameters.post_ids.shift());
					} else {
						self.finalization();
					}
				},
				error: function (response) {
					self.updateStatus(response);
					if (parameters.post_ids.length && !abort_flag) {
						self.start(parameters.post_ids.shift());
					} else {
						self.finalization();
					}
				}
			});
		};

		this.init();
		this.start(parameters.post_ids.shift());
	};
}(jQuery));
