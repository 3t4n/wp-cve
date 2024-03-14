(function ($) {
	$(function () {
		var modalNonce = $( 'input#learndash-powerpack-modal-nonce' ).val();
		$(document.body).on(
			'change',
			// eslint-disable-next-line max-len
			'.enable_disable_class',
			function () {
				var settingsNonce = $( 'input#learndash-powerpack-settings-nonce' ).val();
				var currentElement = $(this);
				var getActive = $(this).is(':checked'),
					getStatus = getActive ? 'active' : 'inactive';
				var data = {
					'action': 'enable_disable_class_ajax',
					'nonce': settingsNonce,
					'value': $(this).val(),
					'active': getStatus,
				};
				$(currentElement).closest('.learndash_snippet_list_item').find('.learndash-powerpack-content').addClass('learndash_powerpack_ajax_loader');
				$.post(learndash_powerpack_jquery_var.ajax_url, data, function (response) {
					$(currentElement).closest('.learndash_snippet_list_item').find('.learndash-powerpack-content').removeClass('learndash_powerpack_ajax_loader');
					if ('success' === response.success) {
						// Success
					} else {
						// Error
					}
				});
			});

		//country code error
		$(document.body).on(
			'change',
			// eslint-disable-next-line max-len
			'#ld_snippet_powerpack_filter_select',
			function () {
				var_current_element = $(this).val();
				if (var_current_element != 'all') {
					$('#learndash_snippet_list .learndash_snippet_list_item').show().not('#' + var_current_element).hide();
				} else $('#learndash_snippet_list .learndash_snippet_list_item').show();
			});

		// load modal popup
		$(document.body).on(
			'click',
			// eslint-disable-next-line max-len
			'.ldt-btn--setting',
			function () {
				var currentElement = $(this);
				var dataClass = $(this).attr('data-class');
				var modal = document.getElementById('learndash-powerpack-modal');
				var data = {
					'action': 'learndash_get_modal_content',
					'class_name': dataClass,
					'nonce': modalNonce,
				};
				$(currentElement).closest('.learndash_snippet_list_item').find('.learndash-powerpack-content').addClass('learndash_powerpack_ajax_loader');
				$.post(learndash_powerpack_jquery_var.ajax_url, data, function (response) {
					$(currentElement).closest('.learndash_snippet_list_item').find('.learndash-powerpack-content').removeClass('learndash_powerpack_ajax_loader');
					var title = response.data.title;
					var settingsContent = response.data.settings_content;
					var footerContent = response.data.footer_content;
					$('.model_data_title').html(title);
					$('.learndash-powerpack-modal-body').html(settingsContent);
					$('.learndash-powerpack-modal-footer').html(footerContent);
					modal.style.display = 'block';
					$('.learndash_success_message').html('');
					if ('success' === response.success) {
						// Success
					} else {
						// Error
					}
				});
			});

		var modal = document.getElementById('learndash-powerpack-modal');

		// Close model popup

		$(document.body).on('click', '.learndash-powerpack-close', function () {
			$('.modal').hide();
		});

		//ajax save classes data
		$(document.body).on(
			'click',
			// eslint-disable-next-line max-len
			'.learndash_save_form_data',
			function (e) {
				e.preventDefault();
				var currentElement = $(this);
				var form = $('form.form_learndash_save_class_data');
				var formData = form.serializeArray();
				var dataClass = $(this).attr('data-class');
				$(currentElement).closest('div.modal').find('.learndash_success_message').html('');
				$(currentElement).closest('div.modal').find('.learndash_error_message').html('');
				var data = {
					'action': 'learndash_save_class_data_ajax',
					'class_name': dataClass,
					'formData': formData,
					'nonce': modalNonce,
				};
				$(currentElement).closest('div.modal').find('.learndash-powerpack-modal-content').addClass('learndash_powerpack_ajax_loader_form');
				$.post(learndash_powerpack_jquery_var.ajax_url, data, function (response) {
					$(currentElement).closest('div.modal').find('.learndash-powerpack-modal-content').removeClass('learndash_powerpack_ajax_loader_form');
					if ( response.success ) {
						$(currentElement).closest('div.modal').find('.learndash_success_message').html('<p>' + response.data.message + '</p>');
					} else {
						$(currentElement).closest('div.modal').find('.learndash_error_message').html('<p>' + response.data.message + '</p>');					}
				});
			});

	//ajax save classes data
	$(document.body).on(
		'click',
		// eslint-disable-next-line max-len
		'.learndash_delete_form_data',
		function (e) {
			e.preventDefault();
			var currentElement = $(this);
			var dataClass = $(this).attr('data-class');
			$(currentElement).closest('div.modal').find('.learndash_success_message').html('');
			$(currentElement).closest('div.modal').find('.learndash_error_message').html('');
			var data = {
				'action': 'learndash_delete_class_data_ajax',
				'class_name': dataClass,
				'nonce': modalNonce,
			};
			$(currentElement).closest('div.modal').find('.learndash-powerpack-modal-content').addClass('learndash_powerpack_ajax_loader_form');
			$.post(learndash_powerpack_jquery_var.ajax_url, data, function (response) {
				$(currentElement).closest('div.modal').find('.learndash-powerpack-modal-content').removeClass('learndash_powerpack_ajax_loader_form');
				if ( response.success ) {
					$(currentElement).closest('div.modal').find('.learndash_success_message').html('<p>' + response.data.message + '</p>');
					$(currentElement).closest('div.modal').find('input[type="text"]').val('');
				} else {
					$(currentElement).closest('div.modal').find('.learndash_error_message').html('<p>' + response.data.message + '</p>');					}
			});
		});


		$('#ld-powerpack-tabs a.button').click(function () {
			var target = $(this).data('target-content');

			$('.ld-powerpack-tab').hide();
			$('.ld-powerpack-tab#' + target).show();

			$('#ld-powerpack-tabs a.button').removeClass('active');
			$(this).addClass('active');
		});

	});
})(jQuery); // Fully reference jQuery after this point.
