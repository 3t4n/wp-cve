function yrmBackend() {

}

yrmBackend.prototype.init = function() {
	
	//this.sortable();
	this.deleteAjaxRequest();
	this.typeDeleteAjaxRequest();
	this.accordionContent();
	this.proOptionsWrapper();
	this.select2();
	this.changeEasings();
	this.changeSwitch();
	this.changeFarStatus();
	this.changeContentGradientColor();
	this.changeTextDecoration();
	this.changeButtonBorderColor();
	this.changeButtonBorderWidth();
	this.changeButtonBoxShadow();
	this.changeButtonTitle();
	this.changeButtonToolTip();
	this.support();

	this.changeContentGradientHeight();
	this.changeContentGradientPosition();
	this.switchContentGradientColor();
	this.switchInlineButton();
	this.swicthEnableIcon();
	this.changeIconDimension();
	this.toggleTabs();

	this.export();
	this.importData();
	this.editor();

	/*copy to clipboard*/
	this.copySortCode();

	this.multipleChoiceButton();
	this.chnageDimensionsMode();
	this.chnageAutoModePadding();
	this.livePreviewDraggble();
	this.promotionalVideo();
	this.imageUpload();
	this.livePreviewToggle();
	this.opacity();
	this.changeCursor();
	this.changeOrder();

	this.farUpgradeButton();
	this.manageReadMores();
	this.saveMessageRemove();

	this.accordionTypeSwitcher()
};

yrmBackend.prototype.accordionTypeSwitcher = function () {
	var keysId = ['content', 'post'];
	var handler =
	jQuery('.yrm-accordion-switcher-label').each(function () {
		var key = jQuery(this).data('key');
		var dataId = jQuery('input', this).data('id');
		for (var index in keysId) {
			//jQuery('#yrm-accordion-content-type-'+key+'-'+keysId[index]).addClass('yrm-hide')
			var hasClass = jQuery('.yrm-accordion-switcher-label-'+key+'-'+(keysId[index])).hasClass('active');

			if (hasClass == true) {
				jQuery('#yrm-accordion-content-type-'+key+'-'+(keysId[index])).removeClass('yrm-hide')
			}
		}
	})

	jQuery('.yrm-accordion-switcher-label').bind('click', function (e) {
		var current = jQuery(this);
		if (jQuery(this).attr('disabled')) {
			window.open(jQuery(this).data('pro-url'));
			e.preventDefault();
			return ;
		}
		var key = current.data('key');
		var dataId = jQuery('input', this).data('id');
		for (var index in keysId) {
			jQuery('#yrm-accordion-content-type-'+key+'-'+keysId[index]).addClass('yrm-hide')
		}

		setTimeout(function () {
			if (current.hasClass('active')) {
				jQuery('#yrm-accordion-content-type-'+key+'-'+dataId).removeClass('yrm-hide')
			}
		}, 0)
	})
}

yrmBackend.prototype.saveMessageRemove = function () {
	jQuery('.notice-dismiss').bind('click', function () {
		if (jQuery('.ycf-bootstrap-wrapper').prev('#default-message').length) {
			jQuery('.ycf-bootstrap-wrapper').prev('#default-message').remove()
		}
	});
};

yrmBackend.prototype.manageReadMores = function () {
	var selectAllCheckbox = jQuery('.yrm-select-all');
	var idCheckbox = jQuery('.yrm-readmore-id-checkbox');
	var deleteButton = jQuery('.yrm-delete-read-mores');

	selectAllCheckbox.bind('click', function () {
		var status = jQuery(this).is(':checked');
		selectAllCheckbox.prop('checked', status);
		idCheckbox.prop('checked', status);
		deleteButton.prop('disabled', !status);
	});

	deleteButton.bind('click', function () {
		var confirmStatus = confirm('Are you shure?');

		if(!confirmStatus) {
			return false;
		}
		var idsList = [];
		jQuery('.yrm-readmore-id-checkbox:checked').each(function(){
			idsList.push(jQuery(this).val());
		});

		var data = {
			action: 'yrm_delete_readmores',
			ajaxNonce: yrmBackendData.nonce,
			idsList: idsList
		};

		jQuery.post(ajaxurl, data, function(response,d) {
			window.location.reload();
		});
	});

	idCheckbox.bind('change', function () {
		selectAllCheckbox.prop('checked', false);
		if (idCheckbox.length == jQuery('.yrm-readmore-id-checkbox:checked').length) {
			selectAllCheckbox.prop('checked', true);
		}
		var length = jQuery('.yrm-readmore-id-checkbox:checked').length;
		deleteButton.prop('disabled', !Boolean(length));
	})
};

yrmBackend.prototype.farUpgradeButton = function () {
	jQuery('.yrm-upgrade-button-orange').bind('click', function (e) {
		e.preventDefault();
	})
};

yrmBackend.prototype.changeOrder = function () {
	jQuery('.yrm-sorting-indicator').bind('click', function () {
		var dataOrderBy = jQuery(this).data('orderby');
		var order = jQuery(this).data('order');
		var newOrder = !Boolean(order);
		jQuery(this).attr('data-order', newOrder);
		var orderStatus = 'desc';
		if (Boolean(order)) {
			orderStatus = 'asc';
		}
		window.location.href = yrmBackendData.YRM_PAGE_URL+'&orderby='+dataOrderBy+'&order='+orderStatus;
	});
};

yrmBackend.prototype.toggleTabs = function () {
	jQuery('.panel-heading').bind('click', function () {
		jQuery('.yrm-tab-triangle', this).toggleClass('yrm-rotate-180');
		jQuery(this).next().toggleClass('yrm-hide');
	});
};


yrmBackend.prototype.opacity = function () {
	var opacity = jQuery('#yrm-button-opacity');

	if (!opacity.length) {
		return false;
	}

	opacity.ionRangeSlider({
		hide_min_max: true,
		keyboard: true,
		min: 0,
		max: 1,
		type: 'single',
		step: 0.01,
		prefix: '',
		grid: false
	}).bind('change', function () {
		jQuery('.yrm-toggle-expand').css({opacity: jQuery(this).val()})
	});
};

yrmBackend.prototype.changeCursor = function () {
	var cursor = jQuery("[name='yrm-cursor']");

	if (!cursor.length) {
		return false;
	}
	jQuery('.yrm-toggle-expand').css({'cursor': cursor.val()});
	cursor.bind('change', function () {
		var cursor = jQuery(this).val();
		jQuery('.yrm-toggle-expand').css({'cursor': cursor});
	});
};

yrmBackend.prototype.editor = function() {
	(function($){
		$(function(){
			if( $('#yrm-edtitor-head').length ) {
				var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
				editorSettings.codemirror = _.extend(
					{},
					editorSettings.codemirror,
					{
						indentUnit: 2,
						tabSize: 2
					}
				);
				var editor = wp.codeEditor.initialize( $('#ycd-edtitor-head'), editorSettings );
			}

			if( $('#yrm-editor-js').length ) {
				var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
				editorSettings.codemirror = _.extend(
					{},
					editorSettings.codemirror,
					{
						mode: 'javascript',
					}
				);
				var editor = wp.codeEditor.initialize( $('#yrm-editor-js'), editorSettings );
			}

			if( $('#yrm-edtitor-css').length ) {
				var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
				editorSettings.codemirror = _.extend(
					{},
					editorSettings.codemirror,
					{
						indentUnit: 2,
						tabSize: 2,
						mode: 'css',
					}
				);
				var editor = wp.codeEditor.initialize( $('#yrm-edtitor-css'), editorSettings );
			}
		});
	})(jQuery);
};

yrmBackend.prototype.imageUpload = function() {
	var custom_uploader;
	jQuery('#js-upload-hidden-bg-image-button').click(function(e) {
		e.preventDefault();

		/* If the uploader object has already been created, reopen the dialog */
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}
		/* Extend the wp.media object */
		custom_uploader = wp.media.frames.file_frame = wp.media({
			titleFF: 'Choose Image',
			button: {
				text: 'Choose Image'
			},
			multiple: false
		});
		/* When a file is selected, grab the URL and set it as the text field's value */
		custom_uploader.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			var imageURL = jQuery('#hidden-bg-image-url');
			imageURL.val(attachment.url);
			imageURL.trigger('change');
		});
		/* Open the uploader dialog */
		custom_uploader.open();
	});

	/* its finish image uploader */
};

yrmBackend.prototype.sortable = function() {
	jQuery('.yrm-all-options-wrapper').sortable();
	jQuery('.yrm-all-left-options-wrapper').sortable();
	jQuery('.yrm-all-right-options-wrapper').sortable();

	jQuery('.yrm-all-options-wrapper .panel-heading').css({'cursor': 'pointer'}).bind('click', function() {
		if(!jQuery(this).data('tab-open')) {
			jQuery(this).data('tab-open', 1);
			jQuery(this).next().hide();
		}
		else {
			jQuery(this).data('tab-open', 0);
			jQuery(this).next().show();
		}
	});
};

yrmBackend.prototype.livePreviewDraggble = function() {
	jQuery('#yrm-live-preview').draggable({
		stop: function(e, ui) {
			jQuery('.yrm-live-preview').css({'height': 'auto'});
		}
	});
};

yrmBackend.prototype.promotionalVideo = function() {
	var target = jQuery('.yrm-play-promotion-video');

	if(!target.length) {
		return false;
	}

	target.bind('click', function(e) {
		e.preventDefault();
		var href = jQuery(this).data('href');
		window.open(href);
	});
};

yrmBackend.prototype.livePreviewToggle = function() {
	var livePreviewText = jQuery('.yrm-toggle-icon');

	if (!livePreviewText.length) {
		return false;
	}
	livePreviewText.attr('checked', true);
	livePreviewText.bind('click', function() {
		var isChecked = jQuery(this).attr('checked');

		if(isChecked) {
			jQuery('.yrm-toggle-icon').removeClass('yrm-toggle-icon-open').addClass('yrm-toggle-icon-close');
		}
		else {
			jQuery('.yrm-toggle-icon').removeClass('yrm-toggle-icon-close').addClass('yrm-toggle-icon-open');
		}
		jQuery('.yrm-livew-preview-content').slideToggle(1000, 'swing', function () {
		});
		livePreviewText.attr('checked', !isChecked);
	});
};

yrmBackend.prototype.support = function() {
	var submit = jQuery('#yrm-support-request-button');

	if(!submit.length) {
		return false;
	}
	jQuery('#yrm-form').submit(function(e) {
		e.preventDefault();
		var isValid = true;
		var emailError = jQuery('.yrm-validate-email-error');
		emailError.addClass('yrm-hide');
		jQuery('.yrm-required-fields').each(function() {
			var currentVal = jQuery(this).val();
			jQuery('.'+jQuery(this).data('error')).addClass('yrm-hide');

			if(!currentVal) {
				isValid = false;
				jQuery('.'+jQuery(this).data('error')).removeClass('yrm-hide');
			}
		});

		if(!isValid) {
			return false;
		}

		var validateEmail = function(email) {
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(String(email).toLowerCase());
		}

		if(!validateEmail(jQuery('#yrm-email').val())) {
			emailError.removeClass('yrm-hide');
			return false;
		}
		var data = {
			action: 'yrm_support',
			ajaxNonce: yrmBackendData.nonce,
			formData: jQuery(this).serialize(),
			beforeSend: function() {
				submit.prop('disabled', true);
				jQuery('.yrm-support-spinner').removeClass('yrm-hide')
			}
		};

		jQuery.post(ajaxurl, data, function(result) {
			submit.prop('disabled', false);
			jQuery('.yrm-support-spinner').addClass('yrm-hide');
			jQuery('#yrm-form').remove();
			jQuery('.yrm-support-success').removeClass('yrm-hide');
		});
	});
};

yrmBackend.prototype.changeIconDimension = function() {
	var widthDimension = jQuery('#arrow-icon-width');

	if(!widthDimension.length) {
		return false;
	}

	widthDimension.bind('change', function() {
		var val = jQuery(this).val();
		var width = parseInt(val)+'px';
		jQuery('.yrm-arrow-img').css({'width': width})
	});

	var heightDimension = jQuery('#arrow-icon-height');
	heightDimension.bind('change', function() {
		var val = jQuery(this).val();
		var height = parseInt(val)+'px';
		jQuery('.yrm-arrow-img').css({'height': height})
	});
	var enableIcon = jQuery('#arrow-icon-alignment');
	enableIcon.bind('change', function() {
		jQuery('#enable-button-icon').trigger('change');
	});
};

yrmBackend.prototype.swicthEnableIcon = function() {
	var enableButtonIcon = jQuery('#enable-button-icon');

	if(!enableButtonIcon.length) {
		return false;
	}

	enableButtonIcon.bind('change', function() {
		var isChecked = jQuery(this).is(':checked');
		jQuery('.yrm-arrow-img').remove();

		if(isChecked) {
			var wrapper = jQuery('.yrm-text-wrapper');
			var alignment = jQuery('#arrow-icon-alignment option:selected').val();
			var iconHTML = '<span class="yrm-arrow-img"></span>';

			if(!wrapper.length) {
				wrapper = jQuery('.yrm-toggle-expand');
			}
			if (alignment === 'left') {
				wrapper.prepend(iconHTML);
			}
			else if (alignment === 'right') {
				wrapper.append(iconHTML);
			}
		}
	});
};

yrmBackend.prototype.switchInlineButton = function() {
	var inlineCheckbox = jQuery('#addButtonOfTheNext');

	inlineCheckbox.bind('change', function() {
		var isChecked = jQuery(this).is(':checked');

		if(isChecked) {
			jQuery('.yrm-inline-wrapper').addClass('yrm-btn-inline');
		}
		else {
			jQuery('.yrm-inline-wrapper').removeClass('yrm-btn-inline');
		}
	});
};

yrmBackend.prototype.switchContentGradientColor = function() {
	var switchGradient = jQuery('#showContentGradient');

	if(!switchGradient.length) {
		return false;
	}

	switchGradient.bind('change', function() {
		var isChecked = jQuery(this).is(':checked');
		var gradientTarget = jQuery('.yrm-content-gradient');
		if(isChecked) {
			if(!gradientTarget.length) {
				jQuery('.yrm-btn-wrapper').prepend('<div class="yrm-content-gradient"></div>');
				jQuery('#showContentGradientPosition').trigger('change');
				jQuery('#showContentGradientHeight').trigger('change');
				jQuery('.show-content-gradient-color').trigger('change');
			}
			gradientTarget.show();
		}
		else {
			gradientTarget.hide();
		}
	})
};

yrmBackend.prototype.changeContentGradientPosition = function() {
	var position = jQuery('#showContentGradientPosition');

	if(!position.length) {
		return false;
	}

	position.bind('change', function() {
		jQuery('.yrm-content-gradient').css({'top': jQuery(this).val()+'px'});
	});
};

yrmBackend.prototype.changeContentGradientHeight = function() {
	var height = jQuery('#showContentGradientHeight');

	if(!height.length) {
		return false;
	}

	height.bind('change', function() {
		jQuery('.yrm-content-gradient').css({'padding': jQuery(this).val()+'px 0'});
	});
};

yrmBackend.prototype.changeContentGradientColor = function() {
	jQuery('.show-content-gradient-color').minicolors({
		change: function() {
			jQuery('#gradineg-color-style').remove();
			jQuery('body').append('<style id="gradineg-color-style">.yrm-content-gradient {background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0, transparent),color-stop(1, '+jQuery(this).val()+')) !important; }</style>');
		
		}
	});
};

yrmBackend.prototype.setDecorationStyle = function () {
	var type = jQuery('.yrm-decoration-type option:selected').val()
	var style = jQuery('.yrm-decoration-style option:selected').val()
	var color = jQuery('.yrm-decoration-color').val();

	if (jQuery("#yrm-enable-decoration").is(":checked")) {
		jQuery('.yrm-button-text-span')
			.css('text-decoration-line', type)
			.css('text-decoration-style', style)
			.css('text-decoration-color', color);
	}
}

yrmBackend.prototype.changeTextDecoration = function() {
	var that = this;
	jQuery('.yrm-decoration-color').minicolors({
		change: function() {
			that.setDecorationStyle()
		}
	});
	jQuery('#yrm-enable-decoration, .yrm-decoration-type, .yrm-decoration-style').bind('change', function () {
		that.setDecorationStyle();
	})
};

yrmBackend.prototype.chnageAutoModePadding = function() {
	var padding = jQuery('.button-padding');

	if(!padding.length) {
		return false;
	}

	padding.bind('change', function(e) {
		e.preventDefault();
		var autoModeButton = jQuery('.yrm-button-auto-mode');

		if(!autoModeButton.length) {
			return false;
		}

		var paddingDirection = jQuery(this).data('direction');
		var val = jQuery(this).val();
		var styleObj = {};
		styleObj['padding-'+paddingDirection] = parseInt(val)+'px';
		autoModeButton.css(styleObj);
	});
}

yrmBackend.prototype.chnageDimensionsMode = function() {
	var dimensions = jQuery('.dimension-mode');

	if(!dimensions.length) {
		return false;
	}

	dimensions.bind('change', function(e) {
		e.preventDefault();
		var val = jQuery(this).val();
		var expandButton = jQuery('.yrm-toggle-expand');
		if(val == 'autoMode') {
			expandButton.addClass('yrm-button-auto-mode');
		}
		else {
			expandButton.removeClass('yrm-button-auto-mode');
		}
	});
};

yrmBackend.prototype.multipleChoiceButton = function() {
	var choiceOptions = jQuery('.yrm-choice-option-wrapper input');
	if(!choiceOptions.length) {
		return false;
	}

	var that = this;

	choiceOptions.each(function() {

		if(jQuery(this).is(':checked')) {
			that.buildChoiceShowOption(jQuery(this));
		}

		jQuery(this).on('change', function() {
			that.hideAllChoiceWrapper(jQuery(this).parents('.yrm-multichoice-wrapper').first());
			that.buildChoiceShowOption(jQuery(this));
		});
	})
};

yrmBackend.prototype.hideAllChoiceWrapper = function(choiceOptionsWrapper) {
	choiceOptionsWrapper.find('input').each(function() {
		var choiceInputWrapperId = jQuery(this).attr('data-attr-href');
		jQuery('#'+choiceInputWrapperId).addClass('yrm-hide-content');
	})
};

yrmBackend.prototype.buildChoiceShowOption = function(currentRadioButton)  {
	var choiceOptions = currentRadioButton.attr('data-attr-href');
	var currentOptionWrapper = currentRadioButton.parents('.yrm-choice-wrapper').first();
	var choiceOptionWrapper = jQuery('#'+choiceOptions).removeClass('yrm-hide-content');
	currentOptionWrapper.after(choiceOptionWrapper);
};

yrmBackend.prototype.copySortCode = function() {
	jQuery('#expm-shortcode-info-div').bind('click', function() {
		var copyText = document.getElementById('expm-shortcode-info-div');
		copyText.select();
		document.execCommand('copy');

		var tooltip = document.getElementById('yrm-tooltip');
		tooltip.innerHTML = yrmBackendData.copied;
	});

	jQuery(document).on('focusout', '#expm-shortcode-info-div',function() {
		var tooltip = document.getElementById('yrm-tooltip');
		tooltip.innerHTML = yrmBackendData.copyToClipboard;
	});
};

yrmBackend.prototype.importData = function() {
	var custom_uploader;
	jQuery('.yrm-import-button').click(function(e) {
		e.preventDefault();
		var ajaxNonce = jQuery(this).attr('data-ajaxNonce');

		/* If the uploader object has already been created, reopen the dialog */
		if (custom_uploader) {
			custom_uploader.open();
			return;
		}

		/* Extend the wp.media object */
		custom_uploader = wp.media.frames.file_frame = wp.media({
			titleFF: 'Select Export File',
			button: {
				text: 'Select Export File'
			},
			multiple: false,
			library : {type : 'text/csv'}
		});
		/* When a file is selected, grab the URL and set it as the text field's value */
		custom_uploader.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();

			var data = {
				action: 'yrm_import_data',
				ajaxNonce: ajaxNonce,
				attachmentUrl: attachment.url
			};
			jQuery('.yrm-spinner').removeClass('yrm-hide-content');
			jQuery.post(ajaxurl, data, function(response,d) {
				window.location.reload();
				jQuery('.yrm-spinner').removeClass('yrm-hide-content');
			});
		});
		/* Open the uploader dialog */
		custom_uploader.open();
	});
};

yrmBackend.prototype.export = function() {
	var exportButton = jQuery('.yrm-exprot-button');

	if (!exportButton.length) {
		return false;
	}

	exportButton.bind('click', function() {
		var data = {
			action: 'yrm_export',
			beforeSend: function() {
				jQuery('.yrm-spinner').removeClass('yrm-hide-content');
			},
			ajaxNonce: yrmBackendData.nonce
		};

		jQuery.post(ajaxurl, data, function(data) {
			var hiddenElement = document.createElement('a');
			jQuery('.yrm-spinner').addClass('yrm-hide-content');
			hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(data);
			hiddenElement.target = '_blank';
			hiddenElement.download = 'readMoreExportData.csv';
			hiddenElement.click()
		})
	});
};

yrmBackend.prototype.changeButtonTitle = function() {
	jQuery('#moreTitle').bind('change', function() {
		var val = jQuery(this).val();
		var readMoreButton = jQuery('.yrm-toggle-expand');
		if(!jQuery('.yrm-content').data('show-status')) {
			readMoreButton.attr('title', val);
		}
		readMoreButton.attr('data-more-title', val);
	});

	jQuery('#lessTitle').bind('change', function() {
		var val = jQuery(this).val();
		var readMoreButton = jQuery('.yrm-toggle-expand');
		readMoreButton.attr('data-less-title', val);
		if(jQuery('.yrm-content').data('show-status')) {
			readMoreButton.attr('title', val);
		}
	});
};

yrmBackend.prototype.changeButtonToolTip = function () {
	var enableToolTip = jQuery('#enable-tooltip');
	if (!enableToolTip.length) {
		return false;
	}
	enableToolTip.bind('change', function () {
		var isChecked = jQuery(this).is(':checked');
		var tooltipText = jQuery('#enable-tooltip-text');
		if (isChecked) {
			jQuery('.yrm-more-button-wrapper').addClass('yrm-tooltip');
			var toolTipText = tooltipText.val();
			jQuery('.yrm-text-wrapper').before('<span class="yrm-tooltiptext" id="yrm-myTooltip">'+toolTipText+'</span>')
		}
		else {
			jQuery('#yrm-myTooltip').remove();
		}

		tooltipText.bind('change', function () {
			jQuery('#yrm-myTooltip').text(jQuery(this).val())
		});
	})

};

yrmBackend.prototype.changeButtonBoxShadow = function() {
	var spreadSizes = jQuery('#button-box-shadow-horizontal, #button-box-shadow-vertical, #button-box-spread-radius, #button-box-blur-radius');

	if (!spreadSizes) {
		return false;
	}
	var liveChangeShadow = function() {
		var shadowHorizontal = jQuery('#button-box-shadow-horizontal').val()+'px';
		var shadowVertical = jQuery('#button-box-shadow-vertical').val()+'px';
		var spreadRadius = jQuery('#button-box-spread-radius').val()+'px';
		var blurRadius = jQuery('#button-box-blur-radius').val()+'px';
		var color = jQuery('#button-box-shadow-color').val();

		jQuery('.yrm-toggle-expand').css({'box-shadow': shadowHorizontal+' '+shadowVertical+' '+blurRadius+' '+spreadRadius+' '+color});
	};

	spreadSizes.bind('change', function() {
		liveChangeShadow();
	});

	jQuery('#button-box-shadow-color').minicolors({
		change: function () {
			liveChangeShadow();
		}
	});
};

yrmBackend.prototype.changeButtonBorderWidth = function() {
	var width = jQuery('#button-border-width');

	if (!width.length) {
		return false;
	}

	width.bind('change', function() {
		var width = jQuery(this).val();
		jQuery('.yrm-toggle-expand').css({'border-width': width});
	});
};

yrmBackend.prototype.changeButtonBorderColor = function() {
	var borderColor = jQuery('.button-border-color');

	if(!borderColor.length) {
		return false;
	}

	borderColor.minicolors({
		change: function () {
			var val = jQuery(this).val();
			var element = '.yrm-toggle-expand';
			jQuery(element).css({'border-color': val});
		}
	});
};

yrmBackend.prototype.changeSwitch = function() {
	var switchStatus = jQuery('.yrm-status-switch');

	if (!switchStatus.length) {
		return false;
	}

	switchStatus.bind('change', function(e) {
		var isChecked = jQuery(this).is(':checked');

		var id = jQuery(this).data('id');

		var data = {
			action: 'yrm_switch_status',
			ajaxNonce: yrmBackendData.nonce,
			readMoreId: id,
			isChecked: isChecked
		};

		jQuery.post(ajaxurl, data, function(response,d) {
			window.location.reload();
		});
	})
};

yrmBackend.prototype.changeFarStatus = function() {
	var switchStatus = jQuery('.yrm-find-replace-status-switch');

	if (!switchStatus.length) {
		return false;
	}

	switchStatus.bind('change', function(e) {
		var isChecked = jQuery(this).is(':checked');

		var id = jQuery(this).data('id');

		var data = {
			action: 'yrm_far_status',
			ajaxNonce: yrmBackendData.nonce,
			id: id,
			isChecked: isChecked
		};

		jQuery.post(ajaxurl, data, function(response,d) {
			window.location.reload();
		});
	})
};

yrmBackend.prototype.changeEasings = function () {

	var readMoreId = 0;
	var hiddenReadMoreId = jQuery('[name="read-more-id"]').val();
	hiddenReadMoreId = parseInt(hiddenReadMoreId);

	if (hiddenReadMoreId) {
		readMoreId = hiddenReadMoreId;
	}
	if (typeof readMoreArgs == 'undefined') {
		return false;
	}
	var readMoreData = readMoreArgs[readMoreId];
	jQuery('.yrm-animate-easings').change(function () {
		var val = jQuery(this).val();
		readMoreData['yrm-animate-easings'] = val;
	});
	jQuery('.yrm-eye-animation-behaviour').bind('click', function () {
		jQuery('.yrm-toggle-expand').click();
	});
};

yrmBackend.prototype.select2 = function () {

	var select2 = jQuery('.yrm-js-select2');

	if(!select2.length) {
		return false;
	}
	var options = {
		width: '100%'
	};

	select2.select2(options);
};

yrmBackend.prototype.proOptionsWrapper = function() {

	if(jQuery('.yrm-pro-options').length == 0) {
		return '';
	}

	jQuery('.yrm-pro-options').on('click', function() {
		window.open('https://edmonsoft.com');
	});
};

yrmBackend.prototype.accordionContent = function () {

	var that = this;
	jQuery('.yrm-accordion-checkbox').each(function () {
		that.doAccordion(jQuery(this), jQuery(this).is(':checked'));
	});
	jQuery('[name="expander-font-family"], [name="hidden-content-font-family"]').bind('change', function() {
		var val = jQuery('option:selected', this).val() == 'customFont';
		var currentCheckbox = jQuery(this);
		that.doAccordion(currentCheckbox, val);
	});
	jQuery('[name="yrm-hidden-content-line-height"]').bind('change', function() {
		var val = jQuery('option:selected', this).val() == 'customLineHeight';
		var currentCheckbox = jQuery(this);
		that.doAccordion(currentCheckbox, val);
	});
	jQuery('[name="expander-font-family"], [name="hidden-content-font-family"], [name="yrm-hidden-content-line-height"]').change();
	jQuery('.yrm-accordion-checkbox').each(function () {
		jQuery(this).bind('change', function () {
			var attrChecked = jQuery(this).is(':checked');
			var currentCheckbox = jQuery(this);
			that.doAccordion(currentCheckbox, attrChecked);
		});
	});
};

yrmBackend.prototype.doAccordion = function (checkbox, ischecked) {
	var accordionContent = checkbox.parents('.row').nextAll('.yrm-accordion-content').first();

	if(ischecked) {
		accordionContent.removeClass('yrm-hide-content');
	}
	else {
		accordionContent.addClass('yrm-hide-content');
	}
};

yrmBackend.prototype.typeDeleteAjaxRequest = function () {

	jQuery('.yrm-type-delete-link').bind('click', function (e) {
		e.preventDefault();

		var confirmStatus = confirm('Are you shure?');

		if(!confirmStatus) {
			return;
		}

		var id = jQuery(this).attr('data-id');
		var type = jQuery(this).data('type');

		var data = {
			action: 'yrm_type_delete',
			ajaxNonce: yrmBackendData.nonce,
			id: id,
			type: type
		};

		jQuery.post(ajaxurl, data, function(response,d) {
			window.location.reload();
		});
	});
};

yrmBackend.prototype.deleteAjaxRequest = function() {

	jQuery('.yrm-delete-link').bind('click', function (e) {
		e.preventDefault();

		var confirmStatus = confirm('Are you shure?');

		if(!confirmStatus) {
			return;
		}

		var id = jQuery(this).attr('data-id');

		var data = {
			action: 'delete_rm',
			ajaxNonce: yrmBackendData.nonce,
			readMoreId: id
		};

		jQuery.post(ajaxurl, data, function(response,d) {
			window.location.reload();
		});
	});
};

jQuery(document).ready(function() {
	
	var obj = new yrmBackend();
	obj.init();
});