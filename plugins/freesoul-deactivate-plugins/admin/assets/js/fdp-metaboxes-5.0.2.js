jQuery(document).ready(function($){
	plugsN = $('.eos-dp-plugin-name').length;
	eos_dpBody = $('body');
	right = eos_dp_js.is_rtl ? 'left' : 'right';
	psiButtons = $('.eos-dp-psi-preview');
	$('.eos-fdp-checkbox').on('click',function(){
		$('.eos-dp-post-name-wrp').addClass('eos-post-locked');
		$('#eos_dp_single_locked').val('locked');
		$('#eos-dp-plugins-wrp').addClass('eos-post-locked');
	});
	$('.eos-dp-lock-post-wrp').on('click',function(event){
		$('#eos-dp-plugins-wrp').toggleClass('eos-post-locked');
		$(this).parent().toggleClass('eos-post-locked');
		$('#fdp-metabox-post-types').toggleClass('eos-hidden');
		$('#fdp-metabox-singles').toggleClass('eos-hidden');
		if($(this).parent().hasClass('eos-post-locked')){
			$('#eos-dp-plugins-wrp').addClass('eos-post-locked');
			$('#eos_dp_single_locked').val('locked');
		}
		else{
			$('#eos-dp-plugins-wrp').removeClass('eos-post-locked');
			$('#eos_dp_single_locked').val('unlocked');
		}
	});
	$('.eos-dp-preview').on('click',function(event){
		var a = this,
			plugin_path = '',
			theme = $('.eos-dp-themes-list').val(),
			chk;
		$('.eos-fdp-checkbox').each(function(){
			chk = $(this);
			if(chk.is(':checked') && !chk.hasClass('eos-dp-global-chk-row')){
				var data_path = chk.val();
				if('undefined' !== data_path){
					plugin_path += ';pn:' + data_path;
				}
			}
			else{
				plugin_path += ';pn:';
			}
		});
		var button = $(this),
			page_speed_insights = button.attr('data-page_speed_insights'),
			microtime = Date.now(),
			admin = 'undefined' !== typeof(eos_dp_js.page && 'eos_dp_admin' === eos_dp_js.page) ? '_admin' : '',
			data = {
				"nonce" : $("#eos_dp_setts").val(),
				"post_id" : eos_dp_js.post_id,
				"plugin_path" : plugin_path,
				"microtime" : microtime,
				"page_speed_insights" : page_speed_insights,
				"action" : 'eos_dp_preview'
			};
		eos_dpBody.addClass('eos-test-in-progress');
		$.ajax({
			type : "POST",
			url : ajaxurl,
			data : data,
			success : function (response) {
				if (parseInt(response) == 1) {
					var first_href = a.href,
						theme_arg = '',
						encode_url = 'undefined' !== typeof(a.dataset.encode_url) && 'true' === a.dataset.encode_url;

					if('dummy_html' !== theme){
						theme_arg = encode_url ? '%26theme%3D' + theme : '&theme=' + theme;
						a.href = a.href.split('%26theme%3D')[0].replace('&','%26') + '%26theme%3D' + theme;
					}
					else{
						a.href = a.href.split('=http')[0].split('%3Dhttp')[0] + '=' + eos_dp_js.html_url;
					}

					if(encode_url){
						a.href = a.href.split('%26theme%3D')[0].split('&').join('%26').split('=').join('%3D') + theme_arg;
						a.href += 'dummy_html' !== theme ? '%26time%3D' + Date.now() : '';
					}
					else{
						a.href = a.href.split('&theme=')[0].split('%26').join('&').split('%3D').join('=');
						a.href += 'dummy_html' !== theme ? '&time=' + Date.now() : '';
					}
					a.href = a.href.replace('%3Dhttps','=https').replace('%3Dhttp','=http');
					a.href += '&test_id=' + microtime;
					window.open(a.href,'_blank');
					a.href = first_href;
					eos_dpBody.removeClass('eos-test-in-progress');
					return true;
				}
				else{
					eos_dpBody.removeClass('eos-test-in-progress');
					alert( 'Something went wrong' );
				}
			}
		});
		return false;
	});
	$('.eos-dp-lock-post').on('click',function(){
		$(this).closest('tr').toggleClass('eos-post-locked');
	});
	$(".eos-dp-pro-autosettings").on("click", function () {
		if('undefined' === typeof('eos_dp_send_autosuggest_request')) return;
		window.eos_dp_metabox_autosuggest_counter = 0;
		window.eos_dp_autosuggest_counter = 0;
		$('#eos-dp-autosuggest-msg').removeClass('eos-hidden');
		$('#eos-dp-autosuggest-msg-error').addClass('eos-hidden');
		var button = $(this),
			ajax_loader = button.next(".ajax-loader-img"),
			plugins= [],
			data = {
				"offset" : 0,
				"post_id" : eos_dp_js.post_id,
				"nonce" : $("#eos_dp_pro_auto_settings_metabox").val(),
				"action" : 'eos_dp_pro_auto_settings_metabox'
			};
		$('.eos-fdp-checkbox').each(function(){
			plugins.push($(this).attr('data-path'));
		});
		data.plugins = plugins.join(',');
		ajax_loader.removeClass('eos-hidden').removeClass('eos-not-visible');
		button.addClass('eos-active-test');
		var N = Math.ceil(plugins.length/4);
		for(var n=1;n<N;++n){
			data.offset = 4*window.eos_dp_autosuggest_counter;
			eos_dp_send_autosuggest_request(button,data);
			++window.eos_dp_autosuggest_counter;
		}
		return false;
	});
	$('.fdp-dismiss-pro-notice').on('click',function(){
		var btn = $(this);
		$.post(ajaxurl,{
			pointer: 'fdp-pro-notice',
			action: 'dismiss-wp-pointer'
		});
		$(this).closest('.fdp-pro-notice').fadeOut(300);
	});
});
function eos_dp_pro_check_suggestion_execution(){
	if('undefined' !== typeof(window.eos_dp_actual_row_in_progress)){
		if(jQuery('.eos-dp-post-row').length - window.eos_dp_actual_row_in_progress < 1){
			if('undefined' !== typeof(fdpCheckboxesInterval)){
				window.clearInterval(fdpCheckboxesInterval);
			}
			if('undefined' !== typeof(window.eos_dp_pro_suggest_allInterval)){
				window.clearInterval(eos_dp_pro_suggest_allInterval);
			}
			window.eos_dp_actual_row_in_progress = 0;
		}
	}
}
function eos_dp_send_ajax( button,data ){
	var ajax_loader = button.next(".ajax-loader-img");
	ajax_loader.removeClass('eos-not-visible');
	jQuery.ajax({
		type : "POST",
		url : ajaxurl,
		data : data,
		success : function (response) {
			ajax_loader.addClass('eos-not-visible');
			if (parseInt(response) == 1) {
				jQuery('.eos-dp-opts-msg_success').removeClass('eos-hidden');
			} else {
				eos_dp_show_errors(response);
			}
		}
	});
}
function eos_dp_show_errors(response){
	if(response !== '0' && response !== ''){
		jQuery('.eos-dp-opts-msg_warning').text(response);
		jQuery('.eos-dp-opts-msg_warning').removeClass('eos-hidden');
	}
	else{
		jQuery('.eos-dp-opts-msg_failed').removeClass('eos-hidden');
	}
}
function eos_dp_send_autosuggest_request(button,data){
	jQuery.ajax({
		type : "POST",
		url : ajaxurl,
		data : data,
		success : function (response){
			if('' !== response){
				jQuery('#eos-dp-autosuggest-msg').addClass('eos-hidden');
				if('error' !== response){
					json = jQuery.parseJSON(response);
					var path = '',chks = jQuery('.eos-theme-checkbox-div input');
					chks.slice(data.offset - 4,3).each(function(idx,el){
						chk = jQuery(this);
						path = jQuery('#eos-dp-plugin-name-' + (idx + 1)).attr('data-path');
						if(json.indexOf(path) > -1){
							chk
								.attr('checked',1)
								.closest('td').addClass('eos-dp-autochecked').removeClass('eos-dp-active').trigger('change')
						}
						else{
							chk
								.removeAttr('checked')
								.closest('td').addClass('eos-dp-autochecked').addClass('eos-dp-active').trigger('change')
						}
						chk.trigger('change');
					});
					if(parseInt(window.eos_dp_autosuggest_counter) < Math.ceil(jQuery('.eos-dp-name-th').length/4)){
						eos_dp_send_autosuggest_request(button,data);
					}
					else{
						jQuery('.eos-dp-autochecked').removeClass('eos-dp-autochecked');
						button
							.closest('tr').removeClass('eos-test-in-progress')
							.removeClass('eos-active-test')
							.closest('table').removeClass('eos-dp-progress')
							.next(".ajax-loader-img").addClass('eos-hidden').addClass('eos-not-visible');
						if('undefined' !== typeof(window.eos_dp_actual_row_in_progress) && null !== window.eos_dp_actual_row_in_progress){
							++window.eos_dp_actual_row_in_progress;
							var nextBtn = 'eos_dp_admin' !== eos_dp_js.page ? row.next().find('.eos-dp-pro-autosettings') : row.nextAll('.eos-dp-admin-row').first().find('.eos-dp-pro-autosettings');
							if(nextBtn.length > 0){
								nextBtn.trigger('click');
							}
							else{
								window.eos_dp_actual_row_in_progress = null;
							}
						}

						return false;
					}

				}
				else{
					jQuery('#eos-dp-autosuggest-msg-error').removeClass('eos-hidden');
					jQuery('.eos-dp-autochecked').removeClass('eos-dp-autochecked');
					eos_dp_stop_checkbox_animation();
					button
						.closest('tr').removeClass('eos-test-in-progress')
						.removeClass('eos-active-test')
						.closest('table').removeClass('eos-dp-progress')
						.next(".ajax-loader-img").addClass('eos-hidden').addClass('eos-not-visible');
					return false;
				}
			}
		}
	});
}
function eos_dp_update_chk_wrp(chk,checked){
	if(true === checked){
		chk.parent().removeClass('eos-dp-active-wrp').addClass('eos-dp-not-active-wrp');
	}
	else{
		chk.parent().addClass('eos-dp-active-wrp').removeClass('eos-dp-not-active-wrp');
	}
}
function eos_dp_update_included_checks(el){
	var wrp = jQuery(el).closest('#eos-dp-plugins-wrp').find('#fdp-metabox-singles'),
		checks_imploder = jQuery(el).closest('#eos-dp-plugins-wrp').find('.checkbox-result'),
		included_chk = [],
		n = 0;
	wrp.find('.eos-theme-checkbox-div').each(function () {
		if (jQuery(this).find('input').prop('checked') === true) {
			included_chk[n] = jQuery(this).find('input').val();
			n = n + 1;
		}
	});
	checks_imploder.val(included_chk.sort().toString());
}
