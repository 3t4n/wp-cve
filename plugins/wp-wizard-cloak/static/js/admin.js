/**
 * plugin admin area javascript
 */
(function($){$(function () {
	if ( ! $('body.pmlc_plugin').length) return; // do not execute any code if we are not on plugin page
	
	// help icons
	$('a.help').tipsy({
		gravity: function() {
			var ver = 'n';
			if ($(document).scrollTop() < $(this).offset().top - $('.tipsy').height() - 2) {
				ver = 's';
			}
			var hor = '';
			if ($(this).offset().left + $('.tipsy').width() < $(window).width() + $(document).scrollLeft()) {
				hor = 'w';
			} else if ($(this).offset().left - $('.tipsy').width() > $(document).scrollLeft()) {
				hor = 'e';
			}
	        return ver + hor;
	    },
		live: true,
		html: true,
		opacity: 1
	}).live('click', function () {
		return false;
	}).each(function () { // fix tipsy title for IE
		$(this).attr('original-title', $(this).attr('title'));
		$(this).removeAttr('title');
	});
	
	// autoselect input content on click
	$('input.selectable').live('click', function () {
		$(this).select();
	});
	
	// swither show/hide logic
	$('input.switcher:checkbox').change(function () {
		var anim_options = $(this).attr('checked') ? 'fadeIn' : 'hide';
		var $targets = $('.switcher-target-' + $(this).attr('id'))[anim_options]();
		if ( ! $(this).attr('checked')) {
			$targets.find('.clear-on-switch').add($targets.filter('.clear-on-switch')).val('');
		}
	}).change();
	
	// toggler show/hide logic
	$('.toggler').click(function () {
		$(this).toggleClass('toggled');
		$(this).find('span.indicator').html($(this).hasClass('toggled') ? '[-]' : '[+]');
		$('input[name="toggler-target-' + $(this).attr('id') + '"]').val($(this).hasClass('toggled') ? '1' : '0');
		$('.toggler-target-' + $(this).attr('id')).toggle();
	});
	
	// meter controller
	$('.meter').each(function () {
		var $input = $(this).find('input[type="text"]');
		$input.after('<input type="button" class="up" /><input type="button" class="down" />');
		$(this).find('input[type="button"].up').click(function () {
			var val = parseInt($input.val()) || 1;
			$input.val(val + 1);
		});
		$(this).find('input[type="button"].down').click(function () {
			var val = parseInt($input.val()) || 1;
			$input.val(val > 1 ? val - 1 : 1);
		});
	});
	
	// colorpicker
	if ($('.color-picker').length) {
		$('.color-picker').each(function () {
			var $this = $(this);
			var $input = $('#' + $this.attr('for'));
			
			$this.click(function () {
				$('#__farbtastic-picker').remove();
				$('<div id="__farbtastic-picker" />').css({
					position: 'absolute', 
					backgroundColor: '#ffffff',
					border: '1px solid #dcdcdc'
				}).insertAfter($this);
				$('#__farbtastic-picker').farbtastic(function (color) {
					$this.css('backgroundColor', color);
					$input.val(color);
				}).get(0).farbtastic.setColor($input.val());
				return false;
			});
			$input.change(function () {
				var value = $(this).val();
				if ('' != value) {
					$this.css('backgroundColor', value);
				} else {
					$this.css('backgroundColor', '#ffffff');
				}
			}).change();
		});
		$('body').click(function () {
			$('#__farbtastic-picker').remove();
		});
	}
	
	// datepicker
	$('input.datepicker').addClass('selectable').datepicker({
		dateFormat: 'yy-mm-dd',
		showAnim: 'fadeIn',
		showOptions: 'fast'
	}).bind('change', function () {
		var selectedDate = $(this).val();
		var instance = $(this).data('datepicker');
		var date = null;
		if ('' != selectedDate) {
			date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
		}
		if ($(this).hasClass('range-from')) {
			$(this).parent().find('.datepicker.range-to').datepicker("option", "minDate", date);
		}
		if ($(this).hasClass('range-to')) {
			$(this).parent().find('.datepicker.range-from').datepicker("option", "maxDate", date);
		}
	}).change();
	$('.ui-datepicker').hide(); // fix: make sure datepicker doesn't break wordpress layout upon initialization 
	
	// input tags with title
	$('input[title]').each(function () {
		var $this = $(this);
		$this.bind('focus', function () {
			if ('' == $(this).val() || $(this).val() == $(this).attr('title')) {
				$(this).removeClass('note').val('');
			}
		}).bind('blur', function () {
			if ('' == $(this).val() || $(this).val() == $(this).attr('title')) {
				$(this).addClass('note').val($(this).attr('title'));
			}
		}).blur();
		$this.parents('form').bind('submit', function () {
			if ($this.val() == $this.attr('title')) {
				$this.val('');
			}
		});
	});
	
	// auto submit link edit form on preset selection
	$('form[name="link"] select[name="load_preset"]').bind('change', function () {
		$(this).parents('form').submit();
	});
	
	// autofill slug when never changed
	$('form[name="link"]').each(function () {
		var $form = $(this);
		var $slug = $form.find('input[name="slug"]');
		var $name = $form.find('input[name="name"]');
		if ('' == $form.find('input[name="id"]').val()) {
			var autoSlug = function () {
				$slug.val($(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/, ''));
			};
			$name.bind('keyup', autoSlug).bind('change', autoSlug);
			$slug.bind('change', function () {
				$name.unbind('keyup', autoSlug).unbind('change', autoSlug);
			});
		}
	});
	
	// referer destination & advanced options show/hide
	$('form[name="link"] select[name="redirect_type"]').bind('change', function () {
		// referer
		var anim_referer = 'REFERER_MASK' == $(this).val() ? 'fadeIn' : 'hide';
		$(this).parents('.form-table').find('.destination-set-container')[anim_referer]();
		// options
		var $options = $(this).parents('form').find('.form-table.options');
		var anim_options = 'META_REFRESH' == $(this).val() || 'FRAME' == $(this).val() || 'JAVASCRIPT' == $(this).val() || 'REFERER_MASK' == $(this).val() ? 'fadeIn' : 'hide';
		$options.find('tr.tracking-code')[anim_options]();
	}).change();
	$('form[name="link"] input[name="destination_type"]').bind('click', function() {
		var $container = $(this).parents('.destination-type-container');
		$('.destination-type-container').not($container).removeClass('selected').find('.destination-set-container').hide();
		$(this).parents('.destination-type-container').addClass('selected').find('.destination-set-container').show();
	}).filter(':checked').click();
	
	// destination set button on add/edit link page
	var $modal = $('<div id="__modal"></div>').dialog({
		autoOpen: false,
		modal: true,
		title: 'Destination Set',
		width: 480,
		minWidth: 400
	});
	$('a.destination-set').tipsy({
		gravity: 'w',
		live: true,
		html: true,
		opacity: 1,
		fallback: 'Click to specify destination set'
	}).live('click', function () {
		__destination_tag = this; // global variable
		var modalOpen = function (url, data) {
			var data = data || '';
			$modal.addClass('loading').empty().dialog('open');
			$(window).resize(); // FIX: for wordpress 3.1 to redraw overlay to avoid scrolls when window size has been reduced
			$.post(url, data, function (response) {
				$modal.removeClass('loading').html(response).dialog('option', 'position', 'center');
				var $form = $modal.find('form[name="destination-set"]');
				$form.find('input:reset').click(function () {
					$modal.dialog('close');
				});
				$form.each(function () {
					if ( ! $(this).attr('action')) {
						$(this).attr('action', url);
					}
				});
				$form.bind('submit', function () {
					modalOpen($(this).attr('action'), $(this).serialize());
					return false;
				});
				$form.find('input[type="submit"]').bind('click', function () {
					$(this).clone().attr('type', 'hidden').appendTo($form);
				});
				
				// load url list from file
				var $form_upload = $modal.find('form[name="destination-set-upload"]');
				$form_upload.ajaxStart(function(){
					$(this).find('input').attr('disabled', true);
					$(this).find(".loading").show();
				}).ajaxComplete(function(){
					$(this).find('input').attr('disabled', false);
					
					$(this).find(".loading").hide();
				});
				$form_upload.error = function (msg) {
					this.find('div.error').remove();
					msg && this.prepend('<div class="error"><p>' + msg + '</p></div>');
				};
				$form_upload.submit(function () {
					$.ajaxFileUpload({
						url: $form_upload.attr('action'),
						secureuri: false,
						fileElementId: 'destination-set-upload-file',
						dataType: 'json',
						success: function (data, status) {
							if(data.error) {
								$form_upload.error(data.error);
							} else {
								$form_upload.error(); // clear error
								$form_upload.find('input[type="file"]').val('');
								var $template = $form.find('.form-table.destination-set').find('tr.template');
								for (var i = 0; i < data.urls.length; i++) {
									$template.clone().insertBefore($template).removeClass('template').find('input[name="url[]"]').val(data.urls[i].replace('&amp;', '&'));
								}
								$form.find('.form-table.destination-set').find('input[name="url[]"]').each(function () {
									if ('' == $(this).val() || 'http://' == $(this).val()) {
										$(this).parents('tr').first().not('.template').find('td.action.remove a').click();
									}
								}); // remove empty urls
								$form.find('.form-table.destination-set .action.auto a').click(); // spread weights equally
							}
							$modal.dialog('option', 'position', 'center');
						},
						error: function (data, status, e) {
							$form_upload.error(e);
						}
					});
					return false;
				});
			});
		};
		modalOpen($(this).attr('href'));
		return false;
	}).each(function () { // fix tipsy title for IE
		$(this).attr('original-title', $(this).attr('title'));
		$(this).removeAttr('title');
	});
	
	// destination edit page
	$('.form-table a.action[href="#add"]').live('click', function () {
		var $template = $(this).parents('table').first().find('tr.template');
		$template.clone().insertBefore($template).css('display', 'none').removeClass('template').fadeIn();
		return false;
	});
	$('.form-table .action.remove a').live('click', function () {
		$(this).parents('tr').first().remove();
		return false;
	});
	$('.form-table.destination-set .action.auto a').live('click', function () {
		var $destinations = $(this).parents('.form-table.destination-set').first().find('.form-field').not('.template');
		var count = $destinations.length;
		var weight = 100;
		$destinations.each(function () {
			var w = Math.round(weight / count-- * 100) / 100; weight -= w;
			$(this).find('input[name="weight[]"]').val(w);
		});
		return false;
	});
	
	// jqplot on stats page
	$.jqplot.DateTickFormatter.noTimezoneAdjustment = true;
	$('.graph').each(function () {
		$.jqplot($(this).attr('id'), [$(this).data('plotData')], {
			gridPadding: {right:35},
			highlighter: {
				show: true,
				tooltipAxes: 'xy',
				formatString: '%s - <b>%d</b> clicks'
			},
			axes: {
				xaxis: {
		            renderer: $.jqplot.DateAxisRenderer, 
		            tickOptions: {formatString:  $(this).data('plotXTickFormat')},
		            ticks: $(this).data('plotXTicks'),
		            pad: 0.0
		        },
		    	yaxis: {
		        	tickOptions: {formatter: $.jqplot.SeparatorTickFormatter, formatString: '%d'},
		        	tickInterval: $(this).data('plotYMax') / 4,
		        	max: $(this).data('plotYMax'),
		        	min: 0
		        }
	        }
		});
	});
	
	// edit keyword form dynamics
	$('form[name="keyword"] input[name="pass_post_id"]').change(function () {
		$(this).parents('form').find('input[name="post_id_param"]').attr('disabled', ! $(this).attr('checked'));
	}).change();

});})(jQuery);