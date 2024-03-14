var MarkNewPostsAdminForm = function ($, scriptOptions) {
	var MESSAGE_TIME = 3000;
	var ANIMATION_TIME = 300;
	var ui;
	var messageTimeout;

	$(document).ready(function() {
		initUi();

		initForm();

		var initialOptions = getOptionsFromForm();

		ui.saveOptionsBtn.click(function() {
			var options = getOptionsFromForm();
			if (!validateOptions(options)) {
				return;
			}
			var data = $.extend({}, options, {
				action: 'mark_new_posts_save_options'
			});
			clearMessage();
			setFormDisabled(true);
			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: data,
				success: function (response) {
					var success = response.success;
					if (success) {
						initialOptions = options;
					}
					showMessage(success, response.message);
					setFormDisabled(false);
				},
				error: function() {
					alert('Request error');
					setFormDisabled(false);
				}
			});
		});

		ui.resetOptionsBtn.click(function() {
			ui.markerPlacement.val(initialOptions.markerPlacement);
			ui.markerType.val(initialOptions.markerType);
			ui.markTitleBg.prop('checked', initialOptions.markTitleBg);
			ui.markBgColor.val(initialOptions.markBgColor);
			ui.openToRead.prop('checked', initialOptions.openToRead);
			ui.useJs.prop('checked', initialOptions.useJs);
			initForm();
			clearMessage();
		});

		ui.postStaysNew.change(onPostStaysNewChange);
	});

	var initUi = function() {
		ui = {
			markerPlacement: $('#mnp-marker-placement'),
			markerType: $('#mnp-marker-type'),
			markTitleBg: $('#mnp-mark-title-bg'),
			markBgColor: $('#mnp-mark-bg-color'),
			postStaysNew: $('#mnp-post-stays-new'),
			postStaysNewDays: $('#mnp-post-stays-new-days'),
			allNewForNewVisitor: $('#mnp-all-new-for-new-visitor'),
			disableForCustomPosts: $('#mnp-disable-for-custom-posts'),
			allowOutsideTheLoop: $('#mnp-allow-outside-the-loop'),
			useJs: $('#mnp-use-js'),
			saveOptionsBtn: $('#mnp-save-options-btn'),
			resetOptionsBtn: $('#mnp-reset-options-btn'),
			message: $('#mnp-message')
		};
	};

	var initForm = function() {
		onPostStaysNewChange();
	}

	var getOptionsFromForm = function() {
		return {
			markerPlacement: ui.markerPlacement.val(),
			markerType: ui.markerType.val(),
			markTitleBg: ui.markTitleBg.is(':checked'),
			markBgColor: ui.markBgColor.val(),
			markAfter: $('[name=mnp-mark-after]:checked').val(),
			postStaysNewDays: +ui.postStaysNewDays.val(),
			allNewForNewVisitor: ui.allNewForNewVisitor.is(':checked'),
			disableForCustomPosts: ui.disableForCustomPosts.is(':checked'),
			allowOutsideTheLoop: ui.allowOutsideTheLoop.is(':checked'),
			useJs: ui.useJs.is(':checked')
		};
	};

	var validateOptions = function(options) {
		var error = {
			field: null,
			message: null
		};
		if (ui.postStaysNew.is(':checked') && !(options.postStaysNewDays > 0)) {
			error.field = 'postStaysNewDays';
			error.message = 'postStaysNewDays';
		}
		var noError = true;
		if (error.field) {
			noError = false;
			ui[error.field].focus();
			showMessage(false, scriptOptions.messages[error.message]);
		}
		return noError;
	};

	var onPostStaysNewChange = function() {
		var checked = ui.postStaysNew.is(':checked');
		ui.postStaysNewDays.prop('disabled', !checked);
		if (!checked) {
			ui.postStaysNewDays.val('');
		} else if (!ui.postStaysNewDays.val()) {
			ui.postStaysNewDays.val(1);
		}
	};

	var toggle = function(el, show, quick) {
		el[show ? 'show' : 'hide'](quick ? 0 : ANIMATION_TIME);
	};

	var showMessage = function(success, text) {
		var CLASS_SUCCESS = 'mnp-success';
		var CLASS_ERROR = 'mnp-error';
		ui.message
			.removeClass(CLASS_SUCCESS + ' ' + CLASS_ERROR)
			.addClass(success ? CLASS_SUCCESS : CLASS_ERROR)
			.text(text)
			.show();
		clearTimeout(messageTimeout);
		if (success) {
			messageTimeout = setTimeout(function() {
				ui.message.hide();
			}, MESSAGE_TIME);
		}
	};

	var clearMessage = function() {
		clearTimeout(messageTimeout);
		ui.message.hide();
	};

	var setFormDisabled = function(value) {
		$.each(ui, function(i, el) {
			$(el).prop('disabled', value);
		});
	};
};