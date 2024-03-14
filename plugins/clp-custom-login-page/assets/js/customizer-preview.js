(function ($) {
	wp.customize('clp_layout-width', function (value) {
		value.bind(function (to) {
			var color = getCustomizerValue('clp_layout-content-background-color');
			var skew = getCustomizerValue('clp_layout-content-skew');
			var alpha = color.replace(/^.*,(.+)\)/, '$1');
			var opaque = !isNaN(alpha) && alpha < 1 ? true : false;

			$('.clp-content').css('width', to + '%');

			if (skew == '0' && !opaque) {
				$('.clp-background-wrapper').css('left', to + '%');
			} else {
				$('.clp-background-wrapper').css('left', '0');
			}

			if (to == '100') {
				$('body').removeClass('clp-content-half');
				$('.clp-background-wrapper').css('left', '0');
			} else {
				$('body').addClass('clp-content-half');
				$('head').append(
					'<style id="clp-content-background-color">.clp-content-half .clp-content:after {background-color: ' +
						color +
						'}'
				);
			}
		});
	});

	wp.customize('clp_layout-content-background-color', function (value) {
		value.bind(function (to) {
			var skew = getCustomizerValue('clp_layout-content-skew');
			var width = getCustomizerValue('clp_layout-width');
			var alpha = to.replace(/^.*,(.+)\)/, '$1');
			var opaque = !isNaN(alpha) && alpha < 1 ? true : false;
			if (opaque || skew != '0') {
				$('.clp-background-wrapper').css('left', '0');
			} else {
				$('.clp-background-wrapper').css('left', width + '%');
			}
			$('#clp-content-background-color').remove();
			$('head').append(
				'<style id="clp-content-background-color">.clp-content-half .clp-content:after {background-color: ' +
					to +
					'}'
			);
		});
	});

	wp.customize('clp_layout-background-blur', function (value) {
		value.bind(function (to) {
			$('#clp-content-background-blur').remove();
			$('head').append(
				'<style id="clp-content-background-blur">.clp-content-half .clp-content:after {backdrop-filter: blur(' +
					to +
					'px)}'
			);
		});
	});
	wp.customize('clp_layout-content-skew', function (value) {
		value.bind(function (to) {
			var width = getCustomizerValue('clp_layout-width');
			var color = getCustomizerValue('clp_layout-content-background-color');
			var alpha = color.replace(/^.*,(.+)\)/, '$1');
			var opaque = !isNaN(alpha) && alpha < 1 ? true : false;

			$('#clp-content-skew').remove();
			$('head').append(
				'<style id="clp-content-skew">.clp-content-half .clp-content:after {transform: skewX(' +
					to +
					'deg)}'
			);

			if (to != '0' || opaque) {
				$('.clp-background-wrapper').css('left', '0');
			} else {
				$('.clp-background-wrapper').css('left', width + '%');
			}

			if (to != '0') {
				$('body').addClass('clp-content-skew');
			} else {
				$('body').removeClass('clp-content-skew');
			}
		});
	});

	wp.customize('clp_background-color', function (value) {
		value.bind(function (to) {
			$('.login-background').css('background-color', to);
		});
	});

	wp.customize('clp_form_typography-google_fonts', function (value) {
		value.bind(function (to) {
			var variant = processGoogleFontVariant(to);
			$('body.login').css('font-family', variant.family);
			$('body.login, .login form, .login form .forgetmenot').css('font-weight', variant.weight);
			$('body.login, .login form, .login form .forgetmenot').css('font-style', variant.style);
		});
	});

	wp.customize('clp_background-gradient-color1', function (value) {
		var gradColor2 = '';
		var gradPos1 = '';
		var gradPos2 = '';
		var angle = '';
		value.bind(function (to) {
			gradColor2 = getCustomizerValue('clp_background-gradient-color2');
			gradPos1 = getCustomizerValue('clp_background-gradient-color1-position');
			gradPos2 = getCustomizerValue('clp_background-gradient-color2-position');
			angle = getCustomizerValue('clp_background-gradient-angle');
			$('.login-background').css(
				'background',
				'linear-gradient(' +
					angle +
					'deg, ' +
					gradColor2 +
					' ' +
					gradPos1 +
					'%, ' +
					to +
					' ' +
					gradPos2 +
					'%)'
			);
		});
	});
	wp.customize('clp_background-gradient-color1-position', function (value) {
		var gradColor1 = '';
		var gradColor2 = '';
		var gradPos2 = '';
		var angle = '';
		value.bind(function (to) {
			gradColor1 = getCustomizerValue('clp_background-gradient-color1');
			gradColor2 = getCustomizerValue('clp_background-gradient-color2');
			gradPos2 = getCustomizerValue('clp_background-gradient-color2-position');
			angle = getCustomizerValue('clp_background-gradient-angle');
			$('.login-background').css(
				'background',
				'linear-gradient(' +
					angle +
					'deg, ' +
					gradColor2 +
					' ' +
					to +
					'%, ' +
					gradColor1 +
					' ' +
					gradPos2 +
					'%)'
			);
		});
	});
	wp.customize('clp_background-gradient-color2-position', function (value) {
		var gradColor1 = '';
		var gradColor2 = '';
		var gradPos1 = '';
		var angle = '';
		value.bind(function (to) {
			gradColor1 = getCustomizerValue('clp_background-gradient-color1');
			gradColor2 = getCustomizerValue('clp_background-gradient-color2');
			gradPos1 = getCustomizerValue('clp_background-gradient-color1-position');
			angle = getCustomizerValue('clp_background-gradient-angle');
			$('.login-background').css(
				'background',
				'linear-gradient(' +
					angle +
					'deg, ' +
					gradColor2 +
					' ' +
					gradPos1 +
					'%, ' +
					gradColor1 +
					' ' +
					to +
					'%)'
			);
		});
	});

	wp.customize('clp_background-gradient-color2', function (value) {
		var gradColor1 = '';
		var gradPos1 = '';
		var gradPos2 = '';
		var angle = '';
		value.bind(function (to) {
			gradColor1 = getCustomizerValue('clp_background-gradient-color1');
			gradPos1 = getCustomizerValue('clp_background-gradient-color1-position');
			gradPos2 = getCustomizerValue('clp_background-gradient-color2-position');
			angle = getCustomizerValue('clp_background-gradient-angle');
			$('.login-background').css(
				'background',
				'linear-gradient(' +
					angle +
					'deg, ' +
					to +
					' ' +
					gradPos1 +
					'%, ' +
					gradColor1 +
					' ' +
					gradPos2 +
					'%)'
			);
		});
	});

	wp.customize('clp_background-gradient-angle', function (value) {
		var gradColor1 = '';
		var gradColor2 = '';
		var gradPos1 = '';
		var gradPos2 = '';
		value.bind(function (to) {
			gradColor1 = getCustomizerValue('clp_background-gradient-color1');
			gradColor2 = getCustomizerValue('clp_background-gradient-color2');
			gradPos1 = getCustomizerValue('clp_background-gradient-color1-position');
			gradPos2 = getCustomizerValue('clp_background-gradient-color2-position');
			$('.login-background').css(
				'background',
				'linear-gradient(' +
					to +
					'deg, ' +
					gradColor2 +
					' ' +
					gradPos1 +
					'%, ' +
					gradColor1 +
					' ' +
					gradPos2 +
					'%)'
			);
		});
	});

	wp.customize('clp_background-pattern', function (value) {
		value.bind(function (to) {
			if (to !== 'custom') {
				$('.login-background').css(
					'background-image',
					'url(' + clpCfg.url + 'assets/img/patterns/' + to + '.png)'
				);
			} else {
				customPattern = getCustomizerValue('clp_background-pattern-custom');
				setBackgroundImageById(customPattern, '.login-background');
			}
		});
	});
	wp.customize('clp_background-pattern-custom', function (value) {
		value.bind(function (to) {
			setBackgroundImageById(to, '.login-background');
		});
	});

	wp.customize('clp_logo', function (value) {
		value.bind(function (to) {
			switch (to) {
				case 'none':
					$('.clp-login-logo').fadeOut();
					break;
				case 'text':
					$('.clp-login-logo').fadeIn();
					$('body').addClass('clp-text-logo');
					break;
				case 'image':
					$('.clp-login-logo').fadeIn();
					$('body').removeClass('clp-text-logo');
					break;

				default:
					break;
			}
		});
	});

	wp.customize('clp_background-image', function (value) {
		value.bind(function (to) {
			if (to) {
				setBackgroundImageById(to, '.login-background');
			} else {
				var unsplashImg = getCustomizerValue('clp_background-unsplash');
				if (unsplashImg) {
					unsplashImg = JSON.parse(unsplashImg);
					var unsplash_img =
						unsplashImg.urls.original + '&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=1920&fit=max';
					$('.login-background').css('background-image', 'url("' + unsplash_img + '")');
				}
			}
		});
	});

	wp.customize('clp_background-unsplash', function (value) {
		value.bind(function (to) {
			var background_img = getCustomizerValue('clp_background-image');
			if (to) {
				var unsplashImg = JSON.parse(to);
				$('.login-background').css(
					'background-image',
					'url("' +
						unsplashImg.urls.original +
						'&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=1920&fit=max")'
				);
			} else {
				setBackgroundImageById(background_img, '.login-background');
			}
		});
	});

	wp.customize('clp_logo-text', function (value) {
		value.bind(function (to) {
			$('.clp-login-logo a').text(to);
		});
	});

	wp.customize('clp_logo-image', function (value) {
		value.bind(function (to) {
			setBackgroundImageById(to, '.clp-login-logo a');
		});
	});

	wp.customize('clp_logo-image-width', function (value) {
		value.bind(function (to) {
			$('.clp-login-logo img').css('max-width', to + 'px');
		});
	});
	wp.customize('clp_logo-image-height', function (value) {
		value.bind(function (to) {
			$('.clp-login-logo img').css('max-height', to + 'px');
		});
	});

	wp.customize('clp_logo-spacing-top', function (value) {
		value.bind(function (to) {
			$('.clp-login-logo').css('margin-top', to + 'px');
		});
	});

	wp.customize('clp_logo-spacing-bottom', function (value) {
		value.bind(function (to) {
			$('.clp-login-logo').css('margin-bottom', to + 'px');
		});
	});

	wp.customize('clp_logo-google_fonts', function (value) {
		value.bind(function (to) {
			var variant = processGoogleFontVariant(to);

			$('.clp-login-logo').css('font-family', variant.family);
			$('.clp-login-logo').css('font-weight', variant.weight);
			$('.clp-login-logo').css('font-style', variant.style);
		});
	});

	wp.customize('clp_logo-text-font_size', function (value) {
		value.bind(function (to) {
			$('.clp-login-logo').css('font-size', to + 'px');
		});
	});

	wp.customize('clp_logo-text-letter_spacing', function (value) {
		value.bind(function (to) {
			$('.clp-login-logo').css('letter-spacing', to + 'px');
		});
	});

	wp.customize('clp_logo-text-color', function (value) {
		value.bind(function (to) {
			$('.clp-login-logo a').css('color', to);
		});
	});

	wp.customize('clp_logo-contained', function (value) {
		var $logo = $('.clp-login-logo');
		value.bind(function (to) {
			to ? $('#login').prepend($logo) : $('.clp-login-form-container').prepend($logo);
		});
	});

	wp.customize('clp_background-overlay-enable', function (value) {
		value.bind(function (to) {
			var color = getCustomizerValue('clp_background-overlay-color');
			$('.login-overlay').css('display', to ? 'block' : 'none');
			$('.login-overlay').css('background-color', color);
		});
	});

	wp.customize('clp_background-overlay-color', function (value) {
		value.bind(function (to) {
			$('.login-overlay').css('background-color', to);
		});
	});

	wp.customize('clp_background-blur', function (value) {
		value.bind(function (to) {
			$('.login-background').css('filter', 'blur(' + to + 'px)');
			to === '0'
				? $('body').removeClass('clp-background-blur')
				: $('body').addClass('clp-background-blur');
		});
	});

	wp.customize('clp_form-width', function (value) {
		value.bind(function (to) {
			$('#loginform').css('width', to + 'px');
		});
	});

	wp.customize('clp_form-height', function (value) {
		value.bind(function (to) {
			$('#login').css('min-height', to + 'px');
		});
	});
	wp.customize('clp_form-padding', function (value) {
		value.bind(function (to) {
			$('#login').css('padding', to + 'px');
		});
	});

	wp.customize('clp_form-background', function (value) {
		value.bind(function (to) {
			$('#login').css('background-color', to);
		});
	});
	wp.customize('clp_form-text_color', function (value) {
		value.bind(function (to) {
			$('.clp-form-container').css('color', to);
		});
	});

	wp.customize('clp_form-blur', function (value) {
		value.bind(function (to) {
			$('#login').css('backdrop-filter', 'blur(' + to + 'px)');
		});
	});

	wp.customize('clp_form-border_radius', function (value) {
		value.bind(function (to) {
			$('#login').css('border-radius', to + 'px');
		});
	});
	wp.customize('clp_form-border_radius', function (value) {
		value.bind(function (to) {
			$('#login').css('border-radius', to + 'px');
		});
	});

	wp.customize('clp_form-borders', function (value) {
		value.bind(function (to) {
			var width = getCustomizerValue('clp_form-border_width');
			var color = getCustomizerValue('clp_form-border_color');
			$('#login').css('border', to ? width + 'px solid ' + color : 'none');
		});
	});

	wp.customize('clp_form-border_width', function (value) {
		value.bind(function (to) {
			$('#login').css('border-width', to + 'px');
		});
	});

	wp.customize('clp_form-border_color', function (value) {
		value.bind(function (to) {
			$('#login').css('border-color', to);
		});
	});

	wp.customize('clp_form-shadow', function (value) {
		value.bind(function (to) {
			var horLen = getCustomizerValue('clp_form-shadow-horizontal_length');
			var verLen = getCustomizerValue('clp_form-shadow-vertical_length');
			var blurRad = getCustomizerValue('clp_form-shadow-blur_radius');
			var spreadRad = getCustomizerValue('clp_form-shadow-spread_radius');
			var color = getCustomizerValue('clp_form-shadow-color');
			$('#login').css(
				'box-shadow',
				to ? horLen + 'px ' + verLen + 'px ' + blurRad + 'px ' + spreadRad + 'px ' + color : 'none'
			);
		});
	});

	wp.customize('clp_form-shadow-horizontal_length', function (value) {
		value.bind(function (to) {
			var verLen = getCustomizerValue('clp_form-shadow-vertical_length');
			var blurRad = getCustomizerValue('clp_form-shadow-blur_radius');
			var spreadRad = getCustomizerValue('clp_form-shadow-spread_radius');
			var color = getCustomizerValue('clp_form-shadow-color');
			$('#login').css(
				'box-shadow',
				to + 'px ' + verLen + 'px ' + blurRad + 'px ' + spreadRad + 'px ' + color
			);
		});
	});
	wp.customize('clp_form-shadow-vertical_length', function (value) {
		value.bind(function (to) {
			var horLen = getCustomizerValue('clp_form-shadow-horizontal_length');
			var blurRad = getCustomizerValue('clp_form-shadow-blur_radius');
			var spreadRad = getCustomizerValue('clp_form-shadow-spread_radius');
			var color = getCustomizerValue('clp_form-shadow-color');
			$('#login').css(
				'box-shadow',
				horLen + 'px ' + to + 'px ' + blurRad + 'px ' + spreadRad + 'px ' + color
			);
		});
	});
	wp.customize('clp_form-shadow-blur_radius', function (value) {
		value.bind(function (to) {
			var horLen = getCustomizerValue('clp_form-shadow-horizontal_length');
			var verLen = getCustomizerValue('clp_form-shadow-vertical_length');
			var spreadRad = getCustomizerValue('clp_form-shadow-spread_radius');
			var color = getCustomizerValue('clp_form-shadow-color');
			$('#login').css(
				'box-shadow',
				horLen + 'px ' + verLen + 'px ' + to + 'px ' + spreadRad + 'px ' + color
			);
		});
	});
	wp.customize('clp_form-shadow-spread_radius', function (value) {
		value.bind(function (to) {
			var horLen = getCustomizerValue('clp_form-shadow-horizontal_length');
			var verLen = getCustomizerValue('clp_form-shadow-vertical_length');
			var blurRad = getCustomizerValue('clp_form-shadow-blur_radius');
			var color = getCustomizerValue('clp_form-shadow-color');
			$('#login').css(
				'box-shadow',
				horLen + 'px ' + verLen + 'px ' + blurRad + 'px ' + to + 'px ' + color
			);
		});
	});
	wp.customize('clp_form-shadow-color', function (value) {
		value.bind(function (to) {
			var horLen = getCustomizerValue('clp_form-shadow-horizontal_length');
			var verLen = getCustomizerValue('clp_form-shadow-vertical_length');
			var blurRad = getCustomizerValue('clp_form-shadow-blur_radius');
			var spreadRad = getCustomizerValue('clp_form-shadow-spread_radius');
			$('#login').css(
				'box-shadow',
				horLen + 'px ' + verLen + 'px ' + blurRad + 'px ' + spreadRad + 'px ' + to
			);
		});
	});

	// button
	wp.customize('clp_button-text', function (value) {
		value.bind(function (to) {
			$('#wp-submit').val(to);
		});
	});
	wp.customize('clp_button-background_color', function (value) {
		value.bind(function (to) {
			var hoverColor = getCustomizerValue('clp_button-background_color_hover');
			setHoverColor($('#wp-submit'), 'background-color', to, hoverColor);
		});
	});

	wp.customize('clp_button-background_color_hover', function (value) {
		value.bind(function (to) {
			var color = getCustomizerValue('clp_button-background_color');
			setHoverColor($('#wp-submit'), 'background-color', color, to);
		});
	});

	wp.customize('clp_button-text_color', function (value) {
		value.bind(function (to) {
			var hoverColor = getCustomizerValue('clp_button-text_color_hover');
			setHoverColor($('#wp-submit'), 'color', to, hoverColor);
		});
	});
	wp.customize('clp_button-text_color_hover', function (value) {
		value.bind(function (to) {
			var color = getCustomizerValue('clp_button-text_color');
			setHoverColor($('#wp-submit'), 'color', color, to);
		});
	});

	wp.customize('clp_button-border_radius', function (value) {
		value.bind(function (to) {
			$('#wp-submit').css('border-radius', to + 'px');
		});
	});

	wp.customize('clp_button-width', function (value) {
		value.bind(function (to) {
			$('#wp-submit').css('min-width', to + '%');
		});
	});
	wp.customize('clp_button-font_size', function (value) {
		value.bind(function (to) {
			$('#wp-submit').css('font-size', to + 'px');
		});
	});
	wp.customize('clp_button-font_weight', function (value) {
		value.bind(function (to) {
			$('#wp-submit').css('font-weight', to);
		});
	});

	wp.customize('clp_button-height', function (value) {
		value.bind(function (to) {
			$('#wp-submit').css('min-height', to + 'px');
		});
	});

	wp.customize('clp_button-align', function (value) {
		value.bind(function (to) {
			$('#login form p.submit').css('text-align', to);
		});
	});

	wp.customize('clp_button-border_color', function (value) {
		value.bind(function (to) {
			var hoverColor = getCustomizerValue('clp_button-border_color_hover');
			setHoverColor($('#wp-submit'), 'border-color', to, hoverColor);
		});
	});

	wp.customize('clp_button-border_color_hover', function (value) {
		value.bind(function (to) {
			var color = getCustomizerValue('clp_button-border_color');
			setHoverColor($('#wp-submit'), 'border-color', color, to);
		});
	});

	wp.customize('clp_button-border_width', function (value) {
		value.bind(function (to) {
			var color = getCustomizerValue('clp_button-border_color');
			$('#wp-submit').css('border', to + 'px solid ' + color);
		});
	});

	// inputs

	wp.customize('clp_input-login_input_text', function (value) {
		value.bind(function (to) {
			$('label[for="user_login"]').text(to);
		});
	});
	wp.customize('clp_input-password_input_text', function (value) {
		value.bind(function (to) {
			$('label[for="user_pass"]').text(to);
		});
	});
	wp.customize('clp_input-background_color', function (value) {
		value.bind(function (to) {
			$(
				'.login input[type="text"], .login input[type="password"], .login input[type="checkbox"]'
			).css('background-color', to);
		});
	});

	wp.customize('clp_input-background_color_focus', function (value) {
		value.bind(function (to) {
			$(
				'.login input[type="text"], .login input[type="password"], .login input[type="checkbox"]'
			).focus(function () {
				$(this).css('background-color', to);
			});
			$(
				'.login input[type="text"], .login input[type="password"], .login input[type="checkbox"]'
			).blur(function () {
				var blurColor = getCustomizerValue('clp_input-background_color');
				$(this).css('background-color', blurColor);
			});
		});
	});

	wp.customize('clp_input-color', function (value) {
		value.bind(function (to) {
			$(
				'.login input[type="text"], .login input[type="password"], .login .button.wp-hide-pw .dashicons'
			).css('color', to);
		});
	});

	wp.customize('clp_input-color_focus', function (value) {
		value.bind(function (to) {
			$('.login input[type="text"], .login input[type="password"]').focus(function () {
				$(this).css('color', to);
			});
			$('.login input[type="text"], .login input[type="password"]').blur(function () {
				var blurColor = getCustomizerValue('clp_input-color');
				$(this).css('color', blurColor);
			});
		});
	});

	wp.customize('clp_input-label_color', function (value) {
		value.bind(function (to) {
			$('.login label').css('color', to);
		});
	});

	wp.customize('clp_input-label_display', function (value) {
		value.bind(function (to) {
			if (to) {
				$('body').removeClass('clp-hide-labels');
			} else {
				$('body').addClass('clp-hide-labels');
			}
		});
	});

	wp.customize('clp_input-height', function (value) {
		value.bind(function (to) {
			$('.login input[type="text"], .login input[type="password"], .button.wp-hide-pw').css(
				'height',
				to + 'px'
			);
		});
	});

	wp.customize('clp_input-font_size_label', function (value) {
		value.bind(function (to) {
			$('.login label').css('font-size', to + 'px');
		});
	});

	wp.customize('clp_input-label_font_weight', function (value) {
		value.bind(function (to) {
			$('.login label').css('font-weight', to);
		});
	});

	wp.customize('clp_input-font_size_input', function (value) {
		value.bind(function (to) {
			$('.login form .input, .login input[type="text"], .login input[type="password"]').css(
				'font-size',
				to + 'px'
			);
		});
	});

	wp.customize('clp_input-border_color', function (value) {
		value.bind(function (to) {
			$(
				'.login input[type="text"], .login input[type="password"], .login input[type="checkbox"]'
			).css('border-color', to);
		});
	});

	wp.customize('clp_input-border_color_focus', function (value) {
		value.bind(function (to) {
			$(
				'.login input[type="text"], .login input[type="password"], .login input[type="checkbox"]'
			).focus(function () {
				$(this).css('border-color', to);
			});
			$(
				'.login input[type="text"], .login input[type="password"], .login input[type="checkbox"]'
			).blur(function () {
				var blurColor = getCustomizerValue('clp_input-border_color');
				$(this).css('border-color', blurColor);
			});
		});
	});

	wp.customize('clp_input-border_width', function (value) {
		value.bind(function (to) {
			var color = getCustomizerValue('clp_input-border_color');
			$('.login input[type="text"], .login input[type="password"]').css(
				'border',
				to + 'px solid ' + color
			);
		});
	});

	wp.customize('clp_input-border_radius', function (value) {
		value.bind(function (to) {
			$(
				'.login input[type="text"], .login input[type="password"], .login input[type="checkbox"]'
			).css('border-radius', to + 'px');
		});
	});
	wp.customize('clp_input-text_indent', function (value) {
		value.bind(function (to) {
			$('.login input[type="text"], .login input[type="password"]').css('text-indent', to + 'px');
		});
	});

	wp.customize('clp_input-remember', function (value) {
		var $forgetmenot = $('.forgetmenot');
		value.bind(function (to) {
			to ? $forgetmenot.fadeIn() : $forgetmenot.fadeOut();
		});
	});

	wp.customize('clp_input-remember_text', function (value) {
		value.bind(function (to) {
			$('.forgetmenot label').text(to);
		});
	});

	wp.customize('clp_input-showpassword', function (value) {
		value.bind(function (to) {
			to ? $('body').removeClass('clp-hide-show-pw') : $('body').addClass('clp-hide-show-pw');
		});
	});

	wp.customize('clp_form_footer-align', function (value) {
		value.bind(function (to) {
			$('#nav, #backtoblog').css('text-align', to);
		});
	});
	wp.customize('clp_form_footer-font_size', function (value) {
		value.bind(function (to) {
			$('.login #nav, .login #backtoblog, .privacy-policy-link').css('font-size', to + 'px');
		});
	});

	wp.customize('clp_form_footer-display_backtoblog', function (value) {
		value.bind(function (to) {
			to ? $('body').removeClass('clp-hide-backtoblog') : $('body').addClass('clp-hide-backtoblog');
		});
	});

	wp.customize('clp_form_footer-backtoblog_text', function (value) {
		value.bind(function (to) {
			$('#backtoblog a').text(to);
		});
	});

	wp.customize('clp_form_footer-display_forgetpassword', function (value) {
		value.bind(function (to) {
			to
				? $('body').removeClass('clp-hide-forgetpassword')
				: $('body').addClass('clp-hide-forgetpassword');
		});
	});

	wp.customize('clp_form_footer-forgetpassword_color', function (value) {
		value.bind(function (to) {
			var hoverColor = getCustomizerValue('clp_form_footer-forgetpassword_color_hover');
			setHoverColor($('#nav a:last-of-type'), 'color', to, hoverColor);
		});
	});

	wp.customize('clp_form_footer-forgetpassword_color_hover', function (value) {
		value.bind(function (to) {
			var color = getCustomizerValue('clp_form_footer-forgetpassword_color');
			setHoverColor($('#nav a:last-of-type'), 'color', color, to);
		});
	});

	wp.customize('clp_form_footer-display_register', function (value) {
		value.bind(function (to) {
			var separator = getCustomizerValue('clp_form_footer-login_link_separator');
			to ? $('body').removeClass('clp-hide-register') : $('body').addClass('clp-hide-register');
			$('#nav').contents()[2].textContent = to ? ' ' + separator + ' ' : '';
		});
	});

	wp.customize('clp_form_footer-register_color', function (value) {
		value.bind(function (to) {
			var hoverColor = getCustomizerValue('clp_form_footer-register_color_hover');
			setHoverColor($('#nav a:first-of-type'), 'color', to, hoverColor);
			$('#nav').css('color', to);
		});
	});

	wp.customize('clp_form_footer-register_color_hover', function (value) {
		value.bind(function (to) {
			var color = getCustomizerValue('clp_form_footer-register_color');
			setHoverColor($('#nav a:first-of-type'), 'color', color, to);
		});
	});
	wp.customize('clp_form_footer-backtoblog_color', function (value) {
		value.bind(function (to) {
			var hoverColor = getCustomizerValue('clp_form_footer-backtoblog_color_hover');
			setHoverColor($('.login #backtoblog a'), 'color', to, hoverColor);
		});
	});

	wp.customize('clp_form_footer-backtoblog_color_hover', function (value) {
		value.bind(function (to) {
			var color = getCustomizerValue('clp_form_footer-backtoblog_color');
			setHoverColor($('.login #backtoblog a'), 'color', color, to);
		});
	});

	wp.customize('clp_form_footer-forgetpassword_text', function (value) {
		value.bind(function (to) {
			$('#nav a:last-of-type').text(to);
		});
	});

	wp.customize('clp_form_footer-display_privacy', function (value) {
		value.bind(function (to) {
			if (to) {
				$('body').removeClass('clp-hide-privacy');
			} else {
				$('body').addClass('clp-hide-privacy');
			}
		});
	});
	wp.customize('clp_form_footer-privacy_color', function (value) {
		value.bind(function (to) {
			var hoverColor = getCustomizerValue('clp_form_footer-privacy_color_hover');
			setHoverColor($('.privacy-policy-link'), 'color', to, hoverColor);
		});
	});

	wp.customize('clp_form_footer-privacy_color_hover', function (value) {
		value.bind(function (to) {
			var color = getCustomizerValue('clp_form_footer-privacy_color');
			setHoverColor($('.privacy-policy-link'), 'color', color, to);
		});
	});

	wp.customize('clp_form_footer-register_text', function (value) {
		value.bind(function (to) {
			$('#nav a:first-of-type').text(to);
		});
	});
	wp.customize('clp_form_footer-login_link_separator', function (value) {
		value.bind(function (to) {
			$('#nav').contents()[2].textContent = ' ' + to + ' ';
		});
	});

	wp.customize('clp_css', function (value) {
		value.bind(function (to) {
			$('#clp-custom-css').html(to);
		});
	});

	wp.customize('clp_messages-text_color', function (value) {
		value.bind(function (to) {
			$('.clp-message').css('color', to);
		});
	});
	wp.customize('clp_messages-border_color', function (value) {
		value.bind(function (to) {
			$('.clp-message').css('border-left-color', to);
		});
	});
	wp.customize('clp_messages-border_color_error', function (value) {
		value.bind(function (to) {
			$('.clp-message').css('border-left-color', to);
		});
	});
	wp.customize('clp_messages-border_color_success', function (value) {
		value.bind(function (to) {
			$('.clp-message').css('border-left-color', to);
		});
	});

	// footer
	wp.customize('clp_footer-enable', function (value) {
		value.bind(function (to) {
			if (to) {
				$('body').removeClass('clp-footer-disabled').addClass('clp-footer-enabled');
			} else {
				$('body').removeClass('clp-footer-enabled').addClass('clp-footer-disabled');
			}
		});
	});

	wp.customize('clp_footer-width', function (value) {
		value.bind(function (to) {
			$('.clp-page-footer').css('width', to + '%');
		});
	});
	wp.customize('clp_footer-padding', function (value) {
		value.bind(function (to) {
			$('.clp-page-footer').css('padding', to + 'px');
		});
	});
	wp.customize('clp_footer-background_color', function (value) {
		value.bind(function (to) {
			$('.clp-page-footer').css('background-color', to);
		});
	});
	wp.customize('clp_footer-text_color', function (value) {
		value.bind(function (to) {
			$('.clp-page-footer').css('color', to);
		});
	});
	wp.customize('clp_footer-link_color', function (value) {
		value.bind(function (to) {
			var hoverColor = getCustomizerValue('clp_footer-link_color_hover');
			setHoverColor($('.clp-page-footer a'), 'color', to, hoverColor);
		});
	});
	wp.customize('clp_footer-link_color_hover', function (value) {
		value.bind(function (to) {
			var color = getCustomizerValue('clp_footer-link_color');
			setHoverColor($('.clp-page-footer a'), 'color', color, to);
		});
	});

	wp.customize('clp_footer-copyright', function (value) {
		value.bind(function (to) {
			var position = getCustomizerValue('clp_footer-copyright_pos');

			$('.clp_footer-copyright').length
				? $('.clp_footer-copyright').html(to)
				: $('.clp-footer-content.' + position).append(
						'<div class="clp_footer-copyright">' + to + '</div>'
				  );
		});
	});

	wp.customize('clp_footer-copyright_pos', function (value) {
		value.bind(function (to) {
			var copyright = $('.clp_footer-copyright').detach();
			$('.clp-footer-content.' + to).append(copyright);
		});
	});

	wp.customize('clp_footer-niteothemes', function (value) {
		value.bind(function (to) {
			var position = getCustomizerValue('clp_footer-niteothemes_pos');
			to
				? $('.clp-footer-content.' + position).append(
						'<div class="clp-niteothemes-msg"><p>Made by <a href="https://wordpress.org/plugins/clp-custom-login-page/">CLP - Custom Login Page</a> / <a href="https://niteothemes.com">NiteoThemes</a></p></div>'
				  )
				: $('.clp-niteothemes-msg').empty();
		});
	});
	wp.customize('clp_footer-niteothemes_pos', function (value) {
		value.bind(function (to) {
			var msg = $('.clp-niteothemes-msg').detach();
			$('.clp-footer-content.' + to).append(msg);
		});
	});
	// send customizer section to focus on click customize preview icon
	$('.clp-customizer-preview button')
		.css('animation-name', 'customize-partial-edit-shortcut-bounce-appear')
		.css('pointer-events', 'auto');
	$('.clp-customizer-preview').on('click', function () {
		wp.customize.preview.send('clp-focus-section', $(this).data('section'));
	});

	function setBackgroundImageById(id, selector) {
		if (!id) {
			$(selector).css('background-image', 'url("#")');
			return;
		}

		var data = {
			action: 'clp_wp_get_attachment_url_ajax',
			id: id,
		};

		$.post(clpCfg.ajaxUrl, data, function (response) {
			$(selector).css('background-image', 'url(' + response + ')');
		});
	}

	function getCustomizerValue(customizerId) {
		var customizerVal = '';

		wp.customize(customizerId, function (value) {
			customizerVal = wp.customize(customizerId).get();
		});

		return customizerVal;
	}

	function setHoverColor($selector, property, color, hoverColor) {
		$selector.css(property, color);
		$selector
			.mouseover(function () {
				$(this).css(property, hoverColor);
			})
			.mouseout(function () {
				$(this).css(property, color);
			});
	}

	function processGoogleFontVariant(font) {
		var font = JSON.parse(font);
		var variant = {};
		variant.family = font.family;
		variant.weight =
			font.selected.variant === 'regular' || font.selected.variant === 'italic'
				? 400
				: font.selected.variant;
		variant.weight = !isNaN(variant.weight) ? variant.weight : variant.weight.slice(0, 3);
		variant.style =
			isNaN(font.selected.variant) && font.selected.variant !== 'regular' ? 'italic' : 'normal';
		WebFont.load({
			google: {
				families: [font.family + ':' + font.selected.variant],
			},
		});

		return variant;
	}
})(jQuery);
