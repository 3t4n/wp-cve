jQuery(document).ready(function($){
	var regex = /^((?!0)(?!.*\.$)((1?\d?\d|25[0-5]|2[0-4]\d|\*)(\.|\/[0-9]+|$)){4})|(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))(\/[0-9]+)?$/;

	$('#backend_ip_blacklist').tagsInput({
		defaultText: '',
		delimiter: ';',
		width: '90%',
		pattern: regex,
		onChange: function(obj, tag){
			if($('#backend_ip_whitelist').tagExist(tag)){
				$('#backend_ip_blacklist').removeTag(tag);
			}
		}
	});

	$('#backend_ip_whitelist').tagsInput({
		defaultText: '',
		delimiter: ';',
		width: '90%',
		pattern: regex,
		onChange: function(obj, tag){
			if($('#backend_ip_blacklist').tagExist(tag)){
				$('#backend_ip_whitelist').removeTag(tag);
			}
		}
	});

	$('#link-reset').on('click', function() {
		var $form = $('<form method="post">').html('<input type="hidden" name="reset" value="true">');

		$('body').append($form);

		$form.submit();
	});

	$('#backend_ip_blacklist_tag').on('paste', function() {
		var $input = $(this);

		setTimeout(function() {
			var tags = $input.val().split(/[ ,\n]+/);

			$.each(tags, function(i, tag) {
				if (!$('#backend_ip_whitelist').tagExist(tag) && regex.test(tag)) {
					$('#backend_ip_blacklist').addTag(tag);
				}
			});
		}, 100);
	});

	$('#backend_ip_whitelist_tag').on('paste', function() {
		var $input = $(this);

		setTimeout(function() {
			var tags = $input.val().split(/[ ,\n]+/);

			$.each(tags, function(i, tag) {
				if (!$('#backend_ip_blacklist').tagExist(tag) && regex.test(tag)) {
					$('#backend_ip_whitelist').addTag(tag);
				}
			});
		}, 100);
	});

	refresh_backend_settings();

	$('.chosen').chosen({
		width: '95%'
	});

	$('#enable_backend,input[name=backend_option]').on('change', function(){
		refresh_backend_settings();
	});

	$('#form_backend_settings').on('submit', function(e){
		if($('#enable_backend').is(':checked')){
			if($('#bypass_code').val().length == 0){
				if(($.inArray($('#my_country_code').val(), $('#backend_ban_list').val()) >= 0 && $('input[name=backend_block_mode]:checked').val() == 1) || ($.inArray($('#my_country_code').val(), $('#backend_ban_list').val()) < 0 && $('input[name=backend_block_mode]:checked').val() == 2)){
					alert("==========\n WARNING \n==========\n\nYou are about to block your own country, " + $('#my_country_name').val() + ".\nThis can locked yourself and prevent you from login to admin area.\n\nPlease set a bypass code to avoid this.");
					$('#bypass_code').focus();
					e.preventDefault();
				}
			}
		}
	});


	function refresh_backend_settings(){
		if($('#enable_backend').length == 0)
			return;

		if($('#enable_backend').is(':checked')){
			$('.input-field,.tagsinput input').prop('disabled', false);

			if($('input[name=backend_option]:checked').val() != '2'){
				$('#backend_error_page').prop('disabled', true);
			}

			if($('input[name=backend_option]:checked').val() != '3'){
				$('#backend_redirect_url').prop('disabled', true);
			}

			$('.disabled').prop('disabled', true);

			if ($('#support_proxy').val() == '0') {
				$('#backend_block_proxy, #backend_block_proxy_type').prop('disabled', true);
			}

			toggleTagsInput(true);
		}
		else{
			$('.input-field').prop('disabled', true);
			toggleTagsInput(false);
		}

		$('.chosen').trigger('chosen:updated');
	}

	function toggleTagsInput(state){
		if(!state){
			$.each($('.tagsinput'), function(i, obj){
				var $div = $('<div class="tagsinput-disabled" style="display:block;position:absolute;z-index:99999;opacity:0.1;background:#808080";top:' + $(obj).offset().top + ';left:' + $(obj).offset().left + '" />').css({
					width: $(obj).outerWidth() + 'px',
					height: $(obj).outerHeight() + 'px'
				});

				$(obj).parent().prepend($div);
			});
		}
		else{
			$('.tagsinput-disabled').remove();
		}
	}
});