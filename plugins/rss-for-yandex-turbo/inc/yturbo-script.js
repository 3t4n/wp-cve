jQuery(document).ready(function($) {

	//блок разбития rss begin
	$('#ytrazb').change(function() {
		if ($('#ytrazb').is(':checked')) {
			$('.ytrazbnumbertr').fadeIn();
		} else {
			$('.ytrazbnumbertr').hide();
		}
	});
	//блок разбития rss end

	//блок выборочного отключения begin
	$('#ytexcludeurls').change(function() {
		if ($('#ytexcludeurls').is(':checked')) {
			$('.ytexcludeurlslisttr').fadeIn();
		} else {
			$('.ytexcludeurlslisttr').hide();
		}
	});
	//блок выборочного отключения end

	//блок полного отключения begin
	$('#ytremoveturbo').change(function() {
		if ($('#ytremoveturbo').is(':checked')) {
			$('.ytprotokoltr').fadeIn();
		} else {
			$('.ytprotokoltr').hide();
		}
	});
	//блок полного отключения end

	//блок включения даты begin
	$('#ytpostdate').change(function() {
		if ($('#ytpostdate').is(':checked')) {
			$('.ytdateformattr').fadeIn();
		} else {
			$('.ytdateformattr').hide();
		}
	});
	//блок включения даты end

	//блок выбора размера миниатюры begin
	$('#ytthumbnail').change(function() {
		if ($('#ytthumbnail').is(':checked')) {
			$('.ytselectthumbtr').fadeIn();
		} else {
			$('.ytselectthumbtr').hide();
		}
	});
	//блок выбора размера миниатюры end

	//блок указания автора записи begin
	$('#ytauthorselect').change(function() {
		if ($('#ytauthorselect option:selected').val() == 'Указать автора') {
			$('#ownname2').fadeIn();
		} else {
			$('#ownname2').hide();
		}
	});
	//блок указания автора записи end

	//блок включения содержания begin
	$('#yttoc').change(function() {
		if ($('#yttoc').is(':checked')) {
			$('.yttocchildtr').fadeIn();
		} else {
			$('.yttocchildtr').hide();
		}
	});
	//блок включения содержания end

	//блок включения "поделиться" begin
	$('#ytshare').change(function() {
		if ($('#ytshare').is(':checked')) {
			$('.ytsharechildtr').fadeIn();
		} else {
			$('.ytsharechildtr').hide();
		}
	});
	//блок включения "поделиться" end

	//блок включения "обратной связи" begin
	$('#ytfeedback').change(function() {
		if ($('#ytfeedback').is(':checked')) {
			$('.ytfeedbackchildtr').fadeIn();
			if ($('#ytfeedbackselect option:selected').val() == 'false') {$('.ytfeedbackselectmestotr').fadeIn();}
		} else {
			$('.ytfeedbackchildtr').hide();
			$('.ytfeedbackselectmestotr').hide();
			$('#ytfeedbackcontacts [value="myselect"]').attr('selected', 'selected');
			$('.ytfeedbackcalltr,.ytfeedbackcallbacktr,.ytfeedbackchattr,.ytfeedbackmailtr,.ytfeedbackvkontaktetr,.ytfeedbackodnoklassnikitr,.ytfeedbacktwittertr,.ytfeedbackfacebooktr,.ytfeedbackvibertr,.ytfeedbackwhatsapptr,.ytfeedbacktelegramtr').hide();
		}
	});
	//блок включения "обратной связи" end

	//выбор места при выборе "в указанном месте" в блоке "обратной связи" begin
	$('#ytfeedbackselect').change(function() {
		if ($('#ytfeedbackselect option:selected').val() == 'false') {
			$('.ytfeedbackselectmestotr').fadeIn();
		} else {
			$('.ytfeedbackselectmestotr').hide();
		}
	});
	//выбор места при выборе "в указанном месте" в блоке "обратной связи" end

	//выбор контактов в блоке "обратной связи" begin
	$('#ytfeedbackcontacts').change(function() {
		if ($('#ytfeedbackcontacts option:selected').val() == 'feedbackcall') {
			$('.ytfeedbackcalltr').fadeIn();
		} else {
			$('.ytfeedbackcalltr').hide();
		}
		if ($('#ytfeedbackcontacts option:selected').val() == 'feedbackcallback') {
			$('.ytfeedbackcallbacktr').fadeIn();
		} else {
			$('.ytfeedbackcallbacktr').hide();
		}
		if ($('#ytfeedbackcontacts option:selected').val() == 'feedbackchat') {
			$('.ytfeedbackchattr').fadeIn();
		} else {
			$('.ytfeedbackchattr').hide();
		}
		if ($('#ytfeedbackcontacts option:selected').val() == 'feedbackmail') {
			$('.ytfeedbackmailtr').fadeIn();
		} else {
			$('.ytfeedbackmailtr').hide();
		}
		if ($('#ytfeedbackcontacts option:selected').val() == 'feedbackvkontakte') {
			$('.ytfeedbackvkontaktetr').fadeIn();
		} else {
			$('.ytfeedbackvkontaktetr').hide();
		}
		if ($('#ytfeedbackcontacts option:selected').val() == 'feedbackodnoklassniki') {
			$('.ytfeedbackodnoklassnikitr').fadeIn();
		} else {
			$('.ytfeedbackodnoklassnikitr').hide();
		}
		if ($('#ytfeedbackcontacts option:selected').val() == 'feedbacktwitter') {
			$('.ytfeedbacktwittertr').fadeIn();
		} else {
			$('.ytfeedbacktwittertr').hide();
		}
		if ($('#ytfeedbackcontacts option:selected').val() == 'feedbackfacebook') {
			$('.ytfeedbackfacebooktr').fadeIn();
		} else {
			$('.ytfeedbackfacebooktr').hide();
		}
		if ($('#ytfeedbackcontacts option:selected').val() == 'feedbackviber') {
			$('.ytfeedbackvibertr').fadeIn();
		} else {
			$('.ytfeedbackvibertr').hide();
		}
		if ($('#ytfeedbackcontacts option:selected').val() == 'feedbackwhatsapp') {
			$('.ytfeedbackwhatsapptr').fadeIn();
		} else {
			$('.ytfeedbackwhatsapptr').hide();
		}
		if ($('#ytfeedbackcontacts option:selected').val() == 'feedbacktelegram') {
			$('.ytfeedbacktelegramtr').fadeIn();
		} else {
			$('.ytfeedbacktelegramtr').hide();
		}
	});
	//выбор контактов в блоке "обратной связи" end

	//блок включения комментариев begin
	$('#ytcomments').change(function() {
		if ($('#ytcomments').is(':checked')) {
			$('.ytcommentschildtr').fadeIn();
		} else {
			$('.ytcommentschildtr').hide();
		}
	});
	//блок включения комментариев end

	//блок включения "похожих записей" begin
	$('#ytrelated').change(function() {
		if ($('#ytrelated').is(':checked')) {
			if ($('#ytrelatedcache').is(':checked')) {$('.ytcachetime').fadeIn();}
			$('.ytrelatedchildtr').fadeIn();
		} else {
			$('.ytrelatedchildtr').hide();
			$('.ytcachetime').hide();
		}
	});
	//блок включения "похожих записей" end

	//блок включения кэша в блоке "похожих записей" begin
	$('#ytrelatedcache').change(function() {
		if ($('#ytrelatedcache').is(':checked')) {
			 $('.ytcachetime').fadeIn();
		} else {
			 $('.ytcachetime').hide();
		}
	});
	//блок включения кэша в блоке "похожих записей" end

	//блок включения рейтинга begin
	$('#ytrating').change(function() {
		if ($('#ytrating').is(':checked')) {
			$('.ytratingchildtr').fadeIn();
		} else {
			$('.ytratingchildtr').hide();
		}
	});
	//блок включения рейтинга end

	//блок включения поиска begin
	$('#ytsearch').change(function() {
		if ($('#ytsearch').is(':checked')) {
			$('.ytsearchchildtr').fadeIn();
		} else {
			$('.ytsearchchildtr').hide();
		}
	});
	//блок включения поиска end

	//блок установки первой рекламы begin
	$('#ytad1').change(function() {
		if ($('#ytad1').is(':checked')) {
			$('.block1').fadeIn();
			if ($('#ytad1set option:selected').val() == 'РСЯ') {
				$('.trrsa').fadeIn();
				$('.trfox1').hide();
			}
			if ($('#ytad1set option:selected').val() == 'ADFOX') {
				$('.trrsa').hide();
				$('.trfox1').fadeIn();
			}
		} else {
			$('.block1').hide();
		}
	});
	$('#ytad1set').change(function() {
		if ($('#ytad1set option:selected').val() == 'РСЯ') {
			$('.trrsa').show();
			$('.trfox1').hide();
		}
		if ($('#ytad1set option:selected').val() == 'ADFOX') {
			$('.trrsa').hide();
			$('.trfox1').show();
		}
	});
	//блок установки первой рекламы end

	//блок установки второй рекламы begin
	$('#ytad2').change(function() {
		if ($('#ytad2').is(':checked')) {
			$('.block2').fadeIn();
			if ($('#ytad2set option:selected').val() == 'РСЯ') {
				$('.trrsa2').fadeIn();
				$('.trfox2').hide();
			}
			if ($('#ytad2set option:selected').val() == 'ADFOX') {
				$('.trrsa2').hide();
				$('.trfox2').fadeIn();
			}
		} else {
			$('.block2').hide();
		}
	});
	$('#ytad2set').change(function() {
		if ($('#ytad2set option:selected').val() == 'РСЯ') {
			$('.trrsa2').show();
			$('.trfox2').hide();
		}
		if ($('#ytad2set option:selected').val() == 'ADFOX') {
			$('.trrsa2').hide();
			$('.trfox2').show();
		}
	});
	//блок установки второй рекламы end

	//блок установки третьей рекламы begin
	$('#ytad3').change(function() {
		if ($('#ytad3').is(':checked')) {
			$('.block3').fadeIn();
			if ($('#ytad3set option:selected').val() == 'РСЯ') {
				$('.trrsa3').fadeIn();
				$('.trfox3').hide();
			}
			if ($('#ytad3set option:selected').val() == 'ADFOX') {
				$('.trrsa3').hide();
				$('.trfox3').fadeIn();
			}
		} else {
			$('.block3').hide();
		}
	});
	$('#ytad3set').change(function() {
		if ($('#ytad3set option:selected').val() == 'РСЯ') {
			$('.trrsa3').show();
			$('.trfox3').hide();
		}
		if ($('#ytad3set option:selected').val() == 'ADFOX') {
			$('.trrsa3').hide();
			$('.trfox3').show();
		}
	});
	//блок установки третьей рекламы end

	//блок установки четвертой рекламы begin
	$('#ytad4').change(function() {
		if ($('#ytad4').is(':checked')) {
			$('.block4').fadeIn();
			if ($('#ytad4set option:selected').val() == 'РСЯ') {
				$('.trrsa4').fadeIn();
				$('.trfox4').hide();
			}
			if ($('#ytad4set option:selected').val() == 'ADFOX') {
				$('.trrsa4').hide();
				$('.trfox4').fadeIn();
			}
		} else {
			$('.block4').hide();
		}
	});
	$('#ytad4set').change(function() {
		if ($('#ytad4set option:selected').val() == 'РСЯ') {
			$('.trrsa4').show();
			$('.trfox4').hide();
		}
		if ($('#ytad4set option:selected').val() == 'ADFOX') {
			$('.trrsa4').hide();
			$('.trfox4').show();
		}
	});
	//блок установки четвертой рекламы end

	//блок установки пятой рекламы begin
	$('#ytad5').change(function() {
		if ($('#ytad5').is(':checked')) {
			$('.block5').fadeIn();
			if ($('#ytad5set option:selected').val() == 'РСЯ') {
				$('.trrsa5').fadeIn();
				$('.trfox5').hide();
			}
			if ($('#ytad5set option:selected').val() == 'ADFOX') {
				$('.trrsa5').hide();
				$('.trfox5').fadeIn();
			}
		} else {
			$('.block5').hide();
		}
	});
	$('#ytad5set').change(function() {
		if ($('#ytad5set option:selected').val() == 'РСЯ') {
			$('.trrsa5').show();
			$('.trfox5').hide();
		}
		if ($('#ytad5set option:selected').val() == 'ADFOX') {
			$('.trrsa5').hide();
			$('.trfox5').show();
		}
	});
	//блок установки пятой рекламы end

	//блок выбора таксономий для исключения/включения begin
	$('#ytqueryselect').change(function() {
		if ($('#ytqueryselect option:selected').val() == 'Все таксономии, кроме исключенных') {
			$('.yttaxlisttr').fadeIn();
			$('.exclude-small-text').fadeIn();
			$('.thexclude').fadeIn();
			$('#excludespan').fadeIn();
		} else {
			$('.yttaxlisttr').hide();
			$('.exclude-small-text').hide();
			$('.thexclude').hide();
			$('#excludespan').hide();
		}
		if ($('#ytqueryselect option:selected').val() == 'Только указанные таксономии') {
			$('.ytaddtaxlisttr').fadeIn();
			$('.include-small-text').fadeIn();
			$('.thinclude').fadeIn();
			$('#includespan').fadeIn();
		} else {
			$('.ytaddtaxlisttr').hide();
			$('.include-small-text').hide();
			$('.thinclude').hide();
			$('#includespan').hide();
		}
	});
	//блок выбора таксономий для исключения/включения end

	//блок удаления шорткодов begin
	$('#ytexcludeshortcodes').change(function() {
		if ($('#ytexcludeshortcodes').is(':checked')) {
			$('.ytexcludeshortcodeslisttr').fadeIn();
		} else {
			$('.ytexcludeshortcodeslisttr').hide();
		}
	});
	//блок удаления шорткодов end

	//блок удаления тегов без контента begin
	$('#ytexcludetags').change(function() {
		if ($('#ytexcludetags').is(':checked')) {
			$('.ytexcludetagslisttr').fadeIn();
		} else {
			$('.ytexcludetagslisttr').hide();
		}
	});
	//блок удаления тегов без контента end

	//блок удаления тегов с контентом begin
	$('#ytexcludetags2').change(function() {
		if ($('#ytexcludetags2').is(':checked')) {
			$('.ytexcludetagslist2tr').fadeIn();
		} else {
			$('.ytexcludetagslist2tr').hide();
		}
	});
	//блок удаления тегов с контентом end

	//блок удаления точного контента begin
	$('#ytexcludecontent').change(function() {
		if ($('#ytexcludecontent').is(':checked')) {
			$('.ytexcludecontentlisttr').fadeIn();
		} else {
			$('.ytexcludecontentlisttr').hide();
		}
	});
	//блок удаления точного контента end


	//вывод полного списка rss-лент begin
	$('#showlistrss').click(function(){
		if ($('#allrss').is(':hidden')) {
			$('#allrss').fadeIn();
			$('#showlistrss').text('скрыть');
		} else {
			$('#allrss').hide();
			$('#showlistrss').text('показать');
		}
	})
	//вывод полного списка rss-лент end

	//управление табами begin
	$('ul.xyztabs__caption').on('click', 'li:not(.active)', function() {
		$(this)
			.addClass('active').siblings().removeClass('active')
			.closest('div.xyztabs').find('div.xyztabs__content').removeClass('active').eq($(this).index()).addClass('active');
			$('#yttab').val($('.xyztabs__caption li.active').text());
	});

	var tabIndex = window.location.hash.replace('#tab','')-1;
	if (tabIndex != -1) $('ul.xyztabs__caption li').eq(tabIndex).click();

	$('a[href="#tab"]').click(function() {
		var tabIndex = $(this).attr('href').replace(/(.*)#tab/, '')-1;
		$('ul.xyztabs__caption li').eq(tabIndex).click();
	});
	//управление табами end

	//управление закрытием рекламных блоков по времени begin
	checkExpTime();

	$('#close-donat').on('click',function(e) {
		localStorage.setItem('yt-close-donat', 'yes');
		$('#donat').slideUp(300);
		$('#restore-hide-blocks').show(300);
		setExpTime();
	});

	$('#close-about').on('click',function(e) {
		localStorage.setItem('yt-close-about', 'yes');
		$('#about').slideUp(300);
		$('#restore-hide-blocks').show(300);
		setExpTime();
	});

	$('#restore-hide-blocks').on('click',function(e) {
		localStorage.removeItem('yt-time');
		localStorage.removeItem('yt-close-donat');
		localStorage.removeItem('yt-close-about');
		$('#restore-hide-blocks').hide(300);
		$('#donat').slideDown(300);
		$('#about').slideDown(300);
	});

	function setExpTime() {
		var limit = 90 * 24 * 60 * 60 * 1000; // 3 месяца
		var time = localStorage.getItem('yt-time');
		if (time === null) {
			localStorage.setItem('yt-time', +new Date());
		} else if(+new Date() - time > limit) {
			localStorage.removeItem('yt-time');
			localStorage.removeItem('yt-close-donat');
			localStorage.removeItem('yt-close-about');
			localStorage.setItem('yt-time', +new Date());
		}
	}

	function checkExpTime() {
		var limit = 90 * 24 * 60 * 60 * 1000; // 3 месяца
		var time = localStorage.getItem('yt-time');
		if (time === null) {

		} else if(+new Date() - time > limit) {
			localStorage.removeItem('yt-time');
			localStorage.removeItem('yt-close-donat');
			localStorage.removeItem('yt-close-about');
		}
	}
	//управление закрытием рекламных блоков по времени end

	//скрипт добавления тегов удаления без контента begin
	var str = $('#tags-list').val();
	var whitelist = str.split(',');

	var input = document.querySelector('input[name="ytexcludetagslist-input"]'),
		tagify = new Tagify(input, {
			whitelist: whitelist,
			enforceWhitelist: true,
			dropdown: {
				maxItems: 20,
				classname: 'tags-look',
				enabled: 0,
				closeOnSelect: false,
			}
		})

	tagify
		.on('add', onAddTag)
		.on('remove', onRemoveTag)
		.on('invalid', onInvalidTag)

	function onAddTag(e) {

		$('tag').data('title', $('tag').attr('title')).removeAttr('title');
		var str = tagify.DOM.originalInput.value;
		var temp = str.replace(/{"value":"/g, '');
		temp = temp.replace(/"}/g, '');
		temp = temp.replace(/"}/g, '');
		temp = temp.replace(/\[/g, '');
		temp = temp.replace(/\]/g, '');

		$('#ytexcludetagslist').val(temp);
	}

	function onRemoveTag(e) {
		if ($('tags').hasClass('tagify--focus')) {
			tagify.dropdown.hide.call(tagify);
			$('tags').removeClass('tagify--focus');
		}

		var str = tagify.DOM.originalInput.value;
		var temp = str.replace(/{"value":"/g, '');
		temp = temp.replace(/"}/g, '');
		temp = temp.replace(/"}/g, '');
		temp = temp.replace(/\[/g, '');
		temp = temp.replace(/\]/g, '');

		$('#ytexcludetagslist').val(temp);

	}

	function onInvalidTag(e) {
		tagify.dropdown.show.call(tagify);
	}

	$('tag').data('title', $('tag').attr('title')).removeAttr('title');
	//скрипт добавления тегов удаления без контента end

	//скрипт добавления тегов удаления с контентом begin
	var str = $('#tags-list2').val();
	var whitelist = str.split(',');

	var input = document.querySelector('input[name="ytexcludetagslist-input2"]'),
		tagify2 = new Tagify(input, {
			whitelist: whitelist,
			enforceWhitelist: true,
			dropdown: {
				maxItems: 20,
				classname: 'tags-look',
				enabled: 0,
				closeOnSelect: false,
			}
		})

	tagify2
		.on('add', onAddTag2)
		.on('remove', onRemoveTag2)
		.on('invalid', onInvalidTag2)

	function onAddTag2(e) {

		$('tag').data('title', $('tag').attr('title')).removeAttr('title');
		var str = tagify2.DOM.originalInput.value;
		var temp = str.replace(/{"value":"/g, '');
		temp = temp.replace(/"}/g, '');
		temp = temp.replace(/"}/g, '');
		temp = temp.replace(/\[/g, '');
		temp = temp.replace(/\]/g, '');

		$('#ytexcludetagslist2').val(temp);
	}

	function onRemoveTag2(e) {
		if ($('tags').hasClass('tagify--focus')) {
			tagify2.dropdown.hide.call(tagify2);
			$('tags').removeClass('tagify--focus');
		}

		var str = tagify2.DOM.originalInput.value;
		var temp = str.replace(/{"value":"/g, '');
		temp = temp.replace(/"}/g, '');
		temp = temp.replace(/"}/g, '');
		temp = temp.replace(/\[/g, '');
		temp = temp.replace(/\]/g, '');

		$('#ytexcludetagslist2').val(temp);

	}

	function onInvalidTag2(e) {
		tagify2.dropdown.show.call(tagify2);
	}

	$('tag').data('title', $('tag').attr('title')).removeAttr('title');
	//скрипт добавления тегов удаления с контентом end

	//ajax-подгрузка терминов таксономий begin
	var loaded_disallows = false;

	function load_disallows() {
		if ( loaded_disallows )
			return;
		loaded_disallows = true;

		var finished_taxonomies = {},
			term_indices = {};

		function load_disallow(taxonomy) {
			if (taxonomy in finished_taxonomies)
				return;
			var display = $('#exclude_' + taxonomy);

			if (display.find('.loading').length)
				return;

			if (taxonomy in term_indices)
				term_indices[taxonomy] = term_indices[taxonomy] + 100;
			else
				term_indices[taxonomy] = 0;
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'yturbo_display_exclude_terms',
					taxonomy: taxonomy,
					offset: term_indices[taxonomy],
					'_ajax_nonce': $('#yturbo_display_exclude_terms-nonce').val()
				},
				beforeSend: function() {
					display.append(loading)
				},
				success: function(html) {
					display.find('.loading').remove();
					if ('<li>:(</li>' == html) {
						finished_taxonomies[taxonomy] = true;
						return;
					}
					display.append(html);
				},
				dataType: 'html'
			});
		}

		$('.exclude_terms').each(function() {
			var id = jQuery(this).attr('id'),
				taxonomy;
			if (!id)
				return;

			taxonomy = id.replace('exclude_', '');

			load_disallow(taxonomy);
			$('#exclude_' + taxonomy).parent('.list_terms_scroll_wrapper').scroll(function() {
				var parent = $(this),
					content = parent.children('ul');
				if (parent.scrollTop() + parent.height() > content.height() - 10)
					load_disallow(taxonomy);
			})
		})

	}

	load_disallows();
	//ajax-подгрузка терминов таксономий end

})


// TO-DO: все остальные кривые скрипты ниже - переделать!


String.prototype.replaceAll = function(search, replace){
	return this.split(search).join(replace);
}

jQuery(document).ready(function($) {
	var temp = jQuery('#ytnetw').val();
	if (temp!==undefined) {
		if (temp.indexOf('facebook') !== -1) {jQuery('#facebook').attr('checked', 'checked');}
		if (temp.indexOf('vkontakte') !== -1) {jQuery('#vkontakte').attr('checked', 'checked');}
		if (temp.indexOf('twitter') !== -1) {jQuery('#twitter').attr('checked', 'checked');}
		if (temp.indexOf('odnoklassniki') !== -1) {jQuery('#odnoklassniki').attr('checked', 'checked');}
		if (temp.indexOf('telegram') !== -1) {jQuery('#telegram').attr('checked', 'checked');}
	}
});
jQuery(function() {
	jQuery('#facebook').click(function(){
		if (jQuery('#ytnetw').val().indexOf('facebook') == -1) {
			temp = jQuery('#ytnetw').val()  + 'facebook' + ',';
			jQuery('#ytnetw').val(temp);
			jQuery('#ytnetwspan').val(temp);
		} else {
			temp = jQuery('#ytnetw').val();
			temp = temp.replaceAll('facebook,', '');
			jQuery('#ytnetw').val(temp);
			jQuery('#ytnetwspan').val(temp);
		}
	})
});
jQuery(function() {
	jQuery('#vkontakte').click(function(){
		if (jQuery('#ytnetw').val().indexOf('vkontakte') == -1) {
			temp = jQuery('#ytnetw').val()  + 'vkontakte' + ',';
			jQuery('#ytnetw').val(temp);
			jQuery('#ytnetwspan').val(temp);
		} else {
			temp = jQuery('#ytnetw').val();
			temp = temp.replaceAll('vkontakte,', '');
			jQuery('#ytnetw').val(temp);
			jQuery('#ytnetwspan').val(temp);
		}
	})
});
jQuery(function() {
	jQuery('#twitter').click(function(){
		if (jQuery('#ytnetw').val().indexOf('twitter') == -1) {
			temp = jQuery('#ytnetw').val()  + 'twitter' + ',';
			jQuery('#ytnetw').val(temp);
			jQuery('#ytnetwspan').val(temp);
		} else {
			temp = jQuery('#ytnetw').val();
			temp = temp.replaceAll('twitter,', '');
			jQuery('#ytnetw').val(temp);
			jQuery('#ytnetwspan').val(temp);
		}
	})
});
jQuery(function() {
	jQuery('#odnoklassniki').click(function(){
		if (jQuery('#ytnetw').val().indexOf('odnoklassniki') == -1) {
			temp = jQuery('#ytnetw').val()  + 'odnoklassniki' + ',';
			jQuery('#ytnetw').val(temp);
			jQuery('#ytnetwspan').val(temp);
		} else {
			temp = jQuery('#ytnetw').val();
			temp = temp.replaceAll('odnoklassniki,', '');
			jQuery('#ytnetw').val(temp);
			jQuery('#ytnetwspan').val(temp);
		}
	})
});
jQuery(function() {
	jQuery('#telegram').click(function(){
		if (jQuery('#ytnetw').val().indexOf('telegram') == -1) {
			temp = jQuery('#ytnetw').val()  + 'telegram' + ',';
			jQuery('#ytnetw').val(temp);
			jQuery('#ytnetwspan').val(temp);
		} else {
			temp = jQuery('#ytnetw').val();
			temp = temp.replaceAll('telegram,', '');
			jQuery('#ytnetw').val(temp);
			jQuery('#ytnetwspan').val(temp);
		}
	})
});

jQuery(document).ready(function($) {
	var temp2 = jQuery('#ytfeedbacknetw').val();
	if (temp2!==undefined) {
		if (temp2.indexOf('call,') !== -1) {jQuery('#feedbackcall').attr('checked', 'checked');jQuery('#ytfeedbackcontacts [value="feedbackcall"]').removeAttr('disabled');}
		if (temp2.indexOf('callback') !== -1) {jQuery('#feedbackcallback').attr('checked', 'checked');jQuery('#ytfeedbackcontacts [value="feedbackcallback"]').removeAttr('disabled');}
		if (temp2.indexOf('chat') !== -1) {jQuery('#feedbackchat').attr('checked', 'checked');jQuery('#ytfeedbackcontacts [value="feedbackchat"]').removeAttr('disabled');}
		if (temp2.indexOf('mail') !== -1) {jQuery('#feedbackmail').attr('checked', 'checked');jQuery('#ytfeedbackcontacts [value="feedbackmail"]').removeAttr('disabled');}
		if (temp2.indexOf('vkontakte') !== -1) {jQuery('#feedbackvkontakte').attr('checked', 'checked');jQuery('#ytfeedbackcontacts [value="feedbackvkontakte"]').removeAttr('disabled');}
		if (temp2.indexOf('odnoklassniki') !== -1) {jQuery('#feedbackodnoklassniki').attr('checked', 'checked');jQuery('#ytfeedbackcontacts [value="feedbackodnoklassniki"]').removeAttr('disabled');}
		if (temp2.indexOf('twitter') !== -1) {jQuery('#feedbacktwitter').attr('checked', 'checked');jQuery('#ytfeedbackcontacts [value="feedbacktwitter"]').removeAttr('disabled');}
		if (temp2.indexOf('facebook') !== -1) {jQuery('#feedbackfacebook').attr('checked', 'checked');jQuery('#ytfeedbackcontacts [value="feedbackfacebook"]').removeAttr('disabled');}
		if (temp2.indexOf('viber') !== -1) {jQuery('#feedbackviber').attr('checked', 'checked');jQuery('#ytfeedbackcontacts [value="feedbackviber"]').removeAttr('disabled');}
		if (temp2.indexOf('whatsapp') !== -1) {jQuery('#feedbackwhatsapp').attr('checked', 'checked');jQuery('#ytfeedbackcontacts [value="feedbackwhatsapp"]').removeAttr('disabled');}
		if (temp2.indexOf('telegram') !== -1) {jQuery('#feedbacktelegram').attr('checked', 'checked');jQuery('#ytfeedbackcontacts [value="feedbacktelegram"]').removeAttr('disabled');}
	}
});
jQuery(function() {
	jQuery('#feedbackcall').click(function(){
		jQuery('#ytfeedbackcontacts [value="myselect"]').attr('selected', 'selected');
		jQuery('.ytfeedbackcalltr,.ytfeedbackcallbacktr,.ytfeedbackchattr,.ytfeedbackmailtr,.ytfeedbackvkontaktetr,.ytfeedbackodnoklassnikitr,.ytfeedbacktwittertr,.ytfeedbackfacebooktr,.ytfeedbackvibertr,.ytfeedbackwhatsapptr,.ytfeedbacktelegramtr').hide();
		if (jQuery('#ytfeedbacknetw').val().indexOf('call,') == -1) {
			temp2 = jQuery('#ytfeedbacknetw').val()  + 'call' + ',';
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackcall"]').removeAttr('disabled');
		} else {
			temp2 = jQuery('#ytfeedbacknetw').val();
			temp2 = temp2.replaceAll('call,', '');
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackcall"]').attr('disabled', 'disabled');
		}
	})
});
jQuery(function() {
	jQuery('#feedbackcallback').click(function(){
		jQuery('#ytfeedbackcontacts [value="myselect"]').attr('selected', 'selected');
		jQuery('.ytfeedbackcalltr,.ytfeedbackcallbacktr,.ytfeedbackchattr,.ytfeedbackmailtr,.ytfeedbackvkontaktetr,.ytfeedbackodnoklassnikitr,.ytfeedbacktwittertr,.ytfeedbackfacebooktr,.ytfeedbackvibertr,.ytfeedbackwhatsapptr,.ytfeedbacktelegramtr').hide();
		if (jQuery('#ytfeedbacknetw').val().indexOf('callback') == -1) {
			temp2 = jQuery('#ytfeedbacknetw').val()  + 'callback' + ',';
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackcallback"]').removeAttr('disabled');
		} else {
			temp2 = jQuery('#ytfeedbacknetw').val();
			temp2 = temp2.replaceAll('callback,', '');
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackcallback"]').attr('disabled', 'disabled');
		}
	})
});
jQuery(function() {
	jQuery('#feedbackchat').click(function(){
		jQuery('#ytfeedbackcontacts [value="myselect"]').attr('selected', 'selected');
		jQuery('.ytfeedbackcalltr,.ytfeedbackcallbacktr,.ytfeedbackchattr,.ytfeedbackmailtr,.ytfeedbackvkontaktetr,.ytfeedbackodnoklassnikitr,.ytfeedbacktwittertr,.ytfeedbackfacebooktr,.ytfeedbackvibertr,.ytfeedbackwhatsapptr,.ytfeedbacktelegramtr').hide();
		if (jQuery('#ytfeedbacknetw').val().indexOf('chat') == -1) {
			temp2 = jQuery('#ytfeedbacknetw').val()  + 'chat' + ',';
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackchat"]').removeAttr('disabled');
		} else {
			temp2 = jQuery('#ytfeedbacknetw').val();
			temp2 = temp2.replaceAll('chat,', '');
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackchat"]').attr('disabled', 'disabled');
		}
	})
});
jQuery(function() {
	jQuery('#feedbackmail').click(function(){ 
		jQuery('#ytfeedbackcontacts [value="myselect"]').attr('selected', 'selected');
		jQuery('.ytfeedbackcalltr,.ytfeedbackcallbacktr,.ytfeedbackchattr,.ytfeedbackmailtr,.ytfeedbackvkontaktetr,.ytfeedbackodnoklassnikitr,.ytfeedbacktwittertr,.ytfeedbackfacebooktr,.ytfeedbackvibertr,.ytfeedbackwhatsapptr,.ytfeedbacktelegramtr').hide();
		if (jQuery('#ytfeedbacknetw').val().indexOf('mail') == -1) {
			temp2 = jQuery('#ytfeedbacknetw').val()  + 'mail' + ',';
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackmail"]').removeAttr('disabled');
		} else {
			temp2 = jQuery('#ytfeedbacknetw').val();
			temp2 = temp2.replaceAll('mail,', '');
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackmail"]').attr('disabled', 'disabled');
		}
	})
});
jQuery(function() {
	jQuery('#feedbackvkontakte').click(function(){
		jQuery('#ytfeedbackcontacts [value="myselect"]').attr('selected', 'selected');
		jQuery('.ytfeedbackcalltr,.ytfeedbackcallbacktr,.ytfeedbackchattr,.ytfeedbackmailtr,.ytfeedbackvkontaktetr,.ytfeedbackodnoklassnikitr,.ytfeedbacktwittertr,.ytfeedbackfacebooktr,.ytfeedbackvibertr,.ytfeedbackwhatsapptr,.ytfeedbacktelegramtr').hide();
		if (jQuery('#ytfeedbacknetw').val().indexOf('vkontakte') == -1) {
			temp2 = jQuery('#ytfeedbacknetw').val()  + 'vkontakte' + ',';
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackvkontakte"]').removeAttr('disabled');
		} else {
			temp2 = jQuery('#ytfeedbacknetw').val();
			temp2 = temp2.replaceAll('vkontakte,', '');
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackvkontakte"]').attr('disabled', 'disabled');
		}
	})
});
jQuery(function() {
	jQuery('#feedbackodnoklassniki').click(function(){
		jQuery('#ytfeedbackcontacts [value="myselect"]').attr('selected', 'selected');
		jQuery('.ytfeedbackcalltr,.ytfeedbackcallbacktr,.ytfeedbackchattr,.ytfeedbackmailtr,.ytfeedbackvkontaktetr,.ytfeedbackodnoklassnikitr,.ytfeedbacktwittertr,.ytfeedbackfacebooktr,.ytfeedbackvibertr,.ytfeedbackwhatsapptr,.ytfeedbacktelegramtr').hide();
		if (jQuery('#ytfeedbacknetw').val().indexOf('odnoklassniki') == -1) {
			temp2 = jQuery('#ytfeedbacknetw').val()  + 'odnoklassniki' + ',';
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackodnoklassniki"]').removeAttr('disabled');
		} else {
			temp2 = jQuery('#ytfeedbacknetw').val();
			temp2 = temp2.replaceAll('odnoklassniki,', '');
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackodnoklassniki"]').attr('disabled', 'disabled');
		}
	})
});
jQuery(function() {
	jQuery('#feedbacktwitter').click(function(){
		jQuery('#ytfeedbackcontacts [value="myselect"]').attr('selected', 'selected');
		jQuery('.ytfeedbackcalltr,.ytfeedbackcallbacktr,.ytfeedbackchattr,.ytfeedbackmailtr,.ytfeedbackvkontaktetr,.ytfeedbackodnoklassnikitr,.ytfeedbacktwittertr,.ytfeedbackfacebooktr,.ytfeedbackvibertr,.ytfeedbackwhatsapptr,.ytfeedbacktelegramtr').hide();
		if (jQuery('#ytfeedbacknetw').val().indexOf('twitter') == -1) {
			temp2 = jQuery('#ytfeedbacknetw').val()  + 'twitter' + ',';
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbacktwitter"]').removeAttr('disabled');
		} else {
			temp2 = jQuery('#ytfeedbacknetw').val();
			temp2 = temp2.replaceAll('twitter,', '');
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbacktwitter"]').attr('disabled', 'disabled');
		}
	})
});
jQuery(function() {
	jQuery('#feedbackfacebook').click(function(){
		jQuery('#ytfeedbackcontacts [value="myselect"]').attr('selected', 'selected');
		jQuery('.ytfeedbackcalltr,.ytfeedbackcallbacktr,.ytfeedbackchattr,.ytfeedbackmailtr,.ytfeedbackvkontaktetr,.ytfeedbackodnoklassnikitr,.ytfeedbacktwittertr,.ytfeedbackfacebooktr,.ytfeedbackvibertr,.ytfeedbackwhatsapptr,.ytfeedbacktelegramtr').hide();
		if (jQuery('#ytfeedbacknetw').val().indexOf('facebook') == -1) {
			temp2 = jQuery('#ytfeedbacknetw').val()  + 'facebook' + ',';
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackfacebook"]').removeAttr('disabled');
		} else {
			temp2 = jQuery('#ytfeedbacknetw').val();
			temp2 = temp2.replaceAll('facebook,', '');
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackfacebook"]').attr('disabled', 'disabled');
		}
	})
});
jQuery(function() {
	jQuery('#feedbackviber').click(function(){ 
		jQuery('#ytfeedbackcontacts [value="myselect"]').attr('selected', 'selected');
		jQuery('.ytfeedbackcalltr,.ytfeedbackcallbacktr,.ytfeedbackchattr,.ytfeedbackmailtr,.ytfeedbackvkontaktetr,.ytfeedbackodnoklassnikitr,.ytfeedbacktwittertr,.ytfeedbackfacebooktr,.ytfeedbackvibertr,.ytfeedbackwhatsapptr,.ytfeedbacktelegramtr').hide();
		if (jQuery('#ytfeedbacknetw').val().indexOf('viber') == -1) {
			temp2 = jQuery('#ytfeedbacknetw').val()  + 'viber' + ',';
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackviber"]').removeAttr('disabled');
		} else {
			temp2 = jQuery('#ytfeedbacknetw').val();
			temp2 = temp2.replaceAll('viber,', '');
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackviber"]').attr('disabled', 'disabled');
		}
	})
});
jQuery(function() {
	jQuery('#feedbackwhatsapp').click(function(){
		jQuery('#ytfeedbackcontacts [value="myselect"]').attr('selected', 'selected');
		jQuery('.ytfeedbackcalltr,.ytfeedbackcallbacktr,.ytfeedbackchattr,.ytfeedbackmailtr,.ytfeedbackvkontaktetr,.ytfeedbackodnoklassnikitr,.ytfeedbacktwittertr,.ytfeedbackfacebooktr,.ytfeedbackvibertr,.ytfeedbackwhatsapptr,.ytfeedbacktelegramtr').hide();
		if (jQuery('#ytfeedbacknetw').val().indexOf('whatsapp') == -1) {
			temp2 = jQuery('#ytfeedbacknetw').val()  + 'whatsapp' + ',';
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackwhatsapp"]').removeAttr('disabled');
		} else {
			temp2 = jQuery('#ytfeedbacknetw').val();
			temp2 = temp2.replaceAll('whatsapp,', '');
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbackwhatsapp"]').attr('disabled', 'disabled');
		}
	})
});
jQuery(function() {
	jQuery('#feedbacktelegram').click(function(){
		jQuery('#ytfeedbackcontacts [value="myselect"]').attr('selected', 'selected');
		jQuery('.ytfeedbackcalltr,.ytfeedbackcallbacktr,.ytfeedbackchattr,.ytfeedbackmailtr,.ytfeedbackvkontaktetr,.ytfeedbackodnoklassnikitr,.ytfeedbacktwittertr,.ytfeedbackfacebooktr,.ytfeedbackvibertr,.ytfeedbackwhatsapptr,.ytfeedbacktelegramtr').hide();
		if (jQuery('#ytfeedbacknetw').val().indexOf('telegram') == -1) {
			temp2 = jQuery('#ytfeedbacknetw').val()  + 'telegram' + ',';
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbacktelegram"]').removeAttr('disabled');
		} else {
			temp2 = jQuery('#ytfeedbacknetw').val();
			temp2 = temp2.replaceAll('telegram,', '');
			jQuery('#ytfeedbacknetw').val(temp2);
			jQuery('#ytfeedbacknetwspan').val(temp2);
			jQuery('#ytfeedbackcontacts [value="feedbacktelegram"]').attr('disabled', 'disabled');
		}
	})
});

