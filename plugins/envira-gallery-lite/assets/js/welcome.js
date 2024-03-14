(function ($) {
	$(function () {
		$('.envira-am-plugins-wrap').on(
			'click',
			'.envira-am-plugins-install',
			function (e) {
				e.preventDefault();
				var $this = $(this),
					url = $this.data('url'),
					basename = $this.data('basename');
					spinner = $this.parent().find('.spinner'),
					message = $(this)
					.parent()
					.parent()
					.find('.envira-am-plugins-status');
				var install_opts = {
					url: envira_gallery_welcome.ajax,
					type: 'post',
					async: true,
					cache: false,
					dataType: 'json',
					data: {
						action: 'envira_install_partner',
						nonce: envira_gallery_welcome.install_nonce,
						basename: basename,
						download_url: url,
					},
					success: function (response) {
						$this.text(envira_gallery_welcome.deactivate)
							.removeClass('envira-am-plugins-install')
							.addClass('envira-am-plugins-deactivate');

							$(message).text(envira_gallery_welcome.active);

						// Trick here to wrap a span around he last word of the status
						var heading = $(message),
							word_array,
							last_word,
							first_part;

						word_array = heading.html().split(/\s+/); // split on spaces
						last_word = word_array.pop(); // pop the last word
						first_part = word_array.join(' '); // rejoin the first words together
						spinner.css('visibility', 'hidden');

						heading.html(
							[
								first_part,
								' <span>',
								last_word,
								'</span>',
							].join(''),
						);
						// Proc
					},
					error: function (xhr, textStatus, e) {
						console.log(e);
					},
				};
				console.log(spinner);
				spinner.css('visibility', 'visible');
				$.ajax(install_opts);
			},
		);
		$('.envira-am-plugins-wrap').on(
			'click',
			'.envira-am-plugins-activate',
			function (e) {
				e.preventDefault();
				var $this = $(this),
					url = $this.data('url'),
				 	basename = $this.data('basename'),
					spinner = $this.parent().find('.spinner'),
					message = $(this)
					.parent()
					.parent()
					.find('.envira-am-plugins-status');
				var activate_opts = {
					url: envira_gallery_welcome.ajax,
					type: 'post',
					async: true,
					cache: false,
					dataType: 'json',
					data: {
						action: 'envira_activate_partner',
						nonce: envira_gallery_welcome.activate_nonce,
						basename: basename,
						download_url: url,
					},
					success: function (response) {
						$this.text(envira_gallery_welcome.deactivate)
							.removeClass('envira-am-plugins-activate')
							.addClass('envira-am-plugins-deactivate');

						$(message).text(envira_gallery_welcome.active);
						// Trick here to wrap a span around he last word of the status
						var heading = $(message),
							word_array,
							last_word,
							first_part;

						word_array = heading.html().split(/\s+/); // split on spaces
						last_word = word_array.pop(); // pop the last word
						first_part = word_array.join(' '); // rejoin the first words together
						spinner.css('visibility', 'hidden');
						heading.html(
							[
								first_part,
								' <span>',
								last_word,
								'</span>',
							].join(''),
						);
						location.reload(true);
					},
					error: function (xhr, textStatus, e) {
						console.log(e);
					},
				};
				console.log(spinner);
				spinner.css('visibility', 'visible');
				$.ajax(activate_opts);
			},
		);
		$('.envira-am-plugins-wrap').on(
			'click',
			'.envira-am-plugins-deactivate',
			function (e) {
				e.preventDefault();
				var $this = $(this),
					url = $this.data('url'),
					basename = $this.data('basename'),
					spinner = $this.parent().find('.spinner'),
					message = $(this)
					.parent()
					.parent()
					.find('.envira-am-plugins-status');
				var deactivate_opts = {
					url: envira_gallery_welcome.ajax,
					type: 'post',
					async: true,
					cache: false,
					dataType: 'json',
					data: {
						action: 'envira_deactivate_partner',
						nonce: envira_gallery_welcome.deactivate_nonce,
						basename: basename,
						download_url: url,
					},
					success: function (response) {
						$this.text(envira_gallery_welcome.activate)
							.removeClass('envira-am-plugins-deactivate')
							.addClass('envira-am-plugins-activate');

						$(message).text(envira_gallery_welcome.inactive);
						// Trick here to wrap a span around he last word of the status
						var heading = $(message),
							word_array,
							last_word,
							first_part;

						word_array = heading.html().split(/\s+/); // split on spaces
						last_word = word_array.pop(); // pop the last word
						first_part = word_array.join(' '); // rejoin the first words together
						spinner.css('visibility', 'hidden');
						heading.html(
							[
								first_part,
								' <span>',
								last_word,
								'</span>',
							].join(''),
						);
						location.reload(true);
					},
					error: function (xhr, textStatus, e) {
						console.log(e);
					},
				};
				console.log(spinner);
				spinner.css('visibility', 'visible');
				$.ajax(deactivate_opts);
			},
		);
	});
})(jQuery);
