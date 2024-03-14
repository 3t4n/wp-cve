(function ($) {
	if ('undefined' !== typeof wp && 'undefined' !== typeof wp.customize) {
		// redirect customizer to login customizer if panel is expanded
		(function (api) {
			api.panel('clp_panel', function (panel) {
				var loginURL = clpCfg.siteurl + '?clp-customize=true&action=login';
				panel.expanded.bind(function (isExpanded) {
					if (isExpanded) {
						wp.customize.previewer.previewUrl.set(loginURL);
					} else {
						wp.customize.previewer.previewUrl.set(clpCfg.siteurl);
					}
				});

				$(
					'<li class="accordion-section control-section control-section-clp-pro cannot-expand control-section-default"><h3 class="wp-ui-highlight"><a class="wp-ui-text-highlight" href="' +
						clpCfg.proUrl +
						'" target="_blank">More Options Available in PRO</a></h3></li>'
				).insertBefore('#accordion-section-clp_templates');
			});
		})(wp.customize);

		(function (api) {
			api.section('clp_register', function (section) {
				var registerURL = clpCfg.siteurl + '?clp-customize=true&action=register';
				var loginURL = clpCfg.siteurl + '?clp-customize=true&action=login';
				section.expanded.bind(function (isExpanded) {
					if (isExpanded) {
						wp.customize.previewer.previewUrl.set(registerURL);
					} else {
						wp.customize.previewer.previewUrl.set(loginURL);
					}
				});
			});
		})(wp.customize);

		// display test message when messages section is opened
		(function (api) {
			api.section('clp_messages', function (section) {
				section.expanded.bind(function (isExpanded) {
					if (isExpanded) {
						var html = $.parseHTML(
							'<div class="message clp-message"><strong>TEST:</strong> This is a test message to set the message colors.</div>'
						);
						$('iframe').contents().find('#login').prepend(html);
					} else {
						$('iframe').contents().find('#login .clp-message').remove();
					}
				});
			});
		})(wp.customize);

		// custom templates
		wp.customize.controlConstructor['clp-template'] = wp.customize.Control.extend({
			ready: function () {
				var control = this;

				this.container.on('change', 'input:radio', function () {
					var template = $(this).val();
					// update input field with template name
					$('#clp-templates').val(template).trigger('change');
					// reload all load template controls
					control.loadTemplate('default');
					if ('default' !== template) {
						control.loadTemplate(template);
					}
				});
			},
			loadTemplate: function (optionName) {
				var control = this,
					options = control.params.options[optionName];
				$.each(options, function (index, option) {
					var currentControl = wp.customize.control(option.name);
					currentControl.setting(option.value);

					if (currentControl.id === 'clp_logo-google_fonts') {
						var family = JSON.parse(option.value);
						$('#customize-control-clp_logo-google_fonts .clp-font-family-select')
							.val(family.family)
							.change();
						$('#customize-control-clp_logo-google_fonts .clp-font-variant-select')
							.val(family.selected.variant)
							.change();
					}

					if (currentControl.id === 'clp_form_typography-google_fonts') {
						var family = JSON.parse(option.value);
						$('#customize-control-clp_form_typography-google_fonts .clp-font-family-select')
							.val(family.family)
							.change();
						$('#customize-control-clp_form_typography-google_fonts .clp-font-variant-select')
							.val(family.selected.variant)
							.change();
					}

					if (currentControl.params.type === 'alpha-color') {
						$(currentControl.selector + ' input').change();
					}
				});
			},
		});

		wp.customize.bind('ready', function () {
			wp.customize.previewer.bind('clp-focus-section', function (section) {
				if (wp.customize.section(section)) {
					wp.customize.section(section).focus();
				}
			});

			wp.customize('clp_logo', function (value) {
				value.bind(function (to) {
					var logoText = wp.customize.control('clp_logo-text'),
						logoTextFontSize = wp.customize.control('clp_logo-text-font_size'),
						logoTextLetterSpacing = wp.customize.control('clp_logo-text-letter_spacing'),
						logoTypographySep = wp.customize.control('clp_logo-typography-separator'),
						logoTypography = wp.customize.control('clp_logo-google_fonts'),
						logoTextColor = wp.customize.control('clp_logo-text-color'),
						logoImage = wp.customize.control('clp_logo-image'),
						logoImageWidth = wp.customize.control('clp_logo-image-width'),
						logoUrl = wp.customize.control('clp_logo-url'),
						logoSpacingTop = wp.customize.control('clp_logo-spacing-top'),
						logoSpacingBottom = wp.customize.control('clp_logo-spacing-bottom'),
						logoSeparator = wp.customize.control('clp_logo-settings-separator'),
						logoContained = wp.customize.control('clp_logo-contained');

					switch (to) {
						case 'none':
							logoText.toggle(false);
							logoTextFontSize.toggle(false);
							logoTextLetterSpacing.toggle(false);
							logoTypographySep.toggle(false);
							logoTypography.toggle(false);
							logoTextColor.toggle(false);
							logoImage.toggle(false);
							logoImageWidth.toggle(false);
							logoUrl.toggle(false);
							logoSpacingTop.toggle(false);
							logoSpacingBottom.toggle(false);
							logoSeparator.toggle(false);
							logoContained.toggle(false);
							break;

						case 'text':
							logoText.toggle(true);
							logoTextFontSize.toggle(true);
							logoTextLetterSpacing.toggle(true);
							logoTypographySep.toggle(true);
							logoTypography.toggle(true);
							logoTextColor.toggle(true);
							logoImage.toggle(false);
							logoImageWidth.toggle(false);
							logoUrl.toggle(true);
							logoSpacingTop.toggle(true);
							logoSpacingBottom.toggle(true);
							logoSeparator.toggle(true);
							logoContained.toggle(true);
							break;

						case 'image':
							logoText.toggle(false);
							logoTextFontSize.toggle(false);
							logoTextLetterSpacing.toggle(false);
							logoTypographySep.toggle(false);
							logoTypography.toggle(false);
							logoTextColor.toggle(false);
							logoImage.toggle(true);
							logoImageWidth.toggle(true);
							logoUrl.toggle(true);
							logoSpacingTop.toggle(true);
							logoSpacingBottom.toggle(true);
							logoSeparator.toggle(true);
							logoContained.toggle(true);
							break;

						default:
							break;
					}
				});
			});

			wp.customize('clp_background-overlay-enable', function (value) {
				value.bind(function (to) {
					var overlayColor = wp.customize.control('clp_background-overlay-color');
					if (to) {
						overlayColor.toggle(true);
					} else {
						overlayColor.toggle(false);
					}
				});
			});

			wp.customize('clp_form-borders', function (value) {
				value.bind(function (to) {
					var borderWidth = wp.customize.control('clp_form-border_width'),
						borderColor = wp.customize.control('clp_form-border_color');

					if (to) {
						borderWidth.toggle(true);
						borderColor.toggle(true);
					} else {
						borderWidth.toggle(false);
						borderColor.toggle(false);
					}
				});
			});

			wp.customize('clp_form-shadow', function (value) {
				value.bind(function (to) {
					var horLen = wp.customize.control('clp_form-shadow-horizontal_length'),
						verLen = wp.customize.control('clp_form-shadow-vertical_length'),
						blurRad = wp.customize.control('clp_form-shadow-blur_radius'),
						spreadRad = wp.customize.control('clp_form-shadow-spread_radius'),
						color = wp.customize.control('clp_form-shadow-color');

					if (to) {
						horLen.toggle(true);
						verLen.toggle(true);
						blurRad.toggle(true);
						spreadRad.toggle(true);
						color.toggle(true);
					} else {
						horLen.toggle(false);
						verLen.toggle(false);
						blurRad.toggle(false);
						spreadRad.toggle(false);
						color.toggle(false);
					}
				});
			});

			wp.customize('clp_form_footer-display_forgetpassword', function (value) {
				value.bind(function (to) {
					var forgetPassText = wp.customize.control('clp_form_footer-forgetpassword_text');
					var forgetPassColor = wp.customize.control('clp_form_footer-forgetpassword_color');
					var forgetPassColorHover = wp.customize.control(
						'clp_form_footer-forgetpassword_color_hover'
					);
					var forgetPassText = wp.customize.control('clp_form_footer-forgetpassword_text');
					var logInText = wp.customize.control('clp_form_footer-login_text');
					if (to) {
						forgetPassText.toggle(true);
						logInText.toggle(true);
						forgetPassColor.toggle(true);
						forgetPassColorHover.toggle(true);
					} else {
						forgetPassText.toggle(false);
						logInText.toggle(false);
						forgetPassColor.toggle(false);
						forgetPassColorHover.toggle(false);
					}
				});
			});

			wp.customize('clp_form_footer-display_backtoblog', function (value) {
				value.bind(function (to) {
					var backToSiteText = wp.customize.control('clp_form_footer-backtoblog_text');
					var backToSiteColor = wp.customize.control('clp_form_footer-backtoblog_color');
					var backToSiteColorHover = wp.customize.control('clp_form_footer-backtoblog_color_hover');
					if (to) {
						backToSiteText.toggle(true);
						backToSiteColor.toggle(true);
						backToSiteColorHover.toggle(true);
					} else {
						backToSiteText.toggle(false);
						backToSiteColor.toggle(false);
						backToSiteColorHover.toggle(false);
					}
				});
			});
			wp.customize('clp_form_footer-display_register', function (value) {
				value.bind(function (to) {
					var registerText = wp.customize.control('clp_form_footer-register_text');
					var registerColor = wp.customize.control('clp_form_footer-register_color');
					var registerColorHover = wp.customize.control('clp_form_footer-register_color_hover');
					var registerSeparator = wp.customize.control('clp_form_footer-login_link_separator');
					if (to) {
						registerText.toggle(true);
						registerColor.toggle(true);
						registerColorHover.toggle(true);
						registerSeparator.toggle(true);
					} else {
						registerText.toggle(false);
						registerColor.toggle(false);
						registerColorHover.toggle(false);
						registerSeparator.toggle(false);
					}
				});
			});

			wp.customize('clp_input-remember', function (value) {
				value.bind(function (to) {
					var rememberMe = wp.customize.control('clp_input-remember_text');
					if (to) {
						rememberMe.toggle(true);
					} else {
						rememberMe.toggle(false);
					}
				});
			});
			wp.customize('clp_background-pattern', function (value) {
				value.bind(function (to) {
					var customPattern = wp.customize.control('clp_background-pattern-custom');
					if (to === 'custom') {
						customPattern.toggle(true);
					} else {
						customPattern.toggle(false);
					}
				});
			});
			wp.customize('clp_layout-width', function (value) {
				value.bind(function (to) {
					var contentBackgroundColor = wp.customize.control('clp_layout-content-background-color');
					var contentSkew = wp.customize.control('clp_layout-content-skew');
					var contentBlur = wp.customize.control('clp_layout-background-blur');
					if (to !== '100') {
						contentBackgroundColor.toggle(true);
						contentSkew.toggle(true);
						contentBlur.toggle(true);
					} else {
						contentBackgroundColor.toggle(false);
						contentSkew.toggle(false);
						contentBlur.toggle(false);
					}
				});
			});

			wp.customize('clp_form_footer-display_privacy', function (value) {
				value.bind(function (to) {
					var color = wp.customize.control('clp_form_footer-privacy_color');
					var colorHover = wp.customize.control('clp_form_footer-privacy_color_hover');
					if (to) {
						color.toggle(true);
						colorHover.toggle(true);
					} else {
						color.toggle(false);
						colorHover.toggle(false);
					}
				});
			});
			wp.customize('clp_input-label_display', function (value) {
				value.bind(function (to) {
					var settings1 = wp.customize.control('clp_input-font_size_label');
					var settings2 = wp.customize.control('clp_input-label_color');
					var settings3 = wp.customize.control('clp_input-label_font_weight');

					if (to) {
						settings1.toggle(true);
						settings2.toggle(true);
						settings3.toggle(true);
					} else {
						settings1.toggle(false);
						settings2.toggle(false);
						settings3.toggle(false);
					}
				});
			});

			wp.customize('clp_footer-enable', function (value) {
				value.bind(function (to) {
					var footerWidth = wp.customize.control('clp_footer-width');
					var footerPadding = wp.customize.control('clp_footer-padding');
					var footerBackgroundColor = wp.customize.control('clp_footer-background_color');
					var footerTextColor = wp.customize.control('clp_footer-text_color');
					var footerLinkColor = wp.customize.control('clp_footer-link_color');
					var footerLinkColorHover = wp.customize.control('clp_footer-link_color_hover');
					var footerCopyright = wp.customize.control('clp_footer-copyright');
					var footerCopyrightPos = wp.customize.control('clp_footer-copyright_pos');
					var footerCopyrightNT = wp.customize.control('clp_footer-niteothemes');
					var footerCopyrightNTPos = wp.customize.control('clp_footer-niteothemes_pos');
					if (to) {
						footerWidth.toggle(true);
						footerPadding.toggle(true);
						footerBackgroundColor.toggle(true);
						footerTextColor.toggle(true);
						footerLinkColor.toggle(true);
						footerLinkColorHover.toggle(true);
						footerCopyright.toggle(true);
						footerCopyrightPos.toggle(true);
						footerCopyrightNT.toggle(true);
						footerCopyrightNTPos.toggle(true);
					} else {
						footerWidth.toggle(false);
						footerPadding.toggle(false);
						footerBackgroundColor.toggle(false);
						footerTextColor.toggle(false);
						footerLinkColor.toggle(false);
						footerLinkColorHover.toggle(false);
						footerCopyright.toggle(false);
						footerCopyrightPos.toggle(false);
						footerCopyrightNT.toggle(false);
						footerCopyrightNTPos.toggle(false);
					}
				});
			});

			wp.customize('clp_footer-niteothemes', function (value) {
				value.bind(function (to) {
					var ntPos = wp.customize.control('clp_footer-niteothemes_pos');
					if (to) {
						ntPos.toggle(true);
					} else {
						ntPos.toggle(false);
					}
				});
			});
		});
	}
})(jQuery);
