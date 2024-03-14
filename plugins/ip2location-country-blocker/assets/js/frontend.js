jQuery(document).ready(function ($) {
	var regex = /^((?!0)(?!.*\.$)((1?\d?\d|25[0-5]|2[0-4]\d|\*)(\.|\/[0-9]+|$)){4})|(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))(\/[0-9]+)?$/;

	$('#frontend_ip_blacklist').tagsInput({
		defaultText: '',
		delimiter: ';',
		width: '90%',
		pattern: regex,
		onChange: function (obj, tag) {
			if ($('#frontend_ip_whitelist').tagExist(tag)) {
				$('#frontend_ip_blacklist').removeTag(tag);
			}
		}
	});

	$('#frontend_ip_whitelist').tagsInput({
		defaultText: '',
		delimiter: ';',
		width: '90%',
		pattern: regex,
		onChange: function (obj, tag) {
			if ($('#frontend_ip_blacklist').tagExist(tag)) {
				$('#frontend_ip_whitelist').removeTag(tag);
			}
		}
	});

	$('#frontend_ip_blacklist_tag').on('paste', function() {
		var $input = $(this);

		setTimeout(function() {
			var tags = $input.val().split(/[ ,\n]+/);

			$.each(tags, function(i, tag) {
				if (!$('#frontend_ip_whitelist').tagExist(tag) && regex.test(tag)) {
					$('#frontend_ip_blacklist').addTag(tag);
				}
			});
		}, 100);
	});

	$('#frontend_ip_whitelist_tag').on('paste', function() {
		var $input = $(this);

		setTimeout(function() {
			var tags = $input.val().split(/[ ,\n]+/);

			$.each(tags, function(i, tag) {
				if (!$('#frontend_ip_blacklist').tagExist(tag) && regex.test(tag)) {
					$('#frontend_ip_whitelist').addTag(tag);
				}
			});
		}, 100);
	});

	refresh_frontend_settings();

	$('.chosen').chosen({
		width: '95%'
	});

	$('#enable_frontend,input[name=frontend_option]').on('change', function () {
		refresh_frontend_settings();
	});

	$('#link-reset').on('click', function() {
		var $form = $('<form method="post">').html('<input type="hidden" name="reset" value="true">');

		$('body').append($form);

		$form.submit();
	});

	function refresh_frontend_settings() {
		if ($('#enable_frontend').length == 0) {
			return;
		}

		if ($('#enable_frontend').is(':checked')) {
			$('.input-field,.tagsinput input,.disabled').prop('disabled', false);

			if ($('input[name=frontend_option]:checked').val() != '2') {
				$('#frontend_error_page').prop('disabled', true);
			}

			if ($('input[name=frontend_option]:checked').val() != '3') {
				$('#frontend_redirect_url').prop('disabled', true);
			}

			if ($('#support_proxy').val() == '0') {
				$('#frontend_block_proxy, #frontend_block_proxy_type').prop('disabled', true);
			}

			toggleTagsInput(true);
		} else {
			$('.input-field,.tagsinput input,.disabled').prop('disabled', true);
			toggleTagsInput(false);
		}

		$('.chosen').trigger('chosen:updated');
	}

	function toggleTagsInput(state) {
		if (!state) {
			$.each($('.tagsinput'), function (i, obj) {
				var $div = $('<div class="tagsinput-disabled" style="display:block;position:absolute;z-index:99999;opacity:0.1;background:#808080";top:' + $(obj).offset().top + ';left:' + $(obj).offset().left + '" />').css({
					width: $(obj).outerWidth() + 'px',
					height: $(obj).outerHeight() + 'px'
				});

				$(obj).parent().prepend($div);
			});
		} else {
			$('.tagsinput-disabled').remove();
		}
	}
});