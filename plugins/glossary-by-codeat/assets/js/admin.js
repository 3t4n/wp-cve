"use strict";
function prompt_generator() {
	var title = jQuery('#title').val();
	if (jQuery('#title').length === 0) {
		title = jQuery('.wp-block-post-title').html();

		if (typeof title === 'string' && title.indexOf('<span') > 0) {
			title = '';
		}

		if (title === 'undefined' || typeof title === 'undefined') {
			title = '';
		}
	}

	if (jQuery('.elementor-screen-only').length > 0) {
		title = jQuery('.elementor-screen-only').html().split(/\"/)[1];
	}

	if ( title === '' ) {
		jQuery('textarea#glossary_openai_Prompt, textarea[data-setting="section_editor-glossary-chatgpt"]').attr('placeholder', window.glossaryAdmindata.warning)
	} else {
		jQuery('textarea#glossary_openai_Prompt, textarea[data-setting="section_editor-glossary-chatgpt"]').val(window.glossaryAdmindata.prompt.replace('[replaceme]', title ))
	}
}

(function ($) {
	$(function () {
		$(".postbox .gl-labels span").each(function (k, v) {
			var $this = $(v);
			if ($this.text() === "0") {
				$this.addClass("zero-el");
			}
		});
		$(
			"#glossary_post_metabox input:checkbox, #glossary_metabox input:checkbox, .glossary_page_glossary .cmb-td input:checkbox"
		).each(function (k, v) {
			var $this = $(v);
			if ($this.is(":checkbox") && !$this.data("checkbox-replaced")) {
				// add some data to this checkbox so we can avoid re-replacing it.
				$this.data("checkbox-replaced", true);

				// create HTML for the new checkbox.
				var $l = $(
					'<label for="' + $this.attr("id") + '" class="chkbox"></label>'
				);
				var $y = $('<span class="yes">checked</span>');
				var $n = $('<span class="no">unchecked</span>');
				var $t = $('<span class="toggle"></span>');

				// insert the HTML in before the checkbox.
				$l.append($y, $n, $t).insertBefore($this);
				$this.addClass("replaced");

				// check if the checkbox is checked, apply styling. trigger focus.
				$this.on("change", function () {
					if ($this.is(":checked")) {
						$l.addClass("on");
					} else {
						$l.removeClass("on");
					}

					$this.trigger("focus");
				});

				$this.on("focus", function () {
					$l.addClass("focus");
				});
				$this.on("blur", function () {
					$l.removeClass("focus");
				});

				// check if the checkbox is checked on init.
				if ($this.is(":checked")) {
					$l.addClass("on");
				} else {
					$l.removeClass("on");
				}
			}
		});
		$(
			"#glossary_post_metabox .cmb2-radio-list input, #glossary_metabox .cmb2-radio-list input, .glossary_page_glossary .cmb2-radio-list input"
		).each(function (k, v) {
			var $this = $(v);
			if ($this.is(":radio") && !$this.data("radio-replaced")) {
				// add some data to this checkbox so we can avoid re-replacing it.
				$this.data("radio-replaced", true);

				// create HTML for the new checkbox.
				var $l = $(
					'<label for="' + $this.attr("id") + '" class="rdio"></label>'
				);
				var $p = $('<span class="pip"></span>');

				// insert the HTML in before the checkbox.
				$l.append($p).insertBefore($this);
				$this.addClass("replaced");

				// check if the radio is checked, apply styling. trigger focus.
				$this.on("change", function () {
					$("label.rdio").each(function (k, v) {
						var $v = $(v);
						if ($("#" + $v.attr("for")).is(":checked")) {
							$v.addClass("on");
						} else {
							$v.removeClass("on");
						}
					});

					$this.trigger("focus");
				});

				$this.on("focus", function () {
					$l.addClass("focus");
				});
				$this.on("blur", function () {
					$l.removeClass("focus");
				});

				// check if the radio is checked on init.
				$("label.rdio").each(function (k, v) {
					var $v = $(v);
					if ($("#" + $v.attr("for")).is(":checked")) {
						$v.addClass("on");
					} else {
						$v.removeClass("on");
					}
				});
			}
		});

		prompt_generator();
		jQuery('#title, .wp-block-post-title').on('change, input', prompt_generator);
		setTimeout(function() {
			prompt_generator();
			jQuery('.wp-block-post-title').on('input', prompt_generator);
		}, 1500);

		if (typeof elementor !== 'undefined') {
			elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model, view ) {
				jQuery('textarea[data-setting="section_editor-glossary-chatgpt"]').parent().parent().find('.e-ai-button').hide();
				prompt_generator();
				jQuery('.elementor-control-section_editor-glossary-chatgpt-button button').on('click', function() {
					chatgpt_click();
				});
			} );
		}

		function chatgpt_click() {
			var textarea = 'textarea#glossary_openai_Prompt, textarea[data-setting="section_editor-glossary-chatgpt"]';
			var _prompt = jQuery(textarea).val();
			jQuery(textarea).val(window.glossaryAdmindata.waiting).prop('disabled', true);
			jQuery.ajax( {
				method: 'GET',
				url: location.href.replace(/[^/]*$/, '').replace('/wp-admin/','') + '/wp-json/wp/v2/glossary/generate',
				data: {
					nonce: window.glossaryAdmindata.nonce,
				prompt: _prompt,
				},
				beforeSend( xhr ) {
					xhr.setRequestHeader(
						'X-WP-Nonce',
						window.glossaryAdmindata.wp_rest
					);
				},
			}
			)
			.done( function (data) {
				if ( jQuery('.wp-block-post-content').length >= 1 ) {
					//wp.data.dispatch( 'core/block-editor' ).resetBlocks([]);
					var el = wp.element.createElement;
					var name = 'core/html';
					var insertedBlock = wp.blocks.createBlock(name, {
						content: data,
					});
					wp.data.dispatch('core/editor').insertBlocks(insertedBlock);
				} else {
					jQuery('#content').val(data);
				}
				if (typeof elementor !== 'undefined') {
					tinyMCE.activeEditor.setContent(data);
				}
				jQuery(textarea).val(_prompt).prop('disabled', false);
			} )
			.fail( function (jqXHR) {
				alert( window.glossaryAdmindata.alert + "\n" + jqXHR.responseText );
				jQuery(textarea).val(_prompt).prop('disabled', false);
			} );
		}

		jQuery('input#glossary_openai_Prompt.button').on('click', function() {
			chatgpt_click();
		});
	});
})(jQuery);
