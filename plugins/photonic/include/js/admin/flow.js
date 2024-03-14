jQuery(document).ready(function($) {
	var wpActiveEditor;
	var photonicLastActiveScreen = 1;
	var photonicNativeWPMediaLibrary;
	var photonicMustPost = false;
	var photonicPermittedGalleries = ['wp', 'default', 'flickr', 'smugmug', 'picasa', 'google', 'zenfolio', 'instagram'];
	var photonicIsWidget = false;

	window.photonicAddTBClass = function() {
		var tb = $('#TB_window', window.parent.document);
		tb.addClass('photonic-tb');
	};
	photonicAddTBClass();

	window.photonicGetSelectedText = function() {
		var $textArea = $('textarea#content', window.parent.document);
		var start = $textArea.prop('selectionStart');
		var end = $textArea.prop('selectionEnd');
		if (start !== undefined && end !== undefined) {
			return $textArea.val().substring(start, end);
		}
		else {
			var widgetNode = window.parent.photonicWidgetData;

			if (widgetNode !== undefined) {
				var sc = widgetNode.attr('value').trim();
				photonicIsWidget = true;
				return sc;
			}
		}
		return '';
	};

	window.photonicPostFlowData = function(activeScreen, nextScreen, $activeScreenElement, screenParameters, parameters) {
		var $waiting = $('.photonic-waiting');
		$.post(Photonic_Wizard_JS.ajaxurl, parameters, function(data){
			if ($(data).hasClass('photonic-flow-error')) {
				$('.photonic-flow-error').remove();
				$('.photonic-flow-screen[data-screen="' + activeScreen + '"]').before(data);
				$activeScreenElement.attr('data-submitted', '');
			}
			else {
				$('.photonic-flow-error').hide();
				var $forceScreen = $('<div></div>').append(data).find('input[name="force_next_screen"]');
				if ($forceScreen.length > 0 && parseInt($($forceScreen[0]).val()) > -1) {
					nextScreen = $($forceScreen[0]).val();
				}

				$('.photonic-flow-screen[data-screen="' + nextScreen + '"]').empty().append(data);
				photonicFlowLogic(nextScreen);
				if (nextScreen <= 3) {
					var existing = $('input[name="existing_selection"]').val();
					var $selection = $('input[name="selected_data"]');
					var $passworded = $('input[name="selection_passworded"]');
					if (existing !== undefined && existing !== '') {
						$selection.val(existing);
					}
					else {
						$selection.val('');
						$passworded.val('');
					}
				}
				photonicLastActiveScreen = nextScreen;
				$activeScreenElement.attr('data-submitted', screenParameters);
				photonicMustPost = false;
			}
			$waiting.hide();
		});
	};

	window.photonicInitializeWPMediaLibrary = function(activeScreen, nextScreen, $activeScreenElement, screenParameters, $clicked) {
		if (top.wp !== undefined) {
			var mode = $('select[name="display_type"]').val();
			if (mode === '') {
				alert(Photonic_Wizard_JS.error_mandatory);
			}
			else {
				mode = mode === 'single-photo' ? 'single' : 'add';
				photonicNativeWPMediaLibrary = top.wp.media({
					multiple: mode,
					title: Photonic_Wizard_JS.media_library_title,
					library: {type: 'image'},
					button: {text: Photonic_Wizard_JS.media_library_button}
				});

				photonicNativeWPMediaLibrary.on('select', function() {
					var $waiting = $('.photonic-waiting');
					$waiting.show();

					var selection = photonicNativeWPMediaLibrary.state().get('selection');
					var selected_data = '';
					selection.map(function(attachment) {
						attachment = attachment.toJSON();
						selected_data += attachment.id + ',';
					});
					selected_data = selected_data.replace(/^,+|,+$/g, '');
					$('input[name="selected_data"]').val(selected_data);

					var $form = $('#photonic-flow');
					var parameters = $form.serialize();
					parameters += ((parameters === '') ? '' : '&') + 'action=photonic_wizard_next_screen&screen=' + (activeScreen) + '&_ajax_nonce=' + $clicked.data('photonicNonce');
					photonicPostFlowData(activeScreen, nextScreen, $activeScreenElement, screenParameters, parameters);

					if (selected_data !== '') {
						photonicFlowLogic(nextScreen);
					}
				});

				photonicNativeWPMediaLibrary.on('open',function() {
					var selection = photonicNativeWPMediaLibrary.state().get('selection');
					var ids = $('input[name="selected_data"]').val();

					var editor_selection = photonicGetSelectedText();
					var shortcode, attrs, win = window.dialogArguments || opener || parent || top;
					if (editor_selection !== '' && top.wp !== undefined && top.wp.shortcode !== undefined) {
						shortcode = top.wp.shortcode.next(Photonic_Wizard_JS.shortcode, editor_selection);
						attrs = shortcode.shortcode.attrs.named;
					}
					else if (editor_selection === '' && top.wp !== undefined && top.wp.data !== undefined && top.wp.data.select !== undefined &&
						win.photonicBlockProperties !== undefined && win.photonicBlockProperties.attributes !== undefined && win.photonicBlockProperties.attributes.shortcode !== undefined) { // Gutenberg
						shortcode = win.photonicBlockProperties.attributes.shortcode;
						attrs = JSON.parse(shortcode);
					}

					if (ids === '' && attrs !== undefined) {
						if (attrs.ids !== undefined) {
							ids = attrs.ids;
						}
						else if (attrs.include !== undefined) {
							ids = attrs.include;
						}
					}


					ids = ids.split(',');
					ids.forEach(function(id) {
						var attachment = top.wp.media.attachment(id);
						attachment.fetch();
						selection.add( attachment ? [ attachment ] : [] );
					});
				});
			}
		}
	};

	window.photonicCheckCondition = function(conditions) {
		var conditionMet = true;
		$(conditions).each(function(cidx, condition) {
			var keys = Object.keys(condition);
			$(keys).each(function(kidx, key){
				var keyValue = $('input[type="radio"][name="' + this + '"]:checked').val() || $('select[name="' + this + '"]').val() || $('input[type="text"][name="' + this + '"]').val() || $('input[type="hidden"][name="' + this + '"]').val();
				conditionMet = conditionMet && ($.inArray(keyValue, condition[key]) !== -1);
			});
		});
		return conditionMet;
	};

	window.photonicUpdateSelection = function(clicked) {
		var $parent = $(clicked.parents('.photonic-flow-selector-container')[0]);
		var selection = [];
		$parent.find('.photonic-flow-selector.selected .photonic-flow-selector-inner').each(function() {
			selection[selection.length] = $(this).attr('data-photonic-selection-id');
		});
		selection = selection.join();
		var selectorFor = $parent.attr('data-photonic-flow-selector-for');
		photonicMustPost = true;
		$('input[name="' + selectorFor + '"]').val(selection);
	};

	function photonicShowConditionalFieldValues(sibling) {
		var $siblingFieldValues = $(sibling).find('input[type="radio"], option');
		$siblingFieldValues.each(function (sfidx, siblingFieldValue) {
			if ($(siblingFieldValue).attr('data-photonic-option-condition') !== undefined) {
				var conditionMet = photonicCheckCondition(JSON.parse($(siblingFieldValue).attr('data-photonic-option-condition')));
				if (conditionMet) {
					if (siblingFieldValue.type === 'radio') {
						$(siblingFieldValue).parents('.photonic-flow-field-radio').show();
					}
					else {
						$(siblingFieldValue).show();
					}
				}
				else {
					if (siblingFieldValue.type === 'radio') {
						siblingFieldValue.checked = false;
						$(siblingFieldValue).parents('.photonic-flow-field-radio').hide();
					}
					else {
						$(siblingFieldValue).hide();
					}
				}
			}
		});
	}

	// From https://developer.mozilla.org/en-US/docs/Glossary/Base64#solution_2_%E2%80%93_rewriting_atob_and_btoa_using_typedarrays_and_utf-8, to handle https://wordpress.org/support/topic/set-load-more-button-default/ ...

	/*\
	|*|
	|*|  Base64 / binary data / UTF-8 strings utilities
	|*|
	|*|  https://developer.mozilla.org/en-US/docs/Web/JavaScript/Base64_encoding_and_decoding
	|*|
	\*/

	/* Array of bytes to Base64 string decoding */
	window.photonicB64ToUint6 = function (nChr) {
		return nChr > 64 && nChr < 91 ?
			nChr - 65
			: nChr > 96 && nChr < 123 ?
				nChr - 71
				: nChr > 47 && nChr < 58 ?
					nChr + 4
					: nChr === 43 ?
						62
						: nChr === 47 ?
							63
							:
							0;

	}

	window.photonicBase64DecToArr = function(sBase64, nBlocksSize) {
		var
			sB64Enc = sBase64.replace(/[^A-Za-z0-9\+\/]/g, ""), nInLen = sB64Enc.length,
			nOutLen = nBlocksSize ? Math.ceil((nInLen * 3 + 1 >> 2) / nBlocksSize) * nBlocksSize : nInLen * 3 + 1 >> 2, taBytes = new Uint8Array(nOutLen);

		for (var nMod3, nMod4, nUint24 = 0, nOutIdx = 0, nInIdx = 0; nInIdx < nInLen; nInIdx++) {
			nMod4 = nInIdx & 3;
			nUint24 |= photonicB64ToUint6(sB64Enc.charCodeAt(nInIdx)) << 6 * (3 - nMod4);
			if (nMod4 === 3 || nInLen - nInIdx === 1) {
				for (nMod3 = 0; nMod3 < 3 && nOutIdx < nOutLen; nMod3++, nOutIdx++) {
					taBytes[nOutIdx] = nUint24 >>> (16 >>> nMod3 & 24) & 255;
				}
				nUint24 = 0;

			}
		}

		return taBytes;
	}

	/* Base64 string to array encoding */
	window.photonicUint6ToB64 = function(nUint6) {

		return nUint6 < 26 ?
			nUint6 + 65
			: nUint6 < 52 ?
				nUint6 + 71
				: nUint6 < 62 ?
					nUint6 - 4
					: nUint6 === 62 ?
						43
						: nUint6 === 63 ?
							47
							:
							65;

	}

	window.photonicBase64EncArr = function(aBytes) {
		var nMod3 = 2, sB64Enc = "";

		for (var nLen = aBytes.length, nUint24 = 0, nIdx = 0; nIdx < nLen; nIdx++) {
			nMod3 = nIdx % 3;
			if (nIdx > 0 && (nIdx * 4 / 3) % 76 === 0) { sB64Enc += "\r\n"; }
			nUint24 |= aBytes[nIdx] << (16 >>> nMod3 & 24);
			if (nMod3 === 2 || aBytes.length - nIdx === 1) {
				sB64Enc += String.fromCodePoint(photonicUint6ToB64(nUint24 >>> 18 & 63), photonicUint6ToB64(nUint24 >>> 12 & 63), photonicUint6ToB64(nUint24 >>> 6 & 63), photonicUint6ToB64(nUint24 & 63));
				nUint24 = 0;
			}
		}

		return sB64Enc.substr(0, sB64Enc.length - 2 + nMod3) + (nMod3 === 2 ? '' : nMod3 === 1 ? '=' : '==');

	}

	/* UTF-8 array to JS string and vice versa */
	window.photonicUTF8ArrToStr = function (aBytes) {
		var sView = "";

		for (var nPart, nLen = aBytes.length, nIdx = 0; nIdx < nLen; nIdx++) {
			nPart = aBytes[nIdx];
			sView += String.fromCodePoint(
				nPart > 251 && nPart < 254 && nIdx + 5 < nLen ? /* six bytes */
					/* (nPart - 252 << 30) may be not so safe in ECMAScript! So…: */
					(nPart - 252) * 1073741824 + (aBytes[++nIdx] - 128 << 24) + (aBytes[++nIdx] - 128 << 18) + (aBytes[++nIdx] - 128 << 12) + (aBytes[++nIdx] - 128 << 6) + aBytes[++nIdx] - 128
					: nPart > 247 && nPart < 252 && nIdx + 4 < nLen ? /* five bytes */
					(nPart - 248 << 24) + (aBytes[++nIdx] - 128 << 18) + (aBytes[++nIdx] - 128 << 12) + (aBytes[++nIdx] - 128 << 6) + aBytes[++nIdx] - 128
					: nPart > 239 && nPart < 248 && nIdx + 3 < nLen ? /* four bytes */
						(nPart - 240 << 18) + (aBytes[++nIdx] - 128 << 12) + (aBytes[++nIdx] - 128 << 6) + aBytes[++nIdx] - 128
						: nPart > 223 && nPart < 240 && nIdx + 2 < nLen ? /* three bytes */
							(nPart - 224 << 12) + (aBytes[++nIdx] - 128 << 6) + aBytes[++nIdx] - 128
							: nPart > 191 && nPart < 224 && nIdx + 1 < nLen ? /* two bytes */
								(nPart - 192 << 6) + aBytes[++nIdx] - 128
								: /* nPart < 127 ? */ /* one byte */
								nPart
			);
		}

		return sView;

	}

	window.photonicStrToUTF8Arr = function(sDOMStr) {
		var aBytes, nChr, nStrLen = sDOMStr.length, nArrLen = 0;

		/* mapping… */

		for (var nMapIdx = 0; nMapIdx < nStrLen; nMapIdx++) {
			nChr = sDOMStr.codePointAt(nMapIdx);

			if (nChr > 65536) {
				nMapIdx++;
			}

			nArrLen += nChr < 0x80 ? 1 : nChr < 0x800 ? 2 : nChr < 0x10000 ? 3 : nChr < 0x200000 ? 4 : nChr < 0x4000000 ? 5 : 6;
		}

		aBytes = new Uint8Array(nArrLen);

		/* transcription… */

		for (var nIdx = 0, nChrIdx = 0; nIdx < nArrLen; nChrIdx++) {
			nChr = sDOMStr.codePointAt(nChrIdx);
			if (nChr < 128) {
				/* one byte */
				aBytes[nIdx++] = nChr;
			} else if (nChr < 0x800) {
				/* two bytes */
				aBytes[nIdx++] = 192 + (nChr >>> 6);
				aBytes[nIdx++] = 128 + (nChr & 63);
			} else if (nChr < 0x10000) {
				/* three bytes */
				aBytes[nIdx++] = 224 + (nChr >>> 12);
				aBytes[nIdx++] = 128 + (nChr >>> 6 & 63);
				aBytes[nIdx++] = 128 + (nChr & 63);
			} else if (nChr < 0x200000) {
				/* four bytes */
				aBytes[nIdx++] = 240 + (nChr >>> 18);
				aBytes[nIdx++] = 128 + (nChr >>> 12 & 63);
				aBytes[nIdx++] = 128 + (nChr >>> 6 & 63);
				aBytes[nIdx++] = 128 + (nChr & 63);
				nChrIdx++;
			} else if (nChr < 0x4000000) {
				/* five bytes */
				aBytes[nIdx++] = 248 + (nChr >>> 24);
				aBytes[nIdx++] = 128 + (nChr >>> 18 & 63);
				aBytes[nIdx++] = 128 + (nChr >>> 12 & 63);
				aBytes[nIdx++] = 128 + (nChr >>> 6 & 63);
				aBytes[nIdx++] = 128 + (nChr & 63);
				nChrIdx++;
			} else /* if (nChr <= 0x7fffffff) */ {
				/* six bytes */
				aBytes[nIdx++] = 252 + (nChr >>> 30);
				aBytes[nIdx++] = 128 + (nChr >>> 24 & 63);
				aBytes[nIdx++] = 128 + (nChr >>> 18 & 63);
				aBytes[nIdx++] = 128 + (nChr >>> 12 & 63);
				aBytes[nIdx++] = 128 + (nChr >>> 6 & 63);
				aBytes[nIdx++] = 128 + (nChr & 63);
				nChrIdx++;
			}
		}

		return aBytes;

	}

	window.photonicFlowLogic = function(screen) {
		var existing = $('input[name="photonic-editor-shortcode"]').val();
		var existingBlock = $('input[name="photonic-editor-json"]').val();
		if (screen === 1) {
			if (existing === undefined || existing === null || existing === '') {
				var shortcode, attributes, type, win = window.dialogArguments || opener || parent || top;
				var selection = photonicGetSelectedText();
				if (selection !== '' && top.wp !== undefined && top.wp.shortcode !== undefined) {
					shortcode = top.wp.shortcode.next(Photonic_Wizard_JS.shortcode, selection.trim());
					var moreShortcode = top.wp.shortcode.next(Photonic_Wizard_JS.shortcode, selection.trim(), 1); // Only one shortcode at a time

					if (shortcode !== undefined && moreShortcode === undefined && shortcode.content.length === selection.trim().length) {
						// var scParameter = window.btoa(JSON.stringify(shortcode)); // Causes a problem with unicode: https://wordpress.org/support/topic/set-load-more-button-default/#post-15815267
						var scParameter = photonicBase64EncArr(photonicStrToUTF8Arr(JSON.stringify(shortcode))); // To handle unicode in "more"; see https://developer.mozilla.org/en-US/docs/Glossary/Base64#solution_1_%E2%80%93_escaping_the_string_before_encoding_it
						scParameter = scParameter.replace(/\n/g, '');
						scParameter = scParameter.replace(/^=+|=+$/g, '');
						attributes = shortcode.shortcode.attrs.named;
						if (attributes['type'] !== undefined && photonicPermittedGalleries.indexOf(attributes['type']) !== -1) {
							type = attributes['type'];
						}
						else if (attributes['style'] !== undefined) {
							type = 'wp';
						}

						if (type !== undefined) {
							$('[name="photonic-editor-shortcode-raw"]').val(scParameter);
							$('[name="photonic-editor-shortcode"]').val(shortcode.content);
							$('[data-photonic-selection-id="' + type + '"]').click();
							$('.photonic-editor-info').empty();
						}
						else {
							$('.photonic-editor-info').html('<div>' + Photonic_Wizard_JS.info_editor_not_shortcode + '</div>');
						}
					}
					else {
						$('.photonic-editor-info').html('<div>' + Photonic_Wizard_JS.info_editor_not_shortcode + '</div>');
					}
				}
				else if (selection === '' && top.wp !== undefined && top.wp.data !== undefined && top.wp.data.select !== undefined &&
					win.photonicBlockProperties !== undefined && win.photonicBlockProperties.attributes !== undefined) { // Gutenberg
					$('[name="photonic-gutenberg-active"]').val(1);
					if (win.photonicBlockProperties.attributes.shortcode !== undefined) {
						shortcode = win.photonicBlockProperties.attributes.shortcode;
						attributes = JSON.parse(shortcode);

						if (attributes.type !== undefined && photonicPermittedGalleries.indexOf(attributes.type) !== -1) {
							type = attributes.type;
						}
						else if (attributes.style !== undefined) {
							type = 'wp';
						}

						if (type !== undefined) {
							$('[name="photonic-editor-json"]').val(shortcode);
							$('[data-photonic-selection-id="' + type + '"]').click();
							$('.photonic-editor-info').empty();
						}
					}
					else {
						$('.photonic-editor-info').html('<div>' + Photonic_Wizard_JS.info_editor_block_select + '</div>');
					}
				}
			}
		}

		if (screen === 6) {
			if ((existing === undefined || existing === '') && (existingBlock === undefined || existingBlock === '')) {
				$('#photonic-nav-next').html(Photonic_Wizard_JS.insert_gallery);
			}
			else {
				$('#photonic-nav-next').html(Photonic_Wizard_JS.update_gallery);
			}
		}
		else {
			$('#photonic-nav-next').html('Next');
		}

		$('.photonic-flow-screen').hide();
		var $activeScreen = $('.photonic-flow-screen[data-screen="' + screen + '"]');
		var fieldSequences = $activeScreen.find('.photonic-flow-field[data-photonic-flow-sequence="1"]');
		var displayType = $activeScreen.find('select[name="display_type"]');
		var popupType = $activeScreen.find('select[name="popup"]');
		$(fieldSequences).each(function(i, v) {
			var group = $(v).attr('data-photonic-flow-sequence-group');
			$('.photonic-flow-field[data-photonic-flow-sequence-group="' + group +'"]').each(function(idx, fieldContainer) {
				var $field = $(fieldContainer).find('input, select');
				var fieldName = $field.attr('name');
				var fieldValue = $('input[type="radio"][name="' + fieldName + '"]:checked').val() || $('input[type="text"][name="' + fieldName + '"], select[name="' + fieldName + '"]').val();

				if (idx !== 0 && (fieldValue === '' || fieldValue === undefined)) {
					$(fieldContainer).hide();
				}

				var siblings = $(fieldContainer).siblings();
				var sequence = parseInt($(fieldContainer).attr('data-photonic-flow-sequence'));
				$field.on('change', function() {
					$('.photonic-flow-error').hide();
					if ($field.val() !== '') {
						$(siblings).each(function (sidx, sibling) {
							if ($(sibling).attr('data-photonic-flow-sequence') > sequence) {
								if ($(sibling).attr('data-photonic-condition') !== undefined) {
									var conditionMet = photonicCheckCondition(JSON.parse($(sibling).attr('data-photonic-condition')));
									if (conditionMet) {
										$(sibling).show();
									}
									else {
										$(sibling).hide();
									}
								}
								else {
									$(sibling).fadeIn();
								}
								photonicShowConditionalFieldValues(sibling);
							}
						});
					}
					else {
						$(siblings).each(function(sidx, sibling){
							if ($(sibling).attr('data-photonic-flow-sequence') > sequence) {
								$(sibling).fadeOut();
							}
						});
					}
				});
			});
		});

		$activeScreen.find('[data-photonic-condition] input, [data-photonic-condition] select').each(function(i, v) {
			var $field = $(v).parents('.photonic-flow-field');
			var $sequences = $(v).parents('[data-photonic-flow-sequence]');
			if ($field.length !== 0 && $sequences.length === 0) {
				$field = $($field[0]);
				if ($field.data('photonicCondition') !== undefined && $field.data('photonicCondition') !== '') {
					var conditionMet = photonicCheckCondition($field.data('photonicCondition'));
					if (conditionMet) {
						$field.show();
					}
					else {
						$field.hide();
					}
				}
			}
		});

		if (screen === 2) {
			// One exception to the above ...
			if (displayType.length > 0 && $(displayType[0]).val() !== '') {
				var fieldContainer = $('[name="for"]').parents('.photonic-flow-field');
				photonicShowConditionalFieldValues(fieldContainer);
				fieldContainer.fadeIn();
			}
		}
		else if (screen === 5) {
			// ... And another exception
			if (popupType.length > 0 && $(popupType[0]).val() !== '' && $(popupType[0]).val() !== 'hide') {
				var managedFields = $('[name="photo_count"], [name="photo_more"], [name="photo_layout"]').parents('.photonic-flow-field');
				$(managedFields).each(function(mi, mv) {
					var $mv = $(mv);
					photonicShowConditionalFieldValues($mv);
					$mv.fadeIn();
				});
			}
		}

		$activeScreen.show();
		if (screen === 1) {
			$('.photonic-flow-navigation a.previous').addClass('disabled');
		}
		else {
			$('.photonic-flow-navigation a.previous').removeClass('disabled');
		}
	};

	$('.photonic-flow-navigation a.disabled').click(function(e) {
		e.preventDefault();
	});

	$('.photonic-flow-navigation a').on('click', function(e) {
		if (!$(this).hasClass('disabled')) {
			e.preventDefault();
			var $waiting = $('.photonic-waiting');
			$waiting.show();
			var activeScreen = $('.photonic-flow-screen:visible').data('screen');
			var nextScreen = activeScreen + 1;
			var previousScreen = activeScreen - 1;
			var $form = $('#photonic-flow');
			var parameters = $form.serialize();

			var $activeScreenElement = $('.photonic-flow-screen[data-screen="' + activeScreen + '"]');
			if ($(this).hasClass('next')) {
				var shortcode = $activeScreenElement.find('#photonic_shortcode');
				var screenParameters = $activeScreenElement.find('input, select, textarea').serialize();
				var submission = $activeScreenElement.attr('data-submitted');

				parameters += ((parameters === '') ? '' : '&') + 'action=photonic_wizard_next_screen&screen=' + activeScreen + '&_ajax_nonce=' + $(this).data('photonicNonce');
				// Make AJAX call if we are on the last screen, or if the current screen's parameters have changed since the last time.
				// Otherwise just get the previously fetched screen. This saves a server call, and also helps preserve screen changes not sent to the back-end.
				if (shortcode.length > 0) {
					var win = window.dialogArguments || opener || parent || top;
					var editor, hasTinymce = typeof win.tinymce !== 'undefined';
					if (top.wp !== undefined && top.wp.data !== undefined && top.wp.data.select !== undefined) { // Gutenberg
						editor = top.wp.data.select('core/editor');
					}
					else if (!wpActiveEditor ) { // TinyMCE, activating
						if (hasTinymce && win.tinymce.activeEditor ) {
							editor = win.tinymce.activeEditor;
							wpActiveEditor = editor.id;
						}
					}
					else if ( hasTinymce ) { // TinyMCE, activated
						editor = win.tinymce.get( wpActiveEditor );
					}

					if (editor !== undefined && editor && editor.isHidden !== undefined && !editor.isHidden() && win.photonicClickedNode !== undefined) { // TinyMCE Editor
						var node = win.photonicClickedNode;
						$(node).attr('data-wpview-text', encodeURIComponent($(shortcode[0]).html()));
						tb_close();
					}
					else if (editor !== undefined && editor && editor.isHidden === undefined && editor.getSelectedBlock !== undefined &&
						win.photonicBlockProperties !== undefined && win.photonicBlockProperties.attributes !== undefined) { // Gutenberg
						win.photonicBlockProperties.setAttributes({ shortcode: $(shortcode).val() });
						tb_close();
					}
					else if (photonicIsWidget) {
						win.photonicWidgetData.attr('value', $(shortcode[0]).html());
						win.photonicWidgetData.change();
						tb_close();
					}
					else {
						win.send_to_editor($(shortcode[0]).html());
					}
				}
				else if (activeScreen === 2 && $('input[name="provider"]').val() === 'wp' && $('select[name="display_type"]').val() === 'multi-photo') {
					photonicInitializeWPMediaLibrary(activeScreen, nextScreen, $activeScreenElement, screenParameters, $(this));
					if (photonicNativeWPMediaLibrary !== undefined) {
						photonicNativeWPMediaLibrary.open();
					}
					$waiting.hide();
				}
				else if (activeScreen === photonicLastActiveScreen || submission !== screenParameters || photonicMustPost) {
					photonicPostFlowData(activeScreen, nextScreen, $activeScreenElement, screenParameters, parameters);
				}
				else {
					photonicFlowLogic(nextScreen);
					$waiting.hide();
				}
			}
			else if ($(this).hasClass('previous')) {
				var $forceScreen = $activeScreenElement.find('input[name="force_previous_screen"]');
				if ($forceScreen.length > 0 && $($forceScreen[0]).val() > -1) {
					previousScreen = $($forceScreen[0]).val();
				}
				photonicFlowLogic(previousScreen);
				$waiting.hide();
			}
		}
	});

	$('.photonic-gallery a').click(function(e) {
		e.preventDefault();
		$('.photonic-gallery a').removeClass('selected');
		var $clicked = $(this);
		$clicked.addClass('selected');
		$('#provider').val($clicked.data('provider'));
	});

	$(document).on('click', '.photonic-flow-selector', function(e) {
		e.preventDefault();
		var $clicked = $(this);
		var $container = $($clicked.parents('.photonic-flow-selector-container')[0]);
		var selectionMode = $container.attr('data-photonic-flow-selector-mode');
		if (selectionMode === 'none') {
			return;
		}
		else if (selectionMode === 'single' || selectionMode === 'single-no-plus') {
			$container.find('.photonic-flow-selector').removeClass('selected');
			$container.find('.photonic-flow-selector .dashicons').remove();
			if ($container.attr('data-photonic-flow-selector-for') === 'selected_data') {
				var $selection_passworded = $('input[name="selection_passworded"]');
				if ($clicked.hasClass('passworded')) {
					if ($selection_passworded.val() === '') {
						photonicMustPost = true;
					}
					$selection_passworded.val('1');
				}
				else {
					if ($selection_passworded.val() === '1' || $selection_passworded.val() === 1) {
						photonicMustPost = true;
					}
					$selection_passworded.val('');
				}
			}
		}

		if (selectionMode === 'multi') {
			$clicked.addClass('selected');
			$clicked.append('<a class="dashicons dashicons-plus" href="#"></a>');
		}
		else if (selectionMode === 'single-no-plus' || selectionMode === 'single') {
			$clicked.addClass('selected');
		}
		photonicUpdateSelection($clicked);
	});

	$(document).on('mouseenter', '.photonic-flow-selector-container[data-photonic-flow-selector-mode="multi"] .dashicons', function() {
		$(this).toggleClass('dashicons-plus');
		$(this).toggleClass('dashicons-minus');
	});

	$(document).on('mouseleave', '.photonic-flow-selector-container[data-photonic-flow-selector-mode="multi"] .dashicons', function() {
		$(this).toggleClass('dashicons-plus');
		$(this).toggleClass('dashicons-minus');
	});

	$(document).on('click', '.photonic-mark', function(e) {
		e.preventDefault();
		var $clicked = $(this);
		var markFor = $clicked.data('photonicMarkFor');
		var $thumbnails = $('.photonic-flow-selector-container[data-photonic-flow-selector-for="' + markFor + '"]').find('.photonic-flow-selector');
		var selection = '';
		$thumbnails.each(function(i, o) {
			if ($clicked.hasClass('photonic-mark-all') && !$(o).hasClass('selected')) {
				$(o).addClass('selected');
				$(o).append('<a class="dashicons dashicons-plus" href="#"></a>');
				selection += $(o).children('.photonic-flow-selector-inner').data('photonicSelectionId') + ',';
			}
			else if ($clicked.hasClass('photonic-mark-none')) {
				$(o).removeClass('selected');
				$(o).find('.dashicons').remove();
			}
		});
		if (selection !== '') {
			selection = selection.replace(/^,+|,+$/g, '');
		}
		$('input[name="' + markFor + '"]').val(selection);
	});

	$(document).on('click', '.photonic-flow-selector-container[data-photonic-flow-selector-mode="multi"] .dashicons', function(e) {
		e.preventDefault();
		e.stopPropagation();
		var $selector = $(this).parents('.photonic-flow-selector');
		$selector.removeClass('selected');
		$(this).remove();
		photonicUpdateSelection($selector);
	});

	$(document).on('click', 'a.photonic-add-date-filter', function(e) {
		e.preventDefault();
		var dateFilterField = $(this).data('photonicAddDate');
		var list = $('ol[data-photonic-date-filter="' + dateFilterField + '"]');
		var dateFilterCount = list.data('photonicFilterCount');
		var currentCount = list.children().length;
		var listItem = $('<li></li>');
		var parts = ['Year', 'Month', 'Date'];
		var texts = ['Year (0 - 9999)', 'Month (0 - 12)', 'Date (0 - 31)'];
		var div = $('<div class="photonic-single-date"></div>');
		$(parts).each(function(j, part) {
			div.append(
				"<label class='photonic-date-filter'>" +
					part.substr(0, 1) +
					"<input type='text' class='photonic-date-" + part.toLowerCase() + "' name='" + dateFilterField + "_" + part.toLowerCase() + "[]' aria-describedby='date_filter_"+ dateFilterField + "_" + currentCount + "_" + part.toLowerCase() + "-hint'/>" +
					"<div class='photonic-flow-hint' role='tooltip' id='date_filter_" + dateFilterField + "_" + currentCount + "_" + part.toLowerCase() + "-hint'>" + texts[j] + "</div>" +
				"</label>"
			);
		});
		listItem.append(div);
		listItem.append("<a href='#' class='photonic-remove-date-filter' title='Remove filter'><span class=\"dashicons dashicons-no\"> </span></a>");
		list.append(listItem);
		if (list.children().length === dateFilterCount) {
			$(this).hide();
		}
	});

	$(document).on('click', 'a.photonic-add-date-range-filter', function(e) {
		e.preventDefault();
		var dateFilterField = $(this).data('photonicAddDateRange');
		var list = $('ol[data-photonic-date-range-filter="' + dateFilterField + '"]');
		var dateFilterCount = list.data('photonicFilterCount');
		var currentCount = list.children().length;
		var listItem = $('<li></li>');
		var parts = ['Year', 'Month', 'Date'];
		var range_parts = ['start', 'finish'];
		var texts = ['Year (0 - 9999)', 'Month (0 - 12)', 'Date (0 - 31)'];
		$(range_parts).each(function(i, range_part) {
			var div = $('<div class="photonic-single-date"></div>');
			$(parts).each(function(j, part) {
				div.append(
					"<label class='photonic-date-filter'>" +
						part.substr(0, 1) +
						"<input type='text' class='photonic-date-" + part.toLowerCase() + "' name='" + dateFilterField + "_" + range_part + "_" + part.toLowerCase() + "[]' aria-describedby='date_range_filter_"+ dateFilterField + "_" + currentCount + "_" + range_part + "_" + part.toLowerCase() + "-hint'/>" +
						"<div class='photonic-flow-hint' role='tooltip' id='date_range_filter_" + dateFilterField + "_" + currentCount + "_" + range_part + "_" + part.toLowerCase() + "-hint'>" + texts[j] + "</div>" +
					"</label>"
				);
			});
			listItem.append(div);
		});

		listItem.append("<a href='#' class='photonic-remove-date-range-filter' title='Remove filter'><span class=\"dashicons dashicons-no\"> </span></a>");
		list.append(listItem);
		if (list.children().length === dateFilterCount) {
			$(this).hide();
		}
	});

	$(document).on('click', 'a.photonic-remove-date-filter', function(e) {
		e.preventDefault();
		var listItem = $($(this).parents('li')[0]);
		var list = $(listItem.parent());
		var dateFilterField = list.data('photonicDateFilter');
		var dateFilterCount = list.data('photonicFilterCount');
		var addButton = $("a[data-photonic-add-date='" + dateFilterField + "']");
		if (addButton.length === 0) {
			addButton = $("<a href='#' class='photonic-add-date-filter' data-photonic-add-date='" + dateFilterField + "'><span class=\"dashicons dashicons-plus-alt\"> </span> Add filter</a>\n");
			addButton.insertAfter(list).hide();
		}
		$(listItem).remove();
		if (list.children().length < dateFilterCount) {
			addButton.show();
		}
	});

	$(document).on('click', 'a.photonic-remove-date-range-filter', function(e) {
		e.preventDefault();
		var listItem = $($(this).parents('li')[0]);
		var list = $(listItem.parent());
		var dateFilterField = list.data('photonicDateRangeFilter');
		var dateFilterCount = list.data('photonicFilterCount');
		var addButton = $("a[data-photonic-add-date-range='" + dateFilterField + "']");
		if (addButton.length === 0) {
			addButton = $("<a href='#' class='photonic-add-date-range-filter' data-photonic-add-date-range='" + dateFilterField + "'><span class=\"dashicons dashicons-plus-alt\"> </span> Add filter</a>\n");
			addButton.insertAfter(list).hide();
		}
		$(listItem).remove();
		if (list.children().length < dateFilterCount) {
			addButton.show();
		}
	});

	$(document).on('change', 'input[class^=photonic-date-]', function() {
		var $changed = $(this);
		var $container = $changed.parents('ol');
		if ($container.length > 0) {
			$container = $($container[0]);
			var range = $container.attr('data-photonic-date-range-filter') !== undefined;
			var $dates = $container.children('li');
			var listMerge = [];
			$dates.each(function(li, v) {
				var itemMerge = [];
				var $dateFields = $(v).find('input');
				if ($dateFields.length > 0) {
					itemMerge[itemMerge.length] = [];
				}
				if ($dateFields.length > 3) {
					itemMerge[itemMerge.length] = [];
				}
				$dateFields.each(function(i, d) {
					var mod = Math.floor(i/3);
					var div = i % 3;
					itemMerge[mod][div] = $(d).val() === '' ? 0 : $(d).val();
				});
				$(itemMerge).each(function(i,d) {
					itemMerge[i] = d.join('/');
				});
				listMerge[listMerge.length] = itemMerge.join('-');
			});
			listMerge = listMerge.join();
			if (range) {
				$('input[name="' + $container.attr('data-photonic-date-range-filter') + '"]').val(listMerge);
			}
			else {
				$('input[name="' + $container.attr('data-photonic-date-filter') + '"]').val(listMerge);
			}
		}
	});

	$(document).on('click', 'a.photonic-flow-more', function(e) {
		e.preventDefault();
		var $waiting = $('.photonic-waiting');
		var $clicked = $(this);
		var link = $clicked.data('photonicMoreLink');
		var existing = $('input[name="photonic-editor-shortcode"]').val();
		var existingBlock = $('input[name="photonic-editor-json"]').val();
		var win = window.dialogArguments || opener || parent || top;

		var shortcode, attributes, albumFilter;


		if (existing && top.wp !== undefined && top.wp.shortcode !== undefined) {
			shortcode = top.wp.shortcode.next(Photonic_Wizard_JS.shortcode, existing.trim());
			if (shortcode !== undefined && shortcode.content.length === existing.trim().length) {
				attributes = shortcode.shortcode.attrs.named;
				if (attributes['filter']) {
					albumFilter = attributes['filter'];
				}
			}
		}
		else if (existingBlock && top.wp !== undefined && top.wp.data !== undefined && top.wp.data.select !== undefined &&
			win.photonicBlockProperties !== undefined && win.photonicBlockProperties.attributes !== undefined) { // Gutenberg
			if (win.photonicBlockProperties.attributes.shortcode !== undefined) {
				shortcode = win.photonicBlockProperties.attributes.shortcode;
				attributes = JSON.parse(shortcode);

				if (attributes.filter !== undefined) {
					albumFilter = attributes.filter;
				}
			}
		}

		if (link !== undefined && link !== '') {
			$waiting.show();
			var parameters = [];
			parameters['action'] = 'photonic_wizard_more';
			parameters['provider'] = $clicked.data('photonicProvider');
			parameters['display_type'] = $clicked.data('photonicDisplayType');
			parameters['url'] = encodeURIComponent($clicked.data('photonicMoreLink'));
			if (albumFilter) {
				albumFilter = '&filter=' + albumFilter;
			}
			else {
				albumFilter = '';
			}
			parameters = 'action=photonic_wizard_more&provider=' + $clicked.data('photonicProvider') + '&display_type=' + $clicked.data('photonicDisplayType') + '&url=' + btoa($clicked.data('photonicMoreLink')) + albumFilter + '&_ajax_nonce=' + $clicked.data('photonicNonce');
			$.post(Photonic_Wizard_JS.ajaxurl, parameters, function(data){
				$clicked.hide();
				$clicked.parents('.photonic-more-wrapper').remove();
				$(data).insertAfter('.photonic-flow-selector:last');
				var $search = $('#thumb-search');
				$search.focus().blur();
				$search.trigger('input');
				photonicUpdateSelection($('.photonic-flow-selector:last'));
				$waiting.hide();
			});
		}
	});

	$(document).on('mouseenter', 'input, select', function() {
		//clearTimeout($(this).data('timeoutId'));
		var $tooltip = $('#' + $(this).attr('aria-describedby'));
		$tooltip.attr('aria-hidden', false);
		$tooltip.css({ display: 'block' });
		$tooltip.fadeIn();
	});

	$(document).on('mouseleave', 'input, select', function() {
		var tooltipId = '#' + $(this).attr('aria-describedby');
		var $tooltip = $(tooltipId);
		if ($(tooltipId + ':hover').length === 0) {
			$tooltip.attr('aria-hidden', true);
			$tooltip.css({ display: 'none' });
		}
	});

	$(document).on('mouseleave', '.photonic-flow-hint', function() {
		var $tooltip = $(this);
		var tooltipId = $tooltip.attr('id');
		if ($('[aria-describedby="' + tooltipId + '"]:hover').length === 0) {
			$tooltip.attr('aria-hidden', true);
			$tooltip.css({ display: 'none' });
		}
	});

	$(document).on('focus', '#thumb-search', function(e) {
		var $search = $(this);
		var $imgs = $('.photonic-flow-selector-container img');
		var cache = [];

		$imgs.each(function() {
			cache.push({
				element: this,
				text: this.alt.trim().toLowerCase()
			})
		});

		function filter() {
			var query = this.value.trim().toLowerCase();
			cache.forEach(function(img) {
				var index = 0;
				if (query) {
					index = img.text.indexOf(query);
				}
				if (index === -1) {
					$(img.element).parents('.photonic-flow-selector').css({ display: 'none' });
				}
				else {
					$(img.element).parents('.photonic-flow-selector').css({ display: 'inline-block' });
				}
			});
		}

		if ('oninput' in $search[0]) {
			$search.on('input', filter);
		}
		else {
			$search.on('keyup', filter);
		}
	});

	$('[name="selected_data"],[name="selection_passworded"]').on('change', function(){
		photonicMustPost = true;
	});

	photonicFlowLogic(1);
});
